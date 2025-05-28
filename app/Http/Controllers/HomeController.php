<?php
namespace App\Http\Controllers;
use App\Models\BuyTransaction;
use App\Models\OrderSupplier;
use App\Models\Invoice;
use App\Models\BankSafeMoney;
use App\Models\PresaleOrder;
use App\Models\StoresLog;
use App\Models\Store;
use App\Http\Controllers\POSController;
use App\Models\CurrencyType;
use App\Models\MoneySafe;
use App\Models\PricingType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use Yajra\DataTables\Facades\DataTables;


class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Buy_Transaction_Count = BuyTransaction::count('id');

        $Buy_Transaction_Total = OrderSupplier::with([
            'currency_type' => function ($query) {
                return $query->with([
                    'currencies' => function ($q) {
                        $q->where('to', '=', null);
                    },
                ]);
            },
        ])->get();


        $buy_Invoice_today = OrderSupplier::whereDate('confirmation_date',Carbon::now())->sum('paied');
        $buy_Invoice_today_count = OrderSupplier::whereDate('confirmation_date',Carbon::now())->count('id');

        $buy_Invoice_last = OrderSupplier::with('supplier')->with('currency_type')->orderBy('id', 'desc')->first();

        // return $Buy_Transaction_Total;
        $sum_Buy_Total = $Buy_Transaction_Total->sum(function ($item) {
            return $item->total_price * ($item->currency_type ? $item->currency_type->currencies[0]->value : 0);
        });

        $sell_Transaction_Count = Invoice::count('id');
        $sell_Transaction_Total = Invoice::sum('actual_price');
        $sell_Invoice_today = Invoice::whereDate('date',Carbon::now())->sum('paied');
        $sell_Invoice_today_count = Invoice::whereDate('date',Carbon::now())->count('id');
        $sell_Invoice_last = Invoice::with('client')->where('flag',0)->orderBy('id', 'desc')->first();

        $presalorder_count = PresaleOrder::count('id');
        $presalorder_count_done = PresaleOrder::where('flag',3)->count('id');
        $presalorder_Total = PresaleOrder::sum('total');
        $presalorder_last = PresaleOrder::with('client')->where('flag',0)->orderBy('id', 'desc')->first();


        $store_selling = DB::table('invoice')->leftJoin('store', 'store.id', '=', 'invoice.store_id')->select(DB::raw('count(*) as total_inv'), DB::raw('sum(price_without_tax) as total_money'), DB::raw('sum(tax_amount)as total_tax'))->groupBy('store.id')->get();


        $bankMoney = BankSafeMoney::all();
        $current_balance_1 = $bankMoney->where('type_money', 1)->sum('money');
        $current_balance_2 = $bankMoney->where('type_money', 0)->sum('money');
        $current_balance_bank = $current_balance_2 - $current_balance_1;

        $safeMoney = MoneySafe::all();
        $current_balance_3 = $safeMoney->where('type_money', 1)->sum('money');
        $current_balance_4 = $safeMoney->where('type_money', 0)->sum('money');
        $current_balance_safe = $current_balance_4 - $current_balance_3;
        $current_balance =  $current_balance_bank + $current_balance_safe;
        $data_inbox = $this->inbox_admin_history();



        return view('index', compact('buy_Invoice_last','sell_Invoice_last','presalorder_last','buy_Invoice_today','buy_Invoice_today_count','sell_Invoice_today_count','sell_Invoice_today','presalorder_count','presalorder_count_done', 'presalorder_Total', 'store_selling', 'data_inbox', 'Buy_Transaction_Count', 'sum_Buy_Total', 'sell_Transaction_Count', 'sell_Transaction_Total', 'current_balance'));
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
    public function show(Country $country)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }



    public function inbox_admin_history()
    {
        $inbox_history = StoresLog::where('stores_log.status', '>', '-1')
            ->where('stores_log.status', '<>', '1')->where('stores_log.status', '<', '4')->get();
        $inbox_history_all = [];
        for ($i = 0; $i < count($inbox_history); $i++) {
            $arr = $this->check_p_type($inbox_history[$i]->type_id);
            $all_data = StoresLog::
                with('type')
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
                if(count($all_data)>0){
                    array_push($inbox_history_all, $all_data[0]);
                }else{
                    //  array_push($inbox_history_all,[ $all_data[0]]);
                }

            // return  $all_data;
        }

        // return $inbox_history_all;
        $data_inbox = $this->inbox_admin();

        // return $inbox_history_all;

        // return $dataTable->render('store.inbox_admin_transaction',compact('inbox_history_all','data_inbox'));
        return $data_inbox;
    }

    public function inbox_admin_history1(Request $request)
    {
        if ($request->ajax()) {
            event(new \App\Events\SaveTransaction());
            $inbox_history = StoresLog::
                where('stores_log.status', '>', '-1')
                ->where('stores_log.status', '<>', '1')->get();
            $inbox_history_all = [];
            for ($i = 0; $i < count($inbox_history); $i++) {
                $arr = $this->check_p_type($inbox_history[$i]->type_id);
                $all_data = StoresLog::
                    with('type')
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
                array_push($inbox_history_all, $all_data[0]);
                // return  $all_data;
            }
            $data = $inbox_history_all;
            // return $data;
            return DataTables::of($data)
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

        // return $inbox_history_all;
        // $data_inbox= $this->inbox_admin();

        // return Datatables::of($inbox_history_all);

        // return $dataTable->render('store.inbox_admin_transaction',compact('inbox_history_all','data_inbox'));
        // return view('store.inbox_admin_transaction',compact('data_inbox'));
    }
    public function inbox_admin()
    {
        return StoresLog::with('store_action')
            ->with('type')
            ->with('store')
            ->where('stores_log.status', '>', '-1')
            ->where('stores_log.status', '<>', '1')
            ->where('stores_log.status', '<>', '3')
            ->get();
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

     public function calender(){
        $buybillDueDate = OrderSupplier::with('buy_transaction')->with('supplier')->where('due_date','<>',null)->get();
        return view('calender',compact('buybillDueDate'));
    }
       public function get_stores_money($store_id)
    {
        Store::all();
        $request = new Request(['storeId' => $store_id]);

        $query1x = new Collection();
        $query2x = new Collection();
        $query3x = new Collection();
        $store = Store::where('id', $request->storeId)->first();

        $entity_tbl = 'App\\Models\\' . ucfirst($store->table_name);
        if ($store->table_name == 'damaged_parts') {
            $entity_tbl = 'damagedPart';
        }

        /*********************************************/
        // return $entity_tbl;
        $query1x = $entity_tbl
            ::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
            ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
            ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_parts.part_id, all_parts.source_id, all_parts.status_id, all_parts.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
            ->groupBy('all_parts.part_id', 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id', $store->table_name . '.type_id')
            ->where($store->table_name . '.type_id', 1);
        $query1x = $query1x
            ->with([
                'stores_log.all_parts.part.part_numbers',
                'stores_log.all_parts.source',
                'stores_log.all_parts.status',
                'stores_log.all_parts.part_quality',
                'stores_log.all_parts.sectionswithoutorder' => function ($query) use ($store) {
                    $query->where('store_id', $store->id);
                },
                'stores_log.all_parts.sectionswithoutorder.store',
                'stores_log.all_parts.sectionswithoutorder.store_structure',
                'stores_log.all_parts.pricing.sale_type',
                'stores_log.all_parts.part.part_details.part_spec',
                'stores_log.all_parts.order_supplier.currency_type',
                'stores_log.all_parts.replayorderss',
                'stores_log.all_parts.order_supplier.buy_transaction',
                'stores_log.all_parts.part.sub_group.group',
            ])
            ->get();

        $query2x = $entity_tbl
            ::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
            ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
            ->where($store->table_name . '.type_id', 6)
            ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_kits.part_id, all_kits.source_id, all_kits.status_id, all_kits.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
            ->groupBy('all_kits.part_id', 'all_kits.source_id', 'all_kits.status_id', 'all_kits.quality_id', $store->table_name . '.type_id')
            ->with([
                'stores_log.all_kits.kit.kit_numbers',
                'stores_log.all_kits.source',
                'stores_log.all_kits.status',
                'stores_log.all_kits.part_quality',

                'stores_log.all_kits.sectionswithoutorder' => function ($query) use ($store) {
                    $query->where('store_id', $store->id);
                },
                'stores_log.all_kits.sectionswithoutorder.store',
                'stores_log.all_kits.sectionswithoutorder.store_structure',
                'stores_log.all_kits.pricing.sale_type',
                'stores_log.all_kits.kit.kit_details.kit_spec',
                'stores_log.all_kits.order_supplier.buy_transaction',
                'stores_log.all_kits.order_supplier.currency_type',
                'stores_log.all_kits.replayorderss',
            ])
            ->get();

        $query3x = $entity_tbl
            ::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
            ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
            ->where($store->table_name . '.type_id', 2)
            ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_wheels.part_id, all_wheels.source_id, all_wheels.status_id, all_wheels.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
            ->groupBy('all_wheels.part_id', 'all_wheels.source_id', 'all_wheels.status_id', 'all_wheels.quality_id', $store->table_name . '.type_id')
            ->with([
                'stores_log.all_wheels.source',
                'stores_log.all_wheels.status',
                'stores_log.all_wheels.part_quality',

                'stores_log.all_wheels.sectionswithoutorder' => function ($query) use ($store) {
                    $query->where('store_id', $store->id);
                },
                'stores_log.all_wheels.sectionswithoutorder.store',
                'stores_log.all_wheels.sectionswithoutorder.store_structure',
                'stores_log.all_wheels.pricing.sale_type',
                'stores_log.all_wheels.wheel.wheel_details.wheel_spec',

                'stores_log.all_wheels.order_supplier.buy_transaction',
                'stores_log.all_wheels.order_supplier.currency_type',
                'stores_log.all_wheels.replayorderss',
            ])
            ->get();

        $query4x = $entity_tbl
            ::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
            ->join('all_tractors', 'stores_log.All_part_id', '=', 'all_tractors.id')
            ->where($store->table_name . '.type_id', 3)
            ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_tractors.part_id, all_tractors.source_id, all_tractors.status_id, all_tractors.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
            ->groupBy('all_tractors.part_id', 'all_tractors.source_id', 'all_tractors.status_id', 'all_tractors.quality_id', $store->table_name . '.type_id')
            ->with([
                'stores_log.all_tractors.source',
                'stores_log.all_tractors.status',
                'stores_log.all_tractors.part_quality',

                'stores_log.all_tractors.sectionswithoutorder' => function ($query) use ($store) {
                    $query->where('store_id', $store->id);
                },
                'stores_log.all_tractors.sectionswithoutorder.store',
                'stores_log.all_tractors.sectionswithoutorder.store_structure',
                'stores_log.all_tractors.pricing.sale_type',
                'stores_log.all_tractors.tractor.tractor_details.tractor_spec',

                'stores_log.all_tractors.order_supplier.buy_transaction',
                'stores_log.all_tractors.order_supplier.currency_type',
                'stores_log.all_tractors.replayorderss',
            ])
            ->get();

        $query5x = $entity_tbl
            ::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
            ->join('all_clarks', 'stores_log.All_part_id', '=', 'all_clarks.id')
            ->where($store->table_name . '.type_id', 4)
            ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_clarks.part_id, all_clarks.source_id, all_clarks.status_id, all_clarks.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
            ->groupBy('all_clarks.part_id', 'all_clarks.source_id', 'all_clarks.status_id', 'all_clarks.quality_id', $store->table_name . '.type_id')
            ->with([
                'stores_log.all_clarks.source',
                'stores_log.all_clarks.status',
                'stores_log.all_clarks.part_quality',

                'stores_log.all_clarks.sectionswithoutorder' => function ($query) use ($store) {
                    $query->where('store_id', $store->id);
                },
                'stores_log.all_clarks.sectionswithoutorder.store',
                'stores_log.all_clarks.sectionswithoutorder.store_structure',
                'stores_log.all_clarks.pricing.sale_type',
                'stores_log.all_clarks.clark.clark_details.clark_spec',

                'stores_log.all_clarks.order_supplier.buy_transaction',
                'stores_log.all_clarks.order_supplier.currency_type',
                'stores_log.all_clarks.replayorderss',
            ])
            ->get();

        $query6x = $entity_tbl
            ::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
            ->join('all_equips', 'stores_log.All_part_id', '=', 'all_equips.id')
            ->where($store->table_name . '.type_id', 5)
            ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_equips.part_id, all_equips.source_id, all_equips.status_id, all_equips.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
            ->groupBy('all_equips.part_id', 'all_equips.source_id', 'all_equips.status_id', 'all_equips.quality_id', $store->table_name . '.type_id')
            ->with([
                'stores_log.all_equips.source',
                'stores_log.all_equips.status',
                'stores_log.all_equips.part_quality',
                'stores_log.all_equips.sectionswithoutorder' => function ($query) use ($store) {
                    $query->where('store_id', $store->id);
                },
                'stores_log.all_equips.sectionswithoutorder.store',
                'stores_log.all_equips.sectionswithoutorder.store_structure',
                'stores_log.all_equips.pricing.sale_type',
                'stores_log.all_equips.equip.equip_details.equip_spec',

                'stores_log.all_equips.order_supplier.buy_transaction',
                'stores_log.all_equips.order_supplier.currency_type',
                'stores_log.all_equips.replayorderss',
            ])
            ->get();

        $mergedResults = $query1x->concat($query2x)->concat($query3x)->concat($query4x)->concat($query5x)->concat($query6x);

        $total_item_buy_price = 0;
        $sell_price = [];
        $sell_name = [];
        $saletype = PricingType::all();
        for ($i = 0; $i < count($saletype); $i++) {
            array_push($sell_price, (object) ['sale_val' . $i => 0]);
            array_push($sell_name, (object) ['sale_val' . $i => $saletype[$i]->type]);
        }
        $flattened_sell_price = new stdClass();
        $flattened_sell_name = new stdClass();

        foreach ($sell_price as $obj) {
            foreach ($obj as $key => $value) {
                $flattened_sell_price->$key = $value;
            }
        }
        foreach ($sell_name as $obj) {
            foreach ($obj as $key => $value) {
                $flattened_sell_name->$key = $value;
            }
        }
        //    return $flattened_sell_price;
        foreach ($mergedResults as $element) {
            if ($element->type_id == 1) {
                $all_data = $element;
                $all_data = $element->stores_log->all_parts[0];
            } elseif ($element->type_id == 2) {
                $all_data = $element->stores_log->all_wheels[0];
            } elseif ($element->type_id == 3) {
                $all_data = $element->stores_log->all_tractors[0];
            } elseif ($element->type_id == 4) {
                $all_data = $element->stores_log->all_clarks[0];
            } elseif ($element->type_id == 5) {
                $all_data = $element->stores_log->all_equips[0];
            } elseif ($element->type_id == 6) {
                $all_data = $element->stores_log->all_kits[0];
            }

            $Ac_currency_id = $all_data->order_supplier->currency_id;
            $Ac_currency_date = $all_data->order_supplier->confirmation_date;
            $Ac_all_currency_types = CurrencyType::with([
                'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                    return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                },
            ])
                ->where('id', $Ac_currency_id)
                ->get();

            $total_item_buy_price += $element->amount * ($all_data->replayorderss->price * $Ac_all_currency_types[0]->currencies[0]->value);

            if (count($all_data->pricing) > 0) {
                // return $all_data->pricing;
                for ($i = 0; $i < count($all_data->pricing); $i++) {
                    //    'sale_val'.$i += $all_data->pricing[$i]->price * $element->amount;
                    $x = 'sale_val' . $i;
                    // $x = $all_data->pricing[$i]->sale_typex->type;
                     if (isset($flattened_sell_price->$x)) {
                            $flattened_sell_price->$x += (($all_data->pricing[$i])?$all_data->pricing[$i]->price : 0) * $element->amount; 
                        }
                    
                    // $flattened_sell_price->$x += $all_data->pricing[$i]->price * $element->amount;
                }
            }
        }
         $flattened_sell_price;
        // return $sell_name;
         $total_item_buy_price;

        //to calc stor with all currenceies
           $Ac_all_currency_types = CurrencyType::with([
            'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                return $query->where('from', '>=', $Ac_currency_date)
                ->where('to', '<=', $Ac_currency_date)->orWhere('to', '=', null);
            },
        ])

            ->get();

            $currency_compare=[];


            foreach ($Ac_all_currency_types as $curr) {
                $currency_sell=[];
                foreach ($flattened_sell_price as $key=> $value) {
                    // return $value;

                    array_push($currency_sell, (object) [$key => $value/ $curr->currencies['0']->value , 'data' => $sell_name]);
                }
                // return $currency_sell;
                //شراء بالعملات المختلفة
                array_push($currency_compare, (object) ['curr'=> $curr->name , 'data' => (object) ['buy_price'=>$total_item_buy_price / $curr->currencies['0']->value,'sell_price'=>$currency_sell ]]);
            }
            return $currency_compare;


    }

    public function  get_stores_money_view(){
        $allstores = Store::where('table_name','<>','damaged_parts')->get();
        return view('store_volume',compact('allstores'));
    }

}
