import { numberFormat } from '../utils/helpers';

export function confirmClaim(url, name, poin) {
    const modal = document.getElementById('confirmModal');
    const content = modal?.querySelector('.modal-content');
    const form = document.getElementById('claimForm');

    if (! modal || ! form) {
        return;
    }

    const modalReward = document.getElementById('modalReward');

    if (modalReward && name) {
        modalReward.innerText = name;
    }

    document.getElementById('modalPoin').innerText = numberFormat(poin);
    form.action = url;

    modal.classList.remove('hidden');
    setTimeout(() => {
        content?.classList.remove('scale-95', 'opacity-0');
        content?.classList.add('scale-100', 'opacity-100');
    }, 10);
}

export function closeModal() {
    const modal = document.getElementById('confirmModal');
    const content = modal?.querySelector('.modal-content');

    if (! modal || ! content) {
        return;
    }

    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

window.numberFormat = numberFormat;
