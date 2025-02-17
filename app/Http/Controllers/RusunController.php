<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRusunRequest;
use App\Http\Requests\UpdateRusunRequest;
use App\Models\Rusun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RusunController extends Controller
{

    const TITLE = 'Rusun';
    const FOLDER_VIEW = 'rusun.';
    const FOLDER_FOTO = 'rusun/foto';
    const URL = 'rusun.';

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
    public function index()
    {
        //
        $title = self::TITLE;
        $subTitle = 'List Data';

        $user = $this->sessionUser;

        $rows = Rusun::orderBy('created_at')
            ->when($user, function ($query, $user) {
                if ($user->level == 'rusun') {
                    $sessionData = session()->get('rusun');

                    $query->where('id', $sessionData->id);
                }

                if ($user->level == 'pemda') {
                    $sessionData = session()->get('pemda');

                    $query
                        ->where('province_id', $sessionData->province_id)
                        ->where('regencie_id', $sessionData->regencie_id);
                }
            })
            ->get()
            ->map(fn($row) => [
                $row->nama,
                // $row->total_tower,
                // $row->total_unit,
                $row->kotas->name ?? NULL,
                $row->kecamatans->name ?? NULL,
                $row->desas->name ?? NULL,
                '<nobr>' . 
                    '<a href="'.route(self::URL .'show', $row->id).'" class="btn btn-success btn-sm" title="Detail"><i class="fas fa-folder"></i> Detail</a> ' .
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
                '</nobr>',
            ]);

        $heads = [
            'Nama',
            // 'Total Tower',
            // 'Total Unit',
            'Kota',
            'Kecamatan',
            'Kelurahan',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 10],
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
        if (! $this->sessionUser->can('create', Rusun::class)) {
            return abort(403, "User does not have the right roles");
        }

        $title = self::TITLE;
        $subTitle = 'Tambah Data';

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle',));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRusunRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRusunRequest $request)
    {
        //
        $input = $request->all();

        $pengembangs = json_decode($input['pengembangs'], TRUE);
        $pengelolas = json_decode($input['pengelolas'], TRUE);

        unset($input['pengembangs'], $input['pengelolas']);

        $foto_1 = NULL;
        $foto_2 = NULL;
        $foto_3 = NULL;

        if ($request->foto_1) {
            $foto_1 = md5(uniqid()) . '.' . $request->foto_1->extension();

            $input['foto_1'] = $foto_1;

            $request->file('foto_1')
                ->storeAs(
                    self::FOLDER_FOTO,
                    $foto_1,
                    'public',
                );
        }

        if ($request->foto_2) {
            $foto_2 = md5(uniqid()) . '.' . $request->foto_2->extension();

            $input['foto_2'] = $foto_2;

            $request->file('foto_2')
                ->storeAs(
                    self::FOLDER_FOTO,
                    $foto_2,
                    'public',
                );
        }

        if ($request->foto_3) {
            $foto_3 = md5(uniqid()) . '.' . $request->foto_3->extension();

            $input['foto_3'] = $foto_3;

            $request->file('foto_3')
                ->storeAs(
                    self::FOLDER_FOTO,
                    $foto_3,
                    'public',
                );
        }

        $insertPengembangs = [];
        if (count($pengembangs)>0) {
            for ($i=0; $i < count($pengembangs); $i++) {
                $insertPengembangs[] = [
                    'keterangan' => $pengembangs[$i][4],
                    'pengembang_id' => $pengembangs[$i][0],
                ];
            }
        }

        $insertPengelolas = [];
        if (count($pengelolas)>0) {
            for ($i=0; $i < count($pengelolas); $i++) {
                $insertPengelolas[] = [
                    'keterangan' => $pengelolas[$i][4],
                    'pengelola_id' => $pengelolas[$i][0],
                ];
            }
        }

        $return = DB::transaction(function () use ($input, $insertPengembangs, $insertPengelolas) {
            $row = Rusun::create($input);
            
            if (count($insertPengembangs)>0) {
                $row->rusun_pengembangs()->createMany($insertPengembangs);
            }

            if (count($insertPengelolas)>0) {
                $row->rusun_pengelolas()->createMany($insertPengelolas);
            }

            return $row;
        });

        return response()->json($return);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rusun  $rusun
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Detail Data';

        $row = Rusun::findOrFail($id);
        $row->total_tower = $row->rusun_details->count();
        $row->total_unit = $row->rusun_details->sum('jumlah_unit');

        if (! $this->sessionUser->can('view', $row)) {
            return abort(403, "User does not have the right roles");
        }

        $row->foto_1 = $row->foto_1 ? asset('storage/' . self::FOLDER_FOTO . '/' . $row->foto_1) : NULL;
        $row->foto_2 = $row->foto_2 ? asset('storage/' . self::FOLDER_FOTO . '/' . $row->foto_2) : NULL;
        $row->foto_3 = $row->foto_3 ? asset('storage/' . self::FOLDER_FOTO . '/' . $row->foto_3) : NULL;

        $row->rusun_pengelolas = $row->rusun_pengelolas->map(function ($rusun_pengelola) {
            $pengelola = \App\Models\Pengelola::where('id', $rusun_pengelola->pengelola_id)->first();

            $rusun_pengelola->nama = $pengelola->nama;
            $rusun_pengelola->telp = $pengelola->telp;
            $rusun_pengelola->email = $pengelola->email;
            $rusun_pengelola->aksi = '<button type="button" class="btn btn-success btn-xs btnModalPengelola" value="'. $rusun_pengelola->pengelola_id .'"><i class="fa fa-file"></i> Cek Dokumen</button>';

            return $rusun_pengelola;
        });

        $row->rusun_pengembangs = $row->rusun_pengembangs->map(function ($rusun_pengembang) {
            $pengembang = \App\Models\Pengembang::where('id', $rusun_pengembang->pengembang_id)->first();

            $rusun_pengembang->nama = $pengembang->nama;
            $rusun_pengembang->telp = $pengembang->telp;
            $rusun_pengembang->email = $pengembang->email;
            $rusun_pengembang->aksi = '<button type="button" class="btn btn-success btn-xs btnModalPengembang" value="'. $rusun_pengembang->pengembang_id .'"><i class="fa fa-file"></i> Cek Dokumen</button>';

            return $rusun_pengembang;
        });

        $fotos = collect([$row->foto_1, $row->foto_2, $row->foto_3])
            ->filter(function ($value, $key) {
                return $value !== NULL;
            })
            ->all();

        return view(self::FOLDER_VIEW . 'show', compact('title', 'subTitle', 'row', 'fotos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pengelola  $pengelola
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = Rusun::findOrFail($id);

        if (! $this->sessionUser->can('update', $row)) {
            return abort(403, "User does not have the right roles");
        }

        $row->rusun_pengelolas = $row->rusun_pengelolas->map(function ($rusun_pengelola) {
            $pengelola = \App\Models\Pengelola::where('id', $rusun_pengelola->pengelola_id)->first();

            $rusun_pengelola->nama = $pengelola->nama;
            $rusun_pengelola->telp = $pengelola->telp;
            $rusun_pengelola->email = $pengelola->email;
            $rusun_pengelola->aksi = '<button type="button" class="btn btn-danger btn-sm btnDeletePengelola" id="'.route(self::URL . 'pengelolaDestroy', $rusun_pengelola->id).'" value="'.$rusun_pengelola->id.'">Hapus</button>';

            return $rusun_pengelola;
        });

        $row->rusun_pengembangs = $row->rusun_pengembangs->map(function ($rusun_pengembang) {
            $pengembang = \App\Models\Pengembang::where('id', $rusun_pengembang->pengembang_id)->first();

            $rusun_pengembang->nama = $pengembang->nama;
            $rusun_pengembang->telp = $pengembang->telp;
            $rusun_pengembang->email = $pengembang->email;
            $rusun_pengembang->aksi = '<button type="button" class="btn btn-danger btn-sm btnDeletePengembang" id="'.route(self::URL . 'pengembangDestroy', $rusun_pengembang->id).'" value="'.$rusun_pengembang->id.'">Hapus</button>';

            return $rusun_pengembang;
        });

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row',));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRusunRequest  $request
     * @param  \App\Models\Rusun  $rusun
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRusunRequest $request, Rusun $rusun)
    {
        //
    }

    public function updateAsStore(UpdateRusunRequest $request, $id)
    {
        $rusun = Rusun::findOrFail($id);

        $input = $request->all();

        $pengembangs = json_decode($input['pengembangs'], TRUE);
        $pengelolas = json_decode($input['pengelolas'], TRUE);

        unset($input['pengembangs'], $input['pengelolas']);

        $foto_1 = $rusun->foto_1;
        $foto_2 = $rusun->foto_2;
        $foto_3 = $rusun->foto_3;

        if ($request->foto_1) {
            $foto_1 = md5(uniqid()) . '.' . $request->foto_1->extension();

            $request->file('foto_1')
                ->storeAs(
                    self::FOLDER_FOTO,
                    $foto_1,
                    'public',
                );

            if ($rusun->foto_1) {
                Storage::delete(self::FOLDER_FOTO . '/' . $rusun->foto_1);
            }
        }

        $input['foto_1'] = $foto_1;

        if ($request->foto_2) {
            $foto_2 = md5(uniqid()) . '.' . $request->foto_2->extension();

            $request->file('foto_2')
                ->storeAs(
                    self::FOLDER_FOTO,
                    $foto_2,
                    'public',
                );

            if ($rusun->foto_2) {
                Storage::delete(self::FOLDER_FOTO . '/' . $rusun->foto_2);
            }
        }

        $input['foto_2'] = $foto_2;

        if ($request->foto_3) {
            $foto_3 = md5(uniqid()) . '.' . $request->foto_3->extension();

            $request->file('foto_3')
                ->storeAs(
                    self::FOLDER_FOTO,
                    $foto_3,
                    'public',
                );

            if ($rusun->foto_3) {
                Storage::delete(self::FOLDER_FOTO . '/' . $rusun->foto_3);
            }
        }

        $input['foto_3'] = $foto_3;

        DB::transaction(function () use ($rusun, $pengembangs, $pengelolas, $input) {
            if (count($pengembangs)>0) {
                for ($i=0; $i < count($pengembangs); $i++) { 
                    \App\Models\RusunPengembang::updateOrCreate(
                        [
                            'rusun_id' => $rusun->id,
                            'pengembang_id' => $pengembangs[$i][0],
                        ],
                        [
                            'keterangan' => $pengembangs[$i][4],
                        ]
                    );                
                }
            }
    
            if (count($pengelolas)>0) {
                for ($i=0; $i < count($pengelolas); $i++) { 
                    \App\Models\RusunPengelola::updateOrCreate(
                        [
                            'rusun_id' => $rusun->id,
                            'pengelola_id' => $pengelolas[$i][0],
                        ],
                        [
                            'keterangan' => $pengelolas[$i][4],
                        ]
                    );
                }
            }
    
            $rusun->update($input);
        });

        return response()->json('Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rusun  $rusun
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $row = Rusun::findOrFail($id);

        if (! $this->sessionUser->can('delete', $row)) {
            return response()->json("User does not have the right roles", 403);
        }

        $details = $row->rusun_details->count();
        $unitDetails = $row->rusun_unit_details->count();
        $fasilitas = $row->rusun_fasilitas->count();
        $pengelolas = $row->rusun_pengelolas->count();
        $pengembangs = $row->rusun_pengembangs->count();
        $tarifs = $row->rusun_tarifs->count();
        $outstandings = $row->rusun_outstanding_penghunis->count();

        if (
            empty($details) &&
            empty($unitDetails) &&
            empty($fasilitas) &&
            empty($pengelolas) &&
            empty($pengembangs) &&
            empty($tarifs) &&
            empty($outstandings)
        ) {
            $row->delete();

            return response()->json('Success');
        } else {
            return response()->json('Data tidak bisa di hapus, karena sudah mempunyai hubungan dibawahnya.', 403);
        }
    }

    public function view_file($id, $filename)
    {
        $row = Rusun::where('id', $id)
            ->where('file', $filename)
            ->first();

        $file = storage_path('app/' . self::FOLDER_FOTO . '/' . $row->file);

        return response()->file($file);
    }

    public function pengelolaDestroy($id)
    {
        \App\Models\RusunPengelola::findOrFail($id);

        return response()->json('Success');
    }

    public function pengelolaDokumen(Request $request, $id)
    {
        $pengelola_id = $request->pengelola_id ?? NULL;

        $rows = \App\Models\PengelolaDokumen::where([
            ['rusun_id', $id],
            ['pengelola_id', $pengelola_id],
        ])
        ->get()
        ->map(function ($row) {
            $row->file = route('pengelola-dokumen.view_file', [$row->id, $row->file]);
            $row->dokumens;
            $row->status = $row->status_text;

            return $row;
        });

        return response()->json($rows);
    }

    public function pengembangDestroy($id)
    {
        \App\Models\RusunPengembang::findOrFail($id);

        return response()->json('Success');        
    }

    public function pengembangDokumen(Request $request, $id)
    {
        $pengembang_id = $request->pengembang_id ?? NULL;

        $rows = \App\Models\PengembangDokumen::where([
            ['rusun_id', $id],
            ['pengembang_id', $pengembang_id],
        ])
        ->get()
        ->map(function ($row) {
            $row->file = route('pengembang-dokumen.view_file', [$row->id, $row->file]);
            $row->dokumens;
            $row->status = $row->status_text;

            return $row;
        });

        return $rows;
    }
}
