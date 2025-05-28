<?php

namespace App\Http\Controllers;

use App\Models\NotesSafeMoney;
use Illuminate\Http\Request;

class NoteSafeMoneyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_notes=NotesSafeMoney::where('id',">",10)->get();
        // return $all_notes;
        return view('money_safe.notes',compact('all_notes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        NotesSafeMoney::create([
            'notes'=>$request->notes,
            'desc'=>$request->desc,

        ]);
        session()->flash("success", "تم الإضافة بنجاح");
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $note=NotesSafeMoney::where('id',$request->id)->first();
        $note->update([
            'notes'=>$request->notes,
            'desc'=>$request->desc,


        ]);
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
