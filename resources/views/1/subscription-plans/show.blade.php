@extends('adminlayout.admin')

@section('content') 
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">View Subscription Plan</h3>
                        <div>
                            <a href="{{ route('subscription-plans.edit', $subscriptionPlan) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('subscription-plans.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 200px;">Internal Name</th>
                                <td>{{ $subscriptionPlan->name }}</td>
                            </tr>
                            <tr>
                                <th>Display Name</th>
                                <td>{{ $subscriptionPlan->display_name }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $subscriptionPlan->description ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td>${{ number_format($subscriptionPlan->price, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Duration</th>
                                <td>{{ $subscriptionPlan->duration_in_days }} days</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge badge-{{ $subscriptionPlan->is_active ? 'success' : 'danger' }}">
                                        {{ $subscriptionPlan->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Features</th>
                                <td>
                                    @if(!empty($subscriptionPlan->features) && is_array($subscriptionPlan->features))
                                        <ul class="list-unstyled mb-0">
                                            @foreach($subscriptionPlan->features as $feature)
                                                <li>
                                                    <i class="fas fa-check text-success"></i>
                                                    {{ is_array($feature) ? ($feature['name'] ?? '') : $feature }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="mb-0">No features specified</p>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
