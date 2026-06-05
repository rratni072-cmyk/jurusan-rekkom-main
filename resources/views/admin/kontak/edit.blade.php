@extends('layouts.admin')
@section('title', 'Informasi Kontak')
@section('content')
<div class="container-lg px-0">
    <nav aria-label="breadcrumb" class="mb-4 mt-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Informasi Kontak</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Informasi Kontak</h1>
    </div>

    <form action="{{ route('admin.kontak.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            {{-- Info Kontak Utama --}}
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header"><strong>Informasi Utama</strong></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror"
                                      id="alamat" name="alamat"
                                      rows="3">{{ old('alamat', $kontak->alamat ?? '') }}</textarea>
                            @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email"
                                   value="{{ old('email', $kontak->email ?? '') }}"
                                   placeholder="info@rekkom.ac.id">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="telepon" class="form-label">Telepon</label>
                            <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                                   id="telepon" name="telepon"
                                   value="{{ old('telepon', $kontak->telepon ?? '') }}"
                                   placeholder="(0541) 7771353">
                            @error('telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="google_maps_embed" class="form-label">
                                <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-map') }}"></use></svg>
                                Google Maps Embed URL
                            </label>
                            <input type="url" class="form-control @error('google_maps_embed') is-invalid @enderror"
                                   id="google_maps_embed" name="google_maps_embed"
                                   value="{{ old('google_maps_embed', $kontak->google_maps_embed ?? '') }}"
                                   placeholder="https://www.google.com/maps/embed?pb=...">
                            @error('google_maps_embed')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">
                                <strong>Cara mendapatkan URL:</strong> Buka Google Maps → Cari lokasi → Klik <strong>Bagikan</strong> → Tab <strong>Sematkan peta</strong> → Copy <strong>URL src</strong> dari kode iframe (bukan seluruh kode HTML).
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="koordinat" class="form-label">Koordinat (Fallback)</label>
                            <input type="text" class="form-control @error('koordinat') is-invalid @enderror"
                                   id="koordinat" name="koordinat"
                                   value="{{ old('koordinat', $kontak->koordinat ?? '') }}"
                                   placeholder="-0.5360357,117.1235594">
                            @error('koordinat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Format: lat,lng — Digunakan jika Google Maps Embed URL tidak diisi.</div>
                        </div>
                        {{-- Preview Peta --}}
                        @php
                            $embedUrl = old('google_maps_embed', $kontak->google_maps_embed ?? '');
                            if (!$embedUrl && ($kontak->koordinat ?? '')) {
                                $coords = explode(',', $kontak->koordinat);
                                $lat = trim($coords[0] ?? '');
                                $lng = trim($coords[1] ?? '');
                                if ($lat && $lng) {
                                    $embedUrl = "https://maps.google.com/maps?q={$lat},{$lng}&z=15&output=embed";
                                }
                            }
                        @endphp
                        @if($embedUrl)
                            <div class="mb-0">
                                <label class="form-label text-body-secondary">Preview Peta</label>
                                <iframe src="{{ $embedUrl }}" width="100%" height="250"
                                        style="border:0; border-radius:6px;" allowfullscreen loading="lazy"></iframe>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Media Sosial --}}
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header"><strong>Media Sosial</strong></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="instagram" class="form-label">
                                <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-camera') }}"></use></svg>
                                Instagram
                            </label>
                            <input type="url" class="form-control @error('instagram') is-invalid @enderror"
                                   id="instagram" name="instagram"
                                   value="{{ old('instagram', $kontak->instagram ?? '') }}"
                                   placeholder="https://instagram.com/...">
                            @error('instagram')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="facebook" class="form-label">
                                <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-thumb-up') }}"></use></svg>
                                Facebook
                            </label>
                            <input type="url" class="form-control @error('facebook') is-invalid @enderror"
                                   id="facebook" name="facebook"
                                   value="{{ old('facebook', $kontak->facebook ?? '') }}"
                                   placeholder="https://facebook.com/...">
                            @error('facebook')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="youtube" class="form-label">
                                <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-video') }}"></use></svg>
                                YouTube
                            </label>
                            <input type="url" class="form-control @error('youtube') is-invalid @enderror"
                                   id="youtube" name="youtube"
                                   value="{{ old('youtube', $kontak->youtube ?? '') }}"
                                   placeholder="https://youtube.com/...">
                            @error('youtube')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="tiktok" class="form-label">
                                <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-music-note') }}"></use></svg>
                                TikTok
                            </label>
                            <input type="url" class="form-control @error('tiktok') is-invalid @enderror"
                                   id="tiktok" name="tiktok"
                                   value="{{ old('tiktok', $kontak->tiktok ?? '') }}"
                                   placeholder="https://tiktok.com/...">
                            @error('tiktok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="linkedin" class="form-label">
                                <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-briefcase') }}"></use></svg>
                                LinkedIn
                            </label>
                            <input type="url" class="form-control @error('linkedin') is-invalid @enderror"
                                   id="linkedin" name="linkedin"
                                   value="{{ old('linkedin', $kontak->linkedin ?? '') }}"
                                   placeholder="https://linkedin.com/in/...">
                            @error('linkedin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <button type="submit" class="btn btn-primary">
                <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-save') }}"></use></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
