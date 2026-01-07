<?php

namespace App\Http\Controllers;

use App\Models\PurchaseInvoice;
use App\Models\PurchaseReturn;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    public function index()
    {
        return view('dashboard.suppliers.index');
    }

    public function getSuppliersData()
    {
        $suppliers = Supplier::select(['id', 'name', 'email', 'phone','address'])->get();
        return DataTables::of($suppliers)
        ->addColumn('actions', function($supplier) {
            return '
             <a class="btn btn-xs btn-primary" href="'.route('supplier.history', $supplier->id).'" title="History"><i class="fa fa-history" aria-hidden="true"></i></a>
                <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editModal" onclick="editSupplier(' . $supplier->id . ', \'' . $supplier->name . '\', \'' . $supplier->email . '\', \'' . $supplier->phone . '\', \'' . $supplier->address . '\', ' . $supplier->payable . ')"><i class="fa fa-pencil-square" aria-hidden="true"></i></button>
                <form action="'.route('supplier.destroy', $supplier->id).'" method="POST" style="display:inline;">
                    '.csrf_field().method_field('DELETE').'
                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm(\'Are you sure?\')"><i class="fa fa-trash-o icon-trash"></i></button>
                </form>
            ';
        })
            ->rawColumns(['actions']) // Allow HTML rendering for actions
            ->make(true);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        Supplier::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        return redirect()->back()->with('success', 'Supplier created successfully.');
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
        ]);
    
        // Find the supplier by ID
        $supplier = Supplier::findOrFail($id);
    
        // Update the supplier details
        $supplier->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
        ]);
    
        // Redirect back with a success message
        return redirect()->route('supplier.index')->with('success', 'Supplier updated successfully.');
    }
    

    public function destroy($id)
    {
        Supplier::destroy($id);
        return redirect()->route('supplier.index')->with('success', 'Supplier deleted successfully.');
    }
    public function history($id)
    {
        $dataInfo = Supplier::findOrFail($id);
        $invoices = PurchaseInvoice::where('supplierId',$id)->get();
        $due = Transaction::where('supplierId',$id)->where('type','supplier_due')->sum('amount');
        $returnList = PurchaseReturn::with('medicine', 'medicine.supplier')->get();
        return view('dashboard.suppliers.view', compact('dataInfo','invoices','due','returnList'));
    }
    public function transaction(Request $request){
        if($request->due > 0 && $request->due >= $request->amount){
            Transaction::create([
                'date' => date('Y-m-d'),
                'type' => 'supplier_due',
                'supplierId' => $request->supId,
                'refId' => $request->supId,
                'amount' => -($request->amount)
            ]);
            return redirect()->back()->with('success',' Due paid successfully');
        }else{
            return redirect()->back()->with('failed',' Paid amount is greater then due amount Or You have no due left');
        }
       
    }
}
