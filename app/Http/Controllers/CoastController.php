<?php

namespace App\Http\Controllers;

use App\Models\AllKit;
use App\Models\AllPart;
use App\Models\AllWheel;
use App\Models\BuyTransaction;
use App\Models\CoastData;
use App\Models\Coasts;
use App\Models\OrderSupplier;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoastController extends Controller
{
    //
    public function index()
    {
        $coasts = Coasts::all();
        $stores = Store::where('table_name', '!=', 'damaged_parts')->get();
        return view('coast.index',compact('coasts','stores'));
    }

    public function getBuyInvoice($id){
        $invoice = BuyTransaction::where('id', $id)
            ->with('company')
            ->with('order_suppliers.supplier')
            ->first();
        $other_coasts = CoastData::where('type_id', -1)
            ->where('item_id', $id)->with('getcoast')
            ->get();
        if ($invoice) {
            return response()->json([
                'success' => true,
                'data' => $invoice,
                'other_coasts' => $other_coasts
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found.'
            ], 404);
        }
    }

    public function savenewcoastitem(Request $request)
    {
              
        $coast = new Coasts();
        $coast->name = $request->name;
        $coast->save();

        return response()->json([
            'success' => true,
            'message' => 'Coast item saved successfully.',
            'data' => $coast
        ]);
    }
    
     public function saveNewCoast(Request $request){

        // add extra coast to table of coasts
        // update invoice coasts and total coast;
        // return $request;
        $totalCoast=0;
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'value' => 'required|numeric',
        //     'type_id' => 'required|integer',
        //     'item_id' => 'required|integer'
        // ]);

        
        foreach ($request->item_name as $key => $coastt) {
          if($request->new_cost[$key] == null){
            $totalCoast +=CoastData::where('item_id', $request->item_id)->where('type_id', -1)->where('coast_id', $coastt)->sum('value');
          }else{
            CoastData::where('item_id', $request->item_id)->where('type_id', -1)->where('coast_id', $coastt)->delete();
            $coast = new CoastData();
            $coast->coast_id = $coastt;
            $coast->value = $request->new_cost[$key];
            $coast->type_id = $request->type_id;
            $coast->item_id = $request->item_id;
            $coast->save();
            $totalCoast += $request->new_cost[$key];
            
          }
        }
        
        if($request->transport_coast != null){
            OrderSupplier::where('transaction_id', $request->item_id)->update(['transport_coast' => $request->transport_coast]);
            $totalCoast += $request->transport_coast;
        }

        if($request->insurant_coast != null){
            OrderSupplier::where('transaction_id', $request->item_id)->update(['insurant_coast' => $request->insurant_coast]);
            $totalCoast += $request->insurant_coast;
        }
        if($request->customs_coast != null){
            OrderSupplier::where('transaction_id', $request->item_id)->update(['customs_coast' => $request->customs_coast]);
            $totalCoast += $request->customs_coast;
        }

        if($request->commotion_coast != null){
            OrderSupplier::where('transaction_id', $request->item_id)->update(['commotion_coast' => $request->commotion_coast]);
            $totalCoast += $request->commotion_coast;
        }

        if($request->taslem_coast != null){
            OrderSupplier::where('transaction_id', $request->item_id)->update(['taslem_coast' => $request->taslem_coast]);
            $totalCoast += $request->taslem_coast;
        }
        if($request->nolon_coast != null){
            OrderSupplier::where('transaction_id', $request->item_id)->update(['nolon_coast' => $request->nolon_coast]);
            $totalCoast += $request->nolon_coast;
        }
       

        if($request->ardya_coast != null){
            OrderSupplier::where('transaction_id', $request->item_id)->update(['ardya_coast' => $request->ardya_coast]);
            $totalCoast += $request->ardya_coast;
        }
        if($request->in_transport_coast != null){
            OrderSupplier::where('transaction_id', $request->item_id)->update(['in_transport_coast' => $request->in_transport_coast]);
            $totalCoast += $request->in_transport_coast;
        }

        if($request->takhles_coast != null){
            OrderSupplier::where('transaction_id', $request->item_id)->update(['takhles_coast' => $request->takhles_coast]);
            $totalCoast += $request->takhles_coast;
        }

        if($request->bank_coast != null){
            OrderSupplier::where('transaction_id', $request->item_id)->update(['bank_coast' => $request->bank_coast]);
            $totalCoast += $request->bank_coast;
        }
        if($request->other_coast != null){
            OrderSupplier::where('transaction_id', $request->item_id)->update(['other_coast' => $request->other_coast]);
            $totalCoast += $request->other_coast;
        }

        if($totalCoast > 0) {
            OrderSupplier::where('transaction_id', $request->item_id)->update(['coast' => $totalCoast]);
        }

       

        return redirect()->back()->with('success',  'Coast item saved successfully.');

    }

    public function saveNewCoast2(Request $request){

        // add extra coast to table of coasts
        // update invoice coasts and total coast;
        // return $request;

        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'value' => 'required|numeric',
        //     'type_id' => 'required|integer',
        //     'item_id' => 'required|integer'
        // ]);
        $totalCoast=0;
        
        foreach ($request->item_name as $key => $coastt) {
            if($request->new_cost[$key] == null){
                $totalCoast += $request->new_cost[$key];
            }else{
              CoastData::where('item_id', $request->selectedItem)->where('coast_id', $coastt)->delete();
              $coast = new CoastData();
              $coast->coast_id = $coastt;
              $coast->value = $request->new_cost[$key];
              $coast->type_id = $request->type_id;
              $coast->item_id = $request->selectedItem;
              $coast->save();
              $totalCoast += $request->new_cost[$key];
              
            }
        }

        if($request->type_id == 1){
            $results = DB::table('all_parts')
            ->whereRaw("CONCAT('1','-',part_id,'-',source_id,'-',status_id,'-',quality_id) = ?", [$request->selectedItem])
            ->where('remain_amount', '>', 0)
            ->pluck('order_supplier_id')
            ->toArray(); 

            $affected = $orderSupplier = OrderSupplier::whereIn('id', $results)->orderBy('id', 'DESC')->first();
            
            if($affected){
                $totalCoast += $affected->transport_coast;
                $totalCoast += $affected->insurant_coast;
                $totalCoast += $affected->customs_coast;
                $totalCoast += $affected->commotion_coast;
                $totalCoast += $affected->taslem_coast;
                $totalCoast += $affected->nolon_coast;
                $totalCoast += $affected->ardya_coast;
                $totalCoast += $affected->in_transport_coast;
                $totalCoast += $affected->takhles_coast;
                $totalCoast += $affected->bank_coast;
                $totalCoast += $affected->other_coast;
                
                $affected->coast = $totalCoast;
                $affected->save();
            }
            
        }
        

       

        return redirect()->back()->with('success',  'Coast item saved successfully.');
    }
    
    public function coastitemData($type_id, $part_id, $source_id, $status_id, $quality_id)
    {
        $data = new \Illuminate\Database\Eloquent\Collection;
        $allstores = Store::all();
        if ($type_id == 1) {

            $data['data'] = AllPart::where('part_id', $part_id)->where('source_id', $source_id)
                ->where('status_id', $status_id)
                ->where('quality_id', $quality_id)
                ->where('remain_amount','>', 0)
                ->orderBy('id', 'ASC')
                ->with('order_supplier.supplier')
                ->with('part')
                ->with('source')
                ->with('status')
                ->with('part_quality')
                ->with('store_log')
                ->get()
                ->map(function ($item) {
                    // Add the static type_id to each item
                    $item->type_id = 1;  // Static type_id
                    return $item;
                });

            
            
        } elseif ($type_id == 2) {
            $data['data'] = AllWheel::where('part_id', $part_id)->where('source_id', $source_id)
                ->where('status_id', $status_id)->where('quality_id', $quality_id)
                ->where('remain_amount','>', 0)
                ->orderBy('id', 'ASC')
                ->with('order_supplier.supplier')
                ->with('wheel')
                ->with('source')
                ->with('status')
                ->with('part_quality')
                ->with('store_log')
                ->get()->map(function ($item) {
                    // Add the static type_id to each item
                    $item->type_id = 2;  // Static type_id
                    return $item;
                });

           
        } elseif ($type_id == 6) {
            $data['data'] = AllKit::where('part_id', $part_id)->where('source_id', $source_id)
                ->where('status_id', $status_id)->where('quality_id', $quality_id)
                ->where('remain_amount','>', 0)
                ->orderBy('id', 'ASC')
                ->with('order_supplier.supplier')
                ->with('kit')
                ->with('source')
                ->with('status')
                ->with('part_quality')
                ->with('store_log')
                ->get()->map(function ($item) {
                    // Add the static type_id to each item
                    $item->type_id = 6;  // Static type_id
                    return $item;
                });

            
        }
         $data['extraCoast'] = CoastData::where('item_id', $type_id.'-'.$part_id.'-'.$source_id.'-'.$status_id.'-'.$quality_id)->with('getcoast')->get();
        return $data;
    }
    
}