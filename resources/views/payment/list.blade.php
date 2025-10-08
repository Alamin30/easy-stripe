@extends('easy-stripe.layouts.app')

@section('title', 'All Payments')

@section('content')
    <div class="payment-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Payment History</h2>
            <a href="{{ route('stripe.payment.index') }}" class="btn btn-primary">New Payment</a>
        </div>

        @if($payments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>
                                <small class="text-muted">#{{ $payment->id }}</small>
                            </td>
                            <td>
                                <strong>{{ $payment->customer_name }}</strong><br>
                                <small class="text-muted">{{ $payment->customer_email }}</small>
                            </td>
                            <td>
                                <strong>${{ number_format($payment->amount, 2) }}</strong><br>
                                <small class="text-muted text-uppercase">{{ $payment->currency }}</small>
                            </td>
                            <td>
                                @if($payment->status === 'succeeded')
                                    <span class="badge bg-success">Success</span>
                                @elseif($payment->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($payment->status === 'failed')
                                    <span class="badge bg-danger">Failed</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $payment->created_at->format('M d, Y') }}<br>
                                <small class="text-muted">{{ $payment->created_at->format('H:i A') }}</small>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#paymentModal{{ $payment->id }}">
                                    View Details
                                </button>
                            </td>
                            <td>
                                @if($payment->stripe_invoice_url)
                                    <a href="{{ $payment->stripe_invoice_url }}"
                                       class="btn btn-sm btn-outline-success"
                                       target="_blank">
                                        <i class="bi bi-receipt"></i> Invoice
                                    </a>
                                @endif
                            </td>
                        </tr>

                        <!-- Payment Detail Modal -->
                        <div class="modal fade" id="paymentModal{{ $payment->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Payment Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <dl class="row mb-0">
                                            <dt class="col-sm-5">Payment Intent ID:</dt>
                                            <dd class="col-sm-7"><small>{{ $payment->stripe_payment_intent_id }}</small></dd>

                                            @if($payment->stripe_charge_id)
                                                <dt class="col-sm-5">Charge ID:</dt>
                                                <dd class="col-sm-7"><small>{{ $payment->stripe_charge_id }}</small></dd>
                                            @endif

                                            <dt class="col-sm-5">Customer Name:</dt>
                                            <dd class="col-sm-7">{{ $payment->customer_name }}</dd>

                                            <dt class="col-sm-5">Email:</dt>
                                            <dd class="col-sm-7">{{ $payment->customer_email }}</dd>

                                            <dt class="col-sm-5">Amount:</dt>
                                            <dd class="col-sm-7">${{ number_format($payment->amount, 2) }} {{ strtoupper($payment->currency) }}</dd>

                                            <dt class="col-sm-5">Status:</dt>
                                            <dd class="col-sm-7">
                                                @if($payment->status === 'succeeded')
                                                    <span class="badge bg-success">Success</span>
                                                @elseif($payment->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($payment->status === 'failed')
                                                    <span class="badge bg-danger">Failed</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                                @endif
                                            </dd>

                                            @if($payment->payment_method)
                                                <dt class="col-sm-5">Payment Method:</dt>
                                                <dd class="col-sm-7">{{ $payment->payment_method }}</dd>
                                            @endif

                                            @if($payment->description)
                                                <dt class="col-sm-5">Description:</dt>
                                                <dd class="col-sm-7">{{ $payment->description }}</dd>
                                            @endif

                                            <dt class="col-sm-5">Created At:</dt>
                                            <dd class="col-sm-7">{{ $payment->created_at->format('M d, Y H:i A') }}</dd>

                                            <dt class="col-sm-5">Updated At:</dt>
                                            <dd class="col-sm-7">{{ $payment->updated_at->format('M d, Y H:i A') }}</dd>
                                        </dl>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $payments->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="alert alert-info text-center">
                <h5>No Payments Yet</h5>
                <p class="mb-0">There are no payment records to display. <a href="{{ route('stripe.payment.index') }}">Make your first payment</a></p>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        .table {
            margin-bottom: 0;
        }
        .table thead th {
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }
        dl {
            font-size: 14px;
        }
        dt {
            font-weight: 600;
            color: #666;
        }
        dd {
            color: #333;
        }
    </style>
@endpush
