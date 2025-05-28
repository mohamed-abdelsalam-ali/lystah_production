@extends('layouts.master')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <style>
        
        @media print {
         .dateLable h6 {white-space: nowrap!important;}
      }
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #212529;
        }

        .invoice {

            background-color: #fff;
            width: 800px;
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
            size: auto;
            margin: 0;
        }

        .watermark {
            position: fixed;
            top: 40%;
            left: 5px;
            opacity: 0.2;
            z-index: 99;
            color: white;
        }

        .watermark h1 {
            transform: rotate(45deg);
            width: 100vw;
            font-size: 135px;
        }
    </style>
@endsection
@section('title')
    Print Service Invoice
@stop


@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="invoice">
                <div class="text-center display-1 w-100 watermark">
                    <h1>EMARA GROUP</h1>
                </div>

                <div class="row">
                    <h2 class="text-center">فاتورة صيانة</h2>
                </div>
                <div class="bg-danger hstack">
                    <h3 class="m-2">بيانات العميل</h3>
                </div>
                <div>
                    <table>
                        <tbody>
                            @if($service->serviceoptionid == 2)
                                    <tr>
                                        <td class="col-1">
                                            <h4>الإسم : </h4>
                                        </td>
                                        <td class="col-3 text-start" >
                                            <h6>{{ isset($service->client->name) ? $service->client->name : $service->store->name }}</h6>
                                        </td>
                                        <td class="col-1">
                                            <h4>رقم التليفون : </h4>
                                        </td>
                                        <td class="col-2">
                                            <h6>{{ isset($service->client->tel01) ? $service->client->tel01 : '--' }}</h6>
                                        </td>
                                        <td  class="col-1 text-start">
                                            <h4>التاريخ  : </h4>
                                        </td>
                                        <td class="dateLable col-2" >
                                            <h6>{{ $service->date }}</h6>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-2">
                                            <h4>رقم اللوحة : </h4>
                                        </td>
                                        <td class="col-2">
                                            <h6>{{ isset($service->motornumber) ? $service->motornumber : '--' }}</h6>
                                        </td>
                                        <td class="col-2">
                                            <h4>نوع الخدمة : </h4>
                                        </td>
                                        <td class="col-2">
                                            <h6>{{ isset($service->serviceoption->option) ? $service->serviceoption->option : '--' }}</h6>
                                        </td>
                                        <td class="col-2">
                                            <h4>نوع المعدة : </h4>
                                        </td>
                                        <td class="col-2 text-end">
                                            <h6>{{ isset($service->servicetype->type) ? $service->servicetype->type : '--' }}</h6>
                                        </td>
                                </tr>
                            @endif
                            @if($service->serviceoptionid == 1)
                                <tr>
                                    
                                    <td class="col-1">
                                        <h4>الإسم  : </h4>
                                    </td>
                                    <td class="col-3 text-start" >
                                        <h6>{{ $service->store->name }}</h6>
                                    </td>
                                    <td  class="col-1 text-start">
                                        <h4>التاريخ  : </h4>
                                    </td>
                                    <td class="col-1 text-start" >
                                        <h6>{{ $service->date }}</h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-1">
                                        <h4>إسم المعدة : </h4>
                                    </td>
                                    <td class="col-3 text-start" >
                                        <h6>{{ $item->name }}</h6>
                                    </td>
                                    <td class="col-1">
                                        <h4>رقم المعدة : </h4>
                                    </td>
                                    <td class="col-3 text-start" >
                                        <h6>{{ isset($item->tractor_number) ?  $item->tractor_number : $item->clark_number}}</h6>
                                    </td>
                                    <td class="col-2">
                                        <h4>نوع المعدة : </h4>
                                    </td>
                                    <td class="col-2 text-end">
                                        <h6>{{ isset($service->servicetype->type) ? $service->servicetype->type : '--' }}</h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-2">
                                        <h4> الماركة : </h4>
                                    </td>
                                    <td class="col-2">
                                        <h6>{{ isset($item->series->model->brand->name) ? $item->series->model->brand->name : '--'}}</h6>
                                    </td>
                                    <td class="col-2">
                                        <h4> الموديل : </h4>
                                    </td>
                                    <td class="col-2">
                                        <h6>{{ isset($item->series->model->name) ? $item->series->model->name: '--'}}</h6>
                                    </td>
                                    <td class="col-2">
                                        <h4> المسلسل : </h4>
                                    </td>
                                    <td class="col-2">
                                        <h6>{{ isset($item->series->name) ? $item->series->name: '--'}}</h6>
                                    </td>
                                    
                                </tr>
                                <tr>
                                    
                                    <td class="col-2">
                                        <h4>نوع الخدمة : </h4>
                                    </td>
                                    <td class="col-2">
                                        <h6>{{ isset($service->serviceoption->option) ? $service->serviceoption->option : '--' }}</h6>
                                    </td>
                                    
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="bg-danger hstack">
                    <h3 class="m-2">الخدمات</h3>
                </div>
                <div>
                    <table class="table text-center table-sm">
                        <thead>
                            <td class="col-6">
                                <h4>الخدمة</h4>
                            </td>
                            <td class="col-6">
                                <h4>القيمة</h4>
                                
                            </td>
                        </thead>
                        <tbody>
                            @foreach ($service->service_invoice_items as $services)
                                <tr class="col-12">
                                    <td class="col-6">
                                        <h6>{{ $services->service->name }}</h6>
                                    </td>
                                    <td class="col-6">
                                        <h6>{{ $services->price }} جنيهاً مصرياً</h6>
                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        
                    </table>
                    <table>
                        <tbody>
                            <tr class="text-center col-12 bg-soft-dark">
                                <td class="col-6">
                                    <h4>
                                        إجمالي الخدمات
                                    </h4>
                                </td>
                                <td class="col-6">
                                    <h6>
                                    {{ $service->totalbefortax }} جنيهاً مصرياً
                                    </h6>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    
                </div>

                <div class="bg-danger hstack">
                    <h3 class="m-2">الضرائب</h3>
                </div>
                <div class="align-items-baseline row">
                    <div>
                            <table class="table text-center table-sm">
                                <thead>
                                    <td class="col-6">
                                        <h4>الضريبة</h4>
                                    </td>
                                    <td class="col-6">
                                        <h4>القيمة</h4>
                                        
                                    </td>
                                </thead>
                                <tbody>
                                    @foreach ($service->service_taxes as $taxes)
                                        <tr class="col-12">
                                            <td class="col-6">
                                                <h6>{{ $taxes->tax->name }}</h6>
                                            </td>
                                            <td class="col-6">
                                                <h6>{{ $taxes->tax->value }} %</h6>
                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                
                            </table>
                            <table>
                                <tbody>
                                    <tr class="text-center col-12 bg-soft-dark">
                                        <td class="col-3">
                                            <h4>
                                                إجمالي الضرائب : 
                                            </h4>
                                        </td>
                                        <td class="col-2">
                                            <h6>{{ isset($service->totaltax) ? $service->totaltax : '0' }} %</h6>
                                        </td>
                                        <td class="col-2">
                                            <h4>
                                                إجمالي الفاتورة : 
                                            </h4>
                                        </td>
                                        <td class="col-3">
                                            <h6>{{ $service->total}} جنيها مصرياً</h6>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
                </div>
                <div class="bg-danger hstack">
                    <h3 class="m-2">الحساب</h3>
                </div>
                
                <div class="align-items-baseline row">
                    <div class="mt-3">
                            <table>
                                <tbody>
                                    <tr class="text-center col-12 ">
                                        <td class="col-2">
                                            <h4>قيمة الخصم : </h4>
                                        </td>
                                        <td class="col-2">
                                            <h6>{{ $service->discount }}  ٪</h6>
                                        </td>
                                        <td class="col-2">
                                            <h4>قيمة الفاتورة : </h4>
                                        </td>
                                        <td class="col-2">
                                            <h6>{{ $service->total - ($service->total * $service->discount / 100)}} جنيهاً مصرياً</h6>
                                        </td>
                                    </tr>
                                    <tr class="text-center col-12">
                                        <td class="col-2">
                                            <h4> المدفوع : </h4>
                                        </td>
                                        <td class="col-2">
                                            <h6>{{ $service->totalpaid }}  جنيهاً مصرياً</h6>
                                        </td>
                                        <td class="col-2">
                                            <h4>قيمة المديونية : </h4>
                                        </td>
                                        <td class="col-2">
                                            <h6>{{ $service->remain}} جنيهاً مصرياً</h6>
                                        </td>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('assets/js/serviceInvoicePrint.js') }}"></script>
    <script>
    
// $(document).ready(function () {



//     window.print();
//     window.onafterprint = function(event) {
//         window.location.href = '/serviceInvoice';

//     };

// });
    </script>

@endsection
