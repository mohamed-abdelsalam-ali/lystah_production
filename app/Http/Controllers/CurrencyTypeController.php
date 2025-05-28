<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\CurrencyType;
use Illuminate\Http\Request;

class CurrencyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_currency_types=CurrencyType::all();
        // return $all_currency_types;
        return view('currency.index',compact('all_currency_types'));
    }
    
    public function GetAllCurrency()
    {
        $all_currency_types=CurrencyType::all();
        return $all_currency_types;
        
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
        CurrencyType::create([
            'name'=>$request->name,
            'desc'=>$request->desc
           
        ]);
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('currency.index');
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
        $currency_type=CurrencyType::where('id',$request->currency_id)->first();
        $currency_type->update([
            'name'=>$request->name,
            'desc'=>$request->desc
        ]);
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('currency.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // return $request;
        $currency_type=CurrencyType::where('id',$request->currency_id)->first();
        $currency_type->delete();
        session()->flash("success", "تم حذف بنجاح");
        return redirect()->route('currency.index');


    }
    public function show_currency($id)
    {
        $currency_type=CurrencyType::where('id',$id)->first();
        $all_account_bank=Currency::where('currency_id',$id)->with('currency_type')->get();
        // return $all_account_bank;
        return view('currency.show_current',compact('currency_type','all_account_bank'));


    }
    public function get_all_currency()
    {
    
     return CurrencyType::all();

    }
}
