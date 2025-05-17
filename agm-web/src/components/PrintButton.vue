<template>
  <button 
    @click="goToPrintPage"
    class="print-btn action-btn action-btn-wide bg-white text-[#1C9AD6] flex items-center justify-center"
  >
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
    </svg>
    {{ buttonText }}
  </button>
</template>

<script>
import { useRouter } from 'vue-router';

export default {
  name: 'PrintButton',
  props: {
    buttonText: {
      type: String,
      default: 'In phiếu tham dự'
    },
    autoPrint: {
      type: Boolean,
      default: true
    },
    documentType: {
      type: String,
      default: 'attendance'
    }
  },
  setup(props) {
    const router = useRouter();
    
    const goToPrintPage = () => {
      // Construct the URL
      const baseUrl = window.location.origin;
      const path = '/print-confirmation';
      const queryParams = `?autoprint=${props.autoPrint ? 'true' : 'false'}&documentType=${props.documentType}`;
      const fullUrl = `${baseUrl}${path}${queryParams}`;
      
      // Open in a new tab
      window.open(fullUrl, '_blank');
    };
    
    return {
      goToPrintPage
    };
  }
};
</script>

<style scoped>
/* Chỉ hiển thị nút khi không đang in */
@media print {
  .print-btn {
    display: none !important;
  }
}
</style> 