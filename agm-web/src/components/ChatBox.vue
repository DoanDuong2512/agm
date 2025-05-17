<template>
  <div class="chat-box">
    <!-- Chat Header with Title and Buttons -->
    <div class="p-4 border-b border-[#ffffff1a]">
      <div class="header-action"> 
        <button 
          class="action-button font-medium py-2 px-2 rounded transition duration-200 text-xs"
          @click="downloadDocuments"
        >
          TÀI LIỆU ĐẠI HỘI
        </button>
        <button 
          class="action-button font-medium py-2 px-2 rounded transition duration-200 text-xs"
          @click="goToVoting"
        >
          BIỂU QUYẾT - BẦU CỬ
        </button>
      </div>
      <div class="flex items-center">
        <h2 class="text-lg font-medium text-white">Hỏi đáp</h2>
      </div>
    </div>

    <!-- Chat Messages -->
    <div class="chat-messages-container flex-1 overflow-y-auto p-4" ref="messagesContainer">
      <!-- Loading indicator for more messages -->
      <div v-if="isLoadingMore" class="loading-more-messages py-2 text-center">
        <div class="dot-loader">
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="dot"></div>
        </div>
      </div>
      
      <!-- Messages list -->
      <div 
        v-for="message in messages" 
        :key="message.id" 
        class="flex items-start gap-3 mb-4"
        :class="{'justify-end': message.is_mine, 'message-item': true}"
      >
        <!-- Avatar (only show for messages from others) -->
        <img 
          v-if="!message.is_mine"
          :src="message.avatar" 
          :alt="message.name" 
          class="w-8 h-8 rounded-full object-cover flex-shrink-0"
        />
        
        <!-- Message content -->
        <div class="max-w-[75%]">
          <!-- Sender name and time -->
          <div class="flex items-center justify-between mb-1" :class="{'flex-row-reverse': message.is_mine}">
            <h3 class="font-medium text-xs ml-2" :class="{'text-blue-300': message.is_mine, 'text-gray-300': !message.is_mine}">
              {{ message.is_mine ? 'Bạn' : message.name }}
            </h3>
            <span class="text-xs text-gray-400 ml-2">{{ message.time }}</span>
          </div>
          <!-- Message bubble -->
          <div 
            class="message-bubble"
            :class="{
              'my-bubble': message.is_mine,
              'sender-bubble': !message.is_mine
            }"
          >
            {{ message.content }}
          </div>
        </div>
        
        <!-- Avatar (only show for your messages, on the right) -->
        <img 
          v-if="message.is_mine"
          :src="message.avatar" 
          :alt="message.name" 
          class="w-8 h-8 rounded-full object-cover flex-shrink-0"
        />
      </div>
    </div>

    <!-- Message Input -->
    <div class="p-4 border-t border-[#ffffff1a]">
      <div class="flex gap-2">
        <input 
          v-model="newMessage"
          type="text"
          placeholder="Nhập nội dung..."
          class="flex-1 bg-[#0A2476] rounded px-4 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#007BFF] text-sm"
          @keyup.enter="sendMessage"
        />
        <button 
          class="p-2 bg-transparent hover:opacity-80 transition duration-200 flex items-center justify-center"
          @click="sendMessage"
        >
          <img 
            src="/src/assets/images/icons/send_msg_btn.png" 
            alt="Send" 
            class="w-100 h-100" 
          />
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, nextTick, onUnmounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import { getConversation, sendMessage as apiSendMessage, getMessages } from '@/api/chat';
import Pusher from 'pusher-js';
import { useAuthStore } from '@/store/auth';
import userAvatar from '@/assets/images/avatar_chat_user_default.png';
import adminAvatar from '@/assets/images/avatar_chat_admin.png';

