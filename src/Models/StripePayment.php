<?php

namespace Alamin\EasyStripe\Models;

use Illuminate\Database\Eloquent\Model;

class StripePayment extends Model
{
    protected $fillable = [
        'stripe_payment_intent_id',
        'amount',
        'currency',
        'status',
        'customer_email',
        'customer_name',
        'description',
        'stripe_charge_id',
        'stripe_invoice_url',
        'payment_method'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];
}
