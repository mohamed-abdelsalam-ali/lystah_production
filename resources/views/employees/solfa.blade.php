@extends('layouts.master')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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
    سلف الموظفين
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
            <h1 class="text-center text-info">سلف الموظفين
            </h1>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-head">
                            <div class="row">
                                <div class="col-6  mt-3 text-start">
                                </div>

                                <div class="col-2 px-1 mt-3 text-start">
                                    <button class="btn btn-info" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop_add_solfa"><i class="ri-add-fill"></i>
                                        إضافة سلفة لموظف
                                    </button>
                                </div>

                                <div class="col-2 px-1 mt-3 text-start">
                                    <button class="btn btn-info" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop_pay_solfa"><i class="ri-add-fill"></i>
                                        سداد سلفة لموظف </button>
                                </div>
                                 <div class="col-2 px-1 mt-3 text-start">
                                    <a class="btn btn-info" href="{{ route('employee_solfa_history',$store_id) }}"><i class="ri-add-fill"></i>
                                          عرض تفاصيل سداد السلف  </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="itemtbl" class="display table table-bordered dt-responsive dataTable dtr-inline"
                                style="width:100%">
                                <thead>
                                    <tr>
                                         <th class="d-none">id</th>
                                        <th> رقم الموظف </th>
                                        <th> الاسم </th>
                                        <th> الوظيفة </th>
                                        <th> المرتب </th>
                                        <th>قيمة السلفة</th>
                                        <th> المتبقي </th>
                                        <th>الحالة</th>
                                        <th>التاريخ</th>
                                        <th>ملاحظات</th>
                                        <th>اسم المستخدم</th>


                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="d-none">id</th>
                                        <th> رقم الموظف </th>
                                        <th> الاسم </th>
                                        <th> الوظيفة </th>
                                        <th> المرتب </th>
                                        <th>قيمة السلفة</th>
                                        <th> المتبقي </th>
                                        <th>الحالة</th>
                                        <th>التاريخ</th>
                                        <th>ملاحظات</th>
                                        <th>اسم المستخدم</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    {{-- <--Add_rent--> --}}

            <div class="modal fade" id="staticBackdrop_add_solfa" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">إضافة سلفة لموظف </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('solfa.store') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" class="form-control " name="flag" value="0">

                                        <label for="" class="col-form-label">اختر الموظف</label>
                                        <select class="form-control select2" id="select_employee_id1" name="employee_id"
                                            required>
                                            <option class="text-center" value="" selected disabled>اختر الموظف
                                            </option>

                                            @foreach ($employees as $employee)
                                                <option class="text-center" value="{{ $employee->id }}">
                                                    {{ $employee->employee_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="recipient-name" class="col-form-label">المبلغ :</label>
                                        <input type="number" class="form-control" name="money"
                                            onkeypress="return event.charCode >= 48" min="1" required>
                                    </div>


                                </div>
                                <div class="row">

                                    <div class="col-lg-12">
                                        <label class="col-form-label">تاريخ الاضافة</label>
                                        <input type="text" class="form-control reserve_date" name="date"
                                            value="{{ date('Y-m-d') }}" readonly>

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
            {{-- <--pay_rent--> --}}

            <div class="modal fade" id="staticBackdrop_pay_solfa" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">سداد سلفة لموظف </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('solfa.store') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-lg-12">
                                     <input type="hidden" class="form-control " name="flag"
                                            value="1" >

                                        <label for="" class="col-form-label">اختر الموظف</label>
                                        <select class="form-control select2" id="select_employee_id2" name="employee_id"
                                            required>
                                            <option class="text-center" value="" selected disabled>اختر الموظف
                                            </option>

                                            @foreach ($employees as $employee)
                                                <option class="text-center" value="{{ $employee->id }}">
                                                    {{ $employee->employee_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="recipient-name" class="col-form-label">إجمالي السلف :</label>
                                        <input type="number" class="form-control" name="total" id="total_solfa"
                                            onkeypress="return event.charCode >= 48" min="0" required readonly>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="recipient-name" class="col-form-label">قيمة السداد :</label>
                                        <input type="number" class="form-control" name="money"
                                            onkeypress="return event.charCode >= 48" min="1" required>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-lg-12">
                                        <label class="col-form-label">تاريخ السداد</label>
                                        <input type="text" class="form-control reserve_date" name="date"
                                            value="{{ date('Y-m-d') }}" readonly>

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

        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
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
        var all_solfas = {!! $all_solfas !!}
        var itemtbl = "";

        $(document).ready(function() {
 $("#select_employee_id1").select2({
        dropdownParent: $('#staticBackdrop_add_solfa')
    });
     $("#select_employee_id2").select2({
        dropdownParent: $('#staticBackdrop_pay_solfa')
    });


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
                        "targets": [0]
                    },

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
                        data: "employee.employee_final_salary"
                    },
                    {
                        data: "total_solfa"
                    },
                     {
                        data: "remain"
                    },
                    {
                        data: "finish_flag",
                        "render": function(data) {
                            if (data == 0) {
                                return '<span class="text-success">حالية </span>';
                            } else {
                                return '<span class="text-danger"> انتهت</span>';
                            }
                        }
                    },
                    {
                        data: "date"
                    },
                    {
                        data: "notes"
                    },
                    {
                        data: "user.username"
                    },

                ],



            });

            itemtbl.clear();
            itemtbl.rows.add(all_solfas).draw();
            itemtbl.buttons().container().appendTo('#itemtbl_wrapper .col-md-6:eq(0)');




            itemtbl.buttons().container().appendTo('#itemtbl_wrapper .col-md-6:eq(0)');

        });
    </script>

    <script>
        $('#itemtbl tbody').on('click', '.bonus_item', function() {
            $("#bonus_employee_id").val('');
            $("#bonus_employee_name").val('');
            $("#bonus_month").val();
            var currentRow = $(this).closest("tr");
            $("#bonus_employee_id").val(currentRow.find("td:eq(0)").text());
            $("#bonus_employee_name").val(currentRow.find("td:eq(1)").text());
            $("#bonus_month").val($("#month").val());


        });
        $('#itemtbl tbody').on('click', '.discount_item', function() {
            $("#discount_employee_id").val('');
            $("#discount_employee_name").val('');
            $("#discount_month").val();

            var currentRow = $(this).closest("tr");
            $("#discount_employee_id").val(currentRow.find("td:eq(0)").text());
            $("#discount_employee_name").val(currentRow.find("td:eq(1)").text());
            $("#discount_month").val($("#month").val());



        });
        $('#itemtbl tbody').on('click', '.rent_item', function() {
            $("#rent_employee_id").val('');
            $("#rent_employee_name").val('');
            $("#rent_month").val('');

            var currentRow = $(this).closest("tr");
            $("#rent_employee_id").val(currentRow.find("td:eq(0)").text());
            $("#rent_employee_name").val(currentRow.find("td:eq(1)").text());
            $("#rent_month").val($("#month").val());



        });
        $('#itemtbl tbody').on('click', '.pay_salary', function() {
            $("#salary_employee_id").val('');
            $("#salary_employee_name").val('');
            $("#salary_value").val('');
            $("#salary_month").val('');
            var currentRow = $(this).closest("tr");
            $("#salary_employee_id").val(currentRow.find("td:eq(0)").text());
            $("#salary_employee_name").val(currentRow.find("td:eq(1)").text());
            $("#salary_value").val(currentRow.find("td:eq(6)").text());
            $("#salary_month").val($("#month").val());



        });
    </script>
    <script>
        $('#select_employee_id2').on('change', function() {
            var employee_id = $(this).val();
            // alert(agency_id)

            if (employee_id) {
                $.ajax({
                    url: "{{ URL::to('employee_solfa_details') }}/" + employee_id,
                    type: "GET",
                    dataType: "json",

                    success: function(data) {
                        $("#total_solfa").val('');
                        $("#total_solfa").val(data.raseed);

                    },
                });

            } else {
                console.log('AJAX load did not work');
            }
        });
    </script>






@endsection
