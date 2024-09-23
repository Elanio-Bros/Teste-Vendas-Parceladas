<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/login', [App\Http\Controllers\System::class, 'login']);
Route::post('/logout', [App\Http\Controllers\System::class, 'logout']);

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'produtos'], function () {
        Route::get('/list', [App\Http\Controllers\Produtos::class, 'get_list']);
        Route::group(['prefix' => 'produto'], function () {
            Route::post('/adicionar', [App\Http\Controllers\Produtos::class, 'create']);
            Route::get('/{id}', [App\Http\Controllers\Produtos::class, 'product']);
            Route::post('/{id}/editar', [App\Http\Controllers\Produtos::class, 'update']);
            Route::post('/{id}/apagar', [App\Http\Controllers\Produtos::class, 'delete']);
        });
    });

    Route::group(['prefix' => 'clientes'], function () {
        Route::get('/list', [App\Http\Controllers\Clientes::class, 'get_list']);
        Route::group(['prefix' => 'cliente'], function () {
            Route::get('/adicionar', [App\Http\Controllers\Clientes::class, 'view_create']);
            Route::post('/adicionar', [App\Http\Controllers\Clientes::class, 'create']);
            Route::get('/{id}', [App\Http\Controllers\Clientes::class, 'client']);
            Route::post('/{id}/editar', [App\Http\Controllers\Clientes::class, 'update']);
            Route::post('/{id}/apagar', [App\Http\Controllers\Clientes::class, 'delete']);
        });
    });

    Route::group(['prefix' => 'vendas'], function () {
        Route::get('/list', [App\Http\Controllers\Clientes::class, 'get_list']);
        Route::group(['prefix' => 'venda'], function () {
            Route::post('/adicionar', [App\Http\Controllers\Vendas::class, 'create']);
            Route::post('/adicionar/produto', [App\Http\Controllers\Vendas::class, 'create_list_products']);
            Route::post('/adicionar/pagamento', [App\Http\Controllers\Vendas::class, 'create_method_payment']);
            Route::get('/{id}', [App\Http\Controllers\Vendas::class, 'sale']);
            Route::post('/{id}/editar', [App\Http\Controllers\Vendas::class, 'update']);
            Route::post('/{id}/apagar', [App\Http\Controllers\Vendas::class, 'delete']);
            Route::post('/{id}/produto/{list_id}', [App\Http\Controllers\Vendas::class, 'update_product_list']);
            Route::post('/{id}/produto/{list_id}/apagar', [App\Http\Controllers\Vendas::class, 'delete_product_list']);
            Route::post('/{id}/pagamento/{payment_id}', [App\Http\Controllers\Vendas::class, 'update_payment']);
            Route::post('/{id}/pagamento/{payment_id}/apagar', [App\Http\Controllers\Vendas::class, 'delete_payment']);
        });
    });
});
