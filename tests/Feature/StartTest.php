<?php

namespace Tests\Feature;

use App\Models\Admins;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StartTest extends TestCase
{
    use DatabaseTransactions;
    
    public function test_inital(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_login_user(): void
    {
        $admin = Admins::factory()->create();

        $response = $this->post('/login', ['email' => $admin['email'], 'password' => 'admin_123']);

        $response->assertStatus(200)->assertJson(['message' => 'admin login']);
    }

    public function test_logout_user(): void
    {
        $admin = Admins::factory()->create();

        $response = $this->actingAs($admin)->post('/logout');

        $response->assertStatus(200)->assertJson(['message' => 'admin logout']);
    }
}
