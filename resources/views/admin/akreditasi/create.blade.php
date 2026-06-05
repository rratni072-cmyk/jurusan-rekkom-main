@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Tambah Akreditasi</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.akreditasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Program Studi</label>
                    <input type="text" name="program_studi" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">No SK</label>
                    <input type="text" name="no_sk" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Peringkat</label>
                    <input type="text" name="peringkat" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Tahun SK</label>
                    <input type="text" name="tahun_sk" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Kedaluwarsa</label>
                    <input type="date" name="tanggal_kedaluwarsa" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">File Sertifikat (PDF)</label>
                    <input type="file" name="file_sertifikat" class="form-control" accept=".pdf">
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>

                <a href="{{ route('admin.akreditasi.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </form>
        </div>
    </div>
</div>
@endsection