@extends('layouts.master')
@section('css')
@endsection
@section('title')
    الشجرة المحاسبية
@endsection

@section('content')
    <main role="main" class="main-content ">
        <div class="page-content">
            <form action="{{ route('journal.save') }}" method="POST">
                @csrf
                <label for="name">Journal Name:</label>
                <input type="text" class="form-control" name="name" id="name" required>

                <label for="year">Year:</label>
                <input type="text" class="form-control" name="year" id="year" value="2024" readonly>

                <label for="journal_type">Journal Type:</label>
                <select class="form-control" name="journal_type" id="journal_type" required>
                    <option selected disabled value="">Select</option>
                    @foreach ($journalType as $type )
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>

                <label for="notes">Notes:</label>
                <textarea name="notes" class="form-control" id="notes"></textarea>

                {{-- <h3>Journal Details</h3> --}}
                <div id="details">
                    <div class="row mt-2">
                        <div class="col-lg">
                            <div class="card">
                                <div class="card-header">
                                    <h2 class="float-start">Accounts</h2>
                                    <button onclick="addAccountRow()" type="button" class="btn btn-icon btn-topbar float-end btn-ghost-secondary rounded-circle">
                                         <i class="ri ri-add-box-line fs-22"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <table id="accountTable">
                                        <thead>
                                            <tr class="h">
                                                <th class="text-center">Name</th>
                                                <th class="text-center">COA</th>
                                                <th class="text-center">Note</th>
                                                <th class="text-center d-none">Default</th>
                                                <th class="text-center"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                           
                                        </tbody>
                                    </table>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-lg">
                            <div class="card">
                                <div class="card-header">
                                    <h2 class="float-start">Incoming Payment</h2>
                                    <button onclick="addIncomeRow()" type="button" class="btn btn-icon btn-topbar float-end btn-ghost-secondary rounded-circle">
                                         <i class="ri ri-add-box-line fs-22"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <table id="incomeTable">
                                        <thead>
                                            <tr class="h">
                                                <th class="text-center">Name</th>
                                                <th class="text-center">COA</th>
                                                <th class="text-center">Note</th>
                                                <th class="text-center">Payment method</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          
                                        </tbody>
                                    </table>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2 class="float-start">Outgoing Payment</h2>
                                    <button onclick="addoutgoingRow()" type="button" class="btn btn-icon btn-topbar float-end btn-ghost-secondary rounded-circle">
                                         <i class="ri ri-add-box-line fs-22"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <table id="outgonigTable">
                                        <thead>
                                            <tr class="h">
                                                <th class="text-center">Name</th>
                                                <th class="text-center">COA</th>
                                                <th class="text-center">Note</th>
                                                <th class="text-center">Payment Methods</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
                                        </tbody>
                                    </table>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <button class="btn btn-danger w-100" type="submit">Save Journal</button>
            </form>
        </div>
    </main>
@endsection




@section('js')
    <script>
        var coa = {!! $coa !!};
        var coaOptions='';
        if(coa.length>0){
            coa.forEach(element => {
                coaOptions+=`<option value='${element.id}'>${element.name_ar} / ${element.ac_number}</option>`;
            });
        }
        function addAccountRow(){
            var index = $("#accountTable tbody tr").length+1;
            $("#accountTable tbody").append(`
                 <tr>
                    <td>
                        <input class="form-control" type="text" name="account_name[]" >
                    </td>
                    <td>
                        <select class="form-control" id="aslct${index}" name="account_value[]" required>
                            <option selected disabled value="">Select</option>
                            ${coaOptions}
                        </select>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="account_notes[]" >
                    </td>
                    <td class="d-none">
                        
                        <select class="form-control" name="account_default[]" required>
                            <option selected  value="0">False</option>
                            <option value="1">True</option>
                        </select>
                    </td>
                    <td>
                        <button onclick="this.closest('tr').remove()" type="button" class="btn btn-icon btn-topbar float-end btn-ghost-secondary rounded-circle">
                                <i class="ri ri-delete-bin-2-line fs-22"></i>
                        </button>
                    </td>
                </tr>
            `);
            $("#aslct"+index).select2()
        }
        function addIncomeRow(){
            var index = $("#incomeTable tbody tr").length+1
            $("#incomeTable tbody").append(`
                 <tr>
                    <td>
                        <input class="form-control" type="text" name="income_name[]" >
                    </td>
                    <td>
                        <select class="form-control" id="bslct${index}" name="income_value[]" required>
                            <option selected disabled value="">Select</option>
                            ${coaOptions}
                        </select>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="income_notes[]" >
                    </td>
                    <td>
                        <select class="form-control" name="income_default[]" required>
                            <option selected  value="0">بدون</option>
                            <option value="1">كاش</option>
                            <option value="2">شيك</option>
                        </select>
                        
                    </td>
                    <td>
                        <button onclick="this.closest('tr').remove()" type="button" class="btn btn-icon btn-topbar float-end btn-ghost-secondary rounded-circle">
                                <i class="ri ri-delete-bin-2-line fs-22"></i>
                        </button>
                    </td>
                </tr>
            `);
            $("#bslct"+index).select2()
        }
        function addoutgoingRow(){
            var index = $("#outgonigTable tbody tr").length+1
            $("#outgonigTable tbody").append(`
                 <tr>
                    <td>
                        <input class="form-control" type="text" name="outgo_name[]" >
                    </td>
                    <td>
                        <select id="cslct${index}" class="form-control" name="outgo_value[]" required>
                            <option selected disabled value="">Select</option>
                            ${coaOptions}
                        </select>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="outgo_notes[]" >
                    </td>
                    <td>
                        
                         <select class="form-control" name="outgo_default[]" required>
                            <option selected  value="0">بدون</option>
                            <option value="1">كاش</option>
                            <option value="2">شيك</option>
                        </select>
                    </td>
                    <td>
                        <button onclick="this.closest('tr').remove()" type="button" class="btn btn-icon btn-topbar float-end btn-ghost-secondary rounded-circle">
                                <i class="ri ri-delete-bin-2-line fs-22"></i>
                        </button>
                    </td>
                </tr>
            `);
            $("#cslct"+index).select2()
        }
        
    </script>
@endsection
