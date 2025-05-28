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
    Inventory
@stop

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Inventory</h4>
                            </div>
                            <div class="card-body">
                                
                                <input type="hidden" name="section_select" id="section_select" value="">
                                <select name="" class="form-control mb-3" id="storeSelect">
                                    <option value="">Select Store</option>
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" id="get-all-trigger" value="0">
                                <div style="margin-bottom: 10px;" class="text-end ">
                                    <span id="dataModeLabel" class="badge bg-primary">25 Item Per Page</span>
                                </div>
                                <table id="inventory-table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>BarCode</th>
                                            <th>Store Name</th>
                                            <th>Part ID</th>
                                            <th>Part Name</th>
                                            <th>Source</th>
                                            <th>Status</th>
                                            <th>Quality</th>
                                            <th>Type</th>
                                            <th>Sections</th>
                                            <th>Total Amount</th>
                                            <th>Order Supplier</th>
                                            <th>All Store</th>
                                            <th>Action</th>
                                            <th>save</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="addSectionModal" tabindex="-1" aria-labelledby="addSectionModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content" style="max-width: 30%;margin: 0 auto;">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="addSectionModalLabel">Modal Title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <select name="" id="StoreSections"></select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





@endsection

@section('js')

    <script>
        $("#storeSelect").change(function() {
            var storeId = $("#storeSelect").val(); // Get the selected store ID

            if ($.fn.dataTable.isDataTable('#inventory-table')) {
                $('#inventory-table').DataTable().clear().destroy();
            }

            $('#inventory-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/inventoryData/" + storeId,
                    data: function(d) {
                        // Pass the search term from DataTable to the backend
                        d.search_value = d.search.value;
                        if ($('#get-all-trigger').val() === '1') {
                            d.length = -1;
                            d.start = 0;
                        }
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'barcode',
                        name: 'barcode'
                    },
                    {
                        data: 'store_table',
                        name: 'store_name'
                    },
                    {
                        data: 'part_id',
                        name: 'part_id'
                    },
                    {
                        data: 'part_name',
                        name: 'part_name'
                    },
                    {
                        data: 'source_name',
                        name: 'source'
                    },
                    {
                        data: 'status_name',
                        name: 'status'
                    },
                    {
                        data: 'quality_name',
                        name: 'quality'
                    },
                    {
                        data: 'type_id',
                        name: 'type',
                        visible: false
                    },
                    {
                        data: 'sections',
                        name: 'sections'
                    },
                    {
                        data: 'amount',
                        name: 'total_amount'
                    },
                    {
                        data: 'osid',
                        name: 'order_supplier'
                    },
                    {
                        data: 'remain_amount',
                        name: 'all_store'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        visible: true,
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'saveAction',
                        name: 'saveAction',
                        visible: false,
                        orderable: false,
                        searchable: false
                    }
                ],
                dom: 'Bfrtip',
                buttons: ['copy', 'excel', 'print',
                    // {
                    //     extend: 'pdf',
                    //     text: 'Export PDF',
                    //     charset: 'utf-8',
                    //     bom: true, // Add BOM for Excel compatibility
                    //     filename: 'exported_data',
                    //     exportOptions: {
                    //         columns: ':visible' // Or choose specific columns
                    //     }
                    // },
                    {
                        extend: 'csv',
                        text: 'Export CSV',
                        charset: 'utf-8',
                        bom: true, // Add BOM for Excel compatibility
                        filename: 'exported_data' + '_' + new Date().toISOString().slice(0, 10),
                        exportOptions: {
                            columns: ':visible' // Or choose specific columns
                        }
                    },
                    {
                        text: 'Get All',
                        action: function(e, dt, node, config) {
                            $('#get-all-trigger').val('1');
                            dt.ajax.reload(null, false); // false to keep the current paging
                            $('#dataModeLabel')
                                .removeClass('bg-primary')
                                .addClass('bg-success')
                                .text('All Data Mode');

                        }
                    },
                    {
                        text: 'Reset',
                        action: function(e, dt, node, config) {
                            $('#get-all-trigger').val('0');
                            $('#dataModeLabel')
                                .removeClass('bg-primary')
                                .addClass('bg-success')
                                .text(25 + ' Items Per Page');

                            dt.ajax.reload();
                        }
                    }
                ],
                pageLength: 25,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search by name Or number..."
                }
            });
        });

        function saveStoreAmount(store_id, type_id, part_id, source_id, status_id, quality_id, amount, section_id) {
            var new_section_id = $('#section_select').val();
            $.ajax({
                url: '/saveStoreAmount',
                type: 'GET',
                data: {
                    store_id: store_id,
                    type_id: type_id,
                    part_id: part_id,
                    source_id: source_id,
                    status_id: status_id,
                    quality_id: quality_id,
                    amount: amount,
                    section_id: section_id,
                    new_section_id: new_section_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Handle the response from the server
                    console.log(response);
                     alert(response.message);
                    $('#section_select').val('')
                },
                error: function(xhr, status, error) {
                    // Handle any errors that occurred during the request
                    console.error(error);
                }
            });
            $('#section_select').val('')
        }

        var currentRow = null; // Variable to store the current row
        function addSection(store_id,el) {
            $('#addSectionModal').modal('show');
            currentRow = $(el).closest('tr'); // Store the current row
            $('#addSectionModalLabel').text('Select Section for Store');
            $('#section_select').val(''); // Reset the section select input

            // You can also load the sections dynamically if needed
            $.ajax({
                url: '/GetSections/' + store_id,
                type: 'GET',
                success: function(response) {
                    
                    $('#StoreSections').empty(); // Clear previous options
                    $('#StoreSections').append('<option value="">Select Section</option>');
                    $('#StoreSections').attr('data-store-id', store_id); 
                    
                    response.forEach(element => {

                        $('#StoreSections').append('<option value="' + element.id + '">' + element
                            .name + '</option>');
                    });
                    $('#StoreSections').select2({
                        dropdownParent: $("#addSectionModal"),
                    });


                }
            });
        }


        $(document).on('change', '#StoreSections', function() {
            var newsection_id = $(this).val();
            var section_name = $(this).find('option:selected').text();
            var el = $(currentRow)
            $(el).find('.sectionName').text(section_name);
            $('.sectionName').removeClass('text-bg-danger');
            $(el).find('.sectionName').addClass('text-bg-danger');
            $('#section_select').val(newsection_id);
            $('#addSectionModal').modal('hide'); // Hide the modal after selection
        });
    </script>
@endsection
