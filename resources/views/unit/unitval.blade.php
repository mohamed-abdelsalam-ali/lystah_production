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
    Units values
@stop


@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-primary " style="z-index: 88888 !important;" role="alert">
            <button type="button" class="btn-close bg-black text-white" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ $message }}
        </div>
    @endif
    <div class="main-content">
        <div class="page-content">
           
            <div class="row">
                <p class="fs-17 text-bg-dark text-center"> قيم الوحدات</p>
                <div class="card p-0">
                    <div class="card-body table-responsive">
                        <div class="col-12 px-1 mt-3 text-end">
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                إضافة </button>
                               
                        </div>
                        <table class="table table-striped table-bordered cell-border " style="font-size: smaller;width:100%"
                            id="itemtbl">
                            <thead style="background: #67b1736e;">
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="d-none">id</th>
                                    <th class="d-none">unit_id</th>
                                    <th class="sorting sorting_asc">الوحدة</th>
                                    <th>القيمة</th>
                                    <th class="d-none"> parent_id</th>
                                    <th>من الوحدة</th>
                                    <th> تعديل</th>
                                    <th>حذف </th>
                                    


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
    <div class="modal fade" id="staticBackdrop_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> تعديل القيمة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('unitValedit') }}" method="post" enctype="multipart/form-data">
                    {{ method_field('post') }}
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <input name="item_id"  id="item_id" type="hidden" class="form-control">
                                <div class="row p-3">
                                    <div class="col-12">
                                        <label class="col-form-label"> إسم الوحدة  </label>
                                        <select name="unit_e"  id="unit_e" class="form-control">
                                            <option value="">إختر الوحدة</option>
                                            @foreach ($unit as $item)
                                            <option value="{{$item->id}}"> {{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="row p-3">
                                    <div class="col-12">
                                        <label  class="col-form-label"> القيمة </label>

                                        <input name="value_e" type="number" step="any" id="unit_val" class="form-control">
                                    </div>
                                </div>
                                <div class="row p-3">
                                    <div class="col-12">
                                        <label  class="col-form-label"> إسم الوحدة  </label>
                                        <select name="p_unit_e"  id="p_unit_e" class="form-control">
                                            <option value="">إختر الوحدة</option>
                                            @foreach ($unit as $item)
                                            <option value="{{$item->id}}"> {{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        
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

    <div class="modal fade" id="staticBackdrop_delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Delete Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('unitValdel') }}" method="post">
                    {{ method_field('post') }}
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row p-3">
                                    <div class="col-12">
                                        <input id="unit_id_delete" name="unit_id_delete" type="hidden" class=""
                                            required="">
                                        <label for="recipient-name" class="col-form-label"> سيتم حذف هذه الوحدة </label>

                                        <input id="unit_name_delete" name="name" type="text" class="form-control"
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

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> تقييم وحدة جديدة </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('unitValAdd') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">


                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row p-3">
                                    <div class="col-12">

                                        <label for="recipient-name" class="col-form-label"> إسم الوحدة  </label>
                                        <select name="unit_a" class="form-control">
                                            <option value="">إختر الوحدة</option>
                                            @foreach ($unit as $item)
                                            <option value="{{$item->id}}"> {{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="row  p-3">
                                    <label for="recipient-name" class="col-form-label">  القيمة  </label>
                                    <div class="col-12">
                                        <input type="number" class="form-control" step="any" name="value_a" >
                                    </div>
                                </div>
                                <div class="row  p-3">
                                    <div class="col-12">

                                        <label for="recipient-name" class="col-form-label">  الوحدة الأخرى  </label>
                                        <select name="p_unit_a" class="form-control">   
                                            <option value="">إختر الوحدة</option>
                                            @foreach ($unit as $item)
                                            <option value="{{$item->id}}"> {{$item->name}}</option>
                                            @endforeach
                                        </select>
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
    <script>
        let unit_value = {!! $unit_value !!}


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
                            columns: [2,3, 5]

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
                        "targets": [0,1,4]
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
                        data: "unit.id"
                    },
                    {
                        data: "unit.name"
                    },
                    {
                        data: "value"
                    },
                    {
                        data: "p_unit.id"
                    },
                    {
                        data: "p_unit.name"
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
            itemtbl.rows.add(unit_value).draw();
            // itemtbl.buttons().container().appendTo('#itemtbl_wrapper .col-md-6:eq(0)');

        });
    </script>
    <script>
        $('#itemtbl tbody').on('click', '.edit_item', function() {
            // $("#unit_e").val('');
            // $("#unit_name").val('');
            $("#item_id").val('');

            var currentRow = $(this).closest("tr");
            $("#item_id").val(currentRow.find("td:eq(0)").text());

            $("#unit_e").val(currentRow.find("td:eq(1)").text()).trigger('change');
            $("#p_unit_e").val(currentRow.find("td:eq(4)").text()).trigger('change');
            $("#unit_val").val(currentRow.find("td:eq(3)").text());



        });

        $('#itemtbl tbody').on('click', '.delete_item', function() {
            $("#unit_id_delete").val('');
            $("#unit_name_delete").val('');
            var currentRow = $(this).closest("tr");
            $("#unit_id_delete").val(currentRow.find("td:eq(0)").text());
            $("#unit_name_delete").val(currentRow.find("td:eq(2)").text());



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
