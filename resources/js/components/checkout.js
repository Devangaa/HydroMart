import { getAppConfig, formatCurrency, getPageConfig } from '../utils/helpers';

export async function fetchOngkirAjax(kecamatanId, totalWeight, grandTotal, basePath, summary = null) {
    const { basePath: appBase } = getAppConfig();
    const path = basePath || appBase;

    if (summary) {
        summary.updateOngkir(0, 'Menghitung...');
    } else {
        const ongkirText = document.getElementById('ongkir-text');
        const totalText = document.getElementById('total');

        if (ongkirText) {
            ongkirText.textContent = 'Menghitung...';
            ongkirText.classList.remove('text-green-600');
            ongkirText.classList.add('text-gray-400');
        }
    }

    try {
        const response = await fetch(`${path}/cek-ongkir?kecamatan_id=${kecamatanId}&total_weight=${totalWeight}`);
        const data = await response.json();

        if (! data.success) {
            if (summary) {
                summary.updateOngkir(0, 'Gagal memuat');
            } else if (document.getElementById('ongkir-text')) {
                document.getElementById('ongkir-text').textContent = 'Gagal memuat';
            }

            return;
        }

        if (summary) {
            summary.updateOngkir(data.ongkir, data.ongkir === 0 ? 'Gratis' : data.formatted_ongkir);
        } else {
            const ongkirText = document.getElementById('ongkir-text');
            const totalText = document.getElementById('total');

            if (ongkirText) {
                if (data.ongkir === 0) {
                    ongkirText.textContent = 'Gratis';
                    ongkirText.classList.add('text-green-600');
                    ongkirText.classList.remove('text-gray-400', 'text-gray-900');
                } else {
                    ongkirText.textContent = data.formatted_ongkir;
                    ongkirText.classList.add('text-gray-900');
                    ongkirText.classList.remove('text-green-600', 'text-gray-400');
                }
            }

            if (totalText) {
                totalText.textContent = `Rp${formatCurrency(grandTotal + data.ongkir)}`;
            }
        }
    } catch (error) {
        console.error('Error fetching ongkir:', error);

        if (summary) {
            summary.updateOngkir(0, 'Gagal memuat');
        } else if (document.getElementById('ongkir-text')) {
            document.getElementById('ongkir-text').textContent = 'Gagal memuat';
        }
    }
}

export function checkoutSummary(initialSubtotal, availableRewards = []) {
    return {
        subtotal: initialSubtotal,
        ongkir: 0,
        ongkirText: 'Pilih Lokasi',
        rewardId: '',
        rewardDiscount: 0,
        rewardName: '',
        showRewardModal: false,
        availableRewards,

        get finalTotal() {
            return Math.max(0, this.subtotal - this.rewardDiscount + this.ongkir);
        },

        formatCurrency(num) {
            return formatCurrency(num);
        },

        selectReward(redemption) {
            this.rewardId = redemption.id;
            this.rewardDiscount = redemption.reward.diskon;
            this.rewardName = redemption.reward.nama_reward;
            this.showRewardModal = false;
        },

        removeReward() {
            this.rewardId = '';
            this.rewardDiscount = 0;
            this.rewardName = '';
        },

        updateOngkir(amount, formatted) {
            this.ongkir = amount;
            this.ongkirText = formatted;
        },

        updateSubtotal(newSubtotal) {
            this.subtotal = newSubtotal;

            if (this.rewardId) {
                const selected = this.availableRewards.find((r) => r.id == this.rewardId);

                if (selected && this.subtotal < selected.reward.minimal_pembelian) {
                    this.removeReward();
                }
            }
        },
    };
}

export function paymentMethod() {
    return {
        selectedMethod: 'bca',
        isCodDisabled: true,

        init() {
            this.updateCodAvailability();

            const observer = new MutationObserver(() => {
                this.updateCodAvailability();
            });

            const provinceInput = document.querySelector('input[name="province_id"]');
            const cityInput = document.querySelector('input[name="city_id"]');
            const kecamatanInput = document.querySelector('input[name="kecamatan_id"]');

            if (provinceInput) {
                observer.observe(provinceInput, { attributes: true });
            }

            if (cityInput) {
                observer.observe(cityInput, { attributes: true });
            }

            if (kecamatanInput) {
                observer.observe(kecamatanInput, { attributes: true });
            }
        },

        updateCodAvailability() {
            let provinceName = 'Jawa Timur';
            let cityName = 'Kabupaten Jember';
            let kecamatanName = '';

            const dropdownDiv = document.querySelector('[x-data*="checkoutDropdown"]');

            if (dropdownDiv?._x_dataStack?.[0]) {
                provinceName = dropdownDiv._x_dataStack[0].selectedProvinceName || provinceName;
                cityName = dropdownDiv._x_dataStack[0].selectedCityName || cityName;
                kecamatanName = dropdownDiv._x_dataStack[0].selectedKecamatanName || '';
            }

            if (! kecamatanName) {
                const kecamatanSelectors = document.querySelectorAll('[x-data*="open: false"]');

                for (const selector of kecamatanSelectors) {
                    if (selector._x_dataStack?.[0]?.selectedName) {
                        kecamatanName = selector._x_dataStack[0].selectedName;

                        break;
                    }
                }
            }

            const isJawatimur = provinceName.toLowerCase().includes('jawa timur');
            const isJember = cityName.toLowerCase().includes('jember');
            const allowedKecamatan = ['Sumbersari', 'Patrang', 'Kaliwates'];
            const isAllowedKecamatan = allowedKecamatan.some((k) =>
                kecamatanName.toLowerCase().includes(k.toLowerCase()),
            );

            const isLayananCheckout = !! document.getElementById('checkout-layanan-config');
            this.isCodDisabled = isLayananCheckout
                ? !(isJawatimur && isJember && isAllowedKecamatan)
                : !(isJawatimur && isJember && isAllowedKecamatan && kecamatanName);

            if (this.isCodDisabled && this.selectedMethod === 'cod') {
                this.selectedMethod = 'bca';
            }
        },
    };
}

export function checkoutDropdown(isSayuran, initialProvinces, basePath = '', restrictedKecamatans = [], lockedProvince = null, lockedCity = null) {
    const config = getPageConfig('checkout-dropdown-config') || window.__checkoutDropdownConfig || {};

    return {
        isSayuran: isSayuran ?? config.isSayuran ?? false,
        provinces: initialProvinces ?? config.initialProvinces ?? [],
        cities: [],
        kecamatan: restrictedKecamatans?.length ? restrictedKecamatans : (config.restrictedKecamatans ?? []),
        selectedProvince: '',
        selectedCity: '',
        selectedKecamatan: '',
        selectedProvinceName: '',
        selectedCityName: '',
        selectedKecamatanName: '',
        provinceSearch: '',
        citySearch: '',
        kecamatanSearch: '',
        loadingCities: false,
        loadingKecamatan: false,
        isProvinceDisabled: isSayuran ?? config.isSayuran ?? false,
        isCityDisabled: isSayuran ?? config.isSayuran ?? false,

        get filteredProvinces() {
            if (! this.provinceSearch) {
                return this.provinces;
            }

            const q = this.provinceSearch.toLowerCase();

            return this.provinces.filter((p) => p.name.toLowerCase().includes(q));
        },

        get filteredCities() {
            if (! this.citySearch) {
                return this.cities;
            }

            const q = this.citySearch.toLowerCase();

            return this.cities.filter((c) => c.name.toLowerCase().includes(q));
        },

        get filteredKecamatan() {
            if (! this.kecamatanSearch) {
                return this.kecamatan;
            }

            const q = this.kecamatanSearch.toLowerCase();

            return this.kecamatan.filter((k) => k.name.toLowerCase().includes(q));
        },

        selectProvince(prov) {
            this.selectedProvince = prov.id;
            this.selectedProvinceName = prov.name;
            this.provinceSearch = '';
            this.selectedCity = '';
            this.selectedCityName = '';
            this.selectedKecamatan = '';
            this.selectedKecamatanName = '';
            this.cities = [];
            this.kecamatan = [];
            this.onProvinceChange();
            this.notifyLocationChange();
        },

        selectCity(city) {
            this.selectedCity = city.id;
            this.selectedCityName = city.name;
            this.citySearch = '';
            this.selectedKecamatan = '';
            this.selectedKecamatanName = '';
            this.kecamatan = [];
            this.onCityChange();
            this.notifyLocationChange();
        },

        selectKecamatan(kec) {
            this.selectedKecamatan = kec.id;
            this.selectedKecamatanName = kec.name;
            this.kecamatanSearch = '';
            this.notifyLocationChange();

            const cfg = getPageConfig('checkout-produk-config') || getPageConfig('checkout-layanan-config') || {};

            if (cfg.onKecamatanSelect) {
                cfg.onKecamatanSelect(kec.id);
            }
        },

        notifyLocationChange() {
            setTimeout(() => {
                const paymentDiv = document.querySelector('[x-data*="paymentMethod"]');

                if (paymentDiv?._x_dataStack?.[0]) {
                    paymentDiv._x_dataStack[0].updateCodAvailability();
                }
            }, 50);
        },

        async init() {
            const province = lockedProvince ?? config.lockedProvince;
            const city = lockedCity ?? config.lockedCity;

            if (this.isSayuran && province && city) {
                this.selectedProvince = province.id;
                this.selectedProvinceName = province.name;
                this.selectedCity = city.id;
                this.selectedCityName = city.name;
            }

            if (this.isSayuran && ! province) {
                const jawatimur = this.provinces.find((p) => p.name === 'Jawa Timur');

                if (jawatimur) {
                    this.selectedProvince = jawatimur.id;
                    this.selectedProvinceName = jawatimur.name;
                    await this.onProvinceChange();

                    const jember = this.cities.find((c) => c.name.toLowerCase().includes('kabupaten jember'));

                    if (jember) {
                        this.selectedCity = jember.id;
                        this.selectedCityName = jember.name;
                        await this.onCityChange();
                    }
                }
            }

            this.notifyLocationChange();
        },

        async onProvinceChange() {
            if (! this.selectedProvince || (this.isSayuran && config.lockedProvince)) {
                return;
            }

            this.loadingCities = true;

            try {
                const apiPath = basePath
                    ? `${basePath}/api/cities-transaction/${this.selectedProvince}`
                    : `/api/cities-transaction/${this.selectedProvince}`;
                const response = await fetch(apiPath);
                this.cities = await response.json();
            } catch (error) {
                console.error('Error fetching cities:', error);
            } finally {
                this.loadingCities = false;
            }
        },

        async onCityChange() {
            if (! this.selectedCity || (this.isSayuran && config.lockedCity)) {
                return;
            }

            this.loadingKecamatan = true;

            try {
                const cfg = getPageConfig('checkout-layanan-config');
                const districtsPath = cfg.useDistrictsApi
                    ? `${basePath}/api/districts/${this.selectedCity}`
                    : `${basePath}/api/districts-transaction/${this.selectedCity}`;
                const response = await fetch(districtsPath);
                let kecamatan = await response.json();

                if (this.isSayuran) {
                    const allowedKecamatan = ['Sumbersari', 'Patrang', 'Kaliwates'];
                    kecamatan = kecamatan.filter((k) => allowedKecamatan.includes(k.name));
                }

                this.kecamatan = kecamatan;
            } catch (error) {
                console.error('Error fetching kecamatan:', error);
            } finally {
                this.loadingKecamatan = false;
            }
        },
    };
}

