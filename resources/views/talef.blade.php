@extends('layouts.master')
@section('title')
    الاهلاكات
@stop
@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <span class="fs-1 mb-sm-0">ستم خذف الصنف من المخزن والقسم</span>
                        <span class="fs-1 mb-sm-0">لم يتم عمل قيود بعد</span>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">DELETE ITEM   </li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <form action="/save_talef" method="POST">
                @csrf
                @method('POST')
                <div class="card">

                    <input type="hidden" name="flag" value="0">
                    <div class="card-body fs-19 fw-bold">
                        <div class="row mb-2">
                            <div class="col-lg-2">
                                <label for="">المخزن </label>
    
                            </div>
                            <div class="col-lg-8">
                                @if($store_id == 0)
                                    <select class="form-control" name="store" id="storeSlct" required>
                                        <option selected disabled  value="">اختر</option>
                                        @foreach($stores as $key => $store)
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select> 
                                @else
                                    <select  class="form-control"  name="store" id="storeSlct">
                                        @foreach($stores as $key => $store)
                                            @if($store->id == $store_id)
                                                <option selected value="{{ $store->id }}">{{ $store->name }}</option>
                                            @else
                                                {{-- <option  value="{{ $store->id }}">{{ $store->name }}</option> --}}
                                            @endif
                                            
                                        @endforeach
                                    </select>
                                @endif
                               
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table">
                                    <tr>
                                        <input type="hidden" name="part_id" value="{{ $item->id }}">
                                        <input type="hidden" name="source_id" value="{{ $source->id }}">
                                        <input type="hidden" name="status_id" value="{{ $status->id }}">
                                        <input type="hidden" name="quality_id" value="{{ $quality->id }}">
                                        <input type="hidden" name="type_id" value="{{ $type_id }}">
                                        <input type="hidden" name="ratioamount" class="ratioamount" value="{{ getSmallUnit($item->big_unit,$item->small_unit) }}" >

                                        <td>{{ $item->name }}</td>
                                        <td>{{ $source->name_arabic }}</td>
                                        <td>{{ $status->name }}</td>
                                        <td>{{ $quality->name }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="col-lg-6">
                            <label for="">   الإجمالى</label><span class="badge badge-dark mx-3" id="storeAmount">0</span>
                            <select id="p_unit">
                               
                                <option value="">إختر الوحدة </option>
                            </select>
                            <input required name="amount"  type="text" class="form-control">
                            </div>
                            <div class="col-lg-6">
                                <label for="">المتسبب </label>
                                <select class="form-control" name="employee" id="" required>
                                    <option selected disabled  value="">اختر</option>
                                    @foreach($employee as $key => $employe)
                                        <option value="{{ $employe->id }}">{{ $employe->employee_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="">ملاحظات </label>
                                <textarea name="notes" class="form-control" id="" cols="30" rows="10" required></textarea>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-12">
                               <button class="btn btn-info w-100"> حفظ </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            



        </div>




    @endsection
    @section('js')
        <script>
            var store_datax = {!! $amount !!};
            var store_id = {!! $store_id !!};
console.log(store_datax);

            $(document).ready(function() {
                var ratio = $('.ratioamount').val();
                var xx = store_datax.find(x => x.id == store_id).storepartCount;
                var units = store_datax.find(x => x.id == store_id).units[0].getsmallunit;
                var units_drp=[];
                $("#storeAmount").text(xx/ratio);
                $(".talefamount").attr('max',xx/ratio);
                // units.forEach(element => {
                //    units_drp.append(`<option value = ${element.value}>${element.id}</option>`)
                    
                // });
            //     // $('#p_unit').html(units_drp);
            //     partunitx +=`<option selected disabled value="">Select Unit</option>`;
                    
            //             if(units.length > 0){
            //                 smallUnit = ($(this).select2('data').length > 0) ? $(this).select2('data')[0].small_unit : 0;

            //                 units.forEach(unit => {
            //                         partunitx +=`<option value="${unit.unit.id}">${unit.unit.name}</option>`;
            //                 });

            //             }else{
            //                 partunitx +=`<option value="1">وحدة</option>`;
            //             }
                  
            //     <select class="form-select partQualty text-left" name="unit[]" id="" required>
            //         $('#p_unit').html(units_drp);
            // </select>
            })
            $(".talefamount").on('keyup', function() {
                var ratio = $('.ratioamount').val();
                var xx = store_datax.find(x => x.id == store_id).storepartCount;
                var amount = $(this).val();
                if(amount > xx/ratio){
                    $(this).val(xx/ratio);
                }
            })
            $("#storeSlct").on('change', function() {
                var storeId = $(this).val();
                var xx = store_datax.find(x => x.id == storeId).storepartCount;
                $("#storeAmount").text(xx/ratio);
            })
        </script>

    @endsection
