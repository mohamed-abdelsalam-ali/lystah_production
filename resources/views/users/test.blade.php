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
    Users
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
                        <h4 class="mb-sm-0">Add New User</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Users</li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
             <div class="card">
                <div class="card-body">
                    <button class="btn btn-primary m-1 float-left" data-bs-toggle="modal" data-bs-target="#staticBackdrop">إضافة مستخدم جديد </i></button>

                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-head">
                            <div class="row">
                                <div class="col-8"></div>
                              
                                <div class="col-2 px-1 mt-3 text-start">
                                  
                                </div>
                            </div>
                        </div>
                        
                      
                        <div class="card-body table-responsive">
                            <table id="itemtbl" class="table table-striped table-bordered cell-border " style="width:100%" >
                                <thead style="background:#5fcee78a">
                                    <tr>
                                        <th class="d-none">id</th>
                                        <th> User Name </th>
                                        <th>Email</th>
                                        <th>Profile Image</th>
                                        <th>رقم التليفون</th>
                                        <th> National Image </th>
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

        </div>
    </div>
    {{-- <edit_model> --}}
    <div class="modal fade" id="staticBackdrop_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.update', 'test') }}" method="post" enctype="multipart/form-data">
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row p-3">
                                    <div class="col-12">
                                        <label for="recipient-name" class="col-form-label"> Unit Name </label>
                                        <input name="unit_id" id="unit_id" type="hidden" class="form-control">
                                        <input name="name" id="unit_name" type="text" class="form-control"
                                            required="">
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="recipient-name" class="col-form-label"> Description </label>
                                    <div class="col-12">
                                        <textarea name="desc" cols="30" rows="5" id="unit_desc" class="form-control"></textarea>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.destroy', 'test') }}" method="post">
                    {{ method_field('Delete') }}
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row p-3">
                                    <div class="col-12">
                                        <input type="hidden" name="id" id="user_id_delete" value="">
                                        <input type="text" class="form-control font1 font1" id="user_name_delete"
                                            name="name" value="" disabled>
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
                    <h5 class="modal-title" id="staticBackdropLabel"> Add New User </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.store') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row ">
                                    <div class="col-12">

                                        <label for="recipient-name" class="col-form-label">اسم المستخدم</label>
                                        <input type="text" class="form-control" name="username" required>
                                    </div>

                                </div>
                                <div class="row ">
                                    <div class="col-12">

                                        <label for="recipient-name" class="col-form-label">البريد الإلكتروني </label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>

                                </div>
                                <div class="row pt-3 div_image d-none">
                                    <img style="width: 200px; height: 100px;" class="displayImagee" src=""
                                        class="rounded border" alt="">
                                </div>
                                <hr>
                                <div class="row">
                                    <label class="form-label" for="">اختر صورة الشخصية
                                    </label>
                                    <input type="file" class="form-control" name="profile_img" id="img_path" />
                                </div>
                                <div class="row ">
                                    <div class="col-12">
                                        <label class="form-label" for="">رقم التليفون
                                        </label>
                                        <input type="text" class="form-control" name="telephone"  required/>

                                    </div>


                                </div>
                                <div class="row pt-3 div_image_national d-none">
                                    <img style="width: 200px; height: 100px;" class="displayImagee_national"
                                        src="" class="rounded border" alt="">
                                </div>
                                <hr>
                                <div class="row">
                                    <label class="form-label" for="">اختر صورة
                                    </label>
                                    <input type="file" class="form-control" name="national_img"
                                        id="img_path_national" required/>
                                </div>
                                <div class="form-outline mb-1">
                                    <label class="form-label" for="form3Example4cg">كلمة المرور</label>
                                    <input type="password" class="form-control" required name="password" />

                                </div>
                                <hr>

                                <div class="form-outline mb-1">
                                    <label class="form-label" for=""> تأكيد كلمة
                                        المرور</label>

                                    <input type="password" class="form-control" required name="password_confirmation" />
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
    {{-- <--reset_password--> --}}
    <div class="modal fade" id="varyModalreset" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Reset User Pawword</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('reset_password_user', 'test') }}" method="post" enctype="multipart/form-data">
                    {{ method_field('POST') }}
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="form-group">
                            <p>هل انت متاكد من عملية تغيير كلمة المرور هذا المستخدم ؟</p><br>
                            <input type="hidden" name="id" id="user_id_reset" value="">
                            <input type="text" class="form-control  " id="user_name_reset" value="" readonly>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Reset</button>
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
        var alluser = {!! $alluser !!}
        var itemtbl = "";
        $(document).ready(function() {
            itemtbl = $("#itemtbl").DataTable({
                   pageLength: 10,
                    deferRender: true,
                    responsive: true,
                    dom: "Bfrltip",
              
                // buttons: [{
                //         extend: 'excel',
                //         text: 'Excel <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>'
                //     },
                //     {
                //         extend: 'print',
                //         text: 'طباعة',
                //         className: 'bg-danger',
                //         exportOptions: {
                //             // columns: [9, 8, 7, 6, 5, 2, 1]
                //             columns: [1, 2, 5, 7, 9, 10, 11, 13, 14, 15, 16]

                //         },
                //         title: function() {
                //             var printTitle = 'كشف حجوزات اليوم ';
                //             return printTitle
                //         },
                //         // autoPrint: true,

                //         customize: function(win) {
                //             $(win.document.body).find('th').addClass('display').css('text-align',
                //                 'center');
                //             $(win.document.body).find('table').addClass('mr-5');
                //             $(win.document.body).find('table').addClass('display').css('font-size',
                //                 '20px');
                //             $(win.document.body).find('table').addClass('display').css('text-align',
                //                 'center');
                //             $(win.document.body).find('tr:nth-child(odd) td').each(function(index) {
                //                 $(this).css('background-color', '#D0D0D0');
                //             });
                //             $(win.document.body).find('h1').css('text-align', 'center');
                //             $(win.document.body).css('direction', 'rtl');

                //         }
                //     }

                // ],
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
                        data: "username"
                    },
                    {
                        data: "email"
                    },

                    {
                        data: "profile_img",
                        "render": function(data) {


                            if (data) {
                                return '<img  class="avatar-img img-thumbnail rounded-5 itemimg" data-url="' +
                                    data + '"  src="users_images/' + data +
                                    '" width="40px">'

                            } else {
                                return '<img  class="avatar-img img-thumbnail rounded-5 itemimg" src="{{ asset('users_images/default.png') }}" width="40px">'
                            }


                        }
                    },
                    {
                        data: "telephone"
                    },
                    {
                        data: "national_img",
                        "render": function(data) {


                            if (data) {
                                return '<img  class="avatar-img img-thumbnail rounded-5 itemimg" data-url="' +
                                    data + '"  src="national_images/' + data +
                                    '" width="40px">'

                            } else {
                                return '<img  class="avatar-img img-thumbnail rounded-5 itemimg" src="{{ asset('users_images/default.png') }}" width="40px">'
                            }


                        }
                    },
                    // {

                    //     data: null,

                    //     className: "dt-center editor-editt",

                    //     defaultContent: '<button  class="btn btn-primary edit_item " data-bs-toggle="modal" data-bs-target="#staticBackdrop_edit">Edit</button>',

                    //     orderable: false

                    // },
                    {

                        data: null,

                        className: "dt-center editor-editt",

                        defaultContent: '<button class="btn btn-danger delete_item"   data-bs-toggle="modal" data-bs-target="#staticBackdrop_delete">Delete</button>',

                        orderable: false

                    },
                    {

                        data: null,

                        className: "dt-center editor-editt",



                        orderable: false,
                        render: function(data, type, row, meta) {
                            return `
                        <button class="btn btn-success reset_item"   data-bs-toggle="modal" data-bs-target="#varyModalreset">reset password</button>
        `;

                        }

                    }

                ],



            });
            itemtbl.clear();
            itemtbl.rows.add(alluser).draw();
            itemtbl.buttons().container().appendTo('#itemtbl_wrapper .col-md-6:eq(0)');

        });
    </script>
    <script>
        $('#itemtbl tbody').on('click', '.edit_item', function() {
            $("#user_id").val("");

            var currentRow = $(this).closest("tr");
            $("#user_id").val(currentRow.find("td:eq(0)").text());

        });





        $('#itemtbl tbody').on('click', '.delete_item', function() {
            var currentRow = $(this).closest("tr");
            var user_id = currentRow.find("td:eq(0)").text();
            var user_name = currentRow.find("td:eq(1)").text();
            $("#user_id_delete").val(user_id);
            $("#user_name_delete").val(user_name);


        });
        $('#itemtbl tbody').on('click', '.reset_item', function() {
            $("#user_id_reset").val('');
            $("#user_name_reset").val('');
            var currentRow = $(this).closest("tr");
            $("#user_id_reset").val(currentRow.find("td:eq(0)").text());
            $("#user_name_reset").val(currentRow.find("td:eq(1)").text());

        });
    </script>
    <script>
        $('#img_path').on('change', function() {
            var input = this;
            var url = $(this).val();
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                var reader = new FileReader();
                reader.onload = function(e) {



                    $('.displayImagee').attr('src', e.target.result);
                    $('.div_image').removeClass('d-none');



                }
                reader.readAsDataURL(input.files[0]);
            } else {


            }




        });
        $('#img_path_national').on('change', function() {
            var input = this;
            var url = $(this).val();
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                var reader = new FileReader();
                reader.onload = function(e) {



                    $('.displayImagee_national').attr('src', e.target.result);
                    $('.div_image_national').removeClass('d-none');



                }
                reader.readAsDataURL(input.files[0]);
            } else {


            }




        });
    </script>




@endsection
