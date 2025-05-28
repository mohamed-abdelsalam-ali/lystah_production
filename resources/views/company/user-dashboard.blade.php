<x-guestRegester-layout>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>{{ auth()->user()->company_name }} Dashboard</h3>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Subscription Information -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">معلومات الاشتراك</h4>
                        </div>
                        <div class="card-body">
                            @if($currentSubscription)
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>الباقة:</strong> {{ $currentSubscription->subscriptionPlan->name }}</p>
                                        <p><strong>الحالة:</strong> 
                                            <span class="badge {{ $currentSubscription->status === 'active' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $currentSubscription->status === 'active' ? 'نشط' : 'منتهي' }}
                                            </span>
                                        </p>
                                        <p><strong>تاريخ البدء:</strong> {{ $currentSubscription->starts_at->format('Y-m-d') }}</p>
                                        <p><strong>تاريخ الانتهاء:</strong> {{ $currentSubscription->ends_at->format('Y-m-d') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>السعر:</strong> {{ number_format($currentSubscription->price, 2) }} $</p>
                                        @if($latestPayment)
                                            <p><strong>طريقة الدفع:</strong> {{ $latestPayment->paymentMethod->name }}</p>
                                            <p><strong>حالة الدفع:</strong> 
                                                <span class="badge {{ $latestPayment->status === 'paid' ? 'bg-success' : 'bg-warning' }}">
                                                    {{ $latestPayment->status === 'paid' ? 'مدفوع' : 'معلق' }}
                                                </span>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    لا يوجد اشتراك نشط.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <a href="/home" class="btn btn-primary">
                            الذهاب للصفحة الرئيسية
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-guestRegester-layout> 