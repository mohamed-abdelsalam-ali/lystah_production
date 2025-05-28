<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\MoneySafe;
use App\Models\NotesSafeMoney;
use App\Models\Solfa;
use App\Models\SolfaDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SolfaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }
    public function get_solfa_store($id)
    {
        // return $id;
        $employees = Employee::where('store_id', $id)->where('flag_finish_job', 0)->get();
        $employees_id = $employees->pluck('id');
        $store_id = $id;
        // $employees_id = Employee::where('store_id',$id)->pluck('id');
        $all_solfas = Solfa::whereIn('employee_id', $employees_id)->with('employee')->with('user')->get();
        // return $all_solfas;

        // return $all_solfas;
        return view('employees.solfa', compact('all_solfas', 'employees', 'store_id'));
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
        // return $request;
        $employee = Employee::where('id', $request->employee_id)->first();
        // return $employee;

        $total = MoneySafe::where('store_id', $employee->store_id)->orderBy('id', 'desc')->first();
        // return $total;
        if ($request->flag == 0) {
            if ($request->money == 0) {
                session()->flash("success", "  المبلغ غير صحيح");
                return redirect()->back();
            } else {
                if (isset($total)) {

                    if ($total->total >= $request->money) {

                        Solfa::create([
                            'employee_id' => $request->employee_id,
                            'total_solfa' => $request->money,
                            'date' => $request->date,
                            'user_id' => Auth::user()->id,
                            'notes' => $request->notes,
                            'finish_flag' => 0,
                            'remain' => $request->money,
                        ]);
                        $employee->update([
                            'raseed' => $request->money + $employee->raseed,
                        ]);
                        MoneySafe::create([
                            'notes' => "  صرف سلفة" . " " . "لـ" . " " . $employee->employee_name . " " . " يوم " . $request->date,
                            'date' => $request->date,
                            'flag' => 6,
                            'money' => $request->money,
                            'total' => $total->total - $request->money,
                            'type_money' => '1',
                            'user_id' => Auth::user()->id,
                            'note_id' => 8,
                            'store_id' => $employee->store_id


                        ]);

                        session()->flash("success", "تم إضافة السلفة بنجاح");
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


        } else {
            // return $request;
            $total = MoneySafe::where('store_id', $employee->store_id)->orderBy('id', 'desc')->first();
            $solfa = Solfa::where('employee_id', $request->employee_id)->where('finish_flag', 0)->get();
            // return $solfa;
            if ($request->total == 0 || $request->total < $request->money ||$request->money == 0) {
                session()->flash("success", "  المبلغ غير صحيح");
                return redirect()->back();
            } else {
                $pay=$request->money;
                foreach ($solfa as $x) {
                    if ($x->remain > $pay) {
                        $x->update([
                            'remain' => $x->remain - $pay,
                        ]);
                    } else if ($x->remain < $pay) {
                        $pay=$pay - $x->remain;
                        $x->update([
                            'remain' => 0,
                            'finish_flag' => 1,

                        ]);
                    } else if ($x->remain == $pay) {
                        $x->update([
                            'remain' => 0,
                            'finish_flag' => 1,

                        ]);
                    }

                    // $remain=$solfa->
                }

                $employee->update([
                    'raseed' => $employee->raseed - $request->money

                ]);

                SolfaDetail::create([
                    'total' => $request->total,
                    'amount' => $request->money,
                    'date' => $request->date,
                    'user_id' => Auth::user()->id,
                    'employee_id' => $request->employee_id,
                ]);

                if (isset($total)) {

                    MoneySafe::create([
                        'notes' => "  سداد مبلغ سلفة" . " " . "لـ" . " " . $employee->employee_name . " " . " يوم " . $request->date,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->money,
                        'total' => $total->total + $request->money,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'note_id' => $request->note_id,
                        'store_id' => $employee->store_id,
                        'note_id' => 9

                    ]);
                } else {
                    MoneySafe::create([
                        'notes' => "  سداد مبلغ سلفة" . " " . "لـ" . " " . $employee->employee_name . " " . " يوم " . $request->date,
                        'date' => date('Y-m-d'),
                        'flag' => 1,
                        'money' => $request->money,
                        'total' => $request->money,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'note_id' => $request->note_id,
                        'store_id' => $employee->store_id,
                        'note_id' => 9


                    ]);
                }
                session()->flash("success", "تم سداد المبلغ بنجاح");
                return redirect()->back();
            }


        }
    }
    public function employee_solfa_details($id)
    {
        // return $id;
        $employee = Employee::where('id', $id)->with('role')->first();
        return $employee;
    }

    public function employee_solfa_history($id)
    {
        $employees = Employee::where('store_id', $id)->with('role')->where('flag_finish_job', 0)->get();

        return view('employees.solfa_details', compact('employees'));
    }
    public function employee_solfa_history_details($id)
    {
        // return $id;
        $solfa_details = SolfaDetail::where('employee_id', $id)->with('employee')->with('user')->get();
        return $solfa_details;
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