@extends('layouts.master')
@section('css')
<style>
    /*body {*/
    /*    margin: 2em;*/
    /*    font-size: 20px;*/
    /*}*/
    /*.homeDiv{*/
    /*    position: absolute;*/
    /*    top: 50px;*/
    /*    left: 50px;*/
    /*    z-index: 88888;*/
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
Suppliers
@stop


@section('content')
<div class="main-content">
    <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Supplier Invoice</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">ٍSupplier</li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="col-12 table-responsive" style="overflow-x:auto;">
                    <table class="table" id="supplierTbl" style="font-size: small">
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
                                <td class="text-center" title="اجمالي المبيعات - سداد البيع - الخصم +مدفوعات الصيانة - رصيد المورد - رصيد العميل">الاجمالي</td>
                                <td class="text-center">عرض</td>
                                {{-- <td class="text-center">سداد</td> --}}
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($supplier as $sup )
                                <tr>
                                    <td>{{ $sup->name }}</td>
                                    <td>{{ $sup->tel01 }}</td>
                                    <td>{{ count($sup->order_suppliers) }}</td>

                                    <td>{{ $sup->Suuplier_actual_price }}</td>
                                    <td>{{ $sup->Total_paied + $sup->Suuplier_Total_paied }}</td>
                                    <td>{{ ($sup->client) > 0 ? 'Client' : '-' }}</td>
                                    <td>{{ $sup->clientRaseed }}</td>
                                    <td>{{ $sup->sellInvoiceCount }}</td>
                                    <td>{{ $sup->servicesMad }}</td>
                                    <td>{{ isset($sup->InvoiceClient) ? $sup->InvoiceClient->sum('actual_price') - $sup->InvoiceClient->sum('paied') - $sup->InvoiceClient->sum('discount') : 0  }}</td>
                                    <td>{{ $sup->refundInvTotal }}</td>
                                    <td>{{ $sup->clientinvoiceMad }}</td>
                                    <td class="text-bg-danger">{{ $sup->Suuplier_actual_price - $sup->Total_paied - $sup->Suuplier_Total_paied + $sup->clientRaseed -$sup->servicesMad - (isset($sup->InvoiceClient) ? $sup->InvoiceClient->sum('actual_price') - $sup->InvoiceClient->sum('paied') - $sup->InvoiceClient->sum('discount') : 0) +$sup->refundInvTotal + $sup->clientinvoiceMad }}</td>
                                    @if ($sup->client > 0)
                                        <td>
                                            <a class="btn btn-success" href="Clientinvoice/{{ $sup->clientid}}/8">الكل</a>
                                            <a class="btn btn-success" href="Supplierinvoice/{{ $sup->id}}">مورد</a>
                                        </td>
                                    @else
                                        <td>
                                            <a class="btn btn-soft-success" href="Supplierinvoice/{{ $sup->id}}">عرض</a>
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
    @endsection

    @section('js')

    <script>
        $("#supplierTbl").dataTable();
    </script>

    @endsection
