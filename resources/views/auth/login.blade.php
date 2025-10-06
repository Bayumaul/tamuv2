<!doctype html>
<html lang="en" class="layout-wide customizer-hide" dir="ltr"
    data-assets-path="{{ asset('templates/vuexy/') }}/assets/" data-template="vertical-menu-template"
    data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    {{-- Ganti Title --}}
    <title>Login Petugas - Kanwil Kemenkum DIY</title>

    <meta name="description"
        content="Halaman login khusus untuk Petugas Loket Kanwil Kementerian Hukum D.I. Yogyakarta." />

    <link rel="icon" type="image/x-icon" href="{{ asset('templates/vuexy/') }}/assets/img/favicon/favicon.ico" />

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
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-6">
                <div class="card">
                    <div class="card-body">
                        
                        {{-- KANWIL LOGO & TITLE --}}
                        <div class="app-brand justify-content-center mb-6">
                            <a href="/" class="app-brand-link">
                                {{-- Ganti logo Vuexy dengan logo Kemenkum DIY --}}
                                <img src="{{ asset('img/logo.png') }}" alt="Logo Kemenkum" style="height: 35px;">
                                <span class="app-brand-text demo text-heading fw-bold ms-2">KANWIL KEMENKUM DIY</span>
                            </a>
                        </div>
                        
                        <h4 class="mb-1">Selamat Datang, Petugas! 👋</h4>
                        <p class="mb-6">Silakan masuk untuk mengakses Dashboard Antrean Loket Anda.</p>

                        {{-- Tampilkan Pesan Error Laravel --}}
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li> @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- FORM LOGIN (Diadaptasi untuk Laravel Breeze) --}}
                        <form id="formAuthentication"
        class="mb-4" method="POST" action="{{ route('login') }}">
    @csrf

    {{-- Username / Email --}}
    <div class="mb-6 form-control-validation">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
            name="username" value="{{ old('username') }}" placeholder="Masukkan username Anda" autofocus required />
        @error('username')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    {{-- Password --}}
    <div class="mb-6 form-password-toggle form-control-validation">
        <label class="form-label" for="password">Password</label>
        <div class="input-group input-group-merge">
            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                aria-describedby="password" required />
            <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
            @error('password')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    {{-- Remember Me & Forgot Password --}}
    <div class="my-8">
        <div class="d-flex justify-content-between">
            <div class="form-check mb-0 ms-2">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember" />
                <label class="form-check-label" for="remember_me"> Ingat Saya </label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">
                    <p class="mb-0">Lupa Password?</p>
                </a>
            @endif
        </div>
    </div>

    <div class="mb-6">
        <button class="btn btn-primary d-grid w-100" type="submit">Login ke Dashboard</button>
    </div>
    </form>

    {{-- Hapus bagian "New on our platform", "or", dan Social Login karena ini untuk Petugas --}}

    </div>
    </div>
    </div>
    </div>
    </div>
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
