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
    الموظفين
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
            <h1 class="text-center text-info">الموظفين
            </h1>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-head">
                            <div class="row">
                                <div class="col-8  mt-3 text-start">
                                    <h4>إجمالى قيمة المرتبات {{ $salary_total }}</h4>
                                </div>

                                <div class="col-2 px-1 mt-3 text-start">
                                    <button class="btn btn-info" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop_add"><i class="ri-add-fill"></i>
                                        إضافة
                                        بيانات موظف جديد </button>
                                </div>

                            </div>
                        </div>
                        <div class="card-body">
                            <table id="itemtbl" class="display table table-bordered dt-responsive dataTable dtr-inline"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th> اسم الموظف</th>
                                        <th>الوظيفة</th>
                                        <th> العنوان</th>
                                        <th>رقم التليفون</th>
                                        <th>رقم تليفون اخر</th>
                                        <th>الرقم القومي</th>
                                        <th>المرتب الأساسى</th>
                                        <th> قيمة التأمينات</th>
                                        <th> المرتب الفعلى</th>
                                        <th> اسم المخزن</th>
                                        <th class="d-none">store_id</th>
                                        <th class="d-none">role_id</th>
                                        <th>الحالة</th>
                                        <th>سلف</th>

                                        <th></th>
                                        <th></th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                         <th>id</th>
                                        <th> اسم الموظف</th>
                                        <th>الوظيفة</th>
                                        <th> العنوان</th>
                                        <th>رقم التليفون</th>
                                        <th>رقم تليفون اخر</th>
                                        <th>الرقم القومي</th>
                                        <th>المرتب الأساسى</th>
                                        <th> قيمة التأمينات</th>
                                        <th> المرتب الفعلى</th>
                                        <th> اسم المخزن</th>
                                        <th class="d-none">store_id</th>
                                        <th class="d-none">role_id</th>
                                        <th>الحالة</th>
                                        <th>سلف</th>
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
    {{-- <--Edit--> --}}
    <div class="modal fade" id="staticBackdrop_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> تعديل بيانات الموظف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employee.update', 'test') }}" method="post" enctype="multipart/form-data">
                        {{ method_field('patch') }}
                        {{ csrf_field() }}
                        <div class="row">
                            <input type="hidden" id="edit_id" name="id">
                            <div class="col-6">
                                <label for="recipient-name" class="col-form-label">اسم الموظف :
                                </label>
                                <input type="text" class="form-control" id="edit_employee_name" name="employee_name"
                                    required>
                            </div>
                            <div class="col-6">
                                <label for="recipient-name" class="col-form-label"> اسم الوظيفة :</label>

                                <select class="form-select form-select-solid  " id="select_role_edit"
                                    name="employee_role_id" data-control="select2"
                                    data-dropdown-parent="#staticBackdrop_edit" data-allow-clear="false" required>

                                    @foreach ($all_roles as $role)
                                        <option class="text-center
                                    "
                                            value="{{ $role->id }}">
                                            {{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-6">

                                <label for="recipient-name" class="col-form-label">رقم تليفون :</label>
                                <input type="number" class="form-control" id="edit_employee_phone" name="employee_phone"
                                    min="0" required>
                            </div>
                            <div class="col-6">
                                <label for="recipient-name" class="col-form-label">رقم تليفون اخر :</label>
                                <input type="text" class="form-control" id="edit_employee_telephone"
                                    name="employee_telephone" min="0" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">

                                <label for="recipient-name" class="col-form-label"> عنوان الموظف :</label>
                                <input type="text" class="form-control" id="edit_employee_address"
                                    name="employee_address" required>
                            </div>
                            <div class="col-6">
                                <label for="recipient-name" class="col-form-label">اسم
                                    المخزن:</label>
                                <select class="form-select form-select-solid  " name="store_id" id="edit_store_id"
                                    data-control="select2" data-dropdown-parent="#staticBackdrop_edit"
                                    data-allow-clear="false" required>
                                    <option class="text-center" value="" selected disabled>اختر اسم المخزن</option>
                                    @foreach ($all_stores as $all_store)
                                        <option class="text-center
                                    "
                                            value="{{ $all_store->id }}">
                                            {{ $all_store->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="row">
                                <label for="recipient-name" class="col-form-label">الرقم القومي:</label>
                                <input type="number" class="form-control" id="edit_employee_national_id"
                                    name="employee_national_id" min="0" required>

                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label for="recipient-name" class="col-form-label"> المرتب الأساسي:</label>
                                    <input type="number" class="form-control" id="edit_employee_salary"
                                        name="employee_salary" min="0" required>
                                </div>
                                <div class="col-4">
                                    <label for="recipient-name" class="col-form-label"> قيمة التأمينات:</label>
                                    <input type="number" class="form-control insurance_value1" id="edit_insurance_value"
                                        name="insurance_value" min="0" required>
                                </div>
                                <div class="col-4">
                                    <label for="recipient-name" class="col-form-label">الفعلى المرتب:</label>
                                    <input type="number" class="form-control" id="edit_employee_final_salary" readonly
                                        name="employee_final_salary" min="0" required>
                                </div>
                            </div>
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

    {{-- <--Delete--> --}}
    <div class="modal fade" id="staticBackdrop_delete" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> حذف الموظف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employee.destroy', 'test') }}" method="post">
                        {{ method_field('Delete') }}
                        {{ csrf_field() }}
                        <div class="form-group">
                            <h5>هل انت متاكد من عملية الحذف ؟</h5><br>
                            <input type="hidden" name="id" id="id_delete" value="">
                            <input type="text" class="form-control  font1" id="name_delete" name="diagnosis_name"
                                value="" disabled>
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

    {{-- <--come_back--> --}}
    <div class="modal fade" id="staticBackdrop_come_back" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> رجوع الموظف للعمل </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employee.edit', 'test') }}" method="post">
                        {{ method_field('GET') }}
                        {{ csrf_field() }}
                        <div class="form-group">
                            <h5>هل انت متاكد من عملية رجوع الموظف للعمل ؟</h5><br>
                            <input type="hidden" name="id" id="id_come_back" value="">
                            <input type="text" class="form-control  font1" id="name_come_back" name="diagnosis_name"
                                value="" disabled>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn mb-2 btn-primary">رجوع</button>
                    <button type="button" class="btn mb-2 btn-secondary" data-bs-dismiss="modal">غلق</button>
                </div>
                </form>

            </div>
        </div>
    </div>
    {{-- <--Add--> --}}

    <div class="modal fade" id="staticBackdrop_add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">إضافة موظف جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employee.store') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-6">
                                <label for="recipient-name" class="col-form-label">اسم الموظف :
                                </label>
                                <input type="text" class="form-control" id="edit_employee_name" name="employee_name"
                                    required>
                            </div>
                            <div class="col-6">
                                <label for="recipient-name" class="col-form-label"> اسم الوظيفة
                                    :</label>

                                <select class="form-select form-select-solid  " name="employee_role_id" required>
                                    <option class="text-center" value="" selected disabled>اختر
                                        الوظيفة
                                    </option>
                                    @foreach ($all_roles as $role)
                                        <option class="text-center" value="{{ $role->id }}">
                                            {{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-6">

                                <label for="recipient-name" class="col-form-label">رقم تليفون
                                    :</label>
                                <input type="number" class="form-control" id="edit_employee_phone"
                                    name="employee_phone" min="0" required>
                            </div>
                            <div class="col-6">
                                <label for="recipient-name" class="col-form-label">رقم تليفون اخر
                                    :</label>
                                <input type="number" class="form-control" id="edit_employee_telephone"
                                    name="employee_telephone" min="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">

                                <label for="recipient-name" class="col-form-label"> عنوان الموظف
                                    :</label>
                                <input type="text" class="form-control" name="employee_address" required>
                            </div>

                            <div class="col-6">
                                <label for="recipient-name" class="col-form-label">اسم
                                    المخزن:</label>
                                <select class="form-select form-select-solid  " name="store_id" data-control="select2"
                                    data-dropdown-parent="#staticBackdrop_edit" data-allow-clear="false" required>
                                    <option class="text-center" value="" selected disabled>اختر اسم المخزن</option>
                                    @foreach ($all_stores as $all_store)
                                        <option class="text-center
                                    "
                                            value="{{ $all_store->id }}">
                                            {{ $all_store->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="row">
                            <label for="recipient-name" class="col-form-label">الرقم
                                القومي:</label>
                            <input type="text" class="form-control" id="edit_employee_national_id"
                                name="employee_national_id" min="0" required>

                        </div>

                        <div class="row">
                            <div class="col-4">
                                <label for="recipient-name" class="col-form-label"> المرتب
                                    الأساسي:</label>
                                <input type="number" class="form-control" id="employee_salary" name="employee_salary"
                                    min="0" required>
                            </div>
                            <div class="col-4">
                                <label for="recipient-name" class="col-form-label"> قيمة
                                    التأمينات:</label>
                                <input type="number" class="form-control insurance_value" id="insurance_value"
                                    name="insurance_value" min="0" required>
                            </div>
                            <div class="col-4">
                                <label for="recipient-name" class="col-form-label">الفعلى
                                    المرتب:</label>
                                <input type="number" class="form-control" id="employee_final_salary" readonly
                                    name="employee_final_salary" min="0" required>
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
        var all_employees = {!! $all_employees !!};
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
                    "targets": [11, 12]
                }, {
                    "className": "text-nowrap",
                    "targets": [1, 3]
                }, {
                    "className": "dt-center",
                    "targets": "_all"
                }],


                columns: [

                    {
                        data: "id"
                    }, {
                        data: "employee_name"
                    }, {
                        data: "role.name"
                    }, {
                        data: "employee_address"
                    }, {
                        data: "employee_phone"
                    }, {
                        data: "employee_telephone"
                    }, {
                        data: "employee_national_id"
                    }, {
                        data: "employee_salary"
                    }, {
                        data: "insurance_value"
                    }, {
                        data: "employee_final_salary"
                    },
                    {
                        data: "store.name"
                    },
                    {
                        data: "store_id"
                    }, {
                        data: "role.id"
                    }, {
                        data: "flag_finish_job",
                        "render": function(data) {
                            if (data == 0) {
                                return '<span class="text-success">حالي</span> ';


                            } else {
                                return '<span class="text-danger">انتهي</span> ';

                            }
                        }
                    },
                     {
                            data: "raseed",
                            "render": function(data) {
                            if (data == 0) {
                                return data;


                            } else {
                                return `<span class="text-danger">${data}</span> `;

                            }
                        }
                        }, {

                        data: null,

                        className: "dt-center editor-editt",

                        defaultContent: '<button  class="btn btn-primary edit_item " data-bs-toggle="modal" data-bs-target="#staticBackdrop_edit">تعديل</button>',

                        orderable: false

                    }, {

                        data: null,

                        className: "dt-center editor-editt",


                        orderable: false,
                        render: function(data, type, row, meta) {
                            if (row.flag_finish_job == 0) {
                                return `
                                    <button class="btn btn-danger delete_item"   data-bs-toggle="modal" data-bs-target="#staticBackdrop_delete">حذف</button>
                                                    `;
                            } else {
                                return `
                                    <button class="btn btn-success come_back_item"   data-bs-toggle="modal" data-bs-target="#staticBackdrop_come_back">تغير الحالة</button>
                                        `;



                            }

                        }

                    },
                ],



            });
            itemtbl.clear();
            itemtbl.rows.add(all_employees).draw();
            itemtbl.buttons().container().appendTo('#itemtbl_wrapper .col-md-6:eq(0)');

        });
    </script>
    <script>
        $('#itemtbl tbody').on('click', '.edit_item', function() {
            $("#edit_id").val("");
            $("#edit_employee_name").val("");
            $("#select_role_edit").val("");
            $("#edit_employee_phone").val("");
            $("#edit_employee_telephone").val("");
            $("#edit_employee_address").val("");
            $("#edit_employee_national_id").val("");
            $("#edit_employee_salary").val("");
            $("#edit_insurance_value").val("");
            $("#edit_employee_final_salary").val("");
            $("#edit_store_id").val("");
            var currentRow = $(this).closest("tr");
            $("#edit_id").val(currentRow.find("td:eq(0)").text());
            $("#edit_employee_name").val(currentRow.find("td:eq(1)").text());
            $("#select_role_edit").val(currentRow.find("td:eq(12)").text()).trigger('change');
            $("#edit_employee_phone").val(currentRow.find("td:eq(4)").text());
            $("#edit_employee_telephone").val(currentRow.find("td:eq(5)").text());
            $("#edit_employee_address").val(currentRow.find("td:eq(3)").text());
            $("#edit_employee_national_id").val(currentRow.find("td:eq(6)").text());
            $("#edit_employee_salary").val(currentRow.find("td:eq(7)").text());
            $("#edit_insurance_value").val(currentRow.find("td:eq(8)").text());
            $("#edit_employee_final_salary").val(currentRow.find("td:eq(9)").text());
            $("#edit_store_id").val(currentRow.find("td:eq(11)").text()).trigger('change');


        });

        $('#itemtbl tbody').on('click', '.delete_item', function() {
            $("#id_delete").val('');
            $("#name_delete").val('');
            var currentRow = $(this).closest("tr");
            $("#id_delete").val(currentRow.find("td:eq(0)").text());
            $("#name_delete").val(currentRow.find("td:eq(1)").text());



        });

        $('#itemtbl tbody').on('click', '.come_back_item', function() {
            $("#id_come_back").val('');
            $("#name_come_back").val('');
            var currentRow = $(this).closest("tr");
            $("#id_come_back").val(currentRow.find("td:eq(0)").text());
            $("#name_come_back").val(currentRow.find("td:eq(1)").text());



        });
    </script>
    <script>
        $('.insurance_value').bind("keyup ", function(event) {

            if ($("#insurance_value").val() >= $("#employee_salary").val() * 0.4) {
                alert("قيمة التأمينات تخطت 40 % من المرتب الأساسى")
                $("#insurance_value").val("");
                $("#employee_final_salary").val("");
            } else {

                var x = $("#employee_salary").val() - $("#insurance_value").val();
                $("#employee_final_salary").val(x);
            }
        });
    </script>
    <script>
        $('.insurance_value1').bind("keyup ", function(event) {

            if (parseInt($("#edit_insurance_value").val()) >= parseInt($("#edit_employee_salary").val() * 0.4)) {
                alert("قيمة التأمينات تخطت 40 % من المرتب الأساسى")
                $("#edit_insurance_value").val("");
                $("#edit_employee_final_salary").val("");
            } else {
                var x = $("#edit_employee_salary").val() - $("#edit_insurance_value").val();
                $("#edit_employee_final_salary").val(x);
            }
        });
    </script>





@endsection
