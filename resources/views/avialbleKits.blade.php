
@extends('layouts.master')
@section('css')
<style>


    #datatable_processing{
        position: fixed;
        top: 30%;
        left: 45%;
    }
    .cards tbody tr {
        float: left;
        width: 18%;
        margin: 0.5rem;
        border: 0.0625rem solid rgba(0, 0, 0, .125);
        border-radius: .25rem;
        box-shadow: 0.25rem 0.25rem 0.5rem rgba(0, 0, 0, 0.25);
    }

    .cards tbody tr td svg {
        width: 100% !important;
    }

    .cards img {
        display: block !important;
        width: 100% !important;
        height: 200px !important;
    }

    .cards tbody td {
        display: block;
    }

    .cards .cardin {
        display: inline !important;
    }

    .cards thead {
        display: none;
    }

    .cards td:before {
        content: attr(data-label);
        position: relative;
        float: left;
        color: #808080;
        min-width: 4rem;
        margin-left: 0;
        margin-right: 1rem;
        text-align: left;
    }

    tr.selected td:before {
        color: #CCC;
    }

    .table .avatar {
        width: 50px;
    }

    .cards .avatar {
        width: 150px;
        margin: 15px;
    }


    #example11Modal1 .modal-dialog {
        width: 35vw;
        left: 0;
        position: absolute;

    }

    .itemAmunt {
        width: 50% !important;
    }

    @font-face {
        font-family: Cairo;
        src: url('../fonts/Cairo-Light.ttf');


    }

    @media (min-width: 320px) and (max-width: 992px) {
        .text-nowrap {
            white-space: normal !important;
        }
    }


    .simplebar-offset {
        margin-top: 10px !important;
    }

    body {
        font-family: Cairo !important;

        width: 100vw !important;
        /* overflow: hidden !important; */
    }


    .pointer {
        cursor: pointer;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    th {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: justify !important;
        /* text-align: center !important; */
    }


    input,
    textarea {
        border: 1px solid #eeeeee;
        box-sizing: border-box;
        margin: 0;
        outline: none;
        padding: 10px;
    }

    input[type="button"] {
        -webkit-appearance: button;
        cursor: pointer;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    .input-group {
        clear: both;
        margin: 15px 0;
        position: relative;
    }

    .input-group input[type='button'] {
        background-color: #eeeeee;
        min-width: 38px;
        width: auto;
        transition: all 300ms ease;
    }

    .input-group .button-minus,
    .input-group .button-plus {
        font-weight: bold;
        height: 38px;
        padding: 0;
        width: 38px;
        position: relative;
    }

    .input-group .quantity-field {
        position: relative;
        height: 38px;
        left: -6px;
        text-align: center;
        width: 62px;
        display: inline-block;
        font-size: 13px;
        margin: 0 0 5px;
        resize: vertical;
    }

    .button-plus {
        left: -13px;
    }

    input[type="number"] {
        -moz-appearance: textfield;
        -webkit-appearance: none;
    }

    @media (min-width: 320px) and (max-width: 992px) {
        table.quickTbl button {
            margin-bottom: 5px !important;
        }
    }

    .loader {
        border: 16px solid #f3f3f3;
        /* Light grey */
        border-top: 16px solid #3498db;
        /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    .zoom {
        padding: 1px;
        background-color: black;
        transition: all ease-in-out;
        /* Animation */
        /*width: 200px;*/
        /*height: 200px;*/
        margin: 0 auto;
    }

    .zoom:hover {
        transform: scale(13.5);
        /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
        margin: 0px 100% 0px 0px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .cssTable td {
        text-align: center;
        vertical-align: middle;
    }

    .dot {
        height: 25px;
        width: 25px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
    }

    .dark-carousel {
        background-color: #333;
        color: #fff;
    }

    .dark-carousel .carousel-caption {
        background-color: rgba(0, 0, 0, 0.6);
    }

    table.quickTbl button {
        font-size: 18px;
        font-weight: bold;
    }

    .fixedbtn {
        position: fixed;
        top: 20%;
        left: 0px;
        border-radius: 0px 50px 50px 0px;
        z-index: 555;
    }

    .fixedbtn1 {
        position: fixed;
        top: 30%;
        left: 0px;
        border-radius: 0px 50px 50px 0px;
        z-index: 555;
    }

    .cssTable th {
        text-align: center !important;
        vertical-align: middle;
    }

    /******************************************************************************/
    .value {
        position: absolute;
        top: 30px;
        left: 50%;
        margin: 0 0 0 -20px;
        width: 40px;
        text-align: center;
        display: block;

        /* optional */

        font-weight: normal;
        font-family: Verdana, Arial, sans-serif;
        font-size: 14px;
        color: #333;
    }

    .price-range-both.value {
        width: 100px;
        margin: 0 0 0 -50px;
        top: 26px;
    }

    .price-range-both {
        display: none;
    }

    .value i {
        font-style: normal;
    }

    /********************************************************************************************/
    .cssTable th::after {
        text-align: left !important;
        vertical-align: middle;
    }

    .cssTable th::before {
        text-align: left !important;
        vertical-align: middle;
    }

    body {
        margin-left: 2em;
        margin-right: 2em;
    }

    #sectionMdl .modal-content {
        width: 100vw !important;
    }

    #sectionMdl .modal-dialog {
        max-width: 100vw !important;
    }

    #ClientMdl .modal-content {
        width: 100vw !important;
    }

    #ClientMdl .modal-dialog {
        max-width: 100vw !important;
    }

    #inboxMdl .modal-content {
        max-width: 100vw !important;
    }

    #inboxMdl .modal-dialog {
        max-width: 100vw !important;
    }

    #storeMdl .modal-content {
        max-width: 100vw !important;
    }

    #storeMdl .modal-dialog {
        max-width: 100vw !important;
    }

    .table-bordered.card {
        border: 0 !important;
    }


    #example.card thead {
        display: none;
    }

    .card tbody tr {
        float: left;
        width: 10em;
        margin: 0.5em;
        padding: 12px;
        border: 1px solid #bfbfbf;
        border-radius: 0.5em;
        background-color: transparent !important;
        box-shadow: 0.25rem 0.25rem 0.5rem rgba(0, 0, 0, 0.25);
        font-size: larger;
    }

    .card tbody tr td img {
        height: 70px;
        position: relative;
        overflow: hidden;
        width: 100%;
    }

    .card tbody tr td {
        display: block;
        border: 0;
    }
    .input-box{
    position: relative;
        }

        .input-box i {
        position: absolute;
        right: 13px;
        top:15px;
        color:#ced4da;

        }

        .form-control1{

        height: 50px;
        background-color:#eeeeee69;
        }

        .form-control1:focus{
        background-color: #eeeeee69;
        box-shadow: none;
        border-color: #eee;
        }
        .column {
        float: left;
        width: 25%;
        padding: 0 10px;
        }
        @media screen and (max-width: 650px) {
        .column {
            width: 100%;
            display: block;
            margin-bottom: 20px;
        }
        }
        @media screen and (max-width: 1200px) {
        .column {
            width: 50%;
            display: block;
            margin-bottom: 20px;
        }
        }

        .cardx {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        padding: 16px;
        text-align: center;
        background-color: #f1f1f1;
        }
        .rowx {margin: 0 -5px;}

        .rowx:after {
        content: "";
        display: table;
        clear: both;
        }
            /*#example11Modal {*/

            /*    top: 0px;*/
            /*    left: 0;*/
            /*    bottom: 0;*/
            /*    z-index: 10040;*/
            /*    overflow: auto;*/
            /*    overflow-y: auto;*/
            /*}*/

            /*#example11Modal .modal-dialog {*/
            /*    margin-left: 0px;*/
            /*    margin-top: 0px;*/
            /*}*/
            #example11Modal .modal-dialog {
                max-width: 80vw !important;
                overflow-y: auto;
            }

            p {
                text-align: center;
                /*color: #0cb853;*/
                font-size: 1.5em;
                font-weight: bold;
                text-shadow: 1px 1px 2px #000;
                margin-bottom: 1.2em;
            }

            .buttons-columnVisibility {
                text-align: center;
                padding: 10px;
            }

            .cairo {
                font-family: Cairo, "Times New Roman", Times, serif;
            }
