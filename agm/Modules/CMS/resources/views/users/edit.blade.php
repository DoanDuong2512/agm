<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Chỉnh sửa người dùng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Tên</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                        <div class="invalid-feedback name-error"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                        <div class="invalid-feedback email-error"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="password" class="form-control">
                        <small class="form-text text-muted">Để trống nếu không muốn thay đổi mật khẩu</small>
                        <div class="invalid-feedback password-error"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Trạng thái</label>
                        <div class="form-selectgroup">
                            <label class="form-selectgroup-item">
                                <input type="radio" name="is_active" id="edit_active_1" value="1" class="form-selectgroup-input">
                                <span class="form-selectgroup-label">Hoạt động</span>
                            </label>
                            <label class="form-selectgroup-item">
                                <input type="radio" name="is_active" id="edit_active_0" value="0" class="form-selectgroup-input">
                                <span class="form-selectgroup-label">Không hoạt động</span>
                            </label>
                        </div>
                        <div class="invalid-feedback is_active-error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>