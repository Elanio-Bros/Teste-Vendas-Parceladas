<?php

namespace Tests\Feature;

use App\Models\Clients;
use App\Models\List_Products_Sales;
use App\Models\Method_Payment_Sales;
use App\Models\Products;
use App\Models\Sales;
use Tests\TestCase;

class SaleTest extends TestCase
{
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
                'type_payment' => 'cash',
                'value' => $total,
                'date_payment' => date('Y-m-d H:i:s')
            ]],
        ]);

        $response->assertStatus(201)->assertJson(['message' => 'sale created']);
    }

    public function test_update_product(): void
    {
        $sale = Sales::first();
        $list = List_Products_Sales::where('sale_id', '=', $sale['id'])->first();
        $product = Products::inRandomOrder()->first();
        $unity_price = number_format((rand(200, 999) / 10), 2, thousands_separator: "");
        $response = $this->post("/vendas/venda/{$sale['id']}/produto/{$list['id']}", [
            'product_id' => $product['id'],
            'unity_price' => $unity_price,
            'total' => number_format(($unity_price * $list['quantity']), 2, thousands_separator: "")
        ]);

        $response->assertStatus(200)->assertJson(['message' => 'product changed']);
    }


    public function test_update_method_payment(): void
    {
        $sale = Sales::first();
        $list = List_Products_Sales::where('sale_id', '=', $sale['id'])->first();
        $payment = Method_Payment_Sales::where('sale_id', '=', $sale['id'])->first();

        $response = $this->post("/vendas/venda/{$sale['id']}/pagamento/{$payment['id']}", ['paid' => true, 'value' => number_format($list['total'], 2, thousands_separator: "")]);

        $response->assertStatus(200)->assertJson(['message' => 'payment changed']);
    }

    public function test_update_sale(): void
    {
        $sale = Sales::first();
        $list = List_Products_Sales::where('sale_id', '=', $sale['id'])->first();

        $response = $this->post("/vendas/venda/{$sale['id']}/editar", ['total_price'=>number_format($list['total'], 2, thousands_separator: "")]);

        $response->assertStatus(200)->assertJson(['message' => 'sale changed']);
    }

    public function test_delete(): void
    {
        $sale = Sales::first();
        $response = $this->post("/vendas/venda/{$sale['id']}/apagar");
        $response->assertStatus(200)->assertJson(['message' => 'sale deleted']);
    }
}
