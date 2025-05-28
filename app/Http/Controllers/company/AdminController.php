<?php

namespace App\Http\Controllers\Company;

use App\Models\Company\User;
use App\Models\Company\UserPayment;
use App\Models\Company\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Company\SubscriptionPlan;
use App\Models\Company\PaymentMethod;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
class AdminController extends Controller
{
    public function dashboard()
    {
        // Get companies with their latest subscriptions and payments
        $companies = User::on('mysql_general')
            ->where('role_name', 'Admin')
            ->with(['subscriptions' => function($query) {
                $query->latest();
            }, 'payments' => function($query) {
                $query->latest();
            }])
            ->get();

        // Calculate statistics
        $stats = [
            'total_companies' => $companies->count(),
            'active_companies' => $companies->filter(function($company) {
                return $company->subscriptions->first()?->status === 'active';
            })->count(),
            'total_revenue' => UserPayment::on('mysql_general')
                ->where('status', 'paid')
                ->sum('amount'),
            'pending_payments' => UserPayment::on('mysql_general')
                ->where('status', 'pending')
                ->count(),
            'subscription_plans' => SubscriptionPlan::on('mysql_general')->count(),
            'payment_methods' => PaymentMethod::on('mysql_general')->count()
        ];

        // Get subscription plan distribution
        $planStats = UserSubscription::on('mysql_general')
            ->where('status', 'active')
            ->selectRaw('subscription_plan_id, COUNT(*) as count')
            ->groupBy('subscription_plan_id')
            ->with('subscriptionPlan')
            ->get()
            ->map(function($stat) {
                return [
                    'name' => $stat->subscriptionPlan->name,
                    'count' => $stat->count
                ];
            });

        // Get recent activities
    //   return UserPayment::on('mysql_general')->latest()->first();
       $recentActivities = collect()
            ->merge(UserSubscription::on('mysql_general')
                ->with('user')  // Eager load the user relationship
                ->latest()
                ->take(5)
                ->get()
                ->map(function($sub) {
                    return [
                        'type' => 'subscription',
                        'date' => $sub->created_at,
                        'details' => "New subscription for " . ($sub->user?->company_name ?? 'Unknown Company')
                    ];
                }))
            ->merge(UserPayment::on('mysql_general')
                ->with('user')  // Eager load the user relationship
                ->latest()
                ->take(5)
                ->get()
                ->map(function($payment) {
                    return [
                        'type' => 'payment',
                        'date' => $payment->created_at,
                        'details' => "Payment of {$payment->amount} from " . ($payment->user?->company_name ?? 'Unknown Company')
                    ];
                }))
            ->sortByDesc('date')
            ->take(5);

        return view('admin.dashboard', compact('companies', 'stats', 'planStats', 'recentActivities'));
    }

    public function switchToCompany(Request $request)
    {
        $company = User::on('mysql_general')
            ->where('role_name', 'Admin')
            ->findOrFail($request->company_id);

        // Store the super admin's original session data
        session(['admin_id' => auth()->id()]);

        // Login as company admin
        auth()->loginUsingId($company->id);

        return redirect()->route('company.dashboard')
            ->with('success', 'تم تبديل الحساب بنجاح');
    }

    public function returnToAdmin()
    {
        if ($adminId = session('admin_id')) {
            auth()->loginUsingId($adminId);
            session()->forget('admin_id');
            return redirect()->route('admin.dashboard')
                ->with('success', 'تم العودة إلى حساب المشرف بنجاح');
        }

        return redirect()->route('login');
    }

