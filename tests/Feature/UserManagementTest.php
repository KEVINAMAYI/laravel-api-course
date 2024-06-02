<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\AuthTrait;

class UserManagementTest extends TestCase
{
    use AuthTrait,  RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_get_users()
    {
        User::factory()->count(10)->create();

        $this->register();

        $response = $this->loginWithHeaders()->getJson('api/users');

        $response->assertStatus(200)->assertJsonStructure([
            ['id', 'name', 'email', 'products']
        ]);

    }

    public function test_get_user()
    {
        User::factory()->count(10)->create();

        $this->register();

        $user = User::first();

        $response = $this->loginWithHeaders()->getJson('api/users/' . $user->id);

        $response->assertStatus(200)->assertJsonStructure(
            ['id', 'name', 'email', 'products']
        );

    }

    public function test_create_user()
    {

        $this->register();

        $response = $this->loginWithHeaders()->postJson('api/users', [
            'name' => 'Test User',
            'email' => 'testemail123@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(201)->assertJsonStructure(
            ['id', 'name', 'email', 'products']
        );

    }

    public function test_update_user()
    {

        User::factory()->count(10)->create();

        $this->register();

        $user_id = User::inRandomOrder()->first()->id;

        $response = $this->loginWithHeaders()->putJson('api/users/' . $user_id, [
            'name' => 'updated User',
            'email' => 'updateduser@gmail.com',
            'password' => 'updatedpassword',
        ]);

        $response->assertStatus(200)->assertJsonStructure(
            ['id', 'name', 'email', 'products']
        );

        $this->assertDatabaseHas('users', [
            'name' => 'updated User',
            'email' => 'updateduser@gmail.com',
        ]);

    }

    public function test_delete_user()
    {

        User::factory()->count(10)->create();

        $this->register();

        $user = User::first();

        $response = $this->loginWithHeaders()->deleteJson('api/users/' . $user->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);


    }
}
