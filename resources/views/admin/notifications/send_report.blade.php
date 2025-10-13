@extends('layouts.app') 

@section('title', 'Kirim Notifikasi Rekap Pimpinan')

@section('content')
<div class="container-p-y container-fluid">
    <div class="row">
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">Formulir Pengiriman Laporan Rekap</h5>
                </div>
                <div class="card-body">
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">Harap periksa isian formulir.</div>
                    @endif

                    <form action="{{ route('admin.notif.send') }}" method="POST">
                        @csrf

                        {{-- Pilihan Periode --}}
                        <div class="mb-5 mt-2">
                            <label class="form-label">Periode Laporan</label>
                            <select name="periode" class="form-select @error('periode') is-invalid @enderror" required>
                                <option value="daily" {{ old('periode') == 'daily' ? 'selected' : '' }}>Rekap Harian (Hari Ini)</option>
                                <option value="monthly" {{ old('periode') == 'monthly' ? 'selected' : '' }}>Rekap Bulanan (Bulan Ini)</option>
                            </select>
                            <small class="form-text text-muted">Data akan dihitung hingga saat ini.</small>
                            @error('periode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Nomor Tujuan --}}
                        <div class="mb-5">
                            <label class="form-label" for="target_phone">Nomor HP/WA Pimpinan (Contoh: 0812xxxxxx)</label>
                            <input type="tel" name="target_phone" value="085713823408" class="form-control @error('target_phone') is-invalid @enderror" placeholder="0812xxxxxx" required value="{{ old('target_phone') }}">
                            @error('target_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mt-4 pt-2">
                            <button type="submit" class="btn btn-primary me-3">
                                <i class="menu-icon icon-base ti tabler-send me-1"></i> Kirim Laporan ke WhatsApp
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        {{-- BLOK PREVIEW PESAN --}}
        <div class="col-lg-5">
            <div class="card bg-light">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">Preview Pesan WA</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Pesan akan diformat sebagai berikut:</p>
                    <pre style="white-space: pre-wrap; font-size: 0.9rem; background: #fff; padding: 10px; border-radius: 5px;">
*REKAP PELAYANAN KANWIL KEMENKUM DIY*
==============================
*Laporan [Harian/Bulanan] Periode [Tanggal]*

• Total Pendaftaran: *[Jumlah]*
• Layanan Selesai: *[Jumlah]*
• Antrean Lewat: *[Jumlah]*
• Tingkat Kehadiran: *[XX.X%]*

Rincian Tipe Pendaftaran:
• Online: *[Jumlah]*
• Offline: *[Jumlah]*

Laporan detail dapat dilihat di Dashboard Admin.
Terima kasih.
_Sistem Otomatis_</pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection