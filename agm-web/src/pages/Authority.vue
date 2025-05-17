<template>
  <div class="authority-page min-h-screen p-4 md:p-8">
    <h1 class="text-2xl md:text-3xl font-bold text-center text-[#1a3694] mb-8">KHAI BÁO ỦY QUYỀN</h1>
    
    <div class="mx-auto bg-white rounded-[6px] border border-[#E4E4E4] shadow-lg overflow-hidden py-8 px-8">
      <div class="px-4 md:px-8">
        <!-- Bên ủy quyền section -->
        <div class="mb-6">
          <h2 class="text-[#1a3694] font-medium mb-4 flex items-center">
            <span class="mr-2">1.</span>
            <span>Bên ủy quyền</span>
          </h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-gray-700 text-sm mb-2">Họ và tên</label>
              <div class="bg-gray-100 text-gray-800 rounded px-4 py-3">
                {{ authorityInfo.delegator.name }}
              </div>
            </div>
            <div>
              <label class="block text-gray-700 text-sm mb-2">Mã cổ đông</label>
              <div class="bg-gray-100 text-gray-800 rounded px-4 py-3">
                {{ authorityInfo.delegator.code }}
              </div>
            </div>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-gray-700 text-sm mb-2">Số sổ hữu chứng khoán</label>
              <div class="bg-gray-100 text-gray-800 rounded px-4 py-3">
                {{ authorityInfo.delegator.securityNumber }}
              </div>
            </div>
            <div>
              <label class="block text-gray-700 text-sm mb-2">Tổng số cổ phần sở hữu</label>
              <div class="bg-gray-100 text-gray-800 rounded px-4 py-3">
                {{ authorityInfo.delegator.totalShares }}
              </div>
            </div>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-700 text-sm mb-2">Tổng số cổ phần đã ủy quyền</label>
              <div class="bg-gray-100 text-gray-800 rounded px-4 py-3">
                {{ authorityInfo.delegator.delegatedShares }}
              </div>
            </div>
            <div>
              <label class="block text-gray-700 text-sm mb-2">Số cổ phần được phép ủy quyền</label>
              <div class="bg-gray-100 text-gray-800 rounded px-4 py-3">
                {{ authorityInfo.delegator.availableShares }}
              </div>
            </div>
          </div>
        </div>
        
        <!-- Bên nhận ủy quyền section -->
        <div class="mb-6">
          <h2 class="text-[#1a3694] font-medium mb-4 flex items-center">
            <span class="mr-2">2.</span>
            <span>Bên nhận ủy quyền</span>
          </h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-gray-700 text-sm mb-2">Họ và tên <span class="text-red-500">*</span></label>
              <input 
                type="text" 
                v-model="delegateForm.name"
                placeholder="Nhập họ và tên"
                class="w-full border border-gray-300 text-gray-800 rounded px-4 py-3 focus:outline-none focus:ring-1 focus:ring-[#007BFF]"
              />
            </div>
            <div>
              <label class="block text-gray-700 text-sm mb-2">Số CCCD/Hộ chiếu <span class="text-red-500">*</span></label>
              <input 
                type="text" 
                v-model="delegateForm.idNumber"
                placeholder="Nhập"
                class="w-full border border-gray-300 text-gray-800 rounded px-4 py-3 focus:outline-none focus:ring-1 focus:ring-[#007BFF]"
              />
            </div>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-gray-700 text-sm mb-2">Số điện thoại <span class="text-red-500">*</span></label>
              <input 
                type="text" 
                v-model="delegateForm.phone"
                placeholder="Nhập số điện thoại"
                class="w-full border border-gray-300 text-gray-800 rounded px-4 py-3 focus:outline-none focus:ring-1 focus:ring-[#007BFF]"
              />
            </div>
            <div>
              <label class="block text-gray-700 text-sm mb-2">Email <span class="text-red-500">*</span></label>
              <input 
                type="email" 
                v-model="delegateForm.email"
                placeholder="Nhập địa chỉ email"
                class="w-full border border-gray-300 text-gray-800 rounded px-4 py-3 focus:outline-none focus:ring-1 focus:ring-[#007BFF]"
              />
            </div>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-gray-700 text-sm mb-2">Số sở hữu chứng khoán (Nếu có)</label>
              <input 
                type="text" 
                v-model="delegateForm.securityNumber"
                placeholder="Nhập số sở hữu chứng khoán"
                class="w-full border border-gray-300 text-gray-800 rounded px-4 py-3 focus:outline-none focus:ring-1 focus:ring-[#007BFF]"
              />
            </div>
            <div>
              <label class="block text-gray-700 text-sm mb-2">Mã cổ đông (Nếu có)</label>
              <input 
                type="text" 
                v-model="delegateForm.shareholderCode"
                placeholder="Nhập mã cổ đông"
                class="w-full border border-gray-300 text-gray-800 rounded px-4 py-3 focus:outline-none focus:ring-1 focus:ring-[#007BFF]"
              />
            </div>
          </div>
          
          <div class="mb-4">
            <label class="block text-gray-700 text-sm mb-2">Số cổ phần được ủy quyền <span class="text-red-500">*</span></label>
            <input 
              type="number" 
              v-model="delegateForm.sharesAmount"
              placeholder="Nhập số cổ phần được ủy quyền"
              class="w-full border border-gray-300 text-gray-800 rounded px-4 py-3 focus:outline-none focus:ring-1 focus:ring-[#007BFF]"
            />
          </div>
        </div>
        
        <!-- Nội dung ủy quyền section -->
        <div class="mb-6">
          <h2 class="text-[#1a3694] font-medium mb-4 flex items-center">
            <span class="mr-2">3.</span>
            <span>Nội dung ủy quyền</span>
          </h2>
          
          <div class="bg-gray-100 rounded p-4 mb-4">
            <p class="text-gray-800 text-sm mb-3">
              - Bên nhận ủy quyền được đại diện cho Bên ủy quyền tham dự Đại hội đồng cổ đông thường niên năm 2025 của Công ty Cổ phần Công nghệ - Viễn thông Elcom tổ chức vào ngày 24/04/2025 và thực hiện mọi quyền lợi và nghĩa vụ tại Đại hội trong phạm vi số cổ phần có quyền biểu quyết được ủy quyền.
            </p>
            <p class="text-gray-800 text-sm">
              - Giấy ủy quyền có hiệu lực kể từ ngày ký đến khi kết thúc Đại hội.
            </p>
          </div>
          
          <a href="#" class="text-[#007BFF] text-sm hover:underline" @click.prevent="showGuide">Hướng dẫn cổ đông ủy quyền</a>
        </div>
        
        <div class="flex justify-end">
          <button 
            class="submit-button hover:bg-[#1084b8] text-white font-medium py-2 px-6 rounded transition duration-200"
            @click="submitAuthorityForm"
            :disabled="isSubmitting"
          >
            <span v-if="isSubmitting">ĐANG GỬI...</span>
            <span v-else>GỬI PHIẾU</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive, onMounted } from 'vue';
