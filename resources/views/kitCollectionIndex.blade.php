@extends('layouts.master')
@section('css')

    <style>
        .itemimg:hover {
            width: 200px;
            height: 200px;
        }

        table {
            font-family: 'Droid Arabic Naskh', serif;
        }

        #partSpecsTbl tr {
            margin-top: 5px;
        }

        .modal-dialog {
            max-width: 80% !important;
        }

        @media screen and (max-width: 767px) {
            li.paginate_button.previous {
                display: inline;
            }

            li.paginate_button.next {
                display: inline;
            }

            li.paginate_button {
                display: none;
            }
        }

        #staticBackdrop .modal-dialog {
            max-width: 80% !important;
        }

        /**************************************/
        .radio-button-container {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .radio-button {
            display: inline-block;
            position: relative;
            cursor: pointer;
        }

        .radio-button__input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .radio-button__label {
            display: inline-block;
            padding-left: 30px;
            margin-bottom: 10px;
            position: relative;
            font-size: 15px;
            color: black;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .radio-button__custom {
            position: absolute;
            top: 0;
            left: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #555;
            transition: all 0.3s ease;
        }

        .radio-button__input:checked+.radio-button__label .radio-button__custom {
            background-color: #4c8bf5;
            border-color: transparent;
            transform: scale(0.8);
            box-shadow: 0 0 20px #4c8bf580;
        }

        .radio-button__input:checked+.radio-button__label {
            color: #4c8bf5;
        }

        .radio-button__label:hover .radio-button__custom {
            transform: scale(1.2);
            border-color: #4c8bf5;
            box-shadow: 0 0 20px #4c8bf580;
        }






        /***************************************/
    </style>
    
    <style>
       
    .wrap{
        display:block !important;
    }



    </style>
@endsection
@section('title')
    الكيت
@stop


@section('content')



    <div class="main-content">
        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Kits</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">KitCollection</li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">

                    <button class="btn btn-primary m-1 float-left " data-bs-toggle="modal" data-bs-target="#staticBackdrop">تجميع</button>
                                        <a  href="/availbleKits" class="btn btn-info m-1 float-left " >الأطقم المتاح تجميعها</a>


                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body table-responsive ">
                            <table id="outkitTbl"class="table table-striped table-bordered cell-border kitTbl" >
                                <thead style="background:#5fcee78a">
                                    <tr>
                                        <th>الاسم</th>
                                        <th>المنشأ</th>
                                        <th>الحالة</th>
                                        <th>الجودة</th>
                                        <th>الكمية</th>
                                        <th>الأصناف</th>
                                        <th>الأقسام</th>
                                        <th>فك كيت</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allkits as $allkit)
                                        <tr>
                                            
                                                
                                               <td> {{ $allkit->kit->name }}</td>
                                                <td> {{ isset($allkit->source) ? $allkit->source->name_arabic : '--' }}</td>
                                                <td> {{ isset($allkit->status) ? $allkit->status->name : '--' }}</td>
                                               <td>  {{ isset($allkit->part_quality) ? $allkit->part_quality->name : '--' }}</td>

                                            
                                            <td>{{ $allkit->amount }}</td>
                                            <td>
                                                <ul>
                                                    @foreach ($allkit->kit->kit_parts as $kitpart)
                                                        <li>{{ $kitpart->amount }} X {{ $kitpart->part->name }} </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>
                                                 <ul>
                                                    @foreach ($allkit->sectionswithoutorder as $section)
                                                        <li>( {{ $section->amount }} ) {{ $section->store_structure->name }} </li>
                                                    @endforeach
                                                 </ul>
                                            </td>
                                                <td>
                                                    <a href="fakkit/{{ $allkit->kit->id }}/{{ $allkit->source->id }}/{{ $allkit->status->id }}/{{ $allkit->part_quality->id }}"  ><i class="bx bx-collection fs-22 text-dark"></i></a>
                                                </td>
                                                    
                                                     
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            
            
               <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body table-responsive ">
                            <table id="outkitTbl2"class="table table-striped table-bordered cell-border kitTbl" >
                                <thead style="background:#5fcee78a">
                                    <tr>
                                        <th>الاسم</th>
                                        <th>المنشأ</th>
                                        <th>الحالة</th>
                                        <th>الجودة</th>
                                        <th>الكمية</th>
                                        <th>الأصناف</th>
                                        <th>الأقسام</th>
                                        <th>طباعة المكونات</th
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allkits2 as $allkit22)
                                        <tr>
                                            
                                                
                                               <td> {{ $allkit22->kit->name }}</td>
                                                <td> {{ isset($allkit22->source) ? $allkit22->source->name_arabic : '--' }}</td>
                                                <td> {{ isset($allkit22->status) ? $allkit22->status->name : '--' }}</td>
                                               <td>  {{ isset($allkit22->part_quality) ? $allkit22->part_quality->name : '--' }}</td>

                                            
                                            <td>{{ $allkit22->amount }}</td>
                                            <td>
                                                <ul>
                                                    @foreach ($allkit22->kit->kit_parts as $kitpart)
                                                        <li>{{ $kitpart->amount }} X {{ $kitpart->part->name }} </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>
                                                 <ul>
                                                    @foreach ($allkit22->sectionswithoutorder as $section)
                                                        <li>( {{ $section->amount }} ) {{ $section->store_structure->name }} </li>
                                                    @endforeach
                                                 </ul>
                                            </td>
                                               
                                                    
                                              <td>
                                                <a href="/printkitpartsection/{{ $allkit22->order_supplier_id }}"  ><i class="bx bx-archive-out fs-22 text-dark"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 d-none">
                    <ul class="list-group m-4" id="listDesc">
                        <li class="list-group-item">An item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                        <li class="list-group-item">A fourth item</li>
                    </ul>
                </div>

                <div class="col-lg-4 mt-4 d-none">
                    <div class="row">
                        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#"> xx </button>
                        <button class="btn btn-info mt-2" data-bs-toggle="modal" data-bs-target="#"> xx </button>
                        <button class="btn btn-info mt-2" data-bs-toggle="modal" data-bs-target="#"> xx </button>
                        <button class="btn btn-info mt-2" data-bs-toggle="modal" data-bs-target="#"> xx </button>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> تجميع كيت
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="radio-button-container justify-content-center">
                                <div class="radio-button d-none">
                                    <input type="radio" class="radio-button__input" id="radio1" value ="0"
                                        name="eventTypeRdio">
                                    <label class="radio-button__label" for="radio1">
                                        <span class="radio-button__custom"></span>
                                        New
                                    </label>
                                </div>
                                <div class="radio-button">
                                    <input type="radio" checked class="radio-button__input" value ="1" id="radio2"
                                        name="eventTypeRdio">
                                    <label class="radio-button__label" for="radio2">
                                        <span class="radio-button__custom"></span>
                                        Collection
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row border p-3" id="tagKit">
                        <div class="col-lg-6">
                            <h2 class="bg-body">أختر الكيت المراد تجميعه   </h2>

                            <select class="form-select" name="" id="collKitSlct">
                                <option value="">-Select Kit-</option>
                                @foreach ($kits as $kit)
                                    <option value="{{ $kit->id }}" data-kitPart="{{ $kit->kit_parts }}">
                                        {{ $kit->name }}</option>
                                @endforeach
                            </select>
                            <br>
                            <select name="SupplierSlct" class="mt-2 d-none" id="SupplierSlct">

                                <option value="">-Select Supplier-</option>
                                @foreach ($allsupplier as $sup)
                                    <option value="{{ $sup->id }}"> {{ $sup->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6" style="height: auto; overflow: auto; ">
                            <h2 class="bg-body">مكونات الكيت المراد تجميعه   </h2>

                            <table id="kitCAmount" class="table table-striped table-bordered cell-border " style="width:100%">
                                <thead style="background:#5fcee78a">
                                    <tr>
                                        <td>Amount</td>
                                        <td>Part Name</td>
                                        <td>Sourc</td>
                                        <td>Status</td>
                                        <td>Quality</td>

                                        {{-- <td><i class="mdi mdi-delete text-danger"></i></td> --}}
                                    </tr>
                                <tbody></tbody>
                                </thead>

                            </table>
                        </div>
                    </div>
                    <div class="border p-3 row" id="newKit" style="display:none">
                        <div class="col-lg-3">
                            <input type="text" class="form-control" placeholder="Kit Name">
                        </div>
                        <div class="col-lg-3">
                            <input class="form-control" type="text" placeholder="KitNumber" id="kitNumberTxt">
                        </div>
                        <div class="col-lg-3">
                            <select class="form-select SupplierSlct" name="" id="SupplierSlctnew">
                                <option value="">-Select Supplier-</option>
                                @foreach ($allsupplier as $sup)
                                    <option value="{{ $sup->id }}"> {{ $sup->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3" style="height: 300px; overflow-y: scroll; ">
                            <table id="kitCNumbers" class="table table-striped table-bordered cell-border " >


                            </table>
                        </div>
                    </div>
                    <div class="p-2 row  text-center ">
                        <div class="col-lg-6" style="height: 300px; overflow-y: scroll; ">
                            <h2 class="bg-body">موجودات المخازن من الكيـــــــت</h2>
                            <table class="table table-striped table-bordered cell-border " >
                                <thead style="background:#5fcee78a">
                                    <tr>
                                        <th>المخزن</th>
                                        <th>الكمية الموجودة</th>
                                    </tr>

                                </thead>
                                <tbody id="kitInStores">

                                </tbody>
                            </table>
                        </div>

                        <div class="col-lg-6 border-start-groove" style="height: 300px; overflow-y: scroll; ">
                            <h3 id="responseMessage" class="bg-body"></h3>
                            <span id="mintxt" class="fs-19"></span>
                            <hr>
                            <table class="table table-bordered" id="responseTbl">

                            </table>
                        </div>

                    </div>
                    <div class="row mt-3" id="StoreContainer">


                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12 table-responsive">
                            <h3 class="text-center"> كل الأصناف بالمخازن </h3>
                            <table id="allpartsTbl" class="dataTable m-0 no-footer p-0 table text-center w-100">
                                <thead>
                                    <tr>
                                        <td>Name</td>
                                        <td>Amount</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allparts as $part)
                                        <tr>
                                            <td>
                                                {{ $part->part->name }}
                                            </td>
                                            <td>
                                                {{ $part->amount }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn mb-2 btn-primary">حفظ</button>
                    <button type="button" class="btn mb-2 btn-secondary" data-bs-dismiss="modal">غلق</button>
                </div>


            </div>
        </div>
    </div>




@endsection

@section('js')

    <script>
        var kittable = '';
        $(document).ready(function() {
            kittable = $("#outkitTbl").DataTable({
                //  dom: '<"dt-buttons"Bf><"clear">lirtp',
                dom: "Bfrltip",
                // dom: "Bfrtip",
                "paging": true,
                "ordering": true,
                "aaSorting" : [[0, 'desc']],
                lengthMenu: [[10, 25, 50, 100,-1], [10, 25, 50,100, "All"]],
                 buttons: [
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [6,5,4,3,2,1,0],
                             stripHtml: false
                        }
                    },
                ],
            });
            kittable2 = $("#outkitTbl2").DataTable({
                //  dom: '<"dt-buttons"Bf><"clear">lirtp',
                dom: "Bfrltip",
                // dom: "Bfrtip",
                "paging": true,
                "ordering": true,
                "aaSorting" : [[0, 'desc']],
                lengthMenu: [[10, 25, 50, 100,-1], [10, 25, 50,100, "All"]],
                 buttons: [
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [6,5,4,3,2,1,0],
                             stripHtml: false
                        }
                    },
                ],
            });
            $("#allpartsTbl").dataTable({
                "ordering": true, // Ordering (Sorting on Each Column)will Be Disabled

                "info": false, // Will show "1 to n of n entries" Text at bottom

                "lengthChange": false // Will Disabled Record number per page
            });



        });



        $("#SupplierSlctnew").change(function(e) {
            e.preventDefault();
            var kitNumber = $("#kitNumberTxt").val();
            var SupplierName = $("#SupplierSlctnew option:selected").text();
            if (kitNumber != "") {
                $("#kitCNumbers").append(`
            <tr>
                <td>${kitNumber}</td>
                <td>${SupplierName}</td>
                <td><i class="mdi mdi-delete"></i></td>
            </tr>
            `);
            }

        });

        $('input[type=radio][name=eventTypeRdio]').change(function() {
            if (this.value == '0') {
                // new
                $("#newKit").show();
                $("#tagKit").hide();

            } else if (this.value == '1') {
                // collect
                $("#newKit").hide();
                $("#tagKit").show();
            }
        });
        var current;
        $('#collKitSlct').change(function() {
            // console.log(JSON.parse($("#collKitSlct option:selected" ).attr('data-kitPart')));
            $.ajax({
                type: "GET",
                url: "/kitInfo",
                data: {
                    "kit_id": $(this).val()
                },
                success: function(response) {
                    console.log(response);
                    current = response;
                    if (response.component.length > 0) {
                        $("#kitCAmount tbody").empty();
                        $("#responseTbl").empty();
                        response.component.forEach(element => {
                            var source = '-';
                            var status = '-';
                            var quality = '-';
                            if (element.source_id != null) {
                                source = element.source.name_arabic;
                            }
                            if (element.status_id != null) {
                                status = element.status.name;
                            }
                            if (element.quality_id != null) {
                                quality = element.part_quality.name;
                            }
                            $("#kitCAmount tbody").append(`
                                <tr>
                                    <td>${element.amount}</td>
                                    <td>${element.part.name} </td>
                                    <td>${source}</td>
                                    <td>${status}</td>
                                    <td>${quality}</td>


                                </tr>
                            `);
                        });

                        $("#responseMessage").text(response.message);
                        if (response.min > 0) {
                            $("#mintxt").text('بإجمالي (' + response.min + ')' + 'كيت');
                            // $(".collectBtn").show();
                        } else {
                            $("#mintxt").text('');
                        }
                        var row = '';
                        var table = $("#responseTbl")[0];
                        if (response.parts) {
                            response.parts.forEach(element => {
                                var row = table.insertRow();
                                var cell1 = row.insertCell(0);
                                cell1.innerHTML = element[0].part.name;
                                cell1.classList.add('pt-5');
                                var cell2 = row.insertCell(1);
                                var xx =
                                    '<table class="table table-striped table-bordered cell-border "> <thead style="background:#5fcee78a"><tr> <td>source</td> <td>status</td> <td>quality</td> <td>Sum</td> <td>Available</td></tr></thead><tbody>';
                                element.forEach(elementx => {
                                    xx += `<tr>
                                            <td>${elementx.source.name_arabic}</td>
                                            <td>${elementx.status.name}</td>
                                            <td>${elementx.part_quality.name}</td>
                                            <td>${elementx.amount}</td>
                                            <td>${elementx.available}</td>
                                        </tr>`;
                                });
                                xx += '</tbody></table>';
                                cell2.innerHTML = xx;
                            });
                        }
                        $("#StoreContainer").empty();
                        if (response.Stores) {
                            var innStore = '';
                            response.Stores.forEach(element => {
                                innStore += `<div class="col-lg-12 card fs-18 mt-3">
                                    <span class="bg-body border p-1">عدد الأصناف المتاحة من الكيت : <span class="border border-2 border-danger border-outset px-2 rounded-circle">${element.partIn.length}</span>  <span class="fs-19 mx-4">${element.name}</span><span class="btn mdi mdi-arrow-collapse-vertical btn-dark float-end close-icon" data-effect="fadeOut"><i class="fa fa-times"></i></span></span>
                                    <div class="storeItmContainer">`;
                                if (element.partIn.length > 0) {
                                    element.partIn.forEach(part => {
                                        part.forEach(partx => {
                                            var priceinEGY = checkcurrency(partx
                                                .price.price, partx.price
                                                .order_supplier
                                                .currency_type.id, partx
                                                .price.order_supplier
                                                .currency_type.currencies)
                                            innStore += `
                                                <div class="border-0 border-bottom mb-1 row itemContain">
                                                    <input type="hidden" value="${element.partIn.length}" name="kitNo" class="kitNo">
                                                    <div class="col-lg-2">${partx.part.name}</div>
                                                    <div class="col-lg-1">${partx.source.name_arabic}</div>
                                                    <div class="col-lg-1">${partx.status.name}</div>
                                                    <div class="col-lg-2">${partx.quality.name}</div>
                                                    <div class="col-lg-2">${partx.amount}</div>
                                                    <div class="col-lg-2">${priceinEGY}  جنيه مصري </div>
                                                    <div class="col-lg-2">
                                                         <input class="border border-2 border-success form-check-input p-2 radBtn" data-price="${priceinEGY}" data-currency="${partx.price.order_supplier.currency_type.name}"  data-part_id="${partx.part.id}" data-source_id="${partx.source.id}" data-status_id="${partx.status.id}" data-quality_id="${partx.quality.id}"  data-avialable="${partx.amount}" type="radio" name="${partx.part.name}" id="">
                                                    </div>


                                                </div>
                                                `;
                                        })

                                    });

                                }
                                innStore += '</div>';
                                if (element.partIn.length > 0) {

                                    innStore +=
                                        `<div class="col-lg-12 w-100 collectBtn" >
                                    <div class="col-lg-2 d-inline-block">عدد الكيت المطلوب تجميعها </div>
                                    <input class="form-control d-inline-block neededKitTxt" id="neededKitTxt" type="number" min="1" value="0" >
                                    <span >Total Buy Price / Kit </span><span class="totalbuyPrice"></span>
                                    <div class="row kitmetaContainer" style="display:none;">
                                        <div class="col">
                                            <select name="SupplierSlctst" class="mt-2 SupplierSlctst" id="" >

                                            <option value="">-Select Supplier-</option>
                                            @foreach ($allsupplier as $sup)
                                            <option value="{{ $sup->id }}"> {{ $sup->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="col">
                                            <select name="sourcest" class="mt-2 sourcest" id="" >

                                            <option value="">-Select Source-</option>
                                            @foreach ($allsources as $sou)
                                            <option value="{{ $sou->id }}"> {{ $sou->name_arabic }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="col">
                                            <select name="statusst" class="mt-2 statusst" id="" >

                                                <option value="">-Select Status-</option>
                                                @foreach ($allstatus as $stu)
                                                <option value="{{ $stu->id }}"> {{ $stu->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col">
                                            <select name="qualityst" class="mt-2 qualityst" id="" >

                                                <option value="">-Select Quality-</option>
                                                @foreach ($allquality as $qu)
                                                <option value="{{ $qu->id }}"> {{ $qu->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <button class="btn btn-dark btn-sm w-100 collectionBtn" data-storeId=${element.id}>تجميع</button></div>`;
                                }

                                innStore += '</div>';

                            })

                            $("#StoreContainer").append(innStore);

                            // $(".itemContain").find(".kitNo").get().forEach(ele =>{
                            //   if(parseInt($(ele).val()) >= response.component.length){
                            //         $(ele).find('.collectBtn').show();
                            //     }
                            // })


                        }

                        $('#kitInStores').empty();
                        if (response.kitInstores.length > 0) {

                            response.kitInstores.forEach(element => {
                                $('#kitInStores').append(
                                    `<tr><td>${element.name}</td><td>${element.storepartCount}</td><tr>`
                                    )
                            });

                        } else {
                            $('#kitInStores').append(`<tr><td>No Data </td><td>No Data</td><tr>`)
                        }
                    }
                }
            });
        })

        $("#collKitSlct").select2({
            dropdownParent: $('#staticBackdrop')
        });


        $(document).on('click', '.close-icon', function() {
            $(this).parent().next('.storeItmContainer').toggle();
            $(this).parent().next().next('.collectBtn').toggle();

        })
        $(document).on('click', '.collectionBtn', function() {
            var neededKitTxt = $(this).parent().find('.neededKitTxt').val();
            var store_id = $(this).attr('data-storeid');
            var checkedItem = $(this).closest('.card').find('input:radio.radBtn:checked');
            var itemInKit = [];
            var itemInKitNotavailable = 0;
            checkedItem.get().forEach(ele => {

                var part_id = $(ele).attr('data-part_id');
                var source_id = $(ele).attr('data-source_id');
                var status_id = $(ele).attr('data-status_id');
                var quality_id = $(ele).attr('data-quality_id');
                var available = $(ele).attr('data-avialable');
                var priceEG = parseFloat($(ele).attr('data-price'));

                var amount = (current.component.find(x => x.part_id == part_id)).amount;
                var Pname = (current.component.find(x => x.part_id == part_id)).part.name;
                const obj = {
                    part_id: part_id,
                    source_id: source_id,
                    status_id: status_id,
                    quality_id: quality_id,
                    amount: amount,
                    store_id: store_id,
                    price: priceEG
                };
                if (parseInt(available) >= amount * parseInt(neededKitTxt)) {
                    itemInKit.push(obj);
                } else {
                    itemInKitNotavailable++;
                }

            })
            // itemInKit['kit_id']=$('#collKitSlct').val();
            // itemInKit['storeId']=store_id;
            // itemInKit['neededKit']=parseInt(neededKitTxt);

            var Kitsupplier = $(this).parent().find('.kitmetaContainer').find('.SupplierSlctst').val();
            var Kitsource = $(this).parent().find('.kitmetaContainer').find('.sourcest').val();
            var Kitstatus = $(this).parent().find('.kitmetaContainer').find('.statusst').val();
            var Kitquality = $(this).parent().find('.kitmetaContainer').find('.qualityst').val();
            var KitpriceEGY = parseFloat($(this).parent().find('.totalbuyPrice').text());

            if (itemInKit.length == current.component.length && itemInKitNotavailable == 0) {
                if (parseInt(neededKitTxt) <= current.min && parseInt(neededKitTxt) > 0) {
                    // insert
                    console.log(itemInKit);
                    if (Kitsupplier > 0 && Kitsource > 0 && Kitstatus > 0 && Kitquality > 0) {
                        $.ajax({
                            type: "POST",
                            url: "/save_kit_collection",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'kitItems': itemInKit,
                                'kit_id': $('#collKitSlct').val(),
                                'storeId': store_id,
                                'neededKit': parseInt(neededKitTxt),
                                'source': Kitsource,
                                'status': Kitstatus,
                                'quality': Kitquality,
                                'buyPrice': KitpriceEGY,
                                'supplier': Kitsupplier,
                            },
                            success: function(response) {
                                console.log(response)
                                if (response) {
                                    
                                      if (response.redirect_url) {
                                        window.location.href = response.redirect_url;
                                    }
                                    // alert("New Kit Successfully Added")

                                    // Swal.fire({
                                    //     text: "New Kit Successfully Added",
                                    //     icon: "success",
                                    //     buttonsStyling: false,
                                    //     confirmButtonText: "Ok, got it!",
                                    //     customClass: {
                                    //         confirmButton: "btn fw-bold btn-primary",
                                    //     }
                                    // })
                                } else {
                                    alert("error")
                                }
                            }
                        });
                    } else {
                        // alert('برجاء ادخال المورد والحالة ')

                        Swal.fire({
                            text: " برجاء ادخال المورد والحالة " ,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }

                } else {
                    // alert("لايمكن تجميع   ( " + neededKitTxt + " )  كيت " + ' المتاح ( ' + current.min + ' )')

                    Swal.fire({
                            text: "لايمكن تجميع   ( " + neededKitTxt + " )  كيت " + ' المتاح ( ' + current.min + ' )',
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                }


            } else {

                // alert("لا يمكن نجميع كيت برجاء مراجعة الأصناف والكميات برجاء مراجعة باقي المخازن لتوفير الكمية المطلوبة");

                Swal.fire({
                            text: "لا يمكن نجميع كيت برجاء مراجعة الأصناف والكميات برجاء مراجعة باقي المخازن لتوفير الكمية المطلوبة",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
            }

            // console.log(checkedItem);
            // current.component.find(x=> x.part_id==407)
            // alert(neededKitTxt)

        })

        $(document).on('keyup', "#neededKitTxt", function(e) {
            var checkedItem = $(this).closest('.card').find('input:radio.radBtn:checked');
            var buytot = 0;
            checkedItem.get().forEach(ele => {
                var price = parseFloat($(ele).attr('data-price'));
                var part_id = $(ele).attr('data-part_id');
                var amount = (current.component.find(x => x.part_id == part_id)).amount;

                buytot += price * amount;


            });

            $(this).parent().find(".totalbuyPrice").text(buytot);
            $(this).parent().find(".kitmetaContainer").show();

            $(this).parent().find(".SupplierSlctst").select2({
                dropdownParent: $(this).parent()
            });
            $(this).parent().find(".sourcest").select2({
                dropdownParent: $(this).parent()
            });
            $(this).parent().find(".statusst").select2({
                dropdownParent: $(this).parent()
            });
            $(this).parent().find(".qualityst").select2({
                dropdownParent: $(this).parent()
            });
        });

        function checkcurrency(price, currency_id, lastpriceArr) {
            return lastpriceArr[lastpriceArr.length - 1].value * price;
        }
        // $(document).on('click','#outkitTbl tr',function (e) {
        //     e.preventDefault();
        //     var row = $(this);
        //     var kitparts = JSON.parse(row.attr('data-kitpart'));
        //     $("#listDesc").empty();
        //     kitparts.forEach(element => {
        //         $("#listDesc").append(`<li class="list-group-item">${element.kit_id}</li>`);
        //     });

        // });
    </script>





@endsection
