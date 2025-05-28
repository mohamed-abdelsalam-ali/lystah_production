<?php

namespace App\Http\Controllers;

use App\Models\RoleHasPermissions;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles=Role::all();
        $permissions=Permission::all();
        $role_perms=RoleHasPermissions::all();
        return view('policies.role_permission',compact('permissions','roles','role_perms'));

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
        //
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
    public function update(Request $request, $id)
    {
        //
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
    public function save_role_perm(Request $request)
    {
        // return $request;


        if ($request->flagCheckbox == 1) {
            $role = Role::where('id',$request->roleID)->first();
            $role->givePermissionTo($request->perm_id);
            // $role->revokePermissionTo($request->perm_id);
            // $role->syncPermissions(params);
             Artisan::call('permission:cache-reset');
            Artisan::call('optimize:clear');
            
        } else {
           RoleHasPermissions::where('permission_id', '=', $request->perm_id)->where('role_id', $request->roleID)->delete();
        //   $role->syncPermissions(params);
           Artisan::call('permission:cache-reset');
           Artisan::call('optimize:clear');
        }
        
    }
}
