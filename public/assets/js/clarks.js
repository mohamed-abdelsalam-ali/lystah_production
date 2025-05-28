
$(document).ready(function () {

  ImgUpload();
  ImgUploadRelease();
  ImgUploadInvoice();
  ImgUploadEdit();
  ImgUploadReleaseEdit();
  ImgUploadInvoiceEdit();



  $("#clarkBrandtypeSlct").select2({
      dropdownParent: $('#addClark')
  });
  $("#clarkBrandtypeSlct_edit").select2({
      dropdownParent: $('#editClark')
  });
  $("#clarkBrandSlct").select2({
      dropdownParent: $('#addClark')
  });
  $("#clarkBrandSlct_edit").select2({
      dropdownParent: $('#editClark')
  });
  $("#clarkModelSlct").select2({
      dropdownParent: $('#addClark')
  });
  $("#clarkModelSlct_edit").select2({
      dropdownParent: $('#editClark')
  });
  $("#clarkSeriesSlct").select2({
      dropdownParent: $('#addClark')
  });
  $("#clarkSeriesSlct_edit").select2({
      dropdownParent: $('#editClark')
  });
  $("#supplayer_id").select2({
      dropdownParent: $('#addClark')
  });
  $("supplayer_id_edit").select2({
      dropdownParent: $('#editClark')
  });
  $("#currency_id").select2({
      dropdownParent: $('#addClark')
  });
  $("#currency_id_edit").select2({
      dropdownParent: $('#editClark')
  });
  $("#status").select2({
      dropdownParent: $('#addClark')
  });
  $("#status_edit").select2({
      dropdownParent: $('#editClark')
  });
  $("#source_id").select2({
      dropdownParent: $('#addClark')
  });
  $("#source_id_edit").select2({
      dropdownParent: $('#editClark')
  });
  $("#quality_id").select2({
      dropdownParent: $('#addClark')
  });
  $("#quality_id_edit").select2({
      dropdownParent: $('#editClark')
  });
  $("#front_tire").select2({
      dropdownParent: $('#addClark')
  });
  $("#front_tire_edit").select2({
      dropdownParent: $('#editClark')
  });
  $("#rear_tire_edit").select2({
      dropdownParent: $('#editClark')
  });
  $("#rear_tire").select2({
      dropdownParent: $('#addClark')
  });
  $("#country_id").select2({
      dropdownParent: $('#addNewSupplier')
  });

  $("#store_id").select2({
      dropdownParent: $('#addClark')
  });
  $("#store_id_edit").select2({
      dropdownParent: $('#editClark')
  });

  $("#gear_box").select2({
      dropdownParent: $('#addClark')
  });
  $("#gear_box_edit").select2({
      dropdownParent: $('#editClark')
  });

  $("#drive").select2({
      dropdownParent: $('#addClark')
  });
  $("#drive_edit").select2({
      dropdownParent: $('#editClark')
  });
  $("#relatedPartSlct").select2({
      dropdownParent: $('#addClark')
  });
  var table = $('#clarksDT').DataTable({
      processing: true,
      serverSide: true,
      pageLength: 50,
      deferRender: true,
      destroy: true,
      ajax: "clarksdata",
      columns: [
          {data: 'DT_RowIndex', name: 'DT_RowIndex'},
          {data: 'name', name: 'Name'},
          {data: 'clarkNumbers', name: 'Clark Numbers'},
          {data: 'clarkSeries', name: 'Clark Series'},
          {data: 'clarkBrand', name: 'Clark Brand'},
          {data: 'Image', name: 'Image' },
          {data: 'efragImage', name: 'Release Image' },
          {data: 'action', name: 'action', orderable: false, searchable: false}
      ]
  });


  $.ajax({
      type: "get",
      url: "wheeldimensions",
      success: function (response) {
          $("#front_tire").empty();
          $("#rear_tire").empty();
          $("#front_tire").append(`<option value="0">Select Dimension</option>`);
          $("#rear_tire").append(`<option value="0">Select Dimension</option>`);
          response.forEach(element => {
              $("#front_tire").append(`<option value="${element.id}">${element.dimension}</option>`);
              $("#front_tire_edit").append(`<option value="${element.id}">${element.dimension}</option>`);
              $("#rear_tire").append(`<option value="${element.id}">${element.dimension}</option>`);
              $("#rear_tire_edit").append(`<option value="${element.id}">${element.dimension}</option>`);
          });

      }
  });

  $.ajax({
      type: "get",
      url: "gearindexdata",
      success: function (response) {
          $("#gear_box").empty();
          $("#gear_box").append(`<option value="0">Select Gearbox</option>`);
          response.forEach(element => {
              $("#gear_box").append(`<option value="${element.id}">${element.gearname}</option>`);
              $("#gear_box_edit").append(`<option value="${element.id}">${element.gearname}</option>`);
          });

      }
  });

  $.ajax({
      type: "get",
      url: "currencyType",
      success: function (response) {
          $("#currency_id").empty();
          $("#currency_id").append(`<option value="0">Select Currency</option>`);
          response.forEach(element => {
              $("#currency_id").append(`<option value="${element.id}">${element.name}</option>`);
              $("#currency_id_edit").append(`<option value="${element.id}">${element.name}</option>`);
          });

      }
  });

  $.ajax({
      type: "get",
      url: "status",
      success: function (response) {
          $("#status").empty();
          $("#status").append(`<option value="0">Select Status</option>`);
          response.forEach(element => {
              $("#status").append(`<option value="${element.id}">${element.name}</option>`);
              $("#status_edit").append(`<option value="${element.id}">${element.name}</option>`);
          });

      }
  });

  $.ajax({
      type: "get",
      url: "source",
      success: function (response) {
          $("#source_id").empty();
          $("#source_id").append(`<option value="0">Select Source</option>`);
          response.forEach(element => {
              $("#source_id").append(`<option value="${element.id}">${element.name_en}</option>`);
              $("#source_id_edit").append(`<option value="${element.id}">${element.name_en}</option>`);
          });

      }
  });

  $.ajax({
      type: "get",
      url: "storedrp",
      success: function (response) {
          $("#store_id").empty();
          $("#store_id").append(`<option value="0">Select Store</option>`);
          response.forEach(element => {
              $("#store_id").append(`<option value="${element.id}">${element.name}</option>`);
              $("#store_id_edit").append(`<option value="${element.id}">${element.name}</option>`);
          });

      }
  });

  $.ajax({
      type: "get",
      url: "partQuality",
      success: function (response) {
          $("#quality_id").empty();
          $("#quality_id").append(`<option value="0">Select Quality</option>`);
          response.forEach(element => {
              $("#quality_id").append(`<option value="${element.id}">${element.name}</option>`);
              $("#quality_id_edit").append(`<option value="${element.id}">${element.name}</option>`);
          });

      }
  });

  $.ajax({
      type: "get",
      url: "get_all_drive",
      success: function (response) {
          $("#drive").empty();
          $("#drive").append(`<option value="0">Select Drive</option>`);
          response.forEach(element => {
              $("#drive").append(`<option value="${element.id}">${element.name}</option>`);
              $("#drive_edit").append(`<option value="${element.id}">${element.name}</option>`);
          });

      }
  });

  $.ajax({
      type: "get",
      url: "supplier",
      success: function (response) {
          $("#supplayer_id").empty();
          $("#supplayer_id").append(`<option value="0">Select Supplier</option>`);
          response.forEach(element => {
              $("#supplayer_id").append(`<option value="${element.id}">${element.name}</option>`);
              $("#supplayer_id_edit").append(`<option value="${element.id}">${element.name}</option>`);
              SupplierSlct +=`<option value="${element.id}">${element.name}</option>`;
          });

      }
  });

  $.ajax({
      type: "get",
      url: "country",
      success: function (response) {
          $("#country_id").empty();
          $("#country_id").append(`<option value="0">Select Country</option>`);
          response.forEach(element => {
              $("#country_id").append(`<option value="${element.id}">${element.name}</option>`);
              $("#country_id_edit").append(`<option value="${element.id}">${element.name}</option>`);
          });

      }
  });

  $.ajax({
      type: "get",
      url: "clarkspecs",
      success: function (response) {
          $("#clarkSpecsTbl").empty();
          $("#clarkSpecsTblEdit").empty();
          response.forEach(element => {
              ClarkDetails +=` <tr data-id="${element.id}">
              <td>${element.name} <input type="hidden" value="${element.id}" name="oldSpecs[]"></td>
              <td><input class="form-control"  type="text" name="oldspecsval[]" id="oldspecsval${element.id}" value=""></td>
              <td onclick="$(this).closest('tr').remove();" class="bg-danger rounded px-4 border-bottom"><i class="ri-delete-bin-5-line">حذف</i></td>
          </tr>`;
          ClarkDetails1 +=` <tr data-id="${element.id}">
              <td>${element.name} <input type="hidden" value="${element.id}" name="oldSpecsEdit[]"></td>
              <td><input class="form-control"  type="text" name="oldspecsvalEdit[]" id="oldspecsvalEdit${element.id}" value=""></td>
              <td onclick="$(this).closest('tr').remove();" class="bg-danger rounded px-4 border-bottom"><i class="ri-delete-bin-5-line">حذف</i></td>
          </tr>`;

          });
          $("#clarkSpecsTbl").append(ClarkDetails);
      }
  });

  $.ajax({
      type: "get",
      url: "clarkBrand",
      success: function (response) {
          $("#clarkBrandtypeSlct_edit").empty();
          $("#clarkBrandtypeSlct").empty();
          $("#clarkBrandSlct").empty();
          $("#clarkBrandSlct_edit").empty();
          $("#clarkBrandtypeSlct").append(`<option disabled selected value="0">Select Clark Type</option>`);
          // $("#clarkBrandtypeSlct_edit").append(`<option selected disabled value="0">Select brand Type</option>`);
          var brandtype = response[0];
          var brand = response[1];
          brandtype.forEach(element => {
              $("#clarkBrandtypeSlct").append(`<option value="${element.id}">${element.name}</option>`);
              $("#clarkBrandtypeSlct_edit").append(`<option value="${element.id}">${element.name}</option>`);
          });
          $("#clarkBrandSlct").append(`<option disabled selected value="0">Select Brand</option>`);
          // $("#clarkBrandSlct_edit").append(`<option disabled selected value="0">Select Brand</option>`);
          brand.forEach(element => {
              $("#clarkBrandSlct").append(`<option value="${element.id}">${element.name}</option>`);
              $("#clarkBrandSlct_edit").append(`<option value="${element.id}">${element.name}</option>`);
          });

      }
  });

  $.ajax({
      type: "get",
      url: "part",
      success: function (response) {
          $("#relatedPartSlct").empty();
          $("#relatedPartSlct").append(`<option value="0">Select Part</option>`);
          $("#relatedPartSlct_edit").empty();
          $("#relatedPartSlct_edit").append(`<option value="0">Select Part</option>`);
          response.forEach(element => {
              $("#relatedPartSlct").append(`<option value="${element.id}">${element.name}</option>`);
              $("#relatedPartSlct_edit").append(`<option value="${element.id}">${element.name}</option>`);
          });

      }
  });

});

  var SupplierSlct ;
  var ClarkDetails ;
  var ClarkDetails1 ;
  var pni=1;



  $("#newClarkSpecRow").click(function (e) {

      e.preventDefault();
      $("#clarkSpecsTbl").append(` <tr>
                          <td><input class="form-control" type="text" name="specs[]" id=""></td>
                          <td><input class="form-control" type="text" name="specsval[]" id=""></td>
                          <td onclick="pni--; $(this).closest('tr').remove();">REMOVE</td>
                      </tr>`);

  });
  $("#newClarkSpecRow_edit").click(function (e) {

      e.preventDefault();
      $("#clarkSpecsTblEdit").append(` <tr>
                          <td><input class="form-control" type="text" name="specsEdit[]" id=""></td>
                          <td><input class="form-control" type="text" name="specsvalEdit[]" id=""></td>
                          <td onclick="pni--; $(this).closest('tr').remove();">REMOVE</td>
                      </tr>`);

  });


  $('#addClark').on('hidden.bs.modal', function () {
  $(this).find('form').trigger('reset')

  });

  $('#editClark').on('hidden.bs.modal', function () {
      $(this).find('form').trigger('reset')

  });


  $(document).on('click', ".editClarkB" , function (e) {
      // $($("#editClark").find('form')[0]).reset();
      var selected_clark_id = $(this).attr("clark_id_value");
      $($("#editClark").find('form')[0]).prop('action','clark/'+selected_clark_id)
      $("#clark_id").val(selected_clark_id);
      $.ajax({
          type: "get",
          url: "clarkIdData/"+selected_clark_id,
          success: function (response) {
              var clark = response[0];
              console.log(clark[0].related_clarks);
              var store = response[1];
              console.log(store);
              $("#name_edit").val(clark[0].name);
              $("#name_en_edit").val(clark[0].eng_name);
              $("#clark_number_edit").val(clark[0].clark_number);
              $("#color_edit").val(clark[0].color);
              $("#supplayer_id_edit").val(clark[0].supplayer_id);
              $("#store_id_edit").val(store[0].store_id).trigger('change');
              $("#bank_account_edit").val(clark[0].all_clarks[0].order_supplier.bank_account);
              $("#tank_edit").val(clark[0].tank);
              $("#buy_price_edit").val(clark[0].buy_price);
              $("#currency_id_edit").val(clark[0].currency_id);
              $("#status_edit").val(clark[0].status);
              $("#source_id_edit").val(clark[0].source_id);
              if(clark[0].all_clarks[0].order_supplier.deliver_date != null){
                  $("#deliverydate_edit").val(clark[0].all_clarks[0].order_supplier.deliver_date.split('T')[0]);
              }
              $("#quality_id_edit").val(clark[0].quality_id);
              $("#container_size_edit").val(clark[0].all_clarks[0].order_supplier.container_size);
              $("#motor_number_edit").val(clark[0].motor_number);
              $("#year_edit").val(clark[0].year);
              $("#hours_edit").val(clark[0].hours);
              if(clark[0].serivcedate != null){
                  $("#serivcedate_edit").val(clark[0].serivcedate.split('T')[0]);
              }
              $("#front_tire_status_edit").val(clark[0].front_tire_status);
              $("#rear_tire_status_edit").val(clark[0].rear_tire_status);
              $("#power_edit").val(clark[0].power);
              $("#discs_edit").val(clark[0].discs);
              $("#desc_edit").val(clark[0].desc);
              $("#front_tire_edit").val(clark[0].front_tire);
              $("#rear_tire_edit").val(clark[0].rear_tire);
              $("#gear_box_edit").val(clark[0].gear_box);
              $("#limit_edit").val(clark[0].limit);
              if(clark[0].active_limit == '1'){
                  $("#active_limit_edit").prop( "checked", true );
              }else{
                  $("#active_limit_edit").prop( "checked", false );
              }
              $("#clarkSpecsTblEdit").empty();
              $("#ralatedPartTbl_edit").empty();
              $("#clarkSpecsTblEdit").append(ClarkDetails1);
              if(clark[0].clark_details.length > 0){
                  var table = $("#clarkSpecsTblEdit tr");
                  var oldSpecs=[];
                  for (var i = 0; i < table.length; i++) {
                      rowid=$(table[i]).attr('data-id');
                      oldSpecs.push(rowid)
                  }
                  (clark[0].clark_details).forEach(element => {
                      var selectedSpec = element.partspecs_id.toString();
                      var i = jQuery.inArray(selectedSpec, oldSpecs)
                      if(i != -1){
                          $("#oldspecsvalEdit"+oldSpecs[i]).val(element.value);
                      }else{
                          $("#clarkSpecsTblEdit").append(` <tr>
                              <td><input class="form-control" type="text" name="specsEdit[]" id="${element.partspecs_id}" value="${element.clark_spec.name}" readonly></td>
                              <td><input class="form-control" type="text" name="specsvalEdit[]" id="${element.id}" value="${element.value}"></td>
                              <td onclick="pni--; $(this).closest('tr').remove();">REMOVE</td>
                              </tr>`);
                      }
                  });
              }
              if(clark[0].series !=null){
                  $("#clarkBrandtypeSlct_edit").val(clark[0].series.model.brand_type.id).trigger('change');
                  $("#clarkBrandSlct_edit").val(clark[0].series.model.brand.id).trigger('change');
                  $("#clarkModelSlct_edit").val(clark[0].series.model.id).trigger('change');
                  $("#clarkSeriesSlct_edit").val(clark[0].series.id).trigger('change');
              }
              if(clark[0].related_clarks.length > 0){
                  (clark[0].related_clarks).forEach(element =>{
                      $("#ralatedPartTbl_edit").append(`<tr>
                      <td>${element.part.name}<input type="hidden" name="relatedPart_edit[]" value=${element.part.id}></td>
                      <td onclick="$(this).closest('tr').remove();relatedPartArrEdit.splice(relatedPartArrEdit.indexOf('${element.part.id}'), 1);">Remove</td>
                  </tr>`)
                  });
              }
              if(clark[0].clark_images.length > 0){
                  $(".upload__img-wrap_edit").empty();
                  for(var i=0 ; i < clark[0].clark_images.length ; i++){
                      $(".upload__img-wrap_edit").append(`<div class="upload__img-box"><div style="background-image: url(assets/clark_images/${clark[0].clark_images[i].image_name})" imageURL ="${clark[0].clark_images[i].image_name}" data-number="0" data-file="Screen Shot 2022-08-23 at 1.40.33 PM.png" class="img-bg"><div class="upload__img-close-edit"></div></div></div>
                      `)
                  }
              }

              if(clark[0].clark_efrags.length > 0){
                  $(".upload__img-wrapRelease_edit").empty();
                  for(var i=0 ; i < clark[0].clark_efrags.length ; i++){
                      $(".upload__img-wrapRelease_edit").append(`<div class="upload__img-box"><div style="background-image: url(assets/efrag_images/${clark[0].clark_efrags[i].image_name})" imageURL ="${clark[0].clark_efrags[i].image_name}" data-number="0" data-file="Screen Shot 2022-08-23 at 1.40.33 PM.png" class="img-bg"><div class="upload__img-close-efrag-edit"></div></div></div>
                      `)
                  }
              }

              if(clark[0].invoice_images.length > 0){
                  $(".upload__img-wrapInvoice_edit").empty();
                  for(var i=0 ; i < clark[0].invoice_images.length ; i++){
                      $(".upload__img-wrapInvoice_edit").append(`<div class="upload__img-box"><div style="background-image: url(assets/invoice_images/${clark[0].invoice_images[i].image_name})" imageURL ="${clark[0].invoice_images[i].image_name}" data-number="0" data-file="Screen Shot 2022-08-23 at 1.40.33 PM.png" class="img-bg"><div class="upload__img-close-invoice-edit"></div></div></div>
                      `)
                  }
              }




          }
      });
  });

  $(document).on('change', '#clarkBrandtypeSlct', function(){
      $("#clarkBrandSlct").append(`<option disabled selected value="0">Select Brand</option>`);
      $("#clarkModelSlct").empty();
      $("#clarkSeriesSlct").empty();
  });

  $(document).on('change', '#clarkBrandtypeSlct_edit', function(){
      $("#clarkBrandSlct_edit").append(`<option disabled selected value="0">Select Brand</option>`);
      $("#clarkModelSlct_edit").empty();
      $("#clarkSeriesSlct_edit").empty();
  });

  $(document).on('change', '#clarkBrandSlct', function(){
      var brandid= $(this).val();
      var ttype_id= $("#clarkBrandtypeSlct").val();
      $.ajax({
          type: "get",
          async: false,
          url: "clarkmodel/"+brandid+"/"+ttype_id,
          success: function (response) {
              $("#clarkModelSlct").empty();
              $("#clarkModelSlct").append(`<option disabled selected value="0">Select Model</option>`);
              response.forEach(element => {
                  $("#clarkModelSlct").append(`<option value="${element.id}">${element.name}</option>`);
              });

          }
      });
  });

  $(document).on('change', '#clarkBrandSlct_edit', function(){
      var brandid= $(this).val();
      var ttype_id= $("#clarkBrandtypeSlct_edit").val();
      $.ajax({
          type: "get",
          async : false,
          url: "clarkmodel/"+brandid+"/"+ttype_id,
          success: function (response) {
              $("#clarkModelSlct_edit").empty();
              $("#clarkModelSlct_edit").append(`<option disabled selected value="0">Select Model</option>`);
              response.forEach(element => {
                  $("#clarkModelSlct_edit").append(`<option value="${element.id}">${element.name}</option>`);
              });

          }
      });
  });


  $(document).on('change', '#clarkModelSlct', function(){
      var modelId= $(this).val();
      $.ajax({
          type: "get",
          url: "clarkseries/"+modelId,
          success: function (response) {
              $("#clarkSeriesSlct").empty();
              $("#clarkSeriesSlct").append(`<option disabled selected value="0">Select Series</option>`);
              response.forEach(element => {
                  $("#clarkSeriesSlct").append(`<option value="${element.id}">${element.name}</option>`);
              });
          }
      });
  });

  $(document).on('change', '#clarkModelSlct_edit', function(){
      var modelId= $(this).val();
      $.ajax({
          type: "get",
          url: "clarkseries/"+modelId,
          async: false,
          success: function (response) {
              console.log(modelId);
              $("#clarkSeriesSlct_edit").empty();
              // $("#clarkSeriesSlct_edit").append(`<option disabled selected value="0">Select Series</option>`);
              response.forEach(element => {
                  $("#clarkSeriesSlct_edit").append(`<option value="${element.id}">${element.name}</option>`);
              });
          }
      });
  });

  $(document).on('change', '#clarkSeriesSlct', function(){
      $("#model_id").val($(this).val()) ;
  });
  $(document).on('change', '#clarkSeriesSlct_edit', function(){
      $("#model_id_edit").val($(this).val()) ;
  });
  // $(document).on('submit', '#addSupplierForm', function(){
  //     event.preventDefault();
  //     console.log("sdfsdf");
  //     $("#addClark").show();
  //     $("#addNewSupplier").hide();
  // });

  var relatedPartArr=[];
  var relatedPartArrEdit=[];
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
  });


  $(document).on('change', '#relatedPartSlct_edit', function(){
      var partId= $(this).val();
      var partIdTxt= $( "#relatedPartSlct_edit option:selected" ).text();

      if($.inArray(partId,relatedPartArrEdit) >= 0){

      }else{
          relatedPartArrEdit.push(partId);
          $("#ralatedPartTbl_edit").append(`<tr>

              <td>${partIdTxt}<input type="hidden" name="relatedPart_edit[]" value=${partId}></td>
              <td onclick="$(this).closest('tr').remove();relatedPartArrEdit.splice(relatedPartArrEdit.indexOf('${partId}'), 1);">Remove</td>
          </tr>`)
      }
  });


  var imgURL =[];
  $(document).on('click' , '.upload__img-close',function(){
      imgURL.push($(this).parent().attr('imageURL'));
      $('[name="imgURLsInp[]"').val(imgURL);
  });

  var imgURLEfrag =[];
  $(document).on('click' , '.upload__img-close-efrag',function(){
      imgURLEfrag.push($(this).parent().attr('imageURL'));
      $('[name="imgURLsInpEfrag[]"').val(imgURLEfrag);
  });

  var imgURLInvoice =[];
  $(document).on('click' , '.upload__img-close-invoice',function(){
      imgURLInvoice.push($(this).parent().attr('imageURL'));
      $('[name="imgURLsInpInvoice[]"').val(imgURLInvoice);
  });

  var imgURL =[];
  $(document).on('click' , '.upload__img-close-edit',function(){
      imgURL.push($(this).parent().attr('imageURL'));
      $('[name="imgURLsInp[]"').val(imgURL);
  });

  var imgURLEfrag =[];
  $(document).on('click' , '.upload__img-close-efrag-edit',function(){
      imgURLEfrag.push($(this).parent().attr('imageURL'));
      $('[name="imgURLsInpEfrag[]"').val(imgURLEfrag);
  });

  var imgURLInvoice =[];
  $(document).on('click' , '.upload__img-close-invoice-edit',function(){
      imgURLInvoice.push($(this).parent().attr('imageURL'));
      $('[name="imgURLsInpInvoice[]"').val(imgURLInvoice);
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

  function ImgUploadRelease() {
      var imgWrap = "";
      var imgArray = [];

      $('.upload__inputfile').each(function () {
        $(this).on('change', function (e) {
          imgWrap = $(this).closest('.upload__box').find('.upload__img-wrapRelease');
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
                  var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close-efrag").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close-efrag'></div></div></div>";
                  imgWrap.append(html);
                  iterator++;
                }
                reader.readAsDataURL(f);
              }
            }
          });
        });
      });

      $('body').on('click', ".upload__img-close-efrag", function (e) {
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

  function ImgUploadInvoice() {
      var imgWrap = "";
      var imgArray = [];

      $('.upload__inputfile').each(function () {
        $(this).on('change', function (e) {
          imgWrap = $(this).closest('.upload__box').find('.upload__img-wrapInvoice');
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
                  var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close-invoice").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close-invoice'></div></div></div>";
                  imgWrap.append(html);
                  iterator++;
                }
                reader.readAsDataURL(f);
              }
            }
          });
        });
      });

      $('body').on('click', ".upload__img-close-invoice", function (e) {
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

      $('.upload__inputfile').each(function () {
        $(this).on('change', function (e) {
          imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap_edit');
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
                  var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close-edit").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close-edit'></div></div></div>";
                  imgWrap.append(html);
                  iterator++;
                }
                reader.readAsDataURL(f);
              }
            }
          });
        });
      });

      $('body').on('click', ".upload__img-close-edit", function (e) {
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

  function ImgUploadReleaseEdit() {
      var imgWrap = "";
      var imgArray = [];

      $('.upload__inputfile').each(function () {
        $(this).on('change', function (e) {
          imgWrap = $(this).closest('.upload__box').find('.upload__img-wrapRelease_edit');
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
                  var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close-efrag-edit").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close-efrag-edit'></div></div></div>";
                  imgWrap.append(html);
                  iterator++;
                }
                reader.readAsDataURL(f);
              }
            }
          });
        });
      });

      $('body').on('click', ".upload__img-close-efrag-edit", function (e) {
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

  function ImgUploadInvoiceEdit() {
      var imgWrap = "";
      var imgArray = [];

      $('.upload__inputfile').each(function () {
        $(this).on('change', function (e) {
          imgWrap = $(this).closest('.upload__box').find('.upload__img-wrapInvoice_edit');
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
                  var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close-invoice-edit").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close-invoice-edit'></div></div></div>";
                  imgWrap.append(html);
                  iterator++;
                }
                reader.readAsDataURL(f);
              }
            }
          });
        });
      });

      $('body').on('click', ".upload__img-close-invoice-edit", function (e) {
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
