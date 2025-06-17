<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <div class="flex items-center justify-center min-h-screen p-4 text-center">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
    
    <!-- Modal panel -->
    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
      <div class="bg-white px-5 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-800">
            Records for <span class="text-indigo-600">{{ $patient->name }}</span>
          </h3>
          <button type="button" onclick="closeModal()" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
            <span class="sr-only">Close</span>
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <div class="px-5 py-4">
        @if($records->isEmpty())
          <div class="text-center py-6 bg-gray-50 rounded-lg">
            <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-700">No records found</h3>
          </div>
        @else
          <div class="overflow-hidden border border-gray-200 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dentist</th>
                  <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosis</th>
                  <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Treatment</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                @foreach($records as $record)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                  <td class="px-4 py-3 whitespace-nowrap text-xs font-medium text-gray-900">
                    {{ $record->created_at->format('M d, Y') }}
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-6 w-6 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-xs font-semibold mr-2">
                        {{ substr($record->dentist->name, 0, 1) }}
                      </div>
                      <span>Dr. {{ $record->dentist->name }}</span>
                    </div>
                  </td>
                  <td class="px-4 py-3 text-xs text-gray-600">
                    <div class="line-clamp-1">{{ $record->diagnostic }}</div>
                  </td>
                  <td class="px-4 py-3 text-xs text-gray-600">
                    <div class="line-clamp-1">{{ $record->traitement }}</div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="mt-4 flex items-center justify-between text-xs">
            <div class="text-gray-500">
              Showing <span class="font-medium">{{ $records->firstItem() }}</span> to <span class="font-medium">{{ $records->lastItem() }}</span> of <span class="font-medium">{{ $records->total() }}</span> records
            </div>
            <div class="flex space-x-1">
              @if($records->onFirstPage())
                <span class="px-3 py-1 rounded border border-gray-200 text-gray-400">Previous</span>
              @else
                <a href="{{ $records->previousPageUrl() }}" class="px-3 py-1 rounded border border-gray-200 hover:bg-gray-50">Previous</a>
              @endif
              
              @foreach(range(1, $records->lastPage()) as $page)
                @if($page == $records->currentPage())
                  <span class="px-3 py-1 rounded border border-indigo-200 bg-indigo-50 text-indigo-600">{{ $page }}</span>
                @else
                  <a href="{{ $records->url($page) }}" class="px-3 py-1 rounded border border-gray-200 hover:bg-gray-50">{{ $page }}</a>
                @endif
              @endforeach
              
              @if($records->hasMorePages())
                <a href="{{ $records->nextPageUrl() }}" class="px-3 py-1 rounded border border-gray-200 hover:bg-gray-50">Next</a>
              @else
                <span class="px-3 py-1 rounded border border-gray-200 text-gray-400">Next</span>
              @endif
            </div>
          </div>
        @endif
      </div>      <div class="bg-gray-50 px-5 py-3 flex justify-end border-t border-gray-200">
        <button type="button" onclick="document.getElementById('patientRecordsModal').classList.add('hidden')" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          Close
        </button>
      </div>
    </div>
  </div>
</div>

<script>
function closeModal() {
  const modal = document.getElementById('patientRecordsModal');
  if (modal) {
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  }
}
</script>