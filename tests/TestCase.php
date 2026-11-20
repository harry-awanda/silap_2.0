<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    public function createApplication(): Application
    {
        $app = parent::createApplication();
        $driver = $app['config']->get('database.default');
        $database = $app['config']->get("database.connections.$driver.database");

        if (! $app->environment('testing') || ! (($driver === 'sqlite' && $database === ':memory:') || ($driver === 'mysql' && $database === 'silap_test'))) {
            throw new RuntimeException('Test hanya boleh menggunakan SQLite :memory: atau MySQL silap_test.');
        }

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        config(['silap.superadmin.password' => null]);
        $this->withoutVite();
    }
}
