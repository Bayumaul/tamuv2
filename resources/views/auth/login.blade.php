@extends('layouts.guest')
{{-- Asumsikan 'layouts.guest' sudah ada dan menyediakan struktur dasar HTML/BODY --}}

@section('title', 'Login Sistem Antrean - Kanwil Kemenkum DIY')

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-6">
            <div class="card">
                <div class="card-body">
                    
                    {{-- KANWIL LOGO & TITLE --}}
                    <div class="app-brand justify-content-center mb-6">
                        <a href="{{ url('/') }}" class="app-brand-link">
                            <img src="{{ asset('img/logo.png') }}" alt="Logo Kemenkum" style="height: 35px;">
                            <span class="app-brand-text demo text-heading fw-bold ms-2">KANWIL KEMENKUM DIY</span>
                        </a>
                    </div>
                    
                    <h4 class="mb-1">Akses Sistem Antrean 👋</h4>
                    <p class="mb-6">Silakan masuk dengan akun yang terdaftar.</p>

                    {{-- Tampilkan Pesan Error Laravel --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- FORM LOGIN --}}
                    <form id="formAuthentication" class="mb-4" method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        {{-- Username (Ganti Email) --}}
                        <div class="mb-6 form-control-validation">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                id="username" name="username" value="{{ old('username') }}" 
                                placeholder="Masukkan username Anda" autofocus required />
                            @error('username')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-6 form-password-toggle form-control-validation">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" 
                                    name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" required />
                                <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Remember Me --}}
                        <div class="my-8">
                            <div class="d-flex justify-content-between">
                                <div class="form-check mb-0 ms-2">
                                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember" />
                                    <label class="form-check-label" for="remember_me"> Ingat Saya </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">
                                        <p class="mb-0">Lupa Password?</p>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="mb-6">
                            <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                        </div>
                    </form>
                    
                    {{-- Tambahkan tautan untuk pengguna umum jika perlu --}}
                    <p class="text-center">
                        <span>Akses cepat untuk Layanan Publik?</span>
                        <a href="{{ route('online') }}">
                            <span>Daftar Antrean Online</span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection