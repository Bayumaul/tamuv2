<?php
$ahu = 2294;
$ki = 1054;
$tu = 83;
$fphd = 277;
$bh = 12;
$lain = 4;

?>

<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Antrean Layanan Kantor Wilayah Kementerian Hukum Daerah Istimewa Yogyakarta">
    <meta name="author" content="Kanwil Kemenkum DIY">
    <meta name="keywords" content="kanwil kemenkum diy, kemenkum, kemenkum diy, kantor wilayah, kementerian hukum">

    <!-- OPEN GRAPH (OG) TAGS -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Antrean Elektronik - Kanwil Kemenkum DIY" />
    <meta property="og:description"
        content="Sistem Antrean dan Antrian Elektronik Kantor Wilayah Kementerian Hukum Daerah Istimewa Yogyakarta" />
    <meta property="og:image" content="https://kemenkumjogja.id/img.png" />
    <meta property="og:url" content="https://kemenkumjogja.id" />
    <meta property="og:site_name" content="KemenkumJogja.ID" />

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('templates/sash/') }}/images/logo.png" />
    <!-- TITLE -->
    <title>Antrean Layanan Kanwil Kementrian Hukum DIY</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ asset('templates/sash/') }}/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- STYLE CSS -->
    <link href="{{ asset('templates/sash/') }}/css/style.css" rel="stylesheet">

    <!-- Plugins CSS -->
    <link href="{{ asset('templates/sash/') }}/css/plugins.css" rel="stylesheet">

    <!--- FONT-ICONS CSS -->
    <link href="{{ asset('templates/sash/') }}/css/icons.css" rel="stylesheet">

    <!-- INTERNAL Switcher css -->
    <link href="{{ asset('templates/sash/') }}/switcher/css/switcher.css" rel="stylesheet">
    <link href="{{ asset('templates/sash/') }}/switcher/demo.css" rel="stylesheet">

</head>

<body class="app ltr landing-page horizontal">

    <!-- GLOBAL-LOADER -->
    <div id="global-loader">
        <img src="{{ asset('templates/sash/') }}/images/loader.svg" class="loader-img" alt="Loader">
    </div>
    <!-- /GLOBAL-LOADER -->

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">
            <div class="hor-header header">
                <div class="container main-container">
                    <div class="d-flex">
                        <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle=""
                            href="javascript:void(0)"></a>
                        <!-- sidebar-toggle-->
                        <a class="logo-horizontal " href="index.html">
                            <img src="{{ asset('templates/sash/') }}/images/logo.png" width="10px" height="47px"
                                class="header-brand-img desktop-logo" alt="logo">
                            <img src="{{ asset('templates/sash/') }}/images/logo.png" width="10px" height="47px"
                                class="header-brand-img light-logo1" alt="logo">
                        </a>
                        <!-- LOGO -->
                        <div class="d-flex order-lg-2 ms-auto header-right-icons">
                            <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto collapsed"
                                type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4"
                                aria-controls="navbarSupportedContent-4" aria-expanded="false"
                                aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon fe fe-more-vertical"></span>
                            </button>
                            <div class="navbar navbar-collapse responsive-navbar p-0">
                                <div class="navbar-collapse bg-white px-0 collapse" id="navbarSupportedContent-4"
                                    style="">
                                    <!-- SEARCH -->
                                    <div class="header-nav-right p-5">
                                        <!-- <a href="register.html" class="btn ripple btn-min w-sm btn-outline-primary me-2 my-auto" target="_blank">New User
                                        </a>
                                        <a href="login.html" class="btn ripple btn-min w-sm btn-primary me-2 my-auto" target="_blank">Login
                                        </a> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="landing-top-header overflow-hidden">
                <div class="top sticky">
                    <!--APP-SIDEBAR-->
                    <div class="app-sidebar bg-light horizontal-main">
                        <div class="container">
                            <header class="border-bottom border-2 border-primary sticky-top">
                                <div class="container py-3">
                                    <div class="d-flex justify-content-between align-items-center">

                                        <!-- Left Side: Logo & Title -->
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('templates/sash/') }}/images/logo.png"
                                                alt="Logo Kemenkumham" width="50px" class="me-3">
                                            <div style="color: #002147;" class="d-none d-md-block">
                                                <h1 class="h5 mb-0 fw-bold">KANWIL KEMENTERIAN HUKUM RI</h1>
                                                <h1 class="h5 mb-0 fw-bold"> DAERAH ISTIMEWA YOGYAKARTA</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </header>

                        </div>
                    </div>
                    <!--/APP-SIDEBAR-->
                </div>
                <div class="bg-light demo-screen-headline main-demo main-demo-1 spacing-top overflow-hidden reveal"
                    id="home">
                    <div class="container px-sm-0">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 mb-5 pb-5 animation-zidex pos-relative">
                                <h4 class="fw-semibold mt-7"></h4>
                                <h2 class="text-start fw-bold">SELAMAT DATANG DI Antrean KANTOR WILAYAH KEMENTERIAN
                                    HUKUM D.I YOGYAKARTA <span class="text-primary animate-heading"></span></h2>
                                <p class="pb-3 mb-3"> Sistem pendaftaran kunjungan digital yang cepat, dan mudah
                                    digunakan. Daftarkan kunjungan Anda secara online untuk pengalaman yang lebih
                                    efisien.</p>
                                <a href="{{ route('online') }}" class="btn ripple btn-min w-lg mb-3 me-2 btn-lg"
                                    style="background-color: #002147; color: #fff"><i
                                        class="fa fa-paper-plane me-2"></i> Layanan Online
                                </a>

                                <a href="{{ route('offline') }}" class="btn ripple btn-min w-lg mb-3 me-2 btn-lg"
                                    style="background-color: #6C5FFC; color: #fff"><i
                                        class="fa fa-paper-plane me-2"></i> Layanan Offline
                                </a>
                            </div>
                            <div class="col-xl-6 col-lg-6 my-auto">
                                <img src="https://spruko.com/demo/sash/dist/assets/images/landing/1.png"
                                    alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--app-content open-->
            <div class="main-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container">
                        <div class="">

                            <!-- ROW-1 OPEN -->
                            <div class="section pb-0">
                                <div class="container">
                                    <div class="row">
                                        <h4 class="text-center fw-semibold">Statistics</h4>
                                        <span class="landing-title"></span>
                                        <h2 class="text-center fw-semibold mb-7">Statistik Tahun 2025</h2>
                                    </div>
                                    <div class="row text-center services-statistics landing-statistics">
                                        <div class="col-xl-2 col-md-4 col-lg-2">
                                            <div class="card">
                                                <div class="card-body bg-secondary-transparent">
                                                    <div class="counter-status">
                                                        <div class="">
                                                            <img src="{{ asset('img/') }}/djki.png" height="90px"
                                                                width="12px"
                                                                class="header-brand-img desktop-logo mb-2"
                                                                alt="logo">
                                                        </div>
                                                        <div class="test-body text-center">
                                                            <h1 class="mb-1">
                                                                <span
                                                                    class="counter fw-semibold counter "><?php echo $ki; ?></span>
                                                            </h1>
                                                            <div class="counter-text">
                                                                <h5 class="font-weight-normal mb-0 ">Kekayaan
                                                                    Intelektual </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-lg-2">
                                            <div class="card">
                                                <div class="card-body bg-secondary-transparent">
                                                    <div class="counter-status">
                                                        <div class="">
                                                            <img src="{{ asset('img/') }}/ahu.png" height="90px"
                                                                width="12px"
                                                                class="header-brand-img desktop-logo mb-2"
                                                                alt="logo">
                                                        </div>
                                                        <div class="text-body text-center">
                                                            <h1 class="mb-1">
                                                                <span
                                                                    class="counter fw-semibold counter "><?php echo $ahu; ?></span>
                                                            </h1>
                                                            <div class="counter-text">
                                                                <h5 class="font-weight-normal mb-0 ">Administrasi Hukum
                                                                    Umum
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-lg-2">
                                            <div class="card">
                                                <div class="card-body bg-secondary-transparent">
                                                    <div class="counter-status">
                                                        <div class="">
                                                            <img src="{{ asset('img/') }}/logo.png" height="90px"
                                                                width="12px"
                                                                class="header-brand-img desktop-logo mb-2"
                                                                alt="logo">
                                                        </div>
                                                        <div class="text-body text-center">
                                                            <h1 class="mb-1">
                                                                <span
                                                                    class="counter fw-semibold counter "><?php echo $tu; ?></span>
                                                            </h1>
                                                            <div class="counter-text">
                                                                <h5 class="font-weight-normal mb-0 ">Tata Usaha dan
                                                                    Umum</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-lg-2">
                                            <div class="card">
                                                <div class="card-body bg-secondary-transparent">
                                                    <div class="counter-status">
                                                        <div class="">
                                                            <img src="{{ asset('img/') }}/djpp.svg" width="12px"
                                                                height="90px"
                                                                class="header-brand-img desktop-logo mb-2"
                                                                alt="logo">
                                                        </div>
                                                        <div class="text-body text-center">
                                                            <h1 class="mb-1">
                                                                <span
                                                                    class="counter fw-semibold counter "><?php echo $fphd; ?></span>
                                                            </h1>
                                                            <div class="counter-text">
                                                                <h5 class="font-weight-normal mb-0 ">Fasilitasi Produk
                                                                    Hukum Daerah
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-lg-2">
                                            <div class="card">
                                                <div class="card-body bg-secondary-transparent">
                                                    <div class="counter-status">
                                                        <div class="">
                                                            <img src="{{ asset('img/') }}/bphn.png" width="12px"
                                                                height="90px"
                                                                class="header-brand-img desktop-logo mb-2"
                                                                alt="logo">
                                                        </div>
                                                        <div class="text-body text-center">
                                                            <h1 class="mb-1">
                                                                <span
                                                                    class="counter fw-semibold counter "><?php echo $bh; ?></span>
                                                            </h1>
                                                            <div class="counter-text">
                                                                <h5 class="font-weight-normal mb-0 ">Pembinaan dan
                                                                    Bantuan Hukum
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-lg-2">
                                            <div class="card">
                                                <div class="card-body bg-secondary-transparent">
                                                    <div class="counter-status">
                                                        <div class="">
                                                            <img src="{{ asset('img/') }}/logo.png" width="12px"
                                                                height="90px"
                                                                class="header-brand-img desktop-logo mb-2"
                                                                alt="logo">
                                                        </div>
                                                        <div class="text-body text-center">
                                                            <h1 class="mb-1">
                                                                <span
                                                                    class="counter fw-semibold counter "><?php echo $lain; ?></span>
                                                            </h1>
                                                            <div class="counter-text">
                                                                <h5 class="font-weight-normal mb-0 ">Lain-lain
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-1 CLOSED -->

                            <!-- ROW-2 OPEN -->
                            <div class="sptb section bg-image-style" id="Features">
                                <div class="container">
                                    <div class="row">
                                        <h4 class="text-center fw-semibold">Features</h4>
                                        <span class="landing-title"></span>
                                        <h2 class="fw-semibold text-center">Mengapa Menggunakan Antrean? </h2>
                                        <p class="text-default mb-5 text-center">Sistem modern yang mengutamakan
                                            keamanan, efisiensi, dan kemudahan akses untuk semua pengunjung .</p>
                                        <div class="row">
                                            <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                                <div class="card  text-start landing-missions">
                                                    <div class="card-body">
                                                        <div class="align-items-top">
                                                            <div class="mb-2"> <span
                                                                    class="avatar avatar-lg avatar-rounded bg-primary-transparent">
                                                                    <i class="text-dark fa fa-globe"
                                                                        data-bs-toggle="tooltip"
                                                                        title="fa fa-globe"></i>
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <h6 class="fw-semibold mb-1"> Akses Mudah Kapan Saja
                                                                </h6>
                                                                <p class="mb-0 text-muted">Pendaftar dapat mendaftar
                                                                    secara online dari perangkat apapun, tanpa perlu
                                                                    datang langsung hanya untuk mengisi data.
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                                <div class="card  text-start landing-missions">
                                                    <div class="card-body">
                                                        <div class="align-items-top">
                                                            <div class="mb-2"> <span
                                                                    class="avatar avatar-lg avatar-rounded bg-primary-transparent">
                                                                    <i class="text-dark fa fa-bolt"
                                                                        data-bs-toggle="tooltip"
                                                                        title="fa fa-bolt"></i>
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <h6 class="fw-semibold mb-1"> Proses Cepat dan Efisien
                                                                </h6>
                                                                <p class="mb-0 text-muted">Formulir digital
                                                                    mempersingkat waktu pendaftaran kunjungan dengan
                                                                    alur yang sederhana dan sistematis.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                                <div class="card  text-start landing-missions">
                                                    <div class="card-body">
                                                        <div class="align-items-top">
                                                            <div class="mb-2"> <span
                                                                    class="avatar avatar-lg avatar-rounded bg-primary-transparent">
                                                                    <i class="text-dark fa fa-lock"
                                                                        data-bs-toggle="tooltip"
                                                                        title="fa fa-lock"></i>
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <h6 class="fw-semibold mb-1"> Keamanan Data Terjamin
                                                                </h6>
                                                                <p class="mb-0 text-muted">Data kunjungan disimpan
                                                                    secara terenkripsi dan hanya dapat diakses oleh
                                                                    petugas yang berwenang.

                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                                <div class="card  text-start landing-missions">
                                                    <div class="card-body">
                                                        <div class="align-items-top">
                                                            <div class="mb-2"> <span
                                                                    class="avatar avatar-lg avatar-rounded bg-primary-transparent">
                                                                    <i class="text-dark fa fa-line-chart"
                                                                        data-bs-toggle="tooltip"
                                                                        title="fa fa-line-chart"></i>
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <h6 class="fw-semibold mb-1"> Rekapitulasi Otomatis
                                                                </h6>
                                                                <p class="mb-0 text-muted">Setiap kunjungan tercatat
                                                                    rapi dan dapat dilihat secara real-time oleh admin
                                                                    untuk keperluan monitoring dan pelaporan.

                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-2 CLOSED -->
                            <section class="py-5 bg-light">
                                <div class="container">
                                    <div class="text-center mb-5">
                                        <h2 class="fw-bold text-dark">Cara Menggunakan Antrean</h2>
                                        <p class="text-muted">Proses pendaftaran yang sederhana dalam 3 langkah mudah
                                        </p>
                                    </div>

                                    <div class="row text-center">
                                        <!-- Step 1 -->
                                        <div class="col-md-4 mb-4">
                                            <div class="mb-3">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                                    style="width: 50px; height: 50px; font-weight: bold;">
                                                    1
                                                </div>
                                            </div>
                                            <div class="card shadow-sm border-0">
                                                <div class="card-body">
                                                    <i class="fa fa-file fa-2x text-primary mb-3"
                                                        data-bs-toggle="tooltip" title="fa fa-file"></i>
                                                    <h5 class="card-title fw-semibold">Mendaftar Secara Online</h5>
                                                    <p class="card-text text-muted">Isi formulir digital dengan data
                                                        diri, instansi, dan keperluan kunjungan Anda.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Step 2 -->
                                        <div class="col-md-4 mb-4">
                                            <div class="mb-3">
                                                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                                    style="width: 50px; height: 50px; font-weight: bold;">
                                                    2
                                                </div>
                                            </div>
                                            <div class="card shadow-sm border-0">
                                                <div class="card-body">
                                                    <i class="fa fa-whatsapp fa-2x text-warning mb-3"
                                                        data-bs-toggle="tooltip" title="fa fa-whatsapp"></i>
                                                    <h5 class="card-title fw-semibold">Konfirmasi via WhatsApp</h5>
                                                    <p class="card-text text-muted">Setelah mendaftar, Anda akan
                                                        menerima konfirmasi otomatis melalui WhatsApp.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Step 3 -->
                                        <div class="col-md-4 mb-4">
                                            <div class="mb-3">
                                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                                    style="width: 50px; height: 50px; font-weight: bold;">
                                                    3
                                                </div>
                                            </div>
                                            <div class="card shadow-sm border-0">
                                                <div class="card-body">
                                                    <i class="fa fa-tasks fa-2x text-success mb-3"
                                                        data-bs-toggle="tooltip" title="fa fa-tasks"></i>
                                                    <h5 class="card-title fw-semibold">Laporan Ditindaklanjuti</h5>
                                                    <p class="card-text text-muted">Kunjungan Anda akan diteruskan dan
                                                        ditindaklanjuti oleh petugas terkait.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                        </div>
                    </div>
                    <!-- CONTAINER CLOSED-->
                </div>
            </div>
            <!--app-content closed-->
        </div>

        <!-- FOOTER OPEN -->
        <div class="demo-footer">
            <div class="container">
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <footer class="main-footer px-0 pb-0 text-center">
                                <div class="row ">
                                    <div class="col-md-12 col-sm-12">
                                        Copyright © <span id="year"></span> Kantor Wilayah Kementerian Hukum D.I.
                                        Yogyakarta.
                                        <span class="fa fa- text-danger"></span> <a href="javascript:void(0)"> </a>
                                        All rights reserved.
                                    </div>
                                </div>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FOOTER CLOSED -->
    </div>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- JQUERY JS -->
    <script src="{{ asset('templates/sash/') }}/js/jquery.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="{{ asset('templates/sash/') }}/plugins/bootstrap/js/popper.min.js"></script>
    <script src="{{ asset('templates/sash/') }}/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- COUNTERS JS-->
    <script src="{{ asset('templates/sash/') }}/plugins/counters/counterup.min.js"></script>
    <script src="{{ asset('templates/sash/') }}/plugins/counters/waypoints.min.js"></script>
    <script src="{{ asset('templates/sash/') }}/plugins/counters/counters-1.js"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="{{ asset('templates/sash/') }}/plugins/owl-carousel/owl.carousel.js"></script>
    <script src="{{ asset('templates/sash/') }}/plugins/company-slider/slider.js"></script>

    <!-- Star Rating Js-->
    <script src="{{ asset('templates/sash/') }}/plugins/rating/jquery-rate-picker.js"></script>
    <script src="{{ asset('templates/sash/') }}/plugins/rating/rating-picker.js"></script>

    <!-- Star Rating-1 Js-->
    <script src="{{ asset('templates/sash/') }}/plugins/ratings-2/jquery.star-rating.js"></script>
    <script src="{{ asset('templates/sash/') }}/plugins/ratings-2/star-rating.js"></script>

    <!-- Sticky js -->
    <script src="{{ asset('templates/sash/') }}/js/sticky.js"></script>

    <!-- CUSTOM JS -->
    <script src="{{ asset('templates/sash/') }}/js/landing.js"></script>

</body>

</html>
