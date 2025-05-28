<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\BrandType;
use App\Models\Model;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_models=Model::with('brand')->with('brand_type')->get();
        $brands=Brand::all();
        $brand_types=BrandType::all();
        // return $all_models;
        return view('models.index',compact('all_models','brands','brand_types'));
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
        if ($request->hasfile('mod_img_name')){
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_model = $time . '-' . $request->name . '-' . $request->mod_img_name->getClientOriginalName();
            $request->mod_img_name->move(public_path('model_images'), $image_model);
            Model::create([
                'name'=>$request->name,
                'brand_id'=>$request->brand_id,
                'type_id'=>$request->type_id,
                'mod_img_name'=>$image_model,
                'desc'=>$request->desc,

            ]);

        }else{
            Model::create([
                'name'=>$request->name,
                'brand_id'=>$request->brand_id,
                'type_id'=>$request->type_id,
                'desc'=>$request->desc,
            ]);

        }
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('model.index');

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
        $model=Model::where('id',$request->model_id)->first();
        // return $company;
        if ($request->hasfile('mod_img_name')){
            if(isset($model->mod_img_name))
            {
             $image_path = public_path() . '/model_images' . '/' . $model->mod_img_name;
             unlink($image_path);
     
            }
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_model = $time . '-' . $request->name . '-' . $request->mod_img_name->getClientOriginalName();
            $request->mod_img_name->move(public_path('model_images'), $image_model);
            $model->update([
                'name'=>$request->name,
                'brand_id'=>$request->brand_id,
                'type_id'=>$request->type_id,
                'mod_img_name'=>$image_model,
                'desc'=>$request->desc,

            ]);
        }else{
            $model->update([
                'name'=>$request->name,
                'brand_id'=>$request->brand_id,
                'type_id'=>$request->type_id,
                'desc'=>$request->desc,

            ]);
            
        }
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('model.index');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Request $request)
    {
        // return $request;
        $model=Model::where('id',$request->model_id)->first();
        // return $company;
            if(isset($model->mod_img_name))
            {
             $image_path = public_path() . '/model_images' . '/' . $model->mod_img_name;
             unlink($image_path);
     
            }
        $model->delete();
        session()->flash("success", "تم الحذف بنجاح");
        return redirect()->route('model.index');
        


    }
}
