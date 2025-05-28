@extends('layouts.master')
@section('css')
    <style>
        table {
            font-family: 'Droid Arabic Naskh', serif;
        }

        .modal-dialog {
            max-width: 1000px !important;
        }
    </style>
@endsection
@section('title')
    Invoices
@stop


@section('content')

<div class="main-content">
    <div class="page-content">

        <div class="containerx mt-5 w-10">
            @if ($message = Session::get('success'))
                <div class="alert alert-primary " style="z-index: 88888 !important;" role="alert">
                    <button type="button" class="btn-close bg-black text-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{ $message }}
                </div>
            @endif

   
            <div id="form4" class="form-container form">
                <span  class="partname"></span>
                <div id="">
                    <table class="table-bordered cell-border table table-sm table-striped  fw-bold m-1  ">
                        <thead style="background:#5fcee78a">
                            <tr>
                                <th>رقم</th>
                                <th>الصنف</th>
                                
                                <th>التاريخ</th>
                                <th>المنشأ</th>
                                <th>الحالة</th>
                                <th>الجودة</th>
                                <th>الكمية</th>
                                <th>الكمية المتبقية</th>
                                <th>المورد</th>
                                <th>تكلفة القطعة</th>
                                <th>سعر القطعة</th>
                                <th>إختار</th>
                                {{-- <th>حذف</th> --}}
                            </tr>
                        </thead>
                        <tbody id="search_result2">
                            <tr>
                                <td colspan="12">Loading...</td>
                            </tr>
                            
                            
                        </tbody>
                    </table>
                </div>

                <button id="form4btn" type="button" class="btn btn-success formbtn"> عودة</button>
            </div>

            <div id="form5" class="form-container form">
                <span  class="partname"></span>
                <div id="">
                    <table class="table-bordered cell-border table table-sm table-striped  fw-bold m-1  ">
                        <thead style="background:#5fcee78a">
                            <tr>
                                <td> المخزن </td>
                                <td> الكمية </td>
                                <td> المنشأ </td>
                                <td> الحالة </td>
                                <td>الكفاءة</td>
                                <td>مرتجع</td>
                                <td>حفظ </td>
                            </tr>
                        </thead>
                        <tbody id="search_result3">
                            <tr>
                                <td colspan="7">Loading...</td>
                            </tr>
                            
                            
                        </tbody>
                    </table>
                </div>

                <button id="form5btn" type="button" class="btn btn-success formbtn"> عودة</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    const all_items ={!! $data !!};
    console.log(all_items);
    $(document).ready(function() {

        $("#form4").show();
        $("#form5").hide();
        loadpage();

        $('#form5btn').click(function() {
                $(".form").hide();
                $("#form4").show();
                location.href="/refundBuyInv/"+all_items[0].order_supplier.transaction_id;
               
        }); 
            $('#form4btn').click(function() {
                // $(".form").hide();
                // $("#form3").show();
                
                location.href="/refundCover";
            
            });
    });
    function loadpage(){
        $("#search_result2").html('');
      
     
        var res = [];
        all_items.forEach(element => {
            if (element) {
                if (element.type === "Part"){
                  
                    var typeId = 1;
                   
                       
                    res.push(
                    ` <tr>
                        <td> <a target="_blank" href="/printBuyInvoice/${ element.order_supplier.transaction_id }">
                        فاتورة شراء رقم ${ element.order_supplier.transaction_id } </a></td>
                        <td> ${element.part.name } </td>
                        <td> ${element.order_supplier.confirmation_date } </td>
                        <td> ${element.source.name_arabic } </td>
                        <td> ${ element.status.name } </td>
                        <td> ${ element.part_quality.name } </td>
                        <td> ${ element.amount } </td>

                        <td> ${ element.remain_amount } </td>
                            <td> ${ element.order_supplier.supplier.name } </td>
                            <td> ${ element.buy_costing } </td>
                            <td> ${ element.buy_price } / <a target="_blank" href="/show_currency/${element.order_supplier.currency_type.id }">${element.order_supplier.currency_type.name }</a>
                            </td>
                            
                            <td>
                                <input type="button"  class="btn btn-info" value="إختيار" 
                                onclick="partDetailsRefundFunc2(${typeId},${element.part_id},${ element.order_supplier.transaction_id },${ element.order_supplier.id})">
                            </td>
                    </tr>`
                    );

                   
                   
                }else if (element.type === "Wheel"){
                 
                    var typeId = 2;
               
                            res.push(
                            ` <tr>
                                <td> <a target="_blank" href="/printBuyInvoice/${ element.order_supplier.transaction_id }">
                                فاتورة شراء رقم ${ element.order_supplier.transaction_id } </a></td>
                                <td> ${element.wheel.name } </td>       
                                <td> ${element.order_supplier.confirmation_date } </td>
                                <td> ${element.source.name_arabic } </td>
                                <td> ${ element.status.name } </td>
                                <td> ${ element.part_quality.name } </td>
                                <td> ${ element.amount } </td>

                                <td> ${ element.remain_amount } </td>
                                    <td> ${ element.order_supplier.supplier.name } </td>
                                    <td> ${ element.buy_costing } </td>
                                    <td> ${ element.buy_price } / <a target="_blank" href="/show_currency/${element.order_supplier.currency_type.id }">${element.order_supplier.currency_type.name }</a>
                                    </td>
                                    
                                    <td>
                                        <input type="button"  class="btn btn-info" value="إختيار" 
                                        onclick="partDetailsRefundFunc2(${typeId},${element.part_id},${ element.order_supplier.transaction_id },${ element.order_supplier.id})">
                                    </td>
                            </tr>`
                            );

                     
                }else if (element.type === "Kit"){
                    
                    var typeId = 6;
              
                            res.push(
                            ` <tr>
                                <td> <a target="_blank" href="/printBuyInvoice/${ element.order_supplier.transaction_id }">
                                فاتورة شراء رقم ${ element.order_supplier.transaction_id } </a></td>
                                <td> ${element.kit.name } </td> 
                                <td> ${element.order_supplier.confirmation_date } </td>
                                <td> ${element.source.name_arabic } </td>
                                <td> ${ element.status.name } </td>
                                <td> ${ element.part_quality.name } </td>
                                <td> ${ element.amount } </td>

                                <td> ${ element.remain_amount } </td>
                                    <td> ${ element.order_supplier.supplier.name } </td>
                                    <td> ${ element.buy_costing } </td>
                                    <td> ${ element.buy_price } / <a target="_blank" href="/show_currency/${element.order_supplier.currency_type.id }">${element.order_supplier.currency_type.name }</a>
                                    </td>
                                    
                                    <td>
                                        <input type="button"  class="btn btn-info" value="إختيار" 
                                        onclick="partDetailsRefundFunc2(${typeId},${element.part_id},${ element.order_supplier.transaction_id },${ element.order_supplier.id})">
                                    </td>
                            </tr>`
                            );

                      
                    
                }  
            }
        });
        $("#search_result2").html(res);
    }
    function partDetailsRefundFunc2(typeId,p_id,inv_id,order_sup_idd){

        // var data= all_items.filter(x=>x.part_id == p_id && x.order_supplier_id == order_sup_idd )[0]

        $("#form4").hide();
        $("#form5").show();
       
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: '/partDetailsRefundAll/'+typeId +'/'+p_id+'/'+inv_id+'/'+order_sup_idd,

                datatype: 'JSON',
                statusCode: {
                    404: function() {
                        alert("page not found");
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    // alert("some error");
                    console.log(errorThrown);
                },
                success: function(data) {
                    if (data) {
                        if (data.type === "Part"){
                            var res = [];
                            $(".partname").text(data.part.name)

                            data.allStores.forEach(element => {
                                var sub_row=[];
                                element.store_sup_data.forEach(elementsup => {
                                    if (elementsup.order_supplier_id ==order_sup_idd) {
                                        sub_row.push(`
                                        <td> ${elementsup.amount} </td>
                                        <td>  ${elementsup.stores_log.all_parts[0].source.name_arabic}  </td>
                                        <td>  ${elementsup.stores_log.all_parts[0].status.name}  </td>
                                        <td>  ${elementsup.stores_log.all_parts[0].part_quality.name}  </td>
                                    
                                        <td>
                                            <input type="number" name="ref_amount" class="form-control ref_amount"  min="0" >
                                        </td>
                                        <td>

                                            <input type="button"  class="btn btn-info" value="حفظ" 
                                            onclick="saveRefundamount(${typeId},${p_id},${elementsup.stores_log.all_parts[0].source.id}
                                            ,${elementsup.stores_log.all_parts[0].status.id},${elementsup.stores_log.all_parts[0].part_quality.id},
                                            ${elementsup.amount},
                                            this,
                                            ${ elementsup.order_supplier_id},${element.store.id})">
                                    
                                        </td>
                                        `);     
                                    }
                                
                                });
                                res.push(
                                `<tr>
                                <td> ${element.store.name} </td>
                                    ${sub_row}
                                </tr>`
                                );

                            }); 
                        }else if (data.type === "Wheel"){
                            // alert('vv');
                            var res = [];
                            $(".partname").text(data.wheel.name)

                            data.allStores.forEach(element => {
                                var sub_row=[];
                                element.store_sup_data.forEach(elementsup => {
                                    if (elementsup.order_supplier_id ==order_sup_idd) {
                                        sub_row.push(`
                                        <td> ${elementsup.amount} </td>
                                        <td>  ${elementsup.stores_log.all_wheels[0].source.name_arabic}  </td>
                                        <td>  ${elementsup.stores_log.all_wheels[0].status.name}  </td>
                                        <td>  ${elementsup.stores_log.all_wheels[0].part_quality.name}  </td>
                                    
                                        <td>
                                            <input type="number" name="ref_amount" class="form-control ref_amount"  min="0" >
                                        </td>
                                        <td>

                                            <input type="button"  class="btn btn-info" value="حفظ" 
                                            onclick="saveRefundamount(${typeId},${p_id},${elementsup.stores_log.all_wheels[0].source.id}
                                            ,${elementsup.stores_log.all_wheels[0].status.id},${elementsup.stores_log.all_wheels[0].part_quality.id},
                                            ${elementsup.amount},
                                            this,
                                            ${ elementsup.order_supplier_id},${element.store.id})">
                                    
                                        </td>
                                        `);     
                                    }
                                
                                });
                                res.push(
                                `<tr>
                                <td> ${element.store.name} </td>
                                    ${sub_row}
                                </tr>`
                                );

                            }); 
                        }else if (data.type === "Kit"){
                            // alert('vv');
                            var res = [];
                            $(".partname").text(data.kit.name)

                            data.allStores.forEach(element => {
                                var sub_row=[];
                                element.store_sup_data.forEach(elementsup => {
                                    if (elementsup.order_supplier_id ==order_sup_idd) {
                                        sub_row.push(`
                                        <td> ${elementsup.amount} </td>
                                        <td>  ${elementsup.stores_log.all_kits[0].source.name_arabic}  </td>
                                        <td>  ${elementsup.stores_log.all_kits[0].status.name}  </td>
                                        <td>  ${elementsup.stores_log.all_kits[0].part_quality.name}  </td>
                                    
                                        <td>
                                            <input type="number" name="ref_amount" class="form-control ref_amount"  min="0" >
                                        </td>
                                        <td>

                                            <input type="button"  class="btn btn-info" value="حفظ" 
                                            onclick="saveRefundamount(${typeId},${p_id},${elementsup.stores_log.all_kits[0].source.id}
                                            ,${elementsup.stores_log.all_kits[0].status.id},${elementsup.stores_log.all_kits[0].part_quality.id},
                                            ${elementsup.amount},
                                            this,
                                            ${ elementsup.order_supplier_id},${element.store.id})">
                                    
                                        </td>
                                        `);     
                                    }
                                
                                });
                                res.push(
                                `<tr>
                                <td> ${element.store.name} </td>
                                    ${sub_row}
                                </tr>`
                                );

                            }); 
                        }
                    
                    

                        $("#search_result3").html(res);
                    }

                }
            });


}


