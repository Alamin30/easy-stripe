@extends('easy-stripe.layouts.app')

@section('title', 'Make Payment - Stripe Integration')

@section('content')
    <div class="payment-card">
        <div class="text-center mb-4">
            <h2 class="mb-2">Secure Payment</h2>
            <p class="text-muted">Complete your payment using Stripe</p>
        </div>

        <form id="stripe-payment-form">
            @csrf

            <!-- Customer Information -->
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-4">
                <label for="amount" class="form-label">Amount (USD)</label>
                <input type="number" class="form-control" id="amount" name="amount" min="1" step="0.01" value="50.00" required>
            </div>

            <!-- Card Element -->
            {{--<div class="mb-3">
                <label for="card-element" class="form-label">Card Information</label>
                <div id="card-element"></div>
                <div id="card-errors" role="alert"></div>
            </div>--}}

            <!-- Submit Button -->
            <button id="submit-button" type="submit" class="btn btn-primary w-100">
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                <span id="button-text">Pay Now</span>
            </button>

            <div class="mt-3 text-center">
                <small class="text-muted">
                    <i class="bi bi-lock"></i> Secured by Stripe
                </small>
            </div>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('stripe.payments.list') }}" class="text-decoration-none">View All Payments</a>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Handle form submission
        const form = document.getElementById('stripe-payment-form');
        const submitButton = document.getElementById('submit-button');
        const buttonText = document.getElementById('button-text');

        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            // Disable button and show loading state
            submitButton.disabled = true;
            submitButton.classList.add('loading');
            buttonText.textContent = 'Redirecting...';

            try {
                // Get form data
                const name = document.getElementById('name').value;
                const email = document.getElementById('email').value;
                const amount = document.getElementById('amount').value;

                // Create checkout session
                const response = await fetch('{{ route('stripe.payment.checkout') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ name, email, amount })
                });

                const data = await response.json();

                if (data.error) {
                    throw new Error(data.error);
                }

                // Redirect to Stripe Checkout
                window.location.href = data.checkout_url;

            } catch (error) {
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;

                // Re-enable button
                submitButton.disabled = false;
                submitButton.classList.remove('loading');
                buttonText.textContent = 'Pay Now';
            }
        });
    </script>
@endpush
