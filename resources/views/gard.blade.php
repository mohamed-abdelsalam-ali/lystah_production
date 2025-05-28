@extends('layouts.master')
@section('css')
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.min.js"></script>

    <style>
        table {
            font-family: 'Droid Arabic Naskh', serif;
        }

        .modal-dialog {
            max-width: 1000px !important;
        }
        @media print{
            .btn  {
                display: none !important;
            }
        }
    </style> --}}
@endsection
@section('title')
    Stores
@stop


@section('content')



    <div class="main-content">
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="text-center">
                                <i class="fab fa-mdb fa-4x ms-0" style="color:#5d9fc5 ;"></i>
                                <h1 class="pt-0">
                                    {{ $storeid->name }}
                                </h1>
                            </div>

                        </div>
                        <div class="col-lg-2">
                            <a href="/printgard" class="btn btn-success">Print</a>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">

                        <table id="itemTbl" class="table table-striped table-bordered cell-border " style="width:100%">
                            <thead style="background:#5fcee78a">
                                <tr>
                                    <th>الرقم</th>
                                    <th>الاسم</th>
                                    <th>رقم القطعة</th>
                                    <th>المنشأ</th>
                                    <th>الحالة</th>
                                    <th>الجودة</th>
                                    <th>الكمية</th>
                                    <th>الباركود</th>
                                    <th>طباعة</th>
                                </tr>
                            </thead>
                            @foreach ( $storeDetails as $item )
                                <tr>
                                    <td> {{ $item->part[0]->id }}</td>
                                    <td> {{$item->part[0]->name }}</td>
                                    <td>
                                        <ul class="list-group list-group-flush">
                                            @for ($x =0 ; $x < count($item->part_numbers) ; $x++)
                                            <li class="list-group-item"> {{ $item->part_numbers[$x]->number }} </li>
                                            @endfor
                                        </ul>

                                    </td>

                                    <td> {{ $item->allPart[0]->source->name_arabic }}</td>
                                    <td> {{ $item->allPart[0]->status->name }}</td>
                                    <td> {{ $item->allPart[0]->part_quality->name }}</td>
                                    <td> {{ $item->amount }}</td>
                                    <td>
                                        <span class="barcodeTxt d-none">{{ $item->type_id }}-{{ $item->part_id }}-{{ $item->allPart[0]->source_id }}-{{ $item->allPart[0]->status_id }}-{{ $item->allPart[0]->quality_id }}</span>
                                            <svg class="barcode"> </svg> </td>
                                    <td><a class="btn text-bg-danger" href="/printBarcode/{{ $item->type_id }}-{{ $item->part_id }}-{{ $item->allPart[0]->source_id }}-{{ $item->allPart[0]->status_id }}-{{ $item->allPart[0]->quality_id }}/{{str_replace('/', '', $item->part[0]->name)  }}">print Barcode</a>  </td>
                                </tr>
                            @endforeach
                        </table>
                        </div>

                    </div>

                </div>
            </div>






        </div>
    </div>



@endsection

@section('js')
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
 --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"></script>

    <script>
    storeDetails={!!$storeDetails!!};
    console.log(storeDetails);
        $(document).ready(function () {
            $('#itemTbl tbody tr').each(function(){
                JsBarcode($(this).find('.barcode')[0],$(this).find('.barcodeTxt').text());
            })
        });
    </script>


@endsection
