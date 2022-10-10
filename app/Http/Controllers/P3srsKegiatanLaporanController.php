<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreP3srsKegiatanLaporanRequest;
use App\Http\Requests\UpdateP3srsKegiatanLaporanRequest;
use App\Models\P3srsKegiatanJadwal;
use App\Models\P3srsKegiatanLaporan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class P3srsKegiatanLaporanController extends Controller
{

    const TITLE = 'P3SRS - Laporan';
    const FOLDER_VIEW = 'p3srs_kegiatan_laporan.';
    const FOLDER_DOCUMENT = 'p3srs_kegiatan_dokumen/';
    const URL = 'p3srs-kegiatan-laporan.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $title = self::TITLE;
        $subTitle = 'List Data';

        $rows = P3srsKegiatanJadwal::orderBy('created_at')
            ->get()
            ->map(fn($row) => [
                $row->rusuns->nama,
                $row->p3srs_kegiatans->nama,
                $row->tanggal,
                $row->lokasi,
                $row->status ? '<nobr>' . 
                    '<a href="'.route(self::URL .'show', $row->id).'" class="btn btn-success btn-sm" title="Detail"><i class="fas fa-folder"></i> Detail</a> ' .
                '</nobr>' :
                '<nobr>' . 
                    '<a href="'.route(self::URL .'show', $row->id).'" class="btn btn-success btn-sm" title="Detail"><i class="fas fa-folder"></i> Detail</a> ' .
                    '<a href="'.route(self::URL .'create').'?p3srs_kegiatan_jadwal_id='.$row->id.'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Tulis</a> ' .
                '</nobr>',
            ]);

        $heads = [
            'Rusun',
            'Kegiatan',
            'Tanggal',
            'Lokasi',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 5],
        ];
        
        $config = [
            'data' => $rows,
        ];

        return view(self::FOLDER_VIEW . 'index', compact('title', 'subTitle', 'heads', 'config'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $p3srs_kegiatan_jadwal_id = $request->p3srs_kegiatan_jadwal_id ?? NULL;
        $p3srs_kegiatan_jadwal = P3srsKegiatanJadwal::where('id', $p3srs_kegiatan_jadwal_id)
        ->firstOrFail();

        // if ($p3srs_kegiatan_jadwal->tanggal < Carbon::today()) {
        //     return abort(403, 'Tanggal kegiatan sudah kedaluwarsa.');
        // }

        $title = self::TITLE;
        $subTitle = 'Laporan ' . $p3srs_kegiatan_jadwal->p3srs_kegiatans->nama;

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'p3srs_kegiatan_jadwal',));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreP3srsKegiatanLaporanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreP3srsKegiatanLaporanRequest $request)
    {
        //
        $input = $request->all();

        $p3srs_kegiatan_jadwal = P3srsKegiatanJadwal::where('id', $input['p3srs_kegiatan_jadwal_id'])->firstOrFail();

        $input['p3srs_kegiatan_id'] = $p3srs_kegiatan_jadwal->p3srs_kegiatan_id;
        $input['rusun_id']  = $p3srs_kegiatan_jadwal->rusun_id;

        $insertDokumentasi = [];
        $files = $input['dokumentasis'] ?? [];

        unset($input['files'], $input['dokumentasis']);
        
        if (count($files) > 0) {
            for ($i=0; $i < count($files); $i++) { 
                $type = $files[$i]->extension();
                $filename = md5(uniqid()) . '.' . $type;

                $files[$i]->storeAs(
                    self::FOLDER_DOCUMENT,
                    $filename,
                    'local',
                );

                $insertDokumentasi[] = [
                    'filename' => $filename,
                    'type' => $type,
                    'p3srs_kegiatan_jadwal_id' => $input['p3srs_kegiatan_jadwal_id'],
                    'p3srs_kegiatan_id' => $p3srs_kegiatan_jadwal->p3srs_kegiatan_id,
                    'rusun_id' => $p3srs_kegiatan_jadwal->rusun_id,
                ];
            }
        }

        DB::transaction(function () use ($input, $files, $insertDokumentasi) {
            $row = P3srsKegiatanLaporan::create($input);

            if (count($files)>0) {
                $row->p3srs_kegiatan_dokumentasis()->createMany($insertDokumentasi);
            }
        });

        return redirect()
            ->route(self::URL . 'show', $p3srs_kegiatan_jadwal->id)
            ->with('success', 'Tambah data berhasil...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\P3srsKegiatanLaporan  $p3srsKegiatanLaporan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Detail Data';

        $row = P3srsKegiatanJadwal::findOrFail($id);

        $groupKanidats = \App\Models\P3srsKegiatanKanidat::where('p3srs_kegiatan_jadwal_id', $id)
            ->select('grup_id', 'grup_nama')
            ->distinct()
            ->orderBy('grup_nama')
            ->get();

        $row->p3srs_kegiatan_laporans = $row->p3srs_kegiatan_laporans->map(function ($p3srs_kegiatan_laporan) {
            $p3srs_kegiatan_laporan->p3srs_kegiatan_dokumentasis = $p3srs_kegiatan_laporan->p3srs_kegiatan_dokumentasis()->get();

            return $p3srs_kegiatan_laporan;
        });

        $terpilihs = \App\Models\P3srsKegiatanKanidat::where('p3srs_kegiatan_jadwal_id', $id)
            ->where('terpilih', 1)
            ->get()
            ->map(function ($terpilih) {
                $terpilih->profile = $terpilih->pemilik_penghuni_profile;
                $terpilih->p3srs_jabatans = $terpilih->p3srs_jabatans;

                return $terpilih;
            });

        $groupTerpilih = collect($terpilihs)->unique('grup_nama')->first();

        return view(self::FOLDER_VIEW . 'show', compact('title', 'subTitle', 'row', 'groupKanidats', 'groupTerpilih', 'terpilihs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\P3srsKegiatanLaporan  $p3srsKegiatanLaporan
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $row = P3srsKegiatanLaporan::findOrFail($id);

        $title = self::TITLE;
        $subTitle = 'Laporan ' . $row->p3srs_kegiatans->nama;

        // if ($row->p3srs_kegiatan_jadwals->tanggal < Carbon::today()) {
        //     return abort(403, 'Tanggal kegiatan sudah kedaluwarsa.');
        // }

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row',));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateP3srsKegiatanLaporanRequest  $request
     * @param  \App\Models\P3srsKegiatanLaporan  $p3srsKegiatanLaporan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateP3srsKegiatanLaporanRequest $request, $id)
    {
        //
        $input = $request->all();

        $row = P3srsKegiatanLaporan::findOrFail($id);

        $insertDokumentasi = [];
        $files = $input['dokumentasis'] ?? [];

        unset($input['files'], $input['dokumentasis']);

        if (count($files) > 0) {
            for ($i=0; $i < count($files); $i++) { 
                $type = $files[$i]->extension();
                $filename = md5(uniqid()) . '.' . $type;

                $files[$i]->storeAs(
                    self::FOLDER_DOCUMENT,
                    $filename,
                    'local',
                );

                $insertDokumentasi[] = [
                    'filename' => $filename,
                    'type' => $type,
                    'p3srs_kegiatan_jadwal_id' => $input['p3srs_kegiatan_jadwal_id'],
                    'p3srs_kegiatan_id' => $row->p3srs_kegiatan_id,
                    'rusun_id' => $row->rusun_id,
                ];
            }
        }

        if (count($files)>0) {
            $row->p3srs_kegiatan_dokumentasis()->createMany($insertDokumentasi);
        }

        $row->update($input);

        return redirect()
            ->route(self::URL . 'show', $row->p3srs_kegiatan_jadwal_id)
            ->with('success', 'Perbarui data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\P3srsKegiatanLaporan  $p3srsKegiatanLaporan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $row = P3srsKegiatanLaporan::findOrFail($id);

        $row->p3srs_kegiatan_dokumentasis()->delete();
        
        $row->delete();

        return response()->json('Success');
    }

    public function dokumentasiViewFile($id, $filename)
    {
        $row = \App\Models\P3srsKegiatanDokumentasi::where([
            ['id', $id],
            ['filename', $filename],
        ])->firstOrFail();

        $file = storage_path('app/' . self::FOLDER_DOCUMENT . $row->filename);

        return response()->file($file);
    }
}
