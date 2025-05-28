@extends('layouts.master')
@section('title')
    الصفحة الرئيسية
@stop

@section('css')

    <style>
        .card1 {}

        .card2 {}

        .card3 {}
    </style>
@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <div class="align-items-center justify-content-around mb-4 row">
                    <div class="col-lg-8 col-md-12">
                        <div class="row">
                            <div class="col m-1 p-0">
                                <div class="card card-animate rounded-4 border m-0">
                                    <a class="card-body" href="/storeManage">
                                        <div class="d-flex align-items-center d-flex justify-content-between">
                                            <p class="fw-bold mb-0  ">فواتير الشراء </p>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-warning fs-3 rounded-circle border">
                                                    <i class="bx bx-package"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <hr class="m-1 text-warning">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="fw-bold mb-0  ">
                                                    اليوم</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h5 class="fs-20 mb-0">
                                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                                    <span class="counter-value text-monospace"
                                                        data-target="{{ $buy_Invoice_today_count }}">0</span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="align-items-center align-items-end d-flex justify-content-between mt-2">
                                            <div>
                                                <h4 class="ff-secondary fs-18 fw-semibold text-monospace"><span
                                                        class="counter-value text-muted"
                                                        data-target="{{ $buy_Invoice_today }}"></span> ج.م
                                                </h4>

                                            </div>
                                        </div>
                                        <hr class="col-6 m-1 text-warning">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="fw-bold mb-0">
                                                    الإجمالي</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h5 class="fs-20 mb-0">
                                                    <i class="ri-bill-line fs-13 align-middle"></i>
                                                    <span class="counter-value text-monospace"
                                                        data-target="{{ $Buy_Transaction_Count }}"></span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="align-items-center align-items-end d-flex justify-content-between mt-2">
                                            <div>
                                                <h4 class="ff-secondary fs-18 fw-semibold text-monospace"><span
                                                        class="counter-value text-muted"
                                                        data-target="{{ $sum_Buy_Total }}"></span>
                                                    ج.م
                                                </h4>

                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            {{-- <div class="col-xl-3 col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="fw-bold mb-0  ">
                                                فواتير الشراء</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <h5 class="text-success fs-20 mb-0">
                                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                                <span class="counter-value" data-target="{{ $Buy_Transaction_Count }}"><span>
                                            </h5>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex align-items-end justify-content-between mt-2">
                                        <div>
                                            <h4 class="fs-18 fw-semibold ff-secondary mb-4 text-muted"><span
                                                    class="counter-value " data-target="{{ $sum_Buy_Total }}"></span>ج.م
                                            </h4>
                                            <a href="/storeManage" class="text-decoration-underline text-muted">إظهار
                                                التفاصيل</a>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span
                                                class="avatar-title bg-soft-primary bg-soft-primary bg-info-subtle rounded fs-3">
                                                <i class="bx bx-shopping-bag text-info"></i>
                                            </span>
                                        </div>

                                    </div>
                                </div>



                            </div>
                        </div> --}}
                            <div class="col m-1 p-0">
                                <div class="card card-animate rounded-4 border m-0">
                                    <a class="card-body" href="/sellInvoices">
                                        <div class="d-flex align-items-center d-flex justify-content-between">
                                            <p class="fw-bold mb-0">
                                                    فواتير البيع
                                            </p>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span
                                                    class="avatar-title bg-info-subtle bg-success fs-3 rounded-circle border">
                                                    <i class="bx bx-barcode-reader"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <hr class="text-bg-success m-1">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="fw-bold mb-0  ">
                                                    اليوم</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h5 class="fs-20 mb-0">
                                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                                    <span class="counter-value text-monospace" data-target="{{ $sell_Invoice_today_count }}">0</span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="align-items-center align-items-end d-flex justify-content-between mt-2">
                                            <div>
                                                <h4 class="ff-secondary fs-18 fw-semibold text-monospace"><span
                                                        class="counter-value text-muted"
                                                        data-target="{{ $sell_Invoice_today }}"></span> ج.م
                                                </h4>
                                            </div>
                                        </div>
                                        <hr class="col-6 m-1 text-warning">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="fw-bold mb-0  ">
                                                    الإجمالي</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h5 class="fs-20 mb-0">
                                                    <i class="align-middle fs-13 ri-bill-line"></i>
                                                    <span class="counter-value text-monospace"
                                                        data-target="{{ $sell_Transaction_Count }}"></span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="align-items-center d-flex justify-content-between mt-2">
                                            <div>
                                                <h4 class="ff-secondary fs-18 fw-semibold text-monospace"><span
                                                        class="counter-value text-monospace"
                                                        data-target="{{ $sell_Transaction_Total }}"></span>
                                                    ج.م
                                                </h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            {{-- <div class="col-xl-3 col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="fw-bold mb-0  ">
                                                فواتير البيع</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <h5 class="text-success fs-20 mb-0">
                                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                                <span class="counter-value" data-target="{{ $sell_Transaction_Count }}"><span>
                                            </h5>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex align-items-end justify-content-between mt-2">
                                        <div>
                                            <h4 class="fs-18 fw-semibold ff-secondary mb-4 text-muted"><span
                                                    class="counter-value "
                                                    data-target="{{ $sell_Transaction_Total }}"></span>ج.م
                                            </h4>
                                            <a href="/sellInvoices" class="text-decoration-underline text-muted">إظهار
                                                التفاصيل</a>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span
                                                class="avatar-title bg-soft-primary bg-soft-primary bg-info-subtle rounded fs-3">
                                                <i class="bx bx-shopping-bag text-info"></i>
                                            </span>
                                        </div>

                                    </div>
                                </div>



                            </div>
                        </div> --}}
                            <div class="col m-1 p-0">
                                <div class="card card-animate rounded-4 border m-0">
                                    <a class="card-body" href="/asar">
                                        <div class="d-flex align-items-center d-flex justify-content-between">
                                            <p class="fw-bold mb-0  ">عروض الأسعار</p>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-danger bg-info-subtle fs-3 rounded rounded-circle border">
                                                    <i class="bx bx-list-check"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <hr class="text-bg-danger m-1">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="fw-bold mb-0  ">
                                                 المنفذة</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h5 class="fs-20 mb-0">
                                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                                    <span class="counter-value text-monospace"
                                                        data-target="{{ $presalorder_count_done }}"><span>
                                                </h5>
                                            </div>
                                        </div>
                                        <hr class="text-bg-danger m-1">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="fw-bold mb-0  ">
                                                 المتبقي</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h5 class="fs-20 mb-0">
                                                    <i class="ri-arrow-left-down-line fs-13 align-middle"></i>
                                                    <span class="counter-value text-monospace"
                                                        data-target="{{ $presalorder_count - $presalorder_count_done }}"><span>
                                                </h5>
                                            </div>
                                        </div>
                                        <hr class="text-bg-danger col-8 m-1">
                                        <div class="d-flex align-items-center mt-2">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="fw-bold mb-0  ">
                                                  الإجمالي</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h5 class="fs-20 mb-0">
                                                    <i class="ri-bill-line fs-13 align-middle"></i>
                                                    <span class="counter-value text-monospace"
                                                        data-target="{{ $presalorder_count }}"></span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="align-items-center align-items-end d-flex justify-content-between mt-2">
                                            <div>
                                                <h4 class="ff-secondary fs-18 fw-semibold text-monospace"><span
                                                        class="counter-value text-muted"
                                                        data-target="{{ $presalorder_Total }}"></span> ج.م
                                                </h4>
                                            </div>
                                        </div>
                                    </a>



                                </div>
                            </div>
                            {{-- <div class="col-xl-3 col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="fw-bold mb-0  ">
                                                عروض الأسعــار </p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <h5 class="text-success fs-20 mb-0">
                                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                                <span class="counter-value" data-target="{{ $presalorder_count }}"><span>
                                            </h5>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex align-items-end justify-content-between mt-2">
                                        <div>
                                            <h4 class="fs-18 fw-semibold ff-secondary mb-4 text-muted"><span
                                                    class="counter-value " data-target="{{ $presalorder_Total }}"></span>ج.م
                                            </h4>
                                            <a href="/asar" class="text-decoration-underline text-muted">إظهار التفاصيل</a>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span
                                                class="avatar-title bg-soft-primary bg-soft-primary bg-info-subtle rounded fs-3">
                                                <i class="bx bx-shopping-bag text-info"></i>
                                            </span>
                                        </div>

                                    </div>
                                </div>



                            </div>
                        </div> --}}
                            {{-- <div class="col-xl-3 col-md-6">
                            <div class="card" style="height: 80% !important;">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span
                                                class="avatar-title bg-soft-primary bg-light text-primary rounded-circle fs-3">
                                                <i class="ri-money-dollar-circle-fill align-middle"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-uppercase fw-bold fs-13 text-muted mb-1"> إجمالى النقود بالبنك
                                                بالجنية المصرى </p>
                                            <h4 class="mb-0"><span class="counter-value" data-target="{{ $current_balance }}">
                                                </span></h4>
                                        </div>
                                        <div class="flex-shrink-0 align-self-end">
                                            <a href="/banksafeMoney" class="badge badge-soft-success"><i
                                                    class="ri-arrow-up-s-fill align-middle me-1"></i>عرض<span> </span></a>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div>
                        </div> --}}
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12">
                        <div class="border border-bottom-0  rounded-4 shadow-sm text-center">
                            <div class="align-items-center bg-warning d-flex rounded-4 rounded-bottom">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="fw-bold mb-0  ">
                                        أخر فاتورة شراء</p>
                                </div>
                            </div>
                            <div class="flex-shrink-0 pt-1 text-bg-light">
                                @if($buy_Invoice_last)
                                    <h6 class="text-decoration-underline mb-0"> <a
                                            href="/Supplierinvoice/{{ $buy_Invoice_last['supplier']->id }}">
                                            {{ $buy_Invoice_last['supplier']->name }}</a>
                                    </h6>
                                @else
                                    <h6 class="text-decoration-underline mb-0"> <a
                                        href="#">
                                      لا يوجد فواتير</a>
                                    </h6>
                                    @endif
                            </div>
                            <div class="align-items-center d-flex justify-content-around text-bg-light">
                                @if($buy_Invoice_last)
                                    <h4 class="fs-18 m-0">
                                        <span class="counter-value" data-target="{{ $buy_Invoice_last->total_price }}">
                                        </span>
                                        {{ $buy_Invoice_last->currency_type->name }}
                                    </h4>

                                    @else
                                        <h4 class="fs-18 m-0">
                                            <span class="counter-value" data-target="0">
                                            </span>
                                           لايوجد
                                        </h4>
                                    @endif

                                    @if($buy_Invoice_last)
                                        <a href="/printBuyInvoice/{{ $buy_Invoice_last->id }}">
                                            <div class="avatar-xs flex-shrink-0">
                                                <span class="avatar-title bg-light fs-3 rounded">
                                                    <i class="bx bx-printer text-bg-light"></i>
                                                </span>
                                            </div>
                                        </a>

                                        @else
                                        <a href="#">
                                            <div class="avatar-xs flex-shrink-0">
                                                <span class="avatar-title bg-light fs-3 rounded">
                                                    <i class="bx bx-printer text-bg-light"></i>
                                                </span>
                                            </div>
                                        </a>
                                    @endif
                            </div>
                        </div>

                        <div class="border border-bottom-0 rounded-4 shadow-sm text-center">
                            <div class="align-items-center bg-success d-flex rounded-4 rounded-bottom text-white">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="fw-bold mb-0  ">
                                        أخر فاتورة بيع</p>
                                </div>
                            </div>
                            <div class="flex-shrink-0 pt-1 text-bg-light">
                                @if($sell_Invoice_last)
                                    <h6 class="text-decoration-underline mb-0"> 
                                        <a href="/getclientinvoice/{{ $sell_Invoice_last['client']->id }}">{{ $sell_Invoice_last['client']->name }}
                                        </a>
                                    </h6>
                                    @else
                                    <h6 class="text-decoration-underline mb-0"> 
                                        <a href="#">لا يوجد
                                        </a>
                                    </h6>
                                @endif
                            </div>
                            <div class="align-items-center d-flex justify-content-around text-bg-light">
                                @if($sell_Invoice_last)
                                    <h4 class="fs-18 m-0">
                                        <span class="counter-value" data-target="{{ $sell_Invoice_last->actual_price - $sell_Invoice_last->discount }}">
                                        </span>
                                        EGP
                                    </h4>
                                @else
                                <h4 class="fs-18 m-0">
                                    <span class="counter-value" data-target="0">
                                    </span>
                                    EGP
                                </h4>
                                @endif

                                @if($sell_Invoice_last)
                                    <a href="/printInvoice/{{ $sell_Invoice_last->id }}">
                                        <div class="avatar-xs flex-shrink-0">
                                            <span class="avatar-title bg-light fs-3 rounded">
                                                <i class="bx bx-printer text-bg-light"></i>
                                            </span>
                                        </div>
                                    </a>
                                    @else
                                    <a href="#">
                                        <div class="avatar-xs flex-shrink-0">
                                            <span class="avatar-title bg-light fs-3 rounded">
                                                <i class="bx bx-printer text-bg-light"></i>
                                            </span>
                                        </div>
                                    </a>
                                    @endif
                            </div>
                        </div>

                        <div class="border border-bottom-0 rounded-4 shadow-sm text-center">
                            <div class="align-items-center bg-danger d-flex rounded-4 rounded-bottom text-white">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="fw-bold mb-0  ">
                                        أخر عرض سعر</p>
                                </div>
                            </div>
                            <div class="flex-shrink-0 pt-1 text-bg-light">

                                @if($presalorder_last)
                                <h6 class="fs-13 mb-0 text-decoration-underline"> 
                                    <a href="/getclientinvoice/{{ $presalorder_last['client']->id }}">
                                        {{ $presalorder_last['client']->name }}
                                    </a>
                                </h6>
                                @else
                                <h6 class="fs-13 mb-0 text-decoration-underline"> 
                                    <a href="#">
                                       لا يوجد
                                    </a>
                                </h6>
                                @endif
                            </div>
                            <div class="align-items-center d-flex justify-content-around text-bg-light">
                                    @if($presalorder_last)
                                        <h4 class="fs-18 m-0">
                                            <span class="counter-value" data-target="{{ $presalorder_last->total }}"></span>
                                            EGP
                                        </h4>
                                    @else
                                        <h4 class="fs-18 m-0">
                                            <span class="counter-value" data-target="0"></span>
                                            EGP
                                        </h4>
                                    @endif
                                    @if($presalorder_last)
                                        <a href="/printpreSale/ar/{{ $presalorder_last->id }}/0">
                                            <div class="avatar-xs flex-shrink-0">
                                                <span class="avatar-title bg-light fs-3 rounded">
                                                    <i class="bx bx-printer text-bg-light"></i>
                                                </span>
                                            </div>
                                        </a>
                                        @else
                                        <a href="#">
                                            <div class="avatar-xs flex-shrink-0">
                                                <span class="avatar-title bg-light fs-3 rounded">
                                                    <i class="bx bx-printer text-bg-light"></i>
                                                </span>
                                            </div>
                                        </a>
                                    @endif
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row d-none">
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="fw-bold mb-0  ">
                                            فواتير الشراء اليوم</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <h5 class="text-success fs-20 mb-0">
                                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                            <span class="counter-value"
                                                data-target="{{ $buy_Invoice_today_count }}"><span>
                                        </h5>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex align-items-end justify-content-between mt-2">
                                    <div>
                                        <h4 class="fs-18 fw-semibold ff-secondary mb-4 text-muted"><span
                                                class="counter-value " data-target="{{ $buy_Invoice_today }}"></span>ج.م
                                        </h4>
                                        <a href="/storeManage" class="text-decoration-underline text-muted">إظهار
                                            التفاصيل</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span
                                            class="avatar-title bg-soft-primary bg-soft-primary bg-info-subtle rounded fs-3">
                                            <i class="bx bx-shopping-bag text-info"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>



                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="fw-bold mb-0  ">
                                            فواتير البيع اليوم</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <h5 class="text-success fs-20 mb-0">
                                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                            <span class="counter-value"
                                                data-target="{{ $sell_Invoice_today_count }}"><span>
                                        </h5>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex align-items-end justify-content-between mt-2">
                                    <div>
                                        <h4 class="fs-18 fw-semibold ff-secondary mb-4 text-muted"><span
                                                class="counter-value " data-target="{{ $sell_Invoice_today }}"></span>ج.م
                                        </h4>
                                        <a href="/sellInvoices" class="text-decoration-underline text-muted">إظهار
                                            التفاصيل</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span
                                            class="avatar-title bg-soft-primary bg-soft-primary bg-info-subtle rounded fs-3">
                                            <i class="bx bx-shopping-bag text-info"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>



                        </div>
                    </div>
                    {{-- <div class="col-lg-4">
                        <div class="card" style="height: 80% !important;">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-primary bg-light text-primary rounded-circle fs-3">
                                            <i class="ri-money-dollar-circle-fill align-middle"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="row">

                                            <div class="col-lg-6">
                                                <p class="text-uppercase fw-bold fs-13 text-muted mb-1"> عروض أسعار</p>
                                                <h4 class="mb-0"><span class="counter-value"
                                                        data-target="{{ $presalorder_count - $presalorder_count_done }}">'{{ $presalorder_count - $presalorder_count_done }}'</span>
                                                    </h4>


                                            </div>
                                            <div class="col-lg-3">
                                                <p class="text-uppercase fw-bold fs-13 text-muted mb-1"> اجمالي
                                                </p>
                                                <h4 class="mb-0"> <span class="counter-value"
                                                        data-target="{{ $presalorder_count }}"><span> </h4>
                                            </div>
                                            <div class="col-lg-3">
                                                <p class="text-uppercase fw-bold fs-13 text-muted mb-1">  تم
                                                </p>
                                                <h4 class="mb-0"> <span class="counter-value"
                                                        data-target="{{ $presalorder_count_done }}"><span> </h4>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="flex-shrink-0 align-self-end">
                                        <a href="/asar" class="badge badge-soft-success"><i
                                                class="ri-arrow-up-s-fill align-middle me-1"></i>عرض<span> </span></a>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div>
                    </div> --}}
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="fw-bold mb-0  ">
                                            عروض الأسعــار المنفذة</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <h5 class="text-success fs-20 mb-0">
                                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                            <span class="counter-value"
                                                data-target="{{ $presalorder_count_done }}"><span>
                                        </h5>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex align-items-end justify-content-between mt-2">
                                    <div>
                                        <h4 class="fs-18 fw-semibold ff-secondary mb-4 text-muted"><span
                                                class="counter-value "
                                                data-target="{{ $presalorder_count - $presalorder_count_done }}"></span>بافى
                                        </h4>
                                        <a href="/asar" class="text-decoration-underline text-muted">إظهار التفاصيل</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span
                                            class="avatar-title bg-soft-primary bg-soft-primary bg-info-subtle rounded fs-3">
                                            <i class="bx bx-shopping-bag text-info"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>



                        </div>
                    </div>






                </div>

                <div class="row d-none">
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="fw-bold mb-0  ">
                                            أخر فاتورة شراء</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @if($buy_Invoice_last)
                                            <h4 class="text-success fs-20 mb-0"> 
                                                <a
                                                    href="/Supplierinvoice/{{ $buy_Invoice_last['supplier']->id }}">
                                                    {{ ($buy_Invoice_last) ? $buy_Invoice_last['supplier']->name : 'لايوجد' }}</a>
                                            </h4>
                                            @else
                                                <h4 class="text-success fs-20 mb-0"> 
                                                    <a
                                                        href="#">
                                                        لايوجد</a>
                                                </h4>
                                            @endif
                                        {{-- <h5 class="text-success fs-20 mb-0">
                                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                                <span class="counter-value"
                                                data-target="{{ $Buy_Transaction_Count }}"><span>
                                            </h5> --}}
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex align-items-end justify-content-between mt-2">
                                    <div>
                                        <h4 class="fs-18 fw-semibold ff-secondary mb-4 text-muted"><span
                                                class="counter-value "
                                                data-target="{{($buy_Invoice_last) ?  $buy_Invoice_last->total_price : 0}}"></span>
                                            {{ ($buy_Invoice_last) ?  $buy_Invoice_last->currency_type->name: 'لا يوجد'}}
                                        </h4>
                                        @if($buy_Invoice_last)
                                        <a href="/printBuyInvoice/{{ $buy_Invoice_last->id }}"
                                            class="text-decoration-underline text-muted">إظهار التفاصيل</a>
                                        @else
                                        <a href="#"
                                            class="text-decoration-underline text-muted">إظهار التفاصيل</a>
                                        @endif
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span
                                            class="avatar-title bg-soft-primary bg-soft-primary bg-info-subtle rounded fs-3">
                                            <i class="bx bx-shopping-bag text-info"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>



                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="fw-bold mb-0  ">
                                            أخر فاتورة بيع </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @if($sell_Invoice_last)
                                            <h4 class="text-success fs-20 mb-0"> <a
                                                    href="/getclientinvoice/{{ $sell_Invoice_last['client']->id }}">
                                                    {{ $sell_Invoice_last['client']->name }}</a>
                                            </h4>
                                        @else
                                            <h4 class="text-success fs-20 mb-0"> <a
                                                href="#">
                                                    لا يوجد</a>
                                            </h4>
                                        @endif

                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex align-items-end justify-content-between mt-2">
                                    <div>
                                        <h4 class="fs-18 fw-semibold ff-secondary mb-4 text-muted"><span
                                                class="counter-value "
                                                data-target="{{($sell_Invoice_last) ? $sell_Invoice_last->actual_price : 0 }}"></span>ج.م
                                        </h4>
                                        @if($sell_Invoice_last)
                                        <a href="/printInvoice/{{ $sell_Invoice_last->id }}"
                                            class="text-decoration-underline text-muted">إظهار التفاصيل</a>
                                        @else
                                        <a href="#"
                                            class="text-decoration-underline text-muted">إظهار التفاصيل</a>
                                        @endif
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span
                                            class="avatar-title bg-soft-primary bg-soft-primary bg-info-subtle rounded fs-3">
                                            <i class="bx bx-shopping-bag text-info"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>



                        </div>
                    </div>


                    <div class="col-xl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="fw-bold mb-0  ">
                                            أخر عرض سعر </p>
                                    </div>
                                    <div class="flex-shrink-0">


                                        @if($sell_Invoice_last)
                                        <h4 class="text-success fs-20 mb-0">
                                            <a href="/getclientinvoice/{{ $presalorder_last['client']->id }}">
                                                {{ $presalorder_last['client']->name }}</a>
                                        </h4>
                                        @else
                                        <h4 class="text-success fs-20 mb-0">
                                            <a href="#">
                                                لا يوجد</a>
                                        </h4>
                                        @endif
                                      

                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex align-items-end justify-content-between mt-2">
                                    <div>
                                        @if($presalorder_last)
                                        <h4 class="fs-18 fw-semibold ff-secondary mb-4 text-muted"><span
                                                    class="counter-value "
                                                    data-target="{{ ($presalorder_last) ?$presalorder_last->total  : 0}}"></span>ج.م
                                            </h4>

                                            <a href="/printpreSale/ar/{{ $presalorder_last->id }}/0"
                                                class="text-decoration-underline text-muted">إظهار التفاصيل</a>
                                        @else
                                        <h4 class="fs-18 fw-semibold ff-secondary mb-4 text-muted"><span
                                            class="counter-value "
                                            data-target="0"></span>ج.م
                                        </h4>

                                            <a href="#"
                                            class="text-decoration-underline text-muted">إظهار التفاصيل</a>
                                        @endif
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span
                                            class="avatar-title bg-soft-primary bg-soft-primary bg-info-subtle rounded fs-3">
                                            <i class="bx bx-shopping-bag text-info"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>



                        </div>
                    </div>




                </div>


                <div class="card d-none">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="chartcls d-flex">
                                        <canvas id="myChart" class="mx-auto"></canvas>
                                    </div>
                                </div>
                                <div class="col-lg-6  ">
                                    <div class=" chartcls d-flex">
                                        <canvas id="myChart2" class="mx-auto"></canvas>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>


                <div class="row">
                    <p class="fs-17 text-bg-dark text-center">الإشعارات</p>
                    <div class="card p-0">
                        <div class="card-body table-responsive">
                            <table class="table table-striped table-bordered cell-border " style="font-size: smaller;width:100%" id="kt_ecommerce_sales_table_inbox">
                                <thead  style="background: #67b1736e;">
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">


                                        <th>#</th>
                                        <th class="text-center">رقم القطعة</th>
                                        <th class="text-center">أسم القطعة</th>
                                        <th></th>

                                        <th class="text-center">بلد المنشأ </th>
                                        <th></th>

                                        <th class="text-center"> الحالة</th>
                                        <th></th>

                                        <th class="text-center"> الكفاءة</th>
                                        <th></th>
                                        <th class="text-center"> النوع</th>

                                        <th class="text-center">الحركة</th>
                                        <th class="text-center"></th>
                                        <th class="text-center">الكمية</th>
                                        <th class="text-center"> مخزن</th>
                                        <th class="text-center"> </th>
                                        <th class="text-center">القسم </th>

                                        <th class="text-center">التاريخ </th>
                                        <th class="text-center">المررد</th>
                                        <th class="text-center">المرحلة</th>
                                        <th class="text-center">ملاحظات </th>

                                        <th class="text-center">Actions</th>


                                    </tr>
                                </thead>

                                <tbody class="fw-semibold text-gray-500">


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div id="myGrid"></div>
                </div>
            </div>
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->


    <!-- end main content-->
