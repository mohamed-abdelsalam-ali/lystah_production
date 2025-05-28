<?php

namespace App\Http\Controllers;

use App\Models\WheelDimension;
use Illuminate\Http\Request;

class WheelDimensionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return WheelDimension::all();
        
    }
    public function index_dim(){
        $all_dimention= WheelDimension::all();
        return view('wheel.wheelDimention',compact('all_dimention'));
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
        WheelDimension::create([
            'dimension'=>$request->name,
            'notes'=>$request->desc

        ]);
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(WheelDimension $wheelDimension)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WheelDimension $wheelDimension)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WheelDimension $wheelDimension)
    {
        //
        $Dimension=WheelDimension::where('id',$request->dimention_id)->first();
        $Dimension->update([
            'dimension'=>$request->name,
            'notes'=>$request->desc
        ]);
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,WheelDimension $wheelDimension)
    {
        //
        $dimention=WheelDimension::where('id',$request->dimention_id)->first();
        $dimention->delete();
        session()->flash("success", "تم حذف بنجاح");
        return redirect()->back();
    }
}
