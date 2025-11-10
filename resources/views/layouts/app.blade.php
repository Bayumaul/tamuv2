@php
    $name = Auth::user()->name ?? 'User'; // fallback jika belum login
    $words = explode(' ', trim($name));
    $initials = '';

    // ambil huruf pertama dari dua kata pertama
    if (count($words) > 0) {
        $initials .= strtoupper(substr($words[0], 0, 1)); // huruf pertama kata pertama
    }
    if (count($words) > 1) {
        $initials .= strtoupper(substr($words[1], 0, 1)); // huruf pertama kata kedua
    } else {
        // jika cuma satu kata, ambil dua huruf pertama
        $initials = strtoupper(substr($words[0], 0, 2));
    }
@endphp

<!doctype html>

<html lang="en" class="layout-navbar-fixed layout-menu-fixed layout-wide" dir="ltr" data-skin="default"
    data-assets-path="{{ asset('templates/vuexy/') }}/assets/" data-template="vertical-menu-template-starter"
    data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>
        @yield('title') &bull; Antrean Kanwil DIY
    </title>
    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('templates/sash/') }}/images/logo.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('templates/vuexy/') }}/assets/vendor/fonts/iconify-icons.css" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="{{ asset('templates/vuexy/') }}/assets/vendor/libs/node-waves/node-waves.css" />

    <link rel="stylesheet" href="{{ asset('templates/vuexy/') }}/assets/vendor/libs/pickr/pickr-themes.css" />

    <link rel="stylesheet" href="{{ asset('templates/vuexy/') }}/assets/vendor/css/core.css" />
    <link rel="stylesheet" href="{{ asset('templates/vuexy/') }}/assets/css/demo.css" />

    <link rel="stylesheet" href="{{ asset('templates/vuexy/') }}/assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet"
        href="{{ asset('templates/vuexy/') }}/assets/vendor/libs/bootstrap-select/bootstrap-select.css" />
    <!-- Vendors CSS -->

    <link rel="stylesheet"
        href="{{ asset('templates/vuexy/') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- endbuild -->

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/js/template-customizer.js"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="{{ asset('templates/vuexy/') }}/assets/js/config.js"></script>
    {{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <link rel="stylesheet" href="{{ asset('templates/vuexy/') }}/assets/vendor/libs/sweetalert2/sweetalert2.css" />

    <link rel="stylesheet"
        href="{{ asset('templates/vuexy/') }}/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('templates/vuexy/') }}/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    {{-- <link rel="stylesheet" href="{{ asset('templates/vuexy/') }}/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" /> --}}
    <link rel="stylesheet" href="{{ asset('templates/vuexy/') }}/assets/vendor/libs/flatpickr/flatpickr.css" />
    <!-- Row Group CSS -->
    {{-- <link rel="stylesheet" href="{{ asset('templates/vuexy/') }}/assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" /> --}}
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu">
                <div class="app-brand demo">
                    <a href="{{ url('/') }}" class="app-brand-link d-flex align-items-center">
                        <span class="app-brand-logo demo">
                            <img src="{{ asset('templates/sash/images/logo.png') }}" alt="Logo Kemenkum DIY"
                                width="40" height="40" class="img-fluid ">
                        </span>
                        <span class="app-brand-text demo menu-text fw-bold ms-3">
                            Kemenkum DIY
                        </span>
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                        <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
                        <i class="icon-base ti tabler-x d-block d-xl-none"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Page -->
                    <li class="menu-item {{ request()->is('dashboard*') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.index') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-smart-home"></i>
                            <div>Dashboard</div>
                        </a>
                    </li>
                    @if (Auth::check() && Auth::user()->role === 'petugas')
                        <li class="menu-item {{ request()->is('call*') ? 'active' : '' }}">
                            <a href="{{ route('call') }}" class="menu-link">
                                <i class="menu-icon icon-base ti tabler-outbound"></i>
                                <div>Panggilan Antrian</div>
                            </a>
                        </li>
                    @endif
                    <li class="menu-item {{ request()->is('stats*') ? 'active' : '' }}">
                        <a href="{{ route('stats.layanan.index') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-chart-arrows-vertical"></i>
                            <div>Statistik</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('reports*') ? 'active' : '' }}">
                        <a href="{{ route('reports.visits.index') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-clipboard-data"></i>
                            <div>Rekap</div>
                        </a>
                    </li>
                    @if (Auth::check() && Auth::user()->role === 'admin')
                        <li class="menu-header small text-uppercase">
                            <span class="menu-header-text">ADMINISTRASI SISTEM</span>
                        </li>
                        <li class="menu-item {{ request()->is('survey*') ? 'active' : '' }}">
                            <a href="{{ route('survey.index') }}" class="menu-link">
                                <i class="menu-icon icon-base ti tabler-device-imac-search"></i>
                                <div>Survey</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->is('admin*') ? 'active' : '' }}">
                            <a href="{{ route('admin.notif.form') }}" class="menu-link">
                                <i class="menu-icon icon-base ti tabler-notification"></i>
                                <div>Notifikasi</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->is('users*') ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}" class="menu-link">
                                <i class="menu-icon icon-base ti tabler-users"></i>
                                <div>User</div>
                            </a>
                        </li>
                    @endif
                    <li class="menu-item">
                        <a href="{{ route('monitor.display') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-screen-share"></i>
                            <div>Monitoring</div>
                        </a>
                    </li>
                </ul>
            </aside>

            <div class="menu-mobile-toggler d-xl-none rounded-1">
                <a href="javascript:void(0);"
                    class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
                    <i class="ti tabler-menu icon-base"></i>
                    <i class="ti tabler-chevron-right icon-base"></i>
                </a>
            </div>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                            <i class="icon-base ti tabler-menu-2 icon-md"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item dropdown me-2 me-xl-0">
                                <a class="nav-link dropdown-toggle hide-arrow" id="nav-theme"
                                    href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <i class="icon-base ti tabler-sun icon-md theme-icon-active"></i>
                                    <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="nav-theme-text">
                                    <li>
                                        <button type="button" class="dropdown-item align-items-center active"
                                            data-bs-theme-value="light" aria-pressed="false">
                                            <span><i class="icon-base ti tabler-sun icon-md me-3"
                                                    data-icon="sun"></i>Light</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item align-items-center"
                                            data-bs-theme-value="dark" aria-pressed="true">
                                            <span><i class="icon-base ti tabler-moon-stars icon-md me-3"
                                                    data-icon="moon-stars"></i>Dark</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item align-items-center"
                                            data-bs-theme-value="system" aria-pressed="false">
                                            <span><i class="icon-base ti tabler-device-desktop-analytics icon-md me-3"
                                                    data-icon="device-desktop-analytics"></i>System</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <ul class="navbar-nav flex-row align-items-center ms-md-auto">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-l">
                                        <span
                                            class="avatar-initial rounded-circle bg-primary">{{ $initials }}</span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-l">
                                                        <span
                                                            class="avatar-initial rounded-circle bg-primary">{{ $initials }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">{{ Auth::user()->username ?? 'Petugas' }}</h6>
                                                    <small class="text-body-secondary capitalize">{{ Auth::user()->role ?? 'Petugas' }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1 mx-n2"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="icon-base ti tabler-user icon-md me-3"></i><span>My
                                                Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i
                                                class="icon-base ti tabler-settings icon-md me-3"></i><span>Settings</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1 mx-n2"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                                class="icon-base ti tabler-power icon-md me-3"></i><span>Log
                                                Out</span></a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @yield('content')
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div
                                class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                                <div class="text-body">
                                    ©
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    Kantor Wilayah Kementerian Hukum D.I.
                                    Yogyakarta. All rights reserved
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/theme.js -->

    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/libs/jquery/jquery.js"></script>

    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/libs/node-waves/node-waves.js"></script>

    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/libs/@algolia/autocomplete-js.js"></script>

    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/libs/pickr/pickr.js"></script>

    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/libs/hammer/hammer.js"></script>

    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/js/menu.js"></script>

    <!-- Vendors JS -->
    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/libs/select2/select2.js"></script>
    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/libs/tagify/tagify.js"></script>
    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{ asset('templates/vuexy/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    {{-- <script src="{{ asset('templates/vuexy/') }}/assets/js/tables-datatables-basic.js"></script> --}}
    <script src="{{ asset('templates/vuexy/') }}/assets/js/extended-ui-sweetalert2.js"></script>
    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/libs/sweetalert2/sweetalert2.js"></script>

    <script src="{{ asset('templates/vuexy/') }}/assets/js/main.js"></script>
    <script>
        $(document).ready(function() {
            $('.data-table').DataTable();
        });
    </script>
    <!-- Page JS -->
    @stack('scripts')

</body>

</html>
