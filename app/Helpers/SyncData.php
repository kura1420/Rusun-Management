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
        DB::transaction(function () use ($object, $row) {
            foreach ($object as $key => $value) {
                if (! empty($value->nama_pemilik) && isset($value->nama_pemilik) && $value->nama_pemilik !== "" && strlen(trim($value->nama_pemilik)) > 0) {
                    $tower = RusunDetail::firstOrCreate([
                        'nama_tower' => $value->tower,
                        'rusun_id' => $row->reff_id,
                    ]);

                    $unit = RusunUnitDetail::firstOrCreate([
                            'jenis' => $value->unit,
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

        return TRUE;
    }

    public static function rusunOutstandingPenghuni($row, $object)
    {
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

        return TRUE;
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
