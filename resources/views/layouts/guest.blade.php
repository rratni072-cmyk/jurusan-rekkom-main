{{--
|--------------------------------------------------------------------------
| Layout Guest / Login (dari CoreUI login.html)
|--------------------------------------------------------------------------
| Halaman autentikasi mengikuti persis struktur login.html template CoreUI.
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
    <title>Login - Admin Jurusan RK</title>

    {{-- Favicon (logo Politani) --}}
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/logo-politani.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('frontend/img/logo-politani.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('frontend/img/logo-politani.png') }}">

    {{-- Vendors styles --}}
    <link rel="stylesheet" href="{{ asset('admin/css/vendors/simplebar.min.css') }}">

    {{-- CoreUI CSS --}}
    <link href="{{ asset('admin/css/coreui.min.css') }}" rel="stylesheet">

    {{-- Custom styles --}}
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">

    {{-- Config & Color Modes JS --}}
    <script src="{{ asset('admin/js/config.js') }}"></script>
    <script src="{{ asset('admin/js/color-modes.js') }}"></script>
</head>

<body>
    {{-- Container login (persis dari login.html template) --}}
    <div class="bg-body-tertiary min-vh-100 d-flex flex-row align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card-group d-block d-md-flex row">

                        {{-- Form login card --}}
                        <div class="card col-md-7 p-4 mb-0">
                            <div class="card-body">
                                {{ $slot }}
                            </div>
                        </div>

                        {{-- Info card (persis dari template) --}}
                        <div class="card col-md-5 text-white bg-primary py-5">
                            <div class="card-body text-center">
                                <div>
                                    <h2>Jurusan RK</h2>
                                    <p>Panel administrasi website Jurusan Rekayasa Komputer - Politeknik Pertanian
                                        Negeri Samarinda.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CoreUI JS --}}
    <script src="{{ asset('admin/js/coreui.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/js/simplebar.min.js') }}"></script>
</body>

</html>