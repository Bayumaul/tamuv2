@extends('layouts.app')

@section('title', 'Laporan Daftar Kunjungan')

@section('content')
    <div class="flex-grow-1 container-p-y container-fluid">

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

                    {{-- Filter Loket --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Filter Loket</label>
                        <select id="filter-loket" class="form-select ">
                            <option value="">Semua Loket</option>
                            @foreach ($loketMaster as $id => $nama)
                                <option value="{{ $id }}">{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Tipe Layanan --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Tipe Layanan</label>
                        <select id="filter-tipe" class="form-select ">
                            <option value="">Semua Tipe</option>
                            <option value="Online">Online</option>
                            <option value="Offline">Offline</option>
                        </select>
                    </div>

                    <div class="col-12 mt-3">
                        <button id="apply-filter" class="btn btn-primary me-2"><i
                                class="menu-icon icon-base ti tabler-filter me-1"></i> Terapkan Filter</button>
                        <button id="reset-filter" class="btn btn-label-secondary me-2"><i
                                class="menu-icon icon-base ti tabler-rotate-2 me-1"></i> Reset</button>
                        <button id="generate-pdf" class="btn btn-danger"><i
                                class="menu-icon icon-base ti tabler-file-text me-1"></i> Generate PDF Rekapan</button>

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
                                <th>Tipe</th>
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

    <div class="modal-onboarding modal fade animate__animated" id="multiStepSurveyModal" tabindex="-1" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content text-center">
                <div class="modal-header border-0">
                    <a class="text-body-secondary close-label" href="javascript:void(0);" data-bs-dismiss="modal"></a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="modal-carousel" class="carousel slide" data-bs-interval="false" data-bs-wrap="false">

                    {{-- Indicators / Navigasi --}}
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#modal-carousel" data-bs-slide-to="0" class="active"
                            aria-current="true"></button>
                        <button type="button" data-bs-target="#modal-carousel" data-bs-slide-to="1"></button>
                    </div>

                    <div class="carousel-inner">

                        {{-- STEP 1: PILIH TEMPLATE (DROPDOWN) --}}
                        <div class="carousel-item active">
                            <div class="onboarding-media pt-4">
                                <img src="{{ asset('templates/vuexy/assets/img/illustrations/girl-with-laptop-light.png') }}"
                                    alt="Pilih Template" width="200" class="img-fluid" />
                            </div>
                            <div class="onboarding-content p-4">
                                <h4 class="onboarding-title text-body mb-4">Langkah 1: Pilih Template Survei</h4>

                                <div class="dropdown d-block w-100 mb-3">
                                    <button class="btn btn-label-secondary dropdown-toggle w-100" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Pilih Template
                                    </button>
                                    <div class="dropdown-menu w-100 p-2" id="template-dropdown-container">
                                        {{-- Dropdown content diisi oleh JS --}}
                                    </div>
                                </div>

                                <p class="text-danger">Survei hanya bisa dikirim untuk layanan yang Selesai.</p>

                                <a href="javascript:void(0);" class="btn btn-label-secondary mt-3"
                                    data-bs-dismiss="modal">Tutup</a>
                            </div>
                        </div>

                        {{-- STEP 2: KONFIRMASI DAN PREVIEW PESAN --}}
                        <div class="carousel-item">
                            <div class="onboarding-media pt-4">
                                <img src="{{ asset('templates/vuexy/assets/img/illustrations/boy-with-laptop-light.png') }}"
                                    alt="Konfirmasi" width="200" class="img-fluid" />
                            </div>
                            <div class="onboarding-content p-4">
                                <h4 class="onboarding-title text-body mb-4">Langkah 2: Konfirmasi Pengiriman</h4>

                                <div class="text-start mb-3 border p-3 rounded">
                                    <p class="mb-1">Tujuan: <strong id="confirm-name" class="text-dark"></strong>
                                        (<span id="confirm-phone"></span>)</p>
                                    <p class="mb-1">Layanan: <strong id="confirm-service"
                                            class="text-primary"></strong> (<span id="confirm-service-detail"></span>)</p>
                                    <p class="mb-0">Template Aktif: <strong id="confirm-template-name"
                                            class="text-success"></strong></p>
                                </div>

                                <div class="mb-3">
                                    <label for="confirm-caption" class="form-label">Preview Pesan yang Akan
                                        Dikirim:</label>
                                    <textarea id="confirm-caption" class="form-control" rows="6" readonly></textarea>
                                </div>

                                {{-- Input Tersembunyi untuk AJAX --}}
                                <input type="hidden" id="final-entry-id">
                                <input type="hidden" id="final-template-id">

                                <div class="d-flex justify-content-between pt-3">
                                    <a href="#modal-carousel" role="button" data-bs-slide="prev"
                                        class="btn btn-label-secondary">
                                        <i class="menu-icon icon-base ti tabler-chevron-left me-2"></i> Ubah Pilihan
                                    </a>
                                    <button id="final-send-btn" class="btn btn-success">
                                        Kirim Sekarang <i class="menu-icon icon-base ti tabler-send ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END: MULTI-STEP SURVEY MODAL --}}

@endsection

@push('scripts')
    {{-- Memastikan SweetAlert dan Datatables dimuat --}}
    <script src="{{ asset('templates/vuexy/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {
            const API_DATA = "{{ route('reports.visits.data') }}";
            const API_GET_DETAILS = "{{ route('get_entry_details', ['id' => ':id']) }}";
            const API_SURVEY_SEND = "{{ route('reports.send_survey', ['id' => ':id']) }}";

            // Data Template Survei dari PHP ke JavaScript
            const SURVEY_TEMPLATES = @json($SurveyLinks);
            let currentEntryId = null;

            const table = $('#visits-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: API_DATA,
                    type: 'GET',
                    data: function(d) {
                        // Mengirim parameter filter ke Controller
                        d.start_date = $('#filter-start-date').val();
                        d.end_date = $('#filter-end-date').val();
                        d.loket = $('#filter-loket').val();
                        d.tipe_layanan = $('#filter-tipe').val();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'nama',
                        name: 'tamu.nama'
                    },
                    {
                        data: 'layanan',
                        name: 'layanan_detail.nama_layanan_detail'
                    },
                    {
                        data: 'nomor_antrean',
                        name: 'nomor_lengkap'
                    },
                    {
                        data: 'loket',
                        name: 'loket'
                    },
                    {
                        data: 'tipe_layanan',
                        name: 'tipe_layanan'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [0, 'desc']
                ],
                dom: '<"row mx-3 justify-content-between"<"d-md-flex col-md-6 mb-2"l><"d-md-flex col-md-6 mb-2 justify-content-end"f>>t<"row mx-3"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>'
            });

            // 1. Event untuk Terapkan Filter & Reset
            $('#apply-filter').on('click', function() {
                table.draw();
            });
            $('#reset-filter').on('click', function() {
                $('#filter-start-date, #filter-end-date, #filter-loket, #filter-tipe').val('');
                table.draw();
            });

            // 2. Event untuk Tombol Kirim Survei di Datatables
            $('#visits-table').on('click', '.send-survey-btn', function() {
                currentEntryId = $(this).data('id');

                if ($(this).attr('disabled')) {
                    Swal.fire('Info', 'Survei hanya bisa dikirim setelah layanan Selesai.', 'info');
                    return;
                }

                // Cek apakah ada template aktif
                if (SURVEY_TEMPLATES.length === 0) {
                    Swal.fire('Info', 'Tidak ada template survei yang diatur AKTIF oleh Administrator.',
                        'warning');
                    return;
                }

                // Ambil data detail pengunjung dan layanan via AJAX
                $.ajax({
                    url: API_GET_DETAILS.replace(':id', currentEntryId),
                    method: 'GET',
                    success: function(response) {
                        if (response.status === 'success') {
                            const data = response.data;

                            // --- Mengisi Konten Modal Langkah 1 (Dropdown) ---
                            let dropdownHtml = '';
                            SURVEY_TEMPLATES.forEach(template => {
                                // Kunci: Simpan link_url dan caption di data-* agar bisa digunakan di step 2
                                dropdownHtml += `<a class="dropdown-item select-template-option" href="#" 
                                            data-template-id="${template.id}" data-template-name="${template.name}"
                                            data-link="${template.link_url}" data-caption="${template.caption.replace(/\n/g, '\\n')}">
                                            <i class="menu-icon icon-base ti tabler-file-text me-2"></i> ${template.name}
                                        </a>`;
                            });

                            $('#template-dropdown-container').html(dropdownHtml);

                            // Isi data pengunjung statis di modal (Langkah 2 preview)
                            $('#final-entry-id').val(data.id);
                            $('#confirm-name').text(data.nama);
                            $('#confirm-phone').text(data.no_hp);
                            $('#confirm-service').text(data.layanan);
                            $('#confirm-service-detail').text(data.layanan_detail);
                            console.log(data);
                            // Tampilkan Modal
                            $('#modal-carousel').carousel(0); // Reset ke langkah pertama
                            $('#multiStepSurveyModal').modal('show');
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    }
                });
            });


            // 3. EVENT TEMPLATE TERPILIH (Langkah 1 -> Langkah 2)
            $(document).on('click', '.select-template-option', function(e) {
                e.preventDefault();
                const templateId = $(this).data('template-id');
                const templateName = $(this).data('template-name');
                const templateLink = $(this).data('link');
                const templateCaption = $(this).data('caption').replace(/\\n/g, '\n'); // Ganti \n

                // Ambil data pengunjung yang sudah disimpan di modal
                const namaTamu = $('#confirm-name').text();

                // A. Proses Caption (Ganti Placeholder)
                const finalCaption = templateCaption.replace('{nama}', namaTamu)
                    .replace('{link_survei}', templateLink);

                // B. Isi Konten Langkah 2
                $('#confirm-template-name').text(templateName);
                $('#confirm-caption').val(finalCaption);

                // Simpan ID template untuk Langkah 3 (Kirim)
                $('#final-template-id').val(templateId);

                // Pindah ke Langkah 2 (Confirmation)
                $('#modal-carousel').carousel('next');
            });


            // 4. EVENT KIRIM (Langkah 2 -> AJAX)
            $('#final-send-btn').on('click', function() {
                const entryId = $('#final-entry-id').val();
                const templateId = $('#final-template-id').val();

                console.log([entryId, templateId]);

                sendAjaxSurvey(entryId, templateId);

                $('#multiStepSurveyModal').modal('hide');
            });

            // 5. Fungsi AJAX Pengiriman Sebenarnya
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
                        Swal.fire({
                            title: 'Mengirim...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        Swal.fire('Terkirim!', response.message, 'success');
                        $('#visits-table').DataTable().draw(false);
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat mengirim link survei.', 'error');
                    }
                });
            }

            // 6. Logika Tombol Generate PDF
            $('#generate-pdf').on('click', function() {
                const startDate = $('#filter-start-date').val();
                const endDate = $('#filter-end-date').val();
                const loket = $('#filter-loket').val();

                // Membangun URL dengan parameter filter
                const params = {
                    start_date: startDate,
                    end_date: endDate,
                    loket: loket
                };

                // Membuat query string dari parameter
                const queryString = new URLSearchParams(params).toString();

                // Arahkan browser ke route PDF generation
                window.location.href = "{{ route('reports.generate_pdf') }}?" + queryString;
            });
        });
    </script>
@endpush
