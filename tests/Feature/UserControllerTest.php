<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_user_login_application()
    {
        User::factory()->create([
            "email" => "test@test.com"
        ]);

        $response = $this->post("/api/login", [
            "email" => "test@test.com",
            "password" => "password"
        ]);

        $response->assertStatus(200);
    }

    public function test_user_login_application_incorrect_email()
    {
        User::factory()->create([
            "email" => "test@test.com"
        ]);

        $response = $this->post("/api/login", [
            "email" => "test2@test.com",
            "password" => "password"
        ]);

        $response->assertStatus(401);
    }

    public function test_user_login_application_incorrect_password()
    {
        User::factory()->create([
            "email" => "test@test.com"
        ]);

        $response = $this->post("/api/login", [
            "email" => "test@test.com",
            "password" => "password2"
        ]);

        $response->assertStatus(401);
    }

    public function test_user_register_application()
    {
        $response = $this->post("/api/register", [
            "name" => "teste",
            "email" => "teste@teste.com",
            "password" => "teste123"
        ]);

        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
        $json->where("name", "teste")
            ->where("email", "teste@teste.com")
            ->where("id", 1)
            ->etc()
        );
    }
}
