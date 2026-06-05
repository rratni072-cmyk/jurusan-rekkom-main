@extends('layouts.frontend')
@section('title', 'Akreditasi Program Studi')
@section('body_class', 'profil-page')
@section('meta_description', 'Status akreditasi 4 program studi Jurusan Rekayasa dan Komputer Politeknik Pertanian Negeri Samarinda: D3 TG, D3 SIA, D4 TRPL, dan D4 TRGS dengan akreditasi B & Baik Sekali.')

@section('content')

    {{-- Page Title --}}
    <div class="page-title" data-aos="fade">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1>Akreditasi Program Studi</h1>
                        <p class="mb-0">Jurusan Rekayasa dan Komputer</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><span class="breadcrumb-disabled">Profil</span></li>
                    <li class="current">Akreditasi Program Studi</li>
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
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h4 class="mb-2" style="color: var(--rk-primary); font-weight: 700;">
                                <i class="bi bi-award me-2"></i>Akreditasi Program Studi
                            </h4>
                            <p class="text-muted small mb-4">Dokumen Akreditasi Program Studi yang ada di Jurusan Rekayasa dan Komputer</p>

                            {{-- Desktop/tablet (≥768px): tabel standar. Mobile-nya pakai card list di bawah --}}
                            <div class="table-responsive table-styled-wrapper d-none d-md-block">
                                <table class="table table-bordered" id="akreditasi-table">
                                    <thead>
                                        <tr>
                                            <th>Program Studi</th>
                                            <th>No. SK</th>
                                            <th>Peringkat</th>
                                            <th>Tahun SK</th>
                                            <th>Tanggal Kedaluwarsa</th>
                                            <th>Status</th>
                                            <th>Tautan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($programStudi as $prodi)
                                            @php
                                                $status = $prodi->getStatusKedaluwarsa();
                                                $verifikasiLabel = $prodi->getVerifikasiLabel();
                                            @endphp
                                            <tr id="prodi-{{ \Illuminate\Support\Str::slug($prodi->nama) }}" class="akreditasi-row-anchor">
                                                <td>{{ $prodi->jenjang ?? '' }} – {{ $prodi->nama }}</td>
                                                <td class="small">{{ $prodi->no_sk ?? '-' }}</td>
                                                <td class="text-center fw-semibold">
                                                    {{ $prodi->akreditasi ?? '-' }}
                                                </td>
                                                <td class="text-center">{{ $prodi->tahun_sk ?? '-' }}</td>
                                                <td class="text-center">
                                                    @tanggal($prodi->tanggal_kedaluwarsa, 'd-m-Y')
                                                </td>
                                                <td class="text-center">
                                                    @if($status)
                                                        <span class="badge rounded-pill bg-{{ $status['color'] }}-subtle text-{{ $status['color'] }}-emphasis border border-{{ $status['color'] }}-subtle">
                                                            <i class="bi bi-{{ $status['color'] === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill' }} me-1"></i>{{ $status['label'] }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="akreditasi-tautan">
                                                    @if($prodi->sertifikat)
                                                        <a href="{{ asset('storage/' . $prodi->sertifikat) }}"
                                                           target="_blank"
                                                           rel="noopener"
                                                           class="akreditasi-link akreditasi-link--sertifikat"
                                                           title="Unduh sertifikat">
                                                            <i class="bi bi-file-earmark-pdf"></i>
                                                            <span>Sertifikat</span>
                                                        </a>
                                                    @endif
                                                    @if($verifikasiLabel)
                                                        <a href="{{ $prodi->verifikasi_url }}"
                                                           target="_blank"
                                                           rel="noopener"
                                                           class="akreditasi-link akreditasi-link--verifikasi"
                                                           title="Data resmi pemerintah di {{ $verifikasiLabel }} — selalu update, dapat dipercaya">
                                                            <i class="bi bi-patch-check-fill"></i>
                                                            <span>{{ $verifikasiLabel }}</span>
                                                            <span class="akreditasi-link-badge">Resmi</span>
                                                            <i class="bi bi-box-arrow-up-right akreditasi-link-extern"></i>
                                                        </a>
                                                    @endif
                                                    @if(! $prodi->sertifikat && ! $verifikasiLabel)
                                                        <span class="text-muted small">Belum tersedia</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">Belum ada data program studi.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Mobile (<768px): card list, 1 prodi per card, label + value stacked --}}
                            <div class="d-block d-md-none akreditasi-mobile-list">
                                @forelse($programStudi as $prodi)
                                    @php
                                        $status = $prodi->getStatusKedaluwarsa();
                                        $verifikasiLabel = $prodi->getVerifikasiLabel();
                                    @endphp
                                    <article id="prodi-m-{{ \Illuminate\Support\Str::slug($prodi->nama) }}" class="akreditasi-card">
                                        <header class="akreditasi-card-header">
                                            <span class="akreditasi-card-jenjang">{{ $prodi->jenjang }}</span>
                                            <h3 class="akreditasi-card-title">{{ $prodi->nama }}</h3>
                                        </header>

                                        <dl class="akreditasi-card-body">
                                            <div class="akreditasi-row">
                                                <dt>Peringkat</dt>
                                                <dd class="fw-semibold">{{ $prodi->akreditasi ?? '-' }}</dd>
                                            </div>
                                            <div class="akreditasi-row">
                                                <dt>No. SK</dt>
                                                <dd class="small text-break">{{ $prodi->no_sk ?? '-' }}</dd>
                                            </div>
                                            <div class="akreditasi-row">
                                                <dt>Tahun SK</dt>
                                                <dd>{{ $prodi->tahun_sk ?? '-' }}</dd>
                                            </div>
                                            <div class="akreditasi-row">
                                                <dt>Kedaluwarsa</dt>
                                                <dd>@tanggal($prodi->tanggal_kedaluwarsa, 'd-m-Y')</dd>
                                            </div>
                                            <div class="akreditasi-row">
                                                <dt>Status</dt>
                                                <dd>
                                                    @if($status)
                                                        <span class="badge rounded-pill bg-{{ $status['color'] }}-subtle text-{{ $status['color'] }}-emphasis border border-{{ $status['color'] }}-subtle">
                                                            <i class="bi bi-{{ $status['color'] === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill' }} me-1"></i>{{ $status['label'] }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </dd>
                                            </div>
                                        </dl>

                                        @if($prodi->sertifikat || $verifikasiLabel)
                                            <div class="akreditasi-card-actions">
                                                @if($prodi->sertifikat)
                                                    <a href="{{ asset('storage/' . $prodi->sertifikat) }}"
                                                       target="_blank"
                                                       rel="noopener"
                                                       class="akreditasi-card-action">
                                                        <i class="bi bi-file-earmark-pdf"></i>
                                                        <span>Sertifikat</span>
                                                    </a>
                                                @endif
                                                @if($verifikasiLabel)
                                                    <a href="{{ $prodi->verifikasi_url }}"
                                                       target="_blank"
                                                       rel="noopener"
                                                       class="akreditasi-card-action akreditasi-card-action--verifikasi">
                                                        <i class="bi bi-patch-check-fill"></i>
                                                        <span>{{ $verifikasiLabel }}</span>
                                                        <span class="akreditasi-card-badge">Resmi</span>
                                                        <i class="bi bi-box-arrow-up-right ms-auto small opacity-75"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </article>
                                @empty
                                    <p class="text-center text-muted py-4 mb-0">Belum ada data program studi.</p>
                                @endforelse
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
