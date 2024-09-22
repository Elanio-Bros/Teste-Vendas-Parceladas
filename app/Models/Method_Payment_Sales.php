<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Method_Payment_Sales extends Model
{
    protected $table = 'method_payment_sales';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_id',
        'paid',
        'type_payment',
        'value',
        'date_payment',
    ];
}
