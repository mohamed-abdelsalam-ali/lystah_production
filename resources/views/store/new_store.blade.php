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
    Stores
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
                <p class="fs-17 text-bg-dark text-center">المخازن</p>
                <div class="card p-0">
                    <div class="card-body table-responsive">
                        <div class="col-12 px-1 mt-3 text-end">
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                إضافة </button>
                                
                        </div>
                        <table class="table table-striped table-bordered cell-border " style="font-size: smaller;width:100%" id="itemtbl">
                            <thead  style="background: #67b1736e;">
                                    <tr>
                                        <th class="d-none">store_id</th>
                                        <th class="sorting sorting_asc">إسم المخزن</th>
                                        <th>الموقع</th>
                                        <th>العنوان</th>
                                        <th>التليفون</th>
                                        <th>ملاحظة</th>
                                        <th>رقم حساب المخزن</th>
                                        <th>رقم حساب الخزنة</th>
                                        <th>تعديل</th>
                                        <th>حذف</th>


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
                    <h5 class="modal-title" id="staticBackdropLabel"> تعديل بيانات المخزن</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('storesv.update', 'test') }}" method="post" enctype="multipart/form-data">
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <div class="modal-body">


                       
                        <div class="row p-3">
                            <div class="col-lg-12">
                                <label for="recipient-name" class="col-form-label">  إسم المخزن </label>
                                <input name="store_id" id="store_id" type="hidden" class="form-control">
                                <input name="name" id="store_name" type="text" class="form-control"
                                    required="">
                            </div>

                            
                        </div>
                        <div class="row p-3">
                            <div class="col-lg-6">
                                <label for="recipient-name" class="col-form-label">  الموقع  </label>
                                <input name="location" id="location" type="text" class="form-control"
                                    required="">
                            </div>
                            <div class="col-lg-6">
                                <label for="recipient-name" class="col-form-label">  العنوان  </label>
                                <input name="adress" id="adress" type="text" class="form-control"
                                    required="">
                            </div>

                            
                        </div>
                        <div class="row p-3">
                            <div class="col-lg-6">
                                <label for="recipient-name" class="col-form-label">  التليفون 1   </label>
                                <input name="tel01" id="tel01" type="text" class="form-control"
                                    required="">
                            </div>
                            <div class="col-lg-6">
                                <label for="recipient-name" class="col-form-label">  التليفون 2  </label>
                                <input name="tel02" id="tel02" type="text" class="form-control"
                                    required="">
                            </div>

                        </div>
                        <div class="row p-3">

                            <label for="recipient-name" class="col-form-label"> وصف </label>
                            <div class="col-12">
                                <textarea name="desc" cols="30" rows="5" id="store_desc" class="form-control"></textarea>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Delete Store</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('storesv.destroy', 'test') }}" method="post">
                    {{ method_field('Delete') }}
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row p-3">
                                    <div class="col-12">
                                        <input id="store_id_delete" name="store_id" type="hidden" class=""
                                            required="">
                                        <label for="recipient-name" class="col-form-label"> سيتم حذف هذا المخزن  </label>

                                        <input id="store_name_delete" name="name" type="text"
                                            class="form-control" readonly>
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
                    <h5 class="modal-title" id="staticBackdropLabel">   إضافة مخزن جديد </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('storesv.store') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">


                        
                        <div class="row p-3">
                            <div class="col-lg-12">
                                <label for="recipient-name" class="col-form-label">  إسم المخزن </label>
                                <input name="store_id"  type="hidden" class="form-control">
                                <input name="name"  type="text" class="form-control"
                                    required="">
                            </div>

                            
                        </div>
                        <div class="row p-3">
                            <div class="col-lg-6">
                                <label for="recipient-name" class="col-form-label">  الموقع  </label>
                                <input name="location"  type="text" class="form-control"
                                    required="">
                            </div>
                            <div class="col-lg-6">
                                <label for="recipient-name" class="col-form-label">  العنوان  </label>
                                <input name="adress"  type="text" class="form-control"
                                    required="">
                            </div>

                            
                        </div>
                        <div class="row p-3">
                            <div class="col-lg-6">
                                <label for="recipient-name" class="col-form-label">  التليفون 1   </label>
                                <input name="tel01"  type="text" class="form-control"
                                    required="">
                            </div>
                            <div class="col-lg-6">
                                <label for="recipient-name" class="col-form-label">  التليفون 2  </label>
                                <input name="tel02"  type="text" class="form-control"
                                    required="">
                            </div>

                        </div>
                        <div class="row p-3">

                            <label for="recipient-name" class="col-form-label"> وصف </label>
                            <div class="col-12">
                                <textarea name="desc" cols="30" rows="5"  class="form-control"></textarea>
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

    <script>
        var all_Stores = {!! $all_Stores !!}
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
                            var printTitle = ' وحدات القياس ';
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
                        data: "name"
                    },
                    {
                        data: "location"
                    }, {
                        data: "address"
                    }, {
                        data: "tel01"
                    }, {
                        data: "note"
                    }, {
                        data: "accountant_number"
                    },
                    {
                        data: "safe_accountant_number"
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
            itemtbl.rows.add(all_Stores).draw();
            itemtbl.buttons().container().appendTo('#itemtbl_wrapper .col-md-6:eq(0)');

        });
    </script>
    <script>
        $('#itemtbl tbody').on('click', '.edit_item', function() {
            $("#store_id").val('');
            $("#store_name").val('');
            $("#location").val('');
            $("#adress").val('');
            $("#tel01").val('');
            $("#tel02").val('');
            $("#store_desc").val('');
            var table = $('#example').DataTable();

            var currentRow = $(this).closest("tr");
            var rowData = itemtbl.row(currentRow).data(); 
            console.log(rowData);
            $("#store_id").val(rowData['id']);
            $("#store_name").val(rowData['name']);
            $("#location").val(rowData['location']);
            $("#adress").val(rowData['address']);
            $("#tel01").val(rowData['tel01']);
            $("#tel02").val(rowData['tel02']);
            $("#store_desc").val(rowData['note']);


        });

        $('#itemtbl tbody').on('click', '.delete_item', function() {
            $("#store_id_delete").val('');
            $("#store_name_delete").val('');
            var currentRow = $(this).closest("tr");
            $("#store_id_delete").val(currentRow.find("td:eq(0)").text());
            $("#store_name_delete").val(currentRow.find("td:eq(1)").text());



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
