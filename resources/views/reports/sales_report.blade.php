@extends('layouts.master')
@section('title')
    تقرير المبيعات
@stop
@section('css')
    <style>
        .card-container,
        .card-box,
        .card-head,
        .card-number-row,
        .card-details,
        .card-holder-col,
        .card-date-col {
            display: flex;
        }

        .card-container {
            align-items: center;
            justify-content: center;
        }

        .card-box {
            width: 300px;
            min-height: 160px;
            background: #fff;
            box-shadow: 0px 0px 15px -2px rgba(0, 0, 0, 0.1);
            border-radius: 18px;
            margin: 16px;
            padding: 1.5em;
            flex-direction: column;
            justify-content: space-around;
            gap: 1.2em;
        }

        .card-head {
            justify-content: space-between;
            align-items: center;
        }

        .card-chip svg {
            width: 32px;
            height: 32px;
        }

        .card-chip svg path {
            fill: #636363;
        }

        .card-logo svg {
            width: 48px;
            height: 48px;
        }

        .card-number-row {
            justify-content: center;
            word-spacing: 1em;
            font-size: 1.3em;
            font-weight: 600;
        }

        .card-box:hover .card-number-row {
            font-size: 1.32em;
        }

        .card-details {
            justify-content: space-between;
            text-transform: uppercase;
        }

        .card-holder-col {
            flex-direction: column;
            gap: 2px;
        }

        .card-holder-title,
        .card-date-title {
            color: #bdbdbd;
            font-size: 0.7em;
        }

        .card-holder-name {
            font-size: 1.1em;
            font-weight: 600;
        }

        .card-date-col {
            flex-direction: column;
            align-items: flex-end;
            gap: 2px;
        }
    </style>
