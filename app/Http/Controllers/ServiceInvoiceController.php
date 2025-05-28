<?php

namespace App\Http\Controllers;

use App\Models\ServiceInvoice;
use App\Models\ServiceInvoiceItem;
use App\Models\ServiceTax;
use App\Models\Tractor;
use App\Models\Clark;
use App\Models\Equip;
use App\Models\AllEquip;
use App\Models\AllClark;
use App\Models\AllTractor;
use App\Models\Store;
use App\Models\StoresLog;
use App\Models\Servicetype;
use App\Models\Serviceoption;
use App\Models\Client;
use Carbon\Carbon;
use App\Models\MoneySafe;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\QaydController;

use Illuminate\Http\Request;

class ServiceInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $serviceInvoice = ServiceInvoice::with('client')
        ->with('store')
        ->with('serviceoption')
        ->with('servicetype')
        ->get()->sortByDesc('id');
        return view('servicesInvoiceIndex',compact('serviceInvoice'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("/services");

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request;
        $quaditems = [];
        $automaicQayd = new QaydController();
        $invoiceac = 0;
        $taxac = 0;
        $binvoiceac =0;
        
        $store_id = $request->store_id;
        $store_data=Store::where('id',$store_id)->first();
        
        $servicetypeid = Servicetype::where('type' , $request->serviceType)->first('id');
        $serviceoptionid = Serviceoption::where('optionhtml' , $request->serviceOption)->first('id');
        $client_data = Client::where('id' , $request->client_id)->first();
        ServiceInvoice::create(['date' => $request->date ,'serviceoptionid'  => $serviceoptionid->id , 'servicetypeid' => $servicetypeid->id , 'itemid' => $request->item_id ,'motornumber' => $request->plate ,  'total' => $request->total , 'totalpaid' => $request->totalPaid , 'discount' => $request->serviceDiscount , 'totaltax' => $request->totaltax , 'remain' => $request->remain , 'totalbefortax'=> $request->totalbefortax ,'store_id' => $store_id , 'client_id'=> $request->client_id]);
        $new_raseed = isset($client_data->client_raseed) ? $client_data->client_raseed : 0;
        if(isset($client_data->client_raseed)){
            $new_raseed += $request->remain; 
            $client_data->update(['client_raseed' => $new_raseed]);
        }
        $serviceInvoiceId = ServiceInvoice::max('id');
        
        if(isset($request->addedServices)){
            for  ($i=0; $i < count($request->addedServices); $i++) { 
                ServiceInvoiceItem::create(['serviceid' =>$request->addedServices[$i] , 'price' =>$request->servicePrice[$i] , 'serviceinviceid' => $serviceInvoiceId]);
            }
        }
        if(isset($request->addedServiceTaxes)){
            for  ($i=0; $i < count($request->addedServiceTaxes); $i++) { 
                ServiceTax::create(['tax_id' =>$request->addedServiceTaxes[$i] , 'service_invoice_id' => $serviceInvoiceId]);
                
            }
        }
        
        if ($request->serviceType == "Clark"){
            Clark::where('id' , $request->item_id)->update(['serivcedate' => Carbon::today()]);
        }else if($request->serviceType == "Tractor"){
            Tractor::where('id' , $request->item_id)->update(['serivcedate' => Carbon::today()]);
        }
        if($request->remain > 0){
                // أجل
                array_push ( $quaditems , (object) [ 'acountant_id'=> $store_data->safe_accountant_number  , 'madin'=> $request->totalPaid , 'dayin'=>0 ] ); // الصندوق 
                
                array_push ( $quaditems , (object) [ 'acountant_id'=> 44 , 'madin'=> 0 , 'dayin'=> $request->total ] ); // الخدمات المباعة 
                
        }else{
            // كاش
            array_push ( $quaditems , (object) [ 'acountant_id'=> $store_data->safe_accountant_number  , 'madin'=> $request->totalPaid , 'dayin'=>0 ] ); // الصندوق 
            
            array_push ( $quaditems , (object) [ 'acountant_id'=> 44 , 'madin'=> 0 , 'dayin'=> $request->total ] ); // الخدمات المباعة 
        }
        if($request->client_id == null){
            array_push ( $quaditems , (object) [ 'acountant_id'=> 331 , 'madin'=> $request->remain , 'dayin'=> 0 ] ); // الزبون 
        }else{
            array_push ( $quaditems , (object) [ 'acountant_id'=> $client_data->accountant_number , 'madin'=> $request->remain , 'dayin'=> 0 ] ); // الزبون 
        }

        $date =Carbon::now();
        $type = null;
        $notes='فاتورة خدمات '.$serviceInvoiceId.'-'.$request->serviceOption;
        $automaicQayd->AutomaticQayd($quaditems,$date,$type,$notes);
        
        
         if ($request->totalPaid > 0) {
            $total = MoneySafe::where('store_id', $request->store_id)->orderBy('id', 'desc')->first();
            if ($request->serviceOption == "external") {
                if (isset($total)) {
                    MoneySafe::create([
                        'notes' => 'فاتورة خدمات للعميل'.' '.$client_data->name,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->totalPaid,
                        'total' => $total->total + $request->totalPaid,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => $request->store_id,
                        'note_id'=>15,

                    ]);
                } else {
                    MoneySafe::create([
                        'notes' => 'فاتورة خدمات للعميل'.' '.$client_data->name,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->totalPaid,
                        'total' => $request->totalPaid,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => $request->store_id,
                        'note_id'=>15,
                    ]);
                }
               
               
                
                return redirect(route("service.print",  $serviceInvoiceId));

            } else {
                if (isset($total)) {
                    if ($total->total > $request->totalPaid) {
                      MoneySafe::create([
                            'notes' => 'فاتورة خدمات داخلي لصالح'.' '.$store_data->name,
                            'date' => date('Y-m-d'),
                            'flag' => 1,
                            'money' => $request->totalPaid,
                            'total' => $total->total - $request->totalPaid,
                            'type_money' => '1',
                            'user_id' => Auth::user()->id,
                            'store_id' => $request->store_id,
                            'note_id'=>15,

                        ]);

                        session()->flash("success", "تم صرف المبلغ  بنجاح");
                        return redirect(route("service.print",  $serviceInvoiceId));
                    } else {
                        session()->flash("success", "  المبلغ غير كافي في الخزنة");
                        return redirect()->back();
                    }
                } else {

                    session()->flash("success", "   لا يوجد رصيد في الخزنة   ");
                    return redirect()->back();
                }
            }

          
                
        }
   
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceInvoice $serviceInvoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceInvoice $serviceInvoice)
    {
        $invoice = ServiceInvoice::where('id' , $serviceInvoice->id)
        ->with('client')
        ->with('servicetype')
        ->with('serviceoption')
        ->with('service_taxes.tax')
        ->with('service_invoice_items.service')
        ->get();
        // return $invoice;
        return view("/serviceInvoiceEdit",compact('serviceInvoice'));
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceInvoice $serviceInvoice)
    {
       $currnet_paid = $serviceInvoice->totalpaid;
        $new_paid = $request->totalPaid;
        $added_money = $new_paid - $currnet_paid;

        $store_id = $request->store_id;
        $store_data = Store::where('id', $store_id)->first();
        if(isset($request->addedServices)){
            ServiceInvoiceItem::where('serviceinviceid' , $serviceInvoice->id)->delete();
            for  ($i=0; $i < count($request->addedServices); $i++) { 
                ServiceInvoiceItem::create(['serviceid' =>$request->addedServices[$i] , 'price' =>$request->servicePrice[$i] , 'serviceinviceid' => $serviceInvoice->id]);
            }
        }
        if(isset($request->addedServiceTaxes)){
            ServiceTax::where('service_invoice_id' , $serviceInvoice->id)->delete();
            for  ($i=0; $i < count($request->addedServiceTaxes); $i++) { 
                ServiceTax::create(['tax_id' =>$request->addedServiceTaxes[$i] , 'service_invoice_id' => $serviceInvoice->id]);
            }
        }
        if (isset($request->item_id)) {
            if ($request->serviceType == "Clark"){
                Clark::where('id' , $request->item_id)->update(['serivcedate' => Carbon::today()]);
            }else if($request->serviceType == "Tractor"){
                Tractor::where('id' , $request->item_id)->update(['serivcedate' => Carbon::today()]);
            }
        }
        $servicetypeid = Servicetype::where('type' , $request->serviceType)->first('id');
        $serviceoptionid = Serviceoption::where('optionhtml' , $request->serviceOption)->first('id');
        $client_data = Client::where('id' , $request->client_id)->first();
        ServiceInvoice::where('id' , $serviceInvoice->id)->update(['date' => $request->date ,'serviceoptionid'  => $serviceoptionid->id , 'servicetypeid' => $servicetypeid->id , 'itemid' => $request->item_id ,'motornumber' => $request->plate ,  'total' => $request->total , 'totalpaid' => $request->totalPaid , 'discount' => $request->serviceDiscount , 'totaltax' => $request->totaltax , 'remain' => $request->remain , 'totalbefortax'=> $request->totalbefortax ,'store_id' => $store_id , 'client_id'=> $request->client_id]);
        $new_raseed = isset($client_data->client_raseed) ? $client_data->client_raseed : 0;
        if(isset($client_data->client_raseed)){
            $new_raseed += $request->remain; 
            $client_data->update(['client_raseed' => $new_raseed]);
        }
        
        if ($request->totalPaid > 0) {
            $total = MoneySafe::where('store_id', $request->store_id)->orderBy('id', 'desc')->first();
            if ($request->serviceOption == "external" && $added_money > 0) {
                if (isset($total)) {
                    MoneySafe::create([
                        'notes' => ' تعديل فاتورة خدمات للعميل' . ' ' . $client_data->name,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $added_money,
                        'total' => $total->total + $added_money,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => $request->store_id,
                        'note_id' => 15,

                    ]);
                } else {
                    MoneySafe::create([
                        'notes' => ' تعديل فاتورة خدمات للعميل' . ' ' . $client_data->name,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $added_money,
                        'total' => $added_money,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => $request->store_id,
                        'note_id' => 15,
                    ]);
                }
                return redirect(route("service.print",  $serviceInvoice->id));
            } else {
                if (isset($total)) {
                    if ($total->total > ($request->added_money * -1)) {
                        MoneySafe::create([
                            'notes' => 'فاتورة خدمات داخلي لصالح' . ' ' . $store_data->name,
                            'date' => date('Y-m-d'),
                            'flag' => 1,
                            'money' => $added_money * -1,
                            'total' => $total->total -  $added_money * -1,
                            'type_money' => '1',
                            'user_id' => Auth::user()->id,
                            'store_id' => $request->store_id,
                            'note_id' => 15,

                        ]);

                        session()->flash("success", "تم صرف المبلغ  بنجاح");
                        return redirect(route("service.print",  $serviceInvoice->id));
                    } else {
                        session()->flash("success", "  المبلغ غير كافي في الخزنة");
                        return redirect()->back();
                    }
                } else {

                    session()->flash("success", "   لا يوجد رصيد في الخزنة   ");
                    return redirect()->back();
                }
            }
        }

        
        
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceInvoice $serviceInvoice)
    {
        $total = MoneySafe::where('store_id', $serviceInvoice->store_id)->orderBy('id', 'desc')->first();
        $client_data = Client::where('id', $serviceInvoice->client_id)->first();

        if (isset($total)) {
            if ($total->total >= $serviceInvoice->totalpaid) {
                MoneySafe::create([
                    'notes' => 'استرجاع فاتورة خدمات داخلي لصالح' . ' ' . $client_data->name,
                    'date' => date('Y-m-d'),
                    'flag' => 1,
                    'money' => $serviceInvoice->totalpaid,
                    'total' => $total->total -  $serviceInvoice->totalpaid,
                    'type_money' => '1',
                    'user_id' => Auth::user()->id,
                    'store_id' => $serviceInvoice->store_id,
                    'note_id' => 16,

                ]);
                ServiceInvoiceItem::where('serviceinviceid', $serviceInvoice->id)->delete();
                ServiceTax::where('service_invoice_id', $serviceInvoice->id)->delete();
                $serviceInvoice = ServiceInvoice::where('id', $serviceInvoice->id)->delete();

                session()->flash("success", "تم حذف الفاتورة بنجاح");

                return redirect()->route('serviceInvoice.index');
            } else {
                session()->flash("success", "  المبلغ غير كافي في الخزنة");
                return redirect()->back();
            }
        } else {

            session()->flash("success", "   لا يوجد رصيد في الخزنة   ");
            return redirect()->back();
        }

    }

    public function printservice($service_id)
    {
        $service =  ServiceInvoice::where('id' ,$service_id)
        ->with('serviceoption')
        ->with('servicetype')
        ->with(['service_invoice_items'=>function($query){
            return $query->with(['service'=>function($query1){
                return $query1->get();
            }])->get();
        }])
        ->with('service_taxes')
        ->with(['service_taxes'=>function($query){
            return $query->with(['tax'=>function($query1){
                return $query1->get();
            }])->get();
        }])
        ->with('store')
        ->with('client')
        ->first();
        if($service->servicetypeid == 4){
            $all_tractor = AllTractor::where('part_id' , $service->itemid)->get();
            $item = Tractor::where('id' , $service->itemid)
            ->with(['all_tractors'=>function($query){
                return $query->with(['order_supplier'=>function($query1){
                    return $query1->with(['replyorders'=>function($query2){
                        return $query2->get();
                }])->get();
                }]);
            }])
            ->with(['series'=> function($query8){
                return $query8->with(['model'=>function($query9){
                    return $query9->with('brand')->with('brand_type')->get();
                }])->get();
            }])  
            ->first();
            
        }elseif($service->servicetypeid == 5){
            $all_equip = AllEquip::where('part_id' , $service->itemid)->get();
            $item = Equip::where('id' , $service->itemid)
            ->with(['all_equips'=>function($query){
                return $query->with(['order_supplier'=>function($query1){
                    return $query1->with(['replyorders'=>function($query2){
                        return $query2->get();
                }])->get();
                }]);
            }])
            ->with(['series'=> function($query8){
                return $query8->with(['model'=>function($query9){
                    return $query9->with('brand')->with('brand_type')->get();
                }])->get();
            }])
            ->first();
            
        }elseif($service->servicetypeid == 6){
        $all_clark = AllClark::where('part_id' , $service->itemid)->get();
            $item = Clark::where('id' , $service->itemid)
            ->with(['all_clarks'=>function($query){
                return $query->with(['order_supplier'=>function($query1){
                    return $query1->with(['replyorders'=>function($query2){
                        return $query2->get();
                }])->get();
                }]);
            }])
            ->with(['series'=> function($query8){
                return $query8->with(['model'=>function($query9){
                    return $query9->with('brand')->with('brand_type')->get();
                }])->get();
            }])
            ->first();
        }
        // return $service;    
        return view("printService",compact("service" , "item" ));

        
    }
}