{{--
|--------------------------------------------------------------------------
| Layout Admin Panel (Template CoreUI v5.3.0)
|--------------------------------------------------------------------------
| Layout utama admin panel, mengikuti persis struktur dari
| coreui_admin/src/views/index.html
|--------------------------------------------------------------------------
--}}
<!DOCTYPE html>
<html lang="id">

<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Admin Jurusan RK</title>

    {{-- Favicon (logo Politani) --}}
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/logo-politani.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('frontend/img/logo-politani.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('frontend/img/logo-politani.png') }}">

    {{-- Vendors styles --}}
    <link rel="stylesheet" href="{{ asset('admin/css/vendors/simplebar.min.css') }}">

    {{-- CoreUI CSS --}}
    <link href="{{ asset('admin/css/coreui.min.css') }}" rel="stylesheet">

    {{-- Custom styles (dari style.scss template) --}}
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">

    {{-- Mobile responsive & a11y enhancement --}}
    <link href="{{ asset('admin/css/admin-mobile.css') }}" rel="stylesheet">

    {{-- Bootstrap Icons (dipakai oleh form admin untuk ikon kontekstual) --}}
    <link href="{{ asset('frontend/vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">

    {{-- Config & Color Modes JS (harus di head seperti template asli) --}}
    <script src="{{ asset('admin/js/config.js') }}"></script>
    <script src="{{ asset('admin/js/color-modes.js') }}"></script>

    @stack('styles')
    @yield('styles')
</head>

<body>

    {{-- Skip-to-content (a11y: keyboard user bisa lompat ke konten utama) --}}
    <a href="#main-content" class="skip-to-content">Lewati ke konten utama</a>

    {{-- ==================== SIDEBAR ==================== --}}
    @include('components.admin.sidebar')

    {{-- ==================== WRAPPER UTAMA ==================== --}}
    <div class="wrapper d-flex flex-column min-vh-100">

        {{-- Header --}}
        @include('components.admin.header')

        {{-- Konten Utama --}}
        <main class="body flex-grow-1" id="main-content" role="main">
            <div class="container-lg px-4">

                {{-- Flash Messages: ditampilkan via SweetAlert2 toast di akhir body --}}

                @yield('content')

            </div>
        </main>

        {{-- Footer Admin Jurusan R&K --}}
        <footer class="footer px-4">
            <div>
                &copy; {{ date('Y') }} <strong>Jurusan Rekayasa Komputer</strong> &mdash;
                Politeknik Pertanian Negeri Samarinda.
            </div>
            <div class="ms-auto">
                <small class="text-body-secondary">Powered by
                    <a href="https://coreui.io" target="_blank" rel="noopener">CoreUI</a>
                    &amp;
                    <a href="https://laravel.com" target="_blank" rel="noopener">Laravel</a>
                </small>
            </div>
        </footer>

    </div>

    {{-- CoreUI and necessary plugins (persis dari template) --}}
    <script src="{{ asset('admin/js/coreui.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/js/simplebar.min.js') }}"></script>
    <script>
        const header = document.querySelector('header.header');

        document.addEventListener('scroll', () => {
            if (header) {
                header.classList.toggle('shadow-sm', document.documentElement.scrollTop > 0);
            }
        });

        // Inisialisasi semua tooltips (CoreUI/Bootstrap)
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-coreui-toggle="tooltip"]'));
            tooltipTriggerList.map(function(el) {
                return new coreui.Tooltip(el);
            });

            // Sidebar toggle (event delegation, ganti inline onclick)
            document.addEventListener('click', function (e) {
                const trigger = e.target.closest('[data-sidebar-toggle]');
                if (!trigger) return;
                const target = document.querySelector(trigger.getAttribute('data-sidebar-toggle'));
                if (!target) return;
                const instance = coreui.Sidebar.getInstance(target) || new coreui.Sidebar(target);
                instance.toggle();
            });
        });
    </script>

    {{-- jQuery (dependency DataTables.js) --}}
    <script src="{{ asset('admin/vendor/jquery/jquery-3.7.1.min.js') }}"></script>

    {{-- SweetAlert2 (jQuery-free) --}}
    <script src="{{ asset('admin/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>

    {{-- CSRF Token untuk AJAX --}}
    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
    </script>

    {{-- ==================== SWEETALERT2 TOAST FLASH ==================== --}}
    {{-- Menampilkan session flash (success/error/warning/info) sebagai
         toast non-intrusive di pojok kanan atas. --}}
    <script>
        (function () {
            if (typeof Swal === 'undefined') return;

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: function (toast) {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            // Expose ke window agar bisa dipakai modul lain (misal AJAX success).
            window.AdminToast = Toast;

            @if(session('success'))
                Toast.fire({ icon: 'success', title: @json(session('success')) });
            @endif

            @if(session('error'))
                Toast.fire({ icon: 'error', title: @json(session('error')) });
            @endif

            @if(session('warning'))
                Toast.fire({ icon: 'warning', title: @json(session('warning')) });
            @endif

            @if(session('info'))
                Toast.fire({ icon: 'info', title: @json(session('info')) });
            @endif

            @if($errors->any())
                {{-- Tampilkan pesan error spesifik dalam modal (lebih jelas dari toast generik).
                     Berguna terutama untuk error upload (mis. "Ukuran gambar maksimal 5MB"). --}}
                Swal.fire({
                    icon: 'error',
                    title: 'Form belum bisa disimpan',
                    html: '<div class="text-start"><p class="mb-2">Ada {{ $errors->count() }} kesalahan input yang perlu diperbaiki:</p>' +
                          '<ul class="text-start mb-0" style="padding-left: 1.25rem;">' +
                          @json(collect($errors->all())->map(fn($m) => '<li class="mb-1">' . e($m) . '</li>')->implode('')) +
                          '</ul></div>',
                    confirmButtonColor: '#321fdb',
                    confirmButtonText: 'Mengerti',
                    width: '32rem',
                });
            @endif
        })();
    </script>

    @stack('scripts')
    @yield('scripts')

</body>

</html>