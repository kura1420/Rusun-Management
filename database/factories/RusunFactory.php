<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RusunFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $provinsi = \App\Models\Provinsi::inRandomOrder()->first();
        $kota = \App\Models\Kota::where('province_id', $provinsi->id)->first();
        $kecamatan = \App\Models\Kecamatan::where('regency_id', $kota->id)->first();
        $kelurahan = \App\Models\Desa::where('district_id', $kecamatan->id)->first();

        return [
            //
            'alamat' => $this->faker->address(),
            'kode_pos' => $this->faker->postcode(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'total_tower' => rand(1, 7),
            'total_unit' => rand(100, 900),
            'foto_1' => rand(1, 3) . '.jpg',
            'foto_2' => rand(1, 3) . '.jpg',
            'foto_3' => rand(1, 3) . '.jpg',
            'website' => $this->faker->url(),
            'facebook' => $this->faker->url(),
            'instgram' => $this->faker->url(),
            'email' => $this->faker->unique()->safeEmail(),
            'telp' => rand(1111111, 9999999),
            'province_id' => $provinsi->id,
            'regencie_id' => $kota->id,
            'district_id' => $kecamatan->id,
            'village_id' => $kelurahan ? $kelurahan->id : NULL,
        ];
    }
}
