<?php

namespace App\Console\Commands;

use App\Helpers\ApiService;
use App\Models\RusunPemilik;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RusunPemilikCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rusun:pemilik';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rusun Penghuni Sync';

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
            $endpoint = '/users';
            $method = 'GET'; 
            $params = NULL;
            $res = ApiService::run($endpoint, $method, $params);
            
            $objects = $res->object();

            if (count($objects) > 0) {
                foreach ($objects as $key => $value) {
                    RusunPemilik::updateOrCreate(
                        [
                            'id' => $value->id,
                        ],
                        [
                            'nama' => $value->name,
                            'email' => $value->email,                            
                            'phone' => $value->phone,
                            // 'identitas_tipe' => '',
                            // 'identitas_file' => '',
                            // 'identitas_nomor' => '',
                            // 'rusun_unit_detail_id' => '',
                            // 'rusun_detail_id' => '',
                            'rusun_id' => \App\Models\Rusun::inRandomOrder()->first()->id,
                        ]
                    );
                }
            }
    
            $this->info('Done');
        } catch (\Exception $e) {
            Log::error('RusunPemilikCommand: ' . $e->getMessage());

            $this->error($e->getMessage());
        }
    }
}
