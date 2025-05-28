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
                                            <span class="editable" title="{{ $value->source->id }}" data-id="{{ $value->id }}"
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
                                                title="{{ $value->status->id }}"
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
                                            <span class="editable" data-id="{{ $value->id }}" title="{{ $value->part_quality->id }}"
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
                                        <?php 
                                        $ratioamount=getSmallUnit($value->part->big_unit,$value->part->small_unit);
                                        ?>

                                        <td onclick="getStoreAmount('1','{{ $value->part_id }}','{{ $value->source->id }}','{{ $value->status->id }}','{{ $value->part_quality->id }}','{{ $value->order_supplier_id }}')">
                                        {{ $value->amount /$ratioamount}}/ {{ $value->part->bigunit->name }}
                                        @php
                                                $totalbuy +=$value->amount / $ratioamount;
                                            @endphp
                                            {{-- <span class="editable" data-id="{{ $value->id }}" data-field="amount">{{ $value->amount }}</span>
                                                <input type="number" class="edit-input" data-id="{{ $value->id }}" data-field="amount" value="{{ $value->amount }}" style="display:none;"> --}}
                                            {{-- <i class="bx bx-edit fs-22 edit-icon" data-id="{{ $value->id }}" data-type_id="1"></i>
                                                <i class="bx bx-save fs-22 save-icon" data-id="{{ $value->id }}" data-type_id="1" style="display:none;"></i> --}}
                                        </td>
                                        <td> {{ $value->remain_amount /$ratioamount }} / {{ $value->part->bigunit->name }}</td>
                                        <td> {{ $value->order_supplier->supplier->name }} </td>
                                        <td> {{ $value->buy_costing }} </td>
                                        <td> {{ $value->buy_price  *$ratioamount }} / <a target="_blank" href="/show_currency/{{ $value->order_supplier->currency_type->id }}">{{ $value->order_supplier->currency_type->name }} </a>

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
                                            <span class="editable" data-id="{{ $value->id }}"  title="{{ $value->source->id }}"
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
                                            <span class="editable" data-id="{{ $value->id }}" title="{{ $value->status->id }}"
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
                                            <span class="editable" data-id="{{ $value->id }}" title="{{ $value->part_quality->id }}"
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
                                        <td onclick="getStoreAmount('6','{{ $value->part_id }}','{{ $value->source->id }}','{{ $value->status->id }}','{{ $value->part_quality->id }}','{{ $value->order_supplier_id }}')"> {{ $value->amount }}
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
                                        <td onclick="getStoreAmount('2','{{ $value->part_id }}','{{ $value->source->id }}','{{ $value->status->id }}','{{ $value->part_quality->id }}','{{ $value->order_supplier_id }}')">
                                            {{ $value->amount }} </td>
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
                                        <?php 
                                        $ratioamount=getSmallUnit($value->part->big_unit,$value->part->small_unit);
                                        ?>
                                        <td> {{ $value->amount / $ratioamount}} / {{ $value->part->bigunit->name }}
                                            @php
                                                $totalsection +=$value->amount / $ratioamount;
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
                                <?php 
                                $ratioamount=getSmallUnit($data->big_unit,$data->small_unit);
                                ?>
                                <td >الحد المسموح به</td>
                                <td>
                                    <div> {{ $data->limit_order  /$ratioamount}} /{{$data->bigunit->name}} </div>
                                    <div> {{ $data->limit_order }} /{{$data->smallunit->name}} </div>
                                </td>
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

                                    <?php 
                                        $ratioamount=getSmallUnit($value_y->stores_log->all_parts[0]->part->big_unit,$value_y->stores_log->all_parts[0]->part->small_unit);
                                        ?> 
                                        <td>{{ isset($value_y) ? $value_y['amount'] / $ratioamount .'/'.$value_y->stores_log->all_parts[0]->part->bigunit->name : 'لايوجد' }} </td>
                                        @php
                                                isset($value_y) ? $totalamount +=$value_y['amount']  / $ratioamount: 0 ;
                                        @endphp
                                        <td>{{ isset($value_y) ? $value_y['stores_log']->all_parts[0]->source->name_arabic : 'لايوجد' }}
                                        </td>
                                        <td>{{ isset($value_y) ? $value_y['stores_log']->all_parts[0]->status->name : 'لايوجد' }}
                                        </td>
                                        <td>{{ isset($value_y) ? $value_y['stores_log']->all_parts[0]->part_quality->name : 'لايوجد' }}
                                        </td>
                                        <?php 
                                        $ratioamount=getSmallUnit($value_y->stores_log->all_parts[0]->part->big_unit,$value_y->stores_log->all_parts[0]->part->small_unit);
                                        ?>
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
                                        $ratioamount=getSmallUnit($value->part->big_unit,$value->part->small_unit);
                                        ?>
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
                                                <span class="editable" data-id="{{ $valuex->id }}" data-field="price">{{ $valuex->price * $ratioamount}}</span>
                                                <input type="text" class="edit-input" data-id="{{ $valuex->id }}" data-field="{{$valuex->sale_typex->id}}" value="{{ $valuex->price *$ratioamount }}"
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
                                        <?php 
                                        $ratioamount=getSmallUnit($value->part->big_unit,$value->part->small_unit);
                                        ?>
                                        <td> {{ $value->part->name }} </td>
                                        <td> {{ $value->source->name_arabic }} </td>
                                        <td> {{ $value->status->name }} </td>
                                        <td> {{ $value->quality->name }} </td>
                                        <td> {{ $value->amount /$ratioamount }} / {{$value->part->bigunit->name}} </td>
                                        <td> {{ $value->price   * $ratioamount}} </td>
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
                                    <?php 
                                    $ratioamount=getSmallUnit($value->part->big_unit,$value->part->small_unit);
                                    ?>
                                    <td>{{ $invoice->amount / $ratioamount}} / {{$value->part->bigunit->name}}
                                        @php
                                            $totalsell +=$invoice->amount / $ratioamount ;
                                        @endphp
                                    </td>
                                </tr>
                                @foreach($invoice->invoice_items_sections as $key => $x)
                                    <tr class="bg-soft-danger">
                                        <td>خروج من قسم</td>
                                        <td>{{$x->store_structure->name}}</td>
                                        <td>الكمية</td>
                                        <?php 
                                        $ratioamount=getSmallUnit($x->invoice_item->part->big_unit,$x->invoice_item->part->small_unit);
                                        ?>
                                        <td>{{ $x->amount / $ratioamount}} / {{$x->invoice_item->part->bigunit->name}}</td>
                                        <td>{{$x->store_structure->store->name}}</td>
                                    </tr>
                                    
                                     
                                @endforeach

                                @foreach($invoice->invoice_items_refund as $key => $xx)
                                
                                    <tr class="bg-soft-success">
                                        <td>تاريخ الاسترجاع</td>
                                        <td>{{$xx->date}}</td>
                                        <td>الكمية</td>    <?php 
                                        $ratioamount=getSmallUnit($xx->invitem->part->big_unit,$xx->invitem->part->small_unit);
                                        ?>
                                        <td>{{$xx->r_amount /$ratioamount}} / {{$xx->invitem->part->bigunit->name }}</td>
                                        @php
                                            $totalrefund +=$xx->r_amount /$ratioamount;
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
                
                        @if($totalbuy - $totalsell + $totalrefund - $totalkit == $totalamount )
                           <div class="bg-success border border-2 border-secondary col-lg-4">
                        @else
                           <div class="border border-2 border-secondary col-lg-4 bg-danger">
                        @endif
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
                                        <button class="btn btn-success">Yes</button>
                                        <button class="btn btn-danger">No</button>
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



    <div class="modal fade" id="getStoreAmountMDL" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> بيانات الصنف كميات  </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <h1>فواتير الشراء <span class="text-bg-warning text-dark px-2 rounded-3" id="allpartCount">0</span></h1>
                            <table id="allparttable" class="table">
                                <thead class="table-success">
                                    <tr>
                                        <td>allpart_id</td>
                                        <td>part_id</td>
                                        <td>source_id</td>
                                        <td>status_id</td>
                                        <td>quality_id</td>
                                        <td>allpart_amount</td>
                                        <td>allpart_remain_amount</td>
                                        <td>order_supplier_id</td>
                                        <td>transaction_id</td>
                                        <td>order_supplier_supplier_id</td>
                                        <td>order_supplier_currency_id</td>
                                        <td>order_supplier_total_price</td>
                                        <td>order_supplier_bank_account</td>
                                        <td>order_supplier_pricebeforeTax</td>
                                        <td>order_supplier_coast</td>
                                        <td>order_supplier_taxInvolved_flag</td>
                                        <td>order_supplier_taxkasmInvolved_flag</td>
                                        <td>order_supplier_user_id</td>


                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <h2>المخازن <span class="text-bg-warning text-dark px-2 rounded-3" id="allstoreCount">0</span></h2>
                            <table id="storetable" class="table">
                                <thead class="table-success">
                                    <tr>
                                        <td>id</td>
                                        <td>part_id</td>
                                        <td>supplier_order_id</td>
                                        <td>amount</td>
                                        <td>store_log_id</td>
                                        <td>type_id</td>
                                        <td>date</td>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>

                            <h2>فواتير البيع <span class="text-bg-warning text-dark px-2 rounded-3" id="allinvoiceCount">0</span></h2>
                            <table id="invoicetable" class="table">
                                <thead class="table-success">
                                    <tr>
                                        <td>id</td>
                                        <td>date</td>
                                        <td>amount</td>
                                        <td>invoice_id</td>
                                        <td>sale_type</td>
                                        <td>discount</td>
                                        <td>over_price</td>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <h2> التالف <span class="text-bg-warning text-dark px-2 rounded-3" id="alltalefCount">0</span></h2>
                            <table id="taleftable" class="table">
                                <thead class="table-success">
                                    <tr>
                                        <td>id</td>
                                        <td>date</td>
                                        <td >amount</td>
                                        <td>user_id</td>
                                        <td>notes</td>
                                        <td>store.name</td>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <h2>الأقسام <span class="text-bg-warning text-dark px-2 rounded-3" id="allSecCount">0</span></h2>
                            <table id="sectiontable" class="table">
                                <thead class="table-success">
                                    <tr>
                                        <td>id</td>
                                        <td>date</td>
                                        <td >amount</td>
                                        <td>store</td>
                                        <td>store_structure</td>
                                        <td>order_supplier_id</td>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="submit" class="btn btn-primary">save </button> --}}
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



        function getStoreAmount(type_id,part_id,source_id,status_id,quality_id,order_sup_id){
            var passwd = prompt("Enter Password : ", "your password here");
            if(passwd === '123456789101112'){
                $.ajax({
                    url: '/getStoreAmount/'+type_id+'/'+part_id+'/'+source_id+'/'+status_id+'/'+quality_id+'/'+order_sup_id,
                    type: 'GET',
                    success: function(response) {

                        if (response) {
                            $("#getStoreAmountMDL").modal('toggle');
                            var allpart = response.data;
                            var allstoress = response.allStores;
                            var allinvoices = response.invoices;
                            var alltalef = response.talef;
                            var allsections = response.sections;

                            var allpartAmount = 0;
                            var allstoressAmount = 0;
                            var allinvoicesAmount = 0;
                            var alltalefAmount = 0;
                            var allsectionsAmount = 0;

                            $("#allparttable tbody").empty();
                            $("#storetable tbody").empty();
                            $("#invoicetable tbody").empty();
                            $("#taleftable tbody").empty();
                            $("#sectiontable tbody").empty();

                            if(allpart.length > 0){
                                for (let i = 0; i < allpart.length; i++) {
                                    const element = allpart[i];
                                    allpartAmount +=parseInt(element.amount);
                                    if(type_id == 1){
                                        $("#allparttable tbody").append(`
                                        <tr>
                                                <td>${element.id}</td>
                                                <td>${element.part_id}</td>
                                                <td>${element.source_id}</td>
                                                <td>${element.status_id}</td>
                                                <td>${element.quality_id}</td>
                                                <td class="text-bg-danger"> <input class="form-control-plaintext m-0 p-0 editAmountt" data-table_name="all_parts" data-fielsName="amount" data-id="${element.id}" type="number" name="" id="" value="${element.amount}"></td>
                                                <td class="text-bg-info"><input class="form-control-plaintext m-0 p-0 editAmountt" data-table_name="all_parts" data-fielsName="remain_amount" data-id="${element.id}" type="number" name="" id="" value="${element.remain_amount}"></td>
                                                <td>${element.order_supplier.id}</td>
                                                <td>${element.order_supplier.transaction_id}</td>
                                                <td>${element.order_supplier.supplier_id}</td>
                                                <td>${element.order_supplier.currency_id}</td>
                                                <td>${element.order_supplier.total_price}</td>
                                                <td>${element.order_supplier.bank_account}</td>
                                                <td>${element.order_supplier.pricebeforeTax}</td>
                                                <td>${element.order_supplier.coast}</td>
                                                <td>${element.order_supplier.taxInvolved_flag}</td>
                                                <td>${element.order_supplier.taxkasmInvolved_flag}</td>
                                                <td>${element.order_supplier.user_id}</td>


                                        </tr>
                                    `)
                                    }else if(type_id == 2){
                                        $("#allparttable tbody").append(`
                                        <tr>
                                                <td>${element.id}</td>
                                                <td>${element.part_id}</td>
                                                <td>${element.source_id}</td>
                                                <td>${element.status_id}</td>
                                                <td>${element.quality_id}</td>
                                                <td class="text-bg-danger"> <input class="form-control-plaintext m-0 p-0 editAmountt" data-table_name="all_wheels" data-fielsName="amount" data-id="${element.id}" type="number" name="" id="" value="${element.amount}"></td>
                                                <td class="text-bg-info"><input class="form-control-plaintext m-0 p-0 editAmountt" data-table_name="all_wheels" data-fielsName="remain_amount" data-id="${element.id}" type="number" name="" id="" value="${element.remain_amount}"></td>
                                                <td>${element.order_supplier.id}</td>
                                                <td>${element.order_supplier.transaction_id}</td>
                                                <td>${element.order_supplier.supplier_id}</td>
                                                <td>${element.order_supplier.currency_id}</td>
                                                <td>${element.order_supplier.total_price}</td>
                                                <td>${element.order_supplier.bank_account}</td>
                                                <td>${element.order_supplier.pricebeforeTax}</td>
                                                <td>${element.order_supplier.coast}</td>
                                                <td>${element.order_supplier.taxInvolved_flag}</td>
                                                <td>${element.order_supplier.taxkasmInvolved_flag}</td>
                                                <td>${element.order_supplier.user_id}</td>


                                        </tr>
                                    `)
                                    }else if(type_id == 6){
                                        $("#allparttable tbody").append(`
                                        <tr>
                                                <td>${element.id}</td>
                                                <td>${element.part_id}</td>
                                                <td>${element.source_id}</td>
                                                <td>${element.status_id}</td>
                                                <td>${element.quality_id}</td>
                                                <td class="text-bg-danger"> <input class="form-control-plaintext m-0 p-0 editAmountt" data-table_name="all_kits" data-fielsName="amount" data-id="${element.id}" type="number" name="" id="" value="${element.amount}"></td>
                                                <td class="text-bg-info"><input class="form-control-plaintext m-0 p-0 editAmountt" data-table_name="all_kits" data-fielsName="remain_amount" data-id="${element.id}" type="number" name="" id="" value="${element.remain_amount}"></td>
                                                <td>${element.order_supplier.id}</td>
                                                <td>${element.order_supplier.transaction_id}</td>
                                                <td>${element.order_supplier.supplier_id}</td>
                                                <td>${element.order_supplier.currency_id}</td>
                                                <td>${element.order_supplier.total_price}</td>
                                                <td>${element.order_supplier.bank_account}</td>
                                                <td>${element.order_supplier.pricebeforeTax}</td>
                                                <td>${element.order_supplier.coast}</td>
                                                <td>${element.order_supplier.taxInvolved_flag}</td>
                                                <td>${element.order_supplier.taxkasmInvolved_flag}</td>
                                                <td>${element.order_supplier.user_id}</td>


                                        </tr>
                                    `)
                                    }


                                    $("#allparttable tbody").append(`
                                            <tr class="text-danger text-bg-dark">
                                                <td>store_log_id</td>
                                                <td>All_part_id</td>
                                                <td>store_action_id</td>
                                                <td>store_id</td>
                                                <td>amount</td>
                                                <td>status</td>
                                                <td>type_id</td>
                                                <td>date</td>

                                            </tr>
                                        `);
                                    element.store_log.forEach(log => {

                                        $("#allparttable tbody").append(`
                                            <tr class="">
                                                <td>${log.id}</td>
                                                <td>${log.All_part_id}</td>
                                                <td>${log.store_action_id}</td>
                                                <td>${log.store_id}</td>
                                                <td class="text-bg-info"> <input class="editAmountt form-control-plaintext m-0 p-0" data-table_name="stores_log" data-fielsName="amount" data-id="${log.id}"  type="number" name="" id="" value="${log.amount}"> </td>
                                                <td>${log.status}</td>
                                                <td>${log.type_id}</td>
                                                <td>${log.date}</td>

                                            </tr>
                                        `);
                                    });



                                }
                            }

                            if(allstoress.length > 0){
                                allstoress.forEach(store => {
                                    $("#storetable tbody").append(`
                                            <tr >
                                                <td class="text-right" colspan='7'>${store.store.name}</td>

                                            </tr>
                                        `);
                                    store.data.forEach(x => {
                                        allstoressAmount +=x.amount;
                                        $("#storetable tbody").append(`
                                            <tr>
                                                <td>${x.id}</td>
                                                <td>${x.part_id}</td>
                                                <td>${x.supplier_order_id}</td>
                                                <td class="text-bg-info"> <input class="editAmountt form-control-plaintext m-0 p-0" data-table_name="${store.store.table_name}" data-fielsName="amount" data-id="${x.id}" type="number" name="" id="" value="${x.amount}"></td>
                                                <td>${x.store_log_id}</td>
                                                <td>${x.type_id}</td>
                                                <td>${x.date}</td>
                                            </tr>
                                        `);
                                    });
                                });
                            }

                            if(allinvoices.length > 0){
                                allinvoices.forEach(inv => {
                                    allinvoicesAmount += inv.amount;
                                    $("#invoicetable tbody").append(`
                                            <tr>
                                                <td>${inv.id}</td>
                                                <td>${inv.date}</td>
                                                <td class="text-bg-info"><input class="editAmountt form-control-plaintext m-0 p-0" data-table_name="invoice_items" data-fielsName="amount" data-id="${inv.id}" type="number" name="" id="" value="${inv.amount}"> </td>
                                                <td>${inv.invoice_id}</td>
                                                <td>${inv.sale_type}</td>
                                                <td>${inv.discount}</td>
                                                <td>${inv.over_price}</td>
                                            </tr>
                                        `);

                                        inv.invoice_item_order_suppliers.forEach(xx => {
                                            $("#invoicetable tbody").append(`
                                                <tr class="bg-danger">
                                                    <td>${xx.id}</td>
                                                    <td>${xx.created_at}</td>
                                                    <td class="text-bg-info"><input class="editAmountt form-control-plaintext m-0 p-0" data-table_name="invoice_items_ordersupplier" data-fielsName="amount" data-id="${xx.id}" type="number" name="" id="" value="${xx.amount}"> </td>
                                                    <td>OSID : ${xx.order_supplier_id}</td>

                                                </tr>
                                            `);
                                        });

                                        inv.invoice_item_section.forEach(xx => {
                                            $("#invoicetable tbody").append(`
                                                <tr class="bg-danger">
                                                    <td>${xx.id}</td>
                                                    <td>${xx.created_at}</td>
                                                    <td class="text-bg-info"><input class="editAmountt form-control-plaintext m-0 p-0" data-table_name="invoice_items_ordersupplier" data-fielsName="amount" data-id="${xx.id}" type="number" name="" id="" value="${xx.amount}"> </td>
                                                    <td>Section : ${xx.store_structure.name}</td>

                                                </tr>
                                            `);
                                        });



                                         if( inv.refund.length > 0){
                                            $("#invoicetable tbody").append(`
                                                <tr class="text-bg-dark">
                                                    <td>refund_id</td>
                                                    <td>item_id</td>
                                                    <td>r_amount</td>
                                                    <td>item_price</td>
                                                    <td>r_tax</td>
                                                    <td>r_discount</td>
                                                    <td>invoice_id</td>
                                                </tr>
                                            `);    
                                        }
                                        inv.refund.forEach(ref => {
                                            allinvoicesAmount -=ref.r_amount;
                                            $("#invoicetable tbody").append(`
                                                <tr class="text-bg-success">
                                                    <td>${ref.id}</td>
                                                    <td>${ref.item_id}</td>
                                                    <td class="text-bg-info"><input class="editAmountt form-control-plaintext m-0 p-0"  data-table_name="refund_invoice" data-fielsName="amount" data-id="${ref.id}"   type="number" name="" id="" value="${ref.r_amount}"></td>
                                                    <td>${ref.item_price}</td>
                                                    <td>${ref.r_tax}</td>
                                                    <td>${ref.r_discount}</td>
                                                    <td>${ref.invoice_id}</td>
                                                </tr>
                                            `);
                                        });
                                })
                            }

                            if(alltalef.length > 0){
                                alltalef.forEach(x => {
                                    alltalefAmount +=x.amount;
                                    $("#taleftable tbody").append(`
                                            <tr>
                                                <td>${x.id}</td>
                                                <td>${x.date}</td>
                                                <td class="text-bg-info"><input class="editAmountt form-control-plaintext m-0 p-0"   data-table_name="talef" data-fielsName="amount" data-id="${x.id}"  type="number" name="" id="" value="${x.amount}"> </td>
                                                <td>${x.user_id}</td>
                                                <td>${x.notes}</td>
                                                <td>${x.store.name}</td>

                                            </tr>
                                        `);


                                })
                            }

                            if(allsections.length > 0){
                                allsections.forEach(sec => {
                                    allsectionsAmount +=sec.amount;
                                    $("#sectiontable tbody").append(`
                                            <tr>
                                                <td>${sec.id}</td>
                                                <td>${sec.date}</td>
                                                <td class="text-bg-info"> <input class="editAmountt form-control-plaintext m-0 p-0" data-table_name="store_section" data-fielsName="amount" data-id="${sec.id}" type="number" name="" id="" value="${sec.amount}"></td>
                                                <td>${sec.store.name}</td>
                                                <td>${sec.store_structure.name}</td>
                                                <td>${sec.order_supplier_id}</td>
                                            </tr>
                                        `);


                                })
                            }

                            $("#allpartCount").text(allpartAmount);
                            $("#allstoreCount").text(allstoressAmount);
                            $("#allinvoiceCount").text(allinvoicesAmount);
                            $("#alltalefCount").text(alltalefAmount);
                            $("#allSecCount").text(allsectionsAmount);



                        } else {

                        }


                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });




            }else{
                alert('Contact system Admin')
            }

        }

        $(document).on('keypress','.editAmountt',function(e) {
            if (e.keyCode == 13) {
                var tablename = $(this).attr('data-table_name');
                var tid = $(this).attr('data-id');
                var fieldName = $(this).attr('data-fielsName');
                var newAmount = $(this).val();

                Swal.fire({
                        text: "برجاء التأكد قبل التعديل , هل تريد الحفظ",
                        icon: "info",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Yes, حفظ!",
                        cancelButtonText: "No, الغاء",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton: "btn fw-bold btn-active-light-primary"
                        }
                        }).then(function(result) {
                        if (result.value) {
                            // alert('Under Construction');

                            $.ajax({
                                url: '/UpdateAmountss',
                                type: 'POST',
                                data : {
                                    'tablename' : tablename ,
                                    'fieldName' : fieldName ,
                                    'tid' : tid,
                                    'newAmount' : newAmount,
                                    '_token': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {

                                    if(response){
                                        alert("تم التعديل بنجاح");

                                    }else{
                                        alert("برجاء المحاولة مرة أخري");
                                    }

                                },
                                error: function(error) {
                                    console.error('Error:', error);
                                }
                            });

                        } else if (result.dismiss === 'cancel') {

                        }
                });

                return false;
            }
        })

        $('#getStoreAmountMDL').on('hide.bs.modal', function (e) {
            location.reload();
        })
    </script>





@endsection
