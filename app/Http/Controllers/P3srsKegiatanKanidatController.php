<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreP3srsKegiatanKanidatRequest;
use App\Http\Requests\UpdateP3srsKegiatanKanidatRequest;
use App\Models\P3srsKegiatanKanidat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class P3srsKegiatanKanidatController extends Controller
{

    const TITLE = 'P3SRS - Kanidat';
    const FOLDER_VIEW = 'p3srs_kegiatan_kanidat.';
    const URL = 'p3srs-kegiatan-kanidat.';

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
        $p3srsKegiatanJadwal = \App\Models\P3srsKegiatanJadwal::with([
                'p3srs_kegiatans',
                'rusuns',
            ])
            ->where('id', $p3srs_jadwal_id)
            ->firstOrFail();

        $rowsPemilikPenghuniKanidats = P3srsKegiatanKanidat::where('p3srs_kegiatan_jadwal_id', $p3srs_jadwal_id)->get();

        $listPemilikPenghunis = [];
        foreach ($rowsPemilikPenghuniKanidats as $rowsPemilikPenghuniKanidat) {
            $listPemilikPenghunis[] = $rowsPemilikPenghuniKanidat->pemilik_penghuni_id;
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

        $p3srsJabatans = \App\Models\P3srsJabatan::orderBy('nama')->get();

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'p3srsKegiatanJadwal', 'wargas', 'p3srsJabatans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreP3srsKegiatanKanidatRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreP3srsKegiatanKanidatRequest $request)
    {
        //
        $wargas = json_decode($request->wargas, TRUE);
        $p3srsKegiatanJadwal = \App\Models\P3srsKegiatanJadwal::where('id', $request->p3srs_kegiatan_jadwal_id)->firstOrFail();

        DB::transaction(function () use($request, $wargas, $p3srsKegiatanJadwal) {
            $group_id = md5(uniqid());

            foreach ($wargas as $key => $value) {
                $apakahPemilik = strstr($value[0], '=pmk');

                P3srsKegiatanKanidat::create([
                    'grup_id' => $group_id,
                    'grup_nama' => $request->grup_nama,
                    'apakah_pemilik' => $apakahPemilik ? 1 : 0,
                    'pemilik_penghuni_id' => substr($value[0], 0, -4),
                    'p3srs_jabatan_id' => $value[1],
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
     * @param  \App\Models\P3srsKegiatanKanidat  $p3srsKegiatanKanidat
     * @return \Illuminate\Http\Response
     */
    public function show(P3srsKegiatanKanidat $p3srsKegiatanKanidat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\P3srsKegiatanKanidat  $p3srsKegiatanKanidat
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $groupId)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';
        
        $p3srs_jadwal_id = $request->p3srs_jadwal_id ?? NULL;
        $p3srsKegiatanJadwal = \App\Models\P3srsKegiatanJadwal::with([
                'p3srs_kegiatans',
                'rusuns',
            ])
            ->where('id', $p3srs_jadwal_id)
            ->firstOrFail();

        $rowsPemilikPenghuni = P3srsKegiatanKanidat::where('p3srs_kegiatan_jadwal_id', $p3srs_jadwal_id)->get();

        $removeSelectOptionPemilikPenghunis = [];
        foreach ($rowsPemilikPenghuni as $rowPemilikPenghuni) {
            $removeSelectOptionPemilikPenghunis[] = $rowPemilikPenghuni->pemilik_penghuni_id;
        }

        $pemiliks = \App\Models\RusunPemilik::where('rusun_id', $p3srsKegiatanJadwal->rusun_id)
            ->whereNotIn('id', $removeSelectOptionPemilikPenghunis)
            ->get();

        $rusunPenghunis = \App\Models\RusunPenghuni::where('rusun_id', $p3srsKegiatanJadwal->rusun_id)
            ->whereNotIn('id', $removeSelectOptionPemilikPenghunis)
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

        $p3srsJabatans = \App\Models\P3srsJabatan::orderBy('nama')->get();

        $row = P3srsKegiatanKanidat::with([
            'p3srs_jabatans'
        ])
        ->where('grup_id', $groupId)
        ->get()
        ->map(function ($r) {
            $r->profile = $r->getPemilikPenghuniProfileAttribute();

            return $r;
        });

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'p3srsKegiatanJadwal', 'wargas', 'p3srsJabatans'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateP3srsKegiatanKanidatRequest  $request
     * @param  \App\Models\P3srsKegiatanKanidat  $p3srsKegiatanKanidat
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateP3srsKegiatanKanidatRequest $request, $groupId)
    {
        //
        $wargas = json_decode($request->wargas, TRUE);
        $p3srsKegiatanJadwal = \App\Models\P3srsKegiatanJadwal::where('id', $request->p3srs_kegiatan_jadwal_id)->firstOrFail();
        $row = P3srsKegiatanKanidat::where('grup_id', $groupId)->firstOrFail();

        DB::transaction(function () use ($row, $wargas, $p3srsKegiatanJadwal, $request) {
            foreach ($wargas as $key => $value) {
                $apakahPemilik = strstr($value[0], '=pmk');

                P3srsKegiatanKanidat::updateOrInsert(
                    [
                        'grup_id' => $row->grup_id,
                        'pemilik_penghuni_id' => substr($value[0], 0, -4),
                    ],
                    [
                        'id' => Str::uuid(),
                        'grup_id' => $row->grup_id,
                        'grup_nama' => $request->grup_nama,
                        'apakah_pemilik' => $apakahPemilik ? 1 : 0,
                        'pemilik_penghuni_id' => substr($value[0], 0, -4),
                        'p3srs_jabatan_id' => $value[1],
                        'p3srs_kegiatan_jadwal_id' => $p3srsKegiatanJadwal->id,
                        'p3srs_kegiatan_id' => $p3srsKegiatanJadwal->p3srs_kegiatan_id,
                        'rusun_id' => $p3srsKegiatanJadwal->rusun_id,
                    ]
                );
            }
        });
        

        return response()->json('Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\P3srsKegiatanKanidat  $p3srsKegiatanKanidat
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        P3srsKegiatanKanidat::findOrFail($id)->delete();

        return response()->json('Success');
    }

    public function destroyGroup($groupId)
    {
        P3srsKegiatanKanidat::where('grup_id', $groupId)->delete();

        return response()->json('Success');
    }
}
