<?php

namespace App\Console\Commands;

use App\Helpers\ApiService;
use App\Helpers\SyncData;
use App\Models\ApiManagement;
use Illuminate\Console\Command;
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

                    SyncData::rusunOutstandingPenghuni($row, $object);
                }
            }

            $this->info('Done...');
        } catch (\Exception $e) {
            Log::error('SyncRusunOutStandingPenghuni: ' . $e->getMessage());
            
            $this->error($e->getMessage());
        }
    }
}
