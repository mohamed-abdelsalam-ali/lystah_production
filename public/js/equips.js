
$('#addEquip').on('show.bs.modal', function (event) {
    $(this).find('#myTab button:first').tab('show');
});
$('#addEquip').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
    $(".upload__img-wrap").empty();
    $(".upload__img-wrapRelease").empty();
    $(".upload__img-wrapInvoice").empty();
    $("#ralatedPartTbl").empty();
    // $(".addNewpartSpecsTbl").closest('tr').remove();


    $("#clarkSpecsTbl").empty();

});
var table='';
$(document).ready(function () {

    ImgUpload();
    ImgUploadInvoice();
    ImgUploadEdit();
    ImgUploadInvoiceEdit();

    $("#relatedEquipsSlct").select2({
        dropdownParent: $('#addEquip')
    });

    $("#relatedEquipsEditSlct").select2({
        dropdownParent: $('#addEquip')
    });

    $("#equipBrandtypeSlct").select2({
        dropdownParent: $('#addEquip')
    });
    $("#equipBrandSlct").select2({
        dropdownParent: $('#addEquip')
    });
    $("#equipModelSlct").select2({
        dropdownParent: $('#addEquip')
    });
    $("#equipSeriesSlct").select2({
        dropdownParent: $('#addEquip')
    });
    $("#supplier_id").select2({
        dropdownParent: $('#addEquip')
    });
    $("#currency_id").select2({
        dropdownParent: $('#addEquip')
    });
    $("#status_id").select2({
        dropdownParent: $('#addEquip')
    });
    $("#source_id").select2({
        dropdownParent: $('#addEquip')
    });
    $("#quality_id").select2({
        dropdownParent: $('#addEquip')
    });
    $("#country_id").select2({
        dropdownParent: $('#addNewSupplier')
    });
    $("#store_id").select2({
        dropdownParent: $('#addEquip')
    });

     table = $('#equipsDT').DataTable({
        dom: "Bfrtip",
        processing: true,
        serverSide: true,
        pageLength: 10,
        deferRender: true,
        responsive: true,
        destroy: true,
        ajax: "equipsdata",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'name_eng', name: 'name_eng'},
            {data: 'related_equips', name: 'related_equips'},
            {data: 'equipSeries', name: 'equipSeries'},
            {data: 'Image', name: 'Image' , orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        buttons: [

            "print",
        ]
    });


    $.ajax({
        type: "get",
        url: "currencyType",
        success: function (response) {
            $("#currency_id").empty();
            $("#currency_id").append(`<option value="">Select Currency</option>`);
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
            $("#status_id").empty();
            $("#status_id").append(`<option value="">Select Status</option>`);
            response.forEach(element => {
                $("#status_id").append(`<option value="${element.id}">${element.name}</option>`);
                $("#status_id_edit").append(`<option value="${element.id}">${element.name}</option>`);
            });

        }
    });

    $.ajax({
        type: "get",
        url: "source",
        success: function (response) {
            $("#source_id").empty();
            $("#source_id").append(`<option value="">Select Source</option>`);
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
            $("#store_id").append(`<option value="">Select Store</option>`);
            $("#store_id_edit").append(`<option value="">Select Store</option>`);
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
            $("#quality_id").append(`<option value="">Select Quality</option>`);
            response.forEach(element => {
                $("#quality_id").append(`<option value="${element.id}">${element.name}</option>`);
                $("#quality_id_edit").append(`<option value="${element.id}">${element.name}</option>`);
            });

        }
    });

    $.ajax({
        type: "get",
        url: "supplier",
        success: function (response) {
            $("#supplier_id").empty();
            $("#supplier_id").append(`<option value="">Select Supplier</option>`);
            response.forEach(element => {
                $("#supplier_id").append(`<option value="${element.id}">${element.name}</option>`);
                $("#supplier_id_edit").append(`<option value="${element.id}">${element.name}</option>`);
                SupplierSlct +=`<option value="${element.id}">${element.name}</option>`;
            });

        }
    });

    $.ajax({
        type: "get",
        url: "equip",
        success: function (response) {
            $("#relatedEquipsSlct").empty();
            $("#relatedEquipsSlct").append(`<option disabled selected value="">Select Related Equip</option>`);
            $("#relatedEquipsSlct_edit").empty();
            $("#relatedEquipsSlct_edit").append(`<option disabled selected value="">Select Related Equip</option>`);
            response.forEach(element => {
                $("#relatedEquipsSlct").append(`<option value="${element.id}">${element.name}</option>`);
                $("#relatedEquipsSlct_edit").append(`<option value="${element.id}">${element.name}</option>`);
            });

        }
    });

    $.ajax({
        type: "get",
        url: "equipspecs",
        success: function (response) {
            $("#equipSpecsTbl").empty();
            $("#equipSpecsTblEdit").empty();
            response.forEach(element => {
                EquipDetails +=` <tr data-id="${element.id}">
                <td>${element.name} <input type="hidden" value="${element.id}" name="oldSpecs[]"></td>
                <td><input class="form-control"  type="text" name="oldspecsval[]" id="oldspecsval${element.id}" value=""></td>
                <td onclick="$(this).closest('tr').remove();" class="bg-danger rounded px-4 border-bottom"><i class="ri-delete-bin-5-line">حذف</i></td>
            </tr>`;
            EquipDetails1 +=` <tr data-id="${element.id}">
                <td>${element.name} <input type="hidden" value="${element.id}" name="oldSpecs_edit[]"></td>
                <td><input class="form-control"  type="text" name="oldspecsval_edit[]" id="oldspecsval_edit${element.id}" value=""></td>
                <td onclick="$(this).closest('tr').remove();" class="bg-danger rounded px-4 border-bottom"><i class="ri-delete-bin-5-line">حذف</i></td>
            </tr>`;

            });
            $("#equipSpecsTbl").append(EquipDetails);
        }
    });

    $.ajax({
        type: "get",
        url: "equipBrand",
        success: function (response) {
            $("#equipBrandtypeSlct_edit").empty();
            $("#equipBrandtypeSlct").empty();
            $("#equipBrandSlct").empty();
            $("#equipBrandSlct_edit").empty();
            $("#equipBrandtypeSlct").append(`<option disabled selected value="">Select Equip Type</option>`);
            $("#equipBrandtypeSlct_edit").append(`<option selected disabled value="">Select brand Type</option>`);
            var brandtype = response[0];
            var brand = response[1];
            brandtype.forEach(element => {
                $("#equipBrandtypeSlct").append(`<option value="${element.id}">${element.name}</option>`);
                $("#equipBrandtypeSlct_edit").append(`<option value="${element.id}">${element.name}</option>`);
            });
            $("#equipBrandSlct").append(`<option disabled selected value="">Select Brand</option>`);
            $("#equipBrandSlct_edit").append(`<option disabled selected value="">Select Brand</option>`);
            brand.forEach(element => {
                $("#equipBrandSlct").append(`<option value="${element.id}">${element.name}</option>`);
                $("#equipBrandSlct_edit").append(`<option value="${element.id}">${element.name}</option>`);
            });

        }
    });

    $.ajax({
        type: "get",
        url: "part",
        success: function (response) {
            $("#relatedPartSlct").empty();
            $("#relatedPartSlct").append(`<option value="">Select Part</option>`);
            $("#relatedPartSlct_edit").empty();
            $("#relatedPartSlct_edit").append(`<option value="">Select Part</option>`);
            response.forEach(element => {
                $("#relatedPartSlct").append(`<option value="${element.id}">${element.name}</option>`);
                $("#relatedPartSlct_edit").append(`<option value="${element.id}">${element.name}</option>`);
            });

        }
    });

});



    var SupplierSlct ;
    var EquipDetails ;
    var EquipDetails1 ;
    var pni=1;
    var equipArr=[];
    var equipArrEdit=[];
    $("#newEquipNumberRow").click(function (e) {
        pni++;
        e.preventDefault();

        $("#equipNumberTbl").append(` <tr>
                            <td><input class="form-control" type="text" name="equipNumber[]" id=""></td>
                            <td>
                                <select name="supplierSlct[]" class="form-control supplierSlct " id="supplierSlct${pni}">
                                    ${SupplierSlct}
                                </select></td>
                            <td onclick="pni--; $(this).closest('tr').remove();">REMOVE</td>
                        </tr>`);
        $("#supplierSlct"+pni).select2({
            dropdownParent: $('#addEquip') ,
            dropdownAutoWidth : true
        });
    });
    $(document).on('change', '#relatedEquipsSlct', function(){
        var equipId= $(this).val();
        var equipTxt= $( "#relatedEquipsSlct option:selected" ).text();

        if($.inArray(equipId,equipArr) >= 0){

        }else{
            equipArr.push(equipId);
            $("#relatedEquipTbl").append(`<tr>
                <td>${equipTxt}<input type="hidden" name="related_equip[]" value=${equipId}></td>
                <td onclick="$(this).closest('tr').remove();equipArr.splice(equipArr.indexOf('${equipId}'), 1);">Remove</td>
            </tr>`)
        }
     });

    $('#addEquip').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset')

    });

    $('#editEquip').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset')

    });

    $(document).on('click', ".editEquipB" , function (e) {
        var selected_equip_id = $(this).attr("equip_id_value");
        $($("#editEquip").find('form')[0]).prop('action','equip/'+selected_equip_id)
        $("#equip_id").val(selected_equip_id);
        $.ajax({
            type: "get",
            url: "equipIdData/"+selected_equip_id,
            success: function (response) {
                var equip = response[0];
                console.log(equip);
                var store = response[1];
                console.log(store);
                $("#name_edit").val(equip[0].name);
                $("#buyTransaction_id").val(equip[0].all_equips[0].order_supplier.transaction_id);

                $("#name_eng_edit").val(equip[0].name_eng);
                $("#store_id_edit").val(store[0].store_id).trigger('change');
                $("#supplier_id_edit").val(equip[0].all_equips[0].order_supplier.supplier_id);
                $("#bank_account_edit").val(equip[0].all_equips[0].order_supplier.bank_account);
                $("#price_edit").val(equip[0].buy_price);
                $("#currency_id_edit").val(equip[0].currency_id);
                $("#status_id_edit").val(equip[0].all_equips[0].source_id);
                $("#source_id_edit").val(equip[0].all_equips[0].status_id);
                $("#quality_id_edit").val(equip[0].all_equips[0].quality_id);
                $("#tank_capacity_edit").val(equip[0].tank_capacity);
                $("#year_edit").val(equip[0].year);
                $("#color_edit").val(equip[0].color);

                $("#hours_edit").val(equip[0].hours);
                if(equip[0].last_sevice_date != null){
                    $("#last_sevice_date_edit").val(equip[0].last_sevice_date.split('T')[0]);
                }
                if(equip[0].all_equips[0].order_supplier.deliver_date != null){
                $("#deliverydate_edit").val(equip[0].all_equips[0].order_supplier.deliver_date.split('T')[0]);
                }
                $("#desc_edit").val(equip[0].desc);
                $("#limit_order_edit").val(equip[0].limit_order);
                if(equip[0].flage_limit_order == '1'){
                    $("#flage_limit_order_edit").prop( "checked", true );
                }else{
                    $("#flage_limit_order_edit").prop( "checked", false );
                }

                $("#equipSpecsTbl_edit").empty();
                $("#ralatedPartTbl_edit").empty();
                $("#equipSpecsTbl_edit").append(EquipDetails1);
                if(equip[0].equip_details.length > 0){
                    var table = $("#equipSpecsTbl_edit tr");
                    var oldSpecs=[];
                    for (var i = 0; i < table.length; i++) {
                        rowid=$(table[i]).attr('data-id');
                        oldSpecs.push(rowid)
                    }
                    (equip[0].equip_details).forEach(element => {
                        var selectedSpec = element.equipspecs_id.toString();
                        var i = jQuery.inArray(selectedSpec, oldSpecs)
                        if(i != -1){
                            $("#oldspecsval_edit"+oldSpecs[i]).val(element.value);
                        }else{
                            $("#equipSpecsTbl_edit").append(` <tr>
                                        <td><input class="form-control" type="text" name="specs_edit[]" id="${element.equipspecs_id}" value="${element.equip_spec.name}" readonly></td>
                                        <td><input class="form-control" type="text" name="specsval_edit[]" id="${element.id}" value="${element.value}"></td>
                                        <td onclick="pni--; $(this).closest('tr').remove();">REMOVE</td>
                                        </tr>`);
                        }
                    });
                }

                if(equip[0].series !=null){
                    $("#equipBrandtypeSlct_edit").val(equip[0].series.model.brand_type.id).trigger('change');
                    $("#equipBrandSlct_edit").val(equip[0].series.model.brand.id).trigger('change');
                    $("#equipModelSlct_edit").val(equip[0].series.model.id).trigger('change');
                    $("#equipSeriesSlct_edit").val(equip[0].series.id).trigger('change');
                }

                if(equip[0].related_equips.length > 0){
                    (equip[0].related_equips).forEach(element =>{
                        $("#ralatedPartTbl_edit").append(`<tr>
                        <td>${element.parts.name}<input type="hidden" name="relatedPartEdit[]" value=${element.parts.id}></td>
                        <td onclick="$(this).closest('tr').remove();relatedPartArrEdit.splice(relatedPartArrEdit.indexOf('$${element.parts.id}'), 1);">Remove</td>
                    </tr>`)
                    });
                }

                if(equip[0].equip_images.length > 0){
                    $(".upload__img-wrap_edit").empty();
                    for(var i=0 ; i < equip[0].equip_images.length ; i++){
                        $(".upload__img-wrap_edit").append(`<div class="upload__img-box"><div style="background-image: url(assets/equip_images/${equip[0].equip_images[i].image_name})" imageURL ="${equip[0].equip_images[i].image_name}" data-number="0" data-file="Screen Shot 2022-08-23 at 1.40.33 PM.png" class="img-bg"><div class="upload__img-close"></div></div></div>
                        `)
                    }
                }

                if(equip[0].invoice_images.length > 0){
                    $(".upload__img-wrapInvoice_edit").empty();
                    for(var i=0 ; i < equip[0].invoice_images.length ; i++){
                        $(".upload__img-wrapInvoice_edit").append(`<div class="upload__img-box"><div style="background-image: url(assets/invoice_images/${equip[0].invoice_images[i].image_name})" imageURL ="${equip[0].invoice_images[i].image_name}" data-number="0" data-file="Screen Shot 2022-08-23 at 1.40.33 PM.png" class="img-bg"><div class="upload__img-close-invoice"></div></div></div>
                        `)
                    }
                }


                $('#store_idd_edit').val(equip[0].all_equips[0].order_supplier.bank_account).trigger('change');
                $('#transCoast_edit').val(equip[0].all_equips[0].order_supplier.transport_coast);
                $('#insuranceCoast_edit').val(equip[0].all_equips[0].order_supplier.insurant_coast);
                $('#customs_edit').val(equip[0].all_equips[0].order_supplier.customs_coast);
                $('#commition_edit').val(equip[0].all_equips[0].order_supplier.commotion_coast);
                $('#otherCoast_edit').val(equip[0].all_equips[0].order_supplier.other_coast);
                $('#InvCoastinglbl_edit').text(equip[0].all_equips[0].buy_costing).trigger('change');
                $('#invPaied_edit').val(equip[0].all_equips[0].order_supplier.paied);
                $('#paymentslect_edit').val(equip[0].all_equips[0].order_supplier.payment).trigger('change');
                $("#dueDate_edit").val(equip[0].all_equips[0].order_supplier.due_date);
                $("#storeSctionSlct_edit").val(equip[0].all_equips[0].sections[0].section_id).trigger('change');



                if(equip[0].all_equips[0].order_supplier.taxInvolved_flag == 0){
                    $('#inlineRadio1_edit').prop("checked", true);

                }else{
                    $('#inlineRadio2_edit').prop("checked", true);

                }
                if(equip[0].all_equips[0].order_supplier.taxkasmInvolved_flag == 0){
                    $('#inlineRadio21_edit').prop("checked", true);

                }else{
                    $('#inlineRadio11_edit').prop("checked", true);

                }

                calc_table_price_edit();




            },complete : function(data){
                $("#storeSctionSlct_edit").val(data.responseJSON[0][0].all_equips[0].sections[0].section_id).trigger('change');

                if( parseFloat( data.responseJSON[0][0].all_equips[0].order_supplier.paied) < parseFloat ($("#invAllTotal_edit").val()) ){

                    $("#dueDiv_edit").show();
                    $("#dueDate_edit").attr('required',true);
            }else{
                    $("#dueDiv_edit").hide();
                    $("#dueDate_edit").attr('required',false);
                }


            }
        });
    });
    $(document).on("click", ".deletepartB", function () {
        // alert('xxx');
        var row = table.row($(this).closest("tr")).data();
        var selected_part_idx = $(this).attr("equip_id_value2");
        $("#part_iddel").val(selected_part_idx);
        $("#pdel_name").val(row["name"]);

        //         $("#deletepartB form").attr("action", function() {
        //     return '{{route("part.destroy",["part" => $selected_part_idx])}}';
        // });
        var selected_part_idx = $(this).attr("equip_id_value2");
        // $("#deletepartB form").attr("action", '{{ route("part.destroy", ["selected_part_idx" => ' + selected_part_idx + ']) }}');
        $($("#deletepartB").find("form")[0]).prop(
            "action",
            "equip.destroy/" + selected_part_idx
        );
    });
    $("#newEquipSpecRow").click(function (e) {

        e.preventDefault();
        $("#equipSpecsTbl").append(` <tr>
                            <td><input class="form-control" type="text" name="specs[]" id=""></td>
                            <td><input class="form-control" type="text" name="specsval[]" id=""></td>
                            <td onclick="pni--; $(this).closest('tr').remove();">REMOVE</td>
                        </tr>`);

    });

    $("#newEquipSpecRow_edit").click(function (e) {

        e.preventDefault();
        $("#equipSpecsTbl_edit").append(` <tr>
                            <td><input class="form-control" type="text" name="specs_edit[]" id=""></td>
                            <td><input class="form-control" type="text" name="specsval_edit[]" id=""></td>
                            <td onclick="pni--; $(this).closest('tr').remove();">REMOVE</td>
                        </tr>`);

    });


    $(document).on('change', '#equipBrandtypeSlct', function(){
        $("#equipBrandSlct").append(`<option disabled selected value="">Select Brand</option>`);
        $("#equipModelSlct").empty();
        $("#equipSeriesSlct").empty();
    });

    $(document).on('change', '#equipBrandtypeSlct_edit', function(){
        $("#equipBrandSlct_edit").append(`<option disabled selected value="">Select Brand</option>`);
        $("#equipModelSlct_edit").empty();
        $("#equipSeriesSlct_edit").empty();
    });

    $(document).on('change', '#equipBrandSlct', function(){
        var brandid= $(this).val();
        var ttype_id= $("#equipBrandtypeSlct").val();
        $.ajax({
            type: "get",
            async: false,
            url: "equipmodel/"+brandid+"/"+ttype_id,
            success: function (response) {
                $("#equipModelSlct").empty();
                $("#equipModelSlct").append(`<option disabled selected value="">Select Model</option>`);
                response.forEach(element => {
                    $("#equipModelSlct").append(`<option value="${element.id}">${element.name}</option>`);
                });

            }
        });
    });

    $(document).on('change', '#equipBrandSlct_edit', function(){
        var brandid= $(this).val();
        var ttype_id= $("#equipBrandtypeSlct_edit").val();
        $.ajax({
            type: "get",
            async: false,
            url: "equipmodel/"+brandid+"/"+ttype_id,
            success: function (response) {
                $("#equipModelSlct_edit").empty();
                $("#equipModelSlct_edit").append(`<option disabled selected value="">Select Model</option>`);
                response.forEach(element => {
                    $("#equipModelSlct_edit").append(`<option value="${element.id}">${element.name}</option>`);
                });

            }
        });
    });


    $(document).on('change', '#equipModelSlct', function(){
        var modelId= $(this).val();
        $.ajax({
            type: "get",
            url: "equipseries/"+modelId,
            success: function (response) {
                $("#equipSeriesSlct").empty();
                $("#equipSeriesSlct").append(`<option disabled selected value="">Select Series</option>`);
                response.forEach(element => {
                    $("#equipSeriesSlct").append(`<option value="${element.id}">${element.name}</option>`);
                });
            }
        });
    });

    $(document).on('change', '#equipModelSlct_edit', function(){
        var modelId= $(this).val();
        $.ajax({
            type: "get",
            async: false,
            url: "equipseries/"+modelId,
            success: function (response) {
                $("#equipSeriesSlct_edit").empty();
                $("#equipSeriesSlct_edit").append(`<option disabled selected value="">Select Series</option>`);
                response.forEach(element => {
                    $("#equipSeriesSlct_edit").append(`<option value="${element.id}">${element.name}</option>`);
                });
            }
        });
    });

    $(document).on('change', '#equipSeriesSlct', function(){
        $("#model_id").val($(this).val()) ;
    });

    $(document).on('change', '#equipSeriesSlct_edit', function(){
        $("#model_id_edit").val($(this).val()) ;
    });
    // $(document).on('submit', '#addSupplierForm', function(){
    //     event.preventDefault();
    //     console.log("sdfsdf");
    //     $("#addEquip").show();
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
    })

    $(document).on('change', '#relatedPartSlct_edit', function(){
        var partId= $(this).val();
        var partIdTxt= $( "#relatedPartSlct_edit option:selected" ).text();

        if($.inArray(partId,relatedPartArrEdit) >= 0){

        }else{
            relatedPartArrEdit.push(partId);
            $("#ralatedPartTbl_edit").append(`<tr>

                <td>${partIdTxt}<input type="hidden" name="relatedPartEdit[]" value=${partId}></td>
                <td onclick="$(this).closest('tr').remove();relatedPartArrEdit.splice(relatedPartArrEdit.indexOf('${partId}'), 1);">Remove</td>
            </tr>`)
        }
    })
    var imgURL =[];
    $(document).on('click' , '.upload__img-close',function(){
        imgURL.push($(this).parent().attr('imageURL'));
        $('[name="imgURLsInp[]"').val(imgURL);
    });

    var imgURLInvoice =[];
    $(document).on('click' , '.upload__img-close-invoice',function(){
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



//////////////////////////// new ///////////////////////////////////////////////////
$.ajax({
    type: "get",
    url: "getAllBankTypes",
    async: false,
    success: function (response) {
        $("#store_idd").empty();
        $("#store_idd_edit").empty();
        $("#store_idd").append(`<option disabled selected value="">اختر اسم الحساب</option>`);
        $("#store_idd_edit").append(`<option disabled selected value="">اختر اسم الحساب</option>`);
        response.forEach(element => {
            $("#store_idd").append(`<option type-name="bank" data-select2-id="${element.id}" value="${element.accountant_number}">${element.bank_name}</option>`);
            $("#store_idd_edit").append(`<option type-name="bank" data-select2-id="${element.id}" value="${element.accountant_number}">${element.bank_name}</option>`);
        });

    }
});
//////////////////////////// new ///////////////////////////////////////////////////
$("#store_idd").change(function(){
    var safeId = $(this).val();
    var safetype = $("#store_idd option:selected ").attr('type-name');
    $.ajax({
        type: "get",
        url: "/getRassed/"+safeId+'/'+safetype,
        success: function(response) {
            console.log(response);
            if(response==""){
                $("#Safetotal").text(0)
                // $("#inserFormBtn").hide();

            }else{
            $("#Safetotal").text(response)
            if(response > 0){
                // $("#inserFormBtn").show();
            }else{
                // $("#inserFormBtn").hide();
            }
            }
        }
    });
})
$("#store_idd_edit").change(function(){
    var safeId = $(this).val();
    var safetype = $("#store_idd_edit option:selected ").attr('type-name');
    $.ajax({
        type: "get",
        url: "/getRassed/"+safeId+'/'+safetype,
        success: function(response) {
            console.log(response);
            if(response==""){
                $("#Safetotal_edit").text(0)
                // $("#editFormBtn_").hide();

            }else{
            $("#Safetotal_edit").text(response)
            if(response > 0){
                // $("#editFormBtn").show();
            }else{
                // $("#editFormBtn").hide();
            }
            }
        }
    });
})

//////////////////////////////////////////////////////////////////////////////////
//////////////////////////// new ///////////////////////////////////////////////////
function changeStore(el) {
var storeid = $(el).val();


$.ajax({
    type: "get",
    url: "GetSections/" + storeid,
    success: function(response) {
        if (response.length > 0) {
            $("#storeSctionSlct").empty();
            $("#storeSctionSlct").append(
                    `<option value="">اختر</option>`);
            response.forEach(element => {
                $("#storeSctionSlct").append(
                    `<option value="${element.id}">${element.name}</option>`);
            });
        } else {
            $("#storeSctionSlct").empty();
            $("#storeSctionSlct").append(`<option value="0">No Sections</option>`);
        }

    }
});
$("#storeSctionSlct").select2();

}

function changeStore_edit(el) {
var storeid = $(el).val();


$.ajax({
    type: "get",
    async : false,
    url: "GetSections/" + storeid,
    success: function(response) {
        if (response.length > 0) {
            $("#storeSctionSlct_edit").empty();
            $("#storeSctionSlct_edit").append(
                    `<option value="">اختر</option>`);
            response.forEach(element => {
                $("#storeSctionSlct_edit").append(
                    `<option value="${element.id}">${element.name}</option>`);
            });
        } else {
            $("#storeSctionSlct_edit").empty();
            $("#storeSctionSlct_edit").append(`<option value="0">No Sections</option>`);
        }

    }
});
$("#storeSctionSlct_edit").select2();

}
function calc_coast(){
var transCoast= parseFloat($("#transCoast").val());
var insuranceCoast= parseFloat($("#insuranceCoast").val());
var customs= parseFloat($("#customs").val());
var commition= parseFloat($("#commition").val());
var otherCoast= parseFloat($("#otherCoast").val());
var Tot=(transCoast?transCoast:0) +(insuranceCoast ? insuranceCoast:0)+(customs ? customs : 0)+(commition ? commition : 0)+(otherCoast ? otherCoast : 0);
$('#InvCoastinglbl').text(Tot);
  $('#InvCoasting').val(Tot);

}
function calc_coast_edit(){
var transCoast= parseFloat($("#transCoast_edit").val());
var insuranceCoast= parseFloat($("#insuranceCoast_edit").val());
var customs= parseFloat($("#customs_edit").val());
var commition= parseFloat($("#commition_edit").val());
var otherCoast= parseFloat($("#otherCoast_edit").val());
var Tot=(transCoast?transCoast:0) +(insuranceCoast ? insuranceCoast:0)+(customs ? customs : 0)+(commition ? commition : 0)+(otherCoast ? otherCoast : 0);
$('#InvCoastinglbl_edit').text(Tot);
  $('#InvCoasting_edit').val(Tot);

}

$('input[type=radio][name=taxInvolved]').change(function(e) {
// var tax = $(this).attr('data-val');
// var invTot = $("#invTotLbl").text();
// var Tot = $("#invAllTotal").val();
// var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100 ).toFixed(2);
// $("#invAllTotal").val((parseFloat(invtotaltax)+parseFloat(Tot)).toFixed(1));
// $("#invTotLbl").text(invtotaltax);

 var tax = $('#invTax').val();
var invTot = $("#invTotLbl1").val();
var Tot = $("#invAllTotal").val();
var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100 ).toFixed(2);
var kasmTax2 = $('input[type=radio][name=taxkasmInvolved]:checked').attr('data-val');
var kasminvtotaltax2 = (parseFloat(invTot) * parseFloat(kasmTax2) / 100 ).toFixed(2);
var kasmTax = $(this).attr('data-val');
var kasminvtotaltax = (parseFloat(invTot) * parseFloat(kasmTax) / 100 ).toFixed(2);

$("#invAllTotal").val((parseFloat(invtotaltax)+parseFloat(invTot)+parseFloat(kasminvtotaltax) + parseFloat(kasminvtotaltax2)).toFixed(1));

});
$('input[type=radio][name=taxInvolved_edit]').change(function(e) {
// var tax = $(this).attr('data-val');
// var invTot = $("#invTotLbl").text();
// var Tot = $("#invAllTotal").val();
// var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100 ).toFixed(2);
// $("#invAllTotal").val((parseFloat(invtotaltax)+parseFloat(Tot)).toFixed(1));
// $("#invTotLbl").text(invtotaltax);

 var tax = $('#invTax_edit').val();
var invTot = $("#invTotLbl1_edit").val();
var Tot = $("#invAllTotal_edit").val();
var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100 ).toFixed(2);
var kasmTax2 = $('input[type=radio][name=taxkasmInvolved_edit]:checked').attr('data-val');
var kasminvtotaltax2 = (parseFloat(invTot) * parseFloat(kasmTax2) / 100 ).toFixed(2);
var kasmTax = $(this).attr('data-val');
var kasminvtotaltax = (parseFloat(invTot) * parseFloat(kasmTax) / 100 ).toFixed(2);

$("#invAllTotal_edit").val((parseFloat(invtotaltax)+parseFloat(invTot)+parseFloat(kasminvtotaltax) + parseFloat(kasminvtotaltax2)).toFixed(1));

});

