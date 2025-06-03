@extends('layouts.posMaster')
@section('css')
<style>
    table.dataTable.stripe>tbody>tr.odd>*,
    table.dataTable.display>tbody>tr.odd>* {
        box-shadow: none;
    }

    #datatable tbody {
        text-align: center !important;
    }
    .cairoFont{
        font-family: 'Cairo' !important;
    }
    .cards tbody tr .box:first-child {
        background: #fff !important;
        padding: 0px !important;
        margin: 1.5rem !important;

    }

    .cards tbody tr .box img {
        border-radius: 0% !important;
        padding: .25rem !important;


    }

    .cards tbody tr .box:nth-child(2) {
        font-size: larger;
        color: #007bff !important;
        border-top: var(--vz-border-width) var(--vz-border-style) var(--vz-border-color) !important;
    }

    .cards tbody tr .box:nth-child(3) {
        display: inline-flex !important;
        font-family: var(--vz-font-monospace) !important;
        padding: .25rem !important;
        margin: .25rem !important;
        background-color: RGBA(243, 246, 249, var(--vz-bg-opacity, 1)) !important;
        color: rgba(var(--vz-primary-rgb), var(--vz-text-opacity)) !important;

    }

    .cards tbody tr .box:nth-child(4) {
        display: inline-flex !important;
        font-family: var(--vz-font-monospace) !important;
        padding: .25rem !important;
        margin: .25rem !important;
        background-color: RGBA(243, 246, 249, var(--vz-bg-opacity, 1)) !important;
        color: rgba(var(--vz-primary-rgb), var(--vz-text-opacity)) !important;

    }

    .cards tbody tr .box:nth-child(5) {
        display: inline-flex !important;
        font-family: var(--vz-font-monospace) !important;
        padding: .25rem !important;
        margin: .25rem !important;
        background-color: RGBA(243, 246, 249, var(--vz-bg-opacity, 1)) !important;

    }

    .cards tbody tr .box:nth-child(6)::before {
        content: 'المتاح: '
    }

    .cards tbody tr .box:nth-child(6) {
        color: rgb(224, 86, 0);
        width: 50%;
        display: inline-flex !important;
        padding: .25rem !important;
        text-shadow: none !important;
        box-shadow: none !important;


    }

    .cards tbody tr .box li {
        list-style: none;
    }

    .cards tbody tr .box:nth-child(6):hover {
        text-shadow: none !important;
        box-shadow: none !important;
    }

    .cards tbody tr .box:nth-child(7) {
        display: inline-flex !important;
        padding: .25rem !important;
        color: rgb(224, 86, 0);
        text-shadow: none !important;
        box-shadow: none !important;

    }

    .cards tbody tr .box:nth-child(7):hover {
        text-shadow: none !important;
        box-shadow: none !important;
    }

    .cards tbody tr .box:nth-child(8) {
        border-top: var(--vz-border-width) var(--vz-border-style) var(--vz-border-color) !important;
        padding: 0 !important;
        margin-bottom: 0 !important;
        margin: 0.5rem !important;
        font-size: 1.5rem;
        text-shadow: none !important;
        box-shadow: none !important;
        background-color: #f1f1f1 !important;
    }

    .cards tbody tr .box:nth-child(8)::after {
        content: ' ج.م '
    }

    .cards tbody tr .box:nth-child(8):hover {
        text-shadow: none !important;
        box-shadow: none !important;
    }

    .cards tbody tr .box:nth-child(9) {
        display: none;
    }

    .cards tbody tr .box:nth-child(10) {
        display: none;
    }

    .cards tbody tr .box:nth-child(11) {
        display: none;
    }

    .cards tbody tr .box:nth-child(12) button {
        margin: 0px !important;
        width: 100%;
        border-radius: 50px !important;
        color: #fff !important;
        background-color: #67b173 !important;
        font-size: 0.25rem !important;
        transition: background-color 200ms ease-out 100ms;
    }

    .cards tbody tr .box:nth-child(12) button:hover {
        background-color: #ff3333 !important;
    }


    .cards tbody tr .box:nth-child(12) input {
        display: none !important;
    }

    .cards tbody tr .box:nth-child(13) {
        display: none !important;
    }














    .cards tbody tr {
        justify-content: center;
        width: 23%;
        text-align: center !important;
        margin: 0.5rem;
        display: inline-block !important;
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

    .dataTables_filter input {
        width: 300px;
        /* Set the width */
        padding: 10px;
        /* Add some padding */
        font-size: 16px;
        /* Increase font size */
        border-radius: 5px;
        /* Rounded corners */
        border: 1px solid #ccc;
        /* Border styling */
        outline: none;
        /* Remove the default outline */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        /* Add a subtle shadow */
    }

    /* Change background color on focus */
    .dataTables_filter input:focus {
        border-color: #66afe9;
        /* Border color on focus */
        box-shadow: 0 0 8px rgba(102, 175, 233, 0.6);
        /* Focus shadow */
    }

    .dataTables_filter {
        float: right !important;
        /* Default is on the right; change as needed */
        margin-bottom: 20px;
        /* Adjust the margin below the search box */
    }

    .dataTables_filter label {
        font-weight: bold;
        /* Customize the label font */
        font-size: 14px;
        margin-right: 10px;
        /* Add space between the label and input */
    }

    .col-lg-3,
    .col-lg-9,
    .col-lg-12 {
        transition: all .05s ease;
    }

    /* Keyframes for shrinking and expanding */
    /* @keyframes shrink {
        from {
            width: 10%;
            opacity: 1;
        }

        to {
            width: 0;
            opacity: 0;
        }
    } */
    @keyframes shrink {
    from {
         transform: translateX(0);
         opacity: 1;
         } /* Fully visible */
    to {
        transform: translateX(-100%);
        opacity: 0;
     } /* Move out to the left */
}

@keyframes expand {
    from {
        transform: translateX(-100%);
        opacity: 0;
    } /* Hidden off-screen */

    to {
        transform: translateX(0);
        opacity: 1;
    } /* Slide into view */
}
    /* @keyframes expand {
        from {
            width: 0;
            opacity: 0;
        }

        to {
            width: 20%;
            opacity: 1;
        }
    } */

    /* Hidden class to stop taking up space */
    .hidden {
        display: none;
    }

    /* Animation when hiding */
    .shrinking {
        animation: shrink 0.001s linear forwards;
    }

    /* Animation when showing */
    .expanding {
        animation: expand 0.001s linear forwards;
    }
    #right_col.shrinking {
    animation: shrink 0.001s forwards;
}

#right_col.expanding {
    animation: expand 0.001s forwards;
}

/* @keyframes shrink {
    from { width: 20%; }
    to { width: 0; }
}

@keyframes expand {
    from { width: 0; }
    to { width: 20%; }
} */
    /* Product-Modal */
    .product {
            display: grid;
            grid-template-columns: 0.9fr 1fr;
            margin: auto;
            padding: -2.5em 0;
            min-width: 600px;
            background-color: white;
            border-radius: 5px;
            font-family: 'Cairo' !important;
        }

        /* ----- Photo Section ----- */
        .product__photo {
            position: relative;
        }

        .carousel-item img{
            /* width: 400px !important; */
            height: 32em !important;
        }
        .photo-container {
            position: absolute;
            /* right: -6em; */
            display: grid;
            grid-template-rows: 1fr;
            width: 100%;
            height: 100%;
            border-radius: 6px;
            box-shadow: 4px 4px 25px -2px rgba(0, 0, 0, 0.3);
        }

        .photo-main {
            border-radius: 6px 6px 0 0;
            background-color: #9be010;
            background: radial-gradient(#e5f89e, #96e001);

            .controls {
                display: flex;
                justify-content: space-between;
                padding: 0.8em;
                color: #fff;

                i {
                    cursor: pointer;
                }
            }

            img {
                position: absolute;
                left: -3.5em;
                top: 2em;
                max-width: 110%;
                filter: saturate(150%) contrast(120%) hue-rotate(10deg) drop-shadow(1px 20px 10px rgba(0, 0, 0, 0.3));
            }
        }

        .photo-album {
            padding: 0.7em 1em;
            border-radius: 0 0 6px 6px;
            background-color: #fff;

            ul {
                display: flex;
                justify-content: space-around;
            }

            li {
                float: left;
                width: 55px;
                height: 55px;
                padding: 7px;
                border: 1px solid $color-secondary;
                border-radius: 3px;
            }
        }

        /* ----- Informations Section ----- */
        .product__info {
            padding: 0.8em 0;
        }

        .title {
            font-family: 'cairo'!important;
            h1 {
                margin-bottom: 0.1em;
                color: $color-primary;
                font-size: 1.5em;
                font-weight: 900;
            }

            span {
                font-size: 0.7em;
                color: $color-secondary;
            }
        }

        .price {
            margin: 1.5em 0;
            color: $color-highlight;
            font-size: 1.2em;

            span {
                padding-left: 0.15em;
                font-size: 2.9em;
            }
        }

        .variant {
            overflow: auto;

            h3 {
                margin-bottom: 1.1em;
            }

            li {
                float: left;
                width: 35px;
                height: 35px;
                padding: 3px;
                border: 1px solid transparent;
                border-radius: 3px;
                cursor: pointer;

                &:first-child,
                &:hover {
                    border: 1px solid $color-secondary;
                }
            }

            li:not(:first-child) {
                margin-left: 0.1em;
            }
        }

        .description {
            clear: left;
            margin: 2em 0;

            h3 {
                margin-bottom: 1em;
            }

            ul {
                font-size: 0.8em;
                list-style: disc;
                margin-left: 1em;
            }

            li {
                text-indent: -0.6em;
                margin-bottom: 0.5em;
            }
        }

        .buy--btn {
            padding: 1.5em 3.1em;
            border: none;
            border-radius: 7px;
            font-size: 0.8em;
            font-weight: 700;
            letter-spacing: 1.3px;
            color: #fff;
            background-color: $color-highlight;
            box-shadow: 2px 2px 25px -7px $color-primary;
            cursor: pointer;

            &:active {
                transform: scale(0.97);
            }
        }
        .addcartbtn{
            margin: 0px !important;
            width: 100%;
            border-radius: 50px !important;
            color: #fff !important;
            background-color: #67b173 !important;
            font-size: 1.25rem !important;
            font-weight: bolder;
            transition: background-color 200ms ease-out 100ms;
        }
    label {
        font-size: smaller;
        margin-bottom: 0;
        --vz-text-opacity: 1;
        color: #878a99 !important;
    }
    .form-control,.form-select{
        --vz-bg-opacity: 1;
        background-color: rgba(var(--vz-body-bg-rgb), var(--vz-bg-opacity)) !important;
    }
    .btn-prime{
        --vz-btn-color: #fff;
        --vz-btn-bg: #81ad04;
        --vz-btn-border-color: #8ba739;
        --vz-btn-hover-color: #fff;
        --vz-btn-hover-bg: #646a70;
        --vz-btn-hover-border-color: #5b6268;
        --vz-btn-focus-shadow-rgb: 100, 106, 112;
        --vz-btn-active-color: #fff;
        --vz-btn-active-bg: #6d7379;
        --vz-btn-active-border-color: #5b6268;
        --vz-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
        --vz-btn-disabled-color: #fff;
        --vz-btn-disabled-bg: #495057;
        --vz-btn-disabled-border-color: #495057;

    }
    .catalog-filter-toggler{
        width: 60px;
        min-height: 700px;
        height: 100%;
        padding: 0;
        float: right;
    }
    .catalog-filter-form{
        /*margin-right: 60px;*/
        height: 100%;
    }
    .btn-vertical{
        writing-mode: vertical-rl;

    }
    button.btn-vertical:hover{
        background-color: #81ad04db !important;
    }

</style>
@endsection
@section('js')
@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Point Of Sale</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">POS </li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>


            <div class="bg-white border-bottom mb-1 mx-4 rounded-0 row">
                <div class="row card-body justify-content-center p-3 text-muted">
                    <div class="col-lg-2">
                        <label class="m-0" for="">الفئة  </label>
                        <select name="slected_type" id="slected_type" class="form-select" required>
                            <option class="text-center" value="" selected>الكل </option>
                            <option class="text-center" value="part">قطع غيار </option>
                            <option class="text-center" value="wheel"> كاوتش</option>
                            <option class="text-center" value="kit"> كيت </option>
                            <option class="text-center" value="tractor"> جرار </option>
                            <option class="text-center" value="clark"> كلارك </option>
                            <option class="text-center" value="equip"> معدات </option>

                        </select>
                    </div>
                    <div class="col-lg-7">
                        <label class="m-0" for=""> بحث تلقائي : </label>
                        {{-- <input class="form-control" placeholder="Search Here" type="search" name="" id=""> --}}
                        <select name="partSlct" id="partSlct"></select>
                    </div>
                </div>
            </div>


            <div class="row mx-4">

                {{-- <span id="filterPanel_toggle" class="link-warning p-2 position-fixed rounded-3 text-bg-dark text-right"  style=" width: 50px;cursor: pointer;right: -5px;top: 44%;z-index: 88888;"><i>Filter</i></span> --}}

                <div class="row col-lg-12" id="row_filters">

                </div>


                <div class="col mt-3" id="left_col" style="">
                    <div class="d-flex">
                        <!-- LOGO -->


                        {{-- <button type="button" class="btn btn-sm bx bx-filter-alt fs-22" id="filterPanel_toggle" style="position: absolute;top: -29px;right: -5px;"> --}}

                        {{-- </button> --}}

                        <!-- App Search-->
                    </div>

                    {{-- <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"  id="filterPanel_toggle" >
                        <span class="hamburger-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button> --}}
                   <table id="datatable" class="table table-striped table-bordered cell-border fw-bold display  dt-responsive dataTable dtr-inline no-footer"
                    style="font-size: smaller;width:100%">
                       <thead style="background: #67b1736e;">
                            <tr>
                                <td>الصورة</td>

                                <td>الإسم</td>
                                <td>المنشأ</td>
                                <td>الحالة</td>
                                <td>الكفاءة</td>
                                <td>الكمية</td>
                                <td>المكان</td>
                                <td>السعر</td>
                                <td>الأرقام</td>
                                <td> كميات المخازن</td>
                                <td>إرسال</td>
                                <td>طلب</td>
                                <td>تالف</td>
                                <td>بيع</td>
                                <td>عرض</td>

                            </tr>
                        </thead>

                    </table>

                </div>

                <div class="hidden m-0 p-0 " id="right_col"  style="width:20%;">
                    <div class="border border-top-0 card m-0 p-0 rounded-0" id="filterPanel">
                        <div class="bg-soft-dark card-body p-0">

                            <div class="catalog-filter-form p-3">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button id="filterBtn" class="btn btn-block btn-prime rounded-0">  بحث تفصيلي <i class="ri-search-2-line"></i></button>
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="partNameSearchTxt">بحث عن</label>
                                        <input type="search" value="" class="form-control" name="number"
                                            id="partNameSearchTxt">
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="brandtypeSlct">النوع'TYPE' :</label>
                                        <select name="brandType" class="form-select" id="brandtypeSlct">
                                            <option value="" selected disabled>Choose Here</option>
                                            @foreach ($Btype as $element)
                                                <option value="{{ $element->id }}">{{ $element->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="brandSlct">Brand</label>
                                        <select name="brand" aria-label="Default select example" class="form-select"
                                            id="brandSlct">
                                            <option value="" selected disabled>Choose Here</option>
                                            @foreach ($allbrand as $element)
                                                <option value="{{ $element->id }}">{{ $element->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="modelSlct">MODEL</label>
                                        <select name="model" class="form-select" id="modelSlct">
                                            <option value="" selected disabled>Choose Here</option>
                                            @foreach ($allmodel as $element)
                                                <option value="{{ $element->id }}">{{ $element->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="seriesSlct">SERIES</label>
                                        <select name="series" class="form-select" id="seriesSlct">
                                            <option value="" selected disabled>Choose Here</option>
                                            @foreach ($allseries as $element)
                                                <option value="{{ $element->id }}">{{ $element->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="partNumberSearchTxt">NUMBER</label>
                                        <input type="search" value="" class="form-control" name="number"
                                            id="partNumberSearchTxt">
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="supplierSlct">SUPPLIER</label>
                                        <select name="supplier" class="form-select" id="supplierSlct">
                                            <option value="" selected disabled>Choose Here</option>
                                            @foreach ($allSuppliers as $element)
                                                <option value="{{ $element->id }}">{{ $element->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="groupSlct">Group</label>
                                        <select name="group" class="form-select" id="groupSlct">
                                            <option value="" selected disabled>Choose Here</option>
                                            @foreach ($allGroups as $element)
                                                <option value="{{ $element->id }}">{{ $element->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="SgroupSlct">Sub Group</label>
                                        <select name="subgroup" class="form-select" id="SgroupSlct">
                                            <option value="" selected disabled>Choose Here</option>
                                            @foreach ($allSGroups as $element)
                                                <option value="{{ $element->id }}">{{ $element->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-soft-dark catalog-filter-toggler"  style="">
                    <span>
                        <button onclick="toggle_pannel()" class="bg-dark border-4 btn btn-block btn-link btn-vertical h-50 p-3 rounded-0 rounded-top text-end text-light" type="button" data-toggle="collapse" data-target="#collapseCatalogFilter" aria-expanded="true" aria-controls="collapseCatalogFilter">
                            <i class="bg-white mb-4 p-1 ri-arrow-right-line round-shape rounded-circle text-danger text-primary" id="filter-icon-toggle"></i> Filters
                        </button>
                    </span>
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
                            {{-- <input type="search" class="form-control mb-2" name="" id="searchSectionTxt"> --}}

                            <table id="resultSection">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>part</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
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
                                                <th>Refund</th>
                                            </tr>
                                        </thead>
                                        @foreach ($allClients as $client)
                                            <tr class="resclient">
                                                <td>{{ $client->name }}</td>
                                                <td>{{ count($client->invoices) }}</td>
                                                <td>{{ $client->egmal }}
                                                </td>
                                                <td>{{ $client->invoices->sum('actual_price') }}</td>
                                                <td>{{ $client->refund_invoices_sum }}</td>
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
                                    class="display table table-bordered dt-responsive dataTable dtr-inline fs-12"
                                    style="width:100%;">
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
                                            <th class="text-center">ratio </th>
                                            <th class="text-center">unit </th>
                                            <th class="text-center">unit_id </th>
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


                    <form id="posForm" action="/printpos" method="POST" class="fs-20 p-5">
                        @csrf
                        <input type="hidden" name="storeId" value="{{ $store_data[0]->id }}">
                        <div class="row">
                            <div class="col-1 m-0 my-2 p-0 text-end">
                                <button type="button" class="AddnewClient btn m-0 p-0"><i
                                        class="fs-2 mdi mdi-plus-circle text-secondary"></i></button>
                            </div>
                            <div class="col-1 text-end">
                                <label>العمــــــيل</label>
                            </div>
                            <div class="col-4 ">

                                <select name="client" id="clientSlct" class="form-control" required>
                                    <option selected disabled value="">إختر العميــــــل</option>
                                    @foreach ($clients as $client)
                                        <option data-mad="{{ $client->egmal }}" data-sup_id="{{ $client->sup_id }}"
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
                                            <option value="{{ $price->id }}">{{ $price->type }} </option>
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
                                            <td>unit</td>
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
                                                        <input style="position: relative" name="taxes[]" type="checkbox"
                                                            value="{{ $tax->value }}">
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
                                            {{-- <option value="0">كاش</option>
                                    <option value="1">تحويل بنكي</option>
                                    <option value="2"> علي الحساب</option> --}}
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
                                        <label for="invPaied" class="form-label">paid</label>
                                        <input type="text" class="form-control" name="invPaied" required=""
                                            id="invPaied" aria-describedby="helpId" placeholder="">

                                    </div>
                                    <div class="col mb-3">
                                        <label for="invDiscount" class="form-label"> Discount ( بالجنية )</label>
                                        <input type="text" class="form-control" name="invDiscount" value="0"
                                            id="invDiscount" aria-describedby="helpId" placeholder="">


                                    </div>
                                    <div class="col mb-3">
                                        <label for="invMad" class="form-label">Remain ( مديونية ) </label>

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
                        {{-- <h5 class="modal-title" id="infoMdlLabel">Info</h5> --}}
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="product mb-3">

                            <div class="product__photo">
                                <div class="photo-container carousel slide d-inline-block h-auto w-100" id="productCarousel"
                                    data-bs-ride="carousel">
                                    <div class=" bg-white carousel-inner rounded-4" id="item-image-car">
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
                            <div class="product__info px-5">
                                <div class="title">
                                    <h2 class="cairo w-100 " id="itemName"></h2>
                                    <h5 class="p-3 text-bg-light" id="itemNum"></h5>
                                </div>
                                <div class="price">
                                    <p class="card-text" style="cursor: pointer;"><span id="itemPrice" class="fw-bolder text-danger" onclick="$('#pList').toggle()">0</span>ج.م
                                    </p>
                                    <div class="row" id="pList" style="display:none">
                                        <div class="col">
                                            <ul id="" class=" itemPriceList ">
                                                <li>No Pricing List</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="description">
                                    <ul class="fs-17 fw-bold">
                                        <li id="itemQuality" class="float-end la"></li>
                                        <li>
                                            <span class="d-none" >المنشأ / الحالة : </span>
                                            <span id="itemDesc"></span></li>
                                        <li>
                                            <span class="d-none"  >المخزون :</span>
                                            <span id="itemStock"> </span></li>


                                    </ul>
                                    {{-- <hr> --}}
                                    {{-- <h3>المواصفات : </h3> --}}
                                    <ul id="carditemSpecs" class="list-group-horizontal fs-19 fw-bold d-inline-flex">

                                    </ul>
                                </div>
                                <button class="addcartbtn btn w-75 " id="addtocardMdl">إضافة <i></i></button>
                            </div>

                        </div>
                        <hr>
                        <div class="row">
                            <div class="col">
                                <nav>
                                    <div class="nav nav-pills nav-justified " id="nav-tab" role="tablist">
                                        <button style="text-wrap: nowrap;" class="nav-link active" id="nav-home-tab"
                                            data-bs-toggle="tab" data-bs-target="#nav-home" type="button"
                                            role="tab" aria-controls="nav-home" aria-selected="true">ITEM
                                            APPLICATIONS</button>
                                        <button style="text-wrap: nowrap;" class="nav-link" id="nav-profile-tab"
                                            data-bs-toggle="tab" data-bs-target="#nav-profile" type="button"
                                            role="tab" aria-controls="nav-profile" aria-selected="false">FLIP
                                            CODES</button>
                                        <button style="text-wrap: nowrap;" class="nav-link" id="nav-contact-tab"
                                            data-bs-toggle="tab" data-bs-target="#nav-contact" type="button"
                                            role="tab" aria-controls="nav-contact"
                                            aria-selected="false">Specifications</button>
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
                                <div class="tab-content fs-19 fw-bold border-ridge border-3" id="nav-tabContent">
                                    <div class="tab-pane fade active show" id="nav-home" role="tabpanel"
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
                                                    <div class="nav nav-pills nav-justified" id="nav-tabss"
                                                        role="tablist">
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
                                <h4>إجمالى الكمية بالمخزن (<span class="text-info" id="itemAmountS"> 00 </span>)    <span id="itemAmountS_unit">00</span></h4>
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
                                    <label for="storeId"> إرسال إالى مخزن</label>
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
        <div class="modal fade" id="askMdl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="askMdlLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 80vw !important;">
                <div class="modal-content w-100" style="width: 100%!important">
                    <div class="modal-header ">
                        <h5 class="modal-title" id="askMdlLabel">ask from Store</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" id="askFormss" method="GET">
                        @csrf
                        @method('GET')
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-lg-6">
                                    <h2 id="askitemNameS">000</h2>
                                </div>
                                <div class="col-lg-6">
                                <h4>إجمالى الكمية بالمخزن (<span class="text-info" id="askitemAmountS"> 00 </span>)    <span id="askitemAmountS_unit">00</span></h4>

                                </div>
                            </div>
                            <input type="hidden" name="askpartIdS" id="askpartIdS" value="0">
                            <input type="hidden" name="askpartTypeS" id="askpartTypeS" value="0">
                            <input type="hidden" name="askpartSourceS" id="askpartSourceS" value="0">
                            <input type="hidden" name="askpartStatusS" id="askpartStatusS" value="0">
                            <input type="hidden" name="askpartQualityS" id="askpartQualityS" value="0">
                            <input type="hidden" name="askCurrentstoreId" id="askCurrentstoreId"
                                value="{{ $store_data[0]->id }}">
                            

                            <div class="row">
                                <div class="col-12">
                                    <h3>الحالى</h3>
                                    <table
                                        class=" mt-4 table table-striped table-bordered cell-border fw-bold display  dt-responsive dataTable dtr-inline no-footer"
                                        style="font-family: 'Cairo';width:100%">
                                        <thead style="background:#5fcee78a">
                                            <tr>
                                                <td>id</td>
                                                <th>القسم</th>
                                                <th>موجود </th>
                                            </tr>
                                        </thead>
                                        <tbody id="ask_sectiontbl">

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <h3>المطلوب</h3>

                                    <table
                                        class=" mt-4 table table-striped table-bordered cell-border fw-bold display  dt-responsive dataTable dtr-inline no-footer"
                                        style="font-family: 'Cairo';width:100%">
                                        <thead style="background:#5fcee78a">
                                            <tr>
                                                <th>المخزن</th>
                                                <th>موجود </th>
                                                <th>كمية</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ask_storetbl">

                                        </tbody>
                                    </table>
                                </div>
                            </div>


                            <div>
                                <button type="button" id="askStoreBtn" class="btn btn-info mt-3 w-50">Save</button>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
         <div class="modal fade" id="imageMdl"  tabindex="-1" aria-labelledby="imageMdlLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: auto !important;">
                <div class="bg-transparent border-0 modal-content w-100" style="width: 100%!important">
                    <div class="bg-transparent modal-body ">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <img src="" id="ModalImage" alt="" style="width: -webkit-fill-available;">
                            </div>
                        </div>
                    </div>

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
            var partsDt;
            var resultSection;
            $(document).ready(function() {
                // toggle_pannel()
                $("#partSlct").select2({
                    ajax: {
                        url: "partsSearch",
                        //   dataType: 'json',
                        async: false,
                        delay: 250,
                        data: function(params) {
                            return {
                                // (params.term).replace(/\//g," ").toLowerCase()
                                q: encodeURIComponent((params.term).toLowerCase()), // search term
                                page: params.page,
                                type: $('#slected_type').val()
                            };
                        },
                        processResults: function(data, params) {
                            // parse the results into the format expected by Select2
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data, except to indicate that infinite
                            // scrolling can be used
                            // $("#select2-partSlct-results").empty();
                            // data.forEach(element => {
                            //     $("#select2-partSlct-results").append(`<li>${element.name}</li>`);
                            // });

                            params.page = params.page || 1;
                            return {
                                results: data,
                                //   pagination: {
                                //     more: (params.page * 30) < data.total_count
                                //   }
                            };
                        },
                        cache: true
                    },
                    placeholder: 'Search ',
                    minimumInputLength: 3,
                    templateResult: formatRepo,
                    templateSelection: formatRepoSelection

                });

                function formatRepo(repo) {
                    //     $("#select2-partSlct-results").append(repo);
                    //    return repo;
                    if (repo.loading) {
                        return repo.text;
                    }

                    var $container = $(
                        "<div class='select2-result-repository clearfix'>" +
                        //"<div class='select2-result-repository__avatar d-none'><img src='" + repo.name + "' /></div>" +
                        "<div class='select2-result-repository__meta'>" +
                        "<div class='select2-result-repository__title'></div>" +
                        "<div class='select2-result-repository__description'></div>" +
                        "<div class='select2-result-repository__statistics'>" +
                        "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> </div>" +
                        "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> </div>" +
                        "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> </div>" +
                        "</div>" +
                        "</div>" +
                        "</div>"
                    );


                    $container.find(".select2-result-repository__title").text(repo.name);
                    // $container.find(".select2-result-repository__description").text(repo.description);
                    // $container.find(".select2-result-repository__forks").append(repo.name + " Forks");
                    // $container.find(".select2-result-repository__stargazers").append(repo.name + " Stars");
                    // $container.find(".select2-result-repository__watchers").append(repo.name + " Watchers");

                    return $container;
                }

                function formatRepoSelection(repo) {
                    return repo.name || repo.text || repo.type_id;
                }

                function select2_search($el, term) {
                    $el.select2('open');

                    // Get the search box within the dropdown or the selection
                    // Dropdown = single, Selection = multiple
                    var $search = $el.data('select2').dropdown.$search || $el.data('select2').selection.$search;
                    // This is undocumented and may change in the future

                    $search.val(term);
                    $search.trigger('keyup').click();
                    $search.trigger('change');
                }


                // dom: '<"dt-buttons"Bf><"clear">lirtp',

                partsDt = $('#datatable').DataTable({
                    dom: '<"top pt-4"Bf>rt<"bottom"ip><"clear">',
                    // dom: '<" pt-4"Bf><"clear">lirtp',
                    paging: true,
                    autoWidth: true,
                    orderCellsTop: true,
                    fixedHeader: true,
                    processing: true,
                    "language": {
                        "zeroRecords": " أدخل كلمات البحث . "
                    },

                    buttons: [
                        // "colvis",
                        //     "copyHtml5",
                        //     "csvHtml5",
                        //     "excelHtml5",
                        // "pdfHtml5",
                        {
                            // extend: 'copy',
                            'text': '<i class="bx bx-copy" aria-hidden="true"></i> ',
                            'className': 'btn-sm',
                            'attr': {
                                'title': 'copy',
                            }

                        },
                        {
                            // extend: 'csvHtml5',
                            'text': '<i class="bx bx-file" aria-hidden="true"></i> ',
                            'className': 'btn-sm',
                            'attr': {
                                'title': 'CSV',
                            }
                        },

                        {
                            // extend: 'print',
                            'text': '<i class="bx bx-printer" aria-hidden="true"></i> ',
                            'className': 'btn-sm',
                            'attr': {
                                'title': 'print',
                            },
                            exportOptions: {
                                columns: [1, 4]
                            }
                        },
                        {
                            'text': '<i class="bx bx-grid-vertical" aria-hidden="true"></i>',

                            'action': function(e, dt, node) {

                                if ($(partsDt.table().node()).hasClass('cards')) {
                                    $('#datatable tbody tr div.box').replaceWith(function() {

                                        var content = $(this).html();
                                        var $d = $('<td>', {
                                            'html': content
                                        });
                                        return $d;
                                    });
                                    $(partsDt.table().node()).toggleClass('cards');
                                    $('.fa', node).toggleClass(['bx-table', 'bx-grid-vertical']);
                                } else {

                                    $(partsDt.table().node()).toggleClass('cards');
                                    $('.fa', node).toggleClass(['bx-table', 'bx-grid-vertical']);

                                    // $('#datatable').addClass('gridShow');
                                    $('#datatable tbody tr td').replaceWith(function() {

                                        var content = $(this).html();
                                        var $d = $('<div>', {
                                            'class': 'box',
                                            'html': content
                                        });
                                        return $d;
                                    });
                                }



                                partsDt.draw('page');
                            },
                            'className': 'btn-sm gridTogView',
                            'attr': {
                                'title': 'Change views',
                            }
                        }
                        // {
                        //     // extend: 'csvHtml5',
                        //     'text': '<i class="bx bx-filter-alt " aria-hidden="true"></i> ',
                        //     'className': 'btn-sm text-bg-info',
                        //     'action': function(e, dt, node) {
                        //         toggle_pannel()
                        //     }
                        // }
                    ],
                    // ajax: "itemsStore/list/" + store_data[0].id,
                    // async:false,
                    columns: [



                        {
                            data: 'Image',
                            // visible : false
                        },
                        {
                            data: 'name',
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
                            visible: false
                        },
                        {
                            data: 'stores_amount',
                        },
                        {
                            data: 'send',
                        },
                        {
                            data: 'ask',
                        },
                        {
                            data: 'talef',
                        },
                        {
                            data: 'action',
                        }, {
                            data: 'view',
                        }
                    ],
                     columnDefs: [
                        { targets: 1, width: '20%' },
                        { targets: 2, width: '3%' },
                        { targets: 3, width: '3%' },
                        { targets: 5, width: '3%' },
                        { targets: 7, width: '3%' },
                        { targets: 13, width: '5%' },
                        // Add more columns as needed
                    ]
                });

                // toggle_pannel();


                resultSection = $('#resultSection').DataTable({
                    dom: '<"top pt-4"Bf>rt<"bottom"ip><"clear">',
                    // dom: '<" pt-4"Bf><"clear">lirtp',
                    paging: true,
                    autoWidth: true,
                    orderCellsTop: true,
                    fixedHeader: true,
                    processing: true,
                    "language": {
                        "zeroRecords": ".............."
                    },

                    buttons: [
                        "colvis",
                        //     "copyHtml5",
                        //     "csvHtml5",
                        //     "excelHtml5",
                        // "pdfHtml5",
                        {
                            // extend: 'copy',
                            'text': '<i class="bx bx-copy" aria-hidden="true"></i> ',
                            'className': 'btn-sm',
                            'attr': {
                                'title': 'copy',
                            }

                        },
                        {
                            // extend: 'csvHtml5',
                            'text': '<i class="bx bx-file" aria-hidden="true"></i> ',
                            'className': 'btn-sm',
                            'attr': {
                                'title': 'CSV',
                            }
                        },

                        {
                            // extend: 'print',
                            'text': '<i class="bx bx-printer" aria-hidden="true"></i> ',
                            'className': 'btn-sm',
                            'attr': {
                                'title': 'print',
                            },
                            exportOptions: {
                                columns: [1, 4]
                            }
                        },


                    ],

                    columns: [



                        {
                            data: 'name',

                        },
                        {
                            data: 'part',
                        },

                    ]
                });
            });
            $('#filterPanel_toggle').on('click', function() {
                toggle_pannel();
            });

            // function toggle_pannel() {
            //     const col3 = $('#right_col');
            //     const col9 = $('#left_col');

            //     if (!col3.hasClass('hidden')) {
            //         // Shrink the col-lg-3 and expand col-lg-9 to col-lg-12
            //         col3.addClass('shrinking');
            //         col9.removeClass('col-lg-9').addClass('col');

            //         // After animation completes, hide col3 and reset classes
            //         col3.one('animationend', function() {
            //             col3.removeClass('shrinking').addClass('hidden');
            //         });
            //     } else {
            //         // Show col-lg-3 and shrink col-lg-12 back to col-lg-9
            //         col3.removeClass('hidden').addClass('expanding');
            //         col9.removeClass('col').addClass('col-lg-9');

            //         // After animation completes, remove the expanding class
            //         col3.one('animationend', function() {
            //             col3.removeClass('expanding');
            //         });
            //     }

            // }
function toggle_pannel() {
    const col3 = $('#right_col'); // Right column for filters
    const col9 = $('#left_col');  // Left column for main content
    const filterIcon = $('#filter-icon-toggle'); // Icon toggle for indication

    if (!col3.hasClass('hidden')) {
        // Hide filter panel and expand main content
        col3.addClass('shrinking');
        // col9.removeClass('col-lg-9').addClass('col'); // Expand to full width

        // Update icon to indicate panel closed
        filterIcon.removeClass('ri-close-line').addClass('ri-arrow-right-line');

        // After animation, hide filter panel and reset classes
        col3.one('animationend', function() {
            col3.removeClass('shrinking').addClass('hidden');
        });
    } else {
        // Show filter panel and reduce main content width
        col3.removeClass('hidden').addClass('expanding');
        // col9.removeClass('col').addClass('col-lg-9'); // Shrink back to original width

        // Update icon to indicate panel opened
        filterIcon.removeClass('ri-arrow-right-line').addClass('ri-arrow-left-line');

        // After animation, reset the expanding class
        col3.one('animationend', function() {
            col3.removeClass('expanding');
        });
    }
}
            // $(document).on('select2:open', () => {
            //     document.querySelector('.select2-search__field').focus();
            // });


$("#partSlct").on('change', function(e) {
    var selectedText = $("#select2-partSlct-container").text();
    var selectedType = $(this).select2('data')[0].type_id
    var selectedPartID = $(this).val();
    $.ajax({
        type: "GET",
        url: "allDataForId",
        data: {
            PartID: selectedPartID,
            typeId: selectedType,
            storeId: store_data[0].id
        },
        success: function(data) {
            console.log(data);
            partsDt.clear();
            if (data.data.length > 0) {
                $('#datatable').DataTable();
                partsDt.rows.add(data.data).draw();

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'الصنف غير موجود بالمخزن ',
                    footer: 'Not In Store  '
                });
            }




        }
    })


});






            function removeDuplicates(arr) {
                return arr.filter((item,
                    index) => arr.indexOf(item) === index);
            }
        </script>

        <script>
            function SendToStoreNew(sections, partId, SourceId, StatusId, QualityId, name, Totalamount, ratiounit ,ratiounit_name, typeId) {

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
                            <td>${sumByType[i].sumOfIds /ratiounit } / ${ratiounit_name}</td>
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
                $("#ratio").val(ratiounit);
                $("#itemAmountS").text(Totalamount / ratiounit );
                $("#itemAmountS_unit").text( ratiounit_name );
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

            function getSections() {
                $("#preloader").css({
                    'opacity': '1',
                    'visibility': 'visible'
                });
                $.ajax({
                    type: "GET",
                    url: "/getSectionsWithData/" + store_data[0].id,
                    success: function(data) {
                        // $('#resultSection').DataTable();
                        resultSection.rows.add(data.data).draw();



                    },
                    complete: function(response) {
                        $("#preloader").css({
                            'opacity': '0',
                            'visibility': 'hidden'
                        });
                    }
                })
            }

            $(document).on('click', 'tr td img', function() {

                $("#ModalImage").attr('src', $(this).attr('src'))
                $("#imageMdl").modal('toggle');
                var imageWidth = $(this).width();
                var viewportWidth = $(window).width();
                var widthPercentage = (imageWidth / viewportWidth) * 100;
                document.querySelector("#imageMdl .modal-dialog")?.style.setProperty("max-width", '40%', "important");
            })
        </script>
        
         <script>
            //ask from store
            function askStoreNew(sections, partId, SourceId, StatusId, QualityId, name, Totalamount, ratiounit ,ratiounit_name, typeId) {
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
                            <td>${sumByType[i].sumOfIds /ratiounit } / ${ratiounit_name}</td>
                            </tr>

                        `)
                        }

                    }

                } else {
                    sectiontbl_html.push(`<tr>
                            <td></td>
                            <td>لم يتم توزيعة على الاقسام</td>
                            <td></td>

                            </tr>

                        `)
                }
                formData={
                    'partId':partId,
                    'SourceId':SourceId,
                    'StatusId':StatusId, 
                    'QualityId':QualityId, 
                    'typeId':typeId
                }
                $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "GET",

                            url: "asksrore_getdata",
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
                                // console.log(data);
                                asktbl_html=[];
                                for (let i = 0; i < data.length; i++) {
                                    if (data[i].storepartCount > 0 && data[i].id != store_data[0].id) {
                                        asktbl_html.push(`<tr>
                                            
                                        <input type="hidden" name="partId[]" id="" value="${partId}">
                                        <input type="hidden" name="sourceId[]" id="" value="${SourceId}">
                                        <input type="hidden" name="statusId[]" id="" value="${StatusId}">
                                        <input type="hidden" name="qualityId[]" id="" value="${QualityId}">
                                        <input type="hidden" name="typeId[]" id="" value="${typeId}">

                                        <input type="hidden" name="askedStore_id[]" id="" value="${data[i].id}">
                                        <input type="hidden" name="Store_id[]" id="" value="${store_data[0].id}">
                                        <td>${data[i].name}</td>
                                        <td>${data[i].storepartCount /ratiounit } / ${ratiounit_name}</td>

                                        <td><input class="form-control border askAmount" name="askAmount[]" type="number" max=${data[i].storepartCount}  min="1" value="0" > </td>
                                        </tr>

                                    `)
                                    }

                                }
                                $("#ask_storetbl").html(asktbl_html);

                            

                            }
                        });
                
                $("#ask_sectiontbl").html(sectiontbl_html);
                $("#askitemNameS").text(name);
                $("#askitemAmountS").text(Totalamount / ratiounit );
                $("#askitemAmountS_unit").text( ratiounit_name );
                $("#askpartIdS").val(partId);
                $("#askpartTypeS").val(typeId);
                $("#askpartSourceS").val(SourceId);
                $("#askpartStatusS").val(StatusId);
                $("#askpartQualityS").val(QualityId);

                $("#askMdl").modal('toggle');
                $("#asktotalAmountNum").val(0);

            }
            $(document).on('keyup', '.askAmount', function() {
                flage = 0;
                var sum = 0;
                var need_from_store = 0;
                need_from_store = parseInt($(this).val()) > 0 ? parseInt($(this).val()) : 0;
                var maxamount = parseInt($(this).attr('max'));
                sum = parseInt($(this).val());
                if (maxamount >= need_from_store && need_from_store >= 0) {

                    flage = 1;
                    } else {
                       
                            Swal.fire({
                                icon: 'error',
                                text: 'الكمية المطلوبة اكبر من المتاح ',
                                title: 'Oops...',
                                footer: 'Not In Store  '
                            });
                            $(this).val(0);
                } 

            });
            $("#askStoreBtn").click(function(e) {
                e.preventDefault();
                var formData = $("#askFormss").serializeArray();
                // var store_idd = parseInt($("#storeId").val());
                var sum = 0;
            
                    
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",

                    url: "askfromStoreNew",
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
                            Swal.fire('تم إرسال الكمية المطلوبة  ');
                            $("#askMdl").modal('toggle');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: ' برجاء مراجعة البيانات ',
                                footer: 'Not In Store  '
                            });
                        }

                        

                    }
                });
            
              

            })
        </script>
        <script>
            document.getElementById('posForm').addEventListener('submit', function(e) {
                // Clear specific key from localStorage before submitting
                localStorage.removeItem('cardOptions');
                localStorage.removeItem('cardsaleType');
                // Form will continue to submit normally
            });
        </script>
    @endsection
