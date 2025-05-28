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
            <form action="/service_part_delever" method="POST">
                @csrf
                @method('POST')
                <div class="card">
                    <input type="hidden" name="order_id" value="{{$data->id}}">
                    <input type="hidden" name="equips_id" value="{{$data->equips_id}}">
                    <input type="hidden" name="equips_type_id" value="{{$data->type_id}}">
                    <input type="hidden" name="equips_store_id" value="{{$data->store_id}}">

                    <div class="card-body fs-19 fw-bold">
                        <div class="row mb-3 border p-2">
                            <div class="col-lg-1">التاريخ</div>
                            <div class="col-lg-3">{{ $data->date }}</div>
                            <div class="col-lg-1">النوع</div>
                            <div class="col-lg-3">{{ $data->type }}</div>
                            <div class="col-lg-1">رقم الشاسيه</div>
                            @if($data->type_id == '3')
                                <div class="col-lg-3">{{ $data->itemData->tractor_number }}</div>

                            @elseif($data->type_id == '4')
                                <div class="col-lg-3">{{ $data->itemData->clark_number }}</div>

                            @elseif($data->type_id == '5')
                                <div class="col-lg-3">{{ $data->itemData->name }}</div>

                            @endif

                        </div>
                        <div class="row">
                           <div class="col-lg-12">

                            <table id="itemsTbl" class="table table-striped " style="width:100%">
                                <thead style="background:#5fcee78a">
                                    <tr>
                                        <th>النوع</th>
                                        <th>الاسم</th>
                                        <th>المنشأ</th>
                                        <th>الحالة</th>
                                        <th>الجودة</th>
                                        <th>الكمية</th>
                                        <th>المتاح</th>
                                        <th>إرسال</th>
                                        <th>المخازن</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data->items as $key => $item)
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
                                            <td >{{ $item->avialable }}</td>

                                            <td><input class="form-control" name="deliver_amount[]" type="number" step="any" value="{{ $item->amount }}" max="{{ $item->amount }}" min="1" required></td>

                                            <td>
                                                <ol class="list-group list-group-numbered">
                                                    @foreach ($item->PartInStoresCount as $x )
                                                       {{-- <li>{{ $x->name }} ( {{ $x->storepartCount }} )</li> --}}
                                                       <li class="list-group-item d-flex justify-content-between align-items-start py-1">
                                                        <div class="ms-2 me-auto">
                                                          <div class="fw-bold">{{ $x->name }}</div>

                                                        </div>
                                                        <span class="badge bg-primary rounded-pill">{{ $x->storepartCount }}</span>
                                                      </li>
                                                    @endforeach
                                                </ol>
                                            </td>
                                            <td class="align-content-center"><i class="fs-2 hover mdi mdi-trash-can text-danger" style="cursor: pointer;" onclick="$(this).closest('tr').remove();"></i></td>
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

                        <div class="row">
                            <div class="col-lg-1">
                                    نوع البيع
                            </div>
                            <div class="col-lg-5">
                                    <select class="form-control" name="sale_type" id="" required>
                                        <option value="" selected disabled>اختر التسعيرة</option>
                                        @foreach ($sale_type as  $typ)
                                            <option value="{{ $typ->id }}">{{ $typ->type }}</option>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="col-lg-1">
                                 ملاحظة
                        </div>
                            <div class="col-lg-5">
                                <textarea name="note" id="" cols="60" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12">
                                <button class="btn btn-danger w-100">صرف</button>
                            </div>
                        </div>
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
