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
                                        <a href="index.php">
                                            <img src="{{ asset('templates/sash/') }}/images/logo.png" alt="Logo"
                                                class="mb-2" style="max-width: 120px" />
                                        </a>
                                        <h2 class="fw-bold">Formulir Layanan</h2>
                                        <h5 class="text-center fw-semibold mt-1">
                                            Silahkan Isi Data Berikut ini untuk mendapatkan Pelayanan Online
                                        </h5>
                                        <h4 class="text-center fw-semibold">
                                            Kanwil Kementrian Hukum DIY
                                        </h4>
                                    </div>
                                    <div class="alert alert-success alert-dismissible fade show " role="alert">
                                        <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
                                        <span class="alert-inner--text"><strong> Data Ditemukan!</strong> Silahkan Pilih
                                            Layanan!</span>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                                        <span class="alert-inner--icon"><i class="fe fe-bell"></i></span>
                                        <span class="alert-inner--text"><strong>Data Tidak Ditemukan!</strong> Silahkan
                                            Isi Data Diri dan Pilih Layanan!</span>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('online.registration') }}" method="POST"
                                        onsubmit="return validateForm()">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="kategori" class="form-label">Kategori Pemohon</label>
                                            <div class="tabs-radio ">
                                                <label class="custom-control custom-radio-md">
                                                    <input type="radio" class="custom-control-input"
                                                        name="kategori_pengunjung" value="1" checked="">
                                                    <span class="custom-control-label">Perorangan</span>
                                                </label>
                                                <label class="custom-control custom-radio-md">
                                                    <input type="radio" class="custom-control-input"
                                                        name="kategori_pengunjung" value="2">
                                                    <span class="custom-control-label">Badan Usaha / Instansi</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nik" class="form-label">NIK</label>
                                            <input type="number" class="form-control" id="nik" name="nik"
                                                placeholder="Masukkan NIK" required />
                                        </div>
                                        <div class="mb-3 form">
                                            <label for="name" class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Masukkan Nama Lengkap" required />
                                        </div>
                                        <div class="mb-3 form">
                                            <label for="alamat" class="form-label">Alamat</label>
                                            <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan Alamat Lengkap"
                                                required></textarea>
                                        </div>
                                        <div class="mb-3 form">
                                            <label for="no_hp" class="form-label">No Handphone / WhatsApp</label>
                                            <input type="tel" class="form-control" id="no_hp" name="no_hp"
                                                placeholder="Masukkan Nomor HP / WhatsApp" required />
                                        </div>
                                        <div class="mb-3 form">
                                            <label for="kategori" class="form-label">Kategori</label>
                                            <select class="form-select select2" id="kategori" name="kategori"
                                                required>
                                                <option value="">Pilih Kategori</option>
                                                <option value="1">Permohonan Informasi</option>
                                                <option value="2">Konsultasi</option>
                                                <option value="3">Pengaduan</option>
                                            </select>
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
                                        <div class="mb-3 form">
                                            <label for="aduan" class="form-label">Pertanyaan / Aduan</label>
                                            <textarea class="form-control" id="aduan" name="aduan" rows="5"
                                                placeholder="Masukkan Pertanyaan / Aduan (opsional)"></textarea>
                                        </div>
                                        <div class="text-center mt-4 form">
                                            <button style="background-color: #002147; color: #fff" type="submit"
                                                class="btn btn-submit px-4 py-2">
                                                <i class="fa fa-paper-plane me-2"></i> Daftar 
                                            </button>
                                        </div>
                                        <div class="btn-periksa text-center mt-4">
                                            <button style="background-color: #002147; color: #fff" type="button"
                                                onclick="checkData()" id="check"
                                                class="btn btn-submit px-4 py-2">
                                                <i class="fa bi-info-circle me-2"></i> Periksa Data
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
    <script>
        $(".form").hide();
        $(".alert-success").hide();
        $(".alert-info").hide();

        function checkData() {
            const nik = document.getElementById('nik').value.trim();
            const noHp = document.getElementById('no_hp').value.trim();
            var kategori_pengunjung = document.querySelector('input[name="kategori_pengunjung"]:checked').value;;

            if (nik === '') {
                swal({
                    title: 'Error',
                    text: 'Harap masukkan NIK terlebih dahulu !',
                    type: 'error',
                });
            } else {
                if (!/^\d{16}$/.test(nik)) {
                    swal({
                        title: 'Error',
                        text: 'NIK harus 16 digit angka!',
                        type: 'error',
                    });
                    return false;
                }

                const url = "{{ url('/validatenik') }}?nik=" + nik + "&kategori=" + kategori_pengunjung;

                fetch(url)
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error("Something went wrong!");
                        }
                        return response.json(); // Parse the JSON data.
                    })
                    .then((result) => {
                        console.log(result);
                        if (result) {
                            const data = result;
                            console.log(data);
                            $("#name").val(data.nama);
                            $("#alamat").val(data.alamat);
                            $("#no_hp").val(data.no_hp);
                            $(".alert-success").show();
                        } else if (result.status === "not_found") {
                            $(".alert-info").show();
                        }

                        $(".form").show();
                        $(".btn-periksa").hide();
                    })
                    .catch((error) => {
                        console.error("Fetch error:", error);
                        $(".alert-danger").show();
                    });
            }
        }

        function validateForm() {
            const nik = document.getElementById('nik').value.trim();
            const noHp = document.getElementById('no_hp').value.trim();

            if (!/^\d{16}$/.test(nik)) {
                swal({
                    title: 'Error',
                    text: 'NIK harus 16 digit angka!',
                    type: 'error',
                });
                return false;
            }

            if (!/^08\d{8,11}$/.test(noHp)) {
                swal({
                    title: 'Error',
                    text: 'No HP harus diawali 08 dan 10-13 digit!',
                    type: 'error',
                });
                return false;
            }

            return true;
        }
    </script>
</body>

</html>
