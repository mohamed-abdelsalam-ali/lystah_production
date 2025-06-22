<?php

namespace App\Http\Controllers;

use App\Models\BuyTransactionEfrag;

use App\Imports\ImportParts;
use App\Models\BuyTransaction;
use App\Models\PartQuality;
use App\Models\Source;
use App\Models\Status;
use Doctrine\DBAL\Schema\View;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Newqayd;
use App\Models\NQayd;
use DataTables;
// use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportUser;
use App\Models\AllKit;
use App\Models\AllPart;
use App\Models\AllWheel;
use App\Models\BankSafeMoney;
use App\Models\CurrencyType;
use App\Models\Kit;
use App\Models\KitNumber;
use App\Models\MoneySafe;
use App\Models\OrderSupplier;
use App\Models\StoreSection;
use App\Models\Part;
use App\Models\PartNumber;
use App\Models\Replyorder;
use App\Models\Store;
use App\Models\StoresLog;
use App\Models\Supplier;
use App\Models\BranchTree;
use App\Models\Client;
use App\Models\Qayditem;
use App\Models\Qayd;
use App\Models\QayditemDeleted;
use App\Models\QaydDeleted;

use App\Models\Wheel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\DataTables as DataTablesDataTables;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;
use App\Http\Controllers\QaydController;
use App\Models\AllClark;
use App\Models\AllEquip;
use App\Models\AllKitPartItem;
use App\Models\AllKitPartItemSection;
use App\Models\AllTractor;
use App\Models\BankType;
use App\Models\Clark;
use App\Models\Equip;
use App\Models\SalePricing;
use App\Models\SaleTypeRatio;
use App\Models\SanadSarf;
use App\Models\Tractor;

