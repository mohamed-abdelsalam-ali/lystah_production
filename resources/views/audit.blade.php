    @extends('layouts.master')
    @section('css')


        <style>


        </style>


    @endsection
    @section('title')
        audit
    @stop


    @section('content')

        @if ($message = Session::get('success'))
            <div class="alert alert-primary " style="z-index: 88888 !important;" role="alert">
                <button type="button" class="btn-close bg-black text-white" data-bs-dismiss="alert"
                    aria-label="Close"></button>
                {{ $message }}
            </div>
        @endif

        <div class="main-content ">
            <div class="page-content">
                <div class="row">
                    <div class="col-12">
                        <div
                            class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                            <h4 class="mb-sm-0">Auditing</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item active">Auditing</li>
                                    <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-head">
                                <div class="row">
                                    <select id="modelSelect">
                                        <option value="" selected disabled>Select Model</option>
                                        @foreach ($models as $model)
                                            <option value="{{ $model }}">{{ $model }}</option>
                                        @endforeach
                                    </select>


                                </div>
                            </div>
                            <div class="card-body table-responsive ">

                                <table id="auditsTable"
                                    class=" table table-striped table-bordered cell-border dataTable no-footer dtr-inline"
                                    style="width:100%">

                                    <thead style="background:#5fcee78a" class="text-nowrap">

                                        <tr>
                                            <th class="text-center">id </th>
                                            <th class="text-center">Model </th>
                                            <th class="text-center">Model_id </th>
                                            <th class="text-center">URL </th>
                                            <th class="text-center">Event </th>
                                            <th class="text-center"> Old Values </th>
                                            <th class="text-center">New Values </th>
                                            <th class="text-center"> Performed By </th>
                                            <th class="text-center">Performed At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($audits as $audit)
                                            <tr>
                                                <td class="text-center">{{ $audit->id }}</td>

                                                <td class="text-center">{{ $audit->auditable_type }}</td>
                                                <td class="text-center">{{ $audit->auditable_id }}</td>
                                                <td class="text-center"> <a href="{{ $audit->url }}"
                                                        target="_blank">{{ $audit->url }}</a>
                                                </td>

                                                <td class="text-center">{{ $audit->event }}</td>
                                                <td class="text-center">
                                                    @foreach ($audit->old_values as $key => $value)
                                                        <strong>{{ $key }}:</strong> {{ $value }}<br>
                                                    @endforeach
                                                </td>
                                                <td class="text-center">
                                                    @foreach ($audit->new_values as $key => $value)
                                                        <strong>{{ $key }}:</strong> {{ $value }}<br>
                                                    @endforeach
                                                </td>
                                                <td class="text-center">
                                                    {{ $audit->performed_by ? $audit->performed_by->username : 'N/A' }}
                                                </td>
                                                <td class="text-center">{{ $audit->created_at }}</td>
                                            </tr>
                                        @endforeach


                                    </tbody>

                                </table>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>


    @endsection

    @section('js')
        <script>
            $(document).ready(function() {
                $("#modelSelect").select2();

                function formatDate(timestamp) {
                    let date = new Date(timestamp);

                    // Format the date and time
                    let year = date.getFullYear();
                    let month = ('0' + (date.getMonth() + 1)).slice(-2); // Add leading zero
                    let day = ('0' + date.getDate()).slice(-2); // Add leading zero
                    let hours = ('0' + date.getHours()).slice(-2); // Add leading zero
                    let minutes = ('0' + date.getMinutes()).slice(-2); // Add leading zero
                    let seconds = ('0' + date.getSeconds()).slice(-2); // Add leading zero

                    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
                }

               var table = $('#auditsTable').DataTable({
    "columnDefs": [
        
        {
            "orderable": false, // Disable ordering on the second column (audit.auditable_type)
            "targets": [1] // Target the second column
        }
    ],
    "order": [[0, "desc"]] // Order by the first column (audit.id) in descending order
}); 
                $('#modelSelect').change(function() {
                    var model = $(this).val();

                    if (model) {
                        $.ajax({
                            url: '{{ route('audit.getAudits') }}',
                            method: 'GET',
                            data: {
                                model: model
                            },
                            success: function(response) {
                                // Clear existing table data
                                table.clear().draw();
                                if (response.length === 0) {
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'No audits found',
                                        text: 'No audits found for the selected model.'
                                    });
                                    return;
                                }

                                table.clear().draw();

                                $.each(response, function(index, audit) {
                                    var oldValues = '';
                                    var newValues = '';


                                    $.each(audit.old_values, function(key, value) {
                                        oldValues += '<strong>' + key +
                                            ':</strong> ' + value + '<br>';
                                    });

                                    $.each(audit.new_values, function(key, value) {
                                        newValues += '<strong>' + key +
                                            ':</strong> ' + value + '<br>';
                                    });

                                    var formattedCreatedAt = formatDate(audit
                                        .created_at); // Format the created_at field
                                    var urlLink = '<a href="' + audit.url +
                                        '" target="_blank">' + audit.url +
                                        '</a>'; // Create anchor tag for URL

                                    table.row.add([
                                        audit.id,
                                        audit.auditable_type, // Model name
                                        audit.auditable_id, // Model ID
                                        urlLink,
                                        audit.event,

                                        oldValues,
                                        newValues,
                                        audit.performed_by ? audit.performed_by
                                        .username : 'N/A',
                                        formattedCreatedAt // Formatted date
                                    ]).draw(false);
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Failed to retrieve audits. Please try again.'
                                });
                            }
                        });
                    }
                });

                $('#auditsTable').DataTable();
            });
        </script>


    @endsection
