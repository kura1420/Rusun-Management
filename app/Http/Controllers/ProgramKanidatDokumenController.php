<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProgramKanidatDokumenRequest;
use App\Http\Requests\UpdateProgramKanidatDokumenRequest;
use App\Models\ProgramKanidatDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramKanidatDokumenController extends Controller
{
    
    const TITLE = 'Program Kanidat Dokumen';
    const FOLDER_VIEW = 'program_kanidat_dokumen.';
    const URL = 'program-kanidat-dokumen.';

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
        $id = $request->program_kanidat_id ?? NULL;
        $user = $this->sessionUser;

        $pemilik_penghuni_id = NULL;
        switch ($user->level) {
            case 'pemilik':
                $pemilik = session()->get('pemilik');

                $pemilik_penghuni_id = $pemilik->id;
                break;

            case 'penghuni':
                $rusunPenghuni = session()->get('rusun_penghuni');
                
                $pemilik_penghuni_id = $rusunPenghuni->id;
                break;
            
            default:
                return abort(404);
                break;
        }

        $title = self::TITLE;
        $subTitle = 'List Data';

        $programKanidat = \App\Models\ProgramKanidat::where([
            ['id', $id],
            ['pemilik_penghuni_id', $pemilik_penghuni_id]
        ])
        ->where('status', '!=', '6')
        ->first();

        if (! $programKanidat) {
            return abort(403, "Anda tidak memilik akses ke halaman ini, silahkan kembali");
        }

        $rows = ProgramKanidatDokumen::where('program_kanidat_id', $programKanidat->id)
            ->get()
            ->map(fn($row) => [
                $row->program_dokumen->nama,
                '<a href="'.route(self::URL .'view_file', [$row->id, $row->file]).'" target="_blank">View</a> ',
                '<nobr>' . 
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
                '</nobr>',
            ]);

        $heads = [
            'Dokumen',
            'File',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 5],
        ];
        
        $config = [
            'data' => $rows,
        ];

        return view(self::FOLDER_VIEW . 'index', compact('title', 'subTitle', 'heads', 'config', 'programKanidat'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $program_kanidat_id = $request->program_kanidat_id ?? NULL;

        $programKanidat = \App\Models\ProgramKanidat::where('id', $program_kanidat_id)->firstOrFail();
        $programDokumens = \App\Models\ProgramDokumen::where('program_id', $programKanidat->program_id)->orderBy('nama')->get();

        $title = self::TITLE;
        $subTitle = 'Tambah Data';

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'programKanidat', 'programDokumens'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProgramKanidatDokumenRequest $request)
    {
        //
        $input = $request->all();

        $filename = md5(uniqid()) . '.' . $request->file->extension();
        $request->file('file')->storeAs(str_replace('.', '', self::FOLDER_VIEW), $filename, 'local');

        $programKanidat = \App\Models\ProgramKanidat::where('id', $request->program_kanidat_id)->firstOrFail();

        $input['file'] = $filename;
        $input['program_kanidat_id'] = $programKanidat->id;
        $input['rusun_unit_detail_id'] = $programKanidat->rusun_unit_detail_id;
        $input['rusun_detail_id'] = $programKanidat->rusun_detail_id;
        $input['pemilik_penghuni_id'] = $programKanidat->pemilik_penghuni_id;
        $input['program_id'] = $programKanidat->program_id;
        $input['rusun_id'] = $programKanidat->rusun_id;
        $input['apakah_pemilik'] = $programKanidat->apakah_pemilik;
        $input['program_jabatan_id'] = $programKanidat->program_jabatan_id;

        ProgramKanidatDokumen::create($input);

        return redirect()
            ->route(self::URL . 'index', ['program_kanidat_id' => $programKanidat->id])
            ->with('success', 'Tambah data berhasil...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProgramKanidatDokumen  $programKanidatDokumen
     * @return \Illuminate\Http\Response
     */
    public function show(ProgramKanidatDokumen $programKanidatDokumen, $id)
    {
        //
        $rows = ProgramKanidatDokumen::where('program_kanidat_id', $id)
            ->get()
            ->map(function ($row) {
                $row->pemilik_penghuni_profile = $row->pemilik_penghuni_profile;
                $row->program_dokumen;
                $row->program_kanidat;

                return $row;
            });

        return response()->json($rows);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProgramKanidatDokumen  $programKanidatDokumen
     * @return \Illuminate\Http\Response
     */
    public function edit(ProgramKanidatDokumen $programKanidatDokumen, $id)
    {
        //
        $row = ProgramKanidatDokumen::findOrFail($id);
        $programDokumens = \App\Models\ProgramDokumen::where('program_id', $row->program_id)->orderBy('nama')->get();

        $title = self::TITLE;
        $subTitle = 'Edit Data';

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'programDokumens'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProgramKanidatDokumen  $programKanidatDokumen
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProgramKanidatDokumenRequest $request, ProgramKanidatDokumen $programKanidatDokumen, $id)
    {
        //
        $input = $request->all();

        $row = ProgramKanidatDokumen::findOrFail($id);

        if ($request->file) {
            $filename = md5(uniqid()) . '.' . $request->file->extension();
            
            $request->file('file')->storeAs(str_replace('.', '', self::FOLDER_VIEW), $filename, 'local');

            Storage::delete(str_replace('.', '', self::FOLDER_VIEW) . '/' . $row->file);

            $input['file'] = $filename;
        } else {
            unset($input['file']);
        }

        $row->update($input);

        return redirect()
            ->route(self::URL . 'index', ['program_kanidat_id' => $request->program_kanidat_id])
            ->with('success', 'Perbarui data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProgramKanidatDokumen  $programKanidatDokumen
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProgramKanidatDokumen $programKanidatDokumen, $id)
    {
        //
        $row = ProgramKanidatDokumen::findOrFail($id);

        if ($row->program_kanidat->grup_status == 0) {
            $row->delete();

            return response()->json('OK', 201);
        } else {
            return response()->json('Data tidak bisa di hapus, karena sudah mempunyai hubungan dibawahnya.', 403);
        }
    }

    public function view_file($id, $filename)
    {
        $row = ProgramKanidatDokumen::where([
            ['id', $id],
            ['file', $filename],
        ])->firstOrFail();

        $path = Storage::path(str_replace('.', '', self::FOLDER_VIEW) . '/' . $row->file);

        return response()->file($path);
    }
}
