<?php

namespace App\Http\Controllers;

use App\Models\Tractor;
use App\Models\TractorNumber;
use App\Models\RelatedTractor;
use App\Models\TractorSpec;
use App\Models\Series;
use App\Models\TractorImage;
use App\Models\EfragImage;
use App\Models\TractorDetail;
use App\Models\BrandType;
use App\Models\Brand;
use App\Models\Model;
use App\Models\BuyTransaction;
use App\Models\OrderSupplier;
use App\Models\Replyorder;
use App\Models\AllTractor;
use App\Models\BankSafeMoney;
use App\Models\BankType;
use App\Models\CurrencyType;
use App\Models\Store;
use App\Models\StoresLog;
use App\Models\InvoiceImage;
use App\Models\MoneySafe;
use App\Models\Qayd;
use App\Models\QaydDeleted;
use App\Models\Qayditem;
use App\Models\QayditemDeleted;
use App\Models\SanadSarf;
use App\Models\StoreSection;
use App\Models\Supplier;
use File;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TractorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexWithID(Tractor $tractorId)
    {

        $all_tractor = AllTractor::where('part_id' , $tractorId->id)->with('sections')->get();
        $tractor=Tractor::where('id' , $tractorId->id)
        ->with(['all_tractors'=>function($query){
                return $query->with(['order_supplier'=>function($query1){
                    return $query1->with(['replyorders'=>function($query2){
                        return $query2->get();
                }])->get();
                }])->with('sections');
            }])

        ->with(['tractor_details'=>function($query3){
            return $query3->with(['tractor_spec'=>function($query4){
                return $query4->get();
            }])->get();
        }])
        ->with(['series'=> function($query8){
            return $query8->with(['model'=>function($query9){
                return $query9->with('brand')->with('brand_type')->get();
            }])->get();
        }])
        ->with('tractor_images')
        ->with('efrag_images')
        ->with(['invoice_images'=>function($query5){
            return $query5->where('part_type_id' , '3')->get();
        }])
        ->with(['related_tractors'=>function($query6){
            return $query6->with(['parts'=>function($query7){
                return $query7->get();
            }])->get();
        }])
        ->get();
        if (count($all_tractor)>0) {
            $store_log = StoresLog::where('All_part_id' , $all_tractor[0]->id )->where( 'type_id' , '3')->get();
        }else{
            $store_log=[];
        }
        return([$tractor,$store_log]);

    }

    public function indexWithRequest(Request $request)
    {
        if ($request->ajax()) {
            $data = Tractor::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name',function($row){
                    return $row->name ;
                })
                ->addColumn('tractorNumbers',function($row){
                    return isset($row->tractor_number) ? $row->tractor_number : '--';


                })
                ->addColumn('tractorSeries', function($row){
                    return isset($row->series->name) ? $row->series->name : '--';

                })
                ->addColumn('tractorBrand', function($row){
                    return isset($row->series->model->brand->name) ? $row->series->model->brand->name : '--';
                    // return isset($row->series->model->brand->name) ? $row->series->model->brand->name : '--';

                })

                // ->addColumn('tractorSeries',function($row){
                //     $tractorSeries= Series::where('id',$row->model_id)->get();
                //     foreach ($tractorSeries as $key => $value) {
                //         return isset($value->name) ? $value->name : '--';
                //     }
                // })
                // ->addColumn('tractorBrand',function($row){
                //     $tractorSeries= Series::where('id',$row->model_id)->get();
                //     foreach ($tractorSeries as $key => $value) {
                //         return isset($value->model->name) ? $value->model->name : '--';
                //     }
                // })

                ->addColumn('Image',function($row){
                    $tractorImage = TractorImage::where('tractor_id',$row->id)->get();
                    $html="";
                    foreach ($tractorImage as $key => $value) {
                        $html = $html . '<img class="rounded-circle header-profile-user" src="assets/tractor_images/'. $value->url.'" alt="Emara">';
                    }
                    return $html;
                })
                ->addColumn('efragImage',function($row){
                    $efragImage = EfragImage::where('tractor_id',$row->id)->get();
                    $html="";
                    foreach ($efragImage as $key => $value) {
                        $html = $html . '<img class="rounded-circle header-profile-user" src="assets/efrag_images/'. $value->image_name.'" alt="Emara">';
                    }
                    return $html;
                })

                ->addColumn('action', function($row){


                    $btn = '<a  data-bs-toggle="modal"  tractor_id_value="'.$row->id.'"  data-bs-target="#editTractor"
                    data-toggle="tooltip" data-original-title="Edit" class="btn btn-primary btn-sm d-inline-block editButton editTractorB "><i class="ri-edit-line editTractorButton"></i></a>';
                    $btn = $btn.' <a  data-bs-toggle="modal"  tractor_id_value2="'.$row->id.'"  data-bs-target="#deletepartB"
                    data-toggle="tooltip" data-original-title="Delete" class="btn btn-danger btn-sm d-inline-block deletepartB "><i class="ri-delete-bin-3-line deletePartButton"></i></a>';


                    // $btn = '<div class="row" style="width: fit-content;"><div class="px-1 mb-1 col"><a  data-bs-toggle="modal"  tractor_id_value="'.$row->id.'"  data-bs-target="#editTractor"
                    // data-toggle="tooltip" data-original-title="Edit" class="editButton btn-sm editTractorB"><i class="ri-edit-line editTractorButton"></i></a></div>';
                    // $btn = $btn.' <div class="px-1 col"><form action="'.route("tractor.destroy",$row->id).'" method="POST"class="form-block float-end">'
                    //         . csrf_field()
                    //         . method_field('DELETE')
                    //         .'<button type="submit" value="" class= "btn-sm deleteButton"><i class="ri-delete-bin-6-fill"></i></button>'.
                    //         '</form></div>';

                    $btn = $btn.'<a href="'.route("tractor.print",$row->id ).'" class="btn-info printButton btn-sm printTractor mr-1"><i class=" ri-printer-fill"></i></a>';

                    return $btn;

                })
                ->rawColumns(['name','tractorNumbers','tractorSeries', 'tractorBrand','Image','efragImage','action'])
                ->make(true);
        }
    }

    public function index()
    {
        return Tractor::all();
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

        // return $request;
        Tractor::create($request->all());
        $tractor_id=Tractor::max('id');

        if(isset($request->specs)){
            for ($i=0; $i < count($request->specs) ; $i++) {
                if($request->specs[$i] != null){
                    $specs = new TractorSpec();
                    $specs->name = $request->specs[$i];
                    $specs->general_flag = $tractor_id;
                    $specs->save();
                    $specDetail = new TractorDetail();
                    $specDetail->Tractorpecs_id = $specs->id;
                    $specDetail->tractor_id = $tractor_id;
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
                    TractorDetail::create(['Tractorpecs_id'=> $request->oldSpecs[$j] ,'tractor_id'=> $tractor_id ,'value' => $request->oldspecsval[$j]]);
                }else{
                    continue;
                }

            }
        }
        if(isset($request->relatedPart)){
            for ($i=0; $i < count($request->relatedPart) ; $i++) {
                RelatedTractor::create(['tractor_id' => $tractor_id , 'sug_tractor_id' => $request->relatedPart[$i]]);
            }
        }
        if(isset($request->tractorImg)){
            for ($i=0; $i < count($request->tractorImg) ; $i++) {
                $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->tractorImg[$i]->getClientOriginalName());
                $imageName = time() . $namewithoutchar.'.'.$request->tractorImg[$i]->extension() ;
                $request->tractorImg[$i]->move(public_path('assets/tractor_images'), $imageName);
                TractorImage::create(['tractor_id' => $tractor_id , 'name' => $namewithoutchar, 'url' => $imageName]);

            }
        }

        if(isset($request->releaseImg)){
            for ($i=0; $i < count($request->releaseImg) ; $i++) {
                $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->releaseImg[$i]->getClientOriginalName());
                $imageName = time() . $namewithoutchar.'.'.$request->releaseImg[$i]->extension() ;
                $request->releaseImg[$i]->move(public_path('assets/efrag_images'), $imageName);
                EfragImage::create(['tractor_id' => $tractor_id ,'name' => $namewithoutchar,  'image_name' => $imageName ]);

            }
        }

        /*********************************************************************************** */

        $quaditems = [];
        $automaicQayd = new QaydController();
        $invoiceac = 0;
        $taxac = 0;
        $binvoiceac = 0;
        $buyCoastAc = 0;
        $totalTaxEgp = 0;
        $taxAc2 = 0;
        $acSupNo = Supplier::where('id', $request->supplier_id)->first()->accountant_number;
        $Ac_currency_id = $request->currency_id;
        $Ac_all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($Ac_currency_id) {
            return $query->where('to', null)->where('currency_id', $Ac_currency_id);
        }])->where('id', $Ac_currency_id)->get();


        if ($request->transCoast > 0) {
            array_push($quaditems, (object) ['acountant_id' => 33101, 'dayin' => 0, 'madin' => $request->transCoast]);
            $buyCoastAc += $request->transCoast;
        }
        if ($request->insuranceCoast > 0) {
            array_push($quaditems, (object) ['acountant_id' => 33102, 'dayin' => 0, 'madin' => $request->insuranceCoast]);
            $buyCoastAc += $request->insuranceCoast;
        }
        if ($request->customs > 0) {
            array_push($quaditems, (object) ['acountant_id' => 33103, 'dayin' => 0, 'madin' => $request->customs]);
            $buyCoastAc += $request->customs;
        }
        if ($request->commition > 0) {
            array_push($quaditems, (object) ['acountant_id' => 33104, 'dayin' => 0, 'madin' => $request->commition]);
            $buyCoastAc += $request->commition;
        }
        if ($request->otherCoast > 0) {
            array_push($quaditems, (object) ['acountant_id' => 33105, 'dayin' => 0, 'madin' => $request->otherCoast]);
            $buyCoastAc += $request->otherCoast;
        }

        if (floatval($request->taxInvolved) == 1) {
            // غير شامل
            if ($request->invTax) {
                $taxac = $request->invTotLbl * $request->invTax / 100; // الضريبة
            } else {
                $taxac = $request->invTotLbl * 14 / 100; // الضريبة
            }

            $invoiceac = $request->invTotLbl + $taxac; //الاجمالي بعد الضريبة
            $binvoiceac = round($invoiceac - $taxac);
        } else {
            // شامل
            $binvoiceac = round($request->invAllTotal / 1.14); //  المشتريات غير شامل الضريبة
            $taxac = $request->invAllTotal - $binvoiceac; // الضريبة
            $invoiceac =  round($binvoiceac + $taxac);
        }


        if (floatval($request->taxkasmInvolved) == 1) {
            $taxAc2 = $request->invTotLbl * -1 / 100;
            $invoiceac = $invoiceac + $taxAc2;
        } else {
            $taxAc2 = 0;
        }

        $totalTaxEgp = ($taxac+$taxAc2) * $Ac_all_currency_types[0]->currencies[0]->value;
        if ($request->payment == 0  && $invoiceac == $request->invPaied) // البيع كاش
        {

            // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac *  $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
            array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => $binvoiceac *  $Ac_all_currency_types[0]->currencies[0]->value, 'dayin' => 0]); // المشتريات مدين
            array_push($quaditems, (object) ['acountant_id' => intval($request->store_idd), 'madin' => 0, 'dayin' => ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // الخزنة دائن
            // array_push ( $quaditems , (object) [ 'acountant_id'=> $acSupNo , 'madin'=> 0 , 'dayin'=> ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value)+$buyCoastAc ] ); // العميل دائن

        } elseif ($request->payment == 1 && $invoiceac == $request->invPaied) // البيع شيك
        {
            // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
            array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value, 'dayin' => 0]); // المشتريات مدين

            // array_push ( $quaditems , (object) [ 'acountant_id'=> $acSupNo , 'madin'=> 0 , 'dayin'=> ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value)+$buyCoastAc ] ); // العميل دائن


            array_push($quaditems, (object) ['acountant_id' => intval($request->store_idd), 'madin' => 0, 'dayin' => ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // بنك دائن


        } else // البيع أجل
        {
            // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
            array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value, 'dayin' => 0]); // المشتريات مدين


            array_push($quaditems, (object) ['acountant_id' => $acSupNo, 'madin' => 0, 'dayin' => ($invoiceac - $request->invPaied) * $Ac_all_currency_types[0]->currencies[0]->value]); // العميل دائن
            if (intval($request->store_idd) != 0) // خزنة
            {
                if ($request->invPaied > 0) {
                    array_push($quaditems, (object) ['acountant_id' => intval($request->store_idd), 'madin' => 0, 'dayin' => ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // الخزنة دائن
                }
            } else // بنك
            {
                if ($request->invPaied > 0) {
                    array_push($quaditems, (object) ['acountant_id' => intval($request->store_idd), 'madin' => 0, 'dayin' => ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // بنك دائن
                }
            }
        }

        if ($taxac > 0) {
            array_push($quaditems, (object) ['acountant_id' => 175, 'dayin' => 0, 'madin' => $taxac * $Ac_all_currency_types[0]->currencies[0]->value]); // الضريبة مدين
        }
        if ($taxAc2 != 0) {
            array_push($quaditems, (object) ['acountant_id' => 2637, 'dayin' => $taxAc2 * $Ac_all_currency_types[0]->currencies[0]->value * -1, 'madin' => 0]); // الضريبة مدين
        }

        // if ($buyCoastAc > 0) {
        //     array_push($quaditems, (object) ['acountant_id' => $request->store_idd, 'dayin' => $buyCoastAc, 'madin' => 0]); // الضريبة مدين
        // }
        // *************************************************************************************



        if (isset($request->currency_id)  && isset($request->supplier_id)) {
            $currency_id = $request->currency_id;

            $Ac_allTotal = 0;
            $bank = BankType::where('accountant_number', intval($request->store_idd))->first();
            if ($bank) {

                $raseed = $bank->bank_raseed;
                $total_qabd = BankSafeMoney::where('bank_type_id', $bank->id)->where('type_money', 0)->sum('money'); //قبض
                $total_sarf = BankSafeMoney::where('bank_type_id', $bank->id)->where('type_money', 1)->sum('money'); //صرف
                $total = floatval($raseed) + floatval($total_qabd) - floatval($total_sarf);
                $Ac_allTotal  = BankSafeMoney::where('bank_type_id',$bank->id)->latest()->first();
            } else {

                $store_bank = Store::where('safe_accountant_number', intval($request->store_idd))->first();
                $raseed = $store_bank->store_raseed;
                $total_qabd = MoneySafe::where('store_id', $store_bank->id)->where('type_money', 0)->sum('money'); //قبض
                $total_sarf = MoneySafe::where('store_id', $store_bank->id)->where('type_money', 1)->sum('money'); //صرف
                $total = floatval($raseed) + floatval($total_qabd) - floatval($total_sarf);
                $Ac_allTotal = MoneySafe::where('store_id', $store_bank->id)->latest()->first();
            }

            $all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($currency_id) {
                return $query->where('to', null)->where('currency_id', $currency_id);
            }])->where('id', $currency_id)->get();

            if (isset($total)) {

                if ($total >= ($request->invPaied * $all_currency_types[0]->currencies[0]->value)+$buyCoastAc) {
                    // return $total;
                    if (isset($request->inv_id)) {
                        $BuyTransaction = BuyTransaction::where('id', $request->inv_id)->first();
                    } else {

                        $BuyTransaction = new BuyTransaction();

                        $BuyTransaction->company_id = '10';
                        $BuyTransaction->date = Carbon::now();
                        $BuyTransaction->creation_date = Carbon::now();
                        $BuyTransaction->name = Carbon::now();
                        $BuyTransaction->desc = 'Tractor';
                        $BuyTransaction->final = '3';
                        $BuyTransaction->save();
                        if(isset($request->invoiceImg)){
                            for ($i=0; $i < count($request->invoiceImg) ; $i++) {
                                $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->invoiceImg[$i]->getClientOriginalName());
                                $imageName = time() . $namewithoutchar.'.'.$request->invoiceImg[$i]->extension() ;
                                $request->invoiceImg[$i]->move(public_path('assets/invoice_images'), $imageName);
                                InvoiceImage::create(['part_id' => $tractor_id ,'name' => $namewithoutchar,  'image_name' => $imageName , 'part_type_id' =>'3' ]);
                            }
                        }
                    }

                    $sup_id = 0;
                    $supplier = Supplier::where('id', $request->supplier_id)->first();
                    if ($bank) {
                        $Ac_allTotal_total = 0;
                        if ($Ac_allTotal) {
                            $Ac_allTotal_total = $Ac_allTotal->total;
                        }
                        BankSafeMoney::create([
                            'notes' => 'صرف مبلغ فاتورة شراء رقم ' . ' ' . $BuyTransaction->id . ' ' . 'من مورد' . ' ' . $supplier->name,
                            'date' => date('Y-m-d'),
                            'flag' => 1,
                            'money' => ($request->invPaied * $all_currency_types[0]->currencies[0]->value) +$buyCoastAc,
                            'total' => $Ac_allTotal_total - ($request->invPaied * $all_currency_types[0]->currencies[0]->value) -$buyCoastAc,
                            'type_money' => '1',
                            'user_id' => Auth::user()->id,
                            'store_id' => null,
                            'bank_type_id' => $bank->id,
                            'money_currency' => $request->invPaied,
                            'currency_id' => $currency_id,

                        ]);
                    } else {
                        $Ac_allTotal_total = 0;
                        if ($Ac_allTotal) {
                            $Ac_allTotal_total = $Ac_allTotal->total;
                        }
                        $xx = Store::where('safe_accountant_number', intval($request->store_idd))->first();
                        MoneySafe::create([
                            'notes' => 'صرف مبلغ فاتورة شراء رقم ' . ' ' . $BuyTransaction->id . ' ' . 'من مورد' . ' ' . $supplier->name,
                            'date' => date('Y-m-d'),
                            'flag' => 1,
                            'money' => ($request->invPaied * $all_currency_types[0]->currencies[0]->value) + $buyCoastAc,
                            'total' => $Ac_allTotal_total - ($request->invPaied * $all_currency_types[0]->currencies[0]->value) - $buyCoastAc,
                            'type_money' => '1',
                            'user_id' => Auth::user()->id,
                            'store_id' => $xx->id,

                        ]);
                    }

                    if($request->invPaied > 0){
                        $mad = new SanadSarf();
                        $mad->client_sup_id = $supplier->id;
                        $mad->paied = $request->invPaied * $all_currency_types[0]->currencies[0]->value;
                        $mad->date = Carbon::now();
                        $mad->pyment_method = $request->store_id;
                        $mad->type = 2;


                        $mad->save();
                    }

                    if ($request->NewinvSupplier <>  null) {
                        // add new Supplier
                        $sup = new Supplier();
                        $sup->name = $request->NewinvSupplier;
                        $sup->save();
                        $sup_id = $sup->id;
                    } else {

                        $sup_id = $request->supplier_id;
                    }
                    $orderSupplier = new OrderSupplier();
                    $orderSupplier->transaction_id = $BuyTransaction->id;
                    $orderSupplier->user_id = Auth::user()->id;
                    $orderSupplier->supplier_id = $sup_id;
                    $orderSupplier->pricebeforeTax = $request->invTotLbl;
                    $orderSupplier->user_id = Auth::user()->id;
                    // $orderSupplier->notes = '';
                    $orderSupplier->status = '4';
                    $orderSupplier->deliver_date = Carbon::now();
                    // $orderSupplier->currency_id = 400;
                    $orderSupplier->currency_id = $request->currency_id;
                    $orderSupplier->total_price = $request->invAllTotal;
                    $orderSupplier->paied = $request->invPaied;
                    $orderSupplier->payment = $request->payment;
                    $orderSupplier->bank_account = $request->store_idd;

                    $orderSupplier->transport_coast = $request->transCoast;
                    $orderSupplier->insurant_coast = $request->insuranceCoast;
                    $orderSupplier->customs_coast = $request->customs;
                    $orderSupplier->commotion_coast = $request->commition;
                    $orderSupplier->other_coast = $request->otherCoast;
                    $orderSupplier->coast = $request->InvCoasting;

                    $toalCoasts = 0;
                    $toalCoasts =  $request->transCoast+ $request->insuranceCoast+$request->customs+$request->commition+ $request->otherCoast;

                    $orderSupplier->taxInvolved_flag = $request->taxInvolved;
                    $orderSupplier->taxkasmInvolved_flag = $request->taxkasmInvolved;



                    if (floatval($request->taxInvolved) == 1) {
                        $orderSupplier->tax = 0;
                    } else {
                        $orderSupplier->tax = $request->invTax;
                    }


                    if ($request->invPaied < $request->invAllTotal) {
                        $orderSupplier->due_date = $request->dueDate;
                        Supplier::where('id', $sup_id)->increment('raseed', (floatval($request->invAllTotal) - floatval($request->invPaied)) * $all_currency_types[0]->currencies[0]->value);
                    } else if ($request->invPaied > $request->invAllTotal) {
                        Supplier::where('id', $sup_id)->decrement('raseed', (floatval($request->invPaied) - floatval($request->invAllTotal)) * $all_currency_types[0]->currencies[0]->value);
                    } else {
                    }
                    // $orderSupplier->bank_account = '';
                    // $orderSupplier->container_size = '';
                    $orderSupplier->confirmation_date = Carbon::now();
                    // $orderSupplier->image_url = '';
                    $orderSupplier->save();


                    $date = Carbon::now();
                    $type = null;
                    $notes = 'فاتورة شراء وتوزيع بالأمر المباشر رقم' . $BuyTransaction->id . 'العملة ' . $Ac_all_currency_types[0]->name;
                    $qaydids = $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
                    Qayditem::where('qaydid', $qaydids)->update([
                        'invoiceid' => $BuyTransaction->id
                    ]);
                    $quaditems = [];
                    $Actotal = 0;

                    $part = new Replyorder();
                    $part->order_supplier_id = $orderSupplier->id;
                    $part->part_id = $tractor_id;
                    $part->price = $request->price;
                    $part->amount = 1;
                    $part->source_id = $request->source_id;
                    $part->status_id = $request->status_id;
                    $part->quality_id = $request->quality_id;
                    $part->creation_date = Carbon::now();
                    $part->part_type_id = 3;
                    $part->save();

                    $store = Store::where('id', $request->store_id)->first();

                    $Ac_inv = Replyorder::where('order_supplier_id', $orderSupplier->id)
                    ->where('part_id', $tractor_id)
                    ->where('source_id', $request->source_id)
                    ->where('status_id', $request->status_id)
                    ->where('quality_id', $request->quality_id)
                    ->where('part_type_id', 3)
                    ->with('order_supplier')->first();

                    $Ac_currency_id = $Ac_inv->order_supplier->currency_id;
                    $Ac_currency_date = $Ac_inv->order_supplier->confirmation_date;
                    $Ac_all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                        return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                    }])->where('id', $Ac_currency_id)->get();

                    $Ac_price = 1 * $Ac_all_currency_types[0]->currencies[0]->value * $Ac_inv->price;

                    array_push($quaditems, (object) ['acountant_id' => $store->accountant_number, 'madin' => $Ac_price- $totalTaxEgp + $toalCoasts, 'dayin' => 0]); // المخزن مدين

                    $Actotal += $Ac_price;


                    $ratio = floatval($request->InvCoasting) / floatval($request->invTotLbl);
                    $itemCoast = ($ratio *  (floatval($request->price) * 1 )) / 1;

                    $allpart = new AllTractor();
                    $allpart->part_id = $tractor_id;
                    $allpart->order_supplier_id = $orderSupplier->id;
                    $allpart->amount = 1;
                    $allpart->source_id = $request->source_id;
                    $allpart->status_id = $request->status_id;
                    $allpart->quality_id = $request->quality_id;
                    $allpart->buy_price = $request->price;
                    $allpart->insertion_date = Carbon::now();
                    $allpart->remain_amount = 1;
                    $allpart->flag = 3;
                    $allpart->buy_costing = $itemCoast;
                    $allpart->save();

                    if (isset($request->storeSectionId)) {

                        $storelog = new StoresLog();
                        $storelog->All_part_id = $allpart->id;
                        $storelog->store_id = $request->store_id;
                        $storelog->store_action_id = 1;
                        $storelog->amount = 1;
                        $storelog->date = Carbon::now();
                        $storelog->status = 1;
                        $storelog->type_id = 3;
                        $storelog->save();

                        $storelog = new StoresLog();
                        $storelog->All_part_id = $allpart->id;
                        $storelog->store_id = $request->store_id;
                        $storelog->store_action_id = 3;
                        $storelog->amount = 1;
                        $storelog->date = Carbon::now();
                        $storelog->status = 3;
                        $storelog->type_id = 3;
                        $storelog->save();


                        $store = Store::where('id', $request->store_id)->get();

                        $storeClsName = ucfirst($store[0]->table_name);
                        $storeClsName = 'App\Models\\' . $storeClsName;
                        $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);

                        // return $storeClsName;
                        if ($store[0]->table_name == "damaged_parts") {
                            $storeClsName = "App\Models\\DamagedPart";
                        }
                        try {
                            //code...
                            $storeCls = new $storeClsName();

                            $storeCls->part_id = $tractor_id;
                            $storeCls->amount = 1;
                            $storeCls->supplier_order_id = $orderSupplier->id;
                            $storeCls->type_id = 3;
                            $storeCls->store_log_id = $storelog->id;
                            $storeCls->date = Carbon::now();
                            $storeCls->save();


                            $section = new StoreSection();
                            $section->store_id = $request->store_id;
                            $section->section_id = $request->storeSectionId;
                            $section->order_supplier_id = $orderSupplier->id;
                            $section->type_id = 3;
                            $section->part_id = $tractor_id;
                            $section->source_id = $request->source_id;
                            $section->status_id = $request->status_id;
                            $section->quality_id = $request->quality_id;
                            $section->amount =1;
                            $section->date = Carbon::now();
                            $section->save();
                        } catch (\Throwable $th) {

                        }
                    }


                    // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> 0 , 'dayin'=>$Actotal ] ); // المخزون دائن
                    array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => 0, 'dayin' => $Actotal - $totalTaxEgp + $toalCoasts]); // المشتريات دائن
                    $date = Carbon::now();
                    $type = null;
                    $notes = 'حركة مخزون';
                    $qyadidss = $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
                    Qayditem::where('qaydid', $qyadidss)->update([
                        'invoiceid' => $BuyTransaction->id
                    ]);



                    session()->flash("success", "تم حفظ البيانات  بنجاح");
                    return redirect()->to('printBuyInvoice/' . $BuyTransaction->id);
                    // return redirect()->back();
                } else {

                    session()->flash("success", "  المبلغ غير كافي في الخزنة");
                    return redirect()->back();
                }
            } else {

                session()->flash("success", "   لا يوجد رصيد في الخزنة   ");
                return redirect()->back();
            }
        } else {
            return back()->with('success', 'please Select Supplier and Currency!.');
        }
        /********************************************************************** */

    }

    /**
     * Display the specified resource.
     */
    public function show(Tractor $tractor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tractor $tractor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tractor $tractor)
    {

        // return $request;
        DB::beginTransaction();
        try {
            $buy_transaction_id =0;
            $tractor_id = $tractor->id;
            $imgURLsInp = explode(',', $request->imgURLsInp[0]) ;
            $imgURLsInpInvoice = explode(',', $request->imgURLsInpInvoice[0]) ;
            $imgURLsInpEfrag = explode(',', $request->imgURLsInpEfrag[0]) ;

           for($i = 0 ; $i< count($imgURLsInpInvoice) ; $i++){
               if($imgURLsInpInvoice[$i] != null){
                   $tractor->invoice_images()->where('image_name', $imgURLsInpInvoice[$i])->delete();
               }
           }

           for($i = 0 ; $i< count($imgURLsInpEfrag) ; $i++){
               if($imgURLsInpEfrag[$i] != null){
                   $tractor->efrag_images()->where('image_name', $imgURLsInpEfrag[$i])->delete();
               }
           }

           for($i = 0 ; $i< count($imgURLsInp) ; $i++){
               if($imgURLsInp[$i] != null){
                   $tractor->tractor_images()->where('url', $imgURLsInp[$i])->delete();
               }
           }

           $relatedTractor= RelatedTractor::where('tractor_id' ,$tractor->id);
           if(isset($relatedTractor)){
               $relatedTractor->delete();
           }

           $tractor->tractor_details()->delete();
           $tractor_specs= Tractorspec::where('general_flag' , $tractor->id)->get();
           if(count($tractor_specs)>0){
               $tractor_specs->each->delete();
           }

           $all_tractor = $tractor->all_tractors()->first();
           $order_supplier_id = 0;
           if(isset($all_tractor)){
               $order_supplier_id = $all_tractor->order_supplier_id;
               $order_supplier = OrderSupplier::where('id' , $order_supplier_id)->first();
               $buy_transaction_id = $order_supplier->transaction_id;
               $buy_trnsaction = BuyTransaction::where('id' , $buy_transaction_id)->first();
               $store_log=StoresLog::where('All_part_id' , $all_tractor->id)->where('type_id' , '3')->first();
           }

           if(isset($store_log)){
               $store = Store::where('id' , $store_log->store_id)->first();

               $storeClsName=ucfirst($store->table_name );
               $storeClsName ='App\Models\\'.$storeClsName;
               $storeClsName = str_replace([' ','_','-'],"",$storeClsName);
               if($store->table_name == "damaged_parts"){
                   $storeClsName = "App\Models\\DamagedPart";
                   $storeCls = new $storeClsName();
                   $toBeDeleted = $storeCls->where('all_part_id' , $all_tractor->id)->where('type_id' , '3')->delete();
               }else{
               $storeCls = new $storeClsName();
               $toBeDeleted = $storeCls->where('part_id' , $tractor->id)->where('type_id' , '3')->delete();
               }

           }

           if(isset($store_log)){
               StoresLog::find($store_log->id)->delete();
           }
           if(isset($all_tractor)){
               AllTractor::find($all_tractor->id)->delete();
           }
           $replyorder = Replyorder::where('part_id' , $tractor->id)->where('part_type_id' , '3')->first();
           if(isset($replyorder)){
               Replyorder::where('id', $replyorder->id)->delete();

           }

           // if(isset($buy_trnsaction)){
           //     BuyTransaction::where('id', $buy_transaction_id)->delete();
           // }
           $tractor->update(['name' => $request->name_edit , 'name_en' => $request->name_en_edit , 'fronttire' => $request->fronttire_edit , 'reartire' => $request->reartire_edit , 'gear_box' => $request->gear_box_edit, 'hours' => $request->hours_edit, 'power' => $request->power_edit , 'year' => $request->year_edit , 'color' => $request->color_edit , 'colotank_capacityr' => $request->tank_capacity_edit , 'discs' => $request->discs_edit , 'tractor_number' => $request->tractor_number_edit ,'model_id' => $request->model_id_edit ,'desc' => $request->desc_edit , 'drive' => $request->drive_edit , 'fronttirestatus' => $request->fronttirestatus_edit , 'reartirestatus' => $request->reartirestatus_edit , 'motornumber' => $request->motornumber_edit , 'serivcedate' => $request-> serivcedate_edit ]);

           if(isset($request->specsEdit)){
               for ($i=0; $i < count($request->specsEdit) ; $i++) {
                   if($request->specsEdit[$i] != null){
                       $specs = new TractorSpec();
                       $specs->name = $request->specsEdit[$i];
                       $specs->general_flag = $tractor->id;
                       $specs->save();
                       $specDetail = new TractorDetail();
                       $specDetail->Tractorpecs_id = $specs->id;
                       $specDetail->tractor_id = $tractor->id;
                       if(isset($request->specsvalEdit)){
                           $specDetail->value = $request->specsvalEdit[$i];
                           $specDetail->save();
                       }
                       $specDetail->save();
                   }
               }
           }
           if(isset($request->oldSpecsEdit)){
               for ($j=0; $j < count($request->oldSpecsEdit) ; $j++) {
                   if($request->oldSpecsEdit[$j] != null){
                       TractorDetail::create(['Tractorpecs_id'=> $request->oldSpecsEdit[$j] ,'tractor_id'=> $tractor->id ,'value' => $request->oldspecsvalEdit[$j]]);
                   }else{
                       continue;
                   }
               }
           }
           if(isset($request->relatedPartEdit)){
               for ($i=0; $i < count($request->relatedPartEdit) ; $i++) {
                   RelatedTractor::create(['tractor_id' => $tractor->id , 'sug_tractor_id' => $request->relatedPartEdit[$i]]);
               }
           }
           if(isset($request->tractorImg_edit)){
               for ($i=0; $i < count($request->tractorImg_edit) ; $i++) {
                   $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->tractorImg_edit[$i]->getClientOriginalName());
                   $imageName = time() . $namewithoutchar.'.'.$request->tractorImg_edit[$i]->extension() ;
                   $request->tractorImg_edit[$i]->move(public_path('assets/tractor_images'), $imageName);
                   TractorImage::create(['tractor_id' => $tractor->id , 'name' => $namewithoutchar, 'url' => $imageName]);

               }
           }

           if(isset($request->releaseImg_edit)){
               for ($i=0; $i < count($request->releaseImg_edit) ; $i++) {
                   $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->releaseImg_edit[$i]->getClientOriginalName());
                   $imageName = time() . $namewithoutchar.'.'.$request->releaseImg_edit[$i]->extension() ;
                   $request->releaseImg_edit[$i]->move(public_path('assets/efrag_images'), $imageName);
                   EfragImage::create(['tractor_id' => $tractor->id ,'name' => $namewithoutchar,  'image_name' => $imageName ]);

               }
           }


           /**********************************************************/

           // Delete qayd

           $old_qayd_item = Qayditem::where('invoiceid', $request->buyTransaction_id)->get();
           $uniqueQaydids = Qayditem::where('invoiceid', $request->buyTransaction_id)
               ->distinct()
               ->pluck('qaydid')
               ->toArray();
           $old_qayd_item2 = $old_qayd_item;
           foreach ($old_qayd_item2 as $one_old_qayd_item) {
               QayditemDeleted::create([
                   'id' => $one_old_qayd_item->id,
                   'qaydid' => $one_old_qayd_item->qaydid,
                   'branchid' => $one_old_qayd_item->branchid,
                   'dayin' => $one_old_qayd_item->dayin,
                   'madin' => $one_old_qayd_item->madin,
                   'topic' => $one_old_qayd_item->topic,
                   'invoiceid' => $one_old_qayd_item->invoiceid,
                   'date' => $one_old_qayd_item->date,
                   'date_created' => date('Y-m-d')
               ]);
               Qayditem::where('id', $one_old_qayd_item->id)->delete();
           }
           foreach ($uniqueQaydids as $uniqueQaydid) {
               $old_qayd = Qayd::where('id', $uniqueQaydid)->first();
               QaydDeleted::create([
                   'id' => $old_qayd->id,
                   'qaydtypeid' => $old_qayd->qaydtypeid,
                   'accountnumber' => $old_qayd->accountnumber,
                   'date' => $old_qayd->date,
                   'note' => $old_qayd->note,
                   'date_created' => date('Y-m-d')
               ]);
               Qayd::where('id', $uniqueQaydid)->delete();
           }


           // Add to safe old_paied

           $editSup = Supplier::where('id',$request->supplier_id_edit)->first();

           $oldOrderSup = OrderSupplier::where('id', $order_supplier_id)->first();
           $xcurrency_id = $oldOrderSup->currency_id;
           $xconfirmation_date = $oldOrderSup->confirmation_date;
           $xpaied = $oldOrderSup->paied;
           $xbank_account = $oldOrderSup->bank_account;

           $oldcoasts =  $oldOrderSup->transport_coast +  $oldOrderSup->insurant_coast + $oldOrderSup->customs_coast +  $oldOrderSup->commotion_coast +  $oldOrderSup->other_coast;


           $bank = BankType::where('accountant_number', intval($xbank_account))->first();

           $xAc_all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($xcurrency_id, $xconfirmation_date) {
               return $query->where('from', '>=', $xconfirmation_date)->where('to', '<=', $xconfirmation_date)->where('currency_id', $xcurrency_id)->orWhere('to', '=', null);
           }])->where('id', $xcurrency_id)->get();

           if($bank){

            $BankSafeMoneyTot  = BankSafeMoney::where('bank_type_id',$bank->id)->latest()->first();

            BankSafeMoney::create([
                   'notes' => 'تعديل جرار ' . ' ' . $request->buyTransaction_id . ' ' . 'من مورد' . ' ' . $editSup->name,
                   'date' => date('Y-m-d'),
                   'flag' => 1,
                   'money' => ($xpaied * $xAc_all_currency_types[0]->currencies[0]->value) +$oldcoasts,
                   'total' => $BankSafeMoneyTot->total + ($xpaied * $xAc_all_currency_types[0]->currencies[0]->value) + $oldcoasts,
                   'type_money' => '0',
                   'user_id' => Auth::user()->id,
                   'store_id' => null,
                   'bank_type_id' => $bank->id,
                   'money_currency' => $xpaied,
                   'currency_id' => $xcurrency_id,

               ]);
           }else{


               $xxd = Store::where('safe_accountant_number', intval($xbank_account))->first();
               $MoneySafeTot = MoneySafe::where('store_id', $xxd->id)->latest()->first();
               MoneySafe::create([
                   'notes' => 'تعديل جرار ' . ' ' . $request->buyTransaction_id . ' ' . 'من مورد' . ' ' . $editSup->name,
                   'date' => date('Y-m-d'),
                   'flag' => 1,
                   'money' => ($xpaied * $xAc_all_currency_types[0]->currencies[0]->value) +$oldcoasts,
                   'total' => $MoneySafeTot->total + ($xpaied * $xAc_all_currency_types[0]->currencies[0]->value)+$oldcoasts,
                   'type_money' => '0',
                   'user_id' => Auth::user()->id,
                   'store_id' => $xxd->id,

               ]);
           }

           // Delete Store Sections
           $StoreSection_deleted = StoreSection::where('order_supplier_id', $all_tractor->order_supplier_id)->get();
               foreach ($StoreSection_deleted as $x) {
                   $x->delete();  // Trigger deleting and deleted events
               }

           // sanad sarf تحت مع الانشاء الجديد لسند الصرف

                   ////////////////////////// hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhereeeeeeeeeeeeeeeeeeeeeeeeee

           ////////////////
           if(isset($order_supplier)){
               OrderSupplier::where('id', $order_supplier_id)->delete();
           }
           /*********************************************************************************** */

           $quaditems = [];
           $automaicQayd = new QaydController();
           $invoiceac = 0;
           $taxac = 0;
           $binvoiceac = 0;
           $buyCoastAc = 0;
           $taxAc2 = 0;
           $totalTaxEgp =0;

           $acSupNo = Supplier::where('id', $request->supplier_id_edit)->first()->accountant_number;


           if ($request->transCoast_edit > 0) {
               array_push($quaditems, (object) ['acountant_id' => 33101, 'dayin' => 0, 'madin' => $request->transCoast_edit]);
               $buyCoastAc += $request->transCoast_edit;
           }
           if ($request->insuranceCoast_edit > 0) {
               array_push($quaditems, (object) ['acountant_id' => 33102, 'dayin' => 0, 'madin' => $request->insuranceCoast_edit]);
               $buyCoastAc += $request->insuranceCoast_edit;
           }
           if ($request->customs_edit > 0) {
               array_push($quaditems, (object) ['acountant_id' => 33103, 'dayin' => 0, 'madin' => $request->customs_edit]);
               $buyCoastAc += $request->customs_edit;
           }
           if ($request->commition_edit > 0) {
               array_push($quaditems, (object) ['acountant_id' => 33104, 'dayin' => 0, 'madin' => $request->commition_edit]);
               $buyCoastAc += $request->commition_edit;
           }
           if ($request->otherCoast_edit > 0) {
               array_push($quaditems, (object) ['acountant_id' => 33105, 'dayin' => 0, 'madin' => $request->otherCoast_edit]);
               $buyCoastAc += $request->otherCoast_edit;
           }

           if (floatval($request->taxInvolved_edit) == 1) {
               // غير شامل
               if ($request->invTax_edit) {
                   $taxac = $request->invTotLbl_edit * $request->invTax_edit / 100; // الضريبة
               } else {
                   $taxac = $request->invTotLbl_edit * 14 / 100; // الضريبة
               }

               $invoiceac = $request->invTotLbl_edit + $taxac; //الاجمالي بعد الضريبة
               $binvoiceac = round($invoiceac - $taxac);
           } else {
               // شامل
               $binvoiceac = round($request->invTotLbl_edit / 1.14); //  المشتريات غير شامل الضريبة
               $taxac = $request->invTotLbl_edit - $binvoiceac; // الضريبة
               $invoiceac =  round($binvoiceac + $taxac);
           }

           if (floatval($request->taxkasmInvolved_edit) == 1) {
               $taxAc2 = $request->invTotLbl_edit * -1 / 100;
               $invoiceac = $invoiceac + $taxAc2;
           } else {
               $taxAc2 = 0;
           }
           $xcur_id = $request->currency_id_edit;
           $Ac_all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($xcur_id, $xconfirmation_date) {
                return $query->where('from', '>=', $xconfirmation_date)->where('to', '<=', $xconfirmation_date)->where('currency_id', $xcur_id)->orWhere('to', '=', null);
            }])->where('id', $xcur_id)->get();


            $totalTaxEgp = ($taxac+$taxAc2) * $Ac_all_currency_types[0]->currencies[0]->value;

           if ($request->payment_edit == 0  && $invoiceac == $request->invPaied_edit) // البيع كاش
           {

               // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac *  $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
               array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => $binvoiceac *  $Ac_all_currency_types[0]->currencies[0]->value, 'dayin' => 0]); // المشتريات مدين
               array_push($quaditems, (object) ['acountant_id' => intval($request->store_idd_edit), 'madin' => 0, 'dayin' => ($request->invPaied_edit * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // الخزنة دائن
               // array_push ( $quaditems , (object) [ 'acountant_id'=> $acSupNo , 'madin'=> 0 , 'dayin'=> ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value)+$buyCoastAc ] ); // العميل دائن

           } elseif ($request->payment_edit == 1 && $invoiceac == $request->invPaied_edit) // البيع شيك
           {
               // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
               array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value, 'dayin' => 0]); // المشتريات مدين

               // array_push ( $quaditems , (object) [ 'acountant_id'=> $acSupNo , 'madin'=> 0 , 'dayin'=> ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value)+$buyCoastAc ] ); // العميل دائن


               array_push($quaditems, (object) ['acountant_id' => intval($request->store_idd_edit), 'madin' => 0, 'dayin' => ($request->invPaied_edit * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // بنك دائن


           } else // البيع أجل
           {
               // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
               array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value, 'dayin' => 0]); // المشتريات مدين


               array_push($quaditems, (object) ['acountant_id' => $acSupNo, 'madin' => 0, 'dayin' => ($invoiceac - $request->invPaied_edit) * $Ac_all_currency_types[0]->currencies[0]->value]); // العميل دائن
               if (intval($request->store_idd_edit) != 0) // خزنة
               {
                   if ($request->invPaied_edit > 0) {
                       array_push($quaditems, (object) ['acountant_id' => intval($request->store_idd_edit), 'madin' => 0, 'dayin' => ($request->invPaied_edit * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // الخزنة دائن
                   }
               } else // بنك
               {
                   if ($request->invPaied_edit > 0) {
                       array_push($quaditems, (object) ['acountant_id' => intval($request->store_idd_edit), 'madin' => 0, 'dayin' => ($request->invPaied_edit * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // بنك دائن
                   }
               }
           }

           if ($taxac > 0) {
               array_push($quaditems, (object) ['acountant_id' => 175, 'dayin' => 0, 'madin' => $taxac * $Ac_all_currency_types[0]->currencies[0]->value]); // الضريبة مدين
           }
           if ($taxAc2 != 0) {
               array_push($quaditems, (object) ['acountant_id' => 2637, 'dayin' => $taxAc2 * $Ac_all_currency_types[0]->currencies[0]->value * -1, 'madin' => 0]); // الضريبة مدين
           }

        //    if ($buyCoastAc > 0) {
        //     array_push($quaditems, (object) ['acountant_id' => $request->store_idd_edit, 'dayin' => $buyCoastAc, 'madin' => 0]); // الضريبة مدين
        // }
           // *************************************************************************************



           if (isset($request->currency_id_edit)  && isset($request->supplier_id_edit)) {
               $currency_id = $request->currency_id_edit;

               $Ac_allTotal = 0;
               $bank = BankType::where('accountant_number', intval($request->store_idd_edit))->first();
               if ($bank) {

                   $raseed = $bank->bank_raseed;
                   $total_qabd = BankSafeMoney::where('bank_type_id', $bank->id)->where('type_money', 0)->sum('money'); //قبض
                   $total_sarf = BankSafeMoney::where('bank_type_id', $bank->id)->where('type_money', 1)->sum('money'); //صرف

                   $total = floatval($raseed) +  floatval($total_qabd) - floatval($total_sarf)   +  floatval($xpaied * $xAc_all_currency_types[0]->currencies[0]->value);

                   $Ac_allTotal  = BankSafeMoney::where('bank_type_id',$bank->id)->latest()->first();
               } else {

                   $store_bank = Store::where('safe_accountant_number', intval($request->store_idd_edit))->first();
                   $raseed = $store_bank->store_raseed;
                   $total_qabd = MoneySafe::where('store_id', $store_bank->id)->where('type_money', 0)->sum('money'); //قبض
                   $total_sarf = MoneySafe::where('store_id', $store_bank->id)->where('type_money', 1)->sum('money'); //صرف
                    $total = floatval($raseed) +  floatval($total_qabd) - floatval($total_sarf)   +  floatval($xpaied * $xAc_all_currency_types[0]->currencies[0]->value);

                   $Ac_allTotal = MoneySafe::where('store_id', $store_bank->id)->latest()->first();
               }

               $all_currency_types =CurrencyType::with(['currencies' => function ($query) use ($currency_id, $xconfirmation_date) {
                   return $query->where('from', '>=', $xconfirmation_date)->where('to', '<=', $xconfirmation_date)->where('currency_id', $currency_id)->orWhere('to', '=', null);
               }])->where('id', $currency_id)->get();

               if (isset($total)) {

                   if ($total >= $request->invPaied_edit * $all_currency_types[0]->currencies[0]->value) {
                       // return $total;
                       if (isset($request->buyTransaction_id)) {
                           $BuyTransaction = BuyTransaction::where('id', $request->buyTransaction_id)->first();
                       } else {

                           $BuyTransaction = new BuyTransaction();

                           $BuyTransaction->company_id = '10';
                           $BuyTransaction->date = Carbon::now();
                           $BuyTransaction->creation_date = Carbon::now();
                           $BuyTransaction->name = Carbon::now();
                           $BuyTransaction->desc = 'Tractor';
                           $BuyTransaction->final = '3';
                           $BuyTransaction->save();
                           if(isset($request->imgURLsInpInvoice)){
                               for ($i=0; $i < count($request->imgURLsInpInvoice) ; $i++) {
                                   $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->imgURLsInpInvoice[$i]->getClientOriginalName());
                                   $imageName = time() . $namewithoutchar.'.'.$request->imgURLsInpInvoice[$i]->extension() ;
                                   $request->imgURLsInpInvoice[$i]->move(public_path('assets/invoice_images'), $imageName);
                                   InvoiceImage::create(['part_id' => $tractor_id ,'name' => $namewithoutchar,  'image_name' => $imageName , 'part_type_id' =>'3' ]);
                               }
                           }
                       }

                       $sup_id = 0;
                       $supplier = Supplier::where('id', $request->supplier_id_edit)->first();
                       if ($bank) {
                           $Ac_allTotal_total = 0;
                           if ($Ac_allTotal) {
                               $Ac_allTotal_total = $Ac_allTotal->total;
                           }

                        //    return $buyCoastAc;
                           BankSafeMoney::create([
                               'notes' => 'صرف مبلغ فاتورة شراء رقم ' . ' ' . $BuyTransaction->id . ' ' . 'من مورد' . ' ' . $supplier->name,
                               'date' => date('Y-m-d'),
                               'flag' => 1,
                               'money' => ($request->invPaied_edit * $all_currency_types[0]->currencies[0]->value) + $buyCoastAc,
                               'total' => $Ac_allTotal_total - ($request->invPaied_edit * $all_currency_types[0]->currencies[0]->value) -$buyCoastAc,
                               'type_money' => '1',
                               'user_id' => Auth::user()->id,
                               'store_id' => null,
                               'bank_type_id' => $bank->id,
                               'money_currency' => $request->invPaied_edit,
                               'currency_id' => $currency_id,

                           ]);
                       } else {
                           $Ac_allTotal_total = 0;
                           if ($Ac_allTotal) {
                               $Ac_allTotal_total = $Ac_allTotal->total;
                           }
                           $xx = Store::where('safe_accountant_number', intval($request->store_idd_edit))->first();
                           MoneySafe::create([
                               'notes' => 'صرف مبلغ فاتورة شراء رقم ' . ' ' . $BuyTransaction->id . ' ' . 'من مورد' . ' ' . $supplier->name,
                               'date' => date('Y-m-d'),
                               'flag' => 1,
                               'money' => ($request->invPaied_edit * $all_currency_types[0]->currencies[0]->value)+$buyCoastAc,
                               'total' => $Ac_allTotal_total - ($request->invPaied_edit * $all_currency_types[0]->currencies[0]->value)-$buyCoastAc,
                               'type_money' => '1',
                               'user_id' => Auth::user()->id,
                               'store_id' => $xx->id,

                           ]);
                       }

                       // if($request->invPaied_edit > 0){

                       // }

                       $Ac_currency_id = $request->currency_id_edit;
                       $Ac_all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($Ac_currency_id, $xconfirmation_date) {
                           return $query->where('from', '>=', $xconfirmation_date)->where('to', '<=', $xconfirmation_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                       }])->where('id', $Ac_currency_id)->get();

                       //تعديل قيمة سند القبصض السابق قبل عملية تعديل الفقاتورة
                       $buy_trnsactionold = BuyTransaction::where('id' , $buy_transaction_id)->first();
                       if ($xpaied * $xAc_all_currency_types[0]->currencies[0]->value  != $request->invPaied_edit * $Ac_all_currency_types[0]->currencies[0]->value ) {

                           if($request->invPaied_edit > 0){
                               $mad = new SanadSarf();
                               $mad->client_sup_id = $supplier->id;
                               $mad->paied = $request->invPaied_edit * $all_currency_types[0]->currencies[0]->value;
                               $mad->date = Carbon::now();
                               $mad->pyment_method = $request->store_idd_edit;
                               $mad->type = 2;
                               $mad->save();

                              $old_sanad =  SanadSarf::where('client_sup_id',$oldOrderSup->supplier_id)
                               ->where('date','>=', explode(' ',$buy_trnsactionold->creation_date)[0])
                               ->where('paied', $xpaied * $Ac_all_currency_types[0]->currencies[0]->value)
                               ->first();

                               SanadSarf::where('id',$old_sanad->id)
                               ->update([
                                   'paied' => 0 ,
                                   'note' => 'تم تعديل سند الصرف  بعد تعديل الفاتورة رقم '.$buy_trnsactionold->id . ',وتم إنشاء سند صرف اخر رقم '.  $mad->id .',وكانت قيمة سند الصرف القديم ' .   $old_sanad->paied     ]);
                           }


                       }

                       if ($request->NewinvSupplier <>  null) {
                           // add new Supplier
                           $sup = new Supplier();
                           $sup->name = $request->NewinvSupplier;
                           $sup->save();
                           $sup_id = $sup->id;
                       } else {

                           $sup_id = $request->supplier_id_edit;
                       }
                       $orderSupplier = new OrderSupplier();
                       $orderSupplier->transaction_id = $BuyTransaction->id;
                       $orderSupplier->user_id = Auth::user()->id;
                       $orderSupplier->supplier_id = $sup_id;
                       $orderSupplier->pricebeforeTax = $request->invTotLbl_edit;
                       $orderSupplier->user_id = Auth::user()->id;
                       // $orderSupplier->notes = '';
                       $orderSupplier->status = '4';
                       $orderSupplier->deliver_date = Carbon::now();
                       // $orderSupplier->currency_id = 400;
                       $orderSupplier->currency_id = $request->currency_id_edit;
                       $orderSupplier->total_price = $request->invAllTotal_edit;
                       $orderSupplier->paied = $request->invPaied_edit;
                       $orderSupplier->payment = $request->payment_edit;
                       $orderSupplier->bank_account = $request->store_idd_edit;

                       $orderSupplier->transport_coast = $request->transCoast_edit;
                       $orderSupplier->insurant_coast = $request->insuranceCoast_edit;
                       $orderSupplier->customs_coast = $request->customs_edit;
                       $orderSupplier->commotion_coast = $request->commition_edit;
                       $orderSupplier->other_coast = $request->otherCoast_edit;
                       $orderSupplier->coast = $request->InvCoasting_edit;
                       $orderSupplier->taxInvolved_flag = $request->taxInvolved_edit;
                       $orderSupplier->taxkasmInvolved_flag = $request->taxkasmInvolved_edit;

                       $toalCoasts = 0;
                       $toalCoasts =  $request->transCoast_edit+ $request->insuranceCoast_edit+$request->customs_edit+$request->commition_edit+ $request->otherCoast_edit;


                       if (floatval($request->taxInvolved_edit) == 1) {
                           $orderSupplier->tax = 0;
                       } else {
                           $orderSupplier->tax = $request->invTax_edit;
                       }


                       if ($request->invPaied_edit < $request->invAllTotal_edit) {
                           $orderSupplier->due_date = $request->dueDate_edit;
                           Supplier::where('id', $sup_id)->increment('raseed', (floatval($request->invAllTotal_edit) - floatval($request->invPaied_edit)) * $all_currency_types[0]->currencies[0]->value);
                       } else if ($request->invPaied_edit > $request->invAllTotal_edit) {
                           Supplier::where('id', $sup_id)->decrement('raseed', (floatval($request->invPaied_edit) - floatval($request->invAllTotal_edit)) * $all_currency_types[0]->currencies[0]->value);
                       } else {
                       }
                       // $orderSupplier->bank_account = '';
                       // $orderSupplier->container_size = '';
                       $orderSupplier->confirmation_date = Carbon::now();
                       // $orderSupplier->image_url = '';
                       $orderSupplier->save();


                       $date = Carbon::now();
                       $type = null;
                       $notes = 'فاتورة شراء وتوزيع بالأمر المباشر رقم' . $BuyTransaction->id . 'العملة ' . $Ac_all_currency_types[0]->name;
                       $qaydids = $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
                       Qayditem::where('qaydid', $qaydids)->update([
                           'invoiceid' => $BuyTransaction->id
                       ]);
                       $quaditems = [];
                       $Actotal = 0;

                       $part = new Replyorder();
                       $part->order_supplier_id = $orderSupplier->id;
                       $part->part_id = $tractor_id;
                       $part->price = $request->price_edit;
                       $part->amount = 1;
                       $part->source_id = $request->source_id_edit;
                       $part->status_id = $request->status_id_edit;
                       $part->quality_id = $request->quality_id_edit;
                       $part->creation_date = Carbon::now();
                       $part->part_type_id = 3;
                       $part->save();

                       $store = Store::where('id', $request->store_id_edit)->first();

                       $Ac_inv = Replyorder::where('order_supplier_id', $orderSupplier->id)
                       ->where('part_id', $tractor_id)
                       ->where('source_id', $request->source_id_edit)
                       ->where('status_id', $request->status_id_edit)
                       ->where('quality_id', $request->quality_id_edit)
                       ->where('part_type_id', 3)
                       ->with('order_supplier')->first();

                       $Ac_currency_id = $Ac_inv->order_supplier->currency_id;
                       $Ac_currency_date = $Ac_inv->order_supplier->confirmation_date;
                       $Ac_all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                           return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                       }])->where('id', $Ac_currency_id)->get();

                       $Ac_price = 1 * $Ac_all_currency_types[0]->currencies[0]->value * $Ac_inv->price;

                       array_push($quaditems, (object) ['acountant_id' => $store->accountant_number, 'madin' => $Ac_price- $totalTaxEgp+$toalCoasts, 'dayin' => 0]); // المخزن مدين

                       $Actotal += $Ac_price;


                       $ratio = floatval($request->InvCoasting_edit) / floatval($request->invTotLbl_edit);
                       $itemCoast = ($ratio *  (floatval($request->price_edit) * 1 )) / 1;

                       $allpart = new AllTractor();
                       $allpart->part_id = $tractor_id;
                       $allpart->order_supplier_id = $orderSupplier->id;
                       $allpart->amount = 1;
                       $allpart->source_id = $request->source_id_edit;
                       $allpart->status_id = $request->status_id_edit;
                       $allpart->quality_id = $request->quality_id_edit;
                       $allpart->buy_price = $request->price_edit;
                       $allpart->insertion_date = Carbon::now();
                       $allpart->remain_amount = 1;
                       $allpart->flag = 3;
                       $allpart->buy_costing = $itemCoast;
                       $allpart->save();

                       if (isset($request->storeSectionId_edit)) {

                           $storelog = new StoresLog();
                           $storelog->All_part_id = $allpart->id;
                           $storelog->store_id = $request->store_id_edit;
                           $storelog->store_action_id = 1;
                           $storelog->amount = 1;
                           $storelog->date = Carbon::now();
                           $storelog->status = 1;
                           $storelog->type_id = 3;
                           $storelog->save();

                           $storelog = new StoresLog();
                           $storelog->All_part_id = $allpart->id;
                           $storelog->store_id = $request->store_id_edit;
                           $storelog->store_action_id = 3;
                           $storelog->amount = 1;
                           $storelog->date = Carbon::now();
                           $storelog->status = 3;
                           $storelog->type_id = 3;
                           $storelog->save();


                           $store = Store::where('id', $request->store_id_edit)->get();

                           $storeClsName = ucfirst($store[0]->table_name);
                           $storeClsName = 'App\Models\\' . $storeClsName;
                           $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);

                           // return $storeClsName;
                           if ($store[0]->table_name == "damaged_parts") {
                               $storeClsName = "App\Models\\DamagedPart";
                           }
                           try {
                               //code...
                               $storeCls = new $storeClsName();

                               $storeCls->part_id = $tractor_id;
                               $storeCls->amount = 1;
                               $storeCls->supplier_order_id = $orderSupplier->id;
                               $storeCls->type_id = 3;
                               $storeCls->store_log_id = $storelog->id;
                               $storeCls->date = Carbon::now();
                               $storeCls->save();


                               $section = new StoreSection();
                               $section->store_id = $request->store_id_edit;
                               $section->section_id = $request->storeSectionId_edit;
                               $section->order_supplier_id = $orderSupplier->id;
                               $section->type_id = 3;
                               $section->part_id = $tractor_id;
                               $section->source_id = $request->source_id_edit;
                               $section->status_id = $request->status_id_edit;
                               $section->quality_id = $request->quality_id_edit;
                               $section->amount =1;
                               $section->date = Carbon::now();
                               $section->save();
                           } catch (\Throwable $th) {

                           }
                       }


                       // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> 0 , 'dayin'=>$Actotal ] ); // المخزون دائن
                       array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => 0, 'dayin' => $Actotal- $totalTaxEgp+$toalCoasts]); // المشتريات دائن
                       $date = Carbon::now();
                       $type = null;
                       $notes = 'حركة مخزون';
                       $qyadidss = $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
                       Qayditem::where('qaydid', $qyadidss)->update([
                           'invoiceid' => $BuyTransaction->id
                       ]);


                       DB::commit();
                       session()->flash("success", "تم صرف المبلغ  بنجاح");
                       return redirect()->to('printBuyInvoice/' . $BuyTransaction->id);
                   } else {
                        DB::rollback();
                        session()->flash("success", "  المبلغ غير كافي في الخزنة" .   $bank);
                       return redirect()->back();

                   }
               } else {
                    DB::rollback();
                   session()->flash("success", "   لا يوجد رصيد في الخزنة   ");
                   return redirect()->back();
               }
           } else {
                DB::rollback();
               return back()->with('success', 'please Select Supplier and Currency!.');
           }

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash("success", "لا يمكن التعديل".$e->getMessage());
            return redirect()->back();
        }

         /////////////////////////////////////////////////////////////
        // BuyTransaction::create(['date'=> Carbon::now() ,
        // 'desc'=> 'Tractor',
        // 'company_id'=> $request->company_id,
        // 'name' => Carbon::now(),
        // 'final'=> '0' ,
        // 'creation_date'=> Carbon::now()]);

        // $transaction_id = BuyTransaction::max('id');
        // if(isset($request->invoiceImg_edit)){
        //     for ($i=0; $i < count($request->invoiceImg_edit) ; $i++) {
        //         $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->invoiceImg_edit[$i]->getClientOriginalName());
        //         $imageName = time() . $namewithoutchar.'.'.$request->invoiceImg_edit[$i]->extension() ;
        //         $request->invoiceImg_edit[$i]->move(public_path('assets/invoice_images'), $imageName);
        //         InvoiceImage::create(['part_id' => $tractor->id ,'part_type_id' => '3' ,'name' => $namewithoutchar,  'image_name' => $imageName ]);

        //     }
        // }
        // OrderSupplier::create(['user_id'=> Auth::user()->id ,'transaction_id' => $transaction_id , 'supplier_id' => $request->supplier_id_edit,'status' =>'0' , 'total_price' => $request->price_edit ,'deliver_date'=> $request->deliverydate_edit , 'currency_id' => $request->currency_id_edit ,'confirmation_date' => Carbon::now() ]);
        // $order_supplier_id = OrderSupplier::max('id');
        // Replyorder::create(['part_id' => $tractor->id , 'amount' => '1' , 'order_supplier_id' => $order_supplier_id , 'creation_date' => Carbon::now(), 'part_type_id' =>'3' , 'price' => $request->price_edit, 'source_id' => $request->source_id_edit ,'quality_id' => $request->quality_id_edit, 'status_id' => $request->status_id_edit ]);
        // AllTractor::create(['part_id' => $tractor->id , 'order_supplier_id' => $order_supplier_id , 'amount' => '1' , 'source_id' => $request->source_id_edit , 'status_id' => $request->status_id_edit , 'buy_price' => $request->price_edit , 'remain_amount' => '1' , 'flag' => '3' , 'quality_id' => $request->quality_id_edit , 'lastupdate' => Carbon::now()]);
        // $all_tractor_id = AllTractor::max('id');
        // StoresLog::create(['All_part_id' => $all_tractor_id , 'store_action_id' => '3' , 'store_id' => $request->store_id_edit , 'amount' => '1' , 'date' => Carbon::now() , 'status' => '3' , 'type_id' => '3']);
        // $store_log_id = StoresLog::max('id');
        // $store = Store::where('id' , $request->store_id_edit)->get();
        // $storeClsName=ucfirst($store[0]->table_name );
        //             $storeClsName ='App\Models\\'.$storeClsName;
        //             $storeClsName = str_replace([' ','_','-'],"",$storeClsName);
        //             if($store[0]->table_name == "damaged_parts"){
        //                 $storeClsName = "App\Models\\DamagedPart";
        //                 $storeCls = new $storeClsName();
        //                 $storeCls->all_part_id = $all_tractor_id ;
        //                 $storeCls->amount = '1';
        //                 $storeCls->supplier_order_id = $order_supplier_id;
        //                 $storeCls->type_id ='3'; ;
        //                 $storeCls->store_log_id =$store_log_id ;
        //                 $storeCls->date = Carbon::now();
        //                 $storeCls->save();
        //             }else{
        //                 try {
        //                     $storeCls = new $storeClsName();
        //                     $storeCls->part_id = $tractor->id ;
        //                     $storeCls->amount = '1';
        //                     $storeCls->supplier_order_id = $order_supplier_id;
        //                     $storeCls->type_id ='3'; ;
        //                     $storeCls->store_log_id =$store_log_id ;
        //                     $storeCls->date = Carbon::now();
        //                     $storeCls->save();

        //                 } catch (\Throwable $th) {
        //                 }
        //             }

        // return back()->with('success','You Have Successfully Updated Tractor.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,Tractor $tractor)
    {

        $tractor['id']=$request->part_id;
        $image_name= InvoiceImage::where('part_id' , $tractor->id)->where('part_type_id' , '3')->get('image_name');

        for($i=0 ; $i < count($image_name) ; $i++){
            $image_path = public_path('assets/invoice_images/'.$image_name);
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
        }
        $invoice_image= InvoiceImage::where('part_id' , $tractor->id)->where('part_type_id' , '3')->get();
        if(isset($invoice_image)){
            $invoice_image->each->delete();
        }

        $image_name = $tractor->efrag_images()->get('image_name');
        for($i=0 ; $i < count($image_name) ; $i++){
            $image_path = public_path('assets/efrag_images/'.$image_name);
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
        }
        $tractor->efrag_images()->delete();
        $image_name = $tractor->tractor_images()->get('url');
        for($i=0 ; $i < count($image_name) ; $i++){
            $image_path = public_path('assets/tractor_images/'.$image_name);
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
        }
        $tractor->tractor_images()->delete();
        $relatedTractor= RelatedTractor::where('tractor_id' ,$tractor->id)->get();
        if(isset($relatedTractor)){
            $relatedTractor->each->delete();
        }

        $tractor->tractor_details()->delete();
        $tractor_specs= Tractorspec::where('general_flag' , $tractor->id)->get();
        if(count($tractor_specs)>0){
        $tractor_specs->each->delete();
            }
        $all_tractor = $tractor->all_tractors()->first();
        if(isset($all_tractor)){
            $order_supplier_id = $all_tractor->order_supplier_id;
            $order_supplier = OrderSupplier::where('id' , $order_supplier_id)->first();
            $buy_transaction_id = $order_supplier->transaction_id;
            $buy_trnsaction = BuyTransaction::where('id' , $buy_transaction_id)->first();
            $store_log=StoresLog::where('All_part_id' , $all_tractor->id)->where('type_id' , '3')->first();
        }
        if(isset($store_log)){
            $store = Store::where('id' , $store_log->store_id)->first();

            $storeClsName=ucfirst($store->table_name );
            $storeClsName ='App\Models\\'.$storeClsName;
            $storeClsName = str_replace([' ','_','-'],"",$storeClsName);
            if($store->table_name == "damaged_parts"){
                $storeClsName = "App\Models\\DamagedPart";
                $storeCls = new $storeClsName();
                $toBeDeleted = $storeCls->where('all_part_id' , $all_tractor->id)->where('type_id' , '3')->delete();
            }else{
            $storeCls = new $storeClsName();
            $toBeDeleted = $storeCls->where('part_id' , $tractor->id)->where('type_id' , '3')->delete();
            }
        }

        if(isset($store_log)){
            StoresLog::find($store_log->id)->delete();
        }
        if(isset($all_tractor)){
            AllTractor::find($all_tractor->id)->delete();
        }
        $replyorder = Replyorder::where('part_id' , $tractor->id)->where('part_type_id' , '3')->first();
        if(isset($replyorder)){
            Replyorder::where('id', $replyorder->id)->delete();

        }
        if(isset($order_supplier)){
            OrderSupplier::where('id', $order_supplier_id)->delete();
        }
        if(isset($buy_trnsaction)){
            BuyTransaction::where('id', $buy_transaction_id)->delete();
        }
        Tractor::where('id' , $tractor->id)->delete();
        return back()->with('success','You Have Successfully Deleted Tractor.');
    }

    public function tractorspecs(){

        return TractorSpec::where('general_flag','0')->get();
    }

    public function tractorBrand(){
        $brandType = BrandType::all();
        $brand = Brand::all();
        return[$brandType , $brand];
    }

    public function tractormodel($brandId,$ttypeid){
        return Model::where('brand_id',$brandId)->where('type_id',$ttypeid)->get();
    }

    public function tractorseries($modelId){
        $series =  Series::where('model_id',$modelId)->with('model')->get();
        return $series;
    }

    public function printtractor($tractor_id){
        $tractor =  Tractor::where('id' , $tractor_id)

        ->with(['all_tractors'=>function($query){
            return $query->with(['order_supplier'=>function($query1){
                    return $query1->with(['buy_transaction'=>function($query2){
                        return $query2->get();
                }])->with('currency_type')
                    ->with('supplier')
                    ->get();
            }])
            ->with('source')
            ->with('status')
            ->with('part_quality')
            ->get();
        }])
        ->with(['tractor_details'=>function($query){
            return $query->with(['tractor_spec'=>function($query1){
                return $query1->get();
            }])->get();
        }])
        ->with(['series'=>function($query){
            return $query->with(['model'=>function($query1){
                    return $query1->with(['brand'=>function($query2){
                        return $query2->get();
                }])->get();
            }]);
        }])
        ->with('drives')
        ->with('gearbox')
        ->with('rearTires')
        ->with('frontTires')
        ->with('tractor_images')->first();

        // return $tractor;
        return view("printTractor",compact("tractor"));
    }
}
