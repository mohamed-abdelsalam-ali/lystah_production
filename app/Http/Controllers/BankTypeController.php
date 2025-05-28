<?php

namespace App\Http\Controllers;

use App\Models\BankType;
use Illuminate\Http\Request;
use App\Models\BranchTree;

class BankTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_bank_types=BankType::all();
        // return $all_bank_types;
        return view('money_safe.bank_type',compact('all_bank_types'));
    }

    public function getAll()
    {
        return $all_bank_types=BankType::all();
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
        
        
        $parentid= BranchTree::where('accountant_number',182)->first()->id;
        $lastchildAccNo = BranchTree::where('parent_id',$parentid)->orderBy('id','DESC')->first()->accountant_number;
        
        $ac_acc = BranchTree::create([
            'name' =>   $request->bank_name,
            'en_name' => $request->bank_name,
            'parent_id' =>  $parentid,
            'accountant_number'=>IntVal($lastchildAccNo)+1
        ])->accountant_number;
        
        BankType::create([
            'bank_name'=>$request->bank_name,
            'account_number'=>$request->account_number,
            'accountant_number' => $ac_acc
        ]);
        
        session()->flash("success", "تم الإضافة بنجاح");
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
        $bank_type=BankType::where('id',$request->id)->first();
        $bank_type->update([
            'bank_name'=>$request->bank_name,
            'account_number'=>$request->account_number,


        ]);
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
