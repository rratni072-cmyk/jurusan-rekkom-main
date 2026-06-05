{{--
|--------------------------------------------------------------------------
| Halaman Profil Admin (CoreUI Layout)
|--------------------------------------------------------------------------
| Menggantikan default Breeze layout dengan CoreUI admin layout.
| Berisi: Avatar upload, Update profil, Update password, dan Hapus akun.
|--------------------------------------------------------------------------
--}}
@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('content')
<div class="container-lg px-0">
    <nav aria-label="breadcrumb" class="mb-4 mt-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Profil Saya</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Profil Saya</h1>
    </div>

    {{-- ===== Profile Header Card (Avatar + Info) ===== --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                {{-- Avatar Section --}}
                <div class="col-auto">
                    <div class="avatar avatar-xl">
                        @if($user->avatar)
                            <img class="avatar-img rounded-circle"
                                src="{{ asset('storage/' . $user->avatar) }}"
                                alt="{{ $user->name }}">
                        @else
                            <div class="avatar-img rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                style="width: 64px; height: 64px;">
                                <span class="text-white fw-bold fs-4">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                {{-- Info Section --}}
                <div class="col">
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="text-body-secondary mb-1">{{ $user->email }}</p>
                    <small class="text-body-tertiary">
                        <svg class="icon icon-sm me-1">
                            <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-calendar') }}"></use>
                        </svg>
                        Bergabung sejak @tanggal($user->created_at, 'd F Y')
                    </small>
                </div>
                {{-- Avatar Upload --}}
                <div class="col-auto">
                    <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data"
                        id="avatar-form">
                        @csrf
                        <label for="avatar-input" class="btn btn-outline-primary btn-sm"
                            data-coreui-toggle="tooltip" title="Upload Foto Profil">
                            <svg class="icon me-1">
                                <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-camera') }}"></use>
                            </svg>
                            Ubah Foto
                        </label>
                        <input type="file" id="avatar-input" name="avatar" class="d-none"
                            accept="image/jpeg,image/png,image/webp"
                            onchange="document.getElementById('avatar-form').submit()">
                    </form>
                    @if (session('status') === 'avatar-updated')
                        <small class="text-success d-block mt-1">
                            <svg class="icon icon-sm me-1">
                                <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-check-circle') }}"></use>
                            </svg>
                            Foto diperbarui
                        </small>
                    @endif
                    @error('avatar')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Update Profile Information ===== --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Informasi Profil</strong>
            <p class="text-body-secondary small mb-0 mt-1">Perbarui nama dan alamat email akun Anda.</p>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name', $user->name) }}"
                            required autofocus autocomplete="name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            id="email" name="email" value="{{ old('email', $user->email) }}"
                            required autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <svg class="icon me-1">
                            <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-save') }}"></use>
                        </svg>
                        Simpan Profil
                    </button>

                    @if (session('status') === 'profile-updated')
                        <span class="text-success ms-3">
                            <svg class="icon me-1">
                                <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-check-circle') }}"></use>
                            </svg>
                            Profil berhasil diperbarui.
                        </span>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- ===== Update Password ===== --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Ubah Password</strong>
            <p class="text-body-secondary small mb-0 mt-1">Gunakan password yang panjang dan acak untuk keamanan akun.</p>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="update_password_current_password" class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                            id="update_password_current_password" name="current_password"
                            autocomplete="current-password">
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="update_password_password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                            id="update_password_password" name="password"
                            autocomplete="new-password">
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="update_password_password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control"
                            id="update_password_password_confirmation" name="password_confirmation"
                            autocomplete="new-password">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-warning">
                        <svg class="icon me-1">
                            <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-lock-locked') }}"></use>
                        </svg>
                        Ubah Password
                    </button>

                    @if (session('status') === 'password-updated')
                        <span class="text-success ms-3">
                            <svg class="icon me-1">
                                <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-check-circle') }}"></use>
                            </svg>
                            Password berhasil diubah.
                        </span>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- ===== Delete Account ===== --}}
    <div class="card mb-4 border-danger">
        <div class="card-header bg-danger bg-opacity-10">
            <strong class="text-danger">Zona Berbahaya</strong>
            <p class="text-body-secondary small mb-0 mt-1">Setelah akun dihapus, semua data akan hilang secara permanen.</p>
        </div>
        <div class="card-body">
            <p class="text-body-secondary mb-3">
                Pastikan Anda telah menyimpan data penting sebelum menghapus akun. Tindakan ini tidak dapat dibatalkan.
            </p>
            <button type="button" class="btn btn-danger" data-coreui-toggle="modal" data-coreui-target="#deleteAccountModal">
                <svg class="icon me-1">
                    <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-trash') }}"></use>
                </svg>
                Hapus Akun
            </button>
        </div>
    </div>

</div>

{{-- Modal Konfirmasi Hapus Akun --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteAccountModalLabel">
                        <svg class="icon me-1">
                            <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-warning') }}"></use>
                        </svg>
                        Konfirmasi Hapus Akun
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus akun? Semua data akan hilang secara permanen.</p>
                    <p class="fw-semibold">Masukkan password Anda untuk konfirmasi:</p>
                    <div class="mb-3">
                        <input type="password"
                            class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                            name="password" placeholder="Password" required>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
