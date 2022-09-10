<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RusunDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'nama_tower' => 'Tower ' . Str::random(3),
            'jumlah_unit' => rand(3, 7),
            'jumlah_jenis_unit' => rand(3, 7),
            'foto' => rand(1, 3) . '.jpg',
            'jumlah_lantai' => rand(2, 10),
            'keterangan' => $this->faker->text(),
            'ukuran_paling_kecil' => rand(5, 25) . ' Meter Persegi',
            'ukuran_paling_besar' => rand(5, 25) . ' Meter Persegi',
        ];
    }
}
