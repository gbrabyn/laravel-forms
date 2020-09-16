<?php

namespace Database\Factories\Repository;

use App\Repository\PersonExperience;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonExperienceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonExperience::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sessionToken' => '9999999999999999999999999999999999999999',
            'lastEdit' => $this->faker->dateTime(),
            'fullName' => $this->faker->name,
            'email' => $this->faker->email,
            'address' => $this->faker->address,
            'countryId' => $this->faker->randomDigit,
            'languages' => [],
            'additionalLanguages' => [],
            'experience' => [],
        ];
    }
}
