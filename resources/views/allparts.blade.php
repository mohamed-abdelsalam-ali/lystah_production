@extends('layouts.master')
@section('css')

@endsection
@section('title')
     المحاسب
@endsection



@section('content')

    <main role="main" class="main-content ">
        <div class="page-content ">

            <div class="row justify-content-center ">
                <div class="col-12 border">

                    <h2 class="text-center my-2" style="background:#0080006b">المخزون</h2>
                    <form method="GET" action="{{ url('allparts') }}" class="mb-3">
                        <input type="text" name="search" placeholder="Search parts..." class="form-control" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary mt-2">Search</button>
                    </form>
                    <div class="row border">
                        <div class="table-responsive">
                            <div class="row mt-5">
                                <table id="datatabless">
                                    <thead>
                                        <tr>

                                            <td>Name</td>
                                            <td>Name</td>
                                            <td>Name</td>
                                            <td>Name</td>
                                            <td>Name</td>
                                            <td>Name</td>
                                            <td>Name</td>
                                            <td>Name</td>
                                            <td>Amount</td>
                                            <td>Remain</td>
                                            @foreach ($allstores as $store )
                                                <td>{{ $store->name }}</td>
                                            @endforeach
                                            <td>Sell</td>
                                            <td>Refund</td>
                                            <td>Talef</td>
                                            <td>In Kit</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allparts as $allpart )
                                            <tr>
                                                <td>{{ $allpart->part_id }} </td>
                                                <td>{{ $allpart->order_supplier_id }} </td>
                                                <td>{{ $allpart->order_supplier->supplier->name }} </td>
                                                <td>{{ $allpart->order_supplier->confirmation_date }} </td>
                                                <td><a href="/partDetails/1/{{ $allpart->part_id }}">{{ $allpart->part->name }} </a></td>
                                                <td>{{ $allpart->source->name_arabic }} </td>
                                                <td>{{ $allpart->status->name }} </td>
                                                <td>{{ $allpart->part_quality->name }} </td>
                                                <td>{{ $allpart->amount }}</td>
                                                <td>{{ $allpart->remain_amount }}</td>
                                                @foreach ($allpart->stores as $ss )
                                                <td>
                                                    {{ $ss->storepartCount }}
                                                    @foreach ($ss->sections as $sec)
                                                        <li>{{ $sec->amount }} <span class="badge-dark px-2">{{ $sec->store_structure->name }}</span> </li>
                                                    @endforeach
                                                    @if ($ss->inkitsections > 0)
                                                        <li>{{ $ss->inkitsections }} in Kit </li>
                                                    @endif
                                                </td>

                                                @endforeach
                                                <td>{{ $allpart->selles }}</td>
                                                <td>{{ $allpart->refund }}</td>
                                                <td></td>
                                                <td>{{ $allpart->inkit }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $allparts->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    </main>

@endsection




@section('js')
    <script>
$(document).ready(function () {
    $('#datatabless').DataTable({
        paging: false,
        searching: false, // Disable the default search box
        ordering: true,
        responsive: true, // Enable responsiveness 
        language: {
            search: "Search Table:", // Customize the search input label
        },
    });
});

    </script>

@endsection
