@extends('layouts.master')
@section('css')

@endsection
@section('title')
     المحاسب
@endsection



@section('content')

    <main role="main" class="main-content ">
        <div class="page-content ">
            <h1>   تقفيل عام مالي  <span id="currentYear"></span> </h1>
            <div class="row justify-content-center ">
                <div class="col-12 border">
                    <h2 class="text-center my-2" style="background:#0080006b">التسويات الجردية</h2>
                    <div class="row">

                        <div class="col">
                            <button class="btn btn-primary fs-18 w-100">الإيرادات المستحقة </button>
                        </div>

                        <div class="col">
                            <button class="btn btn-primary fs-18 w-100">المصروفات المستحقة </button>
                        </div>
                        <div class="col">
                            <button class="btn btn-primary fs-18 w-100">الإهلاك </button>
                        </div>

                        <div class="col">
                            <a href="/storegardMaley" class="btn btn-primary fs-18 w-100">المخزون </a>
                        </div>

                    </div>
                    <h2 class="text-center my-2" style="background:#0080006b">إغلاق الحسابات المؤقتة</h2>
                    <div class="row">
                        <div class="col">
                            <button class="btn btn-primary fs-18 w-100">صافي الربح</button>
                        </div>
                    </div>

                    <h2 class="text-center my-2" style="background:#0080006b">إعداد القوائم المالية</h2>
                    <div class="row">
                        <div class="col">
                            <button class="btn btn-primary fs-18 w-100">قائمة الدخل</button>
                        </div>
                        <div class="col">
                            <button class="btn btn-primary fs-18 w-100">  الميزانية العمومية</button>
                        </div>
                    </div>

                    <h2 class="text-center my-2" style="background:#0080006b">إعداد التقارير الضريبية</h2>
                    <div class="row">
                        <div class="col">
                            <button class="btn btn-primary fs-18 w-100">
                                الضريبة المستحقة
                            </button>
                        </div>
                    </div>
                    <hr>
                    <h2 class="text-center my-2" style="background:#0080006b">تأكيد / تصحيح</h2>
                    <div class="row border">
                        <div class="table-responsive">
                            <table class="table table-striped" id="qayditemsTbl">
                                <thead>
                                    <tr>
                                        <td>رقم الحساب</td>
                                        <td>الحساب</td>
                                        <td>الرصيد</td>
                                        <td>الجديد</td>
                                        <td>حفظ</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($end_results as $res)
                                    <tr>
                                        <td> {{ $res->branch_tree->name }}</td>
                                        <td> {{ $res->branch_tree->accountant_number }}
                                            {{-- / {{ $res->tmadin  }} / {{  $res->tdayin }} --}}
                                        </td>
                                        @php
                                            $count=$res->tmadin -  $res->tdayin;
                                            // $totaldayin += $res->tdayin;
                                            // $totalmadin += $res->tmadin;
                                            // $total += $res->tmadin -  $res->tdayin;
                                        @endphp
                                         @if ( $count>0)
                                         <td class="text-center">  {{ abs($count) }} </td>

                                         @else
                                         <td class="text-center">  {{ abs($count) }}</td>
                                         @endif

                                        <td><input class="form-control" type="text" name="" id=""></td>
                                        <td><button class="btn btn-dark">Save</button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    </main>

@endsection




@section('js')
    <script>
        $("#qayditemsTbl").dataTable({
            paginate : false
        });

        $("#currentYear").text(new Date().getFullYear())
    </script>

@endsection
