@extends('layouts.master')
@section('title')
    طلب بضاعة
@stop
@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active"> Tractor Order Parts </li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <form action="/save_tawreedServices" method="POST">
                @csrf
                @method('POST')
                <div class="card">


                    <div class="card-body fs-19 fw-bold">
                        <div class="row mb-2">
                            <div class="col-lg-2">
                                <label for="">المخزن </label>

                            </div>
                            <div class="col-lg-8">

                                <select class="form-control" name="store" id="storeSlct" required>
                                    <option selected disabled value="">اختر</option>
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                    @endforeach
                                </select>


                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-6">
                                <h4 style=" text-align: start">نوع المعدة</h4>
                                <div class="radio-button-container justify-content-center">
                                    <div class="radio-button d-inline-block col-3">
                                        <input type="radio" name="serviceType" class="radio-button__input" value="Tractor"
                                            id="tractor" required="required">
                                        <label class="radio-button__label" for="tractor">
                                            <span class="radio-button__custom"></span>
                                            جرار
                                        </label>
                                    </div>
                                    <div class="radio-button d-inline-block col-3">
                                        <input type="radio" name="serviceType" class="radio-button__input"
                                            value="Equipment" id="equipment">
                                        <label class="radio-button__label" for="equipment">
                                            <span class="radio-button__custom"></span>
                                            معدة
                                        </label>
                                    </div>
                                    <div class="radio-button d-inline-block col-3">
                                        <input type="radio" name="serviceType" class="radio-button__input" value="Clark"
                                            id="clark">
                                        <label class="radio-button__label" for="clark">
                                            <span class="radio-button__custom"></span>
                                            كلارك
                                        </label>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="">اختر </label>
                                <select class="form-control" name="optionSelect" id="optionSelect" required>
                                    <option selected disabled value="">اختر</option>

                                </select>
                            </div>
                        </div>

                        <div class="row m-3 border">

                            <div class="col-lg-10">
                                <div class=" p-4 bg-light">
                                    <label for=""> بحث </label>
                                    {{-- <input class="form-control" placeholder="Search Here" type="search" name="" id=""> --}}
                                    <select name="partSlct" id="partSlctSS" class="itemCls"></select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <table id="itemTbl" class="table table-striped">
                                    <thead>



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



                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col-lg-6">
                                <h3>عدد الأصناف <span id="itemCounter">0</span></h3>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <table id="itemsTbl" class="table table-striped " style="width:100%">
                                    <thead style="background:#5fcee78a">
                                        <tr>

                                            <td>الصنف</td>
                                            <td>الوزن</td>
                                            <td>الحالة</td>
                                            <td>النوع</td>
                                            <td>الجودة</td>
                                            <td>الكمية</td>
                                            <td class="d-none">السعر</td>
                                            <td class="d-none">أقل سعر </td>
                                            <td class="d-none">أكبر سعر</td>
                                            <td>حذف</td>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <button class="btn btn-info w-100"> طلب </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>




        </div>




    @endsection
    @section('js')
        <script>
            $(document).ready(function() {
                $(".itemCls").select2({

                    ajax: {
                        url: "partsSearch",
                        //   dataType: 'json',
                        async: false,
                        delay: 250,
                        data: function(params) {
                            return {
                                // (params.term).replace(/\//g," ").toLowerCase()
                                q: encodeURIComponent((params.term).toLowerCase()), // search term
                                page: params.page,
                                type: $('#slected_type').val()
                            };
                        },
                        processResults: function(data, params) {
                            // parse the results into the format expected by Select2
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data, except to indicate that infinite
                            // scrolling can be used
                            // $("#select2-partSlct-results").empty();
                            // data.forEach(element => {
                            //     $("#select2-partSlct-results").append(`<li>${element.name}</li>`);
                            // });

                            params.page = params.page || 1;
                            return {
                                results: data,
                                //   pagination: {
                                //     more: (params.page * 30) < data.total_count
                                //   }
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
                    //     $("#select2-partSlct-results").append(repo);
                    //    return repo;
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
                    // $container.find(".select2-result-repository__description").text(repo.description);
                    // $container.find(".select2-result-repository__forks").append(repo.name + " Forks");
                    // $container.find(".select2-result-repository__stargazers").append(repo.name + " Stars");
                    // $container.find(".select2-result-repository__watchers").append(repo.name + " Watchers");

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


                $("input:radio[name='serviceType']").change(function() {
                if ($("input[name='serviceType']:checked").val() == "Tractor") {
                    selecturl = "tractor";

                } else if ($("input[name='serviceType']:checked").val() == "Equipment") {
                    selecturl = "equip";
                } else if ($("input[name='serviceType']:checked").val() == "Clark") {
                    selecturl = "clark";
                }
                var store_id = 8;
                if ( $("#storeSlct").val() > 0) {
                    store_id = $("#storeSlct").val();
                } else {
                    alert('Select Store');
                    return false;
                }
                $.ajax({
                    type: "get",
                    url: '/servicegetItem',
                    data: {
                        'servicType': selecturl,
                        'store_id': store_id
                    },
                    success: function(response) {
                        console.log(response);
                        $("#optionSelect").empty();
                        $("#optionSelect").append(`<option disabled selected value="">Select ` +
                            selecturl + `</option>`);

                        if (selecturl == 'clark') {
                            response.forEach(element => {
                                $("#optionSelect").append(
                                    `<option value="${element.stores_log.all_clarks[0].clark.id}">${element.stores_log.all_clarks[0].clark.name} / ${element.stores_log.all_clarks[0].clark.clark_number}</option>`
                                    );
                            });
                        } else if (selecturl == 'tractor') {
                            response.forEach(element => {
                                $("#optionSelect").append(
                                    `<option value="${element.stores_log.all_tractors[0].tractor.id}">${element.stores_log.all_tractors[0].tractor.name} / ${element.stores_log.all_tractors[0].tractor.tractor_number}</option>`
                                    );
                            });
                        } else if (selecturl == 'equip') {
                            response.forEach(element => {
                                $("#optionSelect").append(
                                    `<option value="${element.stores_log.all_equips[0].equip.id}">${element.stores_log.all_equips[0].equip.name}</option>`
                                    );
                            });
                        }

                    }
                });
            });
            })


            $(document).on('change', '.itemCls', function(e) {
                var selectedText = $("#select2-partSlct-container").text();
                var selectedType = $(this).select2('data')[0].type_id
                var selectedPartID = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "partDetailsArd",
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
                                dataRow += `<td>(${element.remain_amount}) المتاح</td>`;
                                element.stores.forEach(element2 => {
                                    dataRow +=
                                        `<td>${element2.totalAmount} / ${element2.store.name} </td>`;
                                });
                                dataRow +=
                                    // `<td>${ element.pricing > 0 ?  element.pricing[0].price : 0} ج</td>`;
                                    `<td class="text-nowrap"> ${ element.pricing.length > 0 ?element.pricing[0].sale_type['type']+'/'+ element.pricing[0].price : 0} ج</span></td>`;

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
                                    dataRow +=
                                        `<td>0 / ${element2.name} </td>`;
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
                        $("#itemsTbl").prepend(`
                        <tr data-def='${element.part_id}-${element.source_id}-${element.status_id}-${element.quality_id}'>
                            <input type="hidden" name="parts[]" value="${element.part_id}">
                            <input type="hidden" name="types[]" value="${element.type}">
                            <input type="hidden" name="source[]" value="${element.source_id}">
                            <input type="hidden" name="status[]" value="${element.status_id}">
                            <input type="hidden" name="quality[]" value="${element.quality_id}">
                                <td>${namee}</td>
                                <td>${weight}</td>
                                <td>${element.source.name_arabic}</td>
                                <td>${element.status.name}</td>
                                <td class="">${element.part_quality.name}</td>
                                <td><input class="form-control amounts"  type="number" name="amount[]" id="" value="1" required></td>
                                <td class="d-none"><input type="number" name="price[]" step=".1" class="form-control prices" id=""   value="${element.pricing.length > 0 ? element.pricing[0].price : ''}" required></td>
                                 <td class="minprices d-none">${element.pricing.length > 0 ? Math.min(...element.pricing.map(item => item.price)) : ''}</td>
                                 <td class="maxprices d-none">${element.pricing.length > 0 ? Math.max(...element.pricing.map(item => item.price)) : ''}</td>
                                <td onclick="$('#itemCounter').text( parseInt($('#itemCounter').text()) -1 );$(this).closest('tr').remove();checksubTotal()"><i class="mdi mdi-trash-can-outline text-bg-danger px-3 py-0 rounded"></i></td>
                            </tr>
                        `);
                    } else {

                        $("#itemsTbl").prepend(`
                        <tr data-def=''>
                        <input type="hidden"  name="parts[]" value="${object.id}">
                        <input type="hidden" name="types[]" value="${object.type}">

                            <td>${object.name}</td>
                            <td>${weight}</td>
                            <td><select name="source[]" class="form-select required" required id="">${sourceOption}</select></td>
                            <td><select name="status[]" class="form-select required" id="" required>${statusOption}</select></td>
                            <td class=""><select name="quality[]" class="form-select required" id="" required>${qualityOption}</select></td>
                            <td><input class="form-control amounts"  type="number" name="amount[]" id="" value="1" ></td>
                            <td class="d-none"><input type="number" step=".1" name="price[]" class="form-control prices" id="" required></td>
                           <td class="minprices d-none">0</td>
                            <td class="maxprices d-none">0</td>
                             <td onclick="$('#itemCounter').text( parseInt($('#itemCounter').text()) -1 );$(this).closest('tr').remove();checksubTotal()"><i class="mdi mdi-trash-can-outline text-bg-danger px-3 py-0 rounded"></i></td>

                        </tr>
                    `);
                    }

                }

            }

        </script>

    @endsection