function saveRefundamount(type_id,part_id,source,status,quality,remain_amount,el,order_sup_id,store_id){
            var refund_amount = parseInt($($(el).closest("tr").find(".ref_amount")).val());
            console.log(refund_amount);
            var data = {
                            'type_id': type_id,       
                            'part_id': part_id,     
                            'source': source,         
                            'status': status,        
                            'quality': quality,        
                            'remain_amount': remain_amount, 
                            'refund_amount': refund_amount, 
                            'order_sup_id':  order_sup_id,   
                            'store_id': store_id    
                        };

            //remove from all
            Swal.fire({
                    text: "هل تريد  إسترجاع كمية : " + refund_amount + "؟",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, refund!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST", 
                            url: '/newRefundPartAll',
                            data:JSON.stringify(data) ,
                            contentType: "application/json",
                            statusCode: {
                                404: function() {
                                    alert("page not found");
                                }
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                // alert("some error");
                                console.log(errorThrown);
                            },
                            success: function(data) {
                        
                                Swal.fire({
                                    text:  "العملية تمت بنجاح",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary",
                                    }
                                }).then(function () {
                                    // Remove current row
                                    partDetailsRefundFunc2(type_id,part_id,1,order_sup_id)
                                
                                });


                            }
                        });

                      
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text:   "تم الغاء العملية",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });
          
        }
</script>
  

@endsection
