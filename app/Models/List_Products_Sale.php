<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class List_Products_Sale extends Model
{
    protected $table = 'list_products_sale';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'update_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'quantity',
        'unity_price',
        'total'
    ];
}
