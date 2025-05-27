@extends('cms::layouts.master')

@section('title', 'Quản lý cổ đông')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-2 col-lg-2">
                <h2 class="page-title">
                    Quản lý cổ đông
                </h2>
            </div>
            <div class="col-12 col-md-auto col-lg-8 ms-auto d-print-none">
                <form action="{{ route('cms.customers.index') }}" method="GET" class="mb-2 mb-md-0">
                    <div class="d-flex flex-column flex-md-row gap-2 filter-group">
                        <div class="input-group input-search">
                            <input type="text" name="search" class="form-control" placeholder="Tìm theo căn cước, mã CĐ" value="{{ request()->search }}">
                            <button type="submit" class="btn btn-primary" style="padding: 6px">
                                <x-tabler-icon name="search" size="20" style="margin: 0" />
                            </button>
                        </div>
                        <select name="co_dong_noi_bo" class="form-select input-filter" onchange="this.form.submit()">
                            <option value="">Tất cả</option>
                            <option value="1" {{ request()->co_dong_noi_bo === '1' ? 'selected' : '' }}>Cổ đông nội bộ</option>
                            <option value="0" {{ request()->co_dong_noi_bo === '0' ? 'selected' : '' }}>Cổ đông thường</option>
                        </select>
                        <select name="is_checkin" class="form-select input-filter" onchange="this.form.submit()">
                            <option value="">Tất cả</option>
                            <option value="1" {{ request()->is_checkin === '1' ? 'selected' : '' }}>Đã check-in</option>
                            <option value="0" {{ request()->is_checkin === '0' ? 'selected' : '' }}>Chưa check-in</option>
                        </select>
                        @if(request()->has('search') || request()->has('co_dong_noi_bo') || request()->has('is_checkin'))
                            <a href="{{ route('cms.customers.index') }}" class="btn btn-outline-secondary">
                                Xóa filter <x-tabler-icon name="x" size="20" style="margin-left: 10px;" />
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-12 ms-auto col-lg-2 d-print-none">
                <div class="btn-list justify-content-end">
                    <a href="#" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createCustomerModal">
                        <x-tabler-icon name="plus" size="20" />
                        <span>Thêm cổ đông</span>
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
                                <th>Căn cước</th>
                                <th>Mã CĐ</th>
                                <th>Email</th>
                                <th>Điện thoại</th>
                                <th>CP sở hữu</th>
                                <th>CP đã ủy quyền</th>
                                <th>CP được ủy quyền</th>
                                <th>CP được ủy quyền trừ nội bộ</th>
                                <th>Tổng số CĐ ủy quyền</th>
                                <th>CĐ nội bộ</th>
                                <th>Trạng thái</th>
                                <th>Check-in</th>
                                <th class="w-1">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar bg-primary-lt">
                                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        {{ $customer->name }}
                                    </div>
                                </td>
                                <td>{{ $customer->vn_id }}</td>
                                <td>{{ $customer->ma_co_dong }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ number_format($customer->co_phan_so_huu) }}</td>
                                <td>{{ number_format($customer->co_phan_da_uy_quyen) }}</td>
                                <td>{{ number_format($customer->tong_co_phan_duoc_uy_quyen) }}</td>
                                <td>{{ number_format($customer->tong_co_phan_duoc_uy_quyen_tru_noi_bo) }}</td>
                                <td>{{ number_format($customer->tong_so_co_dong_uy_quyen) }}</td>
                                <td>
                                    @if($customer->co_dong_noi_bo == 1)
                                        <span class="badge bg-blue text-white">Nội bộ</span>
                                    @else
                                        <span class="badge bg-secondary text-white">Thường</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $customer->is_active == 1 ? 'green' : 'red' }} text-white">
                                        {{ $customer->is_active == 1 ? 'Hoạt động' : 'Không hoạt động' }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        // Kiểm tra xem có phải là người được ủy quyền không phải cổ đông không
                                        $hasShareholderAuthority = $customer->authoritiesGiven->isNotEmpty();
                                        // Kiểm tra điều kiện ủy quyền hết cổ phần
                                        $hasFullyDelegated = $customer->co_phan_da_uy_quyen >= $customer->co_phan_so_huu;
                                    @endphp
                                    
                                    @if ($hasFullyDelegated && $hasShareholderAuthority)
                                        <button class="btn btn-sm btn-secondary" disabled>
                                            <span class="d-flex align-items-center gap-1">
                                                <x-tabler-icon name="x" size="16" />
                                                <span>Không thể check-in</span>
                                            </span>
                                        </button>
                                        <small class="d-block text-muted" style="font-size: 11px;">(Đã ủy quyền hết cổ phần)</small>
                                    @else
                                        <button
                                            class="btn btn-sm {{ $customer->is_checkin == 1 ? 'btn-green' : 'btn-secondary' }} checkin-toggle"
                                            data-customer-id="{{ $customer->id }}"
                                            data-checkin-status="{{ $customer->is_checkin }}"
                                        >
                                            <span class="d-flex align-items-center gap-1">
                                                @if($customer->is_checkin == 1)
                                                    <x-tabler-icon name="check" size="16" />
                                                    <span>Đã check-in</span>
                                                @else
                                                    <x-tabler-icon name="x" size="16" />
                                                    <span>Chưa check-in</span>
                                                @endif
                                            </span>
                                        </button>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="#"
                                           class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1"
                                           data-bs-toggle="modal"
                                           data-bs-target="#editCustomerModal"
                                           data-customer-id="{{ $customer->id }}"
                                           data-customer-name="{{ $customer->name }}"
                                           data-customer-email="{{ $customer->email }}"
                                           data-customer-phone="{{ $customer->phone }}"
                                           data-customer-vn-id="{{ $customer->vn_id }}"
                                           data-customer-gender="{{ $customer->gender }}"
                                           data-customer-address="{{ $customer->address }}"
                                           data-customer-active="{{ $customer->is_active }}"
                                           data-customer-is-checkin="{{ $customer->is_checkin }}"
                                           data-customer-ma-co-dong="{{ $customer->ma_co_dong }}"
                                           data-customer-co-phan-so-huu="{{ $customer->co_phan_so_huu }}"
                                           data-customer-tong-co-phan-duoc-uy-quyen="{{ $customer->tong_co_phan_duoc_uy_quyen }}"
                                           data-customer-tong-so-co-dong-uy-quyen="{{ $customer->tong_so_co_dong_uy_quyen }}"
                                           data-customer-co-dong-noi-bo="{{ $customer->co_dong_noi_bo ? 1 : 0 }}"
                                           data-customer-vn-id-issue-date="{{ $customer->vn_id_issue_date }}">
                                            <x-tabler-icon name="edit" size="16" />
                                            <span class="d-none d-sm-inline">Sửa</span>
                                        </a>
                                        <a href="#"
                                           class="btn btn-sm d-flex btn-outline-default align-items-center gap-1"
                                           data-bs-toggle="modal"
                                           data-bs-target="#printAttendanceModal"
                                           data-customer-id="{{ $customer->id }}"
                                           data-customer-name="{{ $customer->name }}"
                                           data-customer-vn-id="{{ $customer->vn_id }}"
                                           data-customer-ma-co-dong="{{ $customer->ma_co_dong }}"
                                           data-customer-co-phan-so-huu="{{ $customer->co_phan_so_huu }}"
                                           data-customer-co-phan-da-uy-quyen="{{ $customer->co_phan_da_uy_quyen }}"
                                           data-customer-tong-co-phan-duoc-uy-quyen="{{ $customer->tong_co_phan_duoc_uy_quyen }}"
                                           data-customer-tong-so-co-dong-uy-quyen="{{ $customer->tong_so_co_dong_uy_quyen }}">
                                            <x-tabler-icon name="printer" size="16" />
                                            <span class="d-none d-sm-inline">In phiếu xác nhận tham dự</span>
                                        </a>
                                        <a target="_blank" href="@if(config('app.env') !== 'production') http://103.21.151.146:8380/print-confirmation?autoprint=true&documentType=thebieuquyet&ma_co_dong={{$customer->ma_co_dong}} @else https://agm.elcom.com.vn/print-confirmation?autoprint=true&documentType=thebieuquyet&ma_co_dong={{$customer->ma_co_dong}} @endif"
                                           class="btn btn-sm btn-outline-yellow d-flex align-items-center gap-1"
                                        >
                                            <x-tabler-icon name="printer" size="16" />
                                            <span class="d-none d-sm-inline">In thẻ biểu quyết</span>
                                        </a>
                                        <a target="_blank" href="@if(config('app.env') !== 'production') http://103.21.151.146:8380/print-confirmation?autoprint=true&documentType=votecard&ma_co_dong={{$customer->ma_co_dong}} @else https://agm.elcom.com.vn/print-confirmation?autoprint=true&documentType=votecard&ma_co_dong={{$customer->ma_co_dong}} @endif"
                                           class="btn btn-sm btn-outline-info d-flex align-items-center gap-1"
                                        >
                                            <x-tabler-icon name="printer" size="16" />
                                            <span class="d-none d-sm-inline">In phiếu bầu cử</span>
                                        </a>
                                        <a href="#"
                                           class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1"
                                           data-bs-toggle="modal"
                                           data-bs-target="#deleteCustomerModal"
                                           data-customer-id="{{ $customer->id }}"
                                           data-customer-name="{{ $customer->name }}">
                                            <x-tabler-icon name="trash" size="16" />
                                            <span class="d-none d-sm-inline">Xóa</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="13" class="text-center">Không có cổ đông nào</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@include('cms::customers.create')
@include('cms::customers.edit')
@include('cms::customers.delete')
@include('cms::customers.print_attendance')

@endsection
@push('styles')
<style lang="scss">
.btn-outline-yellow {
    color: #f5c242;
    border-color: #f5c242;
}

.btn-outline-yellow:hover {
    color: #fff;
    background-color: #f5c242;
    border-color: #f5c242;
}
.btn-outline-default {
    color: #000;
}
.btn-outline-default:hover {
    color: #fff;
    background-color: #000;
}
.checkin-toggle {
    cursor: pointer;
    padding: 2px 4px;
}
.btn {
    .icon {
        margin: 0;
    }
}
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
        const createForm = document.getElementById('createCustomerForm');
        if (createForm) {
            createForm.addEventListener('submit', function(e) {
                e.preventDefault();
                submitForm(this, "{{ route('cms.customers.store') }}");
            });
        }

        // Initialize form submission with AJAX for edit form
        const editForm = document.getElementById('editCustomerForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const customerId = document.getElementById('edit_customer_id').value;
                submitForm(this, `{{ route('cms.customers.index') }}/${customerId}`);
            });
        }

        // Initialize delete form
        const deleteCustomerModal = document.getElementById('deleteCustomerModal');
        if (deleteCustomerModal) {
            deleteCustomerModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const customerId = button.getAttribute('data-customer-id');
                const customerName = button.getAttribute('data-customer-name');

                // Update modal content
                document.getElementById('delete_customer_name').textContent = customerName;

                // Update form action
                const deleteForm = this.querySelector('#deleteCustomerForm');
                deleteForm.action = `{{ route('cms.customers.index') }}/${customerId}`;
            });

            // Handle delete form submission
            const deleteForm = document.getElementById('deleteCustomerForm');
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
                        bootstrap.Modal.getInstance(deleteCustomerModal).hide();

                        // Show toast notification
                        const toast = `
                            <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        Cổ đông đã được xóa thành công
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

        // Handle edit customer modal
        const editCustomerModal = document.getElementById('editCustomerModal');
        if (editCustomerModal) {
            editCustomerModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const customerId = button.getAttribute('data-customer-id');
                const customerName = button.getAttribute('data-customer-name');
                const customerEmail = button.getAttribute('data-customer-email');
                const customerPhone = button.getAttribute('data-customer-phone');
                const customerVnId = button.getAttribute('data-customer-vn-id');
                const customerGender = button.getAttribute('data-customer-gender');
                const customerAddress = button.getAttribute('data-customer-address');
                const customerActive = button.getAttribute('data-customer-active');

                // Update the modal form fields
                document.getElementById('edit_customer_id').value = customerId;
                document.getElementById('edit_name').value = customerName;
                document.getElementById('edit_email').value = customerEmail || '';
                document.getElementById('edit_phone').value = customerPhone || '';
                document.getElementById('edit_vn_id').value = customerVnId || '';
                document.getElementById('edit_gender').value = customerGender || '';
                document.getElementById('edit_address').value = customerAddress || '';

                // Set the correct radio button (ensure exact comparison)
                if (customerActive === '1' || customerActive === 1) {
                    document.getElementById('edit_active_1').checked = true;
                } else {
                    document.getElementById('edit_active_0').checked = true;
                }

                // Update the form action URL
                editForm.action = `{{ route('cms.customers.index') }}/${customerId}`;
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

                    // Redirect after a short delay
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 500);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
        const printAttendanceModal = document.getElementById('printAttendanceModal');
        if (printAttendanceModal) {
            printAttendanceModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Get data from button attributes
                const customerName = button.getAttribute('data-customer-name');
                const customerVnId = button.getAttribute('data-customer-vn-id');
                const customerMaCoDong = button.getAttribute('data-customer-ma-co-dong');
                const coPhanSoHuu = parseInt(button.getAttribute('data-customer-co-phan-so-huu')) || 0;
                const tongCoPhanDuocUyQuyen = parseInt(button.getAttribute('data-customer-tong-co-phan-duoc-uy-quyen')) || 0;
                const coPhanDaUyQuyen = parseInt(button.getAttribute('data-customer-co-phan-da-uy-quyen')) || 0;
                // Tính tổng số phiếu biểu quyết (cổ phần sở hữu - cổ phần đã ủy quyền + cổ phần được ủy quyền)
                const totalVotes = (coPhanSoHuu - coPhanDaUyQuyen) + tongCoPhanDuocUyQuyen;

                // Sử dụng API để lấy dữ liệu chi tiết
                const customerId = button.getAttribute('data-customer-id');
                const token = localStorage.getItem('access_token_admin');

                if (customerId) {
                    fetch(`/api/admin/data-for-print-phieu-xac-nhan-tham-du?customer_id=${customerId}`, {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.meta.code === 200) {
                            const customerData = data.data;
                            // Cập nhật các trường với dữ liệu từ API
                            let totalVotes = customerData.co_phan_bieu_quyet;;
                            let ownedVotes = customerData.co_phan_so_huu;
                            let tong_co_phan_duoc_uy_quyen = customerData.tong_co_phan_duoc_uy_quyen;
                            if (customerData.uy_quyen_nguoi_ngoai == 1) {
                                totalVotes = customerData.co_phan_da_uy_quyen;
                                ownedVotes = 0;
                                tong_co_phan_duoc_uy_quyen = customerData.co_phan_da_uy_quyen;
                            }
                            document.getElementById('print_customer_name').textContent = customerData.name || '----';
                            document.getElementById('print_vn_id').textContent = customerData.vn_id || '----';
                            document.getElementById('print_ma_co_dong').textContent = customerData.ma_co_dong || '----';
                            document.getElementById('print_co_phan_so_huu').textContent = Number(ownedVotes).toLocaleString('vi-VN');
                            document.getElementById('print_tong_co_phan_duoc_uy_quyen').textContent = Number(tong_co_phan_duoc_uy_quyen).toLocaleString('vi-VN');
                            document.getElementById('print_total_votes').textContent = Number(totalVotes).toLocaleString('vi-VN');
                        } else {
                            console.error('API error:', data.meta.message);
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                    });
                }
            });

            // Handle print button click
            const printButton = document.getElementById('printAttendanceButton');
            if (printButton) {
                printButton.addEventListener('click', function() {
                    // Create a copy of the content for printing
                    const printContent = document.getElementById('attendanceConfirmation').cloneNode(true);

                    // Create a new div to hold the print content
                    const printArea = document.createElement('div');
                    printArea.id = 'attendancePrintArea';
                    printArea.appendChild(printContent);
                    document.body.appendChild(printArea);

                    // Print the content
                    window.print();

                    // Remove the print area after printing
                    setTimeout(() => {
                        document.body.removeChild(printArea);
                    }, 1000);
                });
            }
        }
    });
</script>

<script>
    // Bổ sung vào phần xử lý sự kiện mở modal edit
    $('#editCustomerModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const customerId = button.data('customer-id');
        const customerName = button.data('customer-name');
        const customerEmail = button.data('customer-email');
        const customerPhone = button.data('customer-phone');
        const customerVnId = button.data('customer-vn-id');
        const customerGender = button.data('customer-gender');
        const customerAddress = button.data('customer-address');
        const customerActive = button.data('customer-active');

        // Thêm dữ liệu mới
        const customerMaCoDong = button.data('customer-ma-co-dong');
        const customerCoPhanSoHuu = button.data('customer-co-phan-so-huu');
        const customerTongCoPhanDuocUyQuyen = button.data('customer-tong-co-phan-duoc-uy-quyen');
        const customerTongSoCoDongUyQuyen = button.data('customer-tong-so-co-dong-uy-quyen');
        const customerCoDongNoiBo = button.data('customer-co-dong-noi-bo');
        const customerVnIdIssueDate = button.data('customer-vn-id-issue-date');
        const customerCheckin = button.data('customer-is-checkin');

        const modal = $(this);
        modal.find('#edit_id').val(customerId);
        modal.find('#edit_name').val(customerName);
        modal.find('#edit_email').val(customerEmail);
        modal.find('#edit_phone').val(customerPhone);
        modal.find('#edit_vn_id').val(customerVnId);
        modal.find('#edit_gender').val(customerGender);
        modal.find('#edit_address').val(customerAddress);

        // Set các giá trị mới
        modal.find('#edit_ma_co_dong').val(customerMaCoDong);
        modal.find('#edit_co_phan_so_huu').val(customerCoPhanSoHuu);
        modal.find('#edit_tong_co_phan_duoc_uy_quyen').val(customerTongCoPhanDuocUyQuyen);
        modal.find('#edit_tong_so_co_dong_uy_quyen').val(customerTongSoCoDongUyQuyen);
        modal.find('#edit_vn_id_issue_date').val(customerVnIdIssueDate);

        // Xử lý checkbox
        if (customerCoDongNoiBo == 1) {
            modal.find('#edit_co_dong_noi_bo').prop('checked', true);
        } else {
            modal.find('#edit_co_dong_noi_bo').prop('checked', false);
        }

        // Xử lý radio buttons
        if (customerActive == 1) {
            modal.find('#edit_active_1').prop('checked', true);
        } else {
            modal.find('#edit_active_0').prop('checked', true);
        }
        if (customerCheckin == 1) {
            modal.find('#edit_checkin_1').prop('checked', true);
        } else {
            modal.find('#edit_checkin_0').prop('checked', true);
        }

        modal.find('#editCustomerForm').attr('action', `/cms/customers/${customerId}`);
    });
</script>

<script>
$(document).ready(function() {
    // Handle check-in toggle
    $('.checkin-toggle').on('click', function() {
        const button = $(this);
        const customerId = button.data('customer-id');
        const currentStatus = button.data('checkin-status');
        const newStatus = currentStatus == 1 ? 0 : 1;

        // Show loading state
        const originalContent = button.html();
        button.prop('disabled', true);
        button.html('<span class="d-flex align-items-center gap-1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...</span>');

        // Make AJAX request
        $.ajax({
            url: `{{ route('cms.customers.index') }}/${customerId}/toggle-checkin`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Update button appearance
                button.removeClass('btn-green btn-secondary')
                    .addClass(newStatus == 1 ? 'btn-green' : 'btn-secondary');

                // Update button content
                const newContent = newStatus == 1
                    ? '<span class="d-flex align-items-center gap-1"><svg class="icon icon-tabler icon-tabler-check" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg><span>Đã check-in</span></span>'
                    : '<span class="d-flex align-items-center gap-1"><svg class="icon icon-tabler icon-tabler-x" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg><span>Chưa check-in</span></span>';

                button.html(newContent);
                button.data('checkin-status', newStatus);
                button.prop('disabled', false);

                // Use window.showToast instead of manual toast creation
                window.showToast(response.message, 'success');
            },
            error: function(xhr) {
                // Restore original button state
                button.html(originalContent);
                button.prop('disabled', false);

                // Use window.showToast for error message
                window.showToast('Có lỗi xảy ra, vui lòng thử lại', 'error');
            }
        });
    });
});
</script>
@endpush
