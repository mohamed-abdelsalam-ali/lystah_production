@extends('adminlayout.admin')

@section('content')
<div class="container-fluid">
    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h3 class="card-title" style="text-align: center;">تصفية التقارير</h3>
        </div>
        <div class="card-body">
            <form id="reportFilters" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>حالة المستخدم</label>
                            <select name="user_status" class="form-select">
                                <option value="">الكل</option>
                                <option value="active" {{ request('user_status') === 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ request('user_status') === 'inactive' ? 'selected' : '' }}>معطل</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>حالة الاشتراك</label>
                            <select name="subscription_status" class="form-select">
                                <option value="">الكل</option>
                                <option value="active" {{ request('subscription_status') === 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="expired" {{ request('subscription_status') === 'expired' ? 'selected' : '' }}>منتهي</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>حالة الدفع</label>
                            <select name="payment_status" class="form-select">
                                <option value="">الكل</option>
                                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>مدفوع</option>
                                <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>معلق</option>
                                <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>فشل</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>الباقة</label>
                            <select name="subscription_plan" class="form-select">
                                <option value="">الكل</option>
                                @foreach($subscriptionPlans as $plan)
                                    <option value="{{ $plan->id }}" {{ request('subscription_plan') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>من تاريخ</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>إلى تاريخ</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-group w-100">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter"></i> تصفية
                            </button>
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-redo"></i> إعادة تعيين
                            </a>
                            <button type="button" class="btn btn-success" id="exportExcel">
                                <i class="fas fa-file-excel"></i> تصدير Excel
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- stats Cards -->
    <div class="row mb-4" style="text-align: center;">
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي المستخدمين
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                الاشتراكات النشطة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_subscriptions'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                إجمالي الإيرادات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($stats['total_revenue'], 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                المدفوعات المعلقة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($stats['pending_payments'], 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Results Table -->
    <div class="card shadow mb-4" style="text-align: center;">
        <div class="card-header">
            <h3 class="card-title">نتائج التقرير</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="reportsTable">
                    <thead class="table-light">
                        <tr>
                            <th>اسم الشركة</th>
                            <th>البريد الإلكتروني</th>
                            <th>الباقة</th>
                            <th>تاريخ البداية</th>
                            <th>تاريخ الانتهاء</th>
                            <th>المبلغ</th>
                            <th>حالة الدفع</th>
                            <th>حالة المستخدم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats['reports'] as $report)
                            <tr>
                                <td>{{ $report['company_name'] }}</td>
                                <td>{{ $report['email'] }}</td>
                                <td>{{ $report['subscription_plan_name'] ?? '-' }}</td>
                                <td>{{ $report['subscription_start'] ? date('Y-m-d', strtotime($report['subscription_start'])) : '-' }}</td>
                                <td>{{ $report['subscription_end'] ? date('Y-m-d', strtotime($report['subscription_end'])) : '-' }}</td>
                                <td>${{ number_format($report['amount'] ?? 0, 2) }}</td>
                                <td>
                                    <span class="badge {{ 
                                        $report['payment_status'] === 'paid' ? 'bg-success' : 
                                        ($report['payment_status'] === 'pending' ? 'bg-warning' : 'bg-danger') 
                                    }}">
                                        {{ $report['payment_status'] ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $report['is_active'] ? 'bg-success' : 'bg-danger' }}">
                                        {{ $report['is_active'] ? 'نشط' : 'معطل' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    
    const table = $('#reportsTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json"
        },
        "responsive": true,
        "order": [[0, 'asc']],
        "pageLength": 25,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    // Handle Excel export
    $('#exportExcel').click(function() {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.append('export', 'excel');
        window.location.href = currentUrl.toString();
    });

    // Auto-submit form on filter change
    $('#reportFilters select, #reportFilters input[type="date"]').change(function() {
        $('#reportFilters').submit();
    });
});
</script>
@endpush
