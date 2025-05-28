<?php

namespace App\Http\Controllers;

use App\Models\Wheel;
use App\Models\WheelPart;
use App\Models\WheelNumber;
use App\Models\WheelImage;
use App\Models\WheelModel;
use App\Models\WheelSpec;
use App\Models\RelatedWheel;
use App\Models\WheelDetail;
use App\Models\BrandType;
use App\Models\Brand;
use App\Models\Replyorder;
use Illuminate\Http\Request;
use File;
use DataTables;
use function PHPUnit\Framework\isNull;

class WheelsController extends Controller
{

    public function indexWithRequest(Request $request)
    {

        if ($request->ajax()) {

            $data = Wheel::all();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('name',function($row){
                        return $row->name;
                    })
                    ->addColumn('dimension',function($row){
                        return isset($row->wheel_dimension->dimension) ? $row->wheel_dimension->dimension : '--';
                    })
                    ->addColumn('material',function($row){
                        return isset($row->wheel_material->name) ? $row->wheel_material->name : '--';
                    })
                    ->addColumn('model',function($row){
                        return isset($row->wheel_model->name) ? $row->wheel_model->name : '--';
                    })
                    ->addColumn('image',function($row){
                        $wheelImage = WheelImage::where('wheel_id',$row->id)->get();
                        $html="";
                        foreach ($wheelImage as $key => $value) {
                            $html = $html . '<img class="rounded-circle header-profile-user" src="assets/wheel_images/'. $value->image_name.'" alt="Emara">';
                        }
                        return $html;
                    })
                    ->addColumn('action', function($row){
                        $btn = '<a  data-bs-toggle="modal"  wheel_id_value="'.$row->id.'"  data-bs-target="#editWheel"
                        data-toggle="tooltip" data-original-title="Edit" class="btn btn-primary btn-sm d-inline-block editwheelB "><i class="ri-edit-line editWeelButton"></i></a>';
                        $btn = $btn.' <a  data-bs-toggle="modal"  wheel_id_value2="'.$row->id.'"  data-bs-target="#deletewheelB"
                        data-toggle="tooltip" data-original-title="Delete" class="btn btn-danger btn-sm d-inline-block deletewheelB "><i class="ri-delete-bin-3-line deleteWeelButton"></i></a>';





                        // $btn = '<a  data-bs-toggle="modal"  wheel_id_value="'.$row->id.'"  data-bs-target="#editWheel"
                        // data-toggle="tooltip" data-original-title="Edit" class="btn btn-primary btn-sm editwheelB"><i class="ri-edit-line editWeelButton"></i></a>';
                        // // $btn = '<a  href="'.route("wheel.edit",$row->id).'" data-toggle="tooltip" data-original-title="Edit" class="edit btn btn-primary btn-sm editWheel"><i class="ri-edit-line"></i></a>';
                        // $btn = $btn.' <form action="'.route("wheel.destroy",$row->id).'" method="POST">'
                        //         . csrf_field()
                        //         . method_field('DELETE')
                        //         .'<input type="submit" value="Delete" class= "btn btn-danger">'.
                        //         '</form>';
                                return $btn;
                 })
                    ->rawColumns(['dimension' , 'material','model' , 'image', 'action'])
                    ->make(true);
        }
    }