</style>
@section('content')

    <div class="main-content ">
        <div class="page-content">
            <div class="bg-white mx-2 row">

                <div class="col-12">
                    <table id="datatable" class="table table-striped table-bordered dataTable no-footer dtr-inline cell-border fw-bold "  style="font-family: 'Cairo';width:100%">
                        <thead style="background:#5fcee78a">
                            <tr>

                                {{-- <td>م</td> --}}
                                <td style="text-align: justify;">الطقم</td>
                                <td style="text-align: justify;">المتاح تجمييعه</td>
                                <td style="text-align: justify;">ألأصنــــــــــــاف</td>
                         

                            </tr>
                        </thead>

                    </table>

                </div>
            </div>
        </div>
    </div>



@endsection

@section('js')


    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    $(document).ready(function() {
        $('#datatable').DataTable({
            dom: '<"dt-buttons"Bf><"clear">lirtp',
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            language: {
                zeroRecords: "No Data Founded   ....",
                processing: "<i class='fs-1 mdi mdi-cog-outline mdi-spin'></i><i class='fs-1 mdi mdi-cog-outline mdi-spin'></i><i class='fs-1 mdi mdi-cog-outline mdi-spin'></i>"
            },
            buttons: [
                "colvis",
                "copyHtml5",
                "csvHtml5",
                "excelHtml5",
                "pdfHtml5",
                {
                    extend: 'print',
                    exportOptions: {
                         columns: [2, 1, 0],
                          stripHtml: false
                    }
                },
            ],
            processing: true,
            serverSide: true,
            searching: true,
            search: {
                return: true
            },
            ajax: {
                url: "/availablekitinstore",
                type: "GET"
            },
            order: [[1, 'DESC']],
            pageLength: 50,
            "columnDefs": [
            {
                "targets": 2,
                "render": function (data, type, row, meta) {
                    return data; // Ensure the HTML content is rendered correctly
                }
            }
        ],
            columns: [
                { data: 'itemname' },
                { data: 'itemavialable' },
                {
                    data: 'itemparts',
                    render: function(data, type, row, meta) {
                        return data; // Ensure the HTML content is rendered correctly
                    }
                }
            ],
            columnDefs: [
                {
                    targets: 2,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).html(cellData); // Render HTML content in the cell
                    }
                }
            ]
        });


    });



    </script>

@endsection


