<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ExpenseCategoryController extends Controller
{
    
    public function index()
    {
        
        return view('dashboard.expense.category_index');
    }

    public function getExpenseCategoryData()
    {
        $expenseCategories = ExpenseCategory::select(['id', 'name', 'description'])->get();
        return DataTables::of($expenseCategories)
        ->addColumn('actions', function($expenseCategory) {
            return '
                <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editModal" 
                onclick="editExpenseCategory(' . $expenseCategory->id . ', \'' . $expenseCategory->name . '\', \'' . $expenseCategory->description . '\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></button>
                <form action="'.route('expense.category.destroy', $expenseCategory->id).'" method="POST" style="display:inline;">
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
            'description' => 'max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        ExpenseCategory::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Expense Category created successfully.');
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'max:255',
        ]);
    
        // Find the expenseCategory by ID
        $expenseCategory = ExpenseCategory::findOrFail($id);
    
        // Update the expenseCategory details
        $expenseCategory->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);
    
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Expense Category updated successfully.');
    }
    

    public function destroy($id)
    {
        ExpenseCategory::destroy($id);
        return redirect()->back()->with('success', 'Expense Category deleted successfully.');
    }
}
