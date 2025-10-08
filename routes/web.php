<?php

use Illuminate\Support\Facades\Route;
use Alamin\EasyStripe\Http\Controllers\StripePaymentController;

$prefix = config('easy-stripe.routes.prefix', 'stripe');
$middleware = config('easy-stripe.routes.middleware', ['web']);

Route::prefix($prefix)->middleware($middleware)->group(function () {
    // Payment routes
    Route::get('/payment', [StripePaymentController::class, 'index'])->name('stripe.payment.index');
    Route::post('/payment/intent', [StripePaymentController::class, 'createPaymentIntent'])->name('stripe.payment.intent');
    Route::post('/payment/checkout', [StripePaymentController::class, 'createCheckoutSession'])->name('stripe.payment.checkout');
    Route::get('/payment/success', [StripePaymentController::class, 'success'])->name('stripe.payment.success');
    Route::get('/payment/cancel', [StripePaymentController::class, 'cancel'])->name('stripe.payment.cancel');

    // Payment management
    Route::get('/payments', [StripePaymentController::class, 'payments'])->name('stripe.payments.list');
    Route::get('/payment/{payment}/invoice', [StripePaymentController::class, 'invoice'])->name('stripe.payment.invoice');

    // Webhook (exclude from CSRF)
    Route::post('/webhook', [StripePaymentController::class, 'webhook'])->name('stripe.webhook')->withoutMiddleware(['web']);
});
