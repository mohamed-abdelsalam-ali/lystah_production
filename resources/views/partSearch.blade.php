@extends('layouts.master')
@section('css')

    <style>
 
    


        @media (min-width:320px) {
            .filter-panel h2 {
                transform: rotate(0) !important;
            }

            .res img {
                width: 60px !important;
                height: 80px !important;

            }
 
        @media (min-width:480px) {
            .filter-panel h2 {
                transform: rotate(0) !important;
            }

            .res img {
                width: 60px !important;
                height: 80px !important;

            }
        }

        @media (min-width:600px) {
            .filter-panel h2 {
                transform: rotate(270deg) !important;
            }

            .res img {
                width: 135px !important;
                height: 135px !important;

            }
        }

        .select2-container--default .select2-selection--single {
            background-color: transparent;
        }

        .select2-search--dropdown {
            background-color: transparent !important;
        }

        .select2-search__field {
            background-color: transparent !important;
        }

        .select2-results {
            background-color: transparent !important;
        }

        input[type=search] {
            background-color: transparent !important;
        }

        .res:hover {
            background-color: #6d8a99;
            cursor: pointer;
            color: white !important;
        }

        /* [1] The container */
        .img-hover-zoom {
            /* height: 300px; [1.1] Set it as per your need */
            overflow: hidden;
            /* [1.2] Hide the overflowing of child elements */
        }

        /* [2] Transition property for smooth transformation of images */
        .img-hover-zoom img {
            transition: transform .5s ease;
        }

        /* [3] Finally, transforming the image when container gets hovered */
        .img-hover-zoom:hover img {
            transform: scale(1.5);
        }

        .res span {
            font-size: 100%;
        }
        #filterBtn {
  font-size: 15px;
  cursor: pointer;
  background-color: #111;
  color: white;
  padding: 4px 10px;
  border: none;
}

#filterBtn:hover {
  background-color: #444;
}
        
    </style>

@endsection
@section('title')
    PARTS
@stop


@section('content')

 

    <div class="main-content">
        <div class="page-content">
            {{-- <form action="customSearchResult" method="POST"> --}}

                {{-- @csrf --}}
                <button id="filterBtn" class="btn btn-info position-absolute" style="top: 61px;left: 54px; z-index: 88888;" type="button" onclick="toggleFilter()">Filter ☰ </button>
                <div class="row mx-3 ">

                    <div class="col-8 pt-1 mw-100 text-center border border-1 card resPanel">
                        <h1 class="w-100 d-none" id="resultTitletxt">Results</h1>
                        <div class="row">
                            <div class="col-lg-4 col-sm-4">
                                <input type="search" name="" class="form-control mb-2" placeholder="Search Name" id="searchNamebtn">
                            </div>
                            <div class="col-lg-4 col-sm-4">
                                <input type="search" name="" class="form-control mb-2" placeholder="Search Number" id="partNumberSearchTxt1">
                            </div>
                            <div class="col-lg-4 col-sm-4">
                                <input type="search" name="" class="form-control mb-2" placeholder="Search Result" id="searchresultDiv">
                            </div>
                        </div>

                        <select name="selectedStore" class="form-select" id="selectedStore">
                            <option value="0">Select Store</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                        <div class="row mx-2" id="resultDiv">

                        </div>
                    </div>
                    <div class="col-4 pb-5 border rounded position-fixed filterPanel"
                        style="background: #6d8a99;height:100vh;top:70px;left:0px" >

                        <div class="row text-white ">

                            <div class="col-lg-10 my-2">
                                <div class="row ">
                                    <div class="col">
                                        <button class="btn btn-dark m-2 w-100">Search</button>
                                        {{-- <button type="reset" class="btn btn-dark mx-2 w-100">Reset</button> --}}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <label for="">TYPE</label>
                                        <select name="brandType" class="form-select select2-dropdown" id="brandtypeSlct">
                                            <option value="" selected disabled>Choose Here</option>
                                            @foreach ($type as $element)
                                                <option value="{{ $element->id }}">{{ $element->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="">Brand</label>
                                        <select name="brand" aria-label="Default select example"
                                            class="form-select select2-dropdown" id="brandSlct">
                                            <option value="" selected disabled>Choose Here</option>
                                            @foreach ($brand as $element)
                                                <option value="{{ $element->id }}">{{ $element->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="">MODEL</label>
                                        <select name="model" class="form-select select2-dropdown" id="modelSlct">
                                            <option value="" selected disabled>Choose Here</option>
                                            @foreach ($model as $element)
                                                <option value="{{ $element->id }}">{{ $element->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="">SERIES</label>
                                        <select name="series" class="form-select select2-dropdown" id="seriesSlct">
                                            <option value="" selected disabled>Choose Here</option>
                                            @foreach ($series as $element)
                                                <option value="{{ $element->id }}">{{ $element->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="">NUMBER</label>
                                        <input type="search" value="0" class="form-control" name="number"
                                            id="partNumberSearchTxt">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="">SUPPLIER</label>
                                        <select name="supplier" class="form-select select2-dropdown" id="supplierSlct">
                                            <option value="" selected disabled>Choose Here</option>
                                            @foreach ($supplier as $element)
                                                <option value="{{ $element->id }}">{{ $element->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="">Group</label>
                                        <select name="group" class="form-select select2-dropdown" id="">
                                            <option value="" selected disabled>Choose Here</option>
                                            @foreach ($group as $element)
                                                <option value="{{ $element->id }}">{{ $element->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="">Sub Group</label>
                                        <select name="subgroup" class="form-select select2-dropdown" id="">
                                            <option value="" selected disabled>Choose Here</option>
                                            @foreach ($subgroup as $element)
                                                <option value="{{ $element->id }}">{{ $element->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div onclick="" class="col-lg-2 filter-panel ml-2 text-center d-flex align-items-center justify-content-center"
                                style="background:#496D80 ">
                                <h2 class="h-auto" style="transform: rotate(270deg)">FILTER</h2>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- </form> --}}

        </div>
    </div>


@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


    <script src="{{ URL::asset('js/partsearch.js') }}"></script>
    <script src="{{ URL::asset('js/basket.js') }}"></script>
    <script>
        $('select').select2();
        function toggleFilter(){

            $(".filterPanel").toggle();
            $(".resPanel").toggleClass("col-8");
        }
    </script>

@endsection