class StoreManageController extends Controller
{
    /** فاتورة شراء
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        // return BuyTransaction::all();
        $bank_types = BankType::all();
        $store_safe = Store::where('safe_accountant_number', '<>', '0')->get();
        // $lastInvId = BuyTransaction::latest()->first()->id;
        return view('buy_invoice', compact('bank_types', 'store_safe'));
    }
    public function GetAllstores()
    {
        return Store::all();
    }
    public function indexWithRequest(Request $request)
    {
        if ($request->ajax()) {
            $data = BuyTransaction::with('company')->with('efrag')->with('order_suppliers.user')->with('order_suppliers.replyorders')->get();
            // return $data;
            return FacadesDataTables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    // $dd = explode(' ', $row->date);
                    // return $dd[0];
                    return $row->date;
                })
                ->addColumn('efrag', function ($row) {
                    $btn = '';
                    foreach ($row->efrag as $key => $ef) {
                        // $btn .= '<a href="' . asset('assets/buytransaction/' . $ef->image_url) . '" target="_blank">' . $ef->image_title . '</a><br>';
                        // $btn .= '<a href="'. $ef->image_url.'" target="_blank">' . $ef->image_title . '</a><br>';
                        $btn .= '<li><a href="' . $ef->image_url . '" target="_blank">' . $ef->image_title . '</a><span class="close border border-danger rounded-circle px-1 pb-1" onclick="deleteBuyTransEfragImage(' . $ef->id . ')">x</span></li>';
                    }
                    return $btn;
                })
                ->addColumn('companyid', function ($row) {
                    return $row->company->id;
                })
                ->addColumn('companyName', function ($row) {
                    return $row->company->name;
                    //  return $row->order_suppliers[0]->user->username;
                })
                ->addColumn('qaydNo', function ($row) {
                    $qaydd = Qayditem::where('invoiceid', $row->id)->where('flag', 0)
                        ->distinct()
                        ->pluck('qaydid');
                    $btn = '';
                    foreach ($qaydd as $key => $value) {
                        $btn .= '<a target="_blank" href="/qayd/' . $value . '">' . $value . '</a>';
                        $btn .= '<br/>';
                    }
                    return $btn;
                    //  return $row->order_suppliers[0]->user->username;
                })
                ->addColumn('userName', function ($row) {
                    if (count($row->order_suppliers) > 0) {
                        if (isset($row->order_suppliers[0]->user)) {
                            return $row->order_suppliers[0]->user->username;
                        } else {
                            return "-";
                        }
                    } else {
                        return "-";
                    }
                })
                ->addColumn('supplierName', function ($row) {
                    $ordersup = OrderSupplier::where('transaction_id', $row->id)->with('supplier')->first();
                    if (isset($ordersup->supplier)) {
                        return $ordersup->supplier->name;
                    } else {
                        return "-----";
                    }
                })
                ->addColumn('action', function ($row) {
                    if (count($row->order_suppliers) > 0) {
                        if (count($row->order_suppliers[0]->replyorders) > 0) {
                            if ($row->desc == 'Tractor') {

                                $btn = '<a href="/tractors?search=' . $row->order_suppliers[0]->replyorders[0]->part_id . '" target="_blank" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editPost"><i class="ri-edit-line"></i></a>';
                                $btn = $btn . ' <a href="tractorprint/' . $row->order_suppliers[0]->replyorders[0]->part_id . '"  data-original-title="Print" class="btn btn-sm btn-success "><i class="ri-printer-line"></i></a>';

                                return $btn;
                            } elseif ($row->desc == 'Clark') {
                                $btn = '<a href="/clarks?search=' . $row->order_suppliers[0]->replyorders[0]->part_id . '" target="_blank" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editPost"><i class="ri-edit-line"></i></a>';
                                $btn = $btn . ' <a href="clarkprint/' . $row->order_suppliers[0]->replyorders[0]->part_id . '"  data-original-title="Print" class="btn btn-sm btn-success "><i class="ri-printer-line"></i></a>';

                                return $btn;
                                // clarkprint/17
                            } elseif ($row->desc == 'Equip') {
                                $btn = '<a href="/equips?search=' . $row->order_suppliers[0]->replyorders[0]->part_id . '" target="_blank" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editPost"><i class="ri-edit-line"></i></a>';
                                $btn = $btn . ' <a href="equipprint/' . $row->order_suppliers[0]->replyorders[0]->part_id . '"  data-original-title="Print" class="btn btn-sm btn-success "><i class="ri-printer-line"></i></a>';

                                return $btn;
                            } else {
                                $btn = '<a href="buyInv/' . $row->id . '" target="_blank" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editPost"><i class="ri-edit-line"></i></a>';

                                $btn = $btn . ' <a href="#" onclick="DeleteInv(' . $row->id . ')" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deletePost"><i class="ri-delete-bin-5-line"></i></a>';
                                $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Send to Store" class="btn btn-secondary btn-sm sendStore"><i class="ri-share-circle-line"></i></a>';
                                $btn = $btn . ' <a href="printBuyInvoice/' . $row->id . '"  data-original-title="Print" class="btn btn-sm btn-success "><i class="ri-printer-line"></i></a>';

                                return $btn;
                            }
                        } else {
                            return "";
                        }
                    } else {
                        return "";
                    }
                })
                ->addColumn('upload', function ($row) {
                    $btn = '<button class="btn btn-dark" data-bs-toggle="modal" data-val="' . $row->id . '" data-bs-target="#efragModel"><i class="ri-upload-2-fill"></i></button>';
                    return $btn;
                })
                ->rawColumns(['companyid', 'companyName', 'supplierName', 'action', 'user', 'qaydNo', 'efrag', 'upload'])
                ->make(true);
        }

        // $lastInvId = BuyTransaction::latest()->first()->id;
        return view('buy_invoice');
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
        // return $request;
        $BuyTransaction = new BuyTransaction();
        $BuyTransaction->company_id = '10';
        $BuyTransaction->date = $request->invDate;
        $BuyTransaction->creation_date = Carbon::now();
        $BuyTransaction->name = Carbon::now();
        $BuyTransaction->final = '3';
        $BuyTransaction->save();
        $sup_id = 0;
        if ($request->NewinvSupplier <>  null) {
            // add new Supplier
            $sup = new Supplier();
            $sup->name = $request->NewinvSupplier;
            $sup->save();
            $sup_id = $sup->id;
        } else {

            $sup_id = $request->invSupplier;
        }
        $orderSupplier = new OrderSupplier();
        $orderSupplier->transaction_id = $BuyTransaction->id;
        $orderSupplier->supplier_id = $sup_id;
        $orderSupplier->user_id = Auth::user()->id;
        $orderSupplier->pricebeforeTax = $request->invTotLbl;
        // $orderSupplier->send_mail = '';
        // $orderSupplier->notes = '';
        $orderSupplier->status = '4';
        $orderSupplier->deliver_date = Carbon::now();
        // $orderSupplier->currency_id = 400;
        $orderSupplier->currency_id = $request->currency_id;
        $orderSupplier->total_price = $request->invAllTotal;
        // $orderSupplier->bank_account = '';
        // $orderSupplier->container_size = '';
        $orderSupplier->confirmation_date = Carbon::now();
        // $orderSupplier->image_url = '';
        $orderSupplier->tax = $request->invTax;
        $orderSupplier->save();

        for ($i = 0; $i < count($request->partId); $i++) {
            # code...
            $ratiounit = getSmallUnit($request->unit[$i], $request->smallUnit[$i]);

            $part = new Replyorder();
            $part->order_supplier_id = $orderSupplier->id;
            $part->part_id = $request->partId[$i];
            $part->price = floatval($request->price[$i]) / floatval($ratiounit);
            $part->amount = floatval($request->amount[$i]) * floatval($ratiounit);
            $part->unit_id = $request->unit[$i];

            $part->source_id = $request->partSource[$i];
            $part->status_id = $request->partStatus[$i];
            $part->quality_id = $request->partQualty[$i];
            $part->creation_date = Carbon::now();
            $part->part_type_id = $request->types[$i];
           
            $part->save();

            if ($request->types[$i] == 1) {
                $allpart = new AllPart();
                $allpart->part_id = $request->partId[$i];;
                $allpart->order_supplier_id = $orderSupplier->id;
                $allpart->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                $allpart->source_id = $request->partSource[$i];
                $allpart->status_id = $request->partStatus[$i];
                $allpart->quality_id = $request->partQualty[$i];
                $allpart->buy_price = floatval($request->price[$i]) / floatval($ratiounit);
                $allpart->insertion_date = Carbon::now();
                $allpart->remain_amount = floatval($request->amount[$i]) * floatval($ratiounit);
                $allpart->flag = 3;
                $allpart->save();
            }

            ///// kit
            if ($request->types[$i] == 6) {
                $allpart = new AllKit();
                $allpart->part_id = $request->partId[$i];;
                $allpart->order_supplier_id = $orderSupplier->id;
                $allpart->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                $allpart->source_id = $request->partSource[$i];
                $allpart->status_id = $request->partStatus[$i];
                $allpart->quality_id = $request->partQualty[$i];
                $allpart->buy_price = floatval($request->price[$i]) / floatval($ratiounit);
                $allpart->insertion_date = Carbon::now();
                $allpart->remain_amount = floatval($request->amount[$i]) * floatval($ratiounit);
                $allpart->flag = 3;
                $allpart->save();
            }

            /// wheel
            if ($request->types[$i] == 2) {
                $allpart = new AllWheel();
                $allpart->part_id = $request->partId[$i];;
                $allpart->order_supplier_id = $orderSupplier->id;
                $allpart->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                $allpart->source_id = $request->partSource[$i];
                $allpart->status_id = $request->partStatus[$i];
                $allpart->quality_id = $request->partQualty[$i];
                $allpart->buy_price = floatval($request->price[$i]) / floatval($ratiounit);
                $allpart->insertion_date = Carbon::now();
                $allpart->remain_amount = floatval($request->amount[$i]) * floatval($ratiounit);
                $allpart->flag = 3;
                $allpart->save();
            }
        }

        return redirect()->to('printBuyInvoice/' . $BuyTransaction->id);
    }


    public function storeManage(Request $request)
    {


        // return $request;
        // return "فقط";

        DB::beginTransaction();
        try {


            $quaditems = [];
            $automaicQayd = new QaydController();
            $invoiceac = 0;
            $taxac = 0;
            $taxAc2 = 0;
            $binvoiceac = 0;
            $makzon_val = 0;
            $buyCoastAc = 0;
            // $request->invTax الضريبة
            // $request->invTotLbl السعر قبل الضريبة
            // $request->invPaied; المدفوع
            // $request->invAllTotal الاجمالي بعد الضريبة
            // $request->payment
            // $request->taxInvolved // شامل الضريبة
            // $request->taxkasmInvolved // شامل الضريبة الخصم
            // $request->currency_id // العملة

            $Ac_currency_id = $request->currency_id;
            $Ac_all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($Ac_currency_id) {
                return $query->where('to', null)->where('currency_id', $Ac_currency_id);
            }])->where('id', $Ac_currency_id)->get();

            if ($request->transCoast > 0) {
                // array_push($quaditems, (object) ['acountant_id' => 33101, 'dayin' => 0, 'madin' => $request->transCoast]);
                $buyCoastAc += $request->transCoast;
            }
            if ($request->insuranceCoast > 0) {
                // array_push($quaditems, (object) ['acountant_id' => 33102, 'dayin' => 0, 'madin' => $request->insuranceCoast]);
                $buyCoastAc += $request->insuranceCoast;
            }
            if ($request->customs > 0) {
                // array_push($quaditems, (object) ['acountant_id' => 33103, 'dayin' => 0, 'madin' => $request->customs]);
                $buyCoastAc += $request->customs;
            }
            if ($request->commition > 0) {
                // array_push($quaditems, (object) ['acountant_id' => 33104, 'dayin' => 0, 'madin' => $request->commition]);
                $buyCoastAc += $request->commition;
            }
            if ($request->otherCoast > 0) {
                // array_push($quaditems, (object) ['acountant_id' => 33105, 'dayin' => 0, 'madin' => $request->otherCoast]);
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
                $binvoiceac = floatval($invoiceac - $taxac);
            } else {
                // شامل
                $binvoiceac = floatval($request->invAllTotal / 1.14);
                $taxac = $request->invAllTotal - $binvoiceac; // الضريبة
                $invoiceac =  floatval($binvoiceac + $taxac);
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
                $makzon_val = $binvoiceac *  $Ac_all_currency_types[0]->currencies[0]->value;
                array_push($quaditems, (object) ['acountant_id' => $request->store_id, 'madin' => 0, 'dayin' => ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // الخزنة دائن

            } elseif ($request->payment == 1 && $invoiceac == $request->invPaied) // البيع شيك
            {
                // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
                array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value, 'dayin' => 0]); // المشتريات مدين
                $makzon_val = $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value;
                array_push($quaditems, (object) ['acountant_id' => $request->store_id, 'madin' => 0, 'dayin' => ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // بنك دائن


            } else // البيع أجل
            {
                // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
                array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value, 'dayin' => 0]); // المشتريات مدين
                $makzon_val = $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value;
                $acSupNo = Supplier::where('id', $request->invSupplier)->first()->accountant_number;

                array_push($quaditems, (object) ['acountant_id' => $acSupNo, 'madin' => 0, 'dayin' => ($invoiceac - $request->invPaied) * $Ac_all_currency_types[0]->currencies[0]->value]); // العميل دائن
                if ($request->store_id != 0) // خزنة
                {
                    if ($request->invPaied > 0) {
                        array_push($quaditems, (object) ['acountant_id' => $request->store_id, 'madin' => 0, 'dayin' => ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // الخزنة دائن
                    }
                } else // بنك
                {
                    if ($request->invPaied > 0) {
                        array_push($quaditems, (object) ['acountant_id' => $request->store_id, 'madin' => 0, 'dayin' => ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // بنك دائن
                    }
                }
            }

            if ($taxac > 0) {
                array_push($quaditems, (object) ['acountant_id' => 175, 'dayin' => 0, 'madin' => $taxac * $Ac_all_currency_types[0]->currencies[0]->value]); // الضريبة مدين
            }


            if ($taxAc2 != 0) {
                array_push($quaditems, (object) ['acountant_id' => 2637, 'dayin' => $taxAc2 * $Ac_all_currency_types[0]->currencies[0]->value * -1, 'madin' => 0]); // الضريبة مدين
            }



            // array_push ( $quaditems , (object) [ 'acountant_id'=> 15 , 'dayin'=> 1000 , 'madin'=>0 ] ); //  الضريبة
            // array_push ( $quaditems , (object) [ 'acountant_id'=> 15 , 'dayin'=> 1000 , 'madin'=>0 ] ); // المديونية
            // array_push ( $quaditems , (object) [ 'acountant_id'=> 15 , 'dayin'=> 1000 , 'madin'=>0 ] );// الخصم

            // array_push ( $quaditems , (object) [ 'acountant_id'=> 15 , 'dayin'=> 1000 , 'madin'=>0 ] ); // البنك / الشركة
            // array_push ( $quaditems , (object) [ 'acountant_id'=> 15 , 'dayin'=> 1000 , 'madin'=>0 ] ); // المورد







            if (isset($request->currency_id)  && isset($request->invSupplier)) {
                $currency_id = $request->currency_id;
                // if ($request->store_id == 0) {
                //     $total = BankSafeMoney::all()->last();
                // } else {
                //     $total = MoneySafe::where('store_id', $request->store_id)->latest()->first();
                // }

                $Ac_allTotal = 0;
                $bank = BankType::where('accountant_number', $request->store_id)->first();
                if ($bank) {

                    $raseed = $bank->bank_raseed;
                    $total_qabd = BankSafeMoney::where('bank_type_id', $bank->id)->where('type_money', 0)->sum('money'); //قبض
                    $total_sarf = BankSafeMoney::where('bank_type_id', $bank->id)->where('type_money', 1)->sum('money'); //صرف
                    $total = floatval($raseed) + floatval($total_qabd) - floatval($total_sarf);
                    $Ac_allTotal  =  BankSafeMoney::where('bank_type_id', $bank->id)->orderBy('id', 'DESC')->first();
                } else {

                    $bank = Store::where('safe_accountant_number', $request->store_id)->first();
                    $raseed = $bank->store_raseed;
                    $total_qabd = MoneySafe::where('store_id', $bank->id)->where('type_money', 0)->sum('money'); //قبض
                    $total_sarf = MoneySafe::where('store_id', $bank->id)->where('type_money', 1)->sum('money'); //صرف
                    $total = floatval($raseed) + floatval($total_qabd) - floatval($total_sarf);
                    $Ac_allTotal = MoneySafe::where('store_id', $bank->id)->orderBy('id', 'DESC')->first();
                }

                $all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($currency_id) {
                    return $query->where('to', null)->where('currency_id', $currency_id);
                }])->where('id', $currency_id)->get();
                if (isset($total)) {
                    if ($total >= $request->invPaied * $all_currency_types[0]->currencies[0]->value) {

                        $BuyTransaction = new BuyTransaction();
                        $BuyTransaction->company_id = '10';
                        $BuyTransaction->date = $request->invDate;
                        $BuyTransaction->creation_date = Carbon::now();
                        $BuyTransaction->name = Carbon::now();
                        $BuyTransaction->final = '3';
                        $BuyTransaction->save();
                        $sup_id = 0;
                        if ($request->NewinvSupplier <>  null) {
                            // add new Supplier
                            $sup = new Supplier();
                            $sup->name = $request->NewinvSupplier;
                            $sup->save();
                            $sup_id = $sup->id;
                        } else {

                            $sup_id = $request->invSupplier;
                        }


                        $supplierx = Supplier::where('id', $sup_id)->first();
                        if ($bank) {
                            $Ac_allTotal_total = 0;
                            if ($Ac_allTotal) {
                                $Ac_allTotal_total = $Ac_allTotal->total;
                            }
                            BankSafeMoney::create([
                                'notes' => 'صرف مبلغ فاتورة شراء رقم ' . ' ' . $BuyTransaction->id . ' ' . 'من مورد' . ' ' . $supplierx->name,
                                'date' => date('Y-m-d'),
                                'flag' => 1,
                                'money' => $request->invPaied * $all_currency_types[0]->currencies[0]->value,
                                'total' => $Ac_allTotal_total - ($request->invPaied * $all_currency_types[0]->currencies[0]->value),
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
                            $xx = Store::where('safe_accountant_number', $request->store_id)->first();
                            MoneySafe::create([
                                'notes' => 'صرف مبلغ فاتورة شراء رقم ' . ' ' . $BuyTransaction->id . ' ' . 'من مورد' . ' ' . $supplierx->name,
                                'date' => date('Y-m-d'),
                                'flag' => 1,
                                'money' => $request->invPaied * $all_currency_types[0]->currencies[0]->value,
                                'total' => $Ac_allTotal_total - ($request->invPaied * $all_currency_types[0]->currencies[0]->value),
                                'type_money' => '1',
                                'user_id' => Auth::user()->id,
                                'store_id' => $xx->id,

                            ]);
                        }


                        if ($request->invPaied > 0) {
                            $mad = new SanadSarf();
                            $mad->client_sup_id = $supplierx->id;
                            $mad->paied = $request->invPaied * $all_currency_types[0]->currencies[0]->value;
                            $mad->date = Carbon::now();
                            $mad->pyment_method = $request->store_id;
                            $mad->type = 2;
                            $mad->save();
                        }


                        $orderSupplier = new OrderSupplier();
                        $orderSupplier->transaction_id = $BuyTransaction->id;
                        $orderSupplier->supplier_id = $sup_id;
                        $orderSupplier->user_id = Auth::user()->id;
                        $orderSupplier->pricebeforeTax = $request->invTotLbl;
                        // $orderSupplier->notes = '';
                        $orderSupplier->status = '4';
                        $orderSupplier->deliver_date = Carbon::now();
                        // $orderSupplier->currency_id = 400;
                        $orderSupplier->currency_id = $request->currency_id;
                        $orderSupplier->total_price = $request->invAllTotal;
                        $orderSupplier->paied = $request->invPaied;
                        $orderSupplier->payment = $request->payment;
                        $orderSupplier->bank_account = $request->store_id;
                        $orderSupplier->transport_coast = $request->transCoast;
                        $orderSupplier->insurant_coast = $request->insuranceCoast;
                        $orderSupplier->customs_coast = $request->customs;
                        $orderSupplier->commotion_coast = $request->commition;
                        $orderSupplier->other_coast = $request->otherCoast;
                        $orderSupplier->coast = $request->InvCoasting;
                        $orderSupplier->taxInvolved_flag = $request->taxInvolved;
                        $orderSupplier->taxkasmInvolved_flag = $request->taxkasmInvolved;
                        if (floatval($request->taxInvolved) == 1) {
                            $orderSupplier->tax = 0;
                        } else {
                            $orderSupplier->tax = $request->invTax;
                        }
                        if ($request->invPaied < $request->invAllTotal) {
                            $orderSupplier->due_date = $request->dueDate;
                            // Supplier::where('id', $sup_id)->increment('raseed', (floatval($request->invAllTotal) - floatval($request->invPaied)) * $all_currency_types[0]->currencies[0]->value);
                        } else if ($request->invPaied > $request->invAllTotal) {
                            // Supplier::where('id', $sup_id)->decrement('raseed', (floatval($request->invPaied) - floatval($request->invAllTotal)) * $all_currency_types[0]->currencies[0]->value);
                        } else {
                        }
                        // $orderSupplier->bank_account = '';
                        // $orderSupplier->container_size = '';
                        $orderSupplier->confirmation_date = Carbon::now();
                        // $orderSupplier->image_url = '';
                        $orderSupplier->save();

                        for ($i = 0; $i < count($request->partId); $i++) {
                            # code...
                            $ratiounit = getSmallUnit($request->unit[$i], $request->smallUnit[$i]);

                            $part = new Replyorder();
                            $part->order_supplier_id = $orderSupplier->id;
                            $part->part_id = $request->partId[$i];
                            $part->price = floatval($request->price[$i]) / floatval($ratiounit);
                            $part->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                            $part->unit_id = $request->unit[$i];
                            $part->source_id = $request->partSource[$i];
                            $part->status_id = $request->partStatus[$i];
                            $part->quality_id = $request->partQualty[$i];
                            $part->creation_date = Carbon::now();
                            $part->part_type_id = $request->types[$i];
                            $part->save();

                            if ($request->types[$i] == 1) {
                                $ratio = floatval($request->InvCoasting) / floatval($request->invTotLbl);
                                $itemCoast = ($ratio *  (floatval($request->price[$i]) * floatval($request->amount[$i]) * floatval($ratiounit))) / (floatval($request->amount[$i]) * floatval($ratiounit));

                                $allpart = new AllPart();
                                $allpart->part_id = $request->partId[$i];
                                $allpart->order_supplier_id = $orderSupplier->id;
                                $allpart->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                $allpart->source_id = $request->partSource[$i];
                                $allpart->status_id = $request->partStatus[$i];
                                $allpart->quality_id = $request->partQualty[$i];
                                $allpart->buy_price = floatval($request->price[$i]) / floatval($ratiounit);
                                $allpart->insertion_date = Carbon::now();
                                $allpart->remain_amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                $allpart->flag = 1;
                                $allpart->buy_costing = $itemCoast;
                                $allpart->save();
                            }

                            ///// kit
                            if ($request->types[$i] == 6) {
                                $ratio = floatval($request->InvCoasting) / floatval($request->invTotLbl);
                                $itemCoast = ($ratio *  (floatval($request->price[$i]) * floatval($request->amount[$i]) * floatval($ratiounit))) / (floatval($request->amount[$i]) * floatval($ratiounit));


                                $allpart = new AllKit();
                                $allpart->part_id = $request->partId[$i];;
                                $allpart->order_supplier_id = $orderSupplier->id;
                                $allpart->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                $allpart->source_id = $request->partSource[$i];
                                $allpart->status_id = $request->partStatus[$i];
                                $allpart->quality_id = $request->partQualty[$i];
                                $allpart->buy_price = floatval($request->price[$i]) / floatval($ratiounit);
                                $allpart->insertion_date = Carbon::now();
                                $allpart->remain_amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                $allpart->flag = 1;
                                $allpart->buy_costing = $itemCoast;
                                $allpart->save();
                            }

                            /// wheel
                            if ($request->types[$i] == 2) {
                                $ratio = floatval($request->InvCoasting) / floatval($request->invTotLbl);
                                $itemCoast = ($ratio *  (floatval($request->price[$i]) * floatval($request->amount[$i]) * floatval($ratiounit))) / (floatval($request->amount[$i]) * floatval($ratiounit));

                                $allpart = new AllWheel();
                                $allpart->part_id = $request->partId[$i];;
                                $allpart->order_supplier_id = $orderSupplier->id;
                                $allpart->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                $allpart->source_id = $request->partSource[$i];
                                $allpart->status_id = $request->partStatus[$i];
                                $allpart->quality_id = $request->partQualty[$i];
                                $allpart->buy_price = floatval($request->price[$i]) / floatval($ratiounit);
                                $allpart->insertion_date = Carbon::now();
                                $allpart->remain_amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                $allpart->flag = 1;
                                $allpart->buy_costing = $itemCoast;
                                $allpart->save();
                            }
                        }
                        $date = Carbon::now();
                        $type = null;
                        $notes = 'فاتورة شراء رقم' . $BuyTransaction->id . 'العملة ' . $Ac_all_currency_types[0]->name;
                        $qaydids = $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
                        Qayditem::where('qaydid', $qaydids)->update([
                            'invoiceid' => $BuyTransaction->id
                        ]);



                        $quaditems = [];
                        $automaicQayd = new QaydController();

                        if ($request->transCoast > 0) {
                            array_push($quaditems, (object) ['acountant_id' => 33101, 'dayin' => 0, 'madin' => $request->transCoast]);
                        }
                        if ($request->insuranceCoast > 0) {
                            array_push($quaditems, (object) ['acountant_id' => 33102, 'dayin' => 0, 'madin' => $request->insuranceCoast]);
                        }
                        if ($request->customs > 0) {
                            array_push($quaditems, (object) ['acountant_id' => 33103, 'dayin' => 0, 'madin' => $request->customs]);
                        }
                        if ($request->commition > 0) {
                            array_push($quaditems, (object) ['acountant_id' => 33104, 'dayin' => 0, 'madin' => $request->commition]);
                        }
                        if ($request->otherCoast > 0) {
                            array_push($quaditems, (object) ['acountant_id' => 33105, 'dayin' => 0, 'madin' => $request->otherCoast]);
                        }

                        $date = Carbon::now();
                        $type = null;
                        $notes = 'مصروفات علي فاتورة شراء رقم ' . $BuyTransaction->id . 'للمعلومية فقط تم تحميل المصروفات علي المشتريات والمخزون';
                        $qaydids = $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
                        Qayditem::where('qaydid', $qaydids)->update([
                            'invoiceid' => $BuyTransaction->id
                        ]);


                        DB::commit();
                        return redirect()->to('printBuyInvoice/' . $BuyTransaction->id);
                    } else {
                        DB::rollback();
                        session()->flash("success", 'الرصيد غير كافي');
                        return back();
                    }
                } else {
                    DB::rollback();
                    session()->flash("success", 'لا يوجد رصيد ');
                    return back();
                }
            } else {
                DB::rollback();
                return back()->with('success', 'please Select Supplier and Currency!.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash("error", "error");
            return redirect()->back();
            // return redirect()->back();
        }
    }

  

    public function storeManage2(Request $request)
    {
       

        // return $request;

        // /************************************************************************************

        DB::beginTransaction();
        try {
            $quaditems = [];
            $automaicQayd = new QaydController();
            $invoiceac = 0;
            $taxac = 0;
            $binvoiceac = 0;
            $buyCoastAc = 0;
            $taxAc2 = 0;
            $totalTaxEgp = 0;
            $acSupNo = Supplier::where('id', $request->invSupplier)->first()->accountant_number;
            $Ac_currency_id = $request->currency_id;
            $Ac_all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($Ac_currency_id) {
                return $query->where('to', null)->where('currency_id', $Ac_currency_id);
            }])->where('id', $Ac_currency_id)->get();


            if ($request->transCoast > 0) {
                // array_push($quaditems, (object) ['acountant_id' => 33101, 'dayin' => 0, 'madin' => $request->transCoast]);
                $buyCoastAc += $request->transCoast;
            }
            if ($request->insuranceCoast > 0) {
                // array_push($quaditems, (object) ['acountant_id' => 33102, 'dayin' => 0, 'madin' => $request->insuranceCoast]);
                $buyCoastAc += $request->insuranceCoast;
            }
            if ($request->customs > 0) {
                // array_push($quaditems, (object) ['acountant_id' => 33103, 'dayin' => 0, 'madin' => $request->customs]);
                $buyCoastAc += $request->customs;
            }
            if ($request->commition > 0) {
                // array_push($quaditems, (object) ['acountant_id' => 33104, 'dayin' => 0, 'madin' => $request->commition]);
                $buyCoastAc += $request->commition;
            }
            if ($request->otherCoast > 0) {
                // array_push($quaditems, (object) ['acountant_id' => 33105, 'dayin' => 0, 'madin' => $request->otherCoast]);
                $buyCoastAc += $request->otherCoast;
            }


            if ($request->taslem_coast > 0) {
                $buyCoastAc += $request->taslem_coast;
            }
            if ($request->ardya_coast > 0) {
                $buyCoastAc += $request->ardya_coast;
            }
            if ($request->in_transport_coast > 0) {
                $buyCoastAc += $request->in_transport_coast;
            }
            if ($request->takhles_coast > 0) {
                $buyCoastAc += $request->takhles_coast;
            }
            if ($request->bank_coast > 0) {
                $buyCoastAc += $request->bank_coast;
            }
            if ($request->nolon_coast > 0) {
                $buyCoastAc += $request->nolon_coast;
            }

            if ($request->Safetotal >= $buyCoastAc) {
            } else {
                session()->flash("success", "   لا يوجد رصيد في الخزنة لسداد المصروفات   ");
                return redirect()->back();
            }

            if (floatval($request->notaxes) == 1) {
                if ($request->invTax) {
                    $taxac = $request->invTotLbl * $request->invTax / 100; // الضريبة

                } else {
                    $taxac = 0; // الضريبةno
                }

                $taxAc2 = 0;
                $invoiceac = $request->invTotLbl + $taxac; //الاجمالي بعد الضريبة
                $binvoiceac = floatval($invoiceac - $taxac);
            } else {
                if (floatval($request->taxInvolved) == 1) {
                    // غير شامل
                    if ($request->invTax) {
                        $taxac = $request->invTotLbl * $request->invTax / 100; // الضريبة
                    } else {
                        $taxac = $request->invTotLbl * 14 / 100; // الضريبة
                    }

                    $invoiceac = $request->invTotLbl + $taxac; //الاجمالي بعد الضريبة
                    $binvoiceac = floatval($invoiceac - $taxac);
                } else {
                    // شامل
                    $binvoiceac = floatval($request->invAllTotal / 1.14); //  المشتريات غير شامل الضريبة
                    $taxac = $request->invAllTotal - $binvoiceac; // الضريبة
                    $invoiceac =  floatval($binvoiceac + $taxac);
                }


                if (floatval($request->taxkasmInvolved) == 1) {
                    $taxAc2 = $request->invTotLbl * -1 / 100;
                    $invoiceac = $invoiceac + $taxAc2;
                } else {
                    $taxAc2 = 0;
                }
            }
            $totalTaxEgp = ($taxac + $taxAc2) * $Ac_all_currency_types[0]->currencies[0]->value;

            // return $totalTaxEgp;
            if ($request->payment == 0  && $invoiceac == $request->invPaied) // البيع كاش
            {

                // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac *  $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
                array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => ($binvoiceac *  $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc, 'dayin' => 0]); // المشتريات مدين
                array_push($quaditems, (object) ['acountant_id' => intval($request->store_id), 'madin' => 0, 'dayin' => ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // الخزنة دائن
                // array_push ( $quaditems , (object) [ 'acountant_id'=> $acSupNo , 'madin'=> 0 , 'dayin'=> ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value)+$buyCoastAc ] ); // العميل دائن

            } elseif ($request->payment == 1 && $invoiceac == $request->invPaied) // البيع شيك
            {
                // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
                array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => ($binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc, 'dayin' => 0]); // المشتريات مدين

                // array_push ( $quaditems , (object) [ 'acountant_id'=> $acSupNo , 'madin'=> 0 , 'dayin'=> ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value)+$buyCoastAc ] ); // العميل دائن


                array_push($quaditems, (object) ['acountant_id' => intval($request->store_id), 'madin' => 0, 'dayin' => ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // بنك دائن


            } else // البيع أجل
            {
                // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
                array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => ($binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc, 'dayin' => 0]); // المشتريات مدين


                array_push($quaditems, (object) ['acountant_id' => $acSupNo, 'madin' => 0, 'dayin' => ($invoiceac - $request->invPaied) * $Ac_all_currency_types[0]->currencies[0]->value]); // العميل دائن
                if (intval($request->store_id) != 0) // خزنة
                {
                    if ($request->invPaied > 0) {
                        array_push($quaditems, (object) ['acountant_id' => intval($request->store_id), 'madin' => 0, 'dayin' => ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // الخزنة دائن
                    }
                } else // بنك
                {
                    if ($request->invPaied > 0) {
                        array_push($quaditems, (object) ['acountant_id' => intval($request->store_id), 'madin' => 0, 'dayin' => ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // بنك دائن
                    }
                }
            }

            if ($taxac > 0) {
                array_push($quaditems, (object) ['acountant_id' => 175, 'dayin' => 0, 'madin' => $taxac * $Ac_all_currency_types[0]->currencies[0]->value]); // الضريبة مدين
            }
            if ($taxAc2 != 0) {
                array_push($quaditems, (object) ['acountant_id' => 2637, 'dayin' => $taxAc2 * $Ac_all_currency_types[0]->currencies[0]->value * -1, 'madin' => 0]); // الضريبة مدين
            }
            if ($buyCoastAc > 0) {
                // array_push($quaditems, (object) ['acountant_id' => $request->store_id, 'dayin' => $buyCoastAc, 'madin' => 0]); // الضريبة مدين
            }
            // *************************************************************************************



            if (isset($request->currency_id)  && isset($request->invSupplier)) {
                $currency_id = $request->currency_id;
                ////////////////////////adel////////////////////
                // if ($request->store_id == 0) {
                //     $total = BankSafeMoney::all()->last();
                // } else {
                //     $total = MoneySafe::where('store_id', $request->store_id)->latest()->first();
                // }
                $Ac_allTotal = 0;
                $bank = BankType::where('accountant_number', intval($request->store_id))->first();
                if ($bank) {
                    $raseed = $bank->bank_raseed;
                    $total_qabd = BankSafeMoney::where('bank_type_id', $bank->id)->where('type_money', 0)->sum('money'); //قبض
                    $total_sarf = BankSafeMoney::where('bank_type_id', $bank->id)->where('type_money', 1)->sum('money'); //صرف
                    $total = floatval($raseed) + floatval($total_qabd) - floatval($total_sarf);
                    $Ac_allTotal  =  BankSafeMoney::where('bank_type_id', $bank->id)->orderBy('id', 'DESC')->first();
                } else {

                    $store_bank = Store::where('safe_accountant_number', intval($request->store_id))->first();
                    $raseed = $store_bank->store_raseed;
                    $total_qabd = MoneySafe::where('store_id', $store_bank->id)->where('type_money', 0)->sum('money'); //قبض
                    $total_sarf = MoneySafe::where('store_id', $store_bank->id)->where('type_money', 1)->sum('money'); //صرف
                    $total = floatval($raseed) + floatval($total_qabd) - floatval($total_sarf);
                    $Ac_allTotal = MoneySafe::where('store_id', $store_bank->id)->orderBy('id', 'DESC')->first();
                }

                $all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($currency_id) {
                    return $query->where('to', null)->where('currency_id', $currency_id);
                }])->where('id', $currency_id)->get();

                if (isset($total)) {

                    if ($total >= ($request->invPaied * $all_currency_types[0]->currencies[0]->value)) {
                        // return $total;
                        if (isset($request->inv_id)) {
                            $BuyTransaction = BuyTransaction::where('id', $request->inv_id)->first();
                        } else {

                            $BuyTransaction = new BuyTransaction();

                            $BuyTransaction->company_id = '10';
                            $BuyTransaction->date = $request->invDate;
                            $BuyTransaction->creation_date = Carbon::now();
                            $BuyTransaction->name = Carbon::now();
                            $BuyTransaction->final = '3';
                            $BuyTransaction->save();
                        }

                        $nqayd = new NQayd();
                        $nqayd->partner_id = $request->invSupplier;
                        $nqayd->type = 'Supplier';
                        $nqayd->name = 'Purchases/' . date('Y') . '/' . date('m');
                        $nqayd->cost_center = '';
                        $nqayd->amount_currency = null;
                        $nqayd->currency_id = null;
                        $nqayd->credit = 0;
                        $nqayd->debit = 0;
                        $nqayd->desc = 'فاتورة شراء رقم ' . $BuyTransaction->id . ' . ';
                        $nqayd->user_id = Auth::user()->id;
                        $nqayd->flag = '0';
                        $nqayd->invoice_id = $BuyTransaction->id;
                        $nqayd->invoice_table = 'printBuyInvoice';
                        $nqayd->save();

                        $newqayd = new Newqayd();
                        $newqayd->refrence = 'BANK/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                        $newqayd->coa_id = 59;
                        $newqayd->journal_id = 7;   //BankJournal
                        $newqayd->partner_id = $request->invSupplier;;
                        $newqayd->type = 'Supplier';
                        $newqayd->label = 'Manual Payment for Bill/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;;
                        $newqayd->cost_center = '';
                        $newqayd->amount_currency = $request->invAllTotal;
                        $newqayd->currency_id = $request->currency_id;
                        // $newqayd->debit =  $request->invAllTotal * $Ac_all_currency_types[0]->currencies[0]->value;
                        $newqayd->debit =  ($request->invPaied * $all_currency_types[0]->currencies[0]->value);
                        $newqayd->credit = '0';
                        $newqayd->desc = 'When Confirm the bill ' . $BuyTransaction->id;
                        $newqayd->user_id = Auth::user()->id;
                        $newqayd->flag = '0';
                        $newqayd->invoice_id = $BuyTransaction->id;
                        $newqayd->invoice_table = 'printBuyInvoice';
                        $newqayd->qayds_id = $nqayd->id;
                        $newqayd->show_no = 3;
                        $newqayd->save();


                        $newqayd = new Newqayd();
                        $newqayd->refrence = 'BANK/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                        $newqayd->coa_id = 28;
                        $newqayd->journal_id = 7;   //BankJournal
                        $newqayd->partner_id = $request->invSupplier;;
                        $newqayd->type = 'Supplier';
                        $newqayd->label = 'Manual Payment for Custom Bill/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;;
                        $newqayd->cost_center = '';
                        $newqayd->amount_currency = $request->invAllTotal;
                        $newqayd->currency_id = $request->currency_id;
                        // $newqayd->debit =  $request->invAllTotal * $Ac_all_currency_types[0]->currencies[0]->value;
                        $newqayd->debit =  $buyCoastAc;
                        $newqayd->credit = '0';
                        $newqayd->desc = 'When Confirm the bill ' . $BuyTransaction->id;
                        $newqayd->user_id = Auth::user()->id;
                        $newqayd->flag = '0';
                        $newqayd->invoice_id = $BuyTransaction->id;
                        $newqayd->invoice_table = 'printBuyInvoice';
                        $newqayd->qayds_id = $nqayd->id;
                        $newqayd->show_no = 3;
                        $newqayd->save();

                        // return $BuyTransaction;
                        $sup_id = 0;
                        $supplier = Supplier::where('id', $request->invSupplier)->first();
                        if ($bank) {
                            $Ac_allTotal_total = 0;
                            if ($Ac_allTotal) {
                                $Ac_allTotal_total = $Ac_allTotal->total;
                            }

                            if ($request->invPaied * $all_currency_types[0]->currencies[0]->value + $buyCoastAc > 0) {
                                BankSafeMoney::create([
                                    'notes' => 'صرف مبلغ فاتورة شراء رقم ' . ' ' . $BuyTransaction->id . ' ' . 'من مورد' . ' ' . $supplier->name,
                                    'date' => date('Y-m-d'),
                                    'flag' => 1,
                                    'money' => ($request->invPaied * $all_currency_types[0]->currencies[0]->value) + $buyCoastAc,
                                    'total' => $Ac_allTotal_total - ($request->invPaied * $all_currency_types[0]->currencies[0]->value) - $buyCoastAc,
                                    'type_money' => '1',
                                    'user_id' => Auth::user()->id,
                                    'store_id' => null,
                                    'bank_type_id' => $bank->id,
                                    'money_currency' => $request->invPaied,
                                    'currency_id' => $currency_id,

                                ]);

                                $newqayd = new Newqayd();
                                $newqayd->refrence = 'BANK/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                                $newqayd->coa_id = 40;
                                $newqayd->journal_id = 7;   //BANKJOURNAL
                                $newqayd->partner_id = $request->invSupplier;;
                                $newqayd->type = 'Supplier';
                                $newqayd->label = 'Manual Payment for Bill/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;;
                                $newqayd->cost_center = '';
                                $newqayd->amount_currency = $request->invPaied;
                                $newqayd->currency_id = $request->currency_id;
                                $newqayd->credit =  ($request->invPaied * $all_currency_types[0]->currencies[0]->value) + $buyCoastAc;
                                $newqayd->debit = '0';
                                $newqayd->desc = 'BANK PAYMENT + CUSTOM';
                                $newqayd->user_id = Auth::user()->id;
                                $newqayd->flag = '0';
                                $newqayd->invoice_id = $BuyTransaction->id;
                                $newqayd->invoice_table = 'printBuyInvoice';
                                $newqayd->qayds_id = $nqayd->id;
                                $newqayd->show_no = 3;
                                $newqayd->save();

                                $newqayd = new Newqayd();
                                $newqayd->refrence = 'BANK/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                                $newqayd->coa_id = 59; // payable 000
                                $newqayd->journal_id = 10;   //vendor bill 00000
                                $newqayd->partner_id = $request->invSupplier;;
                                $newqayd->type = 'Supplier';
                                $newqayd->label = 'Manual Payment for Bill/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;;
                                $newqayd->cost_center = '';
                                $newqayd->amount_currency = $request->invPaied;
                                $newqayd->currency_id = $request->currency_id;
                                $newqayd->credit =  $buyCoastAc;
                                $newqayd->debit = '0';
                                $newqayd->desc = ' CUSTOM';
                                $newqayd->user_id = Auth::user()->id;
                                $newqayd->flag = '0';
                                $newqayd->invoice_id = $BuyTransaction->id;
                                $newqayd->invoice_table = 'printBuyInvoice';
                                $newqayd->qayds_id = $nqayd->id;
                                $newqayd->show_no = 7;
                                $newqayd->save();

                                $newqayd = new Newqayd();
                                $newqayd->refrence = 'BANK/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                                $newqayd->coa_id = 28; // custom 000
                                $newqayd->journal_id = 10;   //vendor bill 00000
                                $newqayd->partner_id = $request->invSupplier;;
                                $newqayd->type = 'Supplier';
                                $newqayd->label = 'Manual Payment for Bill/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;;
                                $newqayd->cost_center = '';
                                $newqayd->amount_currency = $request->invPaied;
                                $newqayd->currency_id = $request->currency_id;
                                $newqayd->credit =  0;
                                $newqayd->debit = $buyCoastAc;
                                $newqayd->desc = ' CUSTOM';
                                $newqayd->user_id = Auth::user()->id;
                                $newqayd->flag = '0';
                                $newqayd->invoice_id = $BuyTransaction->id;
                                $newqayd->invoice_table = 'printBuyInvoice';
                                $newqayd->qayds_id = $nqayd->id;
                                $newqayd->show_no = 7;
                                $newqayd->save();
                            }
                        } else {
                            $Ac_allTotal_total = 0;
                            if ($Ac_allTotal) {
                                $Ac_allTotal_total = $Ac_allTotal->total;
                            }
                            $xx = Store::where('safe_accountant_number', intval($request->store_id))->first();
                            if ($request->invPaied * $all_currency_types[0]->currencies[0]->value + $buyCoastAc > 0) {
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
                                $newqayd = new Newqayd();
                                $newqayd->refrence = 'CASH/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                                $newqayd->coa_id = 41;
                                $newqayd->journal_id = 7;   //CASHJOURNAL
                                $newqayd->partner_id = $request->invSupplier;;
                                $newqayd->type = 'Supplier';
                                $newqayd->label = 'Manual Payment for Bill/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;;
                                $newqayd->cost_center = '';
                                $newqayd->amount_currency = $request->invPaied;
                                $newqayd->currency_id = $request->currency_id;
                                $newqayd->credit = ($request->invPaied * $all_currency_types[0]->currencies[0]->value) + $buyCoastAc;
                                $newqayd->debit = '0';
                                $newqayd->desc = 'CASH PAYMENT + CUSTOM ';
                                $newqayd->user_id = Auth::user()->id;
                                $newqayd->flag = '0';
                                $newqayd->invoice_id = $BuyTransaction->id;
                                $newqayd->invoice_table = 'printBuyInvoice';
                                $newqayd->qayds_id = $nqayd->id;
                                $newqayd->show_no = 3;
                                $newqayd->save();


                                $newqayd = new Newqayd();
                                $newqayd->refrence = 'BANK/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                                $newqayd->coa_id = 59; // payable 000
                                $newqayd->journal_id = 10;   //vendor bill 00000
                                $newqayd->partner_id = $request->invSupplier;;
                                $newqayd->type = 'Supplier';
                                $newqayd->label = 'Manual Payment for Bill/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;;
                                $newqayd->cost_center = '';
                                $newqayd->amount_currency = $request->invPaied;
                                $newqayd->currency_id = $request->currency_id;
                                $newqayd->credit =  $buyCoastAc;
                                $newqayd->debit = '0';
                                $newqayd->desc = ' CUSTOM';
                                $newqayd->user_id = Auth::user()->id;
                                $newqayd->flag = '0';
                                $newqayd->invoice_id = $BuyTransaction->id;
                                $newqayd->invoice_table = 'printBuyInvoice';
                                $newqayd->qayds_id = $nqayd->id;
                                $newqayd->show_no = 7;
                                $newqayd->save();

                                $newqayd = new Newqayd();
                                $newqayd->refrence = 'BANK/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                                $newqayd->coa_id = 28; // custom 000
                                $newqayd->journal_id = 10;   //vendor bill 00000
                                $newqayd->partner_id = $request->invSupplier;;
                                $newqayd->type = 'Supplier';
                                $newqayd->label = 'Manual Payment for Bill/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;;
                                $newqayd->cost_center = '';
                                $newqayd->amount_currency = $request->invPaied;
                                $newqayd->currency_id = $request->currency_id;
                                $newqayd->credit =  0;
                                $newqayd->debit = $buyCoastAc;
                                $newqayd->desc = ' CUSTOM';
                                $newqayd->user_id = Auth::user()->id;
                                $newqayd->flag = '0';
                                $newqayd->invoice_id = $BuyTransaction->id;
                                $newqayd->invoice_table = 'printBuyInvoice';
                                $newqayd->qayds_id = $nqayd->id;
                                $newqayd->show_no = 7;
                                $newqayd->save();
                            }
                        }

                        if ($request->invPaied + $buyCoastAc > 0) {
                            $mad = new SanadSarf();
                            $mad->client_sup_id = $supplier->id;
                            $mad->paied = $request->invPaied * $all_currency_types[0]->currencies[0]->value;
                            $mad->date = Carbon::now();
                            $mad->pyment_method = $request->store_id;
                            $mad->type = 2;


                            $mad->save();
                        }
                        if ($buyCoastAc > 0) {
                            // $newqayd = new Newqayd();
                            // $newqayd->refrence = 'Bill/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                            // $newqayd->coa_id = 28;
                            // $newqayd->journal_id = 10;   //Vendor Bill
                            // $newqayd->partner_id = $request->invSupplier;;
                            // $newqayd->type = 'Supplier';
                            // $newqayd->label = 'LC_ Customs';
                            // $newqayd->cost_center = '';
                            // $newqayd->amount_currency = 0;
                            // $newqayd->currency_id = null;
                            // $newqayd->credit =  0;
                            // $newqayd->debit = $buyCoastAc;
                            // $newqayd->desc = 'Custom PAYMENT ';
                            // $newqayd->user_id = Auth::user()->id;
                            // $newqayd->flag = '0';
                            // $newqayd->invoice_id = $BuyTransaction->id;
                            // $newqayd->invoice_table = 'printBuyInvoice';
                            // $newqayd->show_no=4;
                            // $newqayd->qayds_id=$nqayd->id;
                            // $newqayd->save();

                            // $newqayd = new Newqayd();
                            // $newqayd->refrence = 'Bill/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                            // $newqayd->coa_id = 59;
                            // $newqayd->journal_id = 10;   //Vendor Bill
                            // $newqayd->partner_id = $request->invSupplier;;
                            // $newqayd->type = 'Supplier';
                            // $newqayd->label = 'Bill/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                            // $newqayd->cost_center = '';
                            // $newqayd->amount_currency = 0;
                            // $newqayd->currency_id = null;
                            // $newqayd->debit =  0;
                            // $newqayd->credit = $buyCoastAc;
                            // $newqayd->desc = 'Custom PAYMENT ';
                            // $newqayd->user_id = Auth::user()->id;
                            // $newqayd->flag = '0';
                            // $newqayd->invoice_id = $BuyTransaction->id;
                            // $newqayd->invoice_table = 'printBuyInvoice';
                            // $newqayd->qayds_id=$nqayd->id;
                            // $newqayd->show_no=4;
                            // $newqayd->save();
                        }

                        if ($request->NewinvSupplier <>  null) {
                            // add new Supplier
                            $sup = new Supplier();
                            $sup->name = $request->NewinvSupplier;
                            $sup->save();
                            $sup_id = $sup->id;
                        } else {

                            $sup_id = $request->invSupplier;
                        }
                        $orderSupplier = new OrderSupplier();
                        $orderSupplier->transaction_id = $BuyTransaction->id;
                        $orderSupplier->user_id = Auth::user()->id;
                        $orderSupplier->supplier_id = $sup_id;
                        $orderSupplier->pricebeforeTax = $request->invTotLbl;
                        // $orderSupplier->send_mail = '';
                        // $orderSupplier->notes = '';
                        $orderSupplier->status = '4';
                        $orderSupplier->deliver_date = Carbon::now();
                        // $orderSupplier->currency_id = 400;
                        $orderSupplier->currency_id = $request->currency_id;
                        $orderSupplier->total_price = $request->invAllTotal;
                        $orderSupplier->paied = $request->invPaied;
                        $orderSupplier->payment = $request->payment;
                        $orderSupplier->bank_account = $request->store_id;

                        $orderSupplier->transport_coast = $request->transCoast;
                        $orderSupplier->insurant_coast = $request->insuranceCoast;
                        $orderSupplier->customs_coast = $request->customs;
                        $orderSupplier->commotion_coast = $request->commition;
                        $orderSupplier->other_coast = $request->otherCoast;
                        $orderSupplier->coast = $request->InvCoasting;
                        $orderSupplier->taxInvolved_flag = $request->taxInvolved;
                        $orderSupplier->taxkasmInvolved_flag = $request->taxkasmInvolved;

                        $orderSupplier->taslem_coast = $request->taslem_coast;
                        $orderSupplier->ardya_coast = $request->ardya_coast;
                        $orderSupplier->in_transport_coast = $request->in_transport_coast;
                        $orderSupplier->takhles_coast = $request->takhles_coast;
                        $orderSupplier->bank_coast = $request->bank_coast;
                        $orderSupplier->nolon_coast = $request->nolon_coast;

                        if (floatval($request->taxInvolved) == 1) {
                            $orderSupplier->tax = 0;
                        } else {
                            $orderSupplier->tax = $request->invTax;
                        }


                        if ($request->invPaied < $request->invAllTotal) {
                            $orderSupplier->due_date = $request->dueDate;
                            // Supplier::where('id', $sup_id)->increment('raseed', (floatval($request->invAllTotal) - floatval($request->invPaied)) * $all_currency_types[0]->currencies[0]->value);
                        } else if ($request->invPaied > $request->invAllTotal) {
                            // Supplier::where('id', $sup_id)->decrement('raseed', (floatval($request->invPaied) - floatval($request->invAllTotal)) * $all_currency_types[0]->currencies[0]->value);
                        } else {
                        }
                        // $orderSupplier->bank_account = '';
                        // $orderSupplier->container_size = '';
                        $orderSupplier->confirmation_date = Carbon::now();
                        // $orderSupplier->image_url = '';
                        $orderSupplier->save();

                        if (floatval($request->notaxes) == 1) {
                            if ($request->invTax) {
                                $taxac111 = $request->invTotLbl * $request->invTax / 100; // الضريبة

                                $newqayd = new Newqayd();
                                $newqayd->refrence = 'Bill/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                                $newqayd->coa_id = 84;
                                $newqayd->journal_id = 10;   //Vendor Bill
                                $newqayd->partner_id = $request->invSupplier;;
                                $newqayd->type = 'Supplier';
                                $newqayd->label = $request->invTax . '% VAT';
                                $newqayd->cost_center = '';
                                $newqayd->amount_currency = $taxac111;
                                $newqayd->currency_id = $request->currency_id;
                                $newqayd->credit =  $taxac111 * $Ac_all_currency_types[0]->currencies[0]->value;
                                $newqayd->debit = '0';
                                $newqayd->desc = 'When Confirm the bill ' . $BuyTransaction->id;
                                $newqayd->user_id = Auth::user()->id;
                                $newqayd->flag = '0';
                                $newqayd->invoice_id = $BuyTransaction->id;
                                $newqayd->invoice_table = 'printBuyInvoice';
                                $newqayd->qayds_id = $nqayd->id;
                                $newqayd->show_no = 2;
                                $newqayd->save();
                            } else {
                                $taxac111 = 0;
                            }
                        } else {
                            if (floatval($request->taxInvolved) == 1) {
                                // غير شامل
                                if ($request->invTax) {
                                    $taxac111 = $request->invTotLbl * $request->invTax / 100; // الضريبة
                                    $newqayd = new Newqayd();
                                    $newqayd->refrence = 'Bill/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                                    $newqayd->coa_id = 84;
                                    $newqayd->journal_id = 10;   //Vendor Bill
                                    $newqayd->partner_id = $request->invSupplier;;
                                    $newqayd->type = 'Supplier';
                                    $newqayd->label = $request->invTax . '% VAT';
                                    $newqayd->cost_center = '';
                                    $newqayd->amount_currency = $taxac111;
                                    $newqayd->currency_id = $request->currency_id;
                                    $newqayd->credit =  $taxac111 * $Ac_all_currency_types[0]->currencies[0]->value;
                                    $newqayd->debit = '0';
                                    $newqayd->desc = 'When Confirm the bill ' . $BuyTransaction->id;
                                    $newqayd->user_id = Auth::user()->id;
                                    $newqayd->flag = '0';
                                    $newqayd->invoice_id = $BuyTransaction->id;
                                    $newqayd->invoice_table = 'printBuyInvoice';
                                    $newqayd->qayds_id = $nqayd->id;
                                    $newqayd->show_no = 2;
                                    $newqayd->save();
                                } else {
                                    $taxac111 = $request->invTotLbl * 14 / 100; // الضريبة
                                    $newqayd = new Newqayd();
                                    $newqayd->refrence = 'Bill/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                                    $newqayd->coa_id = 14;
                                    $newqayd->journal_id = 10;   //Vendor Bill
                                    $newqayd->partner_id = $request->invSupplier;;
                                    $newqayd->type = 'Supplier';
                                    $newqayd->label = '14% VAT';
                                    $newqayd->cost_center = '';
                                    $newqayd->amount_currency = $taxac111;
                                    $newqayd->currency_id = $request->currency_id;
                                    $newqayd->debit =  $taxac111 * $Ac_all_currency_types[0]->currencies[0]->value;
                                    $newqayd->credit = '0';
                                    $newqayd->desc = 'When Confirm the bill ' . $BuyTransaction->id;
                                    $newqayd->user_id = Auth::user()->id;
                                    $newqayd->flag = '0';
                                    $newqayd->invoice_id = $BuyTransaction->id;
                                    $newqayd->invoice_table = 'printBuyInvoice';
                                    $newqayd->qayds_id = $nqayd->id;
                                    $newqayd->show_no = 2;
                                    $newqayd->save();
                                }
                            } else {
                                // شامل
                                $binvoiceac11 = floatval($request->invAllTotal / 1.14); //  المشتريات غير شامل الضريبة
                                $taxac111 = $request->invAllTotal - $binvoiceac11; // الضريبة


                                $newqayd = new Newqayd();
                                $newqayd->refrence = 'Bill/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                                $newqayd->coa_id = 14;
                                $newqayd->journal_id = 10;   //Vendor Bill
                                $newqayd->partner_id = $request->invSupplier;;
                                $newqayd->type = 'Supplier';
                                $newqayd->label = '14% VAT';
                                $newqayd->cost_center = '';
                                $newqayd->amount_currency = $taxac111;
                                $newqayd->currency_id = $request->currency_id;
                                $newqayd->debit =  $taxac111 * $Ac_all_currency_types[0]->currencies[0]->value;
                                $newqayd->credit = '0';
                                $newqayd->desc = 'When Confirm the bill ' . $BuyTransaction->id;
                                $newqayd->user_id = Auth::user()->id;
                                $newqayd->flag = '0';
                                $newqayd->invoice_id = $BuyTransaction->id;
                                $newqayd->invoice_table = 'printBuyInvoice';
                                $newqayd->qayds_id = $nqayd->id;
                                $newqayd->show_no = 2;
                                $newqayd->save();
                            }


                            if (floatval($request->taxkasmInvolved) == 1) {
                                $taxAc2222 = $request->invTotLbl * -1 / 100;
                                $taxAc2222 = $taxAc2222 * -1;
                                $newqayd = new Newqayd();
                                $newqayd->refrence = 'Bill/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                                $newqayd->coa_id = 14;
                                $newqayd->journal_id = 10;   //Vendor Bill
                                $newqayd->partner_id = $request->invSupplier;;
                                $newqayd->type = 'Supplier';
                                $newqayd->label = '-1% VAT';
                                $newqayd->cost_center = '';
                                $newqayd->amount_currency = $taxAc2222;
                                $newqayd->currency_id = $request->currency_id;
                                $newqayd->credit =  $taxAc2222 * $Ac_all_currency_types[0]->currencies[0]->value;
                                $newqayd->debit = '0';
                                $newqayd->desc = 'When Confirm the bill ' . $BuyTransaction->id;
                                $newqayd->user_id = Auth::user()->id;
                                $newqayd->flag = '0';
                                $newqayd->invoice_id = $BuyTransaction->id;
                                $newqayd->invoice_table = 'printBuyInvoice';
                                $newqayd->qayds_id = $nqayd->id;
                                $newqayd->show_no = 2;
                                $newqayd->save();
                            } else {
                            }
                        }


                        $newqayd = new Newqayd();
                        $newqayd->refrence = 'Bill/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                        $newqayd->coa_id = 59;
                        $newqayd->journal_id = 10;   //Vendor Bill
                        $newqayd->partner_id = $request->invSupplier;;
                        $newqayd->type = 'Supplier';
                        $newqayd->label = 'Bill/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                        $newqayd->cost_center = '';
                        $newqayd->amount_currency = $request->invAllTotal;
                        $newqayd->currency_id = $request->currency_id;
                        $newqayd->credit =  $request->invAllTotal * $Ac_all_currency_types[0]->currencies[0]->value;
                        $newqayd->debit = '0';
                        $newqayd->desc = 'When Confirm the bill ' . $BuyTransaction->id;
                        $newqayd->user_id = Auth::user()->id;
                        $newqayd->flag = '0';
                        $newqayd->invoice_id = $BuyTransaction->id;
                        $newqayd->invoice_table = 'printBuyInvoice';
                        $newqayd->qayds_id = $nqayd->id;
                        $newqayd->show_no = 2;
                        $newqayd->save();

                        $date = Carbon::now();
                        $type = null;
                        $notes = 'فاتورة شراء وتوزيع بالأمر المباشر رقم' . $BuyTransaction->id . 'العملة ' . $Ac_all_currency_types[0]->name;
                        $qaydids = $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
                        Qayditem::where('qaydid', $qaydids)->update([
                            'invoiceid' => $BuyTransaction->id
                        ]);
                        $quaditems = [];
                        $Actotal = 0;
                        for ($i = 0; $i < count($request->partId); $i++) {
                            // DB::rollBack();
                            $ratiounit = getSmallUnit($request->unit[$i], $request->smallUnit[$i]);

                            # code...
                            $part = new Replyorder();
                            $part->order_supplier_id = $orderSupplier->id;
                            $part->part_id = $request->partId[$i];
                            $part->price = floatval($request->price[$i]) / floatval($ratiounit);
                            $part->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                            $part->source_id = $request->partSource[$i];
                            $part->status_id = $request->partStatus[$i];
                            $part->quality_id = $request->partQualty[$i];
                            $part->creation_date = Carbon::now();
                            $part->part_type_id = $request->types[$i];
                            $part->unit_id = $request->unit[$i];
                            $part->save();


                            $newqayd = new Newqayd();
                            $newqayd->refrence = 'Stock/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                            $newqayd->coa_id = 223;
                            $newqayd->journal_id = 8;   //Inventory Valuation
                            $newqayd->partner_id = $request->invSupplier;;
                            $newqayd->type = 'Supplier';
                            $newqayd->label = 'Stock/IN/' . date('Y') . '/' . date('m') . '/' . $nqayd->id . '-' . 'FN' . $request->types[$i] . $request->partId[$i] . $request->partSource[$i] . $request->partStatus[$i] . $request->partQualty[$i] . '/' . $request->amount[$i];
                            $newqayd->cost_center = '';
                            $newqayd->amount_currency = $request->price[$i];
                            $newqayd->currency_id = $request->currency_id;
                            $newqayd->debit = $request->amount[$i] * ($request->price[$i] * $Ac_all_currency_types[0]->currencies[0]->value);
                            $newqayd->credit = '0';
                            $newqayd->desc = 'Just Received The stock but not Billed' . $BuyTransaction->id;
                            $newqayd->user_id = Auth::user()->id;
                            $newqayd->flag = '0';
                            $newqayd->invoice_id = $BuyTransaction->id;
                            $newqayd->invoice_table = 'printBuyInvoice';
                            $newqayd->qayds_id = $nqayd->id;
                            $newqayd->show_no = 1;
                            $newqayd->save();

                            $newqayd = new Newqayd();
                            $newqayd->refrence = 'Stock/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                            $newqayd->coa_id = 224;
                            $newqayd->journal_id = 8;   //Inventory Valuation
                            $newqayd->partner_id = $request->invSupplier;;
                            $newqayd->type = 'Supplier';
                            $newqayd->label = 'Stock/IN/' . date('Y') . '/' . date('m') . '/' . $nqayd->id . '-' . 'FN' . $request->types[$i] . $request->partId[$i] . $request->partSource[$i] . $request->partStatus[$i] . $request->partQualty[$i] . '/' . $request->amount[$i];
                            $newqayd->cost_center = '';
                            $newqayd->amount_currency = $request->price[$i];
                            $newqayd->currency_id = $request->currency_id;
                            $newqayd->credit = $request->amount[$i] * ($request->price[$i] * $Ac_all_currency_types[0]->currencies[0]->value);
                            $newqayd->debit = '0';
                            $newqayd->desc = 'Just Received The stock but not Billed' . $BuyTransaction->id;
                            $newqayd->user_id = Auth::user()->id;
                            $newqayd->flag = '0';
                            $newqayd->invoice_id = $BuyTransaction->id;
                            $newqayd->invoice_table = 'printBuyInvoice';
                            $newqayd->qayds_id = $nqayd->id;
                            $newqayd->show_no = 1;
                            $newqayd->save();


                            $newqayd = new Newqayd();
                            $newqayd->refrence = 'Bill/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                            $newqayd->coa_id = 224;
                            $newqayd->journal_id = 10;   //Vendor Bill
                            $newqayd->partner_id = $request->invSupplier;;
                            $newqayd->type = 'Supplier';
                            $newqayd->label = 'Stock/IN/' . date('Y') . '/' . date('m') . '/' . $nqayd->id . '-' . 'FN' . $request->types[$i] . $request->partId[$i] . $request->partSource[$i] . $request->partStatus[$i] . $request->partQualty[$i] . '/' . $request->amount[$i];
                            $newqayd->cost_center = '';
                            $newqayd->amount_currency = $request->price[$i];
                            $newqayd->currency_id = $request->currency_id;
                            $newqayd->debit = $request->amount[$i] * ($request->price[$i] * $Ac_all_currency_types[0]->currencies[0]->value);
                            $newqayd->credit = '0';
                            $newqayd->desc = 'When Confirm the bill ' . $BuyTransaction->id;
                            $newqayd->user_id = Auth::user()->id;
                            $newqayd->flag = '0';
                            $newqayd->invoice_id = $BuyTransaction->id;
                            $newqayd->invoice_table = 'printBuyInvoice';
                            $newqayd->qayds_id = $nqayd->id;
                            $newqayd->show_no = 2;
                            $newqayd->save();
                            // /*******************************************************************

                            $store = Store::where('id', $request->Stores[$i])->first();
                            $Ac_inv = Replyorder::where('order_supplier_id', $orderSupplier->id)
                                ->where('part_id', $request->partId[$i])
                                ->where('source_id', $request->partSource[$i])
                                ->where('status_id', $request->partStatus[$i])
                                ->where('quality_id', $request->partQualty[$i])
                                ->where('part_type_id', $request->types[$i])
                                ->with('order_supplier')->first();



                            $Ac_currency_id = $Ac_inv->order_supplier->currency_id;
                            $Ac_currency_date = $Ac_inv->order_supplier->confirmation_date;
                            $Ac_all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                            }])->where('id', $Ac_currency_id)->get();



                            $Ac_price = floatval($request->amount[$i]) * $Ac_all_currency_types[0]->currencies[0]->value * $Ac_inv->price;


                            if ($i == count($request->partId) - 1) {
                                if (floatval($request->taxInvolved) == 1) {
                                    // غير شامل -
                                    // dd($Ac_price);
                                    array_push($quaditems, (object) ['acountant_id' => $store->accountant_number, 'madin' => ($Ac_price + $buyCoastAc), 'dayin' => 0]);
                                } else {
                                    // شامل +

                                    array_push($quaditems, (object) ['acountant_id' => $store->accountant_number, 'madin' => ($Ac_price - ($totalTaxEgp) + $buyCoastAc), 'dayin' => 0]);
                                }
                            } else {
                                array_push($quaditems, (object) ['acountant_id' => $store->accountant_number, 'madin' => $Ac_price, 'dayin' => 0]);
                            }

                            $Actotal += $Ac_price;



                            ///////////////////////////////////////////////////////////////////////
                            if ($request->types[$i] == 1) {
                                // DB::rollBack();
                                // return
                                $ratio = floatval($request->InvCoasting) / floatval($request->invTotLbl);
                                $itemCoast = ($ratio *  (floatval($request->price[$i]) * floatval($request->amount[$i]) * floatval($ratiounit))) / (floatval($request->amount[$i]) * floatval($ratiounit));

                                $allpart = new AllPart();
                                $allpart->part_id = $request->partId[$i];
                                $allpart->order_supplier_id = $orderSupplier->id;
                                $allpart->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                $allpart->source_id = $request->partSource[$i];
                                $allpart->status_id = $request->partStatus[$i];
                                $allpart->quality_id = $request->partQualty[$i];
                                $allpart->buy_price = floatval($request->price[$i]) / floatval($ratiounit);
                                $allpart->insertion_date = Carbon::now();
                                $allpart->remain_amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                $allpart->flag = 3;
                                $allpart->buy_costing = $itemCoast;
                                $allpart->save();

                                $newqayd = new Newqayd();
                                $newqayd->refrence = 'Stock/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                                $newqayd->coa_id = 223;
                                $newqayd->journal_id = 8;   // Inventory Valuation
                                $newqayd->partner_id = $request->invSupplier;;
                                $newqayd->type = 'Supplier';
                                $newqayd->label = 'LC_ Customs-' . 'FN' . $request->types[$i] . $request->partId[$i] . $request->partSource[$i] . $request->partStatus[$i] . $request->partQualty[$i] . '/' . $request->amount[$i];
                                $newqayd->cost_center = '';
                                $newqayd->amount_currency = $itemCoast;
                                $newqayd->currency_id = null;
                                $newqayd->debit = $itemCoast * $request->amount[$i];
                                $newqayd->credit = '0';
                                $newqayd->desc = 'Landed cost With Inventory';
                                $newqayd->user_id = Auth::user()->id;
                                $newqayd->flag = '0';
                                $newqayd->invoice_id = $BuyTransaction->id;
                                $newqayd->invoice_table = 'printBuyInvoice';
                                $newqayd->qayds_id = $nqayd->id;
                                $newqayd->show_no = 6;
                                $newqayd->save();

                                $newqayd = new Newqayd();
                                $newqayd->refrence = 'Stock/' . date('Y') . '/' . date('m') . '/' . $nqayd->id;
                                $newqayd->coa_id = 224;
                                $newqayd->journal_id = 8;   // Inventory Valuation
                                $newqayd->partner_id = $request->invSupplier;;
                                $newqayd->type = 'Supplier';
                                $newqayd->label = 'LC_ Customs-' . 'FN' . $request->types[$i] . $request->partId[$i] . $request->partSource[$i] . $request->partStatus[$i] . $request->partQualty[$i] . '/' . $request->amount[$i];
                                $newqayd->cost_center = '';
                                $newqayd->amount_currency = $itemCoast;
                                $newqayd->currency_id = null;
                                $newqayd->credit = $itemCoast * $request->amount[$i];
                                $newqayd->debit = '0';
                                $newqayd->desc = 'Landed cost With Inventory';
                                $newqayd->user_id = Auth::user()->id;
                                $newqayd->flag = '0';
                                $newqayd->invoice_id = $BuyTransaction->id;
                                $newqayd->invoice_table = 'printBuyInvoice';
                                $newqayd->qayds_id = $nqayd->id;
                                $newqayd->show_no = 6;
                                $newqayd->save();

                                if (isset($request->Sections) && count($request->Sections) > 0) {
                                    // ghoniemstoreLog

                                    $storelog = new StoresLog();
                                    $storelog->All_part_id = $allpart->id;
                                    $storelog->store_id = $request->Stores[$i];
                                    $storelog->store_action_id = 1;
                                    $storelog->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                    $storelog->date = Carbon::now();
                                    $storelog->status = 1;
                                    $storelog->type_id = $request->types[$i];
                                    $storelog->unit_id=$request->unit[$i];
                                    $storelog->save();

                                    $storelog = new StoresLog();
                                    $storelog->All_part_id = $allpart->id;
                                    $storelog->store_id = $request->Stores[$i];
                                    $storelog->store_action_id = 3;
                                    $storelog->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                    $storelog->date = Carbon::now();
                                    $storelog->status = 3;
                                    $storelog->type_id = $request->types[$i];
                                    $storelog->unit_id=$request->unit[$i];
                                    $storelog->save();


                                    $store = Store::where('id', $request->Stores[$i])->get();
                                    // return $store;

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

                                        $storeCls->part_id = $request->partId[$i];
                                        $storeCls->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                        $storeCls->supplier_order_id = $orderSupplier->id;
                                        $storeCls->type_id = $request->types[$i];
                                        $storeCls->store_log_id = $storelog->id;
                                        $storeCls->date = Carbon::now();
                                        $storeCls->unit_id=$request->unit[$i];
                                        $storeCls->save();


                                        $section = new StoreSection();
                                        $section->store_id = $request->Stores[$i];
                                        $section->section_id = $request->Sections[$i];
                                        $section->order_supplier_id = $orderSupplier->id;
                                        $section->type_id = $request->types[$i];
                                        $section->part_id = $request->partId[$i];
                                        $section->source_id = $request->partSource[$i];
                                        $section->status_id = $request->partStatus[$i];
                                        $section->quality_id = $request->partQualty[$i];
                                        $section->amount =  floatval($request->amount[$i]) * floatval($ratiounit);
                                        $section->date = Carbon::now();
                                        $section->unit_id = $request->unit[$i];
                                        $section->save();
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                        continue;
                                    }
                                }
                            }

                            ///// kit
                            if ($request->types[$i] == 6) {
                                $ratio = floatval($request->InvCoasting) / floatval($request->invTotLbl);
                                $itemCoast = ($ratio *  (floatval($request->price[$i]) * floatval($request->amount[$i]) * floatval($ratiounit))) / floatval($request->amount[$i]);

                                $allpart = new AllKit();
                                $allpart->part_id = $request->partId[$i];;
                                $allpart->order_supplier_id = $orderSupplier->id;
                                $allpart->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                $allpart->source_id = $request->partSource[$i];
                                $allpart->status_id = $request->partStatus[$i];
                                $allpart->quality_id = $request->partQualty[$i];
                                $allpart->buy_price = floatval($request->price[$i]) / floatval($ratiounit);
                                $allpart->insertion_date = Carbon::now();
                                $allpart->remain_amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                $allpart->flag = 3;
                                $allpart->buy_costing = $itemCoast;
                                $allpart->save();

                                if (count($request->Sections) > 0) {
                                    // ghoniemstoreLog
                                    $storelog = new StoresLog();
                                    $storelog->All_part_id = $allpart->id;
                                    $storelog->store_id = $request->Stores[$i];
                                    $storelog->store_action_id = 1;
                                    $storelog->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                    $storelog->date = Carbon::now();
                                    $storelog->status = 1;
                                    $storelog->type_id = $request->types[$i];
                                    $storelog->save();

                                    $storelog = new StoresLog();
                                    $storelog->All_part_id = $allpart->id;
                                    $storelog->store_id = $request->Stores[$i];
                                    $storelog->store_action_id = 3;
                                    $storelog->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                    $storelog->date = Carbon::now();
                                    $storelog->status = 3;
                                    $storelog->type_id = $request->types[$i];
                                    $storelog->save();

                                    $store = Store::where('id', $request->Stores[$i])->get();
                                    // return $store;
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

                                        $storeCls->part_id = $request->partId[$i];
                                        $storeCls->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                        $storeCls->supplier_order_id = $orderSupplier->id;
                                        $storeCls->type_id = $request->types[$i];
                                        $storeCls->store_log_id = $storelog->id;
                                        $storeCls->date = Carbon::now();
                                        $storeCls->save();


                                        $section = new StoreSection();
                                        $section->store_id = $request->Stores[$i];
                                        $section->section_id = $request->Sections[$i];
                                        $section->order_supplier_id = $orderSupplier->id;
                                        $section->type_id = $request->types[$i];
                                        $section->part_id = $request->partId[$i];
                                        $section->source_id = $request->partSource[$i];
                                        $section->status_id = $request->partStatus[$i];
                                        $section->quality_id = $request->partQualty[$i];
                                        $section->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                        $section->date = Carbon::now();
                                        $section->save();
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                        continue;
                                    }
                                }
                            }

                            /// wheel
                            if ($request->types[$i] == 2) {
                                $ratio = floatval($request->InvCoasting) / floatval($request->invTotLbl);
                                $itemCoast = ($ratio *  floatval($request->price[$i]) * floatval($request->amount[$i]) * floatval($ratiounit)) / floatval($request->amount[$i]);


                                $allpart = new AllWheel();
                                $allpart->part_id = $request->partId[$i];;
                                $allpart->order_supplier_id = $orderSupplier->id;
                                $allpart->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                $allpart->source_id = $request->partSource[$i];
                                $allpart->status_id = $request->partStatus[$i];
                                $allpart->quality_id = $request->partQualty[$i];
                                $allpart->buy_price = floatval($request->price[$i]) / floatval($ratiounit);
                                $allpart->insertion_date = Carbon::now();
                                $allpart->remain_amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                $allpart->flag = 3;
                                $allpart->buy_costing = $itemCoast;
                                $allpart->save();

                                if (count($request->Sections) > 0) {
                                    // ghoniemstoreLog
                                    $storelog = new StoresLog();
                                    $storelog->All_part_id = $allpart->id;
                                    $storelog->store_id = $request->Stores[$i];
                                    $storelog->store_action_id = 1;
                                    $storelog->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                    $storelog->date = Carbon::now();
                                    $storelog->status = 1;
                                    $storelog->type_id = $request->types[$i];
                                    $storelog->save();

                                    $storelog = new StoresLog();
                                    $storelog->All_part_id = $allpart->id;
                                    $storelog->store_id = $request->Stores[$i];
                                    $storelog->store_action_id = 3;
                                    $storelog->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                    $storelog->date = Carbon::now();
                                    $storelog->status = 3;
                                    $storelog->type_id = $request->types[$i];
                                    $storelog->save();


                                    $store = Store::where('id', $request->Stores[$i])->get();
                                    // return $store;
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

                                        $storeCls->part_id = $request->partId[$i];
                                        $storeCls->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                        $storeCls->supplier_order_id = $orderSupplier->id;
                                        $storeCls->type_id = $request->types[$i];
                                        $storeCls->store_log_id = $storelog->id;
                                        $storeCls->date = Carbon::now();
                                        $storeCls->save();


                                        $section = new StoreSection();
                                        $section->store_id = $request->Stores[$i];
                                        $section->section_id = $request->Sections[$i];
                                        $section->order_supplier_id = $orderSupplier->id;
                                        $section->type_id = $request->types[$i];
                                        $section->part_id = $request->partId[$i];
                                        $section->source_id = $request->partSource[$i];
                                        $section->status_id = $request->partStatus[$i];
                                        $section->quality_id = $request->partQualty[$i];
                                        $section->amount = floatval($request->amount[$i]) * floatval($ratiounit);
                                        $section->date = Carbon::now();
                                        $section->save();
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                        continue;
                                    }
                                }
                            }

                            // $this->Repricing($request->partId[$i],$request->partSource[$i],$request->partStatus[$i],$request->partQualty[$i],$request->types[$i]);


                        }


                        // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> 0 , 'dayin'=>$Actotal ] ); // المخزون دائن
                        array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => 0, 'dayin' => ($Actotal - ($totalTaxEgp) + $buyCoastAc)]); // المشتريات دائن
                        $date = Carbon::now();
                        $type = null;
                        $notes = 'حركة مخزون';
                        $qyadidss = $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
                        Qayditem::where('qaydid', $qyadidss)->update([
                            'invoiceid' => $BuyTransaction->id
                        ]);

                        $quaditems = [];
                        $automaicQayd = new QaydController();

                        if ($request->transCoast > 0) {
                            array_push($quaditems, (object) ['acountant_id' => 33101, 'dayin' => 0, 'madin' => $request->transCoast]);
                        }
                        if ($request->insuranceCoast > 0) {
                            array_push($quaditems, (object) ['acountant_id' => 33102, 'dayin' => 0, 'madin' => $request->insuranceCoast]);
                        }
                        if ($request->customs > 0) {
                            array_push($quaditems, (object) ['acountant_id' => 33103, 'dayin' => 0, 'madin' => $request->customs]);
                        }
                        if ($request->commition > 0) {
                            array_push($quaditems, (object) ['acountant_id' => 33104, 'dayin' => 0, 'madin' => $request->commition]);
                        }
                        if ($request->otherCoast > 0) {
                            // array_push($quaditems, (object) ['acountant_id' => 33105, 'dayin' => 0, 'madin' => $request->otherCoast]);
                            array_push($quaditems, (object) ['acountant_id' => 33105, 'dayin' => 0, 'madin' => $request->otherCoast + $request->taslem_coast + $request->ardya_coast + $request->in_transport_coast + $request->takhles_coast + $request->bank_coast + $request->nolon_coast]);
                        }

                        $date = Carbon::now();
                        $type = null;
                        $notes = 'مصروفات علي فاتورة شراء رقم ' . $BuyTransaction->id . 'للمعلومية فقط تم تحميل المصروفات علي المشتريات والمخزون';
                        $qaydids = $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
                        Qayditem::where('qaydid', $qaydids)->update([
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
            session()->flash("success", "error" . $e);
            return redirect()->back();
            // return redirect()->back();
        }
    }

    public function editInv(Request $request)
    {
        // return $request;

        DB::beginTransaction();
        try {
            $currency_id = $request->currency_id;

            ///////////////////////////////////////////////////////////////

            $orderSupllier = OrderSupplier::where('transaction_id', $request->inv_id)->with('supplier')->first();
            $Store_paid = $orderSupllier->paied - $request->invPaied;

            $stores_sections =  StoreSection::where('order_supplier_id', $orderSupllier->id)->get();
            // return $stores_sections;
            $all_parts = AllPart::where('order_supplier_id', $orderSupllier->id)->get();
            $all_kits = AllKit::where('order_supplier_id', $orderSupllier->id)->get();
            $all_wheels = AllWheel::where('order_supplier_id', $orderSupllier->id)->get();
            // return $all_wheels;
            if (count($stores_sections) > 0) {
                for ($i = 0; $i < count($stores_sections); $i++) {
                    $store = Store::where('id', $stores_sections[$i]->store_id)->get();
                    $storeClsName = ucfirst($store[0]->table_name);
                    $storeClsName = 'App\Models\\' . $storeClsName;
                    $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
                    if ($store[0]->table_name == "damaged_parts") {
                        $storeClsName = "App\Models\\DamagedPart";
                    }
                    $storeClsName::where('supplier_order_id', $orderSupllier->id)->delete();
                }
            }
            if (count($all_parts) > 0) {
                for ($i = 0; $i < count($all_parts); $i++) {
                    StoresLog::where('All_part_id', $all_parts[$i]->id)->delete();
                    if ($all_parts[$i]->amount != $all_parts[$i]->remain_amount) {
                        DB::rollback();
                        session()->flash("success", "لا يمكن التعديل حيث أنه تم التوزيع علي كيت او عملية بيع");
                        return redirect()->back();
                    }
                }
            }
            if (count($all_kits) > 0) {
                for ($i = 0; $i < count($all_kits); $i++) {
                    StoresLog::where('All_part_id', $all_kits[$i]->id)->delete();
                    if ($all_parts[$i]->amount != $all_parts[$i]->remain_amount) {
                        DB::rollback();
                        session()->flash("success", "لا يمكن التعديل حيث أنه تم التوزيع علي كيت او عملية بيع");
                        return redirect()->back();
                    }
                }
            }
            if (count($all_wheels) > 0) {
                for ($i = 0; $i < count($all_wheels); $i++) {
                    StoresLog::where('All_part_id', $all_wheels[$i]->id)->delete();
                    if ($all_parts[$i]->amount != $all_parts[$i]->remain_amount) {
                        DB::rollback();
                        session()->flash("success", "لا يمكن التعديل حيث أنه تم التوزيع علي كيت او عملية بيع");
                        return redirect()->back();
                    }
                }
            }
            $all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($currency_id) {
                return $query->where('to', null)->where('currency_id', $currency_id);
            }])->where('id', $currency_id)->get();
            //////////////////حساب المورد/////////////
            $Ac_currency_id_old = $orderSupllier->currency_id;
            $Ac_currency_date_old = $orderSupllier->confirmation_date;
            $Ac_all_currency_types_old = CurrencyType::with(['currencies' => function ($query) use ($Ac_currency_id_old, $Ac_currency_date_old) {
                return $query->where('from', '>=', $Ac_currency_date_old)->where('to', '<=', $Ac_currency_date_old)->where('currency_id', $Ac_currency_id_old)->orWhere('to', '=', null);
            }])->where('id', $Ac_currency_id_old)->get();


            // Supplier::where('id', $request->invSupplier)->decrement('raseed', (floatval($request->invAllTotal) - floatval($request->invPaied)) * $all_currency_types[0]->currencies[0]->value);
            Supplier::where('id', $request->invSupplier)->decrement('raseed', (floatval($orderSupllier->total_price) - floatval($orderSupllier->paied))  * $Ac_all_currency_types_old[0]->currencies[0]->value);
            $old_qayd_item = Qayditem::where('invoiceid', $request->inv_id)->get();
            $uniqueQaydids = Qayditem::where('invoiceid', $request->inv_id)
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


            // *********************new*************************************************************************************
            // Add to safe old_paied****************************************************1

            $editSup = Supplier::where('id', $request->invSupplier)->first();

            $oldOrderSup = OrderSupplier::where('id', $orderSupllier->id)->first();
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
                if ($xpaied * $xAc_all_currency_types[0]->currencies[0]->value > 0) {
                    $BankSafeMoneyTot  = BankSafeMoney::where('bank_type_id', $bank->id)->orderBy('id', 'DESC')->first();
                    // dd('sss');
                    BankSafeMoney::create([
                        'notes' => 'تعديل فاتورة شراء ' . ' ' . $request->buyTransaction_id . ' ' . 'من مورد' . ' ' . $editSup->name,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => ($xpaied * $xAc_all_currency_types[0]->currencies[0]->value) + $oldcoasts,
                        'total' => $BankSafeMoneyTot->total + ($xpaied * $xAc_all_currency_types[0]->currencies[0]->value) - $oldcoasts,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => null,
                        'bank_type_id' => $bank->id,
                        'money_currency' => $xpaied + $oldcoasts,
                        'currency_id' => $xcurrency_id,
                    ]);
                }
            } else {
                if ($xpaied * $xAc_all_currency_types[0]->currencies[0]->value > 0) {
                    $xxd = Store::where('safe_accountant_number', intval($xbank_account))->first();
                    $MoneySafeTot = MoneySafe::where('store_id', $xxd->id)->orderBy('id', 'DESC')->first();
                    MoneySafe::create([
                        'notes' => 'تعديل فاتورة شراء ' . ' ' . $request->buyTransaction_id . ' ' . 'من مورد' . ' ' . $editSup->name,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => ($xpaied * $xAc_all_currency_types[0]->currencies[0]->value) + $oldcoasts,
                        'total' => $MoneySafeTot->total + ($xpaied * $xAc_all_currency_types[0]->currencies[0]->value) - $oldcoasts,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => $xxd->id,
                    ]);
                }
            }



            // ***************************************************************************************************************************






            $StoreSection_deleted = StoreSection::where('order_supplier_id', $orderSupllier->id)->get();
            foreach ($StoreSection_deleted as $x) {
                $x->delete();  // Trigger deleting and deleted events
            }
            $AllPart_deleted = AllPart::where('order_supplier_id', $orderSupllier->id)->get();
            foreach ($AllPart_deleted as $y) {
                $y->delete();  // Trigger deleting and deleted events
            }
            $AllKit_deleted = AllKit::where('order_supplier_id', $orderSupllier->id)->get();
            foreach ($AllKit_deleted as $c) {
                $c->delete();  // Trigger deleting and deleted events
            }
            $AllWheel_deleted = AllWheel::where('order_supplier_id', $orderSupllier->id)->get();
            foreach ($AllWheel_deleted as $u) {
                $u->delete();  // Trigger deleting and deleted events
            }
            $Replyorder_deleted = Replyorder::where('order_supplier_id', $orderSupllier->id)->get();
            foreach ($Replyorder_deleted as $p) {
                $p->delete();  // Trigger deleting and deleted events
            }
            $OrderSupplier_deleted = OrderSupplier::where('transaction_id', $request->inv_id)->get();
            foreach ($OrderSupplier_deleted as $supp) {
                $supp->delete();  // Trigger deleting and deleted events
            }
            ////////////////////////////////////////////newStoreMange///////////////
            $quaditems = [];
            $automaicQayd = new QaydController();
            $invoiceac = 0;
            $taxac = 0;
            $binvoiceac = 0;
            $buyCoastAc = 0;
            $taxAc2 = 0;
            $totalTaxEgp = 0;
            $acSupNo = Supplier::where('id', $request->invSupplier)->first()->accountant_number;
            $Ac_currency_id = $request->currency_id;
            $Ac_all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($Ac_currency_id) {
                return $query->where('to', null)->where('currency_id', $Ac_currency_id);
            }])->where('id', $Ac_currency_id)->get();


            if ($request->transCoast > 0) {
                // array_push($quaditems, (object) ['acountant_id' => 33101, 'dayin' => 0, 'madin' => $request->transCoast]);
                $buyCoastAc += $request->transCoast;
            }
            if ($request->insuranceCoast > 0) {
                // array_push($quaditems, (object) ['acountant_id' => 33102, 'dayin' => 0, 'madin' => $request->insuranceCoast]);
                $buyCoastAc += $request->insuranceCoast;
            }
            if ($request->customs > 0) {
                // array_push($quaditems, (object) ['acountant_id' => 33103, 'dayin' => 0, 'madin' => $request->customs]);
                $buyCoastAc += $request->customs;
            }
            if ($request->commition > 0) {
                // array_push($quaditems, (object) ['acountant_id' => 33104, 'dayin' => 0, 'madin' => $request->commition]);
                $buyCoastAc += $request->commition;
            }
            if ($request->otherCoast > 0) {
                // array_push($quaditems, (object) ['acountant_id' => 33105, 'dayin' => 0, 'madin' => $request->otherCoast]);
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
                $binvoiceac = floatval($invoiceac - $taxac);
            } else {
                // شامل
                $binvoiceac = floatval($request->invAllTotal / 1.14); //  المشتريات غير شامل الضريبة
                $taxac = $request->invAllTotal - $binvoiceac; // الضريبة
                $invoiceac =  floatval($binvoiceac + $taxac);
            }


            if (floatval($request->taxkasmInvolved) == 1) {
                $taxAc2 = $request->invTotLbl * -1 / 100;
                $invoiceac = $invoiceac + $taxAc2;
            } else {
                $taxAc2 = 0;
            }


            $totalTaxEgp = $taxac + $taxAc2;
            if ($request->payment == 0  && $invoiceac == $request->invPaied) // البيع كاش
            {

                // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac *  $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
                array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => $binvoiceac *  $Ac_all_currency_types[0]->currencies[0]->value, 'dayin' => 0]); // المشتريات مدين
                array_push($quaditems, (object) ['acountant_id' => intval($request->store_id), 'madin' => 0, 'dayin' => ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // الخزنة دائن
                // array_push ( $quaditems , (object) [ 'acountant_id'=> $acSupNo , 'madin'=> 0 , 'dayin'=> ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value)+$buyCoastAc ] ); // العميل دائن

            } elseif ($request->payment == 1 && $invoiceac == $request->invPaied) // البيع شيك
            {
                // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
                array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => ($binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc, 'dayin' => 0]); // المشتريات مدين

                // array_push ( $quaditems , (object) [ 'acountant_id'=> $acSupNo , 'madin'=> 0 , 'dayin'=> ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value)+$buyCoastAc ] ); // العميل دائن


                array_push($quaditems, (object) ['acountant_id' => intval($request->store_id), 'madin' => 0, 'dayin' => ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // بنك دائن


            } else // البيع أجل
            {
                // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> $binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value , 'dayin'=>0 ] ); // المخزون مدين
                array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => ($binvoiceac * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc, 'dayin' => 0]); // المشتريات مدين


                array_push($quaditems, (object) ['acountant_id' => $acSupNo, 'madin' => 0, 'dayin' => ($invoiceac - $request->invPaied) * $Ac_all_currency_types[0]->currencies[0]->value]); // العميل دائن
                if (intval($request->store_id) != 0) // خزنة
                {
                    if ($request->invPaied > 0) {
                        array_push($quaditems, (object) ['acountant_id' => intval($request->store_id), 'madin' => 0, 'dayin' => ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // الخزنة دائن
                    }
                } else // بنك
                {
                    if ($request->invPaied > 0) {
                        array_push($quaditems, (object) ['acountant_id' => intval($request->store_id), 'madin' => 0, 'dayin' => ($request->invPaied * $Ac_all_currency_types[0]->currencies[0]->value) + $buyCoastAc]); // بنك دائن
                    }
                }
            }

            if ($taxac > 0) {
                array_push($quaditems, (object) ['acountant_id' => 175, 'dayin' => 0, 'madin' => $taxac * $Ac_all_currency_types[0]->currencies[0]->value]); // الضريبة مدين
            }
            if ($taxAc2 != 0) {
                array_push($quaditems, (object) ['acountant_id' => 2637, 'dayin' => $taxAc2 * $Ac_all_currency_types[0]->currencies[0]->value * -1, 'madin' => 0]); // الضريبة مدين
            }

            if ($buyCoastAc > 0) {
                // array_push($quaditems, (object) ['acountant_id' => $request->store_id, 'dayin' => $buyCoastAc, 'madin' => 0]); // الضريبة مدين
            }



            // *************************************************************************************



            if (isset($request->currency_id)  && isset($request->invSupplier)) {
                $currency_id = $request->currency_id;
                ////////////////////////adel////////////////////
                // if ($request->store_id == 0) {
                //     $total = BankSafeMoney::all()->last();
                // } else {
                //     $total = MoneySafe::where('store_id', $request->store_id)->orderBy('id','DESC')->first();
                // }
                $Ac_allTotal = 0;
                $bank = BankType::where('accountant_number', intval($request->store_id))->first();
                if ($bank) {
                    $raseed = $bank->bank_raseed;
                    $total_qabd = BankSafeMoney::where('bank_type_id', $bank->id)->where('type_money', 0)->sum('money'); //قبض
                    $total_sarf = BankSafeMoney::where('bank_type_id', $bank->id)->where('type_money', 1)->sum('money'); //صرف
                    // $total = floatval($raseed) + floatval($total_qabd) - floatval($total_sarf);
                    $total = floatval($raseed) - floatval($total_sarf)  + floatval($total_qabd) +  floatval($xpaied * $xAc_all_currency_types[0]->currencies[0]->value);

                    $Ac_allTotal  =  BankSafeMoney::where('bank_type_id', $bank->id)->orderBy('id', 'DESC')->first();
                } else {
                    $store_bank = Store::where('safe_accountant_number', intval($request->store_id))->first();
                    $raseed = $store_bank->store_raseed;
                    $total_qabd = MoneySafe::where('store_id', $store_bank->id)->where('type_money', 0)->sum('money'); //قبض
                    $total_sarf = MoneySafe::where('store_id', $store_bank->id)->where('type_money', 1)->sum('money'); //صرف
                    $total = floatval($raseed) + floatval($total_qabd) - floatval($total_sarf);
                    $Ac_allTotal = MoneySafe::where('store_id', $store_bank->id)->orderBy('id', 'DESC')->first();
                }

                $all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($currency_id) {
                    return $query->where('to', null)->where('currency_id', $currency_id);
                }])->where('id', $currency_id)->get();

                if (isset($total)) {

                    if ($total >= $request->invPaied * $all_currency_types[0]->currencies[0]->value) {
                        // return $total;
                        if (isset($request->inv_id)) {
                            $BuyTransaction = BuyTransaction::where('id', $request->inv_id)->first();
                        } else {

                            $BuyTransaction = new BuyTransaction();

                            $BuyTransaction->company_id = '10';
                            $BuyTransaction->date = $request->invDate;
                            $BuyTransaction->creation_date = Carbon::now();
                            $BuyTransaction->name = Carbon::now();
                            $BuyTransaction->final = '3';
                            $BuyTransaction->save();
                        }
                        // return $BuyTransaction;
                        $sup_id = 0;
                        $supplier = Supplier::where('id', $request->invSupplier)->first();
                        if ($bank) {
                            $Ac_allTotal_total = 0;
                            if ($Ac_allTotal) {
                                $Ac_allTotal_total = $Ac_allTotal->total;
                            }
                            if ($Store_paid < 0) {
                                BankSafeMoney::create([
                                    'notes' => 'صرف مبلغ تعديل فاتورة شراء رقم ' . ' ' . $BuyTransaction->id . ' ' . 'من مورد' . ' ' . $supplier->name,
                                    'date' => date('Y-m-d'),
                                    'flag' => 1,
                                    'money' => ($request->invPaied  * $all_currency_types[0]->currencies[0]->value),
                                    'total' => $Ac_allTotal_total - ($request->invPaied  * $all_currency_types[0]->currencies[0]->value),
                                    'type_money' => '1',
                                    'user_id' => Auth::user()->id,
                                    'store_id' => null,
                                    'bank_type_id' => $bank->id,
                                    'money_currency' =>  $request->invPaied,
                                    'currency_id' => $currency_id,

                                ]);
                            } else {
                                BankSafeMoney::create([
                                    'notes' => 'إيداع مبلغ تعديل فاتورة شراء رقم ' . ' ' . $BuyTransaction->id . ' ' . 'من مورد' . ' ' . $supplier->name,
                                    'date' => date('Y-m-d'),
                                    'flag' => 1,
                                    // 'money' => $Store_paid * $all_currency_types[0]->currencies[0]->value,
                                    'money' => $request->invPaied * $all_currency_types[0]->currencies[0]->value,
                                    'total' => $Ac_allTotal_total - ($request->invPaied * $all_currency_types[0]->currencies[0]->value),
                                    'type_money' => '1',
                                    'user_id' => Auth::user()->id,
                                    'store_id' => null,
                                    'bank_type_id' => $bank->id,
                                    'money_currency' =>  $request->invPaied,
                                    'currency_id' => $currency_id,

                                ]);
                            }
                        } else {
                            $Ac_allTotal_total = 0;
                            if ($Ac_allTotal) {
                                $Ac_allTotal_total = $Ac_allTotal->total;
                            }
                            $xx = Store::where('safe_accountant_number', intval($request->store_id))->first();
                            if ($Store_paid < 0) {
                                MoneySafe::create([
                                    'notes' => 'صرف مبلغ تعديل فاتورة شراء رقم ' . ' ' . $BuyTransaction->id . ' ' . 'من مورد' . ' ' . $supplier->name,
                                    'date' => date('Y-m-d'),
                                    'flag' => 1,
                                    'money' => ($Store_paid * $all_currency_types[0]->currencies[0]->value) * -1,
                                    'total' => $Ac_allTotal_total - ($Store_paid * $all_currency_types[0]->currencies[0]->value) * -1,
                                    'type_money' => '1',
                                    'user_id' => Auth::user()->id,
                                    'store_id' => $xx->id,

                                ]);
                            } else {
                                MoneySafe::create([
                                    'notes' => 'إيداع مبلغ تعديل فاتورة شراء رقم ' . ' ' . $BuyTransaction->id . ' ' . 'من مورد' . ' ' . $supplier->name,
                                    'date' => date('Y-m-d'),
                                    'flag' => 1,
                                    'money' => $Store_paid * $all_currency_types[0]->currencies[0]->value,
                                    'total' => $Ac_allTotal_total + ($Store_paid * $all_currency_types[0]->currencies[0]->value),
                                    'type_money' => '0',
                                    'user_id' => Auth::user()->id,
                                    'store_id' => $xx->id,

                                ]);
                            }
                        }
                        //***************************new**************************************************************************مراجعة هاااااااااااام
                        $Ac_currency_id = $request->currency_id;
                        $Ac_all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($Ac_currency_id, $xconfirmation_date) {
                            return $query->where('from', '>=', $xconfirmation_date)->where('to', '<=', $xconfirmation_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                        }])->where('id', $Ac_currency_id)->get();

                        // ********************************222222*********************************//تعديل قيمة سند القبصض السابق قبل عملية تعديل الفقاتورة
                        $buy_trnsactionold = BuyTransaction::where('id', $request->buyTransaction_id)->first();
                        if ($xpaied * $xAc_all_currency_types[0]->currencies[0]->value  != $request->invPaied_edit * $Ac_all_currency_types[0]->currencies[0]->value) {

                            if ($request->invPaied_edit > 0) {
                                $mad = new SanadSarf();
                                $mad->client_sup_id = $supplier->id;
                                $mad->paied = $request->invPaied_edit * $all_currency_types[0]->currencies[0]->value;
                                $mad->date = Carbon::now();
                                $mad->pyment_method = $request->store_id;
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
                        //**********************************End**************************************
                        if ($request->NewinvSupplier <>  null) {
                            // add new Supplier
                            $sup = new Supplier();
                            $sup->name = $request->NewinvSupplier;
                            $sup->save();
                            $sup_id = $sup->id;
                        } else {

                            $sup_id = $request->invSupplier;
                        }
                        // return $request->invPaied;
                        $orderSupplier = new OrderSupplier();
                        $orderSupplier->transaction_id = $BuyTransaction->id;
                        $orderSupplier->user_id = Auth::user()->id;
                        $orderSupplier->supplier_id = $sup_id;
                        $orderSupplier->pricebeforeTax = $request->invTotLbl;
                        $orderSupplier->status = '4';
                        $orderSupplier->deliver_date = Carbon::now();
                        $orderSupplier->currency_id = $request->currency_id;
                        $orderSupplier->total_price = $request->invAllTotal;
                        $orderSupplier->paied = $request->invPaied;
                        $orderSupplier->payment = $request->payment;
                        $orderSupplier->bank_account = $request->store_id;
                        $orderSupplier->transport_coast = $request->transCoast;
                        $orderSupplier->insurant_coast = $request->insuranceCoast;
                        $orderSupplier->customs_coast = $request->customs;
                        $orderSupplier->commotion_coast = $request->commition;
                        $orderSupplier->other_coast = $request->otherCoast;
                        $orderSupplier->coast = $request->InvCoasting;
                        $orderSupplier->taxInvolved_flag = $request->taxInvolved;
                        $orderSupplier->taxkasmInvolved_flag = $request->taxkasmInvolved;
                        if (floatval($request->taxInvolved) == 1) {
                            $orderSupplier->tax = 0;
                        } else {
                            $orderSupplier->tax = $request->invTax;
                        }


                        if ($request->invPaied < $request->invAllTotal) {
                            $orderSupplier->due_date = $request->dueDate;
                            // Supplier::where('id', $sup_id)->increment('raseed', (floatval($request->invAllTotal) - floatval($request->invPaied)) * $all_currency_types[0]->currencies[0]->value);
                        } else if ($request->invPaied > $request->invAllTotal) {
                            // Supplier::where('id', $sup_id)->decrement('raseed', (floatval($request->invPaied) - floatval($request->invAllTotal)) * $all_currency_types[0]->currencies[0]->value);
                        } else {
                        }
                        // $orderSupplier->bank_account = '';
                        // $orderSupplier->container_size = '';
                        $orderSupplier->confirmation_date = Carbon::now();
                        // $orderSupplier->image_url = '';
                        $orderSupplier->save();
                        // return $orderSupplier;

                        $date = Carbon::now();
                        $type = null;
                        $notes = 'فاتورة شراء وتوزيع بالأمر المباشر رقم' . $BuyTransaction->id . 'العملة ' . $Ac_all_currency_types[0]->name;
                        $qaydids = $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
                        Qayditem::where('qaydid', $qaydids)->update([
                            'invoiceid' => $BuyTransaction->id
                        ]);

                        $quaditems = [];
                        $automaicQayd = new QaydController();

                        if ($request->transCoast > 0) {
                            array_push($quaditems, (object) ['acountant_id' => 33101, 'dayin' => 0, 'madin' => $request->transCoast]);
                        }
                        if ($request->insuranceCoast > 0) {
                            array_push($quaditems, (object) ['acountant_id' => 33102, 'dayin' => 0, 'madin' => $request->insuranceCoast]);
                        }
                        if ($request->customs > 0) {
                            array_push($quaditems, (object) ['acountant_id' => 33103, 'dayin' => 0, 'madin' => $request->customs]);
                        }
                        if ($request->commition > 0) {
                            array_push($quaditems, (object) ['acountant_id' => 33104, 'dayin' => 0, 'madin' => $request->commition]);
                        }
                        if ($request->otherCoast > 0) {
                            array_push($quaditems, (object) ['acountant_id' => 33105, 'dayin' => 0, 'madin' => $request->otherCoast]);
                        }

                        $date = Carbon::now();
                        $type = null;
                        $notes = 'مصروفات علي فاتورة شراء رقم ' . $BuyTransaction->id . 'للمعلومية فقط تم تحميل المصروفات علي المشتريات والمخزون';
                        $qaydids = $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
                        Qayditem::where('qaydid', $qaydids)->update([
                            'invoiceid' => $BuyTransaction->id
                        ]);

                        $quaditems = [];
                        $Actotal = 0;
                        for ($i = 0; $i < count($request->partId); $i++) {

                            $ratiounit = getSmallUnit($request->unit[$i], $request->smallUnit[$i]);
                            # code...
                            $part = new Replyorder();
                            $part->order_supplier_id = $orderSupplier->id;
                            $part->part_id = $request->partId[$i];
                            $part->price = floatval($request->price[$i]) / floatval($ratiounit);
                            $part->amount = floatval($request->amount[$i]) * floatval($ratiounit);;
                            $part->unit_id = $request->unit[$i];
                            $part->source_id = $request->partSource[$i];
                            $part->status_id = $request->partStatus[$i];
                            $part->quality_id = $request->partQualty[$i];
                            $part->creation_date = Carbon::now();
                            $part->part_type_id = $request->types[$i];
                         
                            $part->save();


                            // /*******************************************************************

                            $store = Store::where('id', $request->Stores[$i])->first();
                            $Ac_inv = Replyorder::where('order_supplier_id', $orderSupplier->id)
                                ->where('part_id', $request->partId[$i])
                                ->where('source_id', $request->partSource[$i])
                                ->where('status_id', $request->partStatus[$i])
                                ->where('quality_id', $request->partQualty[$i])
                                ->where('part_type_id', $request->types[$i])
                                ->with('order_supplier')->first();

                            $Ac_currency_id = $Ac_inv->order_supplier->currency_id;
                            $Ac_currency_date = $Ac_inv->order_supplier->confirmation_date;
                            $Ac_all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                                return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                            }])->where('id', $Ac_currency_id)->get();




