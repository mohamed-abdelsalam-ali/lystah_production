<?php

namespace App\Http\Controllers\Company;

use App\Models\Company\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\Controller;
class NewCompanyController extends Controller
{
    //
    public function dashboard()
    {
        // Ensure we're using the company database
        $this->switchToCompanyDatabase();
        
        return view('company.dashboard', [
            'company' => auth()->user()->company_name,
            'users' => User::all() // Users from company database
        ]);
    }

    public function config()
    {
        $this->switchToCompanyDatabase();
        
        return view('company.config', [
            'company' => auth()->user()
        ]);
    }

    // public function addUser(Request $request)
    // {
    //     $this->switchToCompanyDatabase();
        
    //     $validator = Validator::make($request->all(), [
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //         'password' => ['required', 'confirmed', Password::min(8)],
    //         'role' => ['required', 'in:admin,user'],
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()
    //             ->withErrors($validator)
    //             ->withInput();
    //     }

    //     User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'is_admin' => $request->role === 'admin',
    //     ]);

    //     return redirect()->route('company.dashboard')
    //         ->with('success', 'User added successfully!');
    // }

    protected function switchToCompanyDatabase()
    {
        $user = auth()->user();
        // \Log::info('curentttttttttttttttttttttt: ' . $user->db_name);
        if ($user && $user->db_name) {
            config(['database.connections.mysql.database' => $user->db_name]);
            DB::purge('mysql');
            DB::reconnect('mysql');
        }
    }
}
