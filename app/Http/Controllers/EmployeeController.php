<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use App\Models\BranchTree;
use Spatie\Permission\Models\Role as ModelsRole;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_employees = Employee::with('role')->with('store')->get();
        $number_of_employees = count($all_employees);
        $salary_total = Employee::Where('flag_finish_job',0)->sum('employee_final_salary');
        // return $salary_total;
        $all_roles = ModelsRole::all();
        $all_stores=Store::all();

        // return $all_employees;
        return view('employees.index',compact('all_employees','number_of_employees','salary_total','all_roles','all_stores'));
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
        DB::beginTransaction();
        try {
          
            
            
    
            $parentid= BranchTree::where('accountant_number',3111)->first()->id;
            $lastchildAccNo = BranchTree::where('parent_id',$parentid)->orderBy('id','DESC')->first();
            $ac_number = 0;
            if($lastchildAccNo){
                $ac_number = IntVal($lastchildAccNo->accountant_number)+1;
            }else{
                $ac_number = 31111;
            }
             BranchTree::create([
                'name' =>   ' ذمة -'.$request->employee_name,
                'en_name' => $request->employee_name,
                'parent_id' =>  $parentid,
                'accountant_number'=>$ac_number
            ]);
    
            $parentidx= BranchTree::where('accountant_number',1632)->first()->id;
            $lastchildAccNox = BranchTree::where('parent_id',$parentidx)->orderBy('id','DESC')->first();
    
            $ac_numberx = 0;
            if($lastchildAccNox){
                $ac_numberx = IntVal($lastchildAccNox->accountant_number)+1;
            }else{
                $ac_numberx = 16321;
            }
            BranchTree::create([
                'name' =>   ' سلف -'.$request->employee_name,
                'en_name' => $request->employee_name,
                'parent_id' =>  $parentidx,
                'accountant_number'=> $ac_numberx
            ]);
    
            $parentidy= BranchTree::where('accountant_number',3117)->first()->id;
            $lastchildAccNoy = BranchTree::where('parent_id',$parentidy)->orderBy('id','DESC')->first();
    
            $ac_numbery = 0;
            if($lastchildAccNoy){
                $ac_numbery = IntVal($lastchildAccNoy->accountant_number)+1;
            }else{
                $ac_numbery = 31171;
            }
            BranchTree::create([
                'name' =>   ' مكافاة -'.$request->employee_name,
                'en_name' => $request->employee_name,
                'parent_id' =>  $parentidy,
                'accountant_number'=> $ac_numbery
            ]);
            
            
              Employee::create([
                'employee_name' => $request->employee_name,
                'employee_role_id' => $request->employee_role_id,
                'employee_phone' => $request->employee_phone,
                'employee_telephone' => $request->employee_telephone,
                'employee_address' => $request->employee_address,
                'employee_national_id' => $request->employee_national_id,
                'employee_salary' => $request->employee_salary,
                'insurance_value' => $request->insurance_value,
                'employee_final_salary' => $request->employee_final_salary,
                'flag_finish_job'=>0,
                'store_id'=>$request->store_id,
                'accountant_number'=> $ac_number,
                'solfa_accountant_number'=> $ac_numberx,
                'commision_accountant_number'=> $ac_numbery
            ]);
            
             DB::commit();
            session()->flash("success", "تم إضافة بيانات الموظف بنجاح");
            // return redirect()->route('reservations.index');
            return redirect()->back();
            
        } catch (\Exception $e) {
             DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
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
    public function edit(Request $request)
    {
        // return $request;
        $employee = Employee::findorFail($request->id);
        $employee->update([
            'flag_finish_job' => 0,

        ]);
        session()->flash("success", "تم رجوع الموظف بنجاح  ");
        return redirect()->back();
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
        $employee = Employee::findorFail($request->id);
        $employee->update([
            'employee_name' => $request->employee_name,
            'employee_role_id' => $request->employee_role_id,
            'employee_phone' => $request->employee_phone,
            'employee_telephone' => $request->employee_telephone,
            'employee_address' => $request->employee_address,
            'employee_national_id' => $request->employee_national_id,
            'employee_salary' => $request->employee_salary,
            'insurance_value' => $request->insurance_value,
            'employee_final_salary' => $request->employee_final_salary,
            'store_id'=>$request->store_id
        ]);
        session()->flash("success", "تم تعديل بيانات الموظف بنجاح");
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $employee = Employee::findorFail($request->id);
        $employee->update([
            'flag_finish_job' => 1,

        ]);
        session()->flash("success", "تم حذف الموظف بنجاح  ");
        return redirect()->back();
    }
}
