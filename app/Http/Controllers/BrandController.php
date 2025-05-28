<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_brands = Brand::all();
        return view('Brand.index',compact('all_brands'));
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
        if ($request->hasfile('brand_img')) {
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_brand = $time . '-' . $request->name . '-' . $request->brand_img->getClientOriginalName();
            $request->brand_img->move(public_path('brand_images'), $image_brand);
            Brand::create([
                'name'=>$request->name,
                'desc'=>$request->desc,
                'brand_img'=>$image_brand
                
               
            ]);
        }else{
            Brand::create([
                'name'=>$request->name,
                'desc'=>$request->desc
                
               
            ]);
        }
        
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('brand.index');
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
        //  return $request;
         $brand=Brand::where('id',$request->brand_id)->first();
         if ($request->hasfile('brand_img')){
            if(isset($brand->brand_img))
            {
             $image_path = public_path() . '/brand_images' . '/' . $brand->brand_img;
             unlink($image_path);
     
            }
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_brand = $time . '-' . $request->name . '-' . $request->brand_img->getClientOriginalName();
            $request->brand_img->move(public_path('brand_images'), $image_brand);
         $brand->update([
             'name'=>$request->name,
             'desc'=>$request->desc,
             'brand_img'=>$image_brand

         ]);
        }else{
            $brand->update([
                'name'=>$request->name,
                'desc'=>$request->desc
            ]);

        }
         session()->flash("success", "تم التعديل بنجاح");
         return redirect()->route('brand.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // return $request;
        $brand=Brand::where('id',$request->brand_id)->first();
        // return $brand->brand_img;
        if(isset($brand->brand_img))
        {
         $image_path = public_path() . '/brand_images' . '/' . $brand->brand_img;
         unlink($image_path);
 
        }
        
        $brand->delete();
        session()->flash("success", "تم حذف بنجاح");
        return redirect()->route('brand.index');


    }
}
