<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }} - @yield('title', 'CMS')</title>
    <meta name="description" content="{{ $description ?? '' }}">
    <meta name="keywords" content="{{ $keywords ?? '' }}">
    <meta name="author" content="{{ $author ?? '' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="{{ asset('modules/cms/static/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('modules/cms/static/js/axios.min.js') }}"></script>
    <script src="{{ asset('modules/cms/static/js/vue.global.js') }}"></script>
    
    <!-- Styles -->
    @vite(['D:/datn_duong/agm/Modules/CMS/resources/assets/sass/app.scss', 'D:/datn_duong/agm/Modules/CMS/resources/assets/js/app.js'], 'build-cms')
    @stack('styles')
</head>
<body data-flash-message="{{ session('flash_message') }}" data-flash-type="{{ session('flash_type') }}">
    @include('cms::layouts.partials.toast')

    <div class="page">
        <!-- Navbar -->
        @include('cms::layouts.partials.navbar')

        <!-- Main container -->
        <div class="main-container">
            <!-- Sidebar -->
            @include('cms::layouts.partials.sidebar')

            <!-- Content area -->
            <div class="content-wrapper">
                <div class="h-100">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
@if(session('api_token_admin'))
<script>
    // Lưu token vào localStorage
    localStorage.setItem('access_token_admin', '{{ session("api_token_admin") }}');
    
    // Lưu thông tin user vào localStorage
    @if(session('user_data'))
        localStorage.setItem('user', JSON.stringify({
            id: {{ session('user_data.id') }},
            name: '{{ session('user_data.name') }}',
            email: '{{ session('user_data.email') }}'
        }));
    @endif
</script>
@endif
