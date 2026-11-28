<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
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
            'nombres' => fake()->firstName(),
            'apellidos' => fake()->lastName().' '.fake()->lastName(),
            'dni' => fake()->unique()->numerify('########'),
            'email' => fake()->unique()->safeEmail(),
            'telefono' => fake()->numerify('9########'),
            'role' => Role::Trabajador,
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function superadmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::Superadmin,
        ]);
    }
}
