<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sales extends Model
{
    protected $table = 'sales';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'salesman_id',
        'client_id',
        'total_price'
    ];

    public function salesman(): HasOne
    {
        return $this->hasOne(Admins::class, 'id', 'salesman_id')->select('id', 'name');
    }

    public function client(): HasOne
    {
        return $this->hasOne(Clients::class, 'id', 'client_id')->select('id', 'name');
    }

    public function list_products(): HasMany
    {
        return $this->hasMany(List_Products_Sale::class, 'sale_id', 'id')->with('product')->select('id', 'name');
    }

    public function payment_method(): HasMany
    {
        return $this->hasMany(Method_Payment_Sale::class, 'sale_id', 'id');
    }
}
