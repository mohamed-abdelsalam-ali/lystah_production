var serviceTaxArr=[];
var serviceArr=[];
$(document).ready(function () {

    $("#optionSelect").select2({
      dropdownParent: $('#internalDiv')
    });
    $("#serviceSelect").select2({
      dropdownParent: $('#serviceDiv')
    });
    $("#name").select2({
      dropdownParent: $('#externalDiv')
    });
    item.service_taxes.forEach(element => {
      serviceTaxArr.push(element.tax_id);
      
    });
    item.service_invoice_items.forEach(element => {
      serviceArr.push(element.serviceid);
    });
    calcTotal();
    calcTaxes();
    calcDiscount();
    $.ajax({
      type: "get",
      url: '/servicesIndex',
      success: function (response) {
          $("#serviceSelect").empty();
          $("#serviceSelect").append(`<option  disabled selected value="0">Select Services</option>`);
          response.forEach(element =>{
              $("#serviceSelect").append(`<option value="${element.id}">${element.name}</option>`);
            });
        }
    });

    $.ajax({
      type: "get",
      url: '/allTaxes',
      success: function (response) {
          $("#serviceTax").empty();
          $("#serviceTax").append(`<option  disabled selected value="0">Select Service Tax</option>`);
          response.forEach(element =>{
              $("#serviceTax").append(`<option taxValue="${element.value}" value="${element.id}">${element.name}</option>`);
            });
        }
    });

    $.ajax({
      type: "get",
      url: '/allclient',
      success: function (response) {
          // $("#name").empty();
          $("#name").append(`<option  disabled  value="0">اختر إسم العميل</option>`);
          response.forEach(element =>{
              $("#name").append(`<option phone="${element.tel01}" value="${element.id}">${element.name}</option>`);
            });
        }
    });

   
    $("input:radio[name='serviceOption']").trigger('change');
    $("input:radio[name='serviceType']").trigger('change');
    $("#optionSelect").val(item.itemid).trigger('change');
    
    
    
});

$("input:radio[name='serviceOption']").change(function() {
  $('#optionSelect').empty();
  $("#optionSelect").append(`<option value="0">اختر نوع المعدة أولا</option>`);

  if($("input[name='serviceOption']:checked").val() == "internal"){
    $("#externalDiv").hide();
    $("#internalDiv").show();
    }else if($("input[name='serviceOption']:checked").val()=="external"){
      $("#internalDiv").hide();
    $("#externalDiv").show();
    }
      
  
});
var selecturl="";
$("input:radio[name='serviceType']").change(function() {
  if($("input[name='serviceType']:checked").val() == "Tractor"){
    selecturl="tractor";
    
  }else if($("input[name='serviceType']:checked").val() == "Equipment"){
    selecturl="equip";
  }else if($("input[name='serviceType']:checked").val() == "Clark"){
    selecturl="clark";
  }
    $.ajax({
      type: "get",
      url: '/servicegetItem',
      async : false,
      data:{
        'servicType' : selecturl
      },
      success: function (response) {
          $("#optionSelect").empty();
          $("#optionSelect").append(`<option disabled selected value="0">Select `+selecturl+`</option>`);

          if (selecturl == 'clark') {
            response.forEach(element =>{
              $("#optionSelect").append(`<option value="${element.stores_log.all_clarks[0].clark.id}">${element.stores_log.all_clarks[0].clark.name}</option>`);
            });
          }else if(selecturl == 'tractor') {
            response.forEach(element =>{
              $("#optionSelect").append(`<option value="${element.stores_log.all_tractors[0].tractor.id}">${element.stores_log.all_tractors[0].tractor.name}</option>`);
            });
          }else if (selecturl == 'equip'){
            response.forEach(element =>{
              $("#optionSelect").append(`<option value="${element.stores_log.all_equips[0].equip.id}">${element.stores_log.all_equips[0].equip.name}</option>`);
            });
          }
          
        }
    });
});


  $(document).on('change', '#serviceSelect', function(){
      var serviceId= parseInt($(this).val());
      var serviceIdTxt= $( "#serviceSelect option:selected" ).text();

      if($.inArray(serviceId,serviceArr) >= 0){

      }else{
        serviceArr.push(serviceId);
        
          $("#addServicesTbl tbody").append(`<tr>

              <td>${serviceIdTxt}<input type="hidden" name="addedServices[]" value=${serviceId}></td>
              <td><input type="number"   name="servicePrice[]" value="0" class="servicePrice inputText text-center" placeholder=" سعر الخدمة" required></td>
              <td onclick="$(this).closest('tr').remove();serviceArr.splice(serviceArr.indexOf('${serviceId}'), 1);"><button type="submit" id="deletePrice" value="" class="btn-sm deleteButton"><i class="ri-delete-bin-6-fill"></i></button></td>
          </tr>`)
      }
      calcTotal()
  });
  
  $(document).on('click', '#deletePrice', function(){
    calcTotal()
  });
  $(document).on('click', '#deleteTax', function(){
    calcTaxes()
  });

  $(document).on('change', '#optionSelect', function(){
    $('#item_id').val($('#optionSelect option:selected').attr('value'));
  });

  $(document).on('change', '#serviceTax', function(){
      var serviceTaxId= parseInt($(this).val());
      var taxValue= $( "#serviceTax option:selected" ).attr('taxValue');
      var serviceTaxIdTxt= $( "#serviceTax option:selected" ).text();

      if($.inArray(serviceTaxId,serviceTaxArr) >= 0){

      }else{
        serviceTaxArr.push(serviceTaxId);
          $("#serviceTaxTbl").append(`<tr>
              <td>${serviceTaxIdTxt}<input type="hidden" name="addedServiceTaxes[]" value=${serviceTaxId}></td>
              <td name="taxValueTxt[]">${taxValue} %</td>
              <td onclick="$(this).closest('tr').remove();serviceTaxArr.splice(serviceTaxArr.indexOf('${serviceTaxId}'), 1);"><button type="submit" id="deleteTax" value="" class="btn-sm deleteButton"><i class="ri-delete-bin-6-fill"></i></button></td>
          </tr>`)
      }
      
  });

$(document).on('keyup', '.servicePrice', function(e) {
    calcTotal();
});

$(document).on('change', '#serviceTax', function(e) {
  calcTaxes();
});

$(document).on('keyup', '#serviceDiscount', function(e) {
  calcDiscount();
});
$(document).on('keyup', '#totalPaid', function(e) {
  $('#remainValue').text( parseFloat($('#paymentFinalValue').text()) - parseFloat($(this).val()).toFixed(2));
  $('#remain').val(parseFloat($('#paymentFinalValue').text() - $('#totalPaid').val())); 
});

$(document).on('change' , '#name' , function(e){
  $('#client_id').val($("#name option:selected" ).attr('value'))
  $('#phone').val($("#name option:selected" ).attr('phone'));
});




function calcTotal(){
  var totalPrice=0;
  var price = 0;
  $("#paymentValue").text(0);
  $("#addServicesTbl .servicePrice").each(function() {
    price = parseFloat($(this).val());
    totalPrice += price;
  });
    $("#paymentValue").text(totalPrice);
    $("#remainValue").text((parseFloat(totalPrice - $('#totalPaid').val() ).toFixed(2)));
    $("#totalbefortax").val(totalPrice);
    $('#remain').val(($('#paymentFinalValue').text() - $('#totalPaid').val()).toFixed(2)); 



    calcTaxes();

}

function calcTaxes(){
  var totalTaxes=0;
  $("#totalTaxes").text(0);
  $("#totalPayment").text($("#paymentValue").text());
  $("#paymentFinalValue").text($("#paymentValue").text());
  $('#serviceTaxTbl tbody> tr').each(function() {
    let tax = parseFloat($(this).children().eq(1).html());
    totalTaxes+= tax;
    $("#totalTaxes").text(totalTaxes);
    $("#totalPayment").text(parseFloat($("#paymentValue").text()) +((parseFloat($("#paymentValue").text())  * totalTaxes) / 100) );
    $("#paymentFinalValue").text($("#totalPayment").text() );
    $("#remainValue").text((parseFloat($("#paymentFinalValue").text() - $('#totalPaid').val())).toFixed(2));

  });
  $('#total').val($("#totalPayment").text());
  $('#totaltax').val(totalTaxes);
  calcDiscount();
}

function calcDiscount(){
  $('#paymentFinalValue').text(0);
  var discount = 0;
  var totalPayment =0;
  var finalPay =0;
  discount=parseFloat($("#serviceDiscount").val());
  totalPayment = $('#totalPayment').text();
    finalPay = totalPayment - ((discount/100) * totalPayment );
  $('#paymentFinalValue').text(finalPay); 
  $('#remainValue').text((parseFloat($('#paymentFinalValue').text()) - parseFloat($('#totalPaid').val())).toFixed(2)); 
  $('#remain').val(parseFloat($('#paymentFinalValue').text() ) - parseFloat($('#totalPaid').val())); 
  
}





