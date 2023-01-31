<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RusunPemilikFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $rusunUnitDetail = \App\Models\RusunUnitDetail::inRandomOrder()->first();

        return [
            //
            'id' => $this->faker->uuid(),
            'rusun_unit_detail_id' => $rusunUnitDetail->id,
            'rusun_detail_id' => $rusunUnitDetail->rusun_detail_id,
            'rusun_id' => $rusunUnitDetail->rusun_id,
        ];
    }
}