export function initCheckoutProduk() {
    const cfg = getPageConfig('checkout-produk-config');

    if (! cfg) {
        return;
    }

    window.getCurrentWeight = function () {
        if (cfg.mode === 'buy_now' && cfg.productWeight) {
            const qtyInput = document.getElementById('item-qty');
            const qty = parseInt(qtyInput?.value) || 1;

            return cfg.productWeight * qty;
        }

        return cfg.totalWeight ?? 0;
    };

    window.getCurrentSubtotal = function () {
        if (cfg.mode === 'buy_now' && cfg.productPrice) {
            const qtyInput = document.getElementById('item-qty');
            const qty = parseInt(qtyInput?.value) || 1;

            return cfg.productPrice * qty;
        }

        return cfg.grandTotal ?? 0;
    };

    window.updateTotal = function (productPrice, productWeight) {
        if (cfg.mode !== 'buy_now') {
            return;
        }

        const qtyInput = document.getElementById('item-qty');

        if (! qtyInput) {
            return;
        }

        const qty = parseInt(qtyInput.value) || 1;
        const subtotal = productPrice * qty;
        const totalWeight = (productWeight || 0) * qty;
        const summaryEl = document.querySelector('[x-data*="checkoutSummary"]');

        if (! summaryEl?._x_dataStack?.[0]) {
            return;
        }

        const summary = summaryEl._x_dataStack[0];
        summary.updateSubtotal(subtotal);

        const kecamatanInput = document.querySelector('input[name="kecamatan_id"]');
        const kecamatanId = kecamatanInput ? kecamatanInput.value : null;

        if (kecamatanId) {
            fetchOngkirAjax(kecamatanId, totalWeight, subtotal, cfg.basePath, summary);
        }
    };

    cfg.onKecamatanSelect = (kecamatanId) => {
        const summaryEl = document.querySelector('[x-data*="checkoutSummary"]');

        fetchOngkirAjax(
            kecamatanId,
            window.getCurrentWeight(),
            window.getCurrentSubtotal(),
            cfg.basePath,
            summaryEl?._x_dataStack?.[0] ?? null,
        );
    };

    if (cfg.mode === 'buy_now' && cfg.productPrice) {
        document.addEventListener('DOMContentLoaded', () => {
            window.updateTotal(cfg.productPrice, cfg.productWeight);
        });
    }
}

export function initCheckoutLayanan() {
    const cfg = getPageConfig('checkout-layanan-config');

    if (! cfg) {
        return;
    }

    cfg.onKecamatanSelect = (kecamatanId) => {
        fetchOngkirAjax(kecamatanId, cfg.totalWeight, cfg.grandTotal, cfg.basePath);
    };
}
