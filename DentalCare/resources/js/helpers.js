// Generic error handler
export function handleFetchError(error, message) {
    console.error('Error:', error);
    showError(message);
    if (error.response && error.response.status === 403) {
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    }
}

// Show error message
export function showError(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50';
    errorDiv.innerHTML = `
        <span class="block sm:inline">${message}</span>
    `;
    document.body.appendChild(errorDiv);
    setTimeout(() => {
        errorDiv.remove();
    }, 3000);
}

// Check if response is ok
export function checkResponse(response) {
    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }
    return response;
}

// Initialize fetch requests with proper headers and error handling
export function initFetch(url, options = {}) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    return fetch(url, {
        ...options,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            ...(options.headers || {})
        },
        credentials: 'same-origin'
    })
    .then(checkResponse)
    .catch(error => {
        handleFetchError(error, 'An error occurred while processing your request.');
        throw error;
    });
}
