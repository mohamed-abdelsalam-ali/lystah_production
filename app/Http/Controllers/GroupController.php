<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\SubGroup;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $all_groups= Group::all();
        return view('groups.index',compact('all_groups'));
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
        if ($request->hasfile('group_image')){
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_group = $time . '-' . $request->name . '-' . $request->group_image->getClientOriginalName();
            $request->group_image->move(public_path('group_images'), $image_group);
            Group::create([
                'name'=>$request->name,
                'group_img'=>$image_group,
                'desc'=>$request->desc,

            ]);

        }else{
            Group::create([
                'name'=>$request->name,
                'desc'=>$request->desc,

            ]);

        }
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('groups.index');


    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        //
        // return auth()->user();
        return SubGroup::where('group_id', $group->id)->get();
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
    public function update(Request $request)
    {
        // return $request;
        $group=Group::where('id',$request->group_id)->first();
        // return $company;
        if ($request->hasfile('group_img')){
            if(isset($group->group_img))
            {
             $image_path = public_path() . '/group_images' . '/' . $group->group_img;
             unlink($image_path);
     
            }
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_group = $time . '-' . $request->name . '-' . $request->group_img->getClientOriginalName();
            $request->group_img->move(public_path('group_images'), $image_group);
            $group->update([
                'name'=>$request->name,
                'group_img'=>$image_group,
                'desc'=>$request->desc,

            ]);
        }else{
            $group->update([
                'name'=>$request->name,
                'desc'=>$request->desc,

            ]);
            
        }
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('groups.index');
       
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // return $request;
        $group=Group::where('id',$request->group_id)->first();
        // return $group;
        if(isset($group->group_img))
        {
         $image_path = public_path() . '/group_images' . '/' . $group->group_img;
         unlink($image_path);
 
        }
        $group->delete();
        session()->flash("success", "تم الحذف بنجاح");
        return redirect()->route('groups.index');


    }
}