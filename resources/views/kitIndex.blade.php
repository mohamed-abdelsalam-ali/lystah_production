@extends('layouts.master')
@section('css')



<style>

body {
  font-family: Arial, sans-serif;
}

table {
  width: 100%;
  border-collapse: collapse;
}

 td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: right;
}
th {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: justify !important;
}

.scrollable-cell ul{
  max-height: 50px !important; /* Adjust the max height as needed */
  /* overflow: hidden  !important; */
  overflow-y: scroll !important;
}
.list-of-elements ul{
    /* Styles for your list elements */
    /* Example styles: */
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
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
    .upload__img-wrapEdit {
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
    /* #kitsDT_paginate{
        float: unset;
    text-align: center;
    } */

</style>
@endsection
@section('title')
 KITS
@stop


@section('content')



    <div class="main-content">
        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Kits</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Kits</li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-primary m-1 float-left" data-bs-toggle="modal" data-bs-target="#staticBackdrop">إضافة</button>

                </div>
              </div>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-head">

                        </div>
                        <div class="card-body table-responsive">
                            <table id="kitsDT" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>صورة الكيت</th>
                                        <th>الإسم</th>
                                        <th>أصناف الكيت</th>
                                        <th> أرقام الكيت</th>
                                        <th>موديلات الكيت </th>
                                        <th>Action</th>

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

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">  إضافة كيت جديد</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  method="post" action="{{route("kitStore")}}" class="needs-validation" novalidate enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Main Data</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#kitPart" type="button" role="tab" aria-controls="kitPart" aria-selected="false">Kit Parts</button>
                            </li>
                        <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Numbers</button>
                        </li>
                        <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Specs</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact1" type="button" role="tab" aria-controls="contact" aria-selected="false">Brand</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact2" type="button" role="tab" aria-controls="contact" aria-selected="false">Images</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="p-3 row" >
                                <div class="col-6">
                                    <input id="" name="name" type="text" placeholder="Arabic Name" class="form-control" required="enabled">
                                </div>
                                <div class="col-6">
                                    <input id="" name="engname" type="text" placeholder="English Name" class="form-control" required="enabled">
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-2" for="">Notes</label>
                                <div class="col-10">
                                    <textarea name="notes" id="" cols="30" rows="5" value="" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="mt-2 row">
                                <div class="col-6">
                                    <label for="">Limit</label>
                                    <input id="" name="limit" type="text" placeholder="Limit" value="0" class="form-control" >
                                </div>
                                <div class="col-6">
                                    <label for="">Notify</label>
                                    <input type="checkbox" id="notify" name="notify" value="1">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="kitPart" role="tabpanel" aria-labelledby="profile-tab">
                            <h3>Select Part</h3>
                            <select name="partSlct[]" class="form-control partSlct" id="partSlct"></select>
                            <table id="kitPartTbl" class="table kitPartTbl">


                            </table>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <button type = "button" id="newKitNumberRow" class="my-2 btn btn-warning">New Number</button>
                            <table id="kitNumberTbl">
                                <tr>
                                    <td><input class="form-control" type="text" name="kitNumber[]" id=""></td>
                                    <td><select name="supplierSlct[]" class="form-control supplierSlct" id="supplierSlct1"></select></td>
                                    <td>REMOVE</td>
                                </tr>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

                            <button id="newKitSpecRow" class="my-2 btn btn-warning">New Row</button>
                            <table id="kitSpecsTbl">


                            </table>
                        </div>
                        <div class="tab-pane fade" id="contact1" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="row">
                                <div class="col">
                                    <div class="col-3">
                                        TYPE
                                        <select id="brandtypeSlct" class="form-select" >

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-4">
                                    BRAND
                                    <select id="brandSlct"  class="form-select" >

                                    </select>
                                </div>
                                <div class="col-4">
                                    <button type="button" class="p-0 m-0 btn btn-ghost-primary" id="selectallmodel" > Select All Model</button>
                                    <select id="modelSlct" class="form-select" >

                                    </select>
                                </div>
                                <div class="col-4">
                                    <button type="button" class="p-0 m-0 btn btn-ghost-primary" id="selectallseries" > Select All Series</button>

                                    <select id="seriesSlct" class="form-select" >

                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col">
                                    <table class="table" id="kitBrandTbl">

                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="contact2" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="upload__box">
                                <div class="upload__btn-box">
                                <label class="upload__btn">
                                    <p class="m-0">Upload images</p>
                                    <input type="file" multiple="" name="kitImg[]" data-max_length="20" class="upload__inputfile">
                                </label>
                                </div>
                                <div class="upload__img-wrap" id="upload_img-wrapkitAdd"></div>
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

    <div class="modal fade" id="editKit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel"> تعديل كيت</h5>

              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="kit_id" id="kit_id" value="">
                <input type="hidden" name="imgURLsInp[]" id="imgURLsInp[]" value="">
                @method("PUT")
                @csrf
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#main" type="button" role="tab" aria-controls="main" aria-selected="true">Main Data</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#kitPartEdit" type="button" role="tab" aria-controls="kitPartEdit" aria-selected="false">Kit Parts</button>
                            </li>
                        <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#number" type="button" role="tab" aria-controls="number" aria-selected="false">Numbers</button>
                        </li>
                        <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button" role="tab" aria-controls="specs" aria-selected="false">Specs</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#brand" type="button" role="tab" aria-controls="brand" aria-selected="false">Brand</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#image" type="button" role="tab" aria-controls="image" aria-selected="false">Images</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="home-tab">
                            <div class="p-3 row" >
                                <div class="col-6">
                                    <input id="nameedit" name="nameedit" type="text" value="" class="form-control" required="enabled">
                                </div>
                                <div class="col-6">
                                    <input id="engnameedit" name="engnameedit" type="text" placeholder="English Name" class="form-control" required="enabled">
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-2" for="">Notes</label>
                                <div class="col-10">
                                    <textarea id="notesedit" name="notesedit" id="" cols="30" rows="5" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="mt-2 row">
                                <div class="col-6">
                                    <label for="">Limit</label>
                                    <input id="limitedit" name="limitedit" type="text" placeholder="Limit" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label for="">Notify</label>
                                    <input type="checkbox" id="notifyedit" name="notifyEdit" value="1">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="kitPartEdit" role="tabpanel" aria-labelledby="profile-tab">
                            <select name="partSlctEdit[]" class="form-control partSlctEdit" id="partSlctEdit"></select>

                            <table id="kitPartTblEdit">
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="number" role="tabpanel" aria-labelledby="profile-tab">
                            <button type = "button" id="newKitNumberRowEdit" class="my-2 btn btn-warning">New Number</button>
                            <table id="kitNumberTblEdit">
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="specs" role="tabpanel" aria-labelledby="contact-tab">

                            <button id="newKitSpecRowEdit" class="my-2 btn btn-warning">New Row</button>
                            <table id="kitSpecsTblEdit">
                            </table>
                        </div>
                        <div class="tab-pane fade" id="brand" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="row">
                                <div class="col">
                                    <div class="col-3">
                                        TYPE
                                        <select id="brandtypeSlctEdit" class="form-select" >

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-4">
                                    BRAND
                                    <select id="brandSlctEdit"  class="form-select" >

                                    </select>
                                </div>
                                <div class="col-4">
                                    <button type="button" class="p-0 m-0 btn btn-ghost-primary" id="selectallmodelEdit" > Select All Model</button>
                                    <select id="modelSlctEdit" class="form-select" >

                                    </select>
                                </div>
                                <div class="col-4">
                                    <button type="button" class="p-0 m-0 btn btn-ghost-primary" id="selectallseriesEdit" > Select All Series</button>

                                    <select id="seriesSlctEdit" class="form-select" >

                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col">
                                    <table class="table" id="kitBrandTblEdit">

                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="image" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="upload__box">
                                <div class="upload__btn-box">
                                <label class="upload__btn">
                                    <p class="m-0">Upload images</p>
                                    <input type="file" multiple="" name="kitImgEdit[]" data-max_length="20" class="upload__inputfileEdit">
                                </label>
                                </div>
                                <div class="upload__img-wrapEdit">
                                </div>
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
 <div class="modal fade" id="deletekitB" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel2">Delete Part</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method='post' action="" class="deleteform" id='deleteform'>
                    <input type="hidden" name="kit_id" id="kit_iddel" value="">
                    @csrf
                    @method('DELETE')

                    <div class="modal-body">
                        <h1>هل تريد مسح هذا العنصر</h1>
                        <input type='text' placeholder='Part Name' class='form-control' id='pdel_name'>
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

    <script src="{{ URL::asset('js/kits.js')}}"></script>
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

    $("#partSlct").select2();
    $("#partSlctEdit").select2();
    </script>
@endsection
