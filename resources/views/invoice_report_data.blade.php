@extends('layouts.master')
{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

@section('css')

    <style>
        .collapsible {
            background-color: #777777a3;
            color: white;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: center;
            outline: none;

        }

        .collapsible:hover {
            background-color: #555;
        }
    </style>
@endsection
@section('title')
    Invoice Details
@stop


@section('content')



    <div class="main-content">
        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h2 class="pt-2 text-bg-dark" >
                            لم يتم الانتهاء من الشاشة بعد
                        </h2>
                        <h4 class="mb-sm-0"> فاتورة بيع رقم {{ $inv_number }}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Invoice Details</li>
                                <li class="breadcrumb-item"><a href="home">Home</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="mx-3">
                <div class="row card">
                    <h4 class="text-center mt-2"> البيانات الرئيسية للفاتورة</h4>
                    <div class="card-body table-responsive-lg">
                        <table class="table-bordered cell-border table table-sm table-striped  fw-bold m-1  ">
                            <thead style="background:#5fcee78a">
                                <tr>
                                    <th class="text-center">رقم الفاتورة</th>
                                    <th class="text-center">العميل </th>
                                    <th class="text-center">المخزن</th>
                                    <th class="text-center">القيمة</th>
                                    <th class="text-center">الإجمالي</th>
                                    <th class="text-center">المدفوع</th>
                                    <th class="text-center">الخصم </th>
                                    <th class="text-center">الضريبة </th>
                                    <th class="text-center">التسعيرة</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">
                                        {{ $invoice->id }}

                                    </td>
                                    <td class="text-center">
                                        {{ $invoice->client->name }}


                                    </td>
                                    <td class="text-center">
                                        {{ $invoice->store->name }}

                                    </td>
                                    <td class="text-center">
                                        {{ $invoice->price_without_tax }}

                                    </td>
                                    <td class="text-center">
                                        {{ $invoice->actual_price }}

                                    </td>
                                    <td class="text-center">
                                        {{ $invoice->paied }}

                                    </td>
                                    <td class="text-center">
                                        {{ $invoice->discount }}

                                    </td>
                                    <td class="text-center">
                                        {{ $invoice->tax_amount }}

                                    </td>
                                    <td class="text-center">
                                        {{ $invoice->invoice_items[0]->pricing_type->type }}


                                    </td>

                                </tr>
                            </tbody>


                        </table>
                    </div>
                </div>

                <div class="row card">
                    <h4 class="text-center mt-2"> الأصناف الموجودة بالفاتورة</h4>
                    <div class="card-body table-responsive-lg">
                        <table class="table-bordered cell-border table table-sm table-striped  fw-bold m-1  ">
                            <thead style="background:#5fcee78a">
                                <tr>
                                    <th class="text-center">اسم الصنف </th>
                                    <th class="text-center">بلد المنشأ</th>
                                    <th class="text-center">الحالة</th>
                                    <th class="text-center">الجودة</th>
                                    <th class="text-center">الكمية</th>
                                    <th class="text-center">السعر</th>
                                    <th class="text-center">الإجمالي</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->invoice_items as $invoice_item)
                                    <tr>
                                        @if (isset($invoice_item->part))
                                            <td>
                                                {{ $invoice_item->part->name }}
                                            </td>
                                        @elseif (isset($invoice_item->kit))
                                            <td>
                                                {{ $invoice_item->kit->name }}
                                            </td>
                                        @elseif (isset($invoice_item->wheel))
                                            <td>
                                                {{ $invoice_item->wheel->name }}
                                            </td>
                                        @elseif (isset($invoice_item->tractor))
                                            <td>
                                                {{ $invoice_item->tractor->name }}
                                            </td>
                                        @elseif (isset($invoice_item->equip))
                                            <td>
                                                {{ $invoice_item->equip->name }}
                                            </td>
                                        @elseif (isset($invoice_item->clark))
                                            <td>
                                                {{ $invoice_item->clark->name }}
                                            </td>
                                        @endif
                                        <td class="text-center">
                                            {{ $invoice_item->source->name_arabic }}

                                        </td>
                                        <td class="text-center">
                                            {{ $invoice_item->status->name }}


                                        </td>
                                        <td class="text-center">
                                            {{ $invoice_item->part_quality->name }}

                                        </td>
                                        <td class="text-center">
                                            {{ $invoice_item->amount }}

                                        </td>
                                        <td class="text-center">

                                            {{ $invoice_item->price->price }}

                                        </td>
                                        <td class="text-center">

                                            {{ $invoice_item->price->price * $invoice_item->amount }}

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>


                        </table>
                    </div>
                </div>

                <div class="row card">
                    <h4 class="text-center mt-2"> تفاصيل الأصناف الموجودة بالفاتورة</h4>
                    <div class="card-body table-responsive-lg">
                        <table class="table-bordered cell-border table table-sm table-striped  fw-bold m-1  ">
                            <thead style="background:#5fcee78a">
                                <tr>
                                    <th class="text-center">اسم الصنف </th>
                                    <th class="text-center">بلد المنشأ</th>
                                    <th class="text-center">الحالة</th>
                                    <th class="text-center">الجودة</th>
                                    <th class="text-center">الكمية</th>
                                    <th class="text-center">اسم المورد</th>
                                    <th class="text-center">العملة </th>
                                    <th class="text-center"> سعر الشراء</th>
                                    <th class="text-center">الإجمالي </th>
                                    <th class="text-center">رقم فاتورة الشراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->invoice_items as $invoice_item)
                                    <tr>
                                        @if (isset($invoice_item->part))
                                            <td>
                                                {{ $invoice_item->part->name }}
                                            </td>
                                        @elseif (isset($invoice_item->kit))
                                            <td>
                                                {{ $invoice_item->kit->name }}
                                            </td>
                                        @elseif (isset($invoice_item->wheel))
                                            <td>
                                                {{ $invoice_item->wheel->name }}
                                            </td>
                                        @elseif (isset($invoice_item->tractor))
                                            <td>
                                                {{ $invoice_item->tractor->name }}
                                            </td>
                                        @elseif (isset($invoice_item->equip))
                                            <td>
                                                {{ $invoice_item->equip->name }}
                                            </td>
                                        @elseif (isset($invoice_item->clark))
                                            <td>
                                                {{ $invoice_item->clark->name }}
                                            </td>
                                        @endif
                                        <td class="text-center">
                                            {{ $invoice_item->source->name_arabic }}

                                        </td>
                                        <td class="text-center">
                                            {{ $invoice_item->status->name }}


                                        </td>
                                        <td class="text-center">
                                            {{ $invoice_item->part_quality->name }}

                                        </td>
                                        <td class="text-center">
                                            {{ $invoice_item->amount }}

                                        </td>
                                        <td class="text-center">
                                            <ul>
                                                @foreach ($invoice_item->invoice_item_order_suppliers as $invoice_item_order_suppliers)
                                                    <li> <strong>{{ $invoice_item_order_suppliers->order_supplier->supplier->name }}</strong>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="text-center">
                                            <ul>
                                                @foreach ($invoice_item->invoice_item_order_suppliers as $invoice_item_order_suppliers)
                                                    <li> <strong>{{ $invoice_item_order_suppliers->currency_value->name }}</strong>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="text-center">
                                            {{ $invoice_item->buy_inv_price->price }}

                                        </td>
                                        <td class="text-center">

                                            <ul>
                                                @foreach ($invoice_item->invoice_item_order_suppliers as $invoice_item_order_suppliers)
                                                    <li> <strong>{{ $invoice_item_order_suppliers->currency_value->currencies[0]->value * $invoice_item->buy_inv_price->price  * $invoice_item->amount }}</strong>
                                                    </li>
                                                @endforeach
                                            </ul>

                                        </td>
                                        <td class="text-center">

                                            <ul>
                                                @foreach ($invoice_item->invoice_item_order_suppliers as $invoice_item_order_suppliers)
                                                    <li> <strong>{{ $invoice_item_order_suppliers->order_supplier->buy_transaction->id }}</strong>
                                                    </li>
                                                @endforeach
                                            </ul>

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>


                        </table>
                    </div>
                </div>
                <div class="row card">
                    <h4 class="text-center mt-2"> أمكان الأصناف الموجودة بالفاتورة</h4>
                    <div class="card-body table-responsive-lg">
                        <table class="table-bordered cell-border table table-sm table-striped  fw-bold m-1  ">
                            <thead style="background:#5fcee78a">
                                <tr>
                                    <th class="text-center">اسم الصنف </th>
                                    <th class="text-center">بلد المنشأ</th>
                                    <th class="text-center">الحالة</th>
                                    <th class="text-center">الجودة</th>
                                    <th class="text-center">الكمية</th>
                                    <th class="text-center"> المكان </th>
                                    <th class="text-center">المخزن</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->invoice_items as $invoice_item)
                                    <tr>
                                        @if (isset($invoice_item->part))
                                            <td>
                                                {{ $invoice_item->part->name }}
                                            </td>
                                        @elseif (isset($invoice_item->kit))
                                            <td>
                                                {{ $invoice_item->kit->name }}
                                            </td>
                                        @elseif (isset($invoice_item->wheel))
                                            <td>
                                                {{ $invoice_item->wheel->name }}
                                            </td>
                                        @elseif (isset($invoice_item->tractor))
                                            <td>
                                                {{ $invoice_item->tractor->name }}
                                            </td>
                                        @elseif (isset($invoice_item->equip))
                                            <td>
                                                {{ $invoice_item->equip->name }}
                                            </td>
                                        @elseif (isset($invoice_item->clark))
                                            <td>
                                                {{ $invoice_item->clark->name }}
                                            </td>
                                        @endif
                                        <td class="text-center">
                                            {{ $invoice_item->source->name_arabic }}

                                        </td>
                                        <td class="text-center">
                                            {{ $invoice_item->status->name }}


                                        </td>
                                        <td class="text-center">
                                            {{ $invoice_item->part_quality->name }}

                                        </td>
                                        <td class="text-center">
                                            {{ $invoice_item->amount }}

                                        </td>
                                        <td class="text-center">
                                            <ul>
                                                @foreach ($invoice_item->invoice_item_section as $invoice_item_section)
                                                    <li> <strong>{{ $invoice_item_section->store_structure->name }}</strong>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="text-center">
                                            <ul>
                                                @foreach ($invoice_item->invoice_item_section as $invoice_item_section)
                                                    <li> <strong>{{ $invoice_item_section->store_structure->store->name }}</strong>
                                                    </li>
                                                @endforeach
                                            </ul>

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>


                        </table>
                    </div>
                </div>

                <div class="row card">
                    <h4 class="text-center mt-2"> تفاصيل الأصناف المرتجع بالفاتورة</h4>
                    <div class="card-body table-responsive-lg">
                        <table class="table-bordered cell-border table table-sm table-striped  fw-bold m-1  ">
                            <thead style="background:#5fcee78a">
                                <tr>
                                    <th class="text-center">اسم الصنف </th>
                                    <th class="text-center">بلد المنشأ</th>
                                    <th class="text-center">الحالة</th>
                                    <th class="text-center">الجودة</th>
                                    <th class="text-center">الكمية</th>
                                    <th class="text-center">السعر</th>
                                    <th class="text-center">الإجمالي </th>
                                    <th class="text-center"> الضريبة</th>
                                    <th class="text-center"> تاريخ الارتجاع</th>

                                </tr>
                            </thead>
                            <tbody>
                                    @foreach ($invoice->refund_invoices as $refund_invoices)
                                        <tr>
                                            @if (isset($refund_invoices->data))
                                                <td>
                                                    {{ $refund_invoices->data->part->name }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $refund_invoices->data->source->name_arabic }}

                                                </td>
                                                <td class="text-center">
                                                    {{ $refund_invoices->data->status->name }}


                                                </td>
                                                <td class="text-center">
                                                    {{ $refund_invoices->data->part_quality->name }}

                                                </td>
                                            @elseif (isset($refund_invoices->item_kit))
                                                <td>
                                                    {{ $refund_invoices->item_kit->kit->name }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $refund_invoices->item_kit->source->name_arabic }}

                                                </td>
                                                <td class="text-center">
                                                    {{ $refund_invoices->item_kit->status->name }}


                                                </td>
                                                <td class="text-center">
                                                    {{ $refund_invoices->item_kit->part_quality->name }}

                                                </td>
                                            @elseif (isset($refund_invoices->item_wheel))
                                                <td>
                                                    {{ $refund_invoices->item_wheel->wheel->name }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $refund_invoices->item_wheel->source->name_arabic }}

                                                </td>
                                                <td class="text-center">
                                                    {{ $refund_invoices->item_wheel->status->name }}


                                                </td>
                                                <td class="text-center">
                                                    {{ $refund_invoices->item_wheel->part_quality->name }}

                                                </td>
                                            @elseif (isset($refund_invoices->item_tractor))
                                                <td>
                                                    {{ $refund_invoices->item_tractor->tractor->name }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $refund_invoices->item_tractor->source->name_arabic }}

                                                </td>
                                                <td class="text-center">
                                                    {{ $refund_invoices->item_tractor->status->name }}


                                                </td>
                                                <td class="text-center">
                                                    {{ $refund_invoices->item_tractor->part_quality->name }}

                                                </td>
                                            @elseif (isset($refund_invoices->item_equip))
                                                <td>
                                                    {{ $refund_invoices->item_equip->equip->name }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $refund_invoices->item_equip->source->name_arabic }}

                                                </td>
                                                <td class="text-center">
                                                    {{ $refund_invoices->item_equip->status->name }}


                                                </td>
                                                <td class="text-center">
                                                    {{ $refund_invoices->item_equip->part_quality->name }}

                                                </td>
                                            @elseif (isset($refund_invoices->item_clark))
                                                <td>
                                                    {{ $refund_invoices->item_clark->clark->name }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $refund_invoices->item_clark->source->name_arabic }}

                                                </td>
                                                <td class="text-center">
                                                    {{ $refund_invoices->item_clark->status->name }}


                                                </td>
                                                <td class="text-center">
                                                    {{ $refund_invoices->item_clark->part_quality->name }}

                                                </td>
                                            @endif

                                            <td class="text-center">
                                                {{ $refund_invoices->r_amount }}
                                            </td>
                                            {{-- <td class="text-center">
                                            <ul>
                                                @foreach ($refund_invoices->refund_invoices_order_suppliers as $refund_invoices_order_suppliers)
                                                    <li> <strong>{{ $refund_invoices_order_suppliers->order_supplier->supplier->name }}</strong>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td> --}}
                                            <td class="text-center">
                                                {{ $refund_invoices->item_price }}
                                            </td>
                                            <td class="text-center">
                                                {{ $refund_invoices->item_price * $refund_invoices->r_amount }}
                                            </td>
                                            <td class="text-center">
                                                {{ $refund_invoices->r_tax }}
                                            </td>
                                            <td class="text-center">
                                                {{ $refund_invoices->date }}
                                            </td>

                                            {{-- <td class="text-center">
                                            <ul>
                                                @foreach ($refund_invoices->refund_invoices_order_suppliers as $refund_invoices_order_suppliers)
                                                    <li> <strong>{{ $refund_invoices_order_suppliers->order_supplier->buy_transaction->id }}</strong>
                                                    </li>
                                                @endforeach
                                            </ul>

                                        </td> --}}
                                        </tr>
                                    @endforeach
                               


                            </tbody>


                        </table>
                    </div>
                </div>

                <div class="row card">
                    <h4 class="text-center mt-2"> تفاصيل إجمالي الفاتورة</h4>
                    <div class="card-body table-responsive-lg">
                        <table class="table-bordered cell-border table table-sm table-striped  fw-bold m-1  ">
                            <thead style="background:#5fcee78a">
                                <tr>
                                    <th class="text-center">إجمالي سعر البيع </th>
                                    <th class="text-center">إجمالي سعر الشراء </th>
                                    <th class="text-center">إجمالي سعر المرتجع </th>
                                    <th class="text-center"> الربح</th>


                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                       {{ $invoice->actual_price - ($invoice->discount + $invoice->tax_amount) }}

                                    </td>
                                    <td>
                                        @php
                                            $total_buy_invoice_price = 0;
                                            foreach ($invoice->invoice_items as $key => $Item) {
                                                if(count($Item->invoice_item_order_suppliers) > 0){
                                                    $total_buy_invoice_price +=
                                                    $Item->invoice_item_order_suppliers[0]->currency_value
                                                        ->currencies[0]->value * $Item->buy_inv_price->price * $Item->amount ;
                                                }else{
                                                
                                                }
                                                
                                                
                                            }

                                        @endphp
                                        {{ $total_buy_invoice_price }}

                                    </td>

                                    <td>
                                        @php
                                            $total_refund_invoice_price = 0;
                                            foreach ($invoice->refund_invoices as $key => $refund_invoices) {
                                                $total_refund_invoice_price +=
                                                    $refund_invoices->item_price * $refund_invoices->r_amount;
                                            }

                                        @endphp
                                        {{ $total_refund_invoice_price }}

                                    </td>
                                    <td>

                                    {{($invoice->actual_price - ($invoice->discount + $invoice->tax_amount)) - ($total_buy_invoice_price + $total_refund_invoice_price) > 0 ? ($invoice->actual_price - ($invoice->discount + $invoice->tax_amount)) - ($total_buy_invoice_price + $total_refund_invoice_price) : 0  }}
                                    </td>








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

@endsection
