<?php

namespace App\Http\Controllers;

use App\Models\FeeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FeeCategoryController extends Controller
{
    /*
    |--------------------------------------------------
    | INDEX
    |--------------------------------------------------
    */
    public function index()
    {
        $feeCategories = FeeCategory::latest()->paginate(10);

        return view('fee_categories.index', compact('feeCategories'));
    }

    /*
    |--------------------------------------------------
    | CREATE
    |--------------------------------------------------
    */
    public function create()
    {
        return view('fee_categories.create');
    }

    /*
    |--------------------------------------------------
    | STORE
    |--------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:fee_categories,name',
            'description' => 'nullable|string',
            'is_monthly' => 'nullable|boolean',
        ]);

        FeeCategory::create([
            'name' => Str::title($validated['name']),
            'description' => $validated['description'] ?? null,
            'is_monthly' => $request->boolean('is_monthly'),
            'is_active' => true,
        ]);

        return redirect()
            ->route('fee-categories.index')
            ->with('success', 'Fee Category created successfully.');
    }

    /*
    |--------------------------------------------------
    | EDIT
    |--------------------------------------------------
    */
    public function edit(FeeCategory $feeCategory)
    {
        return view('fee_categories.edit', compact('feeCategory'));
    }

    /*
    |--------------------------------------------------
    | UPDATE
    |--------------------------------------------------
    */
    public function update(Request $request, FeeCategory $feeCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:fee_categories,name,' . $feeCategory->id,
            'description' => 'nullable|string',
            'is_monthly' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $feeCategory->update([
            'name' => Str::title($validated['name']),
            'description' => $validated['description'] ?? null,
            'is_monthly' => $request->boolean('is_monthly'),
            'is_active' => $request->boolean('is_active', $feeCategory->is_active),
        ]);

        return redirect()
            ->route('fee-categories.index')
            ->with('success', 'Fee Category updated successfully.');
    }

    /*
    |--------------------------------------------------
    | DELETE
    |--------------------------------------------------
    */
    public function destroy(FeeCategory $feeCategory)
    {
        $feeCategory->delete();

        return redirect()
            ->route('fee-categories.index')
            ->with('success', 'Fee Category deleted successfully.');
    }
}