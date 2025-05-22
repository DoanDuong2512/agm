<!-- Create Vote Item Modal -->
<div class="modal fade" id="createVoteItemModal" tabindex="-1" aria-labelledby="createVoteItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="opacity: 0">
            <form id="createVoteItemForm" action="{{ route('cms.vote-items.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createVoteItemModalLabel">Thêm nội dung biểu quyết</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Phiếu biểu quyết</label>
                        <select name="vote_id" class="form-select" required>
                            <option value="">Chọn phiếu biểu quyết</option>
                            @foreach($votes as $vote)
                                <option value="{{ $vote->id }}">{{ $vote->ten_phieu }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback vote_id-error"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label required">Nội dung</label>
                        <textarea name="noi_dung" class="form-control" required rows="4"></textarea>
                        <div class="invalid-feedback noi_dung-error"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Vị trí ứng cử</label>
                        <input type="text" name="vi_tri_ung_cu" class="form-control">
                        <div class="invalid-feedback vi_tri_ung_cu-error"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tỉ lệ chấp thuận (%)</label>
                        <input type="number" name="ti_le_chap_thuan" class="form-control" min="0" max="100" step="0.01">
                        <div class="invalid-feedback ti_le_chap_thuan-error"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tổng cổ phần biểu quyết</label>
                        <input type="number" name="tong_co_phan_bieu_quyet" class="form-control" min="0">
                        <div class="invalid-feedback tong_co_phan_bieu_quyet-error"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tổng số người biểu quyết</label>
                        <input type="number" name="tong_so_nguoi_bieu_quyet" class="form-control" min="0">
                        <div class="invalid-feedback tong_so_nguoi_bieu_quyet-error"></div>
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
