@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                <div class="card-header bg-white border-bottom-0 py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-semibold text-gray-800">Dentist Management</h5>
                        <button class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#addDentistModal">
                            <i class="fas fa-plus me-2"></i> Add Dentist
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="ps-4 text-uppercase text-xs font-weight-bolder text-gray-500">Dentist</th>
                                    <th class="text-uppercase text-xs font-weight-bolder text-gray-500">Contact</th>
                                    <th class="text-uppercase text-xs font-weight-bolder text-gray-500">Professional Info</th>
                                    <th class="text-uppercase text-xs font-weight-bolder text-gray-500">Status</th>
                                    <th class="pe-4 text-end text-uppercase text-xs font-weight-bolder text-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @foreach($dentists as $dentist)
                                <tr class="align-middle">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-md me-3">
                                                <span class="avatar-text bg-primary-light text-primary">{{ substr($dentist->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $dentist->name }}</h6>
                                                <small class="text-muted">ID: {{ $dentist->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="contact-info">
                                            <div class="d-flex align-items-center text-nowrap">
                                                <i class="fas fa-envelope text-muted me-2"></i>
                                                <span class="text-truncate" style="max-width: 200px;">{{ $dentist->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="professional-info">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="fas fa-id-card text-muted me-2"></i>
                                                <span>{{ $dentist->dentist->license_number }}</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-briefcase text-muted me-2"></i>
                                                <span>{{ $dentist->dentist->years_of_experience }} years experience</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-{{ $dentist->dentist->deleted_at ? 'danger' : 'success' }}-light text-{{ $dentist->dentist->deleted_at ? 'danger' : 'success' }}">
                                            <i class="fas fa-circle me-1" style="font-size: 8px;"></i> {{ $dentist->dentist->deleted_at ? 'Inactive' : 'Active' }}
                                        </span>
                                    </td>
                                    <td class="pe-4">
                                        <div class="actions d-flex justify-content-end">
                                            <button class="btn btn-icon btn-sm btn-outline-info edit-dentist me-2" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editDentistModal"
                                                    data-id="{{ $dentist->id }}"
                                                    data-name="{{ $dentist->name }}"
                                                    data-email="{{ $dentist->email }}"
                                                    data-license="{{ $dentist->dentist->license_number }}"
                                                    data-experience="{{ $dentist->dentist->years_of_experience }}"
                                                    data-bio="{{ $dentist->dentist->bio }}"
                                                    data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-icon btn-sm {{ $dentist->dentist->deleted_at ? 'btn-outline-success' : 'btn-outline-danger' }} toggle-status me-2"
                                                    data-id="{{ $dentist->id }}"
                                                    data-bs-toggle="tooltip" title="{{ $dentist->dentist->deleted_at ? 'Activate' : 'Deactivate' }}">
                                                <i class="fas fa-{{ $dentist->dentist->deleted_at ? 'check' : 'ban' }}"></i>
                                            </button>
                                            <a href="{{ route('admin.schedule') }}?dentist={{ $dentist->id }}" 
                                               class="btn btn-icon btn-sm btn-outline-warning"
                                               data-bs-toggle="tooltip" title="Schedule">
                                                <i class="fas fa-calendar-alt"></i>
                                            </a>
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

<!-- Add Dentist Modal -->
<div class="modal fade" id="addDentistModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.dentists.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-semibold">Add New Dentist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control rounded-lg" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control rounded-lg" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" class="form-control rounded-lg" name="password" required>
                            <i class="fas fa-eye-slash toggle-password"></i>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">License Number</label>
                        <input type="text" class="form-control rounded-lg" name="license_number" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Years of Experience</label>
                        <input type="number" class="form-control rounded-lg" name="years_of_experience" required min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Professional Bio</label>
                        <textarea class="form-control rounded-lg" name="bio" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill">Add Dentist</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Dentist Modal -->
<div class="modal fade" id="editDentistModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="editDentistForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-semibold">Edit Dentist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control rounded-lg" name="name" id="edit-name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control rounded-lg" name="email" id="edit-email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">License Number</label>
                        <input type="text" class="form-control rounded-lg" name="license_number" id="edit-license" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Years of Experience</label>
                        <input type="number" class="form-control rounded-lg" name="years_of_experience" id="edit-experience" required min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Professional Bio</label>
                        <textarea class="form-control rounded-lg" name="bio" id="edit-bio" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Edit Dentist
    const editButtons = document.querySelectorAll('.edit-dentist');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const form = document.getElementById('editDentistForm');
            form.action = `/admin/dentists/${id}`;
            
            document.getElementById('edit-name').value = this.dataset.name;
            document.getElementById('edit-email').value = this.dataset.email;
            document.getElementById('edit-license').value = this.dataset.license;
            document.getElementById('edit-experience').value = this.dataset.experience;
            document.getElementById('edit-bio').value = this.dataset.bio;
        });
    });

    // Toggle Status
    const toggleButtons = document.querySelectorAll('.toggle-status');
    toggleButtons.forEach(button => {
        button.addEventListener('click', async function() {
            const id = this.dataset.id;
            try {
                const response = await fetch(`/admin/dentists/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });

    // Toggle password visibility
    const togglePassword = document.querySelector('.toggle-password');
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const passwordInput = this.previousElementSibling;
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }

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
    
    .card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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
    
    .bg-primary-light {
        background-color: rgba(79, 70, 229, 0.1);
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
    
    .btn-outline-success {
        color: #10b981;
        border-color: #10b981;
    }
    
    .btn-outline-success:hover {
        background-color: #10b981;
        color: white;
    }
    
    .btn-outline-warning {
        color: #f59e0b;
        border-color: #f59e0b;
    }
    
    .btn-outline-warning:hover {
        background-color: #f59e0b;
        color: white;
    }
    
    /* Form Styles */
    .password-input-wrapper {
        position: relative;
    }
    
    .toggle-password {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #9ca3af;
    }
    
    /* Typography */
    .fw-semibold {
        font-weight: 600;
    }
    
    .text-gray-800 {
        color: #1f2937;
    }
    
    .bg-gray-50 {
        background-color: #f9fafb;
    }
</style>
@endsection