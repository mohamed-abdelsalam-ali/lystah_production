
$(document).ready(function () {
    var pricingTable = $("#pricingTbl tr");
    allpartsInStore.forEach(part => {

        var SaleTypeInputs = $("#pricingTbl tr[data-part-id="+part.part_id+"][data-source-id="+part.source_id+"][data-status-id="+part.status_id+"][data-quality-id="+part.quality_id+"]").find('input[type=text]');
        for (let i = 0; i < SaleTypeInputs.length; i++) {
            const element = SaleTypeInputs[i];
            part.pricing.forEach(saleprice => {
                if(saleprice.sale_type.id == $(element).attr('data-sale_type')){
                    $(element).val(saleprice.price);
                    $(element).parent().find('.lastpricespan').text(saleprice.price)
                    $(element).removeClass('text-bg-danger');
                    $(element).addClass('text-bg-light');
                }

            });
            
            $(element).parent().append(`<button type="button" class="btn btn-link showhidehistory"> Show / Hide </button>`);
            $(element).parent().append(`<table class="table table-striped" style="display:none"></table>`);
            $(element).parent().find('table').append(`<tr class="text-center">
                <th>السعر</th>
                <th>من</th>
                <th>إلي</th>
            </tr>`);
            if(  part.lastPricing0.length > 0){
                  part.lastPricing0.forEach(lastsellprice => {
                if(lastsellprice.sale_type.id == $(element).attr('data-sale_type')){
                    $(element).parent().find('table').append(`
                        <tr class="text-nowrap">
                            <td>${lastsellprice.price}</td>
                            <td>${lastsellprice.from}</td>
                            <td>${lastsellprice.to}</td>
                            
                        </tr>
                        
                    `)
                }

            });
            }
          

        }


    });

    // $("#convertEGYBtn").trigger('click');
    
    $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
    
    
    $(".pricingSpan").each(function(index, sp) { 
   
        var cur_id =  $(sp).attr('data-currency-type') ;
        var cur_buy_price =  $(sp).text() ;
        var cur_pricing = currencyHistory.find(x => x.currency_id == cur_id).value;
       
       $(sp).parent().find('.EGYPrice').text(parseFloat(cur_buy_price) * parseFloat(cur_pricing) );
      
    });
    $(".coastingSpan").each(function(index, sp) { 
   
        var cur_id =  $(sp).attr('data-currency-type') ;
        var cur_buy_price =  $(sp).text() ;
        var cur_pricing = currencyHistory.find(x => x.currency_id == cur_id).value;
       if(cur_buy_price){
            var egycc = parseFloat(cur_buy_price)  ;
                    // var egycc = parseFloat(cur_buy_price) * parseFloat(cur_pricing) ;
                  $(sp).parent().find('.EGYcoast').text( Math.round(egycc));
                  $(sp).parent().parent().find('.totEGYcoast').text(Math.round(parseFloat($(sp).parent().parent().find('.EGYPrice').text())) +  Math.round(egycc));

       }
      
    });
});
 
 $(".coastingSpan").each(function(index, sp) { 
   
        var cur_id =  $(sp).attr('data-currency-type') ;
        var cur_buy_price =  $(sp).text() ;
        var cur_pricing = currencyHistory.find(x => x.currency_id == cur_id).value;
        // var egycc = parseFloat(cur_buy_price) * parseFloat(cur_pricing) ;
        var egycc = parseFloat(cur_buy_price) ;
       $(sp).parent().find('.EGYcoast').text(Math.round(egycc));
       $(sp).parent().parent().find('.totEGYcoast').text(Math.round(parseFloat($(sp).parent().parent().find('.EGYPrice').text())) +  Math.round(egycc));
      
    });
$(document).on('change','.priceinp', function (e) {
    var value =  $(this).val();
    var egyPrice = $(this).closest('tr').find('.EGYPrice').text();
    var egyPricecost = $(this).closest('tr').find('.EGYcoast').text();
    value = parseFloat(value);
    egyPrice = parseFloat(egyPrice);
    egyPricecost = parseFloat(egyPricecost);
    //alert(egyPrice);
    if(egyPrice + egyPricecost  > value ){
        alert("برجــــــــاء مراجعة السعر")
    }
});
    // $('.needs-validation').on('submit', function() {
    
    //   alert('sss');
    //   $('.needs-validation').serialize();
    //     return false;
    // });
$(document).dblclick(".pricePercentBtn",function (e) {
    e.preventDefault();
 /////////////////////////old*********************
    // pricePercent
    // var inputEle = $(e.target);
    // var percentval = $(inputEle).val();
    // console.log( $("#"+inputEle.attr('id')).parent().parent().index() );
    // $("input[name='"+inputEle.attr('id')+"[]']").each(function() {
        
        // //var newPercent = parseFloat( $(this).val() ) + ( parseFloat( $(this).val() ) * parseInt(percentval) /100 )
        // var newPercent = parseFloat( parseFloat($(this).parent().find('.lastpricespan').text()) ) + ( parseFloat( $(this).val() ) * parseInt(percentval) /100 )
        // if(newPercent){
        //     $(this).val(newPercent.toFixed(2));
        // }else{
        //     $(this).val(0);
        // }
       
    
    // });
 //////////////////////////////new**************
  var inputEle = $(e.target);
        var percentval = $(inputEle).val();
        console.log( $("#"+inputEle.attr('id')).parent().parent().index() );
        $("input[name='"+inputEle.attr('id')+"[]']").each(function() {
            //var newPercent = parseFloat( $(this).val() ) + ( parseFloat( $(this).val() ) * parseInt(percentval) /100 )
            var newPercent = 0;

             if(parseFloat($(this).parent().find('.lastpricespan').text()) > 0){
                newPercent = parseFloat( parseFloat($(this).parent().find('.lastpricespan').text()) ) +  (parseFloat( $(this).val() ) * parseInt(percentval) /100 );
            }else{
                // newPercent=parseFloat($(this).closest('tr').find('.EGYPrice').text())+(parseFloat($(this).closest('tr').find('.EGYPrice').text())*parseInt(percentval) /100);
                newPercent=parseFloat($(this).closest('tr').find('.totEGYcoast').text())+(parseFloat($(this).closest('tr').find('.totEGYcoast').text())*parseInt(percentval) /100);
                
                
            }


            if(newPercent){
                $(this).val(newPercent.toFixed(2));
            }else{
                $(this).val(0);
            }
        });

});

$('#currencySlct').on('change', function() {
  var cur_id =  this.value ;
  var cur_val =  $("#currencySlct option:selected" ).attr('data-last-price') ;
  if(cur_val > 0){
      
  }else{
    alert("برجــــــاء إضافة تسعيرة للعملة");    
  }
  
  
});

$("#convertEGYBtn").click(function() {
    // currencyHistory  Array
    // span have cur_type pricingSpan->data-currency-type
    // span have price  pricingSpan
    
    $(".pricingSpan").each(function(index, sp) { 
    //   console.log(index);
        var cur_id =  $(sp).attr('data-currency-type') ;
        var cur_buy_price =  $(sp).text() ;
        var cur_pricing = currencyHistory.find(x => x.currency_id == cur_id).value;
       
       $(sp).parent().find('.EGYPrice').text(parseFloat(cur_buy_price) * parseFloat(cur_pricing) );
       $(sp).closest('tr').find('input').val(parseFloat(cur_buy_price) * parseFloat(cur_pricing) );
    });
    
})

$(document).on('click', '.showhidehistory', function(e){

    e.preventDefault();
    $(this).parent().find('table').toggle();
})
