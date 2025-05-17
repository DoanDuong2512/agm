<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In tài liệu</title>
    @vite(['resources/assets/sass/app.scss', 'resources/assets/js/app.js'], 'build-cms')
    @stack('styles')
</head>
<body>
    <div class="container my-4">
        @yield('content')
    </div>
    
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>