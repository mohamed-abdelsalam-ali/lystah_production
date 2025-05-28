<x-guestRegester-layout>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Retry Payment</h4>
                </div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="payment-details mb-4">
                        <h5>Payment Details</h5>
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
                            </table>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('subscription.payment.retry', $payment->id) }}" id="paymentForm">
                        @csrf

                        <div class="mb-3">
                            <label for="payment_method_id" class="form-label">Select Payment Method</label>
                            <select class="form-select @error('payment_method_id') is-invalid @enderror" 
                                    id="payment_method_id" 
                                    name="payment_method_id" 
                                    required>
                                <option value="">Select a payment method</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id }}" 
                                            data-code="{{ $method->code }}"
                                            {{ old('payment_method_id') == $method->id ? 'selected' : '' }}>
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_method_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="cardDetails" style="display: none;">
                            <div class="mb-3">
                                <label for="card_number" class="form-label">Card Number</label>
                                <input type="text" 
                                       class="form-control @error('card_number') is-invalid @enderror" 
                                       id="card_number" 
                                       name="card_number" 
                                       value="{{ old('card_number') }}"
                                       placeholder="1234 5678 9012 3456">
                                @error('card_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="expiry_month" class="form-label">Expiry Month</label>
                                        <select class="form-select @error('expiry_month') is-invalid @enderror" 
                                                id="expiry_month" 
                                                name="expiry_month">
                                            <option value="">MM</option>
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" 
                                                        {{ old('expiry_month') == $i ? 'selected' : '' }}>
                                                    {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('expiry_month')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="expiry_year" class="form-label">Expiry Year</label>
                                        <select class="form-select @error('expiry_year') is-invalid @enderror" 
                                                id="expiry_year" 
                                                name="expiry_year">
                                            <option value="">YYYY</option>
                                            @for($i = date('Y'); $i <= date('Y') + 10; $i++)
                                                <option value="{{ $i }}" 
                                                        {{ old('expiry_year') == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('expiry_year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="text" 
                                       class="form-control @error('cvv') is-invalid @enderror" 
                                       id="cvv" 
                                       name="cvv" 
                                       value="{{ old('cvv') }}"
                                       placeholder="123">
                                @error('cvv')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                Process Payment
                            </button>
                            <a href="{{ route('subscription.payment.show', $payment->id) }}" 
                               class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethodSelect = document.getElementById('payment_method_id');
    const cardDetails = document.getElementById('cardDetails');

    paymentMethodSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const paymentCode = selectedOption.getAttribute('data-code');
        
        if (paymentCode === 'card') {
            cardDetails.style.display = 'block';
        } else {
            cardDetails.style.display = 'none';
        }
    });

    // Trigger change event on page load if a payment method is already selected
    if (paymentMethodSelect.value) {
        paymentMethodSelect.dispatchEvent(new Event('change'));
    }
});
</script>
</x-guestRegester-layout>