    public function indexWithID(Wheel $wheelId)
    {
         $wheel=Wheel::where('id' , $wheelId->id)
        ->with('wheel_dimension')
        ->with('wheel_model')
        ->with('wheel_material')
        ->with(['related_wheels'=>function($query){
            return $query->with(['wheel'=>function($query1){
                return $query1->get();
            }])->get();
        }])
        ->with(['wheel_details'=>function($query){
            return $query->with(['wheel_spec'=>function($query1){
                return $query1->get();
            }])->get();
        }])
        ->with('wheel_images')->get();
        return $wheel;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Wheel::all();
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
    public function store(Request $request){
        // return$request;
        $wheel_id=  Wheel::create([
            'name'=>$request->name ,
            'dimension'=>$request->dimension ,
            'description'=>$request->description ,
            'type_id'=>2 ,
            'status_id'=>1 ,
            'insertion_date'=>date("Y-m-d") ,
            'name_eng'=>$request->name ,
            'limit_order'=>$request->limit_order,
            'flage_limit_order'=>$request->notify ,
            'model_id'=>$request->model_id ,
            'wheel_material_id'=>$request->wheel_material_id ,
            'tt_tl'=>$request->tt_tl ,
            'wheel_container_size'=>$request->container_size 
        ])->id;
        $wheel_id=Wheel::max('id');
        if(isset($request->related_wheel)){
            for ($i=0; $i < count($request->related_wheel) ; $i++) {
                RelatedWheel::create(['wheel_id' => $wheel_id , 'sug_wheel_id' => $request->related_wheel[$i]]);
            }
        }

        if(isset($request->specs)){
            for ($i=0; $i < count($request->specs) ; $i++) {
                if($request->specs[$i] != null){
                    $specs = new WheelSpec();
                    $specs->name = $request->specs[$i];
                    $specs->general_flag = $wheel_id;
                    $specs->save();

                    $specDetail = new WheelDetail();
                    $specDetail->wheelpecs_id = $specs->id;
                    $specDetail->wheel_id = $wheel_id;
                    if(isset($request->specsval)){
                        $specDetail->value = $request->specsval[$i];
                        $specDetail->save();
                    }
                    $specDetail->save();
                }
            }
        }
        if(isset($request->oldSpecs)){
            for ($j=0; $j < count($request->oldspecsval) ; $j++) {
                if($request->oldspecsval[$j] != null){
                    WheelDetail::create(['Wheelpecs_id'=> $request->oldSpecs[$j]  ,'wheel_id'=> $wheel_id ,'value' => $request->oldspecsval[$j]]);
                }else{
                    continue;
                }

            }
        }

        if(isset($request->wheelImg)){
            for ($i=0; $i < count($request->wheelImg) ; $i++) {
                $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->wheelImg[$i]->getClientOriginalName());
                $imageName = time() . $namewithoutchar.'.'.$request->wheelImg[$i]->extension() ;
                $request->wheelImg[$i]->move(public_path('assets/wheel_images'), $imageName);
                WheelImage::create(['wheel_id' => $wheel_id , 'name' => $namewithoutchar,'image_name' => $imageName]);

            }
        }


       return back()->with('success','You Have Successfully Saved Wheel.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Wheel $wheel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wheel $wheel)
    {
        return($wheel);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wheel $wheel)
    {
        // dd($request);
        $wheel_id= $wheel->id;
        
         for($i = 0 ; $i< count(explode(',',$request->imgURLsInp[0])) ; $i++){
            if(explode(',',$request->imgURLsInp[0])[$i] != null){
                $imageNS = explode(',',$request->imgURLsInp[0])[$i];
               
                $imageN = preg_replace('/\s+/','',$imageNS);
 
                $wheel->wheel_images()->where('image_name',$imageN )->Orwhere('image_name',$imageNS )->delete();
            }
        }
        
        // for($i = 0 ; $i< count($request->imgURLsInp) ; $i++){
        //     if($request->imgURLsInp[$i] != null){
        //         $wheel->wheel_images()->where('image_name', $request->imgURLsInp[$i])->delete();
        //     }
        // }
       
        $relatedWheel= RelatedWheel::where('wheel_id' ,$wheel_id);
        if(isset($relatedWheel)){
            $relatedWheel->delete();
            // $wheel->related_wheels()->delete();
        }
        $wheel->wheel_details()->delete();
        $wheelSpecs= WheelSpec::where('general_flag' , $wheel_id)->get();
        if(count($wheelSpecs)>0){
            $wheelSpecs->each->delete();
            }
        if(isset($request->nameEdit)){
            $wheel->update(['name'=> $request->nameEdit ,
             'name_eng'=> $request->nameEdit ,
            'description' => $request->descriptioEditn ,
            'dimension' => $request->dimensionEdit , 
            'model_id' => $request->model_idEdit ,
            'wheel_material_id' => $request->wheel_material_id_Edit ,
            'tt_tl' => $request->tt_tlEdit ,
            'limit_order' => $request->limit_orderEdit ,
            'flage_limit_order' => $request->notifyEdit,
            'wheel_container_size' => $request->container_size_edit
                
                ]);
        }
        if(isset($request->related_wheelEdit)){
            for ($i=0; $i < count($request->related_wheelEdit) ; $i++) {
                RelatedWheel::create(['wheel_id' => $wheel_id , 'sug_wheel_id' => $request->related_wheelEdit[$i]]);
            }
        }

        if(isset($request->specsEdit)){
            for ($i=0; $i < count($request->specsEdit) ; $i++) {
                $specs = new WheelSpec();
                $specs->name = $request->specsEdit[$i];
                $specs->general_flag = $wheel_id;
                $specs->save();

                $specDetail = new WheelDetail();
                $specDetail->wheelpecs_id = $specs->id;
                $specDetail->wheel_id = $wheel_id;
                if(isset($request->specsvalEdit)){
                    $specDetail->value = $request->specsvalEdit[$i];
                    $specDetail->save();
                }
                $specDetail->value = $request->specsvalEdit[$i];
                $specDetail->save();
            }
        }

        if(isset($request->oldSpecsEdit)){
            for ($j=0; $j < count($request->oldSpecsEdit) ; $j++) {
                if($request->oldSpecsEdit[$j] != null){
                    WheelDetail::create(['Wheelpecs_id'=> $request->oldSpecsEdit[$j]  ,'wheel_id'=> $wheel_id ,'value' => $request->oldspecsvalEdit[$j]]);
                }else{
                    continue;
                }

            }
        }

        if(isset($request->wheelImgEdit)){
            for ($i=0; $i < count($request->wheelImgEdit) ; $i++) {
                $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->wheelImgEdit[$i]->getClientOriginalName());
                $imageName = time() . $namewithoutchar.'.'.$request->wheelImgEdit[$i]->extension() ;
                $request->wheelImgEdit[$i]->move(public_path('assets/wheel_images'), $imageName);
                WheelImage::create(['wheel_id' => $wheel_id , 'name' => $namewithoutchar,'image_name' => $imageName]);
            }
        }
        return back()->with('success','You Have Successfully Updated Wheel.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,Wheel $wheel)
    {
        $wheel = Wheel::where('id', $request->wheel_id)->first();
        // return $wheel;
        $item_in_invoice = Replyorder::where('part_id', $request->wheel_id)->where('part_type_id',3)->get();
        //   return $part->part_images()->get('image_name');
    $image_name = $wheel->wheel_images()->get('image_name');
    for($i=0 ; $i < count($image_name) ; $i++){
        $image_path = public_path('assets/wheel_images/'.$image_name);
        if(File::exists($image_path)) {
            File::delete($image_path);
        }
    }
    if(count($item_in_invoice) == 0){
        $wheel->wheel_images()->delete();
        $wheel->wheel_details()->delete();
        $wheel->related_wheels()->delete();
        $wheelSpecs= WheelSpec::where('general_flag' , $wheel->id)->get();
        if(count($wheelSpecs)>0){
            $wheelSpecs->each->delete();
            }
        $wheel->delete();
        return back()->with('success','You Have Successfully Deleted Part.');
    }else{
        return back()->with('error','You cant Deleted this Part.');

    }


    }

}
