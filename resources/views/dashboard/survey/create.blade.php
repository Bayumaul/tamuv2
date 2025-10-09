@extends('layouts.app') 
@section('title', 'Tambah Link Survei Baru')
@section('content')
<div class="container-p-y container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">Formulir Tambah Link Survei</h5>
                </div>
                <div class="card-body">
                    
                    {{-- Tampilkan Error Validation Global --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            Harap periksa kembali isian Anda.
                        </div>
                    @endif
                    
                    {{-- Form diarahkan ke Controller SurveyLink Store --}}
                    {{-- Asumsi Route Anda adalah 'admin.surveys.store' --}}
                    <form action="{{ route('survey.store') }}" method="POST" class="add-new-survey pt-0">
                        @csrf
                        
                        {{-- 1. Nama Survei --}}
                        <div class="mb-6 mt-6 form-control-validation">
                            <label class="form-label" for="survey-name">Nama Survei</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="survey-name" placeholder="Contoh: Survei Kepuasan Pelayanan Umum" name="name" required value="{{ old('name') }}">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        {{-- 2. Link URL Survei --}}
                        <div class="mb-6 form-control-validation">
                            <label class="form-label" for="survey-caption">Isi Pesan / Caption WhatsApp</label>
                            <textarea id="survey-caption" class="form-control @error('caption') is-invalid @enderror" name="caption" rows="5" required placeholder="Halo Sahabat Pengayoman DIY 👋
Sudah menerima pelayanan dari kami hari ini?
Yuk bantu kami jadi lebih baik dengan isi Survey Integritas Pelayanan! ✨

Cuma butuh waktu sebentar, tapi dampaknya luar biasa buat peningkatan pelayanan publik di Kanwil Kemenkum D.I. Yogyakarta.
Lewat survei ini, kamu bisa kasih penilaian dan masukan jujur tentang pelayanan yang kamu rasakan — cepat nggak? ramah nggak? profesional nggak?

Semua jawaban kamu anonim dan sangat berarti buat kami.
Yuk isi sekarang dengan klik link di bawah ini:
👉 [link survei]

Bersama kita wujudkan pelayanan publik yang bersih, berintegritas, dan makin dekat dengan masyarakat! 💙">{{ old('caption') }}</textarea>
                            @error('caption') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        
                        {{-- 3. Status Aktif / Tidak Aktif --}}
                        <div class="mb-6">
                            <label class="form-label" for="survey-active">Status Link</label>
                            <select id="survey-active" class="form-select @error('is_active') is-invalid @enderror" name="is_active" required>
                                <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>Aktif (Link ini akan digunakan)</option>
                                <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mt-4 pt-2">
                            <button type="submit" class="btn btn-primary me-3">Simpan Link Survei</button>
                            {{-- Ubah users.index menjadi surveys.index --}}
                            <a href="{{ route('survey.index') }}" class="btn btn-label-danger">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection