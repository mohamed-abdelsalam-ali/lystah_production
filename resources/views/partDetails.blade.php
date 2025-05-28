@extends('layouts.master')
{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

@section('css')

    <style>
        .collapsible {
          background-color: #777777a3;
          color: white;
          cursor: pointer;
          padding: 18px;
          width: 100%;
          border: none;
          text-align: center;
          outline: none;

        }

        .collapsible:hover {
          background-color: #555;
        }


    </style>
@endsection
@section('title')
    Items Details
@stop


@section('content')



    <div class="main-content">
        <div class="page-content">
            <div>
                <h1 class="text-center">يمكن التعديل عن طريق كتابة الرقم الجديد في الخانات الزرقاء وظغط ENTER </h1>
            </div>
            <div class="row">
                
                <div class="col-lg-12 table-responsive">
                    <h1>فواتير الشراء <span class="text-bg-warning text-dark px-2 rounded-3" id="allpartCount">0</span></h1>
                    <table id="allparttable" class="table">
                        <thead class="table-success">
                            <tr>
                                <td>allpart_id</td>
                                <td class="d-none">part_id</td>
                                <td>part</td>
                                <td class="d-none">source_id</td>
                                <td>source</td>
                                <td class="d-none">status_id</td>
                                <td>status</td>
                                <td class="d-none">quality_id</td>
                                <td>quality</td>
                                <td>amount</td>
                                <td>remain_amount</td>
                                <td class="d-none">order_supplier_id</td>
                                <td>supplier</td>
                                <td>transaction_id</td>
                                <td class="d-none">order_supplier_supplier_id</td>
                                <td class="d-none">order_supplier_currency_id</td>
                                <td class="d-none">order_supplier_total_price</td>
                                <td class="d-none">order_supplier_bank_account</td>
                                <td class="d-none">order_supplier_pricebeforeTax</td>
                                <td class="d-none">order_supplier_coast</td>
                                <td class="d-none">order_supplier_taxInvolved_flag</td>
                                <td class="d-none">order_supplier_taxkasmInvolved_flag</td>
                                <td class="d-none">order_supplier_user_id</td>


                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <h2>المخازن <span class="text-bg-warning text-dark px-2 rounded-3" id="allstoreCount">0</span></h2>
                    <table id="storetable" class="table">
                        <thead class="table-success">
                            <tr>
                                <td>id</td>
                                <td>part_id</td>
                                <td>supplier_order_id</td>
                                <td>amount</td>
                                <td>store_log_id</td>
                                <td>type_id</td>
                                <td>date</td>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                    <h2>فواتير البيع <span class="text-bg-warning text-dark px-2 rounded-3" id="allinvoiceCount">0</span></h2>
                    <table id="invoicetable" class="table d-none">
                        <thead class="table-success">
                            <tr>
                                <td>id</td>
                                <td>date</td>
                                <td>amount</td>
                                <td>invoice_id</td>
                                <td>sale_type</td>
                                <td>discount</td>
                                <td>over_price</td>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <h2> التالف <span class="text-bg-warning text-dark px-2 rounded-3" id="alltalefCount">0</span></h2>
                    <table id="taleftable" class="table">
                        <thead class="table-success">
                            <tr>
                                <td>id</td>
                                <td>date</td>
                                <td >amount</td>
                                <td>user_id</td>
                                <td>notes</td>
                                <td>store.name</td>

                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <h2>الأقسام <span class="text-bg-warning text-dark px-2 rounded-3" id="allSecCount">0</span></h2>
                    <table id="sectiontable" class="table">
                        <thead class="table-success">
                            <tr>
                                <td>id</td>
                                <td>date</td>
                                <td >amount</td>
                                <td>store</td>
                                <td>store_structure</td>
                                <td>order_supplier_id</td>

                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

           
        </div>
    </div>

   
@endsection

@section('js')
   
    <script>
        
        var data = {!! json_encode($data) !!};
        var allpart = data.data;
        var allstoress = data.allStores;
        var allinvoices = data.invoices;
        var alltalef = data.talef;
        var allsections = data.sections;
       
        $(document).ready(function() {
            // var allpart = response.data;
           

            var allpartAmount = 0;
            var allstoressAmount = 0;
            var allinvoicesAmount = 0;
            var alltalefAmount = 0;
            var allsectionsAmount = 0;

            $("#allparttable tbody").empty();
            $("#storetable tbody").empty();
            $("#invoicetable tbody").empty();
            $("#taleftable tbody").empty();
            $("#sectiontable tbody").empty();

            if(allpart.length > 0){
                for (let i = 0; i < allpart.length; i++) {
                    const element = allpart[i];
                    allpartAmount +=parseInt(element.amount);
                    if(element.type_id == 1){
                        $("#allparttable tbody").append(`
                        <tr>
                                <td>${element.id}</td>
                                <td class="d-none">${element.part_id}</td>
                                <td>${element.part.name}</td>
                                <td class="d-none">${element.source_id}</td>
                                <td>${element.source.name_arabic}</td>
                                <td class="d-none">${element.status_id}</td>
                                <td>${element.status.name}</td>
                                <td class="d-none">${element.quality_id}</td>
                                <td>${element.part_quality.name}</td>
                                <td class="text-bg-danger"> <input class="form-control-plaintext m-0 p-0 editAmountt" data-table_name="all_parts" data-fielsName="amount" data-id="${element.id}" type="number" name="" id="" value="${element.amount}"></td>
                                <td class="text-bg-info"><input class="form-control-plaintext m-0 p-0 editAmountt" data-table_name="all_parts" data-fielsName="remain_amount" data-id="${element.id}" type="number" name="" id="" value="${element.remain_amount}"></td>
                                <td class="d-none">${element.order_supplier.id}</td>
                                <td>${element.order_supplier.supplier.name}</td>
                                <td>${element.order_supplier.transaction_id}</td>
                                <td class="d-none">${element.order_supplier.supplier_id}</td>
                                <td class="d-none">${element.order_supplier.currency_id}</td>
                                <td class="d-none">${element.order_supplier.total_price}</td>
                                <td class="d-none">${element.order_supplier.bank_account}</td>
                                <td class="d-none">${element.order_supplier.pricebeforeTax}</td>
                                <td class="d-none">${element.order_supplier.coast}</td>
                                <td class="d-none">${element.order_supplier.taxInvolved_flag}</td>
                                <td class="d-none">${element.order_supplier.taxkasmInvolved_flag}</td>
                                <td class="d-none">${element.order_supplier.user_id}</td>


                        </tr>
                    `)
                    }else if(element.type_id == 2){
                        $("#allparttable tbody").append(`
                        <tr>
                                <td>${element.id}</td>
                                <td class="d-none">${element.part_id}</td>
                                <td>${element.wheel.name}</td>
                                <td class="d-none">${element.source_id}</td>
                                <td>${element.source.name_arabic}</td>
                                <td class="d-none">${element.status_id}</td>
                                <td>${element.status.name}</td>
                                <td class="d-none">${element.quality_id}</td>
                                <td>${element.part_quality.name}</td>
                                <td class="text-bg-danger"> <input class="form-control-plaintext m-0 p-0 editAmountt" data-table_name="all_wheels" data-fielsName="amount" data-id="${element.id}" type="number" name="" id="" value="${element.amount}"></td>
                                <td class="text-bg-info"><input class="form-control-plaintext m-0 p-0 editAmountt" data-table_name="all_wheels" data-fielsName="remain_amount" data-id="${element.id}" type="number" name="" id="" value="${element.remain_amount}"></td>
                                <td class="d-none">${element.order_supplier.id}</td>
                                <td>${element.order_supplier.supplier.name}</td>
                                <td>${element.order_supplier.transaction_id}</td>
                                <td class="d-none">${element.order_supplier.supplier_id}</td>
                                <td class="d-none">${element.order_supplier.currency_id}</td>
                                <td class="d-none">${element.order_supplier.total_price}</td>
                                <td class="d-none">${element.order_supplier.bank_account}</td>
                                <td class="d-none">${element.order_supplier.pricebeforeTax}</td>
                                <td class="d-none">${element.order_supplier.coast}</td>
                                <td class="d-none">${element.order_supplier.taxInvolved_flag}</td>
                                <td class="d-none">${element.order_supplier.taxkasmInvolved_flag}</td>
                                <td class="d-none">${element.order_supplier.user_id}</td>


                        </tr>
                    `)
                    }else if(element.type_id == 6){
                        $("#allparttable tbody").append(`
                        <tr>
                                <td>${element.id}</td>
                               <td class="d-none">${element.part_id}</td>
                                <td>${element.kit.name}</td>
                                <td class="d-none">${element.source_id}</td>
                                <td>${element.source.name_arabic}</td>
                                <td class="d-none">${element.status_id}</td>
                                <td>${element.status.name}</td>
                                <td class="d-none">${element.quality_id}</td>
                                <td>${element.part_quality.name}</td>
                                <td class="text-bg-danger"> <input class="form-control-plaintext m-0 p-0 editAmountt" data-table_name="all_kits" data-fielsName="amount" data-id="${element.id}" type="number" name="" id="" value="${element.amount}"></td>
                                <td class="text-bg-info"><input class="form-control-plaintext m-0 p-0 editAmountt" data-table_name="all_kits" data-fielsName="remain_amount" data-id="${element.id}" type="number" name="" id="" value="${element.remain_amount}"></td>
                                <td class="d-none">${element.order_supplier.id}</td>
                                <td>${element.order_supplier.supplier.name}</td>
                                <td>${element.order_supplier.transaction_id}</td>
                                <td class="d-none">${element.order_supplier.supplier_id}</td>
                                <td class="d-none">${element.order_supplier.currency_id}</td>
                                <td class="d-none">${element.order_supplier.total_price}</td>
                                <td class="d-none">${element.order_supplier.bank_account}</td>
                                <td class="d-none">${element.order_supplier.pricebeforeTax}</td>
                                <td class="d-none">${element.order_supplier.coast}</td>
                                <td class="d-none">${element.order_supplier.taxInvolved_flag}</td>
                                <td class="d-none">${element.order_supplier.taxkasmInvolved_flag}</td>
                                <td class="d-none">${element.order_supplier.user_id}</td>


                        </tr>
                    `)
                    }


                    // $("#allparttable tbody").append(`
                    //         <tr class="text-danger text-bg-dark">
                    //             <td>store_log_id</td>
                    //             <td>All_part_id</td>
                    //             <td>store_action_id</td>
                    //             <td>store_id</td>
                    //             <td>amount</td>
                    //             <td>status</td>
                    //             <td>type_id</td>
                    //             <td>date</td>

                    //         </tr>
                    //     `);
                    // element.store_log.forEach(log => {

                    //     $("#allparttable tbody").append(`
                    //         <tr class="">
                    //             <td>${log.id}</td>
                    //             <td>${log.All_part_id}</td>
                    //             <td>${log.store_action_id}</td>
                    //             <td>${log.store_id}</td>
                    //             <td class="text-bg-info"> <input class="editAmountt form-control-plaintext m-0 p-0" data-table_name="stores_log" data-fielsName="amount" data-id="${log.id}"  type="number" name="" id="" value="${log.amount}"> </td>
                    //             <td>${log.status}</td>
                    //             <td>${log.type_id}</td>
                    //             <td>${log.date}</td>

                    //         </tr>
                    //     `);
                    // });



                }
            }

            if(allstoress.length > 0){
                allstoress.forEach(store => {
                    $("#storetable tbody").append(`
                            <tr >
                                <td class="text-right" colspan='7'>${store.store.name}</td>

                            </tr>
                        `);
                    store.data.forEach(x => {
                        allstoressAmount +=x.amount;
                        $("#storetable tbody").append(`
                            <tr>
                                <td>${x.id}</td>
                                <td>${x.part_id}</td>
                                <td>${x.supplier_order_id}</td>
                                <td class="text-bg-info"> <input class="editAmountt form-control-plaintext m-0 p-0" data-table_name="${store.store.table_name}" data-fielsName="amount" data-id="${x.id}" type="number" name="" id="" value="${x.amount}"></td>
                                <td>${x.store_log_id}</td>
                                <td>${x.type_id}</td>
                                <td>${x.date}</td>
                            </tr>
                        `);
                    });
                });
            }

            if(allinvoices.length > 0){
                allinvoices.forEach(inv => {
                    allinvoicesAmount += inv.amount;
                    $("#invoicetable tbody").append(`
                            <tr>
                                <td>${inv.id}</td>
                                <td>${inv.date}</td>
                                <td class="text-bg-info"><input class="editAmountt form-control-plaintext m-0 p-0" data-table_name="invoice_items" data-fielsName="amount" data-id="${inv.id}" type="number" name="" id="" value="${inv.amount}"> </td>
                                <td>${inv.invoice_id}</td>
                                <td>${inv.sale_type}</td>
                                <td>${inv.discount}</td>
                                <td>${inv.over_price}</td>
                            </tr>
                        `);

                        inv.invoice_item_order_suppliers.forEach(xx => {
                            $("#invoicetable tbody").append(`
                                <tr class="bg-danger">
                                    <td>${xx.id}</td>
                                    <td>${xx.created_at}</td>
                                    <td class="text-bg-info"><input class="editAmountt form-control-plaintext m-0 p-0" data-table_name="invoice_items_ordersupplier" data-fielsName="amount" data-id="${xx.id}" type="number" name="" id="" value="${xx.amount}"> </td>
                                    <td>OSID : ${xx.order_supplier_id}</td>

                                </tr>
                            `);
                        });

                        inv.invoice_item_section.forEach(xx => {
                            $("#invoicetable tbody").append(`
                                <tr class="bg-danger">
                                    <td>${xx.id}</td>
                                    <td>${xx.created_at}</td>
                                    <td class="text-bg-info"><input class="editAmountt form-control-plaintext m-0 p-0" data-table_name="invoice_items_ordersupplier" data-fielsName="amount" data-id="${xx.id}" type="number" name="" id="" value="${xx.amount}"> </td>
                                    <td>Section : ${xx.store_structure.name}</td>

                                </tr>
                            `);
                        });



                            if( inv.refund.length > 0){
                            $("#invoicetable tbody").append(`
                                <tr class="text-bg-dark">
                                    <td>refund_id</td>
                                    <td>item_id</td>
                                    <td>r_amount</td>
                                    <td>item_price</td>
                                    <td>r_tax</td>
                                    <td>r_discount</td>
                                    <td>invoice_id</td>
                                </tr>
                            `);    
                        }
                        inv.refund.forEach(ref => {
                            allinvoicesAmount -=ref.r_amount;
                            $("#invoicetable tbody").append(`
                                <tr class="text-bg-success">
                                    <td>${ref.id}</td>
                                    <td>${ref.item_id}</td>
                                    <td class="text-bg-info"><input class="editAmountt form-control-plaintext m-0 p-0"  data-table_name="refund_invoice" data-fielsName="amount" data-id="${ref.id}"   type="number" name="" id="" value="${ref.r_amount}"></td>
                                    <td>${ref.item_price}</td>
                                    <td>${ref.r_tax}</td>
                                    <td>${ref.r_discount}</td>
                                    <td>${ref.invoice_id}</td>
                                </tr>
                            `);
                        });
                })
            }

            if(alltalef.length > 0){
                alltalef.forEach(x => {
                    alltalefAmount +=x.amount;
                    $("#taleftable tbody").append(`
                            <tr>
                                <td>${x.id}</td>
                                <td>${x.date}</td>
                                <td class="text-bg-info"><input class="editAmountt form-control-plaintext m-0 p-0"   data-table_name="talef" data-fielsName="amount" data-id="${x.id}"  type="number" name="" id="" value="${x.amount}"> </td>
                                <td>${x.user_id}</td>
                                <td>${x.notes}</td>
                                <td>${x.store.name}</td>

                            </tr>
                        `);


                })
            }

            if(allsections.length > 0){
                allsections.forEach(sec => {
                    allsectionsAmount +=sec.amount;
                    $("#sectiontable tbody").append(`
                            <tr>
                                <td>${sec.id}</td>
                                <td>${sec.date}</td>
                                <td class="text-bg-info"> <input class="editAmountt form-control-plaintext m-0 p-0" data-table_name="store_section" data-fielsName="amount" data-id="${sec.id}" type="number" name="" id="" value="${sec.amount}"></td>
                                <td>${sec.store.name}</td>
                                <td>${sec.store_structure.name}</td>
                                <td>${sec.order_supplier_id}</td>
                            </tr>
                        `);


                })
            }

            $("#allpartCount").text(allpartAmount);
            $("#allstoreCount").text(allstoressAmount);
            $("#allinvoiceCount").text(allinvoicesAmount);
            $("#alltalefCount").text(alltalefAmount);
            $("#allSecCount").text(allsectionsAmount);
        })

        $(document).on('keypress','.editAmountt',function(e) {
            if (e.keyCode == 13) {
                var tablename = $(this).attr('data-table_name');
                var tid = $(this).attr('data-id');
                var fieldName = $(this).attr('data-fielsName');
                var newAmount = $(this).val();

                Swal.fire({
                        text: "برجاء التأكد قبل التعديل , هل تريد الحفظ",
                        icon: "info",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Yes, حفظ!",
                        cancelButtonText: "No, الغاء",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton: "btn fw-bold btn-active-light-primary"
                        }
                        }).then(function(result) {
                        if (result.value) {
                            // alert('Under Construction');

                            $.ajax({
                                url: '/UpdateAmountss',
                                type: 'POST',
                                data : {
                                    'tablename' : tablename ,
                                    'fieldName' : fieldName ,
                                    'tid' : tid,
                                    'newAmount' : newAmount,
                                    '_token': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {

                                    if(response){
                                        alert("تم التعديل بنجاح");

                                    }else{
                                        alert("برجاء المحاولة مرة أخري");
                                    }

                                },
                                error: function(error) {
                                    console.error('Error:', error);
                                }
                            });

                        } else if (result.dismiss === 'cancel') {

                        }
                });

                return false;
            }
        })
    </script>
  




@endsection
