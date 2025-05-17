<!-- Create Customer Modal -->
<div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="createCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="opacity: 0">
            <form id="createCustomerForm" action="{{ route('cms.customers.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createCustomerModalLabel">Thêm cổ đông mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Tên</label>
                        <input type="text" name="name" class="form-control" required>
                        <div class="invalid-feedback name-error"></div>
                    </div>
                    
                    <!-- Căn cước + Ngày cấp -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label required">Căn cước</label>
                            <input type="text" name="vn_id" class="form-control" required>
                            <div class="invalid-feedback vn_id-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ngày cấp</label>
                            <input type="date" 
                                   name="vn_id_issue_date" 
                                   class="form-control"
                                   placeholder="YYYY-MM-DD"
                                   pattern="\d{4}-\d{2}-\d{2}"
                                   title="Vui lòng nhập đúng định dạng YYYY-MM-DD">
                            <div class="invalid-feedback vn_id_issue_date-error"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                        <div class="invalid-feedback email-error"></div>
                    </div>
                    
                    <!-- Điện thoại + Giới tính -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Điện thoại</label>
                            <input type="text" name="phone" class="form-control">
                            <div class="invalid-feedback phone-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Giới tính</label>
                            <select name="gender" class="form-select">
                                <option value="">Chọn giới tính</option>
                                <option value="male">Nam</option>
                                <option value="female">Nữ</option>
                                <option value="other">Khác</option>
                            </select>
                            <div class="invalid-feedback gender-error"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                        <div class="invalid-feedback address-error"></div>
                    </div>
                    
                    <!-- Mã cổ đông + Cổ phần sở hữu -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Mã cổ đông</label>
                            <input type="text" name="ma_co_dong" class="form-control">
                            <div class="invalid-feedback ma_co_dong-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cổ phần sở hữu</label>
                            <input type="number" name="co_phan_so_huu" class="form-control" min="0">
                            <div class="invalid-feedback co_phan_so_huu-error"></div>
                        </div>
                    </div>
                    
                    <!-- Tổng cổ phần được ủy quyền + Tổng số cổ đông ủy quyền -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tổng cổ phần được ủy quyền</label>
                            <input type="number" name="tong_co_phan_duoc_uy_quyen" class="form-control" min="0">
                            <div class="invalid-feedback tong_co_phan_duoc_uy_quyen-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tổng số cổ đông ủy quyền</label>
                            <input type="number" name="tong_so_co_dong_uy_quyen" class="form-control" min="0">
                            <div class="invalid-feedback tong_so_co_dong_uy_quyen-error"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="co_dong_noi_bo" value="1">
                            <span class="form-check-label">Cổ đông nội bộ</span>
                        </label>
                        <div class="invalid-feedback co_dong_noi_bo-error"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label required">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" required>
                        <div class="invalid-feedback password-error"></div>
                    </div>
                    
                    <!-- Trạng thái -->
                    <div class="mb-3">
                        <label class="form-label required">Trạng thái</label>
                        <div class="form-selectgroup">
                            <label class="form-selectgroup-item">
                                <input type="radio" name="is_active" value="1" class="form-selectgroup-input" checked>
                                <span class="form-selectgroup-label">Hoạt động</span>
                            </label>
                            <label class="form-selectgroup-item">
                                <input type="radio" name="is_active" value="0" class="form-selectgroup-input">
                                <span class="form-selectgroup-label">Không hoạt động</span>
                            </label>
                        </div>
                        <div class="invalid-feedback is_active-error"></div>
                    </div>
                    
                    <!-- Trạng thái check-in -->
                    <div class="mb-3">
                        <label class="form-label required">Trạng thái check-in</label>
                        <div class="form-selectgroup">
                            <label class="form-selectgroup-item">
                                <input type="radio" name="is_checkin" value="1" class="form-selectgroup-input">
                                <span class="form-selectgroup-label">Đã check-in</span>
                            </label>
                            <label class="form-selectgroup-item">
                                <input type="radio" name="is_checkin" value="0" class="form-selectgroup-input" checked>
                                <span class="form-selectgroup-label">Chưa check-in</span>
                            </label>
                        </div>
                        <div class="invalid-feedback is_checkin-error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>