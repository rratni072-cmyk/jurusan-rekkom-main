{{--
|--------------------------------------------------------------------------
| Komponen Sidebar Admin (dari CoreUI index.html baris 49-220)
|--------------------------------------------------------------------------
| Menggunakan SVG icon sprites persis seperti template asli.
| Menu disesuaikan untuk website Jurusan RK.
| Active state menggunakan routeIs() untuk highlight halaman aktif.
|--------------------------------------------------------------------------
--}}
<div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar">
    <div class="sidebar-header border-bottom">
        <a class="sidebar-brand d-flex align-items-center text-decoration-none gap-2"
           href="{{ route('admin.dashboard') }}"
           aria-label="Admin Jurusan Rekayasa Komputer Politani">
            {{-- Logo Politani (selalu tampil di kedua mode: expanded & narrow) --}}
            <img src="{{ asset('frontend/img/logo-politani.png') }}"
                 alt="Logo Politani"
                 width="32" height="32"
                 class="sidebar-brand-logo flex-shrink-0"
                 style="object-fit: contain; background: #fff; border-radius: 4px; padding: 2px;">

            {{-- Brand text (hilang saat sidebar narrow/unfoldable) --}}
            <span class="sidebar-brand-full d-flex flex-column lh-1">
                <strong style="font-size: 0.95rem;">Jurusan R&amp;K</strong>
                <small class="opacity-75" style="font-size: 0.7rem;">Admin Politani</small>
            </span>
        </a>
        <button class="btn-close d-lg-none" type="button" data-coreui-theme="dark"
            aria-label="Tutup sidebar" data-sidebar-toggle="#sidebar"></button>
    </div>

    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>

        {{-- Dashboard --}}
        <li class="nav-item">
            <a class="nav-link{{ request()->routeIs('admin.dashboard') ? ' active' : '' }}"
                href="{{ route('admin.dashboard') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-speedometer') }}"></use>
                </svg> Dashboard
            </a>
        </li>

        {{-- ====================================================================
             KELOLA KONTEN — urutan match navbar frontend:
             Beranda → Profil → Berita → Kemahasiswaan
             (Tridharma dikelola via Berita filter kategori, lihat shortcut di
              sub-menu Berita)
        ==================================================================== --}}
        <li class="nav-title">Kelola Konten</li>

        {{-- 1. Beranda — Slider Hero (standalone, hanya 1 item) --}}
        <li class="nav-item">
            <a class="nav-link{{ request()->routeIs('admin.slider.*') ? ' active' : '' }}"
                href="{{ route('admin.slider.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-image-plus') }}"></use>
                </svg> Slider Beranda
            </a>
        </li>

        {{-- 2. Profil Jurusan (nav-group dengan sub-menu) --}}
        <li class="nav-group{{ request()->routeIs('admin.profil.*') || request()->routeIs('admin.program-studi.*') ? ' show' : '' }}">
            <a class="nav-link nav-group-toggle" href="#">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-building') }}"></use>
                </svg> Profil Jurusan
            </a>
            <ul class="nav-group-items compact">
                <li class="nav-item">
                    <a class="nav-link{{ request()->routeIs('admin.profil.tentang.*') ? ' active' : '' }}"
                        href="{{ route('admin.profil.tentang.edit') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Tentang Jurusan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ request()->routeIs('admin.profil.visi-misi.*') ? ' active' : '' }}"
                        href="{{ route('admin.profil.visi-misi.edit') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Visi & Misi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ request()->routeIs('admin.profil.struktur.*') ? ' active' : '' }}"
                        href="{{ route('admin.profil.struktur.edit') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Struktur Organisasi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ request()->routeIs('admin.program-studi.*') ? ' active' : '' }}"
                        href="{{ route('admin.program-studi.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Program Studi
                    </a>
                </li>
            </ul>
        </li>

        {{-- 3. Kemahasiswaan (nav-group) --}}
        <li class="nav-group{{ request()->routeIs('admin.jadwal.*') || request()->routeIs('admin.pedoman.*') || request()->routeIs('admin.beasiswa.*') || request()->routeIs('admin.kegiatan.*') || request()->routeIs('admin.tipe-kegiatan.*') ? ' show' : '' }}">
            <a class="nav-link nav-group-toggle" href="#">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-education') }}"></use>
                </svg> Kemahasiswaan
            </a>
            <ul class="nav-group-items compact">
                <li class="nav-item">
                    <a class="nav-link{{ request()->routeIs('admin.jadwal.*') ? ' active' : '' }}"
                        href="{{ route('admin.jadwal.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Jadwal Perkuliahan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ request()->routeIs('admin.pedoman.*') ? ' active' : '' }}"
                        href="{{ route('admin.pedoman.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Pedoman
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ request()->routeIs('admin.beasiswa.*') ? ' active' : '' }}"
                        href="{{ route('admin.beasiswa.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Beasiswa
                    </a>
                </li>
                {{-- Kegiatan (nested nav-group: Semua Kegiatan + Tipe Kegiatan) --}}
                <li class="nav-group{{ request()->routeIs('admin.kegiatan.*') || request()->routeIs('admin.tipe-kegiatan.*') ? ' show' : '' }}">
                    <a class="nav-link nav-group-toggle" href="#">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Kegiatan
                    </a>
                    <ul class="nav-group-items compact">
                        <li class="nav-item">
                            <a class="nav-link{{ request()->routeIs('admin.kegiatan.*') ? ' active' : '' }}"
                                href="{{ route('admin.kegiatan.index') }}">
                                <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Semua Kegiatan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link{{ request()->routeIs('admin.tipe-kegiatan.*') ? ' active' : '' }}"
                                href="{{ route('admin.tipe-kegiatan.index') }}">
                                <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Tipe Kegiatan
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>

        {{-- 4. Tridharma (nav-group) — Pengajaran & Pengabdian.
             Penelitian tidak ada di admin (external link Buletin Poltanesa & Jurnal Tepian). --}}
        <li class="nav-group{{ request()->routeIs('admin.tridharma.*') ? ' show' : '' }}">
            <a class="nav-link nav-group-toggle" href="#">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-book') }}"></use>
                </svg> Tridharma
            </a>
            <ul class="nav-group-items compact">
                <li class="nav-item">
                    <a class="nav-link{{ request()->routeIs('admin.tridharma.*') && request()->route('type') === 'pengajaran' ? ' active' : '' }}"
                        href="{{ route('admin.tridharma.index', 'pengajaran') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Pengajaran
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ request()->routeIs('admin.tridharma.*') && request()->route('type') === 'pengabdian' ? ' active' : '' }}"
                        href="{{ route('admin.tridharma.index', 'pengabdian') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Pengabdian
                    </a>
                </li>
            </ul>
        </li>

        {{-- 5. Berita (nav-group)
             Diletakkan paling bawah karena konten harian — jadwal/pedoman/beasiswa/kegiatan
             di Kemahasiswaan & laporan akademik di Tridharma lebih sering diakses. --}}
        <li class="nav-group{{ request()->routeIs('admin.berita.*') || request()->routeIs('admin.kategori.*') ? ' show' : '' }}">
            <a class="nav-link nav-group-toggle" href="#">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-newspaper') }}"></use>
                </svg> Berita
            </a>
            <ul class="nav-group-items compact">
                <li class="nav-item">
                    <a class="nav-link{{ request()->routeIs('admin.berita.*') ? ' active' : '' }}"
                        href="{{ route('admin.berita.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Semua Berita
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ request()->routeIs('admin.kategori.*') ? ' active' : '' }}"
                        href="{{ route('admin.kategori.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Kategori
                    </a>
                </li>
            </ul>
        </li>

        {{-- ====================================================================
             HUBUNGI KAMI — match navbar frontend "Hubungi Kami"
             Berisi: Kontak Jurusan (info publik) + Pesan Masuk (inbox)
        ==================================================================== --}}
        <li class="nav-title">Hubungi Kami</li>

        {{-- Kontak Jurusan --}}
        <li class="nav-item">
            <a class="nav-link{{ request()->routeIs('admin.kontak.*') ? ' active' : '' }}"
                href="{{ route('admin.kontak.edit') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-phone') }}"></use>
                </svg> Kontak Jurusan
            </a>
        </li>

        {{-- Pesan Masuk (jumlah unread di-bind via AdminLayoutComposer) --}}
        <li class="nav-item">
            <a class="nav-link{{ request()->routeIs('admin.pesan.*') ? ' active' : '' }}"
                href="{{ route('admin.pesan.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-envelope-closed') }}"></use>
                </svg> Pesan Masuk
                @if(($pesanBelumDibaca ?? 0) > 0)
                    <span class="badge badge-sm bg-danger ms-auto">{{ $pesanBelumDibaca }}</span>
                @endif
            </a>
        </li>

        <li class="nav-divider"></li>

        {{-- Logout (form + button = no JS handler needed) --}}
        <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="d-grid">
                @csrf
                <button type="submit" class="nav-link nav-link-logout text-danger text-start border-0 bg-transparent">
                    <svg class="nav-icon" aria-hidden="true">
                        <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-account-logout') }}"></use>
                    </svg> Logout
                </button>
            </form>
        </li>

    </ul>

    <div class="sidebar-footer border-top d-none d-md-flex">
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
    </div>
</div>