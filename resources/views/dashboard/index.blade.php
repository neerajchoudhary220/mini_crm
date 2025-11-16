@extends('layouts.main')
@section('title',"Dashboard")

@section('contents')
@push('custom-styles')
<style>
    .kpi-card {
        border-radius: 18px;
        padding: 25px;
        color: #fff;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        transition: all .3s ease-in-out;
        position: relative;
        overflow: hidden;
    }

    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.18);
    }

    .kpi-icon {
        font-size: 55px;
        opacity: 0.25;
        position: absolute;
        right: 15px;
        bottom: 10px;
    }

    .gradient-blue {
        background: linear-gradient(135deg, #007bff, #00c6ff);
    }

    .gradient-green {
        background: linear-gradient(135deg, #28a745, #5be584);
    }

    .gradient-purple {
        background: linear-gradient(135deg, #6f42c1, #b37bff);
    }

    .kpi-value {
        font-size: 48px;
        font-weight: 800;
        line-height: 1;
    }

    .kpi-label {
        font-size: 18px;
        font-weight: 500;
    }
</style>
@endpush

<div class="container-fluid mt-4">

    <h3 class="fw-bold mb-4"> Dashboard Overview</h3>

    <div class="row g-4">

        <!-- Total Contacts -->
        <div class="col-md-4">
            <a href="{{ route('contacts') }}" class="text-decoration-none text-white">
                <div class="kpi-card gradient-blue">
                    <div class="kpi-label">Total Contacts</div>
                    <div class="kpi-value">{{ $totalContacts }}</div>
                    <i class="bi bi-people-fill kpi-icon"></i>
                </div>
            </a>
        </div>

        <!-- Total Merged Contacts -->
        <div class="col-md-4">
            <a href="{{ route('contacts') }}" class="text-decoration-none text-white">
                <div class="kpi-card gradient-green">
                    <div class="kpi-label">Merged Contacts</div>
                    <div class="kpi-value">{{ $totalMergedContacts }}</div>
                    <i class="bi bi-link-45deg kpi-icon"></i>
                </div>
            </a>
        </div>

        <!-- Total Custom Fields -->
        <div class="col-md-4">
            <a href="{{ route('custom.fields') }}" class="text-decoration-none text-white">
                <div class="kpi-card gradient-purple">
                    <div class="kpi-label">Custom Fields</div>
                    <div class="kpi-value">{{ $totalCustomFields }}</div>
                    <i class="bi bi-ui-checks-grid kpi-icon"></i>
                </div>
            </a>
        </div>

    </div>

</div>

@endsection