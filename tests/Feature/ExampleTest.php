<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_route_is_secured()
    {
        $response = $this->getJson('/api/user');

        $response->assertUnauthorized();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_route_is_secured_against_invalid_tokens()
    {
        $response = $this->getJson('/api/user', [
            'Authorization' => 'Bearer invalidtoken',
        ]);

        $response->assertUnauthorized();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_route_allows_access_when_logged_in()
    {
        $token = $this->postJson('/api/login')->json('token');

        $response = $this->getJson('/api/user', [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertSuccessful();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_route_does_not_allow_access_when_logged_out()
    {
        $token = $this->postJson('/api/login')->json('token');

        var_dump(null);
        var_dump('Tokens BEFORE logout: '.implode(', ', DB::table('personal_access_tokens')->pluck('id')->all()));

        $response = $this->postJson('/api/logout', [], [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertSuccessful();

        var_dump('Tokens AFTER logout: '.implode(', ', DB::table('personal_access_tokens')->pluck('id')->all()));

        $response = $this->getJson('/api/user', [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertUnauthorized();
    }
}
