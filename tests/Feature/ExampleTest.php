<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{


    public function login()
    {
        $user = User::find(12);

    return Passport::actingAs($user);

    }/**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * test_hello_return_200
     *
     * @return void
     */
    public function test_hello_return_200()
    {
        $response = $this->get('/hello');
        $response->assertStatus(200);
    }

    public function test_registered_user_ca_access_admin_page()
    {

        $this->login();
        $response = $this->get('api/users');
        $response->assertStatus(200);

    }
}
