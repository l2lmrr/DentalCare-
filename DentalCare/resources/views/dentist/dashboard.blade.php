@extends('layouts.app')

@section('title', 'Dentist Dashboard')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center mb-8">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold text-gray-900">Dentist Dashboard</h1>
            <p class="mt-2 text-sm text-gray-700">Manage your appointments and patient records</p>
        </div>
    </div>

    <!-- Appointments Section -->
    <div class="mb-12">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium text-gray-900">Upcoming Appointments</h2>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($appointments as $appointment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $appointment->patient->name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $appointment->date_heure->format('M d, Y h:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $appointment->statut === 'confirmé' ? 'bg-green-100 text-green-800' : 
                                       ($appointment->statut === 'annulé' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($appointment->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="openEditModal({{ $appointment->id }})" 
                                        class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                
                                @if($appointment->statut === 'confirmé')
                                <form action="{{ route('appointments.cancel', $appointment) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900 mr-3">Cancel</button>
                                </form>
                                @elseif($appointment->statut === 'annulé')
                                <form action="{{ route('appointments.confirm', $appointment) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Confirm</button>
                                </form>
                                @endif
                                
                                <button onclick="openMedicalRecordModal({{ $appointment->patient->id }})" 
                                        class="text-purple-600 hover:text-purple-900">Medical Record</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 bg-gray-50 sm:px-6">
                {{ $appointments->links() }}
            </div>
        </div>
    </div>

    <!-- Medical Records Section -->
    <div>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium text-gray-900">Patient Medical Records</h2>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($medicalRecords as $record)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $record->patient->name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $record->updated_at->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="openViewRecordModal({{ $record->id }})" 
                                        class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                                <button onclick="openEditRecordModal({{ $record->id }})" 
                                        class="text-indigo-600 hover:text-indigo-900">Edit</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Appointment Modal -->
<div id="editModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <!-- Modal content will be loaded via AJAX -->
</div>

<!-- Medical Record Modal -->
<div id="medicalRecordModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <!-- Modal content will be loaded via AJAX -->
</div>

<!-- View Record Modal -->
<div id="viewRecordModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <!-- Modal content will be loaded via AJAX -->
</div>

<!-- Edit Record Modal -->
<div id="editRecordModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <!-- Modal content will be loaded via AJAX -->
</div>

<script>
    // Open Edit Appointment Modal
    function openEditModal(appointmentId) {
        fetch(`/appointments/${appointmentId}/edit`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('editModal').innerHTML = html;
                document.getElementById('editModal').classList.remove('hidden');
            });
    }

    // Open Create Medical Record Modal
    function openMedicalRecordModal(patientId) {
        fetch(`/medical-records/create?patient_id=${patientId}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('medicalRecordModal').innerHTML = html;
                document.getElementById('medicalRecordModal').classList.remove('hidden');
            });
    }

    // Open View Medical Record Modal
    function openViewRecordModal(recordId) {
        fetch(`/medical-records/${recordId}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('viewRecordModal').innerHTML = html;
                document.getElementById('viewRecordModal').classList.remove('hidden');
            });
    }

    // Open Edit Medical Record Modal
    function openEditRecordModal(recordId) {
        fetch(`/medical-records/${recordId}/edit`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('editRecordModal').innerHTML = html;
                document.getElementById('editRecordModal').classList.remove('hidden');
            });
    }

    // Close any modal
    function closeModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('medicalRecordModal').classList.add('hidden');
        document.getElementById('viewRecordModal').classList.add('hidden');
        document.getElementById('editRecordModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modals = ['editModal', 'medicalRecordModal', 'viewRecordModal', 'editRecordModal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    }
</script>
@endsection