<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use App\Helpers\ApiService;
use App\Helpers\Sanitize;
use App\Models\ApiManagement;
use App\Models\Pemilik;
use App\Models\RusunDetail;
use App\Models\RusunUnitDetail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
                            $tower = RusunDetail::firstOrCreate([
                                'nama_tower' => $value->tower,
                                'rusun_id' => $row->reff_id,
                            ]);

                            $unit = RusunUnitDetail::firstOrCreate([
                                    'jenis' => $value->tipe,
                                    'rusun_detail_id' => $tower->id,
                                    'rusun_id' => $row->reff_id,
                                ]);

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

                            $pemilik = Pemilik::firstOrCreate([
                                'nama' => $value->nama_pemilik,
                                'email' => $email,
                                'phone' => $phone,
                                'email_lainnya' => $emails,
                                'phone_lainnya' => $phones,
                            ]);

                            $pemilik->rusun_pemiliks()
                                ->firstOrCreate([
                                    'rusun_unit_detail_id' => $unit->id,
                                    'rusun_detail_id' => $unit->rusun_detail_id,
                                    'rusun_id' => $unit->rusun_id,
                                    // 'unit' => 
                                ]);

                            if (! empty($value->nama_penyewa)) {
                                $pemilik->rusun_penghunis()
                                    ->firstOrCreate([
                                        'nama' => $value->nama_penyewa,
                                        'rusun_unit_detail_id' => $unit->id,
                                        'rusun_detail_id' => $unit->rusun_detail_id,
                                        'rusun_id' => $unit->rusun_id,
                                    ]);
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
