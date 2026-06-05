@extends('layouts.admin')
@section('title', 'Edit Struktur Organisasi')
@section('content')
<div class="container-lg px-0">
    <nav aria-label="breadcrumb" class="mb-4 mt-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Struktur Organisasi</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Struktur Organisasi</h1>
    </div>

    <form action="{{ route('admin.profil.struktur.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9">
                        @include('admin.profil._judul-field', [
                            'kunci' => 'struktur_organisasi',
                            'item' => $profil,
                            'defaultJudul' => 'Struktur Organisasi',
                        ])

                        <div class="mb-3">
                            <label for="profil_struktur_organisasi_nilai" class="form-label">Konten <span class="text-danger">*</span></label>
                            <textarea class="form-control tinymce-editor"
                                      id="profil_struktur_organisasi_nilai"
                                      name="profil[struktur_organisasi][nilai]"
                                      rows="8">{{ old('profil.struktur_organisasi.nilai', $profil->nilai ?? '') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        @include('admin.profil._gambar-field', ['kunci' => 'struktur_organisasi', 'item' => $profil])
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <button type="submit" class="btn btn-primary">
                <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-save') }}"></use></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@include('components.admin.tinymce-init', [
    'selector'                => '.tinymce-editor',
    'height'                  => 350,
    'uploadUrl'               => route('admin.tinymce.upload-image'),
    'enableStrukturTemplate'  => true,
])
