<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Method_Payment_Sale extends Model
{
    protected $table = 'method_payment_ssale';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'update_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_id',
        'paid',
        'value',
        'date_payment',
    ];
}
