@extends('layouts.master')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.css">
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::asset('assets/dataTables/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/dataTables/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="/assets/libs/sweetalert2/sweetalert2.min.css">
@endsection
@section('title')
    الشجرة المحاسبية
@endsection

@section('content')
<main role="main" class="main-content ">
    <div class="page-content">
        <button class="btn btn-soft-secondary float-end mt-3" onclick="location.href='branch_table'"> <i class="fs-1 ri-toggle-line"></i> </button>
    <table class="table table-striped table-bordered table-hover bg-white fs-15" id="branchTreeTable">
        <thead>
          <tr class="h">
            <th class="text-center"># </th>
            <th class="text-center">اسم الحساب</th>
            <th class="text-center">english</th>
            <th class="text-center">كود الحساب</th>
    
          </tr>
        </thead>
    
        <tbody>
          @php
            $i = 0;
          @endphp
          @foreach($branch as $x)
          @php
            $i++;
          @endphp
            <tr class="text-center">
                <td>{{$i}}</td>
    
    
                <td>{{ $x->name }}</td>
                <td>{{ $x->en_name }}</td>
                 <td>{{ $x->accountant_number }}</td>
            </tr>
    
        @endforeach
        </tbody>
    </table>
    </div></main>

@endsection




@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('assets/dataTables/dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.js"></script>
    <script src="{{ URL::asset('assets/dataTables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.print.min.js') }}"></script>
    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <script>
        $("#branchTreeTable").dataTable({
            // info: false,
            order: [[3, 'desc']],
            ordering: true,
            paging: false ,
            dom: 'Bfrtip',
            buttons: [
                'print',
                 'excel'
            ]
        })
    </script>
@endsection
