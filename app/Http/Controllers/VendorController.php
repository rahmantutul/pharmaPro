<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{  public function index()
    {
        return view('dashboard.vendors.index');
    }

    public function getVendorsData()
    {
        try {
            $vendors = Vendor::select(['id', 'name', 'email', 'phone', 'address', 'payable', 'due'])->get();
            return DataTables::of($vendors)
                ->addColumn('actions', function($vendor) {
                    return '
                        <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editModal" 
                        onclick="editVendor(' . $vendor->id . ', \'' . $vendor->name . '\', \'' . $vendor->email . '\', \'' . $vendor->phone . '\', \'' . $vendor->address . '\', ' . $vendor->payable . ', ' . $vendor->due . ')"><i class="fa fa-pencil-square" aria-hidden="true"></i></button>
                        <form action="'.route('vendor.destroy', $vendor->id).'" method="POST" style="display:inline;">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm(\'Are you sure?\')"><i class="fa fa-trash-o icon-trash"></i></button>
                        </form>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'There was an error fetching the data.'], 500);
        }
    }
    
    
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'payable' => 'numeric',
            'due' => 'numeric',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        Vendor::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'payable' => $request->payable ?? 0,
            'due' => $request->due ?? 0,
        ]);

        return redirect()->back()->with('success', 'Vendor created successfully.');
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'payable' => 'numeric',
            'due' => 'numeric',
        ]);
    
        // Find the vendor by ID
        $vendor = Vendor::findOrFail($id);
    
        // Update the vendor details
        $vendor->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'payable' => $request->input('payable') ?? 0,
            'due' => $request->input('due') ?? 0,
        ]);
    
        // Redirect back with a success message
        return redirect()->route('vendor.index')->with('success', 'Vendor updated successfully.');
    }
    

    public function destroy($id)
    {
        Vendor::destroy($id);
        return redirect()->route('vendor.index')->with('success', 'Vendor deleted successfully.');
    }
}
