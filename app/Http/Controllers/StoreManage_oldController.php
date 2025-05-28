<?php

namespace App\Http\Controllers;

use App\Imports\ImportParts;
use App\Models\BuyTransaction;
use App\Models\PartQuality;
use App\Models\Source;
use App\Models\Status;
use Doctrine\DBAL\Schema\View;
use Illuminate\Http\Request;
use DataTables;
// use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportUser;
use App\Models\AllKit;
use App\Models\AllPart;
use App\Models\AllWheel;
use App\Models\BankSafeMoney;
use App\Models\CurrencyType;
use App\Models\Kit;
use App\Models\KitNumber;
use App\Models\MoneySafe;
use App\Models\OrderSupplier;
use App\Models\StoreSection;
use App\Models\Part;
use App\Models\PartNumber;
use App\Models\Replyorder;
use App\Models\Store;
use App\Models\StoresLog;
use App\Models\Supplier;
use App\Models\BranchTree;
use App\Models\Client;
use App\Models\Wheel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\DataTables as DataTablesDataTables;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;
use App\Http\Controllers\QaydController;
use App\Models\BankType;

class StoreManageController extends Controller
{
    /** فاتورة شراء
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        // return BuyTransaction::all();
        $bank_types=BankType::all();
        $store_safe=Store::where('safe_accountant_number','<>','0')->get();
        // $lastInvId = BuyTransaction::latest()->first()->id;
        return view('buy_invoice',compact('bank_types','store_safe'));
    }
    public function GetAllstores(){
        return Store::all();
    }
    public function indexWithRequest(Request $request)
    {
        if ($request->ajax()) {
            $data = BuyTransaction::with('company')->get();
            // return $data;
            return FacadesDataTables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    // $dd = explode(' ', $row->date);
                    // return $dd[0];
                    return $row->date;
                })
                ->addColumn('companyid', function ($row) {
                    return $row->company->id;
                })
                ->addColumn('companyName', function ($row) {
                    return $row->company->name;
                })
                ->addColumn('supplierName', function ($row) {
                    $ordersup = OrderSupplier::where('transaction_id',$row->id)->with('supplier')->first();
                    if(isset($ordersup->supplier)){
                        return $ordersup->supplier->name;
                    }else{
                        return "-----";
                    }


                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="buyInv/' . $row->id . '" target="_blank" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editPost"><i class="ri-edit-line"></i></a>';

                    $btn = $btn . ' <a href="#" onclick="DeleteInv(' . $row->id . ')" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deletePost"><i class="ri-delete-bin-5-line"></i></a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Send to Store" class="btn btn-secondary btn-sm sendStore"><i class="ri-share-circle-line"></i></a>';
                    $btn = $btn . ' <a href="printBuyInvoice/' . $row->id . '"  data-original-title="Print" class="btn btn-sm btn-success "><i class="ri-printer-line"></i></a>';

                    return $btn;
                })
                ->rawColumns(['companyid', 'companyName','supplierName' ,'action'])
                ->make(true);
        }

        // $lastInvId = BuyTransaction::latest()->first()->id;
        return view('buy_invoice');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // return $request;
        $BuyTransaction = new BuyTransaction();
        $BuyTransaction->company_id = '10';
        $BuyTransaction->date = $request->invDate;
        $BuyTransaction->creation_date = Carbon::now();
        $BuyTransaction->name = Carbon::now();
        $BuyTransaction->final = '3';
        $BuyTransaction->save();
        $sup_id = 0;
        if($request->NewinvSupplier <>  null){
            // add new Supplier
            $sup = new Supplier();
            $sup->name = $request->NewinvSupplier;
            $sup->save();
            $sup_id = $sup->id;

        }else{

            $sup_id = $request->invSupplier;
        }
        $orderSupplier = new OrderSupplier();
        $orderSupplier->transaction_id = $BuyTransaction->id;
        $orderSupplier->supplier_id = $sup_id;
        $orderSupplier->pricebeforeTax = $request->invTotLbl;
        // $orderSupplier->send_mail = '';
        // $orderSupplier->notes = '';
        $orderSupplier->status = '4';
        $orderSupplier->deliver_date = Carbon::now();
        // $orderSupplier->currency_id = 400;
        $orderSupplier->currency_id = $request->currency_id;
        $orderSupplier->total_price = $request->invAllTotal;
        // $orderSupplier->bank_account = '';
        // $orderSupplier->container_size = '';
        $orderSupplier->confirmation_date = Carbon::now();
        // $orderSupplier->image_url = '';
        $orderSupplier->tax = $request->invTax;
        $orderSupplier->save();

        for ($i=0; $i < count($request->partId) ; $i++) {
            # code...
            $part = new Replyorder();
            $part->order_supplier_id = $orderSupplier->id;
            $part->part_id = $request->partId[$i];
            $part->price = $request->price[$i];
            $part->amount = $request->amount[$i];
            $part->source_id = $request->partSource[$i];
            $part->status_id = $request->partStatus[$i];
            $part->quality_id = $request->partQualty[$i];
            $part->creation_date = Carbon::now();
            $part->part_type_id = $request->types[$i];
            $part->save();

            if($request->types[$i] == 1){
                $allpart = new AllPart();
                $allpart->part_id = $request->partId[$i];;
                $allpart->order_supplier_id = $orderSupplier->id;
                $allpart->amount = $request->amount[$i];
                $allpart->source_id = $request->partSource[$i];
                $allpart->status_id = $request->partStatus[$i];
                $allpart->quality_id = $request->partQualty[$i];
                $allpart->buy_price = $request->price[$i];
                $allpart->insertion_date = Carbon::now();
                $allpart->remain_amount = $request->amount[$i];
                $allpart->flag = 3;
                $allpart->save();
            }

            ///// kit
            if($request->types[$i] == 6){
                $allpart = new AllKit();
                $allpart->part_id = $request->partId[$i];;
                $allpart->order_supplier_id = $orderSupplier->id;
                $allpart->amount = $request->amount[$i];
                $allpart->source_id = $request->partSource[$i];
                $allpart->status_id = $request->partStatus[$i];
                $allpart->quality_id = $request->partQualty[$i];
                $allpart->buy_price = $request->price[$i];
                $allpart->insertion_date = Carbon::now();
                $allpart->remain_amount = $request->amount[$i];
                $allpart->flag = 3;
                $allpart->save();
            }

            /// wheel
            if($request->types[$i] == 2){
                $allpart = new AllWheel();
                $allpart->part_id = $request->partId[$i];;
                $allpart->order_supplier_id = $orderSupplier->id;
                $allpart->amount = $request->amount[$i];
                $allpart->source_id = $request->partSource[$i];
                $allpart->status_id = $request->partStatus[$i];
                $allpart->quality_id = $request->partQualty[$i];
                $allpart->buy_price = $request->price[$i];
                $allpart->insertion_date = Carbon::now();
                $allpart->remain_amount = $request->amount[$i];
                $allpart->flag = 3;
                $allpart->save();
            }



        }

        return redirect()->to('printBuyInvoice/'.$BuyTransaction->id);

    }

    public function storeManage(Request $request)
    {


        // return $request;
        // return "فقط";
        $quaditems = [];
        $automaicQayd = new QaydController();
        $invoiceac = 0;
        $taxac = 0;
        $taxAc2 = 0;
        $binvoiceac =0;
        $makzon_val = 0;
        $buyCoastAc = 0 ;
        // $request->invTax الضريبة
        // $request->invTotLbl السعر قبل الضريبة
        // $request->invPaied; المدفوع
        // $request->invAllTotal الاجمالي بعد الضريبة
        // $request->payment
        // $request->taxInvolved // شامل الضريبة
        // $request->taxkasmInvolved // شامل الضريبة الخصم
        // $request->currency_id // العملة

        $Ac_currency_id = $request->currency_id;
        $Ac_all_currency_types=CurrencyType::with(['currencies'=>function($query) use($Ac_currency_id){
                return $query->where('to',null)->where('currency_id',$Ac_currency_id);
            }])->where('id',$Ac_currency_id)->get();

        if($request->transCoast > 0){
            array_push ( $quaditems , (object) [ 'acountant_id'=> 33101  , 'dayin'=> 0 , 'madin'=> $request->transCoast ] );
            $buyCoastAc += $request->transCoast;
        }
        if($request->insuranceCoast > 0){
            array_push ( $quaditems , (object) [ 'acountant_id'=> 33102  , 'dayin'=> 0 , 'madin'=> $request->insuranceCoast ] );
            $buyCoastAc += $request->insuranceCoast;
        }
        if($request->customs > 0){
            array_push ( $quaditems , (object) [ 'acountant_id'=> 33103  , 'dayin'=> 0 , 'madin'=> $request->customs ] );
            $buyCoastAc += $request->customs;
        }
        if($request->commition > 0){
            array_push ( $quaditems , (object) [ 'acountant_id'=> 33104  , 'dayin'=> 0 , 'madin'=> $request->commition ] );
            $buyCoastAc += $request->commition;
        }
        if($request->otherCoast > 0){
            array_push ( $quaditems , (object) [ 'acountant_id'=> 33105  , 'dayin'=> 0 , 'madin'=> $request->otherCoast ] );
            $buyCoastAc += $request->otherCoast;
        }

        if(floatval($request->taxInvolved) == 1 ){
            // غير شامل
            if($request->invTax){
                 $taxac = $request->invTotLbl * $request->invTax /100 ; // الضريبة
            }else{
                 $taxac = $request->invTotLbl * 14 /100 ; // الضريبة
            }

            $invoiceac = $request->invTotLbl + $taxac; //الاجمالي بعد الضريبة
            $binvoiceac =round($invoiceac - $taxac);

        }else{
            // شامل
            $binvoiceac = round($request->invAllTotal / 1.14);
            $taxac = $request->invAllTotal -$binvoiceac ; // الضريبة
            $invoiceac =  round($binvoiceac + $taxac);
        }

        if(floatval($request->taxkasmInvolved) == 1 ){
            $taxAc2 = $request->invTotLbl * -1 / 100;
            $invoiceac = $invoiceac + $taxAc2;
        }else{
           $taxAc2 = 0;
        }

        if($request->payment == 0  && $invoiceac == $request->invPaied) // البيع كاش
        {
            // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac *  $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
            array_push ( $quaditems , (object) [ 'acountant_id'=> 2641  , 'madin'=> $binvoiceac *  $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المشتريات مدين
            $makzon_val = $binvoiceac *  $Ac_all_currency_types[0]->currencies[0]->value;
            array_push ( $quaditems , (object) [ 'acountant_id'=> $request->store_id , 'madin'=> 0 , 'dayin'=> ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value)+$buyCoastAc ] ); // الخزنة دائن

        }elseif($request->payment == 1 && $invoiceac == $request->invPaied ) // البيع شيك
        {
            // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
            array_push ( $quaditems , (object) [ 'acountant_id'=> 2641  , 'madin'=> $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المشتريات مدين
            $makzon_val = $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value;
            array_push ( $quaditems , (object) [ 'acountant_id'=> $request->store_id , 'madin'=> 0 , 'dayin'=> ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value)+$buyCoastAc ] ); // بنك دائن


        }else // البيع أجل
        {
            // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
            array_push ( $quaditems , (object) [ 'acountant_id'=> 2641  , 'madin'=> $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المشتريات مدين
             $makzon_val = $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value ;
            $acSupNo = Supplier::where('id',$request->invSupplier)->first()->accountant_number;

            array_push ( $quaditems , (object) [ 'acountant_id'=> $acSupNo , 'madin'=> 0 , 'dayin'=>($invoiceac- $request->invPaied) * $Ac_all_currency_types[0]->currencies[0]->value ] ); // العميل دائن
            if( $request->store_id != 0  ) // خزنة
            {
                if($request->invPaied > 0){
                    array_push ( $quaditems , (object) [ 'acountant_id'=> $request->store_id , 'madin'=> 0 , 'dayin'=> ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value)+$buyCoastAc ] ); // الخزنة دائن
                }

            }else // بنك
            {
                 if($request->invPaied > 0){
                     array_push ( $quaditems , (object) [ 'acountant_id'=> $request->store_id , 'madin'=> 0 , 'dayin'=> ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value)+$buyCoastAc ] ); // بنك دائن
                 }

            }

        }

        if($taxac > 0){
            array_push ( $quaditems , (object) [ 'acountant_id'=> 175  , 'dayin'=> 0 , 'madin'=> $taxac * $Ac_all_currency_types[0]->currencies[0]->value ] ); // الضريبة مدين
        }


         if($taxAc2 != 0){
            array_push ( $quaditems , (object) [ 'acountant_id'=> 2637  , 'dayin'=> $taxAc2 * $Ac_all_currency_types[0]->currencies[0]->value*-1 , 'madin'=> 0 ] ); // الضريبة مدين
        }



            // array_push ( $quaditems , (object) [ 'acountant_id'=> 15 , 'dayin'=> 1000 , 'madin'=>0 ] ); //  الضريبة
            // array_push ( $quaditems , (object) [ 'acountant_id'=> 15 , 'dayin'=> 1000 , 'madin'=>0 ] ); // المديونية
            // array_push ( $quaditems , (object) [ 'acountant_id'=> 15 , 'dayin'=> 1000 , 'madin'=>0 ] );// الخصم

            // array_push ( $quaditems , (object) [ 'acountant_id'=> 15 , 'dayin'=> 1000 , 'madin'=>0 ] ); // البنك / الشركة
            // array_push ( $quaditems , (object) [ 'acountant_id'=> 15 , 'dayin'=> 1000 , 'madin'=>0 ] ); // المورد







        if( isset($request->currency_id)  && isset($request->invSupplier)){
            $currency_id=$request->currency_id;
            // if ($request->store_id == 0) {
            //     $total = BankSafeMoney::all()->last();
            // } else {
            //     $total = MoneySafe::where('store_id', $request->store_id)->latest()->first();
            // }

            $Ac_allTotal = 0;
            $bank = BankType::where('accountant_number',$request->store_id)->first();
            if ($bank) {

                $raseed= $bank->bank_raseed;
                $total_qabd = BankSafeMoney::where('bank_type_id',$bank->id)->where('type_money',0)->sum('money');//قبض
                $total_sarf = BankSafeMoney::where('bank_type_id',$bank->id)->where('type_money',1)->sum('money');//صرف
                $total= floatval($raseed) + floatval($total_qabd) - floatval($total_sarf);
                $Ac_allTotal  = BankSafeMoney::all()->last();
            } else {

                $bank = Store::where('safe_accountant_number',$request->store_id)->first();
                $raseed= $bank->store_raseed;
                $total_qabd = MoneySafe::where('store_id',$bank->id)->where('type_money',0)->sum('money');//قبض
                $total_sarf = MoneySafe::where('store_id',$bank->id)->where('type_money',1)->sum('money');//صرف
                $total= floatval($raseed) + floatval($total_qabd) - floatval($total_sarf);
                $Ac_allTotal = MoneySafe::where('store_id', $bank->id)->latest()->first();
            }

            $all_currency_types=CurrencyType::with(['currencies'=>function($query) use($currency_id){
                return $query->where('to',null)->where('currency_id',$currency_id);
            }])->where('id',$currency_id)->get();
            if ($total >= 0) {
                if ($total >= $request->invPaied * $all_currency_types[0]->currencies[0]->value) {

                    $BuyTransaction = new BuyTransaction();
                    $BuyTransaction->company_id = '10';
                    $BuyTransaction->date = $request->invDate;
                    $BuyTransaction->creation_date = Carbon::now();
                    $BuyTransaction->name = Carbon::now();
                    $BuyTransaction->final = '3';
                    $BuyTransaction->save();
                    $sup_id = 0;
                    if($request->NewinvSupplier <>  null){
                        // add new Supplier
                        $sup = new Supplier();
                        $sup->name = $request->NewinvSupplier;
                        $sup->save();
                        $sup_id = $sup->id;

                    }else{

                        $sup_id = $request->invSupplier;
                    }


                    $supplierx = Supplier::where('id',$sup_id)->first();
                    if ($bank) {
                        $Ac_allTotal_total = 0;
                        if($Ac_allTotal){
                            $Ac_allTotal_total = $Ac_allTotal->total;
                        }
                        BankSafeMoney::create([
                            'notes' => 'صرف مبلغ فاتورة شراء رقم '.' '.$BuyTransaction->id.' '.'من مورد'.' '.$supplierx->name,
                            'date' => date('Y-m-d'),
                            'flag' => 1,
                            'money' => $request->invPaied * $all_currency_types[0]->currencies[0]->value,
                            'total' => $Ac_allTotal_total -($request->invPaied * $all_currency_types[0]->currencies[0]->value),
                            'type_money'=>'1',
                            'user_id'=>Auth::user()->id,
                            'store_id'=>null,
                            'bank_type_id'=>$bank->id,
                            'money_currency'=>$request->invPaied,
                            'currency_id'=>$currency_id,

                        ]);
                    } else {
                        $Ac_allTotal_total = 0;
                        if($Ac_allTotal){
                            $Ac_allTotal_total = $Ac_allTotal->total;
                        }
                        $xx = Store::where('safe_accountant_number',$request->store_id)->first();
                        MoneySafe::create([
                            'notes' => 'صرف مبلغ فاتورة شراء رقم '.' '.$BuyTransaction->id.' '.'من مورد'.' '.$supplierx->name,
                            'date' => date('Y-m-d'),
                            'flag' => 1,
                            'money' => $request->invPaied * $all_currency_types[0]->currencies[0]->value,
                            'total' => $Ac_allTotal_total -($request->invPaied * $all_currency_types[0]->currencies[0]->value),
                            'type_money'=>'1',
                            'user_id'=>Auth::user()->id,
                            'store_id'=>$xx->id,

                        ]);
                    }





                    $orderSupplier = new OrderSupplier();
                    $orderSupplier->transaction_id = $BuyTransaction->id;
                    $orderSupplier->supplier_id = $sup_id;
                    $orderSupplier->pricebeforeTax = $request->invTotLbl;
                    // $orderSupplier->notes = '';
                    $orderSupplier->status = '4';
                    $orderSupplier->deliver_date = Carbon::now();
                    // $orderSupplier->currency_id = 400;
                    $orderSupplier->currency_id = $request->currency_id;
                    $orderSupplier->total_price = $request->invAllTotal;
                    $orderSupplier->paied = $request->invPaied;
                    $orderSupplier->payment = $request->payment;

                    $orderSupplier->transport_coast = $request->transCoast;
                    $orderSupplier->insurant_coast = $request->insuranceCoast;
                    $orderSupplier->customs_coast = $request->customs;
                    $orderSupplier->commotion_coast = $request->commition;
                    $orderSupplier->other_coast = $request->otherCoast;
                    $orderSupplier->coast = $request->InvCoasting;

                    if($request->invPaied < $request->invAllTotal ){
                            $orderSupplier->due_date = $request->dueDate;
                            Supplier::where('id',$sup_id)->increment('raseed',(floatval($request->invAllTotal) - floatval($request->invPaied))*$all_currency_types[0]->currencies[0]->value );

                    }
                    // $orderSupplier->bank_account = '';
                    // $orderSupplier->container_size = '';
                    $orderSupplier->confirmation_date = Carbon::now();
                    $orderSupplier->tax = $request->invTax;
                    // $orderSupplier->image_url = '';
                    $orderSupplier->save();

                    for ($i=0; $i < count($request->partId) ; $i++) {
                        # code...
                        $part = new Replyorder();
                        $part->order_supplier_id = $orderSupplier->id;
                        $part->part_id = $request->partId[$i];
                        $part->price = $request->price[$i];
                        $part->amount = $request->amount[$i];
                        $part->source_id = $request->partSource[$i];
                        $part->status_id = $request->partStatus[$i];
                        $part->quality_id = $request->partQualty[$i];
                        $part->creation_date = Carbon::now();
                        $part->part_type_id = $request->types[$i];
                        $part->save();

                        if($request->types[$i] == 1){
                            $ratio=floatval($request->InvCoasting) / floatval($request->invTotLbl);
                            $itemCoast=($ratio *  (floatval($request->price[$i]) * intval($request->amount[$i])))/intval($request->amount[$i]);

                            $allpart = new AllPart();
                            $allpart->part_id = $request->partId[$i];
                            $allpart->order_supplier_id = $orderSupplier->id;
                            $allpart->amount = $request->amount[$i];
                            $allpart->source_id = $request->partSource[$i];
                            $allpart->status_id = $request->partStatus[$i];
                            $allpart->quality_id = $request->partQualty[$i];
                            $allpart->buy_price = $request->price[$i];
                            $allpart->insertion_date = Carbon::now();
                            $allpart->remain_amount = $request->amount[$i];
                            $allpart->flag = 1;
                            $allpart->buy_costing = $itemCoast;
                            $allpart->save();

                            // if(isset($request->Sections) && count($request->Sections) > 0){
                            //     // ghoniemstoreLog
                            //     $storelog = new StoresLog();
                            //     $storelog->All_part_id = $allpart->id;
                            //     $storelog->store_id = $request->Stores[$i];
                            //     $storelog->store_action_id = 1;
                            //     $storelog->amount = $request->amount[$i];
                            //     $storelog->date = Carbon::now();
                            //     $storelog->status = 1;
                            //     $storelog->type_id = $request->types[$i];
                            //     $storelog->save();

                            //     $storelog = new StoresLog();
                            //     $storelog->All_part_id = $allpart->id;
                            //     $storelog->store_id = $request->Stores[$i];
                            //     $storelog->store_action_id = 3;
                            //     $storelog->amount = $request->amount[$i];
                            //     $storelog->date = Carbon::now();
                            //     $storelog->status = 3;
                            //     $storelog->type_id = $request->types[$i];
                            //     $storelog->save();


                            //     $store = Store::where('id',$request->Stores[$i])->get();
                            //     // return $store;

                            //     $storeClsName=ucfirst($store[0]->table_name);
                            //     $storeClsName ='App\Models\\'.$storeClsName;
                            //     $storeClsName = str_replace([' ','_','-'],"",$storeClsName);

                            //     // return $storeClsName;
                            //     if($store[0]->table_name == "damaged_parts"){
                            //         $storeClsName = "App\Models\\DamagedPart";
                            //     }
                            //     try {
                            //         //code...
                            //         $storeCls = new $storeClsName();

                            //         $storeCls->part_id = $request->partId[$i];
                            //         $storeCls->amount = $request->amount[$i];
                            //         $storeCls->supplier_order_id = $orderSupplier->id;
                            //         $storeCls->type_id =$request->types[$i];
                            //         $storeCls->store_log_id =$storelog->id ;
                            //         $storeCls->date = Carbon::now();
                            //         $storeCls->save();


                            //         $section = new StoreSection();
                            //         $section->store_id = $request->Stores[$i];
                            //         $section->section_id = $request->Sections[$i];
                            //         $section->order_supplier_id = $orderSupplier->id;
                            //         $section->type_id = $request->types[$i];
                            //         $section->part_id = $request->partId[$i];
                            //         $section->source_id = $request->partSource[$i];
                            //         $section->status_id = $request->partStatus[$i];
                            //         $section->quality_id = $request->partQualty[$i];
                            //         $section->amount = $request->amount[$i];
                            //         $section->date = Carbon::now();
                            //         $section->save();

                            //     } catch (\Throwable $th) {
                            //         //throw $th;
                            //         continue;
                            //     }

                            // }

                        }

                        ///// kit
                        if($request->types[$i] == 6){
                            $ratio=floatval($request->InvCoasting) / floatval($request->invTotLbl);
                            $itemCoast=($ratio *  (floatval($request->price[$i]) * intval($request->amount[$i])))/intval($request->amount[$i]);


                            $allpart = new AllKit();
                            $allpart->part_id = $request->partId[$i];;
                            $allpart->order_supplier_id = $orderSupplier->id;
                            $allpart->amount = $request->amount[$i];
                            $allpart->source_id = $request->partSource[$i];
                            $allpart->status_id = $request->partStatus[$i];
                            $allpart->quality_id = $request->partQualty[$i];
                            $allpart->buy_price = $request->price[$i];
                            $allpart->insertion_date = Carbon::now();
                            $allpart->remain_amount = $request->amount[$i];
                            $allpart->flag = 1;
                            $allpart->buy_costing = $itemCoast;
                            $allpart->save();

                            // if(count($request->Sections) > 0){
                            //     // ghoniemstoreLog
                            //     $storelog = new StoresLog();
                            //     $storelog->All_part_id = $allpart->id;
                            //     $storelog->store_id = $request->Stores[$i];
                            //     $storelog->store_action_id = 1;
                            //     $storelog->amount = $request->amount[$i];
                            //     $storelog->date = Carbon::now();
                            //     $storelog->status = 1;
                            //     $storelog->type_id = $request->types[$i];
                            //     $storelog->save();

                            //     $storelog = new StoresLog();
                            //     $storelog->All_part_id = $allpart->id;
                            //     $storelog->store_id = $request->Stores[$i];
                            //     $storelog->store_action_id = 3;
                            //     $storelog->amount = $request->amount[$i];
                            //     $storelog->date = Carbon::now();
                            //     $storelog->status = 3;
                            //     $storelog->type_id = $request->types[$i];
                            //     $storelog->save();

                            //     $store = Store::where('id',$request->Stores[$i])->get();
                            //     // return $store;
                            //     $storeClsName=ucfirst($store[0]->table_name);
                            //     $storeClsName ='App\Models\\'.$storeClsName;
                            //     $storeClsName = str_replace([' ','_','-'],"",$storeClsName);

                            //     // return $storeClsName;
                            //     if($store[0]->table_name == "damaged_parts"){
                            //         $storeClsName = "App\Models\\DamagedPart";
                            //     }
                            //     try {
                            //         //code...
                            //         $storeCls = new $storeClsName();

                            //         $storeCls->part_id = $request->partId[$i];
                            //         $storeCls->amount = $request->amount[$i];
                            //         $storeCls->supplier_order_id = $orderSupplier->id;
                            //         $storeCls->type_id =$request->types[$i];
                            //         $storeCls->store_log_id =$storelog->id ;
                            //         $storeCls->date = Carbon::now();
                            //         $storeCls->save();


                            //         $section = new StoreSection();
                            //         $section->store_id = $request->Stores[$i];
                            //         $section->section_id = $request->Sections[$i];
                            //         $section->order_supplier_id = $orderSupplier->id;
                            //         $section->type_id = $request->types[$i];
                            //         $section->part_id = $request->partId[$i];
                            //         $section->source_id = $request->partSource[$i];
                            //         $section->status_id = $request->partStatus[$i];
                            //         $section->quality_id = $request->partQualty[$i];
                            //         $section->amount = $request->amount[$i];
                            //         $section->date = Carbon::now();
                            //         $section->save();

                            //     } catch (\Throwable $th) {
                            //         //throw $th;
                            //         continue;
                            //     }
                            // }
                        }

                        /// wheel
                        if($request->types[$i] == 2){
                            $ratio=floatval($request->InvCoasting) / floatval($request->invTotLbl);
                            $itemCoast=($ratio *  (floatval($request->price[$i]) * intval($request->amount[$i])))/intval($request->amount[$i]);

                            $allpart = new AllWheel();
                            $allpart->part_id = $request->partId[$i];;
                            $allpart->order_supplier_id = $orderSupplier->id;
                            $allpart->amount = $request->amount[$i];
                            $allpart->source_id = $request->partSource[$i];
                            $allpart->status_id = $request->partStatus[$i];
                            $allpart->quality_id = $request->partQualty[$i];
                            $allpart->buy_price = $request->price[$i];
                            $allpart->insertion_date = Carbon::now();
                            $allpart->remain_amount = $request->amount[$i];
                            $allpart->flag = 1;
                            $allpart->buy_costing = $itemCoast;
                            $allpart->save();

                            // if(count($request->Sections) > 0){

                            //     $storelog = new StoresLog();
                            //     $storelog->All_part_id = $allpart->id;
                            //     $storelog->store_id = $request->Stores[$i];
                            //     $storelog->store_action_id = 1;
                            //     $storelog->amount = $request->amount[$i];
                            //     $storelog->date = Carbon::now();
                            //     $storelog->status = 1;
                            //     $storelog->type_id = $request->types[$i];
                            //     $storelog->save();

                            //     $storelog = new StoresLog();
                            //     $storelog->All_part_id = $allpart->id;
                            //     $storelog->store_id = $request->Stores[$i];
                            //     $storelog->store_action_id = 3;
                            //     $storelog->amount = $request->amount[$i];
                            //     $storelog->date = Carbon::now();
                            //     $storelog->status = 3;
                            //     $storelog->type_id = $request->types[$i];
                            //     $storelog->save();


                            //     $store = Store::where('id',$request->Stores[$i])->get();

                            //     $storeClsName=ucfirst($store[0]->table_name);
                            //     $storeClsName ='App\Models\\'.$storeClsName;
                            //     $storeClsName = str_replace([' ','_','-'],"",$storeClsName);


                            //     if($store[0]->table_name == "damaged_parts"){
                            //         $storeClsName = "App\Models\\DamagedPart";
                            //     }
                            //     try {

                            //         $storeCls = new $storeClsName();

                            //         $storeCls->part_id = $request->partId[$i];
                            //         $storeCls->amount = $request->amount[$i];
                            //         $storeCls->supplier_order_id = $orderSupplier->id;
                            //         $storeCls->type_id =$request->types[$i];
                            //         $storeCls->store_log_id =$storelog->id ;
                            //         $storeCls->date = Carbon::now();
                            //         $storeCls->save();


                            //         $section = new StoreSection();
                            //         $section->store_id = $request->Stores[$i];
                            //         $section->section_id = $request->Sections[$i];
                            //         $section->order_supplier_id = $orderSupplier->id;
                            //         $section->type_id = $request->types[$i];
                            //         $section->part_id = $request->partId[$i];
                            //         $section->source_id = $request->partSource[$i];
                            //         $section->status_id = $request->partStatus[$i];
                            //         $section->quality_id = $request->partQualty[$i];
                            //         $section->amount = $request->amount[$i];
                            //         $section->date = Carbon::now();
                            //         $section->save();

                            //     } catch (\Throwable $th) {

                            //         continue;
                            //     }
                            // }
                        }



                    }
                    $date =Carbon::now();
                    $type = null;
                    $notes='فاتورة شراء رقم'.$BuyTransaction->id.'العملة '. $Ac_all_currency_types[0]->name;
                    $automaicQayd->AutomaticQayd($quaditems,$date,$type,$notes);



                    return redirect()->to('printBuyInvoice/'.$BuyTransaction->id);

                }else{
                    session()->flash("success", 'الرصيد غير كافي');
                    return back();
                }

            }else{
                session()->flash("success", 'لا يوجد رصيد ');
             return back();
            }
        }
        else{
             return back()->with('success','please Select Supplier and Currency!.');
        }



    }

     public function storeManage2(Request $request)
    {
        // return 'tawze3';
        // return $request;


        // /************************************************************************************
        $quaditems = [];
        $automaicQayd = new QaydController();
        $invoiceac = 0;
        $taxac = 0;
        $binvoiceac =0;
        $buyCoastAc = 0 ;
        $taxAc2 = 0;
        $acSupNo = Supplier::where('id',$request->invSupplier)->first()->accountant_number;
        $Ac_currency_id = $request->currency_id;
        $Ac_all_currency_types=CurrencyType::with(['currencies'=>function($query) use($Ac_currency_id){
                return $query->where('to',null)->where('currency_id',$Ac_currency_id);
            }])->where('id',$Ac_currency_id)->get();


        if($request->transCoast > 0){
            array_push ( $quaditems , (object) [ 'acountant_id'=> 33101  , 'dayin'=> 0 , 'madin'=> $request->transCoast ] );
            $buyCoastAc += $request->transCoast;
        }
        if($request->insuranceCoast > 0){
            array_push ( $quaditems , (object) [ 'acountant_id'=> 33102  , 'dayin'=> 0 , 'madin'=> $request->insuranceCoast ] );
            $buyCoastAc += $request->insuranceCoast;
        }
        if($request->customs > 0){
            array_push ( $quaditems , (object) [ 'acountant_id'=> 33103  , 'dayin'=> 0 , 'madin'=> $request->customs ] );
            $buyCoastAc += $request->customs;
        }
        if($request->commition > 0){
            array_push ( $quaditems , (object) [ 'acountant_id'=> 33104  , 'dayin'=> 0 , 'madin'=> $request->commition ] );
            $buyCoastAc += $request->commition;
        }
        if($request->otherCoast > 0){
            array_push ( $quaditems , (object) [ 'acountant_id'=> 33105  , 'dayin'=> 0 , 'madin'=> $request->otherCoast ] );
            $buyCoastAc += $request->otherCoast;
        }

        if(floatval($request->taxInvolved) == 1 ){
            // غير شامل
            if($request->invTax){
                 $taxac = $request->invTotLbl * $request->invTax /100 ; // الضريبة
            }else{
                 $taxac = $request->invTotLbl * 14 /100 ; // الضريبة
            }

            $invoiceac = $request->invTotLbl + $taxac; //الاجمالي بعد الضريبة
            $binvoiceac =round($invoiceac - $taxac);

        }else{
            // شامل
            $binvoiceac = round($request->invAllTotal / 1.14); //  المشتريات غير شامل الضريبة
            $taxac = $request->invAllTotal -$binvoiceac ; // الضريبة
            $invoiceac =  round($binvoiceac + $taxac);
        }


        if(floatval($request->taxkasmInvolved) == 1 ){
            $taxAc2 = $request->invTotLbl * -1 / 100;
            $invoiceac = $invoiceac + $taxAc2;
        }else{
           $taxAc2 = 0;
        }

        if($request->payment == 0  && $invoiceac == $request->invPaied) // البيع كاش
        {

            // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac *  $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
            array_push ( $quaditems , (object) [ 'acountant_id'=> 2641  , 'madin'=> $binvoiceac *  $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المشتريات مدين
            array_push ( $quaditems , (object) [ 'acountant_id'=> intval($request->store_id) , 'madin'=> 0 , 'dayin'=> ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value)+$buyCoastAc ] ); // الخزنة دائن
            // array_push ( $quaditems , (object) [ 'acountant_id'=> $acSupNo , 'madin'=> 0 , 'dayin'=> ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value)+$buyCoastAc ] ); // العميل دائن

        }elseif($request->payment == 1 && $invoiceac == $request->invPaied ) // البيع شيك
        {
            // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
            array_push ( $quaditems , (object) [ 'acountant_id'=> 2641  , 'madin'=> $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المشتريات مدين

            // array_push ( $quaditems , (object) [ 'acountant_id'=> $acSupNo , 'madin'=> 0 , 'dayin'=> ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value)+$buyCoastAc ] ); // العميل دائن


            array_push ( $quaditems , (object) [ 'acountant_id'=> intval($request->store_id) , 'madin'=> 0 , 'dayin'=> ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value)+$buyCoastAc ] ); // بنك دائن


        }else // البيع أجل
        {
            // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
            array_push ( $quaditems , (object) [ 'acountant_id'=> 2641  , 'madin'=> $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المشتريات مدين


            array_push ( $quaditems , (object) [ 'acountant_id'=> $acSupNo , 'madin'=> 0 , 'dayin'=>($invoiceac- $request->invPaied) * $Ac_all_currency_types[0]->currencies[0]->value ] ); // العميل دائن
            if( intval($request->store_id) != 0  ) // خزنة
            {
                if($request->invPaied > 0){
                    array_push ( $quaditems , (object) [ 'acountant_id'=> intval($request->store_id) , 'madin'=> 0 , 'dayin'=> ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value)+$buyCoastAc ] ); // الخزنة دائن
                }

            }else // بنك
            {
                 if($request->invPaied > 0){
                     array_push ( $quaditems , (object) [ 'acountant_id'=> intval($request->store_id) , 'madin'=> 0 , 'dayin'=> ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value)+$buyCoastAc ] ); // بنك دائن
                 }

            }

        }

        if($taxac > 0){
            array_push ( $quaditems , (object) [ 'acountant_id'=> 175  , 'dayin'=> 0 , 'madin'=> $taxac * $Ac_all_currency_types[0]->currencies[0]->value ] ); // الضريبة مدين
        }
        if($taxAc2 != 0){
            array_push ( $quaditems , (object) [ 'acountant_id'=> 2637  , 'dayin'=> $taxAc2 * $Ac_all_currency_types[0]->currencies[0]->value*-1 , 'madin'=> 0 ] ); // الضريبة مدين
        }


        // *************************************************************************************



        if( isset($request->currency_id)  && isset($request->invSupplier)){
            $currency_id=$request->currency_id;
            ////////////////////////adel////////////////////
            // if ($request->store_id == 0) {
            //     $total = BankSafeMoney::all()->last();
            // } else {
            //     $total = MoneySafe::where('store_id', $request->store_id)->latest()->first();
            // }
            $Ac_allTotal = 0;
            $bank = BankType::where('accountant_number',intval($request->store_id))->first();
            if ($bank) {

                $raseed= $bank->bank_raseed;
                $total_qabd = BankSafeMoney::where('bank_type_id',$bank->id)->where('type_money',0)->sum('money');//قبض
                $total_sarf = BankSafeMoney::where('bank_type_id',$bank->id)->where('type_money',1)->sum('money');//صرف
                $total= floatval($raseed) + floatval($total_qabd) - floatval($total_sarf);
                $Ac_allTotal  = BankSafeMoney::all()->last();
            } else {

                $bank = Store::where('safe_accountant_number',intval($request->store_id))->first();
                $raseed= $bank->store_raseed;
                $total_qabd = MoneySafe::where('store_id',$bank->id)->where('type_money',0)->sum('money');//قبض
                $total_sarf = MoneySafe::where('store_id',$bank->id)->where('type_money',1)->sum('money');//صرف
                $total= floatval($raseed) + floatval($total_qabd) - floatval($total_sarf);
                $Ac_allTotal = MoneySafe::where('store_id', $bank->id)->latest()->first();
            }

            $all_currency_types=CurrencyType::with(['currencies'=>function($query) use($currency_id){
                return $query->where('to',null)->where('currency_id',$currency_id);
            }])->where('id',$currency_id)->get();
        if ($total >= 0){
            if ($total >= $request->invPaied * $all_currency_types[0]->currencies[0]->value) {

                $BuyTransaction = new BuyTransaction();
                $BuyTransaction->company_id = '10';
                $BuyTransaction->date = $request->invDate;
                $BuyTransaction->creation_date = Carbon::now();
                $BuyTransaction->name = Carbon::now();
                $BuyTransaction->final = '3';
                $BuyTransaction->save();
                $sup_id = 0;
                $supplier=Supplier::where('id',$request->invSupplier)->first();
                if ($bank) {
                    $Ac_allTotal_total = 0;
                        if($Ac_allTotal){
                            $Ac_allTotal_total = $Ac_allTotal->total;
                        }
                    BankSafeMoney::create([
                        'notes' => 'صرف مبلغ فاتورة شراء رقم '.' '.$BuyTransaction->id.' '.'من مورد'.' '.$supplier->name,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->invPaied * $all_currency_types[0]->currencies[0]->value,
                        'total' => $Ac_allTotal_total -($request->invPaied * $all_currency_types[0]->currencies[0]->value),
                        'type_money'=>'1',
                        'user_id'=>Auth::user()->id,
                        'store_id'=>null,
                        'bank_type_id' =>$bank->id,
                        'money_currency'=>$request->invPaied,
                        'currency_id'=>$currency_id,

                    ]);
                } else {
                    $Ac_allTotal_total = 0;
                        if($Ac_allTotal){
                            $Ac_allTotal_total = $Ac_allTotal->total;
                        }
                    $xx = Store::where('safe_accountant_number',intval($request->store_id))->first();
                    MoneySafe::create([
                        'notes' => 'صرف مبلغ فاتورة شراء رقم '.' '.$BuyTransaction->id.' '.'من مورد'.' '.$supplier->name,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->invPaied * $all_currency_types[0]->currencies[0]->value,
                        'total' => $Ac_allTotal_total-($request->invPaied * $all_currency_types[0]->currencies[0]->value),
                        'type_money'=>'1',
                        'user_id'=>Auth::user()->id,
                        'store_id'=>$xx->id,

                    ]);
                }


                if($request->NewinvSupplier <>  null){
                    // add new Supplier
                    $sup = new Supplier();
                    $sup->name = $request->NewinvSupplier;
                    $sup->save();
                    $sup_id = $sup->id;

                }else{

                    $sup_id = $request->invSupplier;
                }
                $orderSupplier = new OrderSupplier();
                $orderSupplier->transaction_id = $BuyTransaction->id;
                $orderSupplier->supplier_id = $sup_id;
                $orderSupplier->pricebeforeTax = $request->invTotLbl;
                // $orderSupplier->send_mail = '';
                // $orderSupplier->notes = '';
                $orderSupplier->status = '4';
                $orderSupplier->deliver_date = Carbon::now();
                // $orderSupplier->currency_id = 400;
                $orderSupplier->currency_id = $request->currency_id;
                $orderSupplier->total_price = $request->invAllTotal;
                $orderSupplier->paied = $request->invPaied;
                $orderSupplier->payment = $request->payment;

                $orderSupplier->transport_coast = $request->transCoast;
                $orderSupplier->insurant_coast = $request->insuranceCoast;
                $orderSupplier->customs_coast = $request->customs;
                $orderSupplier->commotion_coast = $request->commition;
                $orderSupplier->other_coast = $request->otherCoast;
                $orderSupplier->coast = $request->InvCoasting;


                if($request->invPaied < $request->invAllTotal ){
                    $orderSupplier->due_date = $request->dueDate;
                    Supplier::where('id',$sup_id)->increment('raseed',(floatval($request->invAllTotal) - floatval($request->invPaied))*$all_currency_types[0]->currencies[0]->value );
                }
                // $orderSupplier->bank_account = '';
                // $orderSupplier->container_size = '';
                $orderSupplier->confirmation_date = Carbon::now();
                // $orderSupplier->image_url = '';
                $orderSupplier->save();


                $date =Carbon::now();
                $type = null;
                $notes='فاتورة شراء وتوزيع بالأمر المباشر رقم'.$BuyTransaction->id.'العملة '. $Ac_all_currency_types[0]->name;
                $automaicQayd->AutomaticQayd($quaditems,$date,$type,$notes);
                $quaditems = [];
                 $Actotal=0;
                for ($i=0; $i < count($request->partId) ; $i++) {


                    # code...
                    $part = new Replyorder();
                    $part->order_supplier_id = $orderSupplier->id;
                    $part->part_id = $request->partId[$i];
                    $part->price = $request->price[$i];
                    $part->amount = $request->amount[$i];
                    $part->source_id = $request->partSource[$i];
                    $part->status_id = $request->partStatus[$i];
                    $part->quality_id = $request->partQualty[$i];
                    $part->creation_date = Carbon::now();
                    $part->part_type_id = $request->types[$i];
                    $part->save();


                    // /*******************************************************************

                    $store = Store::where('id',$request->Stores[$i])->first();
                    $Ac_inv = Replyorder::where('order_supplier_id',$orderSupplier->id)
                    ->where('part_id',$request->partId[$i])
                    ->where('source_id',$request->partSource[$i])
                    ->where('status_id',$request->partStatus[$i])
                    ->where('quality_id',$request->partQualty[$i])
                    ->where('part_type_id',$request->types[$i])
                    ->with('order_supplier')->first();

                    $Ac_currency_id = $Ac_inv->order_supplier->currency_id;
                    $Ac_currency_date = $Ac_inv->order_supplier->confirmation_date;
                    $Ac_all_currency_types=CurrencyType::with(['currencies'=>function($query) use($Ac_currency_id , $Ac_currency_date){
                            return $query->where('from','>=',$Ac_currency_date)->where('to','<=',$Ac_currency_date)->where('currency_id',$Ac_currency_id)->orWhere('to','=',null);
                        }])->where('id',$Ac_currency_id)->get();




                    $Ac_price = $request->amount[$i] * $Ac_all_currency_types[0]->currencies[0]->value * $Ac_inv->price ;

                    array_push ( $quaditems , (object) [ 'acountant_id'=> $store->accountant_number , 'madin'=> $Ac_price , 'dayin'=> 0 ] ); // المخزن مدين

                   $Actotal += $Ac_price;



                    ///////////////////////////////////////////////////////////////////////
                    if($request->types[$i] == 1){
                           $ratio=floatval($request->InvCoasting) / floatval($request->invTotLbl);
                            $itemCoast=($ratio *  (floatval($request->price[$i]) * intval($request->amount[$i])))/intval($request->amount[$i]);

                        $allpart = new AllPart();
                        $allpart->part_id = $request->partId[$i];
                        $allpart->order_supplier_id = $orderSupplier->id;
                        $allpart->amount = $request->amount[$i];
                        $allpart->source_id = $request->partSource[$i];
                        $allpart->status_id = $request->partStatus[$i];
                        $allpart->quality_id = $request->partQualty[$i];
                        $allpart->buy_price = $request->price[$i];
                        $allpart->insertion_date = Carbon::now();
                        $allpart->remain_amount = $request->amount[$i];
                        $allpart->flag = 3;
                        $allpart->buy_costing = $itemCoast;
                        $allpart->save();

                        if(isset($request->Sections) && count($request->Sections) > 0){
                            // ghoniemstoreLog
                            $storelog = new StoresLog();
                            $storelog->All_part_id = $allpart->id;
                            $storelog->store_id = $request->Stores[$i];
                            $storelog->store_action_id = 1;
                            $storelog->amount = $request->amount[$i];
                            $storelog->date = Carbon::now();
                            $storelog->status = 1;
                            $storelog->type_id = $request->types[$i];
                            $storelog->save();

                            $storelog = new StoresLog();
                            $storelog->All_part_id = $allpart->id;
                            $storelog->store_id = $request->Stores[$i];
                            $storelog->store_action_id = 3;
                            $storelog->amount = $request->amount[$i];
                            $storelog->date = Carbon::now();
                            $storelog->status = 3;
                            $storelog->type_id = $request->types[$i];
                            $storelog->save();


                            $store = Store::where('id',$request->Stores[$i])->get();
                            // return $store;

                            $storeClsName=ucfirst($store[0]->table_name);
                            $storeClsName ='App\Models\\'.$storeClsName;
                            $storeClsName = str_replace([' ','_','-'],"",$storeClsName);

                            // return $storeClsName;
                            if($store[0]->table_name == "damaged_parts"){
                                $storeClsName = "App\Models\\DamagedPart";
                            }
                            try {
                                //code...
                                $storeCls = new $storeClsName();

                                $storeCls->part_id = $request->partId[$i];
                                $storeCls->amount = $request->amount[$i];
                                $storeCls->supplier_order_id = $orderSupplier->id;
                                $storeCls->type_id =$request->types[$i];
                                $storeCls->store_log_id =$storelog->id ;
                                $storeCls->date = Carbon::now();
                                $storeCls->save();


                                $section = new StoreSection();
                                $section->store_id = $request->Stores[$i];
                                $section->section_id = $request->Sections[$i];
                                $section->order_supplier_id = $orderSupplier->id;
                                $section->type_id = $request->types[$i];
                                $section->part_id = $request->partId[$i];
                                $section->source_id = $request->partSource[$i];
                                $section->status_id = $request->partStatus[$i];
                                $section->quality_id = $request->partQualty[$i];
                                $section->amount = $request->amount[$i];
                                $section->date = Carbon::now();
                                $section->save();

                            } catch (\Throwable $th) {
                                //throw $th;
                                continue;
                            }

                        }

                    }

                    ///// kit
                    if($request->types[$i] == 6){
                         $ratio=floatval($request->InvCoasting) / floatval($request->invTotLbl);
                            $itemCoast=($ratio *  (floatval($request->price[$i]) * intval($request->amount[$i])))/intval($request->amount[$i]);

                        $allpart = new AllKit();
                        $allpart->part_id = $request->partId[$i];;
                        $allpart->order_supplier_id = $orderSupplier->id;
                        $allpart->amount = $request->amount[$i];
                        $allpart->source_id = $request->partSource[$i];
                        $allpart->status_id = $request->partStatus[$i];
                        $allpart->quality_id = $request->partQualty[$i];
                        $allpart->buy_price = $request->price[$i];
                        $allpart->insertion_date = Carbon::now();
                        $allpart->remain_amount = $request->amount[$i];
                        $allpart->flag = 3;
                        $allpart->buy_costing = $itemCoast;
                        $allpart->save();

                        if(count($request->Sections) > 0){
                            // ghoniemstoreLog
                            $storelog = new StoresLog();
                            $storelog->All_part_id = $allpart->id;
                            $storelog->store_id = $request->Stores[$i];
                            $storelog->store_action_id = 1;
                            $storelog->amount = $request->amount[$i];
                            $storelog->date = Carbon::now();
                            $storelog->status = 1;
                            $storelog->type_id = $request->types[$i];
                            $storelog->save();

                            $storelog = new StoresLog();
                            $storelog->All_part_id = $allpart->id;
                            $storelog->store_id = $request->Stores[$i];
                            $storelog->store_action_id = 3;
                            $storelog->amount = $request->amount[$i];
                            $storelog->date = Carbon::now();
                            $storelog->status = 3;
                            $storelog->type_id = $request->types[$i];
                            $storelog->save();

                            $store = Store::where('id',$request->Stores[$i])->get();
                            // return $store;
                            $storeClsName=ucfirst($store[0]->table_name);
                            $storeClsName ='App\Models\\'.$storeClsName;
                            $storeClsName = str_replace([' ','_','-'],"",$storeClsName);

                            // return $storeClsName;
                            if($store[0]->table_name == "damaged_parts"){
                                $storeClsName = "App\Models\\DamagedPart";
                            }
                            try {
                                //code...
                                $storeCls = new $storeClsName();

                                $storeCls->part_id = $request->partId[$i];
                                $storeCls->amount = $request->amount[$i];
                                $storeCls->supplier_order_id = $orderSupplier->id;
                                $storeCls->type_id =$request->types[$i];
                                $storeCls->store_log_id =$storelog->id ;
                                $storeCls->date = Carbon::now();
                                $storeCls->save();


                                $section = new StoreSection();
                                $section->store_id = $request->Stores[$i];
                                $section->section_id = $request->Sections[$i];
                                $section->order_supplier_id = $orderSupplier->id;
                                $section->type_id = $request->types[$i];
                                $section->part_id = $request->partId[$i];
                                $section->source_id = $request->partSource[$i];
                                $section->status_id = $request->partStatus[$i];
                                $section->quality_id = $request->partQualty[$i];
                                $section->amount = $request->amount[$i];
                                $section->date = Carbon::now();
                                $section->save();

                            } catch (\Throwable $th) {
                                //throw $th;
                                continue;
                            }
                        }
                    }

                    /// wheel
                    if($request->types[$i] == 2){
                         $ratio=floatval($request->InvCoasting) / floatval($request->invTotLbl);
                        $itemCoast=($ratio *  (floatval($request->price[$i]) * intval($request->amount[$i])))/intval($request->amount[$i]);

                        $allpart = new AllWheel();
                        $allpart->part_id = $request->partId[$i];;
                        $allpart->order_supplier_id = $orderSupplier->id;
                        $allpart->amount = $request->amount[$i];
                        $allpart->source_id = $request->partSource[$i];
                        $allpart->status_id = $request->partStatus[$i];
                        $allpart->quality_id = $request->partQualty[$i];
                        $allpart->buy_price = $request->price[$i];
                        $allpart->insertion_date = Carbon::now();
                        $allpart->remain_amount = $request->amount[$i];
                        $allpart->flag = 3;
                        $allpart->buy_costing = $itemCoast;
                        $allpart->save();

                        if(count($request->Sections) > 0){
                            // ghoniemstoreLog
                            $storelog = new StoresLog();
                            $storelog->All_part_id = $allpart->id;
                            $storelog->store_id = $request->Stores[$i];
                            $storelog->store_action_id = 1;
                            $storelog->amount = $request->amount[$i];
                            $storelog->date = Carbon::now();
                            $storelog->status = 1;
                            $storelog->type_id = $request->types[$i];
                            $storelog->save();

                            $storelog = new StoresLog();
                            $storelog->All_part_id = $allpart->id;
                            $storelog->store_id = $request->Stores[$i];
                            $storelog->store_action_id = 3;
                            $storelog->amount = $request->amount[$i];
                            $storelog->date = Carbon::now();
                            $storelog->status = 3;
                            $storelog->type_id = $request->types[$i];
                            $storelog->save();


                            $store = Store::where('id',$request->Stores[$i])->get();
                            // return $store;
                            $storeClsName=ucfirst($store[0]->table_name);
                            $storeClsName ='App\Models\\'.$storeClsName;
                            $storeClsName = str_replace([' ','_','-'],"",$storeClsName);

                            // return $storeClsName;
                            if($store[0]->table_name == "damaged_parts"){
                                $storeClsName = "App\Models\\DamagedPart";
                            }
                            try {
                                //code...
                                $storeCls = new $storeClsName();

                                $storeCls->part_id = $request->partId[$i];
                                $storeCls->amount = $request->amount[$i];
                                $storeCls->supplier_order_id = $orderSupplier->id;
                                $storeCls->type_id =$request->types[$i];
                                $storeCls->store_log_id =$storelog->id ;
                                $storeCls->date = Carbon::now();
                                $storeCls->save();


                                $section = new StoreSection();
                                $section->store_id = $request->Stores[$i];
                                $section->section_id = $request->Sections[$i];
                                $section->order_supplier_id = $orderSupplier->id;
                                $section->type_id = $request->types[$i];
                                $section->part_id = $request->partId[$i];
                                $section->source_id = $request->partSource[$i];
                                $section->status_id = $request->partStatus[$i];
                                $section->quality_id = $request->partQualty[$i];
                                $section->amount = $request->amount[$i];
                                $section->date = Carbon::now();
                                $section->save();

                            } catch (\Throwable $th) {
                                //throw $th;
                                continue;
                            }
                        }
                    }



                }


                // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> 0 , 'dayin'=>$Actotal ] ); // المخزون دائن
                array_push ( $quaditems , (object) [ 'acountant_id'=> 2641  , 'madin'=> 0 , 'dayin'=>$Actotal ] ); // المشتريات دائن
                $date =Carbon::now();
                $type = null;
                $notes='حركة مخزون';
                $automaicQayd->AutomaticQayd($quaditems,$date,$type,$notes);




                session()->flash("success", "تم صرف المبلغ  بنجاح");
                return redirect()->to('printBuyInvoice/'.$BuyTransaction->id);
            } else {
                session()->flash("success", "  المبلغ غير كافي في الخزنة");
                return redirect()->back();
            }


        } else {

            session()->flash("success", "   لا يوجد رصيد في الخزنة   ");
            return redirect()->back();
        }


        }
        else{
             return back()->with('success','please Select Supplier and Currency!.');
        }



    }


    public function storeManage2222(Request $request)
    {
        //
        // return $request;
        if( isset($request->currency_id)  && isset($request->invSupplier)){
            $currency_id=$request->currency_id;
            ////////////////////////adel////////////////////
            // if ($request->store_id == 0) {
            //     $total = BankSafeMoney::all()->last();
            // } else {
            //     $total = MoneySafe::where('store_id', $request->store_id)->latest()->first();
            // }
            $Ac_allTotal = 0;
            $bank = BankType::where('accountant_number',$request->store_id)->first();
            if ($bank) {

                $raseed= $bank->bank_raseed;
                $total_qabd = BankSafeMoney::where('bank_type_id',$bank->id)->where('type_money',0)->sum('money');//قبض
                $total_sarf = BankSafeMoney::where('bank_type_id',$bank->id)->where('type_money',1)->sum('money');//صرف
                $total= floatval($raseed) + floatval($total_qabd) - floatval($total_sarf);
                $Ac_allTotal  = BankSafeMoney::all()->last();
            } else {

                $bank = Store::where('safe_accountant_number',$request->store_id)->first();
                $raseed= $bank->store_raseed;
                $total_qabd = MoneySafe::where('store_id',$bank->id)->where('type_money',0)->sum('money');//قبض
                $total_sarf = MoneySafe::where('store_id',$bank->id)->where('type_money',1)->sum('money');//صرف
                $total= floatval($raseed) + floatval($total_qabd) - floatval($total_sarf);
                $Ac_allTotal = MoneySafe::where('store_id', $bank->id)->latest()->first();
            }

            $all_currency_types=CurrencyType::with(['currencies'=>function($query) use($currency_id){
                return $query->where('to',null)->where('currency_id',$currency_id);
            }])->where('id',$currency_id)->get();
        if ($total >= 0) {
            if ($total >= $request->invPaied * $all_currency_types[0]->currencies[0]->value) {

                $BuyTransaction = new BuyTransaction();
                $BuyTransaction->company_id = '10';
                $BuyTransaction->date = $request->invDate;
                $BuyTransaction->creation_date = Carbon::now();
                $BuyTransaction->name = Carbon::now();
                $BuyTransaction->final = '3';
                $BuyTransaction->save();
                $sup_id = 0;
                $supplier=Supplier::where('id',$request->invSupplier)->first();
                if ($bank) {
                    BankSafeMoney::create([
                        'notes' => 'صرف مبلغ فاتورة شراء رقم '.' '.$BuyTransaction->id.' '.'من مورد'.' '.$supplier->name,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->invPaied * $all_currency_types[0]->currencies[0]->value,
                        'total' => $Ac_allTotal->total-($request->invPaied * $all_currency_types[0]->currencies[0]->value),
                        'type_money'=>'1',
                        'user_id'=>Auth::user()->id,
                        'store_id'=>null,
                        'bank_type_id'=> $bank->id,
                        'money_currency'=>$request->invPaied,
                        'currency_id'=>$currency_id,

                    ]);
                } else {
                    $xx = Store::where('safe_accountant_number',$request->store_id)->first();
                    MoneySafe::create([
                        'notes' => 'صرف مبلغ فاتورة شراء رقم '.' '.$BuyTransaction->id.' '.'من مورد'.' '.$supplier->name,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->invPaied * $all_currency_types[0]->currencies[0]->value,
                        'total' => $Ac_allTotal->total-($request->invPaied * $all_currency_types[0]->currencies[0]->value),
                        'type_money'=>'1',
                        'user_id'=>Auth::user()->id,
                        'store_id'=>$xx->id,

                    ]);
                }


                if($request->NewinvSupplier <>  null){
                    // add new Supplier
                    $sup = new Supplier();
                    $sup->name = $request->NewinvSupplier;
                    $sup->save();
                    $sup_id = $sup->id;

                }else{

                    $sup_id = $request->invSupplier;
                }
                $orderSupplier = new OrderSupplier();
                $orderSupplier->transaction_id = $BuyTransaction->id;
                $orderSupplier->supplier_id = $sup_id;
                $orderSupplier->pricebeforeTax = $request->invTotLbl;
                // $orderSupplier->send_mail = '';
                // $orderSupplier->notes = '';
                $orderSupplier->status = '4';
                $orderSupplier->deliver_date = Carbon::now();
                // $orderSupplier->currency_id = 400;
                $orderSupplier->currency_id = $request->currency_id;
                $orderSupplier->total_price = $request->invAllTotal;
                $orderSupplier->paied = $request->invPaied;
                $orderSupplier->payment = $request->payment;

                $orderSupplier->transport_coast = $request->transCoast;
                $orderSupplier->insurant_coast = $request->insuranceCoast;
                $orderSupplier->customs_coast = $request->customs;
                $orderSupplier->commotion_coast = $request->commition;
                $orderSupplier->other_coast = $request->otherCoast;
                $orderSupplier->coast = $request->InvCoasting;

                if($request->invPaied < $request->invAllTotal ){
                    $orderSupplier->due_date = $request->dueDate;
                    Supplier::where('id',$sup_id)->increment('raseed',(floatval($request->invAllTotal) - floatval($request->invPaied))*$all_currency_types[0]->currencies[0]->value );

                }
                // $orderSupplier->bank_account = '';
                // $orderSupplier->container_size = '';
                $orderSupplier->confirmation_date = Carbon::now();
                // $orderSupplier->image_url = '';
                $orderSupplier->save();

                for ($i=0; $i < count($request->partId) ; $i++) {
                    # code...
                    $part = new Replyorder();
                    $part->order_supplier_id = $orderSupplier->id;
                    $part->part_id = $request->partId[$i];
                    $part->price = $request->price[$i];
                    $part->amount = $request->amount[$i];
                    $part->source_id = $request->partSource[$i];
                    $part->status_id = $request->partStatus[$i];
                    $part->quality_id = $request->partQualty[$i];
                    $part->creation_date = Carbon::now();
                    $part->part_type_id = $request->types[$i];
                    $part->save();

                    if($request->types[$i] == 1){
                        $allpart = new AllPart();
                        $allpart->part_id = $request->partId[$i];;
                        $allpart->order_supplier_id = $orderSupplier->id;
                        $allpart->amount = $request->amount[$i];
                        $allpart->source_id = $request->partSource[$i];
                        $allpart->status_id = $request->partStatus[$i];
                        $allpart->quality_id = $request->partQualty[$i];
                        $allpart->buy_price = $request->price[$i];
                        $allpart->insertion_date = Carbon::now();
                        $allpart->remain_amount = $request->amount[$i];
                        $allpart->flag = 3;
                        $allpart->save();

                        if(isset($request->Sections) && count($request->Sections) > 0){
                            // ghoniemstoreLog
                            $storelog = new StoresLog();
                            $storelog->All_part_id = $allpart->id;
                            $storelog->store_id = $request->Stores[$i];
                            $storelog->store_action_id = 1;
                            $storelog->amount = $request->amount[$i];
                            $storelog->date = Carbon::now();
                            $storelog->status = 1;
                            $storelog->type_id = $request->types[$i];
                            $storelog->save();

                            $storelog = new StoresLog();
                            $storelog->All_part_id = $allpart->id;
                            $storelog->store_id = $request->Stores[$i];
                            $storelog->store_action_id = 3;
                            $storelog->amount = $request->amount[$i];
                            $storelog->date = Carbon::now();
                            $storelog->status = 3;
                            $storelog->type_id = $request->types[$i];
                            $storelog->save();


                            $store = Store::where('id',$request->Stores[$i])->get();
                            // return $store;

                            $storeClsName=ucfirst($store[0]->table_name);
                            $storeClsName ='App\Models\\'.$storeClsName;
                            $storeClsName = str_replace([' ','_','-'],"",$storeClsName);

                            // return $storeClsName;
                            if($store[0]->table_name == "damaged_parts"){
                                $storeClsName = "App\Models\\DamagedPart";
                            }
                            try {
                                //code...
                                $storeCls = new $storeClsName();

                                $storeCls->part_id = $request->partId[$i];
                                $storeCls->amount = $request->amount[$i];
                                $storeCls->supplier_order_id = $orderSupplier->id;
                                $storeCls->type_id =$request->types[$i];
                                $storeCls->store_log_id =$storelog->id ;
                                $storeCls->date = Carbon::now();
                                $storeCls->save();


                                $section = new StoreSection();
                                $section->store_id = $request->Stores[$i];
                                $section->section_id = $request->Sections[$i];
                                $section->order_supplier_id = $orderSupplier->id;
                                $section->type_id = $request->types[$i];
                                $section->part_id = $request->partId[$i];
                                $section->source_id = $request->partSource[$i];
                                $section->status_id = $request->partStatus[$i];
                                $section->quality_id = $request->partQualty[$i];
                                $section->amount = $request->amount[$i];
                                $section->date = Carbon::now();
                                $section->save();

                            } catch (\Throwable $th) {
                                //throw $th;
                                continue;
                            }

                        }

                    }

                    ///// kit
                    if($request->types[$i] == 6){
                        $allpart = new AllKit();
                        $allpart->part_id = $request->partId[$i];;
                        $allpart->order_supplier_id = $orderSupplier->id;
                        $allpart->amount = $request->amount[$i];
                        $allpart->source_id = $request->partSource[$i];
                        $allpart->status_id = $request->partStatus[$i];
                        $allpart->quality_id = $request->partQualty[$i];
                        $allpart->buy_price = $request->price[$i];
                        $allpart->insertion_date = Carbon::now();
                        $allpart->remain_amount = $request->amount[$i];
                        $allpart->flag = 3;
                        $allpart->save();

                        if(count($request->Sections) > 0){
                            // ghoniemstoreLog
                            $storelog = new StoresLog();
                            $storelog->All_part_id = $allpart->id;
                            $storelog->store_id = $request->Stores[$i];
                            $storelog->store_action_id = 1;
                            $storelog->amount = $request->amount[$i];
                            $storelog->date = Carbon::now();
                            $storelog->status = 1;
                            $storelog->type_id = $request->types[$i];
                            $storelog->save();

                            $storelog = new StoresLog();
                            $storelog->All_part_id = $allpart->id;
                            $storelog->store_id = $request->Stores[$i];
                            $storelog->store_action_id = 3;
                            $storelog->amount = $request->amount[$i];
                            $storelog->date = Carbon::now();
                            $storelog->status = 3;
                            $storelog->type_id = $request->types[$i];
                            $storelog->save();

                            $store = Store::where('id',$request->Stores[$i])->get();
                            // return $store;
                            $storeClsName=ucfirst($store[0]->table_name);
                            $storeClsName ='App\Models\\'.$storeClsName;
                            $storeClsName = str_replace([' ','_','-'],"",$storeClsName);

                            // return $storeClsName;
                            if($store[0]->table_name == "damaged_parts"){
                                $storeClsName = "App\Models\\DamagedPart";
                            }
                            try {
                                //code...
                                $storeCls = new $storeClsName();

                                $storeCls->part_id = $request->partId[$i];
                                $storeCls->amount = $request->amount[$i];
                                $storeCls->supplier_order_id = $orderSupplier->id;
                                $storeCls->type_id =$request->types[$i];
                                $storeCls->store_log_id =$storelog->id ;
                                $storeCls->date = Carbon::now();
                                $storeCls->save();


                                $section = new StoreSection();
                                $section->store_id = $request->Stores[$i];
                                $section->section_id = $request->Sections[$i];
                                $section->order_supplier_id = $orderSupplier->id;
                                $section->type_id = $request->types[$i];
                                $section->part_id = $request->partId[$i];
                                $section->source_id = $request->partSource[$i];
                                $section->status_id = $request->partStatus[$i];
                                $section->quality_id = $request->partQualty[$i];
                                $section->amount = $request->amount[$i];
                                $section->date = Carbon::now();
                                $section->save();

                            } catch (\Throwable $th) {
                                //throw $th;
                                continue;
                            }
                        }
                    }

                    /// wheel
                    if($request->types[$i] == 2){
                        $allpart = new AllWheel();
                        $allpart->part_id = $request->partId[$i];;
                        $allpart->order_supplier_id = $orderSupplier->id;
                        $allpart->amount = $request->amount[$i];
                        $allpart->source_id = $request->partSource[$i];
                        $allpart->status_id = $request->partStatus[$i];
                        $allpart->quality_id = $request->partQualty[$i];
                        $allpart->buy_price = $request->price[$i];
                        $allpart->insertion_date = Carbon::now();
                        $allpart->remain_amount = $request->amount[$i];
                        $allpart->flag = 3;
                        $allpart->save();

                        if(count($request->Sections) > 0){
                            // ghoniemstoreLog
                            $storelog = new StoresLog();
                            $storelog->All_part_id = $allpart->id;
                            $storelog->store_id = $request->Stores[$i];
                            $storelog->store_action_id = 1;
                            $storelog->amount = $request->amount[$i];
                            $storelog->date = Carbon::now();
                            $storelog->status = 1;
                            $storelog->type_id = $request->types[$i];
                            $storelog->save();

                            $storelog = new StoresLog();
                            $storelog->All_part_id = $allpart->id;
                            $storelog->store_id = $request->Stores[$i];
                            $storelog->store_action_id = 3;
                            $storelog->amount = $request->amount[$i];
                            $storelog->date = Carbon::now();
                            $storelog->status = 3;
                            $storelog->type_id = $request->types[$i];
                            $storelog->save();


                            $store = Store::where('id',$request->Stores[$i])->get();
                            // return $store;
                            $storeClsName=ucfirst($store[0]->table_name);
                            $storeClsName ='App\Models\\'.$storeClsName;
                            $storeClsName = str_replace([' ','_','-'],"",$storeClsName);

                            // return $storeClsName;
                            if($store[0]->table_name == "damaged_parts"){
                                $storeClsName = "App\Models\\DamagedPart";
                            }
                            try {
                                //code...
                                $storeCls = new $storeClsName();

                                $storeCls->part_id = $request->partId[$i];
                                $storeCls->amount = $request->amount[$i];
                                $storeCls->supplier_order_id = $orderSupplier->id;
                                $storeCls->type_id =$request->types[$i];
                                $storeCls->store_log_id =$storelog->id ;
                                $storeCls->date = Carbon::now();
                                $storeCls->save();


                                $section = new StoreSection();
                                $section->store_id = $request->Stores[$i];
                                $section->section_id = $request->Sections[$i];
                                $section->order_supplier_id = $orderSupplier->id;
                                $section->type_id = $request->types[$i];
                                $section->part_id = $request->partId[$i];
                                $section->source_id = $request->partSource[$i];
                                $section->status_id = $request->partStatus[$i];
                                $section->quality_id = $request->partQualty[$i];
                                $section->amount = $request->amount[$i];
                                $section->date = Carbon::now();
                                $section->save();

                            } catch (\Throwable $th) {
                                //throw $th;
                                continue;
                            }
                        }
                    }



                }

                session()->flash("success", "تم صرف المبلغ  بنجاح");
                return redirect()->to('printBuyInvoice/'.$BuyTransaction->id);
            } else {
                session()->flash("success", "  المبلغ غير كافي في الخزنة");
                return redirect()->back();
            }


        } else {

            session()->flash("success", "   لا يوجد رصيد في الخزنة   ");
            return redirect()->back();
        }


        }
        else{
             return back()->with('success','please Select Supplier and Currency!.');
        }



    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function deleteInv(BuyTransaction $id){

        $orderSup=OrderSupplier::where('transaction_id',$id->id)->get();
        foreach ($orderSup as $order) {
            $repOrders = Replyorder::where('order_supplier_id',$order->id)->get();

            foreach($repOrders as $repOrder){
                if($repOrder->part_type_id == 1){
                    $allparts = AllPart::where('order_supplier_id',$order->id)->get();
                    foreach($allparts as $allpart){
                        $storelog= StoresLog::where('All_part_id',$allpart->id)->delete();
                    }
                    AllPart::where('order_supplier_id',$order->id)->delete();
                }elseif($repOrder->part_type_id == 2){
                    $allwheels = AllWheel::where('order_supplier_id',$order->id)->get();
                    foreach($allwheels as $allwheel){
                        $storelog= StoresLog::where('All_part_id',$allwheel->id)->delete();
                    }
                    AllWheel::where('order_supplier_id',$order->id)->delete();
                }elseif($repOrder->part_type_id == 6){
                    $allkits = AllKit::where('order_supplier_id',$order->id)->get();
                    foreach($allkits as $allkit){
                        $storelog= StoresLog::where('All_part_id',$allkit->id)->delete();
                    }
                    AllKit::where('order_supplier_id',$order->id)->delete();
                }else{

                }
            }
            Replyorder::where('order_supplier_id',$order->id)->delete();
        }
        OrderSupplier::where('transaction_id',$id->id)->delete();
        $id->delete();

        return Redirect::back()->with('message','Successfully Deleted !');
    }
    public function source()
    {
        return Source::all();
    }
    public function status()
    {
        return Status::all();
    }
    public function quality()
    {
        return PartQuality::all();
    }

    public function lastInvId(){
        $lastid = BuyTransaction::orderBy('id','desc')->first();
        if($lastid){
            return $lastid->id
            ;
        }else{
            return 0;
        }
    }

    // public function import(Request $request){
    //     // return $request->file('file');
    //     Excel::import(new ImportParts, $request->file('file')->store('files'));
    //     return redirect()->back();
    // }

    public function printBuyInvoice(BuyTransaction $id){
        // return $id;
        $invoice_id = $id->id;
        $invoice = $id;
        $company = $id->with('company')->get();
        // return $company;
        $orderSup = OrderSupplier::where('transaction_id',$invoice_id)->with('supplier')->with('currency_type')->get();
        // $items = Replyorder::where('order_supplier_id',$orderSup[0]->id)->where('part_type_id','1')->with('source')->with('status')->with('part_quality')->get();
        $items = Replyorder::where('order_supplier_id',$orderSup[0]->id)->with('source')->with('status')->with('part_quality')->get();
        foreach ($items as $key => $item) {
            if(($item->part_type_id)==1){
                $item['part'] = Part::where('id',$item->part_id)->with('part_numbers')->get();
            }elseif(($item->part_type_id)==2){  // wheel
                $item['part'] = Wheel::where('id',$item->part_id)->get();

            }elseif(($item->part_type_id)==6){  // kit
                $item['part'] = Kit::where('id',$item->part_id)->with('kit_numbers')->get();

            }


        }

        return View('printBuyInvoice',compact(['invoice','orderSup','items','company']));
    }

    public function storeManageItems(BuyTransaction $id){
        // return $id;
        $buyTrans = $id;
        $orderSup = OrderSupplier::where('transaction_id',$buyTrans->id)->with('supplier')->with('currency_type')->get();
        if(count($orderSup) <= 0){
            return redirect()->to('storeManage/')->with('success','No Supplier Support');;
        }
        $store = Store::where('table_name','<>','damaged_parts')->where('id','<>',8)->get();
        // $items = Replyorder::where('order_supplier_id',$orderSup[0]->id)->with('part')->with('source')->with('status')->with('part_quality')->get();


        $items1 = AllPart::where('order_supplier_id',$orderSup[0]->id)->with('part')->with('source')->with('status')->with('part_quality')->get();
        $items2 = AllKit::where('order_supplier_id',$orderSup[0]->id)->with('kit')->with('source')->with('status')->with('part_quality')->get();
        $items3 = AllWheel::where('order_supplier_id',$orderSup[0]->id)->with('wheel')->with('source')->with('status')->with('part_quality')->get();

        $items = $items1->merge($items2)->merge($items3);
        // return $items;

        foreach ($items as $key => $item) {
            // $item['remainAmountInInvoice'] = StoresLog::where('All_part_id',$item->id)->where('store_action_id',3)->where('type_id',1)->sum('amount');
            $item['remainAmountInInvoice'] = StoresLog::where('All_part_id',$item->id)->where('store_action_id',3)->where('status',3)->sum('amount');

            $item['needConfirmAmountInInvoice'] = StoresLog::where('All_part_id',$item->id)->where('store_action_id',1)->where('status',0)->sum('amount');
            if(isset($item->part)){
                $item['type'] =1;
            }elseif(isset($item->wheel)){
                $item['type'] =2;
            }elseif(isset($item->kit)){
                $item['type'] =6;
            }



        }
        // return $items;
        return View('storeManageItems',compact('buyTrans','orderSup','items','store'));

    }
    public function storeSend1(Request $request){
        // return $request;


       // /*******************************************************************************************************
        $quaditems = [];
        $automaicQayd = new QaydController();
        $Actotal=0;



       // /*******************************************************************************************************
         $stores = store::where('table_name','<>','damaged_parts')->where('id','<>','8')->get();
        foreach ($stores as $key => $store) {
            $storeName = 'store'.$store->id;
            $storeId = $store->id;
            for ($i=0; $i < count($request->$storeName) ; $i++) {
                $amount = $request->$storeName[$i];

                if($amount <> null && intval($amount) > 0){

                    // /*******************************************************************************************************
                    $storex = Store::where('id',$storeId)->first();
                    $Ac_inv = Replyorder::where('order_supplier_id',$request['orderSupplierId'])
                    ->where('part_id',$request->partIds[$i])
                    ->where('source_id',$request->source[$i])
                    ->where('status_id',$request->status[$i])
                    ->where('quality_id',$request->quality[$i])
                    ->where('part_type_id',1)
                    ->with('order_supplier')->first();
                    $Ac_currency_id = $Ac_inv->order_supplier->currency_id;
                    $Ac_currency_date = $Ac_inv->order_supplier->confirmation_date;
                    $Ac_all_currency_types=CurrencyType::with(['currencies'=>function($query) use($Ac_currency_id , $Ac_currency_date){
                            return $query->where('from','>=',$Ac_currency_date)->where('to','<=',$Ac_currency_date)->where('currency_id',$Ac_currency_id)->orWhere('to','=',null);
                        }])->where('id',$Ac_currency_id)->get();


                    // $Ac_inv->price // السعر
                    // $Ac_inv->order_supplier->currency_id // العملة
                    // $request['data'][1]['store_data'][0]['ins_amount']; // الكمية
                    // $Ac_inv->order_supplier->confirmation_date // تاريخ الشراء
                    // $Ac_all_currency_types[0]->currencies[0]->value  // سعر العملة في وقت الشراء

                    $Ac_price = $amount * $Ac_all_currency_types[0]->currencies[0]->value * $Ac_inv->price ;

                    array_push ( $quaditems , (object) [ 'acountant_id'=> $storex->accountant_number , 'madin'=> $Ac_price , 'dayin'=> 0 ] ); // المخزن مدين


                   $Actotal += $Ac_price;


                    // /*******************************************************************************************************
                    $storelog = new StoresLog();
                    $storelog->All_part_id = $request->allpart[$i];

                    $storelog->store_action_id = 3;
                    $storelog->store_id = $storeId;
                    $storelog->amount = $amount;
                    $storelog->date = Carbon::now();
                    $storelog->status = 3;
                    $storelog->type_id = $request->types[$i];
                    $storelog->save();


                    $storeClsName=ucfirst($store->table_name);
                    $storeClsName ='App\Models\\'.$storeClsName;
                    $storeClsName = str_replace([' ','_','-'],"",$storeClsName);

                    // return $storeClsName;
                    if($store->table_name == "damaged_parts"){
                        $storeClsName = "App\Models\\DamagedPart";
                    }
                    try {
                        //code...
                        $storeCls = new $storeClsName();

                        $storeCls->part_id = $request->partIds[$i];
                        $storeCls->amount = $amount;
                        $storeCls->supplier_order_id = $request->orderSupplierId;
                        $storeCls->type_id =$request->types[$i]; ;
                        $storeCls->store_log_id =$storelog->id ;
                        $storeCls->date = Carbon::now();
                        $storeCls->save();

                    } catch (\Throwable $th) {
                        //throw $th;
                        continue;
                    }



                    // /storeManage
                }

            }
        }
        // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> 0 , 'dayin'=>$Actotal ] ); // المخزون دائن
        array_push ( $quaditems , (object) [ 'acountant_id'=> 2641  , 'madin'=> 0 , 'dayin'=>$Actotal ] ); // المخزون دائن
        $date =Carbon::now();
        $type = null;
        $notes='توزيع مباشر علي المخزن ';
        $automaicQayd->AutomaticQayd($quaditems,$date,$type,$notes);
        // return 'sss';
        return redirect()->to('storeManage/');
    }

    public function stores(){
        $stores =  Store::all();
        // return  $stores;
        foreach ($stores as $key => $store) {

             $store_model=ucfirst($store->table_name);
            $storeClsName ='App\Models\\'.$store_model;

            $storeClsName = str_replace([' ','_','-'],"",$storeClsName);
            if($store->table_name == "damaged_parts"){
                $storeClsName = "App\Models\\DamagedPart";
            }
            $store['storeDetails']= $storeClsName::all();

        }
        // return  $store;
        return view('stores',compact('stores'));
    }

    public function gard(Store $storeid){

        $storeClsName=ucfirst($storeid->table_name);
        $storeClsName ='App\Models\\'.$storeClsName;
        $storeClsName = str_replace([' ','_','-'],"",$storeClsName);
        if($storeid->table_name == "damaged_parts"){
            $storeClsName = "App\Models\\DamagedPart";
        }
        $storeDetails = $storeClsName::whereIn('type_id',[1,2,6])->with('order_supplier')->with('stores_log')->get();
        // $storeDetails = $storeClsName::where('type_id',1)->groupBy('part_id','type_id')->selectRaw('sum(amount) as sum, part_id,type_id')->with('order_supplier')->with('stores_log')->get();

        // return $storeDetails;
        foreach ($storeDetails as $key => $value) {
            if($value->type_id == 1){
                $value['part'] = Part::where('id',$value->part_id)->with('part_numbers')->get();
                $value['allPart'] = AllPart::where('id',$value->stores_log->All_part_id)->with('source')->with('status')->with('part_quality')->get();
                $value['part_numbers'] = PartNumber::where('part_id',$value->part_id)->get();
            }elseif($value->type_id == 2){  // wheel
                $value['part'] = Wheel::where('id',$value->part_id)->get();
                $value['allPart'] = AllWheel::where('id',$value->stores_log->All_part_id)->with('source')->with('status')->with('part_quality')->get();
                $value['part_numbers'] =[];
            }elseif($value->type_id == 6){  // kit
                $value['part'] = Kit::where('id',$value->part_id)->get();
                $value['allPart'] = AllKit::where('id',$value->stores_log->All_part_id)->with('source')->with('status')->with('part_quality')->get();
                $value['part_numbers'] = KitNumber::where('kit_id',$value->part_id)->get();
            }

        }
        // return $storeDetails;
        return view('gard',compact('storeDetails','storeid'));


    }

    public function buyInv(BuyTransaction $id){
        // return $id;
        $buyTrans = $id;
        $orderSup = OrderSupplier::where('transaction_id',$buyTrans->id)->with('supplier')->get();

        $store = Store::where('table_name','<>','damaged_parts')->get();
        $items = AllPart::where('order_supplier_id',$orderSup[0]->id)->with('part')->with('source')->with('status')->with('part_quality')->get();
        foreach ($items as $key => $item) {
            $item['remainAmountInInvoice'] = StoresLog::where('All_part_id',$item->id)->where('store_action_id',3)->where('type_id',1)->sum('amount');
            $item['replyOrder'] = Replyorder::where('order_supplier_id',$item->order_supplier_id)->where('part_id',$item->part_id)->where('source_id',$item->source_id)->where('status_id',$item->status_id)->where('quality_id',$item->quality_id)->first();
            $item['Partitemstorelog'] = StoresLog::where('All_part_id',$item->id)->where('type_id',1)->get();
            $item['Kititemstorelog'] = StoresLog::where('All_part_id',$item->id)->where('type_id',6)->get();
            $item['Wheelitemstorelog'] = StoresLog::where('All_part_id',$item->id)->where('type_id',2)->get();

        }

        // return $items;
        return view('editBuyInv',compact('buyTrans','items'));

    }

    public function editBuyInv(Request $request){
        // return $request;
        if($request->type_id == 1) // part
        {
            AllPart::where('id',$request->allpartId)->update(['amount'=> $request->amount ,'remain_amount'=> $request->amount , 'buy_price'=> $request->price]);

        }elseif($request->type_id == 2) // wheel
        {
            AllWheel::where('id',$request->allpartId)->update(['amount'=> $request->amount ,'remain_amount'=> $request->amount , 'buy_price'=> $request->price]);

        }elseif($request->type_id == 6) // kit
        {
            AllKit::where('id',$request->allpartId)->update(['amount'=> $request->amount ,'remain_amount'=> $request->amount , 'buy_price'=> $request->price]);
        }
        else{

            return Redirect::back()->with('message','error try again !');
        }

        Replyorder::where('id',$request->replyorder)->update(['amount'=> $request->amount , 'price'=> $request->price]);

        for ($i=0; $i <count($request->log) ; $i++) {
            StoresLog::where('id',$request->log[$i])->update(['amount'=> $request->amount ]);
        }

        if($request->store_id == 0){
            
        }else{
            $store = Store::where('id',$request->store_id)->get();
            // return $store;
            $storeClsName ='App\Models\\'.ucfirst($store[0]->table_name);
            $storeClsName = str_replace([' ','_','-'],"",$storeClsName);
    
            if($store[0]->table_name == "damaged_parts"){
                $storeClsName = "App\Models\\DamagedPart";
            }

            $storeClsName::where('part_id',$request->part_id)->where('type_id',$request->type_id)->where('supplier_order_id',$request->order_supplier_id)->update(['amount'=> $request->amount ]);    
        }
        
        
        // change total in orderSupplier
        // OrderSupplier::where('id',$request->order_supplier_id)->update([
        //     'pricebeforeTax' => 
        //     ]);

        return Redirect::back()->with('message','Successfully Upadte !');
    }

    public function buyinvnewPage(){
        $bank_types=BankType::all();
        $store_safe=Store::where('safe_accountant_number','<>','0')->get();
        // $lastInvId = BuyTransaction::latest()->first()->id;
        return view('newbuyInvoice2',compact('bank_types','store_safe'));

    }
}
