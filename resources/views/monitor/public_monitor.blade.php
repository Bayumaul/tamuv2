{{-- File: resources/views/monitor/public_monitor.blade.php (RESPONSIVE) --}}
@extends('layouts.master') 

@section('title', 'Monitor Antrean Publik')

@section('content')
<style>
    /* Tambahan Styling Responsif */
    .service-grid-card {
        min-height: 120px;
        transition: transform 0.2s;
    }
    .grid-number {
        font-size: 2.2rem; /* Lebih kecil agar muat di ponsel */
        font-weight: 800;
        line-height: 1.2;
    }
    @media (max-width: 768px) {
        .grid-number {
            font-size: 1.8rem;
        }
        .main-call-display {
             font-size: 3rem !important;
        }
    }
</style>

<div class="row">
    <div class="col-12">
        {{-- Banner Info/Header --}}
        <div class="alert alert-warning p-3 d-flex flex-wrap justify-content-between align-items-center">
            <h5 class="mb-0 text-dark">Monitor Status Antrean Kanwil Kemenkum DIY</h5>
            <span class="badge bg-dark mt-2 mt-sm-0" id="clock-display">Memuat Waktu...</span>
        </div>
    </div>
</div>

{{-- === 1. BAGIAN UTAMA: PANGGILAN SUARA AKTIF (Paling Atas di Ponsel) === --}}
<div class="row match-height">
    <div class="col-12 mb-3">
        <div id="main-call-card" class="card bg-info text-white shadow-lg h-100">
            <div class="card-body text-center p-4">
                <h4 class="fw-bolder text-white">NOMOR ANTRIAN YANG DIPANGGIL</h4>
                <h1 id="currentNumber" class="main-call-display fw-bold text-warning">---</h1>
                <h4 id="currentLoket" class="fw-bold mt-1"></h4>
            </div>
        </div>
    </div>
</div>

{{-- === 2. GRID STATUS LAYANAN (RESPONSIVE) === --}}
<h4 class="px-3 mb-3 text-secondary">Status Terakhir Per Layanan:</h4>
<div class="row" id="serviceGrid">
    {{-- Cards Layanan akan di-render di sini oleh JavaScript --}}
</div>

<div class="bg-secondary text-white mt-3 py-2 px-3">
    <marquee style="font-size: 1.2rem; font-weight: bold; padding: 5px;">
        Mohon siapkan dokumen Anda sebelum dipanggil | Selamat datang di Kanwil Kemenkum DIY | Jaga ketertiban ruang tunggu
    </marquee>
</div>

@endsection

@push('scripts')
<script>
    // URL API Laravel
    const API_PROCESSOR = "{{ route('api.display.processor') }}";
    const API_LAST_ACTIVE = "{{ route('api.public.last_active_call') }}";
    const API_GRID_STATUS = "{{ route('api.public.last_active_call') }}";

    let isSpeaking = false;
    let lastProcessedQueueId = 0;

    // --- FUNGSI UTAMA: UPDATE GRID LAYANAN (Responsive Rendering) ---
    function updateServiceGrid() {
        $.ajax({
            url: API_GRID_STATUS,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    let htmlContent = '';
                    response.grid_data.forEach(item => {
                        // Tentukan kelas CSS untuk warna kartu
                        const isIdle = item.nomor_terakhir.endsWith('-000');
                        const cardClass = isIdle ? 'bg-light' : 'bg-success text-white';
                        const numberColor = isIdle ? 'text-dark' : 'text-warning';
                        
                        // Gunakan col-12 di ponsel, col-md-4 di tablet, col-lg-3 di desktop
                        htmlContent += `
                            <div class="col-6 col-md-4 col-lg-3 mb-3">
                                <div class="card text-center shadow-sm service-grid-card ${cardClass}">
                                    <div class="card-body p-2">
                                        <h6 class="mb-1 antrian-prefix fw-bold">${item.kode}</h6>
                                        <small class="d-block mb-1 text-truncate">${item.nama}</small>
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
        if (isSpeaking) return;

        $.ajax({
            url: API_PROCESSOR, 
            method: 'GET',
            dataType: 'json',
            data: { action: 'get_new' },
            success: function(response) {
                if (response.status === 'new_call') {
                    // LOGIKA PANGGILAN AKTIF
                    const data = response.data;
                    if (data.id === lastProcessedQueueId) return; 

                    lastProcessedQueueId = data.id; 

                    $('#main-call-card').removeClass('bg-info').addClass('bg-success');
                    $('#currentNumber').text(data.nomor_lengkap);
                    $('#currentLoket').text(`SEGERA MENUJU LOKET ${data.loket_pemanggil}`);

                    // [Lanjutkan dengan playAudioSequence(data.nomor_lengkap, data.loket_pemanggil) di sini]

                    // Setelah simulasi suara:
                    new Promise(resolve => setTimeout(resolve, 5000)).then(() => {
                        $.post(API_PROCESSOR, { _token: '{{ csrf_token() }}', action: 'mark_announced', queue_id: data.id });
                        $('#main-call-card').removeClass('bg-success').addClass('bg-info');
                        updateServiceGrid(); 
                    });

                } else {
                    // Jika IDLE, tampilkan nomor terakhir yang aktif
                    updateLastActiveDisplay();
                }
            }
        });
    }
    
    // --- FUNGSI UPDATE NOMOR SAAT IDLE ---
    function updateLastActiveDisplay() {
         $.ajax({
            url: API_LAST_ACTIVE, 
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (!isSpeaking) {
                    if (response.status === 'success') {
                        $('#currentNumber').text(response.nomor);
                        $('#currentLoket').text(`Terakhir Dipanggil: Loket ${response.loket}`);
                        $('#main-call-card').removeClass('bg-success').addClass('bg-info');
                    } else {
                        $('#currentNumber').text('---');
                        $('#currentLoket').text('Belum ada panggilan hari ini');
                        $('#main-call-card').removeClass('bg-success').addClass('bg-warning');
                    }
                }
            }
        });
    }

    // --- FUNGSI JAM REALTIME ---
    function updateClock() {
        const now = new Date();
        const options = { year: "numeric", month: "long", day: "numeric", hour: "2-digit", minute: "2-digit", second: "2-digit",};
        document.getElementById("clock-display").textContent = now.toLocaleDateString("id-ID", options);
    }
    
    // --- INISIASI ---
    $(document).ready(function() {
        updateClock();
        updateServiceGrid();
        
        setInterval(updateClock, 1000);
        setInterval(checkAndProcessCall, 5000); 
        setInterval(updateServiceGrid, 15000); 
    });
</script>
@endpush