$('input[type=radio][name=taxkasmInvolved]').change(function(e) {
// var tax = $(this).attr('data-val');
// var invTot = $("#invTotLbl").text();
// var Tot = $("#invAllTotal").val();
// var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100 ).toFixed(2);
// $("#invAllTotal").val((parseFloat(invtotaltax)+parseFloat(Tot)).toFixed(1));
// $("#invTotLbl").text(invtotaltax);

 var tax = $('#invTax').val();
var invTot = $("#invTotLbl1").val();
var Tot = $("#invAllTotal").val();
var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100 ).toFixed(2);
var kasmTax2 = $('input[type=radio][name=taxInvolved]:checked').attr('data-val');
var kasminvtotaltax2 = (parseFloat(invTot) * parseFloat(kasmTax2) / 100 ).toFixed(2);
var kasmTax = $(this).attr('data-val');
var kasminvtotaltax = (parseFloat(invTot) * parseFloat(kasmTax) / 100 ).toFixed(2);

$("#invAllTotal").val((parseFloat(invtotaltax)+parseFloat(invTot)+parseFloat(kasminvtotaltax)+ parseFloat(kasminvtotaltax2)).toFixed(1));

});

$('input[type=radio][name=taxkasmInvolved_edit]').change(function(e) {
// var tax = $(this).attr('data-val');
// var invTot = $("#invTotLbl").text();
// var Tot = $("#invAllTotal").val();
// var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100 ).toFixed(2);
// $("#invAllTotal").val((parseFloat(invtotaltax)+parseFloat(Tot)).toFixed(1));
// $("#invTotLbl").text(invtotaltax);

 var tax = $('#invTax_edit').val();
var invTot = $("#invTotLbl1_edit").val();
var Tot = $("#invAllTotal_edit").val();
var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100 ).toFixed(2);
var kasmTax2 = $('input[type=radio][name=taxInvolved_edit]:checked').attr('data-val');
var kasminvtotaltax2 = (parseFloat(invTot) * parseFloat(kasmTax2) / 100 ).toFixed(2);
var kasmTax = $(this).attr('data-val');
var kasminvtotaltax = (parseFloat(invTot) * parseFloat(kasmTax) / 100 ).toFixed(2);

$("#invAllTotal_edit").val((parseFloat(invtotaltax)+parseFloat(invTot)+parseFloat(kasminvtotaltax)+ parseFloat(kasminvtotaltax2)).toFixed(1));

});

