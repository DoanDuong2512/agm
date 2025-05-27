<div class="sidebar">
    <div class="p-3">
        <div class="collapsible-menu mb-2">
            <button class="btn d-flex align-items-center justify-content-between w-100 text-start p-0" type="button"
                    data-bs-toggle="collapse" data-bs-target="#setupMenu" aria-expanded="{{ request()->routeIs('cms.index', 'cms.meeting-configs.*', 'cms.customers.*', 'cms.authority.*', 'cms.documents.*') ? 'true' : 'false' }}">
                <div class="nav-link d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center">
                        <x-tabler-icon name="calendar" size="20" />
                        <span class="fw-semibold">Thiết lập đại hội</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </div>
            </button>
            <div class="collapse {{ request()->routeIs('cms.index', 'cms.meeting-configs.*', 'cms.customers.*', 'cms.votes.*', 'cms.authority.*', 'cms.documents.*', 'cms.vote-items.*') ? 'show' : '' }}"
                 id="setupMenu">
                <div class="nav flex-column nav-pills ms-4 mt-2">
                    <a href="{{ route('cms.meeting-configs.index') }}" class="nav-link {{ request()->routeIs('cms.meeting-configs.*') ? 'active' : '' }} d-flex align-items-center py-1">
                        <span>Cấu hình hệ thống</span>
                    </a>
                    <a href="{{ route('cms.vote-items.index') }}" class="nav-link {{ request()->routeIs('cms.vote-items.*') ? 'active' : '' }} d-flex align-items-center py-1">
                        <span>Quản lý phiếu cũ </span>
                    </a>
                    <a href="{{ route('cms.votes.index') }}" class="nav-link {{ request()->routeIs('cms.votes.*') ? 'active' : '' }} d-flex align-items-center py-1">
                        <span>Quản lý phiếu mới </span>
                    </a>
                    <a href="{{ route('cms.customers.index') }}" class="nav-link {{ request()->routeIs('cms.customers.*') ? 'active' : '' }} d-flex align-items-center py-1">
                        <span>Quản lý cổ đông</span>
                    </a>
                    <a href="{{ route('cms.authority.index') }}" class="nav-link {{ request()->routeIs('cms.authority.*') ? 'active' : '' }} d-flex align-items-center py-1">
                        <span>Ủy quyền tham dự</span>
                    </a>
                    <a href="{{ route('cms.documents.index') }}" class="nav-link {{ request()->routeIs('cms.documents.*') ? 'active' : '' }} d-flex align-items-center py-1">
                        <span>Tài liệu cuộc họp</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="collapsible-menu mb-2">
            <button class="btn d-flex align-items-center justify-content-between w-100 text-start p-0" type="button"
                    data-bs-toggle="collapse" data-bs-target="#operateMenu" aria-expanded="{{ request()->routeIs('cms.index', 'cms.vote.*', 'cms.conversations.*') ? 'true' : 'false' }}">
                <div class="nav-link d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center">
                    <x-tabler-icon name="device-desktop" size="20" />
                        <span class="fw-semibold">Điều hành đại hội</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </div>
            </button>
            <div class="collapse {{ request()->routeIs('cms.index', 'cms.vote.*', 'cms.conversations.*') ? 'show' : '' }}"
                 id="operateMenu">
                <div class="nav flex-column nav-pills ms-4 mt-2">
                    <a href="{{ route('cms.vote.index') }}" class="nav-link {{ request()->routeIs('cms.vote.index') ? 'active' : '' }} d-flex align-items-center py-1">
                        <span>Nhập phiếu BQBC</span>
                    </a>
                    <a href="{{ route('cms.conversations.index') }}" class="nav-link {{ request()->routeIs('cms.conversations.*') ? 'active' : '' }} d-flex align-items-center py-1">
                        <span>Hỏi đáp cổ đông</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="collapsible-menu mb-2">
            <button class="btn d-flex align-items-center justify-content-between w-100 text-start p-0" type="button"
                    data-bs-toggle="collapse" data-bs-target="#resultMenu" aria-expanded="{{ request()->routeIs('cms.index', 'cms.vote.print', 'cms.print.bbdh') ? 'true' : 'false' }}">
                <div class="nav-link d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center">
                    <x-tabler-icon name="file-text" size="20" />
                        <span class="fw-semibold">Kết quả đại hội</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </div>
            </button>
            <div class="collapse {{ request()->routeIs('cms.index', 'cms.vote.print', 'cms.print.bbdh') ? 'show' : '' }}"
                 id="resultMenu">
                <div class="nav flex-column nav-pills ms-4 mt-2">
                    <a href="{{ route('cms.vote.print') }}" class="nav-link {{ request()->routeIs('cms.vote.print') ? 'active' : '' }} d-flex align-items-center py-1">
                        <span>Biên bản KTTC CĐ</span>
                    </a>
                </div>
                <div class="nav flex-column nav-pills ms-4 mt-2">
                    <a href="{{ route('cms.print.bbdh') }}" class="nav-link {{ request()->routeIs('cms.print.bbdh') ? 'active' : '' }} d-flex align-items-center py-1">
                        <span>Biên bản họp đại hội</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="collapsible-menu mb-2">
            <button class="btn d-flex align-items-center justify-content-between w-100 text-start p-0" type="button"
                    data-bs-toggle="collapse" data-bs-target="#systemMenu" aria-expanded="{{ request()->routeIs('cms.index', 'cms.users.*') ? 'true' : 'false' }}">
                <div class="nav-link d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center">
                        <x-tabler-icon name="settings" size="20" />
                        <span class="fw-semibold">Cấu hình hệ thống</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </div>
            </button>
            <div class="collapse {{ request()->routeIs('cms.index', 'cms.users.*') ? 'show' : '' }}"
                 id="systemMenu">
                <div class="nav flex-column nav-pills ms-4 mt-2">
                    <a href="{{ route('cms.users.index') }}" class="nav-link {{ request()->routeIs('cms.users.*') ? 'active' : '' }} d-flex align-items-center py-1">
                        <span>Tài khoản người dùng</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="nav flex-column nav-pills">
            <!-- Quản lý người dùng -->
            <!-- <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('cms.users.*') ? 'active' : '' }}" href="{{ route('cms.users.index') }}">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <x-tabler-icon name="user" />
                    </span>
                    <span class="nav-link-title">
                        Quản lý người dùng
                    </span>
                </a>
            </li> -->
            <!-- Quản lý cổ đông -->
            <!-- <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('cms.customers.*') ? 'active' : '' }}" href="{{ route('cms.customers.index') }}">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <x-tabler-icon name="users" />
                    </span>
                    <span class="nav-link-title">
                        Quản lý cổ đông
                    </span>
                </a>
            </li> -->
            <!-- Quản lý ủy quyền -->
            <!-- <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('cms.authority.*') ? 'active' : '' }}" href="{{ route('cms.authority.index') }}">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <x-tabler-icon name="users" />
                    </span>
                    <span class="nav-link-title">
                        Quản lý ủy quyền
                    </span>
                </a>
            </li> -->
            <!-- Quản lý tài liệu -->
            <!-- <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('cms.documents.*') ? 'active' : '' }}" href="{{ route('cms.documents.index') }}">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                        </svg>
                    </span>
                    <span class="nav-link-title">
                        Quản lý tài liệu
                    </span>
                </a>
            </li> -->
            <!-- <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('cms.conversations.*') ? 'active' : '' }}" href="{{ route('cms.conversations.index') }}">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <x-tabler-icon name="message-circle" />
                    </span>
                    <span class="nav-link-title">
                        Chat với cổ đông
                    </span>
                </a>
            </li> -->
            <!-- <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('cms.meeting-configs.*') ? 'active' : '' }}" href="{{ route('cms.meeting-configs.index') }}">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <x-tabler-icon name="settings" />
                    </span>
                    <span class="nav-link-title">
                        Quản lý cấu hình
                    </span>
                </a>
            </li> -->
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý lưu trạng thái menu
        const meetingMenuButton = document.querySelector('[data-bs-target="#meetingMenu"]');
        const meetingMenu = document.getElementById('meetingMenu');
        const sidebar = document.querySelector('.sidebar');

        // Lấy trạng thái đã lưu
        const savedState = localStorage.getItem('meetingMenu-collapsed');

        if (savedState === 'true') {
            meetingMenu.classList.remove('show');
            meetingMenuButton.setAttribute('aria-expanded', 'false');
        }

        // Lưu trạng thái khi click
        meetingMenuButton.addEventListener('click', function() {
            const isCollapsed = meetingMenu.classList.contains('show');
            localStorage.setItem('meetingMenu-collapsed', isCollapsed);
        });

        // Theo dõi sự thay đổi của sidebar
        const sidebarObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    // Nếu sidebar bị thu gọn, tự động đóng menu
                    if (sidebar.classList.contains('collapsed')) {
                        meetingMenu.classList.remove('show');
                        meetingMenuButton.setAttribute('aria-expanded', 'false');
                    }
                }
            });
        });

        // Cấu hình observer để theo dõi các thay đổi của class
        sidebarObserver.observe(sidebar, { attributes: true });
    });
</script>
