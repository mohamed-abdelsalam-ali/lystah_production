@extends('layouts.master')
@section('css')
 


   
@endsection
@section('title')
    Talabea
@stop


@section('content')

   

    <div class="main-content">
        <div class="page-content">
          
            <div class="row">
                <div class="col-lg-3">
                    <h2>{{ $talabea->name }}</h2>
                </div>
                <div class="col-lg-6">

                </div>
                <div class="col-lg-3">
                    <label for="">طريقة العرض   : </label>
                    <select id="languageSwitch" class="form-control">
                        <option value="en">English</option>
                        <option value="ar">Arabic</option>
                    </select>
                </div>
            </div>
            <div class="card">
                
                <div class="card-body">
                    <div class="col-12 px-1 m-3 text-end">
                        <table id="talabeaTbl">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Numbers</th>
                                    <th class="arabic-col">الاسم</th>
                                    <th class="english-col"> Name</th>
                                    <th class="arabic-col">المنشأ</th>
                                    <th class="english-col"> Source</th>
                                    <th class="arabic-col">الحالة</th>
                                    <th class="english-col"> Status</th>
                                    <th class="arabic-col">الجودة</th>
                                    <th class="english-col"> Quality</th>
                                    <th>Amount</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($talabeaItems as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                        @if (isset($item->item->part_numbers))
                                            @forelse ( $item->item->part_numbers as $num )
                                                @if (isset($num->supplier))
                                                <li title="{{ $num->supplier->name }}">{{ $num->number }}</li>    
                                                @endif
                                            @empty
                                                <li></li>
                                            @endforelse
                                        @endif
                                        </td>
                                        <td class="arabic-col">{{ $item->item->name }}</td>
                                        <td class="english-col">{{ $item->item->name_eng }}</td>
                                        <td class="arabic-col">{{ isset($item->source) ? $item->source->name_arabic : null }}</td>
                                        <td class="english-col">{{ isset($item->source) ? $item->source->name_en : null }}</td>
                                        <td class="arabic-col">{{ isset($item->status) ? $item->status->name : null }}</td>
                                        <td class="english-col">{{ isset($item->status) ? $item->status->name : null  }}</td>
                                        <td class="arabic-col">{{ isset($item->part_quality) ? $item->part_quality->name : null  }}</td>
                                        <td class="english-col">{{ isset($item->part_quality) ? $item->part_quality->name : null  }}</td>
                                     
                                        <td>
                                         
                                                <form action="/UpdatetalabeaItemAmount" method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <input type="hidden" name="part_id" value="{{ $item->part_id }}">
                                                    <input type="hidden" name="source_id" value="{{ $item->source_id }}">
                                                    <input type="hidden" name="status_id" value="{{ $item->status_id }}">
                                                    <input type="hidden" name="quality_id" value="{{ $item->quality_id }}">
                                                    <input type="hidden" name="type_id" value="{{ $item->type_id }}">
                                                    <input type="hidden" name="talabea_id" value="{{ $talabea->id }}">
                                                    <span class="d-none">{{ $item->amount }}</span> <input class="form-control" type="number" name="amount" value="{{ $item->amount }}">
                                                </form>
                                           
                                           
                                        </td>
                                        <td>
                                           
                                            <form action="/DeleteTalabeaItem" method="POST">
                                                @csrf
                                                @method('POST')
                                                <input type="hidden" name="part_id" value="{{ $item->part_id }}">
                                                <input type="hidden" name="source_id" value="{{ $item->source_id }}">
                                                <input type="hidden" name="status_id" value="{{ $item->status_id }}">
                                                <input type="hidden" name="quality_id" value="{{ $item->quality_id }}">
                                                <input type="hidden" name="type_id" value="{{ $item->type_id }}">
                                                <input type="hidden" name="talabea_id" value="{{ $talabea->id }}">
                                                <button type="submit" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle ">
                                                    <i class="ri ri-delete-bin-2-line fs-22"></i>
                                                </button>
                                            </form>
                                           
                                            
                                            
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11">No Data Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
   
@endsection

@section('js')
   
<script>
    $('#talabeaTbl').dataTable({
           
            ordering: true,
            paging: false,
            dom: 'Bfrtip',
            buttons: [
                'print',
                'excel',
                 {
                    extend: 'csvHtml5',
                    text: 'CSV',
                    bom: true, // Add BOM for Arabic
                    charset: 'utf-8',
                    title: 'EMARA Data - ' + Date(),
                    exportOptions: {
                        columns: ':visible' // Only export visible columns
                    }
                }
            ]
        })

        $(document).ready(function () {
            // Function to toggle columns
            function toggleLanguageColumns(language) {
                if (language === "ar") {
                    $(".english-col").hide();
                    $(".arabic-col").show();
                } else {
                    $(".english-col").show();
                    $(".arabic-col").hide();
                }
            }

            // Set default language to English
            toggleLanguageColumns("en");

            // Change language on dropdown selection
            $("#languageSwitch").change(function () {
                toggleLanguageColumns($(this).val());
            });
        });

</script>



@endsection
