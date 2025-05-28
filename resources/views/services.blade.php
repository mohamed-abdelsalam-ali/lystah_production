@extends('layouts.posMaster')
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
    
    table{
        font-family: 'Droid Arabic Naskh', serif;
    }
    .row{
        align-items: self-end
    }

    .radio-button-container {
    display: flex;
    align-items: center;
    gap: 24px;
    }

    .radio-button {
    display: inline-block;
    position: relative;
    cursor: pointer;
    }

    .radio-button__input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
    }

    .radio-button__label {
    display: inline-block;
    padding-left: 30px;
    margin-bottom: 10px;
    position: relative;
    font-size: 15px;
    color: #060606;
    font-weight: 600;
    cursor: pointer;
    text-transform: uppercase;
    transition: all 0.3s ease;
    }

    .radio-button__custom {
    position: absolute;
    top: 0;
    left: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #555;
    transition: all 0.3s ease;
    }

    .radio-button__input:checked + .radio-button__label .radio-button__custom {
    background-color: #4c8bf5;
    border-color: transparent;
    transform: scale(0.8);
    box-shadow: 0 0 20px #4c8bf580;
    }

    .radio-button__input:checked + .radio-button__label {
    color: #4c8bf5;
    }

    .radio-button__label:hover .radio-button__custom {
    transform: scale(1.2);
    border-color: #4c8bf5;
    box-shadow: 0 0 20px #4c8bf580;
    }
    #addServicesTbl>:not(caption)>*>*  {
        padding : 0rem 1.6rem !important;   
    }
</style>
@endsection
@section('title')
 SERVICES
@stop


@section('content')


