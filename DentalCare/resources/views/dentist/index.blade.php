@extends('layouts.app')

@section('title', 'Our Dentists')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center mb-8">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold text-gray-900">Our Dental Specialists</h1>
            <p class="mt-2 text-sm text-gray-700">Choose a dentist to book an appointment</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($dentists as $dentist)
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-16 w-16">
                        <img class="h-16 w-16 rounded-full" src="{{ $dentist->dentist->photo_url ?? asset('images/default-dentist.jpg') }}" alt="{{ $dentist->name }}">
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Dr. {{ $dentist->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $dentist->dentist->specialty ?? 'General Dentist' }}</p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h4 class="text-sm font-medium text-gray-500">About</h4>
                    <p class="mt-1 text-sm text-gray-900 line-clamp-3">
                        {{ $dentist->dentist->bio ?? 'No bio available.' }}
                    </p>
                </div>

                <div class="mt-4">
                    <h4 class="text-sm font-medium text-gray-500">Experience</h4>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $dentist->dentist->years_of_experience ?? 0 }} years
                    </p>
                </div>                <div class="mt-6">
                    <a href="{{ route('appointments.calendar', ['id' => $dentist->id]) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Book Appointment
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $dentists->links() }}
    </div>
</div>
@endsection