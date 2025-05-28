@extends('layouts.posMaster')

@section('css')
    <style>



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
@endsection
@section('content')

    <div class="main-content ">
        <div class="page-content">
            <div class="row">
                <div class="col text-center">
                    <!--<div class="input-group mb-3">-->
                    <!--  <span class="input-group-text" id="basic-addon1">بحث</span>-->
                    <!--  <input type="text" class="form-control" placeholder="Search Here" aria-label="Username" aria-describedby="basic-addon1">-->
                    <!--</div>-->
                </div>

            </div>
            {{-- <button type="button" class="btn fixedbtn text-white" style="background-color:#38598b" data-bs-toggle="modal"
                data-bs-target="#example11Modal">

                <i class="mdi fs-22 mdi-cart-variant">
                    <span class=" topbar-badge cartitem-badge fs-11 translate-middle badge rounded-pill bg-info"
                        style="position:absolute !important;top:0px !important" id="basketCounterLbl">0</span>
                </i>
            </button>

            <button type="button" class="btn fixedbtn1 text-white" style="background-color:#38598b" data-bs-toggle="modal"
                data-bs-target="#example11Modal1">

                <i class="mdi fs-22 mdi-filter-outline"></i>
            </button> --}}
            <input id="input-to-fill" class="opacity-0" type="text" contenteditable="true" placeholder="Barcode Result">
            <div class="row bg-body">

                <div class="border  col-lg-12  table-responsive bg-white">
                    <div class="overflow-hidden h-100 tableOverlay text-center w-100 ">
                        <div class="loader" style="margin: 100px auto;"></div>
                    </div>
                    <table id="example" class="table table-striped table-bordered cell-border " style="width:100%">
                        <thead style="background:#5fcee78a">
                            <tr>
                                <th>View</th>
                                <th>Image </th>
                                <th>Name </th>
                                <th>Numbers </th>
                                <th>Price </th>
                                <th>Amount </th>
                                <th>Sections</th>
                                <th>Sub Groups</th>
                                <th>Groups</th>
                                <th>Series</th>
                                <th>Model</th>
                                <th>Brand</th>
                                <th>Category</th>
                                <th>All price</th>
                                <th>Stores</th>
                                <th>Type</th>
                                <th>Actions </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($allItems as $item)
                                @if ($item['type_id'] == 1)
                                    <!--part-->
                                    <tr>

                                        @php
                                            $imgName = '';
                                            //if (count($item['image']) > 0) {
                                              //  $imgName = $item['image'][0]->image_name;
                                            //} else {
                                              //  $imgName = 'tractor-solid.png';
                                            //}
                                        @endphp
                                        @if ($item['type_id'] == 1 && count($item['allparts']) >= 0)
                                            <!--part-->

                                            <td style="cursor: pointer;"
                                                onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
                                                <!--<img class="d-none" src="assets\part_images\{{ $imgName }}"-->
                                                <!--    alt="">-->
                                            @else
                                            <td>
                                        @endif


                                        <i class="fs-2 mdi mdi-eye px-2 text-secondary"></i>

                                        </td>

                                        <td>
                                            <h6>FN{{ $item['type_id'] }}{{ $item['p_data']->id }}{{ $item['source'][0]->id }}{{ $item['status'][0]->id }}{{ $item['quality'][0]->id }}
                                            </h6>
                                            <!--<img class=" header-profile-user zoom"-->
                                            <!--    src="assets/part_images/{{ $imgName }}" alt="Emara">-->
                                            <!--<span-->
                                            <!--    class="barcodeTxt  ">{{ $item['type_id'] }}-{{ $item['p_data']->id }}-{{ $item['source'][0]->id }}-{{ $item['status'][0]->id }}-{{ $item['quality'][0]->id }}</span>-->
                                            <svg class="barcode ">

                                            </svg>

                                        </td>
                                        @if ($item['type_id'] == 1 && count($item['allparts']) > 0)
                                            <!--part-->
                                            <td onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
                                            @else
                                            <td>
                                        @endif

                                        {{ $item['p_data']->name }}
                                        <br><span>{{ $item['source'][0]->name_arabic }}</span>
                                        <span>{{ $item['status'][0]->name }}</span>
                                        <span>{{ $item['quality'][0]->name }}</span>
                                        <span class="d-none">FN{{ $item['type_id'] }}{{ $item['p_data']->id }}{{ $item['source'][0]->id }}{{ $item['status'][0]->id }}{{ $item['quality'][0]->id }}</span>
                                        </td>
                                        <td>
                                            <span class="cardin d-none">الأرقام : </span>
                                            @if (isset($item['p_data']->part_numbers))
                                                @for ($i = 0; $i < count($item['p_data']->part_numbers); $i++)
                                                    <li onclick="">
                                                        {{ $item['p_data']->part_numbers[$i]->number }}</li>
                                                @endfor
                                            @else
                                                <li>لا يوجد ارقام</li>
                                            @endif
                                        </td>
                                        {{-- <td>{{ $item['source'][0]->name_arabic }}</td>
                                    <td>{{ $item['status'][0]->name }}</td> --}}
                                        <td> <span class="cardin d-none">السعر :
                                            </span>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}
                                        </td>
                                        <td><span class="cardin d-none">الكمية : </span>{{ $item['Tamount'] }}</td>
                                        <td>
                                            <span class="cardin d-none">المكان : </span>
                                            @if(isset($item['section']))
                                                @php
                                                    $arrIn = [];
                                                @endphp
                                                @for ($i = 0; $i < count($item['section']); $i++)
                                                    @if(in_array($item['section'][$i]->store_structure->id , $arrIn))
                                                    
                                                    @else
                                                         @php
                                                            array_push($arrIn,$item['section'][$i]->store_structure->id);
                                                        @endphp
                                                    
                                                    <li class="sectionList d-inline">
                                                        {{ $item['section'][$i]->store_structure->name }}</li>
                                                    @endif
                                                @endfor
                                            @else
                                                <li class="sectionList d-inline">لا يوجد</li>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="cardin d-none">sub Group : </span>
                                            @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                @for ($i = 0; $i < count($item['groups']); $i++)
                                                    <li>{{ $item['groups'][$i]->sub_group->name }} /
                                                        {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="cardin d-none">Group : </span>
                                            <!--@if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))-->
                                            <!--    @for ($i = 0; $i < count($item['groups']); $i++)-->
                                            <!--        <li> {{ $item['groups'][$i]->sub_group->group->name }} </li>-->
                                            <!--    @endfor-->
                                            <!--@else-->
                                            <!--    <li>لا يوجد</li>-->
                                            <!--@endif-->
                                        </td>
                                        <td>
                                            <span class="cardin d-none">Series : </span>
                                            @if (isset($item['models']))
                                                @for ($i = 0; $i < count($item['models']); $i++)
                                                    <li>{{ $item['models'][$i]->series->name }} </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="cardin d-none">Models : </span>
                                            @if (isset($item['models']))
                                                @for ($i = 0; $i < count($item['models']); $i++)
                                                    <li> {{ $item['models'][$i]->series->model->name }} </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="cardin d-none">Brands : </span>
                                            <!--@if (isset($item['models']))-->
                                            <!--    @for ($i = 0; $i < count($item['models']); $i++)-->
                                            <!--        <li> {{ $item['models'][$i]->series->model->brand->name }}-->
                                            <!--        </li>-->
                                            <!--    @endfor-->
                                            <!--@else-->
                                            <!--    <li>لا يوجد</li>-->
                                            <!--@endif-->
                                        </td>
                                        <td>
                                            <span class="cardin d-none">Type : </span>
                                            <!--@if (isset($item['models']))-->
                                            <!--    @for ($i = 0; $i < count($item['models']); $i++)-->
                                            <!--        <li> {{ $item['models'][$i]->series->model->brand_type->name }}-->
                                            <!--        </li>-->
                                            <!--    @endfor-->
                                            <!--@else-->
                                            <!--    <li>لا يوجد</li>-->
                                            <!--@endif-->
                                        </td>
                                        <td>
                                            <span class="cardin d-none">Price : </span>
                                            @if (isset($item['price']))
                                                @for ($i = 0; $i < count($item['price']); $i++)
                                                    <li> {{ $item['price'][$i]->price }}
                                                    </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="cardin d-none">المخازن : </span>
                                            @if (isset($item['stores']))
                                                @for ($i = 0; $i < count($item['stores']); $i++)
                                                    <li> {{ $item['stores'][$i]->name }} /
                                                        {{ $item['stores'][$i]->storepartCount }}
                                                    </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="cardin d-none">النوع : </span>
                                            <li> {{ $item['type_N'] }} </li>
                                        </td>
                                        <td>
                                            <div class="input-group mb-3 addBtn">
                                                <input type="number" class=" itemAmunt" placeholder="Amount" value="1"
                                                    aria-label="Username" aria-describedby="basic-addon1">
                                                <button class="btn cardbtn" style="background-color:#29badb"
                                                    onclick="addtoInvoice(this,{{isset($item['allparts'][0]) ? $item['allparts'][0]->part->part_details : 0}},{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }},1,{{ isset($item['price'][0]->price) ? json_encode($item['price']) : 0 }})">
                                                    <i class="fs-22 mdi mdi-cart-variant"></i>
                                                </button>

                                            </div>

                                        </td>

                                    </tr>
                                @elseif ($item['type_id'] == 2)
                                    <!--wheel 0000000000000000000000000000-->
                                    <tr>

                                        <!--@php-->
                                        <!--    $imgName = '';-->
                                        <!--    if (count($item['image']) > 0) {-->
                                        <!--        $imgName = $item['image'][0]->image_name;-->
                                        <!--    } else {-->
                                        <!--        $imgName = 'tractor-solid.png';-->
                                        <!--    }-->
                                        <!--@endphp-->
                                        @if ($item['type_id'] == 2 && count($item['allparts']) > 0)
                                            <!--part-->
                                            <td style="cursor: pointer;"
                                                onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
                                                <!--<img class="d-none" src="assets\wheel_images\{{ $imgName }}"-->
                                                <!--    alt="">-->
                                            @else
                                            <td>
                                        @endif


                                        <i class="fs-2 mdi mdi-eye px-2 text-secondary"></i>

                                        </td>
                                        <td>
                                            <h5>FN{{ $item['type_id'] }}{{ $item['p_data']->id }}{{ $item['source'][0]->id }}{{ $item['status'][0]->id }}{{ $item['quality'][0]->id }}
                                            </h5>

                                            <!--<img class=" header-profile-user zoom"-->
                                            <!--    src="assets/kit_images/{{ $imgName }}" alt="Emara">-->
                                            <!--<span-->
                                            <!--    class="barcodeTxt  ">{{ $item['type_id'] }}-{{ $item['p_data']->id }}-{{ $item['source'][0]->id }}-{{ $item['status'][0]->id }}-{{ $item['quality'][0]->id }}</span>-->
                                            <svg class="barcode ">

                                            </svg>

                                        </td>
                                        <td>{{ $item['p_data']['name'] }}
                                            <br><span>{{ $item['source'][0]->name_arabic }}</span>
                                            <span>{{ $item['status'][0]->name }}</span>
                                            <span>{{ $item['quality'][0]->name }}</span>
                                            <span class="d-none">FN{{ $item['type_id'] }}{{ $item['p_data']->id }}{{ $item['source'][0]->id }}{{ $item['status'][0]->id }}{{ $item['quality'][0]->id }}</span>
                                        </td>
                                        <td>
                                            <span class="cardin d-none">الأرقام : </span>
                                            @if (isset($item['p_data']->part_numbers))
                                                @for ($i = 0; $i < count($item['p_data']->part_numbers); $i++)
                                                    <li onclick="">
                                                        {{ $item['p_data']->part_numbers[$i]->number }}</li>
                                                @endfor
                                            @else
                                                <li>لا يوجد ارقام</li>
                                            @endif
                                        </td>

                                        <td><span class="cardin d-none">السعر :
                                            </span>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}
                                        </td>
                                        <td><span class="cardin d-none">الكمية : </span>{{ $item['Tamount'] }}</td>
                                        <td><span class="cardin d-none">المكان : </span>
                                            @if (isset($item['section']) && isset($item['section'][$i]->store_structure->name))
                                                @for ($i = 0; $i < count($item['section']); $i++)
                                                    <li class="sectionList d-inline">
                                                        {{ $item['section'][$i]->store_structure->name }}</li>
                                                @endfor
                                            @else
                                                <li class="sectionList d-inline">لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">Sub Group : </span>
                                            @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                @for ($i = 0; $i < count($item['groups']); $i++)
                                                    <li>{{ $item['groups'][$i]->sub_group->name }} /
                                                        {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="cardin d-none">Group : </span>
                                            <!--@if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))-->
                                            <!--    @for ($i = 0; $i < count($item['groups']); $i++)-->
                                            <!--        <li> {{ $item['groups'][$i]->sub_group->group->name }} </li>-->
                                            <!--    @endfor-->
                                            <!--@else-->
                                            <!--    <li>لا يوجد</li>-->
                                            <!--@endif-->
                                        </td>
                                        <td><span class="cardin d-none">Series : </span>
                                            @if (isset($item['models']))
                                                @for ($i = 0; $i < count($item['models']); $i++)
                                                    <li>{{ $item['models'][$i]->series->name }} </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="cardin d-none">Model : </span>
                                            <!--@if (isset($item['models']))-->
                                            <!--    @for ($i = 0; $i < count($item['models']); $i++)-->
                                            <!--        <li> {{ $item['models'][$i]->series->model->name }} </li>-->
                                            <!--    @endfor-->
                                            <!--@else-->
                                            <!--    <li>لا يوجد</li>-->
                                            <!--@endif-->
                                        </td>
                                        <td>
                                            <span class="cardin d-none">Brand : </span>
                                            <!--@if (isset($item['models']))-->
                                            <!--    @for ($i = 0; $i < count($item['models']); $i++)-->
                                            <!--        <li> {{ $item['models'][$i]->series->model->brand->name }}-->
                                            <!--        </li>-->
                                            <!--    @endfor-->
                                            <!--@else-->
                                            <!--    <li>لا يوجد</li>-->
                                            <!--@endif-->
                                        </td>
                                        <td>
                                            <span class="cardin d-none">Type : </span>
                                            <!--@if (isset($item['models']))-->
                                            <!--    @for ($i = 0; $i < count($item['models']); $i++)-->
                                            <!--        <li> {{ $item['models'][$i]->series->model->brand_type->name }}-->
                                            <!--        </li>-->
                                            <!--    @endfor-->
                                            <!--@else-->
                                            <!--    <li>لا يوجد</li>-->
                                            <!--@endif-->
                                        </td>
                                        <td><span class="cardin d-none">السعر : </span>
                                            @if (isset($item['price']))
                                                @for ($i = 0; $i < count($item['price']); $i++)
                                                    <li> {{ $item['price'][$i]->price }}
                                                    </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">المخازن : </span>
                                            @if (isset($item['stores']))
                                                @for ($i = 0; $i < count($item['stores']); $i++)
                                                    <li> {{ $item['stores'][$i]->name }} /
                                                        {{ $item['stores'][$i]->storepartCount }}
                                                    </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="cardin d-none">النوع : </span>
                                            <li> {{ $item['type_N'] }} </li>
                                        </td>
                                        <td>
                                            <div class="input-group mb-3 addBtn">
                                                <input type="number" class=" itemAmunt" placeholder="Amount" value="1"
                                                    aria-label="Username" aria-describedby="basic-addon1">
                                                <button class="btn " style="background-color:#a2a8d3"
                                                    onclick="addtoInvoice(this,{{isset($item['allparts'][0]) ? $item['allparts'][0]->wheel->wheel_details : 0}},{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }},1,{{ isset($item['price'][0]->price) ? json_encode($item['price']) : 0 }})">
                                                    <i class="fs-22 mdi mdi-cart-variant"></i>
                                                </button>

                                            </div>
                                        </td>

                                    </tr>
                                @elseif ($item['type_id'] == 6)
                                    <!--kit-->
                                    <tr>


                                        <!--@php-->
                                        <!--    $imgName = '';-->
                                        <!--    if (count($item['image']) > 0) {-->
                                        <!--        $imgName = $item['image'][0]->image_name;-->
                                        <!--    } else {-->
                                        <!--        $imgName = 'tractor-solid.png';-->
                                        <!--    }-->
                                        <!--@endphp-->
                                        @if ($item['type_id'] == 6 && count($item['allparts']) > 0)
                                            <!--part-->
                                            <td style="cursor: pointer;"
                                                onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
                                                <!--<img class="d-none" src="assets\kit_images\{{ $imgName }}"-->
                                                <!--    alt="">-->
                                            @else
                                            <td>
                                        @endif


                                        <i class="fs-2 mdi mdi-eye px-2 text-secondary"></i>

                                        </td>

                                        <td>
                                            <h5>FN{{ $item['type_id'] }}{{ $item['p_data']->id }}{{ $item['source'][0]->id }}{{ $item['status'][0]->id }}{{ $item['quality'][0]->id }}
                                            </h5>
                                            <!--<img class=" header-profile-user zoom"-->
                                            <!--    src="assets/part_images/{{ $imgName }}" alt="Emara">-->
                                            <!--<span-->
                                            <!--    class="barcodeTxt  ">{{ $item['type_id'] }}-{{ $item['p_data']->id }}-{{ $item['source'][0]->id }}-{{ $item['status'][0]->id }}-{{ $item['quality'][0]->id }}</span>-->
                                            <svg class="barcode ">

                                            </svg>

                                        </td>
                                        <td>{{ $item['p_data']->name }}
                                            <br><span>{{ $item['source'][0]->name_arabic }}</span>
                                            <span>{{ $item['status'][0]->name }}</span>
                                            <span>{{ $item['quality'][0]->name }}</span>
                                        </td>
                                        <td><span class="cardin d-none">الأرقام : </span>
                                            @if (isset($item['kit']->kit_numbers))
                                                @for ($i = 0; $i < count($item['kit']->kit_numbers); $i++)
                                                    <li onclick="">
                                                        {{ $item['kit']->kit_numbers[$i]->number }}</li>
                                                @endfor
                                            @else
                                                <li>لا يوجد ارقام</li>
                                            @endif
                                        </td>
                                        {{-- <td>{{ $item['source'][0]->name_arabic }}</td>
                                    <td>{{ $item['status'][0]->name }}</td> --}}
                                        <td><span class="cardin d-none">السعر : </span>
                                            {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}
                                        </td>
                                        <td><span class="cardin d-none">الكمية : </span>{{ $item['Tamount'] }}</td>
                                        <td><span class="cardin d-none">المكان : </span>
                                            @if (isset($item['section']) && count($item['section']) > 0)
                                                @for ($i = 0; $i < count($item['section']); $i++)
                                                    <li class="sectionList d-inline">
                                                        {{ $item['section'][$i]->store_structure->name }}</li>
                                                @endfor
                                            @else
                                                <li class="sectionList d-inline">لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">Sub Group : </span>
                                            @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                @for ($i = 0; $i < count($item['groups']); $i++)
                                                    <li>{{ $item['groups'][$i]->sub_group->name }} /
                                                        {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="cardin d-none">Group : </span>
                                            <!--@if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))-->
                                            <!--    @for ($i = 0; $i < count($item['groups']); $i++)-->
                                            <!--        <li> {{ $item['groups'][$i]->sub_group->group->name }} </li>-->
                                            <!--    @endfor-->
                                            <!--@else-->
                                            <!--    <li>لا يوجد</li>-->
                                            <!--@endif-->
                                        </td>
                                        <td><span class="cardin d-none">Series : </span>
                                            @if (isset($item['models']))
                                                @for ($i = 0; $i < count($item['models']); $i++)
                                                    <li>{{ $item['models'][$i]->series->name }} </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">Model : </span>
                                            <!--@if (isset($item['models']))-->
                                            <!--    @for ($i = 0; $i < count($item['models']); $i++)-->
                                            <!--        <li> {{ $item['models'][$i]->series->model->name }} </li>-->
                                            <!--    @endfor-->
                                            <!--@else-->
                                            <!--    <li>لا يوجد</li>-->
                                            <!--@endif-->
                                        </td>
                                        <td><span class="cardin d-none">Brand : </span>
                                            <!--@if (isset($item['models']))-->
                                            <!--    @for ($i = 0; $i < count($item['models']); $i++)-->
                                            <!--        <li> {{ $item['models'][$i]->series->model->brand->name }}-->
                                            <!--        </li>-->
                                            <!--    @endfor-->
                                            <!--@else-->
                                            <!--    <li>لا يوجد</li>-->
                                            <!--@endif-->
                                        </td>
                                        <td><span class="cardin d-none">Type : </span>
                                            <!--@if (isset($item['models']))-->
                                            <!--    @for ($i = 0; $i < count($item['models']); $i++)-->
                                            <!--        <li> {{ $item['models'][$i]->series->model->brand_type->name }}-->
                                            <!--        </li>-->
                                            <!--    @endfor-->
                                            <!--@else-->
                                            <!--    <li>لا يوجد</li>-->
                                            <!--@endif-->
                                        </td>
                                        <td><span class="cardin d-none">السعر : </span>
                                            @if (isset($item['price']))
                                                @for ($i = 0; $i < count($item['price']); $i++)
                                                    <li> {{ $item['price'][$i]->price }}
                                                    </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">المخازن : </span>
                                            @if (isset($item['stores']))
                                                @for ($i = 0; $i < count($item['stores']); $i++)
                                                    <li> {{ $item['stores'][$i]->name }} /
                                                        {{ $item['stores'][$i]->storepartCount }}
                                                    </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="cardin d-none">النوع : </span>
                                            <li> {{ $item['type_N'] }} </li>
                                        </td>
                                        <td>
                                            <div class="input-group mb-3 addBtn">
                                                <input type="number" class=" itemAmunt" placeholder="Amount"
                                                    value="1" aria-label="Username" aria-describedby="basic-addon1">
                                                <button class="btn " style="background-color:#29badb"
                                                    onclick="addtoInvoice(this,{{isset($item['allparts'][0]) ? $item['allparts'][0]->kit->kit_details : 0}},{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }},1,{{ isset($item['price'][0]->price) ? json_encode($item['price']) : 0 }})">
                                                    <i class="fs-22 mdi mdi-cart-variant"></i>
                                                </button>

                                            </div>
                                        </td>

                                    </tr>
                                @elseif ($item['type_id'] == 3)
                                    <!--tractor-->
                                    <tr>

                                        @php
                                            $imgName = '';
                                            if (count($item['image']) > 0) {
                                                $imgName = $item['image'][0]->image_name;
                                            } else {
                                                $imgName = 'tractor-solid.png';
                                            }
                                        @endphp
                                        @if ($item['type_id'] == 3 && count($item['allparts']) > 0)
                                            <!--part-->
                                            <td style="cursor: pointer;"
                                                onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
                                                <img class="d-none" src="assets\tractor_images\{{ $imgName }}"
                                                    alt="">
                                            @else
                                            <td>
                                        @endif


                                        <i class="fs-2 mdi mdi-eye px-2 text-secondary"></i>

                                        </td>
                                        <td>
                                            <h5>FN{{ $item['type_id'] }}{{ $item['p_data']->id }}{{ $item['source'][0]->id }}{{ $item['status'][0]->id }}{{ $item['quality'][0]->id }}
                                            </h5>
                                            <!--<img class=" header-profile-user zoom"-->
                                            <!--    src="assets/tractor_images/{{ $imgName }}" alt="Emara">-->
                                            <span
                                                class="barcodeTxt  ">{{ $item['type_id'] }}-{{ $item['p_data']->id }}-{{ $item['source'][0]->id }}-{{ $item['status'][0]->id }}-{{ $item['quality'][0]->id }}</span>
                                            <svg class="barcode ">

                                            </svg>

                                        </td>
                                        <td>{{ $item['p_data']->name }}
                                            <br><span>{{ $item['source'][0]->name_arabic }}</span>
                                            <span>{{ $item['status'][0]->name }}</span>
                                            <span>{{ $item['quality'][0]->name }}</span>
                                        </td>
                                        <td>
                                            <span class="cardin d-none">الأرقام : </span>
                                            @if (isset($item['p_data']->part_numbers))
                                                @for ($i = 0; $i < count($item['p_data']->part_numbers); $i++)
                                                    <li onclick="">
                                                        {{ $item['p_data']->part_numbers[$i]->number }}</li>
                                                @endfor
                                            @else
                                                <li>لا يوجد ارقام</li>
                                            @endif
                                        </td>
                                        {{-- <td>{{ $item['source'][0]->name_arabic }}</td>
                                          <td>{{ $item['status'][0]->name }}</td> --}}
                                        <td><span class="cardin d-none">السعر :
                                            </span>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}
                                        </td>
                                        <td><span class="cardin d-none">الكمية : </span>{{ $item['Tamount'] }}</td>
                                        <td><span class="cardin d-none">المكان : </span>
                                            @if (isset($item['section']) && isset($item['section'][$i]->store_structure->name))
                                                @for ($i = 0; $i < count($item['section']); $i++)
                                                    <li class="sectionList d-inline">
                                                        {{ $item['section'][$i]->store_structure->name }}</li>
                                                @endfor
                                            @else
                                                <li class="sectionList d-inline">لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">Sub Group : </span>
                                            @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                @for ($i = 0; $i < count($item['groups']); $i++)
                                                    <li>{{ $item['groups'][$i]->sub_group->name }} /
                                                        {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="cardin d-none">Group : </span>
                                            @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                @for ($i = 0; $i < count($item['groups']); $i++)
                                                    <li> {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">Series : </span>
                                            @if (isset($item['models']))
                                                @for ($i = 0; $i < count($item['models']); $i++)
                                                    <li>{{ $item['models'][$i]->series->name }} </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">Model : </span>
                                            @if (isset($item['models']))
                                                @for ($i = 0; $i < count($item['models']); $i++)
                                                    <li> {{ $item['models'][$i]->series->model->name }} </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="cardin d-none">Brand : </span>
                                            @if (isset($item['models']))
                                                @for ($i = 0; $i < count($item['models']); $i++)
                                                    <li> {{ $item['models'][$i]->series->model->brand->name }}
                                                    </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="cardin d-none">Type : </span>
                                            @if (isset($item['models']))
                                                @for ($i = 0; $i < count($item['models']); $i++)
                                                    <li> {{ $item['models'][$i]->series->model->brand_type->name }}
                                                    </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">السعر : </span>
                                            @if (isset($item['price']))
                                                @for ($i = 0; $i < count($item['price']); $i++)
                                                    <li> {{ $item['price'][$i]->price }}
                                                    </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">المخازن : </span>
                                            @if (isset($item['stores']))
                                                @for ($i = 0; $i < count($item['stores']); $i++)
                                                    <li> {{ $item['stores'][$i]->name }} /
                                                        {{ $item['stores'][$i]->storepartCount }}
                                                    </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="cardin d-none">النوع : </span>
                                            <li> {{ $item['type_N'] }} </li>
                                        </td>
                                        <td>
                                            <div class="input-group mb-3 addBtn">
                                                <input type="number" class=" itemAmunt" placeholder="Amount"
                                                    value="1" readonly aria-label="Username"
                                                    aria-describedby="basic-addon1">
                                                <button class="btn btn-success   addBtn"
                                                    style="background-color: #29badb ;color:black"
                                                    onclick="addtoInvoice(this,{{isset($item['allparts'][0]) ? $item['allparts'][0]->tractor->tractor_details : 0 }},{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }},1)">
                                                    <i class="fs-22 mdi mdi-cart-variant"></i>
                                                </button>

                                            </div>


                                        </td>

                                    </tr>
                                @elseif ($item['type_id'] == 4)
                                    <!--tractor-->
                                    <tr>

                                        @php
                                            $imgName = '';
                                            if (count($item['image']) > 0) {
                                                $imgName = $item['image'][0]->image_name;
                                            } else {
                                                $imgName = 'tractor-solid.png';
                                            }
                                        @endphp
                                        @if ($item['type_id'] == 4 && count($item['allparts']) > 0)
                                            <td style="cursor: pointer;"
                                                onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
                                                <img class="d-none" src="assets\clark_images\{{ $imgName }}"
                                                    alt="">
                                            @else
                                            <td>
                                        @endif


                                        <i class="fs-2 mdi mdi-eye px-2 text-secondary"></i>

                                        </td>
                                        <td>
                                            <h5>FN{{ $item['type_id'] }}{{ $item['p_data']->id }}{{ $item['source'][0]->id }}{{ $item['status'][0]->id }}{{ $item['quality'][0]->id }}
                                            </h5>
                                            <img class=" header-profile-user zoom"
                                                src="assets/tractor_images/{{ $imgName }}" alt="Emara">
                                            <span
                                                class="barcodeTxt  ">{{ $item['type_id'] }}-{{ $item['p_data']->id }}-{{ $item['source'][0]->id }}-{{ $item['status'][0]->id }}-{{ $item['quality'][0]->id }}</span>
                                            <svg class="barcode ">

                                            </svg>

                                        </td>
                                        <td>{{ $item['p_data']->name }}
                                            <br><span>{{ $item['source'][0]->name_arabic }}</span>
                                            <span>{{ $item['status'][0]->name }}</span>
                                            <span>{{ $item['quality'][0]->name }}</span>
                                        </td>
                                        <td><span class="cardin d-none">الأرقام : </span>
                                            @if (isset($item['p_data']->part_numbers))
                                                @for ($i = 0; $i < count($item['p_data']->part_numbers); $i++)
                                                    <li onclick="">
                                                        {{ $item['p_data']->part_numbers[$i]->number }}</li>
                                                @endfor
                                            @else
                                                <li>لا يوجد ارقام</li>
                                            @endif
                                        </td>
                                        {{-- <td>{{ $item['source'][0]->name_arabic }}</td>
                                    <td>{{ $item['status'][0]->name }}</td> --}}
                                        <td><span class="cardin d-none">السعر :
                                            </span>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}
                                        </td>
                                        <td><span class="cardin d-none">الكمية : </span>{{ $item['Tamount'] }}</td>
                                        <td><span class="cardin d-none">المكان : </span>
                                            @if (isset($item['section']) && isset($item['section'][$i]->store_structure->name))
                                                @for ($i = 0; $i < count($item['section']); $i++)
                                                    <li class="sectionList d-inline">
                                                        {{ $item['section'][$i]->store_structure->name }}</li>
                                                @endfor
                                            @else
                                                <li class="sectionList d-inline">لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">Sub Group : </span>
                                            @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                @for ($i = 0; $i < count($item['groups']); $i++)
                                                    <li>{{ $item['groups'][$i]->sub_group->name }} /
                                                        {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">Group : </span>
                                            @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                @for ($i = 0; $i < count($item['groups']); $i++)
                                                    <li> {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">Series : </span>
                                            @if (isset($item['models']))
                                                @for ($i = 0; $i < count($item['models']); $i++)
                                                    <li>{{ $item['models'][$i]->series->name }} </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">Model : </span>
                                            @if (isset($item['models']))
                                                @for ($i = 0; $i < count($item['models']); $i++)
                                                    <li> {{ $item['models'][$i]->series->model->name }} </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">Brand : </span>
                                            @if (isset($item['models']))
                                                @for ($i = 0; $i < count($item['models']); $i++)
                                                    <li> {{ $item['models'][$i]->series->model->brand->name }}
                                                    </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">Type : </span>
                                            @if (isset($item['models']))
                                                @for ($i = 0; $i < count($item['models']); $i++)
                                                    <li> {{ $item['models'][$i]->series->model->brand_type->name }}
                                                    </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">السعر : </span>
                                            @if (isset($item['price']))
                                                @for ($i = 0; $i < count($item['price']); $i++)
                                                    <li> {{ $item['price'][$i]->price }}
                                                    </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">المخازن : </span>
                                            @if (isset($item['stores']))
                                                @for ($i = 0; $i < count($item['stores']); $i++)
                                                    <li> {{ $item['stores'][$i]->name }} /
                                                        {{ $item['stores'][$i]->storepartCount }}
                                                    </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="cardin d-none">النوع : </span>
                                            <li> {{ $item['type_N'] }} </li>
                                        </td>
                                        <td>
                                            <div class="input-group mb-3 addBtn">
                                                <input type="number" class=" itemAmunt" placeholder="Amount"
                                                    value="1" readonly aria-label="Username"
                                                    aria-describedby="basic-addon1">
                                                <button class="btn btn-success  addBtn"
                                                    style="background: #29badb;color:black"
                                                    onclick="addtoInvoice(this,{{ isset($item['allparts'][0]) ? $item['allparts'][0]->clark->clark_details : 0}},{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }},1)">
                                                    <i class="fs-22 mdi mdi-cart-variant"></i>
                                                </button>
                                            </div>
                                        </td>

                                    </tr>
                                @elseif ($item['type_id'] == 5)
                                    <!--tractor-->
                                    <tr>

                                        @php
                                            $imgName = '';
                                            if (count($item['image']) > 0) {
                                                $imgName = $item['image'][0]->image_name;
                                            } else {
                                                $imgName = 'tractor-solid.png';
                                            }
                                        @endphp
                                        @if ($item['type_id'] == 5 && count($item['allparts']) > 0)
                                            <!--part-->
                                            <td style="cursor: pointer;"
                                                onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
                                                <img class="d-none" src="assets\equip_images\{{ $imgName }}"
                                                    alt="">
                                            @else
                                            <td>
                                        @endif


                                        <i class="fs-2 mdi mdi-eye px-2 text-secondary"></i>

                                        </td>
                                        <td>
                                            <h5>FN{{ $item['type_id'] }}{{ $item['p_data']->id }}{{ $item['source'][0]->id }}{{ $item['status'][0]->id }}{{ $item['quality'][0]->id }}
                                            </h5>
                                            <!--<img class=" header-profile-user zoom"-->
                                            <!--    src="assets/tractor_images/{{ $imgName }}" alt="Emara">-->
                                            <span
                                                class="barcodeTxt  ">{{ $item['type_id'] }}-{{ $item['p_data']->id }}-{{ $item['source'][0]->id }}-{{ $item['status'][0]->id }}-{{ $item['quality'][0]->id }}</span>
                                            <svg class="barcode ">

                                            </svg>

                                        </td>
                                        <td>{{ $item['p_data']->name }}
                                            <br><span>{{ $item['source'][0]->name_arabic }}</span>
                                            <span>{{ $item['status'][0]->name }}</span>
                                            <span>{{ $item['quality'][0]->name }}</span>
                                        </td>
                                        <td><span class="cardin d-none">الأرقام : </span>
                                            @if (isset($item['p_data']->part_numbers))
                                                @for ($i = 0; $i < count($item['p_data']->part_numbers); $i++)
                                                    <li onclick="">
                                                        {{ $item['p_data']->part_numbers[$i]->number }}</li>
                                                @endfor
                                            @else
                                                <li>لا يوجد ارقام</li>
                                            @endif
                                        </td>
                                        {{-- <td>{{ $item['source'][0]->name_arabic }}</td>
                                    <td>{{ $item['status'][0]->name }}</td> --}}
                                        <td><span class="cardin d-none">السعر :
                                            </span>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}
                                        </td>
                                        <td><span class="cardin d-none">الكمية : </span>{{ $item['Tamount'] }}</td>
                                        <td><span class="cardin d-none">المكان : </span>
                                            @if (isset($item['section']) && isset($item['section'][$i]->store_structure->name))
                                                @for ($i = 0; $i < count($item['section']); $i++)
                                                    <li class="sectionList d-inline">
                                                        {{ $item['section'][$i]->store_structure->name }}</li>
                                                @endfor
                                            @else
                                                <li class="sectionList d-inline">لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">Sub Group : </span>
                                            @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                @for ($i = 0; $i < count($item['groups']); $i++)
                                                    <li>{{ $item['groups'][$i]->sub_group->name }} /
                                                        {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">Group : </span>
                                            @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                @for ($i = 0; $i < count($item['groups']); $i++)
                                                    <li> {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">Series : </span>
                                            @if (isset($item['models']))
                                                @for ($i = 0; $i < count($item['models']); $i++)
                                                    <li>{{ $item['models'][$i]->series->name }} </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">Model : </span>
                                            @if (isset($item['models']))
                                                @for ($i = 0; $i < count($item['models']); $i++)
                                                    <li> {{ $item['models'][$i]->series->model->name }} </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">Brand : </span>
                                            @if (isset($item['models']))
                                                @for ($i = 0; $i < count($item['models']); $i++)
                                                    <li> {{ $item['models'][$i]->series->model->brand->name }}
                                                    </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">Type : </span>
                                            @if (isset($item['models']))
                                                @for ($i = 0; $i < count($item['models']); $i++)
                                                    <li> {{ $item['models'][$i]->series->model->brand_type->name }}
                                                    </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">السعر : </span>
                                            @if (isset($item['price']))
                                                @for ($i = 0; $i < count($item['price']); $i++)
                                                    <li> {{ $item['price'][$i]->price }}
                                                    </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td><span class="cardin d-none">المخازن : </span>
                                            @if (isset($item['stores']))
                                                @for ($i = 0; $i < count($item['stores']); $i++)
                                                    <li> {{ $item['stores'][$i]->name }} /
                                                        {{ $item['stores'][$i]->storepartCount }}
                                                    </li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="cardin d-none">النوع : </span>
                                            <li> {{ $item['type_N'] }} </li>
                                        </td>
                                        <td>
                                            <div class="input-group mb-3 addBtn">
                                                <input type="number" class=" itemAmunt" placeholder="Amount"
                                                    value="1" readonly aria-label="Username"
                                                    aria-describedby="basic-addon1">
                                                <button class="btn btn-success  addBtn"
                                                    style="background: #29badb;color:black"
                                                    onclick="addtoInvoice(this,{{isset($item['allparts'][0]) ? $item['allparts'][0]->equip->equip_details : 0}},{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }},1)">
                                                    <i class="fs-22 mdi mdi-cart-variant"></i>
                                                </button>
                                            </div>
                                        </td>

                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
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
                            <!--slow down-->
                            <!--@foreach ($allSections as $section)-->
                            <!--    <div class="col-lg-3 col-sm-12 resSec">-->
                            <!--        <h3 class="text-bg-dark text-center">{{ $section->name }}</h3>-->
                            <!--        <table class="table">-->
                            <!--            @foreach ($section->store_sections as $item)-->
                            <!--                @if ($item->type_id == 1)-->
                            <!--                    <tr>-->
                            <!--                        <td>{{ $item->part->name }}</td>-->
                            <!--                        <td>{{ $item->amount }}</td>-->
                            <!--                    </tr>-->
                            <!--                @elseif($item->type_id == 2)-->
                            <!--                    <tr>-->
                            <!--                        <td>{{ $item->wheel->name }}</td>-->
                            <!--                        <td>{{ $item->amount }}</td>-->
                            <!--                    </tr>-->
                            <!--                @elseif($item->type_id == 3)-->
                            <!--                    <tr>-->
                            <!--                        <td>{{ $item->tractor->name }}</td>-->
                            <!--                        <td>{{ $item->amount }}</td>-->
                            <!--                    </tr>-->
                            <!--                @elseif($item->type_id == 4)-->
                            <!--                    <tr>-->
                            <!--                        <td>{{ $item->clark->name }}</td>-->
                            <!--                        <td>{{ $item->amount }}</td>-->
                            <!--                    </tr>-->
                            <!--                @elseif($item->type_id == 5)-->
                            <!--                    <tr>-->
                            <!--                        <td>{{ $item->equip->name }}</td>-->
                            <!--                        <td>{{ $item->amount }}</td>-->
                            <!--                    </tr>-->
                            <!--                @elseif($item->type_id == 6)-->
                            <!--                    <tr>-->
                            <!--                        <td>{{ $item->kit->name }}</td>-->
                            <!--                        <td>{{ $item->amount }}</td>-->
                            <!--                    </tr>-->
                            <!--                @endif-->
                            <!--            @endforeach-->
                            <!--        </table>-->
                            <!--    </div>-->
                            <!--@endforeach-->
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
                        <div class="col-2 text-end">
                            <label>العمــــــــــيل</label>
                        </div>
                        <div class="col-4 ">

                            <select name="client" id="clientSlct" class="form-control" required>
                                <option selected disabled value="">إختر العميــــــل</option>
                                @foreach ($clients as $client)
                                    <option
                                        data-mad="{{ round($client->invoices->sum('actual_price') - $client->invoices->sum('paied') - $client->invoices->sum('discount') - $client->invoice_client_madyoneas->sum('paied') + $client->servicesMad) - $client->as_sup_madunia - $client->client_raseed }}"
                                        value="{{ $client->id }}">{{ $client->name }} /
                                        {{ $client->tel01 }}</option>
                                @endforeach

                                {{-- {{ $client->invoices->sum('actual_price') - $client->invoices->sum('paied') - $client->invoices->sum('discount') - $client->invoice_client_madyoneas->sum('paied') }} --}}
                            </select>
                            <span>حساب العميل : </span>
                            <span id="madClientTxt">00</span><br>
                            <span id="">( + ) مديونية</span>
                            <span id="">( - ) رصيد</span>
                        </div>
                        <div class="col-2 ">
                            <label>نـــــــــوع البيع</label>
                        </div>

                        <div class="col-4 ">

                            <select name="saleTypeSlct" id="saleTypeSlct" class="form-control">
                                <option selected disabled value="NEW">إختر </option>
                                @foreach ($allprices as $price)
                                    @if ($price->id == 5)
                                        <option value="{{ $price->id }}" selected>{{ $price->type }} </option>
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
                                        <td>weight</td>
                                        <td>Qty</td>
                                        <td>Price</td>
                                        <td>Total</td>
                                        <td>Remove</td>
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
                                                    <input name="taxes[]" type="checkbox" value="{{ $tax->value }}">
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
                                    <select class="form-select mt-1" name="payment" id="paymentslect">
                                        {{-- <option value="0">كاش</option>
                                    <option value="1">تحويل بنكي</option>
                                    <option value="2"> علي الحساب</option> --}}

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
                                            <a href="#" class="btn btn-primary mt-2 w-100" id="addtocardMdl">Add to Cart</a>

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
                                    <button style="text-wrap: nowrap;" class="nav-link " id="nav-home-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-home" type="button" role="tab"
                                        aria-controls="nav-home" aria-selected="true">ITEM APPLICATIONS</button>
                                    <button style="text-wrap: nowrap;" class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-profile" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">FLIP CODES</button>
                                    <button style="text-wrap: nowrap;" class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-contact" type="button" role="tab"
                                        aria-controls="nav-contact" aria-selected="false">Specifications</button>
                                    <button style="text-wrap: nowrap;" class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-4" type="button" role="tab" aria-controls="nav-4"
                                        aria-selected="false">Stores</button>
                                    <button style="text-wrap: nowrap;" class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-5" type="button" role="tab" aria-controls="nav-5"
                                        aria-selected="false">Price</button>
                                    <button style="text-wrap: nowrap;" class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-6" type="button" role="tab" aria-controls="nav-6"
                                        aria-selected="false">suggistion parts</button>
                                    <button style="text-wrap: nowrap;" class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-7" type="button" role="tab" aria-controls="nav-7"
                                        aria-selected="false">related parts</button>
                                    <button style="text-wrap: nowrap;" class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-15" type="button" role="tab" aria-controls="nav-15"
                                        aria-selected="false">^</button>

                                    </div>
                                    <div class="input-box">
                                        <input type="search" name="searchtables" id="searchtables" class="form-control m-2 w-10">
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
                                                    <button class="nav-link" id="nav-series-tab" data-bs-toggle="tab" --}}
                                                        data-bs-target="#nav-series" type="button" role="tab" aria-controls="nav-series"
                                                        aria-selected="false">Series <i class="bx bx-menu-alt-left"></i></button>

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
                                                <div class="nav nav-pills nav-justified" id="nav-tabssnum" role="tablist">

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
                                            <table
                                            class="table table-striped table-bordered cell-border " style="width:100%">
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

                                            <table
                                            class="table table-striped table-bordered cell-border " style="width:100%">
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

                                            <table class="table table-striped table-bordered cell-border " style="width:100%">
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

                                            <table class="table table-striped table-bordered cell-border " style="width:100%">
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



@endsection







@section('js')
    <script>
        var store_data = {!! $store_data !!};
        var allStores = {!! $allStores !!};
        var store_inbox = {!! $store_inbox !!};
    </script>
    <script src={{ URL::asset('js/pos.js') }}></script>

    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endsection
