@extends('layouts.master')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<!--<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.2.0/css/searchPanes.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ URL::asset('assets/css/dataTablesButtons.css') }}">



<style>
    /*.modal-content{*/
    /*    width: 50vw !important;*/
    /*}*/
    
    /*#kitsDT td:nth-child(5){*/
    /*    overflow-y: scroll;*/
    /*    height: 100px !important;*/
    /*    display: block;*/
    /*}*/
    
    /*#kitsDT td:nth-child(3){*/
    /*    overflow-y: scroll;*/
    /*    height: 100px !important;*/
        /*display: block;*/
    /*}*/
    
     #kitsDT td{
        /*overflow-y: scroll;*/
        max-height: 100px !important;
        /*display: block;*/
    }
    table{
        font-family: 'Droid Arabic Naskh', serif;
    }
    #kitSpecsTbl tr{
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
    #kitsDT_paginate{
        float: unset;
    text-align: center;
    }

</style>
@endsection
@section('title')
 MOTAGRA
@stop


@section('content')


    <div class="main-content">
        <div class="page-content">
            <h1 class="text-center text-info">المتاجرة</h1>
            <div class="row">
                <div class="col-lg-6">
                     <table id="motagraTbl1" class="table">
                        <thead>
                            <tr>
                                <td>المبلغ</td>
                                <td>رقم الحساب</td>
                                <td>البيــــــان</td>
                        
                            </tr>
                        </thead>
                        <tbody>
                              @php
                                    global $profit ;
                                @endphp
                            @foreach($data[0] as $madin)
                              @php
                                     $profit += ($madin->madin );
                                @endphp
                                 <tr>
                                    <td>{{$madin->madin }}</td>
                                    <td><a href="/qayd/searchaccount">{{$madin->accountant_number}}</a> </td>
                                    <td>{{$madin->name}}</td>
                                </tr>
                            @endforeach
                               <tr>
                                      
                                    <td colspan="2">{{$profit}}</td>
                                     <td> مجمل الربح </td>
                                    
                                </tr>
                        </tbody>
                    </table>         
                </div>
                <div class="col-lg-6">
                     <table id="motagraTbl2" class="table">
                        <thead>
                            <tr>
                                <td>المبلغ</td>
                                <td>رقم الحساب</td>
                                <td>البيــــــان</td>
                            </tr>
                        </thead>
                        <tbody>
                                @php
                                    global $losses ;
                                @endphp
                           @foreach($data[1] as $dayin)
                                @php
                                     $losses += ($dayin->dayin);
                                @endphp
                                <tr>
                                    <td>{{$dayin->dayin }}</td>
                                    <td>{{$dayin->accountant_number}}</td>
                                    <td>{{$dayin->name}}</td>
                                </tr>
                            @endforeach
                                 <tr>
                                      
                                    <td colspan="2">{{$losses}}</td>
                                     <td> مجمل الخسارة </td>
                                    
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
           

        </div>
    </div>

@section('js')
 <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

@endsection
