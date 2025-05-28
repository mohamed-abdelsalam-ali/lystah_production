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
    <meta content="Themesbrand" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico') }}">

    <link href="{{ URL::asset('assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" />

    <!-- Layout config Js -->a
    <script src="{{ URL::asset('assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ URL::asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ URL::asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ URL::asset('assets/css/app-rtl.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ URL::asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <link rel="stylesheet" href="/assets/libs/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css">


    <style>
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
        .fixedbtn {
            position: fixed;
            top: 20%;
            right: 0px;
            border-radius: 50px 0px 0px 50px;
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
            margin: 2em;
        }

        #sectionMdl .modal-content {
            width: 60vw !important;
        }

        #sectionMdl .modal-dialog {
            max-width: 60vw !important;
        }

        #ClientMdl .modal-content {
            width: 60vw !important;
        }

        #ClientMdl .modal-dialog {
            max-width: 60vw !important;
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

        #example11Modal {

            top: 0px;
            left: 0;
            bottom: 0;
            z-index: 10040;
            overflow: auto;
            overflow-y: auto;
        }

        #example11Modal .modal-dialog {
            margin-left: 0px;
            margin-top: 0px;
        }

        p {
            text-align: center;
            color: #0cb853;
            font-size: 1.5em;
            font-weight: bold;
            text-shadow: 1px 1px 2px #000;
            margin-bottom: 1.2em;
        }

        .buttons-columnVisibility {
            text-align: center;
            padding: 10px;
        }
    </style>

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">
        <div class="main-content m-0">
            <div class="page-content p-0">
                <button type="button" class="border rounded-circle text-bg-success"
                    onclick="$('#posHeader').toggle();"><i class="ri-arrow-up-fill"></i></button>
                <div id="posHeader" class="bg-white p-2 row">
                    <div class="col-4">
                        <table>
                            <tr>
                                <td class="text-center">
                                    <h2 style="text-shadow: 1px 1px 2px #000;color:#0cb853">مجموعة شركــــات عمــارة
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
                    <div class="col-lg-4">
                        <p class="m-1">EMARA STORE</p>
                        <h3 class="text-danger text-center">{{ $store_data[0]->name }}</h3>
                        <h4 class="text-info text-center"><i class="ri-phone-fill"> </i> {{ $store_data[0]->tel01 }}
                        </h4>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-4 text-end">
                        <table class="w-100">
                            <tr>
                                <td>
                                    <button class="btn p text-bg-danger w-100" data-bs-toggle="modal"
                                        data-bs-target="#inboxMdl">الــــوارد
                                        <span
                                            class="position-absolute topbar-badge cartitem-badge fs-11 translate-middle badge rounded-pill bg-info"
                                            id="inboxCounterLbl">0</span>
                                    </button>
                                </td>
                                <td>
                                    <button class="btn p text-bg-info w-100" data-bs-toggle="modal"
                                        data-bs-target="#sectionMdl"> تقسيم
                                        المخزن</button>
                                </td>

                                <td>
                                    <button class="btn p text-bg-primary w-100" data-bs-toggle="modal"
                                        data-bs-target="#storeMdl">بضاعة بالمخزن
                                        {{-- <span
                                        class="position-absolute topbar-badge cartitem-badge fs-11 translate-middle badge rounded-pill bg-info"
                                        id="CounterLbl">0</span> --}}
                                    </button>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <button class="btn p text-bg-warning  w-100" id="gardBtn">جـــــــرد</button>
                                </td>

                                <td>
                                    <button class="btn p text-bg-success w-100 " data-bs-toggle="modal"
                                        data-bs-target="#ClientMdl" id="">العمــــلاء</button>
                                </td>

                                <td>
                                    <button class="btn p text-bg-secondary w-100" onclick="toggleFullScreen()"><i
                                            class="bx bx-fullscreen fs-22"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <button class="btn p text-bg-success w-100 "
                                        onclick="location.href='storeSections/{{ $store_data[0]->id }}'"
                                        id="">الاقســـام</button>
                                </td>
                                <td>
                                    <a class="dropdown-item" href="{{ route('logout') }}" id="btn-logout"
                                        onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
                                            class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span
                                            class="align-middle" data-key="t-logout">Logout</span></a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </td>
                            </tr>
                        </table>







                    </div>

                </div>
                <button type="button" class="btn btn-primary fixedbtn" data-bs-toggle="modal"
                    data-bs-target="#example11Modal">
                    Filter
                </button>
                <input id="input-to-fill" class="opacity-0" type="text" contenteditable="true"
                    placeholder="Barcode Result">
                <div class="row bg-body">
                    <div class="border  col-8 p-3 m-1 table-responsive bg-white" style="">
                        <table id="example" class="table cssTable table-striped text-center  " cellspacing="0"
                            width="100%">
                            <thead class="text">
                                <tr>
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
                                    @if ($item['type_id'] == 1)  <!--part-->
                                        <tr>
                                        
                                        @php
                                            $imgName = '';
                                            if (count($item['image']) > 0) {
                                                $imgName = $item['image'][0]->image_name;
                                            }
                                        @endphp
                                        <td><img class="rounded-circle header-profile-user"
                                                src="assets/part_images/{{ $imgName }}" alt="Emara">
                                            <span
                                                class="barcodeTxt d-none ">{{ $item['type_id'] }}-{{ $item['p_data']->id }}-{{ $item['source'][0]->id }}-{{ $item['status'][0]->id }}-{{ $item['quality'][0]->id }}</span>
                                            <svg class="barcode d-none">

                                            </svg>

                                        </td>
                                         @if ($item['type_id'] == 1 && count($item['allparts']) > 0 )  <!--part-->
                                            <td onclick="CardInfo({{ $item['allparts'][0]->id }})">
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
                                                    <li onclick="">{{ $item['p_data']->part_numbers[$i]->number }}</li>
                                                @endfor
                                            @else
                                                <li>لا يوجد ارقام</li>
                                            @endif
                                        </td>
                                        {{-- <td>{{ $item['source'][0]->name_arabic }}</td>
                                        <td>{{ $item['status'][0]->name }}</td> --}}
                                        <td>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}</td>
                                        <td>{{ $item['Tamount'] }}</td>
                                        <td>
                                            @if (isset($item['section']))
                                                @for ($i = 0; $i < count($item['section']); $i++)
                                                    <li>{{ $item['section'][$i]->store_structure->name }}</li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
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
                                                    <li> {{ $item['models'][$i]->series->model->brand->name }} </li>
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
                                                onclick="addtoInvoice(this,{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }})">Add</button>
                                        </td>

                                    </tr>
                                    @elseif ($item['type_id'] == 2) <!--wheel-->
                                        <tr>
                                        
                                        @php
                                            $imgName = '';
                                            if (count($item['image']) > 0) {
                                                $imgName = $item['image'][0]->image_name;
                                            }
                                        @endphp
                                        <td><img class="rounded-circle header-profile-user"
                                                src="assets/part_images/{{ $imgName }}" alt="Emara">
                                            <span
                                                class="barcodeTxt d-none ">{{ $item['type_id'] }}-{{ $item['p_data']->id }}-{{ $item['source'][0]->id }}-{{ $item['status'][0]->id }}-{{ $item['quality'][0]->id }}</span>
                                            <svg class="barcode d-none">

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
                                                    <li onclick="">{{ $item['p_data']->part_numbers[$i]->number }}</li>
                                                @endfor
                                            @else
                                                <li>لا يوجد ارقام</li>
                                            @endif
                                        </td>
                                        {{-- <td>{{ $item['source'][0]->name_arabic }}</td>
                                        <td>{{ $item['status'][0]->name }}</td> --}}
                                        <td>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}</td>
                                        <td>{{ $item['Tamount'] }}</td>
                                        <td>
                                            @if (isset($item['section']))
                                                @for ($i = 0; $i < count($item['section']); $i++)
                                                    <li>{{ $item['section'][$i]->store_structure->name }}</li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
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
                                                    <li> {{ $item['models'][$i]->series->model->brand->name }} </li>
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
                                                onclick="addtoInvoice(this,{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }})">Add</button>
                                        </td>

                                    </tr>
                                    @elseif ($item['type_id'] == 6) <!--kit-->
                                        <tr>
                                        
                                        @php
                                            $imgName = '';
                                            if (count($item['image']) > 0) {
                                                $imgName = $item['image'][0]->image_name;
                                            }
                                        @endphp
                                        <td><img class="rounded-circle header-profile-user"
                                                src="assets/part_images/{{ $imgName }}" alt="Emara">
                                            <span
                                                class="barcodeTxt d-none ">{{ $item['type_id'] }}-{{ $item['p_data']->id }}-{{ $item['source'][0]->id }}-{{ $item['status'][0]->id }}-{{ $item['quality'][0]->id }}</span>
                                            <svg class="barcode d-none">

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
                                                    <li onclick="">{{ $item['p_data']->part_numbers[$i]->number }}</li>
                                                @endfor
                                            @else
                                                <li>لا يوجد ارقام</li>
                                            @endif
                                        </td>
                                        {{-- <td>{{ $item['source'][0]->name_arabic }}</td>
                                        <td>{{ $item['status'][0]->name }}</td> --}}
                                        <td>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}</td>
                                        <td>{{ $item['Tamount'] }}</td>
                                        <td>
                                            @if (isset($item['section']))
                                                @for ($i = 0; $i < count($item['section']); $i++)
                                                    <li>{{ $item['section'][$i]->store_structure->name }}</li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
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
                                                    <li> {{ $item['models'][$i]->series->model->brand->name }} </li>
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
                                                onclick="addtoInvoice(this,{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }})">Add</button>
                                        </td>

                                    </tr>
                                    @elseif ($item['type_id'] == 3) <!--tractor-->
                                        <tr>
                                        
                                        @php
                                            $imgName = '';
                                            if (count($item['image']) > 0) {
                                                $imgName = $item['image'][0]->url;
                                            }
                                        @endphp
                                        <td><img class="rounded-circle header-profile-user"
                                                src="assets/tractor_images/{{ $imgName }}" alt="Emara">
                                            <span
                                                class="barcodeTxt d-none ">{{ $item['type_id'] }}-{{ $item['p_data']->id }}-{{ $item['source'][0]->id }}-{{ $item['status'][0]->id }}-{{ $item['quality'][0]->id }}</span>
                                            <svg class="barcode d-none">

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
                                                    <li onclick="">{{ $item['p_data']->part_numbers[$i]->number }}</li>
                                                @endfor
                                            @else
                                                <li>لا يوجد ارقام</li>
                                            @endif
                                        </td>
                                        {{-- <td>{{ $item['source'][0]->name_arabic }}</td>
                                        <td>{{ $item['status'][0]->name }}</td> --}}
                                        <td>{{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }}</td>
                                        <td>{{ $item['Tamount'] }}</td>
                                        <td>
                                            @if (isset($item['section']))
                                                @for ($i = 0; $i < count($item['section']); $i++)
                                                    <li>{{ $item['section'][$i]->store_structure->name }}</li>
                                                @endfor
                                            @else
                                                <li>لا يوجد</li>
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
                                                    <li> {{ $item['models'][$i]->series->model->brand->name }} </li>
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
                                                onclick="addtoInvoice(this,{{ $item['part_id'] }},'{{ $item['p_data']->name }} ', {{ $item['source'][0]->id }} , {{ $item['status'][0]->id }} , {{ $item['quality'][0]->id }} ,{{ isset($item['price'][0]->sale_type) ? $item['price'][0]->sale_type : 0 }} , {{ isset($item['price'][0]->price) ? $item['price'][0]->price : 0 }},{{ $item['Tamount'] }},{{ $item['type_id'] }})">Add</button>
                                        </td>

                                    </tr>
                                    @endif
                                @endforeach
                                {{-- <div id="divOverlay" style=""><p>Overlay Row</p></div> --}}
                            </tbody>
                        </table>
                    </div>
                    <div class="col card p-2  m-1">
                        <h3 class="text-center ">فــاتورة البيع</h3>
                        <form action="/printpos" method="POST">
                            @csrf
                            <input type="hidden" name="storeId" value="{{ $store_data[0]->id }}">
                            <div class="row">
                                <div class="col-8">
                                    <select name="client" id="clientSlct" required="form-control">
                                        <option selected disabled value="NEW">عميل</option>
                                        @foreach ($clients as $client)
                                            <option
                                                data-mad="{{ $client->invoices->sum('actual_price') - $client->invoices->sum('paied') - $client->invoices->sum('discount') - $client->invoice_client_madyoneas->sum('paied') }}"
                                                value="{{ $client->id }}">{{ $client->name }} /
                                                {{ $client->tel01 }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4 text-end">
                                    <span id="madClientTxt">00</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col table-responsive">
                                    <table class="table" id="invoiceItems">
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
                                                        <label class="btn bg-light text-nowrap"
                                                            style="font-size: 10px">
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
                                        <div class="col-lg-4 text-end">
                                            <span>Total : </span>
                                            <span id="total" class=" rounded bg-light">0</span>
                                            <input type="hidden" name="total" id="totaltxt" value="0">
                                        </div>
                                        <div class="col-lg-4 text-end">
                                            <span>Taxes : </span>
                                            <span id="taxval">0</span>
                                            <input type="hidden" name="taxval" id="taxvaltxt" value="0">

                                        </div>
                                        <div class="col-lg-4 text-end">
                                            <span>SubTotal :</span>
                                            <span id="subtotal">0</span>
                                            <input type="hidden" name="subtotal" id="subtotaltxt" value="0">
                                        </div>
                                    </div>

                                    <div class="row">

                                    </div>
                                    <hr>
                                    <div class="row" id="cashpay">

                                        <div class="col mb-3">
                                            <label for="" class="form-label">paied</label>
                                            <input type="text" class="form-control" name="invPaied"
                                                required="" id="invPaied" aria-describedby="helpId"
                                                placeholder="">

                                        </div>
                                        <div class="col mb-3">
                                            <label for="" class="form-label"> Discount ( بالجنية )</label>
                                            <input type="text" class="form-control" name="invDiscount"
                                                value="0" id="invDiscount" aria-describedby="helpId"
                                                placeholder="">


                                        </div>
                                        <div class="col mb-3">
                                            <label for="" class="form-label">Remain ( مديونية ) </label>

                                            <input type="text" class="form-control" readonly="" name="invMad"
                                                value="0" id="invMad" aria-describedby="helpId"
                                                placeholder="">

                                        </div>

                                    </div>

                                </div>
                            </div>
                            <button class="btn btn-success w-100">Save</button>
                        </form>
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
                        <h5 class="modal-title" id="sectionMdlLabel">توزيع المخزن</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="search" class="form-control mb-2" name="" id="searchSectionTxt">
                        <div class="row">
                            @foreach ($allSections as $section)
                                <div class="col-3 resSec">
                                    <h3 class="text-bg-dark text-center">{{ $section->name }}</h3>
                                    <table class="table">
                                        @foreach ($section->store_sections as $item)
                                            <tr>
                                                <td>{{ $item->part->name }}</td>
                                                <td>{{ $item->amount }}</td>
                                            </tr>
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
                                            {{-- <td>
                                            <button type="button" class="btn btn-info">سداد مديونية </button>
                                        </td> --}}

                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        {{-- <button type="button" class="btn btn-primary">save</button> --}}
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
                        <h5 class="modal-title" id="inboxMdlLabel">الــــــوارد

                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="button" value="Accept All" class="btn btn-success" id="accept_all_inbox">
                            <input type="button" value="Preview All" class="btn btn-primary" id="accept_preview">
                            <div class="table-responsive">
                                <div class="table-responsive">
                                    <table id="transtbl" name=""
                                        class="table text-center text-nowrap table-bordered table-striped">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>storeLogId</th>
                                                <th>PartID</th>
                                                <th>AllPartID</th>
                                                <th>Part</th>
                                                <th>Source</th>
                                                <th>SourceID</th>
                                                <th>Status</th>
                                                <th>StatusID</th>
                                                <th>Quality</th>
                                                <th>QualityID</th>

                                                <th>Type </th>
                                                <th>TypeId </th>
                                                <th>Store_action </th>
                                                <th>StoreActionId </th>
                                                <th>Amount </th>
                                                <th>Store </th>
                                                <th>SrtorId </th>
                                                <th>date </th>
                                                <th>sublier </th>
                                                <th>status </th>
                                                <th>Notes</th>
                                                <th>Enter Amount</th>
                                                <th>Save</th>

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
                        <h5 class="modal-title" id="storeMdlLabel">الــــــوارد

                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {{-- <input type="button" value="Accept All" class="btn btn-success" id="accept_all_inbox">
                        <input type="button" value="Preview All" class="btn btn-primary" id="accept_preview"> --}}
                            <div class="table-responsive">
                                <div class="table-responsive">
                                    <table id="storetbl"
                                        name=""class="table text-center text-nowrap table-bordered table-striped"
                                        style="width:100% !important">
                                        <thead class="thead-dark">
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
                                                <th>Ask </th>


                                            </tr>
                                        </thead>
                                        <tbody id="parts_details_edit1">
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
                <div class="modal-header bg-soft-danger">
                    <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="" id="filterForm" action="#">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col">
                                <h3>النـــوع : </h3>
                                <select onchange="DTsearch(14,this)" id="" class="form-select" name=""
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
                                <input onkeyup="DTsearch(1 ,this)" id="FilterNametxt" type="text" name=""
                                    class="form-control" id="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h4>الرقم : </h4>
                                <input onkeyup="DTsearch(2 ,this)" id="FilterNumbertxt" type="text" name=""
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
                                <select onchange="DTsearch(7,this)" id="FilterGroupSlct" class="form-select"
                                    name="" id="">
                                    <option value="" selected disabled>Select</option>
                                    <option value="" >All</option>
                                    @foreach ( $allGroups as $group)
                                        <option value="{{$group->name}}" >{{ $group->name }}</option>
                                    @endforeach
                                    
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h4>Sub Groups : </h4>
                                <select onchange="DTsearch(6,this)" id="FilterSGroupSlct" class="form-select"
                                    name="" id="">
                                    <option value="" selected disabled>Select</option>
                                    <option value="" >All</option>
                                    @foreach ( $allSGroups as $sgroup)
                                        <option value="{{$sgroup->name}}" >{{ $sgroup->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h4>Brand Type : </h4>
                                <select onchange="DTsearch(11,this)" id="FilterBtypeSlct" class="form-select"
                                    name="" id="">
                                    <option value="" selected disabled>Select</option>
                                    <option value="" >All</option>
                                    @foreach ( $Btype as $ty)
                                        <option value="{{$ty->name}}" >{{ $ty->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h4>Brand : </h4>
                                <select onchange="DTsearch(10,this)" id="FilterBrandSlct" class="form-select"
                                    name="" id="">
                                    <option value="" selected disabled>Select</option>
                                    <option value="" >All</option>
                                    @foreach ( $allbrand as $br)
                                        <option value="{{$br->name}}" >{{ $br->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h4>Model : </h4>
                                <select onchange="DTsearch(9,this)" id="FilterModelSlct" class="form-select"
                                    name="" id="">
                                    <option value="" selected disabled>Select</option>
                                    <option value="" >All</option>
                                    @foreach ( $allmodel as $md)
                                        <option value="{{$md->name}}" >{{ $md->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h4>Series : </h4>
                                <select onchange="DTsearch(8,this)" id="FilterSeriesSlct" class="form-select"
                                    name="" id="">
                                    <option value="" selected disabled>Select</option>
                                    <option value="" >All</option>
                                    @foreach ( $allseries as $ser)
                                        <option value="{{$ser->name}}" >{{ $ser->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" onclick="this.form.reset();$('#example11Modal select').val(-1).trigger('change');partsDt.columns().search('').draw();" class="btn btn-info" >بحث جديد</button>
                        <button type="button" class="btn btn-primary" onclick="partsDt.columns().search('').draw();">الغاء البحث</button>
                    </div>
                </form>
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
    
     <div class="modal fade" id="infoMdl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="infoMdlLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 80vw !important;">
          <div class="modal-content w-100" style="width: 100%!important">
            <div class="modal-header ">
              <h5 class="modal-title" id="infoMdlLabel">Info</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                      <p class="card-text" >Price: $ <span id="itemPrice">0</span></p>
                      <p class="card-text">Status: <span id="itemStock">0</span></p>
                      <p class="card-text" id="itemGroup">Availability: Ships within 2-3 business days</p>
                      {{-- <a href="#" class="btn btn-primary">Add to Cart</a> --}}
                    </div>
                    <div id="productCarousel" class="carousel slide d-inline-block w-50" data-bs-ride="carousel">
                        <div class="carousel-inner" id="item-image-car">
                         
                        </div>
                        <a class="carousel-control-prev " href="#productCarousel" role="button" data-bs-slide="prev">
                          <span class="carousel-control-prev-icon text-bg-dark" aria-hidden="true"></span>
                          <span class="visually-hidden">Previous</span>
                        </a>
                        <a class="carousel-control-next " href="#productCarousel" role="button" data-bs-slide="next">
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
                              <button class="nav-link " id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">ITEM APPLICATIONS</button>
                              <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">FLIP CODES</button>
                              <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Specifications</button>
                              <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-4" type="button" role="tab" aria-controls="nav-4" aria-selected="false">Stores</button>
                              <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-5" type="button" role="tab" aria-controls="nav-5" aria-selected="false">Price</button>
                              <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-15" type="button" role="tab" aria-controls="nav-15" aria-selected="false">^</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade " id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                <div class="row">
                                    <div class="col">
                                        <ul id="itemModels" class="list-group-horizontal">

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                <div class="row">
                                    <div class="col">
                                        <ul id="itemNumbers" class="mt-3 list-group list-group-horizontal position-relative overflow-auto w-75">
                                            <li>No Numbers</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                                <div class="row">
                                    <div class="col">
                                        <ul id="itemSpecs" class="mt-3  list-group">
                                            <li>No Specs</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-4" role="tabpanel" aria-labelledby="nav-contact-tab">
                                <div class="row">
                                    <div class="col">
                                        <ul id="itemStores" class="mt-3  list-group">
                                            <li>No Stocks</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-5" role="tabpanel" aria-labelledby="nav-contact-tab">
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
                <h2 onclick="$('#relatedpartDiv').toggle()" style="cursor: pointer;" class="rounded-4 text-bg-dark text-center">Suggestion part</h2>
                <div class="row" id="relatedpartDiv" style="display: none;">
                    
                </div>
                <h2 onclick="$('#relatedpartDiv1').toggle()" style="cursor: pointer;" class="rounded-4 text-bg-primary text-center">Related part</h2>
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
    
    
    
    <!--<script src="{{ URL::asset('js/app.js') }}"></script>-->
    <!--<script src="{{ mix('js/app.js') }}"></script>-->
    {{-- ////////////////////////////////////////////////////////////////// --}}
    <script src="{{ URL::asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ URL::asset('assets/js/jquery-3.5.1.js') }}"></script>
    {{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"></script>
    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <script src={{ URL::asset('assets/js/barcode/dbr.js') }}></script>
    <script>
        var store_data = {!! $store_data !!};
        // console.log(store_data);
        var partsDt;
        var table = "";
        var table2 = "";
        $(document).ready(function() {
            $('#clientSlct').select2({
                tags: true
            });
            /***************************************************************************/

            /*******************************************************************************/

            $('#example thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#example thead');
            //Only needed for the filename of export files.
            //Normally set in the title tag of your page.

            document.title = "Parts";

            partsDt = $("#example").DataTable({
                dom: '<"dt-buttons"Bf><"clear">lirtp',
                paging: false,
                // autoWidth: true,
                orderCellsTop: true,
                fixedHeader: true,
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
                initComplete: function(settings, json) {
                    $(".dt-buttons .btn-group").append(
                        '<a id="cv" class="btn bg-light" href="#">Card View</a>'
                    );
                    $(".dt-buttons .btn-group").append(
                        `<button type="button" class="btn btn-primary" onclick="partsDt.columns().search('').draw();">الغاء البحث</button>`
                    );
                    $("#cv").on("click", function() {
                        if ($("#example").hasClass("card")) {
                            $(".colHeader").remove();
                        } else {
                            var labels = [];
                            $("#example thead th").each(function() {
                                labels.push($(this).text());
                            });
                            $("#example tbody tr").each(function() {
                                $(this)
                                    .find("td")
                                    .each(function(column) {
                                        $("<span class='colHeader'>").prependTo(
                                            $(this)
                                        );
                                    });
                            });
                        }
                        $("#example").toggleClass("card");
                    });


                    var api = this.api();

                    // For each column
                    api.columns().eq(0).each(function(colIdx) {
                        // Set the header cell to contain the input element
                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );

                        var title = $(cell).text();
                        $(cell).html('<input type="text" placeholder="' + title + '" />');

                        // On every keypress in this input
                        $(
                                'input',
                                $('.filters th').eq($(api.column(colIdx).header()).index())
                            )
                            .off('keyup change')
                            .on('change', function(e) {
                                // Get the search value
                                $(this).attr('title', $(this).val());
                                var regexr =
                                    '({search})'; //$(this).parents('th').find('select').val();

                                var cursorPosition = this.selectionStart;
                                // Search the column for that value
                                api
                                    .column(colIdx)
                                    .search(
                                        this.value != '' ?
                                        regexr.replace('{search}', '(((' + this.value +
                                            ')))') :
                                        '',
                                        this.value != '',
                                        this.value == ''
                                    )
                                    .draw();
                            })
                            .on('keyup', function(e) {
                                e.stopPropagation();

                                $(this).trigger('change');
                                $(this)
                                    .focus()[0]
                                    .setSelectionRange(cursorPosition, cursorPosition);
                            });
                    })




                },
                "columnDefs": [{
                        "visible": false,
                        "targets": 0
                    },
                    {
                        "visible": false,
                        "targets": 2
                    },
                    {
                        "visible": false,
                        "targets": 5
                    },
                    {
                        "visible": false,
                        "targets": 6
                    },
                    {
                        "visible": false,
                        "targets": 7
                    },
                    {
                        "visible": false,
                        "targets": 8
                    },
                    {
                        "visible": false,
                        "targets": 9
                    },
                    {
                        "visible": false,
                        "targets": 10
                    },
                    {
                        "visible": false,
                        "targets": 11
                    },
                    {
                        "visible": false,
                        "targets": 12
                    },
                    {
                        "visible": false,
                        "targets": 13
                    },
                    {
                        "visible": false,
                        "targets": 14
                    }
                ]

            });


            /**************************************************************************/
            /////////////////////////////////////////////////////////////////////////////

            table = $('#transtbl').DataTable({
                dom: '<"dt-buttons"Bf><"clear">lirtp',
                paging: false,
                autoWidth: true,
                orderCellsTop: true,
                fixedHeader: true,
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
                ajax: "inboxStore/list/" + store_data[0].id,
                // async:false,
                columns: [

                    {
                        data: "stores_log_id",
                        "visible": false
                    },
                    {
                        data: 'part_id',
                        name: 'part_id',
                        "visible": false
                    },
                    {
                        data: 'All_part_id',
                        name: 'All_part_id',
                        "visible": false
                    },

                    {
                        data: 'part_name',
                        name: 'part_name'
                    },
                    {
                        data: 'source_name',
                        name: 'source_name'
                    },
                    {
                        data: 'source_id',
                        name: 'source_id',
                        "visible": false
                    },
                    {
                        data: 'staus_name',
                        name: 'staus_name'
                    },
                    {
                        data: 'status_id',
                        name: 'status_id',
                        "visible": false
                    },
                    {
                        data: 'quality_name',
                        name: 'quality_name'
                    },


                    {
                        data: 'quality_id',
                        name: 'quality_id',
                        "visible": false
                    },

                    {
                        data: 'type.name',
                        name: 'type_name'
                    },


                    {
                        data: 'type.id',
                        name: 'type_id',
                        "visible": false
                    },

                    {
                        data: 'store_action.name',
                        name: 'store_action_name'
                    },
                    {
                        data: 'store_action.id',
                        name: 'store_action_id',
                        "visible": false
                    },
                    {
                        data: 'trans_amount',
                        name: 'trans_amount'
                    },

                    {
                        data: 'store.name',
                        name: 'to_store'
                    },
                    {
                        data: 'store.id',
                        name: 'to_store_id',
                        "visible": false
                    },
                    {
                        data: 'date',
                        name: 'trans_date',
                        render: function(data, type, row) {

                            return data.split('T')[0];

                        }
                    },
                    {
                        data: 'sup_name',
                        name: 'sup_name'
                    },
                    {
                        data: "status",
                        name: 'status',
                        defaultContent: "-",


                        render: function(data, type, row) {
                            if (type === 'display' || type === 'filter') {

                                var statusword = '';
                                var statusclass = '';
                                // alert(data);
                                switch (data) {

                                    case -1:
                                        statusword = 'مرفوضة';
                                        statusclass = 'light-dark';
                                        break;
                                    case 0:
                                        statusword = 'منتظر إستلام';
                                        statusclass = 'light-dark';
                                        break;
                                    case 1:
                                        statusword = 'تم الإستلام';
                                        statusclass = 'light-dark';
                                        break;
                                    case 2:
                                        statusword = 'منتظر تأكيد';
                                        statusclass = 'light-dark';
                                        break;

                                }


                                return '<span name="statusword" class = "badge badge-pill bg-success badge-' +
                                    statusclass + '">' + statusword + '</span>';

                            }
                            return data;
                        }
                    },
                    {
                        data: 'notes',
                        name: 'notes'
                    },
                    {
                        data: null,
                        className: "dt-center editor-edit1 ",
                        defaultContent: '<input  type="number" name="acceptedamount" class="form-controle acceptedamount bind_this" placeholder="الكمية">',
                        orderable: false
                    },
                    {
                        // data: null,
                        // className: "text-center",

                        // render: function(data, type, row) {
                        //     data = `
                    //     <a href="javascript:void(0)" class="edit btn btn-success btn-sm" onclick="Confirm_transaction(this)">Save</a>
                    //     `;
                        //     return data;
                        // }
                        data: null,
                        className: "dt-center editor-edit",
                        defaultContent: ' <a href="javascript:void(0)" class="edit btn btn-success btn-sm" onclick="Confirm_transaction(this)">Save</a>',
                        orderable: false


                    },



                ]

            });

            table2 = $('#storetbl').DataTable({
                dom: '<"dt-buttons"Bf><"clear">lirtp',
                paging: false,
                autoWidth: true,
                orderCellsTop: true,
                fixedHeader: true,
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
                ajax: "itemsStore/list/" + store_data[0].id,
                // async:false,
                columns: [


                    {
                        data: "part_id",
                        "visible": false
                    },
                    {
                        data: "store_log_id",
                        "visible": false
                    },
                    {
                        data: "supplier_order_id",
                        "visible": false
                    },
                    {
                        data: "type_id",
                        "visible": false
                    },
                    // { data: "All_part_id", "visible": false },
                    {
                        data: "p_data.name"
                    },
                    {
                        data: "type_N"
                    },
                    {
                        data: "source[0].name_arabic"
                    },
                    {
                        data: "source_id",
                        "visible": false
                    },
                    {
                        data: "status[0].name"
                    },
                    {
                        data: "status_id",
                        "visible": false
                    },
                    {
                        data: "quality[0].name"
                    },
                    {
                        data: "quality_id",
                        "visible": false
                    },
                    {
                        data: "Tamount"
                    },
                    {
                        data: null,
                        className: "dt-center editor-send1 ",
                        defaultContent: '<input  type="number" id="" class="form-controle send_amount" placeholder="الكمية">',
                        orderable: false
                    },
                    {
                        data: null,
                        className: "dt-center",
                        defaultContent: '<select class="form-control m-b select2  stores_namedrp" id="" name=""><option>Select Store</option></select>',
                        render: function(data, type, row) {
                            if (type === 'display' || type === 'filter') {

                                store_drp = [];
                                store_drp.push(`<option value=''>Select Store</option>`)

                                for (let i = 0; i < allStores.length; i++) {
                                    if (allStores[i].id == store_data[0].id) {

                                    } else {
                                        store_drp.push(
                                            `<option value="${allStores[i].id}">${allStores[i].name}</option>`
                                        )

                                    }
                                }
                                $('.stores_namedrp').html(store_drp);

                                // $('.stores_namedrp').select2();

                            }
                            return data;
                        }
                    },

                    {
                        data: null,
                        className: "dt-center editor-send",

                        defaultContent: '<a href="javascript:void(0)" class="btn-info btn btn-xs " onclick="send_to_other(this)">Send</button>',
                        orderable: false
                    },
                    {
                        data: null,
                        className: "dt-center editor-send",
                        defaultContent: '<button  type="button" id="" class="btn-success btn btn-xs ">Need</button>',
                        orderable: false
                    }


                ]

            });


        });
        var allStores = {!! $allStores !!}

        function send_to_other(el) {

            var row_clicked = $(el).closest('tr');
            var row_object = table2.row(row_clicked).data();
            console.log(row_object)
            // var row_accept_amount = table.row(row_clicked).node();
            var row_accept_amount = parseInt(row_clicked.find('.send_amount').val());
            var stores_namedrp = row_clicked.find('.stores_namedrp').val();
            var actual_amount = parseInt(row_object.Tamount);

            if (row_accept_amount <= actual_amount) {
                if (stores_namedrp != '') {

                    flages = 1;
                    Swal.fire('Transaction Accepted');
                    send_to_other_store(row_object, row_accept_amount, stores_namedrp);

                } else {
                    flages = 2;
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'wrong Store',
                        footer: 'Select Store First'
                    });
                }



            } else {
                flages = 2;
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'wrong amount',
                    footer: 'Enter Right Value In amount'
                })

            }
            // if (flages != 2) {
            //     if (row_object.store_action_id == 1 || row_object.store_action_id == 3 && row_object.status == 2) {
            //         $.ajax({
            //             headers: {
            //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //             },
            //             type: "POST",
            //             url: "{{ route('confirmStore') }}",
            //             data: {
            //                 'data': {
            //                     'Store_log_id': row_object.stores_log_id,
            //                     'store_table_name': row_object.store.table_name,
            //                     'store_action_id': row_object.store_action_id,
            //                     'all_p_id': row_object.All_part_id,
            //                     'accamount': row_accept_amount,
            //                     'type_id': row_object.type_id,
            //                     'store_id': row_object.store_id,
            //                     'flag_completed': flages,
            //                     'actual_amount': actual_amount
            //                 }



            //             },
            //             datatype: 'JSON',
            //             statusCode: {
            //                 404: function() {
            //                     alert("page not found");
            //                 }
            //             },
            //             error: function(XMLHttpRequest, textStatus, errorThrown) {
            //                 // alert("some error");
            //                 console.log(errorThrown);
            //             },
            //             success: function(data) {
            //                 console.log(data);

            //                 if (data) {
            //                     $('#transtbl').DataTable().ajax.reload();
            //                     // $('#example').DataTable().ajax.reload();
            //                 }
            //                 // SuccessAlert("Transaction Accepted" )

            //                 // getAllNewParts();
            //                 // getAllNewParts_inbox();
            //                 // $('.nav-tabs a[href="#tab-3"]').tab('show');

            //                 // var resArr1 = $.parseJSON(data);
            //                 // console.log(resArr1);

            //                 // arr_partNo_html = draw(resArr1['Last_Amount'], 'Last_Amount');
            //                 // $(".Last_Amount").html(arr_partNo_html);


            //             }
            //         });
            //     } else {
            //         alert("xxxxxxx");
            //     }
            // }




        }

        function send_to_other_store(P_details, sent_amount, other_store_id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "{{ route('sendToOtherStore') }}",
                data: {
                    'data': {
                        'P_details': P_details,
                        'sent_amount': sent_amount,
                        'other_store_id': other_store_id,
                        'store_id': store_data[0].id

                    }

                },
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
                    if (data == "empty") {

                    } else {
                        // resArr0 = $.parseJSON(data);


                        // inbox_amount = resArr0;
                        // $(".inbox_store").html(inbox_amount.length)
                        // console.log(resArr0);
                        // partStoreDT.clear();
                        // partStoreDT.rows.add(inbox_amount);
                        // partStoreDT.draw();
                    }




                    // resArr[i].id
                    // $(".notification .badge").html(notifications[0].notifications)





                }
            });
        }

        var flages = '';
        var store_idx;

        function Confirm_transaction(el) {

            var row_clicked = $(el).closest('tr');
            var row_object = table.row(row_clicked).data();
            // console.log(row_object.trans_amount)
            var row_accept_amount = table.row(row_clicked).node();
            var row_accept_amount = parseInt(row_clicked.find('.acceptedamount').val());
            var actual_amount = parseInt(row_object.trans_amount);
            store_idx = row_object.store_id;
            if (row_accept_amount == actual_amount) {
                flages = 1;
                Swal.fire('Transaction Accepted')

            } else if (row_accept_amount < actual_amount) {
                flages = 0;
                Swal.fire('Transaction Not Completed')
                Swal.fire(
                    'Warning !',
                    'Transaction Not Completed',
                    'The transaction still pendding ...'
                )

            } else {
                flages = 2;
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'wrong amount',
                    footer: 'Enter  Value In amount'
                })

            }
            if (flages != 2) {
                if (row_object.store_action_id == 1 || row_object.store_action_id == 3 && row_object.status == 0) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "{{ route('confirmStore') }}",
                        data: {
                            'data': {
                                'Store_log_id': row_object.stores_log_id,
                                'store_table_name': row_object.store.table_name,
                                'store_action_id': row_object.store_action_id,
                                'all_p_id': row_object.All_part_id,
                                'accamount': row_accept_amount,
                                'type_id': row_object.type_id,
                                'store_id': row_object.store_id,
                                'flag_completed': flages,
                                'actual_amount': actual_amount
                            }



                        },
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

                            if (data) {
                                $('#transtbl').DataTable().ajax.reload();
                                // $('#example').DataTable().ajax.reload();
                            }
                            // SuccessAlert("Transaction Accepted" )

                            // getAllNewParts();
                            // getAllNewParts_inbox();
                            // $('.nav-tabs a[href="#tab-3"]').tab('show');

                            // var resArr1 = $.parseJSON(data);
                            // console.log(resArr1);

                            // arr_partNo_html = draw(resArr1['Last_Amount'], 'Last_Amount');
                            // $(".Last_Amount").html(arr_partNo_html);


                        }
                    });
                } else {
                    alert("xxxxxxx");
                }
            }




        }

        $('#example tbody tr').each(function() {
            JsBarcode($(this).find('.barcode')[0], $(this).find('.barcodeTxt').text());
        })

        function addtoInvoice(el, partId, name, SourceId, StatusId, qualityId, PriceTypeId, price, totalAmount, type_id) {
            $(el).addClass('d-none');
            var row = $(el).closest('tr');
            var indexRw = partsDt.row(row).index();
            $("#invoiceItems ").append(`
                <tr data-val="${type_id}-${partId}-${SourceId}-${StatusId}-${qualityId}">



                    <td style="">${name}</td>
                    <td><input name="itemAmount[]"  class="form-control itemAmount p-1 text-center w-50" type="number" name="" value="1" min="1"  max="${totalAmount}" id=""></td>

                    <td class="itemPrice">${price}</td>
                    <td class="itemTotalPrice">${price}</td>
                    <td onclick="removeItemFromInvoice(${indexRw},this)"><i class="ri-recycle-fill rounded text-bg-danger"></i></td>
                </tr>
                <input type="hidden" name="items_part[]" value="${partId}-${SourceId}-${StatusId}-${qualityId}-${type_id}">
                <input type="hidden" name="pricetype[]" value="${PriceTypeId}">
                `);

            calcTotal();

        }
        $(document).on('keyup', '.itemAmount', function(e) {
            var amount = $(this).val();;
            $(this).closest('tr').find('.itemTotalPrice').text(amount * $(this).closest('tr').find('.itemPrice')
                .text());
            calcTotal();
        });

        function removeItemFromInvoice(rowindex, el) {
            $(el).closest('tr').remove();
            $(partsDt.row(rowindex).nodes()[0]).find('.addBtn').removeClass('d-none');
            calcTotal();
        }
        $('[name="taxes[]"]').change(function() {
            calcTotal();
        })

        function calcTotal() {

            var subtotal = 0;
            var taxval = 0;
            var total = 0;

            $('#invoiceItems > tr').each(function() {
                let price = parseFloat($(this).children().eq(3).html()) || 0;
                subtotal += price;
            });

            $("input[name='taxes[]']").each(function(index, obj) {
                if ($(obj).is(':checked')) {
                    var tax = parseFloat($(obj).val());
                    var subtax = subtotal * tax / 100;
                    taxval += subtax;

                }
            });
            $("#subtotal").text(subtotal);
            $("#taxval").text(taxval);
            $("#total").text(Math.round(subtotal + taxval));

            $("#subtotaltxt").val(subtotal);
            $("#taxvaltxt").val(taxval);
            $("#totaltxt").val(Math.round(subtotal + taxval));

        }
        $("#invPaied").keyup(function(e) {
            var invPaied = $("#invPaied").val();
            var invTotal = $("#total").text();

            $("#invMad").val((invTotal - invPaied).toFixed(2));
        });

        $("#invDiscount").keyup(function(e) {
            var invPaied = $("#invPaied").val();
            var invTotal = $("#total").text();
            var invdiscount = $("#invDiscount").val();

            $("#invMad").val((invTotal - invPaied - invdiscount).toFixed(2));
        });

        function toggleFullScreen() {
            if (!document.fullscreenElement && // alternative standard method
                !document.mozFullScreenElement && !document.webkitFullscreenElement) { // current working methods
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                    document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
            } else {
                if (document.cancelFullScreen) {
                    document.cancelFullScreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                }
            }
        }

        $("#gardBtn").on("click", function() {
            partsDt.button('.buttons-print').trigger();
        });
        //
        $("#searchSectionTxt").keyup(function(e) {
            var searchValue = $(this).val();
            $('.resSec').hide();
            $('.resSec:contains("' + searchValue + '")').show();
        });
        $("#searchclientTxt").keyup(function(e) {
            var searchValue = $(this).val();
            $('.resclient').hide();
            $('.resclient:contains("' + searchValue + '")').show();
        });
        $("#newClient").click(function(e) {
            e.preventDefault();


        });

        function addNewClient(telNumber) {
            return $.ajax({
                type: "get",
                url: "newClientInline/" + telNumber
            });

        }
        $("#clientSlct").change(function(e) {
            e.preventDefault();

            $("#madClientTxt").text($(this).find('option:selected').attr('data-mad'))
        });

        //         $(selectedBtn).trigger('click');
        //         // $(this).val('');
        //     }


        // });
        on_scanner() // init function

        function on_scanner() {
            let is_event = false; // for check just one event declaration
            let input = document.getElementById("input-to-fill");
            input.addEventListener("focus", function() {
                if (!is_event) {
                    is_event = true;
                    input.addEventListener("keypress", function(e) {
                        setTimeout(function() {
                            if (e.keyCode == 13) {
                                scanner(input.value); // use value as you need
                                input.select().empty();
                            }
                        }, 500)
                    })
                }
            });
            document.addEventListener("keypress", function(e) {
                if (e.target.tagName !== "INPUT") {
                    input.focus();
                }
            });
        }

        function scanner(value) {
            if (value == '') return;
            console.log(value)
            partsDt.search(value).draw();

            if (partsDt.rows({
                    search: 'applied'
                }).count() == 1) {
                if ($("#invoiceItems tr[data-val='" + value + "']").find('input').length > 0) {
                    var currentVal = $("#invoiceItems tr[data-val='" + value + "']").find('input').val()
                    $("#invoiceItems tr[data-val='" + value + "']").find('input').val(parseInt(currentVal) + 1).trigger(
                        "change").keyup();
                } else {
                    // var selectedBtn = partsDt.('tr', {"filter":"applied"}).data()[0];
                    var selectedBtn = partsDt.row('tr', {
                        "filter": "applied"
                    }).data()[6];
                    $(selectedBtn).trigger('click');
                }


                // $(this).val('');
            }
        }

        $("#FilterTypeSlct").change(function(e) {
            e.preventDefault();
            partsDt.search($(this).val()).draw();
        });

        function DTsearch(columnIndex, el) {
            partsDt.column(columnIndex).search($(el).val()).draw();
        }
        
        function CardInfo(allpartId){
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
                data: { "allpartId" : allpartId},
                success: function (response) {
                    console.log(response);
                    if(response.allpart.length > 0){
                        var item =response.allpart[0];
                        var name = item.part.name;
                        var part_images = item.part.part_images;
                        var part_status = item.status.name;
                        var part_source = item.source.name_arabic;
                        var part_quality = item.part_quality.name;
                        var part_price = item.price;
                        var part_stores = item.stores;

                        var itemNumbers=item.part.part_numbers;
                        var itemSpecs=item.part.part_details;
                        var itemModels = item.part.part_models;
                        var itemGroup = item.part.sub_group;
                        var Srelatedpart = item.part.all_parts;
                        var relatedpart = item.part.related_parts;

                        $("#itemName").text(name);
                        if(part_price.length > 0){
                            $("#itemPrice").text(part_price[0].price);
                        }else{
                            $("#itemPrice").text(0);
                        }
                    
                        $("#itemStock").html(`Out Of Stock <span class="dot float-start"></span>`);

                        if(itemGroup){
                            $("#itemGroup").text(`${itemGroup.group.name } / ${itemGroup.name } `);
                        }
                        $("#itemDesc").html(`<span>${part_source}</span> / <span>${part_status}</span>`);
                        if(part_quality == 'ORIGINAL'){
                            $("#itemQuality").html(`<img src="assets/part_images/original.png" style="width:100px;height:100px" class="">`);
                        }else{
                            $("#itemQuality").html(`<img src="assets/part_images/high-quality.png" style="width:100px;height:100px" class="">`);
                        }
                        
                        var storeStatus = 0;
                        part_stores.forEach(store => {
                            storeStatus +=store.storepartCount;
                        });
                        var addedSource =[];
                        var addedStatus =[];
                        var addedQuality =[];
                        if(relatedpart.length > 0){
                            relatedpart.forEach(part => {
                                if(part.part.part_images.length > 0){
                                    $("#relatedpartDiv1").append(`<div class="col text-center" style="max-height: 250px;">   
                                        <img src="assets/part_images/${part.part.part_images[0].image_name}"  class="card-img-top" style="max-height: 150px;width:150px" alt="Product Image">
                                        <div class="card-body">
                                            <p class="card-descs text-center">${part.part.name}  </p>
                                        </div>
                                    </div>`);
                                }else{
                                    $("#relatedpartDiv1").append(`<div class="col text-center" style="max-height: 250px;">   
                                        <img src="assets/part_images/tractor-solid.png"  class="card-img-top" style="max-height: 150px;width:150px" alt="Product Image">
                                        <div class="card-body">
                                            <p class="card-descs text-center">${part.part.name}  </p>
                                        </div>
                                    </div>`);
                                }
                                
                            })
                        }else{
                            $("#relatedpartDiv1").append('<p class="text-center">No Data To Preview<p>');
                        }
                        if(Srelatedpart.length > 1){
                            
                            Srelatedpart.forEach(part => {
                                var itPric = 0;
                                if(part.rprice.length > 0){
                                    itPric =part.rprice[0].price;
                                }
                                if( part.source_id != item.source_id && part.status_id != item.status_id && part.quality_id != item.quality_id ){
                                    if( jQuery.inArray(part.source_id, addedSource) == -1  && jQuery.inArray(part.status_id, addedStatus) == -1 && jQuery.inArray(part.quality_id, addedQuality) == -1 ){
                                        if(part_images.length > 0){
                                            $("#relatedpartDiv").append(`<div class="col text-center" style="max-height: 250px;">
                                                
                                                <img src="assets/part_images/${part_images[0].image_name}"  class="card-img-top" style="max-height: 150px;width:150px" alt="Product Image">
                                                <div class="card-body">
                                                    <p class="card-descs text-center">${item.part.name} </p>
                                                    <p class="card-descs text-center">${part.source.name_arabic} / ${part.status.name} / ${part.part_quality.name} </p>
                                                    <p class="card-price">$ ${itPric}</p>
                                                   
                                                    <a href="#" class="btn btn-primary d-none">Add to Cart</a>
                                                </div>
                                            </div>`);
                                        }else{
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
                        }else{
                            $("#relatedpartDiv").append('<p class="text-center">No Data To Preview<p>');
                        }
                        if(storeStatus > 0){
                            $("#itemStock").html(`Available <span class="dot bg-success  float-start"></span>`);
                        }else{
                            $("#itemStock").html(`Out Of Stock <span class="dot  float-start"></span>`);
                          
                        }
                        if(part_stores.length > 0){
                            part_stores.forEach(store => {
                                $("#itemStores").append(`<li class="list-group-item d-flex justify-content-between align-items-center">${store.name}<span class="badge bg-primary rounded-pill">${store.storepartCount}</span></li>`);
                            });
                            
                        }
                        if(part_price.length > 0){
                            part_price.forEach(price => {
                                $("#itemPriceList").append(`<li class="list-group-item d-flex justify-content-between align-items-center">${price.sale_type.type}<span class="badge bg-primary rounded-pill">${price.price}</span></li>`);
                            });
                            
                        }else{
                            $("#itemPriceList").append('<li>No Price List</li>');
                        }
                        if(part_images.length > 0){
                            part_images.forEach(img => {
                                $("#item-image-car").append(`<div class="carousel-item ">
                                        <img src="assets/part_images/${img.image_name}" class="d-block w-100" style="height:250px" alt="Product Image 1">
                                    </div>`);
                            });
                            $($(".carousel-item")[0]).addClass('active');
                        }else{
                            $("#item-image-car").append(`<div class="carousel-item active">
                                <img src="assets/part_images/tractor-solid.png" class="d-block w-100" style="height:150px" alt="Product Image 1">
                            </div>`);
                        }

                        if(itemNumbers.length > 0){
                            itemNumbers.forEach(num => {
                                $("#itemNumbers").append(`<li class="list-group-item">${num.number}</li>`);
                            });
                        }else{
                            $("#itemNumbers").append(`<li class="list-group-item">No Numbers</li>`);
                        }

                        
                        if(itemSpecs.length > 0){
                            itemSpecs.forEach(specs => {
                                $("#itemSpecs").append(`<li class="list-group-item d-flex justify-content-between align-items-center">${specs.part_spec.name}<span class="badge bg-primary rounded-pill">${specs.value}</span></li>`);
                            });
                        }else{
                            $("#itemSpecs").append(`<li class="list-group-item d-flex justify-content-between align-items-center">No Specs</li>`);
                        }
                        
                        if(itemModels.length > 0){
                            itemModels.forEach(mdl => {
                                $("#itemModels").append(`<li class="list-group-item ">
                                        <span>${mdl.series.model.brand_type.name}</span> -
                                        <span>${mdl.series.model.brand.name}</span> - 
                                        <span>${mdl.series.model.name}</span> -
                                        <span>${mdl.series.name}</span> 
                                    </li>`);
                            });
                        }else{
                            $("#itemModels").append(`<li class="list-group-item d-flex justify-content-between align-items-center">No Models</li>`);
                        }

                        $("#infoMdl").modal('show');
                    }else{
                        $("#infoMdl").modal('hide');
                    }
                }
            });
        }
    </script>



    <script>
        //salam code in pos
        var store_inbox = {!! $store_inbox !!};
        if (store_inbox) {
            $('#inboxCounterLbl').html(store_inbox.length);
            $('.kt_topbar_notifications_3').html();
            // draw_notification(store_inbox)
        }
        //    function draw_notification(data_arr) {
        //             // partStoreDT.clear();
        //             partStoreDT.rows.add(data_arr);
        //             partStoreDT.draw();
        // }
        
   
    </script>



    <script type="module">
                window.Echo.channel('StoreTransactionch')
                    .listen('.StoreTranaction', (e) => {
                    var arr_data2 = e['data'];
                    // alert( store_data[0]['id']);
                    // console.log(e['data']);
                    playAudio();
                    if(arr_data2[0]['store']['id']==store_data[0]['id']){
                        $('#inboxCounterLbl').html(arr_data2.length);
                    }



            });


            function playAudio() {
      var x = new Audio('http://127.0.0.1:8000/assets/noti.mp3');
      // Show loading animation.
      var playPromise = x.play();

      if (playPromise !== undefined) {
          playPromise.then(_ => {
                  x.play();
              })
              .catch(error => {
              });

      }
  }

  /***********************************SLIDER RANGE********************************************/
    function collision($div1, $div2) {
      var x1 = $div1.offset().left;
      var w1 = 40;
      var r1 = x1 + w1;
      var x2 = $div2.offset().left;
      var w2 = 40;
      var r2 = x2 + w2;
        
      if (r1 < x2 || x1 > r2) return false;
      return true;
      
    }
    
        // // slider call

    $('#slider').slider({
        range: true,
        min: 0,
        max: 500,
        values: [ 75, 300 ],
        slide: function(event, ui) {
            
            $('.ui-slider-handle:eq(0) .price-range-min').html('$' + ui.values[ 0 ]);
            $('.ui-slider-handle:eq(1) .price-range-max').html('$' + ui.values[ 1 ]);
            $('.price-range-both').html('<i>$' + ui.values[ 0 ] + ' - </i>$' + ui.values[ 1 ] );
            
            //
            
        if ( ui.values[0] == ui.values[1] ) {
        $('.price-range-both i').css('display', 'none');
        } else {
        $('.price-range-both i').css('display', 'inline');
        }
            
            //
            
            if (collision($('.price-range-min'), $('.price-range-max')) == true) {
                $('.price-range-min, .price-range-max').css('opacity', '0');	
                $('.price-range-both').css('display', 'block');		
            } else {
                $('.price-range-min, .price-range-max').css('opacity', '1');	
                $('.price-range-both').css('display', 'none');		
            }
            
        }
    });

    $('.ui-slider-range').append('<span class="price-range-both value"><i>$' + $('#slider').slider('values', 0 ) + ' - </i>' + $('#slider').slider('values', 1 ) + '</span>');

    $('.ui-slider-handle:eq(0)').append('<span class="price-range-min value">$' + $('#slider').slider('values', 0 ) + '</span>');

    $('.ui-slider-handle:eq(1)').append('<span class="price-range-max value">$' + $('#slider').slider('values', 1 ) + '</span>');

    /*************************************************************************************/

    $("#example11Modal select").select2({ dropdownParent: $('#example11Modal')})
    
    $('#example tbody').on( 'click', 'tr', function () {
        console.log( partsDt.row( this ).data() );
        // $('#partShowMdl').modal('toggle');
    } );
    
    
    
    
    
    </script>

</body>

</html>
