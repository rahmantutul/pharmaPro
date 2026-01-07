<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\SalesReturn;
use App\Models\Transaction; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
 
class CustomerController extends Controller
{
    public function index()
    {
        return view('dashboard.customers.index');
    }

    public function getCustomersData()
    {
        $customers = Customer::select(['id', 'name', 'email', 'phone','address'])->get();
        return DataTables::of($customers)
        ->addColumn('actions', function($customer) {
            return '
                <a class="btn btn-xs btn-primary" href="'.route('customer.history', $customer->id).'" title="History"><i class="fa fa-history" aria-hidden="true"></i></a>
                <button class="btn btn-warning btn-xs" title="Edit" data-toggle="modal" data-target="#editModal" onclick="editCustomer(' . $customer->id . ', \'' . $customer->name . '\', \'' . $customer->email . '\', \'' . $customer->phone . '\', \'' . $customer->address . '\', ' . $customer->balance . ')"><i class="fa fa-pencil-square" aria-hidden="true"></i></button>
                <form action="'.route('customer.destroy', $customer->id).'" method="POST" style="display:inline;">
                    '.csrf_field().method_field('DELETE').'
                    <button type="submit" title="Delete" class="btn btn-danger btn-xs" onclick="return confirm(\'Are you sure?\')"><i class="fa fa-trash-o icon-trash"></i></button>
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
        
        Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        return redirect()->back()->with('success', 'Customer created successfully.');
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
    
        // Find the customer by ID
        $customer = Customer::findOrFail($id);
    
        // Update the customer details
        $customer->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
        ]);
    
        // Redirect back with a success message
        return redirect()->route('customer.index')->with('success', 'Customer updated successfully.');
    }
    

    public function destroy($id)
    {
        Customer::destroy($id);
        return redirect()->route('customer.index')->with('success', 'Customer deleted successfully.');
    }
    public function history($id)
    {
        $dataInfo = Customer::findOrFail($id);
        $invoices = Sale::where('customerId',$id)->get();
        $due = Transaction::where('customerId',$id)->where('type','customer_due')->sum('amount');
        $returnList = SalesReturn::with('medicine', 'medicine.supplier')->get();
        return view('dashboard.customers.view', compact('dataInfo','invoices','due','returnList'));
    }
    
    public function transaction(Request $request){
        if($request->due > 0 && $request->due >= $request->amount){
            Transaction::create([
                'date' => date('Y-m-d'),
                'type' => 'customer_due',
                'customerId' => $request->custId,
                'refId' => $request->custId,
                'amount' => -($request->amount)
            ]);
            return redirect()->back()->with('success',' Due paid successfully');
        }else{
            return redirect()->back()->with('failed',' Paid amount is greater then due amount Or Customer has no Due');
        }
       
    }
    // public function invoice($id)
    // {
    //     $dataInfo = Sales::where('id',$id)->with('details','details.medicine','customer')->first();
    //     $general_setting = GeneralSetting::first();
    //     return view('backend.invoice.invoice', compact('dataInfo','general_setting'));
    // }
}
