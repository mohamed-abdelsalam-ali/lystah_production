@extends('layouts.master')
@section('css')
@endsection
@section('title')
ميزان مراجعة
@endsection



@section('content')

<main role="main" class="main-content ">
    <div class="page-content pb-1 ">
        <div class="mb-4">
            <h1 class="text-center font1"> كشف ميزان مراجعة</h1>
        </div>

    <form action="{{url('gettrialbalance')}}" method="GET" >
        @csrf
        <div class="row form-group   mb-2">
            <label class="col-lg-2 col-form-label font1"> من
            </label>
            <div class="col-lg-4">
                <input id="dateQaydfrom" required name="dateQaydfrom" type="date" placeholder="الاسم باللغة العربية " class="form-control">

            </div>
            <label class="col-lg-2 col-form-label font1"> الي
            </label>
            <div class="col-lg-4">
                <input id="dateQaydto" required name="dateQaydto" type="date" placeholder="الاسم باللغة العربية " class="form-control">

            </div>

        </div>
        <div class="form-group  mt-3 text-center">
            <button type="submit" class="btn btn-primary">بحث</button>
        </div>
    </form>

        <div class=" row form-group mb-2">
            <div class="table-responsive">
                <table class="table table-striped  table-bordered   text-center justify-content-center table-active border-inset" id="viewdatatable">
                    <thead class="border-light text-center">
                        <tr class="h">
                            <!--<th class="text-center">م </th>-->
                            <th class="text-center"> اسم الحساب </th>
                            <th class="text-center"> رقم الحساب </th>
                            <th class="text-center">مدين </th>
                            <th class="text-center">دائن </th>

                          </tr>

                    </thead>


                    <tbody id="accountsDetails">
                        @php
                            $i =1;
                            $totaldayin = 0;
                            $totalmadin = 0;
                            $total = 0;
                        @endphp
                        @foreach ( $results as $result )
                            <tr class="h">
                                <!--<td class="text-center">   {{ $i++ }}  </td>-->
                                <td class="text-center"> {{ $result->branch_tree->name }}</td>
                                <td class="text-center"> {{ $result->branch_tree->accountant_number }}</td>
    
                                @php
                                    $count=$result->tmadin -  $result->tdayin;
                                    $totaldayin += $result->tdayin;
                                    $totalmadin += $result->tmadin;
                                    $total += $result->tmadin -  $result->tdayin;
                                @endphp
    
                                @if ( $count>0)
                                <td class="text-center">  {{ abs($count) }} </td>
    
                                @else
                                <td class="text-center">  </td>
                                @endif
                                @if ( $count<0)
                                <td class="text-center">  {{ abs($count) }} </td>
    
                                @else
                                <td class="text-center">  </td>
                                @endif
    
                            </tr>
                        @endforeach
                        
                    </tbody>
                   
                </table>
                
                <table>
                    
                        <tr class="h text-bg-info fs-20">
                            <!--<td class="text-center">   -  </td>-->
                            <td class="text-center">مدين</td>
                            <td class="text-center">دائن</td>
                            <td class="text-center">الاجمالي</td>
                            
                           

                            
                            
                            

                        </tr>
                        <tr class="text-bg-success fs-20 text-center">
                            <td class="text-center">  {{ abs($totalmadin) }} </td>

                            
                           
                            
                            <td class="text-center">  {{ abs($totaldayin) }} </td> 
                                <td class="text-center"> {{ $total }} </td>
                            
                        </tr>
                </table>
            </div>
        </div>




</div>
</main>
@endsection

@section('js')
<script src="{{URL::asset('assets/js/addqayd.js')}}"></script>
<script>

    $("#viewdatatable").DataTable({
                lengthChange: false,
                "oLanguage": {
                    "sSearch": "البحث"
                },
                "bPaginate": false,
                 dom: '<"dt-buttons"Bf><"clear">lirtp',
                buttons: [
                    "copyHtml5",
                    "csvHtml5",
                    "excelHtml5",
                    
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [0,1,2,3]
                        }
                    },

                ],
                "columnDefs": [

                    {
                        "className": "dt-center",
                        "targets": "_all"
                    }
                ],


                columns: [

                   
                    {
                        data: "tdayin"
                    },
                    {
                        data: "tmadin"
                    },

                    // {
                    //     data: "branchid"
                    // },
                    {
                        data: "branch_tree.name"
                    },
                    {
                        data: "branch_tree.accountant_number"
                    }

                ],



            });

</script>
@endsection
