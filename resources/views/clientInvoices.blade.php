@extends('layouts.master')
@section('css')



    <style>
        /*.modal-content{*/
        /*    width: 50vw !important;*/
        /*}*/

        .dataTables_processing {
            z-index: 9999 !important;
            width: 100vw !important;
            height: auto !important;
            top: 10% !important;
            left: 0 !important;
            position: fixed !important;
        }

        .pointer {
            cursor: pointer;
        }




        .upload__box {
            padding: 40px;
        }

        .upload__inputfile {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        .upload__inputfilePartEdit {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        .upload__btn {
            display: inline-block;
            font-weight: 600;
            color: #fff;
            text-align: center;
            min-width: 116px;
            padding: 5px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid;
            background-color: #3d78e3; //#3d78e3
            border-color: #3d78e3;
            border-radius: 10px;
            line-height: 26px;
            font-size: 14px;
        }

        .upload__btn:hover {
            background-color: unset;
            color: #3d78e3;
            transition: all 0.3s ease;
        }

        .upload__btn-box {
            margin-bottom: 10px;
        }

        .upload__img-wrap {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .upload__img-wrapPart {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .upload__img-wrapPartEdit {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .upload__img-box {
            width: 120px;
            padding: 0 10px;
            margin-bottom: 12px;
        }

        .upload__img-close {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: rgba(0, 0, 0, 0.5);
            position: absolute;
            top: 10px;
            right: 10px;
            text-align: center;
            line-height: 24px;
            z-index: 1;
            cursor: pointer;
        }

        .upload__img-close:after {
            content: "✖";
            font-size: 14px;
            color: white;
        }

        .img-bg {
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            position: relative;
            padding-bottom: 100%;
        }



        /* * {

                font-family: , "Times New Roman", Times, serif;
            } */
        /* .{
                font-family: , "Times New Roman", Times, serif;
            } */
    </style>
@endsection
@section('title')
    فواتير العملاء
@stop


@section('content')



    <div class="main-content ">
        <div class="page-content">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                            <h4 class="mb-sm-0">Client Invoice</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item active">Clients</li>
                                    <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card row">
                    <h1 class="text-center text-decoration-underline">{{ $store->name }}</h1>
                    <input type="hidden" name="storeId" id="storeId" value="{{ $store->id }}">


                    <div class="card-body fs-18 fw-bold">
                        <div class="row">
                            <div class="col-12 table-responsive" style="overflow-x:auto;">
                                <table class="table">
                                    <tr>
                                        <td>
                                            <label for="" class="text-success">الاسم :</label>
                                            {{ $client->name }}
                                        </td>
                                        <td>
                                            <label for="" class="text-success">رقم التليفون :</label>
                                            {{ $client->tel01 }}
                                        </td>
                                        <td><label for="" class="text-success">رقم التليفون 1:</label>
                                            {{ $client->tel02 }}</td>
                                    </tr>
                                    <tr>
                                        <td><label for="" class="text-success">رقم التليفون 2:</label>
                                            {{ $client->tel03 }}</td>
                                        <td><label for="" class="text-success">الرقم القومي :</label>
                                            {{ $client->national_no }}</td>
                                        <td><label for="" class="text-success">ملاحظات :</label>
                                            {{ $client->notes }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><label for="" class="text-success">العنوان :</label>
                                            {{ $client->address }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col text-end">
                                <h3><span>إجمالي : </span> <span class="p-1 rounded text-bg-danger">
                                        {{ isset($rassedd) ? $rassedd['message'] : '-' }}
                                        {{ isset($rassedd) ? $rassedd['rassed'] : '0' }} </span>

                                </h3>
                                {{-- <h4>رصيد العميل : <span>{{ $client->client_raseed }}</span></h4>
                                    <h4>رصيده كمورد : <span>{{ $as_sup_madunia }}</span> </h4> --}}
                            </div>
                            <div class="col text-end">
                                <button data-bs-toggle="modal" data-bs-target="#madMdl" class="btn btn-success w-50">سند قبض
                                    </button>

                            </div>
                            <div class="col text-star">
                                <button data-bs-toggle="modal" data-bs-target="#madMd2" class="btn btn-danger w-50">سند صرف
                                </button>

                            </div>

                        </div>
                        <hr>
                        <!--<h3>إجمالي مديونية الخدمات <span>{{ $servicesMad }}</span> </h3>-->
                        <hr />
                        <input type="search" placeholder="بحــــث" name="" id="searchClientInv">
                        <h3>فواتير البيع</h3>
                        <div class="row mt-2">
                            <div class="col">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>م</th>
                                            <th>التاريخ</th>
                                            <th>المخزن</th>
                                            <th>إجمالي الفاتورة</th>
                                            <th>المدفوع</th>
                                            <th>الخصم</th>
                                            <th>إجمالي الضريبة</th>
                                            <th> المستحق ردة للعميل</th>
                                            <th> مدفوع على المرتجع</th>
                                            <th> عدد القطع</th>
                                            <th> </th>

                                        </tr>
                                    </thead>
                                    @php
                                        $m = 1;
                                    @endphp
                                    @foreach ($invoices as $invoice)
                                        <tr class="resinv">
                                            <td>{{ $m++ }}</td>
                                            <td class="">{{ date('d-m-Y', strtotime($invoice->date)) }}</td>
                                            @if($invoice->presale_order_id)
                                                <td class="text-danger">{{ $invoice->store->name }}</td>
                                            @else
                                                <td>{{ $invoice->store->name }}</td>
                                            @endif
                                            
                                            <td>{{ $invoice->actual_price }}</td>
                                            <td>{{ $invoice->paied }}</td>
                                             @if($invoice->presale_order_id)
                                                <td title="{{ $invoice->discount }}" >0</td>
                                            @else
                                                <td>{{ $invoice->discount }}</td>
                                            @endif
                                            
                                            <td>{{ $invoice->tax_amount }}</td>
                                            <!--<td>{{ $invoice->refund_invoices->sum('item_price') + $invoice->refund_invoices->sum('r_tax') }} </td>-->
                                            <td>{{count($invoice->refund_invoice_payment) > 0 ?  $invoice->refund_invoice_payment->sum('total_paied') : 0}}</td>
                                            <td>{{count($invoice->refund_invoice_payment)>0 ?  $invoice->refund_invoice_payment->sum('paied') : 0}} </td>
                                           
                                            <td>{{ $invoice->invoice_items->count() }}</td>
                                            <td><a href="/printInvoice/{{ $invoice->id }}">عرض</a>
                                                <a class="text-danger" href="/refund/{{ $invoice->id }}/{{ $store->id }}">إسترجاع</a>
                                            </td>
                                            <td class="d-none">
                                                <!--{{ ($invoice->actual_price  - $invoice->refund_invoices->sum('item_price') + $invoice->refund_invoices->sum('r_tax'))-($invoice->discount - $invoice->refund_invoices->sum('r_discount'))-$invoice->paied }}-->
                                               {{ ($invoice->actual_price  - $invoice->refund_invoices->sum('item_price') + $invoice->refund_invoices->sum('r_tax'))-($invoice->discount - $invoice->refund_invoices->sum('r_discount'))-$invoice->paied }}

                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        @if ($as_sup_madunia)
                            <h3>فواتير الشراء</h3>
                            <div class="row mt-2">
                                <div class="col">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>م</th>
                                                <th>التاريخ</th>
                                                <th>المورد</th>
                                                <th>إجمالي الفاتورة</th>
                                                <th>المدفوع</th>
                                                <th>سعر العملة</th>
                                                <th>طريقة الدفع</th>
                                                <th>إجمالي الضريبة</th>
                                                <th> عدد القطع</th>
                                                <th> </th>

                                            </tr>
                                        </thead>
                                        @php
                                            $m = 1;
                                        @endphp
                                        @foreach ($supplierData->order_suppliers as $invoice)
                                            <tr class="resinv">
                                                <td>{{ $m++ }}</td>
                                                <td class="">{{ date('d-m-Y', strtotime($invoice->confirmation_date)) }}
                                                </td>
                                                <td>{{ $invoice->supplier->name }}</td>
                                                <td>{{ $invoice->total_price }}/{{ $invoice->currency_type->name }}</td>
                                                <td>{{ $invoice->paied }}</td>
                                                <td>{{ $invoice->currency_value }}</td>
                                                <td>{{ $invoice->payment == 1 ? 'كاش' : 'علي الرصيد' }}</td>
                                                <td>{{ $invoice->tax }}</td>
                                                <td>{{ $invoice->replyorders->count() }}</td>
                                                <td><a href="/printBuyInvoice/{{ $invoice->transaction_id }}">عرض</a>
                                                    {{-- <a class="text-danger" href="/refund/{{ $invoice->id }}">إسترجاع</a> --}}
                                                </td>

                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="madMdl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="madMdlLabel" aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="madMdlLabel">ســداد المديونية</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- <input type="search" class="form-control mb-2" name="" id="searchSectionTxt"> --}}

                        <form action="/saveMadyonea" method="POST">
                            @csrf
                            @method('POST')
                            <h3><span>إجمالي : </span> <span class="p-1 rounded text-bg-danger">
                                {{ isset($rassedd) ? $rassedd['message'] : '-' }}</span>
                                <span class="p-1 rounded text-bg-danger" name="total_amount" id="total_amount"> {{ isset($rassedd) ? $rassedd['rassed'] : '0' }} </span>
                            </h3>
                            <hr>
                            <div class="row">
                                <div class="col-lg-1">
                                    <label>المبلغ</label>
                                </div>
                                <div class="col-lg-2">
                                    <input type="hidden" name="store_id" value="{{ $store->id }}">
                                    <input type="hidden" name="client_id" value="{{ $client->id }}">
                                    <input type="hidden" id="payment_type" name="payment_type" value="">
                                    <input type="number" step=any class="form-control" name="paied" id="paied" min="0" required>


                                </div>
                                <div class="col-lg-2">
                                    <span>  وسيلة الدفع</span>
                                </div>
                                <div class="col-lg-3 ">

                                    <select class="form-select mt-0" name="payment" id="paymentslect" required>
                                        {{-- <option value="0">كاش</option>
                                    <option value="1">تحويل بنكي</option>
                                    <option value="2"> علي الحساب</option> --}}
                                    <option  selected disabled value="">إختر البنك </option>
                                        @foreach ($bank_types as $bank)
                                            <option class="text-center" type-name="bank"
                                                value="{{ $bank->accountant_number }}">{{ $bank->bank_name }} </option>
                                        @endforeach


                                        @foreach ($store_safe as $safe)
                                            <option class="text-center" type-name="store"
                                                value="{{ $safe->safe_accountant_number }}">{{ $safe->name }} </option>
                                        @endforeach

                                    </select>
                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-1">
                                    <label>خصم </label>
                                </div>
                                <div class="col-lg-2">
                                    <input type="number" step=any class="form-control"  name="discount" id="discount" value="0" min="0">
                                </div>
                                <div class="col-lg-2">
                                    <label>ملاحظات </label>
                                </div>
                                <div class="col-lg-3">
                                    <textarea class="form-control"   name="noteabd" id="" cols="23" rows="2"></textarea>
                                </div>
                                <div class="col-3">
                                    <button type="submit" class="btn btn-primary btn-block">حفظ</button>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="row">
                            <div class="col">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>مسلسل</th>
                                            <th>التاريخ</th>
                                            <th>المدفوع</th>
                                            <th>خصم مسموح</th>
                                            <th>الحساب</th>
                                            <th>ملاحظات</th>
                                            <th>طباعة</th>
                                        </tr>
                                    </thead>
                                    @foreach ($invoiceMad as $mad)
                                        <tr>
                                            <td><a href="/print_sanad_abd/client/{{$mad->id}}">{{$mad->id}}</a></td>
                                            <td>{{ date('d-m-Y', strtotime($mad->date)) }}</td>
                                            <td>{{ $mad->paied }}</td>
                                            <td>{{ $mad->discount }}</td>
                                            <td>{{ isset($mad->payment) ? $mad->payment->name : '-' }}</td>
                                            <td>{{ $mad->note }}</td>
                                            <td><a href="/print_sanad_abd/client/{{$mad->id}}"><i class="mdi mdi-printer"></i></a></td>

                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary">save</button> --}}
                </div>
            </div>
        </div>

    </div>
    <div class="modal fade" id="madMd2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="madMd2Label" aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="madMd2Label">سند صرف </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- <input type="search" class="form-control mb-2" name="" id="searchSectionTxt"> --}}
                    <h3><span>إجمالي : </span> <span class="p-1 rounded text-bg-danger">
                            {{ isset($rassedd) ? $rassedd['message'] : '-' }}
                            {{ isset($rassedd) ? $rassedd['rassed'] : '0' }} </span> </h3>

                        <hr>
                        <form action="/save_sanadsarf" method="POST">
                            @csrf
                            @method('POST')
                            <div class="row">
                                <div class="col-lg-1">
                                    <label>المبلغ</label>
                                </div>
                                <div class="col-lg-2">
                                    <input type="hidden" name="store_id" value="{{ $store->id }}">
                                    <input type="hidden" name="client_id" value="{{ $client->id }}">
                                    <input type="number" step=any class="form-control" name="paied" id="sarfpaied" required>
                                    <input type="hidden" id="payment_type1" name="payment_type">
                                    <input type="hidden" name="currency_id" value="400">

                                </div>
                                <div class="col-lg-2">
                                    <span> وسيلة الدفع </span>
                                </div>
                                <div class="col-lg-3 ">

                                    <select class="form-select mt-0" name="payment" id="paymentslect1" required>
                                        <option class="text-center" selected disabled type-name="bank"
                                        value="">اختر </option>
                                        @foreach ($bank_types as $bank)
                                            <option class="text-center" type-name="bank"
                                                value="{{ $bank->accountant_number }}">{{ $bank->bank_name }} </option>
                                        @endforeach


                                        @foreach ($store_safe as $safe)
                                            <option class="text-center" type-name="store"
                                                value="{{ $safe->safe_accountant_number }}">{{ $safe->name }} </option>
                                        @endforeach

                                    </select>
                                    <span id="Safetotal">00.00</span>
                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                {{-- <div class="col-lg-1">
                                    <label>خصم </label>
                                </div>
                                <div class="col-lg-2">
                                    <input type="number" step=any class="form-control" readonly name="discount1" value="0" min="0">
                                </div> --}}
                                <div class="col-lg-1">
                                    <label>ملاحظات </label>
                                </div>
                                <div class="col-lg-7">
                                    <textarea class="form-control"   name="notesarf" id="" cols="23" rows="2"></textarea>
                                </div>
                                <div class="col-3">
                                    <button type="submit" class="btn btn-primary btn-block">حفظ</button>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="row">
                            <div class="col">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>مسلسل</th>
                                            <th>التاريخ</th>
                                            <th>المدفوع</th>
                                            <th>الحساب</th>
                                            <th>ملاحظات</th>
                                            <th>طباعة</th>
                                        </tr>
                                    </thead>
                                    @forelse($sanadSarf as $mad)
                                        <tr>
                                            <td><a href="/print_sanad_sarf/client/{{$mad->id}}">{{$mad->id}}</a></td>
                                            <td>{{ date('d-m-Y', strtotime($mad->date)) }}</td>
                                            <td>{{ $mad->paied }}</td>
                                            <td>{{ isset($mad->payment) ? $mad->payment->name : '-' }}</td>
                                            <td>{{ $mad->note }}</td>

                                            <td><a href="/print_sanad_sarf/client/{{$mad->id}}">
                                                <i class="mdi mdi-printer"></i>
                                            </a></td>
                                        </tr>
                                    @empty

                                    @endforelse
                                </table>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary">save</button> --}}
                </div>
            </div>
        </div>

    </div>
    @endsection

    @section('js')
        <script>
            var partsDt;
             $('#discount').on('keyup',function(){
                var discount_val=parseFloat($(this).val());
                var paied=parseFloat($('#paied').val());
                var total_amount=parseFloat($('#total_amount').text());
                if(total_amount >= paied + discount_val){

                }else{
                    $(this).val(0);
                }


            });

            $(document).ready(function() {
                    $('#paymentslect').select2({
                    tags: true ,
                    dropdownParent: $("#madMdl")
                });
                $('#paymentslect1').select2({
                    tags: true ,
                    dropdownParent: $("#madMd2")
                });
            });



            $("#searchClientInv").keyup(function(e) {
                e.preventDefault();
                var searchValue = $(this).val();
                $('.resinv').hide();
                $('.resinv:contains("' + searchValue + '")').show();

            });


            $("#paymentslect1").change(function(){


                var safeId = $(this).val();
                var safetype = $("#paymentslect1 option:selected ").attr('type-name');
                $("#payment_type1").val(safetype);




                $.ajax({
                    type: "get",
                    url: "/getRassed/"+safeId+'/'+safetype,
                    success: function(response) {
                        console.log(response);
                        var paied = $("#paymentslect1").closest('form').find('input[name="paied"]').val()
                        if(response==""){
                            $("#Safetotal").text(0);
                            $("#paymentslect1").closest('form').find('button').hide();

                        }else{
                        $("#Safetotal").text(response)
                        if(response > 0 &&  paied <= response){
                            $("#paymentslect1").closest('form').find('button').show();
                        }else{
                            $("#paymentslect1").closest('form').find('button').hide();
                        }
                        }
                    }
                });



            })
            $("#paymentslect").change(function(){
                var safeId = $(this).val();
                var safetype = $("#paymentslect option:selected ").attr('type-name');
                $("#payment_type").val(safetype);

            })

            $("#sarfpaied").keyup(function(e){
                var paied = $(this).val();
                var rassed = $(this).closest('form').find('select').next('span').text();

                if(parseFloat(paied) >= parseFloat(rassed)){
                    $(this).val(0);
                    e.preventDefault();
                }

            })
        </script>
    @endsection
