<?php

namespace Tests\Feature\Role_User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function test_user_register()
    {

        $response = $this->postJson('/api/auth/register', [
            'name' => 'User test',
            'email' => 'user@user.com',
            'password' => '1234'
        ]);

        $response->assertStatus(201);

        //Opcional
        //$response->assertJsonStructure(['message']);

        $response->assertJson(['message' => 'User register successfully']);

        $this->assertDatabaseHas('users', [
            'name' => 'User test',
            'email' => 'user@user.com'
        ]);
    }

    /** @test */
    public function test_user_login()
    {

        //Creado clientes de passport en BD
        $this->artisan('passport:install');

        $user = User::factory()->create();


        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertOk();

        //token para acceso
        $response->assertJsonStructure(['access_token','user_id']);
    }
}
