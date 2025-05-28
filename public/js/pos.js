
        $(".tableOverlay").removeClass("d-none");

        // console.log(store_data);
        var partsDt;
        var table = "";
        var table2 = "";
        var table2loaded = 0;

        $(document).ready(function() {


            $('#clientSlct').select2({
                tags: true ,
                dropdownParent: $("#example11Modal")
            });

            /***************************************************************************/

            /*******************************************************************************/
            $("#brandSlct").select2();
            $("#supplierSlct").select2();
            $("#groupSlct").select2();
            // $('#example thead tr')
                // .clone(true)
                // .addClass('filters')
                // .appendTo('#example thead');
            //Only needed for the filename of export files.
            //Normally set in the title tag of your page.

            // document.title = "Parts";

            partsDt = $("#example").DataTable({
                // dom: "Bfrtip",

                dom: '<"dt-buttons"Bf><"clear">lirtp',
                paging: true,
                autoWidth: true,
                orderCellsTop: true,
                fixedHeader: true,
                autoWidth: true,
                recordsDisplay : false,

                "search": {
                    "search": "*****"
                  },
                  "language": {
                    "zeroRecords": "برجاء اإستخدام البحث لأظهار البيانات"
                  },
                buttons: [
                    // "colvis",
                    "copyHtml5",
                    "csvHtml5",
                    "excelHtml5",
                    "pdfHtml5",
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [1,2,3,4,5,6]
                        }
                    },
                    {
                        'text': '<i class="bx bx-grid-vertical" aria-hidden="true"></i>',
                        'action': function (e, dt, node) {

                           $(dt.table().node()).toggleClass('cards');
                           $('.fa', node).toggleClass(['bx-table', 'bx-grid-vertical']);
                           dt.draw('page');
                        },
                        'className': 'btn-sm',
                        'attr': {
                           'title': 'Change views',
                        }
                    }
                ],
                layout: {
                    topStart: {
                        buttons: ['colvis']
                    }
                },
                initComplete: function(settings, json) {


                    $(".dt-buttons .btn-group").append(
                        '<a id="cv" class="btn bg-light" href="#">Card View</a>'
                    );
                    $(".dt-buttons .btn-group").append(
                        `<button type="button" class="btn btn-light" onclick="partsDt.columns().search('').draw();">الغاء البحث</button>`
                    );
                    $("#cv").on("click", function() {
                        if ($("#example").hasClass("card")) {
                            $(".colHeader").remove();
                        } else {
                            var labels = [];
                            $("#example thead th").each(function() {
                                labels.push($(this).text());
                            });
                            $("#example tbody tr").each(function() {
                                $(this)
                                    .find("td")
                                    .each(function(column) {
                                        $("<span class='colHeader'>").prependTo(
                                            $(this)
                                        );
                                    });
                            });
                        }
                        $("#example").toggleClass("card");
                    });


                    var api = this.api();

                    // For each column
                    api.columns().eq(0).each(function(colIdx) {
                        // Set the header cell to contain the input element
                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );

                        var title = $(cell).text();
                        $(cell).html('<input type="text" placeholder="' + title + '" />');

                        // On every keypress in this input
                        $(
                                'input',
                                $('.filters th').eq($(api.column(colIdx).header()).index())
                            )
                            .off('keyup change')
                            .on('change', function(e) {
                                // Get the search value
                                $(this).attr('title', $(this).val());
                                var regexr =
                                    '({search})'; //$(this).parents('th').find('select').val();

                                var cursorPosition = this.selectionStart;
                                // Search the column for that value
                                api
                                    .column(colIdx)
                                    .search(
                                        this.value != '' ?
                                        regexr.replace('{search}', '(((' + this.value +
                                            ')))') :
                                        '',
                                        this.value != '',
                                        this.value == ''
                                    )
                                    .draw();
                            })
                            .on('keyup', function(e) {
                                e.stopPropagation();

                                $(this).trigger('change');
                                $(this)
                                    .focus()[0]
                                    .setSelectionRange(cursorPosition, cursorPosition);
                            });
                    })





                },
                "columnDefs": [{
                        "visible": true,
                        "targets": 0

                    },
                    {
                        "visible": false,
                        "targets": 3
                    },
                    {
                        "visible": false,
                        "targets": 1
                    },
                    {
                        "visible": true,
                        "targets": 6
                    },
                    {
                        "visible": true,
                        "targets": 7
                    },
                    {
                        "visible": false,
                        "targets": 8
                    },
                    {
                        "visible": false,
                        "targets": 9
                    },
                    {
                        "visible": false,
                        "targets": 10
                    },
                    {
                        "visible": false,
                        "targets": 11
                    },
                    {
                        "visible": false,
                        "targets": 12
                    },
                    {
                        "visible": false,
                        "targets": 13
                    },
                    {
                        "visible": false,
                        "targets": 14
                    },
                    {
                        "visible": true,
                        "width" : "10%",
                        "targets": 16
                    },{
                        "visible": true,
                        "targets": 15
                    }
                ]

            });


            $(".tableOverlay").addClass("d-none");

            partsDt.search('***').draw();
            /**************************************************************************/
            /////////////////////////////////////////////////////////////////////////////


            table = $('#transtbl').DataTable({
                dom: '<"dt-buttons"Bf><"clear">lirtp',
                paging: false,
                autoWidth: true,
                orderCellsTop: true,
                fixedHeader: true,
                buttons: [
                    "colvis",
                    "copyHtml5",
                    "csvHtml5",
                    "excelHtml5",
                    "pdfHtml5",
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [1, 4]
                        }
                    },
                ],
                ajax: "inboxStore/list/" +store_data[0].id,
                // async:false,
                columns: [

                    {
                        data: "stores_log_id",
                        "visible": false
                    },
                    {
                        data: 'part_id',
                        name: 'part_id',
                        "visible": false
                    },
                    {
                        data: 'All_part_id',
                        name: 'All_part_id',
                        "visible": false
                    },

                    {
                        data: 'part_name',
                        name: 'part_name'
                    },
                    {
                        data: 'source_name',
                        name: 'source_name'
                    },
                    {
                        data: 'source_id',
                        name: 'source_id',
                        "visible": false
                    },
                    {
                        data: 'staus_name',
                        name: 'staus_name'
                    },
                    {
                        data: 'status_id',
                        name: 'status_id',
                        "visible": false
                    },
                    {
                        data: 'quality_name',
                        name: 'quality_name'
                    },


                    {
                        data: 'quality_id',
                        name: 'quality_id',
                        "visible": false
                    },

                    {
                        data: 'type.name',
                        name: 'type_name'
                    },


                    {
                        data: 'type.id',
                        name: 'type_id',
                        "visible": false
                    },

                    {
                        data: 'store_action.name',
                        name: 'store_action_name'
                    },
                    {
                        data: 'store_action.id',
                        name: 'store_action_id',
                        "visible": false
                    },
                    {
                        data: 'trans_amount',
                        name: 'trans_amount'
                    },

                    {
                        data: 'store.name',
                        name: 'to_store'
                    },
                    {
                        data: 'store.id',
                        name: 'to_store_id',
                        "visible": false
                    },
                    {
                        data: 'date',
                        name: 'trans_date',
                        render: function(data, type, row) {

                            return data.split('T')[0];

                        }
                    },
                    {
                        data: 'sup_name',
                        name: 'sup_name'
                    },
                    {
                        data: "status",
                        name: 'status',
                        defaultContent: "-",


                        render: function(data, type, row) {
                            if (type === 'display' || type === 'filter') {

                                var statusword = '';
                                var statusclass = '';
                                // alert(data);
                                switch (data) {

                                    case -1:
                                        statusword = 'مرفوضة';
                                        statusclass = 'light-dark';
                                        break;
                                    case 0:
                                        statusword = 'منتظر إستلام';
                                        statusclass = 'light-dark';
                                        break;
                                    case 1:
                                        statusword = 'تم الإستلام';
                                        statusclass = 'light-dark';
                                        break;
                                    case 2:
                                        statusword = 'منتظر تأكيد';
                                        statusclass = 'light-dark';
                                        break;

                                }


                                return '<span name="statusword" class = "badge badge-pill bg-success badge-' +
                                    statusclass + '">' + statusword + '</span>';

                            }
                            return data;
                        }
                    },
                    {
                        data: 'notes',
                        name: 'notes'
                    },
                    {
                        data: null,
                        className: "dt-center editor-edit1 ",
                        defaultContent: '<input  type="number" name="acceptedamount" class="form-controle acceptedamount bind_this w-100" placeholder="الكمية">',
                        orderable: false
                    },
                    {
                        // data: null,
                        // className: "text-center",

                        // render: function(data, type, row) {
                        //     data = `
                    //     <a href="javascript:void(0)" class="edit btn btn-success btn-sm" onclick="Confirm_transaction(this)">Save</a>
                    //     `;
                        //     return data;
                        // }
                        data: null,
                        className: "dt-center editor-edit",
                        defaultContent: ' <a href="javascript:void(0)" class="edit btn  btn-sm" style="background-color:#38598b" onclick="Confirm_transaction(this)">Save</a>',
                        orderable: false


                    },



                ]

            });

                  table2 = $('#storetbl').DataTable({
                dom: '<"dt-buttons"Bf><"clear">lirtp',
                paging: false,
                autoWidth: true,
                orderCellsTop: true,
                fixedHeader: true,
                processing : true,
                "language": {
                    "zeroRecords": "جاري تحميــــــــــــــــــــــــــــل البيانات ...."
                  },
                buttons: [
                    "colvis",
                    "copyHtml5",
                    "csvHtml5",
                    "excelHtml5",
                    "pdfHtml5",
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [1, 4]
                        }
                    },
                ],
                // ajax: "itemsStore/list/" + store_data[0].id,
                // async:false,
                columns: [


                    {
                        data: "part_id",

                    },
                    {
                        data: "store_log_id",
                        "visible": false
                    },
                    {
                        data: "supplier_order_id",
                        "visible": false
                    },
                    {
                        data: "type_id",
                        "visible": false
                    },
                    // { data: "All_part_id", "visible": false },
                    {
                        data: "p_data.name"
                    },
                    {
                        data: "type_N"
                    },
                    {
                        data: "source[0].name_arabic"
                    },
                    {
                        data: "source_id",
                        "visible": false
                    },
                    {
                        data: "status[0].name"
                    },
                    {
                        data: "status_id",
                        "visible": false
                    },
                    {
                        data: "quality[0].name"
                    },
                    {
                        data: "quality_id",
                        "visible": false
                    },
                    {
                        data: "Tamount"
                    },
                    {
                        data: null,
                        className: "dt-center editor-send1 ",
                        defaultContent: '<input  type="number" id="" class="form-controle send_amount" placeholder="الكمية">',
                        orderable: false
                    },
                    {
                        data: null,
                        className: "dt-center",
                        defaultContent: '<select class="form-control m-b select2  stores_namedrp" id="" name=""><option>Select Store</option></select>',
                        render: function(data, type, row) {
                            if (type === 'display' || type === 'filter') {

                                store_drp = [];
                                store_drp.push(`<option value=''>Select Store</option>`)

                                for (let i = 0; i < allStores.length; i++) {
                                    if (allStores[i].id == store_data[0].id) {

                                    } else {
                                        store_drp.push(
                                            `<option value="${allStores[i].id}">${allStores[i].name}</option>`
                                        )

                                    }
                                }
                                $('.stores_namedrp').html(store_drp);

                                // $('.stores_namedrp').select2();

                            }
                            return data;
                        }
                    },

                    {
                        data: null,
                        className: "dt-center editor-send",

                        defaultContent: '<a href="javascript:void(0)" class="btn-info btn btn-xs " onclick="send_to_other(this)">Send</button>',
                        orderable: false
                    }
                    // ,
                    // {
                    //     data: null,
                    //     className: "dt-center editor-send",
                    //     defaultContent: '<button  type="button" id="" class="btn-success btn btn-xs ">Need</button>',
                    //     orderable: false
                    // }


                ]

            });

        reloadCart();
        });
            $('#storemodal').on('click',function(){
                $('#storeMdl').modal('show');

                // if (table2 instanceof $.fn.dataTable.Api) {
                //     table2.destroy()
                // }else{

                // }

                if(table2loaded == 0){
                 $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "GET",
                        //ajax: "itemsStore/list/" + store_data[0].id,
                        url: "itemsStore/list/"+ store_data[0].id,

                        datatype: 'JSON',
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
                            table2.clear();
                            if (data) {
                                $('#storetbl').DataTable();
                                // table2.rows().add(data);
                                table2.rows.add(data.data).draw();

                                // $('#example').DataTable().ajax.reload();
                            }
                            // SuccessAlert("Transaction Accepted" )

                            // getAllNewParts();
                            // getAllNewParts_inbox();
                            // $('.nav-tabs a[href="#tab-3"]').tab('show');

                            // var resArr1 = $.parseJSON(data);
                            // console.log(resArr1);

                            // arr_partNo_html = draw(resArr1['Last_Amount'], 'Last_Amount');
                            // $(".Last_Amount").html(arr_partNo_html);


                        }
                    });
                table2loaded =1;
                }

            });

        function send_to_other(el) {

            var row_clicked = $(el).closest('tr');
            var row_object = table2.row(row_clicked).data();
            console.log(row_object)

            delete row_object.models;
            delete row_object.image;
            delete row_object.stores;
            delete row_object.models;
            delete row_object.section;
            delete row_object.price;
            delete row_object.quality;
            delete row_object.source;
            delete row_object.status;
            delete row_object.date;

            // var row_accept_amount = table.row(row_clicked).node();
            var row_accept_amount = parseInt(row_clicked.find('.send_amount').val());
            var stores_namedrp = row_clicked.find('.stores_namedrp').val();
            var actual_amount = parseInt(row_object.Tamount);

            if (row_accept_amount <= actual_amount) {
                if (stores_namedrp != '') {

                    flages = 1;
                    Swal.fire('Transaction Accepted');
                    send_to_other_store(row_object, row_accept_amount, stores_namedrp);

                } else {
                    flages = 2;
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'wrong Store',
                        footer: 'Select Store First'
                    });
                }



            } else {
                flages = 2;
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'wrong amount',
                    footer: 'Enter Right Value In amount'
                })

            }
            // if (flages != 2) {
            //     if (row_object.store_action_id == 1 || row_object.store_action_id == 3 && row_object.status == 2) {
            //         $.ajax({
            //             headers: {
            //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //             },
            //             type: "POST",
            //             url: "{{ route('confirmStore') }}",
            //             data: {
            //                 'data': {
            //                     'Store_log_id': row_object.stores_log_id,
            //                     'store_table_name': row_object.store.table_name,
            //                     'store_action_id': row_object.store_action_id,
            //                     'all_p_id': row_object.All_part_id,
            //                     'accamount': row_accept_amount,
            //                     'type_id': row_object.type_id,
            //                     'store_id': row_object.store_id,
            //                     'flag_completed': flages,
            //                     'actual_amount': actual_amount
            //                 }



            //             },
            //             datatype: 'JSON',
            //             statusCode: {
            //                 404: function() {
            //                     alert("page not found");
            //                 }
            //             },
            //             error: function(XMLHttpRequest, textStatus, errorThrown) {
            //                 // alert("some error");
            //                 console.log(errorThrown);
            //             },
            //             success: function(data) {
            //                 console.log(data);

            //                 if (data) {
            //                     $('#transtbl').DataTable().ajax.reload();
            //                     // $('#example').DataTable().ajax.reload();
            //                 }
            //                 // SuccessAlert("Transaction Accepted" )

            //                 // getAllNewParts();
            //                 // getAllNewParts_inbox();
            //                 // $('.nav-tabs a[href="#tab-3"]').tab('show');

            //                 // var resArr1 = $.parseJSON(data);
            //                 // console.log(resArr1);

            //                 // arr_partNo_html = draw(resArr1['Last_Amount'], 'Last_Amount');
            //                 // $(".Last_Amount").html(arr_partNo_html);


            //             }
            //         });
            //     } else {
            //         alert("xxxxxxx");
            //     }
            // }




        }

        function send_to_other_store(P_details, sent_amount, other_store_id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "/sendToOtherStore",
                data: {
                    'data': {
                        'P_details': P_details,
                        'sent_amount': sent_amount,
                        'other_store_id': other_store_id,
                        'store_id': store_data[0].id

                    }

                },
                datatype: 'JSON',
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
                    // console.log(data);
                    if (data == "empty") {

                    } else {
                        // resArr0 = $.parseJSON(data);


                        // inbox_amount = resArr0;
                        // $(".inbox_store").html(inbox_amount.length)
                        // console.log(resArr0);
                        // partStoreDT.clear();
                        // partStoreDT.rows.add(inbox_amount);
                        // partStoreDT.draw();
                    }




                    // resArr[i].id
                    // $(".notification .badge").html(notifications[0].notifications)





                }
            });
        }

        var flages = '';
        var store_idx;

        function Confirm_transaction(el) {

            var row_clicked = $(el).closest('tr');
            var row_object = table.row(row_clicked).data();
            // console.log(row_object.trans_amount)
            var row_accept_amount = table.row(row_clicked).node();
            var row_accept_amount = parseInt(row_clicked.find('.acceptedamount').val());
            var actual_amount = parseInt(row_object.trans_amount);
            store_idx = row_object.store_id;
            if (row_accept_amount == actual_amount) {
                flages = 1;
                Swal.fire('Transaction Accepted')

            } else if (row_accept_amount < actual_amount) {
                flages = 0;
                Swal.fire('Transaction Not Completed')
                Swal.fire(
                    'Warning !',
                    'Transaction Not Completed',
                    'The transaction still pendding ...'
                )

            } else {
                flages = 2;
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'wrong amount',
                    footer: 'Enter  Value In amount'
                })

            }
            if (flages != 2) {
                if (row_object.store_action_id == 1 || row_object.store_action_id == 3 && row_object.status == 0) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "confirmStore",
                        data: {
                            'data': {
                                'Store_log_id': row_object.stores_log_id,
                                'store_table_name': row_object.store.table_name,
                                'store_action_id': row_object.store_action_id,
                                'all_p_id': row_object.All_part_id,
                                'part_id': row_object.part_id,
                                'accamount': row_accept_amount,
                                'type_id': row_object.type_id,
                                'store_id': row_object.store_id,
                                'flag_completed': flages,
                                'actual_amount': actual_amount
                            }



                        },
                        datatype: 'JSON',
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

                            if (data) {
                                $('#transtbl').DataTable().ajax.reload();
                                // $('#example').DataTable().ajax.reload();
                            }
                            // SuccessAlert("Transaction Accepted" )

                            // getAllNewParts();
                            // getAllNewParts_inbox();
                            // $('.nav-tabs a[href="#tab-3"]').tab('show');

                            // var resArr1 = $.parseJSON(data);
                            // console.log(resArr1);

                            // arr_partNo_html = draw(resArr1['Last_Amount'], 'Last_Amount');
                            // $(".Last_Amount").html(arr_partNo_html);


                        }
                    });
                } else {
                    alert("xxxxxxx");
                }
            }




        }

        $('#example tbody tr').each(function() {
            JsBarcode($(this).find('.barcode')[0], $(this).find('.barcodeTxt').text());
        })
        var cardOptions=[];
        function addtoInvoice(el,part_specs=["nospecs"], partId, name, SourceId, StatusId, qualityId, PriceTypeId, price, totalAmount, type_id, Amount,priceList ,card=0) {
            // console.log($(el).closest('tr').find('.sectionList').parent().html());
            
            var options =[];

            // console.log(part_specs);
            var weight = 0 ;
            var Amount = 0 ;
            if(card=1){
                 Amount = $(el).siblings('input').val();
            }else{
                 Amount = $(el).closest('td').find('.itemAmunt').val();


            }
            if(type_id == 1){
                if(part_specs.length > 0){
                    var p_weight1={};
                    if(part_specs.find(a=>/وزن/.test(a.part_spec.name))){
                        p_weight1=part_specs.find(a=>/وزن/.test(a.part_spec.name));
                        weight = parseFloat(p_weight1.value).toFixed(4);
                    }
                }else{
                    weight=0;
                }
            }else if(type_id == 2){
                if(part_specs.length > 0){
                    var p_weight2={};
                    if(part_specs.find(a=> /وزن/.test(a.wheel_spec.name))){
                        p_weight2=part_specs.find(a=> /وزن/.test(a.wheel_spec.name));
                        weight = parseFloat(p_weight2.value).toFixed(4);
                    }
                }else{
                    weight=0;
                }
            }else if(type_id == 6){
                if(part_specs.length > 0){
                    var p_weight3={};
                    if(part_specs.find(a=>/وزن/.test(a.kit_spec.name))){
                        p_weight3=part_specs.find(a=>/وزن/.test(a.kit_spec.name));
                        weight = parseFloat(p_weight3.value).toFixed(4);
                    }
                }else{
                    weight=0;
                }
            }else if(type_id == 3){
                if(part_specs.length > 0){
                    var p_weight4={};
                    if(part_specs.find(a=>/وزن/.test(a.tractor_spec.name))){
                        p_weight4=part_specs.find(a=>/وزن/.test(a.tractor_spec.name));
                        weight = parseFloat(p_weight4.value).toFixed(4);
                    }
                }else{
                    weight=0;
                }
            }else if(type_id == 4){
                if(part_specs.length > 0){
                    var p_weight5={};
                    if(part_specs.find(a=>/وزن/.test(a.clark_spec.name))){
                        p_weight5=part_specs.find(a=>/وزن/.test(a.clark_spec.name));
                        weight = parseFloat(p_weight5.value).toFixed(4);
                    }
                }else{
                    weight=0;
                }
            }else if(type_id == 5){
                if(part_specs.length > 0){
                    var p_weight6={};
                    if(part_specs.find(a=>/وزن/.test(a.equip_spec.name))){
                        p_weight6=part_specs.find(a=>/وزن/.test(a.equip_spec.name));
                        weight = parseFloat(p_weight6.value).toFixed(4);
                    }
                }else{
                    weight=0;
                }
            }



            if(price== 0){
                Swal.fire({
                        text: "لا يوجد تسعيرة لهذا الصنف",
                        icon: "error",
                        showCancelButton: true,
                        buttonsStyling: false,
                        // confirmButtonText: "Yes, !",
                        // cancelButtonText: "No, الغاء",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton: "btn fw-bold btn-active-light-primary"
                        }
                        });
                return false;
            }

            if(Amount > totalAmount){
                Swal.fire({
                        text: "الكمية المطلوبة غير متاحة",
                        icon: "error",
                        showCancelButton: true,
                        buttonsStyling: false,
                        // confirmButtonText: "Yes, !",
                        // cancelButtonText: "No, الغاء",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton: "btn fw-bold btn-active-light-primary"
                        }
                        });
                return false;
            }

            if(Amount){

            }else{
                Amount = 1;
            }


              $(el).closest('.addBtn').addClass('d-none');


            var row = $(el).closest('tr');

            //console.log(priceList);
            var priceList1 =JSON.stringify(priceList);
            var indexRw = partsDt.row(row).index();
            // <td>${$(el).closest('tr').find('.sectionList').parent().html()}</td>

            if($(`#invoiceItems tr[data-val='${type_id}-${partId}-${SourceId}-${StatusId}-${qualityId}']`).length > 0 ){
                var xsx = $(`#invoiceItems tr[data-val='${type_id}-${partId}-${SourceId}-${StatusId}-${qualityId}']`).find('input.itemAmount').val();
                xsx = parseInt(xsx);
                $(`#invoiceItems tr[data-val='${type_id}-${partId}-${SourceId}-${StatusId}-${qualityId}']`).find('input.itemAmount').val(xsx+1).trigger('keyup');
            }else{
                 $("#basketCounterLbl").text(parseInt($("#basketCounterLbl").text())+1);
                 $("#invoiceItems").append(`
                <tr class="specscontainer" data-price='${priceList1}' data-val="${type_id}-${partId}-${SourceId}-${StatusId}-${qualityId}">

                    <td style="">${name}</td>

                    <td><input name="itemAmount[]"  class="form-control itemAmount p-1 text-center w-50" type="number" name="" value="${Amount}" min="0"  max="${totalAmount}" id=""></td>

                    <td class="itemPrice">${price}</td>
                    <td class="itemTotalPrice">${parseFloat(price) * parseFloat(Amount)}</td>
                    <td class="p-3" style="cursor: pointer;" onclick="removeItemFromInvoice(${indexRw},this)"><i class="mdi mdi-trash-can-outline p-1 rounded text-bg-danger"></i></td>
                    <td style="">${weight}</td>
                      <input type="hidden" name="items_part[]" value="${partId}-${SourceId}-${StatusId}-${qualityId}-${type_id}">
                        <input type="hidden" name="pricetype[]" value="${PriceTypeId}">
                        <input type="hidden" name="itemPrice[]" value="${price}">
                </tr>

                `);
            }


            calcTotal();
            calcWeight();
            
            
            
              options.push({'part_specs': JSON.stringify(part_specs) , 'partId' : partId,'name' : name
                ,'SourceId' : SourceId,'StatusId' : StatusId,'qualityId' : qualityId,'Amount' : Amount ,'weight' : weight,'PriceTypeId' : PriceTypeId
                ,'price' : price,'totalAmount' : totalAmount,'type_id' : type_id,'priceList' : priceList1,'card' : card});
            cardOptions.push(JSON.stringify(options))
            localStorage.setItem('cardOptions',cardOptions);

        }
        $(document).on('keyup', '.itemAmount', function(e) {
            var amount = $(this).val();;
            $(this).closest('tr').find('.itemTotalPrice').text(amount * $(this).closest('tr').find('.itemPrice')
                .text());
            calcTotal();

        });

          function reloadCart(){


            var cardsaleType = localStorage.getItem('cardsaleType') || 0;
            var cardClientId = localStorage.getItem('cardClientId') || 0;



            if (parseInt(cardClientId) >  0) {
                $("#clientSlct").val(cardClientId).trigger('change')
            } else {

            }
            var jsonSting = localStorage.getItem('cardOptions')
            jsonSting = '[' + jsonSting.split('],[').join(',') + ']';

            var storedData = JSON.parse(jsonSting) || [];
            if (storedData.length > 0){
                if (storedData[0].length === 0) {
                } else {
                    $("#invoiceItems").empty();
                    storedData[0].forEach(function(item, index) {
                        /************************************* */





                                $("#basketCounterLbl").text(parseInt($("#basketCounterLbl").text())+1);
                                $("#invoiceItems").append(`
                                <tr class="specscontainer" data-price='${item.priceList}' data-val="${item.type_id}-${item.partId}-${item.SourceId}-${item.StatusId}-${item.qualityId}">

                                    <td style="">${item.name}</td>

                                    <td><input name="itemAmount[]"  class="form-control itemAmount p-1 text-center w-50" type="number" name="" value="${item.Amount}" min="0"  max="${item.totalAmount}" id=""></td>

                                    <td class="itemPrice">${item.price}</td>
                                    <td class="itemTotalPrice">${parseFloat(item.price) * parseFloat(item.Amount)}</td>
                                    <td class="p-3" style="cursor: pointer;" onclick="removeItemFromInvoice(${item.indexRw},this)"><i class="mdi mdi-trash-can-outline p-1 rounded text-bg-danger"></i></td>
                                    <td style="">${item.weight}</td>
                                    <input type="hidden" name="items_part[]" value="${item.partId}-${item.SourceId}-${item.StatusId}-${item.qualityId}-${item.type_id}">
                                        <input type="hidden" name="pricetype[]" value="${item.PriceTypeId}">
                                        <input type="hidden" name="itemPrice[]" value="${item.price}">
                                </tr>

                                `);



                            calcTotal();
                            calcWeight();

                        /************************************* */
                    });
                }

            }

            // if (parseInt(cardsaleType) > 0) {
            //     $("#saleTypeSlct").val(cardsaleType).trigger('change');
            // } else {

            // }
            
             if (parseInt(cardsaleType) > 0 && storedData.length > 0) {
                $("#saleTypeSlct").val(cardsaleType).trigger('change');
            } else {
                $("#saleTypeSlct").val(5).trigger('change')
            }

        }
         function removeItemFromInvoice(rowindex, el) {
            $("#basketCounterLbl").text(parseInt($("#basketCounterLbl").text())-1);
            var partDetails = $(el).closest('tr').attr('data-val');
            var values = partDetails.split('-');

            var partId = parseInt(values[1]);
            var sourceId = parseInt(values[2]);
            var statusId = parseInt(values[3]);
            var qualityId = parseInt(values[4]);

            $(el).closest('tr').remove();
            $(partsDt.row(rowindex).nodes()[0]).find('.addBtn').removeClass('d-none');


            var jsonSting = localStorage.getItem('cardOptions')
            jsonSting = '[' + jsonSting.split('],[').join(',') + ']';
            var jsonObj = JSON.parse(jsonSting)
            var jsonObj = jsonObj[0];

            var jsonObjR=  jsonObj.filter(function( obj ) {
                return !(obj.partId === partId && obj.SourceId === sourceId && obj.StatusId === statusId &&obj.qualityId === qualityId)  ;
              });



            // console.log(jsonObjR);
            jsonObjR = JSON.stringify(jsonObjR)
            localStorage.setItem('cardOptions',jsonObjR)



            calcWeight()
            calcTotal();
        }
        $('[name="taxes[]"]').change(function() {
            calcTotal();
        })
        function calcWeight() {
            var total_weight = 0;
            $('#invoiceItems > tr').each(function() {
                let weight = parseFloat($(this).children().eq(5).html()) || 0;
                let amount = parseFloat($(this).children().eq(1).children().val()) || 1;
                total_weight += weight * amount;
            });

            $("#totalweight").text(total_weight.toFixed(4));

            $("#totalweighttxt").val(total_weight.toFixed(4));
        }
        function calcTotal() {
            calcWeight();
            var subtotal = 0;
            var taxval = 0;
            var total = 0;

            $('#invoiceItems > tr').each(function() {
                let price = parseFloat($(this).children().eq(3).html()) || 0;
                subtotal += price;
            });

            $("input[name='taxes[]']").each(function(index, obj) {
                if ($(obj).is(':checked')) {
                    var tax = parseFloat($(obj).val());
                    var subtax = ( subtotal * tax ) / 100;
                    taxval += subtax;

                }
            });
            $("#subtotal").text(subtotal);
            $("#taxval").text(taxval);
            $("#total").text(parseFloat(subtotal + taxval).toFixed(2));

            $("#subtotaltxt").val(subtotal);
            $("#taxvaltxt").val(taxval);
            $("#totaltxt").val(parseFloat(subtotal + taxval).toFixed(2));
            $("#invPaied").val('');
            $("#invDiscount").val('');
             $("#invMad").val('');

        }
        $("#invPaied").keyup(function(e) {
            var invPaied = $("#invPaied").val();
            var invTotal = $("#total").text();

            $("#invMad").val((invTotal - invPaied).toFixed(2));
        });

        $("#invDiscount").keyup(function(e) {
            var invPaied = $("#invPaied").val();
            var invTotal = $("#total").text();
            var invdiscount = $("#invDiscount").val();

            $("#invMad").val((invTotal - invPaied - invdiscount).toFixed(2));
        });

        function toggleFullScreen() {
            if (!document.fullscreenElement && // alternative standard method
                !document.mozFullScreenElement && !document.webkitFullscreenElement) { // current working methods
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                    document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
            } else {
                if (document.cancelFullScreen) {
                    document.cancelFullScreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                }
            }
        }

        $("#gardBtn").on("click", function() {
            partsDt.button('.buttons-print').trigger();
        });
        //
        $("#searchSectionTxt").keyup(function(e) {
            var searchValue = $(this).val();
            $('.resSec').hide();
            $('.resSec:contains("' + searchValue + '")').show();
        });
        $("#searchclientTxt").keyup(function(e) {
            var searchValue = $(this).val();
            $('.resclient').hide();
            $('.resclient:contains("' + searchValue + '")').show();
        });
        $("#newClient").click(function(e) {
            e.preventDefault();


        });

        function addNewClient(telNumber) {
            return $.ajax({
                type: "get",
                url: "newClientInline/" + telNumber
            });

        }
        $("#clientSlct").change(function(e) {
            
            
            e.preventDefault();
            localStorage.setItem('cardClientId',$(this).val());

            var selectedClient =0;
            var url=0;
            if($('option:selected', this).attr('data-sup_id')){
                 selectedClient = $('option:selected', this).attr('data-sup_id');
                 url = "/getRassedAll/all/"+selectedClient;
            }else{
                 selectedClient = $(this).val();
                 url = "/getRassedAll/client/"+selectedClient;
            }

            // /getRassedAll/{type}/{id}

            $.ajax({
                type: "GET",
                url: url,
                success: function (response) {
                     if(response){
                        $("#madClientTxt").text(response.message + ' '+ response.rassed)
                     }else{
                        $("#madClientTxt").text(0)
                     }
                }
            })

        });

        //         $(selectedBtn).trigger('click');
        //         // $(this).val('');
        //     }


        // });
        on_scanner() // init function

        function on_scanner() {
            // let is_event = false; // for check just one event declaration
            // let input = document.getElementById("input-to-fill");
            // input.addEventListener("focus", function() {
            //     if (!is_event) {
            //         is_event = true;
            //         input.addEventListener("keypress", function(e) {
            //             setTimeout(function() {
            //                 if (e.keyCode == 13) {
            //                     scanner(input.value); // use value as you need
            //                     // input.select().empty();
            //                 }
            //             }, 500)
            //         })
            //     }
            // });
            // document.addEventListener("keypress", function(e) {
            //     if (e.target.tagName !== "INPUT") {
            //         input.focus();
            //     }
            // });
        }

        function scanner(value) {
            if (value == '') return;
            console.log(value)
            partsDt.search(value).draw();

            if (partsDt.rows({
                    search: 'applied'
                }).count() == 1) {
                if ($("#invoiceItems tr[data-val='" + value + "']").find('input').length > 0) {
                    var currentVal = $("#invoiceItems tr[data-val='" + value + "']").find('input').val()
                    $("#invoiceItems tr[data-val='" + value + "']").find('input').val(parseInt(currentVal) + 1).trigger(
                        "change").keyup();
                } else {
                    // var selectedBtn = partsDt.('tr', {"filter":"applied"}).data()[0];
                    var selectedBtn = partsDt.row('tr', {
                        "filter": "applied"
                    }).data()[6];
                    $(selectedBtn).trigger('click');
                }


                // $(this).val('');
            }
        }

        $("#FilterTypeSlct").change(function(e) {
            e.preventDefault();
            partsDt.search($(this).val()).draw();
        });

        function DTsearch(columnIndex, el) {
            if($(el).val()==''){
                partsDt.search('****').draw();
            }else{
                partsDt.search('');
                partsDt.column(columnIndex).search($(el).val()).draw();
            }

        }

        function CardInfo(allpartId,typeId){

            $("#item-image-car").empty();
            $("#itemNumbers").empty();
            $("#itemSpecs").empty();
            $("#itemModels").empty();
            $("#itemStores").empty();
            $("#iteSps").empty();
            $(".itemPriceList").empty();
            $("#relatedpartDiv").empty();
            $("#relatedpartDiv1").empty();
            $("#itemGroup").empty();


            $.ajax({
                type: "GET",
                url: "CardInfo",
               data: {
            allpartId: allpartId,
                typeId:typeId
         },
                success: function (response) {
                    console.log(response);
                   if (response.allpart.length > 0) {
                var item = response.allpart[0];
                console.log(item);
                if (typeId == 1) {
                    var name = item.part.name;
                    var part_images = item.part.part_images;
                    var part_status = item.status.name;
                    var part_source = item.source.name_arabic;
                    var part_quality = item.part_quality.name;
                    var part_price = item.price;
                    var part_stores = item.stores;
                    var part_number =
                        "FN" +
                        "1" +
                        item.part_id +
                        item.source_id +
                        item.status_id +
                        item.quality_id;
                    var itemNumbers = item.part.part_numbers;
                    var itemSpecs = item.part.part_details;
                    var itemModels = item.part.part_models;
                    var itemGroup = item.part.sub_group;
                    var Srelatedpart = item.part.all_parts;
                    var relatedpart = item.part.related_parts;
                    var sale_price_id = 0;
                    var pricesv=[];
                    if(item.price.length > 0){
                        sale_price_id = item.price[0].sale_type.id;
                        pricesv = item.price[0].price;
                    }

                    $("#addtocardMdl").attr('onclick',`addtoInvoice(this,${JSON.stringify(itemSpecs)}, ${item.part_id},
                     ${JSON.stringify(item.part.name)}, ${item.source.id}, ${item.status.id}, ${item.part_quality.id},
                      ${sale_price_id}, ${JSON.stringify(pricesv)}, ${item.amount}, 1,1 , ${JSON.stringify(item.price)},1)`)
                }
                else if(typeId == 6){
                    var name = item.kit.name;
                    var part_images = item.kit.kit_images;
                    var part_status = item.status.name;
                    var part_source = item.source.name_arabic;
                    var part_quality = item.part_quality.name;
                    var part_price = item.price;
                    var part_stores = item.stores;
                    var part_number =
                        "FN" +
                        "6" +
                        item.part_id +
                        item.source_id +
                        item.status_id +
                        item.quality_id;
                    var itemNumbers = item.kit.kit_numbers;
                    var itemSpecs = item.kit.kit_details;
                    var itemModels = item.kit.kit_models;
                    var Srelatedpart = item.kit.all_kits;
                    // var relatedpart = item.kit.related_kits;
                    var itemGroup = [];
                    var relatedpart = [];
                    var sale_price_id = 0;
                    var pricesv=[];
                    if(item.price.length > 0){
                        sale_price_id = item.price[0].sale_type.id;
                        pricesv = item.price[0].price;
                    }
                    $("#addtocardMdl").attr('onclick',`addtoInvoice(this,${JSON.stringify(itemSpecs)}, ${item.part_id},
                    ${JSON.stringify(item.kit.name)}, ${item.source.id}, ${item.status.id}, ${item.part_quality.id},
                     ${sale_price_id}, ${JSON.stringify(pricesv)}, ${item.amount}, 6,1 , ${JSON.stringify(item.price)},1)`)

                }
                else if(typeId == 2){
                    var name = item.wheel.name;

                    var part_images = item.wheel.wheel_images;
                    var part_status = item.status.name;
                    var part_source = item.source.name_arabic;
                    var part_quality = item.part_quality.name;
                    var part_price = item.price;
                    var part_stores = item.stores;
                    var part_number =
                        "FN" +
                        "2" +
                        item.part_id +
                        item.source_id +
                        item.status_id +
                        item.quality_id;
                    var itemNumbers = [];
                    var itemSpecs = item.wheel.wheel_details;
                    var itemModels = [];
                    var Srelatedpart = item.wheel.all_wheels;
                    var relatedpart = item.wheel.related_wheels;
                    var itemGroup = [];
                    var pricesv=[];
                    var sale_price_id = 0;
                    if(item.price.length > 0){
                        sale_price_id = item.price[0].sale_type.id;
                        pricesv = item.price[0].price;
                    }
                    $("#addtocardMdl").attr('onclick',`addtoInvoice(this,${JSON.stringify(itemSpecs)}, ${item.part_id},
                    ${JSON.stringify(item.wheel.name)}, ${item.source.id}, ${item.status.id}, ${item.part_quality.id},
                    ${sale_price_id}, ${JSON.stringify(pricesv)}, ${item.amount}, 2,1 , ${JSON.stringify(item.price)},1)`)

                }
                else if(typeId == 3){
                    var name = item.tractor.name;

                    var part_images = item.tractor.tractor_images;
                    var efrag_images = item.tractor.efrag_images;
                    var part_status = item.status.name;
                    var part_source = item.source.name_arabic;
                    var part_quality = item.part_quality.name;
                    var part_price = item.price;
                    var part_stores = item.stores;
                    var part_number =
                        "FN" +
                        "3" +
                        item.part_id +
                        item.source_id +
                        item.status_id +
                        item.quality_id;
                         var itemNumbers=[];
                         itemNumbers.push({'tractor_number':item.tractor.tractor_number});
                         itemNumbers.push({'motor_number':item.tractor.motornumber});


                    //      itemNumbers[0]['tractor_number']= item.tractor.tractor_number;

                    //  itemNumbers[0]['motor_number'] = item.tractor.motornumber;
                    var itemSpecs = item.tractor.tractor_details;
                    var itemModels=[];
                    if(item.tractor.series){
                        itemModels.push(item.tractor);
                    }
                    var pricesv=[];
                    var sale_price_id = 0;
                    if(item.price.length > 0){
                        sale_price_id = item.price[0].sale_type.id;
                        pricesv = item.price[0].price;
                    }
                    var itemGroup = [];
                    var Srelatedpart = item.tractor.all_tractors;
                    var relatedpart = item.tractor.related_tractors;
                    $("#addtocardMdl").attr('onclick',`addtoInvoice(this,${JSON.stringify(itemSpecs)}, ${item.part_id},
                     ${JSON.stringify(item.tractor.name)},${item.source.id}, ${item.status.id}, ${item.part_quality.id},
                      ${sale_price_id}, ${JSON.stringify(pricesv)}, ${item.amount}, 3,1 , ${JSON.stringify(item.price)},1)`)


                }
                else if(typeId == 4){
                    var name = item.clark.name;

                    var part_images = item.clark.clark_images;
                    var efrag_images = item.clark.clark_efrags;
                    var part_status = item.status.name;
                    var part_source = item.source.name_arabic;
                    var part_quality = item.part_quality.name;
                    var part_price = item.price;
                    var part_stores = item.stores;
                    var part_number =
                        "FN" +
                        "4" +
                        item.part_id +
                        item.source_id +
                        item.status_id +
                        item.quality_id;
                         var itemNumbers=[];
                     itemNumbers.push({'clark_number':item.clark.clark_number});

                     itemNumbers.push({'motor_number':item.clark.motor_number});

                    //  itemNumbers[0]['clark_number']= item.tractor.tractor_number;
                    //  itemNumbers[0]['motor_number'] = item.tractor.motornumber;
                    var itemSpecs = item.clark.clark_details;
                    var itemModels=[];
                    if(item.clark.series){
                     itemModels.push(item.clark);
                    }
                    var itemGroup = [];
                    var pricesv=[];
                    var sale_price_id = 0;
                    if(item.price.length > 0){
                        sale_price_id = item.price[0].sale_type.id;
                        pricesv = item.price[0].price;
                    }
                    var Srelatedpart = item.clark.all_clarks;
                    var relatedpart = item.clark.related_clarks;
                    $("#addtocardMdl").attr('onclick',`addtoInvoice(this,${JSON.stringify(itemSpecs)}, ${item.part_id},
                    ${JSON.stringify(item.clark.name)}, ${item.source.id}, ${item.status.id}, ${item.part_quality.id},
                     ${sale_price_id}, ${JSON.stringify(pricesv)}, ${item.amount},4,1 , ${JSON.stringify(item.price)},1)`)


                }
                else if(typeId == 5){
                     var name = item.equip.name;

                    var part_images = item.equip.equip_images;
                    // var efrag_images = item.equip.clark_efrags;
                    var part_status = item.status.name;
                    var part_source = item.source.name_arabic;
                    var part_quality = item.part_quality.name;
                    var part_price = item.price;
                    var part_stores = item.stores;
                    var part_number =
                        "FN" +
                        "5" +
                        item.part_id +
                        item.source_id +
                        item.status_id +
                        item.quality_id;
                         var itemNumbers=[];
                     itemNumbers['tractor_number']= [];
                     itemNumbers['motor_number'] = [];

                    var itemSpecs = item.equip.equip_details;
                    var itemModels=[];
                    if(item.equip.series){
                        itemModels.push(item.equip);

                       }
                       var pricesv=[];
                       var sale_price_id = 0;
                       if(item.price.length > 0){
                        sale_price_id = item.price[0].sale_type.id;
                        pricesv = item.price[0].price;
                    }
                    var itemGroup = [];
                    var Srelatedpart = item.equip.all_equips;
                    var relatedpart = item.equip.related_equips;
                    $("#addtocardMdl").attr('onclick',`addtoInvoice(this,${JSON.stringify(itemSpecs)}, ${item.part_id},
                     ${JSON.stringify(item.equip.name)}, ${item.source.id}, ${item.status.id}, ${item.part_quality.id},
                     ${sale_price_id}, ${JSON.stringify(pricesv)}, ${item.amount}, 5,1 , ${JSON.stringify(item.price)},1)`)


                }
                $("#itemName").text(name);
                $("#itemNum").text(part_number);

                if (part_price.length > 0) {
                    $("#itemPrice").text(part_price[0].price);
                } else {
                    $("#itemPrice").text(0);
                }



                $("#itemStock").html(
                    `Out Of Stock <span class="dot align-bottom"></span>`
                );

                if (itemGroup.length > 0) {
                    $("#itemGroup").text(
                        `${itemGroup.group.name} / ${itemGroup.name} `
                    );
                }
                $("#itemDesc").html(
                    `<span>${part_source}</span> / <span>${part_status}</span> `
                );
                 if (part_quality == "ORIGINAL") {
                    $("#itemQuality").html(
                        `<img src="assets/part_images/original.png" style="width:100px;height:100px" class="border border-3 rounded-circle">`
                    );
                } else if (part_quality == "HIGH QUALITY") {
                    $("#itemQuality").html(
                        `<img src="assets/part_images/high-quality.png" style="width:100px;height:100px" class="border border-3 rounded-circle">`
                    );
                }else{
                    $("#itemQuality").html(
                        `<p>${part_quality}</p>`
                    );
                }

                var storeStatus = 0;
                part_stores.forEach((store) => {
                    storeStatus += store.storepartCount;
                });
                var addedSource = [];
                var addedStatus = [];
                var addedQuality = [];
                if (relatedpart.length > 0) {
                    relatedpart.forEach((part) => {
                        if (part.part.part_images.length > 0) {

                            $("#relatedpartDiv1").append(`
                            <div class="column">
                                    <div class="card cardx">
                                        <div class="card-head">
                                            <div  class=" text-center" style="max-height: 250px;">
                                                <img src="assets/part_images/${part.part.part_images[0].image_name}"  class="card-img-top" style="height: 150px;width:150px" alt="Product Image">
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-descs text-center fs-18" style=" text-wrap: nowrap;">${part.part.name}  </p>
                                        </div>
                                    </div>
                                    </div>
                                     `);
                        } else {
                            $("#relatedpartDiv1")
                                .append(`<div class="column">
                                            <div class="card cardx">
                                            <div class="card-head">
                                                <div  class=" text-center" style="max-height: 250px;">
                                                    <img src="assets/part_images/tractor-solid.png"  class="card-img-top" style="height: 150px;width:150px" alt="Product Image">
                                                </div>
                                            </div>
                                            <div class="card-body">
                                            <p class="card-descs text-center fs-18" style=" text-wrap: nowrap;" >${part.part.name}  </p>
                                            </div>
                                        </div>
                                        </div>`);
                        }
                    });
                } else {
                    $("#relatedpartDiv1").html(
                        '<p class="text-center">No Data To Preview<p>'
                    );
                }
                if (Srelatedpart.length > 1) {
                    Srelatedpart.forEach((part) => {
                        var itPric = 0;
                        if (part.rprice.length > 0) {
                            itPric = part.rprice[0].price;
                        }
                        if (
                            part.source_id != item.source_id &&
                            part.status_id != item.status_id &&
                            part.quality_id != item.quality_id
                        ) {
                            if (
                                jQuery.inArray(part.source_id, addedSource) ==
                                    -1 &&
                                jQuery.inArray(part.status_id, addedStatus) ==
                                    -1 &&
                                jQuery.inArray(part.quality_id, addedQuality) ==
                                    -1
                            ) {
                                if (part_images.length > 0) {
                                    $("#relatedpartDiv")
                                        .append(`<div class="column">
                                                    <div class=" card card text-center" style="max-height: 250px;">

                                                <img src="assets/part_images/${part_images[0].image_name}"  class="card-img-top" style="height: 150px;width:150px" alt="Product Image">
                                                <div class="card-body">
                                                    <p class="card-descs text-center fs-18" style=" text-wrap: nowrap;">${item.part.name} </p>
                                                    <p class="card-descs text-center fs-18" style=" text-wrap: nowrap;">${part.source.name_arabic} / ${part.status.name} / ${part.part_quality.name} </p>
                                                    <p class="card-price fs-18">$ ${itPric}</p>

                                                    <a href="#"  class="btn btn-primary d-none">Add to Cart</a>
                                                </div>
                                                </div>
                                            </div>`);
                                } else {
                                    $("#relatedpartDiv")
                                        .append(`<div class="column">
                                                    <div class="card  cardx">
                                                    <div class="card-head">
                                                        <div  class=" text-center" style="max-height: 250px;">
                                                            <img src="assets/part_images/tractor-solid.png"  class="card-img-top" style="height: 150px;width:150px" alt="Product Image">
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <p class="card-descs text-center fs-18" style=" text-wrap: nowrap;">${item.part.name} </p>
                                                        <p class="card-descs text-center fs-18" style=" text-wrap: nowrap;">${part.source.name_arabic} / ${part.status.name} / ${part.part_quality.name} </p>
                                                        <p class="card-price fs-18">$ ${itPric}</p>
                                                        <a href="#" class="btn btn-primary d-none">Add to Cart</a>
                                                        </div>
                                                    </div>
                                                    </div>`);
                                }

                                addedQuality.push(part.quality_id);
                                addedSource.push(part.source_id);
                                addedStatus.push(part.status_id);
                            }
                        }
                    });
                } else {
                    $("#relatedpartDiv").html(
                        '<h5 class="text-center">No Data To Preview</h5>'
                    );
                }
                if (storeStatus > 0) {
                    // alert(storeStatus;);
                    $("#itemStock").html(
                        `Available <span class="dot bg-success  align-bottom"></span>`
                    );
                } else {
                    $("#itemStock").html(
                        `Out Of Stock <span class="dot  align-bottom"></span>`
                    );
                }
                if (part_stores.length > 0) {
                    part_stores.forEach((store) => {
                        $("#itemStores").append(
                            `<tr class="specscontainer">
                                <td>${store.name}</td>
                                <td>${store.storepartCount}</td>
                            </tr>`
                        );
                    });
                }

                if (part_price.length > 0) {
                    part_price.forEach((price) => {
                        $(".itemPriceList").append(
                            `<tr class="specscontainer">
                                <td >${price.sale_type.type}</td>
                                <td >${price.price}</td>
                            </tr>`
                        );
                    });
                } else {
                    $(".itemPriceList").append("<li>No Price List</li>");
                }
                if (part_images.length > 0) {

                    part_images.forEach((img) => {
                        if (typeId == 6) {
                            p_images='kit_images';
                            Pname=item.kit.name;
                            img_name=img.image_url
                        } else if (typeId == 1){
                            p_images='part_images';
                            Pname=item.part.name;
                            img_name=img.image_name


                        }else if (typeId == 2){
                            p_images='wheel_images';
                            Pname=item.wheel.name;
                            img_name=img.image_name


                        }
                        else if (typeId == 3){
                            p_images='tractor_images';
                            Pname=item.tractor.name;
                            img_name=img.url


                        }
                          else if (typeId == 4){
                            p_images='clark_images';
                            Pname=item.clark.name;
                            img_name=img.image_name


                        } else if (typeId == 5){
                            p_images='equip_images';
                            Pname=item.equip.name;
                            img_name=img.image_name


                        }

                        $("#item-image-car")
                            .append(`<div class="carousel-item ">
                                        <img src="assets/${p_images}/${img_name}" class="d-block w-100" style="" alt="Product Image 1">
                                    </div>`);
                    });
                    $($(".carousel-item")[0]).addClass("active");
                } else {
                    $("#item-image-car")
                        .append(`<div class="carousel-item active">
                                <img src="assets/part_images/tractor-solid.png" class="d-block w-100" style="height:150px" alt="Product Image 1">
                            </div>`);
                }
                if( Array.isArray(itemNumbers)){
                     if(typeId == 3 || typeId == 4 ||typeId == 5){
                        $("#containerNUM").empty();


                            itemNumbers.forEach((key,val)=>{
                                   $("#containerNUM").append(

                                `<tr class="specscontainer row">
                                    <td class="col-lg-6">${Object.keys(key)[0]} : </td>
                                    <td class="col-lg-6"> ${Object.values(key)[0]}</td>

                                </tr>`
                            );
                            })
                    }else{
                        if (itemNumbers.length > 0) {

                            var supplierss = removeDuplicates(itemNumbers.map(a => (a.supplier) ? a.supplier.name : 'No sup need Edit'));
                            $("#nav-tabssnum").empty();
                            $(".numbersDialog").empty();


                            for (let b = 0; b < supplierss.length; b++) {
                                // $("#p_brand").append(`<li class="specscontainer border border-dark list-group-item list-group-item-info d-flex justify-content-center align-items-center">
                                // ${brand[b]}
                                // </li>`);
                                $("#nav-tabssnum").append(` <button class="nav-link " id="nav-type-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-typenum${b}" type="button" role="tab"
                                aria-controls="nav-type${b}" aria-selected="true">${supplierss[b]} <i class="bx bx-menu-alt-left"></i></button>`)


                               var arrList =  itemNumbers.map(a=>(a.supplier) ? a.supplier.name : 'No sup need Edit' === supplierss[b]);

                               var xx = `<div class="tab-content" >
                               <div class="tab-pane fade numbersDialog" id="nav-typenum${b}" role="tabpanel"
                                   aria-labelledby="nav-type-tab">
                                   <table class="table table-striped table-bordered cell-border "> <tr style="background:#5fcee78a">
                                   <td> Number</td>
                                   <td> Supplier </td>
                                   </tr>`;

                               for (let index = 0; index < arrList.length; index++) {
                                    const element = arrList[index];
                                     if(element){
                                        if(element===supplierss[b]){
                                            xx +=`<tr class="specscontainer">
                                                        <td> ${itemNumbers[index].number} </td>
                                                        <td> ${(itemNumbers[index].supplier) ? itemNumbers[index].supplier.name : 'No sup need Edit'} </td>
                                                        </tr>

                                                `;

                                        }

                                    }

                                }

                                xx +=`</table></div></div>`;
                                $("#containerNUM").find('nav').after(xx);



                            }

                            //     itemNumbers.forEach((num) => {
                            //     $("#itemNumbers").append(
                            //         `<tr class="specscontainer">
                            //         <td >${num.number} </td>
                            //         <td>${num.supplier.name}</td>
                            //         <td>${(num.flag_OM > 0) ? 'OEM' : 'SUP'}</td>
                            //     </tr>`

                            //     );
                            // });


                        } else {
                            $("#itemNumbers").append(
                                `<tr class="specscontainer">
                                <td ></td>
                                <td> No Numbers </td>
                                <td></td>
                            </tr>`

                            );
                        }
                    }
                }
                else{
                     $("#itemNumbers").append(
                        `<tr class="specscontainer">
                        <td >${itemNumbers} </td>
                        <td></td>
                        <td></td>
                    </tr>`
                    );
                }
                if (itemSpecs.length > 0) {
                    var specsCounter = 0;
                    $("#itemSpecs").empty();
                    $("#carditemSpecs").empty();
                    itemSpecs.forEach((specs) => {
                        var speecs_name='';
                        if (typeId==1){
                            speecs_name= specs.part_spec.name;
                        }else if(typeId==2){
                              speecs_name= specs.wheel_spec.name;
                        }else if(typeId==6){
                              speecs_name= specs.kit_specs.name;
                        }else if(typeId==3){
                            speecs_name= specs.tractor_spec.name;
                        }else if(typeId==4){
                            speecs_name= specs.clark_spec.name;
                        }else if(typeId==5){
                            speecs_name= specs.equip_spec.name;
                        }
                        $("#itemSpecs").append(

                            `<tr class="specscontainer">
                            <td class="">${speecs_name}</td>
                            <td class="">${specs.value}</td>
                            </tr>`
                        );
                        if(specsCounter < 3){
                            $("#carditemSpecs").append(

                                `<tr class="specscontainer">
                                <td class="border-0 ">${speecs_name}</td>
                                <td class="border-0 border-end">${specs.value}</td>
                                </tr>`
                            );
                            specsCounter++;
                        }else{

                        }


                    });
                } else {
                    $("#itemSpecs").append(
                         '<p class="text-center">No Data To Preview<p>'
                    );
                    $("#carditemSpecs").append(
                        '<p class="text-center">No Data To Preview<p>'
                   );
                }
                console.log(itemModels);
                if (itemModels.length > 0) {
                    $(".ModelsDialog").empty();
                    // itemModels.forEach((mdl) => {
                    //     $("#itemModels").append(`<tr class="specscontainer">
                    //                     <td>${mdl.series.model.brand_type.name}</td>
                    //                     <td>${mdl.series.model.brand.name}</td>
                    //                     <td>${mdl.series.model.name}</td>
                    //                     <td>${mdl.series.name}</td>
                    //                 </tr>`);
                    //                 // $('#bradtbl').DataTable();
                    let type = removeDuplicates(itemModels.map(a => a.series.model.brand_type.name));

                        for (let a = 0; a < type.length; a++) {
                            $("#p_type").append(`<li class="specscontainer border border-dark list-group-item list-group-item-info d-flex justify-content-center align-items-center">
                            ${type[a]}
                            </li>`);
                        }
                        let brand = removeDuplicates(itemModels.map(a => a.series.model.brand.name));

                        $("#nav-tabss").empty();

                        for (let b = 0; b < brand.length; b++) {
                            // $("#p_brand").append(`<li class="specscontainer border border-dark list-group-item list-group-item-info d-flex justify-content-center align-items-center">
                            // ${brand[b]}
                            // </li>`);
                            $("#nav-tabss").append(` <button class="nav-link " id="nav-type-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-type${b}" type="button" role="tab"
                            aria-controls="nav-type${b}" aria-selected="true">${brand[b]} <i class="bx bx-menu-alt-left"></i></button>`)


                           var arrList =  itemModels.map(a=> a.series.model.brand.name === brand[b]);

                           var xx = `<div class="tab-content" >
                           <div class="tab-pane fade ModelsDialog" id="nav-type${b}" role="tabpanel"
                               aria-labelledby="nav-type-tab">
                               <table class="table table-striped table-bordered cell-border "> <tr style="background:#5fcee78a">
                               <td> Model</td>
                               <td> Series </td>
                               </tr>`;

                           for (let index = 0; index < arrList.length; index++) {
                                const element = arrList[index];
                                if(element){


                                       xx +=`

                                                    <tr class="specscontainer">
                                                    <td> ${itemModels[index].series.model.name} </td>
                                                    <td> ${itemModels[index].series.name} </td>
                                                    </tr>

                                            `;

                                }

                            }

                            xx +=`</table></div></div>`;
                            $("#containerLL").find('nav').after(xx);



                        }
                        let model = removeDuplicates(itemModels.map(a => a.series.model.name));
                        for (let c = 0; c < model.length; c++) {
                            $("#p_model").append(`<li class="specscontainer border border-dark list-group-item list-group-item-info d-flex justify-content-center align-items-center">
                            ${model[c]}
                            </li>`);
                        }

                        let series = removeDuplicates(itemModels.map(a => a.series.name));
                        for (let d = 0; d < series.length; d++) {
                            $("#p_series").append(`<li class="specscontainer border border-dark list-group-item list-group-item-info d-flex justify-content-center align-items-center">
                            ${series[d]}
                            </li>`);
                        }




                    // });

                } else {
                    // $("#itemModels").append(
                    //     `<li class="list-group-item d-flex justify-content-between align-items-center">No Models</li>`
                    // );
                    $("#p_type").append( `<li class="list-group-item d-flex justify-content-between align-items-center">No Models</li>`);
                        $("#p_brand").append(`<li class="list-group-item d-flex justify-content-between align-items-center">No Models</li>`);
                        $("#p_model").append(`<li class="list-group-item d-flex justify-content-between align-items-center">No Models</li>`);
                        $("#p_series").append(`<li class="list-group-item d-flex justify-content-between align-items-center">No Models</li>`);
                }

                $("#infoMdl").modal("show");
            } else {
                $("#infoMdl").modal("hide");
            }
        },
            });
        }


        //salam code in pos

        if (store_inbox) {
            $('#inboxCounterLbl').html(store_inbox.length);
            $('.kt_topbar_notifications_3').html();
            // draw_notification(store_inbox)
        }
        //    function draw_notification(data_arr) {
        //             // partStoreDT.clear();
        //             partStoreDT.rows.add(data_arr);
        //             partStoreDT.draw();
        // }



    function removeDuplicates(arr) {
        return arr.filter((item,
            index) => arr.indexOf(item) === index);
    }
    function incrementValue(e) {
      e.preventDefault();
      var fieldName = $(e.target).data('field');
      var parent = $(e.target).closest('div');
      var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

      if (!isNaN(currentVal)) {
        parent.find('input[name=' + fieldName + ']').val(currentVal + 1);
      } else {
        parent.find('input[name=' + fieldName + ']').val(0);
      }
    }

    function decrementValue(e) {
      e.preventDefault();
      var fieldName = $(e.target).data('field');
      var parent = $(e.target).closest('div');
      var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

      if (!isNaN(currentVal) && currentVal > 0) {
        parent.find('input[name=' + fieldName + ']').val(currentVal - 1);
      } else {
        parent.find('input[name=' + fieldName + ']').val(0);
      }
    }

    $('.input-group').on('click', '.button-plus', function(e) {
      incrementValue(e);
    });

    $('.input-group').on('click', '.button-minus', function(e) {
      decrementValue(e);
    });

    $('#saleTypeSlct').change(function() {
        var selectedSaleType = parseInt($(this).val());
         localStorage.setItem('cardsaleType',selectedSaleType);


        $('#invoiceItems > tr').each(function() {
                let pricelist = $(this).attr('data-price');
                if(pricelist != 0){
                   pricelist = JSON.parse(pricelist);
                   pricelist.forEach(element => {
                            if(element.sale_type.id == selectedSaleType){
                                $(this).find('.itemPrice').text(element.price);
                                // $(this).find('input[name="pricetype[]"]').val(selectedSaleType);
                                $($(this).parent()[0]).find('input[name="pricetype[]"]').val(selectedSaleType);
                                $($(this).parent()[0]).find('input[name="itemPrice[]"]').val(element.price);
                                $(this).find('.itemTotalPrice').text(parseFloat($(this).find('input[name="itemAmount[]"]').val()) * parseFloat(element.price))  ;
                            }
                        });
                    // var selected = pricelist.find((element) => element.sale_type.id = selectedSaleType);

                }
                // console.log(JSON.parse(pricelist));
                //pricetype[]   sale_type
                //itemPrice
                //itemTotalPrice
                //itemAmount[]
            });
        calcTotal();
    })



    function saveClientPrice(el){
        console.log(el);

        $.ajax({
            type: "POST",
            url: "saveClientPrice",
            data: $(el).parent().serialize(),
            success: function (response) {
                 alert(response);
            }
        })
    }

    // $('#paymentslect').change(function() {
    //     alert($("#paymentslect option:selected").attr('data-mad'));
    // })

    $(document).on('keyup','#searchtables',function(e){
        var searchValue = $(this).val();
        $('.specscontainer').hide();
        // document.getElementsByClassName("specscontainer").children[0].style.display = "none"
        $('.specscontainer:contains("' + searchValue + '")').show();
    })

 $(document).on('click','.AddnewClient',function(e){

        $("#example11Modal").modal('toggle')
        $("#newclientMdl").modal('toggle')

    })

    $('#newclientMdl').on('hidden.bs.modal', function () {
        $("#example11Modal").modal('toggle')
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
$("#partNumberSearchTxt").keypress(function (e) {
    var key = e.which;
    var searchValue = $(this).val();
    if(key == 13){
        // $("#preloader").css({
        //     'opacity': '1',
        //     'visibility': 'visible'
        // });
        // e.preventDefault();
        // $.ajax({
        //     type: "get",
        //     url: "allDataForIdPartNumber",
        //     data : {
        //         storeId : store_data[0].id ,
        //         searchkey : searchValue,
        //         typeId : 1
        //     },
        //     success: function (response) {
        //         partsDt.clear();
        //         if (response.data.length > 0) {
        //             $('#datatable').DataTable();
        //             partsDt.rows.add(response.data).draw();

        //         }

        //     },complete:function(response){
        //         $("#preloader").css({
        //             'opacity': '0',
        //             'visibility': 'hidden'
        //         });
        //     }
        // });


    }
});


$(document).on('change', '#brandSlct', function(){
    var brandid= $(this).val();
    var ptype_id= $("#brandtypeSlct").val();
    if(ptype_id > 0){
        $.ajax({
            type: "get",
            url: "partmodel/"+brandid+"/"+ptype_id,
            success: function (response) {

                $("#modelSlct").empty();
                $("#modelSlct").append(`<option selected disabled value="">اختر</option>`);
                response.forEach(element => {
                    $("#modelSlct").append(`<option value="${element.id}">${element.name}</option>`);
                });
                $("#modelSlct").select2();
            }
        });
        // $.ajax({
        //     type: "get",
        //     url: "allDataForIdBrand",
        //     data : {
        //         storeId : store_data[0].id ,
        //         searchkey : brandid,
        //         typeId : 1
        //     },
        //     success: function (response) {
        //         partsDt.clear();
        //         if (response.data.length > 0) {
        //             $('#datatable').DataTable();
        //             partsDt.rows.add(response.data).draw();

        //         }

        //     }
        // });

    }else{
        alert("Select Type First")
    }

})

$(document).on('change', '#modelSlct', function(){
    // $("#preloader").css({
    //     'opacity': '1',
    //     'visibility': 'visible'
    // });
    var modelId= $(this).val();
    $.ajax({
        type: "get",
        url: "partseries/"+modelId,
        success: function (response) {
            $("#seriesSlct").empty();
            $("#seriesSlct").append(`<option selected disabled value="">اختر</option>`);
            response.forEach(element => {
                $("#seriesSlct").append(`<option value="${element.id}">${element.name}</option>`);
            });
            $("#seriesSlct").select2();
        }
    });
    // $.ajax({
    //     type: "get",
    //     url: "allDataForIdModel",
    //     data : {
    //         storeId : store_data[0].id ,
    //         searchkey : modelId,
    //         typeId : 1
    //     },
    //     success: function (response) {
    //         partsDt.clear();
    //         if (response.data.length > 0) {
    //             $('#datatable').DataTable();
    //             partsDt.rows.add(response.data).draw();

    //         }

    //     },complete:function(response){
    //         $("#preloader").css({
    //             'opacity': '0',
    //             'visibility': 'hidden'
    //         });
    //     }
    // });

})


$(document).on('change', '#seriesSlct', function(){
    var seriesId= $(this).val();
    // $("#preloader").css({
    //     'opacity': '1',
    //     'visibility': 'visible'
    // });
    // $.ajax({
    //     type: "get",
    //     url: "allDataForIdSeries",
    //     data : {
    //         storeId : store_data[0].id ,
    //         searchkey : seriesId,
    //         typeId : 1
    //     },
    //     success: function (response) {
    //         partsDt.clear();
    //         if (response.data.length > 0) {
    //             $('#datatable').DataTable();
    //             partsDt.rows.add(response.data).draw();

    //         }

    //     },complete:function(response){
    //         $("#preloader").css({
    //             'opacity': '0',
    //             'visibility': 'hidden'
    //         });
    //     }
    // });

})


$(document).on('change', '#groupSlct', function(){
    var groupid= $(this).val();
    $.ajax({
        type: "get",
        url: "group/"+groupid,
        success: function (response) {
            $("#SgroupSlct").empty();
            $("#SgroupSlct").append(`<option selected disabled value="">Select Sub Groups</option>`);
            response.forEach(element => {
                $("#SgroupSlct").append(`<option value="${element.id}">${element.name}</option>`);
            });
            $("#SgroupSlct").select2();
        }
    });

    // $("#preloader").css({
    //     'opacity': '1',
    //     'visibility': 'visible'
    // });
    // $.ajax({
    //     type: "get",
    //     url: "allDataForIdGroup",
    //     data : {
    //         storeId : store_data[0].id ,
    //         searchkey : groupid,
    //         typeId : 1
    //     },
    //     success: function (response) {
    //         partsDt.clear();
    //         if (response.data.length > 0) {
    //             $('#datatable').DataTable();
    //             partsDt.rows.add(response.data).draw();

    //         }

    //     },complete:function(response){
    //         $("#preloader").css({
    //             'opacity': '0',
    //             'visibility': 'hidden'
    //         });
    //     }
    // });


})
$(document).on('change', '#SgroupSlct', function(){
    var sgroupid= $(this).val();


    // $("#preloader").css({
    //     'opacity': '1',
    //     'visibility': 'visible'
    // });
    // $.ajax({
    //     type: "get",
    //     url: "allDataForIdSubGroup",
    //     data : {
    //         storeId : store_data[0].id ,
    //         searchkey : sgroupid,
    //         typeId : 1
    //     },
    //     success: function (response) {
    //         partsDt.clear();
    //         if (response.data.length > 0) {
    //             $('#datatable').DataTable();
    //             partsDt.rows.add(response.data).draw();

    //         }

    //     },complete:function(response){
    //         $("#preloader").css({
    //             'opacity': '0',
    //             'visibility': 'hidden'
    //         });
    //     }
    // });


})

$(document).on('change', '#supplierSlct', function(){
    var sup_id= $(this).val();


    // $("#preloader").css({
    //     'opacity': '1',
    //     'visibility': 'visible'
    // });
    // $.ajax({
    //     type: "get",
    //     url: "allDataForIdSupplier",
    //     data : {
    //         storeId : store_data[0].id ,
    //         searchkey : sup_id,
    //         typeId : 1
    //     },
    //     success: function (response) {
    //         partsDt.clear();
    //         if (response.data.length > 0) {
    //             $('#datatable').DataTable();
    //             partsDt.rows.add(response.data).draw();

    //         }

    //     },complete:function(response){
    //         $("#preloader").css({
    //             'opacity': '0',
    //             'visibility': 'hidden'
    //         });
    //     }
    // });


})
$(document).on('click', '#filterBtn', function(){
    var partNameSearchTxt= $("#partNameSearchTxt").val();
    var brandtypeSlct= $("#brandtypeSlct").val();
    var brandSlct= $("#brandSlct").val();
    var modelSlct= $("#modelSlct").val();
    var seriesSlct= $("#seriesSlct").val();
    var partNumberSearchTxt= $("#partNumberSearchTxt").val();
    var supplierSlct= $("#supplierSlct").val();
    var groupSlct= $("#groupSlct").val();
    var SgroupSlct= $("#SgroupSlct").val();

    $("#row_filters").empty();
    if(partNameSearchTxt){
        $("#row_filters").append(` <div class="ml-1  w-auto">
            <button type="button" onclick="reloadFilter(this,'partNameSearchTxt')" class="btn btn-outline-info btn-sm ">${partNameSearchTxt}  <i class="ri-close-fill"></i></button>
        </div>`);
    }
    if(brandtypeSlct){
        $("#row_filters").append(` <div class="ml-1  w-auto">
            <button type="button" onclick="reloadFilter(this,'brandtypeSlct')" class="btn btn-outline-info btn-sm ">${$("#brandtypeSlct option:selected").text()}  <i class="ri-close-fill"></i></button>
        </div>`);
    }
    if(brandSlct){
        $("#row_filters").append(` <div class="ml-1  w-auto">
            <button type="button" onclick="reloadFilter(this,'brandSlct')" class="btn btn-outline-info btn-sm ">${$("#brandSlct option:selected").text()}  <i class="ri-close-fill"></i></button>
        </div>`);
    }
    if(modelSlct){
        $("#row_filters").append(` <div class="ml-1  w-auto">
            <button type="button" onclick="reloadFilter(this,'modelSlct')" class="btn btn-outline-info btn-sm ">${$("#modelSlct option:selected").text()}  <i class="ri-close-fill"></i></button>
        </div>`);
    }
    if(seriesSlct){
        $("#row_filters").append(` <div class="ml-1  w-auto">
            <button type="button" onclick="reloadFilter(this,'seriesSlct')" class="btn btn-outline-info btn-sm ">${$("#seriesSlct option:selected").text()}  <i class="ri-close-fill"></i></button>
        </div>`);
    }
    if(partNumberSearchTxt){
        $("#row_filters").append(` <div class="ml-1  w-auto">
            <button type="button" onclick="reloadFilter(this,'partNumberSearchTxt')" class="btn btn-outline-info btn-sm ">${partNumberSearchTxt}  <i class="ri-close-fill"></i></button>
        </div>`);
    }
    if(supplierSlct){
        $("#row_filters").append(` <div class="ml-1  w-auto">
            <button type="button" onclick="reloadFilter(this,'supplierSlct')" class="btn btn-outline-info btn-sm ">${$("#supplierSlct option:selected").text()}  <i class="ri-close-fill"></i></button>
        </div>`);
    }
    if(groupSlct){
        $("#row_filters").append(` <div class="ml-1  w-auto">
            <button type="button" onclick="reloadFilter(this,'groupSlct')" class="btn btn-outline-info btn-sm ">${$("#groupSlct option:selected").text()}  <i class="ri-close-fill"></i></button>
        </div>`);
    }
    if(SgroupSlct){
        $("#row_filters").append(` <div class="ml-1  w-auto">
            <button type="button" onclick="reloadFilter(this,'SgroupSlct')" class="btn btn-outline-info btn-sm ">${$("#SgroupSlct option:selected").text()}  <i class="ri-close-fill"></i></button>
        </div>`);
    }



    $("#preloader").css({
        'opacity': '1',
        'visibility': 'visible'
    });
    $.ajax({
        type: "get",
        url: "allDataForIdFilterAll",
        data : {
            storeId : store_data[0].id ,
            searchData : {
                'partNameSearchTxt' : partNameSearchTxt ,
                'brandtypeSlct' : brandtypeSlct ,
                'brandSlct' : brandSlct ,
                'modelSlct' : modelSlct ,
                'seriesSlct' : seriesSlct ,
                'partNumberSearchTxt' : partNumberSearchTxt ,
                'supplierSlct' : supplierSlct ,
                'groupSlct' : groupSlct ,
                'SgroupSlct' : SgroupSlct ,

            },
            typeId : 1
        },
        success: function (response) {
            partsDt.clear().draw();
            if (response.data.length > 0) {
                /*************************** */
                if ($(partsDt.table().node()).hasClass('cards')) {
                    $($(".gridTogView")[0]).click();
                }else{

                }

                /****************************** */
                $('#datatable').DataTable();
                partsDt.rows.add(response.data).draw();

            }

        },complete:function(response){
            $("#preloader").css({
                'opacity': '0',
                'visibility': 'hidden'
            });
        }
    });


})

function reloadFilter(el,element){
    $(el).parent().remove();

    $("#"+element).val('');
    $("#filterBtn").trigger('click');

}
