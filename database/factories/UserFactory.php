<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_code' => $this->faker->unique()->randomNumber(5),
            'name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'second_last_name' => $this->faker->lastName,
            'ci' => $this->faker->numerify('########'),
            'issued' => $this->faker->city,
            'gender' => $this->faker->randomElement(['masculino', 'femenino', 'otro']),
            'birthdate' => $this->faker->date(),
            'email' => $this->faker->unique()->safeEmail,
            'home_phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'office' => $this->faker->jobTitle,
            'document' => $this->faker->word,
            'photo' => $this->faker->imageUrl(),
            'personal_email' => $this->faker->unique()->safeEmail,
            'profession' => $this->faker->jobTitle,
            'date_of_admission' => $this->faker->date(),
            'assigned_office' => $this->faker->word,
            'salary' => $this->faker->randomNumber(5),
            'post' => $this->faker->word,
            'observation' => $this->faker->sentence,
            'state' => $this->faker->boolean,
            'user_id' => $this->faker->unique()->randomNumber(5),
            'password' => bcrypt('password') // Cambia 'password' por el valor deseado para las contraseñas
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
