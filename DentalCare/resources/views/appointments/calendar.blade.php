@extends('layouts.app')

@section('title', 'Book Appointment')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-blue-500">                <h3 class="text-xl font-bold text-white">
                    Book Appointment with Dr. {{ $dentist->name }}
                </h3>
                <p class="text-blue-100 mt-1">{{ $dentist->specialty ?? 'General Dentist' }}</p>
            </div>
            <div class="px-6 py-6">
                <div class="grid grid-cols-1 lg:grid-cols-7 gap-8">
                    <div class="lg:col-span-5">
                        <div id="calendar" class="rounded-xl border border-gray-200 overflow-hidden"></div>
                    </div>
                    <div class="lg:col-span-2">
                        <div id="timeSlotsContainer" class="hidden">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Time Slots</h3>
                            <div id="selectedDate" class="text-sm text-blue-600 font-medium mb-3"></div>
                            <div id="timeSlots" class="grid grid-cols-1 gap-2 max-h-[400px] overflow-y-auto">
                                <!-- Time slots will be populated here -->
                                testing
                            </div>
                            
                            <form id="appointmentForm" action="{{ route('appointment.store') }}" method="POST" class="mt-6 hidden">
                                @csrf
                                <input type="hidden" name="dentist_id" value="{{ $dentist->id }}">
                                <input type="hidden" name="date_heure" id="selectedDateTime">
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                                    </div>
                                    
                                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                        <h5 class="text-sm font-medium text-blue-800">Selected Time:</h5>
                                        <p id="selectedTimeDisplay" class="mt-1 text-sm text-blue-600 font-medium"></p>
                                    </div>
                                    
                                    <button type="submit" onclick="return confirmBooking(event)" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-base font-medium text-white hover:from-blue-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                        Confirm Appointment
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.2.8/main.min.css' rel='stylesheet' />
<style>
    #calendar {
        height: 550px;
        margin: 0 auto;
        background-color: white;
        border-radius: 0.75rem;
    }
    
    /* Calendar header */
    .fc-header-toolbar {
        padding: 1rem 1rem 0;
        margin-bottom: 0.5rem !important;
    }
    
    /* Calendar buttons */
    .fc-button {
        background-color: #fff !important;
        border-color: #e5e7eb !important;
        color: #374151 !important;
        text-transform: capitalize !important;
        font-weight: 500 !important;
        padding: 0.4rem 1rem !important;
        border-radius: 0.375rem !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }
    
    .fc-button:hover {
        background-color: #f9fafb !important;
    }
    
    .fc-button-active {
        background-color: #f3f4f6 !important;
    }
    
    .fc-button-primary:not(:disabled).fc-button-active {
        background-color: #3b82f6 !important;
        border-color: #3b82f6 !important;
        color: white !important;
    }
    
    /* Calendar title */
    .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
    }
    
    /* Calendar cells */
    .fc-daygrid-day {
        padding: 4px;
        border-color: #f3f4f6 !important;
    }
    
    .fc-daygrid-day-top {
        justify-content: center;
    }
    
    .fc-daygrid-day-number {
        font-weight: 500;
        color: #374151;
        padding: 0.25rem;
        width: 1.75rem;
        height: 1.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
    }
    
    .fc-day-today .fc-daygrid-day-number {
        background-color: #3b82f6;
        color: white;
    }
    
    .fc-day-future:hover {
        background-color: #f8fafc;
        cursor: pointer;
    }
    
    .fc-day-selected {
        background-color: #e0f2fe !important;
    }
    
    .fc-day-selected .fc-daygrid-day-number {
        background-color: #2563eb;
        color: white;
    }
    
    .fc-day-past {
        background-color: #f9fafb;
        cursor: not-allowed !important;
    }
    
    .fc-day-past .fc-daygrid-day-number {
        color: #9ca3af;
    }
    
    .fc-daygrid-day.fc-day-sat,
    .fc-daygrid-day.fc-day-sun {
        background-color: #f9fafb;
        cursor: not-allowed !important;
    }
    
    /* Time slots styling */
    .time-slot {
        padding: 0.75rem 0.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.9rem;
        font-weight: 500;
        position: relative;
        overflow: hidden;
        background-color: white;
    }
    
    .time-slot:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .time-slot.selected {
        background-color: #3b82f6;
        color: white;
        border-color: #2563eb;
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3), 0 2px 4px -1px rgba(59, 130, 246, 0.1);
    }
    
    .time-slot.morning {
        background-color: #fef9c3;
        border-left: 4px solid #f59e0b;
    }
    
    .time-slot.afternoon {
        background-color: #dbeafe;
        border-left: 4px solid #3b82f6;
    }
    
    .time-slot.selected .time-slot-badge {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
    }
    
    .time-slot-badge {
        position: absolute;
        top: 0;
        right: 0;
        font-size: 0.65rem;
        padding: 0.15rem 0.4rem;
        background-color: #f3f4f6;
        color: #6b7280;
        border-bottom-left-radius: 0.375rem;
        font-weight: 600;
    }
    
    /* Loading spinner */
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/combine/npm/@fullcalendar/core@6.1.8/index.global.min.js,npm/@fullcalendar/daygrid@6.1.8/index.global.min.js,npm/@fullcalendar/timegrid@6.1.8/index.global.min.js,npm/@fullcalendar/interaction@6.1.8/index.global.min.js'></script>
<script>
function confirmBooking(event) {
    event.preventDefault();
    const selectedTime = document.getElementById('selectedTimeDisplay').textContent;
    const notes = document.getElementById('notes').value;
    
    const message = `Are you sure you want to book this appointment?\n\nTime: ${selectedTime}${notes ? '\nNotes: ' + notes : ''}`;
    
    if (confirm(message)) {
        event.target.closest('form').submit();
        return true;
    }
    return false;
}

