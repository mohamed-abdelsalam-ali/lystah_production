
$(document).ready(function () {

$("#filterBtn").trigger('click');
});

$(document).on('change', '#groupSlct', function(){
    var groupid= $(this).val();
    $.ajax({
        type: "get",
        url: "group/"+groupid,
        success: function (response) {
            $("#SgroupSlct").empty();
            $("#SgroupSlct").append(`<option value="0">Select Sub Groups</option>`);
            response.forEach(element => {
                $("#SgroupSlct").append(`<option value="${element.id}">${element.name}</option>`);
            });
        }
    });
})
$(document).on('change', '#brandSlct', function(){
    var brandid= $(this).val();
    var ptype_id= $("#brandtypeSlct").val();
    if(ptype_id > 0){
        $.ajax({
            type: "get",
            url: "partmodel/"+brandid+"/"+ptype_id,
            success: function (response) {
                $("#resultDiv").empty();
                $("#resultTitletxt").text('Result => ' + $( "#brandSlct option:selected" ).text() );

                response.forEach(element => {
                    $("#resultDiv").append(`<div class="col-sm-12 col-lg-4" data-id="${element.id}" onclick="GetModel(${element.id})">
                        <div class="row border rounded res m-0">
                            <div class="col-lg-8 col-sm-12">
                                <img style="width:135px;height:135px;" class="p-2" src="assets/part_images/categoryimg/${element.mod_img_name}" alt="">
                            </div>
                            <div class="col-lg-4 col-sm-12  my-auto"><span class="text-nowrap">${element.name}</span></div>
                        </div>
                    </div>`);
                });
            }
        });
    }else{
        alert("Select Type First")
    }

})

$(document).on('change', '#brandtypeSlct', function(){
    var ptype_id= $(this).val();
    var brandid= $("#brandSlct").val();

        $.ajax({
            type: "get",
            url: "partmodel/"+brandid+"/"+ptype_id,
            success: function (response) {
                $("#resultDiv").empty();
                $("#resultTitletxt").text('Result => ' + $( "#brandSlct option:selected" ).text() );

                // $("#modelSlct").append(`<option value="0">Select Model</option>`);
                response.forEach(element => {
                    $("#resultDiv").append(`<div class="col-4" data-id="${element.id}">
                        <div class="row border rounded m-0 res">
                            <div class="col-lg-8 col-sm-12">
                                <img style="width:135px;height:135px;" class="p-2" src="assets/part_images/${element.mod_img_name}" alt="">
                            </div>
                            <div class="col-lg-4 col-sm-12 my-auto"><span class="text-nowrap">${element.name}</span></div>
                        </div>
                    </div>`);
                });
            }
        });


})

$(document).on('change', '#modelSlct', function(){
    var modelId= $(this).val();

    $.ajax({
        type: "get",
        url: "partseries/"+modelId,
        success: function (response) {
            $("#resultDiv").empty();
            $("#resultTitletxt").text('Result => ' + $( "#modelSlct option:selected" ).text() );

            response.forEach(element => {
                $("#resultDiv").append(`<div class="col-lg-4 col-sm-12" data-id="${element.id}" onclick="GetSeries(${element.id})">
                    <div class="row border rounded res m-0">
                        <div class="col-lg-8 col-sm-12">
                            <img style="width:135px;height:135px;" class="p-2" src="assets/part_images/categoryimg/${element.model.mod_img_name}" alt="">
                        </div>
                        <div class="col-lg-4 col-sm-12  my-auto"><span class="text-nowrap">${element.name}</span></div>
                    </div>
                </div>`);
            });
        }
    });
})

$(document).on('change', '#seriesSlct', function(){
    var seriesId= $(this).val();

    $.ajax({
        type: "get",
        url: "partSearchSeries/"+seriesId,
        success: function (response) {
            $("#resultDiv").empty();

            $("#resultTitletxt").text('Result => ' + $( "#seriesSlct option:selected" ).text() );

            response.forEach(element => {
                $("#resultDiv").append(`<div class="col-lg-4 col-sm-12" data-id="${element.group.id}" onclick="GetSubGroup(${element.group.id})">
                    <div class="row border rounded res m-0">
                        <div class="col-lg-8 col-sm-12">
                            <img style="width:135px;height:135px;" class="p-2" src="assets/part_images/categoryimg/000" alt="">
                        </div>
                        <div class="col-lg-4 col-sm-12  my-auto"><span class="text-nowrap">${element.group.name}</span></div>
                    </div>
                </div>`);
            });
        }
    });

})

