<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Kode OTP yang akan dikirim ke email pengguna.
     */
    public string $otp;

    /**
     * Membuat instance email OTP baru.
     */
    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }

    /**
     * Mendefinisikan envelope email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode Verifikasi Anda',
        );
    }

    /**
     * Mendefinisikan konten email.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.otp',
        );
    }

    /**
     * Menentukan lampiran email (jika ada).
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
