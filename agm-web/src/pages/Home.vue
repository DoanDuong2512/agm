<template>
  <div class="home-page min-h-screen bg-gradient-to-b from-[#0056b3] to-[#0A2476]">
    <div class="container mx-auto px-4 py-8">
      <!-- Tiêu đề đại hội -->
      <div class="text-center mb-12 text-white">
        <h1 class="text-3xl md:text-4xl font-bold mb-2 home-title">ĐẠI HỘI CỔ ĐÔNG THƯỜNG NIÊN</h1>
        <h2 class="text-4xl md:text-6xl font-bold custom-color-title home-title-year">NĂM 2025</h2>
      </div>
      
      <!-- Phần thông tin cổ đông và thông tin đại hội -->
<div class="max-w-7xl mx-auto mb-8">
        <div class="w-full bg-[#0A3694]/80 rounded-lg overflow-hidden shadow-xl p-6">
          <ShareholderInfo />
        </div>
      </div>

      <!-- Phần Thông tin Đại hội và Timeline (hàng thứ hai) -->
      <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-8 mb-16">
        <!-- Thông tin Đại hội card -->
        <div class="w-full md:w-1/2 flex flex-col gap-6">
          <div class="bg-[#0A3694]/80 rounded-lg overflow-hidden shadow-xl p-6">
            <h3 class="text-2xl font-bold text-white mb-4">THÔNG TIN ĐẠI HỘI CỔ ĐÔNG</h3>
            <p class="text-white mb-2"><span class="font-semibold">Tình trạng:</span> <span class="font-bold text-[#6DD0FF]">Chưa diễn ra</span></p>
            <p class="text-white mb-2"><span class="font-semibold">Thời gian:</span> <span class="font-bold">08:00 AM - 15/04/2025</span></p>
            <p class="text-white mb-2"><span class="font-semibold">Địa điểm:</span> Tòa nhà ELCOM, Phố Duy Tân, Quận Cầu Giấy, Hà Nội</p>
            <p class="text-white mb-4"><span class="font-semibold">Hình thức tổ chức:</span> Kết hợp trực tiếp và trực tuyến</p>
            <!-- Countdown -->
            <div class="flex items-center gap-2 mt-4">
              <div class="bg-white rounded-lg px-4 py-2 text-[#0A3694] font-bold text-2xl text-center shadow">
               08
                <div class="text-xs font-semibold text-[#0A3694]">Ngày</div>
              </div>
              <span class="text-white font-bold text-xl">:</span>
              <div class="bg-white rounded-lg px-4 py-2 text-[#0A3694] font-bold text-2xl text-center shadow">
                12
                <div class="text-xs font-semibold text-[#0A3694]">Giờ</div>
              </div>
              <span class="text-white font-bold text-xl">:</span>
              <div class="bg-white rounded-lg px-4 py-2 text-[#0A3694] font-bold text-2xl text-center shadow">
                36
                <div class="text-xs font-semibold text-[#0A3694]">Phút</div>
              </div>
              <span class="text-white font-bold text-xl">:</span>
              <div class="bg-white rounded-lg px-4 py-2 text-[#0A3694] font-bold text-2xl text-center shadow">
                19
                <div class="text-xs font-semibold text-[#0A3694]">Giây</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Timeline card -->
        <div class="w-full md:w-1/2 bg-[#0A3694]/80 rounded-lg overflow-hidden shadow-xl pt-4 pl-6 pr-6 pb-0">
          <h4 class="text-lg font-semibold text-white mb-4">TIMELINE</h4>
          <ul class="relative border-l border-[#6DD0FF] pl-4">
            <li class="mb-4">
              <div class="absolute -left-3 flex items-center justify-center w-6 h-6 bg-white border-2 border-[#6DD0FF] rounded-full">
                <span class="w-3 h-3 bg-[#6DD0FF] rounded-full"></span>
              </div>
              <div class="flex items-center pl-6">
                <span class="w-16 text-[#6DD0FF] font-semibold">08:00</span>
                <span class="text-white">PHÁT BIỂU KHAI MẠC</span>
              </div>
            </li>
            <li class="mb-4">
              <div class="absolute -left-3 flex items-center justify-center w-6 h-6 bg-white border-2 border-[#6DD0FF] rounded-full">
                <span class="w-3 h-3 bg-[#6DD0FF] rounded-full"></span>
              </div>
              <div class="flex items-center pl-6">
                <span class="w-16 text-[#6DD0FF] font-semibold">08:10</span>
                <span class="text-white">BÁO CÁO HOẠT ĐỘNG, TÀI CHÍNH</span>
              </div>
            </li>
            <li class="mb-4">
              <div class="absolute -left-3 flex items-center justify-center w-6 h-6 bg-white border-2 border-[#6DD0FF] rounded-full">
                <span class="w-3 h-3 bg-[#6DD0FF] rounded-full"></span>
              </div>
              <div class="flex items-center pl-6">
                <span class="w-16 text-[#6DD0FF] font-semibold">08:35</span>
                <span class="text-white">TRÌNH BÀY VÀ THẢO LUẬN CÁC NGHỊ QUYẾT</span>
              </div>
            </li>
            <li class="mb-4">
              <div class="absolute -left-3 flex items-center justify-center w-6 h-6 bg-white border-2 border-[#6DD0FF] rounded-full">
                <span class="w-3 h-3 bg-[#6DD0FF] rounded-full"></span>
              </div>
              <div class="flex items-center pl-6">
                <span class="w-16 text-[#6DD0FF] font-semibold">09:15</span>
                <span class="text-white">BIỂU QUYẾT CÁC NỘI DUNG QUAN TRỌNG</span>
              </div>
            </li>
            <li class="mb-4">
              <div class="absolute -left-3 flex items-center justify-center w-6 h-6 bg-white border-2 border-[#6DD0FF] rounded-full">
                <span class="w-3 h-3 bg-[#6DD0FF] rounded-full"></span>
              </div>
              <div class="flex items-center pl-6">
                <span class="w-16 text-[#6DD0FF] font-semibold">09:45</span>
                <span class="text-white">GIẢI ĐÁP THẮC MẮC</span>
              </div>
            </li>
            <li>
              <div class="absolute -left-3 flex items-center justify-center w-6 h-6 bg-white border-2 border-[#6DD0FF] rounded-full">
                <span class="w-3 h-3 bg-[#6DD0FF] rounded-full"></span>
              </div>
              <div class="flex items-center pl-6">
                <span class="w-16 text-[#6DD0FF] font-semibold">10:15</span>
                <span class="text-white">KẾT LUẬN VÀ BẾ MẠC</span>
              </div>
            </li>
          </ul>
        </div>
      </div>
      
      <!-- Phần footer thông tin công ty -->
      <div class="home-footer text-center text-white">
        <!-- Các nút chức năng -->
        <div class="flex flex-col items-center justify-center gap-6 mb-10">
          <router-link to="/livestream" class="action-btn action-btn-wide bg-white text-[#1C9AD6] flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            XEM PHÁT SÓNG ĐẠI HỘI
          </router-link>
          <PrintButton 
            buttonText="XÁC NHẬN THAM DỰ" 
            documentType="attendance" 
            :autoPrint="true"
          />
        </div>
        
        <div class="flex sm:flex-row md:flex-row items-center justify-center gap-6 bottom-group-btn">
          <router-link to="/tai-lieu-dai-hoi" class="action-btn action-btn-normal bg-[#1C9AD6] text-white">
            TÀI LIỆU ĐẠI HỘI
          </router-link>
          <router-link to="/livestream" class="action-btn action-btn-normal gradient-border gradient-text">
            BIỂU QUYẾT - BẦU CỬ
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from '../store/auth';
import router from '../router';
import ShareholderInfo from '@/components/ShareholderInfo.vue';
import PrintButton from '@/components/PrintButton.vue';
import { getMeetingConfig } from '@/api/home';
import { ref } from 'vue';
import { showToast } from '@/utils/toast';
export default {
  components: {
    ShareholderInfo,
    PrintButton
  },
  setup() {
    const authStore = useAuthStore();
    const isDownloading = ref(false);

    const logout = () => {
      authStore.logout();
      router.push('/login');
    };

    const downloadAuthorizationForm = async () => {
      if (isDownloading.value) return;
      
      try {
        isDownloading.value = true;
        const response = await getMeetingConfig('giay-uy-quyen-download-url');
        
        if (response.data && response.data.data && response.data.data.value) {
          const downloadUrl = response.data.data.value;
          window.open(downloadUrl, '_blank');
          showToast('success', 'Tải giấy ủy quyền thành công');
        } else {
          // showToast('error', 'Đã có lỗi xảy ra');
        }
      } catch (error) {
        console.error('Failed to get authorization form URL:', error);
        // showToast('error', 'Đã có lỗi xảy ra');
      } finally {
        isDownloading.value = false;
      }
    };

    return { authStore, logout, downloadAuthorizationForm };
  },
};
</script>

