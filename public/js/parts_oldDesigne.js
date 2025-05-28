var parttable;
$(document).ready(function () {
    // $('#example').DataTable();
    ImgUpload();
    ImgUploadEdit();
    $("#groupSlct").select2({
        dropdownParent: $('#staticBackdrop')
    });
    $("#SgroupSlct").select2({
        dropdownParent: $('#staticBackdrop')
    });
    $("#groupSlctEdit").select2({
        dropdownParent: $('#editPart')
    });
    $("#SgroupSlctEdit").select2({
        dropdownParent: $('#editPart')
    });
    $(".supplierSlct").select2({
        dropdownParent: $('#staticBackdrop') ,
        dropdownAutoWidth : true
    });
    $(".supplierSlctEdit").select2({
        dropdownParent: $('#editPart') ,
        dropdownAutoWidth : true
    });
    $("#brandtypeSlct").select2({
        dropdownParent: $('#staticBackdrop')
    });
    $("#partbrandtypeSlctEdit").select2({
        dropdownParent: $('#editPart')
    });
    $("#brandSlct").select2({
        dropdownParent: $('#staticBackdrop')
    });
    $("#partbrandSlctEdit").select2({
        dropdownParent: $('#editPart')
    });
    $("#modelSlct").select2({
        dropdownParent: $('#staticBackdrop')
    });

        $("#relatedPartSlct").select2({
        dropdownParent: $('#staticBackdrop')
    });
    $("#partmodelSlctEdit").select2({
        dropdownParent: $('#editPart')
    });
    $("#seriesSlct").select2({
        dropdownParent: $('#staticBackdrop')
    });
    $("#partseriesSlctEdit").select2({
        dropdownParent: $('#editPart')
    });

    $("#relatedPartSlct_edit").select2({
        dropdownParent: $('#editPart')
    });

    parttable = $('#partsDT').DataTable({
        dom: 'Bfrtip',
        processing: true,
        serverSide: true,
        pageLength: 10,
        deferRender: true,
         responsive: true,
        destroy: true,
        ajax: "partsData",
        columns: [
            {data: 'DT_RowIndex', name: '#'},
            {data: 'Image', name: 'Image'  },
            {data: 'name', name: 'name'},
            {data: 'name_eng', name: 'name_eng'},
            {data: 'insertion_date', name: 'insertion_date' ,visible : false},
            {data: 'description', name: 'description' ,visible : false},
            {data: 'limit_order', name: 'limit_order' ,visible : false},
            {data: 'sub_group_id', name: 'sub_group_id' ,visible : false},
            {data: 'subGroup', name: 'subGroup' },
            {data: 'group', name: 'group' },
            {data: 'Number', name: 'Number' ,visible : true},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        columnDefs: [{
            targets: [10],
            className: 'scrollable-cell list-of-elements'
          }],
        buttons: [
            {

                text:      '<button class="btn btn-soft-success p-0 m-0" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="ri-add-fill"></i></button>',
                titleAttr: 'New Part'
            },
            {
                extend:    'copyHtml5',
                text:      '<a class="btn btn-soft-info p-0 m-0" href="customSearch"><i class="ri-user-search-fill"></i></a>',
                titleAttr: 'Custom Search'
            } ,
            'print'
        ]

    });

    $.ajax({
        type: "get",
        url: "group",
        success: function (response) {
            $("#groupSlct").empty();
            $("#groupSlctEdit").empty();
            $("#groupSlct").append(`<option value="0">Select Groups</option>`);
            response.forEach(element => {
                $("#groupSlct").append(`<option value="${element.id}">${element.name}</option>`);
                $("#groupSlctEdit").append(`<option value="${element.id}">${element.name}</option>`);
            });
        }
    });

    $.ajax({
        type: "get",
        url: "partspecs",
        success: function (response) {
            $("#partSpecsTbl").empty();
            $("#partSpecsTblEdit").empty();
            response.forEach(element => {
                // $("#partSpecsTbl").append(` <tr data-id="${element.id}">
                //     <td>${element.name} <input type="hidden" value="${element.id}" name="oldSpecs[]"></td>
                //     <td><input class="form-control"  type="text" name="oldspecsval[]" id=""></td>
                //     <td onclick="$(this).closest('tr').remove();" class="bg-danger rounded px-4 border-bottom"><i class="ri-delete-bin-5-line">حذف</i></td>
                // </tr>`);
                partDetails +=` <tr data-id="${element.id}">
                <td>${element.name} <input type="hidden" value="${element.id}" name="oldSpecs[]"></td>
                <td><input class="form-control"  type="text" name="oldspecsval[]" id="oldspecsval${element.id}" value=""></td>
                <td onclick="$(this).closest('tr').remove();" class="bg-danger rounded px-4 border-bottom"><i class="ri-delete-bin-5-line">حذف</i></td>
            </tr>`;
                partDetails1 +=` <tr data-id="${element.id}">
                <td>${element.name} <input type="hidden" value="${element.id}" name="oldSpecsEdit[]"></td>
                <td><input class="form-control"  type="text" name="oldspecsvalEdit[]" id="oldspecsvalEdit${element.id}" value=""></td>
                <td onclick="$(this).closest('tr').remove();" class="bg-danger rounded px-4 border-bottom"><i class="ri-delete-bin-5-line">حذف</i></td>
            </tr>`;
            });
            $("#partSpecsTbl").append(partDetails);
        }
    });

    $.ajax({
        type: "get",
        url: "partBrand",
        success: function (response) {
            $("#brandtypeSlct").empty();
            $("#partbrandtypeSlctEdit").empty();
            $("#brandSlct").empty();
            $("#partbrandSlctEdit").empty();
            $("#partbrandtypeSlctEdit").append(`<option selected disabled value="0">Select brand Type</option>`);
            $("#brandtypeSlct").append(`<option selected disabled value="0">Select brand Type</option>`);

            var brandtype = response[0];
            var brand = response[1];
            brandtype.forEach(element => {

                $("#brandtypeSlct").append(`<option value="${element.id}">${element.name}</option>`);
                $("#partbrandtypeSlctEdit").append(`<option value="${element.id}">${element.name}</option>`);
            });
            $("#brandSlct").append(`<option selected disabled value="0">Select Brand</option>`);
            $("#partbrandSlctEdit").append(`<option selected disabled value="0">Select Brand</option>`);
            brand.forEach(element => {

                $("#brandSlct").append(`<option value="${element.id}">${element.name}</option>`);
                $("#partbrandSlctEdit").append(`<option value="${element.id}">${element.name}</option>`);
            });
        }
    });

$.ajax({
        type: "get",
        url: "part",
        success: function (response) {
            $("#relatedPartSlct").empty();
            $("#relatedPartSlct_edit").empty();
            $("#relatedPartSlct").append(`<option value="0">Select Part</option>`);
            $("#relatedPartSlct_edit").append(`<option value="0">Select Part</option>`);
            response.forEach(element => {
                $("#relatedPartSlct").append(`<option value="${element.id}">${element.name}</option>`);
                $("#relatedPartSlct_edit").append(`<option value="${element.id}">${element.name}</option>`);
            });

        }
    });




    $.ajax({
        type: "get",
        url: "Selectindex",
        success: function (response) {
            $("#supplierSlct1").empty();
            $("#supplierSlct1").append(`<option value="0">Select Supplier</option>`);
            response.forEach(element => {
                $("#supplierSlct1").append(`<option value="${element.id}">${element.name}</option>`);
                SupplierSlct +=`<option value="${element.id}">${element.name}</option>`;
            });

        }
    });


});


 var relatedPartArr=[];
    $(document).on('change', '#relatedPartSlct', function(){
        var partId= $(this).val();
        var partIdTxt= $( "#relatedPartSlct option:selected" ).text();

        if($.inArray(partId,relatedPartArr) >= 0){

        }else{
            relatedPartArr.push(partId);
            $("#ralatedPartTbl").append(`<tr>

                <td>${partIdTxt}<input type="hidden" name="relatedPart[]" value=${partId}></td>
                <td onclick="$(this).closest('tr').remove();relatedPartArr.splice(relatedPartArr.indexOf('${partId}'), 1);">Remove</td>
            </tr>`)
        }
    })
    var relatedPartEditArr=[];
    $(document).on('change', '#relatedPartSlct_edit', function(){
        var partId= $(this).val();
        var partIdTxt= $( "#relatedPartSlct_edit option:selected" ).text();

        if($.inArray(partId,relatedPartEditArr) >= 0){

        }else{
            relatedPartEditArr.push(partId);
            $("#ralatedPartTbl_edit").append(`<tr>

                <td>${partIdTxt}<input type="hidden" name="relatedPartEdit[]" value=${partId}></td>
                <td onclick="$(this).closest('tr').remove();relatedPartEditArr.splice(relatedPartEditArr.indexOf('${partId}'), 1);">Remove</td>
            </tr>`)
        }
    })
var SupplierSlct ;

    var partDetails = ""
    var partDetails1=""
var pni=1;
// $('#partsDT tbody').on( 'click', 'tr', function () {
//     console.log( parttable.row( this ).data() );
//     var rowdata = parttable.row( this ).data();

//     //PartCardInfo(rowdata.id)
// } );
$(document).on('click', ".deletepartB" , function () {

    // alert('xxx');
        var row =parttable.row($(this).closest('tr')).data();
        var selected_part_idx = $(this).attr("part_id_value2");
        $("#part_iddel").val(selected_part_idx);
        $("#pdel_name").val(row['name']);

//         $("#deletepartB form").attr("action", function() {
//     return '{{route("part.destroy",["part" => $selected_part_idx])}}';
// });
 var selected_part_idx = $(this).attr("part_id_value2");
// $("#deletepartB form").attr("action", '{{ route("part.destroy", ["selected_part_idx" => ' + selected_part_idx + ']) }}');
  $($("#deletepartB").find('form')[0]).prop('action','part.destroy/'+selected_part_idx)


});

$(document).on('click', ".editpartB" , function (e) {

    var selected_part_id = $(this).attr("part_id_value");
    $($("#editPart").find('form')[0]).prop('action','part/'+selected_part_id)
    $("#part_id").val(selected_part_id);
    $.ajax({
        type: "get",
        url: "partIdData/"+selected_part_id,
        success: function (response) {
            var part = response[0];
            // console.log(part);

            $("#partSpecsTblEdit").empty();
            $("#nameedit").val(part.name);
            $("#engnameedit").val(part.name_eng);
            $("#descedit").val(part.description);
            if(part.sub_group){
                $("#groupSlctEdit").append(`<option selected value="${part.sub_group.group.id}">${part.sub_group.group.name}</option>`);
                $("#SgroupSlctEdit").append(`<option selected value="${part.sub_group.id}">${part.sub_group.name}</option>`);
            }


            if(part.part_numbers.length > 0){
                console.log(part.part_numbers.length );
                $("#partNumberTblEdit tbody").empty();
                for(var i=0 ; i < part.part_numbers.length ; i++){
                    $("#partNumberTblEdit tbody").append(`<tr><td>
                    <input class="form-control" type="text"  name="partNumberEdit[]" value="${part.part_numbers[i].number}">
                    </td>
                    <td>
                        <select name="partsupplierSlctEdit[]" class="form-control partsupplierSlctEdit" id="${part.part_numbers[i].id}" >
                        ${SupplierSlct}
                        </select></td>
                    <td onclick="pni--; $(this).closest('tr').remove();">REMOVE</td>
                    /tr>`);
                    $("#"+part.part_numbers[i].id).val(part.part_numbers[i].supplier.id);
                }
            }

            $("#partSpecsTblEdit").append(partDetails1);

            if(part.part_details.length > 0){
                var table = $("#partSpecsTblEdit tr");
                var oldSpecs=[];
                for (var i = 0; i < table.length; i++) {
                    rowid=$(table[i]).attr('data-id');
                    oldSpecs.push(rowid);
                }
                (part.part_details).forEach(element => {
                    var selectedSpec = element.partspecs_id.toString();
                    var i = jQuery.inArray(selectedSpec, oldSpecs);
                    if(i != -1){
                        $("#oldspecsvalEdit"+oldSpecs[i]).val(element.value);
                    }else{
                        $("#partSpecsTblEdit").append(` <tr>
                                    <td><input class="form-control" type="text" name="specsEdit[]" id="${element.partspecs_id}" value="${element.part_spec.name}" readonly></td>
                                    <td><input class="form-control" type="text" name="specsvalEdit[]" id="${element.id}" value="${element.value}"></td>
                                    <td onclick="pni--; $(this).closest('tr').remove();">REMOVE</td>
                                    </tr>`);
                    }
                });
            }

            if(part.part_models.length > 0){

                for(var i=0 ; i < part.part_models.length ; i++){
                    console.log(part.part_models[i].series)
                    $("#partBrandTblEdit").append(`<tr>
                        <td>${part.part_models[i].series.model.brand_type.name}</td>
                        <td>${part.part_models[i].series.model.brand.name}</td>
                        <td>${part.part_models[i].series.model.name}</td>
                        <td>${part.part_models[i].series.name}<input type="hidden" name="seriesEdit[]" value="${part.part_models[i].series.id}"></td>
                        <td onclick="$(this).closest('tr').remove();partBrandArr.splice(kitBrandArr.indexOf('${part.part_models[i].seriesId}'), 1);">Remove</td>
                    </tr>`);
                }
            }
            if(part.related_parts.length > 0){
                    (part.related_parts).forEach(element =>{
                        $("#ralatedPartTbl_edit").append(`<tr>
                        <td>${element.part.name}<input type="hidden" name="relatedPartEdit[]" value=${element.part.id}></td>
                        <td onclick="$(this).closest('tr').remove();relatedPartArrEdit.splice(relatedPartArrEdit.indexOf('$${element.part.id}'), 1);">Remove</td>
                    </tr>`)
                    });
                }
            if(part.part_images.length > 0){
                $(".upload__img-wrapPartEdit").empty();
                for(var i=0 ; i < part.part_images.length ; i++){
                    $(".upload__img-wrapPartEdit").append(`<div class="upload__img-box"><div style="background-image: url(assets/part_images/${part.part_images[i].image_name})" imageURL ="${part.part_images[i].image_name}" data-number="0" data-file="Screen Shot 2022-08-23 at 1.40.33 PM.png" class="img-bg"><div class="upload__img-close"></div></div></div>
                    `)
                }
            }
        }

    });
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
$(document).on('change', '#groupSlctEdit', function(){
    var groupid= $(this).val();
    $.ajax({
        type: "get",
        url: "group/"+groupid,
        success: function (response) {
            $("#SgroupSlctEdit").empty();
            $("#SgroupSlctEdit").append(`<option disabled value="0">Select Sub Groups</option>`);
            response.forEach(element => {
                $("#SgroupSlctEdit").append(`<option value="${element.id}">${element.name}</option>`);
            });
        }
    });
})
$(document).on('change', '#brandSlct', function(){
    var brandid= $(this).val();
    var ptype_id= $("#brandtypeSlct").val();
    $.ajax({
        type: "get",
        url: "partmodel/"+brandid+"/"+ptype_id,
        success: function (response) {
            $("#modelSlct").empty();
            $("#modelSlct").append(`<option selected disabled value="0">Select Model</option>`);
            response.forEach(element => {
                $("#modelSlct").append(`<option value="${element.id}">${element.name}</option>`);
            });
        }
    });
})
$(document).on('change', '#partbrandSlctEdit', function(){
    var brandid= $(this).val();
    var ptype_id= $("#partbrandtypeSlctEdit").val();
    $.ajax({
        type: "get",
        url: "partmodel/"+brandid+"/"+ptype_id,
        success: function (response) {
            $("#partmodelSlctEdit").empty();
            $("#partmodelSlctEdit").append(`<option selected disabled value="0">Select Model</option>`);
            response.forEach(element => {
                $("#partmodelSlctEdit").append(`<option value="${element.id}">${element.name}</option>`);
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
            $("#seriesSlct").empty();
            // $("#seriesSlct").append(`<option value="0">Select Series</option>`);
            response.forEach(element => {
                $("#seriesSlct").append(`<option value="${element.id}">${element.name}</option>`);
            });
        }
    });
})
$(document).on('change', '#partmodelSlctEdit', function(){
    var modelId= $(this).val();

    $.ajax({
        type: "get",
        url: "partseries/"+modelId,
        success: function (response) {
            $("#partseriesSlctEdit").empty();
            $("#partseriesSlctEdit").append(`<option selected disabled value="0">Select Series</option>`);
            response.forEach(element => {
                $("#partseriesSlctEdit").append(`<option value="${element.id}">${element.name}</option>`);
            });
        }
    });
})

var partBrandArr=[];
$(document).on('change', '#seriesSlct', function(){
    var seriesId= $(this).val();
    var seriesTxt= $( "#seriesSlct option:selected" ).text();
    var modelTxt= $( "#modelSlct option:selected" ).text();
    var brandTxt= $( "#brandSlct option:selected" ).text();

    if($.inArray(seriesId,partBrandArr) >= 0){

    }else{
        partBrandArr.push(seriesId);
        $("#partBrandTbl").append(`<tr>

            <td>${brandTxt}</td>
            <td>${modelTxt}</td>
            <td>${seriesTxt}<input type="hidden" name="series[]" value=${seriesId}></td>
            <td onclick="$(this).closest('tr').remove();partBrandArr.splice(partBrandArr.indexOf('${seriesId}'), 1);">Remove</td>
        </tr>`)
    }

})

$(document).on('change', '#partseriesSlctEdit', function(){
    var seriesId= $(this).val();
    var brandTypeTxt= $( "#partbrandtypeSlctEdit option:selected" ).text();
    var seriesTxt= $( "#partseriesSlctEdit option:selected" ).text();
    var modelTxt= $( "#partmodelSlctEdit option:selected" ).text();
    var brandTxt= $( "#partbrandSlctEdit option:selected" ).text();

    if($.inArray(seriesId,partBrandArr) >= 0){

    }else{
        partBrandArr.push(seriesId);
        $("#partBrandTblEdit").append(`<tr>

            <td>${brandTypeTxt}</td>
            <td>${brandTxt}</td>
            <td>${modelTxt}</td>
            <td>${seriesTxt}<input type="hidden" name="seriesEdit[]" value=${seriesId}></td>
            <td onclick="$(this).closest('tr').remove();partBrandArr.splice(partBrandArr.indexOf('${seriesId}'), 1);">Remove</td>
        </tr>`)
    }

})

var imgURL =[];
$(document).on('click' , '.upload__img-close',function(){
    imgURL.push($(this).parent().attr('imageURL'));
    $('[name="imgURLsInp[]"').val(imgURL.join(' , '));

});


$("#newPartNumberRow").click(function (e) {
    pni++;
    e.preventDefault();
    $("#partNumberTbl").append(` <tr>
                        <td><input class="form-control" type="text" name="partNumber[]" id=""></td>
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
$("#newPartNumberRowEdit").click(function (e) {
    pni++;
    e.preventDefault();

    $("#partNumberTblEdit").append(` <tr>
                        <td><input class="form-control" type="text" name="partNumberEdit[]" id=""></td>
                        <td>
                            <select name="partsupplierSlctEdit[]" class="form-control supplierSlct " id="partsupplierSlctEdit${pni}">
                                ${SupplierSlct}
                            </select></td>
                        <td onclick="pni--; $(this).closest('tr').remove();">REMOVE</td>
                    </tr>`);
    $("#partsupplierSlctEdit"+pni).select2({
        dropdownParent: $('#editPart') ,
        dropdownAutoWidth : true
    });
});

$("#newPartSpecRow").click(function (e) {

    e.preventDefault();
    $("#partSpecsTbl").append(` <tr>
                        <td><input class="form-control" type="text" name="specs[]" id=""></td>
                        <td><input class="form-control" type="text" name="specsval[]" id=""></td>
                        <td onclick="pni--; $(this).closest('tr').remove();">REMOVE</td>
                    </tr>`);

});
$("#newPartSpecRowEdit").click(function (e) {

    e.preventDefault();
    $("#partSpecsTblEdit").append(` <tr>
                        <td><input class="form-control" type="text" name="specsEdit[]" id=""></td>
                        <td><input class="form-control" type="text" name="specsvalEdit[]" id=""></td>
                        <td onclick="pni--; $(this).closest('tr').remove();">REMOVE</td>
                    </tr>`);

});

$("#selectallmodel").click(function (e) {
  e.preventDefault();
  if( $("#brandSlct").val() > 0){
    $("#modelSlct option").each(function()
    {

        $(this).prop('selected', true).trigger('change');
        $("#selectallseries").click();
    });

  }else{
    alert("Please Select Brand")
  }
})

$("#selectallseries").click(function (e) {
    e.preventDefault();
    if( $("#modelSlct").val() > 0){
      $("#seriesSlct option").each(function()
      {
        $(this).prop('selected', true).trigger('change');

      });

    }else{
      alert("Please Select Brand")
    }
  })

$("#editselectallseries").click(function (e) {
    e.preventDefault();
    if( $("#partmodelSlctEdit").val() > 0){
      $("#partseriesSlctEdit option").each(function()
      {
        $(this).prop('selected', true).trigger('change');

      });

    }else{
      alert("Please Select Brand")
    }
  })

function ImgUpload() {
    var imgWrap = "";
    var imgArray = [];

    $('.upload__inputfile').each(function () {
      $(this).on('change', function (e) {
        imgWrap = $(this).closest('.upload__box').find('.upload__img-wrapPart');
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

    $('.upload__inputfilePartEdit').each(function () {
      $(this).on('change', function (e) {
        imgWrap = $(this).closest('.upload__box').find('.upload__img-wrapPartEdit');
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



// $('#partsDT tbody').on( 'click', 'tr', function () {
//     console.log( parttable.row( this ).data() );
//     var rowdata = parttable.row( this ).data();

//     //PartCardInfo(rowdata.id)
// } );
