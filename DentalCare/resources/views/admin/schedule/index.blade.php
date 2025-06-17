@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title-modern">Working Hours Management</h5>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                            <i class="fas fa-plus me-2"></i> Add Schedule
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Dentist</th>
                                    <th>Day</th>
                                    <th>Working Hours</th>
                                    <th>Duration</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>                            <tbody>
                                @forelse($schedules as $schedule)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <span class="avatar-text">{{ substr($schedule['dentist']->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Dr. {{ $schedule['dentist']->name }}</h6>
                                                <small class="text-muted">ID: {{ $schedule['dentist']->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="day-badge">{{ $schedule['day'] }}</span>
                                    </td>
                                    <td>
                                        <div class="time-slot">
                                            <span class="start-time">{{ date('h:i A', strtotime($schedule['start_time'])) }}</span>
                                            <span class="time-separator">to</span>
                                            <span class="end-time">{{ date('h:i A', strtotime($schedule['end_time'])) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="duration-badge">
                                            {{ $schedule['duration'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <button type="button" class="btn btn-icon btn-sm btn-danger" onclick="deleteSchedule({{ $schedule['id'] }})" data-bs-toggle="tooltip" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="fas fa-calendar-times fa-2x mb-3"></i>
                                            <h5>No Schedules Found</h5>
                                            <p class="text-muted">Add working hours for dentists</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.schedule.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Working Hours</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Dentist</label>
                        <select class="form-select modern-select" name="dentist_id" required>
                            <option value="">Select Dentist</option>
                            @foreach($dentists as $dentist)
                                <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Day of Week</label>                        <select class="form-select modern-select" name="jour" required>
                            @foreach($days as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Time</label>
                            <div class="time-input-wrapper">
                                <input type="time" class="form-control modern-time-input" name="heure_debut" required>
                                <i class="fas fa-clock time-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Time</label>
                            <div class="time-input-wrapper">
                                <input type="time" class="form-control modern-time-input" name="heure_fin" required>
                                <i class="fas fa-clock time-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Table Styles */
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table th {
        background-color: #f8f8f8;
        padding: 1rem 1.5rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-secondary);
        font-weight: 600;
        border: none;
    }

    .modern-table td {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid var(--border-color);
        vertical-align: middle;
    }

    .modern-table tr:last-child td {
        border-bottom: none;
    }

    .modern-table tr:hover td {
        background-color: rgba(115, 103, 240, 0.03);
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

    /* Badge Styles */
    .day-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        background-color: rgba(115, 103, 240, 0.1);
        color: var(--primary-color);
        font-weight: 500;
        font-size: 0.8rem;
    }

    .duration-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        background-color: rgba(40, 199, 111, 0.1);
        color: var(--success-color);
        font-weight: 500;
        font-size: 0.8rem;
    }

    /* Time Slot Styles */
    .time-slot {
        display: flex;
        align-items: center;
    }

    .start-time, .end-time {
        font-weight: 500;
        color: var(--text-primary);
    }

    .time-separator {
        margin: 0 0.5rem;
        color: var(--text-secondary);
        font-size: 0.8rem;
    }

    /* No Schedule Styles */
    .no-schedule {
        color: var(--secondary-color);
        font-size: 0.9rem;
    }

    /* Empty State Styles */
    .empty-state {
        text-align: center;
        padding: 2rem 0;
    }

    .empty-state i {
        color: var(--secondary-color);
    }

    .empty-state h5 {
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        border-bottom: 1px solid var(--border-color);
        padding: 1.25rem 1.5rem;
    }

    .modal-title {
        font-weight: 600;
        color: var(--text-primary);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid var(--border-color);
        padding: 1rem 1.5rem;
    }

    /* Form Styles */
    .form-label {
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .modern-select {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .modern-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(115, 103, 240, 0.2);
    }

    .time-input-wrapper {
        position: relative;
    }

    .modern-time-input {
        padding-left: 2.5rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .modern-time-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(115, 103, 240, 0.2);
    }

    .time-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--secondary-color);
    }

    /* Button Styles */
    .btn-outline-secondary {
        border-color: var(--secondary-color);
        color: var(--secondary-color);
    }

    .btn-outline-secondary:hover {
        background-color: var(--secondary-color);
        color: white;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 8px;
    }
</style>

<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    function deleteSchedule(scheduleId) {
        if (confirm('Are you sure you want to delete this schedule?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/schedule/' + scheduleId;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection