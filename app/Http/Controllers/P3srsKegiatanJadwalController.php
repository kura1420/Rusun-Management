<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreP3srsKegiatanJadwalRequest;
use App\Http\Requests\UpdateP3srsKegiatanJadwalRequest;
use App\Models\P3srsKegiatanJadwal;

class P3srsKegiatanJadwalController extends Controller
{

    const TITLE = 'P3SRS - Jadwal';
    const FOLDER_VIEW = 'p3srs_jadwal.';
    const URL = 'p3srs-jadwal.';

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

        $rows = P3srsKegiatanJadwal::with([
                'rusuns',
                'p3srs_kegiatans',
            ])
            ->orderBy('created_at')
            ->get()
            ->map(fn($row) => [
                $row->rusuns->nama,
                $row->p3srs_kegiatans->nama,
                $row->tanggal,
                $row->lokasi,
                '<nobr>' . 
                    '<a href="'.route(self::URL .'show', $row->id).'" class="btn btn-success btn-sm" title="Detail"><i class="fas fa-eye"></i> Detail</a> ' .
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
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
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, null, ['orderable' => false]],
        ];

        return view(self::FOLDER_VIEW . 'index', compact('title', 'subTitle', 'heads', 'config'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $title = self::TITLE;
        $subTitle = 'Tambah Data';

        $rusuns = \App\Models\Rusun::orderBy('nama', 'asc')->get();
        $kegiatans = \App\Models\P3srsKegiatan::orderBy('nama', 'asc')->get();

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'rusuns', 'kegiatans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreP3srsKegiatanJadwalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreP3srsKegiatanJadwalRequest $request)
    {
        //
        $input = $request->all();

        $kegiatanCheck = \App\Models\P3srsKegiatan::where('id', $input['p3srs_kegiatan_id'])->first();

        if (!$kegiatanCheck) {
            $kegiatan = \App\Models\P3srsKegiatan::create([
                'nama' => $input['p3srs_kegiatan_id'],
            ]);

            $input['p3srs_kegiatan_id'] = $kegiatan->id;
        }

        unset($input['files']);

        P3srsKegiatanJadwal::create($input);

        return response()->json('Success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\P3srsKegiatanJadwal  $p3srsKegiatanJadwal
     * @return \Illuminate\Http\Response
     */
    public function show(P3srsKegiatanJadwal $p3srsKegiatanJadwal)
    {
        //
        return $p3srsKegiatanJadwal;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\P3srsKegiatanJadwal  $p3srsKegiatanJadwal
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $rusuns = \App\Models\Rusun::orderBy('nama', 'asc')->get();
        $kegiatans = \App\Models\P3srsKegiatan::orderBy('nama', 'asc')->get();

        $row = P3srsKegiatanJadwal::with([
            'rusuns',
            'p3srs_kegiatans',
        ])->findOrFail($id);

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'rusuns', 'kegiatans'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateP3srsKegiatanJadwalRequest  $request
     * @param  \App\Models\P3srsKegiatanJadwal  $p3srsKegiatanJadwal
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateP3srsKegiatanJadwalRequest $request, $id)
    {
        //
        $input = $request->all();

        $kegiatanCheck = \App\Models\P3srsKegiatan::where('id', $input['p3srs_kegiatan_id'])->first();

        if (!$kegiatanCheck) {
            $kegiatan = \App\Models\P3srsKegiatan::create([
                'nama' => $input['p3srs_kegiatan_id'],
            ]);

            $input['p3srs_kegiatan_id'] = $kegiatan->id;
        }

        unset($input['files']);

        P3srsKegiatanJadwal::findOrFail($id)->update($input);

        return response()->json('Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\P3srsKegiatanJadwal  $p3srsKegiatanJadwal
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        P3srsKegiatanJadwal::findOrFail($id)->delete();

        return response()->json('Success');
    }
}
