@extends('easy-stripe.layouts.app')

@section('title', 'Payment Cancelled')

@section('content')
    <div class="payment-card text-center">
        <div class="mb-4">
            <div class="cancel-icon mb-3">
                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="40" cy="40" r="40" fill="#EF4444"/>
                    <path d="M30 30L50 50M50 30L30 50" stroke="white" stroke-width="4" stroke-linecap="round"/>
                </svg>
            </div>
            <h2 class="text-danger mb-2">Payment Cancelled</h2>
            <p class="text-muted">Your payment was not completed</p>
        </div>

        <div class="alert alert-warning">
            <strong>What happened?</strong><br>
            You cancelled the payment process or the payment failed. No charges have been made to your card.
        </div>

        <div class="mt-4">
            <a href="{{ route('payment.index') }}" class="btn btn-primary">Try Again</a>
            <a href="/" class="btn btn-outline-secondary">Go Home</a>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .cancel-icon {
            animation: shakeIn 0.5s ease-in-out;
        }

        @keyframes shakeIn {
            0%, 100% {
                transform: translateX(0);
            }
            25% {
                transform: translateX(-10px);
            }
            75% {
                transform: translateX(10px);
            }
        }
    </style>
@endpush
