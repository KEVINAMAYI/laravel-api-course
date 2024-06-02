<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\AuthTrait;

class AuthenticationTest extends TestCase
{

    use AuthTrait;

    /**
     * A basic feature test example.
     */
    public function test_user_registration()
    {
        $response = $this->register();

        $response->assertStatus(201)->assertJsonStructure(['message', 'token']);

        $this->assertDatabaseHas('users', [
            'name' => 'TestUserName',
            'email' => 'testemail@test.com',
        ]);

    }


    public function test_user_login()
    {

        $response = $this->login();
        $response->assertStatus(200)->assertJsonStructure([
            'message', 'user', 'token'
        ]);

    }


    public function test_user_logout()
    {

        $response = $this->loginWithHeaders()->postJson('api/logout');

        $this->assertCount(0, $this->getUser()->tokens);

        $response->assertStatus(200)->assertJsonStructure(['message']);

        $this->refreshApplication();
    }



}
