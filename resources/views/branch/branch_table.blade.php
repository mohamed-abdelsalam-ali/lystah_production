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
    الشجرة المحاسبية
@endsection


<style>
    img.card-img-top.w-50.h-50{
        width: 200px !important;
    }
    .bb{
        filter: saturate(1) grayscale(1) brightness(.7) contrast(1000%) invert(1);
    }
</style>

@section('content')
    
    <main role="main" class="main-content ">
        <div class="page-content ">
            <h1>  الشجرة المحاسبية </h1>
            <div class="row justify-content-center ">
                <div class="col-12 border">
                    <div class="row border">
                        <div class="table-responsive">
                            <table class="table table-striped  table-bordered border-light  text-center justify-content-center">
                                <thead class="border-light text-center">
                                    <tr class="h">

                                        <th class="text-center">اسم الحساب</th>
                                        <th class="text-center">english Name</th>
                                        <th class="text-center">كود الحساب</th>

                                      </tr>

                                </thead>

                                <tbody>
                                    @foreach($branchparent as $x)
                                        <tr class="text-center">

                                            <td>
                                                <button onclick="getchild(this,{{$x->id }})"class="form-controle btn btn-danger w-100">{{ $x->name }}</button>
                                            </td>
                                            <td>{{ $x->en_name }}</td>
                                            <td>{{ $x->accountant_number }}</td>
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
 <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
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
<script>



var flag = [];
    function getchild(el, cid) {
        var critiria_id = cid;
         if($(el).closest('tr').hasClass('opened')){
           childe= $(el).closest('tr').siblings('.child');
            console.log(childe);
            for (let index = 0; index < childe.length; index++) {
               childe[index].remove();

            }
            $(el).closest('tr').removeClass( "opened" );
        }else{
            if (critiria_id) {
            $.ajax({
                url: "{{ URL::to('branch_child') }}/"+critiria_id,
                type: "GET",
                dataType: "json",

                success: function(data) {
                  console.log(data);
                  let random= Math.floor(Math.random()*16777215).toString(16);
                  let random1= Math.floor(Math.random()*16777215).toString(16);
                  $(el).closest('tr').addClass('opened');
                    for (let index = 0; index < data.length; index++) {

                    $(el).closest('tr').after(`


                    <tr class="text-center child" >

                        <td>
                                <button onclick="getchild(this,${data[index].id })"class="form-controle btn  w-100 " style="background-color:#${random};color:black;">${data[index].name }</button>
                        </td>
                        <td>${data[index].en_name }</td>
                        <td>${data[index].accountant_number }</td>
                    </tr>

                    `);
                    }







                },
            });

          }
            else {
                console.log('AJAX load did not work');
            }
        }

    };
</script>


@endsection
