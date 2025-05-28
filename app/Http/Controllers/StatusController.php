<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_status=Status::all();
        // return 'ahmed';
        // return $all_status;
        return view('status.index',compact('all_status'));
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
        Status::create([
            'name'=>$request->name,
           
        ]);
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('all_status.index');

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
        $status=Status::where('id',$request->status_id)->first();
        $status->update([
            'name'=>$request->name,

        ]);
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('all_status.index');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // return $request;
        $status=Status::where('id',$request->status_id)->first();
        $status->delete();
        session()->flash("success", "تم الحذف بنجاح");
        return redirect()->route('all_status.index');


    }
}
