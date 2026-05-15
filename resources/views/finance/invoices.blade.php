@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Invoices Breakdown</h3>

    <div class="card">
        <div class="card-body">

            @forelse($groupedInvoices as $classId => $invoices)

                {{-- CLASS HEADER (ONCE ONLY) --}}
                <div class="alert alert-secondary fw-bold mb-3">
                    Class: {{ $invoices->first()->schoolClass->name ?? 'N/A' }}
                </div>

                {{-- EACH INVOICE --}}
                @foreach($invoices as $invoice)

                    <div class="border rounded p-3 mb-4">

                        {{-- INVOICE HEADER --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>#{{ $invoice->invoice_no }}</strong>
                            </div>

                            <span class="badge bg-info">
                                {{ $invoice->status }}
                            </span>
                        </div>

                        <hr>

                        {{-- STUDENT SELECTOR --}}
                        <form method="GET"
                              action="{{ url('/finance/invoice/student/'.$invoice->id) }}"
                              class="row g-2 align-items-end mb-3">

                            <div class="col-md-8">
                                <label class="form-label">Select Student</label>

                                <select name="student_id" class="form-select" required>
                                    <option value="">-- Choose Student --</option>

                                    @foreach($studentsByClass[$classId] ?? [] as $student)
                                        <option value="{{ $student->id }}">
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <button class="btn btn-primary w-100">
                                    View Invoice
                                </button>
                            </div>
                        </form>

                        {{-- FEES BREAKDOWN --}}
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Fee Category</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($invoice->invoiceItems as $item)
                                    <tr>
                                        <td>{{ $item->feeCategory->name ?? 'Unknown' }}</td>
                                        <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr class="fw-bold">
                                    <td>Total</td>
                                    <td class="text-end">
                                        {{ number_format($invoice->total_amount, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                    </div>

                @endforeach

            @empty
                <div class="text-center text-muted">
                    No invoices found
                </div>
            @endforelse

        </div>
    </div>
</div>
@endsection