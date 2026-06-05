@extends('layouts.admin')
@section('title', 'Edit Jadwal')
@section('content')
    <div class="container-lg px-0">
        <nav aria-label="breadcrumb" class="mb-4 mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.jadwal.index') }}">Jadwal</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Edit Jadwal</h1>
            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">
                <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-arrow-left') }}"></use></svg> Kembali
            </a>
        </div>
        <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('admin.jadwal._form')
            {{-- Action footer: tombol secondary di kiri, primary di kanan
                 (modern convention; user terbiasa dari OS dialog & web app populer). --}}
            <div class="d-flex justify-content-end gap-2 mt-4 mb-4">
                <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-save') }}"></use></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