    public function updatePaymentStatus(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:mysql_general.user_payments,id',
            'status' => 'required|in:paid,pending,failed',
            'transaction_id' => 'nullable|string'
        ]);

        DB::connection('mysql_general')->beginTransaction();

        try {
            $payment = UserPayment::on('mysql_general')->findOrFail($request->payment_id);
            $payment->status = $request->status;
            
            if ($request->status === 'paid') {
                $payment->paid_at = now();
                if ($request->transaction_id) {
                    $payment->transaction_id = $request->transaction_id;
                }
            }
            
            $payment->save();

            // If payment is marked as paid, activate the subscription
            if ($request->status === 'paid') {
                $subscription = UserSubscription::on('mysql_general')->find($payment->subscription_id);
                if ($subscription) {
                    $subscription->status = 'active';
                    $subscription->save();
                }
            }

            DB::connection('mysql_general')->commit();
            return response()->json(['success' => true, 'message' => 'Payment status updated successfully']);
        } catch (\Exception $e) {
            DB::connection('mysql_general')->rollBack();
            return response()->json(['success' => false, 'message' => 'Error updating payment status'], 500);
        }
    }

    public function processPaymentGateway(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:mysql_general.user_payments,id',
            'transaction_id' => 'required|string',
            'status' => 'required|string'
        ]);

        DB::connection('mysql_general')->beginTransaction();

        try {
            $payment = UserPayment::on('mysql_general')->findOrFail($request->payment_id);
            
            // Update payment based on gateway response
            $payment->transaction_id = $request->transaction_id;
            $payment->status = $request->status === 'success' ? 'paid' : 'failed';
            $payment->payment_details = array_merge(
                $payment->payment_details ?? [], 
                ['gateway_response' => $request->all()]
            );
            
            if ($request->status === 'success') {
                $payment->paid_at = now();
            }
            
            $payment->save();

            // Activate subscription if payment successful
            if ($request->status === 'success') {
                $subscription = UserSubscription::on('mysql_general')->find($payment->subscription_id);
                if ($subscription) {
                    $subscription->status = 'active';
                    $subscription->save();
                }
            }

            DB::connection('mysql_general')->commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::connection('mysql_general')->rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function users()
    {
        $users = User::on('mysql_general')
            ->where('role_name', 'Admin')
            ->with(['subscriptions' => function($query) {
                $query->latest();
            }, 'payments' => function($query) {
                $query->latest();
            }])
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function showUser($userId)
    {
        $user = User::on('mysql_general')
            ->where('role_name', 'Admin')
            ->with([
                'subscriptions' => function($query) {
                    $query->latest();
                },
                'payments' => function($query) {
                    $query->latest();
                }
            ])
            ->findOrFail($userId);

        $subscriptionPlans = SubscriptionPlan::on('mysql_general')->get();

        return view('admin.users.show', compact('user', 'subscriptionPlans'));
    }

    public function resetUserPassword(Request $request, $userId)
    {
        $user = User::on('mysql_general')->findOrFail($userId);
        $newPassword = Str::random(10);
        
        $user->password = Hash::make($newPassword);
        $user->setConnection('mysql_general')->save();

        return response()->json([
            'success' => true,
            'message' => 'تم إعادة تعيين كلمة المرور بنجاح',
            'password' => $newPassword
        ]);
    }

    public function toggleUserStatus(Request $request, $userId)
    {
        $user = User::on('mysql_general')->findOrFail($userId);
        $user->is_active = !$user->is_active;
        $user->setConnection('mysql_general')->save();

        return response()->json([
            'success' => true,
            'message' => $user->is_active ? 'تم تفعيل المستخدم بنجاح' : 'تم تعطيل المستخدم بنجاح',
            'is_active' => $user->is_active
        ]);
    }

    public function reports(Request $request)
    {
        $query = User::on('mysql_general') ->with(['subscriptions' => function($query) {
            $query->latest();
        }, 'payments' => function($query) {
            $query->latest();
        }])->where('role_name', 'Admin');
        
        // Apply filters
        if ($request->filled('date_from')) {
            $query->whereHas('subscriptions', function($q) use ($request) {
                $q->where('starts_at', '>=', $request->date_from);
            });
        }
        if ($request->filled('date_to')) {
            $query->whereHas('subscriptions', function($q) use ($request) {
                $q->where('ends_at', '<=', $request->date_to);
            });
        }
        if ($request->filled('subscription_status')) {
            $query->whereHas('subscriptions', function($q) use ($request) {
                $q->where('status', $request->subscription_status);
            });
        }
        if ($request->filled('user_status')) {
            $query->where('is_active', $request->user_status);
        } 

        if ($request->filled('payment_status')) {
            $query->whereHas('payments', function($q) use ($request) {
                $q->where('status', $request->payment_status);
            });
        }
    
        if ($request->filled('subscription_plan')) {
            $query->whereHas('subscriptions', function($q) use ($request) {
                $q->where('subscription_plan_id', $request->subscription_plan);
            });
        }
            
        $users = $query->get();
        
        $subscriptionPlans = SubscriptionPlan::on('mysql_general')->where('is_active', true)->get();
        $stats = [
            'total_users' => $users->count(),
            'active_users' => $users->filter(function($user) {
                return $user->is_active;
            })->count(),
            'total_revenue' => $users->sum(function($user) {
                return $user->payments->where('status', 'paid')->sum('amount');
            }),
            'subscription_distribution' => $users->groupBy(function($user) {
                return $user->subscriptions->first()?->subscriptionPlan->name ?? 'No Plan';
            })->map->count(),
            'active_subscriptions' => $users->flatMap->subscriptions
                ->where('status', 'active')
                ->count(),
            'payment_status_distribution' => $users->flatMap->payments
                ->groupBy('status')
                ->map->count(),
            'pending_payments' => $users->flatMap->payments
                ->where('status', 'pending')
                ->sum('amount'),
            'pending_payments_count' => $users->flatMap->payments
                ->where('status', 'pending')
                ->count(),
            'reports' => $users->map(function($user) use ($request) {
                return [
                    'company_name' => $user->company_name,
                    'email' => $user->email,
                    'subscription_plan_name' => $user->subscriptions->first()?->subscriptionPlan->name,
                    'subscription_start' => $user->subscriptions->first()?->starts_at,
                    'subscription_end' => $user->subscriptions->first()?->ends_at,
                    'amount' => $user->payments->where('status', 'paid')->sum('amount'),
                    'payment_status' => $user->payments->where('status', 'paid')->first()?->status,
                    'is_active' => $user->is_active
                ];
            })->values()->all()
        ];
        
        return view('admin.reports.index', compact('users', 'stats', 'subscriptionPlans'));
    }
}
