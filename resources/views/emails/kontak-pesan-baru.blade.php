<x-mail::message>
# Pesan Masuk Baru

Ada pesan baru dari pengunjung website **{{ config('app.name') }}**:

<x-mail::panel>
**Dari:** {{ $pesan->nama }} &lt;{{ $pesan->email }}&gt;
**Subjek:** {{ $pesan->subjek }}
**Diterima:** {{ $pesan->created_at->translatedFormat('l, d F Y H:i') }} WITA
</x-mail::panel>

**Isi pesan:**

> {!! nl2br(e($pesan->pesan)) !!}

<x-mail::button :url="$adminUrl" color="primary">
Lihat di Panel Admin
</x-mail::button>

---

*Tip: Tekan **Reply** di aplikasi email Anda untuk membalas langsung ke {{ $pesan->nama }}.*

Terima kasih,<br>
**Sistem Notifikasi {{ config('app.name') }}**
</x-mail::message>
