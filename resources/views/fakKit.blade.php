
@extends('layouts.master')
@section('css')

    <style>
        .itemimg:hover {
            width: 200px;
            height: 200px;
        }

        table {
            font-family: 'Droid Arabic Naskh', serif;
        }

        #partSpecsTbl tr {
            margin-top: 5px;
        }

        .modal-dialog {
            max-width: 80% !important;
        }

        @media screen and (max-width: 767px) {
            li.paginate_button.previous {
                display: inline;
            }

            li.paginate_button.next {
                display: inline;
            }

            li.paginate_button {
                display: none;
            }
        }

        #staticBackdrop .modal-dialog {
            max-width: 80% !important;
        }

        /**************************************/
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
            color: black;
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

        .radio-button__input:checked+.radio-button__label .radio-button__custom {
            background-color: #4c8bf5;
            border-color: transparent;
            transform: scale(0.8);
            box-shadow: 0 0 20px #4c8bf580;
        }

        .radio-button__input:checked+.radio-button__label {
            color: #4c8bf5;
        }

        .radio-button__label:hover .radio-button__custom {
            transform: scale(1.2);
            border-color: #4c8bf5;
            box-shadow: 0 0 20px #4c8bf580;
        }






        /***************************************/
    </style>

    <style>

    .wrap{
        display:block !important;
    }



    </style>
@endsection
@section('title')
    فك كيت
@stop
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">Kits</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active">KitRelease</li>
                            <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row border p-3" >
                    <div class="col-lg-3">
                        <h3 class="bg-body"> بيانات الكيت   </h3>
                        <h3 class=""> الصنف  : {{$kitData[0]->kit->name}}     </h3>
                        <h3 class="">  المنشأ:{{$kitData[0]->source->name_arabic}}     </h3>
                        <h3 class="">  الحالة:{{$kitData[0]->status->name}}     </h3>
                        <h3 class="">  الجودة:{{$kitData[0]->part_quality->name}}     </h3>
                        <?php
                        $tot_amount = 0;
                        $remain_amount = 0;
                        foreach ($kitData as $key => $value) {
                            $tot_amount += $value->amount;
                            $remain_amount += $value->remain_amount;
                        }
                         ?>
                        <h3 class=""> تم تجميع :{{$tot_amount}}     </h3>
                        <h3 class="">متبقى : <span id="remain_kit"> {{$remain_amount}}  </span>   </h3>
                        @forelse($kitData[0]->sectionswithoutorder as $key => $section)
                        <h5 class="">موجود : ({{$section->amount}})  فى : ({{$section->store_structure->name}})  / {{$section->store_structure->store->name}} </h5>

                        @empty
                        <h3 class="bg-body"> لم يتم توزيعه فى الاقسام </h3>

                        @endforelse




                        <input type="hidden"name="fakAllKitId" class="form-controle readonly" value=" {{$kitData[0]->part_id}}">
                    </div>
                    <div class="col-lg-9" style="height: auto; overflow: auto; ">
                        <h2 class="bg-body">مكونات الكيت المراد فكة   </h2>

                        <table id="fakKitCAmount" class="table table-striped table-bordered cell-border " style="width:100%">
                            <thead style="background:#5fcee78a">
                                <tr>
                                    <td>عدد</td>
                                    <td>اسم القطعة </td>
                                    {{-- <td>المنشأ</td>
                                    <td>الحالة</td>
                                    <td>الجودة</td> --}}
                                    <td>موجود من الصنف</td>
                                    <td>التوزيعة</td>
                                    <td>اجمالى بعد الفك </td>


                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kitData[0]->parts_in_allkit_item as $key => $value)
                                <tr>
                                    <td>{{$value->amount}} </td>
                                    <td>{{$value->part->name}}</td>


                                    {{-- <td>{{$value->source->name_arabic}}</td> --}}
                                    {{-- <td>{{$value->status->name}}</td> --}}
                                    {{-- <td>{{$value->part_quality->name}}</td> --}}
                                    <?php
                                    $totalamount = 0;
                                    $section_amount='';
                                    $Arrtest=[];


                                    foreach($value->part->store_sections as $key => $sections){
                                        // $xsx = $sections->id.'-'.$sections->source->id.'-'.$sections->status->id.'-'.$sections->part_quality->id.'-'.$sections->store_structure->store->id ;
                                        if ($sections->amount > 0 ) {
                                            $totalamount+=$sections->amount;
                                            // $section_amount .='<table>';
                                            // $section_amount .= '<tr>';
                                            // $section_amount .= '<td >' . $sections->amount . '</td>';
                                            // $section_amount .= '<td>' . $sections->source->name_arabic . '</td>';
                                            // $section_amount .= '<td>' . $sections->status->name . '</td>';
                                            // $section_amount .= '<td>' . $sections->part_quality->name . '</td>';
                                            // $section_amount .= '<td>' . $sections->store_structure->name . '</td>';
                                            // $section_amount .= '<td>' . $sections->store_structure->store->name . '</td>';
                                            // $section_amount .= '</tr>';
                                            // $section_amount .= '</table>';

                                        // array_push( $Arrtest , $sections->id.'-'.$sections->source->id.'-'.$sections->status->id.'-'.$sections->part_quality->id.'-'.$sections->store_structure->store->id );
                                        }

                                    }


                                    ?>
                                    <td>{{$totalamount}}</td>
                                    <td></td>
                                    {{-- <td><?php echo $section_amount; ?></td> --}}

                                    <td >{{($value->amount *  $remain_amount)+$totalamount}}</td>



                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row border p-3" >

                    <div class="col-lg-12">
                        <form action="/savefakkit" method="POST">
                            @csrf
                            @method('POST')

                            @forelse($storeSections as $key => $section)
                                <div class="row">
                                    <div class="col-2">
                                        <label for="#fak_amount"> البافى فى القسم :  </label>
                                        <input class="form-control remain_fak_amount" readonly type="number " name="remain_fak_amount[]" id="" value="{{$section->total_amount}}">

                                    </div>
                                    <div class="col-6">
                                        <input type="hidden" name="kitId" value="{{ $kitData[0]->kit->id }}">
                                        <input type="hidden" name="sourceId" value="{{ $section->source->id }}">
                                        <input type="hidden" name="statusId" value="{{ $section->status->id }}">
                                        <input type="hidden" name="qualityId" value="{{ $section->part_quality->id }}">
                                        <input type="hidden" name="storeId[]" value="{{ $section->store_structure->store_id }}">
                                        <input type="hidden" name="secId[]" value="{{ $section->store_structure->id }}">
                                        {{-- <input type="hidden" name="supplier_order_id[]" value="{{ $section->order_supplier_id}}"> --}}

                                        <label for="#fak_amount">الكمية المراد فكها من {{$section->store_structure->store->name}}   فى القسم : ({{$section->store_structure->name}})</label>
                                       <input class="form-control fak_amount    " type="number" name="fak_amount[]" id="" data-amount="{{$section->total_amount}}" value="0">


                                    </div>
                                </div>
                                @empty
                                @foreach($data as $key => $store)
                                    @foreach($store->storepart as $key => $ele)
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="#fak_amount"> الباقى فى المخزن :  </label>
                                            <input class="form-control remain_fak_amount" readonly type="number " name="remain_fak_amount[]" id="" value="{{$store->storepartCount}}">

                                        </div>
                                        <div class="col-6">
                                            <input type="hidden" name="kitId" value="{{ $ele->part_id }}">
                                            <input type="hidden" name="sourceId" value="{{ $ele->source_id }}">
                                            <input type="hidden" name="statusId" value="{{ $ele->status_id }}">
                                            <input type="hidden" name="qualityId" value="{{ $ele->quality_id }}">
                                            <input type="hidden" name="storeId[]" value="{{ $store->id}}">
                                            <input type="hidden" name="supplier_order_id[]" value="{{ $ele->supplier_order_id}}">
                                            <label for="#fak_amount">الكمية المراد فكها من {{$store->name}}  </label>
                                            <input class="form-control fak_amount"  max="{{$store->storepartCount}}" required type="number" name="fak_amount[]" id="" data-amount="{{$store->storepartCount}}" value="0">
                                        </div>
                                    </div>
                                    @endforeach

                                @endforeach
                                {{-- <h3 class="bg-body"> لم يتم توزيعه فى الاقسام </h3> --}}
                            @endforelse

                            <div class="row mt-2">
                                <div class="col-lg-4"></div>
                                <div class="col-lg-4">
                                     <button class="btn btn-info form-controle"> فك الكمية المطلوبة</button>
                                </div>
                                <div class="col-lg-4"></div>
                            </div>


                        </form>
                    </div>
                    <div class="col-lg-6">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

 @section('js')


    <script>

    $(document).on('keyup','.fak_amount',function(){
        remain_kit_amount  = parseInt($('#remain_kit').text());
        remain_sec_amount=0;
                remain_sec_amount= parseInt($(this).attr('data-amount'));
                if(parseInt($(this).val()) <= remain_sec_amount  ){
                    $($(this).closest('div').siblings().find('.remain_fak_amount')).val(remain_sec_amount -( (parseInt($(this).val())) ? parseInt($(this).val()) : 0));
                }else{
                    $($(this).closest('div').siblings().find('.remain_fak_amount')).val(remain_sec_amount);
                    $(this).val(0);
                }

        })

        function openFakKitMdl(kit_idd){
            $("#fakKit").modal('toggle');
            // alert(kit_idd);
        }
    </script>

@endsection
