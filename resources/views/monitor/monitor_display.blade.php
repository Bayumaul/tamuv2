<!doctype html>
<html lang="id" class="layout-navbar-fixed layout-menu-fixed layout-wide" dir="ltr"
    data-assets-path="{{ asset('templates/vuexy/') }}/assets/" data-template="vertical-menu-template-starter"
    data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Monitor Antrean | Kanwil Kemenkum DIY</title>

    <link rel="icon" type="image/png" href="{{ asset('img/') }}/logo.png" />

    <!-- Fonts & Core CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('templates/vuexy/') }}/assets/vendor/css/core.css" />
    <link rel="stylesheet" href="{{ asset('templates/vuexy/') }}/assets/css/demo.css" />
    <link rel="stylesheet" href="{{ asset('templates/vuexy/') }}/assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet"
        href="{{ asset('templates/vuexy/') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <style>
        /* ====== TEMA UTAMA ====== */
        body {
            background: linear-gradient(135deg, #7b1113, #0a1931);
            color: #fff;
            font-family: 'Public Sans', sans-serif;
            min-height: 100vh;
        }

        .logo-text {
            font-weight: 700;
            color: #ffd700;
            text-transform: uppercase;
        }

        .text-gold {
            color: #ffd700 !important;
        }

        /* ====== CARD UTAMA ====== */
        .card-gold {
            background: linear-gradient(145deg, #ffd700, #f4c10f);
            color: #0a1931;
            border: 3px solid #b8860b;
            border-radius: 1rem;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-gold:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(255, 215, 0, 0.3);
        }

        .card-navy {
            background: linear-gradient(145deg, #0d224a, #1b2f5a);
            border: 3px solid #ffd700;
            border-radius: 1rem;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-navy:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(255, 215, 0, 0.25);
        }

        /* ====== DETAIL UI ====== */
        #clock {
            font-size: 1.2rem;
            font-weight: 500;
            color: #ffd700;
        }

        .loket-card {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 215, 0, 0.5);
            border-radius: 0.75rem;
            padding: 1rem;
            color: #fff;
        }

        .loket-card h5 {
            color: #ffd700;
        }

        /* ====== OVERLAY AUDIO ====== */
        .overlay-audio {
            position: fixed;
            inset: 0;
            background: rgba(10, 25, 49, 0.95);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .overlay-card {
            background: #fff;
            color: #0a1931;
            padding: 2rem 3rem;
            border-radius: 1rem;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
            text-align: center;
        }

        /* ====== STATUS WARNA ANTREAN ====== */
        .bg-warning .main-call-display {
            color: #0a1931 !important;
        }

        .bg-success .main-call-display {
            color: #0a1931 !important;
        }

        .bg-navy .main-call-display {
            color: #ffd700 !important;
        }

        /* ====== MARQUEE ====== */
        .marquee-wrapper {
            background: rgba(255, 255, 255, 0.1);
            border-top: 3px solid #ffd700;
            border-radius: 0.75rem;
            padding: 0.75rem;
            margin-top: 1.5rem;
        }

        .marquee-text {
            font-size: 1.4rem;
            font-weight: 600;
            color: #ffd700;
        }

        /* ====== ANIMASI ANGKA ====== */
        #currentNumber {
            animation: pulse 1.3s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
</head>

<body>

    <!-- Overlay Audio Interaction -->
    <div id="audio-interaction-overlay" class="overlay-audio">
        <div class="overlay-card">
            <h3 class="fw-bold mb-3 text-navy">🔊 Monitor Audio Diblokir</h3>
            <p class="mb-3">Tekan tombol di bawah untuk mengaktifkan pengumuman suara.</p>
            <button id="start-audio-btn" class="btn btn-primary btn-lg fw-bold">
                <i class="ti ti-volume me-2"></i> Aktifkan Pengumuman
            </button>
        </div>
    </div>

    <div class="container-fluid py-3">
        <!-- Header -->
        <div class="row align-items-center mb-3">
            <div class="col-md-6 d-flex align-items-center">
                <img src="{{ asset('img/') }}/logo.png" alt="Logo Kemenkum" height="60" class="me-3" />
                <div>
                    <h5 class="logo-text mb-0">Kementerian Hukum dan HAM RI</h5>
                    <span>Kanwil D.I. Yogyakarta</span>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div id="clock" class="fw-semibold"></div>
                <small class="text-light">Jam Layanan: Senin–Kamis 08.00–15.00 | Jum’at Online</small>
            </div>
        </div>

        <!-- Display Utama -->
        <div class="row g-4 align-items-stretch">
            <div class="col-md-6">
                <div class="card card-gold text-center shadow-lg h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h4 class="fw-bold mb-3">Nomor Antrean Dipanggil</h4>
                        <h1 id="currentNumber" class="display-1 fw-bold main-call-display">---</h1>
                        <h4 id="currentLoket" class="fw-bold mt-3 text-navy">Loket -</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-navy shadow-lg h-100">
                    <div class="card-body p-0 rounded-3 overflow-hidden">
                        <div class="ratio ratio-16x9">
                            <iframe id="info-video"
                                src="https://www.youtube.com/embed/T-U2_ADY9qM?autoplay=1&mute=1&loop=1&playlist=T-U2_ADY9qM"
                                title="Video Informasi"
                                allow="autoplay; clipboard-write; encrypted-media; picture-in-picture"
                                allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Loket -->
        <div class="row g-3 mt-3 text-center" id="loketStatus">
            @for ($i = 1; $i <= 4; $i++)
                <div class="col-md-3 col-6">
                    <div class="loket-card">
                        <h5 class="fw-bold mb-1">LOKET {{ $i }}</h5>
                        <p class="fs-4 mb-0" id="loket-{{ $i }}">IDLE</p>
                    </div>
                </div>
            @endfor
        </div>

        <!-- Running Text -->
        <div class="marquee-wrapper text-center">
            <marquee behavior="scroll" direction="left" class="marquee-text">
                Selamat datang di layanan antrean Kanwil Kemenkum D.I. Yogyakarta | Mohon jaga ketertiban dan tetap
                tertib
            </marquee>
        </div>
    </div>

    <!-- JS -->
    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('templates/vuexy/') }}/assets/vendor/js/bootstrap.js"></script>

    <script>
        // URL API Laravel dari Route Name
        const API_DISPLAY_PROCESSOR = "{{ route('api.display.processor') }}";
        const API_LOKET_STATUS = "{{ route('api.public.loket_status') }}";
        const ASSET_URL = "{{ asset('audio') }}";
        const API_LAST_ACTIVE = "{{ route('api.public.last_active_call') }}";

        let isSpeaking = false;
        let lastProcessedQueueId = 0;

        const VIDEO_ELEMENT = document.getElementById('info-video');
        const VOLUME_NORMAL = 1.0; // 100%
        const VOLUME_DUCKING = 0.2; // 20%

        VIDEO_ELEMENT.volume = 1.0; // Atur volume default saat script dimuat


        function updateLastActiveDisplay() {
            $.ajax({
                url: API_LAST_ACTIVE,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Hanya update jika display sedang IDLE (tidak sedang mengumumkan panggilan)
                    if (!isSpeaking) {
                        if (response.status === 'success') {
                            // Update Tampilan Utama dengan nomor aktif terakhir
                            $('#currentNumber').text(response.nomor);
                            $('#currentLoket').text(`Terakhir Dipanggil: Loket ${response.loket}`);
                            $('#currentNumber').parent().removeClass('bg-warning bg-success').addClass(
                                'bg-info');
                        } else {
                            $('#currentNumber').text('---');
                            $('#currentLoket').text('Belum ada panggilan hari ini');
                            $('#currentNumber').parent().removeClass('bg-success bg-info').addClass(
                                'bg-warning');
                        }
                    }
                }
            });
        }

        // --- FUNGSI 1: MEMUTAR SUARA DARI FILE MP3 ---
        async function playAudioSequence(nomorLengkap, loketTujuan) {
            const [prefix, urut] = nomorLengkap.split('-');
            const urutDigits = urut.split('');

            let audioSequence = [
                'lonceng.mp3',
                'nomor_antrean.mp3',
                `${prefix}.mp3`, // Contoh: KI.mp3
            ];

            urutDigits.forEach(digit => {
                audioSequence.push(`${digit}.mp3`); // Contoh: 0.mp3, 0.mp3, 1.mp3
            });

            audioSequence.push('silahkan_ke_loket.mp3');
            audioSequence.push(`${loketTujuan}.mp3`); // Contoh: 2.mp3

            isSpeaking = true;
            VIDEO_ELEMENT.volume = VOLUME_DUCKING;
            for (const file of audioSequence) {
                const audio = new Audio(`${ASSET_URL}/${file}`);
                await new Promise(resolve => {
                    audio.onended = resolve;
                    audio.onerror = resolve;
                    audio.play().catch(e => {
                        console.error("Gagal memutar audio:", file, e);
                        resolve();
                    });
                });
            }

            isSpeaking = false;
            VIDEO_ELEMENT.volume = VOLUME_NORMAL;
        }


        // --- FUNGSI 2: MEMERIKSA ANTREAM PANGGILAN BARU (POLLING) ---
        function checkAndProcessCall() {
            // console.log('tes check');
            if (isSpeaking) {
                return;
            }

            $.ajax({
                url: API_DISPLAY_PROCESSOR,
                method: 'GET',
                dataType: 'json',
                data: {
                    action: 'get_new'
                },
                success: function(response) {
                    if (response.status === 'new_call') {
                        const data = response.data;

                        if (data.id === lastProcessedQueueId) return;

                        lastProcessedQueueId = data.id;

                        // 1. Update Tampilan Utama
                        // Menghapus kelas warna lama dan menambahkan kelas sukses
                        $('#currentNumber').parent().removeClass('bg-warning bg-info').addClass('bg-success');

                        // KOREKSI KRITIS: Ubah warna font angka menjadi gelap (Navy/Hitam)
                        $('#currentNumber').removeClass('text-warning text-emas').addClass('text-dark');

                        $('#currentNumber').text(data.nomor_lengkap);
                        $('#currentLoket').text(`Menuju Loket ${data.loket_pemanggil}`);
                        // 2. Putar Suara (Jalankan fungsi async)
                        playAudioSequence(data.nomor_lengkap, data.loket_pemanggil)
                            .then(() => {
                                // 3. Setelah suara selesai, tandai sebagai ANNOUNCED
                                $.post(API_DISPLAY_PROCESSOR, {
                                        _token: '{{ csrf_token() }}',
                                        action: 'mark_announced',
                                        queue_id: data.id
                                    })
                                    .fail(() => console.error("Gagal menandai pengumuman."));
                            });

                    } else {
                        // Jika tidak ada panggilan baru, kembalikan ke status IDLE
                        $('#currentNumber').text('---').parent().removeClass('bg-success').addClass(
                            'bg-warning');
                        $('#currentLoket').text('');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching new call:", error);
                }
            });
        }

        // --- FUNGSI 3: MEMPERBARUI STATUS LOKET (Bagian Bawah Layar) ---
        function updateLoketStatus() {
            $.ajax({
                url: API_LOKET_STATUS,
                method: 'GET',
                dataType: 'json',
                success: function(loketData) {
                    for (let i = 1; i <= 4; i++) {
                        $(`#loket-${i}`).text(loketData[i] || 'IDLE');
                    }
                }
            });
        }

        // Jam realtime (tetap dipertahankan dari kode Anda)
        function updateClock() {
            const now = new Date();
            const options = {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit",
            };
            document.getElementById("clock").innerText = now.toLocaleDateString("id-ID", options);
        }

        // --- INISIASI ---
        document.addEventListener("DOMContentLoaded", () => {
            updateClock();
            setInterval(updateClock, 1000);

            // Mulai Polling
            checkAndProcessCall();
            updateLoketStatus();

            // Polling utama untuk panggilan baru (5 detik)
            setInterval(checkAndProcessCall, 5000);

            // Polling untuk status loket di bawah (10 detik)
            setInterval(updateLoketStatus, 10000);
        });
        // --- INISIASI ---
        $(document).ready(function() {
            // Jam Realtime (boleh berjalan sebelum klik)
            updateClock();
            setInterval(updateClock, 1000);

            // Logika Fullscreen (tetap sama)

            // >>> KUNCI: EVENT LISTENER UNTUK TOMBOL START <<<
            $('#start-audio-btn').on('click', function() {

                // 1. Sembunyikan Overlay
                $('#audio-interaction-overlay').hide();

                // 2. Coba putar suara dummy (untuk 'membuka' izin audio di browser)
                new Audio(ASSET_URL + '/lonceng.mp3').play().catch(e => {
                    console.warn("Lonceng dummy gagal diputar, tapi izin audio sudah didapatkan.");
                });

                // 3. Mulai Polling dan Update Display
                checkAndProcessCall();
                updateServiceGrid();

                // Polling Utama
                setInterval(checkAndProcessCall, 5000);
                setInterval(updateServiceGrid, 15000);
            });
        });
    </script>
    </script>
</body>

</html>
