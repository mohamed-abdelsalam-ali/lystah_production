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

                    <h2 class="text-center my-2" style="background:#0080006b">المخزون</h2>
                    <div class="row border">
                        <div class="table-responsive">
                            <div class="row mt-5">
                                    @foreach ($stores as $store)
                                        <div class="card" style="width: 18rem;">
                                            <img src="assets/images/store.png" class="card-img-top m-auto w-50" alt="...">
                                            <div class="card-body">
                                                <h2 class="card-title1">{{ $store->name }}</h2>
                                                <p class="card-text fs-17 fw-bold">
                                                    مدين : {{ isset($store->qayd) ? $store->qayd->tmadin : 0}}
                                                    <br>
                                                    دائن : {{ isset($store->qayd) ? $store->qayd->tdayin : 0}}
                                                    </br>
                                                    الرصيد : {{  (isset($store->qayd) ?  $store->qayd->tmadin : 0) - (isset($store->qayd) ? $store->qayd->tdayin : 0) }}
                                                </p>
                                                <a href="/storegardMaley/{{ $store->id }}" class="btn btn-primary">جرد مالي</a>
                                            </div>
                                        </div>
                                    @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    </main>

@endsection




@section('js')
    <script>
        $("#qayditemsTbl").dataTable({
            paginate : false
        });
    </script>

@endsection
