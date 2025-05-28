<x-guestRegester-layout>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Subscription Payments</h4>
                </div>

                <div class="card-body">
                    @if(session('warning'))
                        <div class="alert alert-warning">
                            {{ session('warning') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Current Subscription Status -->
                    <div class="subscription-status mb-4">
                        <h5>Current Subscription Status</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Plan:</th>
                                    <td>{{ $user->subscription_plan ?? 'No active subscription' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($user->subscription_status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Expires At:</th>
                                    <td>{{ $user->subscription_expires_at ? \Carbon\Carbon::parse($user->subscription_expires_at)->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Latest Payment -->
                    @if($payments->isNotEmpty())
                    <div class="latest-payment mb-4">
                        <h5>Latest Payment</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Amount:</th>
                                    <td>{{ number_format($payments->first()->amount, 2) }} {{ $payments->first()->currency }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @switch($payments->first()->status)
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
                                                <span class="badge bg-secondary">{{ $payments->first()->status }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th>Date:</th>
                                    <td>{{ $payments->first()->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Transaction ID:</th>
                                    <td>{{ $payments->first()->id ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Payment History -->
                    <div class="payment-history">
                        <h5>Payment History</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Transaction ID</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                                            <td>{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</td>
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
                                            <td>{{ $payment->id ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('subscription.payment.show', $payment->id) }}" 
                                                   class="btn btn-sm btn-info">
                                                    View Details
                                                </a>
                                                @if($payment->status === 'pending' || $payment->status === 'failed')
                                                    <a href="{{ route('subscription.payment.showRetry', $payment->id) }}" 
                                                       class="btn btn-sm btn-warning">
                                                        Retry Payment
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No payment history found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4">
                        @if($payments->isNotEmpty() && ($payments->first()->status === 'pending' || $payments->first()->status === 'failed'))
                            <a href="{{ route('subscription.payment.showRetry', $payments->first()->id) }}" 
                               class="btn btn-warning">
                                Retry Payment
                            </a>
                        @endif
                        
                        @if($user->subscription_status !== 'active')
                            <a href="{{ route('subscription.renew') }}" 
                               class="btn btn-primary">
                                Renew Subscription
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-guestRegester-layout>
