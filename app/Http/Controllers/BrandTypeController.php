<?php

namespace App\Http\Controllers;

use App\Models\BrandType;
use Illuminate\Http\Request;

class BrandTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_brans_types = BrandType::all();
        return view('Brand.brand_type', compact('all_brans_types'));
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
        if ($request->hasfile('brand_type_img')) {
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_brand_type = $time . '-' . $request->name . '-' . $request->brand_type_img->getClientOriginalName();
            $request->brand_type_img->move(public_path('brand_type_images'), $image_brand_type);

            BrandType::create([
                'name' => $request->name,
                'desc' => $request->desc,
                'brand_type_img' => $image_brand_type


            ]);
        } else {
            BrandType::create([
                'name' => $request->name,
                'desc' => $request->desc


            ]);
        }
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('brand_type.index');
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
        $brand_type = BrandType::where('id', $request->brand_type_id)->first();
        if ($request->hasfile('brand_type_img')) {
            if (isset($brand_type->brand_type_img)) {
                $image_path = public_path() . '/brand_type_images' . '/' . $brand_type->brand_type_img;
                unlink($image_path);
            }
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_brand_type = $time . '-' . $request->name . '-' . $request->brand_type_img->getClientOriginalName();
            $request->brand_type_img->move(public_path('brand_type_images'), $image_brand_type);
            $brand_type->update([
                'name' => $request->name,
                'desc' => $request->desc,
                'brand_type_img' => $image_brand_type

            ]);
        } else {
            $brand_type->update([
                'name' => $request->name,
                'desc' => $request->desc
            ]);
        }
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('brand_type.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // return $request;
        $brand_type = BrandType::where('id', $request->brand_type_id)->first();
        if (isset($brand_type->brand_type_img)) {
            $image_path = public_path() . '/brand_type_images' . '/' . $brand_type->brand_type_img;
            unlink($image_path);
        }
        $brand_type->delete();
        session()->flash("success", "تم حذف بنجاح");
        return redirect()->route('brand_type.index');
    }
}
