@extends('layouts.master')
@section('title')
    تقرير حركات القطعة
@stop
@section('css')

    <style>
        .card-container,
        .card-box,
        .card-head,
        .card-number-row,
        .card-details,
        .card-holder-col,
        .card-date-col {
            display: flex;
        }

        .card-container {
            align-items: center;
            justify-content: center;
        }

        .card-box {
            width: 300px;
            min-height: 160px;
            background: #fff;
            box-shadow: 0px 0px 15px -2px rgba(0, 0, 0, 0.1);
            border-radius: 18px;
            margin: 16px;
            padding: 1.5em;
            flex-direction: column;
            justify-content: space-around;
            gap: 1.2em;
        }

        .card-head {
            justify-content: space-between;
            align-items: center;
        }

        .card-chip svg {
            width: 32px;
            height: 32px;
        }

        .card-chip svg path {
            fill: #636363;
        }

        .card-logo svg {
            width: 48px;
            height: 48px;
        }

        .card-number-row {
            justify-content: center;
            word-spacing: 1em;
            font-size: 1.3em;
            font-weight: 600;
        }

        .card-box:hover .card-number-row {
            font-size: 1.32em;
        }

        .card-details {
            justify-content: space-between;
            text-transform: uppercase;
        }

        .card-holder-col {
            flex-direction: column;
            gap: 2px;
        }

        .card-holder-title,
        .card-date-title {
            color: #bdbdbd;
            font-size: 0.7em;
        }

        .card-holder-name {
            font-size: 1.1em;
            font-weight: 600;
        }

        .card-date-col {
            flex-direction: column;
            align-items: flex-end;
            gap: 2px;
        }





        ul.timeline {
            list-style-type: none;
            position: relative;
        }

        ul.timeline:before {
            content: ' ';
            background: #d4d9df;
            display: inline-block;
            position: absolute;
            left: 29px;
            width: 2px;
            height: 100%;
            z-index: 400;
        }

        ul.timeline>li {
            margin: 20px 0;
            padding-left: 20px;
        }

        ul.timeline>li:before {
            content: ' ';
            background: white;
            display: inline-block;
            position: absolute;
            border-radius: 50%;
            border: 3px solid #22c0e8;
            left: 20px;
            width: 20px;
            height: 20px;
            z-index: 400;
        }
    </style>
