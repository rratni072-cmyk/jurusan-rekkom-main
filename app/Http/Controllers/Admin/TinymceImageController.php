<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UploadInlineImageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

/**
 * Handle upload gambar inline dari TinyMCE editor.
 *
 * Disebut oleh dialog Insert Image, drag-drop, dan paste-from-clipboard.
 * Response wajib JSON {location: "..."} sesuai kontrak TinyMCE.
 *
 * Pipeline: validasi → resize (Intervention Image) → simpan → activity log.
 *
 * Disk      : public
 * Folder    : storage/app/public/berita/inline/
 * Public URL: /storage/berita/inline/{filename}
 */
class TinymceImageController extends Controller
{
    /**
     * Lebar maksimal gambar (px). Foto kamera HP biasanya >3000px,
     * sedangkan kebutuhan web umumnya <=1600px. Resize agar:
     *  - Hemat storage server.
     *  - Halaman berita load lebih cepat.
     */
    private const MAX_WIDTH = 1600;

    /**
     * Quality JPEG/WebP (1-100). 85 adalah sweet spot:
     * file ~30-40% lebih kecil dari 100 dengan visual loss minimal.
     */
    private const IMAGE_QUALITY = 85;

    /**
     * Upload satu file gambar dan kembalikan URL publiknya.
     */
    public function upload(UploadInlineImageRequest $request): JsonResponse
    {
        $file = $request->file('file');

        // Sanitasi nama file: slug + random suffix untuk hindari collision & XSS.
        $original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::slug(Str::limit($original, 40, '')).'-'.Str::random(8).'.'.$extension;
        $path = 'berita/inline/'.$filename;

        // Untuk GIF: simpan apa adanya (resize bisa rusak animasi).
        if ($extension === 'gif') {
            $file->storeAs('berita/inline', $filename, 'public');
            $finalSizeKb = round($file->getSize() / 1024, 2);
        } else {
            // Resize + optimize dengan Intervention Image.
            $image = Image::read($file->getRealPath());

            // scaleDown() hanya resize jika lebar > MAX_WIDTH (tidak upscale gambar kecil).
            $image->scaleDown(width: self::MAX_WIDTH);

            $encoded = match ($extension) {
                'png' => $image->toPng(),
                'webp' => $image->toWebp(self::IMAGE_QUALITY),
                'jpg', 'jpeg' => $image->toJpeg(self::IMAGE_QUALITY),
                default => $image->toJpeg(self::IMAGE_QUALITY),
            };

            Storage::disk('public')->put($path, (string) $encoded);
            $finalSizeKb = round(strlen((string) $encoded) / 1024, 2);
        }

        $url = asset('storage/'.$path);

        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'path' => $path,
                'original_size_kb' => round($file->getSize() / 1024, 2),
                'final_size_kb' => $finalSizeKb,
                'extension' => $extension,
            ])
            ->log('Upload gambar inline berita: '.$filename);

        // Format response sesuai dokumentasi TinyMCE: { location: "<url>" }
        return response()->json(['location' => $url]);
    }
}