import { getShareholderInfo, submitAuthority, getAuthorityGuide } from '@/api/authority';
import { useAuthStore } from '@/store/auth';
import { showToast } from '@/utils/toast';

export default {
  setup() {
    const authStore = useAuthStore();
    const isSubmitting = ref(false);
    const authorityGuide = ref('');
    
    // Initial data for delegator (current user's info)
    const authorityInfo = reactive({
      delegator: {
        name: '',
        code: '',
        securityNumber: '',
        totalShares: '0',
        delegatedShares: '0',
        availableShares: '0'
      }
    });
    
    // Form for delegate information
    const delegateForm = reactive({
      name: '',
      idNumber: '',
      phone: '',
      email: '',
      securityNumber: '',
      shareholderCode: '',
      sharesAmount: ''
    });
    
    const loadUserInfo = async () => {
      try {
        const response = await getShareholderInfo();
        
        if (response?.data?.data) {
          const data = response.data.data;
          
          // Update delegator information
          authorityInfo.delegator = {
            name: data.name || '',
            code: data.code || '',
            securityNumber: data.securityNumber || '',
            totalShares: data.totalShares || '0',
            delegatedShares: data.delegatedShares || '0',
            availableShares: data.availableShares || '0'
          };
        }
      } catch (error) {
        console.error('Error loading shareholder information:', error);
        // For demo purposes, set default values
        authorityInfo.delegator = {
          name: 'Nguyễn Văn A',
          code: 'ELC39759',
          securityNumber: '036205014835',
          totalShares: '1694564',
          delegatedShares: '0',
          availableShares: '1694564'
        };
      }
    };
    
    const loadAuthorityGuide = async () => {
      try {
        const response = await getAuthorityGuide();
        if (response?.data?.data) {
          authorityGuide.value = response.data.data.content;
        }
      } catch (error) {
        console.error('Error loading authority guide:', error);
      }
    };
    
    const submitAuthorityForm = async () => {
      try {
        // Basic validation
        if (!delegateForm.name || !delegateForm.idNumber || 
            !delegateForm.phone || !delegateForm.email || 
            !delegateForm.sharesAmount) {
          showToast('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
          return;
        }
        
        // Check if delegated shares amount is valid
        if (parseInt(delegateForm.sharesAmount) > parseInt(authorityInfo.delegator.availableShares)) {
          showToast('error', 'Số cổ phần ủy quyền không được vượt quá số cổ phần được phép ủy quyền.');
          return;
        }
        
        isSubmitting.value = true;
        
        // Submit authority information
        const response = await submitAuthority({
          delegator: {
            shareholderCode: authorityInfo.delegator.code
          },
          delegate: {
            name: delegateForm.name,
            idNumber: delegateForm.idNumber,
            phone: delegateForm.phone,
            email: delegateForm.email,
            securityNumber: delegateForm.securityNumber || null,
            shareholderCode: delegateForm.shareholderCode || null,
            sharesAmount: parseInt(delegateForm.sharesAmount)
          }
        });
        
        if (response?.data?.success) {
          showToast('success', 'Gửi thông tin ủy quyền thành công!');
          // Reload user info to update available shares
          await loadUserInfo();
          // Reset form
          resetForm();
        } else {
          showToast('error', 'Có lỗi xảy ra khi gửi thông tin ủy quyền. Vui lòng thử lại sau.');
        }
      } catch (error) {
        console.error('Error submitting authority delegation:', error);
        showToast('error', 'Có lỗi xảy ra khi gửi thông tin ủy quyền. Vui lòng thử lại sau.');
      } finally {
        isSubmitting.value = false;
      }
    };
    
    const resetForm = () => {
      // Reset the form fields
      Object.keys(delegateForm).forEach(key => {
        delegateForm[key] = '';
      });
    };
    
    const showGuide = () => {
      if (authorityGuide.value) {
        showToast('info', authorityGuide.value);
      } else {
        showToast('info', 'Hướng dẫn cổ đông ủy quyền đang được cập nhật. Vui lòng thử lại sau.');
      }
    };
    
    onMounted(() => {
      // Load user information and authority guide when component is mounted
      loadUserInfo();
      loadAuthorityGuide();
    });
    
    return {
      authorityInfo,
      delegateForm,
      isSubmitting,
      submitAuthorityForm,
      showGuide
    };
  }
};
</script>

<style lang="scss" scoped>

input::placeholder {
  color: rgba(107, 114, 128, 0.5);
}
.submit-button {
    background: linear-gradient(321.18deg, #1C9AD6 7.73%, #006FFF 94.96%);
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .authority-page {
    padding: 1rem;
  }
}
</style>
  