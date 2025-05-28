@extends('layouts.master')
@section('css')
    <style>
        body {
            background-color: #f8f9fa;
            /* font-family: 'Tajawal', sans-serif; */
        }
        .containerx {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h4 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .form-container {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-control {
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .btn {
            border-radius: 5px;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }
        .btn-success {
            background-color: #2ecc71;
            border-color: #2ecc71;
        }
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        .table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .table th {
            background-color: #67b1736e;
            color: #2c3e50;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }
        .input-group {
            display: flex;
            align-items: center;
        }
        .input-group .form-control {
            flex: 1;
        }
        .input-group .btn {
            margin-left: 10px;
        }
    </style>
@endsection
@section('title')
    Refund
@stop
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-primary " style="z-index: 88888 !important;" role="alert">
            <button type="button" class="btn-close bg-black text-white" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ $message }}
        </div>
    @endif

    <div class="main-content">
        <div class="page-content">

            <div class="containerx mt-5 w-10">
                <div>
                    <h4> إختار نوع الفاتورة</h4>

                </div>
                <!-- Radio Buttons -->
                <div class="mb-4">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="form_type" id="form1_radio" value="form1" checked>
                        <label class="form-check-label" for="form1_radio">فاتورة شراء</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="form_type" id="form2_radio" value="form2">
                        <label class="form-check-label" for="form2_radio">فاتورة بيع</label>
                    </div>
                </div>


                <!-- Form 1 -->
                <div id="form1" class="form-container form">

                    <form action="#" id="searchAllFrom2" class="" method="GET">
                        @csrf
                        <div class="form-group mb-4">
                            <div class="flex-column input-group">
                                <div class="d-flex w-100">
                                    <input type="text" id="searchalllabl2"  class="form-control fs-13 h-auto rounded-0"
                                        placeholder="ابحث عن صنف معين هنا ..." required>
                                    <button class="btn btn-soft-success rounded-0" type="submit"><i
                                            class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                   
                    <form id="buyform" action="" method="GET">
                        @csrf
                        <div class="flex-column input-group">
                            <div class="d-flex w-100">
                                <input required type="number" id="buy_bill_no" value=""  class="form-control fs-13 h-auto rounded-0"
                                    placeholder="أدخل رقم الفاتورة هنا ..." required>
                                <button class="btn btn-soft-success rounded-0" type="submit" id="form1btn"><i
                                        class="mdi mdi-magnify"></i></button>
                            </div>
                        </div>
                    </form>

                    <table class="table table-striped table-bordered cell-border  mt-3" style="font-size: medium;width:100%">
                        <thead style="background: #67b1736e;"> 
                            <tr>
                            <th class="text-center">النوع</th>
                            <th class="text-center">كمية مرتجعه</th>
                            <th class="text-center"> تسوية مالية </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>صنف</td>
                                <td>{{ count($refunded_part) > 0 ? count($refunded_part) : 0  }}</td>
                                <td rowspan="3">
                                    <a href="/refundSattlement" class="btn btn-info">تسوية مالية</a>
                                </td>
                            </tr>
                            <tr>
                                <td>كاوتش</td>
                                <td>{{ count($refunded_wheel) > 0 ? count($refunded_wheel) : 0  }}</td>
                               
                            </tr>
                            <tr>
                                <td>كيت</td>
                                <td>{{ count($refunded_kit) > 0 ? count($refunded_kit) : 0  }}</td>
                              
                            </tr>
                            

                        </tbody>
                    </table>


                </div>

                <!-- Form 2  -->

                <div id="form2" class="form-container form">
                    <form id="sellform" action="" method="GET">
                        @csrf
                        <div class="mb-3">
                            <select id="storedrp" class="form-control select2" name="option1"  placeholder="إختر المخزن" required>
                                <option value="" readonly selected>إختر المخزن</option>
                                @foreach ($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group mb-4">
                            <div class="flex-column input-group">
                                <div class="d-flex w-100">
                                    <input type="number" id="sell_bill_no" class="form-control fs-13 h-auto rounded-0"
                                        placeholder="   أدخل رقم الفاتورة هنا ..."  name="sell_bill_no" required>
                                    <button class="btn btn-soft-success rounded-0" type="submit" id="form2btn"><i
                                            class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="sell_bill_no"> أدخل رقم الفاتورة</label>
                            <input type="number" id="sell_bill_no" class="form-control" name="sell_bill_no">
                        </div>
                        <button id="form2btn" type="submit" class="btn btn-success"> بحث</button> --}}
                    </form>
                </div>

                <div id="form3" class="form-container form">
                    <div id="">
                        <table class="table table-striped table-bordered cell-border  mt-3" style="font-size: smaller;width:100%">
                            <thead style="background: #67b1736e;"> 
                                <tr>
                                <th>الصنف</th>
                                <th>إختار</th>
                                </tr>
                            </thead>
                            <tbody id="search_result">
                                <tr>
                                    <td colspan="2">Loading...</td>
                                </tr>
                                
                                
                            </tbody>
                        </table>
                    </div>

                    <button id="form3btn" type="button" class="btn btn-success formbtn"> عودة</button>
                </div>
                <div id="form4" class="form-container form">
                    <span  class="partname"></span>
                    <div id="">
                        <table class="table-bordered cell-border table table-sm table-striped  fw-bold m-1  ">
                            <thead style="background:#5fcee78a">
                                <tr>
                                    <th>رقم</th>
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
                                    <td colspan="11">Loading...</td>
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
        $(document).ready(function() {
            $('.select2').select2(); // Initialize Select2

            // Show Form 1 by default
            $(".form").hide();
            $("#form1").show();
            
           

            // Toggle between forms
            $('input[name="form_type"]').change(function() {
                if ($(this).val() === 'form1') {
                    $(".form").hide();
                    $("#form1").show();
             

                } else {
                    $(".form").hide();
                    $("#form2").show();
                }
            });

            $('#form1btn').click(function(event) {
                event.preventDefault(); // Prevent default form submission
                let buy_bill_no = $('#buy_bill_no').val();
                // let sup_no = $('#supdrp').val();
                let actionUrl = "{{ url('/refundBuyInv') }}/" + buy_bill_no ;
                $("#buyform").attr("action", actionUrl);
                $("#buyform").submit(); // Now submit the form with updated action
            });
            $('#form2btn').click(function(event) {
                event.preventDefault(); // Prevent default form submission

                let sell_bill_no = $('#sell_bill_no').val();
                let store_no = $('#storedrp').val();
                let actionUrl = "{{ url('/refund') }}/" + sell_bill_no + "/" + store_no;

                $("#sellform").attr("action", actionUrl);
                $("#sellform").submit(); // Now submit the form with updated action

            });
            $('#form3btn').click(function() {
                $(".form").hide();
                $("#form1").show();
               
               
            });
            $('#form4btn').click(function() {
                $(".form").hide();
                $("#form3").show();
            
            });
            $('#form5btn').click(function() {
                $(".form").hide();
                $("#form4").show();
               
            }); 

            $("#searchAllFrom2").submit(function(e) {
                e.preventDefault();
                var searckey = encodeURIComponent($("#searchalllabl2").val());
                $("#form3").show();
                $("#form1").hide();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: '/searchAllRefund?q=' + searckey,

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
                        console.log(data);
                        $("#search_result").html('');
                        if (data) {
                            var res = [];
                            data.forEach(element => {
                                res.push(
                                    `<tr>
                                        <td>
                                            <a href="/partDetailsRefund/${element.type_id}/${element.id}">${element.name} </a>
                                        </td> 
                                        <td>
                                            <button type="button" class="btn btn-info" onclick="partDetailsRefundFunc(${element.type_id},${element.id})"> اختيار</button>
                                        </td>
                                    
                                    </tr>`
                                    );

                            });

                            $("#search_result").html(res);
                        }



                    }
                });

            })
           
        });
        function partDetailsRefundFunc(typeId,p_id){



            $("#form3").hide();
            $("#form4").show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: '/partDetailsRefund/'+typeId +'/'+p_id,

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
                    console.log(data);
                   
                    $("#search_result2").html('');
                    if (data) {
                        if (data.type === "Part"){
                            var res = [];

                            $(".partname").text(data.name)
                            if(data.all_parts.length > 0){
                                data.all_parts.forEach(element => {
                                    res.push(
                                    ` <tr>
                                        <td> <a target="_blank" href="/printBuyInvoice/${ element.order_supplier.transaction_id }">
                                        فاتورة شراء رقم ${ element.order_supplier.transaction_id } </a></td>
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
                                                onclick="partDetailsRefundFunc2(${typeId},${p_id},${ element.order_supplier.id})">
                                            </td>
                                    </tr>`
                                    );

                                }); 
                            }else{
                                res.push(
                                    ` <tr>
                                        <td> لايوجد فواتير</td>
                                    </tr>`);
                            }
                        }else if (data.type === "Wheel"){
                            var res = [];
                            $(".partname").text(data.name)
                            if(data.all_wheels.length > 0){
                                data.all_wheels.forEach(element => {
                                    res.push(
                                    ` <tr>
                                        <td> <a target="_blank" href="/printBuyInvoice/${ element.order_supplier.transaction_id }">
                                        فاتورة شراء رقم ${ element.order_supplier.transaction_id } </a></td>
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
                                                onclick="partDetailsRefundFunc2(${typeId},${p_id},${ element.order_supplier.id})">
                                            </td>
                                    </tr>`
                                    );

                                }); 
                            }else{
                                res.push(
                                    ` <tr>
                                        <td> لايوجد فواتير</td>
                                    </tr>`);
                            }
                        }else if (data.type === "Kit"){
                            var res = [];
                            $(".partname").text(data.name)
                            if(data.all_kits.length > 0){
                                data.all_kits.forEach(element => {
                                    res.push(
                                    ` <tr>
                                        <td> <a target="_blank" href="/printBuyInvoice/${ element.order_supplier.transaction_id }">
                                        فاتورة شراء رقم ${ element.order_supplier.transaction_id } </a></td>
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
                                                onclick="partDetailsRefundFunc2(${typeId},${p_id},${ element.order_supplier.id})">
                                            </td>
                                    </tr>`
                                    );

                                }); 
                            }else{
                                res.push(
                                    ` <tr>
                                        <td> لايوجد فواتير</td>
                                    </tr>`);
                            }
                         
                        }
                       
                        // data.forEach(element => {
                        //     res.push(
                        //         ` <a href="/partDetailsRefund/${element.type_id}/${element.id}">${element.name} </a> </br>`
                        //         );

                        // });

                        $("#search_result2").html(res);
                    }



                }
            });

        }
        function partDetailsRefundFunc2(typeId,p_id,order_sup_idd){



            $("#form4").hide();
            $("#form5").show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: '/partDetailsRefund/'+typeId +'/'+p_id,

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
                    console.log(data);
                    $("#search_result3").html('');
                    if (data) {
                        if (data.type === "Part"){
                            var res = [];
                            $(".partname").text(data.all_parts[0].part.name)

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
                            $(".partname").text(data.all_wheels[0].wheel.name)

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
                            $(".partname").text(data.all_kits[0].kit.name)

                            data.allStores.forEach(element => {
                                var sub_row=[];
                                element.store_sup_data.forEach(elementsup => {
                                    if (elementsup.order_supplier_id ==order_sup_idd) {
                                        sub_row.push(`
                                        <td> ${elementsup.amount} </td>
                                        <td>  ${elementsup.all_kits[0].source.name_arabic}  </td>
                                        <td>  ${elementsup.all_kits[0].status.name}  </td>
                                        <td>  ${elementsup.all_kits[0].part_quality.name}  </td>
                                       
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
                    
                        // data.forEach(element => {
                        //     res.push(
                        //         ` <a href="/partDetailsRefund/${element.type_id}/${element.id}">${element.name} </a> </br>`
                        //         );

                        // });

                        $("#search_result3").html(res);
                    }



                }
            });


        }
        function store_amount_func(){

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
                            url: 'newRefundPart',
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
                                    partDetailsRefundFunc2(type_id,part_id,order_sup_id)
                                
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
