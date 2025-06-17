@extends('layouts.app')

@section('title', 'Appointment Details')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Appointment Details
                    </h3>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $appointment->statut === 'confirmé' ? 'bg-green-100 text-green-800' : 
                           ($appointment->statut === 'annulé' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($appointment->statut) }}
                    </span>
                </div>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Dentist</h4>
                        <div class="mt-1 flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="{{ $appointment->dentist->photo_url }}" alt="{{ $appointment->dentist->name }}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">Dr. {{ $appointment->dentist->name }}</div>
                                <div class="text-sm text-gray-500">{{ $appointment->dentist->dentist->specialty ?? 'General Dentist' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Patient</h4>
                        <div class="mt-1">
                            <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->name }}</div>
                            <div class="text-sm text-gray-500">{{ $appointment->patient->email }}</div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Date & Time</h4>
                        <div class="mt-1 text-sm text-gray-900">
                            {{ $appointment->date_heure->format('l, F j, Y \a\t g:i A') }}
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Duration</h4>
                        <div class="mt-1 text-sm text-gray-900">
                            30 minutes
                        </div>
                    </div>
                    
                    @if($appointment->notes)
                    <div class="sm:col-span-2">
                        <h4 class="text-sm font-medium text-gray-500">Notes</h4>
                        <div class="mt-1 text-sm text-gray-900 whitespace-pre-line">
                            {{ $appointment->notes }}
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="mt-8 flex justify-end space-x-3">
                    @if($appointment->statut === 'confirmé')
                    <form action="{{ route('appointments.cancel', $appointment) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Cancel Appointment
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection