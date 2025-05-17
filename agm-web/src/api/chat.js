import apiClient from './base';

// Get conversation with admin
export const getConversation = (adminEmail) => {
  return apiClient.get(`/conversation-admin`, { 
    params: { admin_email: adminEmail } 
  });
};

// Send a message in a conversation
export const sendMessage = (data) => {
  return apiClient.post('/conversation/send-message', {
    conversation_id: data.conversation_id,
    body: data.message
  });
};

// Get messages from a conversation
export const getMessages = (conversationId, page = 1, perPage = 10) => {
  return apiClient.get(`/conversation/get-messages/${conversationId}`, {
    params: { page: page, per_page: perPage }
  });
};

  
  
  