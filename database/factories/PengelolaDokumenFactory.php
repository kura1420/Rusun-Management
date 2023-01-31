<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PengelolaDokumenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $rusunPengelola = \App\Models\RusunPengelola::inRandomOrder()->first();
        $tersedia = $this->faker->boolean();

        return [
            //
            'tersedia' => $tersedia,
            'file' => $tersedia ? rand(1, 3) . '.pdf' : NULL,
            'keterangan' => $this->faker->text(),
            'dokumen_id' => \App\Models\Dokumen::inRandomOrder()->first()->id,
            'pengelola_id' => $rusunPengelola->pengelola_id,
            'rusun_id' => $rusunPengelola->rusun_id,
        ];
    }
}
