<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreP3srsKegiatanAnggotaRequest;
use App\Http\Requests\UpdateP3srsKegiatanAnggotaRequest;
use App\Models\P3srsKegiatanAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class P3srsKegiatanAnggotaController extends Controller
{

    const TITLE = 'P3SRS - Anggota';
    const FOLDER_VIEW = 'p3srs_kegiatan_anggota.';
    const URL = 'p3srs-kegiatan-anggota.';

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
        $title = self::TITLE;
        $subTitle = 'Tambah Data';
        
        $p3srs_jadwal_id = $request->p3srs_jadwal_id ?? NULL;
        $p3srsKegiatanJadwal = \App\Models\P3srsKegiatanJadwal::where('id', $p3srs_jadwal_id)
            ->firstOrFail();

        $rowsPemilikPenghuniKanidats = \App\Models\P3srsKegiatanKanidat::where('p3srs_kegiatan_jadwal_id', $p3srs_jadwal_id)->get();
        $rowsPemilikPenghuniAnggotas = P3srsKegiatanAnggota::where('p3srs_kegiatan_jadwal_id', $p3srs_jadwal_id)->get();

        $listPemilikPenghunis = [];
        foreach ($rowsPemilikPenghuniKanidats as $rowsPemilikPenghuniKanidat) {
            $listPemilikPenghunis[] = $rowsPemilikPenghuniKanidat->pemilik_penghuni_id;
        }

        foreach ($rowsPemilikPenghuniAnggotas as $rowsPemilikPenghuniAnggota) {
            $listPemilikPenghunis[] = $rowsPemilikPenghuniAnggota->pemilik_penghuni_id;
        }

        $pemiliks = \App\Models\RusunPemilik::where('rusun_id', $p3srsKegiatanJadwal->rusun_id)
            ->whereNotIn('id', $listPemilikPenghunis)
            ->get();

        $rusunPenghunis = \App\Models\RusunPenghuni::where('rusun_id', $p3srsKegiatanJadwal->rusun_id)
            ->whereNotIn('id', $listPemilikPenghunis)
            ->get()
            ->map(function ($rusunPenghuni)  {
                return [
                    'id' => $rusunPenghuni->id . '=pgh',
                    'text' => $rusunPenghuni->nama,
                    'phone' => $rusunPenghuni->phone,
                    'email' => $rusunPenghuni->email,
                    'profile' => $rusunPenghuni,
                ];
            });

        $rusunPenghuniPemilikFilter = [];
        if ($rusunPenghunis) {
            foreach ($rusunPenghunis as $rusunPenghuni) {
                $rusunPenghuniPemilikFilter[] = $rusunPenghuni['profile']['pemilik_id'];
            }
        }

        $wargas = collect($pemiliks)
            ->whereNotIn('pemilik_id', $rusunPenghuniPemilikFilter)
            ->map(function ($item, $key) {
                $pemilikProfile = \App\Models\Pemilik::where('id', $item->pemilik_id)->first();

                return [
                    'id' => $item->id . '=pmk',
                    'text' => $pemilikProfile->nama,
                    'phone' => $pemilikProfile->phone,
                    'email' => $pemilikProfile->email,
                    'profile' => $pemilikProfile,
                ];
            })
            ->merge($rusunPenghunis);

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'p3srsKegiatanJadwal', 'wargas', ));
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
        $wargas = json_decode($request->wargas, TRUE);
        $p3srsKegiatanJadwal = \App\Models\P3srsKegiatanJadwal::where('id', $request->p3srs_kegiatan_jadwal_id)->firstOrFail();

        DB::transaction(function () use ($wargas, $p3srsKegiatanJadwal, $request) {
            foreach ($wargas as $key => $value) {
                $apakahPemilik = strstr($value[0], '=pmk');

                P3srsKegiatanAnggota::create([
                    'apakah_pemilik' => $apakahPemilik ? 1 : 0,
                    'pemilik_penghuni_id' => substr($value[0], 0, -4),
                    'p3srs_kegiatan_jadwal_id' => $p3srsKegiatanJadwal->id,
                    'p3srs_kegiatan_id' => $p3srsKegiatanJadwal->p3srs_kegiatan_id,
                    'rusun_id' => $p3srsKegiatanJadwal->rusun_id,
                ]);
            }
        });

        return response()->json('Success');
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
    public function edit($id)
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
    public function destroy($id)
    {
        //
        P3srsKegiatanAnggota::where('p3srs_kegiatan_jadwal_id', $id)->delete();

        return response()->json('Success');
    }
}
