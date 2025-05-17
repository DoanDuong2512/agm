<nav class="navbar navbar-expand-lg navbar-dark navbar-primary">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <button class="navbar-toggler me-2 border-0" type="button" id="toggle-sidebar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a href="{{ route('cms.index') }}" class="navbar-brand d-flex align-items-center">
                <img src="{{ asset('/images/logo_elc_cms.png') }}" alt="">
                <span class="fw-bold">PHẦN MỀM HĐQT</span>
            </a>

            <div class="ms-4 d-flex">
                <a href="#" class="nav-link text-white d-flex align-items-center">
                    <button type="button" class="sidebar-toggler btn btn-link text-white p-0 me-2 border-0 d-none d-lg-block" id="sidebar-toggler">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-circle-arrow-left"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 21a9 9 0 1 0 0 -18a9 9 0 0 0 0 18" /><path d="M8 12l4 4" /><path d="M8 12h8" /><path d="M12 8l-4 4" /></svg>
                    </button>
                    <span class="d-none d-lg-inline text-uppercase">{{ \Modules\CMS\App\Services\PageTitleService::getTitle() }}</span>
                </a>
            </div>
        </div>

        <div class="d-flex align-items-center">
            <div class="dropdown me-3">
                <a href="#" class="nav-link text-white position-relative" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                    </svg>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        1
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <div class="dropdown-header">Thông báo</div>
                    <a class="dropdown-item" href="#">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle text-primary" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                </svg>
                            </div>
                            <div class="ms-2">
                                <div class="fw-semibold">Cổ đông mới</div>
                                <div class="text-muted small">Có cổ đông mới vừa được tạo</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="avatar avatar-sm bg-white text-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                        <span class="fw-bold">A</span>
                    </div>
                    <span>Admin</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small shadow">
                    <li><a class="dropdown-item" href="#">Thông tin tài khoản</a></li>
                    <li><a class="dropdown-item" href="#">Cài đặt</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('cms.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Đăng xuất</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
