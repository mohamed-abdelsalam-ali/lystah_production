<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\StoreStructure;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;
use App\Models\Store;
use App\Models\StoreSection;
use Illuminate\Support\Facades\DB;


class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_store_structures=StoreStructure::with('store')->get();
        $stores=Store::all();
        // return $all_store_structures;
        return view('sections.index',compact('all_store_structures','stores'));
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
        StoreStructure::create([
            'name'=>$request->name,
            'store_id'=>$request->store_id,
            'notes'=>$request->notes,
        ]);
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('section.index');
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
    
    public function GetSections( $store)
    {
        $store= Store::where('id',$store)->first();
        return StoreStructure::where('store_id',$store->id)->with('store')->get();
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // return $request;
        $section=StoreStructure::where('id',$request->section_id)->first();
        $section->update([
            'name'=>$request->name,
            'store_id'=>$request->store_id,
            'notes'=>$request->notes,
        ]);
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('section.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // return $request;
        $section=StoreStructure::where('id',$request->section_id)->first();
        $section->delete();
        session()->flash("success", "تم حذف بنجاح");
        return redirect()->route('section.index');


    }
    
       function multi_section_add(  Request $request){
        //  $request;
        DB::beginTransaction();
        try {
            $start=intval($request->range_from);
            $range=intval($request->range_to);
            for ($i=$start; $i <= $range ; $i++) {
                $name_r = $request->name .' '. $i;
                StoreStructure::create([
                    'name'=>$name_r,
                    'store_id'=>$request->store_id_range,
                    'notes'=>$request->notes_range,
                ]);
            }
            DB::commit();
            session()->flash("success", "تم الاضافة بنجاح");
            return redirect()->route('section.index');
        } catch (\Exception $e) {
            //throw $th;
            DB::rollback();
            session()->flash("error", $e->getMessage());
            return false;
        }


    }
    
    public function getSectionsWithData($store_id){

       $data = StoreStructure::where('store_id', $store_id)->with(['store_sections' => function($query) use ($store_id) {
            $query->where('amount', '>', 0)
                ->where('store_id', $store_id)
                ->with('store_structure', 'source', 'status', 'part_quality')
                // Conditionally eager load based on type_id
                ->with(['part' => function ($q) use ($query){
                    $query->where('type_id', 1);
                }])
                ->with(['wheel' => function ($q) use ($query){
                    $query->where('type_id', 2);
                }])
                ->with(['tractor' => function ($q) use ($query) {
                    $query->where('type_id', 3);
                }])
                ->with(['clark' => function ($q) use ($query) {
                    $query->where('type_id', 4);
                }])
                ->with(['equip' => function ($q) use ($query){
                    $query->where('type_id', 5);
                }])
                ->with(['kit' => function ($q) use ($query) {
                    $query->where('type_id', 6);
                }]);
        }])->get();


        return FacadesDataTables::of($data)
                // ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    $btn = '';
                    if ($row->name) {
                        $btn = $row->name;
                    } else {
                        $btn = '-';
                    }
                    return $btn;
                })
                ->addColumn('part', function ($row) {
                    $btn = '';
                    foreach ($row->store_sections as $key => $x) {
                        if ($x->part) {
                            $btn .= '<li>'.$x->part->name.'</li>';
                        }elseif ($x->wheel) {
                            $btn .= '<li>'.$x->wheel->name.'</li>';
                        }elseif ($x->tractor) {
                            $btn .= '<li>'.$x->tractor->name.'</li>';
                        }elseif ($x->clarck) {
                            $btn .= '<li>'.$x->clarck->name.'</li>';
                        }elseif ($x->equip) {
                            $btn .= '<li>'.$x->equip->name.'</li>';
                        }elseif ($x->kit) {
                            $btn .= '<li>'.$x->kit->name.'</li>';
                        } else {
                            $btn = '-';
                        }
                    }

                    return $btn;
                })

                ->rawColumns(['name','part'])
                ->setTotalRecords(20)
        ->make(true);




    }
    
        public function sectionsSearch(Request $request)
    {
        // return 'NNNN';

        $searchKey = urldecode($request->q);
        $nameRes = StoreStructure::where('name', 'LIKE', '%' . $searchKey . '%')
        ->where('store_id' , $request->store_id)
       ->get();
            return $nameRes;


    }
    
    
     public function manageSection($store_id){
        
        $all_store_structures=StoreStructure::where('store_id',$store_id)->with('store')->get();
        return view('sections.manageSection',compact('all_store_structures'));
    }

    public function changeManageSection(Request $request){
        // return $request;
        $leftSection = $request->leftSection;
        $leftData = $request->leftData;
        $rightSection = $request->rightSection;
        $rightData = $request->rightData;

        foreach ($leftData as $key => $secString) {
            $secStringArr = explode("-", $secString);
            $store_id = $secStringArr[0];
            $part_id = $secStringArr[1];
            $source_id = $secStringArr[2];
            $status_id = $secStringArr[3];
            $quality_id = $secStringArr[4];
            $type_id = $secStringArr[5];
            $amount = $secStringArr[6];

           $founded = StoreSection::where('store_id', $store_id)
            ->where('part_id', $part_id)
            ->where('source_id', $source_id)
            ->where('status_id', $status_id)
            ->where('quality_id', $quality_id)
            ->where('type_id', $type_id)
            ->where('section_id', $leftSection)
            ->groupBy('part_id', 'type_id', 'source_id', 'status_id', 'quality_id')
            ->selectRaw('part_id,type_id ,sum(amount) as amount,source_id,status_id, quality_id')
            ->get();

            if($founded){
                if($founded[0]->amount == $amount){
                    // nothing
                }else{
                    StoreSection::where('store_id', $store_id)
                    ->where('part_id', $part_id)
                    ->where('source_id', $source_id)
                    ->where('status_id', $status_id)
                    ->where('quality_id', $quality_id)
                    ->where('type_id', $type_id)
                    ->where('section_id', $leftSection)
                    ->update(['amount' => $amount - $founded[0]->amount ]);
                }
            }else{
                // insert into section but before it get orderSupplier
                
            }
        }
    }
}
