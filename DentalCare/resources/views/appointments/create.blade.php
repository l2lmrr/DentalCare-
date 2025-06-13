<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Book Appointment with Dr. {{ $dentist->name }}</h3>
            
            <form action="{{ route('appointment.store') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="dentist_id" value="{{ $dentist->id }}">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="date_heure">
                        Select Date & Time
                    </label>
                    <select name="date_heure" id="date_heure" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select a time slot</option>
                        @foreach($availableSlots as $slot)
                        <option value="{{ $slot }}">
                            {{ \Carbon\Carbon::parse($slot)->format('l, F j, Y \a\t g:i A') }}
                        </option>
                        @endforeach
                    </select>
                    @error('date_heure')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="px-4 py-3 sm:flex sm:flex-row-reverse">
                    <button type="submit" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Confirm Appointment
                    </button>
                    <button type="button" onclick="closeModal()" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
            
            <div class="mt-6">
                <h4 class="text-md font-medium text-gray-900">Dr. {{ $dentist->name }}'s Working Hours</h4>
                <ul class="mt-2 space-y-1">
                    @foreach($workingHours as $hours)
                        <li class="text-sm text-gray-700">
                            {{ ucfirst($hours->jour) }}: {{ $hours->heure_debut }} - {{ $hours->heure_fin }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>