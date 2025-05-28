@extends('layouts.master')
@section('css')


<style>

@media print
{
html, body { height: auto; }
.dt-print-table thead {
        border: 0 none !important;
        display : none;
    }
}

.modal-content {
    width: 50vw !important;
}

.dt-buttons{
    float: left!important;
}


.dt-buttons button{
    display: inline-block;
    border: 1px solid #4f4f4f;
    border-radius: 4px;
    transition: all 0.2s ease-in;
    position: relative;
    overflow: hidden;
    font-size: 17px;
    color: black;
    z-index: 1;

}

.dt-buttons button:hover {
    color: #ffffff;
    border: 1px solid #39bda7;
}

.dt-buttons button:before {
 content: "";
 position: absolute;
 left: 50%;
 transform: translateX(-50%) scaleY(1) scaleX(1.25);
 top: 100%;
 width: 140%;
 height: 180%;
 background-color: rgba(0, 0, 0, 0.05);
 border-radius: 50%;
 display: block;
 transition: all 0.5s 0.1s cubic-bezier(0.55, 0, 0.1, 1);
 z-index: -1;
}
.dt-buttons button:after {
content: "";
 position: absolute;
 left: 55%;
 transform: translateX(-50%) scaleY(1) scaleX(1.45);
 top: 180%;
 width: 160%;
 height: 190%;
 background-color: #39bda7;
 border-radius: 50%;
 display: block;
 transition: all 0.5s 0.1s cubic-bezier(0.55, 0, 0.1, 1);
 z-index: -1;
}

.dt-buttons button:hover:before {
 top: -35%;
 background-color: #39bda7;
 transform: translateX(-50%) scaleY(1.3) scaleX(0.8);
}

.dt-buttons button:hover:after {
 top: -45%;
 background-color: #39bda7;
 transform: translateX(-50%) scaleY(1.3) scaleX(0.8);
}

table {
    font-family: 'Droid Arabic Naskh', serif;
}

#kitSpecsTbl tr {
    margin-top: 5px;
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

#kitsDT_paginate {
    float: unset;
    text-align: center;
}
</style>
@endsection
@section('title')
Part Numbers
@stop

@section('content')


<div class="main-content">
    <div class="page-content">
        <h1 class="text-center text-info">Part Numbers</h1>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-head">
                        <!--<div class="row">-->
                        <!--    <div class="col-8"></div>-->
                        <!--    <div class="col-2 p-0 mt-3 text-end">-->
                        <!--        <a class="btn btn-info" href="customSearch">Custom Search<i-->
                        <!--                class="ri-user-search-fill"></i></a>-->
                        <!--    </div>-->
                        <!--</div>-->
                    </div>
                    <div class="card-body">
                        <table id="partNumbersDT" class="display text-center table table-bordered dt-responsive dataTable dtr-inline"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center">Part Number</th>
                                    <th class="text-center sorting sorting_asc">Part Name Arabic</th>
                                    <th class="text-center sorting sorting_asc">Part Name English</th>
                                    <th class="text-center">Supplier</th>
                                    <th class="text-center">show</th>
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
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>



<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="{{ URL::asset('assets/pdfMakeAr/vfs_fonts.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="{{ URL::asset('js/partNumbers.js')}}"></script>

@endsection
