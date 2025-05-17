import apiClient from './base';

export const login = (credentials) => apiClient.post('/pre-login', credentials);

export const getCurrentUserInfo = () => {
    return apiClient.get('/me')
}

export const validateOtp = (data) => {
    return apiClient.post('/validate-otp', {
        vn_id: data.vn_id,
        temp_token: data.temp_token,
        digit_code: data.digit_code
    });
};

export const changeDefaultPassword = (data) => {
    return apiClient.post('/change-default-password', {
        vn_id: data.vn_id,
        temp_token: data.temp_token,
        password: data.password,
        password_confirmation: data.password_confirmation
    });
};