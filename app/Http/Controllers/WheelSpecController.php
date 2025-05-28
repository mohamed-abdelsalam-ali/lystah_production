<?php

namespace App\Http\Controllers;

use App\Models\WheelSpec;
use Illuminate\Http\Request;

class WheelSpecController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return WheelSpec::where('general_flag','0')->get();
        
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
    }

    /**
     * Display the specified resource.
     */
    public function show(WheelSpec $wheelSpec)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WheelSpec $wheelSpec)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WheelSpec $wheelSpec)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WheelSpec $wheelSpec)
    {
        //
    }
}
