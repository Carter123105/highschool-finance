@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Students Finance View</h3>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Total Paid</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($students as $student)
                        <tr>
                            {{-- ✅ FIXED NAME --}}
                            <td>
                                {{ $student->first_name }} {{ $student->last_name }}
                            </td>

                            {{-- ✅ FIXED CLASS RELATION --}}
                            <td>
                                {{ $student->schoolClass->name ?? 'N/A' }}
                            </td>

                            {{-- ✅ FIXED PAYMENT SUM --}}
                            <td>
                                {{ number_format($student->payments_sum_amount_paid ?? 0) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
</div>
@endsection