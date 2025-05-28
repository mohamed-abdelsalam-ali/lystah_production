<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Supplierbank;
use Illuminate\Http\Request;

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

            ]);

        }
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('supplier.index');

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

            ]);
            
        }
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('supplier.index');
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
        return redirect()->route('supplier.index');

    }
    public function show_account_bank($id)
    {
        $supplier=Supplier::where('id',$id)->first();
        $all_account_bank=Supplierbank::where('supplier_id',$id)->with('supplier')->get();
        // return $all_account_bank;
        return view('supplier.show_account_bank',compact('supplier','all_account_bank'));


    }
}
