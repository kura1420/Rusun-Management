<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RusunUnitDetailFactory extends Factory
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
            'ukuran' => rand(5, 25) . ' Meter Persegi',
            'jumlah' => rand(1, 10),
            'foto' => rand(1, 3) . '.jpg',
            'keterangan' => $this->faker->text(),
            // 'rusun_detail_id' => \App\Models\RusunDetail::inRandomOrder()->first()->id,
            // 'rusun_id' => \App\Models\Rusun::inRandomOrder()->first()->id,
        ];
    }
}
