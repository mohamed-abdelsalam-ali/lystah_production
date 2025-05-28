<?php

namespace App\Http\Controllers;

use App\Models\WheelMaterial;
use Illuminate\Http\Request;

class WheelMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return WheelMaterial::all();
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
    public function show(WheelMaterial $wheelMaterial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WheelMaterial $wheelMaterial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WheelMaterial $wheelMaterial)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WheelMaterial $wheelMaterial)
    {
        //
    }
}
