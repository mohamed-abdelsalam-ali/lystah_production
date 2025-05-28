@extends('layouts.master')
@section('css')



    <style>
        /*.modal-content{*/
        /*    width: 50vw !important;*/
        /*}*/
        @font-face {
            font-family: Cairo;
            src: url('fonts/Cairo-Light.ttf');
        }

        * {

            font-family: Cairo, "Times New Roman", Times, serif;
        }

        .cairo {
            font-family: Cairo, "Times New Roman", Times, serif;
        }

        table {
            font-family: 'Droid Arabic Naskh', serif;
        }

        #wheelSpecsTbl tr {
            margin-top: 5px;
        }

        .upload__box {
            padding: 40px;
        }

        .upload__inputfileWheel {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        .upload__inputfileWheelEdit {
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
            background-color: #3d78e3;
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

        .upload__img-wrapWheel {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .upload__img-wrapWheelEdit {
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
    </style>
@endsection
@section('title')
    الكاوتش
@stop


@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-solid " style="z-index: 88888 !important;" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="main-content">
        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0 cairo">Wheels</h4>

                        <div class="page-title-right cairo">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Wheels</li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">

                    <button class="btn btn-primary m-1 float-left cairo" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop">إضافة </button>
                    {{-- <button class="btn btn-primary m-1 float-left cairo"  onclick="location.href='groups'"><i class="ri-user-search-fill"></i></button> --}}
                    {{-- <button class="btn btn-primary m-1 float-left cairo" onclick="window.open('customSearch','_blank')"><i class="ri-user-search-fill"></i></button> --}}
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-head">
                            {{-- <div class="row">
                                <div class="col-8"></div>

                                <div class="px-4 mt-3 col-lg-4 text-end">
                                    <button class="btn addButton btn-info" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Add New <i class="ri-add-fill"></i></button>
                                </div>
                            </div> --}}
                        </div>
                        <div class="card-body table-responsive">
                            <table id="wheelsDT"class="table table-striped table-bordered cell-border " style="width:100%">
                                <thead style="background:#5fcee78a">
                                    <tr >
                                        <th></th>
                                        <th >صور</th>
                                        <th >الأسم</th>
                                        <th >الأبعاد</th>
                                        <th >المادة</th>
                                        <th >Model</th>

                                        <th >Action</th>
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
        <div class="modal-dialog cairo">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">إضافة كاوتش </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post"  id="WheelFormID" action="{{ route('wheelStore') }}" class="needs-validation" novalidate enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                    type="button" role="tab" aria-controls="home" aria-selected="true">Main
                                    Data</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="related-wheel-tab" data-bs-toggle="tab"
                                    data-bs-target="#relatedWheel" type="button" role="tab" aria-controls="contact"
                                    aria-selected="false">Related Wheel</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                    type="button" role="tab" aria-controls="profile"
                                    aria-selected="false">Spcs</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                                    type="button" role="tab" aria-controls="contact"
                                    aria-selected="false">Image</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active in" id="home" role="tabpanel"aria-labelledby="home-tab">

                                <div class="row  pt-2">
                                    <div class="col-lg-12">
                                        <input id="name" name="name" type="text" placeholder="Name"
                                            class="form-control" required readonly>
                                    </div>

                                </div>
                                <div class="row  pt-2">
                                    <div class="col-lg-6">

                                        <select id="typeSlct" class="form-select mt-2" required>
                                            <option selected readonly value="">اختر نوع الكاوتش</option>
                                            <option value="1">كاوتش</option>
                                            <option value="2">جنط</option>
                                            <option value="3">داخلي</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 pt-2">

                                       <input class="form-control " name="container_size" type="number" step=".1" value="" placeholder="Container Size">
                                    </div>
                                </div>
                                <div class=" row pt-2">
                                    <div id="div1" class="col-md-6 m-auto text-center" style='display:none'>
                                        <div class=" form-check form-check-inline">
                                            <input data-name="TT" type="radio" id="Radio1"class="form-check-input"
                                                name="tt_tl" value="1">
                                            <label for="Radio1"class="form-check-label">TT</label>
                                        </div>
                                        <div class=" form-check form-check-inline">
                                            <input data-name="TL" type="radio" id="Radio1" class="form-check-input"
                                                name="tt_tl" value="2">
                                            <label for="Radio2"class="form-check-label">TL</label>
                                        </div>
                                    </div>
                                    <div id="div2" class="col-md-6 m-auto text-center" style=' display:none'>
                                        <div class=" form-check form-check-inline">
                                            <input data-name="Big Hole" type="radio" id="Radio1"
                                                class="form-check-input" name="tt_tl" value="1">
                                            <label for="Radio1"class="form-check-label">Big Hole</label>
                                        </div>
                                        <div class=" form-check form-check-inline">
                                            <input data-name="Small Hole" type="radio"
                                                id="Radio1"class="form-check-input" name="tt_tl" value="2">
                                            <label for="Radio2"class="form-check-label">Small Hole</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="dimtypeSlct"> Dimension</label>
                                        <select id="dimtypeSlct" name="dimension" class="form-select" required>

                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="modeltypeSlct"> Brand</label>
                                        <select id="modeltypeSlct" name="model_id" class="form-select" required>

                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="materialtypeSlct"> Material</label>
                                        <select id="materialtypeSlct" name ="wheel_material_id" class="form-select"
                                            required>

                                        </select>
                                    </div>
                                </div>
                                <div class="p-3 row">

                                    <div class="col-lg-4">
                                        <label  for="">Description</label>
                                        <textarea name="description" id="" style="" cols="30" rows="2"
                                            class="form-control"></textarea>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="">Limit</label>
                                        <input id="" name="limit_order" type="text" placeholder="Limit"
                                            class="form-control">

                                    </div>

                                    <div class="col-lg-4 form-check">
                                        <label for="notify" class="form-check-label ">هل تريد تفعيل الحد الأدني ?
                                            </label>
                                        <input type="checkbox" class="form-check-input ms-2"id="notify" name="notify"
                                            value="1">
                                    </div>
                                </div>

                                {{-- <br> --}}
                                {{-- <div class="row">
                                    <div id="div1" class="col-md-6 m-auto text-center" style='display:none'>
                                        <div class=" form-check form-check-inline">
                                            <input data-name="TT" type="radio" id="Radio1"class="form-check-input"
                                                name="tt_tl" value="1">
                                            <label for="Radio1"class="form-check-label">TT</label>
                                        </div>
                                        <div class=" form-check form-check-inline">
                                            <input data-name="TL" type="radio" id="Radio1" class="form-check-input"
                                                name="tt_tl" value="2">
                                            <label for="Radio2"class="form-check-label">TL</label>
                                        </div>
                                    </div>


                                    <div id="div2" class="col-md-6 m-auto text-center" style=' display:none'>
                                        <div class=" form-check form-check-inline">
                                            <input data-name="Big Hole" type="radio" id="Radio1"
                                                class="form-check-input" name="tt_tl" value="1">
                                            <label for="Radio1"class="form-check-label">Big Hole</label>
                                        </div>
                                        <div class=" form-check form-check-inline">
                                            <input data-name="Small Hole" type="radio"
                                                id="Radio1"class="form-check-input" name="tt_tl" value="2">
                                            <label for="Radio2"class="form-check-label">Small Hole</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Limit</label>
                                        <input id="" name="limit_order" type="text" placeholder="Limit"
                                            class="form-control">
                                    </div>
                                </div> --}}


                                {{-- <br> --}}
                                {{-- <div class="row">
                                    <div class="col">
                                        <label for="">Limit</label>
                                        <input id="" name="limit_order" type="text" placeholder="Limit"
                                            class="form-control">
                                    </div>
                                </div>
                                <br> --}}

                            </div>
                            <div class="tab-pane fade" id="relatedWheel" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row">
                                    <div class="col">
                                        Related Wheel
                                        <select id="relatedWheelSlct" name="relatedWheelSlct" class="form-select">

                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <table class="table" id="wheelTbl">

                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <button id="newWheelSpecRow" class="my-2 btn btn-primary">إضافة</button>
                                <table id="wheelSpecsTbl">


                                </table>
                            </div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

                                <div class="upload__box">
                                    <div class="upload__btn-box">
                                        <label class="upload__btn">
                                            <p class="m-0">تحميل صور القطعة</p>
                                            <input type="file" multiple="" name="wheelImg[]" data-max_length="20"
                                                class="upload__inputfileWheel">
                                        </label>
                                    </div>
                                    <div class="upload__img-wrapWheel"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">غلق</button>
                        <button type="submit" class="btn btn-danger me-auto">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editWheel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Wheel</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="wheel_id" id="wheel_id" value="">
                    <input type="hidden" name="imgURLsInp[]" id="imgURLsInp[]" value="">
                    @method('PUT')
                    @csrf
                    <div class="modal-body">

                        <ul class="nav nav-tabs" id="myTab2" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="mainWheelEdit-tab" data-bs-toggle="tab"
                                    data-bs-target="#mainWheelEdit" type="button" role="tab"
                                    aria-controls="mainWheelEdit" aria-selected="true">Main Data</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="relatedWheelEdit-tab" data-bs-toggle="tab"
                                    data-bs-target="#relatedWheelEdit" type="button" role="tab"
                                    aria-controls="relatedWheelEdit" aria-selected="false">Related Wheel</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="wheelSpecsEdit-tab" data-bs-toggle="tab"
                                    data-bs-target="#wheelSpecsEdit" type="button" role="tab"
                                    aria-controls="wheelSpecsEdit" aria-selected="false">Spcs</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="wheelImageEdit-tab" data-bs-toggle="tab"
                                    data-bs-target="#wheelImageEdit" type="button" role="tab"
                                    aria-controls="wheelImageEdit" aria-selected="false">Image</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTab2Content">
                            <div class="tab-pane fade show active" id="mainWheelEdit" role="tabpanel"
                                aria-labelledby="home-tab">

                                <div class="row">
                                    <div class="col-lg-12 pt-2">
                                        <input id="nameEdit" name="nameEdit" type="text" placeholder="Name"
                                            class="form-control" required="enabled" readonly>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-6 pt-2">

                                        <select id="typeEditSlct" class="form-select">

                                        </select>
                                    </div>
                                    <div class="col-lg-6 pt-2">
                                        <input class="form-control " id="container_size_edit" name="container_size_edit" type="number" step=".1" value="" placeholder="Container Size">


                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div id="div3" style='display:none'>
                                        <input data-nameEdit="TT" type="radio" id="Radio1" name="tt_tlEdit"
                                            value="1">
                                        <label for="Radio1">TT</label><br>
                                        <input data-nameEdit="TL" type="radio" id="Radio1" name="tt_tlEdit"
                                            value="2">
                                        <label for="Radio2">TL</label><br>
                                    </div>
                                    <div id="div4" style='display:none'>
                                        <input data-nameEdit="Big Hole" type="radio" id="Radio1" name="tt_tlEdit"
                                            value="1">
                                        <label for="Radio1">Big Hole</label><br>
                                        <input data-nameEdit="Small Hole" type="radio" id="Radio1" name="tt_tlEdit"
                                            value="2">
                                        <label for="Radio2">Small Hole</label><br>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        Dimension
                                        <select id="dimtypeEditSlct" name="dimensionEdit" class="form-select">

                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        Brand
                                        <select id="modeltypeEditSlct" name="model_idEdit" class="form-select">

                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        Material
                                        <select id="materialtypeEditSlct" name ="wheel_material_id_Edit"
                                            class="form-select">

                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <label class="" for="">Description</label>
                                            <textarea name="descriptioEditn" id="descriptioEditn" cols="30" rows="2" class="form-control"></textarea>

                                    </div>
                                    <div class="col-lg-4">
                                        <label for="">Limit</label>
                                        <input id="limit_orderEdit" name="limit_orderEdit" type="text"
                                            placeholder="Limit" class="form-control">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="">Do you want To Active Limit Order ?</label>
                                        <input type="checkbox" id="notifyEdit" name="notifyEdit" value="1">
                                    </div>

                                </div>

                            </div>
                            <div class="tab-pane fade" id="relatedWheelEdit" role="tabpanel"
                                aria-labelledby="profile-tab">
                                <div class="row">
                                    <div class="col">
                                        Related Wheel
                                        <select id="relatedWheelEditSlct" name="relatedWheelEditSlct"
                                            class="form-select">

                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <table class="table" id="relatedwheelِEditTbl">
                                            <tbody>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="wheelSpecsEdit" role="tabpanel"
                                aria-labelledby="profile-tab">
                                <button id="newWheelSpecRowEdit" class="my-2 btn btn-warning">New Row</button>
                                <table id="wheelSpecsEditTbl">


                                </table>
                            </div>
                            <div class="tab-pane fade" id="wheelImageEdit" role="tabpanel"
                                aria-labelledby="contact-tab">
                                <div class="upload__box">
                                    <div class="upload__btn-box">
                                        <label class="upload__btn">
                                            <p class="m-0">Upload images</p>
                                            <input type="file" multiple="" name="wheelImgEdit[]"
                                                data-max_length="20" class="upload__inputfileWheelEdit">
                                        </label>
                                    </div>
                                    <div class="upload__img-wrapWheelEdit"></div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deletewheelB" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel2">Delete wheel</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method='post' action="" class="deleteform" id='deleteform'>
                    <input type="hidden" name="wheel_id" id="wheel_iddel" value="">
                    @csrf
                    @method('DELETE')

                    <div class="modal-body">
                        <h1>هل تريد مسح هذا العنصر</h1>
                        <input type='text' placeholder='Wheel Name' readonly class='form-control' id='wdel_name'>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" value="DELETE" class= "py-1 btn btn-danger " data-bs-dismiss="modal">'.
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="{{ URL::asset('js/wheels.js') }}"></script>

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
