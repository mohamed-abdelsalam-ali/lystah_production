<?php

namespace App\Http\Controllers;

use App\Models\BankSafeMoney;
use App\Models\BankType;
use App\Models\CurrencyType;
use App\Models\MoneySafe;
use App\Models\NotesSafeMoney;
use App\Models\Store;
// use App\Models\BankType;
use Illuminate\Http\Request;
use App\Http\Controllers\QaydController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SafeMoneyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  return 'ahmed';

    }


    public function get_safe_store($store_id)
    {
        $safeMoney = MoneySafe::where('store_id', $store_id)->with('user')->with('note')->get();
        $AccsafeMoney = Store::where('id',$store_id)->first();
        $store = Store::where('id', $store_id)->first();
        $all_curencys = CurrencyType::all();
        $bank_types=BankType::all();
        $all_notes=NotesSafeMoney::where('id',">",10)->get();

        $store_data = Store::where('id', $store_id)->get();

        $all_stores = Store::all();
        $current_balance = $safeMoney->last();
        if (isset($current_balance->total)) {
            $current_balance = $safeMoney->last()->total;
            return view('money_safe.index', compact('store_data','all_notes','bank_types','safeMoney', 'current_balance', 'store', 'all_stores', 'all_curencys','AccsafeMoney'));
        } else {
            $current_balance = 0;
            return view('money_safe.index', compact('store_data','all_notes','bank_types','safeMoney', 'current_balance', 'store', 'all_stores', 'all_curencys','AccsafeMoney'));
        }
    }

    public function show_safeMoney_month(Request $request)
    {
        // return $request;
        $safeMoney = MoneySafe::whereDate('date', $request->month)->where('store_id', $request->store_id)->with('user')->get();
        return $safeMoney;
    }
    public function add_money(Request $request)
    {
        // return $request;
        $total = MoneySafe::where('store_id', $request->store_id)->orderBy('id', 'desc')->first();
        if (isset($total)) {
            $safe= MoneySafe::create([
                'notes' => $request->notes,
                'date' => date('Y-m-d'),
                'flag' => 1,
                'money' => $request->money,
                'total' => $total->total + $request->money,
                'type_money' => '0',
                'user_id' => Auth::user()->id,
                'store_id' => $request->store_id,
                'note_id'=>1

            ]);
        } else {
            $safe= MoneySafe::create([
                'notes' => $request->notes,
                'date' => date('Y-m-d'),
                'flag' => 1,
                'money' => $request->money,
                'total' => $request->money,
                'type_money' => '0',
                'user_id' => Auth::user()->id,
                'store_id' => $request->store_id,
                'note_id'=>1


            ]);
        }
        if ($request->file('img_path')) {
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_user = $time . '-'  . $request->img_path->getClientOriginalName();
            $request->img_path->move(public_path('notes_safe_money'), $image_user);
           $safe->update([
            'img_path'=>$image_user

           ]);
        }
        session()->flash("success", "تم أضافة المبلغ للخزنة بنجاح");
        return redirect()->back();
    }
    public function money_send_store(Request $request)
    {
        // return $request;
        $total = MoneySafe::where('store_id', $request->store_id)->orderBy('id', 'desc')->first();
        $Ac_From_store = Store::where('id', $request->store_id)->first();
        $Ac_To_store = Store::where('id', $request->to_store)->first();
        if (isset($total)) {
            if ($total->total > $request->money) {
                MoneySafe::create([
                    'notes' => $request->notes,
                    'date' => date('Y-m-d'),
                    'flag' => 1,
                    'money' => $request->money,
                    'total' => $total->total - $request->money,
                    'type_money' => '1',
                    'user_id' => Auth::user()->id,
                    'store_id' => $request->store_id

                ]);
                $total_to_store = MoneySafe::where('store_id', $request->to_store)->orderBy('id', 'desc')->first();

                if (isset($total_to_store)) {
                    MoneySafe::create([
                        'notes' => $request->notes,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->money,
                        'total' => $total_to_store->total + $request->money,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => $request->to_store
                    ]);
                } else {
                    MoneySafe::create([
                        'notes' => $request->notes,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->money,
                        'total' => $request->money,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => $request->to_store

                    ]);
                }
                
                // /******************************* QAYD ************************************
                $quaditems = [];
                $automaicQayd = new QaydController();
    
                array_push ( $quaditems , (object) [ 'acountant_id'=>$Ac_From_store->safe_accountant_number  , 'madin'=> 0 , 'dayin'=> $request->money ] ); // الخزنة دائن
	            array_push ( $quaditems , (object) [ 'acountant_id'=>$Ac_To_store->safe_accountant_number  , 'dayin'=> 0 , 'madin'=> $request->money ] ); // المخزن مدين 
                
                $date =Carbon::now();
                $type = null;
                $notes='حركة نقدية '. $request->notes;
                $automaicQayd->AutomaticQayd($quaditems,$date,$type,$notes);
                /////////////////////////////////////////////////////////////////////////
                
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
    public function money_send_company(Request $request)
    {
        // return $request;
        $total = MoneySafe::where('store_id', $request->store_id)->orderBy('id', 'desc')->with('store')->first();

        if (isset($total)) {
            if ($total->total >= $request->money) {
                MoneySafe::create([
                    'notes' => $request->notes,
                    'date' => date('Y-m-d'),
                    'flag' => 1,
                    'money' => $request->money,
                    'total' => $total->total - $request->money,
                    'type_money' => '1',
                    'user_id' => Auth::user()->id,
                    'store_id' => $request->store_id,
                    'note_id'=>5,

                ]);
                $total_company = MoneySafe::where('store_id', $request->to_store)->orderBy('id', 'desc')->first();
                if (isset($total_company)) {

                    $safe=  MoneySafe::create([
                        'notes' => '('.' '.$total->store->name.' '.')'.' '.$request->notes,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->money,
                        'total' => $total_company->total + $request->money,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => $request->to_store,
                        'note_id'=>6
                    ]);
                } else {
                    $safe=  MoneySafe::create([
                        'notes' => '('.' '.$total->store->name.' '.')'.' '.$request->notes,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->money,
                        'total' => $request->money,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => $request->to_store,
                        'note_id'=>6


                    ]);
                }
                if ($request->file('img_path')) {
                    $var = date_create();
                    $time = date_format($var, 'YmdHis');
                    $image_user = $time . '-'  . $request->img_path->getClientOriginalName();
                    $request->img_path->move(public_path('notes_safe_money'), $image_user);
                   $safe->update([
                    'img_path'=>$image_user

                   ]);
                }


                // /******************************* QAYD ************************************
                $quaditems = [];
                $automaicQayd = new QaydController();
                $ac_from_store = Store::where('id',$request->store_id)->first();
                $ac_to_store = Store::where('id',$request->to_store)->first();
                array_push ( $quaditems , (object) [ 'acountant_id'=>$ac_from_store->safe_accountant_number  , 'madin'=> 0 , 'dayin'=> $request->money ] ); // المخزن دائن
	            array_push ( $quaditems , (object) [ 'acountant_id'=>$ac_to_store->safe_accountant_number  , 'dayin'=> 0 , 'madin'=> $request->money ] ); // خزنة الشركة مدين 
                
                $date =Carbon::now();
                $type = null;
                $notes='حركة نقدية '. $request->notes;
                $automaicQayd->AutomaticQayd($quaditems,$date,$type,$notes);
                /////////////////////////////////////////////////////////////////////////
                session()->flash("success", "تم توريد المبلغ  بنجاح");
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
    public function money_send_bank(Request $request)
    {
        // return $request;
        $total = MoneySafe::where('store_id', $request->store_id)->orderBy('id', 'desc')->first();
         $ac_store = Store::where('id',$request->store_id)->first();
         $ac_to_bank = BankType::where('id',$request->bank_type_id)->first();
        // return $total;
        $currency_id = $request->currency_id;
        $all_currency_types = CurrencyType::with(['currencies' => function ($query) use ($currency_id) {
            return $query->where('to', null)->where('currency_id', $currency_id);
        }])->where('id', $currency_id)->get();
        // return $request->money * $all_currency_types[0]->currencies[0]->value;
        if (isset($total)) {
            if ($total->total >= ($request->money * $all_currency_types[0]->currencies[0]->value)) {

               $safe= MoneySafe::create([
                    'notes' => $request->notes,
                    'date' => date('Y-m-d'),
                    'flag' => 1,
                    'money' => $request->money * $all_currency_types[0]->currencies[0]->value,
                    'total' => $total->total - ($request->money * $all_currency_types[0]->currencies[0]->value),
                    'type_money' => '1',
                    'user_id' => Auth::user()->id,
                    'store_id' => $request->store_id,
                    'note_id'=>3,
                ]);

                $total_bank = BankSafeMoney::all()->last();
                // return $total_bank;
                if (isset($total_bank)) {
                   $bank_safe= BankSafeMoney::create([
                        'notes' => $request->notes,
                        'date' => date('Y-m-d'),
                        'flag' => 4,
                        'money' => $request->money *  $all_currency_types[0]->currencies[0]->value,
                        'total' => $total_bank->total + ($request->money *  $all_currency_types[0]->currencies[0]->value),
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => $request->store_id,
                        'money_currency' => $request->money,
                        'currency_id' => $currency_id,
                        'bank_type_id'=>$request->bank_type_id
                    ]);
                } else {
                    $bank_safe= BankSafeMoney::create([
                        'notes' => $request->notes,
                        'date' => date('Y-m-d'),
                        'flag' => 4,
                        'money' => $request->money *  $all_currency_types[0]->currencies[0]->value,
                        'total' => $request->money *  $all_currency_types[0]->currencies[0]->value,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id' => $request->store_id,
                        'money_currency' => $request->money,
                        'currency_id' => $currency_id,
                        'bank_type_id'=>$request->bank_type_id,
                    ]);
                }
                if ($request->file('img_path')) {
                    $var = date_create();
                    $time = date_format($var, 'YmdHis');
                    $image_user = $time . '-'  . $request->img_path->getClientOriginalName();
                    $request->img_path->move(public_path('notes_safe_money'), $image_user);

                   $safe->update([
                    'img_path'=>$image_user

                   ]);
                   $bank_safe->update([
                    'img_path'=>$image_user

                   ]);
                }


                // /******************************* QAYD ************************************
                    $quaditems = [];
                    $automaicQayd = new QaydController();
                    
                    array_push ( $quaditems , (object) [ 'acountant_id'=>$ac_store->safe_accountant_number  , 'madin'=> 0 , 'dayin'=> $request->money *  $all_currency_types[0]->currencies[0]->value ] ); // الخزنة دائن
    	            array_push ( $quaditems , (object) [ 'acountant_id'=>$ac_to_bank->accountant_number  , 'dayin'=> 0 , 'madin'=> $request->money *  $all_currency_types[0]->currencies[0]->value ] ); // البنك مدين 
                    
                    $date =Carbon::now();
                    $type = null;
                    // $notes='توريد مبلغ للبنك  '. $request->notes;
                    $notes=$request->notes;
                    $automaicQayd->AutomaticQayd($quaditems,$date,$type,$notes);
                /////////////////////////////////////////////////////////////////////////

                session()->flash("success", "تم توريد المبلغ  بنجاح");
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

    public function add_other(Request $request)
    {
        // return $request;
        $total = MoneySafe::where('store_id', $request->store_id)->orderBy('id', 'desc')->first();
        if (isset($total)) {
            if ($total->total > $request->money) {
               $safe= MoneySafe::create([
                    'notes' => $request->notes,
                    'date' => date('Y-m-d'),
                    'flag' => 1,
                    'money' => $request->money,
                    'total' => $total->total - $request->money,
                    'type_money' => '1',
                    'user_id' => Auth::user()->id,
                    'store_id' => $request->store_id,
                    'note_id'=>$request->note_id,

                ]);
                if ($request->file('img_path')) {
                    $var = date_create();
                    $time = date_format($var, 'YmdHis');
                    $image_user = $time . '-'  . $request->img_path->getClientOriginalName();
                    $request->img_path->move(public_path('notes_safe_money'), $image_user);
                   $safe->update([
                    'img_path'=>$image_user

                   ]);
                }
                
                
                // /******************************* QAYD ************************************
                 $ac_store = Store::where('id',$request->store_id)->first();
                    $quaditems = [];
                    $automaicQayd = new QaydController();
                    
                    array_push ( $quaditems , (object) [ 'acountant_id'=>$ac_store->safe_accountant_number  , 'madin'=> 0 , 'dayin'=> $request->money  ] ); // الخزنة دائن
    	            array_push ( $quaditems , (object) [ 'acountant_id'=>337  , 'dayin'=> 0 , 'madin'=> $request->money  ] ); // نثريات مدين 
                    
                    $date =Carbon::now();
                    $type = null;
                    // $notes='توريد مبلغ للبنك  '. $request->notes;
                    $notes=$request->notes;
                    $automaicQayd->AutomaticQayd($quaditems,$date,$type,$notes);
                /////////////////////////////////////////////////////////////////////////
                
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
