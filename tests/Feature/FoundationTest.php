<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_renders_the_school_template_without_authentication(): void
    {
        $this->withoutVite()
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Layanan Administrasi Sekolah')
            ->assertSee('assets/vendor/css/rtl/core.css')
            ->assertSee('Dalam persiapan');
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

    public function test_default_seeding_does_not_create_example_accounts(): void
    {
        $this->seed();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_migrations_can_be_rolled_back_and_reapplied(): void
    {
        Artisan::call('migrate:rollback', ['--force' => true]);
        Artisan::call('migrate', ['--force' => true]);

        $this->assertSame('sqlite', DB::connection()->getDriverName());
        $this->assertSame(':memory:', DB::connection()->getDatabaseName());
        $this->assertDatabaseCount('users', 0);
    }
}
