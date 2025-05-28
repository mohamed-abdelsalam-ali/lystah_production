@extends('layouts.master')
@section('css')

<style>


</style>
@endsection
@section('title')

Stores
@stop


@section('content')



<div class="main-content">
    <div class="page-content">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">Stores</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active">Stores</li>
                            <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            @foreach ($stores as $store)
            {{-- @hasanyrole('super admin|admin|staff|store keeper') --}}
            @can($store->table_name)
            <div class="col">
                <div class="text-center">
                    <img src="assets/images/store.png" class="card-img-top m-auto w-50" alt="...">
                    <div class="card-body">
                        <h5 class="card-title p-2">{{ $store->name }}</h5>
                        <p class="card-text d-none">{{ $store->note }} .</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <a target="_blank" href="https://www.google.com/maps/place/Itay+Al+Barud,+Atay+Al+Baroud,+Itay+El+Barud,+El+Beheira+Governorate/@30.8841134,30.6461341,14z/data=!4m15!1m8!3m7!1s0x14f639007fb2dbd7:0x736e2099fe3100bc!2sItay+Al+Barud,+Atay+Al+Baroud,+Itay+El+Barud,+El+Beheira+Governorate!3b1!8m2!3d30.8860402!4d30.6660076!16s%2Fg%2F121hdy2t!3m5!1s0x14f639007fb2dbd7:0x736e2099fe3100bc!8m2!3d30.8860402!4d30.6660076!16s%2Fg%2F121hdy2t?entry=ttu"><span>
                                    <i class="bx bx-location-plus fs-22"></i>
                                </span></a>
                            {{ $store->location }}

                        </li>
                        <li class="list-group-item">{{ $store->tel01 }}</li>
                        <li class="list-group-item">{{ $store->tel02 }}</li>
                    </ul>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <a href="/gard/{{ $store->id }}" class="card-link text-bg-danger p-2 rounded-1 btn m-0">جـــــــــرد</a>
                                <a href="{{ route('get_safe_store', $store->id) }}" class="card-link text-bg-danger p-2 rounded-1 btn m-0">الخزينة</a>
                                <a href="/get_store_employee_salary/{{ $store->id }}" class="card-link text-bg-danger p-2 rounded-1 btn m-0 text-nowrap">مرتبات
                                    الموظفين</a>


                            </div>
                        </div>
                        <div class="row m-3">
                            <div class="row m-1">
                                <div class="col-lg-12">
                                    <form action="/pos" method="">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="storeId" value="{{ $store->id }}">
                                        <!--<button class="btn btn-secondary w-100">Open It</button>-->
                                    </form>
                                </div>
                            </div>
                            <div class="row m-1">
                                <div class="col-lg-12">
                                    <form action="/newpos" method="">
                                        <!--@csrf-->
                                        @method('POST')
                                        <input type="hidden" name="storeId" value="{{ $store->id }}">
                                        <button class="btn btn-secondary w-100">Open new pos</button>
                                    </form>
                                </div>
                            </div>
                            <div class="row m-1">
                                 <div class="col-lg-12">
                                    <form action="/posSearch" method="">
                                        <!--@csrf-->
                                        @method('POST')
                                        <input type="hidden" name="storeId" value="{{ $store->id }}">
                                        <button class="btn btn-secondary w-100">Pos Search  </button>
                                    </form>
                                </div>
                            </div>

                        </div>






                    </div>
                </div>
            </div>
            @endcan
            {{-- @endhasanyrole --}}
            @endforeach

        </div>



    </div>
</div>



@endsection

@section('js')




@endsection
