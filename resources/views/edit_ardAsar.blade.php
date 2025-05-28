@extends('layouts.master')
@section('css')
    <style>
        table {
            font-family: 'Droid Arabic Naskh', serif;
        }

        .modal-dialog {
            max-width: 1000px !important;
        }
    </style>
@endsection
@section('title')
    تعديل عرض أسعار
@stop


@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-primary " style="z-index: 88888 !important;" role="alert">
            <button type="button" class="btn-close bg-black text-white" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ $message }}
        </div>
    @endif

    <div class="main-content">
        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h1 class="text-center text-info"> عــرض أســـــعار</h1>
                        <h3 class="text-center ">رقـــم ({{ $presaleOrder->id }})
                        </h3>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Buy Invoice </li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('asar.update', 'test') }}" method="post" enctype="multipart/form-data">
                                {{ method_field('patch') }}
                                {{ csrf_field() }}


                                {{-- new --}}
                                <div class="row">
                                    <div class="col-lg-4">
                                        <input type="hidden" name="presaleOrder_id" value="{{ $presaleOrder->id }}" />
                                        <select name="clientId" class="form-select required" id="" required>
                                            <option value="" disabled>Select Client</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}"
                                                    {{ $client->id == $presaleOrder->client_id ? 'selected' : '' }}>
                                                    {{ $client->name }}
                                                </option>
                                            @endforeach
                                        </select>


                                    </div>
                                    <label for="" class="col-lg-1 p-0 text-end"> Due Date </label>
                                    <div class="col-lg-3">
                                        <input type="date" name="dueDate" class="form-control"
                                            value="{{ \Illuminate\Support\Carbon::parse($presaleOrder->due_date)->toDateString() }}"
                                            id="dueDate" min="{{ date('Y-m-d') }}">
                                    </div>
                                    <label for="" class="col-lg-1 p-0 text-end">صورة العقد</label>
                                    <div class="col-lg-3 text-end">
                                        <input name="contractImg" type="file" class="form-control">
                                    </div>

                                </div>
                                <div class="row my-2 text-center">
                                    <label for="" class="col-lg-1 p-0 text-end"> مكان التسليم</label>
                                    <div class="col-lg-4">
                                        <input name="location" type="text" class="form-control"
                                            value="{{ $presaleOrder->location }}">
                                    </div>
                                    <label for="" class="col-lg-1 p-0 text-end"> مخزن التجميع </label>
                                    <div class="col-lg-4">
                                        <select name="storeId" class="form-control" required id="">
                                            <option value="" selected disabled>Select Store</option>
                                            @foreach ($stores as $store)
                                                <option value="{{ $store->id }}"
                                                    {{ $store->id == $presaleOrder->store_id ? 'selected' : '' }}>
                                                    {{ $store->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">

                                    @foreach ($taxes as $tax)
                                        @if (count($presaleTaxIds) > 0)
                                            <div class="col-lg-4">
                                                <div onclick="checksubTotal()" class="btn-group btn-group-toggle"
                                                    data-toggle="buttons">
                                                    <label class="btn bg-light text-nowrap fs-18">
                                                        <input name="taxes[]" data-value="{{ $tax->value }}"
                                                            style="position: relative" type="checkbox"
                                                            value="{{ $tax->id }}"
                                                            {{ in_array($tax->id, $presaleTaxIds) ? 'checked' : '' }}>
                                                        {{ $tax->name }} ({{ $tax->value }}%)
                                                    </label>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-lg-4">
                                                <div onclick="checksubTotal()" class="btn-group btn-group-toggle "
                                                    data-toggle="buttons">
                                                    <label class="btn bg-light text-nowrap fs-18">
                                                        <input name="taxes[]" data-value="{{ $tax->value }}"
                                                            style="position: relative" type="checkbox"
                                                            value="{{ $tax->id }}"> {{ $tax->name }} (
                                                        {{ $tax->value }} % )
                                                    </label>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach


                                </div>
                                <br>

                                <select name="" class="form-select itemCls" id=""></select>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table id="itemTbl" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <td>الصنف</td>
                                                    <td>النوع</td>
                                                    <td>الحالة</td>
                                                    <td>الجودة</td>
                                                    <td>المتاح</td>

                                                    @foreach ($stores as $store)
                                                        @if ($store->table_name === 'damaged_parts')
                                                        @else
                                                            <td>{{ $store->name }}</td>
                                                        @endif
                                                    @endforeach
                                                    <td>السعر</td>

                                                    <td>إضافة</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>

                                                    <td></td>
                                                    <td><span class="statustd">---</span></td>
                                                    <td class="storetd">---</td>
                                                    <td class="">---</td>
                                                    <td class="">---</td>
                                                    <td class="">---</td>
                                                    <td class="">---</td>
                                                    <td class="">---</td>
                                                    <td class="">---</td>
                                                    <td class="">---</td>


                                                    <td class="partstype"></td>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div>
                                    <label for="">التسعير</label>
                                    <select name="" id="typeSelect" class="form-select">
                                        <option value="" disabled selected>Select Item</option>
                                        @foreach ($PricingType as $PType)
                                            <option value="{{ $PType->id }}">{{ $PType->type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <h3>عدد الأصناف <span
                                        id="itemCounter">{{ count($presaleOrder->presaleorderpart) > 0 ? count($presaleOrder->presaleorderpart) : 0 }}</span>
                                </h3>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table id="itemsTbl" class="table table-striped">
                                            <thead>
                                                <tr class="text-center">

                                                    <td class="text-center">الصنف</td>
                                                    <td class="text-center">الوزن</td>
                                                    <td class="text-center">الحالة</td>
                                                    <td class="text-center">النوع</td>
                                                    <td class="text-center">الجودة</td>
                                                    <td class="text-center">الكمية</td>
                                                    <td class="text-center">الوحدة</td>

                                                    <td class="text-center">السعر</td>

                                                    <td class="text-center">حذف</td>
                                                    <td class="d-none">السعر</td>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @if (count($presaleOrder->presaleorderpart) > 0)
                                                    @foreach ($presaleOrder->presaleorderpart as $presaleorderpart)
                                                        <tr
                                                            data-def='{{ $presaleorderpart->part_id }}-{{ $presaleorderpart->source_id }}-{{ $presaleorderpart->status_id }}-{{ $presaleorderpart->quality_id }}'>
                                                            <input type="hidden" name="parts[]"
                                                                value="{{ $presaleorderpart->part_id }}">
                                                            <input type="hidden" name="types[]"
                                                                value="{{ $presaleorderpart->part_type_id }}">
                                                            <input type="hidden" name="source[]"
                                                                value="{{ $presaleorderpart->source_id }}">
                                                            <input type="hidden" name="status[]"
                                                                value="{{ $presaleorderpart->status_id }}">
                                                            <input type="hidden" name="quality[]"
                                                                value="{{ $presaleorderpart->quality_id }}">
                                                                <input type="hidden" name="samllmeasureUnits[]" value="{{$presaleorderpart->part->small_unit}}">
                                                                <input type="hidden" name="itemPrice[]" value="{{ $presaleorderpart->price }}">
                                                                <input type="hidden" name="bigunitid[]" value="{{$presaleorderpart->part->big_unit}}">
                                                                <input type="hidden" name="smallunitid[]" value="{{$presaleorderpart->part->small_unit}}">
                                                            @if ($presaleorderpart->part_type_id == 1)
                                                                <td class="text-center">{{ $presaleorderpart->part->name }}</td>
                                                                <td class="text-center">
                                                                    {{ count($presaleorderpart->part->part_details_weight) > 0 ? $presaleorderpart->part->part_details_weight[0]->value : 0 }}
                                                                </td>
                                                            @elseif($presaleorderpart->part_type_id == 6)
                                                                <td class="text-center">{{ $presaleorderpart->kit->name }}</td>
                                                                <td class="text-center">
                                                                    {{ count($presaleorderpart->kit->part_details_weight) > 0  ? $presaleorderpart->kit->part_details_weight[0]->value : 0 }}
                                                                </td>
                                                            @elseif($presaleorderpart->part_type_id == 2)
                                                            <td class="text-center">{{ $presaleorderpart->wheel->name }}</td>
                                                            <td class="text-center">
                                                                {{ count($presaleorderpart->wheel->part_details_weight) >0 ? $presaleorderpart->wheel->part_details_weight[0]->value : 0 }}
                                                            </td>
                                                            @elseif($presaleorderpart->part_type_id == 3)
                                                                <td class="text-center">{{ $presaleorderpart->tractor->name }}</td>
                                                                <td class="text-center">
                                                                    {{ count($presaleorderpart->tractor->part_details_weight) >0 ? $presaleorderpart->tractor->part_details_weight[0]->value : 0 }}
                                                                </td>
                                                            @elseif($presaleorderpart->part_type_id == 4)
                                                                <td class="text-center">{{ $presaleorderpart->clark->name }}</td>
                                                                <td class="text-center">
                                                                    {{ count($presaleorderpart->clark->part_details_weight) >0  ? $presaleorderpart->clark->part_details_weight[0]->value : 0 }}
                                                                </td>
                                                            @elseif($presaleorderpart->part_type_id == 5)
                                                                <td class="text-center">{{ $presaleorderpart->equip->name }}</td>
                                                                <td class="text-center">
                                                                    {{ count($presaleorderpart->equip->part_details_weight) >0 ? $presaleorderpart->equip->part_details_weight[0]->value : 0 }}
                                                                </td>
                                                            @endif

                                                            <td class="text-center">
                                                                {{ $presaleorderpart->source->name_arabic }}</td>
                                                            <td class="text-center">{{ $presaleorderpart->status->name }}
                                                            </td>
                                                            <td class="text-center">{{ $presaleorderpart->quality->name }}
                                                            </td>
                                                            @if($presaleorderpart->part_type_id == 1 || $presaleorderpart->part_type_id == 6 )
                                                            <?php $ratioamount=getSmallUnit($presaleorderpart->unit_id,$presaleorderpart->part->small_unit);?>
                                                            @else
                                                            <?php $ratioamount =1;?>
                                                            @endif
                                                            <td><input class="form-control amounts text-center"
                                                                    type="number" name="amount[]" id=""
                                                                    required 
                                                                    value="{{ $presaleorderpart->amount }}"></td>
                                                                    <td>
                                                                        <select class="form-select partunit mesureClass" name="measureUnit[]" 
                                                                            id="" required="">
                                                                            @if($presaleorderpart->part_type_id == 1  )
                                                                                @foreach ( $presaleorderpart->part->getsmallunit as $unit)
                                                                                    @if($unit->unit->id == $presaleorderpart->unit_id)
                                                                                        <option value="{{$unit->unit->id}}" data-val="{{$unit->value}}" selected>{{$unit->unit->name}}</option>
                                                                                    @else
                                                                                        <option value="{{$unit->unit->id}}" data-val="{{$unit->value}}" >{{$unit->unit->name}}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            @else
                                                                            <option value="1">وحدة</option>
                                                                            @endif
                
                                                                        </select>
                                                                        <input type="hidden" name=""
                                                                            value="{{ $presaleorderpart->unit_id }}">
                                                                    </td>
                                                                    <td><input type="number" name="price[]" step=any required
                                                                    value="{{ $presaleorderpart->price * $ratioamount }}"
                                                                    class="form-control prices text-center price"
                                                                    id=""></td>
                                                            <td onclick="removeRow(this)"><button type="button"
                                                                    class="btn btn-danger">DELETE</button></td>
                                                             <td class="d-none">
                                                                @foreach ($presaleorderpart->pricing as $pricex)
                                                                    <li class="priceT-{{ $pricex->sale_type }}">{{ $pricex->price }}</li>
                                                                @endforeach
                                                            </td>       
                                                        </tr>
                                                    @endforeach
                                                @endif




                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">

                                    <div class="col-lg-12">
                                        <span>Total Item Weight :</span>
                                        {{-- @php
                                            $totalWeight = 0;
                                            foreach ($presaleorderpart->part->part_details_weight as $item) {
                                                $totalWeight += $item->value;
                                            }
                                        @endphp --}}
                                        <span id="totalweight"></span>
                                        / KG
                                        <input type="hidden" name="totalweight" id="totalweighttxt" value="0">
                                    </div>

                                </div>
                                <hr>
                                <div class="border border-2 border-warning p-2 row">
                                    <div class="col-lg-3">
                                        SubTotal : <span id="subtotal">{{ $presaleOrder->subtotal }}</span>
                                    </div>
                                    <div class="col-lg-3">
                                        Tax : <span id="tax">{{ $presaleOrder->tax }}</span>
                                    </div>
                                    <div class="col-lg-3">
                                        Total : <span id="total">{{ $presaleOrder->total }}</span>
                                    </div>
                                    <div class="col-lg-3">
                                        {{-- Mad : <span id="clientMad">0</span> --}}
                                    </div>
                                    <input type="hidden" name="subtotal" id="subtotalv"
                                        value="{{ $presaleOrder->subtotal }}">
                                    <input type="hidden" name="tax" value="{{ $presaleOrder->tax }}"
                                        id="taxv">
                                    <input type="hidden" name="total" value="{{ $presaleOrder->total }}"
                                        id="totalv">
                                </div>

                                {{-- new --}}



                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger">Save</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>



@endsection

@section('js')

    <script>
        var parts = '';
        var wheels = '';
        var tractors = '';
        var kits = '';
        var clarks = '';
        var equips = '';
        var sourceList = '';
        var statusList = '';
        var qualityList = '';
        // parts = JSON.parse(parts);
        var partsOption = '';
        var sourceOption = '';
        var statusOption = '';
        var qualityOption = '';
        var typesOption = '';
        var stores = '';

        var table = "";
        $(document).ready(function() {
            table = $('#itemsTbl').DataTable({
                dom: '<"dt-buttons"Bf><"clear">lirtp',
                paging: false,
            });


            stores = {!! json_encode($stores) !!};

            sourceList = {!! json_encode($source) !!};
            statusList = {!! json_encode($status) !!};
            qualityList = {!! json_encode($quality) !!};

            sourceOption += `<option value="" disabled selected>Select Source</option>`;
            statusOption += `<option value="" disabled selected>Select Status</option>`;
            qualityOption += `<option value="" disabled selected>Select Quality</option>`;


            sourceList.forEach(element => {
                sourceOption += `<option  value="${element.id}">${element.name_arabic}</option>`;
            });
            statusList.forEach(element => {
                statusOption += `<option  value="${element.id}">${element.name}</option>`;
            });
            qualityList.forEach(element => {
                qualityOption += `<option  value="${element.id}">${element.name}</option>`;
            });





            $(".itemCls").select2({


                ajax: {
                    url: "/partsSearch",
                    // async: false,
                    // delay: 250,
                    data: function(params) {
                        return {
                            q: encodeURIComponent((params.term).toLowerCase()), // search term
                            page: params.page,
                            type: ''
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data,
                        };
                    },
                    cache: true
                },
                placeholder: 'Search ',
                minimumInputLength: 3,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection
            });

            function formatRepo(repo) {
                if (repo.loading) {
                    return repo.text;
                }

                var $container = $(
                    "<div class='select2-result-repository clearfix'>" +
                    //"<div class='select2-result-repository__avatar d-none'><img src='" + repo.name + "' /></div>" +
                    "<div class='select2-result-repository__meta'>" +
                    "<div class='select2-result-repository__title'></div>" +
                    "<div class='select2-result-repository__description'></div>" +
                    "<div class='select2-result-repository__statistics'>" +
                    "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> </div>" +
                    "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> </div>" +
                    "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> </div>" +
                    "</div>" +
                    "</div>" +
                    "</div>"
                );


                $container.find(".select2-result-repository__title").text(repo.name);

                return $container;
            }

            function formatRepoSelection(repo) {
                return repo.name || repo.text || repo.type_id;
            }

            function select2_search($el, term) {
                $el.select2('open');

                // Get the search box within the dropdown or the selection
                // Dropdown = single, Selection = multiple
                var $search = $el.data('select2').dropdown.$search || $el.data('select2').selection.$search;
                // This is undocumented and may change in the future

                $search.val(term);
                $search.trigger('keyup').click();
                $search.trigger('change');
            }

            $(document).on('change', '.mesureClass', function(){
                var mesureVal = parseFloat($('option:selected', this).attr('data-val')) ;
                var rowPrice = parseFloat($(this).closest('tr').find('input[name="itemPrice[]"]').val());
                $(this).closest('tr').find('.price').val(mesureVal * rowPrice);
                $(this).closest('tr').find('.amounts').trigger('keyup');
                checksubTotal();
            });
            checkweightTotal()
        });




        // $(document).on('change', '.itemCls', function(e) {
        //     e.preventDefault();
        //     var Selected = $(this).find('option:selected');
        //     var obj = JSON.parse(Selected.attr('data-obj'));
        //     var allPart_length = Selected.attr('data-allpart-length');
        //     var amount = Selected.attr('data-amount');
        //     $(this).closest('tr').find('.statustd').empty();
        //     $(this).closest('tr').find('.pricetd').empty();
        //     $(this).closest('tr').find('.storetd').empty();
        //     $(this).closest('tr').find('.partstype').empty();
        //     // var pricing = obj.pricing;
        //     var storesData = obj.stores;

        //     if (allPart_length > 0) {
        //         $(this).closest('tr').find('.statustd').text('المتاح ( ' + amount + ' )');

        //         $(this).closest('tr').find('.storetd').empty();


        //         storesData.forEach(element2 => {
        //             $(this).closest('tr').find('.storetd').append(
        //                 `<li class="list-group-item p-0">${element2.amount} / ${element2.name}</li>`)
        //         });


        //     } else {
        //         $(this).closest('tr').find('.statustd').text('غير متوفر');
        //         $(this).closest('tr').find('.storetd').empty();

        //     }
        //     if (obj.hasOwnProperty('all_parts')) {
        //         if (obj.all_parts.length > 0) {
        //             obj.all_parts.forEach(elementx => {
        //                 var maxprice = 0;
        //                 if (elementx.pricing.length > 0) {
        //                     maxprice = elementx.pricing[0].price;
        //                 }

        //                 $(this).closest('tr').find('.partstype').append(
        //                     `<li class="align-items-center border btn-link cursor-pointer d-flex justify-content-between list-group-item" onclick='addNew(${JSON.stringify(obj)} , ${JSON.stringify(elementx)})'><span><i class="mdi mdi-arrow-down-bold"></i> </span> ${elementx.status.name} - ${elementx.source.name_arabic} - ${elementx.quality.name}<span class="badge bg-primary rounded-pill">${maxprice} ج</span></li>`
        //                 )

        //             });


        //         } else {


        //             $(this).closest('tr').find('.partstype').append(
        //                 `<li class="align-items-center border btn-link cursor-pointer d-flex justify-content-between list-group-item" onclick='addNew(${JSON.stringify(obj)} , ${JSON.stringify(obj)})'> <span><i class="mdi mdi-arrow-down-bold"></i> </span> Add To List </li>`
        //             )
        //         }
        //     } else if (obj.hasOwnProperty('all_wheels')) {
        //         if (obj.all_wheels.length > 0) {
        //             obj.all_wheels.forEach(elementx => {
        //                 var maxprice = 0;
        //                 if (elementx.pricing.length > 0) {
        //                     maxprice = elementx.pricing[0].price;
        //                 }

        //                 $(this).closest('tr').find('.partstype').append(
        //                     `<li class="align-items-center border btn-link cursor-pointer d-flex justify-content-between list-group-item" onclick='addNew(${JSON.stringify(obj)} , ${JSON.stringify(elementx)})'> ${elementx.status.name} - ${elementx.source.name_arabic} - ${elementx.quality.name}<span class="badge bg-primary rounded-pill">${maxprice} ج</span></li>`
        //                 )



        //             });


        //         } else {
        //             $(this).closest('tr').find('.partstype').append(
        //                 `<li class="align-items-center border btn-link cursor-pointer d-flex justify-content-between list-group-item" onclick='addNew(${JSON.stringify(obj)} , ${JSON.stringify(obj)})'> Add To List </li>`
        //             )
        //         }
        //     } else if (obj.hasOwnProperty('all_kits')) {
        //         if (obj.all_kits.length > 0) {
        //             obj.all_kits.forEach(elementx => {
        //                 var maxprice = 0;
        //                 if (elementx.pricing.length > 0) {
        //                     maxprice = elementx.pricing[0].price;
        //                 }

        //                 $(this).closest('tr').find('.partstype').append(
        //                     `<li class="align-items-center border btn-link cursor-pointer d-flex justify-content-between list-group-item" onclick='addNew(${JSON.stringify(obj)} , ${JSON.stringify(elementx)})'> ${elementx.status.name} - ${elementx.source.name_arabic} - ${elementx.quality.name}<span class="badge bg-primary rounded-pill">${maxprice} ج</span></li>`
        //                 )



        //             });


        //         } else {
        //             $(this).closest('tr').find('.partstype').append(
        //                 `<li class="align-items-center border btn-link cursor-pointer d-flex justify-content-between list-group-item" onclick='addNew(${JSON.stringify(obj)} , ${JSON.stringify(obj)})'> Add To List </li>`
        //             )
        //         }
        //     } else if (obj.hasOwnProperty('all_tractors')) {
        //         if (obj.all_tractors.length > 0) {
        //             obj.all_tractors.forEach(elementx => {
        //                 var maxprice = 0;
        //                 if (elementx.pricing.length > 0) {
        //                     maxprice = elementx.pricing[0].price;
        //                 }

        //                 $(this).closest('tr').find('.partstype').append(
        //                     `<li class="align-items-center border btn-link cursor-pointer d-flex justify-content-between list-group-item" onclick='addNew(${JSON.stringify(obj)} , ${JSON.stringify(elementx)})'> ${elementx.status.name} - ${elementx.source.name_arabic} - ${elementx.quality.name}<span class="badge bg-primary rounded-pill">${maxprice} ج</span></li>`
        //                 )



        //             });


        //         } else {
        //             $(this).closest('tr').find('.partstype').append(
        //                 `<li class="align-items-center border btn-link cursor-pointer d-flex justify-content-between list-group-item" onclick='addNew(${JSON.stringify(obj)} , ${JSON.stringify(obj)})'> Add To List </li>`
        //             )
        //         }
        //     } else if (obj.hasOwnProperty('all_clarks')) {
        //         if (obj.all_clarks.length > 0) {
        //             obj.all_clarks.forEach(elementx => {
        //                 var maxprice = 0;
        //                 if (elementx.pricing.length > 0) {
        //                     maxprice = elementx.pricing[0].price;
        //                 }

        //                 $(this).closest('tr').find('.partstype').append(
        //                     `<li class="align-items-center border btn-link cursor-pointer d-flex justify-content-between list-group-item" onclick='addNew(${JSON.stringify(obj)} , ${JSON.stringify(elementx)})'> ${elementx.status.name} - ${elementx.source.name_arabic} - ${elementx.quality.name}<span class="badge bg-primary rounded-pill">${maxprice} ج</span></li>`
        //                 )



        //             });


        //         } else {
        //             $(this).closest('tr').find('.partstype').append(
        //                 `<li class="align-items-center border btn-link cursor-pointer d-flex justify-content-between list-group-item" onclick='addNew(${JSON.stringify(obj)} , ${JSON.stringify(obj)})'> Add To List </li>`
        //             )
        //         }
        //     } else if (obj.hasOwnProperty('all_equips')) {
        //         if (obj.all_equips.length > 0) {
        //             obj.all_equips.forEach(elementx => {
        //                 var maxprice = 0;
        //                 if (elementx.pricing.length > 0) {
        //                     maxprice = elementx.pricing[0].price;
        //                 }

        //                 $(this).closest('tr').find('.partstype').append(
        //                     `<li class="align-items-center border btn-link cursor-pointer d-flex justify-content-between list-group-item" onclick='addNew(${JSON.stringify(obj)} , ${JSON.stringify(elementx)})'> ${elementx.status.name} - ${elementx.source.name_arabic} - ${elementx.quality.name}<span class="badge bg-primary rounded-pill">${maxprice} ج</span></li>`
        //                 )



        //             });


        //         } else {
        //             $(this).closest('tr').find('.partstype').append(
        //                 `<li class="align-items-center border btn-link cursor-pointer d-flex justify-content-between list-group-item" onclick='addNew(${JSON.stringify(obj)} , ${JSON.stringify(obj)})'> Add To List </li>`
        //             )
        //         }
        //     }



        //     // console.log(obj);
        // });
        $(document).on('change', '.itemCls', function(e) {
            var selectedText = $("#select2-partSlct-container").text();
            var selectedType = $(this).select2('data')[0].type_id
            var selectedPartID = $(this).val();
            $.ajax({
                type: "GET",
                url: "/partDetailsArd",
                data: {
                    PartID: selectedPartID,
                    typeId: selectedType
                },
                success: function(data) {
                    console.log(data);
                    $("#itemTbl tbody").empty();
                    if (data[0].stores) {
                        var dataRow = '';
                        var part = '';
                        data.forEach(element => {
                            if (selectedType == 1) {
                                part = element.part;
                                part['type'] = 1;
                                element['type'] = 1;
                            } else if (selectedType == 2) {
                                part = element.wheel;
                                part['type'] = 2;
                                element['type'] = 2;


                            } else if (selectedType == 3) {

                                part = element.tractor;
                                part['type'] = 3;
                                element['type'] = 3;

                            } else if (selectedType == 4) {

                                part = element.clark;
                                part['type'] = 4;
                                element['type'] = 4;

                            } else if (selectedType == 5) {

                                part = element.equip;
                                part['type'] = 5;
                                element['type'] = 5;

                            } else if (selectedType == 6) {
                                part = element.kit;
                                part['type'] = 6;
                                element['type'] = 6;


                            }
                            dataRow += `<tr>`;
                            dataRow += `<td>${ part.name }</td>`;
                            dataRow += `<td> ${element.source.name_arabic} </td>`;
                            dataRow += `<td> ${element.status.name} </td>`;
                            dataRow += `<td> ${element.part_quality.name} </td>`;
                            dataRow += `<td>(${element.remain_amount /element.ratiounit} / ${element.part.bigunit.name}) المتاح</td>`;

                            element.stores.forEach(element2 => {
                                dataRow +=
                                `<td>${element2.totalAmount / element.ratiounit}  / ${element.part.bigunit.name}/ ${element2.store.name} </td>`;
                            });
                            dataRow +=
                            `<td> ${ element.pricing.length > 0 ? element.pricing[0].sale_type['type'] +'/'+ (element.pricing[0].price *element.ratiounit) : 0} ج</td>`;
                            dataRow +=
                                `<td class="align-items-center border btn-link cursor-pointer d-flex justify-content-between list-group-item" onclick='addNew(${JSON.stringify(part)} , ${JSON.stringify(element)})'><span><i class="mdi mdi-arrow-down-bold"></i> </span> <span class="badge bg-primary rounded-pill"></span></td>`;


                            dataRow += `<tr>`;

                        });
                        $("#itemTbl tbody").append(dataRow);
                    } else {
                        alert('not in store');

                        data.forEach(element => {
                            if (selectedType == 1) {
                                // part = element.part;
                                // part['type'] = 1;
                                element['type'] = 1;
                            } else if (selectedType == 2) {
                                // part = element.wheel;
                                // part['type'] = 2;
                                element['type'] = 2;


                            } else if (selectedType == 3) {

                                // part = element.tractor;
                                // part['type'] = 3;
                                element['type'] = 3;

                            } else if (selectedType == 4) {

                                // part = element.clark;
                                // part['type'] = 4;
                                element['type'] = 4;

                            } else if (selectedType == 5) {

                                // part = element.equip;
                                // part['type'] = 5;
                                element['type'] = 5;

                            } else if (selectedType == 6) {
                                // part = element.kit;
                                // part['type'] = 6;
                                element['type'] = 6;


                            }
                            dataRow += `<tr>`;
                            dataRow += `<td>${ element.name }</td>`;
                            dataRow += `<td> - </td>`;
                            dataRow += `<td> - </td>`;
                            dataRow += `<td> - </td>`;
                            dataRow += `<td>(0) المتاح</td>`;
                            stores.forEach(element2 => {
                                if(element2.table_name =='damaged_parts'){

                                }else{
                                    dataRow +=

                                    `<td>0 / ${element2.name} </td>`;
                                }

                            });
                            dataRow +=
                                `<td>0 ج</td>`;
                            dataRow +=
                                `<td class="align-items-center border btn-link cursor-pointer d-flex justify-content-between list-group-item" onclick='addNew(${JSON.stringify(element)} , ${JSON.stringify(element)})'><span><i class="mdi mdi-arrow-down-bold"></i> </span> <span class="badge bg-primary rounded-pill"></span></td>`;


                            dataRow += `<tr>`;
                            $("#itemTbl tbody").append(dataRow);
                        })

                    }


                }
            })


        });


        function addNew(object, element) {

            // console.log(object);
            var weight = 0;
            var namee = '';
            var type = object.type;

            if (type == 1) {

                var part_specs = object.part_details;
                if (part_specs.length > 0) {
                    var p_weight1 = {};
                    if (part_specs.find(a => /وزن/.test(a.part_spec.name))) {
                        p_weight1 = part_specs.find(a => /وزن/.test(a.part_spec.name));
                        weight = parseFloat(p_weight1.value).toFixed(4);
                    }
                } else {
                    weight = 0;
                }
                var measureUnits = object.getsmallunit;
                var samllmeasureUnits = object.smallunit;
                var bigmeasureUnits = object.bigunit;
                // console.log(bigmeasureUnits);
                var measureUnitsElement = '<select name="measureUnit[]" required class="form-select partunit mesureClass"><option value="" selected disabled>Choose Units</option>';
                    if(measureUnits.length > 0){
                        measureUnits.forEach(element => {
                            if(element.unit.id == bigmeasureUnits.id){
                                measureUnitsElement +='<option value="'+element.unit.id+'"  data-val="'+element.value+'" selected>'+element.unit.name+'</option>';    

                            }else{
                                measureUnitsElement +='<option value="'+element.unit.id+'"  data-val="'+element.value+'">'+element.unit.name+'</option>';    

                            }
                        });
                        measureUnitsElement +='</select>';
                    }else{
                        measureUnitsElement +='</select>';
                
                    }
            } else if (type == 2) {

                var part_specs = object.wheel_details;
                if (part_specs.length > 0) {
                    var p_weight2 = {};
                    if (part_specs.find(a => /وزن/.test(a.wheel_spec.name))) {
                        p_weight2 = part_specs.find(a => /وزن/.test(a.wheel_spec.name));
                        weight = parseFloat(p_weight2.value).toFixed(4);
                    }
                } else {
                    weight = 0;
                }
                var measureUnits = object.getsmallunit;
                var samllmeasureUnits = object.smallunit;
                var bigmeasureUnits = object.bigunit;
                var measureUnitsElement = '<select name="measureUnit[]" required class="form-select partunit mesureClass"><option value="" selected disabled>Choose Units</option>';
                    if(measureUnits.length > 0){
                        measureUnits.forEach(element => {
                            if(element.unit.id == bigmeasureUnits.id){
                                measureUnitsElement +='<option value="'+element.unit.id+'"  data-val="'+element.value+'" selected>'+element.unit.name+'</option>';    

                            }else{
                                measureUnitsElement +='<option value="'+element.unit.id+'"  data-val="'+element.value+'">'+element.unit.name+'</option>';    

                            }
                        });
                        measureUnitsElement +='</select>';
                    }else{
                        measureUnitsElement +='</select>';
                
                    }
            } else if (type == 6) {

                var part_specs = object.kit_details;
                if (part_specs.length > 0) {
                    var p_weight3 = {};
                    if (part_specs.find(a => /وزن/.test(a.kit_specs.name))) {
                        p_weight3 = part_specs.find(a => /وزن/.test(a.kit_specs.name));
                        weight = parseFloat(p_weight3.value).toFixed(4);
                    }
                } else {
                    weight = 0;
                }
            } else if (type == 3) {

                var part_specs = object.tractor_details;

                if (part_specs.length > 0) {
                    var p_weight4 = {};
                    if (part_specs.find(a => /وزن/.test(a.tractor_spec.name))) {
                        p_weight4 = part_specs.find(a => /وزن/.test(a.tractor_spec.name));
                        weight = parseFloat(p_weight4.value).toFixed(4);
                    }
                } else {
                    weight = 0;
                }
            } else if (type == 4) {

                var part_specs = object.clark_details;

                if (part_specs.length > 0) {
                    var p_weight5 = {};
                    if (part_specs.find(a => /وزن/.test(a.clark_spec.name))) {
                        p_weight5 = part_specs.find(a => /وزن/.test(a.clark_spec.name));
                        weight = parseFloat(p_weight5.value).toFixed(4);
                    }
                } else {
                    weight = 0;
                }
            } else if (type == 5) {

                var part_specs = object.equip_details;

                if (part_specs.length > 0) {
                    var p_weight6 = {};
                    if (part_specs.find(a => /وزن/.test(a.equip_spec.name))) {
                        p_weight6 = part_specs.find(a => /وزن/.test(a.equip_spec.name));
                        weight = parseFloat(p_weight6.value).toFixed(4);
                    }
                } else {
                    weight = 0;
                }
            }

            // console.log(element);
            var m = $("#itemsTbl tbody").length;
            var founded =
                `tr[data-def="${element.part_id}-${element.source_id}-${element.status_id}-${element.quality_id}"]`;
            if ($(founded).length <= 0) {


                $("#itemCounter").text(parseInt($("#itemCounter").text()) + 1);
                if (element.stores) {
                    // alert('no all parts');
                    if (type == 1) {
                        namee = element.part.name;


                    } else if (type == 2) {
                        namee = element.wheel.name;

                    } else if (type == 6) {
                        namee = element.kit.name;

                    } else if (type == 3) {
                        namee = element.tractor.name;

                    } else if (type == 4) {
                        namee = element.clark.name;

                    } else if (type == 5) {
                        namee = element.equip.name;

                    }
                    var newRoww = ` <tr data-def='${element.part_id}-${element.source_id}-${element.status_id}-${element.quality_id}'>
                            <input type="hidden" name="parts[]" value="${element.part_id}">
                            <input type="hidden" name="types[]" value="${element.type}">
                            <input type="hidden" name="source[]" value="${element.source_id}">
                            <input type="hidden" name="status[]" value="${element.status_id}">
                            <input type="hidden" name="quality[]" value="${element.quality_id}">
                            <input type="hidden" name="samllmeasureUnits[]" value="${samllmeasureUnits.id}">
                             <input type="hidden" name="itemPrice[]" value="${element.pricing.length > 0 ? element.pricing[0].price: ''}">
                                <input type="hidden" name="bigunitid[]" value="${bigmeasureUnits.id}">
                                <td>${namee}</td>
                                <td>${weight}</td>
                                <td>${element.source.name_arabic}</td>
                                <td>${element.status.name}</td>
                                <td class="">${element.part_quality.name}</td>
                                <td><input class="form-control amounts"  type="number" name="amount[]" id=""  value="1" required></td>
                                <td>${measureUnitsElement}</td>
                                <td><input type="number" name="price[]" step=any class="form-control prices" id=""  value="${element.pricing.length > 0 ? element.pricing[0].price : ''}" required></td>
                                <td onclick="$('#itemCounter').text( parseInt($('#itemCounter').text()) -1 );$(this).closest('tr').remove();checksubTotal()"><button class="btn btn-danger">DELETE</button></td> <td></td>
                            </tr>`;


                        // var currentData = table.data().toArray();

                        // var newRoww = $(newRowHtml);
                        // var newRowData = [];

                        // newRoww.find('td').each(function() {
                        //     newRowData.push($(this).html());
                        // });

                        // currentData.unshift(newRowData);
                        // table.clear();
                        // table.rows.add(currentData).draw();


                        // table.row.add($(newRoww)).draw(false);
                        // table.order([0, 'desc']).draw(false);
                    table.row.add($(newRoww)).draw();
                } else {
                    var newRoww  = `<tr data-def=''>
                        <input type="hidden" name="parts[]" value="${object.id}">
                        <input type="hidden" name="types[]" value="${object.type}">

                            <td>${object.name}</td>
                            <td>${weight}</td>
                            <td><select name="source[]" class="form-select required" required id="">${sourceOption}</select></td>
                            <td><select name="status[]" class="form-select required" id="" required>${statusOption}</select></td>
                            <td class=""><select name="quality[]" class="form-select required" id="" required>${qualityOption}</select></td>
                            <td><input class="form-control amounts"  type="number" name="amount[]" id=""  value="1" required></td>
                            <td>${measureUnitsElement}</td>
                            <td><input type="number" name="price[]" step=any class="form-control prices" id="" required></td>
                            <td onclick="$('#itemCounter').text( parseInt($('#itemCounter').text()) -1 );$(this).closest('tr').remove();checksubTotal()"><button class="btn btn-danger">DELETE</button></td> <td></td>
                        </tr>`;
                        // var currentData = table.data().toArray();

                        // var newRoww = $(newRowHtml);
                        // var newRowData = [];

                        // newRoww.find('td').each(function() {
                        //     newRowData.push($(this).html());
                        // });

                        // currentData.unshift(newRowData);
                        // table.clear();
                        // table.rows.add(currentData).draw();

                        // table.row.add($(newRoww)).draw(false);
                        // table.order([0, 'desc']).draw(false);

                        table.row.add($(newRoww)).draw();
                }

            }
            checkweightTotal();
            checksubTotal();

        }

        function addItemToDataTable(newItem) {
            // Get the existing DataTable object
            var table = $('#itemsTbl').DataTable();

            // Insert the new row at the top of the DataTable
            table.row.add(newItem).node().prependTo(table.row().table().body()).draw();
        }

        function removeRow(button) {
            var table = $('#itemsTbl').DataTable();
            var row = $(button).closest('tr');
            table.row(row).remove().draw();
            $("#itemCounter").text(parseInt($("#itemCounter").text()) - 1);
            checksubTotal();
        }


        var xtable = '';
        $('#presaleOrderTbl').dataTable();



        function checkweightTotal() {
            var total_weight = 0;
            // var amounts = $('input.amounts').map(function() { return $(this).val(); }).get();

            // alert("xxxx");

            table.rows().every(function() {
                let weight = parseFloat(this.data()[1]) || 0;
                let amount = parseFloat($(this.node()).find('td:eq(5)').children().val()) || 1;
                total_weight += weight * amount;
            });

            $("#totalweight").text(total_weight.toFixed(4));

            $("#totalweighttxt").val(total_weight.toFixed(4));

        }

        function checksubTotal() {
            checkweightTotal();
            var tax = 0;
            var subtotal = 0;
            var taxValue = 0;
            var total = 0;

            var taxes = $('input[type="checkbox"][name="taxes\\[\\]"]:checked').map(function() {
                return $(this).attr('data-value');
            }).get();
            var prices = $('input.prices').map(function() {
                return $(this).val();
            }).get();
            var amounts = $('input.amounts').map(function() {
                return $(this).val();
            }).get();

            for (let i = 0; i < amounts.length; i++) {
                subtotal += ((parseFloat(amounts[i]) > 0) ? parseFloat(amounts[i]) : 0) * ((parseFloat(prices[i]) > 0) ?
                    parseFloat(prices[i]) : 0);
            }

            for (let i = 0; i < taxes.length; i++) {
                tax += ((parseFloat(taxes[i])) ? parseFloat(taxes[i]) : 0);
            }

            taxValue = tax * subtotal / 100;
            $("#subtotal").text(subtotal);
            $("#tax").text(taxValue);
            $("#total").text(subtotal + taxValue);

            $("#subtotalv").val(subtotal);
            $("#taxv").val(taxValue);
            $("#totalv").val(subtotal + taxValue);



        }

        $(document).on('keyup', '.price', function() {
            checksubTotal();
        })
        $(document).on('keyup', '.amounts', function() {
            checksubTotal();
        })



        $("input[name='shownumbers']").change(function() {
            if (this.checked) {
                var xx = $(this).closest('tr').find('.printbtns');

                for (let index = 0; index < xx.length; index++) {
                    const element = xx[index];
                    var h = element.href.slice(0, -1);
                    h = h + '1';
                    $(element).prop('href', h)
                }

            } else {
                var xx = $(this).closest('tr').find('.printbtns');

                for (let index = 0; index < xx.length; index++) {
                    const element = xx[index];
                    var h = element.href.slice(0, -1);
                    h = h + '0';
                    $(element).prop('href', h)
                }
            }

        });
        
        $("#typeSelect").change(function(){
            const typeId = this.value;
            document.querySelectorAll('#itemsTbl tbody tr').forEach(row => {
                const priceElement = row.querySelector(`.priceT-${typeId}`);
                const priceText = priceElement ? priceElement.textContent : 0 ;
                const priceTarget = row.querySelector('.prices');
                if (priceTarget && priceText) {
                    priceTarget.value = parseFloat(priceText) || 0;
                }
            });

        })
        
    </script>







@endsection
