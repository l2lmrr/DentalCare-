@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">404 | Page Not Found</h1>
        <p class="text-gray-600 mb-8">The page you're looking for doesn't exist.</p>
        <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
            Return Home
        </a>
    </div>
</div>
@endsection
