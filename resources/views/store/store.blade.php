@extends('layouts.master')
@section('css')
@endsection
@section('title')
الرئيسية
@stop
@section('mainheadpage')
عنوان
@stop
@section('mainhomepage')
احمد
@stop
@section('pagename')
عادل
@stop


@section('content')


{{-- {{ $invoices[0]->order_suppliers[0]->supplier->name }} --}}
<!--begin::Main-->

<div class="main-content">
    <div class="page-content">
        <h1 class="text-center text-info">البضاعة الموجودة بمخزن {{$store_data[0]->name}}</h1>
        <div class="row">
            <table class="table align-middle table-row-dashed fs-8 gy-9" id="kt_ecommerce_sales_table">
                <!--begin::Table head-->
                <thead>
                    <!--begin::Table row-->
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        {{-- <th class="w-10px pe-2">
                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ecommerce_sales_table .form-check-input" value="1" />
                            </div>
                        </th> --}}
                        <th class="text-center min-w-50px">رقم القطعة</th>
                        <th class="text-center min-w-100px">أسم القطعة</th>
                        <th class="text-center min-w-75px">الأرقام</th>
                        <th class="text-center min-w-50px">الكمية</th>
                        <th class="text-center min-w-50px">النوع</th>


                        <th class="text-center min-w-100px">Actions</th>
                    </tr>
                    <!--end::Table row-->
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody class="fw-semibold text-gray-500">
                    @foreach ($allItems  as $key=> $item)
                        <!--begin::Table row-->

                            <tr calss="">
                                <!--begin::Checkbox-->
                                {{-- <td  class="text-center pe-0">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="1" name="check_box"/>
                                    </div>
                                </td> --}}
                                <!--end::Checkbox-->
                                <!--begin::Order ID=-->
                                <td  class="text-center pe-0" data-kt-ecommerce-order-filter="order_id">

                                    <a href="details.html" class="text-gray-800 text-hover-primary fw-bold">{{$item['part_id']}}</a>
                                    <input type="hidden" name="part_id" value="{{$item['part_id']}}">

                                </td>
                                <!--end::Order ID=-->
                                <!--begin::Customer=-->
                                <td  class="text-center pe-0">


                                    <a href="#" class="text-gray-800 text-hover-primary fs-3 fw-bold">
                                        @if( $item['p_data'])
                                                {{$item['p_data']['name']}}
                                        @endif
                                     </a>

                                    <div>
                                        <span >{{$item['source'][0] ->name_arabic}}</span>-<span>{{$item['status'][0]->name}}</span>-<span>{{$item['quality'][0]->name}}</span>
                                        <input type="hidden" name="source_id" value="{{$item['source_id']}}">
                                        <input type="hidden" name="ststus_id" value="{{$item['status_id']}}">
                                        <input type="hidden" name="quality_id" value="{{$item['quality_id']}}">
                                    </div>

                                </td>

                                <td class="text-center pe-0">
                                    @if($item['p_data'] && $item['type_id']==1 )
                                        <ul>


                                            @foreach($item['p_data']['part_numbers'] as $p_num)
                                                <li class="fw-bold"> {{ $p_num->number}}</li>
                                            @endforeach
                                        </ul>
                                    @endif

                                </td>


                                <!--begin::Total=-->

                                <td class="text-center pe-0 ">
                                    <span name="remain_amount" class="fw-bold  remain_itm_amount" remain-amount="{{$item['Tamount']}}">{{$item['Tamount']}}</span>
                                </td>

                                <td class="text-center pe-0 ">
                                    <span name="type_N" class="fw-bold  remain_itm_amount">{{$item['type_N']}}</span>
                                </td>



                                <td class="text-center">
                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                    <span class="svg-icon svg-icon-5 m-0">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon--></a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3" onclick="">
                                            <a  class="menu-link px-3 save_send_amount">حفظ</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->

                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-ecommerce-order-filter="delete_row">Delete</a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
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
<!--end:::Main-->




