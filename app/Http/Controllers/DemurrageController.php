<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDemurrageRequest;
use App\Models\Demurrage;
use App\Models\Medicine;
use App\Models\Transaction;
use App\Services\StockService;
use Illuminate\Http\Request;

class DemurrageController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index(Request $request)
    {   
        $medicines = Medicine::orderBy('name')->get();
        $fromDate = date('Y-m-d'); 
        $toDate = date('Y-m-d');
        $query = Demurrage::query();
        
        if($request->filled('fromDate') && $request->filled('toDate')){
            $fromDate = $request->fromDate;
            $toDate=$request->toDate;
            $query->whereBetween('demurrage_date', [$fromDate, $toDate]);
        }

        if($request->filled('medicineId')){
            $query->where('medicineId', $request->medicineId);
        }

        $dataList = $query->with('medicine')->latest()->paginate(15);

        return view('dashboard.demurrage.index', compact('dataList','medicines','fromDate','toDate'));
    }

    public function create()
    {
        $medicines = Medicine::orderBy('name')->get();
        return view('dashboard.demurrage.create', compact('medicines'));
    }

    public function store(StoreDemurrageRequest $request)
    {
        // Validation handled by Request

        // Create a new Demurrage record
        $demurrage = Demurrage::create([
            'medicineId'     => $request->medicineId,
            'price'          => $request->price,
            'demurrage_date' => $request->demurrage_date,
            'qty'            => $request->quantity,
            'total'          => $request->total
        ]);

        if($demurrage){
            // Create Transaction (Financial loss)
            Transaction::create([
                'medicineId' => $request->medicineId,
                'date'       => $request->demurrage_date,
                'type'       => 'demurrage',
                'refId'      => $demurrage->id,
                'amount'     => -($request->total)
            ]);

            // Reduce Stock (Inventory loss)
            // Using demurrage_date as expire_date to satisfy DB constraint and ensure total count is reduced.
            $this->stockService->reduceStock(
                $request->medicineId,
                $request->quantity,
                $request->demurrage_date,
                $request->demurrage_date, // Fallback expiry date
                'DEM-' . $demurrage->id,
                $demurrage->id,
                'demurrage'
            );
        }

        return redirect()->back()->with('success', 'Demurrage created successfully.');
    }

    public function edit($id)
    {   
        $medicines = Medicine::orderBy('name')->get();
        $dataInfo = Demurrage::findOrFail($id);
        return view('dashboard.demurrage.edit', compact('dataInfo','medicines'));
    }

    public function update(StoreDemurrageRequest $request)
    {
        $dataId = $request->dataId;
        $demurrage = Demurrage::findOrFail($dataId);
        
        $demurrage->update([
            'medicineId'     => $request->medicineId,
            'price'          => $request->price,
            'demurrage_date' => $request->demurrage_date,
            'qty'            => $request->quantity,
            'total'          => $request->total,
        ]);

        // Update Transaction
        Transaction::where('refId', $dataId)->where('type', 'demurrage')->delete();
        
        Transaction::create([
            'medicineId' => $request->medicineId,
            'date'       => $request->demurrage_date,
            'type'       => 'demurrage',
            'refId'      => $dataId,
            'amount'     => -($request->total)
        ]);

        // Update Stock
        // 1. Remove old stock entry for this demurrage
        \App\Models\Stock::where('ref_id', $dataId)->where('type', 'demurrage')->delete();

        // 2. Create new stock entry
        $this->stockService->reduceStock(
            $request->medicineId,
            $request->quantity,
            $request->demurrage_date,
            $request->demurrage_date,
            'DEM-' . $dataId,
            $dataId,
            'demurrage'
        );
        
        return redirect()->back()->with('success', 'Demurrage updated successfully.');
    }

    public function destroy($id)
    {
        $demurrage = Demurrage::findOrFail($id);
        
        // Remove associated transaction
        Transaction::where('refId', $id)->where('type', 'demurrage')->delete();
        
        // Remove associated stock
        \App\Models\Stock::where('ref_id', $id)->where('type', 'demurrage')->delete();

        $demurrage->delete();
        
        return redirect()->route('demurrage.index')->with('success', 'Demurrage deleted successfully.');
    }
}
