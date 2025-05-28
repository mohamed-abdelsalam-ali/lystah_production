<!-- resources/views/auth/register.blade.php -->
<x-guestRegester-layout>
  <style>
    /* Loading Overlay Styles */
    .loading-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.9);
      z-index: 9999;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }

    .loading-content {
      text-align: center;
      background: white;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .loading-spinner {
      width: 120px;
      margin-bottom: 1rem;
    }

    .progress-bar-container {
      width: 300px;
      height: 20px;
      background: #f0f0f0;
      border-radius: 10px;
      overflow: hidden;
      margin: 1rem 0;
    }

    .progress-bar {
      width: 0%;
      height: 100%;
      background: linear-gradient(90deg, #4e73df, #224abe);
      transition: width 0.3s ease;
    }

    .loading-text {
      color: #4e73df;
      font-size: 1.1rem;
      margin-top: 0.5rem;
    }
    /* Base RTL Styles */
    body {
      direction: rtl;
      text-align: right;
    }

    .card {
      direction: rtl;
      text-align: right;
    }

    .form-group.row {
      direction: rtl;
    }

    .col-form-label {
      text-align: right;
    }

    .form-control {
      text-align: right;
    }

    .custom-file-label {
      text-align: right;
    }

    .custom-file-label::after {
      content: "تصفح";
      border-radius: 7px 0 0 7px;
      right: 0;
    }

    .invalid-feedback {
      text-align: right;
    }

    .form-text {
      text-align: right;
    }

    /* RTL Progress Bar Styles */
    .progress {
      direction: rtl;
    }

    .progress-bar {
      float: right;
    }

    /* RTL Step Indicators */
    .step-indicator {
      display: flex;
      justify-content: space-between;
      margin-bottom: 3rem;
      position: relative;
      direction: rtl;
    }

    .step {
      text-align: center;
      flex: 1;
      position: relative;
      /* z-index: 2; */
    }

    .step:not(:last-child)::after {
      content: '';
      position: absolute;
      top: 25px;
      /* left: 50%; */
      width: 100%;
      height: 3px;
      background: #e3e6f0;
      z-index: 1;
      transition: background-color 0.3s ease;
    }

    .step.completed:not(:last-child)::after {
      background: #1cc88a;
    }

    .step-number {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: #e3e6f0;
      color: #5a5c69;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 15px;
      position: relative;
      z-index: 3;
      transition: all 0.3s ease;
      font-weight: 600;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .step.active .step-number {
      background: #4e73df;
      color: white;
      transform: scale(1.1);
      box-shadow: 0 4px 8px rgba(78, 115, 223, 0.3);
    }

    .step.completed .step-number {
      background: #1cc88a;
      color: white;
    }

    .step-title {
      font-size: 1rem;
      color: #5a5c69;
      font-weight: 500;
      transition: all 0.3s ease;
      white-space: nowrap;
      position: relative;
      z-index: 2;
    }

    .step.active .step-title {
      color: #4e73df;
      font-weight: 600;
      transform: scale(1.05);
    }

    .step.completed .step-title {
      color: #1cc88a;
    }

    @media (max-width: 768px) {
      .step-indicator {
        flex-direction: column;
        align-items: flex-end;
      }

      .step {
        width: 100%;
        margin-bottom: 1.5rem;
      }

      .step:not(:last-child)::after {
        display: none;
      }

      .step-number {
        margin-right: 1rem;
        margin-left: 0;
      }

      .step-title {
        text-align: right;
        margin-right: 1rem;
      }
    }

    /* RTL Navigation Buttons */
    .navigation-buttons {
      direction: rtl;
    }

    .btn i {
      margin-left: 0.5rem;
      margin-right: 0;
    }

    /* RTL Form Step Transitions */
    .form-step {
      transform: translateX(-20px);
    }

    .form-step.active {
      transform: translateX(0);
    }

    /* RTL Step Instructions */
    .step-instructions {
      direction: rtl;
      text-align: right;
      border-right: 4px solid #4e73df;
      border-left: none;
    }

    /* RTL Subscription Plans */
    .plans-container {
      direction: rtl;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.5rem;
      padding: 1rem;
    }

    .plan-card {
      background: white;
      border: 1px solid #e3e6f0;
      border-radius: 10px;
      padding: 2rem;
      text-align: center;
      position: relative;
      transition: all 0.3s ease;
    }

    .plan-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0.5rem 1rem rgba(78, 115, 223, 0.1);
    }

    .plan-name {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 1rem;
      color: #2e59d9;
    }

    .plan-price {
      font-size: 2.5rem;
      font-weight: 700;
      color: #5a5c69;
      margin-bottom: 1.5rem;
    }

    .plan-price small {
      font-size: 1rem;
      color: #858796;
    }

    .plan-features {
      list-style: none;
      padding: 0;
      margin: 0 0 2rem;
      text-align: right;
    }

    .plan-features li {
      padding: 0.5rem 0;
      color: #666;
    }

    .plan-features li i {
      color: #1cc88a;
      margin-left: 0.5rem;
    }

    .plan-card.popular::before {
      content: 'الأكثر شعبية';
      right: 0;
      border-bottom-left-radius: 15px;
      border-bottom-right-radius: 0;
    }

    .plan-features li i {
      margin-left: 0.5rem;
      margin-right: 0;
    }

    /* RTL Form Layout */
    .form-group.row {
      margin-right: 0;
      margin-left: 0;
    }

    .col-md-4 {
      padding-right: 15px;
      padding-left: 15px;
    }

    .col-md-6 {
      padding-right: 15px;
      padding-left: 15px;
    }

    /* RTL Button Icons */
    .btn i.fa-arrow-right {
      margin-right: 0.5rem;
      margin-left: 0;
    }

    .btn i.fa-arrow-left {
      margin-left: 0.5rem;
      margin-right: 0;
    }

    /* RTL Form Validation */
    .invalid-feedback {
      text-align: right;
    }

    .valid-feedback {
      text-align: right;
    }

    /* RTL Custom File Input */
    .custom-file-input:lang(ar) ~ .custom-file-label::after {
      content: "تصفح";
    }

    /* RTL Responsive Adjustments */
    @media (max-width: 768px) {
      .form-group.row {
        flex-direction: column;
      }
      
      .col-form-label {
        text-align: right;
        margin-bottom: 0.5rem;
      }
    }

    .custom-file-label::after {
      content: "Browse";
      background: #4e73df;
      color: white;
      border-radius: 0 7px 7px 0;
      padding: 0.75rem 1rem;
    }

    .card {
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
      transition: all 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
    }

    .card-header {
      font-weight: 600;
      letter-spacing: 0.5px;
      background: linear-gradient(135deg, #4e73df 0%, #2e59d9 100%);
      padding: 1.5rem;
    }

    .form-section {
      background: white;
      border-radius: 10px;
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.05);
    }

    .form-section h5 {
      color: #4e73df;
      font-weight: 600;
      margin-bottom: 1.5rem;
      position: relative;
      padding-bottom: 0.5rem;
    }

    .form-section h5::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 50px;
      height: 3px;
      background: #4e73df;
      border-radius: 3px;
    }

    .form-control:focus {
      border-color: #4e73df;
      box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
    }

    .custom-file-label {
      border-radius: 8px;
      padding: 0.75rem 1rem;
      border: 1px solid #d1d3e2;
      background-color: white;
    }

    .btn-primary {
      background: linear-gradient(135deg, #4e73df 0%, #2e59d9 100%);
      border: none;
      border-radius: 8px;
      padding: 0.75rem 2rem;
      font-weight: 600;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 0.5rem 1rem rgba(78, 115, 223, 0.2);
    }

    .back-link {
      color: #5a5c69;
      text-decoration: none;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      margin-left: 1rem;
    }

    .back-link:hover {
      color: #4e73df;
      transform: translateX(-3px);
    }

    .form-text {
      font-size: 0.85rem;
      color: #858796;
    }

    .invalid-feedback {
      font-size: 0.85rem;
      color: #e74a3b;
    }

    #logo-preview {
      border-radius: 8px;
      border: 2px solid #e3e6f0;
      transition: all 0.3s ease;
    }

    #logo-preview:hover {
      transform: scale(1.05);
      box-shadow: 0 0.5rem 1rem rgba(58, 59, 69, 0.1);
    }

    .required-label::after {
      content: "*";
      color: #e74a3b;
      margin-left: 4px;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      font-weight: 500;
      color: #5a5c69;
    }

    @media (max-width: 768px) {
      .card {
        margin: 1rem;
      }
      
      .form-section {
        padding: 1.5rem;
      }
    }

    /* RTL Progress Bar Styles */
    .progress {
      height: 15px;
      border-radius: 10px;
      margin-bottom: 2.5rem;
      background-color: #f8f9fc;
      box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
      direction: rtl;
    }

    .progress-bar {
      background: linear-gradient(135deg, #2e59d9 0%, #4e73df 100%);
      transition: width 0.5s ease-in-out;
      position: relative;
      float: right;
    }

    .progress-bar::after {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      width: 5px;
      background: rgba(255,255,255,0.3);
      animation: progress-pulse 1.5s infinite;
    }

    @keyframes progress-pulse {
      0% { opacity: 0.3; }
      50% { opacity: 0.6; }
      100% { opacity: 0.3; }
    }

    /* RTL Navigation Buttons */
    .navigation-buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 2.5rem;
      padding-top: 2rem;
      border-top: 1px solid #e3e6f0;
      direction: rtl;
    }

    .btn-outline-primary {
      color: #4e73df;
      border-color: #4e73df;
      padding: 0.75rem 2rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
      transform: translateX(3px);
    }

    .btn-primary:hover {
      transform: translateX(-3px);
    }

    /* RTL Form Step Transitions */
    .form-step {
      display: none;
      opacity: 0;
      transform: translateX(-20px);
      transition: all 0.5s ease;
    }

    .form-step.active {
      display: block;
      opacity: 1;
      transform: translateX(0);
    }

    /* RTL Step Instructions */
    .step-instructions {
      background: #f8f9fc;
      border-radius: 10px;
      padding: 1.5rem;
      margin-bottom: 2rem;
      border-right: 4px solid #4e73df;
      direction: rtl;
      text-align: right;
    }

    .step-instructions h6 {
      color: #4e73df;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .step-instructions p {
      color: #5a5c69;
      margin-bottom: 0;
    }

    /* Subscription Plans Styles */
    .plans-container {
      display: flex;
      justify-content: space-between;
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .plan-card {
      flex: 1;
      background: white;
      border-radius: 15px;
      padding: 2rem;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
      transition: all 0.3s ease;
      border: 2px solid #e3e6f0;
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }

    .plan-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15);
    }

    .plan-card.selected {
      border-color: #4e73df;
      background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
    }

    .plan-card.popular {
      border-color: #1cc88a;
    }

    .plan-card.popular::before {
      content: 'Most Popular';
      position: absolute;
      top: 0;
      right: 0;
      background: #1cc88a;
      color: white;
      padding: 0.5rem 1rem;
      font-size: 0.8rem;
      font-weight: 600;
      border-bottom-left-radius: 15px;
    }

    .plan-name {
      font-size: 1.5rem;
      font-weight: 600;
      color: #4e73df;
      margin-bottom: 1rem;
    }

    .plan-price {
      font-size: 2.5rem;
      font-weight: 700;
      color: #5a5c69;
      margin-bottom: 1.5rem;
    }

    .plan-price small {
      font-size: 1rem;
      font-weight: 400;
      color: #858796;
    }

    .plan-features {
      list-style: none;
      padding: 0;
      margin: 0 0 1.5rem 0;
    }

    .plan-features li {
      padding: 0.5rem 0;
      color: #5a5c69;
      display: flex;
      align-items: center;
    }

    .plan-features li i {
      color: #1cc88a;
      margin-left: 0.5rem;
    }

    .plan-select-btn {
      width: 100%;
      padding: 0.75rem;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .plan-select-btn.selected {
      background: #4e73df;
      color: white;
    }

    @media (max-width: 768px) {
      .plans-container {
        flex-direction: column;
      }
    }

    /* Free Trial Plan Styles */
    .plan-card.free-trial {
      border: 2px solid #4e73df;
      background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
      position: relative;
    }

    .plan-card.free-trial::before {
      content: 'تجربة مجانية';
      position: absolute;
      top: 0;
      right: 0;
      background: #4e73df;
      color: white;
      padding: 0.5rem 1rem;
      font-size: 0.8rem;
      font-weight: 600;
      border-bottom-left-radius: 15px;
    }

    .plan-card.free-trial .plan-name {
      color: #4e73df;
    }

    .plan-card.free-trial .plan-price {
      color: #1cc88a;
    }

    .trial-period {
      font-size: 0.9rem;
      color: #5a5c69;
      margin-top: -1rem;
      margin-bottom: 1.5rem;
    }

    .trial-features {
      list-style: none;
      padding: 0;
      margin: 0 0 1.5rem 0;
      background: #f8f9fc;
      padding: 1rem;
      border-radius: 8px;
    }

    .trial-features li {
      padding: 0.5rem 0;
      color: #5a5c69;
      display: flex;
      align-items: center;
    }

    .trial-features li i {
      color: #4e73df;
      margin-left: 0.5rem;
    }

    .trial-note {
      font-size: 0.85rem;
      color: #858796;
      text-align: center;
      margin-top: 1rem;
      padding: 0.5rem;
      background: #f8f9fc;
      border-radius: 5px;
    }

    /* Validation Message Styles */
    .validation-message {
      display: none;
      color: #e74a3b;
      font-size: 0.875rem;
      margin-top: 0.25rem;
      text-align: right;
    }

    .validation-message.show {
      display: block;
    }

    .is-invalid {
      border-color: #e74a3b !important;
    }

    .is-valid {
      border-color: #1cc88a !important;
    }

    /* Plan Selection Validation */
    .plan-selection-error {
      display: none;
      color: #e74a3b;
      font-size: 0.875rem;
      text-align: center;
      margin-top: 1rem;
      padding: 0.5rem;
      background-color: #f8f9fc;
      border-radius: 5px;
      border: 1px solid #e74a3b;
    }

    .plan-selection-error.show {
      display: block;
    }

    .plans-container {
      position: relative;
    }

    /* Payment Method Styles */
    .payment-methods-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1rem;
      margin: 2rem 0;
    }

    .payment-method-card {
      display: flex;
      align-items: center;
      padding: 1.5rem;
      border: 1px solid #ddd;
      border-radius: 8px;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .payment-method-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .payment-method-card.active {
      border-color: #4CAF50;
      box-shadow: 0 0 10px rgba(76, 175, 80, 0.3);
    }

    .method-icon {
      font-size: 2rem;
      margin-right: 1rem;
      color: #666;
    }

    .method-details {
      flex: 1;
    }

    .method-name {
      font-weight: bold;
      margin-bottom: 0.5rem;
    }

    .method-description {
      font-size: 0.9rem;
      color: #666;
    }

    .payment-method-error {
      display: none;
      color: #dc3545;
      text-align: center;
      margin-bottom: 1rem;
    }

    /* Loading Overlay Styles */
    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0.8);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .loading-content {
      text-align: center;
    }

    .loading-spinner {
      width: 50px;
      height: 50px;
      margin-bottom: 1rem;
    }

    .progress-bar-container {
      width: 200px;
      height: 10px;
      background-color: #ddd;
      border-radius: 5px;
      margin-bottom: 1rem;
    }

    .progress-bar {
      width: 0%;
      height: 100%;
      background-color: #4CAF50;
      border-radius: 5px;
    }

    .loading-text {
      font-size: 1.2rem;
      font-weight: bold;
    }
  </style>
   
  <!-- Loading Overlay -->
  <div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
      <img src="{{ asset('assets/img/loading-spinner.gif') }}" alt="Loading..." class="loading-spinner">
      <div class="progress-bar-container">
        <div class="progress-bar" id="progressBar"></div>
      </div>
      <div class="loading-text" id="loadingText">جاري إنشاء حسابك...</div>
      <div class="loading-subtext" id="loadingSubtext" style="font-size: 0.9rem; color: #666; margin-top: 0.5rem;">قد تستغرق هذه العملية بضع دقائق...</div>
    </div>
  </div>

  <div class="col-md-12">
    <div class="card shadow-lg">
      <div class="card-header">
        <h4 class="mb-0 text-white">{{ __('Company Registration') }}</h4>
      </div>

      <div class="card-body p-4">
        <!-- Progress Bar -->
        <div class="progress">
          <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <!-- Step Indicators -->
        <div class="step-indicator">
          <div class="step active" data-step="1">
            <div class="step-number">1</div>
            <div class="step-title">تفاصيل الشركة</div>
          </div>
          <div class="step" data-step="2">
            <div class="step-number">2</div>
            <div class="step-title">خطة الاشتراك</div>
          </div>
          <div class="step" data-step="3">
            <div class="step-number">3</div>
            <div class="step-title">حساب المسؤول</div>
          </div>
          <div class="step" data-step="3">
            <div class="step-number">4</div>
            <div class="step-title"> طريقة الدفع'</div>
          </div>
        </div>

        <form method="POST" action="{{ route('regestercompany') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
          @csrf
          
          <!-- Hidden input for selected plan -->
          <input type="hidden" name="subscription_plan" id="selected_plan" value="">
          <input type="hidden" name="subscription_plan_id" id="selected_plan_id" value="">

          <!-- Company Information Section -->
          <div class="form-step active" id="step1">
            <div class="step-instructions">
              <h6>الخطوة 1: معلومات الشركة</h6>
              <p>يرجى تقديم تفاصيل شركتك. سيتم استخدام هذه المعلومات لإنشاء ملف تعريف شركتك.</p>
            </div>

            <div class="form-section">
              <h5>تفاصيل الشركة</h5>
              
              <!-- Company Name -->
              <div class="form-group row">
                <label for="company_name" class="col-md-4 col-form-label text-md-right required-label">
                  {{ __('اسم الشركة') }}
                </label>
                <div class="col-md-6">
                  <input id="company_name" type="text" 
                         class="form-control @error('company_name') is-invalid @enderror" 
                         name="company_name" value="{{ old('company_name') }}" 
                         required autofocus>
                  @error('company_name')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <small class="form-text">
                    سيتم استخدام هذا لإنشاء قاعدة بيانات شركتك
                  </small>
                </div>
              </div>

              <!-- Company Logo -->
              <div class="form-group row">
                <label for="company_logo" class="col-md-4 col-form-label text-md-right">
                  {{ __('شعار الشركة') }}
                </label>
                <div class="col-md-6">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input @error('company_logo') is-invalid @enderror" 
                           id="company_logo" name="company_logo" accept="image/*">
                    <label class="custom-file-label" for="company_logo">
                      اختر ملف الشعار...
                    </label>
                    @error('company_logo')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <small class="form-text">
                    الحد الأقصى 2 ميجابايت (JPEG, PNG, SVG)
                  </small>
                </div>
              </div>
              
              <!-- Logo Preview -->
              <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                  <img id="logo-preview" src="#" alt="Logo Preview" 
                       class="img-thumbnail d-none" style="max-width: 150px;">
                </div>
              </div>
            </div>

            <div class="navigation-buttons">
              <button type="button" class="btn btn-primary next-step" data-next="2">
                <i class="fas fa-arrow-left ml-2"></i> الخطوة التالية
              </button>
              <div></div>
            </div>
          </div>

          <!-- Subscription Plan Section -->
          <div class="form-step" id="step2">
            <div class="step-instructions">
              <h6>الخطوة 2: اختر خطتك</h6>
              <p>ابدأ بتجربة مجانية لمدة 30 يوم أو اختر إحدى خططنا المدفوعة.</p>
            </div>

            <div class="form-section">
              <h5>اختر خطة الاشتراك</h5>
              <div class="plan-selection-error" id="plan-selection-error" >
                يرجى اختيار خطة قبل المتابعة
              </div>

              <div class="plans-container">
                @foreach($plans as $plan)
                <div class="plan-card {{ $plan->is_free ? 'free-trial' : '' }} {{ $plan->is_popular ? 'popular' : '' }}" data-plan="{{ $plan->name }}" data-plan_id="{{ $plan->id }}">
                  <div class="plan-name">{{ $plan->display_name }}</div>
                  <div class="plan-price">${{ number_format($plan->price, 2) }}<small>/{{ $plan->duration_in_days == 30 ? 'شهرياً' : ($plan->duration_in_days == 365 ? 'سنوياً' : $plan->duration_in_days . ' يوم') }}</small></div>
                  @if($plan->is_free)
                  <div class="trial-period">لمدة {{ $plan->duration_in_days }} يوم</div>
                  @endif
                  <ul class="{{ $plan->is_free ? 'trial-features' : 'plan-features' }}">
                    @foreach($plan->features as $feature)
                    <li><i class="fas fa-check"></i> {{ $feature['name'] }}</li>
                    @endforeach
                  </ul>
                  <button type="button" class="btn {{ $plan->is_free ? 'btn-primary' : 'btn-outline-primary' }} plan-select-btn">
                    {{ $plan->is_free ? 'ابدأ التجربة المجانية' : 'اختر الخطة' }}
                  </button>
                  @if($plan->is_free)
                  <div class="trial-note">
                    لا تتطلب بطاقة ائتمان - يمكنك الترقية في أي وقت
                  </div>
                  @endif
                </div>
                @endforeach
              </div>
            </div>

            <div class="navigation-buttons">
              <button type="button" class="btn btn-primary next-step" data-next="3" disabled>
                <i class="fas fa-arrow-left ml-2"></i> الخطوة التالية
              </button>
              <button type="button" class="btn btn-outline-primary prev-step" data-prev="1">
                الخطوة السابقة <i class="fas fa-arrow-right mr-2"></i>
              </button>
            </div>
          </div>

        

          <!-- Admin User Section -->
          <div class="form-step" id="step3">
            <div class="step-instructions">
              <h6>الخطوة 3: حساب المسؤول</h6>
              <p>قم بإنشاء حساب المسؤول لإدارة ملف تعريف شركتك.</p>
            </div>

            <div class="form-section">
              <h5>حساب المسؤول</h5>
              
              <!-- Name -->
              <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right required-label">
                  {{ __('الاسم الكامل') }}
                </label>
                <div class="col-md-6">
                  <input id="name" type="text" 
                         class="form-control @error('name') is-invalid @enderror" 
                         name="name" value="{{ old('name') }}" required>
                  @error('name')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <!-- Email -->
              <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right required-label">
                  {{ __('البريد الإلكتروني') }}
                </label>
                <div class="col-md-6">
                  <input id="email" type="email" class="form-control" name="email" required>
                  <div class="validation-message" id="email-error"></div>
                </div>
              </div>

              <!-- Password -->
              <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right required-label">
                  {{ __('كلمة المرور') }}
                </label>
                <div class="col-md-6">
                  <input id="password" type="password" class="form-control" name="password" required>
                  <div class="validation-message" id="password-error"></div>
                </div>
              </div>

              <!-- Confirm Password -->
              <div class="form-group row">
                <label for="password_confirmation" class="col-md-4 col-form-label text-md-right required-label">
                  {{ __('تأكيد كلمة المرور') }}
                </label>
                <div class="col-md-6">
                  <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                  <div class="validation-message" id="confirm-password-error"></div>
                </div>
              </div>
            </div>

            <div class="navigation-buttons" id="step3-buttons">
              <!-- Buttons will be dynamically updated based on plan type -->
              <button type="button" class="btn btn-outline-primary prev-step" data-prev="2">
                الخطوة السابقة <i class="fas fa-arrow-right mr-2"></i>
              </button>
            </div>
          </div>
          <!-- Payment Method Section (Only for Paid Plans) -->
          <div class="form-step" id="step4" style="display: none;">
            <div class="step-instructions">
              <h6>اختر طريقة الدفع</h6>
              <p>اختر طريقة الدفع المناسبة لك</p>
            </div>

            <div class="payment-methods-container">
              <div class="payment-method-error" id="payment-method-error">
                يرجى اختيار طريقة دفع قبل المتابعة
              </div>

              @foreach($paymentMethods as $method)
              <div class="payment-method-card" data-method="{{ $method->name }}" data-id="{{ $method->id }}">
                <div class="method-icon">
                  <i class="{{ $method->icon }}"></i>
                </div>
                <div class="method-details">
                  <div class="method-name">{{ $method->display_name }}</div>
                  <div class="method-description">{{ $method->description }}</div>
                </div>
                <button type="button" class="btn btn-outline-primary method-select-btn">اختر</button>
              </div>
              @endforeach
            </div>

            <div class="navigation-buttons">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-building mr-2"></i> {{ __('تسجيل الشركة') }}
              </button>
              <button type="button" class="btn btn-outline-primary prev-step" data-prev="3">
                الخطوة السابقة <i class="fas fa-arrow-right mr-2"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
    
  <!-- Logo Preview Script -->
  <script>
    document.getElementById('company_logo').addEventListener('change', function(e) {
      const preview = document.getElementById('logo-preview');
      const file = e.target.files[0];
      
      if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.classList.remove('d-none');
        }
        
        reader.readAsDataURL(file);
        document.querySelector('.custom-file-label').textContent = file.name;
      }
    });

    // Enhanced Plan Selection Script with Validation
    document.addEventListener('DOMContentLoaded', function() {
      const planCards = document.querySelectorAll('.plan-card');
      const selectedPlanInput = document.getElementById('selected_plan');
      const selectedPlanIdInput = document.getElementById('selected_plan_id');
      const nextButton = document.querySelector('[data-next="3"]');
      const planSelectionError = document.getElementById('plan-selection-error');

      function validatePlanSelection() {
        if (!selectedPlanInput.value) {
          planSelectionError.classList.add('show');
          nextButton.disabled = true;
          return false;
        } else {
          planSelectionError.classList.remove('show');
          nextButton.disabled = false;
          return true;
        }
      }

      planCards.forEach(card => {
        card.addEventListener('click', function() {
          // Remove selected class from all cards
          planCards.forEach(c => {
            c.classList.remove('selected');
            // Reset all buttons to default state
            const btn = c.querySelector('.plan-select-btn');
            btn.classList.remove('selected');
            if (c.classList.contains('free-trial')) {
              btn.textContent = 'ابدأ التجربة المجانية';
            } else if (c.classList.contains('popular')) {
              btn.textContent = 'اختر الخطة';
            } else {
              btn.textContent = 'اختر الخطة';
            }
          });

          // Add selected class to clicked card
          this.classList.add('selected');
          
          // Update the hidden input with the selected plan
          selectedPlanInput.value = this.dataset.plan;
          selectedPlanIdInput.value = this.dataset.plan_id;
          
          // Update the select button of clicked card
          const btn = this.querySelector('.plan-select-btn');
          btn.classList.add('selected');
          if (this.classList.contains('free-trial')) {
            btn.textContent = 'تم اختيار التجربة المجانية';
          } else if (this.classList.contains('popular')) {
            btn.textContent = 'تم اختيار الخطة';
          } else {
            btn.textContent = 'تم اختيار الخطة';
          }

          // Validate plan selection
          validatePlanSelection();
        });
      });

      // Plan selection
      document.querySelectorAll('.plan-select-btn').forEach(button => {
        button.addEventListener('click', function() {
          // Remove active class from all plans
          document.querySelectorAll('.plan-card').forEach(card => {
            card.classList.remove('active');
          });

          // Add active class to selected plan
          const planCard = this.closest('.plan-card');
          planCard.classList.add('active');
          selectedPlan = planCard.dataset.plan;

          // Enable next step button
          const nextButton = planCard.closest('.form-step').querySelector('.next-step');
          nextButton.disabled = false;
        });
      });

      // Payment method selection
      document.querySelectorAll('.method-select-btn').forEach(button => {
        button.addEventListener('click', function() {
          // Remove active class from all methods
          document.querySelectorAll('.payment-method-card').forEach(card => {
            card.classList.remove('active');
          });

          // Add active class to selected method
          const methodCard = this.closest('.payment-method-card');
          methodCard.classList.add('active');
          selectedPaymentMethod = methodCard.dataset.method;
          selectedPaymentMethodId = methodCard.dataset.id;

          // Enable next step button
          const nextButton = methodCard.closest('.form-step').querySelector('.next-step');
          nextButton.disabled = false;
        });
      });

      // Validate plan selection before proceeding to next step
      nextButton.addEventListener('click', function(e) {
        if (!validatePlanSelection()) {
          e.preventDefault();
          return false;
        }
      });

      // Form validation before submission
      const form = document.querySelector('form');
      form.addEventListener('submit', function(e) {
        if (!validatePlanSelection()) {
          e.preventDefault();
          return false;
        }
      });

      // Enhanced Step Navigation Script with RTL support
      const steps = document.querySelectorAll('.step');
      const formSteps = document.querySelectorAll('.form-step');
      const progressBar = document.querySelector('.progress-bar');
      const nextButtons = document.querySelectorAll('.next-step');
      const prevButtons = document.querySelectorAll('.prev-step');

      function updateProgress(currentStep) {
        const totalSteps = window.selectedPlan && document.querySelector(`.plan-card[data-plan="${window.selectedPlan}"]`)?.classList.contains('free-trial') === false ? 4 : 3;
        const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
        progressBar.style.width = `${progress}%`;
        progressBar.setAttribute('aria-valuenow', progress);
      }

      function updateSteps(currentStep) {
        steps.forEach((step, index) => {
          const stepNumber = parseInt(step.dataset.step);
          if (stepNumber < currentStep) {
            step.classList.add('completed');
            step.classList.remove('active');
          } else if (stepNumber === currentStep) {
            step.classList.add('active');
            step.classList.remove('completed');
          } else {
            step.classList.remove('active', 'completed');
          }
        });
      }

      function updateStep3Buttons() {
        const step3Buttons = document.getElementById('step3-buttons');
        const selectedPlanCard = window.selectedPlan ? document.querySelector(`.plan-card[data-plan="${window.selectedPlan}"]`) : null;
        const isPaidPlan = selectedPlanCard && !selectedPlanCard.classList.contains('free-trial');

        // Clear existing buttons except back button
        const backButton = step3Buttons.querySelector('.prev-step');
        step3Buttons.innerHTML = '';
        
        if (isPaidPlan) {
          // Add next button for paid plans
          const nextButton = document.createElement('button');
          nextButton.type = 'button';
          nextButton.className = 'btn btn-primary next-step';
          nextButton.dataset.next = '4';
          nextButton.disabled = false; // Make sure it's not disabled
          nextButton.innerHTML = '<i class="fas fa-arrow-left ml-2"></i> الخطوة التالية';
          // Add click handler directly
          nextButton.addEventListener('click', function() {
            showStep('step4');
          });
          step3Buttons.appendChild(nextButton);
        } else {
          // Add submit button for free plans
          const submitButton = document.createElement('button');
          submitButton.type = 'submit';
          submitButton.className = 'btn btn-primary';
          submitButton.innerHTML = '<i class="fas fa-building mr-2"></i> تسجيل الشركة';
          step3Buttons.appendChild(submitButton);
        }
        
        // Add back button
        step3Buttons.appendChild(backButton);
      }

      function showStep(stepId) {
        // Hide all steps and remove active class
        document.querySelectorAll('.form-step').forEach(step => {
          step.style.display = 'none';
          step.classList.remove('active');
        });

        // Show the requested step and add active class
        const stepToShow = document.getElementById(stepId);
        if (stepToShow) {
          stepToShow.style.display = 'block';
          stepToShow.classList.add('active');
        }

        // Update progress if it's a numbered step
        const stepNumber = stepId.replace('step', '');
        if (!isNaN(stepNumber)) {
          updateSteps(parseInt(stepNumber));
          updateProgress(parseInt(stepNumber));
        }

        // Update step 4 visibility based on plan selection
        const step4Indicator = document.querySelector('.step[data-step="4"]');
        if (step4Indicator) {
          const selectedPlanCard = window.selectedPlan ? document.querySelector(`.plan-card[data-plan="${window.selectedPlan}"]`) : null;
          if (window.selectedPlan && selectedPlanCard && !selectedPlanCard.classList.contains('free-trial')) {
            step4Indicator.style.display = 'flex';
          } else {
            step4Indicator.style.display = 'none';
          }
        }

        // Update step 3 buttons when showing step 3
        if (stepId === 'step3') {
          updateStep3Buttons();
        }
      }

      nextButtons.forEach(button => {
        button.addEventListener('click', function() {
          const nextStepNumber = this.dataset.next;
          const currentStep = this.closest('.form-step');
          
          // Validate current step
          let isValid = true;
          
          // Step 1 validation (Company Information)
          if (currentStep.id === 'step1') {
            const companyName = document.getElementById('company_name');
            const companyNameError = document.getElementById('company_name_error');
            if (companyName && !companyName.value.trim()) {
              isValid = false;
              window.showError(companyNameError, 'يرجى إدخال اسم الشركة');
            } else if (companyName) {
              window.clearError(companyNameError);
            }
          }
          
          // Step 2 validation (Subscription Plan)
          if (currentStep.id === 'step2') {
            if (!selectedPlan) {
              isValid = false;
              document.getElementById('plan-selection-error').style.display = 'block';
              return;
            }
          }

          // Step 3 validation (Admin Account)
          if (currentStep.id === 'step3') {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');
            
            if (!email.value || !password.value || !confirmPassword.value) {
              isValid = false;
              if (!email.value) showError(document.getElementById('email_error'), 'يرجى إدخال البريد الإلكتروني');
              if (!password.value) showError(document.getElementById('password_error'), 'يرجى إدخال كلمة المرور');
              if (!confirmPassword.value) showError(document.getElementById('password_confirmation_error'), 'يرجى تأكيد كلمة المرور');
            }
          }

          // Step 4 validation (Payment Method)
          if (currentStep.id === 'step4') {
            if (!selectedPaymentMethod) {
              isValid = false;
              document.getElementById('payment-method-error').style.display = 'block';
              return;
            }
          }
          
          if (!isValid) return;
          
          // Handle step navigation
          showStep(`step${nextStepNumber}`);
          
          // If moving to step 3, update its buttons based on plan type
          if (nextStepNumber === '3') {
            updateStep3Buttons();
          }
          
          // If this is step 4, ensure payment method buttons are enabled
          if (nextStepNumber === '4') {
            const methodButtons = document.querySelectorAll('.method-select-btn');
            methodButtons.forEach(btn => btn.disabled = false);
          }
        });
      });

      prevButtons.forEach(button => {
        button.addEventListener('click', function() {
          const currentStep = this.closest('.form-step');
          const prevStepNumber = this.dataset.prev;
          
          // If we're in payment step (step4), go back to plan selection
          if (currentStep.id === 'step4') {
            showStep('step3');
          } else {
            showStep(`step${prevStepNumber}`);
          }
        });
      });

      // Initialize first step
      showStep('step1');
    });

    // Form validation with inline messages
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize variables
      window.selectedPlan = null;
      window.selectedPaymentMethod = null;
      const paymentStep = document.getElementById('step4');

      // Error handling functions
      window.showError = function(element, message) {
        if (!element) return;
        element.textContent = message;
        element.style.display = 'block';
        const input = element.previousElementSibling;
        if (input) {
          input.classList.add('is-invalid');
          input.classList.remove('is-valid');
        }
      }

      window.clearError = function(element) {
        if (!element) return;
        element.textContent = '';
        element.style.display = 'none';
        const input = element.previousElementSibling;
        if (input) {
          input.classList.remove('is-invalid');
          input.classList.add('is-valid');
        }
      }

      // Show first step and hide others on load
      document.querySelectorAll('.form-step').forEach((step, index) => {
        step.style.display = index === 0 ? 'block' : 'none';
      });
      const form = document.querySelector('form');
      const emailInput = document.getElementById('email');
      const passwordInput = document.getElementById('password');
      const confirmPasswordInput = document.getElementById('password_confirmation');
      const emailError = document.getElementById('email-error');
      const passwordError = document.getElementById('password-error');
      const confirmPasswordError = document.getElementById('confirm-password-error');

      function showError(element, message) {
        element.textContent = message;
        element.classList.add('show');
        element.previousElementSibling.classList.add('is-invalid');
        element.previousElementSibling.classList.remove('is-valid');
      }

      function clearError(element) {
        element.textContent = '';
        element.classList.remove('show');
        element.previousElementSibling.classList.remove('is-invalid');
        element.previousElementSibling.classList.add('is-valid');
      }

      // Email validation
      emailInput.addEventListener('input', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(this.value)) {
          showError(emailError, 'يرجى إدخال بريد إلكتروني صحيح');
        } else {
          clearError(emailError);
        }
      });

      // Password validation
      passwordInput.addEventListener('input', function() {
        if (this.value.length < 8) {
          showError(passwordError, 'يجب أن تكون كلمة المرور 8 أحرف على الأقل');
        } else {
          clearError(passwordError);
        }

        // Update confirm password validation
        if (confirmPasswordInput.value && this.value !== confirmPasswordInput.value) {
          showError(confirmPasswordError, 'كلمات المرور غير متطابقة');
        } else {
          clearError(confirmPasswordError);
        }
      });

      // Password confirmation validation
      confirmPasswordInput.addEventListener('input', function() {
        if (this.value !== passwordInput.value) {
          showError(confirmPasswordError, 'كلمات المرور غير متطابقة');
        } else {
          clearError(confirmPasswordError);
        }
      });

      // Validate payment method selection
      function validatePaymentMethod() {
        if (!selectedPlan) return true; // Skip validation for free plan
        const planCard = document.querySelector(`.plan-card[data-plan="${selectedPlan}"]`);
        if (!planCard.classList.contains('free-trial') && !selectedPaymentMethod) {
          const error = document.getElementById('payment-method-error');
          error.style.display = 'block';
          return false;
        }
        return true;
      }

      // Form submission validation
      form.addEventListener('submit', function(e) {
        let isValid = true;

        // Email validation
        if (!emailInput.value) {
          showError(emailError, 'يرجى إدخال البريد الإلكتروني');
          isValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
          showError(emailError, 'يرجى إدخال بريد إلكتروني صحيح');
          isValid = false;
        }

        // Password validation
        if (!passwordInput.value) {
          showError(passwordError, 'يرجى إدخال كلمة المرور');
          isValid = false;
        } else if (passwordInput.value.length < 8) {
          showError(passwordError, 'يجب أن تكون كلمة المرور 8 أحرف على الأقل');
          isValid = false;
        }

        // Password confirmation validation
        if (!confirmPasswordInput.value) {
          showError(confirmPasswordError, 'يرجى تأكيد كلمة المرور');
          isValid = false;
        } else if (passwordInput.value !== confirmPasswordInput.value) {
          showError(confirmPasswordError, 'كلمات المرور غير متطابقة');
          isValid = false;
        }

        // Payment method validation for paid plans
        if (!validatePaymentMethod()) {
          isValid = false;
        }

        if (!isValid) {
          e.preventDefault();
          return false;
        }

        // Add selected plan and payment method to form data
        const planInput = document.createElement('input');
        planInput.type = 'hidden';
        planInput.name = 'selected_plan';
        planInput.value = selectedPlan;
        form.appendChild(planInput);

        if (selectedPaymentMethod) {
          const methodInput = document.createElement('input');
          methodInput.type = 'hidden';
          methodInput.name = 'selected_payment_method';
          methodInput.value = selectedPaymentMethod;
          form.appendChild(methodInput);

          const methodIdInput = document.createElement('input');
          methodIdInput.type = 'hidden';
          methodIdInput.name = 'selected_payment_method_id';
          methodIdInput.value = selectedPaymentMethodId;
          form.appendChild(methodIdInput);
        }

        // Show loading overlay
        const loadingOverlay = document.getElementById('loadingOverlay');
        const progressBar = document.getElementById('progressBar');
        const loadingText = document.getElementById('loadingText');
        loadingOverlay.style.display = 'flex';

        // Define progress messages in Arabic with longer timeframes
        const messages = [
          { text: 'جاري التحقق من البيانات...', progress: 10 },
          { text: 'جاري إنشاء قاعدة البيانات...', progress: 30 },
          { text: 'جاري نسخ البيانات الأساسية...', progress: 50 },
          { text: 'جاري إعداد العلاقات والفهارس...', progress: 70 },
          { text: 'جاري إعداد الاشتراك...', progress: 85 },
          { text: 'جاري تجهيز لوحة التحكم...', progress: 95 },
          { text: 'يرجى الانتظار...', progress: 98 }
        ];

        let currentMessageIndex = 0;
        const updateProgress = () => {
          if (currentMessageIndex < messages.length) {
            const message = messages[currentMessageIndex];
            progressBar.style.width = `${message.progress}%`;
            loadingText.textContent = message.text;
            
            // Update subtext with estimated time
            const remainingSteps = messages.length - currentMessageIndex;
            if (remainingSteps > 1) {
              document.getElementById('loadingSubtext').textContent = 
                `قد تستغرق هذه العملية حوالي ${Math.ceil(remainingSteps * 1.5)} دقائق...`;
            }

            currentMessageIndex++;
            
            // Longer delays between updates to match actual database creation time
            setTimeout(updateProgress, 8000 + Math.random() * 4000);
          }
        };

        // Start progress animation
        updateProgress();

        // Submit the form immediately
        form.submit();
      });

      // Show validation messages on blur
      emailInput.addEventListener('blur', function() {
        if (!this.value) {
          showError(emailError, 'يرجى إدخال البريد الإلكتروني');
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value)) {
          showError(emailError, 'يرجى إدخال بريد إلكتروني صحيح');
        }
      });

      passwordInput.addEventListener('blur', function() {
        if (!this.value) {
          showError(passwordError, 'يرجى إدخال كلمة المرور');
        } else if (this.value.length < 8) {
          showError(passwordError, 'يجب أن تكون كلمة المرور 8 أحرف على الأقل');
        }
      });

      confirmPasswordInput.addEventListener('blur', function() {
        if (!this.value) {
          showError(confirmPasswordError, 'يرجى تأكيد كلمة المرور');
        } else if (this.value !== passwordInput.value) {
          showError(confirmPasswordError, 'كلمات المرور غير متطابقة');
        }
      });
    });
  </script>
</x-guestRegester-layout>