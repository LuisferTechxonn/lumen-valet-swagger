<?php

namespace Database\Factories;

use App\Models\Cars;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CarsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cars::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'make' => $this->faker->firstName,
            'model' => $this->faker->firstName,
            'year' => random_int(1970,2019)
        ];
    }
}
