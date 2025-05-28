
$('#staticBackdrop').on('show.bs.modal', function (event) {
    $(this).find('#myTab button:first').tab('show');
});
$('#staticBackdrop').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
    $("#upload_img-wrapkitAdd").empty();
    // $("#ralatedPartTbl").empty();
    $("#kitPartTbl").empty();
    $("#kitBrandTbl").empty();
    // let table = document.getElementsByClassName('kitPartTbl');
    // for (let index = 0; index < table.length; index++) {
    //    table[index].remove();

    // }

    // $("#partSpecsTbl").empty();

});



$(document).ready(function () {
    ImgUpload();
    ImgUploadEdit();
    $(".supplierSlct").select2({
        dropdownParent: $('#staticBackdrop') ,
        dropdownAutoWidth : true
    });
    $("#brandtypeSlct").select2({
        dropdownParent: $('#staticBackdrop')
    });
    $("#brandSlct").select2({
        dropdownParent: $('#staticBackdrop')
    });
    $("#modelSlct").select2({
        dropdownParent: $('#staticBackdrop')
    });
    $("#seriesSlct").select2({
        dropdownParent: $('#staticBackdrop')
    });
    $(".partSlct").select2({
        dropdownParent: $('#staticBackdrop') ,
        dropdownAutoWidth : true
    });
        $(".partSlctEdit").select2({
        dropdownParent: $('#editKit') ,
        dropdownAutoWidth : true
    });

     table = $('#kitsDT').DataTable({
        dom: "Bfrtip",
        processing: true,
        serverSide: true,
        pageLength: 10,
        deferRender: true,
        responsive: true,
        destroy: true,
        ajax: "kitsdata",
        columns: [
            {data: 'DT_RowIndex', name: '#'},
            {data: 'Image', name: 'Image' ,sortable: false, searchable: false},
            {data: 'name', name: 'name', sortable: true, searchable: true  },
            {data: 'KitPart', name: 'KitPart'},
            {data: 'KitNumbers', name: 'KitNumbers'  },
            {data: 'KitBrand', name: 'KitBrand'  },
            {data: 'action', name: 'action', searchable: false ,orderable: false},
        ],

          columnDefs: [{
            targets: [3,4,5],
            className: 'scrollable-cell list-of-elements',
            orderable: true,
            searchable: true,
          }],
        buttons: [


            'print'
        ]

    });



    $(document).on('click', ".deletekitB" , function () {

        // alert('xxx');
            var row =table.row($(this).closest('tr')).data();
            var selected_kit_idx = $(this).attr("kit_id_value2");
            $("#kit_iddel").val(selected_kit_idx);
            $("#pdel_name").val(row['name']);

    //         $("#deletepartB form").attr("action", function() {
    //     return '{{route("part.destroy",["part" => $selected_part_idx])}}';
    // });
     var selected_part_idx = $(this).attr("kit_id_value2");
    // $("#deletepartB form").attr("action", '{{ route("part.destroy", ["selected_part_idx" => ' + selected_part_idx + ']) }}');
      $($("#deletekitB").find('form')[0]).prop('action','kit.destroy/'+selected_part_idx)


    });

    $.ajax({
        type: "get",
        url: "supplier",
        success: function (response) {
            $("#supplierSlct1").empty();
            $("#supplierSlct1").append(`<option value="0">Select Supplier</option>`);
            response.forEach(element => {
                $("#supplierSlct1").append(`<option value="${element.id}">${element.name}</option>`);
                SupplierSlct +=`<option value="${element.id}">${element.name}</option>`;
            });

        }
    });

    $.ajax({
        type: "get",
        url: "kitspecs",
        success: function (response) {
            $("#kitSpecsTbl").empty();
            $("#kitSpecsTblEdit").empty();
            response.forEach(element => {
                KitDetails +=` <tr data-id="${element.id}">
                <td>${element.name} <input type="hidden" value="${element.id}" name="oldSpecs[]"></td>
                <td><input class="form-control"  type="text" name="oldspecsval[]" id="oldspecsval${element.id}" value=""></td>
                <td onclick="$(this).closest('tr').remove();" class="bg-danger rounded px-4 border-bottom"><i class="ri-delete-bin-5-line">حذف</i></td>
            </tr>`;
            KitDetails1 +=` <tr data-id="${element.id}">
                <td>${element.name} <input type="hidden" value="${element.id}" name="oldSpecsEdit[]"></td>
                <td><input class="form-control"  type="text" name="oldspecsvalEdit[]" id="oldspecsvalEdit${element.id}" value=""></td>
                <td onclick="$(this).closest('tr').remove();" class="bg-danger rounded px-4 border-bottom"><i class="ri-delete-bin-5-line">حذف</i></td>
            </tr>`;

            });
            $("#kitSpecsTbl").append(KitDetails);
        }
    });

    $.ajax({
        type: "get",
        url: "part",
        success: function (response) {
            $("#partSlct").empty();
            $("#partSlctEdit").empty();
            $("#partSlct").append(`<option disabled selected value="0">Select Part</option>`);
            $("#partSlctEdit").append(`<option disabled selected value="0">Select Part</option>`);
            response.forEach(element => {
                $("#partSlct").append(`<option value="${element.id}">${element.name}</option>`);
                $("#partSlctEdit").append(`<option value="${element.id}">${element.name}</option>`);
            });

        }
    });
    $.ajax({
        type: "get",
        url: "kitBrand",
        success: function (response) {
            $("#brandtypeSlct").empty();
            $("#brandtypeSlctEdit").empty();
            $("#brandSlct").empty();
            $("#brandSlctEdit").empty();
            var brandtype = response[0];
            var brand = response[1];
            brandtype.forEach(element => {
                $("#brandtypeSlct").append(`<option value="${element.id}">${element.name}</option>`);
                $("#brandtypeSlctEdit").append(`<option value="${element.id}">${element.name}</option>`);
            });
            $("#brandSlct").append(`<option disabled selected value="0">Select Brand</option>`);
            $("#brandSlctEdit").append(`<option disabled selected value="0">Select Brand</option>`);
            brand.forEach(element => {
                $("#brandSlct").append(`<option value="${element.id}">${element.name}</option>`);
                $("#brandSlctEdit").append(`<option value="${element.id}">${element.name}</option>`);
            });

        }
    });
});

var SupplierSlct ;
var KitDetails ;
var KitDetails1 ;
var pni=1;
$("#newKitNumberRow").click(function (e) {
    pni++;
    e.preventDefault();

    $("#kitNumberTbl").append(` <tr>
                        <td><input class="form-control" type="text" name="kitNumber[]" id=""></td>
                        <td>
                            <select name="supplierSlct[]" class="form-control supplierSlct " id="supplierSlct${pni}">
                                ${SupplierSlct}
                            </select></td>
                        <td onclick="pni--; $(this).closest('tr').remove();">REMOVE</td>
                    </tr>`);
    $("#supplierSlct"+pni).select2({
        dropdownParent: $('#staticBackdrop') ,
        dropdownAutoWidth : true
    });
});

$("#newKitNumberRowEdit").click(function (e) {
    pni++;
    e.preventDefault();

    $("#kitNumberTblEdit").append(` <tr>
                        <td><input class="form-control" type="text" name="kitNumberEdit[]" id=""></td>
                        <td>
                            <select name="supplierSlctEdit[]" class="form-control supplierSlct " id="supplierSlctEdit${pni}">
                                ${SupplierSlct}
                            </select></td>
                        <td onclick="pni--; $(this).closest('tr').remove();">REMOVE</td>
                    </tr>`);
    $("#supplierSlctEdit"+pni).select2({
        dropdownParent: $('#editKit') ,
        dropdownAutoWidth : true
    });
});

