<?php

namespace App\Http\Controllers;

use App\Models\CatalogImage;
use App\Models\SubGroup;
use Illuminate\Http\Request;

class CatalogController extends Controller
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
        // return $request;
        if ($request->hasfile('image_url')){
            $sub_group =SubGroup::where('id',$request->sub_group_id)->first();
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_catalog= $time . '-' . $request->image_url->getClientOriginalName();
            $request->image_url->move(public_path('catalog_images'), $image_catalog);
            CatalogImage::create([
                'sub_group_id'=>$request->sub_group_id,
                'image_url'=>$image_catalog,
                'desc'=>$request->desc,

            ]);
            session()->flash("success", "تم الاضافة بنجاح");
            return redirect()->back();

        }else{
            session()->flash("success", "يرجي ادخال صورة  ");
            return redirect()->back();

        }

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
        $catalog=CatalogImage::where('id',$request->category_id)->first();
        $sub_group =SubGroup::where('id',$request->sub_group_id)->first();
        if ($request->hasfile('image_url')){
            $image_path = public_path() . '/catalog_images' . '/' . $catalog->image_url;
            unlink($image_path);
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_catalog= $time . '-' . $request->image_url->getClientOriginalName();
            $request->image_url->move(public_path('catalog_images'), $image_catalog);
            $catalog->update([
                'sub_group_id'=>$request->sub_group_id,
                'image_url'=>$image_catalog,
                'desc'=>$request->desc,

            ]);


        }else{
            $catalog->update([
                'sub_group_id'=>$request->sub_group_id,
                'desc'=>$request->desc,

            ]);

        }

        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // return $request;
        $catalog=CatalogImage::where('id',$request->catalog_id)->first();
        // return $catalog;
        $image_path = public_path() . '/catalog_images' . '/' . $catalog->image_url;
        unlink($image_path);
        $catalog->delete();
        session()->flash("success", "تم الحذف بنجاح");
        return redirect()->back();
    }
}
