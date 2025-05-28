@extends('layouts.master')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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
    Checkout
@stop


@section('content')

   

    <div class="main-content">
        <div class="page-content">
            <h1 class="text-center text-info">. Checkout</h1>
            <form action="saveInv" method="post">
                @csrf
                <div class="row">
                    <div class="col-5">
                        <div class="row">
                            <div class="col rounded">
                                <h3 class="mt-2"> <i class="bx bx-id-card fs-22"></i>Invoice Details</h3>
                                <hr>

                                <div class="row">
                                    <div class="col">
                                        <label for="">Client</label>
                                        <label for="" id="clientmad" class="float-end">0</label>
                                        <select name="clientsSlct" class="form-select" id="clientsSlct" required>
                                            <option selected disabled value="">Select Client</option>
                                                @foreach ($clients as $client)
                                                    <option data-clientmad="{{$client->client_raseed - $client->invoices->sum('actual_price') - $client->invoices->sum('paied') }}" value="{{ $client->id }}">{{ $client->name }} ( {{ $client->tel01 }} ) </option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="">Store</label>
                                        <input type="hidden" name="storeId" value="{{ isset($invoiceItems[0]) ? $invoiceItems[0]->Sstore : 0 }}">
                                        <select name="storeSlct" class="form-select" disabled id="storeSlct">
                                            <option selected disabled value="0">Select Client</option>
                                                @foreach ($stores as $store)
                                                    @if ($store->id == $invoiceItems[0]->Sstore )
                                                        <option selected  value="{{ $store->id }}">{{ $store->name }} </option>
                                                    @else
                                                        <option value="{{ $store->id }}">{{ $store->name }} </option>
                                                    @endif

                                                @endforeach

                                        </select>
                                    </div>
                                </div>

                                <div class="row border border-end-groove m-2 p-2">
                                    <div class="col">
                                        <label for="">Taxes</label>
                                        <br>
                                            @foreach ($taxes as $tax)

                                                <input class="form-check-input" name="tax[]" type="checkbox" value="{{  $tax->value }}" id="tax{{  $tax->id }}">
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    {{ $tax->name }} ( {{ $tax->value }} % )
                                                </label>
                                            @endforeach
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="">Payment Method</label>
                                    <div class="col">

                                        <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                            <label class="btn btn-primary">
                                                <input name="payradio" checked type="radio"> Cash
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                            <label class="btn btn-secondary">
                                                <input name="payradio" type="radio"> Visa
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                            <label class="btn btn-danger">
                                                <input name="payradio" type="radio"> Installment
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="cashpay">

                                        <div class="col mb-3">
                                        <label for="" class="form-label">paied</label>
                                        <input type="text" class="form-control" name="invPaied" required id="invPaied" aria-describedby="helpId" placeholder="">

                                        </div>
                                        <div class="col mb-3">
                                            <label for="" class="form-label"> Discount ( بالجنية )</label>
                                            <input type="text" class="form-control" name="invDiscount" value="0" id="invDiscount" aria-describedby="helpId" placeholder="">


                                        </div>
                                        <div class="col mb-3">
                                            <label for="" class="form-label">Remain ( مديونية ) </label>

                                            <input type="text" class="form-control" readonly name="invMad" value="0" id="invMad" aria-describedby="helpId" placeholder="">

                                        </div>

                                </div>
                                <div class="row">
                                    <div class="col">
                                        <button class="btn btn-success w-100" type="submit"> Place Order</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="row">
                            <div class="bg-white col rounded text-center">
                                <h3 class="mt-2"> <i class="bx bx-shopping-bag fs-22"></i> Card Summary</h3>
                                <hr>
                                <table class="table" id="invoiceItems">
                                    <?php $i=0;?>
                                    @foreach ($invoiceItems as $item )
                                        <tr>
                                            <td class="d-none">
                                                <button onclick="RemoveItem(this)" type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                                                    <i class="bx bx-trash fs-22 text-danger"> </i>
                                                </button>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col">
                                                        <input type="hidden" name="items_part[]" value="{{ $item->id }}-{{ $item->amount }}">
                                                        <input type="hidden" name="sale_type[]" class="saletype" value="0">
                                                        {{-- <input type="hidden" name="items_source[]" value="{{ $item->source[0]->id }}">
                                                        <input type="hidden" name="items_status[]" value="{{ $item->status[0]->id }}">
                                                        <input type="hidden" name="items_quality[]" value="{{ $item->quality[0]->id }}"> --}}
                                                        <span name="itemAmount[]" class="itemAmount"> {{ $item->amount }}</span>
                                                        <span class="text-info m-1"> X </span>
                                                        {{ $item->name }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        {{ $item->source[0]->name_arabic }} /
                                                        {{ $item->status[0]->name }} /
                                                        {{ $item->quality[0]->name }}
                                                    </div>
                                                </div>


                                            </td>
                                            <td>

                                                @foreach ( $item->price1 as $itemPrice )

                                                    <label>{{ $itemPrice->price }}</label>
                                                    <input checked  type="radio" name="price{{ $i }}[]" class="form-check-input priceTypeCls" data-vv="{{ $itemPrice->price }}" value="{{ $itemPrice->sale_type }}" id="">

                                                @endforeach
                                                <?php $i++?>
                                            </td>
                                            <td>
                                                {{-- <select class="form-select" name="" id="">
                                                    <option disabled selected value="0">Select Store</option> --}}
                                                    @foreach ( $item->stores as $itemStore )
                                                        @if ($itemStore->storepartCount > 0)
                                                        <div class="storeCls" value="{{ $itemStore->id }}">{{ $itemStore->storepartCount }} <span class="text-info"> X </span>{{ $itemStore->name }}  </div>
                                                        {{-- <option value="{{ $itemStore->id }}">{{ $itemStore->storepartCount }} <span class="text-info"> X </span>{{ $itemStore->name }}  </option> --}}
                                                        @endif

                                                    @endforeach
                                                {{-- </select> --}}

                                            </td>
                                            <td class="itemPriceCls">
                                                {{ $item->amount*$item->price }}
                                            </td>
                                        </tr>

                                    @endforeach
                                    <tfoot>
                                        <tr class="text-end">
                                            <td colspan="4">
                                                <input type="hidden" name="subtotal" id="subtotal" value="{{ isset($invoicePrice) ? $invoicePrice : 0 }}">
                                                <input type="hidden" name="total_tax" id="tax" value="0">
                                                <input type="hidden" name="total" id="total" value="{{ isset($invoicePrice) ? $invoicePrice : 0 }}">
                                                <span> SubTotal </span> : <span class="fs-3" id="itemsTotal" name="itemsTotal"> {{ $invoicePrice }} </span>
                                            </td>
                                        </tr>
                                        <tr class="text-end">
                                            <td colspan="4">
                                                <span> TAX </span> : <span class="fs-3" id="invTaxTotal" name="invTaxTotal"> 00 </span>
                                            </td>
                                        </tr>
                                        <tr class="text-end">
                                            <td colspan="4">
                                                <span> Total </span> : <span class="fs-3 px-4 text-bg-info" id="invTotal" name="invTotal"> {{ $invoicePrice }} </span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>



@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script src="{{ URL::asset('js/checkout.js') }}"></script>


@endsection
