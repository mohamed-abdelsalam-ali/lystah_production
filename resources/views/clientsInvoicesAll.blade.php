@extends('layouts.master')
@section('css')



<style>
    /*body {*/
    /*    margin: 2em;*/
    /*    font-size: 20px;*/
    /*}*/
  
    /*#madMdl .modal-content{*/
    /*    width: 60vw !important;*/
    /*}*/

    /*#madMdl .modal-dialog{*/
    /*    max-width: 60vw !important;*/
    /*}*/
    /*table {*/
    /*    border: 1px solid #ccc;*/
    /*    border-collapse: collapse;*/
    /*    margin: 0;*/
    /*    padding: 0;*/
    /*    width: 100%;*/
    /*    table-layout: fixed;*/
    /*}*/
</style>
@endsection
@section('title')
Clients
@stop


@section('content')
<div class="main-content">
    <div class="page-content">
            <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                            <h4 class="mb-sm-0">Client Invoice</h4>
        
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item active">Clients</li>
                                    <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                                </ol>
                            </div>
        
                        </div>
                    </div>
                </div>   
            <div class="card  ">
                <div class="card-body fs-18 fw-bold" >
                    <div class="col-12 table-responsive" style="overflow-x:auto;">
                        <table class="table" id="clientTbl" style="font-size: small">
                            <thead class="" style="transform: scaleY(0.8);">
                                <tr>
                                    <td class="text-center">الإسم</td>
                                    <td class="text-center">التليفون</td>
                                    <td class="text-center">عدد فواتير الشراء</td>
                                    <td class="text-center">  الإجمالي/ فواتير الشراء</td>
                                    <td class="text-center">المدفوعات</td>
                                    <td class="text-center">عمبل</td>
                                    <td class="text-center">رصيد العميل</td>
                                    <td class="text-center">فواتير البيع</td>
                                    <td class="text-center">مديونية الخدمات</td>
                                    <td class="text-center">مديونية البيع</td>
                                    <td class="text-center">مردودات البيع</td>
                                    <td class="text-center">سداد البيع</td>
                                    <td class="text-center">ملاحظات </td>
                                    <td class="text-center" title="اجمالي المبيعات - سداد البيع - الخصم +مدفوعات الصيانة - رصيد المورد - رصيد العميل">الاجمالي</td>
                                    <td class="text-center">عرض</td>
                                    {{-- <td class="text-center">سداد</td> --}}
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($clients as $client )
                                    <tr>
                                        <td>{{ $client->name }}</td>
                                        <td>{{ $client->tel01 }}</td>
                                        <td>{{ count($client->order_suppliers) }}</td>
    
                                        <td>{{ $client->Suuplier_actual_price }}</td>
                                        <td>{{ $client->Total_paied + $client->Suuplier_Total_paied }}</td>
                                        <td>{{ ($client->client) > 0 ? 'Client' : '-' }}</td>
                                        <td>{{ $client->clientRaseed }}</td>
                                        <td>{{ $client->sellInvoiceCount }}</td>
                                        <td>{{ $client->servicesMad }}</td>
                                        <td>{{ isset($client->InvoiceClient) ? $client->InvoiceClient->sum('actual_price') - $client->InvoiceClient->sum('paied') - $client->InvoiceClient->sum('discount') : 0  }}</td>
                                        <td>{{ $client->refundInvTotal }}</td>
                                        <td>{{ $client->clientinvoiceMad }}</td>
                                        @if( $client->message== 'No Data Founded')
                                            <td>-</td>
                                        @else
                                            <td>{{ $client->message }}</td>
                                        @endif
                                       
                                        <td class="text-bg-danger fs-19" title="{{ $client->message }}">{{  $client->egmal }}</td>
                                        @if ($client->sup_id > 0)
                                              <td>
                                                <a class="btn btn-success" href="Supplierinvoice/{{ $client->sup_id}}">مورد</a>
                                                <a class="btn btn-soft-success" href="getclientinvoice/{{ $client->id}}">الكل</a>
                                            </td>
                                        @else
                                            <td>
                                                
                                                <a class="btn btn-soft-success" href="getclientinvoice/{{ $client->id}}">عرض</a>
                                                {{-- <button class="btn btn-success" >عرض</button> --}}
                                            </td>
                                        @endif
    
                                        {{-- <td><button class="btn btn-primary">سداد</button></td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                   </div>
                </div>
            </div>
        </div>
</div>

    @endsection

    @section('js')
     
    <script>
         $(document).ready(function () {
            var table = $("#clientTbl").DataTable(
                {
                    dom: "Bfrltip",
                    // paging: true,
                    // searching: true,
                    // ordering: true,
                    // info: true,
                    // "columnDefs": [
                    //     {
                    //         "targets": [ 0 ,1,2,3,4,5,6,7,8,9,10,11,12,13,14],
                    //         "orderable": true,
                    //     },
                    // ],
                    buttons: [
                        {
                            extend: 'csvHtml5',
                            text: 'Export CSV',
                            exportOptions: {
                                columns: [0, 1, 13]
                            }
                        },
                        {
                            extend: 'print',
                            text: 'Print',
                            exportOptions: {
                                columns: [0,1,13] // Include the same columns for printing
                            },
                            customize: function (win) {
                                // Customize the print view
                                
                                $(win.document.body)
                                    .css('font-size', '12px')
                                     .prepend('<h3 style="direction:rtl">'+$("#totsapn").text()+'</h3>')
                                     .prepend('<h3 style="direction:rtl">'+$('option:selected', "#Filter1").attr('data-rep')+'</h3>');

                            $(win.document).prop('title', 'Client Table - Print Preview');

                                $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', '16px')
                                    .css('direction', 'rtl')
                                    .css('font-family', 'cairo');
                                    
                                    
                            }
                        }
                    ],
                    initComplete: function () {
                        const searchBox = $('#clientTbl_filter');
                        const cityFilterHtml = `
                            <select id="Filter1" class="form-control-lg">
                            <option data-rep="كل العملاء" value="">Show All</option>
                            <option data-rep="العملاء المدينيين" value="مديونية">مدينيين</option>
                            <option data-rep="العملاء الدائنون" value="رصيد">دائنون</option>

                        </select>
                                <sapn id="totsapn"></span>
                        `;
                        searchBox.append(cityFilterHtml);
                    }
                }
            );
            $('#Filter1').on('change', function () {
                var filterValue = $(this).val();
                if (filterValue === "") {
                    table.columns(12).search('').draw();
                    $("#totsapn").text("");
                } else {
                    // table.columns(14).search(filterValue, false, false).draw(); // true, false for partial match
                    table.columns(12).search(filterValue).draw(); // true, false for partial match
                    const sum = table
                    .column(13, { search: 'applied' }) // Ensure only filtered rows are considered
                    .data() // Get the data for the column
                    .reduce(function (total, value) {
                        // Parse the value as a number and add to the total
                        const numericValue = parseFloat(value) || 0; // Handle non-numeric or empty values
                        return total + numericValue;
                    }, 0);
                    $("#totsapn").text(sum);
                }
            });
        });
    </script>

    @endsection
