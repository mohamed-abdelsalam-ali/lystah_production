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
                                
                                @if($currentSubscription->ends_at->isPast() || $currentSubscription->ends_at->diffInDays(now()) < 30)
                                    <div class="mt-3">
                                        <a href="{{ route('subscription.renew') }}" class="btn btn-warning">
                                            <i class="fas fa-sync-alt"></i> تجديد الاشتراك
                                        </a>
                                    </div>
                                @else
                                    <div class="mt-3">
                                        <a href="{{ route('subscription.renew') }}" class="btn btn-warning">
                                            <i class="fas fa-sync-alt"></i> ترقية الباقة
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-warning">
                                    لا يوجد اشتراك نشط. <a href="{{ route('subscription.renew') }}" class="alert-link">اشترك الآن</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <a href="{{ route('company.config') }}" class="btn btn-primary">
                            إعدادات الشركة
                        </a>
                        <a href="/home" class="btn btn-primary ms-2">
                            الذهاب للصفحة الرئيسية
                        </a>
                    </div>


                    
                    <p class="fs-17 text-bg-dark text-center">المستخدمين</p>
                    <div class="card-body table-responsive">
                            <table class="table table-striped table-bordered cell-border " style="font-size: smaller;width:100%" id="kt_ecommerce_sales_table_inbox">
                                <thead  style="background: #67b1736e;">
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">


                                <th class="text-center">الإسم</th>
                                <th class="text-center">البريد الإلكتروني</th>
                                <th class="text-center">الوظيف</th>
                                <th class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-500">
                            @foreach($users as $user)
                                <tr>
                                    <td class="text-center">{{ $user->username }}</td>
                                    <td class="text-center">{{ $user->email }}</td>
                                    <td class="text-center">{{ $user->is_admin ? 'Admin' : 'User' }}</td>
                                    <td>
                                        {{-- <!-- Edit/Delete buttons --> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</x-guestRegester-layout>