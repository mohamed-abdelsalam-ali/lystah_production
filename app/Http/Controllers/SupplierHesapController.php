<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CurrencyType;
use App\Models\Invoice;
use App\Models\InvoiceClientMadyonea;
use App\Models\OrderSupplier;
use App\Models\RefundInvoice;
use App\Models\RefundInvoicePayment;

use App\Models\SanadSarf;
use App\Models\ServiceInvoice;
use App\Models\Supplier;
use App\Models\SupplierMadyonea;
use Illuminate\Http\Request;

class SupplierHesapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
            //
            $supplier = Supplier::all();

            return view('supplier_statment.supplier_hesap', compact('supplier'));
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

    public function supplier_hesap()
    {
        $suppliers = Supplier::all();
        return view('items.client_hesap', compact('suppliers'));
    }
    public $start_date1;
    public $end_date1;

    public function hessap2($id)
    {
        $sup_data = Supplier::where('id', '=', $id)->get();
        $client_data= Client::where('sup_id','=',$id)->get();

        // return $id;
        $this->start_date1 = $_GET['start_date'];
        $this->end_date1 = $_GET['end_date'];
        $start_date = $this->start_date1;
        $end_date = $this->end_date1;
           $serviceinv=[];
        if ($start_date && $end_date) {
                // return 'start_date';
               // = Client_pament سندات القيض
               $Client_invoince=[];
               $refund_invoince=[];
               $client_mad=[];
               $sup_mad=[];
               $serviceinv=[];

               $refund_data=[];
                if (count($client_data)>0) {
                    $Client_invoince = Invoice::where('client_id', '=', $client_data[0]->id)->with('store')

                    ->whereDate('date','>=',$start_date)
                      ->whereDate('date','<=',$end_date)
                    ->get();  //invoice_data
                    $refund_data= [];
                    foreach ($Client_invoince as $C_inv) {
                        # code...
                        $refund_invoince = RefundInvoice::where('invoice_id','=' ,$C_inv->id)->get();

                        if(count($refund_invoince)>0){
                            array_push($refund_data,$refund_invoince);
                        }else{
                           array_push($refund_data,[]);
                        }
                    }
                    // return $refund_data;
                    $client_mad = InvoiceClientMadyonea::where('client_id', '=', $client_data[0]->id)

                    ->whereDate('date','>=',$start_date)
                    ->whereDate('date','<=',$end_date)
                    ->get();

                    $serviceinv = ServiceInvoice::where('client_id', '=', $client_data[0]->id)
                    ->whereDate('date','>=',$start_date)
                    ->whereDate('date','<=',$end_date)
                    ->get();


                }
                $Buy_invoince = [];
                $Buy_invoince = OrderSupplier::where('supplier_id', '=', $id)->doesntHave('all_kit_part_item')
                ->with([
                    "buy_transaction"=>function ($query) use ($start_date,$end_date){
                        $query->whereDate('date','>=',$start_date)
                          ->whereDate('date','<=',$end_date);
                    }])->with(['currency_type'=>function($q){
                        $q->with(['currencies'=>function($m){
                            $m->where('to',null)->first();
                        }]);
                    }])->get();  //buy_invoice

                    foreach ($Buy_invoince as $key => $inv) {
                        $c_id = $inv->currency_id;
                        $c_date = $inv->confirmation_date;
                        $Ac_all_currency_types=CurrencyType::with(['currencies'=>function($query) use( $c_id , $c_date){
                            return $query->where('from','>=',$c_date)->where('to','<=',$c_date)->where('currency_id',$c_id)->orWhere('to','=',null);
                        }])->where('id',$c_id)->get();
                        $inv['Egp_total'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price ;
                        $inv['Egp_paied'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
                        $inv['currency_value'] = $Ac_all_currency_types[0]->currencies[0]->value ;



                    }

                $sup_mad = SupplierMadyonea::where('supplier_id', '=', $id)->get();





        } elseif ($start_date && !$end_date) {



                /////////////////////////////////////////////
                $Client_invoince=[];
                $refund_invoince=[];
                $client_mad=[];
                $sup_mad=[];

                $refund_data=[];
                 if (count($client_data)>0) {
                     $Client_invoince = Invoice::where('client_id', '=', $client_data[0]->id)->with('store')
                     ->whereDate('date', '>=', $start_date)
                     ->get();
                     $refund_data= [];
                     foreach ($Client_invoince as $C_inv) {
                         # code...
                         $refund_invoince = RefundInvoice::where('invoice_id','=' ,$C_inv->id)->get();

                         if(count($refund_invoince)>0){
                            array_push($refund_data,$refund_invoince);
                        }else{
                           array_push($refund_data,[]);
                        }

                     }
                      $refund_data;
                     $client_mad = InvoiceClientMadyonea::where('client_id', '=', $client_data[0]->id)
                     ->whereDate('date', '>=', $start_date)->get();  // = Client_pament سندات القيض

                     $serviceinv = ServiceInvoice::where('client_id', '=', $client_data[0]->id)
                     ->whereDate('date', '>=', $start_date)->get();

                 }
                 $Buy_invoince = [];
                 $Buy_invoince = OrderSupplier::where('supplier_id', '=', $id)->doesntHave('all_kit_part_item')
                 ->with([
                     "buy_transaction"=>function ($query) use ($start_date,$end_date){
                         $query->whereDate('date','>=',$start_date);
                     }])->with(['currency_type'=>function($q){
                         $q->with(['currencies'=>function($m){
                             $m->where('to',null)->first();
                         }]);
                     }])->get();  //buy_invoice

                     foreach ($Buy_invoince as $key => $inv) {
                         $c_id = $inv->currency_id;
                         $c_date = $inv->confirmation_date;
                         $Ac_all_currency_types=CurrencyType::with(['currencies'=>function($query) use( $c_id , $c_date){
                             return $query->where('from','>=',$c_date)->where('to','<=',$c_date)->where('currency_id',$c_id)->orWhere('to','=',null);
                         }])->where('id',$c_id)->get();
                         $inv['Egp_total'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price ;
                         $inv['Egp_paied'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
                         $inv['currency_value'] = $Ac_all_currency_types[0]->currencies[0]->value ;



                     }

                 $sup_mad = SupplierMadyonea::where('supplier_id', '=', $id)->get();

                ////////////////////////////////////////////

        } elseif (!$start_date && $end_date) {
           // = Client_pament سندات القيض
           $Client_invoince=[];
           $refund_invoince=[];
           $client_mad=[];
           $sup_mad=[];

           $refund_data=[];
            if (count($client_data)>0) {
                $Client_invoince = Invoice::where('client_id', '=', $client_data[0]->id)->with('store')

                  ->whereDate('date','<=',$end_date)
                ->get();  //invoice_data
                $refund_data= [];
                foreach ($Client_invoince as $C_inv) {
                    # code...
                    $refund_invoince = RefundInvoice::where('invoice_id','=' ,$C_inv->id)->get();

                    if(count($refund_invoince)>0){
                        array_push($refund_data,$refund_invoince);
                    }else{
                       array_push($refund_data,[]);
                    }

                }
                // return $refund_data;
                $client_mad = InvoiceClientMadyonea::where('client_id', '=', $client_data[0]->id)

                ->whereDate('date','<=',$end_date)
                ->get();

                $serviceinv = ServiceInvoice::where('client_id', '=', $client_data[0]->id)
                ->whereDate('date','<=',$end_date)
                ->get();
            }
            $Buy_invoince = [];
            $Buy_invoince = OrderSupplier::where('supplier_id', '=', $id)->doesntHave('all_kit_part_item')
            ->with([
                "buy_transaction"=>function ($query) use ($start_date,$end_date){
                    $query->whereDate('date','<=',$end_date);
                }])->with(['currency_type'=>function($q){
                    $q->with(['currencies'=>function($m){
                        $m->where('to',null)->first();
                    }]);
                }])->get();  //buy_invoice

                foreach ($Buy_invoince as $key => $inv) {
                    $c_id = $inv->currency_id;
                    $c_date = $inv->confirmation_date;
                    $Ac_all_currency_types=CurrencyType::with(['currencies'=>function($query) use( $c_id , $c_date){
                        return $query->where('from','>=',$c_date)->where('to','<=',$c_date)->where('currency_id',$c_id)->orWhere('to','=',null);
                    }])->where('id',$c_id)->get();
                    $inv['Egp_total'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price ;
                    $inv['Egp_paied'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
                    $inv['currency_value'] = $Ac_all_currency_types[0]->currencies[0]->value ;



                }

            $sup_mad = SupplierMadyonea::where('supplier_id', '=', $id)->get();



        } elseif (!$start_date && !$end_date) {
            $Client_invoince=[];
            $refund_invoince=[];
            $client_mad=[];
            $sup_mad=[];

            $refund_data=[];
             if (count($client_data)>0) {
                 $Client_invoince = Invoice::where('client_id', '=', $client_data[0]->id)->with('store')
                 ->get();  //invoice_data
                 $refund_data= [];
                 foreach ($Client_invoince as $C_inv) {
                     # code...
                     $refund_invoince = RefundInvoice::where('invoice_id','=' ,$C_inv->id)->get();

                     if(count($refund_invoince)>0){
                        array_push($refund_data,$refund_invoince);
                    }else{
                       array_push($refund_data,[]);
                    }

                 }
                 // return $refund_data;
                 $client_mad = InvoiceClientMadyonea::where('client_id', '=', $client_data[0]->id)
                 ->get();

                 $serviceinv = ServiceInvoice::where('client_id', '=', $client_data[0]->id)
                 ->get();

             }
             $Buy_invoince = [];
             $Buy_invoince = OrderSupplier::where('supplier_id', '=', $id)->doesntHave('all_kit_part_item')
             ->with(
                 "buy_transaction")->with(['currency_type'=>function($q){
                     $q->with(['currencies'=>function($m){
                         $m->where('to',null)->first();
                     }]);
                 }])->get();  //buy_invoice

                 foreach ($Buy_invoince as $key => $inv) {
                     $c_id = $inv->currency_id;
                     $c_date = $inv->confirmation_date;
                     $Ac_all_currency_types=CurrencyType::with(['currencies'=>function($query) use( $c_id , $c_date){
                         return $query->where('from','>=',$c_date)->where('to','<=',$c_date)->where('currency_id',$c_id)->orWhere('to','=',null);
                     }])->where('id',$c_id)->get();
                     $inv['Egp_total'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price ;
                     $inv['Egp_paied'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
                     $inv['currency_value'] = $Ac_all_currency_types[0]->currencies[0]->value ;



                 }

             $sup_mad = SupplierMadyonea::where('supplier_id', '=', $id)->get();

        }

         return ['sup_mad'=>$sup_mad,'serviceinv' => $serviceinv,'sup_data' => $sup_data,'client_data' => $client_data, 'Client_invoince' => $Client_invoince, 'client_mad' => $client_mad,'Buy_invoince'=>$Buy_invoince ,'refund_data'=>$refund_data];
    }

    public function hesssap_print2($id, $start_date, $end_date)
    {
        $sup_data = Supplier::where('id', '=', $id)->get();
        $client_data= Client::where('sup_id','=',$id)->get();

        // return $id;
        // $this->start_date1 = $_GET['start_date'];
        // $this->end_date1 = $_GET['end_date'];
        // $start_date = $this->start_date1;
        // $end_date = $this->end_date1;
        if ($start_date && $end_date && $start_date != 'null' && $end_date != 'null') {

              // = Client_pament سندات القيض
              $Client_invoince=[];
              $refund_invoince=[];
              $client_mad=[];
              $sup_mad=[];
              $serviceinv=[];


              $refund_data=[];
               if (count($client_data)>0) {
                   $Client_invoince = Invoice::where('client_id', '=', $client_data[0]->id)->with('store')

                   ->whereDate('date','>=',$start_date)
                     ->whereDate('date','<=',$end_date)
                   ->get();  //invoice_data
                   $refund_data= [];
                   foreach ($Client_invoince as $C_inv) {
                       # code...
                       $refund_invoince = RefundInvoice::where('invoice_id','=' ,$C_inv->id)->get();
                       if(count($refund_invoince)>0){
                        array_push($refund_data,$refund_invoince);
                    }else{
                       array_push($refund_data,[]);
                    }

                   }
                   // return $refund_data;
                   $client_mad = InvoiceClientMadyonea::where('client_id', '=', $client_data[0]->id)

                   ->whereDate('date','>=',$start_date)
                   ->whereDate('date','<=',$end_date)
                   ->get();
                   $serviceinv = ServiceInvoice::where('client_id', '=', $client_data[0]->id)
                   ->whereDate('date','>=',$start_date)
                   ->whereDate('date','<=',$end_date)
                   ->get();
               }
               $Buy_invoince = [];
               $Buy_invoince = OrderSupplier::where('supplier_id', '=', $id)->doesntHave('all_kit_part_item')
               ->with([
                   "buy_transaction"=>function ($query) use ($start_date,$end_date){
                       $query->whereDate('date','>=',$start_date)
                         ->whereDate('date','<=',$end_date);
                   }])->with(['currency_type'=>function($q){
                       $q->with(['currencies'=>function($m){
                           $m->where('to',null)->first();
                       }]);
                   }])->get();  //buy_invoice

                   foreach ($Buy_invoince as $key => $inv) {
                       $c_id = $inv->currency_id;
                       $c_date = $inv->confirmation_date;
                       $Ac_all_currency_types=CurrencyType::with(['currencies'=>function($query) use( $c_id , $c_date){
                           return $query->where('from','>=',$c_date)->where('to','<=',$c_date)->where('currency_id',$c_id)->orWhere('to','=',null);
                       }])->where('id',$c_id)->get();
                       $inv['Egp_total'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price ;
                       $inv['Egp_paied'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
                       $inv['currency_value'] = $Ac_all_currency_types[0]->currencies[0]->value ;



                   }

               $sup_mad = SupplierMadyonea::where('supplier_id', '=', $id)->get();



        }elseif (($start_date && !$end_date) || ($start_date != 'null' && $end_date == 'null')) {

                /////////////////////////////////////////////
                $Client_invoince=[];
                $refund_invoince=[];
                $client_mad=[];
                $sup_mad=[];

                $refund_data=[];
                 if (count($client_data)>0) {
                     $Client_invoince = Invoice::where('client_id', '=', $client_data[0]->id)->with('store')
                     ->whereDate('date', '>=', $start_date)
                     ->get();
                     $refund_data= [];
                     foreach ($Client_invoince as $C_inv) {
                         # code...
                         $refund_invoince = RefundInvoice::where('invoice_id','=' ,$C_inv->id)->get();

                         if(count($refund_invoince)>0){
                            array_push($refund_data,$refund_invoince);
                        }else{
                           array_push($refund_data,[]);
                        }

                     }
                     // return $refund_data;
                     $client_mad = InvoiceClientMadyonea::where('client_id', '=', $client_data[0]->id)
                     ->whereDate('date', '>=', $start_date)->get();  // = Client_pament سندات القيض
                     $serviceinv = ServiceInvoice::where('client_id', '=', $client_data[0]->id)
                     ->whereDate('date', '>=', $start_date)->get();

                 }
                 $Buy_invoince = [];
                 $Buy_invoince = OrderSupplier::where('supplier_id', '=', $id)->doesntHave('all_kit_part_item')
                 ->with([
                     "buy_transaction"=>function ($query) use ($start_date,$end_date){
                         $query->whereDate('date','>=',$start_date);
                     }])->with(['currency_type'=>function($q){
                         $q->with(['currencies'=>function($m){
                             $m->where('to',null)->first();
                         }]);
                     }])->get();  //buy_invoice

                     foreach ($Buy_invoince as $key => $inv) {
                         $c_id = $inv->currency_id;
                         $c_date = $inv->confirmation_date;
                         $Ac_all_currency_types=CurrencyType::with(['currencies'=>function($query) use( $c_id , $c_date){
                             return $query->where('from','>=',$c_date)->where('to','<=',$c_date)->where('currency_id',$c_id)->orWhere('to','=',null);
                         }])->where('id',$c_id)->get();
                         $inv['Egp_total'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price ;
                         $inv['Egp_paied'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
                         $inv['currency_value'] = $Ac_all_currency_types[0]->currencies[0]->value ;



                     }

                 $sup_mad = SupplierMadyonea::where('supplier_id', '=', $id)->get();

                ////////////////////////////////////////////

        } elseif ((!$start_date && $end_date) || ($start_date == 'null' && $end_date != 'null')) {

            // = Client_pament سندات القيض
            $Client_invoince=[];
            $refund_invoince=[];
            $client_mad=[];
            $sup_mad=[];

            $refund_data=[];
            if (count($client_data)>0) {
                $Client_invoince = Invoice::where('client_id', '=', $client_data[0]->id)->with('store')

                ->whereDate('date','<=',$end_date)
                ->get();  //invoice_data
                $refund_data= [];
                foreach ($Client_invoince as $C_inv) {
                    # code...
                    $refund_invoince = RefundInvoice::
                    where('invoice_id','=' ,$C_inv->id)->get();

                    if(count($refund_invoince)>0){
                        array_push($refund_data,$refund_invoince);
                    }else{
                       array_push($refund_data,[]);
                    }

                }
                // return $refund_data;
                $client_mad = InvoiceClientMadyonea::where('client_id', '=', $client_data[0]->id)

                ->whereDate('date','<=',$end_date)
                ->get();

                $serviceinv = ServiceInvoice::where('client_id', '=', $client_data[0]->id)
                ->whereDate('date','<=',$end_date)
                ->get();
            }
            $Buy_invoince = [];
            $Buy_invoince = OrderSupplier::where('supplier_id', '=', $id)->doesntHave('all_kit_part_item')
            ->with([
                "buy_transaction"=>function ($query) use ($start_date,$end_date){
                    $query->whereDate('date','<=',$end_date);
                }])->with(['currency_type'=>function($q){
                    $q->with(['currencies'=>function($m){
                        $m->where('to',null)->first();
                    }]);
                }])->get();  //buy_invoice

                foreach ($Buy_invoince as $key => $inv) {
                    $c_id = $inv->currency_id;
                    $c_date = $inv->confirmation_date;
                    $Ac_all_currency_types=CurrencyType::with(['currencies'=>function($query) use( $c_id , $c_date){
                        return $query->where('from','>=',$c_date)->where('to','<=',$c_date)->where('currency_id',$c_id)->orWhere('to','=',null);
                    }])->where('id',$c_id)->get();
                    $inv['Egp_total'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price ;
                    $inv['Egp_paied'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
                    $inv['currency_value'] = $Ac_all_currency_types[0]->currencies[0]->value ;



                }

            $sup_mad = SupplierMadyonea::where('supplier_id', '=', $id)->get();




        } elseif ((!$start_date && !$end_date) || ($start_date == 'null' && $end_date == 'null')) {
            $Client_invoince=[];
            $refund_invoince=[];
            $client_mad=[];
            $sup_mad=[];
            $serviceinv=[];
            $refund_data=[];
             if (count($client_data)>0) {
                 $Client_invoince = Invoice::where('client_id', '=', $client_data[0]->id)->with('store')
                 ->get();  //invoice_data
                 $refund_data= [];
                 foreach ($Client_invoince as $C_inv) {
                     # code...
                     $refund_invoince = RefundInvoice::where('invoice_id','=' ,$C_inv->id)->get();

                     if(count($refund_invoince)>0){
                         array_push($refund_data,$refund_invoince);
                     }else{
                        array_push($refund_data,[]);
                     }

                 }
                 // return $refund_data;
                 $client_mad = InvoiceClientMadyonea::where('client_id', '=', $client_data[0]->id)->get();

                 $serviceinv = ServiceInvoice::where('client_id', '=', $client_data[0]->id)
                 ->get();
             }
             $Buy_invoince = [];
             $Buy_invoince = OrderSupplier::where('supplier_id', '=', $id)->doesntHave('all_kit_part_item')
             ->with(
                 "buy_transaction")->with(['currency_type'=>function($q){
                     $q->with(['currencies'=>function($m){
                         $m->where('to',null)->first();
                     }]);
                 }])->get();  //buy_invoice

                 foreach ($Buy_invoince as $key => $inv) {
                     $c_id = $inv->currency_id;
                     $c_date = $inv->confirmation_date;
                     $Ac_all_currency_types=CurrencyType::with(['currencies'=>function($query) use( $c_id , $c_date){
                         return $query->where('from','>=',$c_date)->where('to','<=',$c_date)->where('currency_id',$c_id)->orWhere('to','=',null);
                     }])->where('id',$c_id)->get();
                     $inv['Egp_total'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price ;
                     $inv['Egp_paied'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
                     $inv['currency_value'] = $Ac_all_currency_types[0]->currencies[0]->value ;



                 }

             $sup_mad = SupplierMadyonea::where('supplier_id', '=', $id)->get();


        }
        // return $sup_mad;
        //  return ['sup_data' => $sup_data, 'Client_invoince' => $Client_invoince, 'client_mad' => $client_mad,'Buy_invoince'=>$Buy_invoince ,'refund_data'=>$refund_data ];


        return view('supplier_statment.hessap_print', compact('sup_mad','client_data','serviceinv','sup_data', 'Client_invoince', 'client_mad','Buy_invoince','refund_data', 'start_date', 'end_date'));
    }

    public function getRassedAllOld($type,$id){
        // return $id;

        $clientData = Client::where('id',$id)->get();
        $supplierData = Supplier::where('id',$id)->get();
        $supplierClientData = Client::where('sup_id',$id)->with('supplier')->first();
        if($type == 'client' && count($clientData) > 0){
            /// فواتير بيع
            /// الضرائب
            $actual_price = Invoice::where('client_id',$id)->sum('actual_price');
            /// خصم علي الفاتورة
            $discount = Invoice::where('client_id',$id)->sum('discount');
            //// المدفوع
            /// سداد مديونية بيع
            $Invoice_paied = Invoice::where('client_id',$id)->sum('paied');
            $InvoiceClientMadyonea_paied = InvoiceClientMadyonea::where('client_id',$id)->sum('paied');
            $InvoiceClientMadyonea_discount = InvoiceClientMadyonea::where('client_id',$id)->sum('discount');
            $totalpaied = $Invoice_paied +$InvoiceClientMadyonea_paied+$InvoiceClientMadyonea_discount;

            /// مرتجعات فواتير البيع
            $client_invoices =  Invoice::where('client_id',$id)->get();
            $total_refund = 0;
            foreach ($client_invoices as $key => $client_invoice) {
                $RefundInvoices = RefundInvoice::where('invoice_id',$client_invoice->id)->get();
                foreach ($RefundInvoices as $key => $RefundInvoice) {
                    $total_refund += ($RefundInvoice->r_amount * $RefundInvoice->item_price)+ $RefundInvoice->r_tax;
                }
            }

            $sarfTotal = SanadSarf::where('client_sup_id',$id)->where('type',1)->sum('paied');

            $serviceinv_total = ServiceInvoice::where('client_id',$id)->sum('total');
            $serviceinv_totalpaied = ServiceInvoice::where('client_id',$id)->sum('totalpaid');
            $serviceinv_discount = ServiceInvoice::where('client_id',$id)->sum('discount');


            $servicesTotal = $serviceinv_totalpaied - $serviceinv_discount;
            //  $rassed = $actual_price - $discount -$totalpaied - $total_refund-$servicesTotal;

             $rassed = 0 ;
             if(floor($actual_price + $serviceinv_total + $sarfTotal) >= floor($discount +$totalpaied + $total_refund  + $serviceinv_totalpaied + $serviceinv_discount + $clientData[0]->client_raseed )){
                    $rassed = floor($actual_price + $serviceinv_total + $sarfTotal - ( $clientData[0]->client_raseed + $totalpaied + $total_refund + $discount +$servicesTotal )   ) ;

                //  $rassed = floor($actual_price - ($totalpaied + $total_refund) + $serviceinv_total + $sarfTotal  - $discount -$servicesTotal-  $clientData[0]->client_raseed) ;

                 $msg ='مديونية';
             }else{

                 $msg ='رصيد';
                $rassed = floor(( $clientData[0]->client_raseed + $totalpaied + $total_refund +$discount  +  $servicesTotal ) - ($actual_price   + $serviceinv_total + $sarfTotal)    );

                //  $rassed = floor(($totalpaied + $total_refund) - $actual_price   + $serviceinv_total - $discount - $sarfTotal - $servicesTotal +  $clientData[0]->client_raseed );
             }
            return ['data' => $clientData[0] ,'message' => $msg  , 'actual_price' => $actual_price , 'discount' => $discount , 'totalpaied' => $totalpaied , 'total_refund' => $total_refund ,'rassed' => $rassed ];


        }elseif ($type == 'supplier' && count($supplierData) > 0) {
            // return $supplierData;

            // فواتيير الشراء
            $actual_price = 0;
            $paied_price = 0;

            $Buy_invoince = OrderSupplier::where('supplier_id', '=', $id)->doesntHave('all_kit_part_item')->get();

                foreach ($Buy_invoince as $key => $inv) {
                    $c_id = $inv->currency_id;
                    $c_date = $inv->confirmation_date;
                    $Ac_all_currency_types=CurrencyType::with(['currencies'=>function($query) use( $c_id , $c_date){
                        return $query->where('from','>=',$c_date)->where('to','<=',$c_date)->where('currency_id',$c_id)->orWhere('to','=',null);
                    }])->where('id',$c_id)->get();


                    $inv['Egp_total'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price ;
                    $inv['Egp_paied'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
                    $inv['currency_value'] = $Ac_all_currency_types[0]->currencies[0]->value ;

                    $actual_price += $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price;
                    $paied_price += $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
                }

            //   $sarfTotal = SanadSarf::where('client_sup_id',$id)->where('type',2)->sum('paied');
            // المدفوعات

            $InvoiceSupplierMadyonea_paied = SupplierMadyonea::where('supplier_id',$id)->sum('paied');
            $totalpaied = $paied_price +$InvoiceSupplierMadyonea_paied;

            $rassed = 0 ;
            if($actual_price >= $totalpaied){
                // المورد له
                // رصيد
                $rassed = $actual_price - $totalpaied;
                $msg ='رصيد';
            }else{
                // المورد عليه
                // مديونية
                $rassed = $totalpaied - $actual_price ;
                $msg ='مديونية';
            }
            return ['data' => $supplierData[0], 'message' => $msg  ,'actual_price' => $actual_price , 'discount' => 0 ,'totalpaied' => $totalpaied ,'total_refund' => 0 ,'rassed' => $rassed ];


        }elseif($type == 'all' && isset($supplierClientData)){
            // return $supplierClientData;
            
            $client_actual_price = Invoice::where('client_id',$supplierClientData->id)->sum('actual_price');
            $client_discount = Invoice::where('client_id',$supplierClientData->id)->sum('discount');
            $client_Invoice_paied = Invoice::where('client_id',$supplierClientData->id)->sum('paied');
            $client_InvoiceClientMadyonea_paied = InvoiceClientMadyonea::where('client_id',$supplierClientData->id)->sum('paied');
            $client_InvoiceClientMadyonea_discount = InvoiceClientMadyonea::where('client_id',$supplierClientData->id)->sum('discount');
            $client_totalpaied = $client_Invoice_paied +$client_InvoiceClientMadyonea_paied - $client_InvoiceClientMadyonea_discount;

            /// مرتجعات فواتير البيع
            $client_client_invoices =  Invoice::where('client_id',$supplierClientData->id)->get();
            $client_total_refund = 0;
            foreach ($client_client_invoices as $key => $client_invoice) {
                $RefundInvoices = RefundInvoice::where('invoice_id',$client_invoice->id)->get();
                foreach ($RefundInvoices as $key => $RefundInvoice) {
                    $client_total_refund += ($RefundInvoice->r_amount * $RefundInvoice->item_price)+ $RefundInvoice->r_tax;
                }
            }

            $serviceinv_total = ServiceInvoice::where('client_id',$supplierClientData->id)->sum('total');
            $serviceinv_totalpaied = ServiceInvoice::where('client_id',$supplierClientData->id)->sum('totalpaid');
            $serviceinv_discount = ServiceInvoice::where('client_id',$supplierClientData->id)->sum('discount');


            $servicesTotal = $serviceinv_totalpaied -$serviceinv_discount;
            $sarfTotal = SanadSarf::where('client_sup_id',$supplierClientData->id)->where('type',1)->sum('paied');
            
             $client_rassed = $client_actual_price + $serviceinv_total - $client_discount - $sarfTotal -($client_totalpaied + $client_total_refund) - $servicesTotal - $supplierClientData->client_raseed;

             /////////////////////////////////////////////////////////////////////////////////////////
             $supplier_actual_price = 0;
            $supplier_paied_price = 0;

            // $supplier_Buy_invoince = OrderSupplier::where('supplier_id', '=', $supplierClientData->supplier->id)->doesntHave('all_kit_part_item')->get();
            $supplierId = $supplierClientData->supplier?->id;

            if ($supplierId) {
                $supplier_Buy_invoince = OrderSupplier::where('supplier_id', $supplierId)
                    ->doesntHave('all_kit_part_item')
                    ->get();
            } else {
                $supplier_Buy_invoince = collect();
            }
            foreach ($supplier_Buy_invoince as $key => $inv) {
                $c_id = $inv->currency_id;
                $c_date = $inv->confirmation_date;
                $Ac_all_currency_types=CurrencyType::with(['currencies'=>function($query) use( $c_id , $c_date){
                    return $query->where('from','>=',$c_date)->where('to','<=',$c_date)->where('currency_id',$c_id)->orWhere('to','=',null);
                }])->where('id',$c_id)->get();


                $inv['Egp_total'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price ;
                $inv['Egp_paied'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
                $inv['currency_value'] = $Ac_all_currency_types[0]->currencies[0]->value ;

                $supplier_actual_price += $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price;
                $supplier_paied_price += $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
            }
            // $supplier_InvoiceSupplierMadyonea_paied = 0;
            // $supplier_InvoiceSupplierMadyonea_paied = SupplierMadyonea::where('supplier_id',$supplierClientData->supplier->id)->sum('paied');
            if ($supplierClientData->supplier) {
                $supplier_InvoiceSupplierMadyonea_paied = SupplierMadyonea::where('supplier_id', $supplierClientData->supplier->id)->sum('paied');
            } else {
                $supplier_InvoiceSupplierMadyonea_paied = 0;
            }
            $supplier_totalpaied = $supplier_paied_price +$supplier_InvoiceSupplierMadyonea_paied;

            $supplier_rassed = $supplier_actual_price  -$supplier_totalpaied ;

            ////////////////////////////////////////////////////////////////////////////////////////////////////////



            $rassed =0;
            $msg =0;
            if($supplier_rassed >= $client_rassed){
                // المورد له
                // رصيد
                $rassed = $supplier_rassed - $client_rassed;
                $msg ='رصيد';
            }else{
                // المورد عليه
                // مديونية
                $rassed = $supplier_rassed - $client_rassed;
                $msg ='مديونية';
            }
            return ['data' => $supplierClientData ,'message' => $msg ,'actual_price' => 0 , 'discount' => 0 ,'totalpaied' => 0 ,'total_refund' => 0 ,'rassed' => $rassed ];


        }else{

        }

        return ['data' => [] ,'message' => 'No Data Founded' ,'actual_price' => 0 , 'discount' => 0 ,'totalpaied' => 0 ,'total_refund' => 0 ,'rassed' => 0 ];



    }
    

       public function getRassedAll11($type,$id){
        // return $id;

        $clientData = Client::where('id',$id)->get();
        $supplierData = Supplier::where('id',$id)->get();
       $supplierClientData = Client::where('sup_id',$id)->with('supplier')->first();


        if($type == 'all' && isset($supplierClientData)){
            // return $supplierClientData;
            ///مجموع إجمالي الفواتير  (بما في ذلك الضريبة)

            $actual_price = Invoice::where('client_id',$supplierClientData->id)->sum('actual_price');
            /// مجموع الضريبة
            $actual_taxes = Invoice::where('client_id',$supplierClientData->id)->sum('tax_amount');
            /// مجموع الخصم
             $discount = Invoice::where('client_id',$supplierClientData->id)->sum('discount');
            /// سداد مديونية بيع
             $Invoice_paied = Invoice::where('client_id',$supplierClientData->id)->sum('paied');
             $InvoiceClientMadyonea_paied = InvoiceClientMadyonea::where('client_id',$supplierClientData->id)->sum('paied');
             $InvoiceClientMadyonea_discount = InvoiceClientMadyonea::where('client_id',$supplierClientData->id)->sum('discount');
            // مجموع المدفوعات
            $totalpaied = $Invoice_paied +$InvoiceClientMadyonea_paied + $InvoiceClientMadyonea_discount;

            $client_invoices =  Invoice::where('client_id',$supplierClientData->id)->get();
            // المبلغ المسترجع الكلي
            $total_refund = 0;
            $total_refund_paied = 0;
            // مجموع مبلغ البضاعة المرتجعة
            $total_refund_without_tax_discount = 0;
            $total_refund_without_tax_discount_paied = 0;
            $total_refund_total_paied = 0;
            foreach ($client_invoices as $key => $client_invoice) {
                $RefundInvoices = RefundInvoice::where('invoice_id',$client_invoice->id)->get();
                $RefundInvoices_paied = RefundInvoicePayment::where('invoice_id',$client_invoice->id)->get();
                foreach ($RefundInvoices as $key => $RefundInvoice) {
                    $total_refund += ($RefundInvoice->r_amount * $RefundInvoice->item_price)+ $RefundInvoice->r_tax - $RefundInvoice->r_discount;
                    $total_refund_without_tax_discount += ($RefundInvoice->r_amount * $RefundInvoice->item_price);
                }
                foreach ($RefundInvoices_paied as $key => $RefundInvoice) {
                     $total_refund_paied += $RefundInvoice->paied;
                    $total_refund_total_paied += $RefundInvoice->total_paied;
                    $total_refund_without_tax_discount_paied += $RefundInvoice->paied - $RefundInvoice->total_dicount - $RefundInvoice->total_tax;
                }
            }
            // قيمة البضائع الأساسية الكلية=مجموع إجمالي الفواتير−مجموع الضريبة
            // return $actual_price;
            $totlItemPrices = $actual_price -$discount;
            // نسبة الخصم الكلية
            $Totaldiscount = 0;
            if($totlItemPrices <= 0){
                $Totaldiscount = 0;
            }else{
                $Totaldiscount = ($discount / $totlItemPrices ) * 100;
            }
            // الخصم المسترجع الكلي
            $totalRefundDiscount=$total_refund_without_tax_discount * ($Totaldiscount / 100);
            // الضريبة المسترجعة الكلية
            $totalRefundTax =0;
            if($totlItemPrices > 0){
                $totalRefundTax = $total_refund_without_tax_discount * ($actual_taxes / $totlItemPrices);
            }
            // المبلغ المسترجع الكلي
            $refundTotal = $total_refund_without_tax_discount + $totalRefundTax - $totalRefundDiscount;
            // صافي الفواتير بعد الاسترجاع
             $totalInvoices_after_refund = $actual_price - $refundTotal;
            // صافي الرصيد
           $rassedd = $totalInvoices_after_refund - $totalpaied - $discount  ;
            //    return $total_refund_total_paied;
            $rassedd = $actual_price - $discount - $total_refund_total_paied - $totalpaied + $total_refund_paied;


            $sarfTotal = SanadSarf::where('client_sup_id',$supplierClientData->id)->where('type',1)->sum('paied');
            $rassedd += $sarfTotal ;

            $serviceinv_total = ServiceInvoice::where('client_id',$supplierClientData->id)->sum('total');
            $serviceinv_totalpaied = ServiceInvoice::where('client_id',$supplierClientData->id)->sum('totalpaid');
            $serviceinv_totalbefortax = ServiceInvoice::where('client_id',$supplierClientData->id)->sum('totalbefortax');
            $serviceinv_discount = ServiceInvoice::where('client_id',$supplierClientData->id)->sum('discount');
            $serviceinv_tax = ServiceInvoice::where('client_id',$supplierClientData->id)->sum('totaltax');

            $servicesTotal =( $serviceinv_totalbefortax - $serviceinv_discount + $serviceinv_tax) - $serviceinv_totalpaied;
            $rassedd += $servicesTotal ;

            //  $rassed = $actual_price - $discount -$totalpaied - $total_refund-$servicesTotal;

             $rassed = 0 ;
             if($rassedd >= 0){
                // if(floor($actual_price + $serviceinv_total + $sarfTotal) >= floor($discount +$totalpaied + $total_refund  + $serviceinv_totalpaied + $serviceinv_discount + $clientData[0]->client_raseed )){

                if(floatVal($supplierClientData->client_raseed) >= $rassedd ){
                    $rassed = $supplierClientData->client_raseed - $rassedd  ;

                }else{
                    $rassed = $rassedd - $supplierClientData->client_raseed   ;

                }
            }else{

                $rassed = ($supplierClientData->client_raseed + $rassedd *-1)  ;
            }



             $client_rassed = $rassed;



             /////////////////////////////////////////////////////////////////////////////////////////
             $supplier_actual_price = 0;
            $supplier_paied_price = 0;

            // $supplier_Buy_invoince = OrderSupplier::where('supplier_id', '=', $supplierClientData->supplier->id)->doesntHave('all_kit_part_item')->get();
            $supplierId = $supplierClientData->supplier?->id;

            if ($supplierId) {
                $supplier_Buy_invoince = OrderSupplier::where('supplier_id', $supplierId)
                    ->doesntHave('all_kit_part_item')
                    ->get();
            } else {
                $supplier_Buy_invoince = collect();
            }
            foreach ($supplier_Buy_invoince as $key => $inv) {
                $c_id = $inv->currency_id;
                $c_date = $inv->confirmation_date;
                $Ac_all_currency_types=CurrencyType::with(['currencies'=>function($query) use( $c_id , $c_date){
                    return $query->where('from','>=',$c_date)->where('to','<=',$c_date)->where('currency_id',$c_id)->orWhere('to','=',null);
                }])->where('id',$c_id)->get();


                $inv['Egp_total'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price ;
                $inv['Egp_paied'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
                $inv['currency_value'] = $Ac_all_currency_types[0]->currencies[0]->value ;

                $supplier_actual_price += $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price;
                $supplier_paied_price += $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
            }
            // $supplier_InvoiceSupplierMadyonea_paied = 0;
            // $supplier_InvoiceSupplierMadyonea_paied = SupplierMadyonea::where('supplier_id',$supplierClientData->supplier->id)->sum('paied');
            if ($supplierClientData->supplier) {
                $supplier_InvoiceSupplierMadyonea_paied = SupplierMadyonea::where('supplier_id', $supplierClientData->supplier->id)->sum('paied');
            } else {
                $supplier_InvoiceSupplierMadyonea_paied = 0;
            }
            $supplier_totalpaied = $supplier_paied_price +$supplier_InvoiceSupplierMadyonea_paied;

            $supplier_rassed = $supplier_actual_price  -$supplier_totalpaied ;

            ////////////////////////////////////////////////////////////////////////////////////////////////////////



            $rassed =0;
            $msg =0;


             if($supplier_rassed - $client_rassed >= 0){
                // if(floor($actual_price + $serviceinv_total + $sarfTotal) >= floor($discount +$totalpaied + $total_refund  + $serviceinv_totalpaied + $serviceinv_discount + $clientData[0]->client_raseed )){

                if($supplier_rassed >= $client_rassed ){
                    $rassed =$supplier_rassed - $client_rassed ;
                    $msg ='رصيد';
                }else{
                    $rassed = $client_rassed - $supplier_rassed   ;
                    $msg ='مديونية';
                }
            }else{
                $msg ='رصيد';
                $rassed = ($supplier_rassed - $client_rassed )*-1  ;
            }


            return ['data' => $supplierClientData ,'message' => $msg ,'actual_price' => 0 , 'discount' => 0 ,'totalpaied' => 0 ,'total_refund' => 0 ,'rassed' => $rassed ];


        }elseif($type == 'client' && count($clientData) > 0){
            ///مجموع إجمالي الفواتير  (بما في ذلك الضريبة)
            $actual_price = Invoice::where('client_id',$id)->sum('actual_price');
            /// مجموع الضريبة
            $actual_taxes = Invoice::where('client_id',$id)->sum('tax_amount');
            /// مجموع الخصم
            //  $discount = Invoice::where('client_id',$id)->sum('discount');
             $discount = Invoice::where('client_id',$id)->where('presale_order_id',null)->sum('discount');
            /// سداد مديونية بيع
             $Invoice_paied = Invoice::where('client_id',$id)->sum('paied');
             $InvoiceClientMadyonea_paied = InvoiceClientMadyonea::where('client_id',$id)->sum('paied');
             $InvoiceClientMadyonea_discount = InvoiceClientMadyonea::where('client_id',$id)->sum('discount');
            // مجموع المدفوعات
            $totalpaied = $Invoice_paied +$InvoiceClientMadyonea_paied+$InvoiceClientMadyonea_discount;

            $client_invoices =  Invoice::where('client_id',$id)->get();
            // المبلغ المسترجع الكلي
            $total_refund = 0;
            $total_refund_paied = 0;
            // مجموع مبلغ البضاعة المرتجعة
            $total_refund_without_tax_discount = 0;
            $total_refund_without_tax_discount_paied = 0;
            $total_refund_total_paied = 0;
            foreach ($client_invoices as $key => $client_invoice) {
                $RefundInvoices = RefundInvoice::where('invoice_id',$client_invoice->id)->get();
                $RefundInvoices_paied = RefundInvoicePayment::where('invoice_id',$client_invoice->id)->get();
                foreach ($RefundInvoices as $key => $RefundInvoice) {
                    $total_refund += ($RefundInvoice->r_amount * $RefundInvoice->item_price)+ $RefundInvoice->r_tax - $RefundInvoice->r_discount;
                    $total_refund_without_tax_discount += ($RefundInvoice->r_amount * $RefundInvoice->item_price);
                }
                foreach ($RefundInvoices_paied as $key => $RefundInvoice) {
                     $total_refund_paied += $RefundInvoice->paied;
                    $total_refund_total_paied += $RefundInvoice->total_paied;
                    $total_refund_without_tax_discount_paied += $RefundInvoice->paied - $RefundInvoice->total_dicount - $RefundInvoice->total_tax;
                }
            }
            // قيمة البضائع الأساسية الكلية=مجموع إجمالي الفواتير−مجموع الضريبة
            // return $actual_price;
            $totlItemPrices = $actual_price -$discount;
            // نسبة الخصم الكلية
            $Totaldiscount = 0;
            if($totlItemPrices <= 0){
                $Totaldiscount = 0;
            }else{
                $Totaldiscount = ($discount / $totlItemPrices ) * 100;
            }
            // الخصم المسترجع الكلي
            $totalRefundDiscount=$total_refund_without_tax_discount * ($Totaldiscount / 100);
            // الضريبة المسترجعة الكلية
            $totalRefundTax =0;
            if($totlItemPrices > 0){
                $totalRefundTax = $total_refund_without_tax_discount * ($actual_taxes / $totlItemPrices);
            }
            // المبلغ المسترجع الكلي
            $refundTotal = $total_refund_without_tax_discount + $totalRefundTax - $totalRefundDiscount;
            // صافي الفواتير بعد الاسترجاع
             $totalInvoices_after_refund = $actual_price - $refundTotal;
            // صافي الرصيد
           $rassedd = $totalInvoices_after_refund - $totalpaied - $discount  ;
            //    return $total_refund_total_paied;
            $rassedd = $actual_price - $discount - $total_refund_total_paied - $totalpaied + $total_refund_paied;


            $sarfTotal = SanadSarf::where('client_sup_id',$id)->where('type',1)->sum('paied');
            $rassedd += $sarfTotal ;

            $serviceinv_total = ServiceInvoice::where('client_id',$id)->sum('total');
            $serviceinv_totalpaied = ServiceInvoice::where('client_id',$id)->sum('totalpaid');
            $serviceinv_totalbefortax = ServiceInvoice::where('client_id',$id)->sum('totalbefortax');
            $serviceinv_discount = ServiceInvoice::where('client_id',$id)->sum('discount');
            $serviceinv_tax = ServiceInvoice::where('client_id',$id)->sum('totaltax');

            $servicesTotal =( $serviceinv_totalbefortax - $serviceinv_discount + $serviceinv_tax) - $serviceinv_totalpaied;
            $rassedd += $servicesTotal ;

            //  $rassed = $actual_price - $discount -$totalpaied - $total_refund-$servicesTotal;

             $rassed = 0 ;
             if($rassedd >= 0){
                // if(floor($actual_price + $serviceinv_total + $sarfTotal) >= floor($discount +$totalpaied + $total_refund  + $serviceinv_totalpaied + $serviceinv_discount + $clientData[0]->client_raseed )){

                if($clientData[0]->client_raseed >= $rassedd ){
                    $rassed = $clientData[0]->client_raseed - $rassedd  ;
                    $msg ='رصيد';
                }else{
                    $rassed = $rassedd - $clientData[0]->client_raseed   ;
                    $msg ='مديونية';
                }
            }else{
                $msg ='رصيد';
                $rassed = ($clientData[0]->client_raseed + $rassedd *-1)  ;
                if($rassed < 0){
                    $rassed = $rassed * -1;
                }
            }
            return ['data' => $clientData[0] ,'message' => $msg  , 'actual_price' => $actual_price , 'discount' => $discount , 'totalpaied' => $totalpaied , 'total_refund' => $total_refund ,'rassed' => round($rassed,2) ];


        }elseif ($type == 'supplier' && count($supplierData) > 0) {
            // return $supplierData;

            // فواتيير الشراء
            $actual_price = 0;
            $paied_price = 0;

            $Buy_invoince = OrderSupplier::where('supplier_id', '=', $id)->doesntHave('all_kit_part_item')->get();

                foreach ($Buy_invoince as $key => $inv) {
                    $c_id = $inv->currency_id;
                    $c_date = $inv->confirmation_date;
                    $Ac_all_currency_types=CurrencyType::with(['currencies'=>function($query) use( $c_id , $c_date){
                        return $query->where('from','>=',$c_date)->where('to','<=',$c_date)->where('currency_id',$c_id)->orWhere('to','=',null);
                    }])->where('id',$c_id)->get();


                    $inv['Egp_total'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price ;
                    $inv['Egp_paied'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
                    $inv['currency_value'] = $Ac_all_currency_types[0]->currencies[0]->value ;

                    $actual_price += $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price;
                    $paied_price += $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
                }

            //   $sarfTotal = SanadSarf::where('client_sup_id',$id)->where('type',2)->sum('paied');
            // المدفوعات

            $InvoiceSupplierMadyonea_paied = SupplierMadyonea::where('supplier_id',$id)->sum('paied');
            $totalpaied = $paied_price +$InvoiceSupplierMadyonea_paied;

            $rassed = 0 ;
            if($actual_price >= $totalpaied){
                // المورد له
                // رصيد
                $rassed = $actual_price - $totalpaied;
                $msg ='رصيد';
            }else{
                // المورد عليه
                // مديونية
                $rassed = $totalpaied - $actual_price ;
                $msg ='مديونية';
            }
            return ['data' => $supplierData[0], 'message' => $msg  ,'actual_price' => $actual_price , 'discount' => 0 ,'totalpaied' => $totalpaied ,'total_refund' => 0 ,'rassed' => $rassed ];


        }else{

        }

        return ['data' => [] ,'message' => 'No Data Founded' ,'actual_price' => 0 , 'discount' => 0 ,'totalpaied' => 0 ,'total_refund' => 0 ,'rassed' => 0 ];



    }
    
    
    public function getRassedAll($type,$id){
        // return $id;

        $clientData = Client::where('id',$id)->get();
        $supplierData = Supplier::where('id',$id)->get();
       $supplierClientData = Client::where('sup_id',$id)->with('supplier')->first();


        if($type == 'all' && isset($supplierClientData)){
            // return $supplierClientData;
            ///مجموع إجمالي الفواتير  (بما في ذلك الضريبة)

            $actual_price = Invoice::where('client_id',$supplierClientData->id)->sum('actual_price');
            /// مجموع الضريبة
            $actual_taxes = Invoice::where('client_id',$supplierClientData->id)->sum('tax_amount');
            /// مجموع الخصم
             $discount = Invoice::where('client_id',$supplierClientData->id)->whereNull('presale_order_id')->sum('discount');
            /// سداد مديونية بيع
             $Invoice_paied = Invoice::where('client_id',$supplierClientData->id)->sum('paied');
             $InvoiceClientMadyonea_paied = InvoiceClientMadyonea::where('client_id',$supplierClientData->id)->sum('paied');
             $InvoiceClientMadyonea_discount = InvoiceClientMadyonea::where('client_id',$supplierClientData->id)->sum('discount');
            // مجموع المدفوعات
            $totalpaied = $Invoice_paied +$InvoiceClientMadyonea_paied + $InvoiceClientMadyonea_discount;

            $client_invoices =  Invoice::where('client_id',$supplierClientData->id)->get();
            // المبلغ المسترجع الكلي
            $total_refund = 0;
            $total_refund_paied = 0;
            // مجموع مبلغ البضاعة المرتجعة
            $total_refund_without_tax_discount = 0;
            $total_refund_without_tax_discount_paied = 0;
            $total_refund_total_paied = 0;
            foreach ($client_invoices as $key => $client_invoice) {
                $RefundInvoices = RefundInvoice::where('invoice_id',$client_invoice->id)->get();
                $RefundInvoices_paied = RefundInvoicePayment::where('invoice_id',$client_invoice->id)->get();
                foreach ($RefundInvoices as $key => $RefundInvoice) {
                    $total_refund += ($RefundInvoice->r_amount * $RefundInvoice->item_price)+ $RefundInvoice->r_tax - $RefundInvoice->r_discount;
                    $total_refund_without_tax_discount += ($RefundInvoice->r_amount * $RefundInvoice->item_price);
                }
                foreach ($RefundInvoices_paied as $key => $RefundInvoice) {
                     $total_refund_paied += $RefundInvoice->paied;
                    $total_refund_total_paied += $RefundInvoice->total_paied;
                    $total_refund_without_tax_discount_paied += $RefundInvoice->paied - $RefundInvoice->total_dicount - $RefundInvoice->total_tax;
                }
            }
            // قيمة البضائع الأساسية الكلية=مجموع إجمالي الفواتير−مجموع الضريبة
            // return $actual_price;
            $totlItemPrices = $actual_price -$discount;
            // نسبة الخصم الكلية
            $Totaldiscount = 0;
            if($totlItemPrices <= 0){
                $Totaldiscount = 0;
            }else{
                $Totaldiscount = ($discount / $totlItemPrices ) * 100;
            }
            // الخصم المسترجع الكلي
            $totalRefundDiscount=$total_refund_without_tax_discount * ($Totaldiscount / 100);
            // الضريبة المسترجعة الكلية
            $totalRefundTax =0;
            if($totlItemPrices > 0){
                $totalRefundTax = $total_refund_without_tax_discount * ($actual_taxes / $totlItemPrices);
            }
            // المبلغ المسترجع الكلي
            $refundTotal = $total_refund_without_tax_discount + $totalRefundTax - $totalRefundDiscount;
            // صافي الفواتير بعد الاسترجاع
             $totalInvoices_after_refund = $actual_price - $refundTotal;
            // صافي الرصيد
           $rassedd = $totalInvoices_after_refund - $totalpaied - $discount  ;
            //    return $total_refund_total_paied;
            $rassedd = $actual_price - $discount - $total_refund_total_paied - $totalpaied + $total_refund_paied;


            $sarfTotal = SanadSarf::where('client_sup_id',$supplierClientData->id)->where('type',1)->sum('paied');
            $rassedd += $sarfTotal ;

            $serviceinv_total = ServiceInvoice::where('client_id',$supplierClientData->id)->sum('total');
            $serviceinv_totalpaied = ServiceInvoice::where('client_id',$supplierClientData->id)->sum('totalpaid');
            $serviceinv_totalbefortax = ServiceInvoice::where('client_id',$supplierClientData->id)->sum('totalbefortax');
            $serviceinv_discount = ServiceInvoice::where('client_id',$supplierClientData->id)->sum('discount');
            $serviceinv_tax = ServiceInvoice::where('client_id',$supplierClientData->id)->sum('totaltax');

            $servicesTotal =( $serviceinv_totalbefortax - $serviceinv_discount + $serviceinv_tax) - $serviceinv_totalpaied;
            $rassedd += $servicesTotal ;

            //  $rassed = $actual_price - $discount -$totalpaied - $total_refund-$servicesTotal;

             $rassed = 0 ;
             if($rassedd >= 0){
                // if(floor($actual_price + $serviceinv_total + $sarfTotal) >= floor($discount +$totalpaied + $total_refund  + $serviceinv_totalpaied + $serviceinv_discount + $clientData[0]->client_raseed )){

                if(floatVal($supplierClientData->client_raseed) >= $rassedd ){
                    $rassed = $supplierClientData->client_raseed - $rassedd  ;

                }else{
                    $rassed = $rassedd - $supplierClientData->client_raseed   ;

                }
            }else{
                // if($supplierClientData->client_raseed >= $rassedd ){
                //     $rassed = $supplierClientData->client_raseed - $rassedd  ;

                // }else{
                //     $rassed = $rassedd - $supplierClientData->client_raseed   ;

                // }
                $rassed = (floatVal($supplierClientData->client_raseed) + $rassedd *-1)  ;
            }



             $client_rassed = $rassed;



             /////////////////////////////////////////////////////////////////////////////////////////
             $supplier_actual_price = 0;
            $supplier_paied_price = 0;

            // $supplier_Buy_invoince = OrderSupplier::where('supplier_id', '=', $supplierClientData->supplier->id)->doesntHave('all_kit_part_item')->get();
            $supplierId = $supplierClientData->supplier?->id;

            if ($supplierId) {
                $supplier_Buy_invoince = OrderSupplier::where('supplier_id', $supplierId)
                    ->doesntHave('all_kit_part_item')
                    ->get();
            } else {
                $supplier_Buy_invoince = collect();
            }
            foreach ($supplier_Buy_invoince as $key => $inv) {
                $c_id = $inv->currency_id;
                $c_date = $inv->confirmation_date;
                $Ac_all_currency_types=CurrencyType::with(['currencies'=>function($query) use( $c_id , $c_date){
                    return $query->where('from','>=',$c_date)->where('to','<=',$c_date)->where('currency_id',$c_id)->orWhere('to','=',null);
                }])->where('id',$c_id)->get();


                $inv['Egp_total'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price ;
                $inv['Egp_paied'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
                $inv['currency_value'] = $Ac_all_currency_types[0]->currencies[0]->value ;

                $supplier_actual_price += $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price;
                $supplier_paied_price += $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
            }
            // $supplier_InvoiceSupplierMadyonea_paied = 0;
            // $supplier_InvoiceSupplierMadyonea_paied = SupplierMadyonea::where('supplier_id',$supplierClientData->supplier->id)->sum('paied');
            if ($supplierClientData->supplier) {
                $supplier_InvoiceSupplierMadyonea_paied = SupplierMadyonea::where('supplier_id', $supplierClientData->supplier->id)->sum('paied');
            } else {
                $supplier_InvoiceSupplierMadyonea_paied = 0;
            }
            $supplier_totalpaied = $supplier_paied_price +$supplier_InvoiceSupplierMadyonea_paied;

            $supplier_rassed = $supplier_actual_price  -$supplier_totalpaied ;

            ////////////////////////////////////////////////////////////////////////////////////////////////////////



            $rassed =0;
            $msg =0;


             if($supplier_rassed - $client_rassed >= 0){
                // if(floor($actual_price + $serviceinv_total + $sarfTotal) >= floor($discount +$totalpaied + $total_refund  + $serviceinv_totalpaied + $serviceinv_discount + $clientData[0]->client_raseed )){

                if($supplier_rassed >= $client_rassed ){
                    $rassed =$supplier_rassed - $client_rassed ;
                    $msg ='رصيد';
                }else{
                    $rassed = $client_rassed - $supplier_rassed   ;
                    $msg ='مديونية';
                }
            }else{
                $msg ='رصيد';
                $rassed = ($supplier_rassed - $client_rassed )*-1  ;
            }


            return ['data' => $supplierClientData ,'message' => $msg ,'actual_price' => 0 , 'discount' => 0 ,'totalpaied' => 0 ,'total_refund' => 0 ,'rassed' => $rassed ];


        }elseif($type == 'client' && count($clientData) > 0){
            ///مجموع إجمالي الفواتير  (بما في ذلك الضريبة)
            $actual_price = Invoice::where('client_id',$id)->sum('actual_price');
            /// مجموع الضريبة
            $actual_taxes = Invoice::where('client_id',$id)->sum('tax_amount');
            /// مجموع الخصم
             $discount = Invoice::where('client_id',$id)->where('presale_order_id',null)->sum('discount');
            /// سداد مديونية بيع
             $Invoice_paied = Invoice::where('client_id',$id)->sum('paied');
             $InvoiceClientMadyonea_paied = InvoiceClientMadyonea::where('client_id',$id)->sum('paied');
             $InvoiceClientMadyonea_discount = InvoiceClientMadyonea::where('client_id',$id)->sum('discount');
            // مجموع المدفوعات
            $totalpaied = $Invoice_paied +$InvoiceClientMadyonea_paied+$InvoiceClientMadyonea_discount;

            $client_invoices =  Invoice::where('client_id',$id)->get();
            // المبلغ المسترجع الكلي
            $total_refund = 0;
            $total_refund_paied = 0;
            // مجموع مبلغ البضاعة المرتجعة
            $total_refund_without_tax_discount = 0;
            $total_refund_without_tax_discount_paied = 0;
            $total_refund_total_paied = 0;
            foreach ($client_invoices as $key => $client_invoice) {
                $RefundInvoices = RefundInvoice::where('invoice_id',$client_invoice->id)->get();
                $RefundInvoices_paied = RefundInvoicePayment::where('invoice_id',$client_invoice->id)->get();
                foreach ($RefundInvoices as $key => $RefundInvoice) {
                    $total_refund += ($RefundInvoice->r_amount * $RefundInvoice->item_price)+ $RefundInvoice->r_tax - $RefundInvoice->r_discount;
                    $total_refund_without_tax_discount += ($RefundInvoice->r_amount * $RefundInvoice->item_price);
                }
                foreach ($RefundInvoices_paied as $key => $RefundInvoice) {
                     $total_refund_paied += $RefundInvoice->paied;
                    $total_refund_total_paied += $RefundInvoice->total_paied;
                    $total_refund_without_tax_discount_paied += $RefundInvoice->paied - $RefundInvoice->total_dicount - $RefundInvoice->total_tax;
                }
            }
            // قيمة البضائع الأساسية الكلية=مجموع إجمالي الفواتير−مجموع الضريبة
            // return $actual_price;
            $totlItemPrices = $actual_price -$discount;
            // نسبة الخصم الكلية
            $Totaldiscount = 0;
            if($totlItemPrices <= 0){
                $Totaldiscount = 0;
            }else{
                $Totaldiscount = ($discount / $totlItemPrices ) * 100;
            }
            // الخصم المسترجع الكلي
            $totalRefundDiscount=$total_refund_without_tax_discount * ($Totaldiscount / 100);
            // الضريبة المسترجعة الكلية
            $totalRefundTax =0;
            if($totlItemPrices > 0){
                $totalRefundTax = $total_refund_without_tax_discount * ($actual_taxes / $totlItemPrices);
            }
            // المبلغ المسترجع الكلي
            $refundTotal = $total_refund_without_tax_discount + $totalRefundTax - $totalRefundDiscount;
            // صافي الفواتير بعد الاسترجاع
            $totalInvoices_after_refund = $actual_price - $refundTotal;
            // صافي الرصيد
            // return $totalpaied;
            $rassedd = $totalInvoices_after_refund - $totalpaied - $discount  ;
            //    return $total_refund_paied;
            $rassedd = $actual_price - $discount - $total_refund_total_paied - $totalpaied + $total_refund_paied;


            $sarfTotal = SanadSarf::where('client_sup_id',$id)->where('type',1)->sum('paied');
            $rassedd += $sarfTotal ;

            $serviceinv_total = ServiceInvoice::where('client_id',$id)->sum('total');
            $serviceinv_totalpaied = ServiceInvoice::where('client_id',$id)->sum('totalpaid');
            $serviceinv_totalbefortax = ServiceInvoice::where('client_id',$id)->sum('totalbefortax');
            $serviceinv_discount = ServiceInvoice::where('client_id',$id)->sum('discount');
            $serviceinv_tax = ServiceInvoice::where('client_id',$id)->sum('totaltax');

            $servicesTotal =( $serviceinv_totalbefortax - $serviceinv_discount + $serviceinv_tax) - $serviceinv_totalpaied;
             $rassedd += $servicesTotal ;
            //   $clientData[0]->client_raseed;
            //  $rassed = $actual_price - $discount -$totalpaied - $total_refund-$servicesTotal;

             $rassed = 0 ;
             if($rassedd >= 0){
                // if(floor($actual_price + $serviceinv_total + $sarfTotal) >= floor($discount +$totalpaied + $total_refund  + $serviceinv_totalpaied + $serviceinv_discount + $clientData[0]->client_raseed )){

                if(floatVal($clientData[0]->client_raseed) >= $rassedd ){
                    $rassed = $clientData[0]->client_raseed - $rassedd  ;
                    $msg ='رصيد';
                }else{
                    $rassed = $rassedd - $clientData[0]->client_raseed   ;
                    $msg ='مديونية';
                }
            }else{
                if(floatVal($clientData[0]->client_raseed) >= $rassedd ){
                    $msg ='رصيد';
                    $rassed = ($clientData[0]->client_raseed - $rassedd) *-1 ;
                }else{
                    $msg ='مديونية';
                    $rassed = ($clientData[0]->client_raseed - $rassedd )*-1 ;
                }

                if($rassed < 0){
                    $rassed = $rassed * -1;
                }
            }
            return ['data' => $clientData[0] ,'message' => $msg  , 'actual_price' => $actual_price , 'discount' => $discount , 'totalpaied' => $totalpaied , 'total_refund' => $total_refund ,'rassed' => round($rassed,2) ];


        }elseif ($type == 'supplier' && count($supplierData) > 0) {
            // return $supplierData;

            // فواتيير الشراء
            $actual_price = 0;
            $paied_price = 0;

         $Buy_invoince = OrderSupplier::where('supplier_id', '=', $id)->doesntHave('all_kit_part_item')->get();

                foreach ($Buy_invoince as $key => $inv) {
                    $c_id = $inv->currency_id;
                    $c_date = $inv->confirmation_date;
                    $Ac_all_currency_types=CurrencyType::with(['currencies'=>function($query) use( $c_id , $c_date){
                        return $query->where('from','>=',$c_date)->where('to','<=',$c_date)->where('currency_id',$c_id)->orWhere('to','=',null);
                    }])->where('id',$c_id)->get();


                    $inv['Egp_total'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price ;
                    $inv['Egp_paied'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
                    $inv['currency_value'] = $Ac_all_currency_types[0]->currencies[0]->value ;

                    $actual_price += $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price;
                    $paied_price += $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
                }

            //   $sarfTotal = SanadSarf::where('client_sup_id',$id)->where('type',2)->sum('paied');
            // المدفوعات
                // return $actual_price;
             $InvoiceSupplierMadyonea_paied = SupplierMadyonea::where('supplier_id',$id)->sum('paied');
            $totalpaied = $paied_price +$InvoiceSupplierMadyonea_paied;

            $rassed = 0 ;
            if($actual_price >= $totalpaied){
                // المورد له
                // رصيد
                $rassed = $actual_price - $totalpaied;
                $msg ='رصيد';
            }else{
                // المورد عليه
                // مديونية
                $rassed = $totalpaied - $actual_price ;
                $msg ='مديونية';
            }
            return ['data' => $supplierData[0], 'message' => $msg  ,'actual_price' => $actual_price , 'discount' => 0 ,'totalpaied' => $totalpaied ,'total_refund' => 0 ,'rassed' => $rassed ];


        }else{

        }

        return ['data' => [] ,'message' => 'No Data Founded' ,'actual_price' => 0 , 'discount' => 0 ,'totalpaied' => 0 ,'total_refund' => 0 ,'rassed' => 0 ];



    }
}
