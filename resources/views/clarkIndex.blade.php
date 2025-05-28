@extends('layouts.master')
@section('css')



    <style>
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
            background-color: #4045ba;
            border-color: #4045ba;
            border-radius: 10px;
            line-height: 26px;
            font-size: 14px;
        }

        .upload__btn:hover {
            background-color: unset;
            color: #4045ba;
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

        .upload__img-wrapRelease {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .upload__img-wrapInvoice {
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

        .upload__img-close:after {
            content: "✖";
            font-size: 14px;
            color: white;
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

        .upload__img-close-edit:after {
            content: "✖";
            font-size: 14px;
            color: white;
        }

        .upload__img-close-efrag {
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

        .upload__img-close-efrag:after {
            content: "✖";
            font-size: 14px;
            color: white;
        }

        .upload__img-close-efrag-edit {
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

        .upload__img-close-efrag-edit:after {
            content: "✖";
            font-size: 14px;
            color: white;
        }

        .upload__img-close-invoice {
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

        .upload__img-close-invoice:after {
            content: "✖";
            font-size: 14px;
            color: white;
        }

        .upload__img-close-invoice-edit {
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

        .upload__img-close-invoice-edit:after {
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
    </style>
@endsection
@section('title')
    CLARKS
@stop


@section('content')


    <div class="main-content">
        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Clarks</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Clarks</li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-primary m-1 float-left" data-bs-toggle="modal" data-bs-target="#addClark">إضافة
                    </button>


                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-head">
                            <div class="row">

                            </div>
                            <div class="card-body">
                                <table id="clarksDT" class="table table-striped table-bordered cell-border "
                                    style="width:100%">
                                    <thead style="background:#5fcee78a">
                                        <tr>
                                            <th></th>
                                            <th class="text-center">الإسم</th>
                                            <th class="text-center sorting sorting_asc"> رقم الشاسية</th>
                                            <th class="text-center sorting sorting_asc">Clark Series</th>
                                            <th class="text-center sorting sorting_asc">Clark Brand</th>
                                            <th class="text-center">صور الكلارك</th>
                                            <th class="text-center">صور الإفراج </th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fs-18">


                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal fade" id="addClark" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">شراء كلارك جديد</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="{{ route('clark.store') }}" class="needs-validation" novalidate
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="model_id" name="model_id">
                        <input type="hidden" id="company_id" name="company_id" value="10">

                        <div class="modal-body">

                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="main-tab" data-bs-toggle="tab"
                                        data-bs-target="#main" type="button" role="tab" aria-controls="main"
                                        aria-selected="true">Main Data</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link d-none" id="numbers-tab" data-bs-toggle="tab"
                                        data-bs-target="#numbers" type="button" role="tab" aria-controls="numbers"
                                        aria-selected="false">Numbers</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs"
                                        type="button" role="tab" aria-controls="specs"
                                        aria-selected="false">Specs</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="brand-tab" data-bs-toggle="tab" data-bs-target="#brand"
                                        type="button" role="tab" aria-controls="brand"
                                        aria-selected="false">Brand</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="related-part-tab" data-bs-toggle="tab"
                                        data-bs-target="#related-part" type="button" role="tab"
                                        aria-controls="related-part" aria-selected="false">Related Parts</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="image-tab" data-bs-toggle="tab" data-bs-target="#image"
                                        type="button" role="tab" aria-controls="image"
                                        aria-selected="false">Images</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="efargImage-tab" data-bs-toggle="tab"
                                        data-bs-target="#efragImage" type="button" role="tab"
                                        aria-controls="efragImage" aria-selected="false">Release Images</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="invoiceImage-tab" data-bs-toggle="tab"
                                        data-bs-target="#invoiceImage" type="button" role="tab"
                                        aria-controls="invoiceImage" aria-selected="false">Invoice Images</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="invoicePrice-tab" data-bs-toggle="tab"
                                        data-bs-target="#invoicePrice" type="button" role="tab"
                                        aria-controls="invoicePrice" aria-selected="false">Invoice Price</button>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="invoiceSection-tab" data-bs-toggle="tab"
                                        data-bs-target="#invoiceSection" type="button" role="tab"
                                        aria-controls="invoiceSection" aria-selected="false">Place</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="main" role="tabpanel"
                                    aria-labelledby="main-tab">
                                    <div class="p-3 row">

                                        <div class="col-lg-3">
                                            <label for="">الإسم عربى </label>
                                            <input id="name" name="name" type="text"
                                                placeholder="Arabic Name" class="form-control" required>
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="">الإسم إنجليزى </label>
                                            <input id="eng_name" name="eng_name" type="text"
                                                placeholder="English Name" class="form-control" required>
                                        </div>
                                         <div class="col-lg-3">
                                            <label for="">رقم الشاسية </label>
                                            <input id="clark_number" name="clark_number" type="text"
                                                placeholder="Chassis No." class="form-control" required>
                                        </div>
                                        <div class="col-lg-3 form-group">
                                            <label for=""> الدفع</label>
                                            <select id="drive" name="drive" class="select2 required form-control">
                                            </select>
                                        </div>


                                    </div>


                                    <div class="p-3 row">
                                        <div class="col-lg-3 form-group">
                                            <label for=""> بلد المنشأ</label>
                                            <select id="source_id" name ="source_id"
                                                class="select2 required form-control" required>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 form-group">
                                            <label for=""> الحالة</label>
                                            <select id="status" name ="status" class="select2 required form-control"
                                                required>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 form-group">
                                            <label for=""> الجودة</label>
                                            <select id="quality_id" name="quality_id"
                                                class="select2 required form-control" required>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 form-group">
                                            <label for=""> ناقل الحركة</label>
                                            <select id="gear_box" name="gear_box" class="select2 required form-control">
                                            </select>
                                        </div>
                                    </div>

                                    <div class="p-3 row ">
                                        <div class="col-lg-3 ">
                                            <label for=""> سنة الصنع </label>
                                            <input id="year" name="year" type="number" placeholder="Year"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-lg-3 ">
                                            <label for="">عدد ساعات العمل </label>
                                            <input id="hours" name="hours" type="number" placeholder="Hours"
                                                class="form-control input-sm" required>
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="">سعة تانك الجرار </label>
                                            <input id="tank" name="tank" type="number" placeholder="Tank"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="">رقم موتور الجرار </label>
                                            <input id="motor_number" name="motor_number" type="text"
                                                placeholder="Motor Number" class="form-control input-sm" required>
                                        </div>

                                    </div>

                                    <div class="p-3 row">
                                        <div class="col-lg-3">
                                            <label for="">مقاس الكاوتش الأمامى </label>
                                            <select id="front_tire" name ="front_tire"
                                                class="select2 required form-control">
                                            </select>

                                        </div>
                                        <div class="col-lg-3">
                                            <label for=""> مقاس الكاوتش الخلفى</label>
                                            <select id="rear_tire" name="rear_tire"
                                                class="select2 required form-control">
                                            </select>
                                        </div>
                                        <div class="col-lg-3 bs-example form-group">
                                            <label for=""> حالة الكاوتش الأمامى </label>
                                            <input id="front_tire_status" name="front_tire_status" type="text"
                                                placeholder="Front tire status %" class="form-control">
                                        </div>
                                        <div class="col-lg-3 bs-example">
                                            <label for=""> حالة الكاوتش الخلفى </label>
                                            <input id="rear_tire_status" name="rear_tire_status" type="text"
                                                placeholder="Rear tire status %" class="form-control">

                                        </div>
                                    </div>

                                    <div class="p-3 row">
                                        <div class="col-lg-3">
                                            <label for=""> القدرة </label>
                                            <input id="power" name="power" type="text" placeholder="Power"
                                                class="form-control">
                                        </div>
                                        <div class="col-lg-3">
                                            <label for=""> الديسكات </label>
                                            <input id="discs" name="discs" type="text"
                                                placeholder="Clutch disk" class="form-control">
                                        </div>
                                        <div class="col-lg-3">
                                            <label for=""> اخر معاد للصيانة</label>
                                            <input id="serivcedate" name="serivcedate" type="date" placeholder=""
                                                class="form-control ">
                                        </div>
                                        <div class="col-lg-3">
                                            <label for=""> تاريخ الوصول</label>
                                            <input id="deliverydate" name="deliverydate" type="date" placeholder=""
                                                class="form-control " required>
                                        </div>
                                    </div>
                                    <div class="p-3 row">
                                        <div class="col-lg-3">
                                            <label for="">لون الكلارك </label>
                                            <input id="color" name="color" type="color" placeholder=""
                                                class="form-control input-sm">
                                        </div>
                                        <div class="col-lg-9">
                                            <label for=""> الوصف </label>

                                            <textarea id="desc" name="desc" placeholder="Description" style="" cols="40" rows="2"
                                            class="form-control"></textarea>
                                        </div>

                                    </div>


                                    <div class="p-3 row">
                                        {{-- <div class="col-lg-3">
                                            <input id="limit" name="limit" type="text"
                                                placeholder="Limit Order" class="form-control">
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="">Do you want To Active Limit Order ?</label>
                                            <input type="checkbox" id="active_limit" name="active_limit" value="1">
                                        </div> --}}
                                    </div>
                                </div>

                                <div class="tab-pane fade " id="numbers" role="tabpanel"
                                    aria-labelledby="numbers-tab">
                                    <button type = "button" id="newClarkNumberRow" class="my-2 btn btn-warning">New
                                        Number</button>
                                    <table id="clarkNumberTbl">
                                        <tr>
                                            <td><input class="form-control" type="text" name="clarkNumber[]"
                                                    id=""></td>
                                            <td><select name="supplierSlct[]" class="form-control supplierSlct"
                                                    id="supplierSlct1"></select></td>
                                            <td>REMOVE</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="specs" role="tabpanel" aria-labelledby="specs-tab">

                                    <button id="newClarkSpecRow" class="my-2 btn btn-warning">New Row</button>
                                    <table id="clarkSpecsTbl">


                                    </table>
                                </div>
                                <div class="tab-pane fade" id="brand" role="tabpanel" aria-labelledby="brand-tab">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            TYPE
                                            <select id="clarkBrandtypeSlct" class="select2 required form-control">
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            BRAND
                                            <select id="clarkBrandSlct" class="select2 required form-control">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            MODEL
                                            <button class="p-0 m-0 btn btn-ghost-primary" id="selectallmodel"> Select All
                                                Model</button>
                                            <select id="clarkModelSlct" class="select2 required form-control">
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            SERIES
                                            <button class="p-0 m-0 btn btn-ghost-primary" id="selectallseries"> Select All
                                                Series</button>
                                            <select id="clarkSeriesSlct" class="select2 required form-control">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="related-part" role="related_part"
                                    aria-labelledby="related-part-tab">
                                    <div class="row">
                                        <div class="col-4">
                                            <select id="relatedPartSlct" class="select2 required form-control">

                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col">
                                            <table class="table" id="ralatedPartTbl">

                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="image" role="tabpanel" aria-labelledby="image-tab">
                                    <div class="upload__box">
                                        <div class="upload__btn-box">
                                            <label class="upload__btn">
                                                <p class="m-0">Upload Images</p>
                                                <input type="file" multiple="" name="clarkImg[]"
                                                    data-max_length="20" class="upload__inputfile">
                                            </label>
                                        </div>
                                        <div class="upload__img-wrap"></div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="efragImage" role="tabpanel"
                                    aria-labelledby="efragEmage-tab">
                                    <div class="upload__box">
                                        <div class="upload__btn-box">
                                            <label class="upload__btn">
                                                <p class="m-0">Upload Release Images</p>
                                                <input type="file" multiple="" name="releaseImg[]"
                                                    data-max_length="20" class="upload__inputfile">
                                            </label>
                                        </div>
                                        <div class="upload__img-wrapRelease"></div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="invoiceImage" role="tabpanel"
                                    aria-labelledby="invoiceImage-tab">
                                    <div class="upload__box">
                                        <div class="upload__btn-box">
                                            <label class="upload__btn">
                                                <p class="m-0">Upload Invoice Images</p>
                                                <input type="file" multiple="" name="invoiceImg[]"
                                                    data-max_length="20" class="upload__inputfile">
                                            </label>
                                        </div>
                                        <div class="upload__img-wrapInvoice"></div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="invoicePrice" role="tabpanel"
                                    aria-labelledby="invoicePrice-tab">
                                    <div class="row">

                                        <div class="col-lg-3">
                                            <label for=""> المورد </label>
                                            <select id="supplier_id" name="supplier_id" required class="select2 required form-control"
                                                required>
                                            </select>
                                        </div>
                                        <div class="col-lg-3">

                                            <label for=""> سعر الشراء </label>
                                            <input onkeyup="calc_table_price()" id="price" name="price" type="number" placeholder="Price"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-lg-3 form-group">
                                            <label for="">العملة </label>
                                            <select id="currency_id" name ="currency_id"
                                                class="select2 required form-control" required>
                                            </select>

                                        </div>
                                        <div class="col-lg-3">
                                            <label for="">اختر حساب الصرف <span class="fw-bold text-secondary" id="Safetotal">0</span></label>
                                            <select name="store_idd" id="store_idd" class="form-select " required>
                                                <option class="text-center" value="" selected disabled>اختر اسم الحساب
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-lg-3"></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-4">
                                            <input type="hidden" name="InvCoasting" id="InvCoasting" value="0">
                                            <h5>تكلفة شراء البضاعة : <span id="InvCoastinglbl"> 0</span></h5>
                                            <label class="mt-2" for=""> مصروفات شحن ونقل المشتريات </label>
                                            <input onkeyup="calc_coast()" type="number" name="transCoast" id="transCoast"
                                                value="0" class="form-control mt-1">
                                            <label class="mt-2" for=""> مصروفات التأمين على البضاعة المشتراة
                                            </label>
                                            <input onkeyup="calc_coast()" type="number" name="insuranceCoast"
                                                id="insuranceCoast" value="0" class="form-control mt-1">
                                            <label class="mt-2" for=""> الرسوم الجمركية على البضاعة المشتراة
                                            </label>
                                            <input onkeyup="calc_coast()" type="number" name="customs" id="customs"
                                                value="0" class="form-control mt-1">
                                            <label class="mt-2" for=""> عمولة وكلاء الشراء </label>
                                            <input onkeyup="calc_coast()" type="number" name="commition" id="commition"
                                                value="0" class="form-control mt-1">
                                            <label class="mt-2" for=""> مصروفات اخرى </label>
                                            <input onkeyup="calc_coast()" type="number" name="otherCoast" id="otherCoast"
                                                value="0" class="form-control mt-1">

                                        </div>

                                        <div class="col-4"></div>
                                        <div class="col-4 text-end">
                                            {{-- <button type="button" class="btn btn-success"> + Excel upload</button> --}}

                                            <div class="form-check form-check-inline m-3">
                                                <input class="form-check-input" data-val="0" checked type="radio"
                                                    name="taxInvolved" value="0" id="inlineRadio1" value="option1">
                                                <label class="form-check-label" for="inlineRadio1">شامل ضريبة القيمة
                                                    المضافة</label>
                                            </div>
                                            <div class="form-check form-check-inline m-3">
                                                <input class="form-check-input" data-val="14" type="radio"
                                                    name="taxInvolved" value="1" id="inlineRadio2" value="option2">
                                                <label class="form-check-label" for="inlineRadio2">غير شامل </label>
                                            </div>
                                            <br />
                                            <div class="form-check form-check-inline m-3">
                                                <input class="form-check-input" data-val="-1" type="radio"
                                                    name="taxkasmInvolved" value="1" id="inlineRadio11"
                                                    value="option1">
                                                <label class="form-check-label" for="inlineRadio11">ضريبة خصم أرباح تجارية
                                                    وصناعية </label>
                                            </div>
                                            <div class="form-check form-check-inline m-3">
                                                <input class="form-check-input" data-val="0" type="radio"
                                                    name="taxkasmInvolved" value="0" checked id="inlineRadio21"
                                                    value="option2">
                                                <label class="form-check-label" for="inlineRadio21">لا </label>
                                            </div>


                                            <input type="hidden" name="invTotLbl" id="invTotLbl1" value="0">
                                            <h5>Items Total : <span id="invTotLbl"> 0000.0000 </span></h5>
                                            <label class="mt-2" for=""> % Tax </label>
                                            <input type="text" name="invTax" id="invTax" value="0"
                                                class="form-control mt-1 d-none">
                                            <label class="mt-2" for="">Total </label>
                                            <input type="text" readonly name="invAllTotal" id="invAllTotal"
                                                value="0" class="form-control mt-1">
                                            <label class="mt-2" for="">Paied</label>
                                            <input type="text" name="invPaied" id="invPaied" value=""
                                                class="form-control mt-1" required>
                                            <label class="mt-2" for="">Payment Method</label>
                                            <select class="form-select mt-1" name="payment" id="paymentslect" >
                                                <option selected value="0">كاش</option>
                                                <option value="1">تحويل بنكي</option>
                                                <option value="2"> أجل</option>
                                            </select>
                                            <div style="display:none" id="dueDiv">
                                                <label class="mt-2" for="">Due Date</label>
                                                <input type="date" name="dueDate" class="form-control"
                                                    value="<?php echo date('Y-m-d'); ?>" id="dueDate1">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="invoiceSection" role="tabpanel"
                                    aria-labelledby="invoiceSection-tab">

                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label for="">المخزن </label>
                                            <select onchange="changeStore(this);" id="store_id" name="store_id" class="select2 required form-control"
                                                required>
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="">الأقسام </label>
                                            <select id="storeSctionSlct" name="storeSectionId" class="select2 required form-control"
                                                required>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">غلق</button>
                            <button type="submit" class="btn btn-danger">حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="modal fade" id="editClark" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel2">Edit Clark</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="imgURLsInp[]" id="imgURLsInp[]" value="">
                        <input type="hidden" name="imgURLsInpEfrag[]" id="imgURLsInpEfrag[]" value="">
                        <input type="hidden" name="imgURLsInpInvoice[]" id="imgURLsInpInvoice[]" value="">
                        <input type="hidden" id="model_id_edit" name="model_id_edit">
                        <input type="hidden" id="clark_id_edit" name="clark_id_edit">
                        <input type="hidden" id="buyTransaction_id" name="buyTransaction_id" value="">

                        <input type="hidden" id="company_id" name="company_id" value="10">

                        <div class="modal-body">

                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="main-tab-edit" data-bs-toggle="tab"
                                        data-bs-target="#main_edit" type="button" role="tab"
                                        aria-controls="main_edit" aria-selected="true">Main Data</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link d-none" id="numbers-tab-edit" data-bs-toggle="tab"
                                        data-bs-target="#numbers_edit" type="button" role="tab"
                                        aria-controls="numbers_edit" aria-selected="false">Numbers</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="specs-tab-edit" data-bs-toggle="tab"
                                        data-bs-target="#specs_edit" type="button" role="tab"
                                        aria-controls="specs_edit" aria-selected="false">Specs</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="brand-tab-edit" data-bs-toggle="tab"
                                        data-bs-target="#brand_edit" type="button" role="tab"
                                        aria-controls="brand_edit" aria-selected="false">Brand</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="related-part-tab-edit" data-bs-toggle="tab"
                                        data-bs-target="#related-part_edit" type="button" role="tab"
                                        aria-controls="related-part_edit" aria-selected="false">Related Parts</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="image-tab-edit" data-bs-toggle="tab"
                                        data-bs-target="#image_edit" type="button" role="tab"
                                        aria-controls="image_edit" aria-selected="false">Images</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="efargImage-tab-edit" data-bs-toggle="tab"
                                        data-bs-target="#efragImage_edit" type="button" role="tab"
                                        aria-controls="efragImage_edit" aria-selected="false">Release Images</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="invoiceImage-tab-edit" data-bs-toggle="tab"
                                        data-bs-target="#invoiceImage_edit" type="button" role="tab"
                                        aria-controls="invoiceImage_edit" aria-selected="false">Invoice Images</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="invoicePrice_edit-tab" data-bs-toggle="tab"
                                        data-bs-target="#invoicePrice_edit" type="button" role="tab"
                                        aria-controls="invoicePrice_edit" aria-selected="false">Invoice Price</button>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="invoiceSection_edit-tab" data-bs-toggle="tab"
                                        data-bs-target="#invoiceSection_edit" type="button" role="tab"
                                        aria-controls="invoiceSection_edit" aria-selected="false">Place</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="main_edit" role="tabpanel"
                                    aria-labelledby="main-tab">

                                    <div class="p-3 row">

                                        <div class="col-lg-3">
                                            <label for="">الإسم عربى </label>
                                            <input id="name_edit" name="name_edit" type="text"
                                                placeholder="Arabic Name" class="form-control" required>
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="">الإسم إنجليزى </label>
                                            <input id="name_en_edit" name="name_en_edit" type="text"
                                                placeholder="English Name" class="form-control" required>
                                        </div>
                                         <div class="col-lg-3">
                                            <label for="">رقم الشاسية </label>
                                            <input id="clark_number_edit" name="clark_number_edit" type="text"
                                                placeholder="Chassis No." class="form-control" required>
                                        </div>
                                        <div class="col-lg-3 form-group">
                                            <label for=""> الدفع</label>
                                            <select id="drive_edit" name="drive_edit" class="select2 required form-control">
                                            </select>
                                        </div>


                                    </div>


                                    <div class="p-3 row">
                                        <div class="col-lg-3 form-group">
                                            <label for=""> بلد المنشأ</label>
                                            <select id="source_id_edit" name ="source_id_edit"
                                                class="select2 required form-control" required>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 form-group">
                                            <label for=""> الحالة</label>
                                            <select id="status_edit" name ="status_edit" class="select2 required form-control"
                                                required>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 form-group">
                                            <label for=""> الجودة</label>
                                            <select id="quality_id_edit" name="quality_id_edit"
                                                class="select2 required form-control" required>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 form-group">
                                            <label for=""> ناقل الحركة</label>
                                            <select id="gear_box_edit" name="gear_box_edit" class="select2 required form-control">
                                            </select>
                                        </div>
                                    </div>

                                    <div class="p-3 row ">
                                        <div class="col-lg-3 ">
                                            <label for=""> سنة الصنع </label>
                                            <input id="year_edit" name="year_edit" type="number" placeholder="Year"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-lg-3 ">
                                            <label for="">عدد ساعات العمل </label>
                                            <input id="hours_edit" name="hours_edit" type="number" placeholder="Hours"
                                                class="form-control input-sm" required>
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="">سعة تانك الجرار </label>
                                            <input id="tank_edit" name="tank_edit" type="number" placeholder="Tank"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="">رقم موتور الجرار </label>
                                            <input id="motor_number_edit" name="motor_number_edit" type="text"
                                                placeholder="Motor Number" class="form-control input-sm" required>
                                        </div>

                                    </div>

                                    <div class="p-3 row">
                                        <div class="col-lg-3">
                                            <label for="">مقاس الكاوتش الأمامى </label>
                                            <select id="front_tire_edit" name ="front_tire_edit"
                                                class="select2 required form-control">
                                            </select>

                                        </div>
                                        <div class="col-lg-3">
                                            <label for=""> مقاس الكاوتش الخلفى</label>
                                            <select id="rear_tire_edit" name="rear_tire_edit"
                                                class="select2 required form-control">
                                            </select>
                                        </div>
                                        <div class="col-lg-3 bs-example form-group">
                                            <label for=""> حالة الكاوتش الأمامى </label>
                                            <input id="front_tire_status_edit" name="front_tire_status_edit" type="text"
                                                placeholder="Front tire status %" class="form-control">
                                        </div>
                                        <div class="col-lg-3 bs-example">
                                            <label for=""> حالة الكاوتش الخلفى </label>
                                            <input id="rear_tire_status_edit" name="rear_tire_status_edit" type="text"
                                                placeholder="Rear tire status %" class="form-control">

                                        </div>
                                    </div>

                                    <div class="p-3 row">
                                        <div class="col-lg-3">
                                            <label for=""> القدرة </label>
                                            <input id="power_edit" name="power_edit" type="text" placeholder="Power"
                                                class="form-control">
                                        </div>
                                        <div class="col-lg-3">
                                            <label for=""> الديسكات </label>
                                            <input id="discs_edit" name="discs_edit" type="text"
                                                placeholder="Clutch disk" class="form-control">
                                        </div>
                                        <div class="col-lg-3">
                                            <label for=""> اخر معاد للصيانة</label>
                                            <input id="serivcedate_edit" name="serivcedate_edit" type="date" placeholder=""
                                                class="form-control ">
                                        </div>
                                        <div class="col-lg-3">
                                            <label for=""> تاريخ الوصول</label>
                                            <input id="deliverydate_edit" name="deliverydate_edit" type="date" placeholder=""
                                                class="form-control " required>
                                        </div>
                                    </div>
                                    <div class="p-3 row">
                                        <div class="col-lg-3">
                                            <label for="">لون الكلارك </label>
                                            <input id="color_edit" name="color_edit" type="color" placeholder=""
                                                class="form-control input-sm">
                                        </div>
                                        <div class="col-lg-9">
                                            <label for=""> الوصف </label>

                                            <textarea id="desc_edit" name="desc_edit" placeholder="Description" style="" cols="40" rows="2"
                                            class="form-control"></textarea>
                                        </div>

                                    </div>


                                    <div class="p-3 row">
                                        {{-- <div class="col-lg-3">
                                            <input id="limit" name="limit" type="text"
                                                placeholder="Limit Order" class="form-control">
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="">Do you want To Active Limit Order ?</label>
                                            <input type="checkbox" id="active_limit" name="active_limit" value="1">
                                        </div> --}}
                                    </div>
                                    
                                </div>

                                <div class="tab-pane fade " id="numbers_edit" role="tabpanel"
                                    aria-labelledby="numbers-tab">
                                    <button type = "button" id="newClarkNumberRow_edit" class="my-2 btn btn-warning">New
                                        Number</button>
                                    <table id="clarkNumberTbl_edit">
                                        <tr>
                                            <td><input class="form-control" type="text" name="clarkNumber_edit[]"
                                                    id=""></td>
                                            <td><select name="supplierSlc_edit[]" class="form-control supplierSlct"
                                                    id="supplierSlct1_edit"></select></td>
                                            <td>REMOVE</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="specs_edit" role="tabpanel" aria-labelledby="specs-tab">

                                    <button id="newClarkSpecRow_edit" class="my-2 btn btn-warning">New Row</button>
                                    <table id="clarkSpecsTblEdit">


                                    </table>
                                </div>
                                <div class="tab-pane fade" id="brand_edit" role="tabpanel" aria-labelledby="brand-tab">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            TYPE
                                            <select id="clarkBrandtypeSlct_edit" class="select2 required form-control">
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            BRAND
                                            <select id="clarkBrandSlct_edit" class="select2 required form-control">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            MODEL
                                            <button class="p-0 m-0 btn btn-ghost-primary" id="selectallmodel_edit"> Select
                                                All Model</button>
                                            <select id="clarkModelSlct_edit" class="select2 required form-control">
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            SERIES
                                            <button class="p-0 m-0 btn btn-ghost-primary" id="selectallseries_edit">
                                                Select All Series</button>
                                            <select id="clarkSeriesSlct_edit" class="select2 required form-control">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="related-part_edit" role="related_part"
                                    aria-labelledby="related-part-tab">
                                    <div class="row">
                                        <div class="col-4">
                                            <select id="relatedPartSlct_edit" class="select2 required form-control">

                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col">
                                            <table class="table" id="ralatedPartTbl_edit">

                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="image_edit" role="tabpanel" aria-labelledby="image-tab">
                                    <div class="upload__box">
                                        <div class="upload__btn-box">
                                            <label class="upload__btn">
                                                <p class="m-0">Upload Images</p>
                                                <input type="file" multiple="" name="clarkImg_edit[]"
                                                    data-max_length="20" class="upload__inputfile">
                                            </label>
                                        </div>
                                        <div class="upload__img-wrap_edit"></div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="efragImage_edit" role="tabpanel"
                                    aria-labelledby="efragEmage-tab">
                                    <div class="upload__box">
                                        <div class="upload__btn-box">
                                            <label class="upload__btn">
                                                <p class="m-0">Upload Release Images</p>
                                                <input type="file" multiple="" name="releaseImg_edit[]"
                                                    data-max_length="20" class="upload__inputfile">
                                            </label>
                                        </div>
                                        <div class="upload__img-wrapRelease_edit"></div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="invoiceImage_edit" role="tabpanel"
                                    aria-labelledby="invoiceImage-tab">
                                    <div class="upload__box">
                                        <div class="upload__btn-box">
                                            <label class="upload__btn">
                                                <p class="m-0">Upload Invoice Images</p>
                                                <input type="file" multiple="" name="invoiceImg_edit[]"
                                                    data-max_length="20" class="upload__inputfile">
                                            </label>
                                        </div>
                                        <div class="upload__img-wrapInvoice_edit"></div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="invoicePrice_edit" role="tabpanel"
                                    aria-labelledby="invoicePrice-tab">
                                    <div class="row">

                                        <div class="col-lg-3">

                                            <label for=""> المورد </label>
                                            <select id="supplier_id_edit" name="supplier_id_edit" required class="select2 required form-control"
                                                required>
                                            </select>
                                        </div>



                                        <div class="col-lg-3">

                                            <label for=""> سعر الشراء </label>
                                            <input onkeyup="calc_table_price_edit()" id="price_edit" name="price_edit" type="number" placeholder="Price"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-lg-3 form-group">
                                            <label for="">العملة </label>
                                            <select id="currency_id_edit" name ="currency_id_edit"
                                                class="select2 required form-control" required>
                                            </select>

                                        </div>
                                        <div class="col-lg-3">
                                            <label for="">اختر حساب الصرف <span class="fw-bold text-secondary" id="Safetotal_edit">0</span></label>
                                            <select name="store_idd_edit" id="store_idd_edit" class="form-select " required>
                                                <option class="text-center" value="" selected disabled>اختر اسم الحساب
                                                </option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <input type="hidden" name="InvCoasting_edit" id="InvCoasting_edit" value="0">
                                            <h5>تكلفة شراء البضاعة : <span id="InvCoastinglbl_edit"> 0</span></h5>
                                            <label class="mt-2" for=""> مصروفات شحن ونقل المشتريات </label>
                                            <input onkeyup="calc_coast_edit()" type="number" name="transCoast_edit" id="transCoast_edit"
                                                value="0" class="form-control mt-1">
                                            <label class="mt-2" for=""> مصروفات التأمين على البضاعة المشتراة
                                            </label>
                                            <input onkeyup="calc_coast_edit()" type="number" name="insuranceCoast_edit"
                                                id="insuranceCoast_edit" value="0" class="form-control mt-1">
                                            <label class="mt-2" for=""> الرسوم الجمركية على البضاعة المشتراة
                                            </label>
                                            <input onkeyup="calc_coast_edit()" type="number" name="customs_edit" id="customs_edit"
                                                value="0" class="form-control mt-1">
                                            <label class="mt-2" for=""> عمولة وكلاء الشراء </label>
                                            <input onkeyup="calc_coast_edit()" type="number" name="commition_edit" id="commition_edit"
                                                value="0" class="form-control mt-1">
                                            <label class="mt-2" for=""> مصروفات اخرى </label>
                                            <input onkeyup="calc_coast_edit()" type="number" name="otherCoast_edit" id="otherCoast_edit"
                                                value="0" class="form-control mt-1">

                                        </div>

                                        <div class="col-4"></div>
                                        <div class="col-4 text-end">
                                            {{-- <button type="button" class="btn btn-success"> + Excel upload</button> --}}

                                            <div class="form-check form-check-inline m-3">
                                                <input class="form-check-input" data-val="0" checked type="radio"
                                                    name="taxInvolved_edit" value="0" id="inlineRadio1_edit" value="option1">
                                                <label class="form-check-label" for="inlineRadio1_edit">شامل ضريبة القيمة
                                                    المضافة</label>
                                            </div>
                                            <div class="form-check form-check-inline m-3">
                                                <input class="form-check-input" data-val="14" type="radio"
                                                    name="taxInvolved_edit" value="1" id="inlineRadio2_edit" value="option2">
                                                <label class="form-check-label" for="inlineRadio2_edit">غير شامل </label>
                                            </div>
                                            <br />
                                            <div class="form-check form-check-inline m-3">
                                                <input class="form-check-input" data-val="-1" type="radio"
                                                    name="taxkasmInvolved_edit" value="1" id="inlineRadio11_edit"
                                                    value="option1">
                                                <label class="form-check-label" for="inlineRadio11_edit">ضريبة خصم أرباح تجارية
                                                    وصناعية </label>
                                            </div>
                                            <div class="form-check form-check-inline m-3">
                                                <input class="form-check-input" data-val="0" type="radio"
                                                    name="taxkasmInvolved_edit" value="0" checked id="inlineRadio21_edit"
                                                    value="option2">
                                                <label class="form-check-label" for="inlineRadio21_edit">لا </label>
                                            </div>


                                            <input type="hidden" name="invTotLbl_edit" id="invTotLbl1_edit" value="0">
                                            <h5>Items Total : <span id="invTotLbl_edit"> 0000.0000 </span></h5>
                                            <label class="mt-2" for=""> % Tax </label>
                                            <input type="text" name="invTax_edit" id="invTax_edit" value="0"
                                                class="form-control mt-1 d-none">
                                            <label class="mt-2" for="">Total </label>
                                            <input type="text" readonly name="invAllTotal_edit" id="invAllTotal_edit"
                                                value="0" class="form-control mt-1">
                                            <label class="mt-2" for="">Paied</label>
                                            <input type="text" name="invPaied_edit" id="invPaied_edit" value=""
                                                class="form-control mt-1" required>
                                            <label class="mt-2" for="">Payment Method</label>
                                            <select class="form-select mt-1" name="payment_edit" id="paymentslect_edit" >
                                                <option selected value="0">كاش</option>
                                                <option value="1">تحويل بنكي</option>
                                                <option value="2"> أجل</option>
                                            </select>
                                            <div style="display:none" id="dueDiv_edit">
                                                <label class="mt-2" for="">Due Date</label>
                                                <input type="date" name="dueDate_edit" class="form-control"
                                                    value="<?php echo date('Y-m-d'); ?>" id="dueDate1_edit">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="invoiceSection_edit" role="tabpanel"
                                    aria-labelledby="invoiceSection-tab">

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="">المخزن </label>
                                            <select onchange="changeStore_edit(this);" id="store_id_edit" name="store_id_edit" class="select2 required form-control"
                                                required>
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="">الأقسام </label>
                                            <select id="storeSctionSlct_edit" name="storeSectionId_edit" class="select2 required form-control"
                                                required>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger saveUpdate">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deletepartB" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel2" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel2">Delete Clark</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method='post' action="" class="deleteform" id='deleteform'>
                        <input type="hidden" name="part_id" id="part_iddel" value="">
                        @csrf
                        @method('DELETE')

                        <div class="modal-body">
                            <h1>هل تريد مسح هذا العنصر</h1>
                            <input type='text' placeholder='Part Name' class='form-control' id='pdel_name'>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input type="submit" value="DELETE" class= "py-1 btn btn-danger "
                                data-bs-dismiss="modal">'.
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @endsection

    @section('js')

        <script src="{{ URL::asset('js/clarks.js') }}"></script>
        <script>
            (function() {
                'use strict'

                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.querySelectorAll('.needs-validation')

                // Loop over them and prevent submission
                Array.prototype.slice.call(forms)
                    .forEach(function(form) {
                        form.addEventListener('submit', function(event) {
                            if (!form.checkValidity()) {
                                event.preventDefault();
                                event.stopPropagation();
                                // alert("لابد من تسجيل بعض البيانات لإدخال قطعة جديدة");
                                Swal.fire({
                                    text: "لابد من تسجيل بعض البيانات لإدخال قطعة جديدة",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary",
                                    }

                                });
                                $('#myTab button:first').tab('show');
                            }

                            form.classList.add('was-validated')
                        }, false)
                    })
            })();
        </script>
    @endsection
