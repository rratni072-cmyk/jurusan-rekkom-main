<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Berita;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Repository untuk akses data Berita.
 *
 * Mengabstraksi query Eloquent dari Controller sesuai pola Repository yang
 * ditetapkan di .agents/rules/arsitektur-proyek.md dan kualitas-kode.md.
 */
class BeritaRepository
{
    /**
     * Ambil berita untuk highlight homepage.
     *
     * Mengembalikan koleksi berita yang sudah dipublikasi, di-eager-load
     * relasi kategori untuk mencegah N+1, dan diurutkan dari tanggal
     * publikasi terbaru.
     *
     * @param  int  $limit  Jumlah maksimum berita yang dikembalikan.
     * @return Collection<int, Berita>
     */
    public function getHomepageHighlights(int $limit = 5): Collection
    {
        return Berita::query()
            ->regular()
            ->published()
            ->with(['kategoris:id,nama,slug', 'penulis:id,name'])
            ->latest('tanggal_publikasi')
            ->take($limit)
            ->get();
    }

    /**
     * Ambil daftar berita terkini untuk widget sidebar "Artikel Terkini".
     *
     * Hanya memuat kolom yang dibutuhkan widget agar query ringan dan
     * tidak memuat field besar seperti `konten`/`ringkasan`.
     *
     * @param  int  $limit  Jumlah berita yang dikembalikan.
     * @return Collection<int, Berita>
     */
    public function getRecentForSidebar(int $limit = 5): Collection
    {
        return Berita::query()
            ->regular()
            ->published()
            ->latest('tanggal_publikasi')
            ->take($limit)
            ->get(['id', 'slug', 'judul', 'gambar', 'tanggal_publikasi']);
    }

    /**
     * Paginated daftar berita untuk halaman index publik.
     *
     * Mendukung filter: search (judul + ringkasan LIKE), kategori slug,
     * dan sort (terbaru|terlama|judul|populer). `withQueryString()` agar
     * pagination preserve semua filter aktif.
     *
     * @param  array{search?: ?string, kategori?: ?string, sort?: ?string}  $filters
     */
    public function paginateWithFilters(array $filters, int $perPage = 8): LengthAwarePaginator
    {
        $search = $filters['search'] ?? null;
        $kategoriSlug = $filters['kategori'] ?? null;
        $sort = $filters['sort'] ?? 'terbaru';

        $query = Berita::query()
            ->regular()
            ->published()
            ->with(['kategoris:id,nama,slug', 'penulis:id,name']);

        if ($search !== null && $search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('ringkasan', 'like', "%{$search}%");
            });
        }

        if ($kategoriSlug !== null && $kategoriSlug !== '') {
            $query->whereHas('kategoris', function ($q) use ($kategoriSlug) {
                $q->where('kategoris.slug', $kategoriSlug);
            });
        }

        match ($sort) {
            'terlama' => $query->orderBy('tanggal_publikasi', 'asc'),
            'judul' => $query->orderBy('judul', 'asc'),
            'populer' => $query->orderByDesc('views')->orderByDesc('tanggal_publikasi'),
            default => $query->latest('tanggal_publikasi'),
        };

        return $query->paginate($perPage)->withQueryString();
    }
}
