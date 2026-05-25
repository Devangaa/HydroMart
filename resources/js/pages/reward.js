import { numberFormat } from '../utils/helpers';

const MODAL_FADE_MS = 300;

function getModalElements() {
    const modal = document.getElementById('confirmModal');
    const backdrop = modal?.querySelector('.modal-backdrop');
    const content = modal?.querySelector('.modal-content');

    return { modal, backdrop, content };
}

function showModalFade() {
    const { modal, backdrop, content } = getModalElements();

    if (! modal || ! backdrop || ! content) {
        return;
    }

    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        backdrop.classList.remove('opacity-0');
        backdrop.classList.add('opacity-100');
        content.classList.remove('opacity-0');
        content.classList.add('opacity-100');
    });
}

function hideModalFade(callback) {
    const { modal, backdrop, content } = getModalElements();

    if (! modal || ! backdrop || ! content) {
        callback?.();

        return;
    }

    backdrop.classList.remove('opacity-100');
    backdrop.classList.add('opacity-0');
    content.classList.remove('opacity-100');
    content.classList.add('opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
        callback?.();
    }, MODAL_FADE_MS);
}

export function confirmClaim(url, name, poin) {
    const { modal, content, backdrop } = getModalElements();
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

    if (content && backdrop) {
        content.classList.add('opacity-0');
        backdrop.classList.add('opacity-0');
    }

    showModalFade();
}

export function closeModal() {
    hideModalFade();
}

window.numberFormat = numberFormat;
