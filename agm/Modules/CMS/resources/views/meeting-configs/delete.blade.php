<!-- Delete Config Modal -->
<div class="modal fade" id="deleteConfigModal" tabindex="-1" aria-labelledby="deleteConfigModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="opacity: 0">
            <form id="deleteConfigForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteConfigModalLabel">Xác nhận xóa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                        <h5>Bạn có chắc chắn muốn xóa cấu hình này?</h5>
                        <p class="text-muted" id="delete_config_key"></p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </div>
            </form>
        </div>
    </div>
</div>