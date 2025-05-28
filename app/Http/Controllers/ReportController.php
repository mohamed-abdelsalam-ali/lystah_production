<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Clark;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Model;
use App\Models\PartModel;
use App\Models\PartQuality;
use App\Models\PresaleOrder;
use App\Models\Source;
use App\Models\Status;
use App\Models\Store;
use App\Models\SubGroup;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Replyorder;
use App\Models\SalePricing;
use App\Models\CurrencyType;
use App\Models\Equip;
use App\Models\Kit;
use App\Models\Part;
use App\Models\OrderSupplier;
use App\Models\InvoicesTax;
use App\Models\Tractor;
use App\Models\Wheel;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
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
    public function sales_report()
    {
        // store

        $stores = Store::all();

        // product
        // customer
        $clients = Client::all();
        $sources = Source::all();
        $status_all = Status::all();
        $qualities = PartQuality::all();
        $brands = Brand::all();
        $models = Model::all();
        $sub_groups = SubGroup::all();
        $users = User::all();
        $sum_PresaleOrder = PresaleOrder::sum('total');
        $sum_Invoice_actual_price = Invoice::sum('actual_price');
        $sum_Invoice_paied = Invoice::sum('paied');
         $stores_orders = Store::withSum('invoices', 'actual_price')->orderBy('invoices_sum_actual_price', 'desc')->get();
        $invoice_order_items = InvoiceItem::with(['part', 'kit', 'wheel', 'tractor', 'equip', 'clark', 'source', 'status', 'part_quality'])
            ->selectRaw('part_id, source_id,part_type_id, status_id, quality_id, COUNT(*) as item_count')
            ->groupBy('part_id', 'source_id', 'status_id', 'quality_id', 'part_type_id')
            ->orderBy('item_count', 'desc')
            ->limit('5')->get();
        $clientWithMostInvoices = Invoice::with('client')->select('client_id', DB::raw('COUNT(id) as total_invoices'))
            ->groupBy('client_id')
            ->orderByDesc('total_invoices')->limit('5')->get();
        $invoices_order_sources = InvoiceItem::with(['part', 'kit', 'wheel', 'tractor', 'equip', 'clark', 'source', 'status', 'part_quality'])
            ->selectRaw('part_id, source_id,part_type_id, COUNT(*) as item_count')
            ->groupBy('part_id', 'source_id', 'part_type_id')
            ->orderBy('item_count', 'desc')
            ->limit('5')->get();
        $invoices_order_statuses = InvoiceItem::with(['part', 'kit', 'wheel', 'tractor', 'equip', 'clark', 'source', 'status', 'part_quality'])
            ->selectRaw('part_id, status_id,part_type_id, COUNT(*) as item_count')
            ->groupBy('part_id', 'status_id', 'part_type_id')
            ->orderBy('item_count', 'desc')
            ->limit('5')->get();
        $invoices_order_qualities = InvoiceItem::with(['part', 'kit', 'wheel', 'tractor', 'equip', 'clark', 'source', 'status', 'part_quality'])
            ->selectRaw('part_id, quality_id,part_type_id, COUNT(*) as item_count')
            ->groupBy('part_id', 'quality_id', 'part_type_id')
            ->orderBy('item_count', 'desc')
            ->limit('5')->get();
        //   $invoice_order_items[0]->part_type_id;
        // return InvoiceItem::with(['part', 'kit', 'wheel', 'tractor', 'equip', 'clark'])
        // ->selectRaw('part_id,part_type_id, COUNT(*) as item_count')
        // ->groupBy('part_id', 'part_type_id')
        // ->orderBy('item_count', 'desc')
        // ->limit('5')->get();


        // return InvoiceItem::select(
        //         'invoice_items.part_id',

        //         DB::raw('COUNT(part_model.id) as countmodels')
        //     )
        //     ->join('part', 'invoice_items.part_id', '=', 'part.id')
        //     ->join('part_model', 'part.id', '=', 'part_model.part_id')
        //     ->join('series', 'part_model.model_id', '=', 'series.id')
        //     ->join('model', 'series.model_id', '=', 'model.id')
        //     ->join('brand', 'model.brand_id', '=', 'brand.id')
        //     ->groupBy('invoice_items.part_id')
        //     ->orderByDesc('countmodels')
        //     ->with('part')
        //     ->get();

        return view('reports.sales_report', compact('invoices_order_qualities', 'invoices_order_sources', 'invoices_order_statuses', 'clientWithMostInvoices', 'invoice_order_items', 'stores_orders', 'sum_Invoice_paied', 'sum_Invoice_actual_price', 'sum_PresaleOrder', 'stores', 'clients', 'sources', 'status_all', 'qualities', 'brands', 'models', 'sub_groups', 'users'));
    }
    public function sales_report_date(Request $request)
    {
        $filterDate = $request->input('filter_date');
        $query = InvoiceItem::query();
        $today = Carbon::today();

        switch ($filterDate) {
            case '1':
                $startDate = $today->copy()->startOfYear()->format('Y-m-d');
                break;
            case '2':
                $startDate = $today->copy()->subDays(7)->format('Y-m-d');
                break;
            case '3':
                $startDate = $today->copy()->subDays(30)->format('Y-m-d');
                break;
            case '4':
                $startDate = $today->copy()->subDays(90)->format('Y-m-d');
                break;
            case '5':
                $startDate = $today->copy()->subDays(180)->format('Y-m-d');
                break;
            case '6':
                $startDate = $today->copy()->subDays(365)->format('Y-m-d');
                break;
            case '7':
                $startDate = $today->copy()->subYears(3)->format('Y-m-d');
                break;
            default:
                $startDate = null;
                break;
        }



        $sum_PresaleOrder = PresaleOrder::where('created_at', '>=', $startDate)->sum('total');
        $sum_Invoice_actual_price = Invoice::where('date', '>=', $startDate)->sum('actual_price');
        $sum_Invoice_paied = Invoice::where('date', '>=', $startDate)->sum('paied');
        $stores_orders = Store::withSum(['invoices' => function ($query) use ($startDate) {
            if ($startDate) {
                $query->where('date', '>=', $startDate);
            }
        }], 'actual_price')
            ->orderBy('invoices_sum_actual_price', 'desc')
            ->get();
        $invoice_order_items = InvoiceItem::where('date', '>=', $startDate)->with(['part', 'kit', 'wheel', 'tractor', 'equip', 'clark', 'source', 'status', 'part_quality'])
            ->selectRaw('part_id, source_id,part_type_id, status_id, quality_id, COUNT(*) as item_count')
            ->groupBy('part_id', 'source_id', 'status_id', 'quality_id', 'part_type_id')
            ->orderBy('item_count', 'desc')
            ->limit('5')->get();
        $clientWithMostInvoices = Invoice::where('date', '>=', $startDate)->with('client')->select('client_id', DB::raw('COUNT(id) as total_invoices'))
            ->groupBy('client_id')
            ->orderByDesc('total_invoices')->limit('5')->get();
        $invoices_order_sources = InvoiceItem::where('date', '>=', $startDate)->with(['part', 'kit', 'wheel', 'tractor', 'equip', 'clark', 'source', 'status', 'part_quality'])
            ->selectRaw('part_id, source_id,part_type_id, COUNT(*) as item_count')
            ->groupBy('part_id', 'source_id', 'part_type_id')
            ->orderBy('item_count', 'desc')
            ->limit('5')->get();
        $invoices_order_statuses = InvoiceItem::where('date', '>=', $startDate)->with(['part', 'kit', 'wheel', 'tractor', 'equip', 'clark', 'source', 'status', 'part_quality'])
            ->selectRaw('part_id, status_id,part_type_id, COUNT(*) as item_count')
            ->groupBy('part_id', 'status_id', 'part_type_id')
            ->orderBy('item_count', 'desc')
            ->limit('5')->get();
        $invoices_order_qualities = InvoiceItem::where('date', '>=', $startDate)->with(['part', 'kit', 'wheel', 'tractor', 'equip', 'clark', 'source', 'status', 'part_quality'])
            ->selectRaw('part_id, quality_id,part_type_id, COUNT(*) as item_count')
            ->groupBy('part_id', 'quality_id', 'part_type_id')
            ->orderBy('item_count', 'desc')
            ->limit('5')->get();

        return response()->json([
            'stores_orders' => $stores_orders,
            'sum_PresaleOrder' => $sum_PresaleOrder,
            'sum_Invoice_actual_price' => $sum_Invoice_actual_price,
            'invoices_order_qualities' => $invoices_order_qualities,
            'invoices_order_statuses' => $invoices_order_statuses,
            'invoices_order_sources' => $invoices_order_sources,
            'clientWithMostInvoices' => $clientWithMostInvoices,
            'invoice_order_items' => $invoice_order_items,
            'sum_Invoice_paied' => $sum_Invoice_paied,
        ]);
    }
     public function invoice_report_data($inv_number)
    {
           $invoice = Invoice::where('id', $inv_number)->with(['client', 'store', 'invoice_items', 'refund_invoices'])
            ->first();
        foreach ($invoice->invoice_items as $key => $Item) {
            $buy_inv_price = 0;
            if(count($Item->invoice_item_order_suppliers)>0){
                 $buy_inv_price = Replyorder::where('part_id', $Item->part_id)->where('order_supplier_id', $Item->invoice_item_order_suppliers[0]->order_supplier_id)
                ->where('source_id', $Item->source_id)
                ->where('status_id', $Item->status_id)
                ->where('quality_id', $Item->quality_id)
                ->where('part_type_id', $Item->part_type_id)
                ->first('price');
            }else{
                 $buy_inv_price = Replyorder::where('part_id', $Item->part_id)
                ->where('source_id', $Item->source_id)
                ->where('status_id', $Item->status_id)
                ->where('quality_id', $Item->quality_id)
                ->where('part_type_id', $Item->part_type_id)
                ->first('price');
            }
            // $price = SalePricing::where('from', '<=', $Item->date)
            $price = SalePricing::where(function ($q) use ($Item) {
                    $q->where('to', '>=', $Item->date)->orWhere('to', null);
                })
                ->where('sale_type', $Item->sale_type)
                ->where('part_id', $Item->part_id)
                ->where('source_id', $Item->source_id)
                ->where('status_id', $Item->status_id)
                ->where('quality_id', $Item->quality_id)
                ->first('price');
            $Item->price = $price;
            $Item->buy_inv_price = $buy_inv_price;


            



            foreach ($Item->invoice_item_order_suppliers as $key => $price_buy) {
                $price_buy['currency_value'] =  CurrencyType::with([
                    'currencies' => function ($query) use ($price_buy) {
                        return $query->where('from', '>=', $price_buy->order_supplier->confirmation_date)->where('to', '<=', $price_buy->order_supplier->confirmation_date)->where('currency_id', $price_buy->order_supplier->currency_id)->orWhere('to', '=', null);
                    },
                ])->where('id', $price_buy->order_supplier->currency_id)->first();
            }


        }

        foreach ($invoice->refund_invoices as $key => $Item) {
                $Item['data']=InvoiceItem::where('id',$Item->item_id)->with(['source','status','part_quality'])->first();
                   if($Item['data']->part_type_id == 1){
                        $Item['data']->part = Part::find($Item['data']->part_id);
                    }elseif($Item['data']->part_type_id == 2){
                        $Item['data']->part = Wheel::find($Item['data']->part_id);
                    }elseif($Item['data']->part_type_id == 3){
                        $Item['data']->part = Tractor::find($Item['data']->part_id);
                    }elseif($Item['data']->part_type_id == 4){
                        $Item['data']->part = Clark::find($Item['data']->part_id);
                    }elseif($Item['data']->part_type_id == 5){
                        $Item['data']->part = Equip::find($Item['data']->part_id);
                    }elseif($Item['data']->part_type_id == 6){
                        $Item['data']->part = Kit::find($Item['data']->part_id);
                    }

        }
          

          $invoice;
        return view('invoice_report_data', compact('inv_number', 'invoice'));
    }
    
    public function taxReport()
    {
        $taxes = DB::table('taxes')->get();
        return view('taxReport', compact('taxes'));
    }

    public function GettaxesReport(Request $request)
    {
        $from = $request->input('from_date');
        $to = $request->input('to_date');
        $tax_id = $request->input('tax_type');
        $inv_type = $request->input('inv_type');



        if ($inv_type == 1) {
            // OrderSupplier (handle your logic here if needed)


            $first = OrderSupplier::select([
                'order_supplier.*',
                'supplier.name as supplier_name',
                'buy_transaction.id as invoice_id',
                DB::raw("'غير شامل ضريبة القيمة المضافة' as tax_name"),
                DB::raw('14 as tax_value'),
                DB::raw('(pricebeforeTax * 14 / 100) as tax_amount')
            ])
            ->leftJoin('supplier', 'supplier.id', '=', 'order_supplier.supplier_id')
            ->leftJoin('buy_transaction', 'buy_transaction.id', '=', 'order_supplier.transaction_id')
            ->where('taxInvolved_flag', 1);
            
            $second = OrderSupplier::select([
                'order_supplier.*',
                'supplier.name as supplier_name',
                'buy_transaction.id as invoice_id',
                DB::raw("'ضريبة خصم أرباح تجارية وصناعية' as tax_name"),
                DB::raw('-1 as tax_value'),
                DB::raw('(pricebeforeTax * -1 / 100) as tax_amount')
            ])
            ->leftJoin('supplier', 'supplier.id', '=', 'order_supplier.supplier_id')
            ->leftJoin('buy_transaction', 'buy_transaction.id', '=', 'order_supplier.transaction_id')
            ->where('taxkasmInvolved_flag', 1);

            // Union them
            $query = $first->unionAll($second);
            
            if ($from && $to) {
                $query = DB::query()->fromSub($query, 'order_supplier_union')
                         ->whereBetween('confirmation_date', [$from, $to]);
            }elseif($from){
                $query = DB::query()->fromSub($query, 'order_supplier_union')
                         ->whereDate('confirmation_date','>=', $from);
            }elseif($to){
                $query = DB::query()->fromSub($query, 'order_supplier_union')
                         ->whereDate('confirmation_date','<=', $to);
            } else {
                $query = DB::query()->fromSub($query, 'order_supplier_union');
            }

            
            return DataTables::of($query)
            ->addColumn('invoice_number', function ($row) {
                return $row->invoice_id ?? '-';
            })
            ->addColumn('type_name', function ($row) {
                return 'فاترة شراء';
            })
            ->addColumn('invoice_date', function ($row) {
                return $row->confirmation_date;
            })
            ->addColumn('tax_name', function ($row) {
                return $row->tax_name;
            })
            ->addColumn('tax_value', function ($row) {
                return $row->tax_value;
            })
            ->addColumn('tax_amount', function ($row) {
                return round($row->tax_amount);
            })
            ->addColumn('total_invoice', function ($row) {
                return $row->total_price;
            })
            ->addColumn('customer_name', function ($row) {
                return $row->supplier_name ?? '-';
            })
            ->addColumn('action', function ($row) {
               
                    return '<a href="printBuyInvoice/'.$row->invoice_id.'" target="blank" class="btn btn-sm btn-primary">View</a>';
                
            })
            ->rawColumns(['action']) 
            ->make(true);
        } elseif ($inv_type == 2) {
            // invoice
            $query = InvoicesTax::query()->with(['invoice', 'tax']);
            if ($from && $to && $tax_id) {
                $query->whereHas('invoice', function ($q) use ($from, $to) {
                    $q->whereBetween('date', [$from, $to]);
                })
                    ->where('tax_id', $tax_id);
            } else {
                if ($from && $to) {
                   
                }
                if ($from && $to) {
                    $query->whereHas('invoice', function ($q) use ($from, $to) {
                        $q->whereBetween('date', [$from, $to]);
                    });
                }elseif($from){
                    $query->whereHas('invoice', function ($q) use ($from, $to) {
                        $q->whereDate('date','>=',$from);
                    });
                }elseif($to){
                    $query->whereHas('invoice', function ($q) use ($from, $to) {
                        $q->whereDate('date','<=',$to);
                    });
                } 

                
                if ($tax_id) {
                    $query->where('tax_id', $tax_id);
                }
            }

            return DataTables::of($query)
                ->addColumn('invoice_number', function ($row) {
                    return $row->invoice->id;
                })
                ->addColumn('type_name', function ($row) {
                    return 'فاتورة بيع';
                })
                ->addColumn('invoice_date', function ($row) {
                    return $row->invoice->date;
                })
                ->addColumn('tax_name', function ($row) {
                    return optional($row->tax)->name;
                })
                ->addColumn('tax_value', function ($row) {
                    return optional($row->tax)->value;
                })
                ->addColumn('tax_amount', function ($row) {
                    return $row->tax->value * $row->invoice->price_without_tax / 100 ;
                })
                ->addColumn('total_invoice', function ($row) {
                    return $row->invoice->price_without_tax;
                })
                ->addColumn('customer_name', function ($row) {
                    return optional($row->invoice->client)->name;
                })
                ->addColumn('action', function ($row) {
                    return '<a href="printInvoice/'.$row->invoice->id.'" target="blank" class="btn btn-sm btn-primary">View</a>';
                })
                ->rawColumns(['action']) // Allow HTML in 'action'
                ->make(true);
        } else {
            // all
            $orderFirst = OrderSupplier::select([
                'order_supplier.transaction_id as id',
                'order_supplier.confirmation_date as date',
                'supplier.name as customer_name',
                DB::raw("'غير شامل ضريبة القيمة المضافة' as tax_name"),
                DB::raw("'فاتررة شراء' as type_name"),
                DB::raw("1 as type_id"),
                DB::raw('14 as tax_value'),
                DB::raw('(pricebeforeTax * 14 / 100) as tax_amount'),
                'total_price as total_invoice'
            ])
            ->leftJoin('supplier', 'supplier.id', '=', 'order_supplier.supplier_id')
            ->where('taxInvolved_flag', 1);
        
            $orderSecond = OrderSupplier::select([
                'order_supplier.transaction_id as id',
                'order_supplier.confirmation_date as date',
                'supplier.name as customer_name',
               
                DB::raw("'ضريبة خصم أرباح تجارية وصناعية' as tax_name"),
                DB::raw("'فاتررة شراء' as type_name"),
                DB::raw("1 as type_id"),
                DB::raw('-1 as tax_value'),
                DB::raw('(pricebeforeTax * -1 / 100) as tax_amount'),
                'total_price as total_invoice'
            ])
            ->leftJoin('supplier', 'supplier.id', '=', 'order_supplier.supplier_id')
            ->where('taxkasmInvolved_flag', 1);
        
            $orderUnion = $orderFirst->unionAll($orderSecond);
        
            // Invoice separately
            $invoices = InvoicesTax::select([
                'invoice.id as id',
                'invoice.date as date',
                'clients.name as customer_name',
                'taxes.name as tax_name',
                DB::raw("'فاتررة بيع' as type_name"),
                DB::raw("2 as type_id"),
                'taxes.value as tax_value',
                DB::raw('(invoice.price_without_tax * taxes.value / 100) as tax_amount'),
                'invoice.price_without_tax as total_invoice',
                
            ])
            ->join('invoice', 'invoice.id', '=', 'invoices_tax.invoice_id')
            ->join('clients', 'clients.id', '=', 'invoice.client_id')
            ->join('taxes', 'taxes.id', '=', 'invoices_tax.tax_id');
            
            if ($tax_id) {
                $invoices->where('taxes.id', $tax_id);
            }
            // Now combine both orders and invoices
            $query = DB::query()->fromSub(
                $orderUnion->unionAll($invoices),
                'combined_results'
            );
        
            
            // Apply date filter if provided
            if ($from && $to) {
                $query->whereBetween('date', [$from, $to]);
            }elseif($from){
                $query->whereDate('date','>=',$from);
            }elseif($to){
                $query->whereDate('date','<=',$to);
            }
        
            // Return DataTable
            return DataTables::of($query)
                ->addColumn('invoice_number', function ($row) {
                    return $row->id;
                })
                ->addColumn('invoice_date', function ($row) {
                    return $row->date;
                })
                ->addColumn('type_name', function ($row) {
                    return $row->type_name;
                })
                ->addColumn('tax_name', function ($row) {
                    return $row->tax_name;
                })
                ->addColumn('tax_value', function ($row) {
                    return $row->tax_value;
                })
                ->addColumn('tax_amount', function ($row) {
                    return round($row->tax_amount);
                })
                ->addColumn('total_invoice', function ($row) {
                    return $row->total_invoice;
                })
                ->addColumn('customer_name', function ($row) {
                    return $row->customer_name ?? '-';
                })
                ->addColumn('action', function ($row) {
                    if($row->type_id == 2){
                        return '<a href="printInvoice/'.$row->id.'" target="blank" class="btn btn-sm btn-primary">View</a>';
                    }else{
                        return '<a href="printBuyInvoice/'.$row->id.'" target="blank" class="btn btn-sm btn-primary">View</a>';
                    }
                    
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
