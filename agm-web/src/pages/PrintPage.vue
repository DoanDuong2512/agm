<template>
  <div class="print-page">
    <!-- Phần hiển thị nội dung cần in -->
    <component
      :is="documentComponent"
      ref="documentRef"
      :class="{ 'hidden': !showContent}"
    />

    <div v-if="!isPrinting" class="flex flex-col items-center justify-center h-screen bg-gray-100">
      <div class="bg-white rounded-lg shadow-md p-8 max-w-md text-center">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">{{ documentTitle }}</h1>
        <p class="mb-6 text-gray-600">Nhấn nút bên dưới để in {{ documentType }}</p>

        <button @click="printNow" class="px-6 py-2 mr-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
          </svg>
          In ngay
        </button>

        <button @click="goBack" class="px-6 py-2 border border-gray-300 text-white-700 rounded-md transition-colors ml-2">
          Quay lại
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import AttendanceConfirmation from '@/components/AttendanceConfirmation.vue';
import VoteCard from '@/components/VoteCard.vue';
import TheBieuQuyet from '@/components/TheBieuQuyet.vue';
// Import các component khác mà bạn muốn sử dụng
// import OtherPrintDocument from '@/components/OtherPrintDocument.vue';

export default {
  name: 'PrintPage',
  components: {
    AttendanceConfirmation,
    VoteCard,
    TheBieuQuyet
    // Đăng ký các component khác mà bạn muốn sử dụng
    // OtherPrintDocument
  },
  setup() {
    const router = useRouter();
    const route = useRoute();
    const documentRef = ref(null);
    const showContent = ref(false);
    const isPrinting = ref(false);

    // Xác định component cần in dựa trên tham số documentType
    const documentComponent = computed(() => {
      const type = route.query.documentType || 'attendance';

      // Ánh xạ các loại document đến component tương ứng
      const componentMap = {
        'attendance': 'AttendanceConfirmation',
        'votecard': 'VoteCard',
        'thebieuquyet': 'TheBieuQuyet',
        // Thêm các loại document khác ở đây
        // 'other-document': 'OtherPrintDocument',
      };

      return componentMap[type] || 'AttendanceConfirmation';
    });

    // Tiêu đề trang tương ứng với từng loại phiếu
    const documentTitle = computed(() => {
      const type = route.query.documentType || 'attendance';

      const titleMap = {
        'attendance': 'In phiếu xác nhận tham dự',
        // Thêm các loại document khác ở đây
        // 'other-document': 'In phiếu khác',
      };

      return titleMap[type] || 'In phiếu';
    });

    // Loại phiếu
    const documentType = computed(() => {
      const type = route.query.documentType || 'attendance';

      const typeTextMap = {
        'attendance': 'phiếu xác nhận tham dự',
        // Thêm các loại document khác ở đây
        // 'other-document': 'phiếu khác',
      };

      return typeTextMap[type] || 'phiếu';
    });

    // Tải dữ liệu cho phiếu (nếu cần)
    const loadDocumentData = async () => {
      const type = route.query.documentType || 'attendance';

      // Kích hoạt phương thức loadUserData cho component hiện tại
      if (documentRef.value && typeof documentRef.value.loadUserData === 'function') {
        await documentRef.value.loadUserData();
      }
    };

    const printNow = async () => {
      // Tải dữ liệu trước khi in
      await loadDocumentData();

      showContent.value = true;
      isPrinting.value = true;

      setTimeout(() => {
        window.print();

        // Đặt timeout để ẩn lại component sau khi dialog in đóng
        setTimeout(() => {
          showContent.value = false;
          isPrinting.value = false;
        }, 500);
      }, 300);
    };

    const goBack = () => {
      if (window.history.length > 1 && document.referrer.includes(window.location.host)) {
        router.back();
      } else {
        // If opened in a new tab or no history, navigate to home
        router.push('/');
      }
    };

    // Tự động in khi load trang nếu có query parameter
    onMounted(() => {
      if (route.query.autoprint === 'true') {
        printNow();
      }
    });

    // Sự kiện khi in bắt đầu/kết thúc
    onMounted(() => {
      window.addEventListener('beforeprint', () => {
        isPrinting.value = true;
      });

      window.addEventListener('afterprint', () => {
        isPrinting.value = false;
      });
    });

    return {
      documentRef,
      showContent,
      isPrinting,
      documentComponent,
      documentTitle,
      documentType,
      printNow,
      goBack
    };
  }
};
</script>

<style scoped>
@media print {
  .print-page > *:not(.attendance-confirmation):not(.dynamic-document) {
    display: none !important;
  }
}
</style>
