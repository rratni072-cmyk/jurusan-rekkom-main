@extends('layouts.frontend')
@section('title', 'Hubungi Kami')
@section('body_class', 'contact-page')
@section('meta_description', 'Hubungi Jurusan Rekayasa dan Komputer Politeknik Pertanian Negeri Samarinda: alamat Jalan Samratulangi Sungai Keledang, telepon (0541) 260421, email rekkom@politani.ac.id.')

@section('content')

    <div class="page-title" data-aos="fade">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1>Hubungi Kami</h1>
                        <p class="mb-0">Jangan ragu untuk menghubungi kami</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="current">Hubungi Kami</li>
                </ol>
            </div>
        </nav>
    </div>

    <!-- Contact Section - Eterna Style -->
    <section id="contact" class="contact section">

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            {{-- Notifikasi sukses/error: ditangani toast global di layout
                 (lihat partials._toast-flash). Bootstrap Toast otomatis muncul
                 saat ada session('success') atau session('error'). --}}

            {{-- Baris 1: 3 Info Box (Address, Call Us, Email Us) --}}
            <div class="row gy-4">

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="info-item d-flex flex-column justify-content-center align-items-center text-center h-100">
                        <i class="bi bi-geo-alt"></i>
                        <h3>Alamat</h3>
                        <p>{{ $kontak->alamat ?? 'Jalan Samratulangi, Sungai Keledang, Kec. Samarinda Seberang, Kota Samarinda, Kalimantan Timur 75131' }}</p>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="info-item d-flex flex-column justify-content-center align-items-center text-center h-100">
                        <i class="bi bi-telephone"></i>
                        <h3>Telepon</h3>
                        <p><a href="tel:{{ $kontak->telepon ?? '' }}">{{ $kontak->telepon ?? '(0541) 260421' }}</a></p>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="info-item d-flex flex-column justify-content-center align-items-center text-center h-100">
                        <i class="bi bi-envelope"></i>
                        <h3>Email</h3>
                        <p><a href="mailto:{{ $kontak->email ?? '' }}">{{ $kontak->email ?? 'info@politanisamarinda.ac.id' }}</a></p>
                    </div>
                </div>

            </div>

            {{-- Baris 2: Google Maps (kiri) + Form Kontak (kanan) — equal height via align-items-stretch --}}
            <div class="row gy-4 mt-1 align-items-stretch">

                {{-- Google Maps Embed --}}
                <div class="col-lg-6 d-flex" data-aos="fade-up" data-aos-delay="100">
                    @php
                        $defaultEmbedUrl = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.814342710498!2d117.12312951475658!3d-0.5360357995299879!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df68080334dac99%3A0x5327a22a4028b267!2sPoliteknik%20Pertanian%20Negeri%20Samarinda!5e0!3m2!1sid!2sid!4v1714025000000";

                        $mapEmbedUrl = '';

                        // Prioritas 1: URL embed dari admin
                        if ($kontak && $kontak->google_maps_embed) {
                            $mapEmbedUrl = $kontak->google_maps_embed;
                        }
                        // Prioritas 2: Koordinat fallback dari admin
                        elseif ($kontak && $kontak->koordinat) {
                            $coords = explode(',', $kontak->koordinat);
                            $lat = trim($coords[0] ?? '');
                            $lng = trim($coords[1] ?? '');
                            if ($lat && $lng) {
                                $mapEmbedUrl = "https://maps.google.com/maps?q={$lat},{$lng}&z=17&output=embed";
                            }
                        }

                        // Prioritas 3: Default hardcoded Politani Samarinda (dengan detail lokasi)
                        if (!$mapEmbedUrl) {
                            $mapEmbedUrl = $defaultEmbedUrl;
                        }
                    @endphp
                    {{-- Wrapper card — visual match dengan .php-email-form (bg + shadow), equal-height via h-100 --}}
                    <div class="contact-map-card w-100">
                        <iframe
                            src="{{ $mapEmbedUrl }}"
                            title="Lokasi Jurusan Rekayasa dan Komputer — Politeknik Pertanian Negeri Samarinda"
                            allowfullscreen loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>

                {{-- Form Kirim Pesan --}}
                <div class="col-lg-6 d-flex" data-aos="fade-up" data-aos-delay="200">
                    <form action="{{ route('kontak.kirim') }}" method="POST" class="php-email-form w-100">
                        @csrf

                        {{-- Honeypot anti-bot: WAJIB kosong. Disembunyikan dari user via CSS .honeypot-field. --}}
                        <div class="honeypot-field" aria-hidden="true">
                            <label for="website_url">Website (jangan diisi)</label>
                            <input type="text" name="website_url" id="website_url" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="row gy-4">
                            <div class="col-md-6">
                                <label for="kontak-nama" class="form-label">Nama Lengkap <span class="text-danger" aria-label="wajib">*</span></label>
                                <input type="text"
                                       id="kontak-nama"
                                       name="nama"
                                       class="form-control @error('nama') is-invalid @enderror"
                                       placeholder="Contoh: Budi Santoso"
                                       value="{{ old('nama') }}"
                                       autocomplete="name"
                                       autocapitalize="words"
                                       required
                                       aria-required="true"
                                       @error('nama') aria-describedby="kontak-nama-error" aria-invalid="true" @enderror>
                                @error('nama')<div id="kontak-nama-error" class="invalid-feedback" role="alert">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="kontak-email" class="form-label">Email <span class="text-danger" aria-label="wajib">*</span></label>
                                <input type="email"
                                       id="kontak-email"
                                       name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       placeholder="nama@email.com"
                                       value="{{ old('email') }}"
                                       autocomplete="email"
                                       inputmode="email"
                                       autocapitalize="off"
                                       spellcheck="false"
                                       required
                                       aria-required="true"
                                       @error('email') aria-describedby="kontak-email-error" aria-invalid="true" @enderror>
                                @error('email')<div id="kontak-email-error" class="invalid-feedback" role="alert">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="kontak-subjek" class="form-label">Subjek <span class="text-danger" aria-label="wajib">*</span></label>
                                <input type="text"
                                       id="kontak-subjek"
                                       name="subjek"
                                       class="form-control @error('subjek') is-invalid @enderror"
                                       placeholder="Misal: Pertanyaan tentang pendaftaran"
                                       value="{{ old('subjek') }}"
                                       autocomplete="off"
                                       required
                                       aria-required="true"
                                       @error('subjek') aria-describedby="kontak-subjek-error" aria-invalid="true" @enderror>
                                @error('subjek')<div id="kontak-subjek-error" class="invalid-feedback" role="alert">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="kontak-pesan" class="form-label">Pesan <span class="text-danger" aria-label="wajib">*</span></label>
                                <textarea id="kontak-pesan"
                                          name="pesan"
                                          class="form-control @error('pesan') is-invalid @enderror"
                                          rows="6"
                                          placeholder="Tuliskan pesan Anda di sini..."
                                          required
                                          aria-required="true"
                                          @error('pesan') aria-describedby="kontak-pesan-error" aria-invalid="true" @enderror>{{ old('pesan') }}</textarea>
                                @error('pesan')<div id="kontak-pesan-error" class="invalid-feedback" role="alert">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-send me-1" aria-hidden="true"></i> Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

        </div>

    </section>

@endsection
