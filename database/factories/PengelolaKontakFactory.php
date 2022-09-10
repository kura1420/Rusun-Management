<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PengelolaKontakFactory extends Factory
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
            'nama' => $this->faker->name(),
            'handphone' => rand(1111111, 9999999),
            'email' => $this->faker->unique()->safeEmail(),
            'posisi' => $this->faker->jobTitle(),
        ];
    }
}
