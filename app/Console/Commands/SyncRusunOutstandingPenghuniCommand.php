<?php

namespace App\Console\Commands;

use App\Helpers\ApiService;
use App\Models\ApiManagement;
use App\Models\Pemilik;
use App\Models\RusunDetail;
use App\Models\RusunOutstandingPenghuni;
use App\Models\RusunPemilik;
use App\Models\RusunPenghuni;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncRusunOutstandingPenghuniCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:rusunoutpp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Sync Rusun Outstanding Pemilik/Penghuni with API Manage';

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
            $rows = ApiManagement::where('table', 'rusun_outstanding_penghunis')->get();

            foreach ($rows as $key => $row) {
                $res = ApiService::run($row, 'GET', NULL);

                if ($res->ok()) {
                    $object = $res->object();

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
                }
            }

            $this->info('Done...');
        } catch (\Exception $e) {
            Log::error('SyncRusunOutStandingPenghuni: ' . $e->getMessage());
            
            $this->error($e->getMessage());
        }
    }
}
