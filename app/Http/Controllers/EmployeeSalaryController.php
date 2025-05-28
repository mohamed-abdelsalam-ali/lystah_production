<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\MoneySafe;
use App\Models\SalaryEmployeeAction;
use App\Models\SalaryEmployeeMonth;
use App\Models\Solfa;
use App\Models\SolfaDetail;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeSalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }
    public function get_store_employee_salary($store_id)
    {
        // return $store_id;
        $store=Store::where('id',$store_id)->first();
        // return $store;

        return view('employees.employee_salary',compact('store'));

    }
    public function get_employee_salary(Request $request)
    {
        // return $month;
        $employees=Employee::where('flag_finish_job',0)->where('store_id',$request->store_id)->with('role')->get();
        foreach ($employees as $employee) {
            $actions=SalaryEmployeeAction::where('month',$request->month)->where('employee_id',$employee->id)->where('finish_flag',0)->sum('money');

            if ( $actions) {
                $employee['newsalary']= $employee->employee_final_salary + $actions;
            } else {
                 $employee['newsalary']= $employee->employee_final_salary ;

            }
        }
        return $employees;

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
        $employee = Employee::where('id', $request->employee_id)->first();
        $check_slary_paid = SalaryEmployeeMonth::where('employee_id', $request->employee_id)->where('month', $request->month)->first();
        // return $check_slary_paid;
        if (isset($check_slary_paid)) {
            session()->flash("success", "تم صرف المرتب مسبقا لهذا الشهر");
            return redirect()->back();
        } else {
            if ($request->flag_type == 0) {
                SalaryEmployeeAction::create([
                    'employee_id' => $request->employee_id,
                    'flag_type' => $request->flag_type,
                    'money' => $request->money,
                    'date' => $request->date,
                    'finish_flag' => 0,
                    'month' => $request->month,
                    'user_id' => Auth::user()->id,
                    'notes' => $request->notes
                ]);

                $quaditems = [];
                $automaicQayd = new QaydController();
                array_push($quaditems, (object) ['acountant_id' => $employee->accountant_number, 'madin' => $request->money, 'dayin' => 0]); //
                array_push($quaditems, (object) ['acountant_id' => $employee->solfa_accountant_number, 'madin' =>0 , 'dayin' => $request->money]); //
                $date = Carbon::now();
                $type = null;
                $notes = 'قيد الرواتب مع السلف';
                $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
            } else {
                if ($request->money > $employee->employee_salary) {
                    session()->flash("success", "  هذا المبلغ اكبر من مرتب الموظف لا يمكن اضافتة ");
                    return redirect()->back();
                } else {
                    SalaryEmployeeAction::create([
                        'employee_id' => $request->employee_id,
                        'flag_type' => $request->flag_type,
                        'money' => $request->money * -1,
                        'date' => $request->date,
                        'finish_flag' => 0,
                        'month' => $request->month,
                        'user_id' => Auth::user()->id,
                        'notes' => $request->notes

                    ]);

                    $quaditems = [];
                    $automaicQayd = new QaydController();
                    array_push($quaditems, (object) ['acountant_id' => $employee->accountant_number, 'madin' => $request->money* -1, 'dayin' => 0]); //
                    array_push($quaditems, (object) ['acountant_id' => $employee->solfa_accountant_number, 'madin' =>0 , 'dayin' => $request->money]); //
                    $date = Carbon::now();
                    $type = null;
                    $notes = 'قيد الرواتب مع السلف';
                    $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);
                }


            }


            if ($request->flag_type == 0) {
                session()->flash("success", "تم إضافة المنحة بنجاح");
                return redirect()->back();
            } elseif ($request->flag_type == 1) {
                session()->flash("success", "تم إضافة الخصم بنجاح");
                return redirect()->back();
            } else {

                $total = MoneySafe::where('store_id',$employee->store_id)->latest()->first();
                if (isset($total)) {
                    if ($total->total >= $request->money) {
                        MoneySafe::create([
                            'notes' => "  صرف سلفة" . " " . "لـ" . " " . $employee->employee_name . " " . " لشهر " . $request->month,
                            'date' => $request->date,
                            'flag' => 6,
                            'money' => $request->money,
                            'total' => $total->total - $request->money,
                            'type_money' => '1',
                            'user_id' => Auth::user()->id,
                            'store_id'=>$employee->store_id
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
        }
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
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // return $request;
        $action = SalaryEmployeeAction::where('id', $request->id)->with('employee')->first();
        // return $action->employee->store_id;
        $check_slary_paid = SalaryEmployeeMonth::where('employee_id', $request->employee_id)->where('month', $request->month)->first();
        if (isset($check_slary_paid)) {
            session()->flash("success", "تم صرف المرتب مسبقا لهذا الشهر");
            return redirect()->back();
        }else{
            if ($action->flag_type == 2) {
                $total = MoneySafe::where('store_id',$action->employee->store_id)->latest()->first();
                if (isset($total)) {
                    MoneySafe::create([
                        'notes' => "  إلغاء سلفة" . " " . "لـ" . " " . $action->employee->employee_name . " " . " لشهر" . $action->month,
                        'date' => $action->date,
                        'flag' => 6,
                        'money' => $action->money * -1,
                        'total' => $total->total + $action->money * -1,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id'=>$action->employee->store_id
                    ]);
                } else {
                    MoneySafe::create([
                        'notes' => "  إلغاء سلفة" . " " . "لـ" . " " . $action->employee->employee_name . " " . " لشهر" . $action->month,
                        'date' => $action->date,
                        'flag' => 6,
                        'money' => $action->money * -1,
                        'total' => $action->money * -1,
                        'type_money' => '0',
                        'user_id' => Auth::user()->id,
                        'store_id'=>$action->employee->store_id

                    ]);
                }
            }
        }

        $action->delete();
        session()->flash("success", "تم الحذف بنجاح  ");
        return redirect()->back();
    }
    public function employee_salary_details($id)
    {
        // return $id;
        $employee_id = $id;
        $actions = SalaryEmployeeAction::where('employee_id', $id)->with('employee')->get();
        // return $actions;
        return view('employees.salary_details', compact('actions', 'employee_id'));

    }

    public function get_employee_salary_details(Request $request)
    {

        $actions = SalaryEmployeeAction::where('employee_id', $request->employee_id)->where('month', $request->month)->with('employee')->where('finish_flag', 0)->get();
        return $actions;
    }

    public function pay_salary(Request $request)
    {
        // return $request;
        $employee = Employee::where('id', $request->employee_id)->first();
        $check_slary_paid = SalaryEmployeeMonth::where('employee_id', $request->employee_id)->where('month', $request->month)->first();
        // return $check_slary_paid;
        if (isset($check_slary_paid)) {
            // return 'sss';
            session()->flash("success", "تم صرف المرتب مسبقا لهذا الشهر");
            return redirect()->back();
        } else {
            SalaryEmployeeMonth::create(
                [
                    'salary_month' => $request->money,
                    'employee_id' => $request->employee_id,
                    'date' => $request->date,
                    'month' => $request->month,

                ]
            );



            $total = MoneySafe::where('store_id',$employee->store_id)->latest()->first();
            // return $total;
            if (isset($total)) {
                if ($total->total >= $request->money) {
                    MoneySafe::create([
                        'notes' => " صرف مرتب" . " " . " لـ" . " " . $employee->employee_name . " " . "لشهر" . " " . $request->month,
                        'date' => $request->date,
                        'flag' => 3,
                        'money' => $request->money,
                        'total' => $total->total - $request->money,
                        'type_money' => '1',
                        'user_id' => Auth::user()->id,
                        'store_id'=>$employee->store_id,
                        'note_id'=>7,


                    ]);

                    $quaditems = [];
                    $automaicQayd = new QaydController();
                    array_push($quaditems, (object) ['acountant_id' => 2741, 'madin' => $request->money, 'dayin' => 0]); //
                    array_push($quaditems, (object) ['acountant_id' => $employee->accountant_number, 'madin' =>0 , 'dayin' => $request->mone]); //
                    $date = Carbon::now();
                    $type = null;
                    $notes = 'قيد استحقاق المرتبات والأجور';
                    $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);

                    SalaryEmployeeAction::where('employee_id', $request->employee_id)->update([
                        'finish_flag' => 1,
                    ]);
                    if ($request->flag_solfa && $request->flag_solfa == 1) {
                        $total = MoneySafe::where('store_id',$employee->store_id)->latest()->first();
                        $solfa = Solfa::where('employee_id', $request->employee_id)->where('finish_flag', 0)->get();
                        if ($request->solfa_total == 0 || $request->solfa_total < $request->solfa_money ||$request->solfa_money == 0) {
                            session()->flash("success", "    تم صرف المرتب بنجاح ولكن لم يتم سداد السلفة لان المبلغ غير صحيح");
                            return redirect()->back();
                        } else {
                            $pay=$request->solfa_money;
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
                                'raseed' => $employee->raseed - $request->solfa_money

                            ]);

                            SolfaDetail::create([
                                'total' => $request->total,
                                'amount' => $request->solfa_money,
                                'date' => $request->date,
                                'user_id' => Auth::user()->id,
                                'employee_id' => $request->employee_id,
                            ]);

                            if (isset($total)) {

                                MoneySafe::create([
                                    'notes' => "  سداد مبلغ سلفة" . " " . "لـ" . " " . $employee->employee_name . " " . " يوم " . $request->date,
                                    'date' => date('Y-m-d'),
                                    'flag' => 1,
                                    'money' => $request->solfa_money,
                                    'total' => $total->total + $request->solfa_money,
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
                                    'money' => $request->solfa_money,
                                    'total' => $request->solfa_money,
                                    'type_money' => '0',
                                    'user_id' => Auth::user()->id,
                                    'note_id' => $request->note_id,
                                    'store_id' => $employee->store_id,
                                    'note_id' => 9


                                ]);
                            }

                            $quaditems = [];
                            $automaicQayd = new QaydController();
                            array_push($quaditems, (object) ['acountant_id' => $employee->accountant_number, 'madin' => 0, 'dayin' => $request->solfa_money]); //
                            array_push($quaditems, (object) ['acountant_id' => $employee->solfa_accountant_number, 'madin' =>$request->solfa_money , 'dayin' => 0 ]); //
                            $date = Carbon::now();
                            $type = null;
                            $notes = 'قيد الرواتب مع السلف';
                            $automaicQayd->AutomaticQayd($quaditems, $date, $type, $notes);

                            session()->flash("success", "تم صرف المرتب وسداد السلفة بنجاح بنجاح ");
                            return redirect()->back();
                        }
                    }





                } else {
                    session()->flash("success", "  المبلغ غير كافي في الخزنة");
                    return redirect()->back();
                }
            } else {

                session()->flash("success", "   لا يوجد رصيد في الخزنة   ");
                return redirect()->back();
            }


        }
    }
    public function salary_history($store_id)
    {

        $historyy = SalaryEmployeeMonth::whereHas('employee', function ($query) use ($store_id) {
            $query->where('store_id', $store_id);
        })->with('employee')->get();
        $store=Store::where('id',$store_id)->first();

        return view('employees.salary_history', compact('historyy','store'));
    }
}
