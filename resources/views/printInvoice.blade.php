@extends('layouts.print-master')
@section('css')
    <style>
        th{
            text-align: center !important;
        }

        @media print {
            .togRow{
                display: none;
            }
        }
    </style>
@endsection

@section('content')

{{ $presaleFlag }}
<div class="tm_f14 tm_text_center tm_semi_bold tm_border"> <span> رقم الفاتورة</span> {{ $invoice->id }}</div>
<table class="table table-striped table-bordered cell-border tbl1" style="direction: rtl;">
    <thead style="background-color:#84B0CA ;" >
        <tr>
            <th>مسلسل</th>
            <th>الصنف</th>
            <th>الكمية</th>
            <th>سعر الوحدة</th>
            <th>الإجمالى</th>
        </tr>
    </thead>
    <tbody style="text-align: center;">
        @php
            $m = 1;
            $refund_sub_tot=0;
            $refund_tax=0;
            $refund_discount = 0;
        @endphp
        @foreach ($invoiceItems as $item )

            <tr>
                <th scope="row">{{ $m }}</th>
                @if($item->part_type_id == 1)
                    <td>{{ $item->part->name }}</td>
                @elseif ($item->part_type_id == 6)
                    <td>{{ $item->kit->name }}</td>
                @elseif ($item->part_type_id == 2)
                    <td>{{ $item->wheel->name }}</td>
                @elseif ($item->part_type_id == 3)
                    <td>{{ $item->tractor->name }}</td>
                @elseif ($item->part_type_id == 4)
                    <td>{{ $item->clark->name }}</td>
                @elseif ($item->part_type_id == 5)
                    <td>{{ $item->equip->name }}</td>
                @endif
                <?php 
                 $ratioamount=getSmallUnit($item->unit_id,$item->part->small_unit);
                 ?>
               <td>{{ floatVal($item->amount - $item->refund_amount) >= 0 ? floatVal($item->amount - $item->refund_amount) / floatVal($ratioamount) : 0   }}  / {{ $item->unit->name }}</td>
               @if($presaleFlag)
                    <td>{{ count($item->price)>0 ? floatVal($item->price[0]->price) * floatVal($ratioamount) : 0 }}</td>
                    <td>{{ floatVal($item->amount- $item->refund_amount)/ floatVal($ratioamount ) * ( count($item->price)>0 ? floatVal($item->price[0]->price) * floatVal($ratioamount ) : 0 ) }}</td>
                @else
                    <td>{{ isset($item->price[0]->price) ? ( floatVal($item->price[0]->price) * floatVal($ratioamount) - floatVal($item->discount) + floatVal($item->over_price))  : 0  }}</td>
                    <td>{{ floatVal($item->amount- $item->refund_amount) / floatVal($ratioamount )* ( floatVal($item->price[0]->price)* floatVal($ratioamount) - floatVal($item->discount) + floatVal($item->over_price)) }}</td>
                @endif


            </tr>
            @php
            $m++;
             $refund_sub_tot += floatval($item->refund_price);
            $refund_tax += floatval($item->refund_total_tax);
            $refund_discount += floatval($item->refund_total_discount);
            @endphp
        @endforeach
    </tbody>

</table>
<!--////////////////////////////////////////////////////////////////////////////////-->


<table class="table table-striped table-bordered cell-border tbl2 d-none" style="display: none">
    <thead style="background-color:#84B0CA ;" class="text-white">
        <tr>
            <th>مسلسل</th>
            <th>الصنف</th>
            <th>الكمية</th>
            <th>القسم</th>
        </tr>
    </thead>
    <tbody style="text-align: center;">
        @php
            $m = 1;
        @endphp
        @foreach ($invoiceItems as $item )

            <tr>
                <th scope="row">{{ $m }}</th>
                @if(isset($item->part))
                    <td>{{ $item->part->name }}</td>
                @elseif (isset($item->kit))
                    <td>{{ $item->kit->name }}</td>
                @elseif (isset($item->wheel))
                    <td>{{ $item->wheel->name }}</td>
                @elseif (isset($item->tractor))
                    <td>{{ $item->tractor->name }}</td>
                @elseif (isset($item->clark))
                    <td>{{ $item->clark->name }}</td>
                @elseif (isset($item->equip))
                    <td>{{ $item->equip->name }}</td>
                @endif

                <td>{{ floatVal($item->amount - $item->refund_amount) >= 0 ? floatVal( $item->amount - $item->refund_amount)/floatVal($ratioamount) : 0   }}  / {{ $item->unit->name }}</td>
                <td>
                    @if( count($item->section_out)>0 )
                      @forelse($item->section_out as $key => $sec)
                           <li> {{ $sec->store_structure->name }}</li>
                        @empty
                        <li> لا يوجد قسم </li>
                        @endforelse
                   @else
                       
                        <li> لا يوجد قسم </li>
                        
                    @endif
                    <!--{{ isset($item->section[0]->name) ? $item->section[0]->name : 'لا يوجد توصيف'  }}-->
                </td>
                <!--<td>{{ $item->amount * isset($item->price[0]->price) ? $item->price[0]->price : 0 }}</td>-->
            </tr>
            @php
            $m++;
            @endphp
        @endforeach
    </tbody>

