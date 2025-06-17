<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>
    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Medical Record for {{ $medicalRecord->patient->name }}</h3>
            <div class="space-y-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-700">Diagnostic:</h4>
                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $medicalRecord->diagnostic }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-700">Treatment:</h4>
                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $medicalRecord->traitement }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-700">Prescription:</h4>
                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $medicalRecord->prescription }}</p>
                </div>
                <div class="text-sm text-gray-500">
                    Last updated: {{ $medicalRecord->updated_at->format('M d, Y h:i A') }}
                </div>
            </div>
            <div class="mt-5 sm:mt-6">
                <button type="button" onclick="closeModal()" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>