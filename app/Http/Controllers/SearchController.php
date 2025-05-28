<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Part;
use App\Models\Group;
use App\Models\SubGroup;
use App\Models\BrandType;
use App\Models\Brand;
use App\Models\Model;
use App\Models\Series;
use App\Models\Supplier;
use App\Models\Talabea;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;
use App\Models\Store;
use App\Models\AllPart;
use App\Models\TalabeaItem;
use Illuminate\Support\Facades\DB;


class SearchController extends Controller
{   
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $models = Model::all();
        $brands = Brand::all();
        return view('search.index',compact('models','brands'));
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
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
       

    }
    
        
}
