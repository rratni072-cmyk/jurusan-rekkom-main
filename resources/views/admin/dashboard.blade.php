{{--
|--------------------------------------------------------------------------
| Dashboard Admin — Website Jurusan Rekayasa & Komputer
|--------------------------------------------------------------------------
| Layout robust: Welcome + Quick Actions, Stat Cards (6 konsisten),
| Chart dengan filter rentang, Pesan Belum Dibalas, Berita Terbaru,
| Acara Mendatang, Activity Log.
|
| Data via App\Repositories\DashboardRepository.
|--------------------------------------------------------------------------
--}}
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    {{-- ================== BREADCRUMB ================== --}}
    <nav aria-label="breadcrumb" class="mb-3 mt-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>

    {{-- ================== WELCOME + QUICK ACTIONS ================== --}}
    <div class="card mb-4 dashboard-welcome">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-md-7">
                    <h1 class="h4 mb-1 d-flex align-items-center gap-2">
                        <svg class="icon icon-lg text-primary" aria-hidden="true"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-hand-point-up') }}"></use></svg>
                        Selamat datang, {{ Auth::user()->name ?? 'Admin' }}
                    </h1>
                    <p class="text-body-secondary mb-2">
                        Panel admin Website Jurusan Rekayasa &amp; Komputer — Politeknik Pertanian Negeri Samarinda.
                    </p>
                    <div class="d-flex flex-wrap gap-3 small text-body-secondary">
                        <span>
                            <svg class="icon icon-sm me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-calendar') }}"></use></svg>
                            @tanggal(now(), 'l, d F Y')
                        </span>
                        @if($stats['pesan_unread'] > 0)
                            <span class="text-danger fw-semibold">
                                <svg class="icon icon-sm me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-envelope-letter') }}"></use></svg>
                                {{ $stats['pesan_unread'] }} pesan belum dibaca
                            </span>
                        @endif
                        @if($stats['berita_draft'] > 0)
                            <span class="text-warning-emphasis fw-semibold">
                                <svg class="icon icon-sm me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-pencil') }}"></use></svg>
                                {{ $stats['berita_draft'] }} berita draft
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="d-flex flex-wrap justify-content-md-end gap-2">
                        <a href="{{ route('admin.berita.create') }}" class="btn btn-primary">
                            <svg class="icon icon-sm me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-plus') }}"></use></svg>
                            Tulis Berita
                        </a>
                        <a href="{{ route('admin.kegiatan.create') }}" class="btn btn-warning text-white">
                            <svg class="icon icon-sm me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-calendar-check') }}"></use></svg>
                            Kegiatan
                        </a>
                        <a href="{{ route('admin.slider.create') }}" class="btn btn-info text-white">
                            <svg class="icon icon-sm me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-image-plus') }}"></use></svg>
                            Slider
                        </a>
                        <a href="{{ route('admin.pesan.index') }}" class="btn btn-outline-primary position-relative">
                            <svg class="icon icon-sm me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-envelope-open') }}"></use></svg>
                            Pesan
                            @if($stats['pesan_unread'] > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $stats['pesan_unread'] }}
                                    <span class="visually-hidden">pesan belum dibaca</span>
                                </span>
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================== STAT CARDS (6 KONSISTEN) ================== --}}
    @php
        $statCards = [
            ['label' => 'Total Berita', 'value' => $stats['berita'], 'bg' => 'primary', 'icon' => 'cil-newspaper', 'route' => 'admin.berita.index'],
            ['label' => 'Total Jadwal', 'value' => $stats['jadwal'], 'bg' => 'info', 'icon' => 'cil-calendar', 'route' => 'admin.jadwal.index'],
            ['label' => 'Total Kegiatan', 'value' => $stats['kegiatan'], 'bg' => 'warning', 'icon' => 'cil-star', 'route' => 'admin.kegiatan.index'],
            ['label' => 'Pesan Masuk', 'value' => $stats['pesan'], 'bg' => 'danger', 'icon' => 'cil-envelope-letter', 'route' => 'admin.pesan.index', 'sub' => $stats['pesan_unread'] > 0 ? $stats['pesan_unread'].' belum dibaca' : null],
            ['label' => 'Slider Aktif', 'value' => $stats['slider_aktif'].'/'.$stats['slider'], 'bg' => 'success', 'icon' => 'cil-image-plus', 'route' => 'admin.slider.index'],
            ['label' => 'Program Studi', 'value' => $stats['prodi'], 'bg' => 'secondary', 'icon' => 'cil-education', 'route' => 'admin.program-studi.index'],
        ];
    @endphp

    <div class="row g-3 mb-4">
        @foreach($statCards as $card)
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <div class="card text-white bg-{{ $card['bg'] }} h-100 stat-card">
                    <div class="card-body pb-2 d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1 min-w-0">
                            <div class="fs-4 fw-semibold">{{ $card['value'] }}</div>
                            <div class="text-truncate">{{ $card['label'] }}</div>
                            @if(!empty($card['sub']))
                                <small class="opacity-75 d-block mt-1">{{ $card['sub'] }}</small>
                            @endif
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-transparent text-white p-0"
                                    type="button"
                                    data-coreui-toggle="dropdown"
                                    aria-expanded="false"
                                    aria-label="Aksi {{ $card['label'] }}">
                                <svg class="icon"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-options') }}"></use></svg>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route($card['route']) }}">
                                    <svg class="icon icon-sm me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#'.$card['icon']) }}"></use></svg>
                                    Lihat Semua
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ================== CHART KONTEN ================== --}}
    <div class="card mb-4">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <strong>Statistik Konten</strong>
                <p class="text-body-secondary small mb-0">Jumlah berita &amp; kegiatan per bulan.</p>
            </div>
            <div class="btn-group btn-group-sm" role="group" aria-label="Filter rentang bulan">
                @foreach([3, 6, 12] as $opt)
                    <a href="{{ route('admin.dashboard') }}?months={{ $opt }}"
                       class="btn btn-outline-primary {{ $chartMonths === $opt ? 'active' : '' }}"
                       aria-pressed="{{ $chartMonths === $opt ? 'true' : 'false' }}">
                        {{ $opt }} bulan
                    </a>
                @endforeach
            </div>
        </div>
        <div class="card-body">
            <div id="dashboard-chart-skeleton" class="dashboard-chart-skeleton" aria-hidden="true"></div>
            <canvas id="dashboard-chart" height="80" class="d-none"></canvas>
        </div>
    </div>

    {{-- ================== ROW: PESAN BELUM DIBALAS + BERITA TERBARU ================== --}}
    <div class="row g-4 mb-4">

        {{-- Pesan Belum Dibalas --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <svg class="icon me-1 text-danger"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-envelope-letter') }}"></use></svg>
                        <strong>Pesan Belum Dibaca</strong>
                    </div>
                    <a href="{{ route('admin.pesan.index') }}" class="btn btn-sm btn-outline-danger">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($pesanBelumDibalas->isEmpty())
                        <div class="text-center text-body-secondary py-4">
                            <svg class="icon icon-xl mb-2 text-success" aria-hidden="true"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-check-circle') }}"></use></svg>
                            <p class="mb-0">Semua pesan sudah dibaca.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($pesanBelumDibalas as $pesan)
                                <a href="{{ route('admin.pesan.show', $pesan->id) }}"
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fw-semibold text-truncate">{{ $pesan->nama }}</div>
                                            <small class="text-body-secondary d-block text-truncate">
                                                {{ $pesan->subjek }}
                                            </small>
                                        </div>
                                        <small class="text-body-secondary text-nowrap">
                                            @waktuRelatif($pesan->created_at)
                                        </small>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Berita Terbaru --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <svg class="icon me-1 text-primary"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-newspaper') }}"></use></svg>
                        <strong>Berita Terbaru</strong>
                    </div>
                    <a href="{{ route('admin.berita.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($beritaTerbaru->isEmpty())
                        <div class="text-center text-body-secondary py-4">
                            <svg class="icon icon-xl mb-2"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-newspaper') }}"></use></svg>
                            <p class="mb-0">Belum ada berita.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <tbody>
                                    @foreach($beritaTerbaru as $berita)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold text-truncate" style="max-width: 280px;">
                                                    {{ Str::limit($berita->judul, 60) }}
                                                </div>
                                                <small class="text-body-secondary">
                                                    @waktuRelatif($berita->created_at)
                                                </small>
                                            </td>
                                            <td class="text-end text-nowrap">
                                                @if($berita->is_published)
                                                    <span class="badge bg-success-subtle text-success">Published</span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning">Draft</span>
                                                @endif
                                                <a href="{{ route('admin.berita.edit', $berita->id) }}"
                                                   class="btn btn-sm btn-outline-secondary ms-1"
                                                   aria-label="Edit berita {{ $berita->judul }}">
                                                    <svg class="icon icon-sm"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-pencil') }}"></use></svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- ================== ROW: ACARA MENDATANG + ACTIVITY LOG ================== --}}
    <div class="row g-4 mb-4">

        {{-- Acara Mendatang — alias dari Kegiatan (workshop/webinar/seminar/lomba/akademik)
             yang tanggal pelaksanaannya >= hari ini. --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <svg class="icon me-1 text-warning"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-calendar-check') }}"></use></svg>
                        <strong>Acara Mendatang</strong>
                    </div>
                    <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-sm btn-outline-warning">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($kegiatanMendatang->isEmpty())
                        <div class="text-center text-body-secondary py-4">
                            <svg class="icon icon-xl mb-2"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-calendar') }}"></use></svg>
                            <p class="mb-0">Belum ada acara mendatang.</p>
                            <a href="{{ route('admin.kegiatan.create') }}" class="btn btn-sm btn-warning text-white mt-2">
                                <svg class="icon icon-sm me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-plus') }}"></use></svg>
                                Tambah Kegiatan
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <tbody>
                                    @foreach($kegiatanMendatang as $kegiatan)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold text-truncate" style="max-width: 280px;">
                                                    {{ Str::limit($kegiatan->judul, 60) }}
                                                </div>
                                                <small class="text-body-secondary">
                                                    @waktuRelatif($kegiatan->tanggal)
                                                </small>
                                            </td>
                                            <td class="text-end text-nowrap">
                                                <span class="badge bg-info-subtle text-info">
                                                    @tanggal($kegiatan->tanggal, 'd M Y')
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Activity Log --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <svg class="icon me-1 text-success"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-history') }}"></use></svg>
                        <strong>Aktivitas Terkini</strong>
                    </div>
                    <small class="text-body-secondary">Audit trail admin</small>
                </div>
                <div class="card-body p-0">
                    @if($recentActivities->isEmpty())
                        <div class="text-center text-body-secondary py-4">
                            <svg class="icon icon-xl mb-2"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-history') }}"></use></svg>
                            <p class="mb-0">Belum ada aktivitas tercatat.</p>
                        </div>
                    @else
                        <ul class="list-unstyled mb-0 activity-feed">
                            @foreach($recentActivities as $activity)
                                <li class="activity-feed-item">
                                    <div class="activity-feed-bullet" aria-hidden="true"></div>
                                    <div class="activity-feed-content">
                                        <div class="d-flex justify-content-between gap-2">
                                            <span class="fw-semibold">
                                                {{ $activity->causer?->name ?? 'Sistem' }}
                                            </span>
                                            <small class="text-body-secondary text-nowrap">
                                                @waktuRelatif($activity->created_at)
                                            </small>
                                        </div>
                                        <small class="text-body-secondary">{{ $activity->description }}</small>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

    </div>

@endsection

@push('styles')
<style>
    /* Stat card hover lift halus */
    .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }

    /* Skeleton loader untuk chart */
    .dashboard-chart-skeleton {
        height: 220px;
        border-radius: 6px;
        background: linear-gradient(90deg, rgba(0,0,0,0.04) 25%, rgba(0,0,0,0.08) 37%, rgba(0,0,0,0.04) 63%);
        background-size: 400% 100%;
        animation: dashboard-skeleton-shimmer 1.4s ease-in-out infinite;
    }
    [data-coreui-theme="dark"] .dashboard-chart-skeleton {
        background: linear-gradient(90deg, rgba(255,255,255,0.04) 25%, rgba(255,255,255,0.08) 37%, rgba(255,255,255,0.04) 63%);
        background-size: 400% 100%;
    }
    @keyframes dashboard-skeleton-shimmer {
        0% { background-position: 100% 50%; }
        100% { background-position: 0 50%; }
    }
    @media (prefers-reduced-motion: reduce) {
        .dashboard-chart-skeleton { animation: none; }
        .stat-card { transition: none; }
        .stat-card:hover { transform: none; }
    }

    /* Activity feed timeline-style */
    .activity-feed { padding: 8px 16px; }
    .activity-feed-item {
        display: flex;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .activity-feed-item:last-child { border-bottom: 0; }
    .activity-feed-bullet {
        width: 10px; height: 10px; border-radius: 50%;
        background: var(--cui-primary, #321fdb);
        margin-top: 6px;
        flex-shrink: 0;
    }
    .activity-feed-content { flex: 1; min-width: 0; }
    [data-coreui-theme="dark"] .activity-feed-item { border-bottom-color: rgba(255,255,255,0.08); }
</style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('dashboard-chart');
            const skeleton = document.getElementById('dashboard-chart-skeleton');
            if (!canvas) return;

            const isDark = document.documentElement.getAttribute('data-coreui-theme') === 'dark';
            const gridColor = isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.08)';
            const textColor = isDark ? '#adb5bd' : '#636f83';

            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: @json($chart['labels']),
                    datasets: [
                        {
                            label: 'Berita',
                            data: @json($chart['berita']),
                            backgroundColor: 'rgba(50, 31, 219, 0.65)',
                            borderColor: 'rgb(50, 31, 219)',
                            borderWidth: 1,
                            borderRadius: 4,
                        },
                        {
                            label: 'Kegiatan',
                            data: @json($chart['kegiatan']),
                            backgroundColor: 'rgba(249, 177, 21, 0.65)',
                            borderColor: 'rgb(249, 177, 21)',
                            borderWidth: 1,
                            borderRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { labels: { color: textColor } }
                    },
                    scales: {
                        x: { grid: { color: gridColor }, ticks: { color: textColor } },
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor },
                            ticks: { color: textColor, stepSize: 1, precision: 0 }
                        }
                    }
                }
            });

            // Reveal canvas, hide skeleton
            canvas.classList.remove('d-none');
            if (skeleton) skeleton.remove();
        });
    </script>
@endpush
