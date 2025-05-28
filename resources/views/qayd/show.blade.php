@extends('layouts.master')
@section('css')
@endsection
@section('title')
    عرض بيانات قيد
@endsection



@section('content')

<main role="main" class="main-content ">
    <div class="page-content">
        <div class="mb-4">
            <h1 class="text-center font1">  عرض بيانات قيد</h1>
        </div>

        <div class=" row form-group mb-2">
            <div class="table-responsive">
                <table class="table table-striped  table-bordered border-light  text-center justify-content-center">
                    <thead class="border-light text-center">
                        <tr class="h">

                            <th class="text-center">م  </th>
                            <th class="text-center">اسم  الحساب</th>
                            <th class="text-center">رقم الحساب</th>
                            <th class="text-center">البيان </th>
                            <th class="text-center">مدين </th>
                            <th class="text-center">دائن </th>
                            <th class="text-center">التاريخ </th>
                            {{-- <th class="text-center">حذف </th> --}}
                          </tr>

                    </thead>


                    <tbody id="accountDetails">
                    @php
                        $i=1;
                    @endphp
                    @foreach ( $qayddetails as $qayddetail )

                        <tr class="h">
                            <td class="text-center"> {{ $i++ }} </td>
                            <td class="text-center"> {{ $qayddetail->branch_tree->name}} </td>
                            <td class="text-center">  {{ $qayddetail->branch_tree->accountant_number }} </td>
                            <td class="text-center">  {{ $qayddetail->topic }} </td>
                            <td class="text-center">  {{ $qayddetail->madin }} </td>
                            <td class="text-center">  {{  $qayddetail->dayin }} </td>
                            <td class="text-center">  {{  substr($qayddetail->date,0,10) }} </td>
                            {{-- <td class="text-center">
                                <form action="" method="POST">
                                    <a class="btn btn-primary" href="qayd/{{  $account->qaydid }}">عرض</a>
                                    @csrf
                                    @method('POST')
                                </form>
                            </td> --}}
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>

</div>
</main>
@endsection

@section('js')
{{-- <script src="{{URL::asset('assets/js/addqayd.js')}}"></script> --}}
<script>


</script>
@endsection
