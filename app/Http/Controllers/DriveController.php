<?php

namespace App\Http\Controllers;

use App\Models\Drive;
use Illuminate\Http\Request;

class DriveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_drives =Drive::all();
        return view('drive.index',compact('all_drives'));
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
        Drive::create([
            'name'=>$request->name,
           
        ]);
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('drive.index');
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
        //  return $request;
        $drive=Drive::where('id',$request->drive_id)->first();
        $drive->update([
           'name'=>$request->name,
        ]);
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('drive.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $drive=Drive::where('id',$request->drive_id)->first();
        $drive->delete();
        session()->flash("success", "تم حذف بنجاح");
        return redirect()->route('drive.index');
    }
      public function get_all_drive(){
         return Drive::all();
    }
}
