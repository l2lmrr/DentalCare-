@extends('layouts.app')

@section('title', 'My Medical Records')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header with Profile and Logout -->
    <div class="flex justify-between items-center mb-6 bg-white p-4 rounded-lg shadow">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Medical Records</h1>
            <p class="text-sm text-gray-600">Your complete dental treatment history</p>
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

    <!-- Records Section Header -->
    <div class="sm:flex sm:items-center mb-8">
        <div class="sm:flex-auto">
            <h2 class="text-xl font-semibold text-gray-900">Treatment History</h2>
            <p class="mt-2 text-sm text-gray-700">All your dental records in one place</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <button onclick="printRecords()" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-print mr-2"></i>Print Records
            </button>
        </div>
    </div>

    <!-- Records Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if($medicalRecords->isEmpty())
            <div class="p-6 text-center text-gray-500">
                No medical records found. Your treatment history will appear here after visits.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dentist</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosis</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Treatment</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prescription</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($medicalRecords as $record)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $record->created_at->format('M d, Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $record->created_at->format('h:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ $record->dentist->photo_url ?? asset('images/default-dentist.jpg') }}" alt="{{ $record->dentist->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Dr. {{ $record->dentist->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $record->dentist->dentist->specialty ?? 'General Dentist' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $record->diagnostic }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $record->traitement }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($record->prescription)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Provided
                                </span>
                                @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    None
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="openRecordModal('{{ route('medical-records.show', $record) }}')" 
                                        class="text-blue-600 hover:text-blue-900">View Details</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 bg-gray-50 sm:px-6">
                {{ $medicalRecords->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Record Details Modal Container -->
<div id="modal-container" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <!-- Modal content will be loaded here via AJAX -->
</div>

<script>
    // Open Record Details Modal
    function openRecordModal(url) {
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

    // Print records
    function printRecords() {
        window.print();
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('modal-container');
        if (event.target === modal) {
            closeModal();
        }
    }
</script>
@endsection