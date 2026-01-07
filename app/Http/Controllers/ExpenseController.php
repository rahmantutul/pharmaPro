<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ExpenseController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::all(); // Fetch all categories
        return view('dashboard.expense.index', compact('categories')); // Pass to the view
    }


    public function getExpensesData()
    {
        $expenses = Expense::with('category')->select(['id', 'date', 'categoryId', 'expense_for', 'amount', 'note']);
        return DataTables::of($expenses)
            ->addColumn('category', function($expense) {
                return $expense->category->name; // Show category name
            })
            ->addColumn('actions', function($expense) {
                return '
                    <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editModal" onclick="editExpense(' . $expense->id . ', \'' . $expense->date . '\', \'' . $expense->categoryId . '\', \'' . $expense->expense_for . '\', ' . $expense->amount . ', \'' . $expense->note . '\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></button>
                    <form action="'.route('expense.destroy', $expense->id).'" method="POST" style="display:inline;">
                        '.csrf_field().method_field('DELETE').'
                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm(\'Are you sure?\')"><i class="fa fa-trash-o icon-trash"></i></button>
                    </form>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    
    public function store(StoreExpenseRequest $request)
    {
        Expense::create($request->validated());
    
        return redirect()->back()->with('success' , 'Expense added successfully.');
    }
    

    public function update(StoreExpenseRequest $request, $id)
    {
        $expense = Expense::findOrFail($id);
        $expense->update($request->validated());
    
        return redirect()->back()->with('success' , 'Expense updated successfully.');
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();
    
        return redirect()->back()->with('success' , 'Expense deleted successfully.');
    }
    
}
