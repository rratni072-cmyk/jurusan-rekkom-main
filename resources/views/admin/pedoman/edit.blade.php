@extends('layouts.admin')
@section('title', 'Edit Pedoman')
@section('content')
    <div class="container-lg px-0">
        <nav aria-label="breadcrumb" class="mb-4 mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.pedoman.index') }}">Pedoman</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Edit Pedoman</h1>
        </div>

        <form action="{{ route('admin.pedoman.update', $pedoman->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')
            @include('admin.pedoman._form')

            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="{{ route('admin.pedoman.index') }}" class="btn btn-secondary">
                    <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-x') }}"></use></svg>
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
