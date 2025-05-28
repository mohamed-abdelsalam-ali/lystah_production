@extends('layouts.master')
@section('css')



    <style>
     .row {
            align-items: self-end
        }

        .img-bg {
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            position: relative;
            padding-bottom: 100%;
        }

        /* .itmeImg{
                width : 250 ;
                 height :auto;

            } */
        body {
            /* font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; */
            background-color: #f8f9fa;
            color: #212529;
        }

        .invoice {

            background-color: #fff;
            width: 100%;
            margin: 0 auto;
            border: 1px solid #dee2e6;
            padding: 20px;
        }

        .invoice img {
            max-width: 300px;
            margin-bottom: 20px;
        }

        .invoice h2 {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 30px;
            color: blue;
        }

        .invoice h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .invoice ul {
            list-style: none;
            margin-left: 0;
            padding-left: 0;
        }

        .invoice li {
            margin-bottom: 10px;
        }

        .box1 img {
            object-fit: cover;
        }

        @page {
            /* size: auto; */
            margin: 0 auto;
        }

        .watermark
        {
        position:fixed;
        top:40%;
        left:5px;
        opacity:0.2;
        z-index:99;
        color:white;
        }
        .watermark h1{
            transform: rotate(45deg);
            width: 100vw;
            font-size: 135px;
        }
    </style>
@endsection
@section('title')
    Print Tractor Invoice
@stop


@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="invoice">
                <div class="display-1 text-center w-100 watermark">
                    <h1>EMARA GROUP</h1>
                </div>

                <div class="m-1 row">
                    <h2 class="text-center">فاتورة الصنف</h2>
                </div>
                <div>
                    <table>
                        <tbody>
                            <tr>
                                <td class="box1 img col-4 p-4">
                                        @isset($tractor->tractor_images[0]->url)
                                        <img class="h-100 header-profile-user itmeImg w-100"
                                        src="{{ URL::asset('assets/tractor_images/' . $tractor->tractor_images[0]->url) }}"
                                        alt="Emara">';
                                        @endisset

                                </td>
                                <td class="col-4">
                                    <h4>{{ $tractor->name }}</h4>
                                    <h4>{{ isset($tractor->series->model->brand->name) ? $tractor->series->model->brand->name: '--' }}</h4>
                                </td>
                                <td class="col-4">

                                    <h4> السنة : {{ $tractor->year }}</h4>
                                    <h4> السعر : {{ isset($$tractor->all_tractors[0]->order_supplier->total_price) ? $tractor->all_tractors[0]->order_supplier->total_price: '--' }}
                                                {{ isset($tractor->all_tractors[0]->order_supplier->currency_type->name) ? $tractor->all_tractors[0]->order_supplier->currency_type->name : '--' }}</h4>
                                    <h4> الحالة : {{ isset($tractor->all_tractors[0]->status->name) ? $tractor->all_tractors[0]->status->name: '--' }}</h4>
                                    <h4> الجودة : {{ isset($tractor->all_tractors[0]->part_quality->name) ? $tractor->all_tractors[0]->part_quality->name : '--' }}</h4>
                                    <h4> المصدر : {{ isset($tractor->all_tractors[0]->source->name_arabic ) ? $tractor->all_tractors[0]->source->name_arabic  : '--' }}</h4>
                                </td>
                            </tr>

                        </tbody>

                    </table>
                </div>
                <div>
                    <hr>
                    <tr class="col">
                        <td>
                            <h4> الموديل : {{ isset($tractor->series->name ) ? $tractor->series->name  : '--' }}</h4>
                            <h4> الوصف : {{ $tractor->desc }}</h4>
                            <h4> رقم المعدة : {{ $tractor->tractor_number }}</h4>
                        </td>
                    </tr>
                </div>
                <div class="bg-danger hstack">
                    <h3 class="m-2">المورد</h3>
                </div>
                <div class="mt-3">

                    <tr class="col">
                        <td>
                            <h4> المورد : {{ isset($tractor->all_tractors[0]->order_supplier->supplier->name) ? $tractor->all_tractors[0]->order_supplier->supplier->name : '--' }}</h4>
                            <h4> العنوان : {{ isset($tractor->all_tractors[0]->order_supplier->supplier->address) ? $tractor->all_tractors[0]->order_supplier->supplier->address : '--' }}</h4>
                            <h4> الحساب البنكي : {{ isset($tractor->all_tractors[0]->order_supplier->bank_account) ? $tractor->all_tractors[0]->order_supplier->bank_account : '--' }}</h4>


                        </td>
                    </tr>
                </div>

                <div class="bg-danger hstack">
                    <h3 class="m-2">المواصفات</h3>
                </div>
                <div class="align-items-baseline row">
                    <div class="col-4">
                        <div class="table-responsive p-4 rtl">
                            <table
                                class="table card-table table-vcenter text-nowrap datatable text-center  table-hover  row-border cell-border "
                                id="itemtbl">

                                <thead>
                                    <tr>
                                        <th class="bg-soft-dark" colspan="2">مواصفات المحرك</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-start">
                                            Gear Box
                                        </td>
                                        <td class="text-end">
                                            {{ isset($tractor->gearbox->gearname) ? $tractor->gearbox->gearname : '--' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">
                                            Drive
                                        </td>
                                        <td class="text-end">
                                            {{ isset($tractor->drives->name) ? $tractor->drives->name : '--' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">
                                            Power
                                        </td>
                                        <td class="text-end">
                                            {{ $tractor->power }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">
                                            Hours
                                        </td>
                                        <td class="text-end">
                                            {{ $tractor->hours }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="table-responsive p-4 rtl">
                            <table
                                class="table card-table table-vcenter text-nowrap datatable text-center  table-hover  row-border cell-border "
                                id="itemtbl">

                                <thead>
                                    <tr>
                                        <th class="bg-soft-dark" colspan="2">مواصفات المحرك</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-start">
                                            Disc
                                        </td>
                                        <td class="text-end">
                                            {{ $tractor->discs }}

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">
                                            Tank Capacity
                                        </td>
                                        <td class="text-end">
                                            {{ $tractor->tank_capacity }}

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">
                                            Service Date
                                        </td>
                                        <td class="text-end">
                                            {{( $tractor->serivcedate)? $tractor->serivcedate->format('Y-m-d') : 'لم يحدد بعد' }}

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">
                                            Motor Number
                                        </td>
                                        <td class="text-end">
                                            {{ $tractor->motornumber }}

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="table-responsive p-4 rtl">
                            <table
                                class="table card-table table-vcenter text-nowrap datatable text-center  table-hover  row-border cell-border "
                                id="itemtbl">

                                <thead>
                                    <tr>
                                        <th class="bg-soft-dark" colspan="2">مواصفات الكاوتش</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-start">
                                            Front Tire Size
                                        </td>
                                        <td class="text-end">
                                            {{ isset($tractor->frontTires) ? $tractor->frontTires->dimension : '--' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">
                                            Rear Tire Size
                                        </td>
                                        <td class="text-end">
                                            {{ isset($tractor->rearTires) ? $tractor->rearTires->dimension : '--' }}

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">
                                            Front Tire Status
                                        </td>
                                        <td class="text-end">
                                            {{ $tractor->fronttirestatus }} %

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">
                                            Rear Tire Status
                                        </td>
                                        <td class="text-end">
                                            {{ $tractor->reartirestatus }} %
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="bg-danger hstack">
                    <h3 class="m-2">المميزات</h3>
                </div>
                <div class="align-items-baseline row">
                    <div class="col">
                        <div class="table-responsive p-4 rtl">
                            <table
                                class="table card-table table-vcenter text-nowrap datatable text-center  table-hover  row-border cell-border "
                                id="itemtbl">

                                <thead>
                                    <tr>
                                        <th class="bg-soft-dark">الوصف</th>
                                        <th class="bg-soft-dark">القيمة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tractor->tractor_details as $tractorDetails)
                                        <tr>
                                            <td>
                                                {{ $tractorDetails->tractor_spec->name }}
                                            </td>
                                            <td>
                                                {{ $tractorDetails->value }}
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>


    </div>






@endsection

@section('js')


    {{-- <script src="{{ URL::asset('js/invoicePrint.js') }}"></script> --}}
<script>

$(document).ready(function () {



window.print();

window.onafterprint = function(event) {
            window.location.href = '/tractors';

        };


});



</script>

@endsection
