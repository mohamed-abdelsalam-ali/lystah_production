@extends('layouts.master')
@section('css')
    <style>
        body {
            background-color: #f8f9fa;
            /* font-family: 'Tajawal', sans-serif; */
        }
        .containerx {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h4 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .form-container {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-control {
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .btn {
            border-radius: 5px;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }
        .btn-success {
            background-color: #2ecc71;
            border-color: #2ecc71;
        }
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        .table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .table th {
            background-color: #67b1736e;
            color: #2c3e50;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }
        .input-group {
            display: flex;
            align-items: center;
        }
        .input-group .form-control {
            flex: 1;
        }
        .input-group .btn {
            margin-left: 10px;
        }
    </style>
@endsection
@section('title')
    Refund Sattelment
@stop
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-primary " style="z-index: 88888 !important;" role="alert">
            <button type="button" class="btn-close bg-black text-white" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ $message }}
        </div>
    @endif

    <div class="main-content">
        <div class="page-content">
          
            <div class="row card">
                {{-- <h4 class="collapsible"> {{  $item->created_at}}  </h4> --}}
                
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3 d-none">
                                <label for="supplierFilter">Filter by Supplier:</label>
                                <select id="supplierFilter" class="form-control">
                                    <option value="">All Suppliers</option>
                                    <!-- Dynamic options will be appended here -->
                                </select>
                            </div>
        
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="transactionFilter">Filter by Transaction ID:</label>
                                <select id="transactionFilter" class="form-control">
                                    <option value="">All Transactions</option>
                                    <!-- Dynamic options for transaction IDs will be added here -->
                                </select>
                            </div>
                            
                        </div>                    
                       
                        <div class="col pt-4"> <button class="btn btn-sm btn-secondary" id="clearFilter">Clear Filter</button></div>
                        <div class="col text-center border pt-4">
                            <select id="payment_method" class="form-select mb-3" >

                                <option class="text-center" value="" selected disabled>اختر اسم الحساب
                                </option>
                                @foreach ($bank_types as $bank)
                                    <option class="text-center"  type-name="bank"
                                        value="{{ $bank->accountant_number }}">{{ $bank->bank_name }} </option>
                                @endforeach


                                @foreach ($store_safe as $safe)
                                    <option class="text-center" type-name="store"
                                        value="{{ $safe->safe_accountant_number }}">{{ $safe->name }}
                                    </option>
                                @endforeach



                            </select>
                            <span id="filterValue">0</span><span>  ج م </span>
                            <input type="number" name="" id="">
                            <button id="submitRefund" class="btn btn-sm btn-primary" >سداد مديونية</button>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-striped table-bordered table-hover bg-white fs-15" id="refTable">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Name</td>
                                        <td>Supplier</td>
                                        <td>Item Name</td>
                                        <td>Source</td>
                                        <td>Status</td>
                                        <td>Quality</td>
                                        <td>Amount</td>
                                        <td>Price</td>
                                        <td>Currency</td>
                                        <td>EGP Price</td>
                                        <td>Tax</td>
                                        <td>Coasting</td>
                                        <td>المدفوع علي الفاتورة</td>
                                        <td class="d-none">sid</td>
                                        <td class="d-none">tid</td>
                                        <td class="d-none">rwid</td>
                                        <td class="d-none">osid</td>
                                        <td class="d-none">partId</td>
                                        <td class="d-none">sourceId</td>
                                        <td class="d-none">statusId</td>
                                        <td class="d-none">qualityId</td>
                                        <td class="d-none">type</td>
                                        {{-- <td></td> --}}
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($refund_items as $item)
                                
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>فاتورة شراء رقم {{  isset($item->buyTransaction) ?  $item->buyTransaction->id : 0 }}</td>
                                            <td>{{  $item->order_supplier->supplier->name }}</td>
                                            <td>{{  $item->item->name }}</td>
                                            <td>{{  $item->source->name_arabic }}</td>
                                            <td>{{  $item->status->name }}</td>
                                            <td>{{  $item->part_quality->name }}</td>
                                            <td>{{  $item->amount }}</td>
                                            <td>{{  $item->buy_price }}</td>
                                            <td>{{  $item->currency_type->name }}</td>
                                            <td>{{  $item->buy_value_curcency * $item->amount * $item->buy_price  }} ج م </td>
                                            <td>{{  $item->tax }}</td>
                                            <td>{{  $item->buy_costing }}</td>
                                            <td>{{  $item->order_supplier->paied }}</td>
                                            <td class="d-none">{{  $item->order_supplier->supplier->id }}</td>
                                            <td class="d-none">{{  $item->buyTransaction->id }}</td>
                                            <td class="d-none">{{  $item->id }}</td>
                                            <td class="d-none">{{  $item->order_supplier->id }}</td>
                                            <td class="d-none">{{  $item->item->id }}</td>
                                            <td  class="d-none">{{  $item->source->id }}</td>
                                            <td  class="d-none">{{  $item->status->id }}</td>
                                            <td  class="d-none">{{  $item->part_quality->id }}</td>
                                            <td  class="d-none">{{  $item->type_id }}</td>
                                            {{-- <td><button class="btn btn-sm btn-primary">Add to mad</button></td> --}}
                                            
                                        </tr>
                                        @empty  
                                            <tr><td colspan="15" class="text-center">No data available</td></tr>
                                        @endforelse
                                        
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
    <script>
      var table;
      $(document).ready(function() {
        table = $("#refTable").DataTable({
            ordering: true,
            paging: false,
            dom: 'Bfrtip',
            buttons: ['print', 'excel']
        });

        function updateEGPPriceSum() {
            var sum = 0;
            // Loop through all rows in the table and add the EGP Price values
            table.rows({ search: 'applied' }).every(function() {
                var data = this.data();
                var price = parseFloat(data[10].replace(' ج م', '').replace(',', '')); // Clean the price and convert to number
                if (!isNaN(price)) {
                    sum += price;
                }
            });
            // Update the span with the calculated sum
            $('#filterValue').text(sum.toFixed(2)); // Update with 2 decimal places
        }
        // Get all unique suppliers from the table (column index 2)
        var suppliers = [];
        table.column(2).data().unique().each(function(value, index) {
            if (value && !suppliers.includes(value)) {
                suppliers.push(value);
            }
        });

        // Get all unique buyTransaction IDs from the table (column index 1, which is where buyTransaction ID is located)
        var transactionIds = [];
        table.column(1).data().unique().each(function(value, index) {
            if (value && !transactionIds.includes(value)) {
                transactionIds.push(value);
            }
        });

        // Populate the supplier filter dropdown
        var supplierFilter = $('#supplierFilter');
        supplierFilter.empty(); // Clear existing options
        supplierFilter.append('<option value="">All Suppliers</option>');
        suppliers.forEach(function(supplier) {
            supplierFilter.append('<option data-id="'+ supplier+'" value="' + supplier + '">' + supplier + '</option>');
        });

        // Populate the transaction ID filter dropdown
        var transactionFilter = $('#transactionFilter');
        transactionFilter.empty(); // Clear existing options
        transactionFilter.append('<option value="">All Transactions</option>');
        transactionIds.forEach(function(transactionId) {
            transactionFilter.append('<option data-id="'+ transactionId+'"  value="' + transactionId + '">' + transactionId + '</option>');
        });

        // Filter by supplier (column 2)
        $('#supplierFilter').on('change', function() {
            var supplier = $(this).val();
            table.column(2).search(supplier ? '^' + supplier + '$' : '', true, false).draw();
            updateEGPPriceSum();
        });

        // Filter by buyTransaction ID (column 1)
        $('#transactionFilter').on('change', function() {
            var transactionId = $(this).val();
            table.column(1).search(transactionId ? '^' + transactionId + '$' : '', true, false).draw();
            updateEGPPriceSum();
        });

        // Optional: Clear filter button to reset both filters
        $('#clearFilter').on('click', function() {
            $('#supplierFilter').val('');
            $('#transactionFilter').val('');
            table.column(1).search('').draw();  // Clear transaction ID filter
            table.column(2).search('').draw();  // Clear supplier filter
            updateEGPPriceSum();
        });

   

        updateEGPPriceSum();
});

    $("#submitRefund").click(function(e){
    e.preventDefault();

    if (!$("#supplierFilter").val()  && !$("#transactionFilter").val()  ) {
        alert("select Supplier and Transaction")
        return false;
    }

    if(!$("#payment_method").val()){
        alert("select Payment")
        return false;
    }
    var visibleRows = table.rows({ search: "applied" }).data();

    if (visibleRows.length === 0) {
        alert("No data available for refund.");
        return false;
    }

    var refundData = [];
    visibleRows.each(function(rowData) {
        refundData.push({
            transactionId: rowData[15], // Transaction ID (Adjust column index accordingly)
            supplier: rowData[14], // Supplier Name
            amount: rowData[7], // Amount
            refundbuy_id: rowData[16] ,
            osid: rowData[17] ,
            part_id: rowData[18] ,
            source_id: rowData[19] ,
            status_id: rowData[20] ,
            quality_id: rowData[21] ,
            type_id: rowData[22] 
        });
    });

    console.log(refundData);
    
    $.ajax({
        url: "/refund/submit",
        type: "POST",
        data: {
            refunds: refundData,
            refund_price : $("#refund_price").val(),
            _token: $('meta[name="csrf-token"]').attr('content') // CSRF Token
        },
        success: function(response) {
            alert("Refund submitted successfully!");
            // location.reload(); // Reload page after success
        },
        error: function(xhr) {
            alert("Error submitting refund: " + xhr.responseText);
        }
    });

})



        
    </script>
@endsection