<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ProfilJurusan;
use Illuminate\Database\Seeder;

/**
 * Seeder untuk 3 section Profil Jurusan.
 *
 * Pakai `updateOrCreate` (bukan `create`) supaya idempotent — aman re-run
 * tanpa duplikat. Kunci HARUS sesuai dengan
 * {@see \App\Http\Requests\Admin\ProfilJurusanRequest::SLUG_TO_KEY}:
 *   - tentang_jurusan      → /profil/tentang-jurusan
 *   - visi_misi            → /profil/visi-misi
 *   - struktur_organisasi  → /profil/struktur-organisasi
 *
 * Field `judul` WAJIB (validator `required`) → di-seed eksplisit.
 *
 * Cara run independent:
 *   php artisan db:seed --class=ProfilJurusanSeeder
 */
class ProfilJurusanSeeder extends Seeder
{
    public function run(): void
    {
        foreach (self::data() as $profil) {
            ProfilJurusan::updateOrCreate(
                ['kunci' => $profil['kunci']],
                $profil,
            );
        }
    }

    /**
     * Data profil default. Konten realistis dari rule
     * `.windsurf/rules/identitas-website.md` (nama institusi, prodi, kontak).
     *
     * @return array<int, array{kunci: string, judul: string, nilai: string}>
     */
    public static function data(): array
    {
        return [
            [
                'kunci' => 'tentang_jurusan',
                'judul' => 'Tentang Jurusan Rekayasa dan Komputer',
                'nilai' => self::tentangJurusan(),
            ],
            [
                'kunci' => 'visi_misi',
                'judul' => 'Visi & Misi Jurusan Rekayasa dan Komputer',
                'nilai' => self::visiMisi(),
            ],
            [
                'kunci' => 'struktur_organisasi',
                'judul' => 'Struktur Organisasi Jurusan Rekayasa dan Komputer',
                'nilai' => self::strukturOrganisasi(),
            ],
        ];
    }

    private static function tentangJurusan(): string
    {
        return <<<'HTML'
<p>Jurusan <strong>Rekayasa dan Komputer (R&amp;K)</strong> adalah salah satu jurusan di Politeknik Pertanian Negeri Samarinda yang fokus pada bidang teknologi informasi, rekayasa perangkat lunak, dan geomatika.</p>
<p>Berlokasi di <em>Jalan Samratulangi, Sungai Keledang, Kecamatan Samarinda Seberang, Kota Samarinda, Kalimantan Timur</em>, Jurusan R&amp;K menyelenggarakan pendidikan vokasi yang menggabungkan teori akademik dengan praktik industri.</p>
<h4 class="mt-4">Program Studi</h4>
<p>Saat ini Jurusan R&amp;K mengelola <strong>4 program studi</strong>:</p>
<ul>
    <li><strong>D3 Teknologi Geomatika (TG)</strong></li>
    <li><strong>D4 Teknologi Rekayasa Perangkat Lunak (TRPL)</strong></li>
    <li><strong>D4 Teknologi Rekayasa Geomatika dan Survei (TRGS)</strong></li>
    <li><strong>D3 Sistem Informasi Akuntansi (SIA)</strong></li>
</ul>
<h4 class="mt-4">Komitmen</h4>
<p>Kami berkomitmen mencetak lulusan yang tidak hanya menguasai teori, tetapi juga terampil dalam praktik di dunia industri &mdash; siap menjawab tantangan era digital di Kalimantan Timur dan nasional.</p>
HTML;
    }

    private static function visiMisi(): string
    {
        return <<<'HTML'
<h4>Visi</h4>
<blockquote class="border-start border-4 border-primary ps-3 my-3 fst-italic">
    Menjadikan jurusan berkualitas unggul dan berkembang dalam pengelolaan pendidikan vokasi pada program studi teknik dan informatika di Kalimantan di tahun 2024.
</blockquote>

<h4 class="mt-4">Misi</h4>
<ol>
    <li>Mengoptimalkan potensi yang ada dalam melaksanakan Tri Darma Perguruan Tinggi sebagai perguruan tinggi vokasi.</li>
    <li>Menciptakan iklim kinerja yang kondusif, kebersamaan yang tinggi, dan <em>teamwork</em> yang solid.</li>
    <li>Pengembangan manajemen organisasi yang berbasis pada IT/digitalisasi dan penguatan basis data jurusan.</li>
    <li>Memberikan kesempatan dalam peningkatan sumber daya manusia, baik tenaga dosen maupun kependidikan.</li>
</ol>

<h4 class="mt-4">Tujuan</h4>
<ol>
    <li>Terlaksananya proses pembelajaran yang efisien dan efektif baik teoritis maupun praktis.</li>
    <li>Tercapainya kualitas dan kuantitas karya ilmiah baik dosen maupun mahasiswa.</li>
    <li>Terwujudnya implementasi praktis yang bisa dirasakan masyarakat secara berkesinambungan.</li>
    <li>Tercapainya hasil yang maksimal serta adanya <em>sense of belonging</em>.</li>
    <li>Tercapainya produk unggulan yang mampu berkompetisi di dunia industri maupun di bursa kerja.</li>
</ol>

<h4 class="mt-4">Sasaran</h4>
<ol>
    <li>Melakukan kerja sama/kemitraan dengan dunia industri (DUDI) dan institusi pemerintah maupun swasta.</li>
    <li>Menggiatkan <em>video branding</em> terhadap calon input maupun output.</li>
    <li>Memberikan kesempatan terhadap peningkatan sumber daya manusia, baik tenaga dosen maupun kependidikan.</li>
    <li>Melakukan kolaborasi dan <em>sharing</em> baik di lingkup internal dan eksternal kampus dalam publikasi karya ilmiah (penelitian dan pengabdian).</li>
    <li>Mengupayakan peningkatan akreditasi kelembagaan berdasarkan standar nasional versi BAN-PT (Baik &rarr; Baik Sekali, Baik Sekali &rarr; Unggul).</li>
    <li>Pengembangan jurusan dengan menambah program studi baru yang serumpun dengan mencermati peluang <em>outcome</em> di dunia kerja.</li>
    <li>Memberikan kesempatan pada mahasiswa, dosen, PLP, dan tenaga administrasi dalam meningkatkan kemampuan berbahasa Inggris secara aktif serta peningkatan <em>grade</em> TOEFL yang mampu mendukung bidang keilmuannya masing-masing.</li>
</ol>
HTML;
    }

    private static function strukturOrganisasi(): string
    {
        return <<<'HTML'
<p>Struktur organisasi Jurusan R&amp;K disusun untuk memastikan tata kelola pendidikan, penelitian, dan pengabdian masyarakat berjalan efektif. Berikut komposisi pejabat struktural Jurusan Rekayasa dan Komputer.</p>

<h4 class="mt-4">Pengelola Jurusan</h4>
<ul>
    <li><strong>Ketua Jurusan</strong> &mdash; Dr. Suswanto, M.Pd. <span class="text-muted">(NIP. 19680525 199512 1 001)</span></li>
    <li><strong>Sekretaris Jurusan</strong> &mdash; Ida Maratul Khamidah, S.Kom., M.Cs. <span class="text-muted">(NIP. 19910113 201903 2 023)</span></li>
</ul>

<h4 class="mt-4">Tenaga Administrasi</h4>
<ul>
    <li>Arief Yani Budiman, S.E. <span class="text-muted">(NIP. 19670503 199002 1 002)</span></li>
</ul>

<h4 class="mt-4">Koordinator Program Studi</h4>
<div class="alert alert-info border-0 bg-info-subtle text-info-emphasis my-3 small">
    <i class="bi bi-info-circle me-2"></i>
    Data koordinator prodi sedang diperbarui.
</div>
<ul>
    <li><strong>Koordinator Prodi D3 Teknologi Geomatika (TG)</strong> &mdash; Segera diperbarui</li>
    <li><strong>Koordinator Prodi D3 Sistem Informasi Akuntansi (SIA)</strong> &mdash; Segera diperbarui</li>
    <li><strong>Koordinator Prodi D4 Teknologi Rekayasa Perangkat Lunak (TRPL)</strong> &mdash; Segera diperbarui</li>
    <li><strong>Koordinator Prodi D4 Teknologi Rekayasa Geomatika dan Survei (TRGS)</strong> &mdash; Segera diperbarui</li>
</ul>

<p class="text-muted small mt-4">
    <i class="bi bi-envelope me-1"></i>
    Untuk informasi resmi struktur organisasi, hubungi
    <a href="mailto:rekkom@politani.ac.id">rekkom@politani.ac.id</a>
    atau telepon
    <a href="tel:+62541260421">(0541) 260421</a>.
</p>
HTML;
    }
}