@endsection

@section('js')

    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}


    <script src="{{ URL::asset('js/store/listing_inbox.js') }}"></script>


    <!--end::Custom Javascript-->

    <script>
        var store_selling = {!! $store_selling !!};

        const labels = store_selling.map(function(value) {
            return value.name;
        });

        const datax = store_selling.map(function(value) {
            return value.total_inv;
        });
        const dataxx = store_selling.map(function(value) {
            return value.total_money;
        });

        const ctx = document.getElementById('myChart');
        const ctx2 = document.getElementById('myChart2');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'إجمالى فواتير البيع',
                    data: datax,
                    borderWidth: 1
                }]
            },
            options: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'uyuuyuyu'
                }
            }


        });
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: ' المبيعات ',
                    data: dataxx,
                    borderWidth: 1
                }]
            },


        });

        function hide_transaction(el) {
            var row_clicked = $(el).closest('tr');
            var row_object = table.row(row_clicked).data();
            console.log(row_object);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('hideStoreTrans') }}",
                data: {
                    'data': {
                        'Store_log_id': row_object.stores_log_id,
                        'store_table_name': row_object.store.table_name,
                        'store_action_id': row_object.store_action_id,
                        'all_p_id': row_object.All_part_id,
                        'amount': row_object.trans_amount,
                        'type_id': row_object.type_id,
                        'store_id': row_object.store_id
                    }

                },
                datatype: 'JSON',
                statusCode: {
                    404: function() {
                        alert("page not found");
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    // alert("some error");
                    console.log(errorThrown);
                },
                success: function(data) {
                    console.log(data);
                    if (data) {
                        $('#kt_ecommerce_sales_table_inbox').DataTable().ajax.reload();
                    }
                    // getAllNewParts();
                    // getAllNewParts_inbox();
                    // $('.nav-tabs a[href="#tab-3"]').tab('show');


                    // SuccessAlert("This Message Hide Successfully")

                }
            });
        }

        function refuse_transaction(el) {
            var row_clicked = $(el).closest('tr');
            var row_object = table.row(row_clicked).data();
            console.log(row_object);
            if (row_object.store_action_id == 2 || row_object.store_action_id == 6 && row_object.status == 2) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('refuseStoreTrans') }}",
                    data: {
                        'data': {
                            'Store_log_id': row_object.stores_log_id,
                            'store_table_name': row_object.store.table_name,
                            'store_action_id': row_object.store_action_id,
                            'all_p_id': row_object.All_part_id,
                            'amount': row_object.trans_amount,
                            'type_id': row_object.type_id,
                            'store_id': row_object.store_id
                        }



                    },
                    datatype: 'JSON',
                    statusCode: {
                        404: function() {
                            alert("page not found");
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        // alert("some error");
                        console.log(errorThrown);
                    },
                    success: function(data) {
                        console.log(data);
                        if (data) {
                            $('#kt_ecommerce_sales_table_inbox').DataTable().ajax.reload();
                        }
                        // getAllNewParts();
                        // getAllNewParts_inbox();
                        // $('.nav-tabs a[href="#tab-3"]').tab('show');
                        // SuccessAlert("This Message Denied you can Hide It")
                        // var resArr1 = $.parseJSON(data);
                        // console.log(resArr1);

                        // arr_partNo_html = draw(resArr1['Last_Amount'], 'Last_Amount');
                        // $(".Last_Amount").html(arr_partNo_html);


                    }
                });
            } else {
                alert("This Process Can`t Deleted")
            }
        }

        function Confirm_transaction(el) {

            var row_clicked = $(el).closest('tr');
            var row_object = table.row(row_clicked).data();
            console.log(row_object);
            if (row_object.store_action_id == 2 || row_object.store_action_id == 6 && row_object.status == 2) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('confirmStoreTrans') }}",
                    data: {
                        'data': {
                            'Store_log_id': row_object.stores_log_id,
                            'store_table_name': row_object.store.table_name,
                            'store_action_id': row_object.store_action_id,
                            'all_p_id': row_object.All_part_id,
                            'amount': row_object.trans_amount,
                            'type_id': row_object.type_id,
                            'store_id': row_object.store_id
                        }



                    },
                    datatype: 'JSON',
                    statusCode: {
                        404: function() {
                            alert("page not found");
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        // alert("some error");
                        console.log(errorThrown);
                    },
                    success: function(data) {
                        console.log(data);

                        if (data) {
                            $('#kt_ecommerce_sales_table_inbox').DataTable().ajax.reload();
                        }
                        // SuccessAlert("Transaction Accepted" )

                        // getAllNewParts();
                        // getAllNewParts_inbox();
                        // $('.nav-tabs a[href="#tab-3"]').tab('show');

                        // var resArr1 = $.parseJSON(data);
                        // console.log(resArr1);

                        // arr_partNo_html = draw(resArr1['Last_Amount'], 'Last_Amount');
                        // $(".Last_Amount").html(arr_partNo_html);


                    }
                });
            } else {
                alert("Need  Store To recive Amount");
            }

        }
    </script>

    <script>
        var table = "";
        $(function() {
            // alert("cccc");
            table = $('#kt_ecommerce_sales_table_inbox').DataTable({

                processing: true,
                serverSide: true,
                // dom: 'Bfrtip',

                type: 'get',
                ajax: "{{ route('inboxAdmin.list') }}",
                // async:false,
                columns: [

                    {
                        data: "stores_log_id",
                        "visible": false
                    },
                    {
                        data: 'part_id',
                        name: 'part_id',

                    },

                    {
                        data: 'part_name',
                        name: 'part_name'
                    },
                    {
                        data: 'source_id',
                        name: 'source_id',
                        "visible": false
                    },
                    {
                        data: 'source_name',
                        name: 'source_name'
                    },
                    {
                        data: 'quality_id',
                        name: 'quality_id',
                        "visible": false
                    },
                    {
                        data: 'quality_name',
                        name: 'quality_name'
                    },
                    {
                        data: 'status_id',
                        name: 'status_id',
                        "visible": false
                    },
                    {
                        data: 'staus_name',
                        name: 'staus_name'
                    },


                    {
                        data: 'type.id',
                        name: 'type_id',
                        "visible": false
                    },
                    {
                        data: 'type.name',
                        name: 'type_name'
                    },
                    {
                        data: 'store_action.name',
                        name: 'store_action_name'
                    },
                    {
                        data: 'store_action.id',
                        name: 'store_action_id',
                        "visible": false
                    },
                    {
                        data: 'trans_amount',
                        name: 'trans_amount',
                        render: function(data, type, row) {
        
                            return data/row.ratio +'/'+row.bigunit;
    
                        }
                    },

                    {
                        data: 'store.name',
                        name: 'to_store'
                    },
                    {
                        data: 'store.id',
                        name: 'to_store_id',
                        "visible": false
                    },
                    {
                        data: 'storelog_section',
                        render: function(data, type, row) {
                            var section = '';
                            if (row.storelog_section != null) {
                                if (row.storelog_section.length > 0) {
                                    row.storelog_section.forEach(element => {
                                        section += `${element.name} / ${element.amount }`
                                    });
                                    return section;
                                } else {
                                    return '--';
                                }
                            } else {
                                return '--';
                            }



                        }
                    },
                    {
                        data: 'date',
                        name: 'trans_date',
                        "visible": false,
                        render: function(data, type, row) {

                            return data.split('T')[0];

                        }
                    },
                    {
                        data: 'sup_name',
                        name: 'sup_name',
                        "visible": false
                    },
                    {
                        data: "status",
                        name: 'status',
                        defaultContent: "-",


                        render: function(data, type, row) {
                            if (type === 'display' || type === 'filter') {

                                var statusword = '';
                                var statusclass = '';
                                // alert(data);
                                switch (data) {

                                    case -1:
                                        statusword = 'مرفوضة';
                                        statusclass = 'light-dark bg-danger';
                                        break;
                                    case 0:
                                        statusword = 'منتظر إستلام';
                                        statusclass = 'light-dark bg-warning';
                                        break;
                                    case 1:
                                        statusword = 'تم الإستلام';
                                        statusclass = 'light-dark bg-info';
                                        break;
                                    case 2:
                                        statusword = 'منتظر تأكيد';
                                        statusclass = 'light-dark bg-success';
                                        break;

                                }

                                // if (isadmin == 'true') {
                                // return '<div class="m-4"><input type="checkbox" class="pendingChk" value="' + data['event_id'] + '"><br><div class = "lblstatus label label-' + statusclass + '">' + statusword + '</div></div>';
                                // } else {
                                return '<span name="statusword" class = "badge badge-pill  badge-' +
                                    statusclass + '">' + statusword + '</span>';
                                // }
                            }
                            return data;
                        }
                    },
                    {
                        data: 'notes',
                        name: 'notes'
                    },
                    {
                        data: null,
                        className: "text-center",
                        // defaultContent: `<div  class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                    //                     <span class="svg-icon svg-icon-5 m-0">
                    //                         <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    //                             <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                    //                         </svg>
                    //                     </span>
                    //                 </div>
                    //                 <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                    //                     <div class="menu-item px-3">
                    //                     </div>
                    //                 </div> `,
                        render: function(data, type, row) {
                            data = `<a href="javascript:void(0)" class=" d-none edit btn btn-primary btn-sm"  onclick="hide_transaction(this)">hide</a>
                <a href="javascript:void(0)" class="edit btn btn-danger btn-sm" onclick="refuse_transaction(this)"><i class="mdi mdi-close-box"></i></a>
                <a href="javascript:void(0)" class="edit btn btn-success btn-sm" onclick="Confirm_transaction(this)"><i class="mdi mdi-checkbox-marked"></i></a>
                `;
                            return data;
                        }


                    },



                ]

            });

        });
    </script>

    <script>
        const data_inbox = {!! $data_inbox !!};


        if (data_inbox) {
            $('.inbox_trans_counter').html(data_inbox.length);
            // $('.simplebar-content').empty();
            draw_notification(data_inbox, '.kt_topbar_notifications_33')
        }


        let gridApi;

        // Grid Options: Contains all of the grid configurations
        const gridOptions = {
            // Row Data: The data to be displayed.
            rowData: [{
                    make: 'Tesla',
                    model: 'Model Y',
                    price: 64950,
                    electric: true
                },
                {
                    make: 'Ford',
                    model: 'F-Series',
                    price: 33850,
                    electric: false
                },
                {
                    make: 'Toyota',
                    model: 'Corolla',
                    price: 29600,
                    electric: false
                },
            ],
            // Column Definitions: Defines & controls grid columns.
            columnDefs: [{
                    field: 'make',
                    filter: true
                },
                {
                    field: 'model',
                    filter: true
                },
                {
                    field: 'price',
                    filter: true
                },
                {
                    field: 'electric',
                    filter: true
                },
            ],
        };

        // Create Grid: Create new grid within the #myGrid div, using the Grid Options object
        // gridApi = agGrid.createGrid(document.querySelector('#myGrid'), gridOptions);
    </script>
@endsection