$(document).on('click', ".editkitB" , function (e) {

    var selected_kit_id = $(this).attr("kit_id_value");
    $($("#editKit").find('form')[0]).prop('action','kit/'+selected_kit_id)
    $("#kit_id").val(selected_kit_id);
    $.ajax({
        type: "get",
        url: "kitIdData/"+selected_kit_id,
        success: function (response) {
            var kit = response[0];
            $("#kitSpecsTblEdit").empty();
            $("#kitPartTblEdit tbody").empty();
            $("#nameedit").val(kit.name);
            $("#engnameedit").val(kit.engname);
            $("#notesedit").val(kit.notes);
            $("#limitedit").val(kit.limit);
            console.log(kit);
            if(kit.notify == "1"){
                $('#notifyedit').prop( "checked", true );
            }
            if(kit.kit_parts.length > 0){
             $("#kitPartTblEdit tbody").empty();
             for(var i=0 ; i < kit.kit_parts.length ; i++){
                    $("#kitPartTblEdit tbody").append(`<tr>
                    <td><label>${kit.kit_parts[i].part.name}</label> <input type="hidden" name="kitPartEdit[]" value="${kit.kit_parts[i].part.id}"></td>
                   
                    <td>
                    <input class="form-control" type="text"  name="kitPartAmountEdit[]" value="${kit.kit_parts[i].amount}">
                    </td>
                    <td onclick="pni--; $(this).closest('tr').remove();">REMOVE</td>
                    /tr>`);
                }

            }

            if(kit.kit_numbers.length > 0){
                  $("#kitNumberTblEdit tbody").empty();
                
                for(var i=0 ; i < kit.kit_numbers.length ; i++){
                    
                    $("#kitNumberTblEdit tbody").append(`<tr><td>
                    <input class="form-control" type="text"  name="kitNumberEdit[]" value="${kit.kit_numbers[i].number}">
                    </td>
                    <td>
                        <select name="supplierSlctEdit[]" class="form-control supplierSlctEdit" id="${kit.kit_numbers[i].id}" >
                        ${SupplierSlct}
                        </select></td>
                    <td onclick="pni--; $(this).closest('tr').remove();">REMOVE</td>
                    /tr>`);
                    $("#"+kit.kit_numbers[i].id).val(kit.kit_numbers[i].supplier.id);
                }
            }
            $("#kitSpecsTblEdit").append(KitDetails1);
            if(kit.kit_details.length > 0){
                var table = $("#kitSpecsTblEdit tr");
                var oldSpecs=[];
                for (var i = 0; i < table.length; i++) {
                    rowid=$(table[i]).attr('data-id');
                    oldSpecs.push(rowid)
                }
                (kit.kit_details).forEach(element => {
                    var selectedSpec = element.kitpecs_id.toString();
                    var i = jQuery.inArray(selectedSpec, oldSpecs)
                    if(i != -1){
                        $("#oldspecsvalEdit"+oldSpecs[i]).val(element.value);
                    }else{
                        $("#kitSpecsTblEdit").append(` <tr>
                                    <td><input class="form-control" type="text" name="specsEdit[]" id="${element.kit_specs_id}" value="${element.kit_spec.name}" readonly></td>
                                    <td><input class="form-control" type="text" name="specsvalEdit[]" id="${element.id}" value="${element.value}"></td>
                                    <td onclick="pni--; $(this).closest('tr').remove();">REMOVE</td>
                                    </tr>`);
                    }
                });
            }
            if(kit.kit_models.length > 0){
                $("#kitBrandTblEdit").empty();
                for(var i=0 ; i < kit.kit_models.length ; i++){
                                          
                    $("#kitBrandTblEdit").append(`<tr>
                        <td>${kit.kit_models[i].series.model.brand_type.name}</td>
                        <td>${kit.kit_models[i].series.model.brand.name}</td>
                        <td>${kit.kit_models[i].series.model.name}</td>
                        <td>${kit.kit_models[i].series.name}<input type="hidden" name="seriesEdit[]" value="${kit.kit_models[i].series.id}"></td>
                        <td onclick="$(this).closest('tr').remove();kitBrandArr.splice(kitBrandArr.indexOf('${kit.kit_models[i].seriesId}'), 1);">Remove</td>
                    </tr>`);
                }
            }
            if(kit.kit_images.length > 0){
                $(".upload__img-wrapEdit").empty();
                for(var i=0 ; i < kit.kit_images.length ; i++){
                    $(".upload__img-wrapEdit").append(`<div class="upload__img-box"><div style="background-image: url(assets/kit_images/${kit.kit_images[i].image_url})" imageURL ="${kit.kit_images[i].image_url}" data-number="0" data-file="Screen Shot 2022-08-23 at 1.40.33 PM.png" class="img-bg"><div class="upload__img-close"></div></div></div>
                    `)
                }
            }
        }

    });
});

var kitPartArr = []
$(document).on('change', '#partSlct', function(){
    var partId= $(this).val();
    var partTxt = $("#partSlct option:selected" ).text();
    if($.inArray(partId,kitPartArr) >= 0){

    }else{
        kitPartArr.push(partId);
        $("#kitPartTbl").append(`<tr>
            <td>${partTxt} <input type="hidden" name="kitParts[]" value=${partId}></td>
            <td><input class="form-control" type="text" name="kitPartAmount[]" id="" required></td>
            <td onclick="$(this).closest('tr').remove();kitPartArr.splice(kitPartArr.indexOf('${partId}'), 1);">Remove</td>
        </tr>`)
    }

 });

 $(document).on('change', '#partSlctEdit', function(){
    var partId= $(this).val();
    var partTxt = $("#partSlctEdit option:selected" ).text();
    if($.inArray(partId,kitPartArr) >= 0){

    }else{
        kitPartArr.push(partId);
        $("#kitPartTblEdit").append(`<tr>
            <td>${partTxt} <input type="hidden" name="kitPartEdit[]" value=${partId}></td>
            <td><input class="form-control" type="text" name="kitPartAmountEdit[]" id="" required></td>
            <td onclick="$(this).closest('tr').remove();kitPartArr.splice(kitPartArr.indexOf('${partId}'), 1);">Remove</td>
        </tr>`)
    }

 });

$("#newKitSpecRow").click(function (e) {

    e.preventDefault();
    $("#kitSpecsTbl").append(` <tr>
                        <td><input class="form-control" type="text" name="specs[]" id=""></td>
                        <td><input class="form-control" type="text" name="specsval[]" id=""></td>
                        <td onclick="pni--; $(this).closest('tr').remove();">REMOVE</td>
                    </tr>`);

});

$("#newKitSpecRowEdit").click(function (e) {

    e.preventDefault();
    $("#kitSpecsTblEdit").append(` <tr>
                        <td><input class="form-control" type="text" name="specsEdit[]" id=""></td>
                        <td><input class="form-control" type="text" name="specsvalEdit[]" id=""></td>
                        <td onclick="pni--; $(this).closest('tr').remove();">REMOVE</td>
                    </tr>`);

});

$(document).on('change', '#brandSlct', function(){
    var brandid= $(this).val();
    var ptype_id= $("#brandtypeSlct").val();
    $.ajax({
        type: "get",
        url: "partmodel/"+brandid+"/"+ptype_id,
        success: function (response) {
            $("#modelSlct").empty();
            $("#modelSlct").append(`<option readonly value="">Select Brand</option>`);
            response.forEach(element => {
                $("#modelSlct").append(`<option value="${element.id}">${element.name}</option>`);
            });

        }
    });
});

