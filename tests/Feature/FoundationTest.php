<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_home_redirects_to_the_school_login_template(): void
    {
        $this->get('/')->assertRedirect(route('login'));
        $this->get(route('login'))->assertOk()->assertSee('assets/vendor/css/rtl/core.css')->assertSee('Selamat datang kembali');
    }

    public function test_health_endpoint_is_available(): void
    {
        $this->get('/up')->assertOk();
    }

    public function test_application_uses_jakarta_time_and_indonesian_locale(): void
    {
        $this->assertSame('Asia/Jakarta', config('app.timezone'));
        $this->assertSame('id', config('app.locale'));
    }

    public function test_seeding_creates_nine_roles_idempotently_without_example_accounts(): void
    {
        $this->seed();
        $this->seed();
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('roles', 9);
    }

    public function test_migrations_can_be_rolled_back_and_reapplied_on_the_isolated_database(): void
    {
        Artisan::call('migrate:rollback', ['--force' => true]);
        Artisan::call('migrate', ['--force' => true]);
        $this->assertContains(DB::connection()->getDriverName(), ['sqlite', 'mysql']);
        $this->assertDatabaseCount('users', 0);
    }
}
