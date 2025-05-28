<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="EMARA Template">
    <meta name="keywords" content="EMARA, MMG, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>EMARA | SHOP</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="assets/ecomerce/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="assets/ecomerce/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="assets/ecomerce/css/elegant-icons.css" type="text/css">
    {{-- <link rel="stylesheet" href="assets/ecomerce/css/nice-select.css" type="text/css"> --}}
    <link rel="stylesheet" href="assets/ecomerce/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="assets/ecomerce/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="assets/ecomerce/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="assets/ecomerce/css/style.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- <style>
        .left-modal .modal-dialog {
            position: fixed;
            margin: auto;
            width: 300px;
            /* Adjust the width as needed */
            height: 100%;
            left: 0;
            top: 0;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }

        .left-modal.show .modal-dialog {
            transform: translateX(0);
        }

        .select2-container--default .select2-results__option {
            text-align: center;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #007bff;
            color: white;
        }
    </style> --}}
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .select2-results__option--highlighted[aria-selected] {
            background-color: #447001;
            /* Change background color on hover */
            color: #ffffff;
            /* Change text color to white on hover */
            /* Change text color on hover */
        }

        /* Additional styles for better visibility */
        .select2-results__option {
            padding: 8px;
            /* Adjust padding for options */
            transition: background-color 0.3s, color 0.3s;
            /* Smooth transition */
        }

        .modal.left .modal-dialog {
            position: fixed;
            margin: auto;
            width: 300px;
            height: 100%;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }

        .modal.left .modal-dialog.show {
            transform: translateX(0);
        }

        .modal.left .modal-content {
            height: 100%;
            border: 0;
            border-radius: 0;
            background-color: #fff;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background-color: #f7f7f7;
            color: #333;
            border-bottom: 1px solid #e5e5e5;
        }

        .modal-body {
            padding: 20px;
        }

        .btn-primary {
            background-color: #ff9900;
            border-color: #ff9900;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #cc7a00;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .filter-button {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1050;
        }

        .modal-header .close {
            color: #333;
            opacity: 1;
        }
    </style>
</head>

</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Humberger Begin -->
    <div class="humberger__menu__overlay"></div>
    <div class="humberger__menu__wrapper">
        <div class="humberger__menu__logo">
            {{-- <a href="#"><img src="assets/ecomerce/img/logo.png" alt=""></a> --}}
            <h3>Emara</h3>
        </div>
        <div class="humberger__menu__cart">
            <ul>
                <li><a href="#"><i class="fa fa-heart"></i> <span>0</span></a></li>
                <li><a href="#"><i class="fa fa-shopping-bag"></i> <span>0</span></a></li>
            </ul>
            <div class="header__cart__price">item: <span>$000.00</span></div>
        </div>
        <div class="humberger__menu__widget">
            <div class="header__top__right__language">
                <img src="assets/ecomerce/img/language.png" alt="">
                <div>English</div>
                <span class="arrow_carrot-down"></span>
                <ul>
                    <li><a href="#">Spanis</a></li>
                    <li><a href="#">English</a></li>
                </ul>
            </div>
            <div class="header__top__right__auth">
                <a href="#"><i class="fa fa-user"></i> Login</a>
            </div>
        </div>
        <nav class="humberger__menu__nav mobile-menu">
            <ul>
                <li><a href="pos">Home</a></li>
                <li class="active"><a href="shop">Shop</a></li>
                <li><a href="#">Pages</a>
                    <ul class="header__menu__dropdown">
                        <li><a href="./shop-details.html">Shop Details</a></li>
                        <li><a href="./shoping-cart.html">Shoping Cart</a></li>
                        <li><a href="./checkout.html">Check Out</a></li>
                        <li><a href="./blog-details.html">Blog Details</a></li>
                    </ul>
                </li>
                <li><a href="./blog.html">Blog</a></li>
                <li><a href="./contact.html">Contact</a></li>
            </ul>
        </nav>
        <div id="mobile-menu-wrap"></div>
        <div class="header__top__right__social">
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-linkedin"></i></a>
            <a href="#"><i class="fa fa-pinterest-p"></i></a>
        </div>
        <div class="humberger__menu__contact">
            <ul>
                <li><i class="fa fa-envelope"></i> emara@emara.com</li>
                {{-- <li>Free Shipping for all Order of $99</li> --}}
            </ul>
        </div>
    </div>
    <!-- Humberger End -->

    <!-- Header Section Begin -->
    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__left">
                            <ul>
                                <li><i class="fa fa-envelope"></i> emara@emara.com</li>
                                {{-- <li>Free Shipping for all Order of $99</li> --}}
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__right">
                            <div class="header__top__right__social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-linkedin"></i></a>
                                <a href="#"><i class="fa fa-pinterest-p"></i></a>
                            </div>
                            <div class="header__top__right__language">
                                <img src="assets/ecomerce/img/language.png" alt="">
                                <div>English</div>
                                <span class="arrow_carrot-down"></span>
                                <ul>
                                    <li><a href="#">Spanis</a></li>
                                    <li><a href="#">English</a></li>
                                </ul>
                            </div>
                            <div class="header__top__right__auth">
                                <a href="#"><i class="fa fa-user"></i> Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="header__logo">
                        {{-- <a href="./index.html"><img src="assets/ecomerce/img/logo.png" alt=""></a> --}}
                        <h3>EMARA</h3>
                    </div>
                </div>
                <div class="col-lg-6">
                    <nav class="header__menu">
                        <ul>
                            <li><a href="pos">Home</a></li>
                            <li class="active"><a href="shop">Shop</a></li>
                            <li><a href="#">Pages</a>
                                <ul class="header__menu__dropdown">
                                    <li><a href="./shop-details.html">Shop Details</a></li>
                                    <li><a href="./shoping-cart.html">Shoping Cart</a></li>
                                    <li><a href="./checkout.html">Check Out</a></li>
                                    <li><a href="./blog-details.html">Blog Details</a></li>
                                </ul>
                            </li>
                            <li><a href="./blog.html">Blog</a></li>
                            <li><a href="./contact.html">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="header__cart">
                        <ul>
                            <li><a href="#"><i class="fa fa-heart"></i> <span>0</span></a></li>
                            <li><a href="#"><i class="fa fa-shopping-bag"></i> <span>0</span></a></li>
                        </ul>
                        <div class="header__cart__price">item: <span>$000.00</span></div>
                    </div>
                </div>
            </div>
            <div class="humberger__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>
    <!-- Header Section End -->

    <!-- Hero Section Begin -->
    <section class="hero">
        <div class="container">





            <div class="row">
                <div class="col-lg-1">
                    <button class="btn btn-primary" id="filterButton">
                        <i class="fa fa-filter" aria-hidden="true"></i> Filters
                    </button>
                    {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        Filter<i class="fa fa-filter"></i>
                    </button> --}}

                </div>

                <div class="col-lg-9">
                    <div class="hero__search">
                        <div class="hero__search__form">
                            <form id="price-filter-form" method="GET" action="{{ route('shop') }}">
                                <input type="hidden" id="input_brand" value="" name="filter[brand]" />
                                <input type="hidden" id="input_group" value="" name="filter[group]" />
                                <input type="hidden" id="input_supplier" value="" name="filter[supplier]" />

                                {{-- <input type="hidden" id="input_type" value="" name="filter[part_type]" /> --}}
                                <input type="hidden" id="input_model" value="" name="filter[model]" />
                                <input type="hidden" id="input_series" value="" name="filter[series]" />
                                <input type="hidden" id="input_subgroup" value="" name="filter[sub_group]" />




                                <div class="hero__search__categories">
                                    All
                                    <span class="arrow_carrot-down"></span>
                                </div>
                                <input type="text" placeholder="بحث بالاسم ؟" name="filter[name]"
                                    value="{{ request()->input('filter.name') }}">

                                <button type="submit" class="site-btn">SEARCH</button>
                        </div>
                        <div class="hero__search__phone">
                            <div class="hero__search__phone__icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="hero__search__phone__text">
                                <h5>+02 100 732 3654</h5>
                                <span>support 24/7 time</span>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="hero__item set-bg" data-setbg="assets/ecomerce/img/banner/banner01.jpg">
                        <div class="hero__text">
                            <span class="text-warning">AGRE Spare Part</span>
                            <h2 class="text-white">Parts <br />100% Original</h2>
                            <p>Free Pickup and Delivery Available</p>
                            <a href="#" class="primary-btn">SHOP NOW</a>
                        </div>
                    </div> --}}
                </div>

            </div>
            <div class="row">
                <div class="row col-lg-7" id="row_filters">


                </div>
                <div clas="col-lg-5">
                    <button type="button" id="refresh_filter" class="btn text-white  btn-sm"
                        style="background-color: #7fad39;"> <i class="fa fa-refresh" aria-hidden="true"></i>
                    </button>

                </div>


            </div>



        </div>
    </section>

    <!-- Hero Section End -->

    <!-- Breadcrumb Section Begin -->
    {{-- <section class="breadcrumb-section set-bg pt-0" data-setbg="assets/ecomerce/img/breadcrumb01.jpg" >
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Parts Shop</h2>
                        <div class="breadcrumb__option">
                            <a href="pos">Home</a>
                            <span>Shop</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <!-- Breadcrumb Section End -->

    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-5">
                    <div class="sidebar">
                        <div class="sidebar__item">
                            <h4>Department</h4>
                            <ul>
                                <li><a href="#">Parts</a></li>
                                <li><a href="#">Wheel</a></li>
                                <li><a href="#">Tractor</a></li>
                                <li><a href="#">KITS</a></li>
                                <li><a href="#">Clark</a></li>
                                <li><a href="#">Equipment</a></li>
                            </ul>
                        </div>
                        <div class="sidebar__item">
                            <h4>Price</h4>
                            <div class="price-range-wrap">
                                <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                                    data-min="10" data-max="540">
                                    <div class="ui-slider-range ui-corner-all ui-widget-header"></div>
                                    <span tabindex="0"
                                        class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                    <span tabindex="0"
                                        class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                </div>
                                <div class="range-slider">
                                    <div class="price-input " style="width:300px;">
                                        <input type="text" id="minamount" name="filter[min_price]"
                                            value={{ $max_price }} readonly>
                                        <input type="text" id="maxamount" name="filter[max_price]"
                                            value={{ $min_price }} readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sidebar__item sidebar__item__color--option">
                            <h4>Colors</h4>
                            <div class="sidebar__item__color sidebar__item__color--white">
                                <label for="white">
                                    White
                                    <input type="radio" id="white">
                                </label>
                            </div>
                            <div class="sidebar__item__color sidebar__item__color--gray">
                                <label for="gray">
                                    Gray
                                    <input type="radio" id="gray">
                                </label>
                            </div>
                            <div class="sidebar__item__color sidebar__item__color--red">
                                <label for="red">
                                    Red
                                    <input type="radio" id="red">
                                </label>
                            </div>
                            <div class="sidebar__item__color sidebar__item__color--black">
                                <label for="black">
                                    Black
                                    <input type="radio" id="black">
                                </label>
                            </div>
                            <div class="sidebar__item__color sidebar__item__color--blue">
                                <label for="blue">
                                    Blue
                                    <input type="radio" id="blue">
                                </label>
                            </div>
                            <div class="sidebar__item__color sidebar__item__color--green">
                                <label for="green">
                                    Green
                                    <input type="radio" id="green">
                                </label>
                            </div>
                        </div>
                        <div class="sidebar__item">
                            <h4>Popular Size</h4>
                            <div class="sidebar__item__size">
                                <label for="large">
                                    Large
                                    <input type="radio" id="large">
                                </label>
                            </div>
                            <div class="sidebar__item__size">
                                <label for="medium">
                                    Medium
                                    <input type="radio" id="medium">
                                </label>
                            </div>
                            <div class="sidebar__item__size">
                                <label for="small">
                                    Small
                                    <input type="radio" id="small">
                                </label>
                            </div>
                            <div class="sidebar__item__size">
                                <label for="tiny">
                                    Tiny
                                    <input type="radio" id="tiny">
                                </label>
                            </div>
                        </div>
                        {{-- <div class="sidebar__item">
                            <div class="latest-product__text">
                                <h4>Latest Products</h4>
                                <div class="latest-product__slider owl-carousel">
                                    <div class="latest-prdouct__slider__item">
                                        <a href="#" class="latest-product__item">
                                            <div class="latest-product__item__pic">
                                                <img src="assets/ecomerce/img/latest-product/lp-1.jpg" alt="">
                                            </div>
                                            <div class="latest-product__item__text">
                                                <h6>Crab Pool Security</h6>
                                                <span>$30.00</span>
                                            </div>
                                        </a>
                                        <a href="#" class="latest-product__item">
                                            <div class="latest-product__item__pic">
                                                <img src="assets/ecomerce/img/latest-product/lp-2.jpg" alt="">
                                            </div>
                                            <div class="latest-product__item__text">
                                                <h6>Crab Pool Security</h6>
                                                <span>$30.00</span>
                                            </div>
                                        </a>
                                        <a href="#" class="latest-product__item">
                                            <div class="latest-product__item__pic">
                                                <img src="assets/ecomerce/img/latest-product/lp-3.jpg" alt="">
                                            </div>
                                            <div class="latest-product__item__text">
                                                <h6>Crab Pool Security</h6>
                                                <span>$30.00</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="latest-prdouct__slider__item">
                                        <a href="#" class="latest-product__item">
                                            <div class="latest-product__item__pic">
                                                <img src="assets/ecomerce/img/latest-product/lp-1.jpg" alt="">
                                            </div>
                                            <div class="latest-product__item__text">
                                                <h6>Crab Pool Security</h6>
                                                <span>$30.00</span>
                                            </div>
                                        </a>
                                        <a href="#" class="latest-product__item">
                                            <div class="latest-product__item__pic">
                                                <img src="assets/ecomerce/img/latest-product/lp-2.jpg" alt="">
                                            </div>
                                            <div class="latest-product__item__text">
                                                <h6>Crab Pool Security</h6>
                                                <span>$30.00</span>
                                            </div>
                                        </a>
                                        <a href="#" class="latest-product__item">
                                            <div class="latest-product__item__pic">
                                                <img src="assets/ecomerce/img/latest-product/lp-3.jpg" alt="">
                                            </div>
                                            <div class="latest-product__item__text">
                                                <h6>Crab Pool Security</h6>
                                                <span>$30.00</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="col-lg-9 col-md-7">
                    <div class="filter__item">
                        <div class="row">
                            <div class="col-lg-4 col-md-5">
                                <div class="filter__sort">
                                    <span>Sort By</span>
                                    <select id="part_sort_select" name="sort">
                                        <option value="" {{ request()->input('sort') == '' ? 'selected' : '' }}>
                                            default</option>

                                        <option value="-price"
                                            {{ request()->input('sort') == '-price' ? 'selected' : '' }}> price big
                                        </option>
                                        <option value="price"
                                            {{ request()->input('sort') == 'price' ? 'selected' : '' }}>price small
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="filter__found">
                                    <h6><span>
                                            {{ $pagedItems->appends(request()->query())->total('pagination::bootstrap-5') }}
                                        </span> Products found</h6>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-3">
                                <div class="filter__option">
                                    <span class="icon_grid-2x2"></span>
                                    <span class="icon_ul"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if (count($pagedItems) > 0)
                            @foreach ($pagedItems as $item)
                                @if (isset($item->part))
                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                        <div class="product__item">

                                            <div class="product__item__pic set-bg"
                                                data-setbg="{{ count($item->part->part_images) > 0 ? URL('assets/part_images/' . str_replace(' ', '%20', $item->part->part_images[0]->image_name)) : URL('assets/part_images/default.png') }}"
                                                style="background-size: contain;">
                                                <ul class="product__item__pic__hover">
                                                    <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                                </ul>
                                            </div>



                                            <div class="product__item__text">
                                                <h6><a href="#">{{ $item->part->name }}</a>
                                                </h6>
                                                 <p>
                                                    {{ $item->source->name_arabic }}
                                                    -
                                                    {{ $item->status->name }}
                                                    -
                                                    {{ $item->part_quality->name }}
                                                </p>
                                                <h6>السعر:<span class="font-weight-bold">{{ isset($item->max_pricing) ? $item->max_pricing->price : ' لم يتم التسعير ' }}</span>
                                                </h6>

                                               <h6>المتاح:<span class="font-weight-bold">{{ isset($item->amount) ? $item->amount : 0 }}</span>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                @elseif(isset($item->kit))
                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                        <div class="product__item">

                                            <div class="product__item__pic set-bg"
                                            
                                                data-setbg="{{ count($item->kit->part_images) > 0 ? URL('assets/kit_images/' . str_replace(' ', '%20', $item->kit->part_images[0]->image_url)) : URL('assets/kit_images/default.png') }}" style="background-size: contain;">
                                                <ul class="product__item__pic__hover">
                                                    <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                                </ul>
                                            </div>



                                            <div class="product__item__text">
                                                <h6><a href="#">{{ $item->kit->name }}</a>
                                                </h6>
                                                <p>
                                                    {{ $item->source->name_arabic }}
                                                    -
                                                    {{ $item->status->name }}
                                                    -
                                                    {{ $item->part_quality->name }}
                                                </p>
                                             <h6>السعر:<span class="font-weight-bold">{{ isset($item->max_pricing) ? $item->max_pricing->price : ' لم يتم التسعير ' }}</span>
                                                </h6>

                                                <h6>المتاح:<span class="font-weight-bold">{{ isset($item->amount) ? $item->amount : 0 }}</span>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                @elseif(isset($item->wheel))
                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                        <div class="product__item">

                                            <div class="product__item__pic set-bg"
                                            
                                                data-setbg="{{ count($item->wheel->wheel_images) > 0 ? URL('assets/wheel_images/' . str_replace(' ', '%20', $item->wheel->wheel_images[0]->image_name)) : URL('assets/wheel_images/default.png') }}" style="background-size: contain;">
                                                <ul class="product__item__pic__hover">
                                                    <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                                </ul>
                                            </div>



                                            <div class="product__item__text">
                                                <h6><a href="#">{{ $item->wheel->name }}</a>
                                                </h6>
                                                <p>
                                                    {{ $item->source->name_arabic }}
                                                    -
                                                    {{ $item->status->name }}
                                                    -
                                                    {{ $item->part_quality->name }}
                                                </p>
                                                <h6>السعر:<span class="font-weight-bold">{{ isset($item->max_pricing) ? $item->max_pricing->price : ' لم يتم التسعير ' }}</span>
                                                </h6>

                                                <h6>المتاح:<span class="font-weight-bold">{{ isset($item->amount) ? $item->amount : 0 }}</span>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                @elseif(isset($item->clark))
                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                        <div class="product__item">
                                            

                                            <div class="product__item__pic set-bg"
                                                data-setbg="{{ count($item->clark->clark_images) > 0 ? URL('assets/clark_images/' . str_replace(' ', '%20', $item->clark->clark_images[0]->image_name)) : URL('assets/clark_images/default.png') }}" style="background-size: contain;">
                                                <ul class="product__item__pic__hover">
                                                    <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                                </ul>
                                            </div>



                                            <div class="product__item__text">
                                                <h6><a href="#">{{ $item->clark->name }}</a>
                                                </h6>
                                                <p>
                                                    {{ $item->source->name_arabic }}
                                                    -
                                                    {{ $item->status->name }}
                                                    -
                                                    {{ $item->part_quality->name }}
                                                </p>
                                                <h6>السعر:<span class="font-weight-bold">{{ isset($item->max_pricing) ? $item->max_pricing->price : ' لم يتم التسعير ' }}</span>
                                                </h6>
                                                <h6>المتاح:<span class="font-weight-bold">{{ isset($item->amount) ? $item->amount : 0 }}</span>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                @elseif(isset($item->tractor))
                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                        <div class="product__item">

                                            <div class="product__item__pic set-bg"
                                            
                                                data-setbg="{{ count($item->tractor->tractor_images) > 0 ? URL('assets/tractor_images/' . str_replace(' ', '%20', $item->tractor->tractor_images[0]->url)) : URL('assets/tractor_images/default.png') }}" style="background-size: contain;">
                                                <ul class="product__item__pic__hover">
                                                    <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                                </ul>
                                            </div>



                                            <div class="product__item__text">
                                                <h6><a href="#">{{ $item->tractor->name }}</a>
                                                </h6>
                                                <p>
                                                    {{ $item->source->name_arabic }}
                                                    -
                                                    {{ $item->status->name }}
                                                    -
                                                    {{ $item->part_quality->name }}
                                                </p>
                                                <h6>السعر:<span class="font-weight-bold">{{ isset($item->max_pricing) ? $item->max_pricing->price : ' لم يتم التسعير ' }}</span>
                                                </h6>
                                                <h6>المتاح:<span class="font-weight-bold">{{ isset($item->amount) ? $item->amount : 0 }}</span>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                @elseif(isset($item->equip))
                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                        <div class="product__item">

                                            <div class="product__item__pic set-bg"
                                            
                                                data-setbg="{{ count($item->equip->equip_images) > 0 ? URL('assets/equip_images/' . str_replace(' ', '%20', $item->equip->equip_images[0]->image_name)) : URL('assets/equip_images/default.png') }}" style="background-size: contain;">
                                                <ul class="product__item__pic__hover">
                                                    <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                                </ul>
                                            </div>



                                            <div class="product__item__text">
                                                <h6><a href="#">{{ $item->equip->name }}</a>
                                                </h6>
                                                 <p>
                                                    {{ $item->source->name_arabic }}
                                                    -
                                                    {{ $item->status->name }}
                                                    -
                                                    {{ $item->part_quality->name }}
                                                </p>
                                                <h6>السعر:<span class="font-weight-bold">{{ isset($item->max_pricing) ? $item->max_pricing->price : ' لم يتم التسعير ' }}</span>
                                                </h6>

                                                 <h6>المتاح:<span class="font-weight-bold">{{ isset($item->amount) ? $item->amount : 0 }}</span>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="media-body">
                                <h4 class="text-center">No Products Found..</h4>

                            </div>



                        @endif

                    </div>
                    {{ $pagedItems->appends(request()->query())->links('pagination::bootstrap-5') }}


                    {{-- <div class="product__pagination">
                        <a href="#">1</a>
                        <a href="#">2</a>
                        <a href="#">3</a>
                        <a href="#"><i class="fa fa-long-arrow-right"></i></a>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>
    </form>

    <!-- Product Section End -->

    <!-- Footer Section Begin -->
    <footer class="footer spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <div class="footer__about__logo">
                            <a href="./index.html"><img src="assets/ecomerce/img/logo.png" alt=""></a>
                        </div>
                        <ul>
                            <li>Address: 60-49 Road 11378 New York</li>
                            <li>Phone: +65 11.188.888</li>
                            <li>Email: hello@colorlib.com</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 offset-lg-1">
                    <div class="footer__widget">
                        <h6>Useful Links</h6>
                        <ul>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">About Our Shop</a></li>
                            <li><a href="#">Secure Shopping</a></li>
                            <li><a href="#">Delivery infomation</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Our Sitemap</a></li>
                        </ul>
                        <ul>
                            <li><a href="#">Who We Are</a></li>
                            <li><a href="#">Our Services</a></li>
                            <li><a href="#">Projects</a></li>
                            <li><a href="#">Contact</a></li>
                            <li><a href="#">Innovation</a></li>
                            <li><a href="#">Testimonials</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="footer__widget">
                        <h6>Join Our Newsletter Now</h6>
                        <p>Get E-mail updates about our latest shop and special offers.</p>
                        {{-- <form action="#">
                            <input type="text" placeholder="Enter your mail">
                            <button type="submit" class="site-btn">Subscribe</button>
                        </form> --}}
                        <div class="footer__widget__social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-pinterest"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="footer__copyright">
                        <div class="footer__copyright__text">
                            <p>
                                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                                Copyright &copy;
                                <script>
                                    document.write(new Date().getFullYear());
                                </script> All rights reserved | This template is made with <i
                                    class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com"
                                    target="_blank">Colorlib</a>
                                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            </p>
                        </div>
                        <div class="footer__copyright__payment"><img src="assets/ecomerce/img/payment-item.png"
                                alt=""></div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->
    <!-- Modal -->
    <!-- Filter Modal -->
    <div class="modal left fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-side modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filters</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body filter-section">
                    <div class="form-group">
                        <label for="price_min"> Brand:</label>
                        <select class="form-control" id="filter_part_brand">
                            @foreach ($allBrands as $brand)
                                <option value=""></option>


                                @if (request()->input('filter.brand') == $brand->id)
                                    <option value="{{ $brand->id }}" selected>{{ $brand->name }}</option>
                                @else
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endif
                            @endforeach
                        </select>

                    </div>

                    <div class="form-group">
                        <label for="price_range"> Type:</label>
                        <select class="form-control" id="filter_part_type">
                            <option value=""></option>

                            @foreach ($all_types as $type)
                                @if (request()->input('filter.type') == $type->id)
                                    <option value="{{ $type->id }}" selected>{{ $type->name }}</option>
                                @else
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endif
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="part_number">Series</label>
                        <select class="form-control" id="filter_part_series">
                            <option value=""></option>

                            @foreach ($all_seriess as $series)
                                @if (request()->input('filter.series') == $series->id)
                                    <option value="{{ $series->id }}" selected>{{ $series->name }}</option>
                                @else
                                    <option value="{{ $series->id }}">{{ $series->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Model:</label>
                        <select class="form-control" id="filter_part_model">
                            <option value=""></option>

                            @foreach ($all_models as $model)
                                @if (request()->input('filter.model') == $model->id)
                                    <option value="{{ $model->id }}" selected>{{ $model->name }}</option>
                                @else
                                    <option value="{{ $model->id }}">{{ $model->name }}</option>
                                @endif
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="name">Supplier:</label>
                        <select class="form-control" id="filter_part_supplier">
                            <option value=""></option>

                            @foreach ($all_suppliers as $supplier)
                                @if (request()->input('filter.supplier') == $supplier->id)
                                    <option value="{{ $supplier->id }}" selected>{{ $supplier->name }}</option>
                                @else
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endif
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="price_max">Group: </label>
                        <select class="form-control" id="filter_part_group">
                            <option value=""></option>

                            @foreach ($allGroups as $group)
                                @if (request()->input('filter.group') == $group->id)
                                    <option value="{{ $group->id }}" selected>{{ $group->name }}</option>
                                @else
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endif
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="name">Sub Group:</label>
                        <select class="form-control" id="filter_part_subgroup">
                            <option value=""></option>

                            @foreach ($all_subgroups as $subgroup)
                                @if (request()->input('filter.subgroup') == $subgroup->id)
                                    <option value="{{ $subgroup->id }}" selected>{{ $subgroup->name }}</option>
                                @else
                                    <option value="{{ $subgroup->id }}">{{ $subgroup->name }}</option>
                                @endif
                            @endforeach
                        </select>

                    </div>

                    <button type="button" id="btn_filter_search" class="btn btn-primary">Apply Filters</button>
                    <button type="reset" class="btn btn-secondary ml-2" id="reset_filter">Reset</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Js Plugins -->
    <script src="assets/ecomerce/js/jquery-3.3.1.min.js"></script>
    <script src="assets/ecomerce/js/bootstrap.min.js"></script>
    {{-- <script src="assets/ecomerce/js/jquery.nice-select.min.js"></script> --}}
    <script src="assets/ecomerce/js/jquery-ui.min.js"></script>
    <script src="assets/ecomerce/js/jquery.slicknav.js"></script>
    <script src="assets/ecomerce/js/mixitup.min.js"></script>
    <script src="assets/ecomerce/js/owl.carousel.min.js"></script>
    <script src="assets/ecomerce/js/main.js"></script>
    <script src="{{ URL::asset('asetNew/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#filterButton').click(function() {
                $('#filterModal .modal-dialog').toggleClass('show');
                $('#filterModal').modal('show');
            });
            $('.close').click(function() {
                $('#filterModal .modal-dialog').removeClass('show');
            });
            @if (request()->has('filter.brand') && !empty(request('filter.brand')))
                $("#filter_part_brand").val('{{ request('filter.brand') }}').trigger('change');
            @endif

            @if (request()->has('filter.group') && !empty(request('filter.group')))
                $("#filter_part_group").val('{{ request('filter.group') }}').trigger('change');
            @endif

            @if (request()->has('filter.type') && !empty(request('filter.type')))
                $("#filter_part_type").val('{{ request('filter.type') }}').trigger('change');
            @endif

            @if (request()->has('filter.model') && !empty(request('filter.model')))
                $("#filter_part_model").val('{{ request('filter.model') }}').trigger('change');
            @endif

            @if (request()->has('filter.series') && !empty(request('filter.series')))
                $("#filter_part_series").val('{{ request('filter.series') }}').trigger('change');
            @endif

            @if (request()->has('filter.supplier') && !empty(request('filter.supplier')))
                $("#filter_part_supplier").val('{{ request('filter.supplier') }}').trigger('change');
            @endif

            @if (request()->has('filter.subgroup') && !empty(request('filter.subgroup')))
                $("#filter_part_subgroup").val('{{ request('filter.subgroup') }}').trigger('change');
            @endif
        });
    </script>

    <script>
        $("#filter_part_brand").select2({
            placeholder: 'Select Brand',
            width: '100%'
        });
        $("#filter_part_group").select2({
            placeholder: 'Select Group',
            width: '100%'
        });
        $("#filter_part_type").select2({
            placeholder: 'Select Type',
            width: '100%'
        });
        $("#filter_part_model").select2({
            placeholder: 'Select Model',
            width: '100%'
        });
        $("#filter_part_series").select2({
            placeholder: 'Select Series',
            width: '100%'
        });
        $("#filter_part_supplier").select2({
            placeholder: 'Select Supplier',
            width: '100%'
        });
        $("#filter_part_subgroup").select2({
            placeholder: 'Select SubGroup',
            width: '100%'
        });
        $("#filter_part_brand").on('change', function() {
            $("#input_brand").val('');
            remove_filter_brand();
            var selectedValue = $(this).val();
            if (selectedValue) {
                var selectedText = $(this).find(":selected").text();
                $("#input_brand").val(selectedValue);

                $('#row_filters').append(`
              <div class="ml-3 ">
                    <button type="button" id="remove_btn_brand" onclick="remove_filter_brand()" class="btn btn-outline-info btn-sm">${selectedText} <i
                            class="fa fa-close"></i></button>
                </div>
             `);

            }
        });

        function remove_filter_brand() {
            $("#input_brand").val('');
            $("#remove_btn_brand").parent().remove();

        }


        $("#filter_part_group").on('change', function() {
            $("#input_group").val('');
            remove_filter_group();

            var selectedValue = $(this).val();
            if (selectedValue) {
                var selectedText = $(this).find(":selected").text();
                $("#input_group").val(selectedValue);

                $('#row_filters').append(`
              <div class="ml-3 ">
                    <button type="button" id="remove_btn_group" onclick="remove_filter_group()" class="btn btn-outline-info btn-sm">${selectedText} <i
                            class="fa fa-close"></i></button>
                </div>
             `);

            }





        });

        function remove_filter_group() {
            $("#input_group").val('');
            $("#remove_btn_group").parent().remove();
        }
        $("#filter_part_supplier").on('change', function() {
            $("#input_supplier").val('');
            remove_filter_supplier();

            var selectedValue = $(this).val();
            if (selectedValue) {
                var selectedText = $(this).find(":selected").text();
                $("#input_supplier").val(selectedValue);

                $('#row_filters').append(`
              <div class="ml-3 ">
                    <button type="button" id="remove_btn_supplier" onclick="remove_filter_supplier()" class="btn btn-outline-info btn-sm">${selectedText} <i
                            class="fa fa-close"></i></button>
                </div>
             `);

            }



        });

        function remove_filter_supplier() {
            $("#input_supplier").val('');
            $("#remove_btn_supplier").parent().remove();
        }

        $("#filter_part_model").on('change', function() {
            $("#input_model").val('');
            remove_filter_model();

            var selectedValue = $(this).val();
            if (selectedValue) {
                var selectedText = $(this).find(":selected").text();
                $("#input_model").val(selectedValue);

                $('#row_filters').append(`
              <div class="ml-3 ">
                    <button type="button" id="remove_btn_model" onclick="remove_filter_model()" class="btn btn-outline-info btn-sm">${selectedText} <i
                            class="fa fa-close"></i></button>
                </div>
             `);

            }





        });

        function remove_filter_model() {
            $("#input_model").val('');
            $("#remove_btn_model").parent().remove();
        }

        $("#filter_part_series").on('change', function() {
            $("#input_series").val('');
            remove_filter_series();

            var selectedValue = $(this).val();
            if (selectedValue) {
                var selectedText = $(this).find(":selected").text();
                $("#input_series").val(selectedValue);

                $('#row_filters').append(`
              <div class="ml-3 ">
                    <button type="button" id="remove_btn_series" onclick="remove_filter_series()" class="btn btn-outline-info btn-sm">${selectedText} <i
                            class="fa fa-close"></i></button>
                </div>
             `);

            }
        });

        function remove_filter_series() {
            $("#input_series").val('');
            $("#remove_btn_series").parent().remove();
        }
        $("#filter_part_subgroup").on('change', function() {
            $("#input_subgroup").val('');
            remove_filter_subgroup();

            var selectedValue = $(this).val();
            if (selectedValue) {
                var selectedText = $(this).find(":selected").text();
                $("#input_subgroup").val(selectedValue);

                $('#row_filters').append(`
              <div class="ml-3 ">
                    <button type="button" id="remove_btn_subgroup" onclick="remove_filter_subgroup()" class="btn btn-outline-info btn-sm">${selectedText} <i
                            class="fa fa-close"></i></button>
                </div>
             `);

            }
        });

        function remove_filter_subgroup() {
            $("#input_subgroup").val('');
            $("#remove_btn_subgroup").parent().remove();
        }



        //////////////////////////////////////

        $("#btn_filter_search").on('click', function() {
            $("#price-filter-form").submit();
        });
        $("#reset_filter").on('click', function() {
            $("#row_filters").empty();
            $("#input_brand").val('');
            $("#filter_part_brand").val('').trigger('change');

            $("#input_group").val('');
            $("#filter_part_group").val('').trigger('change');

            $("#input_model").val('');
            $("#filter_part_model").val('').trigger('change');

            $("#input_series").val('');
            $("#filter_part_series").val('').trigger('change');

            $("#input_subgroup").val('');
            $("#filter_part_subgroup").val('').trigger('change');
            $("#minamount").val(ui.values[0]);
            $("#maxamount").val(ui.values[1]);
            $("#price-filter-form").submit();


        });
        $("#refresh_filter").on('click', function() {
            $("#price-filter-form").submit();


        });




        var max_price = {!! $max_price !!};
        var min_price = {!! $min_price !!};

        $(function() {
            let minPrice = {{ request('filter.min_price', $min_price) }};
            let maxPrice = {{ request('filter.max_price', $max_price) }};

            $(".price-range").slider({
                range: true,
                min: min_price, // Use PHP variable for min price
                max: max_price, // Use PHP variable for max price
                values: [minPrice, maxPrice],
                slide: function(event, ui) {
                    $("#minamount").val(ui.values[0]);
                    $("#maxamount").val(ui.values[1]);
                },
                change: function(event, ui) {
                    $("#price-filter-form").submit();
                }
            });

            $("#minamount").val($(".price-range").slider("values", 0));
            $("#maxamount").val($(".price-range").slider("values", 1));
        });

        $('#part_sort_select').on('change', function() {
            $("#price-filter-form").submit();

        });
    </script>

    <script>
        $('.hero__categories__all').click();
    </script>

</body>

</html>
