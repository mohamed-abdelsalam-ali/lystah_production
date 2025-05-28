@extends('layouts.master')
@section('css')

    <style>
        table {
            font-family: 'Droid Arabic Naskh', serif;
        }

        .modal-dialog {
            max-width: 1000px !important;
        }
    </style>
@endsection
@section('title')
    Invoice
@stop


@section('content')



    <div class="main-content">
        <div class="page-content">
            {{-- <h1 class="text-center text-info">. Invoice</h1> --}}



            <div class="row">
                <div class="col-lg-10"></div>
                <div class="col-lg-2">
                    <a href="/storeManage" class="border-0 btn btn-close-white btn-light text-capitalize w-100" data-mdb-ripple-color="dark"><i
                        class="fas fa-print text-primary"></i>  Home</a>
                </div>
            </div>
                    <div class="card">
                        <div class="card-body">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="text-center">
                                            <i class="fab fa-mdb fa-4x ms-0" style="color:#5d9fc5 ;"></i>
                                            <p class="pt-0">
                                                Invoice
                                            </p>
                                        </div>

                                    </div>
                                </div>

                                    <div class="row">
                                        <div class="col-xl-8 col-lg-8 col-8">
                                            <ul class="list-unstyled">
                                                <li class="text-muted">To: <span style="color:#5d9fc5 ;">{{ $orderSup[0]->supplier->name }}</span>
                                                </li>
                                                <li class="text-muted">{{ $orderSup[0]->supplier->tel01 }}</li>
                                                <li class="text-muted">{{ $orderSup[0]->supplier->address }}</li>
                                                <li class="text-muted"><i class="fas fa-phone"></i> 123-456-789</li>
                                            </ul>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-4">
                                            <p class="text-muted">{{ $company[0]->company->name }}</p>
                                            <ul class="list-unstyled">
                                                <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i>
                                                    <span class="fw-bold">ID:</span>#{{ $invoice->id }}</li>
                                                <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i>
                                                    <span class="fw-bold">Creation Date: </span> {{ substr($invoice->creation_date , 0,10)  }}</li>
                                                <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i>
                                                    <span
                                                        class="badge bg-warning text-black fw-bold">
                                                        {{ $company[0]->company->telephone }}</span></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="row my-2 mx-1 justify-content-center">
                                        <table class="table table-striped table-borderless">
                                            <thead style="background-color:#84B0CA ;" class="text-white">
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Item</th>
                                                    <th scope="col">Qty</th>
                                                    <th scope="col">Unit Price</th>
                                                    <th scope="col">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $m = 1;
                                                    $tax = 0;
                                                    $subtotal = 0;
                                                    $total = 0;
                                                @endphp
                                                @foreach ( $items as $item )
                                                    <tr>

                                                        <th scope="row">{{ $m }}</th>
                                                        <td>{{ $item->part[0]->name }}</td>
                                                        <td>{{ $item->amount }}</td>
                                                        <td>{{ $item->price }}</td>
                                                        <td>{{ $item->amount * $item->price }}</td>
                                                        @php
                                                            $m++;
                                                            $subtotal += $item->amount * $item->price ;
                                                        @endphp
                                                    </tr>
                                                @endforeach

                                            </tbody>

                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-8">
                                            <p class="ms-3">Add additional notes and payment information</p>

                                        </div>
                                        <div class="col-xl-3">
                                            <ul class="list-unstyled">
                                                <li class="text-muted ms-3"><span
                                                        class="text-black me-4">SubTotal</span>${{ $subtotal }}</li>
                                                <li class="text-muted ms-3 mt-2"><span
                                                        class="text-black me-4">Tax</span>$ {{ ($subtotal * $orderSup[0]->tax / 100) }} </li>
                                            </ul>

                                            <p class="text-black float-start"><span class="text-black me-3"> paied
                                                </span><span style="font-size: 25px;">$ {{ $orderSup[0]->paied }}</span></p>
                                            <p class="text-black float-start"><span class="text-black me-3"> Total
                                                    </span><span style="font-size: 25px;">{{ $orderSup[0]->currency_type->name }} {{ $subtotal + ($subtotal * $orderSup[0]->tax / 100)  }}</span></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-xl-10">
                                            <p>Thank you for your purchase</p>
                                        </div>
                                        <div class="col-xl-2">

                                        </div>
                                    </div>

                        </div>
                    </div>



        </div>
    </div>



@endsection

@section('js')

    <script>
        $(window).on('load', function() {
            window.print();
        });
    </script>


@endsection
