<?php

namespace App\Http\Controllers;

use App\Models\RelatedWheel;
use Illuminate\Http\Request;

class RelatedWheelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        RelatedWheel::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(RelatedWheel $relatedWheel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RelatedWheel $relatedWheel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RelatedWheel $relatedWheel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RelatedWheel $relatedWheel)
    {
        //
    }
}
