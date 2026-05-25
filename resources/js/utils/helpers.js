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

    if (el._parsedConfig) {
        return el._parsedConfig;
    }

    if (el.dataset.config) {
        try {
            el._parsedConfig = JSON.parse(el.dataset.config);
            return el._parsedConfig;
        } catch {
            return {};
        }
    }

    el._parsedConfig = { ...el.dataset };
    return el._parsedConfig;
}

export function formatCurrency(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

export function numberFormat(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}
