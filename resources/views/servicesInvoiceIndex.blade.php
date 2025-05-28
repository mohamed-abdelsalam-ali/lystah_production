@extends('layouts.master')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<!--<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.2.0/css/searchPanes.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ URL::asset('assets/css/dataTablesButtons.css') }}">



<style>
    /*.modal-content{*/
    /*    width: 50vw !important;*/
    /*}*/
    table{
        font-family: 'Droid Arabic Naskh', serif;
    }
    .row{
        align-items: self-end
    }
    
</style>
@endsection
@section('title')
 Service Invoices
@stop


@section('content')



    <div class="main-content">
        <div class="page-content">
            <h1 class="text-center text-info">Service Invoices</h1>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-head">
                            <div class="row">
                                <div class="col-8"></div>
                                    <div class="px-4 mt-3 col-4 text-end">
                                        {{-- <button class="btn addButton btn-info"  href={{ route("serviceInvoice.create")}} >Add New <i class="ri-add-fill"> </i></button> --}}
                                        {{-- <a type="button" href="{{route("serviceInvoice.create")}}" class= "addButton">Add New<i class="ri-add-fill"> </i></a> --}}
                                        <a type="button" href="/services/8" class= "addButton">Add New<i class="ri-add-fill"> </i></a>
                                    </div>
                            </div>
                        </div>
                        <div class="card-head">
                            <div class="row">
                                <div class="col-lg-6 ">
                                </div>
                                <div class="col-lg-6 ">
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="servicesDT" class="table text-center display table-bordered dt-responsive dataTable dtr-inline" style="width:100%; font-size:16px;">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="text-center sorting sorting_asc">Store Name</th>
                                        <th class="text-center sorting sorting_asc">Client Name</th>
                                        <th class="text-center">Invoice Total</th>
                                        <th class="text-center">Total Paid</th>
                                        <th class="text-center">Remain</th>
                                        <th class="text-center">Service Type</th>
                                        <th class="text-center">Service Option</th>
                                        <th class="text-center sorting sorting_asc">Date</th>
                                        <th class="text-center">Print</th>
                                        <th class="text-center">Edit</th>
                                        <th class="text-center">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $serviceInvoice as $serviceInvoices)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ $serviceInvoices->store->name }}</td>
                                        <td>{{ isset($serviceInvoices->client->name) ? $serviceInvoices->client->name : "--" }}</td>
                                        <td>{{ $serviceInvoices->total}}</td>
                                        <td>{{ $serviceInvoices->totalpaid }}</td>
                                        <td>{{ $serviceInvoices->remain }}</td>
                                        <td>{{ $serviceInvoices->servicetype->type }}</td>
                                        <td>{{ $serviceInvoices->serviceoption->option }}</td>
                                        <td>{{ isset($serviceInvoices->date) ? $serviceInvoices->date: "--" }}</td>
                                        <td><a type="button" href="{{route("service.print",$serviceInvoices->id)}}" class= "printButton">Print</a></td>

                                       <td><a type="button" serviceInvoiceID = "{{ $serviceInvoices->id }}" href="{{route("serviceInvoice.edit",$serviceInvoices->id)}}" id="editButton" class= "editButton">Edit</a></td>

                                        <td><form action="{{route("serviceInvoice.destroy" , $serviceInvoices->id)}}" method="POST">
                                                @csrf
                                                @method("delete")
                                        
                                                <input type="submit" value="Delete" class= "deleteServiceButton">
                                            </form>
                                        </td>
                                        
                                    </tr>
                                        
                                    @endforeach


                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th class="text-center">Store Name</th>
                                        <th class="text-center sorting sorting_asc">Client Name</th>
                                        <th class="text-center sorting sorting_asc">Invoice Total</th>
                                        <th class="text-center sorting sorting_asc">Total Paid</th>
                                        <th class="text-center">Remain</th>
                                        <th class="text-center">Service Type</th>
                                        <th class="text-center">Service Option</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Print</th>
                                        <th class="text-center">Edit</th>
                                        <th class="text-center">Delete</th>
                                    </tr>
                                </tfoot>
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
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="{{ URL::asset('js/servicesEdit.js')}}"></script>

<script>$("#servicesDT").dataTable()</script>
@endsection
