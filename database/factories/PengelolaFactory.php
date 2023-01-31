<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PengelolaFactory extends Factory
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
            'telp' => rand(1111111, 9999999),
            'email' => $this->faker->unique()->safeEmail(),
            'website' => $this->faker->url(),
            'keterangan' => $this->faker->text(),
            'province_id' => $provinsi->id,
            'regencie_id' => $kota->id,
            'district_id' => $kecamatan->id,
            'village_id' => $kelurahan->id,
        ];
    }
}