$(document).on('change', '#supplierSlct', function(){
    var supplierid= $(this).val();

    $.ajax({
        type: "get",
        url: "brandTypeUnderSupplier/"+supplierid,
        success: function (response) {
            $("#resultDiv").empty();
            $("#resultTitletxt").text('Result => ' + $( "#brandSlct option:selected" ).text() );

            response.forEach(element => {
                $("#resultDiv").append(`<div class="col-lg-4 col-sm-12" data-id="${element.id}" onclick="GetBrandUnderSupplier(${supplierid},${element.id})">
                    <div class="row border rounded res m-0">
                        <div class="col-lg-8 col-sm-12">
                            <img style="width:135px;height:135px;" class="p-2" src="assets/part_images/categoryimg/${element.mod_img_name}" alt="">
                        </div>
                        <div class="col-lg-4 col-sm-12  my-auto"><span class="text-nowrap">${element.name}</span></div>
                    </div>
                </div>`);
            });
        }
    });

})

$("#partNumberSearchTxt").keypress(function (e) {
    var key = e.which;
    var searchValue = $(this).val();
    if(key == 13){
        e.preventDefault();
        $.ajax({
            type: "get",
            url: "partsSearchNumber/"+searchValue,
            success: function (response) {
                $("#resultDiv").empty();
                $("#resultTitletxt").text('Result Part with Number => ' + $( "#partNumberSearchTxt" ).val() );
                draw_cards(response);

            }
        });
    }
});






function GetModel(brandId){
    var modelId= brandId;

    $.ajax({
        type: "get",
        url: "partseries/"+modelId,
        success: function (response) {
            $("#resultDiv").empty();
            $("#resultTitletxt").text('Result => ' + $( "#modelSlct option:selected" ).text() );

            response.forEach(element => {
                $("#resultDiv").append(`<div class="col-lg-4 col-sm-12" data-id="${element.id}" onclick="GetSeries(${element.id})">
                    <div class="row border rounded res m-0">
                        <div class="col-lg-8 col-sm-12">
                            <img style="width:135px;height:135px;" class="p-2" src="assets/part_images/categoryimg/${element.model.mod_img_name}" alt="">
                        </div>
                        <div class="col-lg-4 col-sm-12  my-auto"><span class="text-nowrap">${element.name}</span></div>
                    </div>
                </div>`);
            });
        }
    });
}

function GetSeries(modelId){
    var seriesId= modelId;

    $.ajax({
        type: "get",
        url: "partSearchSeries/"+seriesId,
        success: function (response) {
            $("#resultDiv").empty();

            $("#resultTitletxt").text('Result => ' + $( "#seriesSlct option:selected" ).text() );

            response.forEach(element => {
                $("#resultDiv").append(`<div class="col-lg-4 col-sm-12" data-id="${element.group.id}" onclick="GetSubGroup(${element.group.id})">
                    <div class="row border rounded res m-0">
                        <div class="col-lg-8 col-sm-12">
                            <img style="width:135px;height:135px;" class="p-2" src="assets/part_images/categoryimg/000" alt="">
                        </div>
                        <div class="col-lg-4 col-sm-12  my-auto"><span class="text-nowrap">${element.group.name}</span></div>
                    </div>
                </div>`);
            });
        }
    });
}

function GetSubGroup(groupid){
    var groupid= groupid;

    $.ajax({
        type: "get",
        url: "partsubgroup/"+groupid,
        success: function (response) {
            $("#resultDiv").empty();

            $("#resultTitletxt").text('Result');

            response.forEach(element => {
                $("#resultDiv").append(`<div class="col-lg-4 col-sm-12" data-id="${element.id}" onclick="GetPartUnderSubGroup(${element.id})">
                    <div class="row border rounded res m-0">
                        <div class="col-lg-8 col-sm-12">
                            <img style="width:135px;height:135px;" class="p-2" src="assets/part_images/categoryimg/000" alt="">
                        </div>
                        <div class="col-lg-4 col-sm-12  my-auto"><span class="text-nowrap">${element.name}</span></div>
                    </div>
                </div>`);
            });
        }
    });
}

