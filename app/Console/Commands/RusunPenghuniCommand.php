<?php

namespace App\Console\Commands;

use App\Helpers\ApiService;
use App\Models\RusunPenghuni;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RusunPenghuniCommand extends Command
{
    protected $api = NULL;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rusun:penghuni';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rusun Penghuni Auto Sync';

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
                    RusunPenghuni::updateOrCreate(
                        [
                            'email' => $value->email,
                        ],
                        [
                            'id' => uniqid(),
                            'nama' => $value->name,
                            'phone' => $value->phone,
                            'rusun_id' => \App\Models\Rusun::inRandomOrder()->first()->id,
                        ]
                    );
                }
            }
    
            $this->info('Done');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
