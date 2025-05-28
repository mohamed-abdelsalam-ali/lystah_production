
$('#addClark').on('show.bs.modal', function (event) {
    $(this).find('#myTab button:first').tab('show');
});
$('#addClark').on('hidden.bs.modal', function () {
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
    $("#supplier_id").select2({
        dropdownParent: $('#addClark')
    });
    $("supplier_id_edit").select2({
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
    $("#frontTire").select2({
        dropdownParent: $('#addClark')
    });
    $("#front_tire_edit").select2({
        dropdownParent: $('#editClark')
    });
    $("#rear_tire_edit").select2({
        dropdownParent: $('#editClark')
    });
    $("#rearTire").select2({
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
     table = $('#clarksDT').DataTable({
        dom: "Bfrtip",
        processing: true,
        serverSide: true,
        pageLength: 10,
        deferRender: true,
        responsive: true,
        destroy: true,
        ajax: "clarksdata",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'clarkNumbers', name: 'clarkNumbers'},
            {data: 'series', name: 'series'},
            {data: 'clarkBrand', name: 'clarkBrand'},
            {data: 'Image', name: 'Image', orderable: false, searchable: false },
            {data: 'efragImage', name: 'efragImage', orderable: false, searchable: false },
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ] ,
        buttons: [

            "print",
        ]
    });


    $.ajax({
        type: "get",
        url: "wheeldimensions",
        success: function (response) {
            $("#front_tire").empty();
            $("#rear_tire").empty();
            $("#front_tire").append(`<option value="" selected readonly>Select Dimension</option>`);
            $("#rear_tire").append(`<option value="" selected readonly>Select Dimension</option>`);
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
            $("#gear_box").append(`<option value="" selected readonly>Select Gearbox</option>`);
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
            $("#currency_id").append(`<option value="" selected readonly>Select Currency</option>`);
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
            $("#status").append(`<option value="" selected readonly>Select Status</option>`);
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
            $("#source_id").append(`<option value="" selected readonly>Select Source</option>`);
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
            $("#store_id").append(`<option value="" selected readonly>Select Store</option>`);
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
            $("#quality_id").append(`<option value="" selected readonly>Select Quality</option>`);
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
            $("#drive").append(`<option value="" selected readonly>Select Drive</option>`);
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
            $("#supplier_id").empty();
            $("#supplier_id").append(`<option value="" selected readonly>Select Supplier</option>`);
            response.forEach(element => {
                $("#supplier_id").append(`<option value="${element.id}">${element.name}</option>`);
                $("#supplier_id_edit").append(`<option value="${element.id}">${element.name}</option>`);
                SupplierSlct +=`<option value="${element.id}">${element.name}</option>`;
            });

        }
    });

    $.ajax({
        type: "get",
        url: "country",
        success: function (response) {
            $("#country_id").empty();
            $("#country_id").append(`<option value="" selected readonly>Select Country</option>`);
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
                <td><input class="form-control"  type="text" name="oldspecsval[]" id="oldspecsval${element.id}" value="" selected readonly></td>
                <td onclick="$(this).closest('tr').remove();" class="bg-danger rounded px-4 border-bottom"><i class="ri-delete-bin-5-line">حذف</i></td>
            </tr>`;
            ClarkDetails1 +=` <tr data-id="${element.id}">
                <td>${element.name} <input type="hidden" value="${element.id}" name="oldSpecsEdit[]"></td>
                <td><input class="form-control"  type="text" name="oldspecsvalEdit[]" id="oldspecsvalEdit${element.id}" value="" selected readonly></td>
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
            $("#clarkBrandtypeSlct").append(`<option disabled selected value="" selected readonly>Select Clark Type</option>`);
            // $("#clarkBrandtypeSlct_edit").append(`<option selected disabled value="" selected readonly>Select brand Type</option>`);
            var brandtype = response[0];
            var brand = response[1];
            brandtype.forEach(element => {
                $("#clarkBrandtypeSlct").append(`<option value="${element.id}">${element.name}</option>`);
                $("#clarkBrandtypeSlct_edit").append(`<option value="${element.id}">${element.name}</option>`);
            });
            $("#clarkBrandSlct").append(`<option disabled selected value="" selected readonly>Select Brand</option>`);
            // $("#clarkBrandSlct_edit").append(`<option disabled selected value="" selected readonly>Select Brand</option>`);
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
            $("#relatedPartSlct").append(`<option value="" selected readonly>Select Part</option>`);
            $("#relatedPartSlct_edit").empty();
            $("#relatedPartSlct_edit").append(`<option value="" selected readonly>Select Part</option>`);
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
                $("#name_en_edit").val(clark[0].eng_name)
                $("#buyTransaction_id").val(clark[0].all_clarks[0].order_supplier.transaction_id);

                $("#clark_number_edit").val(clark[0].clark_number);
                $("#color_edit").val(clark[0].color);
                $("#supplier_id_edit").val(clark[0].all_clarks[0].order_supplier.supplier_id).trigger('change');

                $("#store_id_edit").val(store[0].store_id).trigger('change');
                $("#bank_account_edit").val(clark[0].all_clarks[0].order_supplier.bank_account);
                $("#tank_edit").val(clark[0].tank);
                $("#price_edit").val(clark[0].all_clarks[0].buy_price);
                $("#currency_id_edit").val(clark[0].all_clarks[0].order_supplier.currency_id).trigger('change');

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



                $('#store_idd_edit').val(clark[0].all_clarks[0].order_supplier.bank_account).trigger('change');
                $('#transCoast_edit').val(clark[0].all_clarks[0].order_supplier.transport_coast);
                $('#insuranceCoast_edit').val(clark[0].all_clarks[0].order_supplier.insurant_coast);
                $('#customs_edit').val(clark[0].all_clarks[0].order_supplier.customs_coast);
                $('#commition_edit').val(clark[0].all_clarks[0].order_supplier.commotion_coast);
                $('#otherCoast_edit').val(clark[0].all_clarks[0].order_supplier.other_coast);
                $('#InvCoastinglbl_edit').text(clark[0].all_clarks[0].buy_costing).trigger('change');
                $('#invPaied_edit').val(clark[0].all_clarks[0].order_supplier.paied);
                $('#paymentslect_edit').val(clark[0].all_clarks[0].order_supplier.payment).trigger('change');
                $("#dueDate_edit").val(clark[0].all_clarks[0].order_supplier.due_date);
                $("#storeSctionSlct_edit").val(clark[0].all_clarks[0].sections[0].section_id).trigger('change');



                if(clark[0].all_clarks[0].order_supplier.taxInvolved_flag == 0){
                    $('#inlineRadio1_edit').prop("checked", true);

                }else{
                    $('#inlineRadio2_edit').prop("checked", true);

                }
                if(clark[0].all_clarks[0].order_supplier.taxkasmInvolved_flag == 0){
                    $('#inlineRadio21_edit').prop("checked", true);

                }else{
                    $('#inlineRadio11_edit').prop("checked", true);

                }

                calc_table_price_edit();




            },complete : function(data){
                $("#storeSctionSlct_edit").val(data.responseJSON[0][0].all_clarks[0].sections[0].section_id).trigger('change');

                if( parseFloat( data.responseJSON[0][0].all_clarks[0].order_supplier.paied) < parseFloat ($("#invAllTotal_edit").val()) ){

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
        var selected_part_idx = $(this).attr("clark_id_value2");
        $("#part_iddel").val(selected_part_idx);
        $("#pdel_name").val(row["name"]);

        //         $("#deletepartB form").attr("action", function() {
        //     return '{{route("part.destroy",["part" => $selected_part_idx])}}';
        // });
        var selected_part_idx = $(this).attr("clark_id_value2");
        // $("#deletepartB form").attr("action", '{{ route("part.destroy", ["selected_part_idx" => ' + selected_part_idx + ']) }}');
        $($("#deletepartB").find("form")[0]).prop(
            "action",
            "clark.destroy/" + selected_part_idx
        );
    });
    $(document).on('change', '#clarkBrandtypeSlct', function(){
        $("#clarkBrandSlct").append(`<option disabled selected value="" selected readonly>Select Brand</option>`);
        $("#clarkModelSlct").empty();
        $("#clarkSeriesSlct").empty();
    });

    $(document).on('change', '#clarkBrandtypeSlct_edit', function(){
        $("#clarkBrandSlct_edit").append(`<option disabled selected value="" selected readonly>Select Brand</option>`);
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
                $("#clarkModelSlct").append(`<option disabled selected value="" selected readonly>Select Model</option>`);
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
                $("#clarkModelSlct_edit").append(`<option disabled selected value="" selected readonly>Select Model</option>`);
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
                $("#clarkSeriesSlct").append(`<option disabled selected value="" selected readonly>Select Series</option>`);
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
                // console.log(modelId);
                $("#clarkSeriesSlct_edit").empty();
                // $("#clarkSeriesSlct_edit").append(`<option disabled selected value="" selected readonly>Select Series</option>`);
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
})
