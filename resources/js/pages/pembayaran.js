import { getPageConfig } from '../utils/helpers';

export function initPembayaranPage() {
    const config = getPageConfig('pembayaran-config');

    if (! config.expiryTime) {
        return;
    }

    const expiryTime = new Date(config.expiryTime).getTime();

    const countdownInterval = setInterval(() => {
        const now = new Date().getTime();
        const distance = expiryTime - now;
        const countdownEl = document.getElementById('countdown');

        if (! countdownEl) {
            clearInterval(countdownInterval);

            return;
        }

        if (distance < 0) {
            clearInterval(countdownInterval);
            countdownEl.innerHTML = 'EXPIRED';

            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        countdownEl.innerHTML =
            (hours < 10 ? '0' + hours : hours) +
            ' : ' +
            (minutes < 10 ? '0' + minutes : minutes) +
            ' : ' +
            (seconds < 10 ? '0' + seconds : seconds);
    }, 1000);

    if (config.statusCheckUrl) {
        const statusCheckInterval = setInterval(() => {
            fetch(config.statusCheckUrl)
                .then((response) => response.json())
                .then((data) => {
                    if (data.is_processed) {
                        clearInterval(statusCheckInterval);
                        window.location.href = data.redirect_url;
                    }
                })
                .catch((error) => console.error('Error checking status:', error));
        }, 5000);
    }
}

export function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Kode berhasil disalin!');
    });
}
