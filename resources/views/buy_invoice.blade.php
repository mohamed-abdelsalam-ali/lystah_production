@extends('layouts.master')
@section('css')


    <style>
        table {
            font-family: 'Droid Arabic Naskh', serif;
        }

        .modal-dialog {
            max-width: 80% !important;
        }
         td li {
            padding: 5px;
            margin: 5px 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #ddd;
        }
        .close {
            color: red;
            cursor: pointer;
            font-weight: bold;
        }
        .close:hover {
            color: darkred;
        }
    </style>
@endsection
@section('title')
   Buy Invoices
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
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Buy Invoice</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Buy Invoice</li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-head">
                            <div class="row">
                                <div class="col-8"></div>
                                <div class="col-2 px-0 mt-3 text-end">
                                    <button  class="btn btn-info newInvModalBtn" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                        فاتورة شراء</button>

                                </div>
                                <div class="col-2 px-0 mt-3 text-start">
                                    <a href="buyinv2" class="btn  mx-2 nav-link p-2 text-bg-primary" data-key="t-calendar">فاتورة شراء + توزيع مباشر</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="partsDT" class="table table-striped table-bordered cell-border " style="width:100%">
                                <thead style="background:#5fcee78a">
                                    <tr>
                                        <th>INV NO</th>
                                        <th>name</th>
                                         <th>efrag</th>
                                        <th>date</th>
                                        <th>creation_date</th>
                                        <th class="d-none">companyName</th>
                                        <th>supplierName</th>
                                        <th>UserName</th>
                                        <th>qaydNo</th>
                                        <th>companyid</th>
                                        <th>action</th>
                                        <th>upload</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">فاتورة جديــــدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {{-- <input type="file" id="file_upload" />
                    <button onclick="upload()">Upload</button>
                    <br><br>
                    <!-- container to display the json result -->
                    <textarea id="json-result" style="display:none;height:500px;width:350px;"></textarea> --}}
                <hr />

                <!--<form method="POST" action="{{ url('storeManage') }}" enctype="multipart/form-data">-->
                <form method="POST" class="needs-validation" novalidate id="addInvFrm" action="{{ url('storeManage1') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-6 text-end">
                                <label class="fs-2 text-decoration-underline" for="">رقم الفاتورة  : </label>
                            </div>
                            <div class="col-6 text-start">
                                <h2>EM# <span class="text-danger" id="newInvId"></span></h2>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label for="">Date</label>
                                <input class="form-control" type="date" name="invDate" id="invDate">
                            </div>
                            <div class="col-6">
                                <label for="">Company</label>
                                @php
                                    use App\Models\Company\User;
                                    $user = auth()->user();
                                    $generalUser = User::on('mysql_general')->where('email', $user->email)->first();
                                @endphp
                                <label class="form-control" name="invCompany" for="">{{ $generalUser->company_name }}</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <label class="mt-2" for="">بحث عن </label>
                                <select name="slected_type" id="slected_type" class="form-select " required>
                                    <option class="text-center" value="part" selected >صنف   </option>
                                    <option class="text-center" value="wheel"  >  كاوتش</option>
                                    <option class="text-center" value="kit"  > كيت </option>

                                </select>

                            </div>
                            <div class="col-lg-3">
                                <label class="mt-2" for="">Supplier</label>
                                <!--<button type="button" onclick="$('.supp').toggle();$('.supp1').toggleClass('d-none')"-->
                                <!--    class="bg-transparent border border-1 btn mb-1 rounded-circle">-->
                                <!--    <i class="ri-add-circle-fill fs-21"></i>-->
                                <!--</button>-->
                                <select name="invSupplier" id="" class="form-select required supp" required>

                                    <option value=""></option>
                                </select>
                                <input type="text" name="NewinvSupplier" id="" class="form-control d-none supp1">

                            </div>
                            <div class="col-lg-3">
                                <label class="mt-2" for="">Currency</label>
                                <select name="currency_id" id="currencySlct" class="form-control required mt-1" required>

                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label class="mt-2" for="">اختر حساب الصرف </label> <span class="float-end text-bg-danger" >المتاح ج.م<span id="Safetotal">0</span></span>
                                <select name="store_id" id="store_idd" class="form-select " required>
                                    <option class="text-center" value="" selected disabled>اختر اسم الحساب</option>
                                    @foreach ( $bank_types as $bank )
                                    <option class="text-center" type-name="bank" value="{{$bank->accountant_number}}">{{$bank->bank_name}} </option>
                                    @endforeach


                                    @foreach ( $store_safe as $safe )
                                    <option class="text-center" type-name="store" value="{{$safe->safe_accountant_number}}">{{$safe->name}} </option>
                                    @endforeach




                                </select>

                            </div>

                        </div>
                        <div class="row border mt-2">
                            <div class="col">
                                <div class=" p-4 bg-light">
                                    <label for=""> بحث </label>
                                    {{-- <input class="form-control" placeholder="Search Here" type="search" name="" id=""> --}}
                                    <select name="partSlct" id="partSlct"></select>
                                </div>
                                <table id="newinvtbl" class="table table-striped table-bordered cell-border " style="width:100%">
                                    <thead style="background:#5fcee78a">
                                        <tr>
                                            <td>
                                                <label for="">Item</label>
                                            </td>
                                            <td> <label for="">Source</label>
                                                <select class="form-select" name="" id="global_sourceslct">
                                                    <option value="">11</option>
                                                    <option value="">22</option>
                                                </select>
                                            </td>
                                            <td> <label for="">Status</label>
                                                <select class="form-select" name="" id="global_statuslct">
                                                    <option value="">11</option>
                                                    <option value="">22</option>
                                                </select>
                                            </td>
                                            <td> <label for="">Quality</label>
                                                <select class="form-select" name="" id="global_qualityslct">
                                                    <option value="">11</option>
                                                    <option value="">22</option>
                                                </select>
                                            </td>
                                            <td >unit</td>
                                            <td>Qty</td>
                                            <td>Price</td>
                                            <td>Total</td>
                                            <td class="d-none">
                                                <label for="">Store</label>
                                                <select class="form-select" name="" id="global_storeslct">

                                                </select>
                                            </td>
                                            <td>section</td>
                                            <td>Action</td>

                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <input type="hidden" name="InvCoasting" id="InvCoasting" value="0">
                                 <h5>تكلفة شراء البضاعة      : <span id="InvCoastinglbl"> 0</span></h5>
                                 <label class="mt-2" for=""> مصروفات شحن ونقل المشتريات </label>
                                <input   onkeyup="calc_coast()" type="number" name="transCoast" id="transCoast" value="0" class="form-control mt-1">
                                 <label class="mt-2" for=""> مصروفات التأمين على البضاعة المشتراة </label>
                                <input  onkeyup="calc_coast()" type="number" name="insuranceCoast" id="insuranceCoast" value="0" class="form-control mt-1">
                                 <label class="mt-2" for="">  الرسوم الجمركية على البضاعة المشتراة </label>
                                <input  onkeyup="calc_coast()" type="number" name="customs" id="customs" value="0" class="form-control mt-1">
                                 <label class="mt-2" for="">   عمولة وكلاء الشراء  </label>
                                <input  onkeyup="calc_coast()" type="number" name="commition" id="commition" value="0" class="form-control mt-1">
                                 <label class="mt-2" for=""> مصروفات اخرى    </label>
                                <input  onkeyup="calc_coast()" type="number" name="otherCoast" id="otherCoast" value="0" class="form-control mt-1">

                            </div>

                            <div class="col-4"></div>

                            <div class="col-4 text-end">
                                {{-- <button type="button" class="btn btn-success"> + Excel upload</button> --}}
                                <!--/////////////////////////////////////////////////////////////////////////-->
                                <div class="form-check form-check-inline m-3">
                                  <input class="form-check-input" data-val="0" type="radio" name="taxInvolved" value="0" checked id="inlineRadio1" value="option1">
                                  <label class="form-check-label" for="inlineRadio1">شامل ضريبة القيمة المضافة</label>
                                </div>
                                <div class="form-check form-check-inline m-3">
                                  <input class="form-check-input" data-val="14" type="radio" name="taxInvolved" value="1"  id="inlineRadio2" value="option2">
                                  <label class="form-check-label" for="inlineRadio2">غير شامل </label>
                                </div>

                                 <div class="form-check form-check-inline m-3">
                                  <input class="form-check-input" data-val="-1" type="radio" name="taxkasmInvolved" value="1" id="inlineRadio11" value="option1">
                                  <label class="form-check-label" for="inlineRadio11">ضريبة خصم أرباح تجارية وصناعية </label>
                                </div>
                                <div class="form-check form-check-inline m-3">
                                  <input class="form-check-input" data-val="0" type="radio" name="taxkasmInvolved" value="0" checked id="inlineRadio21" value="option2">
                                  <label class="form-check-label" for="inlineRadio21">لا  </label>
                                </div>


                                <!--//////////////////////////////////////////////////////////////////////-->
                                <input type="hidden" name="invTotLbl" id="invTotLbl1" value="0">
                                <h5>Items Total : <span id="invTotLbl"> 0000.0000 </span></h5>
                                <label class="mt-2" for=""> % Tax </label>
                                <input type="text" name="invTax" id="invTax" value="0"  class="form-control mt-1 d-none">
                                <label class="mt-2" for="">Total </label>
                                <input type="text" readonly name="invAllTotal" id="invAllTotal" value="0" class="form-control mt-1">
                                 <label class="mt-2" for="">Paied</label>
                                <input type="text" name="invPaied" id="invPaied" value="0" class="form-control mt-1">
                                <label class="mt-2" for="">سداد المبلغ المتبقي </label>
                                <select class="form-select mt-1" name="payment" id="paymentslect">
                                    <option value="0">كاش</option>
                                    <option value="1">تحويل بنكي</option>
                                    <option value="2"> علي الحساب</option>
                                </select>
                                <div style="display:none" id="dueDiv">
                                     <label class="mt-2" for="">Due Date</label>
                                    <input type="date" name="dueDate" class="form-control"   id="dueDate1">
                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="efragModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="efragModelLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="efragModelLabel"> تحميل صور علي الفاتورة <span id="buyTransactionId1">0</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
              <form action="/saveBuyTransactionEfrag" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')


                    <input type="hidden" name="buyTransactionId" id="buyTransactionId" value="0">
                    <input type="file"  class="form-control mx-3 w-75" name="efrag_image[]" multiple data-max_length="20">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Save</button>
                    </div>
              </form>



            </div>
        </div>
    </div>
