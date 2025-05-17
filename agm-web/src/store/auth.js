import { defineStore } from 'pinia';
import { getCurrentUserInfo } from '../api/auth';
import { saveToken, clearToken, saveUser, removeUser, getUser, getToken } from '../utils/auth';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: getUser(),
    token: getToken(),
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    currentUser: (state) => state.user,
    userToken: (state) => state.token
  },

  actions: {
    setUserAndToken(token, user) {
      this.token = token;
      this.user = user;
      saveToken(token);
      saveUser(user);
    },
  
    logout() {
      this.user = null;
      this.token = null;
      clearToken();
      removeUser();
    },
    async getCurrentUser() {
      try {
        const response = await getCurrentUserInfo();
        const responseData = response.data;
        const user = responseData.data;
        this.user = user;
        saveUser(this.user);
      } catch (error) {
        console.error('Get current user error:', error.message || 'Failed to get current user'); // Log lỗi
        throw new Error(error.message || 'Failed to get current user');
      }
    },

    async handleAuthSuccess(response) {
      const { access_token, customer } = response.data.data;
      this.setUserAndToken(access_token, customer);
    }
  },
});
