<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Models\Category;
use App\Models\Customer;
use App\Models\GeneralSetting;
use App\Models\Medicine;
use App\Models\PaymentMethod;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Services\StockService;
use App\Traits\InvoiceGeneratorTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    use InvoiceGeneratorTrait;

    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Display list of all sales with filters
     */
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'details.medicine', 'paymentMethod']);

        // Filter by medicine
        if ($request->filled('medId')) {
            $query->whereHas('details', function($q) use ($request) {
                $q->where('medicineId', $request->medId);
            });
        }

        // Filter by invoice number
        if ($request->filled('invNo')) {
            $query->where('invoice_no', 'LIKE', '%' . $request->invNo . '%');
        }

        // Filter by customer
        if ($request->filled('customer')) {
            $query->where('customerId', $request->customer);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            if ($request->payment_status == 'paid') {
                $query->where('due_amount', 0);
            } elseif ($request->payment_status == 'partial') {
                $query->where('due_amount', '>', 0)->where('paid_amount', '>', 0);
            } elseif ($request->payment_status == 'due') {
                $query->where('paid_amount', 0);
            }
        }

        $dataList = $query->latest()->paginate(15);

        // Calculate statistics
        $totalSales = $query->sum('grand_total');
        $totalPaid = $query->sum('paid_amount');
        $totalDue = $query->sum('due_amount');

        $medicines = Medicine::all();
        $customers = Customer::all();
        $general_setting = GeneralSetting::first();
        
        $invList = Sale::select('id', 'invoice_no')->latest()->get();

        if ($request->ajax()) {
            return view('dashboard.sale.sales_list_partial', compact(
                'dataList',
                'general_setting'
            ))->render();
        }

        return view('dashboard.sale.sales_index', compact(
            'dataList',
            'medicines',
            'customers',
            'invList',
            'general_setting',
            'totalSales',
            'totalPaid',
            'totalDue'
        ));
    }

    /**
     * Show form to create new sale
     */
    public function create()
    {
        // Generate unique invoice number
        // Using trait method
        $invoice = $this->generateInvoiceNumber(Sale::class, 'INV', 'invoice_date');
        $today = now()->format('Y-m-d');
        
        // Get payment methods and find default (COD or first one)
        $methods = PaymentMethod::orderBy('name')->get();
        $defaultMethod = PaymentMethod::where('name', 'LIKE', '%cash%')
            ->orWhere('name', 'LIKE', '%COD%')
            ->first();
        
        // If no cash/COD method found, use first available method
        if (!$defaultMethod && $methods->count() > 0) {
            $defaultMethod = $methods->first();
        }
        
        $customers = Customer::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        
        // Get medicines with available stock (not expired)
        $medicines = Medicine::whereHas('stocks', function($query) {
            $query->where('expire_date', '>', now()->toDateString());
        })
        ->withSum(['stocks as total_stock' => function($query) {
            $query->where('expire_date', '>', now()->toDateString());
        }], 'qty')
        ->with(['supplier', 'category'])
        ->orderBy('name')
        ->paginate(30);
        
        return view('dashboard.sale.order_create', compact(
            'suppliers',
            'today',
            'invoice',
            'medicines',
            'methods',
            'categories',
            'customers',
            'defaultMethod'
        ));
    }
    
    /**
     * Filter medicines by search criteria
     */
    public function filter(Request $request)
    {
        $query = Medicine::query();

        // Filter by name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter by generic name
        if ($request->filled('generic')) {
            $query->where('generic_name', 'like', '%' . $request->generic . '%');
        }

        // Filter by supplier
        if ($request->filled('supplier')) {
            $query->where('supplierId', $request->supplier);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('categoryId', $request->category);
        }

        // Only show medicines with valid non-expired stock
        $query->whereHas('stocks', function($q) {
            $q->where('expire_date', '>', now()->toDateString());
        });

        $medicines = $query->withSum(['stocks as total_stock' => function($q) {
            $q->where('expire_date', '>', now()->toDateString());
        }], 'qty')
        ->with(['supplier', 'category'])
        ->orderBy('name')
        ->paginate(30);

        return view('dashboard.sale.search_result', compact('medicines'))->render();
    }

    /**
     * Add medicine to cart (Get stock details)
     */
    public function addToCart(Request $request)
    {
        $medicineId = $request->id;
        
        // Get medicine details
        $medicine = Medicine::with('supplier')->find($medicineId);
        
        if (!$medicine) {
            return response()->json([
                'error' => 'Medicine not found'
            ], 404);
        }

        // Get available stock grouped by expiry date (FIFO - First In First Out)
        $stockEntries = Stock::where('medicineId', $medicineId)
            ->where('expire_date', '>', now()->toDateString())
            ->select('expire_date', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('expire_date')
            ->havingRaw('SUM(qty) > 0')
            ->orderBy('expire_date', 'asc') // Oldest first (FIFO)
            ->get();

        if ($stockEntries->isEmpty()) {
            return response()->json([
                'error' => 'No stock available',
                'message' => 'This medicine has no available stock or all stock has expired'
            ], 400);
        }

        // Calculate total available quantity
        $totalAvailable = $stockEntries->sum('total_qty');

        return response()->json([
            'success' => true,
            'medicine' => $medicine,
            'stocks' => $stockEntries,
            'total_available' => $totalAvailable
        ]);
    }

    /**
     * Store new sale
     */
    public function store(StoreSaleRequest $request)
    {
        // Validation is handled by StoreSaleRequest

        // Double check stock availability for safety
        foreach ($request->medicineId as $key => $medicineId) {
            $expireDate = $request->expire_date[$key];
            $requestedQty = $request->qty[$key];
            
            $availableStock = Stock::where('medicineId', $medicineId)
                ->where('expire_date', $expireDate)
                ->where('expire_date', '>', now()->toDateString())
                ->sum('qty');
            
            if ($availableStock < $requestedQty) {
                $medicine = Medicine::find($medicineId);
                return redirect()->back()
                    ->withErrors([
                        'stock' => "Insufficient stock for {$medicine->name}. Available: {$availableStock}, Requested: {$requestedQty}"
                    ])
                    ->withInput();
            }
        }

        DB::beginTransaction();

        try {
            $isWalkingCustomer = !$request->customerId ? 1 : 0;

            // Create main sale record
            $sale = Sale::create([
                'invoice_date'      => $request->invoice_date,
                'invoice_no'        => $request->invoice_no,
                'customerId'        => $request->customerId,
                'is_walking_customer' => $isWalkingCustomer,
                'grand_total'       => $request->grand_total,
                'invoice_discount'  => $request->invoice_discount ?? 0,
                'discount_type'     => $request->discount_type ?? 0,
                'payable_total'     => $request->payable_total,
                'paid_amount'       => $request->paid_amount,
                'due_amount'        => $request->due_amount,
                'paymentId'         => $request->paymentId,
                'status'            => $request->due_amount > 0 ? 'partial' : 'paid',
                'created_by'        => Auth::id(),
            ]);

            $totalItems = 0;

            // Create sale details and update stock
            foreach ($request->medicineId as $key => $medicineId) {
                // Create sale detail
                SaleDetails::create([
                    'medicineId'  => $medicineId,
                    'salesId'     => $sale->id,
                    'expiry_date' => $request->expire_date[$key],
                    'sell_price'  => $request->price[$key],
                    'qty'         => $request->qty[$key],
                    'subtotal'    => $request->subtotal[$key],
                    'discount'    => $request->discount[$key] ?? 0,
                    'total'       => $request->total[$key],
                ]);

                // Update stock using Service
                $this->stockService->reduceStock(
                    $medicineId,
                    $request->qty[$key],
                    $request->invoice_date,
                    $request->expire_date[$key],
                    $request->invoice_no,
                    $sale->id,
                    'sales'
                );

                $totalItems++;
            }

            // Create transaction for PAID amount (if any payment made)
            if ($request->paid_amount > 0) {
                Transaction::create([
                    'medicineId'  => null,
                    'customerId'  => $request->customerId ?? null,
                    'supplierId'  => null,
                    'is_walking_customer' => $isWalkingCustomer,
                    'amount'      => $request->paid_amount, // Positive - money received
                    'date'        => $request->invoice_date,
                    'refId'       => $sale->id,
                    'type'        => 'sale', // This is payment received
                ]);
            }

            // Create transaction for DUE amount (if customer has due)
            if ($request->due_amount > 0 && $request->customerId) {
                Transaction::create([
                    'medicineId'  => null,
                    'customerId'  => $request->customerId,
                    'supplierId'  => null,
                    'is_walking_customer' => 0,
                    'amount'      => $request->due_amount, // Positive - customer owes us
                    'date'        => $request->invoice_date,
                    'refId'       => $sale->id,
                    'type'        => 'customer_due',
                ]);
            }

            DB::commit();

            // Redirect based on print preference
            if ($request->input('print_invoice')) {
                return redirect()
                    ->route('sales.order.invoice', ['id' => $sale->id, 'print' => 'true'])
                    ->with('success', "Sale completed! Invoice: {$sale->invoice_no}, Items: {$totalItems}, Total: ৳{$sale->grand_total}");
            } else {
                return redirect()
                    ->route('sales.order.create')
                    ->with('success', "Sale completed successfully! Invoice: {$sale->invoice_no}, Total: ৳{$sale->grand_total}");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Sale creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            
            return redirect()->back()
                ->withErrors(['error' => 'Failed to process sale: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Print invoice
     */
    public function print_invoice(Request $request)
    {
        $dataInfo = Sale::with(['details.medicine', 'customer', 'paymentMethod'])
            ->find($request->id);
        
        if (!$dataInfo) {
            return redirect()->route('sales.index')
                ->with('error', 'Invoice not found');
        }
        
        $general_setting = GeneralSetting::first();
        
        return view('dashboard.sale.invoice', compact('dataInfo', 'general_setting'));
    }

    /**
     * Download PDF invoice
     */
    public function downloadInvoicePDF(Request $request)
    {
        $dataInfo = Sale::with(['details.medicine', 'customer', 'paymentMethod'])
            ->find($request->id);
            
        if (!$dataInfo) {
            return redirect()->back()->with('error', 'Invoice not found');
        }

        $general_setting = GeneralSetting::first();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dashboard.sale.invoice', compact('dataInfo', 'general_setting'));
        return $pdf->download('invoice-' . $dataInfo->invoice_no . '.pdf');
    }

    /**
     * Get sale details
     */
    public function details($id)
    {
        $sale = Sale::with([
            'details.medicine.supplier',
            'customer',
            'paymentMethod'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'sale' => $sale,
            'details' => $sale->details
        ]);
    }

    /**
     * Delete sale
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $sale = Sale::findOrFail($id);
            $invoiceNo = $sale->invoice_no;

            // Delete stock entries
            Stock::where('inv_no', $invoiceNo)->delete();

            // Delete transactions
            Transaction::where('refId', $id)
                ->whereIn('type', ['sale', 'customer_due'])
                ->delete();

            // Delete sale details
            SaleDetails::where('salesId', $id)->delete();

            // Delete sale
            $sale->delete();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Sale deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Failed to delete sale: ' . $e->getMessage());
        }
    }

    /**
     * Update payment for a sale
     */
    public function updatePayment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|exists:payment_methods,id',
            'payment_date'   => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            $sale = Sale::findOrFail($id);

            if ($sale->due_amount <= 0) {
                return redirect()->back()
                    ->with('error', 'This sale has no due amount');
            }

            if ($request->payment_amount > $sale->due_amount) {
                return redirect()->back()
                    ->with('error', 'Payment amount cannot exceed due amount');
            }

            // Update sale amounts
            $sale->paid_amount += $request->payment_amount;
            $sale->due_amount -= $request->payment_amount;
            $sale->status = $sale->due_amount > 0 ? 'partial' : 'paid';
            $sale->save();

            // Create transaction for this payment
            Transaction::create([
                'medicineId'  => null,
                'customerId'  => $sale->customerId,
                'supplierId'  => null,
                'is_walking_customer' => $sale->is_walking_customer,
                'amount'      => $request->payment_amount,
                'date'        => $request->payment_date,
                'refId'       => $sale->id,
                'type'        => 'sale_payment', // New type for additional payments
            ]);

            // Reduce customer due transaction (if tracking)
            Transaction::create([
                'medicineId'  => null,
                'customerId'  => $sale->customerId,
                'supplierId'  => null,
                'is_walking_customer' => 0,
                'amount'      => -($request->payment_amount), // Negative - reducing due
                'date'        => $request->payment_date,
                'refId'       => $sale->id,
                'type'        => 'customer_due_payment',
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', "Payment of ৳{$request->payment_amount} recorded successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Payment update failed', [
                'error' => $e->getMessage(),
                'sale_id' => $id,
                'user_id' => Auth::id()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to update payment: ' . $e->getMessage());
        }
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get sales statistics
     */
    public function getStatistics(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth();
        $endDate = $request->end_date ?? now()->endOfMonth();

        $stats = Sale::whereBetween('invoice_date', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as total_sales,
                SUM(grand_total) as total_amount,
                SUM(paid_amount) as total_paid,
                SUM(due_amount) as total_due,
                SUM(invoice_discount) as total_discount
            ')
            ->first();

        return response()->json([
            'success' => true,
            'statistics' => $stats
        ]);
    }
}