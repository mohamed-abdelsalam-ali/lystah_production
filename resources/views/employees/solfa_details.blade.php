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
    سداد سلف الموظفين
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
            <h1 class="text-center text-info">     سداد سلف الموظفين


            </h1>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-head">
                            <div class="row">


                                <div class="col-4 mt-3 offset-lg-8 px-1">
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
                        </div>
                        <div class="card-body">
                            <table id="itemtbl" class="display table table-bordered dt-responsive dataTable dtr-inline"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th> رقم الموظف </th>
                                        <th> الاسم </th>
                                        <th> الوظيفة </th>
                                        <th> الإجمالي </th>
                                        <th>قيمة السداد</th>
                                        <th>المتبقي </th>
                                        <th>تاريخ السداد </th>
                                        <th>اسم المستخدم</th>


                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th> رقم الموظف </th>
                                        <th> الاسم </th>
                                        <th> الوظيفة </th>
                                        <th> الإجمالي </th>
                                        <th>قيمة السداد</th>
                                        <th>المتبقي </th>
                                        <th>تاريخ السداد </th>
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
    {{-- <-staticBackdrop_add_money--> --}}

    <div class="modal fade" id="staticBackdrop_add_money" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> إضافة ملاحظة جديدة
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('notes.store') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <label for="recipient-name" class="col-form-label"> الإسم:</label>
                                <input type="text" class="form-control" name="notes" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">ملاحظات</label>
                            <textarea class="form-control" rows="3" name="desc" required></textarea>
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

    <div class="modal fade" id="staticBackdrop_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">تعديل
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('notes.update', 'test') }}" method="post" enctype="multipart/form-data">
                        {{ method_field('patch') }}
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" class="form-control" name="id" id="edit_id" required>

                                <label for="recipient-name" class="col-form-label"> الإسم:</label>
                                <input type="text" class="form-control" name="notes" id="edit_notes" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">ملاحظات</label>
                            <textarea class="form-control" rows="3" name="desc" id="edit_desc" required></textarea>
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
        var solfa_details = '';
        var itemtbl = "";
        $('#select_employee_id2').on('change', function() {
            var employee_id = $(this).val();
            itemtbl.clear();
            $.ajax({
                url: "{{ URL::to('employee_solfa_history_details') }}/" + employee_id,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    solfa_details = data
                    itemtbl.rows.add(data).draw();



                },
            });

        });
        $(document).ready(function() {
            $("#select_employee_id2").select2({});
            itemtbl = $("#itemtbl").DataTable({

                lengthChange: false,
                "oLanguage": {
                    "sSearch": "البحث"
                },

                buttons: [{
                        extend: 'excel',
                        text: 'Excel <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>'
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>'
                    },

                    'copy', 'colvis',
                    'pdf', {
                        extend: 'print',
                        text: 'طباعة',
                        className: 'bg-danger',
                        exportOptions: {
                            columns: [1, 2]
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
                        data: "employee_id"
                    },
                    {
                        data: "employee.employee_name"
                    },
                    {
                        data: "employee.role.name"
                    },

                    {
                        data: "total"
                    },
                    {
                        data: "amount"
                    },
                    {
                        data: "employee.raseed",
                        render: function(data, type, row, meta) {
                            return row.total - row.amount;
                        }


                    },
                    {
                        data: "date"
                    },
                    {
                        data: "user.username"
                    },

                ],

            });
            itemtbl.clear();
            itemtbl.rows.add(solfa_details).draw();

            itemtbl.buttons().container().appendTo('#itemtbl_wrapper .col-md-6:eq(0)');

        });


        $("#itemtbl_filter").children().find('label').css('float', 'left').addClass('pt-2');
        $("#itemtbl_filter").children().find('input').removeAttr('class').addClass('form-control');
    </script>







@endsection
