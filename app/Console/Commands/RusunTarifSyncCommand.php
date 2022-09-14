<?php

namespace App\Console\Commands;

use App\Helpers\ApiService;
use App\Models\ApiManagement;
use App\Models\RusunTarif;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RusunTarifSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rusun:tarifSync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rusun Tarif Sync';

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
            $rows = ApiManagement::where('table', 'rusun_tarifs')->get();

            foreach ($rows as $row) {
                $res = ApiService::run($row, 'GET', NULL);

                $object = $res->object();

                DB::transaction(function () use ($object, $row) {
                    foreach ($object as $key => $value) {
                        RusunTarif::updateOrCreate(
                            [
                                'rusun_id' => $row->reff_id,
                            ],
                            [
                                'item' => $value->item,
                                'tarif' => $value->tarif,
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
