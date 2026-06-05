@extends('layouts.frontend')
@section('title', 'Tentang Jurusan Rekayasa Dan Komputer')
@section('body_class', 'profil-page')
@section('meta_description', 'Profil lengkap Jurusan Rekayasa dan Komputer Politeknik Pertanian Negeri Samarinda: sejarah pendirian, identitas, dan komitmen pendidikan vokasi di bidang teknologi komputer.')

@section('content')

    {{-- Page Title --}}
    <div class="page-title" data-aos="fade">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1>Tentang Jurusan Rekayasa Dan Komputer</h1>
                        <p class="mb-0">Politeknik Pertanian Negeri Samarinda</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><span class="breadcrumb-disabled">Profil</span></li>
                    <li class="current">Tentang Jurusan</li>
                </ol>
            </div>
        </nav>
    </div>

    {{-- Content --}}
    <section class="section">
        <div class="container" data-aos="fade-up">
            <div class="row">
                {{-- Konten Utama (70%) --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                        @include('frontend.profil._cover-gambar', [
                            'profil' => $tentang ?? null,
                            'alt'    => 'Cover Tentang Jurusan Rekayasa dan Komputer',
                        ])
                        <div class="card-body p-4">
                            <h4 class="mb-2" style="color: var(--rk-primary); font-weight: 700;">
                                {{ $tentang->judul ?? 'Tentang Jurusan Rekayasa Dan Komputer' }}
                            </h4>
                            @include('frontend.profil._meta-bar', ['profil' => $tentang ?? null])
                            <div>
                                @if($tentang->nilai ?? false)
                                    {!! $tentang->nilai !!}
                                @else
                                    <div class="text-center py-5">
                                        <i class="bi bi-info-circle" style="font-size: 2rem; color: #adb5bd;"></i>
                                        <p class="text-muted mt-3 mb-2">Konten sedang dalam proses pembaruan.</p>
                                        <a href="{{ route('kontak') }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-envelope"></i> Hubungi Kami
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar (30%) --}}
                <div class="col-lg-4">
                    @include('components.frontend.sidebar-artikel')
                </div>
            </div>
        </div>
    </section>

@endsection
