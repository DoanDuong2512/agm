@extends('cms::layouts.master')

@section('title', 'Quản lý ủy quyền')

@push('styles')
<link href="{{ asset('modules/cms/static/js/select2.min.css') }}" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 5px;
        border: 1px solid #ddd;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .select2-container {
        width: 100%!important;
    }
</style>
@endpush

@push('scripts')
    <script src="{{ asset('modules/cms/static/js/select2.min.js') }}"></script>
@endpush

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Quản lý ủy quyền
                </h2>
            </div>
            <div class="col-12 col-md-auto ms-auto d-print-none">
                <form action="{{ route('cms.authority.index') }}" method="GET" class="mb-2 mb-md-0">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm ủy quyền..." value="{{ request()->search }}">
                        <button type="submit" class="btn btn-primary">
                            <x-tabler-icon name="search" size="20" />
                        </button>
                        @if(request()->has('search'))
                            <a href="{{ route('cms.authority.index') }}" class="btn btn-outline-secondary">
                                <x-tabler-icon name="x" size="20" />
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="#" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createAuthorityModal">
                        <x-tabler-icon name="plus" size="20" />
                        <span>Thêm ủy quyền</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Người ủy quyền</th>
                                <th>Người được ủy quyền</th>
                                <th>Cổ phần ủy quyền</th>
                                <th>Loại người nhận</th>
                                <th class="w-1">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($authorities as $authority)
                            <tr>
                                <td>{{ $authority->id }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bold">{{ $authority->authorizer->ma_co_dong ?? 'N/A' }}</span>
                                        <span>{{ $authority->authorizer->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        @if($authority->is_shareholder)
                                            <span class="font-weight-bold">{{ $authority->authorized->ma_co_dong ?? 'N/A' }}</span>
                                            <span>{{ $authority->authorized->name ?? 'N/A' }}</span>
                                        @else
                                            <span>{{ $authority->ten_nguoi_duoc_uy_quyen }}</span>
                                            <small class="text-muted">CCCD: {{ $authority->vn_id }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ number_format($authority->co_phan_uy_quyen) }}</td>
                                <td>
                                    @if($authority->is_shareholder)
                                        <span class="badge bg-blue text-white">Cổ đông</span>
                                    @else
                                        <span class="badge bg-secondary text-white">Người khác</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <!-- <a href="#"
                                           class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1 edit-authority-btn"
                                           data-bs-toggle="modal"
                                           data-bs-target="#editAuthorityModal"
                                           data-authority-id="{{ $authority->id }}">
                                            <x-tabler-icon name="edit" size="16" />
                                            <span class="d-none d-sm-inline">Sửa</span>
                                        </a> -->
                                        <a href="#"
                                           class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1"
                                           data-bs-toggle="modal"
                                           data-bs-target="#deleteAuthorityModal"
                                           data-authority-id="{{ $authority->id }}"
                                           data-authority-name="{{ $authority->authorizer->name ?? 'N/A' }}"
                                           data-authority-receiver="{{ $authority->is_shareholder ? ($authority->authorized->name ?? 'N/A') : $authority->ten_nguoi_duoc_uy_quyen }}">
                                            <x-tabler-icon name="trash" size="16" />
                                            <span class="d-none d-sm-inline">Xóa</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Không có thông tin ủy quyền nào</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $authorities->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@include('cms::authority.create')
@include('cms::authority.edit')
@include('cms::authority.delete')

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animations for all modals
        const modals = document.querySelectorAll('.modal');

        // Function to reset modal state for next animation
        function resetModalState(modal) {
            const modalContent = modal.querySelector('.modal-content');
            const modalDialog = modal.querySelector('.modal-dialog');

            // Reset classes and styles
            modal.classList.remove('animate__animated', 'animate__fadeIn', 'animate__faster');
            modalDialog.classList.remove('animate__animated', 'animate__zoomIn', 'animate__zoomOut', 'animate__faster');
            modalContent.style.opacity = '0';
        }

        modals.forEach(modal => {
            // Reset state before display for smooth animation
            modal.addEventListener('hide.bs.modal', function() {
                // Animation when closing modal
                const modalDialog = this.querySelector('.modal-dialog');
                modalDialog.classList.remove('animate__zoomIn');
                modalDialog.classList.add('animate__zoomOut');
            });

            // Complete reset after closing
            modal.addEventListener('hidden.bs.modal', function() {
                resetModalState(this);
            });

            // Setup animation when opening
            modal.addEventListener('show.bs.modal', function() {
                // Ensure initial state is set
                resetModalState(this);

                // Then apply opening animation
                setTimeout(() => {
                    // Zoom-in and fade effects
                    this.classList.add('animate__animated', 'animate__fadeIn', 'animate__faster');

                    // Modal content zoom-in
                    const modalDialog = this.querySelector('.modal-dialog');
                    modalDialog.classList.add('animate__animated', 'animate__zoomIn', 'animate__faster');

                    // Increase opacity from 0 to 1 for content
                    const modalContent = this.querySelector('.modal-content');
                    modalContent.style.transition = 'opacity 0.2s ease';
                    modalContent.style.opacity = '1';
                }, 10); // Small delay to ensure CSS is applied after modal begins to display
            });
        });

        // Handle edit authority modal loading
        $('.edit-authority-btn').on('click', function() {
            const authorityId = $(this).data('authority-id');
            
            // Clear previous data and show loading state
            $('#editAuthorityForm button[type="submit"]').attr('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang tải...');
            
            // Ensure Select2 is initialized
            setTimeout(function() {
                // Check if Select2 is initialized
                if ($.fn.select2 && typeof $.fn.select2 === 'function') {
                    // Fetch authority data
                    $.ajax({
                        url: `{{ route('cms.authority.index') }}/${authorityId}/edit`,
                        type: 'GET',
                        success: function(response) {
                            // Call the function defined in edit.blade.php to load data
                            if (window.loadAuthorityData) {
                                window.loadAuthorityData(response);
                            }
                            
                            // Reset button state
                            $('#editAuthorityForm button[type="submit"]').attr('disabled', false).text('CẬP NHẬT');
                        },
                        error: function(xhr) {
                            toastr.error('Đã xảy ra lỗi khi tải dữ liệu. Vui lòng thử lại sau.');
                            $('#editAuthorityForm button[type="submit"]').attr('disabled', false).text('CẬP NHẬT');
                        }
                    });
                } else {
                    console.error('Select2 is not loaded properly');
                    toastr.error('Đã xảy ra lỗi: Select2 không được tải đúng cách');
                    $('#editAuthorityForm button[type="submit"]').attr('disabled', false).text('CẬP NHẬT');
                }
            }, 1000);
        });

        // Handle delete authority modal
        const deleteAuthorityModal = document.getElementById('deleteAuthorityModal');
        if (deleteAuthorityModal) {
            deleteAuthorityModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const authorityId = button.getAttribute('data-authority-id');
                const authorityName = button.getAttribute('data-authority-name');
                const authorityReceiver = button.getAttribute('data-authority-receiver');

                // Update modal content
                document.getElementById('delete_authority_name').textContent = authorityName;
                document.getElementById('delete_authority_receiver').textContent = authorityReceiver;

                // Update form action
                const deleteForm = this.querySelector('#deleteAuthorityForm');
                deleteForm.action = `{{ route('cms.authority.index') }}/${authorityId}`;
            });
        }
    });
</script>
@endpush
