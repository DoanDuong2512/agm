<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="createUserForm" action="{{ route('cms.users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Thêm người dùng mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Tên</label>
                        <input type="text" name="name" class="form-control" required>
                        <div class="invalid-feedback name-error"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Email</label>
                        <input type="email" name="email" class="form-control" required>
                        <div class="invalid-feedback email-error"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" required>
                        <div class="invalid-feedback password-error"></div>
                    </div>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>