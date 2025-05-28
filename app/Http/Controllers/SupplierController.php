<?php

namespace App\Http\Controllers;

use App\Models\BankType;
use App\Models\Supplier;
use App\Models\Client;
use App\Models\Supplierbank;
use Illuminate\Http\Request;
use App\Models\BranchTree;
use App\Models\CurrencyType;
use App\Models\Invoice;
use App\Models\InvoiceClientMadyonea;
use App\Models\OrderSupplier;
use App\Models\RefundInvoice;
use App\Models\ServiceInvoice;
use App\Models\Store;
use App\Models\SupplierMadyonea;
use App\Models\SanadSarf;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_suplliers=Supplier::all();
        // return $all_suplliers;
        return view('supplier.index',compact('all_suplliers'));
    }

    public function Selectindex()
    {
        $all_suplliers=Supplier::all();
        return $all_suplliers;

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

         $parentid= BranchTree::where('accountant_number',261)->first()->id;
        $lastchildAccNo = BranchTree::where('parent_id',$parentid)->orderBy('id','DESC')->first()->accountant_number;

        $accId = BranchTree::create([
            'name' =>   ' مورد -'.$request->name,
            'en_name' => $request->name,
            'parent_id' =>  $parentid,
            'accountant_number'=>IntVal($lastchildAccNo)+1
        ])->id;


         if ($request->hasfile('supplier_image')){
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_supplier = $time . '-' . $request->name . '-' . $request->supplier_image->getClientOriginalName();
            $request->supplier_image->move(public_path('supplier_images'), $image_supplier);
            Supplier::create([
                'name'=>$request->name,
                'desc'=>$request->desc,
                'rate'=>$request->rate,
                'address'=>$request->address,
                'email'=>$request->email,
                'tel01'=>$request->tel01,
                'tel02'=>$request->tel02,
                'country'=>$request->country,
                'image'=>$image_supplier,
                'accountant_number'=>IntVal($lastchildAccNo)+1,
                'website'=>$request->website,
                'website_username'=>$request->website_username,
                'website_password'=>$request->website_password

            ]);

        }else{
            Supplier::create([
                'name'=>$request->name,
                'desc'=>$request->desc,
                'rate'=>$request->rate,
                'address'=>$request->address,
                'email'=>$request->email,
                'tel01'=>$request->tel01,
                'tel02'=>$request->tel02,
                'country'=>$request->country,
                'accountant_number'=>IntVal($lastchildAccNo)+1,
                'website'=>$request->website,
                'website_username'=>$request->website_username,
                'website_password'=>$request->website_password

            ]);

        }


        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('supplierindex.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // return $request;
        $supplier=Supplier::where('id',$request->supplier_id)->first();
        // return $supplier;
         if ($request->hasfile('supplier_image')){
            if(isset($supplier->image))
            {
             $image_path = public_path() . '/supplier_images' . '/' . $supplier->image;
             unlink($image_path);

            }
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_supplier = $time . '-' . $request->name . '-' . $request->supplier_image->getClientOriginalName();
            $request->supplier_image->move(public_path('supplier_images'), $image_supplier);
            $supplier->update([
                'name'=>$request->name,
                'desc'=>$request->desc,
                'rate'=>$request->rate,
                'address'=>$request->address,
                'email'=>$request->email,
                'tel01'=>$request->tel01,
                'tel02'=>$request->tel02,
                'country'=>$request->country,
                'image'=>$image_supplier,
                'website'=>$request->website_edit,
                'website_username'=>$request->website_username_edit,
                'website_password'=>$request->website_password_edit

            ]);
        }else{
            $supplier->update([
                'name'=>$request->name,
                'desc'=>$request->desc,
                'rate'=>$request->rate,
                'address'=>$request->address,
                'email'=>$request->email,
                'tel01'=>$request->tel01,
                'tel02'=>$request->tel02,
                'country'=>$request->country,
                'website'=>$request->website_edit,
                'website_username'=>$request->website_username_edit,
                'website_password'=>$request->website_password_edit

            ]);

        }
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('supplierindex.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Request $request)
    {
        // return $request;
        $supplier=Supplier::where('id',$request->supplier_id)->first();
        // return $company;
        if(isset($supplier->image))
        {
            $image_path = public_path() . '/supplier_images' . '/' . $supplier->image;
            unlink($image_path);


        }
        $supplier->delete();
        session()->flash("success", "تم الحذف بنجاح");
        return redirect()->route('supplierindex.index');

    }
    public function show_account_bank($id)
    {
        $supplier=Supplier::where('id',$id)->first();
        $all_account_bank=Supplierbank::where('supplier_id',$id)->with('supplier')->get();
        // return $all_account_bank;
        return view('supplier.show_account_bank',compact('supplier','all_account_bank'));


    }


    public function use_Sup_As_Client(Request $request){
        // return $request->data;

        $supplier_id =$request->data;
        $supllier_data=Supplier::where('id','=',$supplier_id)->first();


        $parentid= BranchTree::where('accountant_number',161)->first()->id;
        $lastchildAccNo = BranchTree::where('parent_id',$parentid)->orderBy('id','DESC')->first()->accountant_number;

        BranchTree::create([
            'name' =>   ' زبون -'.$supllier_data->name,
            'en_name' => $supllier_data->name,
            'parent_id' =>  $parentid,
            'accountant_number'=>IntVal($lastchildAccNo)+1
        ]);

        $client_id=Client::create([
            'name' => $supllier_data->name,
            'address' => $supllier_data->country,
            'tel01' => $supllier_data->tel01,
            'tel02' => $supllier_data->tel02,
            'national_no' => $supllier_data->national_no,
            'notes' => $supllier_data->desc,
            'email1' => $supllier_data->email,
            'email2' => $supllier_data->email2,
            'sup_id' => $supplier_id,
            'client_raseed' => 0,
            'accountant_number'=>IntVal($lastchildAccNo)+1
        ])->id;

        return $client_id;
    }

    public function check_Sup_As_Client(Request $request){
        // return $request->data;

        $supplier_id =$request->data;
        $client=Client::where('sup_id','=',$supplier_id)->get();




        return $client;
    }

     public function use_client_as_suplier(Request $request){
        // return $request->data;

        $client_id =$request->data;
        $client_data=Client::where('id','=',$client_id)->first();

        $parentid= BranchTree::where('accountant_number',261)->first()->id;
        $lastchildAccNo = BranchTree::where('parent_id',$parentid)->orderBy('id','DESC')->first()->accountant_number;

        BranchTree::create([
            'name' =>   ' مورد -'.$client_data->name,
            'en_name' => $client_data->name,
            'parent_id' =>  $parentid,
            'accountant_number'=>IntVal($lastchildAccNo)+1
        ]);
        $sup_id=Supplier::create([

            'name'=> $client_data->name,
            'desc'=>$client_data->notes,
            'address'=>$client_data->address,
            'email'=>$client_data->email1,
            'tel01'=>$client_data->tel01,
            'tel02'=>$client_data->tel02,
            'accountant_number'=>IntVal($lastchildAccNo)+1
        ])->id;

        Client::where('id','=',$client_id)->update(['sup_id'=>$sup_id]);
        return $sup_id;

    }
    public function get_all_sup(){
         return Supplier::all();
    }


    public function supplierView(){
        $supplier = Supplier::with('order_suppliers.replyorders')->get();
        $bank_types=BankType::all();
        $store_safe=Store::where('safe_accountant_number','<>','0')->get();
        foreach ($supplier as $key => $value) {
            $invoiceMad = SupplierMadyonea::where('supplier_id',$value->id)->get();
            $invoiceMadTot = SupplierMadyonea::where('supplier_id',$value->id)->sum('paied');
            $value['paiedArr'] = $invoiceMad;
            $value['Total_paied'] = $invoiceMadTot;

            $Buy_invoince = OrderSupplier::where('supplier_id', '=', $value->id)->doesntHave('all_kit_part_item')->get();
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
            $supAsClient = Client::where('sup_id',$value->id)->get();
            $value['client'] = 0;
            $value['clientRaseed'] = 0;
            $value['clientid'] = 0;
            if(count($supAsClient) > 0){
                $value['client'] = 1;
                $value['clientid'] = $supAsClient[0]->id;
                $servicesMad = ServiceInvoice::where('client_id',$supAsClient[0]->id)->sum('remain');
                $value['servicesMad'] = $servicesMad;
                $clientinvoiceMad = InvoiceClientMadyonea::where('client_id',$supAsClient[0]->id)->sum('paied');
                $value['clientinvoiceMad'] = $clientinvoiceMad;
                $sellInvoiceCount = Invoice::where('client_id',$supAsClient[0]->id)->count();
                $InvoiceClient = Invoice::where('client_id',$supAsClient[0]->id)->with('refund_invoices')->get();
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
                $value['clientRaseed'] = $supAsClient[0]->client_raseed;
            }

        }
        // return $supplier;
        return view('SupplierInvoices',compact('bank_types','store_safe','supplier'));
    }
    public function Supplierinvoice(Supplier $sup_id){
        // return $sup_id;
        $supplier = $sup_id;
        foreach ($supplier->order_suppliers as $key => $inv) {
            $c_id = $inv->currency_id;
            $c_date = $inv->confirmation_date;
            $Ac_all_currency_types=CurrencyType::with(['currencies'=>function($query) use( $c_id , $c_date){
                return $query->where('from','>=',$c_date)->where('to','<=',$c_date)->where('currency_id',$c_id)->orWhere('to','=',null);
            }])->where('id',$c_id)->get();
            $inv['Egp_total'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price ;
            $inv['Egp_paied'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied ;
            $inv['currency_value'] = $Ac_all_currency_types[0]->currencies[0]->value ;
        }
        $bank_types=BankType::all();
        $store_safe=Store::where('safe_accountant_number','<>','0')->get();
        $invoiceMad = SupplierMadyonea::where('supplier_id',$supplier->id)->sum('paied');
        // $invoiceMadAll = SupplierMadyonea::where('supplier_id',$supplier->id)->get();
        $invoiceMadAll =SanadSarf::where('client_sup_id',$supplier->id)->where('type',2)->with('payment')->get();
        $SupplierHesapController = new SupplierHesapController();
        $rassedd = $SupplierHesapController->getRassedAll('supplier',$supplier->id);
        // return $supplier;
        return view('supplier.supplierInv',compact('rassedd','supplier','invoiceMad','bank_types','store_safe','invoiceMadAll'));
    }

}
