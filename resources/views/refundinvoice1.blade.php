<!doctype html>
<html lang="en" data-layout="twocolumn" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none"
    data-preloader="disable" dir="rtl">


<head>
    <meta charset="utf-8" />
    <title>
        فواتير العملاء
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico') }}">

    <link href="{{ URL::asset('assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" />

    <!-- Layout config Js -->
    <script src="{{ URL::asset('assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ URL::asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ URL::asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ URL::asset('assets/css/app-rtl.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ URL::asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <style>
        body {
            margin: 2em;
            font-size: 20px;
        }
        .homeDiv{
            position: absolute;
            top: 50px;
            left: 50px;
            z-index: 88888;
        }
        #madMdl .modal-content{
            width: 60vw !important;
        }

        #madMdl .modal-dialog{
            max-width: 60vw !important;
        }
        table {
            border: 1px solid #ccc;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            width: 100%;
            table-layout: fixed;
        }
    </style>

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">
        <div class="main-content m-0">
            <div class="page-content p-0">
                <div class="homeDiv">
                    <form action="/pos" method="">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="storeId" value="{{ $store->id }}">
                        <button class="btn btn-secondary float-md-end">الرئيسية</button>
                    </form>
                </div>
                <form action="/saveRefund" method="POST">
                        @csrf
                        @method('POST')
                <input type="hidden" name="clientId" value=" {{ $client->id }}">
                <div class="bg-white border border-2 border-dark mb-3 p-2 row">
                    <h1 class="text-center text-decoration-underline">{{ $store->name }}</h1>
                    <input type="hidden" name="storeId" id="storeId" value="{{ $store->id }}">
                    <div class="col">
                        <div class="row d-none">
                            <div class="col-12 table-responsive" style="overflow-x:auto;">
                                بيانات العميل
                                <table class="table">
                                    <tr>
                                        <td>
                                            <label for="" class="text-success">الاسم :</label>
                                            {{ $client->name }}</td>
                                        <td>
                                            <label for="" class="text-success">رقم التليفون :</label>
                                            {{ $client->tel01 }}</td>
                                        <td><label for="" class="text-success">رقم التليفون  1:</label>
                                            {{ $client->tel02 }}</td>
                                    </tr>
                                    <tr>
                                        <td><label for="" class="text-success">رقم التليفون  2:</label>
                                            {{ $client->tel03 }}</td>
                                        <td><label for="" class="text-success">الرقم القومي  :</label>
                                            {{ $client->national_no }}</td>
                                        <td><label for="" class="text-success">ملاحظات  :</label>
                                            {{ $client->notes }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><label for="" class="text-success">العنوان  :</label>
                                            {{ $client->address }}</td>
                                    </tr>
                                </table>
                            </div>
                         
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <h3>بيانات الفاتورة</h3> 
                                <label for="" class="text-success">رقم الفاتورة :</label> <span> {{$invoice->id}}</span><br/>
                                <label for="" class="text-success">إجمالي الفاتورة :</label> <span id="baseSubtot"> {{$invoice->price_without_tax - $refund_price}}</span><br/>
                                <label for="" class="text-success">إجمالي الضريبة :</label> <span> {{$invoice->tax_amount - $refund_total_tax}}</span><br/>
                                <label for="" class="text-success"> الاجمالي :</label> <span> {{$invoice->actual_price - $refund_price - $refund_total_tax}}</span><br/>
                                <label for="" class="text-success"> المدفوع :</label> <span> {{$invoice->paied}}</span><br/>
                                
                            </div>
                            <div class="col-lg-4">
                                <h3>الضريبة</h3>
                                <ul>
                                    @foreach($taxes as $tax)
                                     <li>{{ $tax->name}} : {{$tax->value}}</li>
                                     
                                    @endforeach
                                    
                                </ul>
                            </div>
                            <div class="col-lg-4">
                                
                                <label for="" class="text-success">Total Refund : </label> <span id="refundAmount"> 00</span><br/>
                                <label for="" class="text-success">Total Refund Price : </label> <span id="refundPrice"> 00</span><br/>
                                <hr>
                                <label for="" class="text-success">Total WithOut Tax  : </label> <span id="subtotal"> {{$invoice->price_without_tax  - $refund_price}} </span><br/>
                                <label for="" class="text-success">New Tax : </label> <span id="newTax"> 00</span><br/>
                                <input type="hidden" value="0" id="newTaxx" name="newTax">
                                <label for="" class="text-success">Total : </label> <span id="invoiceTotal"> 00</span><br/>
                                <label for="" class="text-success">المبلغ المستحق رده : </label> <span  class="text-danger display-6" id="refundMoney"> 00</span><br/>
                                
                                <input type="hidden" value="0" id="refundMoneyxx" name="totalRef">
                                <label for="" class="text-success">طريقة الدفع : </label>
                                <select class="form-select" name="paymnet" id="">
                                    <option value="0">كاش</option>
                                    <option value="1">علي الحساب</option>
                                </select>
                                
                            </div>
                        </div>
                        <hr>
                     <button> حفـــــــــــــــظ </button>
                        <hr/>
                        <input type="search" placeholder="بحــــث" name="" id="searchClientInv">
                        <div class="row mt-2">
                            <div class="col">
                                <table class="table">
                                    <thead>
                                        <tr>
                                           <th>م</th>
                                            <th>النوع</th>
                                            <th>الصنف</th>
                                            <th>الكمية</th>
                                            <th>السعر</th>
                                            <th>المنشأ</th>
                                            <th>الحالة</th>
                                            <th>الجودة</th>
                                            <th>مرتجع </th>
                                            
                                            <th> </th>

                                        </tr>
                                    </thead>
                                    @php
                                        $m=1;
                                    @endphp
                                    <input type="hidden" value="{{ $invoice->id }}" name="invoiceId">
                                       @foreach ($invoiceItems as $invoicex )
                                        @if($invoicex->amount - $invoicex->refund_amount > 0 )
                                        <tr class="resinv">

                                            <td>{{ $m++ }}</td>
                                            @if ($invoicex->part_type_id == 1)
                                                <input type="hidden" value="{{ $invoicex->part->id }}" name="refunditem[]">

                                                <input type="hidden" value="1" name="part_type_id[]">
                                                <td>قطع غيار</td>
                                                <td>{{ $invoicex->part->name }}</td>
                                            @elseif ($invoicex->part_type_id == 2)
                                                 <input type="hidden" value="{{ $invoicex->wheel->id }}" name="refunditem[]">
                                                <input type="hidden" value="2" name="part_type_id[]">
                                                <td>كاوتش</td>
                                                <td>{{ $invoicex->wheel->name }}</td>
                                            @elseif ($invoicex->part_type_id == 6)
                                                <input type="hidden" value="{{ $invoicex->kit->id }}" name="refunditem[]">
                                                <input type="hidden" value="6" name="part_type_id[]">
                                                <td>كيت</td>
                                                <td>{{ $invoicex->kit->name }}</td>
                                            @endif
                                            <input type="hidden" value="{{ $invoicex->source_id }}" name="refunditemSource[]">
                                            <input type="hidden" value="{{ $invoicex->status_id }}" name="refunditemStatus[]">
                                            <input type="hidden" value="{{ $invoicex->quality_id }}" name="refunditemquality[]">
                                            <input type="hidden" value="{{ $invoicex->sale_type }}" name="refunditemSaletype[]">
                                            <input type="hidden" value="{{ $invoicex->price[0]->price }}" name="refunditemSalePrice[]">

                                            <td class="itemAmount">{{ $invoicex->amount - $invoicex->refund_amount }}</td>
                                            <td class="itemPrice">{{ $invoicex->price[0]->price}}</td>
                                            <td>{{ $invoicex->source->name_en }}</td>
                                            <td>{{ $invoicex->status->name }}</td>
                                            <td>{{ $invoicex->part_quality->name }}</td>

                                            <td>
                                                <input class="form-control RefAmount" oninput="this.value = Math.abs(this.value)" onkeyup="calcInvoiceTotal(this,{{ $invoicex->amount}},{{ $invoicex->price[0]->price}})" type="number"  value="0" min=0 name="refundAmount[]" id="">
                                            </td>
                                            <td>{{ $invoicex->refund_amount }}</td>
                                        </tr>
                                        @else
                                        @endif


                                    @endforeach
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                </form>


            </div>
        </div>
    </div>

 
    <script src="{{ URL::asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    {{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js"></script>

    <script>
        var partsDt;
        var TotalTax = 0;
        var salamSubtotal = {!! $invoice->price_without_tax  !!} ;
        var salamSubtotal_refunded = {!! $refund_price  !!} ;
        var InvoiceTaxes = {!! $taxes  !!};
        var InvoiceTaxes_refunded = {!! $taxes  !!};
        
        
        $(document).ready(function() {

        });

        
        InvoiceTaxes.forEach(function(tax){ 
              TotalTax += tax.value;      
        })
        $("#searchClientInv").keyup(function (e) {
            e.preventDefault();
            var searchValue = $(this).val();
            $('.resinv').hide();
            $('.resinv:contains("'+searchValue+'")').show();

        });
      
        function calcInvoiceTotal(el,oldAmount,itemPrice){
            var refundAmounts = 0;
            var refundPrices = 0;
            var refundAmount = $(el).val();
            
            refundAmount = parseInt(refundAmount);
            if(refundAmount <=oldAmount){
               
               $('.resinv').each(function(index,row){
                //   console.log(row);
                var taxee = parseFloat($(row).find('.itemPrice').text()) * parseInt($(row).find('.RefAmount').val());
                refundAmounts += parseInt($(row).find('.RefAmount').val());
                refundPrices +=parseFloat($(row).find('.itemPrice').text()) * parseInt($(row).find('.RefAmount').val());
                
                    
                
             
               })
               
         
            }else{
                
                $(el).val(0);
                
                 
            }
              $("#subtotal").text( salamSubtotal -  refundPrices -salamSubtotal_refunded );
                //  $("#newTax").text( parseFloat($("#subtotal").text()) * TotalTax / 100); 
                 $("#newTax").text( parseFloat( refundPrices ) * TotalTax / 100);
                 $("#newTaxx").val($("#newTax").text());
                
                // $("#invoiceTotal").text( Math.round(parseFloat($("#subtotal").text()) + parseFloat($("#newTax").text())));
                $("#invoiceTotal").text( Math.round(parseFloat(refundPrices) + parseFloat($("#newTax").text())));
                
           $("#refundAmount").text(refundAmounts);
            $("#refundPrice").text(refundPrices);
            $("#refundMoney").text(refundPrices + (refundPrices * TotalTax / 100 ) );
        $("#refundMoneyxx").val($("#refundMoney").text());
            
        }
    </script>

</body>

</html>
