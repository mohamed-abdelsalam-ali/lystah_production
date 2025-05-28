$('#staticBackdrop').on('show.bs.modal', function (event) {
    $(this).find('#myTab button:first').tab('show');
});
$('#staticBackdrop').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
    $(".upload__img-wrapWheel").empty();
    // $("#wheelSpecsTbl").empty();
    $('.specsvaltxt').val('');
    $("#wheelTbl").empty();
    $(".AddwheelSpecsTblNew").closest('tr').remove();

  

    // $("#partSpecsTbl").empty();

});
var wheeltable ='';
$(document).ready(function () {

    ImgUpload();
    ImgUploadEdit();
    $("#relatedWheelSlct").select2({
        dropdownParent: $('#staticBackdrop'),
        dropdownAutoWidth : true
    });
    $("#relatedWheelEditSlct").select2({
        dropdownParent: $('#editWheel'),
        dropdownAutoWidth : true
    });

    $("#dimtypeSlct").select2({
        dropdownParent: $('#staticBackdrop')
    });

    $("#dimtypeEditSlct").select2({
        dropdownParent: $('#editWheel')
    });

    $("#materialtypeSlct").select2({
        dropdownParent: $('#staticBackdrop')
    });

    $("#materialtypeEditSlct").select2({
        dropdownParent: $('#editWheel')
    });

    $("#modeltypeSlct").select2({
        dropdownParent: $('#staticBackdrop')
    });

    $("#modeltypeEditSlct").select2({
        dropdownParent: $('#editWheel')
    });


     wheeltable = $('#wheelsDT').DataTable({
        dom: "Bfrtip",
        processing: true,
        serverSide: true,
        pageLength: 10,
        deferRender: true,
        responsive: true,
        destroy: true,
        ajax: "wheelsdata",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'image', name: 'image' },
            {data: 'name', name: 'name'},
            {data: 'dimension', name: 'dimension'},
            {data: 'model', name: 'model'},
            {data: 'material', name: 'material'},

            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],

        buttons: [

            "print",
        ],

    });

    $.ajax({
        type: "get",
        url: "wheeldimensions",
        success: function (response) {
            $("#dimtypeSlct").empty();
            $("#dimtypeSlct").append(`<option readonly selected  value="" readonly>Select Dimensions</option>`);
            $("#dimtypeEditSlct").empty();
            $("#dimtypeEditSlct").append(`<option readonly selected  value="" readonly>Select Dimensions</option>`);

            response.forEach(element => {
                $("#dimtypeSlct").append(`<option value="${element.id}">${element.dimension}</option>`);
                $("#dimtypeEditSlct").append(`<option value="${element.id}">${element.dimension}</option>`);
            });

        }
    });

    $.ajax({
        type: "get",
        url: "wheelmodel",
        success: function (response) {
            $("#modeltypeSlct").empty();
            $("#modeltypeSlct").append(`<option readonly selected value=""  >Select Brand</option>`);
            $("#modeltypeEditSlct").empty();
            $("#modeltypeEditSlct").append(`<option readonly selected value=""  >Select Brand</option>`);
            response.forEach(element => {
                $("#modeltypeSlct").append(`<option value="${element.id}">${element.name}</option>`);
                $("#modeltypeEditSlct").append(`<option value="${element.id}">${element.name}</option>`);
            });

        }
    });

    $.ajax({
        type: "get",
        url: "wheelmaterial",
        success: function (response) {
            $("#materialtypeSlct").empty();
            $("#materialtypeSlct").append(`<option  value="" readonly selected >Select Material</option>`);
            $("#materialtypeEditSlct").empty();
            $("#materialtypeEditSlct").append(`<option  value="" readonly selected >Select Material</option>`);
            response.forEach(element => {
                $("#materialtypeSlct").append(`<option value="${element.id}">${element.name}</option>`);
                $("#materialtypeEditSlct").append(`<option value="${element.id}">${element.name}</option>`);
            });

        }
    });

    $.ajax({
        type: "get",
        url: "wheelspecs",
        success: function (response) {
            $("#wheelSpecsTbl").empty();
            $("#wheelSpecsEditTbl").empty();
            response.forEach(element => {
                wheelDetails +=` <tr data-id="${element.id}">
                    <td>${element.name} <input type="hidden" value="${element.id}" name="oldSpecs[]"></td>
                    <td><input class="form-control specsvaltxt"  type="text" name="oldspecsval[]" id="oldspecsval${element.id}" value=""></td>
                    <td onclick="$(this).closest('tr').remove();" style="font-size:24px;" class="text-danger text-center pointer rounded px-4"><i class="ri-delete-bin-5-line"></i></td>

                    </tr>`;
                wheelDetails1 +=` <tr data-id="${element.id}">
                    <td>${element.name} <input type="hidden" value="${element.id}" name="oldSpecsEdit[]"></td>
                    <td><input class="form-control specsvaltxt"  type="text" name="oldspecsvalEdit[]" id="oldspecsvalEdit${element.id}" value=""></td>

                    <td onclick=" $(this).closest('tr').remove();" style="font-size:24px;" class="text-danger text-center pointer rounded px-4"><i class="ri-delete-bin-5-line"></i></td>
                </tr>`;
            });
            $("#wheelSpecsTbl").append(wheelDetails);

        }
    });

    $.ajax({
        type: "get",
        url: "wheel",
        success: function (response) {
            $("#relatedWheelSlct").empty();
            $("#relatedWheelSlct").append(`<option selected readonly value="">Select Wheels</option>`);
            $("#relatedWheelEditSlct").empty();
            $("#relatedWheelEditSlct").append(`<option selected readonly value="">Select Wheels</option>`);
            response.forEach(element => {
                $("#relatedWheelSlct").append(`<option value="${element.id}">${element.name}</option>`);
                $("#relatedWheelEditSlct").append(`<option value="${element.id}">${element.name}</option>`);
            });

        }
    });

});

