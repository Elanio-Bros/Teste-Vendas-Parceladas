<?php

namespace App\Http\Controllers;

use App\Models\List_Products_Sales;
use App\Models\Method_Payment_Sales;
use App\Models\Sales;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class Vendas extends Controller
{
    public function view_list(Request $request): View
    {
        return view('Sale\List', json_decode($this->get_list($request)->getContent(), true));
    }

    public function get_list(Request $request): JsonResponse
    {
        $validate = $this->validate($request, [
            'search' => 'string',
            'date_sale' => 'array|max:2|min:2',
            'date_sale.*' => 'datetime',
            'total_price' => 'decimal:2',
            'salesman' => 'integer|exists:admins,id',
            'client' => 'integer|exists:clients,id',
            'per_page' => 'integer',
            'page' => 'integer'
        ]);

        $sales = new Sales;

        if (isset($validate['search'])) {
            $search = str_replace(" ", "%", $validate['search'] . "%");
            $sales->where(function ($query) use ($search) {
                $query->where('id', 'LIKE', $search)
                    ->orWhereRelation('client', 'name', 'LIKE', $search)
                    ->orWhereRelation('client', 'document', 'LIKE', $search)
                    ->orWhereRelation('list_products.product', 'id', 'LIKE', $search)
                    ->orWhereRelation('list_products.product', 'name', 'LIKE', $search);
            });
        }

        if (isset($validate['date_sale'])) {
            $sales->whereBetween('created_at', $validate['date_sale']);
        }

        if (isset($validate['total_price'])) {
            $sales->where('total_price', '=', $validate['total_price']);
        }

        if (isset($validate['salesman'])) {
            $sales->where('salesman_id', '=', $validate['salesman']);
        }

        if (isset($validate['client'])) {
            $sales->where('client_id', '=', $validate['client']);
        }

        $sales = $sales->paginate(page: $validate['page'] ?? 1, perPage: $validate['per_page'] ?? 10);

        return response()->json(compact("sales"));
    }

    public function create(Request $request): JsonResponse
    {
        $validate = $this->validate($request, [
            'client_id' => 'integer|exists:clients,id|required',
        ]);

        $validate['salesman_id'] = Auth::user()['id'];
        $validate['total_price'] = 0;

        $sale = Sales::create($validate);

        $request->merge(['sale_id' => $sale['id']]);
        $response = ['message' => 'sale created'];
        $code = 201;

        try {
            $this->create_list_products($request);
            $payment = $this->create_method_payment($request);

            if ($payment->getStatusCode() !== 201) {
                $response = json_decode($payment->getContent(), true);
                $code = $payment->getStatusCode();
            }
        } catch (HttpResponseException $e) {
            $this->delete($sale['id']);
            $erro_response = $e->getResponse();
            $response = json_decode($erro_response->getContent(), true);
            $code = $erro_response->getStatusCode();
        }

        return response()->json($response, $code);
    }

    public function create_list_products(Request $request): JsonResponse
    {
        $validate = $this->validate($request, [
            'sale_id' => 'integer|exists:sales,id',
            'products' => 'array|required',
            'products.*.product_id' => 'integer|exists:products,id|required',
            'products.*.quantity' => 'integer|required',
            'products.*.unity_price' => 'decimal:2|required',
            'products.*.total' => 'decimal:2|required',
        ]);

        $sale_id = $validate['sale_id'];

        $products = array_map(function ($product) use ($sale_id) {
            return array_merge($product, [
                'sale_id' => $sale_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }, $validate['products']);

        List_Products_Sales::insert($products);

        Sales::where('id', '=', $sale_id)->update(['total_price' => array_sum(array_column($validate['products'], 'total'))]);

        return response()->json(['message' => 'products add list sale'], 201);
    }

    public function create_method_payment(Request $request): JsonResponse
    {
        $validate = $this->validate($request, [
            'sale_id' => 'integer|exists:sales,id',
            'payment' => 'array|required',
            'payment.*.paid' => 'boolean',
            'payment.*.type_payment' => 'in:credit,cash|required',
            'payment.*.value' => 'decimal:2|required',
            'payment.*.date_payment' => 'date|required',
        ]);

        $sale_id = $validate['sale_id'];
        $sum_products = List_Products_Sales::selectRaw('SUM(total) as total')->where([['sale_id', '=', $sale_id]])->first()['total'] ?? 0;
        $sum_payment = array_sum(array_column($validate['payment'], 'value'));
        if ($sum_products >= 0 && $sum_payment == $sum_products) {
            $payment = array_map(function ($payment) use ($sale_id) {
                return array_merge($payment, [
                    'sale_id' => $sale_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }, $validate['payment']);

            Method_Payment_Sales::insert($payment);

            return response()->json(['message' => 'created payment'], 201);
        } elseif ($sum_products == 0) {
            return response()->json(['erro' => 'product', 'message' => 'not registred product list'], 400);
        } elseif ($sum_payment !== $sum_products) {
            return response()->json(['erro' => 'payment', 'message' => 'payment not equal product list'], 400);
        }
    }

    public function sale(int $id): JsonResponse
    {
        $sale = Sales::with('salesman', 'client', 'list_products', 'payment_method')->where('id', '=', $id)->first();

        if ($sale !== null) {
            return response()->json(compact('sale'), 200);
        } else {
            return response()->json(['erro' => 'sale', 'message' => 'sale not found'], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validate = $this->validate($request, [
            'client_id' => 'integer|exists:clients,id',
            'total_price' => 'decimal:2',
        ]);

        $sale = Sales::where('id', '=', $id)->first();

        if ($sale !== null) {
            if (count($validate) >= 1) {
                $sale->update($validate);
                return response()->json(['message' => 'sale changed'], 200);
            } else {
                return response()->json(['message' => 'sale not changed'], 304);
            }
        } else {
            return response()->json(['erro' => 'sale', 'message' => 'sale not found'], 404);
        }
    }

    public function update_product_list(Request $request, int $id, int $list_id): JsonResponse
    {
        $validate = $this->validate($request, [
            'product_id' => 'integer|exists:products,id',
            'quantity' => 'integer',
            'unity_price' => 'decimal:2',
            'total' => 'decimal:2',
        ]);

        $product = List_Products_Sales::where([['id', '=', $list_id], ['sale_id', '=', $id]])->first();

        if ($product !== null) {
            if (count($validate) >= 1) {
                $product->update($validate);
                return response()->json(['message' => 'product changed'], 200);
            } else {
                return response()->json(['message' => 'product not changed'], 304);
            }
        } else {
            return response()->json(['erro' => 'sale', 'message' => 'product not found'], 404);
        }
    }

    public function update_payment(Request $request, int $id, int $payment_id): JsonResponse
    {
        $validate = $this->validate($request, [
            'paid' => 'boolean',
            'type_payment' => 'in:credit,cash',
            'value' => 'decimal:2',
            'date_payment' => 'date',
        ]);

        $payment = Method_Payment_Sales::where([['id', '=', $payment_id], ['sale_id', '=', $id]])->first();

        if ($payment !== null) {
            if (count($validate) >= 1) {
                $payment->update($validate);
                return response()->json(['message' => 'payment changed'], 200);
            } else {
                return response()->json(['message' => 'payment not changed'], 304);
            }
        } else {
            return response()->json(['erro' => 'sale', 'message' => 'payment not found'], 404);
        }
    }

    // Request front For Back
    public function delete(int $id): JsonResponse
    {
        $sale = Sales::where('id', '=', $id)->first();

        if ($sale !== null) {
            $sale->delete();

            return response()->json(['message' => 'sale deleted'], 200);
        } else {
            return response()->json(['erro' => 'sale', 'message' => 'sale not found'], 404);
        }
    }

    public function delete_product_list(int $id, int $list_id): JsonResponse
    {
        $product = List_Products_Sales::where([['id', '=', $list_id], ['sale_id', '=', $id]])->first();
        if ($product !== null) {
            $product->delete();

            $sum_products = List_Products_Sales::selectRaw('SUM(total) as total')->where([['sale_id', '=', $id]])->get()['total'] ?? 0;
            $this->rebalancing_parcels($id);
            Sales::where('id', '=', $id)->first()->update(['total_price' => $sum_products]);

            return response()->json(['message' => 'product deleted'], 200);
        } else {
            return response()->json(['erro' => 'sale', 'message' => 'product not found'], 404);
        }
    }

    public function delete_payment(int $id, int $payment_id): JsonResponse
    {
        $payment = Method_Payment_Sales::where([['id', '=', $payment_id], ['sale_id', '=', $id]])->first();

        if ($payment !== null && $payment['paid'] == false) {
            $payment->delete();

            $this->rebalancing_parcels($id);

            return response()->json(['message' => 'payment deleted'], 200);
        } elseif ($payment !== null && $payment['paid'] == true) {
            return response()->json(['erro' => 'sale', 'message' => 'payment is paid'], 404);
        } else {
            return response()->json(['erro' => 'sale', 'message' => 'payment not found'], 404);
        }
    }

    private function rebalancing_parcels(int $sale_id): void
    {
        $sale = Sales::where('id', '=', $sale_id)->first();

        // Pegar total de produtos, os não pagos e a soma dos pagos
        $sum_products = List_Products_Sales::selectRaw('SUM(total) as total')->where([['sale_id', '=', $sale_id]])->get()['total'] ?? 0;
        $not_paid_list = Method_Payment_Sales::select('id', 'value')->where([['sale_id', '=', $sale_id], ['paid', '=', 0]])->get()->toArray();
        $sum_paid = Method_Payment_Sales::selectRaw('SUM(value) as value')->where([['sale_id', '=', $sale_id], ['paid', '=', 1]])->first()['value'] ?? 0;

        $total_sale = $sum_products - $sum_paid;
        $total_sale = $total_sale >= 0 ? $total_sale : 0;

        // Rebalacear valores das pacerlas
        foreach ($not_paid_list as $not_paid) {
            Method_Payment_Sales::where('id', '=', $not_paid['id'])->update(['value' => ((($not_paid['value'] * 100) / $sale['total_price']) * $total_sale)]);
        }
    }
}
