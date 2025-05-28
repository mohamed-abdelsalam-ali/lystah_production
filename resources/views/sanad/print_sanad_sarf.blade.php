@extends('layouts.print-master')

@section('css')
<style>
    * {
        font-family: arabic;
    }
</style>
@endsection

@section('title')
سند صرف
@stop

@section('content')


<div class="tm_text_right tm_f22">


    <div class=" tm_box_3 tm_border_bottom">
        <div class="tm_col_2_md">
            <label>مبلغ وقدرة</label> : {{ $moneyVal }}
            {{-- <span>{{ $ClientPayment->value + $ClientPayment->value2 }}</span> --}}
            <span>فقط لا غير</span>
        </div>
        <div class="tm_col_2_md">
            <label> تم صرف للأستاذ</label> :
            <span>{{ $personName }}  </span>
        </div>

    </div>

    <div class="tm_box_3 tm_border_bottom">
        <div class="tm_col_2_md">
            <label>بتاريخ</label> :
            {{ $datee }}
            {{-- <span>{{ str_split($ClientPayment->date, 10)[0] }}</span> --}}
        </div>
        <div class="tm_col_2_md">
            <label>الطريقة</label> :
           {{ $payment_types }}
        </div>
        {{-- <div class="tm_col_2_md">
            <label>على بنك</label> :

        </div>
        <div class="tm_col_2_md ">
            <label>رقم التحويل</label> :

        </div> --}}

    </div>


    <div class="tm_box_3 tm_border_bottom ">
        <div class="tm_col_2_md">
           <span>{{isset($emp) ? $emp : ' '}}<span>
        </div>
        <div class="tm_col_2_md">
         :<label>المحاسب</label>
        </div>
        
    </div>
    <div class="tm_box_3 tm_border_bottom ">
  <div class="tm_col_2_md">
         <span>{{isset($note) ? $note : ' '}}<span>  
        </div>
        <div class="tm_col_2_md">
         :<label>ملاحظة</label>
         </div>
         
    </div>



    <div class="tm_box_3 tm_text_center">
        <h4>المركز الرئيسي - البحيرة - تليفون / 01019162004</h4>
    </div>
</div>
@endsection

@section('js')
<script>
   function disableF5(e)
     if ((e.which || e.keyCode) == 116 || (e.which || e.keyCode) == 82){
     e.preventDefault();
      };

    $(document).ready(function(){
        $(document).on("keydown", disableF5);
    });
</script>
@endsection
