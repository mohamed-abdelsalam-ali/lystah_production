@extends('layouts.master')
@section('css')
    <style>
        * {
            font-family: arabic;
        }

        .page-body label {
            font-size: 20px;
            /* font-weight: bold; */
            text-decoration: underline;
        }

        .page-body span {
            font-size: 15px;
        }

        table {
            font-size: 20px;

        }
    </style>

@endsection
@section('title')

@stop


@section('content')

    <!-- Page header -->

    <!-- Page body -->
    <div class="main-content">
        <div class="page-content">
            <h1 class="text-center text-info">كشف حساب عميل</h1>
            <div class="card text-center  text-nowrap">
                <div class="row text-center border border-blue p-2 m-2 rounded">
                    <div class="col-5">
                        <h1>مجموعة شركات عمارة</h1>
                        <h4> معدات زراعية </h4>
                        <h4>قطع غيار - كاوتش </h4>
                        <h4> جرارات - كلاركات - كيت</h4>
                        <h4>خدمات صيانة</h4>
                        <h5>الرقم الضريبي / <span>60032000200003</span></h5>
                    </div>
                    <div class="col-2">
                        <img style="width: 120px;height: 120px" class="rounded-circle"
                            src="{{ asset('brand_images/20230705151210-DFAM-DF.png') }}" alt="">
                    </div>
                    <div class="col-5">
                        <h2>ُEmara Group Companies </h2>
                        <h4> Agriculture Equipments</h4>
                        <h4>Spar Parts - Wheels </h4>
                        <h4> Tractors - Clarcks & Kits</h4>
                        <h4> Mechanical Services .</h4>
                        <h5> VAT. / <span>60032000200003</span></h5>
                    </div>
                </div>


                <div class="row ">
                    <div class="col-6">
                        @if ($start_date && $start_date != 'null')
                            <label class="">من </label> :
                            <span>{{ $start_date }}</span><br>
                        @endif
                        @if ($end_date && $end_date != 'null')
                            <label class="">إلى </label> :
                            <span>{{ $end_date }}</span>
                        @endif


                    </div>
                    {{-- <div class="col-4">

                    </div> --}}
                    <div class="col-6">
                        <label class="">التاريخ</label> :
                        <span id="hegri"></span><br>
                        <label class="">الموافق</label> :
                        <span id="3arabi"></span>

                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h2>كشف حساب عميل رقم (<span>{{ $client_data[0]->id }}</span>)</h2>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-6">
                        <label class="">اسم العميل</label> :
                        <span>{{ $client_data[0]->name }}</span>

                    </div>
                    <div class="col-6">
                        <label class=""> الموقع</label> :
                        <span>البحيرة </span>

                    </div>
                </div>

                <div class="row p-2">

                    <div class="col-12 m-0">
                        <div class="table-responsive p-2">
                            <table class="table  table-vcenter  datatable text-center table-bordered" id="itemtbl">
                                <thead>
                                    <tr>
                                        <th style="font-weight: bold">م</th>
                                        <th>البيان</th>
                                        <th>المخزن</th>
                                        <th>الفاتورة</th>
                                        <th>التاريخ</th>
                                        <th>دائن</th>
                                        <th>مدين</th>
                                        <th>الرصيد </th>


                                    </tr>
                                </thead>
                                <tbody id="client_hessap_tbl">


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class=" row">
                    <div class="col-4 ">
                        <label class="">المشرف</label> <br>
                        <span> م / أحمد عمارة </span>
                    </div>
                    <div class="col-4 ">
                        <label class="">المحاسب</label> <br>
                        <span>م / رمضان حسين </span>
                    </div>
                    <div class="col-4 ">
                        <label class="">المدير العام</label> <br>
                        <span> م / يوسف عمارة</span>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12">
                        <h1>
                            مجوعة شركات عمارة الرئيسي - البحيرة - تليفون / 01112002966 </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- this to check variable from controller in js  --}}
    <p class="d-none">Variable from Controller: <span
            id="dataFromController">{{ count($Buy_invoince) == 0 ? 0 : $Buy_invoince }}</span></p>

