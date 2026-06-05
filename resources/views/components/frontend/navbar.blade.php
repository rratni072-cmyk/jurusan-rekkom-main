{{--
|--------------------------------------------------------------------------
| Komponen Navbar (Navigasi utama)
|--------------------------------------------------------------------------
| Navbar sesuai 24 SS PBL lama:
| BERANDA | PROFIL ▾ | PROGRAM STUDI ▾ | KEMAHASISWAAN ▾ | TRIDHARMA ▾ | BERITA | HUBUNGI KAMI
|--------------------------------------------------------------------------
--}}
<div class="branding">
    <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="{{ route('home') }}" class="logo d-flex align-items-center">
            <img src="{{ asset('frontend/img/logo-politani.png') }}" alt="Logo Politani"
                style="height: 40px; margin-right: 8px;">
            <span class="sitename">REKAYASA DAN KOMPUTER</span>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                {{-- BERANDA --}}
                <li>
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                        BERANDA
                    </a>
                </li>

                {{-- PROFIL ▾ --}}
                <li class="dropdown">
                    <a href="#" class="{{ request()->routeIs('profil.*') ? 'active' : '' }}">
                        <span>PROFIL</span>
                        <i class="bi bi-chevron-down toggle-dropdown"></i>
                    </a>
                    <ul>
                        <li><a href="{{ route('profil.tentang') }}" class="{{ request()->routeIs('profil.tentang') ? 'active' : '' }}">Tentang Jurusan Rekayasa Dan Komputer</a></li>
                        <li><a href="{{ route('profil.visi-misi') }}" class="{{ request()->routeIs('profil.visi-misi') ? 'active' : '' }}">Visi dan Misi</a></li>
                        <li><a href="{{ route('profil.struktur') }}" class="{{ request()->routeIs('profil.struktur') ? 'active' : '' }}">Struktur Organisasi</a></li>
                        <li><a href="{{ route('profil.akreditasi') }}" class="{{ request()->routeIs('profil.akreditasi') ? 'active' : '' }}">Akreditasi Program Studi</a></li>
                    </ul>
                </li>

                {{-- PROGRAM STUDI ▾ (semua external links) --}}
                <li class="dropdown">
                    <a href="#">
                        <span>PROGRAM STUDI</span>
                        <i class="bi bi-chevron-down toggle-dropdown"></i>
                    </a>
                    <ul>
                        <li><a href="https://politanisamarinda.ac.id" target="_blank" rel="noopener noreferrer">D-III Teknologi Geomatika</a></li>
                        <li><a href="https://politanisamarinda.ac.id" target="_blank" rel="noopener noreferrer">D-III Sistem Informasi Akuntansi</a></li>
                        <li><a href="https://politanisamarinda.ac.id" target="_blank" rel="noopener noreferrer">D-IV Teknologi Rekayasa Geomatika Survei</a></li>
                        <li><a href="https://politanisamarinda.ac.id" target="_blank" rel="noopener noreferrer">D-IV Teknologi Rekayasa Perangkat Lunak</a></li>
                    </ul>
                </li>

                {{-- KEMAHASISWAAN ▾ (4 internal + 3 external) --}}
                <li class="dropdown">
                    <a href="#" class="{{ request()->routeIs('kemahasiswaan.*') ? 'active' : '' }}">
                        <span>KEMAHASISWAAN</span>
                        <i class="bi bi-chevron-down toggle-dropdown"></i>
                    </a>
                    <ul>
                        <li><a href="{{ route('kemahasiswaan.jadwal') }}" class="{{ request()->routeIs('kemahasiswaan.jadwal') ? 'active' : '' }}">Jadwal Perkuliahan</a></li>
                        <li><a href="{{ route('kemahasiswaan.pedoman') }}" class="{{ request()->routeIs('kemahasiswaan.pedoman') ? 'active' : '' }}">Pedoman</a></li>
                        <li><a href="{{ route('kemahasiswaan.beasiswa') }}" class="{{ request()->routeIs('kemahasiswaan.beasiswa') ? 'active' : '' }}">Beasiswa</a></li>
                        <li><a href="https://pmb.politanisamarinda.ac.id/" target="_blank" rel="noopener noreferrer">Penerimaan</a></li>
                        <li><a href="https://www.instagram.com/ikapolitani?igsh=ZXltOHpvcjF4ZGM0" target="_blank" rel="noopener noreferrer">Alumni</a></li>
                        <li><a href="https://repository.politanisamarinda.ac.id/" target="_blank" rel="noopener noreferrer">Repository</a></li>
                        <li><a href="{{ route('kemahasiswaan.kegiatan') }}" class="{{ request()->routeIs('kemahasiswaan.kegiatan') ? 'active' : '' }}">Kegiatan</a></li>
                    </ul>
                </li>

                {{-- TRIDHARMA ▾ (Penelitian punya sub-dropdown) --}}
                <li class="dropdown">
                    <a href="#" class="{{ request()->routeIs('tridharma.*') ? 'active' : '' }}">
                        <span>TRIDHARMA</span>
                        <i class="bi bi-chevron-down toggle-dropdown"></i>
                    </a>
                    <ul>
                        <li><a href="{{ route('tridharma.pengajaran') }}" class="{{ request()->routeIs('tridharma.pengajaran') ? 'active' : '' }}">Pengajaran</a></li>
                        <li class="dropdown">
                            <a href="#"><span>Penelitian</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                            <ul>
                                <li><a href="https://e-journal.politanisamarinda.ac.id/index.php/tanesa" target="_blank" rel="noopener noreferrer">Buletin Poltanesa</a></li>
                                <li><a href="https://e-journal.politanisamarinda.ac.id/index.php/tepian" target="_blank" rel="noopener noreferrer">Jurnal Tepian</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('tridharma.pengabdian') }}" class="{{ request()->routeIs('tridharma.pengabdian') ? 'active' : '' }}">Pengabdian Masyarakat</a></li>
                    </ul>
                </li>

                {{-- BERITA --}}
                <li>
                    <a href="{{ route('berita.index') }}" class="{{ request()->routeIs('berita.*') ? 'active' : '' }}">
                        BERITA
                    </a>
                </li>

                {{-- HUBUNGI KAMI --}}
                <li>
                    <a href="{{ route('kontak') }}" class="{{ request()->routeIs('kontak') ? 'active' : '' }}">
                        HUBUNGI KAMI
                    </a>
                </li>
            </ul>

            {{-- Offcanvas Trigger (Hamburger) — Mobile only --}}
            <button class="mobile-drawer-trigger d-xl-none"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#mobileMenuDrawer"
                    aria-controls="mobileMenuDrawer"
                    aria-label="Buka menu navigasi">
                <i class="bi bi-list" aria-hidden="true"></i>
            </button>
        </nav>
    </div>
