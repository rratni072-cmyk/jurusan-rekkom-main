{{--
|--------------------------------------------------------------------------
| Komponen Topbar (Bar info kontak di atas navbar)
|--------------------------------------------------------------------------
| Topbar dengan email, telepon, dan social media icons.
| Data kontak ditarik dinamis dari database via ViewComposer.
| Mengikuti struktur Eterna template index.html baris 43-56.
|--------------------------------------------------------------------------
--}}
<header id="header" class="header sticky-top">
    <div class="topbar d-flex align-items-center dark-background">
        <div class="container d-flex justify-content-center justify-content-md-between">
            <div class="contact-info d-flex align-items-center">
                <i class="bi bi-envelope d-flex align-items-center">
                    <a href="mailto:{{ $kontak->email ?? 'rekkom@politani.ac.id' }}">{{ $kontak->email ?? 'rekkom@politani.ac.id' }}</a>
                </i>
                <i class="bi bi-phone d-flex align-items-center ms-4">
                    <span>{{ $kontak->telepon ?? '(0541) 260421' }}</span>
                </i>
            </div>
            <div class="social-links d-none d-md-flex align-items-center">
                @if($kontak->facebook ?? false)
                    <a href="{{ $kontak->facebook }}" target="_blank" class="facebook"><i class="bi bi-facebook"></i></a>
                @endif
                @if($kontak->instagram ?? false)
                    <a href="{{ $kontak->instagram }}" target="_blank" class="instagram"><i class="bi bi-instagram"></i></a>
                @endif
                @if($kontak->youtube ?? false)
                    <a href="{{ $kontak->youtube }}" target="_blank" class="youtube"><i class="bi bi-youtube"></i></a>
                @endif
                @if($kontak->tiktok ?? false)
                    <a href="{{ $kontak->tiktok }}" target="_blank" class="tiktok"><i class="bi bi-tiktok"></i></a>
                @endif
                @if($kontak->linkedin ?? false)
                    <a href="{{ $kontak->linkedin }}" target="_blank" class="linkedin"><i class="bi bi-linkedin"></i></a>
                @endif
            </div>
        </div>
    </div>