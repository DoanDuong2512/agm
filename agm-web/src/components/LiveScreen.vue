<template>
  <div class="live-screen-container">
    <div class="video-wrapper">
      <!-- Loading state -->
      <div v-if="loading" class="video-overlay loading-overlay">
        <div class="loading-spinner"></div>
        <p class="mt-4 text-lg font-medium">Đang tải ...</p>
      </div>
      
      <!-- Livestream iframe - chỉ hiển thị khi có URL hợp lệ -->
      <iframe 
        v-else-if="validatedUrl"
        class="video-frame"
        :src="validatedUrl" 
        frameborder="0" 
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
        allowfullscreen
      ></iframe>
      
      <!-- Fallback thumbnail khi không có URL hợp lệ hoặc đang loading -->
      <div v-else class="thumbnail-container">
        <img 
          src="/src/assets/images/home_background.png" 
          alt="Livestream thumbnail" 
          class="thumbnail-image"
          @error="handleImageError"
        />
        <div class="thumbnail-overlay">
          <p class="text-lg font-medium">Livestream chưa sẵn sàng</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue';

export default {
  name: 'LiveScreen',
  props: {
    url: {
      type: String,
      default: ''
    },
    loading: {
      type: Boolean,
      default: false
    }
  },
  setup(props) {
    const fallbackImage = ref('/src/assets/images/home_background.jpg');
    
    // Validate URL để đảm bảo định dạng YouTube embed
    const validatedUrl = computed(() => {
      const urlToCheck = props.url.trim();
      
      // Kiểm tra định dạng YouTube embed URL
      if (urlToCheck && urlToCheck.match(/^https:\/\/www\.youtube\.com\/embed\/[\w-]+/)) {
        return urlToCheck;
      }
      
      // Kiểm tra các URL YouTube khác và chuyển đổi thành embed URL
      if (urlToCheck) {
        // Kiểm tra URL dạng youtube.com/watch?v=VIDEO_ID
        let videoId = '';
        
        // youtube.com/watch?v=VIDEO_ID
        const watchMatch = urlToCheck.match(/youtube\.com\/watch\?v=([\w-]+)/);
        if (watchMatch && watchMatch[1]) {
          videoId = watchMatch[1];
        }
        
        // youtu.be/VIDEO_ID
        const shortMatch = urlToCheck.match(/youtu\.be\/([\w-]+)/);
        if (shortMatch && shortMatch[1]) {
          videoId = shortMatch[1];
        }
        
        // youtube.com/live/VIDEO_ID
        const liveMatch = urlToCheck.match(/youtube\.com\/live\/([\w-]+)/);
        if (liveMatch && liveMatch[1]) {
          videoId = liveMatch[1];
        }
        
        if (videoId) {
          return `https://www.youtube.com/embed/${videoId}`;
        }
      }
      
      // Nếu không phải URL YouTube embed hợp lệ, trả về chuỗi rỗng
      return '';
    });
    
    // Xử lý khi ảnh thumbnail lỗi
    const handleImageError = (e) => {
      e.target.src = fallbackImage.value;
    };

    return {
      validatedUrl,
      handleImageError
    };
  }
};
</script>

<style scoped>
.live-screen-container {
  width: 100%;
}

.video-wrapper {
  position: relative;
  width: 100%;
  padding-top: 56.25%; /* 16:9 Aspect Ratio */
  background: black;
  border-radius: 0.5rem;
  overflow: hidden;
}

.video-frame {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.thumbnail-container {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.thumbnail-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.thumbnail-overlay {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(0, 0, 0, 0.6);
  color: white;
}

.video-overlay {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(0, 0, 0, 0.5);
  color: white;
}

/* Add loading spinner styles */
.loading-overlay {
  position: absolute;
  inset: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: rgba(0, 0, 0, 0.7);
  color: white;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 4px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: white;
  animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>