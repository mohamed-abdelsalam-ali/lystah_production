@extends('layouts.master')
@section('css')
    <style>
        table {
            font-family: 'Droid Arabic Naskh', serif;
        }

        .modal-dialog {
            max-width: 1000px !important;
        }

        .smallColumn {
            width: 7% !important;
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
                        <h4 class="mb-sm-0">Buy Invoice Direct To Store</h4>

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
                            <form method="POST" class="needs-validation" novalidate id="addInvFrm"
                                action="{{ url('storeManage2') }}" enctype="multipart/form-data">
                                @csrf



                                <div class="row">
                                    <div class="col-4">
                                        {{-- <input class="form-control" type="hidden" name="store_id" value='8'> --}}

                                        <label for="">Date</label>
                                        <input class="form-control" type="date" name="invDate" id="invDate" required>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="">Company</label>
                                        <label class="form-control" name="invCompany" for="">ALAMAL</label>
                                    </div>
                                    <div class="col-4">
                                        <label for="">اختر حساب الصرف</label>> <span
                                            class="float-end text-bg-danger">المتاح ج.م<span id="Safetotal">0</span></span>
                                        <input type="hidden" id="SafetotalV" name="Safetotal" value="0">
                                        <select name="store_id" id="store_idd" class="form-select " required>

                                            <option class="text-center" value="" selected disabled>اختر اسم الحساب
                                            </option>
                                            @foreach ($bank_types as $bank)
                                                <option class="text-center" selected type-name="bank"
                                                    value="{{ $bank->accountant_number }}">{{ $bank->bank_name }} </option>
                                            @endforeach


                                            @foreach ($store_safe as $safe)
                                                <option class="text-center" type-name="store"
                                                    value="{{ $safe->safe_accountant_number }}">{{ $safe->name }}
                                                </option>
                                            @endforeach



                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <label for="">Supplier</label>

                                        <select name="invSupplier" id="invSuppliernew" class="form-select supp" required>


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
                                        <select name="slected_type" id="slected_type" class="form-select " >

                                            <option class="text-center" value="" selected> الكل </option>
                                            <option class="text-center" value="part">قطع غيار </option>
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

                                            <thead style="border-color: #075f8b;border-bottom-width: 3px;">
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
                                                        <select class="form-select  text-left" name=""
                                                            id="global_qualityslct">
                                                            <option value="">11</option>
                                                            <option value="">22</option>
                                                        </select>
                                                    </td>
                                                    <td class="smallColumn">unit</td>

                                                    <td class="smallColumn">Qty</td>
                                                    <td class="smallColumn">Price</td>
                                                    <td class="smallColumn">Total</td>
                                                    <td>
                                                        <label for="">Store</label>
                                                        <select class="form-select" name="" id="global_storeslct">

                                                        </select>
                                                    </td>
                                                    <td>section</td>
                                                    <td>Action</td>

                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <!--<div class="col-4">-->
                                    <!--    <input type="hidden" name="InvCoasting" id="InvCoasting" value="0">-->
                                    <!--    <h5>تكلفة شراء البضاعة : <span id="InvCoastinglbl"> 0</span></h5>-->
                                    <!--    <label class="mt-2" for=""> مصروفات شحن ونقل المشتريات </label>-->
                                    <!--    <input onkeyup="calc_coast()" type="number" name="transCoast" id="transCoast"-->
                                    <!--        value="0" class="form-control mt-1">-->
                                    <!--    <label class="mt-2" for=""> مصروفات التأمين على البضاعة المشتراة-->
                                    <!--    </label>-->
                                    <!--    <input onkeyup="calc_coast()" type="number" name="insuranceCoast"-->
                                    <!--        id="insuranceCoast" value="0" class="form-control mt-1">-->
                                    <!--    <label class="mt-2" for=""> الرسوم الجمركية على البضاعة المشتراة-->
                                    <!--    </label>-->
                                    <!--    <input onkeyup="calc_coast()" type="number" name="customs" id="customs"-->
                                    <!--        value="0" class="form-control mt-1">-->
                                    <!--    <label class="mt-2" for=""> عمولة وكلاء الشراء </label>-->
                                    <!--    <input onkeyup="calc_coast()" type="number" name="commition" id="commition"-->
                                    <!--        value="0" class="form-control mt-1">-->
                                    <!--    <label class="mt-2" for=""> مصروفات اخرى </label>-->
                                    <!--    <input onkeyup="calc_coast()" type="number" name="otherCoast" id="otherCoast"-->
                                    <!--        value="0" class="form-control mt-1">-->

                                    <!--</div>-->

                                    <!--<div class="col-4"></div>-->
                                    <!--<div class="col-4 text-end">-->
                                    <!--    {{-- <button type="button" class="btn btn-success"> + Excel upload</button> --}}-->


                                    <!--    <div class="form-check form-check-inline m-3">-->
                                    <!--        <input class="form-check-input" data-val="-1" type="radio"-->
                                    <!--            name="notaxes" value="1" id="inlineRadio11" required>-->
                                    <!--        <label class="form-check-label" for="inlineRadio11">بدون ضريبة</label>-->
                                    <!--    </div>-->
                                    <!--    <div class="form-check form-check-inline m-3">-->
                                    <!--        <input class="form-check-input" data-val="-1" type="radio"-->
                                    <!--            name="notaxes" value="0" id="inlineRadio121" required>-->
                                    <!--        <label class="form-check-label" for="inlineRadio11"> ضريبة</label>-->
                                    <!--    </div>-->
                                    <!--    <br>-->
                                    <!--    <div class="taxContainer border border-success border-end-groove" style="display: none">-->
                                    <!--        <div class="form-check form-check-inline m-3">-->
                                    <!--            <input class="form-check-input" data-val="0" checked type="radio"-->
                                    <!--                name="taxInvolved" value="0" id="inlineRadio1" value="option1">-->
                                    <!--            <label class="form-check-label" for="inlineRadio1">شامل ضريبة القيمة-->
                                    <!--                المضافة</label>-->
                                    <!--        </div>-->
                                    <!--        <div class="form-check form-check-inline m-3">-->
                                    <!--            <input class="form-check-input" data-val="14" type="radio"-->
                                    <!--                name="taxInvolved" value="1" id="inlineRadio2" value="option2">-->
                                    <!--            <label class="form-check-label" for="inlineRadio2">غير شامل </label>-->
                                    <!--        </div>-->
                                    <!--        <br />-->
                                    <!--        <div class="form-check form-check-inline m-3">-->
                                    <!--            <input class="form-check-input" data-val="-1" type="radio"-->
                                    <!--                name="taxkasmInvolved" value="1" id="inlineRadio11"-->
                                    <!--                value="option1">-->
                                    <!--            <label class="form-check-label" for="inlineRadio11">ضريبة خصم أرباح تجارية-->
                                    <!--                وصناعية </label>-->
                                    <!--        </div>-->
                                    <!--        <div class="form-check form-check-inline m-3">-->
                                    <!--            <input class="form-check-input" data-val="0" type="radio"-->
                                    <!--                name="taxkasmInvolved" value="0" checked id="inlineRadio21"-->
                                    <!--                value="option2">-->
                                    <!--            <label class="form-check-label" for="inlineRadio21">لا </label>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                        
                                    <!--    <input type="hidden" name="invTotLbl" id="invTotLbl1" value="0">-->
                                    <!--    <h5>Items Total : <span id="invTotLbl"> 0000.0000 </span></h5>-->
                                    <!--    <label class="mt-2" for=""> % Tax </label>-->
                                    <!--    <input type="text" name="invTax" id="invTax" value="0"-->
                                    <!--        class="form-control mt-1 " style="display:none">-->
                                    <!--    <label class="mt-2" for="">Total </label>-->
                                    <!--    <input type="text" readonly name="invAllTotal" id="invAllTotal"-->
                                    <!--        value="0" class="form-control mt-1">-->
                                    <!--    <label class="mt-2" for="">Paied</label>-->
                                    <!--    <input type="text" name="invPaied" id="invPaied" value=""-->
                                    <!--        class="form-control mt-1" required>-->
                                    <!--    <label class="mt-2" for="">Payment Method</label>-->
                                    <!--    <select class="form-select mt-1" name="payment" id="paymentslect">-->
                                    <!--        <option selected value="0">كاش</option>-->
                                    <!--        <option value="1">تحويل بنكي</option>-->
                                    <!--        <option value="2"> أجل</option>-->
                                    <!--    </select>-->
                                    <!--    <div style="display:none" id="dueDiv">-->
                                    <!--        <label class="mt-2" for="">Due Date</label>-->
                                    <!--        <input type="date" name="dueDate" class="form-control"-->
                                    <!--            value="<?php echo date('Y-m-d'); ?>" id="dueDate1">-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    
                                    <input type="hidden" name="InvCoasting" id="InvCoasting" value="0">
                                    <h5>تكلفة شراء البضاعة : <span id="InvCoastinglbl"> 0</span></h5>

                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="row">
                                                <div class="col-lg-4">

                                                    <label class="mt-2" for=""> مصروفات شحن ونقل المشتريات
                                                    </label>
                                                    <input onkeyup="calc_coast()" type="number" name="transCoast"
                                                        id="transCoast" value="0" class="form-control mt-1">
                                                </div>
                                                <div class="col-lg-4">
                                                    <label class="mt-2" for=""> مصروفات التأمين على البضاعة
                                                        المشتراة
                                                    </label>
                                                    <input onkeyup="calc_coast()" type="number" name="insuranceCoast"
                                                        id="insuranceCoast" value="0" class="form-control mt-1">
                                                </div>
                                                <div class="col-lg-4">
                                                    <label class="mt-2" for=""> الرسوم الجمركية على البضاعة
                                                        المشتراة
                                                    </label>
                                                    <input onkeyup="calc_coast()" type="number" name="customs"
                                                        id="customs" value="0" class="form-control mt-1">
                                                </div>
                                                <div class="col-lg-4">
                                                    <label class="mt-2" for=""> عمولة وكلاء الشراء </label>
                                                    <input onkeyup="calc_coast()" type="number" name="commition"
                                                        id="commition" value="0" class="form-control mt-1">
                                                </div>
                                                <div class="col-lg-4">
                                                    <label class="mt-2" for=""> مصروفات اخرى </label>
                                                    <input onkeyup="calc_coast()" type="number" name="otherCoast"
                                                        id="otherCoast" value="0" class="form-control mt-1">
                                                </div>

                                                <div class="col-lg-4">
                                                    <label class="mt-2" for=""> مصروفات التسليم </label>
                                                    <input onkeyup="calc_coast()" type="number" name="taslem_coast"
                                                        id="taslem_coast" value="0" class="form-control mt-1">
                                                </div>
                                                <div class="col-lg-4">
                                                    <label class="mt-2" for=""> النولون البحري </label>
                                                    <input onkeyup="calc_coast()" type="number" name="nolon_coast"
                                                        id="nolon_coast" value="0" class="form-control mt-1">
                                                </div>
                                                <div class="col-lg-4">
                                                    <label class="mt-2" for=""> غرامات وارضيات</label>
                                                    <input onkeyup="calc_coast()" type="number" name="ardya_coast"
                                                        id="ardya_coast" value="0" class="form-control mt-1">
                                                </div>
                                               
                                                <div class="col-lg-4">
                                                    <label class="mt-2" for="">نقل داخلى </label>
                                                    <input onkeyup="calc_coast()" type="number" name="in_transport_coast"
                                                        id="in_transport_coast" value="0" class="form-control mt-1">
                                                </div>
                                                <div class="col-lg-4">
                                                    <label class="mt-2" for=""> اتعاب التخليص</label>
                                                    <input onkeyup="calc_coast()" type="number" name="takhles_coast"
                                                        id="takhles_coast" value="0" class="form-control mt-1">
                                                </div>
                                                <div class="col-lg-4">
                                                    <label class="mt-2" for=""> عمولة تحويل البنك</label>
                                                    <input onkeyup="calc_coast()" type="number" name="bank_coast"
                                                        id="bank_coast" value="0" class="form-control mt-1">
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            {{-- <button type="button" class="btn btn-success"> + Excel upload</button> --}}


                                            <div class="form-check form-check-inline m-3">
                                                <input class="form-check-input" data-val="-1" type="radio"
                                                    name="notaxes" value="1" id="inlineRadio11" required>
                                                <label class="form-check-label" for="inlineRadio11">بدون ضريبة</label>
                                            </div>
                                            <div class="form-check form-check-inline m-3">
                                                <input class="form-check-input" data-val="-1" type="radio"
                                                    name="notaxes" value="0" id="inlineRadio121" required>
                                                <label class="form-check-label" for="inlineRadio11"> ضريبة</label>
                                            </div>
                                            <br>
                                            <div class="taxContainer border border-success border-end-groove"
                                                style="display: none">
                                                <div class="form-check form-check-inline m-3">
                                                    <input class="form-check-input" data-val="0" checked type="radio"
                                                        name="taxInvolved" value="0" id="inlineRadio1"
                                                        value="option1">
                                                    <label class="form-check-label" for="inlineRadio1">شامل ضريبة القيمة
                                                        المضافة</label>
                                                </div>
                                                <div class="form-check form-check-inline m-3">
                                                    <input class="form-check-input" data-val="14" type="radio"
                                                        name="taxInvolved" value="1" id="inlineRadio2"
                                                        value="option2">
                                                    <label class="form-check-label" for="inlineRadio2">غير شامل </label>
                                                </div>
                                                <br />
                                                <div class="form-check form-check-inline m-3">
                                                    <input class="form-check-input" data-val="-1" type="radio"
                                                        name="taxkasmInvolved" value="1" id="inlineRadio11"
                                                        value="option1">
                                                    <label class="form-check-label" for="inlineRadio11">ضريبة خصم أرباح
                                                        تجارية
                                                        وصناعية </label>
                                                </div>
                                                <div class="form-check form-check-inline m-3">
                                                    <input class="form-check-input" data-val="0" type="radio"
                                                        name="taxkasmInvolved" value="0" checked id="inlineRadio21"
                                                        value="option2">
                                                    <label class="form-check-label" for="inlineRadio21">لا </label>
                                                </div>
                                            </div>

                                            <input type="hidden" name="invTotLbl" id="invTotLbl1" value="0">
                                            <h5>Items Total : <span id="invTotLbl"> 0000.0000 </span></h5>
                                            <label class="mt-2" for=""> % Tax </label>
                                            <input type="text" name="invTax" id="invTax" value="0"
                                                class="form-control mt-1 " style="display:none">
                                            <label class="mt-2" for="">Total </label>
                                            <input type="text" readonly name="invAllTotal" id="invAllTotal"
                                                value="0" class="form-control mt-1">
                                            <label class="mt-2" for="">Paied</label>
                                            <input type="text" name="invPaied" id="invPaied" value=""
                                                class="form-control mt-1" required>
                                            <label class="mt-2" for="">Payment Method</label>
                                            <select class="form-select mt-1" name="payment" id="paymentslect">
                                                <option selected value="0">كاش</option>
                                                <option value="1">تحويل بنكي</option>
                                                <option value="2"> أجل</option>
                                            </select>
                                            <div style="display:none" id="dueDiv">
                                                <label class="mt-2" for="">Due Date</label>
                                                <input type="date" name="dueDate" class="form-control"
                                                    value="<?php echo date('Y-m-d'); ?>" id="dueDate1">
                                            </div>
                                        </div>
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
        $(document).ready(function () {
            // Attach a change event listener to all radio buttons with the name "notaxes"
            $('input[name="notaxes"]').on('change', function () {
                // Check the value of the selected radio button
                if ($(this).val() === '0') {
                    $('.taxContainer').show(); // Show the div
                    $('#invTax').hide(); // Hide the div

                } else {
                    $('.taxContainer').hide(); // Hide the div
                    $('#invTax').show(); // Hide the div

                    $('input[type=radio][name=taxInvolved]').each(function () {
                        if ($(this).attr('data-val') === '0') {
                            $(this).prop('checked', true); // Set as checked
                        } else {
                            $(this).prop('checked', false); // Ensure others are unchecked
                        }
                    });

                    $('input[type=radio][name=taxkasmInvolved]').each(function () {
                        if ($(this).attr('data-val') === '0') {
                            $(this).prop('checked', true); // Set as checked
                        } else {
                            $(this).prop('checked', false); // Ensure others are unchecked
                        }
                    });

                }
            });
        });
        $("#invDate").val(new Date().toJSON().slice(0, 10)).trigger('chage');

        $("#partSlct").select2({
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

        var partunit= "";
        var partStatusOption = "";
        var partSourceOption = "";
        var partQualtyOption = "";
        var storeOption = "";
        var storeSectionOption = "";
        $.ajax({
            type: "get",
            url: "source",
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
            url: "status",
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
            url: "quality",
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
            url: "GetAllstores",
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
            url: "Selectindex", // Supplier
            success: function(response) {
                $(".supp").empty();
                $(".supp").append(`<option disabled selected value="">Select Supplier</option>`);

                response.forEach(element => {
                    $(".supp").append(
                        `<option value="${element.id}">${element.name} - ${element.tel01} - ${element.tel02}</option>`
                    );

                });
            }
        });

        $.ajax({
            type: "get",
            url: "GetAllCurrency",
            success: function(response) {
                $("#currencySlct").empty();
                $("#currencySlct").append(`<option value="" selected disabled>Select Currency</option>`);

                response.forEach(element => {
                    $("#currencySlct").append(`<option value="${element.id}">${element.name}</option>`);

                });
            }
        });
        $("#partSlct").on('change', function(e) {
            // console.log($(this).select2('data')[0]);

            var partunitx='';

            var selectedText = $("#select2-partSlct-container").text();
            var selectedType = ($(this).select2('data').length > 0) ? $(this).select2('data')[0].type_id : 0;
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
                    <input type="hidden" name="smallUnit[]"  class="form-control" value="${smallUnit}">

                    <label class="text-nowrap">${selectedText}</label>
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
                    <select class="form-select partQualty text-left" name="partQualty[]" id="" required>
                    ${partQualtyOption}
                    </select>
                </td>
                  <td>
                    <select class="form-select partQualty text-left" name="unit[]" id="" required>
                    ${partunitx}
                    </select>
                </td>
                <td><input onkeyup="calc_table_price()" type="text" name="amount[]" oninput="validateIntegerInput(event)" class="form-control row_amount" value=""  id="" required></td>
                <td><input type="text" name="price[]" onkeyup="calc_table_price()" class="form-control row_price" value="" id="" required>
                <span class="lastpriceValue">0</span>
                </td>
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
                <td class="btn btn-ghost-danger smallColumn" onclick='invTotal -=$(this).closest("tr").find("input.row_tot").val(); $("#invAllTotal").val(invTotal);$("#invTotLbl1").val(invTotal);$("#invTotLbl").text(invTotal);$(this).closest("tr").remove();' >R</td>
            </tr>`);



            var lastrow = $("#newinvtbl tbody tr").first();
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
            
            var taslem_coast = parseFloat($("#taslem_coast").val());
            var ardya_coast = parseFloat($("#ardya_coast").val());
            var in_transport_coast = parseFloat($("#in_transport_coast").val());
            var takhles_coast = parseFloat($("#takhles_coast").val());
            var bank_coast = parseFloat($("#bank_coast").val());
            var nolon_coast = parseFloat($("#nolon_coast").val());
            
            var Tot = (transCoast ? transCoast : 0) + (insuranceCoast ? insuranceCoast : 0) + (customs ? customs : 0) + 
            (taslem_coast ? taslem_coast : 0) + 
            (ardya_coast ? ardya_coast : 0) + 
            (in_transport_coast ? in_transport_coast : 0) + 
            (takhles_coast ? takhles_coast : 0) + 
            (bank_coast ? bank_coast : 0) + 
            (nolon_coast ? nolon_coast : 0) + 
            (commition ? commition : 0) + (otherCoast ? otherCoast : 0);
            $('#InvCoastinglbl').text(Tot);
            $('#InvCoasting').val(Tot);

        }
        $("#global_sourceslct").change(function(e) {
            e.preventDefault();
            var selectedSource = $(this).val();
            var allpartSource = $("#newinvtbl tbody").find(".partSource");
            Swal.fire({
                text: "Are you sure you want to change all sources",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, change!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function(result) {
                if (result.value) {
                    for (let i = 0; i < allpartSource.length; i++) {
                        const element = allpartSource[i];
                        $(element).val(selectedSource).trigger('change');

                    }
                    Swal.fire({
                        text: "You have change them ",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    }).then(function() {
                        // Remove current row

                    });
                } else if (result.dismiss === 'cancel') {
                    //undo select
                    $("#global_sourceslct").val('0')
                    ////////////
                    Swal.fire({
                        text: " was not change.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    });
                }
            });


        });

        $("#global_statuslct").change(function(e) {
            e.preventDefault();
            var selectedSource = $(this).val();
            var allpartSource = $("#newinvtbl tbody").find(".partStatus");

            Swal.fire({
                text: "Are you sure you want to change all Status",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, change!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function(result) {
                if (result.value) {
                    for (let i = 0; i < allpartSource.length; i++) {
                        const element = allpartSource[i];
                        $(element).val(selectedSource).trigger('change');

                    }

                    Swal.fire({
                        text: "You have change them ",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    }).then(function() {
                        // Remove current row

                    });
                } else if (result.dismiss === 'cancel') {
                    //undo select
                    $("#global_statuslct").val('0')
                    ////////////

                    Swal.fire({
                        text: " was not change.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    });
                }
            });


        });

        $("#global_qualityslct").change(function(e) {
            e.preventDefault();
            var selectedSource = $(this).val();
            var allpartSource = $("#newinvtbl tbody").find(".partQualty");

            Swal.fire({
                text: "Are you sure you want to change all Quality",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, change!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function(result) {
                if (result.value) {
                    for (let i = 0; i < allpartSource.length; i++) {
                        const element = allpartSource[i];
                        $(element).val(selectedSource).trigger('change');

                    }

                    Swal.fire({
                        text: "You have change them ",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    }).then(function() {
                        // Remove current row

                    });
                } else if (result.dismiss === 'cancel') {
                    //undo select
                    $("global_qualityslct").val('0')

                    ////////////
                    Swal.fire({
                        text: " was not change.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    });
                }
            });



        });

        $("#global_storeslct").change(function(e) {
            e.preventDefault();
            var selectedStore = $(this).val();
            var allpartStore = $("#newinvtbl tbody").find(".Stores");
            Swal.fire({
                text: "Are you sure you want to change all Stores",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, change!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function(result) {
                if (result.value) {
                    for (let i = 0; i < allpartStore.length; i++) {
                        const element = allpartStore[i];
                        $(element).val(selectedStore).trigger('change');

                    }

                    Swal.fire({
                        text: "You have change them ",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    }).then(function() {
                        // Remove current row

                    });
                } else if (result.dismiss === 'cancel') {
                    //undo select

                    ////////////
                    Swal.fire({
                        text: " was not change.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    });
                }
            });




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
        
        $('input[type=radio][name=notaxes]').change(function(e) {
            // var tax = $(this).attr('data-val');
            // var invTot = $("#invTotLbl").text();
            // var Tot = $("#invAllTotal").val();
            // var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100 ).toFixed(2);
            // $("#invAllTotal").val((parseFloat(invtotaltax)+parseFloat(Tot)).toFixed(1));
            // $("#invTotLbl").text(invtotaltax);

            var tax = 0;
            var invTot = $("#invTotLbl1").val();
            var Tot = $("#invAllTotal").val();
            var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100).toFixed(2);
            var kasmTax2 = 0;
            var kasminvtotaltax2 = (parseFloat(invTot) * parseFloat(kasmTax2) / 100).toFixed(2);
            var kasmTax = 0;
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


            $.ajax({
                type: "get",
                url: "GetSections/" + storeid,
                success: function(response) {
                    if (response.length > 0) {
                        storeSectionOption += `<option selected disabled value="">Select Section</option>`;
                        $(el).closest('tr').find('.Sections').empty();
                        $(el).closest('tr').find('.Sections').append(
                            `<option value="">اختر</option>`);
                        response.forEach(element => {
                            $(el).closest('tr').find('.Sections').append(
                                `<option value="${element.id}">${element.name}</option>`);
                            storeSectionOption +=
                                `<option value="${element.id}">${element.name}</option>`;
                        });
                    } else {
                        $(el).closest('tr').find('.Sections').empty();
                        $(el).closest('tr').find('.Sections').append(`<option value="0">No Sections</option>`);
                    }

                }
            });
            $(el).closest('tr').find('.Sections').select2();

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
                        $("#SafetotalV").val(0)
                    } else {
                        $("#Safetotal").text(response)
                        $("#SafetotalV").val(response)

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

        $('#invSuppliernew').select2();
        $('#store_idd').select2();

        $(document).on('change', '.partQualty', function() {
            getlastpriceValue(this);
        })

        function getlastpriceValue(el) {
            // lastpriceValue
            var part_id = $(el).closest('tr').find("input[name='partId[]']").val();
            var type_id = $(el).closest('tr').find("input[name='types[]']").val();

            var source_id = $(el).closest('tr').find('.partSource').val();
            var status_id = $(el).closest('tr').find('.partStatus').val();
            var quality_id = $(el).closest('tr').find('.partQualty').val();

            var currency_id = $("#currencySlct").val();

            console.log(part_id + "/" + source_id + "/" + status_id + "/" + quality_id + "/" + currency_id)

            $.ajax({
                type: "get",
                url: "getlastpriceValue",
                data: {
                    'part_id': part_id,
                    'source_id': source_id,
                    'status_id': status_id,
                    'quality_id': quality_id,
                    'currency_id': currency_id,
                    'type_id': type_id
                },
                success: function(response) {
                    $(el).closest('tr').find('.lastpriceValue').text(response);

                }
            });

        }
        function fillunit(part_id,type_id){
            $.ajax({
                type: "get",
                url: "getpart_unit",
                data: {
                    'part_id': part_id,
                    'type_id': type_id
                },
                success: function(response) {

                    $(el).closest('tr').find('.lastpriceValue').text(response);

                }
            });
        }
    </script>


@endsection
