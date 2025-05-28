@extends('layouts.master')
@section('css')
@endsection
@section('title')
    الشجرة المحاسبية
@endsection

@section('content')
    <main role="main" class="main-content ">
        <div class="page-content">
            
            <table class="table table-striped table-bordered table-hover bg-white fs-15" id="branchTreeTable">
                <thead>
                    <tr class="h">
                        <th class="text-center"># </th>
                        <th class="text-center">Name </th>
                        <th class="text-center">Debit </th>
                        <th class="text-center">Credit </th>
                        <th class="text-center">Balance </th>
                        
                        
                    </tr>
                   
                </thead>

                <tbody>
                    @foreach ($allCoaType as $index => $item)
                        <tr class="parent-row text-bg-light" data-id="{{ $index }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary float-start toggle-row" data-id="{{ $index }}">+</button>
                                {{ $item->name }}
                            </td>
                            <td class="text-end">{{ number_format($item->total_debit, 2) }}</td>
                            <td class="text-end">{{ number_format($item->total_credit, 2) }}</td>
                            <td class="text-end">{{ number_format($item->total_debit - $item->total_credit, 2) }}</td>
                        </tr>
        
                        @foreach ($item->coa as $coaIndex=>$coa)
                            <tr class="sub-row text-bg-info d-none" data-parent="{{ $index }}">
                                <td></td>
                                <td class="text-start ps-4 fw-bold">
                                    <button class="btn btn-sm btn-secondary toggle-subrow" data-id="coa-{{ $index }}-{{ $coaIndex }}">+</button>
                                    {{ $coa->name_ar }}
                                </td>
                                <td class="text-end">{{ number_format($coa->total_debit, 2) }}</td>
                                <td class="text-end">{{ number_format($coa->total_credit, 2) }}</td>
                                <td class="text-end">{{ number_format($coa->total_debit - $coa->total_credit, 2) }}</td>
                            </tr>
        
                            @foreach ($coa->qayds as $qayd)
                                <!-- Sub-sub-row (Qayds) -->
                                <tr class="sub-sub-row text-bg-dark d-none" data-parent="coa-{{ $index }}-{{ $coaIndex }}">
                                    <td class="text-start ps-5">{{ $qayd->qayd->desc }}</td>
                                    <td class="text-start ps-5">{{ $qayd->label }}</td>
                                    
                                    <td class="text-end">{{ number_format($qayd->debit, 2) }}</td>
                                    <td class="text-end">{{ number_format($qayd->credit, 2) }}</td>
                                    <td class="text-end">{{ number_format($qayd->debit - $qayd->credit, 2) }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-bold bg-light">
                        <td colspan="2" class="text-center">Total</td>
                        <td class="text-end"></td> <!-- Debit Total -->
                        <td class="text-end"></td> <!-- Credit Total -->
                        <td class="text-end"></td> <!-- Balance Total -->
                    </tr>
                </tfoot>
            </table>
        </div>
    </main>
@endsection




@section('js')
    <script>
        var table = $("#branchTreeTable").DataTable({
            order: [[0, 'asc']],
            ordering: true,
            paging: false,
            dom: 'Bfrtip',
            buttons: ['print', 'excel'],
            orderCellsTop: true,
            footerCallback: function (row, data, start, end, display) {
                var api = this.api();

                var intVal = function (i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ? i : 0;
                };

                var totalDebit = api.column(2, { page: 'all' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                var totalCredit = api.column(3, { page: 'all' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                var totalBalance = totalDebit - totalCredit;

                $(api.column(2).footer()).html(totalDebit.toFixed(2));
                $(api.column(3).footer()).html(totalCredit.toFixed(2));
                $(api.column(4).footer()).html(totalBalance.toFixed(2));
            }
        });

  // Toggle sub-rows (Coa) below the clicked parent row
$(document).on('click', '.toggle-row', function () {
    var id = $(this).data('id');
    var parentRow = $(this).closest('tr');
    var subRows = $('tr[data-parent="' + id + '"]');

    if ($(this).text() === '+') {
        $(this).text('-');
        subRows.removeClass('d-none'); // Show sub-rows

        // Insert directly below clicked row
        subRows.each(function () {
            $(this).insertAfter(parentRow);
            parentRow = $(this); // Update reference for next sub-row
        });
    } else {
        $(this).text('+');
        subRows.addClass('d-none'); // Hide sub-rows & their sub-sub-rows

        // Hide all sub-sub-rows when collapsing the parent
        subRows.each(function () {
            var subRowId = $(this).data('id');
            $('tr[data-parent="' + subRowId + '"]').addClass('d-none');
            $('.toggle-subrow[data-id="' + subRowId + '"]').text('+'); // Reset sub-subrow button
        });
    }
});

// Toggle sub-sub-rows (Qayds) below the clicked sub-row
$(document).on('click', '.toggle-subrow', function () {
    var id = $(this).data('id');
    var subRow = $(this).closest('tr');
    var subSubRows = $('tr[data-parent="' + id + '"]');

    if ($(this).text() === '+') {
        $(this).text('-');
        subSubRows.removeClass('d-none'); // Show sub-sub-rows

        // Insert directly below clicked sub-row
        subSubRows.each(function () {
            $(this).insertAfter(subRow);
            subRow = $(this); // Update reference for next sub-sub-row
        });
    } else {
        $(this).text('+');
        subSubRows.addClass('d-none'); // Hide sub-sub-rows
    }
});
    </script>
@endsection
