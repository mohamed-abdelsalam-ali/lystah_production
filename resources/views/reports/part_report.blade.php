@extends('layouts.master')
@section('css')



    <style>
        .ag-layout-normal{
            /* direction: rtl; */
        }
    </style>
@endsection
@section('title')
    PARTS
@stop


@section('content')



    <div class="main-content">
        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Parts</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Reports</li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">

                    <div id="myGrid" class="ag-theme-quartz" style="height: 500px"></div>
                </div>
            </div>


        </div>
    </div>


@endsection

@section('js')


    <script>
        var allparts = {!! $parts !!};
        var gridOptions = {};

         gridOptions = {

        rowData: allparts,
        pagination: true,
        sideBar : true,
        statusBar : true,
        enableCharts : true,
        enableRangeSelection: true,
        groupLockGroupColumns: 1,
        rowGroupPanelShow: 'always',
        groupDefaultExpanded: -1,
        columnDefs: [
            {
                headerName : 'Part Number',
                field: "part_numbers.number",
                cellDataType : 'text',
                valueGetter: (params) => {
                    if(params.data.part_numbers.length > 0){
                        return (params.data.part_numbers[0].number).toLocaleString();
                    }
                    else{
                        return 0;
                    }

                }
            },
            { field: "name" , rowGroup: true, },
            { field: "insertion_date"  },
            { field: "name_eng" },
            {
                headerName : 'Part Group',

                // headerName: 'Athlete Details',
            children: [
                { field: 'sub_group.name' ,headerName : 'Sub Group', rowGroup: true,  },
                { field: 'sub_group.group.name' , headerName : ' Group' , columnGroupShow: 'open'  },

            ] ,

            },
        ] ,

        defaultColDef: {
    flex: 1,
    minWidth: 150,
    filter: 'agTextColumnFilter',
    menuTabs: ['filterMenuTab'],
  },

        };

        // gridOptions.datasource = allparts;

        const myGridElement = document.querySelector('#myGrid');
        agGrid.createGrid(myGridElement, gridOptions);

    </script>

    {{-- <script>
        $('.nav-link').removeClass('active');
        $('#partli a').addClass('active');
        $('.nav-item').removeClass('active');
        $('.partli').addClass('active');
    </script> --}}

@endsection
