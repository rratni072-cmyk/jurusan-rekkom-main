{{--
|--------------------------------------------------------------------------
| Layout Frontend (Template Eterna - BootstrapMade)
|--------------------------------------------------------------------------
| Layout utama untuk halaman publik website Jurusan Rekayasa Komputer.
| Menggunakan template Eterna dengan Bootstrap 5.3.
|
| Sections yang tersedia:
| - @section('title') : Judul halaman
| - @section('meta') : Meta tags tambahan
| - @section('styles') : CSS tambahan per halaman
| - @section('content') : Konten utama halaman
| - @section('scripts') : JavaScript tambahan per halaman
|--------------------------------------------------------------------------
--}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Judul dinamis per halaman --}}
    <title>@yield('title', 'Jurusan Rekayasa Komputer') - Politani Samarinda</title>

    {{-- Meta SEO --}}
    <meta name="description"
        content="@yield('meta_description', 'Website resmi Jurusan Rekayasa Komputer - Politeknik Pertanian Negeri Samarinda')">
    <meta name="keywords"
        content="@yield('meta_keywords', 'Rekayasa Komputer, Politani Samarinda, Politeknik Pertanian')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Social Media --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Jurusan Rekayasa Komputer') - Politani Samarinda">
    <meta property="og:description" content="@yield('meta_description', 'Website resmi Jurusan Rekayasa Komputer - Politeknik Pertanian Negeri Samarinda')">
    <meta property="og:image" content="@yield('og_image', asset('frontend/img/logo-politani.png'))">

    @yield('meta')

    {{-- Favicons (logo Politani) --}}
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/logo-politani.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('frontend/img/logo-politani.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('frontend/img/logo-politani.png') }}">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Raleway:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- Vendor CSS --}}
    <link href="{{ asset('frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">

    {{-- Main CSS Eterna --}}
    <link href="{{ asset('frontend/css/main.css') }}" rel="stylesheet">

    {{-- Custom CSS Jurusan RK --}}
    <link href="{{ asset('frontend/css/custom.css') }}" rel="stylesheet">

    {{-- CSS tambahan per halaman.
         Dual support: pakai @section('styles')...@endsection ATAU @push('styles')...@endpush
         (ikuti rules .agents/rules/blade-components.md — preferensi @push untuk multi-source). --}}
    @stack('styles')
    @yield('styles')
</head>

<body class="@yield('body_class', 'index-page')">

    {{-- ==================== HEADER ==================== --}}
    @include('components.frontend.topbar')
    @include('components.frontend.navbar')

    {{-- ==================== KONTEN UTAMA ==================== --}}
    <main class="main">
        @yield('content')
    </main>

    {{-- ==================== FOOTER ==================== --}}
    @include('components.frontend.footer')

    {{-- Toast Flash Message (success/error) — render kalau ada session flash --}}
    @include('partials._toast-flash')

    {{-- Scroll Top Button --}}
    <a href="#" id="scroll-top"
       class="scroll-top d-flex align-items-center justify-content-center"
       role="button"
       aria-label="Kembali ke atas halaman"
       title="Kembali ke atas">
        <i class="bi bi-arrow-up-short" aria-hidden="true"></i>
    </a>

    {{-- Custom smooth scroll + drawer body class hook --}}
    <script>
        (function() {
            // === Smooth scroll-to-top (custom requestAnimationFrame) ===
            function easeInOutCubic(t) {
                return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
            }

            function smoothScrollToTop(duration) {
                const startY = window.scrollY || window.pageYOffset;
                if (startY === 0) return;
                const startTime = performance.now();
                duration = duration || 600;

                function animate(now) {
                    const elapsed = now - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const eased = easeInOutCubic(progress);
                    window.scrollTo(0, Math.round(startY * (1 - eased)));
                    if (progress < 1) {
                        requestAnimationFrame(animate);
                    }
                }
                requestAnimationFrame(animate);
            }

            document.addEventListener('click', function(e) {
                const btn = e.target.closest('#scroll-top');
                if (!btn) return;
                e.preventDefault();
                e.stopImmediatePropagation();
                smoothScrollToTop(600);
            }, true);

            // === Drawer body class hook (Bootstrap offcanvas events) ===
            // Toggle body.drawer-open saat drawer open/close
            // Lebih cepat dari CSS :has() selector
            document.addEventListener('show.bs.offcanvas', function(e) {
                if (e.target.id === 'mobileMenuDrawer') {
                    document.body.classList.add('drawer-open');
                }
            });

            document.addEventListener('hidden.bs.offcanvas', function(e) {
                if (e.target.id === 'mobileMenuDrawer') {
                    document.body.classList.remove('drawer-open');
                }
            });

            // === Drawer submenu accordion (CSS Grid grid-template-rows) ===
            // Pengganti Bootstrap collapse — animasi grid-template-rows jauh lebih
            // ringan vs height transition (lihat .agents/rules/performance-css.md).
            // Single-open behavior: membuka satu submenu menutup yang lain.
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('[data-drawer-toggle]');
                if (!btn) return;
                e.preventDefault();
                const targetSel = btn.getAttribute('data-drawer-target');
                const target = targetSel ? document.querySelector(targetSel) : null;
                if (!target) return;

                const willOpen = !target.classList.contains('is-open');

                // Tutup submenu lain dalam drawer yang sama (single-open accordion)
                const drawer = btn.closest('.mobile-drawer') || document;
                drawer.querySelectorAll('.drawer-submenu-wrap.is-open').forEach(function(el) {
                    if (el !== target) {
                        el.classList.remove('is-open');
                        const otherBtn = drawer.querySelector('[data-drawer-target="#' + el.id + '"]');
                        if (otherBtn) otherBtn.setAttribute('aria-expanded', 'false');
                    }
                });

                target.classList.toggle('is-open', willOpen);
                btn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            });
        })();
    </script>

    {{-- Preloader --}}
    <div id="preloader"></div>

    {{-- Vendor JS (defer = download paralel tanpa block render, execute in order) --}}
    {{-- NOTE: validate.js BootstrapMade SENGAJA TIDAK di-load. Script tersebut --}}
    {{-- intercept submit class .php-email-form via AJAX dan expect response "OK" --}}
    {{-- string ala PHPMailer legacy. Laravel handle submit native (redirect + flash --}}
    {{-- session) yang lebih reliable. Class CSS .php-email-form tetap dipakai --}}
    {{-- HANYA untuk styling (bg + shadow) di /kontak. --}}
    <script src="{{ asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('frontend/vendor/aos/aos.js') }}" defer></script>
    <script src="{{ asset('frontend/vendor/swiper/swiper-bundle.min.js') }}" defer></script>
    <script src="{{ asset('frontend/vendor/purecounter/purecounter_vanilla.js') }}" defer></script>
    <script src="{{ asset('frontend/vendor/waypoints/noframework.waypoints.js') }}" defer></script>
    <script src="{{ asset('frontend/vendor/glightbox/js/glightbox.min.js') }}" defer></script>
    <script src="{{ asset('frontend/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}" defer></script>
    <script src="{{ asset('frontend/vendor/isotope-layout/isotope.pkgd.min.js') }}" defer></script>

    {{-- Main JS Eterna (defer agar tidak block render) --}}
    <script src="{{ asset('frontend/js/main.js') }}" defer></script>

    {{-- Custom frontend interactions --}}
    <script src="{{ asset('frontend/js/visited-articles.js') }}" defer></script>

    {{-- JavaScript tambahan per halaman.
         Dual support: @section('scripts')...@endsection ATAU @push('scripts')...@endpush --}}
    @stack('scripts')
    @yield('scripts')

</body>

</html>
