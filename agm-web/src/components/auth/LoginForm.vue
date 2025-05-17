<template>
    <div class="login-form">
        <h2 class="login-title">ĐĂNG NHẬP</h2>
        
        <form @submit.prevent="handleSubmit">
            <div class="form-group">
                <label for="vn_id">CMTND/CCCD<span>*</span></label>
                <input 
                    type="text" 
                    id="vn_id" 
                    v-model="form.vn_id" 
                    @input="validateForm"
                    placeholder="Nhập CMTND/CCCD"
                    :class="{ 'error': errors.vn_id }"
                    required
                >
                <span class="error-message" v-if="errors.vn_id">{{ errors.vn_id }}</span>
            </div>
            
            <div class="form-group">
                <label for="password">Mật khẩu<span>*</span></label>
                <div class="password-input">
                    <input 
                        :type="showPassword ? 'text' : 'password'" 
                        id="password" 
                        v-model="form.password" 
                        @input="validateForm"
                        placeholder="Nhập mật khẩu"
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
            
            <button 
                type="submit" 
                class="submit-btn" 
                :disabled="hasErrors || loading"
                :class="{ 'disabled': hasErrors || loading }"
            >
                {{ loading ? 'ĐANG XỬ LÝ...' : 'ĐĂNG NHẬP' }}
            </button>
        </form>
        
        <div class="help-text">
            <a href="#" @click="openUserGuide">Hướng dẫn cổ đông sử dụng phần mềm</a>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { showToast } from '../../utils/toast';
import { saveToLocalStorage } from '../../utils/localStorage';
import { login } from '../../api/auth';

const form = reactive({
    vn_id: '',
    password: ''
});

const errors = reactive({
    vn_id: '',
    password: ''
});

const showPassword = ref(false);
const loading = ref(false);

const emit = defineEmits(['login-success', 'login-error']);

const hasErrors = computed(() => {
    return Boolean(errors.vn_id || errors.password || !form.vn_id || !form.password);
});

const validateForm = () => {
    // Validate VN ID
    if (!form.vn_id) {
        errors.vn_id = 'CMND/CCCD bắt buộc nhập';
    } else if (!/^\d{12}$/.test(form.vn_id)) {
        errors.vn_id = 'CMND/CCCD phải có 12 chữ số';
    } else {
        errors.vn_id = '';
    }

    // Validate password
    if (!form.password) {
        errors.password = 'Mật khẩu bắt buộc nhập';
    } else {
        errors.password = '';
    }
};

const handleSubmit = async () => {
    validateForm();
    
    if (hasErrors.value) {
        return;
    }

    try {
        loading.value = true;
        const response = await login(form);
        
        if (response.data.meta.code === 200) {
            saveToLocalStorage('vn_id', form.vn_id);
            saveToLocalStorage('email', response.data.data.email);
            saveToLocalStorage('temp_token', response.data.data.temp_token);
            showToast('success', response.data.data.message);
            emit('login-success', response);
        } else {
            showToast('error', response.data.meta.message);
            emit('login-error', response);
        }
    } catch (error) {
        console.error(error.message);
        // Hiển thị message lỗi từ response API
        const errorMessage = error.message || 'Đã có lỗi xảy ra';
        showToast('error', errorMessage);
        console.error('Login error:', error);
        emit('login-error', error);
    } finally {
        loading.value = false;
    }
};

const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

const userGuideUrl = import.meta.env.VITE_HDSD_URL;

const openUserGuide = (e) => {
    e.preventDefault();
    if (userGuideUrl && userGuideUrl !== '') {
        window.open(userGuideUrl, '_blank');
    }
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
      text-align: start;
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
    
    .help-text {
      text-align: center;
      margin-top: 15px;
      font-size: 13px;
      color: #169bd5;
    }
    
    .help-text a {
      color: #169bd5;
      text-decoration: none;
    }
    
    .help-text a:hover {
      text-decoration: underline;
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
</style>