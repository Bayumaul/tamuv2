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
    <title>Antrean - Aplikasi Kunjungan Kanwil Kementrian Hukum DIY</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ asset('templates/sash/') }}/plugins/bootstrap/css/bootstrap.min.css"
        rel="stylesheet" />
    <base href="https://kemenkumjogja.id/" />

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

        #html-content-holder {
            width: 80mm;
            padding: 10px;
            margin: auto;
            font-size: 12px;
            background: white;
            color: black;
            border: 1px solid #ccc;
        }

        #canvas {
            display: none;
        }

        .camera {
            width: auto;
            display: inline-block;
        }

        .output {
            width: auto;
            text-align: center;
        }

        #startbutton {
            display: block;
            position: relative;
            margin-left: auto;
            margin-right: auto;
            bottom: 32px;
            background-color: rgba(0, 150, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow: 0px 0px 1px 2px rgba(0, 0, 0, 0.2);
            font-size: 14px;
            font-family: "Lucida Grande", "Arial", sans-serif;
            color: rgba(255, 255, 255, 1.0);
        }

        .contentarea {
            font-size: 16px;
            font-family: "Lucida Grande", "Arial", sans-serif;
            width: 760px;
        }

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
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

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
                                        <h2 class="fw-bold">Kartu Tamu Elektronik</h2>
                                        <h5 class="text-center fw-semibold mt-1">
                                            Silakan tunjukkan pesan ini ke petugas saat anda berada di pelayanan secara
                                            tatap muka
                                        </h5>
                                        <h4 class="text-center fw-semibold">
                                            Kanwil Kementrian Hukum DIY
                                        </h4>
                                    </div>
                                      {{-- === BLOK STATUS REAL-TIME (BARU) === --}}
                              <!-- Card Status Antrian -->
<div class="card shadow-sm border-0 rounded-4 mt-3 mb-3">
    <div class="card-body text-center p-4" style="background: linear-gradient(180deg, #f8fafc, #ffffff);">

        <h6 class="fw-bold text-secondary mb-3">
            <i class="bi bi-info-circle-fill text-primary me-1"></i> Status Anda
        </h6>
        <h4 class="fw-bold mb-4" style="color: #002147" id="status-saat-ini">Memuat...</h4>

        <div class="border-top border-bottom py-3 my-3">
            <p class="mb-1 text-muted fw-semibold">
                <i class="bi bi-person-check-fill text-success me-1"></i> Antrean Aktif Loket:
            </p>
            <h5 class="fw-bold text-dark" id="antrian-dipanggil">---</h5>
        </div>

        <div class="mb-3">
            <h6 class="fw-semibold text-danger mb-1">
                <i class="bi bi-exclamation-circle-fill me-1"></i> Posisi di Depan
            </h6>
            <h4 class="fw-bold text-dark" id="posisi-di-depan">Memuat...</h4>
        </div>

        <div class="bg-light p-3 rounded-3 shadow-sm mt-4">
            <h6 class="fw-semibold text-secondary mb-1">
                <i class="bi bi-clock-history text-warning me-1"></i> Estimasi Dilayani
            </h6>
            <h4 class="fw-bold text-dark mb-0" id="estimasi-waktu-display">Memuat...</h4>
        </div>
    </div>
