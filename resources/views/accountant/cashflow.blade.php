@extends('layouts.master')
@section('css')

@endsection
@section('title')
     المحاسب
@endsection



@section('content')

    <main role="main" class="main-content ">
        <div class="page-content ">
            <h1>Cash Flow Report</h1>
            <div class="card mb-4">
                <div class="card-body">
                    <h4>Summary</h4>
                    <p><strong>Total Cash In:</strong> {{ number_format($cashSummary->total_cash_in, 2) }}</p>
                    <p><strong>Total Cash Out:</strong> {{ number_format($cashSummary->total_cash_out, 2) }}</p>
                    <p><strong>Net Cash:</strong> {{ number_format($cashSummary->net_cash, 2) }}</p>
                </div>
            </div>
        
            <div class="card">
                <div class="card-body">
                    <h4>All Cash Transactions</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Account Number</th>
                                <th>Account Name</th>
                                <th>Reference</th>
                                <th>Label</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $txn)
                            <tr>
                                <td>{{ $txn->created_at }}</td>
                                <td>{{ $txn->ac_number }}</td>
                                <td>{{ $txn->name_en }}</td>
                                <td>{{ $txn->refrence }}</td>
                                <td>{{ $txn->label }}</td>
                                <td>{{ number_format($txn->debit, 2) }}</td>
                                <td>{{ number_format($txn->credit, 2) }}</td>
                                <td>{{ $txn->desc }}</td>
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
