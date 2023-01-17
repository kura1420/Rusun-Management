<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProgramFactory extends Factory
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
            'nama' => $this->faker->sentence(),
            'status' => 2,
            'keterangan' => $this->faker->paragraph($nbSentences = 3, $variableNbSentences = true),
            'file' => '1.png',
            'periode' => 3,
            'tahun' => 2023,
            'publish' => 1,
            'rusun_id' => '3a1ed721-dfe6-4df1-995c-ea775f739e20',
            'publish_at' => $this->faker->dateTime('now', 'Asia/Jakarta'),
            'slug' => $this->faker->slug(),
        ];
    }
}
