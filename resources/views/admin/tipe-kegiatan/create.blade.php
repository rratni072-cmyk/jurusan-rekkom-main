@extends('layouts.admin')

@section('title', 'Tambah Tipe Kegiatan')

@section('content')
    <div class="container-lg px-0">
        <nav aria-label="breadcrumb" class="mb-4 mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tipe-kegiatan.index') }}">Tipe Kegiatan</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Tambah Tipe Kegiatan</h1>
            <a href="{{ route('admin.tipe-kegiatan.index') }}" class="btn btn-secondary">
                <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-arrow-left') }}"></use></svg>
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.tipe-kegiatan.store') }}" method="POST">
            @csrf
            @include('admin.tipe-kegiatan._form')

            <div class="mt-4 mb-4">
                <button type="submit" class="btn btn-primary">
                    <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-save') }}"></use></svg>
                    Simpan
                </button>
                <a href="{{ route('admin.tipe-kegiatan.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
@endsection
