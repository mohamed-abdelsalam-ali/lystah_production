@extends('layouts.master')
@section('css')



    <style>
        /*.modal-content{*/
        /*    width: 50vw !important;*/
        /*}*/

        .dataTables_processing{
            z-index : 9999 !important;
            width : 100vw !important;
            height : auto !important;
            top : 10% !important;
            left : 0 !important;
            position : fixed !important;
        }
        .pointer {cursor: pointer;}




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
            background-color: #3d78e3;//#3d78e3
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

        .upload__img-close:after {
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



          /* * {

            font-family: , "Times New Roman", Times, serif;
        } */
          /* .{
            font-family: , "Times New Roman", Times, serif;
        } */
    </style>
@endsection
@section('title')
    قطع الغيار
@stop


@section('content')



    <div class="main-content ">
        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Parts</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">parts</li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">

                    <button class="btn btn-primary m-1 float-left " data-bs-toggle="modal" data-bs-target="#staticBackdrop">إضافة</button>
                    {{-- <button class="btn btn-primary m-1 float-left "  onclick="location.href='groups'"><i class="ri-user-search-fill"></i></button> --}}
                    <button class="btn btn-primary m-1 float-left " onclick="window.open('customSearch','_blank')"><i class="ri-user-search-fill"></i></button>

                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-head">
                            <div class="row m-2">
                                <div class="col-lg-6">
                                    {{-- <button class="btn btn-soft-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="ri-add-fill"></i></button>
                                    <a  class="btn btn-soft-info " href="customSearch"><i class="ri-user-search-fill"></i></a> --}}
                                </div>
                                <div class="col-lg-6 text-end">

                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive ">
                                <table id="partsDT" class="table table-striped table-bordered cell-border " style="width:100%">
                                    <thead style="background:#5fcee78a">
                                    <tr>
                                        <th class="">#</th>
                                        <th class="">صورة القطعة</th>
                                        <th class="">الاسم</th>
                                        <th>الاسم بالانجليزية</th>
                                        <th>تاريخ</th>
                                        <th>الوصف</th>
                                        <th>الحد الأدني</th>
                                        <th>sub_group_id</th>
                                        <th>Sub Group</th>
                                        <th>Group</th>
                                        <th>Models</th>
                                        <th>رقم القطعة</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-full-width ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title " id="staticBackdropLabel">إضافة قطعة غيار جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" id="PartFormID" action="{{ url('store-part') }}" class="needs-validation" novalidate enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                    type="button" role="tab" aria-controls="home" aria-selected="true">
                                    البيانات الأساسية</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                    type="button" role="tab" aria-controls="profile"
                                    aria-selected="false">الأرقام</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                                    type="button" role="tab" aria-controls="contact"
                                    aria-selected="false">المواصفات الخاصة</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="related-part-tab" data-bs-toggle="tab"
                                    data-bs-target="#related-part" type="button" role="tab"
                                    aria-controls="related-part" aria-selected="false"> أصناف ذات صلة</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact1"
                                    type="button" role="tab" aria-controls="contact"
                                    aria-selected="false">الموديلات</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#contact2" type="button" role="tab" aria-controls="contact"
                                    aria-selected="false">الصور</a>
                            </li>
                        </ul>
                        <div class="tab-content " id="myTabContent">
                            <div class="tab-pane   active in" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="p-3 row">
                                    <div class="col-3">
                                        <label for="">الأسم  باللغة العربية <span style="color: red">*</span></label>
                                        <input id="" name="partarname" type="text" placeholder="Arabic Name"
                                            class="form-control" required>
                                    </div>
                                    <div class="col-3">
                                        <label for="">الأسم باللغة الانجليزية<span style="color: red">*</span></label>
                                        <input id="" name="partengname" type="text"
                                            placeholder="English Name" class="form-control" required>
                                    </div>
                                    <div class="col-3">
                                    <label for="">المجموعة <span style="color: red">*</span></label>
                                    <select class="form-control" name="groupSlct" id="groupSlct" required></select>
                                    </div>
                                    <div class="col-3">
                                    <label for="">المجموعة الفرعية  <span style="color: red">*</span></label>
                                    <select class="form-control" name="SgroupSlct" id="SgroupSlct" required></select>
                                    </div>
                                </div>

                              
                                <div class="mt-2 row">
                                    <div class="col-6">
                                        <label for="">الوحدة الصغرى</label>
                                        <select class="form-control" name="small_unit_a" id="small_unit_a"></select>
                                    </div>
                                    <div class="col-6">
                                        <label for="">الوحدة الكبرى </label>
                                        <select class="form-control" name="big_unit_a" id="big_unit_a"></select>
                                    </div>
                                </div>
                                <div class="mt-2 row">
                                    <div class="col-6">
                                    <label for="">اقل كمية للتنبية(وحدة صغرى)</label>
                                    <input id="" name="limit" type="number" placeholder="Limit"
                                            class="form-control">
                                    </div>
                                    <div class="col-6 pt-4 text-center">
                                    <label for="notify" class="pe-2">إضافة للنواقص</label>
                                    <input type="checkbox" id="notify" class="form-check-input " name="notify" value="1">
                                    </div>
                                </div>
                                <div class="mt-2 row">

                                    <div class="col-12">
                                        <label for="partdesc">وصف القطعة</label>
                                        <textarea name="partdesc" id="partdesc" cols="30" rows="5" style="resize:none"class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <button id="newPartNumberRow" class="my-2 btn btn-primary">إضافة رقم جديد</button>
                                <table id="partNumberTbl">
                                    <tr>
                                        <td><input class="form-control" type="text" name="partNumber[]"
                                                id=""></td>
                                        <td><select name="supplierSlct[]" class="form-control supplierSlct"
                                                id="supplierSlct1"></select></td>
                                        <td class="text-center pointer text-danger" style="font-size: 24px;"><i class="ri-delete-bin-line"></i></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

                                <button id="newPartSpecRow" class="my-2 btn btn-primary">إضافة توصيف جديد </button>
                                <table id="partSpecsTbl">


                                </table>
                            </div>
                            <div class="tab-pane fade" id="related-part" role="related_part"
                                aria-labelledby="related-part-tab">
                                <div class="row">
                                    <div class="col-4 mt-2">
                                        <select id="relatedPartSlct" class="form-select ">

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
                            <div class="tab-pane fade" id="contact1" role="tabpanel" aria-labelledby="contact-tab">




                                <div class="row ">
                                    <div class="col-3 text-dark">
                                        Type
                                        <select id="brandtypeSlct" class="form-select">

                                        </select>
                                    </div>
                                    <div class="col-3 text-dark">
                                        Brand

                                        <select id="brandSlct" class="form-select">

                                        </select>
                                    </div>
                                    <div class="col-3 text-dark">
                                        <!--<button type="button" class="p-0 m-0 btn btn-ghost-primary" id="selectallmodel" > Select All Model</button>-->
                                        Model
                                        <select id="modelSlct" class="form-select">

                                        </select>
                                    </div>
                                    <div class="col-3 text-dark">
                                        <button type="button" class="p-0 m-0 btn btn-ghost-primary"
                                            id="selectallseries"> Select All Series</button>

                                        <select id="seriesSlct" class="form-select">

                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col">
                                        <table class="table" id="partBrandTbl">

                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="contact2" role="tabpanel" aria-labelledby="contact-tab">
                                <div class="upload__box">
                                    <div class="upload__btn-box">
                                        <label class="upload__btn">
                                            <p class="m-0">تحميل صور القطعة</p>
                                            <input type="file" multiple="" name="partImg[]" data-max_length="20"
                                                class="upload__inputfile">
                                        </label>
                                    </div>
                                    <div class="upload__img-wrapPart" id="upload__img-wrapPartAdd"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer" >
                        <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">غلق</button>
                        <button type="submit" class="btn btn-danger me-auto">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editPart" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"> تعديل الصنف</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="part_id" id="part_id" value="">
                    <input type="hidden" name="imgURLsInp[]" id="imgURLsInp[]" value="">
                    @method('PUT')
                    @csrf
                    <div class="modal-body">
                        <ul class="nav nav-tabs" id="myTab2" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#mainPartEdit" type="button" role="tab"
                                    aria-controls="mainPartEdit" aria-selected="true"> البيانات الأساسية</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#partNumberEdit" type="button" role="tab"
                                    aria-controls="partNumberEdit" aria-selected="false">الأرقام</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#partSpecsEdit" type="button" role="tab"
                                    aria-controls="partSpecsEdit" aria-selected="false">المواصفات الخاصة</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="related-part_edit-tab" data-bs-toggle="tab"
                                    data-bs-target="#related-part_edit" type="button" role="tab"
                                    aria-controls="related-part_edit" aria-selected="false"> أصناف ذات صلة</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#partBrandEdit" type="button" role="tab"
                                    aria-controls="partBrandEdit" aria-selected="false">Brand</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#partImageEdit" type="button" role="tab"
                                    aria-controls="partImageEdit" aria-selected="false">الصور</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTab2Content">
                            <div class="tab-pane fade show active" id="mainPartEdit" role="tabpanel"  aria-labelledby="home-tab">
                                <div class="p-3 row">
                                    <div class="col-6">
                                    <label for="">الإسم عربى</label>

                                        <input id="nameedit" name="nameedit" type="text" value=""
                                            class="form-control" required="enabled">
                                    </div>
                                    <div class="col-6">
                                    <label for="">الإسم إنجليزى</label>

                                        <input id="engnameedit" name="engnameedit" type="text"
                                            placeholder="English Name" class="form-control" required="enabled">
                                    </div>
                                </div>
                                <!-- <div class="row">
                                    <label class="col-2" for="">الوصف</label>
                                    <div class="col-10">
                                        <textarea id="descedit" name="descedit" id="" cols="30" rows="5" class="form-control"></textarea>
                                    </div>
                                </div> -->
                                <div class="mt-2 row">
                                    <div class="col-6">
                                    <label for="">المجموعة</label>
                                    <select class="form-control" name="groupSlctEdit" id="groupSlctEdit"></select>
                                    </div>
                                    <div class="col-6">
                                    <label for=""> المجموعة الفرعية</label>
                                    <select class="form-control" name="SgroupSlctEdit" id="SgroupSlctEdit"></select>
                                    </div>
                                </div>
                                <div class="row p-3">
                                    <div class="col-6">
                                        <label for="">الوحدة الصغرى</label>
                                        <select class="form-control" name="small_unit_e" id="small_unit_e"></select>
                                    </div>
                                    <div class="col-6">
                                        <label for="">الوحدة الكبرى </label>
                                        <select class="form-control" name="big_unit_e" id="big_unit_e"></select>
                                    </div>
                                </div>
                                <div class="mt-2 row">
                                    <div class="col-6">
                                    <label for="">أقل كمية للتنبية</label>
                                    <input id="limitedit" name="limitedit" type="text" placeholder="Limit"
                                            class="form-control">
                                    </div>
                                    <div class="col-6">
                                    <label for="">إضافة للنواقص</label>
                                    <input type="checkbox" id="notifyedit" name="notifyEdit" value="1">
                                    </div>
                                </div>
                                <div class="row p-3">
                                    <label class="" for="">وصف القطعة</label>

                                    <div class="col-12">
                                        <textarea id="descedit" name="descedit" id="" cols="30" rows="5" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="partNumberEdit" role="tabpanel"
                                aria-labelledby="profile-tab">
                                <button type = "button" id="newPartNumberRowEdit" class="my-2 btn btn-warning">
                                رقم جديد</button>
                                <table id="partNumberTblEdit">
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="partSpecsEdit" role="tabpanel" aria-labelledby="contact-tab">
                            <button id="newPartSpecRowEdit" class="my-2 btn btn-warning"> بند جديد</button>
                            <table id="partSpecsTblEdit">
                                </table>
                            </div>
                            <div class="tab-pane fade" id="partBrandEdit" role="tabpanel" aria-labelledby="contact-tab">

                                <div class="row">
                                    <div class="col-3">
                                            TYPE
                                            <select id="partbrandtypeSlctEdit" class="form-select">

                                            </select>
                                    </div>
                                    <div class="col-3">
                                        BRAND
                                        <select id="partbrandSlctEdit" class="form-select">

                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <!--<button class="p-0 m-0 btn btn-ghost-primary" id="selectallmodel" > Select All Model</button>-->
                                        MODEL
                                        <select id="partmodelSlctEdit" class="form-select">

                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <button type="button" class="p-0 m-0 btn btn-ghost-primary"
                                            id="editselectallseries"> Select All Series</button>

                                        <select id="partseriesSlctEdit" class="form-select">

                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col">
                                        <table class="table" id="partBrandTblEdit">

                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="related-part_edit" role="related_part"
                                aria-labelledby="related-part-tab">
                                <div class="row">
                                    <div class="col-4">
                                        <select id="relatedPartSlct_edit" class="form-select">

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
                            <div class="tab-pane fade" id="partImageEdit" role="tabpanel" aria-labelledby="contact-tab">
                                <div class="upload__box">
                                    <div class="upload__btn-box">
                                        <label class="upload__btn">
                                        <p class="m-0"> الصور</p>
                                        <input type="file" multiple="" name="partImgEdit[]"
                                                data-max_length="20" class="upload__inputfilePartEdit">
                                        </label>
                                    </div>
                                    <div class="upload__img-wrapPartEdit">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-danger">حفظ</button>
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
                <h5 class="modal-title" id="staticBackdropLabel2"> حذف هذا الصنف</h5>

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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <input type="submit" value="حذف" class= "py-1 btn btn-danger " data-bs-dismiss="modal">
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')

    <script src="{{ URL::asset('js/parts.js') }}"></script>
    <script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
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
        // $("#PartFormID").validate();
        $('.nav-link').removeClass('active');
        $('#partli a').addClass('active');
        $('.nav-item').removeClass('active');
        $('.partli').addClass('active');
    </script>

@endsection
