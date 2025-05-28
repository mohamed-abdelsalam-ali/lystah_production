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
    حسابات البنوك
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
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0"> حسابات البنوك </h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">حسابات البنوك </li>
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


                                <div class="col-4 mt-3 offset-lg-8 px-1">
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop_add_money"><i class="ri-add-fill"></i>
                                        إضافة حساب بنكي جديد</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                                <table id="itemtbl" class=" table table-striped table-bordered cell-border dataTable no-footer dtr-inline" style="width:100%">

                                    <thead style="background:#5fcee78a" class="text-nowrap" >
                                        <tr>
                                        <th class="d-none">id</th>
                                        <th class="text-center">اسم الحساب</th>
                                        <th class="text-center">رقم الحساب</th>

                                        <th></th>


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
                    <h5 class="modal-title" id="staticBackdropLabel"> إضافة حساب جديد
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('bank_type.store') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <label for="recipient-name" class="col-form-label"> الإسم:</label>
                                <input type="text" class="form-control" name="bank_name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="recipient-name" class="col-form-label"> رقم الحساب:</label>
                                <input type="number" class="form-control" name="account_number"
                                    onkeypress="return event.charCode >= 48" min="0" required>
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
                    <form action="{{ route('bank_type.update', 'test') }}" method="post" enctype="multipart/form-data">
                        {{ method_field('patch') }}
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" class="form-control" name="id" id="edit_id">

                                <label for="recipient-name" class="col-form-label"> الإسم:</label>
                                <input type="text" class="form-control" name="bank_name" id="bank_name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="recipient-name" class="col-form-label"> رقم الحساب:</label>
                                <input type="number" class="form-control" name="account_number" id="account_number"
                                    onkeypress="return event.charCode >= 48" min="0" required>
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
        var all_bank_types = {!! $all_bank_types !!}
        var itemtbl = "";
        $(document).ready(function() {
            itemtbl = $("#itemtbl").DataTable({

                lengthChange: false,
                dom: 'Bfrtip',


                buttons: [


                    {
                        extend: 'print',
                        className: 'dt-button buttons-print',
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
                        "className": "text-nowrap",
                        "targets": [1, 2]
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
                        data: "bank_name"
                    },
                    {
                        data: "account_number"
                    },
                    {

                        data: null,

                        className: "dt-center editor-editt",

                        defaultContent: '<button  class="btn btn-primary edit_item " data-bs-toggle="modal" data-bs-target="#staticBackdrop_edit">تعديل</button>',

                        orderable: false

                    },



                ],

            });
            itemtbl.clear();
            itemtbl.rows.add(all_bank_types).draw();


        });



    </script>
    <script>
        $('#itemtbl tbody').on('click', '.edit_item', function() {
            $("#edit_id").val('');

            $("#bank_name").val('');
            $("#account_number").val('');


            var currentRow = $(this).closest("tr");

            $("#edit_id").val(currentRow.find("td:eq(0)").text());

            $("#bank_name").val(currentRow.find("td:eq(1)").text());
            $("#account_number").val(currentRow.find("td:eq(2)").text());

        });
    </script>






@endsection