@endsection

@section('js')

    <script src="{{ URL::asset('js/buy_inv.js') }}"></script>

    <script>
       $("#invPaied").keyup(function (e) {
            var paied = parseFloat($(this).val());
            var total = parseFloat($("#invAllTotal").val());

            if(paied < total){
                $("#dueDiv").show();
                $("#dueDate1").attr("required", true);
                 $("#paymentslect").val(2);
                //$("#dueDate1").prop('required',true);
            }else if(paied = total){
                $("#dueDiv").hide();
                $("#dueDate1").attr("required", false);
                if( $("#store_idd option:selected ").attr('type-name') ==  "bank"){
                    $("#paymentslect").val(1);
                }else{
                    $("#paymentslect").val(0);
                }
                //$("#dueDate1").prop('required',false);
            }else{
                $(this).val(0)
            }
        });
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                            // alert("لابد من تسجيل بعض البيانات لإدخال قطعة جديدة");
                            Swal.fire({
                            text: "لابد من تسجيل بعض البيانات لإدخال قطعة جديدة",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }

                        });
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })();

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
                }else{
                $("#Safetotal").text(response)
                }
            }
        });
    })


    // $('input[type=radio][name=taxInvolved]').change(function() {
    //     if (this.value == 0) // شامل
    //     {

    //     }
    //     else if (this.value == 1) // غير شامل
    //     {
    //         $("#invTotLbl").text( $("#invTotLbl").text() *1.14);
    //     }
    // });
    
    
    var efragModel = document.getElementById('efragModel');
    efragModel.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-val');
        $("#buyTransactionId").val(id);
        $("#buyTransactionId1").text(id);

    });

    function deleteBuyTransEfragImage(id){
        $.ajax({
                type: "get",
                url: "/deleteBuyTransEfragImage/" + id ,
                success: function(response) {
                    console.log(response);
                    alert(response.message);
                    location.reload();
                }
            });
    }
    </script>


@endsection
