@extends('layouts.master')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.min.js"></script>

    <style>
        table {
            font-family: 'Droid Arabic Naskh', serif;
            table-layout: fixed;
        }
        td {
            overflow: hidden;
            text-overflow: ellipsis;
            word-wrap: break-word;
        }

        .modal-dialog {
            max-width: 1000px !important;
        }
        td input{
            width: 100% !important;
        }
    </style>
@endsection


@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="row card m-3">
                <div class="table-responsive table-card pt-4">
                    <table class="table align-middle table-row-dashed fs-10 gy-9 p-3" id="kt_ecommerce_sales_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">

                                <th class="text-center ">رقم القطعة</th>
                                <th class="text-center ">أسم القطعة</th>
                                <th class="text-center ">الأرقام</th>
                                <th class="text-center ">الكمية</th>
                                @if($items[0]->order_suppliers_with_replayorder)

                                @foreach ($items[0]->order_suppliers_with_replayorder[0]->store_data as $store)
                                    <th class="text-center ">{{ $store['name'] }}</th>
                                @endforeach
                                @endif
                                <th class="text-center ">Actions</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-500">
                            @foreach ($items[0]->order_suppliers_with_replayorder as $item)
                                <!--begin::Table row-->

                                <tr calss="">
                                    <!--begin::Checkbox-->

                                    <!--end::Checkbox-->
                                    <!--begin::Order ID=-->
                                    <td class="text-center pe-0" data-kt-ecommerce-order-filter="order_id">

                                        <a href="details.html"
                                            class="text-gray-800 text-hover-primary fw-bold">{{ $item->part_id }}</a>
                                        <input type="hidden" name="part_id" value="{{ $item->part_id }}">

                                    </td>
                                    <!--end::Order ID=-->
                                    <!--begin::Customer=-->
                                    <td class="text-center pe-0">
                                        <a href="#"
                                            class="text-gray-800 text-hover-primary ">{{ $item->static_item_data[0]->name }}
                                        </a>

                                        <div>
                                            <span>{{ $item->source->name_arabic }}</span>-<span>{{ $item->status->name }}</span>-<span>{{ $item->part_quality->name }}</span>
                                            <input type="hidden" name="source_id" value="{{ $item->source_id }}">
                                            <input type="hidden" name="ststus_id" value="{{ $item->status_id }}">
                                            <input type="hidden" name="quality_id" value="{{ $item->quality_id }}">
                                        </div>

                                    </td>

                                    <td class="text-center pe-0">
                                        @if (isset($item->static_item_data[0]->part_numbers) )
                                            <ul>


                                                @foreach ($item->static_item_data[0]->part_numbers as $p_num)
                                                    <li class="fw-bold"> {{ $p_num->number }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                        <li class="fw-bold"> لا يوجد ارقام</li>
                                        @endif

                                    </td>


                                    <!--begin::Total=-->

                                    <td class="text-center pe-0 ">
                                        <span name="remain_amount" class="fw-bold  remain_itm_amount"
                                            remain-amount="{{ $item->remain_amount - $item->pending_amount }}">{{ $item->remain_amount - $item->pending_amount }}</span>
                                    </td>
                                    <!--end::Total=-->

                                    @foreach ($item->store_data as $store)
                                        <td class="text-center pe-0 bind_this">

                                            <input name="inserted_amount" class="form-control-sm inserted_ammount" type="number"
                                                store-id="{{ $store['id'] }}" min="1" value=""
                                                placeholder="{{ $store['old_amount'] }}"
                                                class="inserted_ammount text-center">
                                            <input type="hidden" class="store_idd" name="store_id"
                                                value="{{ $store['id'] }}">
                                            <input type="hidden" class="store_table_name" name="store_table_name"
                                                value="{{ $store['table_name'] }}">
                                        </td>
                                    @endforeach
                                    <!--begin::Action=-->
                                    <td class="text-center">

                                            <div class="menu-item px-3 d-flex"
                                                onclick="save_amount(this,{{ $item->order_supplier_id }},{{ $item->allpart_data[0]->id }},{{ $item->part_type_id }},{{ $item->part_id }},{{ $item->source_id }},{{ $item->status_id }},{{ $item->quality_id }},{{ $items[0]->id }})">
                                                <a class="menu-link px-3 btn btn-info save_send_amount">حفظ</a>
                                            </div>
                                            {{-- <div class="menu-item px-3 d-flex">
                                                <a href="#" class="menu-link btn btn-danger px-3"
                                                    data-kt-ecommerce-order-filter="delete_row">Delete</a>
                                            </div> --}}



                                    </td>
                                    <!--end::Action=-->
                                </tr>

                                <!--end::Table row-->
                            @endforeach



                        </tbody>
                        <!--end::Table body-->
                    </table>
                </div>


            </div>
        </div>
    </div>
@endsection
@section('js')
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <link rel="stylesheet" href="/assets/libs/sweetalert2/sweetalert2.min.css">
    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    {{-- <link rel="stylesheet" href="sweetalert2.min.css"> --}}
    <script src="{{ URL::asset('/assets/js/store/listing_items_inv.js') }}"></script>

    <script>
        //     window.Echo.channel(`SaveTransactionch`)
        // .listen('.SaveTransaction', (e) => {
        //     // alert(e['message']);
        //     var url = '/items_inv/'+e['message'];
        // 	location.href = url;
        //     // console.log(e['message']);
        // });
    </script>


    <script>
        $('.bind_this').on('keyup', function() {

            var remain_amount = parseInt($(this.closest('tr').children).find('.remain_itm_amount').attr(
                'remain-amount'));
            var inserted_amount = calcamount(this);

            if (inserted_amount <= remain_amount) {
                $(this.closest('tr').children).find('.remain_itm_amount').text(remain_amount - inserted_amount);
            } else {
                $($(this)[0].firstElementChild).val("");
                check_wrong_amount(this);
                alert("wrong_amount");
            }


        });

        function calcamount(el) {
            var sum = 0;
            for (let i = 0; i < $(el.closest('tr')).find('.inserted_ammount').length; i++) {

                var ins_amount = parseInt($($(el.closest('tr')).find('.inserted_ammount')[i]).val());
                if (ins_amount) {

                } else {
                    ins_amount = 0
                }
                sum += ins_amount;
                // sum+=


            }
            return sum;
        }

        function check_wrong_amount(el2) {
            var remain_amount = parseInt($(el2.closest('tr').children).find('.remain_itm_amount').attr('remain-amount'));
            var inserted_amount = calcamount(el2);

            if (inserted_amount <= remain_amount) {
                $(el2.closest('tr').children).find('.remain_itm_amount').text(remain_amount - inserted_amount);
            } else {
                $($(el2)[0].firstElementChild).val("");
                successAlert("wrong_amount");
            }
        }

        // var x=new KTAppEcommerceSalesListing;
        function save_amount(el, o_s_id, all_p_id, p_type_id, p_id, sorce_id, status_id, q_id, inv_id) {
            // alert(p_id);
            var data_arr = [{
                'bisc_data': ""
            }, {
                'store_data': ""
            }];
            var data_arr1 = [];
            var data_arr2 = [];
            data_arr1.push({
                'inv_id': inv_id,
                'o_s_id': o_s_id,
                'all_p_id': all_p_id,
                'p_type_id': p_type_id,
                'part_id': p_id,
                'sorce_id': sorce_id,
                'status_id': status_id,
                'quality_id': q_id
            });

            for (let i = 0; i < $(el.closest('tr')).find('.inserted_ammount').length; i++) {

                var ins_amount = parseInt($($(el.closest('tr')).find('.inserted_ammount')[i]).val());
                var store_id = parseInt($($(el.closest('tr')).find('.store_idd')[i]).val());
                var store_table_name = $($(el.closest('tr')).find('.store_table_name')[i]).val();
                if (ins_amount) {
                    data_arr2.push({
                        'store_id': store_id,
                        'ins_amount': ins_amount,
                        'store_table_name': store_table_name
                    });
                } else {
                    // ins_amount=0
                }
            }
            // data_arr['bisc_data']=data_arr1;
            data_arr[0].bisc_data = data_arr1;
            // data_arr['store_data']=data_arr2;
            data_arr[1].store_data = data_arr2;
            // data_arr.push(data_arr);
            console.log(data_arr);


            if (data_arr2.length > 0) {


                Swal.fire({
                    text: "هل تريد الحفظ ؟",
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
                        save_amount_extend(data_arr);
                        Swal.fire({
                            text: "تمت العملية بنجاح " + inv_id,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        }).then(function() {
                            // Remove current row
                            // save_amount_extend(data_arr);
                            // datatable.row($(parent)).remove().draw();
                        });
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: " لم يتم الحفظ " + inv_id,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });

            } else {
                Swal.fire({
                    text: " أدخل الكميات أولا",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn fw-bold btn-primary",
                    }
                });
            }



        }

        function save_amount_extend(data_arr) {
            console.log(data_arr);
            // var xx = JSON.stringify(data_arr);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('saveTransaction') }}",
                async: false,
                type: 'POST',
                data: {
                    'data': data_arr
                },

                success: function(response) {
                    // $(form).trigger("reset");
                    // alert(response.success)
                    console.log(response);


                },
                error: function(response) {}
            });
        }
    </script>

    <script>
        var data_inbox = {!! $data_inbox !!};
        if (data_inbox) {
            $('.inbox_trans_counter').html(data_inbox.length);
            // $('.simplebar-content').empty();
            draw_notification(data_inbox,'.kt_topbar_notifications_33')
        }
    </script>
@endsection
