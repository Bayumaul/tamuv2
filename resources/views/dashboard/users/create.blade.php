@extends('layouts.app')

@section('title', 'Tambah Petugas Baru')

@section('content')
    <div class="container-p-y container-fluid">

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">Formulir Tambah Petugas Loket</h5>
                    </div>
                    <div class="card-body">
                        {{-- Tampilkan Error Validation Global --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                Harap periksa kembali isian Anda.
                            </div>
                        @endif

                        {{-- Form diarahkan ke Controller Store (Pastikan Route sudah benar) --}}
                        {{-- Route::resource menggunakan 'users.store' --}}
                        <form action="{{ route('users.store') }}" method="POST" class="add-new-user pt-0">
                            @csrf

                            <div class="mb-6 mt-6 form-control-validation">
                                <label class="form-label" for="add-user-fullname">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="add-user-fullname" placeholder="Nama Petugas" name="name" required
                                    value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-6 form-control-validation">
                                <label class="form-label" for="add-user-username">username</label>
                                <input type="text" id="add-user-username"
                                    class="form-control @error('username') is-invalid @enderror" placeholder="Username"
                                    name="username" required value="{{ old('username') }}">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-6 form-control-validation">
                                <label class="form-label" for="add-user-email">Email</label>
                                <input type="email" id="add-user-email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="petugas@kemenkum.go.id" name="email" required
                                    value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-6 form-password-toggle form-control-validation">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" required />
                                    <span class="input-group-text cursor-pointer"><i
                                            class="icon-base ti tabler-eye-off"></i></span>
                                    @error('password')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="form-label" for="user-loket">Loket Bertugas</label>
                                <select id="user-loket" class="form-select @error('id_loket') is-invalid @enderror"
                                    name="id_loket" required>
                                    <option value="">Pilih Loket</option>
                                    {{-- Loop data masterLoket yang dikirim dari Controller index/create --}}
                                    @foreach ($masterLoket as $id => $nama)
                                        <option value="{{ $id }}" {{ old('id_loket') == $id ? 'selected' : '' }}>
                                            {{ $nama }}</option>
                                    @endforeach
                                </select>
                                @error('id_loket')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-4 pt-2">
                                <button type="submit" class="btn btn-primary me-3">Simpan Petugas</button>
                                <a href="{{ route('users.index') }}" class="btn btn-label-danger">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
