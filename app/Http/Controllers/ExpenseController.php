<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /*
    |------------------------------------------------------------------
    | INDEX
    |------------------------------------------------------------------
    */
    public function index()
    {
        $yearId = session('academic_year_id');

        $expenses = Expense::with('user')
            ->when($yearId, function ($query) use ($yearId) {
                $query->where('academic_year_id', $yearId);
            })
            ->latest()
            ->get();

        $totalExpenses = $expenses->sum('amount');

        return view('expenses.index', compact(
            'expenses',
            'totalExpenses'
        ));
    }

    /*
    |------------------------------------------------------------------
    | CREATE
    |------------------------------------------------------------------
    */
    public function create()
    {
        return view('expenses.create');
    }

    /*
    |------------------------------------------------------------------
    | STORE
    |------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'expense_date' => 'nullable|date',
        ]);

        Expense::create([
            'title' => $validated['title'],
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'expense_date' => $validated['expense_date'] ?? now(),
            'user_id' => auth()->user()->id(),
            'academic_year_id' => session('academic_year_id'),
        ]);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense created successfully.');
    }

    /*
    |------------------------------------------------------------------
    | SHOW (FIXED ERROR)
    |------------------------------------------------------------------
    */
    public function show(Expense $expense)
    {
        $expense->load('user');

        return view('expenses.show', compact('expense'));
    }

    /*
    |------------------------------------------------------------------
    | EDIT
    |------------------------------------------------------------------
    */
    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    /*
    |------------------------------------------------------------------
    | UPDATE
    |------------------------------------------------------------------
    */
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'expense_date' => 'nullable|date',
        ]);

        $expense->update([
            'title' => $validated['title'],
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'expense_date' => $validated['expense_date'] ?? null,
        ]);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    /*
    |------------------------------------------------------------------
    | DESTROY
    |------------------------------------------------------------------
    */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}                