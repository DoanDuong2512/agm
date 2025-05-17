<!-- Delete Authority Modal -->
<div class="modal fade" id="deleteAuthorityModal" tabindex="-1" aria-labelledby="deleteAuthorityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deleteAuthorityForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAuthorityModalLabel">Xác nhận xóa ủy quyền</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa ủy quyền của cổ đông <strong id="delete_authority_name"></strong> cho <strong id="delete_authority_receiver"></strong>?</p>
                    <p class="text-danger">Hành động này không thể hoàn tác!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">HỦY</button>
                    <button type="submit" class="btn btn-danger">XÓA</button>
                </div>
            </form>
        </div>
    </div>
</div>