<?php

namespace App\Services;

use App\Models\AccountAudit;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AccountService
{
    public function create(User $actor, array $data): array
    {
        return DB::transaction(function () use ($actor, $data) {
            $this->lockAdministratorState();
            $actor->refresh()->load('roles');
            Gate::forUser($actor)->authorize('create', User::class);
            $this->assertRoleAssignment($actor, $data['roles']);
            $password = Str::password(20);
            $user = User::create([
                'name' => $data['name'], 'username' => $data['username'],
                'email' => $data['email'] ?? null, 'password' => $password,
                'must_change_password' => true,
            ]);
            $user->syncRoles($data['roles']);
            $this->audit($actor, $user, 'created', ['roles' => $data['roles']]);

            return [$user, $password];
        });
    }

    public function update(User $actor, User $user, array $data): bool
    {
        return DB::transaction(function () use ($actor, $user, $data) {
            $this->lockAdministratorState();
            $actor->refresh()->load('roles');
            $user->refresh()->load('roles');
            Gate::forUser($actor)->authorize('update', $user);
            $this->assertRoleAssignment($actor, $data['roles']);
            $before = $user->getRoleNames()->all();
            $removedCore = array_diff(array_intersect($before, ['admin', 'superadmin']), $data['roles']);

            if ($actor->is($user) && $removedCore !== []) {
                throw ValidationException::withMessages(['roles' => 'Anda tidak boleh mencabut role administrator dari akun sendiri.']);
            }

            $this->assertAdministratorRemains($user, $data['roles'], $user->disabled_at === null);
            $user->update(['name' => $data['name'], 'username' => $data['username'], 'email' => $data['email'] ?? null]);
            $user->syncRoles($data['roles']);

            if (array_diff($before, $data['roles']) !== [] || array_diff($data['roles'], $before) !== []) {
                $this->revokeSessions($user);
            }

            $this->audit($actor, $user, 'updated', ['old_roles' => $before, 'new_roles' => $data['roles']]);

            return array_diff($before, $data['roles']) !== [] || array_diff($data['roles'], $before) !== [];
        });
    }

    public function resetPassword(User $actor, User $user): string
    {
        return DB::transaction(function () use ($actor, $user) {
            User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $actor->refresh()->load('roles');
            $user->refresh()->load('roles');
            Gate::forUser($actor)->authorize('resetPassword', $user);
            $password = Str::password(20);
            $user->update(['password' => $password, 'must_change_password' => true, 'password_changed_at' => null]);
            $this->revokeSessions($user);
            $this->audit($actor, $user, 'password_reset');

            return $password;
        });
    }

    public function setActive(User $actor, User $user, bool $active): void
    {
        DB::transaction(function () use ($actor, $user, $active) {
            $this->lockAdministratorState();
            $actor->refresh()->load('roles');
            $user->refresh()->load('roles');
            Gate::forUser($actor)->authorize('setActive', $user);
            $this->assertAdministratorRemains($user, $user->getRoleNames()->all(), $active);
            $oldActive = $user->disabled_at === null;
            $user->update(['disabled_at' => $active ? null : now()]);
            $this->revokeSessions($user);
            $this->audit($actor, $user, $active ? 'activated' : 'disabled', ['was_active' => $oldActive]);
        });
    }

    public function changePassword(User $user, string $password, string $sessionId, string $currentPassword): void
    {
        DB::transaction(function () use ($user, $password, $sessionId, $currentPassword) {
            User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $user->refresh();
            if ($user->disabled_at !== null || ! Hash::check($currentPassword, $user->password)) {
                throw ValidationException::withMessages(['current_password' => 'Akses atau password akun telah berubah. Masuk kembali.']);
            }
            $user->update(['password' => $password, 'must_change_password' => false, 'password_changed_at' => now()]);
            $this->revokeSessions($user, $sessionId);
            $this->audit($user, $user, 'password_changed');
        });
    }

    public function bootstrap(array $identity, string $password): User
    {
        return DB::transaction(function () use ($identity, $password) {
            $this->lockAdministratorState();

            if (User::role('superadmin')->whereNull('disabled_at')->exists()) {
                throw ValidationException::withMessages(['username' => 'Superadmin aktif sudah tersedia. Gunakan pengelolaan akun untuk perubahan berikutnya.']);
            }

            if (User::where('username', $identity['username'])->exists()) {
                throw ValidationException::withMessages(['username' => 'Username sudah digunakan; akun lama tidak diambil alih.']);
            }

            $user = User::create($identity + ['password' => $password, 'must_change_password' => true]);
            $user->assignRole('superadmin');
            $this->audit(null, $user, 'superadmin_bootstrapped');

            return $user;
        });
    }

    private function lockAdministratorState(): void
    {
        // Kunci role inti untuk menserialisasi perubahan administrator tanpa mengunci seluruh tabel user.
        DB::table('roles')->whereIn('name', ['admin', 'superadmin'])->orderBy('id')->lockForUpdate()->get(['id']);
    }

    private function assertRoleAssignment(User $actor, array $roles): void
    {
        abort_if(in_array('superadmin', $roles, true) && ! $actor->hasRole('superadmin'), 403);
    }

    private function assertAdministratorRemains(User $user, array $roles, bool $active): void
    {
        if ($user->disabled_at !== null) {
            return;
        }

        foreach (['superadmin', 'administrator'] as $kind) {
            $core = $kind === 'superadmin' ? ['superadmin'] : ['superadmin', 'admin'];
            if (! $user->hasAnyRole($core) || ($active && array_intersect($core, $roles) !== [])) {
                continue;
            }

            if (User::role($core)->whereNull('disabled_at')->count() <= 1) {
                throw ValidationException::withMessages(['roles' => 'Minimal satu '.$kind.' aktif harus tetap tersedia.']);
            }
        }
    }

    private function revokeSessions(User $user, ?string $except = null): void
    {
        DB::table('sessions')->where('user_id', $user->id)
            ->when($except, fn ($query) => $query->where('id', '!=', $except))->delete();
        $user->forceFill(['remember_token' => Str::random(60)])->save();
    }

    private function audit(?User $actor, User $user, string $action, array $metadata = []): void
    {
        AccountAudit::create([
            'actor_id' => $actor?->id, 'user_id' => $user->id,
            'action' => $action, 'metadata' => $metadata, 'created_at' => now(),
        ]);
    }
}
