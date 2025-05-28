<!doctype html>
<html lang="en" data-layout="twocolumn" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none"
    data-preloader="disable" dir="rtl">


<head>
    <meta charset="utf-8" />
    <title>
        فواتير العملاء
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico') }}">

    <link href="{{ URL::asset('assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" />

    <!-- Layout config Js -->
    <script src="{{ URL::asset('assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ URL::asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ URL::asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ URL::asset('assets/css/app-rtl.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ URL::asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <style>
        body {
            margin: 2em;
            font-size: 20px;
        }

        .homeDiv {
            position: absolute;
            top: 50px;
            left: 50px;
            z-index: 88888;
        }

        #madMdl .modal-content {
            width: 60vw !important;
        }

        #madMdl .modal-dialog {
            max-width: 60vw !important;
        }

        table {
            border: 1px solid #ccc;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            width: 100%;
            table-layout: fixed;
        }
    </style>

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">
        <div class="m-0 main-content">
            <div class="p-0 page-content">
                <div class="homeDiv">
                    <form action="/pos" method="">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="storeId" value="{{ $store->id }}">
                        <button class="btn btn-secondary float-md-end">الرئيسية</button>
                    </form>
                </div>
                <div class="p-2 mb-3 bg-white border border-2 border-dark row">
                    <h1 class="text-center text-decoration-underline">{{ $store->name }}</h1>
                    <input type="hidden" name="storeId" id="storeId" value="{{ $store->id }}">
                    <div class="col">
                        <div class="row">
                            <div class="col-12 table-responsive" style="overflow-x:auto;">
                                <table class="table bg-soft-dark">
                                    <tr>
                                        <td colspan="1"  class="text-end">
                                            <label for="" class=" text-success">الاسم :</label></td>
                                            <td colspan="5"><span class="">{{ $client->name }}</span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td class="text-end">
                                            <label for="" class=" text-success">رقم التليفون :</label></td>
                                        <td><span class="">{{ $client->tel01 }}</span>
                                        </td>
                                        <td class="text-end"><label for="" class=" text-success">رقم التليفون :</label></td>
                                    <td><span class="">{{ $client->tel02 }}</span>
                                        </td>

                                        <td class="text-end"><label for="" class=" text-success">رقم التليفون :</label></td>
                                    <td><span class="">{{ $client->tel03 }}</span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td class="text-end"><label for="" class=" text-success">الرقم القومي :</label></td>
                                    <td><span class="">{{ $client->national_no }}</span>
                                        </td>
                                        <td class="text-end"><label for="" class=" text-success">ملاحظات :</label></td>
                                    <td><span class=""> {{ $client->notes }}</span>
                                        </td>
                                        <td class="text-end"><label for="" class=" text-success">العنوان :</label></td>
                                    <td><span class="">{{ $client->address }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col text-end">
                                <h3><span>إجمالي المديونية : </span> <span class="p-1 rounded text-bg-danger">
                                        {{ $client->invoices->sum('actual_price') - $client->invoices->sum('paied') - $client->invoices->sum('discount') - $client->invoice_client_madyoneas->sum('paied') }}
                                    </span></h3>

                            </div>
                            <div class="col text-start">

                            </div>
                        </div>
                        <hr>
                        <input type="search" placeholder="بحــــث" name="" id="searchClientInv">
                        <div class="mt-2 row">
                            <div class="col">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>م</th>
                                            <th>التاريخ</th>
                                            <th>المخزن</th>

                                            <th> عدد القطع</th>
                                            <th> </th>

                                        </tr>
                                    </thead>
                                    @php
                                        $m = 1;
                                    @endphp
                                    @foreach ($quotes as $invoice)
                                        <tr class="resinv">
                                            <td>{{ $m++ }}</td>
                                            <td class="">{{ date('d-m-Y', strtotime($invoice->date)) }}</td>
                                            <td>{{ $invoice->store->name }}</td>

                                            <td>{{ empty($invoice->quoteItems) ? 0 : $invoice->quoteItems->count() }}
                                            </td>
                                            <td>

                                                <button onclick="openModel({{ $m-2 }})" class="btn btn-success w-50">عرض </button>
                                                {{-- <a class="px-2 text-danger" href="#">تحويل لفاتورة</a> --}}
                                            </td>

                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                    </div>
                </div>



            </div>
        </div>
    </div>

    <div class="modal fade" id="madMdl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="madMdlLabel" aria-hidden="true">

        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="madMdlLabel">عرض أسعـــــار </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/saveclientinvoiceprice" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="storeId" value="{{ $store->id }}">
                    <input type="hidden" name="clientId" value="{{ $client->id }}">
                    <input type="hidden" id="saletype" name="saletype" value="0">
                    <input type="hidden" id="quoteId" name="quoteId" value="0">
                    <div class="row">
                        <div class="col"> <h4 id="invoiceDateLbl">15/5/2023</h4></div>
                        <div class="col text-end">
                            <button type="button" id="showInvoiceData" class="btn btn-success mb-2">تحويل لفاتورة بيع</button>
                            <button type="button" class="btn btn-danger mb-2"><i class="mdi mdi-plus p-1 rounded text-bg-danger"></i></button>
                        </div>
                    </div>


                    <div class="row" id="mainItemContent">
                        <div class="col-lg-12">
                            <table class="table" id="itemTbl">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>New Price</th>
                                        <th>Total</th>
                                        <th>Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>استك فتيس TM/5</td>
                                        <td>1</td>
                                        <td>120</td>
                                        <td>120</td>
                                        <td>
                                            <i class="mdi mdi-trash-can-outline p-1 rounded text-bg-danger"></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-4" style="display: none" id="invoiceTaxContent">
                            <label for="">Price Type</label>
                            <select name="" class="form-control" id="pricingTypeSlct" name="pricingTypeSlct" disabled="true">
                                <option selected disabled value="">Select Pricing Type</option>
                                @foreach($SaleType as $type)
                                    <option value="{{ $type->id }}">{{ $type->type }}</option>
                                @endforeach
                            </select>
                            <label for="">Tax</label>
                            <select name="" class="form-control mb-2" id="taxSlct">
                                <option selected disabled value="">Select Tax</option>
                                @foreach($alltaxes as $type)
                                    <option value="{{ $type->id }}" data-tax ="{{ $type->value }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <ul class="list-group" id="taxContainer">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                  tax 14%
                                  <span class="badge badge-primary badge-pill text-bg-danger">1401</span>
                                </li>

                            </ul>
                            <hr>
                            <input type="text" class="form-control" name="taxPrice" value="0" placeholder="Tax" id="taxPrice" readonly>
                            <input type="text" class="form-control" name="discPrice" placeholder="Discount" value="0" id="discPrice" >
                            <input type="text" class="form-control" name="subtotalPrice" placeholder="Sub Total" id="subtotalPrice" readonly>
                            <input type="text" class="form-control" name="totalPrice" placeholder="Total" id="totalPrice" readonly>
                            <input type="text" class="form-control" name="paiedPrice" placeholder="المدفوع" id="paiedPrice" >
                            <button class="btn btn-info w-100" >save</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            {{-- <button type="button" class="btn btn-primary">save</button> --}}
            </div>
        </div>
        </div>

    </div>

    {{-- ////////////////////////////////////////////////////////////////// --}}
    <script src="{{ URL::asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    {{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js"></script>

    <script>
        (function($) {
            $.fn.clickToggle = function(func1, func2) {
                var funcs = [func1, func2];
                this.data('toggleclicked', 0);
                this.click(function() {
                    var data = $(this).data();
                    var tc = data.toggleclicked;
                    $.proxy(funcs[tc], this)();
                    data.toggleclicked = (tc + 1) % 2;
                });
                return this;
            };
        }(jQuery));
    </script>
    <script>
        var items={!! $quotes !!};
        var store_id = {!! $store->id !!};
        var allparts = {!! $items_part !!};
        var partSelectOptions='<option selected disabled value="">Select Part</option>';
        $(document).ready(function() {

            for(var i=0 ; i<allparts.length ; i++){
                partSelectOptions += `<option value="${allparts[i]['p_data']['id']}">${allparts[i]['p_data']['name']}</option>`;
            }


        });

        function openModel(index) {
            var invoice = items[index];
            console.log(items[index]);

            //  $.ajax({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     },
            //     type: "GET",
            //     url: "/getquoteData/"+invoice['id']+"/"+store_id,

            //     datatype: 'JSON',
            //     statusCode: {
            //         404: function() {
            //             alert("page not found");
            //         }
            //     },
            //     error: function(XMLHttpRequest, textStatus, errorThrown) {
            //         console.log(errorThrown);
            //     },
            //     success: function(data) {

            //     }
            // });

            $("#invoiceDateLbl").text(invoice['date']);
            $("#itemTbl tbody").empty();
            $("#quoteId").val(items[index]['id']);
            var subtotalPrice=0;
            invoice['quote_items'].forEach(element => {
                var priceTypeid = element.pricing_type.id;
                var newprice = 0;
                for (let index = 0; index < element.price.length; index++) {
                    const priceing = element.price[index];
                    if(priceing.sale_type.id == priceTypeid){
                        newprice = priceing.price
                        $("#pricingTypeSlct").val(priceTypeid);
                        $("#saletype").val(priceTypeid);
                    }

                }
                var pName='';
                if (element.part_type_id==1) {
                    pName=element['part']['name'];
                }else if(element.part_type_id==6){
                    pName=element['kit']['name'];
                }

                $("#itemTbl tbody").append(` <tr>
                                        <input type="hidden" value="${element.part_id}" name="part_id[]">
                                        <input type="hidden" value="${element.source_id}" name="source_id[]">
                                        <input type="hidden" value="${element.status_id}" name="status_id[]">
                                        <input type="hidden" value="${element.quality_id}" name="quality_id[]">
                                        <input type="hidden" value="${element.part_type_id}" name="type_id[]">

                                        <td>${pName}</td>
                                        <td> <input class="form-control-sm w-100" name="itemamount[]" type="number" value="${element['amount']}"  /> </td>
                                        <td>${element['selected_price']}</td>
                                        <td class="newPriceLbl">${newprice}</td>
                                        <td> <span class="totalitemPrice">${element['amount'] * element['selected_price']} </span></td>
                                        <td onclick="removeitem(this)">
                                            <i class="mdi mdi-trash-can-outline p-1 rounded text-bg-danger"></i>
                                        </td>
                                    </tr>`);
                subtotalPrice+= element['amount'] * element['selected_price'];

            });
            $("#subtotalPrice").val(subtotalPrice);
            $("#totalPrice").val(subtotalPrice);

            $("#madMdl").modal('toggle')
        }



        $("#showInvoiceData").clickToggle(function () {
            $("#mainItemContent").find('.col-lg-12').removeClass('col-lg-12').addClass('col-lg-8').addClass('fs-19');
            $("#invoiceTaxContent").show();
        }, function() {
            $("#mainItemContent").find('.col-lg-8').removeClass('col-lg-8').addClass('col-lg-12').removeClass('fs-19');
            $("#invoiceTaxContent").hide();
        });

        $("#pricingTypeSlct").change(function (e) {
            e.preventDefault();


        });
        $("#taxContainer").empty();
        var totalTaxPercent = 0;
        $("#taxSlct").change(function (e) {
            e.preventDefault();
           var id = $(this).val();
           var name = $("#taxSlct option:selected").text();
           var value = $("#taxSlct option:selected").attr('data-tax');
            $("#taxContainer").append(`<li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span onclick="removeTax(this,${value})" class="btn fs-6 link-primary">REMOVE</span> ${name}
                                        <span class="badge badge-primary badge-pill text-bg-danger">${value} %</span>
                                        </li>`);
            var oldtaxtot = $("#taxPrice").val();
            var subtotalPrice = $("#subtotalPrice").val();

            $("#taxPrice").val(parseFloat(oldtaxtot,2)+(subtotalPrice*value/100));
            $("#totalPrice").val(parseFloat(subtotalPrice,2)+parseFloat($("#taxPrice").val()));
            totalTaxPercent +=parseInt(value);
        });

        function removeTax(el,value){
            $(el).closest('li').remove();
            var subtotalPrice = $("#subtotalPrice").val();
            $("#taxPrice").val(parseFloat($("#taxPrice").val(),2)-(subtotalPrice*value/100))
            $("#totalPrice").val(parseFloat(subtotalPrice,2)+parseFloat($("#taxPrice").val()));
        }

        $("#discPrice").keyup(function (e) {
            var subtotalPrice =parseFloat($("#subtotalPrice").val(),2);
            var taxPrice =parseFloat($("#taxPrice").val(),2);
            var discount =parseFloat($(this).val(),2);
            $("#totalPrice").val(subtotalPrice+taxPrice-discount);


        });

        function removeitem(el){
            $(el).closest('tr').remove();
            var itemTotal = parseFloat($(el).closest('tr').find('.totalitemPrice').text(),2);
            var subtotalPrice = $("#subtotalPrice").val();

            $("#subtotalPrice").val(parseFloat(subtotalPrice,2)-itemTotal);
            var itemTax = itemTotal*totalTaxPercent/100;
            $("#totalPrice").val(parseFloat(subtotalPrice,2)-itemTotal+parseFloat($("#taxPrice").val())-itemTax);
        }
    </script>

</body>

</html>
