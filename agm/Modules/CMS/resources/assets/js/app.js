import '@tabler/core/dist/js/tabler.min.js';
import { showToast, initToastFromSession } from "./toast";

window.showToast = showToast;

document.addEventListener('DOMContentLoaded', function() {
    initToastFromSession();
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggler = document.getElementById('sidebar-toggler');
    const mobileToggler = document.getElementById('toggle-sidebar');

    // Kiểm tra localStorage cho trạng thái đã lưu (chỉ áp dụng cho màn hình lớn)
    if (window.innerWidth >= 992) {
        const savedState = localStorage.getItem('sidebar-collapsed');
        if (savedState === 'true') {
            sidebar.classList.add('collapsed');
        }
    }

    // Xử lý toggle từ nút trong navbar (desktop và mobile)
    if (sidebarToggler && sidebar) {
        sidebarToggler.addEventListener('click', function(e) {
            e.preventDefault();

            if (window.innerWidth >= 992) {
                // Desktop: thu gọn/mở rộng sidebar
                sidebar.classList.toggle('collapsed');
                localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
            } else {
                // Mobile: hiện/ẩn sidebar hoặc thu gọn/mở rộng nếu đã hiển thị
                if (sidebar.classList.contains('show')) {
                    sidebar.classList.toggle('collapsed');
                } else {
                    sidebar.classList.add('show');
                }
            }
        });
    }

    // Xử lý toggle từ nút hamburger trên mobile
    if (mobileToggler && sidebar) {
        mobileToggler.addEventListener('click', function(e) {
            e.preventDefault();
            sidebar.classList.toggle('show');

            // Đảm bảo sidebar ở trạng thái mở rộng khi hiển thị
            if (sidebar.classList.contains('show')) {
                sidebar.classList.remove('collapsed');
            }
        });
    }

    // Đóng sidebar khi click bên ngoài (chỉ trên mobile)
    document.addEventListener('click', function(event) {
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnMobileToggler = mobileToggler && mobileToggler.contains(event.target);
        const isClickOnSidebarToggler = sidebarToggler && sidebarToggler.contains(event.target);

        if (!isClickInsideSidebar &&
            !isClickOnMobileToggler &&
            !isClickOnSidebarToggler &&
            window.innerWidth < 992 &&
            sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
        }
    });

    // Xử lý dropdown menus trong sidebar thu gọn
    const dropdowns = document.querySelectorAll('.sidebar .dropdown');

    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');

        if (toggle) {
            toggle.addEventListener('click', function(e) {
                if (sidebar.classList.contains('collapsed') &&
                    !sidebar.classList.contains('show') &&
                    window.innerWidth >= 992) {
                    e.preventDefault();
                    e.stopPropagation();

                    const dropdownMenu = dropdown.querySelector('.dropdown-menu');
                    dropdownMenu.classList.toggle('show');

                    // Position dropdown menu
                    dropdownMenu.style.top = `${this.getBoundingClientRect().top}px`;

                    // Close when clicking outside
                    const closeDropdown = function(event) {
                        if (!dropdownMenu.contains(event.target) && event.target !== toggle) {
                            dropdownMenu.classList.remove('show');
                            document.removeEventListener('click', closeDropdown);
                        }
                    };

                    document.addEventListener('click', closeDropdown);
                }
            });
        }
    });

    // Xử lý thay đổi kích thước cửa sổ
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 992) {
            // Đóng sidebar mobile khi chuyển sang desktop
            sidebar.classList.remove('show');

            // Khôi phục trạng thái thu gọn từ localStorage
            const savedState = localStorage.getItem('sidebar-collapsed');
            if (savedState === 'true') {
                sidebar.classList.add('collapsed');
            } else {
                sidebar.classList.remove('collapsed');
            }
        }
    });
});