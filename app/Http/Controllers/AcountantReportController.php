<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewQayd; // your model
use App\Models\Coa;     // your chart of accounts model
use Illuminate\Support\Facades\DB;

class AcountantReportController extends Controller
{
    public function cashflow()
    {
        // Cash Flow Summary
        $cashSummary = DB::table('newqayd as n')
            ->join('coa as c', 'n.coa_id', '=', 'c.id')
            ->selectRaw('
                SUM(CASE WHEN n.debit > 0 THEN n.debit ELSE 0 END) AS total_cash_in,
                SUM(CASE WHEN n.credit > 0 THEN n.credit ELSE 0 END) AS total_cash_out,
                (SUM(n.debit) - SUM(n.credit)) AS net_cash
            ')
            ->where('c.type_id', 1) // Cash/Bank accounts
            ->whereNull('n.deleted_at')
            ->first();

        // All Cash Transactions
        $transactions = DB::table('newqayd as n')
            ->join('coa as c', 'n.coa_id', '=', 'c.id')
            ->where('c.type_id', 1)
            ->whereNull('n.deleted_at')
            ->orderBy('n.created_at', 'desc')
            ->select('n.*', 'c.name_en', 'c.ac_number')
            ->get();

        return view('accountant.cashflow', compact('cashSummary', 'transactions'));
    }

    public function payable()
    {
        // Accounts Payable
        $payables = DB::table('newqayd as n')
            ->join('coa as c', 'n.coa_id', '=', 'c.id')
            ->select('n.partner_id', DB::raw('SUM(n.credit - n.debit) as balance'))
            ->where('c.type_id', 14) // 14 = Payable type
            ->whereNull('n.deleted_at')
            ->groupBy('n.partner_id')
            ->having('balance', '>', 0)
            ->get();

        return view('accountant.payable', compact('payables'));
    }

    public function receivable()
    {
        // Accounts Receivable
        $receivables = DB::table('newqayd as n')
            ->join('coa as c', 'n.coa_id', '=', 'c.id')
            ->select('n.partner_id', DB::raw('SUM(n.debit - n.credit) as balance'))
            ->where('c.type_id', 5) // 5 = Receivable type
            ->whereNull('n.deleted_at')
            ->groupBy('n.partner_id')
            ->having('balance', '>', 0)
            ->get();

        return view('accountant.receivable', compact('receivables'));
    }


    public function trial()
    {
        // Trial Balance
        $trialBalance = DB::table('newqayd as n')
            ->join('coa as c', 'n.coa_id', '=', 'c.id')
            ->select(
                'c.ac_number',
                'c.name_en',
                'c.name_ar',
                DB::raw('SUM(n.debit) as total_debit'),
                DB::raw('SUM(n.credit) as total_credit'),
                DB::raw('(SUM(n.debit) - SUM(n.credit)) as balance')
            )
            ->whereNull('n.deleted_at')
            ->groupBy('c.ac_number', 'c.name_en', 'c.name_ar')
            ->orderBy('c.ac_number')
            ->get();

        return view('accountant.trial', compact('trialBalance'));
    }
}