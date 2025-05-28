@extends('layouts.posMaster')
@section('css')
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css"> --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />


    <link rel="stylesheet" href="{{ URL::asset('assets/dataTables/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/dataTables/buttons.bootstrap5.min.css') }}"> --}}
    {{-- <link href='https://fonts.googleapis.com/css?family=Cairo' rel='stylesheet'> --}}

    <style>

        /* @font-face {
                    font-family: "My Custom Font";
                    src: url(https://fonts.gstatic.com/s/cairo/v28/SLXgc1nY6HkvangtZmpQdkhzfH5lkSs2SgRjCAGMQ1z0hOA-a1PiLA.woff2) format("truetype");
                            }
                    p.customfont {
                    font-family: "My Custom Font", Verdana, Tahoma;
                            } */
        /* .itemimg:hover {
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
            } */
    </style>


@endsection
@section('title')
    الخزينة
@stop


@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-primary " style="z-index: 88888 !important;" role="alert">
            <button type="button" class="btn-close bg-black text-white" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ $message }}
        </div>
    @endif

    <div class="main-content ">
        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">خزينة {{ $store->name }}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">خزينة {{ $store->name }}</li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-head">
                            <div class="row">
                                <div class="col-2  mt-3 text-start">
                                    <h4>رقم الحساب : {{ $AccsafeMoney->safe_accountant_number }}</h4>
                                    <h4>الإجمالي : {{ $current_balance }}</h4>
                                </div>
                                <div class="col-2 px-1 mt-3 text-start">
                                    <input type="date" class="form-control" name="month" id="month">
                                    <input type="hidden" class="form-control" value="{{ $store->id }}"
                                        id="get_store_id">


                                </div>
                                @if ($store->id == 8)
                                    <div class="col-2 px-1 mt-3 text-start">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#staticBackdrop_send_store"><i class="ri-add-fill"></i>
                                            صرف مبلغ لمخزن
                                        </button>
                                    </div>
                                @else
                                    <div class="col-2 px-1 mt-3 text-start">
                                        <button class="btn  btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#staticBackdrop_send_company"><i class="ri-add-fill"></i>
                                            توريد مبلغ للخزنة الرئيسية
                                        </button>
                                    </div>
                                @endif
                                <div class="col-2 px-1 mt-3 text-start">
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop_send_bank"><i class="ri-add-fill"></i>
                                        توريد مبلغ للبنك
                                    </button>
                                </div>

                                <div class="col-2 px-1 mt-3 text-start">
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop_add_money"><i class="ri-add-fill"></i>
                                        ضخ مبلغ للخزنة </button>
                                </div>
                                <div class="col-2 px-1 mt-3 text-start">
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop_other"><i class="ri-add-fill"></i>
                                        صرف مبلغ من الخزنة (نثريات) </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive ">

                            <table id="itemtbl" class=" table table-striped table-bordered cell-border dataTable no-footer dtr-inline" style="width:100%">

                                <thead style="background:#5fcee78a" class="text-nowrap" >

                                    <tr >
                                        <th class="text-center" >رقم المسلسل</th>
                                        <th class="text-center"> القيمة </th>
                                        <th class="text-center">نو ع السند</th>
                                        <th class="text-center" > المبلغ الموجود بالخزنة</th>
                                        <th class="text-center">الصورة</th>
                                        <th class="text-center">النوع</th>
                                        <th class="text-center">ملاحظات</th>
                                        <th class="text-center">اسم المستخدم</th>
                                        <th class="text-center">التاريخ</th>


                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    {{-- <-staticBackdrop_add_money--> --}}

    <div class="modal fade" id="staticBackdrop_add_money" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> ضخ مبلغ للخزنة
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6> ملحوظة : لا يوجد قيد ألي . </h6>
                    <form action="{{ route('add_money') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="store_id" value="{{ $store->id }}">

                                <label for="recipient-name" class="col-form-label"> المبلغ:</label>
                                <input type="number" class="form-control" name="money"
                                    onkeypress="return event.charCode >= 48" min="0" required>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="" class="col-form-label">إضافة صورة</label>

                                <input type="file" class="form-control" name="img_path">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">ملاحظات</label>
                            <textarea class="form-control" name="notes" rows="3"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn mb-2 btn-primary">حفظ</button>
                    <button type="button" class="btn mb-2 btn-secondary" data-bs-dismiss="modal">غلق</button>
                </div>
                </form>

            </div>
        </div>
    </div>
    {{-- <-staticBackdrop_send_store--> --}}

    <div class="modal fade" id="staticBackdrop_send_store" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> صرف مبلغ لمخزن
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('money_send_store') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <select class="form-select form-select-solid  " name="to_store" id="to_store" required>
                                <option class="text-center" value="" selected disabled>اختر اسم المخزن</option>
                                @foreach ($all_stores as $all_store)
                                    @if ($all_store->id != 8)
                                        <option class="text-center
                                    "
                                            value="{{ $all_store->id }}">
                                            {{ $all_store->name }}</option>
                                    @endif
                                @endforeach
                            </select>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="store_id" value="{{ $store->id }}">

                                <label for="recipient-name" class="col-form-label"> المبلغ:</label>
                                <input type="number" class="form-control" name="money"
                                    onkeypress="return event.charCode >= 48" min="0" required>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="" class="col-form-label">إضافة صورة</label>

                                <input type="file" class="form-control" name="img_path">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">ملاحظات</label>
                            <textarea class="form-control" name="notes" rows="3"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn mb-2 btn-primary">حفظ</button>
                    <button type="button" class="btn mb-2 btn-secondary" data-bs-dismiss="modal">غلق</button>
                </div>
                </form>

            </div>
        </div>
    </div>
    {{-- <-staticBackdrop_send_company--> --}}

    <div class="modal fade" id="staticBackdrop_send_company" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> توريد مبلغ للخزنة الرئيسية
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('money_send_company') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="to_store" value="8">
                                <input type="hidden" name="store_id" value="{{ $store->id }}">


                                <label for="recipient-name" class="col-form-label"> المبلغ:</label>
                                <input type="number" class="form-control" name="money"
                                    onkeypress="return event.charCode >= 48" min="0" required>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="" class="col-form-label">إضافة صورة</label>

                                <input type="file" class="form-control" name="img_path">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">ملاحظات</label>
                            <textarea class="form-control" name="notes" rows="3"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn mb-2 btn-primary">حفظ</button>
                    <button type="button" class="btn mb-2 btn-secondary" data-bs-dismiss="modal">غلق</button>
                </div>
                </form>

            </div>
        </div>
    </div>
    {{-- <-staticBackdrop_send_bank--> --}}

    <div class="modal fade" id="staticBackdrop_send_bank" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> توريد مبلغ للبنك
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('money_send_bank') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row ">
                            <select class="form-select form-select-solid  " name="currency_id" id="currency_id"
                                data-control="select2" required>
                                <option class="text-center" value="" selected disabled>اختر اسم العملة</option>
                                @foreach ($all_curencys as $all_curency)
                                    <option class="text-center
                                    "
                                        value="{{ $all_curency->id }}">
                                        {{ $all_curency->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row ">
                            <div class="col-6">

                                <label for="recipient-name" class="col-form-label"> اختر البنك: </label>
                                <select name="bank_type_id" id="bank_type_id" data-control="select2"
                                    class="form-control ">
                                    <option class="text-center" data-account-number="" value=""selected disabled>
                                        أختر البنك</option>
                                    @foreach ($bank_types as $bank_type)
                                        <option class="text-center"
                                            data-account-number="{{ $bank_type->account_number }}"
                                            value="{{ $bank_type->id }}">
                                            {{ $bank_type->bank_name }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-6">
                                <label for="recipient-name" class="col-form-label"> رقم الحساب:</label>
                                <input type="number" class="form-control" name="account_number" id="account_number"
                                    disabled>
                            </div>



                        </div>
                        <div class="row">
                            <div class="col-md-12">

                                <input type="hidden" name="store_id" value="{{ $store->id }}">


                                <label for="recipient-name" class="col-form-label"> المبلغ:</label>
                                <input type="number" class="form-control" name="money"
                                    onkeypress="return event.charCode >= 48" min="0" required>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="" class="col-form-label">إضافة صورة</label>

                                <input type="file" class="form-control" name="img_path">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">ملاحظات</label>
                            <textarea class="form-control" name="notes" rows="3"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn mb-2 btn-primary">حفظ</button>
                    <button type="button" class="btn mb-2 btn-secondary" data-bs-dismiss="modal">غلق</button>
                </div>
                </form>

            </div>
        </div>
    </div>
    {{-- <-staticBackdrop_other--> --}}

    <div class="modal fade" id="staticBackdrop_other" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">صرف مبلغ من الخزنة (نثريات)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('add_other') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row ">
                            <label for="recipient-name" class="col-form-label"> اختر بند الصرف: </label>
                            <select name="note_id" id="note_id" data-control="select2" class="form-control " required>
                                <option class="text-center" value=""selected disabled> أختر البند</option>
                                @foreach ($all_notes as $all_note)
                                    <option class="text-center" value="{{ $all_note->id }}">
                                        {{ $all_note->notes }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="store_id" value="{{ $store->id }}">
                                <label for="recipient-name" class="col-form-label"> المبلغ:</label>
                                <input type="number" class="form-control" name="money"
                                    onkeypress="return event.charCode >= 48" min="0" required>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="" class="col-form-label">إضافة صورة</label>

                                <input type="file" class="form-control" name="img_path">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">ملاحظات</label>
                            <textarea class="form-control" rows="3" name="notes"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn mb-2 btn-primary">حفظ</button>
                    <button type="button" class="btn mb-2 btn-secondary" data-bs-dismiss="modal">غلق</button>
                </div>
                </form>

            </div>
        </div>
    </div>

@endsection

@section('js')
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script> --}}

    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
    {{-- <script src="{{ URL::asset('assets/dataTables/dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.print.min.js') }}"></script> --}}
    <script>
        var safeMoney = {!! $safeMoney !!}
        var itemtbl = "";
        $(document).ready(function() {





            itemtbl = $("#itemtbl").DataTable({

                lengthChange: false,
                "oLanguage": {
                    "sSearch": "البحث"
                },

                buttons: [

                    {
                        extend: 'print',
                        text: 'طباعة',
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        }
                    }

                ],
                "order": [
                    [0, 'desc']
                ],
                "columnDefs": [
                    // {
                    //     "className": "d-none",
                    //     "targets": [0]
                    // },
                    {
                        "className": "text-nowrap",
                        "targets": [4]
                    }, {
                        "className": "dt-center",
                        "targets": "_all"
                    }

                ],


                columns: [

                    {
                        data: "id"
                    }, {
                        data: "money"
                    }, {
                        data: "type_money",
                        "render": function(data) {
                            if (data == 0) {
                                return '<span class="text-success">سند قبض</span>';
                            } else {
                                return '<span class="text-danger">سند صرف</span>';
                            }
                        }
                    },

                    {
                        data: "total"
                    }, {
                        data: "img_path",
                        "render": function(data) {


                            if (data) {
                                return '<img  class="avatar-img img-thumbnail rounded-5 itemimg" data-url="' +
                                    data + '"  src="../notes_safe_money/' + data +
                                    '" width="40px">'

                            } else {
                                return 'لا يوجد صورة';
                            }


                        }
                    },
                    {
                        data: "note",
                        render: function(data, type, row, meta) {
                            if (row) {
                                return row.notes;
                            } else {
                                return '-';
                            }
                        }

                    },
                    {
                        data: "notes"
                    },
                    {
                        data: "user.username"
                    },
                    {
                        data: "date"
                    },


                ],

            });
            itemtbl.clear();
            itemtbl.rows.add(safeMoney).draw();
            $('#month').on('change', function() {
                var month = $(this).val();
                var store_id = $('#get_store_id').val();
                if (month) {
                    $.ajax({
                        url: "{{ URL::to('show_safeMoney_month') }}/",
                        type: "GET",
                        dataType: "json",
                        data: {
                            'month': month,
                            'store_id': store_id
                        },
                        success: function(data) {

                            itemtbl.clear();

                            itemtbl.rows.add(data).draw();

                        },
                    });

                } else {
                    itemtbl.clear();

                    itemtbl.rows.add(safeMoney).draw();
                }
            });
            itemtbl.buttons().container().appendTo('#itemtbl_wrapper .col-md-6:eq(0)');

        });


        $("#itemtbl_filter").children().find('label').css('float', 'left').addClass('pt-2');
        $("#itemtbl_filter").children().find('input').removeAttr('class').addClass('form-control');
    </script>
    <script>
        $(document).ready(function() {
            $("#bank_type_id").select2({
                dropdownParent: $('#staticBackdrop_send_bank')
            });
            $("#currency_id").select2({
                dropdownParent: $('#staticBackdrop_send_bank')
            });
            $("#to_store").select2({
                dropdownParent: $('#staticBackdrop_send_store')
            });

            $("#note_id").select2({
                dropdownParent: $('#staticBackdrop_other')
            });
        });

        $("#bank_type_id").on('change', function() {
            $("#account_number").val($("#bank_type_id option:selected").attr('data-account-number'));
        });
    </script>





@endsection
