<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Tests\Feature\FeatureTestCase;

class UserTest extends FeatureTestCase
{
    private $adminUser;

    private $superAdminUser;

    public function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('Admin');
        $this->superAdminUser = User::factory()->create();
        $this->superAdminUser->assignRole('SuperAdmin');
        $this->actingAs($this->superAdminUser);
    }

    public function testListUsers(): void
    {
        $user = User::factory()->make();
        $encUser = User::factory([
            'name' => $this->encryptAttribute($user->name),
            'email' => $this->encryptAttribute($user->email),
            'phone' => $this->encryptAttribute($user->phone),
            'token' => $user->token,
        ])->create();
        $response = $this->get('users');
        $response->assertOk();
        $response->assertSee($user->token);
    }

    public function testGetUser(): void
    {
        $user = User::factory()->make();
        $encUser = User::factory([
            'name' => $this->encryptAttribute($user->name),
            'email' => $this->encryptAttribute($user->email),
            'phone' => $this->encryptAttribute($user->phone),
            'token' => $user->token,
        ])->create();
        $response = $this->get('users/'.$encUser->id);
        $response->assertOk();
        $response->assertSee($user->token);
    }

    public function testCreateUser(): void
    {
        $user = User::factory()->make();
        $encUser = User::factory([
            'name' => $this->encryptAttribute($user->name),
            'email' => $this->encryptAttribute($user->email),
            'phone' => $this->encryptAttribute($user->phone),
            'token' => $user->token,
        ])->make();
        $response = $this->post('users', $user->toArray());
        $response->assertCreated();
        $encryptedEmail = $this->encryptAttribute($user->email);
        $this->assertDatabaseHas('users', ['phone' => $this->encryptAttribute($user->phone)]);
        $response->assertSee($user->email);
    }

    public function testUpdateUser(): void
    {
        $this->actingAs($this->superAdminUser);
        $user = User::factory()->make();
        $encUser = User::factory([
            'name' => $this->encryptAttribute($user->name),
            'email' => $this->encryptAttribute($user->email),
            'phone' => $this->encryptAttribute($user->phone),
        ])->create();

        $user->phone = '0800 000 000';

        $url = 'users/'.$encUser->id;

        $response = $this->put($url, $user->toArray());
        $response->assertOk();
        $this->assertDatabaseHas('users', ['phone' => $this->encryptAttribute($user->phone)]);
    }

    public function testDeleteUser(): void
    {
        $user = User::factory()->make();
        $encUser = User::factory([
            'name' => $this->encryptAttribute($user->name),
            'email' => $this->encryptAttribute($user->email),
            'phone' => $this->encryptAttribute($user->phone),
        ])->create();
        $response = $this->delete('users/'.$encUser->id);
        $response->assertNoContent();
        $this->assertDatabaseMissing('users', ['name' => $this->encryptAttribute($user->name)]);
    }
}
