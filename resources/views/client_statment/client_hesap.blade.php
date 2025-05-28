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
    Client Statment
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
            <h1 class="text-center text-info">Client Statment</h1>

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
            <div class="row row-cards mt-2">
                <div class="col-lg-4">

                </div>

                <div class="col-lg-4">


                    <select class="form-select select2 fs-18 fw-bold" name="clientdrp" id="clientdrp">
                        <option selected disabled value="">إحتر العميل</option>

                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
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
                        كشف حساب عميل
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

            <div class="row d-none">
                <div class="col"> init rassed <span class="badge-danger px-2" id="g_init_rassed"></span></div>
                <div class="col"> g_actual_price <span class=" px-2" id="g_actual_price"></span></div>
                <div class="col"> - g_discount <span class=" px-2" id="g_discount"></span></div>
                <div class="col"> - g_total_refund_paied <span class=" px-2" id="g_total_refund_paied"></span></div>
                <div class="col"> - g_totalpaied <span class="badge-danger px-2" id="g_totalpaied"></span></div>
                <div class="col"> + g_refund_paied <span class="badge-danger px-2" id="g_refund_paied"></span></div>
                {{-- <div class="col">  g_total_refund_paiedxx <span class="badge-danger px-2" id="g_total_refund_paiedxx"></span></div> --}}

            </div>

            <div class="row row-cards">


                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                        </div>

                        <div class="table-responsive p-2">
                            <table class="table  table-vcenter  datatable text-center table-bordered fs-18 fw-bold"
                                id="itemtbl">
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

            <!--<h2> ( - ) رصيد </h2>-->
            <!--<h2> ( + ) مديونية </h2>-->


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
            $('#clientdrp').select2();



        });
    </script>

    <script>
        $(document).on('change', '#clientdrp', function() {
            update_tbl_hesap()
        })

        function update_tbl_hesap() {
            var client_id = $('#clientdrp').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            // alert(start_date)
            if (!start_date) {
                start_date = null;
            }
            if (!end_date) {
                end_date = null;
            }

            newURL = 'hesssap_print/' + client_id + '/' + start_date + '/' + end_date;
            $('#print_hessap').attr("href", newURL)
            $.ajax({
                url: "{{ URL::to('hessap') }}/" + client_id,
                data: {
                    'start_date': $('#start_date').val(),
                    'end_date': $('#end_date').val()

                },
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log(data);
                      /******************************/
                  Gblance=0;
                  $("#g_actual_price").text(data.g_actual_price);
                  $("#g_discount").text(data.g_discount);
                  $("#g_total_refund_paied").text(data.g_total_refund_paied);
                  $("#g_totalpaied").text(data.g_totalpaied);
                  $("#g_refund_paied").text(data.g_refund_paied);
                  $("#g_init_rassed").text(data.g_init_rassed);
                  $("#g_total_refund_paiedxx").text(data.g_total_refund_paiedxx);




                  $("#itemtbl tbody").empty();
                  data['all_res'].forEach((element,index) => {
                    Gblance += element.madeen - element.da2en;
                    $("#itemtbl tbody").append(`<tr style="background-color:${element.color}">
                                            <td>${index+1}</td>

                                                <td style="background-color:#46260f59">${element.name}</td>
                                                <td>${element.Store}</td>
                                                <td style="background-color:#46260f59"> ${element.Invoice_data}</td>
                                                <td style="background-color:#46260f59">${element.date.split('T')[0] }</td>
                                                <td style="background-color:#46260f59">${element.da2en}</td>
                                                <td style="background-color:#46260f59">${element.madeen}</td>
                                                <td style="background-color:gray">${Gblance}</td>
                                        </tr>
                                    `);
                  });
                  
                  $(".page-pretitle").text(Gblance);

                  return false;
                  /******************************/
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
                    var rassed = data['client_data'][0].client_raseed == null ? 0 : data['client_data'][0]
                        .client_raseed;
                    //الرصيد
                    if (rassed >= 0) {
                        counter++;
                        sum_balance += rassed;
                        da2en += rassed;
                        tbody.push(`
                            <tr>
                                <td style="background-color: #f0f0c9">${counter}</td>
                                <td style="background-color: #f0f0c9"> الرصيد الإفتتاحي</td>
                                <td style="background-color: #f0f0c9">--</td>
                                <td style="background-color: #f0f0c9">--</td>
                                <td style="background-color: #f0f0c9">--</td>
                                <td style="background-color: #f0f0c9">${rassed }</td>
                                <td style="background-color: #f0f0c9"></td>
                                <td style="background-color: #f0f0c9">${parseFloat(sum_balance).toFixed(2) }</td>

                                </tr>
                            `)
                        // sum_balance = parseFloat(sum_balance) ;
                    } else {
                        counter++;
                        madeen += parseFloat(rassed) * -1;
                        sum_balance += parseFloat(rassed);
                        tbody.push(`
                            <tr>
                                <td        >${counter}</td>
                                <td style="background-color: #f0f0c9"> الرصيد الإفتتاحي</td>
                                 <td style="background-color: #f0f0c9">---</td>
                                <td style="background-color: #f0f0c9">---</td>
                                <td style="background-color: #f0f0c9">---</td>
                                <td style="background-color: #f0f0c9"></td>
                                <td style="background-color: #f0f0c9">${parseFloat(rassed) * -1 }</td>

                                <td style="background-color: #f0f0c9"> ${parseFloat(sum_balance).toFixed(2) }</td>

                                </tr>
                            `)
                        // sum_balance = parseFloat(sum_balance) ;
                    };

                    // sum_balance = parseFloat(sum_balance) * -1

                    for (let i = 0; i < data['Client_invoince'].length; i++) {
                        counter++;
                        old_data1 = data['Client_invoince'][i].date;
                        old_data = new Date(old_data1);

                        if (i < data['Client_invoince'].length - 1) {
                            x = i + 1;
                            new_data1 = data['Client_invoince'][x].date
                            new_data = new Date(new_data1);

                        } else {
                            new_data = '';
                        }

                        for (let j = 0; j < data['client_mad'].length; j++) {
                            sanad_abd_date1 = data['client_mad'][j].date;
                            sanad_abd_date = new Date(sanad_abd_date1);
                            if (sanad_abd_date.valueOf() > old_data.valueOf() && sanad_abd_date.valueOf() < new_data.valueOf()) {
                                sum_balance += parseFloat(data['client_mad'][j].paied ? data['client_mad'][j]
                                    .paied + parseFloat(data['client_mad'][j].discount) : 0);
                                da2en += parseFloat(data['client_mad'][j].paied ? data['client_mad'][j].paied + parseFloat(data['client_mad'][j].discount) :
                                    0);
                                counter++;
                                tbody.push(`
                                        <tr>
                                            <td>${counter}</td>
                                            <td style="background-color:#70d6ff">   إستلام نقدية سداد مديونية </td>
                                            <td style="background-color:#70d6ff">---</td>
                                            <td style="background-color:#70d6ff">--</td>
                                            <td style="background-color:#70d6ff">${data['client_mad'][j].date.split('T')[0] }</td>

                                            <td style="background-color:#70d6ff">${parseInt(data['client_mad'][j].paied ? data['client_mad'][j].paied+parseInt(data['client_mad'][j].discount) : 0) }</td>
                                            <td style="background-color:#70d6ff"></td>
                                            <td style="background-color:#70d6ff">${parseFloat(sum_balance).toFixed(2) }</td>
                                        </tr>
                                    `);
                            } else if (new_data == '' && sanad_abd_date.valueOf() >= old_data.valueOf()) {
                                sum_balance += parseFloat(data['client_mad'][j].paied ? data['client_mad'][j]
                                    .paied+ parseFloat(data['client_mad'][j].discount) : 0);
                                da2en += parseFloat(data['client_mad'][j].paied ? data['client_mad'][j].paied+ parseFloat(data['client_mad'][j].discount) :
                                    0);
                                counter++;
                                tbody.push(`
                                    <tr>
                                        <td>${counter}</td>
                                        <td style="background-color:#70d6ff">   إستلام نقدية سداد مديونية </td>
                                        <td style="background-color:#70d6ff">---</td>
                                        <td style="background-color:#70d6ff">--</td>
                                        <td style="background-color:#70d6ff">${data['client_mad'][j].date.split('T')[0] }</td>

                                        <td style="background-color:#70d6ff">${parseInt(data['client_mad'][j].paied ? data['client_mad'][j].paied + parseFloat(data['client_mad'][j].discount) : 0) }</td>
                                        <td style="background-color:#70d6ff"></td>
                                        <td style="background-color:#70d6ff">${parseFloat(sum_balance).toFixed(2) }</td>
                                        </tr>
                                    `);
                            } else if (sanad_abd_date.valueOf() <= old_data.valueOf() && i == 0) {
                                sum_balance += parseFloat(data['client_mad'][j].paied ? data['client_mad'][j]
                                    .paied + parseFloat(data['client_mad'][j].discount) : 0);
                                da2en += parseFloat(data['client_mad'][j].paied ? data['client_mad'][j].paied + parseFloat(data['client_mad'][j].discount) :
                                    0);
                                counter++;
                                tbody.push(`
                                        <tr>
                                            <td>${counter}</td>
                                            <td style="background-color:#70d6ff">   إستلام نقدية سداد مديونية </td>
                                            <td style="background-color:#70d6ff">---</td>
                                            <td style="background-color:#70d6ff">--</td>
                                            <td style="background-color:#70d6ff">${data['client_mad'][j].date.split('T')[0] }</td>

                                            <td style="background-color:#70d6ff">${parseInt(data['client_mad'][j].paied ? data['client_mad'][j].paied + parseFloat(data['client_mad'][j].discount) : 0) }</td>
                                            <td style="background-color:#70d6ff"></td>
                                            <td style="background-color:#70d6ff">${parseFloat(sum_balance).toFixed(2) }</td>
                                        </tr>
                                    `);
                            }
                        }


                        for (let j = 0; j < data['client_sup_sarf'].length; j++) {
                            sanad_sarf_date1 = data['client_sup_sarf'][j].date;
                            sanad_sarf_date = new Date(sanad_sarf_date1);

                            if (sanad_sarf_date.valueOf() >= old_data.valueOf() && sanad_sarf_date.valueOf() < new_data.valueOf()) {

                                sum_balance -= parseFloat(data['client_sup_sarf'][j].paied > 0 ? data[
                                    'client_sup_sarf'][j].paied : 0);
                                madeen += parseFloat(data['client_sup_sarf'][j].paied > 0 ? data[
                                    'client_sup_sarf'][j].paied : 0);
                                counter++;
                                tbody.push(`
                                    <tr>
                                        <td>${counter}</td>
                                        <td style="background-color:#F63F3C">    صرف نقدية    </td>
                                         <td style="background-color:#F63F3C">---</td>
                                        <td style="background-color:#F63F3C">--</td>
                                        <td style="background-color:#F63F3C">${data['client_sup_sarf'][j].date.split('T')[0] }</td>

                                        <td style="background-color:#F63F3C"></td>
                                        <td style="background-color:#F63F3C">${parseInt(data['client_sup_sarf'][j].paied > 0 ? data['client_sup_sarf'][j].paied : 0) }</td>
                                        <td style="background-color:#F63F3C">${parseFloat(sum_balance).toFixed(2) }</td>
                                        </tr>
                                    `);


                            } else if (new_data == '' && sanad_sarf_date.valueOf() >= old_data.valueOf()) {

                                sum_balance -= parseFloat(data['client_sup_sarf'][j].paied ? data[
                                    'client_sup_sarf'][j].paied : 0);
                                madeen += parseFloat(data['client_sup_sarf'][j].paied ? data['client_sup_sarf'][
                                    j
                                ].paied : 0);
                                counter++;
                                tbody.push(`
                                    <tr>
                                        <td>${counter}</td>
                                        <td style="background-color:#F63F3C">    صرف مبلغ  </td>
                                         <td style="background-color:#F63F3C">---</td>
                                        <td style="background-color:#F63F3C">--</td>
                                        <td style="background-color:#F63F3C">${data['client_sup_sarf'][j].date.split('T')[0] }</td>

                                        <td style="background-color:#F63F3C"></td>
                                        <td style="background-color:#F63F3C">${parseInt(data['client_sup_sarf'][j].paied  ? data['client_sup_sarf'][j].paied : 0) }</td>
                                        <td style="background-color:#F63F3C">${parseFloat(sum_balance).toFixed(2) }</td>
                                        </tr>
                                    `);
                            } else if (sanad_abd_date.valueOf() <= old_data.valueOf() && i == 0) {

                                sum_balance -= parseFloat(data['client_sup_sarf'][j].paied > 0 ? data[
                                    'client_sup_sarf'][j].paied : 0);
                                madeen += parseFloat(data['client_sup_sarf'][j].paied > 0 ? data[
                                    'client_sup_sarf'][j].paied : 0);
                                counter++;
                                tbody.push(`
                                    <tr>
                                        <td>${counter}</td>
                                        <td style="background-color:#F63F3C">    صرف نقدية    </td>
                                         <td style="background-color:#F63F3C">---</td>
                                        <td style="background-color:#F63F3C">--</td>
                                        <td style="background-color:#F63F3C">${data['client_sup_sarf'][j].date.split('T')[0] }</td>

                                        <td style="background-color:#F63F3C"></td>
                                        <td style="background-color:#F63F3C">${parseInt(data['client_sup_sarf'][j].paied > 0 ? data['client_sup_sarf'][j].paied : 0) }</td>
                                        <td style="background-color:#F63F3C">${parseFloat(sum_balance).toFixed(2) }</td>
                                        </tr>
                                    `);

                            }

                        }
                        
                        sum_balance -= parseFloat(data['Client_invoince'][i].actual_price);


                        madeen += parseFloat(data['Client_invoince'][i].actual_price);
                        tbody.push(`
                                <tr>
                                    <td style="background-color: #0a9396">${counter}</td>
                                    <td style="background-color: #0a9396"> حساب فاتورة بيع</td>
                                    <td style="background-color: #0a9396"> ${data['Client_invoince'][i].store['name']}</td>
                                    <td style="background-color: #0a9396">${ data['Client_invoince'][i].id}</td>
                                    <td style="background-color: #0a9396">${data['Client_invoince'][i].date.split('T')[0] }</td>
                                    <td style="background-color: #0a9396"></td>
                                    <td style="background-color: #0a9396">${parseInt(data['Client_invoince'][i].actual_price) }</td>
                                    <td style="background-color: #0a9396">${parseFloat(sum_balance).toFixed(2) }</td>
                                </tr>
                            `);

                        counter++;
                        // inv_discount = parseFloat(data['Client_invoince'][i].discount);
                        inv_discount = 0;
                        if (data['Client_invoince'][i].presale_order_id > 0) {
                            inv_discount = 0;
                        } else {
                            inv_discount = parseFloat(data['Client_invoince'][i].discount);
                        }
                        // sum_balance -= parseFloat(data['Client_invoince'][i].discount);
                        if (sum_balance > 0) {
                            sum_balance -= inv_discount;



                        } else {
                            sum_balance += inv_discount;

                        }
                        da2en += parseFloat(inv_discount);
                        tbody.push(`
                                    <tr>
                                        <td>${counter}</td>

                                            <td style="background-color:#F3742B">  خصم على الفاتورة      </td>
                                      <td style="background-color:#F3742B">${data['Client_invoince'][i].store['name']}</td>
                                            <td style="background-color:#F3742B"> ${data['Client_invoince'][i].id}</td>
                                            <td style="background-color:#F3742B">${data['Client_invoince'][i].date.split('T')[0]}</td>
                                            <td style="background-color:#F3742B">${inv_discount}</td>
                                            <td style="background-color:#F3742B"></td>
                                            <td style="background-color:#F3742B">${parseFloat(sum_balance).toFixed(2) }</td>
                                            </tr>
                                    `);
                        counter++;
                        sum_balance += parseFloat(data['Client_invoince'][i].paied);
                        da2en += parseFloat(data['Client_invoince'][i].paied);
                        tbody.push(`
                                <tr>
                                     <td>${counter}</td>
                                    <td style="background-color:#70d6ff"> إستلام نقدية فاتورة بيع</td>
                                      <td style="background-color:#70d6ff">${data['Client_invoince'][i].store['name']}</td>
                                    <td style="background-color:#70d6ff">${ data['Client_invoince'][i].id}</td>
                                    <td style="background-color:#70d6ff">${data['Client_invoince'][i].date.split('T')[0] }</td>

                                    <td style="background-color:#70d6ff">${parseInt(data['Client_invoince'][i].paied) }</td>
                                    <td style="background-color:#70d6ff"></td>
                                    <td style="background-color:#70d6ff">${parseFloat(sum_balance).toFixed(2) }</td>
                                </tr>
                            `);


                        //for refund after finishing it by ghoniem
                        inv_n = "--";
                        refund_price = 0;
                        if ((data['Client_invoince'][i].refund_invoice_payment).length > 0) {
                            for (let z = 0; z < data['Client_invoince'][i].refund_invoice_payment.length; z++) {
                                refund_price = data['Client_invoince'][i].refund_invoice_payment[z].total_paied;
                                refund_price_paied = data['Client_invoince'][i].refund_invoice_payment[z].paied;
                                inv_n = 'فاتورة رقم : ' + data['Client_invoince'][i].refund_invoice_payment[z]
                                    .invoice_id;

                                counter++;
                                sum_balance += parseFloat(refund_price);
                                da2en += parseFloat(refund_price);
                                tbody.push(`
                                    <tr>
                                        <td>${counter}</td>

                                            <td style="background-color:#3a86ff">  اجمالى إسترجاع بضاعة   </td>
                                            <td style="background-color:#3a86ff">---</td>
                                            <td style="background-color:#3a86ff"> ${inv_n}</td>
                                            <td style="background-color:#3a86ff">${data['Client_invoince'][i].refund_invoice_payment[z].created_at.split('T')[0]}</td>
                                            <td style="background-color:#3a86ff">${refund_price}</td>
                                            <td style="background-color:#3a86ff"></td>
                                            <td style="background-color:#3a86ff">${parseFloat(sum_balance).toFixed(2) }</td>
                                            </tr>
                                    `);
                                counter++;
                                sum_balance -= parseFloat(refund_price_paied);

                                madeen += parseFloat(refund_price_paied);

                                tbody.push(`
                                    <tr>
                                        <td>${counter}</td>

                                            <td style="background-color:#F63F3C"> صرف نقدية على إسترجاع    </td>
                                            <td style="background-color:#F63F3C">---</td>
                                            <td style="background-color:#F63F3C"> ${inv_n}</td>
                                            <td style="background-color:#F63F3C">${data['Client_invoince'][i].refund_invoice_payment[z].created_at.split('T')[0]}</td>
                                            <td style="background-color:#F63F3C"></td>
                                            <td style="background-color:#F63F3C">${refund_price_paied}</td>
                                            <td style="background-color:#F63F3C">${parseFloat(sum_balance).toFixed(2) }</td>
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

                                            <td style="background-color:#3a86ff">  اجمالى إسترجاع بضاعة   </td>
                                            <td style="background-color:#3a86ff">---</td>
                                            <td style="background-color:#3a86ff"> ${inv_n}</td>
                                            <td style="background-color:#3a86ff"></td>
                                            <td style="background-color:#3a86ff">${refund_price}</td>
                                            <td style="background-color:#3a86ff"></td>
                                            <td style="background-color:#3a86ff">${parseFloat(sum_balance).toFixed(2) }</td>
                                            </tr>
                                    `);

                        }
                        // refund_price=parseFloat(data['refund_data'][i][0]);





                    }


                    for (let k = 0; k < data['Buy_invoince'].length; k++) {
                        // data['Buy_invoince'][k];
                        if (data['Buy_invoince'][k].buy_transaction) {
                            var egytotalprice = data['Buy_invoince'][k].total_price * data['Buy_invoince'][k]
                                .currency_value;
                            if (sum_balance < 0) {
                                sum_balance += parseFloat(egytotalprice) - parseFloat(data['Buy_invoince'][k]
                                    .paied == null ? 0 : data['Buy_invoince'][k].paied);

                            } else {
                                sum_balance -= parseFloat(egytotalprice) - parseFloat(data['Buy_invoince'][k]
                                    .paied == null ? 0 : data['Buy_invoince'][k].paied);

                            }
                            da2en += parseFloat(egytotalprice) - parseFloat(data['Buy_invoince'][k].paied ==
                                null ? 0 : data['Buy_invoince'][k].paied);

                            counter++;
                            tbody.push(`
                                        <tr>
                                            <td>${counter}</td>

                                                <td style="background-color:#46260f59">   فاتورة شراء من العميل  </td>
                                                <td>---</td>

                                                <td style="background-color:#46260f59"> ${  data['Buy_invoince'][k].transaction_id}</td>
                                                <td style="background-color:#46260f59">${data['Buy_invoince'][k].buy_transaction.date.split('T')[0] }</td>
                                                <td style="background-color:#46260f59">${parseFloat(egytotalprice) - parseFloat(data['Buy_invoince'][k].paied==null ? 0 : data['Buy_invoince'][k].paied)}</td>
                                                <td style="background-color:#46260f59"></td>
                                                <td style="background-color:gray">${parseFloat(sum_balance).toFixed(2)  }</td>
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
                                    <td style="background-color:#337da759">   إستلام نقدية سداد مديونية </td>
                                     <td style="background-color:#337da759">---</td>
                                    <td style="background-color:#337da759">--</td>
                                    <td style="background-color:#337da759">${data['sup_mad'][j].date.split('T')[0] }</td>
                                    <td style="background-color:#337da759"></td>
                                    <td style="background-color:#337da759">${parseInt(data['sup_mad'][j].paied) }</td>

                                    <td style="background-color:#337da759">${parseFloat(sum_balance).toFixed(2)  }</td>
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
                                    <td>   فاتورةخدمة</td>
                                    <td>-</td>
                                    <td>${ data['serviceinv'][i].id}</td>
                                    <td>${data['serviceinv'][i].date.split('T')[0] }</td>
                                    <td></td>
                                    <td>${parseInt(data['serviceinv'][i].total) }</td>
                                    <td>${parseFloat(sum_balance).toFixed(2)   }</td>
                                </tr>
                            `);


                        counter++;
                        // sum_balance -= parseFloat(data['serviceinv'][i].totalpaid);
                        if (sum_balance < 0) {
                            sum_balance += parseFloat(data['serviceinv'][i].totalpaid);



                        } else {
                            sum_balance -= parseFloat(data['serviceinv'][i].totalpaid);

                        }
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
                                    <td style="background-color:gray">${parseFloat(sum_balance).toFixed(2)   }</td>
                                </tr>
                            `);


                    }
                    counter++;
                    tbody.push(`
                                <tr>
                                        <td style="background-color:#d74e09"> </td>
                                        <td style="background-color:#d74e09"></td>
                                        <td style="background-color:#d74e09"></td>
                                        <td style="background-color:#d74e09"></td>
                                        <td style="background-color:#d74e09"></td>
                                        <td style="background-color:#d74e09"></td>
                                        <td style="background-color:#d74e09"></td>
                                        <td style="background-color:#d74e09"></td>
                                    </tr>
                                    <tr>

                                        <td > ${counter}</td>
                                        <td style="background-color:#f0f0c9"></td>
                                        <td style="background-color:#f0f0c9"></td>
                                        <td style="background-color:#f0f0c9"></td>
                                         <td style="background-color:#f0f0c9"></td>
                                        <td style="background-color:#f0f0c9">${parseFloat(da2en).toFixed(2) }</td>
                                        <td style="background-color:#f0f0c9">${ parseFloat(madeen).toFixed(2) }</td>
                                        <td style="background-color:#d74e09">${parseFloat(da2en -madeen).toFixed(2)}</td>
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
