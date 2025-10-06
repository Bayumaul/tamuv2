<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Buku Tamu Layanan Kantor Wilayah Kementerian Hukum Daerah Istimewa Yogyakarta" />
    <meta name="author" content="Kanwil Kemenkum DIY" />
    <meta name="keywords" content="kanwil kemenkum diy, kemenkum, kemenkum diy, kantor wilayah, kementerian hukum" />

    <meta property="og:type" content="website" />
    <meta property="og:title" content="Buku Tamu Elektronik - Kanwil Kemenkum DIY" />
    <meta property="og:description"
        content="Sistem Buku Tamu dan Antrian Elektronik Kantor Wilayah Kementerian Hukum Daerah Istimewa Yogyakarta" />
    <meta property="og:image" content="https://kemenkumjogja.id/img.png" />
    <meta property="og:url" content="https://kemenkumjogja.id" />
    <meta property="og:site_name" content="KemenkumJogja.ID" />

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('templates/sash/') }}/images/logo.png" />
    <title>Buku Tamu Layanan Kanwil Kementrian Hukum DIY</title>

    <link id="style" href="{{ asset('templates/sash/') }}/plugins/bootstrap/css/bootstrap.min.css"
        rel="stylesheet" />

    <link href="{{ asset('templates/sash/') }}/css/style.css" rel="stylesheet" />

    <link href="{{ asset('templates/sash/') }}/css/plugins.css" rel="stylesheet" />

    <link href="{{ asset('templates/sash/') }}/css/icons.css" rel="stylesheet" />

    <link href="{{ asset('templates/sash/') }}/switcher/css/switcher.css" rel="stylesheet" />
    <link href="{{ asset('templates/sash/') }}/switcher/demo.css" rel="stylesheet" />
</head>

<body class="app ltr landing-page horizontal">
    {{-- <div id="global-loader">
        <img src="{{ asset('templates/sash/images/loader.svg') }}" class="loader-img" alt="Loader" />
    </div> --}}
    <div class="page" style="background-color: #1e1e54">
        <div class="container-fluid p-3 header">
            <div class="row align-items-center">
                <div class="col-md-6 d-flex align-items-center">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo Kemenkum"
                        style="height: 60px; margin-right: 15px" />
                    <span class="logo-text fw-bold fs-5 text-dark">
                        Kantor Wilayah Kementerian Hukum <br />D.I. Yogyakarta
                    </span>
                </div>

                <div class="col-md-6 text-end d-flex justify-content-end align-items-center text-dark">
                    <div class="dropdown d-flex me-3">
                        <a class="nav-link icon full-screen-link nav-link-bg">
                            <i class="fe fe-minimize fullscreen-button"></i>
                        </a>
                    </div>
                    <div>
                        <p id="clock" class="fw-semibold fs-5 mt-2"></p>
                        <small>Jadwal Buka Layanan: Senin s.d Kamis - 08.00 s.d 15.00, Jum'at:
                            Pelayanan Online
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <section class="py-4 text-white">
            <div class="container-fluid">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card bg-warning text-dark text-center shadow-lg h-100">
                            <div class="card-body d-flex flex-column justify-content-center">
                                <h3 class="fw-bold">NOMOR ANTRIAN YANG DIPANGGIL</h3>
                                <h1 id="currentNumber" class="display-1 fw-bold">---</h1>
                                <h4 id="currentLoket" class="fw-bold mt-3"></h4> {{-- Menampilkan Loket Tujuan --}}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card bg-dark shadow-lg h-100">
                            <div class="card-body p-0">
                                <iframe width="100%" height="315"
                                    src="https://www.youtube.com/embed/T-U2_ADY9qM?autoplay=1&mute=1&loop=1&playlist=T-U2_ADY9qM"
                                    title="YouTube video player" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status Loket 1-4 (Diisi dari API) --}}
                <div class="row g-2 mt-3 text-center" id="loketStatus">
                    @for ($i = 1; $i <= 4; $i++)
                        <div class="col">
                            <div class="card bg-info shadow-sm">
                                <div class="card-body">
                                    <h5 class="fw-bold">LOKET {{ $i }}</h5>
                                    <p class="fs-3 mb-0" id="loket-{{ $i }}">IDLE</p>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="bg-secondary text-white mt-3 py-2 px-3">
                    <marquee behavior="scroll" direction="left"
                        style="font-size: 28px; font-weight: bold; padding: 10px;">
                        Selamat datang di layanan antrian Kanwil Kemenkum DIY | Mohon jaga
                        ketertiban ruang tunggu | Mohon antri dengan tertib
                    </marquee>
                </div>
            </div>
        </section>

    </div>

    {{-- Scripts --}}
    <script src="{{ asset('templates/sash/js/jquery.min.js') }}"></script>
    <script>
        // URL API Laravel dari Route Name
        const API_DISPLAY_PROCESSOR = "{{ route('api.display.processor') }}";
        const API_LOKET_STATUS = "{{ route('api.public.loket_status') }}";
        const ASSET_URL = "{{ asset('audio') }}";
        const API_LAST_ACTIVE = "{{ route('api.public.last_active_call') }}";

        let isSpeaking = false;
        let lastProcessedQueueId = 0;


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
                audioSequence.push(`${digit}.wav`); // Contoh: 0.mp3, 0.mp3, 1.mp3
            });

            audioSequence.push('silahkan_ke_loket.mp3');
            audioSequence.push(`${loketTujuan}.wav`); // Contoh: 2.mp3

            isSpeaking = true;

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
        }


        // --- FUNGSI 2: MEMERIKSA ANTREAM PANGGILAN BARU (POLLING) ---
        function checkAndProcessCall() {
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
                        $('#currentNumber').text(data.nomor_lengkap).parent().removeClass('bg-warning')
                            .addClass('bg-success');
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
    </script>
    {{-- ... (Sisa script Anda) ... --}}
</body>

</html>
