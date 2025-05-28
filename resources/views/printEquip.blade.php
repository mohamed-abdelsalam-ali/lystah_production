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
    Print Equipment Invoice
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
                                    @isset($equip->equip_images[0]->image_name)
                                        <img class="h-100 header-profile-user itmeImg w-100"
                                        src="{{ URL::asset('assets/equip_images/' . $equip->equip_images[0]->image_name) }}"
                                        alt="Emara">';
                                    @endisset

                                </td>
                                <td class="col-4">
                                    <h4>{{ $equip->name }}</h4>
                                    <h4>{{ isset($equip->series->model->brand->name) ? $equip->series->model->brand->name: '--' }}</h4>
                                </td>
                                <td class="col-4">

                                    <h4> السنة : {{ $equip->year }}</h4>
                                    <h4> السعر : {{ isset($equip->all_equips[0]->order_supplier->total_price) ? $equip->all_equips[0]->order_supplier->total_price: '--' }}
                                    {{ isset($equip->all_equips[0]->order_supplier->currency_type->name) ? $equip->all_equips[0]->order_supplier->currency_type->name: '--' }}
                                    </h4>
                                    <h4> الحالة : {{ isset($equip->all_equips[0]->status->name) ? $equip->all_equips[0]->status->name: '--' }}</h4>
                                    <h4> الجودة : {{ isset($equip->all_equips[0]->part_quality->name) ? $equip->all_equips[0]->part_quality->name: '--' }}</h4>
                                    <h4> المصدر : {{ isset($equip->all_equips[0]->source->name_arabic) ? $equip->all_equips[0]->source->name_arabic: '--' }}</h4>
                                </td>
                            </tr>

                        </tbody>

                    </table>
                </div>
                <div>
                    <hr>
                    <tr class="col">
                        <td>
                            <h4> الموديل : {{ isset($equip->series->model->brand->name) ? $equip->series->model->brand->name: '--' }}</h4>
                            <h4> الوصف : {{ $equip->desc }}</h4>

                        </td>
                    </tr>
                </div>
                <div class="bg-danger hstack">
                    <h3 class="m-2">المورد</h3>
                </div>
                <div class="mt-3">

                    <tr class="col">
                        <td>
                            <h4> المورد : {{ isset($equip->all_equips[0]->order_supplier->supplier->name) ? $equip->all_equips[0]->order_supplier->supplier->name: '--' }}</h4>
                            <h4> العنوان : {{ isset($equip->all_equips[0]->order_supplier->supplier->address) ? $equip->all_equips[0]->order_supplier->supplier->address: '--' }}</h4>
                            <h4> الحساب البنكي : {{ isset($equip->all_equips[0]->order_supplier->bank_account) ? $equip->all_equips[0]->order_supplier->bank_account: '--' }}</h4>
                        </td>
                    </tr>
                </div>

                <div class="bg-danger hstack">
                    <h3 class="m-2">المواصفات</h3>
                </div>
                <div class="align-items-baseline row">
                    <div class="col">
                        <div class="table-responsive p-4 rtl">
                            <table
                                class="table card-table table-vcenter text-nowrap datatable text-center  table-hover  row-border cell-border "
                                id="itemtbl">

                                <thead>
                                    <tr>
                                        <th class="bg-soft-dark">الوصف </th>
                                        <th class="bg-soft-dark">القيمة </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td class="text-start">
                                            Tank Capacity
                                        </td>
                                        <td class="text-end">
                                            {{ $equip->tank_capacity }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">
                                            Hours
                                        </td>
                                        <td class="text-end">
                                            {{ $equip->hours }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">
                                            Disc
                                        </td>
                                        <td class="text-end">
                                            {{ $equip->discs }}

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">
                                            Delivery Date
                                        </td>
                                        <td class="text-end">
                                            {{ isset($equip->order_supplier->deliver_date) ? $equip->order_supplier->deliver_date->format('Y-m-d'): '--' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">
                                            Service Date
                                        </td>
                                        <td class="text-end">
                                            {{ isset($equip->last_sevice_date) ? $equip->last_sevice_date->format('Y-m-d'): '--' }}
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
                                    @foreach ($equip->equip_details as $equipDetails)
                                        <tr>
                                            <td>
                                                {{ $equipDetails->equip_spec->name }}
                                            </td>
                                            <td>
                                                {{ $equipDetails->value }}
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
<script>

    $(document).ready(function () {



    window.print();

    window.onafterprint = function(event) {
                window.location.href = '/equips';

            };


    });



</script>
@endsection
