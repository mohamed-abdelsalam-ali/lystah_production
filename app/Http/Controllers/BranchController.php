<?php

namespace App\Http\Controllers;
use App\Models\BranchTree;
use Illuminate\Http\Request;
use PHPUnit\Util\Json;
use Illuminate\Support\Collection;


class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $branch=BranchTree::get(['id as key' ,'name','parent_id as parent']);
        return view('branch.branch',['branch' => json_encode($branch)]);
    }
    public function getAll()
    {
        //
        $branch=BranchTree::all();
        return $branch;
    }
    public function getAllwithView()
    {
        //
        $branch=BranchTree::all();
        return view('branch.branchtable',compact('branch'));
    }
    public function getparent()
    {
        //
        // $m= BranchTree::where('parent_id',"=",null)->with("getallbranchs")->get();
        // return $m;


        $branchparent=BranchTree::where('parent_id',"=",null)->get();

        // return $branchparent;
        return view('branch.branch_table',compact('branchparent'));
    }
    public function salam_recursive($current,$next){
         
            if (count($current->getallbranchs) > 0 ){
                 return $next;;
            }else{
                return $current;
            }
      
        
        
    }
    public function calcTree($branchId){

     
        
         $branch =BranchTree::where('id',$branchId)->with("getallbranchs")->get();
        Collection::macro('recursive',function(){
            return $this->map(function($value){
                if(count($value->getallbranchs) > 0){
                    return collect($value->getallbranchs)->recursive();
                }else{
                    if(count($value->qayditems) > 0){
                        $value['madinSum']= $value->qayditems->sum('madin');        
                        $value['dayinSum']= $value->qayditems->sum('dayin');        
                    }
                }
                return $value;
            });
            
        });
        return collect($branch)->recursive();
      
        

         foreach($branch[0]->getallbranchs as $node){
            echo '-----------------------------';

            echo $node;
            echo '-----------------------------';


         }
        
          return $this->mainBranch($branchId);

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

        public $lastChildsArray=  array();
        public $childsArrayIndex=0;
    public function getChilds($array){
        foreach($array as $node){

            if(sizeof( $node->getallbranchs)>0){

                   $this-> getChilds($node->getallbranchs) ;
              }
               else  {
                // echo $node->id;
                // $lastChildsArray[$this->childsArrayIndex]=$node->id;
                // $this->childsArrayIndex++;

                array_push($this->lastChildsArray,$node);
                $this->childsArrayIndex+=$node->notes;
                // echo  $this->childsArrayIndex;  echo"<br>";
                // this->parent->notes += node->notes;

            }
            //
            //   echo "-";
        }

        //  dd( $this->lastChildsArray);
    }
    public function mainBranch ($branchId){
        $theWholebranch =BranchTree::where('id',$branchId)->with("getallbranchs")->get();

            //return $theWholebranch;
          if(isset( $theWholebranch[0]->getallbranchs)){
             $this-> getChilds($theWholebranch[0]->getallbranchs) ;
          }
        //   echo $this->lastChildsArray;
        // return $this->lastChildsArray;
        return $this->childsArrayIndex;


    }
    public function buildTree(array $elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }
    public function getAllChild($node){

        if(isset($node[0]->id)){
            while (BranchTree::where('parent_id',$node->id)->with("branch_trees")->get()) {
                $d =BranchTree::where('parent_id',$node->id)->with("branch_trees")->get();
                $this->getAllChild($d[0]->branch_trees);
            }
        }else{
            return $node->id . '<br>';
        }


    }
    public function getlastnodeMakram($node){
        if(isset($node->getallbranchs)){
            $child = $node->getallbranchs;
            return $this->getlastnodeMakram($child);
        }else{

            return $node;
        }

    }
    public function getbranchwithchild($id){
        $branchchild=BranchTree::where('parent_id',"=",$id)->get();
        return $branchchild;

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

        dd($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
    public function save_branch(Request $request)
    {

         $branchtree=BranchTree::where('id','=',$request->critiria_id)->first();
        //  dd($branchtree);

         if($branchtree!= ''){
                    // dd( $request);
                    $branchtree->update([
                        'name' =>   $request->critiria_name,
                        'en_name' => $request->critiria_namen,
                        'parent_id' =>  $request->parent_id,
                        'accountant_number'=>$request->weight
                    ]);


                return redirect()->route('profile');
         }else{
                BranchTree::create([

                        'name' =>   $request->critiria_name,
                        'en_name' => $request->critiria_namen,
                        'parent_id' =>  $request->parent_id,
                        'accountant_number'=>$request->weight
                    ]);


                return redirect()->route('profile');
         }

    }
    public function delete_branch(Request $request)
    {

        // dd($request);
        // $branchtree=BranchTree::where('parent_id','=',$request->cid)->get();
        // dd($branchtree);
        $BranchTree=BranchTree::findorfail($request->cid)->delete();
        return redirect()->route('profile');




    }
    public function getAllFinalChild(){

        $childs=BranchTree::whereNotIn('id',function ($query) {
            $query->select('parent_id')
                ->distinct()
                ->from('branch_tree')
                ->whereNotNull('parent_id');
            })->get();

            return $childs;
    }











}
