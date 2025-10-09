@extends('layouts.app')
@section('title', 'Data Survei')
@section('content')
    <div class="flex-grow-1 container-p-y container-fluid">
        @include('layouts._alert')
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Daftar Survey</h2>
                    <a href="{{ route('survey.create') }}" class="btn btn-primary">
                        <span class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-plus icon-xs"></i> <span
                                class="d-none d-sm-inline-block">Tambah Survey </span></span>
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="card-datatable table-responsive pt-0">
                    <table class="table data-table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Survey</th>
                                <th class="text-center">Captio</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($survies as $survey)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $survey->name }}</td>
                                    <td>{{ $survey->caption }}</td>
                                    <td class="text-center">
                                        {{-- Logika untuk menampilkan Badge Status --}}
                                        @if ($survey->is_active)
                                            <span class="badge bg-success">
                                                <i class="menu-icon icon-base ti tabler-check me-1"></i> AKTIF
                                            </span>
                                        @else
                                            <span class="badge bg-label-secondary">
                                                <i class="menu-icon icon-base ti tabler-x me-1"></i> TIDAK AKTIF
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('survey.edit', $survey->id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <a type="button" onclick="openModal({{ $survey->id }})"
                                                class="btn btn-danger btn-sm text-white">Hapus</a>
                                        </div>
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
