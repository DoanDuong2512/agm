<template>
  <AuthLayout>
    <div class="close-system-content">
      <h1 class="main-title">ĐẠI HỘI ĐỒNG CỔ ĐÔNG THƯỜNG NIÊN NĂM 2025</h1>
      <p class="date">THỨ NĂM, NGÀY 24 THÁNG 04 NĂM 2025</p>
      
      <!-- <div class="status-box">
        <div class="clock-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
        </div>
        
        <h2 class="status-title">HỆ THỐNG HIỆN CHƯA ĐƯỢC MỞ</h2>
        <p class="status-message">Quý cổ đông vui lòng quay lại sau để tham gia đại hội.</p>
        <p class="status-message">Hệ thống sẽ được mở từ 12h ngày 23 tháng 04 năm 2025</p>
      </div> -->
    </div>
  </AuthLayout>
</template>

<script>
import AuthLayout from '@/layouts/AuthLayout.vue';

export default {
  name: 'CloseSystem',
  components: {
    AuthLayout
  }
};
</script>

<style scoped>
.close-system-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
  color: white;
}

.main-title {
  font-size: 2rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
  text-align: center;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
}

.date {
  font-size: 1.2rem;
  margin-bottom: 3rem;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
}

.status-box {
  background-color: white;
  border-radius: 8px;
  padding: 1rem;
  width: 100%;
  max-width: 550px;
  text-align: center;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.clock-icon {
  display: flex;
  justify-content: center;
  margin-bottom: 1rem;
  color: #2c3e50;
}

.status-title {
  font-size: 1.5rem;
  font-weight: bold;
  color: #2c3e50;
  margin-bottom: 1rem;
}

.status-message {
  color: #636268;
  font-size: 1.1rem;
}

.footer-logo {
  margin-top: 2rem;
}

.small-logo {
  max-width: 120px;
}

@media (max-width: 768px) {
  .main-title {
    font-size: 1.5rem;
  }
  
  .date {
    font-size: 1rem;
  }
  
  .status-title {
    font-size: 1.25rem;
  }
  
  .status-message {
    font-size: 1rem;
  }
}
</style>
