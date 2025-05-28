<?php

namespace App\Http\Controllers;

use App\Models\Clark;
use App\Models\ClarkSpec;
use App\Models\ClarkNumber;
use App\Models\RelatedClark;
use App\Models\Series;
use App\Models\ClarkImage;
use App\Models\ClarkEfrag;
use App\Models\ClarkDetail;
use App\Models\BrandType;
use App\Models\Brand;
use App\Models\Model;
use App\Models\BuyTransaction;
use App\Models\OrderSupplier;
use App\Models\Replyorder;
use App\Models\AllClark;
use App\Models\BankSafeMoney;
use App\Models\BankType;
use App\Models\CurrencyType;
use App\Models\StoresLog;
use App\Models\Store;
use App\Models\EfragImages;
use App\Models\InvoiceImage;
use App\Models\MoneySafe;
use App\Models\Qayd;
use App\Models\QaydDeleted;
use App\Models\Qayditem;
use App\Models\QayditemDeleted;
use App\Models\SanadSarf;
use App\Models\StoreSection;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use File;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ClarksController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function indexWithRequest(Request $request)
    {
        if ($request->ajax()) {
            $data = Clark::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('clarkNumbers', function ($row) {
                    return isset($row->clark_number) ? $row->clark_number : '--';
                })

                ->addColumn('series', function ($row) {
                    return isset($row->series->name) ? $row->series->name : '--';
                })
                ->addColumn('clarkBrand', function ($row) {
                    return isset($row->series->model->brand->name) ? $row->series->model->brand->name : '--';
                })

                ->addColumn('Image', function ($row) {
                    $clarkImage = ClarkImage::where('clark_id', $row->id)->get();
                    $html = "";
                    foreach ($clarkImage as $key => $value) {
                        $html = $html . '<img class="rounded-circle header-profile-user" src="assets/clark_images/' . $value->image_name . '" alt="Emara">';
                    }
                    return $html;
                })
                ->addColumn('efragImage', function ($row) {
                    $efragImage = ClarkEfrag::where('clark_id', $row->id)->get();
                    $html = "";
                    foreach ($efragImage as $key => $value) {
                        $html = $html . '<img class="rounded-circle header-profile-user" src="assets/efrag_images/' . $value->image_name . '" alt="Emara">';
                    }
                    return $html;
                })

                ->addColumn('action', function ($row) {

                    $btn = '<a  data-bs-toggle="modal"  clark_id_value="' . $row->id . '"  data-bs-target="#editClark"
                    data-toggle="tooltip" data-original-title="Edit" class="btn btn-primary btn-sm d-inline-block editButton editClarkB "><i class="ri-edit-line editClarkButton"></i></a>';
                    $btn = $btn . ' <a  data-bs-toggle="modal"  clark_id_value2="' . $row->id . '"  data-bs-target="#deletepartB"
                    data-toggle="tooltip" data-original-title="Delete" class="btn btn-danger btn-sm d-inline-block deletepartB "><i class="ri-delete-bin-3-line deletePartButton"></i></a>';


                    // $btn = '<div class="row" style="width: fit-content;"><div class="px-1 mb-1 col"><a  data-bs-toggle="modal"  clark_id_value="'.$row->id.'"  data-bs-target="#editClark"
                    // data-toggle="tooltip" data-original-title="Edit" class="btn-sm editButton editClarkB"><i class="ri-edit-line editClarkButton"></i></a></div>';
                    // $btn = $btn.' <div class="px-1 col"><form action="'.route("clark.destroy",$row->id).'" method="POST">'
                    //         . csrf_field()
                    //         . method_field('DELETE')
                    //         .'<button type="submit" value="" class= "btn-sm deleteButton"><i class="ri-delete-bin-6-fill"></i></button>'.
                    //         '</form></div>';



                    $btn = $btn . '<a href="' . route("clark.print", $row->id) . '" class="btn-info printButton  btn-sm d-inline-block printClark mr-1"><i class=" ri-printer-fill"></i></a>';
                    return $btn;
                })
                ->rawColumns(['name', 'clarkNumbers', 'series', 'clarkBrand', 'Image', 'efragImage', 'action'])
                ->make(true);
        }
    }

    public function indexWithID(Clark $clarkId)
    {

        $all_clark = AllClark::where('part_id', $clarkId->id)->with('sections')->get();


        $clark = Clark::where('id', $clarkId->id)
            ->with(['all_clarks' => function ($query) {
                return $query->with(['order_supplier' => function ($query1) {
                    return $query1->with(['replyorders' => function ($query2) {
                        return $query2->get();
                    }])->get();
                }])->with('sections');
            }])

            ->with(['clark_details' => function ($query3) {
                return $query3->with(['clark_spec' => function ($query4) {
                    return $query4->get();
                }])->get();
            }])

            ->with(['series' => function ($query8) {
                return $query8->with(['model' => function ($query9) {
                    return $query9->with('brand')->with('brand_type')->get();
                }])->get();
            }])
            ->with('clark_images')
            ->with('clark_efrags')
            ->with(['invoice_images' => function ($query5) {
                return $query5->where('part_type_id', '4')->get();
            }])
            ->with(['related_clarks' => function ($query6) {
                return $query6->with(['part' => function ($query7) {
                    return $query7->get();
                }])->get();
            }])
            ->get();
        if (count($all_clark) > 0) {
            $store_log = StoresLog::where('All_part_id', $all_clark[0]->id)->where('type_id', '4')->get();
        } else {
            $store_log = [];
        }

        return ([$clark, $store_log]);
    }

    public function index()
    {
        return Clark::all();
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
        DB::beginTransaction();
        try {
            Clark::create($request->all());
            $clark_id = Clark::max('id');

            if (isset($request->specs)) {
                for ($i = 0; $i < count($request->specs); $i++) {
                    if ($request->specs[$i] != null) {
                        $specs = new ClarkSpec();
                        $specs->name = $request->specs[$i];
                        $specs->general_flag = $clark_id;
                        $specs->save();
                        $specDetail = new ClarkDetail();
                        $specDetail->partspecs_id = $specs->id;
                        $specDetail->clark_id = $clark_id;
                        if (isset($request->specsval)) {
                            $specDetail->value = $request->specsval[$i];
                            $specDetail->save();
                        }
                        $specDetail->save();
                    }
                }
            }
            if (isset($request->oldSpecs)) {
                for ($j = 0; $j < count($request->oldspecsval); $j++) {
                    if ($request->oldspecsval[$j] != null) {
                        ClarkDetail::create(['partspecs_id' => $request->oldSpecs[$j], 'clark_id' => $clark_id, 'value' => $request->oldspecsval[$j]]);
                    } else {
                        continue;
                    }
                }
            }

            if (isset($request->relatedPart)) {
                for ($i = 0; $i < count($request->relatedPart); $i++) {
                    RelatedClark::create(['clark_id' => $clark_id, 'sug_part_id' => $request->relatedPart[$i]]);
                }
            }
            if (isset($request->clarkImg)) {
                for ($i = 0; $i < count($request->clarkImg); $i++) {
                    $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->clarkImg[$i]->getClientOriginalName());
                    $imageName = time() . $namewithoutchar . '.' . $request->clarkImg[$i]->extension();
                    $request->clarkImg[$i]->move(public_path('assets/clark_images'), $imageName);
                    clarkImage::create(['clark_id' => $clark_id, 'name' => $namewithoutchar,  'image_name' => $imageName]);
                }
            }

            if (isset($request->releaseImg)) {
                for ($i = 0; $i < count($request->releaseImg); $i++) {
                    $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->releaseImg[$i]->getClientOriginalName());
                    $imageName = time() . $namewithoutchar . '.' . $request->releaseImg[$i]->extension();
                    $request->releaseImg[$i]->move(public_path('assets/efrag_images'), $imageName);
                    ClarkEfrag::create(['clark_id' => $clark_id, 'name' => $namewithoutchar,  'image_name' => $imageName, 'company_id' => $request->company_id]);
                }
            }

            $quaditems = [];
            $automaicQayd = new QaydController();
            $invoiceac = 0;
            $taxac = 0;
            $binvoiceac = 0;
            $totalTaxEgp = 0;

            $buyCoastAc = 0;
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

            $totalTaxEgp = ($taxac+$taxAc2) * $Ac_all_currency_types[0]->currencies[0]->value;

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
                            $BuyTransaction->desc = 'Clark';
                            $BuyTransaction->final = '3';
                            $BuyTransaction->save();
                            if (isset($request->invoiceImg)) {
                                for ($i = 0; $i < count($request->invoiceImg); $i++) {
                                    $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->invoiceImg[$i]->getClientOriginalName());
                                    $imageName = time() . $namewithoutchar . '.' . $request->invoiceImg[$i]->extension();
                                    $request->invoiceImg[$i]->move(public_path('assets/invoice_images'), $imageName);
                                    InvoiceImage::create(['part_id' => $clark_id, 'name' => $namewithoutchar,  'image_name' => $imageName, 'part_type_id' => '4']);
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
                                'money' => ($request->invPaied * $all_currency_types[0]->currencies[0]->value)+$buyCoastAc,
                                'total' => $Ac_allTotal_total - ($request->invPaied * $all_currency_types[0]->currencies[0]->value)-$buyCoastAc,
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
                                'money' => ($request->invPaied * $all_currency_types[0]->currencies[0]->value)+$buyCoastAc,
                                'total' => $Ac_allTotal_total - ($request->invPaied * $all_currency_types[0]->currencies[0]->value)-$buyCoastAc,
                                'type_money' => '1',
                                'user_id' => Auth::user()->id,
                                'store_id' => $xx->id,

                            ]);
                        }

                        if ($request->invPaied > 0) {
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
                        $part->part_id = $clark_id;
                        $part->price = $request->price;
                        $part->amount = 1;
                        $part->source_id = $request->source_id;
                        $part->status_id = $request->status;
                        $part->quality_id = $request->quality_id;
                        $part->creation_date = Carbon::now();
                        $part->part_type_id = 4;
                        $part->save();

                        $store = Store::where('id', $request->store_id)->first();

                        $Ac_inv = Replyorder::where('order_supplier_id', $orderSupplier->id)
                            ->where('part_id', $clark_id)
                            ->where('source_id', $request->source_id)
                            ->where('status_id', $request->status)
                            ->where('quality_id', $request->quality_id)
                            ->where('part_type_id', 4)
                            ->with('order_supplier')->first();

                        $Ac_currency_id = $Ac_inv->order_supplier->currency_id;
                        $Ac_currency_date = $Ac_inv->order_supplier->confirmation_date;
                        $Ac_all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                            return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                        }])->where('id', $Ac_currency_id)->get();

                        $Ac_price = 1 * $Ac_all_currency_types[0]->currencies[0]->value * $Ac_inv->price;

                        array_push($quaditems, (object) ['acountant_id' => $store->accountant_number, 'madin' => $Ac_price - $totalTaxEgp + $toalCoasts, 'dayin' => 0]); // المخزن مدين

                        $Actotal += $Ac_price;


                        $ratio = floatval($request->InvCoasting) / floatval($request->invTotLbl);
                        $itemCoast = ($ratio *  (floatval($request->price) * 1)) / 1;

                        $allpart = new AllClark();
                        $allpart->part_id = $clark_id;
                        $allpart->order_supplier_id = $orderSupplier->id;
                        $allpart->amount = 1;
                        $allpart->source_id = $request->source_id;
                        $allpart->status_id = $request->status;
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
                            $storelog->type_id = 4;
                            $storelog->save();

                            $storelog = new StoresLog();
                            $storelog->All_part_id = $allpart->id;
                            $storelog->store_id = $request->store_id;
                            $storelog->store_action_id = 3;
                            $storelog->amount = 1;
                            $storelog->date = Carbon::now();
                            $storelog->status = 3;
                            $storelog->type_id = 4;
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

                                $storeCls->part_id = $clark_id;
                                $storeCls->amount = 1;
                                $storeCls->supplier_order_id = $orderSupplier->id;
                                $storeCls->type_id = 4;
                                $storeCls->store_log_id = $storelog->id;
                                $storeCls->date = Carbon::now();
                                $storeCls->save();


                                $section = new StoreSection();
                                $section->store_id = $request->store_id;
                                $section->section_id = $request->storeSectionId;
                                $section->order_supplier_id = $orderSupplier->id;
                                $section->type_id = 4;
                                $section->part_id = $clark_id;
                                $section->source_id = $request->source_id;
                                $section->status_id = $request->status;
                                $section->quality_id = $request->quality_id;
                                $section->amount = 1;
                                $section->date = Carbon::now();
                                $section->save();
                            } catch (\Throwable $th) {
                                 DB::rollback();
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


                        DB::commit();
                        session()->flash("success", "تم صرف المبلغ  بنجاح");
                        return redirect()->to('printBuyInvoice/' . $BuyTransaction->id);
                    } else {
                        DB::rollback();
                        session()->flash("success", "  المبلغ غير كافي في الخزنة");
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
        }  catch (\Exception $e) {
            DB::rollback();
            session()->flash("success", "لا يمكن الحفظ".$e->getMessage());
            return redirect()->back();
        }

        /********************************************************************** */
    }


    /**
     * Display the specified resource.
     */
    public function show(Clark $clark)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clark $clark)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clark $clark)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $buy_transaction_id = 0;
            $ckark_id = $clark->id;
            $imgURLsInp = explode(',', $request->imgURLsInp[0]);
            $imgURLsInpInvoice = explode(',', $request->imgURLsInpInvoice[0]);
            $imgURLsInpEfrag = explode(',', $request->imgURLsInpEfrag[0]);

            for ($i = 0; $i < count($imgURLsInpInvoice); $i++) {
                if ($imgURLsInpInvoice[$i] != null) {
                    $clark->invoice_images()->where('image_name', $imgURLsInpInvoice[$i])->delete();
                }
            }

            for ($i = 0; $i < count($imgURLsInpEfrag); $i++) {
                if ($imgURLsInpEfrag[$i] != null) {
                    $clark->clark_efrags()->where('image_name', $imgURLsInpEfrag[$i])->delete();
                }
            }
            for ($i = 0; $i < count($imgURLsInp); $i++) {
                if ($imgURLsInp[$i] != null) {
                    $clark->clark_images()->where('image_name', $imgURLsInp[$i])->delete();
                }
            }

            $relatedClark = RelatedClark::where('clark_id', $clark->id)->get();
            if (isset($relatedClark)) {
                $relatedClark->each->delete();
            }

            $clark->clark_details()->delete();
            $clark_specs = ClarkSpec::where('general_flag', $clark->id)->get();
            if (count($clark_specs) > 0) {
                $clark_specs->each->delete();
            }

            $all_clark = $clark->all_clarks()->first();
            if (isset($all_clark)) {
                $order_supplier_id = $all_clark->order_supplier_id;
                $order_supplier = OrderSupplier::where('id', $order_supplier_id)->first();
                $buy_transaction_id = $order_supplier->transaction_id;
                $buy_trnsaction = BuyTransaction::where('id', $buy_transaction_id)->first();
                $store_log = StoresLog::where('All_part_id', $all_clark->id)->where('type_id', '4')->first();
            }

            if (isset($store_log)) {
                $store = Store::where('id', $store_log->store_id)->first();
                $storeClsName = ucfirst($store->table_name);
                $storeClsName = 'App\Models\\' . $storeClsName;
                $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                if ($store->table_name == "damaged_parts") {
                    $storeClsName = "App\Models\\DamagedPart";
                    $storeCls = new $storeClsName();
                    $toBeDeleted = $storeCls->where('all_part_id', $all_clark->id)->where('type_id', '4')->delete();
                } else {
                    $storeCls = new $storeClsName();
                    $toBeDeleted = $storeCls->where('part_id', $clark->id)->where('type_id', '4')->delete();
                }
            }

            if (isset($store_log)) {
                StoresLog::find($store_log->id)->delete();
            }
            if (isset($all_clark)) {
                AllClark::find($all_clark->id)->delete();
            }
            $replyorder = Replyorder::where('part_id', $clark->id)->where('part_type_id', '4')->first();

            if (isset($replyorder)) {
                Replyorder::where('id', $replyorder->id)->delete();
            }
            // if(isset($order_supplier)){
            //     OrderSupplier::where('id', $order_supplier_id)->delete();
            // }
            // if(isset($buy_trnsaction)){
            //     BuyTransaction::where('id', $buy_transaction_id)->delete();
            // }
            $clark->update(['name' => $request->name_edit, 'eng_name' => $request->name_en_edit, 'desc' => $request->desc_edit, 'serivcedate' => $request->serivcedate_edit, 'motor_number' => $request->motor_number_edit, 'clark_number' => $request->clark_number_edit, 'hours' => $request->hours_edit, 'color' => $request->color_edit, 'year' => $request->year_edit, 'front_tire' => $request->front_tire_edit, 'front_tire_status' => $request->front_tire_status_edit, 'rear_tire' => $request->rear_tire_edit, 'rear_tire_status' => $request->rear_tire_status_edit, 'company_id' => $request->company_id, 'tank' => $request->tank_edit, 'power' => $request->power_edit, 'discs' => $request->discs_edit, 'status' => $request->status_edit, 'limit' => $request->limit_edit, 'active_limit' => $request->active_limit_edit, 'supplayer_id' => $request->supplier_id_edit, 'gear_box' => $request->gear_box_edit, 'currency_id' => $request->currency_id_edit, 'source_id' => $request->source_id_edit, 'quality_id' => $request->quality_id_edit, 'buy_price' => $request->price_edit, 'model_id' => $request->model_id_edit]);
            // return  $clark;
            if (isset($request->specsEdit)) {
                for ($i = 0; $i < count($request->specsEdit); $i++) {
                    if ($request->specsEdit[$i] != null) {
                        $specs = new ClarkSpec();
                        $specs->name = $request->specsEdit[$i];
                        $specs->general_flag = $clark->id;
                        $specs->save();
                        $specDetail = new ClarkDetail();
                        $specDetail->partspecs_id = $specs->id;
                        $specDetail->clark_id = $clark->id;
                        if (isset($request->specsvalEdit)) {
                            $specDetail->value = $request->specsvalEdit[$i];
                            $specDetail->save();
                        }
                        $specDetail->save();
                    }
                }
            }
            if (isset($request->oldSpecsEdit)) {
                for ($j = 0; $j < count($request->oldSpecsEdit); $j++) {
                    if ($request->oldSpecsEdit[$j] != null) {
                        ClarkDetail::create(['partspecs_id' => $request->oldSpecsEdit[$j], 'clark_id' => $clark->id, 'value' => $request->oldspecsvalEdit[$j]]);
                    } else {
                        continue;
                    }
                }
            }

            if (isset($request->relatedPart_edit)) {
                for ($i = 0; $i < count($request->relatedPart_edit); $i++) {
                    RelatedClark::create(['clark_id' => $clark->id, 'sug_part_id' => $request->relatedPart_edit[$i]]);
                }
            }

            if (isset($request->clarkImg_edit)) {
                for ($i = 0; $i < count($request->clarkImg_edit); $i++) {
                    $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->clarkImg_edit[$i]->getClientOriginalName());
                    $imageName = time() . $namewithoutchar . '.' . $request->clarkImg_edit[$i]->extension();
                    $request->clarkImg_edit[$i]->move(public_path('assets/clark_images'), $imageName);
                    ClarkImage::create(['clark_id' => $clark->id, 'name' => $namewithoutchar, 'image_name' => $imageName]);
                }
            }

            if (isset($request->releaseImg_edit)) {
                for ($i = 0; $i < count($request->releaseImg_edit); $i++) {
                    $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->releaseImg_edit[$i]->getClientOriginalName());
                    $imageName = time() . $namewithoutchar . '.' . $request->releaseImg_edit[$i]->extension();
                    $request->releaseImg_edit[$i]->move(public_path('assets/efrag_images'), $imageName);
                    ClarkEfrag::create(['clark_id' => $clark->id, 'name' => $namewithoutchar,  'image_name' => $imageName]);
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

            $editSup = Supplier::where('id', $request->supplier_id_edit)->first();

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

            if ($bank) {

                // $BankSafeMoneyTot  = BankSafeMoney::all()->last();
                $BankSafeMoneyTot  = BankSafeMoney::where('bank_type_id',$bank->id)->latest()->first();

                BankSafeMoney::create([
                    'notes' => 'تعديل كلارك ' . ' ' . $request->buyTransaction_id . ' ' . 'من مورد' . ' ' . $editSup->name,
                    'date' => date('Y-m-d'),
                    'flag' => 1,
                    'money' => ($xpaied * $xAc_all_currency_types[0]->currencies[0]->value) + $oldcoasts,
                    'total' => $BankSafeMoneyTot->total + ($xpaied * $xAc_all_currency_types[0]->currencies[0]->value) + $oldcoasts,
                    'type_money' => '0',
                    'user_id' => Auth::user()->id,
                    'store_id' => null,
                    'bank_type_id' => $bank->id,
                    'money_currency' => $xpaied,
                    'currency_id' => $xcurrency_id,

                ]);
            } else {


                $xxd = Store::where('safe_accountant_number', intval($xbank_account))->first();
                $MoneySafeTot = MoneySafe::where('store_id', $xxd->id)->latest()->first();
                MoneySafe::create([
                    'notes' => 'تعديل كلارك ' . ' ' . $request->buyTransaction_id . ' ' . 'من مورد' . ' ' . $editSup->name,
                    'date' => date('Y-m-d'),
                    'flag' => 1,
                    'money' => ($xpaied * $xAc_all_currency_types[0]->currencies[0]->value)+$oldcoasts,
                    'total' => $MoneySafeTot->total + ($xpaied * $xAc_all_currency_types[0]->currencies[0]->value)+$oldcoasts,
                    'type_money' => '0',
                    'user_id' => Auth::user()->id,
                    'store_id' => $xxd->id,

                ]);
            }

            // Delete Store Sections
            $StoreSection_deleted = StoreSection::where('order_supplier_id', $all_clark->order_supplier_id)->get();
            foreach ($StoreSection_deleted as $x) {
                $x->delete();  // Trigger deleting and deleted events
            }

            // sanad sarf تحت مع الانشاء الجديد لسند الصرف

            ////////////////////////// hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhereeeeeeeeeeeeeeeeeeeeeeeeee

            ////////////////
            if (isset($order_supplier)) {
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
            $totalTaxEgp = 0;
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

            // if ($buyCoastAc > 0) {
            //     array_push($quaditems, (object) ['acountant_id' => $request->store_idd_edit, 'dayin' => $buyCoastAc, 'madin' => 0]); // الضريبة مدين
            // }
            // *************************************************************************************
            $totalTaxEgp = ($taxac+$taxAc2) * $Ac_all_currency_types[0]->currencies[0]->value;


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

                $all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($currency_id, $xconfirmation_date) {
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
                            $BuyTransaction->desc = 'Clark';
                            $BuyTransaction->final = '3';
                            $BuyTransaction->save();
                            if (isset($request->imgURLsInpInvoice)) {
                                for ($i = 0; $i < count($request->imgURLsInpInvoice); $i++) {
                                    $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->imgURLsInpInvoice[$i]->getClientOriginalName());
                                    $imageName = time() . $namewithoutchar . '.' . $request->imgURLsInpInvoice[$i]->extension();
                                    $request->imgURLsInpInvoice[$i]->move(public_path('assets/invoice_images'), $imageName);
                                    InvoiceImage::create(['part_id' => $ckark_id, 'name' => $namewithoutchar,  'image_name' => $imageName, 'part_type_id' => '4']);
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
                            BankSafeMoney::create([
                                'notes' => 'صرف مبلغ فاتورة شراء رقم ' . ' ' . $BuyTransaction->id . ' ' . 'من مورد' . ' ' . $supplier->name,
                                'date' => date('Y-m-d'),
                                'flag' => 1,
                                'money' => ($request->invPaied_edit * $all_currency_types[0]->currencies[0]->value)+$buyCoastAc,
                                'total' => $Ac_allTotal_total - ($request->invPaied_edit * $all_currency_types[0]->currencies[0]->value)-$buyCoastAc,
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
                        $buy_trnsactionold = BuyTransaction::where('id', $buy_transaction_id)->first();
                        if ($xpaied * $xAc_all_currency_types[0]->currencies[0]->value  != $request->invPaied_edit * $Ac_all_currency_types[0]->currencies[0]->value) {

                            if ($request->invPaied_edit > 0) {
                                $mad = new SanadSarf();
                                $mad->client_sup_id = $supplier->id;
                                $mad->paied = $request->invPaied_edit * $all_currency_types[0]->currencies[0]->value;
                                $mad->date = Carbon::now();
                                $mad->pyment_method = $request->store_idd_edit;
                                $mad->type = 2;
                                $mad->save();

                                $old_sanad =  SanadSarf::where('client_sup_id', $oldOrderSup->supplier_id)
                                    ->where('date', '>=', explode(' ', $buy_trnsactionold->creation_date)[0])
                                    ->where('paied', $xpaied * $Ac_all_currency_types[0]->currencies[0]->value)
                                    ->first();

                                SanadSarf::where('id', $old_sanad->id)
                                    ->update([
                                        'paied' => 0,
                                        'note' => 'تم تعديل سند الصرف  بعد تعديل الفاتورة رقم ' . $buy_trnsactionold->id . ',وتم إنشاء سند صرف اخر رقم ' .  $mad->id . ',وكانت قيمة سند الصرف القديم ' .   $old_sanad->paied
                                    ]);
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
                        $part->part_id = $ckark_id;
                        $part->price = $request->price_edit;
                        $part->amount = 1;
                        $part->source_id = $request->source_id_edit;
                        $part->status_id = $request->status_edit;
                        $part->quality_id = $request->quality_id_edit;
                        $part->creation_date = Carbon::now();
                        $part->part_type_id = 4;
                        $part->save();

                        $store = Store::where('id', $request->store_id_edit)->first();

                        $Ac_inv = Replyorder::where('order_supplier_id', $orderSupplier->id)
                            ->where('part_id', $ckark_id)
                            ->where('source_id', $request->source_id_edit)
                            ->where('status_id', $request->status_edit)
                            ->where('quality_id', $request->quality_id_edit)
                            ->where('part_type_id', 4)
                            ->with('order_supplier')->first();

                        $Ac_currency_id = $Ac_inv->order_supplier->currency_id;
                        $Ac_currency_date = $Ac_inv->order_supplier->confirmation_date;
                        $Ac_all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                            return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                        }])->where('id', $Ac_currency_id)->get();

                        $Ac_price = 1 * $Ac_all_currency_types[0]->currencies[0]->value * $Ac_inv->price;

                        array_push($quaditems, (object) ['acountant_id' => $store->accountant_number, 'madin' => $Ac_price- $totalTaxEgp +$toalCoasts, 'dayin' => 0]); // المخزن مدين

                        $Actotal += $Ac_price;


                        $ratio = floatval($request->InvCoasting_edit) / floatval($request->invTotLbl_edit);
                        $itemCoast = ($ratio *  (floatval($request->price_edit) * 1)) / 1;

                        $allpart = new AllClark();
                        $allpart->part_id = $ckark_id;
                        $allpart->order_supplier_id = $orderSupplier->id;
                        $allpart->amount = 1;
                        $allpart->source_id = $request->source_id_edit;
                        $allpart->status_id = $request->status_edit;
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
                            $storelog->type_id = 4;
                            $storelog->save();

                            $storelog = new StoresLog();
                            $storelog->All_part_id = $allpart->id;
                            $storelog->store_id = $request->store_id_edit;
                            $storelog->store_action_id = 3;
                            $storelog->amount = 1;
                            $storelog->date = Carbon::now();
                            $storelog->status = 3;
                            $storelog->type_id = 4;
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

                                $storeCls->part_id = $ckark_id;
                                $storeCls->amount = 1;
                                $storeCls->supplier_order_id = $orderSupplier->id;
                                $storeCls->type_id = 4;
                                $storeCls->store_log_id = $storelog->id;
                                $storeCls->date = Carbon::now();
                                $storeCls->save();


                                $section = new StoreSection();
                                $section->store_id = $request->store_id_edit;
                                $section->section_id = $request->storeSectionId_edit;
                                $section->order_supplier_id = $orderSupplier->id;
                                $section->type_id = 4;
                                $section->part_id = $ckark_id;
                                $section->source_id = $request->source_id_edit;
                                $section->status_id = $request->status_edit;
                                $section->quality_id = $request->quality_id_edit;
                                $section->amount = 1;
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
                        session()->flash("success", "  المبلغ غير كافي في الخزنة");
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
            session()->flash("success", "لا يمكن التعديل" . $e->getMessage());
            return redirect()->back();
        }


        // BuyTransaction::create(['date'=> Carbon::now(),'desc'=> 'Clark' , 'company_id'=> $request->company_id,'name' => Carbon::now(),  'final'=> '0' ,'creation_date'=> Carbon::now()]);
        // $transaction_id = BuyTransaction::max('id');
        // if(isset($request->invoiceImg_edit)){
        //     for ($i=0; $i < count($request->invoiceImg_edit) ; $i++) {
        //         $namewithoutchar = preg_replace('/[^A-Za-z0-9]/', '', $request->invoiceImg_edit[$i]->getClientOriginalName());
        //         $imageName = time() . $namewithoutchar.'.'.$request->invoiceImg_edit[$i]->extension() ;
        //         $request->invoiceImg_edit[$i]->move(public_path('assets/invoice_images'), $imageName);
        //         InvoiceImage::create(['part_id' => $clark->id ,'part_type_id' => '4' ,'name' => $namewithoutchar,  'image_name' => $imageName ]);

        //     }
        // }

        // OrderSupplier::create(['user_id'=> Auth::user()->id ,'transaction_id' => $transaction_id , 'supplier_id' => $request->supplier_id_edit,'status' =>'0' , 'total_price' => $request->price_edit ,'deliver_date'=> $request->deliverydate_edit , 'currency_id' => $request->currency_id_edit ,'confirmation_date' => Carbon::now() ]);
        // $order_supplier_id = OrderSupplier::max('id');
        // Replyorder::create(['part_id' => $clark->id , 'amount' => '1' , 'order_supplier_id' => $order_supplier_id , 'creation_date' => Carbon::now(), 'part_type_id' =>'4' , 'price' => $request->price_edit, 'source_id' => $request->source_id_edit ,'quality_id' => $request->quality_id_edit, 'status_id' => $request->status_edit ]);
        // AllClark::create(['part_id' => $clark->id , 'order_supplier_id' => $order_supplier_id , 'amount' => '1' , 'source_id' => $request->source_id_edit , 'status_id' => $request->status_edit , 'buy_price' => $request->price_edit , 'remain_amount' => '1' , 'flag' => '3' , 'quality_id' => $request->quality_id_edit , 'lastupdate' => Carbon::now()]);
        // $all_clark_id = AllClark::max('id');
        // StoresLog::create(['All_part_id' => $all_clark_id , 'store_action_id' => '3' , 'store_id' => $request->store_id_edit , 'amount' => '1' , 'date' => Carbon::now() , 'status' => '3' , 'type_id' => '4']);
        // $store_log_id = StoresLog::max('id');
        // $store = Store::where('id' , $request->store_id_edit)->get();
        // $storeClsName=ucfirst($store[0]->table_name );
        //             $storeClsName ='App\Models\\'.$storeClsName;
        //             $storeClsName = str_replace([' ','_','-'],"",$storeClsName);
        //             if($store[0]->table_name == "damaged_parts"){
        //                 $storeClsName = "App\Models\\DamagedPart";
        //                 $storeCls = new $storeClsName();
        //                 $storeCls->all_part_id = $all_clark_id ;
        //                 $storeCls->amount = '1';
        //                 $storeCls->supplier_order_id = $order_supplier_id;
        //                 $storeCls->type_id ='4'; ;
        //                 $storeCls->store_log_id =$store_log_id ;
        //                 $storeCls->date = Carbon::now();
        //                 $storeCls->save();
        //             }else{
        //                 try {
        //                     $storeCls = new $storeClsName();

        //                     $storeCls->part_id = $clark->id ;
        //                     $storeCls->amount = '1';
        //                     $storeCls->supplier_order_id = $order_supplier_id;
        //                     $storeCls->type_id ='4'; ;
        //                     $storeCls->store_log_id =$store_log_id ;
        //                     $storeCls->date = Carbon::now();
        //                     $storeCls->save();

        //                 } catch (\Throwable $th) {
        //                 }
        //             }
        // return back()->with('success','You Have Successfully Updated Clark.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Clark $clark)
    {

        $clark['id'] = $request->part_id;
        $image_name = InvoiceImage::where('part_id', $clark->id)->where('part_type_id', '4')->get('image_name');
        for ($i = 0; $i < count($image_name); $i++) {
            $image_path = public_path('assets/invoice_images/' . $image_name);
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }
        $invoice_image = InvoiceImage::where('part_id', $clark->id)->where('part_type_id', '4')->get();
        if (isset($invoice_image)) {
            $invoice_image->each->delete();
        }

        $image_name = $clark->clark_efrags()->get('image_name');
        for ($i = 0; $i < count($image_name); $i++) {
            $image_path = public_path('assets/efrag_images/' . $image_name);
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }
        $efrag_image = ClarkEfrag::where('clark_id', $clark->id)->get();
        if (isset($efrag_image)) {
            $efrag_image->each->delete();
        }

        $image_name = $clark->clark_images()->get('image_name');
        for ($i = 0; $i < count($image_name); $i++) {
            $image_path = public_path('assets/clark_images/' . $image_name);
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }
        $clark_image = ClarkImage::where('clark_id', $clark->id)->get();
        if (isset($clark_image)) {
            $clark_image->each->delete();
        }
        $relatedClark = RelatedClark::where('clark_id', $clark->id)->get();
        if (isset($relatedClark)) {
            $relatedClark->each->delete();
        }

        $clark->clark_details()->delete();
        $clark_specs = Clarkspec::where('general_flag', $clark->id)->get();
        if (count($clark_specs) > 0) {
            $clark_specs->each->delete();
        }
        $all_clark = $clark->all_clarks()->first();
        if (isset($all_clark)) {
            $order_supplier_id = $all_clark->order_supplier_id;
            $order_supplier = OrderSupplier::where('id', $order_supplier_id)->first();
            $buy_transaction_id = $order_supplier->transaction_id;
            $buy_trnsaction = BuyTransaction::where('id', $buy_transaction_id)->first();
            $store_log = StoresLog::where('All_part_id', $all_clark->id)->where('type_id', '4')->first();
        }
        if (isset($store_log)) {
            $store = Store::where('id', $store_log->store_id)->first();
            $storeClsName = ucfirst($store->table_name);
            $storeClsName = 'App\Models\\' . $storeClsName;
            $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
            if ($store->table_name == "damaged_parts") {
                $storeClsName = "App\Models\\DamagedPart";
                $storeCls = new $storeClsName();
                $toBeDeleted = $storeCls->where('all_part_id', $all_clark->id)->where('type_id', '4')->delete();
            } else {
                $storeCls = new $storeClsName();
                $toBeDeleted = $storeCls->where('part_id', $clark->id)->where('type_id', '4')->delete();
            }
        }

        if (isset($store_log)) {
            StoresLog::find($store_log->id)->delete();
        }
        if (isset($all_clark)) {
            AllClark::find($all_clark->id)->delete();
        }
        $replyorder = Replyorder::where('part_id', $clark->id)->where('part_type_id', '4')->first();

        if (isset($replyorder)) {
            Replyorder::where('id', $replyorder->id)->delete();
        }
        if (isset($order_supplier)) {
            OrderSupplier::where('id', $order_supplier_id)->delete();
        }
        if (isset($buy_trnsaction)) {
            BuyTransaction::where('id', $buy_transaction_id)->delete();
        }
        Clark::where('id', $clark->id)->delete();
        return back()->with('success', 'You Have Successfully Deleted Clark.');
    }

    public function clarkspecs()
    {

        return ClarkSpec::where('general_flag', '0')->get();
    }

    public function clarkBrand()
    {
        $brandType = BrandType::all();
        $brand = Brand::all();
        return [$brandType, $brand];
    }

    public function clarkmodel($brandId, $ttypeid)
    {
        return Model::where('brand_id', $brandId)->where('type_id', $ttypeid)->get();
    }

    public function clarkseries($modelId)
    {
        $series =  Series::where('model_id', $modelId)->with('model')->get();
        return $series;
    }

    public function printclark($clark_id)
    {
        $clark =  Clark::where('id', $clark_id)

            ->with(['all_clarks' => function ($query) {
                return $query->with(['order_supplier' => function ($query1) {
                    return $query1->with(['buy_transaction' => function ($query2) {
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
            ->with(['clark_details' => function ($query) {
                return $query->with(['clark_spec' => function ($query1) {
                    return $query1->get();
                }])->get();
            }])
            ->with(['series' => function ($query) {
                return $query->with(['model' => function ($query1) {
                    return $query1->with(['brand' => function ($query2) {
                        return $query2->get();
                    }])->get();
                }]);
            }])
            ->with('rearTires')
            ->with('frontTires')
            ->with('clark_images')->first();
        // return $clark;
        return view("printClark", compact("clark"));
    }
}
