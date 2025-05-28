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
    New Products
@stop

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">New Products</h4>
                            </div>
                            <div class="card-body">


                                <table id="partTables" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>part_id</th>
                                            <th>Name</th>
                                            <th>Stores</th>
                                            <th>Source</th>
                                            <th>Status</th>
                                            <th>Quality</th>
                                            <th>Sum of Stores</th>
                                            <th>Count</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                </table>
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
        let partTable = $("#partTables").DataTable({
            // dom: "Bfrltip",
            processing: true,
            serverSide: true,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            pageLength: 10,
            deferRender: true,
            responsive: true,
            destroy: true,
            ajax: {
                url: "newproductsData",
                type: "GET",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                // { data: "part_id", name: "part_id" },
                {
                    data: "part_id",
                    name: "part_id",
                    render: function(data, type, row, meta) {
                        return '<a target="blank" href=/partDetails/1/' + data + '>' + data + '</a>';
                    }
                },
                {
                    data: "part.name",
                    name: "part.name"
                },
                {
                    data: "stores",
                    name: "stores",
                    render: function(data, type, row, meta) {
                        var el = '';
                        data.forEach(store => {
                            el += '<li>' + store.name + '-' + store.storepartCount + '</li>';
                        });
                        return el;
                    }
                },
                {
                    data: "source.name_arabic",
                    name: "source.name_arabic"
                },
                {
                    data: "status.name",
                    name: "status.name"
                },
                {
                    data: "quality.name",
                    name: "quality.name"
                },
                {
                    data: "sumremainamount",
                    name: "sumremainamount"
                },
                {
                    data: "counts",
                    name: "counts"
                },
                {
                    data: "insertion_date",
                    name: "insertion_date"
                }
            ],
            select: true
        });
    </script>
@endsection
