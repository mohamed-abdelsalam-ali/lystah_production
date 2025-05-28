<?php

namespace App\Http\Controllers;

use App\Models\AllClark;
use App\Models\Qayditem;
use App\Models\AllEquip;
use App\Models\AllKit;
use App\Models\AllPart;
use App\Models\AllTractor;
use App\Models\AllWheel;
use App\Models\BankSafeMoney;
use App\Models\BankType;
use App\Models\Clark;
use App\Models\Client;
use App\Models\CurrencyType;
use App\Models\Equip;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\PricingType;
use App\Models\InvoiceItemsOrderSupplier;
use App\Models\InvoiceItemsSection;
use App\Models\InvoicesTax;
use App\Models\Kit;
use App\Models\MoneySafe;
use App\Models\Part;
use App\Models\PartQuality;
use App\Models\PresaleOrder;
use App\Models\PresaleOrderPart;
use App\Models\PresaleOrderTax;
use App\Models\SalePricing;
use App\Models\Source;
use App\Models\Status;
use App\Models\Store;
use App\Models\StoreSection;
use App\Models\StoresLog;
use App\Models\Tax;
use App\Models\Tractor;
use App\Models\Wheel;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPUnit\Event\Tracer\Tracer;

class ArdasarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        $presaleOrder = PresaleOrder::with('presaleTaxes')->where('flag', '<>', 3)->get();

        $parts = Part::with('all_parts')->with('part_details.part_spec')->with('part_details.mesure_unit')->get();

        $wheels = Wheel::with('all_wheels')->with('wheel_dimension')->with('wheel_details.wheel_spec')->with('wheel_details.mesure_unit')->get();
        $kits = Kit::with('all_kits')->with('kit_numbers')->with('kit_details.kit_specs')->with('kit_details.mesure_unit')->get();

        $tractors = Tractor::with('all_tractors')->with('tractor_details.tractor_spec')->with('tractor_details.mesure_unit')->get();

        $clarcks = Clark::with('all_clarks')->with('clark_details.clark_spec')->with('clark_details.mesure_unit')->get();

        $equips = Equip::with('all_equips')->with('equip_details.equip_spec')->with('equip_details.mesure_unit')->get();


        $stores = Store::where('table_name', '<>', 'damaged_parts')->get();
        // foreach ($parts as $key => $value) {
        //     foreach ($value->all_parts as $key => $pp) {
        //         $pp['pricing'] = SalePricing::where('part_id',$pp->part_id)->where('source_id',$pp->source_id)->where('status_id',$pp->status_id)->where('quality_id',$pp->quality_id)->where('type_id',1)->where('to',null)->orderBy('price','Desc')->get();
        //         $pp['source'] = Source::where('id',$pp->source_id)->first();
        //         $pp['status'] = Status::where('id',$pp->status_id)->first();
        //         $pp['quality'] =PartQuality::where('id',$pp->quality_id)->first();
        //         $pp['part'] =Part::where('id',$pp->part_id)->with('part_details.part_spec')->with('part_details.mesure_unit')->first();
        //         $pp['type'] = 1;
        //     }
        //     $storeData =[];
        //     foreach ($stores as $key => $st) {
        //         $storeClsName=ucfirst($st->table_name);
        //         $storeClsName ='App\Models\\'.$storeClsName;
        //         $storeClsName = str_replace([' ','_','-'],"",$storeClsName);
        //         if($st->table_name == "damaged_parts"){

        //         }else{
        //             array_push($storeData , (object) [ 'name' => $st->name ,'id' => $st->id , 'amount' => $storeClsName::where('part_id',$value->id)->where('type_id',1)->sum('amount') ]) ;
        //         }
        //     }
        //     $value['stores'] = $storeData;
        //     $value['type'] = 1;
        // }

        // foreach ($wheels as $key => $value) {
        //     foreach ($value->all_wheels as $key => $pp) {
        //         $pp['pricing'] = SalePricing::where('part_id',$pp->part_id)->where('source_id',$pp->source_id)->where('status_id',$pp->status_id)->where('quality_id',$pp->quality_id)->where('type_id',2)->where('to',null)->orderBy('price','Desc')->get();
        //         $pp['source'] = Source::where('id',$pp->source_id)->first();
        //         $pp['status'] = Status::where('id',$pp->status_id)->first();
        //         $pp['quality'] =PartQuality::where('id',$pp->quality_id)->first();
        //         $pp['part'] =Wheel::where('id',$pp->part_id)->with([
        //             'wheel_details' => function ($q) {
        //                 $q->with('wheel_spec')->with('mesure_unit')->get();
        //             },
        //         ])->first();
        //         $pp['type'] = 2;
        //     }
        //     $storeData =[];
        //     foreach ($stores as $key => $st) {
        //         $storeClsName=ucfirst($st->table_name);
        //         $storeClsName ='App\Models\\'.$storeClsName;
        //         $storeClsName = str_replace([' ','_','-'],"",$storeClsName);
        //         if($st->table_name == "damaged_parts"){

        //         }else{
        //             array_push($storeData , (object) [ 'name' => $st->name ,'id' => $st->id , 'amount' => $storeClsName::where('part_id',$value->id)->where('type_id',2)->sum('amount') ]) ;
        //         }
        //     }
        //     $value['stores'] = $storeData;
        //     $value['type'] = 2;
        // }

        // foreach ($kits as $key => $value) {
        //     foreach ($value->all_kits as $key => $pp) {
        //         $pp['pricing'] = SalePricing::where('part_id',$pp->part_id)->where('source_id',$pp->source_id)->where('status_id',$pp->status_id)->where('quality_id',$pp->quality_id)->where('type_id',6)->where('to',null)->orderBy('price','Desc')->get();
        //         $pp['source'] = Source::where('id',$pp->source_id)->first();
        //         $pp['status'] = Status::where('id',$pp->status_id)->first();
        //         $pp['quality'] =PartQuality::where('id',$pp->quality_id)->first();
        //         $pp['part'] =Kit::where('id',$pp->part_id)->with([
        //             'kit_details' => function ($q) {
        //                 $q->with('kit_specs')->with('mesure_unit')->get();
        //             },
        //         ])->first();
        //         $pp['type'] = 6;

        //     }
        //     $storeData =[];
        //     foreach ($stores as $key => $st) {
        //         $storeClsName=ucfirst($st->table_name);
        //         $storeClsName ='App\Models\\'.$storeClsName;
        //         $storeClsName = str_replace([' ','_','-'],"",$storeClsName);
        //         if($st->table_name == "damaged_parts"){

        //         }else{
        //             array_push($storeData , (object) [ 'name' => $st->name ,'id' => $st->id , 'amount' => $storeClsName::where('part_id',$value->id)->where('type_id',6)->sum('amount') ]) ;
        //         }
        //     }
        //     $value['stores'] = $storeData;
        //     $value['type'] = 6;
        // }

        // foreach ($tractors as $key => $value) {
        //     foreach ($value->all_tractors as $key => $pp) {
        //         $pp['pricing'] = SalePricing::where('part_id',$pp->part_id)->where('source_id',$pp->source_id)->where('status_id',$pp->status_id)->where('quality_id',$pp->quality_id)->where('type_id',3)->where('to',null)->orderBy('price','Desc')->get();
        //         $pp['source'] = Source::where('id',$pp->source_id)->first();
        //         $pp['status'] = Status::where('id',$pp->status_id)->first();
        //         $pp['quality'] =PartQuality::where('id',$pp->quality_id)->first();
        //         $pp['part'] =Tractor::where('id',$pp->part_id)->with([
        //             'tractor_details' => function ($q) {
        //                 $q->with('tractor_spec')->with('mesure_unit')->get();
        //             },
        //         ])->first();
        //         $pp['type'] = 3;

        //     }
        //     $storeData =[];
        //     foreach ($stores as $key => $st) {
        //         $storeClsName=ucfirst($st->table_name);
        //         $storeClsName ='App\Models\\'.$storeClsName;
        //         $storeClsName = str_replace([' ','_','-'],"",$storeClsName);
        //         if($st->table_name == "damaged_parts"){

        //         }else{
        //             array_push($storeData , (object) [ 'name' => $st->name ,'id' => $st->id , 'amount' => $storeClsName::where('part_id',$value->id)->where('type_id',3)->sum('amount') ]) ;
        //         }
        //     }
        //     $value['stores'] = $storeData;
        //     $value['type'] = 3;
        // }

        // foreach ($clarcks as $key => $value) {
        //     foreach ($value->all_clarks as $key => $pp) {
        //         $pp['pricing'] = SalePricing::where('part_id',$pp->part_id)->where('source_id',$pp->source_id)->where('status_id',$pp->status_id)->where('quality_id',$pp->quality_id)->where('type_id',4)->where('to',null)->orderBy('price','Desc')->get();
        //         $pp['source'] = Source::where('id',$pp->source_id)->first();
        //         $pp['status'] = Status::where('id',$pp->status_id)->first();
        //         $pp['quality'] =PartQuality::where('id',$pp->quality_id)->first();
        //         $pp['part'] =Clark::where('id',$pp->part_id)->with([
        //             'clark_details' => function ($q) {
        //                 $q->with('clark_spec')->with('mesure_unit')->get();
        //             },
        //         ])->first();
        //         $pp['type'] = 4;

        //     }
        //     $storeData =[];
        //     foreach ($stores as $key => $st) {
        //         $storeClsName=ucfirst($st->table_name);
        //         $storeClsName ='App\Models\\'.$storeClsName;
        //         $storeClsName = str_replace([' ','_','-'],"",$storeClsName);
        //         if($st->table_name == "damaged_parts"){

        //         }else{
        //             array_push($storeData , (object) [ 'name' => $st->name ,'id' => $st->id , 'amount' => $storeClsName::where('part_id',$value->id)->where('type_id',4)->sum('amount') ]) ;
        //         }
        //     }
        //     $value['stores'] = $storeData;
        //     $value['type'] = 4;
        // }

        // foreach ($equips as $key => $value) {
        //     foreach ($value->all_equips as $key => $pp) {
        //         $pp['pricing'] = SalePricing::where('part_id',$pp->part_id)->where('source_id',$pp->source_id)->where('status_id',$pp->status_id)->where('quality_id',$pp->quality_id)->where('type_id',5)->where('to',null)->orderBy('price','Desc')->get();
        //         $pp['source'] = Source::where('id',$pp->source_id)->first();
        //         $pp['status'] = Status::where('id',$pp->status_id)->first();
        //         $pp['quality'] =PartQuality::where('id',$pp->quality_id)->first();
        //         $pp['part'] =Equip::where('id',$pp->part_id)->with([
        //             'equip_details' => function ($q) {
        //                 $q->with('equip_spec')->with('mesure_unit')->get();
        //             },
        //         ])->first();
        //         $pp['type'] = 5;

        //     }
        //     $storeData =[];
        //     foreach ($stores as $key => $st) {
        //         $storeClsName=ucfirst($st->table_name);
        //         $storeClsName ='App\Models\\'.$storeClsName;
        //         $storeClsName = str_replace([' ','_','-'],"",$storeClsName);
        //         if($st->table_name == "damaged_parts"){

        //         }else{
        //             array_push($storeData , (object) [ 'name' => $st->name ,'id' => $st->id , 'amount' => $storeClsName::where('part_id',$value->id)->where('type_id',5)->sum('amount') ]) ;
        //         }
        //     }
        //     $value['stores'] = $storeData;
        //     $value['type'] = 5;
        // }


        // return $wheels;
        $clients = Client::all();
        $source = Source::all();
        $status = Status::all();
        $quality = PartQuality::all();
        $taxes = Tax::all();
        // return $parts;
        return view('ardAsar', compact('parts', 'wheels', 'clarcks', 'kits', 'tractors', 'source', 'equips', 'status', 'quality', 'clients', 'presaleOrder', 'taxes', 'stores'));
    }


    public function edit_asar($id)
    {
        $presaleOrder = PresaleOrder::where('id', $id)->with('client')->with(['presaleorderpart'])->with('presaleTaxes')->with('parts')->with('store')->first();

        foreach ($presaleOrder->presaleorderpart as $key => $item) {
            if ($item->part_type_id == 1) {
                $item['part'] =  Part::where('id', $item->part_id)->with('part_details_weight.part_spec_weight')->first();
            } elseif ($item->part_type_id == 6) {
                $item['kit'] =  Kit::where('id', $item->part_id)->with('part_details_weight.part_spec_weight')->first();
            } elseif ($item->part_type_id == 2) {
                $item['wheel'] =  Wheel::where('id', $item->part_id)->with('part_details_weight.part_spec_weight')->first();
            } elseif ($item->part_type_id == 3) {
                $item['tractor'] =  Tractor::where('id', $item->part_id)->with('part_details_weight.part_spec_weight')->first();
            } elseif ($item->part_type_id == 4) {
                $item['clarck'] =  Clark::where('id', $item->part_id)->with('part_details_weight.part_spec_weight')->first();
            } elseif ($item->part_type_id == 5) {
                $item['equip'] =  Equip::where('id', $item->part_id)->with('part_details_weight.part_spec_weight')->first();
            }

            $item['pricing'] = SalePricing::where('to', null)->where('part_id', $item->part_id)->where('status_id', $item->status_id)->where('source_id', $item->source_id)->where('quality_id', $item->quality_id)->with('sale_typex')->get();
        }

        // return $presaleOrder->presaleorderpart[0]->part->name;
        $presaleTaxIds = collect($presaleOrder->presaleTaxes)->pluck('tax_id')->toArray();

        $stores = Store::all();

        // return $wheels;
        $clients = Client::all();
        $source = Source::all();
        $status = Status::all();
        $quality = PartQuality::all();
        $taxes = Tax::all();
        //    return $presaleOrder;
        $PricingType = PricingType::all();
        return view('edit_ardAsar', compact('presaleOrder', 'presaleTaxIds', 'source', 'status', 'quality', 'clients', 'taxes', 'stores', 'PricingType'));
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
        // return $request;

        DB::beginTransaction();
        try {
            $imageName = null;
            if ($request->contractImg) {
                $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->contractImg->getClientOriginalName());
                $imageName = time() . $namewithoutchar . '.' . $request->contractImg->extension();
                $request->contractImg->move(public_path('assets/presaleOrderImage'), $imageName);
            }


            $client = Client::where('id', $request->clientId)->first();
            $lastId = PresaleOrder::create([
                'name' => ' عرض أسعار' . $client->name . ' بتاريخ ' . Carbon::now(),
                'due_date' => $request->dueDate,
                'flag' => 0,
                'client_id' => $client->id,
                'img' => $imageName,
                'subtotal' => $request->subtotal,
                'tax' => $request->tax,
                'total' => $request->total,
                'location' => $request->location,
                'store_id' => $request->storeId

            ])->id;
            if ($request->parts) {
                foreach ($request->parts as $key => $part) {
                    // $amount = $request->amount[$key];
                    $samllmeasureUnits = $request->samllmeasureUnits[$key];
                    $measureUnit = $request->measureUnit[$key];
                    $ratiounit = getSmallUnit($measureUnit, $samllmeasureUnits);

                    PresaleOrderPart::create([
                        'part_id' => $part,
                        'amount' => $request->amount[$key] *  $ratiounit ,
                        'presaleOrder_id' => $lastId,
                        'status_id' => $request->status[$key],
                        'source_id' => $request->source[$key],
                        'quality_id' => $request->quality[$key],
                        'part_type_id' => $request->types[$key],
                        'price' => $request->price[$key] /  $ratiounit ,
                        'unit_id'=> $request->measureUnit[$key],
		                'amount_unit'=> $request->amount[$key] 

                    ]);
                }
            }
            if ($request->taxes) {
                foreach ($request->taxes as $key => $tax) {
                    PresaleOrderTax::create([
                        'presaleOrderid' => $lastId,
                        'tax_id' => $tax
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'تم إضافة عرض أسعار جديد');
        } catch (\Exception $e) {
            //throw $th;
            DB::rollback();
            session()->flash("error", $e->getMessage());
            return false;
        }
        // return redirect()->back()->with('success','تم إضافة عرض أسعار جديد');

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
        // return $request;
        DB::beginTransaction();
        try {
            $parts = PresaleOrderPart::where('presaleOrder_id', $request->presaleOrder_id)->get();
            foreach ($parts as $part) {
                $part->delete();
            }

            $taxes = PresaleOrderTax::where('presaleOrderid', $request->presaleOrder_id)->get();
            foreach ($taxes as $tax) {
                $tax->delete();
            }

            $edit_presaleorder = PresaleOrder::where('id', $request->presaleOrder_id)->first();
            if ($request->file('contractImg')) {
                if ($edit_presaleorder->img != null) {
                    $image_path = public_path() . '/users_images' . '/' . $edit_presaleorder->img;
                    unlink($image_path);
                    $imageName = null;
                    if ($request->contractImg) {
                        $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->contractImg->getClientOriginalName());
                        $imageName = time() . $namewithoutchar . '.' . $request->contractImg->extension();
                        $request->contractImg->move(public_path('assets/presaleOrderImage'), $imageName);
                    }
                    $edit_presaleorder->update([
                        'img' => $imageName,
                    ]);
                }
            }
            $client = Client::where('id', $request->clientId)->first();
            $edit_presaleorder->update([
                'name' => ' عرض أسعار' . $client->name . ' بتاريخ ' . Carbon::now(),
                'due_date' => $request->dueDate,
                'flag' => 0,
                'client_id' => $client->id,
                'subtotal' => $request->subtotal,
                'tax' => $request->tax,
                'total' => $request->total,
                'location' => $request->location,
                'store_id' => $request->storeId
            ]);
            if ($request->parts) {
                foreach ($request->parts as $key => $part) {
                    $samllmeasureUnits = $request->samllmeasureUnits[$key];
                    $measureUnit = $request->measureUnit[$key];
                    $ratiounit = getSmallUnit($measureUnit, $samllmeasureUnits);
                
                    PresaleOrderPart::create([
                        'part_id' => $part,
                        'amount' => $request->amount[$key] *  $ratiounit ,
                        'presaleOrder_id' => $edit_presaleorder->id,
                        'status_id' => $request->status[$key],
                        'source_id' => $request->source[$key],
                        'quality_id' => $request->quality[$key],
                        'part_type_id' => $request->types[$key],
                        'price' => $request->price[$key] /  $ratiounit,
                        'unit_id'=> $request->measureUnit[$key],
                        'amount_unit'=> $request->amount[$key]

                    ]);
                }
            }
            if ($request->taxes) {
                foreach ($request->taxes as $key => $tax) {
                    PresaleOrderTax::create([
                        'presaleOrderid' => $edit_presaleorder->id,
                        'tax_id' => $tax
                    ]);
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'تم تعديل عرض أسعار ');
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash("success", "يوجد خطأ");
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function GetOrderStatus(PresaleOrder $orderId)
    {
        $presaleOrders = PresaleOrder::where('id' , $orderId->id)->with('presaleorderpart.unit')->with('store')->first();

        $stores = Store::where('table_name', '<>', 'damaged_parts')->get();


        foreach ($presaleOrders->presaleorderpart as $key => $part) {
            $part['pricing'] = SalePricing::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->where('type_id', $part->part_id)->where('to', null)->orderBy('price', 'Desc')->get();
            $part['source'] = Source::where('id', $part->source_id)->first();
            $part['status'] = Status::where('id', $part->status_id)->first();
            $part['ratiounit']=getSmallUnit($part->unit_id,$part->part->small_unit);

            $part['quality'] = PartQuality::where('id', $part->quality_id)->first();
            $storeData = [];

            if ($part->part_type_id == 1) {
                $part['total'] = AllPart::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->sum('remain_amount');
                $part['part'] =Part::where('id',$part->part_id)->with('getsmallunit.unit')->first();

                $allparts = AllPart::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->get();
                foreach ($allparts as $key => $value) {
                    $allstorelogs = StoresLog::where('All_part_id', $value->id)->where('type_id', 1)->select('id')->get();

                    foreach ($stores as $key => $st) {
                        $storeClsName = ucfirst($st->table_name);
                        $storeClsName = 'App\Models\\' . $storeClsName;
                        $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                        if ($st->table_name == "damaged_parts") {
                        } else {

                            $ItemAmmount = $storeClsName::where('part_id', $part->part_id)->whereIn('store_log_id', $allstorelogs)->sum('amount');
                            array_push($storeData, (object) ['name' => $st->name, 'id' => $st->id, 'amount' => ($ItemAmmount) ? $ItemAmmount : 0]);
                        }
                    }
                    $part['stores'] = $storeData;
                }
            } else if ($part->part_type_id == 2) {
                $part['total'] = AllWheel::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->sum('remain_amount');
                $part['part'] = Wheel::where('id', $part->part_id)->first();
                $allparts = AllWheel::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->get();
                foreach ($allparts as $key => $value) {
                    $allstorelogs = StoresLog::where('All_part_id', $value->id)->where('type_id', $part->part_type_id)->select('id')->get();

                    foreach ($stores as $key => $st) {
                        $storeClsName = ucfirst($st->table_name);
                        $storeClsName = 'App\Models\\' . $storeClsName;
                        $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                        if ($st->table_name == "damaged_parts") {
                        } else {

                            $ItemAmmount = $storeClsName::where('part_id', $part->part_id)->whereIn('store_log_id', $allstorelogs)->sum('amount');
                            array_push($storeData, (object) ['name' => $st->name, 'id' => $st->id, 'amount' => ($ItemAmmount) ? $ItemAmmount : 0]);
                        }
                    }
                    $part['stores'] = $storeData;
                }
            } else if ($part->part_type_id == 3) {
                $part['total'] = AllTractor::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->sum('remain_amount');
                $part['part'] = Tractor::where('id', $part->part_id)->first();
                $allparts = AllTractor::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->get();
                foreach ($allparts as $key => $value) {
                    $allstorelogs = StoresLog::where('All_part_id', $value->id)->where('type_id', $part->part_type_id)->select('id')->get();

                    foreach ($stores as $key => $st) {
                        $storeClsName = ucfirst($st->table_name);
                        $storeClsName = 'App\Models\\' . $storeClsName;
                        $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                        if ($st->table_name == "damaged_parts") {
                        } else {

                            $ItemAmmount = $storeClsName::where('part_id', $part->part_id)->whereIn('store_log_id', $allstorelogs)->sum('amount');
                            array_push($storeData, (object) ['name' => $st->name, 'id' => $st->id, 'amount' => ($ItemAmmount) ? $ItemAmmount : 0]);
                        }
                    }
                    $part['stores'] = $storeData;
                }
            } else if ($part->part_type_id == 4) {
                $part['total'] = AllClark::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->sum('remain_amount');
                $part['part'] = Clark::where('id', $part->part_id)->first();
                $allparts = AllClark::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->get();
                foreach ($allparts as $key => $value) {
                    $allstorelogs = StoresLog::where('All_part_id', $value->id)->where('type_id', $part->part_type_id)->select('id')->get();

                    foreach ($stores as $key => $st) {
                        $storeClsName = ucfirst($st->table_name);
                        $storeClsName = 'App\Models\\' . $storeClsName;
                        $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                        if ($st->table_name == "damaged_parts") {
                        } else {

                            $ItemAmmount = $storeClsName::where('part_id', $part->part_id)->whereIn('store_log_id', $allstorelogs)->sum('amount');
                            array_push($storeData, (object) ['name' => $st->name, 'id' => $st->id, 'amount' => ($ItemAmmount) ? $ItemAmmount : 0]);
                        }
                    }
                    $part['stores'] = $storeData;
                }
            } else if ($part->part_type_id == 5) {
                $part['total'] = AllEquip::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->sum('remain_amount');
                $part['part'] = Equip::where('id', $part->part_id)->first();
                $allparts = AllEquip::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->get();
                foreach ($allparts as $key => $value) {
                    $allstorelogs = StoresLog::where('All_part_id', $value->id)->where('type_id', $part->part_type_id)->select('id')->get();

                    foreach ($stores as $key => $st) {
                        $storeClsName = ucfirst($st->table_name);
                        $storeClsName = 'App\Models\\' . $storeClsName;
                        $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                        if ($st->table_name == "damaged_parts") {
                        } else {

                            $ItemAmmount = $storeClsName::where('part_id', $part->part_id)->whereIn('store_log_id', $allstorelogs)->sum('amount');
                            array_push($storeData, (object) ['name' => $st->name, 'id' => $st->id, 'amount' => ($ItemAmmount) ? $ItemAmmount : 0]);
                        }
                    }
                    $part['stores'] = $storeData;
                }
            } else if ($part->part_type_id == 6) {
                $part['total'] = AllKit::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->sum('remain_amount');
                $part['part'] = Kit::where('id', $part->part_id)->first();
                $allparts = AllKit::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->get();
                foreach ($allparts as $key => $value) {
                    $allstorelogs = StoresLog::where('All_part_id', $value->id)->where('type_id', $part->part_type_id)->select('id')->get();

                    foreach ($stores as $key => $st) {
                        $storeClsName = ucfirst($st->table_name);
                        $storeClsName = 'App\Models\\' . $storeClsName;
                        $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                        if ($st->table_name == "damaged_parts") {
                        } else {

                            $ItemAmmount = $storeClsName::where('part_id', $part->part_id)->whereIn('store_log_id', $allstorelogs)->sum('amount');
                            array_push($storeData, (object) ['name' => $st->name, 'id' => $st->id, 'amount' => ($ItemAmmount) ? $ItemAmmount : 0]);
                        }
                    }
                    $part['stores'] = $storeData;
                }
            }
        }
        return $presaleOrders;
    }

    public function preSaleToInvoice(PresaleOrder $orderId)
    {

        $presaleOrders = PresaleOrder::where('id' , $orderId->id)->with('presaleorderpart.unit')->with('presaleTaxes')->with('client')->with('store')->first();
        $stores = Store::all();

        $bank_types = BankType::all();
        $store_safe = Store::where('safe_accountant_number', '<>', '0')->get();

        foreach ($presaleOrders->presaleorderpart as $key => $part) {
            // $part['pricing'] = SalePricing::with('part_quality')->with('sale_typex')->where('part_id',$part->part_id)->where('source_id',$part->source_id)->where('status_id',$part->status_id)->where('quality_id',$part->quality_id)->where('type_id',1)->where('to',null)->orderBy('price','DESC')->get();
            // return $part->pricing[0]->sale_typex;
            $part['source'] = Source::where('id', $part->source_id)->first();
            $part['status'] = Status::where('id', $part->status_id)->first();
            $part['ratiounit']=1;

            // $part['part'] =Part::where('id',$part->part_id)->first();
            $part['quality'] = PartQuality::where('id', $part->quality_id)->first();
            if ($part->part_type_id == 1) {
                $part['part'] = Part::where('id', $part->part_id)->first();
                $part['ratiounit']=getSmallUnit($part->unit_id,$part->part->small_unit);

                $part['pricing'] = SalePricing::with('part_quality')->with('sale_typex')->where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->where('type_id', 1)->where('to', null)->orderBy('price', 'DESC')->get();
            } elseif ($part->part_type_id == 2) {
                $part['part'] = Wheel::where('id', $part->part_id)->first();
                $part['pricing'] = SalePricing::with('part_quality')->with('sale_typex')->where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->where('type_id', 2)->where('to', null)->orderBy('price', 'DESC')->get();
            } elseif ($part->part_type_id == 3) {
                $part['part'] = Tractor::where('id', $part->part_id)->first();
                $part['pricing'] = SalePricing::with('part_quality')->with('sale_typex')->where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->where('type_id', 3)->where('to', null)->orderBy('price', 'DESC')->get();
            } elseif ($part->part_type_id == 4) {
                $part['part'] = Clark::where('id', $part->part_id)->first();
                $part['pricing'] = SalePricing::with('part_quality')->with('sale_typex')->where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->where('type_id', 4)->where('to', null)->orderBy('price', 'DESC')->get();
            } elseif ($part->part_type_id == 5) {
                $part['part'] = Equip::where('id', $part->part_id)->first();
                $part['pricing'] = SalePricing::with('part_quality')->with('sale_typex')->where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->where('type_id', 5)->where('to', null)->orderBy('price', 'DESC')->get();
            } elseif ($part->part_type_id == 6) {
                $part['part'] = Kit::where('id', $part->part_id)->first();
                $part['ratiounit']=getSmallUnit($part->unit_id,$part->part->small_unit);

                $part['pricing'] = SalePricing::with('part_quality')->with('sale_typex')->where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->where('type_id', 6)->where('to', null)->orderBy('price', 'DESC')->get();
            }
            $storeData = [];

            $prices_arr = array_column($part->pricing->toArray(), 'price');
            $part['closestPrice'] = $this->getClosest($part->price, $prices_arr);
            $part['closestPriceindex'] = array_search($part['closestPrice'], $prices_arr);
            if ($part['closestPrice']) {
                $part['closestPricedata'] = $part->pricing[$part['closestPriceindex']];
                $part['closestPricename'] = $part->pricing[$part['closestPriceindex']];
            } else {
                $part['closestPricedata'] = 0;
                $part['closestPricename'] = '-';
            }



            $storeData = [];

            if ($part->part_type_id == 1) {
                $part['total'] = AllPart::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->sum('remain_amount');
                $part['part'] = Part::where('id', $part->part_id)->with([
                    'part_details' => function ($q) {
                        $q->with('part_spec')->with('mesure_unit')->get();
                    },
                ])->first();
                $part['type'] = 1;

                $allparts = AllPart::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->get();
                foreach ($allparts as $key => $value) {
                    $allstorelogs = StoresLog::where('All_part_id', $value->id)->where('type_id', 1)->select('id')->get();

                    foreach ($stores as $key => $st) {
                        $storeClsName = ucfirst($st->table_name);
                        $storeClsName = 'App\Models\\' . $storeClsName;
                        $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                        if ($st->table_name == "damaged_parts") {
                        } else {

                            $ItemAmmount = $storeClsName::where('part_id', $part->part_id)->whereIn('store_log_id', $allstorelogs)->sum('amount');
                            array_push($storeData, (object) ['name' => $st->name, 'id' => $st->id, 'amount' => ($ItemAmmount) ? $ItemAmmount : 0]);
                        }
                    }
                    $part['stores'] = $storeData;
                }
            } else if ($part->part_type_id == 2) {
                $part['total'] = AllWheel::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->sum('remain_amount');
                $part['part'] = Wheel::where('id', $part->part_id)->with([
                    'wheel_details' => function ($q) {
                        $q->with('wheel_spec')->with('mesure_unit')->get();
                    },
                ])->first();
                $part['type'] = 2;
                $allparts = AllWheel::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->get();
                foreach ($allparts as $key => $value) {
                    $allstorelogs = StoresLog::where('All_part_id', $value->id)->where('type_id', $part->part_type_id)->select('id')->get();

                    foreach ($stores as $key => $st) {
                        $storeClsName = ucfirst($st->table_name);
                        $storeClsName = 'App\Models\\' . $storeClsName;
                        $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                        if ($st->table_name == "damaged_parts") {
                        } else {

                            $ItemAmmount = $storeClsName::where('part_id', $part->part_id)->whereIn('store_log_id', $allstorelogs)->sum('amount');
                            array_push($storeData, (object) ['name' => $st->name, 'id' => $st->id, 'amount' => ($ItemAmmount) ? $ItemAmmount : 0]);
                        }
                    }
                    $part['stores'] = $storeData;
                }
            } else if ($part->part_type_id == 3) {
                $part['total'] = AllTractor::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->sum('remain_amount');
                $part['part'] = Tractor::where('id', $part->part_id)->with([
                    'tractor_details' => function ($q) {
                        $q->with('tractor_spec')->with('mesure_unit')->get();
                    },
                ])->first();
                $part['type'] = 3;
                $allparts = AllTractor::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->get();
                foreach ($allparts as $key => $value) {
                    $allstorelogs = StoresLog::where('All_part_id', $value->id)->where('type_id', $part->part_type_id)->select('id')->get();

                    foreach ($stores as $key => $st) {
                        $storeClsName = ucfirst($st->table_name);
                        $storeClsName = 'App\Models\\' . $storeClsName;
                        $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                        if ($st->table_name == "damaged_parts") {
                        } else {

                            $ItemAmmount = $storeClsName::where('part_id', $part->part_id)->whereIn('store_log_id', $allstorelogs)->sum('amount');
                            array_push($storeData, (object) ['name' => $st->name, 'id' => $st->id, 'amount' => ($ItemAmmount) ? $ItemAmmount : 0]);
                        }
                    }
                    $part['stores'] = $storeData;
                }
            } else if ($part->part_type_id == 4) {
                $part['total'] = AllClark::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->sum('remain_amount');
                $part['part'] = Clark::where('id', $part->part_id)->with([
                    'clark_details' => function ($q) {
                        $q->with('clark_spec')->with('mesure_unit')->get();
                    },
                ])->first();
                $part['type'] = 4;
                $allparts = AllClark::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->get();
                foreach ($allparts as $key => $value) {
                    $allstorelogs = StoresLog::where('All_part_id', $value->id)->where('type_id', $part->part_type_id)->select('id')->get();

                    foreach ($stores as $key => $st) {
                        $storeClsName = ucfirst($st->table_name);
                        $storeClsName = 'App\Models\\' . $storeClsName;
                        $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                        if ($st->table_name == "damaged_parts") {
                        } else {

                            $ItemAmmount = $storeClsName::where('part_id', $part->part_id)->whereIn('store_log_id', $allstorelogs)->sum('amount');
                            array_push($storeData, (object) ['name' => $st->name, 'id' => $st->id, 'amount' => ($ItemAmmount) ? $ItemAmmount : 0]);
                        }
                    }
                    $part['stores'] = $storeData;
                }
            } else if ($part->part_type_id == 5) {
                $part['total'] = AllEquip::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->sum('remain_amount');
                $part['part'] = Equip::where('id', $part->part_id)->with([
                    'equip_details' => function ($q) {
                        $q->with('equip_spec')->with('mesure_unit')->get();
                    },
                ])->first();
                $part['type'] = 5;
                $allparts = AllEquip::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->get();
                foreach ($allparts as $key => $value) {
                    $allstorelogs = StoresLog::where('All_part_id', $value->id)->where('type_id', $part->part_type_id)->select('id')->get();

                    foreach ($stores as $key => $st) {
                        $storeClsName = ucfirst($st->table_name);
                        $storeClsName = 'App\Models\\' . $storeClsName;
                        $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                        if ($st->table_name == "damaged_parts") {
                        } else {

                            $ItemAmmount = $storeClsName::where('part_id', $part->part_id)->whereIn('store_log_id', $allstorelogs)->sum('amount');
                            array_push($storeData, (object) ['name' => $st->name, 'id' => $st->id, 'amount' => ($ItemAmmount) ? $ItemAmmount : 0]);
                        }
                    }
                    $part['stores'] = $storeData;
                }
            } else if ($part->part_type_id == 6) {
                $part['total'] = AllKit::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->sum('remain_amount');
                $part['part'] = Kit::where('id', $part->part_id)->with([
                    'kit_details' => function ($q) {
                        $q->with('kit_specs')->with('mesure_unit')->get();
                    },
                ])->first();
                $part['type'] = 6;
                $allparts = AllKit::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->get();
                foreach ($allparts as $key => $value) {
                    $allstorelogs = StoresLog::where('All_part_id', $value->id)->where('type_id', $part->part_type_id)->select('id')->get();

                    foreach ($stores as $key => $st) {
                        $storeClsName = ucfirst($st->table_name);
                        $storeClsName = 'App\Models\\' . $storeClsName;
                        $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                        if ($st->table_name == "damaged_parts") {
                        } else {

                            $ItemAmmount = $storeClsName::where('part_id', $part->part_id)->whereIn('store_log_id', $allstorelogs)->sum('amount');
                            array_push($storeData, (object) ['name' => $st->name, 'id' => $st->id, 'amount' => ($ItemAmmount) ? $ItemAmmount : 0]);
                        }
                    }
                    $part['stores'] = $storeData;
                }
            }
        }

        // return $presaleOrders;

        return view('presaleToInvoice', compact('presaleOrders', 'stores', 'bank_types', 'store_safe'));
    }

    function getClosest($search, $arr)
    {
        $closest = null;
        foreach ($arr as $item) {
            if ($closest === null || abs($search - $closest) > abs($item - $search)) {
                $closest = $item;
            }
        }
        return $closest;
    }

    public function printpreSale($lang, PresaleOrder $orderId, $showflag)
    {
        $presaleOrders = PresaleOrder::where('id', $orderId->id)
            ->with([
                'presaleorderpart' => function ($qq) {
                    $qq->with('oem_number')->get();
                },
            ])
            ->with('presaleTaxes')->with('client')->with('store')->first();
        $stores = Store::all();

        foreach ($presaleOrders->presaleorderpart as $key => $part) {
            $part['pricing'] = SalePricing::with('part_quality')->with('sale_typex')->where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->where('type_id', 1)->where('to', null)->orderBy('price', 'DESC')->get();
            // return $part->pricing[0]->sale_typex;
            $part['source'] = Source::where('id', $part->source_id)->first();
            $part['status'] = Status::where('id', $part->status_id)->first();
            // $part['part'] =Part::where('id',$part->part_id)->first();
            $part['quality'] = PartQuality::where('id', $part->quality_id)->first();
            $storeData = [];
            $prices_arr = array_column($part->pricing->toArray(), 'price');
            $part['closestPrice'] = $this->getClosest($part->price, $prices_arr);
            $part['closestPriceindex'] = array_search($part['closestPrice'], $prices_arr);

            if ($part['closestPrice']) {
                $part['closestPricedata'] = $part->pricing[$part['closestPriceindex']];
                $part['closestPricename'] = $part->pricing[$part['closestPriceindex']];
            } else {
                $part['closestPricedata'] = 0;
                $part['closestPricename'] = '-';
            }

            //    return  $part['closestPricedata'];
            // array_search($part['closestPrice'],$prices_arr);
            // if($part->part_type_id == 1){
            //    $part['total'] = AllPart::where('part_id',$part->part_id)->where('source_id',$part->source_id)->where('status_id',$part->status_id)->where('quality_id',$part->quality_id)->sum('remain_amount');
            // }

            if ($part->part_type_id == 1) {
                $part['total'] = AllPart::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->sum('remain_amount');
                $part['part'] = Part::where('id', $part->part_id)->with([
                    'part_details' => function ($q) {
                        $q->with('part_spec')->with('mesure_unit')->get();
                    },
                ])->first();
                $part['type'] = 1;

                $allparts = AllPart::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->get();
                if ($allparts) {
                    foreach ($allparts as $key => $value) {
                        $allstorelogs = StoresLog::where('All_part_id', $value->id)->where('type_id', 1)->select('id')->get();

                        foreach ($stores as $key => $st) {
                            $storeClsName = ucfirst($st->table_name);
                            $storeClsName = 'App\Models\\' . $storeClsName;
                            $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                            if ($st->table_name == "damaged_parts") {
                            } else {

                                $ItemAmmount = $storeClsName::where('part_id', $part->part_id)->whereIn('store_log_id', $allstorelogs)->sum('amount');
                                array_push($storeData, (object) ['name' => $st->name, 'id' => $st->id, 'amount' => ($ItemAmmount) ? $ItemAmmount : 0]);
                            }
                        }
                        $part['stores'] = $storeData;
                    }
                }
            } else if ($part->part_type_id == 2) {
                $part['total'] = AllWheel::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->sum('remain_amount');
                $part['part'] = Wheel::where('id', $part->part_id)->with([
                    'wheel_details' => function ($q) {
                        $q->with('wheel_spec')->with('mesure_unit')->get();
                    },
                ])->first();
                $part['type'] = 2;

                $allparts = AllWheel::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->get();
                if ($allparts) {
                    foreach ($allparts as $key => $value) {
                        $allstorelogs = StoresLog::where('All_part_id', $value->id)->where('type_id', $part->part_type_id)->select('id')->get();

                        foreach ($stores as $key => $st) {
                            $storeClsName = ucfirst($st->table_name);
                            $storeClsName = 'App\Models\\' . $storeClsName;
                            $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                            if ($st->table_name == "damaged_parts") {
                            } else {

                                $ItemAmmount = $storeClsName::where('part_id', $part->part_id)->whereIn('store_log_id', $allstorelogs)->sum('amount');
                                array_push($storeData, (object) ['name' => $st->name, 'id' => $st->id, 'amount' => ($ItemAmmount) ? $ItemAmmount : 0]);
                            }
                        }
                        $part['stores'] = $storeData;
                    }
                }
            } else if ($part->part_type_id == 3) {
                $part['total'] = AllTractor::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->sum('remain_amount');
                $part['part'] = Tractor::where('id', $part->part_id)->with([
                    'tractor_details' => function ($q) {
                        $q->with('tractor_spec')->with('mesure_unit')->get();
                    },
                ])->first();
                $part['type'] = 3;

                $allparts = AllTractor::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->get();
                if ($allparts) {
                    foreach ($allparts as $key => $value) {
                        $allstorelogs = StoresLog::where('All_part_id', $value->id)->where('type_id', $part->part_type_id)->select('id')->get();

                        foreach ($stores as $key => $st) {
                            $storeClsName = ucfirst($st->table_name);
                            $storeClsName = 'App\Models\\' . $storeClsName;
                            $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                            if ($st->table_name == "damaged_parts") {
                            } else {

                                $ItemAmmount = $storeClsName::where('part_id', $part->part_id)->whereIn('store_log_id', $allstorelogs)->sum('amount');
                                array_push($storeData, (object) ['name' => $st->name, 'id' => $st->id, 'amount' => ($ItemAmmount) ? $ItemAmmount : 0]);
                            }
                        }
                        $part['stores'] = $storeData;
                    }
                }
            } else if ($part->part_type_id == 4) {
                $part['total'] = AllClark::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->sum('remain_amount');
                $part['part'] = Clark::where('id', $part->part_id)->with([
                    'clark_details' => function ($q) {
                        $q->with('clark_spec')->with('mesure_unit')->get();
                    },
                ])->first();
                $part['type'] = 4;
                $allparts = AllClark::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->get();
                if ($allparts) {
                    foreach ($allparts as $key => $value) {
                        $allstorelogs = StoresLog::where('All_part_id', $value->id)->where('type_id', $part->part_type_id)->select('id')->get();

                        foreach ($stores as $key => $st) {
                            $storeClsName = ucfirst($st->table_name);
                            $storeClsName = 'App\Models\\' . $storeClsName;
                            $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                            if ($st->table_name == "damaged_parts") {
                            } else {

                                $ItemAmmount = $storeClsName::where('part_id', $part->part_id)->whereIn('store_log_id', $allstorelogs)->sum('amount');
                                array_push($storeData, (object) ['name' => $st->name, 'id' => $st->id, 'amount' => ($ItemAmmount) ? $ItemAmmount : 0]);
                            }
                        }
                        $part['stores'] = $storeData;
                    }
                }
            } else if ($part->part_type_id == 5) {
                $part['total'] = AllEquip::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->sum('remain_amount');
                $part['part'] = Equip::where('id', $part->part_id)->with([
                    'equip_details' => function ($q) {
                        $q->with('equip_spec')->with('mesure_unit')->get();
                    },
                ])->first();
                $part['type'] = 5;
                $allparts = AllEquip::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->get();
                if ($allparts) {
                    foreach ($allparts as $key => $value) {
                        $allstorelogs = StoresLog::where('All_part_id', $value->id)->where('type_id', $part->part_type_id)->select('id')->get();

                        foreach ($stores as $key => $st) {
                            $storeClsName = ucfirst($st->table_name);
                            $storeClsName = 'App\Models\\' . $storeClsName;
                            $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                            if ($st->table_name == "damaged_parts") {
                            } else {

                                $ItemAmmount = $storeClsName::where('part_id', $part->part_id)->whereIn('store_log_id', $allstorelogs)->sum('amount');
                                array_push($storeData, (object) ['name' => $st->name, 'id' => $st->id, 'amount' => ($ItemAmmount) ? $ItemAmmount : 0]);
                            }
                        }
                        $part['stores'] = $storeData;
                    }
                }
            } else if ($part->part_type_id == 6) {
                $part['total'] = AllKit::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->sum('remain_amount');
                $part['part'] = Kit::where('id', $part->part_id)->with([
                    'kit_details' => function ($q) {
                        $q->with('kit_specs')->with('mesure_unit')->get();
                    },
                ])->first();
                $part['type'] = 6;
                $allparts = AllKit::where('part_id', $part->part_id)->where('source_id', $part->source_id)->where('status_id', $part->status_id)->where('quality_id', $part->quality_id)->get();
                if ($allparts) {
                    foreach ($allparts as $key => $value) {
                        $allstorelogs = StoresLog::where('All_part_id', $value->id)->where('type_id', $part->part_type_id)->select('id')->get();

                        foreach ($stores as $key => $st) {
                            $storeClsName = ucfirst($st->table_name);
                            $storeClsName = 'App\Models\\' . $storeClsName;
                            $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                            if ($st->table_name == "damaged_parts") {
                            } else {
                                $ItemAmmount = $storeClsName::where('part_id', $part->part_id)->whereIn('store_log_id', $allstorelogs)->sum('amount');
                                array_push($storeData, (object) ['name' => $st->name, 'id' => $st->id, 'amount' => ($ItemAmmount) ? $ItemAmmount : 0]);
                            }
                        }
                        $part['stores'] = $storeData;
                    }
                }
            }

            // $storeData =[];

            // $allparts = AllPart::where('part_id',$part->part_id)->where('source_id',$part->source_id)->where('status_id',$part->status_id)->where('quality_id',$part->quality_id)->get();
            // foreach ($allparts as $key => $value) {
            //     $allstorelogs = StoresLog::where('All_part_id',$value->id)->where('type_id',1)->select('id')->get();

            //     foreach ($stores as $key => $st) {
            //         $storeClsName=ucfirst($st->table_name);
            //         $storeClsName ='App\Models\\'.$storeClsName;
            //         $storeClsName = str_replace([' ','_','-'],"",$storeClsName);
            //         if($st->table_name == "damaged_parts"){

            //         }else{

            //             array_push($storeData , (object) [ 'name' => $st->name ,'id' => $st->id , 'amount' => $storeClsName::where('part_id',$part->part_id)->whereIn('store_log_id',$allstorelogs)->sum('amount') ]) ;
            //         }
            //     }
            //     $part['stores'] = $storeData;

            // }



        }
        if ($lang === 'ar') {
            # code...
            $paperTitle = "عرض أسعار ";
            $recordName = "العميل";
            $recordValue = $presaleOrders->client->name;
            $recoredId = $presaleOrders->id;
            $recoredUrl = 'printpreSale/ar/' . $recoredId;

            // return $presaleOrders;
            return view('print_ard_asar_ar', compact('presaleOrders', 'paperTitle', 'recordName', 'recordValue', 'recoredId', 'recoredUrl', 'showflag'));
        } elseif ($lang === 'en') {
            $paperTitle = "Offer Price";
            $recordName = "Client";
            $recordValue = $presaleOrders->client->name;
            $recoredId = $presaleOrders->id;
            $recoredUrl = 'printpreSale/en/' . $recoredId;
            // return $presaleOrders;
            return view('print_ard_asar_en', compact('presaleOrders', 'paperTitle', 'recordName', 'recordValue', 'recoredId', 'recoredUrl', 'showflag'));
        }
    }
    public function ConvertPresaleInvoice(Request $request)
    {
        // return $request;

        DB::beginTransaction();
        $logMessage = '';
        $logUser = Auth::user()->id;

        try {

            $cli_id = $request->client_id;
            $discountArr = array_filter($request->notes, function ($x) {
                return $x < 0;
            });
            $overPriceArr = array_filter($request->notes, function ($x) {
                return $x > 0;
            });

            $discount = array_sum($discountArr) * -1;
            $overPrice = array_sum($overPriceArr);
            $actualPrice = array_sum($request->salepricingVlaue);
            //  $total = $actualPrice
            $invoice = new Invoice();

            $invoice->name = Carbon::now();
            $invoice->casher_id = Auth::user()->id;
            $invoice->discount = $discount;
            // $invoice->overprice = $overPrice;
            $invoice->actual_price = $request->subtotal + $request->tax;
            $invoice->client_id  = $cli_id;
            $invoice->company_id = "10";
            $invoice->store_id = $request->storeId;
            $invoice->price_without_tax = $request->subtotal;
            $invoice->tax_amount = $request->tax;
            $invoice->paied = $request->invPaied;
            $invoice->date = Carbon::now();
            $invoice->presale_order_id = $request->presale_id;
            $invoice->save();

            $logMessage .= 'تم اضافة فاتورة بيع رقم ' . $invoice->id . 'من عرض سعر ' . $request->presale_id . '<br/>';
            // return $invoice;

            /// invoice Tax /////////////////
            if (isset($request->taxArr)) {
                for ($i = 0; $i < count($request->taxArr); $i++) {
                    $invTax = new InvoicesTax();
                    $invTax->invoice_id = $invoice->id;
                    $invTax->tax_id = $request->taxArr[$i];
                    $invTax->save();

                    $logMessage .= 'tax added ' . $request->taxArr[$i] . '<br/>';
                }
            }



            //////////////////// Only Part//////////////////////////
            $inv_items = $request->parts;
            $total_item_buy_price = 0;
            for ($i = 0; $i < count($inv_items); $i++) {

                $presalePrice = floatval($request->price[$i]); // السعر في عرض الاسعار
                $priceingvalue = floatval($request->salepricingVlaue[$i]); // التسعيرة
                $discount_temp = 0;
                $overprice_temp = 0;
                if ($presalePrice - $priceingvalue >= 0) {
                    $overprice_temp = $priceingvalue - $presalePrice;
                    if ($overprice_temp < 0) {
                        $overprice_temp = $overprice_temp * -1;
                    }
                } else {
                    $discount_temp = $presalePrice - $priceingvalue;
                    if ($discount_temp < 0) {
                        $discount_temp = $discount_temp * -1;
                    }
                }
                $part_id = $inv_items[$i];
                $source_id = $request->source[$i];
                $status_id = $request->status[$i];
                $quality_id = $request->quality[$i];
                $type_id = $request->type[$i];
                $amount = $request->amount[$i];
                $samllmeasureUnits = $request->samllmeasureUnits[$i];
                $measureUnit = $request->measureUnit[$i];

                $ratiounit = getSmallUnit($measureUnit, $samllmeasureUnits);
                $invoiceItems = new InvoiceItem();
                $invoiceItems->date = Carbon::now();

                $invoiceItems->part_id = $part_id;
                $invoiceItems->amount = $amount *  $ratiounit;
                $invoiceItems->source_id = $source_id;
                $invoiceItems->status_id = $status_id;
                $invoiceItems->quality_id = $quality_id;
                $invoiceItems->part_type_id = $type_id;
                $invoiceItems->invoice_id = $invoice->id;
                $invoiceItems->sale_type = $request->salepricing[$i];
                $invoiceItems->discount = $discount_temp * $ratiounit ;
                $invoiceItems->over_price = $overprice_temp * $ratiounit ;
                $invoiceItems->unit_id = $request->measureUnit[$i];
                $invoiceItems->amout_unit =  $amount; //zyada
                $invoiceItems->save();
                $amount = $amount * $ratiounit;

                $logMessage .= 'item added ' . $part_id . ' with sale_type ' . $request->salepricing[$i] . ' with Discount ' . $discount_temp . ' with Overprice ' . $overprice_temp . ' <br/>';

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

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            $store = Store::where('id', $request->storeId)->get();
                            $store_id = $store[0]->id;
                            $store_name = $store[0]->name;
                            $store_table_name = $store[0]->table_name;

                            $returnRes = DB::table($store_table_name)

                                ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                                ->join($all_part_table, function ($join) use ($store_table_name, $all_part_table) {
                                    $join->on($store_table_name . '.supplier_order_id', '=', $all_part_table . '.order_supplier_id')
                                        ->on('stores_log.All_part_id', '=', $all_part_table . '.id');
                                })
                                ->where('stores_log.store_id', $store_id)
                                ->where($all_part_table . '.source_id', $source_id)
                                ->where($all_part_table . '.status_id', $status_id)
                                ->where($all_part_table . '.quality_id', $quality_id)
                                ->where($store_table_name . '.part_id', $part_id)
                                ->where($store_table_name . '.supplier_order_id', $element->order_supplier_id)
                                ->where($store_table_name . '.type_id', 1)
                                ->decrement($store_table_name . '.amount', $requestAmount0);

                            $logMessage .= 'Store ' . $store_id . ' decrement with ' . $requestAmount0 . ' Part_id - ' . $part_id . ' OSID - ' . $element->order_supplier_id . ' <br/>';

                            // ->get();

                            if ($returnRes) {
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $requestAmount0);
                                $logMessage .= 'Allpart decrement Id ' . $element->id . ' with ' . $requestAmount0 . ' <br/>';

                                break;
                            } else {
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

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            $store = Store::where('id', $request->storeId)->get();
                            $store_id = $store[0]->id;
                            $store_name = $store[0]->name;
                            $store_table_name = $store[0]->table_name;

                            $returnRes =  DB::table($store_table_name)
                                ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                                ->join($all_part_table, function ($join) use ($store_table_name, $all_part_table) {
                                    $join->on($store_table_name . '.supplier_order_id', '=', $all_part_table . '.order_supplier_id')
                                        ->on('stores_log.All_part_id', '=', $all_part_table . '.id');
                                })
                                ->where('stores_log.store_id', $store_id)
                                ->where($all_part_table . '.source_id', $source_id)
                                ->where($all_part_table . '.status_id', $status_id)
                                ->where($all_part_table . '.quality_id', $quality_id)
                                ->where($store_table_name . '.part_id', $part_id)
                                ->where($store_table_name . '.supplier_order_id', $element->order_supplier_id)
                                ->where($store_table_name . '.type_id', 1)
                                ->decrement($store_table_name . '.amount', $element->remain_amount);
                            // ->get();
                            $logMessage .= 'Store ' . $store_id . ' decrement with ' . $element->remain_amount . ' Part_id - ' . $part_id . ' OSID - ' . $element->order_supplier_id . ' <br/>';

                            if ($returnRes) {
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                                $requestAmount0 = $requestAmount0 - $element->remain_amount;
                                $logMessage .= 'Allpart decrement Id ' . $element->id . ' with ' . $element->remain_amount . ' <br/>';
                            } else {
                                continue;
                            }
                            // $requestAmount0 = $requestAmount0 - $element->remain_amount;
                        } else if ($requestAmount0 <= 0) {
                            break;
                        }
                    }


                    ////// remove from all parts ////////

                    // $allpp = AllPart::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->get();
                    // $requestAmount = $amount;
                    // foreach ($allpp as $key => $element) {

                    //     if ($element->remain_amount >= $requestAmount) {
                    //         // decrement amount
                    //         AllPart::where('id', $element->id)->decrement('remain_amount', $requestAmount);
                    //         InvoiceItemsOrderSupplier::create([
                    //             'invoice_item_id' => $invoiceItems->id,
                    //             'order_supplier_id' => $element->order_supplier_id,
                    //             'amount' =>  $requestAmount
                    //         ]);
                    //         break;
                    //     } elseif ($element->remain_amount < $requestAmount) {
                    //         // decrement remain_amount
                    //         AllPart::where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                    //         $requestAmount = $requestAmount - $element->remain_amount;

                    //         InvoiceItemsOrderSupplier::create([
                    //             'invoice_item_id' => $invoiceItems->id,
                    //             'order_supplier_id' => $element->order_supplier_id,
                    //             'amount' =>  $element->remain_amount
                    //         ]);
                    //     } else if ($requestAmount <= 0) {
                    //         break;
                    //     }
                    // }

                    ////// remove from Sections ////////
                    $allsecc = DB::table('store_section')
                        ->where('part_id', $part_id)
                        // ->where('supplier_order_id', $allparts[0]->order_supplier_id)
                        ->where('type_id', 1)
                        ->where('source_id', $source_id)
                        ->where('status_id', $status_id)
                        ->where('amount', '>', 0)
                        ->where('quality_id', $quality_id)
                        ->where('store_id', $request->storeId)
                        ->get();

                    $requestSecAmount = $amount;
                    foreach ($allsecc as $key => $element) {

                        if ($element->amount >= $requestSecAmount) {
                            // decrement amount
                            StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $requestSecAmount
                            ]);
                            $logMessage .= 'Section decrement Id ' . $element->id . ' with ' . $requestSecAmount . ' <br/>';

                            break;
                        } elseif ($element->amount < $requestSecAmount) {
                            // decrement remain_amount
                            StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                            $requestSecAmount = $requestSecAmount - $element->amount;
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $element->amount
                            ]);
                            $logMessage .= 'Section decrement Id ' . $element->id . ' with ' . $element->amount . ' <br/>';
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

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            $store = Store::where('id', $request->storeId)->get();
                            $store_id = $store[0]->id;
                            $store_name = $store[0]->name;
                            $store_table_name = $store[0]->table_name;



                            $returnRes = DB::table($store_table_name)
                                ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                                ->join($all_part_table, function ($join) use ($store_table_name, $all_part_table) {
                                    $join->on($store_table_name . '.supplier_order_id', '=', $all_part_table . '.order_supplier_id')
                                        ->on('stores_log.All_part_id', '=', $all_part_table . '.id');
                                })
                                ->where('stores_log.store_id', $store_id)
                                ->where($all_part_table . '.source_id', $source_id)
                                ->where($all_part_table . '.status_id', $status_id)
                                ->where($all_part_table . '.quality_id', $quality_id)
                                ->where($store_table_name . '.part_id', $part_id)
                                ->where($store_table_name . '.supplier_order_id', $element->order_supplier_id)
                                ->where($store_table_name . '.type_id', 2)
                                ->decrement($store_table_name . '.amount', $requestAmount0);




                            if ($returnRes) {
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $requestAmount0);
                                break;
                            } else {
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

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            $store = Store::where('id', $request->storeId)->get();
                            $store_id = $store[0]->id;
                            $store_name = $store[0]->name;
                            $store_table_name = $store[0]->table_name;


                            $returnRes = DB::table($store_table_name)
                                ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                                ->join($all_part_table, function ($join) use ($store_table_name, $all_part_table) {
                                    $join->on($store_table_name . '.supplier_order_id', '=', $all_part_table . '.order_supplier_id')
                                        ->on('stores_log.All_part_id', '=', $all_part_table . '.id');
                                })
                                ->where('stores_log.store_id', $store_id)
                                ->where($all_part_table . '.source_id', $source_id)
                                ->where($all_part_table . '.status_id', $status_id)
                                ->where($all_part_table . '.quality_id', $quality_id)
                                ->where($store_table_name . '.part_id', $part_id)
                                ->where($store_table_name . '.supplier_order_id', $element->order_supplier_id)
                                ->where($store_table_name . '.type_id', 2)
                                ->decrement($store_table_name . '.amount', $element->remain_amount);


                            if ($returnRes) {
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                                $requestAmount0 = $requestAmount0 - $element->remain_amount;
                            } else {
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
                    //         InvoiceItemsOrderSupplier::create([
                    //             'invoice_item_id' => $invoiceItems->id,
                    //             'order_supplier_id' => $element->order_supplier_id,
                    //             'amount' =>  $requestAmount
                    //         ]);
                    //         break;
                    //     } elseif ($element->remain_amount < $requestAmount) {
                    //         // decrement remain_amount
                    //         AllWheel::where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                    //         $requestAmount = $requestAmount - $element->remain_amount;

                    //         InvoiceItemsOrderSupplier::create([
                    //             'invoice_item_id' => $invoiceItems->id,
                    //             'order_supplier_id' => $element->order_supplier_id,
                    //             'amount' =>  $element->remain_amount
                    //         ]);
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
                        ->where('amount', '>', 0)
                        ->where('quality_id', $quality_id)
                        ->where('store_id', $request->storeId)
                        ->get();

                    $requestSecAmount = $amount;
                    foreach ($allsecc as $key => $element) {

                        if ($element->amount >= $requestSecAmount) {
                            // decrement amount
                            StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $requestSecAmount
                            ]);

                            break;
                        } elseif ($element->amount < $requestSecAmount) {
                            // decrement remain_amount
                            StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                            $requestSecAmount = $requestSecAmount - $element->amount;
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $element->amount
                            ]);
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

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            $store = Store::where('id', $request->storeId)->get();
                            $store_id = $store[0]->id;
                            $store_name = $store[0]->name;
                            $store_table_name = $store[0]->table_name;



                            $returnRes =  DB::table($store_table_name)
                                ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                                ->join($all_part_table, function ($join) use ($store_table_name, $all_part_table) {
                                    $join->on($store_table_name . '.supplier_order_id', '=', $all_part_table . '.order_supplier_id')
                                        ->on('stores_log.All_part_id', '=', $all_part_table . '.id');
                                })
                                ->where('stores_log.store_id', $store_id)
                                ->where($all_part_table . '.source_id', $source_id)
                                ->where($all_part_table . '.status_id', $status_id)
                                ->where($all_part_table . '.quality_id', $quality_id)
                                ->where($store_table_name . '.part_id', $part_id)
                                ->where($store_table_name . '.supplier_order_id', $element->order_supplier_id)
                                ->where($store_table_name . '.type_id', 6)
                                ->decrement($store_table_name . '.amount', $requestAmount0);



                            if ($returnRes) {
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $requestAmount0);
                                break;
                            } else {
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

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            $store = Store::where('id', $request->storeId)->get();
                            $store_id = $store[0]->id;
                            $store_name = $store[0]->name;
                            $store_table_name = $store[0]->table_name;


                            $returnRes = DB::table($store_table_name)
                                ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                                ->join($all_part_table, function ($join) use ($store_table_name, $all_part_table) {
                                    $join->on($store_table_name . '.supplier_order_id', '=', $all_part_table . '.order_supplier_id')
                                        ->on('stores_log.All_part_id', '=', $all_part_table . '.id');
                                })
                                ->where('stores_log.store_id', $store_id)
                                ->where($all_part_table . '.source_id', $source_id)
                                ->where($all_part_table . '.status_id', $status_id)
                                ->where($all_part_table . '.quality_id', $quality_id)
                                ->where($store_table_name . '.part_id', $part_id)
                                ->where($store_table_name . '.supplier_order_id', $element->order_supplier_id)
                                ->where($store_table_name . '.type_id', 6)
                                ->decrement($store_table_name . '.amount', $element->remain_amount);

                            if ($returnRes) {
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                                $requestAmount0 = $requestAmount0 - $element->remain_amount;
                            } else {
                                continue;
                            }
                            // $requestAmount0 = $requestAmount0 - $element->remain_amount;
                        } else if ($requestAmount0 <= 0) {
                            break;
                        }
                    }


                    ////// remove from all parts ////////

                    // $allpp = AllKit::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->get();
                    // $requestAmount = $amount;
                    // foreach ($allpp as $key => $element) {

                    //     if ($element->remain_amount >= $requestAmount) {
                    //         // decrement amount
                    //         AllKit::where('id', $element->id)->decrement('remain_amount', $requestAmount);
                    //         InvoiceItemsOrderSupplier::create([
                    //             'invoice_item_id' => $invoiceItems->id,
                    //             'order_supplier_id' => $element->order_supplier_id,
                    //             'amount' =>  $requestAmount
                    //         ]);
                    //         break;
                    //     } elseif ($element->remain_amount < $requestAmount) {
                    //         // decrement remain_amount
                    //         AllKit::where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                    //         $requestAmount = $requestAmount - $element->remain_amount;

                    //         InvoiceItemsOrderSupplier::create([
                    //             'invoice_item_id' => $invoiceItems->id,
                    //             'order_supplier_id' => $element->order_supplier_id,
                    //             'amount' =>  $element->remain_amount
                    //         ]);
                    //     } else if ($requestAmount <= 0) {
                    //         break;
                    //     }
                    // }

                    ////// remove from Sections ////////
                    $allsecc = DB::table('store_section')
                        ->where('part_id', $part_id)
                        // ->where('supplier_order_id', $allparts[0]->order_supplier_id)
                        ->where('type_id', 6)
                        ->where('source_id', $source_id)
                        ->where('status_id', $status_id)
                        ->where('amount', '>', 0)
                        ->where('quality_id', $quality_id)
                        ->where('store_id', $request->storeId)
                        ->get();

                    $requestSecAmount = $amount;
                    foreach ($allsecc as $key => $element) {

                        if ($element->amount >= $requestSecAmount) {
                            // decrement amount
                            StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $requestSecAmount
                            ]);

                            break;
                        } elseif ($element->amount < $requestSecAmount) {
                            // decrement remain_amount
                            StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                            $requestSecAmount = $requestSecAmount - $element->amount;
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $element->amount
                            ]);
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

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            $store = Store::where('id', $request->storeId)->get();
                            $store_id = $store[0]->id;
                            $store_name = $store[0]->name;
                            $store_table_name = $store[0]->table_name;



                            $returnRes =  DB::table($store_table_name)
                                ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                                ->join($all_part_table, function ($join) use ($store_table_name, $all_part_table) {
                                    $join->on($store_table_name . '.supplier_order_id', '=', $all_part_table . '.order_supplier_id')
                                        ->on('stores_log.All_part_id', '=', $all_part_table . '.id');
                                })
                                ->where('stores_log.store_id', $store_id)
                                ->where($all_part_table . '.source_id', $source_id)
                                ->where($all_part_table . '.status_id', $status_id)
                                ->where($all_part_table . '.quality_id', $quality_id)
                                ->where($store_table_name . '.part_id', $part_id)
                                ->where($store_table_name . '.supplier_order_id', $element->order_supplier_id)
                                ->where($store_table_name . '.type_id', 3)
                                ->decrement($store_table_name . '.amount', $requestAmount0);


                            if ($returnRes) {
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $requestAmount0);
                                break;
                            } else {
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

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            $store = Store::where('id', $request->storeId)->get();
                            $store_id = $store[0]->id;
                            $store_name = $store[0]->name;
                            $store_table_name = $store[0]->table_name;



                            $returnRes = DB::table($store_table_name)
                                ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                                ->join($all_part_table, function ($join) use ($store_table_name, $all_part_table) {
                                    $join->on($store_table_name . '.supplier_order_id', '=', $all_part_table . '.order_supplier_id')
                                        ->on('stores_log.All_part_id', '=', $all_part_table . '.id');
                                })
                                ->where('stores_log.store_id', $store_id)
                                ->where($all_part_table . '.source_id', $source_id)
                                ->where($all_part_table . '.status_id', $status_id)
                                ->where($all_part_table . '.quality_id', $quality_id)
                                ->where($store_table_name . '.part_id', $part_id)
                                ->where($store_table_name . '.supplier_order_id', $element->order_supplier_id)
                                ->where($store_table_name . '.type_id', 3)
                                ->decrement($store_table_name . '.amount', $element->remain_amount);

                            if ($returnRes) {
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                                $requestAmount0 = $requestAmount0 - $element->remain_amount;
                            } else {
                                continue;
                            }
                            // $requestAmount0 = $requestAmount0 - $element->remain_amount;
                        } else if ($requestAmount0 <= 0) {
                            break;
                        }
                    }


                    ////// remove from all parts ////////

                    // $allpp = AllTractor::where('remain_amount', '>', 0)->where('part_id', $part_id)->where('source_id', $source_id)->where('status_id', $status_id)->where('quality_id', $quality_id)->orderBy('id', 'ASC')->get();
                    // $requestAmount = $amount;
                    // foreach ($allpp as $key => $element) {

                    //     if ($element->remain_amount >= $requestAmount) {
                    //         // decrement amount
                    //         AllTractor::where('id', $element->id)->decrement('remain_amount', $requestAmount);
                    //         InvoiceItemsOrderSupplier::create([
                    //             'invoice_item_id' => $invoiceItems->id,
                    //             'order_supplier_id' => $element->order_supplier_id,
                    //             'amount' =>  $requestAmount
                    //         ]);
                    //         break;
                    //     } elseif ($element->remain_amount < $requestAmount) {
                    //         // decrement remain_amount
                    //         AllTractor::where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                    //         $requestAmount = $requestAmount - $element->remain_amount;

                    //         InvoiceItemsOrderSupplier::create([
                    //             'invoice_item_id' => $invoiceItems->id,
                    //             'order_supplier_id' => $element->order_supplier_id,
                    //             'amount' =>  $element->remain_amount
                    //         ]);
                    //     } else if ($requestAmount <= 0) {
                    //         break;
                    //     }
                    // }

                    ////// remove from Sections ////////
                    $allsecc = DB::table('store_section')
                        ->where('part_id', $part_id)
                        // ->where('supplier_order_id', $allparts[0]->order_supplier_id)
                        ->where('type_id', 3)
                        ->where('source_id', $source_id)
                        ->where('status_id', $status_id)
                        ->where('quality_id', $quality_id)
                        ->where('store_id', $request->storeId)
                        ->where('amount', '>', 0)
                        ->get();

                    $requestSecAmount = $amount;
                    foreach ($allsecc as $key => $element) {

                        if ($element->amount >= $requestSecAmount) {
                            // decrement amount
                            StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $requestSecAmount
                            ]);

                            break;
                        } elseif ($element->amount < $requestSecAmount) {
                            // decrement remain_amount
                            StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                            $requestSecAmount = $requestSecAmount - $element->amount;
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $element->amount
                            ]);
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

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            $store = Store::where('id', $request->storeId)->get();
                            $store_id = $store[0]->id;
                            $store_name = $store[0]->name;
                            $store_table_name = $store[0]->table_name;


                            $returnRes = DB::table($store_table_name)
                                ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                                ->join($all_part_table, function ($join) use ($store_table_name, $all_part_table) {
                                    $join->on($store_table_name . '.supplier_order_id', '=', $all_part_table . '.order_supplier_id')
                                        ->on('stores_log.All_part_id', '=', $all_part_table . '.id');
                                })
                                ->where('stores_log.store_id', $store_id)
                                ->where($all_part_table . '.source_id', $source_id)
                                ->where($all_part_table . '.status_id', $status_id)
                                ->where($all_part_table . '.quality_id', $quality_id)
                                ->where($store_table_name . '.part_id', $part_id)
                                ->where($store_table_name . '.supplier_order_id', $element->order_supplier_id)
                                ->where($store_table_name . '.type_id', 4)
                                ->decrement($store_table_name . '.amount', $requestAmount0);

                            if ($returnRes) {
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $requestAmount0);
                                break;
                            } else {
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

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            $store = Store::where('id', $request->storeId)->get();
                            $store_id = $store[0]->id;
                            $store_name = $store[0]->name;
                            $store_table_name = $store[0]->table_name;



                            $returnRes = DB::table($store_table_name)
                                ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                                ->join($all_part_table, function ($join) use ($store_table_name, $all_part_table) {
                                    $join->on($store_table_name . '.supplier_order_id', '=', $all_part_table . '.order_supplier_id')
                                        ->on('stores_log.All_part_id', '=', $all_part_table . '.id');
                                })
                                ->where('stores_log.store_id', $store_id)
                                ->where($all_part_table . '.source_id', $source_id)
                                ->where($all_part_table . '.status_id', $status_id)
                                ->where($all_part_table . '.quality_id', $quality_id)
                                ->where($store_table_name . '.part_id', $part_id)
                                ->where($store_table_name . '.supplier_order_id', $element->order_supplier_id)
                                ->where($store_table_name . '.type_id', 4)
                                ->decrement($store_table_name . '.amount', $element->remain_amount);

                            if ($returnRes) {
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                                $requestAmount0 = $requestAmount0 - $element->remain_amount;
                            } else {
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
                    //         InvoiceItemsOrderSupplier::create([
                    //             'invoice_item_id' => $invoiceItems->id,
                    //             'order_supplier_id' => $element->order_supplier_id,
                    //             'amount' =>  $requestAmount
                    //         ]);
                    //         break;
                    //     } elseif ($element->remain_amount < $requestAmount) {
                    //         // decrement remain_amount
                    //         AllClark::where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                    //         $requestAmount = $requestAmount - $element->remain_amount;

                    //         InvoiceItemsOrderSupplier::create([
                    //             'invoice_item_id' => $invoiceItems->id,
                    //             'order_supplier_id' => $element->order_supplier_id,
                    //             'amount' =>  $element->remain_amount
                    //         ]);
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
                        ->where('amount', '>', 0)
                        ->where('store_id', $request->storeId)
                        ->get();

                    $requestSecAmount = $amount;
                    foreach ($allsecc as $key => $element) {

                        if ($element->amount >= $requestSecAmount) {
                            // decrement amount
                            StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $requestSecAmount
                            ]);

                            break;
                        } elseif ($element->amount < $requestSecAmount) {
                            // decrement remain_amount
                            StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                            $requestSecAmount = $requestSecAmount - $element->amount;
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $element->amount
                            ]);
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

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            $store = Store::where('id', $request->storeId)->get();
                            $store_id = $store[0]->id;
                            $store_name = $store[0]->name;
                            $store_table_name = $store[0]->table_name;


                            $returnRes = DB::table($store_table_name)
                                ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                                ->join($all_part_table, function ($join) use ($store_table_name, $all_part_table) {
                                    $join->on($store_table_name . '.supplier_order_id', '=', $all_part_table . '.order_supplier_id')
                                        ->on('stores_log.All_part_id', '=', $all_part_table . '.id');
                                })
                                ->where('stores_log.store_id', $store_id)
                                ->where($all_part_table . '.source_id', $source_id)
                                ->where($all_part_table . '.status_id', $status_id)
                                ->where($all_part_table . '.quality_id', $quality_id)
                                ->where($store_table_name . '.part_id', $part_id)
                                ->where($store_table_name . '.supplier_order_id', $element->order_supplier_id)
                                ->where($store_table_name . '.type_id', 5)
                                ->decrement($store_table_name . '.amount', $requestAmount0);


                            if ($returnRes) {
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $requestAmount0);
                                break;
                            } else {
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

                            $total_item_buy_price += $amount * ($element->buy_price * $Ac_all_currency_types[0]->currencies[0]->value);
                            $store = Store::where('id', $request->storeId)->get();
                            $store_id = $store[0]->id;
                            $store_name = $store[0]->name;
                            $store_table_name = $store[0]->table_name;


                            $returnRes = DB::table($store_table_name)
                                ->join('stores_log', $store_table_name . '.store_log_id', '=', 'stores_log.id')
                                ->join($all_part_table, function ($join) use ($store_table_name, $all_part_table) {
                                    $join->on($store_table_name . '.supplier_order_id', '=', $all_part_table . '.order_supplier_id')
                                        ->on('stores_log.All_part_id', '=', $all_part_table . '.id');
                                })
                                ->where('stores_log.store_id', $store_id)
                                ->where($all_part_table . '.source_id', $source_id)
                                ->where($all_part_table . '.status_id', $status_id)
                                ->where($all_part_table . '.quality_id', $quality_id)
                                ->where($store_table_name . '.part_id', $part_id)
                                ->where($store_table_name . '.supplier_order_id', $element->order_supplier_id)
                                ->where($store_table_name . '.type_id', 5)
                                ->decrement($store_table_name . '.amount', $element->remain_amount);

                            if ($returnRes) {
                                DB::table($all_part_table)->where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                                $requestAmount0 = $requestAmount0 - $element->remain_amount;
                            } else {
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
                    //         InvoiceItemsOrderSupplier::create([
                    //             'invoice_item_id' => $invoiceItems->id,
                    //             'order_supplier_id' => $element->order_supplier_id,
                    //             'amount' =>  $requestAmount
                    //         ]);
                    //         break;
                    //     } elseif ($element->remain_amount < $requestAmount) {
                    //         // decrement remain_amount
                    //         AllEquip::where('id', $element->id)->decrement('remain_amount', $element->remain_amount);
                    //         $requestAmount = $requestAmount - $element->remain_amount;

                    //         InvoiceItemsOrderSupplier::create([
                    //             'invoice_item_id' => $invoiceItems->id,
                    //             'order_supplier_id' => $element->order_supplier_id,
                    //             'amount' =>  $element->remain_amount
                    //         ]);
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
                        ->where('amount', '>', 0)
                        ->where('store_id', $request->storeId)
                        ->get();

                    $requestSecAmount = $amount;
                    foreach ($allsecc as $key => $element) {

                        if ($element->amount >= $requestSecAmount) {
                            // decrement amount
                            StoreSection::where('id', $element->id)->decrement('amount', $requestSecAmount);
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $requestSecAmount
                            ]);

                            break;
                        } elseif ($element->amount < $requestSecAmount) {
                            // decrement remain_amount
                            StoreSection::where('id', $element->id)->decrement('amount', $element->amount);
                            $requestSecAmount = $requestSecAmount - $element->amount;
                            InvoiceItemsSection::create([
                                'invoice_item_id' => $invoiceItems->id,
                                'section_id' => $element->section_id,
                                'amount' =>  $element->amount
                            ]);
                        } else if ($requestSecAmount <= 0) {
                            break;
                        }
                    }
                }
            }

            PresaleOrder::where('id', $request->presale_id)->update([
                'flag' => 3
            ]);
            // return $invoice;
            // 00000000000000000000000000000000000000000000000000000000000
            /////////////////////adel///////////
            $clientData = Client::where('id', $cli_id)->first();





            $store = Store::where('id', $request->storeId)->first();

            $storees = Store::where('safe_accountant_number', $request->payment)->first();

            if ($storees) {
                $total = MoneySafe::where('store_id', $request->storeId)
                    ->latest()
                    ->first();
                if (isset($total)) {
                    MoneySafe::create([
                        'notes' => 'مبلغ فاتورة بيع رقم' . ' ' . $invoice->id . ' ' . ' من عميل ' . ' ' . $clientData->name . ' ' . $store->name,

                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->invPaied,
                        'total' => $total->total + $request->invPaied,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => $request->storeId,
                        'note_id' => 17,
                    ]);
                } else {
                    MoneySafe::create([
                        'notes' => 'مبلغ فاتورة بيع رقم' . ' ' . $invoice->id . ' ' . ' من عميل ' . ' ' . $clientData->name . ' ' . $store->name,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->invPaied,
                        'total' => $request->invPaied,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => $request->storeId,
                        'note_id' => 17,
                    ]);
                }
            } else {
                $storees = BankType::where('accountant_number', $request->payment)->first();
                $total = BankSafeMoney::where('bank_type_id', $storees->id)
                    ->latest()
                    ->first();

                if (isset($total)) {
                    BankSafeMoney::create([
                        'notes' => 'مبلغ فاتورة بيع رقم' . ' ' . $invoice->id . ' ' . ' من عميل ' . ' ' . $clientData->name . ' ' . $store->name,

                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->invPaied,
                        'total' => $total->total + $request->invPaied,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => null,
                        'money_currency' => $request->invPaied,
                        'currency_id' => 400,
                        'bank_type_id' => $storees->id

                    ]);
                } else {
                    BankSafeMoney::create([
                        'notes' => 'مبلغ فاتورة بيع رقم' . ' ' . $invoice->id . ' ' . ' من عميل ' . ' ' . $clientData->name . ' ' . $store->name,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->invPaied,
                        'total' => $request->invPaied,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => null,
                        'money_currency' => $request->invPaied,
                        'currency_id' => 400,
                        'bank_type_id' => $storees->id
                    ]);
                }
            }

            $quaditems = [];
            $automaicQayd = new QaydController();
            array_push($quaditems, (object) ['acountant_id' => 37, 'dayin' => 0, 'madin' => $total_item_buy_price]); // المبيعات مدين
            array_push($quaditems, (object) ['acountant_id' => $store->accountant_number, 'dayin' => $total_item_buy_price, 'madin' => 0]); // المخزن دائن
            $date = Carbon::now();
            $type = null;
            $notes = 'فاتورة بيع رقم' . $invoice->id;
            $qyadidss = $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
            Qayditem::where('qaydid', $qyadidss)->update([
                'invoiceid' => $invoice->id,
                'flag' => 1
            ]);

            // /********************************** Automaic qayd*******************************************************
            // $request->tax الضريبة
            // $request->taxArr انواع الضرائب
            // $request->subtotal السعر قبل الضريبة
            // $request->invPaied; المدفوع
            // $request->total الاجمالي بعد الضريبة
            // $request->payment

            $quaditems = [];
            $automaicQayd = new QaydController();
            $invoiceac = 0;
            $taxac = 0;
            $binvoiceac = 0;

            if (floatval($request->tax) > 0) {
                foreach ($request->taxArr as $key => $value) {
                    if ($value == "4") {
                        $taxac = $request->subtotal * 14 / 100; // الضريبة
                        // $invoiceac = floatval($request->subtotal) + $taxac; //الاجمالي بعد الضريبة
                        // $binvoiceac =round($invoiceac - $taxac);
                        array_push($quaditems, (object) ['acountant_id' => 2636, 'madin' => 0, 'dayin' => $taxac]); // الضريبة دائن

                    } else if ($value == "5") {
                        $taxac = $request->subtotal * -1 / 100; // الضريبة
                        // $invoiceac = floatval($request->subtotal) + $taxac; //الاجمالي بعد الضريبة
                        // $binvoiceac =round($invoiceac - $taxac);
                        array_push($quaditems, (object) ['acountant_id' => 2637, 'dayin' => $taxac, 'madin' => 0]); // الضريبة مدين
                    } else {
                    }
                    $invoiceac +=  $taxac;
                    $binvoiceac += round($invoiceac - $taxac);
                }
            }


            if ($request->invPaied == $request->total) // البيع كاش
            {

                array_push($quaditems, (object) ['acountant_id' => 4511, 'dayin' => $request->invPaied, 'madin' => 0]); // المبيعات دائن
                array_push($quaditems, (object) ['acountant_id' => $request->payment, 'dayin' => 0, 'madin' => $request->invPaied]); // الخزنة مدين



            } else // البيع أجل
            {
                array_push($quaditems, (object) ['acountant_id' => 4511, 'dayin' => floatval($request->total) - $invoiceac, 'madin' => 0]); // المبيعات دائن

                $acClientNo = Client::where('id', $cli_id)->first()->accountant_number;

                array_push($quaditems, (object) ['acountant_id' => $acClientNo, 'dayin' => 0, 'madin' => (floatval($request->total) - $request->invPaied) - $request->invDiscount]); // العميل مدين
                // if( $request->store_id != 0  ) // خزنة
                // {
                //     if($request->invPaied > 0){
                //         array_push ( $quaditems , (object) [ 'acountant_id'=> 1812 , 'dayin'=> 0 , 'madin'=> $request->invPaied ] ); // الخزنة مدين
                //     }

                // }else // بنك
                // {
                //      if($request->invPaied > 0){
                //          array_push ( $quaditems , (object) [ 'acountant_id'=> 1821 , 'dayin'=> 0 , 'madin'=> $request->invPaied ] ); // بنك مدين
                //      }

                // }
                if ($request->invPaied > 0) {
                    array_push($quaditems, (object) ['acountant_id' => $request->payment, 'dayin' => 0, 'madin' => $request->invPaied]); // بنك مدين
                }
            }



            //   return $taxac;

            // هنضيف الضريبة

            if ($request->invDiscount > 0) {
                array_push($quaditems, (object) ['acountant_id' => 4513, 'madin' =>  $request->invDiscount, 'dayin' => 0]); // مسموحات مبيعات دائن
            }


            $date = Carbon::now();
            $type = null;
            $notes = 'فاتورة بيع رقم' . $invoice->id;
            $qyadidss = $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);

            Qayditem::where('qaydid', $qyadidss)->update([
                'invoiceid' => $invoice->id,
                'flag' => 1
            ]);

            $log = new LogController();
            $log->newLog($logUser, $logMessage);

            DB::commit();
            // /*****************************************************************************************
            return redirect()->to('printInvoice/' . $invoice->id);
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash("error", $e->getMessage());
            return $e;
            // return redirect()->back();
        }
    }

    public function partDetailsArd(Request $request)
    {
        // return $request;

        // $storesx = Store::all();
        $storesx = Store::where('table_name', '<>', 'damaged_parts')->get();

        $query = '';
        if ($request->typeId == 1) {
            $query = AllPart::where('part_id', $request->PartID)
                ->selectRaw('SUM(all_parts.amount) as amount,SUM(all_parts.remain_amount) as remain_amount, all_parts.part_id, all_parts.source_id, all_parts.status_id, all_parts.quality_id ')
                ->groupBy('all_parts.part_id', 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id')
                ->with([
                    'part.part_numbers',
                    'source',
                    'status',
                    'part_quality',
                    'pricing.sale_type',
                    'order_supplier.buy_transaction',
                    'part.sub_group.group',
                    'part.part_details.part_spec',
                    'part.part_models.series.model.brand',
                    'part.part_images',
                    'part.smallunit',
                    'part.bigunit',
                    'part.getbigunitval',
                    'part.getsmallunit.unit'
                ])
                ->get();

            if (count($query) > 0) {
                foreach ($query as $key => $part) {
                    $partId = $part->part_id;
                    $sourceId = $part->source_id;
                    $statusId = $part->status_id;
                    $qualityId = $part->quality_id;
                    $type = 1;
                    $part['ratiounit']=getSmallUnit($part->part->big_unit,$part->part->small_unit);

                    $storesData = [];
                    foreach ($storesx as $key => $item) {
                        $sd = $item->storepartCount = DB::table($item->table_name)
                            ->select('*')
                            ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                            ->join('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                            ->where('all_parts.part_id', '=', $partId)
                            ->where('all_parts.source_id', '=', $sourceId)
                            ->where('all_parts.status_id', '=', $statusId)
                            ->where('all_parts.quality_id', '=', $qualityId)
                            ->where('stores_log.status', '=', 3)
                            // ->orWhere('stores_log.status', '=', 1)
                            ->where($item->table_name . '.type_id', '=', $type)
                            ->sum($item->table_name . '.amount');
                        array_push($storesData, (object)['totalAmount' => $sd, 'store' => $item]);
                    }

                    $part['stores'] = $storesData;
                }
            } else {
                return Part::where('id', $request->PartID)->with('all_parts')->with('part_details.part_spec')->with('part_details.mesure_unit')->get();
            }
        } else if ($request->typeId == 2) {

            $query = AllWheel::where('part_id', $request->PartID)
                ->selectRaw('SUM(all_wheels.amount) as amount,SUM(all_wheels.remain_amount) as remain_amount, all_wheels.part_id, all_wheels.source_id, all_wheels.status_id, all_wheels.quality_id ')
                ->groupBy('all_wheels.part_id', 'all_wheels.source_id', 'all_wheels.status_id', 'all_wheels.quality_id')
                ->with([
                    'wheel.wheel_details.wheel_spec',
                    'source',
                    'status',
                    'part_quality',
                    'pricing.sale_type',
                    'order_supplier.buy_transaction'
                ])
                ->get();

            if (count($query) > 0) {
                foreach ($query as $key => $part) {
                    $partId = $part->part_id;
                    $sourceId = $part->source_id;
                    $statusId = $part->status_id;
                    $qualityId = $part->quality_id;
                    $type = 2;

                    $storesData = [];
                    foreach ($storesx as $key => $item) {
                        $sd = $item->storepartCount = DB::table($item->table_name)
                            ->select('*')
                            ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                            ->join('all_wheels', 'stores_log.All_part_id', '=', 'all_wheels.id')
                            ->where('all_wheels.part_id', '=', $partId)
                            ->where('all_wheels.source_id', '=', $sourceId)
                            ->where('all_wheels.status_id', '=', $statusId)
                            ->where('all_wheels.quality_id', '=', $qualityId)
                            ->where('stores_log.status', '=', 3)
                            // ->orWhere('stores_log.status', '=', 1)
                            ->where($item->table_name . '.type_id', '=', $type)
                            ->sum($item->table_name . '.amount');
                        array_push($storesData, (object)['totalAmount' => $sd, 'store' => $item]);
                    }

                    $part['stores'] = $storesData;
                }
            } else {

                return Wheel::where('id', $request->PartID)->with('all_wheels')->with('wheel_dimension')->with('wheel_details.wheel_spec')->with('wheel_details.mesure_unit')->get();
            }
        } else if ($request->typeId == 3) {
            $query = AllTractor::where('part_id', $request->PartID)
                ->selectRaw('SUM(all_tractors.amount) as amount,SUM(all_tractors.remain_amount) as remain_amount, all_tractors.part_id, all_tractors.source_id, all_tractors.status_id, all_tractors.quality_id ')
                ->groupBy('all_tractors.part_id', 'all_tractors.source_id', 'all_tractors.status_id', 'all_tractors.quality_id')
                ->with([
                    'tractor.tractor_details.tractor_spec',
                    'source',
                    'status',
                    'part_quality',
                    'pricing.sale_type',
                    'order_supplier.buy_transaction'
                ])
                ->get();




            if (count($query) > 0) {
                foreach ($query as $key => $part) {
                    $partId = $part->part_id;
                    $sourceId = $part->source_id;
                    $statusId = $part->status_id;
                    $qualityId = $part->quality_id;
                    $type = 3;

                    $storesData = [];
                    foreach ($storesx as $key => $item) {
                        $sd = $item->storepartCount = DB::table($item->table_name)
                            ->select('*')
                            ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                            ->join('all_tractors', 'stores_log.All_part_id', '=', 'all_tractors.id')
                            ->where('all_tractors.part_id', '=', $partId)
                            ->where('all_tractors.source_id', '=', $sourceId)
                            ->where('all_tractors.status_id', '=', $statusId)
                            ->where('all_tractors.quality_id', '=', $qualityId)
                            ->where('stores_log.status', '=', 3)
                            // ->orWhere('stores_log.status', '=', 1)
                            ->where($item->table_name . '.type_id', '=', $type)
                            ->sum($item->table_name . '.amount');
                        array_push($storesData, (object)['totalAmount' => $sd, 'store' => $item]);
                    }

                    $part['stores'] = $storesData;
                }
            } else {


                return Tractor::where('id', $request->PartID)->with('all_tractors')->with('tractor_details.tractor_spec')->with('tractor_details.mesure_unit')->get();
            }
        } else if ($request->typeId == 4) {
            $query = AllClark::where('part_id', $request->PartID)
                ->selectRaw('SUM(all_clarks.amount) as amount,SUM(all_clarks.remain_amount) as remain_amount, all_clarks.part_id, all_clarks.source_id, all_clarks.status_id, all_clarks.quality_id ')
                ->groupBy('all_clarks.part_id', 'all_clarks.source_id', 'all_clarks.status_id', 'all_clarks.quality_id')
                ->with([
                    'clark.clark_details.clark_spec',
                    'source',
                    'status',
                    'part_quality',
                    'pricing.sale_type',
                    'order_supplier.buy_transaction'
                ])
                ->get();

            if (count($query) > 0) {
                foreach ($query as $key => $part) {
                    $partId = $part->part_id;
                    $sourceId = $part->source_id;
                    $statusId = $part->status_id;
                    $qualityId = $part->quality_id;
                    $type = 4;

                    $storesData = [];
                    foreach ($storesx as $key => $item) {
                        $sd = $item->storepartCount = DB::table($item->table_name)
                            ->select('*')
                            ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                            ->join('all_clarks', 'stores_log.All_part_id', '=', 'all_clarks.id')
                            ->where('all_clarks.part_id', '=', $partId)
                            ->where('all_clarks.source_id', '=', $sourceId)
                            ->where('all_clarks.status_id', '=', $statusId)
                            ->where('all_clarks.quality_id', '=', $qualityId)
                            ->where('stores_log.status', '=', 3)
                            // ->orWhere('stores_log.status', '=', 1)
                            ->where($item->table_name . '.type_id', '=', $type)
                            ->sum($item->table_name . '.amount');
                        array_push($storesData, (object)['totalAmount' => $sd, 'store' => $item]);
                    }

                    $part['stores'] = $storesData;
                }
            } else {

                return Clark::where('id', $request->PartID)->with('all_clarks')->with('clark_details.clark_spec')->with('clark_details.mesure_unit')->get();
            }
        } else if ($request->typeId == 5) {
            $query = AllEquip::where('part_id', $request->PartID)
                ->selectRaw('SUM(all_equips.amount) as amount,SUM(all_equips.remain_amount) as remain_amount, all_equips.part_id, all_equips.source_id, all_equips.status_id, all_equips.quality_id ')
                ->groupBy('all_equips.part_id', 'all_equips.source_id', 'all_equips.status_id', 'all_equips.quality_id')
                ->with([
                    'equip.equip_details.equip_spec',
                    'source',
                    'status',
                    'part_quality',
                    'pricing.sale_type',
                    'order_supplier.buy_transaction'
                ])
                ->get();

            if (count($query) > 0) {
                foreach ($query as $key => $part) {
                    $partId = $part->part_id;
                    $sourceId = $part->source_id;
                    $statusId = $part->status_id;
                    $qualityId = $part->quality_id;
                    $type = 5;

                    $storesData = [];
                    foreach ($storesx as $key => $item) {
                        $sd = $item->storepartCount = DB::table($item->table_name)
                            ->select('*')
                            ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                            ->join('all_equips', 'stores_log.All_part_id', '=', 'all_equips.id')
                            ->where('all_equips.part_id', '=', $partId)
                            ->where('all_equips.source_id', '=', $sourceId)
                            ->where('all_equips.status_id', '=', $statusId)
                            ->where('all_equips.quality_id', '=', $qualityId)
                            ->where('stores_log.status', '=', 3)
                            // ->orWhere('stores_log.status', '=', 1)
                            ->where($item->table_name . '.type_id', '=', $type)
                            ->sum($item->table_name . '.amount');
                        array_push($storesData, (object)['totalAmount' => $sd, 'store' => $item]);
                    }

                    $part['stores'] = $storesData;
                }
            } else {


                return Equip::where('id', $request->PartID)->with('all_equips')->with('equip_details.equip_spec')->with('equip_details.mesure_unit')->get();
            }
        } else if ($request->typeId == 6) {
            $query = AllKit::where('part_id', $request->PartID)
                ->selectRaw('SUM(all_kits.amount) as amount,SUM(all_kits.remain_amount) as remain_amount, all_kits.part_id, all_kits.source_id, all_kits.status_id, all_kits.quality_id ')
                ->groupBy('all_kits.part_id', 'all_kits.source_id', 'all_kits.status_id', 'all_kits.quality_id')
                ->with([
                    'kit.kit_details.kit_specs',
                    'source',
                    'status',
                    'part_quality',
                    'pricing.sale_type',
                    'order_supplier.buy_transaction'
                ])
                ->get();

            if (count($query) > 0) {
                foreach ($query as $key => $part) {
                    $partId = $part->part_id;
                    $sourceId = $part->source_id;
                    $statusId = $part->status_id;
                    $qualityId = $part->quality_id;
                    $type = 6;
                    $part['ratiounit']=getSmallUnit($part->part->big_unit,$part->part->small_unit);

                    $storesData = [];
                    foreach ($storesx as $key => $item) {
                        $sd = $item->storepartCount = DB::table($item->table_name)
                            ->select('*')
                            ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                            ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                            ->where('all_kits.part_id', '=', $partId)
                            ->where('all_kits.source_id', '=', $sourceId)
                            ->where('all_kits.status_id', '=', $statusId)
                            ->where('all_kits.quality_id', '=', $qualityId)
                            ->where('stores_log.status', '=', 3)
                            // ->orWhere('stores_log.status', '=', 1)
                            ->where($item->table_name . '.type_id', '=', $type)
                            ->sum($item->table_name . '.amount');
                        array_push($storesData, (object)['totalAmount' => $sd, 'store' => $item]);
                    }

                    $part['stores'] = $storesData;
                }
            } else {


                return Kit::where('id', $request->PartID)->with('all_kits')->with('kit_numbers')->with('kit_details.kit_specs')->with('kit_details.mesure_unit')->get();
            }
        }


        return $query;
    }

    public function presaleAdminConfirm(Request $request)
    {
        // return $request;
        $res = PresaleOrder::where('id', $request->order_id)->update([
            'admin_confirm' => $request->status
        ]);
        return $res;
    }
}