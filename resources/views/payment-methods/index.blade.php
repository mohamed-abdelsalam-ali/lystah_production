@extends('adminlayout.admin')

@section('content')

        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Payment Methods</h4>
                    <a href="{{ route('payment-methods.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Payment Method
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Display Name</th>
                                    <th>Status</th>
                                    <th>Requires Credentials</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($paymentMethods as $paymentMethod)
                                    <tr>
                                        <td>{{ $paymentMethod->name }}</td>
                                        <td>{{ $paymentMethod->display_name }}</td>
                                        <td>
                                            <span class="badge badge-{{ $paymentMethod->is_active ? 'success' : 'danger' }}">
                                                {{ $paymentMethod->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $paymentMethod->requires_credentials ? 'info' : 'secondary' }}">
                                                {{ $paymentMethod->requires_credentials ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('payment-methods.show', $paymentMethod) }}" 
                                                   class="btn btn-info btn-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('payment-methods.edit', $paymentMethod) }}" 
                                                   class="btn btn-primary btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('payment-methods.destroy', $paymentMethod) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" 
                                                            title="Delete" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No payment methods found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
  

@endsection
