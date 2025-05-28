@extends('layouts.master')
@section('css')

@endsection
@section('title')
     المحاسب
@endsection



@section('content')

    <main role="main" class="main-content ">
        <div class="page-content ">
            <h1>Accounts Payable Report</h1>

    <div class="card">
        <div class="card-body">
            <h4>Suppliers Owed Money</h4>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Partner ID</th>
                        <th>Balance Owed</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payables as $payable)
                    <tr>
                        <td>{{ $payable->partner_id }}</td>
                        <td>{{ number_format($payable->balance, 2) }}</td>
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
