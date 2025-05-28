@extends('layouts.print-master')

@section('css')
<style>
    * {
        font-family: arabic;
    }
</style>
@endsection

@section('title')
سند قبض
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
            <label>إستلمنا من الأستاذ</label> :
            <span>{{ $personName }}  </span>
        </div>

    </div>

    <div class="tm_box_3 tm_border_bottom">
        <div class="tm_col_2_md">
            <label>الطريقة</label> :
            {{ $payment_types }}
        </div>

        <div class="tm_col_2_md">
            <label>بتاريخ</label> :   {{ $datee }}

        </div>
    </div>

    <div class="tm_box_3 tm_border_bottom">
        <div class="tm_col_2_md">
            :  <label>وذلك مقابل</label>
        </div>
        <div class="tm_col_2_md">
            <label>لحساب</label> : <span>شركة الأمل</span>
        </div>
    </div>

    <div class="tm_box_3 tm_border_bottom ">
        <div class="tm_col_2_md">
            <span>{{isset($emp) ? $emp :"المدير"}} </span>
        </div>
        <div class="tm_col_2_md">
            <label>المستلم</label>
        </div>

    </div>


    <div class="tm_box_3 tm_border_bottom ">
        <div class="tm_col_2_md">
            <span>{{isset($note) ? $note :"لايوجد ملاحظات"}} </span>
        </div>
        <div class="tm_col_2_md">
            <label>ملاحظات</label>
        </div>

    </div>
    <div class="tm_box_3 tm_text_center">
        <h4>المركز الرئيسي - البحيرة - تليفون /  01019162004</h4>
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
