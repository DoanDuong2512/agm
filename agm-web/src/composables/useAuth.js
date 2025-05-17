import { useAuthStore } from '../store/auth';

export const useAuth = () => {
  const authStore = useAuthStore();

  const isLoggedIn = () => !!authStore.token;

  return { authStore, isLoggedIn };
};
