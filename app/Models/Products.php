<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'update_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'stock_quantity',
        'unity_price',
    ];
}
