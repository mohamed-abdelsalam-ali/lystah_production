@extends('layouts.master')
@section('css')

@endsection
@section('title')
     المحاسب
@endsection



@section('content')

    <main role="main" class="main-content ">
        <div class="page-content ">
            <h1>   غلق عام مالي </h1>
            <div class="row justify-content-center ">
                <div class="col-12 border">

                    <h2 class="text-center my-2" style="background:#0080006b">{{ $store->name }} حساب رقم :  {{ $store->accountant_number }}</h2>
                    <div class="row border">
                        <div class="table-responsive">
                            <label for="storeStructureFilter" class="form-label">Filter by Section</label>

                            <input id="storeStructureFilter1" type="text" class="form-control" placeholder="Search by Section">
                            <table id="storeSectionsTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>القسم</th>
                                        <th>الصنف</th>
                                        <th>النوع</th>
                                        <th>بلدالمنشأ</th>
                                        <th>الحالة</th>
                                        <th>الجودة</th>
                                        <th>الكمية</th>
                                        <th>الكمية الجديدة</th>
                                        <th>-</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($storeSections as $section)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $section->store_structure->name }}</td>

                                            @if($section->part)
                                                <td>{{ optional($section->part)->name }}</td>
                                            @elseif ($section->wheel)
                                                <td>{{ optional($section->wheel)->name }}</td>
                                            @elseif ($section->tractor)
                                                <td>{{ optional($section->tractor)->name }}</td>
                                            @elseif ($section->clark)
                                                <td>{{ optional($section->clark)->name }}</td>
                                            @elseif ($section->kit)
                                                <td>{{ optional($section->kit)->name }}</td>
                                            @elseif ($section->equip)
                                                <td>{{ optional($section->equip)->name }}</td>
                                            @endif

                                            <td>{{ optional($section->type)->name }}</td>
                                            <td>{{ optional($section->source)->name_arabic }}</td>
                                            <td>{{ optional($section->status)->name }}</td>
                                            <td>{{ optional($section->part_quality)->name }}</td>
                                            <td>{{ $section->amount }}</td>
                                            <td><input type="number" name="" class="form-control" id=""></td>
                                            <td><button class="btn btn-primary">Save</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

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
        var table =$('#storeSectionsTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true
        });

        $('#storeStructureFilter').on('change', function () {
            var filterValue = $(this).val();
            if (filterValue === "") {
                table.columns(1).search('').draw();
            } else {
                table.columns(1).search(filterValue, false, false).draw(); // true, false for partial match
            }
        });

        $('#storeStructureFilter1').on('keyup change', function () {
            var filterValue = $(this).val();
            if (filterValue === "") {
                table.columns(1).search('').draw();
            } else {
                table.columns(1).search(filterValue, false, false).draw(); // true, false for partial match
            }
        })
    });
</script>

@endsection


