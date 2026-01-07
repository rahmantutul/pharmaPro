<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        return view('dashboard.medicine.category_index');
    }

    public function getCategoryData()
    {
        $categories = Category::select(['id', 'name'])->get();
        return DataTables::of($categories)
        ->addColumn('actions', function($category) {
            return '
                <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editModal" 
                onclick="editCategory(' . $category->id . ', \'' . $category->name . '\')">Edit</button>
                <form action="'.route('medicine.category.destroy', $category->id).'" method="POST" style="display:inline;">
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
        
        Category::create([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:100',
        ]);
    
        // Find the category by ID
        $category = Category::findOrFail($id);
    
        // Update the category details
        $category->update([
            'name' => $request->input('name')
        ]);
    
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Category updated successfully.');
    }
    

    public function destroy($id)
    {
        Category::destroy($id);
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }
}
