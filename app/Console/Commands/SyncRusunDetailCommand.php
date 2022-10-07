<?php

namespace App\Console\Commands;

use App\Helpers\ApiService;
use App\Models\ApiManagement;
use App\Models\RusunDetail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncRusunDetailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:rusundetail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Sync Rusun Detail/Tower with API Manage';

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
            $rows = ApiManagement::where('table', 'rusun_details')->get();

            foreach ($rows as $key => $row) {
                $res = ApiService::run($row, 'GET', NULL);

                if ($res->ok()) {
                    $object = $res->object();

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
                }
            }

            $this->info('Done...');
        } catch (\Exception $e) {
            Log::error('SyncRusunDetailCommand: ' . $e->getMessage());
            
            $this->error($e->getMessage());
        }
    }
}
