@extends('layouts.master')
@section('css')
 


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
    Sources
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
          
            <p class="fs-17 text-bg-dark text-center">المنشأ </p>

            <div class="card">
                
                <div class="card-body">
                    <div class="col-12 px-1 m-3 text-end">

                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        إضافة</button>
                    </div>
                    <table id="itemtbl"  class="table table-striped table-bordered cell-border  mt-3" style="font-size: smaller;width:100%">
                        <thead style="background: #67b1736e;"> 
                            <tr>
                                <th class="d-none">source_id</th>
                                <th class="sorting sorting_asc">الإختصار</th>
                                <th> الإسم عربى</th>
                                <th> الإسم إنجليزى</th>
                                <th></th>
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
    {{-- <edit_model> --}}
    <div class="modal fade" id="staticBackdrop_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> تعديل بلد المنشأ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('all_source.update', 'test') }}" method="post" enctype="multipart/form-data">
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row p-3">
                                    <div class="col-12">
                                        <input name="source_id" id="source_id" type="hidden" class="form-control"
                                            required="">

                                        <label for="recipient-name" class="col-form-label">  الإختصار </label>
                                        <input name="iso" type="text" id="source_iso" class="form-control"
                                            required="">
                                    </div>

                                </div>
                                <div class="row p-3">
                                    <div class="col-12">

                                        <label for="recipient-name" class="col-form-label">   الإسم عربى </label>
                                        <input name="name_arabic" type="text" id="source_name_arabic" class="form-control"
                                            required="">
                                    </div>

                                </div>
                                <div class="row p-3">
                                    <div class="col-12">

                                        <label for="recipient-name" class="col-form-label">   الإسم إنجليزى </label>
                                        <input name="name_en" type="text" id="source_name_en" class="form-control"
                                            required="">
                                    </div>

                                </div>
                               






                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-danger">تعديل</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- <delete_model> --}}
    <div class="modal fade" id="staticBackdrop_delete" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> حذف المنشأ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('all_source.destroy', 'test') }}" method="post">
                    {{ method_field('Delete') }}
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">

                                <div class="row p-3">
                                    <div class="col-12">
                                        <input id="source_id_delete" name="source_id" type="hidden" class=""
                                            required="">
                                        <label for="recipient-name" class="col-form-label">  الإسم المختصر </label>
                                        <input name="iso" type="text" id="source_iso_delete" class="form-control"
                                            readonly>
                                    </div>

                                </div>
                                <div class="row p-3">
                                    <div class="col-12">

                                        <label for="recipient-name" class="col-form-label">   الإسم عربى </label>
                                        <input name="name_arabic" type="text" id="source_name_arabic_delete"
                                            class="form-control" readonly>
                                    </div>

                                </div>
                                <div class="row p-3">
                                    <div class="col-12">

                                        <label for="recipient-name" class="col-form-label">   الإسم إنجليزى </label>
                                        <input name="name_en" type="text" id="source_name_en_delete"
                                            class="form-control" readonly>
                                    </div>

                                </div>
                               


                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-danger">حذف</button>
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
                    <h5 class="modal-title" id="staticBackdropLabel">   إضافة منشأ جديد </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('all_source.store') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row p-3">
                                    <div class="col-12">

                                        <label for="recipient-name" class="col-form-label">  الإسم المختصر </label>
                                        <input name="iso" type="text" class="form-control" required="">
                                    </div>

                                </div>
                           
                                <div class="row p-3">
                                    <div class="col-12">

                                        <label for="recipient-name" class="col-form-label">   الإسم عربى </label>
                                        <input name="name_arabic" type="text" class="form-control" required="">
                                    </div>

                                </div>
                                <div class="row p-3">
                                    <div class="col-12">

                                        <label for="recipient-name" class="col-form-label">   الإسم إنجليزى </label>
                                        <input name="name_en" type="text" class="form-control" required="">
                                    </div>

                                </div>






                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-danger">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
   
    <script>
        var all_sources = {!! $all_sources !!}
        var itemtbl = "";
        $(document).ready(function() {
            itemtbl = $("#itemtbl").DataTable({
                 dom: "Bfrltip",
                 lengthMenu: [[10, 25, 50, 100,-1], [10, 25, 50,100, "All"]],
                deferRender: true,
                responsive: true,
                 destroy: true,
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
                        data: "iso"
                    },
                    {
                        data: "name_arabic"
                    },
                    {
                        data: "name_en"
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
            itemtbl.rows.add(all_sources).draw();
            // itemtbl.buttons().container().appendTo('#itemtbl_wrapper .col-md-6:eq(0)');

        });
    </script>
    <script>
        $('#itemtbl tbody').on('click', '.edit_item', function() {
            $("#source_id").val('');
            $("#source_iso").val('');
            $("#source_name_en").val('');
            $("#source_name_arabic").val('');


            var currentRow = itemtbl.row($(this).closest("tr")).data();
            console.log(currentRow);
            $("#source_id").val(currentRow.id);
            $("#source_iso").val(currentRow.iso);
            $("#source_name_en").val(currentRow.name_en);
            $("#source_name_arabic").val(currentRow.name_arabic);




        });

        $('#itemtbl tbody').on('click', '.delete_item', function() {
            $("#source_id_delete").val('');
            $("#source_iso_delete").val('');
            $("#source_name_en_delete").val('');
            $("#source_name_arabic_delete").val('');
            var currentRow = itemtbl.row($(this).closest("tr")).data();
            $("#source_id_delete").val(currentRow.id);
            $("#source_iso_delete").val(currentRow.iso);
            $("#source_name_en_delete").val(currentRow.name_en);
            $("#source_name_arabic_delete").val(currentRow.name_arabic);
            
        });
    </script>
    <script>
        $('#supplier_image_edit').on('change', function() {
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
                    $("#div_image_supplier").removeClass('d-none');

                    $('.add_logo_image').attr('src', e.target.result);



                }
                reader.readAsDataURL(input.files[0]);
            } else {


            }




        });
    </script>




@endsection
