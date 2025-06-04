@extends('layouts.app')

@section('title', 'Home - DentalCare')

@section('content')
<!-- Hero Section -->
<section class="relative bg-blue-600 text-white py-32">
    <div class="container mx-auto px-6 flex flex-col md:flex-row items-center">
        <div class="md:w-1/2 mb-12 md:mb-0 slide-up">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Your Perfect Smile Starts Here</h1>
            <p class="text-xl mb-8">Experience world-class dental care in a comfortable environment with our team of expert dentists.</p>
            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-medium text-center hover:bg-gray-100 transition duration-300">
                    Book Appointment
                </a>
                <a href="#services" class="bg-blue-700 text-white px-8 py-3 rounded-lg font-medium text-center hover:bg-blue-800 transition duration-300">
                    Our Services
                </a>
            </div>
        </div>
        <div class="md:w-1/2 slide-up" style="animation-delay: 0.2s;">
            <img src="https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" 
                 alt="Dental Clinic" 
                 class="rounded-lg shadow-2xl w-full h-auto">
        </div>
    </div>
    <div class="absolute bottom-0 left-0 right-0 h-16 bg-white transform skew-y-2 -mb-8"></div>
</section>

<!-- Services Section -->
<section id="services" class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 slide-up">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Our Dental Services</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Comprehensive care for all your dental needs</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Service 1 -->
            <div class="bg-gray-50 rounded-xl p-6 shadow-lg hover:shadow-xl transition duration-300 slide-up">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mb-6 mx-auto">
                    <i class="fas fa-tooth text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-center mb-4">Teeth Cleaning</h3>
                <p class="text-gray-600 text-center mb-6">Professional cleaning to remove plaque and tartar, keeping your teeth and gums healthy.</p>
                <a href="#" class="text-blue-600 font-medium flex items-center justify-center">
                    Learn More <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <!-- Service 2 -->
            <div class="bg-gray-50 rounded-xl p-6 shadow-lg hover:shadow-xl transition duration-300 slide-up" style="animation-delay: 0.2s;">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mb-6 mx-auto">
                    <i class="fas fa-teeth text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-center mb-4">Dental Implants</h3>
                <p class="text-gray-600 text-center mb-6">Permanent solution for missing teeth that look, feel, and function like natural teeth.</p>
                <a href="#" class="text-blue-600 font-medium flex items-center justify-center">
                    Learn More <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <!-- Service 3 -->
            <div class="bg-gray-50 rounded-xl p-6 shadow-lg hover:shadow-xl transition duration-300 slide-up" style="animation-delay: 0.4s;">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mb-6 mx-auto">
                    <i class="fas fa-smile text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-center mb-4">Cosmetic Dentistry</h3>
                <p class="text-gray-600 text-center mb-6">Enhance your smile with veneers, whitening, and other cosmetic procedures.</p>
                <a href="#" class="text-blue-600 font-medium flex items-center justify-center">
                    Learn More <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Dentists Section -->
<section id="dentists" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 slide-up">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Meet Our Dentists</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Highly qualified professionals dedicated to your oral health</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($dentists as $index => $dentist)
            <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition duration-300 slide-up" 
                 @if($index > 0) style="animation-delay: {{ $index * 0.2 }}s" @endif>
                <div class="h-64 bg-blue-600 flex items-center justify-center overflow-hidden">
                    @if($dentist->photo)
                        <img src="{{ asset('storage/' . $dentist->photo) }}" alt="Dr. {{ $dentist->user->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-user-md text-white text-8xl"></i>
                    @endif
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-1">Dr. {{ $dentist->user->name }}</h3>
                    <p class="text-blue-600 mb-4">{{ $dentist->specialite ?? 'General Dentist' }}</p>
                    <p class="text-gray-600 mb-4">
                        {{ $dentist->bio ?? 'Specializes in dental procedures with professional expertise.' }}
                    </p>
                    
                    @auth
                        @if(auth()->user()->isPatient())
                            <a href="{{ route('appointment.create', $dentist->id) }}" 
                               class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition duration-300">
                                Book Appointment
                            </a>
                        @elseif(auth()->user()->isDentist())
                            <span class="text-gray-500">Please login as patient to book</span>
                        @endif
                    @else
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition duration-300">
                            Login to Book
                        </a>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 slide-up">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Patient Testimonials</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">What our patients say about us</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Testimonial 1 -->
            <div class="bg-gray-50 rounded-xl p-8 shadow-lg slide-up">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center mr-4">
                        <i class="fas fa-user text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold">James Wilson</h4>
                        <div class="flex text-yellow-400 mt-1">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600">"The best dental experience I've ever had. Dr. Johnson was gentle and thorough. My teeth have never felt cleaner!"</p>
            </div>
            
            <!-- Testimonial 2 -->
            <div class="bg-gray-50 rounded-xl p-8 shadow-lg slide-up" style="animation-delay: 0.2s;">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center mr-4">
                        <i class="fas fa-user text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold">Lisa Thompson</h4>
                        <div class="flex text-yellow-400 mt-1">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600">"I was nervous about getting braces as an adult, but Dr. Chen made the process so easy. The results are amazing!"</p>
            </div>
            
            <!-- Testimonial 3 -->
            <div class="bg-gray-50 rounded-xl p-8 shadow-lg slide-up" style="animation-delay: 0.4s;">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center mr-4">
                        <i class="fas fa-user text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold">Robert Garcia</h4>
                        <div class="flex text-yellow-400 mt-1">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600">"My kids actually look forward to their dental visits thanks to Dr. Rodriguez. She's wonderful with children!"</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-blue-600 text-white">
    <div class="container mx-auto px-6 text-center slide-up">
        <h2 class="text-3xl font-bold mb-6">Ready for a Healthier Smile?</h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto">Schedule your appointment today and experience the DentalCare difference.</p>
        <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-medium hover:bg-gray-100 transition duration-300 inline-block">
            Book Your Visit Now
        </a>
    </div>
</section>
@endsection