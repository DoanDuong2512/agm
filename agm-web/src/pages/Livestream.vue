<template>
  <div class="livestream-page bg-[#0A2476] text-white">
    <div class="flex flex-col flex-grow h-full w-full">
      <div class="content-wrapper flex-grow">
        <div class="left-container">
          <LiveScreen
            :url="livestreamUrl"
            :loading="isLoading"
          />
        </div>
        <div class="right-container">
          <ChatBox
            @message-sent="handleMessageSent"
          />
        </div>
      </div>
      <div class="mt-4">
        <Vote></Vote>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue';
import ChatBox from '@/components/ChatBox.vue';
import LiveScreen from '@/components/LiveScreen.vue';
import Vote from '@/components/Vote.vue';
import { getUrlLivestream } from '@/api/livestream';

export default {
  components: {
    ChatBox,
    LiveScreen,
    Vote,
  },
  setup() {
    const livestreamUrl = ref('');
    const isLoading = ref(true);

    // Fetch livestream URL from API
    const fetchLivestreamUrl = async () => {
      try {
        isLoading.value = true;
        const response = await getUrlLivestream();
        
        // Chỉ lấy giá trị và gán vào biến livestreamUrl
        if (response?.data?.data?.value) {
          // Sử dụng JSON.parse và JSON.stringify để xử lý các ký tự escape
          const cleanUrl = JSON.parse(JSON.stringify(response.data.data.value));
          livestreamUrl.value = cleanUrl;
        } else {
          livestreamUrl.value = '';
        }
      } catch (error) {
        console.error('Error fetching livestream URL:', error);
        livestreamUrl.value = '';
      } finally {
        isLoading.value = false;
      }
    };

    const handleMessageSent = (message) => {
    };

    onMounted(() => {
      fetchLivestreamUrl();
    });

    onUnmounted(() => {
      // Xử lý khi component bị hủy
    });

    return {
      livestreamUrl,
      isLoading,
      handleMessageSent
    };
  }
};
</script>

<style scoped>
.livestream-page {
  min-height: 100%;
  height: 100%;
  padding: 2rem;
  background: linear-gradient(180deg, #03206B 0%, #0538B9 100%);
  display: flex;
  flex-direction: column;
}
.content-wrapper {
  display: flex;
  gap: 1.5rem;
  flex-direction: column;
}
.left-container {
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
.video-overlay {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(0, 0, 0, 0.5);
}
/* Chat Section */
.right-container {
  width: 100%;
  margin-top: 1.5rem;
  display: flex;
  flex-direction: column;
}
/* Desktop Styles */
@media (min-width: 1024px) {
  .content-wrapper {
    flex-direction: row;
    align-items: stretch; /* Thay đổi từ flex-start sang stretch để các container có cùng chiều cao */
  }

  .left-container {
    width: 66.666667%; /* 2/3 width */
    display: flex; /* Đảm bảo left-container là một flex container */
    flex-direction: column; /* Sắp xếp theo chiều dọc */
  }

  .right-container {
    width: 33.333333%; /* 1/3 width */
    margin-top: 0;
    display: flex; /* Đảm bảo right-container là một flex container */
    flex-direction: column; /* Sắp xếp theo chiều dọc */
  }
}
</style>
