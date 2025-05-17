<template>
  <div class="bg-[#0A2476] text-white">
    <div class="container mx-auto">
      <div class="navbar h-16">
        <div class="navbar-start">
          <div class="dropdown lg:hidden" ref="mobileDropdownRef">
            <label tabindex="0" class="btn btn-ghost btn-circle" @click="toggleMobileMenu">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
              </svg>
            </label>
            <ul tabindex="0" class="menu menu-compact dropdown-content mt-3 p-2 shadow bg-[#0A2476] rounded-box w-52 z-20" 
                :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" @click.stop>
              <li><router-link to="/" class="text-white font-medium hover:bg-white hover:text-[#0A2476] transition-colors duration-200" @click="closeMobileMenu">THÔNG TIN CỔ ĐÔNG</router-link></li>
              <!-- <li>
                <router-link to="/" class="text-white font-medium justify-between hover:bg-white hover:text-[#0A2476] transition-colors duration-200" @click="closeMobileMenu">
                  ĐẠI HỘI
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </router-link>
                <ul class="p-2 bg-[#0A2476]">
                  <li><router-link to="/tai-lieu-dai-hoi" class="text-white font-medium hover:bg-white hover:text-[#0A2476] transition-colors duration-200" @click="closeMobileMenu">Tài liệu đại hội</router-link></li>
                  <li><router-link to="/" class="text-white font-medium hover:bg-white hover:text-[#0A2476] transition-colors duration-200" @click="closeMobileMenu">Thông tin đại hội</router-link></li>
                  <li><router-link to="/livestream" class="text-white font-medium hover:bg-white hover:text-[#0A2476] transition-colors duration-200" @click="closeMobileMenu">Livestream đại hội</router-link></li>
                </ul>
              </li> -->
              <li><router-link to="/livestream" class="nav-link text-white font-medium px-4">LIVESTREAM ĐẠI HỘI</router-link></li>
              <li><router-link to="/authority" class="nav-link text-white font-medium px-4">ỦY QUYỀN</router-link></li>
              <li><router-link to="/livestream" class="nav-link text-white font-medium px-4">BIỂU QUYẾT - BẦU CỬ</router-link></li>
              <li><router-link to="/" class="nav-link text-white font-medium px-4" @click.prevent="openUserGuide">HƯỚNG DẪN SỬ DỤNG</router-link></li>
            </ul>
          </div>
          <router-link to="/" class="flex items-center px-4">
            <img src="@/assets/images/logo_elcom.png" alt="Elcom" class="h-8" />
          </router-link>
        </div>
        
        <div class="navbar-center hidden lg:flex">
          <ul class="menu menu-horizontal gap-2">
            <li><router-link to="/" class="nav-link text-white font-medium px-4">THÔNG TIN CỔ ĐÔNG</router-link></li>
            <!-- <li class="dropdown dropdown-hover" ref="daiHoiDropdown">
              <router-link to="/" tabindex="0" class="nav-link text-white font-medium px-4 flex items-center gap-1">
                ĐẠI HỘI
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </router-link>
              <ul tabindex="0" class="dropdown-content z-[10] menu p-2 shadow bg-[#0A2476] w-48" @click="closeAllDropdowns">
                <li><router-link to="/tai-lieu-dai-hoi" class="text-white hover:bg-white hover:text-[#0A2476] transition-colors duration-200">Tài liệu đại hội</router-link></li>
                <li><router-link to="/" class="text-white hover:bg-white hover:text-[#0A2476] transition-colors duration-200">Thông tin đại hội</router-link></li>
                <li><router-link to="/livestream" class="text-white font-medium hover:bg-white hover:text-[#0A2476] transition-colors duration-200">Livestream đại hội</router-link></li>
              </ul>
            </li> -->
            <li><router-link to="/livestream" class="nav-link text-white font-medium px-4">LIVESTREAM ĐẠI HỘI</router-link></li>
            <li><router-link to="/authority" class="nav-link text-white font-medium px-4">ỦY QUYỀN</router-link></li>
            <li><router-link to="/livestream" class="nav-link text-white font-medium px-4">BIỂU QUYẾT - BẦU CỬ</router-link></li>
            <li><router-link to="/" class="nav-link text-white font-medium px-4" @click.prevent="openUserGuide">HƯỚNG DẪN SỬ DỤNG</router-link></li>
          </ul>
        </div>
            
        <div class="navbar-end">
          <div class="dropdown dropdown-end dropdown-hover" ref="userDropdown">
            <label tabindex="0" class="flex items-center gap-2 px-4 cursor-pointer hover:opacity-90 transition-opacity duration-200">
              <div class="avatar">
                <div class="w-8 h-8 rounded-full">
                  <img src="@/assets/images/avatar_chat_user_default.png" alt="Avatar" />
                </div>
              </div>
              <span class="text-white hidden sm:inline max-w-[120px] truncate">{{ authStore.user?.name || 'User' }}</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </label>
            <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-[#0A2476] rounded-box w-40 mt-0 z-20" @click="closeAllDropdowns">
              <li><a class="text-white hover:bg-white hover:text-[#0A2476] transition-colors duration-200" @click="logout">Đăng xuất</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from '../store/auth';
import router from '../router';
import { ref, onMounted, onUnmounted } from 'vue';
import { getMeetingConfig } from '@/api/home';
import { showToast } from '@/utils/toast';

export default {
  name: 'Navbar',
  setup() {
    const authStore = useAuthStore();
    const daiHoiDropdown = ref(null);
    const userDropdown = ref(null);
    const mobileMenuOpen = ref(false);
    const mobileDropdownRef = ref(null);
    const isLoading = ref(false);
    const authorizationFormUrl = ref('');
    const userGuideUrl = ref('');

    const logout = () => {
      authStore.logout();
      router.push('/login');
    };

    // Fetch authorization form URL
    const fetchAuthorizationFormUrl = async () => {
      try {
        isLoading.value = true;
        const response = await getMeetingConfig('giay-uy-quyen-view-url');
        
        if (response.data && response.data.data && response.data.data.value) {
          authorizationFormUrl.value = response.data.data.value;
        }
      } catch (error) {
        console.error('Failed to get authorization form URL:', error);
      } finally {
        isLoading.value = false;
      }
    };

    // Fetch user guide URL
    const fetchUserGuideUrl = async () => {
      try {
        const response = await getMeetingConfig('hdsd-view-url');
        
        if (response.data && response.data.data && response.data.data.value) {
          userGuideUrl.value = response.data.data.value;
        }
      } catch (error) {
        console.error('Failed to get user guide URL:', error);
      }
    };

    // Open the authorization form in a new tab
    const openAuthorizationForm = async (event) => {
      event.preventDefault();
      
      if (authorizationFormUrl.value) {
        window.open(authorizationFormUrl.value, '_blank');
      } else {
        // Try fetching the URL again if it's not available
        try {
          isLoading.value = true;
          const response = await getMeetingConfig('giay-uy-quyen-view-url');
          
          if (response.data && response.data.data && response.data.data.value) {
            const url = response.data.data.value;
            authorizationFormUrl.value = url;
            window.open(url, '_blank');
          } else {
            showToast('error', 'Đã có lỗi xảy ra');
          }
        } catch (error) {
          console.error('Failed to get authorization form URL:', error);
          showToast('error', 'Đã có lỗi xảy ra');
        } finally {
          isLoading.value = false;
        }
      }
    };

    // Open the user guide in a new tab
    const openUserGuide = async (event) => {
      event.preventDefault();
      
      if (userGuideUrl.value) {
        window.open(userGuideUrl.value, '_blank');
      } else {
        // Try fetching the URL again if it's not available
        try {
          isLoading.value = true;
          const response = await getMeetingConfig('hdsd-view-url');
          
          if (response.data && response.data.data && response.data.data.value) {
            const url = response.data.data.value;
            userGuideUrl.value = url;
            window.open(url, '_blank');
          } else {
            showToast('error', 'Đã có lỗi xảy ra');
          }
        } catch (error) {
          console.error('Failed to get user guide URL:', error);
          showToast('error', 'Đã có lỗi xảy ra');
        } finally {
          isLoading.value = false;
        }
      }
    };

    const closeAllDropdowns = () => {
      // Manually force close dropdowns by removing hover classes or blurring elements
      document.querySelectorAll('.dropdown-content').forEach(el => {
        el.style.display = 'none';
        setTimeout(() => {
          el.style.display = '';
        }, 10);
      });
      
      // Close mobile menu
      mobileMenuOpen.value = false;
    };
    
    const toggleMobileMenu = (event) => {
      // Prevent event from propagating to document
      event.stopPropagation();
      mobileMenuOpen.value = !mobileMenuOpen.value;
    };
    
    const closeMobileMenu = () => {
      mobileMenuOpen.value = false;
    };

    // Handle click outside mobile dropdown
    const handleClickOutside = (event) => {
      if (mobileDropdownRef.value && !mobileDropdownRef.value.contains(event.target) && mobileMenuOpen.value) {
        mobileMenuOpen.value = false;
      }
    };

    // Listen to route changes to close dropdowns
    const handleRouteChange = () => {
      closeAllDropdowns();
    };

    onMounted(() => {
      // Fetch the URLs as soon as the component is mounted
      fetchAuthorizationFormUrl();
      fetchUserGuideUrl();
      
      // Store the afterEach hook removal function
      const removeRouteHook = router.afterEach(handleRouteChange);
      
      // Add click outside event listener for mobile Safari
      document.addEventListener('touchend', handleClickOutside);
      document.addEventListener('click', handleClickOutside);
      
      // Clean up on unmount
      onUnmounted(() => {
        // Remove the router hook
        if (typeof removeRouteHook === 'function') {
          removeRouteHook();
        }
        
        // Clean up event listeners
        document.removeEventListener('touchend', handleClickOutside);
        document.removeEventListener('click', handleClickOutside);
      });
    });

    return { 
      authStore, 
      logout, 
      daiHoiDropdown, 
      userDropdown, 
      closeAllDropdowns,
      mobileMenuOpen,
      toggleMobileMenu,
      closeMobileMenu,
      mobileDropdownRef,
      openAuthorizationForm,
      openUserGuide
    };
  }
}
</script>

<style scoped>
/* Chỉnh sửa nav-link để loại bỏ border-bottom */
.nav-link {
  position: relative;
  transition: all 0.3s ease;
  border-radius: 0;
}

.nav-link:hover {
  background-color: transparent;
}

/* Đảm bảo dropdown sát với mép dưới của navbar */
.dropdown-content {
  border-top: none !important;
  visibility: hidden;
  opacity: 0;
  transform: translateY(0);
  transition: all 0.3s ease;
  position: absolute;
  top: 100%;
  left: 0;
  margin-top: 0 !important;
  padding-top: 0 !important;
  border-radius: 0 0 0.375rem 0.375rem !important;
}

/* Loại bỏ đường viền trên cùng của dropdown */
.dropdown-content:before {
  display: none;
}

/* Điều chỉnh menu items để không có khoảng cách với dropdown */
.menu-horizontal > li > a {
  height: 4rem;
  display: flex;
  align-items: center;
  padding-bottom: 0;
  border-bottom: none;
}

/* Chỉnh sửa riêng cho dropdown avatar */
.navbar-end .dropdown-content {
  left: auto;
  right: 0;
  top: 100%;
  margin-top: 0 !important;
  padding-top: 0 !important;
}

/* Đảm bảo dropdown tiếp tục hiển thị khi di chuyển chuột vào nó */
.dropdown-hover:hover .dropdown-content,
.dropdown-content:hover {
  visibility: visible;
  opacity: 1;
  display: block;
}

/* Đảm bảo menu mobile hiển thị khi được bật */
@media (max-width: 1023px) {
  .menu-compact.dropdown-content.block {
    visibility: visible !important;
    opacity: 1 !important;
    display: block !important;
    transform: translateY(0) !important;
  }
}

/* Loại bỏ khoảng cách giữa các items trong dropdown */
.dropdown-content li:first-child {
  margin-top: 0;
  padding-top: 0;
}

/* Làm cho dropdown liền mạch với navbar */
.dropdown-content {
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  border-top: none;
}

/* Nếu có padding trong dropdown menu, loại bỏ padding phía trên */
.dropdown-content.menu {
  padding-top: 0.5rem !important;
}

/* Hiệu ứng hover */
.dropdown:hover .dropdown-content {
  visibility: visible;
  opacity: 1;
  transform: translateY(0);
  display: block;
}

/* Đảm bảo dropdown tiếp tục hiển thị khi di chuyển chuột vào nó */
.dropdown-hover:hover .dropdown-content,
.dropdown-content:hover {
  visibility: visible;
  opacity: 1;
  display: block;
}

/* Đảm bảo menu mobile hiển thị khi được bật */
@media (max-width: 1023px) {
  .menu-compact.dropdown-content.block {
    visibility: visible !important;
    opacity: 1 !important;
    display: block !important;
    transform: translateY(0) !important;
  }
}
</style>
