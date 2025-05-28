<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CMS Login</title>
    <meta name="description" content="{{ $description ?? 'CMS Login Page' }}">
    <meta name="keywords" content="{{ $keywords ?? 'CMS, Login' }}">
    <meta name="author" content="{{ $author ?? '' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    @vite(['resources/assets/sass/app.scss', 'resources/assets/js/app.js'], 'build-cms')
    @stack('styles')
    
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body data-flash-message="{{ session('flash_message') }}" data-flash-type="{{ session('flash_type') }}">
    <div class="main-body">
        @include('cms::layouts.partials.toast')
        <div class="container">
            @yield('content')
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    @stack('scripts')
</body>

</html>