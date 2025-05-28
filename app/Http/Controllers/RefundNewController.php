<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\BuyTransaction;
use App\Models\OrderSupplier;
use App\Models\AllPart;
use App\Models\RefundBuy;
use App\Models\Allkit;
use App\Models\AllKitPartItem;
use App\Models\AllWheel;
use App\Models\StoresLog;
use App\Models\Replyorder;
use App\Models\BankType;
use App\Models\Clark;
use App\Models\Equip;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use App\Models\Kit;
use App\Models\Currency;
use App\Models\KitPart;
use App\Models\Part;
use App\Models\PartQuality;
use App\Models\PresaleOrderPart;
use App\Models\SalePricing;
use App\Models\Source;
use App\Models\Status;
use App\Models\Store;
use App\Models\StoreSection;
use App\Models\Supplier;
use App\Models\Tractor;
use App\Models\CurrencyType;
use App\Models\Wheel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RefundNewController extends Controller
{
    //
    public function refundCover()
    {
        //
        $stores = Store::where('table_name', '<>', 'damaged_parts')->get();
        $supplier = Supplier::get();
        $refunded_part = RefundBuy::where('type_id',1)->where('flag',0)->get();
        $refunded_wheel = RefundBuy::where('type_id',2)->where('flag',0)->get();
        $refunded_kit = RefundBuy::where('type_id',6)->where('flag',0)->get();

        return view('refund.refundCover', compact('stores', 'supplier', 'refunded_part', 'refunded_wheel', 'refunded_kit'));
    }

    public function refundBuyInv(BuyTransaction $id)
    {
        // return $id;
        $buyTrans = $id;
        $order_sup = OrderSupplier::where('transaction_id', $buyTrans->id)->first();
        $datainv = Replyorder::where('order_supplier_id', $order_sup->id)->get();
        $data=[];
        foreach($datainv as $item){
            $part_id=$item->part_id;
            $type_id=$item->part_type_id;
            $inv_id= $item->invoice_id;
            $order_sup_id=$item->order_supplier_id;
        //  return    $this->partDetailsRefundAll($type_id, $part_id,$inv_id,$order_sup_id);
            array_push($data,$this->partDetailsRefundAll($type_id, $part_id,$inv_id,$order_sup_id));
        }
         $data=json_encode($data);
      
        return view('refund.refundBuyInv', compact('data'));
    }


    //only one prt refund
    public function searchAllRefund(Request $request)
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

        // return view('searchAll', compact('allItems'));

        return $allItems;

        // pagination add ?page=2,3,4
    }

    public function partDetailsRefund($type_id, $part_id)
    {

        $allstores = Store::all();
        $data = new \Illuminate\Database\Eloquent\Collection;
        if ($type_id == 1) {
            $data = Part::where('id', $part_id)
                ->with('part_numbers.supplier')->with('sub_group.group')->with('part_details.part_spec')
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
                ->with('all_parts.part')
                ->with('all_parts.store_log.store')
                ->addSelect('*', DB::raw("'Part' as type"))->first();

            $data['allkits'] = KitPart::where('part_id', $part_id)->with('all_kits.kit')->with('all_kits.source')->with('all_kits.status')->with('all_kits.part_quality')->get();
            $kitPartsWithCount = KitPart::where('part_id', $part_id)->withCount('all_kits')->get();
            $data['kitcount'] = $kitPartsWithCount->sum('all_kits_count');

            $data['kitcollection'] = AllKitPartItem::select([
                    'all_kit_part_item.part_id',
                    'all_kit_part_item.source_id',
                    'all_kit_part_item.status_id',
                    'all_kit_part_item.quality_id',
                    'kit.name',
                    'kit.id',
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
                ->groupBy('store_section.part_id', 'store_section.type_id', 'store_section.source_id', 'store_section.status_id', 'store_section.quality_id', 'store_structure.id', 'store_structure.name', 'store_section.store_id', 'store_section.type_id')
                ->havingRaw('SUM(store_section.amount) > 0')
                ->with('source')->with('status')->with('part_quality')->with('part')->with('store')->get();

            $AllStoredata = new \Illuminate\Database\Eloquent\Collection;
            foreach ($allstores as $key => $store) {
                $sdata = [];
                $store_model = ucfirst($store->table_name);
                if ($store_model == 'Damaged_parts') {
                    $store_model = 'damagedPart';
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $sdata['store_sup_data'] = [];
                    $sdata['store'] = $store;
                } else {

                    $entity_tbl = 'App\\Models\\' . $store_model;

                    $query1x =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                        ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                        ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_parts.part_id, all_parts.source_id, all_parts.status_id, all_parts.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                        ->groupBy('all_parts.part_id', 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id',  $store->table_name . '.type_id')
                        ->where($store->table_name . '.type_id', 1)->where($store->table_name . '.part_id', $part_id)->where($store->table_name . '.amount', '>', 0);
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
                    // $sdata['data'] = $entity;
                    $sdata['store'] = $store;

                    $query1x1 =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                        ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                        ->selectRaw('SUM(' . $store->table_name . '.amount) as amount,all_parts.order_supplier_id, all_parts.part_id, all_parts.source_id, all_parts.status_id, all_parts.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                        ->groupBy('all_parts.order_supplier_id', 'all_parts.part_id', 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id', $store->table_name . '.type_id')
                        ->where($store->table_name . '.type_id', 1)->where($store->table_name . '.part_id', $part_id)->where($store->table_name . '.amount', '>', 0);
                    $entity1 = $query1x1->with([
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
                    $sdata['store_sup_data'] = $entity1;
                    // $sdata1['store'] = $store;


                }
                $AllStoredata->push($sdata);
                // $AllStoredata->push($sdata1);

            }

            $data['allStores'] = $AllStoredata;

            $pricelistparts =  SalePricing::where('part_id', $part_id)
                ->where('type_id', 1)->where('to', null)
                ->groupBy('part_id', 'source_id', 'status_id', 'quality_id', 'type_id')->select([
                    'part_id',
                    'type_id',
                    'source_id',
                    'status_id',
                    'quality_id'
                ])->with('source')->with('status')->with('part_quality')->get();

            foreach ($pricelistparts as $key => $value) {
                $value['sale_types'] = SalePricing::where('part_id', $value->part_id)->where('to', null)->where('source_id', $value->source_id)->where('status_id', $value->status_id)->where('quality_id', $value->quality_id)->where('type_id', 1)->with('sale_typex')->get();
            }

            $data['pricelist'] = $pricelistparts;

            $presaleOrder = PresaleOrderPart::with('presale_order.store')
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
        } elseif ($type_id == 6) {
            $data = Kit::where('id', $part_id)
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
                ->addSelect('*', DB::raw("'Kit' as type"))->first();

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
                ->groupBy('store_section.part_id', 'store_section.type_id', 'store_section.source_id', 'store_section.status_id', 'store_section.quality_id', 'store_structure.id', 'store_structure.name', 'store_structure.id', 'store_section.store_id', 'store_section.type_id')
                ->havingRaw('SUM(store_section.amount) > 0')
                ->with('source')->with('status')->with('part_quality')->with('kit')->with('store')->get();



            $AllStoredata = new \Illuminate\Database\Eloquent\Collection;
            foreach ($allstores as $key => $store) {
                $sdata = [];
                $store_model = ucfirst($store->table_name);
                if ($store_model == 'Damaged_parts') {
                    $store_model = 'damagedPart';
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $sdata['data'] = [];
                    $sdata['store_sup_data'] = [];
                    $sdata['store'] = $store;
                } else {

                    $entity_tbl = 'App\\Models\\' . $store_model;

                    $entity =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                        ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                        ->where($store->table_name . '.type_id', 6)
                        ->where($store->table_name . '.part_id', $part_id)
                        ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_kits.part_id, all_kits.source_id, all_kits.status_id, all_kits.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                        ->groupBy('all_kits.part_id', 'all_kits.source_id', 'all_kits.status_id', 'all_kits.quality_id', $store->table_name . '.type_id')
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
                    $query1x1 =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                        ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                        ->selectRaw('SUM(' . $store->table_name . '.amount) as amount,all_kits.order_supplier_id, all_kits.part_id, all_kits.source_id, all_kits.status_id, all_kits.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                        ->groupBy('all_kits.order_supplier_id', 'all_kits.part_id', 'all_kits.source_id', 'all_kits.status_id', 'all_kits.quality_id', $store->table_name . '.type_id')
                        ->where($store->table_name . '.type_id', 1)->where($store->table_name . '.part_id', $part_id)->where($store->table_name . '.amount', '>', 0);
                    $entity1 = $query1x1->with([
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
                    $sdata['store_sup_data'] = $entity1;
                }
                $AllStoredata->push($sdata);
            }

            $data['allStores'] = $AllStoredata;

            $pricelistparts =  SalePricing::where('part_id', $part_id)
                ->where('type_id', 6)->where('to', null)
                ->groupBy('part_id', 'source_id', 'status_id', 'quality_id', 'type_id')->select([
                    'part_id',
                    'type_id',
                    'source_id',
                    'status_id',
                    'quality_id'
                ])->with('source')->with('status')->with('part_quality')->get();

            foreach ($pricelistparts as $key => $value) {
                $value['sale_types'] = SalePricing::where('part_id', $value->part_id)->where('to', null)->where('source_id', $value->source_id)->where('status_id', $value->status_id)->where('quality_id', $value->quality_id)->where('type_id', 6)->with('sale_typex')->get();
            }

            $data['pricelist'] = $pricelistparts;
            $presaleOrder = PresaleOrderPart::with('presale_order.store')
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
        } elseif ($type_id == 2) {
            $data = Wheel::where('id', $part_id)
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
                ->addSelect('*', DB::raw("'Wheel' as type"))->first();

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
                ->groupBy('store_section.part_id', 'store_section.type_id', 'store_section.source_id', 'store_section.status_id', 'store_section.quality_id', 'store_structure.id', 'store_structure.name', 'store_structure.id', 'store_section.store_id', 'store_section.type_id')
                ->havingRaw('SUM(store_section.amount) > 0')
                ->with('source')->with('status')->with('part_quality')->with('wheel')->with('store')->get();

            $AllStoredata = new \Illuminate\Database\Eloquent\Collection;
            foreach ($allstores as $key => $store) {
                $sdata = [];
                $store_model = ucfirst($store->table_name);
                if ($store_model == 'Damaged_parts') {
                    $store_model = 'damagedPart';
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $sdata['data'] = [];
                    $sdata['store_sup_data'] = [];
                    $sdata['store'] = $store;
                } else {

                    $entity_tbl = 'App\\Models\\' . $store_model;

                    $entity =   $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                        ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                        ->where($store->table_name . '.type_id', 2)
                        ->where($store->table_name . '.part_id', $part_id)
                        ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_wheels.part_id, all_wheels.source_id, all_wheels.status_id, all_wheels.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                        ->groupBy('all_wheels.part_id', 'all_wheels.source_id', 'all_wheels.status_id', 'all_wheels.quality_id', $store->table_name . '.type_id')
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


                    $query1x1 =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                        ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                        ->selectRaw('SUM(' . $store->table_name . '.amount) as amount,all_wheels.order_supplier_id, all_wheels.part_id, all_wheels.source_id, all_wheels.status_id, all_wheels.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                        ->groupBy('all_wheels.order_supplier_id', 'all_wheels.part_id', 'all_wheels.source_id', 'all_wheels.status_id', 'all_wheels.quality_id', $store->table_name . '.type_id')
                        ->where($store->table_name . '.type_id', 2)->where($store->table_name . '.part_id', $part_id)->where($store->table_name . '.amount', '>', 0);
                    $entity1 = $query1x1->with([
                        'stores_log.all_wheels.source',
                        'stores_log.all_wheels.status',
                        'stores_log.all_wheels.part_quality',
                        'stores_log.all_wheels.sectionswithoutorder.store',
                        'stores_log.all_wheels.sectionswithoutorder.store_structure',
                        'stores_log.all_wheels.pricing.sale_type',
                        'stores_log.all_wheels.order_supplier.buy_transaction'
                    ])
                        ->get();
                    $sdata['store_sup_data'] = $entity1;
                }
                $AllStoredata->push($sdata);
            }

            $data['allStores'] = $AllStoredata;

            $pricelistparts =  SalePricing::where('part_id', $part_id)
                ->where('type_id', 2)->where('to', null)
                ->groupBy('part_id', 'source_id', 'status_id', 'quality_id', 'type_id')->select([
                    'part_id',
                    'type_id',
                    'source_id',
                    'status_id',
                    'quality_id'
                ])->with('source')->with('status')->with('part_quality')->get();

            foreach ($pricelistparts as $key => $value) {
                $value['sale_types'] = SalePricing::where('part_id', $value->part_id)->where('to', null)->where('source_id', $value->source_id)->where('status_id', $value->status_id)->where('quality_id', $value->quality_id)->where('type_id', 2)->with('sale_typex')->get();
            }

            $data['pricelist'] = $pricelistparts;

            $presaleOrder = PresaleOrderPart::with('presale_order.store')
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
        $allsource  = Source::all();
        $allstatus  = Status::all();
        $allpart_quality = PartQuality::all();

        $invoices = '';
        if ($type_id == 1) {
            $invoices = InvoiceItem::where('part_id', $part_id)->where('part_type_id', $type_id)->where('amount', '>', 0)
                ->with('source')
                ->with('status')
                ->with('part_quality')
                ->with('part')
                ->with('invoice_items_sections.store_structure')
                ->with('invoice.client')
                ->with('invoice.refund_invoices')
                ->with('invoice_items_refund')
                ->get();
        } elseif ($type_id == 2) {
            $invoices = InvoiceItem::where('part_id', $part_id)->where('part_type_id', $type_id)->where('amount', '>', 0)
                ->with('source')
                ->with('status')
                ->with('part_quality')
                ->with('wheel')
                ->with('invoice_items_sections.store_structure')
                ->with('invoice.client')
                ->with('invoice.refund_invoices')
                ->with('invoice_items_refund')
                ->get();
        } elseif ($type_id == 6) {
            $invoices = InvoiceItem::where('part_id', $part_id)->where('part_type_id', $type_id)->where('amount', '>', 0)
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

        return $data;

        return view('itemsDetails', compact('data', 'allsource', 'allstatus', 'allpart_quality', 'invoices'));
    }
    public function partDetailsRefundAll($type_id, $part_id,$inv_id,$order_sup_id)
    {
        // return $part_id;
        $allstores = Store::all();
        $data = new \Illuminate\Database\Eloquent\Collection;
        if ($type_id == 1) {
            $data = AllPart::where('part_id', $part_id)
                    ->where('order_supplier_id', $order_sup_id)
                // ->with('part_numbers.supplier')->with('sub_group.group')->with('part_details.part_spec')
                // ->with('part_models.series.model')
                // ->with('related_parts.part')
                ->with('status')
                ->with('source')
                ->with('part_quality')
                ->with('pricing')
                ->with('sections.store_structure')
                ->with('sections.store')
                ->with('part_without_supplier_in_allkit_item.all_kit.kit')
                ->with('part_without_supplier_in_allkit_item.source')
                ->with('part_without_supplier_in_allkit_item.status')
                ->with('part_without_supplier_in_allkit_item.part_quality')
                ->with('parts_in_allkit_item.all_kit.kit')
                ->with('order_supplier.supplier')
                ->with('order_supplier.currency_type')
                ->with('part')
                ->with('store_log.store')
                ->addSelect('*', DB::raw("'Part' as type"))->first();

            $data['allkits'] = KitPart::where('part_id', $part_id)->with('all_kits.kit')->with('all_kits.source')->with('all_kits.status')->with('all_kits.part_quality')->get();
            $kitPartsWithCount = KitPart::where('part_id', $part_id)->withCount('all_kits')->get();
            $data['kitcount'] = $kitPartsWithCount->sum('all_kits_count');

            $data['kitcollection'] = AllKitPartItem::select([
                    'all_kit_part_item.part_id',
                    'all_kit_part_item.source_id',
                    'all_kit_part_item.status_id',
                    'all_kit_part_item.quality_id',
                    'kit.name',
                    'kit.id',
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
                ->groupBy('store_section.part_id', 'store_section.type_id', 'store_section.source_id', 'store_section.status_id', 'store_section.quality_id', 'store_structure.id', 'store_structure.name', 'store_section.store_id', 'store_section.type_id')
                ->havingRaw('SUM(store_section.amount) > 0')
                ->with('source')->with('status')->with('part_quality')->with('part')->with('store')->get();

            $AllStoredata = new \Illuminate\Database\Eloquent\Collection;
            foreach ($allstores as $key => $store) {
                $sdata = [];
                $store_model = ucfirst($store->table_name);
                if ($store_model == 'Damaged_parts') {
                    $store_model = 'damagedPart';
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $sdata['store_sup_data'] = [];
                    $sdata['store'] = $store;
                } else {

                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $query1x =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                        ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                        ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_parts.part_id, all_parts.source_id, all_parts.status_id, all_parts.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                        ->groupBy('all_parts.part_id', 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id',  $store->table_name . '.type_id')
                        // ->where($store->table_name. '.supplier_order_id',$order_sup_id)
                        ->where($store->table_name . '.type_id', 1)->where($store->table_name . '.part_id', $part_id)->where($store->table_name . '.amount', '>', 0);
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
                    // $sdata['data'] = $entity;
                    $sdata['store'] = $store;

                    $query1x1 =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                        ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                        ->selectRaw('SUM(' . $store->table_name . '.amount) as amount,all_parts.order_supplier_id, all_parts.part_id, all_parts.source_id, all_parts.status_id, all_parts.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                        ->groupBy('all_parts.order_supplier_id', 'all_parts.part_id', 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id', $store->table_name . '.type_id')
                        // ->where($store->table_name. '.supplier_order_id',$order_sup_id)
                        ->where($store->table_name . '.type_id', 1)->where($store->table_name . '.part_id', $part_id)->where($store->table_name . '.amount', '>', 0);
                    $entity1 = $query1x1->with([
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
                    $sdata['store_sup_data'] = $entity1;
                    // $sdata1['store'] = $store;


                }
                $AllStoredata->push($sdata);
                // $AllStoredata->push($sdata1);

            }

            $data['allStores'] = $AllStoredata;

            // $pricelistparts =  SalePricing::where('part_id', $part_id)
            //     ->where('type_id', 1)->where('to', null)
            //     ->groupBy('part_id', 'source_id', 'status_id', 'quality_id', 'type_id')->select([
            //         'part_id',
            //         'type_id',
            //         'source_id',
            //         'status_id',
            //         'quality_id'
            //     ])->with('source')->with('status')->with('part_quality')->get();

            // foreach ($pricelistparts as $key => $value) {
            //     $value['sale_types'] = SalePricing::where('part_id', $value->part_id)->where('to', null)->where('source_id', $value->source_id)->where('status_id', $value->status_id)->where('quality_id', $value->quality_id)->where('type_id', 1)->with('sale_typex')->get();
            // }

            // $data['pricelist'] = $pricelistparts;

            // $presaleOrder = PresaleOrderPart::with('presale_order.store')
            //     ->with('presale_order.client')
            //     ->with('part')
            //     ->with('source')
            //     ->with('status')
            //     ->with('quality')
            //     ->where('part_id', $part_id)
            //     ->where('part_type_id', 1)
            //     ->get([
            //         'presale_order_parts.id',
            //         'presale_order_parts.part_id',
            //         'presale_order_parts.notes',
            //         'presale_order_parts.amount',
            //         'presale_order_parts.presaleOrder_id',
            //         'presale_order_parts.status_id',
            //         'presale_order_parts.source_id',
            //         'presale_order_parts.quality_id',
            //         'presale_order_parts.part_type_id',
            //         'presale_order_parts.price'
            //     ]);
            // $data['presaleOrder'] = $presaleOrder;
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
        } elseif ($type_id == 6) {
            $data = AllKit::where('part_id', $part_id)
            ->where('order_supplier_id', $order_sup_id)
                // ->with('kit_parts.part')
                ->with('status')
                ->with('source')
                ->with('part_quality')
                ->with('pricing')
                ->with('sections.store_structure')
                ->with('sections.store')
                ->with('order_supplier.supplier')
                ->with('order_supplier.currency_type')
                ->with('kit')
                ->addSelect('*', DB::raw("'Kit' as type"))->first();

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
                ->groupBy('store_section.part_id', 'store_section.type_id', 'store_section.source_id', 'store_section.status_id', 'store_section.quality_id', 'store_structure.id', 'store_structure.name', 'store_structure.id', 'store_section.store_id', 'store_section.type_id')
                ->havingRaw('SUM(store_section.amount) > 0')
                ->with('source')->with('status')->with('part_quality')->with('kit')->with('store')->get();



            $AllStoredata = new \Illuminate\Database\Eloquent\Collection;
            foreach ($allstores as $key => $store) {
                $sdata = [];
                $store_model = ucfirst($store->table_name);
                if ($store_model == 'Damaged_parts') {
                    $store_model = 'damagedPart';
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $sdata['data'] = [];
                    $sdata['store_sup_data'] = [];
                    $sdata['store'] = $store;
                } else {

                    $entity_tbl = 'App\\Models\\' . $store_model;

                    $entity =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                        ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                        ->where($store->table_name . '.type_id', 6)
                        ->where($store->table_name . '.part_id', $part_id)
                        ->where($store->table_name. '.supplier_order_id',$order_sup_id)
                        ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_kits.part_id, all_kits.source_id, all_kits.status_id, all_kits.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                        ->groupBy('all_kits.part_id', 'all_kits.source_id', 'all_kits.status_id', 'all_kits.quality_id', $store->table_name . '.type_id')
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
                    $query1x1 =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                        ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                        ->selectRaw('SUM(' . $store->table_name . '.amount) as amount,all_kits.order_supplier_id, all_kits.part_id, all_kits.source_id, all_kits.status_id, all_kits.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                        ->groupBy('all_kits.order_supplier_id', 'all_kits.part_id', 'all_kits.source_id', 'all_kits.status_id', 'all_kits.quality_id', $store->table_name . '.type_id')
                        ->where($store->table_name. '.supplier_order_id',$order_sup_id)
                        ->where($store->table_name . '.type_id', 6)->where($store->table_name . '.part_id', $part_id)->where($store->table_name . '.amount', '>', 0);
                        $entity1 = $query1x1->with([
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
                    $sdata['store_sup_data'] = $entity1;
                }
                $AllStoredata->push($sdata);
            }

            $data['allStores'] = $AllStoredata;

            $pricelistparts =  SalePricing::where('part_id', $part_id)
                ->where('type_id', 6)->where('to', null)
                ->groupBy('part_id', 'source_id', 'status_id', 'quality_id', 'type_id')->select([
                    'part_id',
                    'type_id',
                    'source_id',
                    'status_id',
                    'quality_id'
                ])->with('source')->with('status')->with('part_quality')->get();

            foreach ($pricelistparts as $key => $value) {
                $value['sale_types'] = SalePricing::where('part_id', $value->part_id)->where('to', null)->where('source_id', $value->source_id)->where('status_id', $value->status_id)->where('quality_id', $value->quality_id)->where('type_id', 6)->with('sale_typex')->get();
            }

            $data['pricelist'] = $pricelistparts;
            $presaleOrder = PresaleOrderPart::with('presale_order.store')
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
        } elseif ($type_id == 2) {
            $data = AllWheel::where('part_id', $part_id)
            ->where('order_supplier_id', $order_sup_id)
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
                ->addSelect('*', DB::raw("'Wheel' as type"))->first();

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
                ->groupBy('store_section.part_id', 'store_section.type_id', 'store_section.source_id', 'store_section.status_id', 'store_section.quality_id', 'store_structure.id', 'store_structure.name', 'store_structure.id', 'store_section.store_id', 'store_section.type_id')
                ->havingRaw('SUM(store_section.amount) > 0')
                ->with('source')->with('status')->with('part_quality')->with('wheel')->with('store')->get();

            $AllStoredata = new \Illuminate\Database\Eloquent\Collection;
            foreach ($allstores as $key => $store) {
                $sdata = [];
                $store_model = ucfirst($store->table_name);
                if ($store_model == 'Damaged_parts') {
                    $store_model = 'damagedPart';
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $sdata['data'] = [];
                    $sdata['store_sup_data'] = [];
                    $sdata['store'] = $store;
                } else {

                    $entity_tbl = 'App\\Models\\' . $store_model;

                    $entity =   $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                        ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                        ->where($store->table_name . '.type_id', 2)
                        ->where($store->table_name . '.part_id', $part_id)
                        ->selectRaw('SUM(' . $store->table_name . '.amount) as amount, all_wheels.part_id, all_wheels.source_id, all_wheels.status_id, all_wheels.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                        ->where($store->table_name. '.supplier_order_id',$order_sup_id)
                        ->groupBy('all_wheels.part_id', 'all_wheels.source_id', 'all_wheels.status_id', 'all_wheels.quality_id', $store->table_name . '.type_id')
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


                    $query1x1 =  $entity_tbl::join('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                        ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                        ->selectRaw('SUM(' . $store->table_name . '.amount) as amount,all_wheels.order_supplier_id, all_wheels.part_id, all_wheels.source_id, all_wheels.status_id, all_wheels.quality_id,MAX(stores_log.id) as store_log_id ,' . $store->table_name . '.type_id')
                        ->groupBy('all_wheels.order_supplier_id', 'all_wheels.part_id', 'all_wheels.source_id', 'all_wheels.status_id', 'all_wheels.quality_id', $store->table_name . '.type_id')
                        ->where($store->table_name. '.supplier_order_id',$order_sup_id)
                        ->where($store->table_name . '.type_id', 2)->where($store->table_name . '.part_id', $part_id)->where($store->table_name . '.amount', '>', 0);
                    $entity1 = $query1x1->with([
                        'stores_log.all_wheels.source',
                        'stores_log.all_wheels.status',
                        'stores_log.all_wheels.part_quality',
                        'stores_log.all_wheels.sectionswithoutorder.store',
                        'stores_log.all_wheels.sectionswithoutorder.store_structure',
                        'stores_log.all_wheels.pricing.sale_type',
                        'stores_log.all_wheels.order_supplier.buy_transaction'
                    ])
                        ->get();
                    $sdata['store_sup_data'] = $entity1;
                }
                $AllStoredata->push($sdata);
            }

            $data['allStores'] = $AllStoredata;

            $pricelistparts =  SalePricing::where('part_id', $part_id)
                ->where('type_id', 2)->where('to', null)
                ->groupBy('part_id', 'source_id', 'status_id', 'quality_id', 'type_id')->select([
                    'part_id',
                    'type_id',
                    'source_id',
                    'status_id',
                    'quality_id'
                ])->with('source')->with('status')->with('part_quality')->get();

            foreach ($pricelistparts as $key => $value) {
                $value['sale_types'] = SalePricing::where('part_id', $value->part_id)->where('to', null)->where('source_id', $value->source_id)->where('status_id', $value->status_id)->where('quality_id', $value->quality_id)->where('type_id', 2)->with('sale_typex')->get();
            }

            $data['pricelist'] = $pricelistparts;

            $presaleOrder = PresaleOrderPart::with('presale_order.store')
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
        // // return $data;
        $allsource  = Source::all();
        $allstatus  = Status::all();
        $allpart_quality = PartQuality::all();

        $invoices = '';
        if ($type_id == 1) {
            $invoices = InvoiceItem::where('part_id', $part_id)->where('part_type_id', $type_id)->where('amount', '>', 0)
                ->with('source')
                ->with('status')
                ->with('part_quality')
                ->with('part')
                ->with('invoice_items_sections.store_structure')
                ->with('invoice.client')
                ->with('invoice.refund_invoices')
                ->with('invoice_items_refund')
                ->get();
        } elseif ($type_id == 2) {
            $invoices = InvoiceItem::where('part_id', $part_id)->where('part_type_id', $type_id)->where('amount', '>', 0)
                ->with('source')
                ->with('status')
                ->with('part_quality')
                ->with('wheel')
                ->with('invoice_items_sections.store_structure')
                ->with('invoice.client')
                ->with('invoice.refund_invoices')
                ->with('invoice_items_refund')
                ->get();
        } elseif ($type_id == 6) {
            $invoices = InvoiceItem::where('part_id', $part_id)->where('part_type_id', $type_id)->where('amount', '>', 0)
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

        return $data;

        return view('itemsDetails', compact('data', 'allsource', 'allstatus', 'allpart_quality', 'invoices'));
    }
    public function newRefundPart(Request $request)
    {
        $type_id = $request['type_id'];
        $part_id = $request['part_id'];
        $source = $request['source'];
        $status = $request['status'];
        $quality = $request['quality'];
        $remain_amount = $request['remain_amount'];
        $refund_amount = floatVal($request['refund_amount']);
        $order_sup_id = $request['order_sup_id'];
        $store_id  = $request['store_id'];
        $msg  = '';
        // return $store_id;


        DB::beginTransaction();
        $logMessage = '';
        $logUser = Auth::user()->id;

        try {

            $store = Store::where('id', $store_id)->get();
            $store_table_name = $store[0]->table_name;

            // remove part from storeSection 
            if ($remain_amount < $refund_amount) {
                $msg = '<li> الكمية غير صحيحة </li>';
                return $msg;
            }
            $all_store_section = StoreSection::where('store_id', $store_id)
                ->where('type_id', $type_id)
                ->where('part_id', $part_id)
                ->where('source_id', $source)
                ->where('status_id', $status)
                ->where('quality_id', $quality)
                ->where('order_supplier_id', $order_sup_id)   /// Here
                ->with('store_structure')
                ->get();

            $totalAmount = $refund_amount;
            if (count($all_store_section) >  0) {
                foreach ($all_store_section as $key => $value) {
                    if ($totalAmount  <= $value->amount) {
                        // return    var_dump( $value);
                        $value->decrement('amount', $totalAmount);
                        $logMessage .= 'استرجاع علي StoreSection ' . $value->id . 'الصنف' . $value->part_id . ' الكمية ' . $totalAmount . '<br/>';
                        $msg .= '<li>تم خصم من ' . $totalAmount . 'القسم ' . $value->store_structure->name . '</li>';
                        break;
                    } else if ($totalAmount  > $value->amount) {
                        $value->decrement('amount', $value->amount);
                        $msg .= '<li>تم خصم من ' . $value->amount . 'القسم ' . $value->store_structure->name . '</li>';
                        $logMessage .= 'استرجاع علي StoreSection ' . $value->id . 'الصنف' . $value->part_id . ' الكمية ' . $value->amount . '<br/>';
                        $totalAmount = $totalAmount - $value->amount;
                    } else if ($totalAmount <= 0) {
                        break;
                    }
                }
            }else {
                $msg .= '<li> الكمية غير موجودة في الأقسام </li>';
            }

            // remove part form store

            $allstorees = DB::table($store_table_name)->where('part_id', $part_id)
                ->where('supplier_order_id', $order_sup_id)
                ->where('type_id', $type_id) // HERE
                ->first();
            //  return "xx";
            if ($allstorees) {
                DB::table($store_table_name)->where('id', $allstorees->id)->decrement('amount', $refund_amount);
                //  return "xx";
                StoresLog::where('id', $allstorees->store_log_id)->decrement('amount', $refund_amount);

                $logMessage .= 'استرجاع علي المخزن ' . $allstorees->id . 'الصنف' . $allstorees->part_id . ' الكمية ' . $refund_amount . '<br/>';
                //  return "xx";
                $msg .= '<li>تم خصم من ' . $refund_amount . 'المخزن ' . $store[0]->name . '</li>';
            } else {
                $msg .= '<li> الكمية غير موجودة في المخزون </li>';
            }

            // remove from Allpart
            $allparts = '';
            $table_all='';
            if ($type_id == 1) {
                $allparts = AllPart::where('all_parts.part_id', $part_id)
                    ->where('all_parts.source_id', $source)
                    ->where('all_parts.status_id', $status)
                    ->where('all_parts.quality_id', $quality)
                    ->where('all_parts.order_supplier_id', $order_sup_id) // Here
                    ->orderBy('all_parts.id', 'desc')
                    ->select('all_parts.*')
                    ->first();
                $table_all='All_part';
            } else if ($type_id == 2) {
                // return"x";
                $allparts = AllWheel::where('all_wheels.part_id', $part_id)
                    ->where('all_wheels.source_id', $source)
                    ->where('all_wheels.status_id', $status)
                    ->where('all_wheels.quality_id', $quality)
                    ->where('all_wheels.order_supplier_id', $order_sup_id) // Here
                    ->orderBy('all_wheels.id', 'desc')
                    ->select('all_wheels.*')
                    ->first();
                $table_all='All_wheel';
            } else if ($type_id == 6) {

                $allparts = AllKit::where('all_kits.part_id', $part_id)
                    ->where('all_kits.source_id', $source)
                    ->where('all_kits.status_id', $status)
                    ->where('all_kits.quality_id', $quality)
                    ->where('all_kits.order_supplier_id', $order_sup_id) // Here
                    ->orderBy('all_kits.id', 'desc')
                    ->select('all_kits.*')
                    ->first();
                $table_all='All_wheel';
            }
            if ($allparts) {

                $allparts->decrement('remain_amount', $refund_amount);
                $allparts->decrement('amount', $refund_amount);

                $logMessage .= 'استرجاع علي ' . $table_all .$allparts->id . 'الصنف' . $allparts->part_id . ' الكمية ' . $refund_amount . '<br/>';
                $msg .= '<li>تم خصم من ' . $refund_amount . $table_all.' </li>';
            } else {
                $msg .= '<li> الكمية غير موجودة في المخزون </li>';
            }

            // remove from ReplyOrder

            $allreplayOrder = Replyorder::where('part_id', $part_id)
                ->where('source_id', $source)
                ->where('status_id', $status)
                ->where('quality_id', $quality)
                ->where('order_supplier_id', $order_sup_id)
                ->first();

            if ($allreplayOrder) {

                $allreplayOrder->decrement('amount', $refund_amount);

                $logMessage .= 'استرجاع علي allreplayOrder ' . $allreplayOrder->id . 'الصنف' . $allreplayOrder->part_id . ' الكمية ' . $refund_amount . '<br/>';
                $msg .= '<li>تم خصم من ' . $refund_amount . 'ReplyOrder </li>';
            } else {
                $msg .= '<li> الكمية غير موجودة في Replyorder </li>';
            }

            /// Calc Tax and Discount in buy invoice


            // add to refund buy
            $tax = 0;
            $discount = 0;
            $ordersupplier = OrderSupplier::find($order_sup_id);

            if ($allparts) {
                if ($ordersupplier) {
                    if ($ordersupplier->taxInvolved_flag == 1) {
                        $tax += $allparts->buy_price * 0.14;
                    }

                    if ($ordersupplier->taxkasmInvolved_flag == 1) {
                        $tax += $allparts->buy_price * -0.01;
                    }
                }
                $Ac_currency_id = $ordersupplier->currency_id;
                $Ac_currency_date = $ordersupplier->confirmation_date;
                $Ac_all_currency_types = CurrencyType::with([
                    'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                        return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                    },
                ])->where('id', $Ac_currency_id)
                    ->get();
                // return $ordersupplier->transaction_id;
                     RefundBuy::create([
                    'part_id' => $part_id,
                    'order_supplier_id' => $order_sup_id,
                    'amount' =>  $refund_amount,
                    'source_id' => $source,
                    'status_id' => $status,
                    'buy_price' => $allparts->buy_price,
                    'tax' => $tax,
                    'quality_id' => $quality,
                    'buy_costing' => $allparts->buy_costing,
                    'discount' => $discount,
                    'user_id' => Auth::user()->id,
                    'buy_transaction' => $ordersupplier->transaction_id,
                    'type_id' => $type_id,
                    'currency_id'=> $ordersupplier->currency_id,
                    'buy_value_curcency'=> $Ac_all_currency_types[0]->currencies[0]->value,

                ]);
            } else {
                DB::rollback();
            }
            //qayd to Decrement Stock
            DB::commit();
            return $msg;
        } catch (\Exception $e) {
            //throw $th;
            DB::rollback();
            session()->flash("error", $e);
            return $e;
        }
    }
    public function newRefundPartAll(Request $request)
    {
        // return $request;
        $type_id = $request['type_id'];
        $part_id = $request['part_id'];
        $source = $request['source'];
        $status = $request['status'];
        $quality = $request['quality'];
        $remain_amount = $request['remain_amount'];
        $refund_amount = floatVal($request['refund_amount']);
        $order_sup_id = $request['order_sup_id'];
        $store_id  = $request['store_id'];
        $msg  = '';
       
     

        DB::beginTransaction();
        $logMessage = '';
        $logUser = Auth::user()->id;

        try {

            $store = Store::where('id', $store_id)->get();
            $store_table_name = $store[0]->table_name;

            // remove part from storeSection 
            if ($remain_amount < $refund_amount) {
                $msg = '<li> الكمية غير صحيحة </li>';
                return $msg;
            }
            $all_store_section = StoreSection::where('store_id', $store_id)
                ->where('type_id', $type_id)
                ->where('part_id', $part_id)
                ->where('source_id', $source)
                ->where('status_id', $status)
                ->where('quality_id', $quality)
                ->where('order_supplier_id', $order_sup_id)   /// Here
                ->with('store_structure')
                ->get();

            $totalAmount = $refund_amount;
            if (count($all_store_section) >  0) {
                foreach ($all_store_section as $key => $value) {
                    if ($totalAmount  <= $value->amount) {
                        // return    var_dump( $value);
                        $value->decrement('amount', $totalAmount);
                        $logMessage .= 'استرجاع علي StoreSection ' . $value->id . 'الصنف' . $value->part_id . ' الكمية ' . $totalAmount . '<br/>';
                        $msg .= '<li>تم خصم من ' . $totalAmount . 'القسم ' . $value->store_structure->name . '</li>';
                        break;
                    } else if ($totalAmount  > $value->amount) {
                        $value->decrement('amount', $value->amount);
                        $msg .= '<li>تم خصم من ' . $value->amount . 'القسم ' . $value->store_structure->name . '</li>';
                        $logMessage .= 'استرجاع علي StoreSection ' . $value->id . 'الصنف' . $value->part_id . ' الكمية ' . $value->amount . '<br/>';
                        $totalAmount = $totalAmount - $value->amount;
                    } else if ($totalAmount <= 0) {
                        break;
                    }
                }
            }else {
                $msg .= '<li> الكمية غير موجودة في الأقسام </li>';
            }

            // remove part form store

            $allstorees = DB::table($store_table_name)->where('part_id', $part_id)
                ->where('supplier_order_id', $order_sup_id)
                ->where('type_id', $type_id) // HERE
                ->first();
            //  return "xx";
            if ($allstorees) {
                DB::table($store_table_name)->where('id', $allstorees->id)->decrement('amount', $refund_amount);
                //  return "xx";
                StoresLog::where('id', $allstorees->store_log_id)->decrement('amount', $refund_amount);

                $logMessage .= 'استرجاع علي المخزن ' . $allstorees->id . 'الصنف' . $allstorees->part_id . ' الكمية ' . $refund_amount . '<br/>';
                //  return "xx";
                $msg .= '<li>تم خصم من ' . $refund_amount . 'المخزن ' . $store[0]->name . '</li>';
            } else {
                $msg .= '<li> الكمية غير موجودة في المخزون </li>';
            }

            // remove from Allpart
            $allparts = '';
            $table_all='';
            if ($type_id == 1) {
                $allparts = AllPart::where('all_parts.part_id', $part_id)
                    ->where('all_parts.source_id', $source)
                    ->where('all_parts.status_id', $status)
                    ->where('all_parts.quality_id', $quality)
                    ->where('all_parts.order_supplier_id', $order_sup_id) // Here
                    ->orderBy('all_parts.id', 'desc')
                    ->select('all_parts.*')
                    ->first();
                $table_all='All_part';
            } else if ($type_id == 2) {
                // return"x";
                $allparts = AllWheel::where('all_wheels.part_id', $part_id)
                    ->where('all_wheels.source_id', $source)
                    ->where('all_wheels.status_id', $status)
                    ->where('all_wheels.quality_id', $quality)
                    ->where('all_wheels.order_supplier_id', $order_sup_id) // Here
                    ->orderBy('all_wheels.id', 'desc')
                    ->select('all_wheels.*')
                    ->first();
                $table_all='All_wheel';
            } else if ($type_id == 6) {

                $allparts = AllKit::where('all_kits.part_id', $part_id)
                    ->where('all_kits.source_id', $source)
                    ->where('all_kits.status_id', $status)
                    ->where('all_kits.quality_id', $quality)
                    ->where('all_kits.order_supplier_id', $order_sup_id) // Here
                    ->orderBy('all_kits.id', 'desc')
                    ->select('all_kits.*')
                    ->first();
                $table_all='All_wheel';
            }
            if ($allparts) {

                $allparts->decrement('remain_amount', $refund_amount);
                $allparts->decrement('amount', $refund_amount);

                $logMessage .= 'استرجاع علي ' . $table_all .$allparts->id . 'الصنف' . $allparts->part_id . ' الكمية ' . $refund_amount . '<br/>';
                $msg .= '<li>تم خصم من ' . $refund_amount . $table_all.' </li>';
            } else {
                $msg .= '<li> الكمية غير موجودة في المخزون </li>';
            }

            // remove from ReplyOrder

            $allreplayOrder = Replyorder::where('part_id', $part_id)
                ->where('source_id', $source)
                ->where('status_id', $status)
                ->where('quality_id', $quality)
                ->where('order_supplier_id', $order_sup_id)
                ->first();

            if ($allreplayOrder) {

                $allreplayOrder->decrement('amount', $refund_amount);

                $logMessage .= 'استرجاع علي allreplayOrder ' . $allreplayOrder->id . 'الصنف' . $allreplayOrder->part_id . ' الكمية ' . $refund_amount . '<br/>';
                $msg .= '<li>تم خصم من ' . $refund_amount . 'ReplyOrder </li>';
            } else {
                $msg .= '<li> الكمية غير موجودة في Replyorder </li>';
            }

            /// Calc Tax and Discount in buy invoice


            // add to refund buy
            $tax = 0;
            $discount = 0;
            $ordersupplier = OrderSupplier::find($order_sup_id);

            if ($allparts) {
                if ($ordersupplier) {
                    if ($ordersupplier->taxInvolved_flag == 1) {
                        $tax += $allparts->buy_price * 0.14;
                    }

                    if ($ordersupplier->taxkasmInvolved_flag == 1) {
                        $tax += $allparts->buy_price * -0.01;
                    }
                }

                $Ac_currency_id = $ordersupplier->currency_id;
                $Ac_currency_date = $ordersupplier->confirmation_date;
                $Ac_all_currency_types = CurrencyType::with([
                    'currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                        return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                    },
                ])->where('id', $Ac_currency_id)
                    ->get();
                // return $allparts;
                     RefundBuy::create([
                    'part_id' => $part_id,
                    'order_supplier_id' => $order_sup_id,
                    'amount' =>  $refund_amount,
                    'source_id' => $source,
                    'status_id' => $status,
                    'buy_price' => $allparts->buy_price,
                    'tax' => $tax,
                    'quality_id' => $quality,
                    'buy_costing' => number_format((float)$allparts->buy_costing, 2, '.', ''),
                    'discount' => $discount,
                    'user_id' => Auth::user()->id,
                    'buy_transaction' => $ordersupplier->transaction_id,
                    'type_id' => $type_id,
                    'currency_id'=> $ordersupplier->currency_id,
                    'buy_value_curcency'=> $Ac_all_currency_types[0]->currencies[0]->value,


                ]);
            } else {
                DB::rollback();
            }
            //qayd to Decrement Stock
            DB::commit();
            return $msg;
        } catch (\Exception $e) {
            //throw $th;
            DB::rollback();
            session()->flash("error", $e);
            return $e;
        }
    }
    public function refundSattlement(){
      
       
         $refund_items = RefundBuy::with(['buyTransaction','currency_type','order_supplier.supplier','status','source','part_quality'])->where('flag',0)->get();
        foreach( $refund_items as $item ){
            
            if($item->type_id==1){
                $item['item']= Part::where('id',$item->part_id)->first();
              
            }else if($item->type_id==2){
                $item['item']=Wheel::where('id',$item->part_id)->first();
            }else  if($item->type_id==6){
                $item['item']=Kit::where('id',$item->part_id)->first();
            }
        }
   
        $bank_types = BankType::all();
        $store_safe = Store::where('safe_accountant_number', '<>', '0')->get();


        return view('refund.refundSettlement', compact('refund_items','bank_types','store_safe'));
   
    }
    public function submittaswya(Request $request)
    {

       
        $validator = Validator::make($request->all(), [
            'refunds' => 'required|array|min:1',
            'refund_price' => 'required|integer|min:0',
            'refunds.*.transactionId' => 'required|integer',
            'refunds.*.supplier' => 'required|string',
            'refunds.*.amount' => 'required|numeric|min:1',
            'refunds.*.refundbuy_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
      
            foreach ($request->refunds as $refund) {
                
            return $refundbuy = RefundBuy::find($refund['refundbuy_id']);

                // update refundbuy_id flag set to 1
                $refundbuy->update([
                    'flag' => 1
                ]);

                // update / decrement total_price order supplier 
                OrderSupplier::where('id',$refund->osid)->decrement('total_price',$refundbuy->buy_price * $refundbuy->amount);
                OrderSupplier::where('id',$refund->osid)->decrement('tax',$refundbuy->tax);

            
                // recalc coast and update allpart coast
                $itemOS = OrderSupplier::where('id',$refund->osid)->first();
                // $newratio = inv_coast / inv_total;
                $newratio = $itemOS->coast / $itemOS->total_price;
                // newCoast = $newratio * price 
                $newCoast = $newratio * $refundbuy->buy_price;
                if($refundbuy->type_id == 1){
                    AllPart::where('part_id', $refundbuy->part_id)
                        ->where('source_id', $refundbuy->source_id)
                        ->where('status_id', $refundbuy->status_id)
                        ->where('quality_id', $refundbuy->quality_id)
                        ->where('order_supplier_id', $refundbuy->order_supplier_id)->update([
                            'buy_costing'=> $newCoast
                        ]);
                }
            


                // taxes


            }


            $ordersup = OrderSupplier::where('id',$request->refunds[0]['osid'])->first();
            $curentrefund_price = $this->convertCurrency($request->refund_price ,400,$ordersup->currency_id , Carbon::today());

            // if paied here > 0 , update / decrement paied order supplier 
            if($request->refund_price > 0){
                if($ordersup->paied > 0){
                    if($request->refund_price <= $ordersup->paied ){
                        // decrement paied in ordersupplier
                        $ordersup->decrement('paied',$curentrefund_price);
                    }else{
                        // decremnt paied to 0 in ordersupplier
                        // diff =  $request->refund_price - $ordersup->paied
                        // sanad sarf to supplier with diff
                    }
                }else{
                    // sanad abd


                }
            }

            
            DB::commit();
            return response()->json(['message' => 'Refunds processed successfully!'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash("error", $e->getMessage());
            return false;
        }
    }

    function convertCurrency($amount, $fromCurrency, $toCurrency, $date = null)
    {
        $date = $date ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->format('Y-m-d');
    
        // return $date;
         $rate = Currency::where('from', '<=', $date) 
        ->where(function ($query) use ($date) {
            $query->where('to', '>=', $date) 
                  ->orWhereNull('to'); 
        })
        ->where('currency_id', $toCurrency)->first();
    
        if ($rate) {
            return round($amount * $rate->value, 2); 
        }
    
        return null; 
    }
}
