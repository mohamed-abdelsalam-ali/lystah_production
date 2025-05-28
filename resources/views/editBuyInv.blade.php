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
    Invoices
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
                        <h1 class="text-center text-info">فواتير الشــــــــــراء</h1>
                        <h3 class="text-center ">رقـــم ({{ $buyTrans->id }})
                            @if ($invoice_status)
                                <span> تم التوزيع علي المخازن </span>
                            @else
                                <span> لم يتم التوزيع علي المخازن </span>
                            @endif
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
                            @if ($invoice_status)
                                <form method="POST" class="needs-validation" novalidate id="addInvFrm"
                                    action="{{ url('editInv') }}" enctype="multipart/form-data">

                                    <form method="POST" class="needs-validation" novalidate id="addInvFrm"
                                        action="{{ url('storeManage') }}" enctype="multipart/form-data">
                            @endif

                            @csrf
                            <div class="row">
                                <div class="col-4">
                                    <input class="form-control" type="hidden" name="inv_id" value='{{ $buyTrans->id }}'>

                                    <label for="">Date</label>
                                    <input class="form-control" readonly name="invDate" id="invDate"
                                        value="{{ \Illuminate\Support\Carbon::parse($buyTrans->confirmation_date)->toDateString() }}"
                                        required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label for="">Company</label>
                                    <label class="form-control" name="invCompany" for="">AL AMAL</label>
                                </div>
                                <div class="col-4">
                                    <label for="">اختر حساب الصرف</label>> <span
                                        class="float-end text-bg-danger">المتاح ج.م<span id="Safetotal">{{ $total_rassed }}</span></span>
                                    <select name="store_id" id="store_idd" class="form-select " required>
                                        <option class="text-center" value="" disabled>اختر اسم الحساب</option>
                                        @foreach ($bank_types as $bank)
                                            @if ($bank->accountant_number == $orderSup[0]->bank_account)
                                                <option selected class="text-center" type-name="bank"
                                                    value="{{ $bank->accountant_number }}">{{ $bank->bank_name }} </option>
                                            @else
                                                <option class="text-center" type-name="bank"
                                                    value="{{ $bank->accountant_number }}">{{ $bank->bank_name }} </option>
                                            @endif
                                        @endforeach

                                        @foreach ($store_safe as $safe)
                                            @if ($safe->safe_accountant_number == $orderSup[0]->bank_account)
                                                <option selected class="text-center" type-name="store"
                                                    value="{{ $safe->safe_accountant_number }}">{{ $safe->name }}
                                                </option>
                                            @else
                                                <option class="text-center" type-name="store"
                                                    value="{{ $safe->safe_accountant_number }}">{{ $safe->name }}
                                                </option>
                                            @endif
                                        @endforeach



                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label for="">Supplier</label>
                                    <input type="hidden" name="supids" id="supids"
                                        value="{{ $buyTrans->order_suppliers[0]->supplier->id }}">
                                    <input type="hidden" name="curids" id="curids"
                                        value="{{ $buyTrans->order_suppliers[0]->currency_id }}">
                                    <select name="invSupplier" id="" class="form-select supp" required>


                                    </select>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>


                                </div>
                                <div class="col-4">
                                    <label class="mt-2" for="">Currency</label>
                                    <select name="currency_id" id="currencySlct" class="form-control  mt-1" required>

                                    </select>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label class="mt-2" for="">بحث عن </label>
                                    <select name="slected_type" id="slected_type" class="form-select " required>
                                        <option class="text-center" value="part" selected>قطع غيار </option>
                                        <option class="text-center" value="wheel"> كاوتش</option>
                                        <option class="text-center" value="kit"> كيت </option>

                                    </select>

                                </div>
                            </div>
                            <div class="row border mt-2">
                                <div class="col">
                                    <div class=" p-4 bg-light">
                                        <label for=""> بحث </label>
                                        {{-- <input class="form-control" placeholder="Search Here" type="search" name="" id=""> --}}
                                        <select name="partSlct" id="partSlct"></select>
                                    </div>
                                    <table class="table" id="newinvtbl">

                                        <thead>
                                            <tr>
                                                <td>
                                                    <label for="">Item</label>
                                                </td>
                                                <td> <label for="">Source</label>
                                                    <select class="form-select" name="" id="global_sourceslct">
                                                        <option value="">11</option>
                                                        <option value="">22</option>
                                                    </select>
                                                </td>
                                                <td> <label for="">Status</label>
                                                    <select class="form-select" name="" id="global_statuslct">
                                                        <option value="">11</option>
                                                        <option value="">22</option>
                                                    </select>
                                                </td>
                                                <td> <label for="">Quality</label>
                                                    <select class="form-select" name="" id="global_qualityslct">
                                                        <option value="">11</option>
                                                        <option value="">22</option>
                                                    </select>
                                                </td>
                                                <td class="smallColumn">unit</td>

                                                <td>Qty</td>
                                                <td>Price</td>
                                                <td>Total</td>
                                                <td>
                                                    <label for="">Store</label>
                                                    <select class="form-select" name="" id="global_storeslct">

                                                    </select>
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $item)
                                                <tr>
                                                    @if (count($item->Partitemstorelog) > 0)
                                                        <td>
                                                            <input type="hidden" name="partId[]" id="inputpartId"
                                                                class="form-control" value="{{ $item->part->id }}">
                                                            <input type="hidden" name="types[]" id="inputpartId"
                                                                class="form-control" value="1">
                                                                <input type="hidden" name="smallUnit[]"  class="form-control" value="{{ $item->part->small_unit }}">

                                                            <label class="text-nowrap"> {{ $item->part->name }}</label>
                                                            <input type="text" name="" readonly=""
                                                                class="form-control d-none"
                                                                value="{{ $item->part->name }}"
                                                                id="{{ $item->part->id }}">
                                                        </td>
                                                    @elseif(count($item->Kititemstorelog) > 0)
                                                        <td>
                                                            <input type="hidden" name="partId[]" id="inputpartId"
                                                                class="form-control" value="{{ $item->kit->id }}">
                                                            <input type="hidden" name="types[]" id="inputpartId"
                                                                class="form-control" value="6">
                                                            <label class="text-nowrap"> {{ $item->kit->name }}</label>
                                                            <input type="text" name="" readonly=""
                                                                class="form-control d-none"
                                                                value="{{ $item->kit->name }}"
                                                                id="{{ $item->kit->id }}">
                                                        </td>
                                                    @elseif(count($item->Wheelitemstorelog) > 0)
                                                        <td>

                                                            <input type="hidden" name="partId[]" id="inputpartId"
                                                                class="form-control" value="{{ $item->wheel->id }}">
                                                            <input type="hidden" name="types[]" id="inputpartId"
                                                                class="form-control" value="2">
                                                            <label class="text-nowrap"> {{ $item->wheel->name }}</label>
                                                            <input type="text" name="" readonly=""
                                                                class="form-control d-none"
                                                                value="{{ $item->wheel->name }}"
                                                                id="{{ $item->wheel->id }}">
                                                        </td>
                                                    @endif

                                                    <td>

                                                        <select class="form-select partSource" name="partSource[]"
                                                            id="" required="">

                                                        </select>
                                                        <input type="hidden" name=""
                                                            value="{{ $item->source->id }}">
                                                    </td>
                                                    <td>

                                                        <select class="form-select partStatus" name="partStatus[]"
                                                            id="" required="">

                                                        </select>
                                                        <input type="hidden" name=""
                                                            value="{{ $item->status->id }}">
                                                    </td>
                                                    <td>

                                                        <select class="form-select partQualty" name="partQualty[]"
                                                            id="" required="">

                                                        </select>
                                                        <input type="hidden" name=""
                                                            value="{{ $item->part_quality->id }}">
                                                    </td>
                                                    <td>
                                                        <select class="form-select partunit" name="unit[]"
                                                            id="" required="">
                                                            <?php $ratioamount =1;?>
                                                            @if($item->replyOrder->part_type_id == 1  )
                                                                @foreach ( $item->part->getsmallunit as $unit)
                                                                <option value="{{$unit->unit->id}}">{{$unit->unit->name}}</option>
                                                                @endforeach
                                                            <?php $ratioamount=getSmallUnit($item->replyOrder->unit_id,$item->part->small_unit);?>
                                                            @else
                                                            <option value="1">وحدة</option>
                                                            @endif

                                                        </select>
                                                        <input type="hidden" name=""
                                                            value="{{ $item->replyOrder->unit_id }}">
                                                    </td>
                                                    <td><input onkeyup="calc_table_price()" type="text"
                                                            name="amount[]"  class="form-control row_amount valid"
                                                            value="{{ $item->remain_amount / $ratioamount}}" id=""
                                                            required="" aria-required="true" aria-invalid="false">
                                                    </td>
                                                    <td><input type="text" name="price[]" onkeyup="calc_table_price()"
                                                            class="form-control row_price valid"
                                                            value="{{ $item->buy_price * $ratioamount }}" id="" required=""
                                                            aria-required="true" aria-invalid="false"></td>
                                                    <td><input type="text" name="tot[]"
                                                            class="form-control row_tot valid" readonly=""
                                                            value="{{ $item->buy_price * ($item->remain_amount /$ratioamount) }}"
                                                            id="" aria-invalid="false"></td>
                                                    <td>
                                                        <select class="form-select Stores valid"
                                                            onchange="changeStore(this);" name="Stores[]" id=""
                                                            required="" aria-required="true" aria-invalid="false">
                                                            @foreach ($stores as $store)
                                                                <option value="{{ $store->id }}">{{ $store->name }}
                                                                </option>
                                                            @endforeach

                                                        </select>
                                                        @if (count($item->Partitemstorelog) > 0)
                                                            <input type="hidden" name="" class="storeiddss"
                                                                value="{{ count($item->Partitemstorelog) > 0 ? $item->Partitemstorelog[0]->store_id : 0 }}">
                                                        @elseif (count($item->Kititemstorelog) > 0)
                                                            <input type="hidden" name="" class="storeiddss"
                                                                value="{{ count($item->Kititemstorelog) > 0 ? $item->Kititemstorelog[0]->store_id : 0 }}">
                                                        @elseif (count($item->Wheelitemstorelog) > 0)
                                                            <input type="hidden" name="" class="storeiddss"
                                                                value="{{ count($item->Wheelitemstorelog) > 0 ? $item->Wheelitemstorelog[0]->store_id : 0 }}">
                                                        @endif

                                                    </td>
                                                    <td class="w-sm">
                                                         @if (count($item->sections) > 0)
                                                            <input type="hidden"  name="Sections[]" class="sectioniddss "
                                                                value="{{ count($item->sections) > 0 ? $item->sections[0]->section_id : 0 }}">
                                                          @endif
                                                            <input type="text"  name="" class=" form-control" readonly=""
                                                                value="{{ count($item->sections) > 0 ? $item->sections[0]->store_structure->name : 0 }}">
                                                        

                                                    </td>
                                                    <td class="btn btn-ghost-danger"
                                                        onclick='invTotal -=$(this).closest("tr").find("input.row_tot").val(); $("#invAllTotal").val(invTotal);$("#invTotLbl1").val(invTotal);$("#invTotLbl").text(invTotal);$(this).closest("tr").remove();'>
                                                        Remove</td>
                                                </tr>
                                            @endforeach


                                        </tbody>

                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <input type="hidden" name="InvCoasting" id="InvCoasting"
                                        value="{{ $buyTrans->order_suppliers[0]->transport_coast + $buyTrans->order_suppliers[0]->insurant_coast + $buyTrans->order_suppliers[0]->customs_coast + $buyTrans->order_suppliers[0]->commotion_coast + $buyTrans->order_suppliers[0]->other_coast }}">
                                    <h5>تكلفة شراء البضاعة : <span
                                            id="InvCoastinglbl">{{ $buyTrans->order_suppliers[0]->transport_coast + $buyTrans->order_suppliers[0]->insurant_coast + $buyTrans->order_suppliers[0]->customs_coast + $buyTrans->order_suppliers[0]->commotion_coast + $buyTrans->order_suppliers[0]->other_coast }}</span>
                                    </h5>
                                    <label class="mt-2" for=""> مصروفات شحن ونقل المشتريات </label>
                                    <input onkeyup="calc_coast()" type="number" name="transCoast" id="transCoast"
                                        value="{{ $buyTrans->order_suppliers[0]->transport_coast }}"
                                        class="form-control mt-1">
                                    <label class="mt-2" for=""> مصروفات التأمين على البضاعة المشتراة </label>
                                    <input onkeyup="calc_coast()" type="number" name="insuranceCoast"
                                        id="insuranceCoast" value="{{ $buyTrans->order_suppliers[0]->insurant_coast }}"
                                        class="form-control mt-1">
                                    <label class="mt-2" for=""> الرسوم الجمركية على البضاعة المشتراة </label>
                                    <input onkeyup="calc_coast()" type="number" name="customs" id="customs"
                                        value="{{ $buyTrans->order_suppliers[0]->customs_coast }}"
                                        class="form-control mt-1">
                                    <label class="mt-2" for=""> عمولة وكلاء الشراء </label>
                                    <input onkeyup="calc_coast()" type="number" name="commition" id="commition"
                                        value="{{ $buyTrans->order_suppliers[0]->commotion_coast }}"
                                        class="form-control mt-1">
                                    <label class="mt-2" for=""> مصروفات اخرى </label>
                                    <input onkeyup="calc_coast()" type="number" name="otherCoast" id="otherCoast"
                                        value="{{ $buyTrans->order_suppliers[0]->other_coast }}"
                                        class="form-control mt-1">

                                </div>

                                <div class="col-4"></div>
                                <div class="col-4 text-end">
                                    {{-- <button type="button" class="btn btn-success"> + Excel upload</button> --}}

                                    <div class="form-check form-check-inline m-3">
                                        <input class="form-check-input" data-val="0" type="radio" name="taxInvolved"
                                            value="0" id="inlineRadio1"
                                            @if ($buyTrans->order_suppliers[0]->taxInvolved_flag  == 0) checked @endif>
                                        <label class="form-check-label" for="inlineRadio1">شامل ضريبة القيمة
                                            المضافة</label>
                                    </div>
                                    <div class="form-check form-check-inline m-3">
                                        <input class="form-check-input" data-val="14" type="radio" name="taxInvolved"
                                            value="1" id="inlineRadio2"
                                            @if ($buyTrans->order_suppliers[0]->taxInvolved_flag == 1) checked @endif>
                                        <label class="form-check-label" for="inlineRadio2">غير شامل </label>
                                    </div>
                                    <br />
                                    <div class="form-check form-check-inline m-3">
                                        <input class="form-check-input" data-val="-1" type="radio"
                                            name="taxkasmInvolved" value="1" id="inlineRadio11" @if($buyTrans->order_suppliers[0]->taxkasmInvolved_flag == 1) checked @endif>
                                        <label class="form-check-label" for="inlineRadio11">ضريبة خصم أرباح تجارية وصناعية
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline m-3">
                                        <input class="form-check-input" data-val="0" type="radio"
                                            name="taxkasmInvolved" value="0"  id="inlineRadio21"
                                            @if ($buyTrans->order_suppliers[0]->taxkasmInvolved_flag == 0) checked @endif>
                                        <label class="form-check-label" for="inlineRadio21">لا </label>
                                    </div>


                                    <input type="hidden" name="invTotLbl" id="invTotLbl1" value="0">
                                    <h5>Items Total : <span id="invTotLbl"> 0000.0000 </span></h5>
                                    <label class="mt-2" for=""> % Tax </label>
                                    <input type="text" name="invTax" id="invTax"
                                        value="{{ $buyTrans->order_suppliers[0]->tax }}" class="form-control mt-1">
                                    <label class="mt-2" for="">Total </label>
                                    <input type="text" readonly name="invAllTotal" id="invAllTotal" value="0"
                                        class="form-control mt-1">
                                    <label class="mt-2" for="">Paied</label>
                                    <input type="text" name="invPaied" id="invPaied"
                                        value="{{ $buyTrans->order_suppliers[0]->paied }}" class="form-control mt-1"
                                        required>
                                    <label class="mt-2" for="">Payment Method</label>
                                    <select class="form-select mt-1" name="payment" id="paymentslect">
                                        @if ($buyTrans->order_suppliers[0]->payment == 0)
                                            <option value="0" selected>كاش</option>
                                            <option value="1">تحويل بنكي</option>
                                            <option value="2">أجل</option>
                                        @elseif ($buyTrans->order_suppliers[0]->payment == 1)
                                            <option value="0">كاش</option>
                                            <option value="1" selected>تحويل بنكي</option>
                                            <option value="2">أجل</option>
                                        @else
                                            <option value="0">كاش</option>
                                            <option value="1">تحويل بنكي</option>
                                            <option value="2" selected>أجل</option>
                                        @endif
                                    </select>

                                    @if ($buyTrans->order_suppliers[0]->total_price > $buyTrans->order_suppliers[0]->paid)
                                        <div  id="dueDiv">
                                            <label class="mt-2" for="">Due Date</label>
                                            <input type="date" name="dueDate" class="form-control"
                                                value="{{ $buyTrans->order_suppliers[0]->due_date }}" id="dueDate1">
                                        </div>
                                    @else
                                        <div style="display:none" id="dueDiv">
                                            <label class="mt-2" for="">Due Date</label>
                                            <input type="date" name="dueDate" class="form-control"
                                                value="{{ $buyTrans->order_suppliers[0]->due_date }}" id="dueDate1">
                                        </div>
                                    @endif
                                </div>
                            </div>


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
        // $("#invDate").val(new Date().toJSON().slice(0, 10)).trigger('chage');
        $("#partSlct").select2({

            ajax: {
                url: "/partsSearch",
                //   dataType: 'json',
                async: false,
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
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
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            placeholder: 'Search for a repository',
            minimumInputLength: 1,
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
                "<div class='select2-result-repository__avatar d-none'><img src='" + repo.name + "' /></div>" +
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
            return repo.name || repo.text;
        }

        var partStatusOption = "";
        var partSourceOption = "";
        var partQualtyOption = "";
        var storeOption = "";
        var storeSectionOption = "";
        $.ajax({
            type: "get",
            url: "/source",
            async : false,
            success: function(response) {
                $("#global_sourceslct").empty();
                $("#global_sourceslct").append(`<option value="0">Select Source</option>`);
                partSourceOption += `<option value="">Select Source</option>`;
                response.forEach(element => {
                    partSourceOption += `<option value="${element.id}">${element.name_arabic}</option>`;
                    $("#global_sourceslct").append(
                        `<option value="${element.id}">${element.name_arabic}</option>`);
                });
            }
        });

        $.ajax({
            type: "get",
            url: "/status",
            async : false,
            success: function(response) {
                $("#global_statuslct").empty();
                $("#global_statuslct").append(`<option value="0">Select Status</option>`);
                partStatusOption += `<option value="">Select Status</option>`;
                response.forEach(element => {
                    $("#global_statuslct").append(
                        `<option value="${element.id}">${element.name}</option>`);
                    partStatusOption += `<option value="${element.id}">${element.name}</option>`;
                });
            }
        });

        $.ajax({
            type: "get",
            url: "/quality",
            async : false,
            success: function(response) {
                $("#global_qualityslct").empty();
                $("#global_qualityslct").append(`<option value="0">Select Quality</option>`);
                partQualtyOption += `<option value="">Select Quality</option>`;
                response.forEach(element => {
                    $("#global_qualityslct").append(
                        `<option value="${element.id}">${element.name}</option>`);
                    partQualtyOption += `<option value="${element.id}">${element.name}</option>`;
                });
            }
        });
        $.ajax({
            type: "get",
            url: "/GetAllstores",
            async : false,
            success: function(response) {
                $("#global_storeslct").empty();
                $("#global_storeslct").append(`<option value="">Select Store</option>`);
                storeOption += `<option selected disabled value="">Select Store</option>`;
                response.forEach(element => {
                    // $(".Stores").append(`<option value="${element.id}">${element.name}</option>`);
                    $("#global_storeslct").append(
                        `<option value="${element.id}">${element.name}</option>`);
                    storeOption += `<option value="${element.id}">${element.name}</option>`;
                });
            }
        });

        $.ajax({
            type: "get",
            async: false,
            url: "/Selectindex", // Supplier
            success: function(response) {
                $(".supp").empty();
                $(".supp").append(`<option disabled selected value="">Select Supplier</option>`);


                response.forEach(element => {
                    $(".supp").append(
                        `<option value="${element.id}">${element.name} - ${element.tel01} - ${element.tel02}</option>`
                    );

                });
            },
            complete: function() {
                $(".supp").val($("#supids").val()).trigger('change');
            }
        });



        $.ajax({
            type: "get",
            url: "/GetAllCurrency",
            success: function(response) {
                $("#currencySlct").empty();
                $("#currencySlct").append(`<option value="" selected disabled>Select Currency</option>`);

                response.forEach(element => {
                    $("#currencySlct").append(`<option value="${element.id}">${element.name}</option>`);

                });
            },
            complete: function() {
                $("#currencySlct").val($("#curids").val()).trigger('change');
            }
        });
        $("#partSlct").on('change', function(e) {
            var partunitx='';

            var selectedText = $("#select2-partSlct-container").text();
            var selectedType = $(this).select2('data')[0].type_id
            var selectedPartID = $(this).val();
            var smallUnit = 1;
            partunitx +=`<option selected disabled value="">Select Unit</option>`;
            if(($(this).select2('data').length > 0)){
                if($(this).select2('data')[0].getsmallunit.length > 0){
                    smallUnit = ($(this).select2('data').length > 0) ? $(this).select2('data')[0].small_unit : 0;
                    $(this).select2('data')[0].getsmallunit.forEach(unit => {
                            partunitx +=`<option value="${unit.unit.id}">${unit.unit.name}</option>`;
                    });

                }else{
                    partunitx +=`<option value="1">وحدة</option>`;
                }
            }else{

            }
            $('#newinvtbl').prepend(`<tr>
        <td>
            <input type="hidden" name="partId[]" id="inputpartId" class="form-control" value="${selectedPartID}">
            <input type="hidden" name="types[]" id="inputpartId" class="form-control" value="${selectedType}">
            <label class="text-nowrap">${selectedText}</label>
            <input type="hidden" name="smallUnit[]"  class="form-control" value="${smallUnit}">

            <input type="text" name="" readonly="" class="form-control d-none" value="${selectedText}" id="${selectedPartID}">
        </td>
        <td>
            <select class="form-select partSource" name="partSource[]" id="" required>
                ${partSourceOption}
            </select>

        </td>
        <td>
            <select class="form-select partStatus" name="partStatus[]" id="" required>
            ${partStatusOption}
            </select>
        </td>
        <td>
            <select class="form-select partQualty" name="partQualty[]" id="" required>
            ${partQualtyOption}
            </select>
        </td>
         <td>
            <select class="form-select  text-left partunit" name="unit[]" id="" required>
            ${partunitx}
            </select>
        </td>
        <td><input onkeyup="calc_table_price()" type="text" name="amount[]"  class="form-control row_amount" value=""  id="" required></td>
        <td><input type="text" name="price[]" onkeyup="calc_table_price()" class="form-control row_price" value="" id="" required></td>
        <td><input type="text" name="tot[]" class="form-control row_tot" readonly value="" id="" ></td>
        <td>
            <select class="form-select Stores" onchange="changeStore(this);" name="Stores[]" id="" required>
                ${storeOption}
            </select>
        </td>
        <td class="w-sm">
            <select class="form-select Sections" name="Sections[]" id="" required>

            </select>
        </td>
        <td class="btn btn-ghost-danger" onclick='invTotal -=$(this).closest("tr").find("input.row_tot").val(); $("#invAllTotal").val(invTotal);$("#invTotLbl1").val(invTotal);$("#invTotLbl").text(invTotal);$(this).closest("tr").remove();' >Remove</td>
        </tr>`)
            var lastrow = $("#newinvtbl tr").last();
            if ($("#global_sourceslct").val() > 0) {
                lastrow.find('select.partSource').val($("#global_sourceslct").val())
            }
            if ($("#global_statuslct").val() > 0) {
                lastrow.find('select.partStatus').val($("#global_statuslct").val())
            }
            if ($("#global_qualityslct").val() > 0) {
                lastrow.find('select.partQualty').val($("#global_qualityslct").val())
            }

            if ($("#global_storeslct").val() > 0) {
                lastrow.find('select.Stores').val($("#global_storeslct").val()).trigger('change');
            }

            $(this).val(null);

        })
        function validateIntegerInput(event) {
                const input = event.target;
                const value = input.value;
        
                // Remove any non-digit characters
                const sanitizedValue = value.replace(/[^0-9]/g, '');
        
                // Update the input value if it has been changed
                if (value !== sanitizedValue) {
                    input.value = sanitizedValue;
                }
            }
        function calc_table_price() {

            var tableRow = $("#newinvtbl tbody tr");
            invTotal = 0;
            tableRow.each(function(index, tr) {
                var amount = $(tr).find("input.row_amount").val();
                var price = $(tr).find("input.row_price").val();
                amount = parseFloat(amount);
                price = parseFloat(price);
                var total = amount * price;
                invTotal += total;
                $(tr).find("input.row_tot").val(total)
            })

            $("#invTotLbl").text(invTotal);
            $("#invTotLbl1").val(invTotal);
            $("#invAllTotal").val(invTotal)

        }

        function calc_coast() {
            var transCoast = parseFloat($("#transCoast").val());
            var insuranceCoast = parseFloat($("#insuranceCoast").val());
            var customs = parseFloat($("#customs").val());
            var commition = parseFloat($("#commition").val());
            var otherCoast = parseFloat($("#otherCoast").val());
            var Tot = (transCoast ? transCoast : 0) + (insuranceCoast ? insuranceCoast : 0) + (customs ? customs : 0) + (
                commition ? commition : 0) + (otherCoast ? otherCoast : 0);
            $('#InvCoastinglbl').text(Tot);
            $('#InvCoasting').val(Tot);

        }
        $("#global_sourceslct").change(function(e) {
            e.preventDefault();
            var selectedSource = $(this).val();
            var allpartSource = $("#newinvtbl tbody").find(".partSource");

            for (let i = 0; i < allpartSource.length; i++) {
                const element = allpartSource[i];
                $(element).val(selectedSource).trigger('change');

            }

        });

        $("#global_statuslct").change(function(e) {
            e.preventDefault();
            var selectedSource = $(this).val();
            var allpartSource = $("#newinvtbl tbody").find(".partStatus");

            for (let i = 0; i < allpartSource.length; i++) {
                const element = allpartSource[i];
                $(element).val(selectedSource).trigger('change');

            }

        });

        $("#global_qualityslct").change(function(e) {
            e.preventDefault();
            var selectedSource = $(this).val();
            var allpartSource = $("#newinvtbl tbody").find(".partQualty");

            for (let i = 0; i < allpartSource.length; i++) {
                const element = allpartSource[i];
                $(element).val(selectedSource).trigger('change');

            }

        });

        $("#global_storeslct").change(function(e) {
            e.preventDefault();
            var selectedStore = $(this).val();
            var allpartStore = $("#newinvtbl tbody").find(".Stores");

            for (let i = 0; i < allpartStore.length; i++) {
                const element = allpartStore[i];
                $(element).val(selectedStore).trigger('change');

            }




        });

        $("#invTax").keyup(function(e) {
            var tax = $(this).val();
            var invTot = $("#invTotLbl1").val();
            var Tot = $("#invAllTotal").val();
            var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100).toFixed(2);

            var kasmTax = $('input[type=radio][name=taxkasmInvolved]:checked').attr('data-val');
            var kasminvtotaltax = (parseFloat(invTot) * parseFloat(kasmTax) / 100).toFixed(2);

            $("#invAllTotal").val((parseFloat(invtotaltax) + parseFloat(invTot) + parseFloat(kasminvtotaltax))
                .toFixed(1));
            // $("#invTotLbl").text(invtotaltax);

        });

        $('input[type=radio][name=taxInvolved]').change(function(e) {
            // var tax = $(this).attr('data-val');
            // var invTot = $("#invTotLbl").text();
            // var Tot = $("#invAllTotal").val();
            // var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100 ).toFixed(2);
            // $("#invAllTotal").val((parseFloat(invtotaltax)+parseFloat(Tot)).toFixed(1));
            // $("#invTotLbl").text(invtotaltax);

            var tax = $('#invTax').val();
            var invTot = $("#invTotLbl1").val();
            var Tot = $("#invAllTotal").val();
            var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100).toFixed(2);
            var kasmTax2 = $('input[type=radio][name=taxkasmInvolved]:checked').attr('data-val');
            var kasminvtotaltax2 = (parseFloat(invTot) * parseFloat(kasmTax2) / 100).toFixed(2);
            var kasmTax = $(this).attr('data-val');
            var kasminvtotaltax = (parseFloat(invTot) * parseFloat(kasmTax) / 100).toFixed(2);

            $("#invAllTotal").val((parseFloat(invtotaltax) + parseFloat(invTot) + parseFloat(kasminvtotaltax) +
                parseFloat(kasminvtotaltax2)).toFixed(1));

        });

        $('input[type=radio][name=taxkasmInvolved]').change(function(e) {
            // var tax = $(this).attr('data-val');
            // var invTot = $("#invTotLbl").text();
            // var Tot = $("#invAllTotal").val();
            // var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100 ).toFixed(2);
            // $("#invAllTotal").val((parseFloat(invtotaltax)+parseFloat(Tot)).toFixed(1));
            // $("#invTotLbl").text(invtotaltax);

            var tax = $('#invTax').val();
            var invTot = $("#invTotLbl1").val();
            var Tot = $("#invAllTotal").val();
            var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100).toFixed(2);
            var kasmTax2 = $('input[type=radio][name=taxInvolved]:checked').attr('data-val');
            var kasminvtotaltax2 = (parseFloat(invTot) * parseFloat(kasmTax2) / 100).toFixed(2);
            var kasmTax = $(this).attr('data-val');
            var kasminvtotaltax = (parseFloat(invTot) * parseFloat(kasmTax) / 100).toFixed(2);

            $("#invAllTotal").val((parseFloat(invtotaltax) + parseFloat(invTot) + parseFloat(kasminvtotaltax) +
                parseFloat(kasminvtotaltax2)).toFixed(1));

        });

        function changeStore(el) {
            var storeid = $(el).val();


            // $.ajax({
            //     type: "get",
            //     url: "/GetSections/" + storeid,
            //     success: function(response) {
            //         if (response.length > 0) {
            //             storeSectionOption += `<option selected disabled value="">Select Section</option>`;
            //             $(el).closest('tr').find('.Sections').empty();
            //             response.forEach(element => {
            //                 $(el).closest('tr').find('.Sections').append(
            //                     `<option value="${element.id}">${element.name}</option>`);
            //                 storeSectionOption +=
            //                     `<option value="${element.id}">${element.name}</option>`;
                            
            //             });
                        
                        
            //         } else {
            //             $(el).closest('tr').find('.Sections').empty();
            //             $(el).closest('tr').find('.Sections').append(`<option value="0">No Sections</option>`);
            //         }
            //          $(el).closest('tr').find('.Sections').select2();

            //     }, complete: function(response) {
                   
            //         // setTimeout(function() {
            //       $(el).closest('tr').find('.Sections').val($(el).closest('tr').find('.Sections').closest('td').find('.sectioniddss').val()).trigger('change');
            //     // }, 10);
            //     }
                
                
                
            // });
            
            
            
                        
        }

        $("#invPaied").keyup(function(e) {
            var paied = parseFloat($(this).val());
            var total = parseFloat($("#invAllTotal").val());

            if (paied < total) {
                $("#dueDiv").show();
                $("#dueDate1").attr('required', true);
            } else if (paied = total) {
                $("#dueDiv").hide();
                $("#dueDate1").attr('required', false);
            } else {
                $(this).val(0)
            }
        });

        // Example starter JavaScript for disabling form submissions if there are invalid fields
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

        $("#store_idd").change(function() {
            var safeId = $(this).val();
            var safetype = $("#store_idd option:selected ").attr('type-name');
            $.ajax({
                type: "get",
                url: "/getRassed/" + safeId + '/' + safetype,
                success: function(response) {
                    console.log(response);
                    if (response == "") {
                        $("#Safetotal").text(0)
                    } else {
                        $("#Safetotal").text(response)
                    }
                }
            });
        })

        $("#invPaied").keyup(function(e) {
            var paied = parseFloat($(this).val());
            var total = parseFloat($("#invAllTotal").val());

            if (paied < total) {
                $("#dueDiv").show();
                $("#dueDate1").attr("required", true);
                $("#paymentslect").val(2);
                //$("#dueDate1").prop('required',true);
            } else if (paied = total) {
                $("#dueDiv").hide();
                $("#dueDate1").attr("required", false);
                if ($("#store_idd option:selected ").attr('type-name') == "bank") {
                    $("#paymentslect").val(1);
                } else {
                    $("#paymentslect").val(0);
                }
                //$("#dueDate1").prop('required',false);
            } else {
                $(this).val(0)
            }
        });


        $(window).on('load', function() {
            $("#newinvtbl tbody tr").each(function() {
                // alert($(this).find(".partSource").next('input').val())
                $(this).find(".partSource").append(partSourceOption);
                $(this).find(".partSource").val($(this).find(".partSource").next('input').val()).trigger(
                    'change');
                $(this).find(".partStatus").append(partStatusOption);
                $(this).find(".partStatus").val($(this).find(".partStatus").next('input').val()).trigger(
                    'change');
                $(this).find(".partQualty").append(partQualtyOption);
                $(this).find(".partQualty").val($(this).find(".partQualty").next('input').val()).trigger(
                    'change');
                    $(this).find(".partunit").append(partunitx);
                $(this).find(".partunit").val($(this).find(".partunit").next('input').val()).trigger(
                    'change');
                $(this).find(".Stores").val($(this).find(".Stores").next('input').val()).trigger('change');


                
            })

            calc_table_price()
            var x = $("#invTax").val();
            $("#invTax").val(x).trigger('change');
            $("#invTax").keyup();
            
             
        });
    </script>


@endsection
