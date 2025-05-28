<?php

namespace App\Http\Controllers;

use App\Models\PartQuality;
use Illuminate\Http\Request;

class PartQualityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_parts =PartQuality::all();
        return view('parts.part_quality',compact('all_parts'));
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
        PartQuality::create([
            'name'=>$request->name,
            'note'=>$request->note
           
        ]);
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('part_quality.index');
    
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
        $part_quality=PartQuality::where('id',$request->part_quality_id)->first();
        $part_quality->update([
            'name'=>$request->name,
            'note'=>$request->note
        ]);
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('part_quality.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // return $request;
        $part_quality=PartQuality::where('id',$request->part_quality_id)->first();
        $part_quality->delete();
        session()->flash("success", "تم حذف بنجاح");
        return redirect()->route('part_quality.index');
    }
    public function get_all_p_quality(){
          return PartQuality::all();
    }
}
