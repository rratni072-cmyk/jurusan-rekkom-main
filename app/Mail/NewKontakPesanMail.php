<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\KontakPesan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Email notifikasi ke admin jurusan saat ada pesan masuk baru dari form
 * Hubungi Kami publik.
 *
 * - Subject: "[Jurusan R&K] Pesan Baru: {subjek}" (prefix agar mudah di-filter
 *   di inbox admin).
 * - Reply-To di-set ke email pengirim — admin tinggal tekan Reply di client
 *   email-nya untuk balas langsung ke user.
 */
class NewKontakPesanMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public KontakPesan $pesan) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Jurusan R&K] Pesan Baru: '.$this->pesan->subjek,
            replyTo: [
                new Address($this->pesan->email, $this->pesan->nama),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.kontak-pesan-baru',
            with: [
                'pesan' => $this->pesan,
                'adminUrl' => route('admin.pesan.show', $this->pesan->id),
            ],
        );
    }

    /**
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
