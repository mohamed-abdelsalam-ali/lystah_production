@extends('adminlayout.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h3 class="card-title">إدارة المستخدمين</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="usersTable">
                            <thead class="table-light">
                                <tr>
                                    <th>اسم الشركة</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الباقة الحالية</th>
                                    <th>حالة الاشتراك</th>
                                    <th>تاريخ الانتهاء</th>
                                    <th>آخر دفعة</th>
                                    <th>حالة الدفع</th>
                                    <th>حالة المستخدم</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    @php
                                        $currentSubscription = $user->subscriptions->first();
                                        $latestPayment = $user->payments->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $user->company_name }}</td>
                                        <td>{{ $user->email }}</td>
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
                                            <!-- dd($currentSubscription) -->
                                            @if($currentSubscription)
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
                                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $user->is_active ? 'نشط' : 'معطل' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.users.show', $user->id) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-warning reset-password"
                                                        data-user-id="{{ $user->id }}">
                                                    <i class="fas fa-key"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-success' }} toggle-status"
                                                        data-user-id="{{ $user->id }}"
                                                        data-current-status="{{ $user->is_active }}">
                                                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                                </button>
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

<!-- Password Reset Modal -->
<div class="modal fade" id="passwordResetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إعادة تعيين كلمة المرور</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>كلمة المرور الجديدة: <strong id="newPassword"></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <button type="button" class="btn btn-primary copy-password">نسخ كلمة المرور</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    $('#usersTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json"
        },
        "responsive": true,
        "order": [[0, 'asc']],
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "info": true
    });

    // Handle payment status changes
    $('.payment-status').change(function() {
        const paymentId = $(this).data('payment-id');
        const status = $(this).val();
        
        updatePaymentStatus(paymentId, status);
    });

    // Handle password reset
    $('.reset-password').click(function() {
        const userId = $(this).data('user-id');
        
        $.ajax({
            url: `/admin/users/${userId}/reset-password`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#newPassword').text(response.password);
                    $('#passwordResetModal').modal('show');
                    toastr.success(response.message);
                }
            },
            error: function() {
                toastr.error('حدث خطأ أثناء إعادة تعيين كلمة المرور');
            }
        });
    });

    // Handle user status toggle
    $('.toggle-status').click(function() {
        const userId = $(this).data('user-id');
        const button = $(this);
        
        $.ajax({
            url: `/admin/users/${userId}/toggle-status`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    
                    // Update button appearance
                    const newStatus = response.is_active;
                    button
                        .removeClass(newStatus ? 'btn-success' : 'btn-danger')
                        .addClass(newStatus ? 'btn-danger' : 'btn-success')
                        .find('i')
                        .removeClass(newStatus ? 'fa-check' : 'fa-ban')
                        .addClass(newStatus ? 'fa-ban' : 'fa-check');
                    
                    // Update status badge
                    button.closest('tr').find('.badge')
                        .removeClass(newStatus ? 'bg-danger' : 'bg-success')
                        .addClass(newStatus ? 'bg-success' : 'bg-danger')
                        .text(newStatus ? 'نشط' : 'معطل');
                }
            },
            error: function() {
                toastr.error('حدث خطأ أثناء تحديث حالة المستخدم');
            }
        });
    });

    // Handle password copy
    $('.copy-password').click(function() {
        const password = $('#newPassword').text();
        navigator.clipboard.writeText(password).then(function() {
            toastr.success('تم نسخ كلمة المرور بنجاح');
        });
    });
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
</script>
@endpush
