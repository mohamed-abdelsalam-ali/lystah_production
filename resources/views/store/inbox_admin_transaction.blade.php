@extends('layouts.master')
@section('content')
@section('css')



@section('content')

   
                    
                    
<div class="main-content">
    <div class="page-content">
        <div class="card "> 
            <h1 class="text-danger text-center"> Admin Inbox </h1>
            <div class="card-body  table-responsive"> 
                    
                    
                     <table class="table table-striped table-bordered cell-border " style="font-size: smaller;width:100%" id="kt_ecommerce_sales_table_inbox">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">

                                <th></th>
                                <th class="text-center">رقم القطعة</th>
                                <th class="text-center">أسم القطعة</th>
                                <th></th>

                                <th class="text-center">بلد المنشأ </th>
                                <th></th>

                                <th class="text-center"> الحالة</th>
                                <th></th>

                                <th class="text-center"> الكفاءة</th>
                                <th></th>
                                <th class="text-center"> النوع</th>

                                <th class="text-center">الحركة</th>
                                <th class="text-center"></th>
                                <th class="text-center">الكمية</th>
                                <th class="text-center"> مخزن</th>
                                <th class="text-center"> </th>

                                <th class="text-center">التاريخ </th>
                                <th class="text-center">المررد</th>
                                <th class="text-center">المرحلة</th>
                                <th class="text-center">ملاحظات </th>

                                <th class="text-center">Actions</th>

                                
                            </tr>
                        </thead>

                        <tbody class="fw-semibold text-gray-500">


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



