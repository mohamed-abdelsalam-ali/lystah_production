@extends('layouts.master')
{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

@section('css')
<style>
    .tfoot{
        font-size: 18px !important;
    }
</style>
@endsection
@section('title')
    Taxes Details
@stop


@section('content')



    <div class="main-content">
        <div class="page-content">
           
            <div class="row">
                <div class="col-lg-12">
                    <h2>الضرائب</h2>
                    <div class="card">
                        
                        <div class="card-body">
                           <div class="row">
                            <div class="col-lg-2">
                                <label for="from_date">من</label>
                                <input type="date" class="form-control" id="from_date" name="from_date" value="">
                            </div>
                            <div class="col-lg-2">
                                <label for="to_date">إلي</label>
                                <input type="date" class="form-control" id="to_date" name="to_date" value="">
                            </div>
                            <div class="col-lg-3">
                                <label for="tax_type">نوع الضريبة</label>
                                <select class="form-control" id="tax_type" name="tax_type">
                                    <option selected  value="0">الكل</option>
                                    @foreach ($taxes as $tax)
                                        <option value="{{ $tax->id }}">{{ $tax->name }}</option>
                                        
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="search">شراء - بيع</label>
                                <select class="form-control" id="inv_type" name="inv_type">
                                    <option value="0">الكل</option>
                                    <option value="1">فواتير الشراء</option>
                                    <option value="2">فواتير البيع</option>
                                </select>
                            </div>
                            <div class="col-lg-2 text-end">
                                <button type="button" id="searchtaxesBtn" class="btn btn-dark mt-4">Search</button>
                            </div>
                            
                           </div>
                        </div>
                </div>
            </div>

            <div class="row card">
                <div class="col-lg-12">
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="taxes_table">
                            <thead>
                                <tr>
                                    <th> الفاتورة</th>
                                    <th>رقم الفاتورة</th>
                                    <th>تاريخ الفاتورة</th>
                                    <th>نوع الضريبة</th>
                                    <th> الضريبة</th>
                                    <th>قيمة الضريبة</th>
                                    <th>اجمالي الفاتورة</th>
                                    <th>العميل - المورد</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" style="text-align:right">المجموع:</th>
                                    <th></th>
                                    <th colspan="3"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>



@endsection

@section('js')
    <script>
        $("#searchtaxesBtn").click(function() {
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var tax_type = $("#tax_type").val();
            var type = $("select[name='inv_type']").val();

            $('#taxes_table').DataTable().destroy();
            $('#taxes_table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,   
                paging: false,  
                ajax: {
                    url: "{{ route('GettaxesReport') }}",
                    data: {
                        from_date: from_date,
                        to_date: to_date,
                        tax_type: tax_type,
                        inv_type: type
                    }
                },
                columns: [
                    {data: 'type_name', name: 'type_name'},
                    {data: 'invoice_number', name: 'invoice_number'},
                    {data: 'invoice_date', name: 'invoice_date'},
                    {data: 'tax_name', name: 'tax_name'},
                    {data: 'tax_value', name: 'tax_value'},
                    {data: 'tax_amount', name: 'tax_amount'},
                    {data: 'total_invoice', name: 'total_invoice'},
                    
                    
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'action', name: 'action'}
                ],
                dom: 'Bfrtip',  // مهم عشان تظهر الأزرار
                buttons: [
                    {
                        extend: 'print',
                        text: 'طباعة',
                        className: 'btn btn-dark',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7] // <<< هنا نحدد الأعمدة اللي تطبع
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'تصدير CSV',
                        charset: 'utf-8',
                        bom: true,
                        filename: 'taxes_report '+Date(),
                        title: null,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7] // <<< هنا نحدد الأعمدة اللي تطبع
                        }
                    }
                ],
                 footerCallback: function (row, data, start, end, display) {
                    var api = this.api();

                    // دالة تساعد لتحويل النص إلى رقم صحيح
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            parseFloat(i.replace(/[\$,]/g, '')) :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    // حساب مجموع العمود tax_amount
                    var total = api
                        .column(5, { page: 'current' }) // العمود السادس (تبدأ من 0)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // تحديث التوتال في الفوتر (تحت الجدول)
                    $(api.column(5).footer()).html(`<span class="fw-bold fs-18 text-danger">${total.toFixed(2)}</span>`);
                }
            });
        });
    </script>
@endsection
