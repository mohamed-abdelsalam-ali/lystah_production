<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use App\Models\CoaType;
use App\Models\CurrencyType;
use App\Models\Journal;
use App\Models\JournalType;
use App\Models\JournalDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\Newqayd;
use App\Models\NQayd;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\SalePricing;
use App\Models\OrderSupplier;
use App\Models\Supplier;


class coaController extends Controller
{
    public function coa()
    {
        $coa = Coa::all();
        $coa_types = CoaType::all();
        $curency = CurrencyType::all();
        return View('coa.coa', compact('coa', 'coa_types', 'curency'));
    }

    public function addcoa(Request $request)
    {
        // Validate incoming data
        // return $request;
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'ac_number' => 'required|string|max:255',
            'type_id' => 'required|integer',
            'reconciliation' => 'nullable|boolean',
            'account_currency' => 'nullable|integer|exists:currency_type,id',
        ]);

        // Handle the reconciliation checkbox (defaults to false if unchecked)
        $reconciliation = $request->has('reconciliation') ? 'true' : 'false'; // true if checkbox is checked, false if not
        $account_currency = $request->input('account_currency') !== null ? (int) $request->input('account_currency') : null;

        // Create a new row in the database
        $Coa = Coa::create([
            'name_ar' => $validated['name_ar'],
            'name_en' => $validated['name_en'],
            'ac_number' => $validated['ac_number'],
            'type_id' => $validated['type_id'],
            'reconciliation' => $reconciliation,
            'account_currency' => $account_currency,
        ]);

        // return  $Coa ;
        // Redirect back with a success message
        return redirect()->route('coa.index')->with('success', 'New row added successfully!');
    }

    public function getcoa($id){
        return $coa = Coa::findOrFail($id);
    }
    public function updateCoa(Request $request)
    {
        // return $request;
        // Validate incoming data
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'ac_number' => 'required|string|max:255',
            'type_id' => 'required|integer',
            // 'reconciliation' => 'nullable|boolean',
            'account_currency' => 'nullable|integer|exists:currency_type,id', // Ensure it exists in the related table
        ]);


        // Find the existing record by its ID
        $coa = Coa::findOrFail($request->e_id);

        // Update the record with the new data
        $coa->name_ar = $validated['name_ar'];
        $coa->name_en = $validated['name_en'];
        $coa->ac_number = $validated['ac_number'];
        $coa->type_id = $validated['type_id'];
        $coa->reconciliation = $request->has('reconciliation') ? 'true' : 'false'; // 'true' if checked, 'false' if not
        $coa->account_currency = $validated['account_currency'] !== null ? (int) $validated['account_currency'] : null;

        // Save the updated record
        $coa->save();

        // Redirect back to the index page with a success message
        return redirect()->route('coa.index')->with('success', 'Row updated successfully!');
    }
    
     public function deleteCoa($id)
    {
        try {
            
            $coa = Coa::findOrFail($id); // Find the record
            $inQayds=Newqayd::where('coa_id',$id)->first();
            if($inQayds){
                 return response()->json([
                    'message' => 'Cant Delete This found in Qayds!',
                ], 500);
            }
            $coa->delete(); // Delete the record
    
            return response()->json([
                'message' => 'COA deleted successfully!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete COA. It may not exist.',
            ], 500);
        }
    }
    
    
    public function journal(){
        $journals = Journal::all();
        return View('coa.journal', compact('journals'));
        
    }
    
    public function newjournal(){
        $coa = Coa::all();
        $journals = Journal::all();
         $journalType = JournalType::all();
        return View('coa.newjournal', compact('coa','journals','journalType'));
    }
    
    public function storejournal(Request $request){
        // return $request;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer',
            'journal_type' => 'required|integer|exists:journal_type,id',
            'notes' => 'nullable|string',
           
        ]);

        $journal = Journal::create([
            'name' => $validated['name'],
            'year' => $validated['year'],
            'journal_type' => $validated['journal_type'],
            'notes' => $validated['notes'],
        ]);

        
        if(isset($request->account_value)){
            foreach ($request->account_value as $index => $name) {
                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'note' => $request->account_notes[$index] ?? null,
                    'name' => $request->account_name[$index] ?? null, 
                    'value' => $request->account_value[$index] ?? null,
                    'user_id' => auth()->user()->id,
                    'details_header' => 'Accounts',
                    'is_default' => $request->account_default[$index],
                    
                ]);
    
               
    
            }
        }
       

        if(isset($request->income_value)){
            foreach ($request->income_value as $index => $name) {
                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'note' => $request->income_notes[$index] ?? null,
                    'name' => $request->income_name[$index] ?? null, 
                    'value' => $request->income_value[$index] ?? null,
                    'user_id' => auth()->user()->id,
                    'details_header' => 'Incoming Payment',
                    'is_default' => $request->income_default[$index],
                    
                ]);

            

            }
        }
        if(isset($request->outgo_value)){
            foreach ($request->outgo_value as $index => $name) {
                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'note' => $request->outgo_notes[$index] ?? null,
                    'name' => $request->outgo_name[$index] ?? null, 
                    'value' => $request->outgo_value[$index] ?? null,
                    'user_id' => auth()->user()->id,
                    'details_header' => 'Outgoing Payment',
                    'is_default' => $request->outgo_default[$index],
                    
                ]);

            

            }
        }

        return redirect()->route('coa.journal')->with('success', 'Row Added successfully!');

    }
    
      public function showjournal($journalId)
    {
        
        $journal = Journal::with('jornaldetails')->with('jornaltype')->where('id', $journalId)->first();
         $groupedJournalDetails = $journal->jornaldetails->groupBy('details_header');
       
        if (!$journal) {
            return redirect()->route('coa.journal')->with('error', 'No journal details found.');
        }

        // return $journal;
        return view('coa.showjournal', compact('journal','groupedJournalDetails'));
    }
    
     public function deletejournal($journalId)
    {
        
        try {
            $journal = Journal::find($journalId);

            $inQayds=Newqayd::where('coa_id',$journalId)->first();
            if($inQayds){
                 return response()->json([
                    'message' => 'Cant Delete This found in Qayds!',
                ], 500);
            }
        if (!$journal) {
            return response()->json([
                'message' => 'Journal Not Found .',
            ], 500);
        }

        // Delete the related journal details first (if any)
        $journal->jornaldetails()->delete();

        // Then, delete the journal itself
        $journal->delete();
    
            return response()->json([
                'message' => 'Journal deleted successfully!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Journal. It may not exist.',
            ], 500);
        }

       
    }

    public function allQayds(){
         $allQayds = NQayd::with('newqayds')->get();
        return View('coa.allQayds', compact('allQayds'));
    }

    public function showqayds($id){
        $qayd =  NQayd::where('id',$id)->with('newqayds.coa')->with('newqayds.journal')->with('newqayds.currency_type')->first();
        if(!$qayd){
            return 'Not Found';
        }
        if($qayd->type == 'Client' && $qayd->invoice_id != null){
            $qayd->invoiceData = Invoice::where('id',$qayd->invoice_id)
            ->with('client')
            ->with('invoice_items')
            ->with('refund_invoices')
            ->with('taxes')->first();

            if($qayd->invoiceData){
                if($qayd->invoiceData->invoice_items){
                    foreach ($qayd->invoiceData->invoice_items as $key => $inv_item) {
                        $inv_item['pricing']  = SalePricing::where('from','<=',$inv_item->date)->where(function ($q) use($inv_item) {
                            $q->where('to','>=',$inv_item->date)->orWhere('to', null);
                        })->where('sale_type',$inv_item->sale_type)->where('part_id',$inv_item->part_id)
                        ->where('source_id',$inv_item->source_id)->where('status_id',$inv_item->status_id)
                        ->where('quality_id',$inv_item->quality_id)->with('sale_type')->get();
                    }
                }
            }
            $qayd->partner = Client::findOrfail($qayd->partner_id);
        }
        elseif($qayd->type == 'Supplier' && $qayd->invoice_id != null){
            $qayd->invoiceData = OrderSupplier::where('transaction_id',$qayd->invoice_id)
            ->with('supplier')
            ->with('replyorders.part')
            ->with('replyorders.wheel')
            ->with('replyorders.kit')
            // ->with('refund_invoices')
            ->with('currency_type')
            ->first();

            
            $qayd->partner = Supplier::findOrfail($qayd->partner_id);
        }
        if($qayd){
            $groupedNewQayds = $qayd->newqayds->groupBy('journal_id');
        }
        if($qayd){
            $groupedNewQaydss = $qayd->newqayds->groupBy('show_no')->sortKeys();
        }
        return View('coa.showqayds', compact('qayd','groupedNewQayds','groupedNewQaydss'));
        return $qayd;
    }
    
     public function getJournalData($journal_id){
       $allQayds = Newqayd::with('coa')
        ->with('journal')->with('currency_type')
        ->with('client')->with('supplier')
        ->with('qayd')->orderBy('created_at','DESC')
        ->where('journal_id', $journal_id)
        ->get();

        return View('coa.JournalData',compact('allQayds'));

    }
    public function GeneralLedger(){
        $allQayds = Newqayd::with('coa')
        ->with('journal')->with('currency_type')
        ->with('client')->with('supplier')
        ->with('qayd')->orderBy('created_at','DESC')
        ->get();

        return View('coa.GeneralLedger',compact('allQayds'));

    }
    
    public function BalanceSheet(){

        $allCoaType = CoaType::with('coa.qayds.qayd')->orderBy('id','ASC')->get()->map(function ($coaType) {
            // Sum credit and debit for CoaType
            $coaType->total_credit = $coaType->coa->flatMap->qayds->sum('credit');
            $coaType->total_debit = $coaType->coa->flatMap->qayds->sum('debit');
        
            // Sum credit and debit for each Coa inside CoaType
            $coaType->coa = $coaType->coa->map(function ($coa) {
                $coa->total_credit = $coa->qayds->sum('credit');
                $coa->total_debit = $coa->qayds->sum('debit');
                return $coa;
            });
            return $coaType;
        });

        return View('coa.BalanceSheet',compact('allCoaType'));

        
    }
    
    
}