<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\SubGroup;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Group::all();
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
    public function show(Group $group)
    {
        //
        // return "groupxxxxxxxxx";
        // return auth()->user();
        return SubGroup::where('group_id',$group->id)->get();
    }
    public function getsubgroups($group)
    {
        //
        // return $group;
        // return auth()->user();
        return SubGroup::where('group_id',$group)->get();
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        //
    }
}
