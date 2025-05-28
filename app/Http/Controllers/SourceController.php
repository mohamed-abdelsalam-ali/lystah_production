<?php

namespace App\Http\Controllers;

use App\Models\Source;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_sources= Source::all();
        return view('source.index',compact('all_sources'));
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
        Source::create([
            'iso'=>$request->iso,
            'name_en'=>$request->name_en,
            'name_arabic'=>$request->name_arabic

           
        ]);
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('all_source.index');
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
        $source=Source::where('id',$request->source_id)->first();
        $source->update([
            'iso'=>$request->iso,
            'name_en'=>$request->name_en,
            'name_arabic'=>$request->name_arabic

        ]);
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('all_source.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // return $request;
           $source=Source::where('id',$request->source_id)->first();
        $p= AllPart::where('source_id',$request->source_id)->get();
        $w= AllWheel::where('source_id',$request->source_id)->get();
        $k= AllKit::where('source_id',$request->source_id)->get();
        $t= AllTractor::where('source_id',$request->source_id)->get();
        $c= AllClark::where('source_id',$request->source_id)->get();
        $e= AllEquip::where('source_id',$request->source_id)->get();
        if(count($p) > 0 ||count($w) > 0 ||count($k) > 0 ||count($t) > 0 ||count($c) > 0 ||count($e) > 0 ){
            session()->flash("success", " لا يمكن الحذف ");

        }else{
            $source->delete();
            session()->flash("success", "تم الحذف بنجاح");

        }

        return redirect()->route('all_source.index');



    }
}
