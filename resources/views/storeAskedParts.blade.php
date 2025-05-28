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
                                                    <p><strong>الكمية المطلوبة :</strong> {{ $value->amount }}</p>
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
                                                <input type="hidden" name="demand_amount" value="{{ $value->amount }}">
                            
                                                <div class="mt-3">
                                                    <hr>
                                                    <h2> المتاح </h2>
                                                    @foreach ($value->storeSection as $sec)
                                                        @if ($sec->amount > 0)
                                                            
                                                            <div class="card mb-2">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <p><strong>القسم:</strong> {{ $sec->store_structure->name }}</p>
                                                                            <p><strong>الكمية:</strong> {{ $sec->amount }}</p>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>الكمية المرسلة:</label>
                                                                                <input type="number" name="sectionAmount[]" class="form-control sectionAmount">
                                                                            </div>
                                                                            <input type="hidden" name="sectionIds[]" value="{{ $sec->section_id }}">
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
    </script>

    @endsection
