import axios from 'axios';
import { getFromLocalStorage, removeFromLocalStorage } from '../utils/localStorage';

const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api',
  timeout: parseInt(import.meta.env.VITE_API_BASE_URL, 10) || 10000, // Thời gian chờ request (30 giây)
  headers: {
    'Content-Type': 'application/json',
  },
});

// Thêm interceptor để xử lý token hoặc lỗi
apiClient.interceptors.request.use((config) => {
  const token = getFromLocalStorage('access_token'); // Lấy token từ localStorage
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

apiClient.interceptors.response.use(
    (response) => {
        return response;
      },
      (error) => {
        let errorMessage = 'An unknown error occurred';
        if (error.response) {
          const { data } = error.response;
    
          if (error.response.status === 401) {
            removeFromLocalStorage('access_token');
            removeFromLocalStorage('user');
            console.error('Unauthorized! Redirecting to login...');
            // Điều hướng người dùng tới trang đăng nhập
            window.location.href = '/login';
          } else if (error.response.status === 404) {
            errorMessage = 'Resource not found';
          } else if (data && data.meta && data.meta.message) {
            // Lấy message từ meta trong response
            errorMessage = data.meta.message;
          } else if (!data) {
            // Trường hợp không có `data` trong response
            errorMessage = error.response.statusText || 'Unknown error occurred';
          }
        }
        return Promise.reject({ message: errorMessage, error: error});
      }
);

export default apiClient;
