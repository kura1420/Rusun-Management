<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RusunPengembangFactory extends Factory
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
            'pengembang_id' => \App\Models\Pengembang::inRandomOrder()->first()->id,
        ];
    }
}
