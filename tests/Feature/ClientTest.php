<?php

namespace Tests\Feature;

use App\Models\Clients;
use Tests\TestCase;

class ClientTest extends TestCase
{
    public function test_create(): void
    {
        $name = fake()->name();
        $email = str_replace(" ", "_", strtolower($name));
        $response = $this->post('/clientes/cliente/adicionar', ['name' => $name, 'email' => "$email@email.com.br", 'type_document' => 'cpf', 'document' => '000.000.000-01']);
        $response->assertStatus(201)->assertJson(['message' => 'client created']);
    }

    public function test_update(): void
    {
        $client = Clients::first();
        $name = fake()->name();
        $email = str_replace(" ", "_", strtolower($name));
        $response = $this->post("/clientes/cliente/{$client['id']}/editar", ['email' => "$email@email.com.br"]);
        $response->assertStatus(200)->assertJson(['message' => 'client changed']);
    }

    public function test_delete(): void
    {
        $client = Clients::first();

        $response = $this->post("/clientes/cliente/{$client['id']}/apagar");
        $response->assertStatus(200)->assertJson(['message' => 'client deleted']);
    }
}
