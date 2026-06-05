{{--
|--------------------------------------------------------------------------
| Halaman Register (dari CoreUI register.html)
|--------------------------------------------------------------------------
| Form registrasi mengikuti persis struktur register.html template CoreUI:
| single card layout, input-group dengan SVG icons.
| Validasi error ditampilkan sebagai CoreUI alert.
|--------------------------------------------------------------------------
--}}
<x-guest-layout>

    <h1>Register</h1>
    <p class="text-body-secondary">Buat akun baru</p>

    {{-- Validation Errors (CoreUI alert style) --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Nama (input-group dengan SVG icon seperti template) --}}
        <div class="input-group mb-3">
            <span class="input-group-text">
                <svg class="icon">
                    <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-user') }}"></use>
                </svg>
            </span>
            <input class="form-control @error('name') is-invalid @enderror"
                   type="text" name="name" value="{{ old('name') }}"
                   placeholder="Nama Lengkap" required autofocus autocomplete="name">
        </div>

        {{-- Email (input-group dengan SVG icon seperti template) --}}
        <div class="input-group mb-3">
            <span class="input-group-text">
                <svg class="icon">
                    <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-envelope-open') }}"></use>
                </svg>
            </span>
            <input class="form-control @error('email') is-invalid @enderror"
                   type="email" name="email" value="{{ old('email') }}"
                   placeholder="Email" required autocomplete="username">
        </div>

        {{-- Password (input-group dengan SVG icon seperti template) --}}
        <div class="input-group mb-3">
            <span class="input-group-text">
                <svg class="icon">
                    <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-lock-locked') }}"></use>
                </svg>
            </span>
            <input class="form-control @error('password') is-invalid @enderror"
                   type="password" name="password"
                   placeholder="Password" required autocomplete="new-password">
        </div>

        {{-- Konfirmasi Password (input-group dengan SVG icon seperti template) --}}
        <div class="input-group mb-4">
            <span class="input-group-text">
                <svg class="icon">
                    <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-lock-locked') }}"></use>
                </svg>
            </span>
            <input class="form-control @error('password_confirmation') is-invalid @enderror"
                   type="password" name="password_confirmation"
                   placeholder="Ulangi Password" required autocomplete="new-password">
        </div>

        {{-- Tombol register (btn-success persis dari template) --}}
        <button class="btn btn-block btn-success" type="submit">Buat Akun</button>

        {{-- Link ke halaman login --}}
        <div class="mt-3 text-center">
            <span class="text-body-secondary">Sudah punya akun?</span>
            <a href="{{ route('login') }}">Login</a>
        </div>
    </form>

</x-guest-layout>