function calc_table_price() {

var invTotal = $("#price").val();
$("#invTotLbl").text(invTotal);
$("#invTotLbl1").val(invTotal);
$("#invAllTotal").val(invTotal)

}

function calc_table_price_edit() {

var invTotal = $("#price_edit").val();
$("#invTotLbl_edit").text(invTotal);
$("#invTotLbl1_edit").val(invTotal);
$("#invAllTotal_edit").val(invTotal)

}

$("#invPaied").keyup(function (e) {
var paied = parseFloat($(this).val());
var total = parseFloat($("#invAllTotal").val());
var Safetotal = parseFloat($("#Safetotal").text());

if(paied > Safetotal){
    $(this).val(Safetotal)
}

if(paied < total){
    $("#dueDiv").show();

    $("#dueDate").attr('required',true);
}else if(paied = total){
    $("#dueDiv").hide();
    $("#dueDate").attr('required',false);
}else{
    $(this).val(0)
}
});

$("#invPaied_edit").keyup(function (e) {
var paied = parseFloat($(this).val());
var total = parseFloat($("#invAllTotal_edit").val());
var Safetotal = parseFloat($("#Safetotal_edit").text());

if(paied > Safetotal){
    $(this).val(Safetotal)
}
if(paied < total){
    $("#dueDiv_edit").show();

    $("#dueDate_edit").attr('required',true);
}else if(paied = total){
    $("#dueDiv_edit").hide();
    $("#dueDate_edit").attr('required',false);
}else{
    $(this).val(0)
}
});

$(document).on('change','paymentslect_edit',function(){
var paied = parseFloat($('#invPaied_edit').val());
var total = parseFloat($("#invAllTotal_edit").val());

if(paied < total){
    $("#dueDiv_edit").show();

    $("#dueDate_edit").val(due_date);
    $("#dueDate_edit").attr('required',true);
}else if(paied = total){
    $("#dueDiv_edit").hide();
    $("#dueDate_edit").attr('required',false);
}else{
    $(this).val(0)
}
});


