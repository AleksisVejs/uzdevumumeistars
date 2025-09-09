import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.baseURL = 'http://127.0.0.1:8000/api';

// Global 401 handler: clear token and fail fast
window.axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error && error.response && error.response.status === 401) {
            try {
                localStorage.removeItem('token');
                // Remove stale Authorization header
                if (window.axios && window.axios.defaults && window.axios.defaults.headers && window.axios.defaults.headers.common) {
                    delete window.axios.defaults.headers.common['Authorization'];
                }
            } catch (_) {}
        }
        return Promise.reject(error);
    }
);
