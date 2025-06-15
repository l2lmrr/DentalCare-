<!-- Medical Record Modal -->
<div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <div class="absolute top-0 right-0 hidden pt-4 pr-4 sm:block">
                    <button type="button" onclick="closeModal()" class="rounded-md bg-white text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4">Medical Record</h3>
                        
                        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                            <dl class="sm:divide-y sm:divide-gray-200">
                                <!-- Patient Info -->
                                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                                    <dt class="text-sm font-medium text-gray-500">Patient Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                        {{ $appointment->patient->name }}
                                    </dd>
                                </div>

                                <!-- Appointment Date -->
                                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                                    <dt class="text-sm font-medium text-gray-500">Appointment Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                        {{ $appointment->date_heure->format('M d, Y h:i A') }}
                                    </dd>
                                </div>

                                @if($medicalRecord)
                                    <!-- Diagnosis -->
                                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                                        <dt class="text-sm font-medium text-gray-500">Diagnosis</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                            {{ $medicalRecord->diagnostic }}
                                        </dd>
                                    </div>

                                    <!-- Treatment -->
                                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                                        <dt class="text-sm font-medium text-gray-500">Treatment</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                            {{ $medicalRecord->traitement }}
                                        </dd>
                                    </div>

                                    <!-- Prescription -->
                                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                                        <dt class="text-sm font-medium text-gray-500">Prescription</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                            {{ $medicalRecord->prescription ?? 'No prescription given' }}
                                        </dd>
                                    </div>
                                @else
                                    <div class="py-4 text-center text-gray-500">
                                        No medical record has been created for this appointment yet.
                                    </div>
                                @endif
                            </dl>
                        </div>

                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="button" onclick="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
