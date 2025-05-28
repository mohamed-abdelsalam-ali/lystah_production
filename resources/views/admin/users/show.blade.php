@extends('adminlayout.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- User Profile Card -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header">
                    <h3 class="card-title">معلومات المستخدم</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ $user->profile_photo ?? asset('images/default-avatar.png') }}" 
                             class="rounded-circle img-fluid" 
                             alt="صورة المستخدم"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                    <dl class="row">
                        <dt class="col-sm-4">اسم الشركة</dt>
                        <dd class="col-sm-8">{{ $user->company_name }}</dd>

                        <dt class="col-sm-4">البريد الإلكتروني</dt>
                        <dd class="col-sm-8">{{ $user->email }}</dd>

                        <dt class="col-sm-4">رقم الهاتف</dt>
                        <dd class="col-sm-8">{{ $user->phone ?? 'غير متوفر' }}</dd>

                        <dt class="col-sm-4">العنوان</dt>
                        <dd class="col-sm-8">{{ $user->address ?? 'غير متوفر' }}</dd>

                        <dt class="col-sm-4">تاريخ التسجيل</dt>
                        <dd class="col-sm-8">{{ $user->created_at->format('Y-m-d') }}</dd>

                        <dt class="col-sm-4">الحالة</dt>
                        <dd class="col-sm-8">
                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->is_active ? 'نشط' : 'معطل' }}
                            </span>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Subscription History -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h3 class="card-title">سجل الاشتراكات</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>الباقة</th>
                                    <th>تاريخ البداية</th>
                                    <th>تاريخ الانتهاء</th>
                                    <th>السعر</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->subscriptions as $subscription)
                                    <tr>
                                        <td>{{ $subscription->subscriptionPlan->name }}</td>
                                        <td>{{ $subscription->starts_at->format('Y-m-d') }}</td>
                                        <td>{{ $subscription->ends_at->format('Y-m-d') }}</td>
                                        <td>{{ number_format($subscription->price, 2) }} $</td>
                                        <td>
                                            <span class="badge {{ $subscription->status === 'active' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $subscription->status === 'active' ? 'نشط' : 'منتهي' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">لا يوجد سجل اشتراكات</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div class="card shadow mt-4">
                <div class="card-header">
                    <h3 class="card-title">سجل المدفوعات</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>رقم الفاتورة</th>
                                    <th>المبلغ</th>
                                    <th>طريقة الدفع</th>
                                    <th>تاريخ الدفع</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->payments as $payment)
                                    <tr>
                                        <td>{{ $payment->invoice_number }}</td>
                                        <td>{{ number_format($payment->amount, 2) }} $</td>
                                        <td>{{ $payment->payment_method }}</td>
                                        <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <span class="badge {{ $payment->status === 'paid' ? 'bg-success' : ($payment->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                                {{ $payment->status === 'paid' ? 'مدفوع' : ($payment->status === 'pending' ? 'معلق' : 'فشل') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">لا يوجد سجل مدفوعات</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
