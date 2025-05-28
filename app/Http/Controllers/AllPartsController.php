<?php

namespace App\Http\Controllers;

use App\Models\AllPart;
use App\Models\Part;
use App\Models\Store;
use Illuminate\Http\Request;
use DataTables;
use DB;

class AllPartsController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AllPart $allPart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AllPart $allPart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AllPart $allPart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AllPart $allPart)
    {
        //
    }
    public function defectsItemsIndex(Request $request)
    {
        if ($request->ajax()) {
            $all_parts = AllPart::select('part_id', 'source_id', 'status_id', 'quality_id' ,  DB::raw('SUM(remain_amount) AS sum_remain_amount'))
                ->groupBy('part_id'  , 'source_id' ,'status_id' , 'quality_id' )
                ->with('status')
                ->with('source')
                ->with('part_quality')
                ->with(['part'=>function($q){
                    return $q->with('part_numbers')->get();
                }])->get();

            $data = $all_parts->filter(function ($i) {
                return $i->sum_remain_amount < $i->part->limit_order;
            });
            return Datatables::of($data)
                    ->addIndexColumn()

                    ->addColumn('ArabicName',function($row){
                        return $row->part->name ;
                    })
                    ->addColumn('EnglishName',function($row){
                        return $row->part->name_eng ;
                    })
                    ->addColumn('PartNumber',function($row){
                        $html = "";
                        foreach ($row->part->part_numbers as $key => $value) {
                            $html .='<li class="text-info">'.$value->number.'</li>';
                        }
                        return $html;
                    })
                    ->addColumn('LimitOrder',function($row){
                        return $row->part->limit_order ;
                    })

                    ->addColumn('RemainingAmount',function($row){
                        return $row->sum_remain_amount;
                    })

                    ->addColumn('Source',function($row){
                        return $row->source->name_arabic;
                    })

                    ->addColumn('Quality',function($row){
                        return $row->part_quality->name;
                    })

                    ->addColumn('Status',function($row){
                        return $row->status->name;
                    })
                    ->rawColumns(['ArabicName', 'EnglishName' ,'PartNumber' ,'LimitOrder' , 'RemainingAmount' , 'Source' , 'Quality' ,'Status'])
                    ->make(true);
        }
    }
    
    public function allpartReport(){
        $parts = AllPart::all();
        
        return view('reports.part_report', compact('parts')); 
    }
      public function  defectsItems($store_id){

        $store_data = Store::where('id', $store_id)->get();
        return view('defectsItems_pos', compact('store_data')); 
    }
}