@extends('layouts.master')
@section('css')
@endsection
@section('title')
    الشجرة المحاسبية
@endsection

@section('content')
    <main role="main" class="main-content ">
        <div class="page-content">
            <a type="button" href="{{ route('journal.add') }}" class="btn btn-icon btn-topbar  float-end btn-info px-5" >
                 <i class="ri ri-add-box-line fs-22"></i>
            </a>
            <table class="table table-striped table-bordered table-hover bg-white fs-15" id="branchTreeTable">
                <thead>
                    <tr class="h">
                        <th class="text-center"># </th>
                        <th class="text-center">Name </th>
                        <th class="text-center">Type </th>
                        <th class="text-center">Notes </th>
                        <th class="text-center">Details</th>
                        <th class="text-center"> </th>
                        <th class="text-center"> </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($journals as $index => $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->jornaltype->name }}</td>
                            <td>{{ $item->notes }}</td>
                            <td>
                                <a type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                     href="/journal/{{ $item->id }}">
                                    <i class="ri ri-arrow-down-s-line fs-22"></i>
                                </a>
                            </td>
                            <td>
                                <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                    onclick="editJournal({{ $item->id }})">
                                    <i class="ri ri-pencil-fill fs-22"></i>
                                </button>
                            </td>
                            <td>
                                <button type="button"
                                    class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle text-bg-danger"
                                    onclick="deleteJournal({{ $item->id }})">
                                    <i class="ri ri-delete-bin-line fs-15"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach


                </tbody>
            </table>
        </div>
    </main>
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
                'excel'
            ]
        })

    function deleteJournal(id){
            if (!confirm('Are you sure you want to delete this Journal With Details?')) {
                return; // Exit if the user cancels
            }

            $.ajax({
                url: '/journals/' + id, // The endpoint defined in the route
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
