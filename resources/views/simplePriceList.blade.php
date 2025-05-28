@extends('layouts.master')
@section('css')


    <style>
        table {
            font-family: 'Droid Arabic Naskh', serif;
        }



        .modal-dialog {
            max-width: 1000px !important;
        }

        @media print {

            select,
            button,
            .noprint {
                display: none !important;
            }

        }
        /*#datatable > tbody > tr:nth-child(3n+1) {*/
           /* background-color: #07995463 !important; // Change this to the color you want */
        /*}*/
          .highlight-row {
                background-color: #07995463 !important;/* This is a semi-transparent green color */
            }
    </style>
@endsection
@section('title')
    Pricing
@stop


@section('content')



    <div class="main-content">
        <div class="page-content">
            <h1 class="text-center text-info">. التسعــــــــيرة</h1>
            <div class="card">
                <div class="row">
                    <div class="col-lg-12 text-left">
                        <button class="btn text-bg-success" type="button" data-bs-toggle="modal"
                            data-bs-target="#staticBackdrop">Currency</button>
                        <a class="btn text-bg-info" href="/pricingAll" type="button"> تسعيير الكــــــــل</a>
                        <a class="btn text-bg-danger" href="{{URL::route('pricingwith_flag', array('id' => 0))}}" id="" class="btn text-bg-danger  w-100">   القطع التى لم تسعر   </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table id="datatable" class="table table-striped table-bordered cell-border fw-bold display  dt-responsive dataTable dtr-inline no-footer"  style="font-family: 'Cairo';width:100%">
                            <thead style="background:#5fcee78a">
                                <tr>

                                    <td>م</td>
                                    <td>Name</td>
                                    <td>Units</td>

                                    <td>Source</td>
                                    <td>Status</td>
                                    <td>Quality</td>
                                    
                                    <td>Price</td>
                                    <td>Sale Type</td>
                                    <td>Action</td>
                                    <td>History</td>

                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">العمــــــلات</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="GET" action="/updatecurrcy" enctype="multipart/form-data">
                            @csrf
                            @method('GET')
                            <div class="modal-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    @foreach ($currencyHistory as $currency)
                                        <tr>
                                            <td>
                                                {{ $currency->currency_type->name }}
                                                <input type="hidden" name="curency[]" value="{{ $currency->currency_id }}">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" name="price[]"
                                                    value="{{ $currency->value }}" id="">
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="static1Backdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">الأنـــــواع</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="/pricingAll" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="modal-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    @foreach ($pricingTypes as $pricingType)
                                        <tr>
                                            <td>
                                                {{ $pricingType->type }}
                                                <input type="hidden" name="pricingType[]" value="{{ $pricingType->id }}">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" name="pricepercent[]"
                                                    value="0" id="">
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <div class="modal fade" id="priceHistory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="priceHistoryLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="priceHistoryLabel">التسعيرة</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                            <div class="modal-body text-center">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h3>الأسعار</h3>
                                        <table id="pricingListTbl" class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>من</th>
                                                    <th>الي</th>
                                                    <th>السعر</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-lg-6">
                                        <h3>فواتير الشراء</h3>
                                        <table id="buyInvoicesTbl" class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>المورد</th>
                                                    <th>التاريخ</th>
                                                    <th>العملة</th>
                                                    <th>التكلفة</th>
                                                    <th>السعر</th>
                                                    <th>سعر العملة وقت الشراء</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                        
                                        <table id="lastestTbl" class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                   
                                                    <th>النوع</th>
                                                    <th>السعر</th>
                                                    <th></th>
                                                  
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">

                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('js')
