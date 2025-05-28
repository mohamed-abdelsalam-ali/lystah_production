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
        #staticBackdrop .modal-content {
        width: 50vw !important;
        /* height: 50vh !important; */

    }
    #staticBackdrop_edit .modal-content {
        width: 50vw !important;
        /* height: 50vh !important; */

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
            <h1 class="text-center text-info">Clients</h1>
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
                        <div class="card-body table-responsive">
                            <table id="itemtbl" class="display table table-bordered dt-responsive dataTable dtr-inline"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="d-none">client_id</th>
                                        <th class="sorting sorting_asc">Name</th>
                                        <th>client_image</th>
                                        <th>address</th>
                                        <th>telephone 1</th>
                                        <th>telephone 2</th>
                                        <th class="d-none">telephone 3</th>
                                        <th>Email1</th>
                                        <th class="d-none">Email2</th>
                                        <th>segel</th>
                                        <th>betaa darebia</th>
                                        <th>national_no</th>
                                        <th>notes</th>
                                        <th></th>
                                        <th></th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="d-none">client_id</th>
                                        <th class="sorting sorting_asc">Name</th>
                                        <th>client_image</th>
                                        <th>address</th>
                                        <th>telephone 1</th>
                                        <th>telephone 2</th>
                                        <th class="d-none">telephone 3</th>
                                        <th>Email1</th>
                                        <th class="d-none">Email2</th>
                                        <th>segel</th>
                                        <th>betaa darebia</th>
                                        <th>national_no</th>
                                        <th>notes</th>
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
                                    <div class="col-6">
                                        <input id="client_id" name="client_id" type="hidden" class="-control"
                                           >
                                        <label for="recipient-name" class="col-form-label"> client Name </label>

                                        <input id="client_name" name="name" type="text" class="form-control"
                                            >
                                    </div>
                                    <div class="col-6">
                                        <label for="recipient-name" class="col-form-label"> client Address </label>

                                        <input id="client_address" name="address" type="text" class="form-control"
                                           >
                                    </div>

                                </div>
                                <div class="row ">
                                    <div class="col-6">
                                        <label for="recipient-name" class="col-form-label"> client Email  </label>

                                        <input id="client_email1" name="email1" type="email" class="form-control"
                                           >
                                    </div>
                                    <div class="col-6">
                                        <label for="recipient-name" class="col-form-label"> client Website </label>

                                        <input id="client_email2" name="email2" type="text" class="form-control"
                                            >
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label"> client telephone 1 </label>

                                        <input id="client_telephone1" name="tel01" type="number" class="form-control"
                                           >
                                    </div>
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label"> client telephone 2 </label>

                                        <input id="client_telephone2" name="tel02" type="number" class="form-control"
                                            >
                                    </div>
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label"> client telephone 3 </label>

                                        <input id="client_telephone3" name="tel03" type="number" class="form-control"
                                           >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label">  سجل تجاري </label>

                                        <input  name="segl_togary" id="client_segl_togary" type="text" class="form-control"
                                            >
                                    </div>
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label">   بطاقة ضريبة </label>

                                        <input  name="betaa_darebia" id="client_betaa_darebia" type="text" class="form-control"
                                           >
                                    </div>

                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label"> National Number </label>

                                        <input id="client_national_no" name="national_no" type="number" class="form-control"
                                           >
                                    </div>
                                </div>
                                <div class="row pt-3  d-none" id="div_image_edit">
                                    <img style="width: 200px; height: 100px;" class="displayImagee" src=""
                                        class="rounded border" alt="">
                                </div>
                                <input type="file" class="form-control" name="client_img" id="client_image_edit">
                                <div class="row">
                                    <label for="recipient-name" class="col-form-label"> Client notes </label>
                                    <div class="col-12">
                                        <textarea name="notes" id="client_notes" cols="30" rows="5" class="form-control"></textarea>
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
    <div class="modal fade" id="staticBackdrop_delete" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Delete client</h5>
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
                    <h5 class="modal-title" id="staticBackdropLabel"> Add New client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('client.store') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row ">
                                    <div class="col-6">

                                        <label for="recipient-name" class="col-form-label"> client Name </label>

                                        <input  name="name" type="text" class="form-control"
                                           >
                                    </div>
                                    <div class="col-6">
                                        <label for="recipient-name" class="col-form-label"> client Address </label>

                                        <input  name="address" type="text" class="form-control"
                                           >
                                    </div>

                                </div>
                                <div class="row ">
                                    <div class="col-6">

                                        <label for="recipient-name" class="col-form-label"> client Email  </label>

                                        <input  name="email1" type="email" class="form-control"
                                           >
                                    </div>
                                    <div class="col-6">
                                        <label for="recipient-name" class="col-form-label"> client Website  </label>

                                        <input  name="email2" type="text" class="form-control"
                                           >
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label"> client telephone 1 </label>

                                        <input  name="tel01" type="number" class="form-control"
                                            >
                                    </div>
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label"> client telephone 2 </label>

                                        <input  name="tel02" type="number" class="form-control"
                                            >
                                    </div>
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label"> client telephone 3 </label>

                                        <input  name="tel03" type="number" class="form-control"
                                           >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label">  سجل تجاري </label>

                                        <input  name="segl_togary" type="text" class="form-control"
                                            >
                                    </div>
                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label">   بطاقة ضريبة </label>

                                        <input  name="betaa_darebia" type="text" class="form-control"
                                           >
                                    </div>

                                    <div class="col-4">
                                        <label for="recipient-name" class="col-form-label"> National Number </label>

                                        <input  name="national_no" type="number" class="form-control"
                                           >
                                    </div>
                                </div>
                                <hr>
                                <div class="row pt-3 d-none" id="div_image_client">
                                    <img style="width: 200px; height: 100px;" class="add_logo_image" src=""
                                        class="rounded border" alt="">
                                </div>
                                <input type="file" class="form-control p-2" id="add_logo" name="client_img">
                                <div class="row">
                                    <label for="recipient-name" class="col-form-label"> Client notes </label>
                                    <div class="col-12">
                                        <textarea name="notes" cols="30" rows="5" class="form-control"></textarea>
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
        var all_clients = {!! $all_clients !!}
        console.log(all_clients);
        var itemtbl = "";
        $(document).ready(function() {
            itemtbl = $("#itemtbl").DataTable({
                // "oLanguage": {
                //     "sSearch": "البحث"
                // },
                // lengthChange: false,
                // "responsive": {
                //     details: true,
                // },
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
                        "targets": [0 ,6,8]
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
                        data: "tel02"
                    },
                    {
                        data: "tel03"
                    },
                    {
                        data: "email1"
                    },
                    {
                        data: "email2"
                    },
                    {
                        data: "segl_togary"
                    },
                    {
                        data: "betaa_darebia"
                    },
                    {
                        data: "national_no"
                    },
                    {
                        data: "notes"
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
            var currentRow = $(this).closest("tr");
            $("#client_id").val(currentRow.find("td:eq(0)").text());
            $("#client_name").val(currentRow.find("td:eq(1)").text());
            if (currentRow.find("td:eq(2)").children().attr('src')) {
                $("#div_image_edit").removeClass('d-none');
                $('.displayImagee').attr('src', currentRow.find("td:eq(2)").children().attr('src'));
            } else {
                $("#div_image_edit").addClass('d-none');
                $('.displayImagee').attr('src', '');

            }
            $("#client_address").val(currentRow.find("td:eq(3)").text());
            $("#client_telephone1").val(currentRow.find("td:eq(4)").text());
            $("#client_telephone2").val(currentRow.find("td:eq(5)").text());
            $("#client_telephone3").val(currentRow.find("td:eq(6)").text());
            $("#client_email1").val(currentRow.find("td:eq(7)").text());
            $("#client_email2").val(currentRow.find("td:eq(8)").text());
            $("#client_segl_togary").val(currentRow.find("td:eq(9)").text());
            $("#client_betaa_darebia").val(currentRow.find("td:eq(10)").text());
            $("#client_national_no").val(currentRow.find("td:eq(11)").text());
            $("#client_notes").val(currentRow.find("td:eq(12)").text());



        });

        $('#itemtbl tbody').on('click', '.delete_item', function() {
            $("#client_id_delete").val('');
            $("#client_name_delete").val('');
            var currentRow = $(this).closest("tr");
            $("#client_id_delete").val(currentRow.find("td:eq(0)").text());
            $("#client_name_delete").val(currentRow.find("td:eq(1)").text());



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
    </script>




@endsection