$(document).on('change', '#brandSlctEdit', function(){
    var brandid= $(this).val();
    var ptype_id= $("#brandtypeSlctEdit").val();
    $.ajax({
        type: "get",
        url: "partmodel/"+brandid+"/"+ptype_id,
        success: function (response) {
            $("#modelSlctEdit").empty();
            $("#seriesSlctEdit").empty();
            // $("#modelSlctEdit").append(`<option readonly value="">Select Brand</option>`);
            response.forEach(element => {
                $("#modelSlctEdit").append(`<option value="${element.id}">${element.name}</option>`);
            });

        }
    });
});

$(document).on('change', '#modelSlct', function(){
    var modelId= $(this).val();

    $.ajax({
        type: "get",
        url: "partseries/"+modelId,
        success: function (response) {
            $("#seriesSlct").empty();
            // $("#seriesSlct").append(`<option readonly value="">Select Brand</option>`);
            response.forEach(element => {
                $("#seriesSlct").append(`<option value="${element.id}">${element.name}</option>`);
            });
        }
    });
});

$(document).on('change', '#modelSlctEdit', function(){
    var modelId= $(this).val();
    $.ajax({
        type: "get",
        url: "partseries/"+modelId,
        success: function (response) {
            $("#seriesSlctEdit").empty();
            // $("#seriesSlctEdit").append(`<option readonly value="">Select Brand</option>`);
            response.forEach(element => {
                $("#seriesSlctEdit").append(`<option value="${element.id}">${element.name}</option>`);
            });
        }
    });
});

var kitBrandArr=[];
$(document).on('change', '#seriesSlct', function(){
    var seriesId= $(this).val();
    var typeTxt = $("#brandtypeSlct option:selected" ).text();
    var seriesTxt= $( "#seriesSlct option:selected" ).text();
    var modelTxt= $( "#modelSlct option:selected" ).text();
    var brandTxt= $( "#brandSlct option:selected" ).text();
    if($.inArray(seriesId,kitBrandArr) >= 0){

    }else{
        kitBrandArr.push(seriesId);
        $("#kitBrandTbl").append(`<tr>
            <td>${typeTxt}</td>
            <td>${brandTxt}</td>
            <td>${modelTxt}</td>
            <td>${seriesTxt}<input type="hidden" name="series[]" value=${seriesId}></td>
            <td onclick="$(this).closest('tr').remove();kitBrandArr.splice(kitBrandArr.indexOf('${seriesId}'), 1);">Remove</td>
        </tr>`)
    }

 });

$("#selectallmodel").click(function (e) {
    e.preventDefault();
    if (parseInt($("#brandSlct").val()) > 0) {
        $("#modelSlct option").each(function () {
            $(this).prop("selected", true).trigger("change");
            $("#selectallseries").click();
        });
    } else {
        alert("Please Select Brand");
    }
});

$("#selectallseries").click(function (e) {
    e.preventDefault();
    if (parseInt($("#modelSlct").val()) > 0) {
        $("#seriesSlct option").each(function () {
             if(parseInt($(this).val()) != 0 ){
                $(this).prop("selected", true).trigger("change");
            }
        });
    } else {
        alert("Please Select Brand");
    }
});


$("#selectallmodelEdit").click(function (e) {
    e.preventDefault();
    if (parseInt($("#brandSlctEdit").val()) > 0) {
        $("#modelSlctEdit option").each(function () {
            $(this).prop("selected", true).trigger("change");
            $("#selectallseriesEdit").click();
        });
    } else {
        alert("Please Select Brand");
    }
});
$("#selectallseriesEdit").click(function (e) {
    e.preventDefault();
    if (parseInt($("#modelSlctEdit").val()) > 0) {
        $("#seriesSlctEdit option").each(function () {
            if(parseInt($(this).val()) != 0 ){
                $(this).prop("selected", true).trigger("change");
            }
        });
    } else {
        alert("Please Select Brand");
    }
});


 var imgURL =[];
