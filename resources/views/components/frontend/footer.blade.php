{{--
|--------------------------------------------------------------------------
| Komponen Footer (Eterna template)
|--------------------------------------------------------------------------
| 4 kolom footer:
| 1. Rekayasa dan Komputer (alamat, telepon, email dari DB)
| 2. Tentang Jurusan (Visi&Misi, Struktur, Akreditasi, Hubungi Kami)
| 3. Web Prodi (4 nama prodi)
| 4. Follow Kami (social icons dari DB)
| Mengikuti Eterna index.html baris 365-443.
|--------------------------------------------------------------------------
--}}
<footer id="footer" class="footer position-relative dark-background">

    <div class="container footer-top">
        <div class="row gy-4">

            {{-- Kolom 1: Informasi Jurusan (Dinamis dari DB) --}}
            <div class="col-lg-4 col-md-6 footer-about">
                <a href="{{ route('home') }}" class="d-flex align-items-center">
                    <span class="sitename">Rekayasa dan Komputer</span>
                </a>
            <div class="footer-contact pt-3">
                    @if($kontak && $kontak->alamat)
                        <p>{{ $kontak->alamat }}</p>
                    @else
                        <p>Jalan Samratulangi, Sungai Keledang,</p>
                        <p>Kec. Samarinda Seberang,</p>
                        <p>Kota Samarinda, Kalimantan Timur 75131</p>
                    @endif
                    <p class="mt-3"><strong>No. Telepon:</strong> <span>{{ $kontak->telepon ?? '(0541) 260421' }}</span></p>
                    <p><strong>Email:</strong> <span>{{ $kontak->email ?? 'info@politanisamarinda.ac.id' }}</span></p>
                </div>
            </div>

            {{-- Kolom 2: Tentang Jurusan --}}
            <div class="col-lg-2 col-md-3 footer-links">
                <h4>Tentang Jurusan</h4>
                <ul>
                    <li><i class="bi bi-chevron-right"></i> <a href="{{ route('profil.visi-misi') }}">Visi&Misi</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="{{ route('profil.struktur') }}">Struktur
                            Organisasi</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="{{ route('profil.akreditasi') }}">Akreditasi</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="{{ route('kontak') }}">Hubungi Kami</a></li>
                </ul>
            </div>

            {{-- Kolom 3: Program Studi
                 Sementara diarahkan ke halaman lembaga jurusan di website Politani Samarinda
                 (induk) karena masing-masing prodi belum punya website dedicated. --}}
            @php
                $prodiExternalUrl = 'https://politanisamarinda.ac.id/detail-lembaga/jurusan-teknik-dan-informatika';
            @endphp
            <div class="col-lg-2 col-md-3 footer-links">
                <h4>Program Studi</h4>
                <ul>
                    @forelse($prodiList ?? [] as $prodi)
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="{{ $prodiExternalUrl }}" target="_blank" rel="noopener noreferrer">{{ $prodi->nama }}</a>
                        </li>
                    @empty
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="{{ $prodiExternalUrl }}" target="_blank" rel="noopener noreferrer">Lihat Semua Prodi</a>
                        </li>
                    @endforelse
                </ul>
            </div>

            {{-- Kolom 4: Follow Kami (Dinamis dari DB) --}}
            <div class="col-lg-4 col-md-12">
                <h4>Follow Kami</h4>
                <p>Follow Social Media Kami untuk Mendapatkan Update Informasi Terbaru Seputar Kampus.</p>
                <div class="social-links d-flex">
                    @if($kontak->facebook ?? false)
                        <a href="{{ $kontak->facebook }}" target="_blank"><i class="bi bi-facebook"></i></a>
                    @endif
                    @if($kontak->instagram ?? false)
                        <a href="{{ $kontak->instagram }}" target="_blank"><i class="bi bi-instagram"></i></a>
                    @endif
                    @if($kontak->youtube ?? false)
                        <a href="{{ $kontak->youtube }}" target="_blank"><i class="bi bi-youtube"></i></a>
                    @endif
                    @if($kontak->tiktok ?? false)
                        <a href="{{ $kontak->tiktok }}" target="_blank"><i class="bi bi-tiktok"></i></a>
                    @endif
                    @if($kontak->linkedin ?? false)
                        <a href="{{ $kontak->linkedin }}" target="_blank"><i class="bi bi-linkedin"></i></a>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <div class="container copyright text-center mt-4">
        <p>
            &copy; {{ date('Y') }}
            <strong class="px-1 sitename">Jurusan Rekayasa & Komputer</strong>
            <span>Politeknik Pertanian Negeri Samarinda</span>
        </p>
        <div class="credits">
            Dikembangkan oleh <strong>Kelompok 15 PBL</strong> — Jurusan Rekayasa dan Komputer
        </div>
    </div>

</footer>