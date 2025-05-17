<template>
    <div class="login-form">
        <h2 class="login-title">ĐỔI MẬT KHẨU</h2>
        
        <form @submit.prevent="handleSubmit">
            <div class="form-group">
                <label for="password">Mật khẩu mới<span>*</span></label>
                <div class="password-input">
                    <input 
                        :type="showPassword ? 'text' : 'password'" 
                        id="password" 
                        v-model="form.password" 
                        @input="validateForm"
                        placeholder="Nhập mật khẩu mới"
                        :class="{ 'error': errors.password }"
                        required
                    >
                    <button 
                        type="button" 
                        class="password-toggle" 
                        @click="togglePassword"
                    >
                        <svg v-if="!showPassword" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                        </svg>
                        <svg v-else width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
                            <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/>
                            <path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"/>
                        </svg>
                    </button>
                </div>
                <span class="error-message" v-if="errors.password">{{ errors.password }}</span>
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Nhập lại mật khẩu<span>*</span></label>
                <div class="password-input">
                    <input 
                        :type="showPasswordConfirm ? 'text' : 'password'" 
                        id="password_confirmation" 
                        v-model="form.password_confirmation" 
                        @input="validateForm"
                        placeholder="Nhập lại mật khẩu mới"
                        :class="{ 'error': errors.password_confirmation }"
                        required
                    >
                    <button 
                        type="button" 
                        class="password-toggle" 
                        @click="togglePasswordConfirm"
                    >
                        <svg v-if="!showPasswordConfirm" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                        </svg>
                        <svg v-else width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
                            <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/>
                            <path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"/>
                        </svg>
                    </button>
                </div>
                <span class="error-message" v-if="errors.password_confirmation">{{ errors.password_confirmation }}</span>
            </div>
            
            <button 
                type="submit" 
                class="submit-btn" 
                :disabled="hasErrors || loading"
                :class="{ 'disabled': hasErrors || loading }"
            >
                {{ loading ? 'ĐANG XỬ LÝ...' : 'XÁC NHẬN' }}
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { useRouter } from 'vue-router';
import { showToast } from '../../utils/toast';
import { useAuthStore } from '../../store/auth';
import { changeDefaultPassword } from '../../api/auth';
import { getFromLocalStorage } from '../../utils/localStorage';

const form = reactive({
    password: '',
    password_confirmation: ''
});

const errors = reactive({
    password: '',
    password_confirmation: ''
});

const showPassword = ref(false);
const showPasswordConfirm = ref(false);
const loading = ref(false);

const emit = defineEmits(['change-password-success', 'change-password-error']);

// Password validation computed properties
const hasMinLength = computed(() => form.password.length >= 8);
const hasUpperCase = computed(() => /[A-Z]/.test(form.password));
const hasNumber = computed(() => /[0-9]/.test(form.password));
const hasSpecialChar = computed(() => /[!@#$%^&*(),.?":{}|<>]/.test(form.password));

const hasErrors = computed(() => {
    return Boolean(
        errors.password || 
        errors.password_confirmation || 
        !form.password || 
        !form.password_confirmation ||
        !hasMinLength.value ||
        !hasUpperCase.value ||
        !hasNumber.value ||
        !hasSpecialChar.value
    );
});

const validateForm = () => {
    // Validate password
    if (!form.password) {
        errors.password = 'Mật khẩu là bắt buộc';
    } else if (!hasMinLength.value) {
        errors.password = 'Mật khẩu phải có ít nhất 8 ký tự';
    } else if (!hasUpperCase.value) {
        errors.password = 'Mật khẩu phải có ít nhất 1 chữ in hoa';
    } else if (!hasNumber.value) {
        errors.password = 'Mật khẩu phải có ít nhất 1 số';
    } else if (!hasSpecialChar.value) {
        errors.password = 'Mật khẩu phải có ít nhất 1 ký tự đặc biệt';
    } else {
        errors.password = '';
    }

    // Validate password confirmation
    if (!form.password_confirmation) {
        errors.password_confirmation = 'Vui lòng nhập lại mật khẩu';
    } else if (form.password !== form.password_confirmation) {
        errors.password_confirmation = 'Mật khẩu nhập lại không khớp';
    } else {
        errors.password_confirmation = '';
    }
};

const router = useRouter();
const authStore = useAuthStore();

const handleSubmit = async () => {
    validateForm();
    
    if (hasErrors.value) {
        return;
    }

    try {
        loading.value = true;
        const response = await changeDefaultPassword({
            vn_id: getFromLocalStorage('vn_id'),
            temp_token: getFromLocalStorage('temp_token_first_login'),
            password: form.password,
            password_confirmation: form.password_confirmation
        });

        if (response.data.meta.code === 200) {
            // Sử dụng action mới từ store để lưu thông tin
            await authStore.handleAuthSuccess(response);
            showToast('success', 'Đổi mật khẩu thành công');
            router.push('/');
        }
    } catch (error) {
        console.error('Change password error:', error);
        showToast('error', error.response?.data?.meta?.message || 'Đổi mật khẩu thất bại');
    } finally {
        loading.value = false;
    }
};

const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

const togglePasswordConfirm = () => {
    showPasswordConfirm.value = !showPasswordConfirm.value;
};
</script>

<style scoped>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Arial, sans-serif;
    }
    .login-form {
      background-color: white;
      border-radius: 8px;
      padding: 30px;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    }
    
    .login-title {
      font-size: 20px;
      font-weight: 600;
      color: #333;
      text-align: center;
      margin-bottom: 25px;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-group label {
      display: block;
      font-size: 14px;
      color: #333;
      margin-bottom: 8px;
      font-weight: 500;
      text-align: left;
      padding-left: 0;
    }
    
    .form-group label span {
      color: #e94e87;
    }
    
    .form-group input {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #e0e0e0;
      border-radius: 4px;
      font-size: 14px;
      outline: none;
      transition: border 0.3s;
    }
    
    .form-group input:focus {
      border-color: #4b2a9e;
    }
    
    .password-input {
      position: relative;
    }
    
    .password-toggle {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #999;
      cursor: pointer;
      outline: none;
    }
    
    .submit-btn {
      width: 100%;
      padding: 12px;
      background-color: #169bd5;
      color: white;
      border: none;
      border-radius: 4px;
      font-size: 16px;
      font-weight: 500;
      cursor: pointer;
      transition: background-color 0.3s;
      margin-top: 10px;
    }
    
    .submit-btn:hover {
      background-color: #1084b8;
    }
    
    @media (max-width: 500px) {
      .login-form {
        padding: 20px;
      }
    }

    .error-message {
        display: block;
        color: #dc3545;
        font-size: 12px;
        margin-top: 4px;
    }

    .form-group input.error {
        border-color: #dc3545;
    }

    .submit-btn.disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .submit-btn:disabled {
        background-color: #ccc;
    }

    /* Styles mới cho password requirements */
    .password-requirements {
        margin-top: 10px;
        font-size: 12px;
        color: #666;
    }

    .password-requirements ul {
        list-style: none;
        padding-left: 15px;
        margin-top: 5px;
    }

    .password-requirements li {
        margin: 3px 0;
        position: relative;
    }

    .password-requirements li:before {
        content: '×';
        color: #dc3545;
        position: absolute;
        left: -15px;
    }

    .password-requirements li.valid:before {
        content: '✓';
        color: #28a745;
    }
</style>