document.addEventListener('DOMContentLoaded', function() {
    const dentistId = {{ $dentist->id }};
    console.log('Dentist ID:', dentistId);

    const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth'
        },
        selectable: true,
        select: function(info) {
            const selectedDate = info.start;
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                alert('Cannot select past dates');
                return;
            }
            fetchTimeSlots(selectedDate);
        },
        validRange: {
            start: new Date(),
        },
        // Add custom content to date cells (optional enhancement)
        dayCellDidMount: function(info) {
            // You can add custom content to date cells here if needed
            if (info.date < new Date() || info.date.getDay() === 0 || info.date.getDay() === 6) {
                info.el.style.backgroundColor = '#f9fafb';
                info.el.style.cursor = 'not-allowed';
            }
        }
    });
    
    calendar.render();    async function retryFetch(url, options, maxRetries = 3) {
        for (let i = 0; i < maxRetries; i++) {
            try {
                const response = await fetch(url, options);
                
                // Log the response for debugging
                console.log('Response status:', response.status);
                const text = await response.text();
                console.log('Response text:', text);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Failed to parse JSON:', text);
                    throw new Error('Invalid JSON response');
                }
            } catch (error) {
                console.error(`Attempt ${i + 1} failed:`, error);
                if (i === maxRetries - 1) throw error;
                await new Promise(resolve => setTimeout(resolve, 1000 * (i + 1)));
            }
        }
    }    function fetchTimeSlots(date) {
        const formattedDate = date.toISOString().split('T')[0];
        // Use the full URL to avoid any routing issues
        const url = `${window.location.origin}/api/dentists/${dentistId}/availability?date=${formattedDate}`;
        console.log('Fetching time slots for dentist:', dentistId, 'date:', formattedDate, 'URL:', url);
        
        // Show loading state
        const timeSlotsContainer = document.getElementById('timeSlotsContainer');
        const timeSlotsDiv = document.getElementById('timeSlots');
        const selectedDateDiv = document.getElementById('selectedDate');
        const appointmentForm = document.getElementById('appointmentForm');
        
        timeSlotsContainer.classList.remove('hidden');
        appointmentForm.classList.add('hidden');
        timeSlotsDiv.innerHTML = '<div class="text-center py-4"><div class="animate-spin inline-block w-6 h-6 border-2 border-blue-600 border-t-transparent rounded-full"></div></div>';
        selectedDateDiv.textContent = date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

        // Get CSRF token from meta tag
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        retryFetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token
            },
            credentials: 'include'
        })
        .then(data => {
            console.log('Received data:', data);
            
            if (!data || !Array.isArray(data.slots)) {
                throw new Error('Invalid response format');
            }

            if (data.slots.length === 0) {
                timeSlotsDiv.innerHTML = `
                    <div class="text-center py-4 text-gray-500">
                        No available time slots for this date
                    </div>
                `;
                return;
            }

            // Sort slots by time
            data.slots.sort((a, b) => a.time.localeCompare(b.time));

            // Group slots by period
            const slotsByPeriod = data.slots.reduce((acc, slot) => {
                if (!acc[slot.period]) {
                    acc[slot.period] = [];
                }
                acc[slot.period].push(slot);
                return acc;
            }, {});

            // Clear existing slots
            timeSlotsDiv.innerHTML = '';

            // Render slots by period
            if (slotsByPeriod.morning && slotsByPeriod.morning.length > 0) {
                const morningDiv = document.createElement('div');
                morningDiv.innerHTML = `
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Morning</h4>
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        ${slotsByPeriod.morning.map(slot => `
                            <div class="time-slot morning" data-time="${slot.time}" data-date="${data.date}">
                                ${slot.time}
                                <span class="time-slot-badge">AM</span>
                            </div>
                        `).join('')}
                    </div>
                `;
                timeSlotsDiv.appendChild(morningDiv);
            }

            if (slotsByPeriod.afternoon && slotsByPeriod.afternoon.length > 0) {
                const afternoonDiv = document.createElement('div');
                afternoonDiv.innerHTML = `
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Afternoon</h4>
                    <div class="grid grid-cols-2 gap-2">
                        ${slotsByPeriod.afternoon.map(slot => `
                            <div class="time-slot afternoon" data-time="${slot.time}" data-date="${data.date}">
                                ${slot.time}
                                <span class="time-slot-badge">PM</span>
                            </div>
                        `).join('')}
                    </div>
                `;
                timeSlotsDiv.appendChild(afternoonDiv);
            }

            // Add click event listeners to time slots
            const timeSlots = timeSlotsDiv.querySelectorAll('.time-slot');
            timeSlots.forEach(slot => {
                slot.addEventListener('click', function() {
                    timeSlots.forEach(s => s.classList.remove('selected'));
                    this.classList.add('selected');
                    
                    const date = this.dataset.date;
                    const time = this.dataset.time;
                    const selectedDateTime = `${date} ${time}`;
                    
                    // Update form
                    document.getElementById('selectedDateTime').value = selectedDateTime;
                    document.getElementById('selectedTimeDisplay').textContent = 
                        `${date} at ${time}`;
                    document.getElementById('appointmentForm').classList.remove('hidden');
                });
            });
        })
        .catch(error => {
            console.error('Error fetching time slots:', error);
            timeSlotsDiv.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="text-sm text-red-800">
                        Unable to load available time slots. Please try again later.
                    </div>
                </div>
            `;
        });
    }

    // Add success message handler for form submission
    const appointmentForm = document.getElementById('appointmentForm');
    appointmentForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error('Failed to book appointment');
            }

            const result = await response.json();
            
            // Show success message
            timeSlotsDiv.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="text-sm text-green-800">
                        Your appointment has been booked successfully!
                    </div>
                </div>
            `;
            appointmentForm.classList.add('hidden');

            // Refresh the calendar after a short delay
            setTimeout(() => {
                calendar.refetchEvents();
            }, 2000);

        } catch (error) {
            console.error('Error booking appointment:', error);
            alert('Failed to book the appointment. Please try again.');
        }
    });
});
</script>
@endpush
@endsection