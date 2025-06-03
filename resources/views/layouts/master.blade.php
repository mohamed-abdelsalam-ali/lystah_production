<!doctype html>
<html lang="en" data-layout="horizontal" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none"
    data-preloader="disable" dir="rtl">


<head>
    <meta charset="utf-8" />
    <title>
        Lystah | ليسته
        {{-- @yield('title') --}}
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Lystah Cloud ERP" name="description" />
    <meta content="ERP" name="visioncore" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{-- <link rel="stylesheet" href="css/timer.css"> --}}
    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="icon" type="image/x-icon" href="{{ URL::asset('assets/images/NFavicon.ico') }}">
    {{-- <link rel="shortcut icon" href="{{ URL::asset('/images/favicon.ico') }}"> --}}

    <link href="{{ URL::asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('css/timer.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('asetNew/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('asetNew/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('asetNew/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('asetNew/css/responsive.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('asetNew/css/searchPanes.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('asetNew/css/ag-grid.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('asetNew/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('asetNew/css/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    {{-- <link rel="stylesheet" href="asetNew/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="asetNew/css/bootstrap.css">
    <link rel="stylesheet" href="asetNew/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="asetNew/css/responsive.bootstrap.css">
    <link rel="stylesheet" href="asetNew/css/searchPanes.dataTables.min.css">
    <link rel="stylesheet" href="asetNew/css/ag-grid.min.css">


    <link href="asetNew/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="asetNew/css/buttons.dataTables.min.css"> --}}
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
        body{

        }
        .dataTables_processing {
            z-index: 9999 !important;
            width: 100vw !important;
            height: 100vh !important;
            top: 50% !important;
            left: 0 !important;
            margin:0 !important;
            position: fixed !important;
        }

        table tr td {
            align-content: center !important;
            text-align: center !important;
        }

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
        .menu-dropdown{
            background: linear-gradient(to left, #384044, #565555);
        }
        .nav-item {
            /* text-decoration:none !important; */

        }

        .menu-dropdown .menu-dropdown {
            left: 100% !important;
            right: auto !important;
        }

        .menu-dropdown:first-child {
            right: 100% !important;
            left: auto !important;
        }

        [data-layout=horizontal] .dropdown-custom-right:first-child .menu-dropdown {
            right: 100% !important;
            left: auto !important;
        }

        [data-layout=horizontal] .dropdown-custom-right:first-child {
            right: 100% !important;
            left: auto !important;
        }

        [data-layout=horizontal] .sidebar-background {
            background: #384044 !important;
            opacity: 100% !important;
        }

        [data-layout=horizontal] .navbar-menu .navbar-nav .nav-link {
            color: #000000 !important;
        }
        [data-layout=horizontal] .nav.nav-link {
            color: #FFF !important;
        }

        .navbar-menu .navbar-nav .nav-sm .nav-link {
            font-family: AllGenders;
        }

        .nav-link {
            color: #495057 !important;
            font-weight: bold;
            border-bottom: 0px !important;
            /* text-shadow: 0px 1px 1px #495057; */
        }

        [data-layout=horizontal] .navbar-menu .navbar-nav .nav-link {
            color: #FFF !important;
        }

        .navbar-menu .navbar-nav .nav-link i {
            font-size: 20px;
            color: #92c01c;
            font-weight: lighter !important;
        }

        .navbar-header .bx {
            color: #1c1c1c;
            font-weight: lighter !important;
        }

        .app-search h3 {
            color: #ffa500;

        }

        /*@font-face {*/
        /*    font-family: Cairo;*/
        /*    src: url('../fonts/Cairo-Light.ttf');*/


        /*}*/

        /*body {*/
        /*    font-family: Gess !important;*/
        /*    width: 100vw !important;*/
            /* overflow: hidden !important; */
        /*}*/
        /*@font-face {*/
        /*    font-family: Gess;*/
        /*    src: url('../fonts/Gess.otf');*/

        /*}*/
            /*:is(.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6) {*/
        /*    font-family: Gess;*/

        /*}*/

        /*.navbar-menu .navbar-nav .nav-link {*/
        /*    font-family: Gess;*/

        /*}*/
            /* @font-face {
                        font-family: AllGenders;
                        src: url('../fonts/AllGenders.otf');
                
            } */
            * {
                        font-family: AllGenders !important;
                        font-size: small;
                    }
            .navbar-menu .navbar-nav .nav-link {
                        font-family: AllGenders;
                        /*font-size: large;*/
                    }

    
        .navbar-nav .nav-item:hover{
            background-color:#f0ffff30!important;
        }

        @media (min-width: 320px) and (max-width: 992px) {
            .text-nowrap {
                white-space: normal !important;
            }
        }


        .simplebar-offset {
            margin-top: 10px !important;
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
            font-family: AllGenders !imortant, "Times New Roman", Times, serif;
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

        /*
        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            background-color: #F5F5F5;
        }
        */
        /*
        ::-webkit-scrollbar {
            width: 6px;
            background-color: #F5F5F5;
        }
        */
        /**
        ::-webkit-scrollbar-thumb {
            background-color: #5fcee7;
        }
        */

        .dark-carousel {
            background-color: #333;
            color: #fff;
        }

        .dark-carousel .carousel-caption {
            background-color: rgba(0, 0, 0, 0.6);
        }


        .nav-link {
            font-size: 0.95rem;
            font-weight: 700;
            border-bottom: solid 1px gray;
        }
    </style>

    <style>
        .bg-sysgradient {
            background: linear-gradient(to left, #384044, #565555);
        }
        .search {
            width: 100%;
            position: relative;
            display: flex;
        }

        .searchTerm {
            width: 100%;
            border: 3px solid #00B4CC;
            border-right: none;
            padding: 5px;
            height: 35px;
            border-radius: 5px 0 0 5px;
            outline: none;
            color: #9DBFAF;
        }

        .searchTerm:focus {
            color: #00B4CC;
        }

        .searchButton {
            width: 40px;
            height: 36px;
            border: 1px solid #00B4CC;
            background: #00B4CC;
            text-align: center;
            color: #fff;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            font-size: 20px;
        }

        /*Resize the wrap to see the search bar change!*/
        .wrap {
            width: 30%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        
        /* Notification container styles */
        .notification-container {
            position: fixed;
            bottom: 5px;
            right: 10px;
            width: 300px;
            max-height: 80vh;
            overflow-y: auto;
            z-index: 1051;
            padding: 10px;
            background-color: rgba(0, 0, 0, 0.1); /* Transparent dark background */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        /* Individual notification styles */
        .notification {
            background-color: #fff;
            border-left: 5px solid #007bff; /* Blue indicator on the left */
            padding: 12px;
            margin-bottom: 10px;
             z-index: 9999999;
            border-radius: 5px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        
        /* Hover effect for notifications */
        .notification:hover {
            background-color: #f1f1f1;
            transform: translateX(5px);
        }
        
        /* Style for read notifications (faded background) */
        .notification.read {
            background-color: #f9f9f9; /* Lighter background for read notifications */
            border-left: 5px solid #ddd; /* Faded indicator for read notifications */
        }
        
        /* Notification title and message style */
        .notification .message {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
        }
        
        .notification .time {
            font-size: 12px;
            color: #888;
        }
        .alert{
            right : 0px !important;
            top :  0px !important;
            left : auto !important;
        }
        .fade-out {
            animation: fadeOut 1s forwards;
        }
        @keyframes fadeOut {
            0% {
                opacity: 1;
            }
            100% {
                opacity: 0;
            }
        }
    </style>
    @yield('css')


</head>

<body class="cairo">
        <div id="notification-container" class="notification-container">
            <!-- Notifications will be inserted here dynamically using JavaScript -->
        </div>

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
    @php
        use App\Models\Company\User;
        $user = auth()->user();
        $generalUser = User::on('mysql_general')->where('email', $user->email)->first();
    @endphp
                     
        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- Horizontal-LOGO -->
                        <div class="horizontal-logo navbar-brand-box p-0">  
                            <a href="/home" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{$generalUser->company_logo}}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <svg id="Layer_1" class="h-100 w-md float-end" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 160 70"><title>Header-Logo</title>
                                    <image width="120" height="70" href="{{ $generalUser->company_logo }}"></image>
                                   
                                    
                                </span>
                            </a>
                      <a href="/" class="logo logo-light">
                                <span class="logo-sm">
                                    <!--<img src="/assets/images/logo-sm.png" alt="" height="22">-->
                                     <svg id="Layer_1" class="h-100 w-md float-end" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 160 70"><title>Header-Logo</title>
                                     <image width="120" height="70" href="{{ $generalUser->company_logo }}"></image>

                                </span>
                                <span class="logo-lg">
                                    <!--<img src="/assets/images/logo-light.png" alt="" height="17">-->
                                     <svg id="Layer_1" class="h-100 w-md float-end" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 160 70"><title>Header-Logo</title>
                                     <image width="120" height="70" href="{{ $generalUser->company_logo }}"></image>

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

                        <!-- App Search    اسم الشركة-->

                        
                        <div class="dropdown topbar-head-dropdown ms-1 header-item kt_topbar_notifications_3"
                            id="notificationDropdown">
                            <button type="button" title="الإشعارات"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                id="page-header-notifications-dropdown" data-bs-toggle="dropdown"
                                ondblclick="location.href='inboxAdmin'" data-bs-auto-close="outside"
                                aria-haspopup="true" aria-expanded="false">
                                <i class='bx bx-bell fs-22'></i>
                                <span
                                    class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger inbox_trans_counter">0<span
                                        class="visually-hidden">unread messages</span></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                aria-labelledby="page-header-notifications-dropdown">

                                <div class="dropdown-head bg-secondary bg-pattern rounded-top">
                                    <div class="p-3">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h6 class="m-0 fs-16 fw-semibold text-white"> Notifications </h6>
                                            </div>
                                            <div class="col-auto dropdown-tabs">
                                                <span class="badge badge-soft-light fs-13 ">New <span
                                                        class="inbox_trans_counter"> 0 </span></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-2 pt-2">
                                        <ul class="nav nav-tabs dropdown-tabs nav-tabs-custom"
                                            data-dropdown-tabs="true" id="notificationItemsTab" role="tablist">
                                            <li class="nav-item waves-effect waves-light">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#all-noti-tab"
                                                    role="tab" aria-selected="true">
                                                    All(<span class="inbox_trans_counter"> 0 </span>)
                                                </a>
                                            </li>

                                        </ul>
                                    </div>

                                </div>

                                <div class="tab-content position-relative" id="notificationItemsTabContent">

                                    <div data-simplebar style="max-height: 300px;"
                                        class="pe-2 kt_topbar_notifications_33">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown topbar-head-dropdown ms-1 header-item">
                            <button type="button" title="المخازن"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class='bx bx-store fs-22'></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">
                                <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0 fw-semibold fs-15"> Quick Access </h6>
                                        </div>
                                        <div class="col-auto">
                                            <a href="supplier" class="btn btn-sm btn-soft-info"> View All Supplier
                                                <i class="ri-arrow-right-s-line align-middle"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-2">
                                    <div class="row g-0">

                                        <div class="col">
                                            <a class="dropdown-icon-item" href="#!">
                                                <img src="/assets/images/brands/github.png" alt="Github">
                                                <span>GitHub</span>
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a class="dropdown-icon-item" href="#!">
                                                <img src="/assets/images/brands/bitbucket.png" alt="bitbucket">
                                                <span>Bitbucket</span>
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a class="dropdown-icon-item" href="#!">
                                                <img src="/assets/images/brands/dribbble.png" alt="dribbble">
                                                <span>Dribbble</span>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="row g-0">
                                        <div class="col">
                                            <a class="dropdown-icon-item" href="#!">
                                                <img src="/assets/images/brands/dropbox.png" alt="dropbox">
                                                <span>Dropbox</span>
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a class="dropdown-icon-item" href="#!">
                                                <img src="/assets/images/brands/mail_chimp.png" alt="mail_chimp">
                                                <span>Mail Chimp</span>
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a class="dropdown-icon-item" href="#!">
                                                <img src="/assets/images/brands/slack.png" alt="slack">
                                                <span>Slack</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown  topbar-head-dropdown header-item">
                            <button type="button" title="بحــث"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="bx bx-search fs-22"></i>
                            </button>
                        </div>  

                        {{-- 88558 --}}

                    </div>
                    <div class="dropdown-menu-end dropdown-menu-lg"
                                aria-labelledby="page-header-search-dropdown">
                                <form action="#" id="searchAllFrom" class="">
                                    <div class="form-group m-0">
                                        <div class="flex-column input-group">
                                            <div class="d-flex">
                                                <input type="text" id="searchalllabl" class="form-control fs-13 h-auto rounded-0"
                                                    placeholder="ابحث هنا ..." aria-label="Recipient's username">
                                                <button class="btn btn-soft-secondary rounded-0" type="submit"><i
                                                        class="mdi mdi-magnify"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                    <div class="d-flex align-items-center">



                        <!--<div class="dropdown ms-1 topbar-head-dropdown header-item">-->
                        <!--    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"-->
                        <!--    data-bs-toggle="modal" data-bs-target="#searchAllModal" >-->
                        <!--        <i class="bx bx-search fs-22"></i>-->
                        <!--    </button>-->

                        <!--</div>-->


                        <div class="dropdown topbar-head-dropdown ms-1 header-item d-none">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                id="page-header-cart-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                aria-haspopup="true" aria-expanded="false">
                                <i class='bx bx-shopping-bag fs-22'></i>
                                <span
                                    class="position-absolute topbar-badge cartitem-badge fs-10 translate-middle badge rounded-pill bg-info"
                                    id="cardCounterlbl">0</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end p-0 dropdown-menu-cart"
                                aria-labelledby="page-header-cart-dropdown">
                                <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0 fs-16 fw-semibold"> My Cart</h6>
                                        </div>
                                        <div class="col-auto">
                                            <span class="badge badge-soft-info fs-13"><span class="cartitem-badge"
                                                    id="cardCounterlbl2">0</span>
                                                items</span>
                                        </div>
                                    </div>
                                </div>
                                <div data-simplebar style="max-height: 300px;">
                                    <div class="p-2" id="headerCardCnt">
                                        <div class="text-center empty-cart" id="empty-cart">
                                            <div class="avatar-md mx-auto my-3">
                                                <div class="avatar-title bg-soft-info text-info fs-36 rounded-circle">
                                                    <i class='bx bx-cart'></i>
                                                </div>
                                            </div>
                                            <h5 class="mb-3">Your Cart is Empty!</h5>
                                            <a href="apps-ecommerce-products.html"
                                                class="btn btn-success w-md mb-3">Shop Now</a>
                                        </div>

                                    </div>
                                </div>
                                <div class="p-3 border-bottom-0 border-start-0 border-end-0 border-dashed border"
                                    id="checkout-elem">
                                    <div class="d-flex justify-content-between align-items-center pb-3">
                                        <h5 class="m-0 text-muted">Total:</h5>
                                        <div class="px-2">
                                            <h5 class="m-0" id="cart-item-totals">000.00</h5>
                                        </div>
                                    </div>
                                    <form action="checkout" method="POST">
                                        <input type="hidden" name="inv_data" id="inv_data">
                                        <input type="hidden" name="inv_price" id="inv_price">
                                        @csrf
                                        <button id="checkoutBtn" class="btn btn-success text-center w-100">
                                            Checkout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                      
                    <!-- <?php
                    $user = auth()->user();
                    $role = $user->roles()->first();
                    $permissions = $role ? $role->permissions : collect();
                    
                    // var_dump($user->username);
                    // var_dump($role ? $role->name : 'No role assigned');
                    // var_dump($permissions->pluck('name'));
                    // var_dump($user->getRoleNames());
                    ?> -->
                        <div class="d-flex align-items-center">
                            <div class="ms-1 header-item d-none d-sm-flex">
                                <button type="button" title="تكبير الشاشة"
                                    class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                    data-toggle="fullscreen">
                                    <i class='bx bx-fullscreen fs-22'></i>
                                </button>
                            </div>

                            <div class="ms-1 header-item d-none d-sm-flex">
                                <button type="button" title="نهاري/ليلي"
                                    class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                                    <i class='bx bx-moon fs-22'></i>
                                </button>
                            </div>
                        </div>

                        <div class="dropdown header-item ms-sm-3 nav-link">
                            <div type="button" class="btn" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    @if (Auth::user()->profile_img)
                                        <img class="rounded-circle header-profile-user"
                                            src="{{ asset('users_images/' . Auth::user()->profile_img) }}"
                                            alt="Header Avatar">
                                    @else
                                        <img src="{{ asset('users_images/default.png') }}" alt="user" style="width: 20px ; height:20px" />
                                    @endif

                                    <span class="text-start ms-xl-2">
                                        <span
                                            class="d-none d-xl-inline-block ms-1 fw-semibold user-name-text">{{ Auth::user()->username }}</span>

                                        <span
                                            class="ms-1 fs-12 text-muted user-name-sub-text d-none">{{ count(Auth::user()->getRoleNames()) > 0 ? Auth::user()->getRoleNames()[0] : '' }}</span>
                                    </span>
                                    <i class="fs-18 m-1 ri-arrow-down-s-line" style="color: #29badb;"></i>
                                </span>
                            </div>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <h6 class="dropdown-header">Welcome .....!</h6>
                                <a class="dropdown-item" href="{{ route('users.edit', auth()->user()->id) }}"><i
                                        class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                        class="align-middle">Profile</span></a>

                                <div class="dropdown-divider"></div>

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
                <a href="#" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ $generalUser->company_logo }}" alt="" height="19">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ $generalUser->company_logo }}" alt="" height="50">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="#" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ $generalUser->company_logo }} alt="" height="19">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ $generalUser->company_logo }}" alt="" height="50">
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
                    <ul class="navbar-nav justify-content-around" id="navbar-nav">
                    
                        @hasanyrole('super admin|admin')
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse"
                                    role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                    <i class="ri-home-4-line"></i> <span data-key="t-dashboards">الرئيسيـــة</span>
                                </a>
                                <div class=" collapse menu-dropdown rounded-0" id="sidebarDashboards">

                                    <ul class="nav nav-sm flex-column">


                                        <li class="nav-item" id="homeli">
                                            <a href="/home" class="nav-link" data-key="t-analytics"> Home </a>
                                        </li>



                                        <li class="nav-item ">
                                            <a href="/get_stores_money_view" class="nav-link" data-key="t-crm">
                                                Stores_money </a>
                                        </li>

                                        <!--xx****-->
                                        <li class="nav-item " style="right: 0% !important">
                                            <a class="nav-link menu-link" href="#sidebarhr" data-bs-toggle="collapse"
                                                role="button" aria-expanded="false" aria-controls="sidebarhr">
                                                <i class="mdi mdi-seal"></i> <span data-key="t-layouts">HR</span>

                                            </a>
                                            <div class="collapse menu-dropdown" id="sidebarhr"
                                                style="right: 100% !important">
                                                <ul class="nav nav-sm flex-column sidebaremployeeul">

                                                    @can('employees')
                                                        <li class="nav-item">
                                                            <a href="/employee" class="nav-link"
                                                                data-key="t-horizontal">الموظفين</a>
                                                        </li>
                                                    @endcan



                                                </ul>

                                            </div>
                                        </li>
                                        <li class="nav-item">
                                            <a href="shop" class="nav-link" data-key="t-ecommerce"> Ecommerce </a>
                                        </li>
                                        <!--<li class="nav-item">-->
                                        <!--    <a href="#" class="nav-link" data-key="t-projects"> Projects </a>-->
                                        <!--</li>-->



                                        <li class="nav-item">
                                            <a href="#" class="nav-link" data-key="t-pos"> POS </a>
                                        </li>

                                    </ul>

                                </div>
                            </li> <!-- end Dashboard Menu -->
                        @endhasanyrole

                        @hasanyrole('super admin|admin|operation')
                            <li class="nav-item partli">
                                <a class="nav-link menu-link" href="#sidebarAppsPart" data-bs-toggle="collapse"
                                    role="button" aria-expanded="false" aria-controls="sidebarAppsPart">
                                    <i class="bx bx-category"></i> <span data-key="t-apps">الصنف</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarAppsPart">

                                    <ul class="nav nav-sm flex-column">
                                        @can('add_new_part')
                                            <li class="nav-item" id="partli">
                                                <a href="/parts" class="nav-link" data-key="t-calendar">إضافة 
                                                    صنف جديد</a>
                                            </li>
                                        @endcan
                                        @can('search_part')
                                            <li class="nav-item" id="">
                                                <a href="/customSearch" class="nav-link" data-key="t-calendar">بحــث 
                                                    الأصناف</a>
                                            </li>
                                            <li class="nav-item" id="">
                                                <a href="/newproducts" class="nav-link" data-key="t-calendar">الأصناف الجدبدة</a>
                                            </li>
                                        @endcan




                                    </ul>

                                </div>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link menu-link" href="#sidebarAppsKit" data-bs-toggle="collapse"
                                    role="button" aria-expanded="false" aria-controls="sidebarAppsKit">
                                    <i class="ri-collage-line"></i> <span data-key="t-apps"> الكيـــــــت</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarAppsKit">

                                    <ul class="nav nav-sm flex-column">
                                        @can('add_new_kit')
                                            <li class="nav-item" id="kitli">
                                                <a href="/kits" class="nav-link" data-key="t-chat"> إضافة كيـــــــــت</a>
                                            </li>
                                        @endcan
                                        @can('collect_kit')
                                            <li class="nav-item" id="kitcollectionli">
                                                <a href="/kitsCollection" class="nav-link" data-key="t-chat1"> تجميع كيت </a>
                                            </li>
                                        @endcan

                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item ">
                                <a class="nav-link menu-link" href="#sidebarAppsWheel" data-bs-toggle="collapse"
                                    role="button" aria-expanded="false" aria-controls="sidebarAppsWheel">
                                    <i class="mdi mdi-tire"></i> <span data-key="t-apps"> الكــــــاوتش</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarAppsWheel">

                                    <ul class="nav nav-sm flex-column">
                                        @can('add_new_wheel')
                                            <li class="nav-item" id="wheelli">
                                                <a href="/wheels" class="nav-link" data-key="t-chat"> إضافة كــاويـتــش </a>
                                            </li>
                                             <li class="nav-item" id="wheelli">
                                                <a href="/dimensions" class="nav-link" data-key="t-chat"> مقاسات الكاوتش  </a>
                                            </li>
                                        @endcan

                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link menu-link" href="#sidebarAppsEquip" data-bs-toggle="collapse"
                                    role="button" aria-expanded="false" aria-controls="sidebarAppsEquip">
                                    <i class="mdi mdi-tractor"></i> <span data-key="t-apps"> المعــــــدات</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarAppsEquip">

                                    <ul class="nav nav-sm flex-column">
                                        @can('add_new_clark')
                                            <li class="nav-item" id="clarckli">
                                                <a href="/clarks" class="nav-link" data-key="t-mailbox">إضافة كـــلارك
                                                </a>
                                            </li>
                                        @endcan
                                        @can('add_new_tractor')
                                            <li class="nav-item" id="tractorli">
                                                <a href="/tractors" class="nav-link" data-key="t-mailbox"> إضافة جــــرار
                                                </a>
                                            </li>
                                        @endcan
                                        @can('add_new_equip')
                                            <li class="nav-item" id="equipli">
                                                <a href="/equips" class="nav-link" data-key="t-mailbox">إضافة معــــدة
                                                </a>
                                            </li>
                                        @endcan

                                    </ul>
                                </div>
                            </li>
                        @endhasanyrole
                        @hasanyrole('super admin|admin|stuff|accountant')
                            @can('stores')
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="#sidebarLayouts" data-bs-toggle="collapse"
                                        role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                                        <i class="mdi mdi-store"></i> <span data-key="t-layouts">المخــــــــــــازن</span>

                                    </a>
                                    <div class="collapse menu-dropdown" id="sidebarLayouts">
                                        <ul class="nav nav-sm flex-column">
                                            @can('stores')
                                                <li class="nav-item">
                                                    <a href="/stores" class="nav-link"
                                                        data-key="t-horizontal">المخــــــــــــازن</a>
                                                </li>
                                            @endcan

                                            @can('enter_to_store')
                                                <li class="nav-item">
                                                    <a href="/all_buy_invs" class="nav-link" data-key="t-horizontal">حركات
                                                        المخــازن</a>
                                                </li>
                                            @endcan
                                            @can('store_sections')
                                                <li class="nav-item">
                                                    <a href="/section" class="nav-link" data-key="t-horizontal">
                                                        أقسام المخازن</a>
                                                </li>
                                            @endcan


                                            @can('stores_process')
                                                <li class="nav-item">
                                                    <a href="#sidebarstoregard" class="nav-link" data-bs-toggle="collapse"
                                                        role="button" aria-expanded="false" aria-controls="sidebarstoregard"
                                                        data-key="t-signin"> جرد
                                                    </a>
                                                    <div class="collapse menu-dropdown" id="sidebarstoregard">
                                                        <ul class="nav nav-sm flex-column" id="sidebarstoregardul">


                                                        </ul>
                                                    </div>
                                                </li>
                                            @endcan
                                            @can('stores_process_edit')
                                                <li class="nav-item">
                                                    <a href="/inventory" class="nav-link" data-key="t-horizontal">
                                                        جرد عام </a>
                                                </li>

                                            @endcan

                                        </ul>

                                    </div>
                                </li> <!-- end Dashboard Menu -->
                            @endcan


                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#sidebarbills" data-bs-toggle="collapse"
                                    role="button" aria-expanded="false" aria-controls="sidebarbills">
                                    <i class="mdi mdi-receipt"></i> <span data-key="t-layouts">الفواتيــر</span>

                                </a>
                                <div class="collapse menu-dropdown" id="sidebarbills">
                                    <ul class="nav nav-sm flex-column">

                                        @can('buy_bills')
                                            <li class="nav-item">
                                                <a href="#sidebarbuybill" class="nav-link" data-bs-toggle="collapse"
                                                    role="button" aria-expanded="false" aria-controls="sidebarbuybill"
                                                    data-key="t-signin"> فواتيــر الشـراء
                                                </a>
                                                <div class="collapse menu-dropdown" id="sidebarbuybill">
                                                    <ul class="nav nav-sm flex-column">
                                                        @can('buy_bills')
                                                            <li class="nav-item">
                                                                <a href="/storeManage" class="nav-link"><span
                                                                        data-key="t-term-conditions"> فاتورة شراء </span>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('buy_bills')
                                                            <li class="nav-item">
                                                                <a href="/refundCover" class="nav-link"><span
                                                                        data-key="t-term-conditions"> إسترجاع فاتورة شراء
                                                                    </span></a>
                                                            </li>
                                                        @endcan


                                                    </ul>
                                                </div>
                                            </li>
                                        @endcan
                                        @can('sales_bills')
                                            <li class="nav-item">
                                                <a href="#sidebarsellbill" class="nav-link" data-bs-toggle="collapse"
                                                    role="button" aria-expanded="false" aria-controls="sidebarsellbill"
                                                    data-key="t-signin"> فواتيــر البيع
                                                </a>
                                                <div class="collapse menu-dropdown" id="sidebarsellbill">
                                                    <ul class="nav nav-sm flex-column">
                                                        @can('add_sales_bill')
                                                            <li class="nav-item">
                                                                <a href="/sellInvoices" class="nav-link"><span
                                                                        data-key="t-term-conditions"> فواتير بيع </span> </a>
                                                            </li>
                                                        @endcan
                                                        @can('refund_sales_bill')
                                                            <li class="nav-item">
                                                                <a href="/refundCover" class="nav-link"><span
                                                                        data-key="t-term-conditions"> إسترجاع فاتورة بيع
                                                                    </span></a>
                                                            </li>
                                                        @endcan



                                                    </ul>
                                                </div>
                                            </li>
                                        @endcan


                                        @can('ard_asar')
                                            <li class="nav-item">
                                                <a href="/asar" target="_self" class="nav-link" data-key="t-hovered">عرض
                                                    أسعار</a>
                                            </li>
                                        @endcan
                                        @can('new_pricing_page')
                                            <li class="nav-item ">
                                                <a href="/simplePriceList" target="_self" class="nav-link"
                                                    data-key="t-hovered">التسـعيـــــــرة</a>
                                            </li>
                                        @endcan
                                        @can('pricing_all')
                                            <li class="nav-item">
                                                <a href="/pricingAll" target="_self" class="nav-link"
                                                    data-key="t-hovered">تسعيير الكل</a>
                                            </li>
                                        @endcan
                                        @can('deliver_eqiup')
                                            <li class="nav-item">
                                                <a href="/equipPrepare" target="_self" class="nav-link"
                                                    data-key="t-hovered"> تسليم معدة</a>
                                            </li>
                                        @endcan
                                        @can('taxes_bills')
                                            <li class="nav-item">
                                                <a href="/taxReport" target="_self" class="nav-link"
                                                    data-key="t-hovered">الفواتير الضريبية</a>
                                            </li>
                                        @endcan
                                        
                                            <li class="nav-item">
                                                <a href="/coast" target="_self" class="nav-link"
                                                    data-key="t-hovered">إضافة التكاليف</a>
                                            </li>
                                       

                                    </ul>

                                </div>
                            </li> <!-- end Dashboard Menu -->

                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#sidebarClient" class="nav-link"
                                    data-bs-toggle="collapse" role="button" aria-expanded="false"
                                    aria-controls="sidebarClient" data-key="t-password-create"> <i
                                        class="ri-customer-service-fill"></i> <span data-key="t-pages"> العملاء</span>

                                </a>
                                <div class="collapse menu-dropdown " id="sidebarClient">
                                    <ul class="nav nav-sm flex-column ">
                                        @can('add_client')
                                            <li class="nav-item">
                                                <a href="/client" class="nav-link"><span data-key="t-term-conditions"> إضافة
                                                        عميل </span></a>

                                            </li>
                                        @endcan
                                        @can('client_account_statement')
                                            <li class="nav-item">
                                                <a href="/clientStatment" class="nav-link" data-key="t-basic">
                                                    كشف حساب عميل </a>
                                            </li>
                                        @endcan
                                        @can('client_paid')
                                            <li class="nav-item">
                                                <a href="/clientview" class="nav-link" data-key="t-basic">
                                                    سداد العملاء </a>
                                            </li>
                                        @endcan

                                    </ul>
                                </div>
                            </li>



                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#sidebarsupplier" class="nav-link"
                                    data-bs-toggle="collapse" role="button" aria-expanded="false"
                                    aria-controls="sidebarsupplier" data-key="t-signup"> <i class="mdi mdi-flag"></i>
                                    <span data-key="t-pages"> المورديــــيــــــــــــــــن</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarsupplier">
                                    <ul class="nav nav-sm flex-column">
                                        @can('add_suppliers')
                                            <li class="nav-item">
                                                <a href="/supplierindex" class="nav-link" data-key="t-timeline"> إضافة
                                                    مـــورد </a>
                                            </li>
                                        @endcan

                                        @can('suppliers_numbers')
                                            <li class="nav-item">
                                                <a href="/partNumbers" class="nav-link" data-key="t-basic"> أرقام المورديين
                                                </a>
                                            </li>
                                        @endcan
                                        @can('supplier_account_statement')
                                            <li class="nav-item">
                                                <a href="/supplierStatment" class="nav-link" data-key="t-basic"> كشف حساب
                                                    مورد
                                                </a>
                                            </li>
                                        @endcan
                                        @can('suppliers_paid')
                                            <li class="nav-item">
                                                <a href="/supplierView" class="nav-link" data-key="t-cover">
                                                    سداد الموردين </a>
                                            </li>
                                        @endcan

                                    </ul>
                                </div>
                            </li>
                            @hasanyrole('super admin|admin|accountant')
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="#sidebaraccountant" data-bs-toggle="collapse"
                                        role="button" aria-expanded="false" aria-controls="sidebaraccountant">
                                        <i class="mdi mdi-currency-usd"></i> <span data-key="t-layouts">الحسابــــــات</span>

                                    </a>
                                    <div class="collapse menu-dropdown" id="sidebaraccountant">
                                        <ul class="nav nav-sm flex-column">

                                            <li class="nav-item">
                                                    <a href="#sidebarSignIn112" class="nav-link" data-bs-toggle="collapse"
                                                        role="button" aria-expanded="false" aria-controls="sidebarSignIn112"
                                                        data-key="t-signin">  الحسابات الجديدة
                                                    </a>
                                                    <div class="collapse menu-dropdown" id="sidebarSignIn112">
                                                        <ul class="nav nav-sm flex-column">
                                                            <li class="nav-item">
                                                                <a href="/coa" class="nav-link" data-key="t-basic">
                                                                    الدليل الحسابي </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="/journal" class="nav-link"
                                                                    data-key="t-basic">Journal  </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="/allQayds" class="nav-link" data-key="t-basic">القيود </a>
                                                            </li>
                                                             <li class="nav-item">
                                                                <a href="/GeneralLedger" class="nav-link" data-key="t-basic">General Ledger </a>
                                                            </li>
                                                             <li class="nav-item">
                                                                <a href="/BalanceSheet" class="nav-link" data-key="t-basic">Balance Sheet </a>
                                                            </li>
                                                            
                                                            <!--<li class="nav-item">-->
                                                            <!--    <a href="/qayd/searchaccount" class="nav-link"-->
                                                            <!--        data-key="t-basic">كشف حساب </a>-->
                                                            <!--</li>-->
                                                            <!--<li class="nav-item">-->
                                                            <!--    <a href="/motagra" class="nav-link" data-key="t-basic">-->
                                                            <!--        المتاجرة </a>-->
                                                            <!--</li>-->
                                                            <!--<li class="nav-item">-->
                                                            <!--    <a href="/incomeStatement" class="nav-link"-->
                                                            <!--        data-key="t-basic">قائمة الدخــــل</a>-->
                                                            <!--</li>-->
                                                            <!--<li class="nav-item">-->
                                                            <!--    <a href="#" class="nav-link" data-key="t-basic">-->
                                                            <!--        المركز المالي </a>-->
                                                            <!--</li>-->

                                                        </ul>
                                                    </div>
                                                </li>
                                            @can('accounting_tree')
                                                <li class="nav-item">
                                                    <a href="/branch" class="nav-link" data-key="t-horizontal"> الشجرة المحاسبية
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('accounting_entries')
                                                <li class="nav-item">
                                                    <a href="/qayd" class="nav-link" data-key="t-horizontal"> القيود </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="/inv_qayd" class="nav-link" data-key="t-horizontal"> عرض الفواتير
                                                        وتعديل القيود </a>
                                                </li>
                                            @endcan
                                            @can('accounting_reports')
                                                <li class="nav-item">
                                                    <a href="#sidebarSignIn11" class="nav-link" data-bs-toggle="collapse"
                                                        role="button" aria-expanded="false" aria-controls="sidebarSignIn11"
                                                        data-key="t-signin"> التقارير المحاسبية
                                                    </a>
                                                    <div class="collapse menu-dropdown" id="sidebarSignIn11">
                                                        <ul class="nav nav-sm flex-column">
                                                            <li class="nav-item">
                                                                <a href="/trialBalance" class="nav-link" data-key="t-basic">ميزان
                                                                    المراجعة </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="/qayd/trialbalance" class="nav-link"
                                                                    data-key="t-basic">ميزان المراجعة بالأرصدة</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="/qayd/search" class="nav-link" data-key="t-basic">بحث
                                                                    بالحساب / القيود </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="/qayd/searchaccount" class="nav-link"
                                                                    data-key="t-basic">كشف حساب </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="/motagra" class="nav-link" data-key="t-basic">
                                                                    المتاجرة </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="/incomeStatement" class="nav-link"
                                                                    data-key="t-basic">قائمة الدخــــل</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="#" class="nav-link" data-key="t-basic">
                                                                    المركز المالي </a>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </li>
                                            @endcan



                                        </ul>
                                        

                                    </div>
                                </li> <!-- end Dashboard Menu -->
                            @endhasanyrole
                            @can('company')
                                <!--xx****-->
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="#sidebarcompany" data-bs-toggle="collapse"
                                        role="button" aria-expanded="false" aria-controls="sidebarcompany">
                                        <i class="mdi mdi-seal"></i> <span data-key="t-layouts">الشركة</span>

                                    </a>
                                    <div class="collapse menu-dropdown" id="sidebarcompany">
                                        <ul class="nav nav-sm flex-column">
                                            @can('company_safe')
                                                <li class="nav-item">
                                                    <a href="/get_safe_store/8" class="nav-link" data-key="t-horizontal">خزينة
                                                        الشركة</a>
                                                </li>
                                            @endcan
                                            @can('account bank')
                                                <li class="nav-item">
                                                    <a href="/banksafeMoney" class="nav-link" data-key="t-horizontal">حساب البنك
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('add_account_bank')
                                                <li class="nav-item">
                                                    <a href="/bank_type" class="nav-link" data-key="t-horizontal"> إضافة حساب
                                                        بنكى </a>
                                                </li>
                                            @endcan

                                            @can('employees')
                                                <li class="nav-item">
                                                    <a href="/employee" class="nav-link" data-key="t-horizontal">الموظفين</a>
                                                </li>
                                            @endcan
                                            @can('company_employess_salary')
                                                <li class="nav-item">
                                                    <a href="/get_store_employee_salary/8" class="nav-link"
                                                        data-key="t-horizontal">مرتبات موظفين الشركة</a>
                                                </li>
                                            @endcan


                                        </ul>

                                    </div>
                                </li> <!-- end Dashboard Menu -->
                            @endcan
                            @can('employees')
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="#sidebaremployee" data-bs-toggle="collapse"
                                        role="button" aria-expanded="false" aria-controls="sidebaremployee">
                                        <i class="bx bx-user"></i> <span data-key="t-layouts">الموظفيين</span>

                                    </a>

                                    <div class="collapse menu-dropdown" id="sidebaremployee">
                                        <ul class="nav nav-sm flex-column sidebaremployeeul" id="sidebaremployeeul">
                                            @can('employees')
                                                <li class="nav-item">
                                                    <a href="/employee" class="nav-link" data-key="t-horizontal">الموظفين</a>
                                                </li>
                                            @endcan

                                        </ul>

                                    </div>
                                </li> <!-- end Dashboard Menu -->
                            @endcan

                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#sidebarservice" class="nav-link"
                                    data-bs-toggle="collapse" role="button" aria-expanded="false"
                                    aria-controls="sidebarservice" data-key="t-signup"> <i class="bx bx-wrench"></i>
                                    <span data-key="t-pages"> الخدمات</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarservice">
                                    <ul class="nav nav-sm flex-column">
                                        @can('new_service_bills')
                                            <li class="nav-item">
                                                <a href="/services/8" class="nav-link"><span data-key="t-term-conditions">
                                                        فاتورة خدمة جديدة </span></a>
                                            </li>
                                        @endcan
                                        @can('new_service_bills')
                                            <li class="nav-item">
                                                <a href="/tawreedServices" class="nav-link"><span
                                                        data-key="t-term-conditions">
                                                        طلب توريد أصناف </span></a>
                                            </li>
                                        @endcan
                                        @can('service_bill')
                                            <li class="nav-item">
                                                <a href="/serviceInvoice" class="nav-link"><span
                                                        data-key="t-term-conditions"> الفواتير </span></a>
                                            </li>
                                        @endcan
                                        @can('services')
                                            <li class="nav-item">
                                                <a href="/service" class="nav-link"><span data-key="t-term-conditions">
                                                        خدماتنا </span></a>
                                            </li>
                                        @endcan

                                    </ul>
                                </div>
                            </li>
                        @endhasanyrole
                        @hasanyrole('super admin|admin|stuff')
                            <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages"> البيانات</span>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#sidebarcalender" class="nav-link"
                                    data-bs-toggle="collapse" role="button" aria-expanded="false"
                                    aria-controls="sidebarcalender" data-key="t-signup"> <i class="bx bx-calendar"></i>
                                    <span data-key="t-pages"> الأجندة</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarcalender">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="/calender" target="_self" class="nav-link"
                                                data-key="t-hovered">الأجندة</a>
                                        </li>



                                    </ul>
                                </div>
                            </li>



                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#sidebarmessage" class="nav-link"
                                    data-bs-toggle="collapse" role="button" aria-expanded="false"
                                    aria-controls="sidebarmessage" data-key="t-signup"> <i class="bx bx-message"></i>
                                    <span data-key="t-pages"> الرسائل</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarmessage">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="/inboxAdmin" target="_self" class="nav-link"
                                                data-key="t-hovered">الرسائــــــل</a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="/audit_page" target="_self" class="nav-link"
                                                data-key="t-hovered">مراقبة النظام</a>
                                        </li>

                                    </ul>
                                </div>
                            </li>



                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#sidebarAuth" data-bs-toggle="collapse"
                                    role="button" aria-expanded="false" aria-controls="sidebarAuth">
                                    <i class="ri-printer-line"></i> <span data-key="t-authentication">التقاريــــر</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarAuth">

                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="#sidebarsalereport" class="nav-link" data-bs-toggle="collapse"
                                                role="button" aria-expanded="false" aria-controls="sidebarsalereport"
                                                data-key="t-salereport"> المبيعات
                                            </a>
                                            <div class="collapse menu-dropdown" id="sidebarsalereport">
                                                <ul class="nav nav-sm flex-column">

                                                    <li class="nav-item">
                                                        <a href="/sales_report" class="nav-link" data-key="t-basic">
                                                            حركة المبيعات </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>

                                        <li class="nav-item">
                                            <a href="#sidebarSignIn" class="nav-link" data-bs-toggle="collapse"
                                                role="button" aria-expanded="false" aria-controls="sidebarSignIn"
                                                data-key="t-signin"> قطــع الغيــــــار
                                            </a>
                                            <div class="collapse menu-dropdown" id="sidebarSignIn">
                                                <ul class="nav nav-sm flex-column">
                                                    <li class="nav-item">
                                                        <a href="/customSearch" class="nav-link" data-key="t-basic">
                                                            بحــــــــــــــــــث </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="auth-signin-cover.html" class="nav-link"
                                                            data-key="t-cover"> أرقام الصنف </a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a href="/defectsItems" class="nav-link" data-key="t-basic">
                                                            النواقــــــــــــص </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="/ItemLive" class="nav-link" data-key="t-basic">
                                                            دليل الصنف </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="/allpartReport" class="nav-link" data-key="t-basic">
                                                            الكــــــــل </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#sidebarSignUp" class="nav-link" data-bs-toggle="collapse"
                                                role="button" aria-expanded="false" aria-controls="sidebarSignUp"
                                                data-key="t-signup"> المورديــــيــــــــــــــــن
                                            </a>
                                            <div class="collapse menu-dropdown" id="sidebarSignUp">
                                                <ul class="nav nav-sm flex-column">
                                                    <li class="nav-item">
                                                        <a href="auth-signup-basic.html" class="nav-link"
                                                            data-key="t-basic"> كشف المورد لفترة </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="auth-signup-cover.html" class="nav-link"
                                                            data-key="t-cover"> كشف حساب مورد </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>

                                        <li class="nav-item">
                                            <a href="#sidebarResetPass" class="nav-link" data-bs-toggle="collapse"
                                                role="button" aria-expanded="false" aria-controls="sidebarResetPass"
                                                data-key="t-password-reset"> الشحـــــــــن
                                            </a>
                                            <div class="collapse menu-dropdown" id="sidebarResetPass">
                                                <ul class="nav nav-sm flex-column">
                                                    <li class="nav-item">
                                                        <a href="auth-pass-reset-basic.html" class="nav-link"
                                                            data-key="t-basic"> حساب الحاوية </a>
                                                    </li>
                                                    <li class="nav-item d-none">
                                                        <a href="auth-pass-reset-cover.html" class="nav-link"
                                                            data-key="t-cover"> Cover </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>

                                        <li class="nav-item">
                                            <a href="#sidebarchangePass" class="nav-link" data-bs-toggle="collapse"
                                                role="button" aria-expanded="false" aria-controls="sidebarchangePass"
                                                data-key="t-password-create">
                                                العملاء
                                            </a>
                                            <div class="collapse menu-dropdown" id="sidebarchangePass">
                                                <ul class="nav nav-sm flex-column">
                                                    <li class="nav-item">
                                                        <a href="/clientStatment" class="nav-link" data-key="t-basic">
                                                            كشف حساب عميل </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="auth-pass-change-basic.html" class="nav-link"
                                                            data-key="t-basic">
                                                            مراجعة فاتورة </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="auth-pass-change-cover.html" class="nav-link"
                                                            data-key="t-cover">
                                                            إسترجــــــاع </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>

                                        <li class="nav-item d-none">
                                            <a href="#sidebarLockScreen" class="nav-link" data-bs-toggle="collapse"
                                                role="button" aria-expanded="false" aria-controls="sidebarLockScreen"
                                                data-key="t-lock-screen"> المخــــــازن
                                            </a>
                                            <div class="collapse menu-dropdown" id="sidebarLockScreen">
                                                <ul class="nav nav-sm flex-column">
                                                    <li class="nav-item">
                                                        <a href="auth-lockscreen-basic.html" class="nav-link"
                                                            data-key="t-basic"> حركات المخــــازن </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="auth-lockscreen-cover.html" class="nav-link"
                                                            data-key="t-cover"> جرد المخـــــازن </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>

                                    </ul>


                                </div>
                            </li>
                        @endhasanyrole
                        @hasanyrole('super admin|admin|operation')
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#sidebarPages" data-bs-toggle="collapse"
                                    role="button" aria-expanded="false" aria-controls="sidebarPages">
                                    <i class="ri-database-2-line"></i> <span data-key="t-pages">قواعد البيانات</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarPages">

                                    <ul class="nav nav-sm flex-column">
                                        <!------------------------------------------>
                                        <li class="nav-item">
                                            <a href="/company" class="nav-link" data-key="t-team"> الشركات
                                            </a>

                                        </li>
                                        <li class="nav-item">
                                            <a href="#sidebarhr" class="nav-link" data-bs-toggle="collapse"
                                                role="button" aria-expanded="false" aria-controls="sidebarhr"
                                                data-key="t-signin"> المستخدمين
                                            </a>
                                            <div class="collapse menu-dropdown" id="sidebarhr">
                                                <ul class="nav nav-sm flex-column">



                                                    <li class="nav-item">
                                                        <a href="/users" class="nav-link"><span
                                                                data-key="t-term-conditions">المستخدمين </span> </a>

                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="/user_role" class="nav-link"><span
                                                                data-key="t-term-conditions"> وظائف المستحدم </span></a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a href="/role" class="nav-link"><span
                                                                data-key="t-term-conditions"> الصلاحيات </span></a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="/role_perm" class="nav-link"><span
                                                                data-key="t-term-conditions"> صلاحيات المستخدم
                                                            </span></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <!----------------------------------->

                                        <!------------------------------------------>
                                        <li class="nav-item">
                                            <a href="#sidebarMoney" class="nav-link" data-bs-toggle="collapse"
                                                role="button" aria-expanded="false" aria-controls="sidebarMoney"
                                                data-key="t-signin"> المعاملات المالية
                                            </a>
                                            <div class="collapse menu-dropdown" id="sidebarMoney">
                                                <ul class="nav nav-sm flex-column">
                                                    <li class="nav-item">
                                                        <a href="/pricing_type" class="nav-link"><span
                                                                data-key="t-term-conditions"> أنواع التسعيير </span> </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="/taxes" class="nav-link"><span
                                                                data-key="t-term-conditions"> الضرائب </span></a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a href="/currency" class="nav-link" data-key="t-maintenance">
                                                            العملات </a>
                                                    </li>
                                                    {{-- <li class="nav-item">
                                                        <a href="/unit" class="nav-link" data-key="t-maintenance">
                                                            Measure Units </a>
                                                    </li> --}}

                                                </ul>
                                            </div>
                                        </li>
                                        <!----------------------------------->


                                        <!------------------------------------------>
                                        <li class="nav-item">
                                            <a href="#sidebarClass" class="nav-link" data-bs-toggle="collapse"
                                                role="button" aria-expanded="false" aria-controls="sidebarClass"
                                                data-key="t-signin"> تصنييف الصنف
                                            </a>
                                            <div class="collapse menu-dropdown" id="sidebarClass">
                                                <ul class="nav nav-sm flex-column">
                                                    <li class="nav-item">
                                                        <a href="/groups" class="nav-link" data-key="t-faqs">
                                                            المجموعات
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="/sub_group" class="nav-link"
                                                            data-key="t-pricing">المجمزعات الفرعية
                                                        </a>
                                                    </li>



                                                    <li class="nav-item">
                                                        <a href="/brand" class="nav-link"><span
                                                                data-key="t-privacy-policy"> البراند </span></a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="/brand_type" class="nav-link"><span
                                                                data-key="t-privacy-policy"> النوع </span> </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="/model" class="nav-link"><span
                                                                data-key="t-privacy-policy"> الموديلات </span> </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="/series" class="nav-link"><span
                                                                data-key="t-term-conditions"> الفئات </span> </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <!----------------------------------->

                                        <!------------------------------------------>
                                        <li class="nav-item">
                                            <a href="#sidebarDesc" class="nav-link" data-bs-toggle="collapse"
                                                role="button" aria-expanded="false" aria-controls="sidebarDesc"
                                                data-key="t-signin"> التوصيف
                                            </a>
                                            <div class="collapse menu-dropdown" id="sidebarDesc">
                                                <ul class="nav nav-sm flex-column">
                                                    <li class="nav-item">
                                                        <a href="/drive" class="nav-link"><span
                                                                data-key="t-term-conditions"><span
                                                                    data-key="t-term-conditions"> Drive </span> </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="/all_source" class="nav-link"><span
                                                                data-key="t-term-conditions"> Source </span></a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="/all_status" class="nav-link"
                                                            data-key="t-gallery"><span data-key="t-term-conditions">
                                                                Status </span> </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="/part_quality" class="nav-link"><span
                                                                data-key="t-term-conditions"> Part Quality </span>
                                                            </span></a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </li>
                                        <!----------------------------------->






                                    </ul>

                                </div>
                            </li>
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
        <div class="layout-width">
            @yield('content')
        </div>





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
        <div class="btn-success btn-rounded shadow-lg btn btn-icon btn-lg p-2" data-bs-toggle="offcanvas"
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
                                    <img src="/assets/images/sidebar/img-1.jpg" alt=""
                                        class="avatar-md w-auto object-cover">
                                </label>
                            </div>

                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidebar-image"
                                    id="sidebarimg-02" value="img-2">
                                <label class="form-check-label p-0 avatar-sm h-auto" for="sidebarimg-02">
                                    <img src="/assets/images/sidebar/img-2.jpg" alt=""
                                        class="avatar-md w-auto object-cover">
                                </label>
                            </div>
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidebar-image"
                                    id="sidebarimg-03" value="img-3">
                                <label class="form-check-label p-0 avatar-sm h-auto" for="sidebarimg-03">
                                    <img src="/assets/images/sidebar/img-3.jpg" alt=""
                                        class="avatar-md w-auto object-cover">
                                </label>
                            </div>
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidebar-image"
                                    id="sidebarimg-04" value="img-4">
                                <label class="form-check-label p-0 avatar-sm h-auto" for="sidebarimg-04">
                                    <img src="/assets/images/sidebar/img-4.jpg" alt=""
                                        class="avatar-md w-auto object-cover">
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
                        <div id="productCarousel" class="carousel slide d-inline-block w-50"
                            data-bs-ride="carousel">
                            <div class="carousel-inner" id="item-image-car">

                            </div>
                            <a class="carousel-control-prev " href="#productCarousel" role="button"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon text-bg-dark" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </a>
                            <a class="carousel-control-next " href="#productCarousel" role="button"
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


    <div class="modal fade" id="searchAllModal" tabindex="-1" aria-labelledby="searchAllModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="wrap" style="direction: ltr">
                        <div class="search">
                            <!--<form action="#" id="searchAllFrom" class="d-inline-flex w-100">-->
                            <!--    <input type="text"  class="searchTerm" id="searchalllabl" placeholder="What are you looking for?">-->
                            <!--    <button type="submit" class="searchButton">-->
                            <!--        <i class="bx bx-search"></i>-->
                            <!--    </button>-->
                            <!--</form>-->

                        </div>
                    </div>
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
        window.onload = function() {
            document.getElementById('customizerclose-btn').click()
        }
        // document.getElementById('customizerclose-btn').click()
    </script>

    <script>
        function playAudio() {
            var x = new Audio('assets/noti.mp3');
            // Show loading animation.
            var playPromise = x.play();

            if (playPromise !== undefined) {
                playPromise.then(_ => {
                        x.play();
                    })
                    .catch(error => {});

            }
        }

        function draw_notification(data_arr, class_to_fill) {


            $(class_to_fill).empty();
            var data_html = [];
            for (let i = 0; i < data_arr.length; i++) {
                $(class_to_fill).prepend(`
                <div class="text-reset notification-item d-block dropdown-item position-relative">
                                                <div class="d-flex">
                                                    <div class="avatar-xs me-3">
                                                        <span
                                                            class="avatar-title bg-soft-info text-info rounded-circle fs-16">
                                                            <i class="bx bx-badge-check"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-1">
                                                        <a href="#!" class="stretched-link">
                                                            <h6 class="mt-0 mb-2 lh-base">${data_arr[i].amount}
                                                            </h6>
                                                        </a>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                            <span><i class="mdi mdi-clock-outline"></i> ${data_arr[i]['store_action'].name}(${data_arr[i]['store'].name})</span>
                                                        </p>
                                                    </div>
                                                    <div class="px-2 fs-15">
                                                        <div class="form-check notification-check">
                                                          <h3>تاكيد</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>




            `);




            }
            $(class_to_fill).append(` <div class="my-3 text-center view-all">
                                                <a href="/inboxAdmin"
                                                    class="btn btn-soft-success waves-effect waves-light">View
                                                    All Notifications <i
                                                        class="ri-arrow-right-line align-middle"></i></a>
                                            </div>`)

        }
    </script>

    <script src="{{ URL::asset('asetNew/js/jquery-3.5.1.js') }}"></script>
    <script src="{{ URL::asset('js/timer.js') }}"></script>
    <script src="{{ URL::asset('asetNew/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('asetNew/js/dataTables.responsive.js') }}"></script>
    <script src="{{ URL::asset('asetNew/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('asetNew/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('asetNew/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('asetNew/js/pdfmake.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

    <script src="{{ URL::asset('asetNew/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('asetNew/js/jquery.validate.min.js') }}"></script>


    <script src="https://cdn.jsdelivr.net/npm/ag-grid-enterprise/dist/ag-grid-enterprise.min.js"></script>

    @yield('js')

    <script>
        function CardInfo(allpartId) {
            $("#item-image-car").empty();
            $("#itemNumbers").empty();
            $("#itemSpecs").empty();
            $("#itemModels").empty();
            $("#itemStores").empty();
            $("#itemPriceList").empty();
            $("#relatedpartDiv").empty();
            $("#relatedpartDiv1").empty();
            $("#itemGroup").empty();


            $.ajax({
                type: "GET",
                url: "CardInfo",
                data: {
                    "allpartId": allpartId
                },
                success: function(response) {
                    console.log(response);
                    if (response.allpart.length > 0) {
                        var item = response.allpart[0];
                        var name = item.part.name;
                        var part_images = item.part.part_images;
                        var part_status = item.status.name;
                        var part_source = item.source.name_arabic;
                        var part_quality = item.part_quality.name;
                        var part_price = item.price;
                        var part_stores = item.stores;

                        var itemNumbers = item.part.part_numbers;
                        var itemSpecs = item.part.part_details;
                        var itemModels = item.part.part_models;
                        var itemGroup = item.part.sub_group;
                        var Srelatedpart = item.part.all_parts;
                        var relatedpart = item.part.related_parts;

                        $("#itemName").text(name);
                        if (part_price.length > 0) {
                            $("#itemPrice").text(part_price[0].price);
                        } else {
                            $("#itemPrice").text(0);
                        }

                        $("#itemStock").html(`Out Of Stock <span class="dot float-start"></span>`);

                        if (itemGroup) {
                            $("#itemGroup").text(`${itemGroup.group.name } / ${itemGroup.name } `);
                        }
                        $("#itemDesc").html(`<span>${part_source}</span> / <span>${part_status}</span>`);
                        if (part_quality == 'ORIGINAL') {
                            $("#itemQuality").html(
                                `<img src="assets/part_images/original.png" style="width:100px;height:100px" class="">`
                            );
                        } else {
                            $("#itemQuality").html(
                                `<img src="assets/part_images/high-quality.png" style="width:100px;height:100px" class="">`
                            );
                        }

                        var storeStatus = 0;
                        part_stores.forEach(store => {
                            storeStatus += store.storepartCount;
                        });
                        var addedSource = [];
                        var addedStatus = [];
                        var addedQuality = [];
                        if (relatedpart.length > 0) {
                            relatedpart.forEach(part => {
                                if (part.part.part_images.length > 0) {
                                    $("#relatedpartDiv1").append(`<div class="col text-center" style="max-height: 250px;">
                                        <img src="assets/part_images/${part.part.part_images[0].image_name}"  class="card-img-top" style="max-height: 150px;width:150px" alt="Product Image">
                                        <div class="card-body">
                                            <p class="card-descs text-center">${part.part.name}  </p>
                                        </div>
                                    </div>`);
                                } else {
                                    $("#relatedpartDiv1").append(`<div class="col text-center" style="max-height: 250px;">
                                        <img src="assets/part_images/tractor-solid.png"  class="card-img-top" style="max-height: 150px;width:150px" alt="Product Image">
                                        <div class="card-body">
                                            <p class="card-descs text-center">${part.part.name}  </p>
                                        </div>
                                    </div>`);
                                }

                            })
                        } else {
                            $("#relatedpartDiv1").append('<p class="text-center">No Data To Preview<p>');
                        }
                        if (Srelatedpart.length > 1) {

                            Srelatedpart.forEach(part => {
                                var itPric = 0;
                                if (part.rprice.length > 0) {
                                    itPric = part.rprice[0].price;
                                }
                                if (part.source_id != item.source_id && part.status_id != item
                                    .status_id && part.quality_id != item.quality_id) {
                                    if (jQuery.inArray(part.source_id, addedSource) == -1 && jQuery
                                        .inArray(part.status_id, addedStatus) == -1 && jQuery.inArray(
                                            part.quality_id, addedQuality) == -1) {
                                        if (part_images.length > 0) {
                                            $("#relatedpartDiv").append(`<div class="col text-center" style="max-height: 250px;">

                                                <img src="assets/part_images/${part_images[0].image_name}"  class="card-img-top" style="max-height: 150px;width:150px" alt="Product Image">
                                                <div class="card-body">
                                                    <p class="card-descs text-center">${item.part.name} </p>
                                                    <p class="card-descs text-center">${part.source.name_arabic} / ${part.status.name} / ${part.part_quality.name} </p>
                                                    <p class="card-price">$ ${itPric}</p>

                                                    <a href="#" class="btn btn-primary d-none">Add to Cart</a>
                                                </div>
                                            </div>`);
                                        } else {
                                            $("#relatedpartDiv").append(`<div class="col" style="max-height: 250px;">
                                                <img src="assets/part_images/tractor-solid.png" class="card-img-top" style="max-height: 150px;width:150px;" alt="Product Image">
                                                <div class="card-body">
                                                    <p class="card-descs text-center">${item.part.name} </p>
                                                    <p class="card-descs text-center">${part.source.name_arabic} / ${part.status.name} / ${part.part_quality.name} </p>
                                                    <p class="card-price">$ ${itPric}</p>
                                                    <a href="#" class="btn btn-primary d-none">Add to Cart</a>
                                                </div>
                                            </div>`);
                                        }

                                        addedQuality.push(part.quality_id);
                                        addedSource.push(part.source_id);
                                        addedStatus.push(part.status_id);
                                    }


                                }

                            });
                        } else {
                            $("#relatedpartDiv").append('<p class="text-center">No Data To Preview<p>');
                        }
                        if (storeStatus > 0) {
                            $("#itemStock").html(`Available <span class="dot bg-success  float-start"></span>`);
                        } else {
                            $("#itemStock").html(`Out Of Stock <span class="dot  float-start"></span>`);

                        }
                        if (part_stores.length > 0) {
                            part_stores.forEach(store => {
                                $("#itemStores").append(
                                    `<li class="list-group-item d-flex justify-content-between align-items-center">${store.name}<span class="badge bg-primary rounded-pill">${store.storepartCount}</span></li>`
                                );
                            });

                        }
                        if (part_price.length > 0) {
                            part_price.forEach(price => {
                                $("#itemPriceList").append(
                                    `<li class="list-group-item d-flex justify-content-between align-items-center">${price.sale_type.type}<span class="badge bg-primary rounded-pill">${price.price}</span></li>`
                                );
                            });

                        } else {
                            $("#itemPriceList").append('<li>No Price List</li>');
                        }
                        if (part_images.length > 0) {
                            part_images.forEach(img => {
                                $("#item-image-car").append(`<div class="carousel-item ">
                                        <img src="assets/part_images/${img.image_name}" class="d-block w-100" style="height:250px" alt="Product Image 1">
                                    </div>`);
                            });
                            $($(".carousel-item")[0]).addClass('active');
                        } else {
                            $("#item-image-car").append(`<div class="carousel-item active">
                                <img src="assets/part_images/tractor-solid.png" class="d-block w-100" style="height:150px" alt="Product Image 1">
                            </div>`);
                        }

                        if (itemNumbers.length > 0) {
                            itemNumbers.forEach(num => {
                                $("#itemNumbers").append(
                                    `<li class="list-group-item">${num.number}</li>`);
                            });
                        } else {
                            $("#itemNumbers").append(`<li class="list-group-item">No Numbers</li>`);
                        }


                        if (itemSpecs.length > 0) {
                            itemSpecs.forEach(specs => {
                                $("#itemSpecs").append(
                                    `<li class="list-group-item d-flex justify-content-between align-items-center">${specs.part_spec.name}<span class="badge bg-primary rounded-pill">${specs.value}</span></li>`
                                );
                            });
                        } else {
                            $("#itemSpecs").append(
                                `<li class="list-group-item d-flex justify-content-between align-items-center">No Specs</li>`
                            );
                        }

                        if (itemModels.length > 0) {
                            itemModels.forEach(mdl => {
                                $("#itemModels").append(`<li class="list-group-item ">
                                        <span>${mdl.series.model.brand_type.name}</span> -
                                        <span>${mdl.series.model.brand.name}</span> -
                                        <span>${mdl.series.model.name}</span> -
                                        <span>${mdl.series.name}</span>
                                    </li>`);
                            });
                        } else {
                            $("#itemModels").append(
                                `<li class="list-group-item d-flex justify-content-between align-items-center">No Models</li>`
                            );
                        }

                        $("#infoMdl").modal('show');
                    } else {
                        $("#infoMdl").modal('hide');
                    }
                }
            });
        }

        function PartCardInfo(partId) {
            $("#item-image-car").empty();
            $("#itemNumbers").empty();
            $("#itemSpecs").empty();
            $("#itemModels").empty();
            $("#itemStores").empty();
            $("#itemPriceList").empty();
            $("#relatedpartDiv").empty();
            $("#relatedpartDiv1").empty();
            $("#itemGroup").empty();


            $.ajax({
                type: "GET",
                url: "CardInfo",
                data: {
                    "partId": partId
                },
                success: function(response) {
                    console.log(response);
                    if (response.allpart) {
                        var item = response.allpart;
                        var name = item.part.name;
                        var part_images = item.part.part_images;
                        var part_status = (item.status) ? item.status.name : 'None';
                        var part_source = (item.source) ? item.source.name_arabic : 'None';
                        var part_quality = (item.part_quality) ? item.part_quality.name : 'None';
                        var part_price = item.price;
                        var part_stores = item.stores;

                        var itemNumbers = item.part.part_numbers;
                        var itemSpecs = item.part.part_details;
                        var itemModels = item.part.part_models;
                        var itemGroup = item.part.sub_group;
                        var Srelatedpart = item.part.all_parts;
                        var relatedpart = item.part.related_parts;

                        $("#itemName").text(name);
                        if (part_price.length > 0) {
                            $("#itemPrice").text(part_price[0].price);
                        } else {
                            $("#itemPrice").text(0);
                        }

                        $("#itemStock").html(`Out Of Stock <span class="dot float-start"></span>`);

                        if (itemGroup) {
                            $("#itemGroup").text(`${itemGroup.group.name } / ${itemGroup.name } `);
                        }
                        $("#itemDesc").html(`<span>${part_source}</span> / <span>${part_status}</span>`);
                        if (part_quality == 'ORIGINAL') {
                            $("#itemQuality").html(
                                `<img src="assets/part_images/original.png" style="width:100px;height:100px" class="">`
                            );
                        } else {
                            $("#itemQuality").html(
                                `<img src="assets/part_images/high-quality.png" style="width:100px;height:100px" class="">`
                            );
                        }

                        var storeStatus = 0;
                        part_stores.forEach(store => {
                            storeStatus += store.storepartCount;
                        });
                        var addedSource = [];
                        var addedStatus = [];
                        var addedQuality = [];
                        if (relatedpart.length > 0) {
                            relatedpart.forEach(part => {
                                if (part.part.part_images.length > 0) {
                                    $("#relatedpartDiv1").append(`<div class="col text-center" style="max-height: 250px;">
                                        <img src="assets/part_images/${part.part.part_images[0].image_name}"  class="card-img-top" style="max-height: 150px;width:150px" alt="Product Image">
                                        <div class="card-body">
                                            <p class="card-descs text-center">${part.part.name}  </p>
                                        </div>
                                    </div>`);
                                } else {
                                    $("#relatedpartDiv1").append(`<div class="col text-center" style="max-height: 250px;">
                                        <img src="assets/part_images/tractor-solid.png"  class="card-img-top" style="max-height: 150px;width:150px" alt="Product Image">
                                        <div class="card-body">
                                            <p class="card-descs text-center">${part.part.name}  </p>
                                        </div>
                                    </div>`);
                                }

                            })
                        } else {
                            $("#relatedpartDiv1").append('<p class="text-center">No Data To Preview<p>');
                        }
                        if (Srelatedpart.length > 1) {

                            Srelatedpart.forEach(part => {
                                var itPric = 0;
                                if (part.rprice.length > 0) {
                                    itPric = part.rprice[0].price;
                                }
                                if (part.source_id != item.source_id && part.status_id != item
                                    .status_id && part.quality_id != item.quality_id) {
                                    if (jQuery.inArray(part.source_id, addedSource) == -1 && jQuery
                                        .inArray(part.status_id, addedStatus) == -1 && jQuery.inArray(
                                            part.quality_id, addedQuality) == -1) {
                                        if (part_images.length > 0) {
                                            $("#relatedpartDiv").append(`<div class="col text-center" style="max-height: 250px;">

                                                <img src="assets/part_images/${part_images[0].image_name}"  class="card-img-top" style="max-height: 150px;width:150px" alt="Product Image">
                                                <div class="card-body">
                                                    <p class="card-descs text-center">${item.part.name} </p>
                                                    <p class="card-descs text-center">${part.source.name_arabic} / ${part.status.name} / ${part.part_quality.name} </p>
                                                    <p class="card-price">$ ${itPric}</p>

                                                    <a href="#" class="btn btn-primary d-none">Add to Cart</a>
                                                </div>
                                            </div>`);
                                        } else {
                                            $("#relatedpartDiv").append(`<div class="col" style="max-height: 250px;">
                                                <img src="assets/part_images/tractor-solid.png" class="card-img-top" style="max-height: 150px;width:150px;" alt="Product Image">
                                                <div class="card-body">
                                                    <p class="card-descs text-center">${item.part.name} </p>
                                                    <p class="card-descs text-center">${part.source.name_arabic} / ${part.status.name} / ${part.part_quality.name} </p>
                                                    <p class="card-price">$ ${itPric}</p>
                                                    <a href="#" class="btn btn-primary d-none">Add to Cart</a>
                                                </div>
                                            </div>`);
                                        }

                                        addedQuality.push(part.quality_id);
                                        addedSource.push(part.source_id);
                                        addedStatus.push(part.status_id);
                                    }


                                }

                            });
                        } else {
                            $("#relatedpartDiv").append('<p class="text-center">No Data To Preview<p>');
                        }
                        if (storeStatus > 0) {
                            $("#itemStock").html(`Available <span class="dot bg-success  float-start"></span>`);
                        } else {
                            $("#itemStock").html(`Out Of Stock <span class="dot  float-start"></span>`);

                        }
                        if (part_stores.length > 0) {
                            part_stores.forEach(store => {
                                $("#itemStores").append(
                                    `<li class="list-group-item d-flex justify-content-between align-items-center">${store.name}<span class="badge bg-primary rounded-pill">${store.storepartCount}</span></li>`
                                );
                            });

                        }
                        if (part_price.length > 0) {
                            part_price.forEach(price => {
                                $("#itemPriceList").append(
                                    `<li class="list-group-item d-flex justify-content-between align-items-center">${price.sale_type.type}<span class="badge bg-primary rounded-pill">${price.price}</span></li>`
                                );
                            });

                        } else {
                            $("#itemPriceList").append('<li>No Price List</li>');
                        }
                        if (part_images.length > 0) {
                            part_images.forEach(img => {
                                $("#item-image-car").append(`<div class="carousel-item ">
                                        <img src="assets/part_images/${img.image_name}" class="d-block w-100" style="height:250px" alt="Product Image 1">
                                    </div>`);
                            });
                            $($(".carousel-item")[0]).addClass('active');
                        } else {
                            $("#item-image-car").append(`<div class="carousel-item active">
                                <img src="assets/part_images/tractor-solid.png" class="d-block w-100" style="height:150px" alt="Product Image 1">
                            </div>`);
                        }

                        if (itemNumbers.length > 0) {
                            itemNumbers.forEach(num => {
                                $("#itemNumbers").append(
                                    `<li class="list-group-item">${num.number}</li>`);
                            });
                        } else {
                            $("#itemNumbers").append(`<li class="list-group-item">No Numbers</li>`);
                        }


                        if (itemSpecs.length > 0) {
                            itemSpecs.forEach(specs => {
                                $("#itemSpecs").append(
                                    `<li class="list-group-item d-flex justify-content-between align-items-center">${specs.part_spec.name}<span class="badge bg-primary rounded-pill">${specs.value}</span></li>`
                                );
                            });
                        } else {
                            $("#itemSpecs").append(
                                `<li class="list-group-item d-flex justify-content-between align-items-center">No Specs</li>`
                            );
                        }

                        if (itemModels.length > 0) {
                            itemModels.forEach(mdl => {
                                $("#itemModels").append(`<li class="list-group-item ">
                                        <span>${mdl.series.model.brand_type.name}</span> -
                                        <span>${mdl.series.model.brand.name}</span> -
                                        <span>${mdl.series.model.name}</span> -
                                        <span>${mdl.series.name}</span>
                                    </li>`);
                            });
                        } else {
                            $("#itemModels").append(
                                `<li class="list-group-item d-flex justify-content-between align-items-center">No Models</li>`
                            );
                        }

                        $("#infoMdl").modal('show');
                    } else {
                        $("#infoMdl").modal('hide');
                    }
                }
            });
        }


        $(document).on('click', '.nav-icon', function() {
            $('.nav-icon').removeClass('active');
            $(this).addClass('active');
        });
        $(document).on('click', '.menu-link', function() {
            $('.menu-link').removeClass('active');
            $(this).addClass('active');
        });
    </script>

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

        $(document).ready(function() {

            $.ajax({
                type: "get",
                url: "/all_data_inbox",
                success: function(data_inbox) {

                    if (data_inbox) {
                        $('.inbox_trans_counter').html(data_inbox.length);
                        // $('.kt_topbar_notifications_33').html();
                        // draw_notification(data_inbox)
                    }
                    $('.inbox_trans_counter').html(data_inbox.length);
                    if (data_inbox.length > 0) {
                        playAudio();
                    }
                    // $($("#notificationItemsTabContent").find('.simplebar-content')[0]).empty();
                    draw_notification(data_inbox, $("#notificationItemsTabContent").find(
                        '.simplebar-content')[0])
                },
            });


            $.ajax({
                type: "get",
                url: "/GetAllstores",
                success: function(data) {
                    data.forEach(store => {
                        if (store.id != 7) {
                            $('.sidebaremployeeul').append(`
                                        <li class="nav-item">
                                            <a href="#sidebaremployeestore${store.id}" class="nav-link" data-bs-toggle="collapse"
                                                role="button" aria-expanded="false" aria-controls="sidebaremployeestore${store.id}"
                                                data-key="t-signin">  ${ store.name }
                                            </a>
                                            <div class="collapse menu-dropdown" id="sidebaremployeestore${store.id}">
                                                <ul class="nav nav-sm flex-column">

                                                    <li class="nav-item">
                                                        <a href="/get_store_employee_salary/${store.id}" class="nav-link" data-key="t-horizontal">المرتبات  </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="/get_store_employee_salary/${store.id}" class="nav-link" data-key="t-horizontal">  السلف</a>
                                                    </li>


                                                </ul>
                                            </div>
                                        </li>

                        `);
                            var xx = "";

                            $('#sidebarstoregardul').append(`

                                                    <li class="nav-item">

                                                        <a href="/gard/${store.id}" class="nav-link"><span
                                                                data-key="t-term-conditions">   ${store.name}
                                                            </span></a>
                                                    </li>

                        `);




                        }


                    });
                }
            });


            $('.select2').on('change', function() {
                if ($(this)[0].name == 'supplierdrp' || $(this)[0].name == 'clientdrp') {

                } else {
                    $(this).valid();
                }

            });

            // $("#gyr_ind").select2();

            // var validator = $(".needs-validation").validate();


            $("#searchAllFrom").submit(function(e) {
                e.preventDefault();
                // var searckey = $("#searchalllabl").val();
                var searckey = encodeURIComponent($("#searchalllabl").val());
                // alert(searckey);
                // encodeURIComponent(myString);
                location.href = '/searchAll?q=' + searckey;
            })

        });

        // New Added Script
        document.addEventListener('DOMContentLoaded', function () {
            let table = new DataTable('#scroll-horizontal', {
                "scrollX": true
                });
            });
            
        function fetchNotifications() {
            fetch('/notifications')
                .then(response => response.json())
                .then(data => {
                    console.log('New notifications:', data);
                    // Handle displaying the notifications
                    displayNotifications(data);
                })
                .catch(error => console.error('Error fetching notifications:', error));
        }
        
        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/read`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Notification marked as read:', data);
            })
            .catch(error => console.error('Error marking notification as read:', error));
        }
        
        function displayNotifications(notifications) {
            const notificationContainer = document.getElementById('notification-container');
            notificationContainer.innerHTML = '';  // Clear previous notifications
        
            notifications.forEach(notification => {
                const notificationElement = document.createElement('div');
                notificationElement.classList.add('notification');
                notificationElement.textContent = notification.data.message;
                
                // Mark the notification as read when clicked
                notificationElement.addEventListener('click', () => {
                    markAsRead(notification.id);
                    notificationElement.classList.add('read');
                    notificationElement.style.display = 'none';
                });
        
                notificationContainer.appendChild(notificationElement);
            
                //  setTimeout(() => {
                //     notificationElement.style.display = 'none';  // Hide the notification
                // }, 10000);  // 10 seconds (10000 milliseconds)
                
            });
            
            
            
            
        }



        // Poll every 30 seconds (adjust the interval as needed)
        setInterval(fetchNotifications, 30000);
        
         function addToTalabea(type_id,part_id,source_id,status_id,quality_id,amount){
            $.ajax({
                type: "GET",
                url: "addtoTalabea",
                data: {
                    "type_id": type_id,
                    "part_id": part_id,
                    "source_id": source_id,
                    "status_id": status_id,
                    "quality_id": quality_id,
                    "amount": amount,
                },
                success: function(response) {
                    console.log(response);
                }
            })
        }
    </script>
</body>

</html>
