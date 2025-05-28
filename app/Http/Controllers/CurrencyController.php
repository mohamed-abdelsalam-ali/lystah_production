<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\CurrencyType;
use Carbon\Carbon;



use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
           $all_currency_types=CurrencyType::with(['currencies'=>function($query){
                return $query->where('to',null);
            }])->get();
        // return $all_currency_types;
        return view('currency.index',compact('all_currency_types'));
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
        $check_last=Currency::where('currency_id',$request->currency_id)->where('to',null)->get();
        $today = Carbon::now()->toDateString();

        // return $check_last->from;
        if (isset($check_last)) {
            // $check_last->update([
            //     'to'=>$request->from,
            // ]);
            Currency::where('currency_id',$request->currency_id)->where('to',null)->update([
                'to'=>$today,
            ]);
            // return 'ahmed';
        } 
        if (isset($request->from)) {
            Currency::create([
                'currency_id'=>$request->currency_id,
                'value'=>$request->value,
                'from'=>$today,
                'desc'=>$request->desc,
                ]);
        } else {
            Currency::create([
                'currency_id'=>$request->currency_id,
                'value'=>$request->value,
                'from'=>$today,
                'desc'=>$request->desc,
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
        // return $request;
        $currency=Currency::where('id',$request->currency_id)->first();
         $currency->update([
            'value'=>$request->value,
            'desc'=>$request->desc,
         ]);
         session()->flash("success", "تم التعديل بنجاح");
         return redirect()->back();
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // return $request;
        $currency=Currency::where('id',$request->currency_id)->first();
        $currency->delete();
        session()->flash("success", "تم حذف بنجاح");
        return redirect()->back();


    }
  
}
