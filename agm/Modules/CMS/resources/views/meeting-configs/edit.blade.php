<!-- Edit Config Modal -->
<div class="modal fade" id="editConfigModal" tabindex="-1" aria-labelledby="editConfigModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="opacity: 0">
            <form id="editConfigForm" action="" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_config_id" name="config_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editConfigModalLabel">Chỉnh sửa cấu hình</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_key" class="form-label required">Key</label>
                        <input type="text" class="form-control" id="edit_key" name="key" required>
                        <div class="invalid-feedback key-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_value" class="form-label">Value</label>
                        <input type="text" class="form-control" id="edit_value" name="value">
                        <div class="invalid-feedback value-error"></div>
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