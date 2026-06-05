@extends('layouts.admin')

@section('title', 'Tambah Slider')

@section('content')
    <div class="container-lg px-0">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4 mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.slider.index') }}">Slider</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Tambah Slider Baru</h1>
            <a href="{{ route('admin.slider.index') }}" class="btn btn-secondary">
                <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-arrow-left') }}"></use></svg>
                Kembali
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.slider.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @include('admin.slider._form')

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-save') }}"></use></svg>
                            Simpan
                        </button>
                        <a href="{{ route('admin.slider.index') }}" class="btn btn-secondary ms-2">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
