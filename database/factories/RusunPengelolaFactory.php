<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RusunPengelolaFactory extends Factory
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
            'keterangan' => $this->faker->text(),
            'pengelola_id' => \App\Models\Pengelola::inRandomOrder()->first()->id,
        ];
    }
}
