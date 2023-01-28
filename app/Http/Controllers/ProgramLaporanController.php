<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProgramLaporanRequest;
use App\Http\Requests\UpdateProgramLaporanRequest;
use App\Models\ProgramLaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProgramLaporanController extends Controller
{

    const TITLE = 'Program Laporan Kegiatan';
    const FOLDER_VIEW = 'program_laporan.';
    const URL = 'program-laporan.';

    protected $sessionUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->sessionUser = auth()->user();

            return $next($request);
        });
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $program_kegiatan_id = $request->program_kegiatan_id ?? NULL;

        $programKegiatan = \App\Models\ProgramKegiatan::where('id', $program_kegiatan_id)->firstOrFail();

        $title = self::TITLE;
        $subTitle = 'List Data';

        $rows = ProgramLaporan::orderBy('created_at')
            ->where('program_kegiatan_id', $program_kegiatan_id)
            ->get()
            ->map(fn($row) => [
                $row->judul,
                $row->tanggal,
                '<nobr>' . 
                    '<a href="'.route(self::URL .'show', $row->id).'" class="btn btn-success btn-sm" title="Edit"><i class="fas fa-eye"></i> Detail</a> ' .
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
                '</nobr>',
            ]);

        $heads = [
            'Judul',
            'Tanggal',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 5],
        ];
        
        $config = [
            'data' => $rows,
        ];

        return view(self::FOLDER_VIEW . 'index', compact('title', 'subTitle', 'heads', 'config', 'programKegiatan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $program_kegiatan_id = $request->program_kegiatan_id ?? NULL;

        $programKegiatan = \App\Models\ProgramKegiatan::where('id', $program_kegiatan_id)->firstOrFail();

        $totalSuara = 0;
        $totalPemilikPenghuni = 0;
        $pollingKanidatCount = 0;
        $grups = [];
        $pollingKanidats = [];
        $pollingKanidat = NULL;

        if ($programKegiatan->template == 'polling') {
            $totalSuara = \App\Models\PollingKanidat::where([
                ['program_id', $programKegiatan->program_id],
                ['rusun_id', $programKegiatan->rusun_id],
            ])->count();

            $grups = \App\Models\ProgramKanidat::orderBy('created_at')
                ->where('program_id', $programKegiatan->program_id)
                // ->where('grup_status', 1)
                ->groupBy('grup_nama')
                ->select('id', 'grup_id', 'grup_nama', DB::raw('COUNT(grup_nama) as total'), 'grup_status', 'program_id')
                ->get()
                ->map(function ($grup) use ($totalSuara) {
                    $count = $grup->polling_kanidats()->count();

                    $grup->total_suara = $count;

                    if ($totalSuara > 0) {
                        $grup->total_suara_percent = round(($count / $totalSuara) * 100, 2);
                    } else {
                        $grup->total_suara_percent = 0;
                    }

                    return $grup;
                });

            $kanidats = \App\Models\ProgramKanidat::where('program_id', $programKegiatan->program_id)
                // ->where('grup_status', 1)
                ->pluck('pemilik_penghuni_id');

            $rusunPenghuni = \App\Models\RusunPenghuni::where('rusun_id', $programKegiatan->rusun_id);
                // ->whereNotIn('id', $kanidats);
            $rusunPenghuniCount = $rusunPenghuni->count();
            $rusunPenghuniPemilik = $rusunPenghuni->pluck('rusun_pemilik_id');

            $rusunPemilikCount = \App\Models\RusunPemilik::whereNotIn('id', $rusunPenghuniPemilik)
                // ->whereNotIn('pemilik_id', $kanidats)
                ->count();

            $totalPemilikPenghuni = $rusunPenghuniCount + $rusunPemilikCount;

            $getPollingKanidat = \App\Models\PollingKanidat::latest('waktu')->where('program_id', $programKegiatan->program_id);

            $pollingKanidat = $getPollingKanidat->first();
            $pollingKanidatCount = $getPollingKanidat->count();
            $pollingKanidats = \App\Models\PollingKanidat::latest('waktu')->where('program_id', $programKegiatan->program_id)->get();
        }

        $title = self::TITLE;
        $subTitle = 'Tambah Data';

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'programKegiatan', 'totalSuara', 'totalPemilikPenghuni', 'pollingKanidatCount', 'grups', 'pollingKanidats', 'pollingKanidat'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProgramLaporanRequest $request)
    {
        //
        $input = $request->all();

        $programKegiatan = \App\Models\ProgramKegiatan::where('id', $request->program_kegiatan_id)->firstOrFail();

        $input['rusun_id'] = $programKegiatan->rusun_id;
        $input['program_id'] = $programKegiatan->program_id;

        $insertDokumentasi = [];
        $files = $input['dokumentasis'] ?? [];

        unset($input['files'], $input['dokumentasis']);

        if (count($files) > 0) {
            for ($i=0; $i < count($files); $i++) { 
                $type = $files[$i]->extension();
                $filename = md5(uniqid()) . '.' . $type;

                $files[$i]->storeAs(
                    str_replace('.', '', self::FOLDER_VIEW),
                    $filename,
                    'local',
                );

                $insertDokumentasi[] = [
                    'filename' => $filename,
                    'type' => $type,
                    'program_id' => $programKegiatan->program_id,
                    'rusun_id' => $programKegiatan->rusun_id,
                    'program_kegiatan_id' => $programKegiatan->id,
                ];
            }
        }

        DB::transaction(function () use ($input, $files, $insertDokumentasi) {
            $programLaporan = ProgramLaporan::create($input);

            if (count($files) > 0) {
                $programLaporan->program_laporan_dokumens()->createMany($insertDokumentasi);
            }
        });

        return redirect()
            ->route(self::URL . 'index', ['program_kegiatan_id' => $request->program_kegiatan_id])
            ->with('success', 'Tambah data berhasil...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProgramLaporan  $programLaporan
     * @return \Illuminate\Http\Response
     */
    public function show(ProgramLaporan $programLaporan)
    {
        //
        return $programLaporan;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProgramLaporan  $programLaporan
     * @return \Illuminate\Http\Response
     */
    public function edit(ProgramLaporan $programLaporan)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = $programLaporan;

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProgramLaporan  $programLaporan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProgramLaporanRequest $request, ProgramLaporan $programLaporan)
    {
        //
        $input = $request->all();

        $row = $programLaporan;
        
        $insertDokumentasi = [];
        $files = $input['dokumentasis'] ?? [];

        unset($input['files'], $input['dokumentasis']);

        if (count($files) > 0) {
            for ($i=0; $i < count($files); $i++) { 
                $type = $files[$i]->extension();
                $filename = md5(uniqid()) . '.' . $type;

                $files[$i]->storeAs(
                    str_replace('.', '', self::FOLDER_VIEW),
                    $filename,
                    'local',
                );

                $insertDokumentasi[] = [
                    'filename' => $filename,
                    'type' => $type,
                    'program_id' => $row->program_id,
                    'rusun_id' => $row->rusun_id,
                    'program_kegiatan_id' => $row->id,
                ];
            }
        }

        if (count($files)>0) {
            $row->program_laporan_dokumens()->createMany($insertDokumentasi);
        }

        $row->update($input);

        return redirect()
            ->route(self::URL . 'index', ['program_kegiatan_id' => $request->program_kegiatan_id])
            ->with('success', 'Perbarui data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProgramLaporan  $programLaporan
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProgramLaporan $programLaporan)
    {
        //
        $row = $programLaporan;

        $row->program_laporan_dokumens()->delete();

        $row->delete();

        return response()->json('Success');
    }

    public function dokumentasiViewFile($id, $filename)
    {
        $row = \App\Models\ProgramLaporanDokumen::where([
            ['id', $id],
            ['filename', $filename],
        ])->firstOrFail();

        $file = Storage::path(str_replace('.', '', self::FOLDER_VIEW) . '/' . $row->filename);

        return response()->file($file);
    }

    public function dokumentasiDestroy($id)
    {
        $row = \App\Models\ProgramLaporanDokumen::findOrFail($id);

        Storage::delete(str_replace('.', '', self::FOLDER_VIEW) . '/' . $row->filename);

        $row->delete();

        return response()->json('Success');
    }
}
