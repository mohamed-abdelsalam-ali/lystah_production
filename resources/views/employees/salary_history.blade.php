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
    سجل صرف المرتبات
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
            <h1 class="text-center text-info">سجل صرف المرتبات
            </h1>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-head">
                            <div class="row">
                                <div class="col-8  mt-3 text-start">
                                </div>

                                <div class="col-2 px-1 mt-3 text-start">

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
                                        <th>اسم الموظف</th>
                                        <th>المرتب</th>
                                        <th> مرتب شهر</th>
                                        <th>تاريخ استلام المرتب</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="d-none">id</th>
                                        <th> رقم الموظف </th>
                                        <th>اسم الموظف</th>
                                        <th>المرتب</th>
                                        <th> مرتب شهر</th>
                                        <th>تاريخ استلام المرتب</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>


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
            var historyy = {!!$historyy!!}
            var itemtbl = "";
            $(document).ready(function() {
                var now = new Date();
                var day = ("0" + now.getDate()).slice(-2);
                var month = ("0" + (now.getMonth() + 1)).slice(-2);
                var today = now.getFullYear() + "-" + (month) + "-" + (day);
                $('.reserve_date').val(today);
                itemtbl = $("#itemtbl").DataTable({
                    "oLanguage": {
                        "sSearch": "البحث"
                    },
                    lengthChange: false,
                    "responsive": {
                        details: true,
                    },






                    "columnDefs": [{
                        "className": "d-none",
                        "targets": [0]
                    }, {
                        "className": "dt-center",
                        "targets": "_all"
                    }],


                    columns: [{
                            data: "id"
                        }, {
                            data: "employee.id"
                        }, {
                            data: "employee.employee_name"
                        },

                        {
                            data: "salary_month"
                        }, {
                            data: "month"

                        }, {
                            data: "date"
                        },
                        // {
                        //     data: "created_by"
                        // },



                    ],



                });
                itemtbl.clear();
                itemtbl.rows.add(historyy).draw();
            });
        </script>





    @endsection
