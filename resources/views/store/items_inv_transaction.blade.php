@extends('layouts.master')
@section('title')
     Invoice Items
@stop
@section('content')
@section('css')


@endsection


@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">Invoice Items </h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active"> Invoice</li>
                            <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <div class="card ">
            <div class="card-body table-responsive">

                    <table class=" table-striped table-bordered cell-border " style="width:100%" id="itemInvdt">
                        <thead style="background:#5fcee78a">
                            <!--begin::Table row-->
                            <tr class="">

                                <th class=" ">رقم القطعة</th>
                                <th class=" ">أسم القطعة</th>
                                <th class=" ">الأرقام</th>
                                <th class=" ">الكمية</th>
                                <th class=" ">منتظر إستلام</th>
                                <th class=" ">الوحدة</th>

                                @if(count($items[0]->order_suppliers_with_replayorder) > 0)

                                    @foreach ($items[0]->order_suppliers_with_replayorder[0]->store_data as $store)
                                        <th class=" ">{{ $store['name'] }}</th>
                                    @endforeach
                                @endif
                                <th class=" ">Actions</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold ">
                            <form action="{{ url('/saveTransactionAll') }}" method="POST">
                                @csrf
                                @method('POST')
                                    @foreach ($items[0]->order_suppliers_with_replayorder as $item)
                                        <!--begin::Table row-->

                                        <tr calss="">
                                            <!--begin::Checkbox-->

                                            <!--end::Checkbox-->
                                            <!--begin::Order ID=-->
                                            <td class="text-center pe-0" data-kt-ecommerce-order-filter="order_id">

                                                <a href="details.html"
                                                    class="text-gray-800 text-hover-primary fw-bold">{{ $item->part_id }}</a>
                                                <input type="hidden" name="part_id[]" value="{{ $item->part_id }}">

                                            </td>
                                            <!--end::Order ID=-->
                                            <!--begin::Customer=-->
                                            <td class="text-center pe-0">
                                                <a href="#"
                                                    class="text-gray-800 text-hover-primary ">{{ $item->static_item_data[0]->name }}
                                                </a>

                                                <div>
                                                    <span>{{ $item->source->name_arabic }}</span>-<span>{{ $item->status->name }}</span>-<span>{{ $item->part_quality->name }}</span>
                                                    <input type="hidden" name="source_id[]" value="{{ $item->source_id }}">
                                                    <input type="hidden" name="ststus_id[]" value="{{ $item->status_id }}">
                                                    <input type="hidden" name="quality_id[]" value="{{ $item->quality_id }}">
                                                </div>

                                            </td>

                                            <td class="text-center pe-0">
                                                @if (isset($item->static_item_data[0]->part_numbers) )
                                                    <ul>


                                                        @foreach ($item->static_item_data[0]->part_numbers as $p_num)
                                                            <li class="fw-bold"> {{ $p_num->number }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                <li class="fw-bold"> لا يوجد ارقام</li>
                                                @endif

                                            </td>


                                            <!--begin::Total=-->
                                            <?php 
                                                $ratioamount=getSmallUnit($item->unit_id,$item->static_item_data[0]->small_unit);
                                            ?>
                                            <td class="text-center pe-0 ">
                                                <input type="hidden" name="item_amount" class ="item_amount" value="{{$item->amount}}">
                                                <input type="hidden" name="deliver_amount" class ="deliver_amount" value="{{ $item->deliver_amount}}">
                                                <input type="hidden" name="pending_amount"class ="pending_amount"  value="{{$item->pending_amount}}">
                                                <input type="hidden" name="smallunitid"class ="smallunitid"  value="{{$item->static_item_data[0]->small_unit}}">
                                                <input type="hidden" name="buyunit_id"class ="buyunit_id"  value="{{$item->unit_id}}">
                                                <input type="hidden" name="bigunitid"class ="bigunitid"  value="{{$item->static_item_data[0]->big_unit}}">
                                                <span name="remain_amount" class="fw-bold  remain_itm_amount"
                                                    remain-amount="{{( $item->amount - $item->deliver_amount - $item->pending_amount) / $ratioamount}}">{{ (floatVal($item->amount) - floatVal($item->deliver_amount) - floatVal($item->pending_amount)) /  $ratioamount }} /{{$item->unit->name}}</span>
                                            </td>
                                            <td class="text-center pe-0 ">
                                                <span name="pending_amount" class="fw-bold  "
                                                    pending-amount="{{ $item->pending_amount}}">{{ $item->pending_amount /  $ratioamount }} /{{$item->unit->name}}</span>
                                            </td>
                                                <!--end::Total=-->
                                                <td>
                                                    <?php 
                                                        
                                                        ?> 
                                                    <select name="unit[]" id="" class="form-control mesureClass unit">

                                                        @forelse ($item->static_item_data[0]->getsmallunit as  $unit )
                                                        <option value="{{$unit->unit_id}}" data-val={{$unit->value}}>{{$unit->unit->name}}</option>
                                                        @empty
                                                        <option value=""></option>
                                                        @endforelse 
                                                      
                                                      
                                                      
                                                    </select>
                                                </td>

                                            @foreach ($item->store_data as $store)
                                                <td class="text-center pe-0 bind_this">

                                                    <input name="store{{  $store['id'] }}[]" class="form-control-sm inserted_ammount" type="number"
                                                        store-id="{{ $store['id'] }}" step=".0001" value=""
                                                        placeholder="{{ $store['old_amount'] /  $ratioamount }} /{{$item->unit->name}}"
                                                        class="inserted_ammount text-center">
                                                        <input type="hidden" class="old_amount" name="old_amount[]"
                                                        value="{{$store['old_amount'] }}">
                                                    <input type="hidden" class="store_idd" name="store_id[]"
                                                        value="{{ $store['id'] }}">
                                                        <input type="hidden" class="ratioo" name="ratio[]"
                                                        value="{{ $ratioamount  }}">
                                                        <input type="hidden" class="bigunitt" name="bigunit[]"
                                                        value="{{$item->bigunit  }}">
                                                    <input type="hidden" class="store_table_name" name="store_table_name[]"
                                                        value="{{ $store['table_name'] }}">
                                                </td>
                                            @endforeach
                                            <!--begin::Action=-->
                                            <td class="text-center">

                                                    <div class=""
                                                        onclick="save_amount(this,{{ $item->order_supplier_id }},{{ $item->allpart_data[0]->id }},{{ $item->part_type_id }},{{ $item->part_id }},{{ $item->source_id }},{{ $item->status_id }},{{ $item->quality_id }},{{ $items[0]->id }},{{   $ratioamount  }},{{ $item->unit_id }})">
                                                        <a class="menu-link px-3 btn btn-info save_send_amount">حفظ</a>
                                                    </div>



                                            </td>
                                            <!--end::Action=-->
                                        </tr>

                                        <!--end::Table row-->
                                    @endforeach

                            </form>
                        </tbody>
                        <!--end::Table body-->

                    </table>
                </div>


            </div>
        </div>
    </div>
@endsection
@section('js')
<script>

const itemes = {!! $items !!};
console.log(itemes);
</script>
<script src="{{ URL::asset('js/store/listing_items_inv.js') }}"></script>

<script>
    $(document).ready(function () {
        // ratioamount2= @json(getSmallUnit(3,4));
        // alert(ratioamount2);
       
        $('.bind_this').on('keyup', function () {
            confirmselected_unit(this);
        });

        $(document).on('change', '.mesureClass', function () {
            confirmselected_unit(this);
        });
   
    });
    function confirmselected_unit(el) {
    var $row = $(el).closest('tr');

    var item_amount = parseFloat($row.find('.item_amount').val()) || 0;
    var deliver_amount = parseFloat($row.find('.deliver_amount').val()) || 0;
    var pending_amount = parseFloat($row.find('.pending_amount').val()) || 0;

    get_ratio(el, function(ratio) {
        console.log("Ratio is:", ratio);
        var ratioValue = ratio; // Use this locally, don't rely on global

        var remain_amount = (item_amount - deliver_amount - pending_amount);
        var bigunit = $row.find('.mesureClass option:selected').text();
        var inserted_amount = calcamount($row, ratioValue);

        if (inserted_amount <= remain_amount) {
            $row.find('.remain_itm_amount').text(
                parseFloat((remain_amount / ratioValue - inserted_amount / ratioValue)) + '/' + bigunit
            );
            $row.find('.pending_amount').text(
                parseFloat((pending_amount / ratioValue)) + '/' + bigunit
            );
        } else {
            $(el).val("");
            check_wrong_amount(el,ratioValue);
        }
    });
}

function calcamount($row, ratioValue) {
    var sum = 0;

    $row.find('.inserted_ammount').each(function () {
        var val = parseFloat($(this).val()) || 0;
        sum += val * ratioValue;
    });

    return sum;
}

    function check_wrong_amount(el2,ratioValue) {
        var $row = $(el2).closest('tr');
        var remain_amount = parseFloat($row.find('.remain_itm_amount').attr('remain-amount')) || 0;
        var inserted_amount = calcamount($row);
        var ratio = ratioValue;

        if (inserted_amount <= remain_amount) {
            $row.find('.remain_itm_amount').text((remain_amount / ratio - inserted_amount / ratio));
        } else {
            // $(el2).val("");
            $row.find('.inserted_ammount').val('');
            Swal.fire({
                icon: 'error',
                title: 'error',
                text: 'Wrong amount!',
                });
        }
    }

    // var x=new KTAppEcommerceSalesListing;
    function save_amount(el, o_s_id, all_p_id, p_type_id, p_id, sorce_id, status_id, q_id, inv_id ,ratio,unit_id) {
        // alert(p_id);
        var data_arr = [{
            'bisc_data': ""
        }, {
            'store_data': ""
        }];
        var data_arr1 = [];
        var data_arr2 = [];
        data_arr1.push({
            'inv_id': inv_id,
            'o_s_id': o_s_id,
            'all_p_id': all_p_id,
            'p_type_id': p_type_id,
            'part_id': p_id,
            'sorce_id': sorce_id,
            'status_id': status_id,
            'quality_id': q_id,
            'unit_id': unit_id
        });

        for (let i = 0; i < $(el.closest('tr')).find('.inserted_ammount').length; i++) {

            var ins_amount = parseFloat($($(el.closest('tr')).find('.inserted_ammount')[i]).val() );
            var store_id = parseFloat($($(el.closest('tr')).find('.store_idd')[i]).val());
            var store_table_name = $($(el.closest('tr')).find('.store_table_name')[i]).val();
            var unit = $($(el.closest('tr')).find('.unit')[i]).val();
            // ratioamount2= @json(getSmallUnit(2,4));
            // alert(ratioamount2);
            if (ins_amount * ratio) {
                data_arr2.push({
                    'store_id': store_id,
                    'ins_amount': ins_amount *ratio,
                    'store_table_name': store_table_name,
                    'unit': unit
                });
            } else {
                // ins_amount=0
            }
        }
        // data_arr['bisc_data']=data_arr1;
        data_arr[0].bisc_data = data_arr1;
        // data_arr['store_data']=data_arr2;
        data_arr[1].store_data = data_arr2;
        // data_arr.push(data_arr);
        // console.log(data_arr);


        if (data_arr2.length > 0) {


            Swal.fire({
                text: "هل تريد الحفظ ؟",
                icon: "info",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, حفظ!",
                cancelButtonText: "No, الغاء",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
                }).then(function(result) {
                if (result.value) {
                    save_amount_extend(data_arr);
                    Swal.fire({
                        text: "تمت العملية بنجاح " + inv_id,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    }).then(function() {
                        // Remove current row
                        // save_amount_extend(data_arr);
                        // datatable.row($(parent)).remove().draw();
                    });
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: " لم يتم الحفظ " + inv_id,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    });
                }
            });

        } else {
            Swal.fire({
                text: " أدخل الكميات أولا",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                }
            });
        }



    }

    function save_amount_extend(data_arr) {
        // console.log(data_arr);
        // var xx = JSON.stringify(data_arr);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('saveTransaction') }}",
            async: false,
            type: 'POST',
            data: {
                'data': data_arr
            },

            success: function(response) {
                // $(form).trigger("reset");
                // alert(response.success)
                // console.log(response);
                $('.inbox_trans_counter').html(response.length);
                // $('.simplebar-content').empty();
                draw_notification(response,'.kt_topbar_notifications_33')
                location.reload();

            },
            error: function(response) {}
        });
    }
    function get_ratio(el, callback) {
    var selectedunitid = $(el).closest('tr').find('.mesureClass option:selected').val();
    var small_unit = $(el).closest('tr').find('.smallunitid').val();
    $.ajax({
        url: '/get-small-unit',
        type: 'GET',
        data: {
            unit_id: selectedunitid,
            small_unit_id: small_unit
        },
        success: function(response) {
            callback(response.ratio); // callback بـ ratio
            // alert(response.ratio);
            
        },
        error: function() {
            alert("Failed to load small unit ratio.");
        }
    });
}
</script>

<script>

</script>
@endsection
