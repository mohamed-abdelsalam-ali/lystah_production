@extends('adminlayout.admin')

@section('content')

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ isset($paymentMethod) ? 'Edit' : 'Create' }} Payment Method</h4>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ isset($paymentMethod) ? route('payment-methods.update', $paymentMethod) : route('payment-methods.store') }}">
                        @csrf
                        @if(isset($paymentMethod))
                            @method('PUT')
                        @endif

                        <div class="form-group">
                            <label for="name">Name (Internal)</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $paymentMethod->name ?? '') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Internal identifier for the payment method (e.g., stripe, paypal)</small>
                        </div>

                        <div class="form-group">
                            <label for="display_name">Display Name</label>
                            <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                   id="display_name" name="display_name" value="{{ old('display_name', $paymentMethod->display_name ?? '') }}" required>
                            @error('display_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Name shown to users (e.g., Credit Card, PayPal)</small>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $paymentMethod->description ?? '') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="icon">Icon Class</label>
                            <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                   id="icon" name="icon" value="{{ old('icon', $paymentMethod->icon ?? '') }}">
                            @error('icon')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Font Awesome icon class (e.g., fab fa-cc-visa)</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" 
                                       {{ old('is_active', $paymentMethod->is_active ?? true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="requires_credentials" name="requires_credentials" 
                                       {{ old('requires_credentials', $paymentMethod->requires_credentials ?? false) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="requires_credentials">Requires API Credentials</label>
                            </div>
                        </div>

                        <div class="form-group credentials-fields" style="display: none;">
                            <label for="credentials">API Credentials (JSON)</label>
                            <textarea class="form-control @error('credentials') is-invalid @enderror" 
                                      id="credentials" name="credentials" rows="3">{{ old('credentials', $paymentMethod->credentials ?? '') }}</textarea>
                            @error('credentials')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Enter API credentials in JSON format</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                {{ isset($paymentMethod) ? 'Update' : 'Create' }} Payment Method
                            </button>
                            <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
 

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const requiresCredentials = document.getElementById('requires_credentials');
        const credentialsFields = document.querySelector('.credentials-fields');

        function toggleCredentialsFields() {
            credentialsFields.style.display = requiresCredentials.checked ? 'block' : 'none';
        }

        requiresCredentials.addEventListener('change', toggleCredentialsFields);
        toggleCredentialsFields(); // Initial state
    });
</script>
@endpush
@endsection 