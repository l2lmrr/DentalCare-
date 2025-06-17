<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Reschedule Appointment</h3>
            
            <form action="{{ route('appointments.update', $appointment) }}" method="POST" class="mt-4">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="date_heure">
                        New Date & Time
                    </label>
                    <select name="date_heure" id="date_heure" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @foreach($availableSlots as $slot)
                        <option value="{{ $slot }}" {{ $appointment->date_heure->format('Y-m-d H:i:s') == $slot ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($slot)->format('l, F j, Y \a\t g:i A') }}
                        </option>
                        @endforeach
                    </select>
                    @error('date_heure')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="statut">
                        Status
                    </label>
                    <select name="statut" id="statut" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="confirmé" {{ $appointment->statut == 'confirmé' ? 'selected' : '' }}>Confirmed</option>
                        <option value="annulé" {{ $appointment->statut == 'annulé' ? 'selected' : '' }}>Cancelled</option>
                        <option value="reporté" {{ $appointment->statut == 'reporté' ? 'selected' : '' }}>Rescheduled</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="notes">
                        Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('notes', $appointment->notes) }}</textarea>
                </div>
                
                <div class="px-4 py-3 sm:flex sm:flex-row-reverse">
                    <button type="submit" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Update Appointment
                    </button>
                    <button type="button" onclick="closeModal()" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>