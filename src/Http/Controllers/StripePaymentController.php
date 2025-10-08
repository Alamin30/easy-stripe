<?php

namespace Alamin\EasyStripe\Http\Controllers;

use App\Http\Controllers\Controller;
use Alamin\EasyStripe\Models\StripePayment;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Invoice;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Webhook;

class StripePaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('easy-stripe.stripe.secret'));
    }

    public function index()
    {
        return view('easy-stripe::payment.index');
    }

    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'email' => 'required|email',
            'name' => 'required|string',
        ]);

        try {
            $amount = $request->amount * 100;

            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => config('easy-stripe.currency'),
                'automatic_payment_methods' => ['enabled' => true],
                'description' => 'Payment from ' . $request->name,
                'receipt_email' => $request->email,
                'metadata' => [
                    'customer_name' => $request->name,
                    'customer_email' => $request->email,
                ],
            ]);

            StripePayment::create([
                'stripe_payment_intent_id' => $paymentIntent->id,
                'amount' => $request->amount,
                'currency' => config('easy-stripe.currency'),
                'status' => 'pending',
                'customer_email' => $request->email,
                'customer_name' => $request->name,
                'description' => 'Payment from ' . $request->name,
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createCheckoutSession(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'email' => 'required|email',
            'name' => 'required|string',
        ]);

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => config('easy-stripe.currency'),
                        'product_data' => [
                            'name' => 'Payment from ' . $request->name,
                        ],
                        'unit_amount' => $request->amount * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'invoice_creation' => ['enabled' => true],
                'success_url' => route('stripe.payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.payment.cancel'),
                'customer_email' => $request->email,
                'metadata' => [
                    'customer_name' => $request->name,
                    'customer_email' => $request->email,
                ],
            ]);

            return response()->json(['checkout_url' => $session->url]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        if ($sessionId) {
            try {
                $session = Session::retrieve($sessionId);

                $invoiceUrl = null;
                if ($session->invoice) {
                    $invoice = Invoice::retrieve($session->invoice);
                    $invoiceUrl = $invoice->hosted_invoice_url;
                }

                $payment = StripePayment::updateOrCreate(
                    ['stripe_payment_intent_id' => $session->payment_intent],
                    [
                        'amount' => $session->amount_total / 100,
                        'currency' => $session->currency,
                        'status' => $session->payment_status === 'paid' ? 'succeeded' : 'pending',
                        'customer_email' => $session->customer_details->email ?? $session->customer_email,
                        'customer_name' => $session->customer_details->name ?? $session->metadata->customer_name,
                        'stripe_invoice_url' => $invoiceUrl,
                        'description' => 'Checkout payment',
                    ]
                );

                return view('easy-stripe::payment.success', compact('payment'));
            } catch (\Exception $e) {
                return redirect()->route('stripe.payment.index')->with('error', 'Payment verification failed');
            }
        }

        return redirect()->route('stripe.payment.index');
    }

    public function cancel()
    {
        return view('easy-stripe::payment.cancel');
    }

    public function payments()
    {
        $payments = StripePayment::orderBy('created_at', 'desc')->paginate(15);
        return view('easy-stripe::payment.list', compact('payments'));
    }

    public function invoice(StripePayment $payment)
    {
        return view('easy-stripe::payment.invoice', compact('payment'));
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('easy-stripe.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event->data->object);
                break;
            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event->data->object);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        StripePayment::where('stripe_payment_intent_id', $paymentIntent->id)
            ->update(['status' => 'succeeded', 'payment_method' => $paymentIntent->payment_method]);
    }

    private function handlePaymentIntentFailed($paymentIntent)
    {
        StripePayment::where('stripe_payment_intent_id', $paymentIntent->id)
            ->update(['status' => 'failed']);
    }
}
