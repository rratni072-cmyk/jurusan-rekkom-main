@extends('layouts.admin')
@section('title', 'Tambah Program Studi')
@section('content')
<div class="container-lg px-0">
    <nav aria-label="breadcrumb" class="mb-4 mt-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li><li class="breadcrumb-item"><a href="{{ route('admin.program-studi.index') }}">Program Studi</a></li><li class="breadcrumb-item active">Tambah</li></ol></nav>
    <div class="d-flex justify-content-between align-items-center mb-4"><h1 class="h3 mb-0">Tambah Program Studi</h1><a href="{{ route('admin.program-studi.index') }}" class="btn btn-secondary">← Kembali</a></div>
    <form action="{{ route('admin.program-studi.store') }}" method="POST" enctype="multipart/form-data">@csrf @include('admin.program-studi._form')
        <div class="mt-4 mb-4"><button type="submit" class="btn btn-primary">Simpan</button><a href="{{ route('admin.program-studi.index') }}" class="btn btn-secondary ms-2">Batal</a></div>
    </form>
</div>
@endsection
