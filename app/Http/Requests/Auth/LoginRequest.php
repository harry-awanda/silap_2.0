<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest {
  public function authorize(): bool {
    return true;
  }

  protected function prepareForValidation(): void {
    $this->merge(['username' => Str::lower(trim((string) $this->input('username')))]);
  }

  public function rules(): array {
    return ['username' => ['required', 'string', 'max:50'], 'password' => ['required', 'string'], 'remember' => ['sometimes', 'boolean']];
  }

  public function authenticate(): void {
    $key = 'login:'.hash('sha256', $this->string('username').'|'.$this->ip());

    if (RateLimiter::tooManyAttempts($key, 5)) {
      throw ValidationException::withMessages(['username' => 'Terlalu banyak percobaan. Coba lagi dalam '.RateLimiter::availableIn($key).' detik.']);
    }

    if (! Auth::attempt($this->only('username', 'password') + ['disabled_at' => null], $this->boolean('remember'))) {
      RateLimiter::hit($key, 60);
      throw ValidationException::withMessages(['username' => 'Username atau password salah, atau akun tidak aktif.']);
    }

    RateLimiter::clear($key);
  }
}
