<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::latest()->paginate(10);

        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        return view('classes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:school_classes'
        ]);

        SchoolClass::create($request->all());

        return redirect()->route('classes.index')
            ->with('success', 'Class Created Successfully');
    }

    public function edit(SchoolClass $class)
    {
        return view('classes.edit', compact('class'));
    }

    public function update(Request $request, SchoolClass $class)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $class->update($request->all());

        return redirect()->route('classes.index')
            ->with('success', 'Updated Successfully');
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();

        return back()->with('success', 'Deleted Successfully');
    }
}