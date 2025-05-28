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
                                <li class="breadcrumb-item active"> صرف بضاعة للصيانة </li>
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
                        <h1 class="text-center">إذن توريد بضاعة لصالح الصيانة</h1>
                    <div class="card-body fs-19 fw-bold">

                        <div class="row mb-3 border p-2">

                            <div class="col-lg-2 col-xs-2">{{ $data->date }}</div>
                            <div class="col-lg-1 col-xs-1">{{ $data->type }}</div>
                            <div class="col-lg-1">رقم الشاسيه</div>
                            @if($data->type_id == '3')
                                <div class="col-lg-2">({{ $data->itemData->tractor_number }})</div>

                            @elseif($data->type_id == '4')
                                <div class="col-lg-2">({{ $data->itemData->clark_number }})</div>

                            @elseif($data->type_id == '5')
                                <div class="col-lg-2">({{ $data->itemData->name }})</div>

                            @endif
                            {{-- <div class="col-lg-1">{{ $data->pricing_type->type }}</div> --}}

                        </div>

                        <div class="row">
                            <div class="col-lg-12">

                                <table id="" class="table table-striped " style="width:100%">
                                    <thead style="background:#5fcee78a">
                                        <tr>
                                            <th>النوع</th>
                                            <th>الاسم</th>
                                            <th>المنشأ</th>
                                            <th>الحالة</th>
                                            <th>الجودة</th>
                                            <th>الكمية</th>
                                            <th>{{ $data->pricing_type->type }}</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data->order_part_service_items_delivers as $key => $item)
                                            <tr class="check${{$item->id}}" >
                                                <input type="hidden" name="part_id[]" value="{{$item->part_id}}">
                                                <input type="hidden" name="source_id[]" value="{{$item->source->id}}">
                                                <input type="hidden" name="status_id[]" value="{{$item->status->id}}">
                                                <input type="hidden" name="quality_id[]" value="{{$item->part_quality->id}}">
                                                <input type="hidden" name="type_id[]" value="{{$item->type->id}}">

                                                <td>{{ $item->type->name }}</td>
                                                <td>{{ $item->data->name }}</td>
                                                <td>{{ $item->source->name_arabic }}</td>
                                                <td>{{ $item->status->name }}</td>
                                                <td>{{ $item->part_quality->name }}</td>
                                                <td>{{ $item->amount }}</td>
                                                <td >{{ $item->price[0]->price }}</td>


                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8">لا يوجد بيانات</td>

                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="col-lg-1">{{ $data->user->username }}</div>

                    </div>
                </div>
            </form>




        </div>




    @endsection
    @section('js')
        <script>
            $(document).ready(function() {

            })
        </script>

    @endsection
