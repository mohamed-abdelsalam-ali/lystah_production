<!doctype html>
<html lang="en" data-layout="twocolumn" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none"
    data-preloader="disable" dir="rtl">


<head>
    <meta charset="utf-8" />
    <title>
        POS
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Emara Parts" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico') }}">

    <link href="{{ URL::asset('assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" />

    <!-- Layout config Js -->
    <script src="{{ URL::asset('assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ URL::asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ URL::asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ URL::asset('assets/css/app-rtl.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ URL::asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">-->
    <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">-->
    <!--<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css">-->
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">-->

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.css"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.2.0/css/searchPanes.dataTables.min.css">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">



    <link rel="stylesheet" href="/assets/libs/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.bulma.css">
    {{-- <link rel="stylesheet" href="{{ URL::asset('assets/css/simpleNav.css') }}"> --}}

    <style>
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

</head>

<body class="cairo ">

    <div id="layout-wrapper">
        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">




                        <!-- App Search-->
                        <form class="app-search d-none d-md-block dateTimeClock d-none">
                            <div class="position-relative">

                                <h3>مجموعة شركات عمارة </h3>
                                <h3 class="text-center"style="color:#38598b">{{ $store_data[0]->name }}</h3>
                            </div>

                        </form>
                    </div>

                    <div class="d-flex align-items-center">



                        <div class="dropdown ms-1 topbar-head-dropdown header-item">
                            <button title="الخزينة والموظفيين" type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img id="header-lang-img" src="assets/images/comingsoon.png" alt="Header Language"
                                    height="20" class="rounded">
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">

                                <!-- item-->
                                <a href="{{ route('get_safe_store', $store_data[0]->id) }}"
                                    class="dropdown-item notify-item language py-2" data-lang="en" title="English">
                                    <i class="bx bx-money-withdraw fs-22"></i>
                                    <span class="align-middle">الخزينة</span>
                                </a>

                                <a href="/get_store_employee_salary/{{ $store_data[0]->id }}"
                                    class="dropdown-item notify-item language" data-lang="ar" title="Arabic">
                                    <i class="bx bx-user-check fs-22"></i>
                                    <span class="align-middle">مرتبات الموظفيين</span>
                                </a>
                            </div>
                        </div>

                        <div class="dropdown topbar-head-dropdown ms-1 header-item">
                            <button title=" أقسام المخزن" type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                data-bs-toggle="modal" data-bs-target="#sectionMdl">
                                <i class="bx bx-category-alt fs-22"></i>
                            </button>

                        </div>

                        <div class="dropdown topbar-head-dropdown ms-1 header-item">
                            <button title="الوارد" type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                data-bs-toggle="modal" data-bs-target="#inboxMdl">
                                <i class="bx bx-archive-in fs-22"></i>
                                <span
                                    class="position-absolute topbar-badge cartitem-badge fs-10 translate-middle badge rounded-pill bg-info"
                                    id="inboxCounterLbl">0</span>
                            </button>

                        </div>

                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button type="button" title="العملاء"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                data-bs-toggle="modal" data-bs-target="#ClientMdl">
                                <i class="bx bx-user fs-22"></i>
                            </button>
                        </div>

                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button title="ارسال بضاعة لمخزن أخر" type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode"
                                data-bs-toggle="modal" data-bs-target="#storeMdl">
                                <i class="bx bx-send fs-22"></i>
                            </button>
                        </div>

                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button title="الاقسام" type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                onclick="location.href='storeSections/{{ $store_data[0]->id }}'">
                                <i class="bx bx-store fs-22"></i>
                            </button>
                        </div>
                        <div class="dropdown topbar-head-dropdown ms-1 header-item kt_topbar_notifications_3"
                            id="notificationDropdown">
                            <button title="الخدمات" type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                onclick="location.href='services/{{ $store_data[0]->id }}'">
                                <i class="bx bx-selection fs-22"></i>
                                {{-- <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger inbox_trans_counter">0<span class="visually-hidden">unread messages</span></span> --}}
                            </button>

                        </div>

                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button type="button" class="btn" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">

                                    <img class="rounded-circle header-profile-user"
                                        src="http://localhost:8000/users_images/20240120072953-admin-111.jpg"
                                        alt="Header Avatar">

                                    <span class="text-start ms-xl-2">
                                        <span
                                            class="d-none d-xl-inline-block ms-1 fw-semibold user-name-text">admin</span>

                                        <span
                                            class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">admin</span>
                                    </span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <h6 class="dropdown-header">Welcome .....!</h6>
                                <a class="dropdown-item" href="http://localhost:8000/users/33/edit"><i
                                        class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                        class="align-middle">Profile</span></a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="http://localhost:8000/logout" id="btn-logout"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
                                        class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span
                                        class="align-middle" data-key="t-logout">Logout</span></a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    <input type="hidden" name="_token"
                                        value="G2NeJXWEgNAEqRaO5TNN5LmHlrplSqse1XlQsP6v" autocomplete="off">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
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
                <button type="button" class="btn fixedbtn text-white" style="background-color:#38598b"
                    data-bs-toggle="modal" data-bs-target="#example11Modal">

                    <i class="mdi fs-22 mdi-cart-variant">
                        <span class=" topbar-badge cartitem-badge fs-11 translate-middle badge rounded-pill bg-info"
                            style="position:absolute !important;top:0px !important" id="basketCounterLbl">0</span>
                    </i>
                </button>

                <button type="button" class="btn fixedbtn1 text-white" style="background-color:#38598b"
                    data-bs-toggle="modal" data-bs-target="#example11Modal1">

                    <i class="mdi fs-22 mdi-filter-outline"></i>
                </button>
                <input id="input-to-fill" class="opacity-0" type="text" contenteditable="true"
                    placeholder="Barcode Result">
                <div class="row bg-body">

                    <div class="border  col-lg-12  table-responsive bg-white">
                        <div class="overflow-hidden h-100 tableOverlay text-center w-100 ">
                            <div class="loader" style="margin: 100px auto;"></div>
                        </div>
                        <table id="example" class="table table-striped table-bordered cell-border "
                            style="width:100%">
                            <thead style="background:#5fcee78a">
                                <tr>
                                    <th>View</th>
                                    <th>Image </th>
                                    <th>Name </th>
                                    <th>Numbers </th>
                                    {{-- <th>Source </th>
                                    <th>Status </th>
                                    <th>Quality </th> --}}
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
                                                if (count($item['image']) > 0) {
                                                    $imgName = $item['image'][0]->image_name;
                                                }
                                            @endphp
                                            @if ($item['type_id'] == 1 && count($item['allparts']) > 0)
                                                <!--part-->
                                                <td style="cursor: pointer;"
                                                    onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
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
                                                <span
                                                    class="barcodeTxt  ">{{ $item['type_id'] }}-{{ $item['p_data']->id }}-{{ $item['source'][0]->id }}-{{ $item['status'][0]->id }}-{{ $item['quality'][0]->id }}</span>
                                                <svg class="barcode ">

                                                </svg>

                                            </td>
                                            @if ($item['type_id'] == 1 && count($item['allparts']) > 0)
                                                <!--part-->
                                                <td
                                                    onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
                                                @else
                                                <td>
                                            @endif

                                            {{ $item['p_data']->name }}
                                            <br><span>{{ $item['source'][0]->name_arabic }}</span>
                                            <span>{{ $item['status'][0]->name }}</span>
                                            <span>{{ $item['quality'][0]->name }}</span>
                                            </td>
                                            <td>
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
                                            <td>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}
                                            </td>
                                            <td>{{ $item['Tamount'] }}</td>
                                            <td>
                                                @if (isset($item['section']))
                                                    @for ($i = 0; $i < count($item['section']); $i++)
                                                        <li class="sectionList">
                                                            {{ $item['section'][$i]->store_structure->name }}</li>
                                                    @endfor
                                                @else
                                                    <li class="sectionList">لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                    @for ($i = 0; $i < count($item['groups']); $i++)
                                                        <li> {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li>{{ $item['models'][$i]->series->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->brand_type->name }}
                                                        </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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

                                                <li> {{ $item['type_N'] }} </li>
                                            </td>
                                            <td>
                                                <div class="input-group mb-3 addBtn">
                                                    <input type="number" class="form-control itemAmunt"
                                                        placeholder="Amount" value="1" aria-label="Username"
                                                        aria-describedby="basic-addon1">
                                                    <button class="btn " style="background-color:#a2a8d3"
                                                        onclick="addtoInvoice(this,{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }},1,{{ isset($item['price'][0]->price) ? json_encode($item['price']) : 0 }})">
                                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                                    </button>

                                                </div>

                                            </td>

                                        </tr>
                                    @elseif ($item['type_id'] == 2)
                                        <!--wheel-->
                                        <tr>

                                            @php
                                                $imgName = '';
                                                if (count($item['image']) > 0) {
                                                    $imgName = $item['image'][0]->image_name;
                                                }
                                            @endphp
                                            @if ($item['type_id'] == 2 && count($item['allparts']) > 0)
                                                <!--part-->
                                                <td style="cursor: pointer;"
                                                    onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
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
                                                <span
                                                    class="barcodeTxt  ">{{ $item['type_id'] }}-{{ $item['p_data']->id }}-{{ $item['source'][0]->id }}-{{ $item['status'][0]->id }}-{{ $item['quality'][0]->id }}</span>
                                                <svg class="barcode ">

                                                </svg>

                                            </td>
                                            <td>{{ $item['p_data']['name'] }}
                                                <br><span>{{ $item['source'][0]->name_arabic }}</span>
                                                <span>{{ $item['status'][0]->name }}</span>
                                                <span>{{ $item['quality'][0]->name }}</span>
                                            </td>
                                            <td>
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
                                            <td>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}
                                            </td>
                                            <td>{{ $item['Tamount'] }}</td>
                                            <td>
                                                @if (isset($item['section']))
                                                    @for ($i = 0; $i < count($item['section']); $i++)
                                                        <li class="sectionList">
                                                            {{ $item['section'][$i]->store_structure->name }}</li>
                                                    @endfor
                                                @else
                                                    <li class="sectionList">لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                    @for ($i = 0; $i < count($item['groups']); $i++)
                                                        <li> {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li>{{ $item['models'][$i]->series->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->brand_type->name }}
                                                        </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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

                                                <li> {{ $item['type_N'] }} </li>
                                            </td>
                                            <td>
                                                <div class="input-group mb-3 addBtn">
                                                    <input type="number" class="form-control itemAmunt"
                                                        placeholder="Amount" value="1" aria-label="Username"
                                                        aria-describedby="basic-addon1">
                                                    <button class="btn " style="background-color:#a2a8d3"
                                                        onclick="addtoInvoice(this,{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }},1,{{ isset($item['price'][0]->price) ? json_encode($item['price']) : 0 }})">
                                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                                    </button>

                                                </div>
                                            </td>

                                        </tr>
                                    @elseif ($item['type_id'] == 6)
                                        <!--kit-->
                                        <tr>


                                            @php
                                                $imgName = '';
                                                if (count($item['image']) > 0) {
                                                    $imgName = $item['image'][0]->image_name;
                                                }
                                            @endphp
                                            @if ($item['type_id'] == 6 && count($item['allparts']) > 0)
                                                <!--part-->
                                                <td style="cursor: pointer;"
                                                    onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
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
                                            <td>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}
                                            </td>
                                            <td>{{ $item['Tamount'] }}</td>
                                            <td>
                                                @if (isset($item['section']))
                                                    @for ($i = 0; $i < count($item['section']); $i++)
                                                        <li class="sectionList">
                                                            {{ $item['section'][$i]->store_structure->name }}</li>
                                                    @endfor
                                                @else
                                                    <li class="sectionList">لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                    @for ($i = 0; $i < count($item['groups']); $i++)
                                                        <li> {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li>{{ $item['models'][$i]->series->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->brand_type->name }}
                                                        </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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

                                                <li> {{ $item['type_N'] }} </li>
                                            </td>
                                            <td>
                                                <div class="input-group mb-3 addBtn">
                                                    <input type="number" class="form-control itemAmunt"
                                                        placeholder="Amount" value="1" aria-label="Username"
                                                        aria-describedby="basic-addon1">
                                                    <button class="btn " style="background-color:#a2a8d3"
                                                        onclick="addtoInvoice(this,{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }},1,{{ isset($item['price'][0]->price) ? json_encode($item['price']) : 0 }})">
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
                                                }
                                            @endphp
                                            @if ($item['type_id'] == 3 && count($item['allparts']) > 0)
                                                <!--part-->
                                                <td style="cursor: pointer;"
                                                    onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
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
                                            <td>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}
                                            </td>
                                            <td>{{ $item['Tamount'] }}</td>
                                            <td>
                                                @if (isset($item['section']))
                                                    @for ($i = 0; $i < count($item['section']); $i++)
                                                        <li class="sectionList">
                                                            {{ $item['section'][$i]->store_structure->name }}</li>
                                                    @endfor
                                                @else
                                                    <li class="sectionList">لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                    @for ($i = 0; $i < count($item['groups']); $i++)
                                                        <li> {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li>{{ $item['models'][$i]->series->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->brand_type->name }}
                                                        </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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

                                                <li> {{ $item['type_N'] }} </li>
                                            </td>
                                            <td> <button class="btn btn-success text-bg-dark w-100 addBtn"
                                                    onclick="addtoInvoice(this,{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }},1)">
                                                    <i class="fs-22 mdi mdi-cart-variant"></i>
                                                </button>
                                            </td>

                                        </tr>
                                    @elseif ($item['type_id'] == 4)
                                        <!--tractor-->
                                        <tr>

                                            @php
                                                $imgName = '';
                                                if (count($item['image']) > 0) {
                                                    $imgName = $item['image'][0]->image_name;
                                                }
                                            @endphp
                                            @if ($item['type_id'] == 4 && count($item['allparts']) > 0)
                                                part
                                                <td style="cursor: pointer;"
                                                    onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
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
                                            <td>
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
                                            <td>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}
                                            </td>
                                            <td>{{ $item['Tamount'] }}</td>
                                            <td>
                                                @if (isset($item['section']))
                                                    @for ($i = 0; $i < count($item['section']); $i++)
                                                        <li class="sectionList">
                                                            {{ $item['section'][$i]->store_structure->name }}</li>
                                                    @endfor
                                                @else
                                                    <li class="sectionList">لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                    @for ($i = 0; $i < count($item['groups']); $i++)
                                                        <li> {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li>{{ $item['models'][$i]->series->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->brand_type->name }}
                                                        </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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

                                                <li> {{ $item['type_N'] }} </li>
                                            </td>
                                            <td> <button class="btn btn-success text-bg-dark w-100 addBtn"
                                                    onclick="addtoInvoice(this,{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }},1)">
                                                    <i class="fs-22 mdi mdi-cart-variant"></i>
                                                </button>
                                            </td>

                                        </tr>
                                    @elseif ($item['type_id'] == 5)
                                        <!--tractor-->
                                        <tr>

                                            @php
                                                $imgName = '';
                                                if (count($item['image']) > 0) {
                                                    $imgName = $item['image'][0]->image_name;
                                                }
                                            @endphp
                                            @if ($item['type_id'] == 5 && count($item['allparts']) > 0)
                                                <!--part-->
                                                <td style="cursor: pointer;"
                                                    onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
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
                                            <td>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}
                                            </td>
                                            <td>{{ $item['Tamount'] }}</td>
                                            <td>
                                                @if (isset($item['section']))
                                                    @for ($i = 0; $i < count($item['section']); $i++)
                                                        <li class="sectionList">
                                                            {{ $item['section'][$i]->store_structure->name }}</li>
                                                    @endfor
                                                @else
                                                    <li class="sectionList">لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                    @for ($i = 0; $i < count($item['groups']); $i++)
                                                        <li> {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li>{{ $item['models'][$i]->series->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->brand_type->name }}
                                                        </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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

                                                <li> {{ $item['type_N'] }} </li>
                                            </td>
                                            <td> <button class="btn btn-success text-bg-dark w-100 addBtn"
                                                    onclick="addtoInvoice(this,{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }},1)">
                                                    <i class="fs-22 mdi mdi-cart-variant"></i>
                                                </button>
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
    </div>

    <!-- Begin page -->
    <div class="d-none" id="layout-wrapper">
        <div class="main-content m-0">
            <div class="page-content p-0">
                <button type="button" class="border rounded-circle text-bg-success"
                    onclick="$('#posHeader').toggle();"><i class="ri-arrow-up-fill"></i></button>
                <div id="posHeader" class="bg-white p-2 row ">
                    <div class="col-lg-4 col-sm-12 text-center">
                        <table class="text-center w-100">
                            <tr>
                                <td class="text-center">
                                    <h2 style="text-shadow: 1px 1px 2px #000;">مجموعة شركــــات عمــارة
                                    </h2>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h6> ك 87 طريق الإسكندرية الزراعي القاهرة مصر <i
                                            class="bx bx-location-plus fs-22"></i> </h6>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h6><i class="ri-phone-fill"> </i>01019162004</h6>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h6><i class="ri-mail-line"> </i>sales@agriemara.com</h6>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <p class="m-1">EMARA STORE</p>
                        <h3 class="text-center"style="color:#38598b">{{ $store_data[0]->name }}</h3>
                        <h4 class=" text-center" style="color:#a2a8d3"><i class="ri-phone-fill"> </i>
                            {{ $store_data[0]->tel01 }}
                        </h4>
                    </div>
                    <div class="col-lg-4 col-sm-12 text-center my-auto"
                        style="background: linear-gradient(45deg, transparent,#e7eaf6);">
                        <a class="dropdown-item" href="{{ route('logout') }}" id="btn-logout"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
                                class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle" data-key="t-logout">Logout</span></a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>







                    </div>

                </div>
                <div class="row my-2">
                    <div class="col table-responsive">
                        <table class="w-100 quickTbl ">
                            <tr class="row">
                                <td class="col-lg-1 col-sm-12 p-0">
                                    <button class="btn p w-100" style="background-color:#e7eaf6"
                                        data-bs-toggle="modal" data-bs-target="#inboxMdl">الــــوارد
                                        <span
                                            class=" topbar-badge cartitem-badge fs-11 translate-middle badge rounded-pill bg-info"
                                            id="inboxCounterLbl">0</span>
                                    </button>
                                </td>
                                <td class="col-lg-2 col-sm-12 p-0">
                                    <button class="btn p  w-100" style="background-color:#a2a8d3"
                                        data-bs-toggle="modal" data-bs-target="#sectionMdl"> تقسيم
                                        المخزن</button>
                                </td>

                                <td class="col-lg-2 col-sm-12 p-0">
                                    <button class="btn p  w-100 text-white" style="background-color:#38598b"
                                        data-bs-toggle="modal" data-bs-target="#storeMdl">بضاعة بالمخزن
                                        {{-- <span
                                        class="position-absolute topbar-badge cartitem-badge fs-11 translate-middle badge rounded-pill bg-info"
                                        id="CounterLbl">0</span> --}}
                                    </button>

                                </td>

                                <td class="col-lg-2 col-sm-12 p-0">
                                    <button class="btn p  w-100 text-white" style="background-color:#38598b"
                                        id="gardBtn">جـــــــرد</button>
                                </td>

                                <td class="col-lg-2 col-sm-12 p-0">
                                    <button class="btn p  w-100 text-white" style="background-color:#38598b"
                                        data-bs-toggle="modal" data-bs-target="#ClientMdl"
                                        id="">العمــــلاء</button>
                                </td>
                                <td class="col-lg-2 col-sm-12  p-0">
                                    <button class="btn p  w-100 " style="background-color:#a2a8d3"
                                        onclick="location.href='storeSections/{{ $store_data[0]->id }}'"
                                        id="">الاقســـام</button>
                                </td>
                                <td class="col-lg-1 col-sm-12 p-0">
                                    <button class="btn p  w-100 text-dark" style="background-color:#e7eaf6"
                                        onclick="toggleFullScreen()"><i class="bx bx-fullscreen "></i></button>
                                </td>
                                <!--<td class="col-lg-2 col-sm-12">-->

                                <!--</td>-->
                            </tr>
                            <tr class="row mt-2 ">



                                <td class="col-lg-4 col-sm-12 p-0 px-1" colspan="3">
                                    <a href="{{ route('get_safe_store', $store_data[0]->id) }}"
                                        style="background-color:#38598b" class="btn p  w-100 text-white">الخزينة</a>


                                </td>

                                <td class="col-lg-4 col-sm-12 p-0 px-1">
                                    <a href="/get_store_employee_salary/{{ $store_data[0]->id }}"
                                        style="background-color:#38598b" class="btn p  w-100 text-white">مرتبات
                                        الموظفين</a>
                                </td>
                                <td class="col-lg-4 col-sm-12 p-0 px-1">
                                    <a href="/services/{{ $store_data[0]->id }}" style="background-color:#38598b"
                                        class="btn p  w-100 text-white">
                                        الخدمة</a>
                                </td>


                            </tr>
                        </table>

                    </div>
                </div>
                <div class="row">
                    <div class="col text-center">
                        <!--<div class="input-group mb-3">-->
                        <!--  <span class="input-group-text" id="basic-addon1">بحث</span>-->
                        <!--  <input type="text" class="form-control" placeholder="Search Here" aria-label="Username" aria-describedby="basic-addon1">-->
                        <!--</div>-->
                    </div>

                </div>
                <button type="button" class="btn fixedbtn text-white" style="background-color:#38598b"
                    data-bs-toggle="modal" data-bs-target="#example11Modal">

                    <i class="mdi fs-22 mdi-cart-variant">
                        <span class=" topbar-badge cartitem-badge fs-11 translate-middle badge rounded-pill bg-info"
                            style="position:absolute !important;top:0px !important" id="basketCounterLbl">0</span>
                    </i>
                </button>

                <button type="button" class="btn fixedbtn1 text-white" style="background-color:#38598b"
                    data-bs-toggle="modal" data-bs-target="#example11Modal1">

                    <i class="mdi fs-22 mdi-filter-outline"></i>
                </button>
                <input id="input-to-fill" class="opacity-0" type="text" contenteditable="true"
                    placeholder="Barcode Result">
                <div class="row bg-body">

                    <div class="border  col-lg-12 p-3 m-1 table-responsive bg-white" style="">
                        <div class="overflow-hidden h-100 tableOverlay text-center w-100 ">
                            <div class="loader" style="margin: 100px auto;"></div>
                        </div>
                        <table id="example"
                            class=" display table table-bordered dt-responsive dataTable dtr-inline "
                            style="width:100%">
                            <thead class="text">
                                <tr>
                                    <th>View</th>
                                    <th>Image </th>
                                    <th>Name </th>
                                    <th>Numbers </th>
                                    {{-- <th>Source </th>
                                    <th>Status </th>
                                    <th>Quality </th> --}}
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
                                                if (count($item['image']) > 0) {
                                                    $imgName = $item['image'][0]->image_name;
                                                }
                                            @endphp
                                            @if ($item['type_id'] == 1 && count($item['allparts']) > 0)
                                                <!--part-->
                                                <td style="cursor: pointer;"
                                                    onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
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
                                                <span
                                                    class="barcodeTxt  ">{{ $item['type_id'] }}-{{ $item['p_data']->id }}-{{ $item['source'][0]->id }}-{{ $item['status'][0]->id }}-{{ $item['quality'][0]->id }}</span>
                                                <svg class="barcode ">

                                                </svg>

                                            </td>
                                            @if ($item['type_id'] == 1 && count($item['allparts']) > 0)
                                                <!--part-->
                                                <td
                                                    onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
                                                @else
                                                <td>
                                            @endif

                                            {{ $item['p_data']->name }}
                                            <br><span>{{ $item['source'][0]->name_arabic }}</span>
                                            <span>{{ $item['status'][0]->name }}</span>
                                            <span>{{ $item['quality'][0]->name }}</span>
                                            </td>
                                            <td>
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
                                            <td>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}
                                            </td>
                                            <td>{{ $item['Tamount'] }}</td>
                                            <td>
                                                @if (isset($item['section']))
                                                    @for ($i = 0; $i < count($item['section']); $i++)
                                                        <li class="sectionList">
                                                            {{ $item['section'][$i]->store_structure->name }}</li>
                                                    @endfor
                                                @else
                                                    <li class="sectionList">لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                    @for ($i = 0; $i < count($item['groups']); $i++)
                                                        <li> {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li>{{ $item['models'][$i]->series->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->brand_type->name }}
                                                        </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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

                                                <li> {{ $item['type_N'] }} </li>
                                            </td>
                                            <td>
                                                <div class="input-group mb-3 addBtn">
                                                    <input type="number" class="form-control itemAmunt"
                                                        placeholder="Amount" value="1" aria-label="Username"
                                                        aria-describedby="basic-addon1">
                                                    <button class="btn " style="background-color:#a2a8d3"
                                                        onclick="addtoInvoice(this,{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }},1,{{ isset($item['price'][0]->price) ? json_encode($item['price']) : 0 }})">
                                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                                    </button>

                                                </div>

                                            </td>

                                        </tr>
                                    @elseif ($item['type_id'] == 2)
                                        <!--wheel-->
                                        <tr>

                                            @php
                                                $imgName = '';
                                                if (count($item['image']) > 0) {
                                                    $imgName = $item['image'][0]->image_name;
                                                }
                                            @endphp
                                            @if ($item['type_id'] == 2 && count($item['allparts']) > 0)
                                                <!--part-->
                                                <td style="cursor: pointer;"
                                                    onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
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
                                                <span
                                                    class="barcodeTxt  ">{{ $item['type_id'] }}-{{ $item['p_data']->id }}-{{ $item['source'][0]->id }}-{{ $item['status'][0]->id }}-{{ $item['quality'][0]->id }}</span>
                                                <svg class="barcode ">

                                                </svg>

                                            </td>
                                            <td>{{ $item['p_data']['name'] }}
                                                <br><span>{{ $item['source'][0]->name_arabic }}</span>
                                                <span>{{ $item['status'][0]->name }}</span>
                                                <span>{{ $item['quality'][0]->name }}</span>
                                            </td>
                                            <td>
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
                                            <td>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}
                                            </td>
                                            <td>{{ $item['Tamount'] }}</td>
                                            <td>
                                                @if (isset($item['section']))
                                                    @for ($i = 0; $i < count($item['section']); $i++)
                                                        <li class="sectionList">
                                                            {{ $item['section'][$i]->store_structure->name }}</li>
                                                    @endfor
                                                @else
                                                    <li class="sectionList">لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                    @for ($i = 0; $i < count($item['groups']); $i++)
                                                        <li> {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li>{{ $item['models'][$i]->series->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->brand_type->name }}
                                                        </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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

                                                <li> {{ $item['type_N'] }} </li>
                                            </td>
                                            <td>
                                                <div class="input-group mb-3 addBtn">
                                                    <input type="number" class="form-control itemAmunt"
                                                        placeholder="Amount" value="1" aria-label="Username"
                                                        aria-describedby="basic-addon1">
                                                    <button class="btn " style="background-color:#a2a8d3"
                                                        onclick="addtoInvoice(this,{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }},1,{{ isset($item['price'][0]->price) ? json_encode($item['price']) : 0 }})">
                                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                                    </button>

                                                </div>
                                            </td>

                                        </tr>
                                    @elseif ($item['type_id'] == 6)
                                        <!--kit-->
                                        <tr>


                                            @php
                                                $imgName = '';
                                                if (count($item['image']) > 0) {
                                                    $imgName = $item['image'][0]->image_name;
                                                }
                                            @endphp
                                            @if ($item['type_id'] == 6 && count($item['allparts']) > 0)
                                                <!--part-->
                                                <td style="cursor: pointer;"
                                                    onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
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
                                            <td>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}
                                            </td>
                                            <td>{{ $item['Tamount'] }}</td>
                                            <td>
                                                @if (isset($item['section']))
                                                    @for ($i = 0; $i < count($item['section']); $i++)
                                                        <li class="sectionList">
                                                            {{ $item['section'][$i]->store_structure->name }}</li>
                                                    @endfor
                                                @else
                                                    <li class="sectionList">لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                    @for ($i = 0; $i < count($item['groups']); $i++)
                                                        <li> {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li>{{ $item['models'][$i]->series->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->brand_type->name }}
                                                        </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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

                                                <li> {{ $item['type_N'] }} </li>
                                            </td>
                                            <td>
                                                <div class="input-group mb-3 addBtn">
                                                    <input type="number" class="form-control itemAmunt"
                                                        placeholder="Amount" value="1" aria-label="Username"
                                                        aria-describedby="basic-addon1">
                                                    <button class="btn " style="background-color:#a2a8d3"
                                                        onclick="addtoInvoice(this,{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }},1,{{ isset($item['price'][0]->price) ? json_encode($item['price']) : 0 }})">
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
                                                }
                                            @endphp
                                            @if ($item['type_id'] == 3 && count($item['allparts']) > 0)
                                                <!--part-->
                                                <td style="cursor: pointer;"
                                                    onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
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
                                            <td>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}
                                            </td>
                                            <td>{{ $item['Tamount'] }}</td>
                                            <td>
                                                @if (isset($item['section']))
                                                    @for ($i = 0; $i < count($item['section']); $i++)
                                                        <li class="sectionList">
                                                            {{ $item['section'][$i]->store_structure->name }}</li>
                                                    @endfor
                                                @else
                                                    <li class="sectionList">لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                    @for ($i = 0; $i < count($item['groups']); $i++)
                                                        <li> {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li>{{ $item['models'][$i]->series->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->brand_type->name }}
                                                        </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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

                                                <li> {{ $item['type_N'] }} </li>
                                            </td>
                                            <td> <button class="btn btn-success text-bg-dark w-100 addBtn"
                                                    onclick="addtoInvoice(this,{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }},1)">
                                                    <i class="fs-22 mdi mdi-cart-variant"></i>
                                                </button>
                                            </td>

                                        </tr>
                                    @elseif ($item['type_id'] == 4)
                                        <!--tractor-->
                                        <tr>

                                            @php
                                                $imgName = '';
                                                if (count($item['image']) > 0) {
                                                    $imgName = $item['image'][0]->image_name;
                                                }
                                            @endphp
                                            @if ($item['type_id'] == 4 && count($item['allparts']) > 0)
                                                part
                                                <td style="cursor: pointer;"
                                                    onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
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
                                            <td>
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
                                            <td>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}
                                            </td>
                                            <td>{{ $item['Tamount'] }}</td>
                                            <td>
                                                @if (isset($item['section']))
                                                    @for ($i = 0; $i < count($item['section']); $i++)
                                                        <li class="sectionList">
                                                            {{ $item['section'][$i]->store_structure->name }}</li>
                                                    @endfor
                                                @else
                                                    <li class="sectionList">لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                    @for ($i = 0; $i < count($item['groups']); $i++)
                                                        <li> {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li>{{ $item['models'][$i]->series->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->brand_type->name }}
                                                        </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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

                                                <li> {{ $item['type_N'] }} </li>
                                            </td>
                                            <td> <button class="btn btn-success text-bg-dark w-100 addBtn"
                                                    onclick="addtoInvoice(this,{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }},1)">
                                                    <i class="fs-22 mdi mdi-cart-variant"></i>
                                                </button>
                                            </td>

                                        </tr>
                                    @elseif ($item['type_id'] == 5)
                                        <!--tractor-->
                                        <tr>

                                            @php
                                                $imgName = '';
                                                if (count($item['image']) > 0) {
                                                    $imgName = $item['image'][0]->image_name;
                                                }
                                            @endphp
                                            @if ($item['type_id'] == 5 && count($item['allparts']) > 0)
                                                <!--part-->
                                                <td style="cursor: pointer;"
                                                    onclick="CardInfo({{ $item['allparts'][0]->id }},{{ $item['type_id'] }})">
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
                                            <td>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}
                                            </td>
                                            <td>{{ $item['Tamount'] }}</td>
                                            <td>
                                                @if (isset($item['section']))
                                                    @for ($i = 0; $i < count($item['section']); $i++)
                                                        <li class="sectionList">
                                                            {{ $item['section'][$i]->store_structure->name }}</li>
                                                    @endfor
                                                @else
                                                    <li class="sectionList">لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['groups']) && isset($item['groups'][0]->sub_group->group))
                                                    @for ($i = 0; $i < count($item['groups']); $i++)
                                                        <li> {{ $item['groups'][$i]->sub_group->group->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li>{{ $item['models'][$i]->series->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->name }} </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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
                                                @if (isset($item['models']))
                                                    @for ($i = 0; $i < count($item['models']); $i++)
                                                        <li> {{ $item['models'][$i]->series->model->brand_type->name }}
                                                        </li>
                                                    @endfor
                                                @else
                                                    <li>لا يوجد</li>
                                                @endif
                                            </td>
                                            <td>
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

                                                <li> {{ $item['type_N'] }} </li>
                                            </td>
                                            <td> <button class="btn btn-success text-bg-dark w-100 addBtn"
                                                    onclick="addtoInvoice(this,{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }},1)">
                                                    <i class="fs-22 mdi mdi-cart-variant"></i>
                                                </button>
                                            </td>

                                        </tr>
                                    @endif
                                @endforeach
                                {{-- <div id="divOverlay" style=""><p>Overlay Row</p></div> --}}
                            </tbody>
                        </table>

                    </div>

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
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="search" class="form-control mb-2" name="" id="searchSectionTxt">
                        <div class="row">
                            @foreach ($allSections as $section)
                                <div class="col-lg-3 col-sm-12 resSec">
                                    <h3 class="text-bg-dark text-center">{{ $section->name }}</h3>
                                    <table class="table">
                                        @foreach ($section->store_sections as $item)
                                            @if ($item->type_id == 1)
                                                <tr>
                                                    <td>{{ $item->part->name }}</td>
                                                    <td>{{ $item->amount }}</td>
                                                </tr>
                                            @elseif($item->type_id == 2)
                                                <tr>
                                                    <td>{{ $item->wheel->name }}</td>
                                                    <td>{{ $item->amount }}</td>
                                                </tr>
                                            @elseif($item->type_id == 3)
                                                <tr>
                                                    <td>{{ $item->tractor->name }}</td>
                                                    <td>{{ $item->amount }}</td>
                                                </tr>
                                            @elseif($item->type_id == 4)
                                                <tr>
                                                    <td>{{ $item->clark->name }}</td>
                                                    <td>{{ $item->amount }}</td>
                                                </tr>
                                            @elseif($item->type_id == 5)
                                                <tr>
                                                    <td>{{ $item->equip->name }}</td>
                                                    <td>{{ $item->amount }}</td>
                                                </tr>
                                            @elseif($item->type_id == 6)
                                                <tr>
                                                    <td>{{ $item->kit->name }}</td>
                                                    <td>{{ $item->amount }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                </div>
                            @endforeach
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
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
                        <h5 class="modal-title" id="storeMdlLabel">الــــــوا</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="storetbl"
                                class="display table table-bordered dt-responsive dataTable dtr-inline"
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

    <div class="modal fade" id="example11Modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
                                                    <input name="taxes[]" type="checkbox"
                                                        value="{{ $tax->value }}"> {{ $tax->name }} (
                                                    {{ $tax->value }} % )
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
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

    <div class="modal fade" id="example11Modal1" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
                                <select onchange="DTsearch(15,this)" id="" class="form-select"
                                    name="" id="">
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
                                <input onkeyup="DTsearch(3 ,this)" id="FilterNumbertxt" type="text"
                                    name="" class="form-control" id="">
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
                                                onclick="$('#pList').toggle()">Price: $ <span
                                                    id="itemPrice">0</span>
                                            </p>
                                            <div class="row" id="pList" style="display:none">
                                                <div class="col">
                                                    <ul id="" class="mt-3 itemPriceList list-group">
                                                        <li>No Pricing List</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="input-group">

                                                <input type="button" value="+" class="button-plus"
                                                    data-field="quantity">
                                                <input class="form-control" type="number" step="1"
                                                    max="" value="1" name="quantity"
                                                    class="quantity-field" readonly
                                                    style="margin-right: 14px !important;">
                                                <input type="button" value="-" class="button-minus"
                                                    data-field="quantity">

                                            </div>
                                            <a href="#" class="btn btn-primary mt-2 w-100">Add to Cart</a>

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
                                </div>
                            </div>

                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link " id="nav-home-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-home" type="button" role="tab"
                                        aria-controls="nav-home" aria-selected="true">ITEM APPLICATIONS</button>
                                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-profile" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">FLIP CODES</button>
                                    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-contact" type="button" role="tab"
                                        aria-controls="nav-contact" aria-selected="false">Specifications</button>
                                    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-4" type="button" role="tab"
                                        aria-controls="nav-4" aria-selected="false">Stores</button>
                                    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-5" type="button" role="tab"
                                        aria-controls="nav-5" aria-selected="false">Price</button>
                                    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-6" type="button" role="tab"
                                        aria-controls="nav-6" aria-selected="false">suggistion parts</button>
                                    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-7" type="button" role="tab"
                                        aria-controls="nav-7" aria-selected="false">related parts</button>
                                    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-15" type="button" role="tab"
                                        aria-controls="nav-15" aria-selected="false">^</button>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade " id="nav-home" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="row">
                                        <div class="col">
                                            <ul id="itemModels" class="list-group-horizontal">

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                    aria-labelledby="nav-profile-tab">
                                    <div class="row">
                                        <div class="col">
                                            <ul id="itemNumbers"
                                                class="mt-3 list-group list-group-horizontal position-relative overflow-auto w-75">
                                                <li>No Numbers</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="row">
                                        <div class="col">
                                            <ul id="itemSpecs" class="mt-3  list-group">
                                                <li>No Specs</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-4" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="row">
                                        <div class="col">
                                            <ul id="itemStores" class="mt-3  list-group">
                                                <li>No Stocks</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-5" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">


                                    <div class="row">
                                        <div class="col">
                                            <ul id="" class="mt-3 itemPriceList list-group">
                                                <li>No Pricing List</li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane fade" id="nav-6" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="row" id="relatedpartDiv">

                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav-7" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="row" id="relatedpartDiv1">



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


    <script src="{{ URL::asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <!--<script src="{{ URL::asset('assets/js/jquery-3.5.1.js') }}"></script>-->
    <!--{{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}-->
    <!--<script src="https://code.jquery.com/jquery-3.5.1.js"></script>-->
    <!--<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>-->

    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>-->
    <!--<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>-->
    <!--<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>-->
    <!--<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js"></script>-->
    <!--<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>-->
    <!--<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>-->
    <!--<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>-->
    <!--<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js"></script>-->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.js"></script>
    <!--<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>-->
    <!--<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>-->
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>-->
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>-->


    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"></script>
    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>-->
    <!--<script src={{ URL::asset('assets/js/barcode/dbr.js') }}></script>-->

    {{-- <script src="{{ URL::asset('asetNew/js/simpleNav.js') }}"></script> --}}
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



</body>

</html>
