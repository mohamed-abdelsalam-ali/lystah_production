@extends('layouts.master')
{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

@section('css')

    <style>
        .collapsible {
          background-color: #777777a3;
          color: white;
          cursor: pointer;
          padding: 18px;
          width: 100%;
          border: none;
          text-align: center;
          outline: none;

        }

        .collapsible:hover {
          background-color: #555;
        }


    </style>
@endsection
@section('title')
    Items Details
@stop


@section('content')



    <div class="main-content">
        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Item Details</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Item</li>
                                <li class="breadcrumb-item"><a href="home">Home</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="mx-3">
                 <div class="row card">
                    <h4 class="collapsible"> مشتروات الصنف</h4>
                    <div class="card-body table-responsive-lg">
                        <table class="table-bordered cell-border table table-sm table-striped  fw-bold m-1  ">
                            <thead style="background:#5fcee78a">
                                <tr>
                                    <th>رقم</th>
                                    <th>التاريخ</th>
                                    <th>المنشأ</th>
                                    <th>الحالة</th>
                                    <th>الجودة</th>
                                    <th>الكمية</th>
                                    <th>الكمية المتبقية</th>
                                    <th>المورد</th>
                                    <th>تكلفة القطعة</th>
                                    <th>سعر القطعة</th>
                                    <th>تعديل</th>
                                    {{-- <th>حذف</th> --}}
                                </tr>
                            </thead>
                            @php
                                $totalbuy = 0;
                                $totalsell = 0;
                                $totalrefund = 0;
                                $totalkit = 0;
                                $totalamount = 0;
                                $totalsection = 0;

                            @endphp
                            @if ($data->type == 'Part')
                                @forelse ($data->all_parts as $key => $value)
                                    <tr>

                                        <td> <a target="_blank" href="/printBuyInvoice/{{ $value->order_supplier->transaction_id }}">فاتورة شراء رقم {{ $value->order_supplier->transaction_id }} </a></td>
                                        <td> {{ explode(' ', $value->order_supplier->confirmation_date)[0] }} </td>
                                        {{-- <td> {{ $value->source->name_arabic }} </td>
                                            <td> {{ $value->status->name }} </td>
                                            <td> {{ $value->part_quality->name }} </td>
                                            <td> {{ $value->amount }} </td> --}}
                                        {{-- <td>
                                                <span class="editable" data-id="{{ $value->id }}" data-field="source">{{ $value->source->name_arabic }}</span>
                                                <select class="edit-input" data-id="{{ $value->id }}" data-field="source" style="display:none;">
                                                    @foreach ($source as $key => $valuess)
                                                    <option value="{{$valuess->id}}">{{$valuess->name_arabic}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <span class="editable" data-id="{{ $value->id }}" data-field="status">{{ $value->status->name }}</span>
                                                <select class="edit-input" data-id="{{ $value->id }}" data-field="status" style="display:none;">
                                                    @foreach ($status as $key => $valuez)
                                                    <option value="{{$valuez->id}}">{{$valuez->name}}</option>
                                                    @endforeach
                                                </select>                                            </td>
                                            <td>
                                                <span class="editable" data-id="{{ $value->id }}" data-field="quality">{{ $value->part_quality->name }}</span>
                                                <select class="edit-input" data-id="{{ $value->id }}" data-field="quality" style="display:none;">
                                                    @foreach ($quality as $key => $valueq)
                                                    <option value="{{$valueq->id}}">{{$valueq->name}}</option>
                                                    @endforeach
                                                </select>                                            </td>
                                            <td>
                                                <span class="editable" data-id="{{ $value->id }}" data-field="amount">{{ $value->amount }}</span>
                                                <input type="text" class="edit-input" data-id="{{ $value->id }}" data-field="amount" value="{{ $value->amount }}" style="display:none;">
                                            </td> --}}
                                        <td>
                                            <span class="editable" data-id="{{ $value->id }}"
                                                data-field="source">{{ $value->source->name_arabic }}</span>
                                            <select class="edit-input" data-id="{{ $value->id }}" data-field="source"
                                                style="display:none;">

                                                @foreach ($allsource as $source)
                                                    <option value="{{ $source->id }}"
                                                        {{ $source->id == $value->source_id ? 'selected' : '' }}>
                                                        {{ $source->name_arabic }}</option>
                                                @endforeach
                                            </select>
                                            {{-- <i class="bx bx-edit fs-22 edit-icon" data-id="{{ $value->id }}" data-type_id="1"></i>
                                                <i class="bx bx-save fs-22 save-icon" data-id="{{ $value->id }}" data-type_id="1" style="display:none;"></i> --}}
                                        </td>

                                        <td>
                                            <span class="editable" data-id="{{ $value->id }}"
                                                data-field="status">{{ $value->status->name }}</span>
                                            <select class="edit-input" data-id="{{ $value->id }}" data-field="status"
                                                style="display:none;">
                                                @foreach ($allstatus as $status)
                                                    <option value="{{ $status->id }}"
                                                        {{ $status->id == $value->status_id ? 'selected' : '' }}>
                                                        {{ $status->name }}</option>
                                                @endforeach
                                            </select>
                                            {{-- <i class="bx bx-edit fs-22 edit-icon" data-id="{{ $value->id }}" data-type_id="1"></i>
                                                <i class="bx bx-save fs-22 save-icon" data-id="{{ $value->id }}" data-type_id="1" style="display:none;"></i> --}}
                                        </td>

                                        <td>
                                            <span class="editable" data-id="{{ $value->id }}"
                                                data-field="quality">{{ $value->part_quality->name }}</span>
                                            <select class="edit-input" data-id="{{ $value->id }}" data-field="quality"
                                                style="display:none;">
                                                @foreach ($allpart_quality as $quality)
                                                    <option value="{{ $quality->id }}"
                                                        {{ $quality->id == $value->quality_id ? 'selected' : '' }}>
                                                        {{ $quality->name }}</option>
                                                @endforeach
                                            </select>
                                            {{-- <i class="bx bx-edit fs-22 edit-icon" data-id="{{ $value->id }}" data-type_id="1"></i>
                                                <i class="bx bx-save fs-22 save-icon" data-id="{{ $value->id }}" data-type_id="1" style="display:none;"></i> --}}
                                        </td>

                                        <td>
                                            {{ $value->amount }}
                                            @php
                                                $totalbuy +=$value->amount;
                                            @endphp
                                            {{-- <span class="editable" data-id="{{ $value->id }}" data-field="amount">{{ $value->amount }}</span>
                                                <input type="number" class="edit-input" data-id="{{ $value->id }}" data-field="amount" value="{{ $value->amount }}" style="display:none;"> --}}
                                            {{-- <i class="bx bx-edit fs-22 edit-icon" data-id="{{ $value->id }}" data-type_id="1"></i>
                                                <i class="bx bx-save fs-22 save-icon" data-id="{{ $value->id }}" data-type_id="1" style="display:none;"></i> --}}
                                        </td>
                                        <td> {{ $value->remain_amount }} </td>
                                        <td> {{ $value->order_supplier->supplier->name }} </td>
                                        <td> {{ $value->buy_costing }} </td>

                                        <td> {{ $value->buy_price }} / <a target="_blank" href="/show_currency/{{ $value->order_supplier->currency_type->id }}">{{ $value->order_supplier->currency_type->name }} </a>
                                        </td>
                                        <td>
                                            <i class="bx bx-edit fs-22 edit-icon text-info" data-id="{{ $value->id }}"
                                                data-type_id="1"></i>
                                            <i class="bx bx-save fs-22 save-icon" data-id="{{ $value->id }}"
                                                data-type_id="1" style="display:none;"></i>
                                        </td>
                                        {{-- <td><i class="bx bx-x-circle fs-22 text-danger" data-id="{{ $value->id }}" data-type_id="1"></i></td> --}}

                                    </tr>
                                    {{-- @forelse ($value->sections as $key => $valuex)
                                                <tr class="bg-danger">
                                                    <td>{{ $valuex->store->name }} </td>
                                                    <td> {{ $valuex->store_structure->name }} </td>
                                                    <td> {{ $valuex->amount }} </td>
                                                    <td><i class="bx bx-edit fs-22" data-id="{{ $valuex->id }}"></i> </td>
                                                    <td><i class="bx bx-x-circle fs-22 text-danger" data-id="{{ $valuex->id }}"></i></td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td>  لا يوجد أقسام للصنف</td>
                                                </tr>
                                                @endforelse --}}


                                @empty
                                    <tr>
                                        <td>لا يوجد فواتير</td>
                                    </tr>
                                @endforelse
                            @elseif($data->type == 'Kit')
                                @forelse ($data->all_kits as $key => $value)
                                    <tr>
                                        <td>فاتورة شراء رقم {{ $value->order_supplier->transaction_id }} </td>
                                        <td> {{ explode(' ', $value->order_supplier->confirmation_date)[0] }} </td>
                                        {{-- <td> {{ $value->source->name_arabic }} </td>
                                            <td> {{ $value->status->name }} </td>
                                            <td> {{ $value->part_quality->name }} </td> --}}
                                        <td>
                                            <span class="editable" data-id="{{ $value->id }}"
                                                data-field="source">{{ $value->source->name_arabic }}</span>
                                            <select class="edit-input" data-id="{{ $value->id }}" data-field="source"
                                                style="display:none;">

                                                @foreach ($allsource as $source)
                                                    <option value="{{ $source->id }}"
                                                        {{ $source->id == $value->source_id ? 'selected' : '' }}>
                                                        {{ $source->name_arabic }}</option>
                                                @endforeach
                                            </select>
                                            {{-- <i class="bx bx-edit fs-22 edit-icon" data-id="{{ $value->id }}" data-type_id="1"></i>
                                                <i class="bx bx-save fs-22 save-icon" data-id="{{ $value->id }}" data-type_id="1" style="display:none;"></i> --}}
                                        </td>

                                        <td>
                                            <span class="editable" data-id="{{ $value->id }}"
                                                data-field="status">{{ $value->status->name }}</span>
                                            <select class="edit-input" data-id="{{ $value->id }}" data-field="status"
                                                style="display:none;">
                                                @foreach ($allstatus as $status)
                                                    <option value="{{ $status->id }}"
                                                        {{ $status->id == $value->status_id ? 'selected' : '' }}>
                                                        {{ $status->name }}</option>
                                                @endforeach
                                            </select>
                                            {{-- <i class="bx bx-edit fs-22 edit-icon" data-id="{{ $value->id }}" data-type_id="1"></i>
                                                <i class="bx bx-save fs-22 save-icon" data-id="{{ $value->id }}" data-type_id="1" style="display:none;"></i> --}}
                                        </td>

                                        <td>
                                            <span class="editable" data-id="{{ $value->id }}"
                                                data-field="quality">{{ $value->part_quality->name }}</span>
                                            <select class="edit-input" data-id="{{ $value->id }}"
                                                data-field="quality" style="display:none;">
                                                @foreach ($allpart_quality as $quality)
                                                    <option value="{{ $quality->id }}"
                                                        {{ $quality->id == $value->quality_id ? 'selected' : '' }}>
                                                        {{ $quality->name }}</option>
                                                @endforeach
                                            </select>
                                            {{-- <i class="bx bx-edit fs-22 edit-icon" data-id="{{ $value->id }}" data-type_id="1"></i>
                                                <i class="bx bx-save fs-22 save-icon" data-id="{{ $value->id }}" data-type_id="1" style="display:none;"></i> --}}
                                        </td>
                                        <td> {{ $value->amount }}
                                            @php
                                                $totalbuy +=$value->amount;
                                            @endphp
                                        </td>
                                        <td> {{ $value->remain_amount }} </td>
                                        <td> {{ $value->order_supplier->supplier->name }} </td>
                                        <td> {{ $value->buy_costing }} </td>
                                        <td> {{ $value->buy_price }} / {{ $value->order_supplier->currency_type->name }}
                                        </td>
                                        <td>
                                            <i class="bx bx-edit fs-22 edit-icon  text-info" data-id="{{ $value->id }}"
                                                data-type_id="6"></i>
                                            <i class="bx bx-save fs-22 save-icon" data-id="{{ $value->id }}"
                                                data-type_id="6" style="display:none;"></i>
                                        </td>
                                        {{-- <td><i class="bx bx-edit fs-22" data-id="{{ $value->id }}" data-type_id="6"></i> </td>
                                            <td><i class="bx bx-x-circle fs-22 text-danger" data-id="{{ $value->id }}"  data-type_id="6"></i></td> --}}
                                    </tr>
                                    {{-- @forelse ($value->sections as $key => $valuex)
                                                <tr class="bg-danger">
                                                    <td>{{ $valuex->store->name }} </td>
                                                    <td> {{ $valuex->store_structure->name }} </td>
                                                    <td> {{ $valuex->amount }} </td>
                                                    <td><i class="bx bx-edit fs-22" data-id="{{ $valuex->id }}"></i> </td>
                                                    <td><i class="bx bx-x-circle fs-22 text-danger" data-id="{{ $valuex->id }}"></i></td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td>  لا يوجد أقسام للصنف</td>
                                                </tr>
                                            @endforelse --}}
                                @empty
                                    <tr>
                                        <td>لا يوجد فواتير</td>
                                    </tr>
                                @endforelse
                            @elseif($data->type == 'Wheel')
                                @forelse ($data->all_wheels as $key => $value)
                                    <tr>
                                        <td>فاتورة شراء رقم {{ $value->order_supplier->transaction_id }} </td>
                                        <td> {{ explode(' ', $value->order_supplier->confirmation_date)[0] }} </td>
                                        {{-- <td> {{ $value->source->name_arabic }} </td>
                                            <td> {{ $value->status->name }} </td>
                                            <td> {{ $value->part_quality->name }} </td> --}}
                                        <td>
                                            <span class="editable" data-id="{{ $value->id }}"
                                                data-field="source">{{ $value->source->name_arabic }}</span>
                                            <select class="edit-input" data-id="{{ $value->id }}" data-field="source"
                                                style="display:none;">

                                                @foreach ($allsource as $source)
                                                    <option value="{{ $source->id }}"
                                                        {{ $source->id == $value->source_id ? 'selected' : '' }}>
                                                        {{ $source->name_arabic }}</option>
                                                @endforeach
                                            </select>
                                            {{-- <i class="bx bx-edit fs-22 edit-icon" data-id="{{ $value->id }}" data-type_id="1"></i>
                                                <i class="bx bx-save fs-22 save-icon" data-id="{{ $value->id }}" data-type_id="1" style="display:none;"></i> --}}
                                        </td>

                                        <td>
                                            <span class="editable" data-id="{{ $value->id }}"
                                                data-field="status">{{ $value->status->name }}</span>
                                            <select class="edit-input" data-id="{{ $value->id }}" data-field="status"
                                                style="display:none;">
                                                @foreach ($allstatus as $status)
                                                    <option value="{{ $status->id }}"
                                                        {{ $status->id == $value->status_id ? 'selected' : '' }}>
                                                        {{ $status->name }}</option>
                                                @endforeach
                                            </select>
                                            {{-- <i class="bx bx-edit fs-22 edit-icon" data-id="{{ $value->id }}" data-type_id="1"></i>
                                                <i class="bx bx-save fs-22 save-icon" data-id="{{ $value->id }}" data-type_id="1" style="display:none;"></i> --}}
                                        </td>

                                        <td>
                                            <span class="editable" data-id="{{ $value->id }}"
                                                data-field="quality">{{ $value->part_quality->name }}</span>
                                            <select class="edit-input" data-id="{{ $value->id }}"
                                                data-field="quality" style="display:none;">
                                                @foreach ($allpart_quality as $quality)
                                                    <option value="{{ $quality->id }}"
                                                        {{ $quality->id == $value->quality_id ? 'selected' : '' }}>
                                                        {{ $quality->name }}</option>
                                                @endforeach
                                            </select>
                                            {{-- <i class="bx bx-edit fs-22 edit-icon" data-id="{{ $value->id }}" data-type_id="1"></i>
                                                <i class="bx bx-save fs-22 save-icon" data-id="{{ $value->id }}" data-type_id="1" style="display:none;"></i> --}}
                                        </td>
                                        <td> {{ $value->amount }} </td>
                                        @php
                                            $totalbuy +=$value->amount;
                                        @endphp
                                        <td> {{ $value->remain_amount }} </td>
                                        <td> {{ $value->order_supplier->supplier->name }} </td>
                                        <td> {{ $value->buy_costing }} </td>
                                        <td> {{ $value->buy_price }} / {{ $value->order_supplier->currency_type->name }}
                                        </td>
                                        <td>
                                            <i class="bx bx-edit fs-22 edit-icon  text-info" data-id="{{ $value->id }}"
                                                data-type_id="2"></i>
                                            <i class="bx bx-save fs-22 save-icon" data-id="{{ $value->id }}"
                                                data-type_id="2" style="display:none;"></i>
                                        </td>
                                        {{-- <td><i class="bx bx-edit fs-22" data-id="{{ $value->id }}" data-type_id="2"></i> </td>
                                            <td><i class="bx bx-x-circle fs-22 text-danger" data-id="{{ $value->id }}"  data-type_id="2"></i></td> --}}
                                    </tr>
                                    {{-- @forelse ($value->sections as $key => $valuex)
                                                <tr class="bg-danger">
                                                    <td>{{ $valuex->store->name }} </td>
                                                    <td> {{ $valuex->store_structure->name }} </td>
                                                    <td> {{ $valuex->amount }} </td>
                                                    <td><i class="bx bx-edit fs-22" data-id="{{ $valuex->id }}"></i> </td>
                                                    <td><i class="bx bx-x-circle fs-22 text-danger" data-id="{{ $valuex->id }}"></i></td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td>  لا يوجد أقسام للصنف</td>
                                                </tr> --}}
                                    {{-- @endforelse --}}
                                @empty
                                    <tr>
                                        <td>لا يوجد فواتير</td>
                                    </tr>
                                @endforelse
                            @else
                            @endif

                        </table>
                    </div>
                </div>

                  <div class="row card">
                    <h4 class="collapsible"> توزيع الصنف على الاقسام</h4>
                    <div class="card-body">

                        <table class="table-bordered cell-border table table-sm table-striped  fw-bold m-1  ">
                            <thead style="background:#5fcee78a">
                                <tr>
                                    <th>المخزن</th>
                                    <th>الاسم</th>
                                    <th>المنشأ</th>
                                    <th>الحالة</th>
                                    <th>الكفاءة</th>
                                    <th>الكمية</th>
                                    <th>تعديل</th>
                                      <th>خطأ</th>

                                </tr>
                            </thead>
                            @if ($data->type == 'Part' && isset($data->all_parts))
                                @forelse ($data->sectionss as $key => $value)
                                    <tr>
                                        <td>{{ $value->store->name }} </td>

                                        <td>{{ $value->name }} </td>
                                        <td>{{ $value->source->name_arabic }} </td>
                                        <td>{{ $value->status->name }} </td>
                                        <td>{{ $value->part_quality->name }} </td>
                                        <td> {{ $value->amount }}
                                            @php
                                                $totalsection +=$value->amount;
                                            @endphp
                                        </td>
                                        <td>
                                            <i class="bx bx-edit fs-22 edit-icon2 text-info"
                                                onclick='change_scetion({{ $value }})'
                                                data-id="{{ $value->id }}" data-type_id="1"></i>
                                        </td>
                                          <td>
                                            <button class="btn correctkitsection" data-bs-toggle="modal" data-bs-target="#correctkitsection" data-id="{{ $value->id }}" data-part_id="{{ $value->part_id }}" data-source="{{ $value->source_id }}" data-status="{{ $value->status_id }}" data-quality="{{ $value->quality_id }}" data-amount="{{ $value->amount }}">
                                                <i class="bx bx-x-circle fs-22 text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td> لا يوجد فى الأقسام</td>
                                    </tr>
                                @endforelse
                            @elseif($data->type == 'Kit')
                                @forelse ($data->sectionss as $key => $value)
                                    <tr>
                                        <td>{{ $value->store->name }} </td>
                                        <td>{{ $value->name }} </td>
                                        <td>{{ $value->source->name_arabic }} </td>
                                        <td>{{ $value->status->name }} </td>
                                        <td>{{ $value->part_quality->name }} </td>
                                        <td> {{ $value->amount }}
                                            @php
                                                $totalsection +=$value->amount;
                                            @endphp
                                         </td>
                                        <td>
                                            <i class="bx bx-edit fs-22 edit-icon2 text-info"
                                                onclick='change_scetion({{ $value }})'
                                                data-id="{{ $value->id }}" data-type_id="6"></i>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td> لا يوجد فى الأقسام</td>
                                    </tr>
                                @endforelse
                            @elseif($data->type == 'Wheel')
                                @forelse ($data->sectionss as $key => $value)
                                    <tr>
                                        <td>{{ $value->store->name }} </td>
                                        <td>{{ $value->name }} </td>
                                        <td>{{ $value->source->name_arabic }} </td>
                                        <td>{{ $value->status->name }} </td>
                                        <td>{{ $value->part_quality->name }} </td>
                                        <td> {{ $value->amount }}
                                            @php
                                                $totalsection +=$value->amount;
                                            @endphp
                                        </td>
                                        <td>
                                            <i class="bx bx-edit fs-22 edit-icon2 text-info"
                                                onclick='change_scetion({{ $value }})'
                                                data-id="{{ $value->id }}" data-type_id="2"></i>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td> لا يوجد فى الأقسام</td>
                                    </tr>
                                @endforelse
                            @else
                            @endif

                        </table>
                    </div>
                </div>
                <div class="row ">

                    <div class="col-lg-6 card  ">
                        <h4 class="collapsible">البيانات الرئيسية</h4>
                        <table class="table-bordered cell-border table table-sm table-striped  fw-bold m-1  ">

                            <tr>
                                <td>الاسم</td>

                                <td>{{ $data->name }}</td>
                            </tr>
                            <tr>
                                <td>الحد المسموح به</td>
                                <td>{{ $data->limit_order }}</td>
                            </tr>

                            @if ($data->type == 'Part')
                                <tr>
                                    <td>الموديلات</td>
                                    <td><button class="btn" data-bs-toggle="modal" data-bs-target="#models"><i
                                                class="bx bx-info-circle fs-22"></i></button></td>
                                </tr>
                            @elseif($data->type == 'Kit')
                                <tr>
                                    <td>الموديلات</td>
                                    <td><button class="btn" data-bs-toggle="modal" data-bs-target="#models"><i
                                                class="bx bx-info-circle fs-22"></i></button></td>
                                </tr>
                            @elseif($data->type == 'Wheel')
                                <tr>
                                    <td>كاوتش</td>
                                    <td>{{ $data->wheel_model->name }} </td>
                                </tr>
                            @else
                            @endif
                            @if ($data->type == 'Part')
                                <tr>

                                     <td>{{($data->sub_group)? $data->sub_group->group->name :"No Group" }}</td>
                                    <td>{{ ($data->sub_group) ? $data->sub_group->name : " No Sub Group" }}</td>
                                </tr>
                            @elseif($data->type == 'Kit')
                                <tr>
                                    <td>Parts</td>
                                    <td><button class="btn" data-bs-toggle="modal" data-bs-target="#kitparts"><i
                                                class="bx bx-info-circle fs-22"></i></button></td>
                                </tr>
                            @elseif($data->type == 'Wheel')
                                <tr>
                                    <td>{{ $data->wheel_material->name }}</td>
                                    <td>{{ $data->wheel_dimension->dimension }}</td>

                                </tr>
                            @else
                            @endif


                        </table>
                    </div>

                    <div class="col-lg-6 card ">
                        <h4 class="collapsible">المواصفات </h4>
                        <table class="table-bordered cell-border table table-sm table-striped  fw-bold m-1  ">

                            @if ($data->type == 'Part')
                                @forelse ($data->part_details as $key => $value)
                                    <tr>
                                        <td>{{ $value->part_spec->name }} </td>
                                        <td> {{ $value->value }} </td>
                                        <td><i class="bx bx-edit fs-22" data-id="{{ $value->id }}"></i> </td>
                                        <td><i class="bx bx-x-circle fs-22 text-danger" data-id="{{ $value->id }}"></i>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td> لا يوجد مواصفات للصنف</td>
                                    </tr>
                                @endforelse
                            @elseif($data->type == 'Kit')
                                @forelse ($data->kit_details as $key => $value)
                                    <tr>
                                        <td>{{ $value->kit_spec->name }} </td>
                                        <td> {{ $value->value }} </td>
                                        <td><i class="bx bx-edit fs-22" data-id="{{ $value->id }}"></i> </td>
                                        <td><i class="bx bx-x-circle fs-22 text-danger" data-id="{{ $value->id }}"></i>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td> لا يوجد مواصفات للصنف</td>
                                    </tr>
                                @endforelse
                            @elseif($data->type == 'Wheel')
                                @forelse ($data->wheel_details as $key => $value)
                                    <tr>
                                        <td>{{ $value->wheel_spec->name }} </td>
                                        <td> {{ $value->value }} </td>
                                        <td><i class="bx bx-edit fs-22" data-id="{{ $value->id }}"></i> </td>
                                        <td><i class="bx bx-x-circle fs-22 text-danger" data-id="{{ $value->id }}"></i>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td> لا يوجد مواصفات للصنف</td>
                                    </tr>
                                @endforelse
                            @else
                            @endif


                        </table>
                    </div>
                </div>

                <div class="row card">
                    <h4 class="collapsible"> أرقام الصنف الاساسية والموردين</h4>
                    <div class="card-body">

                        <table class="table-bordered cell-border table table-sm table-striped  fw-bold m-1  ">
                            <thead style="background:#5fcee78a">
                                <tr>
                                    <th>المورد</th>
                                    <th> الرقم</th>
                                    <th>تعديل </th>
                                    <th>حذف</th>
                                </tr>
                            </thead>
                            @if ($data->type == 'Part')
                                @forelse ($data->part_numbers as $key => $value)
                                    <tr>
                                        <td>{{( $value->supplier)? $value->supplier->name : "OEM  need to edit" }} </td>
                                        <td> {{ $value->number }} </td>
                                        <td><i class="bx bx-edit fs-22" data-id="{{ $value->id }}"></i> </td>
                                        <td><i class="bx bx-x-circle fs-22 text-danger" data-id="{{ $value->id }}"></i>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td> لا يوجد أرقام للصنف</td>
                                    </tr>
                                @endforelse
                            @elseif($data->type == 'Kit')
                                @forelse ($data->kit_numbers as $key => $value)
                                    <tr>
                                        <td>{{( $value->supplier)? $value->supplier->name : "OEM need to edit " }} </td>
                                        <td> {{ $value->number }} </td>
                                        <td><i class="bx bx-edit fs-22" data-id="{{ $value->id }}"></i> </td>
                                        <td><i class="bx bx-x-circle fs-22 text-danger" data-id="{{ $value->id }}"></i>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td> لا يوجد أرقام للصنف</td>
                                    </tr>
                                @endforelse
                            @elseif($data->type == 'Wheel')
                                <tr>
                                    <td> {{ $data->wheel_dimension->dimension }}</td>
                                </tr>
                            @else
                            @endif



                        </table>
                    </div>


                </div>

                <div class="row card">
                    <h4 class="collapsible"> موجودات الصنف فى المخازن </h4>
                    <div class="card-body">

                        <table class="table-bordered cell-border table table-sm table-striped  fw-bold m-1  ">
                            <thead style="background:#5fcee78a">
                                <tr>
                                    <td> المخزن </td>
                                    <td> الكمية </td>
                                    <td> المنشأ </td>
                                    <td> الحالة </td>
                                    <td>الكفاءة</td>
                                    <td>تعديل الكمية</td>
                                </tr>
                            </thead>
                            @if ($data->type == 'Part')

                                @forelse ($data->allStores as $key => $value)
                                    <tr>
                                        <td rowspan="{{ count($value['data']) + 1 }}">{{ $value['store']->name }} </td>
                                        @forelse ($value['data'] as $key => $value_y)
                                    <tr>
                                        <td>{{ isset($value_y) ? $value_y['amount'] : 'لايوجد' }} </td>
                                        @php
                                             isset($value_y) ? $totalamount +=$value_y['amount'] : 0 ;
                                        @endphp
                                        <td>{{ isset($value_y) ? $value_y['stores_log']->all_parts[0]->source->name_arabic : 'لايوجد' }}
                                        </td>
                                        <td>{{ isset($value_y) ? $value_y['stores_log']->all_parts[0]->status->name : 'لايوجد' }}
                                        </td>
                                        <td>{{ isset($value_y) ? $value_y['stores_log']->all_parts[0]->part_quality->name : 'لايوجد' }}
                                        </td>
                                        <td><i class="bx bx-edit fs-22"
                                                data-id="{{ isset($value_y) ? $value_y['id'] : '0' }}"
                                                data-type_id="1"></i> </td>
                                    </tr>


                                @empty

                                    <td>لايوجد </td>
                                    <td>لايوجد </td>
                                    <td>لايوجد </td>
                                    <td>لايوجد </td>
                                    <td> </td>
                                @endforelse

                                </tr>
                            @empty
                                <tr>
                                    <td> لا يوجد أصناف </td>

                                </tr>
                            @endforelse
                        @elseif($data->type == 'Kit')
                            @forelse ($data->allStores as $key => $value)
                                <tr>
                                    <td rowspan="{{ count($value['data']) + 1 }}">{{ $value['store']->name }} </td>
                                    @forelse ($value['data'] as $key => $value_y)
                                <tr>
                                    <td>{{ isset($value_y) ? $value_y['amount'] : 'لايوجد' }} </td>
                                    @php
                                            isset($value_y) ? $totalamount +=$value_y['amount'] : 0 ;
                                    @endphp
                                    <td>{{ isset($value_y) ? $value_y['stores_log']->all_kits[0]->source->name_arabic : 'لايوجد' }}
                                    </td>
                                    <td>{{ isset($value_y) ? $value_y['stores_log']->all_kits[0]->status->name : 'لايوجد' }}
                                    </td>
                                    <td>{{ isset($value_y) ? $value_y['stores_log']->all_kits[0]->part_quality->name : 'لايوجد' }}
                                    </td>
                                    <td><i class="bx bx-edit fs-22"
                                            data-id="{{ isset($value_y) ? $value_y['id'] : '0' }}" data-type_id="6"></i>
                                    </td>
                                </tr>


                            @empty

                                <td>لايوجد </td>
                                <td>لايوجد </td>
                                <td>لايوجد </td>
                                <td>لايوجد </td>
                                <td> </td>
                            @endforelse

                            </tr>
                        @empty
                            <tr>
                                <td> لا يوجد أصناف </td>

                            </tr>
                            @endforelse
                        @elseif($data->type == 'Wheel')
                            @forelse ($data->allStores as $key => $value)
                                <tr>
                                    <td rowspan="{{ count($value['data']) + 1 }}">{{ $value['store']->name }} </td>
                                    @forelse ($value['data'] as $key => $value_y)
                                <tr>
                                    <td>{{ isset($value_y) ? $value_y['amount'] : 'لايوجد' }} </td>
                                    @php
                                            isset($value_y) ? $totalamount +=$value_y['amount'] : 0 ;
                                    @endphp
                                    <td>{{ isset($value_y) ? $value_y['stores_log']->all_wheels[0]->source->name_arabic : 'لايوجد' }}
                                    </td>
                                    <td>{{ isset($value_y) ? $value_y['stores_log']->all_wheels[0]->status->name : 'لايوجد' }}
                                    </td>
                                    <td>{{ isset($value_y) ? $value_y['stores_log']->all_wheels[0]->part_quality->name : 'لايوجد' }}
                                    </td>
                                    <td><i class="bx bx-edit fs-22"
                                            data-id="{{ isset($value_y) ? $value_y['id'] : '0' }}" data-type_id="2"></i>
                                    </td>
                                </tr>


                            @empty

                                <td>لايوجد </td>
                                <td>لايوجد </td>
                                <td>لايوجد </td>
                                <td>لايوجد </td>
                                <td> </td>
                            @endforelse

                            </tr>
                        @empty
                            <tr>
                                <td> لا يوجد أصناف </td>

                            </tr>
                            @endforelse
                        @else
                            @endif

                        </table>
                    </div>
                </div>

                <div class="row card">
                    <h4 class="collapsible"> هل يوجد الصنف فى كيت </h4>
                    <div class="card-body">

                        <table class="table-bordered cell-border table table-sm table-striped  fw-bold m-1  ">
                            <thead style="background:#5fcee78a">
                                <tr>
                                    <td> الاسم </td>
                                    <td> المنشأ </td>
                                    <td> الحالة </td>
                                    <td>الكفاءة</td>
                                     <td>كيت</td>
                                    <td>صنف</td>
                                    <td>عرض </td>

                                </tr>
                            </thead>
                            @if ($data->type == 'Part')
                            <span>{{ $data->kitcount }}</span>
                                @forelse($data->allkits as $key => $valuey)
                                    @foreach($valuey->all_kits as $key => $valuem)
                                    <form action="#" method="POST">
                                    @csrf
                                    @method('POST')
                                        <tr class="">
                                            <input type="hidden"  value="{{ $valuem->kit->id }}" name="kitid">
                                            <input type="hidden"  value="{{ $valuem->id }}" name="allkitid">
                                            <input type="hidden"  value="{{ $valuem->order_supplier_id }}" name="kit_order_supplier_id">
                                            <input type="hidden" value="{{ $data->id }}" name="partid">
                                            <input type="hidden" value="{{ $valuem->source->id }}" name="sourceid">
                                            <input type="hidden" value="{{ $valuem->status->id }}" name="statusid">
                                            <input type="hidden" value="{{ $valuem->part_quality->id }}" name="qualityid">
                                            <input type="hidden" value="{{ $valuey->amount }}" name="partAmount">
                                            <input type="hidden" value="{{ $valuem->amount }}" name="kitAmount">
                                            <td>{{ $valuem->kit->name }} </td>
                                            <td>{{ $valuem->source->name_arabic }} </td>
                                            <td>{{ $valuem->status->name }} </td>
                                            <td>{{ $valuem->part_quality->name }} </td>
                                            <td> {{ $valuem->amount }} </td>
                                            <td> {{ $valuey->amount }}
                                                @php
                                                    $totalkit +=($valuem->amount*$valuey->amount);
                                                @endphp
                                            </td>
                                            <td>
                                                <a href="/partDetails/6/{{ $valuem->kit->id }}"><i
                                                        class="bx bx-info-circle fs-22"></i></a>
                                            </td>

                                        </tr>
                                    </form>
                                    @endforeach


                                @empty
                                <tr>
                                    <td> لا يوجد  كيت</td>
                                </tr>
                                @endforelse
                                @forelse ($data->kitcollection as $key => $valuex)
                                    <tr class="">
                                        <td>{{ $valuex->name }} </td>
                                        <td>{{ $valuex->source->name_arabic }} </td>
                                        <td>{{ $valuex->status->name }} </td>
                                        <td>{{ $valuex->part_quality->name }} </td>
                                        <td> {{ $valuex->amount }} </td>
                                        <td><a href="/partDetails/6/{{ $valuex->id }}"><i
                                                    class="bx bx-info-circle fs-22"></i></a></td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td> لا يوجد تجميع للصنف</td>
                                    </tr>
                                @endforelse
                            @elseif($data->type == 'Kit')

                            @elseif($data->type == 'Wheel')
                            @else
                            @endif

                        </table>
                    </div>
                </div>
                <div class="row card">
                    <h4 class="collapsible"> تسعير الصنف فى المخازن</h4>
                    <div class="card-body">

                        <table class="table-bordered cell-border table table-sm table-striped  fw-bold m-1  ">
                            <thead style="background:#5fcee78a">
                                <tr>
                                    <td> المنشأ </td>
                                    <td> الحالة </td>
                                    <td>الكفاءة</td>
                                    <?php
                                    $saleType_arr = [];
                                    if (isset($data->pricelist[0])) {
                                        $saleType_arr = $data->pricelist[0]->sale_types;
                                    } else {
                                        $saleType_arr = [];
                                    }
                                    ?>
                                    @foreach ($saleType_arr as $key => $valuex)
                                        <td>
                                            {{ $valuex->sale_typex->type }}
                                        </td>
                                    @endforeach
                                    <td>تعديل الكمية</td>
                                </tr>
                            </thead>
                            @if ($data->type == 'Part')
                                @forelse ($data->pricelist as $key => $value)
                                    <tr>
                                        <td> {{ $value->source->name_arabic }} </td>
                                        <td> {{ $value->status->name }} </td>
                                        <td> {{ $value->part_quality->name }} </td>
                                        <?php
                                        $saleType_arr_val = [];
                                        if (isset($value->sale_types)) {
                                            $saleType_arr_val = $value->sale_types;
                                        } else {
                                            $saleType_arr_val = [];
                                        }
                                        ?>
                                        @foreach ($saleType_arr_val as $key => $valuex)
                                            <td>
                                                {{-- {{ $valuex->price }} --}}
                                                <span class="editable" data-id="{{ $valuex->id }}" data-field="price">{{ $valuex->price }}</span>
                                                <input type="text" class="edit-input" data-id="{{ $valuex->id }}" data-field="{{$valuex->sale_typex->id}}" value="{{ $valuex->price }}"
                                                    style="display:none;">

                                            </td>
                                        @endforeach
                                        <td>
                                            <i class="bx bx-edit fs-22 edit-icon" data-id="{{ $value->id }}"
                                                data-type_id="1"></i>
                                            <i class="bx bx-save fs-22 save-icon" data-id="{{ $value->id }}"
                                                data-type_id="1" style="display:none;"></i>
                                        </td>
                                        {{-- <td><i class="bx bx-edit fs-22 edit-icon" data-id="{{ $value->id }}" data-type_id="1"></i></td>
                                        <td><i class="bx bx-save fs-22 save-icon" data-id="{{ $value->id }}" style="display:none;" data-type_id="1"></i></td> --}}
                                        {{-- <td><i class="bx bx-edit fs-22" data-id="{{ $value->id }}"></i> </td> --}}
                                    </tr>
                                @empty
                                    <tr>
                                        <td> لا يوجد تسعييرة للصنف</td>
                                    </tr>
                                @endforelse
                            @elseif($data->type == 'Kit')
                                @forelse ($data->pricelist as $key => $value)
                                    <tr>
                                        <td> {{ $value->source->name_arabic }} </td>
                                        <td> {{ $value->status->name }} </td>
                                        <td> {{ $value->part_quality->name }} </td>
                                        @foreach ($value->sale_types as $key => $valuex)
                                            <td>
                                                {{ $valuex->price }}
                                            </td>
                                        @endforeach

                                        <td><i class="bx bx-edit fs-22" data-id="{{ $value->id }}"></i> </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td> لا يوجد تسعييرة للصنف</td>
                                    </tr>
                                @endforelse
                            @elseif($data->type == 'Wheel')
                                @forelse ($data->pricelist as $key => $value)
                                    <tr>
                                        <td> {{ $value->source->name_arabic }} </td>
                                        <td> {{ $value->status->name }} </td>
                                        <td> {{ $value->part_quality->name }} </td>
                                        @foreach ($value->sale_types as $key => $valuex)
                                            <td>
                                                {{ $valuex->price }}
                                            </td>
                                        @endforeach

                                        <td><i class="bx bx-edit fs-22" data-id="{{ $value->id }}"></i> </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td> لا يوجد تسعييرة للصنف</td>
                                    </tr>
                                @endforelse
                            @else
                            @endif

                        </table>
                    </div>
                </div>
                <div class="row card">
                    <h4 class="collapsible"> موجودات هذا الصنف فى عروض الاسعار</h4>
                    <div class="card-body table-responsive-lg">

                        <table class="table-bordered cell-border table table-sm table-striped  fw-bold m-1  ">
                            <thead style="background:#5fcee78a">
                                <tr>
                                    <td>رقم العرض </td>
                                    <td> لصالح </td>
                                    <td> بتاريخ </td>
                                    <td> حالة العرض </td>
                                    <td> الصنف </td>
                                    <td> المنشاء </td>
                                    <td> الحالة </td>
                                    <td> الكفاءة </td>
                                    <td> الكمية </td>
                                    <td> السعر </td>
                                    <td> صالح لتاريخ </td>
                                    <td> تعديل </td>
                                    <td> حذف </td>
                                </tr>
                            </thead>
                            @if ($data->type == 'Part')
                                @forelse ($data->presaleOrder as $key => $value)
                                    <tr>
                                        <td> {{ $value->presale_order->id }} </td>
                                        <td> {{ $value->presale_order->client->name }} </td>
                                        <td> {{ explode(' ', $value->presale_order->created_at)[0] }} </td>
                                        @php
                                            $statusMappings = [
                                                0 => 'جاري التجهيز',
                                                1 => 'تم تجهيزها',
                                                2 => 'تم التسليم',
                                                3 => 'تم التحويل لفاتورة',
                                                4 => 'تم الالغاء',
                                            ];
                                        @endphp
                                        <td> {{ $statusMappings[$value->presale_order->flag] ?? 'Unknown status' }} </td>

                                        <td> {{ $value->part->name }} </td>
                                        <td> {{ $value->source->name_arabic }} </td>
                                        <td> {{ $value->status->name }} </td>
                                        <td> {{ $value->quality->name }} </td>
                                        <td> {{ $value->amount }} </td>
                                        <td> {{ $value->price }} </td>
                                        <td> {{ $value->presale_order->due_date ? explode(' ', $value->presale_order->due_date)[0] : 'غير محدد' }}
                                        </td>


                                        <td><i class="bx bx-edit fs-22" data-id="{{ $value->id }}"></i> </td>
                                        <td><i class="bx bx-x-circle fs-22 text-danger"
                                                data-id="{{ $value->id }}"></i></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td> لا يوجد فى عروض اسعار</td>
                                    </tr>
                                @endforelse
                            @elseif($data->type == 'Kit')
                                @forelse ($data->presaleOrder as $key => $value)
                                    <tr>
                                        <td> {{ $value->presale_order->id }} </td>
                                        <td> {{ $value->presale_order->client->name }} </td>
                                        <td> {{ explode(' ', $value->presale_order->created_at)[0] }} </td>
                                        @php
                                            $statusMappings = [
                                                0 => 'جاري التجهيز',
                                                1 => 'تم تجهيزها',
                                                2 => 'تم التسليم',
                                                3 => 'تم التحويل لفاتورة',
                                                4 => 'تم الالغاء',
                                            ];
                                        @endphp
                                        <td> {{ $statusMappings[$value->presale_order->flag] ?? 'Unknown status' }} </td>

                                        <td> {{ $value->kit->name }} </td>
                                        <td> {{ $value->source->name_arabic }} </td>
                                        <td> {{ $value->status->name }} </td>
                                        <td> {{ $value->quality->name }} </td>
                                        <td> {{ $value->amount }} </td>
                                        <td> {{ $value->price }} </td>
                                        <td> {{ $value->presale_order->due_date ? explode(' ', $value->presale_order->due_date)[0] : 'غير محدد' }}
                                        </td>


                                        <td><i class="bx bx-edit fs-22" data-id="{{ $value->id }}"></i> </td>
                                        <td><i class="bx bx-x-circle fs-22 text-danger"
                                                data-id="{{ $value->id }}"></i></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td> لا يوجد فى عروض اسعار</td>
                                    </tr>
                                @endforelse
                            @elseif($data->type == 'Wheel')
                                @forelse ($data->presaleOrder as $key => $value)
                                    <tr>
                                        <td> {{ $value->presale_order->id }} </td>
                                        <td> {{ $value->presale_order->client->name }} </td>
                                        <td> {{ explode(' ', $value->presale_order->created_at)[0] }} </td>
                                        @php
                                            $statusMappings = [
                                                0 => 'جاري التجهيز',
                                                1 => 'تم تجهيزها',
                                                2 => 'تم التسليم',
                                                3 => 'تم التحويل لفاتورة',
                                                4 => 'تم الالغاء',
                                            ];
                                        @endphp
                                        <td> {{ $statusMappings[$value->presale_order->flag] ?? 'Unknown status' }} </td>

                                        <td> {{ $value->wheel->name }} </td>
                                        <td> {{ $value->source->name_arabic }} </td>
                                        <td> {{ $value->status->name }} </td>
                                        <td> {{ $value->quality->name }} </td>
                                        <td> {{ $value->amount }} </td>
                                        <td> {{ $value->price }} </td>
                                        <td> {{ $value->presale_order->due_date ? explode(' ', $value->presale_order->due_date)[0] : 'غير محدد' }}
                                        </td>


                                        <td><i class="bx bx-edit fs-22" data-id="{{ $value->id }}"></i> </td>
                                        <td><i class="bx bx-x-circle fs-22 text-danger"
                                                data-id="{{ $value->id }}"></i></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td> لا يوجد فى عروض اسعار</td>
                                    </tr>
                                @endforelse
                            @else
                            @endif


                        </table>
                    </div>
                </div>
                <div class="row card">
                    <h4 class="collapsible">  البيع</h4>
                    <div class="card-body table-responsive-lg">

                        <table class="table-bordered cell-border table table-sm table-striped  fw-bold m-1  ">
                            <thead style="background:#5fcee78a">
                                <tr>
                                   <td>رقم الفاتورة</td>
                                   <td>التاريخ</td>
                                   <td>الصنف</td>
                                   <td> المنشأ </td>
                                   <td> الحالة </td>
                                   <td> الكفاءة </td>
                                   <td> العميل </td>
                                   <td> الكمية </td>
                                </tr>
                            </thead>
                            @foreach($invoices as $key => $invoice)
                                <tr>
                                    <td><a href="/printInvoice/{{ $invoice->invoice->id }}">{{ $invoice->invoice->id }}</a></td>
                                    <td>{{ $invoice->invoice->date }}</td>
                                    @if ($data->type == 'Part')
                                        <td>{{ $invoice->part->name }}</td>

                                    @elseif($data->type == 'Kit')
                                        <td>{{ $invoice->kit->name }}</td>
                                    @elseif($data->type == 'Wheel')
                                        <td>{{ $invoice->wheel->name }}</td>

                                    @else
                                    @endif
                                    
                                    <td>{{ $invoice->source->name_arabic }}</td>
                                    <td>{{ $invoice->status->name }}</td>
                                    <td>{{ $invoice->part_quality->name }}</td>
                                    <td>{{ $invoice->invoice->client->name }}</td>
                                    <td>{{ $invoice->amount }}
                                        @php
                                            $totalsell +=$invoice->amount;
                                        @endphp
                                    </td>
                                </tr>
                                @foreach($invoice->invoice_items_sections as $key => $x)
                                    <tr class="bg-soft-danger">
                                        <td>خروج من قسم</td>
                                        <td>{{$x->store_structure->name}}</td>
                                        <td>الكمية</td>
                                        <td>{{$x->amount}}</td>

                                    </tr>
                                @endforeach

                                @foreach($invoice->invoice->refund_invoices as $key => $xx)
                                    <tr class="bg-soft-success">
                                        <td>تاريخ الاسترجاع</td>
                                        <td>{{$xx->date}}</td>
                                        <td>الكمية</td>
                                        <td>{{$xx->r_amount}}</td>
                                        @php
                                            $totalrefund +=$xx->r_amount;
                                        @endphp
                                    </tr>
                                @endforeach
                            @endforeach


                        </table>
                    </div>
                </div>


            </div>

            <div class="row">
                <div class="col-lg-4">
                    <table>
                        <tr>
                            <td>مشتريات</td>
                            <td>{{ $totalbuy }}</td>
                        </tr>
                        <tr>
                            <td>مبيعات</td>
                            <td>{{ $totalsell }}</td>
                        </tr>
                        <tr>
                            <td>مردودات</td>
                            <td>{{ $totalrefund }}</td>
                        </tr>
                        <tr>
                            <td>في كيت</td>
                            <td>{{ $totalkit }}</td>
                        </tr>
                        <tr>
                            <td>متبقي</td>
                            <td>{{ $totalamount }}</td>
                        </tr>
                        <tr>
                            <td>موجود في أقسام</td>
                            <td>{{ $totalsection }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-4"></div>
                <div class="col-lg-4">
                    
                </div>
            </div>


            <div class="modal fade" id="models" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="modelsLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modelsLabel">الموديلات</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body text-center">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3>{{ $data->name }}</h3>
                                    <table id="modelDatatable" class="table table-sm table-striped">
                                        <thead style="background:#5fcee78a">
                                            <tr>
                                                <th>brand</th>
                                                <th>series</th>
                                                <th>model</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($data->type == 'Part')

                                                @foreach ($data->part_models as $key => $value)
                                                    <tr>
                                                        <td>{{ $value->series->model->brand->name }}</td>
                                                        <td>{{ $value->series->name }} </td>
                                                        <td> {{ $value->series->model->name }} </td>
                                                    </tr>
                                                @endforeach
                                            @elseif($data->type == 'Kit')
                                                @foreach ($data->kit_models as $key => $value)
                                                    <tr>
                                                        <td>{{ $value->series->model->brand->name }}</td>
                                                        <td>{{ $value->series->name }} </td>
                                                        <td> {{ $value->series->model->name }} </td>
                                                    </tr>
                                                @endforeach
                                            @elseif($data->type == 'Wheel')
                                                <tr>
                                                    <td>كاوتش</td>
                                                    <td>{{ $data->wheel_model->name }} </td>
                                                    <td></td>
                                                </tr>
                                            @else
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>
                        <div class="modal-footer">

                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="kitparts" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="modelsLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="kitpartsLabel">الكيت</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body text-center">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3>{{ $data->name }}</h3>
                                    <table id="kitpartsDatatable" class="table table-sm table-striped">
                                        <thead style="background:#5fcee78a">
                                            <tr>

                                                <th>Part</th>
                                                <th>Amounts</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($data->type == 'Kit')
                                                @foreach ($data->kit_parts as $key => $value)
                                                    <tr>
                                                        <td>{{ $value->part->name }} </td>
                                                        <td> {{ $value->amount }} </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>
                        <div class="modal-footer">

                        </div>

                    </div>
                </div>
            </div>

             <div class="modal fade" id="correctkitsection" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="modelsLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="kitpartsLabel">الكيت</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body text-center">

                            <div class="row">
                                <form action="/correctKit" method="POST">
                                    @csrf
                                    @method('POST')
                                    <div class="col-lg-12">

                                                <input type="hidden" value="{{ $data->id }}" name="partid">
                                                <input type="hidden" value="" name="sourceid">
                                                <input type="hidden" value="" name="statusid">
                                                <input type="hidden" value="" name="qualityid">
                                                <input type="hidden" value="" name="sectionAmounts">

                                        <h2>سيتم مسح الكمية من القسم لانه تم التجميع في كيت ؟</h2>
                                        <select class="form-control" name="allkitId" id="correctkitsectionSlct">
                                            <option value="">إختر</option>
                                            @if ($data->type == 'Part')
                                            @forelse($data->allkits as $key => $valuey)
                                                @foreach($valuey->all_kits as $key => $valuem)
                                                    <option value="{{ $valuem->id }} ">
                                                        {{ $valuem->kit->name }} / {{ $valuem->source->name_arabic }} / {{ $valuem->status->name }} / {{ $valuem->part_quality->name }} / {{ $valuem->amount }}
                                                    </option>
                                                @endforeach
                                            @empty
                                                <option value="">لا يوجد</option>
                                            @endforelse
                                             @endif
                                        </select>
                                        <button type="submit" class="btn btn-success">Yes</button>
                                        <button type="button" class="btn btn-danger">No</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changeSectionMDL" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تغيير مكان القطعة </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/sendfromSection" method="POST">
                        @csrf
                        <input type="hidden" name="store_id" id="store_id" value="0">
                        {{-- <input type="hidden" name="order_supplier_id" id="order_supplier_id" value="0"> --}}
                        <input type="hidden" name="type_id" id="type_id" value="0">
                        <input type="hidden" name="part_id" id="part_id" value="0">
                        <input type="hidden" name="source_id" id="source_id" value="0">
                        <input type="hidden" name="quality_id" id="quality_id" value="0">
                        <input type="hidden" name="status_id" id="status_id" value="0">








                        <div class="row">
                            <div class="col lg 6">
                                <label for="itemName" class="col-form-label">إسم القطعة</label>
                                <input type="text" class="form-control" name="itemName" id="itemName">
                                <input type="hidden" name="section_id" id="sectionId" value="0">
                                <input type="hidden" class="form-control" name="item_allp_id" id="item_allp_id">
                            </div>
                        </div>
                        <hr>
                        <div class="row">

                            <div class="col lg 6">
                                <label for="old_amount" class="col-form-label">الكمية القديمة </label>
                                <input type="text" class="form-control" name="old_amount" id="old_amount">
                                <input type="hidden" class="form-control" name="old_amount_txt" id="old_amount_txt">
                            </div>
                            <div class="col lg 6">
                                <label for="old_section" class="col-form-label">المكان الحالى </label>
                                <input type="text" class="form-control" name="old_section" id="old_section">
                            </div>

                        </div>
                        <div class="row">

                            <div class="col lg 6">
                                <label for="new_amount" class="col-form-label">الكمية الجديدة </label>
                                <input type="text" class="form-control" name="new_amount" id="new_amount">
                            </div>
                            <div class="col lg 6">
                                <label for="old_sectionx" class="col-form-label">المكان الجديد </label>
                                {{-- <input type="text" class="form-control" name="new_section" id="new_section"> --}}
                                <select name="new_section" id="new_section">
                                    <option value="0" disabled selected>اختر</option>
                                    {{-- @foreach ($store_structure as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach --}}
                                </select>
                            </div>

                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">save </button>
                </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $("#modelDatatable").DataTable({
            dom: '<"dt-buttons"Bf><"clear">lirtp',
            buttons: [
                "colvis",
                "copyHtml5",
                "csvHtml5",
                "excelHtml5",
                "pdfHtml5",
                {
                    extend: 'print',

                },
            ],
        });
        $("#kitpartsDatatable").DataTable({
            dom: '<"dt-buttons"Bf><"clear">lirtp',
            buttons: [
                "colvis",
                "copyHtml5",
                "csvHtml5",
                "excelHtml5",
                "pdfHtml5",
                {
                    extend: 'print',

                },
            ],
        });
    </script>
    <script>
        var updatedValues = {};
        $(document).ready(function() {
            $('.edit-icon').on('click', function() {
                var updatedValues = {};
                var id = $(this).data('id');
                var editableFields = $('.editable[data-id="' + id + '"]');
                var editInputs = $('.edit-input[data-id="' + id + '"]');
                var editIcon = $(this);
                var saveIcon = $(this).next('.save-icon');

                // Show input fields and hide editable spans
                editableFields.hide();
                editInputs.show().first().focus(); // Focus on the first input field

                // Toggle visibility of edit and save icons
                editIcon.hide();
                saveIcon.show();

                saveIcon.on('click', function() {
                    // editInputs.each(function () {
                    //     var field = $(this).data('field');
                    //     var value = $(this).val().trim();
                    //     var type_id =$(this).data('type_id');
                    //     updatedValues[field] = value;
                    // });
                    var updatedValues = {};
                    var selectedText = {};
                    var type_id = $(this).data('type_id');

                    editInputs.each(function() {
                        var field = $(this).data('field');
                        var value = $(this).val().trim();

                        updatedValues[field] = value;

                        // Store the text of the selected option if it's a dropdown
                        if ($(this).is('select')) {
                            selectedText[field] = $(this).find('option:selected').text();
                        } else {
                            selectedText[field] = value;
                        }
                    });


                    var data = {
                        id: id,
                        values: updatedValues,
                        type_id: type_id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };

                    $.ajax({
                        url: '/updatepart',
                        type: 'GET',
                        contentType: 'application/json',
                        data: data,
                        success: function(response) {
                            if (response.success) {
                                editableFields.each(function() {
                                    var field = $(this).data('field');
                                    // Use selectedText instead of updatedValues for dropdowns
                                    $(this).text(selectedText[field]);

                                    // var field = $(this).data('field');
                                    // $(this).text(updatedValues[field]);
                                });
                                Swal.fire(response.message);



                            } else {
                                Swal.fire({
                                    text: response.message,
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary",
                                    }

                                });
                                // alert('Update failed: ' + (response.message || 'Unknown error'));
                            }
                            editInputs.hide();
                            editableFields.show();
                            editIcon.show();
                            saveIcon.hide();
                        },
                        error: function(error) {
                            console.error('Error:', error);
                        }
                    });
                });
            });

            $('#new_section').select2({
                tags: true,
                dropdownParent: $("#changeSectionMDL")
            });

             $('#correctkitsection').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var id = button.data('id');
                var part_id = button.data('part_id');
                var status = button.data('status');
                var source = button.data('source');
                var quality = button.data('quality');
                var sectionAmount = button.data('amount');


                // Update the modal's content
                var modal =  $('#correctkitsection');

                modal.find('input[name="partid"]').val(part_id);
                modal.find('input[name="sourceid"]').val(source);
                modal.find('input[name="statusid"]').val(status);
                modal.find('input[name="qualityid"]').val(quality);
                modal.find('input[name="sectionAmounts"]').val(sectionAmount);


            });
        });


        function change_scetion(data) {

            $('#store_id').val(data.store_id);

            $.ajax({
                url: '/storeStructure/' + data.store_id,
                type: 'GET',
                contentType: 'application/json',
                data: data,
                success: function(response) {
                    if (response) {
                        $("#new_section").empty();
                        response.forEach(element => {
                            $("#new_section").append(
                                `<option value="${element.id}">${element.name}</option>`)
                        });


                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });

            // $('#order_supplier_id').val(data.order_supplier_id);
            $('#type_id').val(data.type_id);
            $('#part_id').val(data.part_id);
            $('#source_id').val(data.source_id);
            $('#status_id').val(data.status_id);
            $('#quality_id').val(data.quality_id);

            if(data.type_id == 1){
                $('#itemName').val(data['part'].name);
            }else if(data.type_id == 2){
                $('#itemName').val(data['wheel'].name);
            }else if(data.type_id == 6){
                $('#itemName').val(data['kit'].name);
            }else{
                $('#itemName').val("error");
            }


            $('#old_amount').val(data['amount']);
            $('#old_amount_txt').val(data['amount']);
            $('#old_section').val(data.name);

            var section_id = $("#sectionSlct").val();
            var section_name = $("#sectionSlct  option:selected").text();
            $('#old_section').val(data.name);
            $('#sectionId').val(data.id);
            // $('#old_amount').val(data.amount);

            $('#changeSectionMDL').modal('show');
            console.log(data);
        }
    </script>
    <script>
        var coll = document.getElementsByClassName("collapsible");
        var i;

        for (i = 0; i < coll.length; i++) {
          coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.display === "block") {
              content.style.display = "none";
            } else {
              content.style.display = "block";
            }
          });
        }
    </script>





@endsection
