@extends('cms::layouts.blank')

@section('content')

<div class="container">
    <!-- Phần header -->
    <table class="header-table">
        <tr>
            <td style="width: 50%">
                <p class="text-center text-bold">CÔNG TY CỔ PHẦN</p>
                <p class="text-center text-bold">CÔNG NGHỆ - VIỄN THÔNG ELCOM</p>
                <p class="text-center text-bold">------***-----</p>
                <p class="text-center">Số: 01/2025/BB-ĐHĐCĐ</p>
            </td>
            <td style="width: 50%">
                <p class="text-center text-bold">CỘNG HOÀ XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
                <p class="text-center text-bold">Độc lập - Tự do - Hạnh phúc</p>
                <p class="text-center mb-3">---------***---------</p>
                <p class="text-right" style="font-style: italic;">Hà Nội, ngày 24 tháng 4 năm 2025</p>
            </td>
        </tr>
    </table>

    <!-- Tiêu đề biên bản -->
    <div class="title">BIÊN BẢN HỌP ĐẠI HỘI ĐỒNG CỔ ĐÔNG THƯỜNG NIÊN NĂM 2025</div>
    <div class="title">CÔNG TY CỔ PHẦN CÔNG NGHỆ - VIỄN THÔNG ELCOM</div>

    <!-- Phần mở đầu -->
    <p style="text-align: justify; text-indent: 36pt;">
        Hôm nay, vào hồi {{ $soGio ?? '....' }} giờ {{ $soPhut ?? '....' }} phút ngày 24 tháng 04 năm 2025, tại Phòng Hội nghị, Tòa nhà Elcom, phố Duy Tân, phường Dịch Vọng Hậu, quận Cầu Giấy, TP. Hà Nội, Công ty Cổ phần Công nghệ - Viễn thông Elcom (mã số doanh nghiệp 0101435127) đã tổ chức phiên họp Đại hội đồng cổ đông thường niên năm 2025 ("Đại hội").
    </p>

    <!-- Phần 1: Thành phần tham dự -->
    <p class="section-title"><span style="margin-right: 10px;">I.</span>THÀNH PHẦN THAM DỰ</p>
    <div class="indent-1">
        <p>1. Tham dự Đại hội có <span class="highlight">{{ $soNguoiTd ?? '' }}</span> cổ đông đại diện cho <span class="highlight">{{ $cpThamdu ?? '' }}</span> cổ phần, chiếm <span class="highlight">{{ $pcBq ?? '' }}%</span> số cổ phần có quyền biểu quyết của Công ty.</p>
        <p>2. Các thành viên HĐQT, Ban Kiểm soát và Ban điều hành Công ty.</p>
    </div>

    <!-- Phần 2: Nội dung và diễn biến đại hội -->

    <p class="section-title"><span style="margin-right: 10px;">II.</span>NỘI DUNG VÀ DIỄN BIẾN ĐẠI HỘI</p>
    <div class="indent-1">
        <p class="text-bold"><span style="margin-right: 10px;">A.</span>Thông báo kết quả đăng ký dự họp</p>
        <p style="text-align: justify; text-indent: 36pt;">
            Đại hội đã nghe bà Ngô Kiều Anh - Trưởng ban kiểm tra tư cách cổ đông Báo cáo kết quả cổ đông đăng ký dự họp. Dựa trên số liệu cổ đông đăng ký tham gia dự họp, Đại hội đồng cổ đông Công ty đủ điều kiện để tiến hành hợp lệ và đúng pháp luật.
        </p>

        <p class="text-bold"><span style="margin-right: 10px;">B.</span>Bầu Đoàn chủ tịch</p>
        <p style="text-align: justify; text-indent: 36pt;">
            Để điều hành Đại hội, Đại hội đã biểu quyết nhất trí thông qua Đoàn Chủ tịch gồm các thành viên sau đây:
        </p>
        <div class="indent-2">
            <p class="fw-bold"><span style="margin-right: 10px;">1.</span>Ông Phan Chiến Thắng, Chủ tịch HĐQT: Chủ tọa</p>
            <p><span style="margin-right: 10px;">2.</span>Ông Nguyễn Đức Thiện, Phó Chủ tịch HĐQT: Ủy viên</p>
            <p><span style="margin-right: 10px;">3.</span>Ông Phạm Minh Thắng, Tổng Giám đốc: Ủy viên</p>
        </div>

        <!-- Tiếp tục các phần còn lại của biên bản -->
        <p class="text-bold"><span style="margin-right: 10px;">C.</span>Bầu Ban kiểm phiếu và cử Ban thư ký</p>
        <p style="text-align: justify; text-indent: 36pt;">
            Đại hội đã biểu quyết nhất trí thông qua việc cử Ban thư ký và bầu Ban kiểm phiếu tại Đại hội theo đề nghị của Chủ tọa như sau:
        </p>
        <div class="indent-2">
            <p><span style="margin-right: 10px;">-</span>Ban thư ký: bà Nguyễn Thị Thu Trang (Trưởng ban), bà Nguyễn Thị Kim Cúc</p>
            <p><span style="margin-right: 10px;">-</span>Ban kiểm phiếu: Bà Hoàng Thị Phương Thúy - Trưởng ban – Thành viên BKS), bà Vũ Thị Ngân Hà (Thành viên) và bà Nguyễn Thị Minh Hiền (Thành viên).</p>
        </div>

        <p class="text-bold">D. Thông qua chương trình Đại hội</p>
        <p style="text-align: justify; text-indent: 36pt;">
            Ông Nguyễn Đức Thiện giới thiệu nội dung, chương trình Đại hội đồng cổ đông thường niên năm 2025 và được các cổ đông tham dự Đại hội biểu quyết thông qua (Theo nội dung Chương trình Đại hội kèm theo).
        </p>

        <p class="text-bold">E. Nội dung Đại hội</p>
        <p class="text-bold">1. Các Báo cáo và tờ trình tại Đại hội:</p>
        <div class="indent-1">
            <p><span style="margin-right: 10px;">-</span>Ông Phan Chiến Thắng, Chủ tịch Hội đồng quản trị thay mặt Hội đồng quản trị trình bày Báo cáo hoạt động của Hội đồng quản trị năm 2024 và kế hoạch kinh doanh năm 2025; Báo cáo đánh giá của thành viên HĐQT độc lập năm 2024;</p>
            <p><span style="margin-right: 10px;">-</span>Bà Ngô Kiều Anh, Trưởng Ban kiểm soát thay mặt Ban kiểm soát trình bày Báo cáo hoạt động của Ban kiểm soát năm 2024;</p>
            <p><span style="margin-right: 10px;">-</span>Ông Nguyễn Đức Thiện thay mặt Hội đồng quản trị đề nghị các cổ đông xem xét các nội dung tại các Tờ trình để thảo luận và biểu quyết thông qua tại Đại hội, gồm:</p>
            <div class="noi-dung-to-trinh">
                <p>(1) Tờ trình thông qua Báo cáo tài chính kiểm toán năm 2024 và phương án phân phối lợi nhuận năm 2024;</p>
                <p>(2) Tờ trình thông qua phương án phát hành cổ phiếu và trả cổ tức năm 2024 bằng cổ phiếu;</p>
                <p>(3) Tờ trình thông qua phương án phát hành cổ phiếu theo chương trình lựa chọn cho người lao động;</p>
                <p>(4) Tờ trình thông qua phương án chi trả thù lao cho HĐQT và BKS năm 2025;</p>
                <p>(5) Tờ trình thông qua phương án lựa chọn đơn vị kiểm toán BCTC năm 2025;</p>
                <p>(6) Tờ trình thông qua việc bổ sung ngành nghề kinh doanh của Công ty;</p>
                <p>(7) Tờ trình thông qua Báo cáo tài chính đã kiểm toán năm 2024;</p>
                <p>(8) Tờ trình thông qua việc miễn nhiệm TV HĐQT và bầu bổ sung (01) TV HĐQT</p>
            </div>
        </div>
        <p class="text-bold"><span style="margin-right: 10px;">2.</span>Chương trình thảo luận:</p>
        <div class="indent-1 qa-section">
            <p class="text-bold">Câu hỏi 1:</p>
            <p class="text-bold">Trả lời:</p>
            <p class="text-bold">Câu hỏi 2:</p>
            <p class="text-bold">Trả lời:</p>
            <p class="text-bold">Câu hỏi 3:</p>
            <p class="text-bold">Trả lời:</p>
            <p class="text-bold">Câu hỏi 4:</p>
            <p class="text-bold">Trả lời:</p>
            <p class="text-bold">Câu hỏi 5:</p>
            <p class="text-bold">Trả lời:</p>
        </div>
    </div>

    <!-- Thông tin kết quả biểu quyết -->
    <p class="section-title"><span style="margin-right: 10px;">III.</span>BIỂU QUYẾT, BẦU CỬ VÀ KẾT QUẢ KIỂM PHIẾU</p>
    <p style="text-indent: 27pt;">Bà Hoàng Thị Phương Thúy - Trưởng ban kiểm phiếu thay mặt Ban kiểm phiếu đã công bố kết quả kiểm phiếu, bầu cử như sau:</p>

    <p class="text-bold indent-1">1. Kết quả biểu quyết:</p>
    <div class="indent-2">
        <p>- Tổng số thẻ biểu quyết phát ra: <span class="highlight">{{ $TONGPHIEU ?? '' }}</span> đại diện cho <span class="highlight">{{ $TONGCP ?? '' }}</span> cổ phần bằng <span class="highlight">{{ $pcCpPhatRa ?? '' }}%</span> tổng số cổ phần có quyền biểu quyết.</p>
        <p>- Số thẻ biểu quyết thu về: <span class="highlight">{{ $PHIEUHL ?? '' }}</span>, đại diện cho <span class="highlight">{{ $CPHOPLE ?? '' }}</span> cổ phần, chiếm <span class="highlight">{{ $pcSoCoPhanHople ?? '' }}%</span> tổng số cổ phần có quyền biểu quyết của tất cả các cổ đông dự họp.</p>
    </div>

    <!-- Kết quả biểu quyết từng nội dung -->
    <!-- Nội dung 1 -->
    <div style="margin-top: 10px;">
        <p><span class="text-bold" style="text-decoration: underline;">Nội dung 1</span>: Thông qua các Báo cáo: Báo cáo hoạt động của HĐQT năm 2024; kế hoạch kinh doanh năm 2025; Báo cáo đánh giá của thành viên HĐQT độc lập năm 2024; Báo cáo hoạt động của BKS năm 2024.</p>
        <p style="font-weight: bold; font-style: italic;">Kết quả biểu quyết như sau:</p>
        <div class="indent-2">
            <p>- Hình thức biểu quyết: Biểu quyết bằng phiếu biểu quyết, mỗi cổ phần tương ứng với 01 phiếu biểu quyết</p>
            <p>- Tổng số phiếu biểu quyết thu về: <span class="highlight">{{ $TOTAL1 ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTAL1PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu hợp lệ: <span class="highlight">{{ $TOTALHL1 ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTALHL1PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp</p>
            <p>- Tổng số phiếu không hợp lệ: <span class="highlight">{{ $KHL1 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KHL1PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết tán thành: <span class="highlight">{{ $DY1 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $DY1PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết không tán thành: <span class="highlight">{{ $KDY1 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KDY1PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết không ý kiến: <span class="highlight">{{ $KYK1 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KYK1PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Kết quả: Thông qua <span class="highlight">{{ $DY1PC ?? '' }}%</span> trên tổng số phiếu biểu quyết của cổ đông dự họp.</p>
        </div>
    </div>

    <!-- Nội dung 2 -->
    <div style="margin-top: 10px;">
        <p><span class="text-bold" style="text-decoration: underline;">Nội dung 2</span>: Thông qua Báo cáo tài chính kiểm toán năm 2024 và phương án phân phối lợi nhuận năm 2024 theo Tờ trình số 02/2025/TTr-HĐQT ngày 02/04/2025</p>
        <p style="font-weight: bold; font-style: italic;">Kết quả biểu quyết như sau:</p>
        <div class="indent-2">
            <p>- Hình thức biểu quyết: Biểu quyết bằng phiếu biểu quyết, mỗi cổ phần tương ứng với 01 phiếu biểu quyết</p>
            <p>- Tổng số phiếu biểu quyết thu về: <span class="highlight">{{ $TOTAL2 ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTAL2PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu hợp lệ: <span class="highlight">{{ $TOTALHL2 ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTALHL2PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp</p>
            <p>- Tổng số phiếu không hợp lệ: <span class="highlight">{{ $KHL2 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KHL2PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết tán thành: <span class="highlight">{{ $DY2 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $DY2PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết không tán thành: <span class="highlight">{{ $KDY2 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KDY2PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết không ý kiến: <span class="highlight">{{ $KYK2 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KYK2PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Kết quả: Thông qua <span class="highlight">{{ $DY2PC ?? '' }}%</span> trên tổng số phiếu biểu quyết của cổ đông dự họp.</p>
        </div>
    </div>

    <!-- Nội dung 3 -->
    <div style="margin-top: 10px;">
        <p><span class="text-bold" style="text-decoration: underline;">Nội dung 3</span>: Thông qua phương án phát hành cổ phiếu và trả cổ tức năm 2024 bằng cổ phiếu (Theo các nội dung cụ thể tại Tờ trình số 03/2025/TTr-HĐQT ngày 02/04/2025).</p>
        <p style="font-weight: bold; font-style: italic;">Kết quả biểu quyết như sau:</p>
        <div class="indent-2">
            <p>- Hình thức biểu quyết: Biểu quyết bằng phiếu biểu quyết, mỗi cổ phần tương ứng với 01 phiếu biểu quyết</p>
            <p>- Tổng số phiếu biểu quyết thu về: <span class="highlight">{{ $TOTAL3 ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTAL3PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu hợp lệ: <span class="highlight">{{ $TOTALHL3 ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTALHL3PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp</p>
            <p>- Tổng số phiếu không hợp lệ: <span class="highlight">{{ $KHL3 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KHL3PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết tán thành: <span class="highlight">{{ $DY3 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $DY3PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết không tán thành: <span class="highlight">{{ $KDY3 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KDY3PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết không ý kiến: <span class="highlight">{{ $KYK3 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KYK3PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Kết quả: Thông qua <span class="highlight">{{ $DY3PC ?? '' }}%</span> trên tổng số phiếu biểu quyết của cổ đông dự họp.</p>
        </div>
    </div>

    <!-- Nội dung 4 -->
    <div style="margin-top: 10px;">
        <p><span class="text-bold" style="text-decoration: underline;">Nội dung 4</span>: Thông qua phương án phát hành cổ phiếu theo chương trình lựa chọn cho người lao động (Nội dung chi tiết tại Tờ trình số 04/2025/TTr-HĐQT ngày 02/04/2025).</p>
        <p style="font-weight: bold;">a.  Chưa loại trừ số phiếu biểu quyết của cán bộ nhân viên và người có liên quan</p>
        <p style="font-weight: bold; font-style: italic;">Kết quả biểu quyết như sau:</p>
        <div class="indent-2">
            <p>- Hình thức biểu quyết: Biểu quyết bằng phiếu biểu quyết, mỗi cổ phần tương ứng với 01 phiếu biểu quyết</p>
            <p>- Tổng số phiếu biểu quyết thu về: <span class="highlight">{{ $TOTAL4 ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTAL4PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu hợp lệ: <span class="highlight">{{ $TOTALHL4 ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTALHL4PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp</p>
            <p>- Tổng số phiếu không hợp lệ: <span class="highlight">{{ $KHL4 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KHL4PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết tán thành: <span class="highlight">{{ $DY4 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $DY4PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết không tán thành: <span class="highlight">{{ $KDY4 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KDY4PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết không ý kiến: <span class="highlight">{{ $KYK4 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KYK4PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Kết quả: Thông qua <span class="highlight">{{ $DY4PC ?? '' }}%</span> trên tổng số phiếu biểu quyết của cổ đông dự họp.</p>
        </div>
        <p style="font-weight: bold;">b.  Đã loại trừ số phiếu biểu quyết của cán bộ nhân viên và người có liên quan</p>
        <p style="font-weight: bold; font-style: italic;">Kết quả biểu quyết như sau:</p>
        <div class="indent-2">
            <p>- Hình thức biểu quyết: Biểu quyết bằng phiếu biểu quyết, mỗi cổ phần tương ứng với 01 phiếu biểu quyết</p>
            <p>- Tổng số phiếu biểu quyết thu về: <span class="highlight">{{ $TOTAL4b ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTAL4PCb ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp đã loại trừ số phiếu của cán bộ nhân viên và người liên quan.</p>
            <p>- Tổng số phiếu hợp lệ: <span class="highlight">{{ $TOTALHL4b ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTALHL4PCb ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp đã loại trừ số phiếu của cán bộ nhân viên và người liên quan.</p>
            <p>- Tổng số phiếu không hợp lệ: <span class="highlight">{{ $KHL4b ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KHL4PCb ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp đã loại trừ số phiếu của cán bộ nhân viên và người liên quan.</p>
            <p>- Tổng số phiếu biểu quyết tán thành: <span class="highlight">{{ $DY4b ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $DY4PCb ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp đã loại trừ số phiếu của cán bộ nhân viên và người liên quan.</p>
            <p>- Tổng số phiếu biểu quyết không tán thành: <span class="highlight">{{ $KDY4b ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KDY4PCb ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp đã loại trừ số phiếu của cán bộ nhân viên và người liên quan.</p>
            <p>- Tổng số phiếu biểu quyết không ý kiến: <span class="highlight">{{ $KYK4b ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KYK4PCb ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp đã loại trừ số phiếu của cán bộ nhân viên và người liên quan.</p>
            <p>- Kết quả: Thông qua <span class="highlight">{{ $DY4PCb ?? '' }}%</span> trên tổng số phiếu biểu quyết của cổ đông dự họp đã loại trừ số phiếu của cán bộ nhân viên và người liên quan.</p>
        </div>
    </div>

    <!-- Nội dung 5 -->
    <div style="margin-top: 10px;">
        <p><span class="text-bold" style="text-decoration: underline;">Nội dung 5</span>: Thông qua ngân sách thu nhập cho HĐQT và chi trả thù lao cho BKS năm 2025 theo Tờ trình số 05/2025/TTr-HĐQT ngày 02/04/2025</p>
        <p style="font-weight: bold; font-style: italic;">Kết quả biểu quyết như sau:</p>
        <div class="indent-2">
            <p>- Hình thức biểu quyết: Biểu quyết bằng phiếu biểu quyết, mỗi cổ phần tương ứng với 01 phiếu biểu quyết</p>
            <p>- Tổng số phiếu biểu quyết thu về: <span class="highlight">{{ $TOTAL5 ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTAL5PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu hợp lệ: <span class="highlight">{{ $TOTALHL5 ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTALHL5PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp</p>
            <p>- Tổng số phiếu không hợp lệ: <span class="highlight">{{ $KHL5 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KHL5PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết tán thành: <span class="highlight">{{ $DY5 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $DY5PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết không tán thành: <span class="highlight">{{ $KDY5 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KDY5PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết không ý kiến: <span class="highlight">{{ $KYK5 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KYK5PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Kết quả: Thông qua <span class="highlight">{{ $DY5PC ?? '' }}%</span> trên tổng số phiếu biểu quyết của cổ đông dự họp.</p>
        </div>
    </div>

    <!-- Nội dung 6 -->
    <div style="margin-top: 10px;">
        <p><span class="text-bold" style="text-decoration: underline;">Nội dung 6</span>: Thông qua phương án lựa chọn đơn vị kiểm toán theo Tờ trình số 06/2025/TTr-HĐQT ngày 02/04/2025</p>
        <p style="font-weight: bold; font-style: italic;">Kết quả biểu quyết như sau:</p>
        <div class="indent-2">
            <p>- Hình thức biểu quyết: Biểu quyết bằng phiếu biểu quyết, mỗi cổ phần tương ứng với 01 phiếu biểu quyết</p>
            <p>- Tổng số phiếu biểu quyết thu về: <span class="highlight">{{ $TOTAL6 ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTAL6PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu hợp lệ: <span class="highlight">{{ $TOTALHL6 ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTALHL6PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp</p>
            <p>- Tổng số phiếu không hợp lệ: <span class="highlight">{{ $KHL6 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KHL6PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết tán thành: <span class="highlight">{{ $DY6 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $DY6PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết không tán thành: <span class="highlight">{{ $KDY6 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KDY6PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết không ý kiến: <span class="highlight">{{ $KYK6 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KYK6PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Kết quả: Thông qua <span class="highlight">{{ $DY6PC ?? '' }}%</span> trên tổng số phiếu biểu quyết của cổ đông dự họp.</p>
        </div>
    </div>

    <!-- Nội dung 7 -->
    <div style="margin-top: 10px;">
        <p><span class="text-bold" style="text-decoration: underline;">Nội dung 7</span>: Thông qua việc bổ sung ngành nghề kinh doanh của Công ty theo Tờ trình số 07/2025/TTr-HĐQT ngày 02/04/2025.</p>
        <p style="font-weight: bold; font-style: italic;">Kết quả biểu quyết như sau:</p>
        <div class="indent-2">
            <p>- Hình thức biểu quyết: Biểu quyết bằng phiếu biểu quyết, mỗi cổ phần tương ứng với 01 phiếu biểu quyết</p>
            <p>- Tổng số phiếu biểu quyết thu về: <span class="highlight">{{ $TOTAL7 ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTAL7PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu hợp lệ: <span class="highlight">{{ $TOTALHL7 ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTALHL7PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp</p>
            <p>- Tổng số phiếu không hợp lệ: <span class="highlight">{{ $KHL7 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KHL7PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết tán thành: <span class="highlight">{{ $DY7 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $DY7PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết không tán thành: <span class="highlight">{{ $KDY7 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KDY7PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết không ý kiến: <span class="highlight">{{ $KYK7 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KYK7PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Kết quả: Thông qua <span class="highlight">{{ $DY7PC ?? '' }}%</span> trên tổng số phiếu biểu quyết của cổ đông dự họp.</p>
        </div>
    </div>

    <!-- Nội dung 8 -->
    <div style="margin-top: 10px;">
        <p><span class="text-bold" style="text-decoration: underline;">Nội dung 8</span>: Thông qua việc miễn nhiệm thành viên HĐQT Đỗ Minh Tiến và bầu bổ sung (01) thành viên HĐQT theo Tờ trình số 08/2025/TTr-HĐQT ngày 02/04/2025.</p>
        <p style="font-weight: bold; font-style: italic;">Kết quả biểu quyết như sau:</p>
        <div class="indent-2">
            <p>- Hình thức biểu quyết: Biểu quyết bằng phiếu biểu quyết, mỗi cổ phần tương ứng với 01 phiếu biểu quyết</p>
            <p>- Tổng số phiếu biểu quyết thu về: <span class="highlight">{{ $TOTAL8 ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTAL8PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu hợp lệ: <span class="highlight">{{ $TOTALHL8 ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTALHL8PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp</p>
            <p>- Tổng số phiếu không hợp lệ: <span class="highlight">{{ $KHL8 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KHL8PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết tán thành: <span class="highlight">{{ $DY8 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $DY8PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết không tán thành: <span class="highlight">{{ $KDY8 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KDY8PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết không ý kiến: <span class="highlight">{{ $KYK8 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KYK8PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Kết quả: Thông qua <span class="highlight">{{ $DY8PC ?? '' }}%</span> trên tổng số phiếu biểu quyết của cổ đông dự họp.</p>
        </div>
    </div>

    <!-- Nội dung 9 -->
    <div style="margin-top: 10px;">
        <p><span class="text-bold" style="text-decoration: underline;">Nội dung 9</span>: Thông qua việc ĐHĐCĐ giao và ủy quyền cho HĐQT quyết định đầu tư các dự án bất động sản.</p>
        <p style="font-weight: bold; font-style: italic;">Kết quả biểu quyết như sau:</p>
        <div class="indent-2">
            <p>- Hình thức biểu quyết: Biểu quyết bằng phiếu biểu quyết, mỗi cổ phần tương ứng với 01 phiếu biểu quyết</p>
            <p>- Tổng số phiếu biểu quyết thu về: <span class="highlight">{{ $TOTAL9 ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTAL9PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu hợp lệ: <span class="highlight">{{ $TOTALHL9 ?? '' }}</span> phiếu; chiếm <span class="highlight">{{ $TOTALHL9PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp</p>
            <p>- Tổng số phiếu không hợp lệ: <span class="highlight">{{ $KHL9 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KHL9PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết tán thành: <span class="highlight">{{ $DY9 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $DY9PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết không tán thành: <span class="highlight">{{ $KDY9 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KDY9PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Tổng số phiếu biểu quyết không ý kiến: <span class="highlight">{{ $KYK9 ?? '' }}</span> phiếu, chiếm <span class="highlight">{{ $KYK9PC ?? '' }}%</span> tổng số phiếu biểu quyết của cổ đông dự họp.</p>
            <p>- Kết quả: Thông qua <span class="highlight">{{ $DY9PC ?? '' }}%</span> trên tổng số phiếu biểu quyết của cổ đông dự họp.</p>
        </div>
    </div>

    <p class="text-bold indent-1"><span style="margin-right: 10px;">2.</span>Kết quả bầu cử:</p>
    <div class="indent-2">
        <p>- Tổng số thẻ bầu cử HĐQT phát ra là <span class="highlight">{{ $phieuBauCuPhatRa ?? '' }}</span> đại diện cho <span class="highlight">{{ $cpBauCuPhatRa ?? '' }}</span> cổ phần, số thẻ bầu cử thu về hợp lệ là <span class="highlight">{{ $phieuBauCuHopLe ?? '' }}</span> đại diện cho <span class="highlight">{{ $cpBauCuHopLe ?? '' }}</span> cổ phần, số thẻ bầu cử không hợp lệ là <span class="highlight">{{ $phieuBauCuKhongHopLe ?? '' }}</span> đại diện cho <span class="highlight">{{ $cpBauCuKhongHopLe ?? '' }}</span> cổ phần.</p>
        <p>- Ông Nguyễn Văn Mạnh được <span class="highlight">{{ $phieuBauNVM ?? '' }}</span> phiếu bầu, chiếm tỷ lệ <span class="highlight">{{ $tyLePhieuBauNVM ?? '' }}</span>% tổng số cổ phần biểu quyết dự họp.</p>
        <p>- Như vậy, Đại hội đã thông qua việc trúng cử thành viên HĐQT nhiệm kỳ 2022 - 2027 đối với ông Nguyễn Văn Mạnh.</p>
    </div>

    <p class="section-title"><span style="margin-right: 10px;">IV.</span>CÁC NỘI DUNG ĐƯỢC THÔNG QUA</p>
    <p style="margin-top: 10px">
        <span class="text-bold"><span style="margin-right: 10px;">1.</span>Thông qua các Báo cáo sau:</span>
    </p>
    <p style="margin-top: 6px">
        <span class="text-bold"><span style="margin-right: 10px;">1.1</span>Báo cáo hoạt động của Hội đồng quản trị năm 2024, kế hoạch kinh doanh năm 2025</span>
    </p>
    <p style="margin-left: 9pt; font-weight: bold; font-style: italic">Kế hoạch kinh doanh năm 2025</p>
    <div>
    <table style="width: 100%" class="financial-table">
        <tr>
            <td style="width: 40%; text-align: center; font-weight: bold;">Chỉ tiêu</td>
            <td style="width: 30%; text-align: center; font-weight: bold;">Kế hoạch năm 2025<br>(Triệu đồng)</td>
            <td style="width: 30%; text-align: center; font-weight: bold;">Tăng trưởng so với năm 2024</td>
        </tr>
        <tr>
            <td>Doanh thu thuần</td>
            <td class="text-right">1.160.000</td>
            <td class="text-right">45%</td>
        </tr>
        <tr>
            <td>Lợi nhuận sau thuế TNDN</td>
            <td class="text-right">126.000</td>
            <td class="text-right">27%</td>
        </tr>
        <tr>
            <td>Cổ tức</td>
            <td class="text-right">1.160.000</td>
            <td class="text-right">45%</td>
        </tr>
    </table>

    <ol start="2" style="list-style-type: decimal;">
        <li style="margin-top: 6px"><span class="text-bold">Báo cáo đánh giá của thành viên Hội đồng quản trị độc lập năm 2024.</span></li>
        <li style="margin-top: 6px"><span class="text-bold">Báo cáo hoạt động của Ban kiểm soát năm 2024.</span></li>
    </ol>

    <ol class="indent-1" start="2">
        <li style="font-weight: bold; margin-top: 10px">
            Thông qua Báo cáo tài chính kiểm toán năm 2024 và phương án phân phối lợi nhuận năm 2024
            <span style="font-weight: normal">(Theo Tờ trình số 02/2025/TTr-HĐQT ngày 02/04/2025).</span>
        </li>
    </ol>

    <p style="text-align: right; font-style: italic; margin-top: 6px; margin-bottom: 0;">(Đvt: Triệu đồng)</p>
    <div class="table-container">
        <table style="width: 100%" class="financial-table">
            <tr>
                <td style="width: 70%; font-weight: bold;">Chỉ tiêu</td>
                <td style="width: 30%; text-align: right; font-weight: bold;">Năm 2024</td>
            </tr>
            <tr>
                <td colspan="1" style="font-weight: bold;">
                    Một số chỉ tiêu cơ bản trong BCTC hợp nhất và BCTC công ty mẹ năm 2024 đã kiểm toán
                </td>
            </tr>
            <tr>
                <td>Doanh thu thuần</td>
                <td style="text-align: right">800.145</td>
            </tr>
            <tr>
                <td>Lợi nhuận trước thuế</td>
                <td style="text-align: right">115.029</td>
            </tr>
            <tr>
                <td>Lợi nhuận sau thuế TNDN hợp nhất</td>
                <td style="text-align: right">99.252</td>
            </tr>
            <tr>
                <td>Lợi nhuận sau thuế TNDN hợp nhất của cổ đông công ty mẹ</td>
                <td style="text-align: right">95.447</td>
            </tr>
            <tr>
                <td>Lợi nhuận sau thuế TNDN trên BCTC công ty mẹ</td>
                <td style="text-align: right">70.843</td>
            </tr>
            <tr>
                <td style="font-weight: bold">Phân phối lợi nhuận năm 2024</td>
                <td></td>
            </tr>
            <tr>
                <td>Trích quỹ khen thưởng, phúc lợi</td>
                <td style="text-align: right">5.000</td>
            </tr>
            <tr>
                <td>
                    Phát hành cổ phiếu để trả cổ tức 2024 (tỷ lệ 5%) dự kiến tối đa<br>
                    <span style="font-style: italic">(Dự kiến thực hiện sau khi hoàn tất đợt phát hành cổ phiếu ESOP)</span>
                </td>
                <td style="text-align: right">52.423,71</td>
            </tr>
        </table>
    </div>
    <p style="margin-top: 10px">
        <span class="text-bold"><span style="margin-right: 10px;">3.</span>Thông qua phương án phát hành cổ phiếu và trả cổ tức năm 2024 bằng cổ phiếu
        <span style="font-weight: normal">(Theo các nội dung cụ thể tại Tờ trình số 03/2025/TTr-HĐQT ngày 02/04/2025).</span></span>
    </p>
    <p style="margin-top: 10px">
        <span class="text-bold"><span style="margin-right: 10px;">4.</span>Thông qua phương án phát hành cổ phiếu theo chương trình lựa chọn cho người lao động
        <span style="font-weight: normal">(Theo Tờ trình số 04/2025/TTr-HĐQT ngày 02/04/2025).</span></span>
    </p>
    <p style="margin-top: 10px">
        <span class="text-bold"><span style="margin-right: 10px;">5.</span>Thông qua ngân sách thu nhập của HĐQT và phương án chi trả thù lao cho BKS năm 2025
    </p>
    <p style="font-weight: bold">5.1. Ngân sách thu nhập của HĐQT năm 2025</p>

    <ul class="indent-1" style="list-style-type: none;">
        <li style="margin-top: 6px"><span style="margin-right: 10px;">-</span> Lương của các thành viên HĐQT kiêm nhiệm chức danh điều hành sẽ chi trả cho các vị trí điều hành đó từ nguồn chi phí theo kế hoạch kinh doanh năm 2025.</li>
        <li style="margin-top: 6px"><span style="margin-right: 10px;">-</span> Tổng thù lao cho các thành viên HĐQT không kiêm nhiệm chức danh điều hành: không vượt quá 2 tỷ đồng. Giao cho HĐQT triển khai chi tiết việc chi trả bao gồm quyết định mức phân bổ cụ thể, thời gian chi trả.</li>
    </ul>

    <p style="font-weight: bold">5.2. Thù lao cho Ban kiểm soát năm 2025</p>

    <ul class="indent-1" style="list-style-type: none;">
        <li style="margin-top: 6px"><span style="margin-right: 10px;">-</span> Tổng quỹ thù lao cho BKS tối đa 264 triệu đồng</li>
        <li style="margin-top: 6px"><span style="margin-right: 10px;">-</span> Giao cho Hội đồng quản trị triển khai chi tiết việc chi trả bao gồm quyết định mức phân bổ cụ thể, thời gian chi trả.</li>
    </ul>
    <p style="margin-top: 10px">
        <span class="text-bold"><span style="margin-right: 10px;">6.</span>Thông qua phương án lựa chọn đơn vị kiểm toán BCTC năm 2025
        <span style="font-weight: normal">(Theo Tờ trình số 06/2025/TTr-BKS ngày 02/04/2025).</span>
    </p>
    <p style="margin-top: 10px">
        <span class="text-bold"><span style="margin-right: 10px;">7.</span>Thông qua việc bổ sung ngành nghề kinh doanh của Công ty
        <span style="font-weight: normal">(Theo Tờ trình số 07/2025/TTr-HĐQT ngày 02/04/2025).</span>
    </p>
    <p style="margin-top: 10px">
        <span class="text-bold"><span style="margin-right: 10px;">8.</span>Thông qua việc miễn nhiệm thành viên HĐQT Đổ Minh Tiến và bầu bổ sung (01) một thành viên HĐQT.
    </p>
    <p style="margin-top: 10px">
        <span class="text-bold"><span style="margin-right: 10px;">9.</span>Thông qua việc ĐHĐCĐ giao và ủy quyền cho HĐQT quyết định đầu tư các dự án bất động sản.
    </p>
    <p style="margin-top: 10px">
        <span class="text-bold"><span style="margin-right: 10px;">10.</span>Thông qua việc trúng cử thành viên HĐQT đối với ông Nguyễn Văn Mạnh (nhiệm kỳ 2022 – 2027)
    </p>
    <ul style="list-style-type: none;">
        <li style="margin-top: 6px">- Năm sinh: {{ $ngaySinhNVM ?? '..../..../1984' }}</li>
        <li style="margin-top: 6px">- Địa chỉ thường trú: {{ $diaChiNVM ?? '' }}</li>
        <li style="margin-top: 6px">- Trình độ chuyên môn: {{ $trinhDoNVM ?? '' }}</li>
        <li style="margin-top: 6px">- Đơn vị công tác: {{ $donViNVM ?? '' }}</li>
    </ul>

    <p class="section-title"><span style="margin-right: 10px;">V.</span>THÔNG QUA BIÊN BẢN ĐẠI HỘI</p>

    <p style="margin-left: 18pt; text-align: justify; margin-top: 6px">
        Biên bản này được lập vào hồi <span class="highlight">{{ $gioKet ?? '' }}</span> giờ <span class="highlight">{{ $phutKet ?? '' }}</span> phút ngày 24 tháng 04 năm 2025.
    </p>

    <p style="text-indent: 18pt; text-align: justify; margin-top: 6px">
        Biên bản này được đọc lại trước toàn thể Đại hội và được số cổ đông đại diện cho 100% số cổ phần có quyền biểu quyết có mặt tại Đại hội biểu quyết đồng ý thông qua./.
    </p>

    <!-- Phần ký kết biên bản -->
    <table style="margin-top: 30px; width: 100%">
        <tr>
            <td style="width: 50%; text-align: center">
                <p class="text-bold">TRƯỞNG BAN THƯ KÝ</p>
                <div style="height: 80px;"></div>
                <p class="text-bold">NGUYỄN THỊ THU TRANG</p>
            </td>
            <td style="width: 50%; text-align: center">
                <p class="text-bold">CHỦ TỌA</p>
                <div style="height: 80px;"></div>
                <p class="text-bold">PHAN CHIẾN THẮNG</p>
            </td>
        </tr>
    </table>
</div>

<div class="text-center mt-3 mb-5 no-print">
    <button onclick="window.print()" class="btn btn-primary">In biên bản</button>
    <a href="{{ route('cms.index') }}" class="btn btn-secondary ms-2">Quay lại</a>
</div>
@endsection

@push('styles')
<style lang="scss">
    input {
        width: 50px;
        border: none;
    }
    body {
        font-family: "Times New Roman", Times, serif;
        font-size: 12pt;
        color: #000000;
        line-height: 1.3;
    }
    .container {
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    .header-table td {
        vertical-align: top;
        padding: 5px;
    }
    .text-center {
        text-align: center;
    }
    .text-right {
        text-align: right;
    }
    .text-bold {
        font-weight: bold;
    }
    .title {
        font-weight: bold;
        font-size: 14pt;
        text-align: center;
        margin: 10px 0;
    }
    .section-title {
        font-weight: bold;
        text-transform: uppercase;
        padding-bottom: 0;
    }
    .indent-1 {
        padding-left: 20px;
    }
    .indent-2 {
        padding-left: 40px;
    }
    .highlight {
        background-color: #e6ffe6;
    }
    p {
        margin-bottom: 0.5rem!important;
    }
    .qa-section {
        p {
            text-decoration: underline;
        }
    }
    @media print {
        .no-print {
            display: none;
        }
        body {
            font-size: 12pt;
        }
        .page-break {
            page-break-before: always;
        }
        .header-table {
            tr {
                td {
                    p {
                        white-space: nowrap;
                    }
                }
            }
        }
    }
    /* ...existing styles... */
    .btn {
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    .btn-primary {
        background: #007bff;
        color: white;
        border: none;
    }
    .btn-secondary {
        background: #6c757d;
        color: white;
        border: none;
    }
    .mt-3 {
        margin-top: 1rem;
    }
    .mb-5 {
        margin-bottom: 3rem;
    }
    .ms-2 {
        margin-left: 0.5rem;
    }
    .text-center {
        text-align: center;
    }
    .header-table {
        tr {
            td {
                p {
                    margin-bottom: 0;
                }
            }
        }
    }
    .noi-dung-to-trinh {
        p {
            font-style: italic;
            margin-bottom: 0;
        }
    }
    .financial-table {
        tr {
            td {
                border-top: 1px solid #000; border-bottom: 1px solid #000;
                padding: 4px 0;
            }
        }
    }
    .table-container {
        padding: 0 18px;
    }
</style>
@endpush
