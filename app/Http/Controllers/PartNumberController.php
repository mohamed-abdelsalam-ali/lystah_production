<?php

namespace App\Http\Controllers;

use App\Models\AllPart;
use App\Models\PartNumber;
use App\Models\Part;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;

class PartNumberController extends Controller
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
    public function show(PartNumber $partNumber)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PartNumber $partNumber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PartNumber $partNumber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PartNumber $partNumber)
    {
        //
    }

    public function partNumberIndex(Request $request)
    {

        if ($request->ajax()) {

            $data = PartNumber::with('part.all_parts')->with('supplier')->get();



            // return $data;
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('PartNumber',function($row){
                        return $row->number ;
                    })
                    ->addColumn('ArabicName',function($row){
                        return $row->part->name ;
                    })
                    ->addColumn('EnglishName',function($row){
                        return $row->part->name_eng ;
                    })
                    ->addColumn('supplier',function($row){
                        $html = "";
                        if(isset($row->supplier)){
                           if($row->flag_OM == "1"){
                                $html .='<text class="text-danger">'.$row->supplier->name.'</text>';
                            }else{
                                $html .='<text>'.$row->supplier->name.'</text>';
                            }
                        }else{
                            $html = '----';
                        }


                        return $html;
                    })
                    ->addColumn('allPart',function($row){
                        $html = "";
                        if(isset($row->supplier)){

                                $html .='<a href="/allPartUnderSupplier/'.$row->supplier->id.'"><i class="mdi mdi-arrow-all"></i></a>';

                        }else{
                            $html = '----';
                        }


                        return $html;
                    })
                    ->rawColumns(['PartNumber','ArabicName','EnglishName','supplier','allPart'])
                    ->make(true);
        }


    }

    public function allPartUnderSupplier($supplier_id){

        return view('allpart_supplier' ,compact('supplier_id'));
        // return $data = PartNumber::with('part.all_parts')->with('supplier')->where('supplier_id',$supplier_id)->get();



    }

    public function allpartsUnderSupplier(Request $request,$supplier_id){
        // return $request;
        if ($request->ajax()) {
            $data = AllPart::groupBy(['part_id','source_id','status_id','quality_id', 'part_number.number'])
            ->join('part', 'all_parts.part_id', '=', 'part.id')
            ->join('part_number', 'part.id', '=', 'part_number.part_id')
            ->where('part_number.supplier_id', '=', $supplier_id)
            ->select(
                'all_parts.part_id',
                'all_parts.source_id',
                'all_parts.status_id',
                'part_number.number',
                'all_parts.quality_id',
                DB::raw('SUM(remain_amount) as storeAmount'))
            ->with('part')
            ->with('source')
            ->with('status')
            ->with('part_quality')
            ->get();

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('partName',function($row){
                return $row->part->name ;
            })
            ->addColumn('Source',function($row){
                return $row->source->name_arabic ;
            })
            ->addColumn('Status',function($row){
                return $row->status->name ;
            })
            ->addColumn('quality',function($row){
                return $row->part_quality->name ;
            })
            ->addColumn('amount',function($row){
                return $row->storeAmount;
            })
            ->addColumn('number',function($row){
                return $row->number;
            })
            ->addColumn('eng_name',function($row){
                return $row->part->name_eng;
            })
            ->rawColumns(['partName','Source','Status','quality','amount','number','eng_name'])
            ->make(true);
        }
    }
}
