<?php

namespace App\Helpers;

use App\Models\Pemilik;
use App\Models\RusunDetail;
use App\Models\RusunOutstandingPenghuni;
use App\Models\RusunPemilik;
use App\Models\RusunPenghuni;
use App\Models\RusunTarif;
use App\Models\RusunUnitDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SyncData 
{

    public static function tarif($row, $object)
    {
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

        return TRUE;
    }

    public static function rusunPemilik($row, $object)
    {
        ini_set('max_execution_time', 180); //3 minutes

        DB::transaction(function () use ($object, $row) {
            foreach ($object as $key => $value) {
                $nama_pemilik = trim($value->nama_pemilik);
                $nama_penyewa = trim($value->nama_penyewa);
                $unit = trim($value->unit);
                $tower = trim($value->tower);
                $tipe = trim($value->tipe);
                $telepon_pemilik = trim($value->telepon_pemilik);
                $email_pemilik = trim($value->email_pemilik);
                $bast = trim($value->bast);

                if (strlen($nama_pemilik) > 0) {
                    $tower = RusunDetail::firstOrCreate([
                        'nama_tower' => $tower,
                        'rusun_id' => $row->reff_id,
                    ]);

                    $unit = RusunUnitDetail::firstOrCreate([
                        'keterangan' => $tipe,
                        'jenis' => $unit,
                        'rusun_detail_id' => $tower->id,
                        'rusun_id' => $row->reff_id
                    ]);

                    // sanitize phone
                    $phone = NULL;
                    $phones = NULL;
                    $phoneArray = NULL;

                    $checkComma = strpos($telepon_pemilik, ',');
                    $checkMinusSpace = strpos($telepon_pemilik, ' - ');
                    $checkSlash = strpos($telepon_pemilik, '/');
                    $checkMinus = strpos($telepon_pemilik, '-');
                    
                    if ($checkComma) {
                        $phoneArray = explode(',', $telepon_pemilik);
                    } elseif ($checkMinusSpace) {
                        $phoneArray = explode(' - ', $telepon_pemilik);
                    } elseif ($checkSlash) {
                        $phoneArray = explode('/', $telepon_pemilik);
                    } elseif ($checkMinus) {
                        $phoneArray = explode('-', $telepon_pemilik);
                    }
                      else {
                        $phone = Sanitize::inputNumber($telepon_pemilik);
                    }

                    if (is_array($phoneArray)) {
                        $phone = Sanitize::inputNumber($phoneArray[0]);

                        unset($phoneArray[0]);

                        $phones = implode(', ', $phoneArray);
                    } 

                    // sanitize email
                    $email = NULL;
                    $emails = NULL;

                    $emailArray = explode(',', $email_pemilik);
                    $email = $emailArray[0];

                    unset($emailArray[0]);

                    $emails = implode(', ', $emailArray);

                    Pemilik::updateOrCreate(
                        [
                            'nama' => $nama_pemilik,
                            'email' => $email,
                        ],
                        [
                            'phone' => $phone,
                            'email_lainnya' => $emails,
                            'phone_lainnya' => $phones,
                        ]
                    );

                    $pemilik = Pemilik::where([
                        ['nama', $nama_pemilik],
                        ['email', $email]
                    ])->first();

                    $usernamePemilik = Generate::randomUsername($nama_pemilik);
                    $checkUserPemilik = User::where('email', $email)->first();

                    if (! $checkUserPemilik) {
                        $userPemilik = User::firstOrCreate([
                            'name' => $nama_pemilik,
                            'username' => $usernamePemilik,
                            'email' => $email ?? $usernamePemilik . '@domain.com',
                            'password' => Hash::make(config('app.user_password_default', 'RusunKT@2022')),
                            'active' => 1,
                            'level' => 'pemilik',
                        ]);

                        $userPemilik
                            ->user_mapping()
                            ->firstOrCreate([
                                'table' => 'pemiliks',
                                'reff_id' => $pemilik->id,
                            ]);

                        $userPemilik->assignRole('Pemilik');
                    }

                    $pemilik
                        ->rusun_pemiliks()
                        ->updateOrCreate(
                            [
                                'rusun_unit_detail_id' => $unit->id,
                                'rusun_detail_id' => $unit->rusun_detail_id,
                                'rusun_id' => $unit->rusun_id,
                            ],
                            [
                                'bast' => $bast,
                            ],
                        );

                    if (strlen($nama_penyewa) > 0) {
                        RusunPenghuni::updateOrCreate(
                            [
                                'rusun_unit_detail_id' => $unit->id,
                                'rusun_detail_id' => $unit->rusun_detail_id,
                                'rusun_id' => $unit->rusun_id,
                                'pemilik_id' => $pemilik->id,
                            ],
                            [
                                'nama' => $nama_penyewa,
                            ]
                        );

                        $rusunPenghuni = RusunPenghuni::where([
                            ['rusun_unit_detail_id', $unit->id],
                            ['rusun_detail_id', $unit->rusun_detail_id],
                            ['rusun_id', $unit->rusun_id],
                            ['pemilik_id', $pemilik->id],
                            ['nama', $nama_penyewa],
                        ])->first();

                        $usernamePenghuni = Generate::randomUsername($nama_penyewa);
                        $email = md5($nama_pemilik . $nama_penyewa) . '@domain.com';
                        $checkUserPenghuni = User::where('email', $email)->first();

                        if (! $checkUserPenghuni) {
                            $userPenghuni = User::firstOrCreate([
                                'name' => $nama_penyewa,
                                'username' => $usernamePenghuni,
                                'email' => $email,
                                'password' => Hash::make(config('app.user_password_default', 'RusunKT@2022')),
                                'active' => 1,
                                'level' => 'penghuni',
                            ]);

                            $userPenghuni
                                ->user_mapping()
                                ->firstOrCreate([
                                    'table' => 'rusun_penghunis',
                                    'reff_id' => $rusunPenghuni->id,
                                ]);

                            $userPenghuni->assignRole('Penghuni');
                        }
                    }
                }
            }

            $row->update([
                'last_sync' => Carbon::now(),
            ]);
        });

        return TRUE;
    }

    public static function rusunOutstandingPenghuni($row, $object)
    {
        $result = DB::transaction(function () use ($object, $row) {
            $adalah_pemilik = 0;
            
            $nosync = 0;
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

                $pp = NULL;
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

                if ($pp) {
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
                } else {
                    $nosync++;
                }
            }

            $row->update([
                'last_sync' => Carbon::now(),
            ]);

            return $nosync;
        });

        return $result == 0 ? response()->json('OK', 200) : response()->json('Data Pemilik/Penghuni tidak ditemukan.', 422);
    }

    public static function rusunDetail($row, $object)
    {
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

        return TRUE;
    }

}
