<!doctype html>
<html lang="en" class="layout-wide customizer-hide" dir="ltr"
    data-assets-path="{{ asset('templates/vuexy/') }}/assets/" data-template="vertical-menu-template"
    data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    {{-- Ganti Title --}}
    <title>@yield('title', 'Akses Sistem') - Antrean Kanwil Kemenkum DIY</title>

    <meta name="description"
        content="Halaman login khusus untuk Petugas Loket Kanwil Kementerian Hukum D.I. Yogyakarta." />

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('templates/sash/') }}/images/logo.png" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('templates/vuexy/') }}/assets/vendor/fonts/iconify-icons.css" />

    <link rel="stylesheet" href="{{ asset('templates/vuexy/') }}/assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="{{ asset('templates/vuexy/') }}/assets/vendor/css/core.css" />
    <link rel="stylesheet" href="{{ asset('templates/vuexy/') }}/assets/css/demo.css" />

    <link rel="stylesheet"
        href="{{ asset('templates/vuexy/') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet"
        href="{{ asset('templates/vuexy/') }}/assets/vendor/libs/@form-validation/form-validation.css" />

    <link rel="stylesheet" href="{{ asset('templates/vuexy/') }}/assets/vendor/css/pages/page-auth.css" />
<script src="{{ asset('templates/vuexy/') }}/assets/vendor/js/template-customizer.js"></script>
    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/js/helpers.js"></script>
    <script src="{{ asset('templates/vuexy/') }}/assets/js/config.js"></script>
</head>

<body>
    @yield('content')
    
    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/js/menu.js"></script>

    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/libs/@form-validation/popular.js"></script>
    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/libs/@form-validation/auto-focus.js"></script>

    <script src="{{ asset('templates/vuexy/') }}/assets/js/main.js"></script>

    <script src="{{ asset('templates/vuexy/') }}/assets/js/pages-auth.js"></script>
</body>

</html>
