<template>
  <div class="grid md:grid-cols-3 gap-6 p-6">
    <!-- Phần upload ảnh đại diện -->
    <div class="avatar-container">
      <div class="relative w-full h-full rounded-md overflow-hidden bg-[#0A2476] flex items-center justify-center border border-[#3d5fb3] mb-4">
        <!-- <img 
          v-if="avatarUrl" 
          :src="avatarUrl" 
          alt="Avatar" 
          class="w-full h-full object-cover"
        />
        <div v-else class="text-center p-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <p class="text-gray-300 text-sm">TẢI ẢNH LÊN</p>
        </div> -->
        <img 
          :src="defaultAvatar" 
          alt="Avatar" 
          class="w-full h-full object-cover"
        />
        
        <!-- <input 
          type="file" 
          ref="fileInput" 
          @change="handleFileUpload" 
          accept="image/*" 
          class="absolute inset-0 opacity-0 cursor-pointer"
        /> -->
      </div>
    </div>
    
    <!-- Phần thông tin cổ đông -->
    <div class="md:col-span-2">
      <h2 class="text-2xl font-bold text-white mb-4">THÔNG TIN CỔ ĐÔNG</h2>
      
      <div class="space-y-4">
        <div class="flex items-center">
          <div class="text-gray-300 text-sm mr-2">Cổ đông:</div>
          <div class="text-white font-medium">{{ shareholder.name || 'Nguyễn Văn A' }}</div>
        </div>
        
        <div class="flex items-center">
          <div class="text-gray-300 text-sm mr-2">Mã đại biểu:</div>
          <div class="text-white font-medium">{{ shareholder.code || 'ELC39759' }}</div>
        </div>
        
        <div class="flex items-center">
          <div class="text-gray-300 text-sm mr-2">Số phiếu biểu quyết đại diện:</div>
          <div class="text-white font-medium">{{ shareholder.totalVotes || '0' }}</div>
        </div>
      </div>
      
      <!-- Thống kê phiếu -->
      <div class="grid grid-cols-3 gap-4 mt-8 text-center">
        <div class="stat-card bg-blue-vote-owned rounded-md p-4 relative overflow-hidden">
          <div class="stat-content relative z-10">
            <div class="text-3xl font-bold text-white stat-value">{{ shareholder.ownedVotes || '0' }}</div>
            <div class="text-sm text-white mt-1">Phiếu biểu quyết<br/>sở hữu</div>
          </div>
        </div>
        
        <div class="stat-card bg-blue-vote-delegated rounded-md p-4 relative overflow-hidden">
          <div class="stat-content relative z-10">
            <div class="text-3xl font-bold text-white stat-value">{{ shareholder.delegatedVotes || '0' }}</div>
            <div class="text-sm text-white mt-1">Phiếu biểu quyết<br/>nhận ủy quyền</div>
          </div>
        </div>
        
        <div class="stat-card bg-blue-vote-given rounded-md p-4 relative overflow-hidden">
          <div class="stat-content relative z-10">
            <div class="text-3xl font-bold text-white stat-value">{{ shareholder.delegatedToOthers || '0' }}</div>
            <div class="text-sm text-white mt-1">Phiếu biểu quyết<br/>đã ủy quyền</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { useAuthStore } from '@/store/auth';

