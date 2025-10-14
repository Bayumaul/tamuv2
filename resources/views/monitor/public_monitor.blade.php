@extends('layouts.master') 

@section('title', 'Status Antrean Publik Per Layanan')

@section('content')
<style>
    /* 1. WARNA & TEMA */
    .bg-navy { background-color: #002147 !important; color: white; }
    .text-navy { color: #002147 !important; }
    .bg-emas { background-color: #FFC107 !important; color: #002147; }
    .text-emas { color: #FFC107 !important; }

    /* 2. CARD MODERN & KETERBACAAN */
    .service-grid-card {
        min-height: 150px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }
    .service-grid-card.active-call {
        border-color: #28a545; /* Border Hijau saat aktif */
        box-shadow: 0 4px 10px rgba(40, 165, 69, 0.2);
    }
    .card-header-custom {
        background-color: #f7f7f7;
        padding: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        border-bottom: 1px solid #eee;
    }
    .grid-number {
        font-size: 2.8rem; /* Lebih besar dan berani */
        font-weight: 900;
        line-height: 1.2;
        margin-top: 5px;
    }
    .icon-overlay {
        position: absolute;
        top: 10px;
        right: 10px;
        opacity: 0.1;
        font-size: 50px;
    }
    .text-success-custom { color: #28a545 !important; }

    @media (max-width: 768px) {
        .grid-number { font-size: 2rem; }
    }
</style>

<div class="container py-4">

    {{-- HEADER KEMENKUM DIY (FIXED BAR) --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-navy text-white shadow-lg rounded-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-white fw-bold">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height: 30px;" class="me-2">
                        STATUS ANTRIAN KANWIL KEMENKUM DIY
                    </h4>
                    <div class="text-end">
                        <span class="d-block badge bg-emas text-navy fw-bold" id="clock-display">Memuat Waktu...</span>
                        <small class="text-white-50">Waktu Lokal</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- BAR INFORMASI DAN UPDATE TERAKHIR --}}
    <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded shadow-sm border-start border-3 border-warning">
        <div class="text-dark fw-bold">
            <i class="menu-icon icon-base ti tabler-info-circle me-2 text-warning"></i> Nomor Antrean Terakhir Diproses:
        </div>
        <small class="last-update-time text-muted">
            Update: <strong id="last-updated-display">--:--:--</strong>
        </small>
    </div>

    {{-- === GRID STATUS LAYANAN === --}}
    <div class="row g-4" id="serviceGrid">
        {{-- Placeholder Loading --}}
        <div class="col-12 text-center py-5" id="loading-placeholder">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Memuat data status layanan...</p>
        </div>
        {{-- Data Cards akan di-render di sini oleh JavaScript --}}
    </div>

</div>

@endsection

@push('scripts')
<script src="{{ asset('sash/js/jquery.min.js') }}"></script> 
<script>
    const API_GRID_STATUS = "{{ route('api.public.grid_status') }}";

    // Fungsi untuk memetakan icon (Tabler Icons)
    function getServiceIcon(kode) {
        switch (kode) {
            case 'KI': return 'tool'; 
            case 'AHU': return 'building-bank';
            case 'FPHD': return 'file-invoice';
            case 'JDIH': return 'book';
            case 'HKM': return 'users';
            case 'ADM': return 'clipboard-list';
            default: return 'help';
        }
    }

    // --- FUNGSI UTAMA: UPDATE GRID LAYANAN ---
    function updateServiceGrid() {
        $.ajax({
            url: API_GRID_STATUS,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#loading-placeholder').hide();
                if (response.status !== 'success') return;
                
                let htmlContent = '';
                response.grid_data.forEach(item => {
                    const isIdle = item.nomor_terakhir.endsWith('-000');
                    // Tentukan kelas CSS
                    const cardClass = isIdle ? 'bg-white' : 'bg-light active-call'; 
                    const numberColorClass = isIdle ? 'text-warning' : 'text-success-custom'; // Emas saat idle, Hijau saat aktif
                    const iconColorClass = isIdle ? 'text-navy' : 'text-success-custom'; 
                    
                    htmlContent += `
                        <div class="col-6 col-md-4 col-lg-3 mb-2">
                            <div class="card text-center shadow-sm service-grid-card ${cardClass}">
                                <div class="card-header-custom text-uppercase text-navy text-truncate">
                                    ${item.nama}
                                </div>
                                <div class="card-body p-3">
                                    
                                    {{-- Icon Overlay --}}
                                    <i class="menu-icon icon-base ti tabler-${getServiceIcon(item.kode)} icon-overlay text-emas"></i>
                                    
                                    {{-- Nomor Antrean --}}
                                    <p class="grid-number ${numberColorClass}">${item.nomor_terakhir}</p>
                                    
                                    <span class="badge ${isIdle ? 'bg-light text-navy' : 'bg-success text-white'}">
                                        <i class="menu-icon icon-base ti tabler-${isIdle ? 'clock' : 'phone-call'} me-1"></i> 
                                        ${isIdle ? 'BELUM ADA PANGGILAN' : 'SEDANG DIPROSES'}
                                    </span>
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#serviceGrid').html(htmlContent);

                // Kunci: Update timestamp setelah data berhasil dimuat
                $('#last-updated-display').text(new Date().toLocaleTimeString('id-ID', { hour12: false }));
            }
        });
    }

    // --- FUNGSI JAM REALTIME ---
    function updateClock() {
        const now = new Date();
        const options = { weekday: "long", year: "numeric", month: "long", day: "numeric", hour: "2-digit", minute: "2-digit", second: "2-digit",};
        document.getElementById("clock-display").textContent = now.toLocaleDateString("id-ID", options);
    }
    
    // --- INISIASI ---
    $(document).ready(function() {
        updateClock();
        updateServiceGrid();
        
        setInterval(updateClock, 1000);
        // Polling setiap 15 detik untuk update status
        setInterval(updateServiceGrid, 15000); 
    });
</script>
@endpush