<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class List_Products_Sales extends Model
{
    protected $table = 'list_products_sales';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'unity_price',
        'total',
    ];


    public function product()
    {
        return $this->hasOne(Products::class, 'id', 'product_id');
    }
}
