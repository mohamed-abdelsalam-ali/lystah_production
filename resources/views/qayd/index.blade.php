@extends('layouts.master')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.css">
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::asset('assets/dataTables/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/dataTables/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="/assets/libs/sweetalert2/sweetalert2.min.css">
    
    <style>
        .fixedBtn{
            position: fixed;
            top: 17%;
            left: 0px;
            border-radius: 0px 50px 50px 0px;
            z-index: 555;
        }
    </style>
@endsection
@section('title')
    عرض القيود
@endsection



@section('content')

<main role="main" class="main-content ">
    <div class="page-content pb-1">
        <div class="mb-4">
            <h1 class="text-center font1">عرض كافة القيود</h1>
        </div>
        <div class="position-absolute" style="left: 0;"> 
            <button onclick="location.href='qayd/create'" class="btn fixedBtn btn-success">إضافة</button>
        </div>
    </div>
    <div class="bg-white p-2 table-responsive">
        <table class="table table-striped" style="width:100%" id="buyinv_tbl">
            <thead>
                <tr>
                    <th>رقم القيد</th>
                    <th>نوع القيد</th>
                    <th>التاريخ</th>
                    <th>القيد</th>
                    <th>الوصف</th>
                    <th>عرض</th>
                    <!--<th>تعديل</th>-->
                    <!--<th>حذف</th>-->
                </tr>
            </thead>
            <tbody>


            </tbody>

        </table>
    </div>


</main>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('assets/dataTables/dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.js"></script>
    <script src="{{ URL::asset('assets/dataTables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.print.min.js') }}"></script>
    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>

<script>
let allQayd={!! $qayds !!};
console.log(allQayd);
var buyinv_tbl = "";
            $(document).ready(function() {
                //   alert("df")
                buyinv_tbl = $("#buyinv_tbl").DataTable({

                    lengthChange: false,
                    "oLanguage": {
                        "sSearch": "البحث"
                    },

                    buttons: [
                        {
                            extend: 'excel',
                            text: 'Excel <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>'
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>'
                        },

                        'copy', 'colvis',
                        'pdf', {
                            extend: 'print',
                            text: 'طباعة',
                            className: 'bg-danger',
                            exportOptions: {
                                columns: [0, 1, 2, 4, 6, 8, 10, 11]
                            }
                        }

                    ],
                    "columnDefs": [{
                        "className": "dt-center",
                        "targets": "_all"
                    }],


                    rowId: "id",
                    select: true,

                    columns: [

                        {
                            data: "id"
                        },

                        {
                            data: null,
                            render: function (data, type, row, meta) {
                                if(row.qaydtype){
                                     return row.qaydtype.name;
                                }else{
                                    return '--';
                                }
                               
                            }
                        },

                        {
                            data: null,
                            render: function (data, type, row, meta) {
                                if(row.date){
                                return row.date.substring(0,10);
                                }else{
                                      return '--';
                                }
                            }
                        },
                        {
                            data: "accountnumber"
                        },
                         {
                            data: "note"
                        },
                        {

                            data: null,

                            className: "dt-center editor-editt",
                            defaultContent: ``,
                            orderable: false ,
                            render: function (data, type, row, meta) {
                                return  `<form action="" method="POST">
                                                <a class="btn btn-primary" href="qayd/${row.id}">عرض</a>
                                                @csrf
                                                @method('POST')
                                            </form>`;
                            }

                        }
                        ,
                        // {

                        //     data: null,

                        //     className: "dt-center editor-delete",

                        //     defaultContent: '<button class="btn btn-danger">delete</button>',

                        //     orderable: false,
                        //     render: function (data, type, row, meta) {
                        //         return  `<form action="" method="POST">

                        //                         <a class="btn btn-danger" href="deleteInv/${row.id}">حذف</a>

                        //                         @csrf
                        //                         @method('DELETE')
                        //                     </form>`;
                        //     }

                        // }


                    ]

                });
                buyinv_tbl.clear();
                buyinv_tbl.rows.add(allQayd).draw();
                // buyinv_tbl.buttons().container().appendTo('#buyinv_tbl_wrapper .col-md-6:eq(0)');

            });

</script>
@endsection
