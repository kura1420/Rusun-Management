<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        
        $this->call([
            // PengelolaSeeder::class,
            // PengembangSeeder::class,
            // RusunSeeder::class,
            // PollingKanidatSeeder::class,
        ]);

        // \App\Models\PengelolaDokumen::factory(50)->create();
        // \App\Models\PengembangDokumen::factory(50)->create();

        // $this->call([
        //     PemilikSeeder::class,
        // ]);

        // \App\Models\RusunPenghuni::factory(30)->create();
        // \App\Models\Program::factory(14)->create();
    }
}
