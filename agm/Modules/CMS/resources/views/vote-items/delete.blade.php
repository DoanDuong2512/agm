<!-- Delete Vote Item Modal -->
<div class="modal fade" id="deleteVoteItemModal" tabindex="-1" aria-labelledby="deleteVoteItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <form id="deleteVoteItemForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteVoteItemModalLabel">Xác nhận xóa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                        <h5>Bạn có chắc chắn muốn xóa phiếu này?</h5>
                        <p class="text-muted" id="delete_vote_item_content"></p>
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
