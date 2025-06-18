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
                                       <?php
                                       $ratio = 1;
                                       $store_unit_id= 0;
                                       if(isset($unit_store->storepart[0]->unit_id)){
                                        $store_unit_id=$unit_store->storepart[0]->unit_id;
                                        $ratio=getSmallUnit($unit_store->storepart[0]->unit_id,$item->small_unit) ;
                                       }
                                       ?>
                                        <input type="hidden" name="ratioamount" class="ratioamount" value="{{  $ratio }}" >

                                        <td>{{ $item->name }}</td>
                                        <td>{{ $source->name_arabic }}</td>
                                        <td>{{ $status->name }}</td>
                                        <td>{{ $quality->name }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="">   إجمالى المتاح</label></br>
                                <span class="badge badge-dark mx-3" id="storeAmount">0</span>{{$unit_store->storepart[0]->name  }}
                            </div>
                            <div class="col-lg-3">
                                <label for="">الوحدة </label>                              
                               
                                <select id="p_unit" name="p_unit" class="form-control mesur_unit">
                              
                                <option value="">إختر الوحدة </option>
                                @forelse ($unit_store->units[0]->getsmallunit as $unit )

                                    @if (intval($store_unit_id) == intval($unit->unit_id) )
                                   
                                    <option selected value="{{ $unit->unit_id }}" data-val='{{ $unit->value }}'>{{ $unit->unit->name }}</option>

                                    @else
                                    <option  value="{{ $unit->unit_id }}" data-val='{{ $unit->value }}'>{{ $unit->unit->name }}</option>

                                    @endif

                                @empty
                                <option value="">لايوجد وحدات</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="">الكمية التالفة </label>

                            <input required name="amount"  type="number" step='0.01' class="form-control talefamount">
                        </div>
                            <div class="col-lg-3">
                                <label for="">المتسبب فى التلف </label>      
                                <a href="/employee" class="btn btn-primary w-50"> + </a>

                                
                                <select class="form-control" name="employee" id="" required>
                                    <option selected disabled  value="">اختر</option>
                                    @forelse($employee as $key => $employe)
                                        <option value="{{ $employe->id }}">{{ $employe->employee_name }}</option>
                                    @empty
                                    <option value="0">لايوجد موظفين</option>
                                    @endforelse
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
// console.log(store_datax);

            $(document).ready(function() {
                // $(".mesur_unit").select2();
                $(".mesur_unit").on('change', function() {

                var ratio = $(this).find('option:selected').attr("data-val");
                $('.ratioamount').val(ratio);
                var xx = store_datax.find(x => x.id == store_id).storepartCount;
                var amount =$(this).parents().find("input[name='amount']").val();
                $(this).parents().find("input[name='amount']").val(xx/ratio);
                if(amount > xx/ratio){
                    swal.fire({
                        title: 'خطأ',
                        text: 'الكمية المدخلة أكبر من الكمية المتاحة',
                        icon: 'error',
                        confirmButtonText: 'حسنا'
                    });
                    $(this).parents().find("input[name='amount']").val(xx/ratio);
                }
            })
                var ratio = $('.ratioamount').val();
                var xx = store_datax.find(x => x.id == store_id).storepartCount;
                var units = store_datax.find(x => x.id == store_id).units[0].getsmallunit;
                var units_drp=[];
                $("#storeAmount").text(xx/ratio);
                $(".talefamount").attr('max',xx/ratio);      
        
            })
            $(".talefamount").on('keyup', function() {
                var ratio = $('.ratioamount').val();
                var xx = store_datax.find(x => x.id == store_id).storepartCount;
                var amount = $(this).val();
                if(amount > xx/ratio){
                    swal.fire({
                        title: 'خطأ',
                        text: 'الكمية المدخلة أكبر من الكمية المتاحة',
                        icon: 'error',
                        confirmButtonText: 'حسنا'
                    });
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
