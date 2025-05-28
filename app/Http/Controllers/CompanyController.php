<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_companies=Company::all();
        // return $all_companies;
        return view('company.index',compact('all_companies'));
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
        if ($request->hasfile('company_image')){
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_company = $time . '-' . $request->name . '-' . $request->company_image->getClientOriginalName();
            $request->company_image->move(public_path('company_images'), $image_company);
            Company::create([
                'name'=>$request->name,
                'address'=>$request->address,
                'telephone'=>$request->telephone,
                'company_image'=>$image_company,
                'mail'=>$request->mail,
                'desc'=>$request->desc,

            ]);

        }else{
            Company::create([
                'name'=>$request->name,
                'address'=>$request->address,
                'telephone'=>$request->telephone,
                'mail'=>$request->mail,
                'desc'=>$request->desc,

            ]);

        }
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('company.index');


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
        $company=Company::where('id',$request->company_id)->first();
        // return $company;
        if ($request->hasfile('company_image')){
            if(isset($company->company_image))
            {
             $image_path = public_path() . '/company_images' . '/' . $company->company_image;
             unlink($image_path);
     
            }
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_company = $time . '-' . $request->name . '-' . $request->company_image->getClientOriginalName();
            $request->company_image->move(public_path('company_images'), $image_company);
            $company->update([
                'name'=>$request->name,
                'address'=>$request->address,
                'telephone'=>$request->telephone,
                'company_image'=>$image_company,
                'mail'=>$request->mail,
                'desc'=>$request->desc,

            ]);
        }else{
            $company->update([
                'name'=>$request->name,
                'address'=>$request->address,
                'telephone'=>$request->telephone,
                'mail'=>$request->mail,
                'desc'=>$request->desc,

            ]);
            
        }
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('company.index');
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // return $request;
        $company=Company::where('id',$request->company_id)->first();
        // return $company;
        if(isset($company->company_image))
        {
         $image_path = public_path() . '/company_images' . '/' . $company->company_image;
         unlink($image_path);
 
        }
        $company->delete();
        session()->flash("success", "تم الحذف بنجاح");
        return redirect()->route('company.index');


    }
}