function GetPartUnderSubGroup(subgroupId){
    // alert("GetPartUnderSubGroup");
    $.ajax({
        type: "get",
        url: "partUnderSubGroup/"+subgroupId,
        success: function (response) {


            $("#resultDiv").empty();

            $("#resultTitletxt").text('Result');
            draw_cards(response);



        }
    });
}


function GetBrandUnderSupplier(supplierId,typeId){
    var supplierid= supplierId;

    $.ajax({
        type: "get",
        url: "brandUnderSupplier/"+supplierid+"/"+typeId,
        success: function (response) {
            $("#resultDiv").empty();
            $("#resultTitletxt").text('Result => ' + $( "#brandSlct option:selected" ).text() );

            response.forEach(element => {
                $("#resultDiv").append(`<div class="col-lg-4 col-sm-12" data-id="${element.id}" onclick="GetModelUnderSupplier(${supplierId},${element.id})">
                    <div class="row border rounded res m-0">
                        <div class="col-lg-8 col-sm-12">
                            <img style="width:135px;height:135px;" class="p-2" src="assets/part_images/categoryimg/${element.mod_img_name}" alt="">
                        </div>
                        <div class="col-lg-4 col-sm-12  my-auto"><span class="text-nowrap">${element.name}</span></div>
                    </div>
                </div>`);
            });
        }
    });
}

function GetModelUnderSupplier(supplierId,brandId){
    var supplierid= supplierId;

    $.ajax({
        type: "get",
        url: "ModelUnderSupplier/"+supplierid+"/"+brandId,
        success: function (response) {
            $("#resultDiv").empty();
            $("#resultTitletxt").text('Result => ' + $( "#brandSlct option:selected" ).text() );

            response.forEach(element => {
                $("#resultDiv").append(`<div class="col-lg-4 col-sm-12" data-id="${element.id}" onclick="GetSeriesUnderSupplier(${supplierId},${element.id})" >
                    <div class="row border rounded res m-0">
                        <div class="col-lg-8 col-sm-12">
                            <img style="width:135px;height:135px;" class="p-2" src="assets/part_images/categoryimg/${element.mod_img_name}" alt="">
                        </div>
                        <div class="col-lg-4 col-sm-12 my-auto"><span class="text-nowrap">${element.name}</span></div>
                    </div>
                </div>`);
            });
        }
    });
}

function GetSeriesUnderSupplier(supplierId,modelId){
    $.ajax({
        type: "get",
        url: "SeriesUnderSupplier/"+supplierId+"/"+modelId,
        success: function (response) {
            $("#resultDiv").empty();
            $("#resultTitletxt").text('Result => ' + $( "#brandSlct option:selected" ).text() );

            response.forEach(element => {
                $("#resultDiv").append(`<div class="col-lg-4 col-sm-12" data-id="${element.id}" onclick="GetPartsUnderSupplier(${supplierId},${element.id})" >
                    <div class="row border rounded res m-0">
                        <div class="col-lg-8 col-sm-12">
                            <img style="width:135px;height:135px;" class="p-2" src="assets/part_images/categoryimg/${element.mod_img_name}" alt="">
                        </div>
                        <div class="col-lg-4 col-sm-12 my-auto"><span class="text-nowrap">${element.name}</span></div>
                    </div>
                </div>`);
            });
        }
    });
}

function GetPartsUnderSupplier(supplierId,seriesId){
    $.ajax({
        type: "get",
        url: "PartUnderSupplier/"+supplierId,
        success: function (response) {
            $("#resultDiv").empty();
            $("#resultTitletxt").text('Result => ' + $( "#brandSlct option:selected" ).text() );
            draw_cards(response);

        }
    });
}

$("#searchresultDiv").keyup(function (e) {
    var searchValue = $(this).val();
    $('.res').hide();
    $('.res:contains("'+searchValue+'")').show();
});

