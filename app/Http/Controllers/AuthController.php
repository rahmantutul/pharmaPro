<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\GeneralSetting;
use App\Models\PurchaseInvoice;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.home');
        } else {
            return redirect()->route('login');
        }
    }

    public function showLoginForm()
    {
        return view('dashboard.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        // Attempt to log in using the provided credentials
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->intended(route('admin.dashboard'));
        }

        // If login fails, redirect back with an error
        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('login');
    }

    public function dashboard()
    {
        $this_month_sale = Sale::whereYear('invoice_date', Carbon::now()->year)
        ->whereMonth('invoice_date', Carbon::now()->month)
        ->sum('payable_total');

        $this_month_due = Sale::whereYear('invoice_date', Carbon::now()->year)
        ->whereMonth('invoice_date', Carbon::now()->month)
        ->sum('due_amount');

        $this_month_expense = Expense::whereYear('date', Carbon::now()->year)
        ->whereMonth('date', Carbon::now()->month)
        ->sum('amount');

        $this_month_purchase = PurchaseInvoice::whereYear('invoice_date', Carbon::now()->year)
        ->whereMonth('invoice_date', Carbon::now()->month)
        ->sum('total_amount');

        // Year wise data 
        $this_year_sale = Sale::whereYear('invoice_date', Carbon::now()->year)
        ->sum('payable_total');

        $this_year_due = Sale::whereYear('invoice_date', Carbon::now()->year)
        ->sum('due_amount');

        $this_year_expense = Expense::whereYear('date', Carbon::now()->year)
        ->sum('amount');

        $this_year_purchase = PurchaseInvoice::whereYear('invoice_date', Carbon::now()->year)
        ->sum('total_amount');

        // Overall Data 
        $sale = Sale::sum('payable_total');

        $due = Sale::sum('due_amount');

        $expense = Expense::sum('amount');

        $purchase = PurchaseInvoice::sum('total_amount');

        $top_buying_customers = Sale::select('customerId')
            ->selectRaw('SUM(payable_total) as total_spent')
            ->with('customer') // Load related customer information
            ->groupBy('customerId')
            ->orderByDesc('total_spent')
            ->paginate(10, ['*'], 'page_customers');
        
        $top_sold_medicines = SaleDetails::select('medicineId')
            ->selectRaw('SUM(qty) as total_quantity')
            ->with('medicine','medicine.supplier') // Load related medicine information
            ->groupBy('medicineId')
            ->orderByDesc('total_quantity')
            ->paginate(10, ['*'], 'page_sold');

       $alertDay = GeneralSetting::select('expiryalert')->first()->expiryalert;
    $currentDate = Carbon::now();
    $targetDate = $currentDate->addDays($alertDay)->toDateString();

    // Execute the query with paginate()
    $upcoming_expire_medicine = Stock::where('expire_date', '=', $targetDate)
        ->select('medicineId', 'expire_date', DB::raw('SUM(qty) as total_qty'))
        ->groupBy('medicineId', 'expire_date')
        ->with('medicine', 'medicine.supplier', 'medicine.category')
        ->paginate(10, ['*'], 'page_upcoming');

    $expired_medicine = Stock::where('expire_date', '<', date('Y-m-d'))
        ->select('medicineId', 'expire_date', DB::raw('SUM(qty) as total_qty'))
        ->groupBy('medicineId', 'expire_date')
        ->with('medicine','medicine.supplier','medicine.category')
        ->paginate(10, ['*'], 'page_expired');

    return view('dashboard.pages.index', compact(
        'this_month_sale',
        'this_month_due',
        'this_month_expense',
        'this_month_purchase',
        'this_year_sale',
        'this_year_due',
        'this_year_expense',
        'this_year_purchase',
        'sale',
        'due',
        'expense',
        'purchase',
        'top_buying_customers',
        'top_sold_medicines',
        'upcoming_expire_medicine',
        'expired_medicine'
    ));
}
}
