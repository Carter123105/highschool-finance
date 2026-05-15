@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Student Invoice</h3>

        <button onclick="window.print()" class="btn btn-dark">
            Print Invoice
        </button>
    </div>

    <div class="card p-4">

        {{-- STUDENT INFO --}}
        <div class="mb-3">
            <h5>
                {{ $student->first_name }} {{ $student->last_name }}
            </h5>

            <p class="mb-0">
                Class: <b>{{ $invoice->schoolClass->name }}</b><br>
                Type: <b>{{ $student->student_type }}</b><br>
                Invoice: <b>#{{ $invoice->invoice_no }}</b>
            </p>
        </div>

        <hr>

        {{-- BREAKDOWN --}}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fee Category</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>

            <tbody>
                @foreach($invoice->invoiceItems as $item)
                    <tr>
                        <td>{{ $item->feeCategory->name }}</td>
                        <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <th>Total</th>
                    <th class="text-end">{{ number_format($invoice->total_amount, 2) }}</th>
                </tr>
            </tfoot>
        </table>

    </div>
</div>
@endsection