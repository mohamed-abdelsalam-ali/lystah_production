@extends('layouts.master')
@section('css')
 <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.css">
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::asset('assets/dataTables/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/dataTables/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="/assets/libs/sweetalert2/sweetalert2.min.css">
@endsection
@section('title')
    إضافة قيد
@endsection



@section('content')

<main role="main" class="main-content ">
    <div class="page-content">
        <div class="mb-4">
            <h1 class="text-center font1">إضافة قيد جديد</h1>
        </div>
        @if (isset($success))
        <div class="col-sm-12">
            <div class="alert  alert-susccess alert-success fade show" role="alert">
             {{ $success}}

            </div>
        </div>
    @endif
    <form action="{{url('qayd')}}" method="POST" class="bg-white p-4">
        @csrf
        <div class="row form-group   mb-2">
            <label class="col-lg-2 col-form-label font1"> التاريخ
            </label>
            <div class="col-lg-4">
                <input id="dateQayd" name="dateQayd" type="date" placeholder="الاسم باللغة العربية " class="form-control">

            </div>
            <label class="col-lg-2 col-form-label font1">نوع القيد
               </label>
            <div class="col-lg-4">
               <select id="qaydType" class="form-control" name="qaydType">

               </select>
            </div>
        </div>

        <div class="form-group  mt-3  font1">
            <button type="button" onclick="addrowitems()"class="btn btn-success">اضافة</button>
        </div>
        <div class=" row form-group mb-2">
            <div class="table-responsive">
                <table class="table table-striped  table-bordered border-light  text-center justify-content-center">
                    <thead class="border-light text-center">
                        <tr class="h">


                            <th class="text-center">اسم  الحساب</th>
                            <th class="text-center">رقم الحساب</th>
                            <th class="text-center">البيان </th>
                            <th class="text-center">مدين الأموال الواردة إلى الحساب </th>
                            <th class="text-center">دائن الأموال الخارجة من الحساب </th>
                            <th class="text-center">حذف </th>
                          </tr>

                    </thead>

                    <tbody id="qayditems">



                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-group  mt-3 text-center">
            <button type="submit" class="btn btn-primary w-25">حفظ</button>
        </div>
    </form>
</div>
</main>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('assets/dataTables/dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.js"></script>
    <script src="{{ URL::asset('assets/dataTables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.print.min.js') }}"></script>
    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="{{URL::asset('assets/js/addqayd.js')}}"></script>
<script>


</script>
@endsection
