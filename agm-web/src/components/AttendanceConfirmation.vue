<template>
  <div class="attendance-confirmation hidden print:block bg-white p-8 text-black">
    <div class="mb-8 text-left">
      <img src="@/assets/images/logo_elcom_2.svg" alt="Elcom Logo" class="h-12 mb-4" />
    </div>
    
    <div class="text-center mb-5">
      <h1 class="text-3xl font-bold" style="font-size: 24px; margin-bottom: 10px">GIẤY XÁC NHẬN THAM DỰ</h1>
      <h2 class="text-2xl font-bold" style="font-size: 18px;">ĐẠI HỘI CỔ ĐÔNG THƯỜNG NIÊN NĂM 2025</h2>
    </div>
    
    <table class="w-full mb-4 text-left border-collapse confirmation-table">
      <tbody>
        <tr class="row">
          <td class="py-1 w-1/3">Họ và tên Cổ đông/Đại diện:</td>
          <td class="py-1">{{ shareholder.name }}</td>
        </tr>
        <tr class="row">
          <td class="py-1 w-1/3">Số cổ phần sở hữu:</td>
          <td class="py-1">
            <span class="">{{ shareholder.ownedVotes }}</span>
            <span class="ml-2">Cổ phần</span>
          </td>
        </tr>
        <tr class="row">
          <td class="py-1 w-1/3">Số cổ phần được ủy quyền:</td>
          <td class="py-1">
            <span class="">{{ shareholder.delegatedVotes }}</span>
            <span class="ml-2">Cổ phần</span>
          </td>
        </tr>
        <tr class="row">
          <td class="py-1 w-1/3">Tổng số cổ phần biểu quyết:</td>
          <td class="py-1">
            <span class="">{{ shareholder.totalVotes }}</span>
            <span class="ml-2">Cổ phần</span>
          </td>
        </tr>
        <tr class="row">
          <td class="py-1 w-1/3">CCCD/CMND/ĐKDN số:</td>
          <td class="py-1">{{ shareholder.vnId || '---' }}</td>
        </tr>
        <tr class="row">
          <td class="py-1 w-1/3">MCĐ:</td>
          <td class="py-1">{{ shareholder.code }}</td>
        </tr>
      </tbody>
    </table>
    
    <div class="mb-4 text-left">
      <p>Căn cứ Thư mời họp Đại hội đồng cổ đông thường niên năm 2025, tôi xác nhận tham dự Đại hội của Công ty Cổ phần Công nghệ - Viễn thông ELCOM được tổ chức vào ngày 24/04/2025.</p>
    </div>
    
    <div class="flex justify-end">
      <div class="text-center w-81" style="padding-right: 8px;">
        <p class="italic">Hà Nội, ngày 24 tháng 04 năm 2025</p>
        <p class="font-bold whitespace-nowrap">CỔ ĐÔNG/NGƯỜI ĐƯỢC ỦY QUYỀN</p>
        <p class="italic">(Ký và ghi rõ họ tên)</p>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/store/auth';

export default {
  name: 'AttendanceConfirmation',
  setup() {
    const authStore = useAuthStore();
    const shareholder = ref({
      name: '',
      code: '',
      totalVotes: 0,
      ownedVotes: 0,
      delegatedVotes: 0,
      vnId: ''
    });
    
    const printConfirmation = () => {
      window.print();
    };
    
    const loadUserData = () => {
      // Lấy dữ liệu từ auth store
      if (authStore.user) {
        shareholder.value = {
          name: authStore.user.name || '---',
          code: authStore.user.ma_co_dong || '---',
          ownedVotes: authStore.user.co_phan_so_huu || 0,
          delegatedVotes: authStore.user.tong_co_phan_duoc_uy_quyen || 0,
          vnId: authStore.user.vn_id || '---'
        };
        shareholder.value.totalVotes = authStore.user.co_phan_bieu_quyet || 0;
      }
    };
    
    onMounted(() => {
      loadUserData();
    });
    
    return {
      shareholder,
      printConfirmation,
      loadUserData
    };
  }
};
</script>

<style>
.attendance-confirmation {
  max-width: 800px;
  margin: 0 auto;
  font-family: 'Times New Roman', Times, serif;
  line-height: 1.6;
  font-size: 16px;
}

/* Điều chỉnh bảng để mỗi dòng hiển thị riêng biệt */
.confirmation-table {
  border-spacing: 0 2px;
  border: none;
}

.confirmation-table .row {
  border-bottom: 1px solid #eaeaea;
  margin-bottom: 2px;
}

.confirmation-table td {
  padding-top: 2px;
  padding-bottom: 2px;
  vertical-align: top;
  border: none;
}

@media print {
  @page {
    size: A4;
    margin: 1cm;
  }
  
  .attendance-confirmation,
  .attendance-confirmation * {
    visibility: visible;
  }
  
  .attendance-confirmation {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: auto;
    overflow: visible;
    background-color: #fff;
  }
  
  /* Đảm bảo table hiển thị đúng */
  .attendance-confirmation table {
    display: table;
    border-collapse: separate;
    border-spacing: 0 2px;
    width: 100%;
    border: none;
  }
  
  .attendance-confirmation tr {
    display: table-row;
    margin-bottom: 2px;
    page-break-inside: avoid;
    break-inside: avoid;
    border: none;
  }
  
  .attendance-confirmation td {
    display: table-cell;
    padding-top: 2px;
    padding-bottom: 2px;
    vertical-align: top;
    border: none;
  }
  
  /* Thêm CSS để xóa tất cả border khi in */
  .confirmation-table,
  .confirmation-table tr,
  .confirmation-table td {
    border: none;
    box-shadow: none;
    outline: none;
  }
}
</style>