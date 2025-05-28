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
    تفاصيل المرتبات
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
            <h1 class="text-center text-info">تفاصيل المرتبات
            </h1>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-head">
                            <div class="row">
                                <div class="col-8  mt-3 text-start">
                                </div>

                                <div class="col-2 px-1 mt-3 text-start">
                                    <div id="month_text">
                                    </div>
                                    <div>
                                        <label class="col-form-label"> اختر شهر القبض </label>
                                        <input type="month" class="form-control w-100" name="month" id="month">

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-body">
                            <table id="itemtbl" class="display table table-bordered dt-responsive dataTable dtr-inline"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="d-none"> id </th>
                                        <th> رقم الموظف </th>
                                        <th> الاسم </th>
                                        <th> الوظيفة </th>
                                        <th class="d-none"> flag_type </th>
                                        <th> الحالة </th>
                                        <th>الشهر المضاف عليه </th>
                                        <th>المبلغ </th>
                                        <th> تاريخ الاضافة </th>
                                        <th>ملاحظات</th>
                                        <th></th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="d-none"> id </th>
                                        <th> رقم الموظف </th>
                                        <th> الاسم </th>
                                        <th> الوظيفة </th>
                                        <th class="d-none"> flag_type </th>
                                        <th> الحالة </th>
                                        <th>الشهر المضاف عليه </th>
                                        <th>المبلغ </th>
                                        <th> تاريخ الاضافة </th>
                                        <th>ملاحظات</th>
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
    {{-- <--Edit--> --}}
    <div class="modal fade" id="staticBackdrop_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> تعديل </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employee_salary.update', 'test') }}" method="post"
                        enctype="multipart/form-data">
                        {{ method_field('patch') }}
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" name="id" id="edit_id">
                                <label for="recipient-name" class="col-form-label">رقم الموظف :</label>
                                <input type="number" class="form-control" id="edit_employee_id" name="employee_id"
                                    readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="recipient-name" class="col-form-label">اسم الموظف :</label>
                                <input type="text" class="form-control" id="edit_employee_name" name="employee_name"
                                    readonly>
                            </div>

                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="recipient-name" class="col-form-label">المبلغ :</label>
                                <input type="number" class="form-control" name="money" id="edit_money">
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label">شهر خصم المرتب </label>
                                <input type="month" class="form-control " name="month" value="{{ date('Y-m') }}"
                                    min="{{ date('Y-m') }}" id="edit_month">

                            </div>

                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-form-label">نوع الإضافة </label>
                                <select class="form-select form-select-solid" id="edit_flag_type" name="flag_type"
                                    data-control="select2" data-dropdown-parent="#staticBackdrop_edit"
                                    data-allow-clear="false">
                                    <option value="0">منحة</option>
                                    <option value="1">خصم</option>
                                    <option value="2">سلفة</option>
                                </select>

                            </div>

                            <div class="col-md-6">
                                <label class="col-form-label">تاريخ الاضافة</label>
                                <input type="text" class="form-control reserve_date" name="date" readonly>

                            </div>

                        </div>
                        <div class="row">
                            <label class="col-form-label">ملاحظات </label>
                            <textarea name="notes" id="edit_notes" cols="3" rows="3"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn mb-2 btn-primary">تعديل</button>
                    <button type="button" class="btn mb-2 btn-secondary" data-bs-dismiss="modal">غلق</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    {{-- <--Delte--> --}}
    <div class="modal fade" id="staticBackdrop_delete" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> حذف </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employee_salary.destroy', 'test') }}" method="post">
                        {{ method_field('Delete') }}
                        {{ csrf_field() }}
                        <div class="form-group">
                            <h5>هل انت متاكد من عملية الحذف ؟</h5><br>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="hidden" name="id" id="delete_id">
                                    <label for="recipient-name" class="col-form-label">رقم الموظف :</label>
                                    <input type="number" class="form-control" id="delete_employee_id"
                                        name="employee_id" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="recipient-name" class="col-form-label">اسم الموظف :</label>
                                    <input type="text" class="form-control" id="delete_employee_name"
                                        name="employee_name" readonly>
                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="recipient-name" class="col-form-label">المبلغ :</label>
                                    <input type="number" class="form-control" name="money" id="delete_money"
                                        readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label">شهر المرتب </label>
                                    <input type="text" class="form-control " name="month" id="delete_month"
                                        readonly>

                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="col-form-label">نوع الإضافة </label>
                                    <input type="text" class="form-control" id="delete_flag_type" readonly>

                                </div>

                                <div class="col-md-6">
                                    <label class="col-form-label">تاريخ الاضافة</label>
                                    <input type="text" class="form-control " id="delete_date" name="date"
                                        readonly>

                                </div>

                            </div>
                            <div class="row">
                                <label class="col-form-label">ملاحظات </label>
                                <textarea name="notes" id="delete_notes" cols="3" rows="3" readonly></textarea>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn mb-2 btn-danger">حذف</button>
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
            var actions = {!! $actions !!}
            var employee_id = {!! $employee_id !!}
            // alert(employee_id)
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
                    "columnDefs": [{
                            "className": "d-none",
                            "targets": [0, 4]
                        },
                        // {
                        //     "className": "text-nowrap",
                        //     "targets": [2, 9, 12, 11]
                        // },
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
                            data: "employee_id"
                        },
                        {
                            data: "employee.employee_name"
                        },

                        {
                            data: "employee.role.name"
                        },
                        {
                            data: "flag_type"
                        },
                        {
                            data: "flag_type",
                            "render": function(data) {
                                if (data == 0) {
                                    return '<span class="text-success">منحة</span> ';


                                } else if (data == 1) {
                                    return '<span class="text-danger">خصم</span> ';
                                } else {
                                    return '<span class="text-primary">سلفة</span> ';

                                }
                            }
                        },

                        {
                            data: "month"
                        },
                        {
                            data: "money"
                        },
                        {
                            data: "date"
                        },
                        {
                            data: "notes"
                        },

                        {

                            data: null,

                            className: "dt-center editor-editt",

                            defaultContent: `

                                            <button class="btn btn-danger font1  text-center delete_item text-white" data-bs-toggle="modal" data-bs-target="#staticBackdrop_delete"> حذف </button>
                            `,
                            // <li><button class="dropdown-item font1  text-center  edit_item text-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop_edit"><i class="fa-solid fa-pen-to-square"></i> </button></li>

                            orderable: false,


                        },

                    ],



                });
                itemtbl.clear();
                itemtbl.rows.add(actions).draw();
                $('#month').on('change', function() {
                    var month = $(this).val();
                    if (month) {
                        $.ajax({
                            url: "{{ URL::to('get_employee_salary_details') }}/",
                            type: "GET",
                            data: {
                                employee_id: employee_id,
                                month: month,
                            },
                            dataType: "json",
                            success: function(data) {

                                itemtbl.clear();

                                itemtbl.rows.add(data).draw();

                            },
                        });

                    } else {
                        itemtbl.clear();

                        itemtbl.rows.add(actions).draw();
                    }
                });
                itemtbl.buttons().container().appendTo('#itemtbl_wrapper .col-md-6:eq(0)');


            });
        </script>

        <script>
            $('#itemtbl tbody').on('click', '.edit_item', function() {


                $("#edit_id").val("");
                $("#edit_employee_id").val("");
                $("#edit_employee_name").val("");
                $("#edit_money").val("");
                $("#edit_month").val("");
                $("#edit_notes").val("");
                $("#edit_flag_type").val("");


                var currentRow = $(this).closest("tr");
                var flag_type = currentRow.find("td:eq(4)").text();
                $("#edit_id").val(currentRow.find("td:eq(0)").text());
                $("#edit_employee_id").val(currentRow.find("td:eq(1)").text());
                $("#edit_employee_name").val(currentRow.find("td:eq(2)").text());
                if (flag_type == 0) {
                    $("#edit_money").val(currentRow.find("td:eq(7)").text());

                } else {
                    $("#edit_money").val(currentRow.find("td:eq(7)").text() * -1);


                }
                $("#edit_month").val(currentRow.find("td:eq(6)").text());
                $("#edit_notes").val(currentRow.find("td:eq(9)").text());
                $("#edit_flag_type").val(currentRow.find("td:eq(4)").text()).trigger('change');

            });

            $('#itemtbl tbody').on('click', '.delete_item', function() {
                $("#delete_id").val('');
                $("#delete_employee_id").val('');
                $("#delete_employee_name").val('');
                $("#delete_money").val('');
                $("#delete_month").val('');
                $("#delete_flag_type").val('');
                $("#delete_date").val('');
                $("#delete_notes").val('');
                var currentRow = $(this).closest("tr");
                $("#delete_id").val(currentRow.find("td:eq(0)").text());
                $("#delete_employee_id").val(currentRow.find("td:eq(1)").text());
                $("#delete_employee_name").val(currentRow.find("td:eq(2)").text());
                $("#delete_money").val(currentRow.find("td:eq(7)").text());
                $("#delete_month").val(currentRow.find("td:eq(6)").text());
                $("#delete_flag_type").val(currentRow.find("td:eq(5)").text());
                $("#delete_date").val(currentRow.find("td:eq(8)").text());
                $("#delete_notes").val(currentRow.find("td:eq(9)").text());


            });
        </script>





    @endsection
