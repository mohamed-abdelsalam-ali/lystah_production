<?php

namespace App\Http\Controllers;

use App\Models\AllPart;
use App\Models\Part;
use Illuminate\Http\Request;
use DataTables;

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
            $remaining_sum = AllPart::groupBy('part_id')
                ->selectRaw('part_id , sum(remain_amount) as sum')
                ->get();
            $data =$remaining_sum->map(function ( $item, int $key) {
                return  Part::where('id' , $item->part_id)->where('limit_order' , '>' , $item->sum)
                ->with('part_numbers')
                ->with(['all_parts'=>function($q){
                    return $q
                        ->with('status')
                        ->with('source')
                        ->with('part_quality')->get();
                }])
                ->get();
            });

            $data1=collect($data)->filter()->flatten()->all();

            // return($data1);


            return Datatables::of($data1)
                    ->addIndexColumn()

                    ->addColumn('ArabicName',function($row){
                        return $row->name ;
                    })
                    ->addColumn('EnglishName',function($row){
                        return $row->name_eng ;
                    })
                    ->addColumn('PartNumber',function($row){
                        $html = "";
                        foreach ($row->part_numbers as $key => $value) {
                            $html .='<li class="text-info">'.$value->number.'</li>';
                        }
                        return $html;
                    })
                    ->addColumn('LimitOrder',function($row){
                        return $row->limit_order ;
                    })

                    ->addColumn('RemainingAmount',function($row){
                        $html = "";
                        foreach ($row->all_parts as $key => $value) {
                            $html .='<li class="text-info">'.$value->remain_amount.'</li>';
                        }
                        return $html;
                    })
                    ->addColumn('Source',function($row){
                        $html = "";
                        foreach ($row->all_parts as $key => $value) {
                            $html .='<li class="text-info">'.$value->source->name_arabic.'</li>';
                        }
                        return $html;
                    })
                    ->addColumn('Quality',function($row){
                        $html = "";
                        foreach ($row->all_parts as $key => $value) {
                            $html .='<li class="text-info">'.$value->part_quality->name.'</li>';
                        }
                        return $html;
                    })
                    ->addColumn('Status',function($row){
                        $html = "";
                        foreach ($row->all_parts as $key => $value) {
                            $html .='<li class="text-info">'.$value->status->name.'</li>';
                        }
                        return $html;
                    })

                    // ->addColumn('EnglishName',function($row){
                    //     return $row->part->name_eng ;
                    // })
                    // ->addColumn('supplier',function($row){
                    //     $html = "";
                    //         if($row->flag_OM == "1"){
                    //             $html .='<text class="text-danger">'.$row->supplier->name.'</text>';
                    //         }else{
                    //             $html .='<text>'.$row->supplier->name.'</text>';
                    //         }

                        // return $html;
                    // })
                    ->rawColumns(['ArabicName', 'EnglishName' ,'PartNumber' ,'LimitOrder' , 'RemainingAmount' , 'Source' , 'Quality' ,'Status'])
                    // ->rawColumns(['PartNumber','ArabicName','EnglishName','supplier'])
                    ->make(true);
        }


    }
}