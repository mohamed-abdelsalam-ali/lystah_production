@extends('layouts.master')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.min.js"></script>

    <style>
        table {
            font-family: 'Droid Arabic Naskh', serif;
        }

        .modal-dialog {
            max-width: 80% !important;
        }
    </style>
@endsection
@section('title')
    Income
@stop


@section('content')



    <div class="main-content">
        <div class="page-content">
            <h1 class="text-center text-info"> قائمة الدخل</h1>
             <h3 class="text-center text-bg-dark"> قائمة لعرض إيرادات ومصروفات المنشأة خلال فترة مالية</h3>
           <div class="row">
                <div class="col-lg-12">
                    <table class="table table-striped table-bordered fs-20">
                        <thead class="text-bg-dark">
                            <tr>
                                <td>البيان</td>
                                <td>جزئي</td>
                                <td>كلي </td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>حساب المبيعات</td>
                                <td>{{  $sellAc['daien']  }}</td>
                                <td></td>
                            </tr>
                             <tr>
                                <td>حساب الخصوم المسموح به</td>
                                <td>{{  $discountAC['madien']  }}</td>
                                <td></td>
                            </tr>
                             <tr>
                                <td>مردودات البيع</td>
                                <td>{{ $sellRefundAc['daien'] }}</td>
                                <td></td>
                            </tr>
                             <tr class="text-bg-success">
                                <td>صافي المبيعات (ايرادات المبيعات)</td>
                                <td></td>
                                <td>{{ $sellsafi }}</td>
                            </tr>
                             <tr>
                                <td>حساب بضاعة أول المدة</td>
                                <td>{{ $beginInv['madien'] }}</td>
                                <td></td>
                            </tr>
                             <tr>
                                <td>حساب المشريات</td>
                                <td>{{  $buyAc['daien'] }}</td>
                                <td></td>
                            </tr>
                             <tr>
                                <td>حساب الخصم المكتسب</td>
                                <td>{{ $buydiscount['madien'] }}</td>
                                <td></td>
                            </tr>
                             <tr>
                                <td>حساب  المردودات</td>
                                <td>{{ $buyRefundAc['madien'] }}</td>
                                <td></td>
                            </tr>
                            {{-- <tr>
                                <td>حساب مسموحات المشتريات</td>
                                <td>{{ $buydiscount['madien'] }}</td>
                                <td></td>
                            </tr> --}}
                            <!--<tr>-->
                            <!--    <td>حساب البضاعة المتاحة للبيع</td>-->
                            <!--    <td>...</td>-->
                            <!--    <td></td>-->
                            <!--</tr>-->
                             <tr>
                                <td>حساب بضاعة أخر المدة</td>
                                <td>{{ $stores['madien']-$stores['daien'] }}  </td>
                                <td></td>
                            </tr>
                            <tr class="text-bg-success">
                               <td>حساب تكلفة البضاعة المباعة (تكلفة المبيعات)</td>
                                <td>...</td>
                                <td>{{   abs($sellcoast) }}</td>

                            </tr>
                             <tr class="text-bg-danger">
                                <td>مجمل الربح</td>
                                <td></td>
                                <td>{{ abs($sellsafi) - abs($sellcoast) }}</td>
                            </tr>
                        </tbody>

                </table>
                </div>
            </div>

             <div class="justify-content-center row">
                <div class="col-lg-6">
                    <table class="table table-striped table-bordered fs-20">

                       <tr>
                           <td>إيرادات المبيعات</td>
                           <td>{{ abs($sellsafi) }}</td>
                       </tr>
                       <tr>
                           <td>تكلفة المبيعات</td>
                           <td>{{ abs($sellcoast) }}</td>
                       </tr>
                       <tr class="text-bg-dark">
                           <td>مجمل الربح</td>
                           <td>{{ abs($sellsafi)-abs($sellcoast)  }}</td>
                       </tr>
                       <tr>
                           <td>المصاريف التشغيلية</td>
                           <td>{{ $masrof }}</td>
                       </tr>
                       {{-- <tr>
                           <td>مصروف الاستهلاك</td>
                           <td>24000</td>
                       </tr> --}}
                       <tr class="text-bg-dark">
                           <td>الربح التشغيلي</td>
                           <td>{{ abs($sellsafi)-abs($sellcoast) -  $masrof  }}</td>
                       </tr>
                       {{-- <tr>
                           <td>المصاريف الأخري</td>
                           <td>0</td>
                       </tr> --}}
                       <tr>
                           <td>خسائر بيع الأصول الثابتة</td>
                           <td>{{ $ehlak }}</td>
                       </tr>
                       <tr class="text-danger text-decoration-line-through">
                           <td>الايرادات الأخري</td>
                           <td>0</td>
                       </tr>
                       <tr class="text-danger text-decoration-line-through">
                           <td>أرباح بيع استثمارات طويلة الأجل</td>
                           <td>0</td>
                       </tr>
                       <tr class="text-bg-dark">
                           <td>صافي الربح قبل الفائدة والضريبة</td>
                           <td>{{ abs($sellsafi)-abs($sellcoast) -  $masrof - $ehlak }}</td>
                       </tr>
                       <tr>
                           <td>مصروف الفوائد (1733)</td>
                           <td>{{ $faydaa }}</td>
                       </tr>
                       <tr class="text-bg-dark">
                           <td>صافي الربح قبل الضريبة</td>
                           <td>{{ abs($sellsafi)-abs($sellcoast) -  $masrof - $ehlak - $faydaa }}</td>
                       </tr>
                       <tr>
                           <td>مصروف الضريبة(369)</td>
                           <td>{{ $dareba }}</td>
                       </tr>
                       <tr class="text-bg-dark">
                           <td>صافي الربح</td>
                           <td>{{ abs($sellsafi)-abs($sellcoast) -  $masrof - $ehlak - $faydaa -$dareba }}</td>
                       </tr>

                </table>
                </div>
            </div>

        </div>
    </div>


@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>



@endsection
