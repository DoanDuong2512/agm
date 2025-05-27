@extends('cms::layouts.master')
@push('styles')
    <!-- <link rel="stylesheet" href="{{ asset('modules/cms/static/css/fontawesome-all.min.css') }}"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @vite(['D:/datn_duong/agm/Modules/CMS/resources/assets/sass/chat.scss'], 'build-cms')
@endpush
@push('scripts')
    <script src="{{ asset('modules/cms/static/js/pusher.min.js') }}"></script>
    <script src="{{ asset('modules/cms/static/js/date-fns-4.1.0.min.js') }}"></script>
    <script>
        const { createApp, ref, computed, onMounted, nextTick, watch } = Vue;

        // Cấu hình axios mặc định
        axios.defaults.headers.common['Accept'] = 'application/json';

        createApp({
            setup() {
                // Auth & current user
                const accessToken = ref(localStorage.getItem('access_token_admin') || '');
                const currentUser = ref(JSON.parse(localStorage.getItem('user') || '{}'));

                // Cấu hình axios với token
                axios.interceptors.request.use(function (config) {
                    if (accessToken.value) {
                        config.headers.Authorization = `Bearer ${accessToken.value}`;
                    }
                    return config;
                });

                // Chat state
                const conversations = ref([]);
                const activeConversation = ref(null);
                const messages = ref([]);
                const newMessage = ref('');
                const searchQuery = ref('');
                const loading = ref(true);
                const loadingMessages = ref(false);
                const messagesContainer = ref(null);
                const messagesEnd = ref(null);
                const messageInput = ref(null);
                const currentPage = ref(1);
                const hasMoreMessages = ref(true);

                // New conversation modal state
                const showCustomersList = ref(false);
                const customers = ref([]);
                const customerSearchQuery = ref('');
                const loadingCustomers = ref(false);

                // Pusher/Socket setup
                const pusher = ref(null);
                const channel = ref(null);

                // Thêm biến để theo dõi khi nào đã cuộn xuống dưới cùng lần đầu tiên
                const initialScrollComplete = ref(false);

                // Thêm biến để kiểm soát việc tải thêm tin nhắn
                const isLoadingOlderMessages = ref(false);

                // Thêm biến để theo dõi
                const shouldAutoscroll = ref(true);

                // Fetch conversations list
                const fetchConversations = async () => {
                    try {
                        loading.value = true;
                        const response = await axios.get('/api/admin/conversations');
                        if (response.status == 200 && response.data) {
                            conversations.value = response.data.data;
                        }
                    } catch (error) {
                        window.showToast('Đã có lỗi xảy ra khi tải cuộc trò chuyện', 'error')
                        console.error('Error fetching conversations:', error);
                    } finally {
                        loading.value = false;
                    }
                };

                // Fetch messages for a conversation
                const fetchMessages = async (conversationId, page = 1) => {
                    // Lưu vị trí cuộn hiện tại trước khi tải thêm tin nhắn
                    let scrollPos = 0;
                    let isLoadingMore = page > 1;
                    let oldHeight = 0;

                    if (isLoadingMore && messagesContainer.value) {
                        scrollPos = messagesContainer.value.scrollTop;
                        oldHeight = messagesContainer.value.scrollHeight;
                    }

                    if (page === 1) {
                        loadingMessages.value = true;
                        messages.value = [];
                    } else {
                        // Đánh dấu đang tải thêm tin nhắn cũ
                        isLoadingOlderMessages.value = true;
                    }

                    if (page > 1) {
                        // Đang tải tin nhắn cũ, không nên tự động cuộn
                        shouldAutoscroll.value = false;
                    } else {
                        // Đang tải tin nhắn mới, nên tự động cuộn
                        shouldAutoscroll.value = true;
                    }

                    try {
                        const response = await axios.get(`/api/admin/conversation/get-messages/${conversationId}`, {
                            params: {
                                page: page,
                                per_page: 10,  // Số lượng tin nhắn mỗi trang
                            }
                        });

                        const responseData = response.data;
                        if (responseData && responseData.data) {
                            const newMessages = responseData.data;
                            if (page === 1) {
                                messages.value = newMessages;
                                // Chờ DOM cập nhật và sau đó cuộn xuống dưới
                                await nextTick();
                                setTimeout(() => {
                                    scrollToBottom();
                                }, 200);
                            } else {
                                // Thêm tin nhắn cũ vào cuối mảng (vì API trả về tin nhắn mới nhất trước)
                                messages.value = [...messages.value, ...newMessages];

                                // Chờ DOM cập nhật
                                await nextTick();

                                // Quan trọng: Tắt tạm thời watcher messages để tránh tự động cuộn xuống dưới
                                // Đặt một biến để đánh dấu rằng đang cập nhật vị trí cuộn thủ công
                                let updatingScrollPosition = true;

                                // Tính toán và đặt vị trí cuộn sau khi DOM đã cập nhật
                                if (messagesContainer.value) {
                                    const newHeight = messagesContainer.value.scrollHeight;
                                    const heightDiff = newHeight - oldHeight;

                                    // Đặt vị trí cuộn mới = vị trí cuộn cũ + chiều cao mới thêm vào
                                    // để giữ nguyên vị trí tương đối
                                    messagesContainer.value.scrollTop = scrollPos + heightDiff;

                                    // Đánh dấu đã hoàn thành cập nhật vị trí cuộn
                                    setTimeout(() => {
                                        updatingScrollPosition = false;
                                    }, 100);
                                }
                            }

                            // Kiểm tra phân trang từ meta data
                            if (responseData.meta) {
                                hasMoreMessages.value = responseData.meta.current_page < responseData.meta.last_page;
                                currentPage.value = responseData.meta.current_page;
                            }
                        }

                        return response;
                    } catch (error) {
                        window.showToast('Đã có lỗi xảy ra khi tải tin nhắn', 'error')
                        console.error('Error fetching messages:', error);
                        throw error;
                    } finally {
                        loadingMessages.value = false;
                        // Đặt cờ loading thêm tin nhắn về false sau một khoảng thời gian
                        if (isLoadingMore) {
                            setTimeout(() => {
                                isLoadingOlderMessages.value = false;
                            }, 200);
                        }
                    }
                };

                // Send a new message
                const sendMessage = async () => {
                    if (!newMessage.value.trim() || !activeConversation.value) return;

                    // Store the message content before clearing the input
                    const messageContent = newMessage.value.trim();

                    // Clear input field immediately for better UX
                    newMessage.value = '';

                    // Focus back on input
                    messageInput.value.focus();

                    try {
                        // Create a temporary message object to show instantly in UI
                        const tempMessage = {
                            id: 'temp-' + Date.now(), // Temporary ID
                            conversation_id: activeConversation.value.id,
                            body: messageContent,
                            created_at: Math.floor(Date.now() / 1000), // Current timestamp in seconds
                            sender_id: currentUser.value.id,
                            sender_type: 'App\\Models\\User',
                            is_mine: true,
                            sender: currentUser.value,
                            // Add a flag to mark this as a local message that hasn't been confirmed by server
                            is_local: true
                        };

                        // Add to messages immediately
                        messages.value.unshift(tempMessage);

                        // Scroll to bottom to show new message
                        nextTick(() => {
                            scrollToBottom();
                        });

                        // Send to server
                        await axios.post('/api/admin/conversation/send-message', {
                            conversation_id: activeConversation.value.id,
                            body: messageContent
                        });

                        // Also update the conversation in the sidebar
                        updateConversationInList(activeConversation.value.id, tempMessage);

                    } catch (error) {
                        window.showToast('Đã có lỗi xảy ra khi gửi tin nhắn', 'error');
                        console.error('Error sending message:', error);

                        // Optionally: Remove the temporary message on error
                        // messages.value = messages.value.filter(msg => msg.id !== tempId);
                    }
                };

                // Set active conversation and fetch messages
                const setActiveConversation = (conversation) => {
                    // Remove any existing scroll event handlers to prevent issues
                    if (messagesContainer.value) {
                        $(messagesContainer.value).off('scroll', handleScroll);
                    }

                    // Reset scroll state for new conversation
                    initialScrollComplete.value = false;
                    isLoadingOlderMessages.value = false;  // Reset trạng thái tải
                    shouldAutoscroll.value = true;

                    activeConversation.value = conversation;
                    currentPage.value = 1;
                    hasMoreMessages.value = true;

                    // Fetch messages for this conversation
                    fetchMessages(conversation.id);

                    // Update unread count for this conversation in the sidebar
                    const index = conversations.value.findIndex(c => c.id === conversation.id);
                    if (index !== -1 && conversations.value[index].unread_count > 0) {
                        conversations.value[index].unread_count = 0;
                    }

                    // Initialize socket channel for this conversation
                    setupSocketChannel(conversation.id);

                    // Animate scroll to show conversation is active
                    $(".conversation-item.active").animate({
                        backgroundColor: "#e6f2ff"
                    }, 200);
                };

                // Socket/Pusher setup
                const setupSocket = () => {
                    // Define WebSocket host and port variables
                    const wsHost = '{{ env("APP_ENV") === "production" ? env("WS_HOST") : "" }}' || window.location.hostname;
                    const wsPort = {{ env("APP_ENV") === "production" ? env("WS_PORT") : env("PUSHER_PORT") }};

                    pusher.value = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
                        wsHost: wsHost,
                        wsPort: wsPort,
                        wssPort: wsPort,
                        forceTLS: {{ env("APP_ENV") == "production" ? "true" : "false" }},
                        encrypted: {{ env("APP_ENV") == "production" ? "true" : "false" }},
                        disableStats: true,
                        enabledTransports: ['ws', 'wss'],
                        authEndpoint: '/api/admin/broadcasting/auth',
                        auth: {
                            headers: {
                                'Authorization': `Bearer ${accessToken.value}`,
                                'Accept': 'application/json'
                            }
                        }
                    });

                    // Listen for connection events
                    pusher.value.connection.bind('connected', () => {
                        console.log('Connected to WebSocket server');
                    });

                    pusher.value.connection.bind('error', (err) => {
                        console.error('WebSocket connection error:', err);
                    });

                    // Đăng ký kênh thông báo admin
                    const adminChannel = pusher.value.subscribe('presence-admin-notifications');

                    // Lắng nghe tin nhắn mới từ kênh admin
                    adminChannel.bind('new.message', (data) => {
                        if (!data || !data.message) return;

                        const newMsg = data.message;
                        const conversationId = newMsg.conversation_id;

                        // Thêm thông tin is_mine cho tin nhắn để đảm bảo tính nhất quán với kênh conversation
                        newMsg.is_mine = newMsg.sender_id === currentUser.value.id && newMsg.sender_type === `App\\Models\\User`;

                        // Đảm bảo newMsg có thông tin sender giống như kênh conversation
                        if (data.sender) {
                            newMsg.sender = data.sender;
                        }

                        // Nếu admin không ở trong box chat của tin nhắn này hoặc đang ở box chat khác
                        if (!activeConversation.value || activeConversation.value.id !== conversationId) {
                            // Tìm conversation trong danh sách hiện có
                            const existingConversation = conversations.value.find(c => c.id === conversationId);

                            if (existingConversation) {
                                // Nếu đã có conversation này, cập nhật
                                updateConversationInList(conversationId, newMsg);
                            } else {
                                // Nếu chưa có conversation này (cuộc trò chuyện mới), tải lại toàn bộ danh sách
                                fetchConversations();
                            }
                            window.showToast('Bạn có tin nhắn mới từ cổ đông ' + data.sender.name, 'info');
                        } else {
                            // Admin đang ở trong box chat này, vẫn cập nhật conversation trong sidebar
                            // để hiển thị đúng tin nhắn mới nhất, nhưng không tăng unread_count

                            // Cập nhật last_message trong sidebar nhưng không tăng unread_count
                            const index = conversations.value.findIndex(c => c.id === conversationId);
                            if (index !== -1) {
                                const updatedConversation = { ...conversations.value[index] };
                                updatedConversation.last_message = {
                                    body: newMsg.body,
                                    created_at: newMsg.created_at,
                                    sender_id: newMsg.sender_id,
                                    sender_type: newMsg.sender_type,
                                    is_mine: newMsg.is_mine
                                };

                                // Remove and re-add to place at top
                                conversations.value.splice(index, 1);
                                conversations.value.unshift(updatedConversation);
                            }
                        }
                    });
                };

                const setupSocketChannel = (conversationId) => {
                    // Unsubscribe from any existing channel
                    if (channel.value) {
                        pusher.value.unsubscribe(channel.value.name);
                    }

                    // Subscribe to the conversation's presence channel
                    const channelName = `presence-conversation.${conversationId}`;
                    channel.value = pusher.value.subscribe(channelName);

                    // Listen for new messages
                    channel.value.bind('new.message', (data) => {
                        if (data && data.message && activeConversation.value && activeConversation.value.id === conversationId) {
                            // Add the new message to our messages list
                            const newMsg = data.message;
                            newMsg.is_mine = newMsg.sender_id === currentUser.value.id && newMsg.sender_type === `App\\Models\\User`;
                            newMsg.sender = data.sender;

                            // Check if this is our own message that we already added locally
                            if (newMsg.is_mine) {
                                // Replace the temporary message with the confirmed one from server
                                const tempIndex = messages.value.findIndex(msg =>
                                    msg.is_local &&
                                    msg.body === newMsg.body &&
                                    msg.sender_id === newMsg.sender_id
                                );

                                if (tempIndex !== -1) {
                                    // If found, replace the temporary message with the real one
                                    messages.value.splice(tempIndex, 1, newMsg);
                                    return; // Don't add a duplicate
                                }
                            }

                            // Add the message to our list (only for messages from other users)
                            messages.value.unshift(newMsg);

                            // Scroll to bottom to show the new message
                            nextTick(() => {
                                // Đặt timeout để đảm bảo DOM đã được cập nhật hoàn toàn
                                setTimeout(() => {
                                    scrollToBottom();

                                    // Highlight new message with jQuery
                                    $(".message:last-child .message-bubble").addClass("highlight-new");
                                    setTimeout(() => {
                                        $(".message:last-child .message-bubble").removeClass("highlight-new");
                                    }, 1000);
                                }, 100);
                            });
                        }

                        // Update the conversation in the sidebar
                        updateConversationInList(conversationId, data.message);
                    });
                };

                // Update a conversation in the sidebar list with new message data
                const updateConversationInList = (conversationId, message) => {
                    const index = conversations.value.findIndex(c => c.id === conversationId);
                    if (index !== -1) {
                        // Create a copy of the conversation
                        const updatedConversation = { ...conversations.value[index] };

                        // Update last message
                        updatedConversation.last_message = {
                            body: message.body,
                            created_at: message.created_at,
                            sender_id: message.sender_id,
                            sender_type: message.sender_type,
                            is_mine: message.sender_id === currentUser.value.id && message.sender_type === `App\\Models\\User`
                        };

                        // If the message is not from the current user and this is not the active conversation,
                        // increment the unread count
                        if (!updatedConversation.last_message.is_mine &&
                            (!activeConversation.value || activeConversation.value.id !== conversationId)) {
                            updatedConversation.unread_count = (updatedConversation.unread_count || 0) + 1;
                        }

                        // Remove the conversation from the array
                        conversations.value.splice(index, 1);

                        // Add it back at the beginning (newest first)
                        conversations.value.unshift(updatedConversation);
                    }
                };

                // Search for customers to create new conversations
                const searchCustomers = async () => {
                    if (!customerSearchQuery.value.trim()) {
                        customers.value = [];
                        return;
                    }

                    try {
                        loadingCustomers.value = true;
                        const response = await axios.get(`/api/admin/conversation/get-customers`, {
                            params: {
                                search: customerSearchQuery.value
                            }
                        });

                        const data = response.data;
                        if (data.success && data.data) {
                            customers.value = data.data;
                        }
                    } catch (error) {
                        console.error('Error searching customers:', error);

                    } finally {
                        loadingCustomers.value = false;
                    }
                };

                // Create a new conversation with a customer
                const createConversation = async (customer) => {
                    try {
                        const response = await axios.post('/api/admin/conversation/create', {
                            customer_id: customer.id
                        });

                        const data = response.data;
                        if (data.success && data.data && data.data.conversation_id) {
                            // Close the modal
                            showCustomersList.value = false;

                            // Refresh conversations list
                            await fetchConversations();

                            // Find the new conversation and set it as active
                            const newConversation = conversations.value.find(c => c.id === data.data.conversation_id);
                            if (newConversation) {
                                setActiveConversation(newConversation);
                            }
                        }
                    } catch (error) {
                        console.error('Error creating conversation:', error);

                    }
                };

                // Utility function to scroll to bottom of messages
                const scrollToBottom = () => {
                    if (messagesContainer.value) {
                        // Disable scroll event temporarily while scrolling to bottom
                        $(messagesContainer.value).off('scroll', handleScroll);

                        // Thêm một chút trì hoãn để đảm bảo DOM đã được cập nhật
                        setTimeout(() => {
                            $(messagesContainer.value).animate({
                                scrollTop: $(messagesContainer.value)[0].scrollHeight
                            }, 300, function() {
                                // Callback khi animation hoàn thành - đánh dấu đã cuộn ban đầu
                                initialScrollComplete.value = true;

                                // Re-enable scroll event after animation completes
                                setTimeout(() => {
                                    $(messagesContainer.value).on('scroll', handleScroll);
                                }, 500);
                            });
                        }, 100);
                    }
                };

                // Load more messages when scrolling up
                const handleScroll = (e) => {
                    // Chỉ xử lý sự kiện cuộn sau khi đã hoàn thành cuộn lần đầu
                    if (!initialScrollComplete.value) return;

                    // Nếu đang tải tin nhắn cũ hoặc không có thêm tin nhắn hoặc đã đạt giới hạn, không làm gì cả
                    if (isLoadingOlderMessages.value || loadingMessages.value || !hasMoreMessages.value || !messagesContainer.value) return;

                    const container = $(messagesContainer.value);
                    const scrollTop = container.scrollTop();
                    const scrollHeight = container[0].scrollHeight;
                    const clientHeight = container[0].clientHeight;

                    // Kiểm tra xem người dùng đã cuộn gần đến đầu chưa
                    if (scrollTop < 100) {
                        // Tránh kích hoạt nhiều lần
                        $(messagesContainer.value).off('scroll', handleScroll);

                        // Đặt cờ để tránh gọi nhiều lần
                        isLoadingOlderMessages.value = true;

                        // Tăng trang và gọi API
                        currentPage.value++;

                        fetchMessages(activeConversation.value.id, currentPage.value)
                            .finally(() => {
                                // Đảm bảo đặt lại cờ sau khi hoàn thành, bất kể thành công hay thất bại
                                setTimeout(() => {
                                    isLoadingOlderMessages.value = false;
                                    // Gắn lại sự kiện cuộn sau khoảng thời gian
                                    $(messagesContainer.value).on('scroll', handleScroll);
                                }, 500); // Thêm độ trễ nhỏ để tránh gọi liên tục
                            });
                    }
                };

                // Format dates and times
                const formatTime = (timestamp) => {
                    if (!timestamp) return '';
                    const date = new Date(timestamp * 1000);
                    const now = new Date();

                    // If today, show time only
                    if (date.toDateString() === now.toDateString()) {
                        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    }

                    // If this week, show day name
                    const diffDays = Math.floor((now - date) / (1000 * 60 * 60 * 24));
                    if (diffDays < 7) {
                        return date.toLocaleDateString([], { weekday: 'short' });
                    }

                    // Otherwise show date
                    return date.toLocaleDateString([], { day: '2-digit', month: '2-digit' });
                };

                const formatMessageTime = (timestamp) => {
                    if (!timestamp) return '';
                    const date = new Date(timestamp * 1000);
                    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                };

                const formatFullDate = (timestamp) => {
                    if (!timestamp) return '';
                    const date = new Date(timestamp * 1000);
                    return date.toLocaleDateString([], { day: '2-digit', month: '2-digit', year: 'numeric' });
                };

                // Get initials from a name for avatar
                const getInitials = (name) => {
                    if (!name) return 'N/A';
                    return name
                        .split(' ')
                        .map(word => word[0])
                        .slice(0, 2)
                        .join('')
                        .toUpperCase();
                };

                // Truncate text to max length
                const truncateText = (text, maxLength) => {
                    if (!text) return '';
                    if (text.length <= maxLength) return text;
                    return text.substring(0, maxLength) + '...';
                };

                // Group messages by date and by sender
                const groupedMessages = computed(() => {
                    if (!messages.value.length) return [];

                    const result = [];
                    let lastDate = null;

                    // Iterate in reverse order (newest to oldest) since our API returns newest first
                    const sortedMessages = [...messages.value].reverse();

                    sortedMessages.forEach((message, index) => {
                        const messageDate = new Date(message.created_at * 1000).toDateString();

                        // Add date divider if date changes
                        if (messageDate !== lastDate) {
                            result.push({
                                isDateDivider: true,
                                date: formatFullDate(message.created_at)
                            });
                            lastDate = messageDate;
                        }

                        // Add the message
                        result.push(message);
                    });

                    return result;
                });

                // Filtered conversations based on search query
                const filteredConversations = computed(() => {
                    if (!searchQuery.value.trim()) return conversations.value;

                    const query = searchQuery.value.toLowerCase();
                    return conversations.value.filter(conv => {
                        const title = conv.title || (conv.other_participant ? conv.other_participant.name : '');
                        const lastMsg = conv.last_message ? conv.last_message.body : '';

                        return title.toLowerCase().includes(query) ||
                               lastMsg.toLowerCase().includes(query);
                    });
                });

                // Determine if avatar should be shown for a message
                const shouldShowAvatar = (message, index) => {
                    if (index === 0) return true;

                    const prevMessage = groupedMessages.value[index - 1];
                    if (prevMessage.isDateDivider) return true;

                    // If previous message is from a different sender, show avatar
                    return prevMessage.sender_id !== message.sender_id ||
                           prevMessage.sender_type !== message.sender_type;
                };

                // Ref callback để đảm bảo cuộn xuống khi messagesEnd được thêm vào DOM
                const setMessagesEnd = (el) => {
                    messagesEnd.value = el;
                    if (el && !loadingMessages.value) {
                        scrollToBottom();
                    }
                };

                // Hàm kiểm tra xem có nên tự động cuộn xuống dưới không
                const shouldScrollToBottom = () => {
                    // Không tự động cuộn khi đang tải tin nhắn cũ
                    if (isLoadingOlderMessages.value) {
                        return false;
                    }

                    // Tự động cuộn khi là lần đầu tải tin nhắn
                    if (loadingMessages.value && currentPage.value === 1) {
                        return true;
                    }

                    // Tự động cuộn khi có tin nhắn mới và người dùng đang ở gần cuối
                    if (messagesContainer.value) {
                        const { scrollTop, scrollHeight, clientHeight } = messagesContainer.value;
                        // Nếu người dùng đang ở gần cuối (khoảng cách đến cuối < 100px)
                        return scrollHeight - scrollTop - clientHeight < 100;
                    }

                    return false;
                };

                // Lifecycle hooks
                onMounted(async () => {
                    if (!accessToken.value) {
                        window.showToast('Bạn cần đăng nhập để sử dụng tính năng chat', 'error');
                        return;
                    }
                    // Initialize socket connection
                    setupSocket();

                    // Fetch initial data
                    await fetchConversations();
                });

                // Thêm watcher này để đảm bảo handleScroll được gắn lại khi messages container được tạo
                watch(messagesContainer, (newVal) => {
                    if (newVal) {
                        // Xóa event listener cũ nếu có
                        $(newVal).off('scroll', handleScroll);

                        // Thêm event listener mới
                        $(newVal).on('scroll', handleScroll);
                    }
                }, { immediate: true });

                // Watchers
                watch(showCustomersList, (isVisible) => {
                    if (isVisible) {
                        customerSearchQuery.value = '';
                        customers.value = [];
                    }
                });

                // Tự động cuộn xuống khi có tin nhắn mới, nhưng không khi đang tải thêm tin nhắn cũ
                watch(messages, (newVal, oldVal) => {
                    if (shouldAutoscroll.value && newVal.length > oldVal.length) {
                        nextTick(() => {
                            scrollToBottom();
                        });
                    }
                }, { deep: true });

                return {
                    conversations,
                    activeConversation,
                    messages,
                    newMessage,
                    searchQuery,
                    loading,
                    loadingMessages,
                    messagesContainer,
                    messagesEnd,
                    messageInput,
                    showCustomersList,
                    customers,
                    customerSearchQuery,
                    loadingCustomers,
                    setActiveConversation,
                    sendMessage,
                    searchCustomers,
                    createConversation,
                    formatTime,
                    formatMessageTime,
                    getInitials,
                    truncateText,
                    filteredConversations,
                    groupedMessages,
                    shouldShowAvatar,
                    setMessagesEnd,
                    currentPage,
                    hasMoreMessages,
                    isLoadingOlderMessages,
                };
            }
        }).mount('#chat-app');
    </script>
@endpush
@section('content')
<div id="chat-app">
    <div class="chat-container">
        <!-- Sidebar với danh sách cuộc trò chuyện -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3>Cuộc trò chuyện</h3>
                <div class="search-box">
                    <input type="text" placeholder="Tìm kiếm..." v-model="searchQuery">
                </div>
            </div>
            <div class="conversation-list">
                <div v-if="loading" class="loading-indicator">
                    <div class="spinner"></div>
                    <p>Đang tải...</p>
                </div>
                <div v-else-if="conversations.length === 0" class="empty-state">
                    <p>Không có cuộc trò chuyện nào</p>
                </div>
                <div
                    v-for="conversation in filteredConversations"
                    :key="conversation.id"
                    class="conversation-item"
                    :class="{'active': activeConversation && activeConversation.id === conversation.id}"
                    @click="setActiveConversation(conversation)"
                >
                    <div class="avatar">
                        <span class="avatar-text">@{{ getInitials(conversation.title || (conversation.other_participant ? conversation.other_participant.name : 'N/A')) }}</span>
                        <span v-if="conversation.unread_count > 0" class="badge">@{{ conversation.unread_count }}</span>
                    </div>
                    <div class="conversation-info">
                        <div class="title-row">
                            <h4>@{{ conversation.title || (conversation.other_participant ? conversation.other_participant.name : 'N/A') }}</h4>
                            <span class="time">@{{ formatTime(conversation.last_message ? conversation.last_message.created_at : conversation.created_at) }}</span>
                        </div>
                        <div class="last-message" v-if="conversation.last_message">
                            <span v-if="conversation.last_message.is_mine">Bạn: </span>
                            @{{ truncateText(conversation.last_message.body, 30) }}
                        </div>
                        <div class="last-message" v-else>
                            <i>Chưa có tin nhắn</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sidebar-footer">
                <!-- <button class="new-conversation-btn" @click="showCustomersList = true">
                    <i class="fas fa-plus"></i> Cuộc trò chuyện mới
                </button> -->
            </div>
        </div>

        <!-- Khu vực chat chính -->
        <div class="chat-area" :class="{'empty': !activeConversation}">
            <div v-if="!activeConversation" class="empty-chat">
                <div class="empty-chat-content">
                    <i class="fas fa-comments empty-chat-icon"></i>
                    <h3>Chọn một cuộc trò chuyện để bắt đầu</h3>
                </div>
            </div>
            <template v-else>
                <div class="chat-header">
                    <div class="chat-header-user">
                        <div class="avatar">
                            <span class="avatar-text">@{{ getInitials(activeConversation.title || (activeConversation.other_participant ? activeConversation.other_participant.name : 'N/A')) }}</span>
                        </div>
                        <div class="user-info">
                            <h4>@{{ activeConversation.title || (activeConversation.other_participant ? activeConversation.other_participant.name : 'N/A') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="messages-container"
                     ref="messagesContainer"
                     :class="{'loading-more': loadingMessages && currentPage > 1}">
                    <div v-if="loadingMessages" class="loading-indicator centered">
                        <div class="spinner"></div>
                        <p>Đang tải tin nhắn...</p>
                    </div>
                    <div v-else-if="messages.length === 0" class="empty-messages">
                        <p>Chưa có tin nhắn nào. Hãy bắt đầu cuộc trò chuyện!</p>
                    </div>
                    <div v-else class="messages-list">
                        <div v-for="(message, index) in groupedMessages" :key="index" class="message-group">
                            <div v-if="message.isDateDivider" class="date-divider">
                                <span>@{{ message.date }}</span>
                            </div>
                            <template v-else>
                                <div class="message" :class="{'mine': message.is_mine, 'other': !message.is_mine}">
                                    <!-- Luôn hiển thị avatar cho tin nhắn khác -->
                                    <div v-if="!message.is_mine" class="avatar small">
                                        <span class="avatar-text">@{{ getInitials(message.sender && message.sender.name ? message.sender.name : 'N/A') }}</span>
                                    </div>
                                    <!-- Khoảng cách cho tin nhắn của mình -->
                                    <div v-if="message.is_mine" class="avatar-spacer"></div>
                                    <div class="message-content">
                                        <div class="message-bubble">
                                            @{{ message.body }}
                                        </div>
                                        <div class="message-info">
                                            <span class="message-time">@{{ formatMessageTime(message.created_at) }}</span>
                                            <span v-if="message.is_mine && message.read_by && message.read_by.length > 0" class="read-status">
                                                <i class="fas fa-check-double"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div ref="messagesEnd" :ref="setMessagesEnd"></div>
                    </div>
                </div>
                <div class="chat-input">
                    <div class="input-container">
                        <textarea
                            v-model="newMessage"
                            @keydown.enter.prevent="sendMessage"
                            placeholder="Nhập tin nhắn..."
                            ref="messageInput"
                        ></textarea>
                        <button class="send-button" @click="sendMessage" :disabled="!newMessage.trim()">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Modal tạo cuộc trò chuyện mới -->
    <div class="modal" v-if="showCustomersList">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tạo cuộc trò chuyện mới</h3>
                <button class="close-button" @click="showCustomersList = false">&times;</button>
            </div>
            <div class="modal-body">
                <div class="search-box modal-search">
                    <input type="text" placeholder="Tìm kiếm khách hàng..." v-model="customerSearchQuery" @input="searchCustomers">
                </div>
                <div class="customers-list">
                    <div v-if="loadingCustomers" class="loading-indicator">
                        <div class="spinner"></div>
                        <p>Đang tải danh sách khách hàng...</p>
                    </div>
                    <div v-else-if="customers.length === 0" class="empty-state">
                        <p>Không tìm thấy khách hàng nào</p>
                    </div>
                    <div
                        v-for="customer in customers"
                        :key="customer.id"
                        class="customer-item"
                        @click="createConversation(customer)"
                    >
                        <div class="avatar">
                            <span class="avatar-text">@{{ getInitials(customer.name) }}</span>
                        </div>
                        <div class="customer-info">
                            <h4>@{{ customer.name }}</h4>
                            <p>@{{ customer.email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
