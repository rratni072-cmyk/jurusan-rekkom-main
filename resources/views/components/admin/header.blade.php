{{--
|--------------------------------------------------------------------------
| Komponen Header Admin (CoreUI v5.3.0)
|--------------------------------------------------------------------------
| Header fungsional dengan notification dropdown, message badge,
| theme switcher, dan avatar dropdown menu.
|--------------------------------------------------------------------------
--}}
<header class="header header-sticky p-0 mb-4">
    <div class="container-fluid border-bottom px-4">

        {{-- Toggle sidebar button --}}
        <button class="header-toggler" type="button"
            data-sidebar-toggle="#sidebar"
            style="margin-inline-start: -14px;"
            aria-label="Buka/tutup menu sidebar"
            aria-controls="sidebar">
            <svg class="icon icon-lg" aria-hidden="true">
                <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-menu') }}"></use>
            </svg>
        </button>

        {{-- Header nav links --}}
        <ul class="header-nav d-none d-lg-flex">
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        </ul>

        {{-- ===== Notification Icons (Fungsional) ===== --}}
        <ul class="header-nav ms-auto">

            {{-- 🔔 Bell: Notifikasi Ringkas --}}
            <li class="nav-item dropdown">
                <a class="nav-link position-relative" data-coreui-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false"
                    aria-label="Notifikasi{{ ($pesanBelumDibaca ?? 0) > 0 ? ' (' . ($pesanBelumDibaca > 9 ? '9+' : $pesanBelumDibaca) . ' baru)' : '' }}">
                    <svg class="icon icon-lg" aria-hidden="true">
                        <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-bell') }}"></use>
                    </svg>
                    @php $pesanBelumDibaca = $pesanBelumDibaca ?? 0; @endphp
                    @if($pesanBelumDibaca > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="font-size: 0.6rem;">
                            {{ $pesanBelumDibaca > 9 ? '9+' : $pesanBelumDibaca }}
                        </span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-end pt-0" style="min-width: 280px;">
                    <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2">
                        Notifikasi
                    </div>
                    @if($pesanBelumDibaca > 0)
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.pesan.index') }}">
                            <svg class="icon me-2 text-danger">
                                <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-envelope-letter') }}"></use>
                            </svg>
                            <div>
                                <div class="fw-semibold">{{ $pesanBelumDibaca }} Pesan Baru</div>
                                <small class="text-body-secondary">Lihat pesan masuk</small>
                            </div>
                        </a>
                    @else
                        <div class="dropdown-item text-body-secondary text-center py-3">
                            <svg class="icon icon-lg mb-1 text-body-tertiary">
                                <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-bell') }}"></use>
                            </svg>
                            <div class="small">Tidak ada notifikasi baru</div>
                        </div>
                    @endif
                </div>
            </li>

            {{-- 📋 List: Shortcut Menu --}}
            <li class="nav-item dropdown">
                <a class="nav-link" data-coreui-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false"
                    aria-label="Pintasan menu cepat">
                    <svg class="icon icon-lg" aria-hidden="true">
                        <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-list-rich') }}"></use>
                    </svg>
                </a>
                <div class="dropdown-menu dropdown-menu-end pt-0" style="min-width: 240px;">
                    <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2">
                        Pintasan Menu
                    </div>
                    <a class="dropdown-item" href="{{ route('admin.berita.create') }}">
                        <svg class="icon me-2 text-primary">
                            <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-plus') }}"></use>
                        </svg> Tambah Berita
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.kegiatan.create') }}">
                        <svg class="icon me-2 text-warning">
                            <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-star') }}"></use>
                        </svg> Tambah Kegiatan
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.slider.create') }}">
                        <svg class="icon me-2 text-info">
                            <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-image-plus') }}"></use>
                        </svg> Tambah Slider
                    </a>

                    {{-- Tridharma — menu terpisah (Penelitian = external link, tidak di admin) --}}
                    <div class="dropdown-divider"></div>
                    <h6 class="dropdown-header text-body-secondary fw-semibold small mb-1 px-3">Tridharma</h6>
                    <a class="dropdown-item" href="{{ route('admin.tridharma.create', 'pengajaran') }}">
                        <svg class="icon me-2 text-primary">
                            <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-book') }}"></use>
                        </svg> Tambah Pengajaran
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.tridharma.create', 'pengabdian') }}">
                        <svg class="icon me-2 text-success">
                            <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-people') }}"></use>
                        </svg> Tambah Pengabdian
                    </a>

                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('admin.profil.tentang.edit') }}">
                        <svg class="icon me-2 text-success">
                            <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-building') }}"></use>
                        </svg> Edit Profil Jurusan
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.kontak.edit') }}">
                        <svg class="icon me-2 text-secondary">
                            <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-phone') }}"></use>
                        </svg> Edit Kontak
                    </a>
                </div>
            </li>

            {{-- ✉️ Envelope: Link ke Pesan Masuk --}}
            <li class="nav-item">
                <a class="nav-link position-relative" href="{{ route('admin.pesan.index') }}"
                    aria-label="Pesan masuk{{ ($pesanBelumDibaca ?? 0) > 0 ? ' (' . ($pesanBelumDibaca > 9 ? '9+' : $pesanBelumDibaca) . ' belum dibaca)' : '' }}">
                    <svg class="icon icon-lg" aria-hidden="true">
                        <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-envelope-open') }}"></use>
                    </svg>
                    @if($pesanBelumDibaca > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info"
                            style="font-size: 0.6rem;">
                            {{ $pesanBelumDibaca > 9 ? '9+' : $pesanBelumDibaca }}
                        </span>
                    @endif
                </a>
            </li>
        </ul>

        <ul class="header-nav">
            <li class="nav-item py-1">
                <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>

            {{-- 🌙 Theme Switcher (Light / Dark / Auto) --}}
            <li class="nav-item dropdown">
                <button class="btn btn-link nav-link py-2 px-2 d-flex align-items-center" type="button"
                    aria-expanded="false" data-coreui-toggle="dropdown"
                    aria-label="Ubah tema (terang/gelap)">
                    <svg class="icon icon-lg theme-icon-active" aria-hidden="true">
                        <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-contrast') }}"></use>
                    </svg>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem;">
                    <li>
                        <button class="dropdown-item d-flex align-items-center" type="button"
                            data-coreui-theme-value="light">
                            <svg class="icon icon-lg me-3">
                                <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-sun') }}"></use>
                            </svg>Light
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item d-flex align-items-center" type="button"
                            data-coreui-theme-value="dark">
                            <svg class="icon icon-lg me-3">
                                <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-moon') }}"></use>
                            </svg>Dark
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item d-flex align-items-center active" type="button"
                            data-coreui-theme-value="auto">
                            <svg class="icon icon-lg me-3">
                                <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-contrast') }}"></use>
                            </svg>Auto
                        </button>
                    </li>
                </ul>
            </li>

            <li class="nav-item py-1">
                <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>

            {{-- 👤 Avatar Dropdown --}}
            <li class="nav-item dropdown">
                <a class="nav-link py-0 pe-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                    aria-expanded="false"
                    aria-label="Menu pengguna {{ Auth::user()->name ?? 'admin' }}">
                    <div class="avatar avatar-md">
                        @if(Auth::user()->avatar)
                            <img class="avatar-img" src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                alt="{{ Auth::user()->name ?? 'Admin' }}">
                        @else
                            <div class="avatar-img bg-primary d-flex align-items-center justify-content-center"
                                style="width: 36px; height: 36px; border-radius: 50%;">
                                <span class="text-white fw-bold" style="font-size: 0.85rem;">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2">
                        <div>{{ Auth::user()->name ?? 'Admin' }}</div>
                        <small class="fw-normal text-body-tertiary">{{ Auth::user()->email ?? '' }}</small>
                    </div>

                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-user') }}"></use>
                        </svg> Profil Saya
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.kontak.edit') }}">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-settings') }}"></use>
                        </svg> Pengaturan Kontak
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.pesan.index') }}">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-envelope-closed') }}"></use>
                        </svg> Pesan Masuk
                        @if($pesanBelumDibaca > 0)
                            <span class="badge bg-info ms-auto">{{ $pesanBelumDibaca }}</span>
                        @endif
                    </a>

                    <div class="dropdown-divider"></div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <svg class="icon me-2" aria-hidden="true">
                                <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-account-logout') }}"></use>
                            </svg> Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>

    </div>

</header>