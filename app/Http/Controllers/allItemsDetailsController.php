<?php

namespace App\Http\Controllers;

use App\Models\StorelogSection;
use App\Models\StoresLog;
use Carbon\Carbon;
use App\Models\AllKit;
use App\Models\AllKitPartItem;
use App\Models\AllPart;
use App\Models\KitPart;
use App\Models\BuyTransaction;
use App\Models\Kit;
use App\Models\StoresInvoicesLog;
use App\Models\RefundInvoice;
use App\Models\Talef;
use App\Models\Replyorder;
use App\Models\Part;
use App\Models\PartQuality;
use App\Models\InvoiceItem;
use App\Models\PresaleOrder;
use App\Models\PresaleOrderPart;
use App\Models\SalePricing;
use App\Models\Source;
use App\Models\Status;
use App\Models\Store;
use App\Models\StoreSection;
use App\Models\StoreStructure;
use App\Models\Wheel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Clark;
use App\Models\Equip;
use App\Models\Tractor;

class allItemsDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($type_id,$part_id)
    {

        $allstores = Store::all();
        $data = new \Illuminate\Database\Eloquent\Collection;
        if($type_id==1){
            $data = Part::where('id',$part_id)
            ->with('part_numbers.supplier') ->with('sub_group.group')->with('part_details.part_spec')
            ->with('part_models.series.model')
            ->with('related_parts.part')
            ->with('all_parts.status')
            ->with('all_parts.source')
            ->with('all_parts.part_quality')
            ->with('all_parts.pricing')
            ->with('all_parts.sections.store_structure')
            ->with('all_parts.sections.store')
            ->with('all_parts.part_without_supplier_in_allkit_item.all_kit.kit')
            ->with('all_parts.part_without_supplier_in_allkit_item.source')
            ->with('all_parts.part_without_supplier_in_allkit_item.status')
            ->with('all_parts.part_without_supplier_in_allkit_item.part_quality')
            ->with('all_parts.parts_in_allkit_item.all_kit.kit')
            ->with('all_parts.order_supplier.supplier')
            ->with('all_parts.order_supplier.currency_type')
            ->with('all_parts.part.getsmallunit.unit')
            ->with('all_parts.store_log.store')
            ->addSelect('*',DB::raw("'Part' as type"))->first();

            $data['allkits']=KitPart::where('part_id',$part_id)->with('all_kits.kit')->with('all_kits.source')->with('all_kits.status')->with('all_kits.part_quality')->get();
             $kitPartsWithCount=KitPart::where('part_id',$part_id)->withCount('all_kits')->get();
             $data['kitcount'] = $kitPartsWithCount->sum('all_kits_count');

            $data['kitcollection'] =AllKitPartItem::
                select(['all_kit_part_item.part_id',
                    'all_kit_part_item.source_id',
                    'all_kit_part_item.status_id',
                    'all_kit_part_item.quality_id',
                    'kit.name','kit.id',
                    DB::raw('SUM(all_kit_part_item.amount) as amount')
                ])
                ->join('all_kits', 'all_kit_part_item.all_kit_id', '=', 'all_kits.id')
                ->join('kit', 'all_kits.part_id', '=', 'kit.id')
                ->where('all_kit_part_item.part_id', $part_id)
                ->groupBy('all_kit_part_item.part_id', 'all_kit_part_item.source_id', 'all_kit_part_item.status_id', 'all_kit_part_item.quality_id', 'kit.name', 'kit.id')
                ->with('source')->with('status')->with('part_quality')->with('part')
            ->get();

            $data['sectionss'] = StoreSection::select([
            'store_section.part_id',
            'store_section.type_id',
            'store_section.source_id',
            'store_section.status_id',
            'store_section.quality_id',
            DB::raw('SUM(store_section.amount) as amount'),
            'store_structure.id',
            'store_structure.name',
            'store_section.store_id',
            'store_section.type_id'
            ])
            ->join('store_structure', 'store_section.section_id', '=', 'store_structure.id')
            ->where('store_section.part_id', $part_id)
            ->where('store_section.type_id', 1)
            ->groupBy('store_section.part_id', 'store_section.type_id', 'store_section.source_id', 'store_section.status_id', 'store_section.quality_id','store_structure.id','store_structure.name','store_section.store_id','store_section.type_id')
            ->havingRaw('SUM(store_section.amount) > 0')
            ->with('source')->with('status')->with('part_quality')->with('part')->with('store')->get();

            $AllStoredata = new \Illuminate\Database\Eloquent\Collection;
            foreach ($allstores as $key => $store) {
                $sdata=[];
                $store_model = ucfirst($store->table_name);
                if ($store_model == 'Damaged_parts') {
                    $store_model = 'damagedPart';
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $sdata['data'] = [];
                    $sdata['store'] = $store;
                }else{

                    $entity_tbl = 'App\\Models\\' . $store_model;

                     $query1x =  $entity_tbl::join('stores_log', $store->table_name.'.store_log_id', '=', 'stores_log.id')
                            ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                            ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_parts.part_id, all_parts.source_id, all_parts.status_id, all_parts.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                            ->groupBy('all_parts.part_id', 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id',  $store->table_name . '.type_id')
                            ->where($store->table_name.'.type_id',1)->where($store->table_name.'.part_id',$part_id)->where($store->table_name.'.amount','>',0);
                    $entity = $query1x->with([
                            'stores_log.all_parts.part.part_numbers',
                            'stores_log.all_parts.source',
                            'stores_log.all_parts.status',
                            'stores_log.all_parts.part_quality',
                            'stores_log.all_parts.sectionswithoutorder.store',
                            'stores_log.all_parts.sectionswithoutorder.store_structure',
                            'stores_log.all_parts.pricing.sale_type',
                            'stores_log.all_parts.order_supplier',
                            'stores_log.all_parts.part.sub_group.group'
                        ])
                        ->get();
                    $sdata['data'] = $entity;
                    $sdata['store'] = $store;
                }
                $AllStoredata->push($sdata);

            }

                $data['allStores'] = $AllStoredata;

                $pricelistparts =  SalePricing::where('part_id',$part_id)
                ->where('type_id',1)->where('to',null)
                ->groupBy('part_id', 'source_id', 'status_id', 'quality_id','type_id')->select([
                    'part_id',
                    'type_id',
                    'source_id',
                    'status_id',
                    'quality_id'
                    ])->with('source')->with('status')->with('part_quality')->get();

                foreach ($pricelistparts as $key => $value) {
                    $value['sale_types'] = SalePricing::where('part_id',$value->part_id)->where('to',null)->where('source_id',$value->source_id)->where('status_id',$value->status_id)->where('quality_id',$value->quality_id)->where('type_id',1)->with('sale_typex')->get();
                }

                $data['pricelist'] = $pricelistparts;

                $presaleOrder=PresaleOrderPart::with('presale_order.store')
                ->with('presale_order.client')
                ->with('part')
                ->with('source')
                ->with('status')
                ->with('quality')
                ->where('part_id', $part_id)
                ->where('part_type_id', 1)
                ->get([
                    'presale_order_parts.id',
                    'presale_order_parts.part_id',
                    'presale_order_parts.notes',
                    'presale_order_parts.amount',
                    'presale_order_parts.presaleOrder_id',
                    'presale_order_parts.status_id',
                    'presale_order_parts.source_id',
                    'presale_order_parts.quality_id',
                    'presale_order_parts.part_type_id',
                    'presale_order_parts.price'
                ]);
                $data['presaleOrder'] = $presaleOrder;
                // return $results = $presaleOrder->map(function ($part) {
                //     return [
                //         'id' => $part->id,
                //         'part_id' => $part->part_id,
                //         'notes' => $part->notes,
                //         'amount' => $part->amount,
                //         'presaleOrder_id' => $part->presaleOrder_id,
                //         'status_id' => $part->status_id,
                //         'source_id' => $part->source_id,
                //         'quality_id' => $part->quality_id,
                //         'part_type_id' => $part->part_type_id,
                //         'price' => $part->price,
                //         'name' => $part->presale_order->name,
                //         'due_date' => $part->presale_order->due_date,
                //         'flag' => $part->presale_order->flag,
                //         'store_id' => $part->presale_order->store_id,
                //     ];
                // })->toArray();
        }elseif($type_id==6){
           $data = Kit::where('id',$part_id)
            ->with('kit_numbers.supplier')->with('kit_details.kit_spec')
            ->with('kit_models.series.model')
            ->with('kit_parts.part')
            ->with('all_kits.status')
            ->with('all_kits.source')
            ->with('all_kits.part_quality')
            ->with('all_kits.pricing')
            ->with('all_kits.sections.store_structure')
            ->with('all_kits.sections.store')
            ->with('all_kits.order_supplier.supplier')
            ->with('all_kits.order_supplier.currency_type')
            ->with('all_kits.kit')
            ->addSelect('*',DB::raw("'Kit' as type"))->first();

            $data['sectionss'] = StoreSection::select([
                'store_section.part_id',
                'store_section.type_id',
                'store_section.source_id',
                'store_section.status_id',
                'store_section.quality_id',
                DB::raw('SUM(store_section.amount) as amount'),
                'store_structure.id',
                'store_structure.name',
                'store_section.store_id',
                'store_section.type_id'
            ])
            ->join('store_structure', 'store_section.section_id', '=', 'store_structure.id')
            ->where('store_section.part_id', $part_id)
            ->where('store_section.type_id', 6)
            ->groupBy('store_section.part_id', 'store_section.type_id', 'store_section.source_id', 'store_section.status_id', 'store_section.quality_id','store_structure.id','store_structure.name','store_structure.id','store_section.store_id','store_section.type_id')
            ->havingRaw('SUM(store_section.amount) > 0')
            ->with('source')->with('status')->with('part_quality')->with('kit')->with('store')->get();



            $AllStoredata = new \Illuminate\Database\Eloquent\Collection;
            foreach ($allstores as $key => $store) {
                $sdata=[];
                $store_model = ucfirst($store->table_name);
                if ($store_model == 'Damaged_parts') {
                    $store_model = 'damagedPart';
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $sdata['data'] = [];
                    $sdata['store'] = $store;
                }else{

                    $entity_tbl = 'App\\Models\\' . $store_model;

                    $entity =  $entity_tbl::join('stores_log', $store->table_name.'.store_log_id', '=', 'stores_log.id')
                    ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                    ->where($store->table_name.'.type_id',6)
                    ->where($store->table_name.'.part_id',$part_id)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_kits.part_id, all_kits.source_id, all_kits.status_id, all_kits.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_kits.part_id', 'all_kits.source_id', 'all_kits.status_id', 'all_kits.quality_id',$store->table_name.'.type_id')
                    ->with([
                    'stores_log.all_kits.kit.kit_numbers',
                    'stores_log.all_kits.source',
                    'stores_log.all_kits.status',
                    'stores_log.all_kits.part_quality',
                    'stores_log.all_kits.sectionswithoutorder.store',
                    'stores_log.all_kits.sectionswithoutorder.store_structure',
                    'stores_log.all_kits.pricing.sale_type',
                    'stores_log.all_kits.order_supplier.buy_transaction'
                ])
                ->get();
                    $sdata['data'] = $entity;
                    $sdata['store'] = $store;
                }
                $AllStoredata->push($sdata);

            }

            $data['allStores'] = $AllStoredata;

            $pricelistparts =  SalePricing::where('part_id',$part_id)
            ->where('type_id',6)->where('to',null)
            ->groupBy('part_id', 'source_id', 'status_id', 'quality_id','type_id')->select([
                'part_id',
                'type_id',
                'source_id',
                'status_id',
                'quality_id'
            ])->with('source')->with('status')->with('part_quality')->get();

            foreach ($pricelistparts as $key => $value) {
                    $value['sale_types'] = SalePricing::where('part_id',$value->part_id)->where('to',null)->where('source_id',$value->source_id)->where('status_id',$value->status_id)->where('quality_id',$value->quality_id)->where('type_id',6)->with('sale_typex')->get();
            }

            $data['pricelist'] = $pricelistparts;
            $presaleOrder=PresaleOrderPart::with('presale_order.store')
            ->with('presale_order.client')
            ->with('kit')
            ->where('part_id', $part_id)
            ->where('part_type_id', 6)
            ->get([
                'presale_order_parts.id',
                'presale_order_parts.part_id',
                'presale_order_parts.notes',
                'presale_order_parts.amount',
                'presale_order_parts.presaleOrder_id',
                'presale_order_parts.status_id',
                'presale_order_parts.source_id',
                'presale_order_parts.quality_id',
                'presale_order_parts.part_type_id',
                'presale_order_parts.price'
            ]);
            $data['presaleOrder'] = $presaleOrder;
        }elseif($type_id==2){
            $data = Wheel::where('id',$part_id)
            ->with('wheel_dimension')
            ->with('wheel_material')
            ->with('wheel_details.wheel_spec')
            ->with('wheel_model')
            ->with('related_wheels.wheel')
            ->with('all_wheels.status')
            ->with('all_wheels.source')
            ->with('all_wheels.part_quality')
            ->with('all_wheels.pricing')
            ->with('all_wheels.sections.store_structure')
            ->with('all_wheels.sections.store')
            ->with('all_wheels.order_supplier.supplier')
            ->with('all_wheels.order_supplier.currency_type')
            ->with('all_wheels.wheel')
            ->addSelect('*',DB::raw("'Wheel' as type"))->first();

            $data['sectionss'] = StoreSection::select([
                'store_section.part_id',
                'store_section.type_id',
                'store_section.source_id',
                'store_section.status_id',
                'store_section.quality_id',
                DB::raw('SUM(store_section.amount) as amount'),
                'store_structure.id',
                'store_structure.name',
                'store_section.store_id',
                'store_section.type_id'
            ])
            ->join('store_structure', 'store_section.section_id', '=', 'store_structure.id')
            ->where('store_section.part_id', $part_id)
            ->where('store_section.type_id', 2)
            ->groupBy('store_section.part_id', 'store_section.type_id', 'store_section.source_id', 'store_section.status_id', 'store_section.quality_id','store_structure.id','store_structure.name','store_structure.id','store_section.store_id','store_section.type_id')
            ->havingRaw('SUM(store_section.amount) > 0')
            ->with('source')->with('status')->with('part_quality')->with('wheel')->with('store')->get();

            $AllStoredata = new \Illuminate\Database\Eloquent\Collection;
            foreach ($allstores as $key => $store) {
                $sdata=[];
                $store_model = ucfirst($store->table_name);
                if ($store_model == 'Damaged_parts') {
                    $store_model = 'damagedPart';
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $sdata['data'] = [];
                    $sdata['store'] = $store;
                }else{

                    $entity_tbl = 'App\\Models\\' . $store_model;

                     $entity =   $entity_tbl::join('stores_log', $store->table_name.'.store_log_id', '=', 'stores_log.id')
                    ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                    ->where($store->table_name.'.type_id',2)
                    ->where($store->table_name.'.part_id',$part_id)
                    ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_wheels.part_id, all_wheels.source_id, all_wheels.status_id, all_wheels.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                    ->groupBy('all_wheels.part_id', 'all_wheels.source_id', 'all_wheels.status_id', 'all_wheels.quality_id',$store->table_name.'.type_id')
                    ->with([
                    'stores_log.all_wheels.source',
                    'stores_log.all_wheels.status',
                    'stores_log.all_wheels.part_quality',
                    'stores_log.all_wheels.sectionswithoutorder.store',
                    'stores_log.all_wheels.sectionswithoutorder.store_structure',
                    'stores_log.all_wheels.pricing.sale_type',
                    'stores_log.all_wheels.order_supplier.buy_transaction'
                ])
                 ->get();

                    $sdata['data'] = $entity;
                    $sdata['store'] = $store;
                }
                $AllStoredata->push($sdata);

            }

            $data['allStores'] = $AllStoredata;

            $pricelistparts =  SalePricing::where('part_id',$part_id)
            ->where('type_id',2)->where('to',null)
            ->groupBy('part_id', 'source_id', 'status_id', 'quality_id','type_id')->select([
                'part_id',
                'type_id',
                'source_id',
                'status_id',
                'quality_id'
                ])->with('source')->with('status')->with('part_quality')->get();

                foreach ($pricelistparts as $key => $value) {
                    $value['sale_types'] = SalePricing::where('part_id',$value->part_id)->where('to',null)->where('source_id',$value->source_id)->where('status_id',$value->status_id)->where('quality_id',$value->quality_id)->where('type_id',2)->with('sale_typex')->get();
                }

                $data['pricelist'] = $pricelistparts;

                $presaleOrder=PresaleOrderPart::with('presale_order.store')
                ->with('presale_order.client')
                ->with('wheel')
                ->where('part_id', $part_id)
                ->where('part_type_id', 2)
                ->get([
                    'presale_order_parts.id',
                    'presale_order_parts.part_id',
                    'presale_order_parts.notes',
                    'presale_order_parts.amount',
                    'presale_order_parts.presaleOrder_id',
                    'presale_order_parts.status_id',
                    'presale_order_parts.source_id',
                    'presale_order_parts.quality_id',
                    'presale_order_parts.part_type_id',
                    'presale_order_parts.price'
                ]);
                $data['presaleOrder'] = $presaleOrder;
        }
        // return $data;
         $allsource  =Source::all();
        $allstatus  =Status::all();
        $allpart_quality =PartQuality::all();

        $invoices='';
        if($type_id == 1){
            $invoices=InvoiceItem::where('part_id',$part_id)->where('part_type_id',$type_id)->where('amount','>',0)
            ->with('source')
            ->with('status')
            ->with('part_quality')
            ->with('part.getsmallunit.unit')
            ->with('invoice_items_sections.store_structure')
            ->with('invoice.client')
            ->with('invoice.refund_invoices')
            ->with('invoice_items_refund.invitem')
            ->get();
        }elseif($type_id == 2){
            $invoices=InvoiceItem::where('part_id',$part_id)->where('part_type_id',$type_id)->where('amount','>',0)
            ->with('source')
            ->with('status')
            ->with('part_quality')
            ->with('wheel')
            ->with('invoice_items_sections.store_structure')
            ->with('invoice.client')
            ->with('invoice.refund_invoices')
            ->with('invoice_items_refund.invitem')
            ->get();
        }elseif($type_id == 6){
            $invoices=InvoiceItem::where('part_id',$part_id)->where('part_type_id',$type_id)->where('amount','>',0)
            ->with('source')
            ->with('status')
            ->with('part_quality')
            ->with('kit')
            ->with('invoice_items_sections.store_structure')
            ->with('invoice.client')
            ->with('invoice.refund_invoices')
            ->with('invoice_items_refund')
            ->get();
        }

        // return $data;

        return view('itemsDetails',compact('data','allsource','allstatus','allpart_quality','invoices'));


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
    public function updatepart(Request $request){
          $data = $request;
        // $validatedData = $request->validate([
        //     'id' => 'required|exists:presale_order_parts,id',
        //     'values' => 'required|array',
        //     'values.source' => 'nullable|exists:sources,id', // Validate according to your needs
        //     'values.status' => 'nullable|exists:statuses,id',
        //     'values.quality' => 'nullable|exists:qualities,id',
        //     'values.amount' => 'nullable|numeric',
        // ]);

        try {
            // all part amount = remain_amount + kit + invoices/presale
            // invoice_items if exist destroy
            if($data['type_id'] == 1){
                $part_model='AllPart';

            }elseif ($data['type_id'] == 6) {
                $part_model='AllKit';
            }
            elseif ($data['type_id'] == 2) {
                $part_model='AllWheel';
            }
            $Model = 'App\\Models\\' . $part_model;
             $foundininvoices = $Model::where('id',$data->id)->first();
            // $part_in_allkit_item_sum = 0;
            // if($foundininvoices->part_in_allkit_item_sum_amount){
            //     $part_in_allkit_item_sum = $foundininvoices->part_in_allkit_item_sum_amount;
            // }else{
            //     $part_in_allkit_item_sum = 0;
            // }
            if($foundininvoices->amount == $foundininvoices->remain_amount){

                $allpartx = $Model::findOrFail($data->id);
                $allpartx->sections()->update([
                    'source_id' => $data->values['source'] ,
                    'status_id' => $data->values['status'] ,
                    'quality_id' => $data->values['quality'] ,
                ]);

                $allpartx->replayorderss()->update([
                    'source_id' => $data->values['source'] ,
                    'status_id' => $data->values['status'] ,
                    'quality_id' => $data->values['quality'] ,
                ]);

                $allpartx->update([
                    'source_id' => $data->values['source'] ,
                    'status_id' => $data->values['status'] ,
                    'quality_id' => $data->values['quality'] ,
                ]);




                // $allpartx->part_in_allkit_item()->update([
                //     'source_id' => $data->values['source'] ,
                //     'status_id' => $data->values['status'] ,
                //     'quality_id' => $data->values['quality'] ,
                // ]);

                return response()->json(['success' => true , 'message' => 'تم تعديل الصنف بنجاح']);

            }else{
                return response()->json(['success' => false , 'message' => 'لا يمكن التعديل هذا الصنف موجود في فاتورة بيع أو موجود في كيت']);
            }


            // return $row = AllPart::findOrFail($data['id']);
            // replayorder
            // all_part
            // store_log
            // pricing
            // allpartkititem
            //
            // sto
            // $values = $data['values']


            // foreach ($values as $field => $value) {
            //     $row->$field = $value;
            // }

            // $part->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }



    }
    public function storeStructure($store_id){
        return storeStructure::where('store_id',$store_id)->get();
    }

     public function correctKit(Request $request){
        // return $request;

        if($request->allkitId){
            $allkitdata = AllKit::where('id',$request->allkitId)->first();
            $allkitdataAmount = KitPart::where('part_id',$request->partid)->first();
            AllKitPartItem::create([
                'all_kit_id' => $request->allkitId,
                'part_id' => $request->partid,
                'source_id' => $request->sourceid,
                'status_id' => $request->statusid,
                'quality_id' => $request->qualityid,
                'order_sup_id' => $allkitdata->order_sup_id,
                'note' => 'تجمييع',
                'amount' => $allkitdataAmount->amount
            ]);

            $storeSections = StoreSection::where('part_id',$request->partid)
            ->where('source_id',$request->sourceid)
            ->where('status_id',$request->statusid)
            ->where('quality_id',$request->qualityid)
            ->get();


            $totalAmount = $request->sectionAmounts;
            foreach ($storeSections as $key => $value) {
                if($value->amount >= $totalAmount){
                    StoreSection::where('id',$value->id)->decrement('amount', $totalAmount );
                    break;
                }else if($totalAmount > $value->amount){
                        StoreSection::where('id',$value->id)->decrement('amount',  $value->amount);
                        $totalAmount = $totalAmount - $value->amount;
                }else if($totalAmount < 0){
                        break;
                }
            }
        }else{
            $storeSections = StoreSection::where('part_id',$request->partid)
            ->where('source_id',$request->sourceid)
            ->where('status_id',$request->statusid)
            ->where('quality_id',$request->qualityid)
            ->get();


            $totalAmount = $request->sectionAmounts;
            foreach ($storeSections as $key => $value) {
                if($value->amount >= $totalAmount){
                    StoreSection::where('id',$value->id)->decrement('amount', $totalAmount );
                    break;
                }else if($totalAmount > $value->amount){
                        StoreSection::where('id',$value->id)->decrement('amount',  $value->amount);
                        $totalAmount = $totalAmount - $value->amount;
                }else if($totalAmount < 0){
                        break;
                }
            }

        }





        return redirect()->back();

    }


    public function getStoreAmount($type_id,$part_id,$source_id,$status_id,$quality_id,$order_sup_id){
        $data = new \Illuminate\Database\Eloquent\Collection;
        $allstores = Store::all();
        if($type_id==1){
            $data['data']= AllPart::where('part_id', $part_id)->where('source_id', $source_id)
            ->where('status_id', $status_id)->where('quality_id', $quality_id)
            ->where('order_supplier_id', $order_sup_id)
            ->orderBy('id', 'ASC')
            ->with('order_supplier')
            ->with('store_log')
            ->get();

            $AllStoredata = new \Illuminate\Database\Eloquent\Collection;
            foreach ($allstores as $key => $store) {
                $sdata=[];
                $store_model = ucfirst($store->table_name);
                if ($store_model == 'Damaged_parts') {
                    $store_model = 'damagedPart';
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $sdata['data'] = [];
                    $sdata['store'] = $store;
                }else{
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $query1x =  $entity_tbl::where($store->table_name.'.type_id',$type_id)->where($store->table_name.'.part_id',$part_id)->where($store->table_name.'.supplier_order_id',$order_sup_id)->get();
                    $sdata['store'] = $store;
                    $sdata['data'] = $query1x;
                }
                $AllStoredata->push($sdata);

            }

            $data['allStores'] = $AllStoredata;

            $data['sections'] = StoreSection::where('part_id', $part_id)->where('source_id', $source_id)
            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('type_id', $type_id)
            ->with('store')
            ->with('store_structure')
            ->get();


            $data['invoices'] = InvoiceItem::where('part_id', $part_id)->where('source_id', $source_id)
            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('part_type_id', $type_id)
            ->with('invoice_item_order_suppliers')
            ->with('invoice_item_section')
            ->get();

            foreach ($data['invoices'] as $key => $invItems) {
                $invItems['refund'] = RefundInvoice::where('item_id',$invItems->id)->where('invoice_id',$invItems->invoice_id)->get();
            }

            $data['talef'] = Talef::where('part_id', $part_id)->where('source_id', $source_id)
            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('type_id', $type_id)
            ->with('store')
            ->get();

            return $data;
        }elseif($type_id==2){
            $data['data']= AllWheel::where('part_id', $part_id)->where('source_id', $source_id)
            ->where('status_id', $status_id)->where('quality_id', $quality_id)
            ->where('order_supplier_id', $order_sup_id)
            ->orderBy('id', 'ASC')
            ->with('order_supplier')
            ->with('store_log')
            ->get();

            $AllStoredata = new \Illuminate\Database\Eloquent\Collection;
            foreach ($allstores as $key => $store) {
                $sdata=[];
                $store_model = ucfirst($store->table_name);
                if ($store_model == 'Damaged_parts') {
                    $store_model = 'damagedPart';
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $sdata['data'] = [];
                    $sdata['store'] = $store;
                }else{
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $query1x =  $entity_tbl::where($store->table_name.'.type_id',$type_id)->where($store->table_name.'.part_id',$part_id)->where($store->table_name.'.supplier_order_id',$order_sup_id)->get();
                    $sdata['store'] = $store;
                    $sdata['data'] = $query1x;
                }
                $AllStoredata->push($sdata);

            }

            $data['allStores'] = $AllStoredata;

            $data['sections'] = StoreSection::where('part_id', $part_id)->where('source_id', $source_id)
            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('type_id', $type_id)
            ->with('store')
            ->with('store_structure')
            ->get();


            $data['invoices'] = InvoiceItem::where('part_id', $part_id)->where('source_id', $source_id)
            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('part_type_id', $type_id)
            ->with('invoice_item_order_suppliers')
            ->with('invoice_item_section')
            ->get();

            foreach ($data['invoices'] as $key => $invItems) {
                $invItems['refund'] = RefundInvoice::where('item_id',$invItems->id)->where('invoice_id',$invItems->invoice_id)->get();
            }

            $data['talef'] = Talef::where('part_id', $part_id)->where('source_id', $source_id)
            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('type_id', $type_id)
            ->with('store')
            ->get();

            return $data;
        }elseif($type_id==6){
            $data['data']= AllKit::where('part_id', $part_id)->where('source_id', $source_id)
            ->where('status_id', $status_id)->where('quality_id', $quality_id)
            ->where('order_supplier_id', $order_sup_id)
            ->orderBy('id', 'ASC')
            ->with('order_supplier')
            ->with('store_log')
            ->get();

            $AllStoredata = new \Illuminate\Database\Eloquent\Collection;
            foreach ($allstores as $key => $store) {
                $sdata=[];
                $store_model = ucfirst($store->table_name);
                if ($store_model == 'Damaged_parts') {
                    $store_model = 'damagedPart';
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $sdata['data'] = [];
                    $sdata['store'] = $store;
                }else{
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $query1x =  $entity_tbl::where($store->table_name.'.type_id',$type_id)->where($store->table_name.'.part_id',$part_id)->where($store->table_name.'.supplier_order_id',$order_sup_id)->get();
                    $sdata['store'] = $store;
                    $sdata['data'] = $query1x;
                }
                $AllStoredata->push($sdata);

            }

            $data['allStores'] = $AllStoredata;

            $data['sections'] = StoreSection::where('part_id', $part_id)->where('source_id', $source_id)
            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('type_id', $type_id)
            ->with('store')
            ->with('store_structure')
            ->get();


            $data['invoices'] = InvoiceItem::where('part_id', $part_id)->where('source_id', $source_id)
            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('part_type_id', $type_id)
            ->with('invoice_item_order_suppliers')
            ->with('invoice_item_section')
            ->get();

            foreach ($data['invoices'] as $key => $invItems) {
                $invItems['refund'] = RefundInvoice::where('item_id',$invItems->id)->where('invoice_id',$invItems->invoice_id)->get();
            }

            $data['talef'] = Talef::where('part_id', $part_id)->where('source_id', $source_id)
            ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('type_id', $type_id)
            ->with('store')
            ->get();

            return $data;
        }
    }

    public function UpdateAmountss(Request $request){
        // return $request;

        $tablename = $request->tablename;
        $fieldName = $request->fieldName;
        $tid = $request->tid;
        $newAmount = $request->newAmount;

        $updated =DB::table($tablename)
            ->where('id', $tid)
            ->update([$fieldName => $newAmount]);

        if ($updated) {
            return true;
        } else {
            return false;
        }
    }


      public function correctRefund(){
        $refundinvoice = RefundInvoice::all();
        $invoiceitems = InvoiceItem::all();

        $data=[];
        foreach ($refundinvoice as $key => $ref) {
            $invoiceItemsFound = InvoiceItem::where('id',$ref->item_id)->first();

            if(!$invoiceItemsFound){

               $allpartdata = AllPart::where('id',$ref->item_id)->first();
            //    array_push($data, $ref);
                if($allpartdata){
                    $newitem = InvoiceItem::where('part_id',$allpartdata->part_id)->where('part_type_id',1)->where('invoice_id',$ref->invoice_id)->first();
                    // array_push($data, $ref);
                  $ref->update([
                    'item_id' => $newitem->id
                  ]);
                }

            }


        }

        return $data;
    }

    public function storeDailyReport0($store_id){
        $store_data = Store::where('id',$store_id)->get();
        $date = Carbon::now();
        $storeInvoiceLog = DB::table('storesInvoicesLog')
        ->whereDate('created_at', '>=', $date)
        ->where('store_id', $store_id)
        ->select(
            'part_id',
            'source_id',
            'status_id',
            'quality_id',
            'store_id',
            'type_id',
            DB::raw('COUNT(*) as count'),
            DB::raw('MAX(old_amount) as first_old_amount'),
            DB::raw('SUM(amount) as amount')
        )
        ->groupBy('part_id', 'source_id', 'status_id', 'quality_id', 'type_id', 'store_id')
        ->get();

       $wareed = StoresLog::where('store_id', $store_id)
      ->whereDate('date', '>=', $date)
      ->where('store_action_id','=', 3)
      ->get();
        $sader = StoresLog::whereDate('date', '>=', $date)
       ->where('store_id','=', $store_id)
       ->where('store_action_id','=', 2)
        ->get();

        $combinedLog = $wareed->merge($sader);
        $combinedLog = $combinedLog->sortBy('date');
        $storelogss=[];
        foreach ($combinedLog as $key => $log) {
            $AlltblName='all_parts';
            if($log->type_id == 1){
                $AlltblName='all_parts';
            }elseif($log->type_id == 2){
                $AlltblName='all_wheels';
            }elseif($log->type_id == 3){
                $AlltblName='all_tractors';
            }elseif($log->type_id == 4){
                $AlltblName='all_clarcks';
            }elseif($log->type_id == 5){
                $AlltblName='all_equips';
            }elseif($log->type_id == 6){
                $AlltblName='all_kits';
            }

            $storelogss[] = StoresLog::join($AlltblName, 'stores_log.All_part_id', '=', $AlltblName.'.id')
            ->where('stores_log.id', $log->id)
            ->select($AlltblName.'.part_id',
            $AlltblName.'.source_id',
            $AlltblName.'.status_id',
            $AlltblName.'.quality_id',
            'stores_log.store_id',
            DB::raw('0 AS amount'), 
            // 'stores_log.type_id',$AlltblName.'.remain_amount as first_old_amount')
            DB::raw('0 AS first_old_amount'))
            ->get();

        }

        $storelogss = collect($storelogss)->flatten();;
        $merged = $storelogss->merge($storeInvoiceLog);

        $storeInvoiceLog = $merged->unique(function($item) {
            return $item->part_id . '-' .  $item->source_id . '-' . $item->status_id . '-' . $item->quality_id . '-' . $item->type_id . '-' . $item->store_id;
        });

        // return $storeInvoiceLog;
        foreach ($storeInvoiceLog as $key => $item) {

            $item->invoice_details = InvoiceItem::whereDate('date', '>=', $date)
            ->whereHas('invoice', function ($query) use ($store_id) {
                $query->where('store_id', $store_id);
            })
            ->where('part_id',$item->part_id)
            ->where('source_id',$item->source_id)
            ->where('status_id',$item->status_id)
            ->where('quality_id',$item->quality_id)
            ->where('part_type_id',$item->type_id)
            ->with('invoice.client')
            ->get();


            $item->buy_items = Replyorder::where('part_id',$item->part_id)
            ->whereDate('creation_date', '>=', $date)
            ->where('source_id',$item->source_id)
            ->where('status_id',$item->status_id)
            ->where('quality_id',$item->quality_id)
            ->where('part_type_id',$item->type_id)
            ->with('order_supplier.supplier')
            ->get();
            $AlltblName='all_parts';
            if($item->type_id == 1){
                $item->part = Part::where('id',$item->part_id)->first();
                $AlltblName='all_parts';

            }elseif($item->type_id == 2){
                $item->part = Whhel::where('id',$item->part_id)->first();
                $AlltblName='all_wheels';
            }elseif($item->type_id == 3){
                $item->part = Tracttor::where('id',$item->part_id)->first();
                $AlltblName='all_tractors';
            }elseif($item->type_id == 4){
                $item->part = Clark::where('id',$item->part_id)->first();
                $AlltblName='all_clarcks';

            }elseif($item->type_id == 5){
                $item->part = Equip::where('id',$item->part_id)->first();
                $AlltblName='all_equips';

            }elseif($item->type_id == 6){
                $item->part = Kit::where('id',$item->part_id)->first();
                $AlltblName='all_kits';

            }

            $item->storeLogs = StoresLog::join($AlltblName, 'stores_log.All_part_id', '=', $AlltblName.'.id')
            ->where($AlltblName.'.source_id',$item->source_id)
            ->where($AlltblName.'.status_id',$item->status_id)
            ->where($AlltblName.'.quality_id',$item->quality_id)
            ->where($AlltblName.'.part_id',$item->part_id)
            ->where($AlltblName.'.part_id',$item->part_id)
            ->where('stores_log.status', 3)
            ->where('stores_log.store_action_id', 3)
            ->where('stores_log.store_id', $item->store_id)
            ->select('stores_log.*')
            ->whereDate('stores_log.date', '>=', $date)
            ->get();

            // التحويلات الي مواقع اخري
            $item->storeLogsDone = StoresLog::join($AlltblName, 'stores_log.All_part_id', '=', $AlltblName.'.id')
            ->where($AlltblName.'.source_id',$item->source_id)
            ->where($AlltblName.'.status_id',$item->status_id)
            ->where($AlltblName.'.quality_id',$item->quality_id)
            ->where($AlltblName.'.part_id',$item->part_id)
            ->where($AlltblName.'.part_id',$item->part_id)
            ->where('stores_log.store_id', $item->store_id)
            ->where('stores_log.store_action_id','=', 2)
            ->select('stores_log.*')
            ->whereDate('stores_log.date', '>=', $date)
            ->get();


            $item->refundItems = RefundInvoice::join('invoice_items', 'refund_invoice.item_id', '=', 'invoice_items.id')
            ->join('invoice', 'invoice_items.invoice_id', '=', 'invoice.id')
            ->where('invoice.store_id',$item->store_id)
            ->where('invoice_items.source_id',$item->source_id)
                ->where('invoice_items.status_id',$item->status_id)
                ->where('invoice_items.quality_id',$item->quality_id)
                ->where('invoice_items.part_type_id',$item->type_id)
                ->where('invoice_items.part_id',$item->part_id)
            ->select('refund_invoice.*')
            ->whereDate('refund_invoice.date', '>=', $date)
            ->get();
            $tableN = $store_data[0]->table_name;

            $item_type_id = $item->type_id;
            if(!$item_type_id){
                $item_type_id = 0;
            }
            $item->sections = DB::table($tableN)
            ->leftJoin('stores_log', 'stores_log.id', '=', $tableN . '.store_log_id')
            ->leftJoin($AlltblName, 'stores_log.All_part_id', '=', $AlltblName.'.id')
            ->leftJoin('source', $AlltblName.'.source_id', '=', 'source.id')
            ->leftJoin('status', $AlltblName.'.status_id', '=', 'status.id')
            ->leftJoin('part_quality', $AlltblName.'.quality_id', '=', 'part_quality.id')
            ->select(
                $AlltblName.'.part_id as part',
                $tableN . '.store_log_id as store_log_id',
                $AlltblName.'.source_id as source_id',
                $AlltblName.'.status_id as status_id',
                $AlltblName.'.quality_id as quality_id',
                'source.name_arabic as source_name',
                'status.name as status_name',
                'part_quality.name as quality_name',
                $tableN . '.type_id as type_id',
                DB::raw('SUM(' . $tableN . '.amount) as storeAmount'),
                DB::raw('COALESCE((
                            SELECT SUM(amount)
                            FROM store_section
                            WHERE ' . $tableN . '.type_id = '.$item_type_id.'
                            AND store_id = ' . $store_id . '
                                AND part_id = '.$AlltblName.'.part_id
                                AND source_id = '.$AlltblName.'.source_id
                                AND status_id = '.$AlltblName.'.status_id
                                AND quality_id = '.$AlltblName.'.quality_id
                            GROUP BY part_id, source_id, status_id, quality_id
                        ), 0) AS sectionAmount')
            )
            ->where($AlltblName.'.source_id',$item->source_id)
            ->where($AlltblName.'.status_id',$item->status_id)
            ->where($AlltblName.'.quality_id',$item->quality_id)
            ->where($AlltblName.'.part_id',$item->part_id)
            ->where($tableN . '.type_id', $item->type_id)
            ->groupBy($AlltblName.'.part_id', $AlltblName.'.source_id', $AlltblName.'.status_id', $AlltblName.'.quality_id', $tableN . '.store_log_id', $tableN . '.type_id', 'source.name_arabic', 'status.name', 'part_quality.name')
            ->havingRaw('(storeAmount - COALESCE(sectionAmount, 0)) > 0')
            ->get();

            $item->source = Source::where('id',$item->source_id)->first();
            // dd($item->status_id);
            $item->part_status = Status::where('id',$item->status_id)->first();
            $item->quality = PartQuality::where('id',$item->quality_id)->first();

            $item->Code = 'FN'.$item->type_id.$item->part_id.$item->source_id.$item->status_id.$item->quality_id;


        }


        // return $storeInvoiceLog;
        return view('storeDailyReport',compact('store_data','storeInvoiceLog'));


    }
    
    public function storeDailyReport($store_id){
        $store_data = Store::where('id',$store_id)->get();
        $date = Carbon::now();
        // حركات البيع
        $storeInvoiceLog = DB::table('storesInvoicesLog')
        ->whereDate('created_at', '>=', $date)
        ->where('store_id', $store_id)
        ->select(
            'part_id',
            'source_id',
            'status_id',
            'quality_id',
            'store_id',
            'type_id',
            DB::raw('COUNT(*) as count'),
            DB::raw('MAX(old_amount) as first_old_amount'),
            DB::raw('SUM(amount) as amount')
        )
        ->groupBy('part_id', 'source_id', 'status_id', 'quality_id', 'type_id', 'store_id')
        ->get();
            // حركات الوارد
       $wareed = StoresLog::where('store_id', $store_id)
      ->whereDate('date', '>=', $date)
      ->where('store_action_id','=', 3)
      ->get();
      // حركات الصادر
        $sader = StoresLog::whereDate('date', '>=', $date)
       ->where('store_id','=', $store_id)
       ->where('store_action_id','=', 2)
        ->get();

        // حركات المرتجع

        $refundItemss = RefundInvoice::join('invoice_items', 'refund_invoice.item_id', '=', 'invoice_items.id')
        ->join('invoice', 'invoice_items.invoice_id', '=', 'invoice.id')
        ->where('invoice.store_id',$store_id)
        ->select('invoice_items.part_id',
        'invoice_items.source_id',
        'invoice_items.status_id',
        'invoice_items.quality_id',
        'invoice_items.part_type_id as type_id',
        DB::raw('refund_invoice.r_amount AS amount'),
        DB::raw('refund_invoice.r_amount AS first_old_amount'))
        ->whereDate('refund_invoice.date', '>=', $date)
        ->get();

        // حركات الشراء 
        $buyitemss = Replyorder::whereDate('creation_date', '>=', $date)
        ->select('part_id',
        'source_id',
        'status_id',
        'quality_id',
        'part_type_id as type_id',
        DB::raw('amount AS amount'),
        DB::raw('amount AS first_old_amount'))
        ->get();



        $combinedLog = $wareed->merge($sader)->merge($buyitemss)->merge($refundItemss);
        $combinedLog = $combinedLog->sortBy('date');
        $storelogss=[];
        foreach ($combinedLog as $key => $log) {
            $AlltblName='all_parts';
            if($log->type_id == 1){
                $AlltblName='all_parts';
            }elseif($log->type_id == 2){
                $AlltblName='all_wheels';
            }elseif($log->type_id == 3){
                $AlltblName='all_tractors';
            }elseif($log->type_id == 4){
                $AlltblName='all_clarcks';
            }elseif($log->type_id == 5){
                $AlltblName='all_equips';
            }elseif($log->type_id == 6){
                $AlltblName='all_kits';
            }

            $storelogss[] = StoresLog::join($AlltblName, 'stores_log.All_part_id', '=', $AlltblName.'.id')
            ->where('stores_log.id', $log->id)
            ->select($AlltblName.'.part_id',
            $AlltblName.'.source_id',
            $AlltblName.'.status_id',
            $AlltblName.'.quality_id',
            'stores_log.store_id',
            'stores_log.type_id',
            DB::raw('0 AS amount'),
            DB::raw('0 AS first_old_amount'))
            // 'stores_log.type_id',$AlltblName.'.remain_amount as first_old_amount')
            ->get();

        }

        $storelogss = collect($storelogss)->flatten();;
        $merged = $storelogss->merge($storeInvoiceLog);

        $storeInvoiceLog = $merged->unique(function($item) {
            return $item->part_id . '-' .  $item->source_id . '-' . $item->status_id . '-' . $item->quality_id . '-' . $item->type_id . '-' . $item->store_id;
        });

        // return $storeInvoiceLog;
        foreach ($storeInvoiceLog as $key => $item) {

    
            $item->invoice_details = InvoiceItem::whereDate('date', '>=', $date)
            ->whereHas('invoice', function ($query) use ($store_id) {
                $query->where('store_id', $store_id);
            })
            ->where('part_id',$item->part_id)
            ->where('source_id',$item->source_id)
            ->where('status_id',$item->status_id)
            ->where('quality_id',$item->quality_id)
            ->where('part_type_id',$item->type_id)
            ->with('invoice.client')
            ->get();


            $item->buy_items = Replyorder::where('part_id',$item->part_id)
            ->whereDate('creation_date', '>=', $date)
            ->where('source_id',$item->source_id)
            ->where('status_id',$item->status_id)
            ->where('quality_id',$item->quality_id)
            ->where('part_type_id',$item->type_id)
            ->with('order_supplier.supplier')
            ->get();
            $AlltblName='all_parts';
            if($item->type_id == 1){
                $item->part = Part::where('id',$item->part_id)->first();
                $AlltblName='all_parts';

            }elseif($item->type_id == 2){
                $item->part = Whhel::where('id',$item->part_id)->first();
                $AlltblName='all_wheels';
            }elseif($item->type_id == 3){
                $item->part = Tracttor::where('id',$item->part_id)->first();
                $AlltblName='all_tractors';
            }elseif($item->type_id == 4){
                $item->part = Clark::where('id',$item->part_id)->first();
                $AlltblName='all_clarcks';

            }elseif($item->type_id == 5){
                $item->part = Equip::where('id',$item->part_id)->first();
                $AlltblName='all_equips';

            }elseif($item->type_id == 6){
                $item->part = Kit::where('id',$item->part_id)->first();
                $AlltblName='all_kits';

            }

            $item->storeLogs = StoresLog::join($AlltblName, 'stores_log.All_part_id', '=', $AlltblName.'.id')
            ->where($AlltblName.'.source_id',$item->source_id)
            ->where($AlltblName.'.status_id',$item->status_id)
            ->where($AlltblName.'.quality_id',$item->quality_id)
            ->where($AlltblName.'.part_id',$item->part_id)
            ->where($AlltblName.'.part_id',$item->part_id)
            ->where('stores_log.status', 3)
            ->where('stores_log.store_action_id', 3)
            ->where('stores_log.store_id', $item->store_id)
            ->select('stores_log.*')
            ->whereDate('stores_log.date', '>=', $date)
            ->get();

            // التحويلات الي مواقع اخري
            $item->storeLogsDone = StoresLog::join($AlltblName, 'stores_log.All_part_id', '=', $AlltblName.'.id')
            ->where($AlltblName.'.source_id',$item->source_id)
            ->where($AlltblName.'.status_id',$item->status_id)
            ->where($AlltblName.'.quality_id',$item->quality_id)
            ->where($AlltblName.'.part_id',$item->part_id)
            ->where($AlltblName.'.part_id',$item->part_id)
            ->where('stores_log.store_id', $item->store_id)
            ->where('stores_log.store_action_id','=', 2)
            ->select('stores_log.*')
            ->whereDate('stores_log.date', '>=', $date)
            ->get();


            $item->refundItems = RefundInvoice::join('invoice_items', 'refund_invoice.item_id', '=', 'invoice_items.id')
            ->join('invoice', 'invoice_items.invoice_id', '=', 'invoice.id')
            ->where('invoice.store_id',$item->store_id)
            ->where('invoice_items.source_id',$item->source_id)
                ->where('invoice_items.status_id',$item->status_id)
                ->where('invoice_items.quality_id',$item->quality_id)
                ->where('invoice_items.part_type_id',$item->type_id)
                ->where('invoice_items.part_id',$item->part_id)
            ->select('refund_invoice.*')
            ->whereDate('refund_invoice.date', '>=', $date)
            ->get();
            $tableN = $store_data[0]->table_name;

            $item_type_id = $item->type_id;
            if(!$item_type_id){
                $item_type_id = 0;
            }
            $item->sections = DB::table($tableN)
            ->leftJoin('stores_log', 'stores_log.id', '=', $tableN . '.store_log_id')
            ->leftJoin($AlltblName, 'stores_log.All_part_id', '=', $AlltblName.'.id')
            ->leftJoin('source', $AlltblName.'.source_id', '=', 'source.id')
            ->leftJoin('status', $AlltblName.'.status_id', '=', 'status.id')
            ->leftJoin('part_quality', $AlltblName.'.quality_id', '=', 'part_quality.id')
            ->select(
                $AlltblName.'.part_id as part',
                $tableN . '.store_log_id as store_log_id',
                $AlltblName.'.source_id as source_id',
                $AlltblName.'.status_id as status_id',
                $AlltblName.'.quality_id as quality_id',
                'source.name_arabic as source_name',
                'status.name as status_name',
                'part_quality.name as quality_name',
                $tableN . '.type_id as type_id',
                DB::raw('SUM(' . $tableN . '.amount) as storeAmount'),
                DB::raw('COALESCE((
                            SELECT SUM(amount)
                            FROM store_section
                            WHERE ' . $tableN . '.type_id = '.$item_type_id.'
                            AND store_id = ' . $store_id . '
                                AND part_id = '.$AlltblName.'.part_id
                                AND source_id = '.$AlltblName.'.source_id
                                AND status_id = '.$AlltblName.'.status_id
                                AND quality_id = '.$AlltblName.'.quality_id
                            GROUP BY part_id, source_id, status_id, quality_id
                        ), 0) AS sectionAmount')
            )
            ->where($AlltblName.'.source_id',$item->source_id)
            ->where($AlltblName.'.status_id',$item->status_id)
            ->where($AlltblName.'.quality_id',$item->quality_id)
            ->where($AlltblName.'.part_id',$item->part_id)
            ->where($tableN . '.type_id', $item->type_id)
            ->groupBy($AlltblName.'.part_id', $AlltblName.'.source_id', $AlltblName.'.status_id', $AlltblName.'.quality_id', $tableN . '.store_log_id', $tableN . '.type_id', 'source.name_arabic', 'status.name', 'part_quality.name')
            ->havingRaw('(storeAmount - COALESCE(sectionAmount, 0)) > 0')
            ->get();

            $item->source = Source::where('id',$item->source_id)->first();
            // dd($item->status_id);
            $item->part_status = Status::where('id',$item->status_id)->first();
            $item->quality = PartQuality::where('id',$item->quality_id)->first();

            $item->Code = 'FN'.$item->type_id.$item->part_id.$item->source_id.$item->status_id.$item->quality_id;


        }


        // return $storeInvoiceLog;
        return view('storeDailyReport',compact('date','store_data','storeInvoiceLog'));


    }
}
