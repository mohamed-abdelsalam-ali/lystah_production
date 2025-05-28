<?php

namespace App\Http\Controllers;


use App\Models\InvoiceItem;
use App\Models\PricingType;
use App\Models\SalePricing;
use Illuminate\Http\Request;

class PricingTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_pricing_types=PricingType::all();
        return view('pricing type.index',compact('all_pricing_types'));
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
        //   return $request;
          PricingType::create([
            'type'=>$request->type,
            'desc'=>$request->desc
           
        ]);
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('pricing_type.index');
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
        $pricing_type=PricingType::where('id',$request->pricing_type_id)->first();
        $pricing_type->update([
            'type'=>$request->type,
            'desc'=>$request->desc
        ]);
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('pricing_type.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
          $all_sell_invoices=InvoiceItem::where('sale_type',$request->pricing_type_id)->get();
        if(count($all_sell_invoices) > 0){
            session()->flash("error", " لا يمكن الحذف لان التسعيرة موجودة بفاتورة بيع");
            return redirect()->route('pricing_type.index');
        }else{
            SalePricing::where('sale_type',$request->pricing_type_id)->delete();
            $pricing_type=PricingType::where('id',$request->pricing_type_id)->first();
            $pricing_type->delete();

            session()->flash("success", "تم حذف بنجاح");
            return redirect()->route('pricing_type.index');
        }


    }
}
