@extends('layouts.master')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::asset('assets/dataTables/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/dataTables/buttons.bootstrap5.min.css') }}">


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
    </style>
@endsection
@section('title')
    مرتبات الموظفين
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
            <h1 class="text-center text-info">مرتبات موظفين {{ $store->name }}
            </h1>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-head">
                            <div class="row">
                                <div class="col-6  mt-3 text-start">
                                </div>
                                <div class="col-2 px-1 mt-3 text-start">
                                    <div id="month_text">
                                    </div>
                                    <div>
                                        <label class="col-form-label"> اختر شهر القبض </label>

                                        <input type="month" class="form-control " name="month" id="month">
                                        <input type="hidden" class="form-control " id="get_store_id"
                                            value="{{ $store->id }}">


                                    </div>
                                </div>
                                <div class="col-2 px-1 mt-3 text-start">
                                    <a href="{{ route('get_solfa_store', $store->id) }}" class="btn btn-info"><i
                                            class="ri-add-fill"></i>
                                        السلف </a>
                                </div>
                                <div class="col-2 px-1 mt-3 text-start">
                                    <a href="{{ route('salary_history', $store->id) }}" class="btn btn-info"><i
                                            class="ri-add-fill"></i>
                                        سجل صرف المرتبات </a>
                                </div>

                            </div>
                        </div>
                        <div class="card-body">
                            <table id="itemtbl" class="display table table-bordered dt-responsive dataTable dtr-inline"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th> رقم الموظف </th>
                                        <th> الاسم </th>
                                        <th> الوظيفة </th>
                                        <th> رقم تليفون </th>
                                        <th> رقم تليفون اخر </th>
                                        <th> المرتب الأساسي </th>
                                        <th> الصافي </th>
                                        <th></th>
                                        <th></th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th> رقم الموظف </th>
                                        <th> الاسم </th>
                                        <th> الوظيفة </th>
                                        <th> رقم تليفون </th>
                                        <th> رقم تليفون اخر </th>
                                        <th> المرتب الأساسي </th>
                                        <th> المرتب </th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    {{-- <--Add_bonus--> --}}

    <div class="modal fade" id="staticBackdrop_add_bonus" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">إضافة منحة لموظف </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employee_salary.store') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" name="flag_type" value="0">
                                <label for="recipient-name" class="col-form-label">رقم الموظف :</label>
                                <input type="number" class="form-control" id="bonus_employee_id" name="employee_id"
                                    readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="recipient-name" class="col-form-label">اسم الموظف :</label>
                                <input type="text" class="form-control" id="bonus_employee_name" name="employee_name"
                                    readonly>
                            </div>

                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="recipient-name" class="col-form-label">المبلغ :</label>
                                <input type="number" class="form-control" name="money"
                                    onkeypress="return event.charCode >= 48" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label">شهر صرف المنحة </label>
                                <input type="month" class="form-control pay_month" name="month" value="">

                            </div>

                        </div>
                        <hr>
                        <div class="row">

                            <div class="col-md-6">
                                <label class="col-form-label">تاريخ الاضافة</label>
                                <input type="text" class="form-control reserve_date" name="date" readonly>

                            </div>

                        </div>
                        <div class="row">
                            <label class="col-form-label">ملاحظات </label>

                            <textarea name="notes" cols="3" rows="3" required></textarea>
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
    {{-- <--Add_discount--> --}}

    <div class="modal fade" id="staticBackdrop_add_discount" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">إضافة خصم لموظف </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employee_salary.store') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" name="flag_type" value="1">
                                <label for="recipient-name" class="col-form-label">رقم الموظف :</label>
                                <input type="number" class="form-control" id="discount_employee_id" name="employee_id"
                                    readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="recipient-name" class="col-form-label">اسم الموظف :</label>
                                <input type="text" class="form-control" id="discount_employee_name"
                                    name="employee_name" readonly>
                            </div>

                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="recipient-name" class="col-form-label">المبلغ :</label>
                                <input type="number" class="form-control" name="money"
                                    onkeypress="return event.charCode >= 48" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label">شهر خصم المرتب </label>
                                <input type="month" class="form-control pay_month " name="month" value="">

                            </div>

                        </div>
                        <hr>
                        <div class="row">

                            <div class="col-md-6">
                                <label class="col-form-label">تاريخ الاضافة</label>
                                <input type="text" class="form-control reserve_date" name="date" readonly>

                            </div>

                        </div>
                        <div class="row">
                            <label class="col-form-label">ملاحظات </label>

                            <textarea name="notes" cols="3" rows="3" required></textarea>
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

    {{-- <--pay_salary--> --}}

    <div class="modal fade" id="staticBackdrop_pay_salary" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> صرف مرتب </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('pay_salary') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                {{-- <input type="hidden" name="flag_type" value="2"> --}}
                                <label for="recipient-name" class="col-form-label">رقم الموظف :</label>
                                <input type="number" class="form-control" id="salary_employee_id" name="employee_id"
                                    readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="recipient-name" class="col-form-label">اسم الموظف :</label>
                                <input type="text" class="form-control" id="salary_employee_name"
                                    name="employee_name" readonly>
                            </div>

                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="recipient-name" class="col-form-label">المبلغ :</label>
                                <input type="number" readonly id="salary_value" class="form-control" name="money"
                                    onkeypress="return event.charCode >= 48" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label">شهر صرف المرتب </label>
                                <input type="month" class="form-control pay_month" name="month" value="">

                            </div>

                        </div>
                        <hr>
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" id="operation_flag_outside" value="0"
                                name="flag_solfa">
                            <div class="ml-4">
                                <label class="ml-4 font1 " for="operation_flag_outside"> سداد سلفة</label>

                            </div>

                        </div>
                        <div class="row div_operation_flag_outside  ">



                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <label class="col-form-label">تاريخ الاضافة</label>
                                <input type="text" class="form-control reserve_date" name="date" readonly>

                            </div>

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

    <!--end::Modal -->

