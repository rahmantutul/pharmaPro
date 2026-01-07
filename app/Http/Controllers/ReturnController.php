<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseReturnRequest;
use App\Http\Requests\StoreSalesReturnRequest;
use App\Models\Customer;
use App\Models\Medicine;
use App\Models\PurchaseReturn;
use App\Models\SalesReturn;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\Supplier;
use App\Services\StockService;
use App\Traits\InvoiceGeneratorTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReturnController extends Controller
{
    use InvoiceGeneratorTrait;

    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    // ==================== SALES RETURN SECTION ====================
    
    /**
     * Show form to create sales return
     */
    public function sales_return_create()
    {
        $medicines = Medicine::with('supplier')->get();
        $customers = Customer::all();
        return view('dashboard.return.sales_return_create', compact('medicines', 'customers'));
    }

    /**
     * Display all sales returns with filters
     */
    public function sales_return_index(Request $request)
    {
        $query = SalesReturn::with(['medicine.supplier', 'customer']);

        // Apply filters
        if ($request->filled('medId')) {
            $query->where('medicineId', $request->medId);
        }

        if ($request->filled('invNo')) {
            $query->where('inv_no', 'LIKE', '%' . $request->invNo . '%');
        }

        if ($request->filled('customerId')) {
            $query->where('customerId', $request->customerId);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('return_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('return_date', '<=', $request->date_to);
        }

        $medicines = Medicine::all();
        $customers = Customer::all();
        $dataList = $query->latest()->paginate(50);
        
        // Calculate summary
        $totalReturns = $query->count();
        $totalAmount = $query->sum('total');

        return view('dashboard.return.sales_return_index', compact(
            'dataList', 
            'medicines', 
            'customers',
            'totalReturns',
            'totalAmount'
        ));
    }

    /**
     * Store new sales return
     */
    public function sales_return_store(StoreSalesReturnRequest $request)
    {
        // Validation handled by Request
        $validatedData = $request->validated();

        DB::beginTransaction();
        try {
            // Generate unique invoice number
            $invoiceNo = $this->generateInvoiceNumber(SalesReturn::class, 'SRET', 'return_date');
            
            // Logic: usually return date is Today
            $invoiceDate = now()->format('Y-m-d');
            $totalItems = 0;
            $totalAmount = 0;

            foreach ($validatedData['medicine'] as $key => $medicineId) {
                $qty = $validatedData['qty'][$key];
                $price = $validatedData['price'][$key];
                $total = $validatedData['total'][$key];
                $expireDate = Carbon::parse($validatedData['expire_date'][$key])->format('Y-m-d');

                // Create sales return record
                $salesReturn = SalesReturn::create([
                    'medicineId'     => $medicineId,
                    'inv_no'         => $invoiceNo,
                    'return_date'    => $invoiceDate,
                    'qty'            => $qty,
                    'price'          => $price,
                    'total'          => $total,
                    'customerId'     => $validatedData['customer'][$key] ?? null,
                ]);

                // Update stock (add returned items back to stock)
                $this->stockService->addStock(
                    $medicineId,
                    $qty,
                    $invoiceDate,
                    $expireDate,
                    $invoiceNo,
                    $salesReturn->id,
                    'sales_return'
                );

                // Create transaction (negative amount for return - refund to customer)
                Transaction::create([
                    'medicineId'  => $medicineId,
                    'customerId'  => $validatedData['customer'][$key] ?? null,
                    'supplierId'  => null,
                    'is_walking_customer' => !empty($validatedData['customer'][$key]) ? 0 : 1,
                    'amount'      => -($total), // Negative because it's a refund/return
                    'date'        => $invoiceDate,
                    'refId'       => $salesReturn->id,
                    'type'        => 'sales_return',
                ]);

                $totalItems++;
                $totalAmount += $total;
            }

            DB::commit();
            
            return redirect()
                ->route('return.sales.index')
                ->with('success', "Sales return created successfully! Invoice: {$invoiceNo}, Total Items: {$totalItems}, Amount: ৳{$totalAmount}");

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Sales return creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::check() ? Auth::id() : 'unknown'
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create sales return: ' . $e->getMessage());
        }
    }

    /**
     * Show single sales return details
     */
    public function sales_return_show($id)
    {
        $return = SalesReturn::with(['medicine.supplier', 'customer'])
            ->findOrFail($id);
        
        return view('dashboard.return.sales_return_show', compact('return'));
    }

    /**
     * Delete sales return
     */
    public function sales_return_destroy($id)
    {
        DB::beginTransaction();
        try {
            $dataInfo = SalesReturn::findOrFail($id);
            $invNo = $dataInfo->inv_no;

            // Delete related stock entries
            Stock::where('inv_no', $invNo)->delete();

            // Delete related transactions
            Transaction::where('refId', $id)
                ->where('type', 'sales_return')
                ->delete();

            // Delete the return record
            $dataInfo->delete();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Sales return deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Sales return deletion failed', [
                'error' => $e->getMessage(),
                'id' => $id,
                'user_id' => Auth::id()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to delete sales return: ' . $e->getMessage());
        }
    }

    // ==================== PURCHASE RETURN SECTION ====================
    
    /**
     * Show form to create purchase return
     */
    public function purchase_return_create()
    {
        $medicines = Medicine::with('supplier')->get();
        $suppliers = Supplier::all();
        return view('dashboard.return.purchase_return_create', compact('medicines', 'suppliers'));
    }

    /**
     * Display all purchase returns with filters
     */
    public function purchase_return_index(Request $request)
    {
        $query = PurchaseReturn::with(['medicine.supplier', 'supplier']);

        // Apply filters
        if ($request->filled('medId')) {
            $query->where('medicineId', $request->medId);
        }

        if ($request->filled('invNo')) {
            $query->where('inv_no', 'LIKE', '%' . $request->invNo . '%');
        }

        if ($request->filled('supplierId')) {
            $query->where('supplierId', $request->supplierId);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('return_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('return_date', '<=', $request->date_to);
        }

        $medicines = Medicine::all();
        $suppliers = Supplier::all();
        $invList = PurchaseReturn::select('id', 'inv_no')->distinct()->get();
        $dataList = $query->latest()->paginate(50);
        
        // Calculate summary
        $totalReturns = $query->count();
        $totalAmount = $query->sum('total');

        return view('dashboard.return.purchase_return_index', compact(
            'dataList', 
            'medicines', 
            'suppliers',
            'invList',
            'totalReturns',
            'totalAmount'
        ));
    }

    /**
     * Store new purchase return
     */
    public function purchase_return_store(StorePurchaseReturnRequest $request)
    {
        // Validation handled by Request
        $validatedData = $request->validated();

        DB::beginTransaction();
        try {
            // Generate unique invoice number
            $invoiceNo = $this->generateInvoiceNumber(PurchaseReturn::class, 'PRET', 'return_date');
            
            $invoiceDate = now()->format('Y-m-d');
            $totalItems = 0;
            $totalAmount = 0;

            foreach ($validatedData['medicine'] as $key => $medicineId) {
                $qty = $validatedData['qty'][$key];
                $price = $validatedData['price'][$key];
                $total = $validatedData['total'][$key];
                $supplierId = $validatedData['supplier_id'][$key];
                $expireDate = Carbon::parse($validatedData['expire_date'][$key])->format('Y-m-d');

                // Check available stock
                $availableStock = Stock::where('medicineId', $medicineId)->sum('qty');
                
                if ($availableStock < $qty) {
                    throw new \Exception("Insufficient stock for return. Medicine ID: {$medicineId}. Available: {$availableStock}, Requested: {$qty}");
                }

                // Create purchase return record
                $purchaseReturn = PurchaseReturn::create([
                    'medicineId'     => $medicineId,
                    'inv_no'         => $invoiceNo,
                    'return_date'    => $invoiceDate,
                    'qty'            => $qty,
                    'price'          => $price,
                    'total'          => $total,
                    'supplierId'     => $supplierId,
                ]);

                // Update stock (reduce stock - negative quantity)
                $this->stockService->reduceStock(
                    $medicineId,
                    $qty,
                    $invoiceDate,
                    $expireDate,
                    $invoiceNo,
                    $purchaseReturn->id,
                    'purchase_return'
                );

                // Create transaction (positive amount - refund from supplier expected)
                Transaction::create([
                    'medicineId'  => $medicineId,
                    'customerId'  => null,
                    'supplierId'  => $supplierId,
                    'is_walking_customer' => 0,
                    'amount'      => $total, // Positive because we expect refund from supplier
                    'date'        => $invoiceDate,
                    'refId'       => $purchaseReturn->id,
                    'type'        => 'purchase_return',
                ]);

                $totalItems++;
                $totalAmount += $total;
            }

            DB::commit();
            
            return redirect()
                ->route('return.purchase.index')
                ->with('success', "Purchase return created successfully! Invoice: {$invoiceNo}, Total Items: {$totalItems}, Amount: ৳{$totalAmount}");

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Purchase return creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create purchase return: ' . $e->getMessage());
        }
    }

    /**
     * Show single purchase return details
     */
    public function purchase_return_show($id)
    {
        $return = PurchaseReturn::with(['medicine.supplier', 'supplier'])
            ->findOrFail($id);
        
        return view('dashboard.return.purchase_return_show', compact('return'));
    }

    /**
     * Delete purchase return
     */
    public function purchase_return_destroy($id)
    {
        DB::beginTransaction();
        try {
            $dataInfo = PurchaseReturn::findOrFail($id);
            $invNo = $dataInfo->inv_no;

            // Delete related stock entries
            Stock::where('inv_no', $invNo)->delete();

            // Delete related transactions
            Transaction::where('refId', $id)
                ->where('type', 'purchase_return')
                ->delete();

            // Delete the return record
            $dataInfo->delete();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Purchase return deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Failed to delete purchase return: ' . $e->getMessage());
        }
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get return statistics (for dashboard)
     */
    public function getReturnStatistics(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth();
        $endDate = $request->end_date ?? now()->endOfMonth();

        $salesReturns = SalesReturn::whereBetween('return_date', [$startDate, $endDate])
            ->selectRaw('COUNT(*) as count, SUM(total) as total_amount')
            ->first();

        $purchaseReturns = PurchaseReturn::whereBetween('return_date', [$startDate, $endDate])
            ->selectRaw('COUNT(*) as count, SUM(total) as total_amount')
            ->first();

        return response()->json([
            'sales_returns' => $salesReturns,
            'purchase_returns' => $purchaseReturns,
        ]);
    }

    /**
     * Check if medicine can be returned
     */
    public function checkReturnEligibility(Request $request)
    {
        $medicineId = $request->medicine_id;
        $qty = $request->qty;
        $type = $request->type; // 'sales' or 'purchase'

        $availableStock = Stock::where('medicineId', $medicineId)->sum('qty');

        if ($type === 'purchase' && $availableStock < $qty) {
            return response()->json([
                'eligible' => false,
                'message' => "Insufficient stock. Available: {$availableStock}",
                'available_stock' => $availableStock
            ], 400);
        }

        return response()->json([
            'eligible' => true,
            'available_stock' => $availableStock
        ]);
    }
}