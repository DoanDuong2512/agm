<template>
  <div class="container mx-auto px-4 py-10">
    <!-- Title -->
    <div class="text-center mb-16">
      <h1 class="text-3xl md:text-4xl font-bold text-[#0A2476]">TÀI LIỆU ĐẠI HỘI</h1>
    </div>

    <!-- Loading state -->
    <div v-if="loading" class="flex justify-center my-10">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#0A2476]"></div>
    </div>

    <!-- Error state -->
    <div v-else-if="error" class="text-center text-red-600 my-10">
      <p>{{ error }}</p>
      <button @click="fetchDocuments" class="mt-4 bg-[#0A2476] text-white px-4 py-2 rounded">
        Thử lại
      </button>
    </div>

    <!-- Empty state -->
    <div v-else-if="documents.length === 0" class="text-center my-10">
      <p class="text-gray-500">Không có tài liệu nào</p>
    </div>

    <!-- Document list -->
    <div v-else class="max-w-4xl mx-auto space-y-6">
      <div v-for="doc in documents" :key="doc.id" class="flex items-center justify-between border-b pb-4">
        <div class="flex-grow cursor-pointer" @click="viewDocument(doc)">
          <h2 class="text-lg font-semibold text-[#0A2476] mb-1 hover:underline">{{ doc.title }}</h2>
          <div class="flex items-center text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
            </svg>
            <span class="text-sm">{{ formatDateFull(doc.created_at) }}</span>
          </div>
        </div>
        <div>
          <button 
            @click="downloadFile(doc)" 
            class="download-btn btn btn-ghost btn-sm text-[#1C9AD6] normal-case flex items-center gap-1"
            title="Tải tài liệu"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            <span>Tải file</span>
          </button>
        </div>
      </div>
    </div>
    
    <!-- Pagination -->
    <div v-if="pagination && pagination.total_pages > 1" class="flex justify-center mt-8">
      <div class="join">
        <button 
          class="join-item btn" 
          :class="{ 'btn-disabled': currentPage === 1 }"
          @click="changePage(currentPage - 1)"
        >
          «
        </button>
        
        <button 
          v-for="page in paginationArray" 
          :key="page" 
          class="join-item btn" 
          :class="{ 'btn-active': page === currentPage }"
          @click="changePage(page)"
        >
          {{ page }}
        </button>
        
        <button 
          class="join-item btn" 
          :class="{ 'btn-disabled': currentPage === pagination.total_pages }"
          @click="changePage(currentPage + 1)"
        >
          »
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { getDocuments, downloadDocument } from '@/api/document';
import { showToast } from '@/utils/toast';

// State
const documents = ref([]);
const loading = ref(true);
const error = ref(null);
const pagination = ref(null);
const currentPage = ref(1);
const perPage = ref(10);

// Computed
const paginationArray = computed(() => {
  if (!pagination.value) return [];
  
  const totalPages = pagination.value.total_pages;
  const current = currentPage.value;
  
  // Show all pages if 7 or fewer
  if (totalPages <= 7) {
    return [...Array(totalPages)].map((_, i) => i + 1);
  }
  
  // Complex logic for many pages
  let pages = [];
  
  // Always include first page
  pages.push(1);
  
  // Current page is close to start
  if (current <= 3) {
    pages.push(2, 3, 4, '...', totalPages);
  } 
  // Current page is close to end
  else if (current >= totalPages - 2) {
    pages.push('...', totalPages - 3, totalPages - 2, totalPages - 1, totalPages);
  } 
  // Current page is in middle
  else {
    pages.push('...', current - 1, current, current + 1, '...', totalPages);
  }
  
  return pages;
});

// Methods
const fetchDocuments = async () => {
  loading.value = true;
  error.value = null;
  
  try {
    const response = await getDocuments({
      page: currentPage.value,
      per_page: perPage.value
    });
    
    documents.value = response.data.data;
    pagination.value = response.data.meta.pagination;
  } catch (err) {
    console.error('Failed to fetch documents:', err);
    error.value = 'Không thể tải danh sách tài liệu. Vui lòng thử lại sau.';
    showToast('error', error.value);
  } finally {
    loading.value = false;
  }
};

const changePage = (page) => {
  if (page < 1 || page > pagination.value.total_pages || page === '...') {
    return;
  }
  
  currentPage.value = page;
  fetchDocuments();
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  
  const date = new Date(dateString);
  return date.toLocaleDateString('vi-VN', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  });
};

// Định dạng ngày tháng theo yêu cầu: "15 tháng 11, 2021"
const formatDateFull = (timestamp) => {
  if (!timestamp) return '';
  
  // Kiểm tra xem timestamp có phải là chuỗi không
  if (typeof timestamp === 'string') {
    // Nếu là chuỗi có định dạng "YYYY-MM-DD HH:MM:SS", chuyển sang Date object
    if (timestamp.includes('-') || timestamp.includes(':')) {
      const date = new Date(timestamp);
      const day = date.getDate();
      const month = date.getMonth() + 1;
      const year = date.getFullYear();
      return `${day} tháng ${month}, ${year}`;
    }
    
    // Nếu là chuỗi số nguyên, chuyển sang số
    timestamp = parseInt(timestamp, 10);
  }
  
  // Xử lý timestamp dạng số (giây từ epoch)
  const date = new Date(timestamp * 1000); // Convert từ giây sang milli giây
  const day = date.getDate();
  const month = date.getMonth() + 1;
  const year = date.getFullYear();
  
  return `${day} tháng ${month}, ${year}`;
};

const viewDocument = (doc) => {
  // Mở tài liệu trong tab mới
  window.open(doc.view_url, '_blank');
};

const downloadFile = async (doc) => {
  try {
    const response = await downloadDocument(doc.id);
    
    // Tạo URL cho blob
    const blob = new Blob([response.data]);
    const url = window.URL.createObjectURL(blob);
    
    // Tạo thẻ a để tải xuống
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', doc.file_name);
    
    // Thêm sự kiện để biết khi nào tải xuống hoàn tất
    let downloadComplete = false;
    
    // Hiển thị thông báo "đang tải"
    showToast('info', 'Đang tải xuống tài liệu...', 1000);
    
    // Xử lý khi tải xuống hoàn tất
    link.onload = () => {
      downloadComplete = true;
    };
    
    // Xử lý khi click vào link hoàn tất
    link.onclick = () => {
      setTimeout(() => {
        // Dọn dẹp URL object
        window.URL.revokeObjectURL(url);
        
        // Hiển thị thông báo thành công nếu không có lỗi
        if (!downloadComplete) {
          showToast('success', 'Tài liệu đã được tải xuống thành công');
        }
      }, 100);
    };
    
    // Thực hiện tải xuống
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } catch (err) {
    console.error('Failed to download document:', err);
    showToast('error', 'Không thể tải xuống tài liệu. Vui lòng thử lại sau.');
  }
};

// Lifecycle hooks
onMounted(() => {
  fetchDocuments();
});
</script>

<style scoped>
.join {
  display: flex;
}

.join-item {
  margin: 0;
  border-radius: 0;
}

.join-item:first-child {
  border-top-left-radius: 0.5rem;
  border-bottom-left-radius: 0.5rem;
}

.join-item:last-child {
  border-top-right-radius: 0.5rem;
  border-bottom-right-radius: 0.5rem;
}

.btn-active {
  background-color: #0A2476;
  color: white;
}

/* Thêm hiệu ứng hover cho nút tải */
.download-btn {
  position: relative;
  transition: all 0.3s ease;
  overflow: hidden;
}

.download-btn:hover {
  background-color: rgba(28, 154, 214, 0.1);
  transform: translateY(-2px);
  box-shadow: 0 2px 8px rgba(28, 154, 214, 0.2);
}

.download-btn:active {
  transform: translateY(0);
}
</style>