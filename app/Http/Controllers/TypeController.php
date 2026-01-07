<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TypeController extends Controller
{
    public function index()
    {
        return view('dashboard.medicine.type_index');
    }

    public function getTypeData()
    {
        $types = Type::select(['id', 'name'])->get();
        return DataTables::of($types)
        ->addColumn('actions', function($type) {
            return '
                <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editModal" 
                onclick="editType(' . $type->id . ', \'' . $type->name . '\')">Edit</button>
                <form action="'.route('medicine.type.destroy', $type->id).'" method="POST" style="display:inline;">
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
        
        Type::create([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Type created successfully.');
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:100',
        ]);
    
        // Find the type by ID
        $type = Type::findOrFail($id);
    
        // Update the type details
        $type->update([
            'name' => $request->input('name')
        ]);
    
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Type updated successfully.');
    }
    

    public function destroy($id)
    {
        Type::destroy($id);
        return redirect()->back()->with('success', 'Type deleted successfully.');
    }
}
