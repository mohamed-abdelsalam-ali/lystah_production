@extends('layouts.master')
@section('css')


    <style>
        table {
            font-family: 'Droid Arabic Naskh', serif;
        }



        .modal-dialog {
            max-width: 1000px !important;
        }

        @media print {

            select,
            button,
            .noprint {
                display: none !important;
            }

        }
    </style>
@endsection
@section('title')
    Pricing
@stop


@section('content')



    <div class="main-content">
        <div class="page-content">
            <h1 class="text-center text-info">. التسعــــــــيرة</h1>
            <div class="card">
                <div class="row">
                    <div class="col-lg-12 text-left">
                        <button class="btn text-bg-success" type="button" data-bs-toggle="modal"
                            data-bs-target="#staticBackdrop">Currency</button>
                        <a class="btn text-bg-info" href="/simplePriceList" type="button">عرض التسعيرة</a>
                        <a class="btn text-bg-danger" href="{{ URL::route('pricingwith_flag', ['id' => 0]) }}"
                            id="" class="btn text-bg-danger  w-100"> القطع التى لم تسعر </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-check form-switch fs-22 text-center">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" checked onchange="toggleLabel()">
                            <label class="form-check-label" for="flexSwitchCheckDefault" id="switchLabel">طبقا لاخر سعر شراء</label>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <form method="POST" id="myForm" action="/saveAllPriceB1">
                            @csrf
                            @method('POST')
                            <table class="mt-3 table table-sm table-striped">
                                <tr>
                                    <td>النـــــوع</td>
                                    <td>
                                        <select name="part_type" class="form-select" id="part_type">
                                            <option value="1">قطع غيار</option>
                                            <option value="2"> الكاوتش</option>
                                            <option value="3"> جرار</option>
                                            <option value="4"> كلارك</option>
                                            <option value="5"> معدات</option>
                                            <option value="6"> اطقم اصلاح / كيت</option>
                                        </select>
                                    </td>
                                </tr>
                                @foreach ($pricingTypes as $key => $value)
                                    <tr>
                                        <input type="hidden" name="type_id[]" value="{{ $value->id }}">
                                        <td>{{ $value->type }}</td>
                                        <td><input type="number" name="values[]" value="0" class="form-control"
                                                id=""></td>
                                    </tr>
                                @endforeach
                            </table>
                            <button type="submit" class="w-100 btn btn-success">Save</button>
                        </form>
                    </div>
                    <div class="col-lg-12" id="notess" style="display: none">
                        <p class="fs-22 px-5">
                            في حال تسعير لأول مرة يتم حساب السعر كالأتي : اخر سعر شراء * سعر العملة + النسبة المئوية

                        </p>
                        <p class="fs-22 px-5">
                            في حال يوجد تسعيير للأصناف يتم حساب السعر كالاتي : اخر سعر بيع + النسبة المئوية
                        </p>
                    </div>
                    <div class="col-lg-12">
                        <h2 class="text-center">أخر نسب للتسعير من سعر الشراء</h2>
                        <table class="table" id="refrenceTable">

                        </table>
                    </div>

                </div>
            </div>
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">العمــــــلات</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="GET" action="/updatecurrcy" enctype="multipart/form-data">
                            @csrf
                            @method('GET')
                            <div class="modal-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    @foreach ($currencyHistory as $currency)
                                        <tr>
                                            <td>
                                                {{ $currency->currency_type->name }}
                                                <input type="hidden" name="curency[]" value="{{ $currency->currency_id }}">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" name="price[]"
                                                    value="{{ $currency->value }}" id="">
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="static1Backdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">الأنـــــواع</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="/pricingAll" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="modal-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    @foreach ($pricingTypes as $pricingType)
                                        <tr>
                                            <td>
                                                {{ $pricingType->type }}
                                                <input type="hidden" name="pricingType[]" value="{{ $pricingType->id }}">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" name="pricepercent[]"
                                                    value="0" id="">
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>



@endsection

@section('js')

    <script>
     $(document).ready(function(){
            type_id=   $('#part_type').val();
            get_pricing_ratio(type_id);
        });
    function get_pricing_ratio(type_id){
            // alert(type_id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                //ajax: "itemsStore/list/" + store_data[0].id,
                url: "get_pricing_ratio/"+type_id,

                datatype: 'JSON',
                statusCode: {
                    404: function() {
                        alert("page not found");
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    // alert("some error");
                    console.log(errorThrown);
                },
                success: function(data) {
                    console.log(data);
                    $("#refrenceTable").empty();
                    if(data.length > 0){
                        data.forEach(element => {
                            $("#refrenceTable").append(`<tr><td>${element.pricing_type.type}</td><td>${element.value.toFixed()} % </td></tr>`);
                        });
                    }else{
                         $("#refrenceTable").append(`<tr><td>لا يوجد تسعيرة </td></tr>`);
                    }

                    // SuccessAlert("Transaction Accepted" )



                }
            });
        }
    function toggleLabel() {
        var checkbox = document.getElementById('flexSwitchCheckDefault');
        var label = document.getElementById('switchLabel');

        if (checkbox.checked) {
            label.textContent = 'طبقا لاخر سعر شراء';
            $("#notess").hide();
            $('#myForm').attr('action', '/saveAllPriceB1');
        } else {

            label.textContent = 'طبقا لاخر سعر بيع';
            $("#notess").show();
            $('#myForm').attr('action', '/saveAllPrice');
        }
    }
    $(document).on("submit", "form", function(e) {
            var oForm = $(this);
            $("#preloader").css({
                'opacity': '1',
                'visibility': 'visible'
            });

        })
        
    $(document).on('change','#part_type',function(){
            type_id=   $(this).val();
            get_pricing_ratio(type_id);
        })
    </script>


@endsection