$(document).on('click' , '.upload__img-close',function(){
    imgURL.push($(this).parent().attr('imageURL'));
    $('[name="imgURLsInp[]"').val(imgURL.join(' , '));
    // console.log(imgURL);

});

 $(document).on('change', '#seriesSlctEdit', function(){
    var seriesId= $(this).val();
    var typeTxt = $("#brandtypeSlctEdit option:selected" ).text();
    var seriesTxt= $( "#seriesSlctEdit option:selected" ).text();
    var modelTxt= $( "#modelSlctEdit option:selected" ).text();
    var brandTxt= $( "#brandSlctEdit option:selected" ).text();
    if($.inArray(seriesId,kitBrandArr) >= 0){

    }else{
        kitBrandArr.push(seriesId);
        $("#kitBrandTblEdit").append(`<tr>

            <td>${typeTxt}</td>
            <td>${brandTxt}</td>
            <td>${modelTxt}</td>
            <td>${seriesTxt}<input type="hidden" name="seriesEdit[]" value=${seriesId}></td>
            <td onclick="$(this).closest('tr').remove();kitBrandArr.splice(kitBrandArr.indexOf('${seriesId}'), 1);">Remove</td>
        </tr>`)
    }

 });

function ImgUpload() {
    var imgWrap = "";
    var imgArray = [];

    $('.upload__inputfile').each(function () {
      $(this).on('change', function (e) {
        imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap');
        var maxLength = $(this).attr('data-max_length');
        var files = e.target.files;
        var filesArr = Array.prototype.slice.call(files);
        var iterator = 0;
        filesArr.forEach(function (f, index) {

          if (!f.type.match('image.*')) {
            return;
          }

          if (imgArray.length > maxLength) {
            return false
          } else {
            var len = 0;
            for (var i = 0; i < imgArray.length; i++) {
              if (imgArray[i] !== undefined) {
                len++;
              }
            }
            if (len > maxLength) {
              return false;
            } else {
              imgArray.push(f);

              var reader = new FileReader();
              reader.onload = function (e) {
                var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div></div>";
                imgWrap.append(html);
                iterator++;
              }
              reader.readAsDataURL(f);
            }
          }
        });
      });
    });

    $('body').on('click', ".upload__img-close", function (e) {
      var file = $(this).parent().data("file");
      for (var i = 0; i < imgArray.length; i++) {
        if (imgArray[i].name === file) {
          imgArray.splice(i, 1);
          break;
        }
      }
      $(this).parent().parent().remove();
    });
  }


  function ImgUploadEdit() {
    var imgWrap = "";
    var imgArray = [];

    $('.upload__inputfileEdit').each(function () {
      $(this).on('change', function (e) {
        imgWrap = $(this).closest('.upload__box').find('.upload__img-wrapEdit');
        var maxLength = $(this).attr('data-max_length');
        var files = e.target.files;
        var filesArr = Array.prototype.slice.call(files);
        var iterator = 0;
        filesArr.forEach(function (f, index) {

          if (!f.type.match('image.*')) {
            return;
          }

          if (imgArray.length > maxLength) {
            return false
          } else {
            var len = 0;
            for (var i = 0; i < imgArray.length; i++) {
              if (imgArray[i] !== undefined) {
                len++;
              }
            }
            if (len > maxLength) {
              return false;
            } else {
              imgArray.push(f);

              var reader = new FileReader();
              reader.onload = function (e) {
                var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div></div>";
                imgWrap.append(html);
                iterator++;
              }
              reader.readAsDataURL(f);
            }
          }
        });
      });
    });

    $('body').on('click', ".upload__img-close", function (e) {
      var file = $(this).parent().data("file");
      for (var i = 0; i < imgArray.length; i++) {
        if (imgArray[i].name === file) {
          imgArray.splice(i, 1);
          break;
        }
      }
      $(this).parent().parent().remove();
    });
  }

