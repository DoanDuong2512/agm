<template>
    <AuthLayout>
      <LoginForm 
        v-if="!showOtpInput && !showChangePassword"
        @login-success="handleLoginSuccess"
        @login-error="handleLoginError"
      />
      <OtpForm
        v-if="showOtpInput && !showChangePassword"
        ref="otpInputRef"
        :email="userEmail"
        :otpLength="6"
        :expireTime="expireTime"
        @verify-success="handleOtpSuccess"
        @verify-error="handleOtpError"
        @otp-expired="handleOtpExpired"
      />
      <ChangePasswordFirstLogin
        v-if="showChangePassword"
      />
    </AuthLayout>
</template>

<script setup>
import { ref } from 'vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import LoginForm from '@/components/auth/LoginForm.vue';
import OtpForm from '@/components/auth/OtpForm.vue';
import ChangePasswordFirstLogin from '@/components/auth/ChangePasswordFirstLogin.vue';
import { showToast } from '@/utils/toast';
import { removeFromLocalStorage, saveToLocalStorage } from '@/utils/localStorage';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../store/auth';

const router = useRouter();
const showOtpInput = ref(false);
const showChangePassword = ref(false);
const otpInputRef = ref(null);
const userEmail = ref('');
const expireTime = ref(null);
const handleLoginSuccess = (response) => {
  userEmail.value = response.data.data.email;
  expireTime.value = response.data.data.expire_time; 
  showOtpInput.value = true; 
};

const handleLoginError = (error) => {
  console.error('Login error:', error);
};

const handleOtpExpired = () => {
  // Xóa các dữ liệu trong localStorage
  removeFromLocalStorage('temp_token');
  removeFromLocalStorage('vn_id');
  removeFromLocalStorage('email');
  
  // Reset các state
  showOtpInput.value = false;
  userEmail.value = '';
  expireTime.value = null;
  
  // Hiển thị thông báo
  showToast('warning', 'Mã OTP đã hết hạn. Vui lòng đăng nhập lại');
};

const handleOtpSuccess = async (response) => {
  const { is_active, access_token, customer, temp_token_first_login } = response.data.data;
  
  removeFromLocalStorage('temp_token');
  
  if (is_active === 1) {
    removeFromLocalStorage('vn_id');
    removeFromLocalStorage('email');
    // Cập nhật store nếu cần
    const authStore = useAuthStore();
    authStore.setUserAndToken(access_token, customer);
    
    showToast('success', 'Đăng nhập thành công');
    
    // Điều hướng sau khi đã cập nhật token
    router.push('/');
  } else {
    // Người dùng chưa active - chuyển đến form đổi mật khẩu
    saveToLocalStorage('temp_token_first_login', temp_token_first_login);
    showOtpInput.value = false;
    showChangePassword.value = true;
  }
};

  const handleOtpError = (error) => {
    console.error('OTP verification error:', error);
    showToast('error', error.message || 'Xác thực OTP thất bại');
};
</script>