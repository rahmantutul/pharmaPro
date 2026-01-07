<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PaymentMethodController extends Controller
{
    public function index()
    {
        
        return view('dashboard.payment_method.index');
    }

    public function getPaymentMethodsData()
    {
        $paymentMethods = PaymentMethod::select(['id', 'name', 'balance'])->get();
        return DataTables::of($paymentMethods)
        ->addColumn('actions', function($paymentMethod) {
            return '
                <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editModal" 
                onclick="editPaymentMethods(' . $paymentMethod->id . ', \'' . $paymentMethod->name . '\', \'' . $paymentMethod->balance . '\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></button>
                <form action="'.route('expense.category.destroy', $paymentMethod->id).'" method="POST" style="display:inline;">
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
            'balance' => 'numeric',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        PaymentMethod::create([
            'name' => $request->name,
            'balance' => $request->balance ?? 0,
        ]);

        return redirect()->back()->with('success', 'Payment Methods created successfully.');
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'numeric',
        ]);
    
        // Find the paymentMethod by ID
        $paymentMethod = PaymentMethod::findOrFail($id);
    
        // Update the paymentMethod details
        $paymentMethod->update([
            'name' => $request->input('name'),
            'balance' => $request->input('balance') ?? 0,
        ]);
    
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Payment Methods updated successfully.');
    }
    

    public function destroy($id)
    {
        PaymentMethod::destroy($id);
        return redirect()->back()->with('success', 'Payment Methods deleted successfully.');
    }
}
