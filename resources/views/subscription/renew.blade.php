<x-guestRegester-layout>
    <style>
        /* Enhanced Radio Button Styling */
        .plan-radio-group {
            position: relative;
            margin-bottom: 1rem;
        }
        
        .plan-radio-input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .plan-radio-label {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background-color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .plan-radio-input:checked + .plan-radio-label {
            border-color: #3b82f6;
            background-color: #f0f7ff;
            box-shadow: 0 0 0 1px #3b82f6;
        }
        
        .plan-radio-input:focus + .plan-radio-label {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }
        
        .plan-radio-input:disabled + .plan-radio-label {
            opacity: 0.6;
            cursor: not-allowed;
            background-color: #f8fafc;
        }
        
        .plan-radio-indicator {
            width: 24px;
            height: 24px;
            border: 2px solid #cbd5e1;
            border-radius: 50%;
            margin-left: 1rem;
            position: relative;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        
        .plan-radio-input:checked + .plan-radio-label .plan-radio-indicator {
            border-color: #3b82f6;
            background-color: #3b82f6;
        }
        
        .plan-radio-indicator::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: white;
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .plan-radio-input:checked + .plan-radio-label .plan-radio-indicator::after {
            opacity: 1;
        }
        
        /* Popular Plan Badge */
        .popular-badge {
            position: absolute;
            top: 0;
            left: 0;
            background: linear-gradient(135deg, #f59e0b, #ef4444);
            color: white;
            padding: 0.25rem 1rem;
            border-bottom-right-radius: 12px;
            font-size: 0.75rem;
            font-weight: bold;
            transform: translateY(-100%);
            animation: slideDown 0.3s ease forwards;
        }
        
        .free-trial-badge {
            position: absolute;
            top: 0;
            left: 0;
            background: linear-gradient(135deg, #f59e0b, #ef4444);
            color: white;
            padding: 0.25rem 1rem;
            border-bottom-right-radius: 12px;
            font-size: 0.75rem;
            font-weight: bold;
            transform: translateY(-100%);
            animation: slideDown 0.3s ease forwards;
        }
        
        @keyframes slideDown {
            to { transform: translateY(0); }
        }
        
        /* Plan Details */
        .plan-details {
            flex-grow: 1;
        }
        
        .plan-name {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }
        
        .plan-price {
            font-size: 1.75rem;
            font-weight: 700;
            color: #3b82f6;
        }
        
        .plan-period {
            font-size: 0.9rem;
            color: #64748b;
        }
        
        .plan-features {
            margin-top: 1rem;
            padding-left: 0;
            list-style: none;
        }
        
        .plan-features li {
            margin-bottom: 0.5rem;
            position: relative;
            padding-left: 1.5rem;
        }
        
        .plan-features li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #10b981;
            font-weight: bold;
        }
        
        /* Payment Method Cards */
        .payment-method-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: white;
            position: relative;
        }
        
        .payment-method-card:hover {
            border-color: #93c5fd;
        }
        
        .payment-method-input:checked + .payment-method-card {
            border-color: #3b82f6;
            background-color: #f0f7ff;
            box-shadow: 0 0 0 1px #3b82f6;
        }
    </style>

        <div class="row justify-content-center">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center py-3" style="text-align: center;">
                        <h3 class="mb-3"   style="  float: right !important;" >تجديد الاشتراك</h3>
                    </div>

                    <div class="card-body p-4">
                        @if($currentSubscription)
                            <div class="alert alert-info d-flex align-items-center mb-4">
                                <i class="fas fa-info-circle fa-lg me-3"></i>
                                <div class="w-100">
                                    <h5 class="alert-heading">الاشتراك الحالي</h5>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <p class="mb-1"><strong>الباقة:</strong> {{ $currentSubscription->subscriptionPlan->name }}</p>
                                        </div>
                                        <div class="col-lg-3">
                                            <p class="mb-1"><strong>الحالة:</strong> {{ $currentSubscription->status === 'active' ? 'نشط' : 'منتهي' }}</p>
                                        </div>
                                        <div class="col-lg-3">
                                            <p class="mb-1"><strong>تاريخ البدء:</strong> {{ $currentSubscription->starts_at->format('Y-m-d') }}</p>
                                        </div>
                                        <div class="col-lg-3">
                                            <p class="mb-1"><strong>تاريخ الانتهاء:</strong> {{ $currentSubscription->ends_at->format('Y-m-d') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('subscription.process_renewal') }}" id="renewalForm">
                            @csrf
                            
                            <!-- Subscription Plans -->
                            <div class="mb-5">
                                <h4 class="mb-4 text-center text-primary">اختر باقة الاشتراك</h4>
                                
                                <div class="row g-4">
                                    @foreach($plans as $plan)
                                        <div class="col-md-6 col-lg-3 d-flex">
                                            <div class="plan-radio-group w-100 d-flex flex-column">
                                                <input class="plan-radio-input" 
                                                    type="radio" 
                                                    name="plan_id" 
                                                    value="{{ $plan->id }}" 
                                                    id="plan{{ $plan->id }}"
                                                    {{ $plan->is_active ? '' : 'disabled' }}
                                                    {{ old('plan_id') == $plan->id ? 'checked' : '' }}
                                                    required>
                                                
                                                <label class="plan-radio-label flex-grow-1 d-flex flex-column" for="plan{{ $plan->id }}">
                                                    @if($plan->is_popular)
                                                        <span class="popular-badge">الأكثر شيوعاً</span>
                                                    @endif      
                                                    @if($plan->is_free)
                                                        <span class="free-trial-badge">تجربة مجانية</span>
                                                    @endif 
                                                    <div class="plan-details flex-grow-1">
                                                        <h5 class="plan-name">{{ $plan->name }}</h5>
                                                        <div class="d-flex align-items-center mb-3">
                                                            <span class="plan-price">{{ number_format($plan->price, 2) }} $</span>
                                                            <span class="plan-period ms-2">/ {{ $plan->duration_in_days }} يوم</span>
                                                        </div>
                                                        
                                                        <ul class="plan-features mb-3">
                                                            @foreach($plan->features as $feature)
                                                                <li>{{ $feature['name'] }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    
                                                    <div class="plan-footer mt-auto p-3 bg-light rounded-bottom">
                                                        <span class="plan-radio-indicator"></span>
                                                        <span class="ms-2">{{ $plan->is_active ? 'اختر هذه الباقة' : 'غير متاح حالياً' }}</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                @error('plan_id')
                                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Payment Methods -->
                            <div class="mb-5">
                                <h4 class="mb-4 text-center text-primary">اختر طريقة الدفع</h4>
                                
                                <div class="row g-4">
                                    @foreach($paymentMethods as $method)
                                        <div class="col-md-6 col-lg-3 d-flex">
                                            <div class="payment-method-group w-100 d-flex flex-column">
                                                <input class="payment-method-input" 
                                                    type="radio" 
                                                    name="payment_method_id"
                                                    value="{{ $method->id }}" 
                                                    id="payment{{ $method->id }}"
                                                    {{ old('payment_method_id') == $method->id ? 'checked' : '' }}
                                                    required>
                                                
                                                <label class="payment-method-card flex-grow-1 d-flex flex-column" for="payment{{ $method->id }}">
                                                    <div class="payment-details flex-grow-1 p-3">
                                                        <div class="d-flex align-items-center mb-3">
                                                            <img src="{{ asset('icons/'.$method->icon) }}.svg" alt="{{ $method->name }}" class="me-3" width="40">
                                                            <div>
                                                                <h6 class="mb-0">{{ $method->name }}</h6>
                                                                <small class="text-muted">{{ $method->description }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="payment-footer mt-auto p-3 bg-light rounded-bottom d-flex align-items-center">
                                                        <span class="payment-radio-indicator"></span>
                                                        <span class="ms-2">اختر هذه الطريقة</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                @error('payment_method_id')
                                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    أوافق على <button type="button" class="btn btn-link p-0 text-decoration-underline" data-bs-toggle="modal" data-bs-target="#termsModal">
                                        عرض الشروط والأحكام
                                    </button>
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-3 d-md-flex justify-content-md-center mt-5">
                                <button type="submit" class="btn btn-primary btn-lg px-5 py-3">
                                    <i class="fas fa-sync-alt me-2"></i> تأكيد التجديد
                                </button>
                                <a href="{{ route('company.dashboard') }}" class="btn btn-outline-secondary btn-lg px-5 py-3">
                                    <i class="fas fa-arrow-left me-2"></i> رجوع
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">شروط وأحكام الاشتراك</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="terms-content">
                        <h6>1. مدة الاشتراك</h6>
                        <p>يبدأ الاشتراك من تاريخ الدفع وينتهي بعد المدة المحددة في الباقة المختارة.</p>

                        <h6>2. التجديد التلقائي</h6>
                        <p>سيتم تجديد الاشتراك تلقائياً عند انتهاء المدة إذا كان لديك وسيلة دفع صالحة.</p>

                        <h6>3. الإلغاء</h6>
                        <p>يمكنك إلغاء الاشتراك في أي وقت، وسيستمر حتى نهاية الفترة المدفوعة.</p>

                        <h6>4. الاسترداد</h6>
                        <p>لا يمكن استرداد المبلغ المدفوع بعد بدء الاشتراك.</p>

                        <h6>5. التغييرات</h6>
                        <p>نحتفظ بالحق في تغيير الأسعار والخطط مع إشعار مسبق.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" id="acceptTerms">موافق</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Enhanced radio button interactions
        document.querySelectorAll('.plan-radio-input').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.plan-radio-label').forEach(label => {
                    label.classList.remove('active');
                });
                if (this.checked) {
                    this.closest('.plan-radio-group').querySelector('.plan-radio-label').classList.add('active');
                }
            });
            
            // Initialize active state
            if (radio.checked) {
                radio.closest('.plan-radio-group').querySelector('.plan-radio-label').classList.add('active');
            }
        });
        
        // Payment method interactions
        document.querySelectorAll('.payment-method-input').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.payment-method-card').forEach(card => {
                    card.classList.remove('active');
                });
                if (this.checked) {
                    this.closest('.payment-method-group').querySelector('.payment-method-card').classList.add('active');
                }
            });
            
            // Initialize active state
            if (radio.checked) {
                radio.closest('.payment-method-group').querySelector('.payment-method-card').classList.add('active');
            }
        });
        
        // Form validation and modal handling
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('renewalForm');
            const termsModal = document.getElementById('termsModal');
            const modal = new bootstrap.Modal(termsModal);
            
            // Form submission handler
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                if (!form.checkValidity()) {
                    event.stopPropagation();
                    form.classList.add('was-validated');
                    return;
                }
                modal.show();
            });
            
            // Terms button click handler
            document.querySelector('[data-bs-target="#termsModal"]').addEventListener('click', function(e) {
                e.preventDefault();
                modal.show();
            });

            // Accept terms button click handler
            document.getElementById('acceptTerms').addEventListener('click', function() {
                modal.hide();
                form.submit();
            });
        });
    </script>
</x-guestRegester-layout>