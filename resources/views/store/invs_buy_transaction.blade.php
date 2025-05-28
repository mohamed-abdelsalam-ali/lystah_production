@extends('layouts.master')
@section('title')
    Store Transaction
@stop

@section('content')
@section('css')


<div class="main-content">
    <div class="page-content">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">Buy Invoices</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active">Buy Invoices</li>
                            <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <div class="card ">
            <div class="card-body  table-responsive">

                    <table  id="kt_ecommerce_sales_tablez"class="table table-striped table-bordered cell-border " style="width:100%">
                        <thead style="background:#5fcee78a">
                        <tr class="text-center  text-uppercase">

                            <th >رقم الفاتورة</th>
                            <th >الشركة</th>
                           <th >المورد</th>
                            <th >عدد القطع</th>
                              {{-- <th >الصنف</th> --}}
                           <th >إجمالى الفاتورة</th>
                           <th >العملة </th>
                              <th >التاريخ </th>
                           <th >Actions</th>
                        </tr>
                    </thead>

                    <tbody class="fw-semibold ">
{{--

                        @if($invoices)
                            @foreach ($invoices as $inv)
                             <tr  class="">

                                <td class="text-center min-w-100px" data-kt-ecommerce-order-filter="order_id">
                                    <a href="details.html" class="text-gray-800 text-hover-primary fw-bold">{{$inv->id}}</a>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">

                                            <a href="../../user-management/users/view.html" class="text-gray-800 text-hover-primary fs-5 fw-bold">{{$inv->company->name}} </a>

                                    </div>
                                </td>

                                <td class="text-center pe-0">
                                    {{$inv->order_suppliers[0]->supplier->name}}
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

                                <td class="text-center pe-0">
                                    <span class="fw-bold">{{isset($inv->order_suppliers[0]) ? $inv->order_suppliers[0]->total_price : 0 }}</span>

                                </td>
                                <td class="text-center pe-0">
                                    <span class="fw-bold">{{ isset($inv->order_suppliers[0]) ? $inv->order_suppliers[0]->currency_type->name : '-'}}</span>
                                </td>

                                <td class="text-center" data-order="{{$inv->date->format('Y-m-d')}}">
                                    <span class="fw-bold">{{$inv->date->format('d/m/Y')}}</span>

                                </td>

                                <td class="text-end">

                                            <a href="items_inv/{{$inv->id}}" class="menu-link px-3">توزيع</a>

                                            <a href="#" class="menu-link px-3" data-kt-ecommerce-order-filter="delete_row">Delete</a>

                                </td>
                                <!--end::Action=-->
                            </tr>
                            @endforeach
                        @endif
 --}}




                    </tbody>

                    <!--end::Table body-->
                </table>

            </div>
        </div>
    </div>
</div>



@endsection
@section('js')




    <script src="{{ URL::asset('js/store/listing_buy_inv.js') }}"></script>

    <!--end::Custom Javascript-->
<!--!{{-- <script src="{{ mix('js/app.js') }}"></script> --}}-->
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



    //    $(document).ready(function(){
    //     // console.log(invoices)
    //      if(data_inbox){
    //         $('.inbox_trans_counter').html(data_inbox.length);
    //         // $('.kt_topbar_notifications_33').html();
    //         // draw_notification(data_inbox)
    //     }
    //     $('.inbox_trans_counter').html(data_inbox.length);
    //     if(data_inbox.length > 0){
    //         playAudio();
    //     }
    //         // $($("#notificationItemsTabContent").find('.simplebar-content')[0]).empty();
    //         draw_notification(data_inbox,$("#notificationItemsTabContent").find('.simplebar-content')[0])

    // })
    </script>

@endsection
