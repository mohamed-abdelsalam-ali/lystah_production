




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
    Suppliers
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
            <p class="fs-17 text-bg-dark text-center">الموردين </p>

                    <div class="card">

                        <div class="card-body table-responsive ">
                                            
                            <div class="col-12 px-1 mt-3 text-end">
                                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#staticBackdrop">  إضافة</button>

                                
                            </div>

                            <table id="itemtbl"   class="table table-striped table-bordered cell-border  mt-3" style="font-size: smaller;width:100%">
                                <thead style="background: #67b1736e;"> 
                                    <tr>
                                        <th class="">supplier_id</th>
                                        <th class="sorting sorting_asc">الإسم</th>
                                        <th>العنوان</th>
                                        <th>الإيميل</th>
                                        <th>التليفون</th>
                                        <th>الموبايل</th>
                                        <th>الصورة</th>
                                        <th>البلد</th>
                                        <th>تقييم</th>
                                        <th>الوصف</th>
                                        <th>الموقع</th>
                                        <th> إسم المستخدم</th>
                                        <th> كلمة المرور</th>
                                        <th></th>
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
    {{-- <edit_model> --}}
    <div class="modal fade" id="staticBackdrop_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> تعديل بيانات المورد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('supplierindex.update', 'test') }}" method="post" enctype="multipart/form-data">
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row p-3">
                                    <div class="col-lg-4">

                                        <label for="recipient-name" class="col-form-label">  إسم المورد </label>
                                        <input id="supplier_id" name="supplier_id" type="hidden" class="form-control"
                                            required="">
                                        <input id="supplier_name" name="name" type="text" class="form-control"
                                            required="">
                                    </div>
                                    <div class="col-lg-4">

                                        <label for="recipient-name" class="col-form-label">  تقييم المورد </label>

                                        <input name="rate" id="supplier_rate" type="text" class="form-control"
                                            required="">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="recipient-name" class="col-form-label">  الإيميل </label>

                                        <input name="email" type="text" id="supplier_email" class="form-control"
                                            required="">
                                    </div>
                                </div>
                              
                                <div class="row pt-3">
                                    <div class="col-lg-4">
                                        <label for="recipient-name" class="col-form-label">  البلد </label>

                                        <input name="country" type="text" id="supplier_country" class="form-control"
                                            required="">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="recipient-name" class="col-form-label">  التليفون </label>

                                        <input name="tel01" type="number" id="supplier_telephone"
                                            class="form-control" required="">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="recipient-name" class="col-form-label">  الموبايل </label>

                                        <input name="tel02" type="numer" class="form-control" id="supplier_mobile"
                                            required="">
                                    </div>
                                </div>
                                <div class="row pt-3">
                                    <div class="col-lg-4">
                                        <label for="recipient-name" class="col-form-label">  العنوان </label>

                                        <input name="address" type="text" class="form-control" id="supplier_address"
                                        required="">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="website_edit" class="col-form-label">  الموقع الإلكترونى </label>

                                        <input name="website_edit" type="text" class="form-control" id="website_edit"
                                        required="">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="website_username_edit" class="col-form-label"> إسم المستخدم   </label>

                                        <input name="website_username_edit" type="text" class="form-control" id="website_username_edit"
                                        required="">
                                    </div>
                                </div>
                               
                                <div class="row  pt-3">
                                    <div class="col-lg-4">
                                        <label for="website_password_edit" class="col-form-label">   كلمة المرور </label>

                                        <input name="website_password_edit" type="text" class="form-control" id="website_password_edit"
                                        required="">
                                    </div>
                                    <div class="col-lg-8">
                                        <label for="recipient-name" class="col-form-label">  ملاحظات </label>

                                        <textarea name="desc" cols="30" rows="5" id="supplier_desc" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="row pt-3  " id="div_image_edit">
                                    <label for="" class="col-form-label">  الصورة </label>

                                    <img style="width: 200px; height: 100px;" class="displayImagee" src=""
                                        class="rounded border" alt="">
                                </div>
                                <input type="file" class="form-control" name="supplier_image"
                                    id="supplier_image_edit">

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
                    <h5 class="modal-title" id="staticBackdropLabel"> حذف هذاالمورد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('supplierindex.destroy', 'test') }}" method="post">
                    {{ method_field('Delete') }}
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row p-3">
                                    <div class="col-12">
                                        <input id="supplier_id_delete" name="supplier_id" type="hidden" class=""
                                            required="">
                                        <label for="recipient-name" class="col-form-label"> سيتم حذف هذا المورد </label>

                                        <input id="supplier_name_delete" name="name" type="text"
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
                    <h5 class="modal-title" id="staticBackdropLabel">   إضافة مورد جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('supplierindex.store') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row p-3">
                                    <div class="col-lg-4">

                                        <label for="recipient-name" class="col-form-label">  الإسم </label>

                                        <input name="name" type="text" class="form-control" required="">
                                    </div>
                                    <div class="col-lg-4">

                                        <label for="recipient-name" class="col-form-label">  تقييم </label>

                                        <input name="rate" type="text" class="form-control" required="">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="recipient-name" class="col-form-label">  الإيميل </label>

                                        <input name="email" type="text" class="form-control" required="">
                                    </div>
                                </div>

                              
                                <div class="row pt-3">
                                    <div class="col-lg-4">
                                        <label for="recipient-name" class="col-form-label">  البلد </label>

                                        <input name="country" type="text" class="form-control" required="">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="recipient-name" class="col-form-label">  التليفون </label>

                                        <input name="tel01" type="number" class="form-control" required="">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="recipient-name" class="col-form-label">  الموبايل </label>

                                        <input name="tel02" type="numer" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="row pt-3">
                                    <div class="col-lg-4">
                                        <label for="recipient-name" class="col-form-label">  العنوان </label>
                                        <input name="address" type="text" class="form-control" required="">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="recipient-name" class="col-form-label">  الموقع الإلكترونى </label>
                                        <input name="website" type="text" class="form-control" required="">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="recipient-name" class="col-form-label">   إسم المستخدم </label>
                                        <input name="website_username" type="text" class="form-control" required="">
                                    </div>
                                </div>
                              
                                <div class="row  pt-3">
                                    <div class="col-lg-4">
                                        <label for="recipient-name" class="col-form-label">   كلمة المرور</label>
                                        <input name="website_password" type="text" class="form-control" required="">
                                   
                                    </div>
                                    <div class="col-lg-8">
                                        <label for="recipient-name" class="col-form-label">  ملاحظات </label>

                                        <textarea name="desc" cols="30" rows="5" class="form-control"></textarea>
                                    </div>
                                    
                                </div>
                                <div class="row pt-3 d-none" id="div_image_supplier">
                                    <label for="" class="col-form-label">  الصورة </label>

                                    <img style="width: 200px; height: 100px;" class="add_logo_image" src=""
                                        class="rounded border" alt="">
                                </div>
                                <input type="file" class="form-control" id="add_logo" name="supplier_image">

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
        var all_suplliers = {!! $all_suplliers !!}
        var itemtbl = "";
        $(document).ready(function() {
            itemtbl = $("#itemtbl").DataTable({
                dom: "Bfrltip",


                buttons: [
                    "print",
           
        ],


                columns: [

                    {
                        data: "id"
                    },
                    {
                        data: "name"
                    },
                    {
                        data: "address"
                    },
                    {
                        data: "email"
                    },
                    {
                        data: "tel01"
                    },
                    {
                        data: "tel02"
                    },
                    {
                        data: "image",
                        "render": function(data) {
                            if (data) {
                                return '<img  class="avatar-img img-thumbnail rounded-5 itemimg" src="supplier_images/' +
                                    data +
                                    '" width="40px">';


                            } else {
                                return '<span class="text-primary">لا يوجد صورة</span> ';

                            }
                        }
                    },

                    {
                        data: "country"
                    },
                    {
                        data: "rate"
                    },
                    {
                        data: "desc"
                    },
                    {
                        data: "website"
                    },
                    {
                        data: "website_username"
                    },
                    {
                        data: "website_password"
                    },
                    {

                        data: null,

                        className: "dt-center editor-delete",

                        defaultContent: '<button class="btn btn-success"></button>',

                        orderable: false,
                        render: function(data, type, row, meta) {
                            return `

                        <button class="btn btn-info text-nowrap" onClick="convert_to_client(${row.id})"> Use As Client</button>
                        `;
                        }

                    },
                    {

                        data: null,

                        className: "dt-center editor-delete",

                        defaultContent: '<button class="btn btn-success"></button>',

                        orderable: false,
                        render: function(data, type, row, meta) {
                            return `

                        <a class="btn btn-success text-nowrap" href="show_account_bank/${row.id}">  Bank Account  </a>
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
            itemtbl.rows.add(all_suplliers).draw();
            itemtbl.buttons().container().appendTo('#itemtbl_wrapper .col-md-6:eq(0)');

        });
    </script>
    <script>
        $('#itemtbl tbody').on('click', '.edit_item', function() {
            $("#supplier_id").val('');
            $("#supplier_name").val('');
            $("#supplier_address").val('');
            $("#website_edit").val('');
            $("#website_username_edit").val('');
            $("#website_password_edit").val('');
            $("#supplier_telephone").val('');
            $("#supplier_mobile").val('');
            $('.displayImagee').attr('src', '');
            $("#supplier_email").val('');
            $("#supplier_desc").text('');
            $("#supplier_country").val('');
            $("#supplier_rate").val('');

            var currentRow = $(this).closest("tr");
            $("#supplier_id").val(currentRow.find("td:eq(0)").text());
            $("#supplier_name").val(currentRow.find("td:eq(1)").text());
            $("#supplier_address").val(currentRow.find("td:eq(2)").text());
            $("#supplier_email").val(currentRow.find("td:eq(3)").text());
            $("#supplier_telephone").val(currentRow.find("td:eq(4)").text());
            $("#supplier_mobile").val(currentRow.find("td:eq(5)").text());

            if (currentRow.find("td:eq(6)").children().attr('src')) {
                $("#div_image_edit").removeClass('d-none');
                $('.displayImagee').attr('src', currentRow.find("td:eq(6)").children().attr('src'));
            } else {
                $("#div_image_edit").addClass('d-none');
                $('.displayImagee').attr('src', '');

            }
            $("#supplier_country").val(currentRow.find("td:eq(7)").text());
            $("#supplier_rate").val(currentRow.find("td:eq(8)").text());
            $("#supplier_desc").val(currentRow.find("td:eq(9)").text());

            $("#website_edit").val(currentRow.find("td:eq(10)").text());
            $("#website_username_edit").val(currentRow.find("td:eq(11)").text());
            $("#website_password_edit").val(currentRow.find("td:eq(12)").text());







        });

        $('#itemtbl tbody').on('click', '.delete_item', function() {
            $("#supplier_id_delete").val('');
            $("#supplier_name_delete").val('');
            var currentRow = $(this).closest("tr");
            $("#supplier_id_delete").val(currentRow.find("td:eq(0)").text());
            $("#supplier_name_delete").val(currentRow.find("td:eq(1)").text());



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

        function convert_to_client (id){
           flage = check_if_added(id);
        //    alert(flage[0].id);
            if(flage.length==0){
                Swal.fire({
                        text: "هل تريد استخدام هذا المورد كعميل ؟",
                        icon: "info",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Yes, حفظ!",
                        cancelButtonText: "No, الغاء",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton: "btn fw-bold btn-active-light-primary"
                        }
                        }).then(function(result) {
                        if (result.value) {

                            use_as_client(id);
                            Swal.fire({
                                text: "تمت العملية بنجاح " ,
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }

                            }).then(function() {

                            });
                        } else if (result.dismiss === 'cancel') {
                            Swal.fire({
                                text: " لم يتم الحفظ " ,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            });
                        }
                });
            }else{
                Swal.fire('هذا المورد موجود كعميل مسبقاً ');
            }
        }
         function use_as_client(sup_id){
            $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    url: "{{ route('useSupAsClient') }}",
                                    async: false,
                                    type: 'POST',
                                    data: {
                                        'data': sup_id
                                    },

                                    success: function(response) {
                                        // $(form).trigger("reset");
                                        // alert(response.success)
                                        console.log(response);
                                        // $('.simplebar-content').empty();


                                    },
                                    error: function(response) {}
                                });
         }
         function check_if_added(sup_id){
           var flager;
            $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    url: "{{ route('checkSupAsClient') }}",
                                    async: false,
                                    type: 'POST',
                                    data: {
                                        'data': sup_id
                                    },

                                    success: function(response) {
                                        // $(form).trigger("reset");
                                        // alert(response.success)
                                        flager=response;
                                        // console.log(response);
                                        // $('.simplebar-content').empty();


                                    },
                                    error: function(response) {}
                                });
                            return flager;
         }

    </script>




@endsection
