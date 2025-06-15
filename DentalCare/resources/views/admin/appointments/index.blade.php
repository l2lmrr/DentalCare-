@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                <div class="card-header bg-white border-bottom-0 py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-semibold text-gray-800">Appointment Management</h5>
                        <div class="search-box">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="fas fa-search text-gray-500"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" placeholder="Search appointments...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="ps-4 text-uppercase text-xs font-weight-bolder text-gray-500">Patient</th>
                                    <th class="text-uppercase text-xs font-weight-bolder text-gray-500">Dentist</th>
                                    <th class="text-uppercase text-xs font-weight-bolder text-gray-500">Date & Time</th>
                                    <th class="text-uppercase text-xs font-weight-bolder text-gray-500">Status</th>
                                    <th class="text-uppercase text-xs font-weight-bolder text-gray-500">Notes</th>
                                    <th class="pe-4 text-end text-uppercase text-xs font-weight-bolder text-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @foreach($appointments as $appointment)
                                <tr class="align-middle">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-md me-3">
                                                <span class="avatar-text bg-indigo-100 text-indigo-600">{{ substr($appointment->patient->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $appointment->patient->name }}</h6>
                                                <small class="text-muted">ID: {{ $appointment->patient->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-md me-3">
                                                <span class="avatar-text bg-blue-100 text-blue-600">{{ substr($appointment->dentist->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $appointment->dentist->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">{{ $appointment->date_heure->format('d M Y') }}</span>
                                            <small class="text-muted">{{ $appointment->date_heure->format('h:i A') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $statusColor = [
                                                'confirmé' => 'success',
                                                'annulé' => 'danger',
                                                'en attente' => 'warning'
                                            ][$appointment->statut] ?? 'secondary';
                                        @endphp
                                        <span class="badge rounded-pill bg-{{ $statusColor }}-light text-{{ $statusColor }}">
                                            <i class="fas fa-circle me-1" style="font-size: 8px;"></i> 
                                            {{ ucfirst($appointment->statut) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="appointment-notes">
                                            @if($appointment->notes)
                                                <p class="mb-0 text-truncate" style="max-width: 200px;">{{ $appointment->notes }}</p>
                                                <button class="btn btn-link btn-sm p-0 text-primary view-notes" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#viewNotesModal"
                                                        data-notes="{{ $appointment->notes }}">
                                                    View Full
                                                </button>
                                            @else
                                                <span class="text-muted">No notes</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="pe-4">
                                        <div class="actions d-flex justify-content-end">
                                            <button class="btn btn-icon btn-sm btn-outline-info view-notes me-2" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#viewNotesModal"
                                                    data-notes="{{ $appointment->notes }}"
                                                    data-bs-toggle="tooltip" title="View Notes">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($appointment->statut !== 'annulé')
                                                <form action="{{ route('admin.appointments.cancel', $appointment->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-icon btn-sm btn-outline-danger" 
                                                            onclick="return confirm('Are you sure you want to cancel this appointment?')"
                                                            data-bs-toggle="tooltip" title="Cancel Appointment">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 py-3 px-4">
                    {{ $appointments->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Notes Modal -->
<div class="modal fade" id="viewNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-semibold">Appointment Notes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="notes-content bg-gray-50 rounded-lg p-3">
                    <p id="appointment-notes" class="mb-0"></p>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewButtons = document.querySelectorAll('.view-notes');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('appointment-notes').textContent = this.dataset.notes;
        });
    });

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush

<style>
    /* Card Styles */
    .card {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }
    
    /* Table Styles */
    .table {
        margin-bottom: 0;
    }
    
    .table thead th {
        border-bottom: 1px solid #e5e7eb;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    
    .table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .table tbody tr:hover {
        background-color: #f9fafb;
    }
    
    /* Avatar Styles */
    .avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }
    
    .avatar-md {
        width: 40px;
        height: 40px;
    }
    
    .avatar-text {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        font-weight: 600;
    }
    
    .bg-indigo-100 {
        background-color: rgba(199, 210, 254, 0.5);
    }
    
    .text-indigo-600 {
        color: #4f46e5;
    }
    
    .bg-blue-100 {
        background-color: rgba(191, 219, 254, 0.5);
    }
    
    .text-blue-600 {
        color: #2563eb;
    }
    
    /* Status Badges */
    .bg-success-light {
        background-color: rgba(16, 185, 129, 0.1);
    }
    
    .text-success {
        color: #10b981;
    }
    
    .bg-danger-light {
        background-color: rgba(239, 68, 68, 0.1);
    }
    
    .text-danger {
        color: #ef4444;
    }
    
    .bg-warning-light {
        background-color: rgba(245, 158, 11, 0.1);
    }
    
    .text-warning {
        color: #f59e0b;
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
    
    .btn-outline-info {
        color: #3b82f6;
        border-color: #3b82f6;
    }
    
    .btn-outline-info:hover {
        background-color: #3b82f6;
        color: white;
    }
    
    .btn-outline-danger {
        color: #ef4444;
        border-color: #ef4444;
    }
    
    .btn-outline-danger:hover {
        background-color: #ef4444;
        color: white;
    }
    
    /* Search Box */
    .search-box .input-group {
        width: 250px;
    }
    
    .search-box .input-group-text {
        padding-left: 1rem;
    }
    
    .search-box .form-control {
        padding-right: 1rem;
        border-left: 0;
    }
    
    /* Notes Content */
    .notes-content {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .bg-gray-50 {
        background-color: #f9fafb;
    }
    
    /* Typography */
    .fw-semibold {
        font-weight: 600;
    }
    
    .text-gray-800 {
        color: #1f2937;
    }
</style>
@endsection