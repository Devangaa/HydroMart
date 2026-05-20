import { getCsrfToken, getPageConfig } from '../utils/helpers';

export function forgotPasswordHandler() {
    return {
        step: 1,
        email: '',
        otp: '',
        loading: false,
        error: '',

        async sendOtp() {
            if (! this.email) {
                this.error = 'Email wajib diisi!';

                return;
            }

            this.loading = true;
            this.error = '';

            try {
                const response = await fetch('/forgot-password/send-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                    },
                    body: JSON.stringify({ email: this.email }),
                });

                const data = await response.json();

                if (response.ok) {
                    this.step = 2;
                } else {
                    this.error = data.message || 'Gagal mengirim OTP';
                }
            } catch {
                this.error = 'Koneksi bermasalah, silakan coba lagi.';
            }

            this.loading = false;
        },

        async verifyOtp() {
            if (! this.otp) {
                this.error = 'Kode OTP wajib diisi!';

                return;
            }

            this.loading = true;
            this.error = '';

            const config = getPageConfig('forgot-password-config');

            try {
                const response = await fetch('/forgot-password/verify-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                    },
                    body: JSON.stringify({ email: this.email, otp: this.otp }),
                });

                const data = await response.json();

                if (response.ok) {
                    window.location.href = config.resetUrl || '/reset-password';
                } else {
                    this.error = data.message || 'Kode OTP salah.';
                }
            } catch {
                this.error = 'Terjadi kesalahan saat verifikasi.';
            }

            this.loading = false;
        },
    };
}

export function resetPasswordHandler() {
    const config = getPageConfig('reset-password-config');

    return {
        email: config.email || '',
        password: '',
        password_confirmation: '',
        loading: false,
        error: '',
        showSuccessModal: false,

        async submitReset() {
            if (this.password.length < 8) {
                this.error = 'Password minimal 8 karakter!';

                return;
            }

            if (this.password !== this.password_confirmation) {
                this.error = 'Konfirmasi password tidak cocok!';

                return;
            }

            this.loading = true;
            this.error = '';

            try {
                const response = await fetch('/reset-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'ngrok-skip-browser-warning': 'true',
                    },
                    body: JSON.stringify({
                        email: this.email,
                        password: this.password,
                        password_confirmation: this.password_confirmation,
                    }),
                });

                const data = await response.json();

                if (response.ok) {
                    this.showSuccessModal = true;
                } else {
                    this.error = data.message || 'Gagal memperbarui password.';
                }
            } catch {
                this.error = 'Terjadi kesalahan sistem. Coba lagi nanti.';
            }

            this.loading = false;
        },

        goToLogin() {
            window.location.href = config.loginUrl || '/login';
        },
    };
}
