<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()
    {
        $years = AcademicYear::latest()->paginate(10);

        return view('academic_years.index', compact('years'));
    }

    public function create()
    {
        return view('academic_years.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        AcademicYear::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active ? 1 : 0,
        ]);

        return redirect()
            ->route('academic-years.index')
            ->with('success', 'Academic Year Created Successfully');
    }

    public function edit(AcademicYear $academicYear)
    {
        return view('academic_years.edit', compact('academicYear'));
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $academicYear->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active ? 1 : 0,
        ]);

        return redirect()
            ->route('academic-years.index')
            ->with('success', 'Updated Successfully');
    }

    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->delete();

        return back()->with('success', 'Deleted Successfully');
    }
}