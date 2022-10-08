<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreP3srsKegiatanJadwalRequest;
use App\Http\Requests\UpdateP3srsKegiatanJadwalRequest;
use App\Models\P3srsKegiatanJadwal;
use App\Models\P3srsKegiatanKanidat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $rows = P3srsKegiatanJadwal::orderBy('created_at')
            ->get()
            ->map(fn($row) => [
                $row->rusuns->nama,
                $row->p3srs_kegiatans->nama,
                $row->tanggal,
                $row->lokasi,
                $row->status ?
                    '<nobr>' . 
                        '<a href="'.route(self::URL .'show', $row->id).'" class="btn btn-success btn-sm" title="Detail"><i class="fas fa-folder"></i> Detail</a> ' .
                        // '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
                    '</nobr>'
                    : '<nobr>' . 
                    '<a href="'.route(self::URL .'show', $row->id).'" class="btn btn-success btn-sm" title="Detail"><i class="fas fa-folder"></i> Detail</a> ' .
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    // '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
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
    public function show($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Detail Data';

        $row = P3srsKegiatanJadwal::findOrFail($id);

        $row->p3srs_kegiatan_kanidats = $row->p3srs_kegiatan_kanidats->map(function ($p3srs_kegiatan_kanidat) {
            if ($p3srs_kegiatan_kanidat->apakah_pemilik) {
                $p3srs_kegiatan_kanidat->profile = DB::table('rusun_pemiliks')->join('pemiliks', 'rusun_pemiliks.pemilik_id', '=', 'pemiliks.id')
                    ->join('rusun_details', 'rusun_pemiliks.rusun_id', '=', 'rusun_details.rusun_id')
                    ->join('rusun_unit_details', 'rusun_pemiliks.rusun_id', '=', 'rusun_unit_details.rusun_id')
                    ->where('rusun_pemiliks.id', $p3srs_kegiatan_kanidat->pemilik_penghuni_id)
                    ->select([
                        'rusun_pemiliks.id',
                            'pemiliks.nama',
                                'rusun_details.nama_tower',
                                    'rusun_unit_details.jenis'
                    ])
                    ->first();
            } else {
                $p3srs_kegiatan_kanidat->profile = \App\Models\RusunPenghuni::join('rusun_details', 'rusun_penghunis.rusun_id', '=', 'rusun_details.rusun_id')
                    ->join('rusun_unit_details', 'rusun_penghunis.rusun_id', '=', 'rusun_unit_details.rusun_id')
                    ->where('rusun_penghunis.id', $p3srs_kegiatan_kanidat->pemilik_penghuni_id)
                    ->select([
                        'rusun_penghunis.id',
                            'rusun_penghunis.nama',
                                'rusun_details.nama_tower',
                                    'rusun_unit_details.jenis'
                    ])
                    ->first();
            }
            
            return $p3srs_kegiatan_kanidat;
        });

        $row->p3srs_kegiatan_anggotas = $row->p3srs_kegiatan_anggotas->map(function ($p3srs_kegiatan_anggota) {
            if ($p3srs_kegiatan_anggota->apakah_pemilik) {
                $p3srs_kegiatan_anggota->profile = DB::table('rusun_pemiliks')->join('pemiliks', 'rusun_pemiliks.pemilik_id', '=', 'pemiliks.id')
                    ->join('rusun_details', 'rusun_pemiliks.rusun_id', '=', 'rusun_details.rusun_id')
                    ->join('rusun_unit_details', 'rusun_pemiliks.rusun_id', '=', 'rusun_unit_details.rusun_id')
                    ->where('rusun_pemiliks.id', $p3srs_kegiatan_anggota->pemilik_penghuni_id)
                    ->select([
                        'rusun_pemiliks.id',
                            'pemiliks.nama',
                                'rusun_details.nama_tower',
                                    'rusun_unit_details.jenis'
                    ])
                    ->first();
            } else {
                $p3srs_kegiatan_anggota->profile = \App\Models\RusunPenghuni::join('rusun_details', 'rusun_penghunis.rusun_id', '=', 'rusun_details.rusun_id')
                    ->join('rusun_unit_details', 'rusun_penghunis.rusun_id', '=', 'rusun_unit_details.rusun_id')
                    ->where('rusun_penghunis.id', $p3srs_kegiatan_anggota->pemilik_penghuni_id)
                    ->select([
                        'rusun_penghunis.id',
                            'rusun_penghunis.nama',
                                'rusun_details.nama_tower',
                                    'rusun_unit_details.jenis'
                    ])
                    ->first();
            }

            return $p3srs_kegiatan_anggota;
        });

        $collects = collect($row->p3srs_kegiatan_kanidats)
            ->groupBy('grup_id')
            ->sort();

        $groupBys = [];
        if (count($collects)>0) {
            foreach ($collects as $key => $collect) {
                $groupBys[] = [
                    'id' => $key,
                    'text' => collect($collect)
                        ->unique('unique')
                        ->map(function ($item, $key) {
                            return $item->grup_nama;
                        })[0],
                    'terpilih' => collect($collect)
                    ->unique('unique')
                    ->map(function ($item, $key) {
                        return $item->terpilih;
                    })[0],
                    'childrens' => $collect,
                ];
            }
        }

        return view(self::FOLDER_VIEW . 'show', compact('title', 'subTitle', 'row', 'groupBys'));
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

        $row = P3srsKegiatanJadwal::findOrFail($id);

        // if ($row->tanggal < Carbon::today()) {
        //     return abort(403, 'Tanggal kegiatan sudah kedaluwarsa.');
        // }

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
        $p3srsKegiatanJadwal = P3srsKegiatanJadwal::findOrFail($id);
        
        // if ($p3srsKegiatanJadwal->tanggal < Carbon::today()) {
        //     return abort(403, 'Tanggal kegiatan sudah kedaluwarsa.');
        // } else {
            $p3srsKegiatanJadwal->delete();

            return response()->json('Success');
        // }
    }

    public function groupTerpilih(Request $request)
    {
        $id = $request->id ?? NULL;
        $terpilih = $request->terpilih ?? NULL;

        if (isset($id) && isset($terpilih)) {
            $p3srsKegiatanJadwal = P3srsKegiatanJadwal::findOrFail($id)->update(['status' => 1]);

            $kanidatTerpilih = P3srsKegiatanKanidat::where('grup_id', $terpilih)->update(['terpilih' => 1]);

            return response()->json('Success');
        } else {
            return abort(404);
        }
    }
}
