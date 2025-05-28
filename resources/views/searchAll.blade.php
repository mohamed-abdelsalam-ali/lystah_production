@extends('layouts.master')
@section('css')


@endsection
@section('title')
   Search
@stop


@section('content')



    <div class="main-content ">
        <div class="page-content">
            <div class="row">


                <div class="col-lg-12">
                    <table class="table">
                        @foreach($allItems as $key => $value)
                            <tr>
                                <td>
                                    <a href="/partDetails/{{$value->type_id}}/{{$value->id}}"> {{$value->name}} </a>
                                </td>

                            </tr>
                        @endforeach

                    </table>
                    <?php
                        $currentpage =  isset($_GET['page']) ? $_GET['page'] : 1;
                        $nextpage = $currentpage+1;
                        $prevoispage = ($currentpage > 1) ? $currentpage-1 : 1;
                    ?>
                    <button class="btn w-100" onclick="nextpage(<?php echo $prevoispage ?>)">Previous Page</button>
                    <button class="btn w-100" onclick="nextpage(<?php echo $nextpage ?>)">Next Page</button>
                </div>
            </div>


        </div>
    </div>


@endsection

@section('js')

<script>
    function nextpage(pageno){
       let url = new URL(window.location.href);
        let searchParams = new URLSearchParams(url.search);

        // Set the 'page' parameter to '2'
        searchParams.set('page', pageno);

        // Update the URL with the modified query parameters
        url.search = searchParams.toString();

        // var newString ='?page='+pageno;
        // window.history.pushState(null, null, newString);
        window.location.href = url.toString();
        // location.reload();
    }
</script>

@endsection
