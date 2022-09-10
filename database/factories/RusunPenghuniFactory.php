<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RusunPenghuniFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $rusunUntilDetail = \App\Models\RusunPemilik::inRandomOrder()->first();

        return [
            //
            'id' => $this->faker->uuid(),
            'nama' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => rand(1111111, 9999999),
            'status' => NULL,
            'identitas_tipe' => NULL,
            'identitas_file' => NULL,
            'identitas_nomor' => NULL,
            'tanggal_masuk' => NULL,
            'tanggal_keluar' => NULL,
            'pemilik_id' => $rusunUntilDetail->pemilik_id,
            'rusun_unit_detail_id' => $rusunUntilDetail->rusun_unit_detail_id,
            'rusun_detail_id' => $rusunUntilDetail->rusun_detail_id,
            'rusun_id' => $rusunUntilDetail->rusun_id,
        ];
    }
}