<form  method="POST" action="{{ route('serviceInvoice.store') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="total" name="total">
    <input type="hidden" id="totaltax" name="totaltax">
    <input type="hidden" id="totalbefortax" name="totalbefortax">
    <input type="hidden" id="remain" name="remain">
    <input type="hidden" id="client_id" name="client_id">
    <input type="hidden" id="item_id" name="item_id">
            <input type="hidden" id="store_id" name="store_id" value="{{$store_id}}">


    <div class="main-content">
        <div class="page-content">
            
            <h1 class="text-center text-info">Services</h1>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body" >
                            <div class="row" >
                                <div class="border border-secondary col-sm-6"  style="padding: 5px">
                                    <h4 style=" text-align: start">نوع المعدة</h4>
                                    <div class="radio-button-container justify-content-center">
                                        <div class="radio-button">
                                            <input type="radio" name="serviceType" class="radio-button__input" value="Tractor" id="tractor" required="required">
                                            <label class="radio-button__label" for="tractor">
                                                <span class="radio-button__custom"></span>
                                                جرار
                                            </label>
                                        </div>
                                        <div class="radio-button">
                                            <input type="radio" name="serviceType" class="radio-button__input" value="Equipment" id="equipment" >
                                            <label class="radio-button__label" for="equipment">
                                                <span class="radio-button__custom"></span>
                                                معدة
                                            </label>
                                        </div>
                                        <div class="radio-button">
                                            <input type="radio" name="serviceType" class="radio-button__input" value="Clark" id="clark" >
                                            <label class="radio-button__label" for="clark">
                                                <span class="radio-button__custom"></span>
                                                كلارك
                                            </label>
                                        </div>
                                        
                                    </div>
                                </div>
                                
                                <div class="border border-secondary col-sm-6" style="padding: 5px">
                                    <h4 style=" text-align: start">نوع الصيانة</h4>
                                    <div class="radio-button-container justify-content-center">
                                        <div class="radio-button">
                                            <input type="radio" name="serviceOption" class="radio-button__input" value="internal" id="internal" required="required">
                                            <label class="radio-button__label" for="internal">
                                                <span class="radio-button__custom"></span>
                                                داخلي
                                            </label>
                                        </div>
                                        <div class="radio-button">
                                            <input type="radio" name="serviceOption" class="radio-button__input" value="external" id="external">
                                            <label class="radio-button__label" for="external">
                                                <span class="radio-button__custom"></span>
                                                خارجي
                                            </label>
                                        </div>
                                        
                                        
                                    </div>
                                </div>
                            </div>
                            <div style=" display: none" id="internalDiv">
                                <div class="mt-3 border row border-secondary ">
                                    <h4 style=" text-align: start">داخلي</h4>
                                    
                                    <div class="offset-2 col-8 ">
                                        <div>
                                            <select name="optionSelect" id="optionSelect">
                                                <option value="0">اختر نوع المعدة أولا</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div> 
                            </div>
                            <div style=" display: none" id="externalDiv">
                                <div class="mt-3 border row border-secondary ">
                            
                                            <h4 style=" text-align: start">خارجي</h4>
                                            
                                            <div class="row">
                                                <div class="col-6" style="text-align: start;">
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <label for="name">الإسم</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <select name="name" id="name">
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-6" style="text-align: start;">
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <label for="plate">رقم اللوحة</label>

                                                        </div>
                                                        <div class="col-6">
                                                            <input class="inputText form-control" name="plate" type="text" placeholder="  رقم اللوحة  " >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6" style="text-align: start;">
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <label for="name">تليفون</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <input class="inputText form-control"id = "phone" name="phone" type="text" placeholder="  تليفون  " >
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-6" style="text-align: start;">
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <label for="plate">التاريخ</label>

                                                        </div>
                                                        <div class="col-6">
                                                            <input class="inputText form-control" id="date" name="date" type="date" value="{{date('Y-m-d')}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                </div>
                            </div>
                            <div class="p-3 mt-3 border border-secondary row text-end d-flex align-items-start" style="padding: 5px" id="serviceDiv">
                                <h4 style=" text-align: start">الصيانة</h4>
                                
                                <div class=" col-sm-6" >

                                    <div class="row">
                                        <div class="col">
                                            <select name="serviceSelect" id="serviceSelect"></select>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class=" col-sm-6">
                                    <table class="table table-sm" style="text-align: center" id="addServicesTbl">
                                        <thead>
                                            <tr>
                                                <td><label  type="text" name="" id="">الخدمة</td>
                                                <td><label  type="text" name="" id="">السعر</td>
                                                <td><label  type="text" name="" id=""></td>
                                            </tr>
                                        </thead> 
                                        <tbody>
                                            
                                        </tbody>
                                        
                                    </table>
                                </div>
                                
                            </div>
                            <div class="row" >
                                
                                <div class="p-3 mt-3 border border-secondary col-sm-12" style="padding: 5px ; margin-bottom: 16px;">
                                    <h4 style=" text-align: start">الدفع</h4>
                                    <div class="row">
                                        <div class="col-12" style="text-align: start;">
                                            <label for="">مجموع الأسعار : </label>
                                            <span id="paymentValue">0</span>
                                            
                                        </div>
                                    </div>
                                        <div class=" col-sm-6" >
        
                                            <div class="row">
                                                <div class="col">
                                                    <select name="serviceTax" id="serviceTax"></select>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class=" col-sm-6">
                                            <table class="table table-sm" style="text-align: center" id="serviceTaxTbl">
                                                
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-2" style="text-align: start;">
                                                <label for="">إجمالي الضرائب : </label>
                                            </div>
                                            <div class="col-6">
                                                <span id="totalTaxes">0</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2" style="text-align: start;">
                                                <label for="">إجمالي الفاتورة : </label>
                                            </div>
                                            <div class="col 6" >
                                                <span id="totalPayment">0</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6" style="text-align: start;">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <label for="Discount">قيمة الخصم % </label>
                                                    </div>
                                                    <div class="col-6">
                                                        <input class="inputText" id="serviceDiscount" name="serviceDiscount" value="0" lang="en" type="number"  step="any" placeholder="  قيمة الخصم " style="
                                                        max-width: 12pc; text-align: center;"> 
                                                    </div>
                                                </div>
                                                 
                                            </div>
                                            <div class="col-6" style="text-align: start;">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <label for="paymentFinal">القيمة بعد الخصم : </label>
                                                    </div>
                                                    <div class="col-6">
                                                        <span id="paymentFinalValue">0</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-6" style="text-align: start;">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <label for="Discount">المدفوع</label>
                                                    </div>
                                                    <div class="col-6">
                                                        <input class="inputText" id="totalPaid" name="totalPaid" value="0" lang="en" type="number"  step="any" placeholder="  قيمة المدفوع " style="
                                                        max-width: 12pc; text-align: center;"> 
                                                    </div>
                                                </div>
                                                 
                                            </div>
                                            <div class="col-6" style="text-align: start;">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <label for="remain"> المديونية  : </label>
                                                    </div>
                                                    <div class="col-6">
                                                        <span id="remainValue">0</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <div class="row" >
                                <div class="col-11"></div>
                                <div class="col-1">
                                    <button type="submit" class="btn btn-danger">SAVE</button>
                                    
                                </div>
                                
                            </div>
                            
                        </div>
                        
                        
                    
                    </div>
                </div>
            </div>
            

        </div>

    </div>
</form>

@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    
    <script>
       store_id= {!! $store_id !!}
    </script>
    <script src="{{ URL::asset('js/services.js')}}"></script>
@endsection
