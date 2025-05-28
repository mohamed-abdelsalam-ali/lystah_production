@extends('layouts.print-equip_master')
@section('css')
    <style>
        th {
            text-align: center !important;
        }

        @media print {
            .togRow {
                display: none;
            }
        }

        .tawkelTble td {
            border: 0px !important;
        }

        .footerTable td {
            border: 0px !important;
            padding: 0px !important;
        }
    </style>
@endsection

@section('content')
    {{-- {{ $presaleFlag }} --}}
    <div class="tm_container tm_f15 tbl1" style="direction: rtl">
        <table class="tm_f16 tm_bold">
           <tr>
            <td>التاريخ</td>
            <td>{{ $qayd->date }}</td>
            <td>رقم الصفحة اليومية</td>
            <td>...........</td>
            <td>رقم القيد</td>
            <td>{{ $qayd->id }}</td>
           </tr>

        </table>
        <br>
        <hr>
        <br>
        <table class="tm_f16 tm_bold">
           <tr class="tm_accent_bg_10">
            <td>من</td>
            <td>إلي</td>
            <td>الحساب المدين</td>
            <td>الحساب الدائن</td>
            <td>البيـــــان</td>
           </tr>

            @php
                $sum_madin = 0;
                $sum_dayin = 0;
            @endphp
           @forelse($qayd->qayditems as $key => $value)
            @if ($value->madin > 0)
                @php
                    $sum_madin += $value->madin;
                @endphp
                <tr>
                    <td>{{ $value->madin }}</td>
                    <td>0</td>
                    <td>{{ $value->branch_tree->name }}</td>
                    <td> -</td>
                    <td class="tm_f12">{{ $value->topic }}</td>
                </tr>
            @else
                @php
                    $sum_dayin += $value->dayin;
                @endphp
                <tr>
                    <td>0</td>
                    <td>{{ $value->dayin }}</td>
                    <td> -</td>
                    <td>{{ $value->branch_tree->name }}</td>
                    <td class="tm_f12">{{ $value->topic }}</td>
                </tr>
            @endif

           @empty
           <tr>
                <td colspan="5">------------</td>


            </tr>
           @endforelse

            <tr>
                <td>{{ $sum_madin }}</td>
                <td>{{ $sum_madin }}</td>

                <td class="tm_f12" colspan="3"></td>
            </tr>

         </table>

         <table>
            <tr>
                <td>توقيع المدير : </td>
                <td></td>
                <td>توقيع المحاسب :</td>
                <td></td>
            </tr>
         </table>

    </div>



@endsection
@section('js')
    <script>
        var qayd = {!! $qayd !!};
        $(document).ready(function() {
            $("#datenowLbl").text(qayd.date);
        });
        window.onafterprint = function() {




        }

        function toggleInvoice() {

        }
    </script>
@endsection
