<?php

namespace Tests\Feature;

use App\Models\Admins;
use App\Models\Clients;
use App\Models\List_Products_Sales;
use App\Models\Method_Payment_Sales;
use App\Models\Products;
use App\Models\Sales;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SaleTest extends TestCase
{
    use DatabaseTransactions;

    public function get_sale()
    {
        $client = Clients::factory()->create();
        $product = Products::factory()->create();

        $quantity = rand(2, $product['stock_quantity']);
        $unity_price = number_format((rand(200, 999) / 10), 2, thousands_separator: "");
        $total = number_format(($unity_price * $quantity), 2, thousands_separator: "");

        return [
            'client_id' => $client['id'],
            'products' => [[
                'product_id' => $product['id'],
                'quantity' => $quantity,
                'unity_price' => $unity_price,
                'total' =>  $total
            ]],
            'payment' => [[
                'paid' => true,
                'type_payment' => 'cash',
                'value' => number_format($total / 2, 2, thousands_separator: ""),
                'date_payment' => date('Y-m-d H:i:s')
            ], [
                'paid' => false,
                'type_payment' => 'cash',
                'value' => number_format($total / 2, 2, thousands_separator: ""),
                'date_payment' => date('Y-m-d H:i:s', strtotime('+30 days'))
            ]],
        ];
    }

    public function test_create(): void
    {
        $admin = Admins::factory()->create();
        $response = $this->actingAs($admin)->post('/vendas/venda/adicionar', $this->get_sale());
        $response->assertStatus(201)->assertJson(['message' => 'sale created']);
    }

    public function test_update_product(): void
    {
        $admin = Admins::factory()->create();

        $this->actingAs($admin)->post('/vendas/venda/adicionar', $this->get_sale());

        $sale = Sales::first();
        $list = List_Products_Sales::where('sale_id', '=', $sale['id'])->first();
        $product = Products::inRandomOrder()->first();
        $unity_price = number_format((rand(200, 999) / 10), 2, thousands_separator: "");

        $response = $this->actingAs($admin)->post("/vendas/venda/{$sale['id']}/produto/{$list['id']}", [
            'product_id' => $product['id'],
            'unity_price' => $unity_price,
            'total' => number_format(($unity_price * $list['quantity']), 2, thousands_separator: "")
        ]);

        $response->assertStatus(200)->assertJson(['message' => 'product changed']);
    }

    public function test_update_method_payment(): void
    {
        $admin = Admins::factory()->create();

        $this->actingAs($admin)->post('/vendas/venda/adicionar', $this->get_sale());

        $sale = Sales::first();
        $list = List_Products_Sales::where('sale_id', '=', $sale['id'])->first();
        $payment = Method_Payment_Sales::where('sale_id', '=', $sale['id'])->first();

        $response = $this->actingAs($admin)->post("/vendas/venda/{$sale['id']}/pagamento/{$payment['id']}", ['paid' => true, 'value' => number_format($list['total'], 2, thousands_separator: "")]);

        $response->assertStatus(200)->assertJson(['message' => 'payment changed']);
    }

    public function test_update_sale(): void
    {
        $admin = Admins::factory()->create();

        $this->actingAs($admin)->post('/vendas/venda/adicionar', $this->get_sale());

        $sale = Sales::first();
        $list = List_Products_Sales::where('sale_id', '=', $sale['id'])->first();

        $response = $this->actingAs($admin)->post("/vendas/venda/{$sale['id']}/editar", ['total_price' => number_format($list['total'], 2, thousands_separator: "")]);

        $response->assertStatus(200)->assertJson(['message' => 'sale changed']);
    }

    public function test_delete_product(): void
    {
        $admin = Admins::factory()->create();

        $this->actingAs($admin)->post('/vendas/venda/adicionar', $this->get_sale());

        $sale = Sales::first();
        $list = List_Products_Sales::where('sale_id', '=', $sale['id'])->first();

        $response = $this->actingAs($admin)->post("/vendas/venda/{$sale['id']}/produto/{$list['id']}/apagar");
        $response->assertStatus(200)->assertJson(['message' => 'product deleted']);
    }

    public function test_delete_payment(): void
    {
        $admin = Admins::factory()->create();

        $this->actingAs($admin)->post('/vendas/venda/adicionar', $this->get_sale());

        $sale = Sales::first();
        $payment = Method_Payment_Sales::where([['sale_id', '=', $sale['id']], ['paid', '=', false]])->first();
        $response = $this->actingAs($admin)->post("/vendas/venda/{$sale['id']}/pagamento/{$payment['id']}/apagar");
        $response->assertStatus(200)->assertJson(['message' => 'payment deleted']);
    }

    public function test_delete_payment_erro(): void
    {
        $admin = Admins::factory()->create();

        $this->actingAs($admin)->post('/vendas/venda/adicionar', $this->get_sale());

        $sale = Sales::first();
        $payment = Method_Payment_Sales::where([['sale_id', '=', $sale['id']], ['paid', '=', true]])->first();
        $response = $this->actingAs($admin)->post("/vendas/venda/{$sale['id']}/pagamento/{$payment['id']}/apagar");
        $response->assertStatus(404)->assertJson(['erro' => 'sale', 'message' => 'payment is paid']);
    }

    public function test_delete(): void
    {
        $admin = Admins::factory()->create();

        $this->actingAs($admin)->post('/vendas/venda/adicionar', $this->get_sale());

        $sale = Sales::first();
        $response = $this->actingAs($admin)->post("/vendas/venda/{$sale['id']}/apagar");
        $response->assertStatus(200)->assertJson(['message' => 'sale deleted']);
    }
}
