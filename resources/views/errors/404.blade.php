<!doctype html>
<html lang="en" class="layout-wide customizer-hide" dir="ltr" data-skin="default"
    data-assets-path="{{ asset('templates/vuexy/assets/') }}" data-template="vertical-menu-template"
    data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    {{-- Title yang informatif --}}
    <title>404 | Halaman Tidak Ditemukan - Antrean Kanwil DIY</title>

    <meta name="description" content="Halaman yang Anda cari tidak dapat ditemukan di sistem Antrean Kanwil Kemenkum DIY." />

    <link rel="icon" type="image/x-icon" href="{{ asset('templates/vuexy/assets/img/favicon/favicon.ico') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('templates/vuexy/assets/vendor/fonts/iconify-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('templates/vuexy/assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('templates/vuexy/assets/vendor/css/pages/page-misc.css') }}" />
    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/js/template-customizer.js"></script>

    <script src="{{ asset('templates/vuexy/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('templates/vuexy/assets/js/config.js') }}"></script>
</head>

<body>
    <div class="container-xxl container-p-y">
        <div class="misc-wrapper">
            <h1 class="mb-2 mx-2" style="line-height: 6rem; font-size: 6rem">404</h1>
            <h4 class="mb-2 mx-2 text-primary">Akses Tidak Ditemukan (Page Not Found) ⚠️</h4>
            <p class="mb-6 mx-2">
                Mohon maaf, halaman atau tautan yang Anda cari tidak dapat ditemukan di sistem Antrean Kanwil Kementerian Hukum DIY.
            </p>
            
            {{-- Tombol kembali ke Beranda (Route Utama) --}}
            <a href="{{ url('/') }}" class="btn btn-primary mb-10 waves-effect waves-light">
                <i class="menu-icon icon-base ti tabler-home me-1"></i> Kembali ke Beranda
            </a>
            
            <div class="mt-4">
                {{-- Menggunakan asset() untuk path gambar --}}
                <img src="{{ asset('templates/vuexy/assets/img/illustrations/page-misc-error.png') }}" alt="Halaman Tidak Ditemukan" width="225" class="img-fluid" />
            </div>
        </div>
    </div>
    
    <div class="container-fluid misc-bg-wrapper">
        {{-- Menggunakan asset() untuk path background --}}
        <img src="{{ asset('templates/vuexy/assets/img/illustrations/bg-shape-image-light.png') }}"
            height="355" alt="page-misc-error"
            data-app-light-img="illustrations/bg-shape-image-light.png"
            data-app-dark-img="illustrations/bg-shape-image-dark.png" />
    </div>
    <script src="{{ asset('templates/vuexy/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('templates/vuexy/assets/vendor/js/bootstrap.js') }}"></script>
    </body>

</html>