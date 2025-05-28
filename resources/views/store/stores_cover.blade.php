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
Home
@stop
@section('pagename')
Stores
@stop


@section('content')


{{-- {{ $invoices[0]->order_suppliers[0]->supplier->name }} --}}
<!--begin::Main-->
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Orders Listing</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="../../../index.html" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">eCommerce</li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Sales</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <!--begin::Filter menu-->
                    <div class="m-0">
                        <!--begin::Menu toggle-->
                        <a href="#" class="btn btn-sm btn-flex bg-body btn-color-gray-700 btn-active-color-primary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                        <span class="svg-icon svg-icon-6 svg-icon-muted me-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->Filter</a>
                        <!--end::Menu toggle-->
                        <!--begin::Menu 1-->
                        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_63b938e0781b3">
                            <!--begin::Header-->
                            <div class="px-7 py-5">
                                <div class="fs-5 text-dark fw-bold">Filter Options</div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Menu separator-->
                            <div class="separator border-gray-200"></div>
                            <!--end::Menu separator-->
                            <!--begin::Form-->
                            <div class="px-7 py-5">
                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <!--begin::Label-->
                                    <label class="form-label fw-semibold">Status:</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div>
                                        <select class="form-select form-select-solid" data-kt-select2="true" data-placeholder="Select option" data-dropdown-parent="#kt_menu_63b938e0781b3" data-allow-clear="true">
                                            <option></option>
                                            <option value="1">Approved</option>
                                            <option value="2">Pending</option>
                                            <option value="2">In Process</option>
                                            <option value="2">Rejected</option>
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <!--begin::Label-->
                                    <label class="form-label fw-semibold">Member Type:</label>
                                    <!--end::Label-->
                                    <!--begin::Options-->
                                    <div class="d-flex">
                                        <!--begin::Options-->
                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5">
                                            <input class="form-check-input" type="checkbox" value="1" />
                                            <span class="form-check-label">Author</span>
                                        </label>
                                        <!--end::Options-->
                                        <!--begin::Options-->
                                        <label class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value="2" checked="checked" />
                                            <span class="form-check-label">Customer</span>
                                        </label>
                                        <!--end::Options-->
                                    </div>
                                    <!--end::Options-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <!--begin::Label-->
                                    <label class="form-label fw-semibold">Notifications:</label>
                                    <!--end::Label-->
                                    <!--begin::Switch-->
                                    <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="" name="notifications" checked="checked" />
                                        <label class="form-check-label">Enabled</label>
                                    </div>
                                    <!--end::Switch-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Actions-->
                                <div class="d-flex justify-content-end">
                                    <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button>
                                    <button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Apply</button>
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Form-->
                        </div>
                        <!--end::Menu 1-->
                    </div>
                    <!--end::Filter menu-->
                    <!--begin::Secondary button-->
                    <!--end::Secondary button-->
                    <!--begin::Primary button-->
                    <a href="#" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Create</a>
                    <!--end::Primary button-->
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-xxl">
                <!--begin::Products-->
                <div class="card card-flush">
                    <!--begin::Card header-->
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative my-1">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                <input type="text" data-kt-ecommerce-order-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search Order" />
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-2">
                            <!--begin::Flatpickr-->
                            <div class="input-group w-250px">
                                <input class="form-control form-control-solid rounded rounded-end-0" placeholder="Pick date range" id="kt_ecommerce_sales_flatpickr" />
                                <button class="btn btn-icon btn-light" id="kt_ecommerce_sales_flatpickr_clear">
                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr088.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2" rx="1" transform="rotate(-45 7.05025 15.5356)" fill="currentColor" />
                                            <rect x="8.46447" y="7.05029" width="12" height="2" rx="1" transform="rotate(45 8.46447 7.05029)" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </button>
                            </div>
                            <!--end::Flatpickr-->
                            <div class="w-100 mw-150px">
                                <!--begin::Select2-->
                                <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Status" data-kt-ecommerce-order-filter="status">
                                    <option></option>
                                    <option value="all">All</option>
                                    <option value="Cancelled">Cancelled</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Denied">Denied</option>
                                    <option value="Expired">Expired</option>
                                    <option value="Failed">Failed</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Processing">Processing</option>
                                    <option value="Refunded">Refunded</option>
                                    <option value="Delivered">Delivered</option>
                                    <option value="Delivering">Delivering</option>
                                </select>
                                <!--end::Select2-->
                            </div>
                            <!--begin::Add product-->
                            <a href="../catalog/add-product.html" class="btn btn-primary">Add Order</a>
                            <!--end::Add product-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <div class="tab-pane fade show active" id="kt_pos_food_content_1">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-wrap d-grid gap-5 gap-xxl-9">
                                <!--begin::Card-->
                                @foreach ($Stores as $store)
                                <div class="card card-flush flex-row-fluid p-6 pb-5 mw-100">
                                    <!--begin::Body-->
                                    <div class="card-body text-center">
                                        <!--begin::Food img-->
                                        <img src="../../assets/media/stock/food/img-2.jpg" class="rounded-3 mb-4 w-150px h-150px w-xxl-200px h-xxl-200px" alt="" />
                                        <!--end::Food img-->
                                        <!--begin::Info-->
                                        <div class="mb-2">
                                            <!--begin::Title-->
                                            <div class="text-center">
                                                <span class="fw-bold text-gray-800 cursor-pointer text-hover-primary fs-3 fs-xl-1"><a href="store/{{$store->id}}">{{$store->name}}</a></span>
                                            </div>
                                            <!--end::Title-->
                                        </div>
                                        <!--end::Info-->
                                        <!--begin::Total-->
                                        <span class="text-success text-end fw-bold fs-1">------</span>
                                        <!--end::Total-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                @endforeach


                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Products-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
    <!--begin::Footer-->
    <div id="kt_app_footer" class="app-footer">
        <!--begin::Footer container-->
        <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
            <!--begin::Copyright-->
            <div class="text-dark order-2 order-md-1">
                <span class="text-muted fw-semibold me-1">2023&copy;</span>
                <a href="https://keenthemes.com/" target="_blank" class="text-gray-800 text-hover-primary">Keenthemes</a>
            </div>
            <!--end::Copyright-->
            <!--begin::Menu-->
            <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
                <li class="menu-item">
                    <a href="https://keenthemes.com/" target="_blank" class="menu-link px-2">About</a>
                </li>
                <li class="menu-item">
                    <a href="https://devs.keenthemes.com/" target="_blank" class="menu-link px-2">Support</a>
                </li>
                <li class="menu-item">
                    <a href="https://1.envato.market/EA4JP" target="_blank" class="menu-link px-2">Purchase</a>
                </li>
            </ul>
            <!--end::Menu-->
        </div>
        <!--end::Footer container-->
    </div>
    <!--end::Footer-->
</div>
<!--end:::Main-->




@endsection
@section('js')
	<!--begin::Custom Javascript(used for this page only)-->
    <script src="{{ URL::asset('/assets/js/custom/apps/ecommerce/sales/listing_items_inv.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/widgets.bundle.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/custom/widgets.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
    {{-- <script src="{{ URL::asset('/assets/js/custom/utilities/modals/create-app.js') }}"></script> --}}
    {{-- <script src="{{ URL::asset('/assets/js/custom/utilities/modals/users-search.js') }}"></script> --}}

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

       if(data_inbox){
        $('.inbox_trans_counter').html(data_inbox.length);
        // $('.kt_topbar_notifications_33').html();
        draw_notification(data_inbox)
       }

    </script>
@endsection
