<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\StoreKontakPesanRequest;
use App\Mail\NewKontakPesanMail;
use App\Models\Kontak;
use App\Models\KontakPesan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

/**
 * Controller untuk halaman kontak publik.
 *
 * Menampilkan informasi kontak dan menerima pesan dari pengunjung.
 * Validasi & honeypot anti-bot di {@see StoreKontakPesanRequest}.
 */
class KontakController extends Controller
{
    /**
     * Tampilkan halaman kontak.
     */
    public function index(): View
    {
        $kontak = Kontak::first();

        return view('frontend.kontak', compact('kontak'));
    }

    /**
     * Simpan pesan dari pengunjung + kirim email notifikasi ke admin.
     *
     * Jika honeypot terisi, diam-diam tampilkan success ke user
     * (untuk tidak memberi sinyal ke bot bahwa kita mendeteksinya)
     * tapi pesan TIDAK disimpan ke database.
     */
    public function kirimPesan(StoreKontakPesanRequest $request): RedirectResponse
    {
        if ($request->isLikelyBot()) {
            return redirect()
                ->route('kontak')
                ->with('success', 'Pesan Anda berhasil dikirim. Terima kasih!');
        }

        $pesan = KontakPesan::create($request->safe()->only([
            'nama', 'email', 'subjek', 'pesan',
        ]));

        // Kirim email notifikasi ke admin. Wrap try/catch agar error SMTP
        // tidak break UX user — pesan tetap tersimpan di DB dan admin bisa
        // lihat via panel, tapi notifikasi email kali ini gagal. Error
        // di-log untuk follow-up oleh dev.
        try {
            $recipient = $this->resolveAdminRecipient();
            if ($recipient !== null) {
                Mail::to($recipient)->send(new NewKontakPesanMail($pesan));
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal kirim email notifikasi pesan kontak', [
                'pesan_id' => $pesan->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('kontak')
            ->with('success', 'Pesan Anda berhasil dikirim. Terima kasih!');
    }

    /**
     * Tentukan email tujuan notifikasi admin.
     *
     * Prioritas:
     *   1. Email di tabel `kontaks` (editable admin via /admin/kontak/edit)
     *   2. Email user admin pertama di DB
     *   3. null (tidak kirim email — fallback ke notif via panel saja)
     */
    private function resolveAdminRecipient(): ?string
    {
        $email = Kontak::query()->value('email');
        if ($email !== null && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        $adminEmail = User::query()->value('email');
        if ($adminEmail !== null && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
            return $adminEmail;
        }

        return null;
    }
}
