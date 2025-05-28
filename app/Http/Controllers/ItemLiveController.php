<?php

namespace App\Http\Controllers;

use App\Models\AllKit;
use App\Models\AllKitPartItem;
use App\Models\AllPart;
use App\Models\KitPart;
use App\Models\Store1;
use Illuminate\Http\Request;

class ItemLiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('reports.item_live');
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
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    function ItemLivedata(Request $request){

        $Part_id=$request->partId;
        $type_id=$request->type_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        if($type_id == 1){
          $part_story=AllPart::where('part_id',$Part_id)
          ->with('part')
          ->with('source')
          ->with('status')
          ->with('part_quality')
          ->with('order_supplier.buy_transaction')
          ->with('order_supplier.supplier')
          ->with('order_supplier.currency_type')
          ->with('replayorderss')
          ->with('store_log.store_action')
          ->with('store_log.store')
          ->with('part_in_allkit_item.all_kit.kit')
          ->get();






        }elseif($type_id == 6){

        }
        return $part_story;
    }
}
