<!-- Authority Form Modal -->
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 5px;
        border: 1px solid #ddd;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .select2-container {
        width: 100%!important;
    }
    /* Fix dropdown positioning in modal */
    .select2-dropdown {
        z-index: 10000;
    }
    .select2-container--open .select2-dropdown {
        left: 0 !important;
    }
    body .select2-container--open .select2-dropdown {
        z-index: 10000;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

<div class="modal fade" id="createAuthorityModal" tabindex="-1" aria-labelledby="createAuthorityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="opacity: 0">
            <form id="createAuthorityForm" action="{{ route('cms.authority.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createAuthorityModalLabel">Thêm ủy quyền</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Cổ đông ủy quyền -->
                    <h2 class="mb-3">Cổ đông ủy quyền</h2>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label required">Mã cổ đông</label>
                            <select name="nguoi_uy_quyen" id="nguoi_uy_quyen" class="form-select custom-select" required>
                                <option value="">Chọn mã cổ đông</option>
                            </select>
                            <div class="invalid-feedback nguoi_uy_quyen-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tên cổ đông</label>
                            <input type="text" id="ten_co_dong" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số CCCD/ Hộ chiếu</label>
                        <input type="text" id="vn_id_co_dong" class="form-control" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tổng số cổ phần sở hữu</label>
                        <input type="number" id="co_phan_so_huu" class="form-control" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số cổ phần đã ủy quyền</label>
                        <input type="number" id="co_phan_da_uy_quyen" class="form-control" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số cổ phần có thể ủy quyền</label>
                        <input type="number" id="co_phan_co_the_uy_quyen" class="form-control" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Số cổ phần ủy quyền</label>
                        <input type="number" name="co_phan_uy_quyen" id="co_phan_uy_quyen" class="form-control" min="0" required>
                        <div class="invalid-feedback co_phan_uy_quyen-error"></div>
                    </div>

                    <!-- Cổ đông nhận ủy quyền/ Người nhận ủy quyền -->
                    <h2 class="mb-3">Cổ đông nhận ủy quyền/ Người nhận ủy quyền</h2>
                    <div class="form-check mb-3">
                        <input type="hidden" name="is_shareholder" value="0">
                        <input class="form-check-input" type="checkbox" id="is_shareholder" name="is_shareholder" value="1">
                        <label class="form-check-label" for="is_shareholder">
                            Người nhận ủy quyền là cổ đông
                        </label>
                    </div>

                    <!-- Phần thông tin cho người nhận là cổ đông -->
                    <div id="shareholder_section">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Mã cổ đông người nhận ủy quyền</label>
                                <select name="nguoi_duoc_uy_quyen" id="nguoi_duoc_uy_quyen" class="form-select custom-select">
                                    <option value="">Chọn mã cổ đông</option>
                                </select>
                                <div class="invalid-feedback nguoi_duoc_uy_quyen-error"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tên người nhận ủy quyền</label>
                                <input type="text" id="ten_nguoi_nhan" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số DKSH/ số CCCD</label>
                            <input type="text" id="vn_id_nguoi_nhan" class="form-control" disabled>
                        </div>
                    </div>

                    <!-- Phần thông tin cho người nhận không phải cổ đông -->
                    <div id="non_shareholder_section" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label required">Tên người nhận ủy quyền</label>
                            <input type="text" name="ten_nguoi_duoc_uy_quyen" class="form-control">
                            <div class="invalid-feedback ten_nguoi_duoc_uy_quyen-error"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Số CCCD</label>
                                <input type="text" name="vn_id" class="form-control">
                                <div class="invalid-feedback vn_id-error"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ngày cấp</label>
                                <input type="date" name="vn_id_issue_date" class="form-control">
                                <div class="invalid-feedback vn_id_issue_date-error"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <textarea name="address" class="form-control" rows="2"></textarea>
                            <div class="invalid-feedback address-error"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">HỦY</button>
                    <button type="submit" class="btn btn-primary">LƯU</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the select2 for the shareholder selector with searching
    setTimeout(function() {
        $('#nguoi_uy_quyen, #nguoi_duoc_uy_quyen').select2({
            dropdownParent: $('#createAuthorityModal'),
            placeholder: 'Nhập để tìm kiếm',
            allowClear: true,
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

    // When selecting authorizer, fill in their details
    $(document).on('select2:select', '#nguoi_uy_quyen', function(e) {
        var data = e.params.data;
        if (data && data.customer) {
            $('#ten_co_dong').val(data.customer.name);
            $('#vn_id_co_dong').val(data.customer.vn_id);
            $('#co_phan_so_huu').val(data.customer.co_phan_so_huu || 0);
            
            // Lấy thông tin số cổ phần có thể ủy quyền từ API
            $.ajax({
                url: '{{ route("cms.authority.get-available-shares") }}',
                type: 'GET',
                data: {
                    customer_id: data.id
                },
                success: function(response) {
                    $('#co_phan_so_huu').val(response.co_phan_so_huu || 0);
                    $('#co_phan_da_uy_quyen').val(response.co_phan_da_uy_quyen || 0);
                    $('#co_phan_co_the_uy_quyen').val(response.co_phan_co_the_uy_quyen || 0);
                    
                    // Kiểm tra và disable input nhập số cổ phần ủy quyền nếu không còn cổ phần có thể ủy quyền
                    var availableShares = parseInt(response.co_phan_co_the_uy_quyen) || 0;
                    if (availableShares <= 0) {
                        $('#co_phan_uy_quyen').prop('disabled', true).val(0);
                        $('#createAuthorityForm button[type="submit"]').prop('disabled', true);
                        $('.co_phan_uy_quyen-error').text('Không còn cổ phần có thể ủy quyền').show();
                    } else {
                        $('#co_phan_uy_quyen').prop('disabled', false).val('');
                        $('#createAuthorityForm button[type="submit"]').prop('disabled', false);
                        $('.co_phan_uy_quyen-error').text('');
                    }
                },
                error: function() {
                    $('#co_phan_da_uy_quyen').val(0);
                    $('#co_phan_co_the_uy_quyen').val(data.customer.co_phan_so_huu || 0);
                    // Kiểm tra và disable input nhập số cổ phần ủy quyền
                    var availableShares = parseInt(data.customer.co_phan_so_huu) || 0;
                    if (availableShares <= 0) {
                        $('#co_phan_uy_quyen').prop('disabled', true).val(0);
                        $('#createAuthorityForm button[type="submit"]').prop('disabled', true);
                        $('.co_phan_uy_quyen-error').text('Không còn cổ phần có thể ủy quyền').show();
                    } else {
                        $('#co_phan_uy_quyen').prop('disabled', false).val('');
                        $('#createAuthorityForm button[type="submit"]').prop('disabled', false);
                        $('.co_phan_uy_quyen-error').text('');
                    }
                }
            });
            
            // Reset shares input field
            $('#co_phan_uy_quyen').val('').removeClass('is-invalid');
            $('.co_phan_uy_quyen-error').text('');
        }
    });

    // When selecting authorized person who is a shareholder, fill in their details
    $(document).on('select2:select', '#nguoi_duoc_uy_quyen', function(e) {
        var data = e.params.data;
        if (data && data.customer) {
            // Kiểm tra nếu người nhận ủy quyền trùng với người ủy quyền
            var nguoiUyQuyen = $('#nguoi_uy_quyen').val();
            if (nguoiUyQuyen && nguoiUyQuyen === data.id) {
                // Hiển thị thông báo lỗi
                $('#nguoi_duoc_uy_quyen').addClass('is-invalid');
                $('.nguoi_duoc_uy_quyen-error').text('Người nhận ủy quyền không thể giống với người ủy quyền');
                // Reset lại giá trị của select box
                setTimeout(function() {
                    $('#nguoi_duoc_uy_quyen').val(null).trigger('change');
                }, 100);
                return;
            }
            
            // Nếu không trùng thì mới điền thông tin
            $('#ten_nguoi_nhan').val(data.customer.name);
            $('#vn_id_nguoi_nhan').val(data.customer.vn_id);
            // Xóa thông báo lỗi nếu có
            $('#nguoi_duoc_uy_quyen').removeClass('is-invalid');
            $('.nguoi_duoc_uy_quyen-error').text('');
        }
    });

    // Toggle between shareholder and non-shareholder sections
    $('#is_shareholder').change(function() {
        if (this.checked) {
            $('#shareholder_section').show();
            $('#non_shareholder_section').hide();
            $('#nguoi_duoc_uy_quyen').prop('required', true);
            $('input[name="ten_nguoi_duoc_uy_quyen"]').prop('required', false).val('');
            $('input[name="vn_id"]').prop('required', false).val('');
            $('input[name="vn_id_issue_date"]').val('');
            $('textarea[name="address"]').val('');
        } else {
            $('#shareholder_section').hide();
            $('#non_shareholder_section').show();
            $('#nguoi_duoc_uy_quyen').prop('required', false).val(null).trigger('change');
            $('input[name="ten_nguoi_duoc_uy_quyen"]').prop('required', true);
            $('input[name="vn_id"]').prop('required', true);
        }
    });

    // Form submission handling with AJAX
    $('#createAuthorityForm').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        
        // Kiểm tra nếu người ủy quyền và người nhận ủy quyền giống nhau
        var isShareholderChecked = $('#is_shareholder').is(':checked');
        var nguoiUyQuyen = $('#nguoi_uy_quyen').val();
        
        if (isShareholderChecked) {
            var nguoiDuocUyQuyen = $('#nguoi_duoc_uy_quyen').val();
            if (nguoiUyQuyen && nguoiDuocUyQuyen && nguoiUyQuyen === nguoiDuocUyQuyen) {
                $('#nguoi_duoc_uy_quyen').addClass('is-invalid');
                $('.nguoi_duoc_uy_quyen-error').text('Người nhận ủy quyền không thể giống với người ủy quyền');
                return false;
            }
        }
        
        // Ensure non-shareholder fields have proper values when that option is not selected
        var isShareholderChecked = $('#is_shareholder').is(':checked');
        if (isShareholderChecked) {
            // If shareholder is selected, set values from shareholder fields to non-shareholder fields
            form.find('input[name="ten_nguoi_duoc_uy_quyen"]').val($('#ten_nguoi_nhan').val());
            form.find('input[name="vn_id"]').val($('#vn_id_nguoi_nhan').val());
            form.find('input[name="vn_id_issue_date"]').val('');
            form.find('textarea[name="address"]').val('');
        } else {
            // If non-shareholder is selected, validate required fields
            if (!form.find('input[name="ten_nguoi_duoc_uy_quyen"]').val().trim()) {
                form.find('input[name="ten_nguoi_duoc_uy_quyen"]').addClass('is-invalid');
                form.find('.ten_nguoi_duoc_uy_quyen-error').text('Vui lòng nhập tên người nhận ủy quyền');
                return false;
            }
            
            if (!form.find('input[name="vn_id"]').val().trim()) {
                form.find('input[name="vn_id"]').addClass('is-invalid');
                form.find('.vn_id-error').text('Vui lòng nhập số CCCD');
                return false;
            }
            
            // Set empty value for shareholder field
            form.find('select[name="nguoi_duoc_uy_quyen"]').val('');
        }
        
        var formData = form.serialize();
        console.log(formData);
        
        // Validate shares count
        var availableShares = parseInt($('#co_phan_co_the_uy_quyen').val()) || 0;
        var delegatedShares = parseInt($('#co_phan_uy_quyen').val()) || 0;
        
        if (delegatedShares > availableShares) {
            $('#co_phan_uy_quyen').addClass('is-invalid');
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
                $('#createAuthorityForm button[type="submit"]').attr('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang lưu...');
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
                $('#createAuthorityForm button[type="submit"]').attr('disabled', false).text('Lưu');
                
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
                $('#createAuthorityForm button[type="submit"]').attr('disabled', false).text('Lưu');
            }
        });
    });

    // Initialize modal
    $('#createAuthorityModal').on('shown.bs.modal', function() {
        $(this).find('.modal-content').css('opacity', 1);
        $('#nguoi_uy_quyen').val(null).trigger('change');
        $('#nguoi_duoc_uy_quyen').val(null).trigger('change');
        $('#createAuthorityForm')[0].reset();
        $('#ten_co_dong, #vn_id_co_dong, #co_phan_so_huu, #ten_nguoi_duoc_uy_quyen, #vn_id_nguoi_nhan').val('');
        
        // Default state
        $('#is_shareholder').prop('checked', false).trigger('change');
    });
    
    // Clear select options when modal is about to be shown
    $('#createAuthorityModal').on('show.bs.modal', function() {
        $('#nguoi_uy_quyen, #nguoi_duoc_uy_quyen').empty().trigger('change');
    });

    // Real-time validation for shares input
    $('#co_phan_uy_quyen').on('input', function() {
        var availableShares = parseInt($('#co_phan_co_the_uy_quyen').val()) || 0;
        var delegatedShares = parseInt($(this).val()) || 0;
        
        if (delegatedShares > availableShares) {
            $(this).addClass('is-invalid');
            $('.co_phan_uy_quyen-error').text('Số cổ phần ủy quyền không thể vượt quá số cổ phần có thể ủy quyền (' + availableShares + ')');
        } else {
            $(this).removeClass('is-invalid');
            $('.co_phan_uy_quyen-error').text('');
        }
    });
});
</script>