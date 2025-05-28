<?php

namespace App\Http\Controllers;

use App\Models\CatalogImage;
use App\Models\Group;
use App\Models\SubGroup;
use Illuminate\Http\Request;

class SubGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $all_sub_groups= SubGroup::with('group')->get();
        $groups=Group::all();
        return view('groups.sub_group',compact('all_sub_groups','groups'));

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
        if ($request->hasfile('sub_group_img')) {
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_sub_group = $time . '-' . $request->name . '-' . $request->sub_group_img->getClientOriginalName();
            $request->sub_group_img->move(public_path('sub_group_images'), $image_sub_group);
            SubGroup::create([
                'name' => $request->name,
                'group_id' => $request->group_id,
                'desc' => $request->desc,
                'sub_group_img' => $image_sub_group

            ]);
        } else {
            SubGroup::create([
                'name' => $request->name,
                'group_id' => $request->group_id,
                'desc' => $request->desc,
            ]);
        }
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('sub_group.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubGroup $subGroup )
    {
        //


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubGroup $subGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // return $request;
        $sub_group=SubGroup::where('id',$request->sub_group_id)->first();
        // return $company;
        if ($request->hasfile('sub_group_img')){
            if(isset($sub_group->sub_group_img))
            {
             $image_path = public_path() . '/sub_group_images' . '/' . $sub_group->sub_group_img;
             unlink($image_path);

            }
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_sub_group = $time . '-' . $request->name . '-' . $request->sub_group_img->getClientOriginalName();
            $request->sub_group_img->move(public_path('sub_group_images'), $image_sub_group);
            $sub_group->update([
                'name' => $request->name,
                'group_id' => $request->group_id,
                'desc' => $request->desc,
                'sub_group_img' => $image_sub_group

            ]);
        }else{
            $sub_group->update([
                'name' => $request->name,
                'group_id' => $request->group_id,
                'desc' => $request->desc,
            ]);

        }
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('sub_group.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // return $request;
        $sub_group=SubGroup::where('id',$request->sub_group_id)->first();
        // return $company;
        if(isset($sub_group->sub_group_img))
        {
         $image_path = public_path() . '/sub_group_images' . '/' . $sub_group->sub_group_img;
         unlink($image_path);

        }
        $sub_group->delete();
        session()->flash("success", "تم الحذف بنجاح");
        return redirect()->route('sub_group.index');


    }
    public function show_catalog($id)
    {
        // return $id;
        $sub_group =SubGroup::where('id',$id)->first();
        // return $sub_group;
        $all_catalogs =CatalogImage::where('sub_group_id',$id)->get();
        // return $all_catalogs;
        return view('groups.catalog_images',compact('all_catalogs','sub_group'));
    }
}
