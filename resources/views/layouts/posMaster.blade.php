<!doctype html>
<html lang="en" data-layout="horizontal" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none"
    data-preloader="disable" dir="rtl">


<head>
    <meta charset="utf-8" />
    <title>
        @yield('title')
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Emara" name="description" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="shortcut icon" href="{{ URL::asset('/images/favicon.ico') }}">

    <link href="{{ URL::asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!--<link href="{{ URL::asset('css/timer.css') }}" rel="stylesheet" type="text/css" />-->
    <link href="{{ URL::asset('asetNew/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('asetNew/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <!--<link href="{{ URL::asset('asetNew/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />-->
    <link href="{{ URL::asset('asetNew/css/responsive.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('asetNew/css/searchPanes.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <!--<link href="{{ URL::asset('asetNew/css/ag-grid.min.css') }}" rel="stylesheet" type="text/css" />-->
    <link href="{{ URL::asset('asetNew/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('asetNew/css/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />




    <!--<link href="{{ URL::asset('assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" />-->


    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css">
    <!-- Layout config Js -->
    <script src="{{ URL::asset('assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ URL::asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->

    <!-- App Css-->
    <link href="{{ URL::asset('assets/css/app-rtl.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ URL::asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/assets/libs/sweetalert2/sweetalert2.min.css">
    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>




    
    <style>
        .has-error .select2-selection {
            border: 1px solid #f70400;
            border-radius: 4px;
        }


        .alert {
            position: fixed !important;
            top: 30vh !important;
            left: 35vw !important;
            z-index: 88888 !important;
        }


        @font-face {
            font-family: Cairo;
            src: url('../fonts/Cairo-Light.ttf');


        }

        @font-face {
            font-family: Gess;
            src: url('../fonts/Gess.otf');

        }

        @media (min-width: 320px) and (max-width: 992px) {
            .text-nowrap {
                white-space: normal !important;
            }
        }


        .simplebar-offset {
            margin-top: 10px !important;
        }



        .navbar-header .btn-topbar{
            background: #f3f3f9;
        }
        .navbar-header .btn-topbar:hover{
            color:#fff !important;
            background: #212068;
            height: 40px;
            width: 40px;
        }

        .bg-info {
            --vz-bg-opacity: 1;
            background-color: #cddc39 !important;
        }
        .btn .badge {
            color: #2e2d71;
        }
        .navbar-header .btn-topbar i:hover{
            color: #f3f3f9;
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

        .scrollable-cell ul {
            max-height: 50px !important;
            /* Adjust the max height as needed */
            /* overflow: hidden  !important; */
            overflow-y: scroll !important;
        }

        .list-of-elements ul {
            /* Styles for your list elements */
            /* Example styles: */
            list-style: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .pointer {
            cursor: pointer;
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

        .upload__inputfilePartEdit {
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
            background-color: #3d78e3; //#3d78e3
            border-color: #3d78e3;
            border-radius: 10px;
            line-height: 26px;
            font-size: 14px;
        }

        .carousel-item{
            /* display: contents !important; */
        }
        .upload__btn:hover {
            background-color: unset;
            color: #3d78e3;
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

        .upload__img-wrapPart {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .upload__img-wrapPartEdit {
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

        .upload__img-close-edit {
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

        .upload__img-close-edit:after {
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

        .pointer {
            cursor: pointer;
        }






        .upload__inputfileclarktEdit {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }





        /* * {

            font-family: Cairo, "Times New Roman", Times, serif;
        } */
        .cairo {
            font-family: Cairo, "Times New Roman", Times, serif;
        }




        .dataTables_wrapper {
            font-weight: bold;
        }

        /*
            *  STYLE 3
            */
        .dot {
            height: 25px;
            width: 25px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
        }

        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            background-color: #F5F5F5;
        }

        /*::-webkit-scrollbar {
            width: 6px;
            background-color: #F5F5F5;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #5fcee7;
        }*/

        .dark-carousel {
            background-color: #333;
            color: #fff;
        }

        .dark-carousel .carousel-caption {
            background-color: rgba(0, 0, 0, 0.6);
        }
        [data-layout=horizontal] .navbar-menu .navbar-nav .nav-link:hover{

            color: #fff !important;
        }

        [data-layout=horizontal] .navbar-menu .navbar-nav .nav-link:active{

            color: #fff !important;
        }


        [data-layout=horizontal] .menu-dropdown{
            padding: 0;
            padding-top:3px;
            border-radius:0 !important;
        }
        [data-layout=horizontal] .navbar-nav .nav-item{
            border-bottom: 1px solid #f3f3f9 !important;

        }

        [data-layout=horizontal] .navbar-menu .navbar-nav>li:nth-of-type(2)>.nav-link.menu-link {
            padding-right: 24px!important;
        }
        [data-layout=horizontal] .sidebar-background {
                background: #f3f3f9 !important;
                opacity: 100% !important;
        }
        [data-layout=horizontal] .navbar-menu .navbar-nav .nav-link {
            color: #212068  !important;
        }
        .navbar-nav .nav-item:hover {
            padding-left: 10px;
            background-color: #28276d !important;
            border-radius: 0 !important;
        }
        .menu-dropdown {
            background: linear-gradient(to left, #effaff, #bfbfbf);
        }
        .navbar-menu .navbar-nav .nav-link i {
            font-size: 24px;
            color: #CDDC39;
            font-weight: lighter !important;
        }
        :is(.h1,.h2,.h3,.h4,.h5,.h6,h1,h2,h3,h4,h5,h6){
            font-family: AllGenders !important;
        }
        @font-face {
            font-family: AllGenders;
            src: url('../fonts/AllGenders.otf');


        }
        body {
            font-family: AllGenders !important;

            width: 100vw !important;
            /* overflow: hidden !important; */
        }
        .navbar-menu .navbar-nav .nav-link{
            font-family: AllGenders !important;
        }

        /*  */
    </style>

    @yield('css')


</head>

<body>

    @if ($message = Session::get('success'))
        <div class="alert alert-success" style="z-index: 88888 !important;" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif ($message = Session::get('error'))
        <div class="alert alert-danger " style="z-index: 88888 !important;" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <!-- Begin page -->
    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box horizontal-logo">
                            <a href="/posSearch?_method=POST&storeId={{$store_data[0]->id }}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img href="/assets/images/Header-Logo1.png" alt="" height="19">
                                </span>
                                <span class="logo-lg">
                                    <img href="/assets/images/Header-Logo1.png" alt="" height="50">
                                </span>
                            </a>

                            <a href="/posSearch?_method=POST&storeId={{$store_data[0]->id }}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img href="/assets/images/Header-Logo1.png" alt="" height="19">
                                </span>
                                <span class="logo-lg">
                                    <img href="/assets/images/Header-Logo1.png" alt="" height="50">
                                </span>
                            </a>
                        </div>

                        <button type="button"
                            class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                            id="topnav-hamburger-icon">
                            <span class="hamburger-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>

                        <!-- App Search-->
                        <h3 class="align-items-center d-flex" style="font-family: 'Cairo';">{{ $store_data[0]->name }}</h3>
                    </div>

                    <div class="d-flex align-items-center">


                        <div class="dropdown topbar-head-dropdown ms-1 header-item">
                            <button title="فلتر" type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                data-bs-toggle="modal" data-bs-target="#example11Modal1">
                                <i class="bx bx-filter-alt fs-22"></i>
                            </button>

                        </div>

                        <div class="dropdown topbar-head-dropdown ms-1 header-item">
                            <a title="تقرير يومي" type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                href="{{ route('storeDailyReport', $store_data[0]->id) }}">
                                <i class="bx bx-revision fs-22"></i>
                            </a>

                        </div>


                        <div class="dropdown ms-1 topbar-head-dropdown header-item d-none">
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
                            <button onclick="getSections()" title=" أقسام المخزن" type="button"
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
                        <div class="dropdown topbar-head-dropdown ms-1 header-item">
                            <a title="طلبات للصيانة" type="button" href="/tawreedServicesInbox/{{ $store_data[0]->id }}"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" >
                                <i class="bx bx-export fs-22"></i>
                                <span
                                    class="position-absolute topbar-badge cartitem-badge fs-10 translate-middle badge rounded-pill bg-info"
                                    id="inboxServicesLbl">0</span>
                            </a>

                        </div>
                        <div class="dropdown topbar-head-dropdown ms-1 header-item">
                            <a title="طلبات المخازن" type="button" href="/askStoreInbox/{{ $store_data[0]->id }}"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" >
                                <i class="bx bx-vector fs-22"></i>
                                <span
                                    class="position-absolute topbar-badge cartitem-badge fs-10 translate-middle badge rounded-pill bg-info"
                                    id="inboxOtherStoreAskLbl">0</span>
                            </a>

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
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                               id="storemodal">
                                <i class="bx bx-send fs-22"></i>
                            </button>
                        </div>

                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button title="الاقسام" type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                onclick="location.href='/storeSections/{{ $store_data[0]->id }}'">
                                <i class="bx bx-store fs-22"></i>
                            </button>
                        </div>
                        <div class="dropdown topbar-head-dropdown ms-1 header-item kt_topbar_notifications_3"
                            id="notificationDropdown">
                            <button title="الخدمات" type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                onclick="location.href='/services/{{ $store_data[0]->id }}'">
                                <i class="bx bx-wrench fs-22"></i>
                                {{-- <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger inbox_trans_counter">0<span class="visually-hidden">unread messages</span></span> --}}
                            </button>

                        </div>

                        <div class="dropdown topbar-head-dropdown ms-1 header-item">
                            <button title="  سلة المشتريات" type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                data-bs-toggle="modal" data-bs-target="#example11Modal">
                                <i class="bx bx-cart fs-22">
                                    <span class="position-absolute topbar-badge cartitem-badge fs-10 translate-middle badge rounded-pill bg-info" id="basketCounterLbl">0</span>
                                </i>
                            </button>

                        </div>
                        <div class="dropdown ms-sm-3 header-item">
                            <button type="button" class="btn" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    @if (Auth::user()->profile_img)

                                    <img class="rounded-circle header-profile-user"
                                    src="{{ asset('assets/users_images/' . Auth::user()->profile_img) }}" alt="Header Avatar">
                                    @else
                                    <img src="{{ asset('users_images/default.png') }}" alt="user" style="width: 20px ; height:20px"/>

                                    @endif

                                    <span class="text-start ms-xl-2">
                                        <span
                                            class="d-none d-xl-inline-block ms-1 fw-semibold user-name-text">{{Auth::user()->username}}</span>

                                        <span
                                            class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">{{count( Auth::user()->getRoleNames() ) > 0 ? Auth::user()->getRoleNames()[0] : "" }}</span>
                                    </span>
                                    <i class="fs-18 m-1 ri-arrow-down-s-line" style="color: #29badb;"></i>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <h6 class="dropdown-header">Welcome .....!</h6>
                                <a class="dropdown-item" href="{{route('users.edit',auth()->user()->id)}}"><i
                                        class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                        class="align-middle">Profile</span></a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ route('logout') }}" id="btn-logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
                                        class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span
                                        class="align-middle" data-key="t-logout">Logout</span></a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                          </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- removeNotificationModal -->
        <div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            id="NotificationModalbtn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-2 text-center">
                            <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                colors="primary:#495057,secondary:#f06548" style="width:100px;height:100px">
                            </lord-icon>
                            <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                <h4 class="fw-bold">Are you sure ?</h4>
                                <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                            <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete
                                It!</button>
                        </div>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo-->
                <a href="/" class="logo logo-dark">
                    <span class="logo-sm">
                        <h3 class="text-center"style="color:#38598b">{{ $store_data[0]->name }}</h3>
                    </span>
                    <span class="logo-lg">
                        <h3 class="text-center"style="color:#38598b">{{ $store_data[0]->name }}</h3>
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="/" class="logo logo-light">
                    <span class="logo-sm">
                        <h3 class="text-center"style="color:#38598b">{{ $store_data[0]->name }}</h3>
                    </span>
                    <span class="logo-lg">
                        <h3 class="text-center"style="color:#38598b">{{ $store_data[0]->name }}</h3>
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                    id="vertical-hover">
                    <i class="ri-printer-line"></i>
                </button>
            </div>

            <div id="scrollbar">
                <div class="container-fluid">

                    <div id="two-column-menu">
                    </div>
                    <ul class="justify-content-center navbar-nav" id="navbar-nav">

                        @hasanyrole('super admin|admin')
                            <li class="nav-item">
                                <a class="nav-link menu-link active" href="#sidebarDashboards" data-bs-toggle="collapse"
                                    role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                    <i class="ri-home-4-line"></i> <span data-key="t-dashboards">الرئيسيـــة</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarDashboards">

                                    <ul class="nav nav-sm flex-column">


                                        <li class="nav-item" id="homeli">
                                            <form action="/posSearch" method="POST">
                                                @csrf
                                                @method('GET')
                                                <input type="hidden" name="storeId" value="{{ $store_data[0]->id }}">
                                                <button onclick="openform(this)" class="btn nav-link" data-key="t-analytics"> شاشة البيع  رقم 1</button>
                                            </form>


                                        </li>
                                         <li class="nav-item" id="homelix">

                                            <form action="/newpos" method="POST">
                                                @csrf
                                                @method('GET')
                                                <input type="hidden" name="storeId" value="{{ $store_data[0]->id }}">
                                                <button onclick="this.closest('form').submit()" class="btn nav-link" data-key="t-analytics"> شاشة البيع  رقم 2</button>
                                            </form>

                                        </li>

                                        <li class="nav-item">
                                            <a href="/services/{{ $store_data[0]->id }}" class="nav-link" data-key="t-crm"> الخدمات</a>
                                        </li>
                                        <li class="nav-item">

                                        </li>


                                    </ul>

                                </div>
                            </li> <!-- end Dashboard Menu -->
                        @endhasanyrole

                        @hasanyrole('super admin|admin|stuff')
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#sidebarLayouts11" data-bs-toggle="collapse"
                                    role="button" aria-expanded="false" aria-controls="sidebarLayouts11">
                                    <i class="bx bx-money-withdraw"></i> <span data-key="t-layouts">الحسابــــــات</span>
                                    <span class="badge badge-pill bg-danger" data-key="t-hot">Hot</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarLayouts11">
                                    <ul class="nav nav-sm flex-column">

                                        <li class="nav-item">
                                            <a href="{{ route('get_safe_store', $store_data[0]->id) }}" class="nav-link" data-key="t-horizontal">
                                                الخزينة</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/get_store_employee_salary/{{ $store_data[0]->id }}" class="nav-link" data-key="t-horizontal"> الموظفيين
                                            </a>
                                        </li>


                                    </ul>

                                </div>
                            </li> <!-- end Dashboard Menu -->

                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#sidebarLayouts" data-bs-toggle="collapse"
                                    role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                                    <i class="mdi mdi-store"></i> <span data-key="t-layouts">المخــــــــــــازن</span>
                                    <span class="badge badge-pill bg-danger" data-key="t-hot">Hot</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarLayouts">
                                    <ul class="nav nav-sm flex-column">


                                        <li class="nav-item">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#inboxMdl" class="nav-link" data-key="t-horizontal">حركات
                                                المخــازن</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#storeMdl" class="nav-link" data-key="t-horizontal">ارسال
                                                 لمخزن</a>
                                        </li>



                                    </ul>

                                </div>
                            </li> <!-- end Dashboard Menu -->
                        @endhasanyrole

                        @hasanyrole('super admin|admin|operation')
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#sidebarPages" data-bs-toggle="collapse"
                                    role="button" aria-expanded="false" aria-controls="sidebarPages">
                                    <i class="bx bx-user-pin"></i> <span data-key="t-pages"> العملاء</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarPages">

                                    <ul class="nav nav-sm flex-column">

                                        <li class="nav-item">
                                            <a href="#"  data-bs-toggle="modal" data-bs-target="#ClientMdl" class="nav-link"><span data-key="t-term-conditions">
                                                    العملاء </span></a>
                                        </li>





                                    </ul>

                                </div>
                            </li>
                        @endhasanyrole

                        @hasanyrole('super admin|admin|stuff')
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#sidebarLayouts112" data-bs-toggle="collapse"
                                    role="button" aria-expanded="false" aria-controls="sidebarLayouts112">
                                    <i class="bx bx-receipt"></i> <span data-key="t-layouts">التقارير</span>
                                    <span class="badge badge-pill bg-danger" data-key="t-hot">Hot</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarLayouts112">
                                    <ul class="nav nav-sm flex-column">

                                        <!--<li class="nav-item">-->
                                        <!--    <a href="#" class="nav-link"-->
                                        <!--        data-key="t-horizontal"> المركز المحاسبي </a>-->
                                        <!--</li>-->
                                          <li class="nav-item">
                                            <a href="{{ route('storeDailyReport', $store_data[0]->id) }}" class="nav-link" data-key="t-horizontal"> المبيعات اليومية </a>
                                        </li>
                                        <!--<li class="nav-item">-->
                                        <!--    <a href="#" class="nav-link" data-key="t-horizontal">-->
                                        <!--        إفراج جمركي  </a>-->
                                        <!--</li>-->
                                        <!--<li class="nav-item">-->
                                        <!--    <a href="#" class="nav-link" data-key="t-horizontal">-->
                                        <!--        جرد الصندوق </a>-->
                                        <!--</li>-->
                                      
                                        <li class="nav-item">
                                            <a href="{{ route('defectsItems', $store_data[0]->id) }}" class="nav-link" data-key="t-horizontal"> النواقص </a>
                                        </li>


                                    </ul>

                                </div>
                            </li> <!-- end Dashboard Menu -->
                        @endhasanyrole



                    </ul>
                </div>
                <!-- Sidebar -->
            </div>

            <div class="sidebar-background"></div>
        </div>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        @yield('content')





    </div>
    <!-- END layout-wrapper -->



    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!--preloader-->
    <div id="preloader" style="opacity: 1;visibility: visible;">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <div class="customizer-setting d-none d-md-block">
        <div class="btn-info btn-rounded shadow-lg btn btn-icon btn-lg p-2" data-bs-toggle="offcanvas"
            data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas">
            <i class='mdi mdi-spin mdi-cog-outline fs-22'></i>
        </div>
    </div>

    <!-- Theme Settings -->
    <div class="offcanvas offcanvas-end border-0" tabindex="-1" id="theme-settings-offcanvas">
        <div class="d-flex align-items-center bg-primary bg-gradient p-3 offcanvas-header">
            <h5 class="m-0 me-2 text-white">Theme Customizer</h5>

            <button type="button" class="btn-close btn-close-white ms-auto" id="customizerclose-btn"
                data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div data-simplebar class="h-100">
                <div class="p-4">
                    <h6 class="mb-0 fw-bold text-uppercase">Layout</h6>
                    <p class="text-muted">Choose your layout</p>

                    <div class="row gy-3">
                        <div class="col-4">
                            <div class="form-check card-radio">
                                <input id="customizer-layout01" name="data-layout" type="radio"
                                    value="vertical" class="form-check-input">
                                <label class="form-check-label p-0 avatar-md w-100" for="customizer-layout01">
                                    <span class="d-flex gap-1 h-100">
                                        <span class="flex-shrink-0">
                                            <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                            </span>
                                        </span>
                                        <span class="flex-grow-1">
                                            <span class="d-flex h-100 flex-column">
                                                <span class="bg-light d-block p-1"></span>
                                                <span class="bg-light d-block p-1 mt-auto"></span>
                                            </span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Vertical</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio">
                                <input id="customizer-layout02" name="data-layout" type="radio"
                                    value="horizontal" class="form-check-input">
                                <label class="form-check-label p-0 avatar-md w-100" for="customizer-layout02">
                                    <span class="d-flex h-100 flex-column gap-1">
                                        <span class="bg-light d-flex p-1 gap-1 align-items-center">
                                            <span class="d-block p-1 bg-soft-primary rounded me-1"></span>
                                            <span class="d-block p-1 pb-0 px-2 bg-soft-primary ms-auto"></span>
                                            <span class="d-block p-1 pb-0 px-2 bg-soft-primary"></span>
                                        </span>
                                        <span class="bg-light d-block p-1"></span>
                                        <span class="bg-light d-block p-1 mt-auto"></span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Horizontal</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio">
                                <input id="customizer-layout03" name="data-layout" type="radio"
                                    value="twocolumn" class="form-check-input">
                                <label class="form-check-label p-0 avatar-md w-100" for="customizer-layout03">
                                    <span class="d-flex gap-1 h-100">
                                        <span class="flex-shrink-0">
                                            <span class="bg-light d-flex h-100 flex-column gap-1">
                                                <span class="d-block p-1 bg-soft-primary mb-2"></span>
                                                <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                            </span>
                                        </span>
                                        <span class="flex-shrink-0">
                                            <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                            </span>
                                        </span>
                                        <span class="flex-grow-1">
                                            <span class="d-flex h-100 flex-column">
                                                <span class="bg-light d-block p-1"></span>
                                                <span class="bg-light d-block p-1 mt-auto"></span>
                                            </span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Two Column</h5>
                        </div>
                        <!-- end col -->

                        <div class="col-4">
                            <div class="form-check card-radio">
                                <input id="customizer-layout04" name="data-layout" type="radio"
                                    value="semibox" class="form-check-input">
                                <label class="form-check-label p-0 avatar-md w-100" for="customizer-layout04">
                                    <span class="d-flex gap-1 h-100">
                                        <span class="flex-shrink-0 p-1">
                                            <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                            </span>
                                        </span>
                                        <span class="flex-grow-1">
                                            <span class="d-flex h-100 flex-column pt-1 pe-2">
                                                <span class="bg-light d-block p-1"></span>
                                                <span class="bg-light d-block p-1 mt-auto"></span>
                                            </span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Semi Box</h5>
                        </div>
                        <!-- end col -->
                    </div>

                    <h6 class="mt-4 mb-0 fw-bold text-uppercase">Color Scheme</h6>
                    <p class="text-muted">Choose Light or Dark Scheme.</p>

                    <div class="colorscheme-cardradio">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-check card-radio">
                                    <input class="form-check-input" type="radio" name="data-layout-mode"
                                        id="layout-mode-light" value="light">
                                    <label class="form-check-label p-0 avatar-md w-100" for="layout-mode-light">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span
                                                        class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Light</h5>
                            </div>

                            <div class="col-4">
                                <div class="form-check card-radio dark">
                                    <input class="form-check-input" type="radio" name="data-layout-mode"
                                        id="layout-mode-dark" value="dark">
                                    <label class="form-check-label p-0 avatar-md w-100 bg-dark"
                                        for="layout-mode-dark">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-soft-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span class="d-block p-1 px-2 bg-soft-light rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-soft-light d-block p-1"></span>
                                                    <span class="bg-soft-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Dark</h5>
                            </div>
                        </div>
                    </div>

                    <div id="sidebar-visibility">
                        <h6 class="mt-4 mb-0 fw-bold text-uppercase">Sidebar Visibility</h6>
                        <p class="text-muted">Choose show or Hidden sidebar.</p>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-check card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar-visibility"
                                        id="sidebar-visibility-show" value="show">
                                    <label class="form-check-label p-0 avatar-md w-100"
                                        for="sidebar-visibility-show">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0 p-1">
                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span
                                                        class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column pt-1 pe-2">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Show</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar-visibility"
                                        id="sidebar-visibility-hidden" value="hidden">
                                    <label class="form-check-label p-0 avatar-md w-100 px-2"
                                        for="sidebar-visibility-hidden">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column pt-1 px-2">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Hidden</h5>
                            </div>
                        </div>
                    </div>

                    <div id="layout-width">
                        <h6 class="mt-4 mb-0 fw-bold text-uppercase">Layout Width</h6>
                        <p class="text-muted">Choose Fluid or Boxed layout.</p>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-check card-radio">
                                    <input class="form-check-input" type="radio" name="data-layout-width"
                                        id="layout-width-fluid" value="fluid">
                                    <label class="form-check-label p-0 avatar-md w-100" for="layout-width-fluid">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span
                                                        class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Fluid</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check card-radio">
                                    <input class="form-check-input" type="radio" name="data-layout-width"
                                        id="layout-width-boxed" value="boxed">
                                    <label class="form-check-label p-0 avatar-md w-100 px-2"
                                        for="layout-width-boxed">
                                        <span class="d-flex gap-1 h-100 border-start border-end">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span
                                                        class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Boxed</h5>
                            </div>
                        </div>
                    </div>

                    <div id="layout-position">
                        <h6 class="mt-4 mb-0 fw-bold text-uppercase">Layout Position</h6>
                        <p class="text-muted">Choose Fixed or Scrollable Layout Position.</p>

                        <div class="btn-group radio" role="group">
                            <input type="radio" class="btn-check" name="data-layout-position"
                                id="layout-position-fixed" value="fixed">
                            <label class="btn btn-light w-sm" for="layout-position-fixed">Fixed</label>

                            <input type="radio" class="btn-check" name="data-layout-position"
                                id="layout-position-scrollable" value="scrollable">
                            <label class="btn btn-light w-sm ms-0"
                                for="layout-position-scrollable">Scrollable</label>
                        </div>
                    </div>
                    <h6 class="mt-4 mb-0 fw-bold text-uppercase">Topbar Color</h6>
                    <p class="text-muted">Choose Light or Dark Topbar Color.</p>

                    <div class="row">
                        <div class="col-4">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="data-topbar"
                                    id="topbar-color-light" value="light">
                                <label class="form-check-label p-0 avatar-md w-100" for="topbar-color-light">
                                    <span class="d-flex gap-1 h-100">
                                        <span class="flex-shrink-0">
                                            <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                            </span>
                                        </span>
                                        <span class="flex-grow-1">
                                            <span class="d-flex h-100 flex-column">
                                                <span class="bg-light d-block p-1"></span>
                                                <span class="bg-light d-block p-1 mt-auto"></span>
                                            </span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Light</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="data-topbar"
                                    id="topbar-color-dark" value="dark">
                                <label class="form-check-label p-0 avatar-md w-100" for="topbar-color-dark">
                                    <span class="d-flex gap-1 h-100">
                                        <span class="flex-shrink-0">
                                            <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                            </span>
                                        </span>
                                        <span class="flex-grow-1">
                                            <span class="d-flex h-100 flex-column">
                                                <span class="bg-primary d-block p-1"></span>
                                                <span class="bg-light d-block p-1 mt-auto"></span>
                                            </span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Dark</h5>
                        </div>
                    </div>

                    <div id="sidebar-size">
                        <h6 class="mt-4 mb-0 fw-bold text-uppercase">Sidebar Size</h6>
                        <p class="text-muted">Choose a size of Sidebar.</p>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar-size"
                                        id="sidebar-size-default" value="lg">
                                    <label class="form-check-label p-0 avatar-md w-100" for="sidebar-size-default">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span
                                                        class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Default</h5>
                            </div>

                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar-size"
                                        id="sidebar-size-compact" value="md">
                                    <label class="form-check-label p-0 avatar-md w-100" for="sidebar-size-compact">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span class="d-block p-1 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Compact</h5>
                            </div>

                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar-size"
                                        id="sidebar-size-small" value="sm">
                                    <label class="form-check-label p-0 avatar-md w-100" for="sidebar-size-small">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1">
                                                    <span class="d-block p-1 bg-soft-primary mb-2"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Small (Icon View)</h5>
                            </div>

                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar-size"
                                        id="sidebar-size-small-hover" value="sm-hover">
                                    <label class="form-check-label p-0 avatar-md w-100"
                                        for="sidebar-size-small-hover">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1">
                                                    <span class="d-block p-1 bg-soft-primary mb-2"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Small Hover View</h5>
                            </div>
                        </div>
                    </div>

                    <div id="sidebar-view">
                        <h6 class="mt-4 mb-0 fw-bold text-uppercase">Sidebar View</h6>
                        <p class="text-muted">Choose Default or Detached Sidebar view.</p>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-layout-style"
                                        id="sidebar-view-default" value="default">
                                    <label class="form-check-label p-0 avatar-md w-100" for="sidebar-view-default">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span
                                                        class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Default</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-layout-style"
                                        id="sidebar-view-detached" value="detached">
                                    <label class="form-check-label p-0 avatar-md w-100" for="sidebar-view-detached">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-flex p-1 gap-1 align-items-center px-2">
                                                <span class="d-block p-1 bg-soft-primary rounded me-1"></span>
                                                <span class="d-block p-1 pb-0 px-2 bg-soft-primary ms-auto"></span>
                                                <span class="d-block p-1 pb-0 px-2 bg-soft-primary"></span>
                                            </span>
                                            <span class="d-flex gap-1 h-100 p-1 px-2">
                                                <span class="flex-shrink-0">
                                                    <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    </span>
                                                </span>
                                            </span>
                                            <span class="bg-light d-block p-1 mt-auto px-2"></span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Detached</h5>
                            </div>
                        </div>
                    </div>
                    <div id="sidebar-color">
                        <h6 class="mt-4 mb-0 fw-bold text-uppercase">Sidebar Color</h6>
                        <p class="text-muted">Choose a color of Sidebar.</p>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio" data-bs-toggle="collapse"
                                    data-bs-target="#collapseBgGradient.show">
                                    <input class="form-check-input" type="radio" name="data-sidebar"
                                        id="sidebar-color-light" value="light">
                                    <label class="form-check-label p-0 avatar-md w-100" for="sidebar-color-light">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-white border-end d-flex h-100 flex-column gap-1 p-1">
                                                    <span
                                                        class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Light</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio" data-bs-toggle="collapse"
                                    data-bs-target="#collapseBgGradient.show">
                                    <input class="form-check-input" type="radio" name="data-sidebar"
                                        id="sidebar-color-dark" value="dark">
                                    <label class="form-check-label p-0 avatar-md w-100" for="sidebar-color-dark">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-primary d-flex h-100 flex-column gap-1 p-1">
                                                    <span class="d-block p-1 px-2 bg-soft-light rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Dark</h5>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-link avatar-md w-100 p-0 overflow-hidden border collapsed"
                                    type="button" data-bs-toggle="collapse" data-bs-target="#collapseBgGradient"
                                    aria-expanded="false" aria-controls="collapseBgGradient">
                                    <span class="d-flex gap-1 h-100">
                                        <span class="flex-shrink-0">
                                            <span class="bg-vertical-gradient d-flex h-100 flex-column gap-1 p-1">
                                                <span class="d-block p-1 px-2 bg-soft-light rounded mb-2"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                            </span>
                                        </span>
                                        <span class="flex-grow-1">
                                            <span class="d-flex h-100 flex-column">
                                                <span class="bg-light d-block p-1"></span>
                                                <span class="bg-light d-block p-1 mt-auto"></span>
                                            </span>
                                        </span>
                                    </span>
                                </button>
                                <h5 class="fs-13 text-center mt-2">Gradient</h5>
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="collapse" id="collapseBgGradient">
                            <div class="d-flex gap-2 flex-wrap img-switch p-2 px-3 bg-light rounded">

                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar"
                                        id="sidebar-color-gradient" value="gradient">
                                    <label class="form-check-label p-0 avatar-xs rounded-circle"
                                        for="sidebar-color-gradient">
                                        <span class="avatar-title rounded-circle bg-vertical-gradient"></span>
                                    </label>
                                </div>
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar"
                                        id="sidebar-color-gradient-2" value="gradient-2">
                                    <label class="form-check-label p-0 avatar-xs rounded-circle"
                                        for="sidebar-color-gradient-2">
                                        <span class="avatar-title rounded-circle bg-vertical-gradient-2"></span>
                                    </label>
                                </div>
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar"
                                        id="sidebar-color-gradient-3" value="gradient-3">
                                    <label class="form-check-label p-0 avatar-xs rounded-circle"
                                        for="sidebar-color-gradient-3">
                                        <span class="avatar-title rounded-circle bg-vertical-gradient-3"></span>
                                    </label>
                                </div>
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar"
                                        id="sidebar-color-gradient-4" value="gradient-4">
                                    <label class="form-check-label p-0 avatar-xs rounded-circle"
                                        for="sidebar-color-gradient-4">
                                        <span class="avatar-title rounded-circle bg-vertical-gradient-4"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="sidebar-img">
                        <h6 class="mt-4 mb-0 fw-bold text-uppercase">Sidebar Images</h6>
                        <p class="text-muted">Choose a image of Sidebar.</p>

                        <div class="d-flex gap-2 flex-wrap img-switch">
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidebar-image"
                                    id="sidebarimg-none" value="none">
                                <label class="form-check-label p-0 avatar-sm h-auto" for="sidebarimg-none">
                                    <span
                                        class="avatar-md w-auto bg-light d-flex align-items-center justify-content-center">
                                        <i class="ri-close-fill fs-20"></i>
                                    </span>
                                </label>
                            </div>

                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidebar-image"
                                    id="sidebarimg-01" value="img-1">
                                <label class="form-check-label p-0 avatar-sm h-auto" for="sidebarimg-01">
                                    <!--<img src="/assets/images/sidebar/img-1.jpg" alt=""-->
                                    <!--    class="avatar-md w-auto object-cover">-->
                                </label>
                            </div>

                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidebar-image"
                                    id="sidebarimg-02" value="img-2">
                                <label class="form-check-label p-0 avatar-sm h-auto" for="sidebarimg-02">
                                    <!--<img src="/assets/images/sidebar/img-2.jpg" alt=""-->
                                    <!--    class="avatar-md w-auto object-cover">-->
                                </label>
                            </div>
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidebar-image"
                                    id="sidebarimg-03" value="img-3">
                                <label class="form-check-label p-0 avatar-sm h-auto" for="sidebarimg-03">
                                    <!--<img src="/assets/images/sidebar/img-3.jpg" alt=""-->
                                    <!--    class="avatar-md w-auto object-cover">-->
                                </label>
                            </div>
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidebar-image"
                                    id="sidebarimg-04" value="img-4">
                                <label class="form-check-label p-0 avatar-sm h-auto" for="sidebarimg-04">
                                    <!--<img src="/assets/images/sidebar/img-4.jpg" alt=""-->
                                    <!--    class="avatar-md w-auto object-cover">-->
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="preloader-menu">
                        <h6 class="mt-4 mb-0 fw-bold text-uppercase">Preloader</h6>
                        <p class="text-muted">Choose a preloader.</p>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-preloader"
                                        id="preloader-view-custom" value="disable">
                                    <label class="form-check-label p-0 avatar-md w-100" for="preloader-view-custom">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span
                                                        class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                        <!-- <div id="preloader"> -->
                                        <div id="status"
                                            class="d-flex align-items-center justify-content-center">
                                            <div class="spinner-border text-primary avatar-xxs m-auto"
                                                role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        <!-- </div> -->
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Enable</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-preloader"
                                        id="preloader-view-none" value="enable">
                                    <label class="form-check-label p-0 avatar-md w-100" for="preloader-view-none">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span
                                                        class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Disable</h5>
                            </div>
                        </div>

                    </div>
                    <!-- end preloader-menu -->

                </div>
            </div>

        </div>
        <div class="offcanvas-footer border-top p-3 text-center">
            <div class="row">
                <div class="col-12">
                    <button type="button" class="btn btn-light w-100" id="reset-layout">Reset</button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <div class="card-body text-center float-start w-50">

                            <div class="row">
                                <div class="col align-self-xxl-center">
                                    <h5 class="card-title" id="itemName">Product Title</h5>
                                    <p class="card-text" id="itemDesc">Product Description</p>
                                </div>
                                <div class="col">
                                    <p class="card-text" id="itemQuality">Product Description</p>
                                </div>
                            </div>
                            <hr>
                            <p class="card-text">Price: $ <span id="itemPrice">0</span></p>
                            <p class="card-text">Status: <span id="itemStock">0</span></p>
                            <p class="card-text" id="itemGroup">Availability: Ships within 2-3 business days</p>
                            <!--{{-- <a href="#" class="btn btn-primary">Add to Cart</a> --}}-->
                        </div>
                        <div id="productCarousel1" class="carousel slide d-inline-block w-50"
                            data-bs-ride="carousel">
                            <div class="carousel-inner" id="item-image-car">

                            </div>
                            <a class="carousel-control-prev " href="#productCarousel1" role="button"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon text-bg-dark" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </a>
                            <a class="carousel-control-next " href="#productCarousel1" role="button"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon text-bg-dark" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </a>
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
                                            <ul id="itemPriceList" class="mt-3  list-group">
                                                <li>No Pricing List</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h2 onclick="$('#relatedpartDiv').toggle()" style="cursor: pointer;"
                        class="rounded-4 text-bg-dark text-center">Suggestion part</h2>
                    <div class="row" id="relatedpartDiv" style="display: none;">

                    </div>
                    <h2 onclick="$('#relatedpartDiv1').toggle()" style="cursor: pointer;"
                        class="rounded-4 text-bg-primary text-center">Related part</h2>
                    <div class="row" id="relatedpartDiv1" style="display: none;">

                    </div>
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

     <div class="modal fade" id="newclientMdl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="newclientMdlLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 80vw !important;">
            <div class="modal-content w-100" style="width: 100%!important">
                <div class="modal-header ">
                    <h5 class="modal-title" id="newclientMdlLabel">عميل جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">

                        <div class="row">
                            <div class="col-lg-1">
                                <label for="">الاسم</label>
                            </div>
                            <div class="col-lg-3">
                                <input type="text" class="form-control" name="" id="clientName">
                            </div>
                            <div class="col-lg-1">
                                <label for="">رقم التليفون</label>
                            </div>
                            <div class="col-lg-3">
                                <input type="text" class="form-control" name="" id="clientTel">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <button type="button" id="saveNewClient" class="btn btn-success mt-2 w-100">Save</button>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
    <!-- JAVASCRIPT -->

    <script>
        document.addEventListener('readystatechange', event => {

            // When HTML/DOM elements are ready:
            if (event.target.readyState ===
                "interactive") { //does same as:  ..addEventListener("DOMContentLoaded"..

                document.getElementById("preloader").style.opacity = '1';
                document.getElementById("preloader").style.visibility = 'visible';
            }

            // When window loaded ( external resources are loaded too- `css`,`src`, etc...)
            if (event.target.readyState === "complete") {

                document.getElementById("preloader").style.opacity = '0';
                document.getElementById("preloader").style.visibility = 'hidden';
            }
        });
    </script>


    <script src="{{ URL::asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ URL::asset('assets/libs/simplebar/simplebar.min.js') }}"></script>

    <script src="{{ URL::asset('assets/libs/node-waves/waves.min.js') }}"></script>

    <script src="{{ URL::asset('assets/libs/feather-icons/feather.min.js') }}"></script>

    <!--<script src="{{ URL::asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>-->

    <script src="{{ URL::asset('assets/js/plugins.js') }}"></script>
    <!-- apexcharts -->
    <!--<script src="{{ URL::asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>-->

    <script src="../assets/js/scripts.bundle.js"></script>

    <script src="{{ URL::asset('assets/js/app.js') }}"></script>

    <script>
        // $("#customizerclose-btn").click()
        // document.getElementById('customizerclose-btn').click()
        window.onload = function() {
            document.getElementById('customizerclose-btn').click()
         }
    </script>


    <script src="{{ URL::asset('asetNew/js/jquery-3.5.1.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!--<script src="{{ URL::asset('js/timer.js') }}"></script>-->
    <script src="{{ URL::asset('asetNew/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('asetNew/js/dataTables.responsive.js') }}"></script>
    <script src="{{ URL::asset('asetNew/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('asetNew/js/buttons.print.min.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.colVis.min.js"></script>
    <script src="{{ URL::asset('asetNew/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('asetNew/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('asetNew/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('asetNew/js/jquery.validate.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"></script>
    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>-->
    <!--<script src={{ URL::asset('assets/js/barcode/dbr.js') }}></script>-->
    <!--<script src="https://cdn.jsdelivr.net/npm/ag-grid-enterprise/dist/ag-grid-enterprise.min.js"></script>-->




    <script>
        // $.validator.setDefaults({
        //     errorElement: "span",
        //     errorClass: "help-block",
        //     //	validClass: 'stay',
        //     highlight: function(element, errorClass, validClass) {
        //         $(element).addClass(errorClass); //.removeClass(errorClass);
        //         $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        //     },
        //     unhighlight: function(element, errorClass, validClass) {
        //         $(element).removeClass(errorClass); //.addClass(validClass);
        //         $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        //     },
        //     errorPlacement: function(error, element) {
        //         if (element.parent('.input-group').length) {
        //             error.insertAfter(element.parent());
        //         } else if (element.hasClass('select2')) {
        //             error.insertAfter(element.next('span'));
        //         } else {
        //             error.insertAfter(element);
        //         }
        //     }
        // });


            var store_data ='';

        $(document).ready(function() {
            store_data = {!! $store_data !!};
            $('.select2').on('change', function() {
                $(this).valid();
            });

            $("#gyr_ind").select2();

            // var validator = $(".needs-validation").validate();
            // inboxServicesLbl
            var x = getCountServicesParts();
            $("#inboxServicesLbl").text(x);

            var y = getCounttalabatParts();
            $("#inboxOtherStoreAskLbl").text(y);

        });

        function openform(el){
            // el.closest('form').submit();
            $(el.closest('form')).submit()
        }
        function getCounttalabatParts(){
            var count = 0;
            $.ajax({
                type: "get",
                async: false,
                url: "/getCounttalabatParts/"+store_data[0].id,
                success: function (response) {
                   count = response;
                },
            });
            return count;
        }
        function getCountServicesParts(){
            var count = 0;
            $.ajax({
                type: "get",
                async: false,
                url: "/getCountServicesParts/"+store_data[0].id,
                success: function (response) {
                   count = response;
                },
            });
            return count;
        }
    </script>
    

    @yield('js')
</body>

</html>
