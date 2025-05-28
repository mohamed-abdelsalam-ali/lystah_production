<?php

namespace App\Http\Controllers;
use App\Models\AllClark;
use App\Models\AllEquip;
use App\Models\AllPart;
use App\Models\AllKit;
use App\Models\AllWheel;
use App\Models\Currency;
use App\Models\CurrencyType;
use App\Models\PricingType;
use App\Models\SalePricing;
use App\Models\SaleType;
use App\Models\Store;
use Carbon\Carbon;
use App\Models\AllTractor;
use App\Models\SaleTypeRatio;
use App\Models\Unit;

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;
use Illuminate\Support\Facades\Auth;
class PricingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $allparts1 = AllPart::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id')->with('part')->with('source')->with('status')->with('part_quality')->get();
        foreach ($allparts1 as $key => $part1) {

            $part1['pricing'] = SalePricing::where('to',null)->where('part_id',$part1->part_id)->where('status_id',$part1->status_id)->where('source_id',$part1->source_id)->where('quality_id',$part1->quality_id)->with('sale_type')->get();
            $part1['lastpricing'] = AllPart::where('part_id',$part1->part_id)->where('status_id',$part1->status_id)->where('source_id',$part1->source_id)->where('quality_id',$part1->quality_id)->orderBy('id','DESC')->with('order_supplier')->first();
            $part1['lastPricing0'] = SalePricing::where('part_id',$part1->part_id)->where('status_id',$part1->status_id)->where('source_id',$part1->source_id)->where('quality_id',$part1->quality_id)->with('sale_type')->orderBy('id','DESC')->get();

            if(Count($part1['pricing'])>0){
            $part1['check_null_price']=1;
            }else{
            $part1['check_null_price']=0;
            }
        }

        $allpart_without_price = $allparts1->filter(function ($item) { //collection filter
            if($item->check_null_price == 0){
                return $item;
            }

        })->values();
            $allpart_with_price = $allparts1->filter(function ($item) { //collection filter
            if($item->check_null_price == 1){
                return $item;
            }

        })->values();
        // return $allpart_without_price;
        //........................................................................
        $allkits = AllKit::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id')->with('kit')->with('source')->with('status')->with('part_quality')->get();
        foreach ($allkits as $key => $part) {
            $part['pricing'] = SalePricing::where('to',null)->where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->get();
            $part['lastpricing'] = AllKit::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->orderBy('id','DESC')->with('order_supplier')->first();
            $part['lastPricing0'] = SalePricing::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->orderBy('id','DESC')->get();
            if(Count($part['pricing'])>0){
                $part['check_null_price']=1;
            }else{
                $part['check_null_price']=0;
            }
        }

        $allkits_without_price = $allkits->filter(function ($item) { //collection filter
            if($item->check_null_price == 0){
                return $item;
            }

        })->values();
         $allkits_with_price = $allkits->filter(function ($item) { //collection filter
            if($item->check_null_price == 1){
                return $item;
            }

        })->values();
        // return $allkits_without_price;
