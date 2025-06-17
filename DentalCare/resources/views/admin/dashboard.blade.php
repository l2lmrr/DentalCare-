@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Statistics Cards - Modern Glassmorphism Design -->
    <div class="row g-4">
        <div class="col-xl-3 col-sm-6">
            <div class="glass-card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="stats-label">OCCUPANCY RATE</h6>
                            <h2 class="stats-value">{{ $stats['occupancy_rate'] }}%</h2>
                        </div>
                        <div class="stats-icon bg-primary-light">
                            <i class="fas fa-percent"></i>
                        </div>
                    </div>
                    <div class="progress mt-3">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $stats['occupancy_rate'] }}%" 
                             aria-valuenow="{{ $stats['occupancy_rate'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="glass-card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="stats-label">ACTIVE PATIENTS</h6>
                            <h2 class="stats-value">{{ $stats['active_patients'] }}</h2>
                        </div>
                        <div class="stats-icon bg-success-light">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="trend-indicator mt-3 up">
                        <i class="fas fa-arrow-up"></i> 12% from last week
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="glass-card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="stats-label">WEEKLY CONSULTATIONS</h6>
                            <h2 class="stats-value">{{ $stats['weekly_consultations'] }}</h2>
                        </div>
                        <div class="stats-icon bg-warning-light">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="trend-indicator mt-3 down">
                        <i class="fas fa-arrow-down"></i> 5% from last week
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="glass-card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="stats-label">ACTIVE DENTISTS</h6>
                            <h2 class="stats-value">{{ $stats['active_dentists'] }}</h2>
                        </div>
                        <div class="stats-icon bg-info-light">
                            <i class="fas fa-user-md"></i>
                        </div>
                    </div>
                    <div class="trend-indicator mt-3 up">
                        <i class="fas fa-arrow-up"></i> 3% from last month
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Appointments Table - Modern Design -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Recent Appointments</h5>
                        <div>
                            <a href="{{ route('admin.appointments') }}" class="btn btn-sm btn-outline-primary">
                                View All <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Dentist</th>
                                    <th>Date & Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_appointments as $appointment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <span class="avatar-text">{{ substr($appointment->patient->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $appointment->patient->name }}</h6>
                                                <small class="text-muted">ID: {{ $appointment->patient->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $appointment->dentist->name }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $appointment->date_heure->format('d M Y') }}</span>
                                            <small class="text-muted">{{ $appointment->date_heure->format('H:i A') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $appointment->statut === 'confirmé' ? 'success' : ($appointment->statut === 'annulé' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($appointment->statut) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-icon">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dentists Overview - Modern Design -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Dentists Overview</h5>
                        <div>
                            <a href="{{ route('admin.dentists') }}" class="btn btn-sm btn-outline-primary">
                                Manage Dentists <i class="fas fa-cog ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Dentist</th>
                                    <th>License Number</th>
                                    <th>Experience</th>
                                    <th>Specialization</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dentists as $dentist)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($dentist->name) }}&background=random" alt="Avatar">
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $dentist->name }}</h6>
                                                <small class="text-muted">{{ $dentist->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $dentist->dentist->license_number }}</td>
                                    <td>{{ $dentist->dentist->years_of_experience }} years</td>
                                    <td>General Dentistry</td>
                                    <td>
                                        <span class="status-badge status-{{ $dentist->dentist->deleted_at ? 'danger' : 'success' }}">
                                            {{ $dentist->dentist->deleted_at ? 'Inactive' : 'Active' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="{{ route('admin.schedule') }}?dentist={{ $dentist->id }}" class="btn btn-icon btn-sm btn-info" data-bs-toggle="tooltip" title="View Schedule">
                                                <i class="fas fa-calendar-alt"></i>
                                            </a>
                                            <button class="btn btn-icon btn-sm btn-secondary" data-bs-toggle="tooltip" title="View Profile">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color: #7367f0;
        --success-color: #28c76f;
        --warning-color: #ff9f43;
        --danger-color: #ea5455;
        --info-color: #00cfe8;
        --dark-color: #4b4b4b;
        --light-color: #f8f8f8;
        --card-bg: rgba(255, 255, 255, 0.9);
        --glass-blur: 12px;
    }

    body {
        background-color: #f5f7fa;
        color: #5e5873;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Glassmorphism Cards */
    .glass-card {
        background: var(--card-bg);
        backdrop-filter: blur(var(--glass-blur));
        -webkit-backdrop-filter: blur(var(--glass-blur));
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .stats-card {
        padding: 1.5rem;
    }

    .stats-label {
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #6e6b7b;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .stats-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #5e5873;
        margin-bottom: 0;
    }

    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .bg-primary-light {
        background-color: rgba(115, 103, 240, 0.12);
        color: var(--primary-color);
    }

    .bg-success-light {
        background-color: rgba(40, 199, 111, 0.12);
        color: var(--success-color);
    }

    .bg-warning-light {
        background-color: rgba(255, 159, 67, 0.12);
        color: var(--warning-color);
    }

    .bg-info-light {
        background-color: rgba(0, 207, 232, 0.12);
        color: var(--info-color);
    }

    .trend-indicator {
        font-size: 0.75rem;
        font-weight: 500;
    }

    .trend-indicator.up {
        color: var(--success-color);
    }

    .trend-indicator.down {
        color: var(--danger-color);
    }

    /* Modern Card Styles */
    .modern-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        border: none;
        overflow: hidden;
    }

    .modern-card .card-header {
        background: white;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }

    .modern-card .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #5e5873;
        margin-bottom: 0;
    }

    .modern-card .card-body {
        padding: 0;
    }

    /* Modern Table Styles */
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table thead tr {
        background-color: #f8f8f8;
    }

    .modern-table th {
        padding: 1rem 1.5rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6e6b7b;
        font-weight: 600;
        border: none;
    }

    .modern-table td {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.03);
        vertical-align: middle;
    }

    .modern-table tr:last-child td {
        border-bottom: none;
    }

    .modern-table tr:hover td {
        background-color: rgba(115, 103, 240, 0.03);
    }

    /* Status Badges */
    .status-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .status-success {
        background-color: rgba(40, 199, 111, 0.12);
        color: var(--success-color);
    }

    .status-warning {
        background-color: rgba(255, 159, 67, 0.12);
        color: var(--warning-color);
    }

    .status-danger {
        background-color: rgba(234, 84, 85, 0.12);
        color: var(--danger-color);
    }

    /* Avatar Styles */
    .avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        overflow: hidden;
    }

    .avatar-sm {
        width: 36px;
        height: 36px;
    }

    .avatar-text {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        background-color: var(--primary-color);
        color: white;
        font-weight: 600;
    }

    /* Button Styles */
    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 8px;
    }

    .btn-outline-primary {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: white;
    }

    /* Progress Bar */
    .progress {
        height: 6px;
        border-radius: 3px;
        background-color: rgba(115, 103, 240, 0.12);
    }

    .progress-bar {
        border-radius: 3px;
    }
</style>

<script>
    // Add any necessary JavaScript for tooltips or other interactions
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection