@extends('layouts.master')
@section('content')

<div class="main-content">
    <div class="page-content">
        <h1 class="text-danger text-center"> توزيع علي المخــــازن </h1>
        <div class="row card m-3">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table">
                <!--begin::Table head-->
                <thead>
                    <!--begin::Table row-->
                    <tr class="text-center text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="w-10px pe-2">
                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ecommerce_sales_table .form-check-input" value="1" />
                            </div>
                        </th>
                        <th class="text-center min-w-100px">رقم الفاتورة</th>
                        <th class="text-center min-w-100px">الشركة</th>
                        {{-- <th class="text-end min-w-70px">الحالة</th> --}}
                        <th class="text-center min-w-100px">المورد</th>
                        <th class="text-center min-w-100px">عدد القطع</th>
                        <th class="text-center min-w-100px">الصنف</th>
                        <th class="text-center min-w-100px">إجمالى الفاتورة</th>
                        <th class="text-center min-w-100px">العملة </th>
                        <th class="text-center min-w-100px">التاريخ </th>
                        <th class="text-center min-w-100px">Actions</th>
                        <th></th>
                    </tr>
                    <!--end::Table row-->
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody class="fw-semibold text-gray-600">


                    @if($invoices)
                        @foreach ($invoices as $inv)
                         <!--begin::Table row-->
                         <tr  class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <!--begin::Checkbox-->
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="1" />
                                </div>
                            </td>

                            <td class="text-center min-w-100px" data-kt-ecommerce-order-filter="order_id">
                                <a href="details.html" class="text-gray-800 text-hover-primary fw-bold">{{$inv->id}}</a>
                            </td>

                            <td>
                                <div class="d-flex align-items-center">

                                        <a href="../../user-management/users/view.html" class="text-gray-800 text-hover-primary fs-5 fw-bold">{{$inv->company->name}} </a>

                                </div>
                            </td>
                            <!--end::Customer=-->
                            <!--begin::Status=-->
                            {{-- <td class="text-end pe-0" data-order="Refunded">
                                <!--begin::Badges-->
                                <div class="badge badge-light-info">{{$inv->final}}</div>
                                <!--end::Badges-->
                            </td> --}}
                            <!--end::Status=-->
                            <td class="text-center pe-0">
                                <span class="fw-bold">{{$inv->order_suppliers[0]->supplier->name}}</span>
                            </td>
                            <td class="text-center pe-0">
                                <span class="fw-bold">{{count($inv->order_suppliers_with_replayorder)}}</span>
                            </td>
                            <td class="text-center pe-0">
                                @if(count($inv->order_suppliers_with_replayorder)>0)
                               <span class="fw-bold">{{ $inv->order_suppliers_with_replayorder[0]->type->name }}</span>
                                @else

                                @endif
                            </td>

                            <!--begin::Total=-->
                            <td class="text-center pe-0">
                                <span class="fw-bold">{{$inv->order_suppliers[0]->total_price }}</span>

                            </td>
                            <td class="text-center pe-0">
                                <span class="fw-bold">{{ $inv->order_suppliers[0]->currency_type->name}}</span>
                            </td>
                            <!--end::Total=-->
                            <!--begin::Date Added=-->
                            <td class="text-center" data-order="{{$inv->date->format('Y-m-d')}}">
                                <span class="fw-bold">{{$inv->date->format('d/m/Y')}}</span>

                            </td>
                            <!--end::Date Added=-->
                            <!--begin::Date Modified=-->
                            {{-- <td class="text-center" data-order="2023-01-06">
                                <span class="fw-bold">06/01/2023</span>
                            </td> --}}
                            <!--end::Date Modified=-->
                            <!--begin::Action=-->
                            <td class="text-end">
                                {{-- <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
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
                                    <div class="menu-item px-3"> --}}
                                        <a href="items_inv/{{$inv->id}}" class="menu-link px-3">توزيع</a>
                                    {{-- </div> --}}
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->

                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    {{-- <div class="menu-item px-3"> --}}
                                        <a href="#" class="menu-link px-3" data-kt-ecommerce-order-filter="delete_row">Delete</a>
                                    {{-- </div> --}}
                                    <!--end::Menu item-->
                                {{-- </div> --}}
                                <!--end::Menu-->
                            </td>
                            <td></td>
                            <!--end::Action=-->
                        </tr>
                        <!--end::Table row-->
                        @endforeach
                    @endif





                </tbody>
                <!--end::Table body-->
            </table>
        </div>
    </div>
</div>



@endsection
@section('js')
	<!--begin::Custom Javascript(used for this page only)-->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script src="{{ URL::asset('js/store/listing_buy_inv.js') }}"></script>

    <!--end::Custom Javascript-->
<!--{{-- <script src="{{ mix('js/app.js') }}"></script> --}}-->
<script>

//     window.Echo.channel(`Mynotification`)
// .listen('.test_event', (e) => {
//     alert(e['message']);
//     console.log(e['message']);
// });

    </script>
 <script>
    // $(document).ready(function(){
    // })


       </script>

    <script>
       var data_inbox={!! $data_inbox!!};
       if(data_inbox){
        $('.inbox_trans_counter').html(data_inbox.length);
        // $('.kt_topbar_notifications_33').html();
        draw_notification(data_inbox)
       }

    </script>

@endsection