</div>

                                {{-- === AKHIR BLOK STATUS REAL-TIME === --}}
                                    <section class="sheet padding-5mm" id="html-content-holder" style="color:black;">

                                        <div align="center"
                                            style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
                                            <br />
                                            <img src="{{ asset('img/logo.png') }}" class="mb-2" width="70"
                                                crossOrigin="anonymous" /><br>
                                            <strong>KARTU TAMU ELEKTRONIK</strong><br>
                                            <small><?php echo $tgl; ?></small><br />
                                            <small>---------------------------------------------------</small>
                                            <small>
                                                <div align="center"> <strong>NOMOR ANTRIAN </strong></div>
                                                <?php echo $layanan; ?><br>
                                                <img src="{{ $qrPath }}" alt="QR Code"
                                                    style="width:200px; height:200px;" crossOrigin="anonymous">

                                                <h1 style="margin-bottom: -2px; margin-top: -2px;"><?php echo $data['kode_layanan']; ?> -
                                                    <?php echo $no_antrian; ?></h1>
                                                <?php echo $data['nama']; ?><br>
                                            </small>
                                            <small>---------------------------------------------------</small>
                                            <br />
                                            <small><strong>
                                                    <div align="center">KANTOR WILAYAH</div>
                                                </strong></small>
                                            <small><strong>
                                                    <div align="center">KEMENTERIAN HUKUM REPUBLIK INDONESIA</div>
                                                </strong></small>
                                            <small><strong>
                                                    <div align="center">DAERAH ISTIMEWA YOGYAKARTA</div>
                                                </strong></small>
                                            <small>
                                                <div align="center">Jalan Gedongkuning No. 146 Yogyakarta</div>
                                            </small>
                                            <small>
                                                <div align="center">Telp. (0274) 378431 </div>
                                            </small>
                                            <small>
                                                <div align="center">https://jogja.kemenkum.go.id</div>
                                            </small>
                                            <br />
                                        </div>
                                    </section>
                                    <div class="sheet padding-5mm" align="center" style="padding-top: 10px;">
                                        &nbsp;
                                        <!-- <a id="btn-Preview-Image" href="#"
                      class="btn btn-info"><i class="fa fa-camera"></i> Buat Kartu</a>
                    &nbsp;
                    <a id="btn-Convert-Html2Image" href="#" class="btn btn-primary disabled" name="download"> <i class="fa fa-download"></i> Download
                    </a> -->
                                        &nbsp;
                                        <br />
                                        <!-- <button id="print" onclick='printDiv("html-content-holder");' class="btn btn-success disabled" name="print"><i class="fa fa-print"></i> Print
                    </button> -->
                                    </div>
                                    <div class="sheet padding-5mm" align="center" style="padding-top: 10px;">
                                        &nbsp;
                                        <!--<button type="button" style="background-color: #002147; color: #fff" id="btn-Preview-Image" href="#" class="btn btn-submit"><i class="fa fa-download"></i> Unduh Kartu</button>-->
                                        <button type="button" style="background-color: #002147; color: #fff"
                                            onclick="downloadImage()" class="btn btn-submit"><i
                                                class="fa fa-download"></i> Unduh Kartu</button>

                                        &nbsp;

                                        <a href="{{ url()->previous() }}" class="btn btn-warning"><i
                                                class="fa fa-arrow-circle-left me-2"></i> Kembali
                                        </a>
                                        <br />
                                    </div>
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
    <!-- <script src="js/html2canvas.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js"></script>
 <script>
        // KUNCI: ID Antrean Anda untuk dikirim ke API
        const ID_BUKU_SAYA = "{{ $data->id_buku }}"; 
        const API_STATUS_PRIBADI = "{{ route('api.public.personal_status') }}";

        function updatePersonalStatus() {
            $.ajax({
                url: API_STATUS_PRIBADI,
                method: 'GET',
                dataType: 'json',
                data: { id_buku: ID_BUKU_SAYA },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#status-saat-ini').text(response.status_saat_ini); 
                        $('#antrian-dipanggil').text(response.antrian_dipanggil || '---');
                        
                        const $alertDiv = $('#estimasi-alert');
                        
                        if (response.status_saat_ini === 'MENUNGGU') {
                            $('#estimasi-waktu-display').text(`± ${response.estimasi_menit} Menit (${response.waktu_dilayani} WIB)`);
                            $('#posisi-di-depan').text(response.posisi_di_depan + ' Orang Lagi');

                            // Ganti warna saat antrean mendekat (opsional)
                            if (response.posisi_di_depan <= 3) {
                                $alertDiv.css('background-color', '#fff3cd'); // Warning/Kuning
                            } else {
                                $alertDiv.css('background-color', '#f8f9fa'); // Normal
                            }

                        } else if (response.status_saat_ini === 'DIPANGGIL') {
                            $('#estimasi-waktu-display').html('<strong>ANDA SEDANG DIPANGGIL SEKARANG!</strong>');
                            $('#posisi-di-depan').text('0 Orang');
                            $alertDiv.css('background-color', '#d4edda'); // Success/Hijau
                            clearInterval(pollingInterval); 
                        } else if (response.status_saat_ini === 'SELESAI') {
                            $('#estimasi-waktu-display').text('LAYANAN ANDA SUDAH SELESAI. Terima kasih.');
                            $alertDiv.css('background-color', '#d1ecf1'); // Info/Biru
                            clearInterval(pollingInterval);
                        }
                    }
                },
                error: function() {
                    console.error("Gagal koneksi API status pribadi.");
                }
            });
        }

        function downloadImage(fileName) {
            const element = document.getElementById("html-content-holder");
            html2canvas(element, {
                scale: 2,
                useCORS: true // Penting karena ada gambar dari asset()
            }).then(canvas => {
                const image = canvas.toDataURL("image/png");
                const link = document.createElement("a");
                link.href = image;
                link.download = fileName;
                link.click();
            });
        }

        // Jalankan pertama kali saat load dan mulai polling
        $(document).ready(function() {
            updatePersonalStatus(); 
            const pollingInterval = setInterval(updatePersonalStatus, 10000); // Polling setiap 10 detik
        });
    </script>
    <script>
        function redirect() {
            window.setTimeout(function() {
                'use strict';
                console.log('window.top.location', window.top.location);
                console.log('window.location', window.location);

                if (window.location !== window.top.location) {
                    window.top.location = "<?php echo $url; ?>";
                }
            }, 5000);
        };

        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        $(document).ready(function() {
            if (window.location !== window.parent.location) {
                redirect();
            }

            var element = $("#html-content-holder");

            $("#btn-Preview-Image").on('click', function() {
                html2canvas(element, {
                    dpi: 1200,
                    scale: 6,
                    onrendered: function(canvas) {
                        // Dapatkan data gambar
                        var imgageData = canvas.toDataURL("image/png");

                        // Ubah menjadi aliran data untuk diunduh
                        var newData = imgageData.replace(/^data:image\/png/,
                            "data:application/octet-stream");

                        // Buat elemen <a> sementara untuk memicu unduhan
                        var downloadLink = document.createElement('a');
                        downloadLink.href = newData;
                        downloadLink.download = "KARTU PENGUNJUNG.png";

                        // Simulasikan klik pada elemen <a>
                        document.body.appendChild(downloadLink);
                        downloadLink.click();
                        document.body.removeChild(
                            downloadLink); // Bersihkan elemen setelah diunduh
                    }
                });
            });
        });


        function downloadImage() {
            const element = document.getElementById("html-content-holder");
            html2canvas(element, {
                scale: 2,
                useCORS: true
            }).then(canvas => {
                const image = canvas.toDataURL("image/png");
                const link = document.createElement("a");
                link.href = image;
                link.download = "KARTU PENGUNJUNG.png";
                link.click();
            });
        }
    </script>
</body>

</html>
