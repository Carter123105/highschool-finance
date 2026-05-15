<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::with('schoolClass')
            ->latest()
            ->paginate(10);

        return view('sections.index', compact('sections'));
    }

    public function create()
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view('sections.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer',
        ]);

        Section::create([
            'class_id' => $request->class_id,
            'name' => $request->name,
            'capacity' => $request->capacity,
        ]);

        return redirect()
            ->route('sections.index')
            ->with('success', 'Section Created Successfully');
    }

    public function edit(Section $section)
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view('sections.edit', compact('section', 'classes'));
    }

    public function update(Request $request, Section $section)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer',
        ]);

        $section->update([
            'class_id' => $request->class_id,
            'name' => $request->name,
            'capacity' => $request->capacity,
        ]);

        return redirect()
            ->route('sections.index')
            ->with('success', 'Updated Successfully');
    }

    public function destroy(Section $section)
    {
        $section->delete();

        return back()->with('success', 'Deleted Successfully');
    }
}