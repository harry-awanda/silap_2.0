<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
  public function register(): void {}

  public function boot(): void {
    Gate::policy(User::class, UserPolicy::class);
    Paginator::useBootstrapFive();

    // Tidak ada bypass superadmin: input terlambat tetap khusus guru_piket.
    Gate::define('attendance.record-late', fn(User $user) => $user->disabled_at === null && $user->hasRole('guru_piket'));
  }
}
