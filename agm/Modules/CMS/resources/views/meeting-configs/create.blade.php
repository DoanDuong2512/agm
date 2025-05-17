<!-- Create Config Modal -->
<div class="modal fade" id="createConfigModal" tabindex="-1" aria-labelledby="createConfigModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="opacity: 0">
            <form id="createConfigForm" action="{{ route('cms.meeting-configs.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createConfigModalLabel">Thêm cấu hình mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="key" class="form-label required">Key</label>
                        <input type="text" class="form-control" id="key" name="key" required>
                        <div class="invalid-feedback key-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="value" class="form-label">Value</label>
                        <input type="text" class="form-control" id="value" name="value">
                        <div class="invalid-feedback value-error"></div>
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