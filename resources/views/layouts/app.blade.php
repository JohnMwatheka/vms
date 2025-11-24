<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Primary Meta Tags -->
        <title>Vendor Management System – Secure Vendor Onboarding & Approval Pipeline</title>
        <meta name="title" content="Vendor Management System – Secure Vendor Onboarding & Approval Pipeline">
        <meta name="description" content="A secure, role-based vendor management system with 8-stage approval workflow: Initiator → Vendor → Checker → Procurement → Legal → Finance → Directors → Approved. Built with Laravel 12.">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="Vendor Management System – Secure Vendor Onboarding & Approval Pipeline">
        <meta property="og:description" content="Enterprise-grade vendor approval workflow with role-based access, audit trail, document upload, and instant demo role switching.">
        <meta property="og:image" content="{{ asset('images/og-preview.jpg') }}"> <!-- Optional: add a 1200x630 preview image -->
        <meta property="og:site_name" content="{{ config('app.name') }}">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url()->current() }}">
        <meta property="twitter:title" content="Vendor Management System – Laravel 12 Take-Home Project">
        <meta property="twitter:description" content="Full 8-stage vendor approval system with role switcher, history, and production-ready code.">
        <meta property="twitter:image" content="{{ asset('images/og-preview.jpg') }}">

        <!-- Favicon (recommended to have these) -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

        <!-- Theme Color -->
        <meta name="theme-color" content="#6366f1"> <!-- Indigo-500 -->

        <!-- Robots -->
        <meta name="robots" content="noindex, nofollow"> <!-- Change to "index, follow" in production if public -->

        <!-- Canonical URL -->
        <link rel="canonical" href="{{ url()->current() }}">

        <!-- Performance & Preload -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="preconnect" href="https://cdn.jsdelivr.net">
        <link rel="dns-prefetch" href="//fonts.bunny.net">
        <link rel="dns-prefetch" href="//cdn.jsdelivr.net">

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- App CSS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .font-sans { font-family: Figtree, sans-serif !important; }
            .bg-gray-100 { background-color: #f7fafc !important; }
            .bg-white { background-color: #ffffff !important; }
            .shadow { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important; }
            .min-h-screen { min-height: 100vh !important; }
            .antialiased { -webkit-font-smoothing: antialiased !important; -moz-osx-font-smoothing: grayscale !important; }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        
        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <!-- AJAX Setup -->
        <script>
            $(document).ready(function() {
                // AJAX setup with CSRF token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Global AJAX error handler
                $(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
                    console.error('AJAX Error:', thrownError);
                    // You can add custom error handling here
                });

                // Global AJAX success handler
                $(document).ajaxSuccess(function(event, xhr, settings) {
                    console.log('AJAX Success:', settings.url);
                });
            });

            // Example AJAX utility function
            function makeAjaxRequest(url, method = 'GET', data = {}) {
                return $.ajax({
                    url: url,
                    method: method,
                    data: data,
                    dataType: 'json'
                });
            }

            // Example function for form submission via AJAX
            function submitFormViaAjax(formElement, successCallback, errorCallback) {
                const formData = new FormData(formElement);
                
                $.ajax({
                    url: formElement.action,
                    method: formElement.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: successCallback,
                    error: errorCallback
                });
            }
        </script>
    </body>
</html>