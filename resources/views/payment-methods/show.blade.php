@extends('adminlayout.admin')

@section('content')

        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Payment Method Details</h4>
                    <div>
                        <a href="{{ route('payment-methods.edit', $paymentMethod) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px;">Name (Internal)</th>
                                    <td>{{ $paymentMethod->name }}</td>
                                </tr>
                                <tr>
                                    <th>Display Name</th>
                                    <td>{{ $paymentMethod->display_name }}</td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $paymentMethod->description ?: 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Icon</th>
                                    <td>
                                        @if($paymentMethod->icon)
                                            <i class="{{ $paymentMethod->icon }}"></i>
                                            <span class="ml-2">{{ $paymentMethod->icon }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge badge-{{ $paymentMethod->is_active ? 'success' : 'danger' }}">
                                            {{ $paymentMethod->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Requires Credentials</th>
                                    <td>
                                        <span class="badge badge-{{ $paymentMethod->requires_credentials ? 'info' : 'secondary' }}">
                                            {{ $paymentMethod->requires_credentials ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                </tr>
                                @if($paymentMethod->requires_credentials && $paymentMethod->credentials)
                                <tr>
                                    <th>API Credentials</th>
                                    <td>
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Key</th>
                                                    <th>Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($paymentMethod->credentials as $key => $value)
                                                <tr>
                                                    <td>{{ $key }}</td>
                                                    <td>{{ $value }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $paymentMethod->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated</th>
                                    <td>{{ $paymentMethod->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
 
@endsection
