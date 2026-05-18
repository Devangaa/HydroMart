<x-mail::message>
# Kode Verifikasi Anda

Halo,

Anda menerima email ini karena kami menerima permintaan pengaturan ulang kata sandi atau verifikasi untuk akun Anda.

Berikut adalah kode OTP Anda:

<x-mail::panel>
<div style="text-align: center; font-size: 32px; font-weight: bold; letter-spacing: 5px;">
{{ $otp }}
</div>
</x-mail::panel>

Kode ini hanya berlaku selama **10 menit**. Jangan berikan kode ini kepada siapapun demi keamanan akun Anda.

Jika Anda tidak merasa melakukan permintaan ini, Anda dapat mengabaikan email ini.

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
