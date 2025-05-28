@extends('layouts.posMaster')
@section('title')
    صرف بضاعة للصيانة
@stop
@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">  صرف بضاعة للصيانة </li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <form action="#" method="POST">
                @csrf
                @method('POST')
                <div class="card">


                    <div class="card-body fs-19 fw-bold">
                        <div class="row">
                           <div class="col-lg-12">

                            <table id="itemsTbl" class="table table-striped " style="width:100%">
                                <thead style="background:#5fcee78a">
                                    <tr>
                                        <th>التاريخ</th>
                                        <th>النوع</th>
                                        <th>رقم الشاسيه</th>
                                        <th>المخزن</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $key => $value)
                                        <td>{{ $value->date }}</td>
                                        <td>{{ $value->type }}</td>
                                        @if($value->type_id == '3')
                                            <td>{{ $value->itemData->tractor_number }}</td>
                                        @elseif($value->type_id == '4')
                                            <td>{{ $value->itemData->clark_number }}</td>
                                        @elseif($value->type_id == '5')
                                            <td>{{ $value->itemData->name }}</td>
                                        @endif

                                        <td>{{ $value->store->name }}</td>
                                        <td><a href="/orderpartservice/{{ $value->id }}/{{ $value->store_id }}">عرض</a></td>
                                    @empty
                                        <tr>
                                            <td colspan="5">لا يوجد طلبات</td>

                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                           </div>
                        </div>
                    </div>
                </div>
            </form>




        </div>




    @endsection
    @section('js')
        <script>

        </script>

    @endsection
