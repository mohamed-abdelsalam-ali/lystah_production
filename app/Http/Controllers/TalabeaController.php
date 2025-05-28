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


class TalabeaController extends Controller
{   
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $talabeaItems = TalabeaItem::with('type')->with('source')->with('status')->with('part_quality')->with('talabea')->whereNull('talabea_id')->get();
        $talabeas = Talabea::all();
        foreach ($talabeaItems as $key => $value) {
           if($value->type_id == 1){
                $value['item'] = Part::find($value->part_id);
           }
        }
        //    return $talabeaItems;

        $allGroups = Group::all();
        $allSGroups = SubGroup::all();
        $Btype = BrandType::all();
        $allbrand = Brand::all();
        $allmodel = Model::all();
        $allseries = Series::all();
        $allSuppliers = Supplier::all();
        $allStores = Store::all();

       return View('pre.prepare',compact('talabeaItems','allStores','talabeas','allGroups','allSGroups','Btype','allbrand','allmodel','allseries','allSuppliers'));
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
    
    public function processSelectedItems(Request $request)
    {
        // return $request;
        $selectedItems = $request->input('selected_items', []);
        
        if (empty($selectedItems)) {
            return back()->with('error', 'No items selected.');
        }
        if(isset($request->delete)){
            foreach ($selectedItems as $key => $item) {
                $parts = explode("-", $item);
                $type_id = $parts[0];
                $part_id = $parts[1];
                $source_id = $parts[2];
                $status_id = $parts[3];
                $quality_id = $parts[4];
    
    
                TalabeaItem::where('type_id' , $type_id)
                    ->where('part_id' , $part_id)
                    ->where('source_id' , $source_id)
                    ->where('status_id' , $status_id)
                    ->where('quality_id' , $quality_id)->delete();
                    
            }
        }else{
            if(isset($request->talabea_id)){
                foreach ($selectedItems as $key => $item) {
                    $parts = explode("-", $item);
                    $type_id = $parts[0];
                    $part_id = $parts[1];
                    $source_id = $parts[2];
                    $status_id = $parts[3];
                    $quality_id = $parts[4];
        
        
                    TalabeaItem::create([
                        'type_id' => $type_id,
                        'part_id' => $part_id,
                        'source_id' => $source_id === "NULL" ? null : (int) $source_id, 
                        'status_id' => $status_id === "NULL" ? null : (int) $status_id,
                        'quality_id' => $quality_id === "NULL" ? null : (int) $quality_id,
                        'amount' => 1,
                        'talabea_id' => $request->talabea_id
                    ]);
                }
            }else{
              
                return back()->with('error', "اختر الطلبية ");
            }
            
        }
       
        session()->flash("success", "تم حفظ البيانات  بنجاح");
        return redirect()->to('talabea/' . $request->talabea_id);
    
    }
    public function get_all_talabeas(){
        return $talabeas = Talabea::all();
    }
    public function NewTalabea(Request $request){
        Talabea::create([
            'name' => $request->name
        ]);
        return true;
    }

    public function get_all_defects(){
        $all_parts = AllPart::select(
            'part_id', 
            'source_id', 
            'status_id', 
            'quality_id', 
            DB::raw('SUM(remain_amount) AS sum_remain_amount')
        )
        ->groupBy('part_id', 'source_id', 'status_id', 'quality_id')
        ->with('status')
        ->with('source')
        ->with('part_quality')
        ->with(['part' => function ($q) {
            $q->with('part_numbers');
        }])
        ->get();
    
    $data = $all_parts->filter(function ($i) {
        return $i->sum_remain_amount < ($i->part->limit_order ?? 0); // Ensure `limit_order` is handled if null
    })->values()->toArray(); // Convert to array
    
    return response()->json($data);
    }

    public function get_talabea($id){
     
    $talabea = Talabea::where('id',$id)->with('talabea_suppliers')->first();

    $talabeaItems = TalabeaItem::where('talabea_id', $id)
    ->select('part_id', 'source_id', 'status_id', 'quality_id', 'type_id', DB::raw('sum(amount) as amount'))
    ->groupBy('part_id', 'source_id', 'status_id', 'quality_id', 'type_id')
    ->with(['source', 'status', 'part_quality'])
    ->get();

    
    foreach ($talabeaItems as $key => $item) {
        if($item->type_id == 1){
            $item['item'] = Part::find($item->part_id);
        }elseif($item->type_id == 2){
            $item['item'] = Wheel::find($item->part_id);
        }elseif($item->type_id == 3){
            $item['item'] = Tractor::find($item->part_id);
        }elseif($item->type_id == 4){
            $item['item'] = Clark::find($item->part_id);
        }elseif($item->type_id == 5){
            $item['item'] = Equip::find($item->part_id);
        }elseif($item->type_id == 6){
            $item['item'] = Kit::find($item->part_id);
        }
        
    }
//    return $talabeaItems;
    return view('pre.talabea_view',compact('talabea','talabeaItems'));

 
    
    }
    public function UpdatetalabeaItemAmount(Request $request){
        // return $request;
        TalabeaItem::where('part_id',$request->part_id)
        ->where('source_id',$request->source_id)
        ->where('status_id',$request->status_id)
        ->where('quality_id',$request->quality_id)
        ->where('type_id',$request->type_id)
        ->where('talabea_id',$request->talabea_id)->delete();

        TalabeaItem::create([
            'type_id' => $request->type_id,
            'part_id' => $request->part_id,
            'source_id' => $request->source_id,
            'status_id' => $request->status_id,
            'quality_id' => $request->quality_id,
            'amount' => $request->amount,
            'talabea_id' => $request->talabea_id
        ]);

        return redirect()->to('talabea/' . $request->talabea_id);
    }

    public function DeleteTalabeaItem(Request $request){
        // return $request;
        TalabeaItem::where('part_id',$request->part_id)
        ->where('source_id',$request->source_id)
        ->where('status_id',$request->status_id)
        ->where('quality_id',$request->quality_id)
        ->where('type_id',$request->type_id)
        ->where('talabea_id',$request->talabea_id)->delete();

    

        return redirect()->to('talabea/' . $request->talabea_id);
    }
    
    public function addtoTalabea(Request $request){
        TalabeaItem::create([
            'type_id' => $request->type_id,
            'part_id' => $request->part_id,
            'source_id' => $request->source_id,
            'status_id' => $request->status_id,
            'quality_id' => $request->quality_id,
            'amount' => 1
        ]);
        session()->flash("success", "تم الاضافة بنجاح");
        return true;
    }


    
}
