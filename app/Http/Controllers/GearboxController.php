<?php

namespace App\Http\Controllers;

use App\Models\Gearbox;
use Illuminate\Http\Request;

class GearboxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_gearboxes =Gearbox::all();
        return view('gearbox.index',compact('all_gearboxes'));
    }
    
    public function indexdata()
    {
        
        return $all_gearboxes =Gearbox::all();
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
        Gearbox::create([
            'gearname'=>$request->gearname,
           
        ]);
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('gearbox.index');
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
         $gearbox=Gearbox::where('id',$request->gearbox_id)->first();
         $gearbox->update([
            'gearname'=>$request->gearname,
         ]);
         session()->flash("success", "تم التعديل بنجاح");
         return redirect()->route('gearbox.index');
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //  return $request;
         $gearbox=Gearbox::where('id',$request->gearbox_id)->first();
         $gearbox->delete();
         session()->flash("success", "تم حذف بنجاح");
         return redirect()->route('gearbox.index');
    }
    public function get_all_gearbox(){
         return Gearbox::all();
    }
}
