import Alpine from 'alpinejs';
import AOS from 'aos';

import { cascadingDropdown } from './components/cascading-dropdown';
import {
    checkoutDropdown,
    checkoutSummary,
    paymentMethod,
    fetchOngkirAjax,
    initCheckoutProduk,
    initCheckoutLayanan,
} from './components/checkout';
import { forgotPasswordHandler, resetPasswordHandler } from './pages/auth';
import { cartHandler } from './pages/cart';
import { confirmClaim, closeModal } from './pages/reward';
import { initPembayaranPage, copyToClipboard } from './pages/pembayaran';

window.Alpine = Alpine;

window.cascadingDropdown = cascadingDropdown;
window.checkoutDropdown = checkoutDropdown;
window.checkoutSummary = function (initialSubtotal) {
    const el = document.getElementById('checkout-produk-config')
        ?? document.getElementById('checkout-layanan-config');
    let rewards = [];

    if (el?.dataset.config) {
        try {
            rewards = JSON.parse(el.dataset.config).availableRewards ?? [];
        } catch {
            rewards = [];
        }
    }

    return checkoutSummary(initialSubtotal, rewards);
};

window.updateTotal = function (productPrice, productWeight) {
    if (typeof window.__checkoutUpdateTotal === 'function') {
        window.__checkoutUpdateTotal(productPrice, productWeight);
    }
};
window.paymentMethod = paymentMethod;
window.fetchOngkirAjax = fetchOngkirAjax;
window.forgotPasswordHandler = forgotPasswordHandler;
window.resetPasswordHandler = resetPasswordHandler;
window.cartHandler = cartHandler;
window.confirmClaim = confirmClaim;
window.closeModal = closeModal;
window.copyToClipboard = copyToClipboard;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        duration: 800,
        once: true,
        offset: 100,
        disable: 'mobile',
    });

    initCheckoutProduk();
    initCheckoutLayanan();
    initPembayaranPage();
});
