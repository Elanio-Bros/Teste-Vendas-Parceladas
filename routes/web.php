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

Route::get('/', [App\Http\Controllers\System::class, 'view_login']);

Route::post('/login', [App\Http\Controllers\System::class, 'login']);

Route::group(['prefix' => 'produtos'], function () {
    Route::get('/', [App\Http\Controllers\Produtos::class, 'view_list']);
    Route::group(['prefix' => 'produto'], function () {
        Route::get('/adicionar', [App\Http\Controllers\Produtos::class, 'view_create']);
        Route::post('/adicionar', [App\Http\Controllers\Produtos::class, 'create']);
        Route::get('/{id}', [App\Http\Controllers\Produtos::class, 'product']);
        Route::post('/{id}/editar', [App\Http\Controllers\Produtos::class, 'update']);
        Route::post('/{id}/apagar', [App\Http\Controllers\Produtos::class, 'delete']);
    });
});

Route::group(['prefix' => 'clientes'], function () {
    Route::get('/', [App\Http\Controllers\Clientes::class, 'view_list']);
    Route::group(['prefix' => 'cliente'], function () {
        Route::get('/adicionar', [App\Http\Controllers\Clientes::class, 'view_create']);
        Route::post('/adicionar', [App\Http\Controllers\Clientes::class, 'create']);
        Route::get('/{id}', [App\Http\Controllers\Clientes::class, 'client']);
        Route::post('/{id}/editar', [App\Http\Controllers\Clientes::class, 'update']);
        Route::post('/{id}/apagar', [App\Http\Controllers\Clientes::class, 'delete']);
    });
});

Route::group(['prefix' => 'vendas'], function () {
    Route::get('/', [App\Http\Controllers\Vendas::class, 'view_list']);
    Route::group(['prefix' => 'venda'], function () {
        Route::get('/adicionar', [App\Http\Controllers\Vendas::class, 'view_create']);
        Route::post('/adicionar', [App\Http\Controllers\Vendas::class, 'create']);
        Route::get('/{id}', [App\Http\Controllers\Vendas::class, 'sale']);
        Route::post('/{id}/editar', [App\Http\Controllers\Vendas::class, 'update']);
        Route::post('/{id}/apagar', [App\Http\Controllers\Vendas::class, 'delete']);
        Route::post('/{id}/produto/{list_id}', [App\Http\Controllers\Vendas::class, 'update_product_list']);
        Route::post('/{id}/pagamento/{payment_id}', [App\Http\Controllers\Vendas::class, 'update_payment']);
    });
});
