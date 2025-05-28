@extends('layouts.master')
@section('css')


@endsection
@section('title')
    EMARA
@stop


@section('content')



    <div class="main-content">
        <div class="page-content">
            <h1 class="text-center text-info">حجم المخازن</h1>

            <div class="row" id="cardContainer">

            </div>


        </div>
    </div>



@endsection

@section('js')

    <script>
        var allstores = {!! $allstores !!};

        allstores.forEach(store => {
            var card = `
                <div class="col">
                <div class="card" >
                    <div class="card-header fs-20 fw-bold" style="background:#5fcee78a">
                        ${store.name}
                    </div>
            `;
            $.ajax({
                type: "get",
                async: false,
                url: "/get_stores_money/" + store.id,
                success: function(response) {
                    response.forEach(element => {
                        card += `
                                      <ul class="list-group ">
                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="w-100">
                                            <div style="cursor: pointer" onclick="toggledata(this)" class="border text-bg-light fs-20 fw-bold text-center">${element.curr}</div>

                        `;
                        card +=
                            `<div class="tog_container" style="display:block"><div class="fw-bold p-3"><span class="fs-4 text-decoration-underline">سعر الشراء : </span><span class="fs-4 fs-4 p-2">${Math.round(element.data.buy_price)}</span></div>`;
                        card +='<hr>';
                        card +='<table class="fw-bold table">';
                        for (let index = 0; index < element.data.sell_price.length; index++) {
                            const elementx = element.data.sell_price[index];
                            const elementVal = 'sale_val' + index;
                            // card +=`<div class="fw-bold"><span class="fs-4 ">${elementx.data[index][elementVal]} : </sapn>${elementx[elementVal]}</div>`;
                            card +=`<tr><td>${elementx.data[index][elementVal]} : </td><td>${Math.round(elementx[elementVal])}</td></tr>`;
                        }
                        card +='</table>';
                        card += ` </div></div> </li> </ul>`;
                    });
                }
            });

            card += ` </div> </div> </div>`;

            $("#cardContainer").append(card);
        });
        function toggledata(el){
            $(el).parent().find('.tog_container').toggle()
        }
    </script>


@endsection
