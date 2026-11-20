<?php

namespace App\Console\Commands;

use App\Services\AccountService;
use Database\Seeders\RoleSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class CreateSuperadmin extends Command {
  protected $signature = 'silap:superadmin {--username=superadmin} {--name=Superadmin} {--email=} {--generate-password}';

  protected $description = 'Membuat superadmin awal tanpa mengambil alih akun yang sudah ada.';

  public function handle(AccountService $accounts): int {
    $this->call('db:seed', ['--class' => RoleSeeder::class, '--force' => true]);
    $password = $this->option('generate-password') ? Str::password(20) : $this->secret('Password awal (minimal 12 karakter, huruf besar/kecil dan angka)');
    if (! $this->option('generate-password') && $password !== $this->secret('Ulangi password awal')) {
      $this->error('Konfirmasi password tidak sesuai.');

      return self::FAILURE;
    }

    $identity = [
      'username' => Str::lower(trim((string) $this->option('username'))),
      'name' => $this->option('name'), 'email' => $this->option('email') ?: null,
    ];

    try {
      Validator::make($identity + ['password' => $password], [
        'username' => ['required', 'string', 'min:3', 'max:50', 'regex:/\A[a-z0-9][a-z0-9._-]*\z/'],
        'name' => ['required', 'string', 'max:100'], 'email' => ['nullable', 'email'],
        'password' => ['required', Password::min(12)->letters()->mixedCase()->numbers()],
      ])->validate();

      $user = $accounts->bootstrap($identity, $password);
    } catch (ValidationException $exception) {
      $this->error(collect($exception->errors())->flatten()->implode(' '));

      return self::FAILURE;
    }

    $this->info('Superadmin dibuat: '.$user->username);
    if ($this->option('generate-password')) {
      $this->line('Password sementara (ditampilkan sekali): '.$password);
    }
    $this->warn('Wajib mengganti password saat login pertama. Jangan simpan password di log/repository.');

    return self::SUCCESS;
  }
}
