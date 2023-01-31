<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RusunFasilitasFactory extends Factory
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
            'jumlah' => rand(1, 3),
            'keterangan' => $this->faker->text(),
            'foto' => rand(1, 3) . '.jpg',
        ];
    }
}
