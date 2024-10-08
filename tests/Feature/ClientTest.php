<?php

namespace Tests\Feature;

use App\Models\Admins;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Clients;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use DatabaseTransactions;
    
    public function test_create(): void
    {
        $name = fake()->name();
        $email = str_replace(" ", "_", strtolower($name));
        $code = rand(10, 99);
        
        $admin = Admins::factory()->create();
        $response = $this->actingAs($admin)->post('/clientes/cliente/adicionar', ['name' => $name, 'email' => "$email@email.com.br", 'type_document' => 'cpf', 'document' => "000.000.000-{$code}"]);
        $response->assertStatus(201)->assertJson(['message' => 'client created']);
    }

    public function test_update(): void
    {
        Clients::factory()->create();

        $client = Clients::first();
        $name = fake()->name();
        $email = str_replace(" ", "_", strtolower($name));
        $admin = Admins::factory()->create();
        $response = $this->actingAs($admin)->post("/clientes/cliente/{$client['id']}/editar", ['email' => "$email@email.com.br"]);
        $response->assertStatus(200)->assertJson(['message' => 'client changed']);
    }

    public function test_delete(): void
    {
        Clients::factory()->create();
        $client = Clients::first();
        $admin = Admins::factory()->create();
        $response = $this->actingAs($admin)->post("/clientes/cliente/{$client['id']}/apagar");
        $response->assertStatus(200)->assertJson(['message' => 'client deleted']);
    }
}
