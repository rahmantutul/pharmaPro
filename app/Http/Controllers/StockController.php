<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\Medicine;
use App\Models\Stock;
use App\Models\Supplier;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function expired_medicine(Request $request){

        $medicines = Medicine::all();
        $suppliers = Supplier::all();
        $fromdate = date('Y-m-d'); 
        $todate = date('Y-m-d');

        $query = Stock::where('expire_date', '<', date('Y-m-d'))
        ->select('medicineId', 'expire_date', DB::raw('SUM(qty) as total_qty'))
        ->groupBy('medicineId', 'expire_date')
        ->with('medicine','medicine.supplier','medicine.category');

        if($request->filled('fromDate') && $request->filled('toDate')){
            $fromdate = $request->fromDate;
            $todate=$request->toDate;
            $query->whereBetween('expire_date', [$fromdate,$todate]);
        }

       // To filter by supplierId in the related 'medicine' model:
        if ($request->filled('supplierId')) {
            $query->whereHas('medicine', function ($query) use ($request) {
                $query->where('supplierId', $request->supplierId);
            });
        }

        if($request->filled('medicineId')){
            $query->where('medicineId', $request->medicineId);
        }
        
       $dataList= $query->get();

        return view('dashboard.stock.expired_medicine',compact('dataList','suppliers','fromdate','todate','medicines'));
    }
    public function upcoming_expired(Request $request){
        $medicines = Medicine::all();
        $suppliers = Supplier::all();
        $fromdate = date('Y-m-d'); 
        $todate = date('Y-m-d');

        $alertDay = GeneralSetting::select('expiryalert')->first()->expiryalert;

        // Get the current date and calculate the target date
        $currentDate = Carbon::now();
        $targetDate = $currentDate->addDays($alertDay)->toDateString(); // Calculate the date 10 days from today
        
        // Fetch medicines where the remaining days until expiration are equal to the alertDay
        $query = Stock::where('expire_date', '=', $targetDate)
            ->select('medicineId', 'expire_date', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('medicineId', 'expire_date')
            ->with('medicine', 'medicine.supplier', 'medicine.category');
    

        if($request->filled('fromDate') && $request->filled('toDate')){
            $fromdate = $request->fromDate;
            $todate=$request->toDate;
            $query->whereBetween('expire_date', [$fromdate,$todate]);
        }

       // To filter by supplierId in the related 'medicine' model:
        if ($request->filled('supplierId')) {
            $query->whereHas('medicine', function ($query) use ($request) {
                $query->where('supplierId', $request->supplierId);
            });
        }

        if($request->filled('medicineId')){
            $query->where('medicineId', $request->medicineId);
        }
        
       $dataList= $query->get();
        return view('dashboard.stock.upcoming_expired_medicine',compact('dataList','suppliers','fromdate','todate','medicines'));
    }
    public function low_stocks(Request $request){

        $medicines = Medicine::all();
        $suppliers = Supplier::all();
        $fromdate = date('Y-m-d'); 
        $todate = date('Y-m-d');
        // Retrieve the low stock alert setting
        $lowStockAlert = GeneralSetting::select('lowstockalert')->first()->lowstockalert;
        // Fetch medicines where the total quantity is below the lowStockAlert value
        $query = Stock::select('medicineId', DB::raw('SUM(qty) as total_qty'))
        ->groupBy('medicineId')
        ->havingRaw('SUM(qty) > 0 AND SUM(qty) <= ?', [$lowStockAlert])
        ->with('medicine', 'medicine.supplier', 'medicine.category');

        if($request->filled('medicineId')){
            $query->where('medicineId', $request->medicineId);
        }
        
       $dataList= $query->get();
        return view('dashboard.stock.low_stock',compact('dataList','suppliers','medicines'));
    
    }
    public function in_stocks(Request $request){
        $medicines = Medicine::all();
        $suppliers = Supplier::all();
        $fromdate = date('Y-m-d'); 
        $todate = date('Y-m-d');
        
        // Retrieve the low stock alert setting
        $lowStockAlert = GeneralSetting::select('lowstockalert')->first()->lowstockalert;
        // Fetch medicines where the total quantity is below the lowStockAlert value
        $query = Stock::select('medicineId', DB::raw('SUM(qty) as total_qty'))
        ->groupBy('medicineId')
        ->havingRaw('SUM(qty) > ?', [0])
        ->with('medicine', 'medicine.supplier', 'medicine.category');

        if($request->filled('medicineId')){
            $query->where('medicineId', $request->medicineId);
        }
        
       $dataList= $query->get();
        return view('dashboard.stock.in_stock',compact('dataList','suppliers','medicines'));
    
    }
    public function stock_out(Request $request)
    {
        $medicines = Medicine::all();
        $suppliers = Supplier::all();

        // Query medicines and calculate their total stock sum
        $query = Medicine::withSum('stocks as total_qty', 'qty');

        if ($request->filled('supplierId')) {
            $query->where('supplierId', $request->supplierId);
        }

        if ($request->filled('medicineId')) {
            $query->where('id', $request->medicineId);
        }

        // Get medicines where total stock is 0 or null (no records)
        // Note: havingRaw is used because total_qty is an alias
        $outOfStockMedicines = $query->havingRaw('total_qty <= 0 OR total_qty IS NULL')->get();

        // Map to the structure expected by the view ($data->medicine->name, etc.)
        $dataList = $outOfStockMedicines->map(function ($medicine) {
            return (object)[
                'medicine' => $medicine,
                'total_qty' => $medicine->total_qty ?? 0
            ];
        });

        return view('dashboard.stock.stock_out', compact('dataList', 'suppliers', 'medicines'));
    }
}
