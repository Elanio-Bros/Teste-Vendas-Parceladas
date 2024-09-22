<?php

namespace Tests\Feature;

use App\Models\Products;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create(): void
    {
        $response = $this->post('/produtos/produto/adicionar', ['name' => 'Camisa Polo', 'stock_quantity' => rand(1, 50), 'unity_price' => number_format((rand(200, 900) / 10), 2, thousands_separator: "")]);
        $response->assertStatus(201)->assertJson(['message' => 'product created']);
    }

    public function test_update(): void
    {
        $product = Products::first();

        $response = $this->post("/produtos/produto/{$product['id']}/editar", ['name' => 'Camisa Polo M', 'unity_price' => number_format((rand(200, 900) / 10), 2, thousands_separator: "")]);

        $response->assertStatus(200)->assertJson(['message' => 'product changed']);
    }

    public function test_delete(): void
    {
        $product = Products::first();

        $response = $this->post("/produtos/produto/{$product['id']}/apagar");
        $response->assertStatus(200)->assertJson(['message' => 'product deleted']);
    }
}
