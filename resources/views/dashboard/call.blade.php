{{-- File: resources/views/dashboard/petugas_dashboard.blade.php (FINAL CODE) --}}

@extends('layouts.app')
{{-- Pastikan layouts.app Anda meng-include aset Vuexy/Bootstrap --}}

@section('title', 'Dashboard Petugas - ' . $namaLoket)

@section('content')
    <style>
        /* Styling Tambahan untuk Visual Menarik */
        .card-control {
            border-top: 5px solid #007bff;
            /* Garis biru khas Vuexy/Bootstrap Primary */
        }

        .current-antrean {
            padding: 30px 0;
            min-height: 250px;
            border-radius: 8px;
            transition: background-color 0.3s;
            border: 2px dashed #007bff;
        }

        .calling-active {
            background-color: #d4edda;
            /* Hijau muda/sukses */
        }

        .current-number {
            font-size: 5rem;
            font-weight: 900;
            color: #28a545;
            /* Hijau Sukses */
        }

        .current-number.idle {
            color: #ffc107;
            /* Kuning Peringatan */
        }

        .btn-lg-action {
            padding: 10px 15px;
            font-size: 1.1rem;
        }
    </style>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-primary">{{ $namaLoket }}</h1>
            <p class="text-end">
                Masuk sebagai: <strong>{{ Auth::user()->username ?? 'Petugas' }}</strong> |
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            </p>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
        <hr>

        {{-- Input Tersembunyi --}}
        <input type="hidden" id="loket_id" value="{{ $loketId }}">
        <input type="hidden" id="id_buku_saat_ini" value="0">

        <div class="row">
            {{-- 1. KONTROL PANGGILAN (Kolom Kiri) --}}
            <div class="col-md-6">
                <div class="card shadow ">
                    <div class="card-header bg-primary text-white">
                        Pusat Kontrol Layanan
                    </div>
                    <div class="card-body text-center">
                        <div class="current-antrean" id="current_antrean_box">
                            <h5 class="text-muted">Antrean Aktif Saat Ini</h5>
                            <h1 class="current-number idle" id="current_call_display">---</h1>
                        </div>

                        <div class="mt-4">
                            {{-- Tombol Panggil Berikutnya --}}
                            <button id="btn_call_next" class="btn btn-success btn-lg mb-3 w-100 fw-bold"
                                data-original-html='<i class="fe fe-phone-call"></i> Panggil Antrean Berikutnya'>
                                <i class="fe fe-phone-call"></i> Panggil Antrean Berikutnya
                            </button>

                            <div class="d-flex justify-content-center mb-3">
                                {{-- Tombol Panggil Ulang --}}
                                <button id="btn_reissue" class="btn btn-warning btn-md-action me-2 w-50" disabled
                                    data-original-html='<i class="fe fe-refresh-cw"></i> Panggil Ulang'>
                                    <i class="fe fe-refresh-cw"></i> Panggil Ulang
                                </button>
                                {{-- Tombol Lewati (Skip) --}}
                                <button id="btn_skip" class="btn btn-secondary btn-md-action w-50" disabled
                                    data-original-html='<i class="fe fe-skip-forward"></i> Lewati Antrean'>
                                    <i class="fe fe-skip-forward"></i> Lewati Antrean
                                </button>
                            </div>

                            {{-- Tombol Selesaikan --}}
                            <button id="btn_complete" class="btn btn-danger btn-lg w-100 fw-bold" disabled
                                data-original-html='<i class="fe fe-check-circle"></i> Selesaikan Layanan'>
                                <i class="fe fe-check-circle"></i> Selesaikan Layanan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. DAFTAR TUNGGU (Kolom Kanan) --}}
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        Daftar Antrean Menunggu
                    </div>
                    <ul class="list-group list-group-flush" id="waiting_list_display" style="min-height: 480px;">
                        <li class="list-group-item text-center text-muted">Memuat daftar antrean...</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Memastikan JQuery termuat --}}
    <script src="{{ asset('templates/sash/js/jquery.min.js') }}"></script>

    <script>
        // URL API Laravel
        const API_STATUS = "{{ route('api.dashboard.status') }}";
        const API_CALL_NEXT = "{{ route('api.dashboard.call_next') }}";
        const LOKET_ID = $('#loket_id').val();

        // --- 1. FUNGSI PEMBARUAN DASHBOARD (AJAX Polling) ---
        // --- 1. FUNGSI PEMBARUAN DASHBOARD (AJAX Polling) ---
        function updateDashboard() {
            $.ajax({
                url: API_STATUS,
                method: 'GET',
                data: {
                    loket_id: LOKET_ID
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status !== 'success') {
                        return;
                    }

                    let currentNumber = response.current ? response.current.nomor_lengkap : '---';
                    let currentIdBuku = response.current ? response.current.id_buku : '0';
                    let hasWaiting = (response.waiting.length > 0);

                    // A. Update Tampilan Utama
                    $('#current_call_display').text(currentNumber);
                    $('#id_buku_saat_ini').val(currentIdBuku);

                    // B. Kontrol Visual dan Tombol
                    let isDisabled = (currentNumber === '---');

                    // KUNCI PERBAIKAN: Kontrol Warna/Class
                    if (isDisabled) {
                        // Tampilan IDLE
                        $('#current_call_display').addClass('idle').removeClass('current-number');
                        $('#current_antrean_box').removeClass('calling-active');
                    } else {
                        // Tampilan AKTIF
                        $('#current_call_display').removeClass('idle').addClass('current-number');
                        $('#current_antrean_box').addClass('calling-active');
                    }

                    // Kontrol Tombol
                    $('#btn_reissue, #btn_complete, #btn_skip').prop('disabled', isDisabled);
                    $('#btn_call_next').prop('disabled', !hasWaiting &&
                        isDisabled); // Aktif jika ada waiting OR sudah ada yang dipanggil

                    // ... (Lanjutan kode update Daftar Tunggu) ...
                    let htmlList = '';
                    if (hasWaiting) {
                        $.each(response.waiting, function(index, antrean) {
                            htmlList += `<li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="fs-4 fw-semibold text-primary">${antrean.nomor_lengkap}</span>
                                <span class="badge bg-info rounded-pill">Urutan ${index + 1}</span>
                             </li>`;
                        });
                    } else {
                        htmlList =
                            '<li class="list-group-item text-center text-muted py-5">Tidak ada antrean menunggu saat ini.</li>';
                    }
                    $('#waiting_list_display').html(htmlList);
                },
                error: function() {
                    console.error("Gagal memuat status dashboard.");
                }
            });
        }

        // --- 2. FUNGSI AJAX PEMANGGIL UTAMA (callNext, complete, reissue, skip) ---

        // --- 2. FUNGSI AJAX PEMANGGIL UTAMA (callNext, complete, reissue, skip) ---

        function handleCallAction(actionType, idBuku) {
            let url = '';

            // Tentukan URL berdasarkan actionType
            if (actionType === 'next') url = '{{ route('api.dashboard.call_next') }}';
            else if (actionType === 'complete') url = '{{ route('api.dashboard.complete') }}';
            else if (actionType === 'reissue') url = '{{ route('api.dashboard.reissue') }}';
            else if (actionType === 'skip') url = '{{ route('api.dashboard.skip') }}';
            else return;

            let buttonId = `#btn_${actionType}`;
            let button = $(buttonId);

            const originalHtml = button.data('original-html');

            // Tombol Lewati dan Selesaikan butuh ID buku
            if ((actionType !== 'next') && idBuku === '0') return;

            button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Proses...');

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    loket_id: LOKET_ID,
                    id_buku_saat_ini: idBuku
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let title = '';
                        let text = '';
                        let icon = 'success';

                        // Tentukan Pesan SweetAlert
                        switch (actionType) {
                            case 'next':
                                title = 'Panggilan Berhasil!';
                                text = `Nomor antrean ${response.nomor} telah dipanggil.`;
                                break;
                            case 'complete':
                                title = 'Selesai!';
                                text = 'Layanan telah ditutup. Loket siap untuk antrean baru.';
                                break;
                            case 'reissue':
                                title = 'Panggilan Ulang Dikirim!';
                                text = 'Nomor antrean telah dimasukkan kembali ke antrean suara TV.';
                                break;
                            case 'skip':
                                title = 'Antrean Dilewati!';
                                text = 'Antrean ini ditandai sebagai Lewat. Memproses nomor berikutnya.';
                                icon = 'warning';
                                break;
                        }

                        Swal.fire({
                            icon: icon,
                            title: title,
                            text: text,
                            // toast: true,
                            // position: 'top-end', // Tampilkan di pojok kanan atas
                            showConfirmButton: false,
                            timer: 3000
                        });

                    } else if (response.status === 'no_queue') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Tidak Ada Antrean!',
                            text: 'Tidak ada antrean MENUNGGU lagi untuk Loket Anda.',
                            // toast: true,
                            // position: 'top-end',
                            showConfirmButton: false,
                            timer: 4000
                        });
                    } else {
                        // Pesan Error
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message || 'Terjadi kesalahan saat memproses data.',
                            showConfirmButton: true
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Koneksi Gagal!',
                        text: 'Terjadi kesalahan server saat aksi. Cek koneksi Anda.',
                        showConfirmButton: true
                    });
                },
                complete: function() {
                    // Reset tombol dan refresh dashboard
                    button.prop('disabled', false).html(originalHtml);
                    updateDashboard();
                }
            });
        }

        // --- EVENT HANDLERS ---
        // Panggil Berikutnya
        $('#btn_call_next').on('click', function() {
            handleCallAction('next', '0');
        });
        // Selesaikan
        $('#btn_complete').on('click', function() {
            handleCallAction('complete', $('#id_buku_saat_ini').val());
        });
        // Panggil Ulang
        $('#btn_reissue').on('click', function() {
            handleCallAction('reissue', $('#id_buku_saat_ini').val());
        });
        // Lewati
        $('#btn_skip').on('click', function() {
            handleCallAction('skip', $('#id_buku_saat_ini').val());
        });


        // --- Inisiasi Polling ---
        $(document).ready(function() {
            updateDashboard();
            setInterval(updateDashboard, 5000); // Polling setiap 5 detik
        });
    </script>
@endpush
