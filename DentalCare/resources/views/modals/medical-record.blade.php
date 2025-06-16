<!-- View/Add Medical Record Modal -->
<div class="relative bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full sm:p-6">
    <div class="sm:flex sm:items-start">
        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                {{ isset($record) ? 'Medical Record Details' : 'Add New Medical Record' }}
            </h3>
            
            <form action="{{ isset($record) ? route('medical-records.update', $record->id) : route('medical-records.store') }}" 
                  method="POST" 
                  id="medicalRecordForm"
                  class="mt-4">
                @csrf
                @if(isset($record))
                    @method('PUT')
                @endif
                
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                
                <div class="space-y-4">
                    <div>
                        <label for="diagnostic" class="block text-sm font-medium text-gray-700">Diagnosis</label>
                        <textarea id="diagnostic" 
                                name="diagnostic" 
                                rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>{{ $record->diagnostic ?? old('diagnostic') }}</textarea>
                    </div>
                    
                    <div>
                        <label for="traitement" class="block text-sm font-medium text-gray-700">Treatment</label>
                        <textarea id="traitement" 
                                name="traitement" 
                                rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>{{ $record->traitement ?? old('traitement') }}</textarea>
                    </div>
                    
                    <div>
                        <label for="prescription" class="block text-sm font-medium text-gray-700">Prescription</label>
                        <textarea id="prescription" 
                                name="prescription" 
                                rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $record->prescription ?? old('prescription') }}</textarea>
                    </div>
                </div>
                
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ isset($record) ? 'Update Record' : 'Save Record' }}
                    </button>
                    <button type="button" 
                            onclick="closeModal('newRecordModal')" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
