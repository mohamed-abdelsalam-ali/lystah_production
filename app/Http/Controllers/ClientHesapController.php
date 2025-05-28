<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\CurrencyType;
use App\Models\Invoice;
use App\Models\InvoiceClientMadyonea;
use App\Models\OrderSupplier;
use App\Models\RefundInvoice;
use App\Models\SanadSarf;
use App\Models\ServiceInvoice;
use App\Models\SupplierMadyonea;

class ClientHesapController extends Controller
{
    //
    public function index()
    {
        //
        $clients = Client::all();
        // $user = User::all();
        // $PaymentMethod = PaymentMethod::all();
        // $login_user = auth()->user();
        // return $login_user;
        return view('client_statment.client_hesap', compact('clients'));

    }


    public function show($id)
    {
        //where('remian_price','>',0)->where('invoice_type_id','<>',2)->
        // $all_invoices = ClientInvoice::where('client_id', '=', $id)
        //     ->with('client')
        //     ->with('payment_method')
        //     ->with('invoice_type')
        //     ->with('client_invoice_rents')
        //     ->with('client_refund')
        //     ->with([
        //         'client_items' => function ($query) {
        //             $query->where('flag_amount_updated', '=', 0);
        //         },
        //     ])
        //     ->with('client_payments')
        //     ->with('user')
        //     ->with('counter')
        //     ->with('items_invoices_name')
        //     ->get();
        // $all_invoices_total_rent = ClientInvoice::where('client_id', '=', $id)
        //     ->where('invoice_type_id', '<>', 2)
        //     ->with('client')
        //     ->with('payment_method')
        //     ->with('invoice_type')
        //     ->with('client_invoice_rents')
        //     ->with([
        //         'client_items' => function ($query) {
        //             $query->where('flag_amount_updated', '=', 0);
        //         },
        //     ])
        //     ->with('client_payments')
        //     ->with('client_refund')
        //     ->with('user')
        //     ->with('counter')
        //     ->sum('total');
        // $all_invoices_total_sell = ClientInvoice::where('client_id', '=', $id)
        //     ->where('invoice_type_id', '=', 2)
        //     ->with('client')
        //     ->with('payment_method')
        //     ->with('invoice_type')
        //     ->with('client_invoice_rents')
        //     ->with([
        //         'client_items' => function ($query) {
        //             $query->where('flag_amount_updated', '=', 0);
        //         },
        //     ])
        //     ->with('client_payments')
        //     ->with('client_refund')

        //     ->with('user')
        //     ->with('counter')
        //     ->sum('total');
        // $all_invoices_remain = ClientInvoice::where('client_id', '=', $id)
        //     ->with('client')
        //     ->with('payment_method')
        //     ->with('invoice_type')
        //     ->with('client_invoice_rents')
        //     ->with([
        //         'client_items' => function ($query) {
        //             $query->where('flag_amount_updated', '=', 0);
        //         },
        //     ])
        //     ->with('client_payments')
        //     ->with('client_refund')

        //     ->with('user')
        //     ->with('counter')
        //     ->sum('remian_price');

        // // return $all_invoices_total;
        // return [$all_invoices, $all_invoices_total_rent, $all_invoices_total_sell, $all_invoices_remain];
    }
    public function get_inv($id)
    {
        // $invoice = ClientInvoice::where('id', $id)
        //     ->with('client')
        //     ->with('payment_method')
        //     ->with('invoice_type')
        //     ->with('client_payments_recep')
        //     ->with('client_invoice_rents')
        //     ->with([
        //         'client_items' => function ($query) {
        //             $query->where('flag_amount_updated', '=', 0);
        //         },
        //     ])
        //     ->with('client_payments')
        //     ->with('user')
        //     ->with('counter')
        //     ->first();
        // return $invoice;
    }
    public function client_hesap()
    {
        $clients = Client::all();
        return view('items.client_hesap', compact('clients'));
    }
    public $start_date1;
    public $end_date1;

