@extends('cms::layouts.master')

@section('title', 'Quản lý phiếu biểu quyết')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-2 col-lg-2">
                <h2 class="page-title">
                    Quản lý phiếu biểu quyết
                </h2>
            </div>
            <div class="col-12 col-md-auto col-lg-8 ms-auto d-print-none">
                <form action="{{ route('cms.vote-items.index') }}" method="GET" class="mb-2 mb-md-0">
                    <div class="d-flex flex-column flex-md-row gap-2 filter-group">
                        <div class="input-group input-search">
                            <input type="text" name="search" class="form-control" placeholder="Tìm theo nội dung, vị trí ứng cử" value="{{ request()->search }}">
                            <button type="submit" class="btn btn-primary" style="padding: 6px">
                                <x-tabler-icon name="search" size="20" style="margin: 0" />
                            </button>
                        </div>
                        <select name="vote_id" class="form-select input-filter" onchange="this.form.submit()">
                            <option value="">Tất cả phiếu</option>
                            @foreach($votes as $vote)
                                <option value="{{ $vote->id }}" {{ request()->vote_id == $vote->id ? 'selected' : '' }}>{{ $vote->ten_phieu }}</option>
                            @endforeach
                        </select>
                        @if(request()->has('search') || request()->has('vote_id'))
                            <a href="{{ route('cms.vote-items.index') }}" class="btn btn-outline-secondary">
                                Xóa filter
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-12 ms-auto col-lg-2 d-print-none">
                <div class="btn-list justify-content-end">
                    <a href="#" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createVoteItemModal">
                        <x-tabler-icon name="plus" size="20" />
                        <span>Thêm nội dung</span>
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
                                <th>Loại phiếu</th>
                                <th>Nội dung</th>
                                <th>Vị trí ứng cử</th>
                                <th>Tỉ lệ chấp thuận</th>
                                <th>Tổng CP biểu quyết</th>
                                <th>Tổng người biểu quyết</th>
                                <th class="w-1">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($voteItems as $voteItem)
                            <tr>
                                <td>{{ $voteItem->id }}</td>
                                <td>{{ $voteItem->vote->ten_phieu ?? 'N/A' }}</td>
                                <td>{{ $voteItem->noi_dung }}</td>
                                <td>{{ $voteItem->vi_tri_ung_cu }}</td>
                                <td>{{ $voteItem->ti_le_chap_thuan }}%</td>
                                <td>{{ number_format($voteItem->tong_co_phan_bieu_quyet) }}</td>
                                <td>{{ number_format($voteItem->tong_so_nguoi_bieu_quyet) }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="#"
                                           class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1"
                                           data-bs-toggle="modal"
                                           data-bs-target="#editVoteItemModal"
                                           data-vote-item-id="{{ $voteItem->id }}"
                                           data-vote-id="{{ $voteItem->vote_id }}"
                                           data-noi-dung="{{ $voteItem->noi_dung }}"
                                           data-vi-tri-ung-cu="{{ $voteItem->vi_tri_ung_cu }}"
                                           data-ti-le-chap-thuan="{{ $voteItem->ti_le_chap_thuan }}"
                                           data-tong-co-phan-bieu-quyet="{{ $voteItem->tong_co_phan_bieu_quyet }}"
                                           data-tong-so-nguoi-bieu-quyet="{{ $voteItem->tong_so_nguoi_bieu_quyet }}">
                                            <x-tabler-icon name="edit" size="16" />
                                            <span class="d-none d-sm-inline">Sửa</span>
                                        </a>
                                        <a href="#"
                                           class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1"
                                           data-bs-toggle="modal"
                                           data-bs-target="#deleteVoteItemModal"
                                           data-vote-item-id="{{ $voteItem->id }}"
                                           data-noi-dung="{{ $voteItem->noi_dung }}">
                                            <x-tabler-icon name="trash" size="16" />
                                            <span class="d-none d-sm-inline">Xóa</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Không có nội dung biểu quyết nào</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $voteItems->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@include('cms::vote-items.create')
@include('cms::vote-items.edit')
@include('cms::vote-items.delete')

@endsection
@push('styles')
<style>
.filter-group {
    .input-search {
        width: 60%;
    }
    .input-filter {
        width: 20%
    }
}
@media (max-width: 768px) {
    .filter-group {
        .input-search {
            width: 100%;
        }
        .input-filter {
            width: 100%;
        }
    }
}
</style>
@endpush
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Thêm hiệu ứng cho tất cả các modal
        const modals = document.querySelectorAll('.modal');

        // Hàm thiết lập lại trạng thái modal để chuẩn bị cho animation tiếp theo
        function resetModalState(modal) {
            const modalContent = modal.querySelector('.modal-content');
            const modalDialog = modal.querySelector('.modal-dialog');

            // Reset các class và style
            modal.classList.remove('animate__animated', 'animate__fadeIn', 'animate__faster');
            modalDialog.classList.remove('animate__animated', 'animate__zoomIn', 'animate__zoomOut', 'animate__faster');
            modalContent.style.opacity = '0';
        }

        modals.forEach(modal => {
            // Reset trạng thái trước khi hiển thị để đảm bảo animation luôn mượt
            modal.addEventListener('hide.bs.modal', function() {
                // Hiệu ứng khi đóng modal
                const modalDialog = this.querySelector('.modal-dialog');
                modalDialog.classList.remove('animate__zoomIn');
                modalDialog.classList.add('animate__zoomOut');
            });

            // Reset hoàn toàn sau khi đóng
            modal.addEventListener('hidden.bs.modal', function() {
                resetModalState(this);
            });

            // Thiết lập animation khi mở
            modal.addEventListener('show.bs.modal', function() {
                // Đảm bảo trạng thái ban đầu được thiết lập
                resetModalState(this);

                // Sau đó áp dụng animation mở
                setTimeout(() => {
                    // Hiệu ứng zoom-in và fade
                    this.classList.add('animate__animated', 'animate__fadeIn', 'animate__faster');

                    // Modal content zoom-in
                    const modalDialog = this.querySelector('.modal-dialog');
                    modalDialog.classList.add('animate__animated', 'animate__zoomIn', 'animate__faster');

                    // Tăng opacity từ 0 đến 1 cho nội dung
                    const modalContent = this.querySelector('.modal-content');
                    modalContent.style.transition = 'opacity 0.2s ease';
                    modalContent.style.opacity = '1';
                }, 10); // Độ trễ nhỏ để đảm bảo CSS được áp dụng sau khi modal bắt đầu hiển thị
            });
        });

        // Initialize form submission with AJAX for create form
        const createForm = document.getElementById('createVoteItemForm');
        if (createForm) {
            createForm.addEventListener('submit', function(e) {
                e.preventDefault();
                submitForm(this, "{{ route('cms.vote-items.store') }}");
            });
        }

        // Initialize form submission with AJAX for edit form
        const editForm = document.getElementById('editVoteItemForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const voteItemId = document.getElementById('edit_vote_item_id').value;
                submitForm(this, `{{ route('cms.vote-items.index') }}/${voteItemId}`);
            });
        }

        // Initialize delete form
        const deleteVoteItemModal = document.getElementById('deleteVoteItemModal');
        if (deleteVoteItemModal) {
            deleteVoteItemModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const voteItemId = button.getAttribute('data-vote-item-id');
                const noiDung = button.getAttribute('data-noi-dung');

                // Update modal content
                document.getElementById('delete_vote_item_content').textContent = noiDung;

                // Update form action
                const deleteForm = this.querySelector('#deleteVoteItemForm');
                deleteForm.action = `{{ route('cms.vote-items.index') }}/${voteItemId}`;
            });

            // Handle delete form submission
            const deleteForm = document.getElementById('deleteVoteItemForm');
            if (deleteForm) {
                deleteForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Close modal
                        bootstrap.Modal.getInstance(deleteVoteItemModal).hide();

                        // Show toast notification
                        const toast = `
                            <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        Nội dung biểu quyết đã được xóa thành công
                                    </div>
                                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>
                        `;

                        document.getElementById('global-toast-container').innerHTML = toast;
                        const toastElement = document.querySelector('.toast');
                        const toastInstance = new bootstrap.Toast(toastElement);
                        toastInstance.show();

                        // Reload page after a short delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            }
        }

        // Handle edit vote item modal
        const editVoteItemModal = document.getElementById('editVoteItemModal');
        if (editVoteItemModal) {
            editVoteItemModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const voteItemId = button.getAttribute('data-vote-item-id');
                const voteId = button.getAttribute('data-vote-id');
                const noiDung = button.getAttribute('data-noi-dung');
                const viTriUngCu = button.getAttribute('data-vi-tri-ung-cu');
                const tiLeChapThuan = button.getAttribute('data-ti-le-chap-thuan');
                const tongCoPhanBieuQuyet = button.getAttribute('data-tong-co-phan-bieu-quyet');
                const tongSoNguoiBieuQuyet = button.getAttribute('data-tong-so-nguoi-bieu-quyet');

                // Update the modal form fields
                document.getElementById('edit_vote_item_id').value = voteItemId;
                document.getElementById('edit_vote_id').value = voteId;
                document.getElementById('edit_noi_dung').value = noiDung || '';
                document.getElementById('edit_vi_tri_ung_cu').value = viTriUngCu || '';
                document.getElementById('edit_ti_le_chap_thuan').value = tiLeChapThuan || '';
                document.getElementById('edit_tong_co_phan_bieu_quyet').value = tongCoPhanBieuQuyet || '';
                document.getElementById('edit_tong_so_nguoi_bieu_quyet').value = tongSoNguoiBieuQuyet || '';
            });
        }

        // Function to submit form with AJAX
        function submitForm(form, url) {
            // Reset error messages
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

            const formData = new FormData(form);

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.errors) {
                    // Display validation errors
                    Object.keys(data.errors).forEach(field => {
                        const input = form.querySelector(`[name="${field}"]`);
                        const feedback = form.querySelector(`.${field}-error`);
                        if (input && feedback) {
                            input.classList.add('is-invalid');
                            feedback.textContent = data.errors[field][0];
                        }
                    });
                } else {
                    // Success - Close modal and redirect or reload
                    const modalId = form.closest('.modal').id;
                    bootstrap.Modal.getInstance(document.getElementById(modalId)).hide();

                    // Show toast notification
                    const toast = `
                        <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    ${data.message || 'Thao tác thành công'}
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    `;

                    document.getElementById('global-toast-container').innerHTML = toast;
                    const toastElement = document.querySelector('.toast');
                    const toastInstance = new bootstrap.Toast(toastElement);
                    toastInstance.show();

                    // Redirect after a short delay
                    setTimeout(() => {
                        window.location.href = data.redirect || window.location.href;
                    }, 500);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });
</script>
@endpush
