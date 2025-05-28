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
    Models
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
            <h1 class="text-center text-info">Models
            </h1>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-head">
                            <div class="row">
                                <div class="col-8"></div>
                                {{-- <div class="col-2 p-0 mt-3 text-end">
                                    <a class="btn btn-info" href="customSearch">Custom Search<i
                                            class="ri-user-search-fill"></i></a>
                                </div> --}}
                                <div class="col-2 px-1 mt-3 text-start">
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Add
                                        New <i class="ri-add-fill"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="itemtbl" class="display table table-bordered dt-responsive dataTable dtr-inline"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="d-none">model_id</th>
                                        <th class="sorting sorting_asc">Name</th>
                                        <th class="d-none">brand_id</th>
                                        <th>Brand Name</th>
                                        <th class="d-none">brand_type_id</th>
                                        <th>Type </th>
                                        <th>Image</th>
                                        <th>Description</th>
                                        <th></th>
                                        <th></th>


                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="d-none">model_id</th>
                                        <th class="sorting sorting_asc">Name</th>
                                        <th class="d-none">brand_id</th>
                                        <th>Brand Name</th>
                                        <th class="d-none">brand_type_id</th>
                                        <th>Type </th>
                                        <th>Image</th>
                                        <th>Description</th>
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
    {{-- <edit_model> --}}
    <div class="modal fade" id="staticBackdrop_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit model</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('model.update', 'test') }}" method="post" enctype="multipart/form-data">
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row p-3">
                                    <div class="col-12">
                                        <input name="model_id" id="model_id" type="hidden" class="form-control" required="">

                                        <label for="recipient-name" class="col-form-label"> model Name </label>
                                        <input name="name" id="model_name" type="text" class="form-control" required="">
                                    </div>

                                </div>
                                <div class="row p-3">
                                    <div class="col-12">

                                        <label for="recipient-name" class="col-form-label"> Brand Name </label>
                                        <select name="brand_id" id="brand_id_edit"
                                            class="selectpicker button btn btn-light form-control">
                                            @foreach ($brands as $brand)
                                                <option class="text-center" value="{{ $brand->id }}">
                                                    {{ $brand->name }}</option>
                                            @endforeach

                                        </select>
                                    </div>

                                </div>
                                <div class="row p-3">
                                    <div class="col-12">

                                        <label for="recipient-name" class="col-form-label"> Brand Name </label>
                                        <select name="type_id" id="brand_type_id_edit"
                                            class="selectpicker button btn btn-light form-control">
                                            @foreach ($brand_types as $brand_type)
                                                <option class="text-center" value="{{ $brand_type->id }}">
                                                    {{ $brand_type->name }}</option>
                                            @endforeach

                                        </select>
                                    </div>

                                </div>
                                <div class="row pt-3  d-none" id="div_image_edit">
                                    <img style="width: 200px; height: 100px;" class="displayImagee" src=""
                                        class="rounded border" alt="">
                                </div>
                                <input type="file" class="form-control" name="mod_img_name" id="model_image_edit">
                                <div class="row">
                                    <label for="recipient-name" class="col-form-label"> Description </label>
                                    <div class="col-12">
                                        <textarea name="desc" id="model_desc" cols="30" rows="5" class="form-control"></textarea>
                                    </div>
                                </div>





                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- <delete_model> --}}
    <div class="modal fade" id="staticBackdrop_delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Delete model</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('model.destroy', 'test') }}" method="post">
                    {{ method_field('Delete') }}
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row p-3">
                                    <div class="col-12">
                                        <input id="model_id_delete" name="model_id" type="hidden" class=""
                                            required="">
                                        <label for="recipient-name" class="col-form-label"> سيتم حذف هذا الصنف </label>

                                        <input id="model_name_delete" name="name" type="text" class="form-control"
                                            readonly>
                                    </div>

                                </div>


                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- <Add_model> --}}
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> Add New model </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('model.store') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row p-3">
                                    <div class="col-12">

                                        <label for="recipient-name" class="col-form-label"> model Name </label>
                                        <input name="name" type="text" class="form-control" required="">
                                    </div>

                                </div>
                                <div class="row p-3">
                                    <div class="col-12">

                                        <label for="recipient-name" class="col-form-label"> Brand Name </label>
                                        <select name="brand_id" id="brand_id_add"
                                            class="selectpicker button btn btn-light form-control">
                                            @foreach ($brands as $brand)
                                                <option class="text-center" value="{{ $brand->id }}">
                                                    {{ $brand->name }}</option>
                                            @endforeach

                                        </select>
                                    </div>

                                </div>
                                <div class="row p-3">
                                    <div class="col-12">

                                        <label for="recipient-name" class="col-form-label"> Brand Name </label>
                                        <select name="type_id" id="brand_type_id_add"
                                            class="selectpicker button btn btn-light form-control">
                                            @foreach ($brand_types as $brand_type)
                                                <option class="text-center" value="{{ $brand_type->id }}">
                                                    {{ $brand_type->name }}</option>
                                            @endforeach

                                        </select>
                                    </div>

                                </div>
                                <div class="row pt-3 d-none" id="div_image_model">
                                    <img style="width: 200px; height: 100px;" class="add_logo_image" src=""
                                        class="rounded border" alt="">
                                </div>
                                <input type="file" class="form-control" id="add_logo" name="mod_img_name">
                                <div class="row">
                                    <label for="recipient-name" class="col-form-label"> Description </label>
                                    <div class="col-12">
                                        <textarea name="desc" cols="30" rows="5" class="form-control"></textarea>
                                    </div>
                                </div>





                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    {{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}

    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
     --}}
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>

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
        var all_models = {!! $all_models !!}
        var itemtbl = "";
        $(document).ready(function() {


            itemtbl = $("#itemtbl").DataTable({
                "oLanguage": {
                    "sSearch": "البحث"
                },
                lengthChange: false,
                "responsive": {
                    details: true,
                },
                buttons: [{
                        extend: 'excel',
                        text: 'Excel <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>'
                    },
                    {
                        extend: 'print',
                        text: 'طباعة',
                        className: 'bg-danger',
                        exportOptions: {
                            // columns: [9, 8, 7, 6, 5, 2, 1]
                            columns: [1, 2, 5, 7, 9, 10, 11, 13, 14, 15, 16]

                        },
                        title: function() {
                            var printTitle = 'كشف حجوزات اليوم ';
                            return printTitle
                        },
                        // autoPrint: true,

                        customize: function(win) {
                            $(win.document.body).find('th').addClass('display').css('text-align',
                                'center');
                            $(win.document.body).find('table').addClass('mr-5');
                            $(win.document.body).find('table').addClass('display').css('font-size',
                                '20px');
                            $(win.document.body).find('table').addClass('display').css('text-align',
                                'center');
                            $(win.document.body).find('tr:nth-child(odd) td').each(function(index) {
                                $(this).css('background-color', '#D0D0D0');
                            });
                            $(win.document.body).find('h1').css('text-align', 'center');
                            $(win.document.body).css('direction', 'rtl');

                        }
                    }

                ],
                "columnDefs": [{
                        "className": "d-none",
                        "targets": [0, 2, 4]
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
                        data: "name"
                    },
                    {
                        data: "brand_id"
                    },
                    {
                        data: "brand.name"
                    },
                    {
                        data: "type_id"
                    },
                    {
                        data: "brand_type.name"
                    },
                    {
                        data: "mod_img_name",
                        "render": function(data) {
                            if (data) {
                                return '<img  class="avatar-img img-thumbnail rounded-5 itemimg" src="model_images/' +
                                    data +
                                    '" width="40px">';


                            } else {
                                return '<span class="text-primary">لا يوجد صورة</span> ';

                            }
                        }
                    },
                    {
                        data: "desc"
                    },
                    {

                        data: null,

                        className: "dt-center editor-editt",

                        defaultContent: '<button  class="btn btn-primary edit_item " data-bs-toggle="modal" data-bs-target="#staticBackdrop_edit">Edit</button>',

                        orderable: false

                    },
                    {

                        data: null,

                        className: "dt-center editor-editt",

                        defaultContent: '<button class="btn btn-danger delete_item"   data-bs-toggle="modal" data-bs-target="#staticBackdrop_delete">Delete</button>',

                        orderable: false

                    }

                ],



            });
            itemtbl.clear();
            itemtbl.rows.add(all_models).draw();
            itemtbl.buttons().container().appendTo('#itemtbl_wrapper .col-md-6:eq(0)');

        });
    </script>
    <script>
        $('#itemtbl tbody').on('click', '.edit_item', function() {
            $("#model_id").val('');
            $("#model_name").val('');
            $("#brand_id_edit").val('');
            $("#brand_type_id_edit").val('');


            $("#model_desc").val('');
            $('.displayImagee').attr('src', '');


            var currentRow = $(this).closest("tr");
            $("#model_id").val(currentRow.find("td:eq(0)").text());
            $("#model_name").val(currentRow.find("td:eq(1)").text());
            $("#brand_id_edit").val(currentRow.find("td:eq(2)").text());
            $("#brand_type_id_edit").val(currentRow.find("td:eq(4)").text());
            if (currentRow.find("td:eq(6)").children().attr('src')) {
                $("#div_image_edit").removeClass('d-none');
                $('.displayImagee').attr('src', currentRow.find("td:eq(6)").children().attr('src'));
            } else {
                $("#div_image_edit").addClass('d-none');
                $('.displayImagee').attr('src', '');

            }
            $("#model_desc").val(currentRow.find("td:eq(7)").text());




        });

        $('#itemtbl tbody').on('click', '.delete_item', function() {
            $("#model_id_delete").val('');
            $("#model_name_delete").val('');
            var currentRow = $(this).closest("tr");
            $("#model_id_delete").val(currentRow.find("td:eq(0)").text());
            $("#model_name_delete").val(currentRow.find("td:eq(1)").text());



        });
    </script>
    <script>
     $('#model_image_edit').on('change', function() {
            var input = this;
            var url = $(this).val();
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                var reader = new FileReader();
                reader.onload = function(e) {


                    $('.displayImagee').attr('src', e.target.result);



                }
                reader.readAsDataURL(input.files[0]);
            } else {


            }




        });
        $('#add_logo').on('change', function() {
            // alert('ssss')
            var input = this;
            var url = $(this).val();
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $("#div_image_model").removeClass('d-none');

                    $('.add_logo_image').attr('src', e.target.result);
                    all_companies



                }
                reader.readAsDataURL(input.files[0]);
            } else {


            }




        });
    </script>




@endsection
