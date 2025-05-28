<?php

namespace App\Http\Controllers;
use App\Models\AllKit;

use App\Models\Kit;
use App\Models\KitPart;
use App\Models\KitNumber;
use App\Models\KitImage;
use App\Models\KitModel;
use App\Models\KitSpec;
use App\Models\KitDetail;
use App\Models\BrandType;
use App\Models\Brand;
use App\Models\Replyorder;
use Illuminate\Http\Request;
use File;
use DataTables;
use function PHPUnit\Framework\isNull;
class KitsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $kits=Kit::with('kit_parts')->get();;
        // dump($kits);
        // return view("kitIndex",compact("kits"));
    }

    public function indexWithID(Kit $kitId)
    {
        return $kit=Kit::where('id' , $kitId->id)
        ->with(['kit_parts'=>function($query){
            return $query->with('part')->get();
        }])
        ->with(['kit_numbers'=>function($query){
            return $query->with('supplier')->get();
        }])
        ->with(['kit_details'=>function($query){
            return $query->with(['kit_spec'=>function($query1){
                return $query1->get();
            }])->get();
        }])
        ->with(['kit_models'=>function($query){
            return $query->with(['series'=>function($query1){
                return $query1->with(['model'=>function($query1){
                    return $query1->with('brand_type')->with('brand')->get();
                }])->get();
            }])->get();
        }])
        ->with('kit_images')->get();

    }

    public function indexWithRequest(Request $request)
    {

        if ($request->ajax()) {

            $data = Kit::all();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('Image',function($row){
                        $partImage = KitImage::where('kit_id',$row->id)->get();
                        $html="";
                        foreach ($partImage as $key => $value) {
                            $html = $html . '<img class="rounded-circle header-profile-user" src="assets/kit_images/'. $value->image_url.'" alt="Emara">';
                        }
                        return $html;
                    })
                    ->addColumn('name',function($row){
                        return $row->name ;
                    })
                    ->addColumn('KitPart',function($row){
                        $kitParts= KitPart::where('kit_id',$row->id)->get();
                        $html = '<ul>';
                        foreach ($kitParts as $key => $value) {

                                $html .='<li>'.$value->amount.' X '.$value->part->name.'</li>';
                        }
                        $html .= '</ul>';
                        return $html;
                    })
                    ->addColumn('KitNumbers',function($row){
                        $kitNumbers= KitNumber::where('kit_id',$row->id)->get();
                        $html = '<ul>';
                        foreach ($kitNumbers as $key => $value) {
                            if($value->flag_OM == "1"){
                                $html .='<li class="text-info">'.$value->number.'</li>';
                            }else{
                                $html .='<li>'.$value->number.'</li>';
                            }
                        }
                        $html .= '</ul>';
                        return $html;
                    })
                    ->addColumn('KitBrand',function($row){
                        $kitModels= KitModel::where('kit_id',$row->id)->get();
                        $html = '<ul>';
                        foreach ($kitModels as $key => $value) {
                                if( isset($value->series) ){
                                    $html .='<li class="text-info">'.$value->series->name .' / '.$value->series->model->name .' / '.$value->series->model->brand->name.'</li>';
                                }

                        }
                        $html .= '</ul>';

                        return $html;
                    })


                    ->addColumn('action', function($row){
                        $btn = '<a  data-bs-toggle="modal" kit_id_value="'.$row->id.'"  data-bs-target="#editKit"
                        data-toggle="tooltip" data-original-title="Edit" class="btn btn-primary btn-sm d-inline-block editkitB "><i class="ri-edit-line editKitButton"></i></a>';
                        $btn = $btn.' <a  data-bs-toggle="modal"  kit_id_value2="'.$row->id.'"  data-bs-target="#deletekitB"
                        data-toggle="tooltip" data-original-title="Delete" class="btn btn-danger btn-sm d-inline-block deletekitB "><i class="ri-delete-bin-3-line deletekitButton"></i></a>';


                            return $btn;

                        // $btn = '<a  data-bs-toggle="modal"  kit_id_value="'.$row->id.'"  data-bs-target="#editKit"
                        // data-toggle="tooltip" data-original-title="Edit" class="btn btn-primary btn-sm d-inline-block editkitB"><i class="ri-edit-line editKitButton"></i></a>';
                        // $btn = $btn.' <form action="'.route("kit.destroy",$row->id).'" method="POST" class="form-block float-end">'
                        //         . csrf_field()
                        //         . method_field('DELETE')
                        //         .'<input type="submit" value="Delete" class= "btn btn-danger">'.
                        //         '</form>';
                                // return $btn;
                 })
                    ->rawColumns(['name','KitPart','KitNumbers','Image','KitBrand','action'])
                    ->make(true);
        }
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
        kit::create($request->all());
        $kit_id=Kit::max('id');
        for ($i=0 ; $i < count($request->kitNumber) ; $i++) {
            if(isset($request->kitNumber[0])){
                $number = $request->kitNumber[$i];
                $supplier_id = $request->supplierSlct[$i];
                if($request->supplierSlct[$i] == 13){
                    $flag_OM = 1;
                    }else{
                        $flag_OM = 0;
                    }
                KitNumber::create(['number' => $number , 'flag_OM' => $flag_OM,'supplier_id'=> $supplier_id , 'kit_id' => $kit_id]);
            }else{
                break;
            }
        }

        if(isset($request->kitParts)){
            for ($i=0; $i < count($request->kitParts) ; $i++) {
                KitPart::create(['kit_id' => $kit_id , 'part_id' => $request->kitParts[$i] , 'amount' => $request->kitPartAmount[$i] ]);
            }
        }

        if(isset($request->specs)){
            for ($i=0; $i < count($request->specs) ; $i++) {
                $specs = new KitSpec();
                $specs->name = $request->specs[$i];
                $specs->general_flag = $kit_id;
                $specs->save();
                $specDetail = new KitDetail();
                $specDetail->kitpecs_id = $specs->id;
                $specDetail->kit_id = $kit_id;
                if(isset($request->specsval)){
                    $specDetail->value = $request->specsval[$i];
                    $specDetail->save();
                }
                $specDetail->save();
            }
        }
        if(isset($request->oldSpecs)){
            for ($j=0; $j < count($request->oldspecsval) ; $j++) {
                if($request->oldspecsval[$j] != null){
                    KitDetail::create(['kitpecs_id'=> $request->oldSpecs[$j] ,'kit_id'=> $kit_id ,'value' => $request->oldspecsval[$j]]);
                }else{
                    continue;
                }

            }
        }

        if(isset($request->series)){
            for ($i=0; $i < count($request->series) ; $i++) {
                KitModel::create(['kit_id' => $kit_id , 'model_id' => $request->series[$i]]);
            }
        }

        if(isset($request->kitImg)){
            for ($i=0; $i < count($request->kitImg) ; $i++) {
                $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->kitImg[$i]->getClientOriginalName());
                $imageName = time() . $namewithoutchar.'.'.$request->kitImg[$i]->extension() ;
                $request->kitImg[$i]->move(public_path('assets/kit_images'), $imageName);
                KitImage::create(['kit_id' => $kit_id , 'image_url' => $imageName]);

            }
        }
       return back()->with('success','You have successfully Save Kit.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Kit $kit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kit $kit)
    {
        return($kit);

    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, Kit $kit ){

        $kit_id= $kit->id;

        //  return $request;
        $kit->kit_models()->delete();
        $kit->kit_details()->delete();
        $kitSpecs= KitSpec::where('general_flag' , $kit->id)->get();
        if(count($kitSpecs)>0){
            $kitSpecs->each->delete();
            }
        $kit->kit_numbers()->delete();

         for($i = 0 ; $i< count(explode(',',$request->imgURLsInp[0])) ; $i++){
            if(explode(',',$request->imgURLsInp[0])[$i] != null){
                $imageN = explode(',',$request->imgURLsInp[0])[$i];
                $imageN = preg_replace('/\s+/','',$imageN);

                $kit->kit_images()->where('image_url',$imageN )->delete();
            }
        }

        // for($i = 0 ; $i< count($request->imgURLsInp) ; $i++){
        //     if($request->imgURLsInp[$i] != null){
        //         $kit->kit_images()->where('image_url', $request->imgURLsInp[$i])->delete();
        //     }
        // }



        if(isset($request->notifyEdit)){
            $kit->update(['name'=> $request->nameedit , 'engname' => $request->engnameedit , 'notes' => $request->notesedit , 'limit' => $request->limitedit , 'notify' => '1' ]);
        }else{
            $kit->update(['name'=> $request->nameedit , 'engname' => $request->engnameedit , 'notes' => $request->notesedit , 'limit' => $request->limitedit , 'notify' => '0' ]);
        }


        if($request->kitNumberEdit){
            for ($i=0 ; $i < count($request->kitNumberEdit) ; $i++) {
                $number = $request->kitNumberEdit[$i];
                $supplier_id = $request->supplierSlctEdit[$i];
                if($request->supplierSlctEdit[$i] == 13){
                    $flag_OM = 1;
                    }else{
                        $flag_OM = 0;
                    }
                KitNumber::create(['number' => $number , 'flag_OM' => $flag_OM,'supplier_id'=> $supplier_id , 'kit_id' => $kit_id]);
            }
        }
        if(isset($request->specsEdit)){

            for ($i=0; $i < count($request->specsEdit) ; $i++) {
                $specsEdit = new KitSpec();
                $specsEdit->name = $request->specsEdit[$i];
                $specsEdit->general_flag = $kit_id;
                $specsEdit->save();
                $specDetail = new KitDetail();
                $specDetail->kitpecs_id = $specsEdit->id;
                $specDetail->kit_id = $kit->id;
                if(isset($request->specsvalEdit)){
                    $specDetail->value = $request->specsvalEdit[$i];
                    $specDetail->save();
                }
                $specDetail->value = $request->specsvalEdit[$i];
                $specDetail->save();
            }

        }
        if(isset($request->oldSpecsEdit)){
            for ($j=0; $j < count($request->oldspecsvalEdit) ; $j++) {
                if($request->oldspecsvalEdit[$j] != null){
                    KitDetail::create(['kitpecs_id'=> $request->oldSpecsEdit[$j] ,'kit_id'=> $kit_id ,'value' => $request->oldspecsvalEdit[$j]]);
                }else{
                    continue;
                }
            }
        }
        if(isset($request->seriesEdit)){
            for ($i=0; $i < count($request->seriesEdit) ; $i++) {
                KitModel::create(['kit_id' => $kit_id , 'model_id' => $request->seriesEdit[$i]]);
            }
        }
        if($request->kitImgEdit){
            for ($i=0; $i < count($request->kitImgEdit) ; $i++) {
                $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->kitImgEdit[$i]->getClientOriginalName());
                $imageName = time() . $namewithoutchar.'.'.$request->kitImgEdit[$i]->extension() ;
                $request->kitImgEdit[$i]->move(public_path('assets/kit_images'), $imageName);
                KitImage::create(['kit_id' => $kit_id , 'image_url' => $imageName]);

            }
        }

        $allkitPart= AllKit::where('part_id' , $kit->id)->get();
        if(count($allkitPart)>0){
        return back()->with('error','تم التعديل بنجاح ولا يمكن تعديل الأصناف داخل كيت موجود فى فواتير الشراء');

        }else{
            $kitPart= KitPart::where('kit_id' , $kit->id)->get();
            if(count($kitPart)>0){
                $kitPart->each->delete();
            }
            if($request->kitPartEdit){
                for ($i=0 ; $i < count($request->kitPartEdit) ; $i++) {
                    KitPart::create(['kit_id' => $kit_id  , 'part_id' => $request->kitPartEdit[$i] , 'amount' => $request->kitPartAmountEdit[$i]]);
                }
            }
        }





        return back()->with('success',' تم تعديل الكيت بنجاح');

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,Kit $kit){
        // return  $request;
        $kit = Kit::where('id', $request->kit_id)->first();
        $item_in_invoice = Replyorder::where('part_id', $request->kit_id)->where('part_type_id',6)->get();
        $image_name = $kit->kit_images()->get('image_url');
        for($i=0 ; $i < count($image_name) ; $i++){
            $image_path = public_path('assets/kit_images/'.$image_name);
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
        }

        if(count($item_in_invoice) == 0){
        $kit->kit_images()->delete();
        $kit->kit_models()->delete();
        $kit->kit_details()->delete();
        $kitPart= KitPart::where('kit_id' , $kit->id)->get();
        if(count($kitPart)>0){
            $kitPart->each->delete();
                }
        $kitSpecs= KitSpec::where('general_flag' , $kit->id)->get();
        if(count($kitSpecs)>0){
        $kitSpecs->each->delete();
            }
        $kit->kit_numbers()->delete();
        $kit->kit_parts()->delete();
        $kit->delete();
        return back()->with('success','تم حذف الكيت بنجاح');
        }else{
            return back()->with('error',' لا يمكن حذف كيت موجود فى فواتير شراء');

        }
    }

    public function kitspecs(){

        return KitSpec::where('general_flag','0')->get();
    }

    public function kitBrand(){
        $brandType = BrandType::all();
        $brand = Brand::orderBy('name', 'asc')->get();
        return[$brandType , $brand];
    }
}
