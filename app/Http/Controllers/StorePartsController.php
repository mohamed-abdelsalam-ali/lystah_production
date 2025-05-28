<?php

namespace App\Http\Controllers;

use App\Models\AllKit;
use App\Models\AllPart;
use App\Models\AllWheel;
use App\Models\InvoiceItem;
use App\Models\RefundInvoice;
use App\Models\Store;
use App\Models\StoreSection;
use App\Models\Talef;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class StorePartsController extends Controller
{
    public function inventory()
    {

        $stores = Store::where('table_name', '<>', 'damaged_parts')->get();


        return view('inventory', compact('stores'));
    }

    public function inventoryData($storeId, Request $request)
    {
        $store = Store::findOrFail($storeId);
        $modelName = $store->table_name === 'damaged_parts'
            ? 'App\\Models\\DamagedPart'
            : 'App\\Models\\' . ucfirst($store->table_name);

        $storeTable = $store->table_name;

        // Base query with pagination and search functionality
        $partbaseQuery = $modelName::join('stores_log', "$storeTable.store_log_id", '=', 'stores_log.id')
            ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
            ->join('part', 'all_parts.part_id', '=', 'part.id')
            // ->leftJoin('part_number', 'part_number.part_id', '=', 'part.id') // optional: left join in case no part numbers
            // ->join('part_number', 'part_number.part_id', '=', 'part.id')
            ->selectRaw("
                SUM($storeTable.amount) AS amount,
                all_parts.part_id,
                all_parts.source_id,
                all_parts.status_id,
                all_parts.quality_id,
                MAX(stores_log.id) AS store_log_id,
                $storeTable.type_id,
                part.name AS part_name
                 -- GROUP_CONCAT(part_number.number SEPARATOR ', ') AS part_numbers
            ")
            ->groupBy(
                'all_parts.part_id',
                'all_parts.source_id',
                'all_parts.status_id',
                'all_parts.quality_id',
                "$storeTable.type_id",
                'part.name'
            )->toBase();

        $kitbaseQuery = $modelName::join('stores_log', "$storeTable.store_log_id", '=', 'stores_log.id')
            ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
            ->join('kit', 'all_kits.part_id', '=', 'kit.id')
            // ->join('part_number', 'part_number.part_id', '=', 'part.id')
            ->selectRaw("
                SUM($storeTable.amount) AS amount,
                all_kits.part_id,
                all_kits.source_id,
                all_kits.status_id,
                all_kits.quality_id,
                MAX(stores_log.id) AS store_log_id,
                $storeTable.type_id,
                kit.name AS part_name
            ")
            ->groupBy(
                'all_kits.part_id',
                'all_kits.source_id',
                'all_kits.status_id',
                'all_kits.quality_id',
                "$storeTable.type_id",
                'kit.name'
            )->toBase();

        $wheelbaseQuery = $modelName::join('stores_log', "$storeTable.store_log_id", '=', 'stores_log.id')
            ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
            ->join('wheel', 'all_wheels.part_id', '=', 'wheel.id')
            // ->join('part_number', 'part_number.part_id', '=', 'part.id')
            ->selectRaw("
                SUM($storeTable.amount) AS amount,
                all_wheels.part_id,
                all_wheels.source_id,
                all_wheels.status_id,
                all_wheels.quality_id,
                MAX(stores_log.id) AS store_log_id,
                $storeTable.type_id,
                wheel.name AS part_name
            ")
            ->groupBy(
                'all_wheels.part_id',
                'all_wheels.source_id',
                'all_wheels.status_id',
                'all_wheels.quality_id',
                "$storeTable.type_id",
                'wheel.name'
            )->toBase();




        // Apply search filter if search term is provided
        if ($request->has('search_value') && $request->search_value) {
            $searchTerm = $request->search_value;

            // $partbaseQuery->join('part_number', 'part_number.part_id', '=', 'part.id');

            $barcodeMatch = [];
            if (preg_match('/^FN(\d)(\d+)(\d)(\d)(\d)$/', $searchTerm, $barcodeMatch)) {
                $typeId = $barcodeMatch[1];
                $partId = $barcodeMatch[2];
                $sourceId = $barcodeMatch[3];
                $statusId = $barcodeMatch[4];
                $qualityId = $barcodeMatch[5];
                // dd($barcodeMatch);
                // $query->whereRaw('CONCAT("FN1", part_id, source_id, status_id, quality_id)  LIKE ?', ["%$searchKey%"]);

                $partbaseQuery->whereRaw('CONCAT("FN", ?, all_parts.part_id, all_parts.source_id, all_parts.status_id, all_parts.quality_id) LIKE ?', [
                    $typeId, // type_id
                    "%$searchTerm%"
                ]);
                
                $wheelbaseQuery->whereRaw('CONCAT("FN", ?, all_wheels.part_id, all_wheels.source_id, all_wheels.status_id, all_wheels.quality_id) LIKE ?', [
                    $typeId, // type_id
                    "%$searchTerm%"
                ]);

                $kitbaseQuery->whereRaw('CONCAT("FN", ?, all_kits.part_id, all_kits.source_id, all_kits.status_id, all_kits.quality_id) LIKE ?', [
                    $typeId, // type_id
                    "%$searchTerm%"
                ]);
                
                
            
            } else {
                // Existing search (by name or part number)
                $partbaseQuery->where(function ($query) use ($searchTerm) {
                    $query->where('part.name', 'like', "%$searchTerm%")
                        ->orWhereExists(function ($subquery) use ($searchTerm) {
                            $subquery->select(DB::raw(1))
                                ->from('part_number')
                                ->whereRaw('part_number.part_id = part.id')
                                ->where('part_number.number', 'like', "%$searchTerm%");
                        });
                });
            
                $kitbaseQuery->where(function ($query) use ($searchTerm) {
                    $query->where('kit.name', 'like', "%$searchTerm%");
                });
            
                $wheelbaseQuery->where(function ($query) use ($searchTerm) {
                    $query->where('wheel.name', 'like', "%$searchTerm%");
                });
            }
        }
        $baseQuery = $partbaseQuery->union($kitbaseQuery)->union($wheelbaseQuery);
        $length = $request->get('length', 25);
        if ($length === 'all' || (int)$length === -1) {
            // Just get all results (no pagination)
            $results = $baseQuery->get();
        } else {
            $perPage = $request->get('length', 25);
            $page = floor($request->get('start', 0) / $perPage) + 1;

            $results = $baseQuery->paginate($perPage, ['*'], 'page', $page);
        }

        // $perPage = $request->get('length', 25);
        // $page = floor($request->get('start', 0) / $perPage) + 1;

        // $results = $baseQuery->paginate($perPage, ['*'], 'page', $page);


        // Preload related data for all parts (this can be optimized further)
        $allParts = \App\Models\AllPart::with([
            'part.part_numbers',
            'part.part_images',
            'source',
            'status',
            'part_quality',
            'sectionswithoutorder' => function ($query) use ($storeId) {
                $query->where('store_id', $storeId);
            },
            'sectionswithoutorder.store',
            'sectionswithoutorder.store_structure',
            'pricing.sale_type',
            'order_supplier.buy_transaction',
            'order_supplier.supplier',
            'part.sub_group.group',
            'part.part_details.part_spec',
            'part.part_models.series.model.brand',
        ])
            ->whereIn('part_id', $results->pluck('part_id'))
            ->get()
            ->keyBy(function ($item) {
                return '1-' . $item->part_id . '-' . $item->source_id . '-' . $item->status_id . '-' . $item->quality_id;
            });

        $allKits  = \App\Models\AllKit::with([
            'source',
            'kit',
            'status',
            'part_quality',
            'sectionswithoutorder' => function ($query) use ($storeId) {
                $query->where('store_id', $storeId);
            },
            'sectionswithoutorder.store',
            'sectionswithoutorder.store_structure',
            'pricing.sale_type',
            'order_supplier.buy_transaction',
            'order_supplier.supplier'
        ])
            ->whereIn('part_id', $results->pluck('part_id'))
            ->get()
            ->keyBy(function ($item) {
                return '6-' . $item->part_id . '-' . $item->source_id . '-' . $item->status_id . '-' . $item->quality_id;
            });

        $allWheelss  = AllWheel::with([
            'source',
            'wheel',
            'status',
            'part_quality',
            'sectionswithoutorder' => function ($query) use ($storeId) {
                $query->where('store_id', $storeId);
            },
            'sectionswithoutorder.store',
            'sectionswithoutorder.store_structure',
            'pricing.sale_type',
            'order_supplier.buy_transaction',
            'order_supplier.supplier'
        ])
            ->whereIn('part_id', $results->pluck('part_id'))
            ->get()
            ->keyBy(function ($item) {
                return '2-' . $item->part_id . '-' . $item->source_id . '-' . $item->status_id . '-' . $item->quality_id;
            });

        $remainAmountSum = 0;

        // Format the results to fit DataTables format
        $formattedResults = $results->map(function ($item, $index) use ($length, $results, $storeId, $allKits, $allWheelss, $allParts, $store, $remainAmountSum) {
            $key = $item->type_id . '-' . $item->part_id . '-' . $item->source_id . '-' . $item->status_id . '-' . $item->quality_id;

            if ($item->type_id == 1) {
                $relations = isset($allParts[$key]) ? $allParts[$key]->toArray() : null;
                foreach ($relations as $itemx) {

                    $remainAmountSum = AllPart::where('part_id', $item->part_id)->where('source_id', $item->source_id)
                        ->where('status_id', $item->status_id)->where('quality_id', $item->quality_id)
                        ->sum('remain_amount');
                }
            } else if ($item->type_id == 6) {
                $relations = isset($allKits[$key]) ? $allKits[$key]->toArray() : null;
                foreach ($relations as $itemx) {

                    $remainAmountSum = AllKit::where('part_id', $item->part_id)->where('source_id', $item->source_id)
                        ->where('status_id', $item->status_id)->where('quality_id', $item->quality_id)
                        ->sum('remain_amount');
                }
            } else if ($item->type_id == 2) {
                $relations = isset($allWheelss[$key]) ? $allWheelss[$key]->toArray() : null;
                if ($relations) {
                    foreach ($relations as $itemx) {

                        $remainAmountSum = AllWheel::where('part_id', $item->part_id)->where('source_id', $item->source_id)
                            ->where('status_id', $item->status_id)->where('quality_id', $item->quality_id)
                            ->sum('remain_amount');
                    }
                }
            }
            // $relations = isset($allParts[$key]) ? $allParts[$key]->toArray() : null;

            if ($length === 'all' || (int)$length === -1) {
                // Just get all results (no pagination)
                $adjustedIndex = $index + 1;
            } else {
                $adjustedIndex = ($results->currentPage() - 1) * $results->perPage() + $index + 1;
            }
            $sections = StoreSection::where('part_id', $item->part_id)
                ->where('source_id', $item->source_id)
                ->where('status_id', $item->status_id)
                ->where('quality_id', $item->quality_id)
                ->where('type_id', $item->type_id)
                ->where('store_id', $storeId)
                ->where('amount', '>', 0)
                ->with('store_structure')
                ->get();
            $firstSection = null;
            if (count($sections) == 0) {
                $sections = new \Illuminate\Database\Eloquent\Collection;
                $sections = 'N/A';
            } else {
                $firstSection = $sections[0]->section_id;
                foreach ($sections as $key => $sec) {
                    $sections = `<li>` . $sec->store_structure->name . `</li>`;
                }
            }

            // return $sections;
            return [
                'DT_RowIndex' =>  $adjustedIndex, // You can use any field as the index here
                'store_table' => $store->name,
                'part_id' => $item->part_id,
                'barcode' => 'FN'.$item->type_id.$item->part_id.$item->source_id.$item->status_id.$item->quality_id,
                'part_name' => $item->part_name,
                'storeModel' => $store->table_name,
                'source_name' => $relations['source']['name_arabic'] ?? 'N/A',
                'status_name' => $relations['status']['name'] ?? 'N/A',
                'quality_name' => $relations['part_quality']['name'] ?? 'N/A',
                'type_id' => $item->type_id,
                'sections' => '<span class="sectionName">'.$sections.'</span>' .'<i class="ri ri-edit-line float-end" data-toggle="modal" data-target="#addSectionModal" onclick="addSection(' . $store->id . ',this)"></i>',
                'amount' => '<input onkeydown="if(event.key === `Enter`) saveStoreAmount(' . $store->id . ',' . $item->type_id . ',' . $item->part_id . ',' . $item->source_id . ',' . $item->status_id . ',' . $item->quality_id . ',this.value,' . $firstSection . ')" class="form-control RwAmount" type="number" value="' . $item->amount . '" name="" id="">',
                'osid' => $relations['order_supplier']['supplier']['name'] ?? 'N/A',
                'remain_amount' => $remainAmountSum ?? 'N/A',
                'action' => '<a href="/itemDetails/' . $item->type_id . '/' . $item->part_id . '/' . $item->source_id . '/' . $item->status_id . '/' . $item->quality_id . '" class="btn btn-sm btn-primary">View Details</a>',
                'saveAction' => '<button onclick="" class="btn btn-sm btn-primary">Save</button>'
            ];
        });

        if ($length === 'all' || (int)$length === -1) {
            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => $results,
                'recordsFiltered' => $results,
                'data' => $formattedResults
            ]);
        } else {
            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => $results->total(),
                'recordsFiltered' => $results->total(),
                'data' => $formattedResults
            ]);
        }
    }

    public function itemDetails($type_id, $part_id, $source_id, $status_id, $quality_id)
    {
        $data = new \Illuminate\Database\Eloquent\Collection;
        $allstores = Store::all();
        if ($type_id == 1) {

            $data['data'] = AllPart::where('part_id', $part_id)->where('source_id', $source_id)
                ->where('status_id', $status_id)->where('quality_id', $quality_id)
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

            $AllStoredata = new \Illuminate\Database\Eloquent\Collection;
            foreach ($allstores as $key => $store) {
                $sdata = [];
                $store_model = ucfirst($store->table_name);
                if ($store_model == 'Damaged_parts') {
                    $store_model = 'damagedPart';
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $sdata['data'] = [];
                    $sdata['store'] = $store;
                } else {
                    $entity_tbl = 'App\\Models\\' . $store_model;
                   $query1x  = $entity_tbl::join('stores_log', "$store->table_name.store_log_id", '=', 'stores_log.id')
                   ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                   ->where("$store->table_name.type_id", $type_id)
                   ->where("$store->table_name.part_id", $part_id)
                   ->where("all_parts.source_id", $source_id)
                   ->where("all_parts.status_id", $status_id)
                   ->where("all_parts.quality_id", $quality_id)
                   ->select(
                       "$store->table_name.*", 
                       "$store->table_name.amount as amount" 
                   )
                   ->get();
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
                $invItems['refund'] = RefundInvoice::where('item_id', $invItems->id)->where('invoice_id', $invItems->invoice_id)->get();
            }

            $data['talef'] = Talef::where('part_id', $part_id)->where('source_id', $source_id)
                ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('type_id', $type_id)
                ->with('store')
                ->get();

            // $data['data']['type_id']=1;
        } elseif ($type_id == 2) {
            $data['data'] = AllWheel::where('part_id', $part_id)->where('source_id', $source_id)
                ->where('status_id', $status_id)->where('quality_id', $quality_id)
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

            $AllStoredata = new \Illuminate\Database\Eloquent\Collection;
            foreach ($allstores as $key => $store) {
                $sdata = [];
                $store_model = ucfirst($store->table_name);
                if ($store_model == 'Damaged_parts') {
                    $store_model = 'damagedPart';
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $sdata['data'] = [];
                    $sdata['store'] = $store;
                } else {
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $query1x = $entity_tbl::join('stores_log', "$store->table_name.store_log_id", '=', 'stores_log.id')
                        ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                        ->where("$store->table_name.type_id", $type_id)
                        ->where("$store->table_name.part_id", $part_id)
                        ->where("all_parts.source_id", $source_id)
                        ->where("all_parts.status_id", $status_id)
                        ->where("all_parts.quality_id", $quality_id)->get();
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
                $invItems['refund'] = RefundInvoice::where('item_id', $invItems->id)->where('invoice_id', $invItems->invoice_id)->get();
            }

            $data['talef'] = Talef::where('part_id', $part_id)->where('source_id', $source_id)
                ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('type_id', $type_id)
                ->with('store')
                ->get();
        } elseif ($type_id == 6) {
            $data['data'] = AllKit::where('part_id', $part_id)->where('source_id', $source_id)
                ->where('status_id', $status_id)->where('quality_id', $quality_id)
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

            $AllStoredata = new \Illuminate\Database\Eloquent\Collection;
            foreach ($allstores as $key => $store) {
                $sdata = [];
                $store_model = ucfirst($store->table_name);
                if ($store_model == 'Damaged_parts') {
                    $store_model = 'damagedPart';
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $sdata['data'] = [];
                    $sdata['store'] = $store;
                } else {
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $query1x = $entity_tbl::join('stores_log', "$store->table_name.store_log_id", '=', 'stores_log.id')
                        ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                        ->where("$store->table_name.type_id", $type_id)
                        ->where("$store->table_name.part_id", $part_id)
                        ->where("all_parts.source_id", $source_id)
                        ->where("all_parts.status_id", $status_id)
                        ->where("all_parts.quality_id", $quality_id)->get();
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
                $invItems['refund'] = RefundInvoice::where('item_id', $invItems->id)->where('invoice_id', $invItems->invoice_id)->get();
            }

            $data['talef'] = Talef::where('part_id', $part_id)->where('source_id', $source_id)
                ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('type_id', $type_id)
                ->with('store')
                ->get();

            // return $data;
        }
        // return $data;
        return view('partDetails', compact('data'));
    }

    public function saveStoreAmount(Request $request)
    {

        $logMessage = '';
        $logUser = Auth::user()->id;
        $message = '';
        $storeId = $request->input('store_id');
        $typeId = $request->input('type_id');
        $partId = $request->input('part_id');
        $sourceId = $request->input('source_id');
        $statusId = $request->input('status_id');
        $qualityId = $request->input('quality_id');
        $amount = $request->input('amount');
        $newsectionId = $request->input('new_section_id');
        $oldsectionId = $request->input('section_id');

        if (isset($newsectionId) && $newsectionId != '') {
            $sectionId = $newsectionId;
        } else {
            $sectionId = $oldsectionId;
        }
        
        // return $sectionId;
        $store = Store::find($storeId);
        $modelName = $store->table_name === 'damaged_parts'
            ? 'App\\Models\\DamagedPart'
            : 'App\\Models\\' . ucfirst($store->table_name);
        $storeTable = $store->table_name;

       $targetRow ='';
        if ($typeId == 1) {
            $modelName::join('stores_log', "$storeTable.store_log_id", '=', 'stores_log.id')
            ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
            ->where("$storeTable.type_id", $typeId)
            ->where("$storeTable.part_id", $partId)
            ->where("all_parts.source_id", $sourceId)
            ->where("all_parts.status_id", $statusId)
            ->where("all_parts.quality_id", $qualityId)
            ->update([
                "$storeTable.amount" => 0,
            ]);


            $targetRow = $modelName::join('stores_log', "$storeTable.store_log_id", '=', 'stores_log.id')
                ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                ->where("$storeTable.type_id", $typeId)
                ->where("$storeTable.part_id", $partId)
                ->where("all_parts.source_id", $sourceId)
                ->where("all_parts.status_id", $statusId)
                ->where("all_parts.quality_id", $qualityId)
                ->orderBy('stores_log.id', 'DESC')
                ->select("$storeTable.id") // Get the model's own ID
                ->first();

            if ($targetRow) {

                $modelName::where('id', $targetRow->id)->update([
                    'amount' => $amount,
                ]);
                $message .= 'Amount updated successfully in store. ';
            }
        } elseif ($typeId == 6) {
            $modelName::join('stores_log', "$storeTable.store_log_id", '=', 'stores_log.id')
            ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
            ->where("$storeTable.type_id", $typeId)
            ->where("$storeTable.part_id", $partId)
            ->where("all_kits.source_id", $sourceId)
            ->where("all_kits.status_id", $statusId)
            ->where("all_kits.quality_id", $qualityId)
            ->update([
                "$storeTable.amount" => 0,
            ]);


            $targetRow = $modelName::join('stores_log', "$storeTable.store_log_id", '=', 'stores_log.id')
                ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                ->where("$storeTable.type_id", $typeId)
                ->where("$storeTable.part_id", $partId)
                ->where("all_kits.source_id", $sourceId)
                ->where("all_kits.status_id", $statusId)
                ->where("all_kits.quality_id", $qualityId)
                ->orderBy('stores_log.id', 'DESC')
                ->select("$storeTable.id") // Get the model's own ID
                ->first();

            if ($targetRow) {

                $modelName::where('id', $targetRow->id)->update([
                    'amount' => $amount,
                ]);
                $message .= 'Amount updated successfully in store. ';
            }
        }elseif ($typeId == 2) {
            $modelName::join('stores_log', "$storeTable.store_log_id", '=', 'stores_log.id')
            ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
            ->where("$storeTable.type_id", $typeId)
            ->where("$storeTable.part_id", $partId)
            ->where("all_wheels.source_id", $sourceId)
            ->where("all_wheels.status_id", $statusId)
            ->where("all_wheels.quality_id", $qualityId)
            ->update([
                "$storeTable.amount" => 0,
            ]);


            $targetRow = $modelName::join('stores_log', "$storeTable.store_log_id", '=', 'stores_log.id')
                ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                ->where("$storeTable.type_id", $typeId)
                ->where("$storeTable.part_id", $partId)
                ->where("all_wheels.source_id", $sourceId)
                ->where("all_wheels.status_id", $statusId)
                ->where("all_wheels.quality_id", $qualityId)
                ->orderBy('stores_log.id', 'DESC')
                ->select("$storeTable.id") // Get the model's own ID
                ->first();

            if ($targetRow) {

                $modelName::where('id', $targetRow->id)->update([
                    'amount' => $amount,
                ]);
                $message .= 'Amount updated successfully in store. ';
            }
        }


        ///////////////////////////// change all_parts remain_amount


        $stores = Store::all();
        $allsotresData = 0;
        if ($typeId == 1) {
            $allsotresData = $stores->each(function ($item) use ($partId, $sourceId, $statusId, $qualityId, $typeId) {



                $item->storepartCount = DB::table($item->table_name)
                    ->select('*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                    ->where('all_parts.part_id', '=', $partId)
                    ->where('all_parts.source_id', '=', $sourceId)
                    ->where('all_parts.status_id', '=', $statusId)
                    ->where('all_parts.quality_id', '=', $qualityId)
                    ->where($item->table_name . '.type_id', '=', $typeId)
                    ->sum($item->table_name . '.amount');
            });

            $sumOfStores =  $allsotresData->sum('storepartCount');

            $allPart = AllPart::whereIn('id', $modelName::join('stores_log', "$storeTable.store_log_id", '=', 'stores_log.id')
                ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                ->where("$storeTable.type_id", $typeId)
                ->where("$storeTable.part_id", $partId)
                ->where("all_parts.source_id", $sourceId)
                ->where("all_parts.status_id", $statusId)
                ->where("all_parts.quality_id", $qualityId)
                ->orderBy('stores_log.id', 'DESC')
                ->select('stores_log.All_part_id')
                ->get())->orderBy('id', 'DESC')->get();
                
            $sumOfRemainAmount =  $allPart->sum('remain_amount');


            $totalAmount = $sumOfStores - $sumOfRemainAmount;
            foreach ($allPart as $keyx => $valuex) {
                if($totalAmount < 0){
                    if($valuex->amount  >= $valuex->remain_amount + $totalAmount){
                        
                        AllPart::where('id', $valuex->id)->decrement('remain_amount' , $totalAmount * -1 );
                        $logMessage .='here 01';
                        break;
                    }else{
                        AllPart::where('id', $valuex->id)->decrement('remain_amount' , $valuex->amount);
                        $totalAmount = $totalAmount - $valuex->amount;
                        $logMessage .='here 02';
                        break;
                    }
                    
                }else{
                    if($valuex->amount  >= $valuex->remain_amount + $totalAmount){
                        AllPart::where('id', $valuex->id)->increment('remain_amount' , $totalAmount);
                        $logMessage .='here 03';
                        break;
                    }else{
                        $logMessage .='here 04';
                    }
                }



                
                // if ($totalAmount  <= $valuex->amount) {
                //     // لو الاجمالي اقل من مجموع المخازن  يتم اضافة محموع المخازن
                //     $x = AllPart::where('id', $valuex->id)->update(['remain_amount' => $totalAmount]);
                //     $logMessage .= 'تعديل جردعلي  Allpart ' . $valuex->id . 'الصنف' . $valuex->part_id . ' الكمية ' . $totalAmount . '<br/>1<br/>';
                //     break;
                // } else if ($totalAmount > $valuex->amount) {
                //     $diff = 0;
                //     $diff = floatval($totalAmount) - floatval($valuex->amount);
                //     $totalAmount = $totalAmount - $diff;

                //     $x = AllPart::where('id', $valuex->id)->update(['remain_amount' => $valuex->amount]);
                //     $logMessage .= 'تعديل جرد علي Allpart ' . $valuex->id . 'الصنف' . $valuex->part_id . ' الكمية ' . $valuex->amount . '<br/>2<br/>';
                // } else if ($totalAmount <= 0) {
                //     $x = AllPart::where('id', $valuex->id)->update(['remain_amount' => 0]);
                //     $logMessage .= 'تعديل جردعلي  Allpart ' . $valuex->id . 'الصنف' . $valuex->part_id . ' الكمية  0<br/>3<br/>';
                //     break;
                // }
            }
        } elseif ($typeId == 6) {
            $allsotresData =  $stores->each(function ($item) use ($partId, $sourceId, $statusId, $qualityId, $typeId) {


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
                    ->where($item->table_name . '.type_id', '=', $typeId)
                    ->sum($item->table_name . '.amount');
            });

            $sumOfStores =  $allsotresData->sum('storepartCount');

            $allPart = AllKit::whereIn('id', $modelName::join('stores_log', "$storeTable.store_log_id", '=', 'stores_log.id')
                ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                ->where("$storeTable.type_id", $typeId)
                ->where("$storeTable.part_id", $partId)
                ->where("all_kits.source_id", $sourceId)
                ->where("all_kits.status_id", $statusId)
                ->where("all_kits.quality_id", $qualityId)
                ->orderBy('stores_log.id', 'DESC')
                ->select('stores_log.All_part_id')
                ->get())->orderBy('id', 'DESC')->get();


                $sumOfRemainAmount =  $allPart->sum('remain_amount');


                $totalAmount = $sumOfStores - $sumOfRemainAmount;
                foreach ($allPart as $keyx => $valuex) {
                    if($totalAmount < 0){
                        if($valuex->amount  >= $valuex->remain_amount + $totalAmount){
                            
                            AllKit::where('id', $valuex->id)->decrement('remain_amount' , $totalAmount * -1 );
                            $logMessage .='here 01';
                            break;
                        }else{
                            AllKit::where('id', $valuex->id)->decrement('remain_amount' , $valuex->amount);
                            $totalAmount = $totalAmount - $valuex->amount;
                            $logMessage .='here 02';
                            break;
                        }
                        
                    }else{
                        if($valuex->amount  >= $valuex->remain_amount + $totalAmount){
                            AllKit::where('id', $valuex->id)->increment('remain_amount' , $totalAmount);
                            $logMessage .='here 03';
                            break;
                        }else{
                            $logMessage .='here 04';
                        }
                    }
                }
        } elseif ($typeId == 2) {
            $allsotresData =  $stores->each(function ($item) use ($partId, $sourceId, $statusId, $qualityId, $typeId) {


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
                    ->where($item->table_name . '.type_id', '=', $typeId)
                    ->sum($item->table_name . '.amount');
            });

            $sumOfStores =  $allsotresData->sum('storepartCount');

            $allPart = AllWheel::whereIn('id', $modelName::join('stores_log', "$storeTable.store_log_id", '=', 'stores_log.id')
                ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                ->where("$storeTable.type_id", $typeId)
                ->where("$storeTable.part_id", $partId)
                ->where("all_wheels.source_id", $sourceId)
                ->where("all_wheels.status_id", $statusId)
                ->where("all_wheels.quality_id", $qualityId)
                ->orderBy('stores_log.id', 'DESC')
                ->select('stores_log.All_part_id')
                ->get())->orderBy('id', 'DESC')->get();


                $sumOfRemainAmount =  $allPart->sum('remain_amount');


                $totalAmount = $sumOfStores - $sumOfRemainAmount;
                foreach ($allPart as $keyx => $valuex) {
                    if($totalAmount < 0){
                        if($valuex->amount  >= $valuex->remain_amount + $totalAmount){
                            
                            AllWheel::where('id', $valuex->id)->decrement('remain_amount' , $totalAmount * -1 );
                            $logMessage .='here 01';
                            break;
                        }else{
                            AllWheel::where('id', $valuex->id)->decrement('remain_amount' , $valuex->amount);
                            $totalAmount = $totalAmount - $valuex->amount;
                            $logMessage .='here 02';
                            break;
                        }
                        
                    }else{
                        if($valuex->amount  >= $valuex->remain_amount + $totalAmount){
                            AllWheel::where('id', $valuex->id)->increment('remain_amount' , $totalAmount);
                            $logMessage .='here 03';
                            break;
                        }else{
                            $logMessage .='here 04';
                        }
                    }
                }
        } elseif ($typeId == 3) {
            $allsotresData =  $stores->each(function ($item) use ($partId, $sourceId, $statusId, $qualityId, $typeId) {
                // $storeClass = 'App\Models\\'.ucfirst($item->table_name);

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
                    ->where($item->table_name . '.type_id', '=', $typeId)
                    ->sum($item->table_name . '.amount');
            });
        } elseif ($typeId == 4) {
            $allsotresData =  $stores->each(function ($item) use ($partId, $sourceId, $statusId, $qualityId, $typeId) {
                // $storeClass = 'App\Models\\'.ucfirst($item->table_name);


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
                    ->where($item->table_name . '.type_id', '=', $typeId)
                    ->sum($item->table_name . '.amount');
            });
        } elseif ($typeId == 5) {
            $allsotresData =  $stores->each(function ($item) use ($partId, $sourceId, $statusId, $qualityId, $typeId) {
                // $storeClass = 'App\Models\\'.ucfirst($item->table_name);


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
                    ->where($item->table_name . '.type_id', '=', $typeId)
                    ->sum($item->table_name . '.amount');
            });
        }

        $message .= 'Amount updated successfully in All stores. ';

        if(isset($sectionId) && $sectionId != ''){
            StoreSection::where('store_id', $storeId)
            ->where('part_id', $partId)
            ->where('source_id', $sourceId)
            ->where('status_id', $statusId)
            ->where('quality_id', $qualityId)
            ->where('type_id', $typeId)
            ->delete();
            $xc = $modelName::where('id', $targetRow->id)->get();
            StoreSection::create([
                'store_id' => $storeId,
                'part_id' => $partId,
                'source_id' => $sourceId,
                'status_id' => $statusId,
                'quality_id' => $qualityId,
                'section_id' => $sectionId,
                'type_id' => $typeId,
                'amount' => $amount,
                'order_supplier_id' => $xc[0]->supplier_order_id ,
                'notes' => 'Updated from Gard'
            ]);

            $message .= 'Amount updated successfully in store Section. ';
        }else{
            $message .= 'Section not updated. ';
        }
          
        
        
        $logMessage .= 'تعديل جردعلي  ' . $store->name . ' ' . $partId . ' الصنف' . $partId . ' الكمية ' . $amount . '<br/>';






        $log = new LogController();
        $log->newLog($logUser, $logMessage);





        
        return response()->json(['success' => true, 'message' => $message]);
    }
    
     public function check(){
        return view('checkamount');
    }

    public function checkamounts(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('length', 10); // default 10 per page
        $start = ($page - 1) * $perPage;
            $Tindex = 1;
            $summary = [];
            $finalResults = AllPart::with('part')
            ->with('source')
            ->with('status')
            ->with('part_quality')
            ->select(
                'part_id',
                'source_id',
                'status_id',
                'quality_id',
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('SUM(remain_amount) as total_remain_amount')
            )
            ->groupBy('part_id', 'source_id', 'status_id', 'quality_id')
            ->get();
        
        $search = $request->input('search.value');
        if (!empty($search)) {
            $finalResults = $finalResults->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->part->name), strtolower($search)) ||
                    str_contains(strtolower($item->source->name_arabic), strtolower($search)) ||
                    str_contains(strtolower($item->status->name), strtolower($search)) ||
                    str_contains(strtolower($item->part_quality->name), strtolower($search));
            })->values();
        }
        
        foreach($finalResults as $item) {
            $AllStoredata = new \Illuminate\Database\Eloquent\Collection;
            $data = new \Illuminate\Database\Eloquent\Collection;
            $allstores = Store::all();
            $part_id = $item->part_id;
            $source_id = $item->source_id;
            $status_id = $item->status_id;
            $quality_id = $item->quality_id;
            $type_id = 1;
            $storeTotal = 0;
            foreach ($allstores as $key => $store) {
                $sdata = [];
                $store_model = ucfirst($store->table_name);
                if ($store_model == 'Damaged_parts') {
                    $store_model = 'damagedPart';
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $sdata['data'] = [];
                    $sdata['store'] = $store;
                } else {
                    $entity_tbl = 'App\\Models\\' . $store_model;
                    $query1x  = $entity_tbl::join('stores_log', "$store->table_name.store_log_id", '=', 'stores_log.id')
                        ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                        ->where("$store->table_name.type_id", $type_id)
                        ->where("$store->table_name.part_id", $part_id)
                        ->where("all_parts.source_id", $source_id)
                        ->where("all_parts.status_id", $status_id)
                        ->where("all_parts.quality_id", $quality_id)
                        ->select(
                            "$store->table_name.*",
                            "$store->table_name.amount as amount"
                        )
                        ->get();
                    $sdata['store'] = $store;
                    $sdata['data'] = $query1x;
                    $sdata['data'] = $query1x;

                    $storeTotal += $query1x->sum('amount');
                }
            
                $AllStoredata->push($sdata);
            }
            
            $data['allStores'] = $AllStoredata;
            $data['storeTotal'] = $storeTotal;
            
            $data['sections'] = StoreSection::where('part_id', $part_id)->where('source_id', $source_id)
                ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('type_id', $type_id)
                ->with('store')
                ->with('store_structure')
                ->get();
        $sectionTotal =$data['sections']->sum('amount');
            $data['sectionTotal']=$sectionTotal;

            $data['invoices'] = InvoiceItem::where('part_id', $part_id)->where('source_id', $source_id)
                ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('part_type_id', $type_id)
                ->with('invoice_item_order_suppliers')
                ->with('invoice_item_section')
                 ->with('invoice.store')
                ->get();
            $invoiceTotal =$data['invoices']->sum('amount');
            $data['invoiceTotal']=$invoiceTotal;
                
            $refundTotal = 0;
            foreach ($data['invoices'] as $key => $invItems) {
                $invItems['refund'] = RefundInvoice::where('item_id', $invItems->id)->where('invoice_id', $invItems->invoice_id)->get();
                $refundTotal += $invItems['refund']->sum('r_amount');
            }
            $data['refundinvoiceTotal']=$refundTotal;
                
            $data['talef'] = Talef::where('part_id', $part_id)->where('source_id', $source_id)
                ->where('status_id', $status_id)->where('quality_id', $quality_id)->where('type_id', $type_id)
                ->with('store')
                ->get();
            $talefTotal =$data['talef']->sum('amount');
            $data['talefTotal']=$talefTotal;
            
            array_push($summary, [
                'index' => $Tindex,
                'part_id' => $part_id,
                'part_name' => $item->part->name,
                'source_name' => $item->source->name_arabic,
                'status_name' => $item->status->name,
                'quality_name' => $item->part_quality->name,
                'source_id' => $source_id,
                'status_id' => $status_id,
                'quality_id' => $quality_id,
                'total_amount' => $item->total_amount,
                'total_remain_amount' => $item->total_remain_amount,
                'allStores' => $data['allStores'],
                'sections' => $data['sections'],
                'invoices' => $data['invoices'],
                'talef' => $data['talef'],
                
                'storeTotal' => $data['storeTotal'],
                'sectionTotal' => $data['sectionTotal'],
                'invoiceTotal' => $data['invoiceTotal'],
                'refundTotal' => $data['refundinvoiceTotal'],
                'talefTotal' => $data['talefTotal'],
            ]);
            $Tindex++;
        }
        $collection = new Collection($summary);

        $paginated = new LengthAwarePaginator(
            $collection->slice($start, $perPage)->values(),
            $collection->count(),
            $perPage,
            $page
        );

        return response()->json([
            'data' => $paginated->items(),
            'recordsTotal' => $paginated->total(),
            'recordsFiltered' => $paginated->total(),
            'draw' => intval($request->input('draw')),
        ]);

    }
}