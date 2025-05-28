<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Qayd;
use App\Models\Qayditem;
use App\Models\Qaydtype;
use Illuminate\Support\Facades\DB;
use App\Models\BranchTree;
use Carbon\Carbon;
// use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;
class QaydController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $qayds=Qayd::with(['qaydtype'])->get();
        return view("qayd.index",compact("qayds"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("qayd.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd((count($request->request)-3)/4);=((count($request->request)-3)/4)
        // dd(count($request->request));
        //  dd($request->request);
        $qayd = Qayd::create([

            'qaydtypeid' =>   $request->qaydType,
            'date' => $request->dateQayd
        ]);
        $lastID=$qayd->id;
            for ($i=0; $i <count($request->note) ; $i++) {
                // print("branch".$i);
                $qayditem= Qayditem::create([

                    'qaydid' =>  $lastID,
                    'branchid' => $request->branch[$i],
                    'dayin'=> $request->dayn[$i],
                    'madin'=> $request->maden[$i],
                    'topic'=> $request->note[$i],
                    'date' => $request->dateQayd
                ]);
            }
            if ($qayditem) {
                return view('qayd.create')->with('success', 'تم الحفظ بنجاح');
            }
            return view('qayd.create')->with('error', 'لم يتم الحفظ ');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $qayddetails=Qayditem::where('qaydid',$id)->with(['qayd', 'branch_tree'])->orderBy('madin','DESC')->get();
        // dd($qayddetails->toArray());
        return view("qayd.show",compact("qayddetails"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function search()
    {
        // $qayds=Qayd::with(['qaydtype'])->get();
        $accounts=[];
        $summadin=0;
        $sumdayin=0;
        return view("qayd.search",compact("accounts",'sumdayin','summadin'));
    }


    public function getallAccount(Request $request)
    {
        // $qayds=Qayd::with(['qaydtype'])->get();
        // qayditem.branchid = 29 And date >='2022-12-02' And date <= '2022-12-18'
        $accounts=Qayditem::where('branchid',$request->branchs)->
                  where('date','>=',$request->dateQaydfrom)->
                  where('date',"<=",$request->dateQaydto)->
                  with(['qayd', 'branch_tree'])->
                  get();
        $summadin=Qayditem::where('branchid',$request->branchs)->
                  where('date','>=',$request->dateQaydfrom)->
                  where('date',"<=",$request->dateQaydto)->
                  with(['qayd', 'branch_tree'])->
                  sum('madin');
        $sumdayin=Qayditem::where('branchid',$request->branchs)->
                  where('date','>=',$request->dateQaydfrom)->
                  where('date',"<=",$request->dateQaydto)->
                  with(['qayd', 'branch_tree'])->
                  sum('dayin');
        return view("qayd.search",compact("accounts","sumdayin","summadin"));
        // return $sum;
    }

    public function searchaccount()
    {
        // $qayds=Qayd::with(['qaydtype'])->get();
        $accounts=[];
        $summadin=0;
        $sumdayin=0;
        return view("qayd.searchaccount",compact("accounts",'sumdayin','summadin'));
    }
    public function accountstatement(Request $request)
    {
        // $qayds=Qayd::with(['qaydtype'])->get();
        // $accounts=[];
        // $summadin=0;
        // $sumdayin=0;
        // return view("qayd.searchaccount",compact("accounts",'sumdayin','summadin'));
        $from='"'.$request->dateQaydfrom .'"';

        $to='"'.$request->dateQaydto.'"';
        $branchid=$request->branchs;
        $accounts="";
        if(!isset($request->dateQaydfrom )&& !isset($request->dateQaydto )){
             $accounts=Qayditem::where('branchid',$request->branchs)->with(['qayd', 'branch_tree'])->get();
        }elseif(isset($request->dateQaydfrom ) && !isset($request->dateQaydto )){
             $accounts=Qayditem::where('branchid',$request->branchs)->
        where('date','>=',$request->dateQaydfrom)->
        with(['qayd', 'branch_tree'])->get();
        }elseif(!isset($request->dateQaydfrom ) && isset($request->dateQaydto )){
             $accounts=Qayditem::where('branchid',$request->branchs)->
        where('date','<=',$request->dateQaydto)->
        with(['qayd', 'branch_tree'])->get();
        }elseif(isset($request->dateQaydfrom ) && isset($request->dateQaydto )){
             $accounts=Qayditem::where('branchid',$request->branchs)->
        where('date','>=',$request->dateQaydfrom)->
        where('date',"<=",$request->dateQaydto)->
        with(['qayd', 'branch_tree'])->get();
        }

        // return $accounts;
        return view("qayd.searchaccount",compact("accounts","from","to","branchid"));




    }
    public function trialbalance()
    {
        $results=[];
        return view("qayd.trialbalance",compact("results"));

    }
    public function gettrialbalance(Request $request)
    {
        $results=Qayditem::where('date','>=',$request->dateQaydfrom)
                        ->where('date','<=',$request->dateQaydto)
                        ->with(['branch_tree'])
                        ->selectRaw("SUM(qayditem.dayin) as tdayin")
                        ->selectRaw("SUM(qayditem.madin) as tmadin")
                        ->selectRaw("qayditem.branchid")

                        ->groupBy("qayditem.branchid")
                        ->get();

        return view("qayd.trialbalance",compact("results"));


        // return $results;


    }
    public function test(){
        $results=Qayditem::where('date','>=','2022-01-21')
                        ->where('date','<=',now()->toDateString('Y-m-d') )
                        ->with(['branch_tree'])
                        ->selectRaw("SUM(qayditem.dayin) as tdayin")
                        ->selectRaw("SUM(qayditem.madin) as tmadin")
                        ->selectRaw("qayditem.branchid")

                        ->groupBy("qayditem.branchid")
                        ->get();

                        BranchTree::query()->update(['notes' => 0]);
        foreach($results as $result ) {
            $r=$result->tmadin -  $result->tdayin;
            if($r<0){
                $r*=-1;
                BranchTree::where('id', $result->branchid)->update(['notes' => $r]);
            }
            BranchTree::where('id', $result->branchid)->update(['notes' => $r]);

        }
        // return    $results ;
    }




    public function AutomaticQayd($quaditems  , $date ,$type, $notes ){
       try {
            if(!$type){
                $type = 6;
            }
    
    
            $qayd_id = Qayd::create([
                'qaydtypeid' =>   $type,
                'accountnumber' =>   null,
                'note' =>   $notes,
                'date' => $date
            ])->id;
    
    
                foreach($quaditems as $item ){
                    $qayditemFrom= Qayditem::create([
                        'qaydid' =>  $qayd_id,
                        'branchid' => BranchTree::where('accountant_number', intval($item->acountant_id))->first()->id ,
                        'dayin'=> $item->dayin,
                        'madin'=> $item->madin,
                        'topic'=> $notes,
                        'date' => Carbon::now()
                    ]);
                }
       

            DB::commit();
            return $qayd_id;
       } catch (\Exception $e) {
            //throw $th;
            DB::rollback();
            session()->flash("error", $e);
            return $e;
        }
       
    }

    public function inv_qayd_check(Request $request ){

        if ($request->ajax()) {
            $qaydCollection = Qayd::with('qayditems.branch_tree')->get();

            $qaydCollection->each(function ($qayd) {
                $qayd->qayditems->each(function ($item) {
                    if ($item->flag == 0) {
                        $item->load('buy_invoices');
                    } elseif ($item->flag == 1) {
                        $item->load('sell_invoices');
                    }
                });
            });

            return FacadesDataTables::of($qaydCollection)
                    ->addIndexColumn()
                     ->addColumn('id', function ($row) {
                        $btn = '-';
                        if ($row->id) {
                            $btn = $row->id;
                        }
                        return $btn;
                    })
                    ->addColumn('date', function ($row) {
                        $btn = '-';
                        if ($row->date) {
                            $btn = $row->date;
                        }
                        return $btn;
                    })
                    ->addColumn('note', function ($row) {
                        $btn = '-';
                        if (count($row->qayditems) > 0) {
                            $btn =  $row->qayditems[0]->topic;
                        }
                        return $btn;
                    })

                    ->rawColumns(['date'])
                    ->setTotalRecords(20)
                    ->make(true);


        }

        // return view('qaydReview',compact('qaydCollection'));
        // return $qaydCollection;
    }

    public function inv_qayd(){
        return view('qaydReview');
    }

    public function editqayd($qayd_id){
        // return $qayd_id;
        $qayd = Qayd::where('id',$qayd_id)->with('qayditems.branch_tree')->first();
        if($qayd){
            $qayd['invoice_id'] = $qayd->qayditems[0]->invoiceid;
            $qayd['invoice_flag'] = $qayd->qayditems[0]->flag;
        }else{
            $qayd['invoice_id'] = null;
            $qayd['invoice_flag'] = null;
        }
        $qayd_type = Qaydtype::all();
        return view('editqayd',compact('qayd','qayd_type'));
    }

    public function save_editqayd(Request $request){
        // return $request;
        DB::beginTransaction();
        try {

            Qayd::where('id',$request->qaydId)->update([
                'qaydtypeid'=>$request->qaydType,
                'date'=> Carbon::now()
            ]);

            Qayditem::where('qaydid',$request->qaydId)->delete();

            for ($i=0; $i < count($request->branch) ; $i++) {

                Qayditem::create([
                    'qaydid' => $request->qaydId,
                    'branchid' => $request->branch[$i],
                    'dayin' => $request->dayn[$i],
                    'madin' => $request->maden[$i],
                    'topic' => $request->note[$i],
                    'invoiceid' => $request->invoice_id,
                    'date' => Carbon::now(),
                    'flag' => $request->invoice_flag,
                ]);
            }
            DB::commit();

            session()->flash("success", "تم التعديل بنجاح");
            return redirect()->to('editqayd/' . $request->qaydId);

    } catch (\Exception $e) {
        DB::rollback();
        session()->flash("error", "لا يمكن  التعديل");
        return redirect()->back();
    }

    }
     public function print_qayd($qid){
         $qayd = Qayd::where('id',$qid)->with('qayditems.branch_tree')->first();

        $paperTitle = "  مستند قيد ";
        $recordName = "الشركة";
        $recordValue = 'الأمـــل';
        $recoredId = $qayd->id;
        $recoredUrl = 'print_qayd' . $recoredId;

        return view('printQaud',compact('qayd','paperTitle','recordName','recordValue','recoredId','recoredUrl'));


    }
}
