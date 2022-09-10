<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PengembangDokumenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $rusunPengembang = \App\Models\RusunPengembang::inRandomOrder()->first();
        $tersedia = $this->faker->boolean();

        return [
            //
            'tersedia' => $tersedia,
            'file' => $tersedia ? rand(1, 3) . '.pdf' : NULL,
            'keterangan' => $this->faker->text(),
            'dokumen_id' => \App\Models\Dokumen::inRandomOrder()->first()->id,
            'pengembang_id' => $rusunPengembang->pengembang_id,
            'rusun_id' => $rusunPengembang->rusun_id,
        ];
    }
}