</div>

{{-- ==================== MOBILE OFFCANVAS DRAWER ==================== --}}
<div class="offcanvas offcanvas-start mobile-drawer d-xl-none"
     tabindex="-1"
     id="mobileMenuDrawer"
     aria-labelledby="mobileMenuDrawerLabel">

    {{-- Drawer Header: Logo + Close --}}
    <div class="offcanvas-header drawer-header">
        <a href="{{ route('home') }}" class="drawer-brand" aria-label="Beranda Jurusan Rekayasa dan Komputer">
            <img src="{{ asset('frontend/img/logo-politani.png') }}" alt="" width="40" height="40" aria-hidden="true">
            <div class="drawer-brand-text">
                <strong id="mobileMenuDrawerLabel">Jurusan R&K</strong>
                <small>Politani Samarinda</small>
            </div>
        </a>
        <button type="button"
                class="btn-close drawer-close"
                data-bs-dismiss="offcanvas"
                aria-label="Tutup menu"></button>
    </div>

    {{-- Drawer Search --}}
    <div class="drawer-search">
        <form action="{{ route('berita.index') }}" method="GET" role="search">
            <label for="drawer-search-input" class="visually-hidden">Cari berita</label>
            <div class="drawer-search-wrapper">
                <i class="bi bi-search drawer-search-icon" aria-hidden="true"></i>
                <input type="search"
                       id="drawer-search-input"
                       name="q"
                       class="form-control drawer-search-input"
                       placeholder="Cari berita..."
                       autocomplete="off"
                       inputmode="search">
            </div>
        </form>
    </div>

    {{-- Drawer Menu Body --}}
    <div class="offcanvas-body drawer-body">
        <nav aria-label="Menu utama mobile">
            <ul class="drawer-menu list-unstyled">

                {{-- BERANDA --}}
                <li>
                    <a href="{{ route('home') }}" class="drawer-item {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="bi bi-house-door drawer-item-icon" aria-hidden="true"></i>
                        <span>Beranda</span>
                    </a>
                </li>

                {{-- PROFIL --}}
                <li class="drawer-dropdown">
                    <button type="button"
                            class="drawer-item drawer-toggle {{ request()->routeIs('profil.*') ? 'active' : '' }}"
                            data-drawer-toggle
                            data-drawer-target="#submenuProfil"
                            aria-expanded="{{ request()->routeIs('profil.*') ? 'true' : 'false' }}"
                            aria-controls="submenuProfil">
                        <i class="bi bi-person-circle drawer-item-icon" aria-hidden="true"></i>
                        <span>Profil</span>
                        <i class="bi bi-chevron-down drawer-toggle-icon" aria-hidden="true"></i>
                    </button>
                    <div class="drawer-submenu-wrap {{ request()->routeIs('profil.*') ? 'is-open' : '' }}" id="submenuProfil">
                        <ul class="drawer-submenu list-unstyled">
                            <li><a href="{{ route('profil.tentang') }}" class="drawer-subitem {{ request()->routeIs('profil.tentang') ? 'active' : '' }}">Tentang Jurusan</a></li>
                            <li><a href="{{ route('profil.visi-misi') }}" class="drawer-subitem {{ request()->routeIs('profil.visi-misi') ? 'active' : '' }}">Visi dan Misi</a></li>
                            <li><a href="{{ route('profil.struktur') }}" class="drawer-subitem {{ request()->routeIs('profil.struktur') ? 'active' : '' }}">Struktur Organisasi</a></li>
                            <li><a href="{{ route('profil.akreditasi') }}" class="drawer-subitem {{ request()->routeIs('profil.akreditasi') ? 'active' : '' }}">Akreditasi Program Studi</a></li>
                        </ul>
                    </div>
                </li>

                {{-- PROGRAM STUDI --}}
                <li class="drawer-dropdown">
                    <button type="button"
                            class="drawer-item drawer-toggle"
                            data-drawer-toggle
                            data-drawer-target="#submenuProdi"
                            aria-expanded="false"
                            aria-controls="submenuProdi">
                        <i class="bi bi-mortarboard drawer-item-icon" aria-hidden="true"></i>
                        <span>Program Studi</span>
                        <i class="bi bi-chevron-down drawer-toggle-icon" aria-hidden="true"></i>
                    </button>
                    <div class="drawer-submenu-wrap" id="submenuProdi">
                        <ul class="drawer-submenu list-unstyled">
                            <li><a href="https://politanisamarinda.ac.id" target="_blank" rel="noopener noreferrer" class="drawer-subitem">D-III Teknologi Geomatika <i class="bi bi-box-arrow-up-right ms-auto" aria-hidden="true"></i></a></li>
                            <li><a href="https://politanisamarinda.ac.id" target="_blank" rel="noopener noreferrer" class="drawer-subitem">D-III Sistem Informasi Akuntansi <i class="bi bi-box-arrow-up-right ms-auto" aria-hidden="true"></i></a></li>
                            <li><a href="https://politanisamarinda.ac.id" target="_blank" rel="noopener noreferrer" class="drawer-subitem">D-IV Teknologi Rekayasa Geomatika Survei <i class="bi bi-box-arrow-up-right ms-auto" aria-hidden="true"></i></a></li>
                            <li><a href="https://politanisamarinda.ac.id" target="_blank" rel="noopener noreferrer" class="drawer-subitem">D-IV Teknologi Rekayasa Perangkat Lunak <i class="bi bi-box-arrow-up-right ms-auto" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </li>

                {{-- KEMAHASISWAAN --}}
                <li class="drawer-dropdown">
                    <button type="button"
                            class="drawer-item drawer-toggle {{ request()->routeIs('kemahasiswaan.*') ? 'active' : '' }}"
                            data-drawer-toggle
                            data-drawer-target="#submenuKema"
                            aria-expanded="{{ request()->routeIs('kemahasiswaan.*') ? 'true' : 'false' }}"
                            aria-controls="submenuKema">
                        <i class="bi bi-people drawer-item-icon" aria-hidden="true"></i>
                        <span>Kemahasiswaan</span>
                        <i class="bi bi-chevron-down drawer-toggle-icon" aria-hidden="true"></i>
                    </button>
                    <div class="drawer-submenu-wrap {{ request()->routeIs('kemahasiswaan.*') ? 'is-open' : '' }}" id="submenuKema">
                        <ul class="drawer-submenu list-unstyled">
                            <li><a href="{{ route('kemahasiswaan.jadwal') }}" class="drawer-subitem {{ request()->routeIs('kemahasiswaan.jadwal') ? 'active' : '' }}">Jadwal Perkuliahan</a></li>
                            <li><a href="{{ route('kemahasiswaan.pedoman') }}" class="drawer-subitem {{ request()->routeIs('kemahasiswaan.pedoman') ? 'active' : '' }}">Pedoman</a></li>
                            <li><a href="{{ route('kemahasiswaan.beasiswa') }}" class="drawer-subitem {{ request()->routeIs('kemahasiswaan.beasiswa') ? 'active' : '' }}">Beasiswa</a></li>
                            <li><a href="https://pmb.politanisamarinda.ac.id/" target="_blank" rel="noopener noreferrer" class="drawer-subitem">Penerimaan <i class="bi bi-box-arrow-up-right ms-auto" aria-hidden="true"></i></a></li>
                            <li><a href="https://www.instagram.com/ikapolitani?igsh=ZXltOHpvcjF4ZGM0" target="_blank" rel="noopener noreferrer" class="drawer-subitem">Alumni <i class="bi bi-box-arrow-up-right ms-auto" aria-hidden="true"></i></a></li>
                            <li><a href="https://repository.politanisamarinda.ac.id/" target="_blank" rel="noopener noreferrer" class="drawer-subitem">Repository <i class="bi bi-box-arrow-up-right ms-auto" aria-hidden="true"></i></a></li>
                            <li><a href="{{ route('kemahasiswaan.kegiatan') }}" class="drawer-subitem {{ request()->routeIs('kemahasiswaan.kegiatan') ? 'active' : '' }}">Kegiatan</a></li>
                        </ul>
                    </div>
                </li>

                {{-- TRIDHARMA --}}
                <li class="drawer-dropdown">
                    <button type="button"
                            class="drawer-item drawer-toggle {{ request()->routeIs('tridharma.*') ? 'active' : '' }}"
                            data-drawer-toggle
                            data-drawer-target="#submenuTridharma"
                            aria-expanded="{{ request()->routeIs('tridharma.*') ? 'true' : 'false' }}"
                            aria-controls="submenuTridharma">
                        <i class="bi bi-book drawer-item-icon" aria-hidden="true"></i>
                        <span>Tridharma</span>
                        <i class="bi bi-chevron-down drawer-toggle-icon" aria-hidden="true"></i>
                    </button>
                    <div class="drawer-submenu-wrap {{ request()->routeIs('tridharma.*') ? 'is-open' : '' }}" id="submenuTridharma">
                        <ul class="drawer-submenu list-unstyled">
                            <li><a href="{{ route('tridharma.pengajaran') }}" class="drawer-subitem {{ request()->routeIs('tridharma.pengajaran') ? 'active' : '' }}">Pengajaran</a></li>
                            <li><a href="https://e-journal.politanisamarinda.ac.id/index.php/tanesa" target="_blank" rel="noopener noreferrer" class="drawer-subitem">Buletin Poltanesa <i class="bi bi-box-arrow-up-right ms-auto" aria-hidden="true"></i></a></li>
                            <li><a href="https://e-journal.politanisamarinda.ac.id/index.php/tepian" target="_blank" rel="noopener noreferrer" class="drawer-subitem">Jurnal Tepian <i class="bi bi-box-arrow-up-right ms-auto" aria-hidden="true"></i></a></li>
                            <li><a href="{{ route('tridharma.pengabdian') }}" class="drawer-subitem {{ request()->routeIs('tridharma.pengabdian') ? 'active' : '' }}">Pengabdian Masyarakat</a></li>
                        </ul>
                    </div>
                </li>

                {{-- BERITA --}}
                <li>
                    <a href="{{ route('berita.index') }}" class="drawer-item {{ request()->routeIs('berita.*') ? 'active' : '' }}">
                        <i class="bi bi-newspaper drawer-item-icon" aria-hidden="true"></i>
                        <span>Berita</span>
                    </a>
                </li>

                {{-- HUBUNGI KAMI --}}
                <li>
                    <a href="{{ route('kontak') }}" class="drawer-item {{ request()->routeIs('kontak') ? 'active' : '' }}">
                        <i class="bi bi-envelope-paper drawer-item-icon" aria-hidden="true"></i>
                        <span>Hubungi Kami</span>
                    </a>
                </li>

            </ul>
        </nav>
    </div>

    {{-- Drawer Footer: Info Kampus + Social --}}
    <div class="drawer-footer">
        <h6 class="drawer-footer-title">Info Kampus</h6>
        <ul class="drawer-contact-list list-unstyled">
            <li>
                <a href="mailto:{{ $kontak->email ?? 'rekkom@politani.ac.id' }}" class="drawer-contact">
                    <i class="bi bi-envelope" aria-hidden="true"></i>
                    <span>{{ $kontak->email ?? 'rekkom@politani.ac.id' }}</span>
                </a>
            </li>
            <li>
                <a href="tel:{{ str_replace([' ', '(', ')', '-'], '', $kontak->telepon ?? '0541260421') }}" class="drawer-contact">
                    <i class="bi bi-telephone" aria-hidden="true"></i>
                    <span>{{ $kontak->telepon ?? '(0541) 260421' }}</span>
                </a>
            </li>
            @if(($kontak->alamat ?? null))
            <li>
                <span class="drawer-contact">
                    <i class="bi bi-geo-alt" aria-hidden="true"></i>
                    <span>{{ $kontak->alamat }}</span>
                </span>
            </li>
            @endif
        </ul>

        <div class="drawer-social" aria-label="Sosial media">
            @if($kontak->facebook ?? false)
                <a href="{{ $kontak->facebook }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="bi bi-facebook" aria-hidden="true"></i></a>
            @endif
            @if($kontak->instagram ?? false)
                <a href="{{ $kontak->instagram }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="bi bi-instagram" aria-hidden="true"></i></a>
            @endif
            @if($kontak->youtube ?? false)
                <a href="{{ $kontak->youtube }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="bi bi-youtube" aria-hidden="true"></i></a>
            @endif
            @if($kontak->tiktok ?? false)
                <a href="{{ $kontak->tiktok }}" target="_blank" rel="noopener noreferrer" aria-label="TikTok"><i class="bi bi-tiktok" aria-hidden="true"></i></a>
            @endif
            @if($kontak->linkedin ?? false)
                <a href="{{ $kontak->linkedin }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="bi bi-linkedin" aria-hidden="true"></i></a>
            @endif
        </div>
    </div>
</div>
</header>