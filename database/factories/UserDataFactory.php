<?php

namespace Database\Factories;

use App\Models\UserData;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * @var string
     */
    protected $model = UserData::class;

    /**
     * Define the model's default state.
     * @return array
     */
    public function definition()
    {
        return [
            'firstname' => $this->faker->firstname,
            'lastname'  => $this->faker->lastname,
            'birthday'  => $this->faker->date,
        ];
    }
}
