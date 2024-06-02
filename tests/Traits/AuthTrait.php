<?php

namespace Tests\Traits;

use App\Models\User;

trait AuthTrait{

    public function register(){
        return $this->postJson('/api/register', [
            'name' => 'TestUserName',
            'email' => 'testemail@test.com',
            'password' => 'test1234',
            'confirm_password' => 'test1234',
        ]);
    }

    public function login()
    {
        return $this->postJson('/api/login', [
            'email' => 'testemail@test.com',
            'password' => 'test1234',
        ]);
    }


    public function loginWithHeaders(){
        return $this->withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer " . $this->login()['token']
        ]);
    }

    public function getUser(){
       return  User::where('email','testemail@test.com')->first();
    }

}
