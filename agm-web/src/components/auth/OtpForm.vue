<template>
  <div class="otp-container">
    <h2 class="otp-title">NHẬP MÃ OTP</h2>
    <p class="otp-subtitle">(Mã OTP được gửi qua email {{ maskedEmail }})</p>
    
    <div class="otp-input-group">
      <input
        v-for="(digit, index) in otpDigits"
        :key="index"
        ref="inputRefs"
        v-model="otpDigits[index]"
        type="text"
        maxlength="1"
        class="otp-input"
        :class="{ 'otp-input-filled': otpDigits[index] }"
        @input="handleInput(index)"
        @keydown="handleKeyDown($event, index)"
        @paste="handlePaste"
        @focus="$event.target.select()"
      />
    </div>
    
    <p class="otp-timer">OTP sẽ hết hạn sau {{ timer }} giây</p>
    
    <button 
      class="otp-verify-btn" 
      :disabled="!isOtpComplete" 
      @click="verifyOtp">
      XÁC NHẬN
    </button>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue';
import { validateOtp } from '../../api/auth';
import { getFromLocalStorage } from '../../utils/localStorage';

const props = defineProps({
  email: {
    type: String,
    required: true
  },
  otpLength: {
    type: Number,
    default: 6
  },
  expireTime: {
    type: Number,
    default: 60 // seconds
  }
});

const emit = defineEmits(['verify-success', 'verify-error', 'otp-expired']);

const otpDigits = ref(Array(props.otpLength).fill(''));
const inputRefs = ref([]);
const timer = ref(props.expireTime);
let timerInterval = null;

const maskedEmail = computed(() => {
  if (!props.email) return '';
  const [username, domain] = props.email.split('@');
  const maskedUsername = username.charAt(0) + '*'.repeat(username.length - 2) + username.charAt(username.length - 1);
  return maskedUsername + '@' + domain;
});

const isOtpComplete = computed(() => {
  return otpDigits.value.every(digit => digit !== '');
});

const handleInput = (index) => {
  if (otpDigits.value[index] && index < props.otpLength - 1) {
    inputRefs.value[index + 1].focus();
  }
};

const handleKeyDown = (event, index) => {
  // Handle backspace
  if (event.key === 'Backspace' && !otpDigits.value[index] && index > 0) {
    otpDigits.value[index - 1] = '';
    inputRefs.value[index - 1].focus();
  }
  
  // Handle enter key when OTP is complete
  if (event.key === 'Enter' && isOtpComplete.value) {
    verifyOtp();
    return;
  }
  
  // Allow only numbers
  if (!/^[0-9]$/.test(event.key) && !['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'].includes(event.key)) {
    event.preventDefault();
  }
};

const handlePaste = (event) => {
  event.preventDefault();
  const pastedData = event.clipboardData.getData('text');
  const digits = pastedData.replace(/\D/g, '').split('').slice(0, props.otpLength);
  
  digits.forEach((digit, index) => {
    if (index < props.otpLength) {
      otpDigits.value[index] = digit;
    }
  });
  
  if (digits.length > 0 && digits.length < props.otpLength) {
    inputRefs.value[digits.length].focus();
  }
};

const verifyOtp = async () => {
  const otpValue = otpDigits.value.join('');
  
  try {
    const vn_id = getFromLocalStorage('vn_id');
    const temp_token = getFromLocalStorage('temp_token');
    
    const response = await validateOtp({
      vn_id,
      temp_token,
      digit_code: otpValue
    });
    
    if (response.data.meta.code === 200) {
      emit('verify-success', response);
    } else {
      emit('verify-error', { message: response.data.meta.message });
    }
  } catch (error) {
    emit('verify-error', error);
  }
};

const startTimer = () => {
  timerInterval = setInterval(() => {
    if (timer.value > 0) {
      timer.value--;
    } else {
      clearInterval(timerInterval);
      emit('otp-expired');
    }
  }, 1000);
};

onMounted(() => {
  if (inputRefs.value.length > 0) {
    inputRefs.value[0].focus();
  }
  startTimer();
});

onBeforeUnmount(() => {
  if (timerInterval) {
    clearInterval(timerInterval);
  }
});

// Expose methods to parent
defineExpose({
  resetOtp: () => {
    otpDigits.value = Array(props.otpLength).fill('');
    timer.value = props.expireTime;
    startTimer();
    if (inputRefs.value.length > 0) {
      inputRefs.value[0].focus();
    }
  }
});
</script>

<style scoped>
.otp-container {
  width: 100%;
  max-width: 450px;
  padding: 2rem;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  text-align: center;
  margin: 0 auto;
}

.otp-title {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
  color: #333;
}

.otp-subtitle {
  font-size: 0.9rem;
  color: #888;
  margin-bottom: 1.5rem;
}

.otp-input-group {
  display: flex;
  gap: 0.5rem;
  justify-content: center;
  margin-bottom: 1.5rem;
}

.otp-input {
  width: 3rem;
  height: 3rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  text-align: center;
  font-size: 1.25rem;
  font-weight: bold;
  color: #333;
  transition: all 0.2s;
  background-color: #fff;
}

.otp-input:focus {
  border-color: #0096d6;
  outline: none;
  box-shadow: 0 0 0 2px rgba(0, 150, 214, 0.2);
}

.otp-input-filled {
  background-color: #f8f9fa;
}

.otp-timer {
  font-size: 0.9rem;
  color: #888;
  margin-bottom: 1.5rem;
}

.otp-verify-btn {
  width: 100%;
  padding: 0.875rem;
  background-color: #0096d6;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.2s;
}

.otp-verify-btn:hover:not(:disabled) {
  background-color: #0086c1;
}

.otp-verify-btn:disabled {
  background-color: #b3e0f2;
  cursor: not-allowed;
}

@media (max-width: 768px) {
  .otp-container {
    padding: 1.5rem;
  }
  
  .otp-input {
    width: 2.5rem;
    height: 2.5rem;
  }
}
</style>