var pni=1;
var wheelDetails = ""
var wheelDetails1=""
$("#newWheelSpecRow").click(function (e) {

    e.preventDefault();
    $("#wheelSpecsTbl").append(` <tr class="AddwheelSpecsTblNew">
                        <td><input class="form-control " type="text" name="specs[]" id=""></td>
                        <td><input class="form-control specsvaltxt" type="text" name="specsval[]" id=""></td>

                        <td onclick="pni--; $(this).closest('tr').remove();" style="font-size:24px;" class="text-danger text-center pointer rounded px-4"><i class="ri-delete-bin-5-line"></i></td>
                    </tr>`);

});
$("#newWheelSpecRowEdit").click(function (e) {

    e.preventDefault();
    $("#wheelSpecsEditTbl").append(` <tr>
                        <td><input class="form-control" type="text" name="specsEdit[]" id=""></td>
                        <td><input class="form-control" type="text" name="specsvalEdit[]" id=""></td>

                        <td onclick="pni--; $(this).closest('tr').remove();" style="font-size:24px;" class="text-danger text-center pointer rounded px-4"><i class="ri-delete-bin-5-line"></i></td>


                    </tr>`);

});


var textVal="";
var dimText="";
var modelText="";
var materialText="";
var radioVal="";
$(document).on('click', ".editwheelB" , function (e) {

    var selected_wheel_id = $(this).attr("wheel_id_value");
    $($("#editWheel").find('form')[0]).prop('action','wheel/'+selected_wheel_id)
    $("#wheel_id").val(selected_wheel_id);
    $('#notifyEdit').prop('checked', false);
    
    $.ajax({
        type: "get",
        url: "wheelIdData/"+selected_wheel_id,
        success: function (response) {
            var wheel = response[0];
            console.log(wheel);
            // console.log((wheel.name).split('/'));
            wheelType = (wheel.name).split('/');
            // console.log(wheelType);

            $("#div3").hide();
            $("#div4").hide();
            $("#typeEditSlct").empty();
            $("#relatedWheelEditSlct").append(`<option disabled selected value="">Select Wheels</option>`);
            $("#wheelِEditTbl").empty();
            $("#relatedwheelِEditTbl tbody").empty();
            $("#wheelSpecsEditTbl").empty();
            $(".upload__img-wrapWheelEdit").empty();

            if(wheelType[5] =='كاوتش'){
                $("#typeEditSlct").append(`
                <option value="0">اختر نوع الكاوتش</option>
                <option value="1" selected>كاوتش</option>
                <option value="2">جنط</option>
                <option value="3">داخلي</option>
                `);
                $("#div3").show();
                $("#div4").hide();
            }else if(wheelType[5] =='جنط'){
                $("#typeEditSlct").append(`
                <option value="0">اختر نوع الكاوتش</option>
                <option value="1">كاوتش</option>
                <option value="2" selected>جنط</option>
                <option value="3">داخلي</option>
                `);
                $("#div3").hide();
                $("#div4").show();
            }else if(wheelType[5] =='داخلي'){
                $("#typeEditSlct").append(`
                <option value="0">اختر نوع الكاوتش</option>
                <option value="1">كاوتش</option>
                <option value="2">جنط</option>
                <option value="3" selected>داخلي</option>
                `);
                $("#div3").hide();
                $("#div4").show();
            }else{
                $("#typeEditSlct").append(`
                <option value="0" selected>اختر نوع الكاوتش</option>
                <option value="1">كاوتش</option>
                <option value="2">جنط</option>
                <option value="3">داخلي</option>
                `);
                $("#div3").hide();
                $("#div4").hide();
            }

            if(wheel.wheel_dimension != null ){
                $('#dimtypeEditSlct').append(`<option selected value="${wheel.wheel_dimension.id}">${wheel.wheel_dimension.dimension}</option>`);
            }else{
                $("#dimtypeEditSlct").append(`<option disabled selected value="">Select Dimensions</option>`);
            }

            if(wheel.wheel_model != null ){
                $('#modeltypeEditSlct').append(`<option selected value="${wheel.wheel_model.id}">${wheel.wheel_model.name}</option>`);
            }else{
                $("#modeltypeEditSlct").append(`<option disabled selected value="">Select Model</option>`);
            }

            if(wheel.wheel_material_id != null ){
                $('#materialtypeEditSlct').append(`<option selected value="${wheel.wheel_material.id}">${wheel.wheel_material.name}</option>`);
            }else{
                $("#materialtypeEditSlct").append(`<option disabled selected value="">Select Material</option>`);
            }

            if(wheel.limit_order != null ){
                $('#limit_orderEdit').val(wheel.limit_order);
            }


            if(wheel.flage_limit_order == 0 ){
                $('#notifyEdit').prop('checked', false);
            }else if(wheel.flage_limit_order == 1 ){
                $('#notifyEdit').prop('checked', true);
            }

            if(wheel.name != null ){
                $('#nameEdit').val(wheel.name);
            }
            if(wheel.description != null ){
                $('#descriptioEditn').val(wheel.description);
            }
            if(wheel.wheel_container_size != null ){
                $('#container_size_edit').val(wheel.wheel_container_size);
            }
            
            if(wheelType[5] =='كاوتش'){
                if(wheel.tt_tl == 1){
                    $("#div3 input[name=tt_tlEdit][value='1']").prop("checked",true);
                }else if(wheel.tt_tl == 2){
                    $("#div3 input[name=tt_tlEdit][value='2']").prop("checked",true);
                }
            }else{
                if(wheel.tt_tl == 1){
                    $("#div4 input[name=tt_tlEdit][value='1']").prop("checked",true);
                }else if(wheel.tt_tl == 2){
                    $("#div4 input[name=tt_tlEdit][value='2']").prop("checked",true);
                }
            }



            if(wheel.related_wheels.length > 0){
                for(var i=0 ; i < wheel.related_wheels.length ; i++){
                    $("#relatedwheelِEditTbl tbody").append(`<tr>
                    <td><label>${wheel.related_wheels[i].wheel.name}</label></td>
                    <td><input type="hidden" name="related_wheelEdit[]" value="${wheel.related_wheels[i].id}"></td>
                    <td onclick="pni--; $(this).closest('tr').remove();" style="font-size:24px;" class="text-danger text-center pointer rounded px-4"><i class="ri-delete-bin-5-line"></i></td>

                    /tr>`);
                }
            }
            $("#wheelSpecsTblEdit").append(wheelDetails1);
            if(wheel.wheel_details.length > 0){
                var table = $("#wheelSpecsEditTbl tr");
                var oldSpecs=[];
                for (var i = 0; i < table.length; i++) {
                    rowid=$(table[i]).attr('data-id');
                    oldSpecs.push(rowid);
                }
                (wheel.wheel_details).forEach(element => {
                    var selectedSpec = element.Wheelpecs_id.toString();
                    var i = jQuery.inArray(selectedSpec, oldSpecs);
                    if(i != -1){
                        $("#oldspecsvalEdit"+oldSpecs[i]).val(element.value);
                    }else{
                        $("#wheelSpecsEditTbl").append(` <tr>
                                    <td><input class="form-control" type="text" name="specsEdit[]" id="${element.Wheelpecs_id}" value="${element.wheel_spec.name}" readonly></td>
                                    <td><input class="form-control" type="text" name="specsvalEdit[]" id="${element.id}" value="${element.value}"></td>
                                    <td onclick="pni--; $(this).closest('tr').remove();" style="font-size:24px;" class="text-danger text-center pointer rounded px-4"><i class="ri-delete-bin-5-line"></i></td>

                                    </tr>`);
                    }
                });
            }

            if(wheel.wheel_images.length > 0){
                $(".upload__img-wrapWheelEdit").empty();
                for(var i=0 ; i < wheel.wheel_images.length ; i++){
                    $(".upload__img-wrapWheelEdit").append(`<div class="upload__img-box"><div style="background-image: url(assets/wheel_images/${wheel.wheel_images[i].image_name})" imageURL ="${wheel.wheel_images[i].image_name}" data-number="0" data-file="Screen Shot 2022-08-23 at 1.40.33 PM.png" class="img-bg"><div class="upload__img-close"></div></div></div>`)
                }
            }
            /////////////////////////////////////////////
        }

    });
});
$(document).on("click", ".deletewheelB", function () {
    // alert('xxx');
    var row = wheeltable.row($(this).closest("tr")).data();
    var selected_part_idx = $(this).attr("wheel_id_value2");
    $("#wheel_iddel").val(selected_part_idx);
    $("#wdel_name").val(row["name"]);

    //         $("#deletepartB form").attr("action", function() {
    //     return '{{route("part.destroy",["part" => $selected_part_idx])}}';
    // });
    var selected_part_idx = $(this).attr("wheel_id_value2");
    // $("#deletepartB form").attr("action", '{{ route("part.destroy", ["selected_part_idx" => ' + selected_part_idx + ']) }}');
    $($("#deletewheelB").find("form")[0]).prop(
        "action",
        "wheel.destroy/" + selected_part_idx
    );
});
$(document).on('change', '#typeSlct', function(){
    if ( this.value == '0'){
        $("#div1").hide();
        $("#div2").hide();
        $("#name").val("");

    }else if ( this.value == '1'){
        $("#div1").show();
        $("#div2").hide();
        textVal="كاوتش";

    }else if ( this.value == '2'){
        $("#div1").hide();
        $("#div2").show();
        textVal="جنط";

    }else if ( this.value == '3'){
        $("#div1").hide();
        $("#div2").show();
        textVal="داخلي";

    }
    $("#name").val(dimText+"/"+modelText+"/"+materialText+"/"+radioVal+"/"+textVal);


});

