@extends('layouts.posMaster')
@section('css')
    <style>
        #datatable_processing {
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

        .input-box {
            position: relative;
        }

        .input-box i {
            position: absolute;
            right: 13px;
            top: 15px;
            color: #ced4da;

        }

        .form-control1 {

            height: 50px;
            background-color: #eeeeee69;
        }

        .form-control1:focus {
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

        .rowx {
            margin: 0 -5px;
        }

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
                    <table id="datatable"
                        class="table table-striped table-bordered cell-border fw-bold display  dt-responsive dataTable dtr-inline no-footer"
                        style="font-family: 'Cairo';width:100%">
                        <thead style="background:#5fcee78a">
                            <tr>

                                <td>view</td>
                                <td>Name</td>
                                <td>EmaraNumber</td>
                                <td>Source</td>
                                <td>Status</td>
                                <td>Quality</td>
                                <td>Amount</td>
                                <td>Section</td>
                                <td>price</td>
                                <td>Pnumber</td>
                                <td>send</td>
                                <td>action</td>
                            </tr>
                        </thead>

                    </table>

                </div>
            </div>
        </div>
    </div>

    <!-- section Modal -->
    <div class="modal fade" id="sectionMdl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="sectionMdlLabel" aria-hidden="true">
        <form action="">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sectionMdlLabel">عرض أقسام المخزن</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="search" class="form-control mb-2" name="" id="searchSectionTxt">
                        <div class="row">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="ClientMdl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="ClientMdlLabel" aria-hidden="true">
        <form action="">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ClientMdlLabel">العمـــــلاء </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="search" class="form-control mb-2" name="" id="searchclientTxt">
                        {{-- <button class="btn btn-danger float-end">إضــــافة</button> --}}
                        <div class="row mt-2">
                            <div class="col">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>الاسم</th>
                                            <th>عدد مرات الشراء</th>
                                            <th>المديونية</th>
                                            <th>إجمالي مشتروات</th>
                                        </tr>
                                    </thead>
                                    @foreach ($allClients as $client)
                                        <tr class="resclient">
                                            <td>{{ $client->name }}</td>
                                            <td>{{ count($client->invoices) }}</td>
                                            <td>{{ $client->invoices->sum('actual_price') - $client->invoices->sum('paied') - $client->invoices->sum('discount') - $client->invoice_client_madyoneas->sum('paied') }}
                                            </td>
                                            <td>{{ $client->invoices->sum('actual_price') }}</td>
                                            <td><a target="_blank"
                                                    href="Clientinvoice/{{ $client->id }}/{{ $store_data[0]->id }}">عرض
                                                    الفواتير</a></td>
                                            <td><a target="_blank"
                                                    href="Clientinvoiceprice/{{ $client->id }}/{{ $store_data[0]->id }}">عرض
                                                    الأسعار</a></ {{-- <td>
                                            <button type="button" class="btn btn-info">سداد مديونية </button>
                                        </td> --}} </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="inboxMdl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="inboxMdlLabel" aria-hidden="true">
        <form action="">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="inboxMdlLabel">الــــــوارد </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <!--<input type="button" value="Accept All" class="btn btn-success" id="accept_all_inbox">-->
                        <!--<input type="button" value="Preview All" class="btn btn-primary" id="accept_preview">-->

                        <div class="table-responsive">
                            <table id="transtbl" name=""
                                class="display table table-bordered dt-responsive dataTable dtr-inline"
                                style="width:100%">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>storeLogId</th>
                                        <th class="text-center">PartID</th>
                                        <th class="text-center">AllPartID</th>
                                        <th class="text-center">Part</th>
                                        <th>Source</th>
                                        <th>SourceID</th>
                                        <th>Status</th>
                                        <th>StatusID</th>
                                        <th>Quality</th>
                                        <th>QualityID</th>

                                        <th class="text-center">Type </th>
                                        <th>TypeId </th>
                                        <th class="text-center">Store_action </th>
                                        <th class="text-center">StoreActionId </th>
                                        <th class="text-center">Amount </th>
                                        <th class="text-center">Store </th>
                                        <th class="text-center">SrtorId </th>
                                        <th class="text-center">date </th>
                                        <th class="text-center">sublier </th>
                                        <th class="text-center">status </th>
                                        <th class="text-center">Notes</th>
                                        <th class="text-center">Enter Amount</th>
                                        <th class="text-center">Save</th>

                                    </tr>
                                </thead>
                                <tbody id="parts_details_edit">
                                    {{-- @if ($store_inbox)
                                                @foreach ($store_inbox as $data)
                                                    <tr>
                                                        <td>{{$data->id}}</td>
                                                        <td>{{$data->All_part_id}}</td>
                                                        <td>{{$data->id}}</td>
                                                        <td>{{$data->id}}</td>
                                                        <td>{{$data->id}}</td>
                                                        <td>{{$data->id}}</td>
                                                        <td>{{$data->id}}</td>
                                                        <td>{{$data->id}}</td>
                                                        <td>{{$data->id}}</td>
                                                        <td>{{$data->id}}</td>
                                                        <td>{{$data->id}}</td>
                                                        <td>{{$data->id}}</td>
                                                    </tr>
                                                @endforeach
                                            @endif --}}
                                </tbody>


                            </table>


                        </div>



                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="storeMdl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="storeMdlLabel" aria-hidden="true">
        <form action="">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeMdlLabel">رسال إلى مخزن</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="storetbl" class="display table table-bordered dt-responsive dataTable dtr-inline"
                                style="width:100%">
                                <thead>
                                    <tr>

                                        <th>PartID</th>
                                        <th>StoreLogId</th>
                                        <th>OrderSupId</th>
                                        <th>TypeId</th>
                                        <th>Part</th>
                                        <th>Type</th>
                                        <th>Source</th>
                                        <th>SourceID</th>
                                        <th>Status</th>
                                        <th>StatusID</th>
                                        <th>Quality</th>
                                        <th>QualityID</th>
                                        <th>Amount</th>
                                        <th>Enter Amount</th>
                                        <th>Select Store</th>
                                        <th>Send </th>
                                        <!--<th>Ask </th>-->


                                    </tr>
                                </thead>
                                <tbody id="parts_details_edit1">
                                    <!--    {{-- @if ($store_inbox)-->
                                        <!--    @foreach ($store_inbox as $data)-->
                                        <!--        <tr>-->
                                        <!--            <td>{{$data->id}}</td>-->
                                        <!--            <td>{{$data->All_part_id}}</td>-->
                                        <!--            <td>{{$data->id}}</td>-->
                                        <!--            <td>{{$data->id}}</td>-->
                                        <!--            <td>{{$data->id}}</td>-->
                                        <!--            <td>{{$data->id}}</td>-->
                                        <!--            <td>{{$data->id}}</td>-->
                                        <!--            <td>{{$data->id}}</td>-->
                                        <!--            <td>{{$data->id}}</td>-->
                                        <!--            <td>{{$data->id}}</td>-->
                                        <!--            <td>{{$data->id}}</td>-->
                                        <!--            <td>{{$data->id}}</td>-->
                                        <!--        </tr>-->
                                        <!--    @endforeach-->
                                        <!--@endif --}}-->
                                </tbody>


                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="example11Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header ">
                    <!--<h5 class="modal-title" id="exampleModalLabel">Filter</h5>-->
                    <h3 class="text-center w-100 ">فــاتورة البيع</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!--///////////////////////////////////////////////////////////////-->


                <form action="/printpos" method="POST" class="fs-20 p-5">
                    @csrf
                    <input type="hidden" name="storeId" value="{{ $store_data[0]->id }}">
                    <div class="row">
                         <div class="col-1 m-0 my-2 p-0 text-end">
                            <button type="button"  class="AddnewClient btn m-0 p-0"><i class="fs-2 mdi mdi-plus-circle text-secondary"></i></button>
                        </div>
                        <div class="col-1 text-end">
                            <label>العمــــــيل</label>
                        </div>
                        <div class="col-4 ">

                            <select name="client" id="clientSlct" class="form-control" required>
                                <option selected disabled value="">إختر العميــــــل</option>
                                @foreach ($clients as $client)
                                    <option
                                        data-mad="{{ round($client->invoices->sum('actual_price') - $client->invoices->sum('paied') - $client->invoices->sum('discount') - $client->invoice_client_madyoneas->sum('paied') + $client->servicesMad) - $client->as_sup_madunia - $client->client_raseed }}"
                                         data-sup_id="{{ $client->sup_id }}"
                                        value="{{ $client->id }}">{{ $client->name }} /
                                        {{ $client->tel01 }}</option>
                                @endforeach

                                {{-- {{ $client->invoices->sum('actual_price') - $client->invoices->sum('paied') - $client->invoices->sum('discount') - $client->invoice_client_madyoneas->sum('paied') }} --}}
                            </select>
                            <span>حساب العميل : </span>
                            <span id="madClientTxt">00</span><br>
                            <!--<span id="">( + ) مديونية</span>-->
                            <!--<span id="">( - ) رصيد</span>-->
                        </div>
                        <div class="col-2 ">
                            <label>نـــــــــوع البيع</label>
                        </div>

                        <div class="col-4 ">

                            <select name="saleTypeSlct" id="saleTypeSlct" class="form-control" required>
                                <option selected disabled value="">إختر التســــــعيرة </option>
                                @foreach ($allprices as $price)
                                    @if ($price->id == 5)
                                        <option value="{{ $price->id }}" >{{ $price->type }} </option>
                                    @else
                                        <option value="{{ $price->id }}">{{ $price->type }} </option>
                                    @endif
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col table-responsive">
                            <table class="table text-center" id="invoiceItems">
                                <thead>
                                    <tr>
                                        <td>Item</td>
                                        <td>Qty</td>
                                        <td>Price</td>
                                        <td>Total</td>
                                        <td>Remove</td>
                                        <td>weight</td>
                                    </tr>
                                </thead>

                            </table>
                            <hr>
                            <div class="" id="taxesTbl">
                                <h6>Taxes</h6>
                                <div class="row">
                                    @foreach ($alltaxes as $tax)
                                        <div class="col-lg-4">
                                            <div class="btn-group btn-group-toggle " data-toggle="buttons">
                                                <label class="btn bg-light text-nowrap fs-18">
                                                    <input name="taxes[]"  style="position: relative" type="checkbox" value="{{ $tax->value }}">
                                                    {{ $tax->name }} (
                                                    {{ $tax->value }} % )
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-4">

                                </div>
                                <div class="col-lg-4">
                                    <span>Total Item Weight :</span>
                                    <span id="totalweight">0</span> / KG
                                    <input type="hidden" name="totalweight" id="totalweighttxt" value="0">
                                </div>
                                <div class="col-lg-4">

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-3 ">
                                    <span>Total : </span>
                                    <span id="total" class=" rounded bg-light">0</span>
                                    <input type="hidden" name="total" id="totaltxt" value="0">
                                </div>
                                <div class="col-lg-3 ">
                                    <span>Taxes : </span>
                                    <span id="taxval">0</span>
                                    <input type="hidden" name="taxval" id="taxvaltxt" value="0">

                                </div>
                                <div class="col-lg-3 ">
                                    <span>SubTotal :</span>
                                    <span id="subtotal">0</span>
                                    <input type="hidden" name="subtotal" id="subtotaltxt" value="0">
                                </div>
                                <div class="col-lg-3 ">
                                    <span>Payment Method </span>
                                    <select class="form-select mt-1" name="payment" id="paymentslect" required>
                                        <option selected disabled value="">إختر البنك </option>
                                  

                                        @foreach ($bank_types as $bank)
                                            <option class="text-center" type-name="bank"
                                                value="{{ $bank->accountant_number }}">{{ $bank->bank_name }}
                                            </option>
                                        @endforeach


                                        @foreach ($store_safe as $safe)
                                            <option class="text-center" type-name="store"
                                                value="{{ $safe->safe_accountant_number }}">{{ $safe->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>

                            <div class="row">

                            </div>
                            <hr>
                            <div class="row" id="cashpay">

                                <div class="col mb-3">
                                    <label for="" class="form-label">paid</label>
                                    <input type="text" class="form-control" name="invPaied" required=""
                                        id="invPaied" aria-describedby="helpId" placeholder="">

                                </div>
                                <div class="col mb-3">
                                    <label for="" class="form-label"> Discount ( بالجنية )</label>
                                    <input type="text" class="form-control" name="invDiscount" value="0"
                                        id="invDiscount" aria-describedby="helpId" placeholder="">


                                </div>
                                <div class="col mb-3">
                                    <label for="" class="form-label">Remain ( مديونية ) </label>

                                    <input type="text" class="form-control" readonly="" name="invMad"
                                        value="0" id="invMad" aria-describedby="helpId" placeholder="">

                                </div>

                            </div>

                        </div>
                    </div>
                    <button class="btn " style="background-color:#38598b">بيع</button>
                    <!--<button class="btn " style="background-color:#a2a8d3" type="button">إذن اخراج</button>-->
                    <button class="btn " style="background-color:#e7eaf6" type="button"
                        onclick="saveClientPrice(this)">كشف أسعار</button>
                </form>

                <!--/////////////////////////////////////////////////////////////////////-->
            </div>
        </div>
    </div>

    <div class="modal fade" id="example11Modal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header ">
                    <!--<h5 class="modal-title" id="exampleModalLabel">Filter</h5>-->
                    <h3 class="text-center w-100 ">بحث </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!--///////////////////////////////////////////////////////////////-->

                <form method="" id="filterForm" action="#">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col">
                                <h3>النـــوع : </h3>
                                <select onchange="DTsearch(15,this)" id="" class="form-select" name=""
                                    id="">
                                    <option disabled selected>Select</option>
                                    <option value="">الكل</option>
                                    <option value="قطع غيار">قطع غيار</option>
                                    <option value="كيت">كيت</option>
                                    <option value="كاوتش">كاوتش</option>
                                    <option value="جرارات">جرارات</option>
                                    <option value="كلارك">كلارك</option>
                                    <option value="معدات">معدات</option>

                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h4>الاسم : </h4>
                                <input onkeyup="DTsearch(2 ,this)" id="FilterNametxt" type="text" name=""
                                    class="form-control" id="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h4>الرقم : </h4>
                                <input onkeyup="DTsearch(3 ,this)" id="FilterNumbertxt" type="text" name=""
                                    class="form-control" id="">
                            </div>
                        </div>
                        {{-- <div class="row ">
                                    <div class="col mb-5">
                                        <h4>السعر : </h4>
                                        <div id="slider"></div>
                                    </div>
                                </div> --}}
                        <div class="row">
                            <div class="col">
                                <h4>Groups : </h4>
                                <select onchange="DTsearch(8,this)" id="FilterGroupSlct" class="form-select"
                                    name="" id="">
                                    <option value="" selected disabled>Select</option>
                                    <option value="">All</option>
                                    @foreach ($allGroups as $group)
                                        <option value="{{ $group->name }}">{{ $group->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h4>Sub Groups : </h4>
                                <select onchange="DTsearch(7,this)" id="FilterSGroupSlct" class="form-select"
                                    name="" id="">
                                    <option value="" selected disabled>Select</option>
                                    <option value="">All</option>
                                    @foreach ($allSGroups as $sgroup)
                                        <option value="{{ $sgroup->name }}">{{ $sgroup->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h4>Brand Type : </h4>
                                <select onchange="DTsearch(12,this)" id="FilterBtypeSlct" class="form-select"
                                    name="" id="">
                                    <option value="" selected disabled>Select</option>
                                    <option value="">All</option>
                                    @foreach ($Btype as $ty)
                                        <option value="{{ $ty->name }}">{{ $ty->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h4>Brand : </h4>
                                <select onchange="DTsearch(11,this)" id="FilterBrandSlct" class="form-select"
                                    name="" id="">
                                    <option value="" selected disabled>Select</option>
                                    <option value="">All</option>
                                    @foreach ($allbrand as $br)
                                        <option value="{{ $br->name }}">{{ $br->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h4>Model : </h4>
                                <select onchange="DTsearch(10,this)" id="FilterModelSlct" class="form-select"
                                    name="" id="">
                                    <option value="" selected disabled>Select</option>
                                    <option value="">All</option>
                                    @foreach ($allmodel as $md)
                                        <option value="{{ $md->name }}">{{ $md->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h4>Series : </h4>
                                <select onchange="DTsearch(9,this)" id="FilterSeriesSlct" class="form-select"
                                    name="" id="">
                                    <option value="" selected disabled>Select</option>
                                    <option value="">All</option>
                                    @foreach ($allseries as $ser)
                                        <option value="{{ $ser->name }}">{{ $ser->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <!--<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
                        <button type="button"
                            onclick="this.form.reset();$('#example11Modal select').val(-1).trigger('change');partsDt.columns().search('').draw();"
                            class="btn btn-info w-100">بحث جديد</button>
                        <!--<button type="button" class="btn btn-light" onclick="partsDt.columns().search('').draw();">الغاء البحث</button>-->
                    </div>
                </form>


                <!--/////////////////////////////////////////////////////////////////////-->
            </div>
        </div>
    </div>

    <div class="modal fade" id="partShowMdl" tabindex="-1" aria-labelledby="partShowMdlLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!--<div class="modal-header">-->
                <!--  <h5 class="modal-title" id="partShowMdlLabel">ItemDetails</h5>-->
                <!--  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                <!--</div>-->
                <div class="modal-body">

                    <div class="row">
                        <div class="col">
                            <img alt="" src="" class="img">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="infoMdl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="infoMdlLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 80vw !important;">
            <div class="modal-content w-100" style="width: 100%!important">
                <div class="modal-header ">
                    <h5 class="modal-title" id="infoMdlLabel">Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="card-body text-center float-start w-50">

                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col-lg-4  align-self-xxl-center">
                                            <p class="card-title" id="itemName">Product Title</p>
                                            <p class="card-text" style="cursor: pointer;text-decoration: underline;"
                                                onclick="$('#pList').toggle()">Price: $ <span id="itemPrice">0</span>
                                            </p>
                                            <div class="row" id="pList" style="display:none">
                                                <div class="col">
                                                    <ul id="" class="mt-3 itemPriceList list-group">
                                                        <li>No Pricing List</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="input-group d-none">

                                                <input type="button" value="+" class="button-plus"
                                                    data-field="quantity">
                                                <input class="form-control" type="number" step="1" max=""
                                                    value="1" name="quantity" class="quantity-field" readonly
                                                    style="margin-right: 14px !important;">
                                                <input type="button" value="-" class="button-minus"
                                                    data-field="quantity">

                                            </div>
                                            <a href="#" class="btn btn-primary mt-2 w-100" id="addtocardMdl">Add to
                                                Cart</a>
                                            <input type="number" class="form-control itemAmunt" placeholder="Amount"
                                                value="1" aria-label="Username" aria-describedby="basic-addon1"
                                                style="width: 50% !important;">

                                        </div>
                                        <div class="border border-bottom-0 border-top-0 col-lg-4">
                                            <p class="card-text" id="itemDesc">Product Description</p>
                                            <p class="card-text" id="itemQuality">Product Description</p>

                                            <p class="card-text"><span id="itemStock">0</span></p>
                                            <p class="card-text" id="itemGroup">Availability: Ships within 2-3
                                                business days</p>

                                        </div>
                                        <div class="col-lg-4 border border-bottom-0 border-top-0">
                                            <p id="itemNum"></p>
                                            <ul id="iteSps" class="list-group-horizontal">

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div id="productCarousel" class="carousel slide d-inline-block w-100"
                                        data-bs-ride="carousel">

                                        <div class="carousel-inner" id="item-image-car">

                                        </div>
                                        <a class="carousel-control-prev " href="#productCarousel" role="button"
                                            data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon text-bg-dark"
                                                aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </a>
                                        <a class="carousel-control-next " href="#productCarousel" role="button"
                                            data-bs-slide="next">
                                            <span class="carousel-control-next-icon text-bg-dark"
                                                aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </a>
                                    </div>
                                    {{-- *********************************************************** --}}
                                    {{-- <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-indicators">
                                  <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                  <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                  <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                </div>
                                <div class="carousel-inner" id="item-image-car">

                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                  <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                  <span class="visually-hidden">Next</span>
                                </button>
                              </div> --}}

                                    {{-- *************************************************** --}}
                                </div>
                            </div>

                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <nav>
                                <div class="nav nav-pills nav-justified" id="nav-tab" role="tablist">
                                    <button style="text-wrap: nowrap;" class="nav-link " id="nav-home-tab"
                                        data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab"
                                        aria-controls="nav-home" aria-selected="true">ITEM
                                        APPLICATIONS</button>
                                    <button style="text-wrap: nowrap;" class="nav-link" id="nav-profile-tab"
                                        data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">FLIP
                                        CODES</button>
                                    <button style="text-wrap: nowrap;" class="nav-link" id="nav-contact-tab"
                                        data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab"
                                        aria-controls="nav-contact" aria-selected="false">Specifications</button>
                                    <button style="text-wrap: nowrap;" class="nav-link" id="nav-contact-tab"
                                        data-bs-toggle="tab" data-bs-target="#nav-4" type="button" role="tab"
                                        aria-controls="nav-4" aria-selected="false">Stores</button>
                                    <button style="text-wrap: nowrap;" class="nav-link" id="nav-contact-tab"
                                        data-bs-toggle="tab" data-bs-target="#nav-5" type="button" role="tab"
                                        aria-controls="nav-5" aria-selected="false">Price</button>
                                    <button style="text-wrap: nowrap;" class="nav-link" id="nav-contact-tab"
                                        data-bs-toggle="tab" data-bs-target="#nav-6" type="button" role="tab"
                                        aria-controls="nav-6" aria-selected="false">suggistion parts</button>
                                    <button style="text-wrap: nowrap;" class="nav-link" id="nav-contact-tab"
                                        data-bs-toggle="tab" data-bs-target="#nav-7" type="button" role="tab"
                                        aria-controls="nav-7" aria-selected="false">related parts</button>
                                    <button style="text-wrap: nowrap;" class="nav-link" id="nav-contact-tab"
                                        data-bs-toggle="tab" data-bs-target="#nav-15" type="button" role="tab"
                                        aria-controls="nav-15" aria-selected="false">^</button>

                                </div>
                                <div class="input-box">
                                    <input type="search" name="searchtables" id="searchtables"
                                        class="form-control m-2 w-10">
                                    {{-- <i class="bx bx-search"></i> --}}
                                </div>

                                {{-- <input type="search" name="searchtables" id="searchtables" class="w-100"> --}}
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade " id="nav-home" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="row">
                                        <div class="col-lg-12" id="containerLL">

                                            {{-- <table class="bradtbl table table-striped table-bordered cell-border " style="width:100%">
                                        <thead style="background:#5fcee78a">
                                            <tr>
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Brand</th>
                                                <th class="text-center">Model</th>
                                                <th class="text-center">Serie</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemModels">

                                        </tbody>
                                    </table> --}}
                                            <nav>
                                                <div class="nav nav-pills nav-justified" id="nav-tabss" role="tablist">
                                                    {{-- <button class="nav-link " id="nav-type-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-type" type="button" role="tab"
                                                aria-controls="nav-type" aria-selected="true">Types <i class="bx bx-menu-alt-left"></i></button>
                                            <button class="nav-link" id="nav-brand-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-brand" type="button" role="tab"
                                                aria-controls="nav-brand" aria-selected="false">Brands <i class="bx bx-menu-alt-left"></i></button>
                                            <button class="nav-link" id="nav-model-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-model" type="button" role="tab"
                                                aria-controls="nav-model" aria-selected="false">Models <i class="bx bx-menu-alt-left"></i></button>
                                            <button class="nav-link" id="nav-series-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-series" type="button" role="tab"
                                                aria-controls="nav-series"
                                                aria-selected="false">Series <i
                                                    class="bx bx-menu-alt-left"></i></button> --}}

                                                </div>
                                            </nav>


                                            <div class="tab-content" id="nav-tabContent1">
                                                <div class="tab-pane fade " id="nav-type" role="tabpanel"
                                                    aria-labelledby="nav-type-tab">

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <ul class="font-weight-bold list-group " id="p_type">

                                                            </ul>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-content" id="nav-tabContent2">
                                                <div class="tab-pane fade " id="nav-brand" role="tabpanel"
                                                    aria-labelledby="nav-brand-tab">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <ul class="font-weight-bold list-group " id="p_brand">

                                                            </ul>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-content" id="nav-tabContent3">
                                                <div class="tab-pane fade " id="nav-model" role="tabpanel"
                                                    aria-labelledby="nav-model-tab">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <ul class="font-weight-bold list-group " id="p_model">

                                                            </ul>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-content" id="nav-tabContent4">
                                                <div class="tab-pane fade " id="nav-series" role="tabpanel"
                                                    aria-labelledby="nav-series-tab">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <ul class="font-weight-bold list-group " id="p_series">

                                                            </ul>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                    aria-labelledby="nav-profile-tab">
                                    <div class="row">

                                        <div class="col-lg-12" id="containerNUM">


                                            <nav>
                                                <div class="nav nav-pills nav-justified" id="nav-tabssnum"
                                                    role="tablist">

                                                </div>
                                            </nav>


                                            <div class="tab-content" id="nav-tabContent1">
                                                <div class="tab-pane fade " id="nav-type" role="tabpanel"
                                                    aria-labelledby="nav-type-tab">

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <ul class="font-weight-bold list-group " id="p_type">

                                                            </ul>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-lg-12 d-none">
                                            <table class="table table-striped table-bordered cell-border "
                                                style="width:100%">
                                                <thead style="background:#5fcee78a">
                                                    <tr>
                                                        <th class="text-center">Number</th>
                                                        <th class="text-center">Supplier</th>
                                                        <th class="text-center">Type</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="itemNumbers">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="row">
                                        <div class="col-lg-12">

                                            <table class="table table-striped table-bordered cell-border "
                                                style="width:100%">
                                                <thead style="background:#5fcee78a">
                                                    <tr>
                                                        <th class="text-center">Name</th>
                                                        <th class="text-center">value</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="itemSpecs">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-4" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="row">
                                        <div class="col-lg-12">

                                            <table class="table table-striped table-bordered cell-border "
                                                style="width:100%">
                                                <thead style="background:#5fcee78a">
                                                    <tr>
                                                        <th class="text-center">Store Name</th>
                                                        <th class="text-center">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="itemStores">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-5" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">


                                    <div class="row">
                                        <div class="col-lg-12">

                                            <table class="table table-striped table-bordered cell-border "
                                                style="width:100%">
                                                <thead style="background:#5fcee78a">
                                                    <tr>
                                                        <th class="text-center"> Price Type</th>
                                                        <th class="text-center">Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="itemPriceList" class="itemPriceList">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane fade" id="nav-6" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="row rowx" id="relatedpartDiv">

                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav-7" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="row rowx" id="relatedpartDiv1">



                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!--<h2 onclick="$('#relatedpartDiv').toggle()" style="cursor: pointer;" class="rounded-4 text-bg-dark text-center">Suggestion part</h2>-->
                    <!--<div class="row" id="relatedpartDiv" style="display: none;">-->

                    <!--</div>-->
                    <!--<h2 onclick="$('#relatedpartDiv1').toggle()" style="cursor: pointer;" class="rounded-4 text-bg-primary text-center">Related part</h2>-->
                    <!--<div class="row" id="relatedpartDiv1" style="display: none;">-->

                    <!--</div>-->
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col"></div>
                        <div class="col"></div>
                        <div class="col"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sendMdl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="sendMdlLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 80vw !important;">
            <div class="modal-content w-100" style="width: 100%!important">
                <div class="modal-header ">
                    <h5 class="modal-title" id="sendMdlLabel">Send to Store</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" id="sendFormss" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-lg-6">
                                <h2 id="itemNameS">000</h2>
                            </div>
                            <div class="col-lg-6">
                                <h4>إجمالى الكمية بالمخزن <span id="itemAmountS">00</span></h4>
                            </div>
                        </div>
                        <input type="hidden" name="partIdS" id="partIdS" value="0">
                        <input type="hidden" name="partTypeS" id="partTypeS" value="0">
                        <input type="hidden" name="partSourceS" id="partSourceS" value="0">
                        <input type="hidden" name="partStatusS" id="partStatusS" value="0">
                        <input type="hidden" name="partQualityS" id="partQualityS" value="0">
                        <input type="hidden" name="CurrentstoreId" id="CurrentstoreId"
                            value="{{ $store_data[0]->id }}">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for=""> إرسال إالى مخزن</label>
                                <select name="storeId" id="storeId" class="form-control" required>
                                    <option value="">إختر المخزن</option>
                                    @foreach ($allStores as $key => $value)
                                        @if ($value->id == $store_data[0]->id)
                                        @else
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6" class="">
                                <label for=""> إجمالى الكمية المرسلة</label>
                                <input type="number" name="sendAmount" id="totalAmountNum" value="0"
                                    class="form-control" id="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <table
                                    class=" mt-4 table table-striped table-bordered cell-border fw-bold display  dt-responsive dataTable dtr-inline no-footer"
                                    style="font-family: 'Cairo';width:100%">
                                    <thead style="background:#5fcee78a">
                                        <tr>
                                            <td>id</td>
                                            <th>القسم</th>
                                            <th>موجود </th>
                                            <th>كمية</th>
                                        </tr>
                                    </thead>
                                    <tbody id="send_sectiontbl">

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div>
                            <button type="button" id="sendToStoreBtn" class="btn btn-info mt-3 w-50">Save</button>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')
    <script>
        var store_data = {!! $store_data !!};
        var allStores = {!! $allStores !!};
        var store_inbox = {!! $store_inbox !!};
    </script>
    <script src={{ URL::asset('js/pos.js') }}></script>

    <script>
        // var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        // var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        //     return new bootstrap.Tooltip(tooltipTriggerEl)
        // })
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            store_data = {!! $store_data !!};
            partsDt = $('#datatable').DataTable({
                dom: '<"dt-buttons"Bf><"clear">lirtp',

                "language": {
                    "zeroRecords": "جاري تحميــــــــــــــــــــــــــــل البيانات ....",
                    // "processing": "Loading. Please wait..."

                    "processing": "<i class='fs-1 mdi mdi-cog-outline mdi-spin'></i><i class='fs-1 mdi mdi-cog-outline mdi-spin'></i><i class='fs-1 mdi mdi-cog-outline mdi-spin'></i>"

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
                            columns: [1, 4]
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
                    url: "{{ route('allData') }}",
                    type: "GET",
                    data: function(data) {
                        // data.search = $('input[type="search"]').val();
                        data.storeId = store_data[0].id
                    }
                },
                order: ['1', 'DESC'],
                // processing: true,
                pageLength: 50,


                aoColumns: [{
                        data: 'view',
                    },
                    {
                        data: 'name',
                    },
                    {
                        data: 'EmaraNumber',
                    },
                    {
                        data: 'source',
                    },
                    {
                        data: 'status',
                    },
                    {
                        data: 'quality',
                    },
                    {
                        data: 'amount',
                    },
                    {
                        data: 'section',
                    },
                    {
                        data: 'price',
                    },
                    {
                        data: 'partnumbers',
                    },
                    {
                        data: 'send',
                    },
                    {
                        data: 'action',
                    },

                    // {
                    //     data: 'id',
                    //     width: "20%",

                    // }
                ]
            });


        });
    </script>
    <script>
        function SendToStoreNew(sections, partId, SourceId, StatusId, QualityId, name, Totalamount, typeId) {
            sectiontbl_html = [];
            if (sections.length > 0) {

                const groupedData = sections.reduce((acc, item) => {
                    if (!acc[item.section_id]) {
                        acc[item.section_id] = [];
                    }
                    acc[item.section_id].push(item);
                    // acc[item.section_name].push(item.store_structure.name);
                    return acc;
                }, {});

                const sumByType = Object.keys(groupedData).map(section_id => {
                    const sumOfIds = groupedData[section_id].reduce((sum, item) => sum + item.amount, 0);
                    const store_structure = groupedData[section_id][0].store_structure;
                    return {
                        section_id,
                        store_structure,
                        sumOfIds
                    };
                });


                for (let i = 0; i < sumByType.length; i++) {
                    if (sumByType[i].sumOfIds > 0) {
                        sectiontbl_html.push(`<tr>
                        <td>${sumByType[i].section_id}</td>
                        <input type="hidden" name="sectionIds[]" id="" value="${sumByType[i].section_id}">
                        <td>${sumByType[i].store_structure.name}</td>
                        <td>${sumByType[i].sumOfIds}</td>
                        <td><input class="form-control border sectionAmount" name="sectionAmount[]" type="number" max=${sumByType[i].sumOfIds}  min="1" value="0" > </td>
                        </tr>

                    `)
                    }

                }

            } else {
                sectiontbl_html.push(`<tr>
                        <td></td>
                        <td>لم يتم توزيعة على الاقسام</td>
                        <td></td>
                        <td><input type="number" readonly class="form-control border sectionAmount" name="sectionAmount[]" type="number"  value="0" > </td>

                        </tr>

                    `)
            }
            $("#send_sectiontbl").html(sectiontbl_html);
            $("#itemNameS").text(name);
            $("#itemAmountS").text(Totalamount);
            $("#partIdS").val(partId);
            $("#partTypeS").val(typeId);
            $("#partSourceS").val(SourceId);
            $("#partStatusS").val(StatusId);
            $("#partQualityS").val(QualityId);

            $("#sendMdl").modal('toggle');
            $("#totalAmountNum").val(0);

        }

        $("#sendToStoreBtn").click(function(e) {
            e.preventDefault();
            var formData = $("#sendFormss").serializeArray();
            var sendtotAmount = parseInt($("#totalAmountNum").val());
            var store_idd = parseInt($("#storeId").val());
            var totaltAmount = parseInt($("#itemAmountS").text());
            var sum = 0;
            $(".sectionAmount").each(element => {
                sum += parseInt($($(".sectionAmount")[element]).val());

            });
            if (totaltAmount < sendtotAmount) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'الكمية المطلوبة أكبر من الموجود فى المخزن',
                    footer: 'Not In Store  '
                });

            } else {
                //ajax SendToStoreNew
                if (sum == sendtotAmount && sendtotAmount <= totaltAmount && sendtotAmount > 0 && store_idd > 0) {
                    // alert('done');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",

                        url: "SendToStoreNew",
                        data: formData,

                        datatype: 'JSON',
                        statusCode: {
                            404: function() {
                                alert("page not found");
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            // alert("some error");
                            console.log(errorThrown);
                        },
                        success: function(data) {
                            console.log(data);
                            if (data == 1) {
                                Swal.fire('Transaction Accepted');
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: ' برجاء مراجعة البيانات ',
                                    footer: 'Not In Store  '
                                });
                            }

                            $("#sendMdl").modal('toggle');

                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'برجاء مراجعة الكميات',
                        footer: 'Not In Store  '
                    });
                }
            }

        })
        var flage = 0;
        $(document).on('keyup', '.sectionAmount', function() {
            flage = 0;
            var sendtotAmount = parseInt($("#totalAmountNum").val());
            var totaltAmount = parseInt($("#itemAmountS").text());
            var sum = 0;
            var need_from_sec = 0;
            need_from_sec = parseInt($(this).val()) > 0 ? parseInt($(this).val()) : 0;
            var maxamount = parseInt($(this).attr('max'));
            $(".sectionAmount").each(element => {
                sum += parseInt($($(".sectionAmount")[element]).val());

            });
            if (maxamount > need_from_sec && need_from_sec >= 0) {


                if (sendtotAmount == 0) {
                    if (sum > totaltAmount) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'الكمية المطلوبة من الاقسام أكبر من الموجود فى المخزن',
                            footer: 'Not In Store  '
                        });
                        $(this).val(0);
                    } else {
                        flage = 1;
                    }
                } else {
                    if (sum > sendtotAmount) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'الكمية المطلوبة من الاقسام اكبر من المطلوب ',
                            footer: 'Not In Store  '
                        });

                    } else {
                        flage = 1;
                    }

                }

            } else {
                $(this).val(parseInt($(this).attr('max')));
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'القسم لايكفى الكمية المطلوبة  ',
                    footer: 'Not In Store  '
                });
            }

        });

        $(document).on('keyup', '#totalAmountNum', function() {
            var tot_need = parseInt($(this).val());
            var totaltAmount = parseInt($("#itemAmountS").text());
            if (tot_need > 0 && tot_need < totaltAmount)
                var remain = 0;

            var need = tot_need;
            $(".sectionAmount").each(element => {
                var maxamount = parseInt($($(".sectionAmount")[element]).attr('max'));

                remain = need - maxamount;
                if (remain > 0) {
                    $($(".sectionAmount")[element]).val(maxamount)
                    need -= maxamount;
                } else {
                    $($(".sectionAmount")[element]).val(need)
                    remain = 0;
                    need -= need;
                }


            });
        });
    </script>
@endsection
