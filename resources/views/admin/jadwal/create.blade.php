@extends('layouts.admin')
@section('title', 'Tambah Jadwal')
@section('content')
    <div class="container-lg px-0">
        <nav aria-label="breadcrumb" class="mb-4 mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.jadwal.index') }}">Jadwal</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Tambah Jadwal Baru</h1>
            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">
                <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-arrow-left') }}"></use></svg> Kembali
            </a>
        </div>
        <form action="{{ route('admin.jadwal.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.jadwal._form')
            {{-- Action footer: tombol secondary di kiri, primary di kanan
                 (modern convention; user terbiasa dari OS dialog & web app populer). --}}
            <div class="d-flex justify-content-end gap-2 mt-4 mb-4">
                <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-save') }}"></use></svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