$(document).on('change', '#typeEditSlct', function(){
    // var textVal="";
    var dimText="";
    var modelText="";
    var materialText="";
    var radioVal="";
    if ( this.value == '0'){
        $("#div3").hide();
        $("#div4").hide();
        $("#nameEdit").val("");
        textVal="";
    }else if ( this.value == '1'){
        $("#div3").show();
        $("#div4").hide();
        textVal="كاوتش";
    }else if ( this.value == '2'){
        $("#div3").hide();
        $("#div4").show();
        textVal="جنط";
    }else if ( this.value == '3'){
        $("#div3").hide();
        $("#div4").show();
        textVal="داخلي";
    }
    $("#nameEdit").val(dimText+"/"+modelText+"/"+materialText+"/"+radioVal+"/"+textVal);
    $("#dimtypeEditSlct").append(`<option readonly selected value="">Select Dimension</option>`);
    $("#modeltypeEditSlct").append(`<option readonly selected value="">Select Brand</option>`);
    $("#materialtypeEditSlct").append(`<option readonly selected value="">Select Material</option>`);
});


$(document).on('change', '#dimtypeSlct', function(){
    dimText=$("#dimtypeSlct option:selected").text();
    $("#name").val(dimText+"/"+modelText+"/"+materialText+"/"+radioVal+"/"+textVal);
});
$(document).on('change', '#dimtypeEditSlct', function(){
    $("#nameEdit").val($("#dimtypeEditSlct option:selected").text()+"/"+$("#modeltypeEditSlct option:selected").text()+"/"+$("#materialtypeEditSlct option:selected").text()+"/"+$('input[name="tt_tlEdit"]:checked').attr('data-nameEdit')+"/"+$("#typeEditSlct option:selected").text());

});

$(document).on('change', '#modeltypeSlct', function(){
    modelText=$("#modeltypeSlct option:selected").text();
    $("#name").val(dimText+"/"+modelText+"/"+materialText+"/"+radioVal+"/"+textVal);
});
$(document).on('change', '#modeltypeEditSlct', function(){
     $("#nameEdit").val($("#dimtypeEditSlct option:selected").text()+"/"+$("#modeltypeEditSlct option:selected").text()+"/"+$("#materialtypeEditSlct option:selected").text()+"/"+$('input[name="tt_tlEdit"]:checked').attr('data-nameEdit')+"/"+$("#typeEditSlct option:selected").text());
});

$(document).on('change', '#materialtypeSlct', function(){
    materialText=$("#materialtypeSlct option:selected").text();
    $("#name").val(dimText+"/"+modelText+"/"+materialText+"/"+radioVal+"/"+textVal);
});
$(document).on('change', '#materialtypeEditSlct', function(){
    $("#nameEdit").val($("#dimtypeEditSlct option:selected").text()+"/"+$("#modeltypeEditSlct option:selected").text()+"/"+$("#materialtypeEditSlct option:selected").text()+"/"+$('input[name="tt_tlEdit"]:checked').attr('data-nameEdit')+"/"+$("#typeEditSlct option:selected").text());
});

