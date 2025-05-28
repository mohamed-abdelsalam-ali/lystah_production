@extends('layouts.master')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.min.js"></script>

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
            <h1 class="text-center text-info"> توزيع فاتورة علي المخــــــازن</h1>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-head">
                            <div class="row">
                                <div class="col-6">
                                    <table class="table text-capitalize">
                                        <tr>
                                            <td>Order #</td>
                                            <td class="">{{  $buyTrans->id }}</td>
                                        </tr>
                                        <tr>
                                            <td> Date</td>
                                            <td class="">{{  substr($buyTrans->date , 0,10); }}</td>
                                        </tr>
                                        <tr>
                                            <td> Creation Date</td>
                                            <td class="">{{ substr($buyTrans->creation_date , 0,10);  }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-6">

                                    <table class="table text-capitalize">
                                        <tr>
                                            <td>supplier</td>
                                            <td class="">{{  $orderSup[0]->supplier->name }}</td>
                                        </tr>
                                        <tr>
                                            <td> Total Price</td>
                                            <td class="">{{  $orderSup[0]->total_price }}</td>
                                        </tr>

                                    </table>
                                </div>

                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('/storeSend1') }}" method="POST">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="trans_id" value="{{ $buyTrans->id  }}">
                                <input type="hidden" name="orderSupplierId" value="{{ $orderSup[0]->id  }}">
                                <div class="row">
                                    <div class="col">
                                        <table class="table text-capitalize text-nowrap" >
                                            <?php $m=1; ?>
                                            @foreach ( $items as $item )
                                                <tr>
                                                    <td>{{ $m}}</td>
                                                    <td>
                                                        <span class="p-1 px-2 rounded-circle text-bg-danger">{{ $item->amount }}  </span>
                                                        <input readonly name="part[]" class="border-0 w-auto d-block" type="text" value="{{ $item->part->name }}">
                                                        <input type="hidden" name="status[]" value="{{ $item->status->id }}">
                                                        <input type="hidden" name="partIds[]" value="{{ $item->part->id }}">
                                                        <input type="hidden" name="allpart[]" value="{{ $item->id }}">
                                                        <input type="hidden" name="source[]" value="{{ $item->source->id }}">
                                                        <input type="hidden" name="quality[]" value="{{ $item->part_quality->id }}">
                                                        <span>{{ $item->status->name }}</span>
                                                        <span>{{ $item->source->name_arabic }}</span>
                                                        <span>{{ $item->part_quality->name }}</span>
                                                        <span class="d-block"> متبقي لم يتم توزيعه <span class="p-1 px-2 rounded-circle text-bg-success"> {{ $item->amount - $item->remainAmountInInvoice }}</span></span>

                                                        {{-- <input readonly class="border-0 w-auto" type="text" value="{{ $item->status->name }}">
                                                        <input readonly class="border-0 w-auto" type="text" value="{{ $item->source->name_arabic }}">
                                                        <input readonly class="border-0 w-auto" type="text" value="{{ $item->part_quality->name }}"> --}}
                                                    </td>

                                                    @for ($i =0 ; $i < count($store) ; $i++)
                                                        <td>
                                                            <label for="">{{  $store[$i]->name }}</label>
                                                            <input class="form-control " value="0" min="0" style="width: 100px" type="number" max="{{ $item->amount }}" name="store{{  $store[$i]->id }}[]" id="">

                                                        </td>
                                                    @endfor
                                                </tr>
                                                <?php $m++; ?>
                                            @endforeach
                                        </table>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10"></div>
                                    <div class="col-lg-2">
                                        <button type="submit" class="btn btn-success w-100">SAVE</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>





        </div>
    </div>



@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>




@endsection
