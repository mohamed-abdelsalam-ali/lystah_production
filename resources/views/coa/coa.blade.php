@extends('layouts.master')
@section('css')
@endsection
@section('title')
    الشجرة المحاسبية
@endsection

@section('content')
    <main role="main" class="main-content ">
        <div class="page-content">
            <button type="button" class="btn btn-icon btn-topbar  float-end btn-info px-5" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop">
                New <i class="ri ri-add-box-line fs-22"></i>
            </button>
            <table class="table table-striped table-bordered table-hover bg-white fs-15" id="branchTreeTable">
                <thead>
                    <tr class="h">
                        <th class="text-center"># </th>
                        <th class="text-center">Arabic Name </th>
                        <th class="text-center">English Name</th>
                        <th class="text-center">Code </th>
                        <th class="text-center">Type </th>
                        <th class="text-center">Allow Reconciliation  (حساب تسوية)</th>
                        <th class="text-center">Account Currency </th>
                        <th class="text-center"> </th>
                        <th class="text-center"> </th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($coa as $index => $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name_ar }}</td>
                            <td>{{ $item->name_en }}</td>
                            <td>{{ $item->ac_number }}</td>
                            <td>{{ $item->coatype->name }}</td>
                            <td>{{  $item->reconciliation  }}</td>
                            <td>{{ isset($item->currency) ? $item->currency->name : '' }}</td>
                            <td>
                                <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                    onclick="editCoa({{ $item->id }})">
                                    <i class="ri ri-pencil-fill fs-22"></i>
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle text-bg-danger"
                                    onclick="deleteCoa({{ $item->id }})">
                                    <i class="ri ri-delete-bin-line fs-15"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel2">Add to COA</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('coa.add') }}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name_ar" class="form-label">Name (Arabic)</label>
                            <input type="text" class="form-control" id="name_ar" name="name_ar" required>
                        </div>
                        <div class="mb-3">
                            <label for="name_en" class="form-label">Name (English)</label>
                            <input type="text" class="form-control" id="name_en" name="name_en" required>
                        </div>
                        <div class="mb-3">
                            <label for="ac_number" class="form-label">Account Number</label>
                            <input type="text" class="form-control" id="ac_number" name="ac_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="type_id" class="form-label">Type</label>
                            <select class="form-control" id="type_id" name="type_id" required>
                                <option selected disabled value="">Select Type</option>
                                @foreach ($coa_types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="reconciliation" class="form-label">Reconciliation </label>
                            <input type="checkbox" id="reconciliation" name="reconciliation" value="1">
                            <label for="reconciliation">Yes, Reconciled</label>
                        </div>
                        <div class="mb-3">
                            <label for="account_currency" class="form-label">Account Currency</label>

                            <select class="form-control" id="account_currency" name="account_currency">
                                <option selected value="">بدون</option>
                                @foreach ($curency as $cc)
                                    <option value="{{ $cc->id }}">{{ $cc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Row</button>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editRow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel2">Edit COA</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('coa.update') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" value="" name="e_id" id="e_id">
                        <div class="mb-3">
                            <label for="name_ar" class="form-label">Name (Arabic)</label>
                            <input type="text" class="form-control" id="e_name_ar" name="name_ar" required>
                        </div>

                        <!-- Name (English) -->
                        <div class="mb-3">
                            <label for="name_en" class="form-label">Name (English)</label>
                            <input type="text" class="form-control" id="e_name_en" name="name_en" required>
                        </div>

                        <!-- Account Number -->
                        <div class="mb-3">
                            <label for="ac_number" class="form-label">Account Number</label>
                            <input type="text" class="form-control" id="e_ac_number" name="ac_number" required>
                        </div>

                        <!-- Type -->
                        <div class="mb-3">
                            <label for="type_id" class="form-label">Type</label>
                           
                            <select class="form-control" id="e_type_id" name="type_id" required>
                                <option  disabled value="">Select Type</option>
                                @foreach ($coa_types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Reconciliation -->
                        <div class="mb-3">
                            <label for="reconciliation" class="form-label">Reconciliation</label>
                            <input type="checkbox" id="e_reconciliation" name="reconciliation">
                        </div>

                        <!-- Account Currency -->
                        <div class="mb-3">
                            <label for="account_currency" class="form-label">Account Currency</label>
                            <select class="form-control" id="e_account_currency" name="account_currency">
                                <option value="">بدون</option>
                                @foreach ($curency as $cc)
                                    <option value="{{ $cc->id }}">{{ $cc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Edit Row</button>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection




@section('js')
    <script>
        $("#branchTreeTable").dataTable({
            // info: false,
            order: [
                [3, 'desc']
            ],
            ordering: true,
            paging: false,
            dom: 'Bfrtip',
            buttons: [
                'print',
                'excel',
                 {
                    extend: 'csvHtml5',
                    text: 'COA CSV',
                    bom: true, // Add BOM for Arabic
                    charset: 'utf-8',
                    title: 'COA Data'
                }
            ]
        })


        function editCoa(id) {

            $.ajax({
                url: '/coa/' + id,
                type: "GET",
                success: function(response) {

                    if (response) {
                        $('#e_name_ar').val(response.name_ar);
                        $('#e_name_en').val(response.name_en);
                        $('#e_ac_number').val(response.ac_number);
                        $('#e_type_id').val(response.type_id);
                        $('#e_reconciliation').prop('checked', response.reconciliation === 'true');
                        $('#e_account_currency').val(response.account_currency);
                        $('#e_id').val(response.id);
                        
                        $('#editRow').modal('show');
                    }

                },
                error: function(xhr, status, error) {

                    alert("error contact admin .");
                }
            });

        }
        function deleteCoa(id) {
            if (!confirm('Are you sure you want to delete this item?')) {
                return; // Exit if the user cancels
            }

            $.ajax({
                url: '/coa/' + id, // The endpoint defined in the route
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}' // Include CSRF token for security
                },
                success: function(response) {
                    alert(response.message); // Show success message
                    location.reload();
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + xhr.responseJSON.message || 'Something went wrong.');
                }
            });
        }
        
        
    </script>
@endsection