    public function hessap($id)
    {
        $client_data = Client::where('id', '=', $id)->get();
        $sup_mad =[];
        $serviceinv=[];

        // return $id;
        $this->start_date1 = $_GET['start_date'];
        $this->end_date1 = $_GET['end_date'];
        $start_date = $this->start_date1;
        $end_date = $this->end_date1;

        $gho_return_array=[];

        if ($start_date && $end_date) {
                // return 'start_date';
                $Client_invoince = Invoice::where('client_id', '=', $id)->with('store')->with('refund_invoice_payment')

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
                $client_sup_sarf = SanadSarf::where('client_sup_id', '=', $id)->whereDate('date','>=',$start_date)
                ->whereDate('date','<=',$end_date)
                ->get();
                 $client_mad = InvoiceClientMadyonea::where('client_id', '=', $id)

                 ->whereDate('date','>=',$start_date)
                   ->whereDate('date','<=',$end_date)
                 ->get();  // = Client_pament سندات القيض



                 $serviceinv = ServiceInvoice::where('client_id', '=', $client_data[0]->id)
                 ->whereDate('date','>=',$start_date)
                 ->whereDate('date','<=',$end_date)
                 ->get();
                $Buy_invoince = [];
                if ($client_data[0]->sup_id != null) {
                    $Buy_invoince = OrderSupplier::where('supplier_id', '=', $client_data[0]->sup_id)->doesntHave('all_kit_part_item')
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
                        $sup_mad = SupplierMadyonea::where('supplier_id', '=', $client_data[0]->sup_id)->get();


                }


        } elseif ($start_date && !$end_date) {
            // return 'start_date';

            $Client_invoince = Invoice::where('client_id', '=', $id)->with('store')->with('refund_invoice_payment')
            ->whereDate('date', '>=', $start_date)
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
            $client_sup_sarf = SanadSarf::where('client_sup_id', '=', $id)
            ->whereDate('date', '>=', $start_date)->get();
                $client_mad = InvoiceClientMadyonea::where('client_id', '=', $id)
                ->whereDate('date', '>=', $start_date)->get();  // = Client_pament سندات القيض


                $serviceinv = ServiceInvoice::where('client_id', '=', $client_data[0]->id)
                ->whereDate('date','>=',$start_date)
                ->get();
                $Buy_invoince = [];
                if ($client_data[0]->sup_id != null) {
                    $Buy_invoince = OrderSupplier::where('supplier_id', '=', $client_data[0]->sup_id)->doesntHave('all_kit_part_item')
                    ->with([
                        "buy_transaction"=>function ($query) use ($start_date,$end_date){
                            $query->whereDate('date','>=',  $start_date);
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
                        $sup_mad = SupplierMadyonea::where('supplier_id', '=', $client_data[0]->sup_id)->get();

                    }

        } elseif (!$start_date && $end_date) {
            $Client_invoince = Invoice::where('client_id', '=', $id)->with('store')->with('refund_invoice_payment')
            ->whereDate('date', '<=', $end_date)

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
            $client_sup_sarf = SanadSarf::where('client_sup_id', '=', $id)->whereDate('date', '<=', $end_date)
            ->get();
                $client_mad = InvoiceClientMadyonea::where('client_id', '=', $id)
                ->whereDate('date', '<=', $end_date)
                ->get();  // = Client_pament سندات القيض

                $serviceinv = ServiceInvoice::where('client_id', '=', $client_data[0]->id)
                ->whereDate('date','<=',$end_date)
                ->get();
                $Buy_invoince = [];
                if ($client_data[0]->sup_id != null) {
                    $Buy_invoince = OrderSupplier::where('supplier_id', '=', $client_data[0]->sup_id)->doesntHave('all_kit_part_item')
                    ->with([
                        "buy_transaction"=>function ($query) use ($start_date,$end_date){
                            $query->whereDate('date','<=',  $end_date);
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
                        $sup_mad = SupplierMadyonea::where('supplier_id', '=', $client_data[0]->sup_id)->get();

                }


        } elseif (!$start_date && !$end_date) {
               $Client_invoince = Invoice::where('client_id', '=', $id)->with('store')->with('refund_invoice_payment')
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
                $client_mad = InvoiceClientMadyonea::where('client_id', '=', $id)->get();
                $client_sup_sarf = SanadSarf::where('client_sup_id', '=', $id)
                ->get();  // = Client_pament سندات القيض



                $serviceinv = ServiceInvoice::where('client_id', '=', $client_data[0]->id)
                 ->get();

                $Buy_invoince = [];
                if ($client_data[0]->sup_id != null) {
                    $Buy_invoince = OrderSupplier::where('supplier_id', '=', $client_data[0]->sup_id)->doesntHave('all_kit_part_item')
                    ->with('buy_transaction')->with(['currency_type'=>function($q){
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
                        $sup_mad = SupplierMadyonea::where('supplier_id', '=', $client_data[0]->sup_id)->get();
                }



        }

/*******************Here Ghoniem  */
        if($client_data[0]->client_raseed > 0){
            array_push($gho_return_array,
            ['name' => 'الرصيد الإفتتاحي' ,
            'Store' => '--' ,
            'Invoice_data' => '--' ,
            'date' => '--' ,
            'madeen' => '0' , 'da2en' => $client_data[0]->client_raseed ,
            'total' => '0','color' => '' , 'order' => 0]);

        }else{
            array_push($gho_return_array,
            ['name' => 'الرصيد الإفتتاحي' ,
            'Store' => '--' ,
            'Invoice_data' => '--' ,
            'date' => '--' ,
            'madeen' => $client_data[0]->client_raseed *-1 , 'da2en' => '0' ,
            'total' => '0','color' => '' , 'order' => 0]);

        }
        $g_total_refund=0;
        $g_total_refund_paied=0;
        $g_refund_paied =0;
        $g_total_refund_paiedxx = 0;
        foreach ($Client_invoince as $C_inv) {
            array_push($gho_return_array,
            ['name' => 'حساب فاتورة بيع' ,
            'Store' => $C_inv->store->name ,
            'Invoice_data' => $C_inv->id ,
            'date' => $C_inv->date->format('Y-m-d') ,
            'madeen' => $C_inv->actual_price , 'da2en' => '0' ,
            'total' => '0',
            'color' => '' , 'order' => 1
            ]);

            if($C_inv->paied > 0){
                array_push($gho_return_array,
            ['name' => 'إستلام نقدية فاتورة بيع' ,
            'Store' => $C_inv->store->name ,
            'Invoice_data' => $C_inv->id ,
            'date' => $C_inv->date->format('Y-m-d') ,
            'madeen' => '0' , 'da2en' => $C_inv->paied ,
            'total' => '0','color' => '' , 'order' => 2]);
            }


            if($C_inv->refund_invoice_payment){
                foreach ($C_inv->refund_invoice_payment as $key => $refpay) {
                    if( $refpay->paied > 0){
                        array_push($gho_return_array,
                        ['name' => 'صرف نقدية على إسترجاع' ,
                        'Store' => '--' ,
                        'Invoice_data' => $refpay->invoice_id ,
                        'date' => $refpay->created_at->format('Y-m-d') ,
                        'madeen' => $refpay->paied , 'da2en' => '0' ,
                        'total' => '0','color' => '' , 'order' => 4]);

                    }




                        $g_total_refund_paied +=$refpay->total_paied;
                        $g_refund_paied +=$refpay->paied;
                }

            }

            if($C_inv->presale_order_id === null &&  $C_inv->discount > 0){
                array_push($gho_return_array,
                ['name' => 'خصم على الفاتورة' ,
                'Store' => $C_inv->store->name ,
                'Invoice_data' => $C_inv->id ,
                'date' => $C_inv->date->format('Y-m-d') ,
                'madeen' => '0' , 'da2en' => $C_inv->discount ,
                'total' => '0','color' => '' , 'order' => 2]);

            }

            $refund_invoince = RefundInvoice::where('invoice_id','=' ,$C_inv->id)->get();

            foreach ($refund_invoince as $key => $refd) {
                array_push($gho_return_array,
                    ['name' => 'اجمالى إسترجاع بضاعة' ,
                    'Store' => '--' ,
                    'Invoice_data' => $refd->invoice_id ,
                    'date' => Carbon::parse($refd->date)->format('Y-m-d') ,
                    'madeen' => '0' , 'da2en' => $refd->item_price * $refd->r_amount + $refd->r_tax - $refd->r_discount ,
                    'total' => '0','color' => '', 'order' => 3]);

                $g_total_refund_paiedxx +=$refd->item_price * $refd->r_amount + $refd->r_tax - $refd->r_discount;
                $g_total_refund += ($refd->r_amount * $refd->item_price)+ $refd->r_tax - $refd->r_discount;
            }


        }

        foreach ($client_mad as $key => $mad) {

            array_push($gho_return_array,
            ['name' => 'إستلام نقدية سداد مديونية' ,
            'Store' => '--' ,
            'Invoice_data' => '--' ,
            'date' => $mad->date->format('Y-m-d') ,
            'madeen' => '0' , 'da2en' => $mad->paied ,
            'total' => '0','color' => '' , 'order' => 5]);
            if($mad->discount > 0){
                array_push($gho_return_array,
            ['name' => 'خصم/  إستلام نقدية سداد مديونية' ,
            'Store' => '--' ,
            'Invoice_data' => '--' ,
            'date' => $mad->date->format('Y-m-d') ,
            'madeen' => '0' , 'da2en' => $mad->discount ,
            'total' => '0','color' => '' , 'order' => 6]);
            }


        }
        foreach ($client_sup_sarf as $key => $sarf) {
            array_push($gho_return_array,
            ['name' => 'صرف نقدية' ,
            'Store' => '--' ,
            'Invoice_data' => '--' ,
            'date' => $sarf->date->format('Y-m-d') ,
            'madeen' =>  $sarf->paied , 'da2en' => '0' ,
            'total' => '0','color' => '' , 'order' => 7]);

        }

        foreach ($serviceinv as $key => $servic) {
            array_push($gho_return_array,
            ['name' => 'فاتورةخدمة' ,
            'Store' => '--' ,
            'Invoice_data' => $servic->id ,
            'date' => $servic->date ,
            'madeen' =>  $servic->total , 'da2en' => '0' ,
            'total' => '0','color' => '' , 'order' => 8]);

            array_push($gho_return_array,
            ['name' => 'تحصيل علي فاتورة خدمة' ,
            'Store' => '--' ,
            'Invoice_data' => $servic->id ,
            'date' => $servic->date ,
            'madeen' =>  $servic->totalpaid - $servic->discount +$servic->totaltax , 'da2en' => '0' ,
            'total' => '0','color' => '' , 'order' => 9]);
        }

        foreach ($Buy_invoince as $key => $Buy_invoinc) {

            array_push($gho_return_array,
            ['name' => 'فاتورة شراء من العميل    ' ,
            'Store' => '--' ,
            'Invoice_data' => $Buy_invoinc->buy_transaction->id ,
            'date' => $Buy_invoinc->buy_transaction->date->format('Y-m-d') ,
            'madeen' =>  '0' , 'da2en' => $Buy_invoinc->Egp_total - $Buy_invoinc->Egp_paied ,
            'total' => '0','color' => '' , 'order' => 10]);


        }

        foreach ($sup_mad as $key => $sup_madd) {
            array_push($gho_return_array,
            ['name' => 'إستلام نقدية سداد مديونية ' ,
            'Store' => '--' ,
            'Invoice_data' => '--' ,
            'date' => $sup_madd->date->format('Y-m-d') ,
            'madeen' =>  $sup_madd->paied , 'da2en' => '0',
            'total' => '0','color' => '' , 'order' => 13]);
        }
        // return $gho_return_array;
        $g_actual_price=$Client_invoince->sum('actual_price');
        $g_discount=$Client_invoince->whereNull('presale_order_id')->sum('discount');
        $g_totalpaied=$Client_invoince->sum('paied')+$client_mad->sum('paied')+$client_mad->sum('discount');

        // return [$g_actual_price , ' - ', $g_discount , ' - ',$g_total_refund_paied , ' - ',$g_totalpaied , ' + ',$g_refund_paied];
        // return $g_rassed = $g_actual_price - $g_discount - $g_total_refund_paied - $g_totalpaied + $g_refund_paied ;;
        $g_init_rassed = $client_data[0]->client_raseed;

        $gho_return_array = collect($gho_return_array)
            ->sort(function ($a, $b) {
                // Compare by date
                $dateComparison = strcmp($a['date'], $b['date']);
                if ($dateComparison !== 0) {
                    return $dateComparison;
                }

                // Compare by Invoice_data if dates are equal
                $invoiceComparison = strcmp($a['Invoice_data'], $b['Invoice_data']);
                if ($invoiceComparison !== 0) {
                    return $invoiceComparison;
                }

                // Compare by order if date and Invoice_data are equal
                return $a['order'] <=> $b['order'];
            })
            ->values()
            ->all();
         return ['g_actual_price' => $g_actual_price ,
         'g_discount' => $g_discount ,
         'g_total_refund_paied' => $g_total_refund_paied ,
         'g_totalpaied' => $g_totalpaied ,
         'g_refund_paied' => $g_refund_paied ,
         'g_init_rassed' => $g_init_rassed ,
         'g_total_refund_paiedxx' => $g_total_refund_paiedxx,
         'sup_mad'=>$sup_mad,'client_data' => $client_data,'serviceinv' => $serviceinv, 'Client_invoince' => $Client_invoince, 'client_sup_sarf' => $client_sup_sarf, 'client_mad' => $client_mad,'Buy_invoince'=>$Buy_invoince ,'refund_data'=>$refund_data , 'all_res' => $gho_return_array];
        //  return ['sup_mad'=>$sup_mad,'client_data' => $client_data,'serviceinv' => $serviceinv, 'Client_invoince' => $Client_invoince, 'client_sup_sarf' => $client_sup_sarf, 'client_mad' => $client_mad,'Buy_invoince'=>$Buy_invoince ,'refund_data'=>$refund_data];
    }

    public function hesssap_print($id, $start_date, $end_date)
    {
        $client_data = Client::where('id', '=', $id)->get();

        $sup_mad=[];
        $serviceinv=[];
        // return $id;
        // $this->start_date1 = $_GET['start_date'];
        // $this->end_date1 = $_GET['end_date'];
        // $start_date = $this->start_date1;
        // $end_date = $this->end_date1;
        if ($start_date && $end_date && $start_date != 'null' && $end_date != 'null') {

                $Client_invoince = Invoice::where('client_id', '=', $id)->with('store')->with('refund_invoice_payment')
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
                $client_sup_sarf = SanadSarf::where('client_sup_id', '=', $id)->whereDate('date','>=',$start_date)
                ->whereDate('date','<=',$end_date)
                ->get();
                 $client_mad = InvoiceClientMadyonea::where('client_id', '=', $id)
                 ->whereDate('date','>=',$start_date)
                   ->whereDate('date','<=',$end_date)
                 ->get();  // = Client_pament سندات القيض


                 $serviceinv = ServiceInvoice::where('client_id', '=', $client_data[0]->id)
                 ->whereDate('date','>=',$start_date)
                 ->whereDate('date','<=',$end_date)
                 ->get();

                $Buy_invoince = [];
                if ($client_data[0]->sup_id != null) {
                    $Buy_invoince = OrderSupplier::where('supplier_id', '=', $client_data[0]->sup_id)->doesntHave('all_kit_part_item')
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
                        $sup_mad = SupplierMadyonea::where('supplier_id', '=', $client_data[0]->sup_id)->get();
                }


        }elseif (($start_date && !$end_date) || ($start_date != 'null' && $end_date == 'null')) {

            $Client_invoince = Invoice::where('client_id', '=', $id)->with('store')->with('refund_invoice_payment')
            ->whereDate('date', '>=', $start_date)
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
                $client_sup_sarf = SanadSarf::where('client_sup_id', '=', $id)->whereDate('date', '>=', $start_date)
                ->get();
                $client_mad = InvoiceClientMadyonea::where('client_id', '=', $id)
                ->whereDate('date', '>=', $start_date)->get();  // = Client_pament سندات القيض


                $serviceinv = ServiceInvoice::where('client_id', '=', $client_data[0]->id)
                ->whereDate('date','>=',$start_date)
                ->get();

                $Buy_invoince = [];
                if ($client_data[0]->sup_id != null) {
                    $Buy_invoince = OrderSupplier::where('supplier_id', '=', $client_data[0]->sup_id)->doesntHave('all_kit_part_item')
                    ->with([
                        "buy_transaction"=>function ($query) use ($start_date,$end_date){
                            $query->whereDate('date','>=',  $start_date);
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
                        $sup_mad = SupplierMadyonea::where('supplier_id', '=', $client_data[0]->sup_id)->get();
                }

        } elseif ((!$start_date && $end_date) || ($start_date == 'null' && $end_date != 'null')) {


            $Client_invoince = Invoice::where('client_id', '=', $id)->with('store')->with('refund_invoice_payment')
            ->whereDate('date', '<=', $end_date)
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
            $client_sup_sarf = SanadSarf::where('client_sup_id', '=', $id)->whereDate('date', '<=', $end_date)
            ->get();
                $client_mad = InvoiceClientMadyonea::where('client_id', '=', $id)
                ->whereDate('date', '<=', $end_date)
                ->get();  // = Client_pament سندات القيض


                $serviceinv = ServiceInvoice::where('client_id', '=', $client_data[0]->id)
                ->whereDate('date','<=',$end_date)
                ->get();

                $Buy_invoince = [];
                if ($client_data[0]->sup_id != null) {
                    $Buy_invoince = OrderSupplier::where('supplier_id', '=', $client_data[0]->sup_id)->doesntHave('all_kit_part_item')
                    ->with([
                        "buy_transaction"=>function ($query) use ($start_date,$end_date){
                            $query->whereDate('date','<=',  $end_date);
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
                        $sup_mad = SupplierMadyonea::where('supplier_id', '=', $client_data[0]->sup_id)->get();
                }


        } elseif ((!$start_date && !$end_date) || ($start_date == 'null' && $end_date == 'null')) {
            $Client_invoince = Invoice::where('client_id', '=', $id)->with('store')->with('refund_invoice_payment')
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
            $client_sup_sarf = SanadSarf::where('client_sup_id', '=', $id)
            ->get();
                $client_mad = InvoiceClientMadyonea::where('client_id', '=', $id)
                ->get();  // = Client_pament سندات القيض


                $serviceinv = ServiceInvoice::where('client_id', '=', $client_data[0]->id)
                ->get();

                $Buy_invoince = [];
                if ($client_data[0]->sup_id != null) {
                    $Buy_invoince = OrderSupplier::where('supplier_id', '=', $client_data[0]->sup_id)->doesntHave('all_kit_part_item')
                    ->with('buy_transaction')->with(['currency_type'=>function($q){
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
                        $sup_mad = SupplierMadyonea::where('supplier_id', '=', $client_data[0]->sup_id)->get();
                }



        }
        // return $Buy_invoince;
        //  return ['client_data' => $client_data, 'Client_invoince' => $Client_invoince, 'client_mad' => $client_mad,'Buy_invoince'=>$Buy_invoince ,'refund_data'=>$refund_data ];


        return view('client_statment.hessap_print', compact('sup_mad','serviceinv','client_data', 'Client_invoince','client_sup_sarf', 'client_mad','Buy_invoince','refund_data', 'start_date', 'end_date'));
    }


}
