<?php

namespace App\Http\Controllers;

use App\Models\Model;
use App\Models\Series;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_series = Series::with('model')->get();
        $models = Model::all();

        // return $all_series;
        return view('series.index', compact('all_series', 'models'));
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
        if ($request->hasfile('seris_img')) {
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_series = $time . '-' . $request->name . '-' . $request->seris_img->getClientOriginalName();
            $request->seris_img->move(public_path('series_images'), $image_series);
            Series::create([
                'name' => $request->name,
                'model_id' => $request->model_id,
                'desc' => $request->desc,
                'seris_img' => $image_series

            ]);
        } else {
            Series::create([
                'name' => $request->name,
                'model_id' => $request->model_id,
                'desc' => $request->desc,
            ]);
        }
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('series.index');
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
        $series=Series::where('id',$request->series_id)->first();
        // return $company;
        if ($request->hasfile('seris_img')){
            if(isset($series->seris_img))
            {
             $image_path = public_path() . '/series_images' . '/' . $series->seris_img;
             unlink($image_path);
     
            }
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_series = $time . '-' . $request->name . '-' . $request->seris_img->getClientOriginalName();
            $request->seris_img->move(public_path('series_images'), $image_series);
            $series->update([
                'name' => $request->name,
                'model_id' => $request->model_id,
                'desc' => $request->desc,
                'seris_img' => $image_series

            ]);
        }else{
            $series->update([
                'name' => $request->name,
                'model_id' => $request->model_id,
                'desc' => $request->desc,

            ]);
            
        }
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('series.index');
       
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // return $request;
        $series=Series::where('id',$request->series_id)->first();
        // return $series;
            if(isset($series->seris_img))
            {
             $image_path = public_path() . '/series_images' . '/' . $series->seris_img;
             unlink($image_path);
     
            }
        $series->delete();
        session()->flash("success", "تم الحذف بنجاح");
        return redirect()->route('series.index');


    }
}