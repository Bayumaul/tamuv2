@extends('layouts.app')
@section('title', 'Manajemen Petugas Loket')
@section('content')
    <div class="flex-grow-1 container-p-y container-fluid">
        @include('layouts._alert')

        {{-- Cards Statistik (Contoh, Data harus dihitung di Controller) --}}
        <div class="row g-6 mb-6">
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">Total Petugas</span>
                                <div class="d-flex align-items-center my-1">
                                    {{-- Hitung total users dari database --}}
                                    <h4 class="mb-0 me-2">{{ App\Models\User::count() }}</h4>
                                </div>
                                <small class="mb-0">Petugas Terdaftar</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="icon-base ti tabler-users icon-26px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Card Stat lain dihilangkan atau diganti sesuai kebutuhan --}}
        </div>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Daftar Petugas</h2>
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <span class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-plus icon-xs"></i> <span
                                class="d-none d-sm-inline-block">Tambah Petugas </span></span>
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="card-datatable table-responsive pt-0">
                    <table class="table data-table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Username</th>
                                <th class="text-center">Loket Bertugas</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex justify-content-start align-items-center user-name">
                                            <div class="avatar-wrapper">
                                                <div class="avatar avatar-sm me-4"><span
                                                        class="avatar-initial rounded-circle bg-label-primary">VK</span>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <h6 class="text-nowrap mb-0">{{ $user->name }}</h6>
                                                <small class="text-truncate d-none d-sm-block">
                                                    {{ $user->email }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="text-heading">{{ $user->username }}</span></td>
                                    <td class="text-center"><span class="text-heading">{{ $user->id_loket }}</span></td>
                                    <td class="text-center">
                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>
                                        <a type="button" onclick="openModal({{ $user->id }})"
                                            class="btn btn-danger btn-sm text-white">Hapus</a>

                                        <form method="POST" id="form-delete-{{ $user->id }}"
                                            action="{{ route('users.destroy', $user->id) }}">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