//..............................................................................
        $allwheels = AllWheel::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id')->with('wheel')->with('source')->with('status')->with('part_quality')->get();
        foreach ($allwheels as $key => $part) {
            $part['pricing'] = SalePricing::where('to',null)->where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->get();
            $part['lastpricing'] = AllWheel::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->orderBy('id','DESC')->with('order_supplier')->first();
            $part['lastPricing0'] = SalePricing::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->orderBy('id','DESC')->get();
            if(Count($part['pricing'])>0){
                $part['check_null_price']=1;
            }else{
                $part['check_null_price']=0;
            }
        }

        $allwheels_without_price = $allwheels->filter(function ($item) { //collection filter
            if($item->check_null_price == 0){
                return $item;
            }

        })->values();
             $allwheels_with_price = $allwheels->filter(function ($item) { //collection filter
            if($item->check_null_price == 1){
                return $item;
            }

        })->values();
        // return $allwheels_without_price;

        //.....................................................................
        $alltractors = AllTractor::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id')->with('tractor')->with('source')->with('status')->with('part_quality')->get();
        foreach ($alltractors as $key => $part) {
            $part['pricing'] = SalePricing::where('to',null)->where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->get();
            $part['lastpricing'] = AllTractor::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->orderBy('id','DESC')->with('order_supplier')->first();
            $part['lastPricing0'] = SalePricing::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->orderBy('id','DESC')->get();
             if(Count($part['pricing'])>0){
                $part['check_null_price']=1;
            }else{
                $part['check_null_price']=0;
            }
        }
        $alltractors_without_price = $alltractors->filter(function ($item) { //collection filter
            if($item->check_null_price == 0){
                return $item;
            }

        })->values();
           $alltractors_with_price = $alltractors->filter(function ($item) { //collection filter
            if($item->check_null_price == 1){
                return $item;
            }

        })->values();
        // return $alltractors_without_price;

        //.........................................................................................................
        $Allclarks = AllClark::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id')->with('clark')->with('source')->with('status')->with('part_quality')->get();
        foreach ($Allclarks as $key => $part) {
            $part['pricing'] = SalePricing::where('to',null)->where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->get();
            $part['lastpricing'] = AllClark::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->orderBy('id','DESC')->with('order_supplier')->first();
            $part['lastPricing0'] = SalePricing::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->orderBy('id','DESC')->get();
            if(Count($part['pricing'])>0){
                $part['check_null_price']=1;
            }else{
                $part['check_null_price']=0;
            }
        }

        $Allclarks_without_price = $Allclarks->filter(function ($item) { //collection filter
            if($item->check_null_price == 0){
                return $item;
            }

        })->values();
           $Allclarks_with_price = $Allclarks->filter(function ($item) { //collection filter
            if($item->check_null_price == 1){
                return $item;
            }

        })->values();

        // return $Allclarks_without_price;
    //.......................................................................................


        $Allequips = AllEquip::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id')->with('equip')->with('source')->with('status')->with('part_quality')->get();
        foreach ($Allequips as $key => $part) {
            $part['pricing'] = SalePricing::where('to',null)->where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->get();
            $part['lastpricing'] = AllEquip::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->orderBy('id','DESC')->with('order_supplier')->first();
            $part['lastPricing0'] = SalePricing::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->orderBy('id','DESC')->get();
            if(Count($part['pricing'])>0){
                $part['check_null_price']=1;
            }else{
                $part['check_null_price']=0;
            }
        }
        $Allequips_without_price = $Allequips->filter(function ($item) { //collection filter
            if($item->check_null_price == 0){
                return $item;
            }
        })->values();

        $Allequips_with_price = $Allequips->filter(function ($item) { //collection filter
            if($item->check_null_price == 1){
                return $item;
            }
        })->values();
        // return $Allequips_without_price;

        $allparts = new \Illuminate\Database\Eloquent\Collection; //Create empty collection which we know has the merge() method
        $allparts = $allparts->concat($allparts1);
        $allparts = $allparts->concat($allkits);
        $allparts = $allparts->concat($allwheels);
        $allparts = $allparts->concat($alltractors);
        $allparts = $allparts->concat($Allclarks);
        $allparts = $allparts->concat($Allequips);

        $allparts_without_prices = new \Illuminate\Database\Eloquent\Collection; //Create empty collection which we know has the merge() method
        $allparts_without_prices = $allparts_without_prices->concat($allpart_without_price);
        $allparts_without_prices = $allparts_without_prices->concat($allkits_without_price);
        $allparts_without_prices = $allparts_without_prices->concat($allwheels_without_price);
        $allparts_without_prices = $allparts_without_prices->concat($alltractors_without_price);
        $allparts_without_prices = $allparts_without_prices->concat($Allclarks_without_price);
        $allparts_without_prices = $allparts_without_prices->concat($Allequips_without_price);


        $allparts_with_prices = new \Illuminate\Database\Eloquent\Collection; //Create empty collection which we know has the merge() method
        $allparts_with_prices = $allparts_with_prices->concat($allpart_with_price);
        $allparts_with_prices = $allparts_with_prices->concat($allkits_with_price);
        $allparts_with_prices = $allparts_with_prices->concat($allwheels_with_price);
        $allparts_with_prices = $allparts_with_prices->concat($alltractors_with_price);
        $allparts_with_prices = $allparts_with_prices->concat($Allclarks_with_price);
        $allparts_with_prices = $allparts_with_prices->concat($Allequips_with_price);
        // return $allparts_without_prices;


        $pricingTypes = PricingType::all();
        $currency = CurrencyType::with(['currencies'=>function($query){
            return $query->where('to',null);
        }])->get();
         $currencyHistory = Currency::where('to',null)->with('currency_type')->get();
        // return $allparts;
        return view('pricing',compact('allparts','allparts_with_prices','allparts_without_prices','pricingTypes','currency','currencyHistory'));
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
        
        ini_set('memory_limit', '-1');
        // return $request;
        DB::beginTransaction();
        try {
            $xxx = [];
            $saleTypes = PricingType::all();
            // return $request->partId ;
            foreach ($request->partId as $key => $part) {
                $source = $request->source[$key];
                $status = $request->status[$key];
                $quality = $request->quality[$key];
    
    
                    for ($i=0; $i < count($saleTypes) ; $i++) {
                        $saleTypeid = $saleTypes[$i]->id;
    
                        $saleTypeName = 'price-'.$saleTypes[$i]->id;
                        $price = 0;
                        if(isset($request->$saleTypeName[$key])){
                            $price = $request->$saleTypeName[$key];
                        }
                        $selectedUnits = 0;
                        if(isset($request->selectedUnits[$key])){
                            $selectedUnits = $request->selectedUnits[$key];
                        }
                        $ratiounit = 1;
                        if($selectedUnits > 0){
                            $ratiounit = getSmallUnit($selectedUnits, $request->smallUnit[$key]);
                        }
                        // echo $price . '-';
                        //  $x = SalePricing::where('part_id',$part)->where('source_id',$source)->where('sale_type',$saleTypeid)->where('status_id',$status)->where('quality_id',$quality)->orderBy('id','DESC')->first();
                        // if(isset($x->to)){
                        //     SalePricing::where('part_id',$part)->where('source_id',$source)->where('sale_type',$saleTypeid)->where('status_id',$status)->where('quality_id',$quality)->orderBy('id','DESC')->first()->update(['to'=>null]);
                        // }
                        ////////////////////////////////end salam
                        $cond = SalePricing::where('to',null)->where('part_id',$part)->where('sale_type',$saleTypeid)->where('source_id',$source)->where('status_id',$status)->where('quality_id',$quality)->update(['to'=>Carbon::now()]);
                        if( $price <> 0  ){
                            $pricing = new SalePricing();
                            $pricing->part_id = $part;
                            $pricing->source_id = $source;
                            $pricing->status_id = $status;
                            $pricing->quality_id = $quality;
                            $pricing->from = Carbon::now();
                            $pricing->to = null;
                            $pricing->type_id = $request->type[$key];
                            $pricing->currency_id = 400;
                            $pricing->sale_type = $saleTypeid;
                            $pricing->price = $price / $ratiounit;
                            // end old pricing ////////////////////////
    

                            // end old pricing ////////////////////////
                            $pricing->save();
                        }
    
    
                }
    
    
            }
    
    
    
    
            // return $this->index();
           
            // return redirect()->to('/pricing');
            // return redirect()->route('pricing.index');
            
              DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            //throw $th;
            DB::rollback();
            session()->flash("error", $e->getMessage());
            return false;
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
       public function pricingwith_flag(Request $request, $flage)
    {
        //
        // return $flage;
        $allparts1 = AllPart::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id')->with('part.getsmallunit.unit')->with('source')->with('status')->with('part_quality')->get();
        foreach ($allparts1 as $key => $part1) {

            $part1['pricing'] = SalePricing::where('to',null)->where('part_id',$part1->part_id)->where('status_id',$part1->status_id)->where('source_id',$part1->source_id)->where('quality_id',$part1->quality_id)->with('sale_type')->get();
            $part1['lastpricing'] = AllPart::where('part_id',$part1->part_id)->where('status_id',$part1->status_id)->where('source_id',$part1->source_id)->where('quality_id',$part1->quality_id)->orderBy('id','DESC')->with('order_supplier')->first();
            $part1['lastPricing0'] = SalePricing::where('part_id',$part1->part_id)->where('status_id',$part1->status_id)->where('source_id',$part1->source_id)->where('quality_id',$part1->quality_id)->with('sale_type')->orderBy('id','DESC')->limit(5)->get();

            if(Count($part1['pricing'])>0){
            $part1['check_null_price']=1;
            }else{
            $part1['check_null_price']=0;
            }
        }

        $allpart_without_price = $allparts1->filter(function ($item) { //collection filter
            if($item->check_null_price == 0){
                return $item;
            }

        })->values();
            $allpart_with_price = $allparts1->filter(function ($item) { //collection filter
            if($item->check_null_price == 1){
                return $item;
            }

        })->values();
        // return $allpart_without_price;
        //........................................................................
        $allkits = AllKit::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id')->with('kit')->with('source')->with('status')->with('part_quality')->get();
        foreach ($allkits as $key => $part) {
            $part['pricing'] = SalePricing::where('to',null)->where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->get();
            $part['lastpricing'] = AllKit::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->orderBy('id','DESC')->with('order_supplier')->first();
            $part['lastPricing0'] = SalePricing::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->orderBy('id','DESC')->get();
            if(Count($part['pricing'])>0){
                $part['check_null_price']=1;
            }else{
                $part['check_null_price']=0;
            }
        }

        $allkits_without_price = $allkits->filter(function ($item) { //collection filter
            if($item->check_null_price == 0){
                return $item;
            }

        })->values();
         $allkits_with_price = $allkits->filter(function ($item) { //collection filter
            if($item->check_null_price == 1){
                return $item;
            }

        })->values();
        // return $allkits_without_price;
//..............................................................................
        $allwheels = AllWheel::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id')->with('wheel')->with('source')->with('status')->with('part_quality')->get();
        foreach ($allwheels as $key => $part) {
            $part['pricing'] = SalePricing::where('to',null)->where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->get();
            $part['lastpricing'] = AllWheel::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->orderBy('id','DESC')->with('order_supplier')->first();
            $part['lastPricing0'] = SalePricing::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->orderBy('id','DESC')->get();
            if(Count($part['pricing'])>0){
                $part['check_null_price']=1;
            }else{
                $part['check_null_price']=0;
            }
        }

        $allwheels_without_price = $allwheels->filter(function ($item) { //collection filter
            if($item->check_null_price == 0){
                return $item;
            }

        })->values();
             $allwheels_with_price = $allwheels->filter(function ($item) { //collection filter
            if($item->check_null_price == 1){
                return $item;
            }

        })->values();
        // return $allwheels_without_price;

        //.....................................................................
        $alltractors = AllTractor::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id')->with('tractor')->with('source')->with('status')->with('part_quality')->get();
        foreach ($alltractors as $key => $part) {
            $part['pricing'] = SalePricing::where('to',null)->where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->get();
            $part['lastpricing'] = AllTractor::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->orderBy('id','DESC')->with('order_supplier')->first();
            $part['lastPricing0'] = SalePricing::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->orderBy('id','DESC')->get();
             if(Count($part['pricing'])>0){
                $part['check_null_price']=1;
            }else{
                $part['check_null_price']=0;
            }
        }
        $alltractors_without_price = $alltractors->filter(function ($item) { //collection filter
            if($item->check_null_price == 0){
                return $item;
            }

        })->values();
           $alltractors_with_price = $alltractors->filter(function ($item) { //collection filter
            if($item->check_null_price == 1){
                return $item;
            }

        })->values();
        // return $alltractors_without_price;

        //.........................................................................................................
        $Allclarks = AllClark::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id')->with('clark')->with('source')->with('status')->with('part_quality')->get();
        foreach ($Allclarks as $key => $part) {
            $part['pricing'] = SalePricing::where('to',null)->where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->get();
            $part['lastpricing'] = AllClark::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->orderBy('id','DESC')->with('order_supplier')->first();
            $part['lastPricing0'] = SalePricing::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->orderBy('id','DESC')->get();
            if(Count($part['pricing'])>0){
                $part['check_null_price']=1;
            }else{
                $part['check_null_price']=0;
            }
        }

        $Allclarks_without_price = $Allclarks->filter(function ($item) { //collection filter
            if($item->check_null_price == 0){
                return $item;
            }

        })->values();
           $Allclarks_with_price = $Allclarks->filter(function ($item) { //collection filter
            if($item->check_null_price == 1){
                return $item;
            }

        })->values();

        // return $Allclarks_without_price;
    //.......................................................................................


        $Allequips = AllEquip::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id')->with('equip')->with('source')->with('status')->with('part_quality')->get();
        foreach ($Allequips as $key => $part) {
            $part['pricing'] = SalePricing::where('to',null)->where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->get();
            $part['lastpricing'] = AllEquip::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->orderBy('id','DESC')->with('order_supplier')->first();
            $part['lastPricing0'] = SalePricing::where('part_id',$part->part_id)->where('status_id',$part->status_id)->where('source_id',$part->source_id)->where('quality_id',$part->quality_id)->with('sale_type')->orderBy('id','DESC')->get();
            if(Count($part['pricing'])>0){
                $part['check_null_price']=1;
            }else{
                $part['check_null_price']=0;
            }
        }
        $Allequips_without_price = $Allequips->filter(function ($item) { //collection filter
            if($item->check_null_price == 0){
                return $item;
            }
        })->values();

        $Allequips_with_price = $Allequips->filter(function ($item) { //collection filter
            if($item->check_null_price == 1){
                return $item;
            }
        })->values();
        // return $Allequips_without_price;

        $allparts = new \Illuminate\Database\Eloquent\Collection; //Create empty collection which we know has the merge() method
        $allparts = $allparts->concat($allparts1);
        $allparts = $allparts->concat($allkits);
        $allparts = $allparts->concat($allwheels);
        $allparts = $allparts->concat($alltractors);
        $allparts = $allparts->concat($Allclarks);
        $allparts = $allparts->concat($Allequips);

        $allparts_without_prices = new \Illuminate\Database\Eloquent\Collection; //Create empty collection which we know has the merge() method
        $allparts_without_prices = $allparts_without_prices->concat($allpart_without_price);
        $allparts_without_prices = $allparts_without_prices->concat($allkits_without_price);
        $allparts_without_prices = $allparts_without_prices->concat($allwheels_without_price);
        $allparts_without_prices = $allparts_without_prices->concat($alltractors_without_price);
        $allparts_without_prices = $allparts_without_prices->concat($Allclarks_without_price);
        $allparts_without_prices = $allparts_without_prices->concat($Allequips_without_price);


        $allparts_with_prices = new \Illuminate\Database\Eloquent\Collection; //Create empty collection which we know has the merge() method
        $allparts_with_prices = $allparts_with_prices->concat($allpart_with_price);
        $allparts_with_prices = $allparts_with_prices->concat($allkits_with_price);
        $allparts_with_prices = $allparts_with_prices->concat($allwheels_with_price);
        $allparts_with_prices = $allparts_with_prices->concat($alltractors_with_price);
        $allparts_with_prices = $allparts_with_prices->concat($Allclarks_with_price);
        $allparts_with_prices = $allparts_with_prices->concat($Allequips_with_price);
        // return $allparts_without_prices;


        $pricingTypes = PricingType::all();
        $currency = CurrencyType::with(['currencies'=>function($query){
            return $query->where('to',null);
        }])->get();
        $currencyHistory = Currency::where('to',null)->with('currency_type')->get();
        if($flage==1){
            $allparts=$allparts_with_prices;
        }else if($flage == 0){
              $allparts=$allparts_without_prices;
        }else{

        }
        $units =  Unit::all();

        // return $allparts;
        // return $this->simplePriceList();
        return view('pricing',compact('units','allparts','allparts_with_prices','allparts_without_prices','pricingTypes','currency','currencyHistory'));

    }

    public function updatecurrcy(Request $request){
        // return $request->price;
        //  $all_currency_types=CurrencyType::with(['currencies'=>function($query){
        //         return $query->where('to',null);
        //     }])->get();
        //   return $all_currency_types;
          for ($i=0; $i <count($request->curency) ; $i++) {
            $Oldrow=Currency::where('currency_id',$request->curency[$i])->where('to',null)->first();

            if($request->price[$i] != $Oldrow->value){
                  $today = Carbon::now()->toDateString();


                   Currency::where('id',$Oldrow->id)->update([
                        'to'=>  $today ,
                        'desc'=>'تعديل من شاشة التسعيرة'
                     ]);
                        Currency::create([
                    'currency_id'=>$request->curency[$i],
                    'value'=>$request->price[$i],
                    'from'=>$today
                    ]);
             session()->flash("success", "تم التعديل بنجاح");
             return redirect()->back();
            }
        }

    }

    public function pricingAll(){

        // return 'ddddd';
        $pricingTypes = PricingType::all();
        $currency = CurrencyType::with(['currencies'=>function($query){
            return $query->where('to',null);
        }])->get();
         $currencyHistory = Currency::where('to',null)->with('currency_type')->get();

        return view('pricingAll',compact('pricingTypes','currency','currencyHistory'));

    }

    public function saveAllPrice(Request $request){
        // return $request;

        $saleTypes = $request->type_id;

        $selectedType = $request->part_type;
        $allparts=new \Illuminate\Database\Eloquent\Collection;;
        $allwheels=new \Illuminate\Database\Eloquent\Collection;;
        $alltractors=new \Illuminate\Database\Eloquent\Collection;;
        $Allclarks=new \Illuminate\Database\Eloquent\Collection;;
        $Allequips=new \Illuminate\Database\Eloquent\Collection;;
        $allkits=new \Illuminate\Database\Eloquent\Collection;;

        if($selectedType==1){
            $allparts = AllPart::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id',DB::raw("'1' as type"))->with('part')->with('source')->with('status')->with('part_quality')->get();

        }elseif($selectedType==2){
            $allwheels = AllWheel::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id',DB::raw("'2' as type"))->with('wheel')->with('source')->with('status')->with('part_quality')->get();

        }if($selectedType==3){
            $alltractors = AllTractor::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id',DB::raw("'3' as type"))->with('tractor')->with('source')->with('status')->with('part_quality')->get();

        }if($selectedType==4){
            $Allclarks = AllClark::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id',DB::raw("'4' as type"))->with('clark')->with('source')->with('status')->with('part_quality')->get();

        }if($selectedType==5){
            $Allequips = AllEquip::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id',DB::raw("'5' as type"))->with('equip')->with('source')->with('status')->with('part_quality')->get();

        }if($selectedType==6){
            $allkits = AllKit::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id',DB::raw("'6' as type"))->with('kit')->with('source')->with('status')->with('part_quality')->get();

        }

         $allItems = $allparts->concat($allkits)->concat($allwheels)->concat($alltractors)->concat($Allclarks)->concat($Allequips);
        // return $allItems;


        foreach ($allItems as $key => $part) {
            $source = $part->source_id;
            $status = $part->status_id;
            $quality = $part->quality_id;
            $part_id = $part->part_id;
            $type = intval($part->type);

                for ($i=0; $i < count($saleTypes) ; $i++) {
                    $saleTypeid = intval($saleTypes[$i]);

                    $cond = SalePricing::where('to',null)->where('part_id',$part_id)->where('sale_type',$saleTypeid)->where('source_id',$source)->where('status_id',$status)->where('quality_id',$quality)->where('type_id',$type);
                    $conddata =$cond->first();
                    $cond->update(['to'=>Carbon::now()]);

                    if($conddata){
                        $pricing = new SalePricing();
                        $pricing->part_id = $part_id;
                        $pricing->source_id = $source;
                        $pricing->status_id = $status;
                        $pricing->quality_id = $quality;
                        $pricing->from = Carbon::now();
                        $pricing->to = null;
                        $pricing->type_id = $type;
                        $pricing->currency_id = 400;
                        $pricing->sale_type = $saleTypeid;
                        $pricing->price = $conddata->price + ($conddata->price * floatval($request->values[$i]) / 100) ;
                        $pricing->save();
                    }else{
                        // return 'get last buy price';
                        $buyCoast = 0 ;
                        if($type == 1){
                            $row = AllPart::where('part_id',$part_id)->where('source_id',$source)->where('status_id',$status)->where('quality_id',$quality)->with('order_supplier')->orderBy('id','DESC')->first();
                            $cureencyId = $row->order_supplier->currency_id;
                            $cureencydate = $row->order_supplier->confirmation_date;

                            $currency_data=CurrencyType::with(['currencies'=>function($query) use($cureencyId , $cureencydate){
                                return $query->where('from','>=',$cureencydate)->where('to','<=',$cureencydate)->where('currency_id',$cureencyId)->orWhere('to','=',null);
                            }])->where('id',$cureencyId)->first();


                            $buyCoast = $row->buy_price * $currency_data->currencies[0]->value;
                            $buyCoast += $row->buy_costing;

                        }elseif($type == 2){
                            $row = AllWheel::where('part_id',$part_id)->where('source_id',$source)->where('status_id',$status)->where('quality_id',$quality)->with('order_supplier')->orderBy('id','DESC')->first();
                            $cureencyId = $row->order_supplier->currency_id;
                            $cureencydate = $row->order_supplier->confirmation_date;

                            $currency_data=CurrencyType::with(['currencies'=>function($query) use($cureencyId , $cureencydate){
                                return $query->where('from','>=',$cureencydate)->where('to','<=',$cureencydate)->where('currency_id',$cureencyId)->orWhere('to','=',null);
                            }])->where('id',$cureencyId)->first();


                            $buyCoast = $row->buy_price * $currency_data->currencies[0]->value;
                            $buyCoast += $row->buy_costing;

                        }elseif($type == 3){
                            $row = AllTractor::where('part_id',$part_id)->where('source_id',$source)->where('status_id',$status)->where('quality_id',$quality)->with('order_supplier')->orderBy('id','DESC')->first();
                            $cureencyId = $row->order_supplier->currency_id;
                            $cureencydate = $row->order_supplier->confirmation_date;

                            $currency_data=CurrencyType::with(['currencies'=>function($query) use($cureencyId , $cureencydate){
                                return $query->where('from','>=',$cureencydate)->where('to','<=',$cureencydate)->where('currency_id',$cureencyId)->orWhere('to','=',null);
                            }])->where('id',$cureencyId)->first();


                            $buyCoast = $row->buy_price * $currency_data->currencies[0]->value;
                            $buyCoast += $row->buy_costing;

                        }elseif($type == 4){
                            $row = AllClark::where('part_id',$part_id)->where('source_id',$source)->where('status_id',$status)->where('quality_id',$quality)->with('order_supplier')->orderBy('id','DESC')->first();
                            $cureencyId = $row->order_supplier->currency_id;
                            $cureencydate = $row->order_supplier->confirmation_date;

                            $currency_data=CurrencyType::with(['currencies'=>function($query) use($cureencyId , $cureencydate){
                                return $query->where('from','>=',$cureencydate)->where('to','<=',$cureencydate)->where('currency_id',$cureencyId)->orWhere('to','=',null);
                            }])->where('id',$cureencyId)->first();


                            $buyCoast = $row->buy_price * $currency_data->currencies[0]->value;
                            $buyCoast += $row->buy_costing;

                        }elseif($type == 5){
                            $row = AllEquip::where('part_id',$part_id)->where('source_id',$source)->where('status_id',$status)->where('quality_id',$quality)->with('order_supplier')->orderBy('id','DESC')->first();
                            $cureencyId = $row->order_supplier->currency_id;
                            $cureencydate = $row->order_supplier->confirmation_date;

                            $currency_data=CurrencyType::with(['currencies'=>function($query) use($cureencyId , $cureencydate){
                                return $query->where('from','>=',$cureencydate)->where('to','<=',$cureencydate)->where('currency_id',$cureencyId)->orWhere('to','=',null);
                            }])->where('id',$cureencyId)->first();


                            $buyCoast = $row->buy_price * $currency_data->currencies[0]->value;
                            $buyCoast += $row->buy_costing;

                        }elseif($type == 6){
                            $row = AllKit::where('part_id',$part_id)->where('source_id',$source)->where('status_id',$status)->where('quality_id',$quality)->with('order_supplier')->orderBy('id','DESC')->first();
                            $cureencyId = $row->order_supplier->currency_id;
                            $cureencydate = $row->order_supplier->confirmation_date;

                            $currency_data=CurrencyType::with(['currencies'=>function($query) use($cureencyId , $cureencydate){
                                return $query->where('from','>=',$cureencydate)->where('to','<=',$cureencydate)->where('currency_id',$cureencyId)->orWhere('to','=',null);
                            }])->where('id',$cureencyId)->first();


                            $buyCoast = $row->buy_price * $currency_data->currencies[0]->value;
                            $buyCoast += $row->buy_costing;

                        }

                        $pricing = new SalePricing();
                        $pricing->part_id = $part_id;
                        $pricing->source_id = $source;
                        $pricing->status_id = $status;
                        $pricing->quality_id = $quality;
                        $pricing->from = Carbon::now();
                        $pricing->to = null;
                        $pricing->type_id = $type;
                        $pricing->currency_id = 400;
                        $pricing->sale_type = $saleTypeid;
                        $pricing->price = $buyCoast + ($buyCoast * floatval($request->values[$i]) / 100) ;
                        $pricing->save();
                    }




            }


        }


         /// ratio///////////////////////////

         for ($i=0; $i < count($saleTypes) ; $i++) {
            $saleValue = $request->values[$i];
            $sale_typex = $saleTypes[$i];

            if(floatval($saleValue) > 0 ){
                $oldSaleTypeRatio = SaleTypeRatio::where('sale_type_id',$sale_typex)->where('to',null)->where('type',$request->part_type)->get();
                if(count($oldSaleTypeRatio) > 0){
                    // close and open
                    SaleTypeRatio::where('sale_type_id',$sale_typex)->where('to',null)->where('type',$request->part_type)->update([
                        'to' => Carbon::now()
                    ]);
                    SaleTypeRatio::create([
                        'sale_type_id' => $sale_typex,
                        'value' => $saleValue,
                        'from' => Carbon::now(),
                        'to' => null,
                        'user_id' => auth()->user()->id,
                        'type' => $request->part_type,
                        'notes' => 'طبقا لاخر سعر بيع'
                    ]);
                }else{
                    // open
                    SaleTypeRatio::create([
                        'sale_type_id' => $sale_typex,
                        'value' => $saleValue,
                        'from' => Carbon::now(),
                        'to' => null,
                        'user_id' => auth()->user()->id,
                        'type' => $request->part_type,
                        'notes' => 'طبقا لاخر سعر بيع'
                    ]);
                }
            }
        }
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->back();


    }
    public function saveAllPriceB1(Request $request){
        // return $request;

        $saleTypes = $request->type_id;

        $selectedType = $request->part_type;
        $allparts=new \Illuminate\Database\Eloquent\Collection;;
        $allwheels=new \Illuminate\Database\Eloquent\Collection;;
        $alltractors=new \Illuminate\Database\Eloquent\Collection;;
        $Allclarks=new \Illuminate\Database\Eloquent\Collection;;
        $Allequips=new \Illuminate\Database\Eloquent\Collection;;
        $allkits=new \Illuminate\Database\Eloquent\Collection;;

        if($selectedType==1){
            $allparts = AllPart::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id',DB::raw("'1' as type"))->with('part')->with('source')->with('status')->with('part_quality')->get();

        }elseif($selectedType==2){
            $allwheels = AllWheel::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id',DB::raw("'2' as type"))->with('wheel')->with('source')->with('status')->with('part_quality')->get();

        }if($selectedType==3){
            $alltractors = AllTractor::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id',DB::raw("'3' as type"))->with('tractor')->with('source')->with('status')->with('part_quality')->get();

        }if($selectedType==4){
            $Allclarks = AllClark::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id',DB::raw("'4' as type"))->with('clark')->with('source')->with('status')->with('part_quality')->get();

        }if($selectedType==5){
            $Allequips = AllEquip::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id',DB::raw("'5' as type"))->with('equip')->with('source')->with('status')->with('part_quality')->get();

        }if($selectedType==6){
            $allkits = AllKit::where('remain_amount' ,'>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select('part_id','source_id','status_id','quality_id',DB::raw("'6' as type"))->with('kit')->with('source')->with('status')->with('part_quality')->get();

        }

         $allItems = $allparts->concat($allkits)->concat($allwheels)->concat($alltractors)->concat($Allclarks)->concat($Allequips);
        // return $allItems;


        foreach ($allItems as $key => $part) {
            $source = $part->source_id;
            $status = $part->status_id;
            $quality = $part->quality_id;
            $part_id = $part->part_id;
            $type = intval($part->type);

                for ($i=0; $i < count($saleTypes) ; $i++) {
                    $saleTypeid = intval($saleTypes[$i]);

                    if($request->values[$i] > 0){

                        $cond = SalePricing::where('to',null)->where('part_id',$part_id)->where('sale_type',$saleTypeid)->where('source_id',$source)->where('status_id',$status)->where('quality_id',$quality)->where('type_id',$type);
                        $conddata =$cond->first();
                        $cond->update(['to'=>Carbon::now()]);
                        // return 'get last buy price';

                        $buyCoast = 0 ;
                        if($type == 1){
                            $row = AllPart::where('part_id',$part_id)->where('source_id',$source)->where('status_id',$status)->where('quality_id',$quality)->with('order_supplier')->orderBy('id','DESC')->first();
                            $cureencyId = $row->order_supplier->currency_id;
                            $cureencydate = $row->order_supplier->confirmation_date;

                            $currency_data=CurrencyType::with(['currencies'=>function($query) use($cureencyId , $cureencydate){
                                return $query->where('from','>=',$cureencydate)->where('to','<=',$cureencydate)->where('currency_id',$cureencyId)->orWhere('to','=',null);
                            }])->where('id',$cureencyId)->first();


                            $buyCoast = $row->buy_price * $currency_data->currencies[0]->value;
                            $buyCoast += $row->buy_costing;

                        }elseif($type == 2){
                            $row = AllWheel::where('part_id',$part_id)->where('source_id',$source)->where('status_id',$status)->where('quality_id',$quality)->with('order_supplier')->orderBy('id','DESC')->first();
                            $cureencyId = $row->order_supplier->currency_id;
                            $cureencydate = $row->order_supplier->confirmation_date;

                            $currency_data=CurrencyType::with(['currencies'=>function($query) use($cureencyId , $cureencydate){
                                return $query->where('from','>=',$cureencydate)->where('to','<=',$cureencydate)->where('currency_id',$cureencyId)->orWhere('to','=',null);
                            }])->where('id',$cureencyId)->first();


                            $buyCoast = $row->buy_price * $currency_data->currencies[0]->value;
                            $buyCoast += $row->buy_costing;

                        }elseif($type == 3){
                            $row = AllTractor::where('part_id',$part_id)->where('source_id',$source)->where('status_id',$status)->where('quality_id',$quality)->with('order_supplier')->orderBy('id','DESC')->first();
                            $cureencyId = $row->order_supplier->currency_id;
                            $cureencydate = $row->order_supplier->confirmation_date;

                            $currency_data=CurrencyType::with(['currencies'=>function($query) use($cureencyId , $cureencydate){
                                return $query->where('from','>=',$cureencydate)->where('to','<=',$cureencydate)->where('currency_id',$cureencyId)->orWhere('to','=',null);
                            }])->where('id',$cureencyId)->first();


                            $buyCoast = $row->buy_price * $currency_data->currencies[0]->value;
                            $buyCoast += $row->buy_costing;

                        }elseif($type == 4){
                            $row = AllClark::where('part_id',$part_id)->where('source_id',$source)->where('status_id',$status)->where('quality_id',$quality)->with('order_supplier')->orderBy('id','DESC')->first();
                            $cureencyId = $row->order_supplier->currency_id;
                            $cureencydate = $row->order_supplier->confirmation_date;

                            $currency_data=CurrencyType::with(['currencies'=>function($query) use($cureencyId , $cureencydate){
                                return $query->where('from','>=',$cureencydate)->where('to','<=',$cureencydate)->where('currency_id',$cureencyId)->orWhere('to','=',null);
                            }])->where('id',$cureencyId)->first();


                            $buyCoast = $row->buy_price * $currency_data->currencies[0]->value;
                            $buyCoast += $row->buy_costing;

                        }elseif($type == 5){
                            $row = AllEquip::where('part_id',$part_id)->where('source_id',$source)->where('status_id',$status)->where('quality_id',$quality)->with('order_supplier')->orderBy('id','DESC')->first();
                            $cureencyId = $row->order_supplier->currency_id;
                            $cureencydate = $row->order_supplier->confirmation_date;

                            $currency_data=CurrencyType::with(['currencies'=>function($query) use($cureencyId , $cureencydate){
                                return $query->where('from','>=',$cureencydate)->where('to','<=',$cureencydate)->where('currency_id',$cureencyId)->orWhere('to','=',null);
                            }])->where('id',$cureencyId)->first();


                            $buyCoast = $row->buy_price * $currency_data->currencies[0]->value;
                            $buyCoast += $row->buy_costing;

                        }elseif($type == 6){
                            $row = AllKit::where('part_id',$part_id)->where('source_id',$source)->where('status_id',$status)->where('quality_id',$quality)->with('order_supplier')->orderBy('id','DESC')->first();
                            $cureencyId = $row->order_supplier->currency_id;
                            $cureencydate = $row->order_supplier->confirmation_date;

                            $currency_data=CurrencyType::with(['currencies'=>function($query) use($cureencyId , $cureencydate){
                                return $query->where('from','>=',$cureencydate)->where('to','<=',$cureencydate)->where('currency_id',$cureencyId)->orWhere('to','=',null);
                            }])->where('id',$cureencyId)->first();


                            $buyCoast = $row->buy_price * $currency_data->currencies[0]->value;
                            $buyCoast += $row->buy_costing;

                        }

                        $pricing = new SalePricing();
                        $pricing->part_id = $part_id;
                        $pricing->source_id = $source;
                        $pricing->status_id = $status;
                        $pricing->quality_id = $quality;
                        $pricing->from = Carbon::now();
                        $pricing->to = null;
                        $pricing->type_id = $type;
                        $pricing->currency_id = 400;
                        $pricing->sale_type = $saleTypeid;
                        $pricing->price = $buyCoast + ($buyCoast * floatval($request->values[$i]) / 100) ;
                        $pricing->save();

                    }else{

                    }



                }


        }

        /// ratio///////////////////////////

        for ($i=0; $i < count($saleTypes) ; $i++) {
            $saleValue = $request->values[$i];
            $sale_typex = $saleTypes[$i];

            if(floatval($saleValue) > 0 ){
                $oldSaleTypeRatio = SaleTypeRatio::where('sale_type_id',$sale_typex)->where('to',null)->where('type',$request->part_type)->get();
                if(count($oldSaleTypeRatio) > 0){
                    // close and open
                    SaleTypeRatio::where('sale_type_id',$sale_typex)->where('to',null)->where('type',$request->part_type)->update([
                        'to' => Carbon::now()
                    ]);
                    SaleTypeRatio::create([
                        'sale_type_id' => $sale_typex,
                        'value' => $saleValue,
                        'from' => Carbon::now(),
                        'to' => null,
                        'user_id' => auth()->user()->id,
                        'type' => $request->part_type,
                        'notes' => 'طبقا لاخر سعر شراء'
                    ]);
                }else{
                    // open
                    SaleTypeRatio::create([
                        'sale_type_id' => $sale_typex,
                        'value' => $saleValue,
                        'from' => Carbon::now(),
                        'to' => null,
                        'user_id' => auth()->user()->id,
                        'type' => $request->part_type,
                        'notes' => 'طبقا لاخر سعر شراء'
                    ]);
                }
            }
        }

        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->back();


    }

    public function simplePriceList(Request $request){
        $pricingTypes = PricingType::orderBy('id','asc')->get();
        $currency = CurrencyType::with(['currencies'=>function($query){
            return $query->where('to',null);
        }])->get();
         $currencyHistory = Currency::where('to',null)->with('currency_type')->get();
        //  $data =  SalePricing::where('to',null)->whereHas('part', function ($query) {
        //     return $query->where('type_id', '=', 1);
        // })->orwhereHas('wheel', function ($query) {
        //     return $query->where('type_id', '=', 2);
        // })->orwhereHas('tractor', function ($query) {
        //     return $query->where('type_id', '=', 3);
        // })->orwhereHas('clarck', function ($query) {
        //     return $query->where('type_id', '=', 4);
        // })->orwhereHas('equip', function ($query) {
        //     return $query->where('type_id', '=', 5);
        // })->orwhereHas('kit', function ($query) {
        //     return $query->where('type_id', '=', 6);
        // })->with('part')->with('wheel')->with('tractor')->with('equip')->with('clarck')->with('kit')->with('source')->with('status')->with('part_quality')->with('sale_type')->orderBy('part_id', 'asc')->orderBy('sale_type', 'asc')->get();
        //  if ($request->ajax()) {
        //     return view('simplePriceList',compact('data','pricingTypes','currency','currencyHistory'));
        // }
        return view('simplePriceList',compact('pricingTypes','currency','currencyHistory'));
    }

    public function allprice(Request $request){

        if ($request->ajax()) {
            $data =  SalePricing::whereNull('to')
                    ->where(function ($query) {
                        $query->whereHas('part', function ($query) {
                            $query->where('type_id', 1);
                        })
                        ->orWhereHas('wheel', function ($query) {
                            $query->where('type_id', 2);
                        })
                        ->orWhereHas('tractor', function ($query) {
                            $query->where('type_id', 3);
                        })
                        ->orWhereHas('clarck', function ($query) {
                            $query->where('type_id', 4);
                        })
                        ->orWhereHas('equip', function ($query) {
                            $query->where('type_id', 5);
                        })
                        ->orWhereHas('kit', function ($query) {
                            $query->where('type_id', 6);
                        });
                    })
                    ->with(['part.getsmallunit.unit', 'wheel', 'tractor', 'clarck', 'equip', 'kit', 'source', 'status', 'part_quality', 'sale_typex'])
                    ->orderBy('part_id', 'asc')
                    ->orderBy('sale_type', 'asc')
                    ->get();
        // return $data;
            return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){
                $btn='No Name';
                if($row->type_id == 1){

                    return $row->part->smallunit->name .'-'. $row->part->name;
                }elseif($row->type_id == 6){
                    return $row->kit->name;
                }
                elseif($row->type_id == 2){
                    return $row->wheel->name;
                } elseif($row->type_id == 3){
                    return $row->tractor->name;
                } elseif($row->type_id == 4){
                    return $row->clarck->name;
                } elseif($row->type_id == 5){
                    return $row->equip->name;
                }

            })
            ->addColumn('units', function($row){
                $btn='';
                if($row->type_id == 1){
                    $btn= '<input type="hidden" name="smallUnit" class="smallunits" value="'.$row->part->small_unit.'">';
                    $btn.= '<select  class="form-control-sm unitsCls"><option selected disabled value="">Select Unit</option>';
                    foreach ($row->part->getsmallunit as $key => $unitt) {
                        $btn .='<option value="'.$unitt->unit->id.'">'.$unitt->unit->name.'</option>';
                    }
                    $btn.= '</select>';
                    return $btn;
                }else{
                     return 'قطعه';
                }


            })
            ->addColumn('source', function($row){
                $btn='No source';

                return $row->source->name_arabic;


            })
            ->addColumn('status', function($row){
                $btn='No status';

                return $row->status->name;


            }) ->addColumn('quality', function($row){
                $btn='No quality';

                return $row->part_quality->name;


            }) ->addColumn('price', function($row){
                $btn='0';

                return '<input type="text" value="'.$row->price.'" class="priceVal bg-transparent form-control text-center fw-bold border-0" >';


            })->addColumn('sale_type', function($row){
                $btn='0';
                if(isset($row->sale_typex)){
                    return $row->sale_typex->type;
                }else{
                    return $btn;
                }


            })
            ->setRowClass(function ($row) {
                return isset($row->sale_typex) && $row->sale_typex->id == 5 ? 'highlight-row' : '';
            })
            ->addColumn('action', function($row){

                if($row->type_id == 1){
                    return '<button class="btn fs-22 editpriceBtn" part-id="'.$row->part->id.'" source-id="'.$row->source->id.'" status-id="'.$row->status->id.'" quality-id="'.$row->part_quality->id.'" type-id="1" sale_type-id="'.$row->sale_type.'"><i class="bx bx-edit"></i></button><button class="btn btn-danger savepriceBtn fs-22" style="display:none;"  part-id="'.$row->part->id.'" source-id="'.$row->source->id.'" status-id="'.$row->status->id.'" quality-id="'.$row->part_quality->id.'" type-id="1" sale_type-id="'.$row->sale_type.'"><i class="bx bx-save"></i></button>';
                }elseif($row->type_id == 6){
                    return '<button class="btn fs-22 editpriceBtn" part-id="'.$row->kit->id.'" source-id="'.$row->source->id.'" status-id="'.$row->status->id.'" quality-id="'.$row->part_quality->id.'" type-id="6" sale_type-id="'.$row->sale_type.'"><i class="bx bx-edit"></i></button><button class="btn btn-danger savepriceBtn fs-22" style="display:none;"  part-id="'.$row->kit->id.'" source-id="'.$row->source->id.'" status-id="'.$row->status->id.'" quality-id="'.$row->part_quality->id.'" type-id="6" sale_type-id="'.$row->sale_type.'"><i class="bx bx-save"></i></button>';
                }
                elseif($row->type_id == 2){
                    return '<button class="btn fs-22 editpriceBtn" part-id="'.$row->wheel->id.'" source-id="'.$row->source->id.'" status-id="'.$row->status->id.'" quality-id="'.$row->part_quality->id.'" type-id="2" sale_type-id="'.$row->sale_type.'"><i class="bx bx-edit"></i></button><button class="btn btn-danger savepriceBtn fs-22" style="display:none;"  part-id="'.$row->wheel->id.'" source-id="'.$row->source->id.'" status-id="'.$row->status->id.'" quality-id="'.$row->part_quality->id.'" type-id="2" sale_type-id="'.$row->sale_type.'"><i class="bx bx-save"></i></button>';
                } elseif($row->type_id == 3){
                    return '<button class="btn fs-22 editpriceBtn" part-id="'.$row->tractor->id.'" source-id="'.$row->source->id.'" status-id="'.$row->status->id.'" quality-id="'.$row->part_quality->id.'" type-id="3" sale_type-id="'.$row->sale_type.'"><i class="bx bx-edit"></i></button><button class="btn btn-danger savepriceBtn fs-22" style="display:none;"  part-id="'.$row->tractor->id.'" source-id="'.$row->source->id.'" status-id="'.$row->status->id.'" quality-id="'.$row->part_quality->id.'" type-id="3" sale_type-id="'.$row->sale_type.'"><i class="bx bx-save"></i></button>';
                } elseif($row->type_id == 4){
                    return '<button class="btn fs-22 editpriceBtn" part-id="'.$row->clarck->id.'" source-id="'.$row->source->id.'" status-id="'.$row->status->id.'" quality-id="'.$row->part_quality->id.'" type-id="4" sale_type-id="'.$row->sale_type.'"><i class="bx bx-edit"></i></button><button class="btn btn-danger savepriceBtn fs-22" style="display:none;"  part-id="'.$row->clarck->id.'" source-id="'.$row->source->id.'" status-id="'.$row->status->id.'" quality-id="'.$row->part_quality->id.'" type-id="4" sale_type-id="'.$row->sale_type.'"><i class="bx bx-save"></i></button>';
                } elseif($row->type_id == 5){
                    return '<button class="btn fs-22 editpriceBtn" part-id="'.$row->equip->id.'" source-id="'.$row->source->id.'" status-id="'.$row->status->id.'" quality-id="'.$row->part_quality->id.'" type-id="5" sale_type-id="'.$row->sale_type.'"><i class="bx bx-edit"></i></button><button class="btn btn-danger savepriceBtn fs-22" style="display:none;"  part-id="'.$row->equip->id.'" source-id="'.$row->source->id.'" status-id="'.$row->status->id.'" quality-id="'.$row->part_quality->id.'" type-id="5" sale_type-id="'.$row->sale_type.'"><i class="bx bx-save"></i></button>';
                }




            })
            ->addColumn('history', function($row){

                if($row->type_id == 1){
                    return '<button class="btn fs-22 historypriceBtn" part-id="'.$row->part->id.'" source-id="'.$row->source->id.'" status-id="'.$row->status->id.'" quality-id="'.$row->part_quality->id.'" type-id="1" sale_type-id="'.$row->sale_type.'"><i class="bx bx-info-circle text-info"></i></button>';
                }elseif($row->type_id == 6){
                    return '<button class="btn fs-22 historypriceBtn" part-id="'.$row->kit->id.'" source-id="'.$row->source->id.'" status-id="'.$row->status->id.'" quality-id="'.$row->part_quality->id.'" type-id="6" sale_type-id="'.$row->sale_type.'"><i class="bx bx-info-circle text-info"></i></button>';
                }
                elseif($row->type_id == 2){
                    return '<button class="btn fs-22 historypriceBtn" part-id="'.$row->wheel->id.'" source-id="'.$row->source->id.'" status-id="'.$row->status->id.'" quality-id="'.$row->part_quality->id.'" type-id="2" sale_type-id="'.$row->sale_type.'"><i class="bx bx-info-circle text-info"></i></button>';
                } elseif($row->type_id == 3){
                    return '<button class="btn fs-22 historypriceBtn" part-id="'.$row->tractor->id.'" source-id="'.$row->source->id.'" status-id="'.$row->status->id.'" quality-id="'.$row->part_quality->id.'" type-id="3" sale_type-id="'.$row->sale_type.'"><i class="bx bx-info-circle text-info"></i></button>';
                } elseif($row->type_id == 4){
                    return '<button class="btn fs-22 historypriceBtn" part-id="'.$row->clarck->id.'" source-id="'.$row->source->id.'" status-id="'.$row->status->id.'" quality-id="'.$row->part_quality->id.'" type-id="4" sale_type-id="'.$row->sale_type.'"><i class="bx bx-info-circle text-info"></i></button>';
                } elseif($row->type_id == 5){
                    return '<button class="btn fs-22 historypriceBtn" part-id="'.$row->equip->id.'" source-id="'.$row->source->id.'" status-id="'.$row->status->id.'" quality-id="'.$row->part_quality->id.'" type-id="5" sale_type-id="'.$row->sale_type.'"><i class="bx bx-info-circle text-info"></i></button>';
                }




            })
            ->rawColumns(['name','source','status','quality','price','sale_type','action','history','units'])

            ->setTotalRecords(25)
            ->make(true);
        }
        // return  $data;
    }

    public function saveprice1(Request $request){
        // return $request;

        $part_id = $request->part_id;
        $price = $request->price;
        $quality_id = $request->quality_id;
        $sale_type_id = $request->sale_type_id;
        $source_id = $request->source_id;
        $status_id = $request->status_id;
        $type_id = $request->type_id;
        $ratiounit = 1;
        if($request->selectedunit){
            $ratiounit = getSmallUnit($request->selectedunit, $request->smallunits);
        }

        SalePricing::where('to',null)->where('part_id',$part_id)->where('sale_type',$sale_type_id)->where('source_id',$source_id)->where('status_id',$status_id)->where('quality_id',$quality_id)->where('type_id',$type_id)->update(['to'=>Carbon::now()]);
        $pricing = new SalePricing();
        $pricing->part_id = $part_id;
        $pricing->source_id = $source_id;
        $pricing->status_id = $status_id;
        $pricing->quality_id = $quality_id;
        $pricing->from = Carbon::now();
        $pricing->to = null;
        $pricing->type_id = $type_id;
        $pricing->currency_id = 400;
        $pricing->sale_type = $sale_type_id;
        $pricing->price = $price / $ratiounit;
        $pricing->save();

        return true;

    }

    public function getprice1history(Request $request){
        // return $request;
        $part_id = $request->part_id;
        $quality_id = $request->quality_id;
        $sale_type_id = $request->sale_type_id;
        $source_id = $request->source_id;
        $status_id = $request->status_id;
        $type_id = $request->type_id;

        $pricing = SalePricing::where('part_id',$part_id)->where('sale_type',$sale_type_id)->where('source_id',$source_id)->where('status_id',$status_id)->where('quality_id',$quality_id)->where('type_id',$type_id)->orderBy('id','DESC')->get();
        $buyInvoices=[];
        if($type_id == 1){
           $buyInvoices =   AllPart::where('part_id', $part_id)
           ->where('source_id', $source_id)
           ->where('status_id', $status_id)
           ->where('quality_id', $quality_id)
           ->orderBy('id', 'DESC')
          ->with('order_supplier.currency_type.currencies')
           ->with('order_supplier.supplier')
           ->get();




        }elseif($type_id == 2){
            $buyInvoices =   AllWheel::where('part_id', $part_id)
            ->where('source_id', $source_id)
            ->where('status_id', $status_id)
            ->where('quality_id', $quality_id)
            ->orderBy('id', 'DESC')
           ->with('order_supplier.currency_type.currencies')
           ->with('order_supplier.supplier')
            ->get();





        }elseif($type_id == 3){
            $buyInvoices =   AllTractor::where('part_id', $part_id)
            ->where('source_id', $source_id)
            ->where('status_id', $status_id)
            ->where('quality_id', $quality_id)
            ->orderBy('id', 'DESC')
           ->with('order_supplier.currency_type.currencies')
           ->with('order_supplier.supplier')
            ->get();





        }elseif($type_id == 4){
            $buyInvoices =   AllClark::where('part_id', $part_id)
            ->where('source_id', $source_id)
            ->where('status_id', $status_id)
            ->where('quality_id', $quality_id)
            ->orderBy('id', 'DESC')
           ->with('order_supplier.currency_type.currencies')
           ->with('order_supplier.supplier')
            ->get();





        }elseif($type_id == 5){
            $buyInvoices =   AllEquip::where('part_id', $part_id)
            ->where('source_id', $source_id)
            ->where('status_id', $status_id)
            ->where('quality_id', $quality_id)
            ->orderBy('id', 'DESC')
           ->with('order_supplier.currency_type.currencies')
           ->with('order_supplier.supplier')
            ->get();





        }elseif($type_id == 6){
            $buyInvoices =   AllKit::where('part_id', $part_id)
            ->where('source_id', $source_id)
            ->where('status_id', $status_id)
            ->where('quality_id', $quality_id)
            ->orderBy('id', 'DESC')
           ->with('order_supplier.currency_type.currencies')
           ->with('order_supplier.supplier')
            ->get();





        }


        foreach ($buyInvoices as $key => $invoice) {
            $invoice['currency_value'] =  CurrencyType::with([
                'currencies' => function ($query) use ($invoice) {
                    return $query->where('from', '>=', $invoice->order_supplier->confirmation_date)->where('to', '<=', $invoice->order_supplier->confirmation_date)->where('currency_id', $invoice->order_supplier->currency_id)->orWhere('to', '=', null);
                },
            ])->where('id', $invoice->order_supplier->currency_id)->get();
       }
    //     $lasts=  DB::table('pricing_type')
    //   ->leftjoin('sale_pricing', 'pricing_type.id', '=', 'sale_pricing.sale_type')
    //   ->whereNull('sale_pricing.to')
    //   ->where('part_id',$part_id)
    //   ->where('source_id',$source_id)
    //   ->where('status_id',$status_id)
    //   ->where('quality_id',$quality_id)
    //   ->where('type_id',$type_id)
    //   ->select('*')
    //   ->get();
    
    $lasts = DB::table('pricing_type')
    ->leftJoin('sale_pricing', function($join) use ($part_id, $source_id, $status_id, $quality_id, $type_id) {
        $join->on('pricing_type.id', '=', 'sale_pricing.sale_type')
             ->whereNull('sale_pricing.to')
             ->where('sale_pricing.part_id', $part_id)
             ->where('sale_pricing.source_id', $source_id)
             ->where('sale_pricing.status_id', $status_id)
             ->where('sale_pricing.quality_id', $quality_id)
             ->where('sale_pricing.type_id', $type_id);
    })
    ->select(
        'pricing_type.*',
        DB::raw('COALESCE(sale_pricing.price, 0) as price'),  // Fallback value of 0 for price
        DB::raw('COALESCE(sale_pricing.part_id, '.$part_id.') as part_id'),  // Fallback to $part_id if no match
        DB::raw('COALESCE(sale_pricing.source_id, '.$source_id.') as source_id'),  // Fallback to $source_id if no match
        DB::raw('COALESCE(sale_pricing.status_id, '.$status_id.') as status_id'),  // Fallback to $status_id if no match
        DB::raw('COALESCE(sale_pricing.quality_id, '.$quality_id.') as quality_id'),  // Fallback to $quality_id if no match
        DB::raw('COALESCE(sale_pricing.type_id, '.$type_id.') as type_id'),  // Fallback to $type_id if no match
        DB::raw('COALESCE(sale_pricing.sale_type, pricing_type.id) as sale_type')
    )
    ->get();





       return [ "pricingList" => $pricing , "buyInvoices" => $buyInvoices , "lasts" => $lasts ];
    }

    public function get_pricing_ratio_dynamic($type){
            // return $type;
            $data = new Collection();
            if ($type == 1) {
                // ->where('sale_type',5)->orderBy('id', 'desc')
                $firstPart =  SalePricing::where('type_id',$type)
                ->where('to',null)
                ->where('sale_type',5)//قطاعى
                ->orderBy('id', 'desc')
                ->first();
                if($firstPart){
    
                }else{
                     return false
                ;}
                  $data =  SalePricing::whereNull('to')
                ->with(['part', 'sale_typex'])
                ->with('all_part')
                ->where('part_id',$firstPart->part_id)
                ->where('source_id',$firstPart->source_id)
                ->where('status_id',$firstPart->status_id)
                ->where('quality_id',$firstPart->quality_id)
    
                ->get();
    
                $conf_date = $data[0]->all_part[0]->order_supplier->confirmation_date;
                $curr_id = $data[0]->all_part[0]->order_supplier->currency_id;
    
                $currency_value =  CurrencyType::with([
                    'currencies' => function ($query) use ($conf_date , $curr_id) {
                        return $query->where('from', '>=', $conf_date)->where('to', '<=', $conf_date)->where('currency_id', $curr_id)->orWhere('to', '=', null);
                    },
                ])->where('id', $curr_id)->get();
    
                foreach ($data as $key => $value) {
                    $value['ratio'] = ($value->price - ( $value->all_part[0]->buy_price *  $currency_value[0]->currencies[0]->value)) / ( $value->all_part[0]->buy_price *  $currency_value[0]->currencies[0]->value) * 100;
                }
                return $data;
    
            } else if($type == 2){
                $firstPart =  SalePricing::where('type_id',$type)
                ->where('to',null)
                ->where('sale_type',5)
                ->orderBy('id', 'desc')
                ->first();
               if($firstPart){}else{ return false ;}
                $data =  SalePricing::
                with(['wheel', 'sale_typex'])
                ->with('all_wheel.order_supplier.currency_type')
                ->where('part_id',$firstPart->part_id)
                ->where('source_id',$firstPart->source_id)
                ->where('status_id',$firstPart->status_id)
                ->where('quality_id',$firstPart->quality_id)
                ->where('to',null)
                // ->limit(3)
                ->get();
    
                $conf_date = $data[0]->all_wheel[0]->order_supplier->confirmation_date;
                $curr_id = $data[0]->all_wheel[0]->order_supplier->currency_id;
    
                $currency_value =  CurrencyType::with([
                    'currencies' => function ($query) use ($conf_date , $curr_id) {
                        return $query->where('from', '>=', $conf_date)->where('to', '<=', $conf_date)->where('currency_id', $curr_id)->orWhere('to', '=', null);
                    },
                ])->where('id', $curr_id)->get();
    
                foreach ($data as $key => $value) {
                    $value['ratio'] = ($value->price - ( $value->all_wheel[0]->buy_price *  $currency_value[0]->currencies[0]->value)) / ( $value->all_wheel[0]->buy_price *  $currency_value[0]->currencies[0]->value) * 100;
                }
                return $data;
    
            }else if($type == 3){
                $firstPart =  SalePricing::where('type_id',$type)
                ->where('to',null)
                ->where('sale_type',5)
                ->orderBy('id', 'desc')
                ->first();
                if($firstPart){}else{ return false ;}
                $data =  SalePricing::whereNull('to')
                ->with(['tractor', 'sale_typex'])
                ->with('all_tractor.order_supplier.currency_type')
                ->where('part_id',$firstPart->part_id)
                ->where('source_id',$firstPart->source_id)
                ->where('status_id',$firstPart->status_id)
                ->where('quality_id',$firstPart->quality_id)
                // ->limit(3)
                ->get();
    
                $conf_date = $data[0]->all_tractor[0]->order_supplier->confirmation_date;
                $curr_id = $data[0]->all_tractor[0]->order_supplier->currency_id;
    
                $currency_value =  CurrencyType::with([
                    'currencies' => function ($query) use ($conf_date , $curr_id) {
                        return $query->where('from', '>=', $conf_date)->where('to', '<=', $conf_date)->where('currency_id', $curr_id)->orWhere('to', '=', null);
                    },
                ])->where('id', $curr_id)->get();
    
                foreach ($data as $key => $value) {
                    $value['ratio'] = ($value->price - ( $value->all_tractor[0]->buy_price *  $currency_value[0]->currencies[0]->value)) / ( $value->all_tractor[0]->buy_price *  $currency_value[0]->currencies[0]->value) * 100;
                }
                return $data;
            }else if($type == 4){
                $firstPart =  SalePricing::where('type_id',$type)
                ->where('to',null)
                ->where('sale_type',5)
                ->orderBy('id', 'desc')
                ->first();
                if($firstPart){}else{ return false ;}
                $data =  SalePricing::whereNull('to')
                ->with(['clarck', 'sale_typex'])
                ->with('all_clarck.order_supplier.currency_type')
                ->where('part_id',$firstPart->part_id)
                ->where('source_id',$firstPart->source_id)
                ->where('status_id',$firstPart->status_id)
                ->where('quality_id',$firstPart->quality_id)
                // ->limit(3)
                ->get();
    
                $conf_date = $data[0]->all_clarck[0]->order_supplier->confirmation_date;
                $curr_id = $data[0]->all_clarck[0]->order_supplier->currency_id;
    
                $currency_value =  CurrencyType::with([
                    'currencies' => function ($query) use ($conf_date , $curr_id) {
                        return $query->where('from', '>=', $conf_date)->where('to', '<=', $conf_date)->where('currency_id', $curr_id)->orWhere('to', '=', null);
                    },
                ])->where('id', $curr_id)->get();
    
                foreach ($data as $key => $value) {
                    $value['ratio'] = ($value->price - ( $value->all_clarck[0]->buy_price *  $currency_value[0]->currencies[0]->value)) / ( $value->all_clarck[0]->buy_price *  $currency_value[0]->currencies[0]->value) * 100;
                }
                return $data;
            }else if($type == 5){
                $firstPart =  SalePricing::where('type_id',$type)
                ->where('to',null)
                ->where('sale_type',5)
                ->orderBy('id', 'desc')
                ->first();
                if($firstPart){}else{ return false;}
                $data =  SalePricing::whereNull('to')
                ->with(['equip', 'sale_typex'])
                ->with('all_equip.order_supplier.currency_type')
                ->where('part_id',$firstPart->part_id)
                ->where('source_id',$firstPart->source_id)
                ->where('status_id',$firstPart->status_id)
                ->where('quality_id',$firstPart->quality_id)
                // ->limit(3)
                ->get();
    
                $conf_date = $data[0]->all_equip[0]->order_supplier->confirmation_date;
                $curr_id = $data[0]->all_equip[0]->order_supplier->currency_id;
    
                $currency_value =  CurrencyType::with([
                    'currencies' => function ($query) use ($conf_date , $curr_id) {
                        return $query->where('from', '>=', $conf_date)->where('to', '<=', $conf_date)->where('currency_id', $curr_id)->orWhere('to', '=', null);
                    },
                ])->where('id', $curr_id)->get();
    
                foreach ($data as $key => $value) {
                    $value['ratio'] = ($value->price - ( $value->all_equip[0]->buy_price *  $currency_value[0]->currencies[0]->value)) / ( $value->all_equip[0]->buy_price *  $currency_value[0]->currencies[0]->value) * 100;
                }
                return $data;
            }else if($type == 6){
                $firstPart =  SalePricing::where('type_id',$type)
                ->where('to',null)
                ->where('sale_type',5)
                ->orderBy('id', 'desc')
                ->first();
                if($firstPart){}else{ return false ;}
                $data =  SalePricing::whereNull('to')
                ->with(['kit', 'sale_typex'])
                ->with('all_kit.order_supplier.currency_type')
                ->where('part_id',$firstPart->part_id)
                ->where('source_id',$firstPart->source_id)
                ->where('status_id',$firstPart->status_id)
                ->where('quality_id',$firstPart->quality_id)
                // ->limit(3)
                ->get();
    
                $conf_date = $data[0]->all_kit[0]->order_supplier->confirmation_date;
                $curr_id = $data[0]->all_kit[0]->order_supplier->currency_id;
    
                $currency_value =  CurrencyType::with([
                    'currencies' => function ($query) use ($conf_date , $curr_id) {
                        return $query->where('from', '>=', $conf_date)->where('to', '<=', $conf_date)->where('currency_id', $curr_id)->orWhere('to', '=', null);
                    },
                ])->where('id', $curr_id)->get();
    
                foreach ($data as $key => $value) {
                    $value['ratio'] = ($value->price - ( $value->all_kit[0]->buy_price *  $currency_value[0]->currencies[0]->value)) / ( $value->all_kit[0]->buy_price *  $currency_value[0]->currencies[0]->value) * 100;
                }
                return $data;
            }
    
    
        }
        
    public function get_pricing_ratio($type){

        $data=SaleTypeRatio::where('type',$type)->where('to',null)->with('pricing_type')->get();
        return $data;
    }
    }