<script>
    var pricetable;
    $(document).ready(function() {

        pricetable = $('#datatable').DataTable({
            dom: '<"dt-buttons"Bf><"clear">lirtp',

            "language": {
                "zeroRecords": "لا يوجـــــــــــــــــــــد بيانات",
                "processing": "جاري تحميــــــــــــــــــــــــــــل البيانات ...." 
            },
            buttons: [
                "colvis",
                "copyHtml5",
                "csvHtml5",
                "excelHtml5",
                {
                        extend: 'pdfHtml5',
                        download: 'open'
                    },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [1, 2,3,4,5,6]
                    }
                },
            ],
            processing : true,
            serverSide: true,
            lengthMenu: [[10, 25, 50, 100,-1], [10, 25, 50,100, "All"]],
            searching: true,

            search: {
                return: true
            },
            ajax: {
                url: "{{ route('allprice') }}",
                type: "GET"
            },
            order: ['1', 'DESC'],
            // processing: true,
            pageLength: 25,


            aoColumns: [
                {
                    data: 'DT_RowIndex',
                },
                {
                    data: 'name',
                },
                {
                    data: 'units',
                },
                {
                    data: 'source',
                },
                {
                    data: 'status',
                },
                {
                    data: 'quality',
                },
                {
                    data: 'price',
                },
                {
                    data: 'sale_type',
                },
                {
                    data: 'action',
                },
                {
                    data: 'history',
                }
            ],
            // initComplete: function () {
            //         this.api()
            //             .columns()
            //             .every(function () {
            //                 let column = this;

            //                 // Create select element
            //                 let select = document.createElement('select');
            //                 select.add(new Option(''));
            //                 column.footer().replaceChildren(select);

            //                 // Apply listener for user change in value
            //                 select.addEventListener('change', function () {
            //                     column
            //                         .search(select.value, {exact: true})
            //                         .draw();
            //                 });

            //                 // Add list of options
            //                 column
            //                     .data()
            //                     .unique()
            //                     .sort()
            //                     .each(function (d, j) {
            //                         select.add(new Option(d));
            //                     });
            //             });
            // }
        });
    });

    $(document).on('click','.editpriceBtn',function(e){
        e.preventDefault();
        $(this).hide();
        $(this).parent().find('.savepriceBtn').show();
        $(this).closest('tr').find('.priceVal').removeClass('border-0').addClass('border-dark');
         
        // alert($(this).attr('part-id'));

    })

    $(document).on('click','.savepriceBtn',function(e){
        e.preventDefault();
        $(this).hide();
        $(this).parent().find('.editpriceBtn').show();
        $(this).closest('tr').find('.priceVal').addClass('border-0').removeClass('border-dark');
        var price = $(this).closest('tr').find('.priceVal').val();
        var selectedunits = $(this).closest('tr').find('.unitsCls').val();
        var smallunits = $(this).closest('tr').find('.smallunits').val();
        
        var part_id = $(this).attr('part-id');
        var source_id = $(this).attr('source-id');
        var status_id = $(this).attr('status-id');
        var type_id = $(this).attr('type-id');
        var quality_id = $(this).attr('quality-id');
        var sale_type_id = $(this).attr('sale_type-id');
        // alert(price + " / " + sale_type_id);
        // alert($(this).attr('part-id'));

        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "saveprice1",
                data: {
                    'price' : price,
                    'part_id' : part_id,
                    'source_id' : source_id,
                    'status_id' : status_id,
                    'type_id' : type_id,
                    'quality_id' : quality_id,
                    'selectedunit' : selectedunits,
                    'smallunits' : smallunits,
                    'sale_type_id' : sale_type_id
                      },
                datatype: 'JSON',
                statusCode: {
                    404: function() {
                        alert("page not found");
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function(data) {
                    // console.log(data);
                    if (data) {
                        Swal.fire('تم التعديل بنجـــــــاح')
                    }
                   
                }
            });

    })

    $(document).on('click', '.historypriceBtn', function(e) {
            e.preventDefault();

            var price = $(this).closest('tr').find('.priceVal').val();
            var part_id = $(this).attr('part-id');
            var source_id = $(this).attr('source-id');
            var status_id = $(this).attr('status-id');
            var type_id = $(this).attr('type-id');
            var quality_id = $(this).attr('quality-id');
            var sale_type_id = $(this).attr('sale_type-id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "getprice1history",
                data: {
                    'part_id': part_id,
                    'source_id': source_id,
                    'status_id': status_id,
                    'type_id': type_id,
                    'quality_id': quality_id,
                    'sale_type_id': sale_type_id
                },
                datatype: 'JSON',
                statusCode: {
                    404: function() {
                        alert("page not found");
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function(data) {
                    console.log(data);
                    // 00000000000000000
                    if(data){
                        $("#priceHistory").modal('toggle');
                        var pricelist = data.pricingList;
                        var buyInvoices = data.buyInvoices;

                        $("#pricingListTbl tbody").empty();
                        $("#buyInvoicesTbl tbody").empty();
                        $("#lastestTbl tbody").empty();
                        
                        for (let i = 0; i < pricelist.length; i++) {
                            const element = pricelist[i];
                            if(element.to){
                                $("#pricingListTbl tbody").append(` <tr>
                                                    <td>${element.from.split('T')[0]}</td>
                                                    <td>${element.to.split('T')[0]}</td>
                                                    <td>${element.price}</td>
                                                </tr>`);
                            }else{
                                $("#pricingListTbl tbody").append(` <tr>
                                                    <td>${element.from.split('T')[0]}</td>
                                                    <td>حتي الأن</td>
                                                    <td>${element.price}</td>
                                                </tr>`);
                            }


                        }
                        for (let i = 0; i < buyInvoices.length; i++) {
                            const element = buyInvoices[i];
                            $("#buyInvoicesTbl tbody").append(` <tr>
                                                    <td>${element.order_supplier.supplier.name}</td>
                                                    <td>${element.order_supplier.confirmation_date.split('T')[0]}</td>
                                                    <td>${element.order_supplier.currency_type.name}</td>
                                                    <td>${element.buy_costing}</td>
                                                    <td>${element.buy_price}</td>

                                                    <td>${element.currency_value[0].currencies[0].value}</td>

                                                </tr>`);

                        }
                        
                        if(data.lasts.length > 0){
                            for (let i = 0; i < data.lasts.length; i++) {
                              const element = data.lasts[i];
                                    $("#lastestTbl tbody").append(` <tr>
                                                        <td>${element.type}</td>
                                                        <td><input type="text" class="priceVal2" value="${element.price}"></td>
                                                        <td><button class="btn btn-danger savepriceBtn22 fs-22"  part-id="${element.part_id}" source-id="${element.source_id}" status-id="${element.status_id}" quality-id="${element.quality_id}" type-id="${element.type_id}" sale_type-id="${element.sale_type}"><i class="bx bx-save"></i></button></td>
                                                    </tr>`);
                               


                            }
                        }
                    }
                }
            });

        })
        
        
        $(document).on('click','.savepriceBtn22',function(e){
        e.preventDefault();
        
        var price = $(this).closest('tr').find('.priceVal2').val();
        var part_id = $(this).attr('part-id');
        var source_id = $(this).attr('source-id');
        var status_id = $(this).attr('status-id');
        var type_id = $(this).attr('type-id');
        var quality_id = $(this).attr('quality-id');
        var sale_type_id = $(this).attr('sale_type-id');
        

        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "saveprice1",
                data: {
                    'price' : price,
                    'part_id' : part_id,
                    'source_id' : source_id,
                    'status_id' : status_id,
                    'type_id' : type_id,
                    'quality_id' : quality_id,
                    'sale_type_id' : sale_type_id
                      },
                datatype: 'JSON',
                statusCode: {
                    404: function() {
                        alert("page not found");
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function(data) {
                    // console.log(data);
                    if (data) {
                        Swal.fire('تم التعديل بنجـــــــاح')
                    }
                   
                }
            });

    })
     
</script>



@endsection
