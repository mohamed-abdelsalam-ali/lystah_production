<?php

namespace App\Http\Controllers;

use App\Models\AllKit;
use App\Models\AllKitPartItem;
use App\Models\StoreSection;
use App\Models\AllPart;
use App\Models\BuyTransaction;
use App\Models\Kit;
use App\Models\KitPart;
use App\Models\OrderSupplier;
use App\Models\Part;
use App\Models\PartNumber;
use App\Models\AllKitPartItemSection;

use App\Models\PartQuality;
use App\Models\Replyorder;
use App\Models\Source;
use App\Models\Status;
use App\Models\Store;
use App\Models\StoresLog;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Object_;



use Yajra\DataTables\Facades\DataTables as FacadesDataTables;
class kitCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
     $allkits = AllKit::where('remain_amount','>',0)->groupBy(['part_id','source_id','status_id','quality_id'])->select(DB::raw("SUM(`remain_amount`) AS `amount`"),'part_id','status_id','quality_id','source_id')->with('kit')->with('sectionswithoutorder.store_structure')->get();
    $allkits2 = AllKit::where('remain_amount','>',0)->with('kit')->with('sectionswithoutorder.store_structure')->get();

     $allsupplier = Supplier::all();
     $kits = Kit::with('kit_parts')->get();
     $allparts = AllPart::where('remain_amount','>',0)->groupBy(['part_id','status_id','quality_id','source_id'])->select(DB::raw("SUM(`remain_amount`) AS `amount`"), 'part_id')->get();
     $allsources = Source::all();
     $allstatus = Status::all();
     $allquality = PartQuality::all();
       return view('kitCollectionIndex',compact('allkits','allkits2','allparts','allsupplier','kits','allsources','allstatus','allquality'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function kitInfo(Request $request)
    {
        //
        // return $request->kit_id;
        $kitPart = KitPart::where('kit_id',$request->kit_id)->with('part')->with('source')->with('status')->with('part_quality')->get();
        return $kitPartAvailable = $this->calculateKits($request->kit_id);

    }

    public function calculateKits($kit_id){
        $responseArr=[];
        $allparts=[];

          $kitParts = KitPart::where('kit_id',$kit_id)->with('part')->with('source')->with('status')->with('part_quality')->get();
          if(count($kitParts) > 0){
            $responseArr['component'] = $kitParts;
          }else{
            $responseArr['component'] = [];
          }
          // search allpart to found parts availability
          $minArr=[];
          $availableSumArr=[];
          $min=0;
        foreach ($kitParts as $key => $part) {
            // return $part;
            $availableSum = 0;
            $allpartx =AllPart::
            where('part_id','=',$part->part_id)
            ->groupBy(['part_id','status_id','quality_id','source_id'])
            ->select(DB::raw("SUM(`remain_amount`) AS `amount`"), 'part_id','status_id','quality_id','source_id')
            ->havingRaw('SUM(`remain_amount`) >= ?', [$part->amount])
            // ->where('amount','>=',$part->amount)
            ->with('part')
            ->with('source')
            ->with('status')
            ->with('part_quality')
            ->get();



            foreach ($allpartx as $key => $xx) {
                # code...

                if(floor($xx->amount / $part->amount) > 0  ){
                    $xx['available']= floor($xx->amount / $part->amount);
                    $availableSum +=floor($xx->amount / $part->amount);
                    array_push($minArr,floor($xx->amount / $part->amount));
                }
            }

            array_push($availableSumArr ,$availableSum );
            if(count($allpartx)>0){
                array_push($allparts,$allpartx);
            }

        }
        // return $allparts;
       if(count($kitParts) == count($allparts)){
            $responseArr['parts'] = $allparts;
            //$min = min($minArr);
            //  $min = count($minArr)>0 ? min($minArr) : 0;
             $min = count($availableSumArr)>0 ? min($availableSumArr) : 0;



            $responseArr['message'] = "يمكن تجميع كيت من المخازن";
       }else{
            // return "لا يوجد قطع كافية لعمل كيت";
            $responseArr['message'] = "لا يوجد قطع كافية لعمل كيت";
            $responseArr['parts'] = [];

       }

       $stores = Store::all();

       foreach ($stores as $key1 => $store) {
            $store_model = ucfirst($store->table_name);
            if ($store_model == 'Damaged_parts') {
                $store_model = 'DamagedPart';
            }
            $partIn=[];
            foreach ($kitParts as $key => $part) {
                // return $part;
                $entity_tbl = 'App\\Models\\' . $store_model;
                 $partsIneachStore= $entity_tbl::
                leftjoin('stores_log', $store->table_name . '.store_log_id', '=', 'stores_log.id')
                ->leftjoin('all_parts', 'stores_log.All_part_id', '=', 'all_parts.id')
                ->where('stores_log.type_id', '=', '1')
                ->where('all_parts.remain_amount', '>', '0')
                ->where('all_parts.part_id','=',$part->part_id)
                ->groupBy(['all_parts.part_id','all_parts.status_id','all_parts.quality_id','all_parts.source_id'])
                ->select(DB::raw("SUM($store->table_name.amount) AS `amount`"),'all_parts.part_id','status_id','quality_id','source_id')->get();

                foreach ($partsIneachStore as $key => $row) {
                    $row['part']=Part::find($row->part_id);
                    $row['source']=Source::find($row->source_id);
                    $row['status']=Status::find($row->status_id);
                    $row['quality']=PartQuality::find($row->quality_id);

                    $row['price']= Replyorder::where('part_id',$row->part_id)->where('part_type_id',1)->where('source_id',$row->source_id)->where('status_id',$row->status_id)->where('quality_id',$row->quality_id)->with('order_supplier.currency_type.currencies')->orderBy('id','DESC')->first();
                }
                if(count($partsIneachStore) > 0){
                 array_push($partIn , $partsIneachStore );
                }
            }
            $store->partIn = $partIn;


       }

       $responseArr['min']=$min;
       $responseArr['Stores']=$stores;
        //    $responseArr['Stores']="لسه";
        // return $kitParts[0]['kit_id'];
        $kit_data=AllKit::where("part_id",'=',$kitParts[0]['kit_id'])->get();
        if (count($kit_data) > 0) {
            $responseArr['kitInstores'] =  $this->PartInStoresCount($kit_data[0]->part_id,$kit_data[0]->source_id,$kit_data[0]->status_id,$kit_data[0]->quality_id,6);
        }else{
            $responseArr['kitInstores'] =[];
        }




       return $responseArr;


    }
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
    public function PartInStoresCount($partId , $sourceId,$statusId,$qualityId,$type){

        // get all stores
        $stores = Store::all();

        // if($type == 1){
            return $stores->each(function ($item) use ($partId , $sourceId,$statusId,$qualityId,$type){
                // $storeClass = 'App\Models\\'.ucfirst($item->table_name);
                $item->storepart = DB::table($item->table_name)
                ->select($item->table_name.'.*')
                ->join('stores_log',$item->table_name.'.store_log_id','=','stores_log.id')
                ->join('all_kits','stores_log.All_part_id','=','all_kits.id')
                ->where('all_kits.part_id','=',$partId)
                // ->where('all_kits.source_id','=',$sourceId)
                // ->where('all_kits.status_id','=',$statusId)
                // ->where('all_kits.quality_id','=',$qualityId)
                ->where('stores_log.status','=',3)
                ->get();

                $item->storepartCount = DB::table($item->table_name)
                ->select('*')
                ->join('stores_log',$item->table_name.'.store_log_id','=','stores_log.id')
                ->join('all_kits','stores_log.All_part_id','=','all_kits.id')
                ->where('all_kits.part_id','=',$partId)
                // ->where('all_kits.source_id','=',$sourceId)
                // ->where('all_kits.status_id','=',$statusId)
                // ->where('all_kits.quality_id','=',$qualityId)
                ->where('stores_log.status','=',3)
                ->where($item->table_name.'.type_id','=',$type)
                ->sum($item->table_name.'.amount');


            });
        // }



    }

    public function save_kit_collection(Request $request ){
        // dd($request) ;

        DB::beginTransaction();
        try {
        $store = Store::find($request->storeId);

        $store_model=ucfirst($store->table_name);
        if($store_model=="Damaged_parts"){
            $store_model="damagedPart";
        }
        $entity ='App\\Models\\'.$store_model;
        $sec_history=[];
        foreach ($request->kitItems as $key => $value) {

             $part_id = $value['part_id'];
             $source_id = $value['source_id'];
             $status_id = $value['status_id'];
             $quality_id = $value['quality_id'];
             $amount = $value['amount'];



            $allpartss =  AllPart::where('all_parts.part_id','=',$part_id)
            ->where('all_parts.source_id','=',$source_id)
            ->where('all_parts.status_id','=',$status_id)
            ->where('all_parts.quality_id','=',$quality_id)
            ->whereHas('store_log', function ($query) use($store) {
                // $query->where('store_log.store_id', '=', $store->id);
                $query->where('stores_log.store_id', '=', $store->id);
            })
            ->get();

            $sectionss =  StoreSection::where('part_id','=',$part_id)
            ->where('source_id','=',$source_id)
            ->where('status_id','=',$status_id)
            ->where('quality_id','=',$quality_id)
             ->where('type_id','=',1)
             ->where('store_id','=',$request->storeId)
            ->get();
            


            //  $currentRow->decrement('remain_amount', intval($amount) * intval($request->neededKit) );   /// allpart

            $totalAmount = intval($amount) * intval($request->neededKit);
            foreach ($allpartss as $key => $value) {
                if($value->remain_amount >= $totalAmount ){
                    AllPart::where('id',$value->id)->decrement('remain_amount', floatval($totalAmount) );
                    
                    // $currentRow->decrement('remain_amount', $totalAmount );
                    break;
                }else if($totalAmount > $value->remain_amount){

                    AllPart::where('id',$value->id)->decrement('remain_amount',  floatval($value->remain_amount));
                        $totalAmount = $totalAmount - $value->remain_amount;
                }else if($totalAmount <= 0){
                        break;
                }



            }

            $totalAmount = intval($amount) * intval($request->neededKit);
            foreach ($sectionss as $key => $value) {
                if($value->amount >= $totalAmount){
                       if(floatval($totalAmount) > 0){
                    array_push($sec_history, (object) ['section_id' => $value->section_id, 'amount' => floatval($totalAmount) ,'sec_data' => $value]);/////////salam
                    }
                    StoreSection::where('id',$value->id)->decrement('amount', floatval($totalAmount) );
                    
                    // $currentRow->decrement('remain_amount', $totalAmount );
                    break;
                }else if($totalAmount > $value->amount){
                    if(floatval($value->amount) > 0){
                   array_push($sec_history, (object) ['section_id' => $value->section_id, 'amount' =>  floatval($value->amount),'sec_data' => $value]);//////salam

                    }

                    StoreSection::where('id',$value->id)->decrement('amount',  floatval($value->amount));
                        $totalAmount = $totalAmount - $value->amount;
                }else if($totalAmount <= 0){
                        break;
                }



            }


            $currentRow = DB::table($store->table_name)
            ->select($store->table_name.'.*')
            ->join('stores_log',$store->table_name.'.store_log_id','=','stores_log.id')
            ->join('all_parts','stores_log.All_part_id','=','all_parts.id')
            ->where('all_parts.part_id','=',$part_id)
            ->where('all_parts.source_id','=',$source_id)
            ->where('all_parts.status_id','=',$status_id)
            ->where('all_parts.quality_id','=',$quality_id)
             ->where($store->table_name.'.type_id','=',1)
            ->where('stores_log.status','=',3)->get();



            $totalAmountx = intval($amount) * intval($request->neededKit);
            foreach ($currentRow as $key => $value) {
                if($value->amount >= $totalAmountx){
                    DB::table($store->table_name)->where('id',$value->id)->decrement('amount', $totalAmountx );
                    break;
                }else if($totalAmountx > $value->amount){

                    DB::table($store->table_name)->where('id',$value->id)->decrement('amount',  $value->amount); 
                        $totalAmountx = $totalAmountx - $value->amount;
                }else if($totalAmountx < 0){
                        break;
                }



            }


            // $currentRow->decrement($store->table_name.'.amount',intval($amount) * intval($request->neededKit) ); // store

        }

        $buytrans_id = BuyTransaction::create([
            'date' => Carbon::now(),
            'company_id' => 10,
            'final' => 3,
            'creation_date' => Carbon::now()
        ])->id;


        $orderSupplier_id = OrderSupplier::create([
            'transaction_id' => $buytrans_id,
            'supplier_id' => $request->supplier,
            'deliver_date' => Carbon::now(),
            'currency_id' => 400,
            'total_price' => $request->buyPrice,
             'user_id' => Auth::user()->id,
            'container_size' =>0 ,
            'confirmation_date' => Carbon::now()
        ])->id;
        
        Replyorder::create([
            'order_supplier_id' => $orderSupplier_id,
            'part_id' => $request->kit_id,
            'price' => $request->buyPrice,
            'amount' => $request->neededKit,
            'source_id' => $request->source,
            'status_id' => $request->status,
            'creation_date' => Carbon::now(),
            'quality_id' => $request->quality,
            'part_type_id' => 6
         ]);
         
         
        $allkit_id = AllKit::create([
            'part_id' => $request->kit_id,
            'order_supplier_id' => $orderSupplier_id, // الشركة
            'amount' => $request->neededKit,
            'remain_amount' => $request->neededKit,
            'source_id' => $request->source, // محلي
            'status_id' => $request->status, // محلي
            'buy_price' => $request->buyPrice, // شراء - بيع
            'insertion_date' => Carbon::now(),
            'quality_id' => $request->quality, // محلي
            'lastupdate' => Carbon::now()
        ])->id;
         foreach ( $sec_history as $key => $section) {
            $allkitpartItem = AllKitPartItemSection::create([
                'all_kit_id' => $allkit_id ,
                'part_id' =>$section->sec_data['part_id'],
                'source_id' => $section->sec_data['source_id'],
                'status_id' => $section->sec_data['status_id'],
                'quality_id' => $section->sec_data['quality_id'],
                'order_sup_id' =>$section->sec_data['order_supplier_id'], //part ordersupplier
                'kit_order_sup_id' =>$orderSupplier_id, //kit ordersupplier
                'amount' => $section->amount,
                'section_id'=> $section->section_id,
                'store_id'=> $section->sec_data['store_id']

            ])->id;
        }
        foreach ($request->kitItems as $key => $value) {
             $part_id = $value['part_id'];
             $source_id = $value['source_id'];
             $status_id = $value['status_id'];
             $quality_id = $value['quality_id'];
             $amount = $value['amount'];

             $allkitpartItem = AllKitPartItem::create([
            'all_kit_id' => $allkit_id ,
		    'part_id' =>$value['part_id'],
    		'source_id' => $value['source_id'],
    		'status_id' => $value['status_id'],
    		'quality_id' => $value['quality_id'],
    		'order_sup_id' =>$orderSupplier_id,
    		'note' => "تجميع كيت داخلى",
    		'amount' => $value['amount']
            
        ])->id;      
        }
      
        
        // store_log
        $storelog_id = StoresLog::create([
            'All_part_id' => $allkit_id,
            'store_action_id' => 3,
            'store_id' => $request->storeId,
            'amount' => $request->neededKit,
            'date' => Carbon::now(),
            'user_id' => Auth::user()->id,
            'status' => 3,
            'type_id' => 6
        ])->id;



        // store
            $entity::create([
                'part_id' => $request->kit_id ,
                'amount' => $request->neededKit,
                'supplier_order_id' => $orderSupplier_id,
                'notes' => 'تجميع كيت داخلي',
                'type_id' => 6,
                'store_log_id' => $storelog_id,
                'date' => Carbon::now()
            ]);

          
        $redirectUrl = route('printkitpartsection', [
            'id' => $orderSupplier_id
           
        ]);

        // Return the JSON response
        DB::commit();
        return response()->json(['redirect_url' => $redirectUrl]);
        
            // return true;
        } catch (\Exception $e) {
            //throw $th;
            DB::rollback();
            session()->flash("error", $e->getMessage());
            return false;
        }


    }
    
   
    public function retriveAllKits($id){
        return AllKit::where('id',$id)->with('kit_part')->get();
    }

    public function availablekitinstore(Request $request){
        if ($request->ajax()) {
            $kits = Kit::with([
                'kit_parts.all_parts.part',
                'kit_parts.part',
                'kit_parts.all_parts.source',
                'kit_parts.all_parts.status',
                'kit_parts.all_parts.part_quality',
                 'kit_parts.all_parts.sections.store_structure',
                'kit_parts' => function ($query) {
                    $query->withSum('all_parts', 'remain_amount');
                }
            ])->get();

            return FacadesDataTables::of($kits)
                ->addColumn('itemname', function($row) {
                    return $row->name;
                })
                ->addColumn('itemavialable', function($row) {
                    if ($row->kit_parts->count() > 0) {
                        $partsAvil = [];
                        foreach ($row->kit_parts as $kitPart) {
                            $partsAvil[] = $kitPart->all_parts_sum_remain_amount / $kitPart->amount;
                        }
                        return floor(min($partsAvil));
                    } else {
                        return '0';
                    }
                })
                ->addColumn('itemparts', function($row) {
                    $results = '';
                     if ($row->kit_parts->count() > 0) {
                        foreach ($row->kit_parts as $kitPart) {

                            $results .= '<div class="row mb-3">';
                            $results .= '<div class="col-lg-2"> مطلوب : ' .  $kitPart->amount . '</div>';
                            $results .= '<div class="col-lg-2"><a href="/partDetails/1/'.$kitPart->part->id.'">  ' . $kitPart->part->name  . '</a></div>';
                            $results .= '<div class="col-lg-8">';

                            foreach ($kitPart->all_parts as $allPart) {
                                $results .= '<div class="row mb-2">';
                                $results .= '<div class="col-lg-2">المنشأ : ' . $allPart->source->name_arabic . '</div>';
                                $results .= '<div class="col-lg-2"> الحالة : ' . $allPart->status->name . '</div>';
                                $results .= '<div class="col-lg-3">الجودة : ' . $allPart->part_quality->name . '</div>';
                                $results .= '<div class="col-lg-2"> موجود :' . $allPart->amount . '</div>';
                                if ($allPart->sections->count() > 0) {
                                    $sectionNames = $allPart->sections->pluck('store_structure.name')->implode(', ');

                                    $results .= '<div class="col-lg-2"> فى : ' . $sectionNames . '</div>';
                                }
                                $results .= '</div>'; // Close row

                            }

                            $results .= '</div>'; // Close col-lg-8
                            $results .= '</div>'; // Close row
                            $results .= '<hr style="6px dashed lightseagreen">';

                        }
                    } else {
                        $results .= '<div class="row"><div class="col"> لا يوجد أصناف علي الكيت</div></div>';
                    }
                    return $results;
                })
                ->rawColumns(['itemname','itemavialable','itemparts'])
                ->make(true);
        }
    }

    public function availbleKits(){
        return view('avialbleKits');
    }
    public function getsections($all_p){
        if($all_p){

            foreach ($all_p as $key => $value) {
                $section_data=[];
                if($value->sectionswithoutorder){
                    foreach ($$value->sections as $key => $valuex) {
                        array_push($section_data,$valuex->store_structure->name );
                    }

                }else{
                   return $section_data;
                }

            }
            return $section_data;
        }else{
            return " ";
        }
    }
    
        public function fakkit($kit_id,$sourceId,$statusId,$qualityId){
        // return $kit_id;
        // 00000000000000000000000000
        $store = Store::all();
          $kitData= AllKit::where('part_id',$kit_id)
        ->where('source_id',$sourceId)
        ->where('status_id',$statusId)
        ->where('quality_id',$qualityId)
        ->with('store_log.all_kits.kit.store_sections.store_structure.store')
        ->with('source')
        ->with('status')
        ->with('part_quality')
        ->with('sectionswithoutorder.store_structure.store')
        ->with('parts_in_allkit_item.order_supplier.buy_transaction')
        ->with('parts_in_allkit_item.source')
        ->with('parts_in_allkit_item.status')
        ->with('parts_in_allkit_item.part_quality')
        ->with('parts_in_allkit_item.part.all_parts')
        ->with('parts_in_allkit_item.part.store_sections.store_structure.store')
        ->with('parts_in_allkit_item.sectionswithoutorder.store_structure.store')
        ->with('parts_in_allkit_item.order_supplier.replyorders')
        ->get();

        $storeSections = StoreSection::select('section_id', 'store_id')
        ->selectRaw('SUM(amount) as total_amount')
        ->selectRaw('source_id')
        ->selectRaw('status_id')
        ->selectRaw('quality_id')
        ->where('type_id', 6)
        ->where('part_id', $kit_id)
        ->where('source_id', $sourceId)
        ->where('status_id', $statusId)
        ->where('quality_id', $qualityId)
        ->groupBy('section_id','store_id','source_id','status_id','quality_id')
        ->with('store_structure.store')
        ->with('source')
        ->with('status')
        ->with('part_quality')
        ->get();



        // return $groupedStoreSections;
             $data = $store->each(function ($item) use ($kit_id, $sourceId, $statusId, $qualityId) {
                // $storeClass = 'App\Models\\'.ucfirst($item->table_name);
                $item->storepart = DB::table($item->table_name)
                    ->select($item->table_name . '.*','all_kits.*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                    ->where('all_kits.part_id', '=', $kit_id)
                    ->where('all_kits.remain_amount', '>', 0)
                    ->where('all_kits.source_id', '=', $sourceId)
                    ->where('all_kits.status_id', '=', $statusId)
                    ->where('all_kits.quality_id', '=', $qualityId)
                    ->where($item->table_name . '.amount', '>', 0)
                    ->get();

                $item->storepartCount = DB::table($item->table_name)
                    ->select('*')
                    ->join('stores_log', $item->table_name . '.store_log_id', '=', 'stores_log.id')
                    ->join('all_kits', 'stores_log.All_part_id', '=', 'all_kits.id')
                    ->where('all_kits.part_id', '=', $kit_id)
                    ->where('all_kits.source_id', '=', $sourceId)
                    ->where('all_kits.status_id', '=', $statusId)
                    ->where('all_kits.quality_id', '=', $qualityId)

                    ->where($item->table_name . '.type_id', '=', 6)
                    ->sum($item->table_name . '.amount');
                });
          $data->filter(function ($q) {
            return isset($q['storepartCount']) && $q['storepartCount'] > 0;
        });

        if(count($kitData)>0){
            return view('fakKit',compact('store','kitData','data','storeSections'));
        }else {
            return view('errorPage');
        }

    }

    public function savefakkit(Request $request){

        $kit_id = $request->kitId;
        $source_id = $request->sourceId;
        $status_id = $request->statusId;
        $quality_id = $request->qualityId;
        $kit_amount = $request->fak_amount;
        // $request->fak_amount  الكمية المطلوب فكها من الكيت
        if(isset($request->secId)){
            for ( $i=0; $i < count($request->secId); $i++) {
                /// decrement section by kit_amount where type = 6**************************************
              $secraw=  StoreSection::where('type_id',6)->where('part_id',$kit_id)->where('source_id',$source_id)->where('section_id',$request->secId[$i])
                ->where('status_id',$status_id)->where('quality_id',$quality_id)
                ->where('amount','>','0')
                ->get();



            $requestAmountc =  $request->fak_amount[$i];
            foreach ($secraw as $key => $element) {

            if($element->amount >= $requestAmountc){

                StoreSection::where('id',$element->id)
                ->where('amount','>','0')
                // ->get();
                ->decrement('amount',$requestAmountc);

                break;
            }elseif($element->amount < $requestAmountc){
                StoreSection::where('id',$element->id)
                ->where('amount','>','0')
                ->decrement('remain_amount',$element->amount);



                $requestAmountc = $requestAmountc - $element->amount;

            }else if($requestAmountc <= 0){
                break;
            }

        }

                // ->decrement('amount',$request->fak_amount[$i]);
            }
        }


        // return Store1::where('part_id',$kit_id)->get();
      $kitData =  AllKit::where('part_id',$kit_id)
       ->where('quality_id',$quality_id)
       ->where('source_id',$source_id)
       ->where('status_id',$status_id)
       ->with('store_log.store')
       ->with('sectionswithoutorder')
       ->with('kit_part')
       ->with('parts_in_allkit_item.sectionswithoutorder.store_structure')->get();

       /// decrement allkit by kit_amount done
        $allkitOrderSupplierids = [];
        $requestAmount =  array_sum($kit_amount);
        foreach ($kitData as $key => $element) {
            array_push($allkitOrderSupplierids,$element->order_supplier_id);
            if($element->remain_amount >= $requestAmount){

                AllKit::where('id',$element->id)
                // ->get();
                ->decrement('remain_amount',$requestAmount);

                break;
            }elseif($element->remain_amount < $requestAmount){
                AllKit::where('id',$element->id)
                // ->get();
                ->decrement('remain_amount',$element->remain_amount);



                $requestAmount = $requestAmount - $element->remain_amount;

            }else if($requestAmount <= 0){
                break;
            }

        }





        // decrement store by kit_amount where type=6*************************************
        for ( $i=0; $i < count($request->storeId); $i++) {
            $store_table= Store::where('id',$request->storeId[$i])->get('table_name');
                $store_table_name=$store_table[0]['table_name'];
            $store_model=ucfirst($store_table_name);
            if($store_model=="Damaged_parts"){
                $store_model="DamagedPart";
            }
            $entity ='App\\Models\\'.$store_model;


             $x= $entity::where('part_id',$kit_id)
                ->where('type_id',6)
                ->whereIn('supplier_order_id',$allkitOrderSupplierids)
                // ->where('supplier_order_id',$request->supplier_order_id[$i])
                ->get();
                // ->decrement('amount',$request->fak_amount[$i]);
            $requestAmountx = $request->fak_amount[$i];
            foreach ($x as $key => $storerow) {
                if($storerow->amount >= $requestAmountx){

                    $entity::where('id',$storerow->id)
                    ->where('type_id',6)
                    ->decrement('amount',$requestAmountx);
                    break;
                }elseif($storerow->amount < $requestAmountx){
                            // increment by amount - remain_amount
                            $entity::where('id',$storerow->id)
                            ->where('type_id',6)
                            ->decrement('amount',$storerow->amount);
                    $requestAmountx = $requestAmountx - $storerow->amount;
                }else if($requestAmountx <= 0){
                    break;
                }
            }
        }
       // increment all_part by kit_amount*part_needed for kit  أعمل فاتورة جديدة بالأصناف ولا ازود الكميات والباقى

        $Kit_Amount_total = array_sum($kit_amount);
        // return $kitData;
        $incrementalRow=[];



            foreach ($kitData[0]->parts_in_allkit_item as $key => $kit_part) {
                $incremental_value = $kit_part->amount * $Kit_Amount_total;

                  $kit_part_all_part = AllPart::where('part_id',$kit_part->part_id)->where('source_id',$kit_part->source_id)
                ->where('status_id',$kit_part->status_id)->where('quality_id',$kit_part->quality_id)->get();


                $requestAmount = $incremental_value;
                foreach ($kit_part_all_part as $key => $k_all_part) {
                    if($k_all_part->amount >= $requestAmount + $k_all_part->remain_amount){
                                // increment by incremental value
                        $rowData = AllPart::where('id',$k_all_part->id)->with('store_log')
                        // ->get();
                        ->increment('remain_amount', $requestAmount);
                        array_push($incrementalRow, ['orderSupplier' => $k_all_part->order_supplier_id , 'part_id' => $k_all_part->part_id , 'amount' => $incremental_value ]);

                        break;
                    }elseif($k_all_part->amount < $requestAmount + $k_all_part->remain_amount){
                                // increment by amount - remain_amount
                        $rowData = AllPart::where('id',$k_all_part->id)->with('store_log')
                        ->increment('remain_amount', $k_all_part->amount - $k_all_part->remain_amount);
                        // ->get();
                        array_push($incrementalRow, ['orderSupplier' => $k_all_part->order_supplier_id , 'part_id' => $k_all_part->part_id , 'amount' => $k_all_part->amount - $k_all_part->remain_amount ]);

                        // array_push($incrementalRow, $rowData);
                        $requestAmount = $requestAmount - $k_all_part->amount - $k_all_part->remain_amount;
                    }else if($requestAmount <= 0){
                        break;
                    }
                }
            }


        // return $incrementalRow;
        for ( $i=0; $i < count($request->storeId); $i++) {
                $store_table= Store::where('id',$request->storeId[$i])->get('table_name');
                $store_table_name=$store_table[0]['table_name'];
                $store_model=ucfirst($store_table_name);
                if($store_model=="Damaged_parts"){
                    $store_model="DamagedPart";
                }

                // loop incrementalRow in each store



                $entity ='App\\Models\\'.$store_model;
                foreach ($incrementalRow as $key => $allpartRow) {
                    $kitpartsss = KitPart::where('kit_id',$kit_id)->where('part_id',$allpartRow['part_id'])->get();
                    $entity::where('part_id',$allpartRow['part_id'])
                    ->where('type_id',1)
                    ->where('supplier_order_id',$allpartRow['orderSupplier'])
                    // ->get();
                    ->increment('amount', $request->fak_amount[$i] * $kitpartsss[0]->amount);


                }
                // incremnt


        }
        return redirect()->back();
    } 
    
    public function printkitpartsection($order_sup_id){
         $kitData= AllKitPartItemSection::where('kit_order_sup_id',$order_sup_id)
           
            ->with('all_kit.kit')
            ->with('part')
            ->with('source')
            ->with('status')
            ->with('part_quality')
            ->with('store_structure')
            ->with('store')
            ->get();
            if(count($kitData) > 0){
                return view('printKitCollection',compact('kitData'));

            }else{
                 $responseArr['message'] = "لا يوجد بيانات";
                  return redirect()->back();
            }

    }
}