export default {
  name: 'ChatBox',
  emits: ['message-sent'],
  setup(props, { emit }) {
    const router = useRouter();
    const authStore = useAuthStore();
    const messagesContainer = ref(null);
    const newMessage = ref('');
    const messages = ref([]); // Khởi tạo messages trống thay vì từ prop
    const conversationId = ref(null);
    const isLoading = ref(false);
    const pusher = ref(null);
    const channel = ref(null);
    
    // Pagination tracking
    const currentPage = ref(1);
    const lastPage = ref(1);
    const isLoadingMore = ref(false);
    const scrollPosition = ref(0);
    const scrollHeight = ref(0);
    
    // Get JWT token from auth store
    const jwtToken = authStore.userToken;

    // Get API base URL from environment variables
    const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;

    const scrollToBottom = async () => {
      await nextTick();
      if (messagesContainer.value) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
      }
    };
    
    // Function to preserve scroll position after loading more messages
    const preserveScrollPosition = () => {
      if (messagesContainer.value) {
        const newScrollHeight = messagesContainer.value.scrollHeight;
        const heightDifference = newScrollHeight - scrollHeight.value;
        messagesContainer.value.scrollTop = heightDifference;
      }
    };
    
    // Handle scroll event to detect when user has scrolled to the top
    const handleScroll = () => {
      if (messagesContainer.value) {
        if (messagesContainer.value.scrollTop === 0 && !isLoadingMore.value && currentPage.value < lastPage.value) {
          // Save current scroll height before loading more messages
          scrollHeight.value = messagesContainer.value.scrollHeight;
          loadMoreMessages();
        }
      }
    };

    const formatTimestamp = (timestamp) => {
      const date = new Date(timestamp * 1000);
      return date.toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
      });
    };

    const cleanupWebSocketConnection = () => {
      if (pusher.value) {
        if (channel.value && conversationId.value) {
          pusher.value.unsubscribe(`presence-conversation.${conversationId.value}`);
        }
        pusher.value.disconnect();
        pusher.value = null;
        channel.value = null;
      }
    };

    const setupWebSocketConnection = () => {
      if (!conversationId.value || !jwtToken) return;
      
      // Disconnect existing connection if any
      cleanupWebSocketConnection();
      
      // Lấy cấu hình Pusher từ biến môi trường
      const appKey = import.meta.env.VITE_PUSHER_APP_KEY || 'app-key';
      const wsHost = import.meta.env.VITE_PUSHER_HOST || '127.0.0.1';
      const wsPort = Number(import.meta.env.VITE_PUSHER_PORT || 6001);
      const forceTLS = import.meta.env.VITE_PUSHER_FORCE_TLS === 'true';
      const encrypted = import.meta.env.VITE_PUSHER_ENCRYPTED === 'true';
      const cluster = import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1';
      // Initialize Pusher with configuration from environment variables
      pusher.value = new Pusher(appKey, {
        wsHost: wsHost,
        wsPort: wsPort,
        forceTLS: forceTLS,
        encrypted: encrypted,
        disableStats: true,
        enabledTransports: ['ws'],
        cluster: cluster,
        ignoreNullOrigin: true,
        authEndpoint: `${apiBaseUrl}/customer/broadcasting/auth`,
        auth: {
          headers: {
            'Authorization': `Bearer ${jwtToken}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          }
        },
        authorizer: (channel, options) => {
          return {
            authorize: (socketId, callback) => {
              const channelName = `presence-conversation.${conversationId.value}`;
              fetch(options.authEndpoint, {
                method: 'POST',
                headers: options.auth.headers,
                body: JSON.stringify({
                  socket_id: socketId,
                  channel_name: channelName
                })
              })
              .then(response => {
                if (!response.ok) {
                  throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
              })
              .then(data => {
                callback(null, data);
              })
              .catch(error => {
                console.error('Auth error:', error);
                callback(error, null);
              });
            }
          };
        }
      });
      
      // Thêm xử lý lỗi kết nối
      pusher.value.connection.bind('connected', () => {
        subscribeToChannel();
      });
      
      pusher.value.connection.bind('disconnected', () => {
        console.log('Disconnected from Soketi WebSocket');
      });
      
      pusher.value.connection.bind('error', (err) => {
        console.error('Connection error:', err);
      });
    };
    
    const subscribeToChannel = () => {
      if (!pusher.value || !conversationId.value) return;
      
      const channelName = `presence-conversation.${conversationId.value}`;
      
      try {
        // Subscribe to the channel
        channel.value = pusher.value.subscribe(channelName);
        
        // Listen for the new message event
        channel.value.bind('new.message', (data) => {
          // Kiểm tra xem tin nhắn này có phải là từ người dùng hiện tại không
          const isCurrentUser = data.sender && 
                             data.sender.type === "App\\Models\\Customer" && 
                             data.sender.id === authStore.user?.id;
          // Nếu là tin nhắn từ người dùng hiện tại, bỏ qua không hiển thị
          if (isCurrentUser) {
            return;
          }
          
          // Make sure we don't duplicate messages
          if (data.message && !messages.value.some(m => m.id === data.message.id)) {
            // Add new message to the chat using sender information
            messages.value.push({
              id: data.message.id,
              name: data.sender?.name || data.message.sender_name || 'Unknown User',
                            avatar: adminAvatar,
              content: data.message.body,
              time: formatTimestamp(data.message.created_at),
              is_mine: false // Always false as this is from others (we filtered our own messages)
            });
            
            // Scroll to bottom when receiving new message
            scrollToBottom();
          }
        });
        
        // Handle subscription success
        channel.value.bind('pusher:subscription_succeeded', (data) => {
        });
        
        // Handle subscription errors
        channel.value.bind('pusher:subscription_error', (error) => {
          console.error('Subscription error:', error);
        });
      } catch (error) {
        console.error('Error subscribing to channel:', error);
      }
    };

    const fetchConversation = async () => {
      try {
        isLoading.value = true;
        const response = await getConversation('admin@elcom.com.vn');
        
        if (response?.data?.data) {
          let responseData = response.data.data;
          conversationId.value = responseData.id;
          
          // Set up WebSocket connection once we have the conversation ID
          setupWebSocketConnection();
          
          // Load more messages if available
          await loadMessages();
        }
      } catch (error) {
        console.error('Error fetching conversation:', error);
      } finally {
        isLoading.value = false;
      }
    };

    const loadMessages = async () => {
      if (!conversationId.value) return;
      
      try {
        isLoading.value = true;
        currentPage.value = 1; // Reset to first page
        
        const response = await getMessages(conversationId.value, currentPage.value);
        
        if (response?.data) {
          // Update pagination info
          const meta = response.data.meta;
          if (meta) {
            currentPage.value = meta.current_page;
            lastPage.value = meta.last_page;
          }
          
          const apiMessages = response.data.data;
          
          // Map messages and then reverse order to show newest at the bottom
          messages.value = apiMessages
            .map(msg => ({
              id: msg.id,
              name: msg.sender?.name || msg.sender_name || 'Unknown User',
              avatar: msg.is_mine ? userAvatar : adminAvatar,
              content: msg.body,
              time: formatTimestamp(msg.created_at),
              is_mine: msg.is_mine || false
            }))
            .reverse(); // Reverse to get chronological order (oldest first)
        }
      } catch (error) {
        console.error('Error loading messages:', error);
      } finally {
        isLoading.value = false;
        scrollToBottom();
      }
    };
    
    const loadMoreMessages = async () => {
      if (!conversationId.value || isLoadingMore.value || currentPage.value >= lastPage.value) return;
      
      try {
        isLoadingMore.value = true;
        
        // Load the next page
        const nextPage = currentPage.value + 1;
        const response = await getMessages(conversationId.value, nextPage);
        
        if (response?.data) {
          // Update pagination info
          const meta = response.data.meta;
          if (meta) {
            currentPage.value = meta.current_page;
            lastPage.value = meta.last_page;
          }
          
          const apiMessages = response.data.data;
          
          // Map new messages and add to the beginning of the list (older messages)
          const olderMessages = apiMessages
            .map(msg => ({
              id: msg.id,
              name: msg.sender?.name || msg.sender_name || 'Unknown User',
              // Phân biệt avatar dựa vào is_mine
              avatar: msg.is_mine ? userAvatar : adminAvatar,
              content: msg.body,
              time: formatTimestamp(msg.created_at),
              is_mine: msg.is_mine || false
            }))
            .reverse(); // Reverse to get chronological order
            
          // Add older messages at the beginning of the existing messages array
          messages.value = [...olderMessages, ...messages.value];
          
          // Preserve scroll position to prevent jumping
          await nextTick();
          preserveScrollPosition();
        }
      } catch (error) {
        console.error('Error loading more messages:', error);
      } finally {
        isLoadingMore.value = false;
      }
    };

    const sendMessage = async () => {
      if (!newMessage.value.trim()) return;
      
      const messageContent = newMessage.value;
      
      const message = {
        id: Date.now(),
        name: authStore.user?.name || 'User',
        // Sử dụng avatar người dùng cho tin nhắn của mình
        avatar: userAvatar,
        content: messageContent,
        time: new Date().toLocaleTimeString('en-US', { 
          hour: 'numeric',
          minute: '2-digit',
          hour12: true 
        }),
        is_mine: true
      };

      // Add message to UI immediately
      messages.value.push(message);
      newMessage.value = '';
      scrollToBottom();
      
      // Send message to API if we have a conversation ID
      if (conversationId.value) {
        try {
          await apiSendMessage({
            conversation_id: conversationId.value,
            message: messageContent
          });
          emit('message-sent', message);
        } catch (error) {
          console.error('Error sending message:', error);
          // You could add error handling here
        }
      }
    };

    const downloadDocuments = () => {
      router.push('/tai-lieu-dai-hoi');
    };

    const goToVoting = () => {
      console.log('temporarily do nothing');
    };

    onMounted(() => {
      fetchConversation();
      
      // Add scroll event listener to detect when user scrolls to top
      if (messagesContainer.value) {
        messagesContainer.value.addEventListener('scroll', handleScroll);
      }
    });
    
    onUnmounted(() => {
      // Clean up WebSocket connection when component is unmounted
      cleanupWebSocketConnection();
      
      // Remove scroll event listener
      if (messagesContainer.value) {
        messagesContainer.value.removeEventListener('scroll', handleScroll);
      }
    });
    
    // Watch for messagesContainer ref to add scroll listener when it's available
    watch(messagesContainer, (newVal) => {
      if (newVal) {
        newVal.addEventListener('scroll', handleScroll);
      }
    });

    return {
      messages,
      newMessage,
      messagesContainer,
      sendMessage,
      downloadDocuments,
      goToVoting,
      isLoading,
      isLoadingMore
    };
  }
};
</script>

<style lang="scss" scoped>
.chat-box {
  background: #1a3694;
  border-radius: 0.5rem;
  display: flex;
  flex-direction: column;
  height: 100%;
  width: 100%;
}

/* Existing styles for scrollbar and animations */
.overflow-y-auto {
  scrollbar-width: thin;
  scrollbar-color: #007BFF #1a3694;
}

.overflow-y-auto::-webkit-scrollbar {
  width: 4px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background-color: #007BFF;
  border-radius: 2px;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.flex.items-start {
  animation: fadeIn 0.3s ease-out;
}
.chat-messages-container {
  animation: fadeIn 0.3s ease-out;
  /* Dành cho điện thoại di động (màn hình nhỏ) */
  max-height: 300px; /* Giá trị cố định cho mobile */
  overflow-y: auto;
}
.header-action {
  display: flex;
  justify-content: end;
  gap: 1rem;
  .action-button {
    width: 30%;
    background-color: #007BFF;
    color: #fff;
    &hover {
      background-color: #0056b3;
    }
  }
}
@media (max-width: 768px) {
  .header-action {
    margin-bottom: 0.75rem;
    .action-button {
      width: 50%;
    }
  }
}
/* Small tablets (portrait mode) */
@media (min-width: 640px) {
  .chat-messages-container {
    max-height: 350px;
  }
}

/* Medium devices (landscape tablets) */
@media (min-width: 768px) {
  .chat-messages-container {
    max-height: 400px;
  }
}

/* Large devices (laptops/desktops) */
@media (min-width: 1024px) {
  .chat-messages-container {
    /* Sử dụng giá trị tương đối với viewport height khi màn hình đủ lớn */
    max-height: calc(100vh - 250px); /* Giảm 250px để tính đến header, footer, và menu */
  }
}

/* Extra large devices (large desktops) */
@media (min-width: 1280px) {
  .chat-messages-container {
    max-height: calc(100vh - 300px); /* Điều chỉnh thêm cho màn hình lớn */
  }
}

/* Super large screens */
@media (min-width: 1536px) {
  .chat-messages-container {
    max-height: calc(100vh - 411px); /* Điều chỉnh cho màn hình siêu lớn */
  }
}

/* Media query for height */
@media (max-height: 768px) {
  .chat-messages-container {
    max-height: calc(100vh - 405px); /* Điều chỉnh cho màn hình có chiều cao nhỏ */
  }
}

@media (max-height: 600px) {
  .chat-messages-container {
    max-height: calc(100vh - 427px); /* Điều chỉnh thêm cho màn hình rất thấp */
  }
}

/* Add styles for message bubbles */
.message-bubble {
  padding: 8px 12px;
  border-radius: 18px;
  display: inline-block;
  max-width: 100%;
  word-wrap: break-word;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
  position: relative;
  text-align: left;
  font-size: 14px;
  margin-top: 2px;
}

.my-bubble {
  background-color: #0084ff;
  color: white;
  border-top-right-radius: 4px;
}

.sender-bubble {
  background-color: #f1f1f1;
  color: #333333;
  border-top-left-radius: 4px;
}

/* Loading dots animation for infinite scroll */
.loading-more-messages {
  padding: 10px 0;
}

.dot-loader {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 5px;
}

.dot {
  width: 8px;
  height: 8px;
  background-color: #007BFF;
  border-radius: 50%;
  display: inline-block;
  animation: bounce 1.5s infinite ease-in-out both;
}

.dot:nth-child(1) {
  animation-delay: -0.30s;
}

.dot:nth-child(2) {
  animation-delay: -0.15s;
}

.dot:nth-child(3) {
  animation-delay: 0s;
}

@keyframes bounce {
  0%, 80%, 100% { 
    transform: scale(0);
  } 40% { 
    transform: scale(1.0);
  }
}

.message-item {
  animation: fadeIn 0.3s ease-out;
}
</style>