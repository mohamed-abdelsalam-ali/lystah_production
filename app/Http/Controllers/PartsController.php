<?php

namespace App\Http\Controllers;

use App\Models\AllClark;
use App\Models\AllEquip;
use App\Models\AllKit;
use App\Models\AllPart;
use App\Models\AllTractor;
use App\Models\AllWheel;
use App\Models\RefundInvoice;
use App\Models\Brand;
use App\Models\BrandType;
use App\Models\Clark;
use App\Models\PresaleOrderPart;
use App\Models\Client;
use App\Models\Equip;
use App\Models\Group;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Kit;
use App\Models\KitPart;
use App\Models\Model;
use App\Models\OrderSupplier;
use App\Models\Part;
use App\Models\PartDetail;
use App\Models\PartImage;
use App\Models\PartModel;
use App\Models\PartNumber;
use App\Models\PartQuality;
use App\Models\PartSpec;
use App\Models\SalePricing;
use App\Models\Series;
use App\Models\Source;
use App\Models\Status;
use App\Models\Store;
use App\Models\StoresLog;
use App\Models\SubGroup;
use App\Models\Supplier;
use App\Models\Replyorder;
use App\Models\Tax;
use App\Models\RelatedPart;
use App\Models\InvoiceItemsSection;
use App\Models\Tractor;
use App\Models\Wheel;
use App\Models\RefundInvoicePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

