<div class="modal modal-blur fade" id="printAttendanceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">In phiếu tham dự</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="attendanceConfirmation" class="attendance-confirmation">
                    <div class="mb-4 text-left">
                        <img src="{{ asset('images/logo_elcom_1.svg') }}" alt="Elcom Logo" class="logo-image" />
                    </div>
                    
                    <div class="text-center mb-4">
                        <h1 class="doc-header fw-bold">GIẤY XÁC NHẬN THAM DỰ</h1>
                        <h2 class="doc-sub-header fw-bold">ĐẠI HỘI CỔ ĐÔNG THƯỜNG NIÊN NĂM 2025</h2>
                    </div>
                    
                    <table class="table table-borderless confirmation-table mb-4">
                        <tbody>
                            <tr>
                                <td style="width: 35%">Họ và tên Cổ đông/Đại diện:</td>
                                <td id="print_customer_name">----</td>
                            </tr>
                            <tr>
                                <td>Số cổ phần sở hữu:</td>
                                <td>
                                    <span id="print_co_phan_so_huu">0</span>
                                    <span class="ms-2">Cổ phần</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Số cổ phần được ủy quyền:</td>
                                <td>
                                    <span id="print_tong_co_phan_duoc_uy_quyen">0</span>
                                    <span class="ms-2">Cổ phần</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Tổng số cổ phần biểu quyết:</td>
                                <td>
                                    <span id="print_total_votes">0</span>
                                    <span class="ms-2">Cổ phần</span>
                                </td>
                            </tr>
                            <tr>
                                <td>CCCD/CMND/ĐKDN số:</td>
                                <td id="print_vn_id">----</td>
                            </tr>
                            <tr>
                                <td>MCĐ:</td>
                                <td id="print_ma_co_dong">----</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="mb-4 text-left">
                        <p>Căn cứ Thư mời họp Đại hội đồng cổ đông thường niên năm 2025, tôi xác nhận tham dự Đại hội của Công ty Cổ phần Công nghệ - Viễn thông ELCOM được tổ chức vào ngày 24/04/2025.</p>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <div class="bottom-part text-center" style="width: 300px">
                            <p class="fst-italic">Hà Nội, ngày 24 tháng 04 năm 2025</p>
                            <p class="fw-bold text-nowrap">CỔ ĐÔNG/NGƯỜI ĐƯỢC ỦY QUYỀN</p>
                            <p class="fst-italic">(Ký và ghi rõ họ tên)</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="printAttendanceButton">
                    <x-tabler-icon name="printer" size="16" class="me-1" />
                    In phiếu
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.attendance-confirmation {
    font-family: 'Times New Roman', Times, serif;
    line-height: 1.6;
    max-width: 800px;
    margin: 0 auto;
    font-size: 16px; /* Base font size for all content */
}

.attendance-confirmation * {
    font-family: 'Times New Roman', Times, serif;
    font-size: 16px;
}

.doc-header {
    font-size: 24px;
    margin-bottom: 10px;
    font-weight: 700;
}

.doc-sub-header {
    font-size: 18px;
    font-weight: 700;
}

.logo-image {
    height: 48px; /* Equivalent to h-12 in Tailwind */
    margin-bottom: 1rem;
    display: block;
}

.confirmation-table {
    border-spacing: 0 2px;
    width: 100%;
    text-align: left;
    border-collapse: separate;
}

.confirmation-table tr {
    border-bottom: 1px solid #eaeaea;
    margin-bottom: 2px;
}

.confirmation-table td {
    padding-top: 4px; /* py-1 in Tailwind */
    padding-bottom: 4px; /* py-1 in Tailwind */
    vertical-align: top;
    padding-left: 0;
}

.confirmation-table td:first-child {
    width: 33.333%; /* w-1/3 in Tailwind */
}

.bottom-part {
    padding-right: 8px;
}

.bottom-part > p {
    margin: 0;
}

.bottom-part > p.fst-italic {
    font-style: italic;
}

.bottom-part > p.fw-bold {
    font-weight: 700;
}

@media print {
    @page {
        size: A4;
        margin: 1cm;
    }
    
    body * {
        visibility: hidden;
    }
    
    .modal, .modal-backdrop {
        display: none !important;
    }
    
    #attendancePrintArea, #attendancePrintArea * {
        visibility: visible;
        font-family: 'Times New Roman', Times, serif !important;
    }
    
    #attendancePrintArea {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: auto;
        overflow: visible;
        background-color: #fff;
        padding: 2rem; /* p-8 in Tailwind */
        font-size: 16px;
    }
    
    /* Ensure table displays correctly */
    #attendancePrintArea table {
        display: table;
        border-collapse: separate;
        border-spacing: 0 2px;
        width: 100%;
    }
    
    #attendancePrintArea tr {
        display: table-row;
        margin-bottom: 2px;
        page-break-inside: avoid;
        break-inside: avoid;
    }
    
    #attendancePrintArea td {
        display: table-cell;
        padding-top: 2px; /* py-1 in Tailwind */
        padding-bottom: 2px; /* py-1 in Tailwind */
        vertical-align: top;
    }
    
    .logo-image {
        height: 48px;
        margin-bottom: 1rem;
    }
    
    .bottom-part > p {
        margin: 0;
    }
}
</style>