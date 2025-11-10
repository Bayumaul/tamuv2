@extends('layouts.app')

@section('title', 'Dashboard Petugas - ' . $namaLoket == 1 ? 'Kekayaan Intelektual' : 'Administrasi Hukum Umum')

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

        /* ... CSS Lainnya ... */
        /* Styling untuk item antrean menunggu yang baru */
        .queue-item {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s;
        }

        .queue-item:last-child {
            border-bottom: none;
        }

        .queue-number-large {
            font-size: 1.8rem;
            /* Lebih besar dari sebelumnya */
            font-weight: 700;
            color: #007bff;
            /* Primary color */
        }

        .queue-badge-urutan {
            min-width: 80px;
        }

        .list-group-item.text-center.text-muted.py-5 {
            font-style: italic;
            color: #6c757d !important;
        }

        /* Optional: Sedikit hover effect */
        .queue-item:hover {
            background-color: #f8f9fa;
        }
    </style>

    <div class="mt-5">

        {{-- Input Tersembunyi --}}
        <input type="hidden" id="loket_id" value="{{ $loketId }}">
        <input type="hidden" id="id_buku_saat_ini" value="0">
        <div class="container mt-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-primary">{{ $namaLoket == 1 ? 'Kekayaan Intelektual' : 'Administrasi Hukum Umum' }}</h1>

                {{-- TOMBOL BARU: DAFTAR MANUAL --}}
                <button class="btn btn-info btn-lg-action fw-bold" data-bs-toggle="modal"
                    data-bs-target="#manualRegisterModal">
                    <i class="fe fe-user-plus me-2"></i> Daftar Kunjungan Langsung
                </button>
                {{-- ... (Sisa header dan logout) ... --}}
            </div>
            <hr>

            {{-- ... (lanjutan row Dashboard) ... --}}
        </div>
        <div class="row">
            {{-- 1. KONTROL PANGGILAN (Kolom Kiri) --}}
            <div class="col-md-6">
                <div class="card shadow ">
                    <div class="card-header bg-primary text-white">
                        Pusat Kontrol Layanan
                    </div>
                    <div class="card-body text-center mt-4">
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
                    {{-- Menghapus class list-group-flush dari ul, dan membungkusnya dalam div.card-body untuk padding --}}
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush overflow-auto" id="waiting_list_display"
                            style="min-height: 480px; max-height: 480px;">
                            <li class="list-group-item text-center text-muted py-5">
                                <i class="fe fe-loader fe-spin me-2"></i> Memuat daftar antrean...
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- START: MODAL INPUT MANUAL / ON-SITE REGISTRATION --}}
    <div class="modal fade" id="manualRegisterModal" tabindex="-1" aria-labelledby="manualRegisterModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title" id="manualRegisterModalLabel">Pendaftaran Langsung (On-Site)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                {{-- Form akan POST ke route offlineRegistration Anda --}}
                <form id="formManualRegistration" method="POST" action="{{ route('offline.registration') }}">
                    @csrf
                    <div class="modal-body">

                        {{-- Asumsi Anda memiliki variabel $priorityCategories dan $layanans dari Controller --}}

                        {{-- Input NIK dan Check Data (Opsional, tapi disarankan) --}}
                        <div class="mb-3">
                            <label for="manual_nik" class="form-label">NIK (Cek Data)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="manual_nik" name="nik"
                                    placeholder="Masukkan NIK" required>
                                <button class="btn btn-primary" type="button" id="checkManualDataBtn">Periksa</button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="manual_name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="manual_name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="manual_no_hp" class="form-label">No HP/WhatsApp</label>
                                    <input type="tel" class="form-control" id="manual_no_hp" name="no_hp" required>
                                </div>
                                <div class="mb-3">
                                    <label for="manual_priority" class="form-label">Kategori Prioritas</label>
                                    <select class="form-select" id="manual_priority" name="id_priority_category"
                                        required>
                                        <option value="">Pilih Prioritas</option>
                                        @foreach ($priorityCategories as $category)
                                            <option value="{{ $category->id }}" {{ $category->id == 1 ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="kategori_pengunjung" value="1">
                                    {{-- Default Perorangan --}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="manual_alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="manual_alamat" name="alamat" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="manual_layanan" class="form-label">Layanan yang Dituju</label>
                                    <select class="form-select" id="manual_layanan" name="layanan" required>
                                        <option value="">Pilih Jenis Layanan</option>
                                        {{-- Asumsi $layanans dikirim Controller --}}
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
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary"><i class="fe fe-send me-2"></i> Daftarkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- END: MODAL INPUT MANUAL --}}
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
        const PRIORITY_MAP = {
            // ID: { name: 'Nama Kategori', badge: 'bg-color' }
            2: {
                name: 'DISABILITAS',
                badge: 'bg-danger'
            }, // ID 2 = Prioritas Tertinggi (Merah)
            3: {
                name: 'LANSIA/IBU HAMIL',
                badge: 'bg-info'
            }, // ID 3 = Prioritas Tinggi (Biru)
            1: {
                name: 'UMUM',
                badge: 'bg-secondary'
            } // ID 1 = Default/Umum (Abu-abu)
        };

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

                    let htmlList = '';
                    if (hasWaiting) {
                        console.log(response.waiting);
                        $.each(response.waiting, function(index, antrean) {

                            const priorityId = antrean.id_priority_category || 1;
                            const priorityData = PRIORITY_MAP[priorityId] || PRIORITY_MAP[1];

                            const categoryName = priorityData.name;
                            const priorityBadgeClass = priorityData.badge;

                            const layananDetailName = antrean.layanan_detail ?
                                antrean.layanan_detail.nama_layanan_detail :
                                'Layanan';

                            // --- KUNCI PERBAIKAN: Menetapkan class berdasarkan status_antrean ---
                            let itemClass = '';
                            if (antrean.status_antrean === 'LEWAT') {
                                itemClass =
                                'bg-warning-light border border-warning'; // Contoh class warning yang lebih terang
                            }
                            // --- AKHIR KUNCI PERBAIKAN ---

                            htmlList += `
            <li class="list-group-item d-flex justify-content-between align-items-center queue-item ${itemClass}">
                <div class="d-flex align-items-center">
                    <i class="fe fe-users me-3 text-muted"></i>
                    <div>
                        <span class="d-block text-uppercase text-muted" style="font-size: 0.8rem;">
                            ${antrean.status_antrean === 'LEWAT' ? 'LEWAT DIPANGGIL' : 'NOMOR ANTREAN'} 
                        </span>
                        <span class="queue-number-large text-primary fw-bold">${antrean.nomor_lengkap}</span>
                    </div>
                </div>
                <div class="text-end">
                    
                    {{-- Badge Kategori Prioritas --}}
                    <span class="badge ${priorityBadgeClass} text-white p-1 mb-1 fw-bold">
                        ${categoryName}
                    </span>

                    {{-- Urutan dan Layanan --}}
                    <span class="d-block text-muted" style="font-size: 0.75rem;">
                        (${layananDetailName})
                    </span>
                    
                </div>
            </li>
        `;
                        });
                    } else {
                        htmlList =
                            '<li class="list-group-item text-center text-muted py-5"><i class="fe fe-check-circle me-2"></i> Semua antrean telah diproses.</li>';
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
    <script>
        // Asumsi route validatenik sudah didefinisikan
        const API_VALIDATE_NIK = "{{ route('validatenik') }}";
        const ID_KATEGORI_PERORANGAN = 1; // Asumsi kategori pengunjung Perorangan

        // 1. Fungsi Cek NIK (Sama seperti di form online/offline)
        $('#checkManualDataBtn').on('click', function() {
            const nik = $('#manual_nik').val();
            if (nik.length !== 16) {
                Swal.fire('Warning', 'NIK harus 16 digit.', 'warning');
                return;
            }

            $.ajax({
                url: API_VALIDATE_NIK,
                method: 'GET',
                data: {
                    nik: nik,
                    kategori: ID_KATEGORI_PERORANGAN
                },
                success: function(response) {
                    if (response.status === 'not_found') {
                        Swal.fire('Info', 'Data NIK tidak ditemukan. Harap isi data diri lengkap.',
                            'info');
                        // Biarkan field kosong agar petugas mengisi
                        $('#manual_name').val('');
                        $('#manual_no_hp').val('');
                        $('#manual_alamat').val('');
                    } else if (response.id_tamu) {
                        Swal.fire('Success', 'Data ditemukan! Field terisi otomatis.', 'success');
                        $('#manual_name').val(response.nama);
                        $('#manual_no_hp').val(response.no_hp);
                        $('#manual_alamat').val(response.alamat);
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Gagal cek data server.', 'error');
                }
            });
        });

        // 2. Clear Form saat Modal Tertutup
        $('#manualRegisterModal').on('hidden.bs.modal', function() {
            $('#formManualRegistration')[0].reset();
            // Set kembali priority ke umum (ID 1)
            $('#manual_priority').val(1);
        });
    </script>
@endpush
