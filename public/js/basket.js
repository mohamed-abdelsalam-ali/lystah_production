var shopCard = [];
var totalShopCard = 0;
$(document).ready(function () {


    // $("#cart-item-total").text('00000');
    // $("#page-header-cart-dropdown").find('span').text(0);
    // $(".dropdown-menu-cart").find('.cartitem-badge').text(0);
});

function addtopasket(el,partId,partName , Pimg="",sourceId,statusId,qualityId,amount,price=0) {

    if($("#selectedStore").val() == 0){
        alert("Please Select Store");
        return false;
    }
    var selectedSt = $("#selectedStore").val();
    var selectedStText = $("#selectedStore option:selected").text();
    var storelist = $(el).parent().parent().find('.storelist table tr');

    for(var i=0;i<storelist.length;i++){
        var storeRow = storelist[i];
        // console.log(storeRow)
        if($(storeRow).attr('id') == selectedSt){
            if(parseInt($(storeRow).find("td:eq(1)").text()) <= 0){
                if (confirm(" غير متوفر في"+selectedStText+"هل تريد المتابعة")){

                }else{
                    return false;
                }
            }
        }
    }

    var searchKey = partId+'-'+sourceId+'-'+statusId+'-'+qualityId;
    var founded = 0;
    var returnObj = shopCard.map((obj)=>{
        if(obj.id === searchKey ){
            founded=1;
        }
    })
    if(founded){
        // founded
        upd_obj = shopCard.findIndex((obj => obj.id == searchKey));
        shopCard[upd_obj].amount = parseInt(shopCard[upd_obj].amount)+1;
        localStorage.setItem('shopCard',JSON.stringify(shopCard));
        $("#inv_data").val(JSON.stringify(shopCard));
        $("#EG"+searchKey).find('.ecardAmount').text(parseInt($("#EG"+searchKey).find('.ecardAmount').text())+1);
        $("#EG"+searchKey).find('.cart-item-price').text(price*(parseInt($("#EG"+searchKey).find('.ecardAmount').text())));
        totalShopCard +=parseFloat(price);
        $("#cart-item-totals").text(totalShopCard);
        $("#inv_price").val(totalShopCard);

    }else{
        $("#cardCounterlbl").text(parseInt($("#cardCounterlbl").text())+1);
        $("#cardCounterlbl2").text(parseInt($("#cardCounterlbl2").text())+1);
        shopCard.push({id: searchKey ,name : partName, partId:partId, Pimg:Pimg, sourceId:sourceId, statusId:statusId, qualityId:qualityId,amount:amount, price:price, Sstore:selectedSt})
        localStorage.setItem('shopCard',JSON.stringify(shopCard));
        $("#inv_data").val(JSON.stringify(shopCard));
        $("#headerCardCnt").append(` <div id="EG${searchKey}" class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
        <div class="d-flex align-items-center">
            <img src="assets/part_images/${Pimg}" class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
            <div class="flex-1">
                <h6 class="mt-0 mb-1 fs-14">
                    <a href="" class="text-reset">
                        ${partName}</a>
                </h6>
                <p class="mb-0 fs-12 text-muted">
                    Quantity: <span><span class="ecardAmount">1</span> x $${price}</span>
                </p>
            </div>
            <div class="px-2">
                <h5 class="m-0 fw-normal">$<span class="cart-item-price">${price}</span></h5>
            </div>
            <div class="ps-2">
                <button onclick="removeCardItem(this,'${price}','${searchKey}')" type="button" class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i class="ri-close-fill fs-16"></i></button>
            </div>
        </div>
            </div>`);
        totalShopCard +=parseFloat(price);

        $("#cart-item-totals").text(totalShopCard);
        $("#inv_price").val(totalShopCard);
    }



}

function removeCardItem(el,price,itemid) {

    shopCard.splice(shopCard.findIndex(item => item.id === itemid), 1);
    localStorage.setItem('shopCard',JSON.stringify(shopCard));
    $("#inv_data").val(JSON.stringify(shopCard));
    $(el).closest('.dropdown-item-cart').remove();
    totalShopCard -=parseFloat(price);
    $("#cart-item-totals").text(totalShopCard);
    $("#inv_price").val(totalShopCard);
    $("#cardCounterlbl").text(parseInt($("#cardCounterlbl").text())-1);
    $("#cardCounterlbl2").text(parseInt($("#cardCounterlbl2").text())-1);
}


// $("#checkoutBtn").click(function (e) {
//     // e.preventDefault();
//     return  $.ajax({
//         type: "POST",
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         url: "checkout",
//         data: shopCard ,
//         dataType: "html"
//     });

// });
