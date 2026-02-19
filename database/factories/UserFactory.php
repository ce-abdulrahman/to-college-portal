<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'code' => fake()->unique()->numerify('######'),
            'phone' => null,
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'student',
            'status' => 1,
            'rand_code' => (string) fake()->unique()->numerify('######'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Additional state placeholder for compatibility with older tests.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => []);
    }
}
