<?php

namespace Database\Seeders;

use App\Models\Pengembang;
use App\Models\PengembangKontak;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengembangSeeder extends Seeder
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
            Pengembang::factory()
                ->count(10)
                ->sequence(fn ($sq) => ['nama' => 'Pengelola ' . $sq->index])
                ->has(PengembangKontak::factory(3), 'pengembang_kontaks')
                ->create();
        });
    }
}