@endsection
@section('js')


    <script src="{{ URL::asset('assets/js/jquery-3.5.1.js') }}"></script>

    {{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}

    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
    <script src="{{ URL::asset('assets/dataTables/dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.print.min.js') }}"></script>

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        var Client_invoince = <?php echo json_encode($Client_invoince); ?>;
        var client_mad = <?php echo json_encode($client_mad); ?>;
        var client_sup_sarf = <?php echo json_encode($client_sup_sarf); ?>;
        var sup_mad = <?php echo json_encode($sup_mad); ?>;
        var client_data = <?php echo json_encode($client_data); ?>;
        var refund_data = <?php echo json_encode($refund_data); ?>;
        var serviceinv = <?php echo json_encode($serviceinv); ?>;

        var Buy_invoince = JSON.parse(document.getElementById('dataFromController').textContent);
        console.log(Buy_invoince);

        $(document).ready(function() {


            tbody = [];
            tbody2 = [];
            sum_balance = 0;
            counter = 0;
            counter2 = 0;
            refund_price = 0;
            madeen = 0;
            da2en = 0;
            total_plus_money = 0;
            plus_money = 0;
            total_plus_tax = 0;
            total_plus_money = 0;
            var rassed = client_data[0].client_raseed == null ? 0 : client_data[0].client_raseed;
            if (rassed >= 0) {
                counter++;
                sum_balance += rassed;
                da2en += rassed;
                tbody.push(`
                            <tr>
                                <td>${counter}</td>
                                <td> الرصيد الإفتتاحي</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>

                                <td>${rassed }</td>
                                <td></td>
                                <td>${parseFloat(sum_balance).toFixed(2)}</td>

                                </tr>
                            `)
            } else {
                counter++;
                madeen += parseFloat(rassed) * -1;
                sum_balance += parseFloat(rassed);
                tbody.push(`
                            <tr>
                                <td>${counter}</td>
                                <td> الرصيد الإفتتاحي</td
                                 <td >---</td>>
                                <td>--</td>
                                <td>--</td>
                                <td></td>
                                <td>${parseFloat(rassed) * -1 }</td>

                                <td>${parseFloat(sum_balance).toFixed(2)}</td>

                                </tr>
                            `)
            };

            // sum_balance = parseFloat(sum_balance) * -1
            for (let i = 0; i < Client_invoince.length; i++) {
                counter++;

                old_data1 = Client_invoince[i].date;
                old_data = new Date(old_data1);
                if (i < Client_invoince.length - 1) {
                    x = i + 1;
                    new_data1 = Client_invoince[x].date
                    new_data = new Date(new_data1);

                } else {
                    new_data = '';
                }

                for (let j = 0; j < client_mad.length; j++) {
                    sanad_abd_date1 = client_mad[j].date;
                    sanad_abd_date = new Date(sanad_abd_date1);

                    if (sanad_abd_date.valueOf()  >= old_data.valueOf()  && sanad_abd_date.valueOf()  < new_data.valueOf() ) {
                        sum_balance += parseFloat(client_mad[j].paied ? client_mad[j].paied : 0)+parseFloat(client_mad[j].discount ? client_mad[j].discount : 0);
                        da2en += parseFloat(client_mad[j].paied ? client_mad[j].paied : 0)+parseFloat(client_mad[j].discount ? client_mad[j].discount : 0);
                        counter++;
                        tbody.push(`
                                                <tr>
                                                    <td>${counter}</td>
                                                    <td style="background-color:#ff6a0059">   سداد مديونية </td>
                                                    <td >---</td>
                                                    <td style="background-color:#ff6a0059">--</td>
                                                    <td style="background-color:#ff6a0059">${client_mad[j].date.split('T')[0] }</td>

                                                    <td style="background-color:#ff6a0059">${parseInt(client_mad[j].paied ? client_mad[j].paied : 0) +parseFloat(client_mad[j].discount ? client_mad[j].discount : 0) }</td>
                                                    <td style="background-color:#ff6a0059"></td>
                                                    <td >${parseFloat(sum_balance).toFixed(2)}</td>
                                                </tr>
                                            `);
                    } else if (new_data == '' && sanad_abd_date.valueOf()  >= old_data.valueOf() ) {
                        sum_balance += parseFloat(client_mad[j].paied ? client_mad[j].paied : 0) +parseFloat(client_mad[j].discount ? client_mad[j].discount : 0);
                        da2en += parseFloat(client_mad[j].paied ? client_mad[j].paied : 0)+parseFloat(client_mad[j].discount ? client_mad[j].discount : 0);
                        counter++;
                        tbody.push(`
                                            <tr>
                                                <td>${counter}</td>
                                                <td style="background-color:#ff6a0059">   سداد مديونية </td>
                                                <td >---</td>
                                                <td style="background-color:#ff6a0059">--</td>
                                                <td style="background-color:#ff6a0059">${client_mad[j].date.split('T')[0] }</td>

                                                <td style="background-color:#ff6a0059">${parseInt(client_mad[j].paied ? client_mad[j].paied : 0)+parseFloat(client_mad[j].discount ? client_mad[j].discount : 0) }</td>
                                                <td style="background-color:#ff6a0059"></td>
                                                <td >${parseFloat(sum_balance).toFixed(2)}</td>
                                                </tr>
                                            `);
                    }else if (sanad_abd_date.valueOf()  <= old_data.valueOf()  && i == 0){
                        sum_balance += parseFloat(client_mad[j].paied ? client_mad[j].paied : 0)+parseFloat(client_mad[j].discount ? client_mad[j].discount : 0);
                        da2en += parseFloat(client_mad[j].paied ? client_mad[j].paied : 0)+parseFloat(client_mad[j].discount ? client_mad[j].discount : 0);
                        counter++;
                        tbody.push(`
                                                <tr>
                                                    <td>${counter}</td>
                                                    <td style="background-color:#ff6a0059">   سداد مديونية </td>
                                                    <td >---</td>
                                                    <td style="background-color:#ff6a0059">--</td>
                                                    <td style="background-color:#ff6a0059">${client_mad[j].date.split('T')[0] }</td>

                                                    <td style="background-color:#ff6a0059">${parseInt(client_mad[j].paied ? client_mad[j].paied : 0)+parseFloat(client_mad[j].discount ? client_mad[j].discount : 0) }</td>
                                                    <td style="background-color:#ff6a0059"></td>
                                                    <td >${parseFloat(sum_balance).toFixed(2)}</td>
                                                </tr>
                                            `);
                            }
                }


                for (let j = 0; j < client_sup_sarf.length; j++) {
                    sanad_sarf_date1 = client_sup_sarf[j].date;
                    sanad_sarf_date = new Date(sanad_sarf_date1);

                    if (sanad_sarf_date.valueOf() >= old_data.valueOf() && sanad_sarf_date.valueOf() < new_data.valueOf()) {
                        // sum_balance -= parseFloat(client_sup_sarf[j].paied ? client_sup_sarf[j].paied : 0);
                        if (sum_balance < 0) {
                            sum_balance += parseFloat(client_sup_sarf[j].paied ? client_sup_sarf[j].paied : 0);

                        } else {
                            sum_balance -= parseFloat(client_sup_sarf[j].paied ? client_sup_sarf[j].paied : 0);

                        }
                        madeen += parseFloat(client_sup_sarf[j].paied ? client_sup_sarf[j].paied : 0);
                        counter++;
                        tbody.push(`
                                                <tr>
                                                    <td>${counter}</td>
                                                    <td style="background-color:#ff6a0059">    صرف مبلغ  </td>
                                                    <td >---</td>
                                                    <td style="background-color:#ff6a0059">--</td>
                                                    <td style="background-color:#ff6a0059">${client_sup_sarf[j].date.split('T')[0] }</td>

                                                    <td style="background-color:#ff6a0059"></td>
                                                    <td style="background-color:#ff6a0059">${parseInt(client_sup_sarf[j].paied ? client_sup_sarf[j].paied : 0) }</td>
                                                    <td >${parseFloat(sum_balance).toFixed(2)}</td>
                                                    </tr>
                                                `);


                    } else if (new_data.valueOf() == '' && sanad_sarf_date.valueOf() >= old_data.valueOf()) {
                        // sum_balance -= parseFloat(client_sup_sarf[j].paied ? client_sup_sarf[j].paied : 0);
                        if (sum_balance < 0) {
                            sum_balance += parseFloat(client_sup_sarf[j].paied ? client_sup_sarf[j].paied : 0);

                        } else {
                            sum_balance -= parseFloat(client_sup_sarf[j].paied ? client_sup_sarf[j].paied : 0);

                        }
                        madeen += parseFloat(client_sup_sarf[j].paied ? client_sup_sarf[j].paied : 0);
                        counter++;
                        tbody.push(`
                                                <tr>
                                                    <td>${counter}</td>
                                                    <td style="background-color:#ff6a0059">    صرف مبلغ  </td>
                                                    <td >---</td>
                                                    <td style="background-color:#ff6a0059">--</td>
                                                    <td style="background-color:#ff6a0059">${client_sup_sarf[j].date.split('T')[0] }</td>

                                                    <td style="background-color:#ff6a0059"></td>
                                                    <td style="background-color:#ff6a0059">${parseInt(client_sup_sarf[j].paied ? client_sup_sarf[j].paied : 0) }</td>
                                                    <td >${parseFloat(sum_balance).toFixed(2)}</td>
                                                    </tr>
                                                `);
                    }else if (sanad_sarf_date.valueOf()  <= old_data  && i == 0){
                          // sum_balance -= parseFloat(client_sup_sarf[j].paied ? client_sup_sarf[j].paied : 0);
                          if (sum_balance < 0) {
                            sum_balance += parseFloat(client_sup_sarf[j].paied ? client_sup_sarf[j].paied : 0);

                        } else {
                            sum_balance -= parseFloat(client_sup_sarf[j].paied ? client_sup_sarf[j].paied : 0);

                        }
                        madeen += parseFloat(client_sup_sarf[j].paied ? client_sup_sarf[j].paied : 0);
                        counter++;
                        tbody.push(`
                                                <tr>
                                                    <td>${counter}</td>
                                                    <td style="background-color:#ff6a0059">    صرف مبلغ  </td>
                                                    <td >---</td>
                                                    <td style="background-color:#ff6a0059">--</td>
                                                    <td style="background-color:#ff6a0059">${client_sup_sarf[j].date.split('T')[0] }</td>

                                                    <td style="background-color:#ff6a0059"></td>
                                                    <td style="background-color:#ff6a0059">${parseInt(client_sup_sarf[j].paied ? client_sup_sarf[j].paied : 0) }</td>
                                                    <td >${parseFloat(sum_balance).toFixed(2)}</td>
                                                    </tr>
                                                `);
                    }

                }


                if (sum_balance < 0) {
                    sum_balance += parseFloat(Client_invoince[i].actual_price);

                } else {
                    sum_balance -= parseFloat(Client_invoince[i].actual_price);

                }
                // sum_balance -=parseFloat(Client_invoince[i].actual_price);
                madeen += parseFloat(Client_invoince[i].actual_price);
                tbody.push(`
                                    <tr>
                                        <td>${counter}</td>
                                        <td> حساب فاتورة بيع</td>
                                    <td> ${Client_invoince[i].store['name']}</td>

                                        <td>${ Client_invoince[i].id}</td>
                                        <td>${Client_invoince[i].date.split('T')[0] }</td>
                                        <td></td>
                                        <td>${parseInt(Client_invoince[i].actual_price) }</td>
                                        <td>${parseFloat(sum_balance).toFixed(2)}</td>
                                    </tr>
                                `);
                counter++;
                // inv_discount = parseFloat(Client_invoince[i].discount);
                inv_discount = 0;
                if (Client_invoince[i].presale_order_id > 0) {
                    inv_discount = 0;
                } else {
                    inv_discount = parseFloat(Client_invoince[i].discount);
                }
                // sum_balance -= parseFloat(Client_invoince[i].discount);
                if (sum_balance > 0) {
                    sum_balance -= inv_discount;

                } else {
                    sum_balance += inv_discount;

                }
                da2en += parseFloat(inv_discount);
                tbody.push(`
                                            <tr>
                                                <td>${counter}</td>

                                                    <td style="background-color:#46260f59">  خصم على الفاتورة      </td>
                                                <td style="background-color:#46260f59">${Client_invoince[i].store['name']}</td>
                                                    <td style="background-color:#46260f59"> ${Client_invoince[i].id}</td>
                                                    <td style="background-color:#46260f59">${Client_invoince[i].date.split('T')[0]}</td>
                                                    <td style="background-color:#46260f59">${inv_discount}</td>
                                                    <td style="background-color:#46260f59"></td>
                                                    <td style="background-color:gray">${parseFloat(sum_balance).toFixed(2)}</td>
                                                    </tr>
                                        `);

                counter++;
                sum_balance += parseFloat(Client_invoince[i].paied);
                da2en += parseFloat(Client_invoince[i].paied);
                tbody.push(`
                                    <tr>
                                        <td>${counter}</td>
                                        <td style="background-color:#46260f59"> تحصيل علي فاتورة بيع</td>
                                        <td> ---</td>
                                        <td style="background-color:#46260f59">${ Client_invoince[i].id}</td>
                                        <td style="background-color:#46260f59">${Client_invoince[i].date.split('T')[0] }</td>

                                        <td style="background-color:#46260f59">${parseInt(Client_invoince[i].paied) }</td>
                                        <td style="background-color:#46260f59"></td>
                                        <td style="background-color:gray">${parseFloat(sum_balance).toFixed(2)}</td>
                                    </tr>
                                `);


                //for refund after finishing it by ghoniem
                counter++;
                inv_n = "--";
                refund_price = 0;
                if ((Client_invoince[i].refund_invoice_payment).length > 0) {
                    for (let z = 0; z < Client_invoince[i].refund_invoice_payment.length; z++) {
                        refund_price = Client_invoince[i].refund_invoice_payment[z].total_paied;
                        refund_price_paied = Client_invoince[i].refund_invoice_payment[z].paied;
                        inv_n = 'فاتورة رقم : ' + Client_invoince[i].refund_invoice_payment[z].invoice_id;

                        counter++;
                        sum_balance += parseFloat(refund_price);
                        da2en += parseFloat(refund_price);
                        tbody.push(`
                                            <tr>
                                                <td>${counter}</td>

                                                    <td style="background-color:#46260f59"> اجمالى إسترجاع      </td>
                                                    <td >---</td>
                                                    <td style="background-color:#46260f59"> ${inv_n}</td>
                                                    <td style="background-color:#46260f59">${Client_invoince[i].refund_invoice_payment[z].created_at.split('T')[0]}</td>
                                                    <td style="background-color:#46260f59">${refund_price}</td>
                                                    <td style="background-color:#46260f59"></td>
                                                    <td style="background-color:gray">${parseFloat(sum_balance).toFixed(2)}</td>
                                                    </tr>
                                            `);
                        counter++;
                        // sum_balance -= parseFloat(refund_price_paied);
                        if (sum_balance < 0) {
                            sum_balance += parseFloat(refund_price_paied);

                        } else {
                            sum_balance -= parseFloat(refund_price_paied);

                        }
                        madeen += parseFloat(refund_price_paied);

                        tbody.push(`
                                            <tr>
                                                <td>${counter}</td>

                                                    <td style="background-color:#46260f59"> دفع  على إسترجاع    </td>
                                                    <td >---</td>
                                                    <td style="background-color:#46260f59"> ${inv_n}</td>
                                                    <td style="background-color:#46260f59">${Client_invoince[i].refund_invoice_payment[z].created_at.split('T')[0]}</td>
                                                    <td style="background-color:#46260f59"></td>
                                                    <td style="background-color:#46260f59">${refund_price_paied}</td>
                                                    <td style="background-color:gray">${parseFloat(sum_balance).toFixed(2)}</td>
                                                    </tr>
                                            `);

                    }

                } else {
                    refund_price = 0;
                    inv_n = "--";
                    counter++;
                    // sum_balance -= parseFloat(refund_price);
                    if (sum_balance < 0) {
                        sum_balance += parseFloat(refund_price);

                    } else {
                        sum_balance -= parseFloat(refund_price);

                    }
                    da2en += parseFloat(refund_price);
                    tbody.push(`
                                            <tr>
                                                <td>${counter}</td>

                                                    <td style="background-color:#46260f59"> مبلغ إسترجاع على فواتير البيع للعميل  </td>
                                                    <td >---</td>
                                                    <td style="background-color:#46260f59"> ${inv_n}</td>
                                                    <td style="background-color:#46260f59"></td>
                                                    <td style="background-color:#46260f59">${refund_price}</td>
                                                    <td style="background-color:#46260f59"></td>
                                                    <td style="background-color:gray">${parseFloat(sum_balance).toFixed(2)}</td>
                                                    </tr>
                                            `);
                }





                // for (let j = 0; j < client_mad.length; j++) {
                //     sum_balance+=parseFloat(client_mad[j].paied ? client_mad[j].paied : 0);
                //     da2en+=parseFloat(client_mad[j].paied ? client_mad[j].paied : 0);
                //     counter++;
                //     tbody.push(`
            //     <tr>
            //         <td>${counter}</td>
            //         <td style="background-color:#ff6a0059">    سداد مديونية بيع </td>
            //         <td> ---</td>
            //         <td style="background-color:#ff6a0059">---</td>
            //         <td style="background-color:#ff6a0059">${client_mad[j].date.split('T')[0] }</td>

            //         <td style="background-color:#ff6a0059">${parseInt(client_mad[j].paied ? client_mad[j].paied : 0) }</td>
            //         <td style="background-color:#ff6a0059"></td>
            //         <td >${sum_balance}</td>
            //         </tr>
            //     `);


                // }

                // for (let j = 0; j < client_sup_sarf.length; j++) {
                //     sum_balance -= parseFloat(client_sup_sarf[j].paied ? client_sup_sarf[j].paied : 0);
                //     madeen += parseFloat(client_sup_sarf[j].paied ? client_sup_sarf[j].paied : 0);
                //     counter++;
                //     tbody.push(`
            //             <tr>
            //                 <td>${counter}</td>
            //                 <td style="background-color:#ff6a0059">    صرف مبلغ  </td>
            //                 <td >---</td>
            //                 <td style="background-color:#ff6a0059">--</td>
            //                 <td style="background-color:#ff6a0059">${client_sup_sarf[j].date.split('T')[0] }</td>

            //                 <td style="background-color:#ff6a0059"></td>
            //                 <td style="background-color:#ff6a0059">${parseInt(client_sup_sarf[j].paied ? client_sup_sarf[j].paied : 0) }</td>
            //                 <td >${sum_balance}</td>
            //                 </tr>
            //             `);


                // }


            }
            // ***********************************************************************

            // ***********************************************************************

            for (let k = 0; k < Buy_invoince.length; k++) {
                // data['Buy_invoince'][k];

                if (Buy_invoince[k].buy_transaction) {
                    var egytotalprice = Buy_invoince[k].total_price * Buy_invoince[k].currency_value;

                    if (sum_balance < 0) {
                        sum_balance += parseFloat(egytotalprice) - parseFloat(Buy_invoince[k].paied == null ? 0 :
                            Buy_invoince[k].paied);

                    } else {
                        sum_balance -= parseFloat(egytotalprice) - parseFloat(Buy_invoince[k].paied == null ? 0 :
                            Buy_invoince[k].paied);

                    }
                    da2en += parseFloat(egytotalprice) - parseFloat(Buy_invoince[k].paied == null ? 0 :
                        Buy_invoince[k].paied);

                    counter++;
                    tbody.push(`
                                    <tr>
                                            <td>${counter}</td>
                                            <td style="background-color:#46260f59">   فاتورة شراء من العميل  </td>
                                            <td> ---</td>
                                            <td style="background-color:#46260f59"> ${  Buy_invoince[k].transaction_id}</td>
                                            <td style="background-color:#46260f59">${Buy_invoince[k].buy_transaction.date.split('T')[0] }</td>
                                            <td style="background-color:#46260f59">${parseFloat(egytotalprice) - parseFloat(Buy_invoince[k].paied==null ? 0 : Buy_invoince[k].paied)}</td>
                                            <td style="background-color:#46260f59"></td>
                                            <td style="background-color:gray">${sum_balance }</td>
                                    </tr>
                                `);
                }

            }

            for (let j = 0; j < sup_mad.length; j++) {
                sum_balance += parseFloat(sup_mad[j].paied);
                madeen += parseFloat(sup_mad[j].paied);
                counter++;
                tbody.push(`
                                <tr>
                                    <td>${counter}</td>
                                    <td style="background-color:#ff6a0059">   سداد مديونية </td>
                                     <td >---</td>
                                    <td style="background-color:#ff6a0059">--</td>
                                    <td style="background-color:#ff6a0059">${sup_mad[j].date.split('T')[0] }</td>
                                    <td style="background-color:#ff6a0059"></td>
                                    <td style="background-color:#ff6a0059">${parseInt(sup_mad[j].paied) }</td>

                                    <td >${sum_balance }</td>
                                    </tr>
                                `);


            }



            for (let i = 0; i < serviceinv.length; i++) {
                counter++;


                sum_balance += parseFloat(serviceinv[i].total);
                madeen += parseFloat(serviceinv[i].total);
                tbody.push(`
                                        <tr>
                                            <td>${counter}</td>
                                            <td>   فاتورة حدمة</td>
                                            <td>-</td>
                                            <td>${ serviceinv[i].id}</td>
                                            <td>${serviceinv[i].date.split('T')[0] }</td>
                                            <td></td>
                                            <td>${parseInt(serviceinv[i].total) }</td>
                                            <td>${sum_balance  }</td>
                                        </tr>
                                    `);


                counter++;
                // sum_balance -= parseFloat(serviceinv[i].totalpaid);
                if (sum_balance < 0) {
                    sum_balance += parseFloat(serviceinv[i].totalpaid);

                } else {
                    sum_balance -= parseFloat(serviceinv[i].totalpaid);

                }
                da2en += parseFloat(serviceinv[i].totalpaid);
                tbody.push(`
                                        <tr>
                                            <td>${counter}</td>
                                            <td style="background-color:#46260f59"> تحصيل علي فاتورة خدمة</td>
                                            <td style="background-color:#46260f59">-</td>
                                            <td style="background-color:#46260f59">${ serviceinv[i].id}</td>
                                            <td style="background-color:#46260f59">${serviceinv[i].date.split('T')[0] }</td>

                                            <td style="background-color:#46260f59">${parseInt(serviceinv[i].totalpaid) }</td>
                                            <td style="background-color:#46260f59"></td>
                                            <td style="background-color:gray">${sum_balance  }</td>
                                        </tr>
                                    `);


            }
            counter++;
            tbody.push(`
                                <tr>
                                        <td > </td>
                                         <td > </td>
                                        <td ></td>
                                        <td ></td>
                                        <td ></td>
                                        <td ></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>



                                        <td > ${counter}</td>
                                        <td ></td>
                                         <td ></td>
                                        <td ></td>
                                        <td ></td>
                                        <td style="background-color:#f0f0c9">${parseFloat(da2en).toFixed(2) }</td>
                                        <td style="background-color:#f0f0c9">${ parseFloat(madeen).toFixed(2) }</td>
                                        <td style="background-color:#d74e09">${parseFloat(da2en -madeen).toFixed(2)}</td>
                                    </tr>
                                `);

            $('#client_hessap_tbl').html(tbody)


            // $('#hegri').html( HijriJS.today().toString());
            const date_now = new Date();
            // $('#hegri').html( HijriJS.today().toString()+"هـ");
            // $('#3arabi').html(formatDate(date_now, 'dd/mm/yyyy')+"م");








            window.print();

        })

        window.onafterprint = function() {
            //    console.log("Printing completed...");
            //    if(flag==1 || flag==4){
            //      window.location.href = '/invoice';
            //    }else if(flag==2){
            //     window.location.href = '/in_invoice';


            //    }else if(flag==3){
            window.location.href = '/clientStatment';
            //    }


        }

        function formatDate(date, format) {
            const map = {
                mm: date.getMonth() + 1,
                dd: date.getDate(),
                yy: date.getFullYear().toString().slice(-2),
                yyyy: date.getFullYear()
            }

            return format.replace(/dd|mm|yyyy|yyy/gi, matched => map[matched])
        }

        function get_period_in_dayes(d1, d2) {
            // var start = new Date($('#start_date').val());
            const date1 = new Date(d1);
            const date2 = new Date(d2);
            if (date1 > date2) {
                const diffTime = Math.abs(date1 - date2);
                var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                console.log(diffTime + " milliseconds");
                console.log(diffDays + " days");
            } else {
                diffDays = 0;
            }


            return diffDays;

        }
    </script>

@endsection
