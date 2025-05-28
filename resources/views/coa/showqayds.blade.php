@extends('layouts.master')
@section('css')
@endsection
@section('title')
    الشجرة المحاسبية
@endsection

@section('content')
    <main role="main" class="main-content ">
        <div class="page-content">
            
            @if ($qayd->type == 'Client' && $qayd->invoice_id != null)
                <h2>Sale Invoice</h2>
                <h3>{{ $qayd->partner->name }}</h3>
                <div class="row">
                    <div class="col-lg-6">
                        <table>
                            <tr class="text-bg-dark">                              
                                <td>Name</td>
                                <td>Source</td>
                                <td>Status</td>
                                <td>Quality</td>
                                <td>Q</td>
                                <td>Price</td>
                                <td>Total</td>
                            </tr>   
                            @foreach ($qayd->invoiceData->invoice_items as $inv_item )
                                <tr>
                                    @if($inv_item->part_type_id)
                                        <td>{{ $inv_item->part->name }}</td>
                                    @else
                                        <td>-</td>
                                    @endif
                                    <td>{{ $inv_item->source->name_arabic }}</td>
                                    <td>{{ $inv_item->status->name }}</td>
                                    <td>{{ $inv_item->part_quality->name }}</td>
                                    <td>{{ $inv_item->amount }}</td>
                                    <td>{{ $inv_item->pricing[0]->price }}</td>
                                    <td>{{ $inv_item->pricing[0]->price * $inv_item->amount }}</td>
                                </tr>        
                            @endforeach
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <table>
                            <tr>
                                <td class="text-bg-dark">Discount</td>
                                <td></td>
                                <td>{{ $qayd->invoiceData->discount }}</td>
                            </tr>
                            <tr>
                                <td  class="text-bg-dark">Total</td>
                                <td></td>
                                <td>{{ $qayd->invoiceData->price_without_tax }}</td>
                            </tr>
                            @if($qayd->invoiceData->taxes)
                                @foreach ( $qayd->invoiceData->taxes as $tax )
                                    <tr>
                                        <td  class="text-bg-dark">{{$tax->name}}</td>
                                        <td>{{$tax->value}} % </td>
                                        <td>{{ $qayd->invoiceData->price_without_tax * $tax->value / 100 }}</td>
                                    </tr>   
                                @endforeach
                            @endif
                            <tr class="text-bg-dark">
                                <td>Net</td>
                                <td></td>
                                <td>{{ $qayd->invoiceData->actual_price - $qayd->invoiceData->discount }}</td>
                            </tr> 
                            <tr>
                                <td class="text-bg-dark">Paied</td>
                                <td></td>
                                <td>{{ $qayd->invoiceData->paied }}</td>
                            </tr>   
                        </table>
                    </div>
                </div>

             @elseif($qayd->type == 'Supplier' && $qayd->invoice_id != null)
                <h2>Purchase Invoice</h2>
                <h3>{{ $qayd->partner->name }}</h3>
                <div class="row">
                    <div class="col-lg-6">
                        <table>
                            <tr class="text-bg-dark">                              
                                <td>Name</td>
                                <td>Source</td>
                                <td>Status</td>
                                <td>Quality</td>
                                <td>Q</td>
                                <td>Price</td>
                                <td>Total</td>
                            </tr>   
                          
                            @foreach ($qayd->invoiceData->replyorders as $inv_item )
                                <tr>
                                    @if($inv_item->part_type_id == 1)
                                        <td>{{ isset($inv_item->part) ? $inv_item->part->name : '' }}</td>
                                    @elseif($inv_item->part_type_id == 2)
                                        <td>{{ $inv_item->wheel->name }}</td>
                                    @elseif($inv_item->part_type_id == 6)
                                    <td>{{ isset($inv_item->kit) ? $inv_item->kit->name : '' }}</td>
                                       
                                    @endif
                                    <td>{{ $inv_item->source->name_arabic }}</td>
                                    <td>{{ $inv_item->status->name }}</td>
                                    <td>{{ $inv_item->part_quality->name }}</td>
                                    <td>{{ $inv_item->amount }}</td>
                                    <td>{{ $inv_item->price }}</td>
                                    <td>{{ $inv_item->price * $inv_item->amount }}</td>
                                </tr>        
                            @endforeach
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <table>
                            <tr>
                                <td class="text-bg-dark">Currency</td>
                                <td></td>
                                <td>{{ $qayd->invoiceData->currency_type->name }}</td>
                                
                            </tr>
                            <tr>
                                <td class="text-bg-dark">Discount</td>
                                <td></td>
                                {{-- <td>{{ $qayd->invoiceData->discount }}</td> --}}
                                <td>0</td>
                            </tr>
                            <tr>
                                <td  class="text-bg-dark">Total</td>
                                <td></td>
                                <td>{{ $qayd->invoiceData->pricebeforeTax }}</td>
                            </tr>
                            <tr>
                                <td  class="text-bg-dark">External Tax</td>
                                <td></td>
                                <td>{{ $qayd->invoiceData->tax }}</td>
                            </tr>
                            <tr>
                                <td  class="text-bg-dark">14% VAT</td>
                                <td></td>
                                <td>{{ $qayd->invoiceData->taxInvolved_flag == 1 ? 'YES' : 'NO' }}</td>
                            </tr>
                            <tr>
                                <td  class="text-bg-dark">-1% VAT</td>
                                <td></td>
                                <td>{{ $qayd->invoiceData->taxkasmInvolved_flag ==1 ? 'YES' : 'NO' }}</td>
                            </tr>
                             <tr>
                                <td  class="text-bg-dark">CUSTOM</td>
                                <td></td>
                                <td>{{ $qayd->invoiceData->coast }}</td>
                            </tr>
                            {{-- @if($qayd->invoiceData->taxes)
                                @foreach ( $qayd->invoiceData->taxes as $tax )
                                    <tr>
                                        <td  class="text-bg-dark">{{$tax->name}}</td>
                                        <td>{{$tax->value}} % </td>
                                        <td>{{ $qayd->invoiceData->price_without_tax * $tax->value / 100 }}</td>
                                    </tr>   
                                @endforeach
                            @endif --}}
                            <tr class="text-bg-dark">
                                <td>Net</td>
                                <td></td>
                                <td>{{ $qayd->invoiceData->total_price }}</td>
                            </tr> 
                            <tr>
                                <td class="text-bg-dark">Paied</td>
                                <td></td>
                                <td>{{ $qayd->invoiceData->paied }}</td>
                            </tr>   
                        </table>
                    </div>
                </div>
            @else

            @endif

            <button class="btn" onclick="toggleView()">Group Journal</button>
            <div class="row mt-2 view1">
                <div class="col-lg-12">
                    @if ($qayd->newqayds)
                        <table>
                            <thead class="text-bg-dark text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Reference</th>
                                    <th>Account</th>
                                    <th>Journal</th>
                                    <th>Partner</th>
                                    <th>Label</th>
                                    <th>Cost Center</th>
                                    <th>Amount in Currncy</th>
                                    <th>Currency</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th></th>
                                </tr>
                            </thead>
                            @foreach ($qayd->newqayds as $qayd)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $qayd->refrence }}</td>
                                    <td>{{ $qayd->coa->name_ar }} - {{ $qayd->coa->ac_number }}</td>
                                    <td>{{ $qayd->journal->name }}</td>
                                    <td>{{ $qayd->partner_id }}</td>
                                    <td>{{ $qayd->label }}</td>
                                    <td>{{ $qayd->cost_center }}</td>
                                    <td>{{ $qayd->amount_currency }}</td>
                                    <td>{{ isset($qayd->currency_type) ? $qayd->currency_type->name : '-' }}</td>
                                    <td>{{ $qayd->debit }}</td>
                                    <td>{{ $qayd->credit }}</td>
                                    <td></td>
                                    
                                </tr>
                            @endforeach
                        </table>
                    @endif
                </div>
            </div>

            <div class="row mt-2 view2" style="display: none">
                <div class="col-lg-12">
                    
                            @foreach($groupedNewQaydss as $journalId => $groupedItems)
                                " Journal ID : " {{ $journalId }}
                                <table> 
                                    <thead class="text-bg-dark text-center">
                                        <tr>
                                            <th>#</th>
                                            <th>Reference</th>
                                            <th>Account</th>
                                            <th>Journal</th>
                                            <th>Partner</th>
                                            <th>Label</th>
                                            <th>Cost Center</th>
                                            <th>Amount in Currncy</th>
                                            <th>Currency</th>
                                            <th>Debit</th>
                                            <th>Credit</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                @foreach ($groupedItems as $qayd) 
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $qayd->refrence }}</td>
                                        <td>{{ $qayd->coa->name_ar }} - {{ $qayd->coa->ac_number }}</td>
                                        <td>{{ $qayd->journal->name }}</td>
                                        <td>{{ $qayd->partner_id }}</td>
                                        <td>{{ $qayd->label }}</td>
                                        <td>{{ $qayd->cost_center }}</td>
                                        <td>{{ $qayd->amount_currency }}</td>
                                        <td>{{ isset($qayd->currency_type) ? $qayd->currency_type->name : '-' }}</td>
                                        <td>{{ $qayd->debit }}</td>
                                        <td>{{ $qayd->credit }}</td>
                                        <td></td>
                                        
                                    </tr>
                                @endforeach
                                </table>
                            @endforeach
                    
                </div>
            </div>
        </div>
    </main>

   
    
@endsection




@section('js')
    <script>
    function toggleView(){
        $(".view1").toggle();
        $(".view2").toggle();
    }        
        
    </script>
@endsection
