<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Seeders\RolesPermissionSeeder;
use ESolution\DBEncryption\Traits\EncryptedAttribute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FeatureTestCase extends TestCase
{
    use EncryptedAttribute;
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFaker();

        $this->seed(RolesPermissionSeeder::class);

        // now re-register all the roles and permissions (clears cache and reloads relations)
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
    }
}
