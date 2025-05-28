<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\StoresLog;
use App\Models\Store;
use App\Models\Part;


use Illuminate\Http\Request;

class StoreTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $invoices=BuyTransaction::with('order_suppliers_with_replayorder')->with('order_suppliers')->with('company')->where('final', '=' ,'3')->orderBy('date', 'desc')->get();
        // return $invoices;
        // event(new \App\Events\SaveTransaction('inbox'));compact('invoices','data_inbox')
       $data_inbox= $this->inbox_admin();
        return view('store.store',compact('data_inbox'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StoresLog  $storesLog
     * @return \Illuminate\Http\Response
     */

    public function show($storeid)
    {
        // return $storeid;
        $store_data=Store::where('id','=',$storeid)->get();
        // return $store[0]->table_name;
        // $store_table_name=$store[0]->table_name;

        // return $items;
         $items_part=  $this->get_parts_info($store_data[0]->table_name);
          $items_wheel=  $this->get_wheel_info($store_data[0]->table_name);
            $items_tractor=  $this->get_tractor_info($store_data[0]->table_name);
            $items_clarck=  $this->get_clarck_info($store_data[0]->table_name);
            $items_equip=  $this->get_equip_info($store_data[0]->table_name);
             $items_kit=  $this->get_kit_info($store_data[0]->table_name);

             $allItems = new \Illuminate\Database\Eloquent\Collection; //Create empty collection which we know has the merge() method
             $allItems = $allItems->concat($items_part);
             $allItems = $allItems->concat($items_wheel);
             $allItems = $allItems->concat($items_tractor);
             $allItems = $allItems->concat($items_clarck);
             $allItems = $allItems->concat($items_equip);
             $allItems = $allItems->concat($items_kit);

            $data_inbox= $this->inbox_admin();
            $store_inbox= $this->store_inbox($storeid);
            //   return $store_inbox;
        return view('store.store',compact('store_data','allItems', 'data_inbox','store_inbox'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StoresLog  $storesLog
     * @return \Illuminate\Http\Response
     */
    public function edit(StoresLog $storesLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StoresLog  $storesLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoresLog $storesLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoresLog  $storesLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoresLog $storesLog)
    {
        //
    }
    public function inbox_admin(){

        return StoresLog::with('store_action')->with('type')->with('store')->where('stores_log.status' ,'>=','-1')->where('stores_log.status' ,'<>','1')->get();
    }

    public function store_inbox($store_idd){

        return StoresLog::with('store_action')->with('type')->with('store')->where('stores_log.status' ,'=','0')->where('stores_log.store_id' ,'=',$store_idd)->whereIn('stores_log.store_action_id' ,[3,1])->get();
    }
    public function get_parts_info($store_table_name){
        $store_model=ucfirst($store_table_name);
        if($store_model=="Damaged_parts"){
            $store_model="damagedPart";
        }
        $all_p_table_name='all_parts';
        $all_p_model_name='AllPart';
        $table='Part';

        $entity_tbl ='App\\Models\\'.$store_model;
        $items_arr=$entity_tbl::with('stores_log')->get();
        $entity ='App\\Models\\'.$store_model;
        $store_data=$entity::
                    leftjoin('stores_log',$store_table_name.'.store_log_id','=','stores_log.id')->leftjoin($all_p_table_name,'stores_log.All_part_id','=',$all_p_table_name.'.id')
                    // ->where($all_p_table_name.'.part_id','=', $p_id)
                    ->where('stores_log.type_id','=','1')
                    ->select($store_table_name.'.*' , $all_p_table_name.'.source_id', $all_p_table_name.'.status_id', $all_p_table_name.'.quality_id', $all_p_table_name.'.part_id')->get();
        $groups = $store_data->groupBy(
            'part_id',
            'source_id',
            'status_id',
            'quality_id'
        );
        return $groupwithcount = $groups->map(function ($group) {
            return [
                'part_id' => $group->first()['part_id'], // opposition_id is constant inside the same group, so just take the first or whatever.
                'Tamount' => $group->sum('amount'),
                'store_log_id' => $group->first()['store_log_id'],
                'date' => $group->first()['date'],
                'source_id' => $group->first()['source_id'],
                'status_id' => $group->first()['status_id'],
                'quality_id' => $group->first()['quality_id'],
                'supplier_order_id' => $group->first()['supplier_order_id'],
                'type_id' => $group->first()['type_id'],
                'p_data'=>'App\\Models\\Part'::with('part_numbers')->find($group->first()['part_id']),
                'source'=>'App\\Models\\source'::where('id','=',$group->first()['source_id'])->get(),
                'status'=>'App\\Models\\Status'::where('id','=',$group->first()['status_id'])->get(),
                'quality'=>'App\\Models\\PartQuality'::where('id','=',$group->first()['quality_id'])->get(),
                'type_N' => 'قطع غيار',

            ];
        });


        // for ($i=0; $i <count($items_arr) ; $i++) {
        //     // return $items_arr;
        //    $p_id=$items_arr[$i]->part_id;
        //    $ptt_id=$items_arr[$i]->type_id;
        //    $o_sup_id=$items_arr[$i]->supplier_order_id;
        //    $all_p_id=$items_arr[$i]->stores_log['All_part_id'];
        // //    return $o_sup_id;
        //     if($ptt_id==3){
        //         $all_p_table_name='all_tractors';
        //         $all_p_model_name='AllTractor';
        //         $table='Tractor';

        //     }elseif($ptt_id==4){
        //         $all_p_table_name='all_clarks';
        //         $all_p_model_name='AllClark';
        //         $table='Clark';

        //     }elseif($ptt_id==5){
        //         $all_p_table_name='all_equips';
        //         $all_p_model_name='AllEquip';
        //         $table='Equip';

        //     }else if($ptt_id==1){
        //         $all_p_table_name='all_parts';
        //         $all_p_model_name='AllPart';
        //         $table='Part';

        //     }elseif($ptt_id==2){
        //         $all_p_table_name='all_wheels';
        //         $all_p_model_name='AllWheel';
        //         $table='Wheel';


        //     }elseif($ptt_id==6){
        //         $all_p_table_name='all_kits';
        //         $all_p_model_name='AllKits';
        //         $table='Kit';

        //     }

        // }
    }

    public function get_wheel_info($store_table_name){
        $store_model=ucfirst($store_table_name);
        if($store_model=="Damaged_parts"){
            $store_model="damagedPart";
        }
        $all_p_table_name='all_wheels';
        $all_p_model_name='AllWheel';
        $table='Wheel';


        $entity_tbl ='App\\Models\\'.$store_model;
        $items_arr=$entity_tbl::with('stores_log')->get();
        $entity ='App\\Models\\'.$store_model;
        $store_data=$entity::
                    leftjoin('stores_log',$store_table_name.'.store_log_id','=','stores_log.id')->leftjoin($all_p_table_name,'stores_log.All_part_id','=',$all_p_table_name.'.id')
                    // ->where($all_p_table_name.'.part_id','=', $p_id)
                    ->where('stores_log.type_id','=','2')
                    ->select($store_table_name.'.*' , $all_p_table_name.'.source_id', $all_p_table_name.'.status_id', $all_p_table_name.'.quality_id', $all_p_table_name.'.part_id')->get();
        $groups = $store_data->groupBy(
            'part_id',
            'source_id',
            'status_id',
            'quality_id'
        );
        return $groupwithcount = $groups->map(function ($group) {
            return [
                'part_id' => $group->first()['part_id'], // opposition_id is constant inside the same group, so just take the first or whatever.
                'Tamount' => $group->sum('amount'),
                'store_log_id' => $group->first()['store_log_id'],
                'date' => $group->first()['date'],
                'source_id' => $group->first()['source_id'],
                'status_id' => $group->first()['status_id'],
                'quality_id' => $group->first()['quality_id'],
                'supplier_order_id' => $group->first()['supplier_order_id'],
                'type_id' => $group->first()['type_id'],
                'p_data'=>'App\\Models\\Wheel'::find($group->first()['part_id']),
                'source'=>'App\\Models\\source'::where('id','=',$group->first()['source_id'])->get(),
                'status'=>'App\\Models\\Status'::where('id','=',$group->first()['status_id'])->get(),
                'quality'=>'App\\Models\\PartQuality'::where('id','=',$group->first()['quality_id'])->get(),
                'type_N' => 'كاوتش ',


            ];
        });




    }
    public function get_tractor_info($store_table_name){
        $store_model=ucfirst($store_table_name);
        if($store_model=="Damaged_parts"){
            $store_model="damagedPart";
        }
        $all_p_table_name='all_tractors';
        $all_p_model_name='AllTractor';
        $table='Tractor';


        $entity_tbl ='App\\Models\\'.$store_model;
        $items_arr=$entity_tbl::with('stores_log')->get();
        $entity ='App\\Models\\'.$store_model;
        $store_data=$entity::
                    leftjoin('stores_log',$store_table_name.'.store_log_id','=','stores_log.id')->leftjoin($all_p_table_name,'stores_log.All_part_id','=',$all_p_table_name.'.id')
                    // ->where($all_p_table_name.'.part_id','=', $p_id)
                    ->where('stores_log.type_id','=','3')
                    ->select($store_table_name.'.*' , $all_p_table_name.'.source_id', $all_p_table_name.'.status_id', $all_p_table_name.'.quality_id', $all_p_table_name.'.part_id')->get();
        $groups = $store_data->groupBy(
            'part_id',
            'source_id',
            'status_id',
            'quality_id'
        );
        return $groupwithcount = $groups->map(function ($group) {
            return [
                'part_id' => $group->first()['part_id'], // opposition_id is constant inside the same group, so just take the first or whatever.
                'Tamount' => $group->sum('amount'),
                'store_log_id' => $group->first()['store_log_id'],
                'date' => $group->first()['date'],
                'source_id' => $group->first()['source_id'],
                'status_id' => $group->first()['status_id'],
                'quality_id' => $group->first()['quality_id'],
                'supplier_order_id' => $group->first()['supplier_order_id'],
                'type_id' => $group->first()['type_id'],
                'p_data'=>'App\\Models\\Tractor'::find($group->first()['part_id']),
                'source'=>'App\\Models\\source'::where('id','=',$group->first()['source_id'])->get(),
                'status'=>'App\\Models\\Status'::where('id','=',$group->first()['status_id'])->get(),
                'quality'=>'App\\Models\\PartQuality'::where('id','=',$group->first()['quality_id'])->get(),
                'type_N' => 'جرارات ',


            ];
        });




    }

    public function get_clarck_info($store_table_name){
        $store_model=ucfirst($store_table_name);
        if($store_model=="Damaged_parts"){
            $store_model="damagedPart";
        }
        $all_p_table_name='all_clarks';
        $all_p_model_name='AllClark';
        $table='Clark';



        $entity_tbl ='App\\Models\\'.$store_model;
        $items_arr=$entity_tbl::with('stores_log')->get();
        $entity ='App\\Models\\'.$store_model;
        $store_data=$entity::
                    leftjoin('stores_log',$store_table_name.'.store_log_id','=','stores_log.id')->leftjoin($all_p_table_name,'stores_log.All_part_id','=',$all_p_table_name.'.id')
                    // ->where($all_p_table_name.'.part_id','=', $p_id)
                    ->where('stores_log.type_id','=','4')
                    ->select($store_table_name.'.*' , $all_p_table_name.'.source_id', $all_p_table_name.'.status_id', $all_p_table_name.'.quality_id', $all_p_table_name.'.part_id')->get();
        $groups = $store_data->groupBy(
            'part_id',
            'source_id',
            'status_id',
            'quality_id'
        );
        return $groupwithcount = $groups->map(function ($group) {
            return [
                'part_id' => $group->first()['part_id'], // opposition_id is constant inside the same group, so just take the first or whatever.
                'Tamount' => $group->sum('amount'),
                'store_log_id' => $group->first()['store_log_id'],
                'date' => $group->first()['date'],
                'source_id' => $group->first()['source_id'],
                'status_id' => $group->first()['status_id'],
                'quality_id' => $group->first()['quality_id'],
                'supplier_order_id' => $group->first()['supplier_order_id'],
                'type_id' => $group->first()['type_id'],
                'p_data'=>'App\\Models\\Clark'::find($group->first()['part_id']),
                'source'=>'App\\Models\\source'::where('id','=',$group->first()['source_id'])->get(),
                'status'=>'App\\Models\\Status'::where('id','=',$group->first()['status_id'])->get(),
                'quality'=>'App\\Models\\PartQuality'::where('id','=',$group->first()['quality_id'])->get(),
                'type_N' => 'كلارك ',

            ];
        });




    }

    public function get_equip_info($store_table_name){
        $store_model=ucfirst($store_table_name);
        if($store_model=="Damaged_parts"){
            $store_model="damagedPart";
        }
        $all_p_table_name='all_equips';
        $all_p_model_name='AllEquip';
        $table='Equip';



        $entity_tbl ='App\\Models\\'.$store_model;
        $items_arr=$entity_tbl::with('stores_log')->get();
        $entity ='App\\Models\\'.$store_model;
        $store_data=$entity::
                    leftjoin('stores_log',$store_table_name.'.store_log_id','=','stores_log.id')->leftjoin($all_p_table_name,'stores_log.All_part_id','=',$all_p_table_name.'.id')
                    // ->where($all_p_table_name.'.part_id','=', $p_id)
                    ->where('stores_log.type_id','=','5')
                    ->select($store_table_name.'.*' , $all_p_table_name.'.source_id', $all_p_table_name.'.status_id', $all_p_table_name.'.quality_id', $all_p_table_name.'.part_id')->get();
        $groups = $store_data->groupBy(
            'part_id',
            'source_id',
            'status_id',
            'quality_id'
        );
        return $groupwithcount = $groups->map(function ($group) {
            return [
                'part_id' => $group->first()['part_id'], // opposition_id is constant inside the same group, so just take the first or whatever.
                'Tamount' => $group->sum('amount'),
                'store_log_id' => $group->first()['store_log_id'],
                'date' => $group->first()['date'],
                'source_id' => $group->first()['source_id'],
                'status_id' => $group->first()['status_id'],
                'quality_id' => $group->first()['quality_id'],
                'supplier_order_id' => $group->first()['supplier_order_id'],
                'type_id' => $group->first()['type_id'],
                'p_data'=>'App\\Models\\Equip'::find($group->first()['part_id']),
                'source'=>'App\\Models\\source'::where('id','=',$group->first()['source_id'])->get(),
                'status'=>'App\\Models\\Status'::where('id','=',$group->first()['status_id'])->get(),
                'quality'=>'App\\Models\\PartQuality'::where('id','=',$group->first()['quality_id'])->get(),
                'type_N' => 'معدات ',

            ];
        });




    }

    public function get_kit_info($store_table_name){
        $store_model=ucfirst($store_table_name);
        if($store_model=="Damaged_parts"){
            $store_model="damagedPart";
        }
        $all_p_table_name='all_kits';
        $all_p_model_name='AllKits';
        $table='Kit';




        $entity_tbl ='App\\Models\\'.$store_model;
        $items_arr=$entity_tbl::with('stores_log')->get();
        $entity ='App\\Models\\'.$store_model;
        $store_data=$entity::
                    leftjoin('stores_log',$store_table_name.'.store_log_id','=','stores_log.id')->leftjoin($all_p_table_name,'stores_log.All_part_id','=',$all_p_table_name.'.id')
                    // ->where($all_p_table_name.'.part_id','=', $p_id)
                    ->where('stores_log.type_id','=','6')
                    ->select($store_table_name.'.*' , $all_p_table_name.'.source_id', $all_p_table_name.'.status_id', $all_p_table_name.'.quality_id', $all_p_table_name.'.part_id')->get();
        $groups = $store_data->groupBy(
            'part_id',
            'source_id',
            'status_id',
            'quality_id'
        );
        return $groupwithcount = $groups->map(function ($group) {
            return [
                'part_id' => $group->first()['part_id'], // opposition_id is constant inside the same group, so just take the first or whatever.
                'Tamount' => $group->sum('amount'),
                'store_log_id' => $group->first()['store_log_id'],
                'date' => $group->first()['date'],
                'source_id' => $group->first()['source_id'],
                'status_id' => $group->first()['status_id'],
                'quality_id' => $group->first()['quality_id'],
                'supplier_order_id' => $group->first()['supplier_order_id'],
                'type_id' => $group->first()['type_id'],
                'p_data'=>'App\\Models\\Kit'::find($group->first()['part_id']),
                'source'=>'App\\Models\\source'::where('id','=',$group->first()['source_id'])->get(),
                'status'=>'App\\Models\\Status'::where('id','=',$group->first()['status_id'])->get(),
                'quality'=>'App\\Models\\PartQuality'::where('id','=',$group->first()['quality_id'])->get(),
                'type_N' => 'كيت ',

            ];
        });




    }

}
