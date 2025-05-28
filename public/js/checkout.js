var InvoiceItems;
// var partStatus = {!! $partStatus !!};
$(document).ready(function () {
    InvoiceItems = JSON.parse(localStorage.getItem('shopCard'));

    // InvoiceItems.forEach(element => {
    //     $("#invoiceItems").append(`<tr>
    //                 <td>${element.amount}</td>
    //                 <td class="text-start">${element.name}
    //                     <span>${element.sourceId}</span>
    //                     <span>${element.statusId}</span>
    //                     <span>${element.qualityId}</span>
    //                 </td>
    //                 <td>${element.price}</td>
    //             <tr>`);
    // });


    $("input:checkbox[name='tax[]']").change(function() {
        var checked = $(this).is(":checked");
           if(checked){
            var oldValue = $("#invTaxTotal").text();
            var taxVal = $(this).val();
            var tot = $("#itemsTotal").text();

            var tValue =  parseFloat(tot)*parseFloat(taxVal)/100;
            tValue = tValue.toFixed(2);
            oldValue = parseFloat(oldValue).toFixed(2);
            $("#invTaxTotal").text((parseFloat(oldValue)+parseFloat(tValue)).toFixed(2));
            $("#tax").val((parseFloat(oldValue)+parseFloat(tValue)).toFixed(2));
            $("#invTotal").text( parseFloat( $("#invTaxTotal").text() ) + parseFloat(tot));
            $("#total").text( parseFloat( $("#invTaxTotal").text() ) + parseFloat(tot));
           }else{
            var oldValue = $("#invTaxTotal").text();
            var taxVal = $(this).val();
            var tot = $("#itemsTotal").text();
            var tValue = parseFloat(tot)*parseFloat(taxVal)/100;
            tValue = tValue.toFixed(2);
            oldValue = parseFloat(oldValue).toFixed(2);
            $("#invTaxTotal").text((parseFloat(oldValue)-parseFloat(tValue)).toFixed(2));
            $("#tax").val((parseFloat(oldValue)-parseFloat(tValue)).toFixed(2));
            $("#invTotal").text( parseFloat( $("#invTaxTotal").text() ) + parseFloat(tot))
            $("#total").text( parseFloat( $("#invTaxTotal").text() ) + parseFloat(tot));
           }

    });



    var allItems = $("#invoiceItems tr");

    for (let i = 0; i < allItems.length-3; i++) {
        const element = allItems[i];
        $(element).find('.saletype').val( $('input[name="price'+i+'[]"]:checked').val() );


    }

    


});


$("#clientsSlct").select2();
$("#storeSlct").select2();

$(document).on('change', '#clientsSlct', function(){
    var clientMad = $("#clientsSlct option:selected").attr('data-clientmad');
    $("#clientmad").text(clientMad);
})
$(document).on('change', '#storeSlct', function(){
    var selectedStore = $(this).val();
    // $.each($(".storeCls"), function (indexInArray, valueOfElement) {
    //     if($(valueOfElement).attr('value')== selectedStore){

    //     }
    // });
})


function RemoveItem(el){

    var price = $(el).closest('tr').find('.itemPriceCls').text();
    var totalPrice =$("#itemsTotal").text();
    $("#itemsTotal").text(parseFloat(totalPrice) - parseFloat(price));
    $("#subtotal").val(parseFloat(totalPrice) - parseFloat(price));
    $(el).closest('tr').remove();
}



$(document).on('change', '.priceTypeCls', function(){
    var itemAmount = $(this).closest('tr').find('.itemAmount').text();
    $(this).closest('tr').find('.itemPriceCls').text(parseInt(itemAmount) * parseInt($(this).attr('data-vv')));

    var allItems = $(this).closest('table').find('tr');
    var itemPriceT = 0;
    for (let i = 0; i < allItems.length-3; i++) {
        const element = allItems[i];
        var itemPrice = $(element).find('.itemPriceCls').text();
        itemPriceT += parseFloat(itemPrice) ;

    }
    $("#itemsTotal").text(0);
    $("#itemsTotal").text(itemPriceT);
    $("#subtotal").val(itemPriceT);
    $("#invTaxTotal").text(00);
    $("#tax").val(0);
    $("#invTotal").text(itemPriceT);
    $("#total").val(itemPriceT)

    $("input:checkbox[name='tax[]']").each(function (index, element) {

        $(element).prop('checked',false);
    });

})

$("#invPaied").keyup(function (e) {
    var invPaied = $("#invPaied").val();
    var invTotal = $("#invTotal").text();

    $("#invMad").val((invTotal-invPaied).toFixed(2));
});

$("#invDiscount").keyup(function (e) {
    var invPaied = $("#invPaied").val();
    var invTotal = $("#invTotal").text();
    var invdiscount = $("#invDiscount").val();

    $("#invMad").val((invTotal-invPaied-invdiscount).toFixed(2));
});
