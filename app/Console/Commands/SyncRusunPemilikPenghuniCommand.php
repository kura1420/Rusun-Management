<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use App\Helpers\ApiService;
use App\Helpers\Generate;
use App\Helpers\Sanitize;
use App\Models\ApiManagement;
use App\Models\Pemilik;
use App\Models\RusunDetail;
use App\Models\RusunPenghuni;
use App\Models\RusunUnitDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SyncRusunPemilikPenghuniCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:rusunpp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Sync Rusun Pemilik & Penghuni with API Manage';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $rows = ApiManagement::where('table', 'rusun_pemiliks')->get();

            foreach ($rows as $row) {
                $res = ApiService::run($row, 'GET', NULL);

                if ($res->ok()) {
                    $object = $res->object();

                    // untuk debug
                    // collect($object)
                    //     ->where('nama_pemilik', 'Fiona F Pattinasarany')
                    //     ->dd();

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
                }
            }

            $this->info('Done...');
        } catch (\Exception $e) {
            Log::error('SyncRusunTarifCommand: ' . $e->getMessage());
            
            $this->error($e->getMessage());
        }
    }
}
