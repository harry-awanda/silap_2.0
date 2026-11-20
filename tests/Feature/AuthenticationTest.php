<?php

namespace Tests\Feature;

use App\Enums\AppRole;
use App\Models\AccountAudit;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase {
  use RefreshDatabase;

  protected function setUp(): void {
    parent::setUp();
    $this->seed(RoleSeeder::class);
  }

  private function user(string $role = 'guru', array $attributes = []): User {
    return User::factory()->create($attributes)->assignRole($role);
  }

  public function test_guest_cannot_open_protected_pages(): void {
    $this->get('/dashboard')->assertRedirect(route('login'));
    $this->get('/users')->assertRedirect(route('login'));
    $this->getJson('/dashboard')->assertUnauthorized();
  }

  public function test_username_login_regenerates_session_and_redirects_to_dashboard(): void {
    $user = $this->user('guru', ['username' => 'gurusatu']);
    $this->get('/login');
    $oldSession = session()->getId();
    $this->post('/login', ['username' => ' GURUSATU ', 'password' => 'Password123!'])->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($user);
    $this->assertNotSame($oldSession, session()->getId());
  }

  public function test_wrong_password_and_disabled_account_cannot_login(): void {
    $user = $this->user();
    $this->post('/login', ['username' => $user->username, 'password' => 'WrongPassword123!'])->assertSessionHasErrors('username');
    $user->update(['disabled_at' => now()]);
    $this->post('/login', ['username' => $user->username, 'password' => 'Password123!'])->assertSessionHasErrors('username');
    $this->assertGuest();
  }

  public function test_failed_logins_are_rate_limited(): void {
    for ($i = 0; $i < 5; $i++) {
      $this->post('/login', ['username' => 'unknown', 'password' => 'WrongPassword123!'])->assertSessionHasErrors('username');
    }
    $response = $this->post('/login', ['username' => 'unknown', 'password' => 'WrongPassword123!']);
    $response->assertSessionHasErrors('username');
    $this->assertStringContainsString('Terlalu banyak percobaan', session('errors')->first('username'));
  }

  public function test_initial_password_must_be_changed_before_dashboard_or_account_access(): void {
    $user = $this->user('superadmin', ['must_change_password' => true, 'password_changed_at' => null]);
    $this->post('/login', ['username' => $user->username, 'password' => 'Password123!'])->assertRedirect(route('password.edit'));
    $this->get('/dashboard')->assertRedirect(route('password.edit'));
    $this->get('/users')->assertRedirect(route('password.edit'));
    $this->getJson('/dashboard')->assertForbidden();
    $this->get('/password')->assertOk();
    $this->post('/logout')->assertRedirect(route('login'));
  }

  public function test_password_change_verifies_current_password_confirmation_and_strength(): void {
    $user = $this->user('guru', ['must_change_password' => true]);
    $this->actingAs($user)->put('/password', [
      'current_password' => 'WrongPassword123!', 'password' => 'NewPassword123!', 'password_confirmation' => 'different',
    ])->assertSessionHasErrors(['current_password', 'password']);
    $this->assertTrue($user->fresh()->must_change_password);
  }

  public function test_password_change_revokes_other_sessions_and_preserves_current_login(): void {
    $user = $this->user('guru', ['must_change_password' => true, 'password_changed_at' => null]);
    DB::table('sessions')->insert(['id' => 'other-session', 'user_id' => $user->id, 'payload' => '', 'last_activity' => time()]);
    $oldToken = $user->remember_token;
    $this->actingAs($user)->put('/password', [
      'current_password' => 'Password123!', 'password' => 'NewPassword123!', 'password_confirmation' => 'NewPassword123!',
    ])->assertRedirect(route('dashboard'));
    $this->assertFalse($user->fresh()->must_change_password);
    $this->assertTrue(Hash::check('NewPassword123!', $user->fresh()->password));
    $this->assertNotNull($user->fresh()->password_changed_at);
    $this->assertNotSame($oldToken, $user->fresh()->remember_token);
    $this->assertDatabaseMissing('sessions', ['id' => 'other-session']);
    $this->get('/dashboard')->assertOk();
    $this->assertDatabaseHas('account_audits', ['action' => 'password_changed', 'actor_id' => $user->id]);
    $this->assertStringNotContainsString('NewPassword123!', AccountAudit::first()->toJson());
  }

  public function test_a_disabled_user_with_an_existing_session_is_logged_out(): void {
    $user = $this->user('guru', ['disabled_at' => now()]);
    $this->actingAs($user)->get('/dashboard')->assertRedirect(route('login'));
    $this->assertGuest();
  }

  public function test_changed_password_hash_invalidates_a_stale_session(): void {
    $user = $this->user();
    $oldHash = $user->password;
    $user->update(['password' => 'DifferentPassword123!']);
    $this->actingAs($user)->withSession(['password_hash_web' => $oldHash])->get('/dashboard')->assertRedirect(route('login'));
    $this->assertGuest();
  }

  public function test_dashboard_preserves_all_roles_without_homeroom_redirects(): void {
    $user = $this->user('wali_kelas')->assignRole('guru_bk');
    $this->actingAs($user)->get('/dashboard')->assertOk()->assertSee('Wali Kelas')->assertSee('Guru BK')->assertDontSee('href="'.route('users.index').'"', false);
    $this->get('/users')->assertForbidden();
  }

  public function test_all_nine_roles_can_open_their_dashboard(): void {
    foreach (AppRole::cases() as $role) {
      $user = $this->user($role->value);
      $this->actingAs($user)->get('/dashboard')->assertOk()->assertSee($role->label());
    }
  }

  public function test_late_attendance_capability_has_no_superadmin_bypass(): void {
    $superadmin = $this->user('superadmin');
    $this->assertFalse(Gate::forUser($superadmin)->allows('attendance.record-late'));
    $superadmin->assignRole('guru_piket');
    $this->assertTrue(Gate::forUser($superadmin)->allows('attendance.record-late'));
    $superadmin->update(['disabled_at' => now()]);
    $this->assertFalse(Gate::forUser($superadmin)->allows('attendance.record-late'));
  }

  public function test_logout_removes_authentication_and_rotates_session(): void {
    $user = $this->user();
    $this->actingAs($user)->get('/dashboard');
    $oldId = session()->getId();
    $this->post('/logout')->assertRedirect(route('login'));
    $this->assertGuest();
    $this->assertNotSame($oldId, session()->getId());
  }

  public function test_login_and_account_pages_are_not_cached(): void {
    $this->get('/login')->assertHeader('Cache-Control', 'max-age=0, no-store, private');
    $this->actingAs($this->user('admin'))->get('/users')->assertHeader('Cache-Control', 'max-age=0, no-store, private');
  }
}