@endsection
@section('js')
	<!--begin::Custom Javascript(used for this page only)-->
    {{-- <script src="{{ URL::asset('/assets/js/custom/apps/ecommerce/sales/listing.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/widgets.bundle.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/custom/widgets.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script> --}}
    {{-- <script src="{{ URL::asset('/assets/js/custom/utilities/modals/create-app.js') }}"></script> --}}
    {{-- <script src="{{ URL::asset('/assets/js/custom/utilities/modals/users-search.js') }}"></script> --}}

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!--end::Custom Javascript-->
{{-- <script src="{{ mix('js/app.js') }}"></script> --}}
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
        $('.bind_this').on('keyup',function(){

            var remain_amount=parseInt($(this.closest('tr').children).find('.remain_itm_amount').attr('remain-amount'));
            var inserted_amount=calcamount(this);

            if(inserted_amount<=remain_amount){
                $(this.closest('tr').children).find('.remain_itm_amount').text(remain_amount-inserted_amount);
            }else{
                $($(this)[0].firstElementChild).val("");
                check_wrong_amount(this);
                alert("wrong_amount");
            }


        });
        function calcamount(el){
            var sum=0;
            for (let i = 0; i <$(el.closest('tr')).find('.inserted_ammount').length; i++) {

                var ins_amount=parseInt($($(el.closest('tr')).find('.inserted_ammount')[i]).val());
                if (ins_amount) {

                }else{
                    ins_amount=0
                }
                sum+=ins_amount;
                // sum+=


            }
            return sum;
        }
        function check_wrong_amount(el2){
            var remain_amount=parseInt($(el2.closest('tr').children).find('.remain_itm_amount').attr('remain-amount'));
            var inserted_amount=calcamount(el2);

                if(inserted_amount<=remain_amount){
                    $(el2.closest('tr').children).find('.remain_itm_amount').text(remain_amount-inserted_amount);
                }else{
                    $($(el2)[0].firstElementChild).val("");
                    successAlert("wrong_amount");
                }
        }

        // var x=new KTAppEcommerceSalesListing;
        function save_amount(el,o_s_id,all_p_id,p_type_id,p_id,sorce_id,status_id,q_id,inv_id){
            // alert(p_id);
            var data_arr=[{'bisc_data' : ""} , {'store_data' : "" }];
            var data_arr1=[];
            var data_arr2=[];
            data_arr1.push({'inv_id':inv_id,'o_s_id':o_s_id,'all_p_id':all_p_id,'p_type_id':p_type_id,'part_id':p_id,'sorce_id':sorce_id,'status_id':status_id,'quality_id':q_id});

            for (let i = 0; i <$(el.closest('tr')).find('.inserted_ammount').length; i++) {

                var ins_amount=parseInt($($(el.closest('tr')).find('.inserted_ammount')[i]).val());
                var store_id=parseInt($($(el.closest('tr')).find('.store_idd')[i]).val());
                var store_table_name=$($(el.closest('tr')).find('.store_table_name')[i]).val();
                if (ins_amount) {
                    data_arr2.push({'store_id':store_id,'ins_amount':ins_amount,'store_table_name':store_table_name});
                }else{
                    // ins_amount=0
                }
            }
            // data_arr['bisc_data']=data_arr1;
            data_arr[0].bisc_data=data_arr1;
            // data_arr['store_data']=data_arr2;
            data_arr[1].store_data=data_arr2;
            // data_arr.push(data_arr);
            console.log(data_arr);


            if(data_arr2.length>0){


                Swal.fire({
                    text: "هل تريد الحفظ ؟" ,
                    icon: "info",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, حفظ!",
                    cancelButtonText: "No, الغاء",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        save_amount_extend(data_arr);
                        Swal.fire({
                            text: "تمت العملية بنجاح " + inv_id ,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        }).then(function () {
                            // Remove current row
                            // save_amount_extend(data_arr);
                            // datatable.row($(parent)).remove().draw();
                        });
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text:  " لم يتم الحفظ "+inv_id ,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });

            }else{
                Swal.fire({
                            text:  " أدخل الكميات أولا" ,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
            }



        }
        function save_amount_extend(data_arr){
            console.log(data_arr);
            // var xx = JSON.stringify(data_arr);
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
                    url: "{{ route('saveTransaction') }}",
                    async : false ,
                    type:'POST',
                    data: {'data': data_arr},

                    success:function(response)
                    {
                        // $(form).trigger("reset");
                        // alert(response.success)
                    console.log(response);


                    },
                    error: function(response) {
                    }
                });
        }

    </script>

    <script>

var store_inbox={!! $store_inbox!!};
       if(store_inbox){
        $('.inbox_trans_counter').html(store_inbox.length);
        // $('.kt_topbar_notifications_3').html();
        draw_notification(store_inbox)
       }


    </script>
@endsection
