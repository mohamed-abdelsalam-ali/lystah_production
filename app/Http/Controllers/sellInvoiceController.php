<?php

namespace App\Http\Controllers;

use App\Models\AllClark;
use App\Models\AllEquip;
use App\Models\AllKit;
use App\Models\AllPart;
use App\Models\AllTractor;
use App\Models\AllWheel;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Qayditem;
use App\Models\RefundInvoice;
use App\Models\RefundInvoicePayment;
use App\Models\SalePricing;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\DataTables as DataTablesDataTables;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;


class sellInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return View('sell_invoice');

    }
   public function indexWithRequestold(Request $request)
    {
        if ($request->ajax()) {
            $data = Invoice::with('client')->with('company')->with('store')->with('invoice_items')->get();
            // return $data;
            return FacadesDataTables::of($data)
                ->addIndexColumn()

                ->addColumn('Invoice_id', function ($row) {
                    return $row->id;
                })
                ->addColumn('date', function ($row) {
                    // $dd = explode(' ', $row->date);
                    // return $dd[0];
                    return $row->date;
                })
                ->addColumn('store_name', function ($row) {
                    return $row->store->name;
                })
                ->addColumn('client_name', function ($row) {
                    return $row->client->name;
                })
                    ->addColumn('casher', function ($row) {
                        $cashier = User::find($row->casher_id);
                        if( $cashier){
                          return  $cashier->username;
                        }else{
                            return 'old_inv';
                        }


                })

             ->addColumn('invoice_total', function ($row) {
                    // return $row->actual_price;
                    return $row->actual_price - $row->discount ;
                })
            ->addColumn('invoiceitemCount', function ($row) {
                    return count($row->invoice_items);
                })
                ->addColumn('qaydNo', function ($row) {
                    $qaydd = Qayditem::where('invoiceid',$row->id)->where('flag',1)
                    ->distinct()
                    ->pluck('qaydid');
                    $btn='';
                    foreach ($qaydd as $key => $value) {
                        $btn .= '<a target="_blank" href="/qayd/'.$value.'">'.$value.'</a>';
                        $btn .= '<br/>';
                    }
                    return $btn;
                    //  return $row->order_suppliers[0]->user->username;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="printInvoice/' . $row->id . '" target="_blank" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editPost"><i class="mdi mdi-printer p-2"></i></a>';


                    return $btn;
                })
                ->addColumn('view', function ($row) {
                    $btn = '<a href="invoice_report_data/' . $row->id . '" target="_blank" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-warning btn-sm editPost"><i class="mdi mdi-eye p-2"></i></a>';


                    return $btn;
                })
                ->rawColumns(['Invoice_id', 'date','client_name' ,'store_name','casher','invoice_total','invoiceitemCount','action','view','qaydNo'])
                ->make(true);
        }

        // $lastInvId = BuyTransaction::latest()->first()->id;
        return view('buy_invoice');
    }
    
    
    public function indexWithRequest(Request $request)
    {
        if ($request->ajax()) {
            $data = Invoice::with(['client', 'company', 'store', 'invoice_items']);

            return FacadesDataTables::of($data)
                ->addIndexColumn()

                ->filter(function ($query) use ($request) {
                    // Search by Invoice ID
                    if ($request->has('search.value')) {
                        $search = $request->input('search.value');

                        $query->where(function ($q) use ($search) {
                            $q->where('id', 'like', "%{$search}%")
                            ->orWhereHas('client', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('store', fn($q3) => $q3->where('name', 'like', "%{$search}%"))
                            ->orWhere('actual_price', 'like', "%{$search}%")
                            ->orWhere('discount', 'like', "%{$search}%");
                        });
                    }
                })

                ->addColumn('Invoice_id', fn($row) => $row->id)
                ->addColumn('date', fn($row) => $row->date)
                ->addColumn('store_name', fn($row) => $row->store->name ?? 'N/A')
                ->addColumn('client_name', fn($row) => $row->client->name ?? 'N/A')
                ->addColumn('casher', function ($row) {
                    $cashier = User::find($row->casher_id);
                    return $cashier ? $cashier->username : 'old_inv';
                })
                ->addColumn('invoice_total', fn($row) => $row->actual_price - $row->discount)
                ->addColumn('invoiceitemCount', fn($row) => count($row->invoice_items))
                ->addColumn('qaydNo', function ($row) {
                    $qaydd = Qayditem::where('invoiceid', $row->id)
                        ->where('flag', 1)
                        ->distinct()
                        ->pluck('qaydid');

                    $btn = '';
                    foreach ($qaydd as $value) {
                        $btn .= '<a target="_blank" href="/qayd/' . $value . '">' . $value . '</a><br/>';
                    }
                    return $btn;
                })
                ->addColumn('action', fn($row) =>
                    '<a href="printInvoice/' . $row->id . '" target="_blank" data-toggle="tooltip" class="btn btn-primary btn-sm"><i class="mdi mdi-printer p-2"></i></a>'
                )
                ->addColumn('view', fn($row) =>
                    '<a href="invoice_report_data/' . $row->id . '" target="_blank" data-toggle="tooltip" class="btn btn-warning btn-sm"><i class="mdi mdi-eye p-2"></i></a>'
                )
                ->rawColumns(['Invoice_id', 'date', 'client_name', 'store_name', 'casher', 'invoice_total', 'invoiceitemCount', 'action', 'view', 'qaydNo'])
                ->make(true);
        }

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
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
    
      public function equipPrepare(Request $request){
           $datax = InvoiceItem::whereIn('part_type_id',[3,4,5])->get();
           $invoiceIds = $datax->pluck('invoice_id')->toArray();
        if ($request->ajax()) {
            $data= Invoice::whereIn('id',$invoiceIds )->with('client')->with('company')->with('store')->with('invoice_items')->get();

            return FacadesDataTables::of($data)
                ->addIndexColumn()

                ->addColumn('Invoice_id', function ($row) {
                    return $row->id;
                })
                ->addColumn('date', function ($row) {
                    // $dd = explode(' ', $row->date);
                    // return $dd[0];
                    return $row->date;
                })
                ->addColumn('store_name', function ($row) {
                    return $row->store->name;
                })
                ->addColumn('client_name', function ($row) {
                    return $row->client->name;
                })
                ->addColumn('casher', function ($row) {
                        $cashier = User::find($row->casher_id);
                        if( $cashier){
                            return  $cashier->username;
                        }else{
                            return 'old_inv';
                        }


                })
                ->addColumn('invoice_total', function ($row) {
                    return $row->actual_price;
                })
                ->addColumn('type', function ($row) {
                    if(count($row->invoice_items)>0){
                         $equip_type=  Type::where('id',$row->invoice_items[0]->part_type_id)->first();
                         return $equip_type->name;
                    }

                })
                ->addColumn('qaydNo', function ($row) {
                    $qaydd = Qayditem::where('invoiceid',$row->id)->where('flag',1)
                    ->distinct()
                    ->pluck('qaydid');
                    $btn='';
                    foreach ($qaydd as $key => $value) {
                        $btn .= '<a target="_blank" href="/qayd/'.$value.'">'.$value.'</a>';
                        $btn .= '<br/>';
                    }
                    return $btn;
                    //  return $row->order_suppliers[0]->user->username;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="print_equipPaper/' . $row->id . '" target="_blank" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editPost">أوراق التسليم</a>';
                    return $btn;
                })
                ->rawColumns(['Invoice_id', 'date','client_name' ,'store_name','casher','invoice_total','type','action','qaydNo'])
                ->make(true);
        }

        // $lastInvId = BuyTransaction::latest()->first()->id;
        return view('equipPrepare');
    }
    public function print_equipPaper(Invoice $id){
        $invoice =  $id;

        $presaleFlag = 0;
       if($invoice->presale_order_id){
           $presaleFlag = 1;
       }

       $invoiceItemspart = InvoiceItem::where('invoice_id', $id->id)->where('part_type_id', '1')->with('part')->with('source')->with('status')->with('part_quality')->get();
       $invoiceItemskit = InvoiceItem::where('invoice_id', $id->id)->where('part_type_id', '6')->with('kit')->with('source')->with('status')->with('part_quality')->get();
       $invoiceItemswheel = InvoiceItem::where('invoice_id', $id->id)->where('part_type_id', '2')->with('wheel')->with('source')->with('status')->with('part_quality')->get();
       $invoiceItemstractor = InvoiceItem::where('invoice_id', $id->id)->where('part_type_id', '3')->with('tractor.series.model.brand')->with('source')->with('status')->with('part_quality')->get();
       $invoiceItemsclarks = InvoiceItem::where('invoice_id', $id->id)->where('part_type_id', '4')->with('clark.series.model.brand')->with('source')->with('status')->with('part_quality')->get();
       $invoiceItemsequips = InvoiceItem::where('invoice_id', $id->id)->where('part_type_id', '5')->with('equip.series.model.brand')->with('source')->with('status')->with('part_quality')->get();
      
       $invoiceItems = $invoiceItemspart->concat($invoiceItemskit)->concat($invoiceItemswheel)->concat($invoiceItemsclarks)->concat($invoiceItemstractor)->concat($invoiceItemsequips);
       // return $invoiceItems;
       foreach ($invoiceItems as $key => $Item) {
           # code...
            // $Item['price'] = SalePricing::where('from', '<=', $Item->date)->where(function ($q) use ($Item) {
           $Item['price'] = SalePricing::where(function ($q) use ($Item) {
               $q->where('to', '>=', $Item->date)->orWhere('to', null);
           })->where('sale_type', $Item->sale_type)->where('part_id', $Item->part_id)->where('source_id', $Item->source_id)->where('status_id', $Item->status_id)->where('quality_id', $Item->quality_id)->with('sale_type')->get();

           $allpId = 0;
          if ($Item->part_type_id == 3) {
               $allpart = AllTractor::where('part_id', $Item->part_id)->where('source_id', $Item->source_id)->where('status_id', $Item->status_id)->where('quality_id', $Item->quality_id)->orderBy('id', 'Asc')->select('id')->get();
               $allpId = $allpart;
           } elseif ($Item->part_type_id == 4) {
               $allpart = AllClark::where('part_id', $Item->part_id)->where('source_id', $Item->source_id)->where('status_id', $Item->status_id)->where('quality_id', $Item->quality_id)->orderBy('id', 'Asc')->select('id')->get();
               $allpId = $allpart;
           } elseif ($Item->part_type_id == 5) {
               $allpart = AllEquip::where('part_id', $Item->part_id)->where('source_id', $Item->source_id)->where('status_id', $Item->status_id)->where('quality_id', $Item->quality_id)->orderBy('id', 'Asc')->select('id')->get();
               $allpId = $allpart;
           }

           $Item['refund_amount'] = RefundInvoice::where('invoice_id', $id->id)->whereIn('item_id', $allpId)->sum('r_amount');
           $Item['refund_price'] = RefundInvoice::where('invoice_id', $id->id)->whereIn('item_id', $allpId)->sum('item_price');

           $Item['refund_total_tax'] = RefundInvoice::where('invoice_id', $id->id)->whereIn('item_id', $allpId)->sum('r_tax');
           $Item['refund_total_discount'] = RefundInvoice::where('invoice_id', $id->id)->whereIn('item_id', $allpId)->sum('r_discount');



           //////new adde msalam
           $Item['section'] = DB::table('store_section')->join('store_structure', 'store_structure.id', 'store_section.section_id')->where('part_id', $Item->part_id)->where('type_id',  $Item->part_type_id)->where('source_id', $Item->source_id)->where('status_id', $Item->status_id)->where('quality_id', $Item->quality_id)->select('store_structure.name')->get();
       }

       $invoice['refund_price_total'] = RefundInvoicePayment::where('invoice_id', $id->id)->sum('paied');
       $invoice['refund_price_tax_total'] = RefundInvoicePayment::where('invoice_id', $id->id)->sum('total_tax');
       $invoice['refund_price_discount_total'] = RefundInvoicePayment::where('invoice_id', $id->id)->sum('total_dicount');
       // return $invoice;
       // return Invoice::where('id',$id->id)->get();
       $paperTitle = "فاتورة بيع معتمدة";
       $recordName = "العميل";
       $recordValue = $invoice->client->name;
       $recoredId = $invoice->id;
       $recoredUrl = 'print_equipPaper/' . $recoredId;

       return View('print_equipPaper', compact('presaleFlag','invoice', 'invoiceItems', 'paperTitle', 'recordName', 'recordValue', 'recoredId', 'recoredUrl'));


    }
}
