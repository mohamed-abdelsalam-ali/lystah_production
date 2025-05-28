<x-guestRegester-layout>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Subscription Dashboard</div>

                <div class="card-body">
                    @if($status === 'warning')
                        <div class="alert alert-warning">
                            {{ $message }}
                        </div>
                    @else
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                    @endif

                    <div class="subscription-details">
                        <h4>Subscription Details</h4>
                        <table class="table">
                            <tr>
                                <th>Current Plan:</th>
                                <td>{{ ucfirst($user->subscription_plan) }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>{{ ucfirst($user->subscription_status) }}</td>
                            </tr>
                            <tr>
                                <th>Expiration Date:</th>
                                <td>{{ $user->subscription_expires_at ? $user->subscription_expires_at->format('Y-m-d') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Payment Status:</th>
                                <td>{{ ucfirst($user->payment_status) }}</td>
                            </tr>
                        </table>
                    </div>

                    @if($status === 'warning')
                        <div class="mt-4">
                            <a href="{{ route('subscription.renew') }}" class="btn btn-primary">Renew Subscription</a>
                            <a href="{{ route('subscription.upgrade') }}" class="btn btn-success">Upgrade Plan</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</x-guestRegester-layout>
