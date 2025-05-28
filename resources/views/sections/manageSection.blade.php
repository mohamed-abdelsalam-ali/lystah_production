@extends('layouts.master')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ URL::asset('assets/css/bootstrap-duallistbox.min.css') }}">
<style>
 
</style>
@endsection
@section('title')
    Sections
@stop


@section('content')

    

    <div class="main-content">
        <div class="page-content">
            <h1 class="text-center text-info">Sections
            </h1>
            <div class="border-0 border-bottom mb-4 pb-4 row text-bg-light">
                <div class="col-lg-6">
                    <label for="">اختر القسم</label>
                    <select class="form-control sectionSlct" name="" id="availableSlct">
                        <option value="">Choose</option>
                        @foreach ($all_store_structures as $sec )
                            <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6">
                    <label for="">اختر القسم</label>
                    <select class="form-control sectionSlct" name="" id="selectedSlct">
                        <option value="">Choose</option>
                        @foreach ($all_store_structures as $sec )
                            <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
           <div class="row">
            <div class="col-lg-12">
                <form class="" id="demoform" action="#" method="post">
                    <select multiple="multiple" size="10" name="duallistbox_demo1[]">
                      {{-- <option value="option1">Option 1</option>
                      <option value="option2">Option 2</option>
                      <option value="option3" selected="selected">Option 3</option>
                      <option value="option4">Option 4</option>
                      <option value="option5">Option 5</option>
                      <option value="option6" selected="selected">Option 6</option>
                      <option value="option7">Option 7</option>
                      <option value="option8">Option 8</option>
                      <option value="option9">Option 9</option>
                      <option value="option0">Option 10</option> --}}
                    </select>
                    <br>
                    <button type="submit" class="btn btn-danger btn-block">save</button>
                </form>
            </div>
            
           </div>
        </div>
    </div>
  

@endsection

@section('js')
   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ URL::asset('assets/js/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            var demo1 = $('select[name="duallistbox_demo1[]"]').bootstrapDualListbox({
                nonSelectedListLabel: 'Available Options',
                selectedListLabel: 'Selected Options',
                moveOnSelect: false,
                filterTextClear: "Show All",
                filterPlaceHolder: "Search...",
                moveSelectedLabel: "Move Selected",
                moveAllLabel: "Move All",
                removeSelectedLabel: "Remove Selected",
                removeAllLabel: "Remove All",
            });
    
            // Store the initial selected items on page load
            var previousSelected = $("select[name='duallistbox_demo1[]'] option:selected")
                .map(function () {
                    return $(this).val();
                }).get();
    
            // Detect when an item is moved between lists
            $('select[name="duallistbox_demo1[]"]').on('change', function () {
                let currentSelected = $("select[name='duallistbox_demo1[]'] option:selected")
                    .map(function () {
                        return $(this).val();
                    }).get();
    
                // Compare previous and current selected to find moved items
                let movedToSelected = currentSelected.filter(item => !previousSelected.includes(item));
                let movedToAvailable = previousSelected.filter(item => !currentSelected.includes(item));
    
                // Update previous state
                previousSelected = [...currentSelected]; // Copy currentSelected to previousSelected
    
                // Log only moved items
                if (movedToSelected.length) {
                    console.log("Moved to Selected:", movedToSelected);
                }
                if (movedToAvailable.length) {
                    console.log("Moved to Available:", movedToAvailable);
                }
            });
    
            // Handle Form Submission
            $("#demoform").submit(function (e) {
                e.preventDefault(); // Prevent page refresh
    
                // Get all available (unselected) items
                let availableItems = $("select[name='duallistbox_demo1[]'] option:not(:selected)")
                    .map(function () {
                        return $(this).val();
                    }).get();
    
                // Get all selected items
                let selectedItems = $("select[name='duallistbox_demo1[]'] option:selected")
                    .map(function () {
                        return $(this).val();
                    }).get();
    
                console.log("Available Items:", availableItems);
                console.log("Selected Items:", selectedItems);

                alert("Available Items:" + availableItems + ' / Section '+ $('#availableSlct').val());
                alert("Selected Items:" + selectedItems + ' / Section '+ $('#selectedSlct').val());


                $.ajax({
                type: "get",
                url: "/changeManageSection/",
                data : {
                    leftSection : $('#availableSlct').val(),
                    leftData : availableItems,
                    rightSection : $('#selectedSlct').val(),
                    rightData : selectedItems,
                },
                success: function(response) {
                   

                    // $("select[name='duallistbox_demo1[]']").bootstrapDualListbox('refresh');
                }
            });
            });
        });

        let path = window.location.pathname;
        let storeid = path.split("/").pop();


        $(".sectionSlct").select2({
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
                        store_id: storeid
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

        $(".sectionSlct").change(function(e) {
            e.preventDefault();
            var sectionId = $(this).val();
            var listid = $(this).attr('id');
            var flag = "";
            if(listid == "availableSlct"){
                flag = "avialable";
            }else{
                flag = "selected";
            }
            // var storeId = $("#storeId").val();
            getAllDataInSection(sectionId, storeid,flag)
        });



        function getAllDataInSection(section_id, store_id,flag) {
            $.ajax({
                type: "get",
                url: "/getAllDataInSection/" + section_id + "/" + store_id,
                success: function(response) {
                    if(flag == "avialable"){
                        $("select[name='duallistbox_demo1[]'] option").remove();
                    }else{
                        $("select[name='duallistbox_demo1[]'] option:selected").remove();
                    }
                    
                    var i = 1;
                    response.forEach(element => {
                        if(element.amount > 0){
                            if(flag == "avialable"){
                                $("select[name='duallistbox_demo1[]']").append(`<option class="fs-18 fw-bold p-2" value="${store_id}-${element.part_id}-${element.source_id}-${element.status_id}-${element.quality_id}-${element.type_id}-${element.amount}">${element.part[0].name} الكمية ${element.amount}</option>`);
                            
                            }else{
                                $("select[name='duallistbox_demo1[]']").append(`<option class="fs-18 fw-bold p-2" selected="selected" value="${store_id}-${element.part_id}-${element.source_id}-${element.status_id}-${element.quality_id}-${element.type_id}-${element.amount}">${element.part[0].name} الكمية ${element.amount}</option>`);
                            }
                        }
                    });

                    $("select[name='duallistbox_demo1[]']").bootstrapDualListbox('refresh');
                }
            });
        }
    </script>
    
    




@endsection