// use Yajra\DataTables\DataTables;
class PartsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Part::all();
    }

      public function partsSearch(Request $request)
    {


        $searchKey = urldecode($request->q);

        if (isset($request->type)) {

            if ($request->type == 'part') {

                $nameRes = Part::where('name', 'LIKE', '%' . $searchKey . '%')
                    ->orWhereHas('part_numbers', function ($query) use ($searchKey) {
                        $query->where('number', 'LIKE', '%' . $searchKey . '%');
                    })->orWhereHas('part_details', function ($query) use ($searchKey) {
                        $query->where('value', 'LIKE', '%' . $searchKey . '%')->with('part_spec');
                    })->orWhereHas('part_models.series.model', function ($query) use ($searchKey) {
                        $query->where('name', 'LIKE', '%' . $searchKey . '%');
                    })->orWhereHas('all_parts', function ($query) use ($searchKey) {
                        $query->whereRaw('CONCAT("FN1", part_id, source_id, status_id, quality_id)  LIKE ?', ["%$searchKey%"]);
                    })->with('part_numbers')->with('part_details')->with('getsmallunit.unit')->with('part_models.series.model')->addSelect('*', DB::raw("'Part' as type"), DB::raw("'1' as type_id"))->get();

                return $nameRes;
            } elseif ($request->type == 'kit') {
                $nameRes = Kit::where('name', 'LIKE', '%' . $searchKey . '%')->orWhereHas('kit_numbers', function ($query) use ($searchKey) {
                    $query->where('number', 'LIKE', '%' . $searchKey . '%');
                })->orWhereHas('kit_details', function ($query) use ($searchKey) {
                    $query->where('value', 'LIKE', '%' . $searchKey . '%')->with('kit_spec');
                })->orWhereHas('all_kits', function ($query) use ($searchKey) {
                    $query->whereRaw('CONCAT("FN6", part_id, source_id, status_id, quality_id)  LIKE ?', ["%$searchKey%"]);
                })->with('kit_numbers')->with('kit_details')->addSelect('*', DB::raw("'Kit' as type"), DB::raw("'6' as type_id"))->get();
                return $nameRes;
            } elseif ($request->type == 'wheel') {
                $nameRes = Wheel::where('name', 'LIKE', '%' . $searchKey . '%')->orWhereHas('wheel_material', function ($query) use ($searchKey) {
                    $query->where('name', 'LIKE', '%' . $searchKey . '%');
                })->orWhereHas('wheel_dimension', function ($query) use ($searchKey) {
                    $query->where('dimension', 'LIKE', '%' . $searchKey . '%');
                })
                    ->orwhere('wheel_container_size', 'LIKE', '%' . $searchKey . '%')

                    ->orWhereHas('all_wheels', function ($query) use ($searchKey) {
                        $query->whereRaw('CONCAT("FN2", part_id, source_id, status_id, quality_id)  LIKE ?', ["%$searchKey%"]);
                    })->with('wheel_material')->with('wheel_dimension')->addSelect('*', DB::raw("'Wheel' as type"), DB::raw("'2' as type_id"))->get();
                return $nameRes;
            } elseif ($request->type == 'tractor') {
                $nameRes = Tractor::where('name', 'LIKE', '%' . $searchKey . '%')
                    ->orwhere('tractor_number', 'LIKE', '%' . $searchKey . '%')
                    ->orwhere('name_en', 'LIKE', '%' . $searchKey . '%')

                    ->orwhere('motornumber', 'LIKE', '%' . $searchKey . '%')
                    ->orWhereHas('all_tractors', function ($query) use ($searchKey) {
                        $query->whereRaw('CONCAT("FN3", part_id, source_id, status_id, quality_id)  LIKE ?', ["%$searchKey%"]);
                    })->addSelect('*', DB::raw("'tractor' as type"), DB::raw("'3' as type_id"))->get();
                return $nameRes;
            } elseif ($request->type == 'clark') {
                $nameRes = Clark::where('name', 'LIKE', '%' . $searchKey . '%')
                    ->orwhere('clark_number', 'LIKE', '%' . $searchKey . '%')
                    ->orwhere('eng_name', 'LIKE', '%' . $searchKey . '%')

                    ->orwhere('motor_number', 'LIKE', '%' . $searchKey . '%')
                    ->orWhereHas('all_clarks', function ($query) use ($searchKey) {
                        $query->whereRaw('CONCAT("FN4", part_id, source_id, status_id, quality_id)  LIKE ?', ["%$searchKey%"]);
                    })->addSelect('*', DB::raw("'clark' as type"), DB::raw("'4' as type_id"))->get();
                return $nameRes;
            } elseif ($request->type == 'equip') {
                $nameRes = Equip::where('name', 'LIKE', '%' . $searchKey . '%')
                    ->orwhere('name_eng', 'LIKE', '%' . $searchKey . '%')

                    ->orWhereHas('all_equips', function ($query) use ($searchKey) {
                        $query->whereRaw('CONCAT("FN5", part_id, source_id, status_id, quality_id)  LIKE ?', ["%$searchKey%"]);
                    })->addSelect('*', DB::raw("'equip' as type"), DB::raw("'5' as type_id"))->get();
                return $nameRes;
            }
        } else {



            /******************************* Parts ****************************************/

            $nameRes = Part::where('name', 'LIKE', '%' . $searchKey . '%')
            ->orWhereHas('part_numbers', function ($query) use ($searchKey) {
                $query->where('number', 'LIKE', '%' . $searchKey . '%');
            })->orWhereHas('part_details', function ($query) use ($searchKey) {
                $query->where('value', 'LIKE', '%' . $searchKey . '%')->with('part_spec');
            })->orWhereHas('part_models.series.model', function ($query) use ($searchKey) {
                $query->where('name', 'LIKE', '%' . $searchKey . '%');
            })->orWhereHas('all_parts', function ($query) use ($searchKey) {
                $query->whereRaw('CONCAT("FN1", part_id, source_id, status_id, quality_id)  LIKE ?', ["%$searchKey%"]);
            })->with('part_numbers')->with('getsmallunit.unit')->with('part_details')->with('part_models.series.model')->addSelect('*', DB::raw("'Part' as type"), DB::raw("'1' as type_id"))->get();


            $numberRes = $nameRes->filter();
            /******************************* Wheels ******************************************/
            $wheelss = Wheel::where('name', 'LIKE', '%' . $searchKey . '%')->orWhereHas('wheel_material', function ($query) use ($searchKey) {
                $query->where('name', 'LIKE', '%' . $searchKey . '%');
            })->orWhereHas('wheel_dimension', function ($query) use ($searchKey) {
                $query->where('dimension', 'LIKE', '%' . $searchKey . '%');
            })
                ->orwhere('wheel_container_size', 'LIKE', '%' . $searchKey . '%')

                ->orWhereHas('all_wheels', function ($query) use ($searchKey) {
                    $query->whereRaw('CONCAT("FN2", part_id, source_id, status_id, quality_id)  LIKE ?', ["%$searchKey%"]);
                })->with('wheel_material')->with('wheel_dimension')->addSelect('*', DB::raw("'Wheel' as type"), DB::raw("'2' as type_id"))->get();

            $wheelss = $wheelss->filter();
            /******************************* tractor ******************************************/

            $tractorRes = Tractor::where('name', 'LIKE', '%' . $searchKey . '%')
                ->orwhere('tractor_number', 'LIKE', '%' . $searchKey . '%')
                ->orwhere('name_en', 'LIKE', '%' . $searchKey . '%')

                ->orwhere('motornumber', 'LIKE', '%' . $searchKey . '%')
                ->orWhereHas('all_tractors', function ($query) use ($searchKey) {
                    $query->whereRaw('CONCAT("FN3", part_id, source_id, status_id, quality_id)  LIKE ?', ["%$searchKey%"]);
                })->addSelect('*', DB::raw("'tractor' as type"), DB::raw("'3' as type_id"))->get();

            $tractorRess = $tractorRes->filter();
            /******************************* clark ******************************************/

            $clarkRes = Clark::where('name', 'LIKE', '%' . $searchKey . '%')
                ->orwhere('clark_number', 'LIKE', '%' . $searchKey . '%')
                ->orwhere('eng_name', 'LIKE', '%' . $searchKey . '%')

                ->orwhere('motor_number', 'LIKE', '%' . $searchKey . '%')
                ->orWhereHas('all_clarks', function ($query) use ($searchKey) {
                    $query->whereRaw('CONCAT("FN4", part_id, source_id, status_id, quality_id)  LIKE ?', ["%$searchKey%"]);
                })->addSelect('*', DB::raw("'Clark' as type"), DB::raw("'4' as type_id"))->get();

            $clarkRess = $clarkRes->filter();
            /******************************* equip ******************************************/

            $equipRes = Equip::where('name', 'LIKE', '%' . $searchKey . '%')
                ->orwhere('name_eng', 'LIKE', '%' . $searchKey . '%')

                ->orWhereHas('all_equips', function ($query) use ($searchKey) {
                    $query->whereRaw('CONCAT("FN5", part_id, source_id, status_id, quality_id)  LIKE ?', ["%$searchKey%"]);
                })->addSelect('*', DB::raw("'Equip' as type"), DB::raw("'5' as type_id"))->get();

            $equipRess = $equipRes->filter();

            /******************************* Kits ******************************************/

            $kits = Kit::where('name', 'LIKE', '%' . $searchKey . '%')->orWhereHas('kit_numbers', function ($query) use ($searchKey) {
                $query->where('number', 'LIKE', '%' . $searchKey . '%');
            })->orWhereHas('kit_details', function ($query) use ($searchKey) {
                $query->where('value', 'LIKE', '%' . $searchKey . '%')->with('kit_spec');
            })->orWhereHas('all_kits', function ($query) use ($searchKey) {
                $query->whereRaw('CONCAT("FN6", part_id, source_id, status_id, quality_id)  LIKE ?', ["%$searchKey%"]);
            })->with('kit_numbers')->with('kit_details')->addSelect('*', DB::raw("'Kit' as type"), DB::raw("'6' as type_id"))->get();


            return $numberRes->merge($wheelss)->merge($kits)->merge($tractorRess)->merge($clarkRess)->merge($equipRess);
        }
    }

    public function partsSearchNumber($numberVal){
        // return $res =  PartNumber::where('number','LIKE','%'.$numberVal.'%')->with(['part'=>function($query){
        //     return $query->with(['part_images','all_parts']);
        // }])->get();



        $parts = DB::table('all_parts')
        ->select( DB::raw('sum(remain_amount) as total'), 'all_parts.part_id' , 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id')
        ->join('part','all_parts.part_id','=','part.id')
        ->whereIn('part_id',(function ($query) use($numberVal) {
            $query->from('part_number')
                ->select('part_id')
                ->where('number','LIKE','%'.$numberVal.'%');
        }))
        ->where('remain_amount','>',0)
        ->groupBy('part_id','source_id','status_id','quality_id')->get();

        if(count($parts) > 0){
            return $parts->each(function ($item) {
                $item->part = part::where('id',$item->part_id)->with(['part_images','part_models'=> function ($query) {
                            $query->with(['series'=>function($ser){
                                $ser->with(['model'=>function($brand){
                                    $brand->with('brand');
                                }]);
                            }]);
                        },'part_numbers','part_details'=>function($query){
                            $query->with('part_spec');
                        }])->get();
                $item->source = Source::where('id',$item->source_id)->get();
                $item->status = Status::where('id',$item->status_id)->get();
                $item->part_quality = PartQuality::where('id',$item->quality_id)->get();
                $item->price = SalePricing::where('to',NULL)->where('part_id',$item->part_id)->where('source_id',$item->source_id)->where('status_id',$item->status_id)->where('quality_id',$item->quality_id)->with('sale_type')->get();
                $item->allpart = AllPart::where('part_id',$item->part_id)->where('source_id',$item->source_id)->where('status_id',$item->status_id)->where('quality_id',$item->quality_id)->get();
                $item->stores = $this->PartInStoresCount($item->part_id,$item->source_id,$item->status_id,$item->quality_id);
            });
        }else{
             $pn =  PartNumber::where('number','LIKE','%'.$numberVal.'%')->select(['part_id'])->get()->toArray();
            $SysParts = Part::whereIn('id',$pn)->get();
            return $SysParts->each(function ($item) {
                $item->part = part::where('id',$item->id)->with(['part_images','part_models'=> function ($query) {
                            $query->with(['series'=>function($ser){
                                $ser->with(['model'=>function($brand){
                                    $brand->with('brand');
                                }]);
                            }]);
                        },'part_numbers','part_details'=>function($query){
                            $query->with('part_spec');
                        }])->get();
                $item->source = [];
                $item->status =  [];
                $item->part_quality =  [];
                $item->price =  [];
                $item->allpart =  [];
                $item->stores = 0;
            });
        }





    }

    public function partsSearchName($numberVal){



        $parts = DB::table('all_parts')
        ->select( DB::raw('sum(remain_amount) as total'), 'all_parts.part_id' , 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id')
        ->join('part','all_parts.part_id','=','part.id')
        // ->whereIn('part_id',(function ($query) use($numberVal) {
        //     $query->from('part')
        //         ->select('id')
        //         ->where('name','CONTAIN','%'.$numberVal.'%');
        // }))
        ->where('part.name','LIKE','%'.$numberVal.'%')
        ->where('remain_amount','>',0)
        ->groupBy('part_id','source_id','status_id','quality_id')->get();

        // return $parts;
        return $parts->each(function ($item) {
            $item->part = part::where('id',$item->part_id)->with(['part_images','part_models'=> function ($query) {
                        $query->with(['series'=>function($ser){
                            $ser->with(['model'=>function($brand){
                                $brand->with('brand');
                            }]);
                        }]);
                    },'part_numbers','part_details'=>function($query){
                        $query->with('part_spec');
                    }])->get();
            $item->source = Source::where('id',$item->source_id)->get();
            $item->status = Status::where('id',$item->status_id)->get();
            $item->part_quality = PartQuality::where('id',$item->quality_id)->get();
            $item->price = SalePricing::where('to',NULL)->where('part_id',$item->part_id)->where('source_id',$item->source_id)->where('status_id',$item->status_id)->where('quality_id',$item->quality_id)->with('sale_type')->get();
            $item->allpart = AllPart::where('part_id',$item->part_id)->where('source_id',$item->source_id)->where('status_id',$item->status_id)->where('quality_id',$item->quality_id)->get();
            $item->stores = $this->PartInStoresCount($item->part_id,$item->source_id,$item->status_id,$item->quality_id);
        });




    }

    public function partsubgroup($groupid){
        return SubGroup::where('group_id',$groupid)->get();
    }
    public function indexWithRequestold(Request $request)
    {

        if ($request->ajax()) {
            $data = Part::with('sub_group')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('subGroup',function($row){
                        return isset($row->sub_group->name) ? $row->sub_group->name : '--';
                    })
                    ->addColumn('group',function($row){
                        return isset($row->sub_group->id) ? Group::where('id',$row->sub_group->group_id)->get()[0]->name : '--';
                    })
                    ->addColumn('models',function($row){
                        return isset($row->part_models) ? count($row->part_models) : 0;
                    })
                    ->addColumn('Image',function($row){
                        $partImage = PartImage::where('part_id',$row->id)->get();
                        $html="";
                        foreach ($partImage as $key => $value) {
                            $html = $html . '<img class="rounded-circle header-profile-user" src="assets/part_images/'. $value->image_name.'" alt="Emara">';
                        }
                        return $html;
                    })
                    ->addColumn('Number',function($row){
                        $pnumber= PartNumber::where('part_id',$row->id)->get();
                        $html = '<ul>';
                        foreach ($pnumber as $key => $value) {
                            if($value->flag_OM == "1"){
                                $html .='<li class="text-info">'.$value->number.'</li>';
                            }else{
                                $html .='<li>'.$value->number.'</li>';
                            }
                        }
                        $html .= '</ul>';
                        return $html;
                    })
                    ->addColumn('action', function($row){



                    //     return `
                    //     <div class="dropdown">
                    //         <button id="closeCard2" style="padding: 2px 8px" type="button" aria-expanded="false" class="btn btn-info btn-sm" data-toggle="dropdown" aria-haspopup="true">
                    //             <i class="fa fa-ellipsis-v"></i>
                    //         </button>
                    //         <div aria-labelledby="closeCard2" class="dropdown-menu dropdown-menu-right has-success">
                    //             <a href="#" class="dropdown-item text-info"><i class="fa fa-edit"></i>&nbsp;&nbsp;Edit</a>
                    //             <a href="#" class="dropdown-item text-danger"><i class="fa fa-edit"></i>&nbsp;&nbsp;Delete</a>
                    //         </div>
                    //     </div>
                    // `;

                        $btn = '<a  data-bs-toggle="modal"  part_id_value="'.$row->id.'"  data-bs-target="#editPart"
                        data-toggle="tooltip" data-original-title="Edit" class="btn btn-primary btn-sm d-inline-block editpartB "><i class="ri-edit-line editPartButton"></i></a>';
                        $btn = $btn.' <a  data-bs-toggle="modal"  part_id_value2="'.$row->id.'"  data-bs-target="#deletepartB"
                        data-toggle="tooltip" data-original-title="Delete" class="btn btn-danger btn-sm d-inline-block deletepartB "><i class="ri-delete-bin-3-line deletePartButton"></i></a>';


                            return $btn;
                    })
                    ->rawColumns(['Image','Number','group','subGroup','action','models'])
                    ->make(true);
        }

        return view('parts');
    }

    public function indexWithRequest(Request $request)
    {
        // return $request;
        if ($request->ajax()) {
            // Eager load sub_group and part_models to avoid repeated queries
            $data = Part::with(['sub_group.group', 'part_models', 'part_images', 'part_numbers']);

            return Datatables::of($data)
                ->addIndexColumn()

                ->filter(function ($query) use ($request) {
                    if ($request->has('search.value')) {
                        $search = $request->input('search.value');

                        $query->where('name', 'like', "%{$search}%")
                            ->orWhereHas('sub_group', function ($q) use ($search) {
                                $q->where('name', 'like', "%{$search}%")
                                    ->orWhereHas('group', function ($q2) use ($search) {
                                        $q2->where('name', 'like', "%{$search}%");
                                    });
                            })
                            ->orWhereHas('part_numbers', function ($q) use ($search) {
                                $q->where('number', 'like', "%{$search}%");
                            });
                    }
                })

                ->addColumn('subGroup', function ($row) {
                    return $row->sub_group->name ?? '--';
                })

                ->addColumn('group', function ($row) {
                    return $row->sub_group->group->name ?? '--';
                })

                ->addColumn('models', function ($row) {
                    return $row->part_models->count();
                })

                ->addColumn('Image', function ($row) {
                    $html = '';
                    foreach ($row->part_images as $image) {
                        $html .= '<img class="rounded-circle header-profile-user" style="width:40px;height:40px;margin:2px" src="assets/part_images/' . $image->image_name . '" alt="Part Image">';
                    }
                    return $html;
                })

                ->addColumn('Number', function ($row) {
                    $html = '<ul>';
                    foreach ($row->part_numbers as $number) {
                        $class = $number->flag_OM == "1" ? 'text-info' : '';
                        $html .= '<li class="' . $class . '">' . $number->number . '</li>';
                    }
                    $html .= '</ul>';
                    return $html;
                })

                ->addColumn('action', function ($row) {
                    $btn = '<a data-bs-toggle="modal" part_id_value="' . $row->id . '" data-bs-target="#editPart"
                            class="btn btn-primary btn-sm editpartB"><i class="ri-edit-line"></i></a>';

                    $btn .= ' <a data-bs-toggle="modal" part_id_value2="' . $row->id . '" data-bs-target="#deletepartB"
                            class="btn btn-danger btn-sm deletepartB"><i class="ri-delete-bin-3-line"></i></a>';

                    return $btn;
                })

                ->rawColumns(['Image', 'Number', 'group', 'subGroup', 'action', 'models'])
                ->make(true);
        }

        return view('parts');
    }
    
    public function indexWithID( $partId)
    {
        return $part=Part::where('id' , $partId)
        ->with(['sub_group'=>function($query){
            return $query->with('group')->get();
        }])
        ->with(['part_numbers'=>function($query){
            return $query->with('supplier')->get();
        }])
        ->with(['part_details'=>function($query){
            return $query->with(['part_spec'=>function($query1){
                return $query1->get();
            }])->get();
        }])
        ->with(['part_models'=>function($query){
            return $query->with(['series'=>function($query1){
                return $query1->with(['model'=>function($query1){
                    return $query1->with('brand_type')->with('brand')->get();
                }])->get();
            }])->get();
        }])

           ->with(['related_parts'=>function($query6){
            return $query6->with('part')->get();
        }])


        ->with('part_images')->get();

    }


    public function partspecs(){
        return PartSpec::where('general_flag','0')->where('type_id','1')->get();
    }
    public function partBrand(){
        $brandType = BrandType::all();
        $brand = Brand::orderBy('name', 'asc')->get();
        return[$brandType , $brand];
    }

    public function partmodel($brandId,$btypeid){
        return Model::where('brand_id',$brandId)->where('type_id',$btypeid)->get();
    }

    public function partseries($modelId){
        $res =  Series::where('model_id',$modelId)->with('model')->get();
        return $res;
    }

    public function partSearchSeries($seriesId){
        $parts =  PartModel::where('model_id',$seriesId)->select(['part_id'])->get()->toArray();
        $subgroups = Part::whereIn('id', $parts)->select(['sub_group_id'])->distinct()->get()->toArray();
        $groups = SubGroup::whereIn('id', $subgroups)->with('group')->select(['group_id'])->distinct()->get();
        // return $res1 = $res->sub_group();
        return $groups;
    }

    public function partSearchWithId($partId){

        return Part::where('id',$partId)
        ->with(['sub_group' => function ($query) {
             $query->with('group');
        }])
        ->with('all_parts')
        ->with(['part_details'=> function ($query) {
            $query->with('part_spec');
        }])
        ->with('part_images')
        ->with(['part_models'=> function ($query) {
            $query->with(['series'=>function($ser){
                $ser->with(['model'=>function($brand){
                    $brand->with('brand');
                }]);
            }]);
        }])
        ->with('part_numbers')
        ->with(['related_parts'=>function($query){
            $query->with('part');
        }])->get();
    }

    public function partUnderSubGroup($subgroupID){

        $parts = DB::table('all_parts')
        ->select( DB::raw('sum(remain_amount) as total'), 'all_parts.part_id' , 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id')
        ->join('part','all_parts.part_id','=','part.id')
        ->whereIn('part_id',(function ($query) use($subgroupID) {
            $query->from('part')
                ->select('id')
                ->where('sub_group_id','=',$subgroupID);
        }))
        ->where('remain_amount','>',0)
        ->groupBy('part_id','source_id','status_id','quality_id')->get();


        return $parts->each(function ($item) {
            $item->part = part::where('id',$item->part_id)->with(['part_images','part_models'=> function ($query) {
                        $query->with(['series'=>function($ser){
                            $ser->with(['model'=>function($brand){
                                $brand->with('brand');
                            }]);
                        }]);
                    },'part_numbers','part_details'=>function($query){
                        $query->with('part_spec');
                    }])->get();
            $item->source = Source::where('id',$item->source_id)->get();
            $item->status = Status::where('id',$item->status_id)->get();
            $item->part_quality = PartQuality::where('id',$item->quality_id)->get();
            $item->price = SalePricing::where('to',NULL)->where('part_id',$item->part_id)->where('source_id',$item->source_id)->where('status_id',$item->status_id)->where('quality_id',$item->quality_id)->with('sale_type')->get();
            $item->allpart = AllPart::where('part_id',$item->part_id)->where('source_id',$item->source_id)->where('status_id',$item->status_id)->where('quality_id',$item->quality_id)->get();
            $item->stores = $this->PartInStoresCount($item->part_id,$item->source_id,$item->status_id,$item->quality_id);
        });



    }

    public function brandUnderSupplier($supplierId,$typeId){
        $parts = DB::table("all_parts")
        ->join("order_supplier", function($join){
            $join->on("all_parts.order_supplier_id", "=", "order_supplier.id");
        })
        ->select("all_parts.part_id")->distinct()
        ->where("order_supplier.supplier_id", "=", $supplierId)
        ->get();

        $allPartSeries =  PartModel::whereIn('part_id',$parts->pluck('part_id'))->select('model_id')->distinct()->get()->toArray();

        $model =  Series::whereIN('id',$allPartSeries)->select('model_id')->distinct()->get()->toArray();
        $brand =  Model::where('type_id',$typeId)->whereIN('id',$model)->select('brand_id')->distinct()->get()->toArray();



        return Brand::whereIn('id',$brand)->get();

    }
    public function brandTypeUnderSupplier($supplierId){
        $parts = DB::table("all_parts")
        ->join("order_supplier", function($join){
            $join->on("all_parts.order_supplier_id", "=", "order_supplier.id");
        })
        ->select("all_parts.part_id")->distinct()
        ->where("order_supplier.supplier_id", "=", $supplierId)
        ->get();

        // return $parts;
        $allPartSeries =  PartModel::whereIn('part_id',$parts->pluck('part_id'))->select('model_id')->distinct()->get()->toArray();

        $model =  Series::whereIN('id',$allPartSeries)->select('model_id')->distinct()->get()->toArray();

        $brand_type =  Model::whereIN('id',$model)->select('type_id')->distinct()->get()->toArray();

        return BrandType::whereIn('id',$brand_type)->get();
    }
    public function ModelUnderSupplier($supplierId,$brandId){
        $parts = DB::table("all_parts")
        ->join("order_supplier", function($join){
            $join->on("all_parts.order_supplier_id", "=", "order_supplier.id");
        })
        ->select("all_parts.part_id")->distinct()
        ->where("order_supplier.supplier_id", "=", $supplierId)
        ->get();

        $allPartSeries =  PartModel::whereIn('part_id',$parts->pluck('part_id'))->select('model_id')->distinct()->get()->toArray();


        $model =  Series::whereIN('id',$allPartSeries)->select('model_id')->distinct()->get()->toArray();

        $models = Model::where('brand_id',$brandId)->whereIN('id',$model)->get();

        return $models;
    }
    public function SeriesUnderSupplier($supplierId,$seriesId){
        $parts = DB::table("all_parts")
        ->join("order_supplier", function($join){
            $join->on("all_parts.order_supplier_id", "=", "order_supplier.id");
        })
        ->select("all_parts.part_id")->distinct()
        ->where("order_supplier.supplier_id", "=", $supplierId)
        ->get();

        $allPartSeries =  PartModel::whereIn('part_id',$parts->pluck('part_id'))->select('model_id')->distinct()->get()->toArray();

        $series = Series::where('model_id',$seriesId)->whereIN('id',$allPartSeries)->get();

        return $series;

    }
    public function PartUnderSupplier($supplierId){

        $parts = DB::table('all_parts')
        ->select( DB::raw('sum(remain_amount) as total'), 'all_parts.part_id' , 'all_parts.source_id', 'all_parts.status_id', 'all_parts.quality_id')
        ->join('part','all_parts.part_id','=','part.id')
        ->whereIn('order_supplier_id',(function ($query) use($supplierId) {
            $query->from('order_supplier')
                ->select('id')
                ->where('supplier_id','=',$supplierId);
        }))
        ->where('remain_amount','>',0)
        ->groupBy('part_id','source_id','status_id','quality_id')->get();

        return $parts->each(function ($item) {
            $item->part = part::where('id',$item->part_id)->with(['part_images','part_models'=> function ($query) {
                        $query->with(['series'=>function($ser){
                            $ser->with(['model'=>function($brand){
                                $brand->with('brand');
                            }]);
                        }]);
                    },'part_numbers','part_details'=>function($query){
                        $query->with('part_spec');
                    }])->get();
            $item->source = Source::where('id',$item->source_id)->get();
            $item->status = Status::where('id',$item->status_id)->get();
            $item->part_quality = PartQuality::where('id',$item->quality_id)->get();
            $item->price = SalePricing::where('to',NULL)->where('part_id',$item->part_id)->where('source_id',$item->source_id)->where('status_id',$item->status_id)->where('quality_id',$item->quality_id)->with('sale_type')->get();
            $item->allpart = AllPart::where('part_id',$item->part_id)->where('source_id',$item->source_id)->where('status_id',$item->status_id)->where('quality_id',$item->quality_id)->get();
            $item->stores = $this->PartInStoresCount($item->part_id,$item->source_id,$item->status_id,$item->quality_id);


        });



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

        // dd($request->file('partImg')[0]->getClientOriginalName());
        // return $request;
        $part = new part();
        $part->name = $request->partarname;
        $part->name_eng = $request->partengname;
        $part->description = $request->partdesc;
        $part->sub_group_id = $request->SgroupSlct;
        $part->limit_order = $request->limit;
        $part->small_unit = $request->small_unit_a;
        $part->big_unit = $request->big_unit_a;
        if(isset($request->notify)){
            $part->flage_limit_order = '1';
        }else{
            $part->flage_limit_order = '0';
        }
        $part->insertion_date = Carbon::now();
        $part->save();



        for ($i=0 ; $i < count($request->partNumber) ; $i++) {

            if($request->partNumber[$i] == null){
                continue;
            }
            $partnumbers = new PartNumber();
            $partnumbers->part_id = $part->id;
            $partnumbers->number = $request->partNumber[$i];
            $partnumbers->supplier_id = $request->supplierSlct[$i];
            if($request->supplierSlct[$i] == 13){
                $partnumbers->flag_OM = 1;
            }else{
                $partnumbers->flag_OM = 0;
            }
            $partnumbers->save();
            // return $partnumbers;
        }

        if(isset($request->specs)){
            for ($i=0; $i < count($request->specs) ; $i++) {
                # code...
                $specs = new PartSpec();
                $specs->name = $request->specs[$i];
                $specs->general_flag = $part->id;
                $specs->save();

                $specDetail = new PartDetail();
                $specDetail->part_id = $part->id;
                $specDetail->partspecs_id = $specs->id;
                if(isset($request->specsval)){
                $specDetail->value = $request->specsval[$i];
                // $specDetail->save();
                }
                $specDetail->save();

            }
        }


        if(isset($request->oldSpecs)){
            for ($i=0; $i < count($request->oldSpecs) ; $i++) {
                # code...
                if(isNull($request->oldspecsval[0])){
                    break;
                }
                $specDetail = new PartDetail();
                $specDetail->part_id = $part->id;
                $specDetail->partspecs_id = $request->oldSpecs[$i];
                $specDetail->value = $request->oldspecsval[$i];
                $specDetail->save();
            }
        }


        if(isset($request->series)){
            for ($i=0; $i < count($request->series) ; $i++) {
                # code...
                if($request->series[$i] !=null){
                    $series = new partmodel();
                    $series->part_id = $part->id;
                    $series->model_id = $request->series[$i];
                    $series->save();
                }

            }
        }
           if(isset($request->relatedPart)){
            for ($i=0; $i < count($request->relatedPart) ; $i++) {
                RelatedPart::create(['part_id' => $part->id , 'sug_part_id' => $request->relatedPart[$i]]);
            }
        }

        if(isset($request->partImg)){

            for ($i=0; $i < count($request->partImg) ; $i++) {

                $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->partImg[$i]->getClientOriginalName());
                $imageName = time() . $namewithoutchar.'.'.$request->partImg[$i]->extension() ;
                $request->partImg[$i]->move(public_path('assets/part_images'), $imageName);

                $partImg = new PartImage();
                $partImg->part_id = $part->id;
                $partImg->name = $namewithoutchar;
                $partImg->image_name = $imageName;
                $partImg->save();
            }
        }

        return back()
        ->with('success','You have successfully Save Part.');

        // return $part;
    }

    /**
     * Display the specified resource.
     */
    public function show(Part $part)
    {
        //
        return $part;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Part $part)
    {
        return($part);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $pid)
    {
        // return $pid;
         $part=Part::where('id',$pid)->first();
        $part_id= $part->id;
        $part->part_models()->delete();
        $part->part_details()->delete();
        $partSpecs= PartSpec::where('general_flag' , $part->id)->get();
        if(count($partSpecs)>0){
            $partSpecs->each->delete();
            }
        $part->part_numbers()->delete();
         for($i = 0 ; $i< count(explode(',',$request->imgURLsInp[0])) ; $i++){
            if(explode(',',$request->imgURLsInp[0])[$i] != null){
                $imageN = explode(',',$request->imgURLsInp[0])[$i];
                $imageN = preg_replace('/\s+/','',$imageN);

                $part->part_images()->where('image_name',$imageN )->delete();
            }
        }
        // for($i = 0 ; $i< count($request->imgURLsInp) ; $i++){
        //     if($request->imgURLsInp[$i] != null){
        //         $part->part_images()->where('image_name', $request->imgURLsInp[$i])->delete();
        //     }
        // }

         $relatedPart= RelatedPart::where('part_id' ,$part->id);
        if(isset($relatedPart)){
            $relatedPart->delete();
        }
        if(isset($request->notifyEdit)){
            $part->update(['name'=> $request->nameedit , 'name_eng' => $request->engnameedit , 'description' => $request->notesedit , 'sub_group_id' => $request->SgroupSlctEdit , 'limit_order' => $request->limitedit , 'flage_limit_order' => '1' ]);
        }else{
            $part->update(['name'=> $request->nameedit , 'name_eng' => $request->engnameedit , 'description' => $request->notesedit , 'sub_group_id' => $request->SgroupSlctEdit , 'limit_order' => $request->limitedit , 'flage_limit_order' => '0' ]);
        }
        if(isset($request->nameedit)){
            $part->update(['name'=> $request->nameedit , 'name_eng' => $request->engnameedit , 'description' => $request->descedit , 'sub_group_id' => $request->SgroupSlctEdit ]);
        }
        if(isset($request->small_unit_e)){
            $part->update(['small_unit'=> $request->small_unit_e ]);
        }
        if(isset($request->big_unit_e)){
            $part->update(['big_unit'=> $request->big_unit_e  ]);
        }
        if($request->partNumberEdit){
            for ($i=0 ; $i < count($request->partNumberEdit) ; $i++) {
                $number = $request->partNumberEdit[$i];
                $supplier_id = $request->partsupplierSlctEdit[$i];
                if($request->partsupplierSlctEdit[$i] == 13){
                    $flag_OM = 1;
                    }else{
                        $flag_OM = 0;
                    }
                PartNumber::create(['number' => $number , 'flag_OM' => $flag_OM,'supplier_id'=> $supplier_id , 'part_id' => $part_id]);
            }
        }
        if(isset($request->relatedPartEdit)){
            for ($i=0; $i < count($request->relatedPartEdit) ; $i++) {
                RelatedPart::create(['part_id' => $part->id , 'sug_part_id' => $request->relatedPartEdit[$i]]);
            }
        }
        if(isset($request->specsEdit)){

            for ($i=0; $i < count($request->specsEdit) ; $i++) {
                $specsEdit = new PartSpec();
                $specsEdit->name = $request->specsEdit[$i];
                $specsEdit->general_flag = $part_id;
                $specsEdit->save();
                $specDetail = new PartDetail();
                $specDetail->partspecs_id = $specsEdit->id;
                $specDetail->part_id = $part->id;
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
                    PartDetail::create(['partspecs_id'=> $request->oldSpecsEdit[$j] ,'part_id'=> $part_id ,'value' => $request->oldspecsvalEdit[$j]]);
                }else{
                    continue;
                }
            }
        }

        if(isset($request->seriesEdit)){
            for ($i=0; $i < count($request->seriesEdit) ; $i++) {
                if($request->seriesEdit[$i] !=null){
                    PartModel::create(['part_id' => $part_id , 'model_id' => $request->seriesEdit[$i]]);
                }

            }
        }

        if($request->partImgEdit){
            for ($i=0; $i < count($request->partImgEdit) ; $i++) {
                $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->partImgEdit[$i]->getClientOriginalName());
                $imageName = time() . $namewithoutchar.'.'.$request->partImgEdit[$i]->extension() ;
                $request->partImgEdit[$i]->move(public_path('assets/part_images'), $imageName);
                PartImage::create(['part_id' => $part_id , 'image_name' => $imageName]);

            }
        }
        return back()->with('success','You Have Successfully Updated Part.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,Part $part){

            $part = Part::where('id', $request->part_id)->first();
            $item_in_invoice = Replyorder::where('part_id', $request->part_id)->where('part_type_id',1)->get();
            $item_in_kit = KitPart::where('part_id', $request->part_id)->get();
            //   return $part->part_images()->get('image_name');
        $image_name = $part->part_images()->get('image_name');
        for($i=0 ; $i < count($image_name) ; $i++){
            $image_path = public_path('assets/part_images/'.$image_name);
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
        }
        if(count($item_in_invoice) == 0 && count($item_in_kit) == 0 ){
            $part->part_images()->delete();
            $part->part_models()->delete();
            $part->part_details()->delete();

            $partSpecs= PartSpec::where('general_flag' , $part->id)->get();
            if(count($partSpecs)>0){
            $partSpecs->each->delete();
                }
            $part->part_numbers()->delete();
            $part->delete();
            return back()->with('success','You Have Successfully Deleted Part.');
        }else{
            return back()->with('error','You cant Deleted this Part.');

        }



    }


    public function customSearch(){

        $brand = Brand::all();
        $type = BrandType::all();
        $model = Model::all();
        $series = Series::all();
        $supplier = Supplier::all();
        $group = Group::all();
        $subgroup = SubGroup::all();
        $stores = Store::all();
        return View('partSearch' , compact('stores','brand','type','model','series','supplier','group','subgroup'));
    }

    public function customSearchResult(Request $request){
        return $request;
    }

    public function PartInStoresCount($partId , $sourceId,$statusId,$qualityId){

        // get all stores
        $stores = Store::all();

        return $stores->each(function ($item) use ($partId , $sourceId,$statusId,$qualityId){

            // $storeClass = 'App\Models\\'.ucfirst($item->table_name);
            $item->storepart = DB::table($item->table_name)
            ->select($item->table_name.'.*')
            ->join('stores_log',$item->table_name.'.store_log_id','=','stores_log.id')
            ->join('all_parts','stores_log.All_part_id','=','all_parts.id')
            ->where('all_parts.part_id','=',$partId)
            ->where('all_parts.source_id','=',$sourceId)
            ->where('all_parts.status_id','=',$statusId)
            ->where('all_parts.quality_id','=',$qualityId)
            ->where('stores_log.status','=',3)
            ->get();

            $item->storepartCount = DB::table($item->table_name)
            ->select('*')
            ->join('stores_log',$item->table_name.'.store_log_id','=','stores_log.id')
            ->join('all_parts','stores_log.All_part_id','=','all_parts.id')
            ->where('all_parts.part_id','=',$partId)
            ->where('all_parts.source_id','=',$sourceId)
            ->where('all_parts.status_id','=',$statusId)
            ->where('all_parts.quality_id','=',$qualityId)
            ->where('stores_log.status','=',3)
            ->sum($item->table_name.'.amount');


        });


    }

    public function checkout(Request $request){
        // return $request;
        $invoiceItems = json_decode($request->inv_data);
        $invoicePrice = $request->inv_price;

        if($invoiceItems == null ){
            $invoiceItems = [];
            $invoicePrice = 0;
            $clients = [];
            $stores = [];
            $taxes = [];
            return view('checkout',compact(['invoiceItems','invoicePrice','clients','stores','taxes']));
        }
        foreach ($invoiceItems as $key => $item) {
            $item->source = Source::where('id',$item->sourceId)->get();
            $item->status = Status::where('id',$item->statusId)->get();
            $item->quality = PartQuality::where('id',$item->qualityId)->get();
            $item->price1 = SalePricing::where('to',NULL)->where('part_id',$item->partId)->where('source_id',$item->sourceId)->where('status_id',$item->statusId)->where('quality_id',$item->qualityId)->with('sale_type')->orderBy('price', 'asc')->get();
            $item->stores = $this->PartInStoresCount($item->partId,$item->sourceId,$item->statusId,$item->qualityId);
        }

        $clients = Client::get();
        $stores = Store::all();
        $taxes = Tax::all();
        // return $clients;
        return view('checkout',compact(['invoiceItems','invoicePrice','clients','stores','taxes']));
    }

    public function saveInv(Request $request){
        // return $request;

        $invoice = new Invoice();

        $invoice->name = Carbon::now();
        $invoice->casher_id = "28";
        $invoice->discount = $request->invDiscount;
        $invoice->actual_price = $request->total;
        $invoice->client_id = $request->clientsSlct;
        $invoice->company_id ="10";
        $invoice->store_id = $request->storeId;
        $invoice->price_without_tax = $request->subtotal;
        $invoice->tax_amount = $request->total_tax;
        $invoice->paied = $request->invPaied;
        $invoice->date = Carbon::now();
        $invoice->save();

        /// invoice Tax /////////////////

        //////////////////////////////////////////////
        $inv_items = $request->items_part;
        for ($i=0; $i < count($inv_items) ; $i++) {

            $item = explode ("-", $inv_items[$i]) ;
            $part_id = $item[0];
            $source_id = $item[1];
            $status_id = $item[2];
            $quality_id = $item[3];
            $amount = $item[4];

            $invoiceItems = new InvoiceItem();
            $invoiceItems->date = Carbon::now();

            $invoiceItems->part_id = $part_id;
            $invoiceItems->amount = $amount;
            $invoiceItems->source_id = $source_id;
            $invoiceItems->status_id = $status_id;
            $invoiceItems->quality_id = $quality_id;
            $invoiceItems->part_type_id = "1";
            $invoiceItems->invoice_id = $invoice->id;
            $saleTypeName = 'price'.$i;
            $invoiceItems->sale_type = $request->$saleTypeName[0];
            $invoiceItems->save();


            //// remove from store ///////////////////

            $allparts = AllPart::where('remain_amount','>',0)->where('part_id',$part_id)->where('source_id',$source_id)->where('status_id',$status_id)->where('quality_id',$quality_id)->orderBy('id', 'ASC')->get();

            $store = Store::where('id',$request->storeId)->get();
            $store_id = $store[0]->id;
            $store_name = $store[0]->name;
            $store_table_name = $store[0]->table_name;

            try {

                DB::table($store_table_name)->where('part_id', $part_id)->where('supplier_order_id', $allparts[0]->order_supplier_id)->where('type_id', 1)->decrement('amount', $amount);
            } catch (\Throwable $th) {
                //throw $th;
            }

            /////////////////////////////////////


            ////// remove from all parts ////////

            AllPart::where('remain_amount','>',0)->where('part_id',$part_id)->where('source_id',$source_id)->where('status_id',$status_id)->where('quality_id',$quality_id)->orderBy('id', 'ASC')->decrement('remain_amount',$amount);

            //////////////////////////////////////
        }


        return redirect()->to('printInvoice/'.$invoice->id);
    }

    public function printInvoiceold(Invoice $id){
        $invoice =  $id;
        $invoiceItemspart = InvoiceItem::where('invoice_id', $id->id)->where('part_type_id','1')->with('part')->with('source')->with('status')->with('part_quality')->get();
        $invoiceItemskit = InvoiceItem::where('invoice_id', $id->id)->where('part_type_id','6')->with('kit')->with('source')->with('status')->with('part_quality')->get();
        $invoiceItemswheel = InvoiceItem::where('invoice_id', $id->id)->where('part_type_id','2')->with('wheel')->with('source')->with('status')->with('part_quality')->get();
        $invoiceItemstractor = InvoiceItem::where('invoice_id', $id->id)->where('part_type_id','3')->with('tractor')->with('source')->with('status')->with('part_quality')->get();
        $invoiceItemsclarks = InvoiceItem::where('invoice_id', $id->id)->where('part_type_id','4')->with('clark')->with('source')->with('status')->with('part_quality')->get();
        $invoiceItemsequips = InvoiceItem::where('invoice_id', $id->id)->where('part_type_id','5')->with('equip')->with('source')->with('status')->with('part_quality')->get();
        $invoiceItems = $invoiceItemspart->concat($invoiceItemskit)->concat($invoiceItemswheel)->concat($invoiceItemsclarks)->concat($invoiceItemstractor)->concat($invoiceItemsequips);
        // return $invoiceItems;
        foreach ($invoiceItems as $key => $Item) {
            # code...

            $Item['price']=SalePricing::where('from','<=',$Item->date)->where(function ($q) use($Item) {
                $q->where('to','>=',$Item->date)->orWhere('to', null);
            })->where('sale_type',$Item->sale_type)->where('part_id',$Item->part_id)->where('source_id',$Item->source_id)->where('status_id',$Item->status_id)->where('quality_id',$Item->quality_id)->with('sale_type')->get();

            $allpId = 0;
             if($Item->part_type_id == 1){
                 $allpart = AllPart::where('part_id',$Item->part_id)->where('source_id',$Item->source_id)->where('status_id',$Item->status_id)->where('quality_id',$Item->quality_id)->orderBy('id','Asc')->select('id')->get();
                $allpId = $allpart;
            }elseif($Item->part_type_id == 2){
                $allpart = AllWheel::where('part_id',$Item->part_id)->where('source_id',$Item->source_id)->where('status_id',$Item->status_id)->where('quality_id',$Item->quality_id)->orderBy('id','Asc')->select('id')->get();
                $allpId = $allpart;
            }elseif($Item->part_type_id == 6){
                $allpart = AllKit::where('part_id',$Item->part_id)->where('source_id',$Item->source_id)->where('status_id',$Item->status_id)->where('quality_id',$Item->quality_id)->orderBy('id','Asc')->select('id')->get();
                $allpId = $allpart;
            }elseif($Item->part_type_id == 3){
                $allpart = AllTractor::where('part_id',$Item->part_id)->where('source_id',$Item->source_id)->where('status_id',$Item->status_id)->where('quality_id',$Item->quality_id)->orderBy('id','Asc')->select('id')->get();
                $allpId = $allpart;
            }elseif($Item->part_type_id == 4){
                $allpart = AllClark::where('part_id',$Item->part_id)->where('source_id',$Item->source_id)->where('status_id',$Item->status_id)->where('quality_id',$Item->quality_id)->orderBy('id','Asc')->select('id')->get();
                $allpId = $allpart;
            }elseif($Item->part_type_id == 5){
                $allpart = AllEquip::where('part_id',$Item->part_id)->where('source_id',$Item->source_id)->where('status_id',$Item->status_id)->where('quality_id',$Item->quality_id)->orderBy('id','Asc')->select('id')->get();
                $allpId = $allpart;
            }

            $Item['refund_amount']= RefundInvoice::where('invoice_id',$id->id)->whereIn('item_id',$allpId)->sum('r_amount');
            $Item['refund_price']= RefundInvoice::where('invoice_id',$id->id)->whereIn('item_id',$allpId)->sum('item_price');
            $Item['refund_total_tax']= RefundInvoice::where('invoice_id',$id->id)->whereIn('item_id',$allpId)->sum('r_tax');



            //////new adde msalam
            //  $Item['section']= DB::table('store_section')->join('store_structure','store_structure.id','store_section.section_id')->where('part_id', $Item->part_id)->where('type_id',  $Item->part_type_id)->where('source_id',$Item->source_id)->where('status_id',$Item->status_id)->where('quality_id',$Item->quality_id)->select('store_structure.name')->get();
            $Item['section'] = DB::table('store_section')
          ->join('store_structure', 'store_structure.id', '=', 'store_section.section_id')
          ->where('part_id', $Item->part_id)
          ->where('type_id', $Item->part_type_id)
          ->where('source_id', $Item->source_id)
          ->where('status_id', $Item->status_id)
          ->where('store_structure.store_id', $invoice->store_id)
          ->where('amount','>',0)
          ->where('quality_id', $Item->quality_id)
          ->groupBy('part_id', 'source_id', 'status_id', 'quality_id', 'type_id', 'store_structure.name')
          ->select('store_structure.name')
          ->get();
            
            $item ['section2']= InvoiceItemsSection::where('invoice_item_id',$Item->id)->with('store_structure')->get();

        }


        // return $invoiceItems;
        // return Invoice::where('id',$id->id)->get();
        $paperTitle="فاتورة بيع";
        $recordName= "العميل";
        $recordValue=$invoice->client->name;
        $recoredId = $invoice->id;
        $recoredUrl = 'printInvoice/'.$recoredId;

        return View('printInvoice' , compact('invoice','invoiceItems','paperTitle','recordName','recordValue','recoredId','recoredUrl'));
    }

     public function printInvoice(Invoice $id)
    {
        $invoice =  $id;

         $presaleFlag = 0;
        if($invoice->presale_order_id){
            $presaleFlag = 1;
        }

        $invoiceItemspart = InvoiceItem::where('invoice_id', $id->id)->where('part_type_id', '1')->with('part')->with('source')->with('status')->with('part_quality')->get();
        $invoiceItemskit = InvoiceItem::where('invoice_id', $id->id)->where('part_type_id', '6')->with('kit')->with('source')->with('status')->with('part_quality')->get();
        $invoiceItemswheel = InvoiceItem::where('invoice_id', $id->id)->where('part_type_id', '2')->with('wheel')->with('source')->with('status')->with('part_quality')->get();
        $invoiceItemstractor = InvoiceItem::where('invoice_id', $id->id)->where('part_type_id', '3')->with('tractor')->with('source')->with('status')->with('part_quality')->get();
        $invoiceItemsclarks = InvoiceItem::where('invoice_id', $id->id)->where('part_type_id', '4')->with('clark')->with('source')->with('status')->with('part_quality')->get();
        $invoiceItemsequips = InvoiceItem::where('invoice_id', $id->id)->where('part_type_id', '5')->with('equip')->with('source')->with('status')->with('part_quality')->get();
        $invoiceItems = $invoiceItemspart->concat($invoiceItemskit)->concat($invoiceItemswheel)->concat($invoiceItemsclarks)->concat($invoiceItemstractor)->concat($invoiceItemsequips);
        // return $presaleFlag;
        foreach ($invoiceItems as $key => $Item) {
            if($presaleFlag == 1){
                // return $Item;
                  $Item['price'] = PresaleOrderPart::where('part_type_id', $Item->part_type_id)->where('presaleOrder_id', $invoice->presale_order_id)->where('part_id', $Item->part_id)->where('source_id', $Item->source_id)->where('status_id', $Item->status_id)->where('quality_id', $Item->quality_id)->get();
            }else{
                $Item['price'] = SalePricing::where(function ($q) use ($Item) {
                    $q->where('to', '>=', $Item->date)->orWhere('to', null);
                })->where('sale_type', $Item->sale_type)->where('part_id', $Item->part_id)
                ->where('source_id', $Item->source_id)->where('status_id', $Item->status_id)->where('quality_id', $Item->quality_id)->with('sale_type')->get();
                
                 
                //  $recordsWithValidFrom = SalePricing::where('from', '<=', $Item->date)
                //     ->where('sale_type', $Item->sale_type)
                //     ->where('part_id', $Item->part_id)
                //     ->where('source_id', $Item->source_id)
                //     ->where('status_id', $Item->status_id)
                //     ->where('quality_id', $Item->quality_id)
                //     ->with('sale_type')
                //     ->get();
                
                // // Second Query: Filter results where `to` is either `NULL` or greater than or equal to the given date
                // $Item['price'] = $recordsWithValidFrom->filter(function ($record) use ($Item) {
                //     return is_null($record->to) || $record->to >= $Item->date;
                // });
                

            }

            $allpId = 0;
            if ($Item->part_type_id == 1) {
                $allpart = AllPart::where('part_id', $Item->part_id)->where('source_id', $Item->source_id)->where('status_id', $Item->status_id)->where('quality_id', $Item->quality_id)->orderBy('id', 'Asc')->select('id')->get();
                $allpId = $allpart;
            } elseif ($Item->part_type_id == 2) {
                $allpart = AllWheel::where('part_id', $Item->part_id)->where('source_id', $Item->source_id)->where('status_id', $Item->status_id)->where('quality_id', $Item->quality_id)->orderBy('id', 'Asc')->select('id')->get();
                $allpId = $allpart;
            } elseif ($Item->part_type_id == 6) {
                $allpart = AllKit::where('part_id', $Item->part_id)->where('source_id', $Item->source_id)->where('status_id', $Item->status_id)->where('quality_id', $Item->quality_id)->orderBy('id', 'Asc')->select('id')->get();
                $allpId = $allpart;
            } elseif ($Item->part_type_id == 3) {
                $allpart = AllTractor::where('part_id', $Item->part_id)->where('source_id', $Item->source_id)->where('status_id', $Item->status_id)->where('quality_id', $Item->quality_id)->orderBy('id', 'Asc')->select('id')->get();
                $allpId = $allpart;
            } elseif ($Item->part_type_id == 4) {
                $allpart = AllClark::where('part_id', $Item->part_id)->where('source_id', $Item->source_id)->where('status_id', $Item->status_id)->where('quality_id', $Item->quality_id)->orderBy('id', 'Asc')->select('id')->get();
                $allpId = $allpart;
            } elseif ($Item->part_type_id == 5) {
                $allpart = AllEquip::where('part_id', $Item->part_id)->where('source_id', $Item->source_id)->where('status_id', $Item->status_id)->where('quality_id', $Item->quality_id)->orderBy('id', 'Asc')->select('id')->get();
                $allpId = $allpart;
            }

            $Item['refund_amount'] = RefundInvoice::where('invoice_id', $id->id)->where('item_id', $Item->id)->sum('r_amount');
            $Item['refund_price'] = RefundInvoice::where('invoice_id', $id->id)->where('item_id', $Item->id)->sum('item_price');

            $Item['refund_total_tax'] = RefundInvoice::where('invoice_id', $id->id)->where('item_id', $Item->id)->sum('r_tax');
            $Item['refund_total_discount'] = RefundInvoice::where('invoice_id', $id->id)->where('item_id', $Item->id)->sum('r_discount');



            //////new adde msalam
    //   return   $Item ['section_out']= InvoiceItemsSection::where('invoice_item_id',$Item->id)->with('store_structure')->get();
                $Item ['section_out']= InvoiceItemsSection::where('invoice_item_id',$Item->id)->where('amount','>',0)->with('store_structure')->get();

            
            $storexx = Store::where('id',$invoice->store_id)->first();
            
            $Item['section'] = DB::table('store_section')
          ->join('store_structure', 'store_structure.id', '=', 'store_section.section_id')
          ->where('part_id', $Item->part_id)
          ->where('type_id', $Item->part_type_id)
          ->where('source_id', $Item->source_id)
          ->where('status_id', $Item->status_id)
          ->where('quality_id', $Item->quality_id)
          ->where('store_section.store_id', $storexx->id)
          ->where('store_structure.store_id', $invoice->store_id)
          ->where('amount','>',0)
          ->groupBy('part_id', 'source_id', 'status_id', 'store_section.store_id', 'quality_id', 'type_id', 'store_structure.name')
          ->select('store_structure.name')
          ->get();
          
           
          
           
            // $Item['section'] = DB::table('store_section')->join('store_structure', 'store_structure.id', 'store_section.section_id')->where('part_id', $Item->part_id)->where('type_id',  $Item->part_type_id)->where('source_id', $Item->source_id)->where('status_id', $Item->status_id)->where('quality_id', $Item->quality_id)->select('store_structure.name')->get();
        }

        $invoice['refund_price_total_paied'] = RefundInvoicePayment::where('invoice_id', $id->id)->sum('total_paied');
        $invoice['refund_price_total'] = RefundInvoicePayment::where('invoice_id', $id->id)->sum('paied');
        $invoice['refund_price_tax_total'] = RefundInvoicePayment::where('invoice_id', $id->id)->sum('total_tax');
        $invoice['refund_price_discount_total'] = RefundInvoicePayment::where('invoice_id', $id->id)->sum('total_dicount');
        // return $invoiceItems;
        // return Invoice::where('id',$id->id)->get();
        $paperTitle = "فاتورة بيع";
        $recordName = "العميل";
        $recordValue = $invoice->client->name;
        $recoredId = $invoice->id;
        $recoredUrl = 'printInvoice/' . $recoredId;

        // return $invoiceItems;
        return View('printInvoice', compact('presaleFlag','invoice', 'invoiceItems', 'paperTitle', 'recordName', 'recordValue', 'recoredId', 'recoredUrl'));
    }
    
    public function importNumbers(Request $request)
    {
        // Validate the file input
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
    
        // Clear previous errors if any
        PartNumbersImport::$errors = [];
    
        // Import the file
        Excel::import(new PartNumbersImport, $request->file('file'));
    
        // Get any errors that occurred during the import
        $errors = PartNumbersImport::getErrors();
    
        // If there are errors, return them
        if (count($errors) > 0) {
            return redirect()->back()->with('error', 'Import failed with the following errors: <br>' . implode('<br>', $errors));
        }
    
        // Success message if no errors
        return redirect()->back()->with('success', 'Imported successfully!');
    }
    


    public function importPartNumbersIndex()
    {
        $partNumbers = PartNumber::with('part')->get();
        return view('part_numbers.index', compact('partNumbers'));
    }
    
    public function newProduct()
    {
        return View('newproducts');
    }
    public function newProductData()
    {
    //     $results = DB::select("
    //     SELECT *
    //     FROM (
    //         SELECT 
    //             *,
    //             ROW_NUMBER() OVER (
    //                 PARTITION BY part_id, source_id, status_id, quality_id
    //                 ORDER BY insertion_date DESC
    //             ) AS row_num
    //         FROM all_parts
    //     ) AS ranked
    //     WHERE row_num = 1 AND remain_amount > 0
    //     ORDER BY insertion_date DESC
    //     LIMIT 10
    // ");
        $results = DB::select("
                    SELECT *
                            FROM all_parts AS p
                            WHERE remain_amount > 0
                            AND insertion_date = (
                                SELECT MAX(p2.insertion_date)
                                FROM all_parts AS p2
                                WHERE p2.part_id = p.part_id
                                    AND p2.source_id = p.source_id
                                    AND p2.status_id = p.status_id
                                    AND p2.quality_id = p.quality_id
                            )
                            ORDER BY insertion_date DESC
                            LIMIT 10;
                ");

        $results = collect($results);
        foreach ($results as $part) {
            $part->stores = $this->PartInStoresCount($part->part_id, $part->source_id, $part->status_id, $part->quality_id);
            $part->sumremainamount = AllPart::where('part_id', $part->part_id)->where('source_id', $part->source_id)
                ->where('status_id', $part->status_id)
                ->where('quality_id', $part->quality_id)
                ->sum('remain_amount');

            $part->counts = AllPart::where('part_id', $part->part_id)->where('source_id', $part->source_id)
                ->where('status_id', $part->status_id)
                ->where('quality_id', $part->quality_id)
                ->count();

            $part->part = Part::find($part->part_id);
            $part->source = Source::find($part->source_id);
            $part->status = Status::find($part->status_id);
            $part->quality = PartQuality::find($part->quality_id);
        }

        return DataTables::of($results)
            ->addIndexColumn()
            ->toJson();
    }

}
