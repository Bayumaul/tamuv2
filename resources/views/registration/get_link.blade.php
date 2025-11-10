<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Selamat Datang di Aplikasi Kunjungan Kanwil Kementrian Hukum DIY" />
    <meta name="author" content="Kanwil Kemenkum DIY" />
    <meta name="keywords" content="kanwil kemenkum diy, kemenkum, kemenkum diy, kantor wilayah, kementerian hukum">

    <!-- OPEN GRAPH (OG) TAGS -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Sistem Antrian Elektronik - Kanwil Kemenkum DIY" />
    <meta property="og:description" content="Berikut adalah Kartu Antrian Elektronik Anda." />
    <meta property="og:image" content="https://kemenkumjogja.id/img.png" />
    <meta property="og:url" content="https://kemenkumjogja.id" />
    <meta property="og:site_name" content="KemenkumJogja.ID" />

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('templates/sash/') }}/images/logo.png" />

    <!-- TITLE -->
    <title>TamuKu - Aplikasi Kunjungan Kanwil Kementrian Hukum DIY</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ asset('templates/sash/') }}/plugins/bootstrap/css/bootstrap.min.css"
        rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="{{ asset('templates/sash/') }}/css/style.css" rel="stylesheet" />

    <!-- Plugins CSS -->
    <link href="{{ asset('templates/sash/') }}/css/plugins.css" rel="stylesheet" />

    <!--- FONT-ICONS CSS -->
    <link href="{{ asset('templates/sash/') }}/css/icons.css" rel="stylesheet" />

    <!-- INTERNAL Switcher css -->
    <link href="{{ asset('templates/sash/') }}/switcher/css/switcher.css" rel="stylesheet" />
    <link href="{{ asset('templates/sash/') }}/switcher/demo.css" rel="stylesheet" />
    <style>
        .btn-submit {
            border: 1px solid #002147;
            margin: 0 6px;
            border-radius: 0.375rem;
            background-color: #f8f9fa;
            color: #333;
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            background-color: #ffffff;
            box-shadow: 0 2px 6px #002147;
            color: #000;
        }

        .tabs-radio input[type="radio"]:checked+label {
            background-color: #002147;
            color: #fff;
            border-color: #002147 #002147 white;
            font-weight: bold;
        }
    </style>
</head>

<body class="app sidebar-mini ltr login-img">
    <!-- BACKGROUND-IMAGE -->
    <div class="">
        <!-- PAGE -->
        <div class="page">
            <div class="">
                <!-- Theme-Layout -->

                <!-- CONTAINER OPEN -->
                <div class="col col-login mx-auto mt-7 mb-5"></div>
                <div class="container">
                    <div class="row justify-content-center mt-5 mb-5">
                        <div class="col-lg-8">
                            <div class="card shadow-lg border-0">
                                <div class="card-body p-5">
                                    <div class="text-center mb-4">
                                        <a href="https://layanan.kemenkumjogja.id/index.html">
                                            <img src="{{ asset('templates/sash/') }}/images/logo.png" alt="Logo"
                                                class="mb-2" style="max-width: 120px" />
                                        </a>
                                        <h2 class="fw-bold">Formulir Layanan</h2>
                                        <h5 class="text-center fw-semibold mt-1">
                                            Silahkan Isi Data Berikut ini untuk mendapatkan Nomor Antrean
                                        </h5>
                                        <h4 class="text-center fw-semibold">
                                            Kanwil Kementrian Hukum DIY
                                        </h4>
                                    </div>
                                    @if (session('success'))
                                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                                            <span class="alert-inner--icon"><i class="fe fe-bell"></i></span>
                                            <span class="alert-inner--text">
                                                <strong>Berhasil!</strong> {{ session('success') }}
                                            </span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                    @endif
                                    <form action="{{ route('send.wa.link.submit') }}" method="POST"
                                        onsubmit="return validateForm()">
                                        @csrf
                                        <div class="mb-3">
                                            <div class="mb-3 form">
                                                <label for="no_hp" class="form-label">No Handphone / WhatsApp</label>
                                                <input type="tel" class="form-control" id="no_hp" name="no_hp"
                                                    placeholder="Masukkan Nomor HP / WhatsApp" required />
                                            </div>
                                            <div class="mb-3 form">
                                                <label for="priority_category" class="form-label">Kategori
                                                    Prioritas</label>
                                                <select class="form-select select2" id="priority_category"
                                                    name="priority_category_id" required>
                                                    @foreach ($priorityCategories as $category)
                                                        <option value="{{ $category->id }}"
                                                            data-level="{{ $category->priority_level }}"
                                                            {{ $category->id == 1 ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text text-muted">Pilih jika Anda termasuk Lansia, Ibu
                                                    Hamil, atau Disabilitas.</small>
                                            </div>
                                            <div class="mb-3 form">
                                                <label for="layanan" class="form-label">Layanan</label>
                                                <select class="form-control select2-show-search" name="layanan"
                                                    data-placeholder="Pilih Layanan ..">
                                                    <option value="">Pilih Jenis Layanan</option>

                                                    @foreach ($layanans as $layanan)
                                                        <optgroup label="{{ $layanan->nama_layanan }}">
                                                            @foreach ($layanan->details as $detail)
                                                                <option value="{{ $detail->id_layanan_detail }}">
                                                                    {{ $detail->nama_layanan_detail }}
                                                                </option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>

                                            </div>
                                            <div class="text-center mt-4 form">
                                                <button style="background-color: #002147; color: #fff" type="submit"
                                                    class="btn btn-submit btn-lg px-4 py-2 w-100 fw-bold">
                                                    <i class="fa fa-paper-plane me-2"></i> DAPATKAN NOMOR ANTRIAN
                                                </button>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- CONTAINER CLOSED -->
                @include('layouts.registrations.footer')
            </div>
        </div>
        <!-- End PAGE -->
    </div>
    <!-- BACKGROUND-IMAGE CLOSED -->

    <!-- JQUERY JS -->
    <script src="{{ asset('templates/sash/') }}/js/jquery.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="{{ asset('templates/sash/') }}/plugins/bootstrap/js/popper.min.js"></script>
    <script src="{{ asset('templates/sash/') }}/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- SHOW PASSWORD JS -->
    <script src="{{ asset('templates/sash/') }}/js/show-password.min.js"></script>

    <!-- GENERATE OTP JS -->
    <script src="{{ asset('templates/sash/') }}/js/generate-otp.js"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="{{ asset('templates/sash/') }}/plugins/p-scroll/perfect-scrollbar.js"></script>

    <!-- Color Theme js -->
    <script src="{{ asset('templates/sash/') }}/js/themeColors.js"></script>

    <!-- CUSTOM JS -->
    <script src="{{ asset('templates/sash/') }}/js/custom.js"></script>

    <!-- Custom-switcher -->
    <script src="{{ asset('templates/sash/') }}/js/custom-swicher.js"></script>

    <!-- Switcher js -->
    <script src="{{ asset('templates/sash/') }}/switcher/js/switcher.js"></script>
    <!-- SELECT2 JS -->
    <script src="{{ asset('templates/sash/') }}/plugins/select2/select2.full.min.js"></script>
    <script src="{{ asset('templates/sash/') }}/js/select2.js"></script>
    <!-- SWEET-ALERT JS -->
    <script src="{{ asset('templates/sash/') }}/plugins/sweet-alert/sweetalert.min.js"></script>
    <script src="{{ asset('templates/sash/') }}/js/sweet-alert.js"></script>
</body>

</html>
