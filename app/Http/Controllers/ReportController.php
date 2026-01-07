<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\GeneralSetting;
use App\Models\Purchase;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseOrder;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\ViewCustomerSalesSummery;
use App\Models\ViewSupplierSalesSummery;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{


    public function sales_report(Request $request){
        $fromdate = date('Y-m-d'); 
        $todate = date('Y-m-d');

        $customers = Customer::all();
        $query = Sale::with('details','customer');

        if($request->filled('fromDate') && $request->filled('toDate')){
            $fromdate = $request->fromDate;
            $todate=$request->toDate;
            $query->whereBetween('invoice_date', [$fromdate,$todate]);
        }

        if($request->filled('customerId')){
            $query->where('customerId', $request->customerId);
        }

        // Calculate grand totals for ALL matching records
        $grandStats = (clone $query)->selectRaw('
            SUM(grand_total) as total_amount,
            SUM(invoice_discount) as total_discount,
            SUM(payable_total) as payable_total,
            SUM(paid_amount) as paid_total,
            SUM(due_amount) as due_total
        ')->first();
        
        if ($request->input('submit') == "pdf") {
            $dataList = $query->get();
            $general_info = GeneralSetting::first();
            $pdf = PDF::loadView('dashboard.reports.sales_report_pdf', compact('dataList','fromdate','todate','general_info'));
            return $pdf->download('sales_report_'.'.pdf');
        }

        $dataList = $query->paginate(15);

        return view('dashboard.reports.sales_report',compact('dataList','customers','fromdate','todate', 'grandStats'));
    }
    public function purchase_report(Request $request){
        $fromdate = date('Y-m-d'); 
        $todate = date('Y-m-d');
        $suppliers = Supplier::all();
        $query = PurchaseInvoice::with('details','supplier');

        if($request->filled('fromDate') && $request->filled('toDate')){
            $fromdate = $request->fromDate;
            $todate=$request->toDate;
            $query->whereBetween('invoice_date', [$fromdate,$todate]);
        }

        if($request->filled('supplierId')){
            $query->where('supplierId', $request->supplierId);
        }

        // Calculate grand totals for ALL matching records
        $grandStats = (clone $query)->selectRaw('
            SUM(total_amount) as total_amount,
            SUM(total_discount) as total_discount,
            SUM(paid_amount) as paid_total,
            SUM(due_amount) as due_total
        ')->first();
        if ($request->input('submit') == "pdf") {
            $dataList = $query->get();
            $general_info = GeneralSetting::first();
            $pdf = PDF::loadView('dashboard.reports.purchase_report_pdf', compact('dataList','fromdate','todate','general_info'));
            return $pdf->download('purchase_report_'.'.pdf');
        }

        $dataList = $query->paginate(15);

        return view('dashboard.reports.purchase_report',compact('dataList','suppliers','fromdate','todate', 'grandStats'));
    }

    public function customer_due_report(Request $request){
        
        $customers = Customer::all();
        $query = ViewCustomerSalesSummery::where('total_due','>',0);

        if($request->filled('customerId')){
            $query->where('custId', $request->customerId);
        }

        $grandStats = (clone $query)->selectRaw('
            SUM(grand_total) as payable_total,
            SUM(paid_amount) as paid_total,
            SUM(total_due) as due_total
        ')->first();

        if ($request->input('submit') == "pdf") {
            $dataList = $query->groupBy('name')->get();
            $general_info = GeneralSetting::first();
            $pdf = PDF::loadView('dashboard.reports.customer_due_report_pdf', compact('dataList','general_info'));
            return $pdf->download('customer_due_report'.'.pdf');
        }

        $dataList = $query->groupBy('name')->paginate(15);
        return view('dashboard.reports.customer_due_report',compact('dataList','customers', 'grandStats'));
    }


    public function supplier_due_report(Request $request){

        $suppliers = Supplier::all();

        $query = ViewSupplierSalesSummery::where('total_due','>',0);

        if($request->filled('supplierId')){
            $query->where('id', $request->supplierId);
        }

        $grandStats = (clone $query)->selectRaw('
            SUM(grand_total) as payable_total,
            SUM(paid_amount) as paid_total,
            SUM(total_due) as due_total
        ')->first();

        if ($request->input('submit') == "pdf") {
            $dataList = $query->get();
            $general_info = GeneralSetting::first();
            $pdf = PDF::loadView('dashboard.reports.supplier_due_report_pdf', compact('dataList','general_info'));
            return $pdf->download('supplier_due_report'.'.pdf');
        }

        $dataList = $query->paginate(15);

        return view('dashboard.reports.supplier_due_report',compact('dataList','suppliers', 'grandStats'));
    }
}
