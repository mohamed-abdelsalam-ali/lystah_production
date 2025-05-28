@extends('layouts.print-master')
@section('css')
    <style>
        th {
            text-align: center !important;
        }
    </style>
@endsection

@section('content')
    <table id="partsDT" class="table table-striped table-bordered cell-border " style="width:100%;direction: rtl">
        <thead class="tm_text-center" style="background:#5fcee78a">
            <tr>
                <th class="tm_text-center">اسم الصنف</th>
                <th class="tm_text-center">الوزن</th>
                @if ($showflag)
                    <th class="tm_text-center">الأرقام </th>
                @endif
                <th class="tm_text-center">المنشأ</th>
                <th class="tm_text-center">الحالة</th>
                <th class="tm_text-center">الجودة</th>
                <th class="tm_text-center">السعر</th>
                <th class="tm_text-center">الكمية</th>
                <th class="tm_text-center">القيمة</th>
                <th class="tm_text-center">المتاح</th>

            </tr>
        </thead>
        <tbody class="tm_text-center" id="partsDT_body">
            @foreach ($presaleOrders->presaleorderpart as $item)
                <tr>

                    @if ($item->type == 2)
                        <td>
                            <span>{{  explode("/", $item->part->name)[0] }}</span><br>
                            <span>{{ $item->part->wheel_dimension->dimension }}</span><br>
                            <span>{{ $item->part->wheel_model->name }}</span><br>
                            <span>{{ $item->part->wheel_material->name }}</span><br>
                            <span>{{ ($item->part->tt_tl) == 1 ? 'TT'  : 'TL'}}</span>
    
                        </td>
                    @else
                        <td>{{ $item->part->name }}</td>

                    @endif
                    @if ($item->type == 1)
                        @php
                            $weight = 0;
                            $p_weight1 = null;
                            if (count($item->part->part_details) > 0) {
                                $part_specs = $item->part->part_details;
                                foreach ($part_specs as $spec) {
                                    if (preg_match('/وزن/', $spec['part_spec']['name'])) {
                                        $p_weight1 = $spec; // Store the matching object
                                        // var_dump($p_weight1);
                                        $weight = number_format(floatval($p_weight1['value']), 4); // Convert the value to a floating-point number and format it to 4 decimal places
                                        break; // Exit the loop since we found the matching object
                                    }
                                }
                            } else {
                                $weight = 0;
                            }
                            // var_dump($item);
                        @endphp
                        <td>{{ $weight }}</td>
                        <input type="hidden" name="weight[]" value="{{ $weight }}">
                    @endif
                    @if ($item->type == 2)
                        @php
                            $weight = 0;
                            $p_weight1 = null;
                            if (count($item->part->wheel_details) > 0) {
                                $part_specs = $item->part->wheel_details;
                                foreach ($part_specs as $spec) {
                                    if (preg_match('/وزن/', $spec['wheel_spec']['name'])) {
                                        $p_weight1 = $spec; // Store the matching object
                                        // var_dump($p_weight1);
                                        $weight = number_format(floatval($p_weight1['value']), 4); // Convert the value to a floating-point number and format it to 4 decimal places
                                        break; // Exit the loop since we found the matching object
                                    }
                                }
                            } else {
                                $weight = 0;
                            }
                            // var_dump($item->part_details);
                        @endphp
                        <td>{{ $weight }}</td>
                        <input type="hidden" name="weight[]" value="{{ $weight }}">
                    @endif
                    @if ($item->type == 3)
                        @php
                            $weight = 0;
                            $p_weight1 = null;
                            if (count($item->part->tractor_details) > 0) {
                                $part_specs = $item->part->tractor_details;
                                foreach ($part_specs as $spec) {
                                    if (preg_match('/وزن/', $spec['tractor_spec']['name'])) {
                                        $p_weight1 = $spec; // Store the matching object
                                        // var_dump($p_weight1);
                                        $weight = number_format(floatval($p_weight1['value']), 4); // Convert the value to a floating-point number and format it to 4 decimal places
                                        break; // Exit the loop since we found the matching object
                                    }
                                }
                            } else {
                                $weight = 0;
                            }
                            // var_dump($item->part_details);
                        @endphp
                        <td>{{ $weight }}</td>
                        <input type="hidden" name="weight[]" value="{{ $weight }}">
                    @endif
                    @if ($item->type == 4)
                        @php
                            $weight = 0;
                            $p_weight1 = null;
                            if (count($item->part->clark_details) > 0) {
                                $part_specs = $item->part->clark_details;
                                foreach ($part_specs as $spec) {
                                    if (preg_match('/وزن/', $spec['clark_spec']['name'])) {
                                        $p_weight1 = $spec; // Store the matching object
                                        // var_dump($p_weight1);
                                        $weight = number_format(floatval($p_weight1['value']), 4); // Convert the value to a floating-point number and format it to 4 decimal places
                                        break; // Exit the loop since we found the matching object
                                    }
                                }
                            } else {
                                $weight = 0;
                            }
                            // var_dump($item->part_details);
                        @endphp
                        <td>{{ $weight }}</td>
                        <input type="hidden" name="weight[]" value="{{ $weight }}">
                    @endif
                    @if ($item->type == 5)
                        @php
                            $weight = 0;
                            $p_weight1 = null;
                            if (count($item->part->equip_details) > 0) {
                                $part_specs = $item->part->equip_details;
                                foreach ($part_specs as $spec) {
                                    if (preg_match('/وزن/', $spec['equip_spec']['name'])) {
                                        $p_weight1 = $spec; // Store the matching object
                                        // var_dump($p_weight1);
                                        $weight = number_format(floatval($p_weight1['value']), 4); // Convert the value to a floating-point number and format it to 4 decimal places
                                        break; // Exit the loop since we found the matching object
                                    }
                                }
                            } else {
                                $weight = 0;
                            }
                            // var_dump($item->part_details);
                        @endphp
                        <td>{{ $weight }}</td>
                        <input type="hidden" name="weight[]" value="{{ $weight }}">
                    @endif
                    @if ($item->type == 6)
                        @php
                            $weight = 0;
                            $p_weight1 = null;
                            if (count($item->part->kit_details) > 0) {
                                $part_specs = $item->part->kit_details;
                                foreach ($part_specs as $spec) {
                                    if (preg_match('/وزن/', $spec['kit_specs']['name'])) {
                                        $p_weight1 = $spec; // Store the matching object
                                        // var_dump($p_weight1);
                                        $weight = number_format(floatval($p_weight1['value']), 4); // Convert the value to a floating-point number and format it to 4 decimal places
                                        break; // Exit the loop since we found the matching object
                                    }
                                }
                            } else {
                                $weight = 0;
                            }
                            // var_dump($item->part_details);
                        @endphp
                        <td>{{ $weight }}</td>
                        <input type="hidden" name="weight[]" value="{{ $weight }}">
                    @endif
                    @if ($showflag)
                        <td>
                            {{ count($item->oem_number) > 0 ? $item->oem_number[0]->number : 'بدون أرقام' }}
                        </td>
                    @endif

                    <td>{{ $item->source->name_arabic }}</td>
                    <td>{{ $item->status->name }}</td>
                    <td>{{ $item->quality->name }}</td>
                    <?php 
                        $ratioamount=getSmallUnit($item->unit_id,$item->part->small_unit);
                    ?>
                    <td >  {{floatVal($item->price)  * floatVal($ratioamount)}}</td>
                    <td > {{ floatVal($item->amount) / floatVal($ratioamount)}} / {{ $item->unit->name }}</td>
                    <td >{{ $item->amount * $item->price }}</td>
                    <td >{{floatVal($item->total)  / floatVal($ratioamount) }} / {{ $item->unit->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
    <br>
    <div class="tm_text_center">

        <div class="col-lg-12">
            <span>إجمالى وزن الأصناف :</span>
            <span id="totalweight">0</span> / كجم
            <input type="hidden" name="totalweight" id="totalweighttxt" value="0">
        </div>

    </div>
    <br>
    <hr>

    <table class="table table-striped table-bordered cell-border tm_text_center" style="width:100%;direction: rtl">
        <thead>
            <tr>
                <th> السعر</th>
                <th>الضريبة</th>
                <th>الإجمالي</th>

            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $presaleOrders->subtotal }}</td>
                <td>{{ $presaleOrders->tax }}</td>
                <td>{{ $presaleOrders->total }}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <hr><br>
    @if(request()->query('kethm') == '1')

    <p style="height:50px">
        <img width="145" style="float:inline-end" src="{{ asset('assets/kethm.jpeg') }}" alt="Image description">
    </p>
    @else
    
    @endif
    <p style="width:100%;direction: rtl">
        ملاحظة : هذا العرض سارى حتى تاريخ <strong> {{ explode(' ', $presaleOrders->due_date)[0] }}</strong>
    </p>
@endsection
@section('js')
    <script>
        var showflag = {!! $showflag !!};
        var presale= {!!$presaleOrders!!};
            $('#datenowLbl').text(presale.created_at);

        $(document).ready(function(){
    calcWeight();
})
function calcWeight() {
    var total_weight = 0;
    var amount = 0 ;
    $('#partsDT_body > tr').each(function() {
        let weight = parseFloat($(this).children().eq(1).html()) || 0;
        if (showflag == 1) {
             amount = parseFloat($(this).children().eq(8).html()) || 1;

        }else{
             amount = parseFloat($(this).children().eq(7).html()) || 1;

        }        total_weight += weight * amount;
    });

    $("#totalweight").text(total_weight.toFixed(4));

    $("#totalweighttxt").val(total_weight.toFixed(4));
}
    </script>
@endsection
