@extends('layouts.master')

@section('css')
    
    <style>
        .dataTables_wrapper .dataTables_filter {
            float: right;
            text-align: right;
        }

        .dataTables_wrapper .dataTables_length {
            float: left;
        }

        .dataTables_wrapper .dataTables_paginate {
            float: right;
            text-align: right;
            padding-top: 0.25em;
        }
    </style>
@endsection

@section('title')
    Inventory Summary
@endsection

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <h3>Inventory Summary</h3>
                <div class="row">
                    <div class="col-lg-12">

                                              
                        <table id="summaryTable" class="display table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    
                                   
                                    <th>Part Name</th>
                                    <th>Source</th>
                                    <th>Status</th>
                                    <th>Quality</th>
                                    
                                    <th>Total Amount</th>
                                    <th>Total Remain Amount</th>
                                    <th>Stores</th>
                                    <th>Sections</th>
                                    <th>Buy Invoice</th>
                                    <th>talef</th>
                                    <th>status</th>
                                    
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
   $(document).ready(function() {
    $('#summaryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('checkamounts') }}",
            type: "GET"
        },
        columns: [
            { data: 'index', name: 'index' },
            { data: 'part_name', name: 'part_name' },
            { data: 'source_name', name: 'source_name' },
            { data: 'status_name', name: 'status_name' },
            { data: 'quality_name', name: 'quality_name' },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'total_remain_amount', name: 'total_remain_amount' },
            {
                data: 'allStores',
                render: allStores => {
                    if (!allStores || allStores.length === 0) return 'N/A';
                    return allStores.map(store => {
                        const storeName = store.store?.name || 'Unknown Store';
                        const data = store.data?.map(d => `
                            <div>
                                <strong>Amount:</strong> ${d.amount} <br>
                                <strong>Order ID:</strong> ${d.supplier_order_id} <br>
                                <strong>Date:</strong> ${d.date?.split('T')[0] || ''}
                            </div>
                        `).join('<hr>') || 'No Data';
                        return `<div><strong>${storeName}</strong><br>${data}</div>`;
                    }).join('<hr style="border-top:1px dashed #ccc">');
                }
            },
            {
                data: 'sections',
                render: sections => {
                    if (!sections || sections.length === 0) return 'N/A';
                    return sections.map(section => {
                        const sectionName = section.store_structure?.name || 'Unknown Section';
                        const sectionAmount = section.amount;
                       
                        return `<div><strong>${sectionName}</strong><br>${sectionAmount}<br>${section.store.name}</div>`;
                    }).join('<hr style="border-top:1px dashed #ccc">');
                }
            },
            {
                 data: 'invoices',
                render: invoices => {
                    if (!invoices || invoices.length === 0) return 'N/A';
                    
                    return invoices.map(inv => {
                        $refundElement = ''
                        if (!inv.refund || inv.refund.length === 0) 
                        {
                            $refundElement = 'No Refund';
                        }else{
                            inv.refund.forEach(re => {
                                $refundElement += 'Refund: <strong>' + re.invoice_id + '</strong><br>' + re.r_amount;
                                
                            });
                        }
                        return `<div>Buy Inv Item <strong>${inv.id}</strong><br>${inv.amount}<br>${inv.invoice.store.name}<br>${$refundElement}</div>`;
                    }).join('<hr style="border-top:1px dashed #ccc">');
                }
            },
            {
                data: 'talef',
                render: talef => {
                    if (!talef || talef.length === 0) return 'N/A';
                    return talef.map(inv => {
                        return `<div>talef : <strong>${inv.id}</strong><br>${inv.amount}</div>`;
                    }).join('<hr style="border-top:1px dashed #ccc">');
                }
            },
            {
                data: null,
                title: 'Check Total',
                render: data => {
                    const {
                        total_amount,
                        total_remain_amount,
                        invoiceTotal,
                        refundTotal,
                        talefTotal,
                        storeTotal,
                        sectionTotal
                    } = data;

                    const expected = total_remain_amount + invoiceTotal - refundTotal - talefTotal;
                    const isValid = total_amount === expected && total_amount === storeTotal;

                    return isValid
                        ? '<span style="color:green;">✅</span>'
                        : `<span style="color:red;">❌ Error<br>
                            Total: ${total_amount}<br>
                            Stores: ${storeTotal}<br>
                            Sections: ${sectionTotal}<br>
                            Invoice: ${invoiceTotal}<br>
                            Refund: ${refundTotal}<br>
                            Talef: ${talefTotal}<br>
                        </span>`;
                }
            }
        ]
    });
});
</script>
    
@endsection
