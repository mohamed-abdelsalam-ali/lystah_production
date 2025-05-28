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
   Sell Invoices
@stop


@section('content')


    <div class="main-content">
        <div class="page-content">
            <h1 class="text-center text-info">فواتير البيع</h1>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-head">

                        </div>
                        <div class="card-body">
                            <table id="partsDT" class="table table-striped table-bordered cell-border " style="width:100%">
                                <thead style="background:#5fcee78a">
                                    <tr>
                                        <th class="text-center">INV NO</th>
                                        <th class="sorting sorting_asc">Data</th>
                                        <th class="sorting sorting_asc">invoiceitemCount</th>
                                        <th>Client</th>
                                        <th>Store</th>
                                        <th>Caisher</th>
                                        <th>Invoice Total</th>
                                        <th>Qayed</th>
                                        <th>Info</th>
                                        <th>Print</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>

                            </table>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


<script>

    $(document).ready(function () {


    $("#invDate").val(new Date().toJSON().slice(0,10)).trigger('chage');

    var table = $('#partsDT').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 50,
        deferRender: true,
        destroy: true,
        ajax: "sellInvData",
         "order": [
                    [0, 'desc']
                ],
        // columns: [

        //     {data: 'Invoice_id', name: 'Invoice_id' ,className : 'text-center' },
        //     {data: 'date', name: 'date' },
        //     {data: 'invoiceitemCount', name: 'invoiceitemCount' },
        //     {data: 'client_name', name: 'client_name'  },
        //     {data: 'store_name', name: 'store_name' },
        //     {data: 'casher', name: 'casher' },
        //     {data: 'invoice_total', name: 'invoice_total'  },
        //     {data: 'qaydNo', name: 'qaydNo'  },
        //     {data: 'view', name: 'view'  },
        //     {data: 'action', name: 'action' },

        // ],
        columns: [
            {data: 'Invoice_id', name: 'id'},                  // maps to `id`
            {data: 'date', name: 'date', orderable: false},                      // maps to `date`
            {data: 'invoiceitemCount', name: 'invoiceitemCount', orderable: false},
            {data: 'client_name', name: 'client.name' , orderable: false},        // relation: client
            {data: 'store_name', name: 'store.name' , orderable: false},          // relation: store
            {data: 'casher', name: 'casher_id'},               // maps to `casher_id`
            {data: 'invoice_total', name: 'actual_price'},     // derived from price - discount
            {data: 'qaydNo', name: 'qaydNo', orderable: false},
            {data: 'view', name: 'view', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],

    });
    // GET SOURCE - STATUS - QUALITY



});
</script>


@endsection
