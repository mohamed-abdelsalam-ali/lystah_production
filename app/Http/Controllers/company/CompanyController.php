<?php

namespace App\Http\Controllers\Company;

use App\Models\Company\Company;
use Illuminate\Http\Request;
use App\Models\Company\UserSubscription;
use App\Models\Company\UserPayment;
use Illuminate\Support\Facades\DB;
use App\Models\Company\User;
use App\Http\Controllers\Controller;
class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_companies=Company::all();
        // return $all_companies;
        return view('company.index',compact('all_companies'));
    }

    public function dashboard()
    {
        $user = auth()->user();
        // return $user;
        // $currentSubscription = UserSubscription::where('user_id', $user->id)
        //     ->with(['subscriptionPlan'])
        //     ->latest()
        //     ->first();

        // $latestPayment = UserPayment::where('user_id', $user->id)
        //     ->with(['paymentMethod'])
        //     ->latest()
        //     ->first();
        // config(['database.connections.mysql.database' => 'general']);
        // DB::purge('mysql');
        // DB::reconnect('mysql');
        // $user = auth()->user();
        // Get the current subscription from the general DB
        $user_general = User::on('mysql_general')->where('email', $user->email)->first();
        $currentSubscription = UserSubscription::on('mysql_general')  // 'mysql' is the default connection for the general DB
            ->where('user_id', $user_general->id)
            ->with(['subscriptionPlan'])
            ->latest()
            ->first();
        $latestPayment = UserPayment::on('mysql_general')  // Use the 'mysql' connection for the general DB
            ->where('user_id', $user_general->id)
            ->with(['paymentMethod'])
            ->latest()
            ->first();
     

            
        // config(['database.connections.mysql.database' => $user->db_name]);
        // DB::purge('mysql');
        // DB::reconnect('mysql');
        $users = User::get();
        
        return view('company.dashboard', compact('users', 'currentSubscription', 'latestPayment', 'user_general' ));
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
        // return $request;
        if ($request->hasfile('company_image')){
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_company = $time . '-' . $request->name . '-' . $request->company_image->getClientOriginalName();
            $request->company_image->move(public_path('company_images'), $image_company);
            Company::create([
                'name'=>$request->name,
                'address'=>$request->address,
                'telephone'=>$request->telephone,
                'company_image'=>$image_company,
                'mail'=>$request->mail,
                'desc'=>$request->desc,

            ]);

        }else{
            Company::create([
                'name'=>$request->name,
                'address'=>$request->address,
                'telephone'=>$request->telephone,
                'mail'=>$request->mail,
                'desc'=>$request->desc,

            ]);

        }
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('company.index');


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
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $user_general = User::on('mysql_general')->where('email', $user->email)->first();

        $request->validate([
            'work' => 'nullable|string|max:255',
            'company_tax_file' => 'nullable|string|max:255',
            'company_commercial_register' => 'nullable|string|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
       


        $updateData = $request->only('work', 'company_tax_file', 'company_commercial_register');

        if ($request->hasFile('company_logo')) {
            // Delete old logo if it exists
            if ($user_general->logo_print && \Illuminate\Support\Facades\Storage::disk('public')->exists($user_general->logo_print)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user_general->logo_print);
            }
            // Store new logo
            $path = $request->file('company_logo')->store('company_logos', 'public');
            $updateData['logo_print'] = $path;
        }

        $user_general->update($updateData);

        return redirect()->route('company.dashboard')->with('success', 'تم تحديث بيانات الشركة بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // return $request;
        $company=Company::where('id',$request->company_id)->first();
        // return $company;
        if(isset($company->company_image))
        {
         $image_path = public_path() . '/company_images' . '/' . $company->company_image;
         unlink($image_path);
 
        }
        $company->delete();
        session()->flash("success", "تم الحذف بنجاح");
        return redirect()->route('company.index');


    }
}
