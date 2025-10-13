@extends('layouts.app') 

@section('title', 'Dashboard')

@section('content')
<div class="flex-grow-1 container-p-y container-fluid">
    <div class="row g-6">
        
        {{-- CARD UTAMA: SAMBUTAN & FOKUS HARI INI --}}
        <div class="col-xl-4">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-7">
                        <div class="card-body text-nowrap">
                            {{-- Ganti John dengan nama Admin atau fokus pada hari ini --}}
                            <h5 class="card-title mb-0">Halo, {{ Auth::user()->name ?? 'Admin' }}! 🎉</h5> 
                            <p class="mb-2">Fokus Kinerja Hari Ini</p>
                            {{-- Tampilkan Antrean Menunggu --}}
                            <h4 class="text-primary mb-1" id="total-waiting-count">--</h4>
                            <small>Antrean Menunggu</small>
                            {{-- <a href="{{ route('admin.queue.report') }}" class="btn btn-primary waves-effect waves-light mt-3">Lihat Laporan Harian</a> --}}
                        </div>
                    </div>
                    <div class="col-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            {{-- Ganti ilustrasi --}}
                            <img src="{{ asset('templates/vuexy/assets/img/illustrations/card-advance-sale.png') }}" height="140" alt="Antrean Icon">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- BLOK STATISTIK UTAMA --}}
        <div class="col-xl-8 col-md-12">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Statistik Antrean Hari Ini</h5>
                    <small class="text-body-secondary" id="last-update">Memuat...</small>
                </div>
                <div class="card-body d-flex align-items-end">
                    <div class="w-100">
                        <div class="row gy-3">
                            {{-- STAT 1: TOTAL KUNJUNGAN HARI INI --}}
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-primary me-4 p-2">
                                        <i class="icon-base ti tabler-users icon-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0" id="stat-total-harian">--</h5>
                                        <small>Total Kunjungan</small>
                                    </div>
                                </div>
                            </div>
                            {{-- STAT 2: ANTRIAN AKTIF DIPANGGIL --}}
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-info me-4 p-2">
                                        <i class="icon-base ti tabler-phone-call icon-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0" id="stat-aktif-dipanggil">--</h5>
                                        <small>Sedang Dilayani</small>
                                    </div>
                                </div>
                            </div>
                            {{-- STAT 3: TINGKAT KEHADIRAN --}}
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-success me-4 p-2">
                                        <i class="icon-base ti tabler-user-check icon-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0" id="stat-tingkat-hadir">--%</h5>
                                        <small>Tingkat Kehadiran</small>
                                    </div>
                                </div>
                            </div>
                            {{-- STAT 4: RATA-RATA WAKTU LAYANAN --}}
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-warning me-4 p-2">
                                        <i class="icon-base ti tabler-clock icon-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0" id="stat-avg-time">-- Min</h5>
                                        <small>Rata-rata Layanan</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- GRAFIK 1: TRENS KUNJUNGAN MINGGUAN (Mengganti Revenue Report) --}}
        <div class="col-xxl-8">
            <div class="card h-100">
                <div class="card-body p-0">
                    <div class="row row-bordered g-0">
                        <div class="col-md-12 position-relative p-6">
                            <h5 class="m-0 card-title">Tren Kunjungan Mingguan (Berdasarkan Pendaftaran)</h5>
                            <div id="totalRevenueChart" class="mt-3" style="min-height: 350px;">
                                {{-- Area Chart akan diinisiasi di sini --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- GRAFIK 2: DISTRIBUSI ANTRIAN PER LOKET (Mengganti Earning Reports) --}}
        <div class="col-xxl-4 col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="mb-1">Distribusi Antrean Per Loket</h5>
                        <p class="card-subtitle">Persentase total antrean hari ini</p>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <div id="reportPieChart" style="min-height: 250px;">
                        {{-- Radial/Pie Chart Distribusi Loket akan diinisiasi di sini --}}
                    </div>
                    <ul class="p-0 m-0 list-group list-group-flush mt-3" id="loket-distribution-list">
                        {{-- List Loket dan persentase akan di-render di sini --}}
                    </ul>
                </div>
            </div>
        </div>

        {{-- GRAFIK 3: LAYANAN PALING DIMINATI (Mengganti Popular Product) --}}
        <div class="col-xxl-4 col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title m-0 me-2">
                        <h5 class="mb-1">Top 5 Layanan Paling Diminati</h5>
                        <p class="card-subtitle" id="top-service-date">Bulan Ini</p>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0" id="top-services-list">
                        {{-- Daftar Layanan Detail akan di-render di sini --}}
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
    // URL API Laravel (Pastikan Anda sudah mendefinisikan routes ini di web.php)
    const API_STATS_HARIAN = "{{ route('stats.harian') }}"; 
    const API_TREND_MINGGUAN = "{{ route('stats.weekly_trend') }}";
    const API_DISTRIBUSI_LOKET = "{{ route('stats.loket_dist') }}";
    const API_TOP_SERVICES = "{{ route('stats.top_services') }}";

    // Variabel untuk menyimpan objek chart agar bisa di-update
    let revenueChart, pieChart; 

    // --- 1. FUNGSI UTAMA: UPDATE SEMUA STATISTIK KARTU ---
    function updateStatsCards() {
        $.ajax({
            url: API_STATS_HARIAN,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status !== 'success') return;
                
                // Update Statistik Utama
                $('#stat-total-harian').text(response.data.total_kunjungan);
                $('#stat-aktif-dipanggil').text(response.data.antrean_aktif);
                $('#stat-tingkat-hadir').text(response.data.tingkat_kehadiran + '%');
                $('#stat-avg-time').text(response.data.avg_service_time + ' Min');
                $('#total-waiting-count').text(response.data.antrean_menunggu);
                
                // Update waktu terakhir update
                $('#last-update').text('Terakhir diperbarui: ' + moment().format('HH:mm:ss'));
            }
        });
    }

    // --- 2. FUNGSI PEMBUAT GRAFIK TRENS MINGGUAN (Line/Bar Chart) ---
    function initWeeklyTrendChart() {
        $.ajax({
            url: API_TREND_MINGGUAN,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const options = {
                    series: [{
                        name: "Total Pendaftaran",
                        data: response.data.counts // Jumlah pendaftaran per hari
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: { show: false }
                    },
                    plotOptions: {
                        bar: { horizontal: false, columnWidth: '55%', borderRadius: 5 }
                    },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 2 },
                    xaxis: {
                        categories: response.data.labels, // Nama hari (Sen, Sel, Rab,...)
                    },
                    yaxis: { title: { text: 'Jumlah Antrean' } },
                    fill: { opacity: 1 },
                    tooltip: { y: { formatter: function (val) { return val + " Antrean" } } }
                };
                
                // Inisiasi grafik
                if (revenueChart) {
                    revenueChart.updateOptions(options);
                } else {
                    revenueChart = new ApexCharts(document.querySelector("#totalRevenueChart"), options);
                    revenueChart.render();
                }
            }
        });
    }

    // --- 3. FUNGSI PEMBUAT GRAFIK DISTRIBUSI LOKET (Donut Chart) ---
    function initLoketDistributionChart() {
        $.ajax({
            url: API_DISTRIBUSI_LOKET,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const options = {
                    series: response.data.percentages, // Persentase [30, 25, 45]
                    chart: {
                        type: 'donut',
                        height: 250,
                    },
                    labels: response.data.labels, // Nama Loket [Loket 1, Loket 2, Loket 3]
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: { width: 200 },
                            legend: { position: 'bottom' }
                        }
                    }],
                    legend: { show: false }, // List akan dibuat manual di View
                };

                if (pieChart) {
                    pieChart.updateOptions(options);
                } else {
                    pieChart = new ApexCharts(document.querySelector("#reportPieChart"), options);
                    pieChart.render();
                }
                
                // Update List Manual di bawah Pie Chart
                let listHtml = '';
                response.data.labels.forEach((label, index) => {
                    listHtml += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-medium">${label}</span>
                            <span class="badge bg-primary rounded-pill">${response.data.percentages[index]}%</span>
                        </li>`;
                });
                $('#loket-distribution-list').html(listHtml);
            }
        });
    }

    // --- 4. FUNGSI PEMBUAT DAFTAR TOP LAYANAN ---
    function initTopServicesList() {
        $.ajax({
            url: API_TOP_SERVICES,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let listHtml = '';
                response.data.services.forEach((service, index) => {
                    const iconClass = (index < 2) ? 'star text-warning' : 'file-text';
                    
                    listHtml += `
                        <li class="d-flex align-items-center mb-5">
                            <div class="badge bg-label-secondary text-body p-2 me-4 rounded">
                              <i class="icon-base ti tabler-star icon-md"></i>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">${service.nama_layanan_detail}</h6>
                                    <small class="text-body">${service.total} Kunjungan</small>
                                </div>
                            </div>
                        </li>`;
                });
                $('#top-services-list').html(listHtml);
                $('#top-service-date').text(`Bulan ${response.data.month}`);
            }
        });
    }


    // --- INISIASI DAN POLLING ---
    $(document).ready(function() {
        // Inisiasi awal grafik
        initWeeklyTrendChart();
        initLoketDistributionChart();
        initTopServicesList();
        updateStatsCards(); // Panggil pertama kali

        // Polling untuk metrik yang sering berubah (setiap 30 detik)
        setInterval(updateStatsCards, 30000); 
        
        // Polling untuk grafik (setiap 5 menit atau refresh halaman)
        setInterval(initLoketDistributionChart, 300000); 
        setInterval(initWeeklyTrendChart, 300000); 
    });
</script>
@endpush