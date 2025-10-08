@extends('easy-stripe.layouts.app')

@section('title', 'Payment Successful')

@section('content')
    <div class="payment-card text-center">
        <div class="mb-4">
            <div class="success-icon mb-3">
                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="40" cy="40" r="40" fill="#10B981"/>
                    <path d="M25 40L35 50L55 30" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h2 class="text-success mb-2">Payment Successful!</h2>
            <p class="text-muted">Thank you for your payment</p>
        </div>

        @if(isset($payment))
            <div class="alert alert-light text-start">
                <div class="row">
                    <div class="col-6 mb-2">
                        <strong>Transaction ID:</strong>
                    </div>
                    <div class="col-6 mb-2">
                        <small class="text-muted">{{ $payment->stripe_payment_intent_id }}</small>
                    </div>

                    <div class="col-6 mb-2">
                        <strong>Amount:</strong>
                    </div>
                    <div class="col-6 mb-2">
                        ${{ number_format($payment->amount, 2) }}
                    </div>

                    <div class="col-6 mb-2">
                        <strong>Status:</strong>
                    </div>
                    <div class="col-6 mb-2">
                        <span class="badge bg-success">{{ ucfirst($payment->status) }}</span>
                    </div>

                    <div class="col-6 mb-2">
                        <strong>Email:</strong>
                    </div>
                    <div class="col-6 mb-2">
                        {{ $payment->customer_email }}
                    </div>

                    <div class="col-6">
                        <strong>Date:</strong>
                    </div>
                    <div class="col-6">
                        {{ $payment->created_at->format('M d, Y H:i') }}
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-4">
            <a href="{{ route('stripe.payment.index') }}" class="btn btn-primary">Make Another Payment</a>
            <a href="{{ route('stripe.payments.list') }}" class="btn btn-outline-secondary">View All Payments</a>
        </div>

        <div class="mt-4">
            <small class="text-muted">A receipt has been sent to your email address.</small>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .success-icon {
            animation: scaleIn 0.5s ease-in-out;
        }

        @keyframes scaleIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
@endpush
