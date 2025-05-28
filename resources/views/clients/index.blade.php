@extends('layouts.master')
@section('css')



    <style>
                .modal-dialog {
    width: 50%; /* Set the width */
    margin: auto; /* Center horizontally */
}

@media (max-width: 768px) {
    .modal-dialog {
        width: 90%; /* Adjust width for smaller screens */
    }
}
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
    Clients
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
            <p class="fs-17 text-bg-dark text-center">العملاء </p>
                    <div class="card  p-0">
                       
                        <div class="card-body  table-responsive">
                            <div class="col-12 px-1 mt-3 text-end">
                                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                    إضافة </button>
                                   
                            </div>
                            
                            <table id="itemtbl" class="table table-striped table-bordered cell-border " style="font-size: smaller;width:100%" >
                                <thead style="background: #67b1736e;"> 
                                    <tr>
                                        <th >clientId</th>
                                        <th >الإسم</th>
                                        <th>الصورة</th>
                                        <th>العنوان</th>
                                        <th>التليفون 1 </th>
                                        <th>التليفون 2 </th>
                                        <th>التليفون 3 </th>
                                       
                                        <th>الإيميل</th>
                                     
                                        <th>السجل التجارى</th>
                                        <th> البطاقة الضريبية</th>
                                        <th>الرقم القومى</th>
                                        <th>ملاحظات</th>
                                        <th>الرصيد</th>
                                        <th>مورد</th>

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
                    <h5 class="modal-title" id="staticBackdropLabel">Edit client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('client.update', 'test') }}" method="post" enctype="multipart/form-data">
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row ">
                                    <div class="col-4">
                                        <input id="client_id" name="client_id" type="hidden" class="-control" >
                                        <label for="recipient-name" class="col-form-label">  إسم العميل </label>

                                        <input id="client_name" name="name" type="text" class="form-control" >
                                    </div>
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label"> العنوان  </label>

                                        <input id="client_address" name="address" type="text" class="form-control" >
                                    </div>
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label">  الإيميل  </label>

                                        <input id="client_email1" name="email1" type="email" class="form-control" >
                                    </div>

                                </div>
                                <div class="row ">

                                    <div class="col-6">
                                        <label for="recipient-name" class="col-form-label">  الموقع </label>

                                        <input id="client_email2" name="email2" type="text" class="form-control"
                                            >
                                    </div>
                                    <div class="col-6">
                                        <label for="recipient-name" class="col-form-label">  رصيد العميل  </label>

                                        <input  name="client_raseed" id="client_raseed"  type="number" step="0.01" class="form-control" >
                                    </div>
                                    <div class="col-4 d-none">
                                        <label for="recipient-name" class="col-form-label"> (optional) if Suppplier  </label>

                                        <select  name="sup_id" id="sup_id"  class="form-control " >
                                            <option value=""  selected>Select From Suppplier</option>
                                            @foreach ($all_supplier as $sup)
                                            <option value="{{$sup->id}}">{{$sup->name}}</option>

                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label"> 1 رقم التليفون   </label>

                                        <input id="client_telephone1" name="tel01" type="number" class="form-control"
                                           >
                                    </div>
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label"> 2 رقم التليفون </label>

                                        <input id="client_telephone2" name="tel02" type="number" class="form-control"
                                            >
                                    </div>
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label"> 3 رقم التليفون  </label>

                                        <input id="client_telephone3" name="tel03" type="number" class="form-control"
                                           >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label">  تعديل سجل تجاري </label>
                                        <input  name="segl_togary" id="client_segl_togary" type="file" class="form-control"
                                            >
                                    </div>
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label">    تعديل بطاقة ضريبة </label>
                                        <input  name="betaa_darebia" id="client_betaa_darebia" type="file" class="form-control"
                                           >
                                    </div>

                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label">  رقم البطاقة </label>
                                        <input id="client_national_no" name="national_no" type="number" class="form-control"
                                           >
                                    </div>
                                </div>
                                <div class="row pt-3  d-none" id="div_image_edit">
                                    <img style="width: 200px; height: 100px;" class="displayImagee" src=""
                                        class="rounded border" alt="">
                                </div>
                                <div class="row">
                                    <label for="recipient-name" class="col-form-label">   صورة العميل  </label>
                                    <input type="file" class="form-control" name="client_img" id="client_image_edit">
                                </div>

                                <div class="row">
                                    <label for="recipient-name" class="col-form-label">  ملاحظات </label>
                                    <div class="col-12">
                                        <textarea name="notes" id="client_notes" cols="30" rows="5" class="form-control"></textarea>
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
                    <h5 class="modal-title" id="staticBackdropLabel"> مسح هذا العميل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('client.destroy', 'test') }}" method="post">
                    {{ method_field('Delete') }}
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row ">
                                    <div class="col-12">
                                        <input id="client_id_delete" name="client_id" type="hidden" class="-control"
                                            required="">
                                        <label for="recipient-name" class="col-form-label"> سيتم حذف هذا العميل  </label>

                                        <input id="client_name_delete" name="name" type="text"
                                            class="form-control" readonly>
                                    </div>

                                </div>


                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                    <h5 class="modal-title" id="staticBackdropLabel">   إضافة عميل جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('client.store') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row ">
                                    <div class="col-4">

                                        <label for="recipient-name" class="col-form-label">  إسم العميل </label>

                                        <input  name="name" type="text" class="form-control" >
                                    </div>
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label">  العنوان </label>

                                        <input  name="address" type="text" class="form-control">
                                    </div>
                                    <div class="col-4">

                                        <label for="recipient-name" class="col-form-label">  الإيميل  </label>

                                        <input  name="email1" type="email" class="form-control" >
                                    </div>

                                </div>
                                <div class="row ">

                                    <div class="col-6">
                                        <label for="recipient-name" class="col-form-label">  الموقع الإلكترونى  </label>

                                        <input  name="email2" type="text" class="form-control" >
                                    </div>

                                    <div class="col-6">
                                        <label for="recipient-name" class="col-form-label"> رصيد العميل بالمصرى</label>

                                        <input  name="client_raseed" type="number" step="0.01" class="form-control" >
                                    </div>
                                    <div class="col-4 d-none">
                                        <label for="recipient-name" class="col-form-label"> (optional) if Suppplier  </label>

                                        <select  name="sup_id"  class="form-control " >
                                            <option value=""  selected>Select From Suppplier</option>
                                            @foreach ($all_supplier as $sup)
                                            <option value="{{$sup->id}}">{{$sup->name}}</option>

                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label">  تليفون العميل 1 </label>

                                        <input  name="tel01" type="number" class="form-control"
                                            >
                                    </div>
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label">  تليفون العميل 2 </label>

                                        <input  name="tel02" type="number" class="form-control"
                                            >
                                    </div>
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label">  تليفون العميل 3  </label>

                                        <input  name="tel03" type="number" class="form-control"
                                           >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label">  صورة السجل التجاري </label>

                                        <input  name="segl_togary" type="file" class="form-control"
                                            >
                                    </div>
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label">   صورة البطاقة الضريبية </label>

                                        <input  name="betaa_darebia" type="file" class="form-control"
                                           >
                                    </div>

                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label">  الرقم القومى </label>

                                        <input  name="national_no" type="number" class="form-control"
                                           >
                                    </div>
                                </div>
                                <hr>
                                <div class="row pt-3 d-none" id="div_image_client">
                                    <img style="width: 200px; height: 100px;" class="add_logo_image" src=""
                                        class="rounded border" alt="">
                                </div>
                                <div class="row">
                                    <label for="recipient-name" class="col-form-label">   صورة العميل  </label>
                                    <input type="file" class="form-control p-2" id="add_logo" name="client_img">
                                </div>


                                <div class="row">
                                    <label for="recipient-name" class="col-form-label">  ملاحظات </label>
                                    <div class="col-12">
                                        <textarea name="notes" cols="30" rows="5" class="form-control"></textarea>
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
        var all_clients = {!! $all_clients !!}
        // console.log(all_clients);
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
                            var printTitle = '  العملاء ';
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
                        "targets": [0 ,6,8]
                    },
                    {
                        "className": "dt-center",
                        "targets": "_all"
                    }
                ],


                columns: [

                    {
                        data: "id" , visible:false
                    },
                    {
                        data: "name"
                    },
                    {
                        data: "client_img",
                        "render": function(data) {
                            if (data) {
                                return '<img  class="avatar-img img-thumbnail rounded-5 itemimg" src="client_images/' +
                                    data +
                                    '" width="40px">';


                            } else {
                                return '<span class="text-primary">لا يوجد صورة</span> ';

                            }
                        }
                    },

                    {
                        data: "address"
                    },
                    {
                        data: "tel01"
                    },
                    {
                        data: "tel02" , visible:false
                    },
                    {
                        data: "tel03" , visible:false
                    },
                    {
                        data: "email1"
                    },
                    {
                        data: "email2" , visible:false
                    },
                    {
                        data: "segl_togary",
                        "render": function(data) {
                            if (data) {
                                return '<img  class="avatar-img img-thumbnail rounded-5 itemimg" src="segl_togary_images/' +
                                    data +
                                    '" width="40px">';


                            } else {
                                return '<span class="text-primary">لا يوجد صورة</span> ';

                            }
                        }
                    },
                    {
                        data: "betaa_darebia",
                        "render": function(data) {
                            if (data) {
                                return '<img  class="avatar-img img-thumbnail rounded-5 itemimg" src="betaa_darebia_images/' +
                                    data +
                                    '" width="40px">';


                            } else {
                                return '<span class="text-primary">لا يوجد صورة</span> ';

                            }
                        }
                    },
                    {
                        data: "national_no"
                    },
                    {
                        data: "notes"
                    },

                    {
                        data: "client_raseed",
                        "render": function(data,row) {
                            if (data) {
                                return data;


                            } else {
                                return 0;

                            }
                        }
                    },
                    {
                        data: null,
                          "render": function(data,row) {
                             
                            if (data['sup_id']) {
                                // return data['sup_id'];
                              return  '<i class="fs-22 mdi mdi-check-bold "></i>';


                            } else {
                                return `<button  class="btn btn-info  " onclick='convert_to_sup(${data["id"]})'>Use</button>`;

                            }
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
            itemtbl.rows.add(all_clients).draw();
            itemtbl.buttons().container().appendTo('#itemtbl_wrapper .col-md-6:eq(0)');

        });
    </script>
    <script>
        $('#itemtbl tbody').on('click', '.edit_item', function() {
            $("#client_id").val('');
            $("#client_name").val('');
            $("#client_address").val('');
            $("#client_telephone1").val('');
            $("#client_telephone2").val('');
            $("#client_telephone3").val('');
            $("#client_betaa_darebia").val('');
            $("#client_segl_togary").val('');
            $("#client_email1").val('');
            $("#client_email2").val('');
            $('.displayImagee').attr('src', '');
            $("#client_national_no").val('');
            $("#client_notes").text('');
            $("#client_raseed").val('');
            $("#sup_id").val('').trigger('change');
            var currentRow = $(this).closest("tr");
            var currentRow1 = itemtbl.row($(this).closest("tr")).data();
            console.log(currentRow1);
            $("#client_id").val(currentRow1.id);
            $("#client_name").val(currentRow1.name);
            if (currentRow1.client_img) {
                $("#div_image_edit").removeClass('d-none');
                $('.displayImagee').attr('src','client_images/'+currentRow1.client_img);
            } else {
                $("#div_image_edit").addClass('d-none');
                $('.displayImagee').attr('src', '');

            }
            $("#client_address").val(currentRow1.address);
            $("#client_telephone1").val(currentRow1.tel01);
            $("#client_telephone2").val(currentRow1.tel02);
            $("#client_telephone3").val(currentRow1.tel03);
            $("#client_email1").val(currentRow1.email1);
            $("#client_email2").val(currentRow1.email2);

            $("#client_national_no").val(currentRow1.national_no);
            $("#client_notes").val(currentRow1.notes);

            $("#client_raseed").val(parseFloat(currentRow1.client_raseed));
            if (currentRow1.sup_id) {
                $("#sup_id").val(currentRow1.sup_id).trigger('change');

            }



        });

       $('#itemtbl tbody').on('click', '.delete_item', function() {
            $("#client_id_delete").val('');
            $("#client_name_delete").val('');
            var currentRow =  itemtbl.row($(this).closest("tr")).data();
            console.log(currentRow);
            $("#client_id_delete").val(currentRow.id);
            $("#client_name_delete").val(currentRow.name);



        });
    </script>
    <script>
        $('#client_image_edit').on('change', function() {
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
                    $("#div_image_client").removeClass('d-none');

                    $('.add_logo_image').attr('src', e.target.result);
                    all_clients



                }
                reader.readAsDataURL(input.files[0]);
            } else {


            }




        });
           function convert_to_sup (id){
        //   flage = check_if_added(id);
        //    alert(flage[0].id);
            // if(flage.length==0){
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

                            use_as_sup(id);
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
            // }else{
            //     Swal.fire('هذا المورد موجود كعميل مسبقاً ');
            // }
        }
         function use_as_sup(client_id){
            $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    url: "{{ route('useSupAsSupplier') }}",
                                    async: false,
                                    type: 'POST',
                                    data: {
                                        'data': client_id
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
         
    </script>




@endsection
