@extends('layouts.master')
@section('css')



<style>

</style>
@endsection
@section('title')
 Wheels
@stop


@section('content')

@if ($message = Session::get('success'))
    <div class="alert alert-success" style="z-index: 88888 !important;" role="alert">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>



@endif

    <div class="main-content">
        <div class="page-content">
            <h1 class="text-center text-info">Wheels</h1>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-head">
                            <div class="row">
                                <div class="col-8"></div>

                                <div class="px-4 mt-3 col-4 text-end">
                                    <button class="btn addButton btn-info" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Add New <i class="ri-add-fill"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="wheelsDT" class="table display table-bordered dt-responsive dataTable dtr-inline" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th class="text-center">Dimension</th>
                                        <th class="sorting sorting_asc">Material</th>
                                        <th class="sorting sorting_asc">Model</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>


                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th class="text-center">Dimension</th>
                                        <th class="sorting sorting_asc">Material</th>
                                        <th class="sorting sorting_asc">Model</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Add New Wheel</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  method="post" action="{{route("wheelStore")}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Main Data</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="related-wheel-tab" data-bs-toggle="tab" data-bs-target="#relatedWheel" type="button" role="tab" aria-controls="contact" aria-selected="false">Related Wheel</button>
                            </li>
                        <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Spcs</button>
                        </li>
                        <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Image</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row">
                                <div class="col">

                                        <select id="typeSlct" class="form-select" >
                                            <option value="0">اختر نوع العجلة</option>
                                            <option value="1">كاوتش</option>
                                            <option value="2">جنط</option>
                                            <option value="3">داخلي</option>
                                        </select>
                                </div>
                            </div>
                            <br>
                            <div class="row" >
                                <div class="col">
                                    <input id="name" name="name" type="text" placeholder="Name" class="form-control" required="enabled" readonly>
                                </div>

                            </div>
                            <div class="p-3 row">
                                <label class="col-2" for="">Description</label>
                                <div class="col-10">
                                    <textarea name="description" id="" cols="30" rows="5" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    Dimension
                                    <select id="dimtypeSlct" name="dimension" class="form-select" >

                                    </select>
                                </div>
                                <div class="col-4">
                                    Brand
                                    <select id="modeltypeSlct" name="model_id" class="form-select" >

                                    </select>
                                </div>
                                <div class="col-4">
                                    Material
                                    <select id="materialtypeSlct" name ="wheel_material_id" class="form-select" >

                                    </select>
                                </div>
                            </div>
                            <br>
                            <div id="div1" style='display:none'>
                                <input data-name="TT" type="radio" id="Radio1" name="tt_tl" value="1" >
                                <label for="Radio1">TT</label><br>
                                <input data-name="TL" type="radio" id="Radio1" name="tt_tl" value="2">
                                <label for="Radio2">TL</label><br>
                            </div>
                            <div id="div2" style='display:none'>
                                <input  data-name="Big Hole" type="radio" id="Radio1" name="tt_tl" value="1" >
                                <label for="Radio1">Big Hole</label><br>
                                <input  data-name="Small Hole" type="radio" id="Radio1" name="tt_tl" value="2">
                                <label for="Radio2">Small Hole</label><br>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col">
                                    <label for="">Limit</label>
                                    <input id="" name="limit_order" type="text" placeholder="Limit" class="form-control" >
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <label for="">Do you want To Active Limit Order ?</label>
                                    <input type="checkbox" id="notify" name="notify" value="1">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="relatedWheel" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row">
                                <div class="col">
                                    Related Wheel
                                    <select id="relatedWheelSlct" name="relatedWheelSlct" class="form-select" >

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
                            <button id="newWheelSpecRow" class="my-2 btn btn-warning">New Row</button>
                            <table id="wheelSpecsTbl">


                            </table>
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

                            <div class="upload__box">
                                <div class="upload__btn-box">
                                <label class="upload__btn">
                                    <p class="m-0">Upload images</p>
                                    <input type="file" multiple="" name="wheelImg[]" data-max_length="20" class="upload__inputfileWheel">
                                </label>
                                </div>
                                <div class="upload__img-wrapWheel"></div>
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

    <div class="modal fade" id="editWheel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Edit Wheel</h5>

              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="wheel_id" id="wheel_id" value="">
                <input type="hidden" name="imgURLsInp[]" id="imgURLsInp[]" value="">
                @method("PUT")
                @csrf
                <div class="modal-body">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="mainWheelEdit-tab" data-bs-toggle="tab" data-bs-target="#mainWheelEdit" type="button" role="tab" aria-controls="mainWheelEdit" aria-selected="true">Main Data</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="relatedWheelEdit-tab" data-bs-toggle="tab" data-bs-target="#relatedWheelEdit" type="button" role="tab" aria-controls="relatedWheelEdit" aria-selected="false">Related Wheel</button>
                            </li>
                        <li class="nav-item" role="presentation">
                        <button class="nav-link" id="wheelSpecsEdit-tab" data-bs-toggle="tab" data-bs-target="#wheelSpecsEdit" type="button" role="tab" aria-controls="wheelSpecsEdit" aria-selected="false">Spcs</button>
                        </li>
                        <li class="nav-item" role="presentation">
                        <button class="nav-link" id="wheelImageEdit-tab" data-bs-toggle="tab" data-bs-target="#wheelImageEdit" type="button" role="tab" aria-controls="wheelImageEdit" aria-selected="false">Image</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="mainWheelEdit" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row">
                                <div class="col">

                                        <select id="typeEditSlct" class="form-select" >

                                        </select>
                                </div>
                            </div>
                            <br>
                            <div class="row" >
                                <div class="col">
                                    <input id="nameEdit" name="nameEdit" type="text" placeholder="Name" class="form-control" required="enabled" readonly>
                                </div>

                            </div>
                            <div class="p-3 row">
                                <label class="col-2" for="">Description</label>
                                <div class="col-10">
                                    <textarea name="descriptioEditn" id="" cols="30" rows="5" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    Dimension
                                    <select id="dimtypeEditSlct" name="dimensionEdit" class="form-select" >

                                    </select>
                                </div>
                                <div class="col-4">
                                    Brand
                                    <select id="modeltypeEditSlct" name="model_idEdit" class="form-select" >

                                    </select>
                                </div>
                                <div class="col-4">
                                    Material
                                    <select id="materialtypeEditSlct" name ="wheel_material_id_Edit" class="form-select" >

                                    </select>
                                </div>
                            </div>
                            <br>
                            <div id="div3" style='display:none'>
                                <input data-nameEdit="TT" type="radio" id="Radio1" name="tt_tlEdit" value="1" >
                                <label for="Radio1">TT</label><br>
                                <input data-nameEdit="TL" type="radio" id="Radio1" name="tt_tlEdit" value="2">
                                <label for="Radio2">TL</label><br>
                            </div>
                            <div id="div4" style='display:none'>
                                <input  data-nameEdit="Big Hole" type="radio" id="Radio1" name="tt_tlEdit" value="1" >
                                <label for="Radio1">Big Hole</label><br>
                                <input  data-nameEdit="Small Hole" type="radio" id="Radio1" name="tt_tlEdit" value="2">
                                <label for="Radio2">Small Hole</label><br>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col">
                                    <label for="">Limit</label>
                                    <input id="limit_orderEdit" name="limit_orderEdit" type="text" placeholder="Limit" class="form-control" >
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <label for="">Do you want To Active Limit Order ?</label>
                                    <input type="checkbox" id="notifyEdit" name="notifyEdit" value="1">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="relatedWheelEdit" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row">
                                <div class="col">
                                    Related Wheel
                                    <select id="relatedWheelEditSlct" name="relatedWheelEditSlct" class="form-select" >

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
                        <div class="tab-pane fade" id="wheelSpecsEdit" role="tabpanel" aria-labelledby="profile-tab">
                            <button id="newWheelSpecRowEdit" class="my-2 btn btn-warning">New Row</button>
                            <table id="wheelSpecsEditTbl">


                            </table>
                        </div>
                        <div class="tab-pane fade" id="wheelImageEdit" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="upload__box">
                                <div class="upload__btn-box">
                                <label class="upload__btn">
                                    <p class="m-0">Upload images</p>
                                    <input type="file" multiple="" name="wheelImgEdit[]" data-max_length="20" class="upload__inputfileWheelEdit">
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

@endsection

@section('js')

    <script src="{{ URL::asset('js/wheels.js')}}"></script>


@endsection
