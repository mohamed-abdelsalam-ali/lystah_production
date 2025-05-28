@extends('layouts.master')
@section('css')

@endsection
@section('title')
     المحاسب
@endsection



@section('content')

    <main role="main" class="main-content ">
        <div class="page-content ">
            <h1>Accounts Receivable Report</h1>

    <div class="card">
        <div class="card-body">
            <h4>Customers Who Owe You</h4>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Partner ID</th>
                      
                        <th>Balance Receivable</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receivables as $receivable)
                    <tr>
                        <td>{{ $receivable->partner_id }}</td>
                      
                        <td>{{ number_format($receivable->balance, 2) }}</td>
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
