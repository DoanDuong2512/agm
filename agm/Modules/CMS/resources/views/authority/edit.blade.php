<!-- Edit Authority Modal -->
<div class="modal fade" id="editAuthorityModal" tabindex="-1" aria-labelledby="editAuthorityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="editAuthorityForm" action="" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_authority_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAuthorityModalLabel">Chỉnh sửa ủy quyền</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Cổ đông ủy quyền -->
                    <h6 class="mb-3">Cổ đông ủy quyền</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label required">Mã cổ đông</label>
                            <select name="nguoi_uy_quyen" id="edit_nguoi_uy_quyen" class="form-select custom-select" required>
                                <option value="">Chọn mã cổ đông</option>
                            </select>
                            <div class="invalid-feedback nguoi_uy_quyen-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tên cổ đông</label>
                            <input type="text" id="edit_ten_co_dong" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số CCCD/ Hộ chiếu</label>
                        <input type="text" id="edit_vn_id_co_dong" class="form-control" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tổng số cổ phần sở hữu</label>
                        <input type="number" id="edit_co_phan_so_huu" class="form-control" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số cổ phần đã ủy quyền</label>
                        <input type="number" id="edit_co_phan_da_uy_quyen" class="form-control" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số cổ phần có thể ủy quyền</label>
                        <input type="number" id="edit_co_phan_co_the_uy_quyen" class="form-control" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Số cổ phần ủy quyền</label>
                        <input type="number" name="co_phan_uy_quyen" id="edit_co_phan_uy_quyen" class="form-control" min="0" required>
                        <div class="invalid-feedback co_phan_uy_quyen-error"></div>
                    </div>

                    <!-- Cổ đông nhận ủy quyền/ Người nhận ủy quyền -->
                    <h6 class="mb-3">Cổ đông nhận ủy quyền/ Người nhận ủy quyền</h6>
                    <div class="form-check mb-3">
                        <input type="hidden" name="is_shareholder" value="0">
                        <input class="form-check-input" type="checkbox" id="edit_is_shareholder" name="is_shareholder" value="1">
                        <label class="form-check-label" for="edit_is_shareholder">
                            Người nhận ủy quyền là cổ đông
                        </label>
                    </div>

                    <!-- Phần thông tin cho người nhận là cổ đông -->
                    <div id="edit_shareholder_section">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Mã cổ đông người nhận ủy quyền</label>
                                <select name="nguoi_duoc_uy_quyen" id="edit_nguoi_duoc_uy_quyen" class="form-select custom-select">
                                    <option value="">Chọn mã cổ đông</option>
                                </select>
                                <div class="invalid-feedback nguoi_duoc_uy_quyen-error"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tên người nhận ủy quyền</label>
                                <input type="text" id="edit_ten_nguoi_nhan" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số DKSH/ số CCCD</label>
                            <input type="text" id="edit_vn_id_nguoi_nhan" class="form-control" disabled>
                        </div>
                    </div>

                    <!-- Phần thông tin cho người nhận không phải cổ đông -->
                    <div id="edit_non_shareholder_section" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label required">Tên người nhận ủy quyền</label>
                            <input type="text" name="ten_nguoi_duoc_uy_quyen" id="edit_ten_nguoi_duoc_uy_quyen" class="form-control">
                            <div class="invalid-feedback ten_nguoi_duoc_uy_quyen-error"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Số CCCD</label>
                                <input type="text" name="vn_id" id="edit_vn_id" class="form-control">
                                <div class="invalid-feedback vn_id-error"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ngày cấp</label>
                                <input type="date" name="vn_id_issue_date" id="edit_vn_id_issue_date" class="form-control">
                                <div class="invalid-feedback vn_id_issue_date-error"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <textarea name="address" id="edit_address" class="form-control" rows="2"></textarea>
                            <div class="invalid-feedback address-error"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">HỦY</button>
                    <button type="submit" class="btn btn-primary">CẬP NHẬT</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for the edit form
    setTimeout(function() {
        $('#edit_nguoi_uy_quyen, #edit_nguoi_duoc_uy_quyen').select2({
            dropdownParent: $('#editAuthorityModal'),
            placeholder: 'Nhập để tìm kiếm',
            allowClear: true,
            dropdownAutoWidth: true,
            ajax: {
                url: '{{ route("cms.authority.search-customers") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.ma_co_dong + ' - ' + item.name,
                                id: item.id,
                                customer: item
                            };
                        }),
                        pagination: {
                            more: false
                        }
                    };
                },
                cache: true
            },
            minimumInputLength: 1
        });
    }, 500);

    // When selecting authorizer in edit form
    $(document).on('select2:select', '#edit_nguoi_uy_quyen', function(e) {
        var data = e.params.data;
        if (data && data.customer) {
            $('#edit_ten_co_dong').val(data.customer.name);
            $('#edit_vn_id_co_dong').val(data.customer.vn_id);
            $('#edit_co_phan_so_huu').val(data.customer.co_phan_so_huu || 0);
            
            // Lấy thông tin số cổ phần có thể ủy quyền từ API
            $.ajax({
                url: '{{ route("cms.authority.get-available-shares") }}',
                type: 'GET',
                data: {
                    customer_id: data.id
                },
                success: function(response) {
                    $('#edit_co_phan_so_huu').val(response.co_phan_so_huu || 0);
                    $('#edit_co_phan_da_uy_quyen').val(response.co_phan_da_uy_quyen || 0);
                    $('#edit_co_phan_co_the_uy_quyen').val(response.co_phan_co_the_uy_quyen || 0);
                    
                    // Kiểm tra và disable input nhập số cổ phần ủy quyền nếu không còn cổ phần có thể ủy quyền
                    var availableShares = parseInt(response.co_phan_co_the_uy_quyen) || 0;
                    if (availableShares <= 0) {
                        $('#edit_co_phan_uy_quyen').prop('disabled', true).val(0);
                        $('#editAuthorityForm button[type="submit"]').prop('disabled', true);
                        $('.co_phan_uy_quyen-error').text('Không còn cổ phần có thể ủy quyền').show();
                    } else {
                        $('#edit_co_phan_uy_quyen').prop('disabled', false).val('');
                        $('#editAuthorityForm button[type="submit"]').prop('disabled', false);
                        $('.co_phan_uy_quyen-error').text('');
                    }
                },
                error: function() {
                    $('#edit_co_phan_da_uy_quyen').val(0);
                    $('#edit_co_phan_co_the_uy_quyen').val(data.customer.co_phan_so_huu || 0);
                    
                    // Kiểm tra và disable input nhập số cổ phần ủy quyền
                    var availableShares = parseInt(data.customer.co_phan_so_huu) || 0;
                    if (availableShares <= 0) {
                        $('#edit_co_phan_uy_quyen').prop('disabled', true).val(0);
                        $('#editAuthorityForm button[type="submit"]').prop('disabled', true);
                        $('.co_phan_uy_quyen-error').text('Không còn cổ phần có thể ủy quyền').show();
                    } else {
                        $('#edit_co_phan_uy_quyen').prop('disabled', false).val('');
                        $('#editAuthorityForm button[type="submit"]').prop('disabled', false);
                        $('.co_phan_uy_quyen-error').text('');
                    }
                }
            });
            
            // Reset shares input field
            $('#edit_co_phan_uy_quyen').val('').removeClass('is-invalid');
            $('.co_phan_uy_quyen-error').text('');
        }
    });

    // Real-time validation for shares input
    $('#edit_co_phan_uy_quyen').on('input', function() {
        var availableShares = parseInt($('#edit_co_phan_co_the_uy_quyen').val()) || 0;
        var delegatedShares = parseInt($(this).val()) || 0;
        
        if (delegatedShares > availableShares) {
            $(this).addClass('is-invalid');
            $('.co_phan_uy_quyen-error').text('Số cổ phần ủy quyền không thể vượt quá ' + availableShares);
        } else {
            $(this).removeClass('is-invalid');
            $('.co_phan_uy_quyen-error').text('');
        }
    });

    // When selecting authorized person who is a shareholder in edit form
    $(document).on('select2:select', '#edit_nguoi_duoc_uy_quyen', function(e) {
        var data = e.params.data;
        if (data && data.customer) {
            // Kiểm tra nếu người nhận ủy quyền trùng với người ủy quyền
            var nguoiUyQuyen = $('#edit_nguoi_uy_quyen').val();
            if (nguoiUyQuyen && nguoiUyQuyen === data.id) {
                // Hiển thị thông báo lỗi
                $('#edit_nguoi_duoc_uy_quyen').addClass('is-invalid');
                $('.nguoi_duoc_uy_quyen-error').text('Người nhận ủy quyền không thể giống với người ủy quyền');
                // Reset lại giá trị của select box
                setTimeout(function() {
                    $('#edit_nguoi_duoc_uy_quyen').val(null).trigger('change');
                }, 100);
                return;
            }
            
            // Nếu không trùng thì mới điền thông tin
            $('#edit_ten_nguoi_nhan').val(data.customer.name);
            $('#edit_vn_id_nguoi_nhan').val(data.customer.vn_id);
            // Xóa thông báo lỗi nếu có
            $('#edit_nguoi_duoc_uy_quyen').removeClass('is-invalid');
            $('.nguoi_duoc_uy_quyen-error').text('');
        }
    });

    // Toggle between shareholder and non-shareholder sections in edit form
    $('#edit_is_shareholder').change(function() {
        if (this.checked) {
            $('#edit_shareholder_section').show();
            $('#edit_non_shareholder_section').hide();
            $('#edit_nguoi_duoc_uy_quyen').prop('required', true);
            $('input[name="ten_nguoi_duoc_uy_quyen"]').prop('required', false);
            $('input[name="vn_id"]').prop('required', false);
        } else {
            $('#edit_shareholder_section').hide();
            $('#edit_non_shareholder_section').show();
            $('#edit_nguoi_duoc_uy_quyen').prop('required', false);
            $('input[name="ten_nguoi_duoc_uy_quyen"]').prop('required', true);
            $('input[name="vn_id"]').prop('required', true);
        }
    });

    // Load edit authority data
    function loadAuthorityData(authority) {
        // Set form action and id
        $('#editAuthorityForm').attr('action', '{{ route("cms.authority.index") }}/' + authority.id);
        $('#edit_authority_id').val(authority.id);
        $('#edit_co_phan_uy_quyen').val(authority.co_phan_uy_quyen);
        
        // Set is_shareholder checkbox and trigger change event
        $('#edit_is_shareholder').prop('checked', authority.is_shareholder == 1).trigger('change');
        
        // Handle authorizer (nguoi_uy_quyen)
        if (authority.authorizer) {
            var authorizerOption = new Option(
                authority.authorizer.ma_co_dong + ' - ' + authority.authorizer.name, 
                authority.nguoi_uy_quyen, 
                true, 
                true
            );
            $('#edit_nguoi_uy_quyen').empty().append(authorizerOption).trigger('change');
            
            // Fill in authorizer details
            $('#edit_ten_co_dong').val(authority.authorizer.name);
            $('#edit_vn_id_co_dong').val(authority.authorizer.vn_id);
            $('#edit_co_phan_so_huu').val(authority.authorizer.co_phan_so_huu || 0);
            
            // Lấy thông tin số cổ phần có thể ủy quyền từ API
            $.ajax({
                url: '{{ route("cms.authority.get-available-shares") }}',
                type: 'GET',
                data: {
                    customer_id: authority.nguoi_uy_quyen
                },
                success: function(response) {
                    // Khi edit, cần cộng thêm số cổ phần đang ủy quyền vào số cổ phần có thể ủy quyền
                    var total = response.co_phan_co_the_uy_quyen + parseInt(authority.co_phan_uy_quyen || 0);
                    
                    $('#edit_co_phan_so_huu').val(response.co_phan_so_huu || 0);
                    $('#edit_co_phan_da_uy_quyen').val(response.co_phan_da_uy_quyen || 0);
                    $('#edit_co_phan_co_the_uy_quyen').val(total);
                    
                    // Kiểm tra và disable input nhập số cổ phần ủy quyền nếu không còn cổ phần có thể ủy quyền
                    if (total <= 0) {
                        $('#edit_co_phan_uy_quyen').prop('disabled', true).val(0);
                        $('#editAuthorityForm button[type="submit"]').prop('disabled', true);
                        $('.co_phan_uy_quyen-error').text('Không còn cổ phần có thể ủy quyền').show();
                    } else {
                        $('#edit_co_phan_uy_quyen').prop('disabled', false).val(authority.co_phan_uy_quyen);
                        $('#editAuthorityForm button[type="submit"]').prop('disabled', false);
                        $('.co_phan_uy_quyen-error').text('');
                    }
                },
                error: function() {
                    $('#edit_co_phan_da_uy_quyen').val(0);
                    $('#edit_co_phan_co_the_uy_quyen').val(authority.authorizer.co_phan_so_huu || 0);
                }
            });
        }
        
        // Handle authorized person (nguoi_duoc_uy_quyen) based on is_shareholder
        if (authority.is_shareholder == 1 && authority.authorized) {
            var authorizedOption = new Option(
                authority.authorized.ma_co_dong + ' - ' + authority.authorized.name, 
                authority.nguoi_duoc_uy_quyen, 
                true, 
                true
            );
            $('#edit_nguoi_duoc_uy_quyen').empty().append(authorizedOption).trigger('change');
            
            // Fill in authorized details
            $('#edit_ten_nguoi_nhan').val(authority.authorized.name);
            $('#edit_vn_id_nguoi_nhan').val(authority.authorized.vn_id);
        } else {
            // Fill non-shareholder fields
            $('#edit_ten_nguoi_duoc_uy_quyen').val(authority.ten_nguoi_duoc_uy_quyen);
            $('#edit_vn_id').val(authority.vn_id);
            $('#edit_vn_id_issue_date').val(authority.vn_id_issue_date ? authority.vn_id_issue_date.split('T')[0] : '');
            $('#edit_address').val(authority.address);
        }
    }

    // Edit authority AJAX form submission
    $('#editAuthorityForm').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        
        // Kiểm tra nếu người ủy quyền và người nhận ủy quyền giống nhau
        var isShareholderChecked = $('#edit_is_shareholder').is(':checked');
        var nguoiUyQuyen = $('#edit_nguoi_uy_quyen').val();
        
        if (isShareholderChecked) {
            var nguoiDuocUyQuyen = $('#edit_nguoi_duoc_uy_quyen').val();
            if (nguoiUyQuyen && nguoiDuocUyQuyen && nguoiUyQuyen === nguoiDuocUyQuyen) {
                $('#edit_nguoi_duoc_uy_quyen').addClass('is-invalid');
                $('.nguoi_duoc_uy_quyen-error').text('Người nhận ủy quyền không thể giống với người ủy quyền');
                return false;
            }
        }
        
        // Ensure proper field values based on the selected mode
        var isShareholderChecked = $('#edit_is_shareholder').is(':checked');
        if (isShareholderChecked) {
            // If shareholder is selected, set values from shareholder fields to non-shareholder fields
            form.find('input[name="ten_nguoi_duoc_uy_quyen"]').val($('#edit_ten_nguoi_nhan').val());
            form.find('input[name="vn_id"]').val($('#edit_vn_id_nguoi_nhan').val());
            form.find('input[name="vn_id_issue_date"]').val('');
            form.find('textarea[name="address"]').val('');
        } else {
            // If non-shareholder is selected, make sure nguoi_duoc_uy_quyen is empty
            form.find('select[name="nguoi_duoc_uy_quyen"]').val('');
        }
        
        var formData = form.serialize();
        
        // Validate shares count
        var availableShares = parseInt($('#edit_co_phan_co_the_uy_quyen').val()) || 0;
        var delegatedShares = parseInt($('#edit_co_phan_uy_quyen').val()) || 0;
        
        if (delegatedShares > availableShares) {
            $('#edit_co_phan_uy_quyen').addClass('is-invalid');
            $('.co_phan_uy_quyen-error').text('Số cổ phần ủy quyền không thể vượt quá số cổ phần có thể ủy quyền (' + availableShares + ')');
            return false;
        }
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            beforeSend: function() {
                form.find('.invalid-feedback').text('');
                form.find('.is-invalid').removeClass('is-invalid');
                $('#editAuthorityForm button[type="submit"]').attr('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang lưu...');
            },
            success: function(response) {
                if (response.message) {
                    if (window.showToast) {
                        window.showToast(response.message, 'success');
                    } else {
                        toastr.success(response.message);
                    }
                }
                if (response.redirect) {
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1000);
                }
            },
            error: function(xhr) {
                $('#editAuthorityForm button[type="submit"]').attr('disabled', false).text('Cập nhật');
                
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';
                    
                    // Display individual field errors
                    $.each(errors, function(key, value) {
                        form.find('[name="' + key + '"]').addClass('is-invalid');
                        form.find('.' + key + '-error').text(value[0]);
                        errorMessage += value[0] + '<br>';
                    });
                    
                    // Show toast with all errors
                    if (window.showToast) {
                        window.showToast(errorMessage, 'error');
                    } else {
                        toastr.error('Đã xảy ra lỗi xác thực. Vui lòng kiểm tra lại thông tin.');
                    }
                } else {
                    var errorMsg = 'Đã xảy ra lỗi. Vui lòng thử lại sau.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    
                    if (window.showToast) {
                        window.showToast(errorMsg, 'error');
                    } else {
                        toastr.error(errorMsg);
                    }
                }
            },
            complete: function() {
                $('#editAuthorityForm button[type="submit"]').attr('disabled', false).text('Cập nhật');
            }
        });
    });

    // Initialize modal and expose the loadAuthorityData function
    $('#editAuthorityModal').on('shown.bs.modal', function() {
        $(this).find('.modal-content').css('opacity', 1);
    });
    
    // Clear select options when modal is about to be shown
    $('#editAuthorityModal').on('show.bs.modal', function() {
        $('#edit_nguoi_uy_quyen, #edit_nguoi_duoc_uy_quyen').empty();
    });

    window.loadAuthorityData = loadAuthorityData;
});
</script>