<?php

namespace App\Http\Controllers;

use App\Models\WheelModel;
use Illuminate\Http\Request;

class WheelModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return WheelModel::all();
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
    public function show(WheelModel $wheelModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WheelModel $wheelModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WheelModel $wheelModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WheelModel $wheelModel)
    {
        //
    }
}
