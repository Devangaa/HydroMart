export function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
}

export function getAppConfig() {
    const el = document.getElementById('app-config');

    if (! el) {
        return { basePath: '' };
    }

    return {
        basePath: el.dataset.basePath ?? '',
    };
}

export function getPageConfig(id) {
    const el = document.getElementById(id);

    if (! el) {
        return {};
    }

    if (el.dataset.config) {
        try {
            return JSON.parse(el.dataset.config);
        } catch {
            return {};
        }
    }

    return { ...el.dataset };
}

export function formatCurrency(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

export function numberFormat(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}
