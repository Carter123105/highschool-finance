<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\AcademicYear;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Student::query()->with([
            'schoolClass',
            'section',
            'academicYear'
        ]);

        // FILTER BY CLASS
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $students = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('students.index', [
            'students' => $students,
            'classes' => SchoolClass::orderBy('name')->get(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('students.create', [
            'classes' => SchoolClass::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
            'years' => AcademicYear::orderBy('name')->get(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $data = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',

            'student_id' => 'nullable|unique:students,student_id',

            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',

            'gender' => 'required|in:Male,Female',
            'student_type' => 'required|in:New,Old',

            'phone' => 'nullable|string|max:20',

            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',

            'address' => 'nullable|string|max:500',

            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // AUTO STUDENT ID
        if (empty($data['student_id'])) {
            $class = SchoolClass::findOrFail($data['class_id']);
            $count = Student::where('class_id', $data['class_id'])->count() + 1;

            $prefix = strtoupper(substr($class->name, 0, 3));

            $studentId = $prefix . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            while (Student::where('student_id', $studentId)->exists()) {
                $count++;
                $studentId = $prefix . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }

            $data['student_id'] = $studentId;
        }

        // PHOTO
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        Student::create($data);

        return redirect()->route('students.index')
            ->with('success', 'Student registered successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show(Student $student)
    {
        $student->load([
            'schoolClass',
            'section',
            'academicYear',
            'invoices',
            'payments'
        ]);

        return view('students.show', compact('student'));
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENTS (FIX FOR YOUR ERROR)
    |--------------------------------------------------------------------------
    */
    public function payments(Student $student)
    {
        $payments = Payment::where('student_id', $student->id)
            ->latest()
            ->get();

        return view('students.payments', compact('student', 'payments'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(Student $student)
    {
        return view('students.edit', [
            'student' => $student,
            'classes' => SchoolClass::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
            'years' => AcademicYear::orderBy('name')->get(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',

            'student_id' => [
                'nullable',
                Rule::unique('students', 'student_id')->ignore($student->id),
            ],

            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',

            'gender' => 'required|in:Male,Female',
            'student_type' => 'required|in:New,Old',

            'phone' => 'nullable|string|max:20',

            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',

            'address' => 'nullable|string|max:500',

            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {

            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }

            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        $student->update($data);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(Student $student)
    {
        if ($student->photo && Storage::disk('public')->exists($student->photo)) {
            Storage::disk('public')->delete($student->photo);
        }

        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }
}