@extends('layouts.master')
@section('css')

    <style>
        .itemCls {}
    </style>

@endsection
@section('title')
    عرض أسعار
@stop


@section('content')



    <div class="main-content ">
        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Parts</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">parts</li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-primary m-1 float-left addNews" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop"> إضافة </button>

                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-head">
                            <div class="row m-2">
                                <div class="col-lg-6">
                                    {{-- <button class="btn btn-soft-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="ri-add-fill"></i></button>
                                    <a  class="btn btn-soft-info " href="customSearch"><i class="ri-user-search-fill"></i></a> --}}
                                </div>
                                <div class="col-lg-6 text-end">

                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive ">

                            <table class="table table-striped table-bordered cell-border " id="presaleOrderTbl"
                                style="width:100%">
                                <thead style="background:#5fcee78a">
                                    <tr>
                                        <th>رقم الفاتورة</th>
                                        <th>التاريخ</th>
                                        <th>الزبون</th>
                                        <th>موعد التسليم</th>
                                        <th> الحالة</th>
                                        <th> صورة العقد</th>
                                        <th> موقف العرض</th>
                                        <th>تسليم</th>
                                        <!-- <th>فك</th> -->
                                        <th>حذف</th>
                                        <th>تعديل</th>
                                        <th>طباعة </th>
                                        <th>الأرقــام </th>
                                        <th>الختم </th>
                                        @can('presale_to_invoice')
                                            <th>تم المراجعة من المدير </th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($presaleOrder as $order)
                                        <tr class="text-center">
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->created_at }}</td>
                                            <td>{{ $order->client->name }}</td>
                                            <td>{{ $order->due_date }} </td>
                                            <td>
                                                @if ($order->flag == 0)
                                                    <span> جاري التجهيز </span>
                                                @elseif($order->flag == 1)
                                                    <span> تم تجهيزها </span>
                                                @elseif($order->flag == 2)
                                                    <span> تم التسليم </span>
                                                @elseif($order->flag == 3)
                                                    <span> تم التحوبل لفاتورة </span>
                                                @else
                                                    <span>---</span>
                                                @endif

                                            </td>
                                            <td>
                                                <a target="_blank" href="assets/presaleOrderImage/{{ $order->img }}">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                {{-- <img src="assets/presaleOrderImage/{{ $order->img }}" alt=""> --}}
                                            </td>
                                            <td>
                                                <button onclick="checkPreorder({{ $order->id }})" class="btn">
                                                    <i class="mdi mdi-arrow-collapse-left"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <a target="_self" href="preSaleToInvoice/{{ $order->id }}"
                                                    class="btn">
                                                    <i class="mdi mdi-check"></i>
                                                </a>
                                            </td>
                                            <!-- <td>
                                                                    <button class="btn">
                                                                        <i class="mdi mdi-close"></i>
                                                                    </button>
                                                                </td> -->
                                            <td>
                                                <button class="btn">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <a href="edit_asar/{{ $order->id }}" class="btn">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                            </td>
                                            <td>

                                                <a title="عربي" href="printpreSale/ar/{{ $order->id }}/0"
                                                    class="btn btn-outline-info printbtns">
                                                    <i class="mdi mdi-printer"></i>
                                                </a>

                                                <a title="English" href="printpreSale/en/{{ $order->id }}/0"
                                                    class="btn btn-outline-info printbtns">
                                                    <i class="mdi mdi-printer-outline"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="shownumbers" id="">
                                            </td>
                                              <td>
                                                <input type="checkbox" name="showLogo" id="">
                                            </td>
                                             @can('presale_to_invoice')
                                                <td>
                                                    @if ($order->admin_confirm)
                                                        <input type="checkbox" value="{{ $order->id }}" checked name="adminConfirm" id="">
                                                    @else
                                                        <input type="checkbox" name="adminConfirm" value="{{ $order->id }}" id="">
                                                    @endif

                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form enctype="multipart/form-data" action="asar" method="POST">
                        @csrf
                        @method('POST')
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel"> عرض أسعار
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-1 m-0 my-2 p-0">
                                    <button type="button"  class="AddnewClient btn m-0 p-0"><i class="fs-2 mdi mdi-plus-circle text-secondary"></i></button>
                                </div>
                                <div class="col-lg-4">
                                    <label for="" class=" p-0 text-end"> العميـــــل</label>

                                    <select name="clientId" class="form-select required" id="clientId" required>
                                        <option selected disabled value="">إختر العميــــــل</option>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}/{{ $client->tel01 }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label for="" class=" p-0 text-end"> العرض سارى حتى  </label>

                                    <input type="date" name="dueDate" class="form-control" id="dueDate">
                                </div>
                                
                                <div class="col-lg-3 ">
                                    <label for="" class=" p-0 text-end">صورة العقد</label>
                                    <input name="contractImg" type="file" class="form-control">
                                </div>

                            </div>
                            <div class="row my-2 ">
                                <div class="col-lg-6">
                                    <label for="" class=" p-0 text-end"> مكان التسليم</label>

                                    <input name="location" type="text" class="form-control">
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="p-0 text-end"> مخزن التجميع </label>

                                    <select name="storeId" class="form-control" required id="">
                                        <option value="" selected disabled>Select Store</option>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">

                                @foreach ($taxes as $tax)
                                    {{-- <option data-value="{{ $tax->value }}" value="{{ $tax->id}}">{{ $tax->name }} / {{ $tax->value }}</option> --}}

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
                                @endforeach


                            </div>
                            <br>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="partSearch m-2"></div>

                                        </div>
                                        <div class="col-lg-6">
                                            <div>
                                                <input type="hidden" name="slected_type" id="slected_type"
                                                    value="">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">

                                        </div>
                                    </div>

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
                                <div class="col-lg-6"><h3>عدد الأصناف <span id="itemCounter">0</span></h3></div>
                                <div class="col-lg-6 text-end"><button type="button" id="checkPrice" class="btn btn-info">مراجعة الأسعار</button></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <table id="itemsTbl" class="table table-striped">
                                        <thead>
                                            <tr>

                                                <td>الصنف</td>
                                                <td>الوزن</td>
                                                <td>الحالة</td>
                                                <td>النوع</td>
                                                <td>الجودة</td>
                                                <td>الكمية</td>
                                                <td>الوحدة</td>
                                                <td>السعر</td>
                                                <td>أقل سعر </td>
                                                <td>أكبر سعر</td>
                                                <td>حذف</td>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-4">

                                </div>
                                <div class="col-lg-4">
                                    <span>Total Item Weight :</span>
                                    <span id="totalweight">0</span> / KG
                                    <input type="hidden" name="totalweight" id="totalweighttxt" value="0">
                                </div>
                                <div class="col-lg-4">

                                </div>
                            </div>
                            <hr>
                            <div class="border border-2 border-warning p-2 row">
                                <div class="col-lg-3">
                                    SubTotal : <span id="subtotal">0</span>
                                </div>
                                <div class="col-lg-3">
                                    Tax : <span id="tax">0</span>
                                </div>
                                <div class="col-lg-3">
                                    Total : <span id="total">0</span>
                                </div>
                                <div class="col-lg-3">
                                    {{-- Mad : <span id="clientMad">0</span> --}}
                                </div>
                                <input type="hidden" name="subtotal" id="subtotalv" value="0">
                                <input type="hidden" name="tax" value="0" id="taxv">
                                <input type="hidden" name="total" value="0" id="totalv">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn mb-2 btn-primary">حفظ</button>
                            <button type="button" class="btn mb-2 btn-secondary" data-bs-dismiss="modal">غلق</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>



        <div class="modal fade" id="preorderShow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="preorderShowLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel"> عرض أسعار
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h2 class="text-center" id="OrderStatusName"></h2>
                        <h4 class="text-center"><span>التجميع بــ / </span><span id="OrderStoreName"></span></h4>
                        <table class="table table-striped table-bordered cell-border " id="preorderstatusTbl"
                            style="width:100%">
                            <thead style="background:#5fcee78a">
                                <tr>
                                    <th>Part Name</th>
                                    <th> Amount</th>
                                    <th> Source</th>
                                    <th>Status</th>
                                    <th>Quality</th>
                                    <th>Available</th>
                                    <th>Stores</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>



                </div>
            </div>
        </div>
        <div class="modal fade" id="newclientMdl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="newclientMdlLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 80vw !important;">
            <div class="modal-content w-100" style="width: 100%!important">
                <div class="modal-header ">
                    <h5 class="modal-title" id="newclientMdlLabel">عميل جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">

                        <div class="row">
                            <div class="col-lg-1">
                                <label for="">الاسم</label>
                            </div>
                            <div class="col-lg-3">
                                <input type="text" class="form-control" name="" id="clientName">
                            </div>
                            <div class="col-lg-1">
                                <label for="">رقم التليفون</label>
                            </div>
                            <div class="col-lg-3">
                                <input type="text" class="form-control" name="" id="clientTel">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <button type="button" id="saveNewClient" class="btn btn-success mt-2 w-100">Save</button>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">

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



            $(document).ready(function() {

                stores = {!! json_encode($stores) !!};

                sourceList = {!! json_encode($source) !!};
                statusList = {!! json_encode($status) !!};
                qualityList = {!! json_encode($quality) !!};

                sourceOption += `<option value="" disabled selected>Select Source</option>`;
                statusOption += `<option value="" disabled selected>Select Status</option>`;
                qualityOption += `<option value="" disabled selected>Select Quality</option>`;


                 $('#clientId').select2({
                    tags: true ,
                    dropdownParent: $("#staticBackdrop")
                });

                sourceList.forEach(element => {
                    sourceOption += `<option  value="${element.id}">${element.name_arabic}</option>`;
                });
                statusList.forEach(element => {
                    statusOption += `<option  value="${element.id}">${element.name}</option>`;
                });
                qualityList.forEach(element => {
                    qualityOption += `<option  value="${element.id}">${element.name}</option>`;
                });

                $(".addNews").hide()
                $(".partSearch").append(`<select name="" class="form-select itemCls" id="">${partsOption}</select>`)

                $(".addNews").show()




                $(".itemCls").select2({
                    dropdownParent: $("#staticBackdrop"),
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


            });




            $("#newitemBtn").click(function(e) {
                e.preventDefault();

            });

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
                                dataRow += `<td>(${element.remain_amount /element.ratiounit} / ${element.part.bigunit.name}) المتاح</td>`;
                                element.stores.forEach(element2 => {
                                    dataRow +=
                                    `<td>${element2.totalAmount / element.ratiounit}  / ${element.part.bigunit.name}/ ${element2.store.name} </td>`;
                                });
                                dataRow +=
                                    // `<td>${ element.pricing > 0 ?  element.pricing[0].price : 0} ج</td>`;
                                    `<td class="text-nowrap"> ${ element.pricing.length > 0 ?element.pricing[0].sale_type['type']+'/'+ (element.pricing[0].price * element.ratiounit ): 0} ج</span></td>`;

                                dataRow +=
                                `<td class="align-items-center border btn-link cursor-pointer d-flex justify-content-between list-group-item" onclick='addNew(${JSON.stringify(part)} , ${JSON.stringify(element)})'><span><i class="mdi mdi-arrow-down-bold"></i> </span> <span class="badge bg-primary rounded-pill"></span></td>`;
                                `<tr>`;

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
                    var measureUnits = object.getsmallunit;
                    var samllmeasureUnits = object.smallunit;
                    var bigmeasureUnits = object.bigunit;
                    console.log(bigmeasureUnits);
                    var measureUnitsElement = '<select name="measureUnit[]" required class="form-control-sm py-0 mesureClass"><option value="" selected disabled>Choose Units</option>';
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
                    console.log(bigmeasureUnits);
                    var measureUnitsElement = '<select name="measureUnit[]" required class="form-control-sm py-0 mesureClass"><option value="" selected disabled>Choose Units</option>';
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
                        $("#itemsTbl").prepend(`
                        <tr data-def='${element.part_id}-${element.source_id}-${element.status_id}-${element.quality_id}'>
                            <input type="hidden" name="parts[]" value="${element.part_id}">
                            <input type="hidden" name="types[]" value="${element.type}">
                            <input type="hidden" name="source[]" value="${element.source_id}">
                            <input type="hidden" name="status[]" value="${element.status_id}">
                            <input type="hidden" name="quality[]" value="${element.quality_id}">
                               <input type="hidden" name="samllmeasureUnits[]" value="${samllmeasureUnits.id}">
                             <input type="hidden" name="itemPrice[]" value="${element.pricing.length > 0 ? element.pricing[0].price : ''}">
                                <input type="hidden" name="bigunitid[]" value="${bigmeasureUnits.id}">
                                <td>${namee}</td>
                                <td>${weight}</td>
                                <td>${element.source.name_arabic}</td>
                                <td>${element.status.name}</td>
                                <td class="">${element.part_quality.name}</td>
                                <td><input class="form-control amounts"  type="number" name="amount[]" id="" value="1" required></td>
                                <td>${measureUnitsElement}</td>

                                <td><input type="number" name="price[]" step=".1" class="form-control prices" id=""   value="${element.pricing.length > 0 ? element.pricing[0].price : ''}" required></td>
                                <input type="hidden" class="init_price" value="${element.pricing.length > 0 ? element.pricing[0].price : ''}">

                                <td class="minprices">${element.pricing.length > 0 ? Math.min(...element.pricing.map(item => item.price)) : ''}</td>
                                 <td class="maxprices">${element.pricing.length > 0 ? Math.max(...element.pricing.map(item => item.price)) : ''}</td>
                                <td onclick="$('#itemCounter').text( parseInt($('#itemCounter').text()) -1 );$(this).closest('tr').remove();checksubTotal()"><button class="btn btn-danger">DELETE</button></td>
                              <input type="hidden" class="initminprices" value="${element.pricing.length > 0 ? Math.min(...element.pricing.map(item => item.price))*element.ratiounit : ''}">
                                <input type="hidden" class="initmaxprices" value="${element.pricing.length > 0 ? Math.max(...element.pricing.map(item => item.price))*element.ratiounit : ''}">
                                </tr>
                        `);
                    } else {

                        $("#itemsTbl").prepend(`
                        <tr data-def=''>
                        <input type="hidden"  name="parts[]" value="${object.id}">
                        <input type="hidden" name="types[]" value="${object.type}">
  <input type="hidden" name="samllmeasureUnits[]" value="${samllmeasureUnits.id}">
                        <input type="hidden" name="bigunitid[]" value="${bigmeasureUnits.id}">

                            <td>${object.name}</td>
                            <td>${weight}</td>
                            <td><select name="source[]" class="form-select required" required id="">${sourceOption}</select></td>
                            <td><select name="status[]" class="form-select required" id="" required>${statusOption}</select></td>
                            <td class=""><select name="quality[]" class="form-select required" id="" required>${qualityOption}</select></td>
                            <td><input class="form-control amounts"  type="number" name="amount[]" id="" value="1" ></td>
                            <td><input type="number" step=".1" name="price[]" class="form-control prices" id="" required>
                            <input type="hidden" class="init_price" value="0">

                            </td>
                          
                            <td class="minprices">0</td>
                            <td class="maxprices">0</td>
                             <td onclick="$('#itemCounter').text( parseInt($('#itemCounter').text()) -1 );$(this).closest('tr').remove();checksubTotal()"><button class="btn btn-danger">DELETE</button></td>
<input type="hidden" class="initminprices" value="0">
                            <input type="hidden" class="initmaxprices" value="0">
                        </tr>
                    `);
                    }

                }
                $('.mesureClass').trigger('change');
                checkweightTotal();
                checksubTotal() ;
            }
            $(document).on('change', '.mesureClass', function(){
                var mesureVal = $('option:selected', this).attr('data-val') ;
                var rowPrice = parseFloat($(this).closest('tr').find('.init_price').val());
                var initminprices = parseFloat($(this).closest('tr').find('.initminprices').val());
                var initminprices = parseFloat($(this).closest('tr').find('.initminprices').val());
                $(this).closest('tr').find('.prices').val(mesureVal * rowPrice);
                $(this).closest('tr').find('.amounts').trigger('keyup');
                $(this).closest('tr').find('.minprices').text()
                $(this).closest('tr').find('.maxprices').text()
                
                
                checkweightTotal();
                checksubTotal();
            });
            $('#staticBackdrop').on('hidden.bs.modal', function() {
                $(this).find('form').trigger('reset');
                // $("#itemTbl tbody").empty();

            })
            var xtable = '';
            $('#presaleOrderTbl').dataTable();

            function checkPreorder(orderId) {
                $("#preorderstatusTbl tbody").empty();

                $.ajax({
                    type: "GET",
                    url: "GetOrderStatus/" + orderId,
                    success: function(response) {
                        // console.log(response);
                        $("#OrderStatusName").text('')
                        $("#OrderStoreName").text('')
                        $("#OrderStatusName").text(response.name);
                        $("#OrderStoreName").text(response.store.name);
                        if (response.presaleorderpart.length > 0) {

                            response.presaleorderpart.forEach(element => {
                                var storelist = '';
                                if (element.hasOwnProperty('stores')) {
                                    element.stores.forEach(elements => {
                                        storelist +=
                                        `<li class="align-items-center border btn-link cursor-pointer d-flex justify-content-between list-group-item">${elements.name}<span class="badge bg-primary rounded-pill">${elements.amount / element.ratiounit}  / ${element.unit.name}</span></li>`
                                    });
                                } else {
                                    storelist +=
                                        `<li class="align-items-center border btn-link cursor-pointer d-flex justify-content-between list-group-item">هذا الصنف غير متاح بالمخازن<span class="badge bg-primary rounded-pill">0</span></li>`
                                }

                                if (element.amount > element.total) {
                                    $("#preorderstatusTbl tbody").append(`<tr style="background:red">
                                    <td>${element.part.name}</td>
                                    <td> ${element.amount / element.ratiounit} / ${element.unit.name}</td>
                                    <td> ${element.status.name} </td>
                                    <td> ${element.source.name_arabic} </td>
                                    <td> ${element.quality.name} </td>
                                    <td> ${element.total / element.ratiounit} / ${element.unit.name} </td>

                                    <td>${storelist}</td>
                                </tr>`)
                                } else {
                                    $("#preorderstatusTbl tbody").append(`<tr>
                                    <td>${element.part.name}</td>
                                    <td> ${element.amount / element.ratiounit}  / ${element.unit.name}</td>
                                    <td> ${element.status.name} </td>
                                    <td> ${element.source.name_arabic} </td>
                                    <td> ${element.quality.name} </td>
                                    <td> ${element.total / element.ratiounit} / ${element.unit.name}</td>

                                    <td>${storelist}</td>
                                </tr>`)
                                }



                            });
                            if (!$.fn.DataTable.isDataTable('#preorderstatusTbl')) {
                                xtable = $("#preorderstatusTbl").dataTable({
                                    dom: "Bfrtip",
                                    responsive: true,
                                    buttons: ["print"],
                                })
                            }

                            $("#preorderShow").modal('toggle')
                        } else {
                            alert("لا يوجد أصناف")
                        }

                    }
                })

            }

            function checkweightTotal() {
                var total_weight = 0;
                // var amounts = $('input.amounts').map(function() { return $(this).val(); }).get();
                $('#itemsTbl > tr').each(function() {
                    if (($(this).children()).length <= 10) {
                        let weight = parseFloat($(this).children().eq(3).html()) || 0;
                        let amount = parseFloat($(this).children().eq(7).children().val()) || 1;
                        total_weight += weight * amount;
                    } else {
                        let weight = parseFloat($(this).children().eq(6).html()) || 0;
                        let amount = parseFloat($(this).children().eq(10).children().val()) || 1;
                        total_weight += weight * amount;
                    }

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

            $(document).on('keyup', '.prices', function() {
                checksubTotal();
            })

            $(document).on('keyup', '.amounts', function() {
                checksubTotal();
            })



            $(document).on('change', 'input[name="shownumbers22"]',function() {
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
            
               $(document).on('change', 'input[name="shownumbers"]',function() {
                if (this.checked) {
                    var xx = $(this).closest('tr').find('.printbtns');

                    for (let index = 0; index < xx.length; index++) {
                        const element = xx[index];
                        var h = element.href.slice(0, -1);
                        h = h + '1';

                        var url = new URL(element.href);
                        var pathParts = url.pathname.split('/'); // Split the path into parts
                        pathParts[pathParts.length - 1] = '1';
                        url.pathname = pathParts.join('/');
                        $(element).prop('href', url)
                    }

                } else {
                    var xx = $(this).closest('tr').find('.printbtns');

                    for (let index = 0; index < xx.length; index++) {
                        const element = xx[index];
                        var h = element.href.slice(0, -1);
                        h = h + '0';
                        var url = new URL(element.href);
                        var pathParts = url.pathname.split('/'); // Split the path into parts
                        pathParts[pathParts.length - 1] = '0';
                        url.pathname = pathParts.join('/');
                        $(element).prop('href', url)
                        // $(element).prop('href', h)
                    }
                }


            });

            $(document).on('change', 'input[name="showLogo"]',function() {
                var url = window.location.href;
                    var newUrl;
                    var param = 'kethm';
                    var value = 0;
                    if (this.checked) {

                        value = 1;

                    } else {
                        value = 0;
                    }
                    var xx = $(this).closest('tr').find('.printbtns');


                    for (let index = 0; index < xx.length; index++) {
                        const element = xx[index];
                        var url = new URL(element.href);
                        var searchParams = url.searchParams;
                        searchParams.delete('kethm');
                        searchParams.append('kethm',value)
                        url.search =searchParams.toString()
                        $(element).prop('href', url.toString())
                    }

            });
            
        $("#checkPrice").click(function(e){
                e.preventDefault();
    
    
    
                $('#itemsTbl > tr').each(function(index, row) {
                    var inputprice = parseFloat($(row).find('.prices').val())
                    var minprice = parseFloat($(row).find('.minprices').text())
    
                    if(inputprice < minprice){
                        $(this).addClass('text-bg-danger');
                    }else{
                        $(this).removeClass('text-bg-danger');
                    }
                })
        })
        
        
        
           function addNewClient(telNumber) {
            return $.ajax({
                type: "get",
                url: "newClientInline/" + telNumber
            });

        }
         $(document).on('click','.AddnewClient',function(e){

            $("#staticBackdrop").modal('toggle')
            $("#newclientMdl").modal('toggle')

        });
        
        
         $('#newclientMdl').on('hidden.bs.modal', function () {
            $("#staticBackdrop").modal('toggle')
        });


        $("#saveNewClient").click(function(e){
            e.preventDefault();
            var clientName =$("#clientName").val();
            var clientTel =$("#clientTel").val();
    
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "newClientQuick",
                data: {
                    'clientName' : clientName,
                    'clientTel' : clientTel
                },
                datatype: 'JSON',
                async: false,
                statusCode: {
                    404: function() {
                        alert("page not found");
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    // alert("some error");
                    console.log(errorThrown);
                },
                success: function(data) {
                    console.log(data);
                    reloadClients(data);
    
    
                },complete : function(data){
    
    
                }
            });
    
        });
    
    
        function reloadClients(newclientid){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "allClients",
                async: false,
                statusCode: {
                    404: function() {
                        alert("page not found");
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function(data) {
                    if(data){
                        $('#clientSlct').empty();
                        data.forEach(element => {
                            var textt = element.name +' / '+ element.tel01;
                            var newOption = new Option(textt, element.id, false, false);
                            $('#clientSlct').append(newOption);
                            if(element.id == newclientid){
                                $('#clientSlct').val(element.id).trigger('change');
                            }
        
                        });
                    }
                    $("#newclientMdl").modal('toggle');
                }
            });
        }
        
              $(document).on('change', 'input[name="adminConfirm"]',function() {
                var admin_confirm=0;

                if (this.checked) {
                    admin_confirm = 1;
                } else {
                    admin_confirm = 0;
                }

                $.ajax({
                    type: "get",
                    url: "presaleAdminConfirm",
                    data:{
                        status: admin_confirm ,
                        order_id : $(this).val()
                    },success: function(data) {
                        if(data){
                            if(admin_confirm){
                                alert("تم المراجعة")
                            }else{
                                alert("تم إرجاء المراجعة")
                            }

                        }else{

                        }



                    }
                });

            });
        
        </script>







    @endsection