@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Analytic</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Item Live Cycle </li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <h3>Search</h3>
                        <hr>
                        {{-- <form action="{{ url('ItemLivedata') }}"   method="post" enctype="multipart/form-data"> --}}
                        {{-- @csrf --}}
                        <div class="row">
                            <div class="col-lg-0">
                                <div class=" p-4 bg-light d-none">
                                    <label class="mt-2" for="">بحث عن </label>
                                    <select  name="slected_type" id="slected_type" class="form-select " required>
                                        <option class="text-center" value="part" selected>قطع غيار </option>
                                        <option class="text-center" value="wheel"> كاوتش</option>
                                        <option class="text-center" value="kit"> كيت </option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class=" p-4 bg-light">
                                    <label class="mt-2" for="">إختر القطعة </label>

                                    <select class="form-select select2" name="itemLiveSlct" id="itemLiveSlct">

                                    </select>
                                </div>
                            </div>


                        </div>
                        <br>
                        <div class="row">

                            <div class="col-lg-2">
                                <button type="button" onclick='GetDatax();' class="btn btn-info">Search</button>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">من</label>
                                <input onchange="" type="date" class="form-control" name="start_date" id="start_date"
                                    placeholder="From Date">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">الي</label>
                                <input onchange="" type="date" class="form-control" name="end_date" id="end_date"
                                    placeholder="To Date">
                            </div>

                            {{-- </form> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card">
                    <div class="card-body mt-5 mb-5">
                        <div class="row">
                            <h3 id="partName"> Item Story<span class="item_inv_count_purchase"></span></h3>

                            <div class="col-lg-8 offset-md-2">
                                <div class="row">
                                    <div class="col-lg-3"><h5 id=""> إجمالى كمية مشتريات <span id="itemamount"></span></h5></div>
                                    <div class="col-lg-3"><h5 id=""> إجمالى كيت مجمع <span id="itemkitamount"></span></h5></div>
                                    <div class="col-lg-3"><h5 id=""> إجمالى كمية مباعة <span id="itemsoldamount"></span></h5></div>
                                    <div class="col-lg-3"><h5 id=""> إجمالى  المتبقى <span id="itemremain"></span></h5></div>
                                </div>

                                <hr>
                                {{-- <table class="table table-border table-striped text-center table-sm">
                                    <theaid=:d class="text-center text-bg-info">
                                        <tr>
                                            <th class="text-center">رقم الفاتورة </th>
                                            <th class="text-center">بلد المنشأ</th>
                                            <th class="text-center">الحالة</th>
                                            <th class="text-center">الكفاءة</th>
                                            <th class="text-center">الكمية</th>
                                            <th class="text-center">عرض</th>

                                        </tr>

                                    </thead>
                                    <tbody id="item_in_inv_details">

                                    </tbody>
                                </table> --}}
                                <ul class="timeline" style="direction : ltr">
                                    {{-- <li>
                                        <a target="_blank" href="https://www.totoprayogo.com/#">New Web Design</a>
                                        <a href="#" class="float-right">21 March, 2014</a>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque scelerisque
                                            diam non nisi semper, et elementum lorem ornare. Maecenas placerat facilisis
                                            mollis. Duis sagittis ligula in sodales vehicula....</p>
                                    </li> --}}

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <br>



        </div>

    @endsection

    @section('js')


        <script>
            $(document).ready(function() {

                $("#itemLiveSlct").select2({
                    ajax: {
                        url: "partsSearch",
                        //   dataType: 'json',
                        async: false,
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term.toLowerCase(), // search term
                                page: params.page,
                                type: $('#slected_type').val()
                            };
                        },
                        processResults: function(data, params) {
                            // parse the results into the format expected by Select2
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data, except to indicate that infinite
                            // scrolling can be used
                            // $("#select2-itemLiveSlct-results").empty();
                            // data.forEach(element => {
                            //     $("#select2-itemLiveSlct-results").append(`<li>${element.name}</li>`);
                            // });

                            params.page = params.page || 1;
                            return {
                                results: data,
                                //   pagination: {
                                //     more: (params.page * 30) < data.total_count
                                //   }
                            };
                        },
                        cache: true
                    },
                    placeholder: 'Search ',
                    minimumInputLength: 3,
                    templateResult: formatRepo,
                    templateSelection: formatRepoSelection

                });

                function formatRepo(repo) {
                    //     $("#select2-itemLiveSlct-results").append(repo);
                    //    return repo;
                    if (repo.loading) {
                        return repo.text;
                    }

                    var $container = $(
                        "<div class='select2-result-repository clearfix'>" +
                        //"<div class='select2-result-repository__avatar d-none'><img src='" + repo.name + "' /></div>" +
                        "<div class='select2-result-repository__meta'>" +
                        "<div class='select2-result-repository__title'></div>" +
                        "<div class='select2-result-repository__description'></div>" +
                        "<div class='select2-result-repository__statistics'>" +
                        "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> </div>" +
                        "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> </div>" +
                        "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> </div>" +
                        "</div>" +
                        "</div>" +
                        "</div>"
                    );


                    $container.find(".select2-result-repository__title").text(repo.name);
                    // $container.find(".select2-result-repository__description").text(repo.description);
                    // $container.find(".select2-result-repository__forks").append(repo.name + " Forks");
                    // $container.find(".select2-result-repository__stargazers").append(repo.name + " Stars");
                    // $container.find(".select2-result-repository__watchers").append(repo.name + " Watchers");

                    return $container;
                }

                function formatRepoSelection(repo) {
                    return repo.name || repo.text || repo.type_id;
                }

                function select2_search($el, term) {
                    $el.select2('open');

                    // Get the search box within the dropdown or the selection
                    // Dropdown = single, Selection = multiple
                    var $search = $el.data('select2').dropdown.$search || $el.data('select2').selection.$search;
                    // This is undocumented and may change in the future

                    $search.val(term);
                    $search.trigger('keyup').click();
                    $search.trigger('change');
                }

            });

            // $(document).on('select2:open', () => {
            //     document.querySelector('.select2-search__field').focus();
            // });


            function GetDatax() {
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();
                var type_id = 1;
                if (!start_date) {
                    start_date = null;
                }
                if (!end_date) {
                    end_date = null;
                }
                if ($('#slected_type').val() === "part") {
                    type_id = 1;
                } else if ($('#slected_type').val() === "wheel") {
                    type_id = 2;
                } else if ($('#slected_type').val() === "kit") {
                    type_id = 6;
                }
                $.ajax({
                    type: "get",
                    url: "ItemLivedata",
                    data: {
                        'partId': $('#itemLiveSlct').val(),
                        'type_id': type_id,
                        'start_date': $('#start_date').val(),
                        'end_date': $('#end_date').val()
                    },

                    success: function(response) {
                        var timeline = [];
                        $('.timeline').empty();
                        if(response.length > 0){
                            $("#partName").text(response[0].part.name);
                        }
                        var itemAmount=0;
                        var itemRemain=0;
                        var itemKitamount=0;

                        for (let i = 0; i < response.length; i++) {
                            const element = response[i];
                            itemAmount+=parseInt(element.amount);
                            itemRemain+=parseInt(element.remain_amount);
                            // var storeLogs = " لا يوجد حركات مخازن "
                            var storeLogs = "";
                            var AllKitItems = "";
                            element.store_log.forEach(elementx => {
                                storeLogs +=`
                                    <div  class="col-lg-3 fw-bold">${(elementx.date).split('T')[0]}</div>
                                    <div  class="col-lg-3 fw-bold">  ${elementx.store.name}</div>
                                    <div  class="col-lg-3 fw-bold">  ${elementx.amount}</div>
                                    <div  class="col-lg-3 fw-bold"> ${elementx.store_action.name}</div>
                                `;
                            });
                            if (element.store_log.length > 0) {

                            }else{
                                storeLogs=" لم  يتم توزيع الصنف داخل اى مخزن"
                            }
                            element.part_in_allkit_item.forEach(elementy => {
                                itemKitamount+=parseInt(elementy.amount);
                                storeLogs +=`
                                    <div  class="col-lg-3 fw-bold">${(elementy.created_at).split('T')[0]}</div>
                                    <div  class="col-lg-3 fw-bold"> قطعة ${elementy.amount} </div>
                                    <div  class="col-lg-3 fw-bold">  ${elementy.all_kit.kit.name}</div>
                                    <div  class="col-lg-3 fw-bold"> طقم ${elementy.all_kit.amount} </div>
                                `;
                            });
                            if (element.part_in_allkit_item.length > 0) {

                            }else{
                                AllKitItems=`<div  class="col-lg-3 fw-bold"></div>
                                <div  class="col-lg-6 fw-bold"> لم  يتم توزيع الصنف داخل اى كيت</div>
                                <div  class="col-lg-3 fw-bold"></div>`

                            }

                            timeline.push(` <li class="px-5">
                                            <a class="fs-19 fw-bold" target="_blank" href="/printBuyInvoice/${element.order_supplier.buy_transaction.id}">فاتورة شراء رقم  ${element.order_supplier.buy_transaction.id}</a>
                                            <a href="#" class="float-right float-right fs-15 p-1 text-bg-info">${(element.order_supplier.buy_transaction.date).split('T')[0]}</a>
                                            <br/>
                                            <div class="row">
                                                <div  class="col-lg-3 fw-bold"> ${element.source.name_arabic}</div>
                                                <div  class="col-lg-3 fw-bold">${element.status.name}</div>
                                                <div  class="col-lg-3 fw-bold">  ${element.part_quality.name}</div>
                                                <div  class="col-lg-3 fw-bold">  ${element.order_supplier.supplier.name}</div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div  class="col-lg-6 fw-bold"> الكمية : ${element.amount}</div>
                                                <div  class="col-lg-6 fw-bold"> <span>  ${element.order_supplier.currency_type.name}</span> <span>سعر الشراء   :</span>  ${element.replayorderss.price}  </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                ${storeLogs}
                                            </div>
                                            <hr>
                                            <div class="row">
                                                ${AllKitItems}
                                            </div>
                                            <hr>
                                            <div class="row">

                                            </div>

                                        </li>`);
                        }
                        $('.timeline').append(timeline);
                        $('#itemamount').text(itemAmount);
                        $('#itemkitamount').text(itemKitamount);
                        $('#itemsoldamount').text(itemAmount - itemKitamount - itemRemain);
                        $('#itemremain').text(itemRemain);
                        console.log(response);
                    }
                });
            }
        </script>



    @endsection
