<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leaf;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
class LeafController extends Controller
{
    public function index()
    {
        return view('dashboard.medicine.leaf_index');
    }

    public function getLeafData()
    {
        $leaves = Leaf::select(['id', 'name','qty'])->get();
        return DataTables::of($leaves)
        ->addColumn('actions', function($leaf) {
            return '
                <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editModal" 
                onclick="editLeaf(' . $leaf->id . ', \'' . $leaf->name . '\', \'' . $leaf->qty . '\')">Edit</button>
                <form action="'.route('medicine.leaf.destroy', $leaf->id).'" method="POST" style="display:inline;">
                    '.csrf_field().method_field('DELETE').'
                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm(\'Are you sure?\')">Delete</button>
                </form>
            ';
        })
            ->rawColumns(['actions']) // Allow HTML rendering for actions
            ->make(true);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'qty' => 'required|numeric',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        Leaf::create([
            'name' => $request->name,
            'qty' => $request->qty,
        ]);

        return redirect()->back()->with('success', 'Leaf created successfully.');
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:100',
            'qty' => 'required|numeric',
        ]);
    
        // Find the leaf by ID
        $leaf = Leaf::findOrFail($id);
    
        // Update the leaf details
        $leaf->update([
            'name' => $request->input('name'),
            'qty' => $request->input('qty'),
        ]);
    
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Leaf updated successfully.');
    }
    

    public function destroy($id)
    {
        Leaf::destroy($id);
        return redirect()->back()->with('success', 'Leaf deleted successfully.');
    }
}
