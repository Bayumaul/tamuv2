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
                <div class="col-md-3 mb-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" id="filter-start-date" class="form-control" />
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" id="filter-end-date" class="form-control" />
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Filter Loket</label>
                    <select id="filter-loket" class="form-select">
                        <option value="">Semua Loket</option>
                        @foreach ($loketMaster as $id => $nama)
                            <option value="{{ $id }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tipe Layanan</label>
                    <select id="filter-tipe" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="Online">Online</option>
                        <option value="Offline">Offline</option>
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <button id="apply-filter" class="btn btn-primary me-2"><i class="menu-icon icon-base ti tabler-filter me-1"></i> Terapkan Filter</button>
                    <button id="reset-filter" class="btn btn-label-secondary"><i class="menu-icon icon-base ti tabler-rotate-2 me-1"></i> reset</button>
                </div>
            </div>
        </div>
        
        <div class="card-body">
             <div class="card-datatable table-responsive">
                <table class="datatables-visits table" id="visits-table">
                    <thead class="border-top">
                        <tr>
                            <th>ID</th>
                            <th>Tanggal Kunjungan</th>
                            <th>Nama Pengunjung</th>
                            <th>Layanan Diminta</th>
                            <th>Nomor Antrean</th>
                            <th>Loket</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('templates/vuexy/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    const API_DATA = "{{ route('reports.visits.data') }}";
    const applyFilterBtn = $('#apply-filter');
    const table = $('#visits-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: API_DATA,
            type: 'GET',
            data: function (d) {
                d.start_date = $('#filter-start-date').val();
                d.end_date = $('#filter-end-date').val();
                d.loket = $('#filter-loket').val();
                d.tipe_layanan = $('#filter-tipe').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'tanggal', name: 'tanggal' },
            { data: 'nama', name: 'tamu.nama' }, // Contoh relasi di Datatables
            { data: 'layanan', name: 'layanan_detail.nama_layanan_detail' },
            { data: 'nomor_antrean', name: 'nomor_lengkap' },
            { data: 'loket', name: 'loket' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']], // Urutkan berdasarkan ID terbaru
        dom: '<"row mx-3 justify-content-between"<"d-md-flex col-md-6 mb-2"l><"d-md-flex col-md-6 mb-2 justify-content-end"f>>t<"row mx-3"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>'
    });

    // 1. Event untuk Terapkan Filter
    applyFilterBtn.on('click', function() {
        table.draw();
    });

    // 2. Event untuk Reset Filter
    $('#reset-filter').on('click', function() {
        $('#filter-start-date').val('');
        $('#filter-end-date').val('');
        $('#filter-loket').val('');
        $('#filter-tipe').val('');
        table.draw();
    });

    // 3. Event untuk Kirim Survei
    $('#visits-table').on('click', '.send-survey-btn', function() {
        const entryId = $(this).data('id');
        
        Swal.fire({
            title: 'Kirim Link Survei?',
            text: "Link survei akan dikirim via WhatsApp ke pelanggan ini.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url('admin/reports/send-survey') }}/' + entryId, // Gunakan URL langsung karena route resource tidak didukung
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire('Terkirim!', response.message, 'success');
                        table.draw(false); // Refresh tabel tanpa reset posisi
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', 'Terjadi kesalahan atau layanan belum selesai.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush