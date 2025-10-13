@extends('layouts.app')

@section('title', 'Statistik Layanan AHU & KI')

@section('content')
    <div class="flex-grow-1 container-p-y container-fluid">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Statistik /</span> Layanan AHU & KI
        </h4>

        <div class="row g-6 mb-6">
            {{-- Card Sambutan (Opsional, dari template Vuexy Anda) --}}
            <div class="col-xl-4">
                {{-- Isi dengan card sambutan atau ringkasan total antrean Tahun ini --}}
                <div class="card bg-primary text-white">... Ringkasan KI & AHU Tahun {{ date('Y') }} ...</div>
            </div>

            {{-- BLOK 1: TREN BULANAN (AHU vs KI) --}}
            <div class="col-xl-8 col-md-12">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title mb-0">Tren Kunjungan Bulanan ({{ date('Y') }})</h5>
                        <small class="text-body-secondary">Perbandingan Layanan AHU dan KI</small>
                    </div>
                    <div class="card-body">
                        <div id="monthlyTrendChart" style="min-height: 350px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-6">

            {{-- BLOK 2: DISTRIBUSI STATUS HARI INI --}}
            <div class="col-xl-4 col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Status Antrean Hari Ini</h5>
                    </div>
                    <div class="card-body">
                        <div id="dailyStatusChart" style="min-height: 250px;"></div>
                        <p class="text-center mt-3 mb-0">Total antrean KI & AHU hari ini: <strong
                                id="total-antrean-harian">--</strong></p>
                    </div>
                </div>
            </div>

            {{-- BLOK 3: PERBANDINGAN TIPE LAYANAN (ONLINE vs OFFLINE) --}}
            <div class="col-xl-4 col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tipe Pendaftaran (Tahunan)</h5>
                    </div>
                    <div class="card-body">
                        <div id="typeComparisonChart" style="min-height: 250px;"></div>
                    </div>
                </div>
            </div>

            {{-- BLOK 4: METRIK LAIN (Placeholder) --}}
            <div class="col-xl-4 col-md-12">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Rata-rata Waktu Layanan (KI vs AHU)</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Data ini akan dihitung berdasarkan perbedaan waktu panggil dan waktu selesai.
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        const API_DATA = "{{ route('api.stats.layanan') }}";

        // --- Chart Instance ---
        let monthlyTrendChart, dailyStatusChart, typeComparisonChart;

        // --- Pemetaan Nama Bulan ---
        const monthNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];

        // --- FUNGSI UTAMA: Ambil dan Render Semua Data ---
        function fetchAndRenderCharts(year, month) {
            $.ajax({
                url: API_DATA,
                method: 'GET',
                data: {
                    year: year,
                    month: month
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status !== 'success') return;

                    const stats = response.data;

                    // 1. Tren Bulanan (AHU vs KI)
                    renderMonthlyTrend(stats.monthly_trend);

                    // 2. Distribusi Status Harian
                    renderDailyStatus(stats.daily_status);

                    // 3. Perbandingan Tipe Layanan (Online vs Offline)
                    renderTypeComparison(stats.type_comparison);
                }
            });
        }

        // --- RENDER 1: Tren Bulanan (Bar Chart) ---
        function renderMonthlyTrend(data) {
            const labels = [];
            const ahuSeries = [];
            const kiSeries = [];

            data.forEach(item => {
                labels.push(monthNames[item.month - 1]);
                ahuSeries.push(item.ahu_count);
                kiSeries.push(item.ki_count);
            });

            const options = {
                series: [{
                    name: 'AHU',
                    data: ahuSeries
                }, {
                    name: 'KI',
                    data: kiSeries
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '50%',
                        borderRadius: 5
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: labels
                },
                yaxis: {
                    title: {
                        text: 'Jumlah Antrean'
                    }
                }
            };

            if (!monthlyTrendChart) {
                monthlyTrendChart = new ApexCharts(document.querySelector("#monthlyTrendChart"), options);
                monthlyTrendChart.render();
            } else {
                monthlyTrendChart.updateOptions(options);
            }
        }

        // --- RENDER 2: Status Harian (Donut Chart) ---
        function renderDailyStatus(data) {
            const totalCount = data.reduce((sum, item) => sum + item.count, 0);

            const series = data.map(item => item.count);
            const labels = data.map(item => `${item.status_antrean} (${item.count})`);

            $('#total-antrean-harian').text(totalCount);

            const options = {
                series: series,
                chart: {
                    type: 'donut',
                    height: 250
                },
                labels: labels,
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            if (!dailyStatusChart) {
                dailyStatusChart = new ApexCharts(document.querySelector("#dailyStatusChart"), options);
                dailyStatusChart.render();
            } else {
                dailyStatusChart.updateOptions({
                    series: series,
                    labels: labels
                });
            }
        }
        const ID_AHU = 2; // Ambil dari Controller
        const ID_KI = 1;
        // --- RENDER 3: Perbandingan Tipe Layanan (Baris Bertumpuk) ---
        function renderTypeComparison(data) {
            // Logika kompleks untuk memisahkan data KI dan AHU untuk Baris Bertumpuk (Stacked Bar)
            const ahuOnline = data.find(i => i.id_layanan === ID_AHU && i.tipe_layanan === 'Online')?.count ?? 0;
            const ahuOffline = data.find(i => i.id_layanan === ID_AHU && i.tipe_layanan === 'Offline')?.count ?? 0;
            const kiOnline = data.find(i => i.id_layanan === ID_KI && i.tipe_layanan === 'Online')?.count ?? 0;
            const kiOffline = data.find(i => i.id_layanan === ID_KI && i.tipe_layanan === 'Offline')?.count ?? 0;

            const options = {
                series: [{
                    name: 'Online',
                    data: [ahuOnline, kiOnline]
                }, {
                    name: 'Offline',
                    data: [ahuOffline, kiOffline]
                }],
                chart: {
                    type: 'bar',
                    height: 250,
                    stacked: true,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 5
                    }
                },
                xaxis: {
                    categories: ['AHU', 'KI']
                },
                yaxis: {
                    title: {
                        text: 'Jumlah Antrean'
                    }
                }
            };

            if (!typeComparisonChart) {
                typeComparisonChart = new ApexCharts(document.querySelector("#typeComparisonChart"), options);
                typeComparisonChart.render();
            } else {
                typeComparisonChart.updateOptions(options);
            }
        }


        // --- INISIASI ---
        $(document).ready(function() {
            const currentYear = new Date().getFullYear();
            fetchAndRenderCharts(currentYear);

            // Polling untuk update status harian dan grafik (setiap 5 menit)
            setInterval(() => fetchAndRenderCharts(currentYear), 300000);
        });
    </script>
@endpush
