import './bootstrap';
import { initFetch, showError, handleFetchError } from './helpers';

// Make helper functions available globally
window.initFetch = initFetch;
window.showError = showError;
window.handleFetchError = handleFetchError;