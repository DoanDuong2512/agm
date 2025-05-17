@extends('cms::layouts.blank')

@section('content')
<div id="printContent" class="bbkt-content" style="max-width: 650px; margin: 0 auto; font-size: 16px;">
    <div class="mb-4">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    <p class="fw-bold mb-0">CÔNG TY CỔ PHẦN</p>
                    <p class="fw-bold mb-0 company-name">CÔNG NGHỆ - VIỄN THÔNG ELCOM</p>
                    <p class="mb-0">-----***-----</p>
                </td>
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    <p class="fw-bold mb-0 country-name">CỘNG HOÀ XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
                    <p class="fw-bold mb-0">Độc lập - Tự do - Hạnh phúc</p>
                    <p class="mb-0">-------o0o-------</p>
                </td>
            </tr>
        </table>

        <div class="text-end mt-3">
            <p class="fst-italic mb-0">Hà Nội, ngày <span id="ngay"></span> tháng <span id="thang"></span> năm <span id="nam"></span></p>
        </div>
    </div>

    <div class="title text-center mb-5">
        <p class="fw-bold mb-0" style="font-size: 19px; white-space: nowrap;">BIÊN BẢN KIỂM TRA TƯ CÁCH CỔ ĐÔNG</p>
        <p class="fw-bold mb-0" style="font-size: 19px; white-space: nowrap;">ĐẠI HỘI ĐỒNG CỔ ĐÔNG THƯỜNG NIÊN NĂM <span id="namDaiHoi"></span></p>
        <p class="fw-bold mb-0" style="font-size: 19px; white-space: nowrap;">CÔNG TY CỔ PHẦN CÔNG NGHỆ - VIỄN THÔNG ELCOM</p>
    </div>

    <div class="content">
        <p style="text-indent: 40px; text-align: justify;">Vào hồi <span id="gio"></span> giờ <span id="phut"></span> phút, ngày <span id="ngay2"></span> tháng <span id="thang2"></span> năm <span id="nam2"></span>, tại Tòa nhà ELCOM, phố Duy Tân, phường Dịch Vọng Hậu, quận Cầu Giấy, thành phố Hà Nội.</p>
        
        <p style="text-indent: 40px; text-align: justify;">Theo quy định của Pháp luật, Ban kiểm tra tư cách cổ đông đã tiến hành kiểm tra tư cách các đại biểu cổ đông tới dự đại hội với kết quả cụ thể như sau:</p>
        
        <p style="text-indent: 40px; text-align: justify;">Tại thời điểm khai mạc Đại hội, tổng số cổ đông tham dự là <span id="soNguoiTd"></span> cổ đông (trong đó số lượng cổ đông ủy quyền là <span id="soNguoiUq"></span>), đại diện cho <span id="cpThamdu"></span> cổ phần có quyền biểu quyết, chiếm tỷ lệ <span id="pcBq"></span>% tổng số cổ phần có quyền biểu quyết.</p>
        
        <p style="text-indent: 40px; text-align: justify;">Căn cứ Khoản 1 Điều 145 của Luật Doanh nghiệp đã được Quốc hội nước Cộng hòa Xã hội Chủ nghĩa Việt Nam thông qua ngày 17/06/2020, căn cứ Điều lệ Công ty, Đại hội đồng cổ đông thường niên Công ty Cổ phần Công nghệ - Viễn thông ELCOM năm <span id="namDaiHoi2"></span> với thành phần tham dự như trên là hợp lệ và đủ điều kiện tiến hành đại hội.</p>
        
        <p style="text-indent: 40px; text-align: justify;">Ban tổ chức chúng tôi hoàn toàn chịu trách nhiệm về số liệu thống kê cổ đông tham dự Đại hội và cơ sở tiến hành Đại hội trên.</p>
        
        <p style="text-indent: 40px; text-align: justify;">Báo cáo kiểm tra tư cách cổ đông được lập vào hồi <span id="gio2"></span> giờ <span id="phut2"></span> phút ngày <span id="ngay3"></span>/<span id="thang3"></span>/<span id="nam3"></span> và đã được báo cáo công khai trước Đại hội.</p>
    </div>

    <div style="position: relative;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 40%;"></td>
                <td style="width: 60%; text-align: center; vertical-align: top;">
                    <p class="fw-bold mb-0"  style="font-size: 19px; white-space: nowrap;">TM.BAN KIỂM TRA TƯ CÁCH CỔ ĐÔNG</p>
                    <p class="fw-bold mb-0" style="font-size: 19px;">TRƯỞNG BAN</p>
                    <div style="height: 100px;"></div>
                    <p class="fw-bold" style="font-size: 19px;">NGÔ KIỀU ANH</p>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="text-center mt-3 mb-5">
    <button id="printButton" class="btn btn-primary">In biên bản</button>
    <a href="{{ route('cms.index') }}" class="btn btn-secondary ms-2">Quay lại</a>
</div>
@endsection

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printContent, #printContent * {
            visibility: visible;
        }
        #printContent {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            width: 210mm;
            margin: 20mm auto 0 !important; /* Tăng lề trên */
            padding: 0 3mm !important;
            font-size: 16px !important;
            box-sizing: border-box;
            transform-origin: top center;
            page-break-after: avoid !important;
        }
        .bbkt-content {
            font-family: "Times New Roman", Times, serif !important;
            font-size: 18px !important;
            line-height: 1.3 !important;
            page-break-inside: avoid !important;
        }
        .title p {
            white-space: nowrap !important;
            font-size: 19px !important;
        }
        .country-name {
            white-space: nowrap;
        }
        .company-name {
            white-space: nowrap;
        }
        /* Giảm khoảng cách giữa các phần */
        .mb-3 {
            margin-bottom: 0.5rem !important;
        }
        .mb-4 {
            margin-bottom: 0.7rem !important;
        }
        /* Ngăn trang 2 trống */
        html, body {
            height: auto !important;
            overflow: hidden !important;
        }
        
        @page {
            size: A4 portrait;
            margin: 0;
            orphans: 0;
            widows: 0;
        }
    }
    
    .bbkt-content {
        font-family: "Times New Roman", Times, serif;
        font-size: 18px;
        line-height: 1.3;
    }
    
    .title p {
        white-space: nowrap;
    }
    .country-name {
        white-space: nowrap;
    }
    .company-name {
        white-space: nowrap;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const accessToken = localStorage.getItem('access_token_admin');
        if (!accessToken) {
            alert('Bạn chưa đăng nhập hoặc phiên làm việc đã hết hạn. Vui lòng đăng nhập lại!');
            window.location.href = '{{ route("cms.login") }}';
            return;
        }

        // Lấy dữ liệu từ API với access_token
        fetch('/api/admin/data-for-print-bkkt', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${accessToken}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 401) {
                    throw new Error('Unauthorized');
                }
                throw new Error('Server Error');
            }
            return response.json();
        })
        .then(responseData => {
            // Kiểm tra cấu trúc response
            if (responseData && responseData.data) {
                const data = responseData.data;
                
                // Điền dữ liệu vào trang
                document.getElementById('soNguoiTd').textContent = data.soNguoiTd;
                document.getElementById('soNguoiUq').textContent = data.soNguoiUq;
                document.getElementById('cpThamdu').textContent = data.cpThamdu;
                document.getElementById('pcBq').textContent = data.pcBq;
                
                // Điền dữ liệu thời gian
                document.getElementById('ngay').textContent = data.ngay;
                document.getElementById('thang').textContent = data.thang;
                document.getElementById('nam').textContent = data.nam;
                document.getElementById('gio').textContent = data.gio;
                document.getElementById('phut').textContent = data.phut;
                
                // Điền dữ liệu thời gian ở phần nội dung
                document.getElementById('ngay2').textContent = data.ngay;
                document.getElementById('thang2').textContent = data.thang;
                document.getElementById('nam2').textContent = data.nam;
                document.getElementById('namDaiHoi').textContent = data.nam;
                document.getElementById('namDaiHoi2').textContent = data.nam;
                
                // Điền dữ liệu thời gian ở phần cuối
                document.getElementById('gio2').textContent = data.gio;
                document.getElementById('phut2').textContent = data.phut;
                document.getElementById('ngay3').textContent = data.ngay;
                document.getElementById('thang3').textContent = data.thang;
                document.getElementById('nam3').textContent = data.nam;
            } else {
                throw new Error('Invalid data format');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (error.message === 'Unauthorized') {
                alert('Phiên làm việc đã hết hạn. Vui lòng đăng nhập lại!');
                localStorage.removeItem('access_token_admin');
                window.location.href = '{{ route("cms.login") }}';
            } else {
                alert('Có lỗi xảy ra khi lấy dữ liệu. Vui lòng thử lại sau.');
            }
        });

        // Xử lý nút in
        document.getElementById('printButton').addEventListener('click', function() {
            window.print();
        });
    });
</script>
@endpush