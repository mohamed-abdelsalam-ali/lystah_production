<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Serviceoption;
use App\Models\Servicetype;
use App\Models\ServiceInvoice;
use App\Models\Client;
use App\Models\ServiceInvoiceItem;
use App\Models\ServiceTax;
use App\Models\Tractor;
use App\Models\Clark;
use App\Models\Equip;
use App\Models\AllEquip;
use App\Models\AllClark;
use App\Models\AllKit;
use App\Models\AllPart;
use App\Models\AllTractor;
use App\Models\AllWheel;
use App\Models\CurrencyType;
use App\Models\Kit;
use App\Models\OrderPartService;
use App\Models\OrderPartServiceDeliver;
use App\Models\OrderPartServiceItem;
use App\Models\OrderPartServiceItemsDeliver;
use App\Models\Part;
use App\Models\PricingType;
use App\Models\SalePricing;
use App\Models\Store;
use App\Models\StoreSection;
use App\Models\StoresLog;
use App\Models\Wheel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function servicesIndex(){
        return Service::all();
     }
    public function index()
    {
        $all_services = Service::all();
        return view('service.index',compact('all_services'));
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
        if ($request->hasfile('service_img')){
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_service= $time . '-' . $request->name . '-' . $request->service_img->getClientOriginalName();
            $request->service_img->move(public_path('service_images'), $image_service);
            Service::create([
                'name'=>$request->name,
                'service_img'=>$image_service,

            ]);


        }else{
            Service::create([
                'name'=>$request->name,

            ]);

        }
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->back();
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
        $service=Service::where('id',$request->service_id)->first();
        if ($request->hasfile('service_img')){
            if(isset($service->service_img))
            {
             $image_path = public_path() . '/service_images' . '/' . $service->service_img;
             unlink($image_path);

            }
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_service= $time . '-' . $request->name . '-' . $request->service_img->getClientOriginalName();
            $request->service_img->move(public_path('service_images'), $image_service);
            $service->update([
                'name'=>$request->name,
                'service_img'=>$image_service,

            ]);
        }else{
            $service->update([
                'name'=>$request->name,

            ]);

        }
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('service.index');

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $service=Service::where('id',$request->service_id)->first();
        if(isset($service->service_img))
            {
             $image_path = public_path() . '/service_images' . '/' . $service->service_img;
             unlink($image_path);

            }
        $service->delete();
        session()->flash("success", "تم الحذف بنجاح");
        return redirect()->route('service.index');
    }


    public function getItem(Request $request)
    {
        $store_id =$request->store_id;
         $store_name = Store::where('id' , $store_id)->first('table_name');

        if($request->servicType == "equip"){

          $store_model=ucfirst($store_name->table_name);
            if($store_name=="Damaged_parts"){
                $store_model="damagedPart";
            }
            $store_table ='App\\Models\\'.$store_model;
            return $store_table::where('type_id' , 5)->with(['stores_log'=>function($query){
                return $query->with(['all_equips'=>function($query){
                    return $query->with('equip')->get();
            }])->get();
        }])->get();

        }elseif($request->servicType == "tractor"){

            $store_model=ucfirst($store_name->table_name);
            if($store_name=="Damaged_parts"){
                $store_model="damagedPart";
            }
            $store_table ='App\\Models\\'.$store_model;
            return $store_table::where('type_id' , 3)->with(['stores_log'=>function($query){
                return $query->with(['all_tractors'=>function($query1){
                    return $query1->with('tractor')->get();
            }])->get();
        }])->get();
        }elseif($request->servicType == "clark"){
            $store_model=ucfirst($store_name->table_name);
            if($store_name=="Damaged_parts"){
                $store_model="damagedPart";
            }
            $store_table ='App\\Models\\'.$store_model;
            return $store_table::where('type_id' , 4)->with(['stores_log'=>function($query){
                return $query->with(['all_clarks'=>function($query1){
                    return $query1->with('clark')->get();
            }])->get();
        }])->get();

        }

    }

    public function tawreedServices(){
        $stores= Store::where('table_name','<>','damaged_parts')->get();
        return view('tawreedServices',compact('stores'));
    }

    public function save_tawreedServices(Request $request){
        // return $request;

        DB::beginTransaction();
        try {
            $itemType = 0;
            if($request->serviceType === 'Tractor'){
                $itemType = 3;
            }elseif($request->serviceType === 'Equipment'){
                $itemType = 5;
            }elseif($request->serviceType === 'Clark'){
                $itemType = 4;
            }else{
                DB::rollback();
                session()->flash("error");
                return redirect()->back();
            }

            $lastId = OrderPartService::create([
                'equips_id' => $request->optionSelect,
                'type_id' => $itemType,
                'store_id' => $request->store,
                'user_id' => Auth::user()->id,
                'date' => Carbon::now(),
                'notes' => '',
                'flag' => 0
            ])->id;


            if(!$request->parts){
                DB::rollback();
                session()->flash("error");
                return redirect()->back();
            }
            for ($i=0; $i < count($request->parts) ; $i++) {

                OrderPartServiceItem::create([
                    'order_part_service_id' => $lastId,
                    'part_id' => $request->parts[$i],
                    'amount' => $request->amount[$i],
                    'source_id' => $request->source[$i],
                    'status_id' => $request->status[$i],
                    'quality_id' => $request->quality[$i],
                    'type_id' => $request->types[$i],
                ]);
            }

            DB::commit();
            session()->flash("success", "success");
            return redirect()->back();

        } catch (\Exception $e) {

            DB::rollback();
            session()->flash("error", $e->getMessage());
            return false;
        }
    }

    public function getCountServicesParts($store_id){

        return OrderPartService::where('store_id',$store_id)->where('flag',0)->count();
    }

    public function tawreedServicesInbox($store_id){

        $store_data = Store::where('id',$store_id)->get();
        $data = OrderPartService::where('store_id',$store_id)->where('flag',0)->with('store')->get();
        foreach ($data as $key => $item) {
           if($item->type_id == 3){
                $item['type'] = 'جرار';
                $item['itemData'] = Tractor::where('id',$item->equips_id)->first();
           }elseif($item->type_id == 4){
                $item['type'] = 'كلارك';
                $item['itemData'] = Clark::where('id',$item->equips_id)->first();
           }elseif($item->type_id == 4){
                $item['type'] = 'معدة';
                $item['itemData'] = Equip::where('id',$item->equips_id)->first();
           }

        }
        return view('tawreedServicesInbox',compact('store_data','data'));
    }

    public function orderpartserviceShow($id,$store_id){
        $store_data = Store::where('id',$store_id)->get();
        $sale_type = PricingType::all();
         $data = OrderPartService::where('id',$id)->with('items.source')->with('items.status')->with('items.part_quality')->with('items.type')->with('store')->first();

            if($data->type_id == 3){
                 $data['type'] = 'جرار';
                 $data['itemData'] = Tractor::where('id',$data->equips_id)->first();
            }elseif($data->type_id == 4){
                 $data['type'] = 'كلارك';
                 $data['itemData'] = Clark::where('id',$data->equips_id)->first();
            }elseif($data->type_id == 4){
                 $data['type'] = 'معدة';
                 $data['itemData'] = Equip::where('id',$data->equips_id)->first();
            }

            if(count($data->items) > 0){
                foreach ($data->items as $key => $item) {
                   if($item->type_id == 1){
                    $item['data'] = Part::where('id',$item->part_id)->first();

                    $item['avialable']= AllPart::where('part_id',$item->part_id)
                    ->where('source_id',$item->source_id)
                    ->where('status_id',$item->status_id)->where('quality_id',$item->quality_id)->sum('remain_amount');

                   }elseif($item->type_id == 6){
                    $item['data'] = Kit::where('id',$item->part_id)->first();

                    $item['avialable']= AllKit::where('part_id',$item->part_id)
                    ->where('source_id',$item->source_id)
                    ->where('status_id',$item->status_id)->where('quality_id',$item->quality_id)->sum('remain_amount');
                   }elseif($item->type_id == 2){
                    $item['data'] = Wheel::where('id',$item->part_id)->first();

                    $item['avialable']= AllWheel::where('part_id',$item->part_id)
                    ->where('source_id',$item->source_id)
                    ->where('status_id',$item->status_id)->where('quality_id',$item->quality_id)->sum('remain_amount');
                   }

                   $PartInStoresCount = new POSController();
                   $item['PartInStoresCount'] = $PartInStoresCount->PartInStoresCount($item->part_id,$item->source_id,$item->status_id,$item->quality_id,$item->type_id);
                }
            }
// return $data;
        return view('orderpartserviceShow',compact('store_data','data','sale_type'));
    }
    public function service_part_delever(Request $request){
        // return $request;
        DB::beginTransaction();
        try {
             $store_data= Store::where('id',$request->equips_store_id)->first();
              $deliver_id=  OrderPartServiceDeliver::create([
                    'equips_id'=>$request->equips_id,
                    'type_id'=>$request->equips_type_id,
                    'store_id'=>$request->equips_store_id,
                    'user_id' => Auth::user()->id,
                    'date' => Carbon::now(),
                    'notes'=>$request->note,
                    'pricing_type_id'=>$request->sale_type,
                    'acc_number'=>$store_data->accountant_number
                ])->id;
                    for ($i=0; $i <count($request->part_id) ; $i++) {
                        OrderPartServiceItemsDeliver::create([
                            'order_part_service_deliver_id'=>$deliver_id,
                            'part_id'=>$request->part_id[$i],
                            'amount'=>$request->deliver_amount[$i],
                            'source_id'=>$request->source_id[$i],
                            'status_id'=>$request->status_id[$i],
                            'quality_id'=>$request->quality_id[$i],
                            'type_id'=>$request->type_id[$i]
                        ]);

                        $amount = $request->deliver_amount[$i];
                        $part_id = $request->part_id[$i];
                        $source_id = $request->source_id[$i];
                        $status_id = $request->status_id[$i];
                        $quality_id = $request->quality_id[$i];
                        $type_id = $request->type_id[$i];

                        $totalItemVal = 0;  // سعر شراء

                         // ////////////////////////////////Decrement Store - allpart - section //////////////////////////////////////
                        if ($type_id == 1) {

                            $allparts = AllPart::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->with('order_supplier')->get();
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

                                    $totalItemVal += ($requestAmount0 * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value)) + $element->buy_costing;

                                    $store = Store::where('id', intval($request->equips_store_id))->get();
                                    $store_id = $store[0]->id;
                                    $store_name = $store[0]->name;
                                    $store_table_name = $store[0]->table_name;

                                    DB::table($store_table_name)
                                        ->join('stores_log', $store_table_name.'.store_log_id', '=', 'stores_log.id')
                                        ->join($all_part_table, function($join) use ($store_table_name, $all_part_table) {
                                            $join->on($store_table_name.'.supplier_order_id', '=', $all_part_table.'.order_supplier_id')
                                                ->on('stores_log.All_part_id', '=', $all_part_table.'.id');
                                        })
                                        ->where($all_part_table.'.source_id', $source_id)
                                        ->where($all_part_table.'.status_id', $status_id)
                                        ->where($all_part_table.'.quality_id', $quality_id)
                                        ->where($store_table_name.'.part_id', $part_id)
                                        ->where($store_table_name.'.supplier_order_id', $element->order_supplier_id)
                                        ->where($store_table_name.'.type_id', 1)
                                        ->decrement($store_table_name.'.amount', $requestAmount0);



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

                                    $totalItemVal += ($element->remain_amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value))+$element->buy_costing;

                                    $store = Store::where('id', intval($request->equips_store_id))->get();
                                    $store_id = $store[0]->id;
                                    $store_name = $store[0]->name;
                                    $store_table_name = $store[0]->table_name;

                                    DB::table($store_table_name)
                                        ->join('stores_log', $store_table_name.'.store_log_id', '=', 'stores_log.id')
                                        ->join($all_part_table, function($join) use ($store_table_name, $all_part_table) {
                                            $join->on($store_table_name.'.supplier_order_id', '=', $all_part_table.'.order_supplier_id')
                                                ->on('stores_log.All_part_id', '=', $all_part_table.'.id');
                                        })
                                        ->where($all_part_table.'.source_id', $source_id)
                                        ->where($all_part_table.'.status_id', $status_id)
                                        ->where($all_part_table.'.quality_id', $quality_id)
                                        ->where($store_table_name.'.part_id', $part_id)
                                        ->where($store_table_name.'.supplier_order_id', $element->order_supplier_id)
                                        ->where($store_table_name.'.type_id', 1)
                                        ->decrement($store_table_name.'.amount', $element->remain_amount);
                                        // ->get();
                                    $requestAmount0 = $requestAmount0 - $element->remain_amount;
                                } else if ($requestAmount0 <= 0) {
                                    break;
                                }
                            }


                            ////// remove from all parts ////////

                            $allpp = AllPart::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->get();
                            $requestAmount = $amount;
                            foreach ($allpp as $key => $element) {

                                if ($element->remain_amount >= $requestAmount) {
                                    // decrement amount
                                    AllPart::where('id', $element->id)->decrement('remain_amount', $requestAmount);

                                    break;
                                } elseif ($element->remain_amount < $requestAmount) {
                                    // decrement remain_amount
                                    AllPart::where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                                    $requestAmount = $requestAmount - $element->remain_amount;


                                } else if ($requestAmount <= 0) {
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
                                ->get();

                            $requestSecAmount = $amount;
                            foreach ($allsecc as $key => $element) {

                                if ($element->amount >= $requestSecAmount) {
                                    // decrement amount
                                    StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);


                                    break;
                                } elseif ($element->amount < $requestSecAmount) {
                                    // decrement remain_amount
                                    StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                                    $requestSecAmount = $requestSecAmount - $element->amount;

                                } else if ($requestSecAmount <= 0) {
                                    break;
                                }
                            }
                        } elseif ($type_id == 2) {
                            //wheel
                            $allparts = AllWheel::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->with('order_supplier')->get();

                            $all_part_table = 'all_wheels';
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

                                    $totalItemVal += ($requestAmount0 * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value)) + $element->buy_costing;


                                    $store = Store::where('id', intval($request->equips_store_id))->get();
                                    $store_id = $store[0]->id;
                                    $store_name = $store[0]->name;
                                    $store_table_name = $store[0]->table_name;



                                    DB::table($store_table_name)
                                    ->join('stores_log', $store_table_name.'.store_log_id', '=', 'stores_log.id')
                                    ->join($all_part_table, function($join) use ($store_table_name, $all_part_table) {
                                        $join->on($store_table_name.'.supplier_order_id', '=', $all_part_table.'.order_supplier_id')
                                            ->on('stores_log.All_part_id', '=', $all_part_table.'.id');
                                    })
                                    ->where($all_part_table.'.source_id', $source_id)
                                    ->where($all_part_table.'.status_id', $status_id)
                                    ->where($all_part_table.'.quality_id', $quality_id)
                                    ->where($store_table_name.'.part_id', $part_id)
                                    ->where($store_table_name.'.supplier_order_id', $element->order_supplier_id)
                                    ->where($store_table_name.'.type_id', 2)
                                    ->decrement($store_table_name.'.amount', $requestAmount0);






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

                                    $totalItemVal += ($element->remain_amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value)) + $element->buy_costing;


                                    $store = Store::where('id', intval($request->equips_store_id))->get();
                                    $store_id = $store[0]->id;
                                    $store_name = $store[0]->name;
                                    $store_table_name = $store[0]->table_name;


                                        DB::table($store_table_name)
                                        ->join('stores_log', $store_table_name.'.store_log_id', '=', 'stores_log.id')
                                        ->join($all_part_table, function($join) use ($store_table_name, $all_part_table) {
                                            $join->on($store_table_name.'.supplier_order_id', '=', $all_part_table.'.order_supplier_id')
                                                ->on('stores_log.All_part_id', '=', $all_part_table.'.id');
                                        })
                                        ->where($all_part_table.'.source_id', $source_id)
                                        ->where($all_part_table.'.status_id', $status_id)
                                        ->where($all_part_table.'.quality_id', $quality_id)
                                        ->where($store_table_name.'.part_id', $part_id)
                                        ->where($store_table_name.'.supplier_order_id', $element->order_supplier_id)
                                        ->where($store_table_name.'.type_id', 2)
                                        ->decrement($store_table_name.'.amount', $element->remain_amount);



                                    $requestAmount0 = $requestAmount0 - $element->remain_amount;
                                } else if ($requestAmount0 <= 0) {
                                    break;
                                }
                            }


                            ////// remove from all parts ////////

                            $allpp = AllWheel::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->get();
                            $requestAmount = $amount;
                            foreach ($allpp as $key => $element) {

                                if ($element->remain_amount >= $requestAmount) {
                                    // decrement amount
                                    AllWheel::where('id', $element->id)->decrement('remain_amount', $requestAmount);

                                    break;
                                } elseif ($element->remain_amount < $requestAmount) {
                                    // decrement remain_amount
                                    AllWheel::where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                                    $requestAmount = $requestAmount - $element->remain_amount;

                                } else if ($requestAmount <= 0) {
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
                                ->get();

                            $requestSecAmount = $amount;
                            foreach ($allsecc as $key => $element) {

                                if ($element->amount >= $requestSecAmount) {
                                    // decrement amount
                                    StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);


                                    break;
                                } elseif ($element->amount < $requestSecAmount) {
                                    // decrement remain_amount
                                    StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                                    $requestSecAmount = $requestSecAmount - $element->amount;

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

                                    $totalItemVal += ($requestAmount0 * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value)) + $element->buy_costing;

                                    $store = Store::where('id', intval($request->equips_store_id))->get();
                                    $store_id = $store[0]->id;
                                    $store_name = $store[0]->name;
                                    $store_table_name = $store[0]->table_name;



                                        DB::table($store_table_name)
                                        ->join('stores_log', $store_table_name.'.store_log_id', '=', 'stores_log.id')
                                        ->join($all_part_table, function($join) use ($store_table_name, $all_part_table) {
                                            $join->on($store_table_name.'.supplier_order_id', '=', $all_part_table.'.order_supplier_id')
                                                ->on('stores_log.All_part_id', '=', $all_part_table.'.id');
                                        })
                                        ->where($all_part_table.'.source_id', $source_id)
                                        ->where($all_part_table.'.status_id', $status_id)
                                        ->where($all_part_table.'.quality_id', $quality_id)
                                        ->where($store_table_name.'.part_id', $part_id)
                                        ->where($store_table_name.'.supplier_order_id', $element->order_supplier_id)
                                        ->where($store_table_name.'.type_id', 6)
                                        ->decrement($store_table_name.'.amount', $requestAmount0);




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

                                    $totalItemVal += ($element->remain_amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value)) + $element->buy_costing;

                                    $store = Store::where('id', intval($request->equips_store_id))->get();
                                    $store_id = $store[0]->id;
                                    $store_name = $store[0]->name;
                                    $store_table_name = $store[0]->table_name;


                                        DB::table($store_table_name)
                                        ->join('stores_log', $store_table_name.'.store_log_id', '=', 'stores_log.id')
                                        ->join($all_part_table, function($join) use ($store_table_name, $all_part_table) {
                                            $join->on($store_table_name.'.supplier_order_id', '=', $all_part_table.'.order_supplier_id')
                                                ->on('stores_log.All_part_id', '=', $all_part_table.'.id');
                                        })
                                        ->where($all_part_table.'.source_id', $source_id)
                                        ->where($all_part_table.'.status_id', $status_id)
                                        ->where($all_part_table.'.quality_id', $quality_id)
                                        ->where($store_table_name.'.part_id', $part_id)
                                        ->where($store_table_name.'.supplier_order_id', $element->order_supplier_id)
                                        ->where($store_table_name.'.type_id', 6)
                                        ->decrement($store_table_name.'.amount', $element->remain_amount);


                                    $requestAmount0 = $requestAmount0 - $element->remain_amount;
                                } else if ($requestAmount0 <= 0) {
                                    break;
                                }
                            }


                            ////// remove from all parts ////////

                            $allpp = AllKit::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->get();
                            $requestAmount = $amount;
                            foreach ($allpp as $key => $element) {

                                if ($element->remain_amount >= $requestAmount) {
                                    // decrement amount
                                    AllKit::where('id', $element->id)->decrement('remain_amount', $requestAmount);

                                    break;
                                } elseif ($element->remain_amount < $requestAmount) {
                                    // decrement remain_amount
                                    AllKit::where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                                    $requestAmount = $requestAmount - $element->remain_amount;


                                } else if ($requestAmount <= 0) {
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
                                ->get();

                            $requestSecAmount = $amount;
                            foreach ($allsecc as $key => $element) {

                                if ($element->amount >= $requestSecAmount) {
                                    // decrement amount
                                    StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);


                                    break;
                                } elseif ($element->amount < $requestSecAmount) {
                                    // decrement remain_amount
                                    StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                                    $requestSecAmount = $requestSecAmount - $element->amount;

                                } else if ($requestSecAmount <= 0) {
                                    break;
                                }
                            }
                        }
                        ///////////////////////////////////////////////////////////////////////////////////////////

                    }
                    OrderPartService::where('id',$request->order_id)->update([
                        'flag' => 1
                    ]);


                    $storeData =  Store::where('id',$request->equips_store_id)->first();

                    $quaditems = [];
                    $automaicQayd = new QaydController();
                    array_push($quaditems, (object) ['acountant_id' => 123 , 'madin' => $totalItemVal, 'dayin' => 0]);
                    array_push($quaditems, (object) ['acountant_id' => $storeData->accountant_number, 'madin' => 0, 'dayin' => $totalItemVal]);
                    $date = Carbon::now();
                    $type = null;
                    $notes = ' صرف بضاعة لصالح الصيانة ' . $deliver_id;
                    $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);



                DB::commit();
                session()->flash("success", "success");
                return redirect()->to('orderpartservicePrint/' . $deliver_id.'/'.$request->equips_store_id);
                // return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash("error", $e->getMessage());
            return redirect()->to('tawreedServicesInbox/' . $request->equips_store_id);
        }

    }

    public function orderpartservicePrint($id,$store_id){
        $store_data = Store::where('id',$store_id)->get();
        $data = OrderPartServiceDeliver::where('id',$id)->with('pricing_type')->with('order_part_service_items_delivers.source')->with('order_part_service_items_delivers.status')->with('order_part_service_items_delivers.part_quality')->with('order_part_service_items_delivers.type')->with('store')->with('user')->first();

        if($data->type_id == 3){
             $data['type'] = 'جرار';
             $data['itemData'] = Tractor::where('id',$data->equips_id)->first();
        }elseif($data->type_id == 4){
             $data['type'] = 'كلارك';
             $data['itemData'] = Clark::where('id',$data->equips_id)->first();
        }elseif($data->type_id == 4){
             $data['type'] = 'معدة';
             $data['itemData'] = Equip::where('id',$data->equips_id)->first();
        }

        if(count($data->order_part_service_items_delivers) > 0){
            foreach ($data->order_part_service_items_delivers as $key => $item) {
               if($item->type_id == 1){
                $item['data'] = Part::where('id',$item->part_id)->first();

                $item['avialable']= AllPart::where('part_id',$item->part_id)
                ->where('source_id',$item->source_id)
                ->where('status_id',$item->status_id)->where('quality_id',$item->quality_id)->sum('remain_amount');

               }elseif($item->type_id == 6){
                $item['data'] = Kit::where('id',$item->part_id)->first();

                $item['avialable']= AllKit::where('part_id',$item->part_id)
                ->where('source_id',$item->source_id)
                ->where('status_id',$item->status_id)->where('quality_id',$item->quality_id)->sum('remain_amount');
               }elseif($item->type_id == 2){
                $item['data'] = Wheel::where('id',$item->part_id)->first();

                $item['avialable']= AllWheel::where('part_id',$item->part_id)
                ->where('source_id',$item->source_id)
                ->where('status_id',$item->status_id)->where('quality_id',$item->quality_id)->sum('remain_amount');
               }


               $item['price'] = SalePricing::where(function ($q) use ($item,$data) {
                    $q->where('to', '>=', $data->date)->orWhere('to', null);
                })
                ->where('sale_type', $data->pricing_type_id)
                ->where('part_id', $item->part_id)
                ->where('source_id', $item->source_id)
                ->where('status_id', $item->status_id)
                ->where('quality_id', $item->quality_id)
                ->with('sale_type')
                ->get();
               $PartInStoresCount = new POSController();
               $item['PartInStoresCount'] = $PartInStoresCount->PartInStoresCount($item->part_id,$item->source_id,$item->status_id,$item->quality_id,$item->type_id);
            }
        }
        // return $data;
        return view('orderpartservicePrint',compact('store_data','data'));
    }
      public function store_service($store_id){
        $store_data = Store::where('id', $store_id)->get();
        return view('services',compact('store_id','store_data'));
    }
}
