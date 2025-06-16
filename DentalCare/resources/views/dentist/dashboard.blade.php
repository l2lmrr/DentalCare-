@extends('layouts.app')

@section('title', 'Dentist Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">Dentist Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">Welcome, {{ auth()->user()->name }}</span>
                    <div class="relative">
                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold cursor-pointer">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Today's Appointments -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Today's Appointments</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $todayAppointments->count() }}</div>
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Appointments -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">                            <dt class="text-sm font-medium text-gray-500 truncate">Upcoming Appointments</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $upcomingAppointmentsCount }}</div>
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Patients -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Patients</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $activePatientsCount }}</div>
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button id="appointments-tab" class="tab-button border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Appointment Schedule
                </button>
                <button id="patients-tab" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Patient Records
                </button>
                <button id="prescriptions-tab" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Prescriptions
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="py-6">
            <!-- Appointments Tab Content -->
            <div id="appointments-content" class="tab-content">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Appointment Schedule</h2>
                    <div class="relative w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="appointment-search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Search appointments...">
                    </div>
                </div>

                <!-- Today's Appointments -->
                <div class="mb-10">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Today's Appointments</h3>
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($todayAppointments as $appointment)
                                    <tr class="appointment-row hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">
                                                    {{ substr($appointment->patient->name, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->name }}</div>
                                                    <div class="text-sm text-gray-500">ID: {{ $appointment->patient->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $appointment->date_heure->format('h:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $appointment->statut === 'confirmé' ? 'bg-green-100 text-green-800' : 
                                                   ($appointment->statut === 'annulé' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($appointment->statut) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="openEditAppointmentModal({{ $appointment->id }})" 
                                                    class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                            
                                            @if($appointment->statut === 'confirmé')
                                            <form action="{{ route('appointments.cancel', $appointment) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to cancel this appointment?')"
                                                        class="text-red-600 hover:text-red-900 mr-3">Cancel</button>
                                            </form>
                                            @elseif($appointment->statut === 'annulé')
                                            <form action="{{ route('appointments.confirm', $appointment) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Confirm</button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Appointments -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Upcoming Appointments</h3>
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
                                    <tr class="appointment-row hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">
                                                    {{ substr($appointment->patient->name, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->name }}</div>
                                                    <div class="text-sm text-gray-500">ID: {{ $appointment->patient->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $appointment->date_heure->format('M d, Y h:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $appointment->statut === 'confirmé' ? 'bg-green-100 text-green-800' : 
                                                   ($appointment->statut === 'annulé' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($appointment->statut) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="openEditAppointmentModal({{ $appointment->id }})" 
                                                    class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                            
                                            @if($appointment->statut === 'confirmé')
                                            <form action="{{ route('appointments.cancel', $appointment) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to cancel this appointment?')"
                                                        class="text-red-600 hover:text-red-900 mr-3">Cancel</button>
                                            </form>
                                            @elseif($appointment->statut === 'annulé')
                                            <form action="{{ route('appointments.confirm', $appointment) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Confirm</button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Patient Records Tab Content -->
            <div id="patients-content" class="tab-content hidden">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Patient Records</h2>
                    <div class="relative w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="patient-search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Search patients...">
                    </div>
                </div>

                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Visit</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Records</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($patients as $patient)
                                <tr class="patient-row hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">
                                                {{ substr($patient->name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $patient->name }}</div>
                                                <div class="text-sm text-gray-500">ID: {{ $patient->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $patient->last_visit ?? 'Never' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $patient->records_count }} records
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="openPatientRecordsModal({{ $patient->id }})" 
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">View All</button>                                        <button onclick="openNewRecordModal({{ $patient->id }}, '{{ $patient->name }}')" 
                                                class="text-green-600 hover:text-green-900">Add Record</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Prescriptions Tab Content -->
            <div id="prescriptions-content" class="tab-content hidden">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Prescriptions</h2>
                    <div class="relative w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="prescription-search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Search prescriptions...">
                    </div>
                </div>

                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medication</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($prescriptions as $prescription)
                                <tr class="prescription-row hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">
                                                {{ substr($prescription->patient->name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $prescription->patient->name }}</div>
                                                <div class="text-sm text-gray-500">ID: {{ $prescription->patient->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $prescription->created_at->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ Str::limit($prescription->medication, 50) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="openViewPrescriptionModal({{ $prescription->id }})" 
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">View</button>
                                        <button onclick="openEditPrescriptionModal({{ $prescription->id }})" 
                                                class="text-green-600 hover:text-green-900">Edit</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- All Modals -->
<div id="editAppointmentModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
        <!-- Modal panel animation -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Edit Appointment
                    </h3>
                    <div class="mt-2">
                        <form id="editAppointmentForm" method="POST" onsubmit="return handleFormSubmit(event)">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="appointment_id" id="appointment_id">
                            <div class="space-y-4">
                                <div>
                                    <label for="appointment_date" class="block text-sm font-medium text-gray-700">Date</label>
                                    <input type="date" id="appointment_date" name="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                </div>
                                <div>
                                    <label for="appointment_time" class="block text-sm font-medium text-gray-700">Time</label>
                                    <input type="time" id="appointment_time" name="time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                </div>
                                <div>
                                    <label for="appointment_status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select id="appointment_status" name="statut" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="confirmé">Confirmed</option>
                                        <option value="en_attente">Pending</option>
                                        <option value="annulé">Cancelled</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="appointment_notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                    <textarea id="appointment_notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                    Save changes
                </button>
                <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                    Cancel
                </button>
            </div>
        </form>
            
        <div id="successMessage" class="hidden fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
            Appointment updated successfully!
        </div>
        </div>
    </div>
</div>

<div id="editModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <!-- Modal content will be loaded via AJAX -->
</div>

<div id="medicalRecordModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <!-- Modal content will be loaded via AJAX -->
</div>

<div id="patientRecordsModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <!-- Modal content will be loaded via AJAX -->
</div>

<div id="newRecordModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    @include('modals.create-medical-record', ['patient' => $patient])
</div>

<div id="viewPrescriptionModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <!-- Modal content will be loaded via AJAX -->
</div>

<div id="editPrescriptionModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <!-- Modal content will be loaded via AJAX -->
</div>

<script>
    // Tab Switching
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = ['appointments', 'patients', 'prescriptions'];
        
        tabs.forEach(tab => {
            const tabButton = document.getElementById(`${tab}-tab`);
            const tabContent = document.getElementById(`${tab}-content`);
            
            tabButton.addEventListener('click', () => {
                // Hide all tabs
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });
                
                // Deactivate all tab buttons
                document.querySelectorAll('.tab-button').forEach(button => {
                    button.classList.remove('border-indigo-500', 'text-indigo-600');
                    button.classList.add('border-transparent', 'text-gray-500');
                });
                
                // Show selected tab
                tabContent.classList.remove('hidden');
                
                // Activate selected tab button
                tabButton.classList.remove('border-transparent', 'text-gray-500');
                tabButton.classList.add('border-indigo-500', 'text-indigo-600');
            });
        });
        
        // Search functionality
        document.getElementById('appointment-search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.appointment-row').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
        
        document.getElementById('patient-search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.patient-row').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
        
        document.getElementById('prescription-search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.prescription-row').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    });

    function handleFormSubmit(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        
        // Combine date and time
        const date = formData.get('date');
        const time = formData.get('time');
        formData.set('date_heure', `${date} ${time}`);
        
        // Get the appointment ID
        const appointmentId = document.getElementById('appointment_id').value;
        
        // Submit the form
        fetch(`/appointments/${appointmentId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Show success message
            const successMessage = document.getElementById('successMessage');
            successMessage.classList.remove('hidden');
            
            // Hide message and close modal after delay
            setTimeout(() => {
                successMessage.classList.add('hidden');
                closeModal();
                // Reload page to show updated data
                window.location.reload();
            }, 2000);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating appointment');
        });

        return false;
    }

    function openEditAppointmentModal(appointmentId) {
        const modal = document.getElementById('editAppointmentModal');
        const form = document.getElementById('editAppointmentForm');
        
        if (!modal || !form) {
            console.error('Modal or form not found');
            return;
        }

        // Set the appointment ID
        document.getElementById('appointment_id').value = appointmentId;
        
        // Show modal
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        // Fetch appointment data
        fetch(`/appointments/${appointmentId}`)
            .then(response => response.json())
            .then(data => {
                const dateTime = new Date(data.date_heure);
                document.getElementById('appointment_date').value = dateTime.toISOString().split('T')[0];
                document.getElementById('appointment_time').value = dateTime.toTimeString().slice(0, 5);
                document.getElementById('appointment_status').value = data.statut;
                document.getElementById('appointment_notes').value = data.notes || '';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading appointment data');
            });
    }    function closeModal(modalId = 'editAppointmentModal') {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Other Modal Functions
    function openEditModal(appointmentId) {
        fetch(`/appointments/${appointmentId}/edit`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                const modalContent = document.getElementById('editModal');
                modalContent.innerHTML = html;
                modalContent.classList.remove('hidden');
                // Add backdrop and center modal
                modalContent.innerHTML = `
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="fixed inset-0 z-10 overflow-y-auto">
                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                            ${html}
                        </div>
                    </div>`;
            })
            .catch(error => {
                console.error('Error loading edit form:', error);
                alert('Error loading edit form. Please try again.');
            });
    }

    function openMedicalRecordModal(patientId) {
        fetch(`/medical-records/create?patient_id=${patientId}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('medicalRecordModal').innerHTML = html;
                document.getElementById('medicalRecordModal').classList.remove('hidden');
            });
    }

    function openPatientRecordsModal(patientId) {
        fetch(`/patients/${patientId}/records`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('patientRecordsModal').innerHTML = html;
                document.getElementById('patientRecordsModal').classList.remove('hidden');
            });
    }    function openNewRecordModal(patientId, patientName) {
        document.getElementById('selectedPatientName').textContent = patientName;
        document.getElementById('form_patient_id').value = patientId;
        document.getElementById('newRecordModal').classList.remove('hidden');
    }

    function openViewPrescriptionModal(prescriptionId) {
        fetch(`/prescriptions/${prescriptionId}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('viewPrescriptionModal').innerHTML = html;
                document.getElementById('viewPrescriptionModal').classList.remove('hidden');
            });
    }

    function openEditPrescriptionModal(prescriptionId) {
        fetch(`/prescriptions/${prescriptionId}/edit`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('editPrescriptionModal').innerHTML = html;
                document.getElementById('editPrescriptionModal').classList.remove('hidden');
            });
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        document.querySelectorAll('[id$="Modal"]').forEach(modal => {
            if (event.target === modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });
    }
</script>
@endsection 