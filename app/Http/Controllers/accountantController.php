<?php

namespace App\Http\Controllers;

use App\Models\StoreStructure;
use App\Models\StoreSection;
use App\Models\Store;
use App\Models\BranchTree;
use App\Models\BrandType;
use App\Models\Qayd;
use App\Models\Qayditem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Http\Controllers\QaydController;

class accountantController extends Controller
{
    public function endYear(){
        $end_results=Qayditem::with(['branch_tree'])
                        ->selectRaw("SUM(qayditem.dayin) as tdayin")
                        ->selectRaw("SUM(qayditem.madin) as tmadin")
                        ->selectRaw("qayditem.branchid")
                        ->groupBy("qayditem.branchid")
                        ->get();
        return View('accountant.endYear',compact("end_results"));
    }

    public function storegardMaley(){

        $stores = Store::all();
        foreach ($stores as $key => $store) {

            $store['qayd'] = Qayditem::join('branch_tree', 'qayditem.branchid', '=', 'branch_tree.id')
            ->where('branch_tree.accountant_number', $store->accountant_number)
            ->selectRaw("SUM(qayditem.dayin) as tdayin")
            ->selectRaw("SUM(qayditem.madin) as tmadin")
            ->selectRaw("qayditem.branchid")
            ->groupBy("qayditem.branchid")
            ->first();
        }

        // return $stores;
        return View('accountant.storegardMaley',compact("stores"));
    }


    public function onestoregardMaley($store_id){
        $store = Store::where('id',$store_id)->first();
        $storeSections = StoreSection::where('store_id',$store_id)
        ->where('amount','>',0)
        ->whereHas('part', function ($query) {
            $query->where('type_id', 1);
        })
        ->orWhereHas('wheel', function ($query) {
            $query->where('type_id', 2);
        })
        ->orWhereHas('tractor', function ($query) {
            $query->where('type_id', 3);
        })
        ->orWhereHas('clark', function ($query) {
            $query->where('type_id', 4);
        })
        ->orWhereHas('equip', function ($query) {
            $query->where('type_id', 5);
        })
        ->orWhereHas('kit', function ($query) {
            $query->where('type_id', 6);
        })
        ->with(['part','wheel','tractor','clark','store_structure','type' ,'kit','equip','source','status','part_quality'])
        // ->paginate(10);
        ->get();

        // return $storeSections;

        return View('accountant.onestoregardMaley',compact("storeSections","store"));

    }


}


