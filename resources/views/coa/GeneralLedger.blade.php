@extends('layouts.master')
@section('css')
@endsection
@section('title')
    الشجرة المحاسبية
@endsection

@section('content')
    <main role="main" class="main-content ">
        <div class="page-content">
            <div class="mb-3 row">
                <div class="col-lg-3">
                    <label for="journalFilter" class="form-label">From</label>
                    <input type="date" name="" class="form-control" id="startDate">
                </div>
                <div class="col-lg-3">
                    <label for="journalFilter" class="form-label">To</label>
                <input type="date" name="" id="endDate"  class="form-control">
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover bg-white fs-15" id="branchTreeTable">
                <thead>
                    <tr class="h">
                        <th class="text-center"># </th>
                        <th class="text-center">Date </th>
                        <th class="text-center">Account </th>
                        <th class="text-center">Journal </th>
                        <th class="text-center">Label </th>
                        <th class="text-center">Desc </th>
                        <th class="text-center">Partener </th>
                        <th class="text-center">Debit</th>
                        <th class="text-center">Credit</th>
                        
                    </tr>
                    <tr class="filter-row">
                        <th></th>
                        <th></th>
                        <th><select class="column-filter"><option value="">All</option></select></th>
                        <th><select class="column-filter"><option value="">All</option></select></th>
                        <th><select class="column-filter"><option value="">All</option></select></th>
                        <th><select class="column-filter"><option value="">All</option></select></th>
                        <th><select class="column-filter"><option value="">All</option></select></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($allQayds as $index => $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->coa->name_en }}</td>
                            <td>{{ $item->journal->name }}</td>
                            <td>{{ $item->label }}</td>
                            @if ($item->invoice_table=="printInvoice")
                            <td>فاتورة بيع رقم {{ $item->invoice_id }}</td>
                            @elseif ($item->invoice_table=='printBuyInvoice')
                            <td>فاتورة شراء رقم {{ $item->invoice_id }}</td>
                            @endif
                            @if ($item->invoice_table=="printInvoice")
                                <td>{{ $item->client->name }}</td>
                            @elseif ($item->invoice_table=='printBuyInvoice')
                                <td>{{ $item->supplier->name }}</td>
                            @endif
                            <td class="debit">{{ $item->debit }}</td>
                            <td class="credit">{{ $item->credit }}</td>
                           
                        </tr>
                    @endforeach


                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7" class="text-end"><strong>Total</strong></td>
                        <td id="totalDebit" class="text-center">0</td>
                        <td id="totalCredit" class="text-center">0</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </main>
@endsection




@section('js')
    <script>
        var table = $("#branchTreeTable").dataTable({
            order: [[3, 'desc']],
            ordering: true,
            paging: false,
            dom: 'Bfrtip',
            buttons: ['print', 'excel'],
            orderCellsTop: true,
            // paging: false,
            // dom: 'Bfrtip',
            // buttons: [
            //     'print',
            //     'excel'
            // ],
            initComplete: function () {
                var api = this.api();
                
                // Ensure the second row of <thead> is used for filtering
                $('#branchTreeTable thead tr.filter-row th').each(function (index) {
                    var column = api.column(index);
                    var select = $(this).find('select');
                    if (index === 1 || index === 7 || index === 8) {
                        return; 
                    }
                        if (select.length) {
                            var uniqueValues = new Set(); // Store unique values
                            
                            // Extract unique values from the table
                            column.data().each(function (d) {
                                if (d && d.trim() !== "") { // Ensure value is not empty
                                    uniqueValues.add(d.trim());
                                }
                            });

                            // Debug: Log unique values for each column
                            console.log("Column " + index + " values: ", [...uniqueValues]);

                            // Populate dropdown with unique values
                            uniqueValues.forEach(function (value) {
                                select.append('<option value="' + value + '">' + value + '</option>');
                            });

                            // Apply filter on change
                            select.on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                                updateTotalCredit();
                            });
                            select.select2();
                        }
                    
                });

                // Disable sorting when clicking on the filter row
                $('.filter-row th').off('click');
                
            }
            
        })

        updateTotalCredit();
        

        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            var min = $('#startDate').val();
            var max = $('#endDate').val();
            var date = data[1]; // Column index for date

            if ((min === "" && max === "") ||
                (min === "" && date <= max) ||
                (min <= date && max === "") ||
                (min <= date && date <= max)) {
                return true;
            }
            return false;
        });
        $('#startDate, #endDate').on('change', function() {
            table.fnDraw();
            updateTotalCredit()
        });
        function updateTotalCredit() {
            var totalCredit = 0;
            var totalDebit = 0;
            $('#branchTreeTable tbody tr:visible').each(function() {
                var credit = parseFloat($(this).find('td.credit').text()) || 0;
                var debit = parseFloat($(this).find('td.debit').text()) || 0;
                totalCredit += credit;
                totalDebit += debit;
            });
            $('#totalCredit').text(totalCredit.toFixed(2));
            $('#totalDebit').text(totalDebit.toFixed(2));
        }
    </script>
@endsection