function draw_cards(response){

    response.forEach(element => {
        var pNumber = "";
        if(element.part[0].part_numbers.length == 0){
            pNumber = "No Numbers";
        }
        for (let i = 0; i < element.part[0].part_numbers.length; i++) {
            pNumber +="<li>"+element.part[0].part_numbers[i].number+"</li>"

        }

        var pSpecs = "";
        if(element.part[0].part_details.length == 0){
            pSpecs = "No Specs";
        }
        for (let i = 0; i < element.part[0].part_details.length; i++) {
            pSpecs +="<li>"+element.part[0].part_details[i].part_spec.name+" / "+element.part[0].part_details[i].value+"</li>"

        }

        var pModel = "";
        if(element.part[0].part_models.length == 0){
            pModel = "No Models";
        }
        for (let i = 0; i < element.part[0].part_models.length; i++) {
            // pModel += ". "+element.part[0].part_models[i].series.model.brand.name+"."
            pModel +="<li>"+element.part[0].part_models[i].series.name +" / "+element.part[0].part_models[i].series.model.name+" / "+element.part[0].part_models[i].series.model.brand.name+"</li>"

        }

        var pImg = "";
        var cardImg = "";
        if(element.part[0].part_images.length == 0){
            pImg = "No Image";
        }
        for (let i = 0; i < element.part[0].part_images.length; i++) {
            pImg +=`<div class="col p-0 img-hover-zoom"><img class="img-fluid p-2 rounded-4 rounded-circle header-profile-user" src="assets/part_images/${element.part[0].part_images[i].image_name}" alt=""></div>`
            cardImg = element.part[0].part_images[0].image_name;
        }
        var pStores="";
        if(element.stores.length == 0){
            pStores = "No Stores";
        }
        for (let i = 0; i < element.stores.length; i++) {

                    // pPrice +=`<span class="p-2 text-bg-info text-black" title="${element.price[i].sale_type.type}">${element.price[i].price}</span>`
                    pStores +=`<tr class="text-nowrap text-center" id="${element.stores[i].id}"><td>${element.stores[i].name}</td><td>${element.stores[i].storepartCount}</td></tr>`;
        }
        var pPrice = "";
        var HighPrice = 0;
        if(element.price.length == 0){
            pPrice = "No Pricing";
        }
        for (let i = 0; i < element.price.length; i++) {
                    if(HighPrice < element.price[i].price){
                        HighPrice = element.price[i].price;
                    }
                    // pPrice +=`<span class="p-2 text-bg-info text-black" title="${element.price[i].sale_type.type}">${element.price[i].price}</span>`
                    pPrice +=`<tr class="text-nowrap text-center"><td>${element.price[i].sale_type.type}</td><td>${element.price[i].price}</td></tr>`;
        }

        if(element.allpart.length > 0){
                $("#resultDiv").append(`<div class="col-lg-12 res p-1 " data-part-id="${element.part_id}" data-source-id="${element.source_id}" data-status-id="${element.status_id}" data-quality-id="${element.quality_id}" >

                    <div class="row border rounded res m-0 text-center">
                    <div class="col-lg-2 col-sm-12">
                        <div class="row">

                            ${pImg}

                        </div>
                    </div>
                <!-- <div class="col-lg-3 col-sm-12">
                        <span>${element.part[0].id}</span><span class="fs-20 p-1 px-3 rounded-4 text-bg-dark text-nowrap">${element.part[0].name}</span>
                        <ul>
                            ${pNumber}
                        </ul>
                    </div> -->
                    <div class="col-lg-3 col-sm-12">
                    <span>${element.part[0].id}</span><span class="fs-20 p-1 px-3 rounded-4 text-bg-dark text-nowrap">${element.part[0].name}</span>
                            <p>
                            <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample${element.part_id}" role="button" aria-expanded="false" aria-controls="collapseExample${element.part_id}">
                            Numbers
                            </a>
                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample1${element.part_id}" aria-expanded="false" aria-controls="collapseExample1${element.part_id}">
                            Models
                            </button>
                        </p>
                        <div class="collapse" id="collapseExample${element.part_id}">
                            <div class="card card-body text-bg-success">
                            <ul>
                                ${pNumber}
                            </ul>
                            </div>
                        </div>
                        <div class="collapse" id="collapseExample1${element.part_id}">
                        <div class="card card-body text-bg-light">

                            <ul>
                            ${pModel}
                        </ul>
                        </div>
                    </div>
                    </div>
                    <div class="col-lg-4 col-sm-12 pt-3">

                    <span class="d-inline-block" tabindex="0" data-html="true" data-bs-toggle="tooltip" title="${pModel}">
                        <button class="btn btn-info d-none" type="button" disabled>View Brands</button>
                    </span>
                    ${pSpecs}

                    </div>
                    <div class="col-lg-2 col-sm-12 p-2">

                        <span class="p-1 rounded text-bg-secondary">${element.source[0].name_arabic}</span>
                        <span class="p-1 rounded text-bg-primary">${element.status[0].name}</span>
                        <span class="p-1 rounded text-bg-danger">${element.part_quality[0].name}</span>
                        <hr>
                        <span class="p-2 text-bg-warning text-black" onclick="ToggleStore(this)">${element.total}</span>
                            <div class="storelist" >
                                <table class="bg-body border h6 table">
                                ${pStores}
                                </table>
                            </div>

                        <span class="p-2 text-bg-info text-black" style="" onclick="TogglePrice(this)"><i class="bx bx-category-alt fs-22"></i></span>
                        <div class="pricelist" >
                            <table class="bg-body border h6 table">
                            ${pPrice}
                            </table>
                        </div>

                    </div>
                    <div class="col-lg-1 col-sm-12 p-2">
                        <button type="button" onclick="addtopasket(this ,'${element.part_id}','${element.part[0].name}','${cardImg}','${element.source_id}','${element.status_id}','${element.quality_id}','1','${HighPrice}')" class="btn btn-success"><i class="bx bx-shopping-bag fs-22" aria-hidden="true"></i>Add</button>
                    </div>
                </div>
            </div>



            `);
        }else{
            $("#resultDiv").append(`<div class="col-12 res p-1 " data-part-id="${element.id}"  >

            <div class="row border rounded res m-0 text-center">
            <div class="col-lg-2 col-sm-12">
                <div class="row">

                    ${pImg}

                </div>
            </div>
        <!-- <div class="col-lg-3 col-sm-12">
                <span>${element.part[0].id}</span><span class="fs-20 p-1 px-3 rounded-4 text-bg-dark text-nowrap">${element.part[0].name}</span>
                <ul>
                    ${pNumber}
                </ul>
            </div> -->
            <div class="col-lg-3 col-sm-12">
            <span>${element.part[0].id}</span><span class="fs-20 p-1 px-3 rounded-4 text-bg-dark text-nowrap">${element.part[0].name}</span>
                    <p>
                    <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample${element.id}" role="button" aria-expanded="false" aria-controls="collapseExample${element.id}">
                    Numbers
                    </a>
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample1${element.id}" aria-expanded="false" aria-controls="collapseExample1${element.id}">
                    Models
                    </button>
                </p>
                <div class="collapse" id="collapseExample${element.id}">
                    <div class="card card-body text-bg-success">
                    <ul>
                        ${pNumber}
                    </ul>
                    </div>
                </div>
                <div class="collapse" id="collapseExample1${element.id}">
                <div class="card card-body text-bg-light">

                    <ul>
                    ${pModel}
                </ul>
                </div>
            </div>
            </div>
            <div class="col-lg-4 col-sm-12 pt-3">

            <span class="d-inline-block" tabindex="0" data-html="true" data-bs-toggle="tooltip" title="${pModel}">
                <button class="btn btn-info d-none" type="button" disabled>View Brands</button>
            </span>
            ${pSpecs}

            </div>
            <div class="col-lg-2 col-sm-12 p-2">



            </div>
            <div class="col-lg-1 col-sm-12 p-2">

            </div>
        </div>
    </div>



    `);
        }




        // tooltip options
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
    /////
    });

    $(".pricelist").toggle();
    $(".storelist").toggle();

    $("#resultDiv > div:nth-child(even)").addClass("bg-soft-success");
}

function TogglePrice(el){
    $(el).parent().find('.pricelist').toggle();
}

function ToggleStore(el){
    $(el).parent().find('.storelist').toggle();
}




$(document).on('keypress',"#searchNamebtn",function(e) {
    if(e.which == 13) {
        var Name = $(this).val();
        $.ajax({
            type: "get",
            url: "/partsSearchName/"+Name,
            success: function (response) {
                $("#resultDiv").empty();
                $("#resultTitletxt").text('Result Part with Number => ' + $( "#partNumberSearchTxt" ).val() );
                draw_cards(response);

            }
    });
    }
});

$("#partNumberSearchTxt1").keypress(function (e) {
    var key = e.which;
    var searchValue = $(this).val();
    if(key == 13){
        e.preventDefault();
        $.ajax({
            type: "get",
            url: "/partsSearchNumber/"+searchValue,
            success: function (response) {
                $("#resultDiv").empty();
                $("#resultTitletxt").text('Result Part with Number => ' + $( "#partNumberSearchTxt" ).val() );
                draw_cards(response);

            }
        });
    }
});
