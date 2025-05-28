@extends('layouts.master')
@section('css')
@endsection
@section('title')
    الشجرة المحاسبية
@endsection

@section('content')
    <main role="main" class="main-content ">
        <div class="page-content">
            <!--<button type="button" class="btn btn-icon btn-topbar  float-end btn-info px-5" data-bs-toggle="modal"-->
            <!--    data-bs-target="#staticBackdrop">-->
            <!--    New <i class="ri ri-add-box-line fs-22"></i>-->
            <!--</button>-->
            <table class="table table-striped table-bordered table-hover bg-white fs-15" id="branchTreeTable">
                <thead>
                    <tr class="h">
                        <th class="text-center"># </th>
                        <!--<th class="text-center">refrence</th>-->
                        <!--<th class="text-center">coa</th>-->
                        <!--<th class="text-center">journal </th>-->
                        <!--<th class="text-center">partner </th>-->
                        <!--<th class="text-center">type</th>-->
                        <th class="text-center">label</th>
                        <!--<th class="text-center">cost_center </th>-->
                        <!--<th class="text-center">amount_currency </th>-->
                        <!--<th class="text-center"> currency_id</th>-->
                        <!--<th class="text-center"> debit</th>-->
                        <!--<th class="text-center"> credit</th>-->
                        <th class="text-center d-none"> Details</th>
                        <th class="text-center"> Desc</th>
                        <th class="text-center"> </th>
                        <th class="text-center"> </th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($allQayds as $index => $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <!--<td>{{ $item->refrence }}</td>-->
                            <!--<td>{{ $item->coa_id }}</td>-->
                            <!--<td>{{ $item->journal_id }}</td>-->
                            <!--<td>{{ $item->partner_id }}</td>-->
                            <!--<td>{{ $item->type }}</td>-->
                            <td>{{ $item->name }}</td>
                            <!--<td>{{ $item->cost_center }}</td>-->
                            <!--<td>{{ $item->amount_currency }}</td>-->
                            <!--<td>{{ $item->currency_id }}</td>-->
                            <!--<td>{{ $item->debit }}</td>-->
                            <!--<td>{{ $item->credit }}</td>-->
                            <td class="d-none">
                                @if (isset($item['newqayds']) && count($item['newqayds']) > 0)
                                    <ul>
                                        @foreach ($item['newqayds'] as $newQayd)
                                        <li>
                                            ID: {{ $newQayd['id'] }}, Reference: {{ $newQayd['refrence'] }}, Debit: {{ $newQayd['debit'] }}, Credit: {{ $newQayd['credit'] }}
                                        </li>
                                        @endforeach
                                    </ul>
                                @else
                                    No New Qayds
                                @endif
                            </td>
                            <td>{{ $item->desc }}</td>
                            <td>
                                <a type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                    href="/qayds/{{ $item->id }}">
                                    <i class="ri ri-eye-fill text-warning fs-22"></i>
                                </a>
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


        function editCoa(id) {

           

        }
        function deleteCoa(id) {
            if (!confirm('Are you sure you want to delete this item?')) {
                return; // Exit if the user cancels
            }

            // $.ajax({
            //     url: '/coa/' + id, // The endpoint defined in the route
            //     type: 'DELETE',
            //     data: {
            //         _token: '{{ csrf_token() }}' // Include CSRF token for security
            //     },
            //     success: function(response) {
            //         alert(response.message); // Show success message
            //         location.reload();
            //     },
            //     error: function(xhr, status, error) {
            //         alert('Error: ' + xhr.responseJSON.message || 'Something went wrong.');
            //     }
            // });
        }
        
        
    </script>
@endsection
