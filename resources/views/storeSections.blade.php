@extends('layouts.posMaster')
@section('css')


    <style>
        table {
            font-family: 'Droid Arabic Naskh', serif;
        }

        .modal-dialog {
            max-width: 80% !important;
        }
    </style>
@endsection
@section('title')
    Sections
@stop


@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0"> Sections</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Item Sections </li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card row">
                <h1 class="text-center text-decoration-underline">{{ $store->name }}</h1>
                <input type="hidden" name="storeId" id="storeId" value="{{ $store->id }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-2">
                            <label for="">اختر القـــسم</label>
                        </div>
                        <div class="col-8">
                            <select class="form-control" name="" id="sectionSlct">
                                <option value="0" disabled selected>اختر</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <a data-bs-toggle="modal" data-bs-target="#sectionMdl" style="cursor: pointer;"
                                class="btn btn-success">توزيع أصناف علي المخزن</a>
                        </div>
                    </div>
                    <div class="m-2 row">
                        <div class="col">
                            <table class="table table-bordered" id="storeSectionTbl">
                                <thead>
                                    <tr>
                                        <td>م</td>
                                        <td>القطعة</td>
                                        <td>الكمية</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center ">
                                        <td colspan="3">
                                            من فضلك اختر القســــــــم
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>



        </div>
    </div>

    <div class="modal fade" id="sectionMdl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="sectionMdlLabel" aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sectionMdlLabel">توزيع المخزن</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="search" class="form-control mb-2" name="" id="searchSectionTxt">
                    <div class="row">
                        <div class="col">
                            <form action="/saveNewSection" method="POST">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="storelbl" value="{{ $store->id }}">
                                <button class="btn btn-success w-100">Save</button>

                                <table class="table table-bordered">
                                    <tr>
                                        <td>الصنف</td>
                                        <td>المنشأ</td>
                                        <td>الحالة</td>
                                        <td>الجودة</td>
                                        <td>اجمالي الكمية</td>
                                        <td class="w-25">القسم</td>
                                        <td>الكمية</td>
                                        <td>History</td>
                                    </tr>
                                    @foreach ($newParts as $part)
                                        @if (($part->type_id == 1 || $part->type_id == 2 || $part->type_id == 6 || $part->type_id == 3 || $part->type_id == 5 || $part->type_id == 4) && count($part->partData) > 0)
                                            <tr class="resSec">
                                                <input type="hidden" name="part_id[]" value="{{ $part->part }}">
                                                <input type="hidden" name="details[]"
                                                    value="{{ $part->source_id }} / {{ $part->status_id }} / {{ $part->quality_id }}">
                                                <input type="hidden" name="supplier_order_id[]"
                                                    value="{{ $part->supplier_order_id }}">
                                                <input type="hidden" name="store_log[]" value="{{ $part->store_log_id }}">
                                                <input type="hidden" name="Ptype[]" value="{{ $part->type_id }}">
                                                <input type="hidden" name="samllmeasureUnits[]" value="{{ $part->partData[0]->small_unit }}">
                                                <input type="hidden" name="measureUnit[]" value="{{$part->partData[0]->big_unit }}">
                                                
                                                <td>{{ count($part->partData) > 0 ? strtoupper($part->partData[0]->name) : '-' }}
                                                </td>
                                                <td>{{ $part->source_name }}</td>
                                                <td>{{ $part->status_name }}</td>
                                                <td>{{ $part->quality_name }}</td>
                                                <?php 
                                                $ratioamount=getSmallUnit($part->partData[0]->big_unit,$part->partData[0]->small_unit);
                                                ?>
                                                <td data-storeAmount="{{ $part->storeAmount }}"
                                                    data-sectionAmount="{{ $part->sectionAmount }}">
                                                    {{ ($part->storeAmount - $part->sectionAmount )/ $ratioamount}} / {{$part->partData[0]->bigunit->name}}</td>


                                                <td>
                                                  <select class="form-control w-xl selectSec"
                                                        id="sec_slc{{ $part->store_log_id }}"
                                                        onClick="fire_search('sec_slc{{ $part->store_log_id }}')"
                                                        name="section[]">
                                                        <option value="0" selected>اختر</option>
                                                        <!--@foreach ($sections as $section)-->
                                                        <!--    <option value="{{ $section->id }}">{{ $section->name }}-->
                                                        <!--    </option>-->
                                                        <!--@endforeach-->
                                                    </select>
                                                </td>
                                                <td>
                                                <input type="number" step=".1"
                                                data-max="{{ $part->storeAmount - $part->sectionAmount }}"
                                                        value="0" class="form-control sectionAmountcls"
                                                        name="sectionAmount[]">
                                                </td>
                                                <td>
                                                    @if($part->partHistory)
                                                        @forelse($part->partHistory as $sectionHistory )
                                                            <li>{{ $sectionHistory->store_structure->name }}</li>
                                                        @empty
                                                            <li>No Section</li>
                                                        @endforelse
                                                    @else
                                                        <li>No Section</li>
                                                    @endif

                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary">save</button> --}}
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="changeSectionMDL" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تغيير مكان القطعة </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/sendfromSection" method="POST">
                        @csrf
                        <input type="hidden" name="store_id" id="store_id" value="0">
                        {{-- <input type="hidden" name="order_supplier_id" id="order_supplier_id" value="0"> --}}
                        <input type="hidden" name="type_id" id="type_id" value="0">
                        <input type="hidden" name="part_id" id="part_id" value="0">
                        <input type="hidden" name="source_id" id="source_id" value="0">
                        <input type="hidden" name="quality_id" id="quality_id" value="0">
                        <input type="hidden" name="status_id" id="status_id" value="0">








                        <div class="row">
                            <div class="col lg 6">
                                <label for="itemName" class="col-form-label">إسم القطعة</label>
                                <input type="text" class="form-control" name="itemName" id="itemName">
                                <input type="hidden" name="section_id" id="sectionId" value="0">
                                <input type="hidden" class="form-control" name="item_allp_id" id="item_allp_id">
                            </div>
                        </div>
                        <hr>
                        <div class="row">

                            <div class="col lg 6">
                                <label for="old_amount" class="col-form-label">الكمية القديمة </label>
                                <input type="text" class="form-control" name="old_amount" id="old_amount">
                                <input type="hidden" class="form-control" name="old_amount_txt" id="old_amount_txt">
                            </div>
                            <div class="col lg 6">
                                <label for="old_section" class="col-form-label">المكان الحالى </label>
                                <input type="text" class="form-control" name="old_section" id="old_section">
                            </div>

                        </div>
                        <div class="row">

                            <div class="col lg 6">
                                <label for="new_amount" class="col-form-label">الكمية الجديدة </label>
                                <input type="text" class="form-control" name="new_amount" id="new_amount">
                            </div>
                            <div class="col lg 6">
                                <label for="old_section" class="col-form-label">المكان الجديد </label>
                                {{-- <input type="text" class="form-control" name="new_section" id="new_section"> --}}
                                <select name="new_section" id="new_section">
                                    <option value="0" disabled selected>اختر</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">save </button>
                </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('js')

    <script>
        $(document).on('keyup', '.sectionAmountcls', function() {
            var max_val = parseInt($(this).attr('data-max'));
            var amount = $(this).val();
            if (amount > max_val) {
                $(this).val(max_val);
            } else if (amount < 0) {
                $(this).val(0);
            }

        })
        var partsDt;
        
        
        function fire_search(el) {
            $('#' + el).select2({
                dropdownParent: $('#' + el).parent().closest('tr'),
                ajax: {
                    url: "/sectionsSearch",
                    //   dataType: 'json',
                    async: false,
                    delay: 250,
                    data: function(params) {
                        return {
                            // (params.term).replace(/\//g," ").toLowerCase()
                            q: encodeURIComponent((params.term).toLowerCase()), // search term
                            page: params.page,
                            store_id: $("#storeId").val()
                        };
                    },
                    processResults: function(data, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        // $("#select2-partSlct-results").empty();
                        // data.forEach(element => {
                        //     $("#select2-partSlct-results").append(`<li>${element.name}</li>`);
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
                minimumInputLength: 1,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection
            });

            function formatRepo(repo) {
                //     $("#select2-partSlct-results").append(repo);
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

         
        }
        $(document).ready(function() {
            // $('select').select2();

          
            $('#new_section').select2({
                tags: true,
                dropdownParent: $("#changeSectionMDL"),
                ajax: {
                    url: "/sectionsSearch",
                    //   dataType: 'json',
                    async: false,
                    delay: 250,
                    data: function(params) {
                        return {
                            // (params.term).replace(/\//g," ").toLowerCase()
                            q: encodeURIComponent((params.term).toLowerCase()), // search term
                            page: params.page,
                            store_id: $("#storeId").val()
                        };
                    },
                    processResults: function(data, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        // $("#select2-partSlct-results").empty();
                        // data.forEach(element => {
                        //     $("#select2-partSlct-results").append(`<li>${element.name}</li>`);
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
                minimumInputLength: 1,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection
            });
            
            
            
            
            
            $("#sectionSlct").select2({
                    ajax: {
                        url: "/sectionsSearch",
                        //   dataType: 'json',
                        async: false,
                        delay: 250,
                        data: function(params) {
                            return {
                                // (params.term).replace(/\//g," ").toLowerCase()
                                q: encodeURIComponent((params.term).toLowerCase()), // search term
                                page: params.page,
                                store_id: $("#storeId").val()
                            };
                        },
                        processResults: function(data, params) {
                            // parse the results into the format expected by Select2
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data, except to indicate that infinite
                            // scrolling can be used
                            // $("#select2-partSlct-results").empty();
                            // data.forEach(element => {
                            //     $("#select2-partSlct-results").append(`<li>${element.name}</li>`);
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
                    minimumInputLength: 1,
                    templateResult: formatRepo,
                    templateSelection: formatRepoSelection

                });

                function formatRepo(repo) {
                    //     $("#select2-partSlct-results").append(repo);
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

                // function select2_search($el, term) {
                //     $el.select2('open');

                //     // Get the search box within the dropdown or the selection
                //     // Dropdown = single, Selection = multiple
                //     var $search = $el.data('select2').dropdown.$search || $el.data('select2').selection.$search;
                //     // This is undocumented and may change in the future

                //     $search.val(term);
                //     $search.trigger('keyup').click();
                //     $search.trigger('change');
                // }
                
                
        });

        $("#sectionSlct").change(function(e) {
            e.preventDefault();
            var sectionId = $(this).val();
            var storeId = $("#storeId").val();
            getAllDataInSection(sectionId, storeId)
        });



        function getAllDataInSection(section_id, store_id) {
            $.ajax({
                type: "get",
                url: "/getAllDataInSection/" + section_id + "/" + store_id,
                success: function(response) {
                    $("#storeSectionTbl tbody").empty();
                    var i = 1;
                    response.forEach(element => {
                        $("#storeSectionTbl tbody").append(`
                            <tr >
                                <td>${i}</td>
                                <td>${element.part[0].name}</td>
                                <td>${element.amount}</td>
                                <td>
                                    <button type="button" onclick='change_scetion(${JSON.stringify(element)})'  class="btn btn-default p-1 px-3 text-bg-success"><i class="ri-rocket-line"></i>تغيير القسم</button>
                                    <button type="button" disabled class="btn btn-default p-1 px-3 text-bg-info"><i class="ri-rocket-line"></i>طلب من مخزن أخر</button>
                                </td>

                            </tr>
                        `);
                        i++
                    });
                }
            });
        }
        $("#searchSectionTxt").keyup(function(e) {
            var searchValue = $(this).val();
            $('.resSec').hide();
            $('.resSec:contains("' + searchValue.toLocaleUpperCase() + '")').show();
        });


        function change_scetion(data) {

            $('#store_id').val($("#storeId").val());
            // $('#order_supplier_id').val(data.order_supplier_id);
            $('#type_id').val(data.type_id);
            $('#part_id').val(data.part_id);
            $('#source_id').val(data.source_id);
            $('#status_id').val(data.status_id);
            $('#quality_id').val(data.quality_id);

            $('#itemName').val(data['part'][0].name);

            $('#old_amount').val(data['amount']);
            $('#old_amount_txt').val(data['amount']);
            $('#old_section').val(data['part'][0].name);

            var section_id = $("#sectionSlct").val();
            var section_name = $("#sectionSlct  option:selected").text();
            if(section_name==''){
                section_name = $("#sectionSlct").select2('data')[0].name;
            }
            $('#old_section').val(section_name);
            $('#sectionId').val(section_id);
            // $('#old_amount').val(data.amount);

            $('#changeSectionMDL').modal('show');
            console.log(data);
        }
    </script>



@endsection
