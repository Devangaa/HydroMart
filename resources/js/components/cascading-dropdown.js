import { getAppConfig, getPageConfig } from '../utils/helpers';

export function createCascadingDropdown(config = {}) {
    const { basePath } = getAppConfig();

    return {
        provinces: [],
        cities: [],
        kecamatan: [],

        selectedProvince: config.selectedProvince ?? '',
        selectedCity: config.selectedCity ?? '',
        selectedKecamatan: config.selectedKecamatan ?? '',

        selectedProvinceName: '',
        selectedCityName: '',
        selectedKecamatanName: '',

        provinceSearch: '',
        citySearch: '',
        kecamatanSearch: '',

        loadingCities: false,
        loadingKecamatan: false,

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
        },

        selectCity(city) {
            this.selectedCity = city.id;
            this.selectedCityName = city.name;
            this.citySearch = '';
            this.selectedKecamatan = '';
            this.selectedKecamatanName = '';
            this.kecamatan = [];
            this.onCityChange();
        },

        selectKecamatan(kec) {
            this.selectedKecamatan = kec.id;
            this.selectedKecamatanName = kec.name;
            this.kecamatanSearch = '';
        },

        async init() {
            await this.fetchProvinces();

            if (this.selectedProvince) {
                await this.onProvinceChange();
                const prov = this.provinces.find((p) => p.id == this.selectedProvince);

                if (prov) {
                    this.selectedProvinceName = prov.name;
                }
            }
        },

        async fetchProvinces() {
            try {
                const response = await fetch(`${basePath}/provinces`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                this.provinces = await response.json();
            } catch (error) {
                console.error('Error fetching provinces:', error);
            }
        },

        async onProvinceChange() {
            if (! this.selectedProvince) {
                this.cities = [];
                this.kecamatan = [];
                this.selectedCity = '';
                this.selectedCityName = '';
                this.selectedKecamatan = '';
                this.selectedKecamatanName = '';

                return;
            }

            this.loadingCities = true;
            this.cities = [];
            this.kecamatan = [];

            try {
                const response = await fetch(`${basePath}/cities/${this.selectedProvince}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                this.cities = await response.json();

                if (this.selectedCity) {
                    const city = this.cities.find((c) => c.id == this.selectedCity);

                    if (city) {
                        this.selectedCityName = city.name;
                        await this.onCityChange();
                    }
                }
            } catch (error) {
                console.error('Error fetching cities:', error);
            } finally {
                this.loadingCities = false;
            }
        },

        async onCityChange() {
            if (! this.selectedCity) {
                this.kecamatan = [];
                this.selectedKecamatan = '';
                this.selectedKecamatanName = '';

                return;
            }

            this.loadingKecamatan = true;
            this.kecamatan = [];

            try {
                const response = await fetch(`${basePath}/kecamatan/${this.selectedCity}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                this.kecamatan = await response.json();

                if (this.selectedKecamatan) {
                    const kec = this.kecamatan.find((k) => k.id == this.selectedKecamatan);

                    if (kec) {
                        this.selectedKecamatanName = kec.name;
                    }
                }
            } catch (error) {
                console.error('Error fetching kecamatan:', error);
            } finally {
                this.loadingKecamatan = false;
            }
        },
    };
}

export function cascadingDropdown() {
    const config = getPageConfig('wilayah-config');

    return createCascadingDropdown({
        selectedProvince: config.provinceId ?? config.selectedProvince ?? '',
        selectedCity: config.cityId ?? config.selectedCity ?? '',
        selectedKecamatan: config.kecamatanId ?? config.selectedKecamatan ?? '',
    });
}
