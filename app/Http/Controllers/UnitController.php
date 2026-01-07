<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UnitController extends Controller
{
    public function index()
    {
        return view('dashboard.medicine.unit_index');
    }

    public function getunitData()
    {
        $units = Unit::select(['id', 'name'])->get();
        return DataTables::of($units)
        ->addColumn('actions', function($unit) {
            return '
                <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editModal" 
                onclick="editUnit(' . $unit->id . ', \'' . $unit->name . '\')">Edit</button>
                <form action="'.route('medicine.unit.destroy', $unit->id).'" method="POST" style="display:inline;">
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
            'name' => 'required|string|max:255'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        Unit::create([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Unit created successfully.');
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:100',
        ]);
    
        // Find the unit by ID
        $unit = Unit::findOrFail($id);
    
        // Update the unit details
        $unit->update([
            'name' => $request->input('name')
        ]);
    
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Unit updated successfully.');
    }
    

    public function destroy($id)
    {
        Unit::destroy($id);
        return redirect()->back()->with('success', 'Unit deleted successfully.');
    }
}
