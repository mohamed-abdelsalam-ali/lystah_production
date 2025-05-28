@extends('layouts.master')
@section('css')
<style>
 
.brand-icons{
    display: flex;
    flex-wrap: nowrap; /* Keep images in one row */
    overflow-x: auto; /* Enable horizontal scrolling */
    scroll-behavior: smooth; /* Smooth scrolling */
    white-space: nowrap;
    gap: 10px; /* Space between images */
    padding: 10px; /* Optional padding */
    
    /* Hide scrollbar */
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* Internet Explorer 10+ */
}
.model-icons {
    display: flex;
    flex-wrap: nowrap; /* Keep images in one row */
    overflow-x: auto; /* Enable horizontal scrolling */
    scroll-behavior: smooth; /* Smooth scrolling */
    gap: 10px; /* Space between images */
    padding: 10px;
    
    /* Hide scrollbar */
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* Internet Explorer 10+ */
}
.model-icons::-webkit-scrollbar {
    display: none; /* Chrome, Safari */
}

.model-container {
    position: relative;
    display: inline-block;
    text-align: center;
    flex-shrink: 0;
}

.Modelimg {
    max-height: 150px; /* Adjust size */
    display: block;
    width: 100%;
    border-radius: 8px;
}

.model-container a {
    position: absolute;
    bottom: 10px; /* Adjust vertical position */
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.6); /* Semi-transparent background */
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    font-size: 11px;
    white-space: nowrap; /* Prevent text wrapping */
}

.model-container a:hover {
    background: rgba(0, 0, 0, 0.8);
}
.scroll-btn {
    background-color: #000; /* Black background */
    color: white;
    border: none;
    padding: 10px 15px;
    font-size: 20px;
    cursor: pointer;
    border-radius: 50%;
    transition: 0.3s;
}

.scroll-btn:hover {
    background-color: #444; /* Darker hover effect */
}

.prev-btn {
    left: 0;
}

.next-btn {
    right: 0;
}
</style>
@endsection

@section('title')
    Emara
@stop

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="card">
                <div class="card-body">
                    <div class="col-12 px-1 m-3 text-end">
                    </div>

                    <!-- Navbar List -->
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="list-group list-group-horizontal justify-content-center fs-18">
                                <li class="list-group-item border-0">
                                    <a href="">Make/Model</a>
                                </li>
                                <li class="list-group-item border-0">
                                    <a href="">Accessories</a>
                                </li>
                                <li class="list-group-item border-0">
                                    <a href="">Trailer</a>
                                </li>
                                <li class="list-group-item border-0">
                                    <a class="text-danger" href="">NEW</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="row border border-0 border-top mt-3">
                        <div class="col-lg-12 d-flex align-items-center">
                            <button class="scroll-btn prev-btn me-2" onclick="scrollModels(-1)">❮</button> 
                    
                            <div class="model-icons" id="modelIcons">
                                @forelse ($models as $model)
                                    <div class="model-container">
                                        <img class="img-fluid border Modelimg" src="model_images/{{ $model->mod_img_name }}" title="{{ $model->name }}" alt="{{ $model->name }}">
                                        <a href="model/{{ $model->id }}">{{ $model->brand->name }} - {{ $model->name }}</a>
                                    </div>
                                @empty
                                    <p>No models available</p>
                                @endforelse
                            </div>
                    
                            <button class="scroll-btn next-btn ms-2" onclick="scrollModels(1)">❯</button> 
                        </div>
                    </div>

                    <div class="row border border-0 border-top mt-3">
                        <div class="col-lg-12">
                            <ul class="man_holder_2016 brand-icons" id="brandIcons">
                                @forelse ($brands as $brand)
                                    <li class="ci p-3 border border-1 fs-19">
                                        <a class="fw-bold" href="/brands/{{ $brand->id }}" title="{{ $brand->name }}">{{ $brand->name }}</a>
                                    </li>
                                    @empty
                                    <p>No Brand available</p>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
<script>
    document.getElementById("modelIcons").addEventListener("wheel", function(event) {
        event.preventDefault(); // Prevent vertical scrolling
        this.scrollLeft += event.deltaY; // Scroll horizontally
    });
    document.getElementById("brandIcons").addEventListener("wheel", function(event) {
        event.preventDefault(); // Prevent vertical scrolling
        this.scrollLeft += event.deltaY; // Scroll horizontally
    });
    document.addEventListener("DOMContentLoaded", function () {
        const colors = ["#FF5733", "#33B5E5", "#FFB400", "#76D7C4", "#D63384", "#A569BD", "#F4D03F", "#58D68D" ,"red" ,"Green" , "Blue" , "Orange"];
        document.querySelectorAll('.brand-icons .ci a').forEach((el) => {
            const randomColor = colors[Math.floor(Math.random() * colors.length)];
            el.style.color = randomColor;
        });
    });

    function scrollModels(direction) {
        const container = document.getElementById("modelIcons");
        const scrollAmount = 300; // Adjust scroll speed
        container.scrollLeft += direction * scrollAmount;
    }
</script>
@endsection
