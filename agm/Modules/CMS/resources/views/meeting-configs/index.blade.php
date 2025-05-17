@extends('cms::layouts.master')
@section('title', 'Quản lý cấu hình')
@section('content')
<div class="container-fluid p-0">
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h3>{{ $title ?? 'Cấu hình hệ thống' }}</h3>
        </div>

        <div class="col-auto ms-auto text-end mt-n1">
            <button type="button" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createConfigModal">
                <x-tabler-icon name="plus" size="20" />
                <span>Thêm cấu hình mới</span>
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Key</th>
                                    <th>Value</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($configs as $config)
                                <tr>
                                    <td>{{ $config->id }}</td>
                                    <td>{{ $config->key }}</td>
                                    <td>{{ $config->value }}</td>
                                    <td>{{ $config->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editConfigModal" 
                                                data-config-id="{{ $config->id }}" 
                                                data-config-key="{{ $config->key }}" 
                                                data-config-value="{{ $config->value }}">
                                                <i class="align-middle" data-feather="edit"></i>
                                                <span class="d-none d-sm-inline">Sửa</span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteConfigModal" 
                                                data-config-id="{{ $config->id }}" 
                                                data-config-key="{{ $config->key }}">
                                                <i class="align-middle" data-feather="trash"></i>
                                                <span class="d-none d-sm-inline">Xóa</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $configs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('cms::meeting-configs.create')
@include('cms::meeting-configs.edit')
@include('cms::meeting-configs.delete')

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
        const createForm = document.getElementById('createConfigForm');
        if (createForm) {
            createForm.addEventListener('submit', function(e) {
                e.preventDefault();
                submitForm(this, "{{ route('cms.meeting-configs.store') }}");
            });
        }

        // Initialize form submission with AJAX for edit form
        const editForm = document.getElementById('editConfigForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const configId = document.getElementById('edit_config_id').value;
                submitForm(this, `{{ route('cms.meeting-configs.index') }}/${configId}`);
            });
        }
        
        // Initialize delete form
        const deleteConfigModal = document.getElementById('deleteConfigModal');
        if (deleteConfigModal) {
            deleteConfigModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const configId = button.getAttribute('data-config-id');
                const configKey = button.getAttribute('data-config-key');
                
                // Update modal content
                document.getElementById('delete_config_key').textContent = configKey;
                
                // Update form action
                const deleteForm = document.getElementById('deleteConfigForm');
                deleteForm.action = `{{ route('cms.meeting-configs.index') }}/${configId}`;
            });
            
            // Handle delete form submission
            const deleteForm = document.getElementById('deleteConfigForm');
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
                        bootstrap.Modal.getInstance(deleteConfigModal).hide();
                        
                        // Show toast notification
                        const toast = `
                            <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        Cấu hình đã được xóa thành công
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

        // Handle edit config modal
        const editConfigModal = document.getElementById('editConfigModal');
        if (editConfigModal) {
            editConfigModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const configId = button.getAttribute('data-config-id');
                const configKey = button.getAttribute('data-config-key');
                const configValue = button.getAttribute('data-config-value');
                
                // Update form fields
                document.getElementById('edit_config_id').value = configId;
                document.getElementById('edit_key').value = configKey;
                document.getElementById('edit_value').value = configValue || '';
                
                // Update the form action URL
                editForm.action = `{{ route('cms.meeting-configs.index') }}/${configId}`;
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
                } else if (data.success) {
                    // Success - Close modal and redirect or reload
                    const modalId = form.closest('.modal').id;
                    bootstrap.Modal.getInstance(document.getElementById(modalId)).hide();
                    
                    // Show toast notification
                    const toast = `
                        <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    ${data.message}
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
                }
            })
            .catch(error => {
                toastr.error('Đã xảy ra lỗi khi xử lý yêu cầu của bạn');
                console.error('Error:', error);
            });
        }
    });
</script>
@endpush