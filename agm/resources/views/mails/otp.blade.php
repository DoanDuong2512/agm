<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {}
        .mail-header {
            text-align: center;
        }
        .company-name {
            font-size: 20px;
            font-weight: 600;
            text-transform: uppercase;
            color: #292663;
        }
        .main-container {
            border-top: 10px solid #1C9AD6;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border-left: 2px solid #1C9AD6;
            border-right: 2px solid #1C9AD6;
            border-bottom: 2px solid #1C9AD6;
            border-radius: 0 0 6px 6px;
            overflow: hidden;
        }
        .header {
            text-align: center;
            padding: 10px 0;
        }
        .logo {
            max-width: 150px;
        }
        .content {
            font-size: 14px;
            padding: 20px 0;
            color: #444349;
        }
        .content p {
            margin: 4px 0;
        }
        .button {
            display: block;
            width: 100%;
            background-color: #0088cc;
            color: white;
            text-align: center;
            padding: 12px 0;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            font-size: 14px;
            color: #444349;
        }
        .footer .notice {
            color: #636268;
            text-align: start;
            padding-top: 20px;
            border-top: 1px solid #E2E1E8;
            font-style: italic;
        }
    </style>
    <title>Mã OTP</title>
</head>
<body>
    <div class="container">
        <div class="mail-header">
            <div>
                <img src="{{ $message->embed(public_path('images/logo-elcom.png')) }}" alt="logo Elcom" class="logo">
            </div>
            <p class="company-name">Công ty cổ phần công nghệ - viễn thông Elcom</p>
        </div>
        <div class="main-container">
            <div class="header">
                <img src="{{ $message->embed(public_path('images/logo-elcom.png')) }}" alt="logo Elcom" class="logo">
            </div>

            <div class="content">
                <p>Xin chào!</p>
                <p>Mã xác thực đăng nhập hệ thống Đại hội cổ đông: {{ $otp }}</p>
                <p><i>Lưu ý: Mã xác thực có hiệu lực tối đa trong vòng {{ $minutes_expired }} phút.</i></p>
            </div>

            <div class="footer">
                <p><b>ELCOM</b></p>
                <p>Trân trọng,</p>
                <p class="notice">Đây là email được gửi tự động từ hệ thống, vui lòng không phản hồi lại email này.</p>
            </div>
        </div>
    </div>

</body>
</html>
