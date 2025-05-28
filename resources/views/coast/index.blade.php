@extends('layouts.master')

@section('css')
    <style>

    </style>
@endsection

@section('title')
    Coast
@stop

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4">
                        <h2>إضافة تكاليف</h2>
                    </div>
                    <div class="col-lg-4 ">
                        <div class="form-check text-center fs-18">
                            <input class="form-check-input" type="radio" name="optionsType" id="buyOption" value="buyOption">
                            <label class="form-check-label" for="buyOption">
                                فاتورة شراء
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-check text-center fs-18">
                            <input class="form-check-input" type="radio" name="optionsType" id="itemOption"
                                value="itemOption">
                            <label class="form-check-label" for="itemOption">
                                صنف
                            </label>
                        </div>
                    </div>
                </div>

                <div id="buyDiv" class="option-section" style="display: none;">
                    <div class="card p-3">
                        <h4> فاتورة الشراء</h4>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="" id="invoiceidTxt"
                                        placeholder="رقم فاتورة الشراء...">
                                    <button class="btn btn-primary" id="searchbuyInvoiceId" type="button">بحث</button>
                                </div>
                            </div>

                        </div>
                        <form action="saveNewCoast" method="POST" id="buyInvoiceForm">
                            @csrf
                            <input type="hidden" name="type_id" value="0">
                            <input type="hidden" name="item_id" value="0">

                            <div id="buyinvoicerow" style="display: none;">
                                <div class="row">
                                    <div class="col-lg-12 mt-3">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>رقم الفاتورة</th>
                                                    <th>تاريخ الفاتورة</th>
                                                    <th>المبلغ</th>
                                                    <th>العميل</th>
                                                    <th>التكاليف</th>
                                                </tr>
                                            </thead>
                                            <tbody id="buyinvoicerowbody">
                                                <!-- Dynamic rows will be added here -->
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-lg-12">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>الاسم </th>
                                                    <th>التكلفة</th>
                                                    <th>التكلفة الجديدة</th>
                                                </tr>
                                            </thead>
                                            <tbody id="buyinvoicerowbodycoast">
                                                <!-- Dynamic rows will be added here -->
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-lg-12 text-end my-2">
                                        <button type="button" class="btn btn-primary" id="addnewCoastRowBtn">
                                            إضافة تكلفة جديد
                                        </button>
                                    </div>
                                    <div class="col-lg-12">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>الاسم </th>
                                                    <th>التكلفة</th>
                                                </tr>
                                            </thead>
                                            <tbody id="newCoasttablebody">
                                                <!-- Dynamic rows will be added here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <button class="btn btn-primary w-100">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Item Option Div -->
                <div id="itemDiv" class="option-section" style="display: none;">
                    <div class="card p-3">
                        <h4> الأصنــــــــــــــاف</h4>
                        <form action="saveNewCoast2" method="POST" id="">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">

                                    <select class="form-control" name="storeSlct" id="storeSlct">
                                        <option value="">Select Store</option>
                                        @forelse ($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <select name="partSlct" id="partSlct"></select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-12">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>اختر</th>
                                                <th>الاسم</th>
                                                <th>بلد المنشأ</th>
                                                <th>الحالة</th>
                                                <th>الجودة</th>
                                                <th>النوع</th>
                                                <th>الكمية</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemdetailbody">
                                            <!-- Dynamic rows will be added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <h2>فواتير الشراء</h2>
                                <div class="col-lg-12 mt-3">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>رقم الفاتورة</th>
                                                <th>تاريخ الفاتورة</th>
                                                <th>المبلغ</th>
                                                <th>العميل</th>
                                                <th>اجمالي التكاليف </th>
                                            </tr>
                                        </thead>
                                        <tbody id="itembuyinvoicerowbody">
                                            <!-- Dynamic rows will be added here -->
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-12 text-end my-2">
                                    <button type="button" class="btn btn-primary" id="addnewCoastRowBtn2">
                                        إضافة تكلفة جديد
                                    </button>
                                </div>
                                <div class="col-lg-12">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>الاسم </th>
                                                <th>التكلفة</th>
                                            </tr>
                                        </thead>
                                        <tbody id="newCoasttablebody2">
                                            <!-- Dynamic rows will be added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <button class="btn btn-primary w-100">Save</button>
                        </form>
                    </div>




                </div>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="newCoastModal" tabindex="-1" aria-labelledby="newCoastModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="max-width: 30%;margin: 0 auto;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newCoastModalLabel">إضافة تكلفة جديدة</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Modal content -->
                        <input type="text" class="form-control" id="newCoastName" placeholder="اسم التكلفة الجديدة">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" onclick="savenewcoastitem()" class="btn btn-primary"
                            id="saveNewCoastBtn">حفظ</button>
                    </div>
                </div>
            </div>
        </div>




    @endsection

    @section('js')

        <script>
            var currentRow = 0;
            var coasts = @json($coasts);
            var coastOptions = '<option selected disabled value="">Select Coast</option>';
            $.each(coasts, function(index, coast) {
                coastOptions += `<option value="${coast.id}">${coast.name}</option>`;
            });
            $(document).ready(function() {
                function toggleSections() {
                    if ($('#buyOption').is(':checked')) {
                        $('#buyDiv').show();
                        $('#itemDiv').hide();
                    } else if ($('#itemOption').is(':checked')) {
                        $('#buyDiv').hide();
                        $('#itemDiv').show();
                    }
                }

                // Run toggleSections on change of either radio button
                $('input[name="optionsType"]').on('change', toggleSections);

                // Optional: Run it on page load if one is pre-selected
                toggleSections();
            });
            $(document).on('click', '.newcoastquickBtn', function() {
                $('#newCoastModal').modal('show');
                currentRow = $(this).closest('tr').index();
            });
            $(document).on('click', '.newcoastquickBtn2', function() {
                $('#newCoastModal').modal('show');
                currentRow = $(this).closest('tr').index();
            });
            $('#addnewCoastRowBtn').on('click', function() {
                let newRow = `
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <select class="form-control" name="item_name[]" placeholder="الاسم" required>
                                ${coastOptions}
                            </select>
                            <button type="button" class="btn btn-sm btn-dark newcoastquickBtn">
                                <i class="ri ri-pencil-fill"></i>
                            </button>
                        </div>
                    </td>
                   
                    <td>
                        <input type="number" step="0.01" class="form-control" name="new_cost[]" placeholder="التكلفة الجديدة" required>
                    </td>
                    <td class="text-end">
                       <button type="button" class="btn btn-danger deleteRowBtn " id="">
                                <i class="ri ri-delete-bin-6-line"></i>
                            </button>
                    </td>
                </tr>
            `;
                $('#newCoasttablebody').append(newRow);
            });
            $('#addnewCoastRowBtn2').on('click', function() {
                let newRow = `
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <select class="form-control" name="item_name[]" placeholder="الاسم" required>
                                ${coastOptions}
                            </select>
                            <button type="button" class="btn btn-sm btn-dark newcoastquickBtn2">
                                <i class="ri ri-pencil-fill"></i>
                            </button>
                        </div>
                    </td>
                   
                    <td>
                        <input type="number" step="0.01" class="form-control" name="new_cost[]" placeholder="التكلفة الجديدة" required>
                    </td>
                    <td class="text-end">
                       <button type="button" class="btn btn-danger deleteRowBtn " id="">
                                <i class="ri ri-delete-bin-6-line"></i>
                            </button>
                    </td>
                </tr>
            `;
                $('#newCoasttablebody2').append(newRow);
            });
            $(document).on('click', '.deleteRowBtn', function() {
                $(this).closest('tr').remove();
            });
            $('#invoiceidTxt').on('keypress', function(e) {
                if (e.which === 13) { // 13 is the Enter key
                    $('#searchbuyInvoiceId').click();
                }
            });
            $("#searchbuyInvoiceId").on('click', function() {
                let invoiceId = $("#invoiceidTxt").val();
                $('#buyinvoicerowbody').empty();
                $('#buyinvoicerowbodycoast').empty();
                $('#newCoasttablebody').empty();

                $.ajax({
                    url: '/getBuyInvoice/' + invoiceId,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#buyinvoicerow').show();
                            let invoiceData = response.data;
                            $('#buyInvoiceForm input[name="type_id"]').val(-1);
                            $('#buyInvoiceForm input[name="item_id"]').val(invoiceData.id);
                            let row = `
                            <tr>
                                <td><a href="/printBuyInvoice/${invoiceData.id}" target="blank">${invoiceData.id}</a></td>
                                <td>${invoiceData.creation_date.split('T')[0]}</td>
                                <td>${invoiceData.order_suppliers[0].total_price}</td>
                                <td>${invoiceData.order_suppliers[0].supplier.name}</td>
                                <td>${invoiceData.order_suppliers[0].coast}</td>
                            </tr>
                        `;
                            $('#buyinvoicerowbody').append(row);


                            $('#buyinvoicerowbodycoast').append(`<tr>
                            <td>مصروفات شحن ونقل المشتريات </td>
                            <td>${invoiceData.order_suppliers[0].transport_coast}</td>
                            <td><input type="number" step="0.01" class="form-control" name="transport_coast" placeholder="التكلفة الجديدة"></td>
                        </tr>`);

                            $('#buyinvoicerowbodycoast').append(`<tr>
                            <td>مصروفات التأمين على البضاعة المشتراة </td>
                            <td>${invoiceData.order_suppliers[0].insurant_coast}</td>
                            <td><input type="number" step="0.01" class="form-control" name="insurant_coast" placeholder="التكلفة الجديدة"></td>
                        </tr>`);

                            $('#buyinvoicerowbodycoast').append(`<tr>
                            <td> الرسوم الجمركية على البضاعة المشتراة</td>
                            <td>${invoiceData.order_suppliers[0].customs_coast}</td>
                            <td><input type="number" step="0.01" class="form-control" name="customs_coast" placeholder="التكلفة الجديدة"></td>
                        </tr>`);

                            $('#buyinvoicerowbodycoast').append(`<tr>
                            <td> عمولة وكلاء الشراء</td>
                            <td>${invoiceData.order_suppliers[0].commotion_coast}</td>
                            <td><input type="number" step="0.01" class="form-control" name="commotion_coast" placeholder="التكلفة الجديدة"></td>
                        </tr>`);

                            $('#buyinvoicerowbodycoast').append(`<tr>
                            <td> مصروفات التسليم</td>
                            <td>${invoiceData.order_suppliers[0].taslem_coast}</td>
                            <td><input type="number" step="0.01" class="form-control" name="taslem_coast" placeholder="التكلفة الجديدة"></td>
                        </tr>`);

                            $('#buyinvoicerowbodycoast').append(`<tr>
                            <td> النولون البحري</td>
                            <td>${invoiceData.order_suppliers[0].nolon_coast}</td>
                            <td><input type="number" step="0.01" class="form-control" name="nolon_coast" placeholder="التكلفة الجديدة"></td>
                        </tr>`);


                            $('#buyinvoicerowbodycoast').append(`<tr>
                            <td> غرامات وارضيات</td>
                            <td>${invoiceData.order_suppliers[0].ardya_coast}</td>
                            <td><input type="number" step="0.01" class="form-control" name="ardya_coast" placeholder="التكلفة الجديدة"></td>
                        </tr>`);


                            $('#buyinvoicerowbodycoast').append(`<tr>
                            <td>نقل داخلى </td>
                            <td>${invoiceData.order_suppliers[0].in_transport_coast}</td>
                            <td><input type="number" step="0.01" class="form-control" name="in_transport_coast" placeholder="التكلفة الجديدة"></td>
                        </tr>`);



                            $('#buyinvoicerowbodycoast').append(`<tr>
                            <td> اتعاب التخليص</td>
                            <td>${invoiceData.order_suppliers[0].takhles_coast}</td>
                            <td><input type="number" step="0.01" class="form-control" name="takhles_coast" placeholder="التكلفة الجديدة"></td>
                        </tr>`);



                            $('#buyinvoicerowbodycoast').append(`<tr>
                            <td> عمولة تحويل البنك</td>
                            <td>${invoiceData.order_suppliers[0].bank_coast}</td>
                            <td><input type="number" step="0.01" class="form-control" name="bank_coast" placeholder="التكلفة الجديدة"></td>
                        </tr>`);


                            $('#buyinvoicerowbodycoast').append(`<tr>
                            <td> مصروفات اخرى</td>
                            <td>${invoiceData.order_suppliers[0].other_coast}</td>
                            <td><input type="number" step="0.01" class="form-control" name="other_coast" placeholder="التكلفة الجديدة"></td>
                        </tr>`);


                            let other_coasts = response.other_coasts;
                            if (other_coasts.length > 0) {
                                $.each(other_coasts, function(index, coast) {
                                    $('#newCoasttablebody').append(`<tr>
                                    <td>${coast.getcoast.name}</td>
                                    <input type="hidden" name="item_name[]" value="${coast.getcoast.id}">
                                    
                                    <td>${coast.value}</td>
                                    <td><input type="number" step="0.01" class="form-control" name="new_cost[]" placeholder="التكلفة الجديدة"></td>
                                </tr>`);
                                });
                            }


                        } else {
                            $('#buyinvoicerow').hide();
                            alert('Invoice not found!');
                        }
                    },
                    error: function() {
                        $('#buyinvoicerow').hide();
                        alert('Error fetching invoice data!');
                    }
                });
            });


            function savenewcoastitem() {
                var newcoast = $('#newCoastName').val();
                if (newcoast) {
                    $.ajax({
                        url: '/savenewcoastitem',
                        type: 'GeT',
                        data: {
                            name: newcoast,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#newCoastModal').modal('hide');

                                coastOptions +=
                                    `<option value="${response.data.id}">${response.data.name}</option>`;
                                var current_row1 = $('#newCoasttablebody tr').eq(currentRow);
                                var current_row2 = $('#newCoasttablebody2 tr').eq(currentRow);

                                if (current_row1.length > 0) {
                                    current_row1.find('select[name="item_name[]"]').append(
                                        `<option selected value="${response.data.id}">${response.data.name}</option>`
                                    );
                                }

                                if (current_row2.length > 0) {
                                    current_row2.find('select[name="item_name[]"]').append(
                                        `<option selected value="${response.data.id}">${response.data.name}</option>`
                                    );
                                }

                                $('#newCoastName').val('');
                            } else {
                                alert('Error saving new coast!');
                            }
                        },
                        error: function() {
                            alert('Error saving new coast!');
                        }
                    });
                } else {
                    alert('Please enter a coast name!');
                }
            }




            $(document).on('change', '.selectedItemRadio', function() {
                let value = $(this).val(); // gets the full value like "1-3-4-2-5"

                // Optional: split the value if you want to send separate parameters
                let [type_id, part_id, source_id, status_id, quality_id] = value.split('-');

                $.ajax({
                    url: 'coastitemData/' + type_id + '/' + part_id + '/' + source_id + '/' + status_id + '/' +
                        quality_id,
                    method: 'GET',
                    success: function(response) {
                        console.log('Success:', response);
                        // Do something with the response (e.g., show details, update UI) 
                        var xbuyinvoice = response.data;
                        var extraCoast = response.extraCoast;
                        $('#itembuyinvoicerowbody').empty();
                       
                        $('#newCoasttablebody2').empty();
                        xbuyinvoice.forEach(element => {
                            var inv = element.order_supplier;
                            let row = `
                            <tr>
                                <td><a href="/printBuyInvoice/${inv.id}" target="blank">${inv.id}</a></td>
                                <td>${inv.deliver_date.split('T')[0]}</td>
                                <td>${inv.total_price}</td>
                                <td>${inv.supplier.name}</td>
                                <td>${inv.coast}</td>
                            </tr>
                        `;
                            $('#itembuyinvoicerowbody').append(row);
                        });

                        extraCoast.forEach(element => {
                            $('#newCoasttablebody2').append(`<tr>
                            <td>${element.getcoast.name}</td>
                            <input type="hidden" name="item_name[]" value="${element.getcoast.id}">
                            
                            <td>${element.value}</td>
                            <td><input type="number" step="0.01" class="form-control" name="new_cost[]" placeholder="التكلفة الجديدة"></td>
                        </tr>`);
                        });


                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                    }
                });
            });



















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

            $("#partSlct").on('change', function(e) {
                var selectedText = $("#select2-partSlct-container").text();
                var selectedType = $(this).select2('data')[0].type_id
                var selectedPartID = $(this).val();
                if ($("#storeSlct").val() == "") {
                    alert('الرجاء اختيار المخزن');
                    return;
                }
                $.ajax({
                    type: "GET",
                    url: "allDataForId",
                    data: {
                        PartID: selectedPartID,
                        typeId: selectedType,
                        storeId: $("#storeSlct").val()
                    },
                    success: function(data) {
                        console.log(data);

                        if (data.data.length > 0) {
                            $('#itemdetailbody').empty();
                            data.data.forEach(element => {
                                var row = `
                            <tr>
                                <td><input class="selectedItemRadio form-check-input" type="radio" name="selectedItem" id="selectedItem" value="${element.type_id}-${element.part_id}-${element.source_id}-${element.status_id}-${element.quality_id}"></td>
                                <td>${element.name}</td>
                                <td>${element.source}</td>
                                <td>${element.status}</td>
                                <td>${element.quality}</td>
                                <td>${element.type_id}</td>
                                <td>${element.amount}</td>
                               
                                <input type="hidden" name="type_id" value="${element.type_id}">
                            </tr>`;
                                $('#itemdetailbody').append(row);
                            });
                            $('#itemdetailbody').show();
                        } else {
                            $('#itemdetailbody').empty();
                            alert('لا يوجد بيانات لهذا الصنف');
                        }




                    }
                })
            });
        </script>
    @endsection
