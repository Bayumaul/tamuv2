{{-- File: resources/views/monitor/public_monitor.blade.php (GAYA JATIM) --}}
@extends('layouts.master')

@section('title', 'Monitor Antrean Publik')

@section('content')
    <style>
        /* Styling Vuexy/Bootstrap disesuaikan */
        .service-grid-card {
            min-height: 140px;
            transition: transform 0.2s;
        }

        .service-grid-card:hover {
            transform: translateY(-3px);
        }

        .grid-number {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.2;
        }
    </style>

    <div class="row">
        <div class="col-12">
            {{-- Banner Info/Header --}}
            <div class="alert alert-warning p-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 text-dark">Selamat Datang di Layanan Kanwil Kemenkum D.I. Yogyakarta</h4>
                <span class="badge bg-dark" id="clock-display">Memuat Waktu...</span>
            </div>
        </div>
    </div>

    <div class="row match-height">

        {{-- === 1. BAGIAN UTAMA: PANGGILAN SUARA AKTIF === --}}
        <div class="col-md-6">
            <div id="main-call-card" class="card bg-info text-white shadow-lg h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <h2 class="fw-bolder text-white">NOMOR ANTRIAN YANG SEDANG DIPANGGIL</h2>
                    <h1 id="currentNumber" class="display-1 fw-bold text-warning">---</h1>
                    <h3 id="currentLoket" class="fw-bold mt-2"></h3>
                </div>
            </div>
        </div>

        {{-- === 2. BAGIAN VIDEO / INFORMASI === --}}
        <div class="col-md-6">
            <div class="card shadow h-100">
                <div class="card-body p-0">
                    <iframe width="100%" height="350"
                        src="https://www.youtube.com/embed/T-U2_ADY9qM?autoplay=1&mute=1&loop=1&playlist=T-U2_ADY9qM"
                        frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

    {{-- === 3. GRID STATUS LAYANAN (GAYA JATIM) === --}}
    <div class="row mt-3" id="serviceGrid">
        {{-- Cards Layanan akan di-render di sini oleh JavaScript --}}
    </div>

    <div class="bg-secondary text-white mt-3 py-2 px-3">
        <marquee style="font-size: 1.5rem; font-weight: bold; padding: 5px;">
            Mohon siapkan dokumen Anda sebelum dipanggil | Selamat datang di Kanwil Kemenkum DIY | Jaga ketertiban ruang
            tunggu
        </marquee>
    </div>

@endsection

@push('scripts')
    <script>
        // URL API Laravel
        const API_PROCESSOR = "{{ route('api.display.processor') }}";
        const API_LAST_ACTIVE = "{{ route('api.public.last_active_call') }}";
        const API_GRID_STATUS = "{{ route('api.public.grid_status') }}"; // API Baru
        const ASSET_URL = "{{ asset('audio') }}";

        let isSpeaking = false;
        let lastProcessedQueueId = 0;

        // --- FUNGSI UTAMA: UPDATE GRID LAYANAN ---
        function updateServiceGrid() {
            $.ajax({
                url: API_GRID_STATUS,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let htmlContent = '';
                        response.grid_data.forEach(item => {
                            // Tentukan warna/ikon berdasarkan kode layanan jika perlu
                            const cardClass = item.nomor_terakhir.endsWith('-000') ? 'bg-light' :
                                'bg-success text-white';
                            const numberColor = item.nomor_terakhir.endsWith('-000') ? 'text-dark' :
                                'text-warning';

                            htmlContent += `
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                <div class="card text-center shadow service-grid-card ${cardClass}">
                                    <div class="card-body p-2">
                                        <h5 class="mb-1 antrian-prefix">${item.nama}</h5>
                                        <p class="grid-number ${numberColor}">${item.nomor_terakhir}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        });
                        $('#serviceGrid').html(htmlContent);
                    }
                }
            });
        }

        // --- FUNGSI PANGGILAN UTAMA (checkAndProcessCall) ---
        function checkAndProcessCall() {
            // [Implementasi lengkap fungsi checkAndProcessCall() dari jawaban sebelumnya]
            // Pastikan setelah panggilan suara selesai, Anda memanggil updateServiceGrid()

            if (isSpeaking) return;

            $.ajax({
                url: API_PROCESSOR,
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

                        $('#currentNumber').text(data.nomor_lengkap);
                        $('#currentLoket').text(`SEGERA MENUJU LOKET ${data.loket_pemanggil}`);
                        $('#main-call-card').removeClass('bg-info').addClass('bg-calling');

                        // 2. Putar Suara dan Selesaikan
                        playAudioSequence(data.nomor_lengkap, data.loket_pemanggil)
                            .then(() => {
                                $.post(API_PROCESSOR, {
                                        _token: '{{ csrf_token() }}',
                                        action: 'mark_announced',
                                        queue_id: data.id
                                    })
                                    .fail(() => console.error("Gagal menandai pengumuman."));

                                $('#main-call-card').removeClass('bg-calling').addClass('bg-info');
                                updateServiceGrid(); // PENTING: Update grid setelah panggilan selesai
                            });

                    } else {
                        // Jika IDLE, tampilkan nomor terakhir yang aktif
                        updateLastActiveDisplay();
                    }
                }
            });
        }

        // ... (Fungsi updateLastActiveDisplay dan playAudioSequence dari jawaban sebelumnya) ...
        // ... (Fungsi updateClock) ...

        // --- INISIASI ---
        $(document).ready(function() {
            updateClock();
            updateServiceGrid(); // Panggil pertama kali saat load

            setInterval(updateClock, 1000);
            setInterval(checkAndProcessCall, 5000);
            setInterval(updateServiceGrid, 30000); // Update grid setiap 30 detik (agar tidak terlalu sering query)
        });
    </script>
@endpush
