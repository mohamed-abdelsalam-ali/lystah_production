@extends('layouts.print-equip_master')
@section('css')
    <style>
        th {
            text-align: center !important;
        }

        @media print {
            .togRow {
                display: none;
            }
        }
        .tawkelTble td{
            border: 0px !important;
        }
        .footerTable td{
            border: 0px !important;
            padding: 0px !important;
        }
        .text_val{
            color :blue ;
            text-decoration: underline;"
        }
    </style>
@endsection

@section('content')
    {{-- {{ $presaleFlag }} --}}
    <div class="tm_container tm_f15 tbl1" style="direction: rtl">
        <div class="tm_f15 tm_primary_color headlbl">   السيد قائد مــــرور / .............................................</div>
        <div class="tm_text_center tm_f20 tm_bold"> تحية طيبة وبعد </div>
        <div class="tm_mb10 tm_padd_10">
            نفيد سيادتكم علما بأننا قد بعنا ( السيارة / الجرار الزراعي / المعدة الزراعية) الموضح بياناته :-
        </div>

        <div>
            @if (count($invoiceItems) > 0)
                @foreach ($invoiceItems as $item)
                    @if($item->part_type_id == 3)
                        @php
                            $item_data = $item->tractor;
                            $motornum=$item->tractor->motornumber;
                            $shasehnum=$item->tractor->tractor_number;
                        @endphp
                    @elseif($item->part_type_id == 4)
                        @php
                            $item_data = $item->clark;
                        @endphp
                    @elseif($item->part_type_id == 5)
                        @php
                            $item_data = $item->equip;
                        @endphp
                    @endif
                @endforeach
            @endif
            <table class="tm_f16 tm_bold">
                <tr>
                    <td>ماركة</td>
                    <td  colspan="2" class="text_val">
                        @php
                           echo $item_data->series->model->brand->name ;
                        @endphp



                    </td>
                    <td>اللون</td>
                    <td>.........</td>
                    <td>عدد السلندرات </td>
                    <td  class="text_val"> @php
                        echo $item_data->discs ;
                    @endphp</td>
                    <td>لوحات رقم </td>
                    <td>.........</td>
                </tr>
                <tr>
                    <td  colspan="3"> ( ديزل / بنزين ) موديل</td>
                    <td  class="text_val">
                        @php
                            echo $item_data->series->model->name ;
                        @endphp
                    </td>
                    <td>رقم الشاسية</td>
                    <td colspan="2"  class="text_val">
                        @php
                            echo $shasehnum ;
                        @endphp
                    </td>
                    <td >رقم الموتور  </td>
                    <td colspan="2"  class="text_val">
                        @php
                            echo $motornum ;
                        @endphp
                    </td>

                </tr>
                <tr>
                    <td > إلى السيد /</td>
                    <td colspan="8"  class="text_val">{{$invoice->client->name}}</td>


                </tr>
                <tr>
                    <td> العنـــوان</td>
                    <td colspan="4"  class="text_val">{{$invoice->client->address}}</td>
                    <td>بطاقة رقم/ </td>
                    <td colspan="3"  class="text_val">{{$invoice->client->national_no}}</td>


                </tr>

            </table>


        </div>
        <div class=" tm_mt10 tm_pt25 tm_ tm_bold tm_f17"> وتم هذا البيع ( نقدا / بالتقسيط( ...... ) ولنا حق حفظ الملكية / ليس لنا حق حفظ الملكية )</div>
        <div class="tm_bold tm_f17">  برجاء اتخاذ اللزم نحو ترخيص ( السيارة / الجرار الزراعي / المعدة الزراعية) باسم المشترى بعالية</div>
        <div class="tm_text_center tm_f20 tm_bold tm_pt25">  ولسيادتكم جزيل الشكر </div>

        <table  class="tawkelTble tm_text_center tm_f20 tm_bold tm_pt25">
            <tr>
                <td></td>
                <td> توكيل رقم يعتمـــــد   </td>
            </tr>
            <tr>
                <td>...............................</td>
                <td>...............................</td>
            </tr>
            <tr>
                <td> استلمت الأصل</td>
                <td></td>
            </tr>
            <tr>
                <td>...............................</td>
                <td></td>
            </tr>


        </table>
        <div class="tm_invoice_seperator tm_gray_bg" style="min-height: 10px;"></div>
            <table class="footerTable tm_text_center">
                <tr>
                    <td colspan="2">
                        الإدارة / البحيرة - ايتاى البارود الغرب أمام هندسة الكهرباء <span>615 88 33 045 - 681 88 33 045 – 689 88 33 045 </span>

                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        www.alaml-egypt.com - sales@alamal-egypt.com
                    </td>
                </tr>
            </table>
         </div>
        <table class="table table-striped table-bordered cell-border tbl1">
            <thead style="background-color:#84B0CA ;" class="text-white">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Unit Price</th>
                    <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $m = 1;
                    $refund_sub_tot = 0;
                    $refund_tax = 0;
                    $refund_discount = 0;
                @endphp
                @foreach ($invoiceItems as $item)
                    <tr>
                        <th scope="row">{{ $m }}</th>
                        @if ($item->part_type_id == 1)
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

                        <td>{{ $item->amount - $item->refund_amount }}</td>
                        <td>{{ isset($item->price[0]->price) ? floatVal($item->price[0]->price) - floatVal($item->discount) + floatVal($item->over_price) : 0 }}
                        </td>
                        <td>{{ floatVal($item->amount - $item->refund_amount) * (floatVal($item->price[0]->price) - floatVal($item->discount) + floatVal($item->over_price)) }}
                        </td>
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
        <div class="tm_container tm_f15  tbl2 d-none" style="direction: rtl ;display: none">
            <table class="tm_f16 tm_bold">
                <tr>
                    <td>اقر أنا/</td>
                    <td colspan="7">........................................................................................................................................................................................   </td>

                </tr>
                <tr>
                    <td colspan="2">المقيم في /</td>
                    <td colspan="2">.......................................</td>
                    <td colspan="2">بطاقة رقم /  </td>
                    <td colspan="2">......................................</td>

                </tr>
                <tr>
                    <td > سيارة رقم /</td>
                    <td colspan="1">.......................</td>
                    <td> رخصة السيارة رقم /</td>
                    <td colspan="2">.......................</td>
                    <td> رخصة قيادة رقم / </td>
                    <td colspan="2">.......................</td>


                </tr>
                <tr>
                    <td colspan="8">
                        بأنني استلمت( المعدة الزراعية– الجرار الزراعي – السيارة – الكلرك )
                         ماركة/
                             ............................
                         موديل/
                          ..........................
                    </td>


                </tr>
                <tr>
                    <td >
                        رقم الشاسيه/
                    </td>
                    <td colspan="3">
                        ....................................................
                    </td>
                    <td >
                        رقم الماتور/
                    </td>
                    <td colspan="3">
                        ....................................................
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        وذلك لتوصيلها الى /
                    </td>
                    <td colspan="6">
                        ........................................................................................................
                    </td>
                </tr>
                <tr>
                    <td colspan="8">
                        أو من ينوب عنه بصفة رسمية وهذه المعدة السابق ذكر بياناتها اعله تعد بصفة رسمية امانة وأصبحت مسئول عنها مسئولية
                كاملة لحين توصيلها الى صاحبها المذكور اعله او من ينوب عنه .
                    </td>
                </tr>
            </table>

            <table  class="tawkelTble tm_text_center tm_f20 tm_bold tm_pt25">
                <tr>
                    <td></td>
                    <td> المقر بما فيه</td>
                </tr>
                <tr>
                    <td></td>
                    <td>...............................</td>
                </tr>
                <tr>
                    <td>  الشهود</td>
                    <td></td>
                </tr>
                <tr>
                    <td>...............................</td>
                    <td></td>
                </tr>
                <tr>
                    <td>...............................</td>
                    <td></td>
                </tr>
                <tr>
                    <td>...............................</td>
                    <td></td>
                </tr>


            </table>
            <div class="tm_invoice_seperator tm_gray_bg" style="min-height: 10px;"></div>
                <table class="footerTable tm_text_center">
                    <tr>
                        <td colspan="2">
                            الإدارة / البحيرة - ايتاى البارود الغرب أمام هندسة الكهرباء <span>615 88 33 045 - 681 88 33 045 – 689 88 33 045 </span>

                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            www.alaml-egypt.com - sales@alamal-egypt.com
                        </td>
                    </tr>
                </table>
             </div>
        </div>

        <table class="table table-striped table-bordered cell-border tbl2 d-none" style="display: none">
            <thead style="background-color:#84B0CA ;" class="text-white">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Section</th>
                    <!--<th scope="col">Amount</th>-->
                </tr>
            </thead>
            <tbody>
                @php
                    $m = 1;
                @endphp
                @foreach ($invoiceItems as $item)
                    <tr>
                        <th scope="row">{{ $m }}</th>
                        @if (isset($item->part))
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

                        <td>{{ $item->amount - $item->refund_amount }}</td>
                        <td>
                            @forelse($item->section as $key => $sec)
                                <li> {{ $sec->name }}</li>
                            @empty
                                <li> لا يوجد قسم </li>
                            @endforelse
                            <!--{{ isset($item->section[0]->name) ? $item->section[0]->name : 'لا يوجد توصيف' }}-->
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
        <table class="table table-striped table-bordered cell-border tm_text_center tbldesc"
            style="width:100%;direction: rtl">
            <thead>
                <tr>
                    <th> السعر</th>
                    <th>الضريبة</th>
                    <th>الخصم</th>
                    <th>الإجمالي</th>
                    <th>المدفوع</th>

                    <th>الباقى</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice->price_without_tax - $refund_sub_tot }}</td>
                    <td>{{ $invoice->tax_amount - $refund_tax }}</td>
                    <td>{{ $invoice->discount }}</td>
                    <td>{{ $invoice->price_without_tax - $refund_sub_tot + ($invoice->tax_amount - $refund_tax) - ($invoice->discount - $refund_discount) }}
                    </td>
                    <!--<td>{{ $invoice->paied }}</td>-->
                    <td>{{ $invoice->paied - ($refund_sub_tot + $refund_sub_tot - $refund_discount) }}</td>

                    <td>{{ $invoice->price_without_tax - $refund_sub_tot + ($invoice->tax_amount - $refund_tax) - ($invoice->paied - ($refund_sub_tot + $refund_sub_tot - $refund_discount)) - ($invoice->discount - $refund_discount) }}
                    </td>

                </tr>
            </tbody>
        </table>


        <div class="row togRow" style="color: #1a29c0e3;font-weight: bold;font-size: large;text-decoration: underline;">
            <div class="col-lg-10"></div>
            <div class="col-lg-2">
                <a href="#" onclick="toggleInvoice()"
                    class="border-0 btn btn-close-white btn-light text-capitalize w-100" data-mdb-ripple-color="dark"><i
                        class="fas fa-print text-primary"></i> <span id="buttonTitle"> إذن صرف</span></a>
            </div>
        </div>
    @endsection
    @section('js')
        <script>
            var invoice = {!! $invoice !!};

            var invoiceItems = {!! $invoiceItems !!};

            var flag = 0;
            $(document).ready(function() {
                $("#customizerclose-btn").click();
                $('.papertitleCls').html('فاتورة ');
                $('#buttonTitle').text('  إقرار إستلام');

                //   console.log(invoiceItems);
                window.print();
                flag = 1;
            });
            window.onafterprint = function() {

                console.log("Printing completed...");
                if (flag == 1) {
                    flag = 0;
                    $('.papertitleCls').html(' إقرار إستلام ');
                    $('#buttonTitle').text('فاتورة ');
                    $('.tbl1').hide();
                    $('.tbldesc').hide();
                    $('.tbl2').show()

                    setTimeout(function() {

                        window.print();
                    }, 1000);
                }


            }

            function toggleInvoice() {
                if (flag == 1) {
                    flag = 0;
                    $('.papertitleCls').html(' إقرار إستلام ');

                    $('#buttonTitle').text('فاتورة ');
                    $('.tbl1').hide();
                    $('.tbldesc').hide();
                    $('.tbl2').show()

                    // setTimeout(function() {

                    //     window.print();
                    // }, 1000);
                } else {
                    flag = 1;
                    $('.papertitleCls').html('فاتورة ');
                    $('#buttonTitle').text(' إقرار إستلام ');
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
