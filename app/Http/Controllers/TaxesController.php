<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;

class TaxesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_taxes=Tax::all();
        return view('taxes.index',compact('all_taxes'));
    }
     public function indexTax()
    {
        return Tax::all();
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
          Tax::create([
            'name'=>$request->name,
            'value'=>$request->value
           
        ]);
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('taxes.index');
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
        $taxes=Tax::where('id',$request->taxe_id)->first();
        $taxes->update([
            'name'=>$request->name,
            'value'=>$request->value
        ]);
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('taxes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // return $request;
        $taxes=Tax::where('id',$request->taxe_id)->first();
        $taxes->delete();
        session()->flash("success", "تم حذف بنجاح");
        return redirect()->route('taxes.index');


    }
}
