<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreP3srsKegiatanAnggotaRequest;
use App\Http\Requests\UpdateP3srsKegiatanAnggotaRequest;
use App\Models\P3srsKegiatanAnggota;
use App\Models\P3srsKegiatanJadwal;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;

class P3srsKegiatanAnggotaController extends Controller
{

    const TITLE = 'P3SRS - Anggota';
    const FOLDER_VIEW = 'p3srs_anggota.';
    const URL = 'p3srs-anggota.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return abort(404);
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
        $p3srs_kegiatan_jadwal = P3srsKegiatanJadwal::with([
            'rusuns',
            'p3srs_kegiatans',
        ])
        ->where('id', $p3srs_kegiatan_jadwal_id)
        ->firstOrFail();

        if ($p3srs_kegiatan_jadwal->tanggal < Carbon::today()) {
            return abort(403, 'Tanggal kegiatan sudah kedaluwarsa.');
        }

        $title = self::TITLE;
        $subTitle = 'Anggota Kegiatan ' . $p3srs_kegiatan_jadwal->p3srs_kegiatans->nama;

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'p3srs_kegiatan_jadwal',));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreP3srsKegiatanAnggotaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreP3srsKegiatanAnggotaRequest $request)
    {
        //
        $input = $request->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\P3srsKegiatanAnggota  $p3srsKegiatanAnggota
     * @return \Illuminate\Http\Response
     */
    public function show(P3srsKegiatanAnggota $p3srsKegiatanAnggota)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\P3srsKegiatanAnggota  $p3srsKegiatanAnggota
     * @return \Illuminate\Http\Response
     */
    public function edit(P3srsKegiatanAnggota $p3srsKegiatanAnggota)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateP3srsKegiatanAnggotaRequest  $request
     * @param  \App\Models\P3srsKegiatanAnggota  $p3srsKegiatanAnggota
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateP3srsKegiatanAnggotaRequest $request, P3srsKegiatanAnggota $p3srsKegiatanAnggota)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\P3srsKegiatanAnggota  $p3srsKegiatanAnggota
     * @return \Illuminate\Http\Response
     */
    public function destroy(P3srsKegiatanAnggota $p3srsKegiatanAnggota)
    {
        //
    }
}
