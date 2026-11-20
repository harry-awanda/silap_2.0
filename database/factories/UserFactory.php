<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory {
  protected static ?string $password;

  public function definition(): array {
    return [
      'name' => fake()->name(),
      'username' => fake()->unique()->userName(),
      'email' => fake()->unique()->safeEmail(),
      'password' => static::$password ??= Hash::make('Password123!'),
      'must_change_password' => false,
      'password_changed_at' => now(),
      'remember_token' => Str::random(60),
    ];
  }
}
