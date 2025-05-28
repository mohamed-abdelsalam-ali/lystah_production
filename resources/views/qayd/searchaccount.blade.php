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
   بحث بالحساب
@endsection



@section('content')

<main role="main" class="main-content ">
    <div class="page-content pb-1   ">
        <div class="mb-4">
            <h1 class="text-center font1"> كشف حساب</h1>
        </div>

    <form action="{{url('accountstatement')}}" method="POST" >
        @csrf
        <div class="row form-group   mb-2">
            <label class="col-lg-2 col-form-label font1"> من
            </label>
            <div class="col-lg-4">
                <input id="dateQaydfrom" name="dateQaydfrom" value="" type="date" placeholder="الاسم باللغة العربية " class="form-control">

            </div>
            <label class="col-lg-2 col-form-label font1"> الي
            </label>
            <div class="col-lg-4">
                <input id="dateQaydto" name="dateQaydto" type="date" placeholder="الاسم باللغة العربية " class="form-control">

            </div>
            <label class="col-lg-2 col-form-label font1">اسم الحساب
               </label>
            <div class="col-lg-4">
               <select id="branchs" class="form-control" name="branchs">

               </select>
            </div>
        </div>
        <div class="form-group  mt-3 text-center">
            <button type="submit" class="btn btn-primary">بحث</button>
        </div>
    </form>
    <div class="mb-4">
        <label class="col-lg-2 col-form-label font1">اسم الحساب</label>
        <label class="col-lg-2 font1">
            @if (count($accounts) > 0) 
            {{ $accounts[0]->branch_tree->name}}
            @else

            @endif

        </label>
        <label class="col-lg-2 col-form-label font1">رقم الحساب</label>
        <label class="col-lg-1 font1">
            @if (count($accounts) > 0)
                {{ $accounts[0]->branch_tree->accountant_number}}
            @else


            @endif

        </label>
        {{-- <label class="col-lg-1 col-form-label font1"> مدين</label>
        <label class="col-lg-1 font1">
            @if ($summadin>0) {{ $summadin}}
            @else

            @endif

        </label>
        <label class="col-lg-1 col-form-label font1">دائن </label>
        <label class="col-lg-1 font1">
            @if ($sumdayin>0) {{ $sumdayin}}
            @else

            @endif

        </label> --}}
    </div>
        <div class=" row form-group mb-2">
            <div class="table-responsive">
                <table class="table table-striped  table-bordered border-light  text-center justify-content-center">
                    <thead class="border-light text-center">
                        <tr class="h">
                            <th class="text-center">التاريخ </th>
                            <th class="text-center">رقم القيد </th>

                            <th class="text-center">نوع القيد  </th>


                            <th class="text-center">البيان </th>
                            <th class="text-center">مدين </th>
                            <th class="text-center">دائن </th>
                            <th class="text-center">الرصيد  </th>
                          </tr>

                    </thead>


                    <tbody id="accountDetails">
                    @php
                        $count =0;
                    @endphp
                    @foreach ( $accounts as $account )
                        <tr class="h">
                            <td class="text-center">  {{  substr($account->date,0,10) }} </td>
                            <td class="text-center"> 2023/{{ $account->qaydid }}</td>
                            <td class="text-center"> {{ $account->qayd->qaydtype->name}} </td>


                            <td class="text-center">  {{ $account->topic }} </td>
                            <td class="text-center">  {{ $account->madin }} </td>
                            <td class="text-center">  {{  $account->dayin }} </td>
                            <td class="text-center">


                               @php
                                    $count=$count+ $account->madin -  $account->dayin
                               @endphp
                               {{ abs($count) }}
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <div class="mb-4">
            <h3 class="col-lg-2  " style="display: inline-block"> إجمالي الرصيد :</h3>
            <h4 class="col-lg-2 font1"style="display: inline-block"> {{ abs($count) }}</h4>


        </div>



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
 $(document).ready(function(){
    var fromxx={!! isset($from)? $from : 0 !!};
    var toxx ={!! isset($to)? $to : 0 !!};
    var branchid={!! isset($branchid)? $branchid : " " !!};
    
    if(fromxx){
        
        $('#dateQaydfrom').val(fromxx);
    }
     if(toxx){
        $('#dateQaydto').val(toxx);
    }
   
         if(branchid){
        $('#branchs').val(branchid).trigger('change');
    }
    });
   

</script>
@endsection
