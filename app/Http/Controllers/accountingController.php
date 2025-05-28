<?php

namespace App\Http\Controllers;

use App\Models\BranchTree;
use App\Models\BrandType;
use App\Models\Qayd;
use App\Models\Qayditem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Http\Controllers\QaydController;

class accountingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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



    public function createQuid($account_from , $account_to , $date ,$type, $notes ){
        /// create quid

        // acountant from : { acountant_id , dayin , madin}
        // acountant to : { acountant_id , dayin , madin}

         // quied items - dayin
        //             - madin

        if(!$type){
            $type = 6;
        }


        $qayd_id = Qayd::create([
            'qaydtypeid' =>   $type,
            'date' => $date
        ])->id;



        $qayditemFrom= Qayditem::create([
            'qaydid' =>  $qayd_id,
            'branchid' => $account_from->acountant_id,
            'dayin'=> $account_from->dayin,
            'madin'=> $account_from->madin,
            'topic'=> $notes,
            'date' => Carbon::now()
        ]);
        $qayditemTo= Qayditem::create([
            'qaydid' =>  $qayd_id,
            'branchid' => $account_to->acountant_id,
            'dayin'=> $account_to->dayin,
            'madin'=> $account_to->madin,
            'topic'=> $notes,
            'date' => Carbon::now()
        ]);

        return $qayd_id;
    }

    public function incomeStatementIndex(){

        $sellAc = $this->calcTreeFinal(4511);
        $discountAC = $this->calcTreeFinal(4513);
        $sellRefundAc = $this->calcTreeFinal(4512);
        $sellsafi = $sellAc['daien'] - $discountAC['madien'] -$sellRefundAc['daien'];
        // $sellsafi = $sellAc['daien']-$sellAc['madien'] - $discountAC['daien']-$discountAC['madien'] -$sellRefundAc['daien']-$sellRefundAc['madien'];

        $beginInv = $this->calcTreeFinal(130);
        $buyAc = $this->calcTreeFinal(2641);

        $buyAc = $this->calcTreeFinal(2641);
        $buydiscount = $this->calcTreeFinal(2642);
        $buyRefundAc = $this->calcTreeFinal(2643);

         $endInv = $this->calcTreeFinal(131);

         $buyInvCoasts = $this->calcTreeFinal(3310); //تكاليف شراء البضاعة
         $allCosts = $this->calcTreeFinal(3);


         $ehlaks = $this->calcTreeFinal(23);
         $ehlak = $ehlaks['madien'];

         $faydaas = $this->calcTreeFinal(1733);
         $faydaa = $faydaas['madien'];

         $darebas = $this->calcTreeFinal(369);
         $dareba = $darebas['madien'];


         $masrof = $allCosts['madien'] - $buyInvCoasts['madien'];
         $stores = $this->calcTreeFinal(131); //المخزون

        $sellcoast =$stores['daien']-$stores['madien'] - $beginInv['madien'] + $buyAc['daien'] + $buyInvCoasts['madien']  - $buyRefundAc['madien'] - $buydiscount['madien']  ;
        // $sellcoast = $beginInv['madien'] + $buyAc['madien'] + $buyInvCoasts['madien'] - $buydiscount['madien']  - $buyRefundAc['madien'] - $buydiscount['madien'] - $stores['madien'];

       return View('incomeStatement',compact('dareba','faydaa','ehlak','masrof','sellAc','discountAC','sellRefundAc','sellsafi','beginInv','buyAc','buydiscount','buyRefundAc','endInv','buyInvCoasts','stores','sellcoast'));
    }

    public function trialBalanceIndex(){




        $mainAc = BranchTree::where('parent_id',null)->get();
        $SumDaien=0;
        $SumMadein=0;
        foreach ($mainAc as $key => $ac) {
            // $ac['child'] = BranchTree::where('parent_id',$ac['id'])->with('descendantswithoutQayd')->get();
            $ac['child'] = BranchTree::where('accountant_number', 'LIKE', $ac['accountant_number']."%")->with('qayditems')->get();
            $ac['total'] = $this->calcNode($ac['child']);
            $SumMadein += $ac['total'][0];
            $SumDaien += $ac['total'][1];
        //   return $ac;
        }

        // return $mainAc;

        return View('trialBalance',compact('mainAc','SumMadein','SumDaien'));

    }

    public function calcNode($tree){
        $SumMadein = 0;
        $SumDaien = 0;
        // $tree = $tree->flatten();

        foreach ($tree as $key => $value) {


                $SumMadein += $value->qayditems->sum('madin');
                $SumDaien += $value->qayditems->sum('dayin');

        }

        return [$SumMadein,$SumDaien];

    }

   public function accountstatement($branchId=0,$from,$to)
    {

        $accounts=Qayditem::where('branchid',$branchId)->
        where('date','>=',$from)->
        where('date',"<=",$to)->
        with(['qayd', 'branch_tree'])->get();
        return $accounts;

    }
     public function Dayinaccountstatement($branchId=0,$from,$to)
    {

        $accounts=Qayditem::where('branchid',$branchId)->
        where('date','>=',$from)->
        where('date',"<=",$to)->
        with(['qayd', 'branch_tree'])->sum('dayin');
        return $accounts;

    }

     public function Madinaccountstatement($branchId=0,$from,$to)
    {

        $accounts=Qayditem::where('branchid',$branchId)->
        where('date','>=',$from)->
        where('date',"<=",$to)->
        with(['qayd', 'branch_tree'])->sum('madin');
        return $accounts;

    }

    public function motagra(){
        //Right side
        //      -- 130
        //      -- 1314 - 1312-1313-1315-1316
        //      -- 4512
        //      -- 4513
        //      --3512
        $RightSide =[];
        $year = date("Y");
        // return $year ;
        $B = BranchTree::where('accountant_number',130)->first();
        array_push($RightSide , (object) ['name' => $B->name ,'accountant_number' => $B->accountant_number , 'madin'=> $this->Madinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ,  'dayin'=> $this->Dayinaccountstatement($B->id , '2023-01-01' ,'2023-12-30' ) ] );

        $B = BranchTree::where('accountant_number',1314)->first();
        array_push($RightSide , (object) ['name' => $B->name ,'accountant_number' => $B->accountant_number, 'madin'=> $this->Madinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ,  'dayin'=> $this->Dayinaccountstatement($B->id , '2023-01-01' ,'2023-12-30' ) ] );

        $B = BranchTree::where('accountant_number',1312)->first();
        array_push($RightSide , (object) ['name' => $B->name ,'accountant_number' => $B->accountant_number, 'madin'=> $this->Madinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ,  'dayin'=> $this->Dayinaccountstatement($B->id , '2023-01-01' ,'2023-12-30' ) ] );

        $B = BranchTree::where('accountant_number',1313)->first();
        array_push($RightSide , (object) ['name' => $B->name ,'accountant_number' => $B->accountant_number, 'madin'=> $this->Madinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ,  'dayin'=> $this->Dayinaccountstatement($B->id , '2023-01-01' ,'2023-12-30' ) ] );

        $B = BranchTree::where('accountant_number',1315)->first();
        array_push($RightSide , (object) ['name' => $B->name ,'accountant_number' => $B->accountant_number, 'madin'=> $this->Madinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ,  'dayin'=> $this->Dayinaccountstatement($B->id , '2023-01-01' ,'2023-12-30' ) ] );

        $B = BranchTree::where('accountant_number',1316)->first();
        array_push($RightSide , (object) ['name' => $B->name ,'accountant_number' => $B->accountant_number, 'madin'=> $this->Madinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ,  'dayin'=> $this->Dayinaccountstatement($B->id , '2023-01-01' ,'2023-12-30' ) ] );

         $B = BranchTree::where('accountant_number',4512)->first();
        array_push($RightSide , (object) ['name' => $B->name ,'accountant_number' => $B->accountant_number, 'madin'=> $this->Madinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ,  'dayin'=> $this->Dayinaccountstatement($B->id , '2023-01-01' ,'2023-12-30' ) ] );

         $B = BranchTree::where('accountant_number',4513)->first();
        array_push($RightSide , (object) ['name' => $B->name ,'accountant_number' => $B->accountant_number, 'madin'=> $this->Madinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ,  'dayin'=> $this->Dayinaccountstatement($B->id , '2023-01-01' ,'2023-12-30' ) ] );

         $B = BranchTree::where('accountant_number',3512)->first();
        array_push($RightSide , (object) ['name' => $B->name ,'accountant_number' => $B->accountant_number, 'madin'=> $this->Madinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ,  'dayin'=> $this->Dayinaccountstatement($B->id , '2023-01-01' ,'2023-12-30' ) ] );



        //Left Side
        // -- 1314 - 1312-1313-1315-1316
        // -- 4511
        // -- 342
        $LeftSide =[];
        // $B = BranchTree::where('accountant_number',1314)->first();
        // array_push($LeftSide , (object) ['name' => $B->name ,'accountant_number' => $B->accountant_number, 'madin'=> $this->Madinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ,  'dayin'=> $this->Dayinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ] );


        // $B = BranchTree::where('accountant_number',1312)->first();
        // array_push($LeftSide , (object) ['name' => $B->name ,'accountant_number' => $B->accountant_number, 'madin'=> $this->Madinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ,  'dayin'=> $this->Dayinaccountstatement($B->id , $year.'-01-01' ,$year.'2023-12-30' ) ] );

        // $B = BranchTree::where('accountant_number',1313)->first();
        // array_push($LeftSide , (object) ['name' => $B->name ,'accountant_number' => $B->accountant_number, 'madin'=> $this->Madinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ,  'dayin'=> $this->Dayinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ] );

        // $B = BranchTree::where('accountant_number',1315)->first();
        // array_push($LeftSide , (object) ['name' => $B->name ,'accountant_number' => $B->accountant_number, 'madin'=> $this->Madinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ,  'dayin'=> $this->Dayinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ] );

        // $B = BranchTree::where('accountant_number',1316)->first();
        // array_push($LeftSide , (object) ['name' => $B->name ,'accountant_number' => $B->accountant_number, 'madin'=> $this->Madinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ,  'dayin'=> $this->Dayinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ] );

        array_push($LeftSide , (object) ['name' => 'اخر المدة' ,'accountant_number' => '-----', 'madin'=> 0 ,  'dayin'=> 0 ] );
        $B = BranchTree::where('accountant_number',4511)->first();
        array_push($LeftSide , (object) ['name' => $B->name ,'accountant_number' => $B->accountant_number, 'madin'=> $this->Madinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ,  'dayin'=> $this->Dayinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ] );

        $B1 = BranchTree::where('accountant_number',131)->first();
        if($B1){
            array_push($LeftSide , (object) ['name' => $B1->name ,'accountant_number' => $B1->accountant_number, 'madin'=> $this->calcTreeFinal($B1->accountant_number)['madien'] ,  'dayin'=> $this->calcTreeFinal($B1->accountant_number)['daien'] ] );
            //array_push($LeftSide , (object) ['name' => $B->name ,'accountant_number' => $B->accountant_number, 'madin'=> $this->Madinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ,  'dayin'=> $this->Dayinaccountstatement($B->id , $year.'-01-01' ,$year.'-12-30' ) ] );        
        }
        

        // $BId = BranchTree::where('accountant_number',1314)->first()->id;
        // return $this->accountstatement($BId , '2023-01-01' ,'2023-12-30' );
        // return [$RightSide ,$LeftSide];
        return view('motagra',['data'=>[$RightSide ,$LeftSide]]);
    }

     public function calcTreeFinal($branchId){

     $SumMadien = 0;
     $SumDaein = 0;

         $branch =BranchTree::where('accountant_number',$branchId)->with("getallbranchs")->get();
        Collection::macro('recursive',function(){
            return $this->map(function($value){
                if(count($value->getallbranchs) > 0){
                    return collect($value->getallbranchs)->recursive();
                }else{
                    if(count($value->qayditems) > 0){
                        $value['madinSum']= $value->qayditems->sum('madin');
                        $value['dayinSum']= $value->qayditems->sum('dayin');
                        //  $this-$SumMadien += $value->qayditems->sum('madin');
                        //  $this->SumDaein += $value->qayditems->sum('dayin');
                    }
                }
                return $value;
            });

        });

         $res = collect($branch)->recursive()->flatten();

        foreach($res as $result){
            if($result->madinSum){
                $SumMadien+=$result->madinSum;
            }
             if($result->dayinSum){
                $SumDaein+=$result->dayinSum;
            }
        }

        return ["Result"=>$res , "madien"=>$SumMadien, "daien"=>$SumDaein ];


    }
}


