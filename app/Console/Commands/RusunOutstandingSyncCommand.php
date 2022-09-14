<?php

namespace App\Console\Commands;

use App\Helpers\ApiService;
use App\Models\ApiManagement;
use App\Models\RusunOutstandingPenghuni;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RusunOutstandingSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rusun:outstandingSync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rusun Outstanding Sync';

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

            foreach ($rows as $row) {
                $res = ApiService::run($row, 'GET', NULL);

                $object = $res->object();

                DB::transaction(function () use ($object, $row) {
                    foreach ($object as $key => $value) {
                        $rusunOutstandingPenghuni = RusunOutstandingPenghuni::updateOrCreate(
                            [
                                'pemilik_penghuni_id' => $value->namapenghuni,
                                'rusun_unit_detail_id' => $value->unit,
                                'rusun_detail_id' => $value->tower,
                                'rusun_id' => $row->reff_id,
                            ],
                            [
                                'response' => json_encode($value),
                                'total' => $value->outstanding ?? 0,
                            ]
                        );

                        $rusunOutstandingPenghuni->rusun_outstanding_details()
                            ->updateOrCreate(
                                [
                                    'pemilik_penghuni_id' => $value->namapenghuni,
                                    'rusun_unit_detail_id' => $value->unit,
                                    'rusun_detail_id' => $value->tower,
                                    'rusun_id' => $row->reff_id,
                                ],
                                [
                                    'item' => $key,
                                    'total' => $value->servicecharge,
                                ]
                            );

                        $rusunOutstandingPenghuni->rusun_outstanding_details()
                            ->updateOrCreate(
                                [
                                    'pemilik_penghuni_id' => $value->namapenghuni,
                                    'rusun_unit_detail_id' => $value->unit,
                                    'rusun_detail_id' => $value->tower,
                                    'rusun_id' => $row->reff_id,
                                ],
                                [
                                    'item' => $key,
                                    'total' => $value->sinkingfund,
                                ]
                            );

                        $rusunOutstandingPenghuni->rusun_outstanding_details()
                            ->updateOrCreate(
                                [
                                    'pemilik_penghuni_id' => $value->namapenghuni,
                                    'rusun_unit_detail_id' => $value->unit,
                                    'rusun_detail_id' => $value->tower,
                                    'rusun_id' => $row->reff_id,
                                ],
                                [
                                    'item' => $key,
                                    'total' => $value->air,
                                ]
                            );

                        $rusunOutstandingPenghuni->rusun_outstanding_details()
                            ->updateOrCreate(
                                [
                                    'pemilik_penghuni_id' => $value->namapenghuni,
                                    'rusun_unit_detail_id' => $value->unit,
                                    'rusun_detail_id' => $value->tower,
                                    'rusun_id' => $row->reff_id,
                                ],
                                [
                                    'item' => $key,
                                    'total' => $value->listrik,
                                ]
                            );
                    }
                });

                $row->update([
                    'last_sync' => Carbon::now(),
                ]);
            }

            $this->info('Done...');
        } catch (\Exception $e) {
            Log::error('RusunTarifSyncCommand: ' . $e->getMessage());
            
            $this->error($e->getMessage());
        }
    }
}