@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Analytic</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Sales Report</li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h3>Filter</h3>
                    <hr>
                    <div class="row">

                        <div class="col-lg-1">
                            <select name="filter_date" id="filter_date">
                                <option value="" selected disabled>Select Period</option>
                                <option value="1">Year To Date</option>
                                <option value="2">Last 7 Days</option>
                                <option value="3">Last 30 Days</option>
                                <option value="4">Last 90 Days</option>
                                <option value="5">Last 180 Days</option>
                                <option value="5">Last 365 Days</option>
                                <option value="5">Last 3 Years</option>
                            </select>
                        </div>
                        <div class="col-lg-1">
                            <select name="store" id="store">
                                <option value="" selected disabled>Select Store</option>
                                @foreach ($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-lg-1">
                                <select name="product" id="product">
                                    <option value="" selected disabled>Select Product</option>
                                  @foreach ($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>

                                    @endforeach
                                </select>
                            </div> --}}
                        <div class="col-lg-1">
                            <select name="customer" id="customer">
                                <option value="" selected disabled>Select Customer</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-1">
                            <select name="source" id="source">
                                <option value="" selected disabled>Select Source</option>
                                @foreach ($sources as $source)
                                    <option value="{{ $source->id }}">{{ $source->name_arabic }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-1">
                            <select name="statuss" id="statuss">
                                <option value="" selected disabled>Select Status</option>
                                @foreach ($status_all as $status)
                                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-1">
                            <select name="quality" id="quality">
                                <option value="" selected disabled>Select Quality</option>
                                @foreach ($qualities as $quality)
                                    <option value="{{ $quality->id }}">{{ $quality->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-1">
                            <select name="brand" id="brand">
                                <option value="" selected disabled>Select Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-1">
                            <select name="model" id="model">
                                <option value="" selected disabled>Select Model</option>
                                @foreach ($models as $model)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-1">
                            <select name="sub_group" id="sub_group">
                                <option value="" selected disabled>Select Sub Group</option>
                                @foreach ($sub_groups as $sub_group)
                                    <option value="{{ $sub_group->id }}">{{ $sub_group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-1">
                            <select name="user" id="user">
                                <option value="" selected disabled>Select Sale man</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->username }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="card-container">
                                <div class="card-box">
                                    <div class="card-head">
                                        <div class="card-chip">
                                            عروض الأسعار
                                        </div>
                                        <div class="card-logo">

                                        </div>
                                    </div>
                                    <div class="card-number-row">
                                        <span id="sum_PresaleOrder">{{ $sum_PresaleOrder }} </span>
                                    </div>
                                    <div class="card-details">
                                        <div class="card-holder-col">

                                        </div>
                                        <div class="card-date-col">
                                            <span class="card-date-title"></span>
                                            <span class="card-date-sub"><a href="/asar">عرض</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-3">
                            <div class="card-container">
                                <div class="card-box">
                                    <div class="card-head">
                                        <div class="card-chip">
                                            فواتير البيع
                                        </div>
                                        <div class="card-logo">

                                        </div>
                                    </div>
                                    <div class="card-number-row">
                                        <span id="sum_Invoice_actual_price">
                                            {{ $sum_Invoice_actual_price }}
                                        </span>
                                    </div>
                                    <div class="card-details">
                                        <div class="card-holder-col">

                                        </div>
                                        <div class="card-date-col">
                                            <span class="card-date-title"></span>
                                            <span class="card-date-sub"><a href="">عرض</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-3">
                            <div class="card-container">
                                <div class="card-box">
                                    <div class="card-head">
                                        <div class="card-chip">
                                            الدخل
                                        </div>
                                        <div class="card-logo">

                                        </div>
                                    </div>
                                    <div class="card-number-row">
                                        <span id="sum_Invoice_paied"> {{ $sum_Invoice_paied }}</span>
                                    </div>
                                    <div class="card-details">
                                        <div class="card-holder-col">

                                        </div>
                                        <div class="card-date-col">
                                            <span class="card-date-title"></span>
                                            <span class="card-date-sub"><a href="">عرض</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-3">
                            <div class="card-container">
                                <div class="card-box">
                                    <div class="card-head">
                                        <div class="card-chip">
                                            متوسط المبيعات
                                        </div>
                                        <div class="card-logo">

                                        </div>
                                    </div>
                                    <div class="card-number-row">
                                        <span id="average_invoices">0 </span>
                                    </div>
                                    <div class="card-details">
                                        <div class="card-holder-col">

                                        </div>
                                        <div class="card-date-col">
                                            <span class="card-date-title"></span>
                                            <span class="card-date-sub"><a href="">عرض</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h3> متوسط المبيعات الشهرية</h3>
                    <hr>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <h3> أكثر المخازن مبيعا </h3>
                            <hr>
                            <table id="stores_orders" class="table table-border table-striped text-center table-sm">
                                <thead class="text-center text-bg-info">

                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">المخزن</th>
                                        <th class="text-center">إجمالي المبيعات</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($stores_orders as $stores_order)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $stores_order->name }}</td>
                                            <td>{{ $stores_order->invoices_sum_actual_price ? $stores_order->invoices_sum_actual_price : 0 }}
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <h3> افضل الأصناف طلبا </h3>
                            <hr>
                            <table id="invoice_order_items" class="table table-border table-striped text-center table-sm">
                                <thead class="text-center text-bg-info">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">النوع</th>
                                        <th class="text-center">الصنف</th>
                                        <th class="text-center">بلد المنشأ</th>
                                        <th class="text-center">الحالة</th>
                                        <th class="text-center">الجودة</th>
                                        <th class="text-center">العدد</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($invoice_order_items as $invoice_order_item)
                                        <tr>
                                            <td>{{ ++$i }}</td>

                                            @if ($invoice_order_item->part_type_id == 1)
                                                <td>قطع غيار</td>
                                                <td>{{ $invoice_order_item->part->name ?? 'N/A' }}</td>
                                            @elseif($invoice_order_item->part_type_id == 2)
                                                <td>كاوتش</td>
                                                <td>{{ $invoice_order_item->wheel->name ?? 'N/A' }}</td>
                                            @elseif($invoice_order_item->part_type_id == 3)
                                                <td>جرار</td>
                                                <td>{{ $invoice_order_item->tractor->name ?? 'N/A' }}</td>
                                            @elseif($invoice_order_item->part_type_id == 4)
                                                <td>كلارك</td>
                                                <td>{{ $invoice_order_item->clark->name ?? 'N/A' }}</td>
                                            @elseif($invoice_order_item->part_type_id == 5)
                                                <td>معدة</td>
                                                <td>{{ $invoice_order_item->equip->name ?? 'N/A' }}</td>
                                            @elseif($invoice_order_item->part_type_id == 6)
                                                <td>كيت</td>
                                                <td>{{ $invoice_order_item->kit->name ?? 'N/A' }}</td>
                                            @endif
                                            <td>{{ $invoice_order_item->source->name_arabic ?? 'N/A' }}</td>
                                            <td>{{ $invoice_order_item->status->name ?? 'N/A' }}</td>
                                            <td>{{ $invoice_order_item->part_quality->name ?? 'N/A' }}</td>
                                            <td>{{ $invoice_order_item->item_count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <h3> العملاء الأكثر شراء </h3>
                            <hr>
                            <table id="clientWithMostInvoices"
                                class="table table-border table-striped text-center table-sm">
                                <thead class="text-center text-bg-info">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">العميل</th>
                                        <th class="text-center">عدد مرات الشراء</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($clientWithMostInvoices as $clientWithMostInvoice)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $clientWithMostInvoice->client->name }}</td>
                                            <td>{{ $clientWithMostInvoice->total_invoices }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <h3> بلد المنشأ الأكثر طلبا</h3>
                            <hr>
                            <table id="invoices_order_sources"
                                class="table table-border table-striped text-center table-sm">
                                <thead class="text-center text-bg-info">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">النوع</th>
                                        <th class="text-center">الصنف</th>
                                        <th class="text-center">بلد المنشأ</th>
                                        <th class="text-center">العدد</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($invoices_order_sources as $invoices_order_source)
                                        <tr>
                                            <td>{{ ++$i }}</td>

                                            @if ($invoices_order_source->part_type_id == 1)
                                                <td>قطع غيار</td>
                                                <td>{{ $invoices_order_source->part->name ?? 'N/A' }}</td>
                                            @elseif($invoices_order_source->part_type_id == 2)
                                                <td>كاوتش</td>
                                                <td>{{ $invoices_order_source->wheel->name ?? 'N/A' }}</td>
                                            @elseif($invoices_order_source->part_type_id == 3)
                                                <td>جرار</td>
                                                <td>{{ $invoices_order_source->tractor->name ?? 'N/A' }}</td>
                                            @elseif($invoices_order_source->part_type_id == 4)
                                                <td>كلارك</td>
                                                <td>{{ $invoices_order_source->clark->name ?? 'N/A' }}</td>
                                            @elseif($invoices_order_source->part_type_id == 5)
                                                <td>معدة</td>
                                                <td>{{ $invoices_order_source->equip->name ?? 'N/A' }}</td>
                                            @elseif($invoices_order_source->part_type_id == 6)
                                                <td>كيت</td>
                                                <td>{{ $invoices_order_source->kit->name ?? 'N/A' }}</td>
                                            @endif
                                            <td>{{ $invoices_order_source->source->name_arabic ?? 'N/A' }}</td>
                                            <td>{{ $invoices_order_source->item_count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <h3> الحالة الأكثر طلبا </h3>
                            <hr>
                            <table id="invoices_order_statuses"
                                class="table table-border table-striped text-center table-sm">
                                <thead class="text-center text-bg-info">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">النوع</th>
                                        <th class="text-center">الصنف</th>
                                        <th class="text-center">الحالة </th>
                                        <th class="text-center">العدد</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($invoices_order_statuses as $invoices_order_status)
                                        <tr>
                                            <td>{{ ++$i }}</td>

                                            @if ($invoices_order_status->part_type_id == 1)
                                                <td>قطع غيار</td>
                                                <td>{{ $invoices_order_status->part->name ?? 'N/A' }}</td>
                                            @elseif($invoices_order_status->part_type_id == 2)
                                                <td>كاوتش</td>
                                                <td>{{ $invoices_order_status->wheel->name ?? 'N/A' }}</td>
                                            @elseif($invoices_order_status->part_type_id == 3)
                                                <td>جرار</td>
                                                <td>{{ $invoices_order_status->tractor->name ?? 'N/A' }}</td>
                                            @elseif($invoices_order_status->part_type_id == 4)
                                                <td>كلارك</td>
                                                <td>{{ $invoices_order_status->clark->name ?? 'N/A' }}</td>
                                            @elseif($invoices_order_status->part_type_id == 5)
                                                <td>معدة</td>
                                                <td>{{ $invoices_order_status->equip->name ?? 'N/A' }}</td>
                                            @elseif($invoices_order_status->part_type_id == 6)
                                                <td>كيت</td>
                                                <td>{{ $invoices_order_status->kit->name ?? 'N/A' }}</td>
                                            @endif
                                            <td>{{ $invoices_order_status->status->name ?? 'N/A' }}</td>
                                            <td>{{ $invoices_order_status->item_count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <h3> الجودة الأكثر طلبا </h3>
                            <hr>
                            <table id="invoices_order_qualities"
                                class="table table-border table-striped text-center table-sm">
                                <thead class="text-center text-bg-info">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">النوع</th>
                                        <th class="text-center">الصنف</th>
                                        <th class="text-center">الجودة </th>
                                        <th class="text-center">العدد</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($invoices_order_qualities as $invoices_order_quality)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            @if ($invoices_order_quality->part_type_id == 1)
                                                <td>قطع غيار</td>
                                                <td>{{ $invoices_order_quality->part->name ?? 'N/A' }}</td>
                                            @elseif($invoices_order_quality->part_type_id == 2)
                                                <td>كاوتش</td>
                                                <td>{{ $invoices_order_quality->wheel->name ?? 'N/A' }}</td>
                                            @elseif($invoices_order_quality->part_type_id == 3)
                                                <td>جرار</td>
                                                <td>{{ $invoices_order_quality->tractor->name ?? 'N/A' }}</td>
                                            @elseif($invoices_order_quality->part_type_id == 4)
                                                <td>كلارك</td>
                                                <td>{{ $invoices_order_quality->clark->name ?? 'N/A' }}</td>
                                            @elseif($invoices_order_quality->part_type_id == 5)
                                                <td>معدة</td>
                                                <td>{{ $invoices_order_quality->equip->name ?? 'N/A' }}</td>
                                            @elseif($invoices_order_quality->part_type_id == 6)
                                                <td>كيت</td>
                                                <td>{{ $invoices_order_quality->kit->name ?? 'N/A' }}</td>
                                            @endif
                                            <td>{{ $invoices_order_quality->part_quality->name ?? 'N/A' }}</td>
                                            <td>{{ $invoices_order_quality->item_count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <h3> الماركات الأكثر طلبا </h3>
                            <hr>
                            <table class="table table-border table-striped text-center table-sm">
                                <thead class="text-center text-bg-info">
                                    <tr>
                                        <th class="text-center">1</th>
                                        <th class="text-center">2</th>
                                        <th class="text-center">3</th>
                                        <th class="text-center">4</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr>
                                        <td>m</td>
                                        <td>2</td>
                                        <td>2</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>m</td>
                                        <td>2</td>
                                        <td>2</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>m</td>
                                        <td>2</td>
                                        <td>2</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>m</td>
                                        <td>2</td>
                                        <td>2</td>
                                        <td>2</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <h3> المودلات الأكثر طلبا </h3>
                            <hr>
                            <table class="table table-border table-striped text-center table-sm">
                                <thead class="text-center text-bg-info">
                                    <tr>
                                        <th class="text-center">1</th>
                                        <th class="text-center">2</th>
                                        <th class="text-center">3</th>
                                        <th class="text-center">4</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr>
                                        <td>m</td>
                                        <td>2</td>
                                        <td>2</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>m</td>
                                        <td>2</td>
                                        <td>2</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>m</td>
                                        <td>2</td>
                                        <td>2</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>m</td>
                                        <td>2</td>
                                        <td>2</td>
                                        <td>2</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <h3> التصنيفات الأكثر طلبا </h3>
                            <hr>
                            <table class="table table-border table-striped text-center table-sm">
                                <thead class="text-center text-bg-info">
                                    <tr>
                                        <th class="text-center">1</th>
                                        <th class="text-center">2</th>
                                        <th class="text-center">3</th>
                                        <th class="text-center">4</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr>
                                        <td>m</td>
                                        <td>2</td>
                                        <td>2</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>m</td>
                                        <td>2</td>
                                        <td>2</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>m</td>
                                        <td>2</td>
                                        <td>2</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>m</td>
                                        <td>2</td>
                                        <td>2</td>
                                        <td>2</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <h3> أفضل البائعين </h3>
                            <hr>
                            <table class="table table-border table-striped text-center table-sm">
                                <thead class="text-center text-bg-info">
                                    <tr>
                                        <th class="text-center">1</th>
                                        <th class="text-center">2</th>
                                        <th class="text-center">3</th>
                                        <th class="text-center">4</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr>
                                        <td>m</td>
                                        <td>2</td>
                                        <td>2</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>m</td>
                                        <td>2</td>
                                        <td>2</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>m</td>
                                        <td>2</td>
                                        <td>2</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>m</td>
                                        <td>2</td>
                                        <td>2</td>
                                        <td>2</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endsection

    @section('js')
        <script>
            $('#filter_date').on('change', function() {
                var filterDate = $('#filter_date').val();

                $.ajax({
                    url: '{{ route('sales_report_date') }}',
                    method: 'GET',
                    data: {
                        filter_date: filterDate
                    },
                    success: function(data) {
                        $('#sum_PresaleOrder').text(data.sum_PresaleOrder);
                        $('#sum_Invoice_actual_price').text(data.sum_Invoice_actual_price);
                        $('#sum_Invoice_paied').text(data.sum_Invoice_paied);
                        $('#average_invoices').text(0);
                        //////first table///
                        var storesOrdersTableBody = $('#stores_orders tbody');
                        storesOrdersTableBody.empty();

                        $.each(data.stores_orders, function(index, item) {
                            var row = '<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + item.name + '</td>' +
                                '<td>' + (item.invoices_sum_actual_price ? item
                                    .invoices_sum_actual_price : 0) + '</td>' +
                                '</tr>';

                            storesOrdersTableBody.append(row);
                        });
                        //////second table///
                        var invoiceOrderItemsTableBody = $('#invoice_order_items tbody');
                        invoiceOrderItemsTableBody.empty();

                        $.each(data.invoice_order_items, function(index, item) {
                            var type = '';
                            var name = '';

                            // Determine the type and name based on part_type_id
                            switch (item.part_type_id) {
                                case 1:
                                    type = 'قطع غيار';
                                    name = item.part.name ?? 'N/A';
                                    break;
                                case 2:
                                    type = 'كاوتش';
                                    name = item.wheel.name ?? 'N/A';
                                    break;
                                case 3:
                                    type = 'جرار';
                                    name = item.tractor.name ?? 'N/A';
                                    break;
                                case 4:
                                    type = 'كلارك';
                                    name = item.clark.name ?? 'N/A';
                                    break;
                                case 5:
                                    type = 'معدة';
                                    name = item.equip.name ?? 'N/A';
                                    break;
                                case 6:
                                    type = 'كيت';
                                    name = item.kit.name ?? 'N/A';
                                    break;
                                default:
                                    type = 'N/A';
                                    name = 'N/A';
                            }

                            var source = item.source.name_arabic ?? 'N/A';
                            var status = item.status.name ?? 'N/A';
                            var quality = item.part_quality.name ?? 'N/A';
                            var itemCount = item.item_count;

                            // Construct the table row
                            var row = '<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + type + '</td>' +
                                '<td>' + name + '</td>' +
                                '<td>' + source + '</td>' +
                                '<td>' + status + '</td>' +
                                '<td>' + quality + '</td>' +
                                '<td>' + itemCount + '</td>' +
                                '</tr>';

                            // Append the row to the table body
                            invoiceOrderItemsTableBody.append(row);
                        });

                        //////third table///
                        var tableBody = $('#clientWithMostInvoices tbody');
                        tableBody.empty();
                        $.each(data.clientWithMostInvoices, function(index, item) {
                            var row = '<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + item.client.name + '</td>' +
                                '<td>' + item.total_invoices + '</td>' +
                                '</tr>';

                            tableBody.append(row);
                        });
                        //////fourth table///
                        var invoicesOrderSourcesTableBody = $('#invoices_order_sources tbody');
                        invoicesOrderSourcesTableBody.empty();

                        $.each(data.invoices_order_sources, function(index, item) {
                            var type = '';
                            if (item.part_type_id == 1) {
                                type = 'قطع غيار';
                            } else if (item.part_type_id == 2) {
                                type = 'كاوتش';
                            } else if (item.part_type_id == 3) {
                                type = 'جرار';
                            } else if (item.part_type_id == 4) {
                                type = 'كلارك';
                            } else if (item.part_type_id == 5) {
                                type = 'معدة';
                            } else if (item.part_type_id == 6) {
                                type = 'كيت';
                            }
                            var name = '';
                            if (item.part_type_id == 1) {
                                name = item.part.name ?? 'N/A';
                            } else if (item.part_type_id == 2) {
                                name = item.wheel.name ?? 'N/A';
                            } else if (item.part_type_id == 3) {
                                name = item.tractor.name ?? 'N/A';
                            } else if (item.part_type_id == 4) {
                                name = item.clark.name ?? 'N/A';
                            } else if (item.part_type_id == 5) {
                                name = item.equip.name ?? 'N/A';
                            } else if (item.part_type_id == 6) {
                                name = item.kit.name ?? 'N/A';
                            }

                            var source = item.source.name_arabic ?? 'N/A';
                            var itemCount = item.item_count;

                            var row = '<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + type + '</td>' +
                                '<td>' + name + '</td>' +
                                '<td>' + source + '</td>' +
                                '<td>' + itemCount + '</td>' +
                                '</tr>';

                            invoicesOrderSourcesTableBody.append(row);
                        });
                        //////fifth table///
                        var invoicesOrderStatusesTableBody = $('#invoices_order_statuses tbody');
                        invoicesOrderStatusesTableBody.empty();

                        $.each(data.invoices_order_statuses, function(index, item) {
                            var type = '';
                            var name = '';

                            // Determine the type and name based on part_type_id
                            switch (item.part_type_id) {
                                case 1:
                                    type = 'قطع غيار';
                                    name = item.part.name ?? 'N/A';
                                    break;
                                case 2:
                                    type = 'كاوتش';
                                    name = item.wheel.name ?? 'N/A';
                                    break;
                                case 3:
                                    type = 'جرار';
                                    name = item.tractor.name ?? 'N/A';
                                    break;
                                case 4:
                                    type = 'كلارك';
                                    name = item.clark.name ?? 'N/A';
                                    break;
                                case 5:
                                    type = 'معدة';
                                    name = item.equip.name ?? 'N/A';
                                    break;
                                case 6:
                                    type = 'كيت';
                                    name = item.kit.name ?? 'N/A';
                                    break;
                                default:
                                    type = 'N/A';
                                    name = 'N/A';
                            }

                            var status = item.status.name ?? 'N/A';
                            var itemCount = item.item_count;

                            // Construct the table row
                            var row = '<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + type + '</td>' +
                                '<td>' + name + '</td>' +
                                '<td>' + status + '</td>' +
                                '<td>' + itemCount + '</td>' +
                                '</tr>';

                            // Append the row to the table body
                            invoicesOrderStatusesTableBody.append(row);
                        });

                        ///six table//
                        var invoicesOrderQualitiesTableBody = $('#invoices_order_qualities tbody');
                        invoicesOrderQualitiesTableBody.empty();

                        $.each(data.invoices_order_qualities, function(index, item) {
                            var type = '';
                            if (item.part_type_id == 1) {
                                type = 'قطع غيار';
                            } else if (item.part_type_id == 2) {
                                type = 'كاوتش';
                            } else if (item.part_type_id == 3) {
                                type = 'جرار';
                            } else if (item.part_type_id == 4) {
                                type = 'كلارك';
                            } else if (item.part_type_id == 5) {
                                type = 'معدة';
                            } else if (item.part_type_id == 6) {
                                type = 'كيت';
                            }

                            var name = '';
                            if (item.part_type_id == 1) {
                                name = item.part.name ?? 'N/A';
                            } else if (item.part_type_id == 2) {
                                name = item.wheel.name ?? 'N/A';
                            } else if (item.part_type_id == 3) {
                                name = item.tractor.name ?? 'N/A';
                            } else if (item.part_type_id == 4) {
                                name = item.clark.name ?? 'N/A';
                            } else if (item.part_type_id == 5) {
                                name = item.equip.name ?? 'N/A';
                            } else if (item.part_type_id == 6) {
                                name = item.kit.name ?? 'N/A';
                            }

                            var quality = item.part_quality.name ?? 'N/A';
                            var itemCount = item.item_count;

                            var row = '<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + type + '</td>' +
                                '<td>' + name + '</td>' +
                                '<td>' + quality + '</td>' +
                                '<td>' + itemCount + '</td>' +
                                '</tr>';

                            invoicesOrderQualitiesTableBody.append(row);
                        });




                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + error);
                    }
                });
            });
        </script>
    @endsection
