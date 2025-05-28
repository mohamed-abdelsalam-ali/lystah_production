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
            <h1 class="text-center text-info"> ميزان المراجعة </h1>
             <h3 class="text-center text-bg-dark"> قائمة تعرض الحسابات وأرصدتها . مجموع الأرصدة المدينة = مجموع الأرصدة الدائنة</h3>
           <div class="row">
                <div class="col-lg-12">
                    <table class="table table-striped table-bordered fs-20">
                        <thead class="text-bg-dark">
                            <tr>
                                <td>الحساب</td>
                                <td>الاجمالي مدين</td>
                                <td>الاجمالي دائن </td>
                            </tr>    
                        </thead>
                        <tbody>
                             
                            @foreach ($mainAc as $ac)
                            <tr>
                                <td>{{ $ac->name }}</td>
                                <td>{{ $ac->total[0] }}</td>
                                <td>{{ $ac->total[1] }}</td>
                                <!--<td></td>-->
                                <!--<td></td>-->
                            </tr> 
                            @endforeach
                             <tr class="text-bg-info">
                                <td></td>
                                @if($SumMadein-$SumDaien > 0)
                                    <td>{{ $SumMadein-$SumDaien }}</td>
                                    <td>- </td>
                                @else
                                    <td>-</td>
                                    <td>{{ $SumDaien-$SumMadein }} </td>
                                @endif
                                
                                <!--<td></td>-->
                                <!--<td></td>-->
                            </tr>    
                        </tbody>
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
