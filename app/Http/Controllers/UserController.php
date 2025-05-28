<?php

namespace App\Http\Controllers;

use App\Models\Company\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alluser = User::all();
        // return $alluser;
        // foreach ($alluser as $alluse) {
        //    return $alluse->password;
        // }
        // return $alluser->password;

        return view('users.test', compact('alluser'));
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
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'email' => ['required', 'unique:' . User::class],

            'telephone' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            ' username.required' => 'يرجي ادخال اسم المستخدم',
            'username.unique' => 'هذا الاسم موجود سابقا   ',
            ' email.required' => 'يرجي ادخال اسم المستخدم',
            'email.unique' => 'هذا الاسم موجود سابقا   ',
            'password.required' => 'يرجي ادخال كلمة المرور',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            'telephone.required' => '  يرجي ادخال رقم هاتف',
        ]);
        if ($request->file('profile_img') && $request->file('national_img')) {
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_user = $time . '-' . $request->username . '-' . $request->profile_img->getClientOriginalName();
            $request->profile_img->move(public_path('users_images'), $image_user);
            $image_national = $time . '-' . $request->username . '-' . $request->national_img->getClientOriginalName();
            $request->national_img->move(public_path('national_images'), $image_national);

            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'telephone' => $request->telephone,
                'email' => $request->email,
                'profile_img' => $image_user,
                'national_img' => $image_national,
            ]);
            // return $user;

        } else if ($request->file('profile_img') && !$request->file('national_img')) {
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_user = $time . '-' . $request->username . '-' . $request->profile_img->getClientOriginalName();
            $request->profile_img->move(public_path('users_images'), $image_user);

            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'telephone' => $request->telephone,
                'email' => $request->email,
                'profile_img' => $image_user,
            ]);

        } else if ($request->file('national_img') && !$request->file('profile_img')) {
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_national = $time . '-' . $request->username . '-' . $request->national_img->getClientOriginalName();
            $request->national_img->move(public_path('national_images'), $image_national);

            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'telephone' => $request->telephone,
                'email' => $request->email,
                'national_img' => $image_national,
            ]);

        } else {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'telephone' => $request->telephone,
                'email' => $request->email,
            ]);

        }
        session()->flash("success", "تم إضافة المستخدم بنجاح");
        return redirect()->back();

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
    public function edit($id)
    {
        $user = User::findorfail($id);
        // return $user;

        return view('users.user_profile', compact('user'));

    }
    public function reset_password_user(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $password=12345678;
        $user->update([
            $user->password = Hash::make($password),
        ]);

        session()->flash("success", "تم  تغيير كلمة المرور  بنجاح");
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request)
    {
        // return $request->img_path->getClientOriginalName();
        $user = User::where('id', $request->user_id)->first();

        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'telephone' => ['required'],

        ], [
            'username.required' => 'يرجي ادخال اسم المستخدم',
            'username.unique' => 'هذا الاسم موجود سابقا   ',
            'telephone.required' => '  يرجي ادخال رقم هاتف',
        ]);
        // return $user;
        if ($request->file('img_path') || $request->file('national_img')) {
            if ($request->file('img_path')) {
                if ($user->profile_img != null) {
                    $image_path = public_path() . '/users_images' . '/' . $user->profile_img;
                    unlink($image_path);
                }
                $var = date_create();
                $time = date_format($var, 'YmdHis');
                $image_user = $time . '-' . $request->username . '-' . $request->img_path->getClientOriginalName();
                $request->img_path->move(public_path('users_images'), $image_user);
                $user->update([
                    $user->username = $request->username,
                    $user->telephone = $request->telephone,
                    $user->profile_img = $image_user,
                ]);
            }
            if ($request->file('national_img')) {
                if ($user->national_img != null) {
                    $image_path = public_path() . '/national_images' . '/' . $user->national_img;
                    unlink($image_path);
                }
                $var = date_create();
                $time = date_format($var, 'YmdHis');
                $image_national = $time . '-' . $request->username . '-' . $request->national_img->getClientOriginalName();
                $request->national_img->move(public_path('national_images'), $image_national);
                $user->update([
                    $user->username = $request->username,
                    $user->telephone = $request->telephone,
                    // $user->mobile = $request->mobile,
                    $user->national_img = $image_national,
                ]);
            }
        } else {
            $user->update([
                $user->username = $request->username,
                $user->telephone = $request->telephone,
            ]);
        }
        session()->flash("success", "تم التعديل البيانات بنجاح");
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
         $check=Audit::where('user_id',$request->id)->get();
        if(count($check) > 0)
        {
              session()->flash("error", "   لا يمكن حذف هذا المستخدم");
        return redirect()->back();

        }else{
               $user = User::where('id', $request->id)->first();
        if ($user->profile_img) {
            $image_path = public_path() . '/users_images' . '/' . $user->profile_img;
            unlink($image_path);

        }
        if ($user->national_img) {
            $image_path = public_path() . '/national_images' . '/' . $user->national_img;
            unlink($image_path);

        }
        $user->delete();
        session()->flash("error", "تم حذف المستخدم بنجاح");
        return redirect()->back();


        }
        
     
    }
    public function update_password(Request $request)
    {
        $user = User::findorfail($request->user_id);
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    return $fail(__('كلمة المرور الحالية غير صحيحة'));
                }
            }],
        ], [

            'password.required' => 'يرجي ادخال كلمة المرور',
            'password.confirmed' => 'كلمة المرور غير متطابقة   ',
        ]);
        $user->update([
            $user->password = Hash::make($request->password),
        ]);

        session()->flash("info", "تم التعديل كلمه المرور بنجاح");
        return redirect()->back();

    }
}
