<?php

namespace Database\Seeders;

use App\Models\PengelolaDokumen;
use App\Models\PengembangDokumen;
use App\Models\Rusun;
use App\Models\RusunDetail;
use App\Models\RusunFasilitas;
use App\Models\RusunPengelola;
use App\Models\RusunPengembang;
use App\Models\RusunUnitDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RusunSeeder extends Seeder
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
            Rusun::factory()
                ->count(10)
                ->sequence(fn ($sq) => ['nama' => 'Rusun ' . $sq->index])
                ->has(RusunDetail::factory(5), 'rusun_details')
                ->has(
                    RusunFasilitas::factory()
                        ->count(5)
                        ->state(function (array $attributes, Rusun $rusun) {
                            return [
                                'nama' => 'Fasilitas ' . Str::random(3),
                                'rusun_detail_id' => $rusun->rusun_details()->first()->id
                            ];
                        }),
                    'rusun_fasilitas'
                )         
                ->has(
                    RusunUnitDetail::factory()
                        ->count(15)
                        ->state(function (array $attributes, Rusun $rusun) {
                            return [ 'rusun_detail_id' => $rusun->rusun_details()->first()->id ];
                    }), 'rusun_unit_details'
                )
                ->has(RusunPengelola::factory(5), 'rusun_pengelolas')
                ->has(RusunPengembang::factory(5), 'rusun_pengembangs')
                ->create();
        });
    }
}
