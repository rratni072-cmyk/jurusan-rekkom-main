# Website Jurusan Rekayasa dan Komputer

Website resmi **Jurusan Rekayasa dan Komputer**, Politeknik Pertanian Negeri Samarinda.  
Dibangun sebagai proyek **Project Based Learning (PBL)** menggunakan Laravel 12.

---

## 📋 Daftar Isi

- [Tentang Proyek](#tentang-proyek)
- [Tech Stack](#tech-stack)
- [Prasyarat](#prasyarat)
- [Instalasi](#instalasi)
- [Akun Admin](#akun-admin)
- [Data Dummy](#data-dummy)
- [Arsitektur Proyek](#arsitektur-proyek)
- [Fitur](#fitur)
- [Struktur Folder](#struktur-folder)
- [Perintah Berguna](#perintah-berguna)
- [Kontribusi](#kontribusi)
- [Lisensi](#lisensi)

---

## Tentang Proyek

Website ini berfungsi sebagai portal informasi resmi Jurusan Rekayasa dan Komputer (R&K) yang mencakup:

- Profil jurusan (visi-misi, struktur organisasi, akreditasi)
- Informasi program studi (D3 & D4)
- Berita dan informasi terkini jurusan
- Tridharma Perguruan Tinggi (Pengajaran, Pengabdian, Penelitian)
- Kemahasiswaan (kegiatan, beasiswa, pedoman, jadwal)
- Halaman kontak dengan formulir pesan

### Identitas Institusi

| Field | Keterangan |
|-------|-----------|
| **Institusi** | Politeknik Pertanian Negeri Samarinda |
| **Jurusan** | Rekayasa dan Komputer |
| **Akronim** | R&K / Rekkom |
| **Domain Target** | `rekkom.politani.ac.id` |
| **Email** | `rekkom@politani.ac.id` |
| **Telepon** | (0541) 260421 |

---

## Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| **Backend** | PHP 8.2+, Laravel 12 |
| **Frontend** | Blade, Bootstrap 5, Vite |
| **Admin Template** | CoreUI (Bootstrap 5) |
| **Public Template** | Eterna (Bootstrap 5) |
| **Database** | MySQL 8.0+ |
| **Editor Konten** | TinyMCE 7 |
| **DataTables** | Yajra DataTables |
| **Image Processing** | Intervention Image |
| **Activity Logging** | Spatie Activity Log |
| **Icons** | Bootstrap Icons |

---

## Prasyarat

Pastikan sudah terinstal di sistem Anda:

- **PHP** >= 8.2 (dengan ekstensi: `gd`, `mbstring`, `pdo_mysql`, `openssl`, `fileinfo`)
- **Composer** >= 2.x
- **Node.js** >= 18.x dan **NPM** >= 9.x
- **MySQL** >= 8.0
- **Git**

> **Rekomendasi**: Gunakan [Laragon](https://laragon.org/) (Windows) atau [Herd](https://herd.laravel.com/) untuk kemudahan setup lokal.

---

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/Tulus04/website-jurusan.git
cd website-jurusan
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` dan sesuaikan konfigurasi database:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=website_jurusan
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Setup Database

```bash
php artisan migrate --seed
```

Perintah di atas akan:
- Membuat semua tabel yang diperlukan
- Mengisi data awal (admin user, program studi, kategori, data dummy konten)

### 5. Storage Link

```bash
php artisan storage:link
```

Membuat symbolic link `public/storage` → `storage/app/public` agar file upload dapat diakses dari browser.

### 6. Build Asset Frontend

```bash
npm run build
```

Atau untuk development dengan hot-reload:

```bash
npm run dev
```

### 7. Jalankan Server

```bash
php artisan serve
```

Akses website di: **http://localhost:8000**

---

## Akun Admin

Setelah menjalankan seeder, gunakan kredensial berikut untuk login ke panel admin:

| Field | Value |
|-------|-------|
| **URL Login** | `http://localhost:8000/login` |
| **Email** | `admin@rekkom.ac.id` |
| **Password** | `password` |

> ⚠️ **PENTING**: Segera ganti password default ini jika digunakan di lingkungan production!

---

## Data Dummy

> ⚠️ **PERHATIAN — DATA KONTEN ADALAH DATA DUMMY**

Seluruh konten berikut yang dihasilkan oleh database seeder adalah **data contoh (dummy)** dan **bukan data faktual**:

| Modul | Keterangan |
|-------|-----------|
| **Berita** | Judul, ringkasan, dan isi berita adalah data fiksi untuk keperluan pengembangan |
| **Pengabdian** | Data kegiatan pengabdian masyarakat bersifat ilustrasi |
| **Pengajaran** | Artikel pengajaran adalah konten dummy |
| **Kegiatan Mahasiswa** | Data kegiatan kemahasiswaan bersifat contoh |
| **Slider Homepage** | Gambar dan teks slider menggunakan placeholder |
| **Struktur Organisasi** | Nama pejabat dan jabatan perlu disesuaikan dengan data riil |

> ℹ️ **Catatan**: Data **Beasiswa** yang ada di sistem adalah **data asli** dan tidak perlu diganti.

### Yang Perlu Dilakukan Setelah Deployment:

1. **Login** ke panel admin (`/login`)
2. **Hapus/edit** seluruh berita dummy, ganti dengan berita asli dan faktual
3. **Upload** konten pengabdian dan pengajaran yang sebenarnya
4. **Perbarui** data kegiatan mahasiswa dengan kegiatan nyata
5. **Ganti** slider homepage dengan gambar resmi jurusan
6. **Perbarui** struktur organisasi dengan data pejabat aktual
7. **Update** informasi kontak dan profil jurusan sesuai keadaan terbaru

> **Jangan mempublikasikan website dengan data dummy!** Pastikan seluruh konten sudah diganti dengan informasi asli, akurat, dan dapat dipertanggungjawabkan.

---

## Arsitektur Proyek

Proyek mengikuti pola arsitektur:

```
Routes → Middleware → Controller → Form Request → Repository → Model → Database
```

- **Controller** — Orchestrasi ringan, delegasi logic ke repository/service
- **Form Request** — Validasi input menggunakan class Laravel Form Request
- **Repository** — Query dan business logic data
- **Model** — Definisi relasi, scope, accessor, dan mass-assignment `$fillable`

### Dua Domain UI:

| Domain | Template | Path Views | Auth |
|--------|----------|------------|------|
| **Public (Frontend)** | Eterna + Bootstrap 5 | `resources/views/frontend/` | Tidak |
| **Admin Panel** | CoreUI + Bootstrap 5 | `resources/views/admin/` | Ya (`auth` middleware) |

---

## Fitur

### Frontend (Publik)

- 🏠 **Beranda** — Hero slider, berita terkini, program studi
- 📰 **Berita** — Daftar berita dengan filter kategori, search, dan sorting
- 📖 **Profil Jurusan** — Tentang, visi-misi, struktur organisasi, akreditasi
- 🎓 **Program Studi** — Informasi D3 TG, D3 SIA, D4 TRPL, D4 TRGS
- 🔬 **Tridharma** — Pengajaran, Pengabdian Masyarakat, Penelitian (link eksternal)
- 👨‍🎓 **Kemahasiswaan** — Kegiatan, beasiswa, pedoman akademik, jadwal
- 📧 **Kontak** — Formulir pesan dengan rate-limiting anti-spam
- 📱 **Responsive** — Tampilan optimal di desktop, tablet, dan mobile

### Admin Panel

- 📊 **Dashboard** — Statistik konten dan aktivitas terbaru
- ✏️ **Kelola Berita** — CRUD dengan editor TinyMCE dan upload gambar
- 📚 **Kelola Tridharma** — CRUD Pengajaran & Pengabdian
- 🎪 **Kelola Kegiatan** — CRUD kegiatan mahasiswa
- 🖼️ **Kelola Slider** — Manajemen gambar slider homepage
- 📋 **Kelola Pedoman** — Upload dokumen PDF pedoman akademik
- 📅 **Kelola Jadwal** — Upload jadwal perkuliahan
- 🏆 **Kelola Beasiswa** — Manajemen informasi beasiswa
- 🏫 **Profil Jurusan** — Edit informasi profil, visi-misi, struktur
- 📂 **Kategori** — Manajemen kategori berita
- 📝 **Activity Log** — Riwayat aktivitas admin (via Spatie)

---

## Struktur Folder

```
website-jurusan/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Controller panel admin
│   │   │   └── Frontend/       # Controller halaman publik
│   │   └── Requests/           # Form Request validation
│   ├── Models/                 # Eloquent models
│   ├── Providers/              # Service providers
│   └── Repositories/           # Repository pattern
├── database/
│   ├── migrations/             # Schema database
│   └── seeders/                # Data awal & dummy
├── public/
│   └── storage -> storage link # File upload (symlink)
├── resources/
│   ├── css/                    # Stylesheet (Vite)
│   ├── js/                     # JavaScript (Vite)
│   └── views/
│       ├── admin/              # Blade views admin (CoreUI)
│       ├── frontend/           # Blade views publik (Eterna)
│       └── components/         # Blade components shared
├── routes/
│   └── web.php                 # Route definitions
├── storage/
│   └── app/public/             # File uploads (berita, slider, dll)
└── .agents/                    # Aturan & workflow pengembangan
```

---

## Perintah Berguna

```bash
# Jalankan server development
php artisan serve

# Build asset frontend (production)
npm run build

# Build asset frontend (development + hot-reload)
npm run dev

# Jalankan test
php artisan test

# Cek code style
php vendor/laravel/pint/builds/pint --test

# Auto-fix code style
php vendor/laravel/pint/builds/pint

# Lihat daftar route
php artisan route:list --except-vendor

# Reset database + seed ulang (⚠️ menghapus semua data!)
php artisan migrate:fresh --seed

# Clear semua cache
php artisan optimize:clear
```

---

## Kontribusi

1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b fitur/nama-fitur`)
3. Commit perubahan (`git commit -m 'Tambah fitur baru'`)
4. Push ke branch (`git push origin fitur/nama-fitur`)
5. Buat Pull Request

### Aturan Pengembangan:

- Gunakan **Bootstrap 5** — jangan mix dengan Tailwind
- Validasi input via **Laravel Form Request**
- UI copy dalam **Bahasa Indonesia formal**
- Gunakan **Bootstrap Icons** untuk ikon
- Log mutasi admin dengan **Spatie Activity Log**
- Jalankan `php vendor/laravel/pint/builds/pint` sebelum commit

---

## Lisensi

Proyek ini dikembangkan untuk keperluan akademik (PBL) di Politeknik Pertanian Negeri Samarinda.
