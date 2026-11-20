<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use App\Services\AccountService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller {
  public function index(Request $request): View {
    Gate::authorize('viewAny', User::class);
    $filters = $request->validate([
      'q' => ['nullable', 'string', 'max:100'],
      'role' => ['nullable', Rule::in(AppRole::values())],
      'status' => ['nullable', Rule::in(['active', 'disabled'])],
    ]);

    $users = User::with('roles')
        ->when($filters['q'] ?? null, fn($query, $q) => $query->where(fn($query) => $query->where('name', 'like', '%'.$q.'%')->orWhere('username', 'like', '%'.$q.'%')))
        ->when($filters['role'] ?? null, fn($query, $role) => $query->role($role))
        ->when(($filters['status'] ?? null) === 'active', fn($query) => $query->whereNull('disabled_at'))
        ->when(($filters['status'] ?? null) === 'disabled', fn($query) => $query->whereNotNull('disabled_at'))
        ->orderBy('name')->paginate(20)->withQueryString();

    return view('admin.users.index', ['users' => $users, 'roles' => AppRole::cases(), 'filters' => $filters]);
  }

  public function create(): View {
    Gate::authorize('create', User::class);

    return view('admin.users.form', ['account' => new User(), 'roles' => $this->assignableRoles()]);
  }

  public function store(UserRequest $request, AccountService $accounts): RedirectResponse {
    [$user, $password] = $accounts->create($request->user(), $request->validated());

    return $this->withTemporaryPassword($user, $password, 'Akun berhasil dibuat.');
  }

  public function edit(User $user): View {
    Gate::authorize('update', $user);

    return view('admin.users.form', ['account' => $user->load('roles'), 'roles' => $this->assignableRoles()]);
  }

  public function update(UserRequest $request, User $user, AccountService $accounts): RedirectResponse {
    $rolesChanged = $accounts->update($request->user(), $user, $request->validated());
    if ($rolesChanged && $request->user()->is($user)) {
      Auth::logout();
      $request->session()->invalidate();
      $request->session()->regenerateToken();

      return redirect()->route('login')->with('status', 'Role diperbarui. Silakan masuk kembali.');
    }

    return redirect()->route('users.index')->with('status', 'Akun berhasil diperbarui. Perubahan role mencabut sesi akun terkait.');
  }

  public function resetPassword(Request $request, User $user, AccountService $accounts): RedirectResponse {
    $password = $accounts->resetPassword($request->user(), $user);

    return $this->withTemporaryPassword($user, $password, 'Password sementara dibuat; sesi lama dicabut.');
  }

  public function setActive(Request $request, User $user, AccountService $accounts): RedirectResponse {
    $data = $request->validate(['active' => ['required', 'boolean']]);
    $accounts->setActive($request->user(), $user, (bool) $data['active']);

    return redirect()->route('users.index')->with('status', $data['active'] ? 'Akun diaktifkan.' : 'Akun dinonaktifkan dan sesi lama dicabut.');
  }

  private function assignableRoles(): array {
    return array_values(array_filter(AppRole::cases(), fn($role) => $role !== AppRole::Superadmin || auth()->user()->hasRole('superadmin')));
  }

  private function withTemporaryPassword(User $user, string $password, string $status): RedirectResponse {
    return redirect()->route('users.index')->with([
      'status' => $status, 'temporary_username' => $user->username,
      'temporary_password' => Crypt::encryptString($password),
    ]);
  }
}
