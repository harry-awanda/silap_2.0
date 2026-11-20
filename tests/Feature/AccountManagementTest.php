<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\AccountService;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SuperadminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountManagementTest extends TestCase {
  use RefreshDatabase;

  protected function setUp(): void {
    parent::setUp();
    $this->seed(RoleSeeder::class);
  }

  private function user(string $role, array $attributes = []): User {
    return User::factory()->create($attributes)->assignRole($role);
  }

  private function payload(array $overrides = []): array {
    return array_merge(['name' => 'Guru Baru', 'username' => 'gurubaru', 'email' => null, 'roles' => ['guru']], $overrides);
  }

  public function test_admin_can_create_a_multi_role_account_with_a_temporary_password(): void {
    $admin = $this->user('admin');
    $response = $this->actingAs($admin)->post('/users', $this->payload(['roles' => ['guru', 'guru_bk']]));
    $response->assertRedirect(route('users.index'))->assertSessionHasNoErrors();
    $password = Crypt::decryptString(session('temporary_password'));
    $user = User::where('username', 'gurubaru')->firstOrFail();
    $this->assertTrue(Hash::check($password, $user->password));
    $this->assertTrue($user->must_change_password);
    $this->assertTrue($user->hasAllRoles(['guru', 'guru_bk']));
    $this->assertNull($user->email);
    $this->assertDatabaseHas('account_audits', ['action' => 'created', 'actor_id' => $admin->id, 'user_id' => $user->id]);
    $this->get('/users')->assertSee($password);
    $this->get('/users')->assertDontSee($password);
  }

  public function test_non_admin_cannot_manage_accounts_using_direct_urls_or_requests(): void {
    $guru = $this->user('guru');
    $target = $this->user('siswa');
    $this->actingAs($guru)->get('/users/create')->assertForbidden();
    $this->post('/users', $this->payload())->assertForbidden();
    $this->put('/users/'.$target->id, $this->payload())->assertForbidden();
    $this->post('/users/'.$target->id.'/reset-password')->assertForbidden();
    $this->patch('/users/'.$target->id.'/active', ['active' => false])->assertForbidden();
  }

  public function test_admin_cannot_assign_superadmin_or_manage_superadmin_accounts(): void {
    $admin = $this->user('admin');
    $superadmin = $this->user('superadmin');
    $this->actingAs($admin)->post('/users', $this->payload(['roles' => ['superadmin']]))->assertSessionHasErrors('roles.0');
    $this->get('/users/'.$superadmin->id.'/edit')->assertForbidden();
    $this->put('/users/'.$superadmin->id, $this->payload())->assertForbidden();
    $this->post('/users/'.$superadmin->id.'/reset-password')->assertForbidden();
    $this->patch('/users/'.$superadmin->id.'/active', ['active' => false])->assertForbidden();
    $this->assertNull($superadmin->fresh()->disabled_at);
  }

  public function test_unknown_or_duplicate_roles_and_duplicate_usernames_are_rejected(): void {
    $admin = $this->user('admin');
    $this->actingAs($admin)->post('/users', $this->payload(['roles' => ['new_role']]))->assertSessionHasErrors('roles.0');
    $this->post('/users', $this->payload(['roles' => ['guru', 'guru']]))->assertSessionHasErrors('roles.0');
    $this->post('/users', $this->payload(['username' => strtoupper($admin->username)]))->assertSessionHasErrors('username');
    $this->assertDatabaseCount('roles', 9);
  }

  public function test_administrator_cannot_disable_or_remove_own_core_role(): void {
    $admin = $this->user('superadmin');
    $this->actingAs($admin)->patch('/users/'.$admin->id.'/active', ['active' => false])->assertForbidden();
    $this->put('/users/'.$admin->id, $this->payload(['username' => $admin->username, 'roles' => ['guru']]))->assertSessionHasErrors('roles');
    $this->assertTrue($admin->fresh()->hasRole('superadmin'));
    $this->assertNull($admin->fresh()->disabled_at);
  }

  public function test_reset_revokes_sessions_rotates_remember_token_and_does_not_log_the_password(): void {
    $admin = $this->user('admin');
    $target = $this->user('guru');
    DB::table('sessions')->insert(['id' => 'target-session', 'user_id' => $target->id, 'payload' => '', 'last_activity' => time()]);
    $oldToken = $target->remember_token;
    $this->actingAs($admin)->post('/users/'.$target->id.'/reset-password')->assertRedirect(route('users.index'));
    $password = Crypt::decryptString(session('temporary_password'));
    $target->refresh();
    $this->assertTrue(Hash::check($password, $target->password));
    $this->assertTrue($target->must_change_password);
    $this->assertNull($target->password_changed_at);
    $this->assertNotSame($oldToken, $target->remember_token);
    $this->assertDatabaseMissing('sessions', ['id' => 'target-session']);
    $audit = DB::table('account_audits')->where('action', 'password_reset')->first();
    $this->assertStringNotContainsString($password, json_encode($audit));
    $this->assertStringNotContainsString($target->password, json_encode($audit));
  }

  public function test_disable_and_reenable_keep_the_account_and_revoke_old_sessions(): void {
    $admin = $this->user('admin');
    $target = $this->user('siswa');
    DB::table('sessions')->insert(['id' => 'target-session', 'user_id' => $target->id, 'payload' => '', 'last_activity' => time()]);
    $this->actingAs($admin)->patch('/users/'.$target->id.'/active', ['active' => false])->assertRedirect(route('users.index'));
    $this->assertNotNull($target->fresh()->disabled_at);
    $this->assertDatabaseMissing('sessions', ['id' => 'target-session']);
    $this->patch('/users/'.$target->id.'/active', ['active' => true])->assertRedirect(route('users.index'));
    $this->assertNull($target->fresh()->disabled_at);
    $this->assertDatabaseHas('users', ['id' => $target->id]);
  }

  public function test_role_changes_revoke_sessions_and_preserve_other_administrators(): void {
    $admin = $this->user('superadmin');
    $target = $this->user('admin');
    DB::table('sessions')->insert(['id' => 'target-session', 'user_id' => $target->id, 'payload' => '', 'last_activity' => time()]);
    $this->actingAs($admin)->put('/users/'.$target->id, $this->payload(['username' => $target->username, 'roles' => ['guru']]))->assertRedirect(route('users.index'));
    $this->assertTrue($target->fresh()->hasRole('guru'));
    $this->assertFalse($target->fresh()->hasRole('admin'));
    $this->assertDatabaseMissing('sessions', ['id' => 'target-session']);
    $this->assertTrue($admin->fresh()->hasRole('superadmin'));
  }

  public function test_list_filters_and_escaped_output_work(): void {
    $admin = $this->user('admin');
    $this->user('guru', ['name' => '<script>alert(1)</script>', 'username' => 'escaped']);
    $this->user('siswa', ['name' => 'Excluded Person']);
    $this->actingAs($admin)->get('/users?role=guru&q=escaped&status=active')->assertOk()
        ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
        ->assertDontSee('<script>alert(1)</script>', false)->assertDontSee('Excluded Person');
    $this->get('/users/create')->assertOk();
    $this->get('/users/'.$admin->id.'/edit')->assertOk();
  }

  public function test_role_rename_and_user_delete_endpoints_are_not_exposed(): void {
    $admin = $this->user('superadmin');
    $this->actingAs($admin)->put('/roles/1', ['name' => 'renamed'])->assertNotFound();
    $this->delete('/users/'.$admin->id)->assertStatus(405);
    $this->assertDatabaseCount('roles', 9);
  }

  public function test_environment_superadmin_seed_is_idempotent_and_never_resets_a_password(): void {
    config(['silap.superadmin' => ['username' => 'superadmin', 'name' => 'Superadmin', 'email' => null, 'password' => 'FirstPassword123!']]);
    $this->seed(SuperadminSeeder::class);
    $user = User::where('username', 'superadmin')->firstOrFail();
    $hash = $user->password;
    config(['silap.superadmin.password' => 'OtherPassword123!']);
    $this->seed(SuperadminSeeder::class);
    $this->assertDatabaseCount('users', 1);
    $this->assertSame($hash, $user->fresh()->password);
    $this->assertTrue($user->hasRole('superadmin'));
    $this->assertTrue($user->must_change_password);
  }

  public function test_bootstrap_does_not_take_over_an_existing_username(): void {
    $user = $this->user('guru', ['username' => 'superadmin']);
    $this->artisan('silap:superadmin', ['--generate-password' => true])->assertFailed();
    $this->assertFalse($user->fresh()->hasRole('superadmin'));
  }

  public function test_bootstrap_command_creates_an_initial_admin_but_refuses_a_second_bootstrap(): void {
    $this->artisan('silap:superadmin', ['--generate-password' => true])->assertSuccessful();
    $this->assertTrue(User::where('username', 'superadmin')->firstOrFail()->hasRole('superadmin'));
    $this->artisan('silap:superadmin', ['--username' => 'secondadmin', '--generate-password' => true])->assertFailed();
    $this->assertDatabaseCount('users', 1);
  }

  public function test_changing_own_additional_roles_requires_a_new_login(): void {
    $admin = $this->user('superadmin');
    $this->actingAs($admin)->put('/users/'.$admin->id, $this->payload([
      'username' => $admin->username, 'roles' => ['superadmin', 'guru_piket'],
    ]))->assertRedirect(route('login'));
    $this->assertGuest();
    $this->assertTrue($admin->fresh()->hasAllRoles(['superadmin', 'guru_piket']));
  }

  public function test_concurrent_reset_cannot_be_overwritten_with_a_stale_password(): void {
    $admin = $this->user('admin');
    $target = $this->user('guru');
    $stale = $target->fresh();
    app(AccountService::class)->resetPassword($admin, $target);
    $resetHash = $target->fresh()->password;
    $this->actingAs($stale)->put('/password', [
      'current_password' => 'Password123!', 'password' => 'NewPassword123!', 'password_confirmation' => 'NewPassword123!',
    ])->assertSessionHasErrors('current_password');
    $this->assertSame($resetHash, $target->fresh()->password);
  }
}
