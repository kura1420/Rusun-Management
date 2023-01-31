<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PemilikFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $id_tipe = ['KTP', 'PASSPORT'];

        return [
            //
            'id' => $this->faker->uuid(),
            'nama' => $this->faker->name(),
            'email' => $this->faker->email(),
            'phone' => rand(1111111, 9999999),
            // 'identitas_nomor' => rand(1111111, 9999999),
            // 'identitas_tipe' => $id_tipe[rand(0, 1)],
            // 'identitas_file' => rand(1, 3) . '.jpg',
        ];
    }
}
