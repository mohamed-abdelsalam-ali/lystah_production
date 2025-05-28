@extends('layouts.master')
@section('css')



<style>
    /*.modal-content{*/
    /*    width: 50vw !important;*/
    /*}*/
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
    } .upload__inputfilePartEdit {
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
</style>
@endsection
@section('title')
 PARTS
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
            <div class="row">
                <div class="col">
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
                        <div class="card-body table-responsive">
                            <table id="partsDT" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="">#</th>
                                        <th class="">Image</th>
                                        <th class="">Name</th>
                                        <th>English Name</th>
                                        <th>insertion_date</th>
                                        <th>description</th>
                                        <th>limit_order</th>
                                        <th>sub_group_id</th>
                                        <th>Sub Group</th>
                                        <th>Group</th>
                                        <th>Number</th>
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

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-full-width">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Emara Spare Parts</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  method="post" action="{{url('store-part')}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Main Data</button>
                        </li>
                        <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Numbers</button>
                        </li>
                        <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Specs</button>
                        </li>
                         <li class="nav-item" role="presentation">
                            <button class="nav-link" id="related-part-tab" data-bs-toggle="tab" data-bs-target="#related-part" type="button" role="tab" aria-controls="related-part" aria-selected="false">Related Parts</button>
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
                                    <input id="" name="partarname" type="text" placeholder="Arabic Name" class="form-control" required="">
                                </div>
                                <div class="col-6">
                                    <input id="" name="partengname" type="text" placeholder="English Name" class="form-control" required="">
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-2" for="">Desc.</label>
                                <div class="col-10">
                                    <textarea name="partdesc" id="" cols="30" rows="5" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="mt-2 row">
                                <div class="col-6">
                                    <label for="">Group</label>
                                    <select class="form-control" name="groupSlct" id="groupSlct"></select>
                                </div>
                                <div class="col-6">
                                    <label for="">Sub Groups</label>
                                    <select class="form-control" name="SgroupSlct" id="SgroupSlct"></select>
                                </div>
                            </div>
                            <div class="mt-2 row">
                                <div class="col-6">
                                    <label for="">Limit</label>
                                    <input id="" name="limit" type="text" placeholder="Limit" class="form-control" >
                                </div>
                                <div class="col-6">
                                    <label for="">Notify</label>
                                    <input type="checkbox" id="notify" name="notify" value="1">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <button id="newPartNumberRow" class="my-2 btn btn-warning">New Number</button>
                            <table id="partNumberTbl">
                                <tr>
                                    <td><input class="form-control" type="text" name="partNumber[]" id=""></td>
                                    <td><select name="supplierSlct[]" class="form-control supplierSlct" id="supplierSlct1"></select></td>
                                    <td>REMOVE</td>
                                </tr>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

                            <button id="newPartSpecRow" class="my-2 btn btn-warning">New Row</button>
                            <table id="partSpecsTbl">


                            </table>
                        </div>
                          <div class="tab-pane fade" id="related-part" role="related_part" aria-labelledby="related-part-tab">
                            <div class="row">
                                <div class="col-4">
                                    <select id="relatedPartSlct" class="form-select" >

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
                                    <!--<button type="button" class="p-0 m-0 btn btn-ghost-primary" id="selectallmodel" > Select All Model</button>-->
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
                                    <table class="table" id="partBrandTbl">

                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="contact2" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="upload__box">
                                <div class="upload__btn-box">
                                <label class="upload__btn">
                                    <p class="m-0">Upload images</p>
                                    <input type="file" multiple="" name="partImg[]" data-max_length="20" class="upload__inputfile">
                                </label>
                                </div>
                                <div class="upload__img-wrapPart"></div>
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

    <div class="modal fade" id="editPart" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Edit Part</h5>

              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="part_id" id="part_id" value="">
                <input type="hidden" name="imgURLsInp[]" id="imgURLsInp[]" value="">
                @method("PUT")
                @csrf
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#mainPartEdit" type="button" role="tab" aria-controls="mainPartEdit" aria-selected="true">Main Data</button>
                            </li>
                        <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#partNumberEdit" type="button" role="tab" aria-controls="partNumberEdit" aria-selected="false">Numbers</button>
                        </li>
                        <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#partSpecsEdit" type="button" role="tab" aria-controls="partSpecsEdit" aria-selected="false">Specs</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="related-part_edit-tab" data-bs-toggle="tab" data-bs-target="#related-part_edit" type="button" role="tab" aria-controls="related-part_edit" aria-selected="false">Related Parts</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#partBrandEdit" type="button" role="tab" aria-controls="partBrandEdit" aria-selected="false">Brand</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#partImageEdit" type="button" role="tab" aria-controls="partImageEdit" aria-selected="false">Images</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="mainPartEdit" role="tabpanel" aria-labelledby="home-tab">
                            <div class="p-3 row" >
                                <div class="col-6">
                                    <input id="nameedit" name="nameedit" type="text" value="" class="form-control" required="enabled">
                                </div>
                                <div class="col-6">
                                    <input id="engnameedit" name="engnameedit" type="text" placeholder="English Name" class="form-control" required="enabled">
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-2" for="">Description</label>
                                <div class="col-10">
                                    <textarea id="descedit" name="descedit" id="" cols="30" rows="5" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="mt-2 row">
                                <div class="col-6">
                                    <label for="">Group</label>
                                    <select class="form-control" name="groupSlctEdit" id="groupSlctEdit"></select>
                                </div>
                                <div class="col-6">
                                    <label for="">Sub Groups</label>
                                    <select class="form-control" name="SgroupSlctEdit" id="SgroupSlctEdit"></select>
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
                        <div class="tab-pane fade" id="partNumberEdit" role="tabpanel" aria-labelledby="profile-tab">
                            <button type = "button" id="newPartNumberRowEdit" class="my-2 btn btn-warning">New Number</button>
                            <table id="partNumberTblEdit">
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="partSpecsEdit" role="tabpanel" aria-labelledby="contact-tab">
                            <button id="newPartSpecRowEdit" class="my-2 btn btn-warning">New Row</button>
                            <table id="partSpecsTblEdit">
                            </table>
                        </div>
                        <div class="tab-pane fade" id="partBrandEdit" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="row">
                                <div class="col">
                                    <div class="col-3">
                                        TYPE
                                        <select id="partbrandtypeSlctEdit" class="form-select" >

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-4">
                                    BRAND
                                    <select id="partbrandSlctEdit"  class="form-select" >

                                    </select>
                                </div>
                                <div class="col-4">
                                    <!--<button class="p-0 m-0 btn btn-ghost-primary" id="selectallmodel" > Select All Model</button>-->
                                    <select id="partmodelSlctEdit" class="form-select" >

                                    </select>
                                </div>
                                <div class="col-4">
                                    <button type="button" class="p-0 m-0 btn btn-ghost-primary" id="editselectallseries" > Select All Series</button>

                                    <select id="partseriesSlctEdit" class="form-select" >

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
                        <div class="tab-pane fade" id="related-part_edit" role="related_part" aria-labelledby="related-part-tab">
                            <div class="row">
                                <div class="col-4">
                                    <select id="relatedPartSlct_edit" class="form-select" >

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
                                    <p class="m-0">Upload images</p>
                                    <input type="file" multiple="" name="partImgEdit[]" data-max_length="20" class="upload__inputfilePartEdit">
                                </label>
                                </div>
                                <div class="upload__img-wrapPartEdit">
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


    <div class="modal fade" id="deletepartB" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel2" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel2">Delete Part</h5>

              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method='post' action=""  class="deleteform" id='deleteform'>
                <input type="hidden" name="part_id" id="part_iddel" value="">
                 @csrf
                @method("DELETE")

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

    <script src="{{ URL::asset('js/parts.js')}}"></script>
    <script>
        $('.nav-link').removeClass('active');
        $('#partli a').addClass('active');
        $('.nav-item').removeClass('active');
        $('.partli').addClass('active');

        
       

    </script>

@endsection