export default {
  setup() {
    const authStore = useAuthStore();
    const fileInput = ref(null);
    const avatarFile = ref(null);
    const uploadProgress = ref(0);
    const isUploading = ref(false);
    
    // Mock data cho shareholder (sẽ được thay thế bằng API thực tế)
    const shareholder = ref({
      name: '',
      code: '',
      totalVotes: '',
      ownedVotes: '',
      delegatedVotes: '',
      delegatedToOthers: ''
    });

    // Computed property để hiển thị avatar
    const avatarUrl = computed(() => {
      if (avatarFile.value) {
        return URL.createObjectURL(avatarFile.value);
      } else if (authStore.user?.avatar) {
        return authStore.user.avatar;
      }
      return null;
    });

    // Xử lý upload ảnh
    const handleFileUpload = (event) => {
      const file = event.target.files[0];
      if (!file) return;
      
      // Kiểm tra loại file
      if (!file.type.match('image.*')) {
        alert('Vui lòng chọn file ảnh');
        return;
      }
      
      // Kiểm tra kích thước file (giới hạn 5MB)
      if (file.size > 5 * 1024 * 1024) {
        alert('Kích thước file không được vượt quá 5MB');
        return;
      }
      
      avatarFile.value = file;
      uploadAvatar();
    };
    
    // Upload avatar lên server
    const uploadAvatar = async () => {
      if (!avatarFile.value) return;
      
      // Đây là nơi sẽ gọi API upload thực tế
      try {
        isUploading.value = true;
        uploadProgress.value = 0;
        
        // Giả lập tiến trình upload
        const interval = setInterval(() => {
          uploadProgress.value += 10;
          if (uploadProgress.value >= 100) {
            clearInterval(interval);
            isUploading.value = false;
            
            // TODO: Cập nhật avatar user khi có API thực tế
            // authStore.updateUserAvatar(response.avatarUrl);
          }
        }, 200);
        
        // TODO: Gọi API upload ảnh thực tế
        // const formData = new FormData();
        // formData.append('avatar', avatarFile.value);
        // const response = await uploadAvatarApi(formData);
        
      } catch (error) {
        console.error('Lỗi khi upload avatar:', error);
        alert('Có lỗi xảy ra khi tải ảnh lên. Vui lòng thử lại sau.');
        isUploading.value = false;
      }
    };
    
    // Xóa avatar
    const removeAvatar = () => {
      avatarFile.value = null;
      if (fileInput.value) {
        fileInput.value.value = null;
      }
      
      // TODO: Gọi API xóa avatar khi có API thực tế
      // authStore.removeUserAvatar();
    };
    
    // Load thông tin cổ đông khi component được mount
    onMounted(async () => {
      try {
        // TODO: Gọi API lấy thông tin cổ đông khi có API thực tế
        // const response = await getShareholderInfo();
        // shareholder.value = response.data;
        
        // Dữ liệu mẫu
        if (authStore.user) {
            shareholder.value = {
                name: authStore.user.name || 'Nguyễn Văn A',
                code: authStore.user.ma_co_dong || 'ELC0000',
                totalVotes: 0,
                ownedVotes: authStore.user.co_phan_so_huu || 0,
                delegatedVotes: authStore.user.tong_co_phan_duoc_uy_quyen || 0,
                delegatedToOthers: authStore.user.so_bieu_quyet_da_uy_quyen || 0
            };
            shareholder.value.totalVotes = authStore.user.co_phan_so_huu + authStore.user.tong_co_phan_duoc_uy_quyen;
        }
      } catch (error) {
        console.error('Lỗi khi lấy thông tin cổ đông:', error);
      }
    });

    const defaultAvatar = new URL('../assets/images/avatar_default.png', import.meta.url).href;

    return {
      fileInput,
      avatarUrl,
      avatarFile,
      uploadProgress,
      isUploading,
      shareholder,
      handleFileUpload,
      removeAvatar,
      defaultAvatar
    };
  }
};
</script>

<style scoped>
.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.2);
  z-index: 1;
}

.avatar-container {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.avatar-container > div {
  width: 100%;
  height: 100%;
  aspect-ratio: 1;
}

@media (max-width: 768px) {
  .avatar-container {
    width: 50%;
    margin: 0 auto;
  }
  
  .stat-value {
    font-size: 20px;
  }
}

.stat-card {
  background-size: cover;
  background-position: center;
  background-image: url('@/assets/images/shareholder_detail_info_background.png');
}
.bg-blue-vote-owned {
  background-color: #1e40af; /* Fallback color nếu ảnh không load được */
}

.bg-blue-vote-delegated {
  background-color: #2563eb; /* Fallback color nếu ảnh không load được */
}

.bg-blue-vote-given {
  background-color: #3b82f6; /* Fallback color nếu ảnh không load được */
}
</style> 