@extends('layouts.posMaster')
@section('title')
    صرف بضاعة لمخزن
@stop
@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active"> صرف بضاعة لمخزن </li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card">


                <div class="card-body fs-19 fw-bold">
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="container mt-5">
                                <!-- Search Bar -->
                                <div class="mb-3">
                                    <input type="text" id="searchBar" class="form-control" placeholder="ابحث هنا...">
                                </div>
                            
                                @forelse($data as $key => $value)
                                <form method="POST" action="/SendToStoreNew">
                                    @csrf
                                    @method('POST')
                                    <div class="card mb-3 order-card" data-order-id="{{ $value->id }}">
                                        <div class="card-header" style="background:#5fcee78a;">
                                            <h5 class="card-title">تفاصيل الطلب</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <?php 
                                                    $ratioamount=getSmallUnit($value->unit_id,$value->itemData->small_unit);
                                                    ?>
                                                    <p><strong>التاريخ:</strong> {{ $value->created_at }}</p>
                                                    <p><strong>النوع:</strong> {{ $value->type }}</p>
                                                    <p><strong>المنشأ:</strong> {{ $value->source->name_en }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p><strong>الصنف:</strong> {{ $value->itemData->name }}</p>
                                                    <p><strong>الحالة:</strong> {{ $value->status->name }}</p>
                                                    <p><strong>الجودة:</strong> {{ $value->part_quality->name }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p><strong>الكمية المطلوبة :</strong> {{ $value->amount / $ratioamount }} /{{$value->unit->name  }}</p>
                                                    <p><strong>مطلوب لمخزن :</strong> {{ $value->fromstore->name }}</p>
                                                </div>
                                            </div>
                            
                                            @if (count($value->storeSection) > 0)
                                                <input type="hidden" name="storeId" value="{{ $value->fromstore->id }}">
                                                <input type="hidden" name="partIdS" value="{{ $value->part_id }}">
                                                <input type="hidden" name="partTypeS" value="{{ $value->type_id }}">
                                                <input type="hidden" name="partStatusS" value="{{ $value->status_id }}">
                                                <input type="hidden" name="partSourceS" value="{{ $value->source_id }}">
                                                <input type="hidden" name="partQualityS" value="{{ $value->quality_id }}">
                                                <input type="hidden" name="CurrentstoreId" value="{{ $value->to_store_id }}">
                                                <input type="hidden" name="processFrom" value="askParts">
                                                <input type="hidden" name="demand_id" value="{{ $value->id }}">
                                                <input type="hidden" name="demand_amount" class='demand_amount' value="{{ $value->amount / $ratioamount }}">
                                                <input type="hidden" class="mesurment_unit" name="unitsend_id" value="{{ $value->unit_id }}">
                                                <input type="hidden" class="demand_ratio" name="demand_ratio" value="{{ $ratioamount }}">
                            
                                                <div class="mt-3">
                                                    <hr>
                                                    <h2> المتاح </h2>
                                                    @foreach ($value->storeSection as $sec)
                                                        @if ($sec->amount > 0)
                                                        <?php 
                                                        $ratioamount=getSmallUnit($sec->unit_id,$value->itemData->small_unit);
                                                        ?>
                                                            <div class="card mb-2">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <p><strong>القسم:</strong> {{ $sec->store_structure->name }}</p>
                                                                            <p><strong>الكمية:</strong> <span class="available-amount" data-amount="{{ $sec->amount / $ratioamount }}">{{ $sec->amount / $ratioamount }}</span>/{{ $sec->unit->name }}</p>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                {{-- <label>الكمية المرسلة:</label> --}}
                                                                            <p><strong>:</strong>الكمية المرسلة</p>

                                                                                <input type="number" name="sectionAmount[]" class="form-control sectionAmount">
                                                                            </div>
                                                                            <input type="hidden" name="sectionIds[]" value="{{ $sec->section_id }}">
                                                                        </div>
                                                                            <div class="col-md-3">
                                                                            @if ($value->type_id == 1)
                                                                            {{-- <label >الوحدة :</label> --}}
                                                                            <p><strong>:</strong>الوحدة</p>
                                                                            <select name="unit_send[]" id="" class="form-control mesureClass unit">
                    
                                                                                @forelse ($value->itemData->getsmallunit as  $unit )
                                                                                <option value="{{$unit->unit_id}}" data-val={{$unit->value}}>{{$unit->unit->name}}</option>
                                                                                @empty
                                                                                <option value=""></option>
                                                                                @endforelse 
                                                                              
                                                                              
                                                                              
                                                                            </select>
                                                                            @endif
                                                                     
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                            
                                            <div class="form-group">
                                                <input type="text"  name="sendAmount" value="0" class="border-0 text-center sendAmount" id="sendAmountlabel-{{ $value->id }}" readonly>
                                                <button type="submit" class="btn w-75 btn-primary">إرسال </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @empty
                                <div class="alert alert-warning">
                                    لا يوجد طلبات
                                </div>
                            @endforelse
                            
                            </div>
                            
                            

                        </div>
                    </div>
                </div>
            </div>





        </div>




    @endsection
    @section('js')
    <script>
  
       
        document.getElementById("searchBar").addEventListener("input", function() {
            let searchValue = this.value.toLowerCase();
            let cards = document.querySelectorAll(".order-card");
            
            cards.forEach(card => {
                let orderDetails = card.innerText.toLowerCase();
                if (orderDetails.includes(searchValue)) {
                    card.style.display = "block"; // Show card
                } else {
                    card.style.display = "none"; // Hide card
                }
            });
        });

        document.querySelectorAll('.order-card').forEach(function(card) {
        // Get all the sectionAmount fields within the current card
        let sectionAmounts = card.querySelectorAll('.sectionAmount');
        
        // Add an event listener to each sectionAmount input field
        sectionAmounts.forEach(function(inputField) {
            inputField.addEventListener('keyup', function() {
                let totalAmount = 0;
                
                // Loop through all sectionAmount inputs within the current card and calculate the sum
                sectionAmounts.forEach(function(input) {
                    let amount = parseFloat(input.value) || 0; // Default to 0 if the input is empty or invalid
                    totalAmount += amount;
                });

                // Update the sendAmount field with the total amount
                let sendAmountField = card.querySelector('.sendAmount');
                sendAmountField.value = totalAmount;
            });
        });
    });


    $(document).ready(function() {
        $(document).on('change', '.mesureClass', function() {
            var selected_unit = $(this).val();
            $(this).closest('.order-card').find('.mesurment_unit').val(selected_unit);
            check_amount($(this).closest('.card-body').find('.sectionAmount'));
        }); 

        // Add keyup event for sectionAmount inputs
        $(document).on('keyup', '.sectionAmount', function() {
            var card = $(this).closest('.order-card');
            var sectionAmount = parseFloat($(this).val()) || 0;
            var unitSelect = $(this).closest('.card-body').find('.mesureClass option:selected');
            var unitValue = parseFloat(unitSelect.attr('data-val')) || 1;
            var demandRatio = parseFloat(card.find('.demand_ratio').val()) || 1;
            var demand_amount = parseFloat(card.find('.demand_amount').val()) || 1;
            var availableAmount = parseFloat($(this).closest('.card-body').find('.available-amount').data('amount')) * demandRatio || 0;
           var demandAmount =  demand_amount * demandRatio;
            // Convert to base unit
            var convertedAmount = sectionAmount * unitValue;
            
            // Validate against available amount
            if (convertedAmount > availableAmount) {
                
                Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'الكمية المدخلة تتجاوز الكمية المتاحة!',
                });
                $(this).val('');
                card.find('.sendAmount').val(0);
                return false;
            }
            
            // Validate against demand amount
            if (convertedAmount > demandAmount) {
                Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'الكمية المدخلة تتجاوز الكمية المطلوبة',
                });
                $(this).val('');
                card.find('.sendAmount').val(0);
                return false;
            }
            
            // Calculate total amount
            var totalAmount = 0;
            var unitVal=1;
            card.find('.sectionAmount').each(function() {
                var amount = parseFloat($(this).val()) || 0;
                 unitVal = parseFloat($(this).closest('.card-body').find('.mesureClass option:selected').attr('data-val')) || 1;
                totalAmount += amount * unitVal;
            });
            
            // Update total amount field
            card.find('.sendAmount').val(totalAmount/unitVal);
        });
    });

    function check_amount(el){
        var card = $(el).closest('.order-card');
            var sectionAmount = parseFloat($(el).val()) || 0;
            var unitSelect = $(el).closest('.card-body').find('.mesureClass option:selected');
            var unitValue = parseFloat(unitSelect.attr('data-val')) || 1;
            var demandRatio = parseFloat(card.find('.demand_ratio').val()) || 1;
            var demand_amount = parseFloat(card.find('.demand_amount').val()) || 1;
            var availableAmount = parseFloat($(el).closest('.card-body').find('.available-amount').data('amount')) * demandRatio || 0;
           var demandAmount =  demand_amount * demandRatio;
            // Convert to base unit
            var convertedAmount = sectionAmount * unitValue;
            
            // Validate against available amount
            if (convertedAmount > availableAmount) {
                Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'الكمية المدخلة تتجاوز الكمية المتاحة!',
                });

                $(el).val('');
                card.find('.sendAmount').val(0);
                return false;
            }
            
            // Validate against demand amount
            if (convertedAmount > demandAmount) {
                Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'الكمية المدخلة تتجاوز الكمية المطلوبة',
                });
                $(el).val('');
                card.find('.sendAmount').val(0);
                return false;
            }
            
            // Calculate total amount
            var totalAmount = 0;
            var unitVal=1;
            card.find('.sectionAmount').each(function() {
                var amount = parseFloat($(el).val()) || 0;
                 unitVal = parseFloat($(el).closest('.card-body').find('.mesureClass option:selected').attr('data-val')) || 1;
                totalAmount += amount * unitVal;
            });
            
            // Update total amount field
            card.find('.sendAmount').val(totalAmount/unitVal);
        
        return true;
    }
    </script>
  
 

    @endsection
