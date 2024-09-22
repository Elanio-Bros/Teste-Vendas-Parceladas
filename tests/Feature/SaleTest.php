<?php

namespace Tests\Feature;

use App\Models\Clients;
use App\Models\Products;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SaleTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create(): void
    {
        $client = Clients::first();
        $product = Products::first();
        $quantity = rand(1, $product['stock_quantity']);
        $unity_price = number_format((rand(200, 999) / 10), 2, thousands_separator: "");
        $total = number_format(($unity_price * $quantity), 2, thousands_separator: "");
        $response = $this->post('/vendas/venda/adicionar', [
            'client_id' => $client['id'],
            'total_price' => $total,
            'products' => [[
                'product_id' => $product['id'],
                'quantity' => $quantity,
                'unity_price' => $unity_price,
                'total' =>  $total
            ]],
            'payment' => [[
                'paid' => true,
                'type_payment' => 'cash',
                'value' => $total,
                'date_payment' => date('Y-m-d H:i:s')
            ]],
        ]);

        dd($total, $response->getContent());

        $response->assertStatus(201)->assertJson(['message' => 'sale created']);
    }
}
