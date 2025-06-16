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
                @auth
                    @if(auth()->user()->role === 'patient')
                        <a href="{{ route('appointments.calendar') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-medium text-center hover:bg-gray-100 transition duration-300">
                            Book Appointment
                        </a>
                    @else
                        <button disabled class="bg-gray-300 text-gray-600 px-8 py-3 rounded-lg font-medium text-center cursor-not-allowed">
                            Patient Access Only
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-medium text-center hover:bg-gray-100 transition duration-300">
                        Login to Book
                    </a>
                @endauth
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
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Meet Our Expert Dentists</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Our team of qualified professionals is ready to take care of your dental health</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($dentists as $index => $dentist)
            <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition duration-300 slide-up" 
                 style="animation-delay: {{ $index * 0.2 }}s">
                <div class="relative h-64 bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center overflow-hidden">
                    @if($dentist->photo)
                        <img src="{{ asset('storage/' . $dentist->photo) }}" 
                             alt="Dr. {{ $dentist->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="text-center">
                            <i class="fas fa-user-md text-white text-6xl mb-2"></i>
                            <div class="text-white text-lg font-medium">Dr. {{ $dentist->name }}</div>
                        </div>
                    @endif
                    <!-- Status Badge -->
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 text-sm font-medium bg-green-500 text-white rounded-full shadow-lg">
                            Available
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="mb-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Dr. {{ $dentist->name }}</h3>
                        <div class="flex items-center text-blue-600 font-medium space-x-2">
                            <span>General Dentist</span>
                            @if($dentist->dentist && $dentist->dentist->years_of_experience > 0)
                                <span class="text-gray-400">•</span>
                                <span>{{ $dentist->dentist->years_of_experience }}+ Years Experience</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-6">
                        @if($dentist->dentist && $dentist->dentist->bio)
                            <p class="text-gray-600 line-clamp-3">
                                {{ $dentist->dentist->bio }}
                            </p>
                        @else
                            <p class="text-gray-600">
                                Dedicated dental professional 
                                @if($dentist->dentist && $dentist->dentist->license_number)
                                    with license #{{ $dentist->dentist->license_number }},
                                @endif
                                providing comprehensive dental care and personalized treatment plans.
                            </p>
                        @endif
                        
                        @if($dentist->dentist && $dentist->dentist->years_of_experience > 0)
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-certificate text-blue-500 mr-2"></i>
                                Licensed Professional with {{ $dentist->dentist->years_of_experience }} years of experience
                            </div>
                        @endif
                    </div>

                    <!-- Booking Button -->
                    @auth
                        @if(auth()->user()->role === 'patient')
                            <a href="{{ route('appointments.calendar', ['dentist' => $dentist->id]) }}" 
                               class="group inline-flex items-center justify-center w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300 transform hover:-translate-y-0.5">
                                <i class="fas fa-calendar-plus mr-2 group-hover:animate-bounce"></i>
                                Book Appointment
                            </a>
                        @elseif(auth()->user()->role === 'praticien')
                            <div class="text-center">
                                <button disabled 
                                        class="inline-flex items-center justify-center w-full px-4 py-2 bg-gray-100 text-gray-500 rounded-lg cursor-not-allowed">
                                    <i class="fas fa-lock mr-2"></i>
                                    Patient Access Only
                                </button>
                                <p class="text-xs text-gray-500 mt-2">Only patients can book appointments</p>
                            </div>
                        @endif
                    @else
                        <div class="space-y-3">
                            <a href="{{ route('login') }}" 
                               class="inline-flex items-center justify-center w-full px-4 py-2 bg-white border-2 border-blue-600 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all duration-300">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Login to Book
                            </a>
                            <p class="text-xs text-gray-500 text-center">
                                New patient? 
                                <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register here</a>
                            </p>
                        </div>
                    @endauth
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="inline-block p-6 bg-blue-50 rounded-lg">
                    <i class="fas fa-user-md text-blue-400 text-4xl mb-4"></i>
                    <p class="text-gray-600">No dentists available at the moment.</p>
                    <p class="text-sm text-gray-500 mt-2">Please check back later or contact us for more information.</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- View All Dentists Link -->
        <div class="text-center mt-12">
            <a href="{{ route('dentist.index') }}" 
               class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                View All Dentists
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
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
        @auth
            @if(auth()->user()->role === 'patient')
                <a href="{{ route('appointments.calendar') }}" 
                   class="bg-white text-blue-600 px-8 py-3 rounded-lg font-medium hover:bg-gray-100 transition duration-300 inline-flex items-center justify-center">
                    <i class="fas fa-calendar-plus mr-2"></i> Book Your Visit Now
                </a>
            @else
                <button disabled class="bg-gray-300 text-gray-600 px-8 py-3 rounded-lg font-medium cursor-not-allowed inline-flex items-center">
                    <i class="fas fa-lock mr-2"></i> Patient Access Only
                </button>
            @endif
        @else
            <a href="{{ route('login') }}" 
               class="bg-white text-blue-600 px-8 py-3 rounded-lg font-medium hover:bg-gray-100 transition duration-300 inline-flex items-center">
                <i class="fas fa-sign-in-alt mr-2"></i> Login to Book Your Visit
            </a>
        @endauth
    </div>
</section>
@endsection