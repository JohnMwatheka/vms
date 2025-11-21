<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Ensure Tailwind utilities take precedence over Bootstrap */
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
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
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