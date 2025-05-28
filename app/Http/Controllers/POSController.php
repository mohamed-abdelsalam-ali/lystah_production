<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\NotifyUser;
use App\Models\AllClark;
use App\Models\RefundInvoice;
use App\Models\AllEquip;
use App\Models\AllKit;
use App\Models\AllKitPartItem;
use App\Models\AllKitPartItemSection;
use App\Models\RefundInvoicePayment;
use App\Models\Employee;
use App\Models\AllPart;
use App\Models\Brand;
use App\Models\BrandType;
use App\Models\Client;
use App\Models\PresaleOrderPart;
use App\Models\Newqayd;
use App\Models\NQayd;
use App\Models\Group;
use App\Models\Invoice;
use App\Models\InvoiceClientMadyonea;
use App\Models\InvoiceItem;
use App\Models\InvoicesTax;
use App\Models\Part;
use App\Models\BranchTree;
use App\Models\PricingType;
use App\Models\Wheel;
use App\Models\PartImage;
use App\Models\WheelImage;
use App\Models\Tractor;
use App\Models\StorelogSection;
use App\Models\SaleType;
use App\Models\TractorImage;
use App\Models\ServiceInvoice;
use App\Models\InvoiceItemsOrderSupplier;
use App\Models\InvoiceItemsSection;
use App\Models\BuyTransaction;
use App\Models\KitImage;
use App\Models\Kit;
use App\Http\Controllers\QaydController;
use App\Models\Model;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\PartModel;
use App\Models\SalePricing;
use App\Models\Source;
use App\Models\Status;
use App\Models\PartQuality;
use App\Models\Series;
use App\Models\Store;
use App\Models\StoreSection;
use App\Models\StoresLog;
use App\Models\StoreStructure;
use App\Models\SubGroup;
use App\Models\Tax;
use Illuminate\Contracts\Support\Jsonable;
use App\Models\AllTractor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\AllWheel;
use App\Models\BankSafeMoney;
use App\Models\BankType;
use App\Models\Clark;
use App\Models\ClarkImage;
use App\Models\Equip;
use App\Models\EquipImage;
use App\Models\MoneySafe;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\Replyorder;
use App\Models\CurrencyType;
use App\Models\DamagedPart;
use App\Models\DemandPart;
use App\Models\Qayditem;
use App\Models\SanadSarf;
use App\Models\Supplier;
use App\Models\SupplierMadyonea;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Illuminate\Support\Arr;
use App\Models\Talef;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

use function PHPSTORM_META\type;

