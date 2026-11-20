<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\AccountService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class SuperadminSeeder extends Seeder {
  public function run(): void {
    $data = config('silap.superadmin');
    if (blank($data['password'])) {
      return;
    }

    $data['username'] = Str::lower(trim($data['username']));
    Validator::make($data, [
      'username' => ['required', 'string', 'min:3', 'max:50', 'regex:/\A[a-z0-9][a-z0-9._-]*\z/'],
      'name' => ['required', 'string', 'max:100'],
      'email' => ['nullable', 'email'],
      'password' => ['required', Password::min(12)->letters()->mixedCase()->numbers()],
    ])->validate();

    if (User::where('username', $data['username'])->whereHas('roles', fn($query) => $query->where('name', 'superadmin'))->exists()) {
      return;
    }

    app(AccountService::class)->bootstrap(
      ['username' => $data['username'], 'name' => $data['name'], 'email' => $data['email']],
      $data['password'],
    );
  }
}
