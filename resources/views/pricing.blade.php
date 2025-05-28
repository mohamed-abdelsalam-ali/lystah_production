@extends('layouts.master')
@section('css')
    <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!--  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">-->
    <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">-->
    <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">-->
    <!--<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.css">-->
    <!--<link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.2.0/css/searchPanes.dataTables.min.css">-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.min.js"></script>

    <style>
        table {
            font-family: 'Droid Arabic Naskh', serif;
        }



        .modal-dialog {
            max-width: 1000px !important;
        }
        @media print{
            select , button , .noprint{
                display: none !important;
            }

        }
        .pricePercent {
            z-index: 99999 !important;
        }
    </style>
@endsection
@section('title')
    Pricing
@stop


@section('content')



    <div class="main-content">
        <div class="page-content">
            <h1 class="text-center text-info">. التسعــــــــيرة</h1>
            <div class="card">
                
             
                    
                    
                <form action="/pricing" class="needs-validation" enctype="application/json" novalidate method="POST">
                    @csrf
                    @method('Post')
                    <div class="card-head">
                        <div class="row m-2">
                            <div class="col-lg-4">
                                <!--<select name="currencySlct" id="currencySlct" class="form-select">-->
                                <!--     <option value="0" selected disabled>Select Currency</option>-->
                                <!--    @foreach ( $currency as $cur )-->
                                <!--        <option value="{{ $cur->id }}" data-last-price="{{ count($cur->currencies)>0 ? $cur->currencies[0]->value : 0 }}">{{ $cur->name }}</option>-->
                                <!--    @endforeach-->
                                <!--</select>-->
                            </div>

                            <div class="col-lg-4 text-center">
                                <h3 type="" id="" class="text-center text-info fs-4">  عــدد القطـــــــع = <span class='text-danger'> {{count($allparts)}} </span></h3>



                            </div>
                            <div class="col-lg-4 text-end">
                                <button class="btn text-bg-dark" type="button" onclick="window.print()">Print</button>
                                <button class="btn text-bg-success" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Currency</button>
                                <button class="btn text-bg-primary" type="button" onclick="location.reload()">Reset</button>

                            </div>

                        </div>
                           <div class="row m-2 d-print-none d-none">
                                <div class="col-lg-3 ">
                                    <!--<button type="button" id="convertEGYBtn" class="btn  text-bg-info  w-100">تحويل للجنيه المصري</button>-->
                                </div>

                                <div class="col-lg-3 ">
                                    <a href="{{URL::route('pricingwith_flag' ,array('id' => 1))}}"   class="btn text-bg-primary  w-100">  القطع التى تم تسعيرها   </a>
                                </div>

                                <div class="col-lg-3  ">
                                    <a href="{{URL::route('pricingwith_flag', array('id' => 0))}}" id="" class="btn text-bg-danger  w-100">   القطع التى لم تسعر   </a>

                                </div>
                                <div class="col-lg-3 ">
                                    <a href="{{URL::route('pricingwith_flag', array('id' => 2))}}"  id="" class="btn text-bg-success  w-100">   إظهار الكل   </a>
                                </div>

                            </div>
                    </div>
                    <div class="card-body ">
                         <div class="row ">
                             <div class="col table-responsive">
                                <table class=" display table table-bordered dt-responsive dataTable dtr-inline" id="pricingTbl">
                                            <thead class="sticky-top text-bg-light" style="z-index: 0 !important;">
                                                <tr>
                                                    <th> Name</th>
                                                    <th> Units</th>

                                                     <th> Numbers</th>
                                                    @for ($i = 0 ; $i < count($pricingTypes) ; $i++)
                                                        <th>
                                                            <label class='d-print-none'>Double Clikck to change</label>
                                                            <div class="input-group ">

                                                                <span class="input-group-text text-bg-primary pricePercentBtn" id="basic-addon1">{{ $pricingTypes[$i]->type }}</span>
                                                                <input type="text" class="form-control pricePercent d-print-none" id="price-{{ $pricingTypes[$i]->id }}" placeholder="%" aria-label="Username" aria-describedby="basic-addon1">
                                                              </div>
                                                        </th>
                                                    @endfor
                                                    <th> Source</th>
                                                    <th> Status</th>
                                                    <th> Quality</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 @foreach ($allparts as $parts )

                                                <tr data-part-id="{{ $parts->part_id }}" data-source-id="{{ $parts->source_id }}" data-status-id="{{ $parts->status_id }}" data-quality-id="{{ $parts->quality_id }}">
                                                    <td>

                                                        @if(isset($parts->part))
                                                            {{ $parts->part->name .'/'. $parts->source->name_arabic .'/'. $parts->status->name .'/'. $parts->part_quality->name  }}
                                                            {{ $parts->part->name }}
                                                            <input type="hidden" name="partId[]" value="{{  $parts->part->id }}">
                                                            <input type="hidden" name="type[]" value="1">
                                                            <input type="hidden" name="source[]" value="{{  $parts->source->id }}">
                                                            <input type="hidden" name="status[]" value="{{  $parts->status->id }}">
                                                            <input type="hidden" name="quality[]" value="{{  $parts->part_quality->id }}">
                                                            <input type="hidden" name="smallUnit[]" value="{{  $parts->part->small_unit }}">

                                                            <span class="d-block p-1 rounded text-bg-info noprint">أخر سعر شراء :
                                                                <span class="pricingSpan" data-currency-type="{{ $parts->lastpricing->order_supplier->currency_type->id }}"> {{ $parts->lastpricing->buy_price }} </span><span> {{  ($parts->lastpricing->order_supplier->currency_type) ? ($parts->lastpricing->order_supplier->currency_type->name) : "-"   }} </span>
                                                                <span class="EGYPrice p-1 rounded-4 text-bg-danger float-end"></span>

                                                                <span class="d-block "> بتاريخ {{ date('d-m-Y', strtotime($parts->lastpricing->insertion_date)); }}</span>
                                                            </span>
                                                             <span class="d-block p-1 rounded text-bg-info noprint"> تكلفة سعر شراء القطعه  :
                                                                <span class="coastingSpan" data-currency-type="{{ $parts->lastpricing->order_supplier->currency_type->id }}"> {{ round(round($parts->lastpricing->buy_costing)) }} </span><span class="d-none"> {{  ($parts->lastpricing->order_supplier->currency_type) ? ($parts->lastpricing->order_supplier->currency_type->name) : "-"   }} </span>
                                                                <span class="EGYcoast p-1 rounded-4 text-bg-danger float-end"></span>

                                                            </span>
                                                             <span class="d-block p-1 rounded text-bg-info noprint py-4"> تكلفة القطعة بالمصروفات  :
                                                                <span class="float-end fs-19 p-1 rounded-4 text-bg-dark totEGYcoast"></span>

                                                            </span>
                                                            </td>
                                                        <td>
                                                            <select name="selectedUnits[]" class="form-control-sm" id="">
                                                                <option value="">Chooses Unit</option>
                                                                @foreach ($parts->part->getsmallunit as $unitt )
                                                                        <option value="{{ $unitt->unit->id }}">{{ $unitt->unit->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        @elseif (isset($parts->kit))
                                                            {{ $parts->kit->name .'/'. $parts->source->name_arabic .'/'. $parts->status->name .'/'. $parts->part_quality->name  }}
                                                            {{ $parts->kit->name }}
                                                            <input type="hidden" name="partId[]" value="{{  $parts->kit->id }}">
                                                            <input type="hidden" name="type[]" value="6">
                                                            <input type="hidden" name="source[]" value="{{  $parts->source->id }}">
                                                            <input type="hidden" name="status[]" value="{{  $parts->status->id }}">
                                                            <input type="hidden" name="quality[]" value="{{  $parts->part_quality->id }}">
                                                            <span class="d-block p-1 rounded text-bg-info noprint">أخر سعر شراء :
                                                                <span class="pricingSpan" data-currency-type="{{ $parts->lastpricing->order_supplier->currency_type->id }}"> {{ $parts->lastpricing->buy_price }}  </span><span>{{  ($parts->lastpricing->order_supplier->currency_type) ? ($parts->lastpricing->order_supplier->currency_type->name) : "-"   }} </span>
                                                                <span class="EGYPrice p-1 rounded-4 text-bg-danger float-end"></span>

                                                                <span class="d-block "> بتاريخ {{ date('d-m-Y', strtotime($parts->lastpricing->insertion_date)); }}</span>
                                                            </span>
                                                             <span class="d-block p-1 rounded text-bg-info noprint"> تكلفة سعر شراء القطعه  :
                                                                <span class="coastingSpan" data-currency-type="{{ $parts->lastpricing->order_supplier->currency_type->id }}"> {{ round($parts->lastpricing->buy_costing) }} </span><span class="d-none"> {{  ($parts->lastpricing->order_supplier->currency_type) ? ($parts->lastpricing->order_supplier->currency_type->name) : "-"   }} </span>
                                                                <span class="EGYcoast p-1 rounded-4 text-bg-danger float-end"></span>

                                                            </span>
                                                             <span class="d-block p-1 rounded text-bg-info noprint py-4"> تكلفة القطعة بالمصروفات  :
                                                                <span class="float-end fs-19 p-1 rounded-4 text-bg-dark totEGYcoast"></span>

                                                            </span>
                                                        @elseif (isset($parts->wheel))
                                                            {{ $parts->wheel->name .'/'. $parts->source->name_arabic .'/'. $parts->status->name .'/'. $parts->part_quality->name  }}
                                                            {{ $parts->wheel->name }}
                                                            <input type="hidden" name="partId[]" value="{{  $parts->wheel->id }}">
                                                            <input type="hidden" name="type[]" value="2">
                                                            <input type="hidden" name="source[]" value="{{  $parts->source->id }}">
                                                            <input type="hidden" name="status[]" value="{{  $parts->status->id }}">
                                                            <input type="hidden" name="quality[]" value="{{  $parts->part_quality->id }}">
                                                            <span class="d-block p-1 rounded text-bg-info noprint">أخر سعر شراء :
                                                                <span class="pricingSpan" data-currency-type="{{ $parts->lastpricing->order_supplier->currency_type->id }}"> {{ $parts->lastpricing->buy_price }} </span><span> {{  ($parts->lastpricing->order_supplier->currency_type) ? ($parts->lastpricing->order_supplier->currency_type->name) : "-"   }} </span>
                                                                <span class="EGYPrice p-1 rounded-4 text-bg-danger float-end"></span>

                                                                <span class="d-block "> بتاريخ {{ date('d-m-Y', strtotime($parts->lastpricing->insertion_date)); }}</span>
                                                            </span>
                                                             <span class="d-block p-1 rounded text-bg-info noprint"> تكلفة سعر شراء القطعه  :
                                                                <span class="coastingSpan" data-currency-type="{{ $parts->lastpricing->order_supplier->currency_type->id }}"> {{ round($parts->lastpricing->buy_costing) }} </span><span class="d-none"> {{  ($parts->lastpricing->order_supplier->currency_type) ? ($parts->lastpricing->order_supplier->currency_type->name) : "-"   }} </span>
                                                                <span class="EGYcoast p-1 rounded-4 text-bg-danger float-end"></span>

                                                            </span>
                                                             <span class="d-block p-1 rounded text-bg-info noprint py-4"> تكلفة القطعة بالمصروفات  :
                                                                <span class="float-end fs-19 p-1 rounded-4 text-bg-dark totEGYcoast"></span>

                                                            </span>
                                                        @elseif (isset($parts->tractor))

                                                        {{ $parts->tractor->name }}
                                                        <input type="hidden" name="partId[]" value="{{  $parts->tractor->id }}">
                                                        <input type="hidden" name="type[]" value="3">
                                                        <input type="hidden" name="source[]" value="{{  $parts->source->id }}">
                                                        <input type="hidden" name="status[]" value="{{  $parts->status->id }}">
                                                        <input type="hidden" name="quality[]" value="{{  $parts->part_quality->id }}">
                                                        <span class="d-block p-1 rounded text-bg-info noprint">أخر سعر شراء :
                                                            <span class="pricingSpan" data-currency-type="{{ $parts->lastpricing->order_supplier->currency_type->id }}"> {{ $parts->lastpricing->buy_price }} </span><span> {{  ($parts->lastpricing->order_supplier->currency_type) ? ($parts->lastpricing->order_supplier->currency_type->name) : "-"   }} </span>
                                                            <span class="EGYPrice p-1 rounded-4 text-bg-danger float-end"></span>

                                                            <span class="d-block "> بتاريخ {{ date('d-m-Y', strtotime($parts->lastpricing->insertion_date)); }}</span>
                                                        </span>
                                                         <span class="d-block p-1 rounded text-bg-info noprint"> تكلفة سعر شراء القطعه  :
                                                                <span class="coastingSpan" data-currency-type="{{ $parts->lastpricing->order_supplier->currency_type->id }}"> {{ round($parts->lastpricing->buy_costing) }} </span><span class="d-none"> {{  ($parts->lastpricing->order_supplier->currency_type) ? ($parts->lastpricing->order_supplier->currency_type->name) : "-"   }} </span>
                                                                <span class="EGYcoast p-1 rounded-4 text-bg-danger float-end"></span>

                                                        </span>
                                                        
                                                    @elseif (isset($parts->clark))

                                                        {{ $parts->clark->name }}
                                                        <input type="hidden" name="partId[]" value="{{  $parts->clark->id }}">
                                                        <input type="hidden" name="type[]" value="4">
                                                        <input type="hidden" name="source[]" value="{{  $parts->source->id }}">
                                                        <input type="hidden" name="status[]" value="{{  $parts->status->id }}">
                                                        <input type="hidden" name="quality[]" value="{{  $parts->part_quality->id }}">
                                                        <span class="d-block p-1 rounded text-bg-info noprint">أخر سعر شراء :
                                                            <span class="pricingSpan" data-currency-type="{{ $parts->lastpricing->order_supplier->currency_type->id }}"> {{ $parts->lastpricing->buy_price }} </span><span> {{  ($parts->lastpricing->order_supplier->currency_type) ? ($parts->lastpricing->order_supplier->currency_type->name) : "-"   }} </span>
                                                            <span class="EGYPrice p-1 rounded-4 text-bg-danger float-end"></span>

                                                            <span class="d-block "> بتاريخ {{ date('d-m-Y', strtotime($parts->lastpricing->insertion_date)); }}</span>
                                                        </span>
                                                         <span class="d-block p-1 rounded text-bg-info noprint"> تكلفة سعر شراء القطعه  :
                                                                <span class="coastingSpan" data-currency-type="{{ $parts->lastpricing->order_supplier->currency_type->id }}"> {{ round($parts->lastpricing->buy_costing) }} </span><span class="d-none"> {{  ($parts->lastpricing->order_supplier->currency_type) ? ($parts->lastpricing->order_supplier->currency_type->name) : "-"   }} </span>
                                                                <span class="EGYcoast p-1 rounded-4 text-bg-danger float-end"></span>

                                                            </span>
                                                    @elseif (isset($parts->equip))

                                                        {{ $parts->equip->name }}
                                                        <input type="hidden" name="partId[]" value="{{  $parts->equip->id }}">
                                                        <input type="hidden" name="type[]" value="5">
                                                        <input type="hidden" name="source[]" value="{{  $parts->source->id }}">
                                                        <input type="hidden" name="status[]" value="{{  $parts->status->id }}">
                                                        <input type="hidden" name="quality[]" value="{{  $parts->part_quality->id }}">
                                                        <span class="d-block p-1 rounded text-bg-info noprint">أخر سعر شراء :
                                                            <span class="pricingSpan" data-currency-type="{{ $parts->lastpricing->order_supplier->currency_type->id }}"> {{ $parts->lastpricing->buy_price }} </span><span> {{  ($parts->lastpricing->order_supplier->currency_type) ? ($parts->lastpricing->order_supplier->currency_type->name) : "-"   }} </span>
                                                            <span class="EGYPrice p-1 rounded-4 text-bg-danger float-end"></span>

                                                            <span class="d-block "> بتاريخ {{ date('d-m-Y', strtotime($parts->lastpricing->insertion_date)); }}</span>
                                                        </span>
                                                         <span class="d-block p-1 rounded text-bg-info noprint"> تكلفة سعر شراء القطعه  :
                                                                <span class="coastingSpan" data-currency-type="{{ $parts->lastpricing->order_supplier->currency_type->id }}"> {{ round($parts->lastpricing->buy_costing) }} </span><span class="d-none"> {{  ($parts->lastpricing->order_supplier->currency_type) ? ($parts->lastpricing->order_supplier->currency_type->name) : "-"   }} </span>
                                                                <span class="EGYcoast p-1 rounded-4 text-bg-danger float-end"></span>

                                                            </span>
                                                        @else
                                                         <input type="hidden" name="type[]" value="0">
                                                        @endif

                                                    </td>
                                                     <td>
                                                        <ul>
                                                           @if (isset($parts->part->part_numbers))
                                                                @foreach ( $parts->part->part_numbers as $number)
                                                                    <li>{{ $number->number }}</li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                    </td>
                                                    @for ($i = 0 ; $i < count($pricingTypes) ; $i++)
                                                        <td>

                                                                <input class="form-control text-bg-danger priceinp" name="price-{{ $pricingTypes[$i]->id  }}[]" data-sale_type="{{ $pricingTypes[$i]->id }}" type="text" name="" id="" value="" >
                                                                <div class="valid-feedback">
                                                                   good!
                                                                </div>
                                                                <span class="lastpricespan">0</span>

                                                        </td>

                                                    @endfor
                                                    @if(isset($parts->part))
                                                         <td>
                                                             {{ $parts->source->name_arabic }}
                                                         </td>
                                                         <td>
                                                             {{$parts->status->name }}
                                                         </td>
                                                         <td>
                                                             {{ $parts->part_quality->name }}
                                                         </td>
                                                    @elseif (isset($parts->kit))
                                                        <td>
                                                             {{ $parts->source->name_arabic }}
                                                         </td>
                                                         <td>
                                                             {{$parts->status->name }}
                                                         </td>
                                                         <td>
                                                             {{ $parts->part_quality->name }}
                                                         </td>
                                                    @elseif (isset($parts->wheel))
                                                        <td>
                                                             {{ $parts->source->name_arabic }}
                                                         </td>
                                                         <td>
                                                             {{$parts->status->name }}
                                                         </td>
                                                         <td>
                                                             {{ $parts->part_quality->name }}
                                                         </td>
                                                  @elseif (isset($parts->tractor))
                                                        <td>
                                                            {{ $parts->source->name_arabic }}
                                                        </td>
                                                        <td>
                                                            {{ $parts->status->name }}
                                                        </td>
                                                        <td>
                                                            {{ $parts->part_quality->name }}
                                                        </td>
                                                    @elseif (isset($parts->clark))
                                                        <td>
                                                             {{ $parts->source->name_arabic }}
                                                         </td>
                                                         <td>
                                                             {{$parts->status->name }}
                                                         </td>
                                                         <td>
                                                             {{ $parts->part_quality->name }}
                                                         </td>
                                                    @elseif (isset($parts->equip))
                                                        <td>
                                                             {{ $parts->source->name_arabic }}
                                                         </td>
                                                         <td>
                                                             {{$parts->status->name }}
                                                         </td>
                                                         <td>
                                                             {{ $parts->part_quality->name }}
                                                         </td>
                                                    @else
                                                        <td></td><td></td><td></td>
                                                    @endif
                                                </tr>

                                                @endforeach
                                            </tbody>


                                        </table>
                                <button class="btn btn-success w-100 sticky-bottom">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">العمــــــلات</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form  method="GET" action="/updatecurrcy" enctype="multipart/form-data">
                        @csrf
                        @method('GET')
                        <div class="modal-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                @foreach ( $currencyHistory as $currency )
                                    <tr>
                                        <td>
                                            {{ $currency->currency_type->name }}
                                            <input type="hidden" name="curency[]" value="{{ $currency->currency_id }}">
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" name="price[]" value="{{ $currency->value }}" id="">
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Update</button>
                        </div>
                    </form>
                  </div>
                </div>
            </div>

        </div>
    </div>



@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <!--  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>-->
    <!--<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.js"></script>-->
    <!--<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>-->
    <!--<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>-->

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        var allpartsInStore = {!! $allparts !!}
        var currencyHistory = {!! $currencyHistory !!}

    </script>
    <script src="{{ URL::asset('js/pricing.js') }}"></script>
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
                     
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })();
    </script>
    <script>
        var pricDTs =$("#pricingTbl").dataTable({ paging: false });
         $("#notpriceBtn").click(function(e){
            //   pricDTs.clear();
            // e.preventDefault();
            // $("#pricingTbl").dataTable().fnDestroy();
            // $("#pricingTbl").dataTable({  columnDefs: [
            // { targets: 4, type: 'empty-on-top' }] });
        //   $("#pricingTbl").clear().rows.add('').draw()
        //     $('#pricingTbl').dataTable().fnClearTable();

        //   $_ajax


        //   url:pricingwith_flag/
        })
    </script>


@endsection
