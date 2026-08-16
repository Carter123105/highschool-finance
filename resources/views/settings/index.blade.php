@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">System Settings</h2>
        <p class="text-muted mb-0">Configure school information, receipts, and system preferences</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">

            <!-- LEFT SIDE -->
            <div class="col-lg-8">

                <!-- SCHOOL INFO -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="fw-bold mb-0">School Information</h5>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">School Name</label>
                                <input type="text" name="school_name" class="form-control form-control-lg"
                                       value="{{ old('school_name', $setting->school_name ?? '') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">System Name</label>
                                <input type="text" name="system_name" class="form-control form-control-lg"
                                       value="{{ old('system_name', $setting->system_name ?? '') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="school_email" class="form-control"
                                       value="{{ old('school_email', $setting->school_email ?? '') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="school_phone" class="form-control"
                                       value="{{ old('school_phone', $setting->school_phone ?? '') }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea name="school_address" class="form-control" rows="3">{{ old('school_address', $setting->school_address ?? '') }}</textarea>
                            </div>

                        </div>

                    </div>
                </div>

                <!-- FINANCE -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h5 class="fw-bold mb-0">Finance Settings</h5>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Currency</label>
                                <select name="currency" id="currencySelect" class="form-select form-select-lg">
                                    <option value="LRD" {{ ($setting->currency ?? '') == 'LRD' ? 'selected' : '' }}>LRD - Liberian Dollar</option>
                                    <option value="USD" {{ ($setting->currency ?? '') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                </select>
                            </div>

                            <div class="col-md-6" id="exchangeRateBox" style="display:none;">
                                <label class="form-label">Exchange Rate</label>
                                <input type="number" step="0.01" name="exchange_rate" class="form-control form-control-lg"
                                       value="{{ old('exchange_rate', $setting->exchange_rate ?? '') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Receipt Prefix</label>
                                <input type="text" name="receipt_prefix" class="form-control"
                                       value="{{ old('receipt_prefix', $setting->receipt_prefix ?? 'REC') }}">
                            </div>

                        </div>

                    </div>
                </div>

            </div>

            <!-- RIGHT SIDE -->
            <div class="col-lg-4">

                <!-- LOGO CARD -->
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0">
                        <h5 class="fw-bold mb-0">School Logo</h5>
                    </div>

                    <div class="card-body text-center">

                        @php
                            $logoPath = $setting->logo ?? null;
                            $logoPath = $logoPath ? str_replace(['storage/', 'public/'], '', $logoPath) : null;
                            $logoUrl = $logoPath ? asset('storage/' . $logoPath) : null;
                        @endphp

                        @if($logoUrl)
                            <img
                                src="{{ $logoUrl }}"
                                class="img-fluid rounded mb-3 shadow-sm"
                                style="max-height:140px; object-fit:contain;"
                                alt="School Logo"
                                onerror="this.style.display='none'; document.getElementById('logo-error').style.display='block';"
                            >
                            <div id="logo-error" class="alert alert-warning" style="display:none;">
                                <small>Logo not found at:<br><code>{{ $logoUrl }}</code></small>
                            </div>
                        @else
                            <div class="text-muted mb-3">No logo uploaded</div>
                        @endif

                        <input type="file" name="logo" class="form-control" accept="image/*">

                        <small class="text-muted d-block mt-2">Recommended: PNG or JPG (200x200px)</small>

                    </div>

                </div>

                <!-- AUTHORIZED SIGNATURE CARD -->
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0">
                        <h5 class="fw-bold mb-0">Authorized Signature</h5>
                    </div>

                    <div class="card-body text-center">

                        @php
                            $authSigPath = $setting->authorized_signature ?? null;
                            $authSigPath = $authSigPath ? str_replace(['storage/', 'public/'], '', $authSigPath) : null;
                            $authSigUrl = $authSigPath ? asset('storage/' . $authSigPath) : null;
                        @endphp

                        @if($authSigUrl)
                            <img
                                src="{{ $authSigUrl }}"
                                class="img-fluid rounded mb-3 shadow-sm bg-white border"
                                style="max-height:100px; object-fit:contain;"
                                alt="Authorized Signature"
                                onerror="this.style.display='none'; document.getElementById('auth-sig-error').style.display='block';"
                            >
                            <div id="auth-sig-error" class="alert alert-warning" style="display:none;">
                                <small>Signature not found at:<br><code>{{ $authSigUrl }}</code></small>
                            </div>
                        @else
                            <div class="text-muted mb-3">
                                <i class="bi bi-pen fs-1 d-block mb-2"></i>
                                No signature uploaded
                            </div>
                        @endif

                        <input type="file" name="authorized_signature" class="form-control" accept="image/*">

                        <small class="text-muted d-block mt-2">Upload scanned signature (PNG with transparent background preferred)</small>

                    </div>

                </div>

                <!-- REGISTRAR SIGNATURE CARD -->
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0">
                        <h5 class="fw-bold mb-0">Registrar Signature</h5>
                    </div>

                    <div class="card-body text-center">

                        @php
                            $regSigPath = $setting->registrar_signature ?? null;
                            $regSigPath = $regSigPath ? str_replace(['storage/', 'public/'], '', $regSigPath) : null;
                            $regSigUrl = $regSigPath ? asset('storage/' . $regSigPath) : null;
                        @endphp

                        @if($regSigUrl)
                            <img
                                src="{{ $regSigUrl }}"
                                class="img-fluid rounded mb-3 shadow-sm bg-white border"
                                style="max-height:100px; object-fit:contain;"
                                alt="Registrar Signature"
                                onerror="this.style.display='none'; document.getElementById('reg-sig-error').style.display='block';"
                            >
                            <div id="reg-sig-error" class="alert alert-warning" style="display:none;">
                                <small>Signature not found at:<br><code>{{ $regSigUrl }}</code></small>
                            </div>
                        @else
                            <div class="text-muted mb-3">
                                <i class="bi bi-pen fs-1 d-block mb-2"></i>
                                No signature uploaded
                            </div>
                        @endif

                        <input type="file" name="registrar_signature" class="form-control" accept="image/*">

                        <small class="text-muted d-block mt-2">Upload scanned signature (PNG with transparent background preferred)</small>

                    </div>

                </div>

                <!-- SAVE BUTTON -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold">
                            Save Settings
                        </button>
                        <p class="text-muted text-center mt-2 mb-0">
                            Changes will apply system-wide
                        </p>
                    </div>
                </div>

            </div>

        </div>

    </form>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const currency = document.getElementById('currencySelect');
    const box = document.getElementById('exchangeRateBox');

    function toggleRate() {
        box.style.display = currency.value === 'USD' ? 'block' : 'none';
    }

    currency.addEventListener('change', toggleRate);
    toggleRate();
});
</script>

@endsection