$(document).on('change', 'input[name="tt_tl"]', function(){

    if(textVal=="كاوتش"){
        radioVal=$('input[name="tt_tl"]:checked').attr('data-name');
    }else if(textVal=="جنط" || textVal=="داخلي"){
        radioVal=$('input[name="tt_tl"]:checked').attr('data-name');
    }
    $("#name").val(dimText+"/"+modelText+"/"+materialText+"/"+radioVal+"/"+textVal);

});
$(document).on('change', 'input[name="tt_tlEdit"]', function(){
    $("#nameEdit").val($("#dimtypeEditSlct option:selected").text()+"/"+$("#modeltypeEditSlct option:selected").text()+"/"+$("#materialtypeEditSlct option:selected").text()+"/"+$('input[name="tt_tlEdit"]:checked').attr('data-nameEdit')+"/"+$("#typeEditSlct option:selected").text());
});

var wheelArr=[];
var wheelEditArr=[];
$(document).on('change', '#relatedWheelSlct', function(){
    var wheelId= $(this).val();
    var wheelTxt= $( "#relatedWheelSlct option:selected" ).text();

    if($.inArray(wheelId,wheelArr) >= 0){

    }else{
        wheelArr.push(wheelId);
        $("#wheelTbl").append(`<tr>
            <td>${wheelTxt}<input type="hidden" name="related_wheel[]" value=${wheelId}></td>
            <td onclick=" $(this).closest('tr').remove();wheelArr.splice(wheelArr.indexOf('${wheelId}'), 1);" style="font-size:24px;" class="text-danger text-center pointer rounded px-4"><i class="ri-delete-bin-5-line"></i></td>

        </tr>`)
    }
 });
 $(document).on('change', '#relatedWheelEditSlct', function(){
    var wheelId= $(this).val();
    var wheelTxt= $( "#relatedWheelEditSlct option:selected" ).text();

    if($.inArray(wheelId,wheelEditArr) >= 0){

    }else{
        wheelEditArr.push(wheelId);
        $("#relatedwheelِEditTbl").append(`<tr>
            <td>${wheelTxt}<input type="hidden" name="related_wheelEdit[]" value=${wheelId}></td>
            <td onclick="$(this).closest('tr').remove();wheelEditArr.splice(wheelEditArr.indexOf('${wheelId}'), 1);">Remove</td>
        </tr>`)
    }

 });

 var imgURL =[];
 $(document).on('click' , '.upload__img-close',function(){
     imgURL.push($(this).parent().attr('imageURL'));
     $('[name="imgURLsInp[]"').val(imgURL.join(' , '));
     // console.log(imgURL);

 });

function ImgUpload() {
    var imgWrap = "";
    var imgArray = [];

    $('.upload__inputfileWheel').each(function () {
      $(this).on('change', function (e) {
        imgWrap = $(this).closest('.upload__box').find('.upload__img-wrapWheel');
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

    $('.upload__inputfileWheelEdit').each(function () {
      $(this).on('change', function (e) {
        imgWrap = $(this).closest('.upload__box').find('.upload__img-wrapWheelEdit');
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
