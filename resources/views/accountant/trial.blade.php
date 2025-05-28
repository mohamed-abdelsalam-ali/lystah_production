@extends('layouts.master')
@section('css')

@endsection
@section('title')
     المحاسب
@endsection



@section('content')

    <main role="main" class="main-content ">
        <div class="page-content ">
            <h1>Trial Balance Report</h1>

    <div class="card">
        <div class="card-body">
            <h4>Accounts Summary</h4>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Account Number</th>
                        <th>Account Name (EN)</th>
                        <th>Account Name (AR)</th>
                        <th>Total Debit</th>
                        <th>Total Credit</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trialBalance as $account)
                    <tr>
                        <td>{{ $account->ac_number }}</td>
                        <td>{{ $account->name_en }}</td>
                        <td>{{ $account->name_ar }}</td>
                        <td>{{ number_format($account->total_debit, 2) }}</td>
                        <td>{{ number_format($account->total_credit, 2) }}</td>
                        <td>{{ number_format($account->balance, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
    
        
        </div>




    </main>

@endsection




@section('js')
    <script>
       
    </script>

@endsection