@endsection
@section('js')

            <script src="{{ URL::asset('js/store/listing_inbox.js') }}"></script>


    <!--end::Custom Javascript-->
   
    <script>


    function hide_transaction(el) {
        var row_clicked = $(el).closest('tr');
        var row_object = table.row(row_clicked).data();
        console.log(row_object);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "{{ route('hideStoreTrans') }}",
            data: {
                'data': {
                    'Store_log_id': row_object.stores_log_id,
                    'store_table_name': row_object.store.table_name,
                    'store_action_id': row_object.store_action_id,
                    'all_p_id': row_object.All_part_id,
                    'amount': row_object.trans_amount,
                    'type_id': row_object.type_id,
                    'store_id': row_object.store_id
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
                    $('#kt_ecommerce_sales_table_inbox').DataTable().ajax.reload();
                }
                // getAllNewParts();
                // getAllNewParts_inbox();
                // $('.nav-tabs a[href="#tab-3"]').tab('show');


                // SuccessAlert("This Message Hide Successfully")

            }
        });
    }

    function refuse_transaction(el) {
        var row_clicked = $(el).closest('tr');
        var row_object = table.row(row_clicked).data();
        console.log(row_object);
        if (row_object.store_action_id == 2 || row_object.store_action_id == 6 && row_object.status == 2) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('refuseStoreTrans') }}",
                data: {
                    'data': {
                        'Store_log_id': row_object.stores_log_id,
                        'store_table_name': row_object.store.table_name,
                        'store_action_id': row_object.store_action_id,
                        'all_p_id': row_object.All_part_id,
                        'amount': row_object.trans_amount,
                        'type_id': row_object.type_id,
                        'store_id': row_object.store_id
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
                        $('#kt_ecommerce_sales_table_inbox').DataTable().ajax.reload();
                    }
                    // getAllNewParts();
                    // getAllNewParts_inbox();
                    // $('.nav-tabs a[href="#tab-3"]').tab('show');
                    // SuccessAlert("This Message Denied you can Hide It")
                    // var resArr1 = $.parseJSON(data);
                    // console.log(resArr1);

                    // arr_partNo_html = draw(resArr1['Last_Amount'], 'Last_Amount');
                    // $(".Last_Amount").html(arr_partNo_html);


                }
            });
        } else {
            alert("This Process Can`t Deleted")
            }
    }

    function Confirm_transaction(el) {

            var row_clicked = $(el).closest('tr');
            var row_object = table.row(row_clicked).data();
            console.log(row_object);
            if (row_object.store_action_id == 2 || row_object.store_action_id == 6 && row_object.status == 2) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('confirmStoreTrans') }}",
                    data: {
                        'data': {
                            'Store_log_id': row_object.stores_log_id,
                            'store_table_name': row_object.store.table_name,
                            'store_action_id': row_object.store_action_id,
                            'all_p_id': row_object.All_part_id,
                            'amount': row_object.trans_amount,
                            'type_id': row_object.type_id,
                            'store_id': row_object.store_id
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
                            $('#kt_ecommerce_sales_table_inbox').DataTable().ajax.reload();
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
                alert("Need  Store To recive Amount");
            }

    }
    </script>

    <script>
        var table = "";
        $(function() {
            // alert("cccc");
            table = $('#kt_ecommerce_sales_table_inbox').DataTable({

                processing: true,
                serverSide: true,
                // dom: 'Bfrtip',

                type: 'get',
                ajax: "{{ route('inboxAdmin.list') }}",
                // async:false,
                columns: [
                    // {
                    //     data: "stores_log_id",
                    //     name: 'Select',

                    //     defaultContent: "-",
                    //     render: function (data, type,row) {

                    //     return ` <div class="form-check form-check-sm form-check-custom form-check-solid">
                //                 <input class="form-check-input" type="checkbox" value="1" name="check_box"/>
                //              </div>`;

                    //     }
                    // },
                    {
                        data: "stores_log_id",
                        "visible": false
                    },
                    {
                        data: 'part_id',
                        name: 'part_id',
                       
                    },

                    {
                        data: 'part_name',
                        name: 'part_name'
                    },
                    {
                        data: 'source_id',
                        name: 'source_id',
                        "visible": false
                    },
                    {
                        data: 'source_name',
                        name: 'source_name'
                    },
                    {
                        data: 'quality_id',
                        name: 'quality_id',
                        "visible": false
                    },
                    {
                        data: 'quality_name',
                        name: 'quality_name'
                    },
                    {
                        data: 'status_id',
                        name: 'status_id',
                        "visible": false
                    },
                    {
                        data: 'staus_name',
                        name: 'staus_name'
                    },


                    {
                        data: 'type.id',
                        name: 'type_id',
                        "visible": false
                    },
                    {
                        data: 'type.name',
                        name: 'type_name'
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
                        name: 'trans_amount',
                        render: function(data, type, row) {
        
                            return data/row.ratio +'/'+row.bigunit;
    
                        }
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
                                        statusclass = 'light-dark bg-danger';
                                        break;
                                    case 0:
                                        statusword = 'منتظر إستلام';
                                        statusclass = 'light-dark bg-warning';
                                        break;
                                    case 1:
                                        statusword = 'تم الإستلام';
                                        statusclass = 'light-dark bg-info';
                                        break;
                                    case 2:
                                        statusword = 'منتظر تأكيد';
                                        statusclass = 'light-dark bg-success';
                                        break;

                                }

                                // if (isadmin == 'true') {
                                // return '<div class="m-4"><input type="checkbox" class="pendingChk" value="' + data['event_id'] + '"><br><div class = "lblstatus label label-' + statusclass + '">' + statusword + '</div></div>';
                                // } else {
                                return '<span name="statusword" class = "badge badge-pill  badge-' +
                                    statusclass + '">' + statusword + '</span>';
                                // }
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
                        className: "text-center",
                        // defaultContent: `<div  class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                    //                     <span class="svg-icon svg-icon-5 m-0">
                    //                         <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    //                             <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                    //                         </svg>
                    //                     </span>
                    //                 </div>
                    //                 <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                    //                     <div class="menu-item px-3">
                    //                     </div>
                    //                 </div> `,
                        render: function(data, type, row) {
                            
                            data = `<a href="javascript:void(0)" class="edit btn btn-primary btn-sm d-none" data-fun=''  onclick="hide_transaction(this)" >hide</a>
                    <a href="javascript:void(0)" placeholder="refuse" class="edit btn btn-danger btn-sm" onclick="refuse_transaction(this)"><i class="mdi mdi-close-box"></i></a><br>
                    <a href="javascript:void(0)" placeholder="accept" class="edit btn btn-success btn-sm" onclick="Confirm_transaction(this)"><i class="mdi mdi-checkbox-marked"></i></a>
                    `;
                            return data;
                        }


                    },



                ]

            });

        });
    </script>

<script>

    const data_inbox = {!! $data_inbox !!};


        if (data_inbox) {
            $('.inbox_trans_counter').html(data_inbox.length);
            // $('.simplebar-content').empty();
            draw_notification(data_inbox,'.kt_topbar_notifications_33')
        }

 </script>
@endsection
