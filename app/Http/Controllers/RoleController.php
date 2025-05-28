<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    //     function __construct()
    // {

    // $this->middleware('permission:role-list ', ['only' => ['index']]);
    // $this->middleware('permission:role-list ', ['only' => ['create','store']]);
    // $this->middleware('permission: role-list', ['only' => ['edit','update']]);
    // $this->middleware('permission: role-list', ['only' => ['destroy']]);

    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();

        return view('policies.role', compact('roles'));
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
        $this->validate($request, [
            'role_name' => 'required|unique:roles,name',
        ]);
        Role::create([
            'name' => $request->role_name,
        ]);
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('role.index');
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
        // return $request;

        $role = Role::findorfail($request->role_id_edit);
        // return $role;
        $role->update([
            $role->name = $request->role_name_edit,
        ]);
        session()->flash("info", "تم التعديل بنجاح");
        return redirect()->route('role.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $role = Role::where('id',$request->role_id_delete);
        $role->delete();
        session()->flash("error",  "تم الحذف بنجاح");
        return redirect()->route('role.index');
    }

}
