<?php

namespace App\Http\Controllers;

use App\Models\BankSafeMoney;
use App\Models\BankType;
use App\Models\CurrencyType;
use App\Models\MoneySafe;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\QaydController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BankSafeMoneyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $safeMoney = BankSafeMoney::with('user')->with('store')->with('currency')->with('bank_type')->get();
        // return $safeMoney;
        $all_curencys = CurrencyType::all();
        $all_stores = Store::all();
        $bank_types = BankType::all();

        $current_balance_1 = $safeMoney->where('type_money', 1)->sum('money');
        $current_balance_2 = $safeMoney->where('type_money', 0)->sum('money');
        $current_balance = $current_balance_2 - $current_balance_1;
        $bank_name = "جميع البنوك";
        return view('money_safe.bank_safe_money', compact('bank_types', 'safeMoney', 'current_balance', 'all_stores', 'all_curencys', 'bank_name'));
    }
    public function add_money_bank(Request $request)
    {
        // return $request;
        $currency_id = $request->currency_id;

        $all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($currency_id) {
            return $query->where('to', null)->where('currency_id', $currency_id);
        }])->where('id', $currency_id)->get();

        $total = BankSafeMoney::where('bank_type_id',$request->bank_type_id)->orderBy('id','DESC')->first();
        if (isset($total)) {
            $bank_safe = BankSafeMoney::create([
                'notes' => $request->notes,
                'date' => date('Y-m-d'),
                'flag' => 1,
                'money' => $request->money *  $all_currency_types[0]->currencies[0]->value,
                'total' => $total->total + ($request->money *  $all_currency_types[0]->currencies[0]->value),
                'type_money' => '0',
                'user_id' => Auth::user()->id,
                'store_id' => null,
                'money_currency' => $request->money,
                'currency_id' => $currency_id,
                'bank_type_id' => $request->bank_type_id
            ]);
        } else {
            $bank_safe = BankSafeMoney::create([
                'notes' => $request->notes,
                'date' => date('Y-m-d'),
                'flag' => 1,
                'money' => $request->money *  $all_currency_types[0]->currencies[0]->value,
                'total' => $request->money *  $all_currency_types[0]->currencies[0]->value,
                'type_money' => '0',
                'user_id' => Auth::user()->id,
                'store_id' => null,
                'money_currency' => $request->money,
                'currency_id' => $currency_id,
                'bank_type_id' => $request->bank_type_id



            ]);
        }
        if ($request->file('img_path')) {
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_user = $time . '-'  . $request->img_path->getClientOriginalName();
            $request->img_path->move(public_path('notes_safe_money'), $image_user);
            $bank_safe->update([
                'img_path' => $image_user

            ]);
        }

        // /******************************* QAYD ************************************
        //     $quaditems = [];
        //     $automaicQayd = new QaydController();

        //     array_push ( $quaditems , (object) [ 'acountant_id'=>$Ac_From_store->safe_accountant_number  , 'madin'=> 0 , 'dayin'=> $request->money ] ); // الخزنة دائن
        // array_push ( $quaditems , (object) [ 'acountant_id'=>$Ac_To_store->safe_accountant_number  , 'dayin'=> 0 , 'madin'=> $request->money ] ); // المخزن مدين

        //     $date =Carbon::now();
        //     $type = null;
        //     $notes='حركة نقدية '. $request->notes;
        //     $automaicQayd->AutomaticQayd($quaditems,$date,$type,$notes);
        /////////////////////////////////////////////////////////////////////////


        session()->flash("success", "تم أضافة المبلغ للخزنة بنجاح");
        return redirect()->back();
    }
    public function add_other_bank(Request $request)
    {
        $currency_id = $request->currency_id;

        $all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($currency_id) {
            return $query->where('to', null)->where('currency_id', $currency_id);
        }])->where('id', $currency_id)->get();
        $total = BankSafeMoney::where('bank_type_id',$request->bank_type_id)->orderBy('id','DESC')->first();
        if (isset($total)) {
            if ($total->total >= ($request->money * $all_currency_types[0]->currencies[0]->value)) {
                $bank_safe = BankSafeMoney::create([
                    'notes' => $request->notes,
                    'date' => date('Y-m-d'),
                    'flag' => 2,
                    'money' => $request->money * $all_currency_types[0]->currencies[0]->value,
                    'total' => $total->total - ($request->money * $all_currency_types[0]->currencies[0]->value),
                    'type_money' => '1',
                    'user_id' => Auth::user()->id,
                    'store_id' => null,
                    'money_currency' => $request->money,
                    'currency_id' => $currency_id,
                    'bank_type_id' => $request->bank_type_id
                ]);
                if ($request->file('img_path')) {
                    $var = date_create();
                    $time = date_format($var, 'YmdHis');
                    $image_user = $time . '-'  . $request->img_path->getClientOriginalName();
                    $request->img_path->move(public_path('notes_safe_money'), $image_user);
                    $bank_safe->update([
                        'img_path' => $image_user

                    ]);
                }
                session()->flash("success", "تم صرف المبلغ  بنجاح");
                return redirect()->back();
            } else {
                session()->flash("success", "  المبلغ غير كافي في الخزنة");
                return redirect()->back();
            }
        } else {

            session()->flash("success", "   لا يوجد رصيد في الخزنة   ");
            return redirect()->back();
        }
    }
    public function show_safe_bank_Money_month(Request $request)
    {
        // return $request;
        $current_balance = 0;
        if (isset($request->month) && isset($request->bank_type_id)) {
            $bank = BankType::where('id', $request->bank_type_id)->first();
            $bank_name = $bank->bank_name;
            $safeMoney = BankSafeMoney::whereDate('date', $request->month)->where('bank_type_id', $request->bank_type_id)->with('user')->with('bank_type')->with('store')->with('currency')->get();

            if($safeMoney){
                $current_balance = $safeMoney->last()->total;
            }else{
                $current_balance = 0;
            }
        } elseif (isset($request->bank_type_id)) {

            $bank = BankType::where('id', $request->bank_type_id)->first();
            $bank_name = $bank->bank_name;

            $safeMoney = BankSafeMoney::where('bank_type_id', $request->bank_type_id)->with('user')->with('store')->with('bank_type')->with('currency')->get();
            
            if($safeMoney){
                 if($safeMoney->last()){
                    $current_balance = $safeMoney->last()->total;
                }else{
                    $current_balance = 0;
                }
            }else{
                $current_balance = 0;
            }
        } elseif (isset($request->month)) {
            $safeMoney = BankSafeMoney::whereDate('date', $request->month)->with('user')->with('store')->with('bank_type')->with('currency')->get();
            $bank_name = "جميع البنوك";
            
            $current_balance_1 = $safeMoney->where('type_money', 1)->sum('money');
            $current_balance_2 = $safeMoney->where('type_money', 0)->sum('money');
            $current_balance = $current_balance_2 - $current_balance_1;

       
        }
        
        
        return compact('safeMoney', 'current_balance', 'bank_name');
    }
     public function money_bank_send_store(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {

            $currency_id = $request->currency_id;
            $all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($currency_id) {
                return $query->where('to', null)->where('currency_id', $currency_id);
            }])->where('id', $currency_id)->get();

            $bank_safeMoney = BankSafeMoney::where('bank_type_id', $request->bank_type_id)->orderBy('id','DESC')->first();
            $selectedBank = BankType::where('id', $request->bank_type_id)->first();
            if (isset($bank_safeMoney)) {
                if ($bank_safeMoney->total >= ($request->money * $all_currency_types[0]->currencies[0]->value)) {
                    $bank_safe = BankSafeMoney::create([
                        'notes' => $request->notes,
                        'date' => date('Y-m-d'),
                        'flag' => 3,
                        'money' => $request->money * $all_currency_types[0]->currencies[0]->value,
                        'total' => $bank_safeMoney->total - ($request->money * $all_currency_types[0]->currencies[0]->value),
                        'type_money' => '1',
                        'user_id' => Auth::user()->id,
                        'store_id' => null,
                        'money_currency' => $request->money,
                        'currency_id' => $currency_id,
                        'bank_type_id' => $request->bank_type_id,
                    ]);
                    $to_bank = BankType::where('accountant_number', $request->to_store)->first();
                    if (isset($to_bank)) {
                        $to_bank_safeMoney = BankSafeMoney::where('bank_type_id', $to_bank->id)->orderBy('id','DESC')->first();
                        if (isset($to_bank_safeMoney->total)) {
                            $safe = BankSafeMoney::create([
                                'notes' => $request->notes,
                                'date' => date('Y-m-d'),
                                'flag' => 1,
                                'money' => $request->money * $all_currency_types[0]->currencies[0]->value,
                                'total' => $to_bank_safeMoney->total + ($request->money * $all_currency_types[0]->currencies[0]->value),
                                'type_money' => '0',
                                'user_id' => Auth::user()->id,
                                'store_id' => null,
                                'money_currency' => $request->money,
                                'currency_id' => $currency_id,
                                'bank_type_id' => $to_bank->id,
                            ]);


                        } else {
                            $safe = BankSafeMoney::create([
                                'notes' => $request->notes,
                                'date' => date('Y-m-d'),
                                'flag' => 1,
                                'money' => $request->money * $all_currency_types[0]->currencies[0]->value,
                                'total' => ($request->money * $all_currency_types[0]->currencies[0]->value),
                                'type_money' => '0',
                                'user_id' => Auth::user()->id,
                                'store_id' => null,
                                'money_currency' => $request->money,
                                'currency_id' => $currency_id,
                                'bank_type_id' => $to_bank->id,
                            ]);
                        }

                        /*************************************************** */

                        $quaditems = [];
                        $automaicQayd = new QaydController();
                        array_push($quaditems, (object) ['acountant_id' => $to_bank->accountant_number, 'madin' => $request->money * $all_currency_types[0]->currencies[0]->value, 'dayin' => 0]);
                        array_push($quaditems, (object) ['acountant_id' => $selectedBank->accountant_number, 'madin' => 0, 'dayin' => $request->money * $all_currency_types[0]->currencies[0]->value ]);
                        $date = Carbon::now();
                        $type = null;
                        $notes = ' تحويل مبلغ لمخزن أو حساب بنكي';
                        $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);

                        /*************************************************** */

                    } else {
                        $storeSelectedSafe = Store::where('safe_accountant_number', $request->to_store)->first();
                        if(!isset($storeSelectedSafe)){
                            session()->flash("success", "error ");
                            DB::rollback();
                            return redirect()->back();
                        }
                        $total_to_store = MoneySafe::where('store_id', $storeSelectedSafe->id)->orderBy('id','DESC')->first();

                        if (isset($total_to_store)) {
                            $safe = MoneySafe::create([
                                'notes' => $request->notes,
                                'date' => date('Y-m-d'),
                                'flag' => 4,
                                'money' => $request->money * $all_currency_types[0]->currencies[0]->value,
                                'total' => $total_to_store->total + ($request->money * $all_currency_types[0]->currencies[0]->value),
                                'type_money' => '0',
                                'user_id' => Auth::user()->id,
                                'store_id' => $storeSelectedSafe->id,
                                'note_id' => 4
                            ]);
                        } else {
                            $safe = MoneySafe::create([
                                'notes' => $request->notes,
                                'date' => date('Y-m-d'),
                                'flag' => 4,
                                'money' => $request->money * $all_currency_types[0]->currencies[0]->value,
                                'total' => $request->money * $all_currency_types[0]->currencies[0]->value,
                                'type_money' => '0',
                                'user_id' => Auth::user()->id,
                                'store_id' => $storeSelectedSafe->id,
                                'note_id' => 4
                            ]);
                        }

                        /*************************************************** */

                        $quaditems = [];
                        $automaicQayd = new QaydController();

                        array_push($quaditems, (object) ['acountant_id' => $storeSelectedSafe->safe_accountant_number, 'madin' => $request->money * $all_currency_types[0]->currencies[0]->value, 'dayin' => 0]);
                        array_push($quaditems, (object) ['acountant_id' => $selectedBank->accountant_number, 'madin' => 0, 'dayin' => $request->money * $all_currency_types[0]->currencies[0]->value]);
                        $date = Carbon::now();
                        $type = null;
                        $notes = ' تحويل مبلغ لمخزن أو حساب بنكي';
                        $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);

                        /*************************************************** */

                    }
                    if ($request->file('img_path')) {
                        $var = date_create();
                        $time = date_format($var, 'YmdHis');
                        $image_user = $time . '-'  . $request->img_path->getClientOriginalName();
                        $request->img_path->move(public_path('notes_safe_money'), $image_user);
                        $bank_safe->update([
                            'img_path' => $image_user

                        ]);
                        $safe->update([
                            'img_path' => $image_user

                        ]);
                    }
                    session()->flash("success", "تم صرف المبلغ  بنجاح");
                    DB::commit();
                    return redirect()->back();
                } else {

                    session()->flash("success", "  المبلغ غير كافي في الخزنة");
                    DB::rollback();
                    return redirect()->back();
                }
            } else {
                session()->flash("success", "   لا يوجد رصيد في هذه  الخزنة    ");
                DB::rollback();
                return redirect()->back();
            }
        }catch (\Exception $e) {
            DB::rollback();
            session()->flash("success", "لا يمكن حفظ الفاتورة".$e->getMessage());
            return redirect()->back();
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

    public function getRassed($safeId, $type)
    {
        $total = 0;
        if ($type == 'bank') {
            $bank = BankType::where('accountant_number', $safeId)->first();
            $raseed = $bank->bank_raseed;
            $total_qabd = BankSafeMoney::where('bank_type_id', $bank->id)->where('type_money', 0)->sum('money'); //قبض
            $total_sarf = BankSafeMoney::where('bank_type_id', $bank->id)->where('type_money', 1)->sum('money'); //صرف
            $total = floatval($raseed) + floatval($total_qabd) - floatval($total_sarf);
            // $total = BankSafeMoney::all()->last();
        } elseif ($type == 'store') {

            $bank = Store::where('safe_accountant_number', $safeId)->first();
            $raseed = $bank->store_raseed;
            $total_qabd = MoneySafe::where('store_id', $bank->id)->where('type_money', 0)->sum('money'); //قبض
            $total_sarf = MoneySafe::where('store_id', $bank->id)->where('type_money', 1)->sum('money'); //صرف
            $total = floatval($raseed) + floatval($total_qabd) - floatval($total_sarf);
            // $total = MoneySafe::where('store_id', $safeId)->latest()->first();
        }
        return $total;
    }
}
