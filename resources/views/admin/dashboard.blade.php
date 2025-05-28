@extends('adminlayout.admin')
<style>
    /* Adding table shadow for depth */
    #companiesTable {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    /* Table hover effect */
    #companiesTable tbody tr:hover {
        background-color: #f5f5f5;
    }

    /* Better button appearance */
    .btn-sm {
        transition: all 0.3s ease;
    }

    .btn-sm:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Badge styling for better readability */
    .badge {
        font-size: 0.875rem;
    }

    /* Custom select input inside table for better UI */
    .form-select {
        width: auto;
        margin: 0;
        padding: 0.3rem;
        font-size: 0.875rem;
    }

    /* To handle responsive layout */
    @media (max-width: 768px) {
        #companiesTable {
            width: 100%;
            overflow-x: auto;
        }

        #companiesTable th, #companiesTable td {
            white-space: nowrap;
        }
    }

</style>

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
        <!-- Menu -->
  
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2"style="text-align: center;">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">إجمالي الشركات</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_companies'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2"style="text-align: center;">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">الشركات النشطة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_companies'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2"style="text-align: center;">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">إجمالي الإيرادات</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($stats['total_revenue'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2"style="text-align: center;">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">المدفوعات المعلقة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_payments'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

 

    <!-- Recent Activities -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary"style="text-align: center;">النشاطات الأخيرة</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($recentActivities as $activity)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $activity['details'] }}</h6>
                                    <small>{{ $activity['date']->diffForHumans() }}</small>
                                </div>
                                <small class="text-{{ $activity['type'] === 'subscription' ? 'success' : 'info' }}">
                                    <i class="fas fa-{{ $activity['type'] === 'subscription' ? 'star' : 'money-bill' }}"></i>
                                    {{ $activity['type'] === 'subscription' ? 'اشتراك جديد' : 'دفعة جديدة' }}
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary"style="text-align: center;">توزيع الباقات</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>الباقة</th>
                                    <th>عدد المشتركين</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($planStats as $plan)
                                    <tr>
                                        <td>{{ $plan['name'] }}</td>
                                        <td>{{ $plan['count'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Companies Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"style="text-align: center;">إدارة الشركات والمدفوعات</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="companiesTable">
                            <thead class="table-light">
                                <tr>
                                    <th>الشركة</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الباقة الحالية</th>
                                    <th>حالة الاشتراك</th>
                                    <th>تاريخ الانتهاء</th>
                                    <th>آخر دفعة</th>
                                    <th>حالة الدفع</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($companies as $company)
                                    @php
                                        $currentSubscription = $company->subscriptions->first();
                                        $latestPayment = $company->payments->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $company->company_name }}</td>
                                        <td>{{ $company->email }}</td>
                                        <td>
                                            @if($currentSubscription)
                                                {{ $currentSubscription->subscriptionPlan->name }}
                                            @else
                                                <span class="badge bg-warning">لا يوجد اشتراك</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($currentSubscription)
                                                <span class="badge {{ $currentSubscription->status === 'active' ? 'bg-success' : 'bg-warning' }}">
                                                    {{ $currentSubscription->status === 'active' ? 'نشط' : 'منتهي' }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($currentSubscription && $currentSubscription->ends_at)
                                                {{ $currentSubscription->ends_at->format('Y-m-d') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($latestPayment)
                                                {{ number_format($latestPayment->amount, 2) }} $
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($latestPayment)
                                                <select class="form-select payment-status" 
                                                        data-payment-id="{{ $latestPayment->id }}"
                                                        {{ $latestPayment->status === 'paid' ? 'disabled' : '' }}>
                                                    <option value="pending" {{ $latestPayment->status === 'pending' ? 'selected' : '' }}>معلق</option>
                                                    <option value="paid" {{ $latestPayment->status === 'paid' ? 'selected' : '' }}>مدفوع</option>
                                                    <option value="failed" {{ $latestPayment->status === 'failed' ? 'selected' : '' }}>فشل</option>
                                                </select>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                @if($latestPayment && $latestPayment->status === 'pending')
                                                    <button type="button" 
                                                            class="btn btn-sm btn-success confirm-payment" 
                                                            data-payment-id="{{ $latestPayment->id }}"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#paymentModal">
                                                        تأكيد الدفع
                                                    </button>
                                                @endif
                                                <form action="{{ route('admin.switch-company') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="company_id" value="{{ $company->id }}">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-exchange-alt"></i> تبديل الحساب
                                                    </button>
                                                </form>
                                            </div>
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
</div>

<!-- Payment Confirmation Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الدفع</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    <input type="hidden" name="payment_id" id="paymentId">
                    <div class="mb-3">
                        <label for="transactionId" class="form-label">رقم العملية</label>
                        <input type="text" class="form-control" id="transactionId" name="transaction_id">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" id="confirmPaymentBtn">تأكيد</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    $('#companiesTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json"
        },
        "responsive": true,  // Make the table responsive on mobile
        "order": [[0, 'asc']],  // You can set the default sorting column
        "paging": true,        // Enable pagination
        "lengthChange": false, // Disable the option to change the number of items per page
        "searching": true,     // Enable search bar
        "info": true           // Show info about current page
    });

    // Handle payment status changes
    $('.payment-status').change(function() {
        const paymentId = $(this).data('payment-id');
        const status = $(this).val();
        
        updatePaymentStatus(paymentId, status);
    });

    // Handle payment confirmation modal
    $('.confirm-payment').click(function() {
        const paymentId = $(this).data('payment-id');
        $('#paymentId').val(paymentId);
    });

    // Handle payment confirmation
    $('#confirmPaymentBtn').click(function() {
        const paymentId = $('#paymentId').val();
        const transactionId = $('#transactionId').val();
        
        updatePaymentStatus(paymentId, 'paid', transactionId);
        $('#paymentModal').modal('hide');
    });

    function updatePaymentStatus(paymentId, status, transactionId = null) {
        const data = {
            payment_id: paymentId,
            status: status
        };

        if (transactionId) {
            data.transaction_id = transactionId;
        }

        $.ajax({
            url: '{{ route("admin.update-payment-status") }}',
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('تم تحديث حالة الدفع بنجاح');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    toastr.error('حدث خطأ أثناء تحديث حالة الدفع');
                }
            },
            error: function() {
                toastr.error('حدث خطأ أثناء تحديث حالة الدفع');
            }
        });
    }
});
</script>
@endpush
