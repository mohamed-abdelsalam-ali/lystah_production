
@extends('layouts.posMaster')
@section('css')
@endsection
@section('content')
<div class="main-content">
    <div class="page-content ">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">Refund</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active">Client </li>
                            <li class="breadcrumb-item"><a href="home">Store</a></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">


                    <form action="/saveRefund" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="clientId" value=" {{ $client->id }}">
                        <div class=" row">
                            {{-- <h1 class="text-center text-decoration-underline">{{ $store->name }}</h1> --}}
                            <input type="hidden" name="storeId" id="storeId" value="{{ $store->id }}">
                            <div class="col-lg-12">
                                <div class="row d-none">
                                    <div class="col-12 table-responsive" style="overflow-x:auto;">
                                        بيانات العميل
                                        <table class="table">
                                            <tr>
                                                <td>
                                                    <label for="" class="fw-bold">الاسم :</label>
                                                    {{ $client->name }}
                                                </td>
                                                <td>
                                                    <label for="" class="fw-bold">رقم التليفون :</label>
                                                    {{ $client->tel01 }}
                                                </td>
                                                <td><label for="" class="fw-bold">رقم التليفون 1:</label>
                                                    {{ $client->tel02 }}</td>
                                            </tr>
                                            <tr>
                                                <td><label for="" class="fw-bold">رقم التليفون 2:</label>
                                                    {{ $client->tel03 }}</td>
                                                <td><label for="" class="fw-bold">الرقم القومي :</label>
                                                    {{ $client->national_no }}</td>
                                                <td><label for="" class="fw-bold">ملاحظات :</label>
                                                    {{ $client->notes }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"><label for="" class="fw-bold">العنوان
                                                        :</label>
                                                    {{ $client->address }}</td>
                                            </tr>
                                        </table>
                                    </div>

                                </div>
                                <div class="row fw-bold">
                                    <div class="col-lg-4 " style="border-left : solid">
                                        {{$invoice->presale_order_id}}
                                        <h3>بيانات الفاتورة قبل الإسترحاع</h3>
                                        <label for="" class="fw-bold">رقم الفاتورة :</label> <span class="fs-19"> <a
                                                href="/printInvoice/{{ $invoice->id }}">{{ $invoice->id }}</a></span><br />
                                        <label for="" class="fw-bold">إجمالي الفاتورة :</label> <span class="fs-19"
                                            id="baseSubtot"> {{ $invoice->price_without_tax - $refund_price }}</span><br />
                                                <hr>
                                        <label for="" class="fw-bold">إجمالي الضريبة :</label> <span class="fs-19">
                                            {{ $invoice->tax_amount - $refund_total_tax }}</span><br />
                                        <label for="" class="fw-bold"> الخصـــم :</label> <span class="fs-19">
                                            @if($invoice->presale_order_id)
                                            0 </span><br />
                                            @else
                                            {{ $invoice->discount - $refund_total_discount }}</span><br />
                                            @endif
                                        <label for="" class="fw-bold"> الاجمالي :</label> <span class="fs-19">
                                            @if($invoice->presale_order_id)
                                                {{ $invoice->price_without_tax - $refund_price - ( $refund_total_discount) + ($invoice->tax_amount - $refund_total_tax) }}</span><br />

                                            @else
                                                {{ $invoice->price_without_tax - $refund_price - ($invoice->discount - $refund_total_discount) + ($invoice->tax_amount - $refund_total_tax) }}</span><br />
                                            @endif
                                        <label for="" class="fw-bold"> المدفوع :</label>
                                            <span class="d-none">{{ $invoice->paied - ($refund_price + $refund_total_tax - $refund_total_discount) }}</span>
                                            <span class="fs-19">{{ $invoice->paied - $totalRefundPrices }}</span>
                                            <br />
                                            <h3 class="text-center">الضريبة</h3>
                                            <ul>
                                                @forelse ($taxes as $tax)
                                                    <li>{{ $tax->name }} : {{ $tax->value }}</li>
                                                     <input type="hidden" name="taxesList[]" value="{{ $tax->value }}">
                                                @empty
                                                    <li>لا يوجد ضريبة</li>
                                                    
                                                @endforelse

                                            </ul>

                                    </div>
                                    {{-- <div class="col-lg-4">
                                        <h3 class="text-center">الضريبة</h3>
                                        <ul>
                                            @foreach ($taxes as $tax)
                                                <li>{{ $tax->name }} : {{ $tax->value }}</li>
                                            @endforeach

                                        </ul>
                                    </div> --}}
                                    <div class="col-lg-4" style="border-right : solid">
                                        <h3>بيانات الفاتورة بعد  الإسترحاع</h3>

                                        <label for="" class="fw-bold">عدد القطع المرتجعه  : </label> <span
                                            id="refundAmount" class="fs-19"> 00</span><br />
                                        <label for="" class="fw-bold">إجمالى مبلغ الإسترجاعات : </label> <span
                                            id="refundPrice" class="fs-19"> 00</span><br />
                                        <hr>
                                        <label for="" class="fw-bold">  الإجمالى بدون ضريبة  بعد الإسترجاع: </label> <span
                                            id="subtotal" class="fs-19"> {{ $invoice->price_without_tax - $refund_price }}
                                        </span><br />
                                        <label for="" class="fw-bold"> الضريبة للقطع المترجعه : </label> <span
                                            id="newTax" class="fs-19"> 00</span><br />
                                        <input type="hidden" value="0" id="newTaxx" name="newTax">
                                        <label for="" class="fw-bold">الإجمالى  : </label> <span
                                            id="invoiceTotal" class="fs-19"> 00</span><br />
                                        <label for="" class="fw-bold"> الخصم للقطع  المرتجعة : </label> <span
                                            id="totaldisc" class="fs-19"> 00</span><br />
                                        <label for="" class="fw-bold">المبلغ المستحق رده : </label> <span
                                            class="text-danger display-6 fs-19" id="refundMoney"> 00</span><br />

                                        <input type="hidden" value="0" id="refundMoneyxx" name="totalRef">
                                        <label for="" class="fw-bold">طريقة الدفع : </label>
                                        <label for="" class=" fw-bold mb-3 px-4 py-1 text-bg-info"> فى حالة ( الكاش) يتم رد إجمالى المبلغ للعميل  </label>
                                        <select class="form-select" name="paymnet" id="paymnet">
                                            <option value="0">كاش</option>
                                            <option value="1">علي الرصيد</option>
                                        </select>
                                        <label for="" class="fw-bold "> الحساب: </label>
                                        <select class="form-control" name="paymentAcount" id="" required>
                                            <option value=""> اختر الحساب</option>
                                            @foreach ($bank_types as $bank)
                                                <option class="text-center" type-name="bank"
                                                    value="{{ $bank->accountant_number }}">{{ $bank->bank_name }}
                                                </option>
                                            @endforeach


                                            @foreach ($store_safe as $safe)
                                                <option class="text-center" type-name="store"
                                                    value="{{ $safe->safe_accountant_number }}">{{ $safe->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div id="paymentConatiner" class="border border-1 border-dark p-3 mt-2" style="display: none">
                                            <label for="" class="fw-bold "> المبلغ المردود :  </label>
                                            <input type="text" class="form-control " name="refundTotal1" id="refundTotal1" value="0">
                                            <label for="" class="fw-bold "> الباقي علي الحساب  : <span id="client_rassed">0</span> </label>

                                        </div>


                                    </div>
                                </div>
                                <hr>
                                <button class="btn btn-danger w-100"> حفـــــــــــــــظ </button>
                                <hr />
                                <input type="search" placeholder="بحــــث" name="" id="searchClientInv">
                                <div class="row mt-2">
                                    <div class="col-lg-12">
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
                                                    <th></th>

                                                    <th> مبلغ الخصم</th>
                                                    <th> مبلغ الضريبة</th>

                                                </tr>
                                            </thead>
                                            @php
                                                $m = 1;
                                            @endphp
                                            <input type="hidden" value="{{ $invoice->id }}" name="invoiceId">
                                            @foreach ($invoiceItems as $invoicex)
                                                @if ($invoicex->amount - $invoicex->refund_amount > 0)
                                                    <tr class="resinv">

                                                        <td>{{ $m++ }}</td>
                                                        @if ($invoicex->part_type_id == 1)
                                                            <input type="hidden" value="{{ $invoicex->part->id }}"
                                                                name="refunditem[]">

                                                            <input type="hidden" value="1" name="part_type_id[]">
                                                            <td>صنف </td>
                                                            <td>{{ $invoicex->part->name }}</td>
                                                        @elseif ($invoicex->part_type_id == 2)
                                                            <input type="hidden" value="{{ $invoicex->wheel->id }}"
                                                                name="refunditem[]">
                                                            <input type="hidden" value="2" name="part_type_id[]">
                                                            <td>كاوتش</td>
                                                            <td>{{ $invoicex->wheel->name }}</td>
                                                        @elseif ($invoicex->part_type_id == 6)
                                                            <input type="hidden" value="{{ $invoicex->kit->id }}"
                                                                name="refunditem[]">
                                                            <input type="hidden" value="6" name="part_type_id[]">
                                                            <td>كيت</td>
                                                            <td>{{ $invoicex->kit->name }}</td>
                                                        @endif
                                                        <input type="hidden" value="{{ $invoicex->source_id }}"
                                                            name="refunditemSource[]">
                                                        <input type="hidden" value="{{ $invoicex->status_id }}"
                                                            name="refunditemStatus[]">
                                                        <input type="hidden" value="{{ $invoicex->quality_id }}"
                                                            name="refunditemquality[]">
                                                        <input type="hidden" value="{{ $invoicex->sale_type }}"
                                                            name="refunditemSaletype[]">
                                                        <input type="hidden" value="{{ $invoicex->price[0]->price }}"
                                                            name="refunditemSalePrice[]">
                                                            <input type="hidden" value="{{ $invoicex->part->small_unit }}"
                                                            name="small_unit[]">
                                                            <input type="hidden" value="{{ $invoicex->part->big_unit }}"
                                                            name="big_unit[]">
                                                            <?php 
                                                            $ratioamount=getSmallUnit($invoicex->part->big_unit,$invoicex->part->small_unit);
                                                            ?>
                                                        <td class="itemAmount">
                                                        {{ ($invoicex->amount - $invoicex->refund_amount) /  $ratioamount  }} / {{$invoicex->part->bigunit->name}}</td>
                                                        <td class="itemPrice">
                                                        {{ $invoicex->price[0]->price  * $ratioamount}}
                                                        </td>
                                                        <td>{{ $invoicex->source->name_en }}</td>
                                                        <td>{{ $invoicex->status->name }}</td>
                                                        <td>{{ $invoicex->part_quality->name }}</td>

                                                        <td>
                                                            <input class="form-control RefAmount"
                                                                oninput="this.value = Math.abs(this.value)"
                                                                onkeyup="calcInvoiceTotal(this,{{ ($invoicex->amount -$invoicex->refund_amount) /  $ratioamount }},{{ $invoicex->price[0]->price * $ratioamount }} ,{{$ratioamount}},{{$invoicex->part->bigunit->name}})"
                                                                type="number" value="0" min=0 name="refundAmount[]"
                                                                id="">
                                                        </td>
                                                        <td>{{ $invoicex->refund_amount }}</td>
                                                        <td><input type="text" name="itemDiscount[]"
                                                                class="itemDiscount form-control" value="0" readonly></td>
                                                        <td><input type="text" name="itemTaxes[]"
                                                            class="itemTaxes form-control" value="0" readonly></td>

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

    </div>
</div>
@endsection
@section('js')
    <script>
            var partsDt;
            var TotalTax = 0;
            var salamSubtotal = {!! $invoice->price_without_tax !!};
            var salamTot = {!! $invoice->actual_price !!};
            var salampaid = {!! $invoice->paied !!};
            var salamSubtotal_refunded = {!! $refund_price !!};
            var InvoiceTaxes = {!! $taxes !!};
            var InvoiceTaxes_refunded = {!! $taxes !!};
            var presaleFlag = {!! $invoice->presale_order_id ? $invoice->presale_order_id : 0 !!};
            var Invoicediscount = 0;
            if(presaleFlag){
                Invoicediscount =0;
            }else{
                Invoicediscount = {!! $invoice->discount - $refund_total_discount  !!};

            }
            var InvoiceTax = {!! $invoice->tax_amount - $refund_total_tax !!};


            $(document).ready(function() {

            });


            InvoiceTaxes.forEach(function(tax) {
                TotalTax += tax.value;
            })
            $("#searchClientInv").keyup(function(e) {
                e.preventDefault();
                var searchValue = $(this).val();
                $('.resinv').hide();
                $('.resinv:contains("' + searchValue + '")').show();

            });
            var newDiscount = 0;

            function calcInvoiceTotal(el, oldAmount, itemPrice,ratioamount,bigunit_name) {
                var refundAmounts = 0;
                var refundPrices = 0;
                var refundAmount = $(el).val();

                var x1 = 0;
                var x2 = 0;
                refundAmount = parseFloat(refundAmount) * parseFloat(ratioamount);
                oldAmount =  parseFloat(oldAmount) * parseFloat(ratioamount);
                if (refundAmount <= oldAmount) {


                    $('.resinv').each(function(index, row) {
                        //   console.log(row);
                        var taxee = parseFloat($(row).find('.itemPrice').text()) * parseFloat($(row).find('.RefAmount')
                            .val());
                        refundAmounts += parseFloat($(row).find('.RefAmount').val());
                        refundPrices += parseFloat($(row).find('.itemPrice').text()) * parseFloat($(row).find(
                            '.RefAmount').val());

                        x1 += (parseFloat($(row).find('.itemAmount').text()) * parseFloat($(row).find('.itemPrice')
                    .text()));


                    })
                    newDiscount = 0;
                    newTax = 0;
                    $('.resinv').each(function(index, row) {
                        //   console.log(row);
                        newDiscount += (Invoicediscount / x1) * parseFloat($(row).find('.RefAmount').val()) * parseFloat(
                            $(row).find('.itemPrice').text());
                            $(row).find('.itemDiscount').val(Math.round((Invoicediscount / x1) * parseFloat($(row).find(
                            '.RefAmount').val()) * parseFloat($(row).find('.itemPrice').text())));



                            $(row).find('.itemTaxes').val(parseFloat($(row).find('.RefAmount').val()) * parseFloat($(row).find('.itemPrice').text()) * TotalTax /100 );


                    })




                } else {

                    $(el).val(0);


                }
                $("#subtotal").text(salamSubtotal - refundPrices - salamSubtotal_refunded);
                //  $("#newTax").text( parseFloat($("#subtotal").text()) * TotalTax / 100);
                $("#newTax").text(parseFloat(refundPrices) * TotalTax / 100);
                $("#newTaxx").val($("#newTax").text());

                // $("#invoiceTotal").text( Math.round(parseFloat($("#subtotal").text()) + parseFloat($("#newTax").text())));
                $("#invoiceTotal").text(Math.round(parseFloat(refundPrices) + parseFloat($("#newTax").text())));

                $("#refundAmount").text(refundAmounts +'/'+bigunit_name);
                $("#refundPrice").text(refundPrices);


                var xx1 = 0;
                $('.resinv').each(function(index, row) {

                    xx1 += parseFloat($(row).find('.itemDiscount').val());


                })


                $("#totaldisc").text(xx1);

                var diff = Math.round(parseFloat(salamTot)) - Invoicediscount - salampaid



                $("#refundMoney").text(Math.round(parseFloat(refundPrices + (refundPrices * TotalTax / 100) - newDiscount),2));


                $("#refundMoneyxx").val(Math.round(parseFloat($("#refundMoney").text())),2);


            }
            $("#paymnet").on('change', function(e){
                var paymentType = $(this).val();
                if(paymentType == 1){
                    $("#paymentConatiner").show()
                }else{
                    $("#paymentConatiner").hide()
                }
            })

            $("#refundTotal1").on('keyup', function(e){
                var refundTotal = parseFloat($(this).val());
                var refund_total = parseFloat($("#refundMoneyxx").val());
                if(refundTotal > refund_total){
                    $(this).val(0)
                    $("#client_rassed").text(0);
                    alert('المبلغ غير صحيح')
                }else{
                    $("#client_rassed").text(refund_total - refundTotal);

                }
            })

    </script>
@endsection



