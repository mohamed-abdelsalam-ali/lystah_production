<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\BuyTransaction;
use App\Models\Store;
use App\Events\SaveTransaction;
use App\Models\StoresLog;
use App\Models\DamagedPart;
// use App\DataTables\InboxTransactionsDataTable;
use App\DataTables\StoresLogDataTable;
use DataTables;
use App\Http\Controllers\QaydController;
use App\Models\CurrencyType;
use App\Models\Replyorder;
use App\Models\StorelogSection;
use App\Models\StoreSection;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //    $data_inbox= $this->inbox_admin();

        //     return $data_inbox;

    }

    public function indexWithRequest(Request $request)
    {

        if ($request->ajax()) {
            $data = BuyTransaction::with('order_suppliers_with_replayorder')->with('order_suppliers')->with('company')->where('final', '=', '3')->orderBy('date', 'desc')->get();
            // <th >رقم الفاتورة</th>
            // <th >الشركة</th>
            // <th >المورد</th>
            // <th >عدد القطع</th>
            // <th >الصنف</th>
            // <th >إجمالى الفاتورة</th>
            // <th >العملة </th>
            // <th >التاريخ </th>
            // <th >Actions</th>
           return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('invid', function ($row) {
                    return isset($row) ? $row->id : '--';
                })
                ->addColumn('company', function ($row) {
                    return isset($row->company) ? $row->company->name : '--';
                })
                ->addColumn('supplier', function ($row) {
                    return count($row->order_suppliers) > 0 ? $row->order_suppliers[0]->supplier->name : '--';
                })
                ->addColumn('noOFparts', function ($row) {
                    return count($row->order_suppliers_with_replayorder) > 0  ? count($row->order_suppliers_with_replayorder) : '--';
                })
                ->addColumn('Total', function ($row) {
                    return count($row->order_suppliers) > 0 ? $row->order_suppliers[0]->total_price : '--';
                })
                ->addColumn('currency', function ($row) {
                    return count($row->order_suppliers) > 0 ? $row->order_suppliers[0]->currency_type->name  : '--';
                })
                ->addColumn('date', function ($row) {
                    // return isset($row->date) ? explode(" ", $row->date)[0]  : '--';
                    return isset($row->date) ? explode(" ", $row->date)[0]  : '--';
                })
                ->addColumn('action', function ($row) {





                    $btn = '<a   href="items_inv/' . $row->id . '"  part_id_value="' . $row->id . '"
                         class="btn btn-primary btn-sm d-inline-block  ">توزيع</a>';


                    return $btn;
                })
                // ->rawColumns(['invid','Number','group','subGroup','action'])
            ->rawColumns(['invid',
                'company',
                'supplier',
                'noOFparts',
                'Total',
                'currency',
                'date',
                'action'])
            ->make(true);
        }
        return view('store.invs_buy_transaction');
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
        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BuyTransaction  $buyTransaction
     * @return \Illuminate\Http\Response
     */
    public function show($inv_id)
    {
        // event(new \App\Events\SaveTransaction());
        $items = BuyTransaction::with('order_suppliers')->with('order_suppliers_with_replayorder')->where('final', '=', '3')->where('id', '=', $inv_id)->get();
        // return $items;
        $store = Store::where('table_name', '<>', 'damaged_parts')->where('id', '<>', 8)->get();
        Store::where('table_name', '<>', 'damaged_parts')->where('id', '<>', 8)->get();
        for ($j = 0; $j < count($items[0]->order_suppliers_with_replayorder); $j++) {
            $p_id = $items[0]->order_suppliers_with_replayorder[$j]->part_id;
            $o_sup_id = $items[0]->order_suppliers_with_replayorder[$j]->order_supplier_id;
            $ptt_id = $items[0]->order_suppliers_with_replayorder[$j]->part_type_id;
            $source = $items[0]->order_suppliers_with_replayorder[$j]->source_id;
            $status = $items[0]->order_suppliers_with_replayorder[$j]->status_id;
            $quality = $items[0]->order_suppliers_with_replayorder[$j]->quality_id;
            if ($ptt_id == 3) {
                $all_p_table_name = 'all_tractors';
                $all_p_model_name = 'AllTractor';
                $table = 'Tractor';
            } elseif ($ptt_id == 4) {
                $all_p_table_name = 'all_clarks';
                $all_p_model_name = 'AllClark';
                $table = 'Clark';
            } elseif ($ptt_id == 5) {
                $all_p_table_name = 'all_equips';
                $all_p_model_name = 'AllEquip';
                $table = 'Equip';
            } else if ($ptt_id == 1) {
                $all_p_table_name = 'all_parts';
                $all_p_model_name = 'AllPart';
                $table = 'Part';
            } elseif ($ptt_id == 2) {
                $all_p_table_name = 'all_wheels';
                $all_p_model_name = 'AllWheel';
                $table = 'Wheel';
            } elseif ($ptt_id == 6) {
                $all_p_table_name = 'all_kits';
                $all_p_model_name = 'AllKit';
                $table = 'Kit';
            }

            $entity_tbl = 'App\\Models\\' . $table;
            if ($ptt_id == 1) {
                $static_item_data = $entity_tbl::where('id', '=', $p_id)->with('part_numbers')->get();
                $samllmeasureUnits =$static_item_data[0]->small_unit;
                $measureUnit =$static_item_data[0]->big_unit;
                // ************************************************
                $ratiounit = getSmallUnit($measureUnit, $samllmeasureUnits);
                // ************************************************
                $ratio= $ratiounit;
                $bigunit= $static_item_data[0]->bigunit->name;
               $allunit= $static_item_data[0]->getsmallunit;
            } else {
                $static_item_data = $entity_tbl::where('id', '=', $p_id)->get();
                $ratio= 1;
                $bigunit= "بدون";
                $allunit=[];
            }

            $arr_store_amount = array();
            $arr_store_data = array();
            for ($i = 0; $i < count($store); $i++) {
                $store_table = $store[$i]->table_name;
                $store_model = ucfirst($store[$i]->table_name);
                if ($store_model == "Damaged_parts") {
                    $store_model = "DamagedPart";
                }

                $entity = 'App\\Models\\' . $store_model;
                $old_amount = $entity::join('stores_log', $store_table . '.store_log_id', '=', 'stores_log.id')->join($all_p_table_name, 'stores_log.All_part_id', '=', $all_p_table_name . '.id')
                    ->where($all_p_table_name . '.part_id', '=', $p_id)
                    ->where($all_p_table_name . '.quality_id', '=', $quality)
                    ->where($all_p_table_name . '.source_id', '=', $source)
                    ->where($all_p_table_name . '.status_id', '=', $status)
                    ->where($store_table . '.type_id', '=', $ptt_id)
                    // ->where($all_p_table_name.'.remain_amount','>', 0)
                    ->sum($store_table . '.amount');
                $arr_store_amount[$store_table] = $old_amount;
                array_push($arr_store_data, ['id' => $store[$i]->id, 'name' => $store[$i]->name, 'table_name' => $store_table, 'old_amount' => $old_amount]);
            }

            $entity2 = 'App\\Models\\' . $all_p_model_name;
            $remainn = $entity2::where($all_p_table_name . '.part_id', '=', $p_id)
                ->where($all_p_table_name . '.quality_id', '=', $quality)
                ->where($all_p_table_name . '.source_id', '=', $source)
                ->where($all_p_table_name . '.status_id', '=', $status)
                ->where($all_p_table_name . '.order_supplier_id', '=', $o_sup_id)
                // ->where($all_p_table_name.'.remain_amount','>', 0)
                ->sum($all_p_table_name . '.remain_amount');

            $entity3 = 'App\\Models\\' . $all_p_model_name;
            $allpart_data = $entity3::where($all_p_table_name . '.part_id', '=', $p_id)->with('part.getsmallunit.unit')
                ->where($all_p_table_name . '.quality_id', '=', $quality)
                ->where($all_p_table_name . '.source_id', '=', $source)
                ->where($all_p_table_name . '.status_id', '=', $status)
                ->where($all_p_table_name . '.order_supplier_id', '=', $o_sup_id)->get();

            // ->orwhere('stores_log.store_action_id','=', 6)
            $pending_amount = StoresLog::where('stores_log.All_part_id', '=', $allpart_data[0]->id)
                ->where('stores_log.type_id', '=', $ptt_id)
                //  ->wherein('stores_log.store_action_id', [1,6])
                ->where('stores_log.store_action_id', '=', 1)
                ->where('stores_log.status', '=', 0)
                ->sum('amount');
            $deliver_amount = StoresLog::where('stores_log.All_part_id', '=', $allpart_data[0]->id)
                ->where('stores_log.type_id', '=', $ptt_id)
                //  ->wherein('stores_log.store_action_id', [1,6])
                ->where('stores_log.store_action_id', '=', 3)
                ->where('stores_log.status', '=', 3)
                ->sum('amount');

                if($ptt_id == 1){
                    $samllmeasureUnits =$static_item_data[0]->small_unit;
                    $measureUnit =$static_item_data[0]->big_unit;
                    $ratiounit = getSmallUnit($measureUnit, $samllmeasureUnits);
                    $ratio= $ratiounit;
                    $bigunit= $static_item_data[0]->bigunit->name;
                }else{
                    $ratio= 1;
                    $bigunit= "بدون";
                }

            $items[0]->order_suppliers_with_replayorder[$j]['remain_amount'] = $remainn;
            $items[0]->order_suppliers_with_replayorder[$j]['pending_amount'] = intval($pending_amount);
            $items[0]->order_suppliers_with_replayorder[$j]['deliver_amount'] = intval($deliver_amount);
            $items[0]->order_suppliers_with_replayorder[$j]['allpart_data'] = $allpart_data;
            $items[0]->order_suppliers_with_replayorder[$j]['static_item_data'] = $static_item_data;
            $items[0]->order_suppliers_with_replayorder[$j]["store_data"] =   $arr_store_data;
            $items[0]->order_suppliers_with_replayorder[$j]["ratio"] =   $ratio;
            $items[0]->order_suppliers_with_replayorder[$j]["bigunit"] =   $bigunit;
            $items[0]->order_suppliers_with_replayorder[$j]["allunit"] =   $allunit;
        }




        // return $items;
        // $data_inbox= $this->inbox_admin();


        return view('store.items_inv_transaction', compact('items'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BuyTransaction  $buyTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(BuyTransaction $buyTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BuyTransaction  $buyTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BuyTransaction $buyTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BuyTransaction  $buyTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(BuyTransaction $buyTransaction)
    {
        //
    }
    public function send_new_parts(Request $request)
    {



        // return $request['data'][0]['bisc_data'];
        // return $request;


        // return event(new SaveTransaction('new message for new event'));

        for ($i = 0; $i < count($request['data'][1]['store_data']); $i++) {
            # code...
            $tableName = $request['data'][1]['store_data'][$i]['store_table_name'];
            if ($tableName == "damaged_parts") {
                StoresLog::create([
                    'All_part_id' => $request['data'][0]['bisc_data'][0]['all_p_id'],
                    'store_action_id' => 1,
                    'store_id' => $request['data'][1]['store_data'][$i]['store_id'],
                    'amount' => $request['data'][1]['store_data'][$i]['ins_amount'],
                    // 'user_id' => auth()->user()->id,
                    'status' => 1,
                    'date' => date('Y-m-d H:i:s'),
                    'type_id' => $request['data'][0]['bisc_data'][0]['p_type_id'],
                    'notes' => 'Sent From Admin'

                ]);

                $last_ins = StoresLog::create([
                    'All_part_id' => $request['data'][0]['bisc_data'][0]['all_p_id'],
                    'store_action_id' => 3,
                    'store_id' => $request['data'][1]['store_data'][$i]['store_id'],
                    'amount' => $request['data'][1]['store_data'][$i]['ins_amount'],
                    // 'user_id' => auth()->user()->id,
                    'status' => 1,
                    'date' => date('Y-m-d H:i:s'),
                    'type_id' => $request['data'][0]['bisc_data'][0]['p_type_id'],

                ])->id;
                $all_p_id = $request['data'][0]['bisc_data'][0]['all_p_id'];
                $return_data = DamagedPart::join('stores_log', $tableName . '.store_log_id', '=', 'stores_log.id')->where($tableName . '.all_part_id', '=', $all_p_id)->get();
                $return_amount = DamagedPart::join('stores_log', $tableName . '.store_log_id', '=', 'stores_log.id')->where($tableName . '.all_part_id', '=', $all_p_id)->sum($tableName . '.amount');

                if (count($return_data) > 0) {
                    // return intval($return_amount) + intval($request['data'][1]['store_data'][$i]['ins_amount']);
                    DamagedPart::where('store_log_id', '=', $return_data[0]['store_log_id'])->update([
                        $tableName . '.amount' => intval($return_amount) + intval($request['data'][1]['store_data'][$i]['ins_amount']),
                    ]);
                } else {
                    DamagedPart::create([
                        'all_part_id' => $request['data'][0]['bisc_data'][0]['all_p_id'],
                        'amount' => $request['data'][1]['store_data'][$i]['ins_amount'],
                        'supplier_order_id' => $request['data'][0]['bisc_data'][0]['o_s_id'],
                        'notes' => '',
                        'type_id' => $request['data'][0]['bisc_data'][0]['p_type_id'],
                        'store_log_id' => $last_ins,
                        'date' => date('Y-m-d H:i:s'),
                    ]);
                }
            } else {
                StoresLog::create([
                    'All_part_id' => $request['data'][0]['bisc_data'][0]['all_p_id'],
                    'store_action_id' => 1,
                    'store_id' => $request['data'][1]['store_data'][$i]['store_id'],
                    'amount' => $request['data'][1]['store_data'][$i]['ins_amount'],
                    // 'user_id' => auth()->user()->id,
                    'status' => 0,
                    'date' => date('Y-m-d H:i:s'),
                    'type_id' => $request['data'][0]['bisc_data'][0]['p_type_id'],
                    'notes' => 'Sent From Admin',
                    'unit_id' => $request['data'][1]['store_data'][$i]['unit']

                ]);
            }
            // event(new \App\Events\StoreTranaction($request['data'][1]['store_data'][$i]['store_id']));




        }


        $inbox = $this->inbox_admin();
        // return $inbox;
        //   event(new \App\Events\SaveTransaction());


        //   event(new \App\Events\SaveTransaction('inbox'));
        //   event(new \App\Events\TestEvent());
        return $inbox;
    }
    //
    public function send_all_new_parts(Request $request)
    {
        // return $request['data'][0]['bisc_data'];
        return $request;
        // return event(new SaveTransaction('new message for new event'));
        for ($k = 0; $k < $request['part_id']; $k++) {
        }
        for ($i = 0; $i < count($request['data'][1]['store_data']); $i++) {
            # code...
            $tableName = $request['data'][1]['store_data'][$i]['store_table_name'];
            if ($tableName == "damaged_parts") {
                StoresLog::create([
                    'All_part_id' => $request['data'][0]['bisc_data'][0]['all_p_id'],
                    'store_action_id' => 1,
                    'store_id' => $request['data'][1]['store_data'][$i]['store_id'],
                    'amount' => $request['data'][1]['store_data'][$i]['ins_amount'],
                    // 'user_id' => auth()->user()->id,
                    'status' => 1,
                    'date' => date('Y-m-d H:i:s'),
                    'type_id' => $request['data'][0]['bisc_data'][0]['p_type_id'],
                    'notes' => 'Sent From Admin'

                ]);

                $last_ins = StoresLog::create([
                    'All_part_id' => $request['data'][0]['bisc_data'][0]['all_p_id'],
                    'store_action_id' => 3,
                    'store_id' => $request['data'][1]['store_data'][$i]['store_id'],
                    'amount' => $request['data'][1]['store_data'][$i]['ins_amount'],
                    // 'user_id' => auth()->user()->id,
                    'status' => 1,
                    'date' => date('Y-m-d H:i:s'),
                    'type_id' => $request['data'][0]['bisc_data'][0]['p_type_id'],

                ])->id;
                $all_p_id = $request['data'][0]['bisc_data'][0]['all_p_id'];
                $return_data = DamagedPart::join('stores_log', $tableName . '.store_log_id', '=', 'stores_log.id')->where($tableName . '.all_part_id', '=', $all_p_id)->get();
                $return_amount = DamagedPart::join('stores_log', $tableName . '.store_log_id', '=', 'stores_log.id')->where($tableName . '.all_part_id', '=', $all_p_id)->sum($tableName . '.amount');

                if (count($return_data) > 0) {
                    // return intval($return_amount) + intval($request['data'][1]['store_data'][$i]['ins_amount']);
                    DamagedPart::where('store_log_id', '=', $return_data[0]['store_log_id'])->update([
                        $tableName . '.amount' => intval($return_amount) + intval($request['data'][1]['store_data'][$i]['ins_amount']),
                    ]);
                } else {
                    DamagedPart::create([
                        'all_part_id' => $request['data'][0]['bisc_data'][0]['all_p_id'],
                        'amount' => $request['data'][1]['store_data'][$i]['ins_amount'],
                        'supplier_order_id' => $request['data'][0]['bisc_data'][0]['o_s_id'],
                        'notes' => '',
                        'type_id' => $request['data'][0]['bisc_data'][0]['p_type_id'],
                        'store_log_id' => $last_ins,
                        'date' => date('Y-m-d H:i:s'),
                    ]);
                }
            } else {
                StoresLog::create([
                    'All_part_id' => $request['data'][0]['bisc_data'][0]['all_p_id'],
                    'store_action_id' => 1,
                    'store_id' => $request['data'][1]['store_data'][$i]['store_id'],
                    'amount' => $request['data'][1]['store_data'][$i]['ins_amount'],
                    // 'user_id' => auth()->user()->id,
                    'status' => 0,
                    'date' => date('Y-m-d H:i:s'),
                    'type_id' => $request['data'][0]['bisc_data'][0]['p_type_id'],
                    'notes' => 'Sent From Admin'

                ]);
            }
            // event(new \App\Events\StoreTranaction($request['data'][1]['store_data'][$i]['store_id']));
        }

        $inbox = $this->inbox_admin();
        // return $inbox;
        //   event(new \App\Events\SaveTransaction());


        //   event(new \App\Events\SaveTransaction('inbox'));
        //   event(new \App\Events\TestEvent());
        return $inbox;
    }

    public function inbox_admin_history()
    {
        // event(new \App\Events\SaveTransaction());
        $inbox_history = StoresLog::where('stores_log.status', '>', '-1')->where('stores_log.status', '<', '4')
            ->where('stores_log.status', '<>', '1')->where('stores_log.status', '<>', '3')->get();
        $inbox_history_all = [];
        for ($i = 0; $i < count($inbox_history); $i++) {
            $arr = $this->check_p_type($inbox_history[$i]->type_id);
            $all_data = StoresLog::with('type')
                ->with('store')
                ->with('store_action')
                ->join($arr[0], 'stores_log.All_part_id', '=', $arr[0] . '.id')
                ->join('order_supplier', $arr[0] . '.order_supplier_id', '=', 'order_supplier.id')
                ->join('supplier', 'order_supplier.supplier_id', '=', 'supplier.id')
                ->join($arr[2], $arr[0] . '.part_id', '=', $arr[2] . '.id')
                ->join('source', $arr[0] . '.source_id', '=', 'source.id')
                ->join('status', $arr[0] . '.status_id', '=', 'status.id')
                ->join('part_quality', $arr[0] . '.quality_id', '=', 'part_quality.id')
                ->where('stores_log.id', '=', $inbox_history[$i]->id)

                ->select('stores_log.amount as trans_amount', 'stores_log.id as stores_log_id', 'supplier.name as sup_name', 'stores_log.*', $arr[0] . '.*', $arr[2] . '.name as part_name', 'source.name_arabic as source_name', 'status.name as staus_name', 'part_quality.name as quality_name')

                ->get();
            if($all_data[0]->type->id ==1){
                $samllmeasureUnits = $all_data[0]->all_parts[0]->part->small_unit;
                $measureUnit = $all_data[0]->all_parts[0]->part->big_unit;
                $ratiounit = getSmallUnit($measureUnit, $samllmeasureUnits);
                $all_data[0]['ratio']= $ratiounit;
                $all_data[0]['bigunit']= $all_data[0]->all_parts[0]->part->bigunit->name;
            }else{
                $all_data[0]['ratio']= 1;
                $all_data[0]['bigunit']= "بدون";
            }
            array_push($inbox_history_all, $all_data[0]);
            // return  $all_data;
        }

        $data_inbox = $this->inbox_admin();
        return view('store.inbox_admin_transaction', compact('data_inbox'));
    }


    public function inbox_admin_history1(Request $request)
    {
        if ($request->ajax()) {
            // event(new \App\Events\SaveTransaction());
            $inbox_history = StoresLog::where('stores_log.status', '>', '-1')->where('stores_log.status', '<', '4')
                ->where('stores_log.status', '<>', '1')->where('stores_log.status', '<>', '3')->get();
            $inbox_history_all = [];
            for ($i = 0; $i < count($inbox_history); $i++) {
                $arr = $this->check_p_type($inbox_history[$i]->type_id);
                $all_data = StoresLog::with('type')
                    ->with('store')
                    ->with('store_action')
                    ->join($arr[0], 'stores_log.All_part_id', '=', $arr[0] . '.id')
                    ->join('order_supplier', $arr[0] . '.order_supplier_id', '=', 'order_supplier.id')
                    ->join('supplier', 'order_supplier.supplier_id', '=', 'supplier.id')
                    ->join($arr[2], $arr[0] . '.part_id', '=', $arr[2] . '.id')
                    ->join('source', $arr[0] . '.source_id', '=', 'source.id')
                    ->join('status', $arr[0] . '.status_id', '=', 'status.id')
                    ->join('part_quality', $arr[0] . '.quality_id', '=', 'part_quality.id')
                    ->where('stores_log.id', '=', $inbox_history[$i]->id)

                    ->select('stores_log.amount as trans_amount', 'stores_log.unit_id as trans_unit', 'stores_log.id as stores_log_id', 'supplier.name as sup_name', 'stores_log.*', $arr[0] . '.*', $arr[2] . '.name as part_name', 'source.name_arabic as source_name', 'status.name as staus_name', 'part_quality.name as quality_name')

                    ->get();
                    if($all_data[0]->type->id ==1){
                        $samllmeasureUnits = $all_data[0]->all_parts[0]->part->small_unit;
                         $buy_Unit = $all_data[0]->unit;
                        $measureUnit = $all_data[0]->all_parts[0]->part->big_unit;
                        $ratiounit = getSmallUnit($buy_Unit->id, $samllmeasureUnits);
                        $all_data[0]['ratio']= $ratiounit;
                        $all_data[0]['buy_unit']= $buy_Unit;
                        $all_data[0]['buy_unit_id']= $buy_Unit->id;
                        $all_data[0]['bigunit']= $buy_Unit->name;
                    }else{
                        $all_data[0]['ratio']= 1;
                        $all_data[0]['bigunit']= "بدون";
                    }
                array_push($inbox_history_all, $all_data[0]);
                // return  $all_data;
            }
            $data = $inbox_history_all;
            // return $data;
            return Datatables::of($data)
                // ->addIndexColumn()
                // ->addColumn('action', function($row){

                // //     // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a>
                // //     //  <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';onclick="Confirm_transaction({{$inbox_history_all[$i]->status}},{{$inbox_history_all[$i]->store_action_id}},{{$inbox_history_all[$i]->stores_log_id}},{{$inbox_history_all[$i]->All_part_id}},{{$inbox_history_all[$i]->trans_amount}},{{$inbox_history_all[$i]->type_id}},{{$inbox_history_all[$i]->store->id}},'{{$inbox_history_all[$i]->store->table_name}}')"
                //     $actionBtn='<div class="dropdown">
                //     <a class="btn btn-secondary dropdown-toggle" href="#" role="button" name="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                //         Dropdown link
                //     </a>

                //     <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                //         <li><a class="dropdown-item" href="#">Action</a></li>
                //         <li><a class="dropdown-item" href="#">Another action</a></li>
                //         <li><a class="dropdown-item" href="#">Something else here</a></li>
                //     </ul>
                //     </div>';
                //     return $actionBtn;
                // })
                // ->rawColumns(['action'])
                ->make(true);
        }


    }
    public function inbox_admin()
    {

        return StoresLog::with('store_action')->with('storelog_section')->with('type')->with('store')->where('stores_log.status', '<', '4')->where('stores_log.status', '>', '-1')->where('stores_log.status', '<>', '1')->where('stores_log.status', '<>', '3')->get();
    }
    public function check_p_type($pt_id)
    {
        if ($pt_id == 1) {
            $all_p_table_name = 'all_parts';
            $all_p_model_name = 'AllPart';
            $table = 'part';
        } elseif ($pt_id == 2) {
            $all_p_table_name = 'all_wheels';
            $all_p_model_name = 'AllWheel';
            $table = 'wheel';
        } elseif ($pt_id == 3) {
            $all_p_table_name = 'all_tractors';
            $all_p_model_name = 'AllTractor';
            $table = 'tractor';
        } elseif ($pt_id == 4) {
            $all_p_table_name = 'all_clarks';
            $all_p_model_name = 'AllClark';
            $table = 'clark';
        } elseif ($pt_id == 5) {
            $all_p_table_name = 'all_equips';
            $all_p_model_name = 'AllEquip';
            $table = 'equip';
        } elseif ($pt_id == 6) {
            $all_p_table_name = 'all_kits';
            $all_p_model_name = 'AllKits';
            $table = 'kit';
        }

        return [$all_p_table_name, $all_p_model_name, $table];
    }

    public function confirm_store_trans(Request $request)
    {
        // return $request;
        try {
            $Store_log_id = $request->data['Store_log_id'];
            $store_table_name = $request->data['store_table_name'];
            $all_p_id =  $request->data['all_p_id'];
            $amount =  $request->data['amount'];
            $type_id =  $request->data['type_id'];
            $store_id =  $request->data['store_id'];
            $store_action_id =  $request->data['store_action_id'];

            if ($store_action_id == 1) {

                StoresLog::where('id', $Store_log_id)
                    ->update([
                        'status' => 1
                    ]);
                StoresLog::create([
                    'All_part_id' =>  $all_p_id,
                    'store_action_id' => 3,
                    'store_id' => $store_id,
                    'amount' => $amount,
                    // 'user_id' => auth()->user()->id,
                    'status' => 0,
                    'date' => date('Y-m-d H:i:s'),
                    'type_id' => $type_id,
                    

                ]);



                # code...
            } elseif ($store_action_id == 2) {
                // $arr=$this->check_p_type($inbox_history[$i]->$type_id);
                $store_model = ucfirst($store_table_name);
                if ($store_model == "Damaged_parts") {
                    $store_model = "DamagedPart";
                }
                $entity = 'App\\Models\\' . $store_model;

                $x = $entity::leftjoin('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')->where('stores_log.All_part_id', '=', $all_p_id)->get();
                // return $Store_log_id;
                StoresLog::where('status', $Store_log_id)
                    ->update([
                        'status' => 0
                    ]);


                StoresLog::where('id', $Store_log_id)
                    ->update([
                        'status' => 1
                    ]);
                //  return $Store_log_id;

                $old_s_amount = $entity::where($store_table_name . '.store_log_id', '=',  $x[0]->store_log_id)->get('amount');
                $entity::where($store_table_name . '.store_log_id', '=',  $x[0]->store_log_id)
                    ->update([
                        'amount' => intval($old_s_amount[0]->amount)   - intval($amount),
                        'store_log_id' => $Store_log_id
                    ]);



                // remove items from old store sections 0000000000000000
                if ($type_id == 1) {
                    $allstorelogsection = StorelogSection::where('store_log_id', $Store_log_id)->with('stores_log.all_parts')->get();
                    foreach ($allstorelogsection as $key => $element) {
                        $section_id = $element->section_id;
                        $store_log_id = $element->store_log_id;
                        $part_id = $element->stores_log->all_parts[0]->part_id;
                        $source_id = $element->stores_log->all_parts[0]->source_id;
                        $status_id = $element->stores_log->all_parts[0]->status_id;
                        $quality_id = $element->stores_log->all_parts[0]->quality_id;
                        $orderSupplierId = $element->stores_log->all_parts[0]->order_supplier_id;

                        StoreSection::where('type_id', $type_id)->where('part_id', $part_id)->where('source_id', $source_id)->where('section_id', $section_id)
                            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('order_supplier_id', $orderSupplierId)
                            ->where('amount', '>', '0')->decrement('amount', $element->amount);
                    }
                } elseif ($type_id == 2) {
                    $allstorelogsection = StorelogSection::where('store_log_id', $Store_log_id)->with('stores_log.all_wheels')->get();
                    foreach ($allstorelogsection as $key => $element) {
                        $section_id = $element->section_id;
                        $store_log_id = $element->store_log_id;
                        $part_id = $element->stores_log->all_wheels[0]->part_id;
                        $source_id = $element->stores_log->all_wheels[0]->source_id;
                        $status_id = $element->stores_log->all_wheels[0]->status_id;
                        $quality_id = $element->stores_log->all_wheels[0]->quality_id;
                        $orderSupplierId = $element->stores_log->all_wheels[0]->order_supplier_id;

                        StoreSection::where('type_id', $type_id)->where('part_id', $part_id)->where('source_id', $source_id)->where('section_id', $section_id)
                            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('order_supplier_id', $orderSupplierId)
                            ->where('amount', '>', '0')->decrement('amount', $element->amount);
                    }
                } elseif ($type_id == 6) {
                    $allstorelogsection = StorelogSection::where('store_log_id', $Store_log_id)->with('stores_log.all_kits')->get();
                    foreach ($allstorelogsection as $key => $element) {
                        $section_id = $element->section_id;
                        $store_log_id = $element->store_log_id;
                        $part_id = $element->stores_log->all_kits[0]->part_id;
                        $source_id = $element->stores_log->all_kits[0]->source_id;
                        $status_id = $element->stores_log->all_kits[0]->status_id;
                        $quality_id = $element->stores_log->all_kits[0]->quality_id;
                        $orderSupplierId = $element->stores_log->all_kits[0]->order_supplier_id;

                        StoreSection::where('type_id', $type_id)->where('part_id', $part_id)->where('source_id', $source_id)->where('section_id', $section_id)
                            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('order_supplier_id', $orderSupplierId)
                            ->where('amount', '>', '0')->decrement('amount', $element->amount);
                    }
                } elseif ($type_id == 3) {
                    $allstorelogsection = StorelogSection::where('store_log_id', $Store_log_id)->with('stores_log.all_tractors')->get();
                    foreach ($allstorelogsection as $key => $element) {
                        $section_id = $element->section_id;
                        $store_log_id = $element->store_log_id;
                        $part_id = $element->stores_log->all_tractors[0]->part_id;
                        $source_id = $element->stores_log->all_tractors[0]->source_id;
                        $status_id = $element->stores_log->all_tractors[0]->status_id;
                        $quality_id = $element->stores_log->all_tractors[0]->quality_id;
                        $orderSupplierId = $element->stores_log->all_tractors[0]->order_supplier_id;

                        StoreSection::where('type_id', $type_id)->where('part_id', $part_id)->where('source_id', $source_id)->where('section_id', $section_id)
                            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('order_supplier_id', $orderSupplierId)
                            ->where('amount', '>', '0')->decrement('amount', $element->amount);
                    }
                } elseif ($type_id == 4) {
                    $allstorelogsection = StorelogSection::where('store_log_id', $Store_log_id)->with('stores_log.all_clarks')->get();
                    foreach ($allstorelogsection as $key => $element) {
                        $section_id = $element->section_id;
                        $store_log_id = $element->store_log_id;
                        $part_id = $element->stores_log->all_clarks[0]->part_id;
                        $source_id = $element->stores_log->all_clarks[0]->source_id;
                        $status_id = $element->stores_log->all_clarks[0]->status_id;
                        $quality_id = $element->stores_log->all_clarks[0]->quality_id;
                        $orderSupplierId = $element->stores_log->all_clarks[0]->order_supplier_id;

                        StoreSection::where('type_id', $type_id)->where('part_id', $part_id)->where('source_id', $source_id)->where('section_id', $section_id)
                            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('order_supplier_id', $orderSupplierId)
                            ->where('amount', '>', '0')->decrement('amount', $element->amount);
                    }
                } elseif ($type_id == 5) {
                    $allstorelogsection = StorelogSection::where('store_log_id', $Store_log_id)->with('stores_log.all_equips')->get();
                    foreach ($allstorelogsection as $key => $element) {
                        $section_id = $element->section_id;
                        $store_log_id = $element->store_log_id;
                        $part_id = $element->stores_log->all_equips[0]->part_id;
                        $source_id = $element->stores_log->all_equips[0]->source_id;
                        $status_id = $element->stores_log->all_equips[0]->status_id;
                        $quality_id = $element->stores_log->all_equips[0]->quality_id;
                        $orderSupplierId = $element->stores_log->all_equips[0]->order_supplier_id;

                        StoreSection::where('type_id', $type_id)->where('part_id', $part_id)->where('source_id', $source_id)->where('section_id', $section_id)
                            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('order_supplier_id', $orderSupplierId)
                            ->where('amount', '>', '0')->decrement('amount', $element->amount);
                    }
                }
            } elseif ($store_action_id == 6) {

                $x = StoresLog::where('stores_log.id', '=', $Store_log_id)->get('amount');
                $damage_amount = $x[0]->amount;

                $store_model = ucfirst($store_table_name);
                if ($store_model == "Damaged_parts") {
                    $store_model = "DamagedPart";
                }
                $entity = 'App\\Models\\' . $store_model;
                $y = $entity::leftjoin('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                    ->where('stores_log.All_part_id', '=', $all_p_id)->get();
                $supplier_order_id = $y[0]->supplier_order_id;


                StoresLog::where('status', $Store_log_id)
                    ->update([
                        'status' => 1
                    ]);


                StoresLog::where('id', $Store_log_id)
                    ->update([
                        'status' => 1
                    ]);


                $old_s_amount = $entity::where($store_table_name . '.store_log_id', '=',  $y[0]->store_log_id)->get('amount');
                $entity::where($store_table_name . '.store_log_id', '=',  $y[0]->store_log_id)
                    ->update([
                        'amount' => intval($old_s_amount[0]->amount) - intval($amount),
                        'store_log_id' => $Store_log_id
                    ]);
                // $damage_tableName=Store::where()
                $damage_row = DamagedPart::leftjoin('stores_log', 'damaged_parts.store_log_id', '=', 'stores_log.id')
                    ->where('stores_log.All_part_id', '=', $all_p_id)
                    ->select('stores_log.*', 'damaged_parts.amount as oldamount', 'damaged_parts.*')
                    ->get();
                // return $damage_row;
                if (count($damage_row) > 0) {
                    DamagedPart::where('damaged_parts.store_log_id', '=', $damage_row[0]->store_log_id)
                        ->update([
                            'amount' => intval($damage_row[0]->oldamount) + intval($amount),
                            'store_log_id' => $damage_row[0]->store_log_id
                        ]);
                } else {
                    DamagedPart::create([
                        'all_part_id' => $all_p_id,
                        'amount' => $amount,
                        'supplier_order_id' => $supplier_order_id,
                        'type_id' => $type_id,
                        'store_log_id' => $Store_log_id,
                        'date' => date('Y-m-d H:i:s'),
                    ]);
                }
                // return $store_table_name;
                // remove items from old store sections 0000000000000000
                if ($type_id == 1) {
                    $allstorelogsection = StorelogSection::where('store_log_id', $Store_log_id)->with('stores_log.all_parts')->get();
                    foreach ($allstorelogsection as $key => $element) {
                        $section_id = $element->section_id;
                        $store_log_id = $element->store_log_id;
                        $part_id = $element->stores_log->all_parts[0]->part_id;
                        $source_id = $element->stores_log->all_parts[0]->source_id;
                        $status_id = $element->stores_log->all_parts[0]->status_id;
                        $quality_id = $element->stores_log->all_parts[0]->quality_id;
                        $orderSupplierId = $element->stores_log->all_parts[0]->order_supplier_id;

                        StoreSection::where('type_id', $type_id)->where('part_id', $part_id)
                            ->where('source_id', $source_id)->where('status_id', $status_id)->where('section_id', $section_id)
                            ->where('quality_id', $quality_id)->where('order_supplier_id', $orderSupplierId)
                            ->where('amount', '>', '0')->decrement('amount', $element->amount);
                    }
                } elseif ($type_id == 2) {
                    $allstorelogsection = StorelogSection::where('store_log_id', $Store_log_id)->with('stores_log.all_wheels')->get();
                    foreach ($allstorelogsection as $key => $element) {
                        $section_id = $element->section_id;
                        $store_log_id = $element->store_log_id;
                        $part_id = $element->stores_log->all_wheels[0]->part_id;
                        $source_id = $element->stores_log->all_wheels[0]->source_id;
                        $status_id = $element->stores_log->all_wheels[0]->status_id;
                        $quality_id = $element->stores_log->all_wheels[0]->quality_id;
                        $orderSupplierId = $element->stores_log->all_wheels[0]->order_supplier_id;

                        StoreSection::where('type_id', $type_id)->where('part_id', $part_id)->where('source_id', $source_id)->where('section_id', $section_id)
                            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('order_supplier_id', $orderSupplierId)
                            ->where('amount', '>', '0')->decrement('amount', $element->amount);
                    }
                } elseif ($type_id == 6) {
                    $allstorelogsection = StorelogSection::where('store_log_id', $Store_log_id)->with('stores_log.all_kits')->get();
                    foreach ($allstorelogsection as $key => $element) {
                        $section_id = $element->section_id;
                        $store_log_id = $element->store_log_id;
                        $part_id = $element->stores_log->all_kits[0]->part_id;
                        $source_id = $element->stores_log->all_kits[0]->source_id;
                        $status_id = $element->stores_log->all_kits[0]->status_id;
                        $quality_id = $element->stores_log->all_kits[0]->quality_id;
                        $orderSupplierId = $element->stores_log->all_kits[0]->order_supplier_id;

                        StoreSection::where('type_id', $type_id)->where('part_id', $part_id)->where('source_id', $source_id)->where('section_id', $section_id)
                            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('order_supplier_id', $orderSupplierId)
                            ->where('amount', '>', '0')->decrement('amount', $element->amount);
                    }
                } elseif ($type_id == 3) {
                    $allstorelogsection = StorelogSection::where('store_log_id', $Store_log_id)->with('stores_log.all_tractors')->get();
                    foreach ($allstorelogsection as $key => $element) {
                        $section_id = $element->section_id;
                        $store_log_id = $element->store_log_id;
                        $part_id = $element->stores_log->all_tractors[0]->part_id;
                        $source_id = $element->stores_log->all_tractors[0]->source_id;
                        $status_id = $element->stores_log->all_tractors[0]->status_id;
                        $quality_id = $element->stores_log->all_tractors[0]->quality_id;
                        $orderSupplierId = $element->stores_log->all_tractors[0]->order_supplier_id;

                        StoreSection::where('type_id', $type_id)->where('part_id', $part_id)->where('source_id', $source_id)->where('section_id', $section_id)
                            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('order_supplier_id', $orderSupplierId)
                            ->where('amount', '>', '0')->decrement('amount', $element->amount);
                    }
                } elseif ($type_id == 4) {
                    $allstorelogsection = StorelogSection::where('store_log_id', $Store_log_id)->with('stores_log.all_clarks')->get();
                    foreach ($allstorelogsection as $key => $element) {
                        $section_id = $element->section_id;
                        $store_log_id = $element->store_log_id;
                        $part_id = $element->stores_log->all_clarks[0]->part_id;
                        $source_id = $element->stores_log->all_clarks[0]->source_id;
                        $status_id = $element->stores_log->all_clarks[0]->status_id;
                        $quality_id = $element->stores_log->all_clarks[0]->quality_id;
                        $orderSupplierId = $element->stores_log->all_clarks[0]->order_supplier_id;

                        StoreSection::where('type_id', $type_id)->where('part_id', $part_id)->where('source_id', $source_id)->where('section_id', $section_id)
                            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('order_supplier_id', $orderSupplierId)
                            ->where('amount', '>', '0')->decrement('amount', $element->amount);
                    }
                } elseif ($type_id == 5) {
                    $allstorelogsection = StorelogSection::where('store_log_id', $Store_log_id)->with('stores_log.all_equips')->get();
                    foreach ($allstorelogsection as $key => $element) {
                        $section_id = $element->section_id;
                        $store_log_id = $element->store_log_id;
                        $part_id = $element->stores_log->all_equips[0]->part_id;
                        $source_id = $element->stores_log->all_equips[0]->source_id;
                        $status_id = $element->stores_log->all_equips[0]->status_id;
                        $quality_id = $element->stores_log->all_equips[0]->quality_id;
                        $orderSupplierId = $element->stores_log->all_equips[0]->order_supplier_id;

                        StoreSection::where('type_id', $type_id)->where('part_id', $part_id)->where('source_id', $source_id)->where('section_id', $section_id)
                            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('order_supplier_id', $orderSupplierId)
                            ->where('amount', '>', '0')->decrement('amount', $element->amount);
                    }
                }
            }
            DB::commit();
            return $Store_log_id;
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash("success", "");
            return $e;
            // return redirect()->back();
        }
    }

    public function refuse_store_trans(Request $request)
    {
        // return  $request;
        $Store_log_id = $request->data['Store_log_id'];
        StoresLog::where('id', $Store_log_id)
            ->update([
                'status' => -1
            ]);
        return $Store_log_id;
    }
    public function hide_store_trans(Request $request)
    {
        $Store_log_id = $request->data['Store_log_id'];
        StoresLog::where('id', $Store_log_id)
            ->update([
                'status' => -2
            ]);
        return $Store_log_id;
    }
}
