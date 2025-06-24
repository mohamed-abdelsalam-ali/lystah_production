<?php

namespace App\Http\Controllers;

use App\Models\BankType;
use App\Models\Qayditem;
use App\Models\Client;
use App\Models\Supplier;
use App\Models\Invoice;
use App\Models\BranchTree;
use App\Models\CurrencyType;
use App\Models\InvoiceClientMadyonea;
use App\Models\OrderSupplier;
use App\Models\RefundInvoice;
use App\Models\ServiceInvoice;
use App\Models\Store;
use App\Models\SupplierMadyonea;
use Illuminate\Http\Request;
use App\Models\SanadSarf;
use Carbon\Carbon;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $all_clients = Client::all();
        $all_supplier = Supplier::all();
        // return $all_clients;
        return view('clients.index', compact('all_clients','all_supplier'));
    }
    public function indexAll()
    {
        return Client::all();
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
        // return $request;
        
        $parentid= BranchTree::where('accountant_number',161)->first()->id;
        $lastchildAccNo = BranchTree::where('parent_id',$parentid)->orderBy('id','DESC')->first()->accountant_number;
        
         $clientbranchId = BranchTree::create([
            'name' =>   ' زبون -'.$request->name,
            'en_name' => $request->name,
            'parent_id' =>  $parentid,
            'accountant_number'=>IntVal($lastchildAccNo)+1
        ])->accountant_number;
        
        if ($request->hasfile('client_img')) {
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_client = $time . '-' . $request->name . '-' . $request->client_img->getClientOriginalName();
            $request->client_img->move(public_path('client_images'), $image_client);
            $client_id= Client::create([
                'name' => $request->name,
                'address' => $request->address,
                'tel01' => $request->tel01,
                'tel02' => $request->tel02,
                'tel03' => $request->tel03,
                'national_no' => $request->national_no,
                'notes' => $request->notes,
                'client_img' => $image_client,
                'email1' => $request->email1,
                'email2' => $request->email2,
                'sup_id' => $request->sup_id,
                'client_raseed' => $request->client_raseed,
                'accountant_number'=>IntVal($lastchildAccNo)+1
            ])->id;
        } else {
            $client_id=Client::create([
                'name' => $request->name,
                'address' => $request->address,
                'tel01' => $request->tel01,
                'tel02' => $request->tel02,
                'tel03' => $request->tel03,
                'national_no' => $request->national_no,
                'notes' => $request->notes,
                'email1' => $request->email1,
                'email2' => $request->email2,
                'sup_id' => $request->sup_id,
                'client_raseed' => $request->client_raseed,
                'accountant_number'=>IntVal($lastchildAccNo)+1
            ])->id;
        }
        $client=Client::where('id', $client_id)->first();
        if ($request->hasfile('segl_togary')) {
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_segal = $time . '-' . $request->name . '-' . $request->segl_togary->getClientOriginalName();
            $request->segl_togary->move(public_path('segl_togary_images'), $image_segal);
            $client->update([
                'segl_togary' => $image_segal,
            ]);

        }
        if ($request->hasfile('betaa_darebia')) {
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_betaa_darebia = $time . '-' . $request->name . '-' . $request->betaa_darebia->getClientOriginalName();
            $request->betaa_darebia->move(public_path('betaa_darebia_images'), $image_betaa_darebia);
            $client->update([
                'betaa_darebia' => $image_betaa_darebia,
            ]);
        }

        if($request->client_raseed > 0){

            // Qayditem::where('branchid',$clientbranchId)->where('topic','رصيد افتتاحي')->get();
            $quaditems = [];
            $automaicQayd = new QaydController();
            array_push($quaditems, (object) ['acountant_id' => 213 , 'madin' => floatval($request->client_raseed), 'dayin' => 0]); //
            array_push($quaditems, (object) ['acountant_id' => $clientbranchId, 'madin' => 0, 'dayin' => floatval($request->client_raseed) ]); //
            $date = Carbon::now();
            $type = null;
            $notes = 'رصيد افتتاحي' ;
            $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
        }else{
            $quaditems = [];
            $automaicQayd = new QaydController();
            array_push($quaditems, (object) ['acountant_id' => $clientbranchId , 'madin' => floatval($request->client_raseed)*-1 , 'dayin' => 0]); //
            array_push($quaditems, (object) ['acountant_id' => 213, 'madin' => 0, 'dayin' => floatval($request->client_raseed)*-1 ]); //
            $date = Carbon::now();
            $type = null;
            $notes = 'رصيد افتتاحي' ;
            $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
        }
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('client.index');
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
    public function update(Request $request)
    {
        // return $request;
        $client=Client::where('id',$request->client_id)->first();
        if ($request->hasfile('client_img')){
            if(isset($client->client_img))
            {
             $image_path = public_path() . '/client_images' . '/' . $client->client_img;
             unlink($image_path);
            }
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_client = $time . '-' . $request->name . '-' . $request->client_img->getClientOriginalName();
            $request->client_img->move(public_path('client_images'), $image_client);
            $client->update([
                'name' => $request->name,
                'address' => $request->address,
                'tel01' => $request->tel01,
                'tel02' => $request->tel02,
                'tel03' => $request->tel03,
                'national_no' => $request->national_no,
                'notes' => $request->notes,
                'client_img' => $image_client,
                'email1' => $request->email1,
                'email2' => $request->email2,
                'client_raseed'=> $request->client_raseed,
            ]);
        }else{
            $client->update([
                'name' => $request->name,
                'address' => $request->address,
                'tel01' => $request->tel01,
                'tel02' => $request->tel02,
                'tel03' => $request->tel03,
                'national_no' => $request->national_no,
                'notes' => $request->notes,
                'email1' => $request->email1,
                'email2' => $request->email2,
                'client_raseed'=> $request->client_raseed,
            ]);

        }
        if ($request->hasfile('segl_togary')){
            if(isset($client->segl_togary))
            {
             $image_path = public_path() . '/segl_togary_images' . '/' . $client->segl_togary;
             unlink($image_path);
            }
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_segal = $time . '-' . $request->name . '-' . $request->segl_togary->getClientOriginalName();
            $request->segl_togary->move(public_path('segl_togary_images'), $image_segal);
            $client->update([
                'segl_togary' => $image_segal,
            ]);
        }
        if ($request->hasfile('betaa_darebia')){
            if(isset($client->betaa_darebia))
            {
             $image_path = public_path() . '/betaa_darebia_images' . '/' . $client->betaa_darebia;
             unlink($image_path);
            }
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_betaa_darebia = $time . '-' . $request->name . '-' . $request->betaa_darebia->getClientOriginalName();
            $request->betaa_darebia->move(public_path('betaa_darebia_images'), $image_betaa_darebia);
            $client->update([
                'betaa_darebia' => $image_betaa_darebia,
            ]);
        }
        
         $branchx = BranchTree::where('accountant_number',$client->accountant_number)->first();
        $qayds =Qayditem::where('branchid',$branchx->id)->where('topic','LIKE', '%رصيد افتتاحي%')->get();

        foreach ($qayds as $key => $qaItems) {
            Qayditem::where('qaydid',$qaItems->qaydid)->delete();
        }
        if($request->client_raseed > 0){
            $quaditems = [];
            $automaicQayd = new QaydController();
            array_push($quaditems, (object) ['acountant_id' =>  213, 'madin' => floatval($request->client_raseed), 'dayin' => 0]); //
            array_push($quaditems, (object) ['acountant_id' => $client->accountant_number, 'madin' => 0, 'dayin' => floatval($request->client_raseed) ]); //
            $date = Carbon::now();
            $type = null;
            $notes = 'رصيد افتتاحي' ;
            $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
        }else{
            $quaditems = [];
            $automaicQayd = new QaydController();
            array_push($quaditems, (object) ['acountant_id' =>  $client->accountant_number, 'madin' => floatval($request->client_raseed)*-1 , 'dayin' => 0]); //
            array_push($quaditems, (object) ['acountant_id' => 213, 'madin' => 0, 'dayin' => floatval($request->client_raseed)*-1 ]); //
            $date = Carbon::now();
            $type = null;
            $notes = 'رصيد افتتاحي' ;
            $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
        }
        
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('client.index');
    }

    /**
     * Remove the specified resource from storage.78169
     */
    public function destroy( Request $request)
    {
        $client=Client::where('id',$request->client_id)->first();
        // return $client;
        $invoice=Invoice::where('client_id',$request->client_id)->first();
        if(isset($invoice))
        {
            session()->flash("success", "لا يمكن حذف هذا العميل  ");
            return redirect()->route('client.index');

        }else{
        if(isset($client->client_img))
        {
         $image_path = public_path() . '/client_images' . '/' . $client->client_img;
         unlink($image_path);

        }
        if(isset($client->segl_togary))
        {
         $image_path = public_path() . '/segl_togary_images' . '/' . $client->segl_togary;
         unlink($image_path);
        }
        if(isset($client->betaa_darebia))
        {
             $image_path = public_path() . '/betaa_darebia_images' . '/' . $client->betaa_darebia;
             unlink($image_path);
        }
        $client->delete();
        session()->flash("success", "تم الحذف بنجاح");
        return redirect()->route('client.index');

        }


    }
    
    
    public function clientview(){
        $bank_types=BankType::all();
        $store_safe=Store::where('safe_accountant_number','<>','0')->get();
        $clients = Client::get();

        foreach ($clients as $key => $value) {
            // $supAsClient = Client::where('sup_id',$value->id)->get();

            // $value['clientdata'] = $value;
            $value['clientid'] = $value->id;
            $servicesMad = ServiceInvoice::where('client_id',$value->id)->sum('remain');
            $value['servicesMad'] = $servicesMad;
            $clientinvoiceMad = InvoiceClientMadyonea::where('client_id',$value->id)->sum('paied');
            $value['clientinvoiceMad'] = $clientinvoiceMad;
            $sellInvoiceCount = Invoice::where('client_id',$value->id)->count();
            $InvoiceClient = Invoice::where('client_id',$value->id)->with('refund_invoices')->get();
            $refundInvTotal = 0;
            foreach ($InvoiceClient as $key => $valuex) {
                $RefundInvoices =  RefundInvoice::where('invoice_id',$valuex->id)->get();
                foreach ($RefundInvoices as $key => $RefundInvoice) {
                    $refundInvTotal += ($RefundInvoice->r_amount * $RefundInvoice->item_price)+ $RefundInvoice->r_tax;
                }
            }

            $value['refundInvTotal'] = $refundInvTotal;
            $value['InvoiceClient'] = $InvoiceClient;
            $value['sellInvoiceCount'] = $sellInvoiceCount;
            $value['clientRaseed'] = $value->client_raseed;
            $value['order_suppliers']=[];
            
             $rassedAll = new SupplierHesapController();
           $clientData=$rassedAll->getRassedAll('client',$value->id);
            $value['egmal'] = $clientData['rassed'];
            $value['message'] = $clientData['message'];
            
            if($value->sup_id != null){
                $supplier = Supplier::where('id' , '=' ,$value->sup_id )->with('order_suppliers.replyorders')->get();
                $invoiceMad = SupplierMadyonea::where('supplier_id',$value->sup_id)->get();
                $invoiceMadTot = SupplierMadyonea::where('supplier_id',$value->sup_id)->sum('paied');
                $value['paiedArr'] = $invoiceMad;
                $value['Total_paied'] = $invoiceMadTot;

                $Buy_invoince = OrderSupplier::where('supplier_id', '=', $value->sup_id)->doesntHave('all_kit_part_item')->get();
                $actual_price =0;
                $paied_price =0;
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

                $value['Suuplier_actual_price'] = $actual_price;
                $value['Suuplier_Total_paied'] = $paied_price;
                $value['order_suppliers'] = $Buy_invoince;
                
                $rassedAll = new SupplierHesapController();
                $clientData=$rassedAll->getRassedAll('all',$value->sup_id);
                 $value['egmal'] = $clientData['rassed'];
                 $value['message'] = $clientData['message'];

            }
            // return $value;
        }


        return view('clientsInvoicesAll',compact('bank_types','store_safe','clients'));





        return $clients;
        return view('SupplierInvoices',compact('bank_types','store_safe','supplier'));
    }
    function getclientinvoice( $clientId){
        $client = Client::where('id',$clientId)->first();
        $store = store::where('id',8)->first();
        $as_sup_madunia = 0;
        $supplierData = 0;
        $supinvoiceMad = 0;
        $rassedd = [];
        // return $client->sup_id;
        if ($client->sup_id != null) {
            $as_sup_madunia = Supplier::where('id', $client->sup_id)->first()->raseed;
            $supplier = Supplier::where('id', $client->sup_id)->first();
            foreach ($supplier->order_suppliers as $key => $inv) {
                $c_id = $inv->currency_id;
                $c_date = $inv->confirmation_date;
                $Ac_all_currency_types = CurrencyType::with([
                    'currencies' => function ($query) use ($c_id, $c_date) {
                        return $query->where('from', '>=', $c_date)->where('to', '<=', $c_date)->where('currency_id', $c_id)->orWhere('to', '=', null);
                    },
                ])
                    ->where('id', $c_id)
                    ->get();
                $inv['Egp_total'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price;
                $inv['Egp_paied'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied;
                $inv['currency_value'] = $Ac_all_currency_types[0]->currencies[0]->value;
            }
            $supplierData = $supplier;
            $supinvoiceMad = SupplierMadyonea::where('supplier_id', $supplier->id)->sum('paied');

            $SupplierHesapController = new SupplierHesapController();
            $rassedd = $SupplierHesapController->getRassedAll('all', $client->sup_id);
        } else {
            $SupplierHesapController = new SupplierHesapController();
             $rassedd = $SupplierHesapController->getRassedAll('client', $client->id);
        }
        $invoices = Invoice::where('client_id', $client->id)
            ->with('store')->with('refund_invoices')->with('refund_invoice_payment')
            ->get();
        $invoiceMad = InvoiceClientMadyonea::with('payment')
            ->where('client_id', $client->id)
            ->get();

        $servicesMad = ServiceInvoice::where('client_id', $client->id)->sum('remain');
        // return $supplierData;
        $bank_types = BankType::all();
        $store_safe = Store::where('safe_accountant_number', '<>', '0')->get();
        $store_data = Store::where('id', 8)->get();

        // return $rassedd ;

        $sanadSarf = SanadSarf::where('client_sup_id',$client->id)->where('type',1)->get();

        return view('clientInvoices', compact('sanadSarf','rassedd', 'bank_types', 'store_data', 'store_safe', 'store', 'client', 'invoices', 'invoiceMad', 'servicesMad', 'as_sup_madunia', 'supplierData', 'supinvoiceMad'));
    
        // return view('clientInvoices',compact('store_data','rassedd','client','invoiceMad','bank_types','store_safe','invoiceMadAll'));
    }
}
