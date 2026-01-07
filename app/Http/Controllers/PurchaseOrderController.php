<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseInvoiceRequest;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Mail\PurchaseOrder as MailPurchaseOrder;
use App\Models\Medicine;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceDetails;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Services\StockService;
use App\Traits\InvoiceGeneratorTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class PurchaseOrderController extends Controller
{
    use InvoiceGeneratorTrait;

    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index(Request $request)
    {
        $query = PurchaseOrder::with('supplier','purchase_details','purchase_details.medicine');

        if($request->medId){
            $query->whereHas('purchase_details', function($q) use ($request){
                $q->where('medicineId', $request->medId);
            });
        }

        if ($request->invNo) {
            $query->where('id',$request->invNo);
        }

        $invList = PurchaseOrder::select('id')->get();
        $medicines= Medicine::all();
        $dataList = $query->latest()->paginate(15);
        return view('dashboard.purchase.order_index',compact('dataList','medicines','invList'));
    }

    public function create()
    {
        $medicines= Medicine::all();
        $suppliers = Supplier::all();
        return view('dashboard.purchase.order_create',compact('medicines','suppliers'));
    }

    public function store(StorePurchaseOrderRequest $request){
            // Validate the incoming request data (handled by Request class)
            $validatedData = $request->validated();

            // Insert the order with the grand total
            $order = PurchaseOrder::create([
                'order_date'=>date('Y-m-d'),
                'order_time'=> now()->toTimeString(),
                'order_by'=> Auth::user()->name,
                'supplierId' => $validatedData['supplier'],
                'grand_total' => $validatedData['grandTotal'],
            ]);

            // Loop through the arrays and insert each item
            foreach ($validatedData['medicine'] as $index => $medicine) {
                PurchaseOrderDetail::create([
                    'order_id' => $order->id,
                    'medicineId' => $medicine,
                    'qty' => $validatedData['qty'][$index],
                    'price' => $validatedData['price'][$index],
                    'total' => $validatedData['total'][$index],
                ]);
            }
             // Retrieve supplier's email
            $supplier = Supplier::find($validatedData['supplier']);
            $supplierEmail = $supplier->email;

            // Send email to the supplier's email
            // Note: Leaving this as-is, but in production this should be queued.
            Mail::to($supplierEmail)->send(new MailPurchaseOrder($validatedData));
            return redirect()->back()->with('success', 'Order created successfully.');
    }

    public function destroy($id){
        $childData = PurchaseOrderDetail::where('order_id',$id)->delete();
        if($childData){
            $parentData = PurchaseOrder::find($id)->delete();
            if($parentData){
                return redirect()->back()->with('success', 'Deleted successfully.');
            }else{
                return redirect()->back()->with('error', 'Order Something went wrong!');
            }
        }else{
            return redirect()->back()->with('error', 'Order Something went wrong!');
        }
    }

    public function invoice($id){
        $medicines= Medicine::all();
        $suppliers = Supplier::all();
        $dataInfo = PurchaseOrder::where('id',$id)->with('purchase_details','purchase_details.medicine','purchase_details.medicine.supplier')->first();
        return view('dashboard.purchase.order_invoice',compact('medicines','dataInfo','suppliers'));
    }

    public function invoice_store(StorePurchaseInvoiceRequest $request){
            // Validate the incoming request (handled by Request class)
            $validatedData = $request->validated();
    
            $purchaseId = $request->input('purchaseId');

            if($purchaseId){
                $direct_invoice = 0;
            }else{
                $direct_invoice = 1;
            }

            // Generate Invoice No using Trait
            // Using 'PUR' prefix and 'purchase_invoices' table logic if we want consistency
            // The trait expects a model class. The original logic used PurchaseInvoice.
            $invoice_no = $this->generateInvoiceNumber(PurchaseInvoice::class, 'PUR', 'invoice_date');
            
            $invoice_date = date('Y-m-d');
            
            // Store the parent invoice data
            $invoice = PurchaseInvoice::create([
                'invoice_no'    => $invoice_no,
                'discount_type' => $validatedData['discount_type'] ?? null,
                'total_discount'=> $validatedData['discount'] ?? 0,
                'invoice_date'=> $invoice_date,
                'supplierId'=>   $request->supplier,
                'direct_invoice'=> $direct_invoice,
                'total_amount'  => $validatedData['grandTotal'] ?? 0,
                'due_amount'  => $validatedData['dueAmount'] ?? 0,
                'paid_amount'  => $validatedData['paidAmount'] ?? 0,
            ]);

            // Store the child details for the invoice
            foreach ($validatedData['medicine'] as $key => $medicineId) {
                // Reformat only if it's set
                $expireDate = $request->expire_date[$key];
                $formattedExpireDate = Carbon::parse($expireDate)->format('Y-m-d');
                
                PurchaseInvoiceDetails::create([
                    'invoice_id'   => $invoice->id,
                    'medicineId'  => $medicineId,
                    'expire_date'  => $formattedExpireDate,
                    'supplier_id'  => $request->supplier, // Access supplier_id by index
                    'qty'          => $request->qty[$key],
                    'price'        => $request->price[$key],
                    'total'        => $request->total[$key],
                ]);

                // Stock Update using Service (This ensures Type is 'purchase')
                $this->stockService->addStock(
                    $medicineId,
                    $request->qty[$key],
                    date('Y/m/d'),
                    $formattedExpireDate,
                    $invoice_no,
                    $invoice->id,
                    'purchase' // Explicitly lowercase
                );
            }

            if(isset($invoice)){
                if($validatedData['dueAmount'] > 0){
                    Transaction::create([
                        'medicineId' => NULL,
                        'supplierId' => $request->supplier,
                        'date' => date('Y/m/d'),
                        'type' => 'supplier_due',
                        'refId' => $invoice->id,
                        'amount' => $validatedData['dueAmount']
                    ]);
               }
            }

            if($purchaseId){
                PurchaseOrder::find($purchaseId)->update(['is_invoiced' => 1]);
            }
            
            return redirect()->route('purchase.order.invoice.list')->with('success', 'Purchase invoice saved successfully!!');
            
    }

    public function invoice_list(Request $request){

        $query= PurchaseInvoice::with('details','details.medicine','details.medicine.supplier');

        if($request->medId){
            $query->whereHas('details', function($q) use ($request){
                $q->where('medicineId', $request->medId);
            });
        }

        if ($request->invNo) {
            $query->where('id',$request->invNo);
        }

        $medicines= Medicine::all();
        $invList = PurchaseInvoice::select('id','invoice_no')->get();
        $dataList = $query->latest()->paginate(15);

        return view('dashboard.purchase.invoice_index',compact('dataList','medicines','invList'));
    }

    public function print_invoice($id){
        $dataInfo = PurchaseInvoice::where('id',$id)->with('details','details.medicine','details.medicine.supplier')->first();
        return view('dashboard.purchase.print_invoice',compact('dataInfo'));
    }
    public function direct_invoice(){
        $medicines= Medicine::all();
        $suppliers = Supplier::all();
        return view('dashboard.purchase.direct_invoice',compact('medicines','suppliers'));
    }
    public function download_invoice($id){
        $dataInfo = PurchaseInvoice::with('details.medicine.supplier')->findOrFail($id);
        // Share data with the view
        $pdf = Pdf::loadView('dashboard.purchase.print_invoice', compact('dataInfo'));
        // Return the generated PDF as a download
        return $pdf->download('purchase_invoice_' . $id . '.pdf');
    }
}
