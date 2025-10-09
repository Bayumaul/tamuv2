@extends('layouts.app')

@section('title', 'Laporan Daftar Kunjungan')

@section('content')
<div class="flex-grow-1 container-p-y container-fluid">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin /</span> Laporan Kunjungan
    </h4>

    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Filter Data Kunjungan</h5>
            <div class="row pt-4">
                {{-- Filter Tanggal --}}
                <div class="col-md-3 mb-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" id="filter-start-date" class="form-control" />
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" id="filter-end-date" class="form-control" />
                </div>
                
                {{-- Filter Loket --}}
                <div class="col-md-3 mb-3">
                    <label class="form-label">Filter Loket</label>
                    <select id="filter-loket" class="form-select">
                        <option value="">Semua Loket</option>
                        @foreach ($loketMaster as $id => $nama)
                            <option value="{{ $id }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Filter Tipe Layanan --}}
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tipe Layanan</label>
                    <select id="filter-tipe" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="Online">Online</option>
                        <option value="Offline">Offline</option>
                    </select>
                </div>
                
                <div class="col-12 mt-3">
                    <button id="apply-filter" class="btn btn-primary me-2"><i class="ti ti-filter me-1"></i> Terapkan Filter</button>
                    <button id="reset-filter" class="btn btn-label-secondary"><i class="ti ti-rotate-2 me-1"></i> Reset</button>
                </div>
            </div>
        </div>
        
        <div class="card-body">
             <div class="card-datatable table-responsive">
                <table class="datatables-visits table" id="visits-table">
                    <thead class="border-top">
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Nama Pengunjung</th>
                            <th>Layanan</th>
                            <th>Nomor Antrean</th>
                            <th>Loket</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Data dimuat via AJAX --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Pastikan ini dimuat di layout Anda --}}
<script src="{{ asset('templates/vuexy/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    const API_DATA = "{{ route('reports.visits.data') }}";
    const API_SURVEY_SEND = "{{ route('reports.send_survey', ['id' => ':id']) }}";
    const applyFilterBtn = $('#apply-filter');
    
    // Data Template Survei dari PHP ke JavaScript
    const SURVEY_TEMPLATES = @json($SurveyLinks);

    const table = $('#visits-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: API_DATA,
            type: 'GET',
            data: function (d) {
                // Mengirim parameter filter ke Controller
                d.start_date = $('#filter-start-date').val();
                d.end_date = $('#filter-end-date').val();
                d.loket = $('#filter-loket').val();
                d.tipe_layanan = $('#filter-tipe').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'tanggal', name: 'tanggal' },
            { data: 'nama', name: 'tamu.nama' }, 
            { data: 'layanan', name: 'layanan_detail.nama_layanan_detail' },
            { data: 'nomor_antrean', name: 'nomor_lengkap' },
            { data: 'loket', name: 'loket' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        dom: '<"row mx-3 justify-content-between"<"d-md-flex col-md-6 mb-2"l><"d-md-flex col-md-6 mb-2 justify-content-end"f>>t<"row mx-3"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>'
    });

    // 1. Event untuk Terapkan Filter & Reset
    applyFilterBtn.on('click', function() { table.draw(); });
    $('#reset-filter').on('click', function() {
        $('#filter-start-date, #filter-end-date, #filter-loket, #filter-tipe').val('');
        table.draw();
    });

    // 2. Event untuk Kirim Survei (Menggunakan Dropdown SweetAlert)
    $('#visits-table').on('click', '.send-survey-btn', function() {
        const entryId = $(this).data('id');

        if ($(this).attr('disabled')) {
             Swal.fire('Info', 'Survei hanya bisa dikirim setelah layanan Selesai.', 'info');
             return;
        }
        
        // Cek apakah ada template aktif
        if (SURVEY_TEMPLATES.length === 0) {
            Swal.fire('Info', 'Tidak ada template survei yang diatur AKTIF oleh Administrator.', 'warning');
            return;
        }

        // --- Membangun Dropdown HTML untuk SweetAlert ---
        let dropdownHtml = '';
        SURVEY_TEMPLATES.forEach(template => {
            dropdownHtml += `<a class="dropdown-item survey-option" href="#" 
                                data-template-id="${template.id}" data-template-name="${template.name}">
                                <i class="ti ti-file-text me-2"></i> ${template.name}
                            </a>`;
        });
        
        Swal.fire({
            title: 'Pilih Template Survei',
            html: `<div class="dropdown-menu d-block position-static shadow-lg border p-2">${dropdownHtml}</div>`,
            showCancelButton: true,
            showConfirmButton: false, 
            cancelButtonText: 'Batal',
            focusCancel: true
        });

        // Event Listener untuk item yang dipilih di SweetAlert
        $('.survey-option').off('click').on('click', function(e) {
            e.preventDefault();
            const templateId = $(this).data('template-id');
            const templateName = $(this).data('template-name');
            Swal.close(); 
            
            Swal.fire({
                title: `Kirim Template: ${templateName}?`,
                text: "Survei akan dikirim via WhatsApp ke pelanggan ini.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kirim Sekarang!',
            }).then((result) => {
                if (result.isConfirmed) {
                    sendAjaxSurvey(entryId, templateId);
                }
            });
        });
    });

    // 3. Fungsi AJAX yang Sebenarnya untuk Mengirim Survei
    function sendAjaxSurvey(entryId, templateId) {
        const url = '{{ route('reports.send_survey', ['id' => ':id']) }}'.replace(':id', entryId);

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                template_id: templateId // Kirim ID template yang dipilih
            },
            dataType: 'json',
            beforeSend: function() {
                Swal.fire({ title: 'Mengirim...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => { Swal.showLoading(); } });
            },
            success: function(response) {
                Swal.fire('Terkirim!', response.message, 'success');
                table.draw(false); 
            },
            error: function(xhr) {
                Swal.fire('Gagal!', 'Terjadi kesalahan saat mengirim link survei.', 'error');
            }
        });
    }
});
</script>
@endpush