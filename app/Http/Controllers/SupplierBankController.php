<?php

namespace App\Http\Controllers;

use App\Models\Supplierbank;
use Illuminate\Http\Request;

class SupplierBankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        Supplierbank::create([
            'supplier_id'=>$request->supplier_id,
            'name'=>$request->name,
            'address'=>$request->address,
            'IBAN'=>$request->IBAN,
            'accountNum'=>$request->accountNum,
        ]);

    
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
        $bank_account=Supplierbank::where('id',$request->bank_account_id)->first();
        // return $bank_account;

        $bank_account->update([
            'name'=>$request->name,
            'address'=>$request->address,
            'IBAN'=>$request->IBAN,
            'accountNum'=>$request->accountNum,

        ]);
        session()->flash("success", "تم التعديل بنجاح");
    return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $bank_account=Supplierbank::where('id',$request->bank_account_id)->first();
        $bank_account->delete();
        session()->flash("success", "تم الحذف بنجاح");
        return redirect()->back();

        


    }
}