<style lang="scss" scoped>
.home-page {
  background-image: url('@/assets/images/home_background.png');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-blend-mode: overlay;
}

.action-btn {
  font-weight: 600;
  transition: all 0.3s ease;
  border-radius: 28px;
  padding: 12px 20px;
  border: none;
  outline: none;
  font-size: 0.9rem;
  letter-spacing: 0.02em;
  
  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }
  
  &:active {
    transform: translateY(0);
  }
}

.action-btn-wide {
  width: 280px;
  height: 48px;
}

.action-btn-normal {
  width: 220px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.gradient-border {
  background: transparent;
  position: relative;
  z-index: 1;
  
  &::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 28px;
    padding: 2px;
    background: linear-gradient(321.18deg, #1C9AD6 7.73%, #006FFF 94.96%);
    -webkit-mask: 
      linear-gradient(#fff 0 0) content-box, 
      linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    z-index: -1;
  }
  
  &:hover {
    background: white;
    &::before {
      opacity: 1;
    }
  }
}

.gradient-text {
  background: linear-gradient(321.18deg, #1C9AD6 7.73%, #006FFF 94.96%);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  
  &:hover {
    background: white;
    -webkit-text-fill-color: transparent;
    background: linear-gradient(321.18deg, #1C9AD6 7.73%, #006FFF 94.96%);
    -webkit-background-clip: text;
    background-clip: text;
  }
}

.custom-color-title {
  background: linear-gradient(315.74deg, #6DD0FF 23.83%, #006FFF 95.4%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.home-footer {
  margin-top: 4rem;
}

.bottom-group-btn {
  gap: 1.5rem;
}

.timeline-box {
  @apply bg-[#0A3694]/80 rounded-lg p-2;
}

@media (max-width: 768px) {
  .action-btn-normal, .action-btn-wide {
    width: 90%;
    max-width: 280px;
    height: 44px;
  }
  
  .action-btn {
    font-size: 0.85rem;
    padding: 10px 16px;
    font-size: 12px;
  }
  
  .home-title {
    font-size: 22px;
  }
  
  .home-title-year {
    font-size: 30px;
  }
  
  .action-btn-normal {
    width: 190px;
    margin: 0 5px;
  }
  
  .container {
    padding-top: 1.5rem;
    padding-bottom: 2rem;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
  }
  
  .mb-12 {
    margin-top: auto;
    margin-bottom: 1.5rem;
  }
  
  .mb-16 {
    margin-bottom: 2rem;
  }
  
  .mb-2 {
    margin-bottom: 0.5rem;
  }
  
  .home-footer {
    margin-top: 0;
  }
  
  .bottom-group-btn {
    gap: 0;
  }
  
  .flex.flex-col.md\:flex-row {
    flex-direction: column;
  }
}
</style>