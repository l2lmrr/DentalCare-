@extends('layouts.app')

@section('title', 'Patient Dashboard')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header with Profile and Logout -->
    <div class="flex justify-between items-center mb-6 bg-white p-4 rounded-lg shadow">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Dashboard</h1>
            <p class="text-sm text-gray-600">Welcome, {{ auth()->user()->name }}</p>
        </div>
        <div class="flex items-center space-x-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-red-600 hover:text-red-800 focus:outline-none transition-colors duration-200">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </button>
            </form>
            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
        </div>
    </div>

    <!-- Appointments Section Header -->
    <div class="sm:flex sm:items-center mb-8">
        <div class="sm:flex-auto">
            <h2 class="text-xl font-semibold text-gray-900">My Appointments</h2>
            <p class="mt-2 text-sm text-gray-700">Manage your dental appointments</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('dentist.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Book New Appointment
            </a>
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if($appointments->isEmpty())
            <div class="p-6 text-center text-gray-500">
                You don't have any appointments yet. 
                <a href="{{ route('dentist.index') }}" class="text-blue-600 hover:text-blue-800">Book your first appointment</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dentist</th>
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
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ $appointment->dentist->photo_url }}" alt="{{ $appointment->dentist->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Dr. {{ $appointment->dentist->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $appointment->dentist->dentist->specialty ?? 'General Dentist' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $appointment->date_heure->format('l, F j, Y \a\t g:i A') }}
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
                                <button onclick="openEditModal('{{ route('appointments.edit', $appointment) }}')" 
                                        class="text-blue-600 hover:text-blue-900 mr-3">Reschedule</button>
                                
                                @if($appointment->statut === 'confirmé')
                                <form action="{{ route('appointments.cancel', $appointment) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900">Cancel</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 bg-gray-50 sm:px-6">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Edit Appointment Modal Container -->
<div id="modal-container" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <!-- Modal content will be loaded here via AJAX -->
</div>

<script>
    // Open Edit Appointment Modal
    function openEditModal(url) {
        fetch(url)
            .then(response => response.text())
            .then(html => {
                document.getElementById('modal-container').innerHTML = html;
                document.getElementById('modal-container').classList.remove('hidden');
            });
    }

    // Close modal
    function closeModal() {
        document.getElementById('modal-container').classList.add('hidden');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('modal-container');
        if (event.target === modal) {
            closeModal();
        }
    }

    // Handle modal form submissions
    document.addEventListener('submit', function(e) {
        if (e.target.closest('#modal-container form')) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                }
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.text();
                }
            })
            .then(html => {
                if (html) {
                    document.getElementById('modal-container').innerHTML = html;
                }
            });
        }
    });
</script>
@endsection