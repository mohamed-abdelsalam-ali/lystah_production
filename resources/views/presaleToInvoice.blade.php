@extends('layouts.master')
@section('css')



@endsection
@section('title')
    فاتورةعرض أسعار
@stop


@section('content')


    <div class="main-content">
        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">presaleInvoice</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">presaleInvoice</li>
                                <li class="breadcrumb-item"><a href="/asar">presaleOrder</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="text-center"> تسليم فاتورة عرض أسعــــار
                        <a href="/printpreSale/ar/{{ $presaleOrders->id }}" class="border btn btn-outline-info float-end"><i class="mdi mdi-printer fs-22"></i></a>
                    </h5>

                </div>
            </div>
            <form  id="ConvertPresaleInvoiceFrm" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="client_id" value="{{ $presaleOrders->client->id }}">
                <input type="hidden" name="presale_id" value="{{ $presaleOrders->id }}">
                <input type="hidden" name="due_date" value="{{ $presaleOrders->due_date }}">
                <input type="hidden" name="location" value="{{ $presaleOrders->location }}">
                <input type="hidden" name="subtotal" value="{{ $presaleOrders->subtotal }}">
                <input type="hidden" name="tax" value="{{ $presaleOrders->tax }}">
                <input type="hidden" name="total" value="{{ $presaleOrders->total }}">
                {{-- <div class="row">
                    <div class="col-lg-2">الخروج من مخزن</div>
                    <div class="col-lg-8">
                            <select name="storeId" required  id="">
                                <option value="" selected disabled>Select Store</option>
                                @foreach ($stores as $store )
                                    <option value="{{ $store->id}}" >{{ $store->name }}</option>
                                @endforeach
                            </select>
                    </div>
                </div> --}}
                <div class="row">
                    <div class="col-lg-1">
                        العميل
                    </div>
                    <div class="col-lg-3">
                        <h5>{{ $presaleOrders->client->name }}</h5>
                    </div>
                    <div class="col-lg-1">
                        تاريخ العرض
                    </div>
                    <div class="col-lg-3">
                        <h5>{{ explode(" ",$presaleOrders->created_at)[0] }}</h5>
                    </div>
                    <div class="col-lg-1 text-nowrap" >
                        تاريخ الاستحقاق
                    </div>
                    <div class="col-lg-3">
                        <h5>{{ explode(" ",$presaleOrders->due_date)[0] }}</h5>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-lg-1">
                        المخزن
                    </div>
                    <div class="col-lg-3">
                       <h5> {{ $presaleOrders->store->name  }}</h5>
                       <input type="hidden" name="storeId" value="{{ $presaleOrders->store->id }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-body table-responsive ">
                            <table id="partsDT" class="table table-striped table-bordered cell-border fw-bold" style="width:100%">
                                <thead style="background:#5fcee78a">
                                    <tr>
                                        <th>م</th>
                                        <th>الصنف</th>
                                        <th>الوزن</th>
                                        <th>الحالة</th>
                                        <th>المنشأ</th>
                                        <th>الجودة</th>
                                        <th>الكمية المطلوبة</th>
                                        <th>المتاح</th>
                                        <th>السعر المتفق علية</th>
                                        <th>التسعيرة</th>
                                        <th>الإجمالي</th>
                                        <th>ملاحظة</th>
                                        <th class="d-none">موقف مخزن التجميع</th>
                                        <th>موقف  المخازن</th>
                                    </tr>
                                </thead>
                                <tbody id="partsDT_body">
                                    @foreach ($presaleOrders->presaleorderpart as $item)
                                        @if($item->price - $item->closestPrice >= 0 )
                                            <tr>
                                        @else
                                            <tr class="text-bg-danger">
                                        @endif
                                            <td>{{  $loop->iteration }}</td>
                                            <td>{{ $item->part->name }}</td>
                                            <input type="hidden" name="parts[]" value="{{ $item->part->id }}">
                                            @if($item->type == 1)
                                                @php
                                                    $weight = 0;
                                                    $p_weight1 = null;
                                                    if (count($item->part->part_details)>0){
                                                        $part_specs = $item->part->part_details;
                                                        foreach ($part_specs as $spec) {
                                                            if (preg_match('/وزن/', $spec['part_spec']['name'])) {
                                                                $p_weight1 = $spec; // Store the matching object
                                                                // var_dump($p_weight1);
                                                                $weight = number_format(floatval($p_weight1['value']), 4); // Convert the value to a floating-point number and format it to 4 decimal places
                                                                break; // Exit the loop since we found the matching object
                                                            }
                                                        }
                                                    }else {
                                                        $weight=0;
                                                    }
                                                    // var_dump($item->part_details);

                                                @endphp
                                                <td>{{  $weight }}</td>
                                                <input type="hidden" name="weight[]" value="{{  $weight }}">
                                            @endif
                                            @if($item->type == 2)
                                                @php
                                                    $weight = 0;
                                                    $p_weight1 = null;
                                                    if (count($item->part->wheel_details)>0){
                                                        $part_specs = $item->part->wheel_details;
                                                        foreach ($part_specs as $spec) {
                                                            if (preg_match('/وزن/', $spec['part_spec']['name'])) {
                                                                $p_weight1 = $spec; // Store the matching object
                                                                // var_dump($p_weight1);
                                                                $weight = number_format(floatval($p_weight1['value']), 4); // Convert the value to a floating-point number and format it to 4 decimal places
                                                                break; // Exit the loop since we found the matching object
                                                            }
                                                        }
                                                    }else {
                                                        $weight=0;
                                                    }
                                                    // var_dump($item->part_details);

                                                @endphp
                                                <td>{{  $weight }}</td>
                                                <input type="hidden" name="weight[]" value="{{  $weight }}">
                                            @endif
                                            @if($item->type == 3)
                                                @php
                                                    $weight = 0;
                                                    $p_weight1 = null;
                                                    if (count($item->part->tractor_details)>0){
                                                        $part_specs = $item->part->tractor_details;
                                                        foreach ($part_specs as $spec) {
                                                            if (preg_match('/وزن/', $spec['part_spec']['name'])) {
                                                                $p_weight1 = $spec; // Store the matching object
                                                                // var_dump($p_weight1);
                                                                $weight = number_format(floatval($p_weight1['value']), 4); // Convert the value to a floating-point number and format it to 4 decimal places
                                                                break; // Exit the loop since we found the matching object
                                                            }
                                                        }
                                                    }else {
                                                        $weight=0;
                                                    }
                                                    // var_dump($item->part_details);

                                                @endphp
                                                <td>{{  $weight }}</td>
                                                <input type="hidden" name="weight[]" value="{{  $weight }}">
                                            @endif
                                            @if($item->type == 4)
                                                @php
                                                    $weight = 0;
                                                    $p_weight1 = null;
                                                    if (count($item->part->clark_details)>0){
                                                        $part_specs = $item->part->clark_details;
                                                        foreach ($part_specs as $spec) {
                                                            if (preg_match('/وزن/', $spec['part_spec']['name'])) {
                                                                $p_weight1 = $spec; // Store the matching object
                                                                // var_dump($p_weight1);
                                                                $weight = number_format(floatval($p_weight1['value']), 4); // Convert the value to a floating-point number and format it to 4 decimal places
                                                                break; // Exit the loop since we found the matching object
                                                            }
                                                        }
                                                    }else {
                                                        $weight=0;
                                                    }
                                                    // var_dump($item->part_details);

                                                @endphp
                                                <td>{{  $weight }}</td>
                                                <input type="hidden" name="weight[]" value="{{  $weight }}">
                                            @endif
                                            @if($item->type == 5)
                                                @php
                                                    $weight = 0;
                                                    $p_weight1 = null;
                                                    if (count($item->part->equip_details)>0){
                                                        $part_specs = $item->part->equip_details;
                                                        foreach ($part_specs as $spec) {
                                                            if (preg_match('/وزن/', $spec['part_spec']['name'])) {
                                                                $p_weight1 = $spec; // Store the matching object
                                                                // var_dump($p_weight1);
                                                                $weight = number_format(floatval($p_weight1['value']), 4); // Convert the value to a floating-point number and format it to 4 decimal places
                                                                break; // Exit the loop since we found the matching object
                                                            }
                                                        }
                                                    }else {
                                                        $weight=0;
                                                    }
                                                    // var_dump($item->part_details);

                                                @endphp
                                                <td>{{  $weight }}</td>
                                                <input type="hidden" name="weight[]" value="{{  $weight }}">
                                            @endif
                                            @if($item->type == 6)
                                                @php
                                                    $weight = 0;
                                                    $p_weight1 = null;
                                                    if (count($item->part->kit_details)>0){
                                                        $part_specs = $item->part->kit_details;
                                                        foreach ($part_specs as $spec) {
                                                            if (preg_match('/وزن/', $spec['part_spec']['name'])) {
                                                                $p_weight1 = $spec; // Store the matching object
                                                                // var_dump($p_weight1);
                                                                $weight = number_format(floatval($p_weight1['value']), 4); // Convert the value to a floating-point number and format it to 4 decimal places
                                                                break; // Exit the loop since we found the matching object
                                                            }
                                                        }
                                                    }else {
                                                        $weight=0;
                                                    }
                                                    // var_dump($item->part_details);

                                                @endphp
                                                <td>{{  $weight }}</td>
                                                <input type="hidden" name="weight[]" value="{{  $weight }}">
                                            @endif
                                            <td>{{ $item->status->name }}</td>
                                            <input type="hidden" name="status[]" value="{{ $item->status->id }}">
                                            <td>{{ $item->source->name_arabic }}</td>
                                            <input type="hidden" name="source[]" value="{{ $item->source->id }}">
                                            <td>{{ $item->quality->name }}</td>
                                            <input type="hidden" name="quality[]" value="{{ $item->quality->id }}">
                                            <td> {{ $item->amount / $item->ratiounit }}/{{ $item->unit->name}}</td>
                                            <input type="hidden" name="amount[]" value="{{ $item->amount  / $item->ratiounit }}">
                                            <input type="hidden" name="samllmeasureUnits[]" value="{{ $item->part->small_unit }}">
                                            <input type="hidden" name="measureUnit[]" value="{{ $item->unit_id }}">

                                            <td>{{ $item->total  / $item->ratiounit }} /{{ $item->unit->name}}</td>
                                            <td> {{ $item->price  * $item->ratiounit }}</td>
                                            <input type="hidden" name="price[]" value="{{ $item->price }}">
                                            <td>
                                                <button id="pricelst_btn" class="btn text-bg-info" type="button">
                                                    <i class="mdi mdi-eye "></i>
                                                </button>

                                                <div class="d-none pricelst_dv">
                                                <h3> الوحدة : {{ $item->unit->name}}</h3>

                                                    @foreach ($item->pricing as $key => $value)
                                                        <li
                                                            class="align-items-center active border btn-link cursor-pointer d-flex justify-content-between list-group-item">
                                                            {{ $value->sale_typex->type }}<span
                                                                class="badge bg-primary rounded-pill">
                                                                {{ $value->price  * $item->ratiounit }} </span></li>
                                                    @endforeach
                                                </div>
                                                @if (count($item->pricing) > 0 && isset($item->closestPricedata))
                                                    <li
                                                        class="align-items-center active border btn-link cursor-pointer d-flex justify-content-between list-group-item">
                                                        {{ $item->closestPricedata->sale_typex->type }}<span
                                                            class="badge bg-primary rounded-pill">
                                                            {{ $item->closestPrice  * $item->ratiounit }}</span></li>
                                                            <input type="hidden" name="salepricing[]" value="{{ $item->closestPricedata->sale_typex->id }}">
                                                            <input type="hidden" name="salepricingVlaue[]" value="{{ $item->closestPrice }}">
                                                @else
                                                    <li
                                                        class="align-items-center active border btn-link cursor-pointer d-flex justify-content-between list-group-item">
                                                        لا يوجد تسعيرة<span class="badge bg-primary rounded-pill">0</span></li>
                                                        <input type="hidden" name="salepricing[]" value="0">
                                                @endif


                                            </td>
                                            <td>{{ $item->amount * $item->price }}</td>
                                            <td>{{ $item->price  * $item->ratiounit - $item->closestPrice  * $item->ratiounit }}</td>
                                            <td class="d-none">

                                                @if($item->stores)
                                                    @foreach ($item->stores as $storex)
                                                        @if ($storex->id == $presaleOrders->store_id && $storex->amount >= $item->amount)
                                                            <i class="mdi mdi-check-bold" title="{{ $storex->name }}"></i>
                                                            <input type="hidden" name="availableinSt" id="availableinSt" value="1">
                                                            @break;
                                                        @else
                                                        {{-- <input type="hidden" name="availableinSt" id="availableinSt" value="0"> --}}
                                                            <i class="mdi mdi-close" title="{{ $storex->name }}"></i>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <i class="mdi mdi-close" title="لا يوجد بالمخازن"></i>
                                                @endif

                                                {{-- @if($notFound != count($item->stores))
                                                    <i class="mdi mdi-close"></i>
                                                @endif --}}

                                            </td>
                                            <td>
                                                <ul class="text-justify">
                                                    @if($item->stores )

                                                        @foreach ($item->stores as $storex)
                                                        @if($storex->amount  >= $item->amount)
                                                        <li class="">{{ $storex->name}} / {{ $storex->amount  / $item->ratiounit }} /{{ $item->unit->name}}</li>
                                                        @elseif($storex->amount  < $item->amount && $storex->amount  >0)

                                                        <li class="">{{ $storex->name}} / {{ $storex->amount  / $item->ratiounit }} /{{ $item->unit->name}} <a href="#" onclick="callStock( this ,{{ $storex->id }} , {{ $presaleOrders->store_id }} , {{ $storex->amount }})"><i class="bg-info border fs-16 mdi mdi-call-merge px-2 rounded-circle text-white"></i></a> </</li>


                                                        @else
                                                        <li class="">{{ $storex->name}} / {{ $storex->amount  / $item->ratiounit }} /{{ $item->unit->name}}</li>

                                                        @endif

                                                        @endforeach
                                                    @else
                                                        <li>غير متاح بكل المخازن</li>
                                                    @endif
                                                </ul>
                                            </td>
                                            <input type="hidden" name="notes[]" value="{{ $item->price * $item->ratiounit - $item->closestPrice * $item->ratiounit}}">
                                            <input type="hidden" name="type[]" value="{{ $item->type }}">
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                            <div class="row">
                                <div class="col-lg-4">

                                </div>
                                <div class="col-lg-4">
                                        <span>إجمالى وزن الأصناف :</span>
                                        <span id="totalweight">0</span> / كجم
                                        <input type="hidden" name="totalweight" id="totalweighttxt" value="0">
                                </div>
                                <div class="col-lg-4">

                                </div>
                            </div>
                            <hr>
                <div class="row px-5">
                    <div class="col-lg-3">
                        <span>مكان التسليم</span> <h5>( {{ isset($presaleOrders->location) ? $presaleOrders->location : '- ' }})</h5>
                    </div>
                    <div class="col-lg-3">
                        <input type="hidden" name="subtotal" value="{{ isset($presaleOrders->subtotal) ? $presaleOrders->subtotal : 0 }}">
                        <span> اجمالي الأصناف</span><h5> {{ isset($presaleOrders->subtotal) ? $presaleOrders->subtotal : 0 }}</h5>
                    </div>
                    <div class="col-lg-3">
                        <span> الضرائب</span><h5> {{ isset($presaleOrders->tax) ? $presaleOrders->tax : 0 }}</h5>
                        @foreach ($presaleOrders->presaleTaxes as  $taxx)
                            <input type="hidden" name="taxArr[]" value="{{ $taxx->tax_id }}">
                        @endforeach
                    </div>
                    <div class="col-lg-3">
                        <input type="hidden" name="total" value="{{ isset($presaleOrders->total) ? $presaleOrders->total : 0 }}">
                        <span> الإجمالي</span><h5> {{ isset($presaleOrders->total) ? $presaleOrders->total : 0 }}</h5>
                    </div>
                    <div class="col-lg-3">
                        <span> المدفوع</span>
                        <input type="number" min="0" class="form-control" name="invPaied" value="0" required id="invPaied" aria-describedby="helpId" placeholder="">
                    </div>
                    <div class="col-lg-3">
                        <span> الخصم</span>
                        <input type="number" min="0" class="form-control" name="invDiscount" value="0" required id="invDiscount" aria-describedby="helpId" placeholder="">
                    </div>
                    <div class="col-lg-3 ">
                        <span>Payment Method </span>
                        <select class="form-select mt-1" name="payment" id="paymentslect">
                            @foreach ($bank_types as $bank)
                                <option class="text-center" type-name="bank"
                                    value="{{ $bank->accountant_number }}">{{ $bank->bank_name }}
                                </option>
                            @endforeach


                            @foreach ($store_safe as $safe)
                                <option class="text-center" type-name="store"
                                    value="{{ $safe->safe_accountant_number }}">صندوق {{ $safe->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        @if(count($presaleOrders->presaleorderpart) > 0 && $presaleOrders->admin_confirm == 1)
                            <button type="button" id="submitFrmBtn" class="btn btn-primary text-center w-100"> تحويل لفاتورة </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="pricelst_mdl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="preorderShowLabel" aria-hidden="true">
        <div class="modal-dialog" style="width:20vw !important;">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> التسعيرة
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h2 class="text-center" id=""></h2>

                </div>



            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>

        var presaleOrder = {!! json_encode($presaleOrders) !!};
        $(document).on('click', '#pricelst_btn', function() {
            $('#pricelst_mdl .modal-body').html($(this).parent().find('.pricelst_dv').html());
            $('#pricelst_mdl').modal('toggle');
        })


        if($("#invPaied").val()){

        }else{
            $("#invPaied").val(0)
        }
        $("#submitFrmBtn").click(function (event) {
            event.preventDefault();
            var errorMsg=[];
            if($("#availableinSt").val() == 0  || !$("#availableinSt").val() > 0){
                errorMsg.push('برجاء مراجعة موقف الكميات المتاحة بمخزن التجميع');
            }
            presaleOrder.presaleorderpart.forEach(element => {
                if(element.amount > element.total ){
                    errorMsg.push('الكمية المطلوبة من '+element.part.name + ' غير كافية'+'\r\n ');
                }
                if(!element.closestPricedata ){
                    errorMsg.push('لا يوجد تسعيرة لصنف / '+element.part.name+'\n ');
                }
            });

            if(errorMsg.length > 0){

                Swal.fire({
                    title: "برجاء مراجعة عرض الأسعار" ,
                    text:  errorMsg.join('\r\n ') ,
                    icon: "error",
                    showCancelButton: true,
                    buttonsStyling: false,
                    // confirmButtonText: "Yes, delete!",
                    // cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {

                });
            }else{
                Swal.fire({
                    title: "  عرض الأسعار" ,
                    text:  "هل تريد تحويل عرض الأسعار الي فاتورة ؟" ,
                    icon: "success",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes!",
                    cancelButtonText: "No",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        $("#ConvertPresaleInvoiceFrm").attr("action","/ConvertPresaleInvoice").submit()
                    }
                    else if (result.dismiss === 'cancel') {

                    }
                });

            }





        });

        function callStock(el,fromStore , toStore ,Amount){
            alert("جاري العمل فيها")

            if(fromStore == toStore){

            }else{

                var part_id = $(el).closest('tr').find('input[name="parts[]"]').val();
                var status_id = $(el).closest('tr').find('input[name="status[]"]').val();
                var source_id = $(el).closest('tr').find('input[name="source[]"]').val();
                var quality_id = $(el).closest('tr').find('input[name="quality[]"]').val();


            }


            console.log(el);
            // $ins_id=StoresLog::create([
            // 'All_part_id' => $all_p_data[0]['id'],
            // 'store_action_id' => 2,
            // 'store_id' => $store_id,
            // 'amount' => $sent_amount,
            // // 'user_id' => auth()->user()->id,
            // 'status' => 2,
            // 'date'=>date('Y-m-d H:i:s'),
            // 'type_id' =>  $type_id,
            // 'notes' =>  'To :'.$other_store_data[0]['name'],

            // ])

        }
        $(document).ready(function(){
            calcWeight();
         });

        function calcWeight() {
            var total_weight = 0;
            $('#partsDT_body > tr').each(function() {

                let weight = parseFloat($(this).children().eq(3).html()) || 0;
                let amount = parseFloat($(this).children().eq(11).html()) || 1;
                total_weight += weight * amount;
            });

            $("#totalweight").text(total_weight.toFixed(4));

            $("#totalweighttxt").val(total_weight.toFixed(4));
        }
    </script>

@endsection
