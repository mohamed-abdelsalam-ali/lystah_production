@extends('layouts.master')
@section('css')
    <style>
        .font1 {
            font-family: 'sans-serif';
            font-size: 17px;


        }

        table.dataTable td {
            /* font-size: 3em!important; */
            font-family: sans-serif !important;
            font-size: 16px !important;
        }
    </style>
@endsection
@section('title')
    جميع المستخدمين
@stop
@section('Toolbar container')
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <!--begin::Title-->
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                جميع المستخدمين
            </h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">
                    <a href="index.html" class="text-muted text-hover-primary">
                        جميع</a>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted"> المستخدمين</li>
                <!--end::Item-->
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
        <!--begin::Actions-->
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <!--begin::Secondary button-->
            {{-- <a href="#"
            class="btn btn-sm fw-bold bg-body btn-color-gray-700 btn-active-color-primary"
            data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Rollover</a> --}}
            <!--end::Secondary button-->
            <!--begin::Primary button-->
            <a class="btn btn-sm fw-bold btn-primary fs-5" data-bs-toggle="modal" data-bs-target="#staticBackdrop_add">
                إضافة
                مستخدم جديد</a>
            <!--end::Primary button-->
        </div>
        <!--end::Actions-->
    </div>
    <!--end::Toolbar container-->
@endsection


@section('content')

    <!--begin::Content-->

    <!--end::Content-->
    <div class="table-responsive p-4 rtl">
        <table class="table w-100 table-striped table-bordered  table-hover text-center" id="itemtbl">
            <thead>
                <tr style="background-color:#075599;color:white;">
                    <th class="">id</th>
                    <th> اسم المستخدم </th>
                    <th>الصورة</th>
                    <th>رقم التليفون</th>
                    <th> رقم التليفون أخر</th>
                    <th></th>
                                        <th></th>

                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>


    {{-- <--Delte--> --}}
    <div class="modal fade" id="staticBackdrop_delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> حذف الحجز</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('users.destroy', 'test') }}" method="post">
                        {{ method_field('Delete') }}
                        {{ csrf_field() }}
                        <div class="form-group">
                            <h5>هل انت متاكد من عملية الحذف ؟</h5><br>
                            <input type="hidden" name="id" id="user_id_delete" value="">
                            <input type="text" class="form-control font1 font1" id="user_name_delete" name="name"
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
    {{-- <--Add--> --}}

    <div class="modal fade" id="staticBackdrop_add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">إضافة حجز لمريض جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row form-outline mb-1">
                            <label for="recipient-name" class="col-form-label">اسم المستخدم</label>
                            <input type="text" class="form-control" name="username" placeholder="اسم المستخدم">
                        </div>
                        <div class="row pt-3 div_image d-none">
                            <img style="width: 200px; height: 100px;" class="displayImagee" src=""
                                class="rounded border" alt="">
                        </div>
                        <hr>
                        <div class="row form-outline mb-1">

                            <label class="form-label" for="">اختر صورة
                            </label>
                            <input type="file" class="form-control" name="img_path" id="img_path" />

                        </div>
                        <hr>
                        <div class="row form-outline mb-1">
                            <div class="col-6">
                                <label class="form-label" for="">رقم التليفون
                                </label>
                                <input type="text" class="form-control" name="telephone" placeholder="رقم الهاتف" />

                            </div>
                            <div class="col-6">
                                <label class="form-label" for="">رقم تليفون اخر
                                </label>
                                <input type="text" class="form-control" name="mobile"
                                    placeholder="رقم تليفون اخر" />
                            </div>

                        </div>
                        <hr>
                        <div class="form-outline mb-1">
                            <label class="form-label" for="form3Example4cg">كلمة المرور</label>
                            <input type="password" class="form-control" name="password" placeholder="كلمة المرور" />

                        </div>
                        <hr>

                        <div class="form-outline mb-1">
                            <label class="form-label" for=""> تأكيد كلمة
                                المرور</label>

                            <input type="password" class="form-control" name="password_confirmation"
                                placeholder="تأكيد كلمة المرور" />
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
    <script>
        pdfMake.fonts = {
            Arial: {
                normal: 'arial.ttf',
                bold: 'arial.ttf',

            }
        }
        var alluser = {!! $alluser !!}
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




                buttons: [{
                        extend: 'excel',
                        text: 'Excel <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>'
                    },
                    {
                        extend: 'pdf',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        exportOptions: {
                            columns: [9, 8, 7, 6, 5, 2, 1],
                            format: {
                                body: function(data, row, column, node) {
                                    const arabic = /[\u0600-\u06FF]/;

                                    if (arabic.test(data)) {
                                        return data.split(' ').reverse().join(' ');
                                    }
                                    return data;
                                },
                                header: function(data, row, column, node) {
                                    const arabic = /[\u0600-\u06FF]/;

                                    if (arabic.test(data)) {
                                        return data.split(' ').reverse().join(' ');
                                    }
                                    return data;
                                }
                            }
                        },
                        customize: function(doc) {
                            doc.defaultStyle.font = 'Arial';
                            doc.pageMargins = [20, 60, 20, 30];
                            doc.styles.title.alignment = 'center';
                            doc.defaultStyle.alignment = 'center';


                        },
                        title: function() {
                            var printTitle = 'الحجوزات';
                            return printTitle
                        },
                    },

                    'copy', 'colvis',
                    {
                        extend: 'print',
                        text: 'طباعة',
                        className: 'bg-danger',
                        exportOptions: {
                            // columns: [9, 8, 7, 6, 5, 2, 1]
                            columns: [1,3,4]

                        },
                        title: function() {
                            var printTitle = 'كشف جميع بيانات المستخدمين';
                            return printTitle
                        },
                        // autoPrint: true,

                        customize: function (win) {
                            $(win.document.body).find('th').addClass('display').css('text-align', 'center');
                            $(win.document.body).find('table').addClass('mr-5');
                            $(win.document.body).find('table').addClass('display').css('font-size', '20px');
                            $(win.document.body).find('table').addClass('display').css('text-align', 'center');
                            $(win.document.body).find('tr:nth-child(odd) td').each(function (index) {
                                $(this).css('background-color', '#D0D0D0');
                            });
                            $(win.document.body).find('h1').css('text-align', 'center');
                            $(win.document.body).css('direction', 'rtl');

                        }
                    }

                ],
                "columnDefs": [{
                    "className": "d-none",
                    "targets": [0]
                }, {
                    "className": "dt-center",
                    "targets": "_all"
                }],


                columns: [

                    {
                        data: "id"
                    },
                    {
                        data: "username"
                    },

                    {
                        data: "user_image",
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
                        data: "mobile"
                    },
                    {
                        data: "telephone"
                    },
                    {

                        data: null,

                        className: "dt-center editor-editt",

                        defaultContent: `
                        <div class="dropdown">
                                        <button class="btn btn-primary text-white dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                                            العمليات
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                            <li><button class="dropdown-item font1  text-center  edit_item text-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop_edit">تعديل </button></li>
                                            <li><button class="dropdown-item font1  text-center delete_item text-danger" data-bs-toggle="modal" data-bs-target="#staticBackdrop_delete"> حذف </button></li>
                                        </ul>
                                </div>
                                `,

                        orderable: false,


                    },
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
        </script>

@endsection
