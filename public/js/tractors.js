
$('#addTractor').on('show.bs.modal', function (event) {
    $(this).find('#myTab button:first').tab('show');
});
$('#addTractor').on('hidden.bs.modal', function () {
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


    $("#tractorBrandtypeSlctEdit").select2({
        dropdownParent: $('#editTractor')
    });
    $("#tractorBrandSlctEdit").select2({
        dropdownParent: $('#editTractor'),

    });
    $("#tractorModelSlctEdit").select2({
        dropdownParent: $('#editTractor')
    });
    $("#tractorSeriesSlctEdit").select2({
        dropdownParent: $('#editTractor')
    });
    $("#supplier_id_edit").select2({
        dropdownParent: $('#editTractor')
    });
    $("#currency_id_edit").select2({
        dropdownParent: $('#editTractor')
    });
    $("#status_id_edit").select2({
        dropdownParent: $('#editTractor')
    });
    $("#source_id_edit").select2({
        dropdownParent: $('#editTractor')
    });


    $("#quality_id_edit").select2({
        dropdownParent: $('#editTractor')
    });
    $("#country_id_edit").select2({
        dropdownParent: $('#addNewSupplier_edit')
    });
    $("#store_id_edit").select2({
        dropdownParent: $('#editTractor')
    });
    $("#relatedPartSlct_edit").select2({
        dropdownParent: $('#editTractor')
    });
    $("#rearTire_edit").select2({
        dropdownParent: $('#editTractor')
    });
    $("#frontTire_edit").select2({
        dropdownParent: $('#editTractor')
    });
    $("#rearTire").select2({
        dropdownParent: $('#addTractor')
    });
    $("#frontTire").select2({
        dropdownParent: $('#addTractor')
    });

    $("#tractorBrandtypeSlct").select2({
        dropdownParent: $('#addTractor')
    });
    $("#tractorBrandSlct").select2({
        dropdownParent: $('#addTractor')
    });
    $("#tractorModelSlct").select2({
        dropdownParent: $('#addTractor')
    });
    $("#tractorSeriesSlct").select2({
        dropdownParent: $('#addTractor')
    });
    $("#supplier_id").select2({
        dropdownParent: $('#addTractor')
    });
    $("#currency_id").select2({
        dropdownParent: $('#addTractor')
    });
    $("#status_id").select2({
        dropdownParent: $('#addTractor')
    });
    $("#source_id").select2({
        dropdownParent: $('#addTractor')
    });
    $("#quality_id").select2({
        dropdownParent: $('#addTractor')
    });
    $("#country_id").select2({
        dropdownParent: $('#addNewSupplier')
    });
    $("#store_id").select2({
        dropdownParent: $('#addTractor')
    });
    $("#relatedPartSlct").select2({
        dropdownParent: $('#addTractor')
    });


//   alert('ahmed')

     table = $('#tractorsDT').DataTable({
        dom: "Bfrtip",
        processing: true,
        serverSide: true,
        pageLength: 10,
        deferRender: true,
        responsive: true,
        destroy: true,
        ajax: "tractorsdata",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'Name'},
            {data: 'tractorNumbers', name: 'tractorNumbers'},
            {data: 'tractorSeries', name: 'tractorSeries'},
            {data: 'tractorBrand', name: 'tractorBrand'},
            {data: 'Image', name: 'Image' , orderable: false, searchable: false},
            {data: 'efragImage', name: 'efragImage' , orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        buttons: [

            "print",
        ]
    });


    $.ajax({
        type: "get",
        url: "wheeldimensions",
        success: function (response) {
            $("#frontTire").empty();
            $("#rearTire").empty();
            $("#frontTire").append(`<option value="">Select Dimension</option>`);
            $("#rearTire").append(`<option value="">Select Dimension</option>`);
            response.forEach(element => {
                $("#frontTire").append(`<option value="${element.id}">${element.dimension}</option>`);
                $("#frontTire_edit").append(`<option value="${element.id}">${element.dimension}</option>`);
                $("#rearTire").append(`<option value="${element.id}">${element.dimension}</option>`);
                $("#rearTire_edit").append(`<option value="${element.id}">${element.dimension}</option>`);
            });

        }
    });

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

    $.ajax({
        type: "get",
        url: "gearbox",
        success: function (response) {
            $("#gear_box").empty();
            $("#gear_box").append(`<option value="">Select Gearbox</option>`);
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
        url: "get_all_drive",
        success: function (response) {
            $("#drive").empty();
            $("#drive").append(`<option value="">Select Drive</option>`);
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
        url: "country",
        success: function (response) {
            $("#country_id").empty();
            $("#country_id").append(`<option value="">Select Country</option>`);
            response.forEach(element => {
                $("#country_id").append(`<option value="${element.id}">${element.name}</option>`);
            });

        }
    });

    $.ajax({
        type: "get",
        url: "part",
        success: function (response) {
            $("#relatedPartSlct").empty();
            $("#relatedPartSlct_edit").empty();
            $("#relatedPartSlct").append(`<option value="">Select Part</option>`);
            $("#relatedPartSlct_edit").append(`<option value="">Select Part</option>`);
            response.forEach(element => {
                $("#relatedPartSlct").append(`<option value="${element.id}">${element.name}</option>`);
                $("#relatedPartSlct_edit").append(`<option value="${element.id}">${element.name}</option>`);
            });

        }
    });

    $.ajax({
        type: "get",
        url: "tractorspecs",
        success: function (response) {
            $("#tractorSpecsTbl").empty();
            $("#tractorSpecsTblEdit").empty();
            response.forEach(element => {
                TractorDetails +=` <tr data-id="${element.id}">
                <td>${element.name} <input type="hidden" value="${element.id}" name="oldSpecs[]"></td>
                <td><input class="form-control"  type="text" name="oldspecsval[]" id="oldspecsval${element.id}" value=""></td>
                <td onclick="$(this).closest('tr').remove();" class="bg-danger rounded px-4 border-bottom"><i class="ri-delete-bin-5-line">حذف</i></td>
            </tr>`;
            TractorDetails1 +=` <tr data-id="${element.id}">
                <td>${element.name} <input type="hidden" value="${element.id}" name="oldSpecsEdit[]"></td>
                <td><input class="form-control"  type="text" name="oldspecsvalEdit[]" id="oldspecsvalEdit${element.id}" value=""></td>
                <td onclick="$(this).closest('tr').remove();" class="bg-danger rounded px-4 border-bottom"><i class="ri-delete-bin-5-line">حذف</i></td>
            </tr>`;

            });
            $("#tractorSpecsTbl").append(TractorDetails);
        }
    });

    $.ajax({
        type: "get",
        url: "tractorBrand",
        success: function (response) {
            $("#tractorBrandtypeSlctEdit").empty();
            $("#tractorBrandtypeSlct").empty();
            $("#tractorBrandSlct").empty();
            $("#tractorBrandSlctEdit").empty();
            $("#tractorBrandtypeSlct").append(`<option disabled selected value="">Select Tractor Type</option>`);
            $("#tractorBrandtypeSlctEdit").append(`<option selected disabled value="">Select brand Type</option>`);
            var brandtype = response[0];
            var brand = response[1];
            brandtype.forEach(element => {
                $("#tractorBrandtypeSlct").append(`<option value="${element.id}">${element.name}</option>`);
                $("#tractorBrandtypeSlctEdit").append(`<option value="${element.id}">${element.name}</option>`);
            });
            $("#tractorBrandSlct").append(`<option disabled selected value="">Select Brand</option>`);
            $("#tractorBrandSlctEdit").append(`<option disabled selected value="">Select Brand</option>`);
            brand.forEach(element => {
                $("#tractorBrandSlct").append(`<option value="${element.id}">${element.name}</option>`);
                $("#tractorBrandSlctEdit").append(`<option value="${element.id}">${element.name}</option>`);
            });

        }
    });

    $.ajax({
        type: "get",
        url: "storedrp",
        async: false,
        success: function (response) {
           console.log(response);
            $("#store_id").empty();


            $("#store_id").append(`<option value="">Select Store</option>`);

            response.forEach(element => {
                $("#store_id").append(`<option  value="${element.id}">${element.name}</option>`);
                $("#store_id_edit").append(`<option value="${element.id}">${element.name}</option>`);
                //////////////////////////// new ///////////////////////////////////////////////////
                $("#store_idd").append(`<option type-name="store" data-select2-id="${element.id}" value="${element.safe_accountant_number}">${element.name}</option>`);
            });

        }
    });

});

    var SupplierSlct ;
    var TractorDetails ;
    var TractorDetails1 ;
    var relatedPartEditArr=[];
    var pni=1;

    $("#newTractorNumberRow").click(function (e) {
        pni++;
        e.preventDefault();

        $("#tractorNumberTbl").append(` <tr>
                            <td><input class="form-control" type="text" name="tractorNumber[]" id=""></td>
                            <td>
                                <select name="supplierSlct[]" class="form-control supplierSlct " id="supplierSlct${pni}">
                                    ${SupplierSlct}
                                </select></td>
                                <td ><button class='btn  btn-danger btn-animation m-1' onclick="onclick="pni--; $(this).closest('tr').remove();" >حذف</button></td>
                                </tr>`);
        $("#supplierSlct"+pni).select2({
            dropdownParent: $('#addTractor') ,
            dropdownAutoWidth : true
        });
    });

    $('#addTractor').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset')

    });

    $('#editTractor').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset')

    });
    $(document).on("click", ".deletepartB", function () {
        // alert('xxx');
        var row = table.row($(this).closest("tr")).data();
        var selected_part_idx = $(this).attr("tractor_id_value2");
        $("#part_iddel").val(selected_part_idx);
        $("#pdel_name").val(row["name"]);

        //         $("#deletepartB form").attr("action", function() {
        //     return '{{route("part.destroy",["part" => $selected_part_idx])}}';
        // });
        var selected_part_idx = $(this).attr("tractor_id_value2");
        // $("#deletepartB form").attr("action", '{{ route("part.destroy", ["selected_part_idx" => ' + selected_part_idx + ']) }}');
        $($("#deletepartB").find("form")[0]).prop(
            "action",
            "tractor.destroy/" + selected_part_idx
        );
    });
    $(document).on('click', ".editTractorB" , function (e) {
        var selected_tractor_id = $(this).attr("tractor_id_value");
        $($("#editTractor").find('form')[0]).prop('action','tractor/'+selected_tractor_id)
        $("#tractor_id").val(selected_tractor_id);
        $.ajax({
            type: "get",
            // async :true,
            url: "tractorIdData/"+selected_tractor_id,
            success: function (response) {
                var tractor = response[0];
                console.log(tractor);
                var store = response[1];
                console.log(store);
                $("#name_edit").val(tractor[0].name);
                $("#buyTransaction_id").val(tractor[0].all_tractors[0].order_supplier.transaction_id);

                $("#name_en_edit").val(tractor[0].name_en);
                $("#tractor_number_edit").val(tractor[0].tractor_number);
                $("#color_edit").val(tractor[0].color);
                $("#bank_account_edit").val(tractor[0].all_tractors[0].order_supplier.bank_account);
                $("#price_edit").val(tractor[0].all_tractors[0].buy_price);
                $("#tank_capacity_edit").val(tractor[0].tank_capacity);
                $("#motornumber_edit").val(tractor[0].motornumber);
                $("#serivcedate_edit").val(tractor[0].serivcedate ?tractor[0].serivcedate.split('T')[0] : '').trigger('change');
                $("#deliverydate_edit").val(tractor[0].all_tractors[0].order_supplier.deliver_date ? tractor[0].all_tractors[0].order_supplier.deliver_date.split('T')[0] : '');
                $('#store_id_edit').val(store[0].store_id).trigger('change');
                $("#desc_edit").val(tractor[0].discs);
                $("#power_edit").val(tractor[0].power);
                $("#discs_edit").val(tractor[0].discs);
                $("#fronttirestatus_edit").val(tractor[0].fronttirestatus);
                $("#reartirestatus_edit").val(tractor[0].reartirestatus);
                $("#year_edit").val(tractor[0].year);
                $("#hours_edit").val(tractor[0].hours);
                $("#frontTire_edit").val(tractor[0].fronttire).trigger('change');
                $("#rearTire_edit").val(tractor[0].reartire).trigger('change');
                $("#gear_box_edit").val(tractor[0].gear_box).trigger('change');
                $("#drive_edit").val(tractor[0].drive).trigger('change');
                $("#supplier_id_edit").val(tractor[0].all_tractors[0].order_supplier.supplier_id).trigger('change');
                $("#status_id_edit").val(tractor[0].all_tractors[0].status_id).trigger('change');
                $("#source_id_edit").val(tractor[0].all_tractors[0].source_id).trigger('change');
                $("#currency_id_edit").val(tractor[0].all_tractors[0].order_supplier.currency_id).trigger('change');
                $("#quality_id_edit").val(tractor[0].all_tractors[0].quality_id).trigger('change');

                $("#tractorSpecsTblEdit").empty();
                $("#ralatedPartTbl_edit").empty();
                $("#tractorSpecsTblEdit").append(TractorDetails1);
                $('#store_idd_edit').val(tractor[0].all_tractors[0].order_supplier.bank_account).trigger('change');
                $('#transCoast_edit').val(tractor[0].all_tractors[0].order_supplier.transport_coast);
                $('#insuranceCoast_edit').val(tractor[0].all_tractors[0].order_supplier.insurant_coast);
                $('#customs_edit').val(tractor[0].all_tractors[0].order_supplier.customs_coast);
                $('#commition_edit').val(tractor[0].all_tractors[0].order_supplier.commotion_coast);
                $('#otherCoast_edit').val(tractor[0].all_tractors[0].order_supplier.other_coast);
                $('#InvCoastinglbl_edit').text(tractor[0].all_tractors[0].buy_costing);
                $('#invPaied_edit').val(tractor[0].all_tractors[0].order_supplier.paied);
                $('#paymentslect_edit').val(tractor[0].all_tractors[0].order_supplier.payment).trigger('change');
                $("#dueDate1_edit").val(tractor[0].all_tractors[0].order_supplier.due_date);
                $("#storeSctionSlct_edit").val(tractor[0].all_tractors[0].sections[0].section_id).trigger('change');
                if(tractor[0].all_tractors[0].order_supplier.payment  ){

                        $("#dueDiv_edit").show();
                        $("#dueDate1_edit").attr('required',true);
                }else{
                        $("#dueDiv_edit").hide();
                        $("#dueDate1_edit").attr('required',false);
                    }



                if(tractor[0].all_tractors[0].order_supplier.taxInvolved_flag == 0){
                    $('#inlineRadio1_edit').prop("checked", true);

                }else{
                    $('#inlineRadio2_edit').prop("checked", true);

                }
                if(tractor[0].all_tractors[0].order_supplier.taxkasmInvolved_flag == 0){
                    $('#inlineRadio21_edit').prop("checked", true);

                }else{
                    $('#inlineRadio11_edit').prop("checked", true);

                }
                if(tractor[0].tractor_details.length > 0){
                    var table = $("#tractorSpecsTblEdit tr");
                    var oldSpecs=[];
                    for (var i = 0; i < table.length; i++) {
                        rowid=$(table[i]).attr('data-id');
                        oldSpecs.push(rowid)
                    }
                    (tractor[0].tractor_details).forEach(element => {
                        var selectedSpec = element.Tractorpecs_id.toString();
                        var i = jQuery.inArray(selectedSpec, oldSpecs)
                        if(i != -1){
                            $("#oldspecsvalEdit"+oldSpecs[i]).val(element.value);
                        }else{
                            $("#tractorSpecsTblEdit").append(` <tr>
                                        <td><input class="form-control" type="text" name="specsEdit[]" id="${element.tractor_specs_id}" value="${element.tractor_spec.name}" readonly></td>
                                        <td><input class="form-control" type="text" name="specsvalEdit[]" id="${element.id}" value="${element.value}"></td>
                                        <td ><button class='btn  btn-danger btn-animation m-1' onclick="onclick="pni--; $(this).closest('tr').remove();" >حذف</button></td>
                                        </tr>`);
                        }
                    });
                }

                if(tractor[0].related_tractors.length > 0){
                    (tractor[0].related_tractors).forEach(element =>{
                        $("#ralatedPartTbl_edit").append(`<tr>
                        <td>${element.parts.name}<input type="hidden" name="relatedPartEdit[]" value=${element.parts.id}></td>
                        <td onclick="$(this).closest('tr').remove();relatedPartArrEdit.splice(relatedPartArrEdit.indexOf('$${element.parts.id}'), 1);">حذف</td>
                    </tr>`)
                    });
                }

                if(tractor[0].tractor_images.length > 0){
                    $(".upload__img-wrap_edit").empty();
                    for(var i=0 ; i < tractor[0].tractor_images.length ; i++){
                        $(".upload__img-wrap_edit").append(`<div class="upload__img-box"><div style="background-image: url(assets/tractor_images/${tractor[0].tractor_images[i].url})" imageURL ="${tractor[0].tractor_images[i].url}" data-number="0" data-file="Screen Shot 2022-08-23 at 1.40.33 PM.png" class="img-bg"><div class="upload__img-close"></div></div></div>
                        `)
                    }
                }

                if(tractor[0].efrag_images.length > 0){
                    $(".upload__img-wrap_editRelease").empty();
                    for(var i=0 ; i < tractor[0].efrag_images.length ; i++){
                        $(".upload__img-wrap_editRelease").append(`<div class="upload__img-box"><div style="background-image: url(assets/efrag_images/${tractor[0].efrag_images[i].image_name})" imageURL ="${tractor[0].efrag_images[i].image_name}" data-number="0" data-file="Screen Shot 2022-08-23 at 1.40.33 PM.png" class="img-bg"><div class="upload__img-close-efrag"></div></div></div>
                        `)
                    }
                }

                if(tractor[0].invoice_images.length > 0){
                    $(".upload__img-wrap_editInvoice").empty();
                    for(var i=0 ; i < tractor[0].invoice_images.length ; i++){
                        $(".upload__img-wrap_editInvoice").append(`<div class="upload__img-box"><div style="background-image: url(assets/invoice_images/${tractor[0].invoice_images[i].image_name})" imageURL ="${tractor[0].invoice_images[i].image_name}" data-number="0" data-file="Screen Shot 2022-08-23 at 1.40.33 PM.png" class="img-bg"><div class="upload__img-close-invoice"></div></div></div>
                        `)
                    }
                }
                if(tractor[0].series !=null){
                    $("#tractorBrandtypeSlctEdit").val(tractor[0].series.model.brand_type.id).trigger('change');
                    $("#tractorBrandSlctEdit").val(tractor[0].series.model.brand.id).trigger('change');
                    $("#tractorModelSlctEdit").val(tractor[0].series.model.id).trigger('change');
                    $("#tractorSeriesSlctEdit").val(tractor[0].series.id).trigger('change');
                }
                calc_table_price_edit();



            },complete : function(data){
                $("#storeSctionSlct_edit").val(data.responseJSON[0][0].all_tractors[0].sections[0].section_id).trigger('change');
            }
        });
    });

    $("#newTractorSpecRow").click(function (e) {

        e.preventDefault();
        $("#tractorSpecsTbl").append(` <tr>
                            <td><input class="form-control" type="text" name="specs[]" id=""></td>
                            <td><input class="form-control" type="text" name="specsval[]" id=""></td>
                            <td ><button class='btn  btn-danger btn-animation m-1' onclick="onclick="pni--; $(this).closest('tr').remove();" >حذف</button></td>
                        </tr>`);

    });
    $("#newTractorSpecRowEdit").click(function (e) {

        e.preventDefault();
        $("#tractorSpecsTblEdit").append(` <tr>
                            <td><input class="form-control" type="text" name="specsEdit[]" id=""></td>
                            <td><input class="form-control" type="text" name="specsvalEdit[]" id=""></td>
                            <td ><button class='btn  btn-danger btn-animation m-1' onclick="onclick="pni--; $(this).closest('tr').remove();" >حذف</button></td>

                        </tr>`);

    });


    $(document).on('change', '#tractorBrandtypeSlct', function(){
        $("#tractorBrandSlct").append(`<option disabled selected value="">Select Brand</option>`);
        $("#tractorModelSlct").empty();
        $("#tractorSeriesSlct").empty();
    });

    $(document).on('change', '#tractorBrandtypeSlctEdit', function(){
        $("#tractorBrandSlctEdit").append(`<option disabled selected value="">Select Brand</option>`);
        $("#tractorModelSlctEdit").empty();
        $("#tractorSeriesSlctEdit").empty();
    });

    $(document).on('change', '#tractorBrandSlct', function(){
        var brandid= $(this).val();
        var ttype_id= $("#tractorBrandtypeSlct").val();
        $.ajax({
            type: "get",
            async : false,
            url: "tractormodel/"+brandid+"/"+ttype_id,
            success: function (response) {
                $("#tractorModelSlct").empty();
                $("#tractorModelSlct").append(`<option disabled selected value="">Select Model</option>`);
                response.forEach(element => {
                    $("#tractorModelSlct").append(`<option value="${element.id}">${element.name}</option>`);
                });

            }
        });
    });

    $(document).on('change', '#tractorBrandSlctEdit', function(){
        var brandid= $(this).val();
        var ttype_id= $("#tractorBrandtypeSlctEdit").val();
        $.ajax({
            type: "get",
            async : false,
            url: "tractormodel/"+brandid+"/"+ttype_id,
            success: function (response) {
                $("#tractorModelSlctEdit").empty();
                $("#tractorModelSlctEdit").append(`<option disabled selected value="">Select Model</option>`);
                response.forEach(element => {
                    $("#tractorModelSlctEdit").append(`<option value="${element.id}">${element.name}</option>`);
                });

            }
        });
    });


    $(document).on('change', '#tractorModelSlct', function(){
        var modelId= $(this).val();
        $.ajax({
            type: "get",
            url: "tractorseries/"+modelId,
            success: function (response) {
                $("#tractorSeriesSlct").empty();
                $("#tractorSeriesSlct").append(`<option disabled selected value="">Select Series</option>`);
                response.forEach(element => {
                    $("#tractorSeriesSlct").append(`<option value="${element.id}">${element.name}</option>`);
                });
            }
        });
    });

    $(document).on('change', '#tractorModelSlctEdit', function(){
        var modelId= $(this).val();
        $.ajax({
            type: "get",
            url: "tractorseries/"+modelId,
            async : false,
            success: function (response) {
                $("#tractorSeriesSlctEdit").empty();
                $("#tractorSeriesSlctEdit").append(`<option disabled selected value="">Select Series</option>`);
                response.forEach(element => {
                    $("#tractorSeriesSlctEdit").append(`<option value="${element.id}">${element.name}</option>`);
                });
            }
        });
    });
    $(document).on('change', '#tractorSeriesSlct', function(){
        $("#model_id").val($(this).val()) ;
    });

    $(document).on('change', '#tractorSeriesSlctEdit', function(){
        $("#model_id_edit").val($(this).val()) ;
    });
    // $(document).on('submit', '#addSupplierForm', function(){
    //     event.preventDefault();
    //     console.log("sdfsdf");
    //     $("#addTractor").show();
    //     $("#addNewSupplier").hide();
    // });
    var relatedPartArr=[];
    $(document).on('change', '#relatedPartSlct', function(){
        var partId= $(this).val();
        var partIdTxt= $( "#relatedPartSlct option:selected" ).text();

        if($.inArray(partId,relatedPartArr) >= 0){

        }else{
            relatedPartArr.push(partId);
            $("#ralatedPartTbl").append(`<tr>

                <td>${partIdTxt}<input type="hidden" name="relatedPart[]" value=${partId}></td>
                <td ><button class='btn  btn-danger btn-animation m-1' onclick="$(this).closest('tr').remove();relatedPartEditArr.splice(relatedPartEditArr.indexOf('${partId}'), 1);" >حذف</button></td>
            </tr>`)
        }
    })

    $(document).on('change', '#relatedPartSlct_edit', function(){
        var partId= $(this).val();
        var partIdTxt= $( "#relatedPartSlct_edit option:selected" ).text();

        if($.inArray(partId,relatedPartEditArr) >= 0){

        }else{
            relatedPartEditArr.push(partId);
            $("#ralatedPartTbl_edit").append(`<tr>

                <td>${partIdTxt}<input type="hidden" name="relatedPartEdit[]" value=${partId}></td>
                <td ><button class='btn  btn-danger btn-animation m-1' onclick="$(this).closest('tr').remove();relatedPartEditArr.splice(relatedPartEditArr.indexOf('${partId}'), 1);" >حذف</button></td>
            </tr>`)
        }
    })
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

        $('body').on('click', ".upload__img-clos", function (e) {
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
            imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap_editRelease');
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
            imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap_editInvoice');
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

            $("#dueDate1").attr('required',true);
        }else if(paied = total){
            $("#dueDiv").hide();
            $("#dueDate1").attr('required',false);
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

            $("#dueDate1_edit").attr('required',true);
        }else if(paied = total){
            $("#dueDiv_edit").hide();
            $("#dueDate1_edit").attr('required',false);
        }else{
            $(this).val(0)
        }
    });

    $(document).on('change','paymentslect_edit',function(){
        var paied = parseFloat($('#invPaied_edit').val());
        var total = parseFloat($("#invAllTotal_edit").val());

        if(paied < total){
            $("#dueDiv_edit").show();

            $("#dueDate1_edit").val(due_date);
            $("#dueDate1_edit").attr('required',true);
        }else if(paied = total){
            $("#dueDiv_edit").hide();
            $("#dueDate1_edit").attr('required',false);
        }else{
            $(this).val(0)
        }
    })