                            $Ac_price = $request->amount[$i] * $Ac_all_currency_types[0]->currencies[0]->value * $Ac_inv->price;

                            if ($i == count($request->partId) - 1) {
                                if (floatval($request->taxInvolved) == 1) {
                                    // غير شامل -
                                    array_push($quaditems, (object) ['acountant_id' => $store->accountant_number, 'madin' => ($Ac_price + $buyCoastAc), 'dayin' => 0]);
                                } else {
                                    // شامل +
                                    // dd($totalTaxEgp);
                                    array_push($quaditems, (object) ['acountant_id' => $store->accountant_number, 'madin' => ($Ac_price - ($totalTaxEgp) + $buyCoastAc), 'dayin' => 0]);
                                }
                            } else {
                                array_push($quaditems, (object) ['acountant_id' => $store->accountant_number, 'madin' => $Ac_price, 'dayin' => 0]);
                            }
                            $Actotal += $Ac_price;



                            ///////////////////////////////////////////////////////////////////////
                            if ($request->types[$i] == 1) {
                                $ratio = floatval($request->InvCoasting) / floatval($request->invTotLbl);
                                if (intval($request->amount[$i]) > 0) {
                                    $itemCoast = ($ratio *  (floatval($request->price[$i]) * floatval($request->amount[$i]) * floatval($ratiounit))) / (floatval($request->amount[$i]) * floatval($ratiounit));
                                } else {
                                    DB::rollback();
                                    session()->flash("success", "لا يمكن التعديل حيث أنه تم التوزيع علي كيت او عملية بيع");
                                    return redirect()->back();
                                }

                                $allpart = new AllPart();
                                $allpart->part_id = $request->partId[$i];
                                $allpart->order_supplier_id = $orderSupplier->id;
                                $allpart->amount = floatval($request->amount[$i])  * floatval($ratiounit);
                                $allpart->source_id = $request->partSource[$i];
                                $allpart->status_id = $request->partStatus[$i];
                                $allpart->quality_id = $request->partQualty[$i];
                                $allpart->buy_price = floatval($request->price[$i]) /  floatval($ratiounit);
                                $allpart->insertion_date = Carbon::now();
                                $allpart->remain_amount = floatval($request->amount[$i])  * floatval($ratiounit);
                                $allpart->flag = 3;
                                $allpart->buy_costing = $itemCoast;
                                $allpart->save();

                                if (isset($request->Sections) && count($request->Sections) > 0) {
                                    // ghoniemstoreLog
                                    $storelog = new StoresLog();
                                    $storelog->All_part_id = $allpart->id;
                                    $storelog->store_id = $request->Stores[$i];
                                    $storelog->store_action_id = 1;
                                    $storelog->amount = floatval($request->amount[$i])  * floatval($ratiounit);
                                    $storelog->date = Carbon::now();
                                    $storelog->status = 1;
                                    $storelog->type_id = $request->types[$i];
                                    $storelog->save();

                                    $storelog = new StoresLog();
                                    $storelog->All_part_id = $allpart->id;
                                    $storelog->store_id = $request->Stores[$i];
                                    $storelog->store_action_id = 3;
                                    $storelog->amount = floatval($request->amount[$i])  * floatval($ratiounit);
                                    $storelog->date = Carbon::now();
                                    $storelog->status = 3;
                                    $storelog->type_id = $request->types[$i];
                                    $storelog->save();


                                    $store = Store::where('id', $request->Stores[$i])->get();
                                    // return $store;

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

                                        $storeCls->part_id = $request->partId[$i];
                                        $storeCls->amount = floatval($request->amount[$i])  * floatval($ratiounit);
                                        $storeCls->supplier_order_id = $orderSupplier->id;
                                        $storeCls->type_id = $request->types[$i];
                                        $storeCls->store_log_id = $storelog->id;
                                        $storeCls->date = Carbon::now();
                                        $storeCls->save();


                                        $section = new StoreSection();
                                        $section->store_id = $request->Stores[$i];
                                        $section->section_id = $request->Sections[$i];
                                        $section->order_supplier_id = $orderSupplier->id;
                                        $section->type_id = $request->types[$i];
                                        $section->part_id = $request->partId[$i];
                                        $section->source_id = $request->partSource[$i];
                                        $section->status_id = $request->partStatus[$i];
                                        $section->quality_id = $request->partQualty[$i];
                                        $section->amount = floatval($request->amount[$i])  * floatval($ratiounit);
                                        $section->date = Carbon::now();
                                        $section->save();
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                        continue;
                                    }
                                }
                            }

                            ///// kit
                            if ($request->types[$i] == 6) {
                                $ratio = floatval($request->InvCoasting) / floatval($request->invTotLbl);

                                if (intval($request->amount[$i]) > 0) {
                                    $itemCoast = ($ratio *  (floatval($request->price[$i]) * floatval($request->amount[$i]) * floatval($ratiounit))) / (floatval($request->amount[$i]) * floatval($ratiounit));
                                } else {
                                    DB::rollback();
                                    session()->flash("success", "لا يمكن التعديل حيث أنه تم التوزيع علي كيت او عملية بيع");
                                    return redirect()->back();
                                }

                                $allpart = new AllKit();
                                $allpart->part_id = $request->partId[$i];;
                                $allpart->order_supplier_id = $orderSupplier->id;
                                $allpart->amount = floatval($request->amount[$i])  * floatval($ratiounit);
                                $allpart->source_id = $request->partSource[$i];
                                $allpart->status_id = $request->partStatus[$i];
                                $allpart->quality_id = $request->partQualty[$i];
                                $allpart->buy_price = floatval($request->price[$i]) / floatval($ratiounit);
                                $allpart->insertion_date = Carbon::now();
                                $allpart->remain_amount = floatval($request->amount[$i])  * floatval($ratiounit);
                                $allpart->flag = 3;
                                $allpart->buy_costing = $itemCoast;
                                $allpart->save();

                                if (count($request->Sections) > 0) {
                                    // ghoniemstoreLog
                                    $storelog = new StoresLog();
                                    $storelog->All_part_id = $allpart->id;
                                    $storelog->store_id = $request->Stores[$i];
                                    $storelog->store_action_id = 1;
                                    $storelog->amount = floatval($request->amount[$i])  * floatval($ratiounit);
                                    $storelog->date = Carbon::now();
                                    $storelog->status = 1;
                                    $storelog->type_id = $request->types[$i];
                                    $storelog->save();

                                    $storelog = new StoresLog();
                                    $storelog->All_part_id = $allpart->id;
                                    $storelog->store_id = $request->Stores[$i];
                                    $storelog->store_action_id = 3;
                                    $storelog->amount = floatval($request->amount[$i])  * floatval($ratiounit);
                                    $storelog->date = Carbon::now();
                                    $storelog->status = 3;
                                    $storelog->type_id = $request->types[$i];
                                    $storelog->save();

                                    $store = Store::where('id', $request->Stores[$i])->get();
                                    // return $store;
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

                                        $storeCls->part_id = $request->partId[$i];
                                        $storeCls->amount = floatval($request->amount[$i])  * floatval($ratiounit);
                                        $storeCls->supplier_order_id = $orderSupplier->id;
                                        $storeCls->type_id = $request->types[$i];
                                        $storeCls->store_log_id = $storelog->id;
                                        $storeCls->date = Carbon::now();
                                        $storeCls->save();


                                        $section = new StoreSection();
                                        $section->store_id = $request->Stores[$i];
                                        $section->section_id = $request->Sections[$i];
                                        $section->order_supplier_id = $orderSupplier->id;
                                        $section->type_id = $request->types[$i];
                                        $section->part_id = $request->partId[$i];
                                        $section->source_id = $request->partSource[$i];
                                        $section->status_id = $request->partStatus[$i];
                                        $section->quality_id = $request->partQualty[$i];
                                        $section->amount =  floatval($request->amount[$i])  * floatval($ratiounit);
                                        $section->date = Carbon::now();
                                        $section->save();
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                        continue;
                                    }
                                }
                            }

                            /// wheel
                            if ($request->types[$i] == 2) {
                                $ratio = floatval($request->InvCoasting) / floatval($request->invTotLbl);

                                if (intval($request->amount[$i]) > 0) {
                                    $itemCoast = ($ratio *  (floatval($request->price[$i]) * floatval($request->amount[$i]) * floatval($ratiounit))) / (floatval($request->amount[$i]) * floatval($ratiounit));
                                } else {
                                    DB::rollback();
                                    session()->flash("success", "لا يمكن التعديل حيث أنه تم التوزيع علي كيت او عملية بيع");
                                    return redirect()->back();
                                }
                                $allpart = new AllWheel();
                                $allpart->part_id = $request->partId[$i];;
                                $allpart->order_supplier_id = $orderSupplier->id;
                                $allpart->amount =  floatval($request->amount[$i])  * floatval($ratiounit);
                                $allpart->source_id = $request->partSource[$i];
                                $allpart->status_id = $request->partStatus[$i];
                                $allpart->quality_id = $request->partQualty[$i];
                                $allpart->buy_price = floatval($request->price[$i]) / floatval($ratiounit);
                                $allpart->insertion_date = Carbon::now();
                                $allpart->remain_amount = floatval($request->amount[$i])  * floatval($ratiounit);
                                $allpart->flag = 3;
                                $allpart->buy_costing = $itemCoast;
                                $allpart->save();

                                if (count($request->Sections) > 0) {
                                    // ghoniemstoreLog
                                    $storelog = new StoresLog();
                                    $storelog->All_part_id = $allpart->id;
                                    $storelog->store_id = $request->Stores[$i];
                                    $storelog->store_action_id = 1;
                                    $storelog->amount =  floatval($request->amount[$i])  * floatval($ratiounit);
                                    $storelog->date = Carbon::now();
                                    $storelog->status = 1;
                                    $storelog->type_id = $request->types[$i];
                                    $storelog->save();

                                    $storelog = new StoresLog();
                                    $storelog->All_part_id = $allpart->id;
                                    $storelog->store_id = $request->Stores[$i];
                                    $storelog->store_action_id = 3;
                                    $storelog->amount =  floatval($request->amount[$i])  * floatval($ratiounit);
                                    $storelog->date = Carbon::now();
                                    $storelog->status = 3;
                                    $storelog->type_id = $request->types[$i];
                                    $storelog->save();


                                    $store = Store::where('id', $request->Stores[$i])->get();
                                    // return $store;
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

                                        $storeCls->part_id = $request->partId[$i];
                                        $storeCls->amount =  floatval($request->amount[$i])  * floatval($ratiounit);
                                        $storeCls->supplier_order_id = $orderSupplier->id;
                                        $storeCls->type_id = $request->types[$i];
                                        $storeCls->store_log_id = $storelog->id;
                                        $storeCls->date = Carbon::now();
                                        $storeCls->save();


                                        $section = new StoreSection();
                                        $section->store_id = $request->Stores[$i];
                                        $section->section_id = $request->Sections[$i];
                                        $section->order_supplier_id = $orderSupplier->id;
                                        $section->type_id = $request->types[$i];
                                        $section->part_id = $request->partId[$i];
                                        $section->source_id = $request->partSource[$i];
                                        $section->status_id = $request->partStatus[$i];
                                        $section->quality_id = $request->partQualty[$i];
                                        $section->amount =  floatval($request->amount[$i])  * floatval($ratiounit);
                                        $section->date = Carbon::now();
                                        $section->save();
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                        continue;
                                    }
                                }
                            }

                            // $this->Repricing($request->partId[$i],$request->partSource[$i],$request->partStatus[$i],$request->partQualty[$i],$request->types[$i]);

                        }


                        // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> 0 , 'dayin'=>$Actotal ] ); // المخزون دائن
                        array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => 0, 'dayin' => ($Actotal - ($totalTaxEgp) + $buyCoastAc)]); // المشتريات دائن
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
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash("success", "لا يمكن التعديل حيث أنه تم التوزيع علي كيت او عملية بيع");
            return redirect()->back();
        }
    }
    public function buyInv($id)
    {
        // return $id;
        $x = BuyTransaction::where('id', $id)->first();
        $buyTrans = $x;
        $orderSup = OrderSupplier::where('transaction_id', $buyTrans->id)->with('supplier')->get();
        $store = Store::where('table_name', '<>', 'damaged_parts')->get();
        $items_part = AllPart::where('order_supplier_id', $orderSup[0]->id)->with('store_log')->with('sections')->with('part.getsmallunit.unit')->with('source')->with('status')->with('part_quality')->get();
        $items_kit = Allkit::where('order_supplier_id', $orderSup[0]->id)->with('kit')->with('sections')->with('store_log')->with('source')->with('status')->with('part_quality')->get();
        $items_wheel = AllWheel::where('order_supplier_id', $orderSup[0]->id)->with('wheel')->with('sections')->with('store_log')->with('source')->with('status')->with('part_quality')->get();
        $items = (new Collection)->merge($items_part)->merge($items_kit)->merge($items_wheel);
        $invoice_status = 0; // not int store
        foreach ($items as $key => $item) {
            $item['remainAmountInInvoice'] = StoresLog::where('All_part_id', $item->id)->where('store_action_id', 3)->where('type_id', 1)->sum('amount');
            $item['replyOrder'] = Replyorder::where('order_supplier_id', $item->order_supplier_id)->where('part_id', $item->part_id)->where('source_id', $item->source_id)->where('status_id', $item->status_id)->where('quality_id', $item->quality_id)->first();
            $item['Partitemstorelog'] = StoresLog::where('All_part_id', $item->id)->where('type_id', 1)->where('store_action_id', 3)->get();
            $item['Kititemstorelog'] = StoresLog::where('All_part_id', $item->id)->where('type_id', 6)->where('store_action_id', 3)->get();
            $item['Wheelitemstorelog'] = StoresLog::where('All_part_id', $item->id)->where('type_id', 2)->where('store_action_id', 3)->get();
            if (count($item['Partitemstorelog']) > 0 || count($item['Kititemstorelog']) > 0 || count($item['Wheelitemstorelog']) > 0) {
                $invoice_status = 1;
            }
        }

        // return $items;
        $bank_types = BankType::all();
        $store_safe = Store::where('safe_accountant_number', '<>', '0')->get();

        // $bank = BankType::where('accountant_number',$orderSup[0]->bank_account)->first();

        // if ($bank) {
        //     $raseed= $bank->bank_raseed;
        //     $total_qabd = BankSafeMoney::where('bank_type_id',$bank->id)->where('type_money',0)->sum('money');//قبض
        //     $total_sarf = BankSafeMoney::where('bank_type_id',$bank->id)->where('type_money',1)->sum('money');//صرف
        //     $total_rassed= floatval($raseed) + floatval($total_qabd) - floatval($total_sarf);
        //     // $total = BankSafeMoney::all()->last();
        // } else {

        //     $bank = Store::where('safe_accountant_number',$orderSup[0]->bank_account)->first();
        //     $raseed= $bank->store_raseed;
        //     $total_qabd = MoneySafe::where('store_id',$bank->id)->where('type_money',0)->sum('money');//قبض
        //     $total_sarf = MoneySafe::where('store_id',$bank->id)->where('type_money',1)->sum('money');//صرف
        //     $total_rassed= floatval($raseed) + floatval($total_qabd) - floatval($total_sarf);
        //     // $total = MoneySafe::where('store_id', $safeId)->latest()->first();
        // }
        // return $store_safe   ;
        $total_rassed = 0;

        $stores = Store::where('table_name', '<>', 'damaged_parts')->get();
        // return $orderSup;

        return view('editBuyInv', compact('total_rassed', 'orderSup', 'buyTrans', 'items', 'bank_types', 'store_safe', 'invoice_status', 'stores'));
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

    public function deleteInv( $id)
    {
        // return $id;
        DB::beginTransaction();
        try {
            $id = BuyTransaction::where('id',$id)->first();
            $orderSup = OrderSupplier::where('transaction_id', $id->id)->get();
            StoreSection::where('order_supplier_id', $orderSup[0]->id)->delete();


            foreach ($orderSup as $order) {
                $repOrders = Replyorder::where('order_supplier_id', $order->id)->get();

                foreach ($repOrders as $repOrder) {
                    if ($repOrder->part_type_id == 1) {
                        $allparts = AllPart::where('order_supplier_id', $order->id)->get();
                        foreach ($allparts as $allpart) {
                            $storelog = StoresLog::where('All_part_id', $allpart->id)->delete();
                        }
                        AllPart::where('order_supplier_id', $order->id)->delete();
                    } elseif ($repOrder->part_type_id == 2) {
                        $allwheels = AllWheel::where('order_supplier_id', $order->id)->get();
                        foreach ($allwheels as $allwheel) {
                            $storelog = StoresLog::where('All_part_id', $allwheel->id)->delete();
                        }
                        AllWheel::where('order_supplier_id', $order->id)->delete();
                    } elseif ($repOrder->part_type_id == 6) {
                        ///////salam عشان امسح من فواتير التوزيع المباشر للكيت اللى دخلت المخازن 24-6-2024
                        AllKitPartItemSection::where('order_sup_id', $orderSup[0]->id)->delete();
                        AllKitPartItem::where('order_sup_id', $orderSup[0]->id)->delete();
                        $allkits = AllKit::where('order_supplier_id', $order->id)->get();
                        foreach ($allkits as $allkit) {
                            $storelog = StoresLog::where('All_part_id', $allkit->id)->get(); //delete

                            $store = Store::find($storelog[0]->store_id);

                            $store_model = ucfirst($store->table_name);
                            if ($store_model == "Damaged_parts") {
                                $store_model = "damagedPart";
                            }
                            $entity = 'App\\Models\\' . $store_model;

                            $entity::where('supplier_order_id', $order->id)->delete();
                            $storelog = StoresLog::where('All_part_id', $allkit->id)->delete(); //delete
                            //////endsalam

                        }
                        AllKit::where('order_supplier_id', $order->id)->delete();
                    } else {
                    }
                }
                Replyorder::where('order_supplier_id', $order->id)->delete();
            }
            OrderSupplier::where('transaction_id', $id->id)->delete();
            $id->delete();
            DB::commit();
            return Redirect::back()->with('message', 'Successfully Deleted !');
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash("success", "لا يمكن المسح");
            return redirect()->back();
        }
    }
    public function source()
    {
        return Source::orderBy('name_arabic')->get();
    }
    public function status()
    {
        return Status::orderBy('name')->get();
    }
    public function quality()
    {
        return PartQuality::orderBy('name')->get();
    }

    public function lastInvId()
    {
        $lastid = BuyTransaction::orderBy('id', 'desc')->first();
        if ($lastid) {
            return $lastid->id;
        } else {
            return 0;
        }
    }

   

    public function printBuyInvoice($id)
    {
        // return $id;
        $id = BuyTransaction::where('id', $id)->first();
        $invoice_id = $id->id;
        $invoice = $id;
        $company = $id->with('company')->get();
        // return $company;
        $orderSup = OrderSupplier::where('transaction_id', $invoice_id)->with('supplier')->with('currency_type')->get();
        // $items = Replyorder::where('order_supplier_id',$orderSup[0]->id)->where('part_type_id','1')->with('source')->with('status')->with('part_quality')->get();
        $items = Replyorder::where('order_supplier_id', $orderSup[0]->id)->with('source')->with('status')->with('part_quality')->get();
        foreach ($items as $key => $item) {
            if (($item->part_type_id) == 1) {
                $item['part'] = Part::where('id', $item->part_id)->with('part_numbers')->get();
            } elseif (($item->part_type_id) == 2) {  // wheel
                $item['part'] = Wheel::where('id', $item->part_id)->get();
            } elseif (($item->part_type_id) == 6) {  // kit
                $item['part'] = Kit::where('id', $item->part_id)->with('kit_numbers')->get();
            } elseif (($item->part_type_id) == 3) {  // kit
                $item['part'] = Tractor::where('id', $item->part_id)->get();
            } elseif (($item->part_type_id) == 4) {  // kit
                $item['part'] = Clark::where('id', $item->part_id)->get();
            } elseif (($item->part_type_id) == 5) {  // kit
                $item['part'] = Equip::where('id', $item->part_id)->get();
            }
        }

        return View('printBuyInvoice', compact(['invoice', 'orderSup', 'items', 'company']));
    }

    public function storeManageItems( $id)
    {
        // return $id;
        $id = BuyTransaction::where('id',$id)->first();
        $buyTrans = $id;
        $orderSup = OrderSupplier::where('transaction_id', $buyTrans->id)->with('supplier')->with('currency_type')->get();
        if (count($orderSup) <= 0) {
            return redirect()->to('storeManage/')->with('success', 'No Supplier Support');;
        }
        $store = Store::where('table_name', '<>', 'damaged_parts')->where('id', '<>', 8)->get();
        // $items = Replyorder::where('order_supplier_id',$orderSup[0]->id)->with('part')->with('source')->with('status')->with('part_quality')->get();


        $items1 = AllPart::where('order_supplier_id', $orderSup[0]->id)->with('part')->with('source')->with('status')->with('part_quality')->get();
        $items2 = AllKit::where('order_supplier_id', $orderSup[0]->id)->with('kit')->with('source')->with('status')->with('part_quality')->get();
        $items3 = AllWheel::where('order_supplier_id', $orderSup[0]->id)->with('wheel')->with('source')->with('status')->with('part_quality')->get();

        $items = $items1->merge($items2)->merge($items3);
        // return $items;

        foreach ($items as $key => $item) {
            // return $item;
            // $item['remainAmountInInvoice'] = StoresLog::where('All_part_id',$item->id)->where('store_action_id',3)->where('type_id',1)->sum('amount');
            $item['remainAmountInInvoice'] = StoresLog::where('All_part_id', $item->id)
        ->where('store_action_id', 3)
        ->where('status', 3)
        ->selectraw('sum(amount) as amount ,unit_id')
        ->with('unit')
        ->first();
        // $item['remainAmountInInvoice'] = $item['remainAmountInInvoice'] ? $item['remainAmountInInvoice'] : 0;

            $item['needConfirmAmountInInvoice'] = StoresLog::where('All_part_id', $item->id)->where('store_action_id', 1)->where('status', 0)->sum('amount');   
            if (isset($item->part)) {
                $item['type'] = 1;
            } elseif (isset($item->wheel)) {
                $item['type'] = 2;
            } elseif (isset($item->kit)) {
                $item['type'] = 6;
            }
        }
        // return $items;
        return View('storeManageItems', compact('buyTrans', 'orderSup', 'items', 'store'));
    }
    public function storeSend1(Request $request)
    {
        // return $request;


        // /*******************************************************************************************************
        $quaditems = [];
        $automaicQayd = new QaydController();
        $Actotal = 0;



        // /*******************************************************************************************************
        $stores = store::where('table_name', '<>', 'damaged_parts')->where('id', '<>', '8')->get();
        foreach ($stores as $key => $store) {
            $storeName = 'store' . $store->id;
            $storeId = $store->id;
            for ($i = 0; $i < count($request->$storeName); $i++) {
                $amount = $request->$storeName[$i];

                if ($amount <> null && intval($amount) > 0) {
                    $ratioamount=getSmallUnit($request->unit[$i],$request->small_unit[$i]);
                    // /*******************************************************************************************************
                    $storex = Store::where('id', $storeId)->first();
                    $Ac_inv = Replyorder::where('order_supplier_id', $request['orderSupplierId'])
                        ->where('part_id', $request->partIds[$i])
                        ->where('source_id', $request->source[$i])
                        ->where('status_id', $request->status[$i])
                        ->where('quality_id', $request->quality[$i])
                        ->where('part_type_id', 1)
                        ->with('order_supplier')->first();
                    $Ac_currency_id = $Ac_inv->order_supplier->currency_id;
                    $Ac_currency_date = $Ac_inv->order_supplier->confirmation_date;
                    $Ac_all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($Ac_currency_id, $Ac_currency_date) {
                        return $query->where('from', '>=', $Ac_currency_date)->where('to', '<=', $Ac_currency_date)->where('currency_id', $Ac_currency_id)->orWhere('to', '=', null);
                    }])->where('id', $Ac_currency_id)->get();


                    // $Ac_inv->price // السعر
                    // $Ac_inv->order_supplier->currency_id // العملة
                    // $request['data'][1]['store_data'][0]['ins_amount']; // الكمية
                    // $Ac_inv->order_supplier->confirmation_date // تاريخ الشراء
                    // $Ac_all_currency_types[0]->currencies[0]->value  // سعر العملة في وقت الشراء

                    $Ac_price = $amount * $Ac_all_currency_types[0]->currencies[0]->value * $Ac_inv->price;

                    array_push($quaditems, (object) ['acountant_id' => $storex->accountant_number, 'madin' => $Ac_price, 'dayin' => 0]); // المخزن مدين


                    $Actotal += $Ac_price;


                    // /*******************************************************************************************************
                    $storelog = new StoresLog();
                    $storelog->All_part_id = $request->allpart[$i];

                    $storelog->store_action_id = 3;
                    $storelog->store_id = $storeId;
                    $storelog->amount = $amount*$ratioamount;
                    $storelog->date = Carbon::now();
                    $storelog->status = 3;
                    $storelog->type_id = $request->types[$i];
                    $storelog->unit_id = $request->unit[$i];
                    $storelog->save();


                    $storeClsName = ucfirst($store->table_name);
                    $storeClsName = 'App\Models\\' . $storeClsName;
                    $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);

                    // return $storeClsName;
                    if ($store->table_name == "damaged_parts") {
                        $storeClsName = "App\Models\\DamagedPart";
                    }
                    try {
                        //code...
                        $storeCls = new $storeClsName();

                        $storeCls->part_id = $request->partIds[$i];
                        $storeCls->amount = $amount*$ratioamount;
                        $storeCls->supplier_order_id = $request->orderSupplierId;
                        $storeCls->type_id = $request->types[$i];;
                        $storeCls->store_log_id = $storelog->id;
                        $storeCls->date = Carbon::now();
                        $storeCls->unit_id = $request->unit[$i];
                        $storeCls->save();
                    } catch (\Throwable $th) {
                        //throw $th;
                        continue;
                    }



                    // /storeManage
                }
            }
        }
        // array_push ( $quaditems , (object) [ 'acountant_id'=> 1314  , 'madin'=> 0 , 'dayin'=>$Actotal ] ); // المخزون دائن
        array_push($quaditems, (object) ['acountant_id' => 2641, 'madin' => 0, 'dayin' => $Actotal]); // المخزون دائن
        $date = Carbon::now();
        $type = null;
        $notes = 'توزيع مباشر علي المخزن ';
        $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
        // return 'sss';
        return redirect()->to('storeManage/');
    }

    public function stores()
    {
        $stores =  Store::all();
        // return  $stores;
        foreach ($stores as $key => $store) {

            $store_model = ucfirst($store->table_name);
            $storeClsName = 'App\Models\\' . $store_model;

            $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
            if ($store->table_name == "damaged_parts") {
                $storeClsName = "App\Models\\DamagedPart";
            }
            $store['storeDetails'] = $storeClsName::all();
        }
        // return  $store;
        return view('stores', compact('stores'));
    }

    public function gard(Store $storeid)
    {

        $storeClsName = ucfirst($storeid->table_name);
        $storeClsName = 'App\Models\\' . $storeClsName;
        $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);
        if ($storeid->table_name == "damaged_parts") {
            $storeClsName = "App\Models\\DamagedPart";
        }
        $storeDetails = $storeClsName::whereIn('type_id', [1, 2, 6])->with('order_supplier')->with('stores_log')->where('amount', '>', 0)->orderBy('part_id')->get();
        // $storeDetails = $storeClsName::where('type_id',1)->groupBy('part_id','type_id')->selectRaw('sum(amount) as sum, part_id,type_id')->with('order_supplier')->with('stores_log')->get();

        // return $storeDetails;
        foreach ($storeDetails as $key => $value) {
            if ($value->type_id == 1) {
                $value['part'] = Part::where('id', $value->part_id)->with('part_numbers')->get();
                $value['allPart'] = AllPart::where('id', $value->stores_log->All_part_id)->with('source')->with('status')->with('part_quality')->get();
                $value['part_numbers'] = PartNumber::where('part_id', $value->part_id)->get();
            } elseif ($value->type_id == 2) {  // wheel
                $value['part'] = Wheel::where('id', $value->part_id)->get();
                $value['allPart'] = AllWheel::where('id', $value->stores_log->All_part_id)->with('source')->with('status')->with('part_quality')->get();
                $value['part_numbers'] = [];
            } elseif ($value->type_id == 6) {  // kit
                $value['part'] = Kit::where('id', $value->part_id)->get();
                $value['allPart'] = AllKit::where('id', $value->stores_log->All_part_id)->with('source')->with('status')->with('part_quality')->get();
                $value['part_numbers'] = KitNumber::where('kit_id', $value->part_id)->get();
            }
        }
        // return $storeDetails;
        return view('gard', compact('storeDetails', 'storeid'));
    }


    public function editBuyInv(Request $request)
    {
        // return $request;
        if ($request->type_id == 1) // part
        {
            AllPart::where('id', $request->allpartId)->update(['amount' => $request->amount, 'remain_amount' => $request->amount, 'buy_price' => $request->price]);
        } elseif ($request->type_id == 2) // wheel
        {
            AllWheel::where('id', $request->allpartId)->update(['amount' => $request->amount, 'remain_amount' => $request->amount, 'buy_price' => $request->price]);
        } elseif ($request->type_id == 6) // kit
        {
            AllKit::where('id', $request->allpartId)->update(['amount' => $request->amount, 'remain_amount' => $request->amount, 'buy_price' => $request->price]);
        } else {

            return Redirect::back()->with('message', 'error try again !');
        }

        Replyorder::where('id', $request->replyorder)->update(['amount' => $request->amount, 'price' => $request->price]);

        for ($i = 0; $i < count($request->log); $i++) {
            StoresLog::where('id', $request->log[$i])->update(['amount' => $request->amount]);
        }

        if ($request->store_id == 0) {
        } else {
            $store = Store::where('id', $request->store_id)->get();
            // return $store;
            $storeClsName = 'App\Models\\' . ucfirst($store[0]->table_name);
            $storeClsName = str_replace([' ', '_', '-'], "", $storeClsName);

            if ($store[0]->table_name == "damaged_parts") {
                $storeClsName = "App\Models\\DamagedPart";
            }

            $storeClsName::where('part_id', $request->part_id)->where('type_id', $request->type_id)->where('supplier_order_id', $request->order_supplier_id)->update(['amount' => $request->amount]);
        }


        // change total in orderSupplier
        // OrderSupplier::where('id',$request->order_supplier_id)->update([
        //     'pricebeforeTax' =>
        //     ]);

        return Redirect::back()->with('message', 'Successfully Upadte !');
    }

    public function buyinvnewPage()
    {
        $bank_types = BankType::all();
        $store_safe = Store::where('safe_accountant_number', '<>', '0')->get();
        // $lastInvId = BuyTransaction::latest()->first()->id;
        return view('newbuyInvoice2', compact('bank_types', 'store_safe'));
    }

    public function getlastpriceValue(Request $request)
    {
        // return $request;
        if ($request->type_id == "1") {
            $data = AllPart::where('part_id', $request->part_id)->where('source_id', $request->source_id)->where('status_id', $request->status_id)->where('quality_id', $request->quality_id)->orderBy('id', 'DESC')->first();
            if ($data) {
                return $data->buy_price;
            } else {
                return 0;
            }
        }
    }
    public function Repricing($partId, $partSource, $partStatus, $partQualty, $types)
    {


        DB::beginTransaction();
        try {

            $oldSaleTypeRatio = SaleTypeRatio::where('to', null)->where('type', $types)->get();

            foreach ($oldSaleTypeRatio as $key => $salereatio) {
                $cond = SalePricing::where('to', null)->where('part_id', $partId)->where('sale_type', $salereatio->sale_type_id)->where('source_id', $partSource)->where('status_id', $partStatus)->where('quality_id', $partQualty)->where('type_id', $types);
                if ($cond) {
                    $conddata = $cond->first();
                    $cond->update(['to' => Carbon::now()]);
                }

                $buyCoast = 0;

                if ($types == 1) {
                    $row = AllPart::where('part_id', $partId)->where('source_id', $partSource)->where('status_id', $partStatus)->where('quality_id', $partQualty)->with('order_supplier')->orderBy('id', 'DESC')->first();
                    $cureencyId = $row->order_supplier->currency_id;
                    $cureencydate = $row->order_supplier->confirmation_date;

                    $currency_data = CurrencyType::with(['currencies' => function ($query) use ($cureencyId, $cureencydate) {
                        return $query->where('from', '>=', $cureencydate)->where('to', '<=', $cureencydate)->where('currency_id', $cureencyId)->orWhere('to', '=', null);
                    }])->where('id', $cureencyId)->first();


                    $buyCoast = $row->buy_price * $currency_data->currencies[0]->value;
                    $buyCoast += $row->buy_costing;
                } elseif ($types == 2) {
                    $row = AllWheel::where('part_id', $partId)->where('source_id', $partSource)->where('status_id', $partStatus)->where('quality_id', $partQualty)->with('order_supplier')->orderBy('id', 'DESC')->first();
                    $cureencyId = $row->order_supplier->currency_id;
                    $cureencydate = $row->order_supplier->confirmation_date;

                    $currency_data = CurrencyType::with(['currencies' => function ($query) use ($cureencyId, $cureencydate) {
                        return $query->where('from', '>=', $cureencydate)->where('to', '<=', $cureencydate)->where('currency_id', $cureencyId)->orWhere('to', '=', null);
                    }])->where('id', $cureencyId)->first();


                    $buyCoast = $row->buy_price * $currency_data->currencies[0]->value;
                    $buyCoast += $row->buy_costing;
                } elseif ($types == 3) {
                    $row = AllTractor::where('part_id', $partId)->where('source_id', $partSource)->where('status_id', $partStatus)->where('quality_id', $partQualty)->with('order_supplier')->orderBy('id', 'DESC')->first();
                    $cureencyId = $row->order_supplier->currency_id;
                    $cureencydate = $row->order_supplier->confirmation_date;

                    $currency_data = CurrencyType::with(['currencies' => function ($query) use ($cureencyId, $cureencydate) {
                        return $query->where('from', '>=', $cureencydate)->where('to', '<=', $cureencydate)->where('currency_id', $cureencyId)->orWhere('to', '=', null);
                    }])->where('id', $cureencyId)->first();


                    $buyCoast = $row->buy_price * $currency_data->currencies[0]->value;
                    $buyCoast += $row->buy_costing;
                } elseif ($types == 4) {
                    $row = AllClark::where('part_id', $partId)->where('source_id', $partSource)->where('status_id', $partStatus)->where('quality_id', $partQualty)->with('order_supplier')->orderBy('id', 'DESC')->first();
                    $cureencyId = $row->order_supplier->currency_id;
                    $cureencydate = $row->order_supplier->confirmation_date;

                    $currency_data = CurrencyType::with(['currencies' => function ($query) use ($cureencyId, $cureencydate) {
                        return $query->where('from', '>=', $cureencydate)->where('to', '<=', $cureencydate)->where('currency_id', $cureencyId)->orWhere('to', '=', null);
                    }])->where('id', $cureencyId)->first();


                    $buyCoast = $row->buy_price * $currency_data->currencies[0]->value;
                    $buyCoast += $row->buy_costing;
                } elseif ($types == 5) {
                    $row = AllEquip::where('part_id', $partId)->where('source_id', $partSource)->where('status_id', $partStatus)->where('quality_id', $partQualty)->with('order_supplier')->orderBy('id', 'DESC')->first();
                    $cureencyId = $row->order_supplier->currency_id;
                    $cureencydate = $row->order_supplier->confirmation_date;

                    $currency_data = CurrencyType::with(['currencies' => function ($query) use ($cureencyId, $cureencydate) {
                        return $query->where('from', '>=', $cureencydate)->where('to', '<=', $cureencydate)->where('currency_id', $cureencyId)->orWhere('to', '=', null);
                    }])->where('id', $cureencyId)->first();


                    $buyCoast = $row->buy_price * $currency_data->currencies[0]->value;
                    $buyCoast += $row->buy_costing;
                } elseif ($types == 6) {
                    $row = AllKit::where('part_id', $partId)->where('source_id', $partSource)->where('status_id', $partStatus)->where('quality_id', $partQualty)->with('order_supplier')->orderBy('id', 'DESC')->first();
                    $cureencyId = $row->order_supplier->currency_id;
                    $cureencydate = $row->order_supplier->confirmation_date;

                    $currency_data = CurrencyType::with(['currencies' => function ($query) use ($cureencyId, $cureencydate) {
                        return $query->where('from', '>=', $cureencydate)->where('to', '<=', $cureencydate)->where('currency_id', $cureencyId)->orWhere('to', '=', null);
                    }])->where('id', $cureencyId)->first();


                    $buyCoast = $row->buy_price * $currency_data->currencies[0]->value;
                    $buyCoast += $row->buy_costing;
                }

                $pricing = new SalePricing();
                $pricing->part_id = $partId;
                $pricing->source_id = $partSource;
                $pricing->status_id = $partStatus;
                $pricing->quality_id = $partQualty;
                $pricing->from = Carbon::now();
                $pricing->to = null;
                $pricing->type_id = $types;
                $pricing->currency_id = 400;
                $pricing->sale_type = $salereatio->sale_type_id;
                $pricing->price = $buyCoast + ($buyCoast * floatval($salereatio->value) / 100);
                $pricing->save();
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function saveBuyTransactionEfrag(Request $request)
    {
        // dd($request->all());

        // dd($request->file('efrag_image'));



        $request->validate([
            'efrag_image.*' => 'required|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:4048',
        ]);

        // Check if files exist
        if ($request->hasFile('efrag_image')) {
            foreach ($request->file('efrag_image') as $file) {
                // Generate a unique name for the file
                $imageName = time() . '_' . $file->getClientOriginalName();


                // Move the file to the destination directory
                $file->move(public_path('assets/buytransaction'), $imageName);

                // Save the file information in the database (example)
                $partImg = new BuyTransactionEfrag();
                $partImg->transaction_id = $request->buyTransactionId;
                $partImg->image_title = $file->getClientOriginalName();
                $partImg->image_url = 'assets/buytransaction/' . $imageName;
                $partImg->save();
            }

            return back()->with('success', 'Files uploaded successfully!');
        }

        return back()->with('error', 'No files uploaded.');
    }

    public function deleteBuyTransEfragImage($BuyTransactionEfrag)
    {
        try {
            $efrag = BuyTransactionEfrag::where('id', $BuyTransactionEfrag)->first();
            $filePath = public_path($efrag->image_url);
            if (file_exists($filePath)) {
                unlink($filePath);
                $x = $efrag->delete();
                if ($x) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Record deleted successfully.',
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to delete the record.',
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File not found.',
                ], 404);
            }
        } catch (\Exception $e) {
            // Handle any exception that might occur during deletion
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function salam(){
        
    }
}
