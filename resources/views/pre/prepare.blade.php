@extends('layouts.master')
@section('css')
<style>
    .select2-container{
        text-align : center;
    }
</style>

   
@endsection
@section('title')
    Talabea
@stop


@section('content')

   

    <div class="main-content">
        <div class="page-content">
          
            
            <div class="card">
                <form action="{{ route('process_selected_items') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="col-12 px-1 m-3 text-end">
                            <h2 class="mb-4">Talabea Items List</h2>
                            <div class="row">
                                <div class="col d-flex align-items-center">
                                    <span class="me-2 text-nowrap">اختر الطلبية :  </span> 
                                    <select name="talabea_id" id="talabeasSLCT" class="form-select w-auto" required>
                                    </select>
                                    <button class="btn btn-primary ms-2 edit_item" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop_edit">+</button>
                                    <a class="btn btn-info ms-2 " href="#" id="gotoHref" ><i class="ri ri-eye-fill"></i></a>
                                </div>
                                <div class="col d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-danger text-nowrap" onclick="get_all_defects()">تحميل النواقص</button>
                                    <button type="button" class="btn btn-info text-nowrap" data-bs-toggle="modal" data-bs-target="#filterMdl">فلتر</button>
                                    <select class="form-select w-auto" name="partSlct" id="partSlct"></select>
                                </div>                                
                               
                                <div class="col-lg-2">
                                <input type="search" id="searchInput" class="form-control mb-3" placeholder="Search in table...">
                                </div>
                            </div>
                        <hr>
                                <button name="add" value="1" type="submit" class="btn btn-primary mt-3">Add to Talabea</button>
                                <button value="1" name="delete" type="submit" class="btn btn-danger mt-3">Remove </button>
                                <hr>
                            
                                <table class="table table-bordered" id="itemsTbl">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select_all"></th>
                                            <th>Item</th>
                                            <th>Type</th>
                                            <th>Source</th>
                                            <th>Status</th>
                                            <th>Quality</th>
                                            <th>Amount</th>
                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($talabeaItems as $item)
                                        <tr>
                                            <td><input type="checkbox" name="selected_items[]" value="{{ $item->type->id }}-{{ $item->part_id }}-{{ $item->source_id }}-{{ $item->status_id }}-{{ $item->quality_id }}"></td>
                                        
                                            <td>{{ $item->item->name ?? 'N/A' }}</td>
                                            <td>{{ $item->type->name ?? 'N/A' }}</td>
                                            <td>{{ $item->source->name_arabic ?? 'N/A' }}</td>
                                            <td>{{ $item->status->name ?? 'N/A' }}</td>
                                            <td>{{ $item->part_quality->name ?? 'N/A' }}</td>
                                            <td>{{ $item->amount }}</td>
                                            
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                        
                              
                            
                    </div>
                </form>
            </div>


        </div>
    </div>
    <div class="modal fade " id="staticBackdrop_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true" style="padding-right:33%">
        <div class="modal-dialog">
            <div class="modal-content w-50">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> طلبية جديدة </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            
                    <div class="modal-body">
                    
                            <div class="row">
                                <div class="col text-left">
                                    إسم الطلبية :
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" name="name" id="newTalabeatxt">
                                </div>
                                <div class="col">
                                    <button class="btn btn-sm btn-info text-right" id="newTalabea">Save</button>

                                </div>
                            </div>
                        
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-danger">تعديل</button> --}}
                    </div>
            
            </div>
        </div>
    </div>

    <div class="modal fade " id="filterMdl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true" style="padding-right:33%">
    <div class="modal-dialog">
        <div class="modal-content w-50">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"> Filter </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        
                <div class="modal-body">
                
                    <div class="hidden m-0 p-0 " id="right_col"  style="width:100%;">
                        <div class="border border-top-0 card m-0 p-0 rounded-0" id="filterPanel">
                            <div class="bg-soft-dark card-body p-0">
    
                                <div class="catalog-filter-form p-3">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <button id="filterBtn" class="btn btn-block btn-dark rounded-0">  بحث تفصيلي <i class="ri-search-2-line"></i></button>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="">المخزن :</label>
                                            <select name="storeId" class="form-select" id="storeId">
                                                <option value="" disabled >Select Store</option>
                                                @foreach ($allStores as $element)
                                                    @if ($element->id == 5)
                                                        <option selected value="{{ $element->id }}">{{ $element->name }}</option>
                                                    @else
                                                        <option value="{{ $element->id }}">{{ $element->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="">بحث عن</label>
                                            <input type="search" value="" class="form-control" name="number"
                                                id="partNameSearchTxt">
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="">النوع'TYPE' :</label>
                                            <select name="brandType" class="form-select" id="brandtypeSlct">
                                                <option value="" selected disabled>Choose Here</option>
                                                @foreach ($Btype as $element)
                                                    <option value="{{ $element->id }}">{{ $element->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="">Brand</label>
                                            <select name="brand" aria-label="Default select example" class="form-select"
                                                id="brandSlct">
                                                <option value="" selected disabled>Choose Here</option>
                                                @foreach ($allbrand as $element)
                                                    <option value="{{ $element->id }}">{{ $element->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="">MODEL</label>
                                            <select name="model" class="form-select" id="modelSlct">
                                                <option value="" selected disabled>Choose Here</option>
                                                @foreach ($allmodel as $element)
                                                    <option value="{{ $element->id }}">{{ $element->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="">SERIES</label>
                                            <select name="series" class="form-select" id="seriesSlct">
                                                <option value="" selected disabled>Choose Here</option>
                                                @foreach ($allseries as $element)
                                                    <option value="{{ $element->id }}">{{ $element->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="">NUMBER</label>
                                            <input type="search" value="" class="form-control" name="number"
                                                id="partNumberSearchTxt">
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="">SUPPLIER</label>
                                            <select name="supplier" class="form-select" id="supplierSlct">
                                                <option value="" selected disabled>Choose Here</option>
                                                @foreach ($allSuppliers as $element)
                                                    <option value="{{ $element->id }}">{{ $element->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="">Group</label>
                                            <select name="group" class="form-select" id="groupSlct">
                                                <option value="" selected disabled>Choose Here</option>
                                                @foreach ($allGroups as $element)
                                                    <option value="{{ $element->id }}">{{ $element->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="">Sub Group</label>
                                            <select name="subgroup" class="form-select" id="SgroupSlct">
                                                <option value="" selected disabled>Choose Here</option>
                                                @foreach ($allSGroups as $element)
                                                    <option value="{{ $element->id }}">{{ $element->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                   
                </div>
        
        </div>
    </div>
</div>

@endsection

@section('js')
   
<script>
    get_all_talabeas();
    
    document.getElementById('select_all').addEventListener('click', function(event) {
        let isChecked = event.target.checked;

        document.querySelectorAll("#itemsTbl tbody tr").forEach(row => {
            let checkbox = row.querySelector('input[name="selected_items[]"]');

            if (row.style.display !== "none" && checkbox) { 
                checkbox.checked = isChecked;  // Check/uncheck only visible checkboxes
            }
        });
    });

    function get_all_talabeas(){
        $.ajax({
                type: "GET",
                url: "/get_all_talabeas",
                success: function (response) {
                    $("#talabeasSLCT").empty();
                     if(response){
                        $("#talabeasSLCT").append(`<option selected disabled value="">Select Talabia Name </option>`); 
                        response.forEach(element => {
                            $("#talabeasSLCT").append(`<option value="${element.id}">${element.name}</option>`);    
                        });
                        
                     }else{
                        $("#talabeasSLCT").append(`<option value="">No Talabe</option>`);    
                     }
                     $("#talabeasSLCT").select2()
                }
            })

    }

    $("#newTalabea").click(function(e){
        $.ajax({
                type: "POST",
                url: "/NewTalabea",
                data : {
                    'name' : $("#newTalabeatxt").val(),
                    '_token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $("#staticBackdrop_edit").modal('toggle')
                    get_all_talabeas()
                }
            })

    })

    function get_all_defects(){
        $.ajax({
                type: "GET",
                url: "/get_all_defects",
                success: function (response) {
                    if(response){
                        $("#itemsTbl tbody").empty();
                        response.forEach(element => {
                            $("#itemsTbl").append(`<tr>
                                        <td><input type="checkbox" name="selected_items[]" value="1-${element.part_id}-${element.source_id}-${element.status_id}-${element.quality_id}"></td>
                                       
                                        <td>${element.part.name}</td>
                                        <td>Part</td>
                                        <td>${ element.source.name_arabic }</td>
                                        <td>${element.status.name}</td>
                                        <td>${element.part_quality.name}</td>
                                        <td>1</td>
                                        
                    </tr>`);
                        });
                    }
                    


                }
            })

    }

    document.getElementById("searchInput").addEventListener("keyup", function () {
        let searchValue = this.value.toLowerCase();
        let rows = document.querySelectorAll("#itemsTbl tbody tr");

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? "" : "none";
        });
    });
</script>


<script>
    $(document).on('click', '#filterBtn', function(){
    var partNameSearchTxt= $("#partNameSearchTxt").val();
    var storeId= $("#storeId").val();
    var brandtypeSlct= $("#brandtypeSlct").val();
    var brandSlct= $("#brandSlct").val();
    var modelSlct= $("#modelSlct").val();
    var seriesSlct= $("#seriesSlct").val();
    var partNumberSearchTxt= $("#partNumberSearchTxt").val();
    var supplierSlct= $("#supplierSlct").val();
    var groupSlct= $("#groupSlct").val();
    var SgroupSlct= $("#SgroupSlct").val();

    // $("#row_filters").empty();
    // if(partNameSearchTxt){
    //     $("#row_filters").append(` <div class="ml-1  w-auto">
    //         <button type="button" onclick="reloadFilter(this,'partNameSearchTxt')" class="btn btn-outline-info btn-sm ">${partNameSearchTxt}  <i class="ri-close-fill"></i></button>
    //     </div>`);
    // }
    // if(brandtypeSlct){
    //     $("#row_filters").append(` <div class="ml-1  w-auto">
    //         <button type="button" onclick="reloadFilter(this,'brandtypeSlct')" class="btn btn-outline-info btn-sm ">${$("#brandtypeSlct option:selected").text()}  <i class="ri-close-fill"></i></button>
    //     </div>`);
    // }
    // if(brandSlct){
    //     $("#row_filters").append(` <div class="ml-1  w-auto">
    //         <button type="button" onclick="reloadFilter(this,'brandSlct')" class="btn btn-outline-info btn-sm ">${$("#brandSlct option:selected").text()}  <i class="ri-close-fill"></i></button>
    //     </div>`);
    // }
    // if(modelSlct){
    //     $("#row_filters").append(` <div class="ml-1  w-auto">
    //         <button type="button" onclick="reloadFilter(this,'modelSlct')" class="btn btn-outline-info btn-sm ">${$("#modelSlct option:selected").text()}  <i class="ri-close-fill"></i></button>
    //     </div>`);
    // }
    // if(seriesSlct){
    //     $("#row_filters").append(` <div class="ml-1  w-auto">
    //         <button type="button" onclick="reloadFilter(this,'seriesSlct')" class="btn btn-outline-info btn-sm ">${$("#seriesSlct option:selected").text()}  <i class="ri-close-fill"></i></button>
    //     </div>`);
    // }
    // if(partNumberSearchTxt){
    //     $("#row_filters").append(` <div class="ml-1  w-auto">
    //         <button type="button" onclick="reloadFilter(this,'partNumberSearchTxt')" class="btn btn-outline-info btn-sm ">${partNumberSearchTxt}  <i class="ri-close-fill"></i></button>
    //     </div>`);
    // }
    // if(supplierSlct){
    //     $("#row_filters").append(` <div class="ml-1  w-auto">
    //         <button type="button" onclick="reloadFilter(this,'supplierSlct')" class="btn btn-outline-info btn-sm ">${$("#supplierSlct option:selected").text()}  <i class="ri-close-fill"></i></button>
    //     </div>`);
    // }
    // if(groupSlct){
    //     $("#row_filters").append(` <div class="ml-1  w-auto">
    //         <button type="button" onclick="reloadFilter(this,'groupSlct')" class="btn btn-outline-info btn-sm ">${$("#groupSlct option:selected").text()}  <i class="ri-close-fill"></i></button>
    //     </div>`);
    // }
    // if(SgroupSlct){
    //     $("#row_filters").append(` <div class="ml-1  w-auto">
    //         <button type="button" onclick="reloadFilter(this,'SgroupSlct')" class="btn btn-outline-info btn-sm ">${$("#SgroupSlct option:selected").text()}  <i class="ri-close-fill"></i></button>
    //     </div>`);
    // }



    $("#preloader").css({
        'opacity': '1',
        'visibility': 'visible'
    });
    $.ajax({
        type: "get",
        url: "allDataForIdFilterAll",
        data : {
            storeId : storeId ,
            searchData : {
                'partNameSearchTxt' : partNameSearchTxt ,
                'brandtypeSlct' : brandtypeSlct ,
                'brandSlct' : brandSlct ,
                'modelSlct' : modelSlct ,
                'seriesSlct' : seriesSlct ,
                'partNumberSearchTxt' : partNumberSearchTxt ,
                'supplierSlct' : supplierSlct ,
                'groupSlct' : groupSlct ,
                'SgroupSlct' : SgroupSlct ,

            },
            typeId : 1
        },
        success: function (response) {
            
            if (response.data.length > 0) {
               console.log(response.data);
               $("#itemsTbl tbody").empty();
               response.data.forEach(element => {
                    $("#itemsTbl").append(`<tr>
                                        <td><input type="checkbox" name="selected_items[]" value="${element.type_id}-${element.part_id}-${element.source_id}-${element.status_id}-${element.quality_id}"></td>
                                       
                                        <td>${element.name}</td>
                                        <td>Part</td>
                                        <td>${ element.source }</td>
                                        <td>${element.status}</td>
                                        <td>${element.quality}</td>
                                        <td>${element.amount}</td>
                                        
                    </tr>`);
                });
            }

        },complete:function(response){
            $("#preloader").css({
                'opacity': '0',
                'visibility': 'hidden'
            });
        }
    });


})
</script>



<script>
    $("#partSlct").select2({
            ajax: {
                url: "partsSearch",
                //   dataType: 'json',
                async: false,
                delay: 250,
                data: function(params) {
                    return {
                        // (params.term).replace(/\//g," ").toLowerCase()
                        q: encodeURIComponent((params.term).toLowerCase()), // search term
                        page: params.page,
                        type: $('#slected_type').val()
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
            minimumInputLength: 3,
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

    $("#partSlct").on('change', function(e) {
            var selectedText = $("#select2-partSlct-container").text();
            var selectedType = ($(this).select2('data').length > 0) ? $(this).select2('data')[0].type_id : 0;
            var selectedPartID = $(this).val();

            $("#itemsTbl").append(`<tr>
                                        <td><input type="checkbox" name="selected_items[]" value="${selectedType}-${selectedPartID}-NULL-NULL-NULL"></td>
                                       
                                        <td>${selectedText}</td>
                                        <td>Part</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>1</td>
                                        
                    </tr>`);
           
            $(this).val(null);

        })
    $("#talabeasSLCT").change(function(e){
        $("#gotoHref").attr('href' , '/talabea/'+$(this).val());
    })
</script>

@endsection
