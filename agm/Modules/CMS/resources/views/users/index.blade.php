@extends('cms::layouts.master')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Quản lý người dùng
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="#" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <x-tabler-icon name="plus" size="20" />
                        <span>Thêm người dùng</span>
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
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Trạng thái</th>
                                <th class="w-1">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar bg-primary-lt">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->status_color }} text-white">
                                        {{ $user->status_text }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="#" 
                                           class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1"
                                           data-bs-toggle="modal" 
                                           data-bs-target="#editUserModal" 
                                           data-user-id="{{ $user->id }}"
                                           data-user-name="{{ $user->name }}"
                                           data-user-email="{{ $user->email }}"
                                           data-user-active="{{ $user->is_active }}">
                                            <x-tabler-icon name="edit" size="16" />
                                            <span class="d-none d-sm-inline">Sửa</span>
                                        </a>
                                        <a href="#"
                                           class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1"
                                           data-bs-toggle="modal"
                                           data-bs-target="#deleteUserModal"
                                           data-user-id="{{ $user->id }}"
                                           data-user-name="{{ $user->name }}">
                                            <x-tabler-icon name="trash" size="16" />
                                            <span class="d-none d-sm-inline">Xóa</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Không có người dùng nào</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@include('cms::users.create')
@include('cms::users.edit')
@include('cms::users.delete')
@endsection

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
        const createForm = document.getElementById('createUserForm');
        if (createForm) {
            createForm.addEventListener('submit', function(e) {
                e.preventDefault();
                submitForm(this, "{{ route('cms.users.store') }}");
            });
        }

        // Initialize form submission with AJAX for edit form
        const editForm = document.getElementById('editUserForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const userId = document.getElementById('edit_user_id').value;
                submitForm(this, `{{ route('cms.users.index') }}/${userId}`);
            });
        }

        // Handle edit user modal
        const editUserModal = document.getElementById('editUserModal');
        if (editUserModal) {
            editUserModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const userId = button.getAttribute('data-user-id');
                const userName = button.getAttribute('data-user-name');
                const userEmail = button.getAttribute('data-user-email');
                const userActive = button.getAttribute('data-user-active');
                
                // Update the modal form fields
                document.getElementById('edit_user_id').value = userId;
                document.getElementById('edit_name').value = userName;
                document.getElementById('edit_email').value = userEmail;
                
                // Set the correct radio button (ensure exact comparison)
                if (userActive === '1' || userActive === 1) {
                    document.getElementById('edit_active_1').checked = true;
                } else {
                    document.getElementById('edit_active_0').checked = true;
                }
                
                // Update the form action URL
                editForm.action = `{{ route('cms.users.index') }}/${userId}`;
            });
        }
        
        // Initialize delete form
        const deleteUserModal = document.getElementById('deleteUserModal');
        if (deleteUserModal) {
            deleteUserModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const userId = button.getAttribute('data-user-id');
                const userName = button.getAttribute('data-user-name');
                
                // Update modal content
                document.getElementById('delete_user_name').textContent = userName;
                
                // Update form action
                const deleteForm = this.querySelector('#deleteUserForm');
                deleteForm.action = `{{ route('cms.users.index') }}/${userId}`;
            });
            
            // Handle delete form submission
            const deleteForm = document.getElementById('deleteUserForm');
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
                        bootstrap.Modal.getInstance(deleteUserModal).hide();
                        
                        // Use the global showToast function
                        showToast('Xóa người dùng thành công', 'success');
                        
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
                    
                    // Use the global showToast function instead of manual toast creation
                    showToast(data.message, 'success');
                    
                    // Redirect after a short delay
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });
</script>
@endpush