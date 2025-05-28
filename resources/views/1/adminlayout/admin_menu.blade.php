<div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                  <a href="{{ route('admin.dashboard') }}"> <h6 class="m-0 font-weight-bold text-primary" style="text-align: center;">القائمة الرئيسية</h6></a> 
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('subscription-plans.index') }}" class="btn btn-block btn-primary">
                                <i class="fas fa-tags"></i> باقات الاشتراك
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('payment-methods.index') }}" class="btn btn-block btn-success">
                                <i class="fas fa-credit-card"></i> طرق الدفع
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-block btn-info">
                                <i class="fas fa-users"></i> المستخدمين
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-block btn-warning">
                                <i class="fas fa-chart-bar"></i> التقارير
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>