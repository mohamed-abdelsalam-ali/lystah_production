@extends('layouts.posMaster')
@section('css')
<style>
    th{
        /*white-space: nowrap !important;*/
        background: #92c01c;
    }
</style>
@endsection
@section('js')
@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">التقرير اليومي</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Daily Report </li>
                                <li class="breadcrumb-item"><a href="home">Home</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            <!--<h2>لم يتم الانتهاء بعد</h2>-->

            <div class="card">
                <div class="border-3 border-ridge card-body">
                    <div class="row mb-2">
                        <div class="col-lg-4">
                            <!--<h2>اليوم {{ now()->format('d-m-Y')  }}</h2>-->
                            <h2>اليوم {{ explode(' ', $date)[0]; }}</h2>
                        </div>
                        <div class="col-lg-4 text-center">
                            <h2>تقرير حركة الأصناف</h2>
                        </div>
                        <div class="col-lg-4">

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <table>
                                <thead>
                                    <tr>
                                        <th>كود الصنف</th>
                                        <th>اسم الصنف</th>
                                        <th> المنشأ</th>
                                        <th> الحالة</th>
                                        <th> الجودة</th>
                                        <th>رصيد بداية اليوم</th>
                                        <th>مشتريات اليوم</th>
                                        <th>التحويلات المستلمة من المخازن</th>
                                        <th>الرصيد المتاح</th>
                                        <th>المبيعات</th>
                                        <th>التحويلات الي مواقع اخري</th>
                                        <th>المتاح بعد المبيعات</th>
                                        <th>مردودات</th>
                                        <th>مردودات تم توزيعها</th>
                                        <th>مردودات غير موزعه</th>
                                        <th>رصيد الصنف نهاية اليوم</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($storeInvoiceLog as $item )
                                        <tr>
                                            <td>{{ $item->Code }}</td>
                                            <td>{{ isset($item->part) ? $item->part->name : '-' }}</td>
                                            <td>{{ isset($item->source) ? $item->source->name_arabic : '-' }}</td>
                                            <td>{{ isset($item->part_status) ? $item->part_status->name : '-' }}</td>
                                            <td>{{ isset($item->quality) ? $item->quality->name : '-' }}</td>
                                            <td> {{ $item->first_old_amount }}</td>
                                            <td> {{ $item->buy_items->sum('amount') }}</td>
                                            <td>{{ $item->storeLogs->sum('amount') }}</td>
                                            <td>{{ $item->first_old_amount  + $item->storeLogs->sum('amount') }}</td>
                                            <td>{{ $item->amount }}</td>
                                            <td>{{ count($item->storeLogsDone) }}</td>
                                            <td>  {{ $item->first_old_amount  + $item->storeLogs->sum('amount') - $item->amount - $item->storeLogsDone->sum('amount') }}</td>
                                            <td>{{ isset($item->refundItems) ? $item->refundItems->sum('r_amount') : 0 }}</td>
                                            <td>{{ $item->sections->sum('storeAmount')-$item->sections->sum('sectionAmount')  > 0  ? 0  : $item->sections->sum('storeAmount')-$item->sections->sum('sectionAmount') }}</td>
                                            <td>{{ $item->sections->sum('storeAmount')-$item->sections->sum('sectionAmount') }}</td>
                                            <td>{{ $item->first_old_amount  + $item->storeLogs->sum('amount') - $item->amount - $item->storeLogsDone->sum('amount') + (isset($item->refundItems) ? $item->refundItems->sum('r_amount') : 0) }}</td>

                                            <td class="d-none"><a href="/partDetails/{{ isset($item->part) ? $item->type_id : 1 }}/{{ isset($item->part) ? $item->part->id : 0}}">i</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>




        </div>

    @endsection
    @section('js')
        <script>
          var store_data = {!! $store_data !!};

        </script>

    @endsection
