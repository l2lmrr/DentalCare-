<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <!-- Background overlay with click-to-close -->
  <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" onclick="closeModal('newRecordModal')"></div>

  <!-- Modal container -->
  <div class="flex min-h-screen items-center justify-center p-4 text-center">
    <div class="relative transform overflow-hidden rounded-xl bg-white shadow-2xl transition-all sm:w-full sm:max-w-md">
      <!-- Header -->
      <div class="bg-white px-6 pt-6 pb-2">
        <div class="flex items-center justify-between">
          <h3 class="text-xl font-semibold leading-6 text-gray-900">
            <span class="text-blue-600">New Medical Record</span>
          </h3>
          <button type="button" onclick="closeModal('newRecordModal')" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
            <span class="sr-only">Close</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>        </div>
        <p class="mt-1 text-sm text-gray-500">For patient: <span id="selectedPatientName"></span></p>
      </div>      <!-- Form -->      <form id="createMedicalRecordForm" onsubmit="handleSubmit(event)" class="px-6 py-4">
        @csrf
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <input type="hidden" name="patient_id" id="form_patient_id" value="{{ $patient->id }}">
        
        <!-- Diagnostic Field -->
        <div class="mb-5">
          <label for="diagnostic" class="block text-sm font-medium text-gray-700 mb-1">Diagnosis</label>
          <div class="relative rounded-md shadow-sm">
            <textarea name="diagnostic" id="diagnostic" rows="3"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition duration-150 ease-in-out"
              placeholder="Enter diagnosis details..." required></textarea>
          </div>
          <div id="diagnostic-error" class="error-message text-red-500 text-xs mt-1 hidden"></div>
        </div>

        <!-- Treatment Field -->
        <div class="mb-5">
          <label for="traitement" class="block text-sm font-medium text-gray-700 mb-1">Treatment</label>
          <div class="relative rounded-md shadow-sm">
            <textarea name="traitement" id="traitement" rows="3"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition duration-150 ease-in-out"
              placeholder="Describe the treatment..." required></textarea>
          </div>
          <div id="traitement-error" class="error-message text-red-500 text-xs mt-1 hidden"></div>
        </div>

        <!-- Prescription Field -->
        <div class="mb-6">
          <label for="prescription" class="block text-sm font-medium text-gray-700 mb-1">Prescription</label>
          <div class="relative rounded-md shadow-sm">
            <textarea name="prescription" id="prescription" rows="3"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition duration-150 ease-in-out"
              placeholder="List prescribed medications..." required></textarea>
          </div>
          <div id="prescription-error" class="error-message text-red-500 text-xs mt-1 hidden"></div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3">
          <button type="button" onclick="closeModal()"
            class="mt-3 inline-flex justify-center rounded-md bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors duration-200 sm:mt-0 sm:w-auto">
            Cancel
          </button>
          <button type="submit"
            class="inline-flex justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors duration-200">
            Save Record
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function closeModal() {
  document.querySelector('[aria-modal="true"]').remove();
}

function handleSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    fetch('{{ route('medical-records.store') }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        alert('Record created successfully!');
        document.getElementById('newRecordModal').classList.add('hidden');
        window.location.reload(); // This will refresh the page
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the record');
    });
});
});

// Optional: Toast notification function
function showToast(message, type = 'success') {
  const toast = document.createElement('div');
  toast.className = `fixed top-4 right-4 px-4 py-2 rounded-md shadow-lg text-white ${
    type === 'success' ? 'bg-green-500' : 'bg-red-500'
  }`;
  toast.textContent = message;
  document.body.appendChild(toast);
  
  setTimeout(() => {
    toast.remove();
  }, 3000);
}
</script>