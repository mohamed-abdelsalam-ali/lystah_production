@extends('adminlayout.admin')

@section('content')


        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Create New Payment Method</h4>
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

                    <form method="POST" action="{{ route('payment-methods.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="name">Name (Internal)</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Internal identifier for the payment method (e.g., stripe, paypal)</small>
                        </div>

                        <div class="form-group">
                            <label for="display_name">Display Name</label>
                            <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                   id="display_name" name="display_name" value="{{ old('display_name') }}" required>
                            @error('display_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Name shown to users (e.g., Credit Card, PayPal)</small>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="icon">Icon Class</label>
                            <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                   id="icon" name="icon" value="{{ old('icon') }}">
                            @error('icon')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Font Awesome icon class (e.g., fab fa-cc-visa)</small>
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="is_active" value="0">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="requires_credentials" value="0">
                                <input type="checkbox" class="custom-control-input" id="requires_credentials" name="requires_credentials" value="1" {{ old('requires_credentials', 0) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="requires_credentials">Requires API Credentials</label>
                            </div>
                        </div>

                        <div class="form-group credentials-fields" style="display: none;">
                            <label for="credentials">API Credentials</label>
                            <div id="credentials-container">
                                @php
                                    $oldCredentials = old('credentials', []);
                                    $credentials = [];
                                    if (is_array($oldCredentials)) {
                                        foreach ($oldCredentials as $cred) {
                                            if (isset($cred['key'], $cred['value'])) {
                                                $credentials[] = [
                                                    'key' => $cred['key'],
                                                    'value' => $cred['value']
                                                ];
                                            }
                                        }
                                    }
                                @endphp
                                @forelse($credentials as $index => $cred)
                                    <div class="credential-pair mb-2">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="credentials[{{ $index }}][key]" 
                                                       value="{{ $cred['key'] }}" placeholder="Key">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="credentials[{{ $index }}][value]" 
                                                       value="{{ $cred['value'] }}" placeholder="Value">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger btn-sm remove-credential">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="credential-pair mb-2">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="credentials[0][key]" placeholder="Key">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="credentials[0][value]" placeholder="Value">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger btn-sm remove-credential">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            <button type="button" class="btn btn-secondary btn-sm mt-2" id="add-credential">
                                <i class="fas fa-plus"></i> Add Credential
                            </button>
                            @error('credentials')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Add API credential key-value pairs</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                Create Payment Method
                            </button>
                            <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>


@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const requiresCredentials = document.getElementById('requires_credentials');
        const credentialsFields = document.querySelector('.credentials-fields');
        const credentialsContainer = document.getElementById('credentials-container');
        const addCredentialBtn = document.getElementById('add-credential');

        function toggleCredentialsFields() {
            credentialsFields.style.display = requiresCredentials.checked ? 'block' : 'none';
        }

        function getNextIndex() {
            const inputs = credentialsContainer.querySelectorAll('input[name^="credentials["]');
            let maxIndex = -1;
            inputs.forEach(input => {
                const match = input.name.match(/credentials\[(\d+)\]/);
                if (match) {
                    maxIndex = Math.max(maxIndex, parseInt(match[1]));
                }
            });
            return maxIndex + 1;
        }

        function addCredentialPair() {
            const nextIndex = getNextIndex();
            const template = `
                <div class="credential-pair mb-2">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="credentials[${nextIndex}][key]" placeholder="Key">
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="credentials[${nextIndex}][value]" placeholder="Value">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remove-credential">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            credentialsContainer.insertAdjacentHTML('beforeend', template);
        }

        function removeCredentialPair(event) {
            if (event.target.classList.contains('remove-credential') || 
                event.target.closest('.remove-credential')) {
                const button = event.target.classList.contains('remove-credential') ? 
                              event.target : 
                              event.target.closest('.remove-credential');
                const pair = button.closest('.credential-pair');
                if (credentialsContainer.querySelectorAll('.credential-pair').length > 1) {
                    pair.remove();
                }
            }
        }

        requiresCredentials.addEventListener('change', toggleCredentialsFields);
        addCredentialBtn.addEventListener('click', addCredentialPair);
        credentialsContainer.addEventListener('click', removeCredentialPair);

        toggleCredentialsFields(); // Initial state
    });
</script>

@endpush