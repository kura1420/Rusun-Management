<?php

namespace Database\Seeders;

use App\Models\Pengelola;
use App\Models\PengelolaKontak;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengelolaSeeder extends Seeder
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
            Pengelola::factory()
                ->count(10)
                ->sequence(fn ($sq) => ['nama' => 'Pengelola ' . $sq->index])
                ->has(PengelolaKontak::factory(3), 'pengelola_kontaks')
                ->create();
        });
    }
}
