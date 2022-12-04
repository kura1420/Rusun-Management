<?php

namespace App\Http\Controllers;

use App\Helpers\ApiService;
use App\Helpers\Generate;
use App\Helpers\Sanitize;
use App\Http\Requests\StoreApiManagementRequest;
use App\Http\Requests\UpdateApiManagementRequest;
use App\Models\ApiManagement;
use App\Models\Pemilik;
use App\Models\RusunDetail;
use App\Models\RusunOutstandingPenghuni;
use App\Models\RusunPemilik;
use App\Models\RusunPenghuni;
use App\Models\RusunTarif;
use App\Models\RusunUnitDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApiManagementController extends Controller
{

    const TITLE = 'API Manage';
    const FOLDER_VIEW = 'api_manage.';
    const URL = 'api-manage.';

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

        return view(self::FOLDER_VIEW . 'index', compact('title', 'subTitle'));
    }

    public function listData()
    {
        $rows = ApiManagement::latest()
            ->groupBy('reff_id')
            ->get()
            ->map(function ($row) {
                $row->childs = ApiManagement::where('reff_id', $row->reff_id)
                    ->get()
                    ->map(function ($r) {
                        $r->table = $this->getTableTextAttribute($r->table);

                        return $r;
                    });
                $row->reff_id = $row->reff_id_relation;

                return $row;
            });

        return response()->json(['data' => $rows], 200);
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

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle', 'rusuns'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreApiManagementRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreApiManagementRequest $request)
    {
        //
        if ($request->endpoint_rusun_details) {
            ApiManagement::create([
                'reff_id' => $request->reff_id,
                'username' => $request->username,
                'password' => $request->password,
                'table' => $request->table_rusun_details,
                'endpoint' => $request->endpoint_rusun_details,
                'keterangan' => $request->keterangan_rusun_details,
            ]);
        }

        if ($request->endpoint_rusun_tarifs) {
            ApiManagement::create([
                'reff_id' => $request->reff_id,
                'username' => $request->username,
                'password' => $request->password,
                'table' => $request->table_rusun_tarifs,
                'endpoint' => $request->endpoint_rusun_tarifs,
                'keterangan' => $request->keterangan_rusun_tarifs,
            ]);
        }

        if ($request->endpoint_rusun_outstanding_penghunis) {
            ApiManagement::create([
                'reff_id' => $request->reff_id,
                'username' => $request->username,
                'password' => $request->password,
                'table' => $request->table_rusun_outstanding_penghunis,
                'endpoint' => $request->endpoint_rusun_outstanding_penghunis,
                'keterangan' => $request->keterangan_rusun_outstanding_penghunis,
            ]);
        }

        if ($request->endpoint_rusun_pemiliks) {
            ApiManagement::create([
                'reff_id' => $request->reff_id,
                'username' => $request->username,
                'password' => $request->password,
                'table' => $request->table_rusun_pemiliks,
                'endpoint' => $request->endpoint_rusun_pemiliks,
                'keterangan' => $request->keterangan_rusun_pemiliks,
            ]);
        }

        return redirect()
                ->route(self::URL . 'index')
                ->with('success', 'Tambah data berhasil...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ApiManagement  $apiManagement
     * @return \Illuminate\Http\Response
     */
    public function show(ApiManagement $apiManagement)
    {
        //
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ApiManagement  $apiManagement
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = ApiManagement::findOrFail($id);
        $row->rusun_details = ApiManagement::where('reff_id', $row->reff_id)->where('table', 'rusun_details')->first();
        $row->rusun_pemiliks = ApiManagement::where('reff_id', $row->reff_id)->where('table', 'rusun_pemiliks')->first();
        $row->rusun_tarifs = ApiManagement::where('reff_id', $row->reff_id)->where('table', 'rusun_tarifs')->first();
        $row->rusun_outstanding_penghunis = ApiManagement::where('reff_id', $row->reff_id)->where('table', 'rusun_outstanding_penghunis')->first();

        $rusuns = \App\Models\Rusun::orderBy('nama', 'asc')->get();

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row', 'rusuns'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateApiManagementRequest  $request
     * @param  \App\Models\ApiManagement  $apiManagement
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateApiManagementRequest $request, $id)
    {
        //
        if ($request->endpoint_rusun_details) {
            ApiManagement::where([
                    'reff_id' => $request->reff_id,
                    'table' => 'rusun_details',
                ])
                ->update([
                'username' => $request->username,
                'password' => $request->password,
                'table' => $request->table_rusun_details,
                'endpoint' => $request->endpoint_rusun_details,
                'keterangan' => $request->keterangan_rusun_details,
            ]);
        }

        if ($request->endpoint_rusun_tarifs) {
            ApiManagement::where([
                    'reff_id' => $request->reff_id,
                    'table' => 'rusun_tarifs',
                ])
                ->update([
                'username' => $request->username,
                'password' => $request->password,
                'table' => $request->table_rusun_tarifs,
                'endpoint' => $request->endpoint_rusun_tarifs,
                'keterangan' => $request->keterangan_rusun_tarifs,
            ]);
        }

        if ($request->endpoint_rusun_outstanding_penghunis) {
            ApiManagement::where([
                    'reff_id' => $request->reff_id,
                    'table' => 'rusun_outstanding_penghunis',
                ])
                ->update([
                'username' => $request->username,
                'password' => $request->password,
                'table' => $request->table_rusun_outstanding_penghunis,
                'endpoint' => $request->endpoint_rusun_outstanding_penghunis,
                'keterangan' => $request->keterangan_rusun_outstanding_penghunis,
            ]);
        }

        if ($request->endpoint_rusun_pemiliks) {
            ApiManagement::where([
                    'reff_id' => $request->reff_id,
                    'table' => 'rusun_pemiliks',
                ])
                ->update([
                'username' => $request->username,
                'password' => $request->password,
                'table' => $request->table_rusun_pemiliks,
                'endpoint' => $request->endpoint_rusun_pemiliks,
                'keterangan' => $request->keterangan_rusun_pemiliks,
            ]);
        }

        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Perbarui data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ApiManagement  $apiManagement
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $row = ApiManagement::findOrFail($id);

        ApiManagement::where('reff_id', $row->reff_id)->delete();

        return response()->json('Success');
    }

    public function testEndpoint($id)
    {
        $row = ApiManagement::findOrFail($id);

        $res = ApiService::run($row, 'GET', NULL);

        return $res->object();
    }

    public function syncManual($id)
    {
        $row = ApiManagement::findOrFail($id);

        $res = ApiService::run($row, 'GET', NULL);

        $object = $res->object();

        return $this->syncExecute($row, $object);
    }

    protected function getTableTextAttribute($table)
    {
        switch ($table) {
            case 'rusun_details':
                return 'Tower';
                break;

            case 'rusun_tarifs':
                return 'Tarif';
                break;

            case 'rusun_outstanding_penghunis':
                return 'Outstanding Penghuni';
                break;

            case 'rusun_pemiliks':
                return 'Pemilik & Penghuni';
                break;

            // case 'rusun_penghunis':
            //     return 'Penghuni';
            //     break;
            
            default:
                return 'No Defined';
                break;
        }
    }

    protected function syncExecute($row, $object)
    {
        switch ($row->table) {
            case 'rusun_details':
                DB::transaction(function () use ($object, $row) {
                    foreach ($object as $key => $value) {
                        RusunDetail::updateOrCreate(
                            [
                                'nama_tower' => $value->tower,
                                'rusun_id' => $row->reff_id,
                            ],
                            [
                                'jumlah_unit' => $value->unit,
                                // 'jumlah_jenis_unit' => 0,
                                // 'foto' => NULL,
                                // 'jumlah_lantai' => 0,
                                // 'keterangan' => NULL,
                                // 'ukuran_paling_kecil' => NULL,
                                // 'ukuran_paling_besar' => NULL,
                            ]
                        );
                    }

                    $row->update([
                        'last_sync' => Carbon::now(),
                    ]);
                });

                return response()->json('OK', 200);
                break;
            
            case 'rusun_outstanding_penghunis':
                DB::transaction(function () use ($object, $row) {
                    $adalah_pemilik = 0;
                    
                    foreach ($object as $key => $value) {
                        $tower = RusunDetail::firstOrCreate([
                            'nama_tower' => $value->tower,
                            'rusun_id' => $row->reff_id,
                        ]);

                        $pemilik = Pemilik::where('nama', $value->namapenghuni)->first();
                        $rusunPenghuni = RusunPenghuni::where('nama', $value->namapenghuni)
                            ->where('rusun_detail_id', $tower->id)
                            ->where('rusun_id', $tower->rusun_id)
                            ->first();

                        if ($pemilik) {
                            RusunPemilik::updateOrCreate(
                                [
                                    'pemilik_id' => $pemilik->id,
                                    'rusun_detail_id' => $tower->id,
                                    'rusun_id' => $tower->rusun_id
                                ],
                                [
                                    'unit' => $value->unit,
                                ]
                            );

                            $rusunPemilik = RusunPemilik::where([
                                ['pemilik_id', $pemilik->id],
                                ['rusun_detail_id', $tower->id],
                                ['rusun_id', $tower->rusun_id]
                            ])->first();

                            $pp = $rusunPemilik;

                            $adalah_pemilik = 1;
                        }

                        if ($rusunPenghuni) {
                            $pp = $rusunPenghuni;

                            $adalah_pemilik = 0;
                        }

                        $rusunOutstandingPenghuni = RusunOutstandingPenghuni::updateOrCreate(
                            [
                                'adalah_pemilik' => $adalah_pemilik,
                                'pemilik_penghuni_id' => $adalah_pemilik ? $pemilik->id : $pp->id,
                                'rusun_unit_detail_id' => $pp->rusun_unit_detail_id,
                                'rusun_detail_id' => $pp->rusun_detail_id,
                                'rusun_id' => $pp->rusun_id,
                            ],
                            [
                                'total' => $value->outstanding ?? 0,
                            ]
                        );

                        $rusunOutstandingPenghuni->rusun_outstanding_details()
                            ->updateOrCreate(
                                [
                                    'adalah_pemilik' => $adalah_pemilik,
                                    'pemilik_penghuni_id' => $pp->id,
                                    'rusun_unit_detail_id' => $pp->rusun_unit_detail_id,
                                    'rusun_detail_id' => $pp->rusun_detail_id,
                                    'rusun_id' => $pp->rusun_id,
                                ],
                                [
                                    'item' => $key,
                                    'total' => $value->servicecharge,
                                ]
                            );

                        $rusunOutstandingPenghuni->rusun_outstanding_details()
                            ->updateOrCreate(
                                [
                                    'adalah_pemilik' => $adalah_pemilik,
                                    'pemilik_penghuni_id' => $pp->id,
                                    'rusun_unit_detail_id' => $pp->rusun_unit_detail_id,
                                    'rusun_detail_id' => $pp->rusun_detail_id,
                                    'rusun_id' => $pp->rusun_id,
                                ],
                                [
                                    'item' => $key,
                                    'total' => $value->sinkingfund,
                                ]
                            );

                        $rusunOutstandingPenghuni->rusun_outstanding_details()
                            ->updateOrCreate(
                                [
                                    'adalah_pemilik' => $adalah_pemilik,
                                    'pemilik_penghuni_id' => $pp->id,
                                    'rusun_unit_detail_id' => $pp->rusun_unit_detail_id,
                                    'rusun_detail_id' => $pp->rusun_detail_id,
                                    'rusun_id' => $pp->rusun_id,
                                ],
                                [
                                    'item' => $key,
                                    'total' => $value->air,
                                ]
                            );

                        $rusunOutstandingPenghuni->rusun_outstanding_details()
                            ->updateOrCreate(
                                [
                                    'adalah_pemilik' => $adalah_pemilik,
                                    'pemilik_penghuni_id' => $pp->id,
                                    'rusun_unit_detail_id' => $pp->rusun_unit_detail_id,
                                    'rusun_detail_id' => $pp->rusun_detail_id,
                                    'rusun_id' => $pp->rusun_id,
                                ],
                                [
                                    'item' => $key,
                                    'total' => $value->listrik,
                                ]
                            );
                    }

                    $row->update([
                        'last_sync' => Carbon::now(),
                    ]);
                });

                return response()->json('OK', 200);
                break;

            case 'rusun_pemiliks':
                DB::transaction(function () use ($object, $row) {
                    foreach ($object as $key => $value) {
                        if (! empty($value->nama_pemilik) && isset($value->nama_pemilik) && $value->nama_pemilik !== "" && strlen(trim($value->nama_pemilik)) > 0) {
                            $tower = RusunDetail::firstOrCreate([
                                'nama_tower' => $value->tower,
                                'rusun_id' => $row->reff_id,
                            ]);

                            $unit = RusunUnitDetail::firstOrCreate([
                                    'jenis' => $value->tipe,
                                    'rusun_detail_id' => $tower->id,
                                    'rusun_id' => $row->reff_id,
                                ]);

                            // email rules
                            $emailArray = explode(',', $value->email_pemilik);
                            $email = $emailArray[0];

                            unset($emailArray[0]);

                            $emails = implode(', ', $emailArray);

                            // phone rules
                            $checkComma = strpos($value->telepon_pemilik, ',');
                            $checkMinusSpace = strpos($value->telepon_pemilik, ' - ');
                            $checkSlash = strpos($value->telepon_pemilik, '/');

                            $phoneArray = NULL;
                            if ($checkComma) {
                                $phoneArray = explode(',', $value->telepon_pemilik);
                            } elseif ($checkMinusSpace) {
                                $phoneArray = explode(' - ', $value->telepon_pemilik);
                            } elseif ($checkSlash) {
                                $phoneArray = explode('/', $value->telepon_pemilik);
                            }
                            
                                else {
                                    $phone = Sanitize::inputNumber($value->telepon_pemilik);
                                }

                            if (is_array($phoneArray)) {
                                $phone = Sanitize::inputNumber($phoneArray[0]);

                                unset($phoneArray[0]);

                                $phones = implode(', ', $phoneArray);
                            } else {
                                $phones = NULL;
                            }

                            $pemilik = Pemilik::firstOrCreate(
                                [
                                    'nama' => $value->nama_pemilik,
                                    'email' => $email,
                                    'phone' => $phone,
                                ],
                                [
                                    'email_lainnya' => $emails,
                                    'phone_lainnya' => $phones,
                                ]
                            );

                            $pemilik->rusun_pemiliks()
                                ->firstOrCreate([
                                    'rusun_unit_detail_id' => $unit->id,
                                    'rusun_detail_id' => $unit->rusun_detail_id,
                                    'rusun_id' => $unit->rusun_id,
                                    // 'unit' => 
                                ]);

                            if (! empty($value->nama_penyewa) && isset($value->nama_penyewa) && $value->nama_penyewa !== "" && strlen(trim($value->nama_penyewa)) > 0) {
                                $rusunPenghuni = RusunPenghuni::firstOrCreate([
                                        'nama' => $value->nama_penyewa,
                                        'rusun_unit_detail_id' => $unit->id,
                                        'rusun_detail_id' => $unit->rusun_detail_id,
                                        'rusun_id' => $unit->rusun_id,
                                        'pemilik_id' => $pemilik->id,
                                    ]);

                                $username = Generate::randomUsername($value->nama_penyewa);
                                $email = $username . '@domain.com';

                                $checkUser = User::where('username', $username)->orWhere('email', $email)->first();

                                if (! $checkUser) {
                                    $user = User::create([
                                        'name' => $value->nama_penyewa,
                                        'username' => $username,
                                        'email' => $email,
                                        'password' => Hash::make(config('app.user_password_default', 'RusunKT@2022')),
                                        'active' => 1,
                                        'level' => 'penghuni',
                                    ]);

                                    $user->user_mapping()
                                        ->firstOrCreate([
                                            'table' => 'rusun_penghunis',
                                            'reff_id' => $rusunPenghuni->id,
                                        ]);

                                    $user->assignRole('Penghuni');
                                }
                            } else {
                                if ($value->nama_pemilik) {
                                    $username = Generate::randomUsername($value->nama_pemilik);
                                    $email = ! empty($email) ? $email : $username . '@domain.com';
    
                                    $checkUser = User::where('username', $username)->orWhere('email', $email)->first();
    
                                    if (! $checkUser) {
                                        $user = User::create([
                                            'name' => $value->nama_pemilik,
                                            'username' => $username,
                                            'email' => $email,
                                            'password' => Hash::make(config('app.user_password_default', 'RusunKT@2022')),
                                            'active' => 1,
                                            'level' => 'pemilik',
                                        ]);

                                        $user->user_mapping()
                                            ->firstOrCreate([
                                                'table' => 'pemiliks',
                                                'reff_id' => $pemilik->id,
                                            ]);

                                        $user->assignRole('Pemilik');
                                    }
                                }
                            }
                        }
                    }

                    $row->update([
                        'last_sync' => Carbon::now(),
                    ]);
                });

                return response()->json('OK', 200);
                break;

            case 'rusun_tarifs':
                DB::transaction(function () use ($object, $row) {
                    foreach ($object as $key => $value) {
                        RusunTarif::updateOrCreate(
                            [
                                'rusun_id' => $row->reff_id,
                                'item' => $value->item,
                            ],
                            [
                                'tarif' => $value->tarif,
                            ]
                        );
                    }

                    $row->update([
                        'last_sync' => Carbon::now(),
                    ]);
                });

                return response()->json('OK', 200);
                break;
            
            default:
                return abort(404);
                break;
        }
    }
}
