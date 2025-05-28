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
    sub groups
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
            <p class="fs-17 text-bg-dark text-center">المجموعات الفرعية </p>

                <div class="card">

                    <div class="card-body table-responsive ">
                        <div class="col-12 px-1 mt-3 text-end">
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#staticBackdrop">  إضافة</button>
                        </div>
                        <table id="itemtbl"  class="table table-striped table-bordered cell-border  mt-3" style="font-size: smaller;width:100%">
                            <thead style="background: #67b1736e;">
                                <tr>
                                    <th class="d-none">sub_group_id</th>
                                    <th class="sorting sorting_asc">مجموعة فرعية</th>
                                    <th class="d-none">group_id</th>
                                    <th>المجموعة </th>
                                    <th>الصورة</th>
                                    <th>ملاحظات</th>
                                    <th></th>
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
    {{-- <edit_sub_group> --}}
    <div class="modal fade" id="staticBackdrop_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">تعديل مجموهة فرعية </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('sub_group.update', 'test') }}" method="post" enctype="multipart/form-data">
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row p-3">
                                    <div class="col-12">
                                        <input name="sub_group_id" id="sub_group_id" type="hidden" class="form-control"
                                            required="">

                                        <label for="recipient-name" class="col-form-label"> إسم المجموعة الفرعية </label>
                                        <input name="name" id="sub_group_name" type="text" class="form-control"
                                            required="">
                                    </div>

                                </div>
                                <div class="row p-3">
                                    <div class="col-12">

                                        <label for="recipient-name" class="col-form-label">  إختار المجموعة الرئيسية </label>
                                        <select name="group_id" id="group_id_edit"
                                            class="selectpicker button btn btn-light form-control">
                                            @foreach ($groups as $group)
                                                <option class="text-center" value="{{ $group->id }}">
                                                    {{ $group->name }}</option>
                                            @endforeach

                                        </select>
                                    </div>

                                </div>
                                <div class="row pt-3  d-none" id="div_image_edit">
                                    <img style="width: 200px; height: 100px;" class="displayImagee" src=""
                                        class="rounded border" alt="">
                                </div>
                                <input type="file" class="form-control" name="sub_group_img" id="sub_group_image_edit">
                                <div class="row">
                                    <label for="recipient-name" class="col-form-label"> ملاحظات </label>
                                    <div class="col-12">
                                        <textarea name="desc" id="sub_group_desc" cols="30" rows="5" class="form-control"></textarea>
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
    {{-- <delete_sub_group> --}}
    <div class="modal fade" id="staticBackdrop_delete" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> حذف المجموعة الفرعية</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('sub_group.destroy', 'test') }}" method="post">
                    {{ method_field('Delete') }}
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row p-3">
                                    <div class="col-12">
                                        <input id="sub_group_id_delete" name="sub_group_id" type="hidden"
                                            class="" required="">
                                        <label for="recipient-name" class="col-form-label"> سيتم حذف هذا الفرع </label>

                                        <input id="sub_group_name_delete" name="name" type="text"
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
    {{-- <Add_sub_group> --}}
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> إضافة مجموعة فرعية جديدة   </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('sub_group.store') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row p-3">
                                    <div class="col-12">

                                        <label for="recipient-name" class="col-form-label">  إسم المجموعة الفرعية </label>
                                        <input name="name" type="text" class="form-control" required="">
                                    </div>

                                </div>
                                <div class="row p-3">
                                    <div class="col-12">

                                        <label for="recipient-name" class="col-form-label">  إختار المجموعة الرئيسية </label>
                                        <select name="group_id" id="group_id_add"
                                            class="selectpicker button btn btn-light form-control">
                                            @foreach ($groups as $group)
                                                <option class="text-center" value="{{ $group->id }}">
                                                    {{ $group->name }}</option>
                                            @endforeach

                                        </select>
                                    </div>

                                </div>
                                <div class="row pt-3 d-none" id="div_image_sub_group">
                                    <img style="width: 200px; height: 100px;" class="add_logo_image" src=""
                                        class="rounded border" alt="">
                                </div>
                                <input type="file" class="form-control" id="add_logo" name="sub_group_img">
                                <div class="row">
                                    <label for="recipient-name" class="col-form-label"> ملاحظات </label>
                                    <div class="col-12">
                                        <textarea name="desc" cols="30" rows="5" class="form-control"></textarea>
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
        var all_sub_groups = {!! $all_sub_groups !!}
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
                        "targets": [0, 2]
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
                        data: "group_id"
                    },
                    {
                        data: "group.name"
                    },
                    {
                        data: "sub_group_img",
                        "render": function(data) {
                            if (data) {
                                return '<img  class="avatar-img img-thumbnail rounded-5 itemimg" src="sub_group_images/' +
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

                        className: "dt-center editor-delete",

                        defaultContent: '<button class="btn btn-success"></button>',

                        orderable: false,
                        render: function(data, type, row, meta) {
                            return `

                                <a class="btn btn-success text-nowrap" href="show_catalog/${row.id}">show catalog images  </a>
                                `;
                        }

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
            itemtbl.rows.add(all_sub_groups).draw();
            itemtbl.buttons().container().appendTo('#itemtbl_wrapper .col-md-6:eq(0)');

        });
    </script>
    <script>
        $('#itemtbl tbody').on('click', '.edit_item', function() {
            $("#sub_group_id").val('');
            $("#sub_group_name").val('');
            $("#group_id_edit").val('');
            $("#sub_group_desc").val('');
            $('.displayImagee').attr('src', '');


            var currentRow = $(this).closest("tr");
            $("#sub_group_id").val(currentRow.find("td:eq(0)").text());
            $("#sub_group_name").val(currentRow.find("td:eq(1)").text());
            $("#group_id_edit").val(currentRow.find("td:eq(2)").text());
            if (currentRow.find("td:eq(4)").children().attr('src')) {
                $("#div_image_edit").removeClass('d-none');
                $('.displayImagee').attr('src', currentRow.find("td:eq(4)").children().attr('src'));
            } else {
                $("#div_image_edit").addClass('d-none');
                $('.displayImagee').attr('src', '');

            }
            $("#sub_group_desc").val(currentRow.find("td:eq(5)").text());




        });

        $('#itemtbl tbody').on('click', '.delete_item', function() {
            $("#sub_group_id_delete").val('');
            $("#sub_group_name_delete").val('');
            var currentRow = $(this).closest("tr");
            $("#sub_group_id_delete").val(currentRow.find("td:eq(0)").text());
            $("#sub_group_name_delete").val(currentRow.find("td:eq(1)").text());



        });
    </script>
    <script>
        $('#sub_group_image_edit').on('change', function() {
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
                    $("#div_image_sub_group").removeClass('d-none');

                    $('.add_logo_image').attr('src', e.target.result);
                    all_companies



                }
                reader.readAsDataURL(input.files[0]);
            } else {


            }




        });
    </script>




@endsection
