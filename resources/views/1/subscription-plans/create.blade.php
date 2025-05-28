@extends('adminlayout.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Create Subscription Plan</h3>
                        <a href="{{ route('subscription-plans.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('subscription-plans.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="name">Internal Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" 
                                   name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="display_name">Display Name</label>
                            <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                   id="display_name" name="display_name" value="{{ old('display_name') }}" required>
                            @error('display_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="price">Price</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="duration_in_days">Duration (Days)</label>
                            <input type="number" min="1" class="form-control @error('duration_in_days') is-invalid @enderror" 
                                   id="duration_in_days" name="duration_in_days" value="{{ old('duration_in_days') }}" required>
                            @error('duration_in_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" class="custom-control-input" id="is_active" 
                                       name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Plan Features</label>
                            <div id="features-container">
                                @if(old('features'))
                                    @foreach(old('features') as $index => $feature)
                                        <div class="feature-item mb-2">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="features[{{ $index }}][name]" 
                                                       value="{{ $feature['name'] ?? '' }}" placeholder="Feature description">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger remove-feature">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="feature-item mb-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="features[0][name]" 
                                                   placeholder="Feature description">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-danger remove-feature">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-secondary mt-2" id="add-feature">
                                <i class="fas fa-plus"></i> Add Feature
                            </button>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create Plan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const featuresContainer = document.getElementById('features-container');
        const addFeatureBtn = document.getElementById('add-feature');

        function getNextIndex() {
            const inputs = featuresContainer.querySelectorAll('input[name^="features["]');
            let maxIndex = -1;
            inputs.forEach(input => {
                const match = input.name.match(/features\[(\d+)\]/);
                if (match) {
                    maxIndex = Math.max(maxIndex, parseInt(match[1]));
                }
            });
            return maxIndex + 1;
        }

        function addFeature() {
            const nextIndex = getNextIndex();
            const template = `
                <div class="feature-item mb-2">
                    <div class="input-group">
                        <input type="text" class="form-control" name="features[${nextIndex}][name]" 
                               placeholder="Feature description">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger remove-feature">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            featuresContainer.insertAdjacentHTML('beforeend', template);
        }

        function removeFeature(event) {
            if (event.target.classList.contains('remove-feature') || 
                event.target.closest('.remove-feature')) {
                const button = event.target.classList.contains('remove-feature') ? 
                              event.target : 
                              event.target.closest('.remove-feature');
                const item = button.closest('.feature-item');
                if (featuresContainer.querySelectorAll('.feature-item').length > 1) {
                    item.remove();
                }
            }
        }

        addFeatureBtn.addEventListener('click', addFeature);
        featuresContainer.addEventListener('click', removeFeature);
    });
</script>
@endpush