<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'      => $this->faker->name(),
            'last_name' => $this->faker->lastName(),
            'sur_name'  => $this->faker->lastName(),
            'birthday'  => $this->faker->dateTimeBetween('1980-01-01', '2000-12-31'),
            'rfc'       => $this->faker->regexify('[A-Za-z0-9]{18}'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