@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
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
    <script>
        pdfMake.fonts = {
            Arial: {
                normal: 'arial.ttf',
                bold: 'arial.ttf',

            }
        }
        var employees = '';

        $('#month').on('change', function() {
            var month = $(this).val();
            var store_id = $('#get_store_id').val();

            itemtbl.clear();
            $('#month_text').empty();
            $.ajax({
                url: "{{ URL::to('get_employee_salary') }}/",
                type: "GET",
                dataType: "json",
                data: {
                    'month': month,
                    'store_id': store_id
                },
                success: function(data) {
                    employees = data
                    itemtbl.rows.add(data).draw();
                    $('#month_text').append(
                        `<h3>تم اختيار شهر ${month}</h3>`
                    );


                },
            });

        });

        var itemtbl = "";



        $(document).ready(function() {
            var now = new Date();
            var day = ("0" + now.getDate()).slice(-2);
            var month = ("0" + (now.getMonth() + 1)).slice(-2);
            var today = now.getFullYear() + "-" + (month) + "-" + (day);
            $('.reserve_date').val(today);

            itemtbl = $("#itemtbl").DataTable({
                lengthChange: false,
                "oLanguage": {
                    "sSearch": "البحث"
                },

                buttons: [{
                        extend: 'excel',
                        text: 'Excel <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>'
                    }, {
                        extend: 'pdf',
                        text: 'PDF <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>'
                    },

                    'copy', 'colvis', 'pdf', {
                        extend: 'print',
                        text: 'طباعة',
                        className: 'bg-danger',
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        }
                    }

                ],
                "columnDefs": [

                    {
                        "className": "dt-center",
                        "targets": "_all"
                    }
                ],


                columns: [

                    {
                        data: "id"
                    },
                    {
                        data: "employee_name"
                    },
                    {
                        data: "role.name"
                    },

                    {
                        data: "employee_phone"
                    },
                    {
                        data: "employee_telephone"
                    },
                    {
                        data: "employee_salary"
                    },

                    {
                        data: "newsalary"
                    },

                    {

                        data: null,

                        className: "dt-center editor-editt",

                        defaultContent: `
                        <div class="dropdown">
                                        <button class="btn btn-primary text-white dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-list-ul"></i>
                                     </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                            <li><button class="dropdown-item font1  text-center bonus_item " data-bs-toggle="modal" data-bs-target="#staticBackdrop_add_bonus">إضافة منحة للموظف</button></li>
                                            <li><button class="dropdown-item font1  text-center  discount_item text-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop_add_discount">إضافة خصم للموظف</i> </button></li>
                                            <li><button class="dropdown-item font1  text-center pay_salary text-danger" data-bs-toggle="modal" data-bs-target="#staticBackdrop_pay_salary">  صرف مرتب  </button></li>

                                        </ul>
                                </div>
                            `,

                        orderable: false,


                    },
                    {

                        data: null,

                        className: "dt-center editor-delete",

                        defaultContent: '<button class="btn btn-primary"></button>',

                        orderable: false,
                        render: function(data, type, row, meta) {
                            return `

                                    <a class="btn btn-primary text-nowrap" href="/employee_salary_details/${row.id}">عرض تفاصيل المرتب</a>
                                     `;
                        }

                    },
                ],



            });

            itemtbl.clear();
            itemtbl.rows.add(employees).draw();
            itemtbl.buttons().container().appendTo('#itemtbl_wrapper .col-md-6:eq(0)');




            itemtbl.buttons().container().appendTo('#itemtbl_wrapper .col-md-6:eq(0)');

        });
    </script>

    <script>
        $('#itemtbl tbody').on('click', '.bonus_item', function() {
            $("#bonus_employee_id").val('');
            $("#bonus_employee_name").val('');
            $(".pay_month").val('');
            var currentRow = $(this).closest("tr");
            $("#bonus_employee_id").val(currentRow.find("td:eq(0)").text());
            $("#bonus_employee_name").val(currentRow.find("td:eq(1)").text());
            $(".pay_month").val($("#month").val());


        });
        $('#itemtbl tbody').on('click', '.discount_item', function() {
            $("#discount_employee_id").val('');
            $("#discount_employee_name").val('');
            $(".pay_month").val('');

            var currentRow = $(this).closest("tr");
            $("#discount_employee_id").val(currentRow.find("td:eq(0)").text());
            $("#discount_employee_name").val(currentRow.find("td:eq(1)").text());
            $(".pay_month").val($("#month").val());



        });

        var employee_id;
        $('#itemtbl tbody').on('click', '.pay_salary', function() {
            employee_id = '';

            $("#salary_employee_id").val('');
            $("#salary_employee_name").val('');
            $("#salary_value").val('');
            $(".pay_month").val($("#month").val());
            $("#operation_flag_outside").val(0);

            var currentRow = $(this).closest("tr");
            employee_id = currentRow.find("td:eq(0)").text();
            $("#salary_employee_id").val(currentRow.find("td:eq(0)").text());
            $("#salary_employee_name").val(currentRow.find("td:eq(1)").text());
            $("#salary_value").val(currentRow.find("td:eq(6)").text());
            $(".pay_month").val($("#month").val());
            $('#operation_flag_outside').change(function(e) {
                if ($(this).prop("checked") == true) {
                    $("#operation_flag_outside").val(1);

                    $.ajax({
                        url: "{{ URL::to('employee_solfa_details') }}/" + employee_id,
                        type: "GET",
                        dataType: "json",

                        success: function(data) {
                            $(".div_operation_flag_outside").append(`
                            <div class="col-lg-6">
                                                    <label for="recipient-name" class="col-form-label">إجمالي السلف :</label>
                                                    <input type="number" class="form-control" name="solfa_total" id="total_solfa"
                                                        onkeypress="return event.charCode >= 48" min="0" required readonly>
                                                </div>
                                    <div class="col-lg-6">
                                        <label for="recipient-name" class="col-form-label">قيمة السداد :</label>
                                        <input type="number" class="form-control" name="solfa_money"
                                            onkeypress="return event.charCode >= 48" min="1" required>
                                    </div>

                `);
                            $("#total_solfa").val('');

                            $("#total_solfa").val(data.raseed);

                        },
                    });



                } else if ($(this).prop("checked") == false) {
                    $("#operation_flag_outside").val(0);
                    $(".div_operation_flag_outside").empty();


                }

            });




        });
    </script>





@endsection