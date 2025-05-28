@extends('layouts.master')
@section('css')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">



    <style>
        .itemimg:hover {
            width: 200px;
            height: 200px;
        }

        table {
            font-family: 'Droid Arabic Naskh', serif;
        }

        #partSpecsTbl tr {
            margin-top: 5px;
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
            background-color: #4045ba;
            border-color: #4045ba;
            border-radius: 10px;
            line-height: 26px;
            font-size: 14px;
        }

        .upload__btn:hover {
            background-color: unset;
            color: #4045ba;
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

        #staticBackdrop .modal-content {
            width: 50vw !important;
            /* height: 50vh !important; */

        }

        #staticBackdrop_edit .modal-content {
            width: 50vw !important;
            /* height: 50vh !important; */

        }
    </style>
@endsection
@section('title')
    supplier Statment
@stop


@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-primary " style="z-index: 88888 !important;" role="alert">
            <button type="button" class="btn-close bg-black text-white" data-bs-dismiss="alert"
                aria-label="Close"></button>
            {{ $message }}
        </div>
    @endif

    <div class="main-content">
        <div class="page-content">
            <h1 class="text-center text-info">Supplier Statment</h1>

            <div class="row ">
                <div class="col-lg-6">
                    <label class="form-label">من</label>
                    <input onchange="update_tbl_hesap()" type="date" class="form-control" name="start_date"
                        id="start_date" placeholder="From Date">
                </div>
                <div class="col-lg-6">
                    <label class="form-label">الي</label>
                    <input onchange="update_tbl_hesap()" type="date" class="form-control" name="end_date" id="end_date"
                        placeholder="To Date">
                </div>
            </div>
            <div class="row row-cards">
                <div class="col-lg-4">

                </div>

                <div class="col-lg-4">


                    <select class="form-select select2" name="supplierdrp" id="supplierdrp">
                        <option selected disabled value="">إحتر المورد</option>

                        @foreach ($supplier as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-4"></div>
            </div>
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        مجموعة شركات عمارة
                    </div>
                    <h2 class="page-title">
                        كشف حساب مورد
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <span class="d-none d-sm-inline">
                            <a id="print_hessap" href="#" class="btn btn-info">
                                طباعة
                            </a>
                        </span>


                    </div>
                </div>
            </div>

            <hr>


            <div class="row row-cards">


                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                        </div>

                        <div class="table-responsive p-2">
                            <table class="table  table-vcenter  datatable text-center table-bordered fs-18 fw-bold " id="itemtbl">
                                <thead>
                                    <tr>
                                        <th style="font-weight: bold">م</th>
                                        <th>البيان</th>
                                        <th> المخزن </th>
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
            </div>


            <hr>



        </div>

    </div>


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
        $(document).ready(function() {

$('#supplierdrp').select2();    


        });
    </script>

    <script>
        $(document).on('change', '#supplierdrp', function() {
            update_tbl_hesap()
        })

   function update_tbl_hesap() {
            var supp_id = $('#supplierdrp').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            // alert(start_date)
            if (!start_date) {
                start_date = null;
            }
            if (!end_date) {
                end_date = null;
            }

            newURL = 'hesssap_print2/' + supp_id + '/' + start_date + '/' + end_date;
            $('#print_hessap').attr("href", newURL)
            $.ajax({
                url: "{{ URL::to('hessap2') }}/" + supp_id,
                data: {
                    'start_date': $('#start_date').val(),
                    'end_date': $('#end_date').val()

                },
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log(data);

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
                    // var rassed = data['sup_data'][0].client_raseed == null ? 0 : data['sup_data'][0]
                    //     .client_raseed;
                    var rassed  = 0;
                        if(data['client_data'].length > 0){
                            var rassed = data['client_data'][0].client_raseed==null ? 0 : data['client_data'][0].client_raseed;
                        }
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
                                <td>${sum_balance}</td>

                                </tr>
                            `)
                    } else {
                        counter++;
                        madeen += parseFloat(rassed) * -1;
                        sum_balance += parseFloat(rassed);
                        tbody.push(`
                            <tr>
                                <td>${counter}</td>
                                <td> الرصيد الإفتتاحي</td>
                                 <td >---</td>
                                <td>---</td>
                                <td>---</td>
                                <td></td>
                                <td>${parseFloat(rassed) * -1 }</td>

                                <td>${sum_balance}</td>

                                </tr>
                            `)
                    };

                    sum_balance = parseFloat(sum_balance) * -1
                    for (let i = 0; i < data['Client_invoince'].length; i++) {
                        counter++;


                        sum_balance += parseFloat(data['Client_invoince'][i].actual_price);
                        madeen += parseFloat(data['Client_invoince'][i].actual_price);
                        tbody.push(`
                                <tr>
                                    <td>${counter}</td>
                                    <td> حساب فاتورة بيع</td>
                                    <td> ${data['Client_invoince'][i].store['name']}</td>
                                    <td>${ data['Client_invoince'][i].id}</td>
                                    <td>${data['Client_invoince'][i].date.split('T')[0] }</td>
                                    <td></td>
                                    <td>${parseInt(data['Client_invoince'][i].actual_price) }</td>
                                    <td>${sum_balance > 0 ? sum_balance : (sum_balance * -1) }</td>
                                </tr>
                            `);


                        counter++;
                        sum_balance -= parseFloat(data['Client_invoince'][i].paied);
                        da2en += parseFloat(data['Client_invoince'][i].paied);
                        tbody.push(`
                                <tr>
                                    <td>${counter}</td>
                                    <td style="background-color:#46260f59"> تحصيل علي فاتورة بيع</td>
                                      <td style="background-color:#46260f59">${data['Client_invoince'][i].store['name']}</td>
                                    <td style="background-color:#46260f59">${ data['Client_invoince'][i].id}</td>
                                    <td style="background-color:#46260f59">${data['Client_invoince'][i].date.split('T')[0] }</td>

                                    <td style="background-color:#46260f59">${parseInt(data['Client_invoince'][i].paied) }</td>
                                    <td style="background-color:#46260f59"></td>
                                    <td style="background-color:gray">${sum_balance > 0 ? sum_balance : (sum_balance * -1) }</td>
                                </tr>
                            `);


                        //for refund after finishing it by ghoniem
                        counter++;
                        inv_n = "--";
                        refund_price = 0;
                        if ((data['refund_data'][i]).length > 0) {
                            refund_price = parseFloat(data['refund_data'][i][0].r_amount) * parseFloat(data[
                                'refund_data'][i][0].item_price) + parseFloat(data['refund_data'][i][0]
                                .r_tax);
                            inv_n = 'فاتورة رقم : ' + data['refund_data'][i][0].invoice_id;

                        } else {
                            refund_price = 0;
                            inv_n = "--";
                        }
                        // refund_price=parseFloat(data['refund_data'][i][0]);
                        sum_balance -= parseFloat(refund_price);
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
                                    <td style="background-color:gray">${sum_balance > 0 ? sum_balance : (sum_balance * -1) }</td>
                                    </tr>
                            `);


                    }

                    for (let j = 0; j < data['client_mad'].length; j++) {
                        sum_balance -= parseFloat(data['client_mad'][j].paied);
                        da2en += parseFloat(data['client_mad'][j].paied);
                        counter++;
                        tbody.push(`
                                <tr>
                                    <td>${counter}</td>
                                    <td style="background-color:#ff6a0059">   سداد مديونية </td>
                                     <td >---</td>
                                    <td style="background-color:#ff6a0059">--</td>
                                    <td style="background-color:#ff6a0059">${data['client_mad'][j].date.split('T')[0] }</td>

                                    <td style="background-color:#ff6a0059">${parseInt(data['client_mad'][j].paied) }</td>
                                    <td style="background-color:#ff6a0059"></td>
                                    <td >${sum_balance > 0 ? sum_balance : (sum_balance * -1) }</td>
                                    </tr>
                                `);


                    }




                   for (let k = 0; k < data['Buy_invoince'].length; k++) {
                        // data['Buy_invoince'][k];
                        if (data['Buy_invoince'][k].buy_transaction) {
                            var egytotalprice = data['Buy_invoince'][k].total_price * data['Buy_invoince'][k]
                                .currency_value;
                            sum_balance -= parseFloat(egytotalprice) ;
                            da2en += parseFloat(egytotalprice) ;

                            counter++;
                            tbody.push(`
                                        <tr>
                                            <td>${counter}</td>

                                                <td style="background-color:#46260f59">   فاتورة شراء من العميل  </td>
                                                <td>---</td>

                                                <td style="background-color:#46260f59"> ${ data['Buy_invoince'][k].transaction_id}</td>
                                                <td style="background-color:#46260f59">${data['Buy_invoince'][k].buy_transaction.date.split('T')[0] }</td>
                                                <td style="background-color:#46260f59">${parseFloat(egytotalprice) }</td>
                                                <td style="background-color:#46260f59"></td>
                                                <td style="background-color:gray">${sum_balance > 0 ? sum_balance : (sum_balance * -1) }</td>
                                        </tr>
                                    `);
                                    //////////////////////////new
                                    sum_balance +=  parseFloat(data['Buy_invoince'][k].paied == null ? 0 : data['Buy_invoince'][k].paied);
                                    madeen += parseFloat(data['Buy_invoince'][k].paied ==    null ? 0 : data['Buy_invoince'][k].paied);

                            counter++;
                            tbody.push(`
                                        <tr>
                                            <td>${counter}</td>

                                                <td style="background-color:#ff6a0059">   دفعة على الفاتورة     </td>
                                                <td>---</td>

                                                <td style="background-color:#ff6a0059"> ${ data['Buy_invoince'][k].transaction_id}</td>
                                                <td style="background-color:#ff6a0059">${data['Buy_invoince'][k].buy_transaction.date.split('T')[0] }</td>
                                                <td style="background-color:#ff6a0059"></td>
                                                <td style="background-color:#46260f59">${ parseFloat(data['Buy_invoince'][k].paied==null ? 0 : data['Buy_invoince'][k].paied)}</td>
                                                <td ">${sum_balance > 0 ? sum_balance : (sum_balance * -1) }</td>
                                        </tr>
                                    `);




                        }

                    }

                    for (let j = 0; j < data['sup_mad'].length; j++) {
                        sum_balance += parseFloat(data['sup_mad'][j].paied);
                        madeen += parseFloat(data['sup_mad'][j].paied);
                        counter++;
                        tbody.push(`
                                <tr>
                                    <td>${counter}</td>
                                    <td style="background-color:#ff6a0059">   سداد مديونية </td>
                                     <td >---</td>
                                    <td style="background-color:#ff6a0059">--</td>
                                    <td style="background-color:#ff6a0059">${data['sup_mad'][j].date.split('T')[0] }</td>
                                    <td style="background-color:#ff6a0059"></td>
                                    <td style="background-color:#ff6a0059">${parseInt(data['sup_mad'][j].paied) }</td>

                                    <td >${sum_balance > 0 ? sum_balance : (sum_balance * -1) }</td>
                                    </tr>
                                `);


                    }

                    for (let i = 0; i < data['serviceinv'].length; i++) {
                        counter++;


                        sum_balance += parseFloat(data['serviceinv'][i].total);
                        madeen += parseFloat(data['serviceinv'][i].total);
                        tbody.push(`
                                <tr>
                                    <td>${counter}</td>
                                    <td>   فاتورة حدمة</td>
                                    <td>-</td>
                                    <td>${ data['serviceinv'][i].id}</td>
                                    <td>${data['serviceinv'][i].date.split('T')[0] }</td>
                                    <td></td>
                                    <td>${parseInt(data['serviceinv'][i].total) }</td>
                                    <td>${sum_balance > 0 ? sum_balance : (sum_balance * -1) }</td>
                                </tr>
                            `);


                        counter++;
                        sum_balance -= parseFloat(data['serviceinv'][i].totalpaid);
                        da2en += parseFloat(data['serviceinv'][i].totalpaid);
                        tbody.push(`
                                <tr>
                                    <td>${counter}</td>
                                    <td style="background-color:#46260f59"> تحصيل علي فاتورة خدمة</td>
                                      <td style="background-color:#46260f59">-</td>
                                    <td style="background-color:#46260f59">${ data['serviceinv'][i].id}</td>
                                    <td style="background-color:#46260f59">${data['serviceinv'][i].date.split('T')[0] }</td>

                                    <td style="background-color:#46260f59">${parseInt(data['serviceinv'][i].totalpaid) }</td>
                                    <td style="background-color:#46260f59"></td>
                                    <td style="background-color:gray">${sum_balance > 0 ? sum_balance : (sum_balance * -1) }</td>
                                </tr>
                            `);


                    }


                    counter++;
                    tbody.push(`
                                <tr>
                                        <td > </td>
                                        <td ></td>
                                        <td ></td>
                                        <td ></td>
                                         <td></td>
                                        <td ></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>



                                        <td > ${counter}</td>
                                        <td ></td>
                                        <td ></td>
                                        <td ></td>
                                         <td></td>
                                        <td style="background-color:#ff6a0059">${da2en}</td>
                                        <td style="background-color:#ff6a0059">${madeen  }</td>
                                        <td>${(madeen -da2en) > 0 ? (madeen -da2en) : ((madeen -da2en) * -1)}</td>
                                    </tr>
                                `);

                    $('#client_hessap_tbl').html(tbody)


                }
            });
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
