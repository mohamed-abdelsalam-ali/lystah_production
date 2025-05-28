@extends('layouts.master')

@section('content')
<div class="main-content ">
    <div class="page-content">
        <h1>Logs</h1>
        {{-- <a href="{{ route('logs.create') }}" class="btn btn-primary">Create Log</a> --}}
        <table class="table mt-3" id="logTbl">
            <thead>
                <tr>
                    <th>Date</th>
                    <th class="w-75">Text</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($log->date)->addHours(9) }}</td> <!-- Until change server timezone -->
                          <td>{!! $log->text !!}</td>
                        <td>{{ $log->get_user->username }}</td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>

        
    </div>
</div>
@endsection
  @section('js')
   <script>
//   $('#logTbl').dataTable();
  
  var table =$('#logTbl').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true
        });
  </script>
  @endsection