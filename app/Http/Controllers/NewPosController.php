<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Notifications\NotifyUser;
use App\Models\AllClark;
use App\Models\RefundInvoice;
use App\Models\AllEquip;
use App\Models\AllKit;
use App\Models\Kit;
use App\Models\AllPart;
use App\Models\StoresInvoicesLog;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoicesTax;
use App\Models\InvoiceItemsOrderSupplier;
use Illuminate\Database\Eloquent\Collection;

use App\Models\InvoiceItemsSection;
use App\Http\Controllers\QaydController;
use App\Models\Store;
use App\Models\StoreSection;
use App\Models\Tax;
use App\Models\AllTractor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\AllWheel;
use App\Models\BankSafeMoney;
use App\Models\BankType;
use App\Models\DemandPart;
use App\Models\MoneySafe;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Facades\Auth;

use App\Models\CurrencyType;

use App\Models\Qayditem;
use App\Models\Newqayd;
use App\Models\NQayd;
use App\Models\Part;
use App\Models\StorelogSection;
use App\Models\StoresLog;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;


class NewPosController extends Controller
{
    public function printpos(Request $request)
    {
        // return $request;

        DB::beginTransaction();
         $logMessage='';
        $logUser = Auth::user()->id;
        try {


            $cli = Client::where('id', $request->client)->get();
            // return $cli;
            if (count($cli) > 0) {
                $cli_id = $cli[0]->id;
            } else {
                $client = new Client();
                $client->name = 'عميل جديد';
                $client->tel01 = $request->client;
                $client->save();
                $cli_id = $client->id;
            }

            $invoice = new Invoice();

            $invoice->name = Carbon::now();
            $invoice->casher_id = Auth::user()->id;
            $invoice->discount = floor($request->invDiscount);
            $invoice->actual_price = floor($request->total);
            $invoice->client_id = $cli_id;
            $invoice->company_id = '10';
            $invoice->store_id = $request->storeId;
            $invoice->price_without_tax = floor($request->subtotal);
            $invoice->tax_amount = floor($request->taxval);
            $invoice->paied = floor($request->invPaied);
            $invoice->date = Carbon::now();
            $invoice->save();
            
            
            $nqayd = new NQayd();
            $nqayd->partner_id=$cli_id;
            $nqayd->type='Client';
            $nqayd->name='Sales/'.date('Y').'/'.date('m');
            $nqayd->cost_center='';
            $nqayd->amount_currency=null;
            $nqayd->currency_id=null;
            $nqayd->credit= 0;
            $nqayd->debit=0;
            $nqayd->desc='فاتورة بيع رقم '.$invoice->id.' . ';
            $nqayd->user_id=Auth::user()->id;
            $nqayd->flag='0';
            $nqayd->invoice_id=$invoice->id;
            $nqayd->invoice_table='printInvoice';
            $nqayd->save();


            
            $logMessage.='تم اضافة فاتورة بيع رقم '.$invoice->id.'<br/>';
            /// invoice Tax /////////////////
            if (isset($request->taxes)) {
                for ($i = 0; $i < count($request->taxes); $i++) {
                    $invTax = new InvoicesTax();
                    $invTax->invoice_id = $invoice->id;
                    $tax = Tax::where('value', $request->taxes[$i])->get();
                    $invTax->tax_id = $tax[0]->id;
                    $invTax->save();
                  $logMessage.=' فاتورة بيع رقم '.$invoice->id.'/ الضريبة - '.$tax[0]->name.'<br/>';
                }
            }

            //////////////////// Only Part//////////////////////////
            $inv_items = $request->items_part;
            $total_item_buy_price = 0;
            for ($i = 0; $i < count($inv_items); $i++) {
                $item = explode('-', $inv_items[$i]);
                $part_id = $item[0];
                $source_id = $item[1];
                $status_id = $item[2];
                $quality_id = $item[3];
                $type_id = $item[4];
                $amount = $request->itemAmount[$i];
                $samllmeasureUnits = $request->samllmeasureUnits[$i];
                $measureUnit = $request->measureUnit[$i];

                 $ratiounit = getSmallUnit($measureUnit, $samllmeasureUnits);
                
                $invoiceItems = new InvoiceItem();
                $invoiceItems->date = Carbon::now();

                $invoiceItems->part_id = $part_id;
                $invoiceItems->amount = $amount * $ratiounit;
                $invoiceItems->source_id = $source_id;
                $invoiceItems->status_id = $status_id;
                $invoiceItems->quality_id = $quality_id;
                $invoiceItems->part_type_id = $type_id;
                $invoiceItems->invoice_id = $invoice->id;
                $invoiceItems->sale_type = $request->pricetype[$i];
                $invoiceItems->unit_id = $request->measureUnit[$i];
                $invoiceItems->amount_unit =  $amount; //zyada
                $invoiceItems->save();
                $logMessage.=' فاتورة بيع رقم '.$invoice->id.'/ الصنف FN'.$type_id.$part_id.$source_id.$status_id.$quality_id.' الكمية '.$amount.'<br/>';
                $amount = $amount * $ratiounit;

                //// remove from store ///////////////////
                if ($type_id == 1) {

                    $allparts = AllPart::where('remain_amount', '>', 0)
                    ->where('part_id', $part_id)
                    ->where('source_id', $source_id)
                    ->where('status_id', $status_id)
                    ->where('quality_id', $quality_id)
                    ->orderBy('id', 'ASC')->with('order_supplier')->get();
                    $all_part_table = 'all_parts';

                    ////// remove from Stores ////////
                    $requestAmount0 = $amount;
                    foreach ($allparts as $key => $element) {

                        if ($element->remain_amount >= $requestAmount0) {

                            $Ac_currency_id = $element->order_supplier->currency_id;
                            $Ac_currency_date = $element->order_supplier->confirmation_date;
                            $Ac_all_currency_types = CurrencyType::with([
                                'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                    return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                                },
                            ])->where('id', $Ac_currency_id)
                                ->get();

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);

                            DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $requestAmount0);
                            InvoiceItemsOrderSupplier::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'order_supplier_id' => $element->order_supplier_id,
                                'amount' =>  $requestAmount0
                            ]);
                            
                            $logMessage.=' فاتورة بيع رقم '.$invoice->id.'/ '.$all_part_table.
                            'تم خصم '.$requestAmount0.'من ID'. $element->id.
                            '<br/>';
                            
                            $newqayd = new Newqayd();
                            $newqayd->refrence='Stock/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                            $newqayd->coa_id=225;
                            $newqayd->journal_id=8;
                            $newqayd->partner_id=$cli_id;
                            $newqayd->type='Client';
                            $newqayd->label='Stock/OUT/'.date('Y').'/'.date('m').'/005-'.'FN1'.$part_id.$source_id.$status_id.$quality_id.'/'.$amount;
                            $newqayd->cost_center='';
                            $newqayd->amount_currency=null;
                            $newqayd->currency_id=null;
                            $newqayd->debit= $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            $newqayd->credit='0';
                            $newqayd->desc='';
                            $newqayd->user_id=Auth::user()->id;
                            $newqayd->flag='0';
                            $newqayd->invoice_id=$invoice->id;
                            $newqayd->invoice_table='printInvoice';
                            $newqayd->qayds_id=$nqayd->id;
                            $newqayd->show_no='1';
                            $newqayd->save();
    
                            $newqayd = new Newqayd();
                            $newqayd->refrence='Stock/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                            $newqayd->coa_id=223;
                            $newqayd->journal_id=8;
                            $newqayd->partner_id=$cli_id;
                            $newqayd->type='Client';
                            $newqayd->label='Stock/OUT/'.date('Y').'/'.date('m').'/005-'.'FN1'.$part_id.$source_id.$status_id.$quality_id.'/'.$amount;
                            $newqayd->cost_center='';
                            $newqayd->amount_currency=null;
                            $newqayd->currency_id=null;
                            $newqayd->credit= $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            $newqayd->debit='0';
                            $newqayd->desc='';
                            $newqayd->user_id=Auth::user()->id;
                            $newqayd->flag='0';
                            $newqayd->invoice_id=$invoice->id;
                            $newqayd->invoice_table='printInvoice';
                            $newqayd->qayds_id=$nqayd->id;
                            $newqayd->show_no='1';
                            $newqayd->save();
    
                            break;


                        } elseif ($element->remain_amount < $requestAmount0) {

                            $Ac_currency_id = $element->order_supplier->currency_id;
                            $Ac_currency_date = $element->order_supplier->confirmation_date;
                            $Ac_all_currency_types = CurrencyType::with([
                                'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                    return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                                },
                            ])->where('id', $Ac_currency_id)
                                ->get();

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            DB::table($all_part_table)->where('id', $element->id)
                            ->decrement('remain_amount', $element->remain_amount);
                            InvoiceItemsOrderSupplier::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'order_supplier_id' => $element->order_supplier_id,
                                'amount' =>  $element->remain_amount
                            ]);
                            $requestAmount0 = $requestAmount0 - $element->remain_amount;
                            $logMessage.=' فاتورة بيع رقم '.$invoice->id.'/ '.$all_part_table.
                            'تم خصم '.$element->remain_amount.'من ID'. $element->id.
                            '<br/>';

                            $newqayd = new Newqayd();
                            $newqayd->refrence='Stock/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                            $newqayd->coa_id=225;
                            $newqayd->journal_id=8;
                            $newqayd->partner_id=$cli_id;
                            $newqayd->type='Client';
                            $newqayd->label='Stock/OUT/'.date('Y').'/'.date('m').'/005-'.'FN1'.$part_id.$source_id.$status_id.$quality_id.'/'.$element->remain_amount;
                            $newqayd->cost_center='';
                            $newqayd->amount_currency=null;
                            $newqayd->currency_id=null;
                            $newqayd->debit= $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            $newqayd->credit='0';
                            $newqayd->desc='';
                            $newqayd->user_id=Auth::user()->id;
                            $newqayd->flag='0';
                            $newqayd->invoice_id=$invoice->id;
                            $newqayd->invoice_table='printInvoice';
                            $newqayd->qayds_id=$nqayd->id;
                            $newqayd->show_no='1';
                            $newqayd->save();
    
                            $newqayd = new Newqayd();
                            $newqayd->refrence='Stock/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                            $newqayd->coa_id=223;
                            $newqayd->journal_id=8;
                            $newqayd->partner_id=$cli_id;
                            $newqayd->type='Client';
                            $newqayd->label='Stock/OUT/'.date('Y').'/'.date('m').'/005-'.'FN1'.$part_id.$source_id.$status_id.$quality_id.'/'.$element->remain_amount;
                            $newqayd->cost_center='';
                            $newqayd->amount_currency=null;
                            $newqayd->currency_id=null;
                            $newqayd->credit= $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            $newqayd->debit='0';
                            $newqayd->desc='';
                            $newqayd->user_id=Auth::user()->id;
                            $newqayd->flag='0';
                            $newqayd->invoice_id=$invoice->id;
                            $newqayd->invoice_table='printInvoice';
                            $newqayd->qayds_id=$nqayd->id;
                            $newqayd->show_no='1';
                            $newqayd->save();
    
                        } else if ($requestAmount0 <= 0) {
                            break;
                        }
                    }

                    $store = Store::where('id', $request->storeId)->get();
                    $store_id = $store[0]->id;
                    $store_name = $store[0]->name;
                    $store_table_name = $store[0]->table_name;


                    $requestAmount1 = $amount;
                    $allstoresRes = DB::table($store_table_name)
                    ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join($all_part_table, function ($join) use ($store_table_name, $all_part_table) {
                        $join->on($store_table_name . '.supplier_order_id', '=', $all_part_table . '.order_supplier_id')
                            ->on('stores_log.All_part_id', '=', $all_part_table . '.id');
                    })
                    ->where('stores_log.store_id', $store_id)
                    ->where($all_part_table . '.source_id', $source_id)
                    ->where($all_part_table . '.status_id', $status_id)
                    ->where($all_part_table . '.quality_id', $quality_id)
                    ->where($store_table_name . '.part_id', $part_id)
                    // ->where($store_table_name . '.supplier_order_id', $element->order_supplier_id)
                    ->where($store_table_name . '.type_id', 1)
                    // ->get();
                    ->select($store_table_name.'.*','stores_log.All_part_id','stores_log.store_id',
                    $all_part_table.'.buy_costing',
                    $all_part_table.'.source_id',
                    $all_part_table.'.status_id',
                    $all_part_table.'.quality_id',
                    $all_part_table.'.buy_price')
                    ->get();


                    $old_amount=$allstoresRes->sum('amount');


                    foreach ($allstoresRes as $key => $element) {
                        if ($element->amount >= $requestAmount1) {
                            
                            
                            DB::table($store_table_name)
                            ->where('part_id', $element->part_id)
                            ->where('supplier_order_id', $element->supplier_order_id)
                            ->where('type_id', $element->type_id)
                            ->where('store_log_id', $element->store_log_id)
                            ->decrement('amount', $requestAmount1);
                            StoresInvoicesLog::create([
                                'store_id' => $element->store_id,
                                'store_table_id' => $element->store_log_id,
                                'part_id' => $element->part_id,
                                'amount' =>  $requestAmount1,
                                // 'old_amount' =>  $element->amount,
                                'old_amount' =>  $old_amount,
                                'supplier_order_id' => $element->supplier_order_id,
                                'type_id' => $element->type_id,
                                'All_part_id' => $element->All_part_id,
                                'user_id' => Auth::user()->id,
                                'source_id' => $element->source_id,
                                'status_id' => $element->status_id,
                                'quality_id' => $element->quality_id,
                                'buy_price' => $element->buy_price,
                                'buy_costing' => $element->buy_costing,
                                'invoice_id' => $invoice->id
                                // 'allpartId' => $element->id
                            ]);
                            
                             $logMessage.=' فاتورة بيع رقم '.$invoice->id.'/ المخزن - '.$element->store_id.
                            'تم خصم '.$requestAmount1.'من '. $element->part_id.
                            '<br/>';
                            break;
                        } elseif ($element->amount < $requestAmount1) {
                            DB::table($store_table_name)
                            ->where('part_id', $element->part_id)
                            ->where('supplier_order_id', $element->supplier_order_id)
                            ->where('type_id', $element->type_id)
                            ->where('store_log_id', $element->store_log_id)->decrement('amount', $element->amount);
                            StoresInvoicesLog::create([
                                'store_id' => $element->store_id,
                                'store_table_id' => $element->store_log_id,
                                'part_id' => $element->part_id,
                                'amount' =>  $element->amount,
                                // 'old_amount' =>  $element->amount,
                                'old_amount' =>  $old_amount,
                                'supplier_order_id' => $element->supplier_order_id,
                                'type_id' => $element->type_id,
                                'All_part_id' => $element->All_part_id,
                                'user_id' => Auth::user()->id,
                                'source_id' => $element->source_id,
                                'status_id' => $element->status_id,
                                'quality_id' => $element->quality_id,
                                'buy_price' => $element->buy_price,
                                'buy_costing' => $element->buy_costing,
                                'invoice_id' => $invoice->id
                            ]);
                             $logMessage.=' فاتورة بيع رقم '.$invoice->id.'/ المخزن - '.$element->store_id.
                            'تم خصم '.$element->amount.'من '. $element->part_id.
                            '<br/>';
                            $requestAmount1 = $requestAmount1 - $element->amount;
                            $old_amount = $old_amount - $element->amount;
                        } else if ($requestAmount1 <= 0) {
                            break;
                        }
                    }


                    ////// remove from Sections ////////
                    $allsecc = DB::table('store_section')
                        ->where('part_id', $part_id)
                        // ->where('supplier_order_id', $allparts[0]->order_supplier_id)
                        ->where('type_id', 1)
                        ->where('source_id', $source_id)
                        ->where('status_id', $status_id)
                        ->where('quality_id', $quality_id)
                        ->where('store_id', $request->storeId)
                        ->where('amount', '>',0)
                        ->get();

                    $requestSecAmount = $amount;
                    foreach ($allsecc as $key => $element) {

                        if ($element->amount >= $requestSecAmount) {
                            // decrement amount
                            StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $requestSecAmount
                            ]);
                            $logMessage.=' فاتورة بيع رقم '.$invoice->id.'/ المخزن - '.$request->storeId.
                            'تم خصم '.$requestSecAmount.'من القسم'. $element->section_id.
                            '<br/>';

                            break;
                        } elseif ($element->amount < $requestSecAmount) {
                            // decrement remain_amount
                            StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                            $requestSecAmount = $requestSecAmount - $element->amount;
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $element->amount
                            ]);
                            $logMessage.=' فاتورة بيع رقم '.$invoice->id.'/ المخزن - '.$request->storeId.
                            'تم خصم '.$element->amount.'من القسم'. $element->section_id.
                            '<br/>';
                        } else if ($requestSecAmount <= 0) {
                            break;
                        }
                    }


                } elseif ($type_id == 2) {
                    //wheel
                    $allparts = AllWheel::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->with('order_supplier')->get();

                    $all_part_table = 'all_wheels';
                    $requestAmount0 = $amount;
                    foreach ($allparts as $key => $element) {

                        if ($element->remain_amount >= $requestAmount0) {

                            $Ac_currency_id = $element->order_supplier->currency_id;
                            $Ac_currency_date = $element->order_supplier->confirmation_date;
                            $Ac_all_currency_types = CurrencyType::with([
                                'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                    return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                                },
                            ])->where('id', $Ac_currency_id)
                                ->get();

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);

                            DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $requestAmount0);
                            InvoiceItemsOrderSupplier::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'order_supplier_id' => $element->order_supplier_id,
                                'amount' =>  $requestAmount0
                            ]);
                            break;


                        } elseif ($element->remain_amount < $requestAmount0) {

                            $Ac_currency_id = $element->order_supplier->currency_id;
                            $Ac_currency_date = $element->order_supplier->confirmation_date;
                            $Ac_all_currency_types = CurrencyType::with([
                                'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                    return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                                },
                            ])->where('id', $Ac_currency_id)
                                ->get();

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            DB::table($all_part_table)->where('id', $element->id)
                            ->decrement('remain_amount', $element->remain_amount);
                            InvoiceItemsOrderSupplier::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'order_supplier_id' => $element->order_supplier_id,
                                'amount' =>  $element->remain_amount
                            ]);
                            $requestAmount0 = $requestAmount0 - $element->remain_amount;


                        } else if ($requestAmount0 <= 0) {
                            break;
                        }
                    }

                    $store = Store::where('id', $request->storeId)->get();
                    $store_id = $store[0]->id;
                    $store_name = $store[0]->name;
                    $store_table_name = $store[0]->table_name;


                    $requestAmount1 = $amount;
                    $allstoresRes = DB::table($store_table_name)
                    ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join($all_part_table, function ($join) use ($store_table_name, $all_part_table) {
                        $join->on($store_table_name . '.supplier_order_id', '=', $all_part_table . '.order_supplier_id')
                            ->on('stores_log.All_part_id', '=', $all_part_table . '.id');
                    })
                    ->where('stores_log.store_id', $store_id)
                    ->where($all_part_table . '.source_id', $source_id)
                    ->where($all_part_table . '.status_id', $status_id)
                    ->where($all_part_table . '.quality_id', $quality_id)
                    ->where($store_table_name . '.part_id', $part_id)
                    // ->where($store_table_name . '.supplier_order_id', $element->order_supplier_id)
                    ->where($store_table_name . '.type_id', 2)
                    ->select($store_table_name.'.*','stores_log.All_part_id','stores_log.store_id',
                    $all_part_table.'.buy_costing',
                    $all_part_table.'.source_id',
                    $all_part_table.'.status_id',
                    $all_part_table.'.quality_id',
                    $all_part_table.'.buy_price')
                    ->get();





                    foreach ($allstoresRes as $key => $element) {
                        if ($element->amount >= $requestAmount1) {
                            
                            DB::table($store_table_name)
                            ->where('part_id', $element->part_id)
                            ->where('supplier_order_id', $element->supplier_order_id)
                            ->where('type_id', $element->type_id)
                            ->where('store_log_id', $element->store_log_id)->decrement('amount', $requestAmount1);
                            break;
                        } elseif ($element->amount < $requestAmount1) {
                            DB::table($store_table_name)
                            ->where('part_id', $element->part_id)
                            ->where('supplier_order_id', $element->supplier_order_id)
                            ->where('type_id', $element->type_id)
                            ->where('store_log_id', $element->store_log_id)->decrement('amount', $element->amount);
                            $requestAmount1 = $requestAmount1 - $element->amount;
                        } else if ($requestAmount1 <= 0) {
                            break;
                        }
                    }


                    ////// remove from Sections ////////
                    $allsecc = DB::table('store_section')
                        ->where('part_id', $part_id)
                        // ->where('supplier_order_id', $allparts[0]->order_supplier_id)
                        ->where('type_id', 2)
                        ->where('source_id', $source_id)
                        ->where('status_id', $status_id)
                        ->where('quality_id', $quality_id)
                        ->where('store_id', $request->storeId)
                        ->where('amount', '>',0)
                        ->get();

                    $requestSecAmount = $amount;
                    foreach ($allsecc as $key => $element) {

                        if ($element->amount >= $requestSecAmount) {
                            // decrement amount
                            StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $requestSecAmount
                            ]);

                            break;
                        } elseif ($element->amount < $requestSecAmount) {
                            // decrement remain_amount
                            StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                            $requestSecAmount = $requestSecAmount - $element->amount;
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $element->amount
                            ]);
                        } else if ($requestSecAmount <= 0) {
                            break;
                        }
                    }

                } elseif ($type_id == 6) {
                    $allparts = AllKit::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->with('order_supplier')->get();

                    $all_part_table = 'all_kits';
                    ////// remove from Stores ////////
                    $requestAmount0 = $amount;
                    foreach ($allparts as $key => $element) {

                        if ($element->remain_amount >= $requestAmount0) {

                            $Ac_currency_id = $element->order_supplier->currency_id;
                            $Ac_currency_date = $element->order_supplier->confirmation_date;
                            $Ac_all_currency_types = CurrencyType::with([
                                'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                    return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                                },
                            ])->where('id', $Ac_currency_id)
                                ->get();

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);

                            DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $requestAmount0);
                            InvoiceItemsOrderSupplier::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'order_supplier_id' => $element->order_supplier_id,
                                'amount' =>  $requestAmount0
                            ]);
                            break;


                        } elseif ($element->remain_amount < $requestAmount0) {

                            $Ac_currency_id = $element->order_supplier->currency_id;
                            $Ac_currency_date = $element->order_supplier->confirmation_date;
                            $Ac_all_currency_types = CurrencyType::with([
                                'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                    return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                                },
                            ])->where('id', $Ac_currency_id)
                                ->get();

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            DB::table($all_part_table)->where('id', $element->id)
                            ->decrement('remain_amount', $element->remain_amount);
                            InvoiceItemsOrderSupplier::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'order_supplier_id' => $element->order_supplier_id,
                                'amount' =>  $element->remain_amount
                            ]);
                            $requestAmount0 = $requestAmount0 - $element->remain_amount;


                        } else if ($requestAmount0 <= 0) {
                            break;
                        }
                    }

                    $store = Store::where('id', $request->storeId)->get();
                    $store_id = $store[0]->id;
                    $store_name = $store[0]->name;
                    $store_table_name = $store[0]->table_name;


                    $requestAmount1 = $amount;
                    $allstoresRes = DB::table($store_table_name)
                    ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join($all_part_table, function ($join) use ($store_table_name, $all_part_table) {
                        $join->on($store_table_name . '.supplier_order_id', '=', $all_part_table . '.order_supplier_id')
                            ->on('stores_log.All_part_id', '=', $all_part_table . '.id');
                    })
                    ->where('stores_log.store_id', $store_id)
                    ->where($all_part_table . '.source_id', $source_id)
                    ->where($all_part_table . '.status_id', $status_id)
                    ->where($all_part_table . '.quality_id', $quality_id)
                    ->where($store_table_name . '.part_id', $part_id)
                    // ->where($store_table_name . '.supplier_order_id', $element->order_supplier_id)
                    ->where($store_table_name . '.type_id', 6)
                    ->select($store_table_name.'.*','stores_log.All_part_id','stores_log.store_id',
                    $all_part_table.'.buy_costing',
                    $all_part_table.'.source_id',
                    $all_part_table.'.status_id',
                    $all_part_table.'.quality_id',
                    $all_part_table.'.buy_price')
                    ->get();





                    foreach ($allstoresRes as $key => $element) {
                        if ($element->amount >= $requestAmount1) {
                            DB::table($store_table_name)
                            ->where('part_id', $element->part_id)
                            ->where('supplier_order_id', $element->supplier_order_id)
                            ->where('type_id', $element->type_id)
                            ->where('store_log_id', $element->store_log_id)->decrement('amount', $requestAmount1);
                            break;
                        } elseif ($element->amount < $requestAmount1) {
                            DB::table($store_table_name)
                            ->where('part_id', $element->part_id)
                            ->where('supplier_order_id', $element->supplier_order_id)
                            ->where('type_id', $element->type_id)
                            ->where('store_log_id', $element->store_log_id)->decrement('amount', $element->amount);
                            $requestAmount1 = $requestAmount1 - $element->amount;
                        } else if ($requestAmount1 <= 0) {
                            break;
                        }
                    }


                    ////// remove from Sections ////////
                    $allsecc = DB::table('store_section')
                        ->where('part_id', $part_id)
                        // ->where('supplier_order_id', $allparts[0]->order_supplier_id)
                        ->where('type_id', 6)
                        ->where('source_id', $source_id)
                        ->where('status_id', $status_id)
                        ->where('quality_id', $quality_id)
                        ->where('store_id', $request->storeId)
                        ->where('amount', '>',0)
                        ->get();

                    $requestSecAmount = $amount;
                    foreach ($allsecc as $key => $element) {

                        if ($element->amount >= $requestSecAmount) {
                            // decrement amount
                            StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $requestSecAmount
                            ]);

                            break;
                        } elseif ($element->amount < $requestSecAmount) {
                            // decrement remain_amount
                            StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                            $requestSecAmount = $requestSecAmount - $element->amount;
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $element->amount
                            ]);
                        } else if ($requestSecAmount <= 0) {
                            break;
                        }
                    }

                } elseif ($type_id == 3) {
                    //tractor
                    $allparts = AllTractor::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->with('order_supplier')->get();

                    $all_part_table = 'all_tractors';

                    ////// remove from Stores ////////
                    $requestAmount0 = $amount;
                    foreach ($allparts as $key => $element) {

                        if ($element->remain_amount >= $requestAmount0) {

                            $Ac_currency_id = $element->order_supplier->currency_id;
                            $Ac_currency_date = $element->order_supplier->confirmation_date;
                            $Ac_all_currency_types = CurrencyType::with([
                                'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                    return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                                },
                            ])->where('id', $Ac_currency_id)
                                ->get();

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);

                            DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $requestAmount0);
                            InvoiceItemsOrderSupplier::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'order_supplier_id' => $element->order_supplier_id,
                                'amount' =>  $requestAmount0
                            ]);
                            break;


                        } elseif ($element->remain_amount < $requestAmount0) {

                            $Ac_currency_id = $element->order_supplier->currency_id;
                            $Ac_currency_date = $element->order_supplier->confirmation_date;
                            $Ac_all_currency_types = CurrencyType::with([
                                'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                    return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                                },
                            ])->where('id', $Ac_currency_id)
                                ->get();

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            DB::table($all_part_table)->where('id', $element->id)
                            ->decrement('remain_amount', $element->remain_amount);
                            InvoiceItemsOrderSupplier::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'order_supplier_id' => $element->order_supplier_id,
                                'amount' =>  $element->remain_amount
                            ]);
                            $requestAmount0 = $requestAmount0 - $element->remain_amount;


                        } else if ($requestAmount0 <= 0) {
                            break;
                        }
                    }

                    $store = Store::where('id', $request->storeId)->get();
                    $store_id = $store[0]->id;
                    $store_name = $store[0]->name;
                    $store_table_name = $store[0]->table_name;


                    $requestAmount1 = $amount;
                    $allstoresRes = DB::table($store_table_name)
                    ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join($all_part_table, function ($join) use ($store_table_name, $all_part_table) {
                        $join->on($store_table_name . '.supplier_order_id', '=', $all_part_table . '.order_supplier_id')
                            ->on('stores_log.All_part_id', '=', $all_part_table . '.id');
                    })
                    ->where('stores_log.store_id', $store_id)
                    ->where($all_part_table . '.source_id', $source_id)
                    ->where($all_part_table . '.status_id', $status_id)
                    ->where($all_part_table . '.quality_id', $quality_id)
                    ->where($store_table_name . '.part_id', $part_id)
                    // ->where($store_table_name . '.supplier_order_id', $element->order_supplier_id)
                    ->where($store_table_name . '.type_id', 3)
                    ->select($store_table_name.'.*','stores_log.All_part_id','stores_log.store_id',
                    $all_part_table.'.buy_costing',
                    $all_part_table.'.source_id',
                    $all_part_table.'.status_id',
                    $all_part_table.'.quality_id',
                    $all_part_table.'.buy_price')
                    ->get();





                    foreach ($allstoresRes as $key => $element) {
                        if ($element->amount >= $requestAmount1) {
                            DB::table($store_table_name)
                            ->where('part_id', $element->part_id)
                            ->where('supplier_order_id', $element->supplier_order_id)
                            ->where('type_id', $element->type_id)
                            ->where('store_log_id', $element->store_log_id)->decrement('amount', $requestAmount1);
                            break;
                        } elseif ($element->amount < $requestAmount1) {
                            DB::table($store_table_name)
                            ->where('part_id', $element->part_id)
                            ->where('supplier_order_id', $element->supplier_order_id)
                            ->where('type_id', $element->type_id)
                            ->where('store_log_id', $element->store_log_id)->decrement('amount', $element->amount);
                            $requestAmount1 = $requestAmount1 - $element->amount;
                        } else if ($requestAmount1 <= 0) {
                            break;
                        }
                    }


                    ////// remove from Sections ////////
                    $allsecc = DB::table('store_section')
                        ->where('part_id', $part_id)
                        // ->where('supplier_order_id', $allparts[0]->order_supplier_id)
                        ->where('type_id', 3)
                        ->where('source_id', $source_id)
                        ->where('status_id', $status_id)
                        ->where('quality_id', $quality_id)
                        ->where('store_id', $request->storeId)
                        ->where('amount', '>',0)
                        ->get();

                    $requestSecAmount = $amount;
                    foreach ($allsecc as $key => $element) {

                        if ($element->amount >= $requestSecAmount) {
                            // decrement amount
                            StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $requestSecAmount
                            ]);

                            break;
                        } elseif ($element->amount < $requestSecAmount) {
                            // decrement remain_amount
                            StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                            $requestSecAmount = $requestSecAmount - $element->amount;
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $element->amount
                            ]);
                        } else if ($requestSecAmount <= 0) {
                            break;
                        }
                    }

                } elseif ($type_id == 4) {
                    //clark
                    $allparts = AllClark::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->with('order_supplier')->get();

                    $all_part_table = 'all_clarks';

                    $requestAmount0 = $amount;
                    foreach ($allparts as $key => $element) {

                        if ($element->remain_amount >= $requestAmount0) {

                            $Ac_currency_id = $element->order_supplier->currency_id;
                            $Ac_currency_date = $element->order_supplier->confirmation_date;
                            $Ac_all_currency_types = CurrencyType::with([
                                'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                    return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                                },
                            ])->where('id', $Ac_currency_id)
                                ->get();

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);

                            DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $requestAmount0);
                            InvoiceItemsOrderSupplier::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'order_supplier_id' => $element->order_supplier_id,
                                'amount' =>  $requestAmount0
                            ]);
                            break;


                        } elseif ($element->remain_amount < $requestAmount0) {

                            $Ac_currency_id = $element->order_supplier->currency_id;
                            $Ac_currency_date = $element->order_supplier->confirmation_date;
                            $Ac_all_currency_types = CurrencyType::with([
                                'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                    return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                                },
                            ])->where('id', $Ac_currency_id)
                                ->get();

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            DB::table($all_part_table)->where('id', $element->id)
                            ->decrement('remain_amount', $element->remain_amount);
                            InvoiceItemsOrderSupplier::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'order_supplier_id' => $element->order_supplier_id,
                                'amount' =>  $element->remain_amount
                            ]);
                            $requestAmount0 = $requestAmount0 - $element->remain_amount;


                        } else if ($requestAmount0 <= 0) {
                            break;
                        }
                    }

                    $store = Store::where('id', $request->storeId)->get();
                    $store_id = $store[0]->id;
                    $store_name = $store[0]->name;
                    $store_table_name = $store[0]->table_name;


                    $requestAmount1 = $amount;
                    $allstoresRes = DB::table($store_table_name)
                    ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join($all_part_table, function ($join) use ($store_table_name, $all_part_table) {
                        $join->on($store_table_name . '.supplier_order_id', '=', $all_part_table . '.order_supplier_id')
                            ->on('stores_log.All_part_id', '=', $all_part_table . '.id');
                    })
                    ->where('stores_log.store_id', $store_id)
                    ->where($all_part_table . '.source_id', $source_id)
                    ->where($all_part_table . '.status_id', $status_id)
                    ->where($all_part_table . '.quality_id', $quality_id)
                    ->where($store_table_name . '.part_id', $part_id)
                    // ->where($store_table_name . '.supplier_order_id', $element->order_supplier_id)
                    ->where($store_table_name . '.type_id', 4)
                    ->select($store_table_name.'.*','stores_log.All_part_id','stores_log.store_id',
                    $all_part_table.'.buy_costing',
                    $all_part_table.'.source_id',
                    $all_part_table.'.status_id',
                    $all_part_table.'.quality_id',
                    $all_part_table.'.buy_price')
                    ->get();





                    foreach ($allstoresRes as $key => $element) {
                        if ($element->amount >= $requestAmount1) {
                            DB::table($store_table_name)
                            ->where('part_id', $element->part_id)
                            ->where('supplier_order_id', $element->supplier_order_id)
                            ->where('type_id', $element->type_id)
                            ->where('store_log_id', $element->store_log_id)->decrement('amount', $requestAmount1);
                            break;
                        } elseif ($element->amount < $requestAmount1) {
                            DB::table($store_table_name)
                            ->where('part_id', $element->part_id)
                            ->where('supplier_order_id', $element->supplier_order_id)
                            ->where('type_id', $element->type_id)
                            ->where('store_log_id', $element->store_log_id)->decrement('amount', $element->amount);
                            $requestAmount1 = $requestAmount1 - $element->amount;
                        } else if ($requestAmount1 <= 0) {
                            break;
                        }
                    }


                    ////// remove from Sections ////////
                    $allsecc = DB::table('store_section')
                        ->where('part_id', $part_id)
                        // ->where('supplier_order_id', $allparts[0]->order_supplier_id)
                        ->where('type_id', 4)
                        ->where('source_id', $source_id)
                        ->where('status_id', $status_id)
                        ->where('quality_id', $quality_id)
                        ->where('amount', '>',0)
                        ->where('store_id', $request->storeId)
                        ->get();

                    $requestSecAmount = $amount;
                    foreach ($allsecc as $key => $element) {

                        if ($element->amount >= $requestSecAmount) {
                            // decrement amount
                            StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $requestSecAmount
                            ]);

                            break;
                        } elseif ($element->amount < $requestSecAmount) {
                            // decrement remain_amount
                            StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                            $requestSecAmount = $requestSecAmount - $element->amount;
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $element->amount
                            ]);
                        } else if ($requestSecAmount <= 0) {
                            break;
                        }
                    }

                } elseif ($type_id == 5) {
                    //equip
                    $allparts = AllEquip::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->with('order_supplier')->get();

                    $all_part_table = 'all_equips';
                    $requestAmount0 = $amount;
                    foreach ($allparts as $key => $element) {

                        if ($element->remain_amount >= $requestAmount0) {

                            $Ac_currency_id = $element->order_supplier->currency_id;
                            $Ac_currency_date = $element->order_supplier->confirmation_date;
                            $Ac_all_currency_types = CurrencyType::with([
                                'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                    return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                                },
                            ])->where('id', $Ac_currency_id)
                                ->get();

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);

                            DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $requestAmount0);
                            InvoiceItemsOrderSupplier::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'order_supplier_id' => $element->order_supplier_id,
                                'amount' =>  $requestAmount0
                            ]);
                            break;


                        } elseif ($element->remain_amount < $requestAmount0) {

                            $Ac_currency_id = $element->order_supplier->currency_id;
                            $Ac_currency_date = $element->order_supplier->confirmation_date;
                            $Ac_all_currency_types = CurrencyType::with([
                                'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                    return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                                },
                            ])->where('id', $Ac_currency_id)
                                ->get();

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            DB::table($all_part_table)->where('id', $element->id)
                            ->decrement('remain_amount', $element->remain_amount);
                            InvoiceItemsOrderSupplier::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'order_supplier_id' => $element->order_supplier_id,
                                'amount' =>  $element->remain_amount
                            ]);
                            $requestAmount0 = $requestAmount0 - $element->remain_amount;


                        } else if ($requestAmount0 <= 0) {
                            break;
                        }
                    }

                    $store = Store::where('id', $request->storeId)->get();
                    $store_id = $store[0]->id;
                    $store_name = $store[0]->name;
                    $store_table_name = $store[0]->table_name;


                    $requestAmount1 = $amount;
                    $allstoresRes = DB::table($store_table_name)
                    ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join($all_part_table, function ($join) use ($store_table_name, $all_part_table) {
                        $join->on($store_table_name . '.supplier_order_id', '=', $all_part_table . '.order_supplier_id')
                            ->on('stores_log.All_part_id', '=', $all_part_table . '.id');
                    })
                    ->where('stores_log.store_id', $store_id)
                    ->where($all_part_table . '.source_id', $source_id)
                    ->where($all_part_table . '.status_id', $status_id)
                    ->where($all_part_table . '.quality_id', $quality_id)
                    ->where($store_table_name . '.part_id', $part_id)
                    // ->where($store_table_name . '.supplier_order_id', $element->order_supplier_id)
                    ->where($store_table_name . '.type_id', 5)
                    ->select($store_table_name.'.*','stores_log.All_part_id','stores_log.store_id',
                    $all_part_table.'.buy_costing',
                    $all_part_table.'.source_id',
                    $all_part_table.'.status_id',
                    $all_part_table.'.quality_id',
                    $all_part_table.'.buy_price')
                    ->get();





                    foreach ($allstoresRes as $key => $element) {
                        if ($element->amount >= $requestAmount1) {
                            DB::table($store_table_name)
                            ->where('part_id', $element->part_id)
                            ->where('supplier_order_id', $element->supplier_order_id)
                            ->where('type_id', $element->type_id)
                            ->where('store_log_id', $element->store_log_id)->decrement('amount', $requestAmount1);
                            break;
                        } elseif ($element->amount < $requestAmount1) {
                            DB::table($store_table_name)
                            ->where('part_id', $element->part_id)
                            ->where('supplier_order_id', $element->supplier_order_id)
                            ->where('type_id', $element->type_id)
                            ->where('store_log_id', $element->store_log_id)->decrement('amount', $element->amount);
                            $requestAmount1 = $requestAmount1 - $element->amount;
                        } else if ($requestAmount1 <= 0) {
                            break;
                        }
                    }


                    ////// remove from Sections ////////
                    $allsecc = DB::table('store_section')
                        ->where('part_id', $part_id)
                        // ->where('supplier_order_id', $allparts[0]->order_supplier_id)
                        ->where('type_id', 5)
                        ->where('source_id', $source_id)
                        ->where('status_id', $status_id)
                        ->where('quality_id', $quality_id)
                        ->where('store_id', $request->storeId)
                        ->where('amount', '>',0)
                        ->get();

                    $requestSecAmount = $amount;
                    foreach ($allsecc as $key => $element) {

                        if ($element->amount >= $requestSecAmount) {
                            // decrement amount
                            StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $requestSecAmount
                            ]);

                            break;
                        } elseif ($element->amount < $requestSecAmount) {
                            // decrement remain_amount
                            StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                            $requestSecAmount = $requestSecAmount - $element->amount;
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $element->amount
                            ]);
                        } else if ($requestSecAmount <= 0) {
                            break;
                        }
                    }

                }
            }

            /////////////////////adel///////////
            $store = Store::where('id', $request->storeId)->first();

            $storees = Store::where('safe_accountant_number', $request->payment)->first();

            if ($storees) {
                $total = MoneySafe::where('store_id', $request->storeId)
                    ->latest()
                    ->first();
                if (isset($total)) {
                    MoneySafe::create([
                        'notes' => 'مبلغ فاتورة بيع رقم' . ' ' . $invoice->id . ' ' . ' من عميل ' . ' ' . $cli[0]->name . ' ' . $store->name,

                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->invPaied,
                        'total' => $total->total + $request->invPaied,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => $request->storeId,
                        'note_id' => 17,
                    ]);
                    $logMessage.='مبلغ فاتورة بيع رقم '.$invoice->id.' تم اضافة'.$request->invPaied.
                            ' الي خزنة '.$request->storeId.'<br/>';
                } else {
                    MoneySafe::create([
                        'notes' => 'مبلغ فاتورة بيع رقم' . ' ' . $invoice->id . ' ' . ' من عميل ' . ' ' . $cli[0]->name . ' ' . $store->name,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->invPaied,
                        'total' => $request->invPaied,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => $request->storeId,
                        'note_id' => 17,
                    ]);
                    $logMessage.='مبلغ فاتورة بيع رقم '.$invoice->id.' تم اضافة'.$request->invPaied.
                            ' الي خزنة '.$request->storeId.'<br/>';
                }
                
                $newqayd = new Newqayd();
                $newqayd->refrence='CASH/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                $newqayd->coa_id=41;
                $newqayd->journal_id=7;
                $newqayd->partner_id=$cli_id;
                $newqayd->type='Client';
                $newqayd->label='Manual Payment/'.date('Y').'/'.date('m');
                $newqayd->cost_center='';
                $newqayd->amount_currency=null;
                $newqayd->currency_id=null;
                $newqayd->credit= 0;
                $newqayd->debit=$request->invPaied;
                $newqayd->desc='';
                $newqayd->user_id=Auth::user()->id;
                $newqayd->flag='0';
                $newqayd->invoice_id=$invoice->id;
                $newqayd->invoice_table='printInvoice';
                $newqayd->qayds_id=$nqayd->id;
                $newqayd->show_no='3';
                $newqayd->save();

            } else {
                $storees = BankType::where('accountant_number', $request->payment)->first();
                $total = BankSafeMoney::where('bank_type_id', $storees->id)
                    ->latest()
                    ->first();

                if (isset($total)) {
                    BankSafeMoney::create([
                        'notes' => 'مبلغ فاتورة بيع رقم' . ' ' . $invoice->id . ' ' . ' من عميل ' . ' ' . $cli[0]->name . ' ' . $store->name,

                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->invPaied,
                        'total' => $total->total + $request->invPaied,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => null,
                        'money_currency' => $request->invPaied,
                        'currency_id' => 400,
                        'bank_type_id' => $storees->id

                    ]);
                    $logMessage.='مبلغ فاتورة بيع رقم '.$invoice->id.' تم اضافة'.$request->invPaied.
                            ' الي بنك '.$storees->id.'<br/>';
                } else {
                    BankSafeMoney::create([
                        'notes' => 'مبلغ فاتورة بيع رقم' . ' ' . $invoice->id . ' ' . ' من عميل ' . ' ' . $cli[0]->name . ' ' . $store->name,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->invPaied,
                        'total' => $request->invPaied,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => null,
                        'money_currency' => $request->invPaied,
                        'currency_id' => 400,
                        'bank_type_id' => $storees->id
                    ]);
                     $logMessage.='مبلغ فاتورة بيع رقم '.$invoice->id.' تم اضافة'.$request->invPaied.
                    ' الي بنك '.$storees->id.'<br/>';
                }
                
                
                $newqayd = new Newqayd();
                $newqayd->refrence='BANK/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                $newqayd->coa_id=40;
                $newqayd->journal_id=7;
                $newqayd->partner_id=$cli_id;
                $newqayd->type='Client';
                $newqayd->label='BANK Transfer/'.date('Y').'/'.date('m');
                $newqayd->cost_center='';
                $newqayd->amount_currency=null;
                $newqayd->currency_id=null;
                $newqayd->credit= 0;
                $newqayd->debit=$request->invPaied;
                $newqayd->desc='';
                $newqayd->user_id=Auth::user()->id;
                $newqayd->flag='0';
                $newqayd->invoice_id=$invoice->id;
                $newqayd->invoice_table='printInvoice';
                $newqayd->qayds_id=$nqayd->id;
                $newqayd->show_no='3';
                $newqayd->save();

            }
            
            
            $newqayd = new Newqayd();
            $newqayd->refrence='INV/'.date('Y').'/'.date('m').'/'.$nqayd->id;;
            $newqayd->coa_id=166;
            $newqayd->journal_id=9;
            $newqayd->partner_id=$cli_id;
            $newqayd->type='Client';
            $newqayd->label='Discount Loss To Customers For INV/'.date('Y').'/'.date('m'); 
            $newqayd->cost_center='';
            $newqayd->amount_currency=null;
            $newqayd->currency_id=null;
            $newqayd->credit= 0;
            $newqayd->debit=$request->invDiscount;
            $newqayd->desc='';
            $newqayd->user_id=Auth::user()->id;
            $newqayd->flag='0';
            $newqayd->invoice_id=$invoice->id;
            $newqayd->invoice_table='printInvoice';
            $newqayd->qayds_id=$nqayd->id;
            $newqayd->show_no='2';
            $newqayd->save();


            $newqayd = new Newqayd();
            $newqayd->refrence='INV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
            $newqayd->coa_id=45;
            $newqayd->journal_id=7;
            $newqayd->partner_id=$cli_id;
            $newqayd->type='Client';
            $newqayd->label='Payment For INV/'.date('Y').'/'.date('m'); 
            $newqayd->cost_center='';
            $newqayd->amount_currency=null;
            $newqayd->currency_id=null;
            $newqayd->credit= $request->invPaied;
            $newqayd->debit=0;
            $newqayd->desc='';
            $newqayd->user_id=Auth::user()->id;
            $newqayd->flag='0';
            $newqayd->invoice_id=$invoice->id;
            $newqayd->invoice_table='printInvoice';
            $newqayd->qayds_id=$nqayd->id;
            $newqayd->show_no='3';
            $newqayd->save();
            

            $newqayd = new Newqayd();
            $newqayd->refrence='INV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
            $newqayd->coa_id=45;
            $newqayd->journal_id=9;
            $newqayd->partner_id=$cli_id;
            $newqayd->type='Client';
            $newqayd->label='INV/'.date('Y').'/'.date('m');
            $newqayd->cost_center='';
            $newqayd->amount_currency=null;
            $newqayd->currency_id=null;
            $newqayd->credit= 0;
            $newqayd->debit=$request->total;
            $newqayd->desc='';
            $newqayd->user_id=Auth::user()->id;
            $newqayd->flag='0';
            $newqayd->invoice_id=$invoice->id;
            $newqayd->invoice_table='printInvoice';
            $newqayd->qayds_id=$nqayd->id;
            $newqayd->show_no='2';
            $newqayd->save();
            if (isset($request->taxes)) {
                foreach ($request->taxes as $key => $tax) {
                    if ($tax == '14') {
                        $taxacx = ($request->subtotal * 14) / 100; 
                        
                        $newqayd = new Newqayd();
                        $newqayd->refrence='INV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                        $newqayd->coa_id=83;
                        $newqayd->journal_id=9;
                        $newqayd->partner_id=$cli_id;
                        $newqayd->type='Client';
                        $newqayd->label=$tax.'% Vat';
                        $newqayd->cost_center='';
                        $newqayd->amount_currency=null;
                        $newqayd->currency_id=null;
                        $newqayd->credit=$taxacx;
                        $newqayd->debit=0;
                        $newqayd->desc='';
                        $newqayd->user_id=Auth::user()->id;
                        $newqayd->flag='0';
                        $newqayd->invoice_id=$invoice->id;
                        $newqayd->invoice_table='printInvoice';
                        $newqayd->qayds_id=$nqayd->id;
                        $newqayd->show_no='2';
                        $newqayd->save();
    
                    } elseif ($tax == '-1') {
                        $taxacx = ($request->subtotal * -1) / 100; 
                        $newqayd = new Newqayd();
                        $newqayd->refrence='INV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                        $newqayd->coa_id=15;
                        $newqayd->journal_id=9;
                        $newqayd->partner_id=$cli_id;
                        $newqayd->type='Client';
                        $newqayd->label=$tax.'% WH';
                        $newqayd->cost_center='';
                        $newqayd->amount_currency=null;
                        $newqayd->currency_id=null;
                        $newqayd->credit= 0;
                        $newqayd->debit=$taxacx;
                        $newqayd->desc='';
                        $newqayd->user_id=Auth::user()->id;
                        $newqayd->flag='0';
                        $newqayd->invoice_id=$invoice->id;
                        $newqayd->invoice_table='printInvoice';
                        $newqayd->qayds_id=$nqayd->id;
                        $newqayd->show_no='2';
                        $newqayd->save();
                        
                    } elseif ($tax == '5') {
                        $taxacx = ($request->subtotal * 5) / 100; 
                        $newqayd = new Newqayd();
                        $newqayd->refrence='INV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                        $newqayd->coa_id=15;
                        $newqayd->journal_id=9;
                        $newqayd->partner_id=$cli_id;
                        $newqayd->type='Client';
                        $newqayd->label=$tax.'% Vat';
                        $newqayd->cost_center='';
                        $newqayd->amount_currency=null;
                        $newqayd->currency_id=null;
                        $newqayd->credit=$taxacx;
                        $newqayd->debit=0;
                        $newqayd->desc='';
                        $newqayd->user_id=Auth::user()->id;
                        $newqayd->flag='0';
                        $newqayd->invoice_id=$invoice->id;
                        $newqayd->invoice_table='printInvoice';
                        $newqayd->qayds_id=$nqayd->id;
                        $newqayd->show_no='2';
                        $newqayd->save();
    
                    }else{
                        $taxacx = ($request->subtotal * intval($tax)) / 100; 
                        $newqayd = new Newqayd();
                        $newqayd->refrence='INV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                        $newqayd->coa_id=15;
                        $newqayd->journal_id=9;
                        $newqayd->partner_id=$cli_id;
                        $newqayd->type='Client';
                        $newqayd->label=$tax.'% Vat';
                        $newqayd->cost_center='';
                        $newqayd->amount_currency=null;
                        $newqayd->currency_id=null;
                        $newqayd->credit=$taxacx;
                        $newqayd->debit=0;
                        $newqayd->desc='';
                        $newqayd->user_id=Auth::user()->id;
                        $newqayd->flag='0';
                        $newqayd->invoice_id=$invoice->id;
                        $newqayd->invoice_table='printInvoice';
                        $newqayd->qayds_id=$nqayd->id;
                        $newqayd->show_no='2';
                        $newqayd->save();
    
                    }
                    
                }
            }
            $newqayd = new Newqayd();
            $newqayd->refrence='INV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
            $newqayd->coa_id=170;
            $newqayd->journal_id=9;
            $newqayd->partner_id=$cli_id;
            $newqayd->type='Client';
            $newqayd->label='Stock/OUT/'.date('Y').'/'.date('m');
            $newqayd->cost_center='';
            $newqayd->amount_currency=null;
            $newqayd->currency_id=null;
            $newqayd->credit= $request->subtotal;
            $newqayd->debit=0;
            $newqayd->desc='';
            $newqayd->user_id=Auth::user()->id;
            $newqayd->flag='0';
            $newqayd->invoice_id=$invoice->id;
            $newqayd->invoice_table='printInvoice';
            $newqayd->qayds_id=$nqayd->id;
            $newqayd->show_no='2';
            $newqayd->save();

            $newqayd = new Newqayd();
            $newqayd->refrence='INV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
            $newqayd->coa_id=168;
            $newqayd->journal_id=9;
            $newqayd->partner_id=$cli_id;
            $newqayd->type='Client';
            $newqayd->label='Stock/OUT/'.date('Y').'/'.date('m');
            $newqayd->cost_center='';
            $newqayd->amount_currency=null;
            $newqayd->currency_id=null;
            $newqayd->credit= 0;
            $newqayd->debit=$total_item_buy_price;
            $newqayd->desc='';
            $newqayd->user_id=Auth::user()->id;
            $newqayd->flag='0';
            $newqayd->invoice_id=$invoice->id;
            $newqayd->invoice_table='printInvoice';
            $newqayd->qayds_id=$nqayd->id;
            $newqayd->show_no='2';
            $newqayd->save();

            $newqayd = new Newqayd();
            $newqayd->refrence='INV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
            $newqayd->coa_id=225;
            $newqayd->journal_id=9;
            $newqayd->partner_id=$cli_id;
            $newqayd->type='Client';
            $newqayd->label='Stock/OUT/'.date('Y').'/'.date('m');
            $newqayd->cost_center='';
            $newqayd->amount_currency=null;
            $newqayd->currency_id=null;
            $newqayd->credit=$total_item_buy_price;
            $newqayd->debit=0;
            $newqayd->desc='';
            $newqayd->user_id=Auth::user()->id;
            $newqayd->flag='0';
            $newqayd->invoice_id=$invoice->id;
            $newqayd->invoice_table='printInvoice';
            $newqayd->qayds_id=$nqayd->id;
            $newqayd->show_no='2';
            $newqayd->save();


            $quaditems = [];
            $automaicQayd = new QaydController();
            array_push($quaditems, (object) ['acountant_id' => 37, 'dayin' => 0, 'madin' => $total_item_buy_price]); // المبيعات مدين
            array_push($quaditems, (object) ['acountant_id' => $store->accountant_number, 'dayin' => $total_item_buy_price, 'madin' => 0]); // المخزن دائن
            $date = Carbon::now();
            $type = null;
            $notes = 'فاتورة بيع رقم' . $invoice->id;
            $qyadidss = $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
             $logMessage.='مبلغ فاتورة بيع رقم '.$invoice->id.' تم اضافة القيد'.$qyadidss.'<br/>';
            Qayditem::where('qaydid', $qyadidss)->update([
                'invoiceid' => $invoice->id,
                'flag' => 1
            ]);
            // /********************************** Automaic qayd*******************************************************
            // $request->taxval الضريبة
            // $request->taxes انواع الضرائب
            // $request->subtotal السعر قبل الضريبة
            // $request->invPaied; المدفوع
            // $request->total الاجمالي بعد الضريبة
            // $request->payment

            $quaditems = [];
            $automaicQayd = new QaydController();

            $invoiceac = 0;
            $taxac = 0;
            $binvoiceac = 0;

            if (floatval($request->taxval) > 0) {
                foreach ($request->taxes as $key => $value) {
                    if ($value == '14') {
                        $taxac = ($request->subtotal * 14) / 100; // الضريبة
                        // $invoiceac = floatval($request->subtotal) + $taxac; //الاجمالي بعد الضريبة
                        // $binvoiceac =round($invoiceac - $taxac);
                        array_push($quaditems, (object) ['acountant_id' => 2636, 'madin' => 0, 'dayin' => $taxac]); // الضريبة دائن
                    } elseif ($value == '-1') {
                        $taxac = ($request->subtotal * -1) / 100; // الضريبة
                        // $invoiceac = floatval($request->subtotal) + $taxac; //الاجمالي بعد الضريبة
                        // $binvoiceac =round($invoiceac - $taxac);
                        array_push($quaditems, (object) ['acountant_id' => 2637, 'dayin' => $taxac, 'madin' => 0]); // الضريبة مدين
                    } else {
                    }

                    $invoiceac += $taxac;
                    $binvoiceac += round($invoiceac - $taxac);
                }
            }
            //  if(floatval($request->taxval) > 0 ){
            //     // غير شامل

            //     $taxac = $request->subtotal * 14 /100 ; // الضريبة
            //     $invoiceac = floatval($request->subtotal) + $taxac; //الاجمالي بعد الضريبة
            //     $binvoiceac =round($invoiceac - $taxac);
            //     //   array_push ( $quaditems , (object) [ 'acountant_id'=> 2636  , 'madin'=> 0 , 'dayin'=> $taxac ] ); // الضريبة دائن
            //     // هنا هنزود الضرائب التانية

            // }
            // else{
            //     // شامل
            //     $binvoiceac = round(floatval($request->total) / 1.14); //  المشتريات غير شامل الضريبة
            //     $taxac = floatval($request->total) -$binvoiceac ; // الضريبة
            //     $invoiceac =  round($binvoiceac + $taxac);
            // }

            if ($request->invPaied == $request->total) {
                // البيع كاش
                array_push($quaditems, (object) ['acountant_id' => 4511, 'dayin' => $request->invPaied, 'madin' => 0]); // المبيعات دائن
                array_push($quaditems, (object) ['acountant_id' => $request->payment, 'dayin' => 0, 'madin' => $request->invPaied]); // الخزنة مدين
            }
            // البيع أجل
            else {
                array_push($quaditems, (object) ['acountant_id' => 4511, 'dayin' => floatval($request->total) - $invoiceac, 'madin' => 0]); // المبيعات دائن

                $acClientNo = Client::where('id', $cli_id)->first()->accountant_number;

                array_push($quaditems, (object) ['acountant_id' => $acClientNo, 'dayin' => 0, 'madin' => floatval($request->total) - $request->invPaied - $request->invDiscount]); // العميل مدين
                // if( $request->store_id != 0  ) // خزنة
                // {
                //     if($request->invPaied > 0){
                //         array_push ( $quaditems , (object) [ 'acountant_id'=> 1812 , 'dayin'=> 0 , 'madin'=> $request->invPaied ] ); // الخزنة مدين
                //     }

                // }else // بنك
                // {
                //      if($request->invPaied > 0){
                //          array_push ( $quaditems , (object) [ 'acountant_id'=> 1821 , 'dayin'=> 0 , 'madin'=> $request->invPaied ] ); // بنك مدين
                //      }

                // }
                if ($request->invPaied > 0) {
                    array_push($quaditems, (object) ['acountant_id' => $request->payment, 'dayin' => 0, 'madin' => $request->invPaied]); // بنك مدين
                }
            }

            //   return $taxac;

            // هنضيف الضريبة
            //  if($taxac > 0){
            //     array_push ( $quaditems , (object) [ 'acountant_id'=> 2636  , 'madin'=> 0 , 'dayin'=> $taxac ] ); // الضريبة دائن
            // }
            if ($request->invDiscount > 0) {
                array_push($quaditems, (object) ['acountant_id' => 4513, 'madin' => $request->invDiscount, 'dayin' => 0]); // مسموحات مبيعات دائن
            }

            $date = Carbon::now();
            $type = null;
            $notes = 'فاتورة بيع رقم' . $invoice->id;
            $qyadidss = $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
            $logMessage.='مبلغ فاتورة بيع رقم '.$invoice->id.' تم اضافة القيد'.$qyadidss.'<br/>';
            Qayditem::where('qaydid', $qyadidss)->update([
                'invoiceid' => $invoice->id,
                'flag' => 1
            ]);

            $user = User::find(33); // Get the user
            $user->notify(new NotifyUser('تم إضافة فاتورة بيع رقم '.$invoice->id.' من مخزن '.$store->name));
        
        $user = User::find(39); // Get the user
            $user->notify(new NotifyUser('تم إضافة فاتورة بيع رقم '.$invoice->id.' من مخزن '.$store->name));
            // /*****************************************************************************************
            DB::commit();
            $log = new LogController();
            $log->newLog($logUser,$logMessage);
            return redirect()->to('printInvoice/' . $invoice->id);
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            session()->flash("success", "لا يمكن حفظ الفاتورة" . $e);
            return redirect()->back();
        }
    }


    public function allDataForIdPartNumber(Request $request)
    {

        // return $request;
        if ($request->ajax()) {

            ////           Get Store Entity /////////////////
            $store = Store::where('id', $request->storeId)->first();

            $entity_tbl = 'App\\Models\\' . ucfirst($store->table_name);
            if ($store->table_name == 'damaged_parts') {
                $entity_tbl = 'damagedPart';
            }

            /*********************************************/
            // return $entity_tbl;

            $numberVal = $request->searchkey;
            $query = '';
            if ($request->typeId == 1) {


                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                    // ->where($store->table_name . '.part_id', $request->PartID)
                    ->whereIn($store->table_name . '.part_id',(function ($query) use($numberVal) {
                        $query->from('part_number')
                            ->select('part_id')
                            ->where('number','LIKE','%'.$numberVal.'%');
                    }))
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_parts.part_id, all_parts.source_id, all_parts.status_id, all_parts.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_parts.part_id', 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id',  $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_parts.part.part_numbers',
                        'stores_log.all_parts.part.part_images',
                        'stores_log.all_parts.source',
                        'stores_log.all_parts.status',
                        'stores_log.all_parts.part_quality',
                        'stores_log.all_parts.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_parts.sectionswithoutorder.store',
                        'stores_log.all_parts.sectionswithoutorder.store_structure',
                        'stores_log.all_parts.pricing.sale_type',
                        'stores_log.all_parts.order_supplier.buy_transaction',
                        'stores_log.all_parts.part.sub_group.group',
                        'stores_log.all_parts.part.part_details.part_spec',
                        'stores_log.all_parts.part.part_models.series.model.brand',
                        'stores_log.all_parts.part.part_images',
                    ])
                    ->get();
            } elseif ($request->typeId == 6) {
                $query = $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_kits.part_id, all_kits.source_id, all_kits.status_id, all_kits.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_kits.part_id', 'all_kits.source_id', 'all_kits.status_id', 'all_kits.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_kits.kit.kit_numbers',
                        'stores_log.all_kits.kit.kit_images',
                        'stores_log.all_kits.source',
                        'stores_log.all_kits.status',
                        'stores_log.all_kits.part_quality',
                        'stores_log.all_kits.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_kits.sectionswithoutorder.store',
                        'stores_log.all_kits.sectionswithoutorder.store_structure',
                        'stores_log.all_kits.pricing.sale_type',
                        'stores_log.all_kits.order_supplier.buy_transaction',
                        'stores_log.all_kits.kit.kit_details.kit_spec',
                        'stores_log.all_kits.kit.kit_models.series.model.brand',
                        'stores_log.all_kits.kit.kit_images',
                    ])
                    ->get();
            } elseif ($request->typeId == 2) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_wheels.part_id, all_wheels.source_id, all_wheels.status_id, all_wheels.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_wheels.part_id', 'all_wheels.source_id', 'all_wheels.status_id', 'all_wheels.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_wheels.wheel.wheel_images',
                        'stores_log.all_wheels.source',
                        'stores_log.all_wheels.status',
                        'stores_log.all_wheels.part_quality',
                        'stores_log.all_wheels.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_wheels.sectionswithoutorder.store',
                        'stores_log.all_wheels.sectionswithoutorder.store_structure',
                        'stores_log.all_wheels.pricing.sale_type',
                        'stores_log.all_wheels.order_supplier.buy_transaction',
                        'stores_log.all_wheels.wheel.wheel_details.wheel_spec',
                        'stores_log.all_wheels.wheel.wheel_model',
                        'stores_log.all_wheels.wheel.wheel_images',
                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 3) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_tractors', 'stores_log.All_part_id', '=', 'all_tractors.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_tractors.part_id, all_tractors.source_id, all_tractors.status_id, all_tractors.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_tractors.part_id', 'all_tractors.source_id', 'all_tractors.status_id', 'all_tractors.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_tractors.tractor.tractor_images',
                        'stores_log.all_tractors.source',
                        'stores_log.all_tractors.status',
                        'stores_log.all_tractors.part_quality',
                        'stores_log.all_tractors.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_tractors.sectionswithoutorder.store',
                        'stores_log.all_tractors.sectionswithoutorder.store_structure',
                        'stores_log.all_tractors.pricing.sale_type',
                        'stores_log.all_tractors.order_supplier.buy_transaction',
                        'stores_log.all_tractors.tractor.tractor_details.tractor_spec',
                        'stores_log.all_tractors.tractor.tractor_images',



                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 4) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_clarks', 'stores_log.All_part_id', '=', 'all_clarks.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_clarks.part_id, all_clarks.source_id, all_clarks.status_id, all_clarks.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_clarks.part_id', 'all_clarks.source_id', 'all_clarks.status_id', 'all_clarks.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_clarks.clark.clark_images',
                        'stores_log.all_clarks.source',
                        'stores_log.all_clarks.status',
                        'stores_log.all_clarks.part_quality',
                        'stores_log.all_clarks.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_clarks.sectionswithoutorder.store',
                        'stores_log.all_clarks.sectionswithoutorder.store_structure',
                        'stores_log.all_clarks.pricing.sale_type',
                        'stores_log.all_clarks.order_supplier.buy_transaction',
                        'stores_log.all_clarks.clark.clark_details.clark_spec',
                        'stores_log.all_clarks.clark.clark_images',
                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 5) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_equips', 'stores_log.All_part_id', '=', 'all_equips.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_equips.part_id, all_equips.source_id, all_equips.status_id, all_equips.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_equips.part_id', 'all_equips.source_id', 'all_equips.status_id', 'all_equips.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_equips.equip.equip_images',
                        'stores_log.all_equips.source',
                        'stores_log.all_equips.status',
                        'stores_log.all_equips.part_quality',
                        'stores_log.all_equips.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_equips.sectionswithoutorder.store',
                        'stores_log.all_equips.sectionswithoutorder.store_structure',
                        'stores_log.all_equips.pricing.sale_type',
                        'stores_log.all_equips.order_supplier.buy_transaction',
                        'stores_log.all_equips.equip.equip_details.equip_spec',
                        'stores_log.all_equips.equip.equip_images',
                    ])
                    ->get();
                // Merge all queries
            }
            // return $query;
            foreach ($query as $ele) {
                # code...
                $ele['stores_amount']=$this->PartInStoresCount($ele->part_id , $ele->source_id, $ele->status_id, $ele->quality_id, $ele->type_id);
            }



            return FacadesDataTables::of($query)
                // ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    $btn = 'No Name';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->kit->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->wheel->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->tractor->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->clark->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->equip->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    }
                })
                ->addColumn('Image', function ($row) {
                    $btn = 'No Image';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if(isset($row->stores_log->all_parts[0]->part->part_images) && count($row->stores_log->all_parts[0]->part->part_images)>0  ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/part_images/'.$row->stores_log->all_parts[0]->part->part_images[0]->image_name.'" alt="">';
                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if(isset($row->stores_log->all_kits[0]->kit->kit_image) && count($row->stores_log->all_kits[0]->kit->kit_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/kit_images/'.$row->stores_log->all_kits[0]->kit->kit_image[0]->image_name.'" alt="">';
                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if(isset($row->stores_log->all_wheels[0]->wheel->wheel_image)  && count($row->stores_log->all_wheels[0]->wheel->wheel_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/wheel_images/'.$row->stores_log->all_wheels[0]->wheel->wheel_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if(isset($row->stores_log->all_tractors[0]->tractor->tractor_image) && count($row->stores_log->all_tractors[0]->tractor->tractor_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/tractor_images/'.$row->stores_log->all_tractors[0]->tractor->tractor_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if(isset($row->stores_log->all_clarks[0]->clarck->clarck_image)  && count($row->stores_log->all_clarks[0]->clarck->clarck_image)>0  ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/clarck_images/'.$row->stores_log->all_clarks[0]->clarck->clarck_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if(isset($row->stores_log->all_equips[0]->equip->equip_image) && count($row->stores_log->all_equips[0]->equip->equip_image)>0  ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/equip_images/'.$row->stores_log->all_equips[0]->equip->equip_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    }
                })
                ->addColumn('source', function ($row) {
                    $btn = 'No Source';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    }


                    return $btn;
                })
                ->addColumn('status', function ($row) {
                    $btn = 'No Status';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    }

                    return $btn;
                })
                ->addColumn('quality', function ($row) {
                    $btn = 'No Quality';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    }
                    return $btn;
                })
                ->addColumn('amount', function ($row) {
                    $btn = 0;
                    if ($row->amount > 0) {
                        $btn = $row->amount;
                    } else {
                        $btn = 0;
                    }
                    return $btn;
                })
                ->addColumn('section', function ($row) {
                    $btn = '';
                    $secArr = [];
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->sectionswithoutorder) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->sectionswithoutorder); $i++) {
                                    if (in_array($row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->amount <= 0) {


                                    } else {

                                        $btn .= '<li>' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->amount  . '</span></li>';
                                        array_push($secArr, $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->id);
                                    }
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->sectionswithoutorder)) {
                                if (count($row->stores_log->all_kits[0]->sectionswithoutorder) > 0) {
                                    for ($i = 0; $i < count($row->stores_log->all_kits[0]->sectionswithoutorder); $i++) {
                                        if (in_array($row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->amount <= 0) {
                                        } else {

                                            $btn .= '<li>' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                            array_push($secArr, $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->id);
                                        }
                                    }
                                }
                            } else {
                                $btn = "No Section";
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (isset($row->stores_log->all_wheels[0]->sectionswithoutorder)) {
                                if (count($row->stores_log->all_wheels[0]->sectionswithoutorder) > 0) {
                                    for ($i = 0; $i < count($row->stores_log->all_wheels[0]->sectionswithoutorder); $i++) {
                                        if (in_array($row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->amount <= 0) {
                                        } else {

                                            $btn .= '<li>' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                            array_push($secArr, $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->id);
                                        }
                                    }
                                }
                            } else {
                                $btn = "No Section";
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (count($row->stores_log->all_tractors[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_tractors[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_tractors[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_tractors[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (count($row->stores_log->all_clarks[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_clarks[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_clarks[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_clarks[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (count($row->stores_log->all_equips[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_equips[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_equips[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_equips[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    }
                    return $btn;
                })
                ->addColumn('price', function ($row) {
                    $btn = 0;
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                $dataArr =collect($row->stores_log->all_parts[0]->pricing);


                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;
                                if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->pricing)) {
                                if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                    $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                      $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (isset($row->stores_log->all_equips[0]->pricing)) {
                                if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_equips[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    }

                    return $btn;
                })

                ->addColumn('partnumbers', function ($row) {
                    $btn = 'no num';
                    if ($row->type_id == 1) {
                        if (isset($row->stores_log->all_parts[0]->part->part_numbers)) {
                            if (count($row->stores_log->all_parts[0]->part->part_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->part->part_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_parts[0]->part->part_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 6) {
                        if (isset($row->stores_log->all_kits[0]->kit->kit_numbers)) {
                            if (count($row->stores_log->all_kits[0]->kit->kit_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_kits[0]->kit->kit_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_kits[0]->kit->kit_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 2) {
                    } elseif ($row->type_id == 3) {
                    } elseif ($row->type_id == 4) {
                    } elseif ($row->type_id == 5) {
                    }

                    return $btn;
                })
                ->addColumn('stores_amount', function ($row) {
                    $btn = '<ul class="list-group fs-13 text-truncate">';
                    if(isset($row->stores_amount)){
                        for ($i=0; $i < count($row->stores_amount) ; $i++) {
                            $btn .='<li>'.$row->stores_amount[$i]->name.'/'.$row->stores_amount[$i]->storepartCount.'</li>';

                        }
                    }else{
                        $btn .='<li>لا يوجد بالمخازن</li>';

                    }

                    $btn .= '</ul>';
                    return  $btn;

                })
                ->addColumn('view', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_parts[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_kits[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_wheels[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_tractors[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_clarks[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_equips[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    }

                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_parts) > 0) {
                                if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                    // $price = $row->stores_log->all_parts[0]->pricing[0]->price;

                                     $dataArr =collect($row->stores_log->all_parts[0]->pricing);
                                     $maxObject = $dataArr->sortByDesc('price')->first();


                                    $price =$maxObject->price ;
                                    $SaleType = $maxObject->sale_type;

                                    $prices = $row->stores_log->all_parts[0]->pricing;
                                    $prices_json = json_encode($prices);
                                }

                                if (count($row->stores_log->all_parts[0]->part->part_details) > 0) {
                                    $part_specs = $row->stores_log->all_parts[0]->part->part_details;
                                    $part_specs_json = json_encode($part_specs);
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_parts[0]->part->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_parts[0]->source_id) . ', ' . json_encode($row->stores_log->all_parts[0]->status_id) . ', ' . json_encode($row->stores_log->all_parts[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_kits) > 0) {
                                if (isset($row->stores_log->all_kits[0]->pricing)) {
                                    if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_kits[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_kits[0]->pricing[0]->price;
                                        $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;

                                        $prices = $row->stores_log->all_kits[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_kits[0]->kit->kit_details)) {
                                    if (count($row->stores_log->all_kits[0]->kit->kit_details) > 0) {
                                        $part_specs = $row->stores_log->all_kits[0]->kit->kit_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_kits[0]->kit->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_kits[0]->source_id) . ', ' . json_encode($row->stores_log->all_kits[0]->status_id) . ', ' . json_encode($row->stores_log->all_kits[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_wheels) > 0) {
                                if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                    if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_wheels[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_wheels[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_wheels[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_wheels[0]->wheel->wheel_details)) {
                                    if (count($row->stores_log->all_wheels[0]->wheel->wheel_details) > 0) {
                                        $part_specs = $row->stores_log->all_wheels[0]->wheel->wheel_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_wheels[0]->wheel->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_wheels[0]->source_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->status_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_tractors) > 0) {
                                if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                    if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_tractors[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_tractors[0]->pricing[0]->price;
                                          $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_tractors[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_tractors[0]->tractor->tractor_details)) {
                                    if (count($row->stores_log->all_tractors[0]->tractor->tractor_details) > 0) {
                                        $part_specs = $row->stores_log->all_tractors[0]->tractor->tractor_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_tractors[0]->tractor->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_tractors[0]->source_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->status_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_clarks) > 0) {
                                if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                    if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_clarks[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_clarks[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_clarks[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_clarks[0]->clark->clark_details)) {
                                    if (count($row->stores_log->all_clarks[0]->clark->clark_details) > 0) {
                                        $part_specs = $row->stores_log->all_clarks[0]->clark->clark_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_clarks[0]->clark->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_clarks[0]->source_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->status_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_equips) > 0) {
                                if (isset($row->stores_log->all_equips[0]->pricing)) {
                                    if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_equips[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_equips[0]->pricing[0]->price;
                                        $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_equips[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_equips[0]->equip->equip_details)) {
                                    if (count($row->stores_log->all_equips[0]->equip->equip_details) > 0) {
                                        $part_specs = $row->stores_log->all_equips[0]->equip->equip_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_equips[0]->equip->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_equips[0]->source_id) . ', ' . json_encode($row->stores_log->all_equips[0]->status_id) . ', ' . json_encode($row->stores_log->all_equips[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ', 1,' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    }
                    return '';
                })
                ->addColumn('send', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    }

                    return $btn;
                })
                ->addColumn('ask', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="askStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    }

                    return $btn;
                })
                 ->addColumn('talef', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';


                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $store_id = $row->stores_log->store_id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                        $partId . '/' .
                        $sourceId . '/' .
                        $statusId . '/' .
                        $partQualityId . '/' .
                        $typeId . '/' .
                        $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $store_id = $row->stores_log->store_id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    }

                    return $btn;
                })
                ->rawColumns(['stores_amount','view','Image' ,'name', 'source', 'status', 'quality', 'amount', 'section', 'price', 'partnumbers', 'action', 'send','ask','talef'])
                ->setTotalRecords(20)
                // ->filter(function ($query) {
                //     return $query;
                // })
                ->make(true);
        }
        // return view('testpos');
    }

    public function allDataForIdBrand(Request $request)
    {

        // return $request;
        if ($request->ajax()) {

            ////           Get Store Entity /////////////////
            $store = Store::where('id', $request->storeId)->first();

            $entity_tbl = 'App\\Models\\' . ucfirst($store->table_name);
            if ($store->table_name == 'damaged_parts') {
                $entity_tbl = 'damagedPart';
            }

            /*********************************************/
            // return $entity_tbl;

            $numberVal = $request->searchkey;
            $query = '';
            if ($request->typeId == 1) {


                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                    // ->where($store->table_name . '.part_id', $request->PartID)
                    ->whereIn($store->table_name . '.part_id', function ($query) use ($numberVal) {
                        $query->from('part_model')
                            ->join('series', 'part_model.model_id', '=', 'series.id')
                            ->join('model', 'series.model_id', '=', 'model.id')
                            ->select('part_model.part_id')
                            ->where('model.brand_id', '=', $numberVal);
                    })
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_parts.part_id, all_parts.source_id, all_parts.status_id, all_parts.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_parts.part_id', 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id',  $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_parts.part.part_numbers',
                        'stores_log.all_parts.part.part_images',
                        'stores_log.all_parts.source',
                        'stores_log.all_parts.status',
                        'stores_log.all_parts.part_quality',
                        'stores_log.all_parts.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_parts.sectionswithoutorder.store',
                        'stores_log.all_parts.sectionswithoutorder.store_structure',
                        'stores_log.all_parts.pricing.sale_type',
                        'stores_log.all_parts.order_supplier.buy_transaction',
                        'stores_log.all_parts.part.sub_group.group',
                        'stores_log.all_parts.part.part_details.part_spec',
                        'stores_log.all_parts.part.part_models.series.model.brand',
                        'stores_log.all_parts.part.part_images',
                    ])
                    ->get();
            } elseif ($request->typeId == 6) {
                $query = $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_kits.part_id, all_kits.source_id, all_kits.status_id, all_kits.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_kits.part_id', 'all_kits.source_id', 'all_kits.status_id', 'all_kits.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_kits.kit.kit_numbers',
                        'stores_log.all_kits.kit.kit_images',
                        'stores_log.all_kits.source',
                        'stores_log.all_kits.status',
                        'stores_log.all_kits.part_quality',
                        'stores_log.all_kits.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_kits.sectionswithoutorder.store',
                        'stores_log.all_kits.sectionswithoutorder.store_structure',
                        'stores_log.all_kits.pricing.sale_type',
                        'stores_log.all_kits.order_supplier.buy_transaction',
                        'stores_log.all_kits.kit.kit_details.kit_spec',
                        'stores_log.all_kits.kit.kit_models.series.model.brand',
                        'stores_log.all_kits.kit.kit_images',
                    ])
                    ->get();
            } elseif ($request->typeId == 2) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_wheels.part_id, all_wheels.source_id, all_wheels.status_id, all_wheels.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_wheels.part_id', 'all_wheels.source_id', 'all_wheels.status_id', 'all_wheels.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_wheels.wheel.wheel_images',
                        'stores_log.all_wheels.source',
                        'stores_log.all_wheels.status',
                        'stores_log.all_wheels.part_quality',
                        'stores_log.all_wheels.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_wheels.sectionswithoutorder.store',
                        'stores_log.all_wheels.sectionswithoutorder.store_structure',
                        'stores_log.all_wheels.pricing.sale_type',
                        'stores_log.all_wheels.order_supplier.buy_transaction',
                        'stores_log.all_wheels.wheel.wheel_details.wheel_spec',
                        'stores_log.all_wheels.wheel.wheel_model',
                        'stores_log.all_wheels.wheel.wheel_images',
                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 3) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_tractors', 'stores_log.All_part_id', '=', 'all_tractors.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_tractors.part_id, all_tractors.source_id, all_tractors.status_id, all_tractors.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_tractors.part_id', 'all_tractors.source_id', 'all_tractors.status_id', 'all_tractors.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_tractors.tractor.tractor_images',
                        'stores_log.all_tractors.source',
                        'stores_log.all_tractors.status',
                        'stores_log.all_tractors.part_quality',
                        'stores_log.all_tractors.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_tractors.sectionswithoutorder.store',
                        'stores_log.all_tractors.sectionswithoutorder.store_structure',
                        'stores_log.all_tractors.pricing.sale_type',
                        'stores_log.all_tractors.order_supplier.buy_transaction',
                        'stores_log.all_tractors.tractor.tractor_details.tractor_spec',
                        'stores_log.all_tractors.tractor.tractor_images',



                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 4) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_clarks', 'stores_log.All_part_id', '=', 'all_clarks.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_clarks.part_id, all_clarks.source_id, all_clarks.status_id, all_clarks.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_clarks.part_id', 'all_clarks.source_id', 'all_clarks.status_id', 'all_clarks.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_clarks.clark.clark_images',
                        'stores_log.all_clarks.source',
                        'stores_log.all_clarks.status',
                        'stores_log.all_clarks.part_quality',
                        'stores_log.all_clarks.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_clarks.sectionswithoutorder.store',
                        'stores_log.all_clarks.sectionswithoutorder.store_structure',
                        'stores_log.all_clarks.pricing.sale_type',
                        'stores_log.all_clarks.order_supplier.buy_transaction',
                        'stores_log.all_clarks.clark.clark_details.clark_spec',
                        'stores_log.all_clarks.clark.clark_images',
                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 5) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_equips', 'stores_log.All_part_id', '=', 'all_equips.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_equips.part_id, all_equips.source_id, all_equips.status_id, all_equips.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_equips.part_id', 'all_equips.source_id', 'all_equips.status_id', 'all_equips.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_equips.equip.equip_images',
                        'stores_log.all_equips.source',
                        'stores_log.all_equips.status',
                        'stores_log.all_equips.part_quality',
                        'stores_log.all_equips.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_equips.sectionswithoutorder.store',
                        'stores_log.all_equips.sectionswithoutorder.store_structure',
                        'stores_log.all_equips.pricing.sale_type',
                        'stores_log.all_equips.order_supplier.buy_transaction',
                        'stores_log.all_equips.equip.equip_details.equip_spec',
                        'stores_log.all_equips.equip.equip_images',
                    ])
                    ->get();
                // Merge all queries
            }

            // return $query;


            foreach ($query as $ele) {
                # code...
                $ele['stores_amount']=$this->PartInStoresCount($ele->part_id , $ele->source_id, $ele->status_id, $ele->quality_id, $ele->type_id);
            }

            return FacadesDataTables::of($query)
                // ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    $btn = 'No Name';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->kit->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->wheel->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->tractor->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->clark->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->equip->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    }
                })
                ->addColumn('Image', function ($row) {
                    $btn = 'No Image';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if(isset($row->stores_log->all_parts[0]->part->part_images) && count($row->stores_log->all_parts[0]->part->part_images) >0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/part_images/'.$row->stores_log->all_parts[0]->part->part_images[0]->image_name.'" alt="">';
                                }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if(isset($row->stores_log->all_kits[0]->kit->kit_image) && count($row->stores_log->all_kits[0]->kit->kit_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/kit_images/'.$row->stores_log->all_kits[0]->kit->kit_image[0]->image_name.'" alt="">';
                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if(isset($row->stores_log->all_wheels[0]->wheel->wheel_image)  && count($row->stores_log->all_wheels[0]->wheel->wheel_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/wheel_images/'.$row->stores_log->all_wheels[0]->wheel->wheel_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if(isset($row->stores_log->all_tractors[0]->tractor->tractor_image) && count($row->stores_log->all_tractors[0]->tractor->tractor_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/tractor_images/'.$row->stores_log->all_tractors[0]->tractor->tractor_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if(isset($row->stores_log->all_clarks[0]->clarck->clarck_image)  && count($row->stores_log->all_clarks[0]->clarck->clarck_image)>0  ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/clarck_images/'.$row->stores_log->all_clarks[0]->clarck->clarck_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if(isset($row->stores_log->all_equips[0]->equip->equip_image) && count($row->stores_log->all_equips[0]->equip->equip_image)>0  ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/equip_images/'.$row->stores_log->all_equips[0]->equip->equip_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    }
                })
                ->addColumn('source', function ($row) {
                    $btn = 'No Source';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    }


                    return $btn;
                })
                ->addColumn('status', function ($row) {
                    $btn = 'No Status';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    }

                    return $btn;
                })
                ->addColumn('quality', function ($row) {
                    $btn = 'No Quality';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    }
                    return $btn;
                })
                ->addColumn('amount', function ($row) {
                    $btn = 0;
                    if ($row->amount > 0) {
                        $btn = $row->amount;
                    } else {
                        $btn = 0;
                    }
                    return $btn;
                })
                ->addColumn('section', function ($row) {
                    $btn = '';
                    $secArr = [];
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->sectionswithoutorder) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->sectionswithoutorder); $i++) {
                                    if (in_array($row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->amount <= 0) {


                                    } else {

                                        $btn .= '<li>' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->amount  . '</span></li>';
                                        array_push($secArr, $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->id);
                                    }
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->sectionswithoutorder)) {
                                if (count($row->stores_log->all_kits[0]->sectionswithoutorder) > 0) {
                                    for ($i = 0; $i < count($row->stores_log->all_kits[0]->sectionswithoutorder); $i++) {
                                        if (in_array($row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->amount <= 0) {
                                        } else {

                                            $btn .= '<li>' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                            array_push($secArr, $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->id);
                                        }
                                    }
                                }
                            } else {
                                $btn = "No Section";
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (isset($row->stores_log->all_wheels[0]->sectionswithoutorder)) {
                                if (count($row->stores_log->all_wheels[0]->sectionswithoutorder) > 0) {
                                    for ($i = 0; $i < count($row->stores_log->all_wheels[0]->sectionswithoutorder); $i++) {
                                        if (in_array($row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->amount <= 0) {
                                        } else {

                                            $btn .= '<li>' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                            array_push($secArr, $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->id);
                                        }
                                    }
                                }
                            } else {
                                $btn = "No Section";
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (count($row->stores_log->all_tractors[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_tractors[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_tractors[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_tractors[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (count($row->stores_log->all_clarks[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_clarks[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_clarks[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_clarks[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (count($row->stores_log->all_equips[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_equips[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_equips[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_equips[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    }
                    return $btn;
                })
                ->addColumn('price', function ($row) {
                    $btn = 0;
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                $dataArr =collect($row->stores_log->all_parts[0]->pricing);


                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;
                                if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->pricing)) {
                                if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                    $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                      $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (isset($row->stores_log->all_equips[0]->pricing)) {
                                if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_equips[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    }

                    return $btn;
                })

                ->addColumn('partnumbers', function ($row) {
                    $btn = 'no num';
                    if ($row->type_id == 1) {
                        if (isset($row->stores_log->all_parts[0]->part->part_numbers)) {
                            if (count($row->stores_log->all_parts[0]->part->part_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->part->part_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_parts[0]->part->part_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 6) {
                        if (isset($row->stores_log->all_kits[0]->kit->kit_numbers)) {
                            if (count($row->stores_log->all_kits[0]->kit->kit_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_kits[0]->kit->kit_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_kits[0]->kit->kit_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 2) {
                    } elseif ($row->type_id == 3) {
                    } elseif ($row->type_id == 4) {
                    } elseif ($row->type_id == 5) {
                    }

                    return $btn;
                })
                ->addColumn('stores_amount', function ($row) {
                    $btn = '<ul class="list-group fs-13 text-truncate">';
                    if(isset($row->stores_amount)){
                        for ($i=0; $i < count($row->stores_amount) ; $i++) {
                            $btn .='<li>'.$row->stores_amount[$i]->name.'/'.$row->stores_amount[$i]->storepartCount.'</li>';

                        }
                    }else{
                        $btn .='<li>لا يوجد بالمخازن</li>';

                    }

                    $btn .= '</ul>';
                    return  $btn;

                })
                ->addColumn('view', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_parts[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_kits[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_wheels[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_tractors[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_clarks[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_equips[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    }

                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_parts) > 0) {
                                if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                    // $price = $row->stores_log->all_parts[0]->pricing[0]->price;

                                     $dataArr =collect($row->stores_log->all_parts[0]->pricing);
                                     $maxObject = $dataArr->sortByDesc('price')->first();


                                    $price =$maxObject->price ;
                                    $SaleType = $maxObject->sale_type;

                                    $prices = $row->stores_log->all_parts[0]->pricing;
                                    $prices_json = json_encode($prices);
                                }

                                if (count($row->stores_log->all_parts[0]->part->part_details) > 0) {
                                    $part_specs = $row->stores_log->all_parts[0]->part->part_details;
                                    $part_specs_json = json_encode($part_specs);
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_parts[0]->part->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_parts[0]->source_id) . ', ' . json_encode($row->stores_log->all_parts[0]->status_id) . ', ' . json_encode($row->stores_log->all_parts[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_kits) > 0) {
                                if (isset($row->stores_log->all_kits[0]->pricing)) {
                                    if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_kits[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_kits[0]->pricing[0]->price;
                                        $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;

                                        $prices = $row->stores_log->all_kits[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_kits[0]->kit->kit_details)) {
                                    if (count($row->stores_log->all_kits[0]->kit->kit_details) > 0) {
                                        $part_specs = $row->stores_log->all_kits[0]->kit->kit_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_kits[0]->kit->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_kits[0]->source_id) . ', ' . json_encode($row->stores_log->all_kits[0]->status_id) . ', ' . json_encode($row->stores_log->all_kits[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_wheels) > 0) {
                                if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                    if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_wheels[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_wheels[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_wheels[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_wheels[0]->wheel->wheel_details)) {
                                    if (count($row->stores_log->all_wheels[0]->wheel->wheel_details) > 0) {
                                        $part_specs = $row->stores_log->all_wheels[0]->wheel->wheel_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_wheels[0]->wheel->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_wheels[0]->source_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->status_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_tractors) > 0) {
                                if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                    if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_tractors[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_tractors[0]->pricing[0]->price;
                                          $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_tractors[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_tractors[0]->tractor->tractor_details)) {
                                    if (count($row->stores_log->all_tractors[0]->tractor->tractor_details) > 0) {
                                        $part_specs = $row->stores_log->all_tractors[0]->tractor->tractor_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_tractors[0]->tractor->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_tractors[0]->source_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->status_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_clarks) > 0) {
                                if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                    if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_clarks[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_clarks[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_clarks[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_clarks[0]->clark->clark_details)) {
                                    if (count($row->stores_log->all_clarks[0]->clark->clark_details) > 0) {
                                        $part_specs = $row->stores_log->all_clarks[0]->clark->clark_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_clarks[0]->clark->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_clarks[0]->source_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->status_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_equips) > 0) {
                                if (isset($row->stores_log->all_equips[0]->pricing)) {
                                    if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_equips[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_equips[0]->pricing[0]->price;
                                        $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_equips[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_equips[0]->equip->equip_details)) {
                                    if (count($row->stores_log->all_equips[0]->equip->equip_details) > 0) {
                                        $part_specs = $row->stores_log->all_equips[0]->equip->equip_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_equips[0]->equip->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_equips[0]->source_id) . ', ' . json_encode($row->stores_log->all_equips[0]->status_id) . ', ' . json_encode($row->stores_log->all_equips[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ', 1,' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    }
                    return '';
                })
                ->addColumn('send', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    }

                    return $btn;
                })
                ->addColumn('ask', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="askStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="askStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="askStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="askStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="askStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="askStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    }

                    return $btn;
                })
                ->addColumn('talef', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';


                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $store_id = $row->stores_log->store_id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                        $partId . '/' .
                        $sourceId . '/' .
                        $statusId . '/' .
                        $partQualityId . '/' .
                        $typeId . '/' .
                        $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $store_id = $row->stores_log->store_id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    }

                    return $btn;
                })
                ->rawColumns(['stores_amount','view','Image' ,'name', 'source', 'status', 'quality', 'amount', 'section', 'price', 'partnumbers', 'action', 'send','ask','talef'])
                ->setTotalRecords(20)
                // ->filter(function ($query) {
                //     return $query;
                // })
                ->make(true);
        }
        // return view('testpos');
    }

    public function allDataForIdModel(Request $request)
    {

        // return $request;
        if ($request->ajax()) {

            ////           Get Store Entity /////////////////
            $store = Store::where('id', $request->storeId)->first();

            $entity_tbl = 'App\\Models\\' . ucfirst($store->table_name);
            if ($store->table_name == 'damaged_parts') {
                $entity_tbl = 'damagedPart';
            }

            /*********************************************/
            // return $entity_tbl;

            $numberVal = $request->searchkey;
            $query = '';
            if ($request->typeId == 1) {


                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                    // ->where($store->table_name . '.part_id', $request->PartID)
                    ->whereIn($store->table_name . '.part_id', function ($query) use ($numberVal) {
                        $query->from('part_model')
                            ->join('series', 'part_model.model_id', '=', 'series.id')

                            ->select('part_model.part_id')
                            ->where('series.model_id', '=', $numberVal);
                    })
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_parts.part_id, all_parts.source_id, all_parts.status_id, all_parts.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_parts.part_id', 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id',  $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_parts.part.part_numbers',
                        'stores_log.all_parts.part.part_images',
                        'stores_log.all_parts.source',
                        'stores_log.all_parts.status',
                        'stores_log.all_parts.part_quality',
                        'stores_log.all_parts.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_parts.sectionswithoutorder.store',
                        'stores_log.all_parts.sectionswithoutorder.store_structure',
                        'stores_log.all_parts.pricing.sale_type',
                        'stores_log.all_parts.order_supplier.buy_transaction',
                        'stores_log.all_parts.part.sub_group.group',
                        'stores_log.all_parts.part.part_details.part_spec',
                        'stores_log.all_parts.part.part_models.series.model.brand',
                        'stores_log.all_parts.part.part_images',
                    ])
                    ->get();
            } elseif ($request->typeId == 6) {
                $query = $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_kits.part_id, all_kits.source_id, all_kits.status_id, all_kits.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_kits.part_id', 'all_kits.source_id', 'all_kits.status_id', 'all_kits.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_kits.kit.kit_numbers',
                        'stores_log.all_kits.kit.kit_images',
                        'stores_log.all_kits.source',
                        'stores_log.all_kits.status',
                        'stores_log.all_kits.part_quality',
                        'stores_log.all_kits.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_kits.sectionswithoutorder.store',
                        'stores_log.all_kits.sectionswithoutorder.store_structure',
                        'stores_log.all_kits.pricing.sale_type',
                        'stores_log.all_kits.order_supplier.buy_transaction',
                        'stores_log.all_kits.kit.kit_details.kit_spec',
                        'stores_log.all_kits.kit.kit_models.series.model.brand',
                        'stores_log.all_kits.kit.kit_images',
                    ])
                    ->get();
            } elseif ($request->typeId == 2) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_wheels.part_id, all_wheels.source_id, all_wheels.status_id, all_wheels.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_wheels.part_id', 'all_wheels.source_id', 'all_wheels.status_id', 'all_wheels.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_wheels.wheel.wheel_images',
                        'stores_log.all_wheels.source',
                        'stores_log.all_wheels.status',
                        'stores_log.all_wheels.part_quality',
                        'stores_log.all_wheels.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_wheels.sectionswithoutorder.store',
                        'stores_log.all_wheels.sectionswithoutorder.store_structure',
                        'stores_log.all_wheels.pricing.sale_type',
                        'stores_log.all_wheels.order_supplier.buy_transaction',
                        'stores_log.all_wheels.wheel.wheel_details.wheel_spec',
                        'stores_log.all_wheels.wheel.wheel_model',
                        'stores_log.all_wheels.wheel.wheel_images',
                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 3) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_tractors', 'stores_log.All_part_id', '=', 'all_tractors.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_tractors.part_id, all_tractors.source_id, all_tractors.status_id, all_tractors.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_tractors.part_id', 'all_tractors.source_id', 'all_tractors.status_id', 'all_tractors.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_tractors.tractor.tractor_images',
                        'stores_log.all_tractors.source',
                        'stores_log.all_tractors.status',
                        'stores_log.all_tractors.part_quality',
                        'stores_log.all_tractors.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_tractors.sectionswithoutorder.store',
                        'stores_log.all_tractors.sectionswithoutorder.store_structure',
                        'stores_log.all_tractors.pricing.sale_type',
                        'stores_log.all_tractors.order_supplier.buy_transaction',
                        'stores_log.all_tractors.tractor.tractor_details.tractor_spec',
                        'stores_log.all_tractors.tractor.tractor_images',



                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 4) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_clarks', 'stores_log.All_part_id', '=', 'all_clarks.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_clarks.part_id, all_clarks.source_id, all_clarks.status_id, all_clarks.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_clarks.part_id', 'all_clarks.source_id', 'all_clarks.status_id', 'all_clarks.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_clarks.clark.clark_images',
                        'stores_log.all_clarks.source',
                        'stores_log.all_clarks.status',
                        'stores_log.all_clarks.part_quality',
                        'stores_log.all_clarks.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_clarks.sectionswithoutorder.store',
                        'stores_log.all_clarks.sectionswithoutorder.store_structure',
                        'stores_log.all_clarks.pricing.sale_type',
                        'stores_log.all_clarks.order_supplier.buy_transaction',
                        'stores_log.all_clarks.clark.clark_details.clark_spec',
                        'stores_log.all_clarks.clark.clark_images',
                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 5) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_equips', 'stores_log.All_part_id', '=', 'all_equips.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_equips.part_id, all_equips.source_id, all_equips.status_id, all_equips.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_equips.part_id', 'all_equips.source_id', 'all_equips.status_id', 'all_equips.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_equips.equip.equip_images',
                        'stores_log.all_equips.source',
                        'stores_log.all_equips.status',
                        'stores_log.all_equips.part_quality',
                        'stores_log.all_equips.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_equips.sectionswithoutorder.store',
                        'stores_log.all_equips.sectionswithoutorder.store_structure',
                        'stores_log.all_equips.pricing.sale_type',
                        'stores_log.all_equips.order_supplier.buy_transaction',
                        'stores_log.all_equips.equip.equip_details.equip_spec',
                        'stores_log.all_equips.equip.equip_images',
                    ])
                    ->get();
                // Merge all queries
            }

            // return $query;

            foreach ($query as $ele) {
                # code...
                $ele['stores_amount']=$this->PartInStoresCount($ele->part_id , $ele->source_id, $ele->status_id, $ele->quality_id, $ele->type_id);
            }


            return FacadesDataTables::of($query)
                // ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    $btn = 'No Name';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->kit->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->wheel->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->tractor->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->clark->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->equip->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    }
                })
                ->addColumn('Image', function ($row) {
                    $btn = 'No Image';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if(isset($row->stores_log->all_parts[0]->part->part_images) && count($row->stores_log->all_parts[0]->part->part_images) >0 ){
                            $btn = '<img class="rounded-circle header-profile-user" src="assets/part_images/'.$row->stores_log->all_parts[0]->part->part_images[0]->image_name.'" alt="">';
                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if(isset($row->stores_log->all_kits[0]->kit->kit_image) && count($row->stores_log->all_kits[0]->kit->kit_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/kit_images/'.$row->stores_log->all_kits[0]->kit->kit_image[0]->image_name.'" alt="">';
                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if(isset($row->stores_log->all_wheels[0]->wheel->wheel_image)  && count($row->stores_log->all_wheels[0]->wheel->wheel_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/wheel_images/'.$row->stores_log->all_wheels[0]->wheel->wheel_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if(isset($row->stores_log->all_tractors[0]->tractor->tractor_image) && count($row->stores_log->all_tractors[0]->tractor->tractor_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/tractor_images/'.$row->stores_log->all_tractors[0]->tractor->tractor_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if(isset($row->stores_log->all_clarks[0]->clarck->clarck_image)  && count($row->stores_log->all_clarks[0]->clarck->clarck_image)>0  ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/clarck_images/'.$row->stores_log->all_clarks[0]->clarck->clarck_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if(isset($row->stores_log->all_equips[0]->equip->equip_image) && count($row->stores_log->all_equips[0]->equip->equip_image)>0  ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/equip_images/'.$row->stores_log->all_equips[0]->equip->equip_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    }
                })
                ->addColumn('source', function ($row) {
                    $btn = 'No Source';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    }


                    return $btn;
                })
                ->addColumn('status', function ($row) {
                    $btn = 'No Status';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    }

                    return $btn;
                })
                ->addColumn('quality', function ($row) {
                    $btn = 'No Quality';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    }
                    return $btn;
                })
                ->addColumn('amount', function ($row) {
                    $btn = 0;
                    if ($row->amount > 0) {
                        $btn = $row->amount;
                    } else {
                        $btn = 0;
                    }
                    return $btn;
                })
                ->addColumn('section', function ($row) {
                    $btn = '';
                    $secArr = [];
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->sectionswithoutorder) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->sectionswithoutorder); $i++) {
                                    if (in_array($row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->amount <= 0) {


                                    } else {

                                        $btn .= '<li>' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->amount  . '</span></li>';
                                        array_push($secArr, $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->id);
                                    }
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->sectionswithoutorder)) {
                                if (count($row->stores_log->all_kits[0]->sectionswithoutorder) > 0) {
                                    for ($i = 0; $i < count($row->stores_log->all_kits[0]->sectionswithoutorder); $i++) {
                                        if (in_array($row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->amount <= 0) {
                                        } else {

                                            $btn .= '<li>' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                            array_push($secArr, $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->id);
                                        }
                                    }
                                }
                            } else {
                                $btn = "No Section";
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (isset($row->stores_log->all_wheels[0]->sectionswithoutorder)) {
                                if (count($row->stores_log->all_wheels[0]->sectionswithoutorder) > 0) {
                                    for ($i = 0; $i < count($row->stores_log->all_wheels[0]->sectionswithoutorder); $i++) {
                                        if (in_array($row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->amount <= 0) {
                                        } else {

                                            $btn .= '<li>' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                            array_push($secArr, $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->id);
                                        }
                                    }
                                }
                            } else {
                                $btn = "No Section";
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (count($row->stores_log->all_tractors[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_tractors[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_tractors[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_tractors[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (count($row->stores_log->all_clarks[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_clarks[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_clarks[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_clarks[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (count($row->stores_log->all_equips[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_equips[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_equips[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_equips[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    }
                    return $btn;
                })
                ->addColumn('price', function ($row) {
                    $btn = 0;
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                $dataArr =collect($row->stores_log->all_parts[0]->pricing);


                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;
                                if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->pricing)) {
                                if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                    $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                      $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (isset($row->stores_log->all_equips[0]->pricing)) {
                                if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_equips[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    }

                    return $btn;
                })

                ->addColumn('partnumbers', function ($row) {
                    $btn = 'no num';
                    if ($row->type_id == 1) {
                        if (isset($row->stores_log->all_parts[0]->part->part_numbers)) {
                            if (count($row->stores_log->all_parts[0]->part->part_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->part->part_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_parts[0]->part->part_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 6) {
                        if (isset($row->stores_log->all_kits[0]->kit->kit_numbers)) {
                            if (count($row->stores_log->all_kits[0]->kit->kit_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_kits[0]->kit->kit_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_kits[0]->kit->kit_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 2) {
                    } elseif ($row->type_id == 3) {
                    } elseif ($row->type_id == 4) {
                    } elseif ($row->type_id == 5) {
                    }

                    return $btn;
                })
                ->addColumn('stores_amount', function ($row) {
                    $btn = '<ul class="list-group fs-13 text-truncate">';
                    if(isset($row->stores_amount)){
                        for ($i=0; $i < count($row->stores_amount) ; $i++) {
                            $btn .='<li>'.$row->stores_amount[$i]->name.'/'.$row->stores_amount[$i]->storepartCount.'</li>';

                        }
                    }else{
                        $btn .='<li>لا يوجد بالمخازن</li>';

                    }

                    $btn .= '</ul>';
                    return  $btn;

                })
                ->addColumn('view', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_parts[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_kits[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_wheels[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_tractors[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_clarks[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_equips[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    }

                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_parts) > 0) {
                                if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                    // $price = $row->stores_log->all_parts[0]->pricing[0]->price;

                                     $dataArr =collect($row->stores_log->all_parts[0]->pricing);
                                     $maxObject = $dataArr->sortByDesc('price')->first();


                                    $price =$maxObject->price ;
                                    $SaleType = $maxObject->sale_type;

                                    $prices = $row->stores_log->all_parts[0]->pricing;
                                    $prices_json = json_encode($prices);
                                }

                                if (count($row->stores_log->all_parts[0]->part->part_details) > 0) {
                                    $part_specs = $row->stores_log->all_parts[0]->part->part_details;
                                    $part_specs_json = json_encode($part_specs);
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_parts[0]->part->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_parts[0]->source_id) . ', ' . json_encode($row->stores_log->all_parts[0]->status_id) . ', ' . json_encode($row->stores_log->all_parts[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_kits) > 0) {
                                if (isset($row->stores_log->all_kits[0]->pricing)) {
                                    if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_kits[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_kits[0]->pricing[0]->price;
                                        $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;

                                        $prices = $row->stores_log->all_kits[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_kits[0]->kit->kit_details)) {
                                    if (count($row->stores_log->all_kits[0]->kit->kit_details) > 0) {
                                        $part_specs = $row->stores_log->all_kits[0]->kit->kit_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_kits[0]->kit->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_kits[0]->source_id) . ', ' . json_encode($row->stores_log->all_kits[0]->status_id) . ', ' . json_encode($row->stores_log->all_kits[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_wheels) > 0) {
                                if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                    if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_wheels[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_wheels[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_wheels[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_wheels[0]->wheel->wheel_details)) {
                                    if (count($row->stores_log->all_wheels[0]->wheel->wheel_details) > 0) {
                                        $part_specs = $row->stores_log->all_wheels[0]->wheel->wheel_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_wheels[0]->wheel->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_wheels[0]->source_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->status_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_tractors) > 0) {
                                if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                    if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_tractors[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_tractors[0]->pricing[0]->price;
                                          $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_tractors[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_tractors[0]->tractor->tractor_details)) {
                                    if (count($row->stores_log->all_tractors[0]->tractor->tractor_details) > 0) {
                                        $part_specs = $row->stores_log->all_tractors[0]->tractor->tractor_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_tractors[0]->tractor->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_tractors[0]->source_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->status_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_clarks) > 0) {
                                if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                    if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_clarks[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_clarks[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_clarks[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_clarks[0]->clark->clark_details)) {
                                    if (count($row->stores_log->all_clarks[0]->clark->clark_details) > 0) {
                                        $part_specs = $row->stores_log->all_clarks[0]->clark->clark_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_clarks[0]->clark->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_clarks[0]->source_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->status_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_equips) > 0) {
                                if (isset($row->stores_log->all_equips[0]->pricing)) {
                                    if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_equips[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_equips[0]->pricing[0]->price;
                                        $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_equips[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_equips[0]->equip->equip_details)) {
                                    if (count($row->stores_log->all_equips[0]->equip->equip_details) > 0) {
                                        $part_specs = $row->stores_log->all_equips[0]->equip->equip_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_equips[0]->equip->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_equips[0]->source_id) . ', ' . json_encode($row->stores_log->all_equips[0]->status_id) . ', ' . json_encode($row->stores_log->all_equips[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ', 1,' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    }
                    return '';
                })
                ->addColumn('send', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    }

                    return $btn;
                })
                ->addColumn('ask', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="askStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="askStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="askStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="askStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="askStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="askStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    }

                    return $btn;
                })
                ->addColumn('talef', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';


                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $store_id = $row->stores_log->store_id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                        $partId . '/' .
                        $sourceId . '/' .
                        $statusId . '/' .
                        $partQualityId . '/' .
                        $typeId . '/' .
                        $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $store_id = $row->stores_log->store_id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    }

                    return $btn;
                })
                ->rawColumns(['stores_amount','view','Image' ,'name', 'source', 'status', 'quality', 'amount', 'section', 'price', 'partnumbers', 'action', 'send','ask','talef'])
                ->setTotalRecords(20)
                // ->filter(function ($query) {
                //     return $query;
                // })
                ->make(true);
        }
        // return view('testpos');
    }

    public function allDataForIdSeries(Request $request)
    {

        // return $request;
        if ($request->ajax()) {

            ////           Get Store Entity /////////////////
            $store = Store::where('id', $request->storeId)->first();

            $entity_tbl = 'App\\Models\\' . ucfirst($store->table_name);
            if ($store->table_name == 'damaged_parts') {
                $entity_tbl = 'damagedPart';
            }

            /*********************************************/
            // return $entity_tbl;

            $numberVal = $request->searchkey;
            $query = '';
            if ($request->typeId == 1) {


                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                    // ->where($store->table_name . '.part_id', $request->PartID)
                    ->whereIn($store->table_name . '.part_id', function ($query) use ($numberVal) {
                        $query->from('part_model')
                        ->select('part_model.part_id')
                        ->where('model_id', '=', $numberVal);
                    })
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_parts.part_id, all_parts.source_id, all_parts.status_id, all_parts.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_parts.part_id', 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id',  $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_parts.part.part_numbers',
                        'stores_log.all_parts.part.part_images',
                        'stores_log.all_parts.source',
                        'stores_log.all_parts.status',
                        'stores_log.all_parts.part_quality',
                        'stores_log.all_parts.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_parts.sectionswithoutorder.store',
                        'stores_log.all_parts.sectionswithoutorder.store_structure',
                        'stores_log.all_parts.pricing.sale_type',
                        'stores_log.all_parts.order_supplier.buy_transaction',
                        'stores_log.all_parts.part.sub_group.group',
                        'stores_log.all_parts.part.part_details.part_spec',
                        'stores_log.all_parts.part.part_models.series.model.brand',
                        'stores_log.all_parts.part.part_images',
                    ])
                    ->get();
            } elseif ($request->typeId == 6) {
                $query = $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_kits.part_id, all_kits.source_id, all_kits.status_id, all_kits.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_kits.part_id', 'all_kits.source_id', 'all_kits.status_id', 'all_kits.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_kits.kit.kit_numbers',
                        'stores_log.all_kits.kit.kit_images',
                        'stores_log.all_kits.source',
                        'stores_log.all_kits.status',
                        'stores_log.all_kits.part_quality',
                        'stores_log.all_kits.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_kits.sectionswithoutorder.store',
                        'stores_log.all_kits.sectionswithoutorder.store_structure',
                        'stores_log.all_kits.pricing.sale_type',
                        'stores_log.all_kits.order_supplier.buy_transaction',
                        'stores_log.all_kits.kit.kit_details.kit_spec',
                        'stores_log.all_kits.kit.kit_models.series.model.brand',
                        'stores_log.all_kits.kit.kit_images',
                    ])
                    ->get();
            } elseif ($request->typeId == 2) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_wheels.part_id, all_wheels.source_id, all_wheels.status_id, all_wheels.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_wheels.part_id', 'all_wheels.source_id', 'all_wheels.status_id', 'all_wheels.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_wheels.wheel.wheel_images',
                        'stores_log.all_wheels.source',
                        'stores_log.all_wheels.status',
                        'stores_log.all_wheels.part_quality',
                        'stores_log.all_wheels.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_wheels.sectionswithoutorder.store',
                        'stores_log.all_wheels.sectionswithoutorder.store_structure',
                        'stores_log.all_wheels.pricing.sale_type',
                        'stores_log.all_wheels.order_supplier.buy_transaction',
                        'stores_log.all_wheels.wheel.wheel_details.wheel_spec',
                        'stores_log.all_wheels.wheel.wheel_model',
                        'stores_log.all_wheels.wheel.wheel_images',
                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 3) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_tractors', 'stores_log.All_part_id', '=', 'all_tractors.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_tractors.part_id, all_tractors.source_id, all_tractors.status_id, all_tractors.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_tractors.part_id', 'all_tractors.source_id', 'all_tractors.status_id', 'all_tractors.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_tractors.tractor.tractor_images',
                        'stores_log.all_tractors.source',
                        'stores_log.all_tractors.status',
                        'stores_log.all_tractors.part_quality',
                        'stores_log.all_tractors.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_tractors.sectionswithoutorder.store',
                        'stores_log.all_tractors.sectionswithoutorder.store_structure',
                        'stores_log.all_tractors.pricing.sale_type',
                        'stores_log.all_tractors.order_supplier.buy_transaction',
                        'stores_log.all_tractors.tractor.tractor_details.tractor_spec',
                        'stores_log.all_tractors.tractor.tractor_images',



                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 4) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_clarks', 'stores_log.All_part_id', '=', 'all_clarks.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_clarks.part_id, all_clarks.source_id, all_clarks.status_id, all_clarks.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_clarks.part_id', 'all_clarks.source_id', 'all_clarks.status_id', 'all_clarks.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_clarks.clark.clark_images',
                        'stores_log.all_clarks.source',
                        'stores_log.all_clarks.status',
                        'stores_log.all_clarks.part_quality',
                        'stores_log.all_clarks.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_clarks.sectionswithoutorder.store',
                        'stores_log.all_clarks.sectionswithoutorder.store_structure',
                        'stores_log.all_clarks.pricing.sale_type',
                        'stores_log.all_clarks.order_supplier.buy_transaction',
                        'stores_log.all_clarks.clark.clark_details.clark_spec',
                        'stores_log.all_clarks.clark.clark_images',
                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 5) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_equips', 'stores_log.All_part_id', '=', 'all_equips.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_equips.part_id, all_equips.source_id, all_equips.status_id, all_equips.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_equips.part_id', 'all_equips.source_id', 'all_equips.status_id', 'all_equips.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_equips.equip.equip_images',
                        'stores_log.all_equips.source',
                        'stores_log.all_equips.status',
                        'stores_log.all_equips.part_quality',
                        'stores_log.all_equips.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_equips.sectionswithoutorder.store',
                        'stores_log.all_equips.sectionswithoutorder.store_structure',
                        'stores_log.all_equips.pricing.sale_type',
                        'stores_log.all_equips.order_supplier.buy_transaction',
                        'stores_log.all_equips.equip.equip_details.equip_spec',
                        'stores_log.all_equips.equip.equip_images',
                    ])
                    ->get();
                // Merge all queries
            }
            foreach ($query as $ele) {
                # code...
                $ele['stores_amount']=$this->PartInStoresCount($ele->part_id , $ele->source_id, $ele->status_id, $ele->quality_id, $ele->type_id);
            }

            // return $query;




            return FacadesDataTables::of($query)
                // ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    $btn = 'No Name';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->kit->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->wheel->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->tractor->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->clark->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->equip->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    }
                })
                ->addColumn('Image', function ($row) {
                    $btn = 'No Image';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if(isset($row->stores_log->all_parts[0]->part->part_images) && count($row->stores_log->all_parts[0]->part->part_images) >0){
                            $btn = '<img class="rounded-circle header-profile-user" src="assets/part_images/'.$row->stores_log->all_parts[0]->part->part_images[0]->image_name.'" alt="">';
                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if(isset($row->stores_log->all_kits[0]->kit->kit_image) && count($row->stores_log->all_kits[0]->kit->kit_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/kit_images/'.$row->stores_log->all_kits[0]->kit->kit_image[0]->image_name.'" alt="">';
                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if(isset($row->stores_log->all_wheels[0]->wheel->wheel_image)  && count($row->stores_log->all_wheels[0]->wheel->wheel_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/wheel_images/'.$row->stores_log->all_wheels[0]->wheel->wheel_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if(isset($row->stores_log->all_tractors[0]->tractor->tractor_image) && count($row->stores_log->all_tractors[0]->tractor->tractor_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/tractor_images/'.$row->stores_log->all_tractors[0]->tractor->tractor_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if(isset($row->stores_log->all_clarks[0]->clarck->clarck_image)  && count($row->stores_log->all_clarks[0]->clarck->clarck_image)>0  ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/clarck_images/'.$row->stores_log->all_clarks[0]->clarck->clarck_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if(isset($row->stores_log->all_equips[0]->equip->equip_image) && count($row->stores_log->all_equips[0]->equip->equip_image)>0  ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/equip_images/'.$row->stores_log->all_equips[0]->equip->equip_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    }
                })
                ->addColumn('source', function ($row) {
                    $btn = 'No Source';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    }


                    return $btn;
                })
                ->addColumn('status', function ($row) {
                    $btn = 'No Status';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    }

                    return $btn;
                })
                ->addColumn('quality', function ($row) {
                    $btn = 'No Quality';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    }
                    return $btn;
                })
                ->addColumn('amount', function ($row) {
                    $btn = 0;
                    if ($row->amount > 0) {
                        $btn = $row->amount;
                    } else {
                        $btn = 0;
                    }
                    return $btn;
                })
                ->addColumn('section', function ($row) {
                    $btn = '';
                    $secArr = [];
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->sectionswithoutorder) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->sectionswithoutorder); $i++) {
                                    if (in_array($row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->amount <= 0) {


                                    } else {

                                        $btn .= '<li>' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->amount  . '</span></li>';
                                        array_push($secArr, $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->id);
                                    }
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->sectionswithoutorder)) {
                                if (count($row->stores_log->all_kits[0]->sectionswithoutorder) > 0) {
                                    for ($i = 0; $i < count($row->stores_log->all_kits[0]->sectionswithoutorder); $i++) {
                                        if (in_array($row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->amount <= 0) {
                                        } else {

                                            $btn .= '<li>' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                            array_push($secArr, $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->id);
                                        }
                                    }
                                }
                            } else {
                                $btn = "No Section";
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (isset($row->stores_log->all_wheels[0]->sectionswithoutorder)) {
                                if (count($row->stores_log->all_wheels[0]->sectionswithoutorder) > 0) {
                                    for ($i = 0; $i < count($row->stores_log->all_wheels[0]->sectionswithoutorder); $i++) {
                                        if (in_array($row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->amount <= 0) {
                                        } else {

                                            $btn .= '<li>' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                            array_push($secArr, $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->id);
                                        }
                                    }
                                }
                            } else {
                                $btn = "No Section";
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (count($row->stores_log->all_tractors[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_tractors[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_tractors[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_tractors[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (count($row->stores_log->all_clarks[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_clarks[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_clarks[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_clarks[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (count($row->stores_log->all_equips[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_equips[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_equips[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_equips[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    }
                    return $btn;
                })
                ->addColumn('price', function ($row) {
                    $btn = 0;
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                $dataArr =collect($row->stores_log->all_parts[0]->pricing);


                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;
                                if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->pricing)) {
                                if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                    $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                      $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (isset($row->stores_log->all_equips[0]->pricing)) {
                                if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_equips[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    }

                    return $btn;
                })

                ->addColumn('partnumbers', function ($row) {
                    $btn = 'no num';
                    if ($row->type_id == 1) {
                        if (isset($row->stores_log->all_parts[0]->part->part_numbers)) {
                            if (count($row->stores_log->all_parts[0]->part->part_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->part->part_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_parts[0]->part->part_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 6) {
                        if (isset($row->stores_log->all_kits[0]->kit->kit_numbers)) {
                            if (count($row->stores_log->all_kits[0]->kit->kit_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_kits[0]->kit->kit_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_kits[0]->kit->kit_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 2) {
                    } elseif ($row->type_id == 3) {
                    } elseif ($row->type_id == 4) {
                    } elseif ($row->type_id == 5) {
                    }

                    return $btn;
                })
                ->addColumn('stores_amount', function ($row) {
                    $btn = '<ul class="list-group fs-13 text-truncate">';
                    if(isset($row->stores_amount)){
                        for ($i=0; $i < count($row->stores_amount) ; $i++) {
                            $btn .='<li>'.$row->stores_amount[$i]->name.'/'.$row->stores_amount[$i]->storepartCount.'</li>';

                        }
                    }else{
                        $btn .='<li>لا يوجد بالمخازن</li>';

                    }

                    $btn .= '</ul>';
                    return  $btn;

                })


                ->addColumn('view', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_parts[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_kits[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_wheels[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_tractors[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_clarks[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_equips[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    }

                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_parts) > 0) {
                                if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                    // $price = $row->stores_log->all_parts[0]->pricing[0]->price;

                                     $dataArr =collect($row->stores_log->all_parts[0]->pricing);
                                     $maxObject = $dataArr->sortByDesc('price')->first();


                                    $price =$maxObject->price ;
                                    $SaleType = $maxObject->sale_type;

                                    $prices = $row->stores_log->all_parts[0]->pricing;
                                    $prices_json = json_encode($prices);
                                }

                                if (count($row->stores_log->all_parts[0]->part->part_details) > 0) {
                                    $part_specs = $row->stores_log->all_parts[0]->part->part_details;
                                    $part_specs_json = json_encode($part_specs);
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_parts[0]->part->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_parts[0]->source_id) . ', ' . json_encode($row->stores_log->all_parts[0]->status_id) . ', ' . json_encode($row->stores_log->all_parts[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_kits) > 0) {
                                if (isset($row->stores_log->all_kits[0]->pricing)) {
                                    if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_kits[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_kits[0]->pricing[0]->price;
                                        $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;

                                        $prices = $row->stores_log->all_kits[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_kits[0]->kit->kit_details)) {
                                    if (count($row->stores_log->all_kits[0]->kit->kit_details) > 0) {
                                        $part_specs = $row->stores_log->all_kits[0]->kit->kit_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_kits[0]->kit->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_kits[0]->source_id) . ', ' . json_encode($row->stores_log->all_kits[0]->status_id) . ', ' . json_encode($row->stores_log->all_kits[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_wheels) > 0) {
                                if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                    if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_wheels[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_wheels[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_wheels[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_wheels[0]->wheel->wheel_details)) {
                                    if (count($row->stores_log->all_wheels[0]->wheel->wheel_details) > 0) {
                                        $part_specs = $row->stores_log->all_wheels[0]->wheel->wheel_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_wheels[0]->wheel->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_wheels[0]->source_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->status_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_tractors) > 0) {
                                if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                    if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_tractors[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_tractors[0]->pricing[0]->price;
                                          $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_tractors[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_tractors[0]->tractor->tractor_details)) {
                                    if (count($row->stores_log->all_tractors[0]->tractor->tractor_details) > 0) {
                                        $part_specs = $row->stores_log->all_tractors[0]->tractor->tractor_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_tractors[0]->tractor->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_tractors[0]->source_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->status_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_clarks) > 0) {
                                if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                    if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_clarks[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_clarks[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_clarks[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_clarks[0]->clark->clark_details)) {
                                    if (count($row->stores_log->all_clarks[0]->clark->clark_details) > 0) {
                                        $part_specs = $row->stores_log->all_clarks[0]->clark->clark_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_clarks[0]->clark->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_clarks[0]->source_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->status_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_equips) > 0) {
                                if (isset($row->stores_log->all_equips[0]->pricing)) {
                                    if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_equips[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_equips[0]->pricing[0]->price;
                                        $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_equips[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_equips[0]->equip->equip_details)) {
                                    if (count($row->stores_log->all_equips[0]->equip->equip_details) > 0) {
                                        $part_specs = $row->stores_log->all_equips[0]->equip->equip_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_equips[0]->equip->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_equips[0]->source_id) . ', ' . json_encode($row->stores_log->all_equips[0]->status_id) . ', ' . json_encode($row->stores_log->all_equips[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ', 1,' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    }
                    return '';
                })
                ->addColumn('send', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    }

                    return $btn;
                })
                
                ->addColumn('talef', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';


                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $store_id = $row->stores_log->store_id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                        $partId . '/' .
                        $sourceId . '/' .
                        $statusId . '/' .
                        $partQualityId . '/' .
                        $typeId . '/' .
                        $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $store_id = $row->stores_log->store_id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    }

                    return $btn;
                })
                ->rawColumns(['stores_amount','view','Image' ,'name', 'source', 'status', 'quality', 'amount', 'section', 'price', 'partnumbers', 'action', 'send','talef'])
                ->setTotalRecords(20)
                // ->filter(function ($query) {
                //     return $query;
                // })
                ->make(true);
        }
        // return view('testpos');
    }
    public function allDataForIdGroup(Request $request)
    {

        // return $request;
        if ($request->ajax()) {

            ////           Get Store Entity /////////////////
            $store = Store::where('id', $request->storeId)->first();

            $entity_tbl = 'App\\Models\\' . ucfirst($store->table_name);
            if ($store->table_name == 'damaged_parts') {
                $entity_tbl = 'damagedPart';
            }

            /*********************************************/
            // return $entity_tbl;

            $numberVal = $request->searchkey;
            $query = '';
            if ($request->typeId == 1) {


                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                    // ->where($store->table_name . '.part_id', $request->PartID)
                    ->whereIn($store->table_name . '.part_id', function ($query) use ($numberVal) {
                        $query->from('part')
                        ->join('sub_group', 'part.sub_group_id', '=', 'sub_group.id')
                        ->select('part.id')
                        ->where('sub_group.group_id', '=', $numberVal);
                    })
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_parts.part_id, all_parts.source_id, all_parts.status_id, all_parts.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_parts.part_id', 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id',  $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_parts.part.part_numbers',
                        'stores_log.all_parts.part.part_images',
                        'stores_log.all_parts.source',
                        'stores_log.all_parts.status',
                        'stores_log.all_parts.part_quality',
                        'stores_log.all_parts.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_parts.sectionswithoutorder.store',
                        'stores_log.all_parts.sectionswithoutorder.store_structure',
                        'stores_log.all_parts.pricing.sale_type',
                        'stores_log.all_parts.order_supplier.buy_transaction',
                        'stores_log.all_parts.part.sub_group.group',
                        'stores_log.all_parts.part.part_details.part_spec',
                        'stores_log.all_parts.part.part_models.series.model.brand',
                        'stores_log.all_parts.part.part_images',
                    ])
                    ->get();
            } elseif ($request->typeId == 6) {
                $query = $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_kits.part_id, all_kits.source_id, all_kits.status_id, all_kits.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_kits.part_id', 'all_kits.source_id', 'all_kits.status_id', 'all_kits.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_kits.kit.kit_numbers',
                        'stores_log.all_kits.kit.kit_images',
                        'stores_log.all_kits.source',
                        'stores_log.all_kits.status',
                        'stores_log.all_kits.part_quality',
                        'stores_log.all_kits.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_kits.sectionswithoutorder.store',
                        'stores_log.all_kits.sectionswithoutorder.store_structure',
                        'stores_log.all_kits.pricing.sale_type',
                        'stores_log.all_kits.order_supplier.buy_transaction',
                        'stores_log.all_kits.kit.kit_details.kit_spec',
                        'stores_log.all_kits.kit.kit_models.series.model.brand',
                        'stores_log.all_kits.kit.kit_images',
                    ])
                    ->get();
            } elseif ($request->typeId == 2) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_wheels.part_id, all_wheels.source_id, all_wheels.status_id, all_wheels.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_wheels.part_id', 'all_wheels.source_id', 'all_wheels.status_id', 'all_wheels.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_wheels.wheel.wheel_images',
                        'stores_log.all_wheels.source',
                        'stores_log.all_wheels.status',
                        'stores_log.all_wheels.part_quality',
                        'stores_log.all_wheels.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_wheels.sectionswithoutorder.store',
                        'stores_log.all_wheels.sectionswithoutorder.store_structure',
                        'stores_log.all_wheels.pricing.sale_type',
                        'stores_log.all_wheels.order_supplier.buy_transaction',
                        'stores_log.all_wheels.wheel.wheel_details.wheel_spec',
                        'stores_log.all_wheels.wheel.wheel_model',
                        'stores_log.all_wheels.wheel.wheel_images',
                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 3) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_tractors', 'stores_log.All_part_id', '=', 'all_tractors.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_tractors.part_id, all_tractors.source_id, all_tractors.status_id, all_tractors.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_tractors.part_id', 'all_tractors.source_id', 'all_tractors.status_id', 'all_tractors.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_tractors.tractor.tractor_images',
                        'stores_log.all_tractors.source',
                        'stores_log.all_tractors.status',
                        'stores_log.all_tractors.part_quality',
                        'stores_log.all_tractors.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_tractors.sectionswithoutorder.store',
                        'stores_log.all_tractors.sectionswithoutorder.store_structure',
                        'stores_log.all_tractors.pricing.sale_type',
                        'stores_log.all_tractors.order_supplier.buy_transaction',
                        'stores_log.all_tractors.tractor.tractor_details.tractor_spec',
                        'stores_log.all_tractors.tractor.tractor_images',



                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 4) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_clarks', 'stores_log.All_part_id', '=', 'all_clarks.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_clarks.part_id, all_clarks.source_id, all_clarks.status_id, all_clarks.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_clarks.part_id', 'all_clarks.source_id', 'all_clarks.status_id', 'all_clarks.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_clarks.clark.clark_images',
                        'stores_log.all_clarks.source',
                        'stores_log.all_clarks.status',
                        'stores_log.all_clarks.part_quality',
                        'stores_log.all_clarks.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_clarks.sectionswithoutorder.store',
                        'stores_log.all_clarks.sectionswithoutorder.store_structure',
                        'stores_log.all_clarks.pricing.sale_type',
                        'stores_log.all_clarks.order_supplier.buy_transaction',
                        'stores_log.all_clarks.clark.clark_details.clark_spec',
                        'stores_log.all_clarks.clark.clark_images',
                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 5) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_equips', 'stores_log.All_part_id', '=', 'all_equips.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_equips.part_id, all_equips.source_id, all_equips.status_id, all_equips.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_equips.part_id', 'all_equips.source_id', 'all_equips.status_id', 'all_equips.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_equips.equip.equip_images',
                        'stores_log.all_equips.source',
                        'stores_log.all_equips.status',
                        'stores_log.all_equips.part_quality',
                        'stores_log.all_equips.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_equips.sectionswithoutorder.store',
                        'stores_log.all_equips.sectionswithoutorder.store_structure',
                        'stores_log.all_equips.pricing.sale_type',
                        'stores_log.all_equips.order_supplier.buy_transaction',
                        'stores_log.all_equips.equip.equip_details.equip_spec',
                        'stores_log.all_equips.equip.equip_images',
                    ])
                    ->get();
                // Merge all queries
            }

            // return $query;
            foreach ($query as $ele) {
                # code...
                $ele['stores_amount']=$this->PartInStoresCount($ele->part_id , $ele->source_id, $ele->status_id, $ele->quality_id, $ele->type_id);
            }

                return FacadesDataTables::of($query)
                // ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    $btn = 'No Name';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->kit->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->wheel->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->tractor->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->clark->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->equip->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    }
                })
                ->addColumn('Image', function ($row) {
                    $btn = 'No Image';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if(isset($row->stores_log->all_parts[0]->part->part_images) && count($row->stores_log->all_parts[0]->part->part_images) > 0){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/part_images/'.$row->stores_log->all_parts[0]->part->part_images[0]->image_name.'" alt="">';
                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if(isset($row->stores_log->all_kits[0]->kit->kit_image) && count($row->stores_log->all_kits[0]->kit->kit_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/kit_images/'.$row->stores_log->all_kits[0]->kit->kit_image[0]->image_name.'" alt="">';
                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if(isset($row->stores_log->all_wheels[0]->wheel->wheel_image)  && count($row->stores_log->all_wheels[0]->wheel->wheel_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/wheel_images/'.$row->stores_log->all_wheels[0]->wheel->wheel_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if(isset($row->stores_log->all_tractors[0]->tractor->tractor_image) && count($row->stores_log->all_tractors[0]->tractor->tractor_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/tractor_images/'.$row->stores_log->all_tractors[0]->tractor->tractor_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if(isset($row->stores_log->all_clarks[0]->clarck->clarck_image)  && count($row->stores_log->all_clarks[0]->clarck->clarck_image)>0  ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/clarck_images/'.$row->stores_log->all_clarks[0]->clarck->clarck_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if(isset($row->stores_log->all_equips[0]->equip->equip_image) && count($row->stores_log->all_equips[0]->equip->equip_image)>0  ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/equip_images/'.$row->stores_log->all_equips[0]->equip->equip_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    }
                })
                ->addColumn('source', function ($row) {
                    $btn = 'No Source';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    }


                    return $btn;
                })
                ->addColumn('status', function ($row) {
                    $btn = 'No Status';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    }

                    return $btn;
                })
                ->addColumn('quality', function ($row) {
                    $btn = 'No Quality';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    }
                    return $btn;
                })
                ->addColumn('amount', function ($row) {
                    $btn = 0;
                    if ($row->amount > 0) {
                        $btn = $row->amount;
                    } else {
                        $btn = 0;
                    }
                    return $btn;
                })
                ->addColumn('section', function ($row) {
                    $btn = '';
                    $secArr = [];
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->sectionswithoutorder) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->sectionswithoutorder); $i++) {
                                    if (in_array($row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->amount <= 0) {


                                    } else {

                                        $btn .= '<li>' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->amount  . '</span></li>';
                                        array_push($secArr, $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->id);
                                    }
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->sectionswithoutorder)) {
                                if (count($row->stores_log->all_kits[0]->sectionswithoutorder) > 0) {
                                    for ($i = 0; $i < count($row->stores_log->all_kits[0]->sectionswithoutorder); $i++) {
                                        if (in_array($row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->amount <= 0) {
                                        } else {

                                            $btn .= '<li>' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                            array_push($secArr, $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->id);
                                        }
                                    }
                                }
                            } else {
                                $btn = "No Section";
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (isset($row->stores_log->all_wheels[0]->sectionswithoutorder)) {
                                if (count($row->stores_log->all_wheels[0]->sectionswithoutorder) > 0) {
                                    for ($i = 0; $i < count($row->stores_log->all_wheels[0]->sectionswithoutorder); $i++) {
                                        if (in_array($row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->amount <= 0) {
                                        } else {

                                            $btn .= '<li>' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                            array_push($secArr, $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->id);
                                        }
                                    }
                                }
                            } else {
                                $btn = "No Section";
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (count($row->stores_log->all_tractors[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_tractors[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_tractors[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_tractors[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (count($row->stores_log->all_clarks[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_clarks[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_clarks[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_clarks[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (count($row->stores_log->all_equips[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_equips[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_equips[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_equips[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    }
                    return $btn;
                })
                ->addColumn('price', function ($row) {
                    $btn = 0;
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                $dataArr =collect($row->stores_log->all_parts[0]->pricing);


                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;
                                if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->pricing)) {
                                if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                    $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                      $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (isset($row->stores_log->all_equips[0]->pricing)) {
                                if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_equips[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    }

                    return $btn;
                })

                ->addColumn('partnumbers', function ($row) {
                    $btn = 'no num';
                    if ($row->type_id == 1) {
                        if (isset($row->stores_log->all_parts[0]->part->part_numbers)) {
                            if (count($row->stores_log->all_parts[0]->part->part_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->part->part_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_parts[0]->part->part_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 6) {
                        if (isset($row->stores_log->all_kits[0]->kit->kit_numbers)) {
                            if (count($row->stores_log->all_kits[0]->kit->kit_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_kits[0]->kit->kit_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_kits[0]->kit->kit_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 2) {
                    } elseif ($row->type_id == 3) {
                    } elseif ($row->type_id == 4) {
                    } elseif ($row->type_id == 5) {
                    }

                    return $btn;
                })
                ->addColumn('stores_amount', function ($row) {
                    $btn = '<ul class="list-group fs-13 text-truncate">';
                    if(isset($row->stores_amount)){
                        for ($i=0; $i < count($row->stores_amount) ; $i++) {
                            $btn .='<li>'.$row->stores_amount[$i]->name.'/'.$row->stores_amount[$i]->storepartCount.'</li>';

                        }
                    }else{
                        $btn .='<li>لا يوجد بالمخازن</li>';

                    }

                    $btn .= '</ul>';
                    return  $btn;

                })
                ->addColumn('view', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_parts[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_kits[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_wheels[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_tractors[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_clarks[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_equips[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    }

                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_parts) > 0) {
                                if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                    // $price = $row->stores_log->all_parts[0]->pricing[0]->price;

                                     $dataArr =collect($row->stores_log->all_parts[0]->pricing);
                                     $maxObject = $dataArr->sortByDesc('price')->first();


                                    $price =$maxObject->price ;
                                    $SaleType = $maxObject->sale_type;

                                    $prices = $row->stores_log->all_parts[0]->pricing;
                                    $prices_json = json_encode($prices);
                                }

                                if (count($row->stores_log->all_parts[0]->part->part_details) > 0) {
                                    $part_specs = $row->stores_log->all_parts[0]->part->part_details;
                                    $part_specs_json = json_encode($part_specs);
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_parts[0]->part->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_parts[0]->source_id) . ', ' . json_encode($row->stores_log->all_parts[0]->status_id) . ', ' . json_encode($row->stores_log->all_parts[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_kits) > 0) {
                                if (isset($row->stores_log->all_kits[0]->pricing)) {
                                    if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_kits[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_kits[0]->pricing[0]->price;
                                        $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;

                                        $prices = $row->stores_log->all_kits[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_kits[0]->kit->kit_details)) {
                                    if (count($row->stores_log->all_kits[0]->kit->kit_details) > 0) {
                                        $part_specs = $row->stores_log->all_kits[0]->kit->kit_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_kits[0]->kit->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_kits[0]->source_id) . ', ' . json_encode($row->stores_log->all_kits[0]->status_id) . ', ' . json_encode($row->stores_log->all_kits[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_wheels) > 0) {
                                if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                    if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_wheels[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_wheels[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_wheels[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_wheels[0]->wheel->wheel_details)) {
                                    if (count($row->stores_log->all_wheels[0]->wheel->wheel_details) > 0) {
                                        $part_specs = $row->stores_log->all_wheels[0]->wheel->wheel_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_wheels[0]->wheel->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_wheels[0]->source_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->status_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_tractors) > 0) {
                                if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                    if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_tractors[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_tractors[0]->pricing[0]->price;
                                          $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_tractors[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_tractors[0]->tractor->tractor_details)) {
                                    if (count($row->stores_log->all_tractors[0]->tractor->tractor_details) > 0) {
                                        $part_specs = $row->stores_log->all_tractors[0]->tractor->tractor_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_tractors[0]->tractor->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_tractors[0]->source_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->status_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_clarks) > 0) {
                                if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                    if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_clarks[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_clarks[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_clarks[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_clarks[0]->clark->clark_details)) {
                                    if (count($row->stores_log->all_clarks[0]->clark->clark_details) > 0) {
                                        $part_specs = $row->stores_log->all_clarks[0]->clark->clark_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_clarks[0]->clark->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_clarks[0]->source_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->status_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_equips) > 0) {
                                if (isset($row->stores_log->all_equips[0]->pricing)) {
                                    if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_equips[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_equips[0]->pricing[0]->price;
                                        $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_equips[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_equips[0]->equip->equip_details)) {
                                    if (count($row->stores_log->all_equips[0]->equip->equip_details) > 0) {
                                        $part_specs = $row->stores_log->all_equips[0]->equip->equip_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_equips[0]->equip->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_equips[0]->source_id) . ', ' . json_encode($row->stores_log->all_equips[0]->status_id) . ', ' . json_encode($row->stores_log->all_equips[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ', 1,' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    }
                    return '';
                })
                ->addColumn('send', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    }

                    return $btn;
                })
                ->addColumn('talef', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';


                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $store_id = $row->stores_log->store_id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                        $partId . '/' .
                        $sourceId . '/' .
                        $statusId . '/' .
                        $partQualityId . '/' .
                        $typeId . '/' .
                        $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $store_id = $row->stores_log->store_id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    }

                    return $btn;
                })
                ->rawColumns(['stores_amount','view','Image' ,'name', 'source', 'status', 'quality', 'amount', 'section', 'price', 'partnumbers', 'action', 'send','talef'])
                ->setTotalRecords(20)
                // ->filter(function ($query) {
                //     return $query;
                // })
                ->make(true);



        }
        // return view('testpos');
    }
    public function allDataForIdSubGroup(Request $request)
    {

        // return $request;
        if ($request->ajax()) {

            ////           Get Store Entity /////////////////
            $store = Store::where('id', $request->storeId)->first();

            $entity_tbl = 'App\\Models\\' . ucfirst($store->table_name);
            if ($store->table_name == 'damaged_parts') {
                $entity_tbl = 'damagedPart';
            }

            /*********************************************/
            // return $entity_tbl;

            $numberVal = $request->searchkey;
            $query = '';
            if ($request->typeId == 1) {


                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                    // ->where($store->table_name . '.part_id', $request->PartID)
                    ->whereIn($store->table_name . '.part_id', function ($query) use ($numberVal) {
                        $query->from('part')
                        ->select('part.id')
                        ->where('sub_group_id', '=', $numberVal);
                    })
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_parts.part_id, all_parts.source_id, all_parts.status_id, all_parts.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_parts.part_id', 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id',  $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_parts.part.part_numbers',
                        'stores_log.all_parts.part.part_images',
                        'stores_log.all_parts.source',
                        'stores_log.all_parts.status',
                        'stores_log.all_parts.part_quality',
                        'stores_log.all_parts.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_parts.sectionswithoutorder.store',
                        'stores_log.all_parts.sectionswithoutorder.store_structure',
                        'stores_log.all_parts.pricing.sale_type',
                        'stores_log.all_parts.order_supplier.buy_transaction',
                        'stores_log.all_parts.part.sub_group.group',
                        'stores_log.all_parts.part.part_details.part_spec',
                        'stores_log.all_parts.part.part_models.series.model.brand',
                        'stores_log.all_parts.part.part_images',
                    ])
                    ->get();
            } elseif ($request->typeId == 6) {
                $query = $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_kits.part_id, all_kits.source_id, all_kits.status_id, all_kits.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_kits.part_id', 'all_kits.source_id', 'all_kits.status_id', 'all_kits.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_kits.kit.kit_numbers',
                        'stores_log.all_kits.kit.kit_images',
                        'stores_log.all_kits.source',
                        'stores_log.all_kits.status',
                        'stores_log.all_kits.part_quality',
                        'stores_log.all_kits.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_kits.sectionswithoutorder.store',
                        'stores_log.all_kits.sectionswithoutorder.store_structure',
                        'stores_log.all_kits.pricing.sale_type',
                        'stores_log.all_kits.order_supplier.buy_transaction',
                        'stores_log.all_kits.kit.kit_details.kit_spec',
                        'stores_log.all_kits.kit.kit_models.series.model.brand',
                        'stores_log.all_kits.kit.kit_images',
                    ])
                    ->get();
            } elseif ($request->typeId == 2) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_wheels.part_id, all_wheels.source_id, all_wheels.status_id, all_wheels.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_wheels.part_id', 'all_wheels.source_id', 'all_wheels.status_id', 'all_wheels.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_wheels.wheel.wheel_images',
                        'stores_log.all_wheels.source',
                        'stores_log.all_wheels.status',
                        'stores_log.all_wheels.part_quality',
                        'stores_log.all_wheels.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_wheels.sectionswithoutorder.store',
                        'stores_log.all_wheels.sectionswithoutorder.store_structure',
                        'stores_log.all_wheels.pricing.sale_type',
                        'stores_log.all_wheels.order_supplier.buy_transaction',
                        'stores_log.all_wheels.wheel.wheel_details.wheel_spec',
                        'stores_log.all_wheels.wheel.wheel_model',
                        'stores_log.all_wheels.wheel.wheel_images',
                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 3) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_tractors', 'stores_log.All_part_id', '=', 'all_tractors.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_tractors.part_id, all_tractors.source_id, all_tractors.status_id, all_tractors.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_tractors.part_id', 'all_tractors.source_id', 'all_tractors.status_id', 'all_tractors.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_tractors.tractor.tractor_images',
                        'stores_log.all_tractors.source',
                        'stores_log.all_tractors.status',
                        'stores_log.all_tractors.part_quality',
                        'stores_log.all_tractors.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_tractors.sectionswithoutorder.store',
                        'stores_log.all_tractors.sectionswithoutorder.store_structure',
                        'stores_log.all_tractors.pricing.sale_type',
                        'stores_log.all_tractors.order_supplier.buy_transaction',
                        'stores_log.all_tractors.tractor.tractor_details.tractor_spec',
                        'stores_log.all_tractors.tractor.tractor_images',



                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 4) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_clarks', 'stores_log.All_part_id', '=', 'all_clarks.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_clarks.part_id, all_clarks.source_id, all_clarks.status_id, all_clarks.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_clarks.part_id', 'all_clarks.source_id', 'all_clarks.status_id', 'all_clarks.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_clarks.clark.clark_images',
                        'stores_log.all_clarks.source',
                        'stores_log.all_clarks.status',
                        'stores_log.all_clarks.part_quality',
                        'stores_log.all_clarks.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_clarks.sectionswithoutorder.store',
                        'stores_log.all_clarks.sectionswithoutorder.store_structure',
                        'stores_log.all_clarks.pricing.sale_type',
                        'stores_log.all_clarks.order_supplier.buy_transaction',
                        'stores_log.all_clarks.clark.clark_details.clark_spec',
                        'stores_log.all_clarks.clark.clark_images',
                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 5) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_equips', 'stores_log.All_part_id', '=', 'all_equips.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_equips.part_id, all_equips.source_id, all_equips.status_id, all_equips.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_equips.part_id', 'all_equips.source_id', 'all_equips.status_id', 'all_equips.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_equips.equip.equip_images',
                        'stores_log.all_equips.source',
                        'stores_log.all_equips.status',
                        'stores_log.all_equips.part_quality',
                        'stores_log.all_equips.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_equips.sectionswithoutorder.store',
                        'stores_log.all_equips.sectionswithoutorder.store_structure',
                        'stores_log.all_equips.pricing.sale_type',
                        'stores_log.all_equips.order_supplier.buy_transaction',
                        'stores_log.all_equips.equip.equip_details.equip_spec',
                        'stores_log.all_equips.equip.equip_images',
                    ])
                    ->get();
                // Merge all queries
            }

            // return $query;

            foreach ($query as $ele) {
                # code...
                $ele['stores_amount']=$this->PartInStoresCount($ele->part_id , $ele->source_id, $ele->status_id, $ele->quality_id, $ele->type_id);
            }


            return FacadesDataTables::of($query)
                // ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    $btn = 'No Name';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->kit->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->wheel->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->tractor->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->clark->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->equip->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    }
                })
                ->addColumn('Image', function ($row) {
                    $btn = 'No Image';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if(isset($row->stores_log->all_parts[0]->part->part_images)  && count($row->stores_log->all_parts[0]->part->part_images) > 0 ){
                            $btn = '<img class="rounded-circle header-profile-user" src="assets/part_images/'.$row->stores_log->all_parts[0]->part->part_images[0]->image_name.'" alt="">';
                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if(isset($row->stores_log->all_kits[0]->kit->kit_image) && count($row->stores_log->all_kits[0]->kit->kit_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/kit_images/'.$row->stores_log->all_kits[0]->kit->kit_image[0]->image_name.'" alt="">';
                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if(isset($row->stores_log->all_wheels[0]->wheel->wheel_image)  && count($row->stores_log->all_wheels[0]->wheel->wheel_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/wheel_images/'.$row->stores_log->all_wheels[0]->wheel->wheel_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if(isset($row->stores_log->all_tractors[0]->tractor->tractor_image) && count($row->stores_log->all_tractors[0]->tractor->tractor_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/tractor_images/'.$row->stores_log->all_tractors[0]->tractor->tractor_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if(isset($row->stores_log->all_clarks[0]->clarck->clarck_image)  && count($row->stores_log->all_clarks[0]->clarck->clarck_image)>0  ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/clarck_images/'.$row->stores_log->all_clarks[0]->clarck->clarck_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if(isset($row->stores_log->all_equips[0]->equip->equip_image) && count($row->stores_log->all_equips[0]->equip->equip_image)>0  ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/equip_images/'.$row->stores_log->all_equips[0]->equip->equip_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    }
                })
                ->addColumn('source', function ($row) {
                    $btn = 'No Source';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    }


                    return $btn;
                })
                ->addColumn('status', function ($row) {
                    $btn = 'No Status';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    }

                    return $btn;
                })
                ->addColumn('quality', function ($row) {
                    $btn = 'No Quality';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    }
                    return $btn;
                })
                ->addColumn('amount', function ($row) {
                    $btn = 0;
                    if ($row->amount > 0) {
                        $btn = $row->amount;
                    } else {
                        $btn = 0;
                    }
                    return $btn;
                })
                ->addColumn('section', function ($row) {
                    $btn = '';
                    $secArr = [];
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->sectionswithoutorder) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->sectionswithoutorder); $i++) {
                                    if (in_array($row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->amount <= 0) {


                                    } else {

                                        $btn .= '<li>' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->amount  . '</span></li>';
                                        array_push($secArr, $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->id);
                                    }
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->sectionswithoutorder)) {
                                if (count($row->stores_log->all_kits[0]->sectionswithoutorder) > 0) {
                                    for ($i = 0; $i < count($row->stores_log->all_kits[0]->sectionswithoutorder); $i++) {
                                        if (in_array($row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->amount <= 0) {
                                        } else {

                                            $btn .= '<li>' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                            array_push($secArr, $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->id);
                                        }
                                    }
                                }
                            } else {
                                $btn = "No Section";
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (isset($row->stores_log->all_wheels[0]->sectionswithoutorder)) {
                                if (count($row->stores_log->all_wheels[0]->sectionswithoutorder) > 0) {
                                    for ($i = 0; $i < count($row->stores_log->all_wheels[0]->sectionswithoutorder); $i++) {
                                        if (in_array($row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->amount <= 0) {
                                        } else {

                                            $btn .= '<li>' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                            array_push($secArr, $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->id);
                                        }
                                    }
                                }
                            } else {
                                $btn = "No Section";
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (count($row->stores_log->all_tractors[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_tractors[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_tractors[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_tractors[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (count($row->stores_log->all_clarks[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_clarks[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_clarks[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_clarks[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (count($row->stores_log->all_equips[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_equips[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_equips[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_equips[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    }
                    return $btn;
                })
                ->addColumn('price', function ($row) {
                    $btn = 0;
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                $dataArr =collect($row->stores_log->all_parts[0]->pricing);


                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;
                                if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->pricing)) {
                                if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                    $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                      $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (isset($row->stores_log->all_equips[0]->pricing)) {
                                if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_equips[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    }

                    return $btn;
                })

                ->addColumn('partnumbers', function ($row) {
                    $btn = 'no num';
                    if ($row->type_id == 1) {
                        if (isset($row->stores_log->all_parts[0]->part->part_numbers)) {
                            if (count($row->stores_log->all_parts[0]->part->part_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->part->part_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_parts[0]->part->part_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 6) {
                        if (isset($row->stores_log->all_kits[0]->kit->kit_numbers)) {
                            if (count($row->stores_log->all_kits[0]->kit->kit_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_kits[0]->kit->kit_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_kits[0]->kit->kit_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 2) {
                    } elseif ($row->type_id == 3) {
                    } elseif ($row->type_id == 4) {
                    } elseif ($row->type_id == 5) {
                    }

                    return $btn;
                })
                ->addColumn('stores_amount', function ($row) {
                    $btn = '<ul class="list-group fs-13 text-truncate">';
                    if(isset($row->stores_amount)){
                        for ($i=0; $i < count($row->stores_amount) ; $i++) {
                            $btn .='<li>'.$row->stores_amount[$i]->name.'/'.$row->stores_amount[$i]->storepartCount.'</li>';

                        }
                    }else{
                        $btn .='<li>لا يوجد بالمخازن</li>';

                    }

                    $btn .= '</ul>';
                    return  $btn;

                })
                ->addColumn('view', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_parts[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_kits[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_wheels[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_tractors[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_clarks[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_equips[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    }

                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_parts) > 0) {
                                if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                    // $price = $row->stores_log->all_parts[0]->pricing[0]->price;

                                     $dataArr =collect($row->stores_log->all_parts[0]->pricing);
                                     $maxObject = $dataArr->sortByDesc('price')->first();


                                    $price =$maxObject->price ;
                                    $SaleType = $maxObject->sale_type;

                                    $prices = $row->stores_log->all_parts[0]->pricing;
                                    $prices_json = json_encode($prices);
                                }

                                if (count($row->stores_log->all_parts[0]->part->part_details) > 0) {
                                    $part_specs = $row->stores_log->all_parts[0]->part->part_details;
                                    $part_specs_json = json_encode($part_specs);
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_parts[0]->part->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_parts[0]->source_id) . ', ' . json_encode($row->stores_log->all_parts[0]->status_id) . ', ' . json_encode($row->stores_log->all_parts[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_kits) > 0) {
                                if (isset($row->stores_log->all_kits[0]->pricing)) {
                                    if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_kits[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_kits[0]->pricing[0]->price;
                                        $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;

                                        $prices = $row->stores_log->all_kits[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_kits[0]->kit->kit_details)) {
                                    if (count($row->stores_log->all_kits[0]->kit->kit_details) > 0) {
                                        $part_specs = $row->stores_log->all_kits[0]->kit->kit_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_kits[0]->kit->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_kits[0]->source_id) . ', ' . json_encode($row->stores_log->all_kits[0]->status_id) . ', ' . json_encode($row->stores_log->all_kits[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_wheels) > 0) {
                                if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                    if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_wheels[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_wheels[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_wheels[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_wheels[0]->wheel->wheel_details)) {
                                    if (count($row->stores_log->all_wheels[0]->wheel->wheel_details) > 0) {
                                        $part_specs = $row->stores_log->all_wheels[0]->wheel->wheel_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_wheels[0]->wheel->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_wheels[0]->source_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->status_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_tractors) > 0) {
                                if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                    if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_tractors[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_tractors[0]->pricing[0]->price;
                                          $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_tractors[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_tractors[0]->tractor->tractor_details)) {
                                    if (count($row->stores_log->all_tractors[0]->tractor->tractor_details) > 0) {
                                        $part_specs = $row->stores_log->all_tractors[0]->tractor->tractor_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_tractors[0]->tractor->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_tractors[0]->source_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->status_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_clarks) > 0) {
                                if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                    if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_clarks[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_clarks[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_clarks[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_clarks[0]->clark->clark_details)) {
                                    if (count($row->stores_log->all_clarks[0]->clark->clark_details) > 0) {
                                        $part_specs = $row->stores_log->all_clarks[0]->clark->clark_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_clarks[0]->clark->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_clarks[0]->source_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->status_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_equips) > 0) {
                                if (isset($row->stores_log->all_equips[0]->pricing)) {
                                    if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_equips[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_equips[0]->pricing[0]->price;
                                        $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_equips[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_equips[0]->equip->equip_details)) {
                                    if (count($row->stores_log->all_equips[0]->equip->equip_details) > 0) {
                                        $part_specs = $row->stores_log->all_equips[0]->equip->equip_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_equips[0]->equip->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_equips[0]->source_id) . ', ' . json_encode($row->stores_log->all_equips[0]->status_id) . ', ' . json_encode($row->stores_log->all_equips[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ', 1,' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    }
                    return '';
                })
                ->addColumn('send', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    }

                    return $btn;
                })
                ->addColumn('talef', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';


                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $store_id = $row->stores_log->store_id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                        $partId . '/' .
                        $sourceId . '/' .
                        $statusId . '/' .
                        $partQualityId . '/' .
                        $typeId . '/' .
                        $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $store_id = $row->stores_log->store_id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    }

                    return $btn;
                })
                ->rawColumns(['stores_amount','view','Image' ,'name', 'source', 'status', 'quality', 'amount', 'section', 'price', 'partnumbers', 'action', 'send','talef'])
                ->setTotalRecords(20)
                // ->filter(function ($query) {
                //     return $query;
                // })
                ->make(true);
        }
        // return view('testpos');
    }


    public function allDataForIdSupplier(Request $request)
    {

        // return $request;
        if ($request->ajax()) {

            ////           Get Store Entity /////////////////
            $store = Store::where('id', $request->storeId)->first();

            $entity_tbl = 'App\\Models\\' . ucfirst($store->table_name);
            if ($store->table_name == 'damaged_parts') {
                $entity_tbl = 'damagedPart';
            }

            /*********************************************/
            // return $entity_tbl;

            $numberVal = $request->searchkey;
            $query = '';
            if ($request->typeId == 1) {


                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                    // ->where($store->table_name . '.part_id', $request->PartID)
                    ->whereIn('order_supplier_id',(function ($query) use($numberVal) {
                        $query->from('order_supplier')
                            ->select('id')
                            ->where('supplier_id','=',$numberVal);
                    }))
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_parts.part_id, all_parts.source_id, all_parts.status_id, all_parts.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_parts.part_id', 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id',  $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_parts.part.part_numbers',
                        'stores_log.all_parts.part.part_images',
                        'stores_log.all_parts.source',
                        'stores_log.all_parts.status',
                        'stores_log.all_parts.part_quality',
                        'stores_log.all_parts.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_parts.sectionswithoutorder.store',
                        'stores_log.all_parts.sectionswithoutorder.store_structure',
                        'stores_log.all_parts.pricing.sale_type',
                        'stores_log.all_parts.order_supplier.buy_transaction',
                        'stores_log.all_parts.part.sub_group.group',
                        'stores_log.all_parts.part.part_details.part_spec',
                        'stores_log.all_parts.part.part_models.series.model.brand',
                        'stores_log.all_parts.part.part_images',
                    ])
                    ->get();
            } elseif ($request->typeId == 6) {
                $query = $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_kits.part_id, all_kits.source_id, all_kits.status_id, all_kits.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_kits.part_id', 'all_kits.source_id', 'all_kits.status_id', 'all_kits.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_kits.kit.kit_numbers',
                        'stores_log.all_kits.kit.kit_images',
                        'stores_log.all_kits.source',
                        'stores_log.all_kits.status',
                        'stores_log.all_kits.part_quality',
                        'stores_log.all_kits.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_kits.sectionswithoutorder.store',
                        'stores_log.all_kits.sectionswithoutorder.store_structure',
                        'stores_log.all_kits.pricing.sale_type',
                        'stores_log.all_kits.order_supplier.buy_transaction',
                        'stores_log.all_kits.kit.kit_details.kit_spec',
                        'stores_log.all_kits.kit.kit_models.series.model.brand',
                        'stores_log.all_kits.kit.kit_images',
                    ])
                    ->get();
            } elseif ($request->typeId == 2) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_wheels.part_id, all_wheels.source_id, all_wheels.status_id, all_wheels.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_wheels.part_id', 'all_wheels.source_id', 'all_wheels.status_id', 'all_wheels.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_wheels.wheel.wheel_images',
                        'stores_log.all_wheels.source',
                        'stores_log.all_wheels.status',
                        'stores_log.all_wheels.part_quality',
                        'stores_log.all_wheels.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_wheels.sectionswithoutorder.store',
                        'stores_log.all_wheels.sectionswithoutorder.store_structure',
                        'stores_log.all_wheels.pricing.sale_type',
                        'stores_log.all_wheels.order_supplier.buy_transaction',
                        'stores_log.all_wheels.wheel.wheel_details.wheel_spec',
                        'stores_log.all_wheels.wheel.wheel_model',
                        'stores_log.all_wheels.wheel.wheel_images',
                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 3) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_tractors', 'stores_log.All_part_id', '=', 'all_tractors.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_tractors.part_id, all_tractors.source_id, all_tractors.status_id, all_tractors.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_tractors.part_id', 'all_tractors.source_id', 'all_tractors.status_id', 'all_tractors.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_tractors.tractor.tractor_images',
                        'stores_log.all_tractors.source',
                        'stores_log.all_tractors.status',
                        'stores_log.all_tractors.part_quality',
                        'stores_log.all_tractors.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_tractors.sectionswithoutorder.store',
                        'stores_log.all_tractors.sectionswithoutorder.store_structure',
                        'stores_log.all_tractors.pricing.sale_type',
                        'stores_log.all_tractors.order_supplier.buy_transaction',
                        'stores_log.all_tractors.tractor.tractor_details.tractor_spec',
                        'stores_log.all_tractors.tractor.tractor_images',



                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 4) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_clarks', 'stores_log.All_part_id', '=', 'all_clarks.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_clarks.part_id, all_clarks.source_id, all_clarks.status_id, all_clarks.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_clarks.part_id', 'all_clarks.source_id', 'all_clarks.status_id', 'all_clarks.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_clarks.clark.clark_images',
                        'stores_log.all_clarks.source',
                        'stores_log.all_clarks.status',
                        'stores_log.all_clarks.part_quality',
                        'stores_log.all_clarks.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_clarks.sectionswithoutorder.store',
                        'stores_log.all_clarks.sectionswithoutorder.store_structure',
                        'stores_log.all_clarks.pricing.sale_type',
                        'stores_log.all_clarks.order_supplier.buy_transaction',
                        'stores_log.all_clarks.clark.clark_details.clark_spec',
                        'stores_log.all_clarks.clark.clark_images',
                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 5) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_equips', 'stores_log.All_part_id', '=', 'all_equips.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_equips.part_id, all_equips.source_id, all_equips.status_id, all_equips.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_equips.part_id', 'all_equips.source_id', 'all_equips.status_id', 'all_equips.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_equips.equip.equip_images',
                        'stores_log.all_equips.source',
                        'stores_log.all_equips.status',
                        'stores_log.all_equips.part_quality',
                        'stores_log.all_equips.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_equips.sectionswithoutorder.store',
                        'stores_log.all_equips.sectionswithoutorder.store_structure',
                        'stores_log.all_equips.pricing.sale_type',
                        'stores_log.all_equips.order_supplier.buy_transaction',
                        'stores_log.all_equips.equip.equip_details.equip_spec',
                        'stores_log.all_equips.equip.equip_images',
                    ])
                    ->get();
                // Merge all queries
            }

            // return $query;

            foreach ($query as $ele) {
                # code...
                $ele['stores_amount']=$this->PartInStoresCount($ele->part_id , $ele->source_id, $ele->status_id, $ele->quality_id, $ele->type_id);
            }


            return FacadesDataTables::of($query)
                // ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    $btn = 'No Name';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->kit->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->wheel->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->tractor->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->clark->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->equip->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    }
                })
                ->addColumn('Image', function ($row) {
                    $btn = 'No Image';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if(isset($row->stores_log->all_parts[0]->part->part_images)  && count($row->stores_log->all_parts[0]->part->part_images) > 0 ){
                            $btn = '<img class="rounded-circle header-profile-user" src="assets/part_images/'.$row->stores_log->all_parts[0]->part->part_images[0]->image_name.'" alt="">';
                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if(isset($row->stores_log->all_kits[0]->kit->kit_image) && count($row->stores_log->all_kits[0]->kit->kit_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/kit_images/'.$row->stores_log->all_kits[0]->kit->kit_image[0]->image_name.'" alt="">';
                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if(isset($row->stores_log->all_wheels[0]->wheel->wheel_image)  && count($row->stores_log->all_wheels[0]->wheel->wheel_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/wheel_images/'.$row->stores_log->all_wheels[0]->wheel->wheel_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if(isset($row->stores_log->all_tractors[0]->tractor->tractor_image) && count($row->stores_log->all_tractors[0]->tractor->tractor_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/tractor_images/'.$row->stores_log->all_tractors[0]->tractor->tractor_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if(isset($row->stores_log->all_clarks[0]->clarck->clarck_image)  && count($row->stores_log->all_clarks[0]->clarck->clarck_image)>0  ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/clarck_images/'.$row->stores_log->all_clarks[0]->clarck->clarck_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if(isset($row->stores_log->all_equips[0]->equip->equip_image) && count($row->stores_log->all_equips[0]->equip->equip_image)>0  ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/equip_images/'.$row->stores_log->all_equips[0]->equip->equip_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    }
                })
                ->addColumn('source', function ($row) {
                    $btn = 'No Source';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    }


                    return $btn;
                })
                ->addColumn('status', function ($row) {
                    $btn = 'No Status';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    }

                    return $btn;
                })
                ->addColumn('quality', function ($row) {
                    $btn = 'No Quality';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    }
                    return $btn;
                })
                ->addColumn('amount', function ($row) {
                    $btn = 0;
                    if ($row->amount > 0) {
                        $btn = $row->amount;
                    } else {
                        $btn = 0;
                    }
                    return $btn;
                })
                ->addColumn('section', function ($row) {
                    $btn = '';
                    $secArr = [];
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->sectionswithoutorder) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->sectionswithoutorder); $i++) {
                                    if (in_array($row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->amount <= 0) {


                                    } else {

                                        $btn .= '<li>' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->amount  . '</span></li>';
                                        array_push($secArr, $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->id);
                                    }
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->sectionswithoutorder)) {
                                if (count($row->stores_log->all_kits[0]->sectionswithoutorder) > 0) {
                                    for ($i = 0; $i < count($row->stores_log->all_kits[0]->sectionswithoutorder); $i++) {
                                        if (in_array($row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->amount <= 0) {
                                        } else {

                                            $btn .= '<li>' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                            array_push($secArr, $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->id);
                                        }
                                    }
                                }
                            } else {
                                $btn = "No Section";
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (isset($row->stores_log->all_wheels[0]->sectionswithoutorder)) {
                                if (count($row->stores_log->all_wheels[0]->sectionswithoutorder) > 0) {
                                    for ($i = 0; $i < count($row->stores_log->all_wheels[0]->sectionswithoutorder); $i++) {
                                        if (in_array($row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->amount <= 0) {
                                        } else {

                                            $btn .= '<li>' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                            array_push($secArr, $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->id);
                                        }
                                    }
                                }
                            } else {
                                $btn = "No Section";
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (count($row->stores_log->all_tractors[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_tractors[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_tractors[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_tractors[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (count($row->stores_log->all_clarks[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_clarks[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_clarks[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_clarks[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (count($row->stores_log->all_equips[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_equips[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_equips[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_equips[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    }
                    return $btn;
                })
                ->addColumn('price', function ($row) {
                    $btn = 0;
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                $dataArr =collect($row->stores_log->all_parts[0]->pricing);


                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;
                                if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->pricing)) {
                                if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                    $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                      $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (isset($row->stores_log->all_equips[0]->pricing)) {
                                if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_equips[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    }

                    return $btn;
                })

                ->addColumn('partnumbers', function ($row) {
                    $btn = 'no num';
                    if ($row->type_id == 1) {
                        if (isset($row->stores_log->all_parts[0]->part->part_numbers)) {
                            if (count($row->stores_log->all_parts[0]->part->part_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->part->part_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_parts[0]->part->part_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 6) {
                        if (isset($row->stores_log->all_kits[0]->kit->kit_numbers)) {
                            if (count($row->stores_log->all_kits[0]->kit->kit_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_kits[0]->kit->kit_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_kits[0]->kit->kit_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 2) {
                    } elseif ($row->type_id == 3) {
                    } elseif ($row->type_id == 4) {
                    } elseif ($row->type_id == 5) {
                    }

                    return $btn;
                })
                ->addColumn('stores_amount', function ($row) {
                    $btn = '<ul class="list-group fs-13 text-truncate">';
                    if(isset($row->stores_amount)){
                        for ($i=0; $i < count($row->stores_amount) ; $i++) {
                            $btn .='<li>'.$row->stores_amount[$i]->name.'/'.$row->stores_amount[$i]->storepartCount.'</li>';

                        }
                    }else{
                        $btn .='<li>لا يوجد بالمخازن</li>';

                    }

                    $btn .= '</ul>';
                    return  $btn;

                })
                ->addColumn('view', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_parts[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_kits[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_wheels[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_tractors[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_clarks[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_equips[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    }

                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_parts) > 0) {
                                if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                    // $price = $row->stores_log->all_parts[0]->pricing[0]->price;

                                     $dataArr =collect($row->stores_log->all_parts[0]->pricing);
                                     $maxObject = $dataArr->sortByDesc('price')->first();


                                    $price =$maxObject->price ;
                                    $SaleType = $maxObject->sale_type;

                                    $prices = $row->stores_log->all_parts[0]->pricing;
                                    $prices_json = json_encode($prices);
                                }

                                if (count($row->stores_log->all_parts[0]->part->part_details) > 0) {
                                    $part_specs = $row->stores_log->all_parts[0]->part->part_details;
                                    $part_specs_json = json_encode($part_specs);
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_parts[0]->part->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_parts[0]->source_id) . ', ' . json_encode($row->stores_log->all_parts[0]->status_id) . ', ' . json_encode($row->stores_log->all_parts[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_kits) > 0) {
                                if (isset($row->stores_log->all_kits[0]->pricing)) {
                                    if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_kits[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_kits[0]->pricing[0]->price;
                                        $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;

                                        $prices = $row->stores_log->all_kits[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_kits[0]->kit->kit_details)) {
                                    if (count($row->stores_log->all_kits[0]->kit->kit_details) > 0) {
                                        $part_specs = $row->stores_log->all_kits[0]->kit->kit_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_kits[0]->kit->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_kits[0]->source_id) . ', ' . json_encode($row->stores_log->all_kits[0]->status_id) . ', ' . json_encode($row->stores_log->all_kits[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_wheels) > 0) {
                                if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                    if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_wheels[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_wheels[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_wheels[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_wheels[0]->wheel->wheel_details)) {
                                    if (count($row->stores_log->all_wheels[0]->wheel->wheel_details) > 0) {
                                        $part_specs = $row->stores_log->all_wheels[0]->wheel->wheel_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_wheels[0]->wheel->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_wheels[0]->source_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->status_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_tractors) > 0) {
                                if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                    if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_tractors[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_tractors[0]->pricing[0]->price;
                                          $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_tractors[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_tractors[0]->tractor->tractor_details)) {
                                    if (count($row->stores_log->all_tractors[0]->tractor->tractor_details) > 0) {
                                        $part_specs = $row->stores_log->all_tractors[0]->tractor->tractor_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_tractors[0]->tractor->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_tractors[0]->source_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->status_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_clarks) > 0) {
                                if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                    if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_clarks[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_clarks[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_clarks[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_clarks[0]->clark->clark_details)) {
                                    if (count($row->stores_log->all_clarks[0]->clark->clark_details) > 0) {
                                        $part_specs = $row->stores_log->all_clarks[0]->clark->clark_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_clarks[0]->clark->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_clarks[0]->source_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->status_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_equips) > 0) {
                                if (isset($row->stores_log->all_equips[0]->pricing)) {
                                    if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_equips[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_equips[0]->pricing[0]->price;
                                        $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_equips[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_equips[0]->equip->equip_details)) {
                                    if (count($row->stores_log->all_equips[0]->equip->equip_details) > 0) {
                                        $part_specs = $row->stores_log->all_equips[0]->equip->equip_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_equips[0]->equip->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_equips[0]->source_id) . ', ' . json_encode($row->stores_log->all_equips[0]->status_id) . ', ' . json_encode($row->stores_log->all_equips[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ', 1,' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    }
                    return '';
                })
                ->addColumn('send', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    }

                    return $btn;
                })
                ->addColumn('talef', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';


                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $store_id = $row->stores_log->store_id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                        $partId . '/' .
                        $sourceId . '/' .
                        $statusId . '/' .
                        $partQualityId . '/' .
                        $typeId . '/' .
                        $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $store_id = $row->stores_log->store_id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    }

                    return $btn;
                })
                ->rawColumns(['stores_amount','view','Image' ,'name', 'source', 'status', 'quality', 'amount', 'section', 'price', 'partnumbers', 'action', 'send','talef'])
                ->setTotalRecords(20)
                // ->filter(function ($query) {
                //     return $query;
                // })
                ->make(true);
        }
        // return view('testpos');
    }

    public function allDataForIdFilterAll(Request $request)
    {

        // return $request;
        if ($request->ajax()) {

            ////           Get Store Entity /////////////////
            $store = Store::where('id', $request->storeId)->first();

            $entity_tbl = 'App\\Models\\' . ucfirst($store->table_name);
            if ($store->table_name == 'damaged_parts') {
                $entity_tbl = 'damagedPart';
            }

            /*********************************************/
            // return $entity_tbl;

            $searchData = $request->searchData;
            $queryString ='';


            $query = '';
            if ($request->typeId == 1) {


                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id');
                    // ->where($store->table_name . '.part_id', $request->PartID)



                    if(!is_null($searchData['brandtypeSlct'])){
                        $numberVal = $searchData['brandtypeSlct'];
                            $query->whereIn($store->table_name . '.part_id', function ($query) use ($numberVal) {
                                $query->from('part_model')
                                    ->join('series', 'part_model.model_id', '=', 'series.id')
                                    ->join('model', 'series.model_id', '=', 'model.id')
                                    ->select('part_model.part_id')
                                    ->where('model.type_id', '=', $numberVal);
                            });
                    }

                    if(!is_null($searchData['partNameSearchTxt'])){
                        $numberVal = $searchData['partNameSearchTxt'];
                            $query->whereIn($store->table_name . '.part_id',(function ($query) use($numberVal) {
                                $query->from('part')
                                    ->select('id')
                                    // ->where('name','LIKE','%'.$numberVal.'%');
                                     ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($numberVal) . '%']);
                            }));
                    }

                    if(!is_null($searchData['brandSlct'])){
                        $numberVal = $searchData['brandSlct'];
                            $query->whereIn($store->table_name . '.part_id', function ($query) use ($numberVal) {
                                $query->from('part_model')
                                    ->join('series', 'part_model.model_id', '=', 'series.id')
                                    ->join('model', 'series.model_id', '=', 'model.id')
                                    ->select('part_model.part_id')
                                    ->where('model.brand_id', '=', $numberVal);
                            });
                    }
                    if(!is_null($searchData['supplierSlct'])){
                        $numberVal = $searchData['supplierSlct'];
                            $query->whereIn('order_supplier_id',(function ($query) use($numberVal) {
                            $query->from('order_supplier')
                                ->select('id')
                                ->where('supplier_id','=',$numberVal);
                        }));
                    }

                    if(!is_null($searchData['partNumberSearchTxt'])){
                        $numberVal = $searchData['partNumberSearchTxt'];
                            $query->whereIn($store->table_name . '.part_id',(function ($query) use($numberVal) {
                            $query->from('part_number')
                                ->select('part_id')
                                ->where('number','LIKE','%'.$numberVal.'%');
                        }));
                    }

                    if(!is_null($searchData['modelSlct'])){
                        $numberVal = $searchData['modelSlct'];
                            $query->whereIn($store->table_name . '.part_id', function ($query) use ($numberVal) {
                            $query->from('part_model')
                                ->join('series', 'part_model.model_id', '=', 'series.id')

                                ->select('part_model.part_id')
                                ->where('series.model_id', '=', $numberVal);
                        });
                    }

                    if(!is_null($searchData['seriesSlct'])){
                        $numberVal = $searchData['seriesSlct'];
                        $query->whereIn($store->table_name . '.part_id', function ($query) use ($numberVal) {
                            $query->from('part_model')
                            ->select('part_model.part_id')
                            ->where('model_id', '=', $numberVal);
                        });
                    }

                    if(!is_null($searchData['groupSlct'])){
                        $numberVal = $searchData['groupSlct'];
                        $query->whereIn($store->table_name . '.part_id', function ($query) use ($numberVal) {
                            $query->from('part')
                            ->join('sub_group', 'part.sub_group_id', '=', 'sub_group.id')
                            ->select('part.id')
                            ->where('sub_group.group_id', '=', $numberVal);
                        });
                    }

                    if(!is_null($searchData['SgroupSlct'])){
                        $numberVal = $searchData['SgroupSlct'];
                        $query->whereIn($store->table_name . '.part_id', function ($query) use ($numberVal) {
                            $query->from('part')
                            ->select('part.id')
                            ->where('sub_group_id', '=', $numberVal);
                        });
                    }



                    $query = $query->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_parts.part_id, all_parts.source_id, all_parts.status_id, all_parts.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_parts.part_id', 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id',  $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_parts.part.part_numbers',
                        'stores_log.all_parts.part.part_images',
                        'stores_log.all_parts.source',
                        'stores_log.all_parts.status',
                        'stores_log.all_parts.part_quality',
                        'stores_log.all_parts.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_parts.sectionswithoutorder.store',
                        'stores_log.all_parts.sectionswithoutorder.store_structure',
                        'stores_log.all_parts.pricing.sale_type',
                        'stores_log.all_parts.order_supplier.buy_transaction',
                        'stores_log.all_parts.part.sub_group.group',
                        'stores_log.all_parts.part.part_details.part_spec',
                        'stores_log.all_parts.part.part_models.series.model.brand',
                        'stores_log.all_parts.part.part_images',
                    ])
                    ->get();
            } elseif ($request->typeId == 6) {
                $query = $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_kits.part_id, all_kits.source_id, all_kits.status_id, all_kits.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_kits.part_id', 'all_kits.source_id', 'all_kits.status_id', 'all_kits.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_kits.kit.kit_numbers',
                        'stores_log.all_kits.kit.kit_images',
                        'stores_log.all_kits.source',
                        'stores_log.all_kits.status',
                        'stores_log.all_kits.part_quality',
                        'stores_log.all_kits.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_kits.sectionswithoutorder.store',
                        'stores_log.all_kits.sectionswithoutorder.store_structure',
                        'stores_log.all_kits.pricing.sale_type',
                        'stores_log.all_kits.order_supplier.buy_transaction',
                        'stores_log.all_kits.kit.kit_details.kit_spec',
                        'stores_log.all_kits.kit.kit_models.series.model.brand',
                        'stores_log.all_kits.kit.kit_images',
                    ])
                    ->get();
            } elseif ($request->typeId == 2) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_wheels.part_id, all_wheels.source_id, all_wheels.status_id, all_wheels.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_wheels.part_id', 'all_wheels.source_id', 'all_wheels.status_id', 'all_wheels.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_wheels.wheel.wheel_images',
                        'stores_log.all_wheels.source',
                        'stores_log.all_wheels.status',
                        'stores_log.all_wheels.part_quality',
                        'stores_log.all_wheels.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_wheels.sectionswithoutorder.store',
                        'stores_log.all_wheels.sectionswithoutorder.store_structure',
                        'stores_log.all_wheels.pricing.sale_type',
                        'stores_log.all_wheels.order_supplier.buy_transaction',
                        'stores_log.all_wheels.wheel.wheel_details.wheel_spec',
                        'stores_log.all_wheels.wheel.wheel_model',
                        'stores_log.all_wheels.wheel.wheel_images',
                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 3) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_tractors', 'stores_log.All_part_id', '=', 'all_tractors.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_tractors.part_id, all_tractors.source_id, all_tractors.status_id, all_tractors.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_tractors.part_id', 'all_tractors.source_id', 'all_tractors.status_id', 'all_tractors.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_tractors.tractor.tractor_images',
                        'stores_log.all_tractors.source',
                        'stores_log.all_tractors.status',
                        'stores_log.all_tractors.part_quality',
                        'stores_log.all_tractors.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_tractors.sectionswithoutorder.store',
                        'stores_log.all_tractors.sectionswithoutorder.store_structure',
                        'stores_log.all_tractors.pricing.sale_type',
                        'stores_log.all_tractors.order_supplier.buy_transaction',
                        'stores_log.all_tractors.tractor.tractor_details.tractor_spec',
                        'stores_log.all_tractors.tractor.tractor_images',



                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 4) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_clarks', 'stores_log.All_part_id', '=', 'all_clarks.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_clarks.part_id, all_clarks.source_id, all_clarks.status_id, all_clarks.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_clarks.part_id', 'all_clarks.source_id', 'all_clarks.status_id', 'all_clarks.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_clarks.clark.clark_images',
                        'stores_log.all_clarks.source',
                        'stores_log.all_clarks.status',
                        'stores_log.all_clarks.part_quality',
                        'stores_log.all_clarks.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_clarks.sectionswithoutorder.store',
                        'stores_log.all_clarks.sectionswithoutorder.store_structure',
                        'stores_log.all_clarks.pricing.sale_type',
                        'stores_log.all_clarks.order_supplier.buy_transaction',
                        'stores_log.all_clarks.clark.clark_details.clark_spec',
                        'stores_log.all_clarks.clark.clark_images',
                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 5) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_equips', 'stores_log.All_part_id', '=', 'all_equips.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_equips.part_id, all_equips.source_id, all_equips.status_id, all_equips.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_equips.part_id', 'all_equips.source_id', 'all_equips.status_id', 'all_equips.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_equips.equip.equip_images',
                        'stores_log.all_equips.source',
                        'stores_log.all_equips.status',
                        'stores_log.all_equips.part_quality',
                        'stores_log.all_equips.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_equips.sectionswithoutorder.store',
                        'stores_log.all_equips.sectionswithoutorder.store_structure',
                        'stores_log.all_equips.pricing.sale_type',
                        'stores_log.all_equips.order_supplier.buy_transaction',
                        'stores_log.all_equips.equip.equip_details.equip_spec',
                        'stores_log.all_equips.equip.equip_images',
                    ])
                    ->get();
                // Merge all queries
            }

            // return $query;

            foreach ($query as $ele) {
                # code...
                $ele['stores_amount']=$this->PartInStoresCount($ele->part_id , $ele->source_id, $ele->status_id, $ele->quality_id, $ele->type_id);
            }


            return FacadesDataTables::of($query)
                // ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    $btn = 'No Name';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->kit->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->wheel->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->tractor->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->clark->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->equip->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    }
                })
                ->addColumn('Image', function ($row) {
                    $btn = 'No Image';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if(isset($row->stores_log->all_parts[0]->part->part_images)  && count($row->stores_log->all_parts[0]->part->part_images) > 0 ){
                            $btn = '<img class="rounded-circle header-profile-user" src="assets/part_images/'.$row->stores_log->all_parts[0]->part->part_images[0]->image_name.'" alt="">';
                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if(isset($row->stores_log->all_kits[0]->kit->kit_image) && count($row->stores_log->all_kits[0]->kit->kit_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/kit_images/'.$row->stores_log->all_kits[0]->kit->kit_image[0]->image_name.'" alt="">';
                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if(isset($row->stores_log->all_wheels[0]->wheel->wheel_image)  && count($row->stores_log->all_wheels[0]->wheel->wheel_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/wheel_images/'.$row->stores_log->all_wheels[0]->wheel->wheel_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if(isset($row->stores_log->all_tractors[0]->tractor->tractor_image) && count($row->stores_log->all_tractors[0]->tractor->tractor_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/tractor_images/'.$row->stores_log->all_tractors[0]->tractor->tractor_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if(isset($row->stores_log->all_clarks[0]->clarck->clarck_image)  && count($row->stores_log->all_clarks[0]->clarck->clarck_image)>0  ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/clarck_images/'.$row->stores_log->all_clarks[0]->clarck->clarck_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if(isset($row->stores_log->all_equips[0]->equip->equip_image) && count($row->stores_log->all_equips[0]->equip->equip_image)>0  ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/equip_images/'.$row->stores_log->all_equips[0]->equip->equip_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    }
                })
                ->addColumn('source', function ($row) {
                    $btn = 'No Source';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    }


                    return $btn;
                })
                ->addColumn('status', function ($row) {
                    $btn = 'No Status';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    }

                    return $btn;
                })
                ->addColumn('quality', function ($row) {
                    $btn = 'No Quality';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    }
                    return $btn;
                })
                ->addColumn('amount', function ($row) {
                    $btn = 0;
                    if ($row->amount > 0) {
                        $btn = $row->amount;
                    } else {
                        $btn = 0;
                    }
                    return $btn;
                })
                ->addColumn('section', function ($row) {
                    $btn = '';
                    $secArr = [];
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->sectionswithoutorder) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->sectionswithoutorder); $i++) {
                                    if (in_array($row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->amount <= 0) {


                                    } else {

                                        $btn .= '<li>' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->amount  . '</span></li>';
                                        array_push($secArr, $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->id);
                                    }
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->sectionswithoutorder)) {
                                if (count($row->stores_log->all_kits[0]->sectionswithoutorder) > 0) {
                                    for ($i = 0; $i < count($row->stores_log->all_kits[0]->sectionswithoutorder); $i++) {
                                        if (in_array($row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->amount <= 0) {
                                        } else {

                                            $btn .= '<li>' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                            array_push($secArr, $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->id);
                                        }
                                    }
                                }
                            } else {
                                $btn = "No Section";
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (isset($row->stores_log->all_wheels[0]->sectionswithoutorder)) {
                                if (count($row->stores_log->all_wheels[0]->sectionswithoutorder) > 0) {
                                    for ($i = 0; $i < count($row->stores_log->all_wheels[0]->sectionswithoutorder); $i++) {
                                        if (in_array($row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->amount <= 0) {
                                        } else {

                                            $btn .= '<li>' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                            array_push($secArr, $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->id);
                                        }
                                    }
                                }
                            } else {
                                $btn = "No Section";
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (count($row->stores_log->all_tractors[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_tractors[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_tractors[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_tractors[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (count($row->stores_log->all_clarks[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_clarks[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_clarks[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_clarks[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (count($row->stores_log->all_equips[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_equips[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_equips[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_equips[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    }
                    return $btn;
                })
                ->addColumn('price', function ($row) {
                    $btn = 0;
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                $dataArr =collect($row->stores_log->all_parts[0]->pricing);


                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;
                                if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->pricing)) {
                                if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                    $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                      $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (isset($row->stores_log->all_equips[0]->pricing)) {
                                if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_equips[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    }

                    return $btn;
                })

                ->addColumn('partnumbers', function ($row) {
                    $btn = 'no num';
                    if ($row->type_id == 1) {
                        if (isset($row->stores_log->all_parts[0]->part->part_numbers)) {
                            if (count($row->stores_log->all_parts[0]->part->part_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->part->part_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_parts[0]->part->part_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 6) {
                        if (isset($row->stores_log->all_kits[0]->kit->kit_numbers)) {
                            if (count($row->stores_log->all_kits[0]->kit->kit_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_kits[0]->kit->kit_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_kits[0]->kit->kit_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 2) {
                    } elseif ($row->type_id == 3) {
                    } elseif ($row->type_id == 4) {
                    } elseif ($row->type_id == 5) {
                    }

                    return $btn;
                })
                ->addColumn('stores_amount', function ($row) {
                    $btn = '<ul class="list-group fs-13 text-truncate">';
                    if(isset($row->stores_amount)){
                        for ($i=0; $i < count($row->stores_amount) ; $i++) {
                            $btn .='<li>'.$row->stores_amount[$i]->name.'/'.$row->stores_amount[$i]->storepartCount.'</li>';

                        }
                    }else{
                        $btn .='<li>لا يوجد بالمخازن</li>';

                    }

                    $btn .= '</ul>';
                    return  $btn;

                })
                ->addColumn('view', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_parts[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_kits[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_wheels[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_tractors[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_clarks[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_equips[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    }

                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_parts) > 0) {
                                if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                    // $price = $row->stores_log->all_parts[0]->pricing[0]->price;

                                     $dataArr =collect($row->stores_log->all_parts[0]->pricing);
                                     $maxObject = $dataArr->sortByDesc('price')->first();


                                    $price =$maxObject->price ;
                                    $SaleType = $maxObject->sale_type;

                                    $prices = $row->stores_log->all_parts[0]->pricing;
                                    $prices_json = json_encode($prices);
                                }

                                if (count($row->stores_log->all_parts[0]->part->part_details) > 0) {
                                    $part_specs = $row->stores_log->all_parts[0]->part->part_details;
                                    $part_specs_json = json_encode($part_specs);
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_parts[0]->part->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_parts[0]->source_id) . ', ' . json_encode($row->stores_log->all_parts[0]->status_id) . ', ' . json_encode($row->stores_log->all_parts[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_kits) > 0) {
                                if (isset($row->stores_log->all_kits[0]->pricing)) {
                                    if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_kits[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_kits[0]->pricing[0]->price;
                                        $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;

                                        $prices = $row->stores_log->all_kits[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_kits[0]->kit->kit_details)) {
                                    if (count($row->stores_log->all_kits[0]->kit->kit_details) > 0) {
                                        $part_specs = $row->stores_log->all_kits[0]->kit->kit_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_kits[0]->kit->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_kits[0]->source_id) . ', ' . json_encode($row->stores_log->all_kits[0]->status_id) . ', ' . json_encode($row->stores_log->all_kits[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_wheels) > 0) {
                                if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                    if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_wheels[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_wheels[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_wheels[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_wheels[0]->wheel->wheel_details)) {
                                    if (count($row->stores_log->all_wheels[0]->wheel->wheel_details) > 0) {
                                        $part_specs = $row->stores_log->all_wheels[0]->wheel->wheel_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_wheels[0]->wheel->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_wheels[0]->source_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->status_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_tractors) > 0) {
                                if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                    if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_tractors[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_tractors[0]->pricing[0]->price;
                                          $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_tractors[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_tractors[0]->tractor->tractor_details)) {
                                    if (count($row->stores_log->all_tractors[0]->tractor->tractor_details) > 0) {
                                        $part_specs = $row->stores_log->all_tractors[0]->tractor->tractor_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_tractors[0]->tractor->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_tractors[0]->source_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->status_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_clarks) > 0) {
                                if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                    if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_clarks[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_clarks[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_clarks[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_clarks[0]->clark->clark_details)) {
                                    if (count($row->stores_log->all_clarks[0]->clark->clark_details) > 0) {
                                        $part_specs = $row->stores_log->all_clarks[0]->clark->clark_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_clarks[0]->clark->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_clarks[0]->source_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->status_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_equips) > 0) {
                                if (isset($row->stores_log->all_equips[0]->pricing)) {
                                    if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_equips[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_equips[0]->pricing[0]->price;
                                        $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_equips[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_equips[0]->equip->equip_details)) {
                                    if (count($row->stores_log->all_equips[0]->equip->equip_details) > 0) {
                                        $part_specs = $row->stores_log->all_equips[0]->equip->equip_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_equips[0]->equip->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_equips[0]->source_id) . ', ' . json_encode($row->stores_log->all_equips[0]->status_id) . ', ' . json_encode($row->stores_log->all_equips[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ', 1,' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    }
                    return '';
                })
                ->addColumn('send', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    }

                    return $btn;
                })
                ->addColumn('talef', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';


                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $store_id = $row->stores_log->store_id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                        $partId . '/' .
                        $sourceId . '/' .
                        $statusId . '/' .
                        $partQualityId . '/' .
                        $typeId . '/' .
                        $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $store_id = $row->stores_log->store_id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    }

                    return $btn;
                })
                ->rawColumns(['stores_amount','view','Image' ,'name', 'source', 'status', 'quality', 'amount', 'section', 'price', 'partnumbers', 'action', 'send','talef'])
                ->setTotalRecords(20)
                // ->filter(function ($query) {
                //     return $query;
                // })
                ->make(true);
        }
        // return view('testpos');
    }
    
     public function PartInStoresCount($partId, $sourceId, $statusId, $qualityId, $type)
    {
        // get all stores
        $stores = Store::all();

        if ($type == 1) {
            return $stores->each(function ($item) use ($partId, $sourceId, $statusId, $qualityId, $type) {
                // $storeClass = 'App\Models\\'.ucfirst($item->table_name);
                $item->storepart = DB::table($item->table_name)
                    ->select($item->table_name . '.*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                    ->where('all_parts.part_id', '=', $partId)
                    ->where('all_parts.source_id', '=', $sourceId)
                    ->where('all_parts.status_id', '=', $statusId)
                    ->where('all_parts.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->get();

                $item->storepartCount = DB::table($item->table_name)
                    ->select('*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                    ->where('all_parts.part_id', '=', $partId)
                    ->where('all_parts.source_id', '=', $sourceId)
                    ->where('all_parts.status_id', '=', $statusId)
                    ->where('all_parts.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->where($item->table_name . '.type_id', '=', $type)
                    ->sum($item->table_name . '.amount');
            });
        } elseif ($type == 6) {
            return $stores->each(function ($item) use ($partId, $sourceId, $statusId, $qualityId, $type) {
                // $storeClass = 'App\Models\\'.ucfirst($item->table_name);
                $item->storepart = DB::table($item->table_name)
                    ->select($item->table_name . '.*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                    ->where('all_kits.part_id', '=', $partId)
                    ->where('all_kits.source_id', '=', $sourceId)
                    ->where('all_kits.status_id', '=', $statusId)
                    ->where('all_kits.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->get();

                $item->storepartCount = DB::table($item->table_name)
                    ->select('*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                    ->where('all_kits.part_id', '=', $partId)
                    ->where('all_kits.source_id', '=', $sourceId)
                    ->where('all_kits.status_id', '=', $statusId)
                    ->where('all_kits.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->where($item->table_name . '.type_id', '=', $type)
                    ->sum($item->table_name . '.amount');
            });
        } elseif ($type == 2) {
            return $stores->each(function ($item) use ($partId, $sourceId, $statusId, $qualityId, $type) {
                // $storeClass = 'App\Models\\'.ucfirst($item->table_name);
                $item->storepart = DB::table($item->table_name)
                    ->select($item->table_name . '.*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                    ->where('all_wheels.part_id', '=', $partId)
                    ->where('all_wheels.source_id', '=', $sourceId)
                    ->where('all_wheels.status_id', '=', $statusId)
                    ->where('all_wheels.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->get();

                $item->storepartCount = DB::table($item->table_name)
                    ->select('*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                    ->where('all_wheels.part_id', '=', $partId)
                    ->where('all_wheels.source_id', '=', $sourceId)
                    ->where('all_wheels.status_id', '=', $statusId)
                    ->where('all_wheels.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->where($item->table_name . '.type_id', '=', $type)
                    ->sum($item->table_name . '.amount');
            });
        } elseif ($type == 3) {
            return $stores->each(function ($item) use ($partId, $sourceId, $statusId, $qualityId, $type) {
                // $storeClass = 'App\Models\\'.ucfirst($item->table_name);
                $item->storepart = DB::table($item->table_name)
                    ->select($item->table_name . '.*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_tractors', 'stores_log.All_part_id', '=', 'all_tractors.id')
                    ->where('all_tractors.part_id', '=', $partId)
                    ->where('all_tractors.source_id', '=', $sourceId)
                    ->where('all_tractors.status_id', '=', $statusId)
                    ->where('all_tractors.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->get();

                $item->storepartCount = DB::table($item->table_name)
                    ->select('*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_tractors', 'stores_log.All_part_id', '=', 'all_tractors.id')
                    ->where('all_tractors.part_id', '=', $partId)
                    ->where('all_tractors.source_id', '=', $sourceId)
                    ->where('all_tractors.status_id', '=', $statusId)
                    ->where('all_tractors.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->where($item->table_name . '.type_id', '=', $type)
                    ->sum($item->table_name . '.amount');
            });
        } elseif ($type == 4) {
            return $stores->each(function ($item) use ($partId, $sourceId, $statusId, $qualityId, $type) {
                // $storeClass = 'App\Models\\'.ucfirst($item->table_name);
                $item->storepart = DB::table($item->table_name)
                    ->select($item->table_name . '.*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_clarks', 'stores_log.All_part_id', '=', 'all_clarks.id')
                    ->where('all_clarks.part_id', '=', $partId)
                    ->where('all_clarks.source_id', '=', $sourceId)
                    ->where('all_clarks.status_id', '=', $statusId)
                    ->where('all_clarks.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->get();

                $item->storepartCount = DB::table($item->table_name)
                    ->select('*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_clarks', 'stores_log.All_part_id', '=', 'all_clarks.id')
                    ->where('all_clarks.part_id', '=', $partId)
                    ->where('all_clarks.source_id', '=', $sourceId)
                    ->where('all_clarks.status_id', '=', $statusId)
                    ->where('all_clarks.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->where($item->table_name . '.type_id', '=', $type)
                    ->sum($item->table_name . '.amount');
            });
        } elseif ($type == 5) {
            return $stores->each(function ($item) use ($partId, $sourceId, $statusId, $qualityId, $type) {
                // $storeClass = 'App\Models\\'.ucfirst($item->table_name);
                $item->storepart = DB::table($item->table_name)
                    ->select($item->table_name . '.*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_equips', 'stores_log.All_part_id', '=', 'all_equips.id')
                    ->where('all_equips.part_id', '=', $partId)
                    ->where('all_equips.source_id', '=', $sourceId)
                    ->where('all_equips.status_id', '=', $statusId)
                    ->where('all_equips.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->get();

                $item->storepartCount = DB::table($item->table_name)
                    ->select('*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_equips', 'stores_log.All_part_id', '=', 'all_equips.id')
                    ->where('all_equips.part_id', '=', $partId)
                    ->where('all_equips.source_id', '=', $sourceId)
                    ->where('all_equips.status_id', '=', $statusId)
                    ->where('all_equips.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->where($item->table_name . '.type_id', '=', $type)
                    ->sum($item->table_name . '.amount');
            });
        }
    }
    
    public function asksrore_getdata(Request $request){
      
      return  $this->PartInStoresCount($request->partId, $request->SourceId, $request->StatusId, $request->QualityId, $request->typeId);
    }
    public function askfromStoreNew(Request $request){
        // return $request;
        // return $request->partId[0]->part->small_unit;

          DB::beginTransaction();
        try {
            for($i=0 ; $i < count($request->partId) ; $i++){
                if($request->typeId[$i] == 1){
                    $p_data = Part::find($request->partId[$i]);
                    $samllmeasureUnits = $p_data->small_unit;
                    $measureUnit = $request->unitask_id;
                    $ratiounit = getSmallUnit($measureUnit, $samllmeasureUnits);
                    $askAmount = $request->askAmount[$i] * $ratiounit;
                }else{
                    $askAmount = $request->askAmount[$i] ;

                }
            
                if( $askAmount > 0){
                    DemandPart::create([
                        'part_id' => $request->partId[$i],
                        'source_id' => $request->sourceId[$i],
                        'status_id' => $request->statusId[$i],
                        'quality_id' => $request->qualityId[$i],
                        'amount' => $askAmount,
                        'type_id' => $request->typeId[$i],
                        'flag_send' => 0,
                        'from_store_id' => $request->Store_id[$i],
                        'to_store_id' => $request->askedStore_id[$i],
                        'user_id' => Auth::user()->id,
                        'unit_id'=> $request->unitask_id,
                    ]);
                }else{
    
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $e) { 
            DB::rollback();
            // dd($e);
        }
      
      
       
    }

    public function getCountotherStoreAsk($store_id){
        return DemandPart::where('to_store_id',$store_id)->where('flag_send',0)->count();

    }
    public function askStoreInbox($store_id){

        $store_data = Store::where('id',$store_id)->get();
        $data = DemandPart::where('to_store_id',$store_id)->where('flag_send',0)
        ->with('tostore')
        ->with('source')
        ->with('status')
        ->with('part_quality')
        ->with('fromstore')
        ->with('unit')
        ->get();
        foreach ($data as $key => $item) {
            $item['storeSection']  = StoreSection::where('part_id', $item->part_id)
            ->where('type_id', $item->type_id)
            ->where('source_id', $item->source_id)
            ->where('status_id', $item->status_id)
            ->where('quality_id', $item->quality_id)
            ->where('store_id', $store_id)
            ->select('section_id', DB::raw('SUM(amount) as amount'),'unit_id')  // Summing up the amounts
            ->groupBy('section_id')  // Grouping by section_id
            ->with('store_structure')  // Eager load store_structure
            ->with('unit')
            ->get();
           if($item->type_id == 3){
                $item['type'] = 'جرار';
                $item['itemData'] = Tractor::where('id',$item->part_id)->first();
                
           }elseif($item->type_id == 4){
                $item['type'] = 'كلارك';
                $item['itemData'] = Clark::where('id',$item->part_id)->first();
           }elseif($item->type_id == 5){
                $item['type'] = 'معدة';
                $item['itemData'] = Equip::where('id',$item->part_id)->first();
           }elseif($item->type_id == 1){
                $item['type'] = 'قطعة غيار';
                $item['itemData'] = Part::where('id',$item->part_id)->with('getsmallunit')->first();
            }elseif($item->type_id == 6){
                $item['type'] = 'كيت';
                $item['itemData'] = Kit::where('id',$item->part_id)->first();
            }elseif($item->type_id == 2){
                $item['type'] = 'كاوتش';
                $item['itemData'] = Wheel::where('id',$item->part_id)->first();
            }
            // return $data;
        }
        // return $data;
        return view('storeAskedParts',compact('store_data','data'));
    }
    // public function SendToStoreNewFromAsk(Request $request)
    // {
    //     // return $request;
    //     $logMessage='';
    //     $logUser = Auth::user()->id;
        
    //     DB::beginTransaction();
    //     try {

    //         $otherStore = Store::where('id', $request->storeId)->first();
    //         $currentStore = Store::where('id', $request->CurrentstoreId)->first();
    //         $allinvoices = new Collection();
    //         if ($request->partTypeS == 1) {
    //             $allinvoices = AllPart::where('remain_amount', '>', 0)->where('part_id', $request->partIdS)->where('source_id', $request->partSourceS)->where('status_id', $request->partStatusS)->where('quality_id', $request->partQualityS)->orderBy('id')->get();
    //         } else if ($request->partTypeS == 2) {
    //             $allinvoices = AllWheel::where('remain_amount', '>', 0)->where('part_id', $request->partIdS)->where('source_id', $request->partSourceS)->where('status_id', $request->partStatusS)->where('quality_id', $request->partQualityS)->orderBy('id')->get();
    //         } else if ($request->partTypeS == 6) {
    //             $allinvoices = AllKit::where('remain_amount', '>', 0)->where('part_id', $request->partIdS)->where('source_id', $request->partSourceS)->where('status_id', $request->partStatusS)->where('quality_id', $request->partQualityS)->orderBy('id')->get();
    //         } else if ($request->partTypeS == 3) {
    //             $allinvoices = AllTractor::where('remain_amount', '>', 0)->where('part_id', $request->partIdS)->where('source_id', $request->partSourceS)->where('status_id', $request->partStatusS)->where('quality_id', $request->partQualityS)->orderBy('id')->get();
    //         } else if ($request->partTypeS == 4) {
    //             $allinvoices = AllClark::where('remain_amount', '>', 0)->where('part_id', $request->partIdS)->where('source_id', $request->partSourceS)->where('status_id', $request->partStatusS)->where('quality_id', $request->partQualityS)->orderBy('id')->get();
    //         } else if ($request->partTypeS == 5) {
    //             $allinvoices = AllEquip::where('remain_amount', '>', 0)->where('part_id', $request->partIdS)->where('source_id', $request->partSourceS)->where('status_id', $request->partStatusS)->where('quality_id', $request->partQualityS)->orderBy('id')->get();
    //         }
    //         $lastlog = 0;
    //         $alllogs = [];
    //         // $sendAmount = $request->sendAmount;
    //         foreach ($allinvoices as $key => $element) {
    //             if($element->type_id == 1){
    //                 $samllmeasureUnits = $element->part->small_unit;
    //                 $measureUnit = $element->part->big_unit;
    //                 $ratiounit = getSmallUnit($measureUnit, $samllmeasureUnits);
    //                 $sendAmount = $request->sendAmount * $ratiounit;
    //             }else{
    //                 $sendAmount = $request->sendAmount;
    //             }
              
    //             if ($element->remain_amount >= $sendAmount) {
                   
    //                 $ins_id = StoresLog::create([
    //                     'All_part_id' => $element->id,
    //                     'store_action_id' => 2,  //خروج من مخزن
    //                     'store_id' => $request->CurrentstoreId,
    //                     'amount' => $sendAmount,
    //                     // 'user_id' => auth()->user()->id,
    //                     'status' => 2,
    //                     'date' => date('Y-m-d H:i:s'),
    //                     'type_id' => $request->partTypeS,
    //                     'notes' => 'To :' . $otherStore->name,
    //                 ])->id;
                    
    //                 $logMessage.='سيتم خروج من مخزن الكمية '.$sendAmount.'من AllPartId'.$element->id.' الي'.$otherStore->name.' من'.$currentStore->name.'<br/>';
                    
    //                 $lastlog = StoresLog::create([
    //                     'All_part_id' => $element->id,
    //                     'store_action_id' => 3,  //دخول من مخزن
    //                     'store_id' => $otherStore->id,
    //                     'amount' => $sendAmount,
    //                     // 'user_id' => auth()->user()->id,
    //                     'status' => $ins_id,
    //                     'date' => date('Y-m-d H:i:s'),
    //                     'type_id' => $request->partTypeS,
    //                     'notes' => 'from :' . $currentStore->name,
    //                 ])->id;
                    
    //                 $logMessage.='سيتم دخول من مخزن الكمية '.$sendAmount.'من AllPartId'.$element->id.' من'.$currentStore->name.' الي'.$otherStore->name.'<br/>';
                    
    //                 array_push($alllogs, $ins_id);
                        
    //                 break;
    //             } elseif ($element->remain_amount < $sendAmount) {
    //                 $ins_id = StoresLog::create([
    //                     'All_part_id' => $element->id,
    //                     'store_action_id' => 2,
    //                     'store_id' => $request->CurrentstoreId,
    //                     'amount' => $element->remain_amount,
    //                     // 'user_id' => auth()->user()->id,
    //                     'status' => 2,
    //                     'date' => date('Y-m-d H:i:s'),
    //                     'type_id' => $request->partTypeS,
    //                     'notes' => 'To :' . $otherStore->name,
    //                 ])->id;

    //                 $logMessage.='سيتم خروج من مخزن الكمية '.$element->remain_amount.'من AllPartId'.$element->id.' من'.$currentStore->name.' الي'.$otherStore->name.'<br/>';
                    
    //                 $lastlog = StoresLog::create([
    //                     'All_part_id' => $element->id,
    //                     'store_action_id' => 3,
    //                     'store_id' => $otherStore->id,
    //                     'amount' => $element->remain_amount,
    //                     // 'user_id' => auth()->user()->id,
    //                     'status' => $ins_id,
    //                     'date' => date('Y-m-d H:i:s'),
    //                     'type_id' => $request->partTypeS,
    //                     'notes' => 'from :' . $currentStore->name,
    //                 ])->id;
                    
    //                 $logMessage.='سيتم دخول من مخزن الكمية '.$element->remain_amount.'من AllPartId'.$element->id.' من'.$currentStore->name.' الي'.$otherStore->name.'<br/>';
                    
    //                 $sendAmount = $sendAmount - $element->remain_amount;
    //                 array_push($alllogs, $ins_id);
    //             } else if ($sendAmount < 0) {
    //                 break;
    //             }
    //         }
            
    //         if (isset($request->sectionIds)) {
    //             foreach ($request->sectionIds as $key => $value) {
    //                 if (intval($request->sectionAmount[$key]) > 0) {
    //                     StorelogSection::create([
    //                         'store_log_id' => $alllogs[0],
    //                         // 'store_log_id' => $lastlog,
    //                         'section_id' =>  intval($value),
    //                         'store_id' =>  $currentStore->id,
    //                         'amount' => intval($request->sectionAmount[$key]),
    //                         'notes' => implode(" ", $alllogs)
    //                     ]);
    //                 }
    //             }
    //         }
            
    //         $log = new LogController();
    //         $log->newLog($logUser,$logMessage);
            
    //         DB::commit();
    //         return true;
    //         // return redirect()->back();
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         session()->flash("success", "");
    //         return $e;
    //         // return redirect()->back();
    //     }
    // }

}




