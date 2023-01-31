<?php

namespace Database\Seeders;

use App\Models\Pemilik;
use App\Models\RusunPemilik;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PemilikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::transaction(function () {
            Pemilik::factory()
                ->count(50)
                ->has(
                    RusunPemilik::factory()
                        ->count(rand(1, 3)),
                    'rusun_pemiliks'
                )
                ->create();
        });
    }
}
