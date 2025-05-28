<x-guestRegester-layout>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Payment Details</h4>
                </div>

                <div class="card-body">
                    <div class="payment-details">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Payment ID:</th>
                                    <td>{{ $payment->id }}</td>
                                </tr>
                                <tr>
                                    <th>Amount:</th>
                                    <td>{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @switch($payment->status)
                                            @case('completed')
                                                <span class="badge bg-success">Completed</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @break
                                            @case('failed')
                                                <span class="badge bg-danger">Failed</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $payment->status }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th>Transaction ID:</th>
                                    <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Method:</th>
                                    <td>{{ $payment->payment_method ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Created At:</th>
                                    <td>{{ $payment->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At:</th>
                                    <td>{{ $payment->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                @if($payment->description)
                                <tr>
                                    <th>Description:</th>
                                    <td>{{ $payment->description }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <div class="mt-4">
                        @if($payment->status === 'pending' || $payment->status === 'failed')
                            <a href="{{ route('subscription.payment.showRetry', $payment->id) }}" 
                               class="btn btn-warning">
                                Retry Payment
                            </a>
                        @endif
                        
                        <a href="{{ route('subscription.payments') }}" 
                           class="btn btn-secondary">
                            Back to Payments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-guestRegester-layout>