class POSController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $storeIdd;

    public function index(Request $request)
    {
        //
        ini_set('memory_limit', '-1');
        // return $request;
        $storeId = $request->storeId;
        $store_data = Store::where('id', '=', $storeId)->get();
        $items_part = $this->get_parts_info($store_data[0]->table_name);

        $items_wheel = $this->get_wheel_info($store_data[0]->table_name);
        // $items_wheel = [];
        // $items_tractor = $this->get_tractor_info($store_data[0]->table_name);
        $items_tractor = [];

        // $items_clarck = $this->get_clarck_info($store_data[0]->table_name);
        $items_clarck = [];


        // $items_equip = $this->get_equip_info($store_data[0]->table_name);
        $items_equip = [];


        $items_kit = $this->get_kit_info($store_data[0]->table_name);

        $allItems = new \Illuminate\Database\Eloquent\Collection(); //Create empty collection which we know has the merge() method
        $allItems = $allItems->concat($items_part);
        $allItems = $allItems->concat($items_wheel);
        $allItems = $allItems->concat($items_tractor);
        $allItems = $allItems->concat($items_clarck);
        $allItems = $allItems->concat($items_equip);
        $allItems = $allItems->concat($items_kit);

        $allStores = Store::all();

        $allBrands = Brand::all();
        $allGroups = Group::all();
        $alltaxes = Tax::all();
        $clients = [];
        // $clients = Client::with('invoices')->with('invoice_client_madyoneas')->get();
        // foreach ($clients as $key => $client) {
        //     $as_sup_madunia = 0;
        //     // return $client->sup_id;
        //     if ($client->sup_id) {
        //         $xxs = Supplier::where('id', $client->sup_id)->first();
        //         $as_sup_madunia = 0 ;
        //         if($xxs){
        //             $as_sup_madunia = $xxs->raseed;
        //         }else{
        //             $as_sup_madunia = 0;
        //         }

        //     }
        //     $servicesMad = ServiceInvoice::where('client_id', $client->id)->sum('remain');
        //     $client['as_sup_madunia'] = $as_sup_madunia;
        //     $client['servicesMad'] = $servicesMad;
        // }


        // $allSections = StoreStructure::where('store_id', $storeId)
        //     ->with([
        //         'store_sections' => function ($q) {
        //             $q->where('type_id', 1)->with('part')->with('source')->with('type')->with('status')->with('part_quality')->get();
        //             $q->where('type_id', 2)->with('wheel')->with('source')->with('type')->with('status')->with('part_quality')->get();
        //             $q->where('type_id', 3)->with('tractor')->with('source')->with('type')->with('status')->with('part_quality')->get();
        //             $q->where('type_id', 4)->with('clark')->with('source')->with('type')->with('status')->with('part_quality')->get();
        //             $q->where('type_id', 5)->with('equip')->with('source')->with('type')->with('status')->with('part_quality')->get();
        //             $q->where('type_id', 6)->with('kit')->with('source')->with('type')->with('status')->with('part_quality')->get();
        //         },
        //     ])
        //     ->get();

        $allSections = [];

        $allClients = Client::with('invoices')->with('invoice_client_madyoneas')->get();

        $allGroups = Group::all();
        $allSGroups = SubGroup::all();
        $Btype = BrandType::all();
        $allbrand = Brand::all();
        $allmodel = Model::all();
        $allseries = Series::all();
        $allprices = PricingType::all();
        $this->storeIdd = $storeId;
        // $data_inbox= $this->inbox_admin();
        $store_inbox = $this->store_inbox($storeId);
        // $store_item= $this->store_inbox($storeId);
        // return $allItems;

        $bank_types = BankType::all();
        $store_safe = Store::where('safe_accountant_number', '<>', '0')->get();

        return view('pos_dt', compact('bank_types', 'store_safe', 'allBrands', 'store_data', 'allItems', 'alltaxes', 'clients', 'allSections', 'allClients', 'store_inbox', 'allStores', 'allGroups', 'allSGroups', 'Btype', 'allbrand', 'allmodel', 'allseries', 'allprices'));
    }


    public function pos1(Request $request)
    {
        //
        // return $request;
        $storeId = $request->storeId;
        $store_data = Store::where('id', '=', $storeId)->get();
        $items_part = $this->get_parts_info2($store_data[0]->table_name);
        // return $items_part;
        $items_wheel = $this->get_wheel_info($store_data[0]->table_name);
        $items_tractor = $this->get_tractor_info($store_data[0]->table_name);
        $items_clarck = $this->get_clarck_info($store_data[0]->table_name);
        // return $items_tractor;
        $items_equip = $this->get_equip_info($store_data[0]->table_name);
        // return $items_equip;
        $items_kit = $this->get_kit_info($store_data[0]->table_name);

        $allItems = new \Illuminate\Database\Eloquent\Collection(); //Create empty collection which we know has the merge() method
        $allItems = $allItems->concat($items_part);

        $allItems = $allItems->concat($items_wheel);
        $allItems = $allItems->concat($items_tractor);
        $allItems = $allItems->concat($items_clarck);
        $allItems = $allItems->concat($items_equip);
        $allItems = $allItems->concat($items_kit);
        // $data_inbox= $this->inbox_admin();

        $allStores = Store::all();

        $allBrands = Brand::all();
        $allGroups = Group::all();
        $alltaxes = Tax::all();
        $clients = Client::with('invoices')->with('invoice_client_madyoneas')->get();

        foreach ($clients as $key => $client) {
            $as_sup_madunia = 0;
            // return $client->sup_id;
            if ($client->sup_id) {
                $xxs = Supplier::where('id', $client->sup_id)->first();
                $as_sup_madunia = 0;
                if ($xxs) {
                    $as_sup_madunia = $xxs->raseed;
                } else {
                    $as_sup_madunia = 0;
                }
            }
            $servicesMad = ServiceInvoice::where('client_id', $client->id)->sum('remain');
            $client['as_sup_madunia'] = $as_sup_madunia;
            $client['servicesMad'] = $servicesMad;
        }

        // return view('ecommerce.index',compact('allBrands','allGroups','allItems'));
        // return $items_part;
        $allSections = StoreStructure::where('store_id', $storeId)
            ->with([
                'store_sections' => function ($q) {
                    $q->where('type_id', 1)->with('part')->with('source')->with('type')->with('status')->with('part_quality')->get();
                    $q->where('type_id', 2)->with('wheel')->with('source')->with('type')->with('status')->with('part_quality')->get();
                    $q->where('type_id', 3)->with('tractor')->with('source')->with('type')->with('status')->with('part_quality')->get();
                    $q->where('type_id', 4)->with('clark')->with('source')->with('type')->with('status')->with('part_quality')->get();
                    $q->where('type_id', 5)->with('equip')->with('source')->with('type')->with('status')->with('part_quality')->get();
                    $q->where('type_id', 6)->with('kit')->with('source')->with('type')->with('status')->with('part_quality')->get();
                },
            ])
            ->get();

        $allClients = Client::with('invoices')->with('invoice_client_madyoneas')->get();

        $allGroups = Group::all();
        $allSGroups = SubGroup::all();
        $Btype = BrandType::all();
        $allbrand = Brand::all();
        $allmodel = Model::all();
        $allseries = Series::all();
        $allprices = PricingType::all();
        $this->storeIdd = $storeId;
        // $data_inbox= $this->inbox_admin();
        $store_inbox = $this->store_inbox($storeId);
        // $store_item= $this->store_inbox($storeId);
        // return $allItems;

        $bank_types = BankType::all();
        $store_safe = Store::where('safe_accountant_number', '<>', '0')->get();

        return view('pos_dt1', compact('bank_types', 'allItems', 'store_safe', 'allBrands', 'store_data', 'alltaxes', 'clients', 'allSections', 'allClients', 'store_inbox', 'allStores', 'allGroups', 'allSGroups', 'Btype', 'allbrand', 'allmodel', 'allseries', 'allprices'));
    }


    public function pos1_items(Request $request)
    {
        //
        // return $request;
        $storeId = $request->storeId;
        $store_data = Store::where('id', '=', $storeId)->get();
        $items_part = $this->get_parts_info($store_data[0]->table_name);
        // return $items_part;
        $items_wheel = $this->get_wheel_info($store_data[0]->table_name);
        $items_tractor = $this->get_tractor_info($store_data[0]->table_name);
        $items_clarck = $this->get_clarck_info($store_data[0]->table_name);
        // return $items_tractor;
        $items_equip = $this->get_equip_info($store_data[0]->table_name);
        // return $items_equip;
        $items_kit = $this->get_kit_info($store_data[0]->table_name);

        $allItems = new \Illuminate\Database\Eloquent\Collection(); //Create empty collection which we know has the merge() method
        $allItems = $allItems->concat($items_part);
        $allItems = $allItems->concat($items_wheel);
        $allItems = $allItems->concat($items_tractor);
        $allItems = $allItems->concat($items_clarck);
        $allItems = $allItems->concat($items_equip);
        $allItems = $allItems->concat($items_kit);
        // $data_inbox= $this->inbox_admin();

        return response()->json($allItems);
    }

    public function shoptest()
    {
        //
        // return $request;
        $storeId = 5;
        $store_data = Store::where('id', '=', $storeId)->get();
        $items_part = $this->get_parts_info($store_data[0]->table_name);

        $items_wheel = $this->get_wheel_info($store_data[0]->table_name);
        $items_tractor = $this->get_tractor_info($store_data[0]->table_name);
        $items_clarck = $this->get_clarck_info($store_data[0]->table_name);
        $items_equip = $this->get_equip_info($store_data[0]->table_name);
        $items_kit = $this->get_kit_info($store_data[0]->table_name);

        $allItems = new \Illuminate\Database\Eloquent\Collection(); //Create empty collection which we know has the merge() method
        $allItems = $allItems->concat($items_part);
        $allItems = $allItems->concat($items_wheel);
        $allItems = $allItems->concat($items_tractor);
        $allItems = $allItems->concat($items_clarck);
        $allItems = $allItems->concat($items_equip);
        $allItems = $allItems->concat($items_kit);
        // $data_inbox= $this->inbox_admin();

        $allBrands = Brand::all();
        $allGroups = Group::all();
        $alltaxes = Tax::all();
        $clients = Client::with('invoices')->with('invoice_client_madyoneas')->get();
        // return view('ecommerce.index',compact('allBrands','allGroups','allItems'));
        // return $allItems;
        $allSections = StoreStructure::where('store_id', $storeId)
            ->with([
                'store_sections' => function ($q) {
                    $q->with('part')->with('source')->with('type')->with('status')->with('part_quality')->get();
                },
            ])
            ->get();
        $allClients = Client::with('invoices')->with('invoice_client_madyoneas')->get();
        // return $allClients;
        return view('ecommerce.shop-grid', compact('allBrands', 'store_data', 'allItems', 'alltaxes', 'clients', 'allSections', 'allClients'));
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

    public function inbox_admin()
    {
        return StoresLog::with('store_action')->with('type')->with('store')->where('stores_log.status', '>=', '-1')->where('stores_log.status', '<>', '1')->get();
    }

    public function store_inbox($storeId)
    {
        return StoresLog::with('store_action')
            ->with('type')
            ->with('store')
            ->where('stores_log.status', '=', '0')
            ->where('stores_log.store_id', '=', $storeId)
            ->whereIn('stores_log.store_action_id', [3, 1])
            ->get();
    }
    public function store_inbox_history( $storeId)
    {
        $storeId=Store::where('id',$storeId)->first();
        $inbox_history = StoresLog::where('stores_log.status', '=', '0')
            ->where('stores_log.store_id', '=', $storeId->id)
            ->whereIn('stores_log.store_action_id', [3, 1])
            ->get();
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
            array_push($inbox_history_all, $all_data[0]);
            // return  $all_data;
        }

        // return $inbox_history_all;
        $data_inbox = $this->store_inbox($storeId->id);

        // return $inbox_history_all;

        // return $dataTable->render('store.inbox_admin_transaction',compact('inbox_history_all','data_inbox'));
        return view('store.inbox_admin_transaction', compact('data_inbox'));
    }

    public function store_inbox_history1( $storeId, Request $request)
    {
        $storeId=Store::where('id',$storeId)->first();
        if ($request->ajax()) {
            // event(new \App\Events\SaveTransaction('inbox'));
            $inbox_history = StoresLog::where('stores_log.status', '=', '0')
                ->where('stores_log.store_id', '=', $storeId->id)
                ->whereIn('stores_log.store_action_id', [3, 1])
                ->get();

            $inbox_history_all = [];
            for ($i = 0; $i < count($inbox_history); $i++) {
                $arr = $this->check_p_type($inbox_history[$i]->type_id);
                $all_data = StoresLog::with('type')->with($arr[0].'.'.$arr[2].'.'.'bigunit')
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
            $data = $inbox_history_all;
            // return $data;
            return FacadesDataTables::of($data)
                ->make(true);
        }
    }

    public function store_items_history1($storeId, Request $request)
    {
        if ($request->ajax()) {
            // event(new \App\Events\SaveTransaction('inbox'));
            $storeId = $storeId;
            $store_data = Store::where('id', '=', $storeId)->get();
            $items_part = $this->get_parts_info($store_data[0]->table_name);

            $items_wheel = $this->get_wheel_info($store_data[0]->table_name);
            $items_tractor = $this->get_tractor_info($store_data[0]->table_name);
            $items_clarck = $this->get_clarck_info($store_data[0]->table_name);
            $items_equip = $this->get_equip_info($store_data[0]->table_name);
            $items_kit = $this->get_kit_info($store_data[0]->table_name);

            $allItems = new \Illuminate\Database\Eloquent\Collection(); //Create empty collection which we know has the merge() method
            $allItems = $allItems->concat($items_part);
            $allItems = $allItems->concat($items_wheel);
            $allItems = $allItems->concat($items_tractor);
            $allItems = $allItems->concat($items_clarck);
            $allItems = $allItems->concat($items_equip);
            $allItems = $allItems->concat($items_kit);

            // return $allItems;
            return FacadesDataTables::of($allItems)
                ->make(true);
        }
    }

    public function send_to_other_store(Request $request)
    {
        // return $request['data']['P_details'];
        $P_details = $request['data']['P_details'];
        $sent_amount = trim(isset($request['data']['sent_amount']) ? $request['data']['sent_amount'] : null);
        $other_store_id = trim(isset($request['data']['other_store_id']) ? $request['data']['other_store_id'] : null);
        $store_id = trim(isset($request['data']['store_id']) ? $request['data']['store_id'] : null);
        $part_id = $P_details['part_id'];
        $amount = $P_details['Tamount'];
        $type_id = $P_details['type_id'];
        $supplier_order_id = $P_details['supplier_order_id'];
        // $allPartId = $P_details['All_part_id'] ;
        $Store_log_id = $P_details['store_log_id'];
        // return $other_store_id;
        $store_data = Store::where('id', $store_id)->get();

        $other_store_data = Store::where('id', $other_store_id)->get();
        // return $other_store_data[0]['table_name'];

        $res_d = $this->check_p_type($type_id);
        $all_part_table = $res_d[0];
        $all_part_model = $res_d[1];
        $part_table = $res_d[2];
        // return $res_d;
        $entity = 'App\\Models\\' . $all_part_model;
        $all_p_data = $entity
            ::where('part_id', $part_id)
            ->where('source_id', $P_details['source_id'])
            ->where('status_id', $P_details['status_id'])
            ->where('quality_id', $P_details['quality_id'])
            ->get();
        // return $all_p_data;
        // $tableName=$request['data'][1]['store_data'][$i]['store_table_name'];
        if ($other_store_data[0]['table_name'] == 'damaged_parts') {
            $ins_id = StoresLog::create([
                'All_part_id' => $all_p_data[0]['id'],
                'store_action_id' => 6,
                'store_id' => $store_id,
                'amount' => $sent_amount,
                // 'user_id' => auth()->user()->id,
                'status' => 2,
                'date' => date('Y-m-d H:i:s'),
                'type_id' => $type_id,
                'notes' => 'To :' . $other_store_data[0]['name'],
            ])->id;
            if ($ins_id > 0) {
                StoresLog::create([
                    'All_part_id' => $all_p_data[0]['id'],
                    'store_action_id' => 3,
                    'store_id' => $other_store_id,
                    'amount' => $sent_amount,
                    // 'user_id' => auth()->user()->id,
                    'status' => $ins_id,
                    'date' => date('Y-m-d H:i:s'),
                    'type_id' => $type_id,
                    'notes' => 'from :' . $store_data[0]['name'],
                ]);
            }
        } else {
            $ins_id = StoresLog::create([
                'All_part_id' => $all_p_data[0]['id'],
                'store_action_id' => 2,
                'store_id' => $store_id,
                'amount' => $sent_amount,
                // 'user_id' => auth()->user()->id,
                'status' => 2,
                'date' => date('Y-m-d H:i:s'),
                'type_id' => $type_id,
                'notes' => 'To :' . $other_store_data[0]['name'],
            ])->id;
            if ($ins_id > 0) {
                StoresLog::create([
                    'All_part_id' => $all_p_data[0]['id'],
                    'store_action_id' => 3,
                    'store_id' => $other_store_id,
                    'amount' => $sent_amount,
                    // 'user_id' => auth()->user()->id,
                    'status' => $ins_id,
                    'date' => date('Y-m-d H:i:s'),
                    'type_id' => $type_id,
                    'notes' => 'from :' . $store_data[0]['name'],
                ]);
            }
        }
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
            $all_p_model_name = 'AllKit';
            $table = 'kit';
        }

        return [$all_p_table_name, $all_p_model_name, $table];
    }

    public function confirm_store(Request $request)
    {
        // return $request;
        $logMessage='';
        $logUser = Auth::user()->id;
        
        DB::beginTransaction();
        try {
            $Store_log_id = $request->data['Store_log_id'];
            $store_table_name = $request->data['store_table_name'];
            $all_p_id = $request->data['all_p_id'];
            $part_id = $request->data['part_id'];
            $amount = $request->data['accamount'];
            $actual_amount = $request->data['actual_amount'];
            $type_id = $request->data['type_id'];
            $store_id = $request->data['store_id'];
            $store_action_id = $request->data['store_action_id'];
            $flag_completed = $request->data['flag_completed'];
            $supplier_order_id = '';

            if ($store_action_id == 1) {
                //توزيع من يوسف الى المخازن للاستلام والمخزن بيسلم الكمية من هنا//
                // return $store_action_id;
                StoresLog::where('id', $Store_log_id)->update([
                    'status' => 1,
                ]);
                $oldNotes = StoresLog::where('id', $Store_log_id)->first()->notes;
                $Store_log_id = StoresLog::create([
                    'All_part_id' => $all_p_id,
                    'store_action_id' => 3,
                    'store_id' => $store_id,
                    'amount' => $amount,
                    // 'user_id' => auth()->user()->id,
                    'status' => 3,
                    'date' => date('Y-m-d H:i:s'),
                    'type_id' => $type_id,
                ])->id;
                $logMessage.='تم دخول الكمية '.$amount .' الي'.$store_id.'<br/>';
                    
                if ($flag_completed == 0) {
                    // return 'nnnnnnnnnnnnnnnnnnnnnnnnn';
                    StoresLog::create([
                        'All_part_id' => $all_p_id,
                        'store_action_id' => 1,
                        'store_id' => $store_id,
                        'amount' => $actual_amount - $amount,
                        // 'user_id' => auth()->user()->id,
                        'status' => 0,
                        'date' => date('Y-m-d H:i:s'),
                        'type_id' => $type_id,
                        'notes' => $oldNotes,
                    ]);
                     $logMessage.='تم دخول الكمية '.$actual_amount - $amount .' الي'.$store_id.'<br/>';
                    
                }
                // return $request;

                // /*******************************************************************************************************
                $quaditems = [];
                $automaicQayd = new QaydController();
                $Actotal = 0;
                $store = Store::where('id', $store_id)->first();

                ///// Types //////////////////////////
                $Ac_allPart = '';
                if ($type_id == 1) {
                    $Ac_allPart = AllPart::where('id', $all_p_id)->first();
                } elseif ($type_id == 2) {
                    $Ac_allPart = AllWheel::where('id', $all_p_id)->first();
                } elseif ($type_id == 3) {
                    $Ac_allPart = AllTractor::where('id', $all_p_id)->first();
                } elseif ($type_id == 4) {
                    $Ac_allPart = AllClark::where('id', $all_p_id)->first();
                } elseif ($type_id == 5) {
                    $Ac_allPart = AllEquip::where('id', $all_p_id)->first();
                } elseif ($type_id == 6) {
                    $Ac_allPart = AllKit::where('id', $all_p_id)->first();
                }

                ///////////////////////////////////// توزيع من المخزون

                // $Ac_allPart = AllPart::where('id',$all_p_id)->first();
                $Ac_inv = Replyorder::where('order_supplier_id', $Ac_allPart->order_supplier_id)
                    ->where('part_id', $Ac_allPart->part_id)
                    ->where('source_id', $Ac_allPart->source_id)
                    ->where('status_id', $Ac_allPart->status_id)
                    ->where('quality_id', $Ac_allPart->quality_id)
                    ->where('part_type_id', $type_id)
                    ->with('order_supplier')
                    ->first();

                $Ac_currency_id = $Ac_inv->order_supplier->currency_id;
                $Ac_currency_date = $Ac_inv->order_supplier->confirmation_date;
                $Ac_all_currency_types = CurrencyType::with([
                    'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                        return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                    },
                ])
                    ->where('id', $Ac_currency_id)
                    ->get();
                // $Ac_inv->price // السعر
                // $Ac_inv->order_supplier->currency_id // العملة
                // $request['data'][1]['store_data'][0]['ins_amount']; // الكمية
                // $Ac_inv->order_supplier->confirmation_date // تاريخ الشراء
                // $Ac_all_currency_types[0]->currencies[0]->value  // سعر العملة في وقت الشراء

                $Ac_price = $amount * $Ac_all_currency_types[0]->currencies[0]->value * $Ac_inv->price;

                array_push($quaditems, (object) ['acountant_id' => $store->accountant_number, 'madin' => $Ac_price, 'dayin' => 0]); // المخزن مدين

                $Actotal += $Ac_price; // أجمالي المخزون

                // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> 0 , 'dayin'=> $Ac_price ] ); // المخزون دائن
                array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => 0, 'dayin' => $Ac_price]); // المشتريات دائن
                $date = Carbon::now();
                $type = null;
                $notes = 'حركة مخزون الي ' . $store->name;
                $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
                // /*******************************************************************************************************
                # code...
            } elseif ($store_action_id == 3) {
                StoresLog::where('id', $Store_log_id)->update([
                    'status' => 3,
                ]);
                $oldNotes = StoresLog::where('id', $Store_log_id)->first()->notes;
                if ($flag_completed == 0) {
                    // return 'nnnnnnnnnnnnnnnnnnnnnnnnn';
                    StoresLog::create([
                        'All_part_id' => $all_p_id,
                        'store_action_id' => 3,
                        'store_id' => $store_id,
                        'amount' => $actual_amount - $amount,
                        // 'user_id' => auth()->user()->id,
                        'status' => 0,
                        'date' => date('Y-m-d H:i:s'),
                        'type_id' => $type_id,
                        'notes' => $oldNotes,
                    ]);
                    $logMessage.='تم دخول الكمية '.$actual_amount - $amount .' الي'.$store_id.'<br/>';
                }
                // /********************************************************* من مخزن الي مخزن**********************************************

                $quaditems = [];
                $automaicQayd = new QaydController();
                $Actotal = 0;
                $ac_storeName = explode(':', $oldNotes);
                $storess = Store::where('id', $store_id)->first();

                $storesfrom = Store::where('name', 'like', $ac_storeName[1])->first();

                $quaditems = [];
                $automaicQayd = new QaydController();
                $Actotal = 0;

                ///// Types //////////////////////////
                $Ac_allPart = '';
                if ($type_id == 1) {
                    $Ac_allPart = AllPart::where('id', $all_p_id)->first();
                } elseif ($type_id == 2) {
                    $Ac_allPart = AllWheel::where('id', $all_p_id)->first();
                } elseif ($type_id == 3) {
                    $Ac_allPart = AllTractor::where('id', $all_p_id)->first();
                } elseif ($type_id == 4) {
                    $Ac_allPart = AllClark::where('id', $all_p_id)->first();
                } elseif ($type_id == 5) {
                    $Ac_allPart = AllEquip::where('id', $all_p_id)->first();
                } elseif ($type_id == 6) {
                    $Ac_allPart = AllKit::where('id', $all_p_id)->first();
                }

                /////////////////////////////////////

                // $Ac_allPart = AllPart::where('id',$all_p_id)->first();
                $Ac_inv = Replyorder::where('order_supplier_id', $Ac_allPart->order_supplier_id)
                    ->where('part_id', $Ac_allPart->part_id)
                    ->where('source_id', $Ac_allPart->source_id)
                    ->where('status_id', $Ac_allPart->status_id)
                    ->where('quality_id', $Ac_allPart->quality_id)
                    ->where('part_type_id', $type_id)
                    ->with('order_supplier')
                    ->first();

                $Ac_currency_id = $Ac_inv->order_supplier->currency_id;
                $Ac_currency_date = $Ac_inv->order_supplier->confirmation_date;
                $Ac_all_currency_types = CurrencyType::with([
                    'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                        return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                    },
                ])
                    ->where('id', $Ac_currency_id)
                    ->get();
                // $Ac_inv->price // السعر
                // $Ac_inv->order_supplier->currency_id // العملة
                // $request['data'][1]['store_data'][0]['ins_amount']; // الكمية
                // $Ac_inv->order_supplier->confirmation_date // تاريخ الشراء
                // $Ac_all_currency_types[0]->currencies[0]->value  // سعر العملة في وقت الشراء

                $Ac_price = $amount * $Ac_all_currency_types[0]->currencies[0]->value * $Ac_inv->price;

                array_push($quaditems, (object) ['acountant_id' => $storess->accountant_number, 'madin' => $Ac_price, 'dayin' => 0]); // المخزن مدين

                $Actotal += $Ac_price; // أجمالي المخزون

                array_push($quaditems, (object) ['acountant_id' => $storesfrom->accountant_number, 'madin' => 0, 'dayin' => $Ac_price]); // المخزون دائن
                $date = Carbon::now();
                $type = null;
                $notes = ' حركة مخزون من   ' . $storesfrom->name . ' إلي ' . $storess->name;
                $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
                // /*******************************************************************************************************
            }
           
            if ($Store_log_id > 0) {
                $res = $this->check_p_type($type_id);
                // return $res;
                $all_part_table_name = $res[0];
                $all_part_model_name = $res[1];
                $item_table = $res[2];
                $store_model = ucfirst($store_table_name);
                $entity = 'App\\Models\\' . $store_model;
                $y = $entity
                    ::leftjoin('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                    ->where('stores_log.All_part_id', '=', $all_p_id)
                    ->where('stores_log.type_id', '=', $type_id)
                    ->get();
                // return $y;
                if (count($y) > 0) {
                    $supplier_order_id = $y[0]->supplier_order_id;
                } else {
                    $newentity = 'App\\Models\\' . $all_part_model_name;
                    $z = $newentity::where('id', '=', $all_p_id)->get();

                    $supplier_order_id = $z[0]->order_supplier_id;

                    // return $supplier_order_id;
                }

                $founded_row = $entity
                    ::leftjoin('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                    ->where('stores_log.All_part_id', '=', $all_p_id)
                    ->where('stores_log.type_id', '=', $type_id)
                    ->select('stores_log.*', $store_table_name . '.amount as oldamount', $store_table_name . '.*')
                    ->orderBy('stores_log.id', 'DESC')
                    ->get();


                if (count($y) > 0) {
                    $entity::where($store_table_name . '.store_log_id', '=', $founded_row[0]->store_log_id)->update([
                        'amount' => intval($founded_row[0]->oldamount) + intval($amount),
                        'store_log_id' => $founded_row[0]->store_log_id,
                    ]);
                    
                    $logMessage.='تم دخول part id '.$founded_row[0]->part_id.'الكمية والتعديل'.intval($founded_row[0]->oldamount) + intval($amount).' الي'.$store_table_name.'<br/>';
                     $logMessage.='الكمية القديمة '.intval($founded_row[0]->oldamount) .' مخزن '.$store_table_name.'<br/>';
                     $logMessage.='الكمية المضافة '.intval($amount) .' مخزن '.$store_table_name.'<br/>';
                } else {
                    $entity::create([
                        'part_id' => $part_id,
                        'amount' => $amount,
                        'supplier_order_id' => $supplier_order_id,
                        'type_id' => $type_id,
                        'store_log_id' => $Store_log_id,
                        'date' => date('Y-m-d H:i:s'),
                    ]);
                     $logMessage.='تم دخول part id '.$founded_row[0]->part_id.'الكمية '.$amount .' الي'.$store_table_name.'<br/>';
                 
                }
            }
            
             $log = new LogController();
            $log->newLog($logUser,$logMessage);
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            //throw $th;
            DB::rollback();
            session()->flash("error", $e->getMessage());
            return false;
        }
    }



    public function get_parts_info($store_table_name)
    {
        $store_model = ucfirst($store_table_name);
        $storeId = Store::where('table_name', $store_table_name)->first()->id;
        if ($store_model == 'Damaged_parts') {
            $store_model = 'damagedPart';
        }

        $all_p_table_name = 'all_parts';
        $all_p_model_name = 'AllPart';
        $table = 'Part';

        $entity_tbl = 'App\\Models\\' . $store_model;
        $items_arr = $entity_tbl::with('stores_log')->get();
        $entity = 'App\\Models\\' . $store_model;
        $store_data = $entity
            ::leftjoin('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
            ->leftjoin($all_p_table_name, 'stores_log.All_part_id', '=', $all_p_table_name . '.id')
            // ->where($all_p_table_name.'.part_id','=', $p_id)
            ->where('stores_log.type_id', '=', '1')
            ->select($all_p_table_name . '.source_id', $all_p_table_name . '.status_id', $all_p_table_name . '.quality_id', $all_p_table_name . '.part_id');
        // $groups = $store_data->groupBy('part_id','source_id','status_id','quality_id')->sum($store_table_name.'.amount')->get();

        $groups = $store_data
            ->groupBy('part_id', 'source_id', 'status_id', 'quality_id')
            ->selectRaw('sum(' . $store_table_name . '.amount) as amount')
            ->get();

        return $groupwithcount = $groups->map(function ($group) use ($storeId) {
            return [
                'part_id' => $group->part_id,
                'groups' => part::where('id', $group->part_id)
                    ->with([
                        'sub_group' => function ($q) {
                            $q->with('group')->get();
                        },
                    ])
                    ->get(),
                'models' => PartModel::where('part_id', $group->part_id)
                    ->with([
                        'series' => function ($q) {
                            $q->with([
                                'model' => function ($w) {
                                    $w->with('brand')->with('brand_type')->get();
                                },
                            ])->get();
                        },
                    ])
                    ->get(),
                'Tamount' => $group->amount,
                'image' => PartImage::where('part_id', $group->part_id)->get(),
                'store_log_id' => $group->first()['store_log_id'],
                'date' => $group->first()['date'],
                'source_id' => $group->source_id,
                'status_id' => $group->status_id,
                'quality_id' => $group->quality_id,
                'supplier_order_id' => $group->first()['supplier_order_id'],
                'type_id' => 1,
                'p_data' => Part::where('id', '=', $group->part_id)
                    ->with('part_numbers')
                    ->first(),
                'source' => Source::where('id', '=', $group->source_id)->get(),
                'status' => Status::where('id', '=', $group->status_id)->get(),
                'quality' => PartQuality::where('id', '=', $group->quality_id)->get(),
                'type_N' => 'قطع غيار',
                'allparts' => AllPart::where('part_id', $group->part_id)->with([ //////salam_edit 21-3-2024.
                    'part' => function ($m) {

                        $m->with([
                            'part_details' => function ($q) {
                                $q->with('part_spec')->with('mesure_unit')->get();
                            },
                        ]);
                    }
                ]) //////salam_edit 21-3-2024.
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    // ->where('remain_amount', '>', 0)salam_edit 2-4-2024.
                    ->orderBy('id', 'DESC')
                    ->get(),
                'price' => SalePricing::where('part_id', $group->part_id)
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    ->where('type_id', '1')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get(),
                'section' => StoreSection::where('part_id', $group->part_id)
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    ->where('type_id', '1')
                    ->where('store_id', $storeId)
                    ->where('amount', '>', 0)
                    ->with('store_structure')
                    // ->groupBy('part_id', 'source_id', 'status_id', 'quality_id','order_supplier_id','store_id')
                    // ->selectRaw('sum(amount) as amount')
                    ->get(),
                'stores' => $this->PartInStoresCount($group->part_id, $group->source_id, $group->status_id, $group->quality_id, '1'),
            ];
        });
    }

    public function get_parts_info2($store_table_name)
    {
        $store_model = ucfirst($store_table_name);
        $storeId = Store::where('table_name', $store_table_name)->first()->id;
        if ($store_model == 'Damaged_parts') {
            $store_model = 'damagedPart';
        }

        $all_p_table_name = 'all_parts';
        $all_p_model_name = 'AllPart';
        $table = 'Part';

        $entity_tbl = 'App\\Models\\' . $store_model;
        $items_arr = $entity_tbl::with('stores_log')->get();
        $entity = 'App\\Models\\' . $store_model;
        $store_data = $entity
            ::leftjoin('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
            ->leftjoin($all_p_table_name, 'stores_log.All_part_id', '=', $all_p_table_name . '.id')
            // ->where($all_p_table_name.'.part_id','=', $p_id)
            ->where('stores_log.type_id', '=', '1')
            ->select($all_p_table_name . '.source_id', $all_p_table_name . '.status_id', $all_p_table_name . '.quality_id', $all_p_table_name . '.part_id');
        // $groups = $store_data->groupBy('part_id','source_id','status_id','quality_id')->sum($store_table_name.'.amount')->get();

        $groups = $store_data
            ->groupBy('part_id', 'source_id', 'status_id', 'quality_id')
            ->selectRaw('sum(' . $store_table_name . '.amount) as amount')
            ->get();

        return $groupwithcount = $groups->map(function ($group) use ($storeId) {
            return [
                'part_id' => $group->part_id,
                'groups' => part::where('id', $group->part_id)
                    ->with([
                        'sub_group' => function ($q) {
                            $q->with('group')->get();
                        },
                    ])
                    ->get(),
                'models' => PartModel::where('part_id', $group->part_id)
                    ->with([
                        'series' => function ($q) {
                            $q->with([
                                'model' => function ($w) {
                                    $w->with('brand')->with('brand_type')->get();
                                },
                            ])->get();
                        },
                    ])
                    ->get(),
                'Tamount' => $group->amount,
                'image' => PartImage::where('part_id', $group->part_id)->get(),
                'store_log_id' => $group->first()['store_log_id'],
                'date' => $group->first()['date'],
                'source_id' => $group->source_id,
                'status_id' => $group->status_id,
                'quality_id' => $group->quality_id,
                'supplier_order_id' => $group->first()['supplier_order_id'],
                'type_id' => 1,
                'p_data' => Part::where('id', '=', $group->part_id)
                    ->with('part_numbers')
                    ->first(),
                'source' => Source::where('id', '=', $group->source_id)->get(),
                'status' => Status::where('id', '=', $group->status_id)->get(),
                'quality' => PartQuality::where('id', '=', $group->quality_id)->get(),
                'type_N' => 'قطع غيار',
                'allparts' => AllPart::where('part_id', $group->part_id)->with([ //////salam_edit 21-3-2024.
                    'part' => function ($m) {

                        $m->with([
                            'part_details' => function ($q) {
                                $q->with('part_spec')->with('mesure_unit')->get();
                            },
                        ]);
                    }
                ]) //////salam_edit 21-3-2024.
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    // ->where('remain_amount', '>', 0)salam_edit 2-4-2024.
                    ->orderBy('id', 'DESC')
                    ->get(),
                'price' => SalePricing::where('part_id', $group->part_id)
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    ->where('type_id', '1')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get(),
                'section' => StoreSection::where('part_id', $group->part_id)
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    ->where('type_id', '1')
                    ->where('store_id', $storeId)
                    ->where('amount', '>', 0)
                    ->with('store_structure')
                    ->get(),
                'stores' => $this->PartInStoresCount($group->part_id, $group->source_id, $group->status_id, $group->quality_id, '1'),
            ];
        });
    }

    public function get_wheel_info($store_table_name)
    {
        $store_model = ucfirst($store_table_name);
        $storeId = Store::where('table_name', $store_table_name)->first()->id;
        if ($store_model == 'Damaged_parts') {
            $store_model = 'damagedPart';
        }

        $all_p_table_name = 'all_wheels';
        $all_p_model_name = 'AllWheel';
        $table = 'Wheel';

        $entity_tbl = 'App\\Models\\' . $store_model;
        $items_arr = $entity_tbl::with('stores_log')->get();
        $entity = 'App\\Models\\' . $store_model;
        $store_data = $entity
            ::leftjoin('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
            ->leftjoin($all_p_table_name, 'stores_log.All_part_id', '=', $all_p_table_name . '.id')
            // ->where($all_p_table_name.'.part_id','=', $p_id)
            ->where('stores_log.type_id', '=', '2')
            ->select($all_p_table_name . '.source_id', $all_p_table_name . '.status_id', $all_p_table_name . '.quality_id', $all_p_table_name . '.part_id');
        // $groups = $store_data->groupBy('part_id','source_id','status_id','quality_id')->sum($store_table_name.'.amount')->get();

        $groups = $store_data
            ->groupBy('part_id', 'source_id', 'status_id', 'quality_id')
            ->selectRaw('sum(' . $store_table_name . '.amount) as amount')
            ->get();

        return $groupwithcount = $groups->map(function ($group) use ($storeId) {
            return [
                'part_id' => $group->part_id,
                'groups' => Wheel::where('id', $group->part_id)->get(),
                'models' => [],
                'Tamount' => $group->amount,
                'image' => WheelImage::where('wheel_id', $group->part_id)->get(),
                'store_log_id' => $group->first()['store_log_id'],
                'date' => $group->first()['date'],
                'source_id' => $group->source_id,
                'status_id' => $group->status_id,
                'quality_id' => $group->quality_id,
                'supplier_order_id' => $group->first()['supplier_order_id'],
                'type_id' => 2,
                'p_data' => Wheel::where('id', '=', $group->part_id)->first(),
                'source' => Source::where('id', '=', $group->source_id)->get(),
                'status' => Status::where('id', '=', $group->status_id)->get(),
                'quality' => PartQuality::where('id', '=', $group->quality_id)->get(),
                'type_N' => 'كاوتش',
                'allparts' => AllWheel::where('part_id', $group->part_id)->with([ //////salam_edit 21-3-2024.
                    'wheel' => function ($m) {

                        $m->with([
                            'wheel_details' => function ($q) {
                                $q->with('wheel_spec')->with('mesure_unit')->get();
                            },
                        ]);
                    }
                ]) //////salam_edit 21-3-2024.
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    // ->where('remain_amount', '>', 0) salam_edit 2-4-2024.
                    ->orderBy('id', 'DESC')
                    ->get(),
                'price' => SalePricing::where('part_id', $group->part_id)
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    ->where('type_id', '2')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get(),
                'section' => StoreSection::where('part_id', $group->part_id)
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    ->where('type_id', '2')
                    ->where('store_id', $storeId)
                    ->where('amount', '>', 0)
                    ->with('store_structure')
                    ->get(),
                'stores' => $this->PartInStoresCount($group->part_id, $group->source_id, $group->status_id, $group->quality_id, '2'),
            ];
        });
    }
    public function get_tractor_info($store_table_name)
    {
        $store_model = ucfirst($store_table_name);
        $storeId = Store::where('table_name', $store_table_name)->first()->id;
        if ($store_model == 'Damaged_parts') {
            $store_model = 'damagedPart';
        }
        $all_p_table_name = 'all_tractors';
        $all_p_model_name = 'AllTractor';
        $table = 'Tractor';

        $entity_tbl = 'App\\Models\\' . $store_model;
        $items_arr = $entity_tbl::with('stores_log')->get();
        $entity = 'App\\Models\\' . $store_model;
        $store_data = $entity
            ::leftjoin('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
            ->leftjoin($all_p_table_name, 'stores_log.All_part_id', '=', $all_p_table_name . '.id')
            // ->where($all_p_table_name.'.part_id','=', $p_id)
            ->where('stores_log.type_id', '=', '3')
            ->select($all_p_table_name . '.source_id', $all_p_table_name . '.status_id', $all_p_table_name . '.quality_id', $all_p_table_name . '.part_id');
        // $groups = $store_data->groupBy('part_id','source_id','status_id','quality_id')->sum($store_table_name.'.amount')->get();

        $groups = $store_data
            ->groupBy('part_id', 'source_id', 'status_id', 'quality_id')
            ->selectRaw('sum(' . $store_table_name . '.amount) as amount')
            ->get();

        return $groupwithcount = $groups->map(function ($group) use ($storeId) {
            return [
                'part_id' => $group->part_id,
                'groups' => Tractor::where('id', $group->part_id)->get(),
                'models' => [],
                'Tamount' => $group->amount,
                'image' => TractorImage::where('tractor_id', $group->part_id)->get(),
                'store_log_id' => $group->first()['store_log_id'],
                'date' => $group->first()['date'],
                'source_id' => $group->source_id,
                'status_id' => $group->status_id,
                'quality_id' => $group->quality_id,
                'supplier_order_id' => $group->first()['supplier_order_id'],
                'type_id' => 3,
                'p_data' => Tractor::where('id', '=', $group->part_id)->first(),
                'source' => Source::where('id', '=', $group->source_id)->get(),
                'status' => Status::where('id', '=', $group->status_id)->get(),
                'quality' => PartQuality::where('id', '=', $group->quality_id)->get(),
                'type_N' => 'جرارات',
                'allparts' => AllTractor::where('part_id', $group->part_id)->with([ //////salam_edit 21-3-2024.
                    'tractor' => function ($m) {

                        $m->with([
                            'tractor_details' => function ($q) {
                                $q->with('tractor_spec')->with('mesure_unit')->get();
                            },
                        ]);
                    }
                ]) //////salam_edit 21-3-2024.
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    // ->where('remain_amount', '>', 0) salam_edit 2-4-2024.
                    ->orderBy('id', 'DESC')
                    ->get(),
                'price' => SalePricing::where('part_id', $group->part_id)
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    ->where('type_id', '3')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get(),
                'section' => StoreSection::where('part_id', $group->part_id)
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    ->where('type_id', '3')
                    ->where('store_id', $storeId)
                    ->where('amount', '>', 0)
                    ->with('store_structure')
                    ->get(),
                'stores' => $this->PartInStoresCount($group->part_id, $group->source_id, $group->status_id, $group->quality_id, '3'),
            ];
        });
    }

    public function get_clarck_info($store_table_name)
    {
        $store_model = ucfirst($store_table_name);
        $storeId = Store::where('table_name', $store_table_name)->first()->id;
        if ($store_model == 'Damaged_parts') {
            $store_model = 'damagedPart';
        }
        $all_p_table_name = 'all_clarks';
        $all_p_model_name = 'AllClark';
        $table = 'Clark';

        $entity_tbl = 'App\\Models\\' . $store_model;
        $items_arr = $entity_tbl::with('stores_log')->get();
        $entity = 'App\\Models\\' . $store_model;
        $store_data = $entity
            ::leftjoin('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
            ->leftjoin($all_p_table_name, 'stores_log.All_part_id', '=', $all_p_table_name . '.id')
            // ->where($all_p_table_name.'.part_id','=', $p_id)
            ->where('stores_log.type_id', '=', '4')
            ->select($all_p_table_name . '.source_id', $all_p_table_name . '.status_id', $all_p_table_name . '.quality_id', $all_p_table_name . '.part_id');
        // $groups = $store_data->groupBy('part_id','source_id','status_id','quality_id')->sum($store_table_name.'.amount')->get();

        $groups = $store_data
            ->groupBy('part_id', 'source_id', 'status_id', 'quality_id')
            ->selectRaw('sum(' . $store_table_name . '.amount) as amount')
            ->get();

        return $groupwithcount = $groups->map(function ($group) use ($storeId) {
            return [
                'part_id' => $group->part_id,
                'groups' => Clark::where('id', $group->part_id)->get(),
                'models' => [],
                'Tamount' => $group->amount,
                'image' => ClarkImage::where('clark_id', $group->part_id)->get(),
                'store_log_id' => $group->first()['store_log_id'],
                'date' => $group->first()['date'],
                'source_id' => $group->source_id,
                'status_id' => $group->status_id,
                'quality_id' => $group->quality_id,
                'supplier_order_id' => $group->first()['supplier_order_id'],
                'type_id' => 4,
                'p_data' => Clark::where('id', '=', $group->part_id)->first(),
                'source' => Source::where('id', '=', $group->source_id)->get(),
                'status' => Status::where('id', '=', $group->status_id)->get(),
                'quality' => PartQuality::where('id', '=', $group->quality_id)->get(),
                'type_N' => 'كلارك',
                'allparts' => AllClark::where('part_id', $group->part_id)->with([ //////salam_edit 21-3-2024.
                    'clark' => function ($m) {

                        $m->with([
                            'clark_details' => function ($q) {
                                $q->with('clark_spec')->with('mesure_unit')->get();
                            },
                        ]);
                    }
                ]) //////salam_edit 21-3-2024.
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    // ->where('remain_amount', '>', 0) salam_edit 2-4-2024.
                    ->orderBy('id', 'DESC')
                    ->get(),
                'price' => SalePricing::where('part_id', $group->part_id)
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    ->where('type_id', '4')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get(),
                'section' => StoreSection::where('part_id', $group->part_id)
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    ->where('type_id', '4')
                    ->where('store_id', $storeId)
                    ->where('amount', '>', 0)
                    ->with('store_structure')
                    ->get(),
                'stores' => $this->PartInStoresCount($group->part_id, $group->source_id, $group->status_id, $group->quality_id, '4'),
            ];
        });
    }

    public function get_equip_info($store_table_name)
    {
        $store_model = ucfirst($store_table_name);
        $storeId = Store::where('table_name', $store_table_name)->first()->id;
        if ($store_model == 'Damaged_parts') {
            $store_model = 'damagedPart';
        }
        $all_p_table_name = 'all_equips';
        $all_p_model_name = 'AllEquip';
        $table = 'Equip';

        $entity_tbl = 'App\\Models\\' . $store_model;
        $items_arr = $entity_tbl::with('stores_log')->get();
        $entity = 'App\\Models\\' . $store_model;
        $store_data = $entity
            ::leftjoin('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
            ->leftjoin($all_p_table_name, 'stores_log.All_part_id', '=', $all_p_table_name . '.id')
            // ->where($all_p_table_name.'.part_id','=', $p_id)
            ->where('stores_log.type_id', '=', '5')
            ->select($all_p_table_name . '.source_id', $all_p_table_name . '.status_id', $all_p_table_name . '.quality_id', $all_p_table_name . '.part_id');
        // $groups = $store_data->groupBy('part_id','source_id','status_id','quality_id')->sum($store_table_name.'.amount')->get();

        $groups = $store_data
            ->groupBy('part_id', 'source_id', 'status_id', 'quality_id')
            ->selectRaw('sum(' . $store_table_name . '.amount) as amount')
            ->get();

        return $groupwithcount = $groups->map(function ($group) use ($storeId) {
            return [
                'part_id' => $group->part_id,
                'groups' => Equip::where('id', $group->part_id)->get(),
                'models' => [],
                'Tamount' => $group->amount,
                'image' => EquipImage::where('equip_id', $group->part_id)->get(),
                'store_log_id' => $group->first()['store_log_id'],
                'date' => $group->first()['date'],
                'source_id' => $group->source_id,
                'status_id' => $group->status_id,
                'quality_id' => $group->quality_id,
                'supplier_order_id' => $group->first()['supplier_order_id'],
                'type_id' => 5,
                'p_data' => Equip::where('id', '=', $group->part_id)->first(),
                'source' => Source::where('id', '=', $group->source_id)->get(),
                'status' => Status::where('id', '=', $group->status_id)->get(),
                'quality' => PartQuality::where('id', '=', $group->quality_id)->get(),
                'type_N' => 'معدات',
                'allparts' => AllEquip::where('part_id', $group->part_id)->with([ //////salam_edit 21-3-2024.
                    'equip' => function ($m) {

                        $m->with([
                            'equip_details' => function ($q) {
                                $q->with('equip_spec')->with('mesure_unit')->get();
                            },
                        ]);
                    }
                ]) //////salam_edit 21-3-2024.
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    // ->where('remain_amount', '>', 0) salam_edit 2-4-2024.
                    ->orderBy('id', 'DESC')
                    ->get(),
                'price' => SalePricing::where('part_id', $group->part_id)
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    ->where('type_id', '5')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get(),
                'section' => StoreSection::where('part_id', $group->part_id)
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    ->where('type_id', '5')
                    ->where('store_id', $storeId)
                    ->where('amount', '>', 0)
                    ->with('store_structure')
                    ->get(),
                'stores' => $this->PartInStoresCount($group->part_id, $group->source_id, $group->status_id, $group->quality_id, '5'),
            ];
        });
    }

    public function get_kit_info($store_table_name)
    {
        $store_model = ucfirst($store_table_name);
        $storeId = Store::where('table_name', $store_table_name)->first()->id;
        if ($store_model == 'Damaged_parts') {
            $store_model = 'damagedPart';
        }
        $all_p_table_name = 'all_kits';
        $all_p_model_name = 'AllKit';
        $table = 'kit';

        $entity_tbl = 'App\\Models\\' . $store_model;
        $items_arr = $entity_tbl::with('stores_log')->get();
        $entity = 'App\\Models\\' . $store_model;
        $store_data = $entity
            ::leftjoin('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
            ->leftjoin($all_p_table_name, 'stores_log.All_part_id', '=', $all_p_table_name . '.id')
            // ->where($all_p_table_name.'.part_id','=', $p_id)
            ->where('stores_log.type_id', '=', '6')
            ->select($all_p_table_name . '.source_id', $all_p_table_name . '.status_id', $all_p_table_name . '.quality_id', $all_p_table_name . '.part_id');
        // $groups = $store_data->groupBy('part_id','source_id','status_id','quality_id')->sum($store_table_name.'.amount')->get();

        $groups = $store_data
            ->groupBy('part_id', 'source_id', 'status_id', 'quality_id')
            ->selectRaw('sum(' . $store_table_name . '.amount) as amount')
            ->get();

        return $groupwithcount = $groups->map(function ($group) use ($storeId) {
            return [
                'part_id' => $group->part_id,
                'groups' => kit::where('id', $group->part_id)->get(),
                'models' => [],
                'Tamount' => $group->amount,
                'image' => KitImage::where('kit_id', $group->part_id)->get(),
                'store_log_id' => $group->first()['store_log_id'],
                'date' => $group->first()['date'],
                'source_id' => $group->source_id,
                'status_id' => $group->status_id,
                'quality_id' => $group->quality_id,
                'supplier_order_id' => $group->first()['supplier_order_id'],
                'type_id' => 6,
                'p_data' => kit::where('id', '=', $group->part_id)->first(),
                'source' => Source::where('id', '=', $group->source_id)->get(),
                'status' => Status::where('id', '=', $group->status_id)->get(),
                'quality' => PartQuality::where('id', '=', $group->quality_id)->get(),
                'type_N' => ' كيت ',
                'allparts' => AllKit::where('part_id', $group->part_id)->with([ //////salam_edit 21-3-2024.
                    'kit' => function ($m) {

                        $m->with([
                            'kit_details' => function ($q) {
                                $q->with('kit_specs')->with('mesure_unit')->get();
                            },
                        ]);
                    }
                ]) //////salam_edit 21-3-2024.
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    // ->where('remain_amount', '>', 0) salam_edit 2-4-2024.
                    ->orderBy('id', 'DESC')
                    ->get(),
                'price' => SalePricing::where('part_id', $group->part_id)
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    ->where('type_id', '6')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get(),
                'section' => StoreSection::where('part_id', $group->part_id)
                    ->where('source_id', $group->source_id)
                    ->where('status_id', $group->status_id)
                    ->where('quality_id', $group->quality_id)
                    ->where('type_id', '6')
                    ->where('store_id', $storeId)
                    ->where('amount', '>', 0)
                    ->with('store_structure')
                    ->get(),
                'stores' => $this->PartInStoresCount($group->part_id, $group->source_id, $group->status_id, $group->quality_id, '6'),
            ];
        });
    }

     public function shop(Request $request)
    {
        // return $request;

        $max_price = SalePricing::max('price');
        $min_price =  SalePricing::min('price');
        $allBrands = Brand::all();
        $allGroups = Group::all();
        $alltaxes = Tax::all();
        $all_types = BrandType::all();
        $all_models = Model::all();
        $all_seriess = Series::all();
        $all_suppliers = Supplier::all();
        $all_subgroups = SubGroup::all();
        $allItems = collect();
        $i = 0;
        $types = ['all_parts', 'all_wheels', 'all_kits', 'all_clarks', 'all_tractors', 'all_equips'];

        $relations = [
            ['part.part_images', 'source', 'status', 'part_quality', 'max_pricing'], // Multiple relations for `part`
            ['wheel.wheel_images', 'source', 'status', 'part_quality', 'max_pricing'],                // Relation for `wheel`
            ['kit.part_images', 'source', 'status', 'part_quality', 'max_pricing'],                    // Relation for `kit`
            ['clark.clark_images', 'source', 'status', 'part_quality', 'max_pricing'],                              // Relation for `clark`
            ['tractor.tractor_images', 'source', 'status', 'part_quality', 'max_pricing'],                            // Relation for `tractor`
            ['equip.equip_images', 'source', 'status', 'part_quality', 'max_pricing'],                              // Relation for `equip`
        ];
        $allItems = [];
        $items = [];
        for ($i = 0; $i < count($types); $i++) {
            $z = ucfirst(explode('_', $types[$i])[0]) . ucfirst(explode('_', $types[$i])[1]);
            $entity_tbl = 'App\\Models\\' . ucfirst(substr($z, 0, -1));
            $queryResult = QueryBuilder::for(resolve($entity_tbl)->newQuery())
                ->leftjoin('sale_pricing', function ($join) use ($types, $i) {
                    $join->on($types[$i] . '.part_id', '=', 'sale_pricing.part_id')
                        ->on($types[$i] . '.source_id', '=', 'sale_pricing.source_id')
                        ->on($types[$i] . '.status_id', '=', 'sale_pricing.status_id')
                        ->on($types[$i] . '.quality_id', '=', 'sale_pricing.quality_id')
                        ->whereNull('sale_pricing.to');
                }) // Join the max_pricing relationship table
                ->selectRaw('SUM(remain_amount) as amount, ' . $types[$i] . '.part_id,   ' . $types[$i] . '.source_id,  ' . $types[$i] . '.status_id,   ' . $types[$i] . '.quality_id')
                ->groupBy($types[$i] . '.part_id', $types[$i] . '.source_id', $types[$i] . '.status_id', $types[$i] . '.quality_id')
                ->allowedFilters([
                    AllowedFilter::scope('name'),
                    AllowedFilter::scope('brand'),
                    AllowedFilter::scope('sub_group'),
                    AllowedFilter::scope('supplier'),
                    AllowedFilter::scope('group'),
                    AllowedFilter::scope('model'),
                    AllowedFilter::scope('series'),
                    AllowedFilter::scope('max_price'),
                    AllowedFilter::scope('min_price'),
                    // AllowedFilter::scope('part_type'),
                    // AllowedFilter::scope('max_price'),
                    // AllowedFilter::scope('min_price'),
                ])->allowedSorts([
                    AllowedSort::field('price', 'sale_pricing.price'), // Sort by price in sale_pricing
                ]);
            foreach ($relations[$i] as $relation) {
                $queryResult->with($relation);
            }
            $allItems[$i] = $queryResult->get();
        }
        $items = collect($allItems)->flatten()->sortBy('status_id')->values();
        $page = request()->get('page', 1);
        $perPage = 9;
        $offset = ($page - 1) * $perPage;
        $pagedItems = new LengthAwarePaginator(
            $items->slice($offset, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        // return ($pagedItems);
        return view('ecommerce.shop-grid', compact('all_subgroups', 'all_suppliers', 'all_seriess', 'all_models', 'all_types', 'min_price', 'max_price', 'allBrands', 'allGroups', 'alltaxes', 'pagedItems'));

    }


    public function storeSections( $storeId)
    {
        // return $storeId;
        $storeId=Store::where('id',$storeId)->first();
        $store = $storeId;

        $store_data = Store::where('id', $store->id)->get();
        // $sections = StoreStructure::where('store_id', $store->id)->get();
        $sections = [];
        //////////////////////// StoreParts//////////////////////////////////
        $storeClsName = ucfirst($storeId->table_name);
        $tableN = $storeId->table_name;
        $storeClsName = 'App\Models\\' . $storeClsName;
        $storeClsName = str_replace([' ', '_', '-'], '', $storeClsName);
        if ($storeId->table_name == 'damaged_parts') {
            $storeClsName = 'App\Models\\DamagedPart';
            $tableN  = 'damaged_parts';
        }


        $newkits = DB::table($tableN)
            ->leftJoin('stores_log', 'stores_log.id', '=', $tableN . '.store_log_id')
            ->leftJoin('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
            ->leftJoin('source', 'all_kits.source_id', '=', 'source.id')
            ->leftJoin('status', 'all_kits.status_id', '=', 'status.id')
            ->leftJoin('part_quality', 'all_kits.quality_id', '=', 'part_quality.id')
            ->select(
                'all_kits.part_id as part',
                $tableN . '.store_log_id as store_log_id',
                'all_kits.source_id as source_id',
                'all_kits.status_id as status_id',
                'all_kits.quality_id as quality_id',
                'source.name_arabic as source_name',
                'status.name as status_name',
                'part_quality.name as quality_name',
                $tableN . '.type_id as type_id',
                'all_kits.order_supplier_id as supplier_order_id',
                DB::raw('SUM(' . $tableN . '.amount) as storeAmount'),
                DB::raw('COALESCE((
                            SELECT SUM(amount)
                            FROM store_section
                            WHERE type_id = 6
                            AND store_id = ' . $store->id . '
                                AND part_id = all_kits.part_id
                                AND source_id = all_kits.source_id
                                AND status_id = all_kits.status_id
                                AND quality_id = all_kits.quality_id
                                AND order_supplier_id = all_kits.order_supplier_id
                            GROUP BY part_id, source_id, status_id, quality_id, order_supplier_id
                        ), 0) AS sectionAmount')
            )
            ->where($tableN . '.type_id', 6)

            ->groupBy('all_kits.part_id', 'all_kits.source_id', 'all_kits.status_id', 'all_kits.quality_id', 'all_kits.order_supplier_id', $tableN . '.store_log_id', $tableN . '.type_id', 'source.name_arabic', 'status.name', 'part_quality.name')
            ->havingRaw('(storeAmount - COALESCE(sectionAmount, 0)) > 0')
            ->get();
        $newpar = DB::table($tableN)
            ->leftJoin('stores_log', 'stores_log.id', '=', $tableN . '.store_log_id')
            ->leftJoin('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
            ->leftJoin('source', 'all_parts.source_id', '=', 'source.id')
            ->leftJoin('status', 'all_parts.status_id', '=', 'status.id')
            ->leftJoin('part_quality', 'all_parts.quality_id', '=', 'part_quality.id')
            ->select(
                'all_parts.part_id as part',
                $tableN . '.store_log_id as store_log_id',
                'all_parts.source_id as source_id',
                'all_parts.status_id as status_id',
                'all_parts.quality_id as quality_id',
                'source.name_arabic as source_name',
                'status.name as status_name',
                'part_quality.name as quality_name',
                $tableN . '.type_id as type_id',
                'all_parts.order_supplier_id as supplier_order_id',
                DB::raw('SUM(' . $tableN . '.amount) as storeAmount'),
                DB::raw('COALESCE((
                            SELECT SUM(amount)
                            FROM store_section
                            WHERE type_id = 1
                            AND store_id = ' . $store->id . '
                                AND part_id = all_parts.part_id
                                AND source_id = all_parts.source_id
                                AND status_id = all_parts.status_id
                                AND quality_id = all_parts.quality_id
                                AND order_supplier_id = all_parts.order_supplier_id
                            GROUP BY part_id, source_id, status_id, quality_id, order_supplier_id
                        ), 0) AS sectionAmount')
            )
            ->where($tableN . '.type_id', 1)

            ->groupBy('all_parts.part_id', 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id', 'all_parts.order_supplier_id', $tableN . '.store_log_id', $tableN . '.type_id', 'source.name_arabic', 'status.name', 'part_quality.name')
            ->havingRaw('(storeAmount - COALESCE(sectionAmount, 0)) > 0')
            ->get();

        $newwheels = DB::table($tableN)
            ->leftJoin('stores_log', 'stores_log.id', '=', $tableN . '.store_log_id')
            ->leftJoin('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
            ->leftJoin('source', 'all_wheels.source_id', '=', 'source.id')
            ->leftJoin('status', 'all_wheels.status_id', '=', 'status.id')
            ->leftJoin('part_quality', 'all_wheels.quality_id', '=', 'part_quality.id')
            ->select(
                'all_wheels.part_id as part',
                $tableN . '.store_log_id as store_log_id',
                'all_wheels.source_id as source_id',
                'source.name_arabic as source_name',
                'status.name as status_name',
                'part_quality.name as quality_name',
                'all_wheels.status_id as status_id',
                'all_wheels.quality_id as quality_id',
                $tableN . '.type_id as type_id',
                'all_wheels.order_supplier_id as supplier_order_id',
                DB::raw('SUM(' . $tableN . '.amount) as storeAmount'),
                DB::raw('COALESCE((
                            SELECT SUM(amount)
                            FROM store_section
                            WHERE type_id = 2
                            AND store_id = ' . $store->id . '
                                AND part_id = all_wheels.part_id
                                AND source_id = all_wheels.source_id
                                AND status_id = all_wheels.status_id
                                AND quality_id = all_wheels.quality_id
                                AND order_supplier_id = all_wheels.order_supplier_id
                            GROUP BY part_id, source_id, status_id, quality_id, order_supplier_id
                        ), 0) AS sectionAmount')
            )
            ->where($tableN . '.type_id', 2)

            ->groupBy('all_wheels.part_id', 'all_wheels.source_id', 'all_wheels.status_id', 'all_wheels.quality_id', 'all_wheels.order_supplier_id', $tableN . '.store_log_id', $tableN . '.type_id', 'source.name_arabic', 'status.name', 'part_quality.name')
            ->havingRaw('(storeAmount - COALESCE(sectionAmount, 0)) > 0')
            ->get();



        $newclark = DB::table($tableN)
            ->leftJoin('stores_log', 'stores_log.id', '=', $tableN . '.store_log_id')
            ->leftJoin('all_clarks', 'stores_log.All_part_id', '=', 'all_clarks.id')
            ->leftJoin('source', 'all_clarks.source_id', '=', 'source.id')
            ->leftJoin('status', 'all_clarks.status_id', '=', 'status.id')
            ->leftJoin('part_quality', 'all_clarks.quality_id', '=', 'part_quality.id')
            ->select(
                'all_clarks.part_id as part',
                $tableN . '.store_log_id as store_log_id',
                'all_clarks.source_id as source_id',
                'all_clarks.status_id as status_id',
                'all_clarks.quality_id as quality_id',
                'source.name_arabic as source_name',
                'status.name as status_name',
                'part_quality.name as quality_name',
                $tableN . '.type_id as type_id',
                'all_clarks.order_supplier_id as supplier_order_id',
                DB::raw('SUM(' . $tableN . '.amount) as storeAmount'),
                DB::raw('COALESCE((
                            SELECT SUM(amount)
                            FROM store_section
                            WHERE type_id = 4
                            AND store_id = ' . $store->id . '
                                AND part_id = all_clarks.part_id
                                AND source_id = all_clarks.source_id
                                AND status_id = all_clarks.status_id
                                AND quality_id = all_clarks.quality_id
                                AND order_supplier_id = all_clarks.order_supplier_id
                            GROUP BY part_id, source_id, status_id, quality_id, order_supplier_id
                        ), 0) AS sectionAmount')
            )
            ->where($tableN . '.type_id', 4)

            ->groupBy('all_clarks.part_id', 'all_clarks.source_id', 'all_clarks.status_id', 'all_clarks.quality_id', 'all_clarks.order_supplier_id', $tableN . '.store_log_id', $tableN . '.type_id', 'source.name_arabic', 'status.name', 'part_quality.name')
            ->havingRaw('(storeAmount - COALESCE(sectionAmount, 0)) > 0')
            ->get();

        $newtractor = DB::table($tableN)
            ->leftJoin('stores_log', 'stores_log.id', '=', $tableN . '.store_log_id')
            ->leftJoin('all_tractors', 'stores_log.All_part_id', '=', 'all_tractors.id')
            ->leftJoin('source', 'all_tractors.source_id', '=', 'source.id')
            ->leftJoin('status', 'all_tractors.status_id', '=', 'status.id')
            ->leftJoin('part_quality', 'all_tractors.quality_id', '=', 'part_quality.id')
            ->select(
                'all_tractors.part_id as part',
                $tableN . '.store_log_id as store_log_id',
                'all_tractors.source_id as source_id',
                'all_tractors.status_id as status_id',
                'all_tractors.quality_id as quality_id',
                'source.name_arabic as source_name',
                'status.name as status_name',
                'part_quality.name as quality_name',
                $tableN . '.type_id as type_id',
                'all_tractors.order_supplier_id as supplier_order_id',
                DB::raw('SUM(' . $tableN . '.amount) as storeAmount'),
                DB::raw('COALESCE((
                            SELECT SUM(amount)
                            FROM store_section
                            WHERE type_id = 3
                            AND store_id = ' . $store->id . '
                                AND part_id = all_tractors.part_id
                                AND source_id = all_tractors.source_id
                                AND status_id = all_tractors.status_id
                                AND quality_id = all_tractors.quality_id
                                AND order_supplier_id = all_tractors.order_supplier_id
                            GROUP BY part_id, source_id, status_id, quality_id, order_supplier_id
                        ), 0) AS sectionAmount')
            )
            ->where($tableN . '.type_id', 3)

            ->groupBy('all_tractors.part_id', 'all_tractors.source_id', 'all_tractors.status_id', 'all_tractors.quality_id', 'all_tractors.order_supplier_id', $tableN . '.store_log_id', $tableN . '.type_id', 'source.name_arabic', 'status.name', 'part_quality.name')
            ->havingRaw('(storeAmount - COALESCE(sectionAmount, 0)) > 0')
            ->get();

        $newequip = DB::table($tableN)
            ->leftJoin('stores_log', 'stores_log.id', '=', $tableN . '.store_log_id')
            ->leftJoin('all_equips', 'stores_log.All_part_id', '=', 'all_equips.id')
            ->leftJoin('source', 'all_equips.source_id', '=', 'source.id')
            ->leftJoin('status', 'all_equips.status_id', '=', 'status.id')
            ->leftJoin('part_quality', 'all_equips.quality_id', '=', 'part_quality.id')
            ->select(
                'all_equips.part_id as part',
                $tableN . '.store_log_id as store_log_id',
                'all_equips.source_id as source_id',
                'all_equips.status_id as status_id',
                'all_equips.quality_id as quality_id',
                'source.name_arabic as source_name',
                'status.name as status_name',
                'part_quality.name as quality_name',
                $tableN . '.type_id as type_id',
                'all_equips.order_supplier_id as supplier_order_id',
                DB::raw('SUM(' . $tableN . '.amount) as storeAmount'),
                DB::raw('COALESCE((
                            SELECT SUM(amount)
                            FROM store_section
                            WHERE type_id = 5
                            AND store_id = ' . $store->id . '
                                AND part_id = all_equips.part_id
                                AND source_id = all_equips.source_id
                                AND status_id = all_equips.status_id
                                AND quality_id = all_equips.quality_id
                                AND order_supplier_id = all_equips.order_supplier_id
                            GROUP BY part_id, source_id, status_id, quality_id, order_supplier_id
                        ), 0) AS sectionAmount')
            )
            ->where($tableN . '.type_id', 5)

            ->groupBy('all_equips.part_id', 'all_equips.source_id', 'all_equips.status_id', 'all_equips.quality_id', 'all_equips.order_supplier_id', $tableN . '.store_log_id', $tableN . '.type_id', 'source.name_arabic', 'status.name', 'part_quality.name')
            ->havingRaw('(storeAmount - COALESCE(sectionAmount, 0)) > 0')
        ->get();


        // $newParts = new \Illuminate\Database\Eloquent\Collection(); //Create empty collection which we know has the merge() method
        // $newParts = $newParts->concat($newkits);
        // $newParts = $newParts->concat($newwheels);
        // $newParts = $newParts->concat($newpar);
        // $newParts = $newParts->concat($newclark);
        // $newParts = $newParts->concat($newtractor);
        // $newParts = $newParts->concat($newequip);
         // for ($i = 0; $i < count($newParts); $i++) {

        //     if ($newParts[$i]->type_id == 1) {
        //         $newParts[$i]->partData = Part::where('id', $newParts[$i]->part)->get();

        //     } elseif ($newParts[$i]->type_id == 2) {
        //         $newParts[$i]->partData = Wheel::where('id', $newParts[$i]->part)->get();
        //     } elseif ($newParts[$i]->type_id == 3) {
        //         $newParts[$i]->partData = Tractor::where('id', $newParts[$i]->part)->get();
        //     } elseif ($newParts[$i]->type_id == 4) {
        //         $newParts[$i]->partData = Clark::where('id', $newParts[$i]->part)->get();
        //     } elseif ($newParts[$i]->type_id == 5) {
        //         $newParts[$i]->partData = Equip::where('id', $newParts[$i]->part)->get();
        //     } elseif ($newParts[$i]->type_id == 6) {
        //         $newParts[$i]->partData = Kit::where('id', $newParts[$i]->part)->get();
        //     }

        //     $newParts[$i]->partHistory = StoreSection::where('store_id',$storeId->id)
        //     ->where('part_id',$newParts[$i]->part)
        //     ->where('source_id',$newParts[$i]->source_id)
        //     ->where('status_id',$newParts[$i]->status_id)
        //     ->where('quality_id',$newParts[$i]->quality_id)
        //     ->where('type_id',$newParts[$i]->type_id)
        //     ->with('store_structure')
        //     ->get();
        // }
            $newParts = collect($newkits)
                ->concat($newwheels)
                ->concat($newpar)
                ->concat($newclark)
                ->concat($newtractor)
                ->concat($newequip);
                
                
                   $newParts = collect($newParts)->map(function ($part) {
                    return is_array($part) ? (object) $part : $part;
                });

        $newParts = $newParts->map(function ($part) use ($storeId) {
        switch ($part->type_id) {
        case 1:
            $part->partData = Part::where('id', $part->part)->get();
            break;
        case 2:
            $part->partData = Wheel::where('id', $part->part)->get();
            break;
        case 3:
            $part->partData = Tractor::where('id', $part->part)->get();
            break;
        case 4:
            $part->partData = Clark::where('id', $part->part)->get();
            break;
        case 5:
            $part->partData = Equip::where('id', $part->part)->get();
            break;
        case 6:
            $part->partData = Kit::where('id', $part->part)->get();
            break;
        }
        $part->partHistory = StoreSection::where('store_id', $storeId->id)
        ->where('part_id', $part->part)
        ->where('source_id', $part->source_id)
        ->where('status_id', $part->status_id)
        ->where('quality_id', $part->quality_id)
        ->where('type_id', $part->type_id)
        ->with('store_structure')
        ->get();

        return $part;
        });

        // return $newParts;

        return view('storeSections', compact('store', 'sections', 'newParts', 'store_data'));
    }
    public function getAllDataInSection( $sectionId,  $storeId)
    {
        $storeId=Store::where('id',$storeId)->first();
        $sectionId=StoreStructure::where('id',$sectionId)->first();
        $Pdata = StoreSection::where('store_id', $storeId->id)
            ->where('section_id', $sectionId->id)
            ->groupBy('part_id', 'type_id', 'source_id', 'status_id', 'quality_id')
            ->selectRaw('part_id,type_id ,sum(amount) as amount,source_id,status_id, quality_id')
            ->with('type')
            ->with('source')
            ->with('status')
            ->with('part_quality')
            ->get();
        foreach ($Pdata as $item) {
            if ($item->type_id == 1) {
                $item['part'] = Part::where('id', $item->part_id)->get();
            } elseif ($item->type_id == 2) {
                $item['part'] = Wheel::where('id', $item->part_id)->get();
            } elseif ($item->type_id == 3) {
                $item['part'] = Tractor::where('id', $item->part_id)->get();
            } elseif ($item->type_id == 4) {
                $item['part'] = Clark::where('id', $item->part_id)->get();
            } elseif ($item->type_id == 5) {
                $item['part'] = Equip::where('id', $item->part_id)->get();
            } elseif ($item->type_id == 6) {
                $item['part'] = Kit::where('id', $item->part_id)->get();
            }
        }
        return $Pdata;
    }
    public function saveNewSection(Request $request)
    {
        // return $request;

        for ($i = 0; $i < count($request->section); $i++) {
            $sl = StoresLog::where('id', $request->store_log[$i])->get();
            $type_id = 0;
            $allpart = '';
            if ($request->Ptype[$i] == '1' && intVal($request->section[$i] > 0)) {
                $allpart = AllPart::where('id', $sl[0]->All_part_id)->get();
                $type_id = 1;
            } elseif ($request->Ptype[$i] == '2' && intVal($request->section[$i] > 0)) {
                $allpart = AllWheel::where('id', $sl[0]->All_part_id)->get();
                $type_id = 2;
            } elseif ($request->Ptype[$i] == '3' && intVal($request->section[$i] > 0)) {
                $allpart = AllTractor::where('id', $sl[0]->All_part_id)->get();
                $type_id = 3;
            } elseif ($request->Ptype[$i] == '4' && intVal($request->section[$i] > 0)) {
                $allpart = AllClark::where('id', $sl[0]->All_part_id)->get();
                $type_id = 4;
            } elseif ($request->Ptype[$i] == '5' && intVal($request->section[$i] > 0)) {
                $allpart = AllEquip::where('id', $sl[0]->All_part_id)->get();
                $type_id = 5;
            } elseif ($request->Ptype[$i] == '6' && intVal($request->section[$i] > 0)) {
                $allpart = AllKit::where('id', $sl[0]->All_part_id)->get();
                $type_id = 6;
            }

            $samllmeasureUnits = $request->samllmeasureUnits[$i];
            $measureUnit = $request->measureUnit[$i];
             $ratiounit = getSmallUnit($measureUnit, $samllmeasureUnits);
            if ($type_id != 0) {        
                $storeSection = new StoreSection();
                $storeSection->store_id = $request->storelbl;
                $storeSection->section_id = $request->section[$i];
                $storeSection->order_supplier_id = $request->supplier_order_id[$i];
                $storeSection->type_id = $type_id;
                $storeSection->part_id = $request->part_id[$i];
                $storeSection->source_id = $allpart[0]->source_id;
                $storeSection->status_id = $allpart[0]->status_id;
                $storeSection->quality_id = $allpart[0]->quality_id;
                $storeSection->amount = $request->sectionAmount[$i] * $ratiounit;
                $storeSection->date = Carbon::now();
                $storeSection->save();
            }
        }
        // return $request;
        return redirect()->to('storeSections/' . $request->storelbl);
    }

    public function newClientInline($telNumber)
    {
        $client = new Client();
        $client->name = '';
        $client->tel01 = $telNumber;
        $client->save();
        return $client->id;
    }

    public function Clientinvoice( $clientId,  $storeId)
    {
        // return $clientId;
        $storeId=Store::where('id',$storeId)->first();
        $clientId=Client::where('id',$clientId)->first();

        $store = $storeId;
        $client = $clientId;
        $as_sup_madunia = 0;
        $supplierData = 0;
        $supinvoiceMad = 0;
        $rassedd = [];
        // return $client->sup_id;
        if ($client->sup_id != null) {
            $as_sup_madunia = Supplier::where('id', $client->sup_id)->first()->raseed;
            $supplier = Supplier::where('id', $client->sup_id)->first();
            foreach ($supplier->order_suppliers as $key => $inv) {
                $c_id = $inv->currency_id;
                $c_date = $inv->confirmation_date;
                $Ac_all_currency_types = CurrencyType::with([
                    'currencies' => function ($query) use ($c_id, $c_date) {
                        return $query->where('from', '>=', $c_date)->where('to', '<=', $c_date)->where('currency_id', $c_id)->orWhere('to', '=', null);
                    },
                ])
                    ->where('id', $c_id)
                    ->get();
                $inv['Egp_total'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->total_price;
                $inv['Egp_paied'] = $Ac_all_currency_types[0]->currencies[0]->value * $inv->paied;
                $inv['currency_value'] = $Ac_all_currency_types[0]->currencies[0]->value;
            }
            $supplierData = $supplier;
            $supinvoiceMad = SupplierMadyonea::where('supplier_id', $supplier->id)->sum('paied');

            $SupplierHesapController = new SupplierHesapController();
            $rassedd = $SupplierHesapController->getRassedAll('all', $client->sup_id);
        } else {
            $SupplierHesapController = new SupplierHesapController();
            $rassedd = $SupplierHesapController->getRassedAll('client', $client->id);
        }
        $invoices = Invoice::where('client_id', $client->id)
            ->with('store')->with('refund_invoices')
            ->get();
        $invoiceMad = InvoiceClientMadyonea::with('payment')
            ->where('client_id', $client->id)
            ->get();

        $servicesMad = ServiceInvoice::where('client_id', $client->id)->sum('remain');
        // return $supplierData;
        $bank_types = BankType::all();
        $store_safe = Store::where('safe_accountant_number', '<>', '0')->get();
        $store_data = Store::where('id', $storeId->id)->get();

        // return $rassedd ;

        $sanadSarf = SanadSarf::where('client_sup_id', $client->id)->where('type', 1)->get();

        return view('clientInvoices', compact('sanadSarf', 'rassedd', 'bank_types', 'store_data', 'store_safe', 'store', 'client', 'invoices', 'invoiceMad', 'servicesMad', 'as_sup_madunia', 'supplierData', 'supinvoiceMad'));
    }
    public function Clientinvoiceprice( $clientId,  $storeId)
    {
        $storeId=Store::where('id',$storeId)->first();
        $clientId=Client::where('id',$clientId)->first();
        // return $clientId;
        $store = $storeId;
        $client = $clientId;
        $quotes = Quote::where('client_id', $client->id)
            ->with([
                'quoteItems' => function ($q) {
                    $source = $q->with('source')->get();
                    $q->with('status')->get();
                    $q->with('part_quality')->get();
                    $q->with('pricing_type')->get();
                    $q->where('part_type_id', '1')->with('part')->get();
                },
            ])
            ->get();

        for ($i = 0; $i < count($quotes); $i++) {
            $quotes[$i]['quoteItems']->map(function ($q) use ($storeId) {
                if ($q->part_type_id == 1) {
                    $q['allpartsRemain'] = AllPart::where('part_id', $q->part_id)
                        ->where('source_id', $q->source_id)
                        ->where('status_id', $q->status_id)
                        ->where('quality_id', $q->quality_id)
                        ->sum('remain_amount');
                    $q['price'] = SalePricing::where('part_id', $q->part_id)
                        ->where('source_id', $q->source_id)
                        ->where('status_id', $q->status_id)
                        ->where('quality_id', $q->quality_id)
                        ->where('type_id', $q->part_type_id)
                        ->where('to', null)
                        ->with('sale_type')
                        ->orderBy('price', 'DESC')
                        ->get();
                    $q['storeCount'] = $this->PartInStoresCount($q->part_id, $q->source_id, $q->status_id, $q->quality_id, $q->part_type_id);
                }
            });
        }

        $SaleType = PricingType::all();
        $alltaxes = Tax::all();

        $store_data = Store::where('id', '=', $storeId->id)->get();

        $items_part = $this->get_parts_info($store_data[0]->table_name);

        return view('Clientinvoiceprice', compact('quotes', 'store', 'client', 'SaleType', 'alltaxes', 'items_part'));
    }
   public function saveMadyonea(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $mad = new InvoiceClientMadyonea();
            $mad->client_id = $request->client_id;
            $mad->paied = $request->paied;
            $mad->date = Carbon::now();
            $mad->pyment_method = $request->payment;
            $mad->discount = $request->discount;
            $mad->note = $request->noteabd;
            $mad->user_id = auth()->user()->id;
            $mad->save();
            $payment_types = '-';
            ///////////////////////adel//////////////////////
            $client = Client::where('id', $request->client_id)->first();
            $store = Store::where('safe_accountant_number', $request->payment)->first();


            if ($request->payment_type === 'store') {
                $payment_types = $store->name;
                $total = MoneySafe::where('store_id', $store->id)
                    ->latest()
                    ->first();
                if (isset($total)) {
                    MoneySafe::create([
                        'notes' => 'سداد مبلغ مديونية من عميل ' . ' ' . $client->name . ' ' . $store->name,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->paied,
                        'total' => $total->total + $request->paied,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => $store->id,
                    ]);
                } else {
                    MoneySafe::create([
                        'notes' => 'سداد مبلغ مديونية من عميل ' . ' ' . $client->name . ' ' . $store->name,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->paied,
                        'total' => $request->paied,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => $store->id,
                    ]);
                }
            } else {
                $bank = BankType::where('accountant_number', $request->payment)->first();
                $payment_types = $bank->bank_name;
                $total = BankSafeMoney::where('bank_type_id', $bank->id)
                    ->latest()
                    ->first();
                if (isset($total)) {
                    BankSafeMoney::create([
                        'notes' => 'سداد مبلغ مديونية من عميل ' . ' ' . $client->name . ' ' . $bank->bank_name,
                        'date' => date('Y-m-d'),
                        'flag' => 2,
                        'money' => $request->paied,
                        'total' => $total->total + $request->paied,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => null,
                        'money_currency' => $request->paied,
                        'currency_id' => 400,
                        'bank_type_id' => $bank->id

                    ]);
                } else {
                    BankSafeMoney::create([
                        'notes' => 'سداد مبلغ مديونية من عميل ' . ' ' . $client->name . ' ' . $bank->bank_name,
                        'date' => date('Y-m-d'),
                        'flag' => 2,
                        'money' => $request->paied,
                        'total' => $request->paied,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => null,
                        'money_currency' => $request->paied,
                        'currency_id' => 400,
                        'bank_type_id' => $bank->id
                    ]);
                }
            }


            $quaditems = [];
            $automaicQayd = new QaydController();
            $acClientNo = Client::where('id', $request->client_id)->first()->accountant_number;

            array_push($quaditems, (object) ['acountant_id' => $request->payment, 'dayin' => 0, 'madin' => $request->paied]); // الخزنة  مدين
            array_push($quaditems, (object) ['acountant_id' => 39 , 'dayin' => 0, 'madin' => $request->discount]); // العميل دائن
            array_push($quaditems, (object) ['acountant_id' => $acClientNo, 'madin' => 0, 'dayin' => $request->paied + $request->discount ]); // العميل دائن



            $date = Carbon::now();
            $type = null;
            $notes = 'سداد مديونية ';
            $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);

            $paperTitle = " سند قبض ";
            $recordName = "العميل";
            // $recordName= $client->name;
            $recordValue = $client->name;
            $recoredId = $mad->id;
            $recoredUrl = 'sanad.print_sanad_abd' . $recoredId;
            $moneyVal = $request->paied;
            $personName = $client->name;
            $datee = $mad->date;
            $person_id=$client->id;

            $note=$mad->note;
            $emp=$mad->user->username;
            DB::commit();
            return view('sanad.print_sanad_abd', compact('note','emp','person_id','payment_types', 'paperTitle', 'recordName', 'recordValue', 'recoredUrl', 'moneyVal', 'personName', 'datee'));

            return redirect()->to('Clientinvoice/' . $request->client_id . '/' . $request->store_id);
        } catch (\Exception $e) {
            //throw $th;
            DB::rollback();
            session()->flash("error", $e->getMessage());
            return false;
        }

    }

    public function saveMadyoneasup(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
                $mad = new SupplierMadyonea();
                $mad->supplier_id = $request->supplier_id;
                $mad->paied = $request->paied;
                $mad->pyment_method = $request->payment;
                $mad->date = Carbon::now();
                $mad->discount = $request->discount;
                $mad->note = $request->note;

                $mad->save();


                $mad = new SanadSarf();
                $mad->client_sup_id = $request->supplier_id;
                $mad->paied = $request->paied;
                $mad->note = $request->note;
                $mad->date = Carbon::now();
                $mad->pyment_method = $request->payment;
                $mad->type = 2;
                $mad->discount = $request->discount;
                $mad->user_id= auth()->user()->id;
                

                $mad->save();
                ///////////////////////adel//////////////////////
                $sup = Supplier::where('id', $request->supplier_id)->first();
                $storeAc = Store::where('safe_accountant_number', $request->payment)->first();
                $payment_types = '-';
                if ($storeAc) {
                    $payment_types = $storeAc->name;
                    $total = MoneySafe::where('store_id', $storeAc->id)
                        ->latest()
                        ->first();
                    if (isset($total)) {
                        MoneySafe::create([
                            'notes' => 'سداد مبلغ مديونية  لمورد ' . ' ' . $sup->name,
                            'date' => date('Y-m-d'),
                            'flag' => 1,
                            'money' => $request->paied,
                            'total' => $total->total - $request->paied,
                            'type_money' => '20',
                            'user_id' => Auth::user()->id,
                            'store_id' => $storeAc->id,
                        ]);
                    } else {
                        // MoneySafe::create([
                        //     'notes' => 'سداد مبلغ مديونية  لمورد '.' '.$sup->name  ,
                        //     'date' => date('Y-m-d'),
                        //     'flag' => 1,
                        //     'money' => $request->paied,
                        //     'total' => $request->paied,
                        //     'type_money'=>'18',
                        //     'user_id'=>Auth::user()->id,
                        //     'store_id'=>$storeAc->id

                        // ]);
                    }
                } else {
                    $bank = BankType::where('accountant_number', $request->payment)->first();
                    $payment_types = $bank->bank_name;
                    $total = BankSafeMoney::where('bank_type_id', $bank->id)
                        ->latest()
                        ->first();
                    if (isset($total)) {
                        BankSafeMoney::create([
                            'notes' => 'سداد مبلغ مديونية  لمورد ' . ' ' . $sup->name,
                            'date' => date('Y-m-d'),
                            'flag' => 1,
                            'money' => $request->paied,
                            'total' => $total->total - $request->paied,
                            'type_money' => '20',
                            'user_id' => Auth::user()->id,
                            'store_id' => null,
                            'bank_type_id' => $bank->id,
                            'money_currency' => null,
                            'currency_id' => null,
                        ]);
                    }
                }

                $quaditems = [];
                $automaicQayd = new QaydController();
                $acSupNo = Supplier::where('id', $request->supplier_id)->first()->accountant_number;

                array_push($quaditems, (object) ['acountant_id' => $acSupNo, 'dayin' => 0, 'madin' => $request->paied + $request->discount]); // المورد مدين
                array_push($quaditems, (object) ['acountant_id' => $request->payment, 'madin' => 0, 'dayin' => $request->paied]); // الخزنة  دائن
                array_push($quaditems, (object) ['acountant_id' => 48 , 'madin' => 0, 'dayin' => $request->discount]); //
                $date = Carbon::now();
                $type = null;
                $notes = ' سداد مديونية لمورد';
                $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);

                $paperTitle = " سند صرف ";
                $recordName = "المورد";
                // $recordName= $client->name;
                $recordValue = $sup->name;
                $recoredId = $mad->id;
                $recoredUrl = 'sanad.print_sanad_sarf' . $recoredId;
                $moneyVal = $request->paied;
                $personName = $sup->name;
                $datee = $mad->date;
           
                $data = SanadSarf::where('id', $mad->id)->with('payment')->with('user')->first();

                $note=$data->note;
                $emp=$data->user->username;
                
                
                DB::commit();
                return view('sanad.print_sanad_sarf', compact('note','emp','payment_types', 'paperTitle', 'recordName', 'recordValue', 'recoredUrl', 'moneyVal', 'personName', 'datee'));
                return redirect()->to('Supplierinvoice/' . $request->supplier_id);

            } catch (\Exception $e) {
                //throw $th;
                DB::rollback();
                session()->flash("error", $e->getMessage());
                return false;
            }


        // return $presaleOrders;

    }


    public function printBarcode($barcodeTxt, $name)
    {
        // return $name;
        return view('printBarcode', compact('barcodeTxt', 'name'));
    }

    public function PartInStoresCount($partId, $sourceId, $statusId, $qualityId, $type)
    {
        // get all stores
        $stores = Store::all();

        if ($type == 1) {
            return $stores->each(function ($item) use ($partId, $sourceId, $statusId, $qualityId, $type) {
                // $storeClass = 'App\Models\\'.ucfirst($item->table_name);
                $item->storepart = DB::table($item->table_name)
                    ->select($item->table_name . '.*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                    ->where('all_parts.part_id', '=', $partId)
                    ->where('all_parts.source_id', '=', $sourceId)
                    ->where('all_parts.status_id', '=', $statusId)
                    ->where('all_parts.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->get();
                $item->sections = StoreSection::with('store_structure')->where('store_id',$item->id)
                ->where('part_id', '=', $partId)
                ->where('source_id', '=', $sourceId)
                ->where('status_id', '=', $statusId)
                ->where('quality_id', '=', $qualityId)
                ->where('type_id', '=', $type)->get();

                $item->storepartCount = DB::table($item->table_name)
                    ->select('*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                    ->where('all_parts.part_id', '=', $partId)
                    ->where('all_parts.source_id', '=', $sourceId)
                    ->where('all_parts.status_id', '=', $statusId)
                    ->where('all_parts.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->where($item->table_name . '.type_id', '=', $type)
                    ->sum($item->table_name . '.amount');
            });
        } elseif ($type == 6) {
            return $stores->each(function ($item) use ($partId, $sourceId, $statusId, $qualityId, $type) {
                // $storeClass = 'App\Models\\'.ucfirst($item->table_name);
                $item->storepart = DB::table($item->table_name)
                    ->select($item->table_name . '.*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                    ->where('all_kits.part_id', '=', $partId)
                    ->where('all_kits.source_id', '=', $sourceId)
                    ->where('all_kits.status_id', '=', $statusId)
                    ->where('all_kits.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->get();
                $item->sections = StoreSection::with('store_structure')->where('store_id',$item->id)
                ->where('part_id', '=', $partId)
                ->where('source_id', '=', $sourceId)
                ->where('status_id', '=', $statusId)
                ->where('quality_id', '=', $qualityId)
                ->where('type_id', '=', $type)->get();
                
                $item->storepartCount = DB::table($item->table_name)
                    ->select('*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                    ->where('all_kits.part_id', '=', $partId)
                    ->where('all_kits.source_id', '=', $sourceId)
                    ->where('all_kits.status_id', '=', $statusId)
                    ->where('all_kits.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->where($item->table_name . '.type_id', '=', $type)
                    ->sum($item->table_name . '.amount');
            });
        } elseif ($type == 2) {
            return $stores->each(function ($item) use ($partId, $sourceId, $statusId, $qualityId, $type) {
                // $storeClass = 'App\Models\\'.ucfirst($item->table_name);
                $item->storepart = DB::table($item->table_name)
                    ->select($item->table_name . '.*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                    ->where('all_wheels.part_id', '=', $partId)
                    ->where('all_wheels.source_id', '=', $sourceId)
                    ->where('all_wheels.status_id', '=', $statusId)
                    ->where('all_wheels.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->get();
                $item->sections = StoreSection::with('store_structure')->where('store_id',$item->id)
                ->where('part_id', '=', $partId)
                ->where('source_id', '=', $sourceId)
                ->where('status_id', '=', $statusId)
                ->where('quality_id', '=', $qualityId)
                ->where('type_id', '=', $type)->get();
                
                $item->storepartCount = DB::table($item->table_name)
                    ->select('*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                    ->where('all_wheels.part_id', '=', $partId)
                    ->where('all_wheels.source_id', '=', $sourceId)
                    ->where('all_wheels.status_id', '=', $statusId)
                    ->where('all_wheels.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->where($item->table_name . '.type_id', '=', $type)
                    ->sum($item->table_name . '.amount');
            });
        } elseif ($type == 3) {
            return $stores->each(function ($item) use ($partId, $sourceId, $statusId, $qualityId, $type) {
                // $storeClass = 'App\Models\\'.ucfirst($item->table_name);
                $item->storepart = DB::table($item->table_name)
                    ->select($item->table_name . '.*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_tractors', 'stores_log.All_part_id', '=', 'all_tractors.id')
                    ->where('all_tractors.part_id', '=', $partId)
                    ->where('all_tractors.source_id', '=', $sourceId)
                    ->where('all_tractors.status_id', '=', $statusId)
                    ->where('all_tractors.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->get();
                    
                $item->sections = StoreSection::with('store_structure')->where('store_id',$item->id)
                ->where('part_id', '=', $partId)
                ->where('source_id', '=', $sourceId)
                ->where('status_id', '=', $statusId)
                ->where('quality_id', '=', $qualityId)
                ->where('type_id', '=', $type)->get();

                $item->storepartCount = DB::table($item->table_name)
                    ->select('*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_tractors', 'stores_log.All_part_id', '=', 'all_tractors.id')
                    ->where('all_tractors.part_id', '=', $partId)
                    ->where('all_tractors.source_id', '=', $sourceId)
                    ->where('all_tractors.status_id', '=', $statusId)
                    ->where('all_tractors.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->where($item->table_name . '.type_id', '=', $type)
                    ->sum($item->table_name . '.amount');
            });
        } elseif ($type == 4) {
            return $stores->each(function ($item) use ($partId, $sourceId, $statusId, $qualityId, $type) {
                // $storeClass = 'App\Models\\'.ucfirst($item->table_name);
                $item->storepart = DB::table($item->table_name)
                    ->select($item->table_name . '.*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_clarks', 'stores_log.All_part_id', '=', 'all_clarks.id')
                    ->where('all_clarks.part_id', '=', $partId)
                    ->where('all_clarks.source_id', '=', $sourceId)
                    ->where('all_clarks.status_id', '=', $statusId)
                    ->where('all_clarks.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->get();
                $item->sections = StoreSection::with('store_structure')->where('store_id',$item->id)
                ->where('part_id', '=', $partId)
                ->where('source_id', '=', $sourceId)
                ->where('status_id', '=', $statusId)
                ->where('quality_id', '=', $qualityId)
                ->where('type_id', '=', $type)->get();
                
                $item->storepartCount = DB::table($item->table_name)
                    ->select('*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_clarks', 'stores_log.All_part_id', '=', 'all_clarks.id')
                    ->where('all_clarks.part_id', '=', $partId)
                    ->where('all_clarks.source_id', '=', $sourceId)
                    ->where('all_clarks.status_id', '=', $statusId)
                    ->where('all_clarks.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->where($item->table_name . '.type_id', '=', $type)
                    ->sum($item->table_name . '.amount');
            });
        } elseif ($type == 5) {
            return $stores->each(function ($item) use ($partId, $sourceId, $statusId, $qualityId, $type) {
                // $storeClass = 'App\Models\\'.ucfirst($item->table_name);
                $item->storepart = DB::table($item->table_name)
                    ->select($item->table_name . '.*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_equips', 'stores_log.All_part_id', '=', 'all_equips.id')
                    ->where('all_equips.part_id', '=', $partId)
                    ->where('all_equips.source_id', '=', $sourceId)
                    ->where('all_equips.status_id', '=', $statusId)
                    ->where('all_equips.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->get();
                    
                $item->sections = StoreSection::with('store_structure')->where('store_id',$item->id)
                ->where('part_id', '=', $partId)
                ->where('source_id', '=', $sourceId)
                ->where('status_id', '=', $statusId)
                ->where('quality_id', '=', $qualityId)
                ->where('type_id', '=', $type)->get();

                $item->storepartCount = DB::table($item->table_name)
                    ->select('*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_equips', 'stores_log.All_part_id', '=', 'all_equips.id')
                    ->where('all_equips.part_id', '=', $partId)
                    ->where('all_equips.source_id', '=', $sourceId)
                    ->where('all_equips.status_id', '=', $statusId)
                    ->where('all_equips.quality_id', '=', $qualityId)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->where($item->table_name . '.type_id', '=', $type)
                    ->sum($item->table_name . '.amount');
            });
        }
    }



    public function CardInfo1(Request $request)
    {
        $partData = [];
        $allpartId = $request->allpartId;

        $allpart = AllPart::where('id', $allpartId)
            ->with([
                'part' => function ($m) {
                    $m->with([
                        'sub_group' => function ($q) {
                            $q->with('group')->get();
                        },
                    ]);
                    $m->with([
                        'part_details' => function ($q) {
                            $q->with('part_spec')->with('mesure_unit')->get();
                        },
                    ]);
                    $m->with([
                        'part_numbers' => function ($q) {
                            $q->with('supplier')->get();
                        },
                    ]);
                    $m->with('part_images');
                    $m->with([
                        'part_models' => function ($q) {
                            $q->with([
                                'series' => function ($q1) {
                                    $q1->with([
                                        'model' => function ($q2) {
                                            $q2->with('brand')->with('brand_type')->get();
                                        },
                                    ])->get();
                                },
                            ])->get();
                        },
                    ]);

                    $m->with([
                        'related_parts' => function ($q) {
                            $q->with([
                                'part' => function ($q1) {
                                    $q1->with('part_images')->get();
                                },
                            ])->get();
                        },
                    ]);
                    $m->with([
                        'all_parts' => function ($q) {
                            $q->with('status')->with('source')->with('part_quality')->get();
                        },
                    ]);
                },
            ])
            ->with('status')
            ->with('source')
            ->with('part_quality')
            ->get();

        $allpart[0]['price'] = SalePricing::where('part_id', $allpart[0]->part_id)
            ->where('source_id', $allpart[0]->source_id)
            ->where('status_id', $allpart[0]->status_id)
            ->where('quality_id', $allpart[0]->quality_id)
            ->where('type_id', '1')
            ->where('to', null)
            ->with('sale_type')
            ->orderBy('price', 'DESC')
            ->get();

        $allpart[0]['stores'] = $this->PartInStoresCount($allpart[0]->part_id, $allpart[0]->source_id, $allpart[0]->status_id, $allpart[0]->quality_id, 1);

        $allpart[0]->part->all_parts->map(function ($q) use ($allpart) {
            // if($q->id != $allpart[0]->id && $q->source_id != $allpart[0]->source_id && $q->status_id != $allpart[0]->status_id && $q->quality_id != $allpart[0]->quality_id){
            $q['rprice'] = SalePricing::where('part_id', $q->part_id)
                ->where('source_id', $q->source_id)
                ->where('status_id', $q->status_id)
                ->where('quality_id', $q->quality_id)
                ->where('type_id', '1')
                ->where('to', null)
                ->with('sale_type')
                ->orderBy('price', 'DESC')
                ->get();
            $q['stock'] = $this->PartInStoresCount($q->part_id, $q->source_id, $q->status_id, $q->quality_id, 1);
            // }else{
            //     $q['rprice'] = [];
            //     $q['stock'] = [];
            // }
        });

        // return $allpart[0]->part->all_parts->unique('source_id')->all();
        $partData['allpart'] = $allpart;

        return $partData;
    }

    public function CardInfoOld(Request $request)
    {
        $partData = [];
        $allpartId = $request->allpartId;
        $partId = $request->partId;
        $allpart = '';

        if ($allpartId) {
            $allpart = AllPart::where('id', $allpartId)
                ->with([
                    'part' => function ($m) {
                        $m->with([
                            'sub_group' => function ($q) {
                                $q->with('group')->get();
                            },
                        ]);
                        $m->with([
                            'part_details' => function ($q) {
                                $q->with('part_spec')->with('mesure_unit')->get();
                            },
                        ]);
                        $m->with([
                            'part_numbers' => function ($q) {
                                $q->with('supplier')->get();
                            },
                        ]);
                        $m->with('part_images');
                        $m->with([
                            'part_models' => function ($q) {
                                $q->with([
                                    'series' => function ($q1) {
                                        $q1->with([
                                            'model' => function ($q2) {
                                                $q2->with('brand')->with('brand_type')->get();
                                            },
                                        ])->get();
                                    },
                                ])->get();
                            },
                        ]);

                        $m->with([
                            'related_parts' => function ($q) {
                                $q->with([
                                    'part' => function ($q1) {
                                        $q1->with('part_images')->get();
                                    },
                                ])->get();
                            },
                        ]);
                        $m->with([
                            'all_parts' => function ($q) {
                                $q->with('status')->with('source')->with('part_quality')->get();
                            },
                        ]);
                    },
                ])
                ->with('status')
                ->with('source')
                ->with('part_quality')
                ->get();

            $allpart[0]['price'] = SalePricing::where('part_id', $allpart[0]->part_id)
                ->where('source_id', $allpart[0]->source_id)
                ->where('status_id', $allpart[0]->status_id)
                ->where('quality_id', $allpart[0]->quality_id)
                ->where('type_id', '1')
                ->where('to', null)
                ->with('sale_type')
                ->orderBy('price', 'DESC')
                ->get();

            $allpart[0]['stores'] = $this->PartInStoresCount($allpart[0]->part_id, $allpart[0]->source_id, $allpart[0]->status_id, $allpart[0]->quality_id, 1);

            $allpart[0]->part->all_parts->map(function ($q) use ($allpart) {
                $q['rprice'] = SalePricing::where('part_id', $q->part_id)
                    ->where('source_id', $q->source_id)
                    ->where('status_id', $q->status_id)
                    ->where('quality_id', $q->quality_id)
                    ->where('type_id', '1')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get();
                $q['stock'] = $this->PartInStoresCount($q->part_id, $q->source_id, $q->status_id, $q->quality_id, 1);
            });
        } else {
            $allpart = AllPart::where('part_id', $partId)
                ->with([
                    'part' => function ($m) {
                        $m->with([
                            'sub_group' => function ($q) {
                                $q->with('group')->get();
                            },
                        ]);
                        $m->with([
                            'part_details' => function ($q) {
                                $q->with('part_spec')->with('mesure_unit')->get();
                            },
                        ]);
                        $m->with([
                            'part_numbers' => function ($q) {
                                $q->with('supplier')->get();
                            },
                        ]);
                        $m->with('part_images');
                        $m->with([
                            'part_models' => function ($q) {
                                $q->with([
                                    'series' => function ($q1) {
                                        $q1->with([
                                            'model' => function ($q2) {
                                                $q2->with('brand')->with('brand_type')->get();
                                            },
                                        ])->get();
                                    },
                                ])->get();
                            },
                        ]);

                        $m->with([
                            'related_parts' => function ($q) {
                                $q->with([
                                    'part' => function ($q1) {
                                        $q1->with('part_images')->get();
                                    },
                                ])->get();
                            },
                        ]);
                        $m->with([
                            'all_parts' => function ($q) {
                                $q->with('status')->with('source')->with('part_quality')->get();
                            },
                        ]);
                    },
                ])
                ->with('status')
                ->with('source')
                ->with('part_quality')
                ->first();

            if ($allpart) {
                $allpart['price'] = SalePricing::where('part_id', $allpart->part_id)
                    ->where('source_id', $allpart->source_id)
                    ->where('status_id', $allpart->status_id)
                    ->where('quality_id', $allpart->quality_id)
                    ->where('type_id', '1')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get();

                $allpart['stores'] = $this->PartInStoresCount($allpart->part_id, $allpart->source_id, $allpart->status_id, $allpart->quality_id, 1);

                $allpart->part->all_parts->map(function ($q) use ($allpart) {
                    $q['rprice'] = SalePricing::where('part_id', $q->part_id)
                        ->where('source_id', $q->source_id)
                        ->where('status_id', $q->status_id)
                        ->where('quality_id', $q->quality_id)
                        ->where('type_id', '1')
                        ->where('to', null)
                        ->with('sale_type')
                        ->orderBy('price', 'DESC')
                        ->get();
                    $q['stock'] = $this->PartInStoresCount($q->part_id, $q->source_id, $q->status_id, $q->quality_id, 1);
                });
            } else {
                $allpart['part'] = part::where('id', $partId)
                    ->with([
                        'sub_group' => function ($q) {
                            $q->with('group')->get();
                        },
                    ])
                    ->with([
                        'part_details' => function ($q) {
                            $q->with('part_spec')->with('mesure_unit')->get();
                        },
                    ])
                    ->with([
                        'part_numbers' => function ($q) {
                            $q->with('supplier')->get();
                        },
                    ])
                    ->with('part_images')
                    ->with([
                        'part_models' => function ($q) {
                            $q->with([
                                'series' => function ($q1) {
                                    $q1->with([
                                        'model' => function ($q2) {
                                            $q2->with('brand')->with('brand_type')->get();
                                        },
                                    ])->get();
                                },
                            ])->get();
                        },
                    ])
                    ->with([
                        'related_parts' => function ($q) {
                            $q->with([
                                'part' => function ($q1) {
                                    $q1->with('part_images')->get();
                                },
                            ])->get();
                        },
                    ])
                    ->with([
                        'all_parts' => function ($q) {
                            $q->with('status')->with('source')->with('part_quality')->get();
                        },
                    ])
                    ->first();
                // return $allpart;
                $allpart['price'] = [];
                $allpart['stores'] = [];
                $allpart['price'] = [];
                $allpart['part']['all_parts'] = [];
                $allpart['part']['status'] = [];
                $allpart['part']['source'] = [];
                $allpart['part']['part_quality'] = [];
            }
        }
        $partData['allpart'] = $allpart;
        return $partData;
    }
    public function CardInfo(Request $request)
    {
        $ptypeId = $request->typeId;
        if ($ptypeId == 1) {
            $partData = [];
            $allpartId = $request->allpartId;
            $partId = $request->partId;
            $allpart = '';

            if ($allpartId) {
                $allpart = AllPart::where('id', $allpartId)
                    ->with([
                        'part' => function ($m) {
                            $m->with([
                                'sub_group' => function ($q) {
                                    $q->with('group')->get();
                                },
                            ]);
                            $m->with([
                                'part_details' => function ($q) {
                                    $q->with('part_spec')->with('mesure_unit')->get();
                                },
                            ]);
                            $m->with([
                                'part_numbers' => function ($q) {
                                    $q->with('supplier')->get();
                                },
                            ]);
                            $m->with('part_images');
                            $m->with([
                                'part_models' => function ($q) {
                                    $q->with([
                                        'series' => function ($q1) {
                                            $q1->with([
                                                'model' => function ($q2) {
                                                    $q2->with('brand')->with('brand_type')->get();
                                                },
                                            ])->get();
                                        },
                                    ])->get();
                                },
                            ]);

                            $m->with([
                                'related_parts' => function ($q) {
                                    $q->with([
                                        'part' => function ($q1) {
                                            $q1->with('part_images')->get();
                                        },
                                    ])->get();
                                },
                            ]);
                            $m->with([
                                'all_parts' => function ($q) {
                                    $q->with('status')->with('source')->with('part_quality')->get();
                                },
                            ]);
                        },
                    ])
                    ->with('status')
                    ->with('source')
                    ->with('part_quality')
                    ->get();

                $allpart[0]['price'] = SalePricing::where('part_id', $allpart[0]->part_id)
                    ->where('source_id', $allpart[0]->source_id)
                    ->where('status_id', $allpart[0]->status_id)
                    ->where('quality_id', $allpart[0]->quality_id)
                    ->where('type_id', '1')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get();

                $allpart[0]['stores'] = $this->PartInStoresCount($allpart[0]->part_id, $allpart[0]->source_id, $allpart[0]->status_id, $allpart[0]->quality_id, 1);

                $allpart[0]->part->all_parts->map(function ($q) use ($allpart) {
                    $q['rprice'] = SalePricing::where('part_id', $q->part_id)
                        ->where('source_id', $q->source_id)
                        ->where('status_id', $q->status_id)
                        ->where('quality_id', $q->quality_id)
                        ->where('type_id', '1')
                        ->where('to', null)
                        ->with('sale_type')
                        ->orderBy('price', 'DESC')
                        ->get();
                    $q['stock'] = $this->PartInStoresCount($q->part_id, $q->source_id, $q->status_id, $q->quality_id, 1);
                });
            } else {
                $allpart = AllPart::where('part_id', $partId)
                    ->with([
                        'part' => function ($m) {
                            $m->with([
                                'sub_group' => function ($q) {
                                    $q->with('group')->get();
                                },
                            ]);
                            $m->with([
                                'part_details' => function ($q) {
                                    $q->with('part_spec')->with('mesure_unit')->get();
                                },
                            ]);
                            $m->with([
                                'part_numbers' => function ($q) {
                                    $q->with('supplier')->get();
                                },
                            ]);
                            $m->with('part_images');
                            $m->with([
                                'part_models' => function ($q) {
                                    $q->with([
                                        'series' => function ($q1) {
                                            $q1->with([
                                                'model' => function ($q2) {
                                                    $q2->with('brand')->with('brand_type')->get();
                                                },
                                            ])->get();
                                        },
                                    ])->get();
                                },
                            ]);

                            $m->with([
                                'related_parts' => function ($q) {
                                    $q->with([
                                        'part' => function ($q1) {
                                            $q1->with('part_images')->get();
                                        },
                                    ])->get();
                                },
                            ]);
                            $m->with([
                                'all_parts' => function ($q) {
                                    $q->with('status')->with('source')->with('part_quality')->get();
                                },
                            ]);
                        },
                    ])
                    ->with('status')
                    ->with('source')
                    ->with('part_quality')
                    ->first();

                if ($allpart) {
                    $allpart['price'] = SalePricing::where('part_id', $allpart->part_id)
                        ->where('source_id', $allpart->source_id)
                        ->where('status_id', $allpart->status_id)
                        ->where('quality_id', $allpart->quality_id)
                        ->where('type_id', '1')
                        ->where('to', null)
                        ->with('sale_type')
                        ->orderBy('price', 'DESC')
                        ->get();

                    $allpart['stores'] = $this->PartInStoresCount($allpart->part_id, $allpart->source_id, $allpart->status_id, $allpart->quality_id, 1);

                    $allpart->part->all_parts->map(function ($q) use ($allpart) {
                        $q['rprice'] = SalePricing::where('part_id', $q->part_id)
                            ->where('source_id', $q->source_id)
                            ->where('status_id', $q->status_id)
                            ->where('quality_id', $q->quality_id)
                            ->where('type_id', '1')
                            ->where('to', null)
                            ->with('sale_type')
                            ->orderBy('price', 'DESC')
                            ->get();
                        $q['stock'] = $this->PartInStoresCount($q->part_id, $q->source_id, $q->status_id, $q->quality_id, 1);
                    });
                } else {
                    $allpart['part'] = part::where('id', $partId)
                        ->with([
                            'sub_group' => function ($q) {
                                $q->with('group')->get();
                            },
                        ])
                        ->with([
                            'part_details' => function ($q) {
                                $q->with('part_spec')->with('mesure_unit')->get();
                            },
                        ])
                        ->with([
                            'part_numbers' => function ($q) {
                                $q->with('supplier')->get();
                            },
                        ])
                        ->with('part_images')
                        ->with([
                            'part_models' => function ($q) {
                                $q->with([
                                    'series' => function ($q1) {
                                        $q1->with([
                                            'model' => function ($q2) {
                                                $q2->with('brand')->with('brand_type')->get();
                                            },
                                        ])->get();
                                    },
                                ])->get();
                            },
                        ])
                        ->with([
                            'related_parts' => function ($q) {
                                $q->with([
                                    'part' => function ($q1) {
                                        $q1->with('part_images')->get();
                                    },
                                ])->get();
                            },
                        ])
                        ->with([
                            'all_parts' => function ($q) {
                                $q->with('status')->with('source')->with('part_quality')->get();
                            },
                        ])
                        ->first();
                    // return $allpart;
                    $allpart['price'] = [];
                    $allpart['stores'] = [];
                    $allpart['price'] = [];
                    $allpart['part']['all_parts'] = [];
                    $allpart['part']['status'] = [];
                    $allpart['part']['source'] = [];
                    $allpart['part']['part_quality'] = [];
                }
            }
            $partData['allpart'] = $allpart;
            return $partData;
        } elseif ($ptypeId == 6) {
            return $this->kitCardInfo($request);
        } elseif ($ptypeId == 2) {
            return $this->wheelCardInfo($request);
        } elseif ($ptypeId == 3) {
            return $this->tractorCardInfo($request);
        } elseif ($ptypeId == 4) {
            return $this->clarkCardInfo($request);
        } elseif ($ptypeId == 5) {
            return $this->equipCardInfo($request);
        }
    }
    public function saveClientPrice(Request $request)
    {
        if ($request->client) {
            $quote = new Quote();
            $quote->name = Carbon::now();
            $quote->date = Carbon::now();
            $quote->client_id = $request->client;
            $quote->store_id = $request->storeId;
            $quote->price_without_tax = $request->subtotal;
            $quote->tax_amount = $request->taxval;
            $quote->save();

            $inv_items = $request->items_part;
            for ($i = 0; $i < count($inv_items); $i++) {
                $item = explode('-', $inv_items[$i]);
                $part_id = $item[0];
                $source_id = $item[1];
                $status_id = $item[2];
                $quality_id = $item[3];
                $type_id = $item[4];
                $amount = $request->itemAmount[$i];
                $itemPrice = $request->itemPrice[$i];

                $QuoteItem = new QuoteItem();
                $QuoteItem->date = Carbon::now();

                $QuoteItem->part_id = $part_id;
                $QuoteItem->amount = $amount;
                $QuoteItem->selected_price = $itemPrice;
                $QuoteItem->source_id = $source_id;
                $QuoteItem->status_id = $status_id;
                $QuoteItem->quality_id = $quality_id;
                $QuoteItem->part_type_id = $type_id;
                $QuoteItem->quote_id = $quote->id;
                $QuoteItem->sale_type = $request->pricetype[$i];
                $QuoteItem->save();
            }

            return 'Successfully Added';
        } else {
            return 'No Client Selected';
        }
    }

    public function getquoteData($id, $storeId)
    {
        $quote = Quote::where('id', $id)
            ->with([
                'quoteItems' => function ($q) {
                    $source = $q->with('source')->get();
                    $q->with('status')->get();
                    $q->with('part_quality')->get();
                    $q->with('pricing_type')->get();
                    $q->where('part_type_id', '1')->with('part')->get();
                },
            ])
            ->get();

        $quote[0]['quoteItems']->map(function ($q) use ($storeId) {
            if ($q->part_type_id == 1) {
                $q['allparts'] = AllPart::where('part_id', $q->part_id)
                    ->where('source_id', $q->source_id)
                    ->where('status_id', $q->status_id)
                    ->where('quality_id', $q->quality_id)
                    ->get();
            }
        });

        return $quote;
    }
    public function saveclientinvoiceprice(Request $request)
    {
        return $request;
        $invoice = new Invoice();

        $invoice->name = Carbon::now();
        $invoice->casher_id = Auth::user()->id;
        $invoice->discount = $request->discPrice;
        $invoice->actual_price = $request->totalPrice;
        $invoice->client_id = $request->clientId;
        $invoice->company_id = '10';
        $invoice->store_id = $request->storeId;
        $invoice->price_without_tax = $request->subtotalPrice;
        $invoice->tax_amount = $request->taxPrice;
        $invoice->paied = $request->paiedPrice;
        $invoice->date = Carbon::now();
        $invoice->save();

        /// invoice Tax /////////////////
        // if(isset($request->taxes)){
        //     for ($i=0; $i < count($request->taxes) ; $i++) {
        //         $invTax = new InvoicesTax();
        //         $invTax->invoice_id =$invoice->id ;
        //         $tax= Tax::where('value',$request->taxes[$i])->get();
        //         $invTax->tax_id=$tax[0]->id;
        //         $invTax->save();

        //     }
        // }

        //////////////////// Only Part//////////////////////////
        $inv_items = $request->part_id;
        for ($i = 0; $i < count($inv_items); $i++) {
            $part_id = $inv_items[$i];
            $source_id = $request->source_id[$i];
            $status_id = $request->status_id[$i];
            $quality_id = $request->quality_id[$i];
            $type_id = $request->type_id[$i];
            $amount = $request->itemamount[$i];

            $invoiceItems = new InvoiceItem();
            $invoiceItems->date = Carbon::now();

            $invoiceItems->part_id = $part_id;
            $invoiceItems->amount = $amount;
            $invoiceItems->source_id = $source_id;
            $invoiceItems->status_id = $status_id;
            $invoiceItems->quality_id = $quality_id;
            $invoiceItems->part_type_id = $type_id;
            $invoiceItems->invoice_id = $invoice->id;
            $invoiceItems->sale_type = $request->saletype;
            $invoiceItems->save();

            //// remove from store ///////////////////

            $allparts = AllPart::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->get();

            $store = Store::where('id', $request->storeId)->get();
            $store_id = $store[0]->id;
            $store_name = $store[0]->name;
            $store_table_name = $store[0]->table_name;

            try {
                DB::table($store_table_name)
                    ->where('part_id', $part_id)
                    ->where('supplier_order_id', $allparts[0]->order_supplier_id)
                    ->where('type_id', 1)
                    ->decrement('amount', $amount);
            } catch (\Throwable $th) {
                //throw $th;
            }

            /////////////////////////////////////

            ////// remove from all parts ////////

            AllPart::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->decrement('remain_amount', $amount);

            //////////////////////////////////////
            ////// remove from Sectios ////////
            try {
                DB::table('store_section')
                    ->where('part_id', $part_id)
                    ->where('supplier_order_id', $allparts[0]->order_supplier_id)
                    ->where('type_id', 1)
                    ->where('source_id', $source_id)
                    ->where('status_id', $status_id)
                    ->where('quality_id', $quality_id)
                    ->decrement('amount', $amount);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        /////////////////////adel///////////
        $store = Store::where('id', $request->storeId)->first();
        $cli = Client::where('id', $request->clientId)->get();
        $total = MoneySafe::where('store_id', $request->storeId)
            ->latest()
            ->first();
        if (isset($total)) {
            MoneySafe::create([
                'notes' => 'مبلغ فاتورة بيع رقم' . ' ' . $invoice->id . ' ' . ' من عميل ' . ' ' . $cli[0]->name . ' ' . $store->name,

                'date' => date('Y-m-d'),
                'flag' => 1,
                'money' => $request->paiedPrice,
                'total' => $total->total + $request->paiedPrice,
                'type_money' => '0',
                'user_id' => Auth::user()->id,
                'store_id' => $request->storeId,
                'note_id' => 17,
            ]);
        } else {
            MoneySafe::create([
                'notes' => 'مبلغ فاتورة بيع رقم' . ' ' . $invoice->id . ' ' . ' من عميل ' . ' ' . $cli[0]->name . ' ' . $store->name,
                'date' => date('Y-m-d'),
                'flag' => 1,
                'money' => $request->paiedPrice,
                'total' => $request->paiedPrice,
                'type_money' => '0',
                'user_id' => Auth::user()->id,
                'store_id' => $request->storeId,
                'note_id' => 17,
            ]);
        }

        /// delete quote $request->quoteId
        Quote::find($request->quoteId)->delete();

        return redirect()->to('printInvoice/' . $invoice->id);
    }

    // *******************************salam******************************************
    public function kitCardInfo(Request $request)
    {
        $partData = [];
        $allpartId = $request->allpartId;
        $partId = $request->partId;
        $allpart = '';

        if ($allpartId) {
            $allpart = AllKit::where('id', $allpartId)
                ->with([
                    'kit' => function ($m) {
                        // $m->with(['sub_group' => function ($q) {
                        //     $q->with('group')->get();
                        // }]);
                        // $m->sub_group=[];
                        $m->with([
                            'kit_details' => function ($q) {
                                $q->with('kit_specs')->with('mesure_unit')->get();
                            },
                        ]);
                        $m->with([
                            'kit_numbers' => function ($q) {
                                $q->with('supplier')->get();
                            },
                        ]);
                        $m->with('kit_images');
                        $m->with([
                            'kit_models' => function ($q) {
                                $q->with([
                                    'series' => function ($q1) {
                                        $q1->with([
                                            'model' => function ($q2) {
                                                $q2->with('brand')->with('brand_type')->get();
                                            },
                                        ])->get();
                                    },
                                ])->get();
                            },
                        ]);

                        // $m->with(['related_parts' => function ($q) {
                        //     $q->with(['part' => function ($q1) {
                        //         $q1->with('part_images')->get();
                        //     }])->get();
                        // }]);
                        $m->with([
                            'all_kits' => function ($q) {
                                $q->with('status')->with('source')->with('part_quality')->get();
                            },
                        ]);
                    },
                ])
                ->with('status')
                ->with('source')
                ->with('part_quality')
                ->get();

            $allpart[0]['price'] = SalePricing::where('part_id', $allpart[0]->part_id)
                ->where('source_id', $allpart[0]->source_id)
                ->where('status_id', $allpart[0]->status_id)
                ->where('quality_id', $allpart[0]->quality_id)
                ->where('type_id', '6')
                ->where('to', null)
                ->with('sale_type')
                ->orderBy('price', 'DESC')
                ->get();

            $allpart[0]['stores'] = $this->PartInStoresCount($allpart[0]->part_id, $allpart[0]->source_id, $allpart[0]->status_id, $allpart[0]->quality_id, 6);
            // return $allpart;
            $allpart[0]->kit->all_kits->map(function ($q) use ($allpart) {
                $q['rprice'] = SalePricing::where('part_id', $q->part_id)
                    ->where('source_id', $q->source_id)
                    ->where('status_id', $q->status_id)
                    ->where('quality_id', $q->quality_id)
                    ->where('type_id', '6')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get();
                $q['stock'] = $this->PartInStoresCount($q->part_id, $q->source_id, $q->status_id, $q->quality_id, 6);
            });
        } else {
            $allpart = AllKit::where('part_id', $partId)
                ->with([
                    'part' => function ($m) {
                        // $m->with(['sub_group' => function ($q) {
                        //     $q->with('group')->get();
                        // }]);
                        $m->with([
                            'kit_details' => function ($q) {
                                $q->with('kit_specs')->with('mesure_unit')->get();
                            },
                        ]);
                        $m->with([
                            'kit_numbers' => function ($q) {
                                $q->with('supplier')->get();
                            },
                        ]);
                        $m->with('kit_images');
                        $m->with([
                            'kit_models' => function ($q) {
                                $q->with([
                                    'series' => function ($q1) {
                                        $q1->with([
                                            'model' => function ($q2) {
                                                $q2->with('brand')->with('brand_type')->get();
                                            },
                                        ])->get();
                                    },
                                ])->get();
                            },
                        ]);

                        // $m->with(['related_parts' => function ($q) {
                        //     $q->with(['part' => function ($q1) {
                        //         $q1->with('part_images')->get();
                        //     }])->get();
                        // }]);
                        $m->with([
                            'all_kits' => function ($q) {
                                $q->with('status')->with('source')->with('part_quality')->get();
                            },
                        ]);
                    },
                ])
                ->with('status')
                ->with('source')
                ->with('part_quality')
                ->first();

            if ($allpart) {
                $allpart['price'] = SalePricing::where('part_id', $allpart->part_id)
                    ->where('source_id', $allpart->source_id)
                    ->where('status_id', $allpart->status_id)
                    ->where('quality_id', $allpart->quality_id)
                    ->where('type_id', '6')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get();

                $allpart['stores'] = $this->PartInStoresCount($allpart->part_id, $allpart->source_id, $allpart->status_id, $allpart->quality_id, 6);

                $allpart->kit->all_kits->map(function ($q) use ($allpart) {
                    $q['rprice'] = SalePricing::where('part_id', $q->part_id)
                        ->where('source_id', $q->source_id)
                        ->where('status_id', $q->status_id)
                        ->where('quality_id', $q->quality_id)
                        ->where('type_id', '6')
                        ->where('to', null)
                        ->with('sale_type')
                        ->orderBy('price', 'DESC')
                        ->get();
                    $q['stock'] = $this->PartInStoresCount($q->part_id, $q->source_id, $q->status_id, $q->quality_id, 6);
                });
            } else {
                $allpart['kiit'] = Kit::where('id', $partId)
                    ->with([
                        'sub_group' => function ($q) {
                            $q->with('group')->get();
                        },
                    ])
                    ->with([
                        'kit_details' => function ($q) {
                            $q->with('kit_specs')->with('mesure_unit')->get();
                        },
                    ])
                    ->with([
                        'kit_numbers' => function ($q) {
                            $q->with('supplier')->get();
                        },
                    ])
                    ->with('kit_images')
                    ->with([
                        'kit_models' => function ($q) {
                            $q->with([
                                'series' => function ($q1) {
                                    $q1->with([
                                        'model' => function ($q2) {
                                            $q2->with('brand')->with('brand_type')->get();
                                        },
                                    ])->get();
                                },
                            ])->get();
                            // }])
                            // ->with(['related_parts' => function ($q) {
                            //     $q->with(['part' => function ($q1) {
                            //         $q1->with('part_images')->get();
                            //     }])
                            // ->get();
                        },
                    ])
                    ->with([
                        'all_kits' => function ($q) {
                            $q->with('status')->with('source')->with('part_quality')->get();
                        },
                    ])
                    ->first();
                // return $allpart;
                $allpart['price'] = [];
                $allpart['stores'] = [];
                $allpart['price'] = [];
                $allpart['part']['all_parts'] = [];
                $allpart['part']['status'] = [];
                $allpart['part']['source'] = [];
                $allpart['part']['part_quality'] = [];
            }
        }
        $partData['allpart'] = $allpart;
        return $partData;
    }

    public function wheelCardInfo(Request $request)
    {
        $partData = [];
        $allpartId = $request->allpartId;
        $partId = $request->partId;
        $allpart = '';

        if ($allpartId) {
            $allpart = AllWheel::where('id', $allpartId)
                ->with([
                    'wheel' => function ($m) {
                        // $m->with(['sub_group' => function ($q) {
                        //     $q->with('group')->get();
                        // }]);
                        // $m->sub_group=[];
                        $m->with([
                            'wheel_details' => function ($q) {
                                $q->with('wheel_spec')->with('mesure_unit')->get();
                            },
                        ]);
                        // $m->with(['kit_numbers' => function ($q) {
                        //     $q->with('supplier')->get();
                        // }]);
                        $m->with('wheel_images');
                        // $m->with(['wheel_model' => function ($q) {
                        //     $q->with(['series' => function ($q1) {
                        //         $q1->with(['model' => function ($q2) {
                        //             $q2->with('brand')->with('brand_type')->get();
                        //         }])->get();
                        //     }])->get();
                        // }]);

                        $m->with(['related_wheels' => function ($q) {
                            $q->with(['wheel' => function ($q1) {
                                $q1->with('wheel_images')->get();
                            }])->get();
                        }]);
                        $m->with([
                            'all_wheels' => function ($q) {
                                $q->with('status')->with('source')->with('part_quality')->get();
                            },
                        ]);
                    },
                ])
                ->with('status')
                ->with('source')
                ->with('part_quality')
                ->get();

            $allpart[0]['price'] = SalePricing::where('part_id', $allpart[0]->part_id)
                ->where('source_id', $allpart[0]->source_id)
                ->where('status_id', $allpart[0]->status_id)
                ->where('quality_id', $allpart[0]->quality_id)
                ->where('type_id', '2')
                ->where('to', null)
                ->with('sale_type')
                ->orderBy('price', 'DESC')
                ->get();

            $allpart[0]['stores'] = $this->PartInStoresCount($allpart[0]->part_id, $allpart[0]->source_id, $allpart[0]->status_id, $allpart[0]->quality_id, 2);
            // return $allpart;
            $allpart[0]->wheel->all_wheels->map(function ($q) use ($allpart) {
                $q['rprice'] = SalePricing::where('part_id', $q->part_id)
                    ->where('source_id', $q->source_id)
                    ->where('status_id', $q->status_id)
                    ->where('quality_id', $q->quality_id)
                    ->where('type_id', '2')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get();
                $q['stock'] = $this->PartInStoresCount($q->part_id, $q->source_id, $q->status_id, $q->quality_id, 2);
            });
        } else {
            $allpart = AllWheel::where('part_id', $partId)
                ->with([
                    'wheel' => function ($m) {
                        // $m->with(['sub_group' => function ($q) {
                        //     $q->with('group')->get();
                        // }]);
                        $m->with([
                            'wheel_details' => function ($q) {
                                $q->with('wheel_spec')->with('mesure_unit')->get();
                            },
                        ]);
                        // $m->with(['kit_numbers' => function ($q) {
                        //     $q->with('supplier')->get();
                        // }]);
                        $m->with('wheel_images');
                        // $m->with(['wheel_model' => function ($q) {
                        //     $q->with(['series' => function ($q1) {
                        //         $q1->with(['model' => function ($q2) {
                        //             $q2->with('brand')->with('brand_type')->get();
                        //         }])->get();
                        //     }])->get();
                        // }]);

                        // $m->with(['related_parts' => function ($q) {
                        //     $q->with(['part' => function ($q1) {
                        //         $q1->with('part_images')->get();
                        //     }])->get();
                        // }]);
                        $m->with([
                            'all_wheels' => function ($q) {
                                $q->with('status')->with('source')->with('part_quality')->get();
                            },
                        ]);
                    },
                ])
                ->with('status')
                ->with('source')
                ->with('part_quality')
                ->first();

            if ($allpart) {
                $allpart['price'] = SalePricing::where('part_id', $allpart->part_id)
                    ->where('source_id', $allpart->source_id)
                    ->where('status_id', $allpart->status_id)
                    ->where('quality_id', $allpart->quality_id)
                    ->where('type_id', '2')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get();

                $allpart['stores'] = $this->PartInStoresCount($allpart->part_id, $allpart->source_id, $allpart->status_id, $allpart->quality_id, 2);

                $allpart->wheel->all_wheels->map(function ($q) use ($allpart) {
                    $q['rprice'] = SalePricing::where('part_id', $q->part_id)
                        ->where('source_id', $q->source_id)
                        ->where('status_id', $q->status_id)
                        ->where('quality_id', $q->quality_id)
                        ->where('type_id', '2')
                        ->where('to', null)
                        ->with('sale_type')
                        ->orderBy('price', 'DESC')
                        ->get();
                    $q['stock'] = $this->PartInStoresCount($q->part_id, $q->source_id, $q->status_id, $q->quality_id, 2);
                });
            } else {
                $allpart['wheel'] = Wheel::where('id', $partId)
                    ->with([
                        'sub_group' => function ($q) {
                            $q->with('group')->get();
                        },
                    ])
                    ->with([
                        'wheel_details' => function ($q) {
                            $q->with('wheel_spec')->with('mesure_unit')->get();
                        },
                    ])
                    // with(['kit_numbers' => function ($q) {
                    //     $q->with('supplier')->get();
                    // }])->
                    // with('wheel_images')->with(['wheel_model' => function ($q) {
                    //     $q->with(['series' => function ($q1) {
                    //         $q1->with(['model' => function ($q2) {
                    //             $q2->with('brand')->with('brand_type')->get();
                    //         }])->get();
                    //     }])->get();
                    // }])
                    // ->with(['related_parts' => function ($q) {
                    //     $q->with(['part' => function ($q1) {
                    //         $q1->with('part_images')->get();
                    //     }])
                    // ->get();
                    // }])
                    // ->
                    ->with([
                        'all_wheels' => function ($q) {
                            $q->with('status')->with('source')->with('part_quality')->get();
                        },
                    ])
                    ->first();
                // return $allpart;
                $allpart['price'] = [];
                $allpart['stores'] = [];
                $allpart['price'] = [];
                $allpart['part']['all_parts'] = [];
                $allpart['part']['status'] = [];
                $allpart['part']['source'] = [];
                $allpart['part']['part_quality'] = [];
            }
        }
        $partData['allpart'] = $allpart;
        return $partData;
    }

    public function tractorCardInfo(Request $request)
    {
        $partData = [];
        $allpartId = $request->allpartId;
        $partId = $request->partId;
        $allpart = '';

        if ($allpartId) {
            $allpart = AllTractor::where('id', $allpartId)
                ->with([
                    'tractor' => function ($m) {
                        // $m->with(['sub_group' => function ($q) {
                        //     $q->with('group')->get();
                        // }]);
                        $m->sub_group = [];
                        $m->with([
                            'tractor_details' => function ($q) {
                                $q->with('tractor_spec')->with('mesure_unit')->get();
                            },
                        ]);
                        // $m->with(['kit_numbers' => function ($q) {
                        //     $q->with('supplier')->get();
                        // }]);
                        $m->with('tractor_images');
                        $m->with('efrag_images');
                        $m->with('series.model');
                        // $m->with(['wheel_model' => function ($q) {
                        //     $q->with(['series' => function ($q1) {
                        //         $q1->with(['model' => function ($q2) {
                        //             $q2->with('brand')->with('brand_type')->get();
                        //         }])->get();
                        //     }])->get();
                        // }]);

                        $m->with(['related_tractors' => function ($q) {
                            $q->with(['part' => function ($q1) {
                                $q1->with('part_images')->get();
                            }])->get();
                        }]);
                        $m->with([
                            'all_tractors' => function ($q) {
                                $q->with('status')->with('source')->with('part_quality')->get();
                            },
                        ]);
                    },
                ])
                ->with('status')
                ->with('source')
                ->with('part_quality')
                ->get();

            $allpart[0]['price'] = SalePricing::where('part_id', $allpart[0]->part_id)
                ->where('source_id', $allpart[0]->source_id)
                ->where('status_id', $allpart[0]->status_id)
                ->where('quality_id', $allpart[0]->quality_id)
                ->where('type_id', '3')
                ->where('to', null)
                ->with('sale_type')
                ->orderBy('price', 'DESC')
                ->get();

            $allpart[0]['stores'] = $this->PartInStoresCount($allpart[0]->part_id, $allpart[0]->source_id, $allpart[0]->status_id, $allpart[0]->quality_id, 3);
            // return $allpart;
            $allpart[0]->tractor->all_tractors->map(function ($q) use ($allpart) {
                $q['rprice'] = SalePricing::where('part_id', $q->part_id)
                    ->where('source_id', $q->source_id)
                    ->where('status_id', $q->status_id)
                    ->where('quality_id', $q->quality_id)
                    ->where('type_id', '3')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get();
                $q['stock'] = $this->PartInStoresCount($q->part_id, $q->source_id, $q->status_id, $q->quality_id, 3);
            });
        } else {
            $allpart = AllTractor::where('part_id', $partId)
                ->with([
                    'tractor' => function ($m) {
                        // $m->with(['sub_group' => function ($q) {
                        //     $q->with('group')->get();
                        // }]);
                        $m->with([
                            'tractor_details' => function ($q) {
                                $q->with('tractor_spec')->with('mesure_unit')->get();
                            },
                        ]);
                        // $m->with(['kit_numbers' => function ($q) {
                        //     $q->with('supplier')->get();
                        // }]);
                        $m->with('tractor_images');
                        $m->with('efrag_images');
                        // $m->with(['wheel_model' => function ($q) {
                        //     $q->with(['series' => function ($q1) {
                        //         $q1->with(['model' => function ($q2) {
                        //             $q2->with('brand')->with('brand_type')->get();
                        //         }])->get();
                        //     }])->get();
                        // }]);

                        // $m->with(['related_parts' => function ($q) {
                        //     $q->with(['part' => function ($q1) {
                        //         $q1->with('part_images')->get();
                        //     }])->get();
                        // }]);
                        $m->with([
                            'all_tractors' => function ($q) {
                                $q->with('status')->with('source')->with('part_quality')->get();
                            },
                        ]);
                    },
                ])
                ->with('status')
                ->with('source')
                ->with('part_quality')
                ->first();

            if ($allpart) {
                $allpart['price'] = SalePricing::where('part_id', $allpart->part_id)
                    ->where('source_id', $allpart->source_id)
                    ->where('status_id', $allpart->status_id)
                    ->where('quality_id', $allpart->quality_id)
                    ->where('type_id', '3')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get();

                $allpart['stores'] = $this->PartInStoresCount($allpart->part_id, $allpart->source_id, $allpart->status_id, $allpart->quality_id, 3);

                $allpart->tractor->all_tractors->map(function ($q) use ($allpart) {
                    $q['rprice'] = SalePricing::where('part_id', $q->part_id)
                        ->where('source_id', $q->source_id)
                        ->where('status_id', $q->status_id)
                        ->where('quality_id', $q->quality_id)
                        ->where('type_id', '3')
                        ->where('to', null)
                        ->with('sale_type')
                        ->orderBy('price', 'DESC')
                        ->get();
                    $q['stock'] = $this->PartInStoresCount($q->part_id, $q->source_id, $q->status_id, $q->quality_id, 3);
                });
            } else {
                $allpart['tractor'] = Tractor::where('id', $partId)
                    ->with([
                        'tractor_details' => function ($q) {
                            $q->with('tractor_spec')->with('mesure_unit')->get();
                        },
                    ])
                    // with(['kit_numbers' => function ($q) {
                    //     $q->with('supplier')->get();
                    // }])->
                    // with('wheel_images')->with(['wheel_model' => function ($q) {
                    //     $q->with(['series' => function ($q1) {
                    //         $q1->with(['model' => function ($q2) {
                    //             $q2->with('brand')->with('brand_type')->get();
                    //         }])->get();
                    //     }])->get();
                    // }])
                    // ->with(['related_parts' => function ($q) {
                    //     $q->with(['part' => function ($q1) {
                    //         $q1->with('part_images')->get();
                    //     }])
                    // ->get();
                    // }])
                    // ->
                    ->with([
                        'all_tractors' => function ($q) {
                            $q->with('status')->with('source')->with('part_quality')->get();
                        },
                    ])
                    ->first();
                // return $allpart;
                $allpart['price'] = [];
                $allpart['stores'] = [];
                $allpart['price'] = [];
                $allpart['part']['all_parts'] = [];
                $allpart['part']['status'] = [];
                $allpart['part']['source'] = [];
                $allpart['part']['part_quality'] = [];
            }
        }
        $partData['allpart'] = $allpart;
        return $partData;
    }
    public function clarkCardInfo(Request $request)
    {
        $partData = [];
        $allpartId = $request->allpartId;
        $partId = $request->partId;
        $allpart = '';

        if ($allpartId) {
            $allpart = AllClark::where('id', $allpartId)
                ->with([
                    'clark' => function ($m) {
                        // $m->with(['sub_group' => function ($q) {
                        //     $q->with('group')->get();
                        // }]);
                        $m->sub_group = [];
                        $m->with([
                            'clark_details' => function ($q) {
                                $q->with('clark_spec')->with('mesure_unit')->get();
                            },
                        ]);
                        // $m->with(['kit_numbers' => function ($q) {
                        //     $q->with('supplier')->get();
                        // }]);
                        $m->with('clark_images');
                        $m->with('clark_efrags');
                        $m->with('series.model');
                        // $m->with(['wheel_model' => function ($q) {
                        //     $q->with(['series' => function ($q1) {
                        //         $q1->with(['model' => function ($q2) {
                        //             $q2->with('brand')->with('brand_type')->get();
                        //         }])->get();
                        //     }])->get();
                        // }]);

                        $m->with(['related_clarks' => function ($q) {
                            $q->with(['part' => function ($q1) {
                                $q1->with('part_images')->get();
                            }])->get();
                        }]);
                        $m->with([
                            'all_clarks' => function ($q) {
                                $q->with('status')->with('source')->with('part_quality')->get();
                            },
                        ]);
                    },
                ])
                ->with('status')
                ->with('source')
                ->with('part_quality')
                ->get();

            $allpart[0]['price'] = SalePricing::where('part_id', $allpart[0]->part_id)
                ->where('source_id', $allpart[0]->source_id)
                ->where('status_id', $allpart[0]->status_id)
                ->where('quality_id', $allpart[0]->quality_id)
                ->where('type_id', '4')
                ->where('to', null)
                ->with('sale_type')
                ->orderBy('price', 'DESC')
                ->get();

            $allpart[0]['stores'] = $this->PartInStoresCount($allpart[0]->part_id, $allpart[0]->source_id, $allpart[0]->status_id, $allpart[0]->quality_id, 4);
            // return $allpart;
            $allpart[0]->clark->all_clarks->map(function ($q) use ($allpart) {
                $q['rprice'] = SalePricing::where('part_id', $q->part_id)
                    ->where('source_id', $q->source_id)
                    ->where('status_id', $q->status_id)
                    ->where('quality_id', $q->quality_id)
                    ->where('type_id', '4')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get();
                $q['stock'] = $this->PartInStoresCount($q->part_id, $q->source_id, $q->status_id, $q->quality_id, 4);
            });
        } else {
            $allpart = AllClark::where('part_id', $partId)
                ->with([
                    'clark' => function ($m) {
                        // $m->with(['sub_group' => function ($q) {
                        //     $q->with('group')->get();
                        // }]);
                        $m->with([
                            'clark_details' => function ($q) {
                                $q->with('clark_spec')->with('mesure_unit')->get();
                            },
                        ]);
                        // $m->with(['kit_numbers' => function ($q) {
                        //     $q->with('supplier')->get();
                        // }]);
                        $m->with('clark_images');
                        $m->with('clark_efrags');
                        // $m->with(['wheel_model' => function ($q) {
                        //     $q->with(['series' => function ($q1) {
                        //         $q1->with(['model' => function ($q2) {
                        //             $q2->with('brand')->with('brand_type')->get();
                        //         }])->get();
                        //     }])->get();
                        // }]);

                        $m->with(['related_clarks' => function ($q) {
                            $q->with(['part' => function ($q1) {
                                $q1->with('part_images')->get();
                            }])->get();
                        }]);
                        $m->with([
                            'all_clarks' => function ($q) {
                                $q->with('status')->with('source')->with('part_quality')->get();
                            },
                        ]);
                    },
                ])
                ->with('status')
                ->with('source')
                ->with('part_quality')
                ->first();

            if ($allpart) {
                $allpart['price'] = SalePricing::where('part_id', $allpart->part_id)
                    ->where('source_id', $allpart->source_id)
                    ->where('status_id', $allpart->status_id)
                    ->where('quality_id', $allpart->quality_id)
                    ->where('type_id', '4')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get();

                $allpart['stores'] = $this->PartInStoresCount($allpart->part_id, $allpart->source_id, $allpart->status_id, $allpart->quality_id, 4);

                $allpart->clark->all_clarks->map(function ($q) use ($allpart) {
                    $q['rprice'] = SalePricing::where('part_id', $q->part_id)
                        ->where('source_id', $q->source_id)
                        ->where('status_id', $q->status_id)
                        ->where('quality_id', $q->quality_id)
                        ->where('type_id', '4')
                        ->where('to', null)
                        ->with('sale_type')
                        ->orderBy('price', 'DESC')
                        ->get();
                    $q['stock'] = $this->PartInStoresCount($q->part_id, $q->source_id, $q->status_id, $q->quality_id, 4);
                });
            } else {
                $allpart['clark'] = Clark::where('id', $partId)
                    ->with([
                        'clark_details' => function ($q) {
                            $q->with('clark_spec')->with('mesure_unit')->get();
                        },
                    ])
                    // with(['kit_numbers' => function ($q) {
                    //     $q->with('supplier')->get();
                    // }])->
                    // with('wheel_images')->with(['wheel_model' => function ($q) {
                    //     $q->with(['series' => function ($q1) {
                    //         $q1->with(['model' => function ($q2) {
                    //             $q2->with('brand')->with('brand_type')->get();
                    //         }])->get();
                    //     }])->get();
                    // }])
                    // ->with(['related_parts' => function ($q) {
                    //     $q->with(['part' => function ($q1) {
                    //         $q1->with('part_images')->get();
                    //     }])
                    // ->get();
                    // }])
                    // ->
                    ->with([
                        'all_clarks' => function ($q) {
                            $q->with('status')->with('source')->with('part_quality')->get();
                        },
                    ])
                    ->first();
                // return $allpart;
                $allpart['price'] = [];
                $allpart['stores'] = [];
                $allpart['price'] = [];
                $allpart['part']['all_parts'] = [];
                $allpart['part']['status'] = [];
                $allpart['part']['source'] = [];
                $allpart['part']['part_quality'] = [];
            }
        }
        $partData['allpart'] = $allpart;
        return $partData;
    }
    public function equipCardInfo(Request $request)
    {
        $partData = [];
        $allpartId = $request->allpartId;
        $partId = $request->partId;
        $allpart = '';

        if ($allpartId) {
            $allpart = AllEquip::where('id', $allpartId)
                ->with([
                    'equip' => function ($m) {
                        // $m->with(['sub_group' => function ($q) {
                        //     $q->with('group')->get();
                        // }]);
                        $m->sub_group = [];
                        $m->with([
                            'equip_details' => function ($q) {
                                $q->with('equip_spec')->with('mesure_unit')->get();
                            },
                        ]);
                        // $m->with(['kit_numbers' => function ($q) {
                        //     $q->with('supplier')->get();
                        // }]);
                        $m->with('equip_images');
                        $m->with('series.model');
                        // $m->with(['wheel_model' => function ($q) {
                        //     $q->with(['series' => function ($q1) {
                        //         $q1->with(['model' => function ($q2) {
                        //             $q2->with('brand')->with('brand_type')->get();
                        //         }])->get();
                        //     }])->get();
                        // }]);

                        $m->with(['related_equips' => function ($q) {
                            $q->with(['part' => function ($q1) {
                                $q1->with('part_images')->get();
                            }])->get();
                        }]);
                        $m->with([
                            'all_equips' => function ($q) {
                                $q->with('status')->with('source')->with('part_quality')->get();
                            },
                        ]);
                    },
                ])
                ->with('status')
                ->with('source')
                ->with('part_quality')
                ->get();

            $allpart[0]['price'] = SalePricing::where('part_id', $allpart[0]->part_id)
                ->where('source_id', $allpart[0]->source_id)
                ->where('status_id', $allpart[0]->status_id)
                ->where('quality_id', $allpart[0]->quality_id)
                ->where('type_id', '5')
                ->where('to', null)
                ->with('sale_type')
                ->orderBy('price', 'DESC')
                ->get();

            $allpart[0]['stores'] = $this->PartInStoresCount($allpart[0]->part_id, $allpart[0]->source_id, $allpart[0]->status_id, $allpart[0]->quality_id, 5);
            // return $allpart;
            $allpart[0]->equip->all_equips->map(function ($q) use ($allpart) {
                $q['rprice'] = SalePricing::where('part_id', $q->part_id)
                    ->where('source_id', $q->source_id)
                    ->where('status_id', $q->status_id)
                    ->where('quality_id', $q->quality_id)
                    ->where('type_id', '5')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get();
                $q['stock'] = $this->PartInStoresCount($q->part_id, $q->source_id, $q->status_id, $q->quality_id, 5);
            });
        } else {
            $allpart = AllEquip::where('part_id', $partId)
                ->with([
                    'clark' => function ($m) {
                        // $m->with(['sub_group' => function ($q) {
                        //     $q->with('group')->get();
                        // }]);
                        $m->with([
                            'equip_details' => function ($q) {
                                $q->with('equip_spec')->with('mesure_unit')->get();
                            },
                        ]);
                        // $m->with(['kit_numbers' => function ($q) {
                        //     $q->with('supplier')->get();
                        // }]);
                        $m->with('equip_images');
                        $m->with('series.model');
                        // $m->with(['wheel_model' => function ($q) {
                        //     $q->with(['series' => function ($q1) {
                        //         $q1->with(['model' => function ($q2) {
                        //             $q2->with('brand')->with('brand_type')->get();
                        //         }])->get();
                        //     }])->get();
                        // }]);

                        $m->with(['related_equips' => function ($q) {
                            $q->with(['part' => function ($q1) {
                                $q1->with('part_images')->get();
                            }])->get();
                        }]);
                        $m->with([
                            'all_equips' => function ($q) {
                                $q->with('status')->with('source')->with('part_quality')->get();
                            },
                        ]);
                    },
                ])
                ->with('status')
                ->with('source')
                ->with('part_quality')
                ->first();

            if ($allpart) {
                $allpart['price'] = SalePricing::where('part_id', $allpart->part_id)
                    ->where('source_id', $allpart->source_id)
                    ->where('status_id', $allpart->status_id)
                    ->where('quality_id', $allpart->quality_id)
                    ->where('type_id', '5')
                    ->where('to', null)
                    ->with('sale_type')
                    ->orderBy('price', 'DESC')
                    ->get();

                $allpart['stores'] = $this->PartInStoresCount($allpart->part_id, $allpart->source_id, $allpart->status_id, $allpart->quality_id, 5);

                $allpart->equip->all_equips->map(function ($q) use ($allpart) {
                    $q['rprice'] = SalePricing::where('part_id', $q->part_id)
                        ->where('source_id', $q->source_id)
                        ->where('status_id', $q->status_id)
                        ->where('quality_id', $q->quality_id)
                        ->where('type_id', '5')
                        ->where('to', null)
                        ->with('sale_type')
                        ->orderBy('price', 'DESC')
                        ->get();
                    $q['stock'] = $this->PartInStoresCount($q->part_id, $q->source_id, $q->status_id, $q->quality_id, 5);
                });
            } else {
                $allpart['equip'] = Equip::where('id', $partId)
                    ->with([
                        'equip_details' => function ($q) {
                            $q->with('equip_spec')->with('mesure_unit')->get();
                        },
                    ])
                    // with(['kit_numbers' => function ($q) {
                    //     $q->with('supplier')->get();
                    // }])->
                    // with('wheel_images')->with(['wheel_model' => function ($q) {
                    //     $q->with(['series' => function ($q1) {
                    //         $q1->with(['model' => function ($q2) {
                    //             $q2->with('brand')->with('brand_type')->get();
                    //         }])->get();
                    //     }])->get();
                    // }])
                    // ->with(['related_parts' => function ($q) {
                    //     $q->with(['part' => function ($q1) {
                    //         $q1->with('part_images')->get();
                    //     }])
                    // ->get();
                    // }])
                    // ->
                    ->with([
                        'all_equips' => function ($q) {
                            $q->with('status')->with('source')->with('part_quality')->get();
                        },
                    ])
                    ->first();
                // return $allpart;
                $allpart['price'] = [];
                $allpart['stores'] = [];
                $allpart['price'] = [];
                $allpart['part']['all_parts'] = [];
                $allpart['part']['status'] = [];
                $allpart['part']['source'] = [];
                $allpart['part']['part_quality'] = [];
            }
        }
        $partData['allpart'] = $allpart;
        return $partData;
    }

    public function refund($invId,$storeId)
    {
        $store_data = Store::where('id', $storeId)->get();

        $invoice = Invoice::where('id', $invId)->first();
        $totalRefundPrices = RefundInvoicePayment::where('invoice_id', $invId)->sum('paied');
        $invoiceItemspart = InvoiceItem::where('invoice_id', $invoice->id)
            ->where('part_type_id', '1')
            ->with('part')
            ->with('source')
            ->with('status')
            ->with('part_quality')
            ->get();
        $invoiceItemskit = InvoiceItem::where('invoice_id', $invoice->id)
            ->where('part_type_id', '6')
            ->with('kit')
            ->with('source')
            ->with('status')
            ->with('part_quality')
            ->get();
        $invoiceItemswheel = InvoiceItem::where('invoice_id', $invoice->id)
            ->where('part_type_id', '2')
            ->with('wheel')
            ->with('source')
            ->with('status')
            ->with('part_quality')
            ->get();
        $invoiceItems = $invoiceItemspart->concat($invoiceItemskit)->concat($invoiceItemswheel);
        // return $invoiceItems;
        $refund_price = 0;
        $refund_total_tax = 0;
        $refund_total_discount = 0;
        foreach ($invoiceItems as  $Item) {


            if($invoice->presale_order_id){
                $Item['price'] = PresaleOrderPart::where('part_type_id', $Item->part_type_id)->where('presaleOrder_id', $invoice->presale_order_id)->where('part_id', $Item->part_id)->where('source_id', $Item->source_id)->where('status_id', $Item->status_id)->where('quality_id', $Item->quality_id)->get();
            }else{
                // $Item['price'] = SalePricing::where('from', '<=', $Item->date)
                $Item['price'] = SalePricing::where(function ($q) use ($Item) {
                    $q->where('to', '>=', $Item->date)->orWhere('to', null);
                })
                ->where('sale_type', $Item->sale_type)
                ->where('part_id', $Item->part_id)
                ->where('source_id', $Item->source_id)
                ->where('status_id', $Item->status_id)
                ->where('quality_id', $Item->quality_id)
                ->with('sale_type')
                ->get();
            }

            $allpId = 0;

            if ($Item->part_type_id == 1) {
                $allpart = AllPart::where('part_id', $Item->part_id)
                    ->where('source_id', $Item->source_id)
                    ->where('status_id', $Item->status_id)
                    ->where('quality_id', $Item->quality_id)
                    ->orderBy('id', 'Asc')
                    ->select('id')
                    ->get();
                $allpId = $allpart;
            } elseif ($Item->part_type_id == 2) {
                $allpart = AllWheel::where('part_id', $Item->part_id)
                    ->where('source_id', $Item->source_id)
                    ->where('status_id', $Item->status_id)
                    ->where('quality_id', $Item->quality_id)
                    ->orderBy('id', 'Asc')
                    ->select('id')
                    ->get();
                $allpId = $allpart;
            } elseif ($Item->part_type_id == 6) {
                $allpart = AllKit::where('part_id', $Item->part_id)
                    ->where('source_id', $Item->source_id)
                    ->where('status_id', $Item->status_id)
                    ->where('quality_id', $Item->quality_id)
                    ->orderBy('id', 'Asc')
                    ->select('id')
                    ->get();
                $allpId = $allpart;
            } elseif ($Item->part_type_id == 3) {
                $allpart = AllTractor::where('part_id', $Item->part_id)
                    ->where('source_id', $Item->source_id)
                    ->where('status_id', $Item->status_id)
                    ->where('quality_id', $Item->quality_id)
                    ->orderBy('id', 'Asc')
                    ->select('id')
                    ->get();
                $allpId = $allpart;
            } elseif ($Item->part_type_id == 4) {
                $allpart = AllClark::where('part_id', $Item->part_id)
                    ->where('source_id', $Item->source_id)
                    ->where('status_id', $Item->status_id)
                    ->where('quality_id', $Item->quality_id)
                    ->orderBy('id', 'Asc')
                    ->select('id')
                    ->get();
                $allpId = $allpart;
            } elseif ($Item->part_type_id == 5) {
                $allpart = AllEquip::where('part_id', $Item->part_id)
                    ->where('source_id', $Item->source_id)
                    ->where('status_id', $Item->status_id)
                    ->where('quality_id', $Item->quality_id)
                    ->orderBy('id', 'Asc')
                    ->select('id')
                    ->get();
                $allpId = $allpart;
            }

            // return $allpId;
            $Item['refund_amount'] = RefundInvoice::where('invoice_id', $invoice->id)
                ->where('item_id', $Item->id)
                ->sum('r_amount');
            $Item['refund_price'] = RefundInvoice::where('invoice_id', $invoice->id)
                ->where('item_id',$Item->id)
                ->sum('item_price');
            $Item['refund_total_tax'] = RefundInvoice::where('invoice_id', $invoice->id)
                ->where('item_id', $Item->id)
                ->sum('r_tax');

            $Item['refund_total_discount'] = RefundInvoice::where('invoice_id', $invoice->id)
                ->where('item_id', $Item->id)
                ->sum('r_discount');

            // $refund_price += RefundInvoice::where('invoice_id', $invoice->id)
            //     ->where('item_id', $allpId)
            //     ->sum('item_price');
              $refund_price += RefundInvoice::where('invoice_id', $invoice->id)
                ->where('item_id', $Item->id)
                ->sum(DB::raw('item_price * r_amount'));
            $refund_total_tax += RefundInvoice::where('invoice_id', $invoice->id)
                ->where('item_id', $Item->id)
                ->sum('r_tax');

            $refund_total_discount += RefundInvoice::where('invoice_id', $invoice->id)
                ->where('item_id', $Item->id)
                ->sum('r_discount');
            $Item['section'] = DB::table('store_section')
                ->join('store_structure', 'store_structure.id', 'store_section.section_id')
                ->where('part_id', $Item->part_id)
                ->where('type_id', $Item->part_type_id)
                ->where('source_id', $Item->source_id)
                ->where('status_id', $Item->status_id)
                ->where('quality_id', $Item->quality_id)
                ->select('store_structure.name')
                ->get();
        }
        // return $invoiceItems;
        $client = $invoice->client;
        $store = $invoice->store;
        $taxes = $invoice->taxes;

        $bank_types = BankType::all();
        $store_safe = Store::where('safe_accountant_number', '<>', '0')->get();

        return view('refundinvoice', compact('store_data','totalRefundPrices', 'bank_types', 'store_safe', 'invoice', 'invoiceItems', 'client', 'store', 'taxes', 'refund_price', 'refund_total_tax', 'refund_total_discount'));
    }

    public function saveRefund(Request $request)
    {
        // return $request;
        DB::beginTransaction();
         $logMessage='';
        $logUser = Auth::user()->id;
        
        $cli_id = $request->clientId;
        $nqayd = new NQayd();
        $nqayd->partner_id=$request->clientId;
        $nqayd->type='Client';
        $nqayd->name='RINV/'.date('Y').'/'.date('m');
        $nqayd->cost_center='';
        $nqayd->amount_currency=null;
        $nqayd->currency_id=null;
        $nqayd->credit= 0;
        $nqayd->debit=0;
        $nqayd->desc='فاتورة مرتجع بيع رقم '.$request->invoiceId.' . ';
        $nqayd->user_id=$logUser;
        $nqayd->flag='0';
        $nqayd->invoice_id=$request->invoiceId;
        $nqayd->invoice_table='printInvoice';
        $nqayd->save();
        
        $total_taxes1 = array_sum($request->itemTaxes);
        $total_discount1 = array_sum($request->itemDiscount);

        try {
            $invoiceId = $request->invoiceId;
            $newTax = $request->newTax;
            $store = Store::where('id', $request->storeId)->get();
            $store_table_name = $store[0]->table_name;
            $total_item_buy_price = 0;
            
            if (isset($request->taxesList)) {
                $safi = $request->totalRef - $request->newTax;
                foreach ($request->taxesList as $key => $tax) {
                    if ($tax == '14') {
                        $taxacx = ($safi * 14) / 100; 
                        
                        $newqayd = new Newqayd();
                        $newqayd->refrence='RINV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                        $newqayd->coa_id=83;
                        $newqayd->journal_id=9;
                        $newqayd->partner_id=$cli_id;
                        $newqayd->type='Client';
                        $newqayd->label=$tax.'% Vat';
                        $newqayd->cost_center='';
                        $newqayd->amount_currency=null;
                        $newqayd->currency_id=null;
                        $newqayd->credit=0;
                        $newqayd->debit=$taxacx;
                        $newqayd->desc='';
                        $nqayd->user_id=$logUser;
                        $newqayd->flag='0';
                        $newqayd->invoice_id=$invoiceId;
                        $newqayd->invoice_table='printInvoice';
                        $newqayd->qayds_id=$nqayd->id;
                        $newqayd->show_no='1';
                        
                        $newqayd->save();
                        
    
                    } elseif ($tax == '-1') {
                        $taxacx = ($safi * -1) / 100; 
                        $newqayd = new Newqayd();
                        $newqayd->refrence='INV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                        $newqayd->coa_id=15;
                        $newqayd->journal_id=9;
                        $newqayd->partner_id=$cli_id;
                        $newqayd->type='Client';
                        $newqayd->label=$tax.'% WH';
                        $newqayd->cost_center='';
                        $newqayd->amount_currency=null;
                        $newqayd->currency_id=null;
                        $newqayd->credit= $taxacx * -1;
                        $newqayd->debit=0;
                        $newqayd->desc='';
                        $nqayd->user_id=$logUser;
                        $newqayd->flag='0';
                        $newqayd->invoice_id=$invoiceId;
                        $newqayd->invoice_table='printInvoice';
                        $newqayd->qayds_id=$nqayd->id;
                        $newqayd->show_no='1';
                        $newqayd->save();
                        
                    } elseif ($tax == '5') {
                        $taxacx = ($safi * 5) / 100; 
                        $newqayd = new Newqayd();
                        $newqayd->refrence='INV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                        $newqayd->coa_id=15;
                        $newqayd->journal_id=9;
                        $newqayd->partner_id=$cli_id;
                        $newqayd->type='Client';
                        $newqayd->label=$tax.'% Vat';
                        $newqayd->cost_center='';
                        $newqayd->amount_currency=null;
                        $newqayd->currency_id=null;
                        $newqayd->credit=0;
                        $newqayd->debit=$taxacx;
                        $newqayd->desc='';
                        $nqayd->user_id=$logUser;
                        $newqayd->flag='0';
                        $newqayd->invoice_id=$invoiceId;
                        $newqayd->invoice_table='printInvoice';
                        $newqayd->qayds_id=$nqayd->id;
                        $newqayd->show_no='1';
                        $newqayd->save();
    
                    }else{
                        $taxacx = ($safi * intval($tax)) / 100; 
                        $newqayd = new Newqayd();
                        $newqayd->refrence='INV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                        $newqayd->coa_id=15;
                        $newqayd->journal_id=9;
                        $newqayd->partner_id=$cli_id;
                        $newqayd->type='Client';
                        $newqayd->label=$tax.'% Vat';
                        $newqayd->cost_center='';
                        $newqayd->amount_currency=null;
                        $newqayd->currency_id=null;
                        $newqayd->credit=0;
                        $newqayd->debit=$taxacx;
                        $newqayd->desc='';
                        $nqayd->user_id=$logUser;
                        $newqayd->flag='0';
                        $newqayd->invoice_id=$invoiceId;
                        $newqayd->invoice_table='printInvoice';
                        $newqayd->qayds_id=$nqayd->id;
                        $newqayd->show_no='1';
                        // $newqayd->save();
    
                    }
                    
                }
            }
            
            foreach ($request->refunditem as $key => $value) {
                $allpartId = 0;
                $partId = $value;
                $sourceid = $request->refunditemSource[$key];
                $statusid = $request->refunditemStatus[$key];
                $qualityid = $request->refunditemquality[$key];

                
                $samllmeasureUnits = $request->small_unit[$key ];
                $measureUnit = $request->big_unit[$key];
                $ratiounit = getSmallUnit($measureUnit, $samllmeasureUnits);
                $refundAmount = $request->refundAmount[$key] * $ratiounit;

                $itemDiscount = $request->itemDiscount[$key];
                $itemTaxes = $request->itemTaxes[$key];


                $refunditemSalePrice = $request->refunditemSalePrice[$key];
                $osid = 0;
                if ($refundAmount > 0) {
                    $orderSupplierArr = [];
                    $newItemCoastPrice = 0 ;

                    $newqayd = new Newqayd();
                    $newqayd->refrence='RINV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                    $newqayd->coa_id=170;
                    $newqayd->journal_id=9;
                    $newqayd->partner_id=$request->clientId;
                    $newqayd->type='Client';
                    
                    $newqayd->label='Stock/OUT/'.date('Y').'/'.date('m').'/005-'.'FN'.$request->part_type_id[$key].$partId.$sourceid.$statusid.$qualityid.'/'.$refundAmount;
                    $newqayd->cost_center='';
                    $newqayd->amount_currency=null;
                    $newqayd->currency_id=null;
                    $newqayd->debit= $refundAmount * $refunditemSalePrice;
                    $newqayd->credit='0';
                    $newqayd->desc='';
                    $nqayd->user_id=$logUser;
                    $newqayd->flag='0';
                    $newqayd->invoice_id=$invoiceId;
                    $newqayd->invoice_table='printInvoice';
                    $newqayd->qayds_id=$nqayd->id;
                    $newqayd->show_no='1';
                    $newqayd->save();
                    
                    if ($request->part_type_id[$key] == '1') {
                        $allpart = AllPart::join('stores_log', 'stores_log.All_part_id', '=', 'all_parts.id')
                        // ->where('stores_log.store_id',  $request->storeId)
                        ->where('stores_log.status',  3)
                        ->where('all_parts.part_id', $partId)->where('all_parts.source_id', $sourceid)->where('all_parts.status_id', $statusid)->where('all_parts.quality_id', $qualityid)
                        ->orderBy('all_parts.id', 'desc')
                        ->select('all_parts.*')
                        ->get();

                        $totalAmount = $refundAmount;
                        foreach ($allpart as $keyx => $valuex) {
                            if ($valuex->remain_amount + $totalAmount  <= $valuex->amount) {

                                $Ac_currency_id = $valuex->order_supplier->currency_id;
                                $Ac_currency_date = $valuex->order_supplier->confirmation_date;
                                $Ac_all_currency_types = CurrencyType::with([
                                    'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                        return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                                    },
                                ])->where('id', $Ac_currency_id)
                                ->get();

                                $total_item_buy_price += $totalAmount * ($valuex->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                                $newItemCoastPrice += $totalAmount * ($valuex->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                                
                                $x = AllPart::where('id', $valuex->id)->increment('remain_amount', $totalAmount);
                                $logMessage.='استرجاع علي Allpart '.$valuex->id.'الصنف'.$valuex->part_id.' الكمية '.$totalAmount.'<br/>';
                                $invoiceItemss=InvoiceItem::where('invoice_id',$invoiceId)
                                ->where('part_id',$partId)
                                ->where('source_id',$sourceid)
                                ->where('status_id',$statusid)
                                ->where('quality_id',$qualityid)
                                ->where('part_type_id',1)
                                ->first();

                                RefundInvoice::create([
                                    'invoice_id' => $invoiceId,
                                    'item_id' => $invoiceItemss->id,
                                    'r_amount' => $totalAmount,
                                    'date' => Carbon::now(),
                                    // 'r_tax' => floatVal($newTax),
                                    'r_tax' => floatVal($itemTaxes),
                                    'r_discount' => floatVal($itemDiscount),
                                    'item_price' => floatVal($refunditemSalePrice),
                                ]);

                                array_push($orderSupplierArr, $valuex->order_supplier_id);
                                break;
                            } else if ($totalAmount + $valuex->remain_amount > $valuex->amount) {

                                $Ac_currency_id = $valuex->order_supplier->currency_id;
                                $Ac_currency_date = $valuex->order_supplier->confirmation_date;
                                $Ac_all_currency_types = CurrencyType::with([
                                    'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                        return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                                    },
                                ])->where('id', $Ac_currency_id)
                                ->get();

                                $total_item_buy_price += ($valuex->amount - $valuex->remain_amount) * ($valuex->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                                $newItemCoastPrice += ($valuex->amount - $valuex->remain_amount) * ($valuex->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);


                                $x = AllPart::where('id', $valuex->id)->increment('remain_amount', $valuex->amount - $valuex->remain_amount);
                                $logMessage.='استرجاع علي Allpart '.$valuex->id.'الصنف'.$valuex->part_id.' الكمية '.$valuex->amount - $valuex->remain_amount.'<br/>';
                                $invoiceItemss=InvoiceItem::where('invoice_id',$invoiceId)
                                ->where('part_id',$partId)
                                ->where('source_id',$sourceid)
                                ->where('status_id',$statusid)
                                ->where('quality_id',$qualityid)
                                ->where('part_type_id',1)
                                ->first();

                                RefundInvoice::create([
                                    'invoice_id' => $invoiceId,
                                    'item_id' => $invoiceItemss->id,
                                    'r_amount' =>$valuex->amount - $valuex->remain_amount,
                                    'date' => Carbon::now(),
                                    'r_tax' => floatVal($itemTaxes),
                                    'r_discount' => floatVal($itemDiscount),
                                    'item_price' => floatVal($refunditemSalePrice),
                                ]);
                                array_push($orderSupplierArr, $valuex->order_supplier_id);
                                $diff=0;
                                $diff= floatval($valuex->amount) - floatval($valuex->remain_amount);
                                $totalAmount = $totalAmount - $diff;
                            } else if ($totalAmount <= 0) {
                                break;
                            }
                        }

                        ///// here error //////////////////
                        $allstorees = DB::table($store_table_name)->where('part_id', $partId)->whereIn('supplier_order_id', $orderSupplierArr)->where('type_id', 1)->orderBy('id', 'DESC')->first();
                        
                        if($allstorees){
                            DB::table($store_table_name)->where('id', $allstorees->id)->increment('amount', $refundAmount);
                            $logMessage.='استرجاع علي '.$store[0]->name.' الكمية '.$refundAmount.'موجود <br/>';

                        }else{
                            $allstoreesx =DB::table($store_table_name)
                            ->select('*')
                            ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                            ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                            ->where('all_parts.part_id', '=', $allpart[0]->part_id)
                            ->where('all_parts.source_id', '=', $allpart[0]->source_id)
                            ->where('all_parts.status_id', '=', $allpart[0]->status_id)
                            ->where('all_parts.quality_id', '=', $allpart[0]->quality_id)
                            // ->where('all_parts.order_supplier_id', '=', $allpart->order_supplier_id)
                            ->where($store_table_name . '.type_id', '=', 1)
                            // ->select('all_parts.order_supplier_id')
                            // ->get();
                            ->pluck('all_parts.order_supplier_id'); // Use pluck here
                            $orderSupplierIdss = $allstoreesx->toArray();
                            
                            // DB::table($store_table_name)->whereIn('id', $orderSupplierIdss)->increment('amount', $refundAmount);
                            DB::table($store_table_name)->where('part_id', $partId)->whereIn('supplier_order_id', $orderSupplierIdss)->where('type_id', 1)->increment('amount', $refundAmount);
                            $logMessage.='استرجاع علي '.$store[0]->name.' الكمية '.$refundAmount.'<br/>';

                        }

                        // DB::table('store_section')->where('part_id', $partId)->where('order_supplier_id', $osid)->where('type_id', 1)->where('source_id', $sourceid)->where('status_id', $statusid)->where('quality_id', $qualityid)->increment('amount', $refundAmount);
                    } elseif ($request->part_type_id[$key] == '2') {
                        $allpart = AllWheel::join('stores_log', 'stores_log.All_part_id', '=', 'all_wheels.id')
                        // ->where('stores_log.store_id',  $request->storeId)
                        ->where('stores_log.status',  3)
                        ->where('all_wheels.part_id', $partId)->where('all_wheels.source_id', $sourceid)
                        ->where('all_wheels.status_id', $statusid)->where('all_wheels.quality_id', $qualityid)
                        ->orderBy('all_wheels.id', 'desc')
                        ->select('all_wheels.*')
                        ->get();


                        $totalAmount = $refundAmount;
                        foreach ($allpart as $key => $valuey) {
                            if ($valuey->remain_amount + $totalAmount  <= $valuey->amount) {
                                $Ac_currency_id = $valuey->order_supplier->currency_id;
                                $Ac_currency_date = $valuey->order_supplier->confirmation_date;
                                $Ac_all_currency_types = CurrencyType::with([
                                    'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                        return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                                    },
                                ])->where('id', $Ac_currency_id)
                                ->get();

                                $total_item_buy_price += $totalAmount * ($valuey->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                                $newItemCoastPrice += $totalAmount * ($valuey->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);


                                AllWheel::where('id', $valuey->id)->increment('remain_amount', $totalAmount);
                                $invoiceItemss=InvoiceItem::where('invoice_id',$invoiceId)
                                ->where('part_id',$partId)
                                ->where('source_id',$sourceid)
                                ->where('status_id',$statusid)
                                ->where('quality_id',$qualityid)
                                ->where('part_type_id',2)
                                ->first();
                                RefundInvoice::create([
                                    'invoice_id' => $invoiceId,
                                    'item_id' => $invoiceItemss->id,
                                    'r_amount' => $totalAmount,
                                    'date' => Carbon::now(),
                                    'r_tax' => floatVal($itemTaxes),
                                    'r_discount' => floatVal($itemDiscount),
                                    'item_price' => floatVal($refunditemSalePrice),
                                ]);

                                array_push($orderSupplierArr, $valuey->order_supplier_id);
                                break;
                            } else if ($totalAmount + $valuey->remain_amount > $valuey->amount) {

                                $Ac_currency_id = $valuey->order_supplier->currency_id;
                                $Ac_currency_date = $valuey->order_supplier->confirmation_date;
                                $Ac_all_currency_types = CurrencyType::with([
                                    'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                        return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                                    },
                                ])->where('id', $Ac_currency_id)
                                ->get();

                                $total_item_buy_price += ($valuey->amount - $valuey->remain_amount) * ($valuey->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                                $newItemCoastPrice += ($valuey->amount - $valuey->remain_amount) * ($valuey->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);


                                AllWheel::where('id', $valuey->id)->increment('remain_amount', $valuey->amount - $valuey->remain_amount);
                                $invoiceItemss=InvoiceItem::where('invoice_id',$invoiceId)
                                ->where('part_id',$partId)
                                ->where('source_id',$sourceid)
                                ->where('status_id',$statusid)
                                ->where('quality_id',$qualityid)
                                ->where('part_type_id',2)
                                ->first();
                                RefundInvoice::create([
                                    'invoice_id' => $invoiceId,
                                    'item_id' => $invoiceItemss->id,
                                    'r_amount' => $valuey->amount - $valuey->remain_amount,
                                    'date' => Carbon::now(),
                                    'r_tax' => floatVal($itemTaxes),
                                    'r_discount' => floatVal($itemDiscount),
                                    'item_price' => floatVal($refunditemSalePrice),
                                ]);
                                array_push($orderSupplierArr, $valuey->order_supplier_id);
                                // $totalAmount = $totalAmount - ($valuey->amount - $valuey->remain_amount);
                                
                                $diff=0;
                                $diff= floatval($valuey->amount) - floatval($valuey->remain_amount);
                                $totalAmount = $totalAmount - $diff;
                            } else if ($totalAmount <= 0) {
                                break;
                            }
                        }

                        $allstorees = DB::table($store_table_name)->where('part_id', $partId)->whereIn('supplier_order_id', $orderSupplierArr)->where('type_id', 2)->orderBy('id', 'DESC')->first();

                        // DB::table($store_table_name)->where('id', $allstorees->id)->increment('amount', $refundAmount);
                        if($allstorees){
                            DB::table($store_table_name)->where('id', $allstorees->id)->increment('amount', $refundAmount);
                            $logMessage.='استرجاع علي '.$store[0]->name.' الكمية '.$refundAmount.'موجود <br/>';

                        }else{
                            $allstoreesx =DB::table($store_table_name)
                            ->select('*')
                            ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                            ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                            ->where('all_wheels.part_id', '=', $allpart[0]->part_id)
                            ->where('all_wheels.source_id', '=', $allpart[0]->source_id)
                            ->where('all_wheels.status_id', '=', $allpart[0]->status_id)
                            ->where('all_wheels.quality_id', '=', $allpart[0]->quality_id)
                            // ->where('all_parts.order_supplier_id', '=', $allpart->order_supplier_id)
                            ->where($store_table_name . '.type_id', '=', 2)
                            // ->select('all_parts.order_supplier_id')
                            // ->get();
                            ->pluck('all_wheels.order_supplier_id'); // Use pluck here
                            $orderSupplierIdss = $allstoreesx->toArray();
                            
                            // DB::table($store_table_name)->whereIn('id', $orderSupplierIdss)->increment('amount', $refundAmount);
                            DB::table($store_table_name)->where('part_id', $partId)->whereIn('supplier_order_id', $orderSupplierIdss)->where('type_id', 2)->increment('amount', $refundAmount);
                            $logMessage.='استرجاع علي '.$store[0]->name.' الكمية '.$refundAmount.'<br/>';

                        }
                    } elseif ($request->part_type_id[$key] == '6') {
                        $allpart = AllKit::join('stores_log', 'stores_log.All_part_id', '=', 'all_kits.id')
                        // ->where('stores_log.store_id',  $request->storeId)
                        ->where('stores_log.status',  3)
                        ->where('all_kits.part_id', $partId)->where('all_kits.source_id', $sourceid)
                        ->where('all_kits.status_id', $statusid)->where('all_kits.quality_id', $qualityid)
                        ->orderBy('all_kits.id', 'desc')
                        ->select('all_kits.*')
                        ->get();


                        $totalAmount = $refundAmount;
                        foreach ($allpart as $key => $valuez) {
                            // return $valuez;
                            if ($valuez->remain_amount + $totalAmount  <= $valuez->amount) {
                                $Ac_currency_id = $valuez->order_supplier->currency_id;
                                $Ac_currency_date = $valuez->order_supplier->confirmation_date;
                                $Ac_all_currency_types = CurrencyType::with([
                                    'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                        return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                                    },
                                ])->where('id', $Ac_currency_id)
                                ->get();

                                $total_item_buy_price += $totalAmount * ($valuez->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                                $newItemCoastPrice += $totalAmount * ($valuez->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);

                                AllKit::where('id', $valuez->id)->increment('remain_amount', $totalAmount);
                                $invoiceItemss=InvoiceItem::where('invoice_id',$invoiceId)
                                ->where('part_id',$partId)
                                ->where('source_id',$sourceid)
                                ->where('status_id',$statusid)
                                ->where('quality_id',$qualityid)
                                ->where('part_type_id',6)
                                ->first();
                                RefundInvoice::create([
                                    'invoice_id' => $invoiceId,
                                    'item_id' => $invoiceItemss->id,
                                    'r_amount' => $totalAmount,
                                    'date' => Carbon::now(),
                                    'r_tax' => floatVal($itemTaxes),
                                    'r_discount' => floatVal($itemDiscount),
                                    'item_price' => floatVal($refunditemSalePrice),
                                ]);
                                array_push($orderSupplierArr, $valuez->order_supplier_id);
                                break;
                            } else if ($totalAmount + $valuez->remain_amount > $valuez->amount) {

                                $Ac_currency_id = $valuez->order_supplier->currency_id;
                                $Ac_currency_date = $valuez->order_supplier->confirmation_date;
                                $Ac_all_currency_types = CurrencyType::with([
                                    'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                        return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                                    },
                                ])->where('id', $Ac_currency_id)
                                ->get();

                                $total_item_buy_price += ($valuez->amount - $valuez->remain_amount) * ($valuez->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                                $newItemCoastPrice += ($valuez->amount - $valuez->remain_amount) * ($valuez->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);

                                AllKit::where('id', $valuez->id)->increment('remain_amount', $valuez->amount - $valuez->remain_amount);
                                $invoiceItemss=InvoiceItem::where('invoice_id',$invoiceId)
                                ->where('part_id',$partId)
                                ->where('source_id',$sourceid)
                                ->where('status_id',$statusid)
                                ->where('quality_id',$qualityid)
                                ->where('part_type_id',6)
                                ->first();
                                RefundInvoice::create([
                                    'invoice_id' => $invoiceId,
                                    'item_id' => $invoiceItemss->id,
                                    'r_amount' => $valuez->amount - $valuez->remain_amount,
                                    'date' => Carbon::now(),
                                    'r_tax' => floatVal($itemTaxes),
                                    'r_discount' => floatVal($itemDiscount),
                                    'item_price' => floatVal($refunditemSalePrice),
                                ]);
                                array_push($orderSupplierArr, $valuez->order_supplier_id);
                                // $totalAmount = $totalAmount - ($valuez->amount - $valuez->remain_amount);
                                   $diff=0;
                                $diff= floatval($valuez->amount) - floatval($valuez->remain_amount);
                                $totalAmount = $totalAmount - $diff;
                            } else if ($totalAmount <= 0) {
                                break;
                            }
                        }

                        $allstorees = DB::table($store_table_name)->where('part_id', $partId)->whereIn('supplier_order_id', $orderSupplierArr)->where('type_id', 6)->orderBy('id', 'DESC')->first();

                        // DB::table($store_table_name)->where('id', $allstorees->id)->increment('amount', $refundAmount);
                          
                        if($allstorees){
                            DB::table($store_table_name)->where('id', $allstorees->id)->increment('amount', $refundAmount);
                            $logMessage.='استرجاع علي '.$store[0]->name.' الكمية '.$refundAmount.'موجود <br/>';

                        }else{
                            $allstoreesx =DB::table($store_table_name)
                            ->select('*')
                            ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                            ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                            ->where('all_kits.part_id', '=', $allpart[0]->part_id)
                            ->where('all_kits.source_id', '=', $allpart[0]->source_id)
                            ->where('all_kits.status_id', '=', $allpart[0]->status_id)
                            ->where('all_kits.quality_id', '=', $allpart[0]->quality_id)
                            // ->where('all_parts.order_supplier_id', '=', $allpart->order_supplier_id)
                            ->where($store_table_name . '.type_id', '=', 6)
                            // ->select('all_parts.order_supplier_id')
                            // ->get();
                            ->pluck('all_kits.order_supplier_id'); // Use pluck here
                            $orderSupplierIdss = $allstoreesx->toArray();
                            
                            // DB::table($store_table_name)->whereIn('id', $orderSupplierIdss)->increment('amount', $refundAmount);
                            DB::table($store_table_name)->where('part_id', $partId)->whereIn('supplier_order_id', $orderSupplierIdss)->where('type_id', 6)->increment('amount', $refundAmount);
                            $logMessage.='استرجاع علي '.$store[0]->name.' الكمية '.$refundAmount.'<br/>';

                        }
                    }
                    
                     $newqayd = new Newqayd();
                    $newqayd->refrence='RINV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                    $newqayd->coa_id=225;
                    $newqayd->journal_id=9;
                    $newqayd->partner_id=$request->clientId;
                    $newqayd->type='Client';
                    $newqayd->label='Stock/OUT/'.date('Y').'/'.date('m').'/005-'.'FN'.$request->part_type_id[$key].$partId.$sourceid.$statusid.$qualityid.'/'.$refundAmount;
                    $newqayd->cost_center='';
                    $newqayd->amount_currency=null;
                    $newqayd->currency_id=null;
                    $newqayd->debit= $newItemCoastPrice;
                    $newqayd->credit='0';
                    $newqayd->desc='When Confirm the return Partial of the Invoice quantities';
                    $nqayd->user_id=$logUser;
                    $newqayd->flag='0';
                    $newqayd->invoice_id=$invoiceId;
                    $newqayd->invoice_table='printInvoice';
                    $newqayd->qayds_id=$nqayd->id;
                    $newqayd->show_no='1';
                    $newqayd->save();

                    $newqayd = new Newqayd();
                    $newqayd->refrence='RINV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                    $newqayd->coa_id=168;
                    $newqayd->journal_id=9;
                    $newqayd->partner_id=$request->clientId;
                    $newqayd->type='Client';
                    $newqayd->label='Stock/OUT/'.date('Y').'/'.date('m').'/005-'.'FN'.$request->part_type_id[$key].$partId.$sourceid.$statusid.$qualityid.'/'.$refundAmount;
                    $newqayd->cost_center='';
                    $newqayd->amount_currency=null;
                    $newqayd->currency_id=null;
                    $newqayd->debit= 0;
                    $newqayd->credit=$newItemCoastPrice;
                    $newqayd->desc='When Confirm the return Partial of the Invoice quantities';
                    $nqayd->user_id=$logUser;
                    $newqayd->flag='0';
                    $newqayd->invoice_id=$invoiceId;
                    $newqayd->invoice_table='printInvoice';
                    $newqayd->qayds_id=$nqayd->id;
                    $newqayd->show_no='1';
                    $newqayd->save();
                    /************************************************************************ */
                    $newqayd = new Newqayd();
                    $newqayd->refrence='RINV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                    $newqayd->coa_id=223;
                    $newqayd->journal_id=8;
                    $newqayd->partner_id=$request->clientId;
                    $newqayd->type='Client';
                    $newqayd->label='Stock/OUT/'.date('Y').'/'.date('m').'/005-'.'FN'.$request->part_type_id[$key].$partId.$sourceid.$statusid.$qualityid.'/'.$refundAmount;
                    $newqayd->cost_center='';
                    $newqayd->amount_currency=null;
                    $newqayd->currency_id=null;
                    $newqayd->debit= $newItemCoastPrice;
                    $newqayd->credit=0;
                    $newqayd->desc='When Confirm the return Partial of the Bill quantities';
                    $nqayd->user_id=$logUser;
                    $newqayd->flag='0';
                    $newqayd->invoice_id=$invoiceId;
                    $newqayd->invoice_table='printInvoice';
                    $newqayd->qayds_id=$nqayd->id;
                    $newqayd->show_no='2';
                    $newqayd->save();

                    $newqayd = new Newqayd();
                    $newqayd->refrence='RINV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                    $newqayd->coa_id=225;
                    $newqayd->journal_id=8;
                    $newqayd->partner_id=$request->clientId;
                    $newqayd->type='Client';
                    $newqayd->label='Stock/OUT/'.date('Y').'/'.date('m').'/005-'.'FN'.$request->part_type_id[$key].$partId.$sourceid.$statusid.$qualityid.'/'.$refundAmount;
                    $newqayd->cost_center='';
                    $newqayd->amount_currency=null;
                    $newqayd->currency_id=null;
                    $newqayd->debit= 0;
                    $newqayd->credit=$newItemCoastPrice;
                    $newqayd->desc='When Confirm the return Partial of the Bill quantities';
                    $nqayd->user_id=$logUser;
                    $newqayd->flag='0';
                    $newqayd->invoice_id=$invoiceId;
                    $newqayd->invoice_table='printInvoice';
                    $newqayd->qayds_id=$nqayd->id;
                    $newqayd->show_no='2';
                    $newqayd->save();
                }
            }
            
             $newqayd = new Newqayd();
            $newqayd->refrence='RINV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
            $newqayd->coa_id=45;
            $newqayd->journal_id=9;
            $newqayd->partner_id=$cli_id;
            $newqayd->type='Client';
            $newqayd->label='RINV/'.date('Y').'/'.date('m');
            $newqayd->cost_center='';
            $newqayd->amount_currency=null;
            $newqayd->currency_id=null;
            $newqayd->credit=$request->totalRef;
            $newqayd->debit=0;
            $newqayd->desc='';
            $nqayd->user_id=$logUser;
            $newqayd->flag='0';
            $newqayd->invoice_id=$invoiceId;
            $newqayd->invoice_table='printInvoice';
            $newqayd->qayds_id=$nqayd->id;
            $newqayd->show_no='1';
            $newqayd->save();
            
            $quaditems = [];
            $automaicQayd = new QaydController();

            // $total = MoneySafe::where('store_id', $request->storeId)
            //     ->latest()
            //     ->first();

            $storees = Store::where('safe_accountant_number', $request->paymentAcount)->first();
            if (floatVal($request->totalRef) > 0) {
                if ($request->paymnet == 0) {
                    // كاش
                    array_push($quaditems, (object) ['acountant_id' => $request->paymentAcount, 'madin' => 0, 'dayin' => $request->totalRef]); // المخزن مدين
                    array_push($quaditems, (object) ['acountant_id' => 4512, 'madin' => $request->totalRef, 'dayin' => 0]); // المرتجع دائن

                    if ($storees) {

                        $total = MoneySafe::where('store_id', $request->storeId)
                            ->latest()
                            ->first();
                        if (isset($total)) {
                            MoneySafe::create([
                                'notes' => 'استرجاع علي فاتورة بيع رقم ' . $invoiceId,
                                'date' => Carbon::now(),
                                'flag' => 1,
                                'money' => floatVal($request->totalRef),
                                'total' => $total->total - floatVal($request->totalRef),
                                'type_money' => '1',
                                'user_id' => Auth::user()->id,
                                'store_id' => $request->storeId,
                                'note_id' => 19,
                            ]);
                             $logMessage.='استرجاع   تم خصم'.floatVal($request->totalRef).
                            ' الي خزنة '.$request->storeId.'<br/>';
                        } else {
                            MoneySafe::create([
                                'notes' => 'استرجاع علي فاتورة بيع رقم ' . $invoiceId,
                                'date' => Carbon::now(),
                                'flag' => 1,
                                'money' => floatVal($request->totalRef),
                                'total' => floatVal($request->totalRef),
                                'type_money' => '1',
                                'user_id' => Auth::user()->id,
                                'store_id' => $request->storeId,
                                'note_id' => 19,
                            ]);
                             $logMessage.='استرجاع   تم خصم'.floatVal($request->totalRef).
                            ' الي خزنة '.$request->storeId.'<br/>';
                        }
                        
                        $newqayd = new Newqayd();
                        $newqayd->refrence='CASH/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                        $newqayd->coa_id=41;
                        $newqayd->journal_id=7;
                        $newqayd->partner_id=$cli_id;
                        $newqayd->type='Client';
                        $newqayd->label='Manual Payment/'.date('Y').'/'.date('m');
                        $newqayd->cost_center='';
                        $newqayd->amount_currency=null;
                        $newqayd->currency_id=null;
                        $newqayd->credit= $request->totalRef;
                        $newqayd->debit=0;
                        $newqayd->desc='';
                        $newqayd->user_id=$logUser;
                        $newqayd->flag='0';
                        $newqayd->invoice_id=$invoiceId;
                        $newqayd->invoice_table='printInvoice';
                        $newqayd->qayds_id=$nqayd->id;
                        $newqayd->show_no='3';
                        $newqayd->save();
                    } else {
                        $st_bank = BankType::where('accountant_number', $request->paymentAcount)->first();
                        $total = BankSafeMoney::where('bank_type_id', $st_bank->id)
                            ->latest()
                            ->first();

                        if (isset($total)) {
                            BankSafeMoney::create([
                                'notes' => 'استرجاع علي فاتورة بيع رقم ' . $invoiceId,
                                'date' => date('Y-m-d'),
                                'flag' => 1,
                                'money' => floatVal($request->totalRef),
                                'total' => $total->total - floatVal($request->totalRef),
                                'type_money' => '1',
                                'user_id' => Auth::user()->id,
                                'store_id' => null,
                                'money_currency' => floatVal($request->totalRef),
                                'currency_id' => 400,
                                'bank_type_id' => $st_bank->id

                            ]);
                             $logMessage.='استرجاع   تم خصم'.floatVal($request->totalRef).
                            ' الي بنك '.$st_bank->id.'<br/>';
                        } else {
                            BankSafeMoney::create([
                                'notes' => 'استرجاع علي فاتورة بيع رقم ' . $invoiceId,
                                'date' => date('Y-m-d'),
                                'flag' => 1,
                                'money' => floatVal($request->totalRef),
                                'total' => floatVal($request->totalRef),
                                'type_money' => '1',
                                'user_id' => Auth::user()->id,
                                'store_id' => null,
                                'money_currency' => floatVal($request->totalRef),
                                'currency_id' => 400,
                                'bank_type_id' => $st_bank->id

                            ]);
                            
                            $logMessage.='استرجاع   تم خصم'.floatVal($request->totalRef).
                            ' الي بنك '.$st_bank->id.'<br/>';
                        }
                        
                        $newqayd = new Newqayd();
                        $newqayd->refrence='BANK/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                        $newqayd->coa_id=40;
                        $newqayd->journal_id=7;
                        $newqayd->partner_id=$cli_id;
                        $newqayd->type='Client';
                        $newqayd->label='BANK Transfer/'.date('Y').'/'.date('m');
                        $newqayd->cost_center='';
                        $newqayd->amount_currency=null;
                        $newqayd->currency_id=null;
                        $newqayd->credit= $request->totalRef;
                        $newqayd->debit=0;
                        $newqayd->desc='';
                        $newqayd->user_id=$logUser;
                        $newqayd->flag='0';
                        $newqayd->invoice_id=$invoiceId;
                        $newqayd->invoice_table='printInvoice';
                        $newqayd->qayds_id=$nqayd->id;
                        $newqayd->show_no='3';
                        $newqayd->save();
                    }

                    RefundInvoicePayment::create([
                        'invoice_id' => $request->invoiceId,
                        'total_paied' => $request->totalRef,
                        'paied' => $request->totalRef,
                        'total_dicount' => $total_discount1,
                        'total_tax' => $total_taxes1,
                        'payment_acountant' => $request->paymentAcount,
                        'payment_type' => $request->paymnet
                    ]);
                    
                     $logMessage.='استرجاع '.$request->invoiceId.' تم خصم'.$request->totalRef.
                    ' الخصم  '.$total_discount1.
                    ' الضريبة  '.$total_taxes1.
                    ' الحساب  '.$request->paymentAcount
                    .'<br/>';
                } else {
                    // add madyoniea
                    $clientHessap = Client::where('id', $request->clientId)->first();
                    array_push($quaditems, (object) ['acountant_id' => $request->paymentAcount, 'madin' => 0, 'dayin' => $request->refundTotal1]); // طريقة الدفع
                    array_push($quaditems, (object) ['acountant_id' => 4512, 'madin' => $request->totalRef, 'dayin' => 0]); //مردودات مبيعات
                    array_push($quaditems, (object) ['acountant_id' => $clientHessap->accountant_number, 'madin' =>  0, 'dayin' => floatval($request->totalRef) - floatval($request->refundTotal1)]); // زبون

                    if ($storees) {

                        $total = MoneySafe::where('store_id', $request->storeId)
                            ->latest()
                            ->first();
                        if (isset($total)) {
                            MoneySafe::create([
                                'notes' => 'استرجاع علي فاتورة بيع رقم ' . $invoiceId,
                                'date' => Carbon::now(),
                                'flag' => 1,
                                'money' =>  floatval($request->refundTotal1),
                                'total' => $total->total - floatval($request->refundTotal1),
                                'type_money' => '1',
                                'user_id' => Auth::user()->id,
                                'store_id' => $request->storeId,
                                'note_id' => 19,
                            ]);
                            
                            $logMessage.='استرجاع   تم خصم'.floatVal($request->refundTotal1).
                            ' الي خزنة '.$request->storeId.'<br/>';
                        } else {
                            MoneySafe::create([
                                'notes' => 'استرجاع علي فاتورة بيع رقم ' . $invoiceId,
                                'date' => Carbon::now(),
                                'flag' => 1,
                                'money' => floatval($request->refundTotal1),
                                'total' => floatval($request->refundTotal1),
                                'type_money' => '1',
                                'user_id' => Auth::user()->id,
                                'store_id' => $request->storeId,
                                'note_id' => 19,
                            ]);
                            
                            
                            $logMessage.='استرجاع   تم خصم'.floatVal($request->refundTotal1).
                            ' الي خزنة '.$request->storeId.'<br/>';
                        }
                         $newqayd = new Newqayd();
                        $newqayd->refrence='CASH/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                        $newqayd->coa_id=41;
                        $newqayd->journal_id=7;
                        $newqayd->partner_id=$cli_id;
                        $newqayd->type='Client';
                        $newqayd->label='Manual Payment/'.date('Y').'/'.date('m');
                        $newqayd->cost_center='';
                        $newqayd->amount_currency=null;
                        $newqayd->currency_id=null;
                        $newqayd->credit= floatval($request->refundTotal1);
                        $newqayd->debit=0;
                        $newqayd->desc='';
                        $newqayd->user_id=$logUser;
                        $newqayd->flag='0';
                        $newqayd->invoice_id=$invoiceId;
                        $newqayd->invoice_table='printInvoice';
                        $newqayd->qayds_id=$nqayd->id;
                        $newqayd->show_no='3';
                        $newqayd->save();
                        
                    } else {
                        $st_bank = BankType::where('accountant_number', $request->paymentAcount)->first();
                        $total = BankSafeMoney::where('bank_type_id', $st_bank->id)
                            ->latest()
                            ->first();

                        if (isset($total)) {
                            BankSafeMoney::create([
                                'notes' => 'استرجاع علي فاتورة بيع رقم ' . $invoiceId,
                                'date' => date('Y-m-d'),
                                'flag' => 1,
                                'money' => floatval($request->refundTotal1),
                                'total' => $total->total - floatval($request->refundTotal1),
                                'type_money' => '1',
                                'user_id' => Auth::user()->id,
                                'store_id' => null,
                                'money_currency' => floatval($request->refundTotal1),
                                'currency_id' => 400,
                                'bank_type_id' => $st_bank->id

                            ]);
                            
                            $logMessage.='استرجاع   تم خصم'.floatVal($request->refundTotal1).
                            ' الي بنك '.$st_bank->id.'<br/>';
                        } else {
                            BankSafeMoney::create([
                                'notes' => 'استرجاع علي فاتورة بيع رقم ' . $invoiceId,
                                'date' => date('Y-m-d'),
                                'flag' => 1,
                                'money' => floatval($request->refundTotal1),
                                'total' =>  floatval($request->refundTotal1),
                                'type_money' => '1',
                                'user_id' => Auth::user()->id,
                                'store_id' => null,
                                'money_currency' =>  floatval($request->refundTotal1),
                                'currency_id' => 400,
                                'bank_type_id' => $st_bank->id

                            ]);
                            
                            $logMessage.='استرجاع   تم خصم'.floatVal($request->refundTotal1).
                            ' الي بنك '.$st_bank->id.'<br/>';
                        }
                        
                        $newqayd = new Newqayd();
                        $newqayd->refrence='BANK/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                        $newqayd->coa_id=40;
                        $newqayd->journal_id=7;
                        $newqayd->partner_id=$cli_id;
                        $newqayd->type='Client';
                        $newqayd->label='BANK Transfer/'.date('Y').'/'.date('m');
                        $newqayd->cost_center='';
                        $newqayd->amount_currency=null;
                        $newqayd->currency_id=null;
                        $newqayd->credit= floatval($request->refundTotal1);
                        $newqayd->debit=0;
                        $newqayd->desc='';
                        $newqayd->user_id=$logUser;
                        $newqayd->flag='0';
                        $newqayd->invoice_id=$invoiceId;
                        $newqayd->invoice_table='printInvoice';
                        $newqayd->qayds_id=$nqayd->id;
                        $newqayd->show_no='3';
                        $newqayd->save();
                    }

                    RefundInvoicePayment::create([
                        'invoice_id' => $request->invoiceId,
                        'total_paied' => $request->totalRef,
                        'paied' => floatval($request->refundTotal1),
                        'total_dicount' => $total_discount1,
                        'total_tax' => $total_taxes1,
                        'payment_acountant' => $request->paymentAcount,
                        'payment_type' => $request->paymnet
                    ]);
                    
                    
                     $logMessage.='استرجاع '.$request->invoiceId.' تم خصم'.$request->refundTotal1.
                    ' الخصم  '.$total_discount1.
                    ' الضريبة  '.$total_taxes1.
                    ' الحساب  '.$request->paymentAcount
                    .'<br/>';
                }
                $newqayd = new Newqayd();
                $newqayd->refrence='INV/'.date('Y').'/'.date('m').'/'.$nqayd->id;
                $newqayd->coa_id=45;
                $newqayd->journal_id=7;
                $newqayd->partner_id=$cli_id;
                $newqayd->type='Client';
                $newqayd->label='Payment For INV/'.date('Y').'/'.date('m'); 
                $newqayd->cost_center='';
                $newqayd->amount_currency=null;
                $newqayd->currency_id=null;
                $newqayd->credit= 0;
                $newqayd->debit=$request->totalRef;
                $newqayd->desc='';
                $newqayd->user_id=$logUser;
                $newqayd->flag='0';
                $newqayd->invoice_id=$invoiceId;
                $newqayd->invoice_table='printInvoice';
                $newqayd->qayds_id=$nqayd->id;
                $newqayd->show_no='3';
                $newqayd->save();
            }




            $date = Carbon::now();
            $type = null;
            $notes = 'مردودات مبيعات / فاتورة رقم '.$invoiceId ."من " . $store[0]->name;
            $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);

            $quaditems = [];
            $automaicQayd = new QaydController();
            array_push($quaditems, (object) ['acountant_id' => $store[0]->accountant_number, 'madin' => $total_item_buy_price, 'dayin' => 0]); //
            array_push($quaditems, (object) ['acountant_id' => 37, 'madin' => 0, 'dayin' => $total_item_buy_price]); //
            $date = Carbon::now();
            $type = null;
            $notes = 'مردودات مبيعات / فاتورة رقم '.$invoiceId ."من " . $store[0]->name;
             $qyadidss = $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
             
            $user = User::find(33); // Get the user
            $user->notify(new NotifyUser($notes));
        
            $user = User::find(39); // Get the user
            $user->notify(new NotifyUser($notes));
        
        
            $logMessage.=' تم اضافة القيد'.$qyadidss.'<br/>';
            DB::commit();
             $log = new LogController();
            $log->newLog($logUser,$logMessage);
            
            return redirect()->to('printInvoice/' . $invoiceId);
        } catch (\Exception $e) {
            //throw $th;
            DB::rollback();
            session()->flash("error", $e);
            return $e;
        }
    }

    public function sendfromSection(Request $request)
    {
        // return $request;

        $storeSections =  StoreSection::where('store_id', $request->store_id)->where('part_id', $request->part_id)->where('section_id', $request->section_id)->where('type_id', $request->type_id)->where('source_id', $request->source_id)->where('status_id', $request->status_id)->where('quality_id', $request->quality_id)->get();
        $totalAmount = $request->new_amount;

        foreach ($storeSections as $key => $value) {
            if ($value->amount >= $totalAmount) {
                StoreSection::where('id', $value->id)->decrement('amount', $totalAmount);

                StoreSection::create([
                    'store_id' => $value->store_id,
                    'part_id' => $value->part_id,
                    'section_id' => $request->new_section,
                    'type_id' => $value->type_id,
                    'source_id' => $value->source_id,
                    'status_id' => $value->status_id,
                    'quality_id' => $value->quality_id,
                    'order_supplier_id' => $value->order_supplier_id,
                    'amount' => $totalAmount
                ]);
                break;
            } else if ($totalAmount > $value->amount) {

                StoreSection::where('id', $value->id)->decrement('amount',  $value->amount);
                StoreSection::create([
                    'store_id' => $value->store_id,
                    'part_id' => $value->part_id,
                    'section_id' => $request->new_section,
                    'type_id' => $value->type_id,
                    'source_id' => $value->source_id,
                    'status_id' => $value->status_id,
                    'quality_id' => $value->quality_id,
                    'order_supplier_id' => $value->order_supplier_id,
                    'amount' => $value->amount
                ]);
                $totalAmount = $totalAmount - $value->amount;
            } else if ($totalAmount < 0) {
                break;
            }
        }
        // return 'sddddd';

        return redirect()->back();
    }


    public function allData(Request $request)
    {
        //  return $request;

        $store = Store::where('id', $request->storeId)->first();

        if ($request->ajax()) {
            ini_set('memory_limit', '-1');
            Artisan::call('cache:clear');
            // ini_set('memory_limit', '800M');
            ////           Get Store Entity /////////////////
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
            $query1x =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_parts.part_id, all_parts.source_id, all_parts.status_id, all_parts.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                ->groupBy('all_parts.part_id', 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id',  $store->table_name . '.type_id')
                ->where($store->table_name . '.type_id', 1);
            $query1x = $query1x->with([
                'stores_log.all_parts.part.part_numbers',
                'stores_log.all_parts.part.getsmallunit.unit',
                'stores_log.all_parts.source',
                'stores_log.all_parts.status',
                'stores_log.all_parts.part_quality',
                'stores_log.all_parts.sectionswithoutorder' => function ($query)  use ($store) {
                    $query->where('store_id', $store->id);
                },
                'stores_log.all_parts.sectionswithoutorder.store',
                'stores_log.all_parts.sectionswithoutorder.store_structure',
                'stores_log.all_parts.pricing.sale_type',
                'stores_log.all_parts.part.part_details.part_spec',
                'stores_log.all_parts.order_supplier',
                'stores_log.all_parts.part.sub_group.group'
            ])
                ->get();

            $query2x = $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                ->where($store->table_name . '.type_id', 6)
                ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_kits.part_id, all_kits.source_id, all_kits.status_id, all_kits.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                ->groupBy('all_kits.part_id', 'all_kits.source_id', 'all_kits.status_id', 'all_kits.quality_id', $store->table_name . '.type_id')
                ->with([
                    'stores_log.all_kits.kit.kit_numbers',
                    'stores_log.all_kits.source',
                    'stores_log.all_kits.status',
                    'stores_log.all_kits.part_quality',

                    'stores_log.all_kits.sectionswithoutorder' => function ($query)  use ($store) {
                        $query->where('store_id', $store->id);
                    },
                    'stores_log.all_kits.sectionswithoutorder.store',
                    'stores_log.all_kits.sectionswithoutorder.store_structure',
                    'stores_log.all_kits.pricing.sale_type',
                    'stores_log.all_kits.kit.kit_details.kit_spec',
                    'stores_log.all_kits.order_supplier.buy_transaction'
                ])
                ->get();


            $query3x =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                ->where($store->table_name . '.type_id', 2)
                ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_wheels.part_id, all_wheels.source_id, all_wheels.status_id, all_wheels.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                ->groupBy('all_wheels.part_id', 'all_wheels.source_id', 'all_wheels.status_id', 'all_wheels.quality_id', $store->table_name . '.type_id')
                ->with([
                    'stores_log.all_wheels.source',
                    'stores_log.all_wheels.status',
                    'stores_log.all_wheels.part_quality',

                    'stores_log.all_wheels.sectionswithoutorder' => function ($query)  use ($store) {
                        $query->where('store_id', $store->id);
                    },
                    'stores_log.all_wheels.sectionswithoutorder.store',
                    'stores_log.all_wheels.sectionswithoutorder.store_structure',
                    'stores_log.all_wheels.pricing.sale_type',
                    'stores_log.all_wheels.wheel.wheel_details.wheel_spec',

                    'stores_log.all_wheels.order_supplier.buy_transaction'
                ])
                ->get();

            $query4x =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                ->join('all_tractors', 'stores_log.All_part_id', '=', 'all_tractors.id')
                ->where($store->table_name . '.type_id', 3)
                ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_tractors.part_id, all_tractors.source_id, all_tractors.status_id, all_tractors.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                ->groupBy('all_tractors.part_id', 'all_tractors.source_id', 'all_tractors.status_id', 'all_tractors.quality_id', $store->table_name . '.type_id')
                ->with([
                    'stores_log.all_tractors.source',
                    'stores_log.all_tractors.status',
                    'stores_log.all_tractors.part_quality',

                    'stores_log.all_tractors.sectionswithoutorder' => function ($query)  use ($store) {
                        $query->where('store_id', $store->id);
                    },
                    'stores_log.all_tractors.sectionswithoutorder.store',
                    'stores_log.all_tractors.sectionswithoutorder.store_structure',
                    'stores_log.all_tractors.pricing.sale_type',
                    'stores_log.all_tractors.tractor.tractor_details.tractor_spec',

                    'stores_log.all_tractors.order_supplier.buy_transaction'
                ])
                ->get();

            $query5x =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                ->join('all_clarks', 'stores_log.All_part_id', '=', 'all_clarks.id')
                ->where($store->table_name . '.type_id', 4)
                ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_clarks.part_id, all_clarks.source_id, all_clarks.status_id, all_clarks.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                ->groupBy('all_clarks.part_id', 'all_clarks.source_id', 'all_clarks.status_id', 'all_clarks.quality_id', $store->table_name . '.type_id')
                ->with([
                    'stores_log.all_clarks.source',
                    'stores_log.all_clarks.status',
                    'stores_log.all_clarks.part_quality',

                    'stores_log.all_clarks.sectionswithoutorder' => function ($query)  use ($store) {
                        $query->where('store_id', $store->id);
                    },
                    'stores_log.all_clarks.sectionswithoutorder.store',
                    'stores_log.all_clarks.sectionswithoutorder.store_structure',
                    'stores_log.all_clarks.pricing.sale_type',
                    'stores_log.all_clarks.clark.clark_details.clark_spec',

                    'stores_log.all_clarks.order_supplier.buy_transaction'
                ])
                ->get();

            $query6x =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                ->join('all_equips', 'stores_log.All_part_id', '=', 'all_equips.id')
                ->where($store->table_name . '.type_id', 5)
                ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_equips.part_id, all_equips.source_id, all_equips.status_id, all_equips.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                ->groupBy('all_equips.part_id', 'all_equips.source_id', 'all_equips.status_id', 'all_equips.quality_id', $store->table_name . '.type_id')
                ->with([
                    'stores_log.all_equips.source',
                    'stores_log.all_equips.status',
                    'stores_log.all_equips.part_quality',
                    'stores_log.all_equips.sectionswithoutorder' => function ($query)  use ($store) {
                        $query->where('store_id', $store->id);
                    },
                    'stores_log.all_equips.sectionswithoutorder.store',
                    'stores_log.all_equips.sectionswithoutorder.store_structure',
                    'stores_log.all_equips.pricing.sale_type',
                    'stores_log.all_equips.equip.equip_details.equip_spec',

                    'stores_log.all_equips.order_supplier.buy_transaction'
                ])
                ->get();


               $mergedResults = $query1x->concat($query2x)->concat($query3x)->concat($query4x)->concat($query5x)->concat($query6x);
        //      $dataArr =collect($mergedResults[0]->stores_log->all_parts[0]->pricing);
        //             $maxValue = $dataArr->max('price');

        //           $filteredItems = $dataArr->filter(function ($item) {
        //                 return $item->sale_type == 5; // Condition
        //             });
        //       return $filteredItems[0]->price;
            return   FacadesDataTables::of($mergedResults)
                // ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    $btn = 'No Name';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->kit->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->wheel->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->tractor->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->clark->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->equip->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    }
                })
                ->addColumn('EmaraNumber', function ($row) {
                    $btn = 'No Name';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = "FN1" . $row->stores_log->all_parts[0]->part_id . $row->stores_log->all_parts[0]->source_id . $row->stores_log->all_parts[0]->status_id . $row->stores_log->all_parts[0]->quality_id;
                        } else {
                            $btn = 'No Number';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = "FN6" . $row->stores_log->all_kits[0]->part_id . $row->stores_log->all_kits[0]->source_id . $row->stores_log->all_kits[0]->status_id . $row->stores_log->all_kits[0]->quality_id;
                        } else {
                            $btn = 'No Number';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = "FN2" . $row->stores_log->all_wheels[0]->part_id . $row->stores_log->all_wheels[0]->source_id . $row->stores_log->all_wheels[0]->status_id . $row->stores_log->all_wheels[0]->quality_id;
                        } else {
                            $btn = 'No Number';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = "FN3" . $row->stores_log->all_tractors[0]->part_id . $row->stores_log->all_tractors[0]->source_id . $row->stores_log->all_tractors[0]->status_id . $row->stores_log->all_tractors[0]->quality_id;
                        } else {
                            $btn = 'No Number';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = "FN4" . $row->stores_log->all_clarks[0]->part_id . $row->stores_log->all_clarks[0]->source_id . $row->stores_log->all_clarks[0]->status_id . $row->stores_log->all_clarks[0]->quality_id;
                        } else {
                            $btn = 'No Number';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = "FN5" . $row->stores_log->all_equips[0]->part_id . $row->stores_log->all_equips[0]->source_id . $row->stores_log->all_equips[0]->status_id . $row->stores_log->all_equips[0]->quality_id;
                        } else {
                            $btn = 'No Number';
                        }
                        return $btn;
                    }
                })
                ->addColumn('source', function ($row) {
                    $btn = 'no source';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    }

                    return $btn;
                })
                ->addColumn('status', function ($row) {
                    $btn = 'No status';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    }

                    return $btn;
                })
                ->addColumn('quality', function ($row) {
                    $btn = 'No part Quality';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    }
                    return $btn;
                })
                ->addColumn('amount', function ($row) {
                    $btn = 0;
                    if ($row->amount > 0) {
                        $btn = $row->amount;
                    } else {
                        $btn = 0;
                    }
                    return $btn;
                })
                ->addColumn('section', function ($row) {
                    $btn = "";
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->sectionswithoutorder) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->sectionswithoutorder); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->sectionswithoutorder)) {
                                if (count($row->stores_log->all_kits[0]->sectionswithoutorder) > 0) {
                                    for ($i = 0; $i < count($row->stores_log->all_kits[0]->sectionswithoutorder); $i++) {
                                        $btn .= '<li>' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                    }
                                }
                            } else {
                                $btn = "No Section";
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (count($row->stores_log->all_wheels[0]->sectionswithoutorder) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_wheels[0]->sectionswithoutorder); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (count($row->stores_log->all_tractors[0]->sectionswithoutorder) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_tractors[0]->sectionswithoutorder); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_tractors[0]->sectionswithoutorder[$i]->store_structure->name . ' <span class="d-none">/ ' . $row->stores_log->all_tractors[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (count($row->stores_log->all_clarks[0]->sectionswithoutorder) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_clarks[0]->sectionswithoutorder); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_clarks[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_clarks[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (count($row->stores_log->all_equips[0]->sectionswithoutorder) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_equips[0]->sectionswithoutorder); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_equips[0]->sectionswithoutorder[$i]->store_structure->name . ' <span class="d-none">/ ' . $row->stores_log->all_equips[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    }


                    return $btn;
                })
                ->addColumn('price', function ($row) {
                    $btn = 0;
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                // $btn = $row->stores_log->all_parts[0]->pricing[0]->price;
                                  $dataArr =collect($row->stores_log->all_parts[0]->pricing);
                                    $maxValue = $dataArr->max('price');

                                //   $filteredItems = $dataArr->filter(function ($item) {
                                //     return $item->sale_type == 5; // Condition
                                // });
                                // if(count($filteredItems)>0){
                                //     $btn =$filteredItems->price ;
                                // } else {
                                    $btn = $maxValue;
                                // }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->pricing)) {
                                if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                    // $btn = $row->stores_log->all_kits[0]->pricing[0]->price;
                                    $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                    $maxValue = $dataArr->max('price');

                                    $btn =$maxValue ;
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                    // $btn = $row->stores_log->all_wheels[0]->pricing[0]->price;
                                     $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                    $maxValue = $dataArr->max('price');

                                    $btn =$maxValue ;
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                    // $btn = $row->stores_log->all_tractors[0]->pricing[0]->price;
                                   $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                    $maxValue = $dataArr->max('price');

                                    $btn =$maxValue ;
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                    // $btn = $row->stores_log->all_clarks[0]->pricing[0]->price;
                                     $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                    $maxValue = $dataArr->max('price');

                                    $btn =$maxValue ;
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (isset($row->stores_log->all_equips[0]->pricing)) {
                                if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                    // $btn = $row->stores_log->all_equips[0]->pricing[0]->price;
                                      $dataArr =collect($row->stores_log->pricing[0]->pricing);
                                    $maxValue = $dataArr->max('price');

                                    $btn =$maxValue ;
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    }
                    return $btn;
                })
                ->addColumn('partnumbers', function ($row) {
                    $btn = 'no num';
                    if ($row->type_id == 1) {
                        if (isset($row->stores_log->all_parts[0]->part->part_numbers)) {
                            if (count($row->stores_log->all_parts[0]->part->part_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->part->part_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_parts[0]->part->part_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 6) {
                        if (isset($row->stores_log->all_kits[0]->kit->kit_numbers)) {
                            if (count($row->stores_log->all_kits[0]->kit->kit_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_kits[0]->kit->kit_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_kits[0]->kit->kit_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 2) {
                    } elseif ($row->type_id == 3) {
                    } elseif ($row->type_id == 4) {
                    } elseif ($row->type_id == 5) {
                    }

                    return $btn;
                })
                ->addColumn('view', function ($row) {
                    $btn = "other type";
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_parts[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_kits[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_wheels[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_tractors[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_clarks[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_equips[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    }

                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_parts) > 0) {
                                if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                    // $SaleType = $row->stores_log->all_parts[0]->pricing[0]->sale_type;
                                    // $price = $row->stores_log->all_parts[0]->pricing[0]->price;
                                     $dataArr =collect($row->stores_log->all_parts[0]->pricing);
                                     $maxObject = $dataArr->sortByDesc('price')->first();


                                    $price =$maxObject->price ;
                                    $SaleType = $maxObject->sale_type;
                                    $prices = $row->stores_log->all_parts[0]->pricing;
                                    $prices_json = json_encode($prices);
                                }

                                if (count($row->stores_log->all_parts[0]->part->part_details) > 0) {
                                    $part_specs = $row->stores_log->all_parts[0]->part->part_details;
                                    $part_specs_json = json_encode($part_specs);
                                }
                            }
                            $bigunitval = $row->stores_log->all_parts[0]->part->getbigunitval[0]->value;
                            $bigunitid = $row->stores_log->all_parts[0]->part->getbigunitval[0]->unit_id;

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_parts[0]->part->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_parts[0]->source_id) . ', ' . json_encode($row->stores_log->all_parts[0]->status_id) . ', ' . json_encode($row->stores_log->all_parts[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            // return $prices_json;
                            return $btn;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_kits) > 0) {
                                if (isset($row->stores_log->all_kits[0]->pricing)) {
                                    if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_kits[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_kits[0]->pricing[0]->price;
                                        $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_kits[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_kits[0]->kit->kit_details)) {
                                    if (count($row->stores_log->all_kits[0]->kit->kit_details) > 0) {
                                        $part_specs = $row->stores_log->all_kits[0]->kit->kit_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }
                            $bigunitval = $row->stores_log->all_parts[0]->part->getbigunitval[0]->value;
                            $bigunitid = $row->stores_log->all_parts[0]->part->getbigunitval[0]->unit_id;

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_kits[0]->kit->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_kits[0]->source_id) . ', ' . json_encode($row->stores_log->all_kits[0]->status_id) . ', ' . json_encode($row->stores_log->all_kits[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_wheels) > 0) {
                                if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                    if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_wheels[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_wheels[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_wheels[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_wheels[0]->wheel->wheel_details)) {
                                    if (count($row->stores_log->all_wheels[0]->wheel->wheel_details) > 0) {
                                        $part_specs = $row->stores_log->all_wheels[0]->wheel->wheel_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_wheels[0]->wheel->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_wheels[0]->source_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->status_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_tractors) > 0) {
                                if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                    if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_tractors[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_tractors[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_tractors[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_tractors[0]->tractor->tractor_details)) {
                                    if (count($row->stores_log->all_tractors[0]->tractor->tractor_details) > 0) {
                                        $part_specs = $row->stores_log->all_tractors[0]->tractor->tractor_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_tractors[0]->tractor->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_tractors[0]->source_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->status_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_clarks) > 0) {
                                if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                    if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_clarks[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_clarks[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_clarks[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_clarks[0]->clark->clark_details)) {
                                    if (count($row->stores_log->all_clarks[0]->clark->clark_details) > 0) {
                                        $part_specs = $row->stores_log->all_clarks[0]->clark->clark_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_clarks[0]->clark->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_clarks[0]->source_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->status_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_equips) > 0) {
                                if (isset($row->stores_log->all_equips[0]->pricing)) {
                                    if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_equips[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_equips[0]->pricing[0]->price;
                                          $dataArr =collect($row->stores_log->all_equips[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_equips[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_equips[0]->equip->equip_details)) {
                                    if (count($row->stores_log->all_equips[0]->equip->equip_details) > 0) {
                                        $part_specs = $row->stores_log->all_equips[0]->equip->equip_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_equips[0]->equip->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_equips[0]->source_id) . ', ' . json_encode($row->stores_log->all_equips[0]->status_id) . ', ' . json_encode($row->stores_log->all_equips[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    }
                    return '';
                })
                ->addColumn('send', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;
                        $ratiounit = getSmallUnit($row->stores_log->all_parts[0]->part->bigunit->id, $row->stores_log->all_parts[0]->part->smallunit->id);

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                             $amount . ', ' .
                            $ratiounit . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    }

                    return $btn;
                })
                

                ->rawColumns(['send', 'view', 'name', 'source', 'status', 'quality', 'EmaraNumber', 'amount', 'section', 'price', 'partnumbers', 'action'])
                ->setTotalRecords(20)
                // ->filter(function ($query) {
                //     return $query;
                // })
                ->make(true);
        }
        // return
        // return view('testpos');
    }


    public function posSearch(Request $request)
    {
        $storeId = $request->storeId;
        // $storeId = 5;

        $store_data = Store::where('id', '=', $storeId)->get();

        $allStores = Store::all();

        $allBrands = Brand::all();
        $allGroups = Group::all();
        $alltaxes = Tax::all();
        $allSuppliers = Supplier::all();

        $clients = Client::with(['invoices.refund_invoices', 'invoice_client_madyoneas'])
            ->get()
            ->map(function ($client) {
                $client->refund_invoices_sum = $client->invoices->sum(function ($invoice) {
                    return $invoice->refund_invoices->sum('item_price');
                });
                return $client;
            });
        foreach ($clients as $key => $client) {
            $as_sup_madunia = 0;
            // return $client->sup_id;
            if ($client->sup_id) {
                $xxs = Supplier::where('id', $client->sup_id)->first();
                $as_sup_madunia = 0;
                if ($xxs) {
                    $as_sup_madunia = $xxs->raseed;
                } else {
                    $as_sup_madunia = 0;
                }

                $rassedAll = new SupplierHesapController();
                $clientData=$rassedAll->getRassedAll('all',$client->sup_id);
                 $client['egmal'] = $clientData['rassed'];
                 $client['message'] = $clientData['message'];
            }else{
                 $rassedAll = new SupplierHesapController();
                $clientData=$rassedAll->getRassedAll('client',$client->id);
                 $client['egmal'] = $clientData['rassed'];
                 $client['message'] = $clientData['message'];
            }
            $servicesMad = ServiceInvoice::where('client_id', $client->id)->sum('remain');
            $client['as_sup_madunia'] = $as_sup_madunia;
            $client['servicesMad'] = $servicesMad;
        }


        $allSections = StoreStructure::where('store_id', $storeId)
            ->with([
                'store_sections' => function ($q) {
                    $q->where('type_id', 1)->with('part')->with('source')->with('type')->with('status')->with('part_quality')->get();
                    $q->where('type_id', 2)->with('wheel')->with('source')->with('type')->with('status')->with('part_quality')->get();
                    $q->where('type_id', 3)->with('tractor')->with('source')->with('type')->with('status')->with('part_quality')->get();
                    $q->where('type_id', 4)->with('clark')->with('source')->with('type')->with('status')->with('part_quality')->get();
                    $q->where('type_id', 5)->with('equip')->with('source')->with('type')->with('status')->with('part_quality')->get();
                    $q->where('type_id', 6)->with('kit')->with('source')->with('type')->with('status')->with('part_quality')->get();
                },
            ])
            ->get();



        $allClients = Client::with('invoices')->with('invoice_client_madyoneas')->get();

        foreach($allClients as $cli){
            if ($cli->sup_id) {
                $rassedAll = new SupplierHesapController();
                $clientData=$rassedAll->getRassedAll('all',$cli->sup_id);
                $cli['egmal'] = $clientData['rassed'];
                $cli['message'] = $clientData['message'];
            }else{
                $rassedAll = new SupplierHesapController();
                $clientData=$rassedAll->getRassedAll('client',$cli->id);
                $cli['egmal'] = $clientData['rassed'];
                $cli['message'] = $clientData['message'];
            }

        }
        $allGroups = Group::all();
        $allSGroups = SubGroup::all();
        $Btype = BrandType::all();
        $allbrand = Brand::all();
        $allmodel = Model::all();
        $allseries = Series::all();
        $allprices = PricingType::all();
        $this->storeIdd = $storeId;
        $store_inbox = $this->store_inbox($storeId);

        $bank_types = BankType::all();
        $store_safe = Store::where('safe_accountant_number', '<>', '0')->get();

        return view('posSearch', compact('allStores','allSuppliers', 'store_inbox', 'allSections', 'allClients', 'store_data', 'clients', 'allprices', 'alltaxes', 'bank_types', 'store_safe', 'allGroups', 'allSGroups', 'Btype', 'allbrand', 'allmodel', 'allseries'));
    }
    public function allDataForId(Request $request)
    {

        if ($request->ajax()) {

            ////           Get Store Entity /////////////////
            $store = Store::where('id', $request->storeId)->first();

            $entity_tbl = 'App\\Models\\' . ucfirst($store->table_name);
            if ($store->table_name == 'damaged_parts') {
                $entity_tbl = 'damagedPart';
            }

            /*********************************************/
            // return $entity_tbl;


            $query = '';
            if ($request->typeId == 1) {


                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_parts.part_id, all_parts.source_id, all_parts.status_id, all_parts.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_parts.part_id', 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id',  $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_parts.part.part_numbers',
                        'stores_log.all_parts.part.part_images',
                        'stores_log.all_parts.part.getsmallunit.unit',
                        'stores_log.all_parts.part.smallunit',
                        'stores_log.all_parts.part.bigunit',
                        'stores_log.all_parts.source',
                        'stores_log.all_parts.status',
                        'stores_log.all_parts.part_quality',
                        'stores_log.all_parts.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_parts.sectionswithoutorder.store',
                        'stores_log.all_parts.sectionswithoutorder.store_structure',
                        'stores_log.all_parts.pricing.sale_type',
                        'stores_log.all_parts.order_supplier.buy_transaction',
                        'stores_log.all_parts.part.sub_group.group',
                        'stores_log.all_parts.part.part_details.part_spec',
                        'stores_log.all_parts.part.part_models.series.model.brand',
                        'stores_log.all_parts.part.part_images',
                    ])
                    ->get();


            } elseif ($request->typeId == 6) {
                $query = $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_kits.part_id, all_kits.source_id, all_kits.status_id, all_kits.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_kits.part_id', 'all_kits.source_id', 'all_kits.status_id', 'all_kits.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_kits.kit.kit_numbers',
                        'stores_log.all_kits.kit.kit_images',
                        'stores_log.all_kits.source',
                        'stores_log.all_kits.status',
                        'stores_log.all_kits.part_quality',
                        'stores_log.all_kits.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_kits.sectionswithoutorder.store',
                        'stores_log.all_kits.sectionswithoutorder.store_structure',
                        'stores_log.all_kits.pricing.sale_type',
                        'stores_log.all_kits.order_supplier.buy_transaction',
                        'stores_log.all_kits.kit.kit_details.kit_spec',
                        'stores_log.all_kits.kit.kit_models.series.model.brand',
                        'stores_log.all_kits.kit.kit_images',
                    ])
                    ->get();
            } elseif ($request->typeId == 2) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_wheels.part_id, all_wheels.source_id, all_wheels.status_id, all_wheels.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_wheels.part_id', 'all_wheels.source_id', 'all_wheels.status_id', 'all_wheels.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_wheels.wheel.wheel_images',
                        'stores_log.all_wheels.source',
                        'stores_log.all_wheels.status',
                        'stores_log.all_wheels.part_quality',
                        'stores_log.all_wheels.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_wheels.sectionswithoutorder.store',
                        'stores_log.all_wheels.sectionswithoutorder.store_structure',
                        'stores_log.all_wheels.pricing.sale_type',
                        'stores_log.all_wheels.order_supplier.buy_transaction',
                        'stores_log.all_wheels.wheel.wheel_details.wheel_spec',
                        'stores_log.all_wheels.wheel.wheel_model',
                        'stores_log.all_wheels.wheel.wheel_images',
                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 3) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_tractors', 'stores_log.All_part_id', '=', 'all_tractors.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_tractors.part_id, all_tractors.source_id, all_tractors.status_id, all_tractors.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_tractors.part_id', 'all_tractors.source_id', 'all_tractors.status_id', 'all_tractors.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_tractors.tractor.tractor_images',
                        'stores_log.all_tractors.source',
                        'stores_log.all_tractors.status',
                        'stores_log.all_tractors.part_quality',
                        'stores_log.all_tractors.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_tractors.sectionswithoutorder.store',
                        'stores_log.all_tractors.sectionswithoutorder.store_structure',
                        'stores_log.all_tractors.pricing.sale_type',
                        'stores_log.all_tractors.order_supplier.buy_transaction',
                        'stores_log.all_tractors.tractor.tractor_details.tractor_spec',
                        'stores_log.all_tractors.tractor.tractor_images',



                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 4) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_clarks', 'stores_log.All_part_id', '=', 'all_clarks.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_clarks.part_id, all_clarks.source_id, all_clarks.status_id, all_clarks.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_clarks.part_id', 'all_clarks.source_id', 'all_clarks.status_id', 'all_clarks.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_clarks.clark.clark_images',
                        'stores_log.all_clarks.source',
                        'stores_log.all_clarks.status',
                        'stores_log.all_clarks.part_quality',
                        'stores_log.all_clarks.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_clarks.sectionswithoutorder.store',
                        'stores_log.all_clarks.sectionswithoutorder.store_structure',
                        'stores_log.all_clarks.pricing.sale_type',
                        'stores_log.all_clarks.order_supplier.buy_transaction',
                        'stores_log.all_clarks.clark.clark_details.clark_spec',
                        'stores_log.all_clarks.clark.clark_images',
                    ])
                    ->get();
                // Merge all queries
            } elseif ($request->typeId == 5) {
                $query =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_equips', 'stores_log.All_part_id', '=', 'all_equips.id')
                    ->where($store->table_name . '.part_id', $request->PartID)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_equips.part_id, all_equips.source_id, all_equips.status_id, all_equips.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_equips.part_id', 'all_equips.source_id', 'all_equips.status_id', 'all_equips.quality_id', $store->table_name . '.type_id')
                    ->with([
                        'stores_log.all_equips.equip.equip_images',
                        'stores_log.all_equips.source',
                        'stores_log.all_equips.status',
                        'stores_log.all_equips.part_quality',
                        'stores_log.all_equips.sectionswithoutorder' => function ($query)  use ($store) {
                            $query->where('store_id', $store->id);
                        },
                        'stores_log.all_equips.sectionswithoutorder.store',
                        'stores_log.all_equips.sectionswithoutorder.store_structure',
                        'stores_log.all_equips.pricing.sale_type',
                        'stores_log.all_equips.order_supplier.buy_transaction',
                        'stores_log.all_equips.equip.equip_details.equip_spec',
                        'stores_log.all_equips.equip.equip_images',
                    ])
                    ->get();
                // Merge all queries
            }
            // return $query;
            foreach ($query as $ele) {
                # code...
                $ele['stores_amount']=$this->PartInStoresCount($ele->part_id , $ele->source_id, $ele->status_id, $ele->quality_id, $ele->type_id);
            }



            return FacadesDataTables::of($query)
                // ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    $btn = 'No Name';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->kit->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->wheel->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->tractor->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->clark->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->equip->name;
                        } else {
                            $btn = 'No Name';
                        }
                        return $btn;
                    }
                })
                ->addColumn('Image', function ($row) {
                    $btn = 'No Image';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if(isset($row->stores_log->all_parts[0]->part->part_images) && count($row->stores_log->all_parts[0]->part->part_images)>0){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/part_images/'.$row->stores_log->all_parts[0]->part->part_images[0]->image_name.'" alt="">';
                                }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {

                            if(isset($row->stores_log->all_kits[0]->kit->kit_images) && count($row->stores_log->all_kits[0]->kit->kit_images)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/kit_images/'.$row->stores_log->all_kits[0]->kit->kit_images[0]->image_name.'" alt="">';
                            }

                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {

                            if(isset($row->stores_log->all_wheels[0]->wheel->wheel_images)  && count($row->stores_log->all_wheels[0]->wheel->wheel_images)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/wheel_images/'.$row->stores_log->all_wheels[0]->wheel->wheel_images[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if(isset($row->stores_log->all_tractors[0]->tractor->tractor_image) && count($row->stores_log->all_tractors[0]->tractor->tractor_image)>0 ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/tractor_images/'.$row->stores_log->all_tractors[0]->tractor->tractor_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if(isset($row->stores_log->all_clarks[0]->clarck->clarck_image)  && count($row->stores_log->all_clarks[0]->clarck->clarck_image)>0  ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/clarck_images/'.$row->stores_log->all_clarks[0]->clarck->clarck_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {

                            if(isset($row->stores_log->all_equips[0]->equip->equip_image) && count($row->stores_log->all_equips[0]->equip->equip_image)>0  ){
                                $btn = '<img class="rounded-circle header-profile-user" src="assets/equip_images/'.$row->stores_log->all_equips[0]->equip->equip_image[0]->image_name.'" alt="">';

                            }
                        } else {
                            $btn = '<img src="" alt="">';
                        }
                        return $btn;
                    }
                })
                ->addColumn('source', function ($row) {
                    $btn = 'No Source';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->source->name_arabic;
                        } else {
                            $btn = 'no source';
                        }
                    }


                    return $btn;
                })
                ->addColumn('status', function ($row) {
                    $btn = 'No Status';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->status->name;
                        } else {
                            $btn = 'No status';
                        }
                    }

                    return $btn;
                })
                ->addColumn('quality', function ($row) {
                    $btn = 'No Quality';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = $row->stores_log->all_parts[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = $row->stores_log->all_kits[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = $row->stores_log->all_wheels[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = $row->stores_log->all_tractors[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = $row->stores_log->all_clarks[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = $row->stores_log->all_equips[0]->part_quality->name;
                        } else {
                            $btn = 'No part Quality';
                        }
                    }
                    return $btn;
                })
                ->addColumn('amount', function ($row) {
                    $btn = 0;
                    if ($row->amount > 0) {
                        if($row->stores_log->all_parts[0]->part->bigunit){
                            $ratiounit = getSmallUnit($row->stores_log->all_parts[0]->part->bigunit->id, $row->stores_log->all_parts[0]->part->smallunit->id);
                            $btn = $row->amount / $ratiounit .' / '.$row->stores_log->all_parts[0]->part->bigunit->name;
                        }else{
                            $btn = $row->amount;
                        }
                        
                    } else {
                        $btn = 0;
                    }
                    return $btn;
                })
                ->addColumn('section', function ($row) {
                    $btn = '';
                    $secArr = [];
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->sectionswithoutorder) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->sectionswithoutorder); $i++) {
                                    if (in_array($row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->amount <= 0) {


                                    } else {

                                        $btn .= '<li>' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->amount  . '</span></li>';
                                        array_push($secArr, $row->stores_log->all_parts[0]->sectionswithoutorder[$i]->store_structure->id);
                                    }
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->sectionswithoutorder)) {
                                if (count($row->stores_log->all_kits[0]->sectionswithoutorder) > 0) {
                                    for ($i = 0; $i < count($row->stores_log->all_kits[0]->sectionswithoutorder); $i++) {
                                        if (in_array($row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->amount <= 0) {
                                        } else {

                                            $btn .= '<li>' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                            array_push($secArr, $row->stores_log->all_kits[0]->sectionswithoutorder[$i]->store_structure->id);
                                        }
                                    }
                                }
                            } else {
                                $btn = "No Section";
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (isset($row->stores_log->all_wheels[0]->sectionswithoutorder)) {
                                if (count($row->stores_log->all_wheels[0]->sectionswithoutorder) > 0) {
                                    for ($i = 0; $i < count($row->stores_log->all_wheels[0]->sectionswithoutorder); $i++) {
                                        if (in_array($row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->id, $secArr) || $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->amount <= 0) {
                                        } else {

                                            $btn .= '<li>' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->name . '<span class="d-none"> / ' . $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->amount . '</span></li>';
                                            array_push($secArr, $row->stores_log->all_wheels[0]->sectionswithoutorder[$i]->store_structure->id);
                                        }
                                    }
                                }
                            } else {
                                $btn = "No Section";
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (count($row->stores_log->all_tractors[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_tractors[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_tractors[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_tractors[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (count($row->stores_log->all_clarks[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_clarks[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_clarks[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_clarks[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (count($row->stores_log->all_equips[0]->sections) > 0) {
                                for ($i = 0; $i < count($row->stores_log->all_equips[0]->sections); $i++) {
                                    $btn .= '<li>' . $row->stores_log->all_equips[0]->sections[$i]->store_structure->name . ' / ' . $row->stores_log->all_equips[0]->sections[$i]->amount . '</li>';
                                }
                            }
                        } else {
                            $btn = "No Section";
                        }
                    }
                    return $btn;
                })
                ->addColumn('price', function ($row) {
                    $btn = 0;
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                $dataArr =collect($row->stores_log->all_parts[0]->pricing);


                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;
                                    if(count($filteredItems)>0){
                                        $ratiounit = 1;
                                        if($row->stores_log->all_parts[0]->part->bigunit){
                                            $ratiounit = getSmallUnit($row->stores_log->all_parts[0]->part->bigunit->id, $row->stores_log->all_parts[0]->part->smallunit->id);
                                        }
                                            
                                            $btn =$filteredItems[0]['price'] * $ratiounit;
                                    } else {
                                        $btn = 0;
                                    }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            if (isset($row->stores_log->all_kits[0]->pricing)) {
                                if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                    $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                    $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                    $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                      $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            if (isset($row->stores_log->all_equips[0]->pricing)) {
                                if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                     $dataArr =collect($row->stores_log->all_equips[0]->pricing);
                                        $filteredItems1 = $dataArr->filter(function ($item) {
                                                        return $item->sale_type == 5; // Condition
                                                    })->values()->toArray();
                                        $filteredItems = (array) $filteredItems1;

                                    if(count($filteredItems)>0){
                                        $btn =$filteredItems[0]['price'] ;
                                    } else {
                                        $btn = 0;
                                    }
                                } else {
                                    $btn = 0;
                                }
                            } else {
                                $btn = 0;
                            }
                        } else {
                            $btn = 0;
                        }
                    }

                    return $btn;
                })

                ->addColumn('partnumbers', function ($row) {
                    $btn = 'no num';
                    if ($row->type_id == 1) {
                        if (isset($row->stores_log->all_parts[0]->part->part_numbers)) {
                            if (count($row->stores_log->all_parts[0]->part->part_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_parts[0]->part->part_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_parts[0]->part->part_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 6) {
                        if (isset($row->stores_log->all_kits[0]->kit->kit_numbers)) {
                            if (count($row->stores_log->all_kits[0]->kit->kit_numbers) > 0) {
                                $btnx = '<ul>';
                                for ($i = 0; $i < count($row->stores_log->all_kits[0]->kit->kit_numbers); $i++) {
                                    $btnx .= '<li>' . $row->stores_log->all_kits[0]->kit->kit_numbers[$i]->number . '</li>';
                                }
                                $btnx .= '</ul>'; // Close the unordered list
                                $btn = $btnx; // Assign the generated list to $btn
                            } else {
                                $btn = "No numbers";
                            }
                        } else {
                            $btn = "No numbers";
                        }
                    } elseif ($row->type_id == 2) {
                    } elseif ($row->type_id == 3) {
                    } elseif ($row->type_id == 4) {
                    } elseif ($row->type_id == 5) {
                    }

                    return $btn;
                })
                ->addColumn('stores_amount', function ($row) {
                    $btn = '<ul class="list-group fs-13 text-truncate">';
                    if(isset($row->stores_amount)){
                        for ($i=0; $i < count($row->stores_amount) ; $i++) {
                            $btn .='<li>'.$row->stores_amount[$i]->name.'/'.$row->stores_amount[$i]->storepartCount.'</li>';

                        }
                    }else{
                        $btn .='<li>لا يوجد بالمخازن</li>';

                    }

                    $btn .= '</ul>';
                    return  $btn;

                 })
                ->addColumn('view', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_parts[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_kits[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_wheels[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_tractors[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_clarks[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $btn = '<span style="cursor: pointer;"
                        onclick="CardInfo(' . $row->stores_log->all_equips[0]->id . ',' . $row->type_id . ' )" class="fs-2 mdi mdi-eye px-2 text-secondary"></span>';
                        } else {
                            $btn = "other type";
                        }
                    }

                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        if (count($row->stores_log->all_parts) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_parts) > 0) {
                                if (count($row->stores_log->all_parts[0]->pricing) > 0) {
                                    // $price = $row->stores_log->all_parts[0]->pricing[0]->price;

                                     $dataArr =collect($row->stores_log->all_parts[0]->pricing);
                                     $maxObject = $dataArr->sortByDesc('price')->first();


                                    $price =$maxObject->price ;
                                    $SaleType = $maxObject->sale_type;

                                    $prices = $row->stores_log->all_parts[0]->pricing;
                                    $prices_json = json_encode($prices);
                                }

                                if (count($row->stores_log->all_parts[0]->part->part_details) > 0) {
                                    $part_specs = $row->stores_log->all_parts[0]->part->part_details;
                                    $part_specs_json = json_encode($part_specs);
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_parts[0]->part->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_parts[0]->source_id) . ', ' . json_encode($row->stores_log->all_parts[0]->status_id) . ', ' . json_encode($row->stores_log->all_parts[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 6) {
                        if (count($row->stores_log->all_kits) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_kits) > 0) {
                                if (isset($row->stores_log->all_kits[0]->pricing)) {
                                    if (count($row->stores_log->all_kits[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_kits[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_kits[0]->pricing[0]->price;
                                        $dataArr =collect($row->stores_log->all_kits[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;

                                        $prices = $row->stores_log->all_kits[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_kits[0]->kit->kit_details)) {
                                    if (count($row->stores_log->all_kits[0]->kit->kit_details) > 0) {
                                        $part_specs = $row->stores_log->all_kits[0]->kit->kit_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_kits[0]->kit->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_kits[0]->source_id) . ', ' . json_encode($row->stores_log->all_kits[0]->status_id) . ', ' . json_encode($row->stores_log->all_kits[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 2) {
                        if (count($row->stores_log->all_wheels) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_wheels) > 0) {
                                if (isset($row->stores_log->all_wheels[0]->pricing)) {
                                    if (count($row->stores_log->all_wheels[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_wheels[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_wheels[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_wheels[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_wheels[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_wheels[0]->wheel->wheel_details)) {
                                    if (count($row->stores_log->all_wheels[0]->wheel->wheel_details) > 0) {
                                        $part_specs = $row->stores_log->all_wheels[0]->wheel->wheel_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_wheels[0]->wheel->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_wheels[0]->source_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->status_id) . ', ' . json_encode($row->stores_log->all_wheels[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 3) {
                        if (count($row->stores_log->all_tractors) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_tractors) > 0) {
                                if (isset($row->stores_log->all_tractors[0]->pricing)) {
                                    if (count($row->stores_log->all_tractors[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_tractors[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_tractors[0]->pricing[0]->price;
                                          $dataArr =collect($row->stores_log->all_tractors[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_tractors[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_tractors[0]->tractor->tractor_details)) {
                                    if (count($row->stores_log->all_tractors[0]->tractor->tractor_details) > 0) {
                                        $part_specs = $row->stores_log->all_tractors[0]->tractor->tractor_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_tractors[0]->tractor->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_tractors[0]->source_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->status_id) . ', ' . json_encode($row->stores_log->all_tractors[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 4) {
                        if (count($row->stores_log->all_clarks) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_clarks) > 0) {
                                if (isset($row->stores_log->all_clarks[0]->pricing)) {
                                    if (count($row->stores_log->all_clarks[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_clarks[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_clarks[0]->pricing[0]->price;
                                         $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_clarks[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_clarks[0]->clark->clark_details)) {
                                    if (count($row->stores_log->all_clarks[0]->clark->clark_details) > 0) {
                                        $part_specs = $row->stores_log->all_clarks[0]->clark->clark_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_clarks[0]->clark->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_clarks[0]->source_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->status_id) . ', ' . json_encode($row->stores_log->all_clarks[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ',1, ' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    } elseif ($row->type_id == 5) {
                        if (count($row->stores_log->all_equips) > 0) {
                            $SaleType = 0;
                            $price = 0;
                            $prices_json = 0;
                            $part_specs_json = 0;

                            if (count($row->stores_log->all_equips) > 0) {
                                if (isset($row->stores_log->all_equips[0]->pricing)) {
                                    if (count($row->stores_log->all_equips[0]->pricing) > 0) {
                                        // $SaleType = $row->stores_log->all_equips[0]->pricing[0]->sale_type;
                                        // $price = $row->stores_log->all_equips[0]->pricing[0]->price;
                                        $dataArr =collect($row->stores_log->all_clarks[0]->pricing);
                                         $maxObject = $dataArr->sortByDesc('price')->first();


                                        $price =$maxObject->price ;
                                        $SaleType = $maxObject->sale_type;
                                        $prices = $row->stores_log->all_equips[0]->pricing;
                                        $prices_json = json_encode($prices);
                                    }
                                }
                                if (isset($row->stores_log->all_equips[0]->equip->equip_details)) {
                                    if (count($row->stores_log->all_equips[0]->equip->equip_details) > 0) {
                                        $part_specs = $row->stores_log->all_equips[0]->equip->equip_details;
                                        $part_specs_json = json_encode($part_specs);
                                    }
                                }
                            }

                            $Tamount = $row->amount;
                            $type_id = $row->type_id;
                            $name = json_encode($row->stores_log->all_equips[0]->equip->name); // Safely encode the name

                            $btn = '<div class="input-group mb-3 addBtn">
                                    <input type="number" class="form-control itemAmunt"
                                        placeholder="Amount" value="1" aria-label="Username"
                                        aria-describedby="basic-addon1" style="width: 50% !important;">
                                    <button class="btn fs-2  text-secondary" style="background-color:#a8e4f2"
                                        onclick=\'addtoInvoice(this, ' . $part_specs_json . ', ' . json_encode($row->part_id) . ', ' . $name . ', ' . json_encode($row->stores_log->all_equips[0]->source_id) . ', ' . json_encode($row->stores_log->all_equips[0]->status_id) . ', ' . json_encode($row->stores_log->all_equips[0]->quality_id) . ', ' . $SaleType . ', ' . $price . ', ' . $Tamount . ', ' . $type_id . ', 1,' . $prices_json . ')\'>
                                        <i class="fs-22 mdi mdi-cart-variant"></i>
                                    </button>
                                </div>';
                            return $btn;
                        }
                    }
                    return '';
                })
                ->addColumn('send', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;
                        $ratiounit = getSmallUnit($row->stores_log->all_parts[0]->part->bigunit->id, $row->stores_log->all_parts[0]->part->smallunit->id);
                        $ratiounit_name = addslashes($row->stores_log->all_parts[0]->part->bigunit->name);

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $ratiounit . ', \'' .
                            $ratiounit_name . '\', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="SendToStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-share px-2 text-secondary"></span>';
                    }

                    return $btn;
                })
                 ->addColumn('ask', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;
                        $ratiounit = getSmallUnit($row->stores_log->all_parts[0]->part->bigunit->id, $row->stores_log->all_parts[0]->part->smallunit->id);
                        $ratiounit_name = addslashes($row->stores_log->all_parts[0]->part->bigunit->name);

                        $btn = '<span style="cursor: pointer;" onclick="askStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $ratiounit . ', \'' .
                            $ratiounit_name . '\', ' .
                            $typeId . ')" class="fs-2 mdi mdi-download px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="askStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-download px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="askStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-download px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="askStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-download px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="askStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-download px-2 text-secondary"></span>';
                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<span style="cursor: pointer;" onclick="askStoreNew(' .
                            htmlspecialchars($secc, ENT_QUOTES, 'UTF-8') . ', ' .
                            $partId . ', ' .
                            $sourceId . ', ' .
                            $statusId . ', ' .
                            $partQualityId . ', \'' .
                            $partName . '\', ' .
                            $amount . ', ' .
                            $typeId . ')" class="fs-2 mdi mdi-download px-2 text-secondary"></span>';
                    }

                    return $btn;
                })
                 ->addColumn('talef', function ($row) {
                    $btn = '';
                    if ($row->type_id == 1) {
                        $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_parts[0]->part->id;
                        $sourceId = $row->stores_log->all_parts[0]->source->id;
                        $statusId = $row->stores_log->all_parts[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';


                    } elseif ($row->type_id == 6) {
                        $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_kits[0]->kit->id;
                        $sourceId = $row->stores_log->all_kits[0]->source->id;
                        $statusId = $row->stores_log->all_kits[0]->status->id;
                        $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                        $store_id = $row->stores_log->store_id;
                        $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 2) {

                        $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_wheels[0]->wheel->id;
                        $sourceId = $row->stores_log->all_wheels[0]->source->id;
                        $statusId = $row->stores_log->all_wheels[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 3) {

                        $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_tractors[0]->tractor->id;
                        $sourceId = $row->stores_log->all_tractors[0]->source->id;
                        $statusId = $row->stores_log->all_tractors[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 4) {

                        $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_clarks[0]->clark->id;
                        $sourceId = $row->stores_log->all_clarks[0]->source->id;
                        $statusId = $row->stores_log->all_clarks[0]->status->id;
                        $store_id = $row->stores_log->store_id;
                        $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                        $partId . '/' .
                        $sourceId . '/' .
                        $statusId . '/' .
                        $partQualityId . '/' .
                        $typeId . '/' .
                        $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    } elseif ($row->type_id == 5) {

                        $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                        $partId = $row->stores_log->all_equips[0]->equip->id;
                        $sourceId = $row->stores_log->all_equips[0]->source->id;
                        $store_id = $row->stores_log->store_id;
                        $statusId = $row->stores_log->all_equips[0]->status->id;
                        $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                        $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                        $amount = $row->amount;
                        $typeId = $row->type_id;

                        $btn = '<a style="cursor: pointer;" href="talef/' .
                            $partId . '/' .
                            $sourceId . '/' .
                            $statusId . '/' .
                            $partQualityId . '/' .
                            $typeId . '/' .
                            $store_id . '" class="fs-2 mdi mdi-delete px-2 text-secondary"></a>';

                    }

                    return $btn;
                })
                //  ->addColumn('talabea', function ($row) {
                //     $btn = '';
                //     if ($row->type_id == 1) {
                //         $secc = json_encode($row->stores_log->all_parts[0]->sectionswithoutorder);
                //         $partId = $row->stores_log->all_parts[0]->part->id;
                //         $sourceId = $row->stores_log->all_parts[0]->source->id;
                //         $statusId = $row->stores_log->all_parts[0]->status->id;
                //         $store_id = $row->stores_log->store_id;
                //         $partQualityId = $row->stores_log->all_parts[0]->part_quality->id;
                //         $partName = addslashes($row->stores_log->all_parts[0]->part->name);
                //         $amount = $row->amount;
                //         $typeId = $row->type_id;

                //         $btn = '<button style="cursor: pointer;" onclick="addToTalabea('.$typeId.','.$partId.','.$sourceId.','.$statusId.','.$partQualityId.')" class="fs-2 mdi mdi-plus px-2 text-secondary"></button>';


                //     } elseif ($row->type_id == 6) {
                //         $secc = json_encode($row->stores_log->all_kits[0]->sectionswithoutorder);
                //         $partId = $row->stores_log->all_kits[0]->kit->id;
                //         $sourceId = $row->stores_log->all_kits[0]->source->id;
                //         $statusId = $row->stores_log->all_kits[0]->status->id;
                //         $partQualityId = $row->stores_log->all_kits[0]->part_quality->id;
                //         $store_id = $row->stores_log->store_id;
                //         $partName = addslashes($row->stores_log->all_kits[0]->kit->name);
                //         $amount = $row->amount;
                //         $typeId = $row->type_id;

                //         $btn = '<button style="cursor: pointer;" onclick="addToTalabea('.$typeId.','.$partId.','.$sourceId.','.$statusId.','.$partQualityId.')" class="fs-2 mdi mdi-plus px-2 text-secondary"></button>';


                //     } elseif ($row->type_id == 2) {

                //         $secc = json_encode($row->stores_log->all_wheels[0]->sectionswithoutorder);
                //         $partId = $row->stores_log->all_wheels[0]->wheel->id;
                //         $sourceId = $row->stores_log->all_wheels[0]->source->id;
                //         $statusId = $row->stores_log->all_wheels[0]->status->id;
                //         $store_id = $row->stores_log->store_id;
                //         $partQualityId = $row->stores_log->all_wheels[0]->part_quality->id;
                //         $partName = addslashes($row->stores_log->all_wheels[0]->wheel->name);
                //         $amount = $row->amount;
                //         $typeId = $row->type_id;

                //         $btn = '<button style="cursor: pointer;" onclick="addToTalabea('.$typeId.','.$partId.','.$sourceId.','.$statusId.','.$partQualityId.')" class="fs-2 mdi mdi-plus px-2 text-secondary"></button>';


                //     } elseif ($row->type_id == 3) {

                //         $secc = json_encode($row->stores_log->all_tractors[0]->sectionswithoutorder);
                //         $partId = $row->stores_log->all_tractors[0]->tractor->id;
                //         $sourceId = $row->stores_log->all_tractors[0]->source->id;
                //         $statusId = $row->stores_log->all_tractors[0]->status->id;
                //         $store_id = $row->stores_log->store_id;
                //         $partQualityId = $row->stores_log->all_tractors[0]->part_quality->id;
                //         $partName = addslashes($row->stores_log->all_tractors[0]->tractor->name);
                //         $amount = $row->amount;
                //         $typeId = $row->type_id;

                //         $btn = '<button style="cursor: pointer;" onclick="addToTalabea('.$typeId.','.$partId.','.$sourceId.','.$statusId.','.$partQualityId.')" class="fs-2 mdi mdi-plus px-2 text-secondary"></button>';


                //     } elseif ($row->type_id == 4) {

                //         $secc = json_encode($row->stores_log->all_clarks[0]->sectionswithoutorder);
                //         $partId = $row->stores_log->all_clarks[0]->clark->id;
                //         $sourceId = $row->stores_log->all_clarks[0]->source->id;
                //         $statusId = $row->stores_log->all_clarks[0]->status->id;
                //         $store_id = $row->stores_log->store_id;
                //         $partQualityId = $row->stores_log->all_clarks[0]->part_quality->id;
                //         $partName = addslashes($row->stores_log->all_clarks[0]->clark->name);
                //         $amount = $row->amount;
                //         $typeId = $row->type_id;

                //         $btn = '<button style="cursor: pointer;" onclick="addToTalabea('.$typeId.','.$partId.','.$sourceId.','.$statusId.','.$partQualityId.')" class="fs-2 mdi mdi-plus px-2 text-secondary"></button>';


                //     } elseif ($row->type_id == 5) {

                //         $secc = json_encode($row->stores_log->all_equips[0]->sectionswithoutorder);
                //         $partId = $row->stores_log->all_equips[0]->equip->id;
                //         $sourceId = $row->stores_log->all_equips[0]->source->id;
                //         $store_id = $row->stores_log->store_id;
                //         $statusId = $row->stores_log->all_equips[0]->status->id;
                //         $partQualityId = $row->stores_log->all_equips[0]->part_quality->id;
                //         $partName = addslashes($row->stores_log->all_equips[0]->equip->name);
                //         $amount = $row->amount;
                //         $typeId = $row->type_id;

                //         $btn = '<button style="cursor: pointer;" onclick="addToTalabea('.$typeId.','.$partId.','.$sourceId.','.$statusId.','.$partQualityId.')" class="fs-2 mdi mdi-plus px-2 text-secondary"></button>';


                //     }

                //     return $btn;
                // })
                ->rawColumns(['stores_amount','view','Image' ,'name', 'source', 'status', 'quality', 'amount', 'section', 'price', 'partnumbers', 'action', 'send', 'ask','talef'])
                ->setTotalRecords(20)
                // ->filter(function ($query) {
                //     return $query;
                // })
                ->make(true);
        }
        // return view('testpos');
    }



    public function searchAll(Request $request)
    {
        // return $searchkey;
        $searchkey = urldecode($request->q);

        $parts = Part::where('name', 'LIKE', '%' . $searchkey . '%')
            ->orWhereHas('part_numbers', function ($query) use ($searchkey) {
                $query->where('number', 'LIKE', '%' . $searchkey . '%');
            })->orWhereHas('part_details', function ($query) use ($searchkey) {
                $query->where('value', 'LIKE', '%' . $searchkey . '%')->with('part_spec');
            })->orWhereHas('part_models.series.model', function ($query) use ($searchkey) {
                $query->where('name', 'LIKE', '%' . $searchkey . '%');
            })->orWhereHas('all_parts', function ($query) use ($searchkey) {
                $query->whereRaw('CONCAT("FN1", part_id, source_id, status_id, quality_id) LIKE ?', ["%$searchkey%"]);
            })->with('part_numbers')->with('part_details')->with('part_models.series.model')->addSelect('*', DB::raw("'Part' as type"), DB::raw("'1' as type_id"))->paginate(10);


        $kits = Kit::where('name', 'LIKE', '%' . $searchkey . '%')->orWhereHas('kit_numbers', function ($query) use ($searchkey) {
            $query->where('number', 'LIKE', '%' . $searchkey . '%');
        })->orWhereHas('kit_details', function ($query) use ($searchkey) {
            $query->where('value', 'LIKE', '%' . $searchkey . '%')->with('kit_spec');
        })->orWhereHas('all_kits', function ($query) use ($searchkey) {
            $query->whereRaw('CONCAT("FN6", part_id, source_id, status_id, quality_id)  LIKE ?', ["%$searchkey%"]);
        })->with('kit_numbers')->with('kit_details')->addSelect('*', DB::raw("'Kit' as type"), DB::raw("'6' as type_id"))->paginate(10);

        $wheels = Wheel::where('name', 'LIKE', '%' . $searchkey . '%')->orWhereHas('wheel_material', function ($query) use ($searchkey) {
            $query->where('name', 'LIKE', '%' . $searchkey . '%');
        })->orWhereHas('wheel_dimension', function ($query) use ($searchkey) {
            $query->where('dimension', 'LIKE', '%' . $searchkey . '%');
        })
            ->orwhere('wheel_container_size', 'LIKE', '%' . $searchkey . '%')
            ->orWhereHas('all_wheels', function ($query) use ($searchkey) {
                $query->whereRaw('CONCAT("FN2", part_id, source_id, status_id, quality_id)  LIKE ?', ["%$searchkey%"]);
            })->with('wheel_material')->with('wheel_dimension')->addSelect('*', DB::raw("'Wheel' as type"), DB::raw("'2' as type_id"))->paginate(10);

        //  return $searchkey;
        $tractors = Tractor::where('name', 'LIKE', '%' . $searchkey . '%')->orwhere('tractor_number', 'LIKE', '%' . $searchkey . '%')
            ->orwhere('motornumber', 'LIKE', '%' . $searchkey . '%')
            ->orWhereHas('all_tractors', function ($query) use ($searchkey) {
                $query->whereRaw('CONCAT("FN3", part_id, source_id, status_id, quality_id)  LIKE ?', ["%$searchkey%"]);
            })->addSelect('*', DB::raw("'Tractor' as type"), DB::raw("'3' as type_id"))->get();
        $clarks = Clark::where('name', 'LIKE', '%' . $searchkey . '%')->orwhere('clark_number', 'LIKE', '%' . $searchkey . '%')->orwhere('motor_number', 'LIKE', '%' . $searchkey . '%')
            ->orWhereHas('all_clarks', function ($query) use ($searchkey) {
                $query->whereRaw('CONCAT("FN4", part_id, source_id, status_id, quality_id) LIKE ?', ["%$searchkey%"]);
            })->addSelect('*', DB::raw("'Clark' as type"), DB::raw("'4' as type_id"))->get();
        $equips = Equip::where('name', 'LIKE', '%' . $searchkey . '%')
            ->orWhereHas('all_equips', function ($query) use ($searchkey) {
                $query->whereRaw('CONCAT("FN5", part_id, source_id, status_id, quality_id)  LIKE ?', ["%$searchkey%"]);
            })->addSelect('*', DB::raw("'Equip' as type"), DB::raw("'5' as type_id"))->get();

        $allItems = new \Illuminate\Database\Eloquent\Collection; //Create empty collection which we know has the merge() method
        $allItems = $allItems->merge($parts);
        $allItems = $allItems->merge($kits);
        $allItems = $allItems->merge($wheels);
        $allItems = $allItems->merge($tractors);
        $allItems = $allItems->merge($clarks);
        $allItems = $allItems->merge($equips);

        return view('searchAll', compact('allItems'));

        return $allItems;

        // pagination add ?page=2,3,4
    }
    public function downloadDump()
    {
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', '3306');

        $filename = "backup-" . date('Y-m-d_H-i-s') . ".sql";
        $path = storage_path('app/' . $filename);

        $command = "mysqldump --user={$username} --password={$password} --host={$host} --port={$port} {$database} > {$path}";

        $returnVar = NULL;
        $output = NULL;
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return response()->json(['error' => 'Failed to generate database dump.'], 500);
        }

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function uploadDump(Request $request)
    {
        $request->validate([
            'dump_file' => 'required|mimes:sql|max:20480', // 20MB Max
        ]);

        $file = $request->file('dump_file');
        $filePath = $file->storeAs('dumps', 'upload-' . time() . '.sql');

        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', '3306');

        // Full path to the uploaded dump file
        $fullFilePath = storage_path('app/' . $filePath);

        // Restore the database using the `mysql` command
        $command = "mysql --user={$username} --password={$password} --host={$host} --port={$port} {$database} < {$fullFilePath}";

        $process = Process::fromShellCommandline($command);
        $process->run();

        // Check if the process was successful
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return back()->with('success', 'Database restored successfully.');
    }

    public function SendToStoreNew(Request $request)
    {
        // return $request;
        $logMessage='';
        $logUser = Auth::user()->id;
        
        DB::beginTransaction();
        try {

          
            $otherStore = Store::where('id', $request->storeId)->first();
            $currentStore = Store::where('id', $request->CurrentstoreId)->first();
            $allinvoices = new Collection();
            if ($request->partTypeS == 1) {
                $allinvoices = AllPart::where('remain_amount', '>', 0)->where('part_id', $request->partIdS)->where('source_id', $request->partSourceS)->where('status_id', $request->partStatusS)->where('quality_id', $request->partQualityS)->orderBy('id')->get();
            } else if ($request->partTypeS == 2) {
                $allinvoices = AllWheel::where('remain_amount', '>', 0)->where('part_id', $request->partIdS)->where('source_id', $request->partSourceS)->where('status_id', $request->partStatusS)->where('quality_id', $request->partQualityS)->orderBy('id')->get();
            } else if ($request->partTypeS == 6) {
                $allinvoices = AllKit::where('remain_amount', '>', 0)->where('part_id', $request->partIdS)->where('source_id', $request->partSourceS)->where('status_id', $request->partStatusS)->where('quality_id', $request->partQualityS)->orderBy('id')->get();
            } else if ($request->partTypeS == 3) {
                $allinvoices = AllTractor::where('remain_amount', '>', 0)->where('part_id', $request->partIdS)->where('source_id', $request->partSourceS)->where('status_id', $request->partStatusS)->where('quality_id', $request->partQualityS)->orderBy('id')->get();
            } else if ($request->partTypeS == 4) {
                $allinvoices = AllClark::where('remain_amount', '>', 0)->where('part_id', $request->partIdS)->where('source_id', $request->partSourceS)->where('status_id', $request->partStatusS)->where('quality_id', $request->partQualityS)->orderBy('id')->get();
            } else if ($request->partTypeS == 5) {
                $allinvoices = AllEquip::where('remain_amount', '>', 0)->where('part_id', $request->partIdS)->where('source_id', $request->partSourceS)->where('status_id', $request->partStatusS)->where('quality_id', $request->partQualityS)->orderBy('id')->get();
            }
            $lastlog = 0;
            $alllogs = [];
            // $sendAmount = $request->sendAmount;
            $sendAmount = $request->sendAmount;
            $ratiounit=1;
            foreach ($allinvoices as $key => $element) {
                if($element->type_id == 1){
                    $samllmeasureUnits = $element->part->small_unit;
                    $measureUnit = $element->part->big_unit;
                    $ratiounit = getSmallUnit($measureUnit, $samllmeasureUnits);
                    $sendAmount = $request->sendAmount * $ratiounit;
                }else{
                    $sendAmount = $request->sendAmount;
                }
              
                if ($element->remain_amount >= $sendAmount) {
                   
                    $ins_id = StoresLog::create([
                        'All_part_id' => $element->id,
                        'store_action_id' => 2,  //خروج من مخزن
                        'store_id' => $request->CurrentstoreId,
                        'amount' => $sendAmount,
                        // 'user_id' => auth()->user()->id,
                        'status' => 2,
                        'date' => date('Y-m-d H:i:s'),
                        'type_id' => $request->partTypeS,
                        'notes' => 'To :' . $otherStore->name,
                    ])->id;
                    
                    $logMessage.='سيتم خروج من مخزن الكمية '.$sendAmount.'من AllPartId'.$element->id.' الي'.$otherStore->name.' من'.$currentStore->name.'<br/>';
                    
                    $lastlog = StoresLog::create([
                        'All_part_id' => $element->id,
                        'store_action_id' => 3,  //دخول من مخزن
                        'store_id' => $otherStore->id,
                        'amount' => $sendAmount,
                        // 'user_id' => auth()->user()->id,
                        'status' => $ins_id,
                        'date' => date('Y-m-d H:i:s'),
                        'type_id' => $request->partTypeS,
                        'notes' => 'from :' . $currentStore->name,
                    ])->id;
                    
                    $logMessage.='سيتم دخول من مخزن الكمية '.$sendAmount.'من AllPartId'.$element->id.' من'.$currentStore->name.' الي'.$otherStore->name.'<br/>';
                    
                    array_push($alllogs, $ins_id);

                    break;
                } elseif ($element->remain_amount < $sendAmount) {
                    $ins_id = StoresLog::create([
                        'All_part_id' => $element->id,
                        'store_action_id' => 2,
                        'store_id' => $request->CurrentstoreId,
                        'amount' => $element->remain_amount,
                        // 'user_id' => auth()->user()->id,
                        'status' => 2,
                        'date' => date('Y-m-d H:i:s'),
                        'type_id' => $request->partTypeS,
                        'notes' => 'To :' . $otherStore->name,
                    ])->id;

                    $logMessage.='سيتم خروج من مخزن الكمية '.$element->remain_amount.'من AllPartId'.$element->id.' من'.$currentStore->name.' الي'.$otherStore->name.'<br/>';
                    
                    $lastlog = StoresLog::create([
                        'All_part_id' => $element->id,
                        'store_action_id' => 3,
                        'store_id' => $otherStore->id,
                        'amount' => $element->remain_amount,
                        // 'user_id' => auth()->user()->id,
                        'status' => $ins_id,
                        'date' => date('Y-m-d H:i:s'),
                        'type_id' => $request->partTypeS,
                        'notes' => 'from :' . $currentStore->name,
                    ])->id;
                    
                    $logMessage.='سيتم دخول من مخزن الكمية '.$element->remain_amount.'من AllPartId'.$element->id.' من'.$currentStore->name.' الي'.$otherStore->name.'<br/>';
                    
                    $sendAmount = $sendAmount - $element->remain_amount;
                    array_push($alllogs, $ins_id);
                } else if ($sendAmount < 0) {
                    break;
                }
            }
            
            if (isset($request->sectionIds)) {
                foreach ($request->sectionIds as $key => $value) {
                    if (intval($request->sectionAmount[$key]) > 0) {
                        StorelogSection::create([
                            'store_log_id' => $alllogs[0],
                            // 'store_log_id' => $lastlog,
                            'section_id' =>  intval($value),
                            'store_id' =>  $currentStore->id,
                            'amount' => intval($request->sectionAmount[$key]),
                            'notes' => implode(" ", $alllogs)
                        ]);
                    }
                }
            }
            
            $log = new LogController();
            $log->newLog($logUser,$logMessage);
            
            DB::commit();

            if(isset($request->processFrom)){
                if($request->processFrom === "askParts"){
                    
                    if($sendAmount < $request->demand_amount * $ratiounit){
                        $demand=DemandPart::where('id',$request->demand_id)->update([
                            'flag_send'=>2, //not all amount sent
                        ]);
                        return redirect()->back()->with('success',', تم تحديث طلب الطلب وإرسال جزء من الكمية');

                    }else{
                     
                         $demand=DemandPart::where('id',$request->demand_id)->update([
                            'flag_send'=>1,
                        ]);
                        return redirect()->back()->with('success',', تم تحديث طلب الطلب وإرسال الكمية');

                    }
                    if(!$demand){
                        throw new \Exception('حدث خطأ في تحديث طلب الطلب');
                    }
                }
               
            }
            return true;
            // return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash("success", "");
            return $e;
            // return redirect()->back();
        }
    }


    public function print_sanad_abd($type, $id)
    {

        if ($type == 'client') {
            $data = InvoiceClientMadyonea::where('id', $id)->with('client')->with('payment')->with('user')->first();
            $paperTitle = " سند قبض ";
            $recordName = "العميل";
            // $recordName= $client->name;
            $recordValue = $data->client->name;
            $recoredId = $id;
            $recoredUrl = 'sanad.print_sanad_abd' . $recoredId;
            $moneyVal = $data->paied;
            $personName = $data->client->name;
            $datee = $data->date;
            $payment_types = $data->payment->name;
            $note=$data->note;
            // if(count($data->user)>0){
                
            // }
            $emp=$data->user ?$data->user->username : '';
        } elseif ($type == 'supplier') {
        } else {
        }


        // return $presaleOrders;
        return view('sanad.print_sanad_abd', compact('emp','note','payment_types', 'paperTitle', 'recordName', 'recordValue', 'recoredUrl', 'moneyVal', 'personName', 'datee'));
    }
    public function print_sanad_sarf($type, $id)
    {


        if ($type == 'client') {
            $data = SanadSarf::where('id', $id)->where('type', 1)->with('payment')->first();
            $client = Client::where('id', $data->client_sup_id)->first();
            $paperTitle = " سند صرف ";
            $recordName = "العميل";
            $recordValue = $client->name;
            $recoredId = $id;
            $recoredUrl = 'sanad.print_sanad_sarf' . $recoredId;
            $moneyVal = $data->paied;
            $personName = $client->name;
            $datee = $data->date;
            $payment_types = $data->payment->name;
            $note=$data->note;
            $emp=$data->user->username;
        } elseif ($type == 'supplier') {
            $data = SanadSarf::where('id', $id)->where('type', 2)->with('payment')->first();

            $client = Supplier::where('id', $data->client_sup_id)->first();
            $paperTitle = " سند صرف ";
            $recordName = "المورد";
            $recordValue = $client->name;
            $recoredId = $id;
            $recoredUrl = 'sanad.print_sanad_sarf' . $recoredId;
            $moneyVal = $data->paied;
            $personName = $client->name;
            $datee = $data->date;
            $payment_types = $data->payment->name;
            
            $note=$data->note;
            $emp=isset($data->user) ? $data->user->name : '' ;
        } else {
        }


        // return $presaleOrders;
        return view('sanad.print_sanad_sarf', compact('note','emp','payment_types', 'paperTitle', 'recordName', 'recordValue', 'recoredUrl', 'moneyVal', 'personName', 'datee'));
    }

    public function save_sanadsarf(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $mad = new SanadSarf();
            $mad->client_sup_id = $request->client_id;
            $mad->paied = $request->paied;
            $mad->date = Carbon::now();
            $mad->pyment_method = $request->payment;
            $mad->type = 1;
            $mad->note = $request->notesarf;
            $mad->user_id = auth()->user()->id;

            $mad->save();
            ///////////////////////adel//////////////////////
            $client = Client::where('id', $request->client_id)->first();

            $payment_types = '-';
            if ($request->payment_type === 'store') {
                $store = Store::where('safe_accountant_number', $request->payment)->first();
                $payment_types = $store->name;
                $total = MoneySafe::where('store_id', $store->id)
                    ->latest()
                    ->first();


                if (isset($total)) {
                    if ($total->total >= $request->paied) {
                        MoneySafe::create([
                            'notes' => 'صرف مبلغ لعميل ' . ' ' . $client->name . ' ' . $store->name,
                            'date' => date('Y-m-d'),
                            'flag' => 1,
                            'money' => $request->paied,
                            'total' => $total->total - $request->paied,
                            'type_money' => '1',
                            'user_id' => Auth::user()->id,
                            'store_id' => $store->id,
                        ]);
                    } else {
                        DB::rollback();
                        session()->flash("success", "  المبلغ غير كافي في الخزنة");
                        return redirect()->back();
                    }
                } else {

                    DB::rollback();
                    session()->flash("success", "   لا يوجد رصيد في الخزنة   ");
                    return redirect()->back();
                }
            } else {
                $bank = BankType::where('accountant_number', $request->payment)->first();
                $payment_types = $bank->bank_name;
                $total = BankSafeMoney::where('bank_type_id', $bank->id)
                    ->latest()
                    ->first();
                if (isset($total)) {

                    if ($total->total >= $request->paied) {
                        BankSafeMoney::create([
                            'notes' => 'صرف مبلغ لعميل ' . ' ' . $client->name . ' ' . $payment_types,
                            'date' => date('Y-m-d'),
                            'flag' => 2,
                            'money' => $request->paied,
                            'total' => $total->total - $request->paied,
                            'type_money' => '1',
                            'user_id' => Auth::user()->id,
                            'store_id' => null,
                            'money_currency' => $request->paied,
                            'currency_id' => $request->currency_id,
                            'bank_type_id' => $bank->id

                        ]);
                    } else {
                        DB::rollback();
                        session()->flash("success", "  المبلغ غير كافي في الخزنة");
                        return redirect()->back();
                    }
                } else {
                    DB::rollback();
                    session()->flash("success", "   لا يوجد رصيد في الخزنة   ");
                    return redirect()->back();
                }
            }


            $quaditems = [];
            $automaicQayd = new QaydController();
            $acClientNo = Client::where('id', $request->client_id)->first()->accountant_number;

            array_push($quaditems, (object) ['acountant_id' => $request->payment, 'dayin' =>  $request->paied, 'madin' => 0]); // الخزنة  مدين
            array_push($quaditems, (object) ['acountant_id' => $acClientNo, 'madin' => $request->paied, 'dayin' => 0]); // العميل دائن

            $date = Carbon::now();
            $type = null;
            $notes = 'صرف مبلغ لعميل  ';
            $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);

            $paperTitle = " سند صرف ";
            $recordName = "العميل";
            // $recordName= $client->name;
            $recordValue = $client->name;
            $recoredId = $mad->id;
            $recoredUrl = 'sanad.print_sanad_sarf' . $recoredId;
            $moneyVal = $request->paied;
            $personName = $client->name;
            $datee = $mad->date;

            
            $data = SanadSarf::where('id', $mad->id)->with('payment')->with('user')->first();

            $note=$data->note;
            $emp=$data->user->username;

            // return $presaleOrders;
            DB::commit();
            // return view('sanad.print_sanad_sarf', compact('note','emp','payment_types', 'paperTitle', 'recordName', 'recordValue', 'recoredUrl', 'moneyVal', 'personName', 'datee'));
            return redirect()->to('Clientinvoice/' . $request->client_id . '/' . $request->store_id);
        } catch (\Exception $e) {
            //throw $th;
            DB::rollback();
            session()->flash("error", $e->getMessage());
            return false;
        }
    }



    public function equipPrepare()
    {

        $all_data = new Collection();


        $all_data = InvoiceItem::whereIn('part_type_id', [3, 4, 5])->get();

        return $all_data;

        return view('equipPrepare', compact('all_data'));
    }

    public function newClientQuick(Request $request)
    {
        // return $request;
        $clientName = $request->clientName;
        $clientTel = $request->clientTel;

        $client = Client::where('tel01', $clientTel)->orwhere('tel02', $clientTel)->orwhere('tel03', $clientTel)->first();

        if ($client) {
            return $client->id;
        } else {
            $parentid = BranchTree::where('accountant_number', 161)->first()->id;
            $lastchildAccNo = BranchTree::where('parent_id', $parentid)->orderBy('id', 'DESC')->first()->accountant_number;

            BranchTree::create([
                'name' =>   ' زبون -' . $clientName,
                'en_name' => $clientName,
                'parent_id' =>  $parentid,
                'accountant_number' => IntVal($lastchildAccNo) + 1
            ]);


            $clientnew = new Client();
            $clientnew->name = $clientName;
            $clientnew->tel01 = $clientTel;
            $clientnew->accountant_number = IntVal($lastchildAccNo) + 1;
            $clientnew->save();
            return $clientnew->id;
        }


        //
    }

     public function talef($part_id,$source_id,$status_id,$quality_id,$type_id,$store_id){

        // $user = User::find(Auth::user()->id); // Get the user
        // $user = User::find(39); // Get the user
        // $user->notify(new NotifyUser('new Talef'));
        // $user->unreadNotifications->markAsRead()
        $store_data = Store::where('id', $store_id)->get();
        $stores= Store::where('table_name','<>','damaged_parts')->get();
        $employee = Employee::all();
        $item='';
        $amount='';
        if($type_id == 1){
            $item = Part::where('id',$part_id)->first();
            $amount=$this->PartInStoresCount($part_id, $source_id, $status_id, $quality_id, $type_id);
        }elseif($type_id == 2){
            $item = Wheel::where('id',$part_id)->first();
            $amount=$this->PartInStoresCount($part_id, $source_id, $status_id, $quality_id, $type_id);
        }elseif($type_id == 3){
            $item = Tractor::where('id',$part_id)->first();
            $amount=$this->PartInStoresCount($part_id, $source_id, $status_id, $quality_id, $type_id);
        }elseif($type_id == 4){
            $item = Clark::where('id',$part_id)->first();
            $amount=$this->PartInStoresCount($part_id, $source_id, $status_id, $quality_id, $type_id);
        }elseif($type_id == 5){
            $item = Equip::where('id',$part_id)->first();
            $amount=$this->PartInStoresCount($part_id, $source_id, $status_id, $quality_id, $type_id);
        }elseif($type_id == 6){
            $item = Kit::where('id',$part_id)->first();
            $amount=$this->PartInStoresCount($part_id, $source_id, $status_id, $quality_id, $type_id);
        }else{
            $item = [];
            $amount=[];
        }
        $source = Source::where('id',$source_id)->first();
        $status = Status::where('id',$status_id)->first();
        $quality = PartQuality::where('id',$quality_id)->first();
        // return $amount;
        return view('talef',compact('store_data','stores','employee','item','source','status','quality','store_id','amount','type_id'));
    }

      public function save_talef(Request $request){

        // flag  0-> standard     1-> from storeLog transaction
        // return $request;
        $ratioamount = $request->ratioamount;
        $amount = $request->amount * $ratioamount;
        $part_id = $request->part_id;
        $source_id = $request->source_id;
        $status_id = $request->status_id;
        $quality_id = $request->quality_id;
        $type_id = $request->type_id;

        $totalItemVal = 0;

        DB::beginTransaction();
        try {
            // decrement Store  section allpart

            if ($type_id == 1) {

                $allparts = AllPart::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->with('order_supplier')->get();
                $all_part_table = 'all_parts';

                ////// remove from Stores ////////
                $requestAmount0 = $amount;
                foreach ($allparts as $key => $element) {

                    if ($element->remain_amount >= $requestAmount0) {


                        $Ac_currency_id = $element->order_supplier->currency_id;
                        $Ac_currency_date = $element->order_supplier->confirmation_date;
                        $Ac_all_currency_types = CurrencyType::with([
                            'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                            },
                        ])->where('id', $Ac_currency_id)
                            ->get();

                        $totalItemVal += ($requestAmount0 * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value)) + $element->buy_costing;



                        $store = Store::where('id', $request->store)->get();
                        $store_id = $store[0]->id;
                        $store_name = $store[0]->name;
                        $store_table_name = $store[0]->table_name;

                        $returnRes=DB::table($store_table_name)
                            ->join('stores_log', $store_table_name.'.store_log_id', '=', 'stores_log.id')
                            ->join($all_part_table, function($join) use ($store_table_name, $all_part_table) {
                                $join->on($store_table_name.'.supplier_order_id', '=', $all_part_table.'.order_supplier_id')
                                    ->on('stores_log.All_part_id', '=', $all_part_table.'.id');
                            })
                            ->where('stores_log.store_id', $store_id)
                            ->where($all_part_table.'.source_id', $source_id)
                            ->where($all_part_table.'.status_id', $status_id)
                            ->where($all_part_table.'.quality_id', $quality_id)
                            ->where($store_table_name.'.part_id', $part_id)
                            ->where($store_table_name.'.supplier_order_id', $element->order_supplier_id)
                            ->where($store_table_name.'.type_id', 1)
                            ->decrement($store_table_name.'.amount', $requestAmount0);

                            if($returnRes){
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $requestAmount0);
                                break;
                            }else{
                                continue;
                            }



                        // break;
                    } elseif ($element->remain_amount < $requestAmount0) {

                        $Ac_currency_id = $element->order_supplier->currency_id;
                        $Ac_currency_date = $element->order_supplier->confirmation_date;
                        $Ac_all_currency_types = CurrencyType::with([
                            'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                            },
                        ])->where('id', $Ac_currency_id)
                            ->get();

                        $totalItemVal += ($element->remain_amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value))+$element->buy_costing;

                        $store = Store::where('id', $request->store)->get();
                        $store_id = $store[0]->id;
                        $store_name = $store[0]->name;
                        $store_table_name = $store[0]->table_name;

                        $returnRes=DB::table($store_table_name)
                            ->join('stores_log', $store_table_name.'.store_log_id', '=', 'stores_log.id')
                            ->join($all_part_table, function($join) use ($store_table_name, $all_part_table) {
                                $join->on($store_table_name.'.supplier_order_id', '=', $all_part_table.'.order_supplier_id')
                                    ->on('stores_log.All_part_id', '=', $all_part_table.'.id');
                            })
                            ->where('stores_log.store_id', $store_id)
                            ->where($all_part_table.'.source_id', $source_id)
                            ->where($all_part_table.'.status_id', $status_id)
                            ->where($all_part_table.'.quality_id', $quality_id)
                            ->where($store_table_name.'.part_id', $part_id)
                            ->where($store_table_name.'.supplier_order_id', $element->order_supplier_id)
                            ->where($store_table_name.'.type_id', 1)
                            ->decrement($store_table_name.'.amount', $element->remain_amount);
                            // ->get();
                            if($returnRes){
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                                $requestAmount0 = $requestAmount0 - $element->remain_amount;
                            }else{
                                continue;
                            }

                        // $requestAmount0 = $requestAmount0 - $element->remain_amount;
                    } else if ($requestAmount0 <= 0) {
                        break;
                    }
                }


                ////// remove from all parts ////////



                ////// remove from Sections ////////
                $allsecc = DB::table('store_section')
                    ->where('part_id', $part_id)
                    // ->where('supplier_order_id', $allparts[0]->order_supplier_id)
                    ->where('type_id', 1)
                    ->where('source_id', $source_id)
                    ->where('status_id', $status_id)
                    ->where('quality_id', $quality_id)
                    ->get();

                $requestSecAmount = $amount;
                foreach ($allsecc as $key => $element) {

                    if ($element->amount >= $requestSecAmount) {
                        // decrement amount
                        StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);


                        break;
                    } elseif ($element->amount < $requestSecAmount) {
                        // decrement remain_amount
                        StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                        $requestSecAmount = $requestSecAmount - $element->amount;

                    } else if ($requestSecAmount <= 0) {
                        break;
                    }
                }
            } elseif ($type_id == 2) {
                //wheel
                $allparts = AllWheel::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->with('order_supplier')->get();

                $all_part_table = 'all_wheels';
                ////// remove from Stores ////////
                $requestAmount0 = $amount;
                foreach ($allparts as $key => $element) {

                    if ($element->remain_amount >= $requestAmount0) {


                        $Ac_currency_id = $element->order_supplier->currency_id;
                        $Ac_currency_date = $element->order_supplier->confirmation_date;
                        $Ac_all_currency_types = CurrencyType::with([
                            'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                            },
                        ])->where('id', $Ac_currency_id)
                            ->get();

                        $totalItemVal += ($requestAmount0 * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value)) + $element->buy_costing;


                        $store = Store::where('id', $request->store)->get();
                        $store_id = $store[0]->id;
                        $store_name = $store[0]->name;
                        $store_table_name = $store[0]->table_name;



                        $returnRes=DB::table($store_table_name)
                        ->join('stores_log', $store_table_name.'.store_log_id', '=', 'stores_log.id')
                        ->join($all_part_table, function($join) use ($store_table_name, $all_part_table) {
                            $join->on($store_table_name.'.supplier_order_id', '=', $all_part_table.'.order_supplier_id')
                                ->on('stores_log.All_part_id', '=', $all_part_table.'.id');
                        })
                        ->where('stores_log.store_id', $store_id)
                        ->where($all_part_table.'.source_id', $source_id)
                        ->where($all_part_table.'.status_id', $status_id)
                        ->where($all_part_table.'.quality_id', $quality_id)
                        ->where($store_table_name.'.part_id', $part_id)
                        ->where($store_table_name.'.supplier_order_id', $element->order_supplier_id)
                        ->where($store_table_name.'.type_id', 2)
                        ->decrement($store_table_name.'.amount', $requestAmount0);


                        if($returnRes){
                            DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $requestAmount0);
                            break;
                        }else{
                            continue;
                        }



                        // break;
                    } elseif ($element->remain_amount < $requestAmount0) {

                        $Ac_currency_id = $element->order_supplier->currency_id;
                        $Ac_currency_date = $element->order_supplier->confirmation_date;
                        $Ac_all_currency_types = CurrencyType::with([
                            'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                            },
                        ])->where('id', $Ac_currency_id)
                            ->get();

                        $totalItemVal += ($element->remain_amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value)) + $element->buy_costing;


                        $store = Store::where('id', $request->store)->get();
                        $store_id = $store[0]->id;
                        $store_name = $store[0]->name;
                        $store_table_name = $store[0]->table_name;


                        $returnRes=DB::table($store_table_name)
                            ->join('stores_log', $store_table_name.'.store_log_id', '=', 'stores_log.id')
                            ->join($all_part_table, function($join) use ($store_table_name, $all_part_table) {
                                $join->on($store_table_name.'.supplier_order_id', '=', $all_part_table.'.order_supplier_id')
                                    ->on('stores_log.All_part_id', '=', $all_part_table.'.id');
                            })
                            ->where('stores_log.store_id', $store_id)
                            ->where($all_part_table.'.source_id', $source_id)
                            ->where($all_part_table.'.status_id', $status_id)
                            ->where($all_part_table.'.quality_id', $quality_id)
                            ->where($store_table_name.'.part_id', $part_id)
                            ->where($store_table_name.'.supplier_order_id', $element->order_supplier_id)
                            ->where($store_table_name.'.type_id', 2)
                            ->decrement($store_table_name.'.amount', $element->remain_amount);

                            if($returnRes){
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                                $requestAmount0 = $requestAmount0 - $element->remain_amount;
                            }else{
                                continue;
                            }

                        // $requestAmount0 = $requestAmount0 - $element->remain_amount;
                    } else if ($requestAmount0 <= 0) {
                        break;
                    }
                }


                ////// remove from all parts ////////

                // $allpp = AllWheel::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->get();
                // $requestAmount = $amount;
                // foreach ($allpp as $key => $element) {

                //     if ($element->remain_amount >= $requestAmount) {
                //         // decrement amount
                //         AllWheel::where('id', $element->id)->decrement('remain_amount', $requestAmount);

                //         break;
                //     } elseif ($element->remain_amount < $requestAmount) {
                //         // decrement remain_amount
                //         AllWheel::where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                //         $requestAmount = $requestAmount - $element->remain_amount;


                //     } else if ($requestAmount <= 0) {
                //         break;
                //     }
                // }

                ////// remove from Sections ////////
                $allsecc = DB::table('store_section')
                    ->where('part_id', $part_id)
                    // ->where('supplier_order_id', $allparts[0]->order_supplier_id)
                    ->where('type_id', 2)
                    ->where('source_id', $source_id)
                    ->where('status_id', $status_id)
                    ->where('quality_id', $quality_id)
                    ->get();

                $requestSecAmount = $amount;
                foreach ($allsecc as $key => $element) {

                    if ($element->amount >= $requestSecAmount) {
                        // decrement amount
                        StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);


                        break;
                    } elseif ($element->amount < $requestSecAmount) {
                        // decrement remain_amount
                        StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                        $requestSecAmount = $requestSecAmount - $element->amount;

                    } else if ($requestSecAmount <= 0) {
                        break;
                    }
                }
            } elseif ($type_id == 6) {
                $allparts = AllKit::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->with('order_supplier')->get();

                $all_part_table = 'all_kits';
                ////// remove from Stores ////////
                $requestAmount0 = $amount;
                foreach ($allparts as $key => $element) {

                    if ($element->remain_amount >= $requestAmount0) {

                        $Ac_currency_id = $element->order_supplier->currency_id;
                        $Ac_currency_date = $element->order_supplier->confirmation_date;
                        $Ac_all_currency_types = CurrencyType::with([
                            'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                            },
                        ])->where('id', $Ac_currency_id)
                            ->get();

                        $totalItemVal += ($requestAmount0 * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value)) + $element->buy_costing;

                        $store = Store::where('id', $request->store)->get();
                        $store_id = $store[0]->id;
                        $store_name = $store[0]->name;
                        $store_table_name = $store[0]->table_name;



                        $returnRes=DB::table($store_table_name)
                            ->join('stores_log', $store_table_name.'.store_log_id', '=', 'stores_log.id')
                            ->join($all_part_table, function($join) use ($store_table_name, $all_part_table) {
                                $join->on($store_table_name.'.supplier_order_id', '=', $all_part_table.'.order_supplier_id')
                                    ->on('stores_log.All_part_id', '=', $all_part_table.'.id');
                            })
                            ->where('stores_log.store_id', $store_id)
                            ->where($all_part_table.'.source_id', $source_id)
                            ->where($all_part_table.'.status_id', $status_id)
                            ->where($all_part_table.'.quality_id', $quality_id)
                            ->where($store_table_name.'.part_id', $part_id)
                            ->where($store_table_name.'.supplier_order_id', $element->order_supplier_id)
                            ->where($store_table_name.'.type_id', 6)
                            ->decrement($store_table_name.'.amount', $requestAmount0);

                            if($returnRes){
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $requestAmount0);
                                break;
                            }else{
                                continue;
                            }


                        // break;
                    } elseif ($element->remain_amount < $requestAmount0) {


                        $Ac_currency_id = $element->order_supplier->currency_id;
                        $Ac_currency_date = $element->order_supplier->confirmation_date;
                        $Ac_all_currency_types = CurrencyType::with([
                            'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                            },
                        ])->where('id', $Ac_currency_id)
                            ->get();

                        $totalItemVal += ($element->remain_amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value)) + $element->buy_costing;

                        $store = Store::where('id', $request->store)->get();
                        $store_id = $store[0]->id;
                        $store_name = $store[0]->name;
                        $store_table_name = $store[0]->table_name;


                        $returnRes= DB::table($store_table_name)
                            ->join('stores_log', $store_table_name.'.store_log_id', '=', 'stores_log.id')
                            ->join($all_part_table, function($join) use ($store_table_name, $all_part_table) {
                                $join->on($store_table_name.'.supplier_order_id', '=', $all_part_table.'.order_supplier_id')
                                    ->on('stores_log.All_part_id', '=', $all_part_table.'.id');
                            })
                            ->where('stores_log.store_id', $store_id)
                            ->where($all_part_table.'.source_id', $source_id)
                            ->where($all_part_table.'.status_id', $status_id)
                            ->where($all_part_table.'.quality_id', $quality_id)
                            ->where($store_table_name.'.part_id', $part_id)
                            ->where($store_table_name.'.supplier_order_id', $element->order_supplier_id)
                            ->where($store_table_name.'.type_id', 6)
                            ->decrement($store_table_name.'.amount', $element->remain_amount);

                            if($returnRes){
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                                $requestAmount0 = $requestAmount0 - $element->remain_amount;
                            }else{
                                continue;
                            }
                        // $requestAmount0 = $requestAmount0 - $element->remain_amount;
                    } else if ($requestAmount0 <= 0) {
                        break;
                    }
                }


                ////// remove from all parts ////////

                $allpp = AllKit::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->get();
                $requestAmount = $amount;
                foreach ($allpp as $key => $element) {

                    if ($element->remain_amount >= $requestAmount) {
                        // decrement amount
                        AllKit::where('id', $element->id)->decrement('remain_amount', $requestAmount);

                        break;
                    } elseif ($element->remain_amount < $requestAmount) {
                        // decrement remain_amount
                        AllKit::where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                        $requestAmount = $requestAmount - $element->remain_amount;


                    } else if ($requestAmount <= 0) {
                        break;
                    }
                }

                ////// remove from Sections ////////
                $allsecc = DB::table('store_section')
                    ->where('part_id', $part_id)
                    // ->where('supplier_order_id', $allparts[0]->order_supplier_id)
                    ->where('type_id', 6)
                    ->where('source_id', $source_id)
                    ->where('status_id', $status_id)
                    ->where('quality_id', $quality_id)
                    ->get();

                $requestSecAmount = $amount;
                foreach ($allsecc as $key => $element) {

                    if ($element->amount >= $requestSecAmount) {
                        // decrement amount
                        StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);


                        break;
                    } elseif ($element->amount < $requestSecAmount) {
                        // decrement remain_amount
                        StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                        $requestSecAmount = $requestSecAmount - $element->amount;

                    } else if ($requestSecAmount <= 0) {
                        break;
                    }
                }
            } elseif ($type_id == 3) {
                //tractor
                $allparts = AllTractor::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->with('order_supplier')->get();

                $all_part_table = 'all_tractors';

                ////// remove from Stores ////////
                $requestAmount0 = $amount;
                foreach ($allparts as $key => $element) {

                    if ($element->remain_amount >= $requestAmount0) {

                        $Ac_currency_id = $element->order_supplier->currency_id;
                        $Ac_currency_date = $element->order_supplier->confirmation_date;
                        $Ac_all_currency_types = CurrencyType::with([
                            'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                            },
                        ])->where('id', $Ac_currency_id)
                            ->get();

                        $totalItemVal += ($requestAmount0 * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value)) + $element->buy_costing;

                       $store = Store::where('id', $request->store)->get();
                        $store_id = $store[0]->id;
                        $store_name = $store[0]->name;
                        $store_table_name = $store[0]->table_name;



                        $returnRes=DB::table($store_table_name)
                            ->join('stores_log', $store_table_name.'.store_log_id', '=', 'stores_log.id')
                            ->join($all_part_table, function($join) use ($store_table_name, $all_part_table) {
                                $join->on($store_table_name.'.supplier_order_id', '=', $all_part_table.'.order_supplier_id')
                                    ->on('stores_log.All_part_id', '=', $all_part_table.'.id');
                            })
                            ->where('stores_log.store_id', $store_id)
                            ->where($all_part_table.'.source_id', $source_id)
                            ->where($all_part_table.'.status_id', $status_id)
                            ->where($all_part_table.'.quality_id', $quality_id)
                            ->where($store_table_name.'.part_id', $part_id)
                            ->where($store_table_name.'.supplier_order_id', $element->order_supplier_id)
                            ->where($store_table_name.'.type_id', 3)
                            ->decrement($store_table_name.'.amount', $requestAmount0);
                            if($returnRes){
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $requestAmount0);
                                break;
                            }else{
                                continue;
                            }

                        // break;
                    } elseif ($element->remain_amount < $requestAmount0) {


                        $Ac_currency_id = $element->order_supplier->currency_id;
                        $Ac_currency_date = $element->order_supplier->confirmation_date;
                        $Ac_all_currency_types = CurrencyType::with([
                            'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                            },
                        ])->where('id', $Ac_currency_id)
                            ->get();

                        $totalItemVal += ($element->remain_amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value)) + $element->buy_costing;

                        $store = Store::where('id', $request->store)->get();
                        $store_id = $store[0]->id;
                        $store_name = $store[0]->name;
                        $store_table_name = $store[0]->table_name;



                        $returnRes= DB::table($store_table_name)
                            ->join('stores_log', $store_table_name.'.store_log_id', '=', 'stores_log.id')
                            ->join($all_part_table, function($join) use ($store_table_name, $all_part_table) {
                                $join->on($store_table_name.'.supplier_order_id', '=', $all_part_table.'.order_supplier_id')
                                    ->on('stores_log.All_part_id', '=', $all_part_table.'.id');
                            })
                            ->where('stores_log.store_id', $store_id)
                            ->where($all_part_table.'.source_id', $source_id)
                            ->where($all_part_table.'.status_id', $status_id)
                            ->where($all_part_table.'.quality_id', $quality_id)
                            ->where($store_table_name.'.part_id', $part_id)
                            ->where($store_table_name.'.supplier_order_id', $element->order_supplier_id)
                            ->where($store_table_name.'.type_id', 3)
                            ->decrement($store_table_name.'.amount', $element->remain_amount);

                            if($returnRes){
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                                $requestAmount0 = $requestAmount0 - $element->remain_amount;
                            }else{
                                continue;
                            }
                        // $requestAmount0 = $requestAmount0 - $element->remain_amount;
                    } else if ($requestAmount0 <= 0) {
                        break;
                    }
                }


                ////// remove from all parts ////////

                $allpp = AllTractor::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->get();
                $requestAmount = $amount;
                foreach ($allpp as $key => $element) {

                    if ($element->remain_amount >= $requestAmount) {
                        // decrement amount
                        AllTractor::where('id', $element->id)->decrement('remain_amount', $requestAmount);

                        break;
                    } elseif ($element->remain_amount < $requestAmount) {
                        // decrement remain_amount
                        AllTractor::where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                        $requestAmount = $requestAmount - $element->remain_amount;


                    } else if ($requestAmount <= 0) {
                        break;
                    }
                }

                ////// remove from Sections ////////
                $allsecc = DB::table('store_section')
                    ->where('part_id', $part_id)
                    // ->where('supplier_order_id', $allparts[0]->order_supplier_id)
                    ->where('type_id', 3)
                    ->where('source_id', $source_id)
                    ->where('status_id', $status_id)
                    ->where('quality_id', $quality_id)
                    ->get();

                $requestSecAmount = $amount;
                foreach ($allsecc as $key => $element) {

                    if ($element->amount >= $requestSecAmount) {
                        // decrement amount
                        StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);


                        break;
                    } elseif ($element->amount < $requestSecAmount) {
                        // decrement remain_amount
                        StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                        $requestSecAmount = $requestSecAmount - $element->amount;

                    } else if ($requestSecAmount <= 0) {
                        break;
                    }
                }
            } elseif ($type_id == 4) {
                //clark
                $allparts = AllClark::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->with('order_supplier')->get();

                $all_part_table = 'all_clarks';

                ////// remove from Stores ////////
                $requestAmount0 = $amount;
                foreach ($allparts as $key => $element) {

                    if ($element->remain_amount >= $requestAmount0) {

                        $Ac_currency_id = $element->order_supplier->currency_id;
                        $Ac_currency_date = $element->order_supplier->confirmation_date;
                        $Ac_all_currency_types = CurrencyType::with([
                            'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                            },
                        ])->where('id', $Ac_currency_id)
                            ->get();

                        $totalItemVal += ($requestAmount0 * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value)) + $element->buy_costing;

                        $store = Store::where('id', $request->store)->get();
                        $store_id = $store[0]->id;
                        $store_name = $store[0]->name;
                        $store_table_name = $store[0]->table_name;


                        $returnRes=DB::table($store_table_name)
                            ->join('stores_log', $store_table_name.'.store_log_id', '=', 'stores_log.id')
                            ->join($all_part_table, function($join) use ($store_table_name, $all_part_table) {
                                $join->on($store_table_name.'.supplier_order_id', '=', $all_part_table.'.order_supplier_id')
                                    ->on('stores_log.All_part_id', '=', $all_part_table.'.id');
                            })
                            ->where('stores_log.store_id', $store_id)
                            ->where($all_part_table.'.source_id', $source_id)
                            ->where($all_part_table.'.status_id', $status_id)
                            ->where($all_part_table.'.quality_id', $quality_id)
                            ->where($store_table_name.'.part_id', $part_id)
                            ->where($store_table_name.'.supplier_order_id', $element->order_supplier_id)
                            ->where($store_table_name.'.type_id', 4)
                            ->decrement($store_table_name.'.amount', $requestAmount0);

                            if($returnRes){
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $requestAmount0);
                                break;
                            }else{
                                continue;
                            }
                        // break;
                    } elseif ($element->remain_amount < $requestAmount0) {


                        $Ac_currency_id = $element->order_supplier->currency_id;
                        $Ac_currency_date = $element->order_supplier->confirmation_date;
                        $Ac_all_currency_types = CurrencyType::with([
                            'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                            },
                        ])->where('id', $Ac_currency_id)
                            ->get();

                        $totalItemVal += ($element->remain_amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value)) + $element->buy_costing;

                        $store = Store::where('id', $request->store)->get();
                        $store_id = $store[0]->id;
                        $store_name = $store[0]->name;
                        $store_table_name = $store[0]->table_name;



                        $returnRes= DB::table($store_table_name)
                            ->join('stores_log', $store_table_name.'.store_log_id', '=', 'stores_log.id')
                            ->join($all_part_table, function($join) use ($store_table_name, $all_part_table) {
                                $join->on($store_table_name.'.supplier_order_id', '=', $all_part_table.'.order_supplier_id')
                                    ->on('stores_log.All_part_id', '=', $all_part_table.'.id');
                            })
                            ->where('stores_log.store_id', $store_id)
                            ->where($all_part_table.'.source_id', $source_id)
                            ->where($all_part_table.'.status_id', $status_id)
                            ->where($all_part_table.'.quality_id', $quality_id)
                            ->where($store_table_name.'.part_id', $part_id)
                            ->where($store_table_name.'.supplier_order_id', $element->order_supplier_id)
                            ->where($store_table_name.'.type_id', 4)
                            ->decrement($store_table_name.'.amount', $element->remain_amount);
                            if($returnRes){
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                                $requestAmount0 = $requestAmount0 - $element->remain_amount;
                            }else{
                                continue;
                            }

                        // $requestAmount0 = $requestAmount0 - $element->remain_amount;
                    } else if ($requestAmount0 <= 0) {
                        break;
                    }
                }


                ////// remove from all parts ////////

                // $allpp = AllClark::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->get();
                // $requestAmount = $amount;
                // foreach ($allpp as $key => $element) {

                //     if ($element->remain_amount >= $requestAmount) {
                //         // decrement amount
                //         AllClark::where('id', $element->id)->decrement('remain_amount', $requestAmount);

                //         break;
                //     } elseif ($element->remain_amount < $requestAmount) {
                //         // decrement remain_amount
                //         AllClark::where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                //         $requestAmount = $requestAmount - $element->remain_amount;


                //     } else if ($requestAmount <= 0) {
                //         break;
                //     }
                // }

                ////// remove from Sections ////////
                $allsecc = DB::table('store_section')
                    ->where('part_id', $part_id)
                    // ->where('supplier_order_id', $allparts[0]->order_supplier_id)
                    ->where('type_id', 4)
                    ->where('source_id', $source_id)
                    ->where('status_id', $status_id)
                    ->where('quality_id', $quality_id)
                    ->get();

                $requestSecAmount = $amount;
                foreach ($allsecc as $key => $element) {

                    if ($element->amount >= $requestSecAmount) {
                        // decrement amount
                        StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);


                        break;
                    } elseif ($element->amount < $requestSecAmount) {
                        // decrement remain_amount
                        StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                        $requestSecAmount = $requestSecAmount - $element->amount;

                    } else if ($requestSecAmount <= 0) {
                        break;
                    }
                }
            } elseif ($type_id == 5) {
                //equip
                $allparts = AllEquip::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->with('order_supplier')->get();

                $all_part_table = 'all_equips';

                ////// remove from Stores ////////
                $requestAmount0 = $amount;
                foreach ($allparts as $key => $element) {

                    if ($element->remain_amount >= $requestAmount0) {

                        $Ac_currency_id = $element->order_supplier->currency_id;
                        $Ac_currency_date = $element->order_supplier->confirmation_date;
                        $Ac_all_currency_types = CurrencyType::with([
                            'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                            },
                        ])->where('id', $Ac_currency_id)
                            ->get();

                        $totalItemVal += ($requestAmount0 * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value)) + $element->buy_costing;

                        $store = Store::where('id', $request->store)->get();
                        $store_id = $store[0]->id;
                        $store_name = $store[0]->name;
                        $store_table_name = $store[0]->table_name;


                        $returnRes=DB::table($store_table_name)
                            ->join('stores_log', $store_table_name.'.store_log_id', '=', 'stores_log.id')
                            ->join($all_part_table, function($join) use ($store_table_name, $all_part_table) {
                                $join->on($store_table_name.'.supplier_order_id', '=', $all_part_table.'.order_supplier_id')
                                    ->on('stores_log.All_part_id', '=', $all_part_table.'.id');
                            })
                            ->where('stores_log.store_id', $store_id)
                            ->where($all_part_table.'.source_id', $source_id)
                            ->where($all_part_table.'.status_id', $status_id)
                            ->where($all_part_table.'.quality_id', $quality_id)
                            ->where($store_table_name.'.part_id', $part_id)
                            ->where($store_table_name.'.supplier_order_id', $element->order_supplier_id)
                            ->where($store_table_name.'.type_id', 5)
                            ->decrement($store_table_name.'.amount', $requestAmount0);

                            if($returnRes){
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $requestAmount0);
                                break;
                            }else{
                                continue;
                            }
                        // break;
                    } elseif ($element->remain_amount < $requestAmount0) {


                        $Ac_currency_id = $element->order_supplier->currency_id;
                        $Ac_currency_date = $element->order_supplier->confirmation_date;
                        $Ac_all_currency_types = CurrencyType::with([
                            'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                            },
                        ])->where('id', $Ac_currency_id)
                            ->get();

                        $totalItemVal += ($element->remain_amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value)) + $element->buy_costing;

                        $store = Store::where('id', $request->store)->get();
                        $store_id = $store[0]->id;
                        $store_name = $store[0]->name;
                        $store_table_name = $store[0]->table_name;


                        $returnRes= DB::table($store_table_name)
                            ->join('stores_log', $store_table_name.'.store_log_id', '=', 'stores_log.id')
                            ->join($all_part_table, function($join) use ($store_table_name, $all_part_table) {
                                $join->on($store_table_name.'.supplier_order_id', '=', $all_part_table.'.order_supplier_id')
                                    ->on('stores_log.All_part_id', '=', $all_part_table.'.id');
                            })
                            ->where('stores_log.store_id', $store_id)
                            ->where($all_part_table.'.source_id', $source_id)
                            ->where($all_part_table.'.status_id', $status_id)
                            ->where($all_part_table.'.quality_id', $quality_id)
                            ->where($store_table_name.'.part_id', $part_id)
                            ->where($store_table_name.'.supplier_order_id', $element->order_supplier_id)
                            ->where($store_table_name.'.type_id', 5)
                            ->decrement($store_table_name.'.amount', $element->remain_amount);
                            if($returnRes){
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                                $requestAmount0 = $requestAmount0 - $element->remain_amount;
                            }else{
                                continue;
                            }

                        // $requestAmount0 = $requestAmount0 - $element->remain_amount;
                    } else if ($requestAmount0 <= 0) {
                        break;
                    }
                }


                ////// remove from all parts ////////

                // $allpp = AllEquip::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->get();
                // $requestAmount = $amount;
                // foreach ($allpp as $key => $element) {

                //     if ($element->remain_amount >= $requestAmount) {
                //         // decrement amount
                //         AllEquip::where('id', $element->id)->decrement('remain_amount', $requestAmount);

                //         break;
                //     } elseif ($element->remain_amount < $requestAmount) {
                //         // decrement remain_amount
                //         AllEquip::where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                //         $requestAmount = $requestAmount - $element->remain_amount;


                //     } else if ($requestAmount <= 0) {
                //         break;
                //     }
                // }

                ////// remove from Sections ////////
                $allsecc = DB::table('store_section')
                    ->where('part_id', $part_id)
                    // ->where('supplier_order_id', $allparts[0]->order_supplier_id)
                    ->where('type_id', 5)
                    ->where('source_id', $source_id)
                    ->where('status_id', $status_id)
                    ->where('quality_id', $quality_id)
                    ->get();

                $requestSecAmount = $amount;
                foreach ($allsecc as $key => $element) {

                    if ($element->amount >= $requestSecAmount) {
                        // decrement amount
                        StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);


                        break;
                    } elseif ($element->amount < $requestSecAmount) {
                        // decrement remain_amount
                        StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                        $requestSecAmount = $requestSecAmount - $element->amount;

                    } else if ($requestSecAmount <= 0) {
                        break;
                    }
                }
            }


            // insert to talef table

            $talef = new Talef();



            $talef->date= Carbon::now();
            $talef->store_id= $request->store;
            $talef->part_id= $part_id;
            $talef->source_id= $source_id;
            $talef->status_id= $status_id;
            $talef->quality_id= $quality_id;
            $talef->type_id= $type_id;
            $talef->amount= $amount;
            $talef->employee_id= $request->employee;
            $talef->notes= $request->notes;
            $talef->user_id=  Auth::user()->id;
            $talef->save();

            // insert qayd

            $storeData =  Store::where('id',$request->store)->first();

            $quaditems = [];
            $automaicQayd = new QaydController();
            array_push($quaditems, (object) ['acountant_id' => 38, 'madin' => $totalItemVal, 'dayin' => 0]);
            array_push($quaditems, (object) ['acountant_id' => $storeData->accountant_number, 'madin' => 0, 'dayin' => $totalItemVal]);
            $date = Carbon::now();
            $type = null;
            $notes = 'خسائر وتالف ' . $talef->id;
            $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);


            DB::commit();
            session()->flash("success", "success");
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash("error", $e->getMessage());
            return false;
        }

    }

    public function allparts(Request $request){


        $query = AllPart::with(['part','source','status','part_quality','order_supplier.supplier'])
            ->orderBy('part_id');
    
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('part_id', 'LIKE', "%$search%")
                ->orWhereHas('part', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                })
                ->orWhereHas('source', function ($q) use ($search) {
                    $q->where('name_arabic', 'LIKE', "%$search%");
                });
        }
    
        $allparts = $query->paginate(10);
        // $allparts = AllPart::with(['part','source','status','part_quality','order_supplier.supplier'])->orderBy('part_id')->paginate(10);
        $allstores = Store::all();
        foreach ($allparts as $key => $allpart) {
    
            $stores = Store::all();
            $stores->each(function ($item) use ($allpart) {
                // $storeClass = 'App\Models\\'.ucfirst($item->table_name);
                $item->storepart = DB::table($item->table_name)
                    ->select($item->table_name . '.*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                    ->where('all_parts.part_id', '=', $allpart->part_id)
                    ->where('all_parts.source_id', '=', $allpart->source_id)
                    ->where('all_parts.status_id', '=', $allpart->status_id)
                    ->where('all_parts.quality_id', '=', $allpart->quality_id)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->get();
    
    
    
    
                $item->storepartCount = DB::table($item->table_name)
                    ->select('*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                    ->where('all_parts.part_id', '=', $allpart->part_id)
                    ->where('all_parts.source_id', '=', $allpart->source_id)
                    ->where('all_parts.status_id', '=', $allpart->status_id)
                    ->where('all_parts.quality_id', '=', $allpart->quality_id)
                    ->where('all_parts.order_supplier_id', '=', $allpart->order_supplier_id)
                    // ->where('stores_log.status', '=', 3)
                    // ->orWhere('stores_log.status', '=', 1)
                    ->where($item->table_name . '.type_id', '=', 1)
                    ->sum($item->table_name . '.amount');
    
                $item->sections =StoreSection::where('part_id', $allpart->part_id)
                    ->where('source_id', $allpart->source_id)
                    ->where('status_id', $allpart->status_id)
                    ->where('quality_id', $allpart->quality_id)
                    ->where('order_supplier_id', $allpart->order_supplier_id)
                    ->where('type_id', '1')
                    ->where('store_id', $item->id)
                    ->where('amount', '>', 0)
                    ->with('store_structure')
                    ->get();
    
                $item->inkitsections =AllKitPartItemSection::where('part_id', $allpart->part_id)
                    ->where('source_id', $allpart->source_id)
                    ->where('status_id', $allpart->status_id)
                    ->where('store_id', $item->id)
                    ->where('quality_id', $allpart->quality_id)
                    ->where('order_sup_id', $allpart->order_supplier_id)
                    ->sum('amount');
            });
    
            $allpart->stores= $stores;
    
    
            $allpart->allinvoices=$allinvoices = InvoiceItem::where('part_type_id', '1')
            ->where('part_id', $allpart->part_id)
            ->where('source_id', $allpart->source_id)
            ->where('status_id', $allpart->status_id)
            ->where('quality_id', $allpart->quality_id)
            ->with(['invoice_item_order_suppliers' => function ($query) use ($allpart) {
                $query->where('order_supplier_id', $allpart->order_supplier_id);
            }])->with('invoice_items_refund')
            ->get();
    
    
    
            $allpart->selles=$allinvoices->sum(function ($q) {
                return $q->invoice_item_order_suppliers->sum('amount');
            });
            
            $allpart->refund=$allinvoices->sum(function ($q) {
                return $q->invoice_items_refund->sum('r_amount');
            });
    
    
            $allpart->inkit= AllKitPartItem::where('part_id', $allpart->part_id)
            ->where('source_id', $allpart->source_id)
            ->where('status_id', $allpart->status_id)
            ->where('quality_id', $allpart->quality_id)
            ->where('order_sup_id', $allpart->order_supplier_id)
            ->sum('amount');
    
    
    
        }
    
        // return $allparts;
        return view('allparts',compact('allparts','allstores'));
    
    }

    
public function getCounttalabatParts($store_id){

        return DemandPart::where('to_store_id',$store_id)->where('flag_send',0)->count();
    }
}
