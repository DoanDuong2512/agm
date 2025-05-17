import { createRouter, createWebHistory } from 'vue-router'
import Login from '../pages/Login.vue';
import Home from '../pages/Home.vue';
import Documents from '../pages/Document.vue';
import NotFound from '../pages/NotFound.vue';
import CloseSystem from '../pages/CloseSystem.vue';
import Livestream from '../pages/Livestream.vue';
import Authority from '../pages/Authority.vue';
import PrintPage from '../pages/PrintPage.vue';
import { useAuthStore } from '../store/auth';
import Layout from '@/layouts/Layout.vue';

const routes = [
    { 
        path: '/login',
        name: 'Login',
        component: Login 
    },
    { 
        path: '/close',
        name: 'CloseSystem',
        component: CloseSystem 
    },
    { 
      path: '/', 
      component: Layout,  // Dùng Layout làm component cha
      meta: { requiresAuth: true },
      children: [
          {
              path: '',
              name: 'Home',
              component: Home
          },
          {
            path: 'tai-lieu-dai-hoi',
            name: 'documents',
            component: Documents
          },
          {
            path: 'livestream',
            name: 'livestream',
            component: Livestream
          },
          {
            path: 'authority',
            name: 'authority',
            component: Authority
          },
      ]
  },
    { 
      path: '/:pathMatch(.*)*',
      name: 'NotFound',
      component: NotFound
    },
    {
      path: '/print-confirmation',
      name: 'PrintConfirmation',
      component: PrintPage,
      meta: { requiresAuth: true }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Kiểm tra token trước khi truy cập
router.beforeEach((to, from, next) => {
    // Kiểm tra nếu môi trường là production
    const appStatus = import.meta.env.VITE_APP_STATUS || 'open';
    // Nếu trạng thái ứng dụng là bảo trì và không phải là trang đóng hệ thống
    if (appStatus === 'maintenance' && to.path !== '/close') {
        return next('/close');
    }
    const authStore = useAuthStore();
    if (to.meta.requiresAuth && !authStore.token) {
      return next('/login');
    }
    if (to.path === '/login' && authStore.token) {
      return next('/');
    }
    next();
});
  
export default router;