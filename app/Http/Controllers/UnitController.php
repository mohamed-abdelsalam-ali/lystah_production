<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\UnitValue;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_units = Unit::all();
        return view('unit.index',compact('all_units'));
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
        Unit::create([
            'name'=>$request->name,
            'desc'=>$request->desc

        ]);
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('unit.index');
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
        $unit=Unit::where('id',$request->unit_id)->first();
        $unit->update([
            'name'=>$request->name,
            'desc'=>$request->desc
        ]);
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('unit.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // return $request;
        $unit=Unit::where('id',$request->unit_id)->first();
        $unit->delete();
        session()->flash("success", "تم حذف بنجاح");
        return redirect()->route('unit.index');


    }
    public function unitVal(Request $request)
    {
        // return $request;
        $unit_value=UnitValue::with("unit")->with("p_unit")->get();
        $unit=Unit::all();


      return view('unit.unitval',compact('unit_value','unit'));



    }
    public function unitValAdd(Request $request)
    {
        // return $request;
        UnitValue::create([
            'unit_id'=>$request->unit_a,
            'value'=>$request->value_a,
            'parent_id'=>$request->p_unit_a

        ]);
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->back();
    }


    public function unitValedit(Request $request)
    {
        // return $request;
        $unit=UnitValue::where('id',$request->item_id)->first();
        $unit->update([
            'unit_id'=>$request->unit_e,
            'value'=>$request->value_e,
            'parent_id'=>$request->p_unit_e
        ]);
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->back();
    }
    public function unitValdel(Request $request)
    {
        // return $request;
        $unit=UnitValue::where('id',$request->unit_id_delete)->first();
        $unit->delete();
        session()->flash("success", "تم حذف بنجاح");
        return redirect()->back();


    }
    public function getUnit(Request $request)
    {
                $all_units = Unit::all();
                return   $all_units;



    }

}