</table>
<br>
<hr>
<br>
<table class="table table-striped table-bordered cell-border tm_text_center tbldesc" style="width:100%;direction: rtl">
    <thead>
        <tr>
            <th> السعر</th>
            <th>الضريبة</th>
            <th>الخصم</th>
            <th>الإجمالي</th>
            <th>المدفوع</th>

            <!--<th>الباقى</th>-->

        </tr>
    </thead>
    <tbody>
          {{-- <td>
                {{ $invoice->preSaleOrder }}
            </td> --}}

        @if($presaleFlag)
            <tr>
                <td>{{ $invoice->preSaleOrder[0]->subtotal - $invoice->refund_price_total_paied - $invoice->refund_price_tax_total - $invoice->refund_price_discount_total  }}</td>
                <td>{{ $invoice->preSaleOrder[0]->tax  - $invoice->refund_price_tax_total }}</td>
                <td>{{(($presaleFlag)==1 ?  0: ($invoice->discount - $invoice->refund_price_discount_total)) }}</td>
                <td>{{ ($invoice->preSaleOrder[0]->total - $invoice->refund_price_total_paied - $invoice->refund_price_tax_total - $invoice->refund_price_discount_total) + ( $invoice->refund_price_tax_total) - (($presaleFlag)==1 ?  0: ($invoice->discount - $invoice->refund_price_discount_total)) }}</td>
                @if ($invoice->paied > 0)
                    <td>{{ $invoice->paied - $invoice->refund_price_total }}</td>
                @else
                    <td>{{ $invoice->paied }}</td>
                @endif
            </tr>
        @else
            <tr>
                <td>{{ $invoice->price_without_tax - $invoice->refund_price_total_paied - $invoice->refund_price_tax_total - $invoice->refund_price_discount_total  }}</td>
                <td>{{ $invoice->tax_amount - $invoice->refund_price_tax_total }}</td>
                <td>{{(($presaleFlag)==1 ?  0: ($invoice->discount - $invoice->refund_price_discount_total)) }}</td>
                <td>{{ ($invoice->price_without_tax - $invoice->refund_price_total_paied - $invoice->refund_price_tax_total - $invoice->refund_price_discount_total) + ($invoice->tax_amount - $invoice->refund_price_tax_total) - (($presaleFlag)==1 ?  0: ($invoice->discount - $invoice->refund_price_discount_total)) }}</td>
                @if ($invoice->paied > 0)
                    <td>{{ $invoice->paied - $invoice->refund_price_total }}</td>
                @else
                    <td>{{ $invoice->paied }}</td>
                @endif
            </tr>
        @endif

    </tbody>
</table>

<div style="direction:rtl" class="tm_invoice_info_2 tm_left_auto tm_width_4 w-100">

    {{ isset($invoice->client->betaa_darebia) ? "ب . ض : " .$invoice->client->betaa_darebia : 'لا يوجد بطاقة ضريبية' }}
    <br>
    {{ isset($invoice->client->segl_togary) ? "س . ت : " .$invoice->client->segl_togary : 'لا يوجد سجل تجاري' }}
    
</div>


            <div class="row togRow" style="color: #1a29c0e3;font-weight: bold;font-size: large;text-decoration: underline;">
                <div class="col-lg-10"></div>
                <div class="col-lg-2">
                    <a href="#" onclick="toggleInvoice()" class="border-0 btn btn-close-white btn-light text-capitalize w-100" data-mdb-ripple-color="dark"><i
                        class="fas fa-print text-primary"></i>  <span id="buttonTitle"> إذن صرف</span></a>
                </div>
            </div>



@endsection
@section('js')
    <script>
     var invoice= {!! $invoice!!};
    $('#datenowLbl').text(invoice.date.split("T")[0]);
      var invoiceItems= {!! $invoiceItems!!};

      var flag = 0 ;
           $(document).ready(function(){
                $("#customizerclose-btn").click();
                $('.papertitleCls').html('فاتورة بيع');
                $('#buttonTitle').text(' إذن إخراج');

                localStorage.removeItem('cardClientId');
                localStorage.removeItem('cardOptions');
                localStorage.removeItem('cardsaleType');
            //   console.log(invoiceItems);
                window.print();
                flag = 1;
            });
         window.onafterprint = function(){

                  console.log("Printing completed...");
                  if(flag == 1){
                      flag = 0;
                    $('.papertitleCls').html(' إذن إخراج');
                    $('#buttonTitle').text('فاتورة بيع');
                      $('.tbl1').hide();
                      $('.tbldesc').hide();
                        $('.tbl2').show()

                        setTimeout(function() {

                            window.print();
                        }, 1000);
                    }


             }
        function toggleInvoice(){
            if(flag == 1){
                      flag = 0;
                    $('.papertitleCls').html(' إذن إخراج');

                    $('#buttonTitle').text('فاتورة بيع');
                      $('.tbl1').hide();
                      $('.tbldesc').hide();
                        $('.tbl2').show()

                        // setTimeout(function() {

                        //     window.print();
                        // }, 1000);
                    }else{
                         flag = 1;
                    $('.papertitleCls').html('فاتورة بيع');
                    $('#buttonTitle').text(' إذن إخراج');
                      $('.tbl1').show();
                      $('.tbldesc').show();
                        $('.tbl2').hide()

                        // setTimeout(function() {

                        //     window.print();
                        // }, 1000);
                    }
        }
    </script>

@endsection

