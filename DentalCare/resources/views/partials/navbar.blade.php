<nav class="navbar fixed w-full z-50 bg-white py-4 transition-all duration-300">
    <div class="container mx-auto px-6 flex justify-between items-center">
        <a href="/" class="flex items-center text-2xl font-bold text-blue-600">
            <i class="fas fa-tooth mr-2"></i>
            <span>Dental<span class="text-blue-400">Care</span></span>
        </a>
        
        <div class="hidden md:flex items-center space-x-8">
            <a href="/" class="text-gray-700 hover:text-blue-600 transition">Home</a>
            <a href="#services" class="text-gray-700 hover:text-blue-600 transition">Services</a>
            <a href="#dentists" class="text-gray-700 hover:text-blue-600 transition">Our Dentists</a>
            <a href="#testimonials" class="text-gray-700 hover:text-blue-600 transition">Testimonials</a>
            
            @auth                <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    Dashboard
                </a>
                @if(Auth::user()->role === 'patient')
                    <a href="{{ route('patient.medical-records') }}" class="text-gray-700 hover:text-blue-600 transition">
                        Medical Records
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-blue-600 transition">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition">Login</a>
                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    Register
                </a>
            @endauth
        </div>
        
        <button class="md:hidden text-gray-700 focus:outline-none" id="mobile-menu-button">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>
    
    <!-- Mobile menu -->
    <div class="md:hidden hidden bg-white w-full py-4 px-6" id="mobile-menu">
        <div class="flex flex-col space-y-4">
            <a href="/" class="text-gray-700 hover:text-blue-600 transition">Home</a>
            <a href="#services" class="text-gray-700 hover:text-blue-600 transition">Services</a>
            <a href="#dentists" class="text-gray-700 hover:text-blue-600 transition">Our Dentists</a>
            <a href="#testimonials" class="text-gray-700 hover:text-blue-600 transition">Testimonials</a>
            
            @auth                <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition text-center">
                    Dashboard
                </a>
                @if(Auth::user()->role === 'patient')
                    <a href="{{ route('patient.medical-records') }}" class="text-gray-700 hover:text-blue-600 transition">
                        Medical Records
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-blue-600 transition w-full text-left">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition">Login</a>
                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition text-center">
                    Register
                </a>
            @endauth
        </div>
    </div>
</nav>