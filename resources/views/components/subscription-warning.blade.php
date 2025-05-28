@if(session('subscription_expired'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>تنبيه!</strong> اشتراكك قد انتهى. يرجى تجديد اشتراكك للاستمرار في استخدام النظام.
        <a href="{{ route('subscription.renew') }}" class="btn btn-primary btn-sm ms-3">تجديد الاشتراك